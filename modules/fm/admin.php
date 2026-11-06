<?php
/*
* Просмотр содержимого папки
*/
function fm_admin_main()
{
	//Прелюдие как у всех модулей
	$sysObject = 'fm::admin::main';
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;
  
	//Шаблоны
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject);
	$sysTpl->caching = false;
	
	$browsein = array();
	$browsein[] = array ( 'url' => sysModURL('fm', 'admin', 'main'), 'displayname' => 'Файлы' );
  
	//Возвращаем результат
	$result['object'] =& $sysObject;
	$result['browsein'] =& $browsein;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

/*
* Редактирование конфигурации файлового менеджера
*/
function fm_admin_modifyconfig()
{
	//Прелюдие как у всех модулей
	$sysObject = 'fm::admin::modifyconfig';
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;
  
	//Шаблоны
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject);
	$sysTpl->caching = false;
  
	//Вспоминаем переменные
	$sysTpl->assign('uploadMaxSize', sysModGetVar('fm','uploadMaxSize'));
	
	$uploadDeny = implode("\n", unserialize(sysModGetVar('fm','uploadDeny')));
	$sysTpl->assign('uploadDeny', $uploadDeny);
	
	$uploadAllow = implode("\n", unserialize(sysModGetVar('fm','uploadAllow')));
	$sysTpl->assign('uploadAllow', $uploadAllow);

	$sysTpl->assign('tmbSize', sysModGetVar('fm', 'tmbSize'));
	
	$browsein = array();
	$browsein[] = array ( 'url' => sysModURL('fm', 'admin', 'main'), 'displayname' => 'Файлы' );
	$browsein[] = array ( 'url' => sysModURL('fm', 'admin', 'modifyconfig'), 'displayname' => 'Настройки' );
  
	//Откуда пришел
	$http_referer = $_SERVER['HTTP_REFERER'];
	$sysTpl->assign('http_referer', $http_referer);
  
	//Возвращаем результат
	$result['object'] =& $sysObject;
	$result['browsein'] =& $browsein;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

/*
* Копирование файла
*/
function fm_admin_updateconfig()
{
	//Прелюдие как у всех модулей
	$sysObject = 'fm::admin::updateconfig';
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;
  
	//Забираем переменные со входа
	list ($uploadMaxSize, $uploadDeny, $uploadAllow, $tmbSize) = sysVarCleanFromInput('uploadMaxSize', 'uploadDeny', 'uploadAllow', 'tmbSize');
  
	sysModSetVar ('fm', 'uploadMaxSize', $uploadMaxSize);

	$uploadDeny = explode("\n", $uploadDeny);
	foreach ($uploadDeny as $k=>$m) {
		$uploadDeny[$k] = trim($m);
		if (!$uploadDeny[$k]) 
			unset($uploadDeny[$k]);
	}
	$uploadDeny = serialize($uploadDeny);
	sysModSetVar ('fm', 'uploadDeny', $uploadDeny);
	
	$uploadAllow = explode("\n", $uploadAllow);
	foreach ($uploadAllow as $k=>$m) {
		$uploadAllow[$k] = trim($m);
		if (!$uploadAllow[$k]) 
			unset($uploadAllow[$k]);
	}
	$uploadAllow = serialize($uploadAllow);
	sysModSetVar ('fm', 'uploadAllow', $uploadAllow);
	
	sysModSetVar ('fm', 'tmbSize', $tmbSize);

	//Переходим на страницу откуда были вызванны
    sysShowMessage(sysModURL ('fm','admin','modifyconfig'), 'Изменения сохранены');

}









?>