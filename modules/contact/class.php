<?php

class contact
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
	function create ($contact_indate)
    {
		//Дополняем информацию о комменте
		$contact_indate['user_id'] = sysSessionGetVar('user_id');
		$contact_indate['contact_addtime'] = time();
		$contact_indate['contact_from_ip'] = sysUserIpAddr();
		if ($contact_indate['user_id'] > 0) {
			$vars = sysUserGetVars($contact_indate['user_id']);
			$contact_indate['user_displayname']=$vars['user_displayname'];
			$contact_indate['user_name']=$vars['user_name'];
			$contact_indate['user_email']=$vars['user_email'];	
		}

		//Вставили в базу
		$contact_table = sysDBGetTable('contact');
		$contact_column = sysDBGetColumns($contact_table);
		$contact_indate['id'] = sysDbInsert ($contact_table, $contact_indate);

		return $contact_indate['id'];
    }

  /*
   * @desc Удаляет элемент
   * @return bool
   * @param id integer
  */
  function delete($id)
    {
    //Информация о комментарии
		$contact_dbdate = $this->get_info($id);

    //Проверка разрешеныли комментарии
    if (!sysModGetVar('contact', 'contact_enabled')) return;

    // Проверка наличия модулей
    if (!sysModAvailable ('contact')) return;
    if (!sysModAvailable ($contact_dbdate['comm_module'])) return;

    //загружаем модулю которому адресован коммент
    sysModClassLoad ($contact_dbdate['comm_module']);
    $tomodule = new $contact_dbdate['comm_module'];

    //Пробуем передать управление модулю которому адресован коммент для пред обработки
    $prefilter_func = 'contact_confirm_delete';
    if (method_exists ($tomodule, $prefilter_func)) $contact_dbdate = $tomodule->$prefilter_func($contact_dbdate);

		//Производим удаление
    $contact_table = sysDBGetTable('contact');
    $contact_column = sysDBGetColumns($contact_table);
    sysDbDelete ($contact_table, "WHERE $contact_column[id] = '$id'");

    //Пробуем передать управление модулю которому адресован коммент для пост действий
    $postfilter_func = 'contact_delete';
    if (method_exists ($tomodule, $postfilter_func)) $tomodule->$postfilter_func($contact_dbdate);


    return true;
    }

  /**
   * @desc Информация о комментарие
   * @return array
   * @param id integer
  */
  function get_info ($id)
    {
    $contact_table = sysDBGetTable('contact');
    $contact_column = sysDBGetColumns($contact_table);

    $contact_dbdate = sysDbSelect ($contact_table, $contact_column, "WHERE $contact_column[id]= '$id'");

    return $contact_dbdate;
    }

	/**
	* @desc Список сообщений
	* @return array
	*/
	function get_list ()
    {
		//Общая информация о таблицах и столбцах
		$contact_table = sysDBGetTable('contact');
		$contact_column = sysDBGetColumns($contact_table);

		$users_table = sysDBGetTable ('users');
		$users_column = sysDBGetColumns($users_table);

		//Выборка
		$sql = "SELECT $contact_table.*, $users_column[user_regdate] as user_id_regdate,
					 $users_column[id] as user_id, $users_column[user_name] as user_id_name,
					 $users_column[user_email] as user_id_email,
					  $users_column[user_phone] as user_id_phone,
					 $users_column[user_displayname] as user_id_displayname
				FROM $contact_table
				  LEFT JOIN $users_table ON $contact_table.user_id = $users_table.id
					ORDER BY $contact_column[contact_addtime] DESC ";

		$mysql_result = mysql_query($sql);
		if (mysql_errno()!=0) 
			sysException ('contact_admin_main', DATABASE_ERROR, mysql_error()."<br> $sql");
		$contact_list = array();
		while ($row = mysql_fetch_assoc($mysql_result)) $contact_list[] = $row;
		mysql_free_result($mysql_result);

		return $contact_list;
    }

  /*
   * Обновление элимента
   * item_indate
  */
  function update ($contact_indate)
    {
  	//Проверка разрешеныли комментарии
  	if (!sysModGetVar('contact', 'contact_enabled')) return;

    //Информация о комментарии
    $contact_dbdate = $this->get_info($contact_indate['id']);

  	// Проверка наличия модулей
    if (!sysModAvailable ('contact')) return;
    if (!sysModAvailable ($contact_dbdate['comm_module'])) return;

    //Дополняем информацию о комменте
    $contact_dbdate['comm_modtime'] = time();
    $contact_dbdate['comm_modcount'] = $contact_dbdate['comm_modcount']+1;
    $contact_dbdate['comm_from_ip'] = sysUserIpAddr();

    //Обращаею внимание
    $contact_dbdate['comm_displayname'] = $contact_indate['comm_displayname'];
    $contact_dbdate['comm_content'] = $contact_indate['comm_content'];

  	//загружаем модулю которому адресован коммент
    sysModClassLoad ($contact_dbdate['comm_module']);
    $tomodule = new $contact_dbdate['comm_module'];

  	//Пробуем передать управление модулю которому адресован коммент для пред обработки
  	$prefilter_func = $contact_dbdate['comm_module'].'_contact_modify';
    if (function_exists($prefilter_func)) $contact_dbdate = $prefilter_func($contact_dbdate);

		//Вставляем вбазу
    $contact_table = sysDBGetTable('contact');
    $contact_column = sysDBGetColumns($contact_table);
    sysDbUpdate ($contact_table, $contact_dbdate, "WHERE $contact_column[id] = '$contact_dbdate[id]'");

  	//Пробуем передать управление модулю которому адресован коммент для пост действий
  	$postfilter_func = $contact_dbdate['comm_module'].'_contact_update';
    if (function_exists($postfilter_func)) $postfilter_func($contact_dbdate);

    return true;
    }

	/*
   * Декодирование utf8
	*/
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
	
	function utf8_decode($str, $to = 'utf-8')
	{
		$to = strtolower($to);
		$str = iconv("utf-8", $to, $str);
		return $str;
    }


  }

?>