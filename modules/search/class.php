<?php

class search
  {

  /**
   * @return array
   * @desc Возврашает список доступных плугинов
  */
  function plugin_get_list()
    {
    $plugins_list = array();
    $dh = opendir('modules/search/plugins');
    while ($dir = readdir($dh))
      {
      if ((is_dir("modules/search/plugins/$dir")) &&
                    ($dir != '.') &&
                    ($dir != '..') &&
                    ($dir != 'CVS') &&
                    (file_exists ("modules/search/plugins/$dir/plugin.php")))
        {
        // Нашли
        // Загружаем информацию о плугин
        $plugin_info = $this->plugin_get_info($dir);
        if ($plugin_info)
          {
          array_push ($plugins_list, $plugin_info);
          }
        }
      }
    closedir($dh);
    return $plugins_list;
    }

  /**
   * @return bool
   * @param $plugin_name string
   * @desc Загружает плугин
  */
  function plugin_load($plugin_name)
    {
    $plugin_name = sysVarPrepForOS($plugin_name);
    static $loaded = array();

    //Много кратно не загружаем
    if ($loaded[$plugin_name]) return true;

    //Небольшая проверка
    if (file_exists ("modules/search/plugins/$plugin_name/plugin.php"))
      {
      include_once("modules/search/plugins/$plugin_name/plugin.php");
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
  function plugin_get_info($plugin_name)
    {
    static $loaded = array();

    //Много кратно не загружаем
    if ($loaded[$plugin_name]) return $loaded[$plugin_name];

    //Небольшая проверка + загрузка плугина
    if ($this->plugin_load($plugin_name))
      {
      //Вызыв функции
      $func = 'search_plugin_'.$plugin_name.'_info';
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
	* @desc Вызов функции "поиска", плугина
	*/
	function plugin_exe_search($plugin_name, $args)
    {
		//Небольшая проверка + загрузка плугина
		if ($this->plugin_load($plugin_name))  {
			$func = 'search_plugin_'.$plugin_name.'_search';
			$plugin_search_result = $func($args);

			return $plugin_search_result;
		}
		return false;
    }

	/**
	* @return array
	* @param $plugin_name string
	* @param $arg array
	* @desc Вызов функции "Живого поиска", плугина
	*/
	function plugin_exe_livesearch($plugin_name, $args)   {
		//Небольшая проверка + загрузка плугина
		if ($this->plugin_load($plugin_name))  {
			$func = 'search_plugin_'.$plugin_name.'_livesearch';
			$plugin_search_result = $func($args);
			return $plugin_search_result;
		}
		return false;
    }

	/**
	* @return array
	* @param $arg array
	* @desc Выполнить поиск с помошью функций всех плугинов
	*/
	function plugin_exe_search_all($args)
	{
		//Поиск
		$search_result = array();
		$plugin_list = $this->plugin_get_list();
		foreach ($plugin_list as $plugin)  {
			$plugin_search_result = $this->plugin_exe_search($plugin['name'], $args);
			$search_result = array_merge ($search_result, $plugin_search_result);
		}

		// Сортировка
		if (!empty ($search_result))  {
			switch($args['result_orderby'])  {
				case 'score' :
					if ($args['result_order_asc'])
						usort($search_result, array ('search', '_sort_score_asc'));
					else
						usort($search_result, array ('search', '_sort_score_desc'));
					break;
	
				case 'displayname' :
					if ($args['result_order_asc'])
						usort($search_result, array ('search', '_sort_displayname_asc'));
					else
						usort($search_result, array ('search', '_sort_displayname_desc'));
					break;

				case 'time' :
					if ($args['result_order_asc'])
						usort($search_result, array ('search', '_sort_time_asc'));
					else
						usort($search_result, array ('search', '_sort_time_desc'));
					break;

				case 'author' :
					if ($args['result_order_asc'])
						usort($search_result, array ('search', '_sort_author_asc'));
					else
						usort($search_result, array ('search', '_sort_author_desc'));
					break;
			}


		}

		return $search_result;
    }

  /**
   * @return array
   * @param $arg array
   * @desc Выполнить живой поиск с помошью функций всех плугинов
  */
  function plugin_exe_livesearch_all($args)
    {
    //Поиск
    $search_result = array();
    $plugin_list = $this->plugin_get_list();
    foreach ($plugin_list as $plugin)
      {
      $plugin_search_result = $this->plugin_exe_livesearch($plugin['name'], $args);
      $search_result = array_merge ($search_result, $plugin_search_result);
      }

    return array ('search_result' => array_slice ($search_result, 0, 15)
                  );
    }

	/*
	* Функции сортировки
	*/
	function _sort_score_asc($elt1, $elt2)
    {
		if ($elt1['score'] == $elt2['score']) return 0;
		return ($elt1['score'] < $elt2['score']) ? -1 : 1;
    }

	function _sort_score_desc($elt1,$elt2)
    {
		if ($elt1['score'] == $elt2['score']) return 0;
		return ($elt1['score'] > $elt2['score']) ? -1 : 1;
    }

	function _sort_displayname_asc($elt1, $elt2)
    {
		return strcmp($elt1['displayname'], $elt2['displayname']);
    }

	function _sort_displayname_desc($elt1,$elt2)
    {
		return strcmp($elt2['displayname'], $elt1['displayname']);
    }

	function _sort_time_asc($elt1, $elt2)
    {
		if ($elt1['time'] == $elt2['time']) return 0;
		return ($elt1['time'] < $elt2['time']) ? -1 : 1;
    }

	function _sort_time_desc($elt1,$elt2)
    {
		if ($elt1['time'] == $elt2['time']) return 0;
		return ($elt1['time'] > $elt2['time']) ? -1 : 1;
    }

	function _sort_author_asc($elt1, $elt2)
    {
		return strcmp($elt1['author'], $elt2['author']);
    }

	function _sort_author_desc($elt1,$elt2)
    {
		return strcmp($elt2['author'], $elt1['author']);
    }

	/**
   * @return array
   * @desc Возврашает возможные варианты сортировки
	*/
	function plugin_sort_orders_list()
    {
		$orders_list = array('score'=>'Релевантности',
                         'displayname'=>'Названию',
                         'time'=>'Дате',
                         'author'=>'Автору'
                        );
		return $orders_list;
    }

  }
?>