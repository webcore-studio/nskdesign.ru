<?php 


//Инициализировали статистику
$mtime = explode(' ',microtime());
$dbg_starttime = $mtime[1] + $mtime[0];
global $dbg_sql_queries;
$dbg_sql_queries = array();


//Инициализировали стистему
include 'kernel/API.php';

sysInit();


// Проверка может ip пользователя забанен ?
$isIpBanned = sysIsIpBanned ();
$user_theme = sysUserTheme();//Тема

if ($isIpBanned)  {
	$pageTpl = new sysTpl;
	$pageTpl->caching = false;
	$pageTplFile = 'themes/'.$user_theme.'/pages/ipbanned.tpl';
	$pagecontent = $pageTpl->fetch($pageTplFile);
	echo ($pagecontent);
	sysTerminate ();
	ob_end_flush();
	exit;
}

// TODO: Проверка может сайт временно закрыт ?
$isSiteClosed = sysIsSiteClosed ();
if ($isSiteClosed) {
	$closesite_key = sysModGetVar("SYS_config", "accesskey");
	
	$access_key = sysVarCleanFromInput('access_key');
	if ($access_key)
		sysSessionSetVar("access_key", $access_key);
	else
		$access_key = sysSessionGetVar("access_key");
		
	if ($access_key != $closesite_key) 	{
		sysRedirect("/index.html");
	}
}

// Забрали переменные со входа о модуле функции и типе
list($module,  $func,   $type) = sysVarCleanFromInput('module',   'func', 'type');

if (empty($module))  {
  $module= sysModGetVar('SYS_config', 'startpage');
}

if (empty($type))  {
  $type = 'user';
}

if (empty($func))  {
  $func = "main";
}

//Если модуль есть отображаем модуль
if (!empty($module))  {
  
	//Грузим модуль
	$loadedmod = sysModLoad($module, $type);
	if (empty($loadedmod))    {
		// Ошибка при загрузке модуля
		sysException ('index.php', MODULE_NOT_EXIST, 'Ошибка загрузки модуля '. $module);
   }

	//Запускаем функцию
	//Возможны 3 состояния empty = доступ запрешен, массив = модуль нормально отработал, любое другое - ошибка
	$modresult = sysModFunc($module, $type, $func);
	if (!isset($modresult))    {
		sysException ('index.php', NO_PERMISSION, 'Не хватает прав для доступа к запрошенной странице '.sysGetCurrURI());
   }
   elseif (!is_array($modresult))  {
		sysException ('index.php', MODULE_FUNCTION_NOT_EXIST, 'Ошибка выполнения функции модуля '. $func);
   }
}
else  {//Пустая страница
    
	$modresult = array();
	$modresult['object'] = 'MainPage';
}

$curuser_sec_level = sysSecGetLevel ($modresult['object']);

$modresult['module'] = $module;
$modresult['type'] = $type;
$modresult['func'] = $func;

$modresult['user_theme'] = $user_theme;

//проверяем кодировку
$encoding = sysEncoding();
$modresult['encoding'] = $encoding;


//Выводим результат работы
if ($type=='ajax' || $type=='rss' || $type=='pdf')  {
	$pagecontents = $modresult['content'];
	if ($type == 'rss') 
		header('Content-type: application/xml');
	echo $pagecontents;
}
else   {
   sysShowModPage($modresult);
}
// Если режим отладки, етаг не используем
$debug = sysModGetVar('SYS_config','debug');

//Все выключение системы
sysTerminate ();

// Отладочьная информация
global $dbg_starttime;
$mtime = explode(' ',microtime());
$dbg_endtime = $mtime[1] + $mtime[0];
$dbg_totaltime = ($dbg_endtime - $dbg_starttime);

$log_str = date("d.m.Y H:i:S")."\t".$_SERVER['REQUEST_URI']."\t".str_replace(".",",",$dbg_totaltime)."\r\n";
$time_log = sysModGetVar("SYS_config", "time_log");
if ($time_log)
	error_log ($log_str, 3, 'var/log/loadtime_'.date("d_m").'.log');

if ($type!='ajax' && $type!='rss' && $type!='pdf') {
	printf("<!-- Время создания страницы %f сек.-->", $dbg_totaltime);
	
	if ($curuser_sec_level>=70) 
		echo "<!--sysObject - $modresult[object]-->";

	if ($debug)  {
		echo "<!--";
		echo "SQL queries - <pre>";
		print_r($dbg_sql_queries);
		echo "</pre>-->";
	}
}

//Закрываем "кеш" вывода
ob_end_flush();

?>