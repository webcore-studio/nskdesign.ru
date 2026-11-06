<?php

class comment
  {
  /*
   * Переменные
  */
  var $get_list_found_rows;
  /*
   * Функции
  */


  /*
   * Добавление комментария
  */
  function create ($comment_indate)
    {

   //   sys_log('create',LOG_INFO,'class create', $comment_indate, "comment");

  	//Проверка разрешеныли комментарии
  	if (!sysModGetVar('comment', 'comment_enabled')) return;

  	// Проверка наличия модулей
    if (!sysModAvailable ('comment')) return;
    if (!sysModAvailable ($comment_indate['comm_module'])) return;

    //Проверки
    $comment_indate['comm_key_a'] = (int)$comment_indate['comm_key_a'];
    $comment_indate['comm_key_b'] = (int)$comment_indate['comm_key_b'];
    if ($comment_indate['comm_key_a']<1 && $comment_indate['comm_key_b']<1) return;

    //Дополняем информацию о комменте
    $comment_indate['user_id'] = sysSessionGetVar('user_id');
    $comment_indate['comm_addtime'] = time();
    $comment_indate['comm_modtime'] = $comment_indate['comm_addtime'];
    $comment_indate['comm_modcount'] = 0;
    $comment_indate['comm_from_ip'] = sysUserIpAddr();
   // if ($comment_indate['user_id']>0) unset($comment_indate['user_displayname']); //Юнсетим имя если есть ид пользователя

  	//загружаем модулю которому адресован коммент
    sysModClassLoad ($comment_indate['comm_module']);
    $tomodule = new $comment_indate['comm_module'];

  	//Пробуем передать управление модулю которому адресован коммент для пред обработки
  	$prefilter_func = 'comment_new';
    if (method_exists ($tomodule, $prefilter_func)) $comment_indate = $tomodule->$prefilter_func($comment_indate);

    //Вставили в базу
    $comment_table = sysDBGetTable('comment');
    //$comment_column = sysDBGetColumns($comment_table); // а зачем нам поля доставать
    $comment_indate['comment_id'] = $comment_indate['id'] = sysDbInsert ($comment_table, $comment_indate);

    $rating_table = sysDBGetTable('rating');
    $rating_indate['id'] = sysDbInsert ($rating_table, $comment_indate);


  	//Пробуем передать управление модулю которому адресован коммент для пост действий
  	$postfilter_func = 'comment_create';
    if (method_exists ($tomodule, $postfilter_func)) $tomodule->$postfilter_func($comment_indate);


    return $comment_indate['id'];
    }

function utf8_urldecode($str, $to = 'utf-8')
{
      $to = strtolower($to);
      if ($to == 'utf-8')
        {
              $str = preg_replace( '#%u([0-9A-F]{1,4})#ie', "decode_to_utf8(hexdec('\\1'))", utf8_encode($str) );
              $str = urldecode ($str);
      }
      else
        {
                      $str = preg_replace( '#%u([0-9A-F]{1,4})#ie', "'&#' . hexdec('\\1') . ';'", $str );
                      $str = urldecode ($str);
      $str = @html_entity_decode($str, ENT_NOQUOTES, $to);
                      }

      return $str;
    }
  /*
   * @desc Удаляет элемент
   * @return bool
   * @param id integer
  */
  function delete($id)
    {
    //Информация о комментарии
		$comment_dbdate = $this->get_info($id);

    //Проверка разрешеныли комментарии
    if (!sysModGetVar('comment', 'comment_enabled')) return;

    // Проверка наличия модулей
    if (!sysModAvailable ('comment')) return;
    if (!sysModAvailable ($comment_dbdate['comm_module'])) return;

    //загружаем модулю которому адресован коммент
    sysModClassLoad ($comment_dbdate['comm_module']);
    $tomodule = new $comment_dbdate['comm_module'];

    //Пробуем передать управление модулю которому адресован коммент для пред обработки
    $prefilter_func = 'comment_confirm_delete';
    if (method_exists ($tomodule, $prefilter_func)) $comment_dbdate = $tomodule->$prefilter_func($comment_dbdate);

		//Производим удаление
    $comment_table = sysDBGetTable('comment');
    $comment_column = sysDBGetColumns($comment_table);
    sysDbDelete ($comment_table, "WHERE $comment_column[id] = '$id'");

    //Пробуем передать управление модулю которому адресован коммент для пост действий
    $postfilter_func = 'comment_delete';
    if (method_exists ($tomodule, $postfilter_func)) $tomodule->$postfilter_func($comment_dbdate);


    return true;
    }

  /**
   * @desc Информация о комментарие
   * @return array
   * @param id integer
  */
  function get_info ($id)
    {
    $comment_table = sysDBGetTable('comment');
    $comment_column = sysDBGetColumns($comment_table);

    $comment_dbdate = sysDbSelect ($comment_table, $comment_column, "WHERE $comment_column[id]= '$id'");

    return $comment_dbdate;
    }

  /**
   * @desc Список комментариев
   * @return array
  */
  function get_list ($comm_module, $comm_key_a, $comm_key_b,
										 $page, $comment_perpage, $sort_by, $sort_asc)
    {
      global $dbconn;
    //Общая информация о таблицах и столбцах
    $comment_table = sysDBGetTable('comment');
    $comment_column = sysDBGetColumns($comment_table);

    $users_table = sysDBGetTable ('users');
    $users_column = sysDBGetColumns($users_table);

		$comment_start_num = ($page-1)*$comment_perpage;

		//Выборка
    $sql = "SELECT SQL_CALC_FOUND_ROWS
    							 $comment_table.*,
                   $users_column[id] as user_id, $users_column[user_name] as user_id_name,
                   $users_column[user_displayname] as user_id_displayname,
                   $users_column[user_regdate] as user_id_regdate,
                   $users_column[user_avatar_w] as user_id_avatar_w, $users_column[user_avatar_h] as user_id_avatar_h,
                   $users_column[user_avatar] as user_id_avatar, $users_column[user_rank] as user_id_rank
              FROM $comment_table
                LEFT JOIN $users_table ON $comment_table.user_id = $users_table.id
                  WHERE $comment_column[comm_module] = '$comm_module'";
		if ($comm_key_a) $sql.= " AND $comment_column[comm_key_a] = '$comm_key_a'";
		if ($comm_key_b) $sql.= " AND $comment_column[comm_key_b] = '$comm_key_b'";
		if ($sort_by=='addtime') $sql.= " ORDER BY $comment_column[comm_addtime]";
			else $sql.= " ORDER BY $comment_column[comm_modtime]";
		if ($sort_asc) $sql.= 'ASC';
			else $sql.= 'DESC';

		$sql.= ' LIMIT 100';

    $mysql_result = $dbconn->query($sql);
    if (mysqli_connect_errno()!=0) sysException ('comment->get_list', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
    $comment_list = array();
    while ($row = mysqli_fetch_assoc($mysql_result)) $comment_list[] = $row;
    mysqli_free_result($mysql_result);

    //Считаем суммарное число комментов подпадающих под критерий поиска
    if (!empty($comment_list))
      {
      $sql = "SELECT FOUND_ROWS() AS total";
      $mysql_result = $dbconn->query($sql);
      if (mysqli_connect_errno()!=0) sysException ('comment->get_list', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
      $row = mysqli_fetch_assoc($mysql_result);
      $this->get_list_found_rows = $row['total'];
      mysqli_free_result($mysql_result);
	  
	  foreach ( $comment_list as $k=>$comment)
		if ($comment['comm_fields'])
			$comment_list[$k]['comm_fields'] = unserialize($comment['comm_fields']);
      }

    return $comment_list;
    }

  /*
   * Обновление элимента
   * item_indate
  */
  function update ($comment_indate)
    {
  	//Проверка разрешеныли комментарии
  	if (!sysModGetVar('comment', 'comment_enabled')) return;

    //Информация о комментарии
    $comment_dbdate = $this->get_info($comment_indate['id']);

  	// Проверка наличия модулей
    if (!sysModAvailable ('comment')) return;
    if (!sysModAvailable ($comment_dbdate['comm_module'])) return;

    //Дополняем информацию о комменте
    $comment_dbdate['comm_modtime'] = time();
    $comment_dbdate['comm_modcount'] = $comment_dbdate['comm_modcount']+1;
    $comment_dbdate['comm_from_ip'] = sysUserIpAddr();

    //Обращаею внимание
    $comment_dbdate['comm_displayname'] = $comment_indate['comm_displayname'];
    $comment_dbdate['comm_content'] = $comment_indate['comm_content'];

  	//загружаем модулю которому адресован коммент
    sysModClassLoad ($comment_dbdate['comm_module']);
    $tomodule = new $comment_dbdate['comm_module'];

  	//Пробуем передать управление модулю которому адресован коммент для пред обработки
  	$prefilter_func = $comment_dbdate['comm_module'].'_comment_modify';
    if (function_exists($prefilter_func)) $comment_dbdate = $prefilter_func($comment_dbdate);

		//Вставляем вбазу
    $comment_table = sysDBGetTable('comment');
    $comment_column = sysDBGetColumns($comment_table);
    sysDbUpdate ($comment_table, $comment_dbdate, "WHERE $comment_column[id] = '$comment_dbdate[id]'");

  	//Пробуем передать управление модулю которому адресован коммент для пост действий
  	$postfilter_func = $comment_dbdate['comm_module'].'_comment_update';
    if (function_exists($postfilter_func)) $postfilter_func($comment_dbdate);

    return true;
    }

	
	
	  /**
   * @desc Количество комментариев
   * @return array
  */
  function get_count ($comm_module, $comm_key_a, $comm_key_b)
    {
      global $dbconn;
    //Общая информация о таблицах и столбцах
    $comment_table = sysDBGetTable('comment');
    $comment_column = sysDBGetColumns($comment_table);

		//Выборка
	  $sql = "SELECT count(*)
               FROM $comment_table
                  WHERE $comment_column[comm_module] = '$comm_module'";
		if ($comm_key_a) $sql.= " AND $comment_column[comm_key_a] = '$comm_key_a'";
		if ($comm_key_b) $sql.= " AND $comment_column[comm_key_b] = '$comm_key_b'";

    $mysql_result = $dbconn->query($sql);
    if (mysqli_connect_errno()!=0) sysException ('comment->get_count', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
	list($comment_count) = mysqli_fetch_row($mysql_result);
       mysqli_free_result($mysql_result);

    
    return $comment_count;
    }
	

  }

?>