<?php
/*
 * Главная админская страница
*/
function fields_user_main()
  {
  $sysObject = 'fields::user::main';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

  //Работа с sysTpl
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Выводим список последних комментариев
  sysModClassLoad ('fields');
  $fields = new fields;

  //Общая информация о таблицах и столбцах
  $fields_table = sysDBGetTable('fields');
  $fields_column = sysDBGetColumns($fields_table);

  $users_table = sysDBGetTable ('users');
  $users_column = sysDBGetColumns($users_table);

	//Выборка
  $sql = "SELECT $fields_table.*,
                 $users_column[id] as user_id, $users_column[user_name] as user_id_name,
                 $users_column[user_displayname] as user_id_displayname,
                 $users_column[user_regdate] as user_id_regdate,
                 $users_column[user_avatar_w] as user_id_avatar_w, $users_column[user_avatar_h] as user_id_avatar_h,
                 $users_column[user_avatar] as user_id_avatar, $users_column[user_rank] as user_id_rank
            FROM $fields_table
              LEFT JOIN $users_table ON $fields_table.user_id = $users_table.id
                ORDER BY $fields_column[comm_modtime] DESC
                	LIMIT 50";

  $mysql_result = mysql_query($sql);
  if (mysql_errno()!=0) sysException ('fields_user_main', DATABASE_ERROR, mysql_error()."<br> $sql");
  $fields_list = array();
  while ($row = mysql_fetch_assoc($mysql_result)) $fields_list[] = $row;
  mysql_free_result($mysql_result);

  $sysTpl->assign('fields_list', $fields_list);

  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('fields','user','main'),
                       'displayname'=>'Последние отзывы');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

?>