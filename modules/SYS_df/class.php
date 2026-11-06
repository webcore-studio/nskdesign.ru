<?php
class SYS_df
  {
  /*
   * Ïåðåìåííûå
  */



  /*
   * Ôóíêöèè
  */

  /*
   * Âîçâðàøàåò ñïèñîê òàáëèö â ñèñòåìå
   */
  function get_tables_list()
    {
    $tables_table = sysDBGetTable('tables');
    $tables_column =  sysDBGetColumns($tables_table);
    $sql = "SELECT DISTINCT `table_name` FROM $tables_table ORDER BY `table_name`";
    $sql_result = mysql_query($sql);
    if (mysql_errno()!=0) sysException ('SYS_df->get_tables_list', DATABASE_ERROR, mysql_error()."<br> $sql");

    $tables_list=array();
    while(list($table_name) = mysql_fetch_row($sql_result))
      {
      $tables_list[] = $table_name;
      };
    mysql_free_result($sql_result);
    return ($tables_list);
    }

  /*
   * Âîçâðàøàåò ñïèñîê ïîëåé â òàáëèöå
   */
  function get_fields_list($table_name)
    {
    $tables_table = sysDBGetTable('tables');
    $tables_column = sysDBGetColumns($tables_table);
    $fields_list = sysDbSelect ($tables_table, $tables_column, "WHERE $tables_column[table_name] = '$table_name' ORDER BY id", true);
    foreach ($fields_list as $key=>$value)
      {
      if (($value['table_type']=='int') and (empty ($value['table_size'])))
        {
        $value['table_size']=11;
        };
      $fields_list[$key]=$value;
      }

    return ($fields_list);
    }

  /*
   * Âîçâðàøàåò èíôîðìàöèþ î ïîëå
   */
  function get_info($id)
    {
    $systable = sysDBGetTable('tables');
    $tables_column =  sysDBGetColumns($systable);
    $dbdata = sysDbSelect ($systable, $tables_column, "WHERE ($tables_column[id]='$id')");
    if (empty ($dbdata)) sysException ('SYS_df->get_info', BAD_PARAM);
    //Ïðåîáðàçîûâàåì ê ÷åëîâå÷åñêîìó èìåííè
    $reult = array ('id' =>$dbdata['id'],
                    'table' =>$dbdata['table_name'],
                    'field' =>$dbdata['table_field'],
                    'type' =>$dbdata['table_type'],
                    'size' =>$dbdata['table_size'],
                    'default' =>$dbdata['table_default'],
                    'null' =>$dbdata['table_null'],
                    'unsigned' =>$dbdata['table_unsigned'],
                    'increment' =>$dbdata['table_increment'],
                    'primary_key' =>$dbdata['table_primary_key'],
                    'index' =>$dbdata['table_index'],
                    'unique' =>$dbdata['table_unique'],
                    'fulltext' =>$dbdata['table_fulltext'],
                    'candelete' =>$dbdata['table_candelete'],
                    'canmodify' =>$dbdata['table_canmodify']
                    );
    return ($reult);
    }


  /*
   * Ïðîöåäóðà ñîçäàíèÿ òàáëèöû â ñèñòåìå / òàêæå ñàìà âûñòðàèâàò columns êàòîðûå õðàíèì íà äèñêå
   */
  function CreateTable($tableName, $fields)
    {
    global $dbconn;
    if (!($dbconn instanceof mysqli))
      {
      sysException('SYS_df->CreateTable', DATABASE_ERROR, 'Соединение CMS с базой данных не инициализировано');
      return false;
      }
    //çàíîñèì columns èíôîðìàöèþ â áàçó
    $metaTable = sysDBGetTable('tables');

    //Ñîçäàåì ñàìó òàáëèöó
    $query = "CREATE TABLE `$tableName` (";
    $keys = '';

    $ffields = $fields;
    $fulltext_index = '';
    foreach ($ffields as $name=>$params)
      {
      if (!isset ($params['type']))
        {
        continue;
        };

      $query.= "`$name` ".strtoupper($params['type']);

      // (size)
      if (isset ($params['size']))
        {
        $query.= "($params[size])";
        };

      // DEFAULT 'as'
      // MySQL strict mode: BLOB/TEXT/JSON/GEOMETRY columns can't have a DEFAULT value
      if (isset ($params['default']) && empty($params['increment']) && $params['type']!='datetime'
          && !in_array(strtolower($params['type']), array('tinytext', 'text', 'mediumtext', 'longtext', 'tinyblob', 'blob', 'mediumblob', 'longblob', 'json', 'geometry')))
        {
        $query.= " DEFAULT '".$params['default']."'";
        };

      //NOT NULL
      if (isset ($params['null']))
        {
        if (!$params['null'])
          {
          $query.= ' NOT NULL';
          }
        };

      // AUTO_INCREMENT
      if (!empty($params['increment']))
        {
        $query.= " AUTO_INCREMENT";
        };
      //ÇÄÅÑÜ ÏÎÐÀ Í×ÀÒÜ ÄÓÌÀÒÜ Î ÊËÞ×ÀÕ

      // PRIMARY KEY
      if (!empty($params['primary_key']))
        {
        $keys.= " PRIMARY KEY ( `$name` ) ,";
        };

      // index
      if (!empty($params['index']))
        {
        $keys.= " INDEX ( `$name` ) ,";
        };

      // unique
      if (!empty($params['unique']))
        {
        $keys.= " UNIQUE ( `$name` ) ,";
        };

      // fulltext
      if (!empty($params['fulltext']))
        {
        $fulltext_index .= "$name, ";
        };

      //

      $query.= ', ';
      };

    if (empty($keys))
      {
      $query=rtrim ($query, ', ');
      };
    $keys=rtrim ($keys, ', ');
    //Ïðèëèïèëè êëþ÷è
    $query .= $keys;

    //Ïðèëåïèëè fulltext
    if ($fulltext_index)
      {
      $fulltext_index = rtrim ($fulltext_index, ', ');
      $query .= ", FULLTEXT ($fulltext_index)";
      };

    $query.= ');';

    //Ãðîõíóëè òàáëèöó, åñëè ñóùåñòâóåò
    $dropquery = 'DROP TABLE IF EXISTS ' . $tableName;
    if (mysqli_query($dbconn, $dropquery) === false)
      {
      sysException ('SYS_df->CreateTable', DATABASE_ERROR, mysqli_error($dbconn)."<br> $dropquery");
      return false;
      };

    //Ñîçäàëè íîâóþ :))
    if (mysqli_query($dbconn, $query) === false)
      {
      sysException ('SYS_df->CreateTable', DATABASE_ERROR, mysqli_error($dbconn)."<br> $query");
      return false;
      };

    foreach ($fields as $field_name => $parameters)
      {
      $query = "INSERT INTO $metaTable (
                `table_name`,
                `table_field`,
                `table_type`,
                `table_size`,
                `table_default`,
                `table_candelete`,
                `table_canmodify`,
                `table_index`,
                `table_null`,
                `table_unsigned`,
                `table_increment`,
                `table_primary_key`)
               VALUES (
                '" . sysVarPrepForStore($tableName) . "',
                '" . sysVarPrepForStore($field_name) . "',
                '" . (empty($parameters['type']) ? '' : sysvarPrepForStore($parameters['type'])) . "',
                '" . (empty($parameters['size']) ? '' : sysvarPrepForStore($parameters['size'])) . "',
                '" . (empty($parameters['default']) ? '' : sysvarPrepForStore($parameters['default'])) . "', " .
                     (empty($parameters['candelete']) ? '0' : '1') . ", " .
                     (empty($parameters['canmodify']) ? '0' : '1') . ", " .
                     (empty($parameters['index']) ? '0' : '1') . ", " .
                     (empty($parameters['null']) ? '0' : '1') . ", " .
                     (empty($parameters['unsigned']) ? '0' : '1') . ", " .
                     (empty($parameters['increment']) ? '0' : '1') . ", " .
                     (empty($parameters['primary_key']) ? '0' : '1') .
                     ")";
      if (mysqli_query($dbconn, $query) === false)
        {
        sysException ('SYS_df->CreateTable', DATABASE_ERROR, mysqli_error($dbconn)."<br> $query");
        return false;
        };
      }

    //×èñòèì êåø
    sysVarDelCached('columns', $tableName);
    return true;
    }

  /*
   * Ïðîöåäóðà Óäàëåíèÿ òàáëèöû
   */
  function DeleteTable($tableName)
    {
    $dropquery = 'DROP TABLE IF EXISTS ' . $tableName;
    mysql_query($dropquery);
    if (mysql_errno()!=0)
      {
      sysException ('SYS_df->DeleteTable', DATABASE_ERROR, mysql_error()."<br> $dropquery");
      };

    sysVarDelCached('columns', $tableName);
    return true;
    }

  /*
   * Îïòèìèçàöèÿ òàáëèöû
  */
  function OptimizeTable ($table)
    {
    $query="OPTIMIZE TABLE `$table`";
    mysql_query($query);
    if (mysql_errno()!=0)
      {
      sysException ('SYS_df->OptimizeTable', DATABASE_ERROR, mysql_error()."<br> $dropquery");
      };
    return true;
    }

  /*
   * Ñîçäàíèå ïîëÿ â òàáëèöå
  */
  function CreateField ($indata)
    {
    //Ìåíÿåì ñàìó òàáëèöó :))
    $query="ALTER TABLE `$indata[table]` ADD `$indata[field]` ";

    $query.= strtoupper($indata['type']);

    // (size)
    if (!empty ($indata['size']))
       {
      $query.= "($indata[size])";
      };

    // DEFAULT 'as'
    if (!empty ($indata['default']))
      {
      $query.= " DEFAULT '".$indata['default']."'";
      };

    //NOT NULL
    if (!$indata['null'])
      {
      $query.= ' NOT NULL';
      }
    //Âûïîëíèëè
    mysql_query($query);
    if (mysql_errno()!=0)
      {
      sysException ('SYS_df->CreateField', DATABASE_ERROR, mysql_error()."<br> $query");
      };

    //Äîáàâëÿåì ýëèìåíò â SYS_tables.
    $systable = sysDBGetTable('tables');
    $tables_column =  sysDBGetColumns($systable);
    sysDbInsert ($systable, array('table_name'        =>$indata['table'],
                                  'table_field'       =>$indata['field'],
                                  'table_type'        =>$indata['type'],
                                  'table_size'        =>$indata['size'],
                                  'table_default'     =>$indata['default'],
                                  'table_null'        =>$indata['null'],
                                  'table_unsigned'    =>$indata['unsigned'],
                                  'table_increment'   =>$indata['increment'],
                                  'table_primary_key' =>$indata['primary_key'],
                                  'table_index'       =>$indata['index'],
                                  'table_unique'      =>$indata['unique'],
                                  'table_fulltext'    =>$indata['fulltext'],
                                  'table_candelete'   =>$indata['candelete'],
                                  'table_canmodify'   =>$indata['canmodify']
                                 ));

    //Äîáàâëÿåì êëþ÷è
    if ($indata['index']==true) //Èíäåêñ
      {
      $query="ALTER TABLE `$indata[table]` ADD INDEX (`$indata[field]`)";
      mysql_query($query);
      if (mysql_errno()!=0)
        {
        sysException ('SYS_df->CreateField', DATABASE_ERROR, mysql_error()."<br> $query");
        };
      };
    if ($indata['unique']==true) //unique
      {
      $query="ALTER TABLE `$indata[table]` ADD UNIQUE (`$indata[field]`)";
      mysql_query($query);
      if (mysql_errno()!=0)
        {
        sysException ('SYS_df->CreateField', DATABASE_ERROR, mysql_error()."<br> $query");
        };
      };
    if ($indata['fulltext']==true) //unique
      {
      $query="ALTER TABLE `$indata[table]` ADD FULLTEXT (`$indata[field]`)";
      mysql_query($query);
      if (mysql_errno()!=0)
        {
        sysException ('SYS_df->CreateField', DATABASE_ERROR, mysql_error()."<br> $query");
        };
      };
    //Îïòèìèçèðóåì òàáëèöó
    $this->OptimizeTable ($indata['table']);
    //×èñòèì êåø
    sysVarDelCached('columns', $indata['table']);
    return true;
    }


  /*
   * Èçìåíåíèå ïîëÿ â òàáëèöå
  */
  function UpdateField ($indata)
    {
    //Ñòàðàÿ èíôà î ïîëå
    $dbdata = $this->get_info ($indata['id']);

    //Ìåíÿåì ñàìó òàáëèöó :))
    $query="ALTER TABLE `$indata[table]` CHANGE `$dbdata[field]` ";

    //Íîâîå èìÿ ïîëÿ
    $query.="`$indata[field]` ";

    $query.= strtoupper($indata['type']);

    // (size)
    if (!empty ($indata['size']))
      {
      $query.= "($indata[size])";
      };

    // DEFAULT 'as'
    if (!empty ($indata['default']))
      {
      $query.= " DEFAULT '".$indata['default']."'";
      };

    //NOT NULL
    if (!$indata['null'])
      {
      $query.= ' NOT NULL';
      }

    //Âûïîëíèëè
    mysql_query($query);
    if (mysql_errno()!=0)
      {
      sysException ('SYS_df->UpdateField', DATABASE_ERROR, mysql_error()."<br> $query");
      };

    //Îáíîâëÿåì ýëèìåíò â SYS_tables.
    $systable = sysDBGetTable('tables');
    $tables_column =  sysDBGetColumns($systable);
    sysDbUpdate ($systable,   array('table_name'        =>$indata['table'],
                                    'table_field'       =>$indata['field'],
                                    'table_type'        =>$indata['type'],
                                    'table_size'        =>$indata['size'],
                                    'table_default'     =>$indata['default'],
                                    'table_null'        =>$indata['null'],
                                    'table_unsigned'    =>$indata['unsigned'],
                                    'table_increment'   =>$indata['increment'],
                                    'table_primary_key' =>$indata['primary_key'],
                                    'table_index'       =>$indata['index'],
                                    'table_unique'      =>$indata['unique'],
                                    'table_fulltext'    =>$indata['fulltext']
                                   ), "WHERE ($tables_column[id]='".$indata['id']."')");

    //Äîáàâëÿåì êëþ÷è

    //Óäàëÿåì ñòàðûé êëþ÷
    $query="ALTER TABLE `$indata[table]` DROP INDEX `$indata[field]`";
    mysql_query($query);

    if ($indata['index']==true) //Èíäåêñ
      {
      //Ñîçäàåì íîâûé
      $query="ALTER TABLE `$indata[table]` ADD INDEX (`$indata[field]`)";
      //Âûïîëíèëè
      mysql_query($query);
      if (mysql_errno()!=0)
        {
        sysException ('SYS_df->UpdateField', DATABASE_ERROR, mysql_error()."<br> $query");
        };
      };

    //Óäàëÿåì ñòàðûé êëþ÷
    $query="ALTER TABLE `$indata[table]` DROP UNIQUE `$indata[field]`";
    mysql_query($query);

    if ($indata['unique']==true) //unique
      {
      //Ñîçäàåì íîâûé
      $query="ALTER TABLE `$indata[table]` ADD UNIQUE (`$indata[field]`)";
      //Âûïîëíèëè
      mysql_query($query);
      if (mysql_errno()!=0)
        {
        sysException ('SYS_df->UpdateField', DATABASE_ERROR, mysql_error()."<br> $query");
        };
      };
    //Îïòèìèçèðóåì òàáëèöó
    $this->OptimizeTable ($indata['table']);
    //×èñòèì êåø
    sysVarDelCached('columns', $indata['table']);
    return true;
    }

  /*
   * Óäàëåíèå ïîëÿ èç òàáëèöû
  */
  function DeleteField ($table, $field)
    {
    //Ìîäèôèêàöèÿ òàáëèöû
    $query="ALTER TABLE `$table` DROP `$field`";
    mysql_query($query);
    if (mysql_errno()!=0)
      {
      sysException ('SYS_df->DeleteField', DATABASE_ERROR, mysql_error()."<br> $query");
      };

    //Óäàëåíèå èç SYS_tables
    $systable = sysDBGetTable('tables');
    $tables_column =  sysDBGetColumns($systable);
    sysDbDelete ($systable, "WHERE $tables_column[table_name]='$table' AND $tables_column[table_field]='$field'");

    //Îïòèìèçèðóåì òàáëèöó
    $this->OptimizeTable ($table);
    //×èñòèì êåø
    sysVarDelCached('columns', $table);
    return true;
    }

  }

?>
