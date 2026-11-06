<?php

class SYS_blocks
  {
  /*
   * Ïåðåìåííûå
  */



  /*
   * Ôóíêöèè
  */

  /*
   * Âîçâðàùàåò ñïèñîê áëîêîâ â ñèñòåìå
  */
  function get_list()
    {
    //Äåëàåì âûáîðêó
    $blocks_table = sysDBGetTable('blocks');
    $blocks_column =  sysDBGetColumns($blocks_table);
    $blocks_list = sysDbSelect ($blocks_table, $blocks_column,
        "WHERE $blocks_column[block_active] GROUP BY $blocks_column[block_name] ORDER BY $blocks_column[block_position], $blocks_column[weight]", true);

    return $blocks_list;
    }

  /*
   * Èíôîðìàöèÿ î áëîêå
  */
  function get_info($param)
    {
    if (is_numeric($param))
      {
      $blocks_table = sysDBGetTable('blocks');
      $blocks_column = sysDBGetColumns($blocks_table);
      $info = sysDbSelect ($blocks_table, $blocks_column, "WHERE $blocks_column[id]='$param'");
      $block_displayname = $info['block_displayname'];
      $blockname = $info['block_name'];
      }
      else
        {
        $blockname = $param;
        };

    if (file_exists ("blocks/$blockname/info.php"))
      {
      // Found
      include("blocks/$blockname/info.php");
      $info['block_name'] = $blockname;
      if (is_numeric($param)) //Åñëè èíôà èç áàçû, òî íå ïåðåçàïèñûâàåì èìÿ
        {
        $info['block_displayname'] = $block_displayname;
        }
      }

    return $info;
    }

  /*
   * Äîáàâëåíèå áëîêà
  */
  function add($name, $param = null)
    {
    $blockinfo = $this->get_info($name);
    $blockinfo['block_pattern'] = '.*';
    $blockinfo['block_lang'] = sysModGetVar ('SYS_config','lang');
    $blockinfo['block_position'] = 'e'; //áëîê ôèêñèðîâàííûé (íå ïîêàçûâàåòñÿ) ïî óìîë÷àíèþ

    // Çàãðóæàåì ôàéë ñ ôóíêöèåé èíèöèàëèçàöèè
    $initfile = 'blocks/'.$name.'/init.php';
    if (file_exists ($initfile))
      {
      include_once("$initfile");
      }
      else
        {
        sysException ('SYS_blocks->add', BLOCK_FILE_NOT_EXIST);
        };

    $func = $name . '_add';
    if (function_exists($func))
      {
      if ($func($blockinfo) != true)
        {
        sysException ('SYS_blocks->add', BLOCK_FUNCTION_NOT_EXIST);
        }
      }

if ($name=='advert_block') {$blockinfo['block_position']='a';}	

    //Ïîëó÷èëè ñïèñîê ñòîëáöîâ ìîäóëÿ
    $blocks_table = sysDBGetTable('blocks');
    $blocks_column =  sysDBGetColumns($blocks_table);

    //Íàõîäèì è çàäàåì ñàìûé áîëüøîé âåñ
    $MaxWeight = sysDbMaxWeight ($blocks_table, "WHERE $blocks_column[block_position]='$blockinfo[block_position]'");
    $MaxWeight++;
    $blockinfo [weight]=$MaxWeight;
    unset ($blockinfo['block_content']);
    $insertId = (int) sysDbInsert ($blocks_table, $blockinfo);
    if ($insertId <= 0 && function_exists('mysql_insert_id'))
      {
      $insertId = (int) mysql_insert_id();
      }
    if ($insertId > 0)
      {
      $insertedBlock = sysDbSelect($blocks_table, $blocks_column,
          "WHERE $blocks_column[id]='".$insertId."'");
      if (!empty($insertedBlock['id']) && $insertedBlock['block_name'] == $blockinfo['block_name'])
        {
        return $insertId;
        }
      }

    // Fallback for environments where mysql_insert_id() cannot resolve
    // the active connection correctly (for example via compatibility layers).
    $row = sysDbSelect($blocks_table, $blocks_column,
        "WHERE $blocks_column[block_name]='".$blockinfo['block_name']."' ORDER BY $blocks_column[id] DESC");
    if (!empty($row['id']))
      {
      return (int) $row['id'];
      }

    sysException ('SYS_blocks->add', DATABASE_ERROR, 'Cannot detect inserted block id');
    return false;
    }

  /*
   * Óäàëåíèå áëîêà
  */
  function delete($id)
    {
    //Ïîëó÷èëè ñïèñîê ñòîëáöîâ
    $blocks_table = sysDBGetTable('blocks');
    $blocks_column =  sysDBGetColumns($blocks_table);
    //Ïîëó÷èëè èíôîðìàöèþ î áëîêå
    $blockinfo = sysDbSelect($blocks_table, $blocks_column, "WHERE $blocks_column[id]='$id'");

    // Çàãðóæàåì ôàéë ñ ôóíêöèåé èíèöèàëèçàöèè
    $initfile = 'blocks/'.$blockinfo['block_name'].'/init.php';
    if (file_exists ($initfile))
      {
      include_once("$initfile");
      }
      else
        {
        sysException ('SYS_blocks->delete', BLOCK_FILE_NOT_EXIST);
        };

    //Ïåðåäàåì óïðàâëåíèå áëîêó
    $func = $blockinfo['block_name'] . '_delete';
    if (function_exists($func))
      {
      if ($func($blockinfo) != true)
        {
        sysException ('SYS_blocks->delete', BLOCK_FUNCTION_NOT_EXIST);
        }
      }

    //Ñîáñòâåííî óäàëåíèå
    sysDbDelete ($blocks_table, "WHERE $blocks_column[id]='$id'");

    //Óäàëÿåì âåñ èç òàáëèöû âåñîâ
    sysExtLibLoad();
    sysDbWeightDelete ($blocks_table, $blockinfo['weight'],
          "WHERE $blocks_column[block_position] = '$blockinfo[block_position]'");

    //Óäàëÿåì êåø ñòðàíèö áëîêà
    $sysTpl = new sysTpl;
    $sysTpl->clear_cache(null, $blockinfo['block_name']);
    return true;
    }

  /*
   * Àêòèâàöèÿ áëîêà
  */
  function activate($id)
    {
    //Ïîëó÷èëè ñïèñîê ñòîëáöîâ áëîêà
    $blocks_table = sysDBGetTable('blocks');
    $blocks_column =  sysDBGetColumns($blocks_table);
    // Get block information
    $blockinfo = sysDbSelect($blocks_table, $blocks_column, "WHERE $blocks_column[id]='$id'");
    if (empty($blockinfo['block_name']))
      {
      sysException ('SYS_blocks->activate', ID_NOT_EXIST, "Block with id=$id does not exist");
      }
    // Çàãðóæàåì ôàéë ñ ôóíêöèåé èíèöèàëèçàöèè
    $initfile='blocks/'.$blockinfo['block_name'].'/init.php';
    if (file_exists ($initfile))
      {
      include_once("$initfile");
      }
      else
        {
        sysException ('SYS_blocks->activate', BLOCK_FILE_NOT_EXIST);
        };

    //Ïåðåäàåì óïðàâëåíèå áëîêó
    $func = $blockinfo['block_name'] . '_activate';
    if (function_exists($func))
      {
      if ($func($blockinfo) != true)
        {
        sysException ('SYS_blocks->activate', BLOCK_FUNCTION_NOT_EXIST);
        }
      }
    //
if ($blockinfo['block_name']=='advert_block') {$blockinfo['block_position']='a';}
    $blockinfo['block_active'] = true;
    unset ($blockinfo['block_content']);
    sysDbUpdate ($blocks_table, $blockinfo, "WHERE $blocks_column[id]='$id'");
    sysVarDelCached('blocks_list', 'dbblockslist');
    //×èñòèì êåø øàáëîíîâ, ÿ òàê è íåïîíÿë ãäå îí çàáèâàåòüñÿ
    //Ñëó÷èëîñü ïîñëå òîãî êàê â varsetcachet ïîñòàâèë unset->false
    sysVarDelCached('tpls_list', $blockinfo['block_name']);
    return true;
    }

  /*
   * Äåàêòèâàöèÿ áëîêà
  */
  function deactivate($id)
    {
    //Ïîëó÷èëè ñïèñîê ñòîëáöîâ áëîêà
    $blocks_table = sysDBGetTable('blocks');
    $blocks_column =  sysDBGetColumns($blocks_table);
    // Get block information
    $blockinfo = sysDbSelect($blocks_table, $blocks_column, "WHERE $blocks_column[id]='$id'");
    if (empty($blockinfo['block_name']))
      {
      sysException ('SYS_blocks->deactivate', ID_NOT_EXIST, "Block with id=$id does not exist");
      }
    // Çàãðóæàåì ôàéë ñ ôóíêöèåé èíèöèàëèçàöèè
    $initfile='blocks/'.$blockinfo['block_name'].'/init.php';
    if (file_exists ($initfile))
      {
      include_once("$initfile");
      }
      else
        {
        sysException ('SYS_blocks->deactivate', BLOCK_FILE_NOT_EXIST);
        };

    //Ïåðåäàåì óïðàâëåíèå áëîêó
    $func = $blockinfo['block_name'] . '_deactivate';
    if (function_exists($func))
      {
      if ($func($blockinfo) != true)
        {
        sysException ('SYS_blocks->deactivate', BLOCK_FUNCTION_NOT_EXIST);
        }
      }
    //
    $blockinfo['block_active'] = false;
    unset ($blockinfo['block_content']);
    sysDbUpdate ($blocks_table, $blockinfo, "WHERE $blocks_column[id]='$id'");
    sysVarDelCached('blocks_list', 'dbblockslist');
    //×èñòèì êåø øàáëîíîâ, ÿ òàê è íåïîíÿë ãäå îí çàáèâàåòüñÿ
    //Ñëó÷èëîñü ïîñëå òîãî êàê â varsetcachet ïîñòàâèë unset->false
    sysVarDelCached('tpls_list', $blockinfo['block_name']);
    return true;
    }

  /*
   * Îáíîâëåíèå èíôîðìàöèè î áëîêå
  */
  function update($param)
    {
    $blocks_table = sysDBGetTable('blocks');
    $blocks_column = sysDBGetColumns($blocks_table);

    //Çíà÷èò åñëè ïîëîæåíèå èçìåíèëîñü:
    //Âûãðåáëè ñòàðûå äàííûå
    $blockinfo = sysDbSelect ($blocks_table, $blocks_column, "WHERE $blocks_column[id]='$param[id]'");
    if ($param['block_position'] != $blockinfo['block_position'])
      {
      // óäàëÿåì ýëèìåíò èç òàáëèöû âåñîâ
      sysExtLibLoad();
      sysDbWeightDelete ($blocks_table, $blockinfo['weight'],
                  "WHERE $blocks_column[block_position] = '$blockinfo[block_position]'");
      //Ãåíåðèðóåì íîâûé
      $MaxWeight = sysDbMaxWeight ($blocks_table,
                  "WHERE $blocks_column[block_position] = '$param[block_position]'");
      $MaxWeight++;
      $param['weight'] = $MaxWeight;
      };

    //Âíåñåíèå èçìåíåíèé â áàçó
    unset ($param['block_content']);
    sysDbUpdate ($blocks_table, $param,
                  "WHERE $blocks_column[id] = $param[id]");

    //Ïðåäàåì óïðàâëåíèå ìîäóëþ äëÿ çàïðîñà ïàðàìåòðîâ ñïåöûôè÷åñêèõ äëÿ ìîäóëÿ
    $initfile = 'blocks/'.$blockinfo['block_name'].'/init.php';
    if (file_exists ($initfile))
      {
      include_once($initfile);
      };

    //Ïåðåäàåì óïðàâëåíèå áëîêó
    $func = $blockinfo['block_name'] . '_update';
    if (function_exists($func))
      {
      $func($blockinfo);
      }

    //happy end
    sysVarDelCached('blocks_list', 'dbblockslist');
    return true;
    }

  /*
   * Ïåðåäâèæêà ýëèìåíòà â âåðõ
  */
  function moveup($id)
    {
    $block_info =& $this->get_info($id);

    $blocks_table = sysDBGetTable('blocks');
    $blocks_column = sysDBGetColumns($blocks_table);
    sysExtLibLoad();
    sysDbWeightMoveUp ($blocks_table, $block_info['weight'], "WHERE $blocks_column[block_position]='$block_info[block_position]'");

    sysVarDelCached('blocks_list', 'dbblockslist');
    return true;
    }

  /*
   * Ïåðåäâèæêà ýëèìåíòà â íèç
  */
  function movedown($id)
    {
    $block_info =& $this->get_info($id);

    $blocks_table = sysDBGetTable('blocks');
    $blocks_column = sysDBGetColumns($blocks_table);
    sysExtLibLoad();
    sysDbWeightMoveDown ($blocks_table, $block_info['weight'], "WHERE $blocks_column[block_position]='$block_info[block_position]'");

    sysVarDelCached('blocks_list', 'dbblockslist');
    return true;
    }

  }

?>
