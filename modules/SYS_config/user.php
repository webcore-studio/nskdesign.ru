<?php
function SYS_config_user_main()  {
	sysRedirect(sysModURL('SYS_config','admin','main'));
}

// Страница ошибки
function SYS_config_user_errorpage_view($args = array())  {
	
	//Забираем переменные со входа
	$page = $args['page'];
	if (!isset($page))
		$page = sysVarCleanFromInput('page');
  
	//Прелюдие как у всех модулей
	$sysObject = 'SYS_config::user::errorpage::view';
	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

	$sysTpl = new sysTpl;
	$sysModTpl=sysTplWay ($sysObject);
	$sysTpl->caching = false;
	sysLoadLang($sysTpl, 'SYS_config', 'module');

	if ($page == '404') {
		$text =  sysModGetVar('SYS_config','page404');
		$title = $sysTpl->get_config_vars('page404');
	}	else {
		$text = sysModGetVar('SYS_config','page_error');
		$title = $sysTpl->get_config_vars('page_error');
	}

	$sysTpl->assign('text', $text);
	
	$url = sysSessionGetVar('url');
	$message = sysSessionGetVar('message');
	$excType = sysSessionGetVar('excType');
	$sysTpl->assign('url', $url);
	$sysTpl->assign('message', $message);
	$sysTpl->assign('excType', $excType);
	
	sysSessionDelVar('url');
	sysSessionDelVar('message');
	sysSessionDelVar('excType');
	
	$logvars = array('url' => $url, 'excType' => $excType, 'page' => $page, 'message'=>$message);
	
	if ($page == '404') {
		sys_log ('SYS_config_user_errorpage_view', LOG_INFO, 'Error loading page', $logvars, '404');
		
	}	else {
		
		sys_log ('SYS_config_user_errorpage_view', LOG_INFO, 'Error loading page', $logvars, 'SYS_config');
	}
	

	//BrowseIn
	$browsein[]=array ('url' => '', 'displayname' => $title);
	$sysTpl->assign('browsein', $browsein);

	//Возвращаем результат
	$result['object'] = $sysObject;
	$result['browsein'] = $browsein;
	$result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
	$result['meta_title'] = $title;
	return $result;
}
?>
