<?php

/*
 * Меню выбора, че искать.
*/
function search_user_main()
{
	//Прелюдие как у всех модулей
	$sysObject = 'search::user::main';
 
	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

	//Инициализируем шаблоны
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject);
	sysLoadLang ($sysTpl, 'search');
	$sysTpl->caching = false;

	sysModClassLoad('search');
	$search = new search;

	//Список возможных вариантов сортировки
	$sort_orders_list = $search->plugin_sort_orders_list();
	$sysTpl->assign('sort_orders_list', $sort_orders_list);

	//Документов на страницу
	$result_perpage_list = sysModGetVar('search','result_perpage_list');
	$result_perpage_list = explode(',', $result_perpage_list);
	$result_perpage_display_list = array();
  
	foreach ($result_perpage_list as $key) 
		$result_perpage_display_list[$key] = $key;
	$sysTpl->assign('result_perpage_list', $result_perpage_display_list);
	$sysTpl->assign('result_perpage', sysModGetVar('search','result_perpage'));

	// Сортировка
	$sysTpl->assign('result_orderby', sysModGetVar('search','result_orderby'));
	$sysTpl->assign('result_order_asc', sysModGetVar('search','result_order_asc'));

	//Живой поиск
	$sysTpl->assign('use_livesearch', sysModGetVar('search','use_livesearch'));

	//BrowseIn
	$browsein = array();
	$browsein[]=array ('url'=>'/search',
                   'displayname'=>'Поиск');
	$sysTpl->assign('browsein', $browsein);

	//Возвращаем результат
	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	$result['browsein'] =& $browsein;
	return $result;
}

/*
 * Меню выбора, че искать.
*/
function search_user_view()
{
	//Переменные со входа
	list ($search_string, $result_perpage, $result_orderby, $result_order_asc, $page_current, $search_param, $plugin) =
		sysVarCleanFromInput('search_string', 'result_perpage', 'result_orderby', 'result_order_asc', 'page_current', 'search_param', 'plugin');
		
	//Прелюдие как у всех модулей
	$sysObject = 'search::user::view';
	if ($plugin)
		$sysObject .= "::".$plugin;
  
	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

	//Инициализируем шаблоны
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject);
	sysLoadLang ($sysTpl, 'search');
	$sysTpl->caching = false;

	
	if (!is_numeric ($result_perpage) || $result_perpage < 1) 
		$result_perpage = sysModGetVar('search', 'result_perpage');
	
	sysModClassLoad('search');
	$search = new search;

	//В шаблон параметры поиска

	//Список возможных вариантов сортировки
	$sort_orders_list = $search->plugin_sort_orders_list();
	$sysTpl->assign('sort_orders_list', $sort_orders_list);

	//Документов на страницу
	$result_perpage_list = sysModGetVar('search','result_perpage_list');
	$result_perpage_list = explode(',', $result_perpage_list);
	$result_perpage_display_list = array();
	foreach ($result_perpage_list as $key) 
		$result_perpage_display_list[$key] = $key;
		
	$sysTpl->assign('result_perpage_list', $result_perpage_display_list);
	$sysTpl->assign('result_perpage', $result_perpage);

	// Сортировка
	$sysTpl->assign('result_orderby', $result_orderby);
	$sysTpl->assign('result_order_asc', $result_order_asc);

	//Живой поиск
	$sysTpl->assign('use_livesearch', sysModGetVar('search','use_livesearch'));

	// Ключевые слова
	$sysTpl->assign('search_string', $search_string);

	//Эскейпим строку под мускул
	$search_string = sysVarPrepForStore($search_string);

	//Выполняем поиск
	if ($search_string) {
		$search_result = array();
	
		$args['curuser_sec_level'] = $curuser_sec_level;
		$args['search_param'] = $search_param;
		$args['search_string'] = trim($search_string);
		$args['result_orderby'] = $result_orderby;
		$args['result_order_asc'] = $result_order_asc;
		if (!is_numeric($page_current)) 
			$page_current = 1;
		$args['page_current'] = $page_current;

	
		if ($plugin) {
			
			$plugin_exists = $search->plugin_load($plugin);
			if ($plugin_exists) {
				//смотрим параметры
				$func_param = 'search_plugin_'.$plugin.'_param';
				$form_param = $func_param(array());
			
				//и сам поиск
				$search_result = $search->plugin_exe_search($plugin, $args);
			}
			else {
				$search_result = $search->plugin_exe_search_all($args);
			}
		}
		else {
			$search_result = $search->plugin_exe_search_all($args);
		}
	
	

		$doc_total = count($search_result);
		$page_total = ceil($doc_total / $result_perpage);

		//Проверки
		if ($page_current < 1 ||  $page_current > $page_total) 
			$page_current = 1;
		$limit_from = $result_perpage * ($page_current - 1);
				
		$search_result = array ('search_result' => array_slice ($search_result, $limit_from, $result_perpage),
						'page_total' => $page_total,
						'page_current' => $page_current,
						'doc_total' => $doc_total,
						'start_doc_num' => $limit_from
								);
		
		//Все в шаблон
		$sysTpl->assign('plugin', $plugin);
		$sysTpl->assign($search_result);
		$sysTpl->assign($form_param);
		$sysTpl->assign($search_param);
		$sysTpl->assign('submit', true);
	}

	//BrowseIn
	$browsein = array();
	$browsein[]=array ('url'=>sysModURL('search','user','view', array ('search_string'=>$search_string)),
                   'displayname'=>'Поиск');

	$sysTpl->assign('browsein', $browsein);

	//Возвращаем результат
	$result['object'] =& $sysObject;
	$result['browsein'] =& $browsein;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

?>