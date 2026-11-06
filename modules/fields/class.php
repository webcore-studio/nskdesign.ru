<?php

class fields
  {
  /**
   * @desc Информация о поле
   * @return array
   * @param id integer
  */
  function get_info ($id)
    {
    $fields_table = sysDBGetTable('fields');
    $fields_column = sysDBGetColumns($fields_table);

    $fields_dbdate = sysDbSelect ($fields_table, $fields_column, "WHERE $fields_column[id]= $id");

    return $fields_dbdate;
    }


  function get_params ($id)
    {

    $param_table = sysDBGetTable('fields_param');
    $param_column = sysDBGetColumns($param_table);

    $params_dbdate = sysDbSelect ($param_table, $param_column, "WHERE $param_column[id_field]= $id",true);

    return $params_dbdate;
    }


	/**
	* @desc Список полей
	* @return array
	*/
	
	function get_list ($fld_module, $fld_name, $fld_key_a, $fld_key_b)     {
    
		//Общая информация о таблицах и столбцах
		$fields_table = sysDBGetTable('fields');
		$fields_column = sysDBGetColumns($fields_table);

		$param_table = sysDBGetTable('fields_param');
		$param_column = sysDBGetColumns($param_table);

		$values_table = sysDBGetTable('fields_values');
		$values_column = sysDBGetColumns($values_table);

		// список полей - всех или ищем указанное
		if ($fld_name) 
			$where_fields = "WHERE $fields_column[fld_name] = '$fld_name' ";
		else $where_fields = "";
		
		$fields_list = sysDBSelect($fields_table, $fields_column,  $where_fields, true);

		if (count($fields_list) == 0) return; 
		
		foreach ($fields_list as $k => $f) {
			// какие значения они могут принимать
			$fields_list[$k]['param'] = sysDBSelect($param_table, $param_column, "WHERE $param_column[id_field]=$f[id] ORDER BY $param_column[fld_field] ASC", true);
			if ($fields_list[$k]['param']) {
				foreach ($fields_list[$k]['param'] as $k1=>$p) {
					$values = array();
					$where_values = "WHERE $values_column[fld_value] = '$p[fld_field]' AND $values_column[id_field] = $f[id] ";
					if ($fld_key_a) 
						$where_values .= " AND $values_column[fld_key_a] = $fld_key_a";
					if ($fld_key_b) 
						$where_values .= " AND $values_column[fld_key_b] = $fld_key_b";
					if ($fld_module) 
						$where_values .= " AND $values_column[fld_module] = '$fld_module'";
					
					$where_values .= " LIMIT 0,1";
					
				
					$values	=	sysDBSelect($values_table,	$values_column, $where_values,false);
					if ($values) 
						$fields_list[$k]['param'][$k1]['fld_value'] = $values['fld_value'];
				}
			}
			else {	
				
				$where_values = "WHERE  $values_column[id_field] = $f[id] ";
				if ($fld_key_a) 
					$where_values .= " AND $values_column[fld_key_a] = $fld_key_a";
				if ($fld_key_b) 
					$where_values .= " AND $values_column[fld_key_b] = $fld_key_b";
				if ($fld_module) 
					$where_values .= " AND $values_column[fld_module] = '$fld_module'";
				$values	=	sysDBSelect($values_table,	$values_column, $where_values,false);
				if ($values) 
					$fields_list[$k]['param']['fld_value'] = unserialize($values['fld_value']);
			}

		}
		return $fields_list;
    }


	function get_values ($fld_module, $fld_name, $fld_key_a, $fld_key_b)   {
    
		//Общая информация о таблицах и столбцах
		$fields_table = sysDBGetTable('fields');
		$fields_column = sysDBGetColumns($fields_table);

		$param_table = sysDBGetTable('fields_param');
		$param_column = sysDBGetColumns($param_table);
		
		$values_table = sysDBGetTable('fields_values');
		$values_column = sysDBGetColumns($values_table);
		
		$param_column_s=array();
		$param_column_s['fld_field']=$param_table.".fld_field";

		
		$param_column['fld_value']=$values_column['fld_value'];


		// список возможных полей
		if ($fld_name) 
			$where_fields = "WHERE $fields_column[fld_name] = '$fld_name' ";
		else $where_fields = "";
		
		$fields_list = sysDBSelect($fields_table, $fields_column, $where_fields, true);
		
		if (!$fields_list) return false;
		
		

		foreach ($fields_list as $k => $field)	{
			$where = "WHERE  $values_column[fld_module]= '$fld_module' AND $values_column[id_field]=$field[id] ";
			if ($fld_key_a) $where .= " AND $values_column[fld_key_a] = $fld_key_a ";
			if ($fld_key_b) $where .= " AND $values_column[fld_key_b] = $fld_key_b ";
			$fields_list[$k]['values'] = sysDBSelect($values_table, $values_column, $where, true);

		}

		return $fields_list;
   }



  /*
   * Обновление элимента
   * item_indate
  */
  function field_create ($field_indate)
    {
    $fields_table = sysDBGetTable('fields');
    $fields_column = sysDBGetColumns($fields_table);

$inserted=sysDBInsert($fields_table,$field_indate);
return $inserted;

}


	function value_create($indata) {
		
		$values_table = sysDBGetTable('fields_values');
		$values_column = sysDBGetColumns($values_table);

		$where_arr = array();
		foreach ($indata as $k => $v)
			$where_arr[] = " $values_column[$k] = '$v' ";
		
		$res = sysDbSelect($values_table, $values_column,"WHERE ".implode(" AND ", $where_arr), true);
		if (!$res)
			sysDBInsert($values_table, $indata);

		return true;
	}


	function values_delete($fld_module, $fld_key_a, $fld_key_b) {
    
		$values_table = sysDBGetTable('fields_values');
		$values_column = sysDBGetColumns($values_table);

		if (!$fld_key_a && !$fld_key_b) return false;
		
		$where = array();
		if ($fld_key_a) $where[] = "$values_column[fld_key_a] = $fld_key_a";
		if ($fld_key_b) $where[] = "$values_column[fld_key_b] = $fld_key_b";
		
		sysDBDelete($values_table, "WHERE ".implode(" AND ", $where));
		return true;
	}
	
	
	/*
		Фильтр по значению доп. поля
	*/
	
	function get_objects ($fld_module, $id_field, $fld_value)   {
    
		//Общая информация о таблицах и столбцах

		$values_table = sysDBGetTable('fields_values');
		$values_column = sysDBGetColumns($values_table);
		
		$where = "WHERE $values_column[fld_value] = '$fld_value' ";
		
		if ($fld_module)
			$where .= " AND $values_column[fld_module] = '$fld_module'";
			
		if ($id_field)
			$where .= " AND $values_column[id_field] = $id_field";
		
		// список возможных полей для модуля
		$objects_list = sysDBSelect($values_table, $values_column, $where, true);
		
		if (!$objects_list) return false;
		
		return $objects_list;
   }













  /*
   * Обновление элимента
   * item_indate
  */
  function update ($fields_indate)
    {
  	//Проверка разрешеныли комментарии
  	if (!sysModGetVar('fields', 'fields_enabled')) return;

    //Информация о комментарии
    $fields_dbdate = $this->get_info($fields_indate['id']);

  	// Проверка наличия модулей
    if (!sysModAvailable ('fields')) return;
    if (!sysModAvailable ($fields_dbdate['comm_module'])) return;

    //Дополняем информацию о комменте
    $fields_dbdate['comm_modtime'] = time();
    $fields_dbdate['comm_modcount'] = $fields_dbdate['comm_modcount']+1;
    $fields_dbdate['comm_from_ip'] = sysUserIpAddr();

    //Обращаею внимание
    $fields_dbdate['comm_displayname'] = $fields_indate['comm_displayname'];
    $fields_dbdate['comm_content'] = $fields_indate['comm_content'];

  	//загружаем модулю которому адресован коммент
    sysModClassLoad ($fields_dbdate['comm_module']);
    $tomodule = new $fields_dbdate['comm_module'];

  	//Пробуем передать управление модулю которому адресован коммент для пред обработки
  	$prefilter_func = $fields_dbdate['comm_module'].'_fields_modify';
    if (function_exists($prefilter_func)) $fields_dbdate = $prefilter_func($fields_dbdate);

		//Вставляем вбазу
    $fields_table = sysDBGetTable('fields');
    $fields_column = sysDBGetColumns($fields_table);
    sysDbUpdate ($fields_table, $fields_dbdate, "WHERE $fields_column[id] = '$fields_dbdate[id]'");

  	//Пробуем передать управление модулю которому адресован коммент для пост действий
  	$postfilter_func = $fields_dbdate['comm_module'].'_fields_update';
    if (function_exists($postfilter_func)) $postfilter_func($fields_dbdate);

    return true;
    }

  function field_update ($field_indate)
    {
    $fields_table = sysDBGetTable('fields');
    $fields_column = sysDBGetColumns($fields_table);

sysDBUpdate($fields_table,$field_indate,"WHERE $fields_column[id]=$field_indate[id]");
return true;

}


  function param_create ($param_indate)
    {
    $param_table = sysDBGetTable('fields_param');
    $param_column = sysDBGetColumns($param_table);

if ($param_indate['fld_default'])
	sysDBUpdate($param_table,array('fld_default'=>0),"WHERE $param_column[fld_default]=1");

$inserted=sysDBInsert($param_table,$param_indate);


return $inserted;
}

  function param_delete ($id)
    {
    $param_table = sysDBGetTable('fields_param');
    $param_column = sysDBGetColumns($param_table);

sysDBDelete($param_table,"WHERE $param_column[id]=$id");

return $inserted;
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
	 
	 		/* проверка загруженности плагина */
	
	function plugin_check($plugin_name)
    {
		//Небольшая проверка
		if (file_exists ("modules/fields/plugins/$plugin_name/plugin.php")) return true;
		else return false;
    }
	
	
	/* Список загруженных плагинов */
	
	function plugin_list($plugin_name)
    {
		
		$pluginlist = array();
		$dh = opendir('modules/fields/plugins');
		while ($dir = readdir($dh))  {
		  
			if ((is_dir("modules/fields/plugins/$dir")) &&
						($dir != '.') &&
						($dir != '..') &&
						($dir != 'CVS') &&
						(file_exists ("modules/fields/plugins/$dir/plugin.php")))	{
				// нашли
				$pluginlist['plugin_name']=$dir;
			}
		}
		closedir($dh);
    }

	/* подключение плагинов */
	
	function plugin_load($plugin_name)
    {
		$plugin_name = sysVarPrepForOS($plugin_name);
		static $loaded = array();

		//Много кратно не загружаем
		if ($loaded[$plugin_name]) 
			return true;

		//Небольшая проверка
		if (file_exists ("modules/fields/plugins/$plugin_name/plugin.php"))
		{
			include_once("modules/fields/plugins/$plugin_name/plugin.php");
			$loaded[$plugin_name] = false;
			return true;
		}

		return false;
    }

	/**
	* @return array
	* @param $plugin_name string
	* @desc Возврашает информацию о плугине
	*/
	function plugin_get_info($plugin_name) {
		
		static $loaded = array();

		//Много кратно не загружаем
		if ($loaded[$plugin_name]) 
			return $loaded[$plugin_name];

		//Небольшая проверка + загрузка плугина
		if ($this->plugin_load($plugin_name))
		{
			//Вызыв функции
			$func = 'fields_plugin_'.$plugin_name.'_info';
			$plugin_info = $func();
			$plugin_info['name'] = $plugin_name; //Доп зашита
			$loaded[$plugin_name] = $plugin_info;
			return $plugin_info;
		}

		return false;
    }

	/**
	* @return array
	* @param $plugin_name string
	* @param $arg array
	* @desc Вызов функций плагина
	*/
	function plugin_exe($plugin_name,$func_name, $args)    {
		
		//Небольшая проверка + загрузка плугина
		if ($this->plugin_load($plugin_name))
		{
			$func = 'fields_plugin_'.$plugin_name.'_'.$func_name;
			if (!function_exists($func)) return false;
			$result = $func($args);
			return $result;
		}
		
		return false;
	}


  }

?>