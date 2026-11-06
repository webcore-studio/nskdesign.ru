<?php
/*
 * Класс работы с плугинами
*/
class sysPlugins  {

	
	//  Проверка наличия плагина
	function plugin_check ($mod_name, $plugin_name)    {
		if (empty ($mod_name)) return false;
		if (empty ($plugin_name)) return false;
		if (file_exists ("modules/$mod_name/plugins/$plugin_name/plugin.php")) return true;
		else return false;
   }

	//  Возврашает список плугинов в системе
	function plugin_list ($mod_name)    {
		
		if (empty ($mod_name)) return false;
		$mod_name = sysVarPrepForOS($mod_name);

		$plugins_list = array();
		$dh = @opendir('modules/'.$mod_name.'/plugins');
		while ($dir = @readdir($dh))   {
			if ((is_dir("modules/$mod_name/plugins/$dir")) &&
                    ($dir != '.') &&
                    ($dir != '..') &&
                    ($dir != 'CVS') &&
                    (file_exists ("modules/$mod_name/plugins/$dir/plugin.php")))  {
				// Загружаем информацию о плугин
				$plugin_info = $this->get_info($mod_name, $dir);
				if (!empty ($plugin_info))
					array_push ($plugins_list, $plugin_info);
			}
      }
		@closedir($dh);
		return $plugins_list;
   }

	// Загружает плугин
	function plugin_load($mod_name, $plugin_name)    {
		
		if (empty ($mod_name)) return false;
		if (empty ($plugin_name)) return false;
    
		$mod_name = sysVarPrepForOS($mod_name);
		$plugin_name = sysVarPrepForOS($plugin_name);

		static $loaded = array();
		//Много кратно не загружаем
		if ($loaded[$mod_name][$plugin_name]) return true;

		//Небольшая проверка
		if (file_exists ("modules/$mod_name/plugins/$plugin_name/plugin.php"))   {
			include_once("modules/$mod_name/plugins/$plugin_name/plugin.php");
			$loaded[$mod_name][$plugin_name] = false;
			return true;
      }

		return false;
   }
	 
 
	 

	// Возврашает инфу о плугине
	function get_info ($mod_name, $plugin_name)    {
    
		if (empty ($mod_name)) return false;
		if (empty ($plugin_name)) return false;
    
		$mod_name = sysVarPrepForOS($mod_name);
		$plugin_name = sysVarPrepForOS($plugin_name);

		static $loaded = array();

		//Много кратно не собираем инфу
		if ($loaded[$mod_name][$plugin_name]) 
			return $loaded[$mod_name][$plugin_name];

		//Небольшая проверка + загрузка плугина
		if ($this->plugin_load($mod_name, $plugin_name))     {
			//Вызов функции
			$func = $mod_name.'_plugin_'.$plugin_name.'_info';
			if (function_exists($func))
				$plugin_info = $func();
			else
				return false;

			$plugin_info['name'] = $plugin_name; //Доп зашита
			$loaded[$mod_name][$plugin_name] = $plugin_info;
			return $plugin_info;
      }

		return false;
   }
	 


	//Диалоговое окно, вводимые данные для плугина
	function plugin_modify ($mod_name, $plugin_name)    {
		if (empty ($mod_name)) return false;
		if (empty ($plugin_name)) return false;
		$mod_name = sysVarPrepForOS($mod_name);
		$plugin_name = sysVarPrepForOS($plugin_name);

		if ($this->plugin_load($mod_name, $plugin_name))    {
			//Вызыв функции
			$func = $mod_name.'_plugin_'.$plugin_name.'_modify';
			if (function_exists($func))
				$plugin_modify_input = $func(null);
			else
				return false;

			return $plugin_modify_input;
      }

		return false;
   }


	// Внесение изменений ы настройку плугина
	function update ($mod_name, $plugin_name)  {
		if (empty ($mod_name)) return false;
		if (empty ($plugin_name)) return false;
		$mod_name = sysVarPrepForOS($mod_name);
		$plugin_name = sysVarPrepForOS($plugin_name);

		if ($this->plugin_load($mod_name, $plugin_name))  {
			//Вызoв функции
			$func = $mod_name.'_plugin_'.$plugin_name.'_update';
			if (function_exists($func))
				$func(null);
			else
				return false;

			return true;
      }
		return false;
   }


  // Выполнение плугина
	function plugin_exe($mod_name, $plugin_name, $func_name, $args)   {
    
		if (empty ($mod_name)) return false;
		if (empty ($plugin_name)) return false;
		if (empty ($func_name)) return false;
    
		$mod_name = sysVarPrepForOS($mod_name);
		$plugin_name = sysVarPrepForOS($plugin_name);

		if ($this->plugin_load($mod_name, $plugin_name))    {
			//Вызoв функции
			$func = $mod_name.'_plugin_'.$plugin_name.'_'.$func_name;
			if (!function_exists($func)) return false;
			$result = $func($args);
			return $result;
		}
		return false;
   }
	 
}

?>