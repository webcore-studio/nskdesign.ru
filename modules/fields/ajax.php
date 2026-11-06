<?php
/*
 * Редирект
*/
function fields_ajax_main()  {
	sysRedirect(sysModURL('fields','user','main'));
}


/*
 * Список значений назначенных полей для объекта
*/
function fields_ajax_values_view($params=null)  {
  
	//Если не переданны параметры, собираем совхода
	if (empty($params))  
		list ($params['fld_name'], $params['fld_module'], $params['fld_key_a'], $params['fld_key_b']) = 
			sysVarCleanFromInput('fld_name', 'fld_module', 'fld_key_a','fld_key_b');

	// Проверка наличия модулей
	if (!sysModAvailable ('fields')) return;
	if (!sysModAvailable ($params['fld_module'])) return;

	//Проверки
	$params['fld_key_a'] = (int)$params['fld_key_a'];
	$params['fld_key_b'] = (int)$params['fld_key_b'];
	if ($params['fld_key_a']<1 && $params['fld_key_b']<1) return;

	//Проверка доступа
	$secObject = 'fields::ajax::values::view::'.$params['fld_module'].'::'.$params['fld_key_a'].'::'.$params['fld_key_b'];
	if (!sysSecAuthAction($secObject, ACCESS_READ)) return '';

	//Загружаем класс тем
	$sysTpl = new sysTpl;
	$sysTpl->caching = false;

	//Смотрим может модуль переопределил шаблон
	$sysObject = $params['fld_module'].'::user::fields::values::view';
	if ($params['fld_key_a']) $sysObject.='::'.$params['fld_key_a'];
	if ($params['fld_key_b']) $sysObject.='::'.$params['fld_key_b'];
	$sysModTpl = sysTplWay ($sysObject);

  //Если нет - берем наш шаблон
  if (!$sysModTpl)  {
    $sysObject = $secObject;
    $sysModTpl = sysTplWay ($sysObject);
    }


	//Загружаем модуль работы с отзывами
	sysModClassLoad ('fields');
	$fields = new fields;
  
	//Список значений полей
	$fields_values = $fields->get_values($params['fld_module'], $params['fld_name'], $params['fld_key_a'], $params['fld_key_b']);
	$sysTpl->assign('fields_values', $fields_values);

	//Уровень доступа у текущего пользователя
	$curuser_sec_level = sysSecGetLevel ($secObject);
	$sysTpl->assign('curuser_sec_level', $curuser_sec_level);

	//Ид текущего пользователя
	$sysTpl->assign('curuser_id', sysSessionGetVar('user_id'));

	//Реднерим
	$sysTpl->assign('fields_values', $fields_values);
	$sysTpl->assign($params);

	//Вывод результатов
	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

/*
 * Список начений  полей для объекта для редактирования
*/

function fields_ajax_values_modify($params=null)  {
  
	//Если не переданны параметры, собираем совхода
	if (empty($params))  
		list ($params['fld_name'], $params['fld_module'], $params['fld_key_a'], $params['fld_key_b']) = 
			sysVarCleanFromInput('fld_name', 'fld_module', 'fld_key_a','fld_key_b');
	

	// Проверка наличия модулей
	if (!sysModAvailable ('fields')) return;
	if (!sysModAvailable ($params['fld_module'])) return;

	//Проверки
	$params['fld_key_a'] = (int)$params['fld_key_a'];
	$params['fld_key_b'] = (int)$params['fld_key_b'];
	if ($params['fld_key_a']<1 && $params['fld_key_b']<1) return;

	//Проверка доступа
	$secObject = 'fields::ajax::values::modify::'.$params['fld_module'].'::'.$params['fld_key_a'].'::'.$params['fld_key_b'];
	if (!sysSecAuthAction($secObject, ACCESS_READ)) return '';

	//Загружаем класс тем
	$sysTpl = new sysTpl;
	$sysTpl->caching = false;

	//Смотрим может модуль переопределил шаблон
	$sysObject = $params['fld_module'].'::admin::fields::values::modify';
	if ($params['fld_key_a']) $sysObject.='::'.$params['fld_key_a'];
	if ($params['fld_key_b']) $sysObject.='::'.$params['fld_key_b'];
	$sysModTpl = sysTplWay ($sysObject);

  //Если нет - берем наш шаблон
  if (!$sysModTpl)  {
    $sysObject = $secObject;
    $sysModTpl = sysTplWay ($sysObject);
    }


	//Загружаем модуль
	sysModClassLoad ('fields');
	$fields = new fields;
  
	//Список значений полей
	$field_list = $fields -> get_list($params['fld_module'], $params['fld_name'], $params['fld_key_a'], $params['fld_key_b']);
	$sysTpl->assign('field_list', $field_list);


	//Уровень доступа у текущего пользователя
	$curuser_sec_level = sysSecGetLevel ($secObject);
	$sysTpl->assign('curuser_sec_level', $curuser_sec_level);

	//Ид текущего пользователя
	$sysTpl->assign('curuser_id', sysSessionGetVar('user_id'));

	//Реднерим
	$sysTpl->assign($params);

	//Вывод результатов
	$result['object'] = $sysObject;
	$result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

function fields_ajax_values_update()  {
  
	//Забираем переменные со входа
	list($fld_modname, $fld_key_a, $fld_key_b) = sysVarCleanFromInput("fld_modname", "fld_key_a", "fld_key_b");
	$fld = $_POST['fld'];
	
	// Проверка наличия модулей
	if (!sysModAvailable ('fields')) return;
	if (!sysModAvailable ($fld_modname)) return;
	

	//Строим имя обьекта
	$sysObject = 'fields::ajax::values::update::'.$fld_modname;
	if ($fld_key_a) $sysObject.='::'.$fld_key_a;
	if ($fld_key_b) $sysObject.='::'.$fld_key_b;
	
	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_EDIT)) return;

	//загружаем модулю которому адресовано поле и смотрим, есть ли кому обработать - плагин
	sysExtLibLoad('pluginslib');
	$sysPlugins = new sysPlugins();


	//Пробуем передать управление модулю которому адресован коммент для пред обработки
	$sysPlugins ->plugin_exe($fld_modname,'fields','values_update', array('fld_module' => $fld_modname,'fld_key_a' => $fld_key_a, 'fld_key_b' => $fld_key_b, 'fld' => $fld));
	
	sysRedirect($_SERVER['HTTP_REFERER']);
	exit;
}



/*
 * фильтр по полю
*/
function fields_ajax_filter($params=null)  {
  
	//Если не переданны параметры, собираем совхода
	if (empty($params))  
		list ($params['fld_module'], $params['id_field'], $params['fld_value']) = sysVarCleanFromInput('fld_module', 'id_field', 'fld_value');

	// Проверка наличия модулей
	if (!sysModAvailable ('fields')) return;
	if (isset($params['fld_module']) && !sysModAvailable ($params['fld_module'])) return;

	//Проверка доступа
	$secObject = 'fields::ajax::filter::'.$params['fld_module'].'::'.$params['id_field'].'::'.$params['fld_value'];
	if (!sysSecAuthAction($secObject, ACCESS_READ)) return '';

	//Загружаем класс тем
	$sysTpl = new sysTpl;
	$sysTpl->caching = false;

	//Смотрим может модуль переопределил шаблон
	$sysObject = $params['fld_module'].'::user::fields::filter';
	$sysModTpl = sysTplWay ($sysObject);

	//Если нет - берем наш шаблон
	if (!$sysModTpl)  {
		$sysObject = $secObject;
		$sysModTpl = sysTplWay ($sysObject);
   }


	//Загружаем модуль работы с отзывами
	sysModClassLoad ('fields');
	$fields = new fields;
  
	//Список объектов, к которым привязано это поле
	$objects_list = $fields->get_objects($params['fld_module'],  $params['id_field'], $params['fld_value']);
	
	foreach ($objects_list as $k => $obj) {
		sysModClassLoad ($obj['fld_module']);
		$tomodule = new $obj['fld_module'];
		$info = $tomodule->plugin_exe('fields','object_info', array('fld_key_a' => $obj['fld_key_a'], 'fld_key_b' => $obj['fld_key_b']));
		if ($info) {
			$info['module'] = $obj['fld_module'];
			$objects_list[$k] = $info;
		}
		else unset($objects_list[$k]);
	}
	$sysTpl->assign('objects_list', $objects_list);

	//Уровень доступа у текущего пользователя
	$curuser_sec_level = sysSecGetLevel ($secObject);
	$sysTpl->assign('curuser_sec_level', $curuser_sec_level);

	//Ид текущего пользователя
	$sysTpl->assign('curuser_id', sysSessionGetVar('user_id'));

	//Реднерим
	$sysTpl->assign($params);

	//Вывод результатов
	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	echo $result['content']; exit;
	return $result;
}



?>