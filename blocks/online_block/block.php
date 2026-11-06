<?php

function online_block_display($blockinfo)
  {
  //Прелюдие как у всех
  $sysObject = 'online_block::display::'.$blockinfo['id'];
  $sysModTpl = sysTplWay ($sysObject, $blockinfo['block_tpl']);

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return true;

  $sysTpl = new sysTpl;
  $sysTpl->cache_lifetime = 180; //Время обновления 5 мин

  //Привязка кеша идет к $uid
  $uid = sysSessionGetVar ('user_id');

  if($sysTpl->is_cached($sysModTpl,$sysObject.'::'.$uid))
    {
    //Возвращаем
    $result['block_content'] = $sysTpl->fetch($sysModTpl,$sysObject.'::'.$uid);
    return $result;
    };

  $activetime = time() - (sysModGetVar('SYS_config','sesinactivemins') * 60);
  $cur_session_id = session_id ();
  $num_guests=0;
  $num_users=0;

  //Выгребаем список сессий с диска где дата больше $activetime
  $session_table = sysDBGetTable('session');
  $session_column = sysDBGetColumns($session_table);

  //Гости
  $sql = "SELECT COUNT(*) AS num_guests
            FROM $session_table
            WHERE $session_column[lastused] > '$activetime' AND
            			$session_column[user_gid] < '1'";
  //Логируем выборку
  global $dbg_sql_queries;
  $dbg_sql_queries[] = array('sql_query'=>$sql);
	$mysql_result = mysql_query($sql);
  if (mysql_errno()!=0) sysException ('online_block_display', DATABASE_ERROR, mysql_error()."<br> $sql");
  $sess_dbdate = mysql_fetch_assoc($mysql_result);
  mysql_free_result($mysql_result);
  $num_guests=$sess_dbdate['num_guests'];

  //Пользователи
  $sql = "SELECT COUNT(*) AS num_users
            FROM $session_table
            WHERE $session_column[lastused] > '$activetime' AND
            			$session_column[user_gid] > '0'";
  //Логируем выборку
  global $dbg_sql_queries;
  $dbg_sql_queries[] = array('sql_query'=>$sql);
	$mysql_result = mysql_query($sql);
  if (mysql_errno()!=0) sysException ('online_block_display', DATABASE_ERROR, mysql_error()."<br> $sql");
  $sess_dbdate = mysql_fetch_assoc($mysql_result);
  mysql_free_result($mysql_result);
  $num_users=$sess_dbdate['num_users'];

  //Проверка если оба в 0 то ..
  if (($num_users==0) and ($num_guests==0))
    {
    if (sysUserIsLoggedIn())
      {
      $num_users=1;
      }
      else
        {
        $num_guests=1;
        };
    };

  $sysTpl->assign('num_users', $num_users);
  $sysTpl->assign('num_guests', $num_guests);

  //Переменные пользователя
  $user_vars = sysUserGetVars();
  $sysTpl->assign('user_vars', $user_vars);

  $result['block_content'] = $sysTpl->fetch($sysModTpl,$sysObject.'::'.$uid);
  return $result;
  }


?>