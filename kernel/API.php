<?php
/*
 * Версия движка
 */
define('SYSTEM_VERSION_NUM', '0.99');
define('SYSTEM_VERSION_ID', 'Webcore');
define('SYSTEM_VERSION_SUB', 'RedShtorm');
/*
 * Состояние модулей
 */
define('MODULE_STATE_NA', 0);
define('MODULE_STATE_UNINITIALISED', 10);
define('MODULE_STATE_INACTIVE', 20);
define('MODULE_STATE_ACTIVE', 30);
define('MODULE_STATE_MISSING', 40);
define('MODULE_STATE_UPGRADED', 50);
/*
 * Уровни доступа
 */
define('ACCESS_INVALID', -1);
define('ACCESS_NONE', 0);
define('ACCESS_OVERVIEW', 10);
define('ACCESS_READ', 20);
define('ACCESS_COMMENT', 30);
define('ACCESS_ADD', 40);
define('ACCESS_EDIT', 50);
define('ACCESS_DELETE', 60);
define('ACCESS_ADMIN', 70);
/*
 * Типы исключительных ситуаций
 */
define('BAD_PARAM', 1);
define('DATABASE_ERROR', 2);
define('ID_NOT_EXIST', 3);
define('NO_PERMISSION', 4);
define('MODULE_NOT_EXIST', 5);
define('MODULE_FILE_NOT_EXIST', 6);
define('MODULE_FUNCTION_NOT_EXIST', 7);
define('BLOCK_NOT_EXIST', 8);
define('BLOCK_FILE_NOT_EXIST', 9);
define('BLOCK_FUNCTION_NOT_EXIST', 10);
/*
 * Зарезирвированные глобальные переменные
 * sysVar_cache - кеширование переменных
 * sysConfig - базовые настройки системы
 */
require_once('kernel/smarty4.5.5/Smarty.class.php');
require_once('kernel/mysql_compat.php');

/*
 * Херня потом подправлю
 */
if (phpversion() >= "4.2.0") {
  if (ini_get('register_globals') != 1) {
    $supers = array(
      '_REQUEST',
      '_ENV',
      '_SERVER',
      '_POST',
      '_GET',
      '_COOKIE',
      //                                '_SESSION',
      '_FILES',
      '_GLOBALS'
    );

    foreach ($supers as $__s) {
      if ((isset($$__s) == true) && (is_array($$__s) == true))
        extract($$__s, EXTR_OVERWRITE);
    }
    unset($supers);
  }
} else {
  if (ini_get('register_globals') != 1) {

    $supers = array(
      'HTTP_POST_VARS',
      'HTTP_GET_VARS',
      'HTTP_COOKIE_VARS',
      'GLOBALS',
      //                                'HTTP_SESSION_VARS',
      'HTTP_SERVER_VARS',
      'HTTP_ENV_VARS'
    );

    foreach ($supers as $__s) {
      if ((isset($$__s) == true) && (is_array($$__s) == true))
        extract($$__s, EXTR_OVERWRITE);
    }
    unset($supers);
  }
}

/**
 * @return bool
 * @desc Инициаллизация системы
 */
function sysInit()
{
  //Грузим языковые константы ядра
  include_once 'lang/rus/kernel.php';

  // Initialise and load configuration
  global $sysConfig, $dbconn;
  $sysConfig = array();
  include_once 'var/configs/system.php';

  // Установки значений PHP
  if ($sysConfig['locale.lc_all'])
    setlocale(LC_ALL, $sysConfig['locale.lc_all']);
  else
    setlocale(LC_ALL, 'ru_RU');
  ignore_user_abort(true);

  // Если нужно декодируем пароль и логин из конфига
  if ($sysConfig['DB.Encoded']) {
    $sysConfig['DB.UserName'] = base64_decode($sysConfig['DB.UserName']);
    $sysConfig['DB.Password'] = base64_decode($sysConfig['DB.Password']);
    $sysConfig['DB.Encoded'] = 0;
  }





  //Иницмализируем БД
  if (!sysDBInit()) {
    die(_DBINITFAILED);
  }



  // sys_log('sysInit 1', LOG_INFO, "dbconn", $dbconn);



  // Используем gzip зжатие если не кто не против
  if (sysModGetVar('SYS_config', 'gzip_compression')) {
    $accept_encoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? ($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'] ?? '');
    if (!empty($accept_encoding) && strstr($accept_encoding, 'gzip')) {
      if (extension_loaded('zlib')) {
        ob_start('ob_gzhandler');
        header("Content-Encoding: gzip");
      } else {
        ob_start();
      }
      ;
    } else {
      ob_start();
    }
    ;
  } else {
    ob_start();
  }
  ;

  //устанавливаем заявленную кодировку
  if ($sysConfig['DB.SetNames'] == 'utf-8')
    header("Content-Type: text/html; charset=utf-8");
  else
    header("Content-Type: text/html; charset=utf-8");



  //sys_log('sysInit ', LOG_DEBUG, "SetNames", $sysConfig['DB.SetNames']);

  //header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT"); // Date in the past
  //header("Cache-Control: private, no-cache, must-revalidate"); // HTTP/1.1

  //если не забаненен, то начали сессию
  $isIpBanned = sysIsIpBanned();
  if ($isIpBanned) {
    echo ('Ваш IP находится в черном списке');
    exit;
  }
  sys_log('sysInit 2', LOG_INFO, "до сессии", $dbconn);
  // Начали ссесию
  if (!sysSessionSetup()) {
    die(_SESSINITFAILED);
  }
  if (!sysSessionInit()) {
    die(_SESSINITFAILED);
  }

  sys_log('sysInit 2', LOG_INFO, "Прошли сессии", $dbconn);

  //  header("Cache-Control: private, no-cache, must-revalidate"); // HTTP/1.1
  if (!sysUserIsSpider())
    header("Cache-Control: private, must-revalidate"); // HTTP/1.1

  //Настройки пхп отладки
  $debug = sysModGetVar('SYS_config', 'debug');
  if ($debug) {
    error_reporting(E_ALL ^ E_NOTICE);
  } else {
    error_reporting(E_ERROR);
  }
  ;
  //*/
  return true;
}

/*
 * Выключение системы
 */
function sysTerminate()
{
  if (function_exists('session_write_close')) {
    session_write_close();
  }
  //Збрасываем кеши на диск
  sysVarFlushCached();
}
;

/*
 * Забрать переменную из экранной формы
 */
function sysVarCleanFromInput()
{
  $search = array(
    '|</?\s*SCRIPT.*?>|si',
    '|</?\s*FRAME.*?>|si',
    '|</?\s*OBJECT.*?>|si',
    '|</?\s*META.*?>|si',
    '|</?\s*APPLET.*?>|si',
    '|</?\s*LINK.*?>|si',
    '|</?\s*IFRAME.*?>|si',
    '|STYLE\s*=\s*"[^"]*"|si'
  );

  $replace = array('');

  $resarray = array();
  foreach (func_get_args() as $var) {
    // забираем переменную
    global $$var;
    if (empty($var)) {
      return;
    }
    $ourvar = $$var;
    if (!isset($ourvar)) {
      array_push($resarray, NULL);
      continue;
    }
    if (empty($ourvar)) {
      array_push($resarray, $ourvar);
      continue;
    }

    // Очистка переменной  с PHP 7.4.0 и была УДАЛЕНА
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
      sysStripslashes($ourvar);
    }
    //      if (!sysSecAuthAction('MainPage', ACCESS_ADMIN))
//        {
    $ourvar = preg_replace($search, $replace, $ourvar);
    //        }

    // Добавляем в выходной массив

    array_push($resarray, $ourvar);
  }
  // Возврашаем масив или значение
  if (func_num_args() == 1) {
    return $resarray[0];
  } else {
    return $resarray;
  }
}

/*
 * Забрать переменную из экранной формы (без очистки)
 */
function sysVarGetFromInput()
{
  $resarray = array();
  foreach (func_get_args() as $var) {
    // забираем переменную
    global $$var;
    if (empty($var)) {
      return;
    }
    $ourvar = $$var;
    if (!isset($ourvar)) {
      array_push($resarray, NULL);
      continue;
    }
    if (empty($ourvar)) {
      array_push($resarray, $ourvar);
      continue;
    }

    // Очистка переменной  с PHP 7.4.0 и была УДАЛЕНА
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
      sysStripslashes($ourvar);
    }
    ;

    // Добавляем в выходной массив

    array_push($resarray, $ourvar);
  }

  // Возврашаем масив или значение
  if (func_num_args() == 1) {
    return $resarray[0];
  } else {
    return $resarray;
  }
}


/*
 * Strip slashes используеться в sysVarCleanFromInput
 */
function sysStripslashes(&$value)
{
  if (!is_array($value)) {
    $value = stripslashes($value);
  } else {
    array_walk($value, 'sysStripslashes');
  }
}

/*
 * Подготовка переменной для БД
 */
function sysVarPrepForStore()
{
  $resarray = array();
  // global $dbconn;



  //sys_log('API', LOG_INFO, "sysVarPrepForStore ", func_get_args());

  foreach (func_get_args() as $ourvar) {
    // sys_log('API', LOG_INFO, "sysVarPrepForStore0 ourvar", $ourvar);

    // Собственно подготовка функция УСТАРЕЛА, начиная с PHP 7.4.0 и была УДАЛЕНА
    if (true) {
      //$ourvar = addslashes($ourvar);
      //sys_log('API', LOG_INFO, "sysVarPrepForStore1 ourvar", $ourvar);

      //$ourvar = mysqli_real_escape_string($dbconn, $ourvar);

      //$ourvar = dbconn::real_escape_string($ourvar);
      //sys_log('API', LOG_INFO, "sysVarPrepForStore2 ourvar", $ourvar);

      // sys_log('API', LOG_INFO, "sysVarPrepForStore dbconn->server_info", $dbconn->server_info);


    }

    //sys_log('API', LOG_INFO, "sysVarPrepForStore3 ourvar", $ourvar);
    // Добавляем в массив
    array_push($resarray, $ourvar);
  }
  //*/



  // Возврашаем
  if (func_num_args() == 1) {

    return $resarray[0];

    //sys_log('API', LOG_INFO, "sysVarPrepForStore resarray[0]", $resarray[0]);
  } else {


    return $resarray[0];


    //sys_log('API', LOG_INFO, "sysVarPrepForStore resarray", $resarray);
  }
}

/*
 * Подготовка для операционной системы
 */
function sysVarPrepForOS()
{
  static $search = array(
  '!\.\./!si', // .. (directory traversal)
  '!^.*://!si', // .*:// (start of URL)
  '!/!si',     // Forward slash (directory traversal)
  '!\\\\!si'
  ); // Backslash (directory traversal)

  static $replace = array(
  '',
  '',
  '_',
  '_'
  );

  $resarray = array();
  foreach (func_get_args() as $ourvar) {
    // Parse out bad things
    $ourvar = preg_replace($search, $replace, $ourvar);

    // Prepare var функция УСТАРЕЛА, начиная с PHP 7.4.0 и была УДАЛЕНА
    if (true) {
      $ourvar = addslashes($ourvar);
    }

    // Add to array
    array_push($resarray, $ourvar);
  }

  // Return vars
  if (func_num_args() == 1) {
    return $resarray[0];
  } else {
    return $resarray;
  }
}

/*
 * Транслитируем переменную
 */
function sysVarTranslit($var)
{
  $NpjLettersFrom = "абвгдезиклмнопрстуфцы";
  $NpjLettersTo = "abvgdeziklmnoprstufcy";
  $NpjBiLetters = array(
    "й" => "j",
    "ё" => "jo",
    "ж" => "zh",
    "х" => "h",
    "ч" => "ch",
    "ш" => "sh",
    "щ" => "sh",
    "э" => "e",
    "ю" => "ju",
    "я" => "ja",
    "ъ" => "",
    "ь" => ""
  );

  $NpjCaps = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЪЫЭЮЯ";
  $NpjSmall = "абвгдеёжзийклмнопрстуфхцчшщьъыэюя";

  $converter = array(
    'а' => 'a',
    'б' => 'b',
    'в' => 'v',
    'г' => 'g',
    'д' => 'd',
    'е' => 'e',
    'ё' => 'e',
    'ж' => 'zh',
    'з' => 'z',
    'и' => 'i',
    'й' => 'y',
    'к' => 'k',
    'л' => 'l',
    'м' => 'm',
    'н' => 'n',
    'о' => 'o',
    'п' => 'p',
    'р' => 'r',
    'с' => 's',
    'т' => 't',
    'у' => 'u',
    'ф' => 'f',
    'х' => 'h',
    'ц' => 'c',
    'ч' => 'ch',
    'ш' => 'sh',
    'щ' => 'sch',
    'ь' => '',
    'ы' => 'y',
    'ъ' => '',
    'э' => 'e',
    'ю' => 'yu',
    'я' => 'ya',

    'А' => 'A',
    'Б' => 'B',
    'В' => 'V',
    'Г' => 'G',
    'Д' => 'D',
    'Е' => 'E',
    'Ё' => 'E',
    'Ж' => 'Zh',
    'З' => 'Z',
    'И' => 'I',
    'Й' => 'Y',
    'К' => 'K',
    'Л' => 'L',
    'М' => 'M',
    'Н' => 'N',
    'О' => 'O',
    'П' => 'P',
    'Р' => 'R',
    'С' => 'S',
    'Т' => 'T',
    'У' => 'U',
    'Ф' => 'F',
    'Х' => 'H',
    'Ц' => 'C',
    'Ч' => 'Ch',
    'Ш' => 'Sh',
    'Щ' => 'Sch',
    'Ь' => '',
    'Ы' => 'Y',
    'Ъ' => '',
    'Э' => 'E',
    'Ю' => 'Yu',
    'Я' => 'Ya',
  );

  $var = str_replace(".php", "", $var);
  $var = trim(strip_tags($var));

  $var = preg_replace("/\s+/ms", "-", $var);
  //sys_log('0-sysVarTranslit', LOG_DEBUG, "var", $var);

  $var = strtolower($var);
  //sys_log('1-sysVarTranslit', LOG_DEBUG, "var", $var);

  //$var = strtr( $var, $NpjLettersFrom, $NpjLettersTo );
  //sys_log('2-sysVarTranslit', LOG_DEBUG, "var", $var);

  $var = strtr($var, $converter);


  $var = preg_replace("/[^a-z0-9\_\-.]+/mi", "", $var);
  $var = preg_replace('#[\-]+#i', '-', $var);
  $var = strtolower($var);

  if (strlen($var) > 64) {
    $var = substr($var, 0, 64);
    if (($temp_max = strrpos($var, '-')))
      $var = substr($var, 0, $temp_max);
  }

  sys_log('-sysVarTranslit', LOG_DEBUG, "var", $var);

  return $var;
}

/*
 * Начинаем эпопею работы с кешами переменных
 * Дополнительно к ключю храним параметры
 * $GLOBALS['sysVar_cache'][$cacheKey][syscache_need_flush]
 * $GLOBALS['sysVar_cache'][$cacheKey][syscache_key_loaded]
 */

/*
 * Кеширует переменную
 */
function sysVarSetCached($cacheKey, $name, $value = null)
{
  //с Emptyмы работать не умеем
  if (!isset($value)) {
    $value = false;
  }

  //Если можно Загружаем информацию о старом кеше
  if ($GLOBALS['sysConfig']['Var.caching'] == true) {
    sysVarLoadCached($cacheKey);
    //Флажочек что надо засадить на диск
    //echo "Saving<br>";
    $GLOBALS['sysVar_cache'][$cacheKey]['syscache_need_flush'] = true;
  }
  ;

  if (is_array($name)) {//Если масив то назначяем ключ- значение
    foreach ($name as $key => $val) {
      $GLOBALS['sysVar_cache'][$cacheKey][$key] = $val;
    }
  } else {
    $GLOBALS['sysVar_cache'][$cacheKey][$name] = $value;
  }
  ;
}

/*
 * Извликает переменную из кеша
 */
function sysVarGetCached($cacheKey, $name)
{
  if (!isset($GLOBALS['sysVar_cache'][$cacheKey][$name])) {
    //Если можно пытаемся загрузить с диска
    if ($GLOBALS['sysConfig']['Var.caching'] == true) {
      sysVarLoadCached($cacheKey);
    }
    ;
    if (isset($GLOBALS['sysVar_cache'][$cacheKey][$name])) {
      return $GLOBALS['sysVar_cache'][$cacheKey][$name];
    }
    ;
    return;
  }
  return $GLOBALS['sysVar_cache'][$cacheKey][$name];
}

/*
 * Проверяет наличие переменной в кеше
 */
function sysVarIsCached($cacheKey, $name)
{
  //sys_log('sysVarIsCached', LOG_INFO, "GLOBALS", $GLOBALS);



  if (!isset($GLOBALS['sysVar_cache'][$cacheKey][$name])) {
    //Если можно пытаемся загрузить с диска
    if ($GLOBALS['sysConfig']['Var.caching'] == true) {
      //   print_r($dbg_sql_queries);
      //   exit;
      //
      sysVarLoadCached($cacheKey);


    }
    ;
    if (isset($GLOBALS['sysVar_cache'][$cacheKey][$name])) {
      return true;
    }
    ;
    return false;
  }
  return true;
}

/*
 * Удаляем переменную из кеша
 */
function sysVarDelCached($cacheKey, $name = null)
{
  //Если можно пытаемся загрузить с диска
  if (!empty($GLOBALS['sysConfig']['Var.caching'])) {
    sysVarLoadCached($cacheKey);
  }
  ;
  //Два случая только переменную или весь $cacheKey
  if (!empty($name)) {
    if (isset($GLOBALS['sysVar_cache'][$cacheKey][$name])) {
      unset($GLOBALS['sysVar_cache'][$cacheKey][$name]);
      //Флажочек что надо засадить на диск
      $GLOBALS['sysVar_cache'][$cacheKey]['syscache_need_flush'] = true;
    }
  } else {//Удаляем весь кеш кей
    if (isset($GLOBALS['sysVar_cache'][$cacheKey])) {
      unset($GLOBALS['sysVar_cache'][$cacheKey]);
      //Флажочек что надо засадить на диск
      $GLOBALS['sysVar_cache'][$cacheKey]['syscache_need_flush'] = true;
    }
  }
  ;
}
;

/*
 * Загружаем кеш файл
 */
function sysVarLoadCached($cacheKey)
{


  // sys_log('sysVarLoadCached', LOG_INFO, "GLOBALS", $GLOBALS);

  /* 
 if ($GLOBALS['sysVar_cache'][$cacheKey]['syscache_key_loaded'])
   {
   //Если уже загруженн выходим
   return true;
   };

 $cachefile='cache/vars/'.$cacheKey.'.cache';
 if (file_exists($cachefile))
   {
   $cachecontent = implode ('', file ($cachefile));
   $cachecontent = unserialize($cachecontent);
   $GLOBALS['sysVar_cache'][$cacheKey]=$cachecontent;
   $GLOBALS['sysVar_cache'][$cacheKey]['syscache_key_loaded']=true;
   return true;
   }
 $GLOBALS['sysVar_cache'][$cacheKey]['syscache_key_loaded']=true;
 */
  return true;
}
;

/*
 * Сохраняем кеш файл на диск
 */
function sysVarFlushCached()
{
  //Если можно пытаемся записать кеша на диск
  if ($GLOBALS['sysConfig']['Var.caching'] == true && $GLOBALS['sysVar_cache']) {
    //Пробегаемся по всем кешам и кого надо флюшим
    foreach ($GLOBALS['sysVar_cache'] as $cacheKey => $cacheValue) {
      if ($GLOBALS['sysVar_cache'][$cacheKey]['syscache_need_flush'] == true) {
        //Юнсетим служебные флажки
        unset($cacheValue['syscache_need_flush']);
        unset($cacheValue['syscache_key_loaded']);

        $cachefile = 'cache/vars/' . $cacheKey . '.cache';
        $cachecontent = serialize($cacheValue);
        $fp = fopen($cachefile, "w");
        fwrite($fp, $cachecontent);
        fclose($fp);
        //Меняем права файла
        global $sysConfig;
        chmod($cachefile, $sysConfig['default.file.perms']);
        //        echo "Flushing $cachefile<br>";
      }
      ;
    }
    ;
  }
  ;
}

/*
 * Отображает все блоки $object, $position
 */
function sysBlockRenderAll(&$sysTpl, &$object)
{
  global $dbconn;
  //проверяем если есть в кеше возвращаем из кеша
  if (sysVarIsCached('blocks_list', 'dbblockslist')) {
    $dbblockslist = sysVarGetCached('blocks_list', 'dbblockslist');
  } else {//В кеш не попал список блоков
    $blocks_table = sysDBGetTable('blocks');
    $blocks_column = sysDBGetColumns($blocks_table);
    $sql = "SELECT * FROM $blocks_table FORCE INDEX (block_position) ORDER BY $blocks_column[block_position], $blocks_column[weight]";
    $sql_result = mysqli_query($dbconn, $sql);

    if (mysqli_connect_errno())
      sysException('sysBlockRenderAll', DATABASE_ERROR, mysqli_connect_error() . "<br> $sql");

    $dbblockslist = array();
    while ($row = mysqli_fetch_assoc($sql_result)) {
      $dbblockslist[] = $row;
    }
    ;
    sysVarSetCached('blocks_list', 'dbblockslist', $dbblockslist);
  }
  ;

  $blocks = array();
  $blocks['left'] = array();
  $blocks['right'] = array();
  $blocks['top'] = array();
  $blocks['bottom'] = array();
  $blocks['center'] = array();
  $blocks['advert'] = array();
  $blocks['exact'] = array();

  //Отображаем все блоки
  if (!empty($dbblockslist)) {
    foreach ($dbblockslist as $block) {
      //Нужны только активные
      if (!$block['block_active']) {
        continue;
      }
      ;

      //проверк если под маску не подходит нерендерим
      $pattern = "/" . $block['block_pattern'] . "/iU";
      if (!preg_match($pattern, $object)) {
        continue;
      }
      ;

      //В информацию о блоке добавляем - module_object
      $block['module_object'] = $object;
      //Рендерим блок
      $renblock = sysBlockRender($block);

      sys_log(
        'sysBlockRenderAll',
        LOG_DEBUG,
        "block",
        $block,
        "block"
      );

      sys_log(
        'sysBlockRenderAll',
        LOG_DEBUG,
        "renblock",
        $renblock,
        "block"
      );

      if (empty($renblock['block_content']))//Если нету content - значит ничево выводить ненадо
      {
        continue;
      }
      ;

      //В зависимости от положения
      switch ($block['block_position']) {
        case 'l'://Левые блоки
          array_push($blocks['left'], $renblock);
          break;
        case 'r':
          array_push($blocks['right'], $renblock);
          break;
        case 't':
          array_push($blocks['top'], $renblock);
          break;
        case 'b':
          array_push($blocks['bottom'], $renblock);
          break;
        case 'c':
          array_push($blocks['center'], $renblock);
          break;
        case 'a':
          $blocks['advert'][$renblock['id']] = $renblock;
          break;

        case 'e':
          $blocks['exact'][$renblock['id']] = $renblock;
          break;


      }

    }
    ;
  }
  ;

  //Загоняем в шаблон
  $sysTpl->assign('blocks', $blocks, false);
  return true;
}

/*
 * Рендерит блок
 */
function sysBlockRender(&$blockinfo)
{
  //Загрузили блок
  sysBlockLoad($blockinfo['block_name']);

  $displayfunc = "{$blockinfo['block_name']}_display";

  sys_log('sysBlockRender', LOG_DEBUG, "displayfunc", $displayfunc, "block");

  if (function_exists($displayfunc)) {
    $block_result = array();
    $block_result = $displayfunc($blockinfo);
    if (!empty($block_result['block_content'])) {
      $block_result['id'] = $blockinfo['id'];
      $block_result = array_merge($blockinfo, $block_result);
    }
    ;
    sys_log('sysBlockRender', LOG_DEBUG, "block_result", $block_result, "block");
    return $block_result;
  } else {
    //Блок не найден
    $result['block_displayname'] = sprintf(_BLOCKNFOUND_TITLE, $blockinfo['block_name']);
    $result['block_content'] = sprintf(_BLOCKNFOUND_CONTENT, $blockinfo['block_name']);
    return $result;
  }
  ;
}

/*
 * Загрузка блока
 */
function sysBlockLoad(&$blockname)
{
  static $loaded = array();
  if ($loaded[$blockname])
    return true;

  $FileName = 'blocks/' . $blockname . '/block.php';
  if (file_exists($FileName)) {
    include_once($FileName);
    $loaded[$blockname] = true;
    return true;
  } else {
    return false;
  }
  ;
}

/*
 * Получает переменную блока
 */
function sysBlockGetVar($block_id, $varname = null)
{
  global $dbconn;
  if (sysVarIsCached('blocks_list', 'dbblockslist')) {
    $dbblockslist = sysVarGetCached('blocks_list', 'dbblockslist');
  } else {//В кеш не попал список блоков
    $blocks_table = sysDBGetTable('blocks');
    $blocks_column = sysDBGetColumns($blocks_table);
    $sql = "SELECT * FROM $blocks_table ORDER BY {$blocks_column['block_position']}, {$blocks_column['weight']}";

    $sql_result = mysqli_query($dbconn, $sql);

    if (mysqli_connect_errno())
      sysException('sysBlockGetVar', DATABASE_ERROR, mysqli_connect_error());

    $dbblockslist = array();
    while ($row = mysqli_fetch_assoc($sql_result)) {
      $dbblockslist[] = $row;
    }
    ;
    sysVarSetCached('blocks_list', 'dbblockslist', $dbblockslist);
  }
  ;

  if (isset($dbblockslist)) {
    foreach ($dbblockslist as $block_date) {
      if ($block_date['id'] == $block_id) {
        $block_dbdate = $block_date;
        break;
      }
      ;
    }
    ;
  }
  ;

  if (empty($block_dbdate)) {
    return;
  }
  ;

  $block_vars = unserialize($block_dbdate['block_content']);

  if (!empty($varname)) {
    return $block_vars[$varname];
  } else {
    return $block_vars;
  }
  ;
}
;

/*
 * Устанавливаем переменную блока
 */
function sysBlockSetVar($block_id, $varname, $varvalue)
{
  $block_vars = sysBlockGetVar($block_id);
  $block_vars[$varname] = $varvalue;

  $block_post_content = serialize($block_vars);

  $blocks_table = sysDBGetTable('blocks');
  $blocks_column = sysDBGetColumns($blocks_table);
  sysDbUpdate($blocks_table, array('block_content' => $block_post_content), "WHERE $blocks_column[id]='$block_id'");

  //Чистим кеш блоков
  sysVarDelCached('blocks_list', 'dbblockslist');
  return true;
}
;

/*
 * Удаляем переменную блока
 */
function sysBlockDelVar($block_id, $varname)
{
  $block_vars = sysBlockGetVar($block_id);
  unlink($block_vars[$varname]);
  $block_post_content = serialize($block_vars);

  $blocks_table = sysDBGetTable('blocks');
  $blocks_column = sysDBGetColumns($blocks_table);
  sysDbUpdate(
    $blocks_table,
    $blocks_column,
    "WHERE $blocks_column[id]=$block_id",
    array('block_content' => $block_post_content)
  );
  //Чистим кеш блоков
  sysVarDelCached('blocks_list', 'dbblockslist');
  return true;
}
;




/**
 * @return bool
 * @desc Инициализируем базу данных
 */
function sysDBInit()
{
  global $sysConfig;
  $dbhost = $sysConfig['DB.Host'];
  $dbname = $sysConfig['DB.Name'];
  $dbuname = $sysConfig['DB.UserName'];
  $dbpass = $sysConfig['DB.Password'];

  // Database connection is a global (for now)
  global $dbconn;

  //  sys_log('sysDBInit0', LOG_DEBUG, "sysConfig", $sysConfig);

  // Start connection
  if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
  }
  $dbconn = new mysqli($dbhost, $dbuname, $dbpass, $dbname);

  //sys_log('sysDBInit1', LOG_DEBUG, "dbconn ", $dbconn);

  /* Установка кодировки по умолчанию */
  $dbconn->set_charset('utf8');
  // mysql_set_charset ($sysConfig['DB.SetNames']);

  if ($dbconn == false)
    return false;

  //создаем экземляр класса PHP-MySQLi-Database-Class для плавного перехода // Затычка
  global $db;
  $db = $dbconn;
  if (mysqli_errno($dbconn) != 0)
    return false;

  global $pdo;
  $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuname, $dbpass, array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ));

  //Инициируем статистику
//  global $dbg_sql_queries;
//  $dbg_sql_queries = array();

  //sys_log('sysDBInit', LOG_DEBUG, "dbconn", $dbconn);

  return true;
}

/**
 * @return string
 * @desc возвращает кодировку
 */
function sysEncoding()
{
  global $sysConfig;
  return $sysConfig['DB.SetNames'];
}
;

/*
 * Возврашает полное название имени таблицы
 */
function sysDBGetTable($tablename)
{

  global $sysConfig;
  $prefix = $sysConfig['DB.TablePrefix'];
  return ($prefix . '_' . $tablename);
}

/*
 * Возврашает асоциативный массив имен полей
 */
function sysDBGetColumns($tablename)
{
  global $dbconn;
  if (!($dbconn instanceof mysqli)) {
    sysException('sysDBGetColumns', DATABASE_ERROR, 'Нет соединения с базой данных');
  }

  // Читаем реальную структуру: метаданные при установке могут быть неполными.
  $quotedTable = '`' . str_replace('`', '``', $tablename) . '`';
  $fields = mysqli_query($dbconn, "SHOW COLUMNS FROM $quotedTable");
  if ($fields === false) {
    sysException('sysDBGetColumns', DATABASE_ERROR, mysqli_error($dbconn));
  }

  $newcolumns = array();
  while ($row = mysqli_fetch_assoc($fields)) {
    $newcolumns[$row['Field']] = $quotedTable . '.`' . str_replace('`', '``', $row['Field']) . '`';
  }
  mysqli_free_result($fields);
  sysVarSetCached('columns', $tablename, $newcolumns);
  return $newcolumns;
}

/*
 * Выборка из бызы
 * Возвращает массив в формате key=>value
 */
function sysDBSelect($systable, $column, $where = '', $multi = false)
{

  sys_log('DEPRECATED', LOG_WARNING, "Используется устаревшая sysDBSelect()", debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1] ?? []);

  $mtm = explode(' ', microtime());
  $starttime = $mtm[1] + $mtm[0];
  global $dbconn;
  $sql = '';

  // подготавливаем $column строку для выборки
  $columns_string = '';
  foreach ($column as $col) {
    $columns_string .= $col . ', ';
  }
  $columns_string = rtrim($columns_string, ', ');
  if (empty($columns_string))
    sysException('sysDBSelect', BAD_PARAM, 'Columns list empty ' . $columns_string);

  //Делаем выборку
  $sql = "SELECT $columns_string
            FROM $systable
            $where";


  // if (strpos($systable, 'sys_shop_product_option_values') !== false) {

  //   print_r($sql);exit;
  // }
  sys_log('-sysDBSelect', LOG_DEBUG, "sql", $sql, "sql");

  $sql_result = mysqli_query($dbconn, $sql);

  if ($sql_result === false)
    sysException('sysDBSelect', DATABASE_ERROR, mysqli_error($dbconn) . "<br> $sql");

  //Возврат парраметров
  $RecordCount = mysqli_num_rows($sql_result);
  //Логируем выборку
  $mtm = explode(' ', microtime());
  $endtime = $mtm[1] + $mtm[0];
  $totaltime = ($endtime - $starttime);
  global $dbg_sql_queries;
  $dbg_sql_queries[] = array('sql_query' => $sql, 'totaltime' => $totaltime);
  if ($RecordCount == 0)//Нихера ненашли
  {
    mysqli_free_result($sql_result);
    return;
  } elseif ($RecordCount == 1 && $multi == false)//Одна запись и мултимоде выключен возвращаем как массив
  {
    $result = mysqli_fetch_assoc($sql_result);
    mysqli_free_result($sql_result);
    return $result;
  } else//Много записей возвращаем как массив масивов
  {

    $result = array();
    $i = 0;
    while ($row = mysqli_fetch_assoc($sql_result)) {
      $i++;
      array_push($result, $row);
      if ($i > 500 && strpos($systable, 'sys_shop_payments') !== false) {
        break;
      }
    }
    mysqli_free_result($sql_result);

    sys_log('sysDBSelect', LOG_INFO, "systable ", $systable);
    return $result;
  }
  ;
}
;

/**
 * Новая безопасная функция выборки (PDO)
 * Полная замена sysDBSelect() с поддержкой key => value и всех нужных фишек
 */

function sysDBSelectPDO(
  string $table,
  $columns = '*',
  array $where = [],
  bool $one = false,
  ?string $keyField = null,
  string $orderBy = '',
  string $limit = '',
  string $groupBy = '',
  string $having = ''
) {
  global $pdo;

  // === 1. ОБРАБОТКА КОЛОНОК (самая умная в мире) ===
  if (is_array($columns)) {
    $clean_cols = [];
    foreach ($columns as $col) {
      $col = trim($col);

      // Если есть пробелы, скобки — это выражение (COUNT, SUM, CONCAT, CASE и т.д.)
      if (preg_match('/\s|[\(\)]|\b(AS|as)\b/', $col)) {
        // Экранируем только имена колонок внутри выражения
        $col = preg_replace_callback('/\b([a-zA-Z_][a-zA-Z0-9_]*)\b/', function ($m) {
          $keyword = strtoupper($m[1]);
          $keywords = ['COUNT', 'SUM', 'AVG', 'MIN', 'MAX', 'AS', 'CONCAT', 'IF', 'CASE', 'WHEN', 'THEN', 'ELSE', 'END', 'COALESCE', 'DATE_FORMAT'];
          return in_array($keyword, $keywords) ? $m[1] : '`' . $m[1] . '`';
        }, $col);
        $clean_cols[] = $col;
      }
      // Обычные колонки или table.column
      else {
        $col = trim($col, "`'\" \t\n\r");
        if (strpos($col, '.') !== false) {
          $parts = explode('.', $col);
          $escaped = [];
          foreach ($parts as $part) {
            $part = trim($part, "`'\" ");
            $escaped[] = ($part === '*') ? '*' : '`' . $part . '`';
          }
          $clean_cols[] = implode('.', $escaped);
        } else {
          $clean_cols[] = ($col === '*') ? '*' : '`' . $col . '`';
        }
      }
    }
    $cols = implode(', ', $clean_cols);
  } else {
    $cols = $columns;
  }

  // === 2. СБОРКА ЗАПРОСА ===
  $sql = "SELECT $cols FROM `$table`";
  $params = [];

  // WHERE
  if (!empty($where)) {
    $conditions = [];
    foreach ($where as $field => $value) {
      if (is_int($field)) {
        $conditions[] = $value;
        continue;
      }

      $field = trim($field);
      $operator = '=';
      $placeholder = '?';

      if (preg_match('/\s+(>|<|>=|<=|<>|!=|LIKE|IN|IS)\s*$/i', $field, $m)) {
        $operator = strtoupper(trim($m[1]));
        $field = preg_replace('/\s+(>|<|>=|<=|<>|!=|LIKE|IN|IS)\s*$/i', '', $field);

        if ($operator === 'IN') {
          if (is_array($value) && !empty($value)) {
            $placeholders = str_repeat('?,', count($value) - 1) . '?';
            $placeholder = "($placeholders)";
            $params = array_merge($params, $value);
          } else {
            $placeholder = '(NULL)';
          }
        }
      }

      $conditions[] = "`$field` $operator $placeholder";
      if ($operator !== 'IN' || !is_array($value)) {
        $params[] = $value;
      }
    }
    $sql .= " WHERE " . implode(' AND ', $conditions);
  }

  // GROUP BY
  if ($groupBy !== '') {
    $sql .= " GROUP BY $groupBy";
  }

  // HAVING
  if ($having !== '') {
    $sql .= " HAVING $having";
  }

  // ORDER BY
  if ($orderBy !== '') {
    $sql .= " ORDER BY $orderBy";
  }

  // LIMIT
  if ($limit !== '') {
    $sql .= " LIMIT $limit";
  }

  // === 3. ЛОГИРОВАНИЕ ===
  sys_log('-sysDBSelectPDO', LOG_DEBUG, "SQL", $sql);
  if (!empty($params)) {
    sys_log('-sysDBSelectPDO', LOG_DEBUG, "PARAMS", json_encode($params, JSON_UNESCAPED_UNICODE));
  }

  // === 4. ВЫПОЛНЕНИЕ ===
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  // key => value (например, id => name)
  if ($keyField !== null) {
    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
  }

  if ($one) {
    $row = $stmt->fetch();
    return $row ?: [];
  }

  return $stmt->fetchAll();
}

/**
 * Безопасное удаление записей через PDO
 * 
 * @param string       $table     Название таблицы
 * @param array        $where     Условия WHERE в формате ['field' => value] или ['id IN' => [1,2,3]]
 * @param string|null  $limit     Необязательно: "1" — если нужно удалить только одну запись
 * @return int         Количество удалённых строк
 */
function sysDBDeletePDO(string $table, array $where, ?string $limit = null): int
{
  global $pdo;

  if (empty($where)) {
    sys_log('-sysDBDeletePDO', LOG_ERROR, "Попытка удаления без WHERE!", debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2));
    throw new Exception("Удаление без условия WHERE запрещено!");
  }

  $sql = "DELETE FROM `$table`";
  $params = [];

  // === WHERE — такая же мощная логика, как в sysDBSelectPDO() ===
  $conditions = [];
  foreach ($where as $field => $value) {
    if (is_int($field)) {
      // Готовое условие (например: "deleted_at IS NULL")
      $conditions[] = $value;
      continue;
    }

    $field = trim($field);
    $operator = '=';
    $placeholder = '?';

    if (preg_match('/\s+(>|<|>=|<=|<>|!=|LIKE|IN|IS)\s*$/i', $field, $m)) {
      $operator = strtoupper(trim($m[1]));
      $field = preg_replace('/\s+(>|<|>=|<=|<>|!=|LIKE|IN|IS)\s*$/i', '', $field);

      if ($operator === 'IN') {
        if (is_array($value) && !empty($value)) {
          $placeholders = str_repeat('?,', count($value) - 1) . '?';
          $placeholder = "($placeholders)";
          $params = array_merge($params, $value);
        } else {
          $placeholder = '(NULL)';
        }
      }
    }

    $conditions[] = "`$field` $operator $placeholder";
    if ($operator !== 'IN' || !is_array($value)) {
      $params[] = $value;
    }
  }

  $sql .= " WHERE " . implode(' AND ', $conditions);

  if ($limit !== null) {
    $sql .= " LIMIT " . (int) $limit;
  }

  // === Логирование ===
  sys_log('-sysDBDeletePDO', LOG_INFO, "DELETE SQL", $sql);
  if (!empty($params)) {
    sys_log('-sysDBDeletePDO', LOG_INFO, "DELETE PARAMS", json_encode($params, JSON_UNESCAPED_UNICODE));
  }

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  $deleted = $stmt->rowCount();

  sys_log('-sysDBDeletePDO', LOG_INFO, "Удалено строк", $deleted);

  return $deleted;
}

/*
 * Вносит изминения в базу
 * Пераетса массив в формате key=>value
 */
function sysDBUpdate($systable, $data, $where)
{
  global $dbconn;
  $column = sysDBGetColumns($systable);

  sys_log('sysDBUpdate', LOG_DEBUG, 'data 0', $data, 'sql');

  //проверка добовляем только те параметры, каторые есть в column
  foreach ($data as $key => $value) {
    if (!isset($column[$key])) {
      unset($data[$key]);
    }
    ;
  }
  ;

  //Id щбновлять ненадо
  unset($data['id']);

  mysqli_set_charset($dbconn, "utf8mb4");

  sys_log('sysDBUpdate', LOG_DEBUG, 'data', $data, 'sql');

  foreach ($data as $key => $value) {
    if (isset($set)) {
      $set .= ", " . $column[$key] . " = '" . mysqli_real_escape_string($dbconn, $value) . "'";
    } else {
      $set .= $column[$key] . " = '" . mysqli_real_escape_string($dbconn, $value) . "'";
    }
    ;
  }
  ;



  $sql = "UPDATE $systable SET $set $where";

  //Логируем выборку
  global $dbg_sql_queries;
  $dbg_sql_queries[] = array('sql_query' => $sql);

  sys_log('sysDBUpdate', LOG_DEBUG, 'sql', $sql, 'sql');

  mysqli_query($dbconn, $sql);

  if (mysqli_connect_errno())
    sysException('sysDBUpdate', DATABASE_ERROR, mysqli_connect_error());


  return true;
}
;

/**
 * Обновление записи в таблице с использованием PDO (prepared statements)
 *
 * @param string $systable Имя таблицы (с префиксом, если есть)
 * @param array  $data     Ассоциативный массив данных для обновления (key => value)
 * @param string $where    Условие WHERE (например: "WHERE id = 123")
 * @return bool
 */
function sysDBUpdatePDO($systable, $data, $where)
{
  global $pdo;

  // Если PDO-соединение ещё не создано — создаём (один раз)
  if (!isset($pdo) || !$pdo instanceof PDO) {
    global $sysConfig;

    $dsn = "mysql:host={$sysConfig['DB.Host']};dbname={$sysConfig['DB.Name']};charset=utf8mb4";
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
      $pdo = new PDO($dsn, $sysConfig['DB.UserName'], $sysConfig['DB.Password'], $options);
    } catch (PDOException $e) {
      sysException('sysDBUpdatePDO', DATABASE_ERROR, 'PDO connection failed: ' . $e->getMessage());
    }
  }

  $column = sysDBGetColumns($systable);

  sys_log('sysDBUpdatePDO', LOG_DEBUG, 'data before filter', $data, 'sql');

  // Оставляем только те поля, которые действительно есть в таблице
  foreach ($data as $key => $value) {
    if (!isset($column[$key])) {
      unset($data[$key]);
    }
  }

  // Поле id обновлять не нужно (на всякий случай)
  unset($data['id']);

  if (empty($data)) {
    sys_log('sysDBUpdatePDO', LOG_WARNING, 'No fields to update after filtering', [], 'sql');
    return true; // Нечего обновлять — считаем успешным
  }

  sys_log('sysDBUpdatePDO', LOG_DEBUG, 'data after filter', $data, 'sql');

  // Формируем части запроса
  $setParts = [];
  $params = [];

  foreach ($data as $key => $value) {
    $placeholder = ":$key";
    $setParts[] = "{$column[$key]} = $placeholder";
    $params[$placeholder] = $value;
  }

  $setString = implode(', ', $setParts);
  $sql = "UPDATE $systable SET $setString $where";

  // Логируем полный SQL (с плейсхолдерами)
  global $dbg_sql_queries;
  $dbg_sql_queries[] = ['sql_query' => $sql, 'params' => $params];

  sys_log('sysDBUpdatePDO', LOG_DEBUG, 'sql', $sql, 'sql');
  sys_log('sysDBUpdatePDO', LOG_DEBUG, 'params', $params, 'sql');

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
  } catch (PDOException $e) {
    sysException('sysDBUpdatePDO', DATABASE_ERROR, 'PDO execute failed: ' . $e->getMessage() . ' | SQL: ' . $sql);
  }

  return true;
}

/*
 * Добавляем запись в базу
 * Передаётся массив в формате key=>value
 */
function sysDBInsert($systable, $data)
{
  global $dbconn;
  /* Изменим набор символов на utf8mb4 */
  mysqli_set_charset($dbconn, "utf8mb4");

  $column = sysDBGetColumns($systable);

  sys_log('sysDBInsert', LOG_WARNING, ' sysDBGetColumns', $column);

  //проверка добовляем только те параметры, каторые есть в column
  foreach ($data as $key => $value) {
    if (!isset($column[$key])) {
      unset($data[$key]);
    }
    ;
  }
  ;
  //id нам ненадо у нас авто инкимент
  unset($data['id']);




  //Делаем вставку информаии в базу
  foreach ($data as $key => $value) {
    //Готовим строку ключей
    if (isset($sqlkey)) {
      $sqlkey .= ', ' . $column[$key];
      $sqlvalues .= ", '" . mysqli_real_escape_string($dbconn, $value) . "'";
    } else {
      $sqlkey .= $column[$key];
      $sqlvalues .= "'" . mysqli_real_escape_string($dbconn, $value) . "'";
    }
    ;
    // Готовим строку начений
  }
  ;



  $sql = "INSERT INTO $systable
            ($sqlkey)
            VALUES
            ($sqlvalues)";


  sys_log('sysDBInsert', LOG_WARNING, ' sql', $sql, 'sql');

  //Логируем выборку
  global $dbg_sql_queries;
  $dbg_sql_queries[] = array('sql_query' => $sql);
  try {
    $sql_result = mysqli_query($dbconn, $sql);
  } catch (mysqli_sql_exception $e) {
    sysException('sysDBInsert', DATABASE_ERROR, $e->getMessage());
    return false;
  }

  if ($sql_result === false) {
    sysException('sysDBInsert', DATABASE_ERROR, mysqli_error($dbconn));
    return false;
  }

  return mysqli_insert_id($dbconn);
}
;

/**
 * Вставка записи в таблицу с использованием PDO (prepared statements)
 *
 * @param string $systable Имя таблицы (с префиксом, если используется)
 * @param array  $data     Ассоциативный массив данных для вставки (key => value)
 * @return int             ID вставленной записи (mysqli_insert_id аналог)
 */
function sysDBInsertPDO($systable, $data)
{
  global $pdo;

  // Инициализируем PDO-соединение один раз (если ещё не создано)
  if (!isset($pdo) || !$pdo instanceof PDO) {
    global $sysConfig;

    $dsn = "mysql:host={$sysConfig['DB.Host']};dbname={$sysConfig['DB.Name']};charset=utf8mb4";
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
      $pdo = new PDO($dsn, $sysConfig['DB.UserName'], $sysConfig['DB.Password'], $options);
    } catch (PDOException $e) {
      sysException('sysDBInsertPDO', DATABASE_ERROR, 'PDO connection failed: ' . $e->getMessage());
    }
  }

  $column = sysDBGetColumns($systable);

  sys_log('sysDBInsertPDO', LOG_DEBUG, 'sysDBGetColumns result', $column, 'sql');

  // Оставляем только те поля, которые существуют в таблице
  foreach ($data as $key => $value) {
    if (!isset($column[$key])) {
      unset($data[$key]);
    }
  }

  // Поле id не вставляем — у нас автоинкремент
  unset($data['id']);

  if (empty($data)) {
    sys_log('sysDBInsertPDO', LOG_WARNING, 'No fields to insert after filtering', [], 'sql');
    return 0; // Нечего вставлять
  }

  sys_log('sysDBInsertPDO', LOG_DEBUG, 'filtered data', $data, 'sql');

  // Формируем плейсхолдеры и список колонок
  $fields = [];
  $placeholders = [];
  $params = [];

  foreach ($data as $key => $value) {
    $colName = $column[$key];
    $placeholder = ":$key";

    $fields[] = $colName;
    $placeholders[] = $placeholder;
    $params[$placeholder] = $value;
  }

  $fieldsStr = implode(', ', $fields);
  $placeholdersStr = implode(', ', $placeholders);

  $sql = "INSERT INTO $systable ($fieldsStr) VALUES ($placeholdersStr)";

  // Логируем запрос и параметры
  global $dbg_sql_queries;
  $dbg_sql_queries[] = ['sql_query' => $sql, 'params' => $params];

  sys_log('sysDBInsertPDO', LOG_DEBUG, 'sql', $sql, 'sql');
  sys_log('sysDBInsertPDO', LOG_DEBUG, 'params', $params, 'sql');

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Возвращаем ID вставленной записи
    return (int) $pdo->lastInsertId();
  } catch (PDOException $e) {
    sysException(
      'sysDBInsertPDO',
      DATABASE_ERROR,
      'PDO execute failed: ' . $e->getMessage() . ' | SQL: ' . $sql
    );
  }
}

/*
 * Удалили запись в базе
 * Пераетса массив в формате key=>value
 */
function sysDBDelete($systable, $where)
{
  global $dbconn;
  $sql = "DELETE FROM $systable $where";

  //Логируем выборку
  global $dbg_sql_queries;
  $dbg_sql_queries[] = array('sql_query' => $sql);

  sys_log('sysDBDelete', LOG_DEBUG, ' sql', $sql, 'sql');

  mysqli_query($dbconn, $sql);
  if (mysqli_connect_errno())
    sysException('sysDBDelete', DATABASE_ERROR, mysqli_connect_error());

  return true;
}
;

/**
 * Предаетса список столбцов базы которые потенциально могут быть переданны странице
 * Возвращает масив в формате key=>value
 */
function sysDBCleanFromInput($column)
{
  $args = array_keys($column);

  $search = array(
    '|</?\s*SCRIPT.*?>|si',
    '|</?\s*FRAME.*?>|si',
    '|</?\s*OBJECT.*?>|si',
    '|</?\s*META.*?>|si',
    '|</?\s*APPLET.*?>|si',
    '|</?\s*LINK.*?>|si',
    '|</?\s*IFRAME.*?>|si',
    '|STYLE\s*=\s*"[^"]*"|si'
  );

  $replace = array('');

  $resarray = array();
  foreach ($args as $var) {
    // Get var
    global $$var;

    if (empty($var))
      return;
    $ourvar = $$var;

    if (!isset($ourvar))
      continue;

    if (empty($ourvar)) {
      $resarray[$var] = $ourvar;
      continue;
    }

    // Clean var
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
      sysStripslashes($ourvar);
    }

    // Add to result array
    $resarray[$var] = $ourvar;
  }
  return $resarray;
}

/*
 * Возвращает вес самого большова элимента
 */
function sysDBMaxWeight($systable, $where = '')
{
  global $dbconn;
  $column = sysDBGetColumns($systable);

  //Подготавливаем $where
  $where = str_replace('WHERE ', '', $where);
  if ($where != '') {
    $where = "WHERE $where";
  }
  ;

  $sql = "SELECT MAX( $column[weight] )
              FROM $systable $where";

  $sql_result = mysqli_query($dbconn, $sql);
  $row = mysqli_fetch_row($sql_result);
  $maxweight = $row[0];


  if (mysqli_connect_errno())
    sysException('sysDBMaxWeight', DATABASE_ERROR, mysqli_connect_error() . "<br> $sql");

  mysqli_free_result($sql_result);
  return $maxweight;
}
;

/*
 * sysModGetVar - получить переменную модуля
 * $modname - Определенное в системе имя модуля, переменную которого мы хотим получить.
 * $name - Имя получаемой переменной модуля
 */
function sysModGetVar($modname, $name)
{
  global $dbconn;
  //sys_log('sysModGetVar', LOG_DEBUG, "modname ", $modname);



  if ((empty($modname)) || (empty($name))) {
    sysException('sysModGetVar', BAD_PARAM, "\$modname - $modname \$name - $name");
  }



  //проверяем если есть в кеше возвращаем из кеша
  if (sysVarIsCached('modvar_' . $modname, $name)) {
    //sys_log('sysModGetVar', LOG_INFO, "GLOBALS", $GLOBALS);
    return (sysVarGetCached('modvar_' . $modname, $name));
  }
  ;





  ///
  //Лезим в бд
  $modulevarstable = sysDBGetTable("module_vars");


  $modulevarscolumn = sysDBGetColumns($modulevarstable);


  $sql = "SELECT $modulevarscolumn[value]
            FROM $modulevarstable
            WHERE $modulevarscolumn[modname] = '" . $modname . "'
            AND $modulevarscolumn[name] = '" . $name . "'";
  //  WHERE $modulevarscolumn[modname] = '" . sysVarPrepForStore($modname) . "'
  //  AND $modulevarscolumn[name] = '" . sysVarPrepForStore($name) . "'";
///*


  //Выборка
  $sql_result = mysqli_query($dbconn, $sql);


  if (mysqli_connect_errno())
    sysException('sysModGetVar', DATABASE_ERROR, mysqli_connect_error() . "<br> $sql");

  list($value) = mysqli_fetch_row($sql_result);
  mysqli_free_result($sql_result);

  sysVarSetCached('modvar_' . $modname, $name, $value);


  return $value;
}

/*
 * sysModGetVarAll - получить все переменные модуля
 * $modname - Определенное в системе имя модуля, переменные которого мы хотим получить.
 */
function sysModGetVarAll($modname)
{
  if (empty($modname)) {
    sysException('sysModGetVarAll', BAD_PARAM, "\$modname - $modname ");
  }

  //Лезим в бд
  $modulevarstable = sysDBGetTable('module_vars');
  $modulevarscolumn = sysDBGetColumns($modulevarstable);
  $modulevarscolumn_select = array('name' => $modulevarscolumn['name'], 'value' => $modulevarscolumn['value']);

  $modulevarscond = "WHERE $modulevarscolumn[modname] = '" . sysVarPrepForStore($modname) . "'";

  $module_vars_list = sysDbSelect($modulevarstable, $modulevarscolumn_select, $modulevarscond, $multi = true);

  if (empty($module_vars_list))
    return false;

  $module_vars = array();
  foreach ($module_vars_list as $k => $v)
    $module_vars[$v['name']] = $v['value'];

  sysVarSetCached('modvar_' . $modname, $module_vars);
  return $module_vars;
}


/*
 * sysModSetVar - установка переменной модуля.
 * $modname - Определенное в системе имя модуля, чья переменная должна быть установлена.
 * $name - Имя устанавливаемой переменной.
 * $value - Значение присваиваемое переменной модуля.
 */
function sysModSetVar($modname, $name, $value)
{
  global $dbconn;
  if ((empty($modname)) || (empty($name))) {
    sysException('sysModSetVar', BAD_PARAM, "\$modname - $modname \$name - $name");
  }

  $modulevarstable = sysDBGetTable('module_vars');
  $modulevarscolumn = sysDBGetColumns($modulevarstable);

  //Проверка на существует переменная или новая
  $sql = "SELECT $modulevarscolumn[value]
            FROM $modulevarstable
            WHERE $modulevarscolumn[modname] = '" . sysVarPrepForStore($modname) . "'
            AND $modulevarscolumn[name] = '" . sysVarPrepForStore($name) . "'";

  //Выборка
  $sql_result = mysqli_query($dbconn, $sql);

  if (mysqli_connect_errno())
    sysException('sysModSetVar', DATABASE_ERROR, mysqli_connect_error() . "<br> $sql");

  if (mysqli_num_rows($sql_result) < 1) {
    $sql = "INSERT INTO $modulevarstable
                   ($modulevarscolumn[modname],
                    $modulevarscolumn[name],
                    $modulevarscolumn[value])
                VALUES
                   ('" . sysVarPrepForStore($modname) . "',
                    '" . sysVarPrepForStore($name) . "',
                    '" . sysVarPrepForStore($value) . "');";
  } else {
    $sql = "UPDATE $modulevarstable
                SET $modulevarscolumn[value] = '" . sysVarPrepForStore($value) . "'
                WHERE $modulevarscolumn[modname] = '" . sysVarPrepForStore($modname) . "'
                AND $modulevarscolumn[name] = '" . sysVarPrepForStore($name) . "'";
  }

  mysqli_query($dbconn, $sql);
  if (mysqli_connect_errno())
    sysException('sysModSetVar', DATABASE_ERROR, mysqli_connect_error() . "<br> $sql");


  sysVarSetCached('modvar_' . $modname, $name, $value);
  return true;
}


/*
 * sysModDelVar - удалить переменную модуля.
 * $modname - Определенное в системе имя модуля, переменную которого необходимо удалить.
 * $name - Имя удаляемой переменной.
 */
function sysModDelVar($modname, $name)
{
  global $dbconn;
  if ((empty($modname)) || (empty($name))) {
    sysException('sysModDelVar', BAD_PARAM, "\$modname - $modname \$name - $name");
  }

  $modulevarstable = sysDBGetTable('module_vars');
  $modulevarscolumn = sysDBGetColumns($modulevarstable);
  $sql = "DELETE FROM $modulevarstable
            WHERE $modulevarscolumn[modname] = '" . sysVarPrepForStore($modname) . "'
            AND $modulevarscolumn[name] = '" . sysVarPrepForStore($name) . "'";

  mysqli_query($dbconn, $sql);
  if (mysqli_connect_errno())
    sysException('sysModDelVar', DATABASE_ERROR, mysqli_connect_error() . "<br> $sql");

  sysVarDelCached('modvar_' . $modname, $name);
  return true;
}

/**
 * загрузка модуля
 */
function sysModLoad($modname, $type = 'user')
{
  static $loaded = array();

  //Проверка на передачу имени
  if (empty($modname)) {
    sysException('sysModLoad', BAD_PARAM, "\$modname - $modname");
  }

  //Проверка может уже загружали ?
  if (!empty($loaded["$modname$type"])) {
    return true;
  }

  if (!sysModAvailable($modname)) {//Модуль недоступен
    sysException('sysModLoad', MODULE_NOT_EXIST, 'Модуль или недоступен или не установлен');
  }
  ;

  // Load the module and module language files
  list($osmodname, $ostype) = sysVarPrepForOS($modname, $type);
  $osfile = "modules/$osmodname/$ostype.php";

  if (!file_exists($osfile)) {
    sysException('sysModLoad', MODULE_FILE_NOT_EXIST, 'File does not exist');
  }

  //загружаем файл
  include_once $osfile;
  $loaded["$modname$type"] = 1;

  return true;
}

/*
 * Загрузка класса модуля
 */
function sysModClassLoad($modname)
{
  static $loaded = array();

  //Проверка на передачу имени
  if (empty($modname)) {
    sysException('sysModClassLoad', BAD_PARAM, "\$modname - $modname");
  }

  //Проверка может уже загружали ?
  if (!empty($loaded["$modname"])) {
    return true;
  }

  //  if (!sysModAvailable ($modname))
//    {//Модуль недоступен
//    die ('Модуль недоступен');
//    return false;
//    };

  // Load the module and module language files
  $osmodname = sysVarPrepForOS($modname);
  $osfile = "modules/$osmodname/class.php";

  if (!file_exists($osfile)) {
    sysException('sysModClassLoad', MODULE_FILE_NOT_EXIST, 'File does not exist');
  }

  //загружаем файл
  include_once $osfile;
  $loaded["$modname"] = 1;

  return true;
}

/*
 * Вызов дисплай функции модуля
 */
function sysModFunc($modname, $type = 'user', $func = 'main', $args = array())
{
  //Проверки
  if (empty($modname)) {
    sysException('sysModFunc', BAD_PARAM, "\$modname - $modname");
  }

  if (empty($type)) {
    $func = 'user';
  }

  if (empty($func)) {
    $func = 'main';
  }

  // Build function name and call function
  $modfunc = "{$modname}_{$type}_{$func}";
  if (function_exists($modfunc)) {
    return $modfunc($args);
  }

  sysException('sysModFunc', MODULE_FUNCTION_NOT_EXIST, 'Ошибка выполнения функции модуля ' . $modfunc);
}

/*
 * Строит урл дисплай функции модуля
 */
function sysModURL($modname, $type = 'user', $func = 'main', $args = array())
{
  //Если курлы - используем другую функцию
  if (sysModGetVar('SYS_config', 'shorturls')) {
    return sysModShortURL($modname, $type, $func, $args);
  }
  if (empty($modname)) {
    sysException('sysModURL', BAD_PARAM, "\$modname - $modname");
  }

  // Лепим к урлу имя функции и т.д.
  $urlargs[] = "module=$modname";

  if ((!empty($type)) && ($type != 'user')) {
    $urlargs[] = "type=$type";
  }

  if ((!empty($func)) && ($func != 'main')) {
    $urlargs[] = "func=$func";
  }

  $urlargs = join('&amp;', $urlargs); //Делаем из масива строку

  $url = "/index.php?$urlargs";

  //Лепим передаваемые переменные
  foreach ($args as $k => $v) {
    if (is_array($v)) {
      foreach ($v as $l => $w) {
        $url .= '&amp;' . "$k" . "[$l]=$w";
      }
    } elseif (!empty($v)) {
      $url .= '&amp;' . "$k=" . urlencode($v);
    }
  }

  // Возвращаем полный урл
  //return sysGetBaseURL() . $url;
  return $url;
}

/*
 * Строит короткий урл дисплай функции модуля
 */
function sysModShortURL($mod_name, $type = 'user', $second_part = '', $args = array(), $postfix = false)
{

  //Защита от админских урлов
  if ($type != 'user')
    return false;

  if ($mod_name != "content")
    $url = "/$mod_name";

  if ($second_part)
    $url .= "/$second_part";

  if ($args['name'])
    $url .= "/" . $args['name'];
  elseif ($args['id'])
    $url .= "/" . $args['id'];

  if ($postfix)
    $url .= ".html";
  else if ($args['page'] && $args['page'] > 1)
    $url .= "/" . $args['page'];

  return $url;
}

//Проверка корректности обращения по имени страницы
function sysCheckShortUrl($page_short_url, $modname, $url_param = array())
{
  if (!$modname)
    return true;
  $url_control = sysModGetVar($modname, 'url_control');
  if (!$url_control)
    return true;

  if (count($url_param) > 0) {
    $page_short_url .= "?";
    foreach ($url_param as $k => $v)
      $page_short_url .= "$k=$v&";
    $page_short_url = substr($page_short_url, 0, -1);
  }

  if (urldecode($_SERVER['REQUEST_URI']) != $page_short_url) {
    $root = substr(sysGetSiteURL(), 0, -1);
    header('Location: ' . $root . $page_short_url, true, 301);
    sysTerminate();
    exit;
  }
  return true;

}



function sysGetUrlParam($modname = null)
{

  $indata = $_GET;
  unset($indata['module']);
  unset($indata['type']);
  unset($indata['func']);
  unset($indata['id']);
  unset($indata['page']);
  unset($indata['page_current']);

  if ($modname == "shop") {
    unset($indata['cat_id']);
    unset($indata['cat_name']);
    unset($indata['product_name']);
    unset($indata['subproduct_orderby']);
    unset($indata['subproduct_order_asc']);
    unset($indata['include_subcat']);
  }

  if ($modname == "content") {
    unset($indata['fid']);
    unset($indata['name']);
    unset($indata['dname']);
    unset($indata['fname']);
    unset($indata['subdoc_orderby']);
    unset($indata['subdoc_order_asc']);
  }
  return $indata;
}

/*
 * Извлекает переменные из url если используються короткие урлы
 */
function sysModShortUrlDecode()
{
  $request_uri = getenv("REQUEST_URI");
  if (!strpos($request_uri, 'index.php')) {//Используються короткие урлы распарсиваем

    $param = explode('/', $request_uri);
    $param = array_slice($param, 1);

    //Имя модуля
    if (!empty($param[0])) {
      global $module;
      //Добавляем приставочьку SYS_
      if (
        $param[0] == 'blocks' || $param[0] == 'config' || $param[0] == 'df' ||
        $param[0] == 'groups' || $param[0] == 'modules' || $param[0] == 'permissions' ||
        $param[0] == 'themes' || $param[0] == 'users'
      ) {
        $module = 'SYS_' . $param[0];
      } else {
        $module = $param[0];
      }
    }
    $param = array_slice($param, 1);

    //Тип модуля
    if (!empty($param[0]) && ($param[0] == 'admin' || $param[0] == 'user')) {
      global $type;
      $type = $param[0];
      $param = array_slice($param, 1);
    }

    //Функция модуля
    if (!empty($param[0]) && !strpos($param[0], '=')) {
      global $func;
      $func = $param[0];
      $param = array_slice($param, 1);
    }

    //Переменные
    if (!empty($param)) {
      foreach ($param as $key => $value) {
        $urlvar = explode('=', $value);
        if (count($urlvar) == 2) {
          global ${$urlvar[0]};
          ${$urlvar[0]} = $urlvar[1];
        }
      }
    }

  }
}

/*
 * Проверка модуля в системе на доступность
 */
function sysModAvailable($modname)
{
  if (empty($modname))
    sysException('sysModAvailable', BAD_PARAM, "\$modname - $modname");

  //проверяем если есть в кеше возвращаем из кеша
  if (sysVarIsCached('modavailable', $modname)) {
    return (sysVarGetCached('modavailable', $modname));
  }
  ;

  //Делаем выборку
  $modules_table = sysDBGetTable('modules');
  $modules_column = sysDBGetColumns($modules_table);
  $moduleinfo = sysDbSelect(
    $modules_table,
    $modules_column,
    "WHERE $modules_column[mod_name]='" . sysVarPrepForStore($modname) . "' AND $modules_column[mod_state]=" . MODULE_STATE_ACTIVE
  );
  if (empty($moduleinfo)) {
    return false;
  }
  ;

  sysVarSetCached('modavailable', $modname, true);
  return true;
}


//Наш класс рендеринга
/*
 * Смарти пропатчен smarty.class.php + 415 "var $_sys_tpl_vars            = array();"
 * smarty.class.php + 1303 "$this->_cache_info ['sys_tpl_vars'] = $this->_sys_tpl_vars;"
*/
class sysTpl extends Smarty
{
  var $_sys_tpl_vars = array();

  /**
   * Constructor (PHP 8 & PHP 7)
   **/
  public function __construct()
  {
    parent::__construct();
    global $sysConfig;
    $this->debugging = false;
    $this->caching = false;
    $this->cache_modified_check = false;
    $this->compile_check = false;
    $this->template_dir = '';
    $this->config_dir = '';
    $this->cache_dir = 'cache/content';
    if (!file_exists($this->cache_dir))
      mkdir($this->cache_dir, $sysConfig['default.dir.perms'] ?? 0775, true);
    $this->setCacheDir($this->cache_dir);

    $this->compile_dir = 'cache/templates';
    if (!file_exists($this->compile_dir))
      mkdir($this->compile_dir, $sysConfig['default.dir.perms'] ?? 0775, true);
    $this->setCompileDir($this->compile_dir);
    $this->setPluginsDir(array(
      'kernel/smarty4.5.5/plugins'
    ));

    $this->_file_perms = $sysConfig['default.file.perms'] ?? 0664;
    $this->_dir_perms = $sysConfig['default.dir.perms'] ?? 0775;

    $this->use_sub_dirs = $sysConfig['systpl.use_sub_dirs'] ?? false;
    $this->cache_lifetime = $sysConfig['systpl.cache_lifetime'] ?? 3600;
    $this->force_compile = $sysConfig['systpl.force_compile'] ?? 1;
  }

  function sysTpl()
  {
    $this->__construct();
  }
  /**
   * Fetching
   **/
  function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null)
  {
    $args = func_get_args();
    $tpl_file = $template;
    $vars = null;
    $display = false;

    // Поддержка старой сигнатуры:
    // fetch($tpl_file, $cache_id = null, $vars = null, $compile_id = null, $display = false)
    if (array_key_exists(2, $args) && is_array($args[2])) {
      $vars = $args[2];
      $compile_id = $args[3] ?? null;
      $display = $args[4] ?? false;
    }

    if (empty($tpl_file)) {
      sysException('SYS_tpl->fetch', BAD_PARAM, "Tpl file name empty");
    }

    if ($cache_id) {//Если с кеш классами не работаем, незачем модифицировать
      $sysSecLevel = $this->getTemplateVars('curuser_sec_level');
      if (!isset($sysSecLevel)) //Если уровень доступа не передан - определяем сами
      {
        $sysSecLevel = sysSecGetLevel($cache_id);
        $this->assign('curuser_sec_level', $sysSecLevel);
      }
      $this->assign('sysObject', $cache_id);

      $cache_id = str_replace('::', '|', $cache_id);
      $cache_id .= "|$sysSecLevel";
    }
    $this->_sys_tpl_vars = $vars;
    return (parent::fetch($tpl_file, $cache_id, $compile_id, $parent, $display));
  }
  /**
   * Возврашает переменные прикрепленные к закешированной странице
   **/
  function fetch_cached_vars()
  {
    return $this->_sys_tpl_vars;
  }
  /**
   * DISPLAY
   **/
  function display($template = null, $cache_id = null, $compile_id = null, $parent = null)
  {
    $tpl_file = $template;
    if (empty($tpl_file))
      sysException('SYS_tpl->display', BAD_PARAM, "Tpl file name empty");

    $sysSecLevel = sysSecGetLevel($cache_id);
    $cache_id = str_replace('::', '|', $cache_id);
    $cache_id .= "|$sysSecLevel";
    return (parent::display($tpl_file, $cache_id, $compile_id, $parent));
  }
  /**
   * clear_cache
   **/
  function clear_cache($tpl_file = null, $cache_id = null, $compile_id = null, $exp_time = null)
  {
    $cache_id = str_replace('::', '|', $cache_id);
    return (parent::clearCache($tpl_file, $cache_id, $compile_id, $exp_time));
  }
  /**
   * is_cached
   **/
  function is_cached($tpl_file, $cache_id = null, $compile_id = null)
  {
    if (empty($tpl_file))
      sysException('SYS_tpl->is_cached', BAD_PARAM, "Tpl file name empty");

    if ($cache_id) {//Если с кеш классами не работаем, незачем модифицировать
      $sysSecLevel = sysSecGetLevel($cache_id);
      $this->assign('sysSecLevel', $sysSecLevel);
      $this->assign('sysObject', $cache_id);

      $cache_id = str_replace('::', '|', $cache_id);
      $cache_id .= "|$sysSecLevel";
    }
    return (parent::isCached($tpl_file, $cache_id, $compile_id));
  }
  /**
   * assign
   **/
  function assign($tpl_var, $value = null, $clear = true)
  {
    sys_log('sysTpl', LOG_DEBUG, "tpl_var", $tpl_var, 'sysTpl');
    sys_log('sysTpl', LOG_DEBUG, "value", $value, 'sysTpl');
    return (parent::assign($tpl_var, $value));
  }
}
;

/**
 * По имени файла возвращает полный путь
 * Порядок поиска:
 * themes/$themename
 * themes/default
 * block/themename
 * block/default
 * module/theme
 * module/default
 * modules/$modname/default/$filename
 **/
function sysTplFindFile($themename, $modname, $filename)
{
  if (empty($themename) || empty($modname) || empty($filename)) {
    sysException('sysTplFindFile', BAD_PARAM, "\$themename - $themename \$modname - $modname \$filename - $filename");
  }
  //Ишим в темах
  if (file_exists("themes/$themename/components/$modname/$filename")) {
    return "themes/$themename/components/$modname/$filename";
  } elseif (file_exists("themes/default/components/$modname/$filename")) {
    return "themes/default/components/$modname/$filename";
  }
  //Ишим в блоках
  elseif (file_exists("blocks/$modname/themes/$themename/$filename")) {
    return "blocks/$modname/themes/$themename/$filename";
  } elseif (file_exists("blocks/$modname/themes/default/$filename")) {
    return "blocks/$modname/themes/default/$filename";
  }
  //Ишим в модулях
  elseif (file_exists("modules/$modname/themes/$themename/$filename")) {
    return "modules/$modname/themes/$themename/$filename";
  } elseif (file_exists("modules/$modname/themes/default/$filename")) {
    return "modules/$modname/themes/default/$filename";
  }

  return false;
}
;

/**
 * Возвращает путь к файлу шаблона для компонента
 **/
function sysTplWay($object, $tplfile = NULL)
{
  //Смотрим че у нас в сесии
  $themename = sysUserTheme();

  //Разбили на часть
  list($compname, $smolobject) = preg_split("/::/", $object, 2);
  //Производим поиск наиболее подходящева шаблона

  if (!empty($tplfile)) {
    return (sysTplFindFile($themename, $compname, $tplfile));
  }

  //проверяем если есть в кеше возвращаем из кеша
  if (sysVarIsCached('tpls_list', $compname)) {
    $tpls_list = sysVarGetCached('tpls_list', $compname);
  } else {
    //Делаем выборку
    $tpls_table = sysDBGetTable('templates');
    $tpls_column = sysDBGetColumns($tpls_table);
    $tpls_list = sysDbSelect($tpls_table, $tpls_column, "WHERE $tpls_column[tpl_compname]='$compname'
                                                ORDER BY $tpls_column[weight]", true);
    sysVarSetCached('tpls_list', $compname, $tpls_list);
  }
  ;

  //Перебираем все шаблоны модуля/темы
  if (!empty($tpls_list)) {
    foreach ($tpls_list as $tpl) {
      if (preg_match("/" . $tpl['tpl_pattern'] . "/iU", $object)) {
        //echo "A match was found: \$pattern - $tpl[pattern] -> \$object - $object<br>";
        return (sysTplFindFile($themename, $compname, $tpl['tpl_name']));
      }
      ;
    }
    ;
  }
  ;

  return false;
}
;
/**
 * Возвращает путь к файлу шаблона для страницы
 **/
function sysTplPageWay($object)
{
  //Смотрим че у нас в сесии
  $themename = sysUserTheme();
  return ('themes/' . $themename . '/pages/default.tpl');

  /*
    //Проверка активнали тема
    if (!sysThemeAvailable($themename))
      {
      $themename=sysModGetVar('SYS_config', 'default_theme');
      sysSessionSetVar('theme', $themename);
      //Задаем в настройках пользователя тему по умолчанию
      $uid=sysSessionGetVar('uid');
      if ($uid>0)
        {
        sysUserSetVar('user_theme', $themename, $uid);
        };
      };

    //Разбили на часть

    //проверяем если есть в кеше возвращаем из кеша

    if (sysVarIsCached('tpls_list', 'pagetpl_'.$themename))
      {
      $tpllist=sysVarGetCached('tpls_list', 'pagetpl_'.$themename);
      }
      else
        {
        //Производим поиск наиболее подходящева шаблона
        $pagestable = sysDBGetTable('themes_pages');
        $column =  sysDBGetColumns($pagestable);
        $tpllist=sysDbSelect ($pagestable, $column, "WHERE $column[themename]='$themename'
                                                ORDER BY $column[weight]");
        //Если один , делаем массив в массиве
        if (isset ($tpllist) && !is_array ($tpllist[0]))
          {
          $newtpllist[0]=$tpllist;
          $tpllist=$newtpllist;
          };
        //Кладем в кеш
        sysVarSetCached('tpls_list', 'pagetpl_'.$themename, $tpllist);
        };

    //Перебираем все шаблоны темы
    if (isset ($tpllist))
      {
      foreach ($tpllist as $tpl)
        {
        if (preg_match ("/".$tpl[pattern]."/iU", $object))
          {
          //echo "A match was found.<br> \$pattern-$tpl[pattern]<br> \$object - $object";
          return ('themes/'.$themename.'/pages/'.$tpl[name]);
          };
        };
      };
    return false;
  */
}
;

/*
 * Функции тем
 */

/*
 * Получить список тем доступных в системе
 */
function sysThemesList()
{

  //проверяем если есть в кеше возвращаем из кеша
  if (sysVarIsCached('themes_list', 'themes')) {
    return (sysVarGetCached('themes_list', 'themes'));
  }
  ;

  $themes_table = sysDBGetTable('themes');
  $themes_column = sysDBGetColumns($themes_table);

  sys_log('sysThemesList', LOG_INFO, "themes_column ", $themes_column);

  $themes_list = sysDbSelect(
    $themes_table,
    $themes_column,
    "WHERE $themes_column[theme_active] =1 ORDER BY $themes_column[theme_name]",
    true
  );

  sys_log('sysThemesList', LOG_INFO, "themes_list ", $themes_list);

  //Кладем в кеш
  // sysVarSetCached('themes_list', 'themes', $themes_list);
  return ($themes_list);
}
;

/*
 * Проверка доступнали сейчас данная тема
 */
function sysThemeAvailable($themename)
{
  //if (empty ($themename))  sysException ('sysThemeAvailable', BAD_PARAM, "\$themename - $themename");
  //имя темы может быть и пустое, если это гдето глюк...........
  //Мемори кеш
  static $loaded;
  if (isset($loaded[$themename])) {
    return $loaded[$themename];
  }
  ;
  sys_log('sysThemeAvailable', LOG_INFO, "themename ", $themename);
  $themes_list = sysThemesList();
  sys_log('sysThemeAvailable', LOG_INFO, "themes_list ", $themes_list);

  //Ишем и если находим значит активна
  foreach ($themes_list as $theme) {
    if (($theme['theme_name'] == $themename) && ($theme['theme_active'] == true)) {
      $loaded[$themename] = true;
      return true;
    }
    ;
  }
  ;
  $loaded[$themename] = false;
  sys_log('sysThemeAvailable1', LOG_INFO, "themename ", $themename);
  return false;
}
;


/*
 * Проверяет хватает ли у пользователя прав на выполнение операции
 */
function sysSecAuthAction($testobject, $testlevel, $ownerid = -1)
{
  //sys_log('Зашли в sysSecAuthAction', LOG_DEBUG, " + ", $vars, "menu");

  // Get user access level
  $userlevel = sysSecGetLevel($testobject, $ownerid);
  // User access level is override, so return that if it exists
  if ($userlevel > ACCESS_INVALID) {
    // user has explicitly defined access level for this
    // realm/component/instance combination
    if ($userlevel >= $testlevel) {
      // permission is granted to user
      return true;
    } else {
      // permission is prohibited to user, so group perm
      // doesn't matter
      return false;
    }
  }

  // No access granted
  return false;
}


/*
 * Считаем уровень доступа текушего пользователя к тестируемому обьекту
 */
function sysSecGetLevel($testobject, $ownerid = -1)
{
  if (empty($testobject))
    sysException('sysSecGetLevel', BAD_PARAM, "\$testobject - $testobject");

  //Затычка дабы не вызывать себя по несколько раз
  static $loaded = array();
  if (!empty($loaded["$testobject, $ownerid"])) {
    return $loaded["$testobject, $ownerid"];
  }


  $level = ACCESS_INVALID;

  //id текушева пользователя
  $uid = sysSessionGetVar('user_id');

  //Информация о правах пользователей
  //проверяем если есть в кеше возвращаем из кеша
  if (sysVarIsCached('sec_levels', 'users_perms')) {
    $users_perms_list = sysVarGetCached('sec_levels', 'users_perms');
  } else {
    $users_perms_table = sysDBGetTable('users_perms');
    $users_perms_column = sysDBGetColumns($users_perms_table);
    $users_perms_list = sysDbSelect(
      $users_perms_table,
      $users_perms_column,
      "ORDER BY $users_perms_column[weight]",
      true
    );
    //Кладем в кеш
    if (empty($users_perms_list)) {
      $users_perms_list = array();//Иначе небудет кешироваться
    }
    ;
    sysVarSetCached('sec_levels', 'users_perms', $users_perms_list);
  }
  ;

  //Смотрим, какие правила есть для текущева пользователя
  //До первава совпадения шаримся по всем подходящим правилам
  if (!empty($users_perms_list)) {
    foreach ($users_perms_list as $perm) {
      //Ага формируем регулярное выражение в зависимости от ''- все компаненты, или какойто канкретный
      if ($perm['component']) {
        $pattern = "/^$perm[component]::$perm[pattern]/Ui";
      } else {
        $pattern = "/^.*::$perm[pattern]/Ui";
      }

      if (($uid == $perm['uid']) && (preg_match($pattern, $testobject))) {
        $level = $perm['level'];
        break;
      }
      ;
    }
    ;
    sys_log('sysSecGetLevel Права пользователя', LOG_DEBUG, "Обьект \"$testobject\" уровень доступа \"$level\"");
  }
  ;






  //Смотрим права владельцев
  //echo "\$ownerid - $ownerid \$uid - $uid<br>";
  if (($ownerid > -1) && ($ownerid == $uid)) {
    //проверяем если есть в кеше возвращаем из кеша
    if (sysVarIsCached('sec_levels', 'owners_perms')) {
      $owners_perms_list = sysVarGetCached('sec_levels', 'owners_perms');
    } else {
      $owners_perms_table = sysDBGetTable('owners_perms');
      $owners_perms_column = sysDBGetColumns($owners_perms_table);
      //Смотрим, какие правила есть для текущева пользователя, включая всех, и незарегистрированных
      $owners_perms_list = sysDbSelect(
        $owners_perms_table,
        $owners_perms_column,
        "ORDER BY $owners_perms_column[weight]",
        true
      );
      //WHERE $column[gid]='$gid' OR $column[gid]='-1' OR $column[gid]='0'

      //Кладем в кеш
      if (empty($owners_perms_list)) {
        $owners_perms_list = array();//Иначе небудет кешироваться
      }
      ;
      sysVarSetCached('sec_levels', 'owners_perms', $owners_perms_list);
    }
    ;

    //До первава совпадения шаримся по всем подходящим правилам
    if (!empty($owners_perms_list)) {
      foreach ($owners_perms_list as $perm) {
        //Если не наш gid или не 0 или -1 пропускаем
        if (($perm['gid'] != $gid) && ($perm['gid'] != -1)) {
          continue;
        }
        //Ага формируем регулярное выражение в зависимости от ''- все компаненты, или какойто канкретный
        if ($perm['component']) {
          $pattern = "/^$perm[component]::$perm[pattern]/Ui";
        } else {
          $pattern = "/^.*::$perm[pattern]/Ui";
        }

        if (preg_match($pattern, $testobject)) {
          if ($perm['level'] > $level) {
            $level = $perm['level'];
          }
          ;
          break;
        }
        ;
      }
      ;
    }
    ;
    sys_log('sysSecGetLevel Права владельца', LOG_DEBUG, "Обьект \"$testobject\" уровень доступа \"$level\"");
  }
  ;

  //Смотрим права группы
  $gid = sysUserGetGid($uid);
  //проверяем если есть в кеше возвращаем из кеша
  if (sysVarIsCached('sec_levels', 'groups_perms')) {
    $groups_perms_list = sysVarGetCached('sec_levels', 'groups_perms');
  } else {
    $groups_perms_table = sysDBGetTable('groups_perms');
    $groups_perms_column = sysDBGetColumns($groups_perms_table);
    //Смотрим, какие правила есть для текущева пользователя, включая всех, и незарегистрированных
    $groups_perms_list = sysDbSelect(
      $groups_perms_table,
      $groups_perms_column,
      "ORDER BY $groups_perms_column[weight]",
      true
    );

    //Кладем в кеш
    if (empty($groups_perms_list)) {
      $groups_perms_list = array();//Иначе небудет кешироваться
    }
    ;
    sysVarSetCached('sec_levels', 'groups_perms', $groups_perms_list);
  }
  ;

  //WHERE $column[gid]='$gid' OR $column[gid]='-1' OR $column[gid]='0'
  if (!empty($groups_perms_list)) {
    foreach ($groups_perms_list as $perm) {
      //Если не наш gid или не 0 пропускаем
      if (($perm['gid'] != $gid) && ($perm['gid'] != 0)) {
        continue;
      }
      //
      //Ага формируем регулярное выражение в зависимости от ''- все компаненты, или какойто канкретный
      if ($perm['component']) {
        $pattern = "/^$perm[component]::$perm[pattern]/Ui";
      } else {
        $pattern = "/^.*::$perm[pattern]/Ui";
      }
      if (preg_match($pattern, $testobject)) {
        if ($perm['level'] > $level) {
          $level = $perm['level'];
        }
        ;
        break;
      }
      ;
    }
    ;
    sys_log('sysSecGetLevel Права групп', LOG_DEBUG, "Обьект \"$testobject\" уровень доступа \"$level\"");
  }
  ;

  //Усе
  //echo "Security - $testobject - $level<br>";
  $loaded["$testobject, $ownerid"] = $level;
  sys_log('sysSecGetLevel', LOG_DEBUG, "Обьект \"$testobject\" уровень доступа \"$level\"");
  return ($level);
}

/**
 * generate an authorisation key
 * <br>
 * The authorisation key is used to confirm that actions requested by a
 * particular user have followed the correct path.  Any stage that an
 * action could be made (e.g. a form or a 'delete' button) this function
 * must be called and the resultant string passed to the client as either
 * a GET or POST variable.  When the action then takes place it first calls
 * <code>pnSecConfirmAuthKey()</code> to ensure that the operation has
 * indeed been manually requested by the user and that the key is valid
 *
 * @public
 * @param modname the module this authorisation key is for (optional)
 * @returns string
 * @return an encrypted key for use in authorisation of operations
 */
function sysSecGenAuthKey($modname = '')
{
  if (empty($modname)) {
    $modname = sysVarCleanFromInput('module');
  }

  // Date gives extra security but leave it out for now
  // $key = pnSessionGetVar('rand') . $modname . date ('YmdGi');
  $key = sysSessionGetVar('rand') . strtolower($modname);

  // Encrypt key
  $authid = md5($key);

  // Return encrypted key
  return $authid;
}

/**
 * confirm an authorisation key is valid
 * <br>
 * See description of <code>sysSecGenAuthKey</code> for information on
 * this function
 * @public
 * @returns bool
 * @return true if the key is valid, false if it is not
 */
function sysSecConfirmAuthKey()
{
  list($module, $authid) = sysVarCleanFromInput('module', 'authid');

  // Regenerate static part of key
  $partkey = sysSessionGetVar('rand') . strtolower($module);

  // Not using time-sensitive keys for the moment
//    // Key life is 5 minutes, so search backwards and forwards 5
//    // minutes to see if there is a match anywhere
//    for ($i=-5; $i<=5; $i++) {
//        $testdate  = mktime(date('G'), date('i')+$i, 0, date('m') , date('d'), date('Y'));
//
//        $testauthid = md5($partkey . date('YmdGi', $testdate));
//        if ($testauthid == $authid) {
//            // Match
//
//            // We've used up the current random
//            // number, make up a new one
//            srand((double)microtime()*1000000);
//            pnSessionSetVar('rand', rand());
//
//            return true;
//        }
//    }

  if ((md5($partkey)) == $authid) {
    // Match - generate new random number for next key and leave happy
    srand((double) microtime() * 1000000);
    sysSessionSetVar('rand', rand());
    return true;
  }

  // Not found, assume invalid
  return false;
}

/**
 * Set up session handling
 * Set all PHP options for System session handling
 */
function sysSessionSetup()
{
  global $HTTP_SERVER_VARS;

  $path = sysGetBaseURI();
  if (empty($path))
    $path = '/';

  $host = $HTTP_SERVER_VARS['HTTP_HOST'];
  if (strpos($host, ':') !== false)
    $host = substr($host, 0, strpos($host, ':'));

  // PHP configuration variables

  // Stop adding SID to URLs
  ini_set('session.use_trans_sid', 0);

  // User-defined save handler // 15/04/2019
  // ini_set('session.save_handler', 'user');

  // How to store data
  ini_set('session.serialize_handler', 'php');

  // Use cookie to store the session ID
  ini_set('session.use_cookies', 1);

  // Name of our cookie
  ini_set('session.name', 'SYSSID');

  // Lifetime of our cookie
  // Session lasts set number of days
  $lifetime = sysModGetVar('SYS_config', 'sesdays') * 86400;
  ini_set('session.cookie_lifetime', $lifetime);

  // Garbage collection
  ini_set('session.gc_probability', 1);

  // Inactivity timeout for user sessions
  ini_set('session.gc_maxlifetime', $lifetime);

  // Auto-start session
  // ini_set('session.auto_start', 1);

  session_set_save_handler(
    'sysSessionOpen',
    'sysSessionClose',
    'sysSessionRead',
    'sysSessionWrite',
    'sysSessionDestroy',
    'sysSessionGC'
  );
  return true;
}

/*
 * Session variables here are a bit 'different'.  Because they sit in the
 * global namespace we use a couple of helper functions to give them their
 * own prefix, and also to force users to set new values for them if they
 * require.  This avoids blatant or accidental over-writing of session
 * variables.
 *
 */

/**
 * Get a session variable
 *
 * @param name name of the session variable to get
 */
function sysSessionGetVar($name)
{

  //	sys_log('sysSessionGetVar start', LOG_DEBUG, "name", $name);

  $var = "SYSSV$name";

  global $$var;
  if (!empty($_SESSION[$var])) {
    return $_SESSION[$var];
  }

  return;
}

/**
 * Set a session variable
 * @param name name of the session variable to set
 * @param value value to set the named session variable
 */
function sysSessionSetVar($name, $value)
{
  $var = "SYSSV$name";

  global $$var;
  $$var = $value;
  $_SESSION[$var] = $value;

  //sys_log('sysSessionSetVar', LOG_DEBUG, "_SESSION", $var);
  //sys_log('sysSessionSetVar', LOG_DEBUG, "_SESSION", $value);

  return true;
}

/**
 * Delete a session variable
 * @param name name of the session variable to delete
 */
function sysSessionDelVar($name)
{
  $var = "SYSSV$name";

  global $$var;

  unset($GLOBALS[$var]);

  unset($_SESSION[$var]);

  return true;
}

/**
 * Initialise session
 */
function sysSessionInit()
{

  //sys_log('sysSessionInit start', LOG_DEBUG, "phpversion", phpversion());

  global $HTTP_SERVER_VARS;

  // First thing we do is ensure that there is no attempted pollution
  // of the session namespace
  foreach ($GLOBALS as $k => $v) {
    //if (substr($k,0,5) == 'SYSSV') return false;
  }

  //sys_log('sysSessionInit before session_start', LOG_DEBUG, "session_start", $tmpipaddr);

  // Kick it
  $start = session_start(); // sysSessionOpen Если переопределена

  sys_log('sysSessionInit after session_start', LOG_DEBUG, "start", $start);

  //sys_log('sysSessionInit after session_start', LOG_DEBUG, "session_start", $tmpipaddr);

  $sessid = session_id();

  //sys_log('sysSessionInit after session_start', LOG_DEBUG, "session_id", $sessid);

  // Get (actual) client IP addr
  $ipaddr = sysUserIpAddr();

  //sys_log('sysUserIpAddr after sysUserIpAddr', LOG_DEBUG, "ipaddr", $tmpipaddr);

  //Проверка новая сессия или продолжение текущей
  //К примеру по ip

  if (sysSessionGetVar('ipaddr')) {
    sysSessionCurrent($sessid);

    //sys_log('sysSessionInit sysSessionCurrent', LOG_DEBUG, "sessid", $sessid);

    //todo проверки по ip
    //        } else {
    //          // Mismatch - destroy the session
    //          session_destroy();
    //          sysRedirect('index.php');
    //          return false;
    //        }
  } else {
    sysSessionNew($sessid, $ipaddr);

    //sys_log('sysSessionInit sysSessionNew', LOG_DEBUG, "sessid", $sessid);

  }

  sys_log('sysSessionInit end', LOG_INFO, "sessid", $sessid);


  return true;
}

/**
 * Продолженеи существующей сессии
 */
function sysSessionCurrent($sessid)
{
  //Время последнего использования сесии
  $lastused = sysSessionGetVar('lastused');
  $now = time();
  //Обновляем не чаще раза в 5 минуты
  if ($now - $lastused >= 300)
    sysSessionSetVar('lastused', time());

  return true;
}

/**
 * Создаем новую сессию
 */
function sysSessionNew($sessid, $ipaddr)
{
  global $dbconn;
  // Generate a random number, used for
  srand((double) microtime() * 1000000);
  sysSessionSetVar('rand', rand());
  //Установка переменных по умолчанию
  sysSessionSetVar('user_theme', sysModGetVar('SYS_config', 'default_theme'));
  sysSessionSetVar('user_lang', sysModGetVar('SYS_config', 'lang'));
  sysSessionSetVar('user_gid', -1);
  sysSessionSetVar('user_id', 0);
  sysSessionSetVar('ipaddr', $ipaddr);
  sysSessionSetVar('firstused', time());
  sysSessionSetVar('lastused', time());

  //Не создаем сессии для ботов
  if (sysUserIsSpider())
    return true;

  //Ложим в базу
  $session_table = sysDBGetTable('session');
  $session_column = sysDBGetColumns($session_table);

  $sql = "INSERT INTO $session_table
   									 ({$session_column['sessid']}, {$session_column['rand']}, {$session_column['user_id']},  {$session_column['user_gid']},
   									 	{$session_column['firstused']}, {$session_column['lastused']}, {$session_column['ipaddr']})
  						   VALUES
  						     ('$sessid', '" . sysSessionGetVar('rand') . "', '0', '-1',
  						      '" . sysSessionGetVar('firstused') . "', '" . sysSessionGetVar('lastused') . "', '$ipaddr'
  						     )";

  //Логируем выборку
  global $dbg_sql_queries;
  $dbg_sql_queries[] = array('sql_query' => $sql);

  mysqli_query($dbconn, $sql);
  if (mysqli_connect_errno())
    sysException('sysSessionNew', DATABASE_ERROR, mysqli_connect_error() . "<br> $sql");

  return true;
}

/**
 * Функция при открытии сессии, например подключение к бд
 */
function sysSessionOpen($path = null, $name = null)
{
  return true;
}

/**
 * Функция при закрытии сессии, например отключение от бд
 */
function sysSessionClose($path = null, $name = null)
{
  return true;
}

/**
 * Функция для чтения переменных сессии
 */
function sysSessionRead($sessid)
{
  if (empty($sessid))
    return null;
  global $dbconn;

  //Кеш для уменьшения наргузки на бд
  static $loaded = array();
  if (isset($loaded[$sessid]) && $loaded[$sessid])
    return $loaded[$sessid];

  //Читаем инфу из базы
  $session_table = sysDBGetTable('session');
  sys_log('sysSessionRead', LOG_INFO, "session_table", $session_table);

  $session_column = sysDBGetColumns($session_table);




  $sql = "SELECT SQL_NO_CACHE {$session_column['user_id']}, {$session_column['lastused']}, {$session_column['vars']}
            FROM $session_table
            WHERE {$session_column['sessid']} = '" . sysVarPrepForStore($sessid) . "'";

  sys_log('sysSessionRead', LOG_INFO, "sql", $sql);

  //Логируем выборку
  global $dbg_sql_queries;
  $dbg_sql_queries[] = array('sql_query' => $sql);

  $mysql_result = mysqli_query($dbconn, $sql);


  if (mysqli_connect_errno())
    sysException('sysSessionRead', DATABASE_ERROR, mysqli_connect_error() . "<br> $sql");

  $sess_dbdate = mysqli_fetch_assoc($mysql_result);
  sys_log('sysSessionRead', LOG_INFO, "sess_dbdate", $sess_dbdate);

  mysqli_free_result($mysql_result);

  //Если с временни lastused прошло больше часа, значит последний визит был - lastused
  if ($sess_dbdate['lastused'] < (time() - 3600)) {
    if ($sess_dbdate['user_id'] > 0) {
      $users_table = sysDBGetTable('users');
      $users_column = sysDBGetColumns($users_table);

      $sql = "UPDATE $users_table
	              SET $users_column[user_lastvisit] = '" . (time()) . "'
	                WHERE $users_column[id] = '" . $sess_dbdate['user_id'] . "'";

      //Логируем выборку
      global $dbg_sql_queries;
      $dbg_sql_queries[] = array('sql_query' => $sql);

      mysqli_query($dbconn, $sql);
      //if (mysql_errno()!=0) sysException ('sysSessionWrite', DATABASE_ERROR, mysql_error()."<br> $sql");
    } else {
      //sysSessionSetVar('user_lastvisit', time());
    }
  }

  //$loaded[$sessid] = $sess_dbdate['vars'];
  $loaded[$sessid] = empty($sess_dbdate['vars']) ? '' : $sess_dbdate['vars'];

  sys_log('sysSessionRead', LOG_INFO, "sessid", $loaded[$sessid]);
  sys_log('sysSessionRead', LOG_INFO, "sess_dbdate", $sess_dbdate);

  return ($loaded[$sessid]);
}

/**
 * Функция для записи переменных сессии
 */
function sysSessionWrite($sessid, $vars)
{

  //sys_log('sysSessionWrite start', LOG_DEBUG, "sessid", $sessid);

  if (!isset($sessid) || !isset($vars))
    return false;
  global $dbconn;

  //БД дергаем только если есть реальные данные для обновления
  $old_vars = sysSessionRead($sessid);
  if ($old_vars === $vars)
    return true;

  //Производим обновление данных
  $session_table = sysDBGetTable('session');
  $session_column = sysDBGetColumns($session_table);

  $sql = "UPDATE LOW_PRIORITY $session_table
              SET $session_column[vars] = '" . sysVarPrepForStore($vars) . "',
              	  $session_column[lastused] = '" . sysSessionGetVar('lastused') . "',
              	  $session_column[rand] = '" . sysSessionGetVar('rand') . "',
              	  $session_column[user_id] = '" . sysSessionGetVar('user_id') . "',
              	  $session_column[user_gid] = '" . sysSessionGetVar('user_gid') . "'
              WHERE $session_column[sessid] = '" . sysVarPrepForStore($sessid) . "'";

  //Логируем выборку
  global $dbg_sql_queries;
  $dbg_sql_queries[] = array('sql_query' => $sql);

  mysqli_query($dbconn, $sql);

  if (mysqli_connect_errno())
    sysException('sysSessionWrite', DATABASE_ERROR, mysqli_connect_error() . "<br> $sql");

  //sys_log('sysSessionWrite end', LOG_DEBUG, "sql", $sql);

  return true;
}

function sysSessionDestroy($sessid)
{
  if (!isset($sessid))
    return false;
  global $dbconn;
  $session_table = sysDBGetTable('session');
  $session_column = sysDBGetColumns($session_table);

  $sql = "DELETE FROM $session_table
            WHERE $session_column[sessid] = '" . sysVarPrepForStore($sessid) . "'";
  //Логируем выборку
  global $dbg_sql_queries;
  $dbg_sql_queries[] = array('sql_query' => $sql);

  mysqli_query($dbconn, $sql);
  // Отключил удаление из базы minalexpro 16/04/2019

  sys_log('sysSessionDestroy', LOG_DEBUG, "sql", $sql);

  if (mysqli_connect_errno())
    sysException('sysSessionDestroy', DATABASE_ERROR, mysqli_connect_error() . "<br> $sql");

  return true;
}

function sysSessionGC($maxlifetime)
{
  global $dbconn;
  $now = time();
  $lifetime = sysModGetVar('SYS_config', 'sesdays') * 86400;
  $old_time = $now - $lifetime;
  //
  $guest_old_time = $now - (3 * 86400);

  $session_table = sysDBGetTable('session');
  $session_column = sysDBGetColumns($session_table);
  $sql = "DELETE FROM $session_table
            WHERE $session_column[lastused] <= '$old_time' OR
                  ($session_column[user_id]=0 AND $session_column[lastused] <= '$guest_old_time')";
  //Логируем выборку
  global $dbg_sql_queries;
  $dbg_sql_queries[] = array('sql_query' => $sql);

  mysqli_query($dbconn, $sql);

  sys_log('sysSessionGC', LOG_DEBUG, "sql", $sql);

  if (mysqli_connect_errno())
    sysException('sysSessionGC', DATABASE_ERROR, mysqli_connect_error() . "<br> $sql");

  return true;
}

/*
 * Проверка являетсали текущий пользователь поисковой системой
 */
function sysUserIsSpider()
{
  $spiders_list = array(
    'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
    'Yandex/1.01.001 (compatible; Win16; I)',
    'StackRambler/2.0 (MSIE incompatible)'
  );
  foreach ($spiders_list as $spider_name)
    if ($_SERVER['HTTP_USER_AGENT'] == $spider_name)
      return true;

  return false;
}

/*
 * Пользователь вошел в систему ?
 */
function sysUserIsLoggedIn()
{
  //sys_log('sysUserIsLoggedIn', LOG_INFO, 'user_id', sysSessionGetVar('user_id'));

  if (sysSessionGetVar('user_id') && is_numeric(sysSessionGetVar('user_id'))) {
    return true;
  } else {
    return false;
  }
}

/*
 * Получить все переменные пользователя
 */
function sysUserGetVars($uid = null)
{



  static $loaded = array();
  if (!isset($uid)) {
    $uid = sysSessionGetVar('user_id');
  }
  ;
  //Проверка, может уже выгребали инфу
  if (isset($loaded[$uid])) {
    return $loaded[$uid];
  }
  ;

  $users_table = sysDBGetTable('users');
  $users_column = sysDBGetColumns($users_table);
  $user_vars = sysDbSelect(
    $users_table,
    $users_column,
    "WHERE $users_column[id] = '$uid'"
  );
  $user_vars['deliver_address'] = unserialize($user_vars['deliver_address']);
  $user_vars['legal_address'] = unserialize($user_vars['legal_address']);
  $user_vars['bank_details'] = unserialize($user_vars['bank_details']);

  //Для безопасности
  unset($user_vars['user_pass']);
  //Кладем в память
  $loaded[$uid] = $user_vars;
  return $user_vars;
}

/*
 * Получить переменную пользователя
 */
function sysUserGetVar($name, $uid = null)
{
  $user_vars = sysUserGetVars($uid);
  return ($user_vars[$name]);
}

/*
 * Установить переменную пользователя
 */
function sysUserSetVar($name, $value, $uid = null)
{
  if (!isset($uid)) {
    $uid = sysSessionGetVar('user_id');
  }
  ;

  //Создаем массив
  $user_vars = array();
  $user_vars[$name] = $value;

  $users_table = sysDBGetTable('users');
  $users_column = sysDBGetColumns($users_table);
  sysDbUpdate(
    $users_table,
    $user_vars,
    "WHERE $users_column[id] = '$uid'"
  );
}


/*
 * Возвращает gid по id
 */
function sysUserGetGid($uid = null)
{
  $gid = sysSessionGetVar('user_gid');
  return $gid;
}
;

/*
 * Возвращает ip текущего пользователя
 * 5.4.0	Массив $HTTP_SERVER_VARS больше недоступен в связи с удалением "длинных массивов".
 */
function sysUserIpAddr()
{

  $httpServerVars = isset($GLOBALS['HTTP_SERVER_VARS']) && is_array($GLOBALS['HTTP_SERVER_VARS'])
    ? $GLOBALS['HTTP_SERVER_VARS']
    : $_SERVER;

  //sys_log('sysUserIpAddr start', LOG_DEBUG, "ipaddr", $tmpipaddr);
  // 
  // Get (actual) client IP addr
  $ipaddr = isset($httpServerVars['REMOTE_ADDR']) ? $httpServerVars['REMOTE_ADDR'] : null;
  if (empty($ipaddr))
    $ipaddr = getenv('REMOTE_ADDR');

  if (!empty($httpServerVars['HTTP_CLIENT_IP']))
    $ipaddr = $httpServerVars['HTTP_CLIENT_IP'];

  $tmpipaddr = getenv('HTTP_CLIENT_IP');
  if (!empty($tmpipaddr))
    $ipaddr = $tmpipaddr;

  if (!empty($httpServerVars['HTTP_X_FORWARDED_FOR'])) {
    $ipaddr = preg_replace('/,.*/', '', $httpServerVars['HTTP_X_FORWARDED_FOR']);
  }

  $tmpipaddr = getenv('HTTP_X_FORWARDED_FOR');
  if (!empty($tmpipaddr)) {
    $ipaddr = preg_replace('/,.*/', '', $tmpipaddr);
  }

  //sys_log('sysUserIpAddr', LOG_DEBUG, "ipaddr", $tmpipaddr);

  return $ipaddr;
}
;

/*
 * Возвращает тему текущего пользователя
 */
function sysUserTheme()
{
  sys_log('sysUserTheme', LOG_INFO, "0 ");
  //Проверка может уже узнавали ?
  static $loaded = false;
  if ($loaded)
    return $loaded;

  //Смотрим че у нас в сесии
  $themename = sysSessionGetVar('user_theme');

  sys_log('sysUserTheme', LOG_INFO, "1 Tема в переменных пользователя \"$themename\" ");

  sys_log('sysUserTheme', LOG_INFO, "_SESSION", $_SESSION);

  //Проверка активнали тема
  if (!sysThemeAvailable($themename)) {
    sys_log('sysUserTheme', LOG_INFO, "2 Tема в переменных пользователя \"$themename\" ");

    sys_log('sysUserTheme', LOG_WARNING, "Недоступная тема в переменных пользователя \"$themename\", заменили на тему по умолчанию");
    $themename = sysModGetVar('SYS_config', 'default_theme');
    sysSessionSetVar('user_theme', $themename);
    //Задаем в настройках пользователя тему по умолчанию
    $uid = sysSessionGetVar('user_id');
    if ($uid > 0) {
      sysUserSetVar('user_theme', $themename, $uid);
    }
    ;
  }
  ;

  sys_log('sysUserTheme', LOG_INFO, "3 Tема в переменных пользователя \"$themename\" ");
  $loaded = $themename;
  return $themename;
}
;

/*
 * Exception - возникает при возникновении ошибки
 */
function sysException($sender, $excType = BAD_PARAM, $message = '', $vars = null)
{
  static $in_exception = false;

  // Защита от рекурсии: обработчик ошибки сам вызывает функции БД,
  // которые при сбое снова вызывают sysException (вплоть до исчерпания памяти).
  if ($in_exception)
    {
    if (defined('SYS_INSTALL_MODE') && SYS_INSTALL_MODE)
      {
      http_response_code(500);
      echo '<h1>Ошибка установки CMS</h1><pre>'
        . htmlspecialchars($sender . ': ' . $message, ENT_QUOTES, 'UTF-8') . '</pre>';
      exit;
      }
    error_log('sysException re-entered: ' . $sender . ': ' . $message . "\n", 3, 'var/log/messages.log');
    return false;
    }
  $in_exception = true;

  global $module;
  global $type;
  global $func;
  $uri = sysGetCurrURI();
  $referer = $_SERVER['HTTP_REFERER'];

  //echo $uri; exit;

  //Пишим в журнал
  $vars['module'] = $module;
  $vars['type'] = $type;
  $vars['func'] = $func;
  $vars['referer'] = $uri;
  $vars['excType'] = $excType;
  sys_log($sender, LOG_ERR, $message, $vars);

  // До установки модулей нельзя использовать тему, сессии и редиректы CMS.
  if (defined('SYS_INSTALL_MODE') && SYS_INSTALL_MODE) {
    http_response_code(500);
    echo '<h1>Ошибка установки CMS</h1><p>'
      . htmlspecialchars($sender, ENT_QUOTES, 'UTF-8') . '</p><pre>'
      . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</pre>';
    exit;
  }

  $onException = sysModGetVar('SYS_config', 'onException');
  $main_page_url = sysModGetVar('SYS_config', 'site_url');
  if (!$main_page_url)
    $main_page_url = "/";


  switch ($onException) {
    case 1:
      if (($module) and (sysModAvailable($module)))
        sysShowMessage('/index.php?module=' . $module, 'Ошибка! ', 5);
      else
        sysShowMessage($main_page_url, 'Ошибка! ', 5);
      break;
    case 2:
      switch ($excType) {
        case ID_NOT_EXIST:
          sysSessionSetVar('url', $uri);
          sysSessionSetVar('message', $message);
          sysSessionSetVar('excType', $excType);
          header("HTTP/1.0 404  Not Found");
          header("HTTP/1.1 404 Not Found");
          header("Status: 404  Not Found");

          $loadedmod = sysModLoad('SYS_config', 'user');
          $modresult = sysModFunc('SYS_config', 'user', 'errorpage_view', array('page' => 404));
          $modresult['user_theme'] = sysUserTheme();
          sysShowModPage($modresult);
          sysTerminate();
          ob_end_flush();
          break;

        case NO_PERMISSION:
          sysSessionSetVar('url', $uri);
          sysSessionSetVar('message', $message);
          sysSessionSetVar('excType', $excType);
          $user_id = sysUserGetVar('id');
          if (!empty($user_id)) {
            sysRedirect(sysGetSiteUrl() . "error.html");
          } else
            sysRedirect(sysModUrl('SYS_users', 'user', 'loginscreen'));
          break;

        case DATABASE_ERROR:
          sysSessionSetVar('excType', $excType);
          sysRedirect(sysGetSiteUrl() . "error.html");

          break;

        default:
          sysSessionSetVar('excType', $excType);
          sysRedirect(sysGetSiteUrl() . "error.html");

          break;
      }
      break;
    case 3:
      echo "Caught exception : $message <br>";
      echo "Exception type : $excType<br>";
      echo "Sender : $sender<br><br>";
      echo "Module : $module<br>";
      echo "Module type: $type<br>";
      echo "Funk : $func<br>";
      echo "URI : $uri<br>";
      echo "REFERER : $referer<br>";
      break;
    default:
      sysShowMessage($main_page_url, 'Ошибка! ', 5);
  }
  sysTerminate();
  exit;


}

function sysShowModPage($modresult = array())
{
  global $sysTpl;
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  sysBlockRenderAll($sysTpl, $modresult['object']);
  $sysTpl->assign($modresult);
  $sysTpl->assign('module_content', $modresult['content'], false);
  $sysTpl->assign('module_browsein', $modresult['browsein']);
  $sysTpl->assign('module_page_title', $modresult['page_title']);
  $sysTpl->assign('module_meta_keywords', $modresult['meta_keywords']);
  $sysTpl->assign('module_meta_description', $modresult['meta_description']);
  $sysTpl->assign('module_meta_title', $modresult['meta_title']);
  $sysTpl->assign('module_object', $modresult['object']);
  $sysTpl->assign('module_encoding', $modresult['encoding']);
  // Проверка может надо вывести сообщение
  $msg = sysCaptureMessage();
  if ($msg['message']) {
    $sysTpl->assign('message', $msg['message']);
  }

  $pageTplFile = sysTplPageWay($modresult['object']);
  $pagecontents = $sysTpl->fetch($pageTplFile);

  //sys_log('sysShowModPage end', LOG_DEBUG, "msg", $msg);

  echo ($pagecontents);

}


/*
 * Запись сообшения в журнал событий
 */
function sys_log($sender, $level = LOG_DEBUG, $message = '', $vars = null, $log_module = 'messages')
{
  static $in_log = false;

  // Защита от рекурсии: sys_log может сам вызвать sysException через DB-функции.
  if ($in_log)
    return true;
  $in_log = true;
  $result = sys_log_write($sender, $level, $message, $vars, $log_module);
  $in_log = false;
  return $result;
}

function sys_log_write($sender, $level = LOG_DEBUG, $message = '', $vars = null, $log_module = 'messages')
{
  // При установке таблиц модулей ещё нет — в БД не лезем, пишем только в файл.
  if (defined('SYS_INSTALL_MODE') && SYS_INSTALL_MODE)
    {
    $str_time = date('d.m.y H:i:s');
    $log_str = sprintf("[%s] [%s] [%s] %s %s\r\n", $str_time, $sender, $level, $message,
      isset($vars) ? var_export($vars, true) : '');
    error_log($log_str, 3, 'var/log/' . ($log_module ? $log_module : 'messages') . '.log');
    return true;
    }

  $debug = sysModGetVar('SYS_config', 'debug');



  if (!$debug && $level == LOG_DEBUG)
    return true;

  static $levels = array(
  LOG_EMERG => 'EMERG',
  LOG_ALERT => 'ALERT',
  LOG_CRIT => 'CRIT',
  LOG_ERR => 'ERR',
  LOG_WARNING => 'WARNING',
  LOG_NOTICE => 'NOTICE',
  LOG_INFO => 'INFO',
  LOG_DEBUG => 'DEBUG',
  );
  $level = $levels[$level];

  //$str_time = strftime ('%d.%m.%y %H:%M', time());

  $str_time = date('d.m.y H:i:s');

  $user_ip = sysUserIpAddr();
  $user_id = sysSessionGetVar('user_id');



  /**/
  if (isset($vars)) {
    $vars = var_export($vars, true);
    $log_str = sprintf("[%s] [%s] [%s] [uid=%s] [%s] %s [%s]\r\n", $str_time, $level, $user_ip, $user_id, $sender, $message, $vars);
  } else {
    $log_str = sprintf("[%s] [%s] [%s] [uid=%s] [%s] %s\r\n", $str_time, $level, $user_ip, $user_id, $sender, $message);
  }

  if (!$log_module)
    $log_module = 'messages';

  if (filesize('var/log/' . $log_module . '.log') > 2 * 1024 * 1024) {
    $i = 1;
    while (file_exists('var/log/' . $log_module . '[' . $i . '].log'))
      $i++;

    $url = sysGetSiteUrl() . 'var/log/' . $log_module . '.log';
    $path = 'var/log/' . $log_module . '[' . $i . '].log';
    file_put_contents($path, file_get_contents($url));
    @unlink('var/log/' . $log_module . '.log');

    //$str_time = strftime ('%d.%m.%y %H:%M', time());
    error_log('Start log file at ' . $str_time . "\r\n", 3, 'var/log/' . $log_module . '.log');

    chmod('var/log/' . $log_module . '.log', 0777);
  }

  error_log($log_str, 3, 'var/log/' . $log_module . '.log');
}

/*
 * Проверяет незабенен ли IP
 */
function sysIsIpBanned()
{
  if (sysModGetVar('SYS_config', 'enable_badips') == 1) {
    $user_ip = sysUserIpAddr();
    $bad_ips = explode("\r\n", sysModGetVar('SYS_config', 'bad_ips'));
    foreach ($bad_ips as $bi) {
      if (!empty($bi) && preg_match('/' . $bi . '/iU', $user_ip)) {
        sys_log('sysUserIsIpBanned', LOG_DEBUG, "Доступ с IP \"$user_ip\" запрешен правилом \"$bi\"");
        return true;
      }
    }
  }
  ;

  return false;
}

/*
 * Проверяет незакрыт ли сайт
 */
function sysIsSiteClosed()
{
  return sysModGetVar("SYS_config", "closesite");
}

/*
 * Редирект на урл
 */
function sysRedirect($redirecturl)
{

  //Выключаем систему
  sysTerminate();

  //Меняем &amp; -> &
  $redirecturl = str_replace('&amp;', '&', $redirecturl);

  if (preg_match('!^http!', $redirecturl)) {
    // Absolute URL - simple redirect
    Header("Location: $redirecturl");
  } else {
    // Removing leading slashes from redirect url
    $redirecturl = preg_replace('!^/*!', '', $redirecturl);

    // Get base URL
    $baseurl = sysGetBaseURL();

    Header("Location: $baseurl$redirecturl");
  }
  exit;
}

/*
 * Функция устанавливает сообщение
 */
function sysShowMessage($url, $message = '', $time = 1)
{
  sysSessionSetVar('sysMsgUrl', $url);
  sysSessionSetVar('sysMsgMessage', $message);
  sysSessionSetVar('sysMsgTime', $time);
  sysRedirect($url);

  //Выключаем систему
  sysTerminate();
  exit;
}
;

/*
 * Функция проверяет если было установлеено собшение выводит его
 */
function sysCaptureMessage()
{

  $url = sysSessionGetVar('sysMsgUrl');

  //sys_log('sysCaptureMessage ', LOG_DEBUG, "start", $url);

  if (!isset($url)) {
    return false;//Выходим если нет мессаги
  }
  ;
  //Меняем &amp; -> & HTML 4.01 :))
  $url = str_replace('&amp;', '&', $url);

  global $module;
  if (!empty($module) && strstr($url, 'index.php?') && !strstr($url, $module) && !strstr($url, str_replace('SYS_', '', $module))) //Курлы
  {
    return false;//ВЫходим если мессага не для нашева модуля
  }
  ;

  //Если дошли до сюда отоброжаем мессагу
  $message = sysSessionGetVar('sysMsgMessage');
  $time = sysSessionGetVar('sysMsgTime');

  //sys_log('sysCaptureMessage ', LOG_DEBUG, "message", $message);

  //Удаляем из сессии информацию
  sysSessionDelVar('sysMsgUrl');
  sysSessionDelVar('sysMsgMessage');
  sysSessionDelVar('sysMsgTime');

  $res = array('message' => $message, 'url ' => $url);
  return $res;

  //Удаляем из сессии информацию
  sysSessionDelVar('sysMsgUrl');
  sysSessionDelVar('sysMsgMessage');
  sysSessionDelVar('sysMsgTime');
  //Контрольный редирект
  header("Refresh: $time;url=$url");
  //Рендерим
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;
  $sysTpl->assign('url', $url);
  $sysTpl->assign('message', $message);
  $sysTpl->assign('time', $time);
  //Смотрим че у нас в сесии (Какая тема)
  $themename = sysUserTheme();
  $msgcontent = $sysTpl->fetch("themes/$themename/messages/normal.tpl");
  echo ($msgcontent);
  //Останавливаем систему
  sysTerminate();
  exit;
}
;

/*
 * Загрузка Дополнительной библиотеки ядра
 */
function sysExtLibLoad($lib_name = 'extlib')
{
  //Проверка может уже загружали ?
  static $loaded = array();
  if ($loaded[$lib_name])
    return true;

  //загружаем файл
  include_once './kernel/' . $lib_name . '.php';

  $loaded[$lib_name] = true;
  return true;
}

/*
 * Базовый ури сайта, нужен для сессий
 */
function sysGetBaseURI()
{

  // Start of with REQUEST_URI
  if (isset($_SERVER['REQUEST_URI'])) {
    $path = $_SERVER['REQUEST_URI'];
  } else {
    $path = getenv('REQUEST_URI');
  }

  if ((empty($path)) || (substr($path, -1, 1) == '/')) {
    // REQUEST_URI was empty or pointed to a path
    // Try looking at PATH_INFO
    $path = getenv('PATH_INFO');
    if (empty($path)) {
      // No luck there either
      // Try SCRIPT_NAME
      if (isset($_SERVER['SCRIPT_NAME'])) {
        $path = $_SERVER['SCRIPT_NAME'];
      } else {
        $path = getenv('SCRIPT_NAME');
      }
    }
  }

  $path = preg_replace('/[#\?].*/', '', $path);
  $path = dirname($path);

  if (preg_match('!^[/\\\]*$!', $path)) {
    $path = '';
  }

  return $path;
}

/*
 * Возврашает базовый урл для сайта
 */
function sysGetBaseURL()
{

  if (empty($_SERVER['HTTP_HOST'])) {
    $server = getenv('HTTP_HOST');
  } else {
    $server = $_SERVER['HTTP_HOST'];
  }

  // IIS sets HTTPS=off
  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    $proto = 'https://';
  } else {
    $proto = 'http://';
  }

  $path = sysGetBaseURI();

  return "$proto$server$path/";
}

/*
 * Возврашает адрес сайта
 */
function sysGetSiteURL()
{

  if (empty($_SERVER['HTTP_HOST'])) {
    $server = getenv('HTTP_HOST');
  } else {
    $server = $_SERVER['HTTP_HOST'];
  }

  // IIS sets HTTPS=off
  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    $proto = 'https://';
  } else {
    $proto = 'http://';
  }

  return "$proto$server/";
}

/*
 * Возврашает полный адрес страницы со всеми параметрами
 */
function sysGetCurrURI()
{

  if (empty($_SERVER['HTTP_HOST'])) {
    $server = getenv('HTTP_HOST');
  } else {
    $server = $_SERVER['HTTP_HOST'];
  }

  // IIS sets HTTPS=off
  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    $proto = 'https://';
  } else {
    $proto = 'http://';
  }

  $uri = $_SERVER['REQUEST_URI'];

  return "$proto$server$uri";
}


/*
 * Загрузка языка для модуля или блока
 */
function sysLoadLang(&$sysTpl, $compname, $type = 'module', $filename = 'lang.conf')
{
  $userlang = sysSessionGetVar('user_lang');
  $deflang = 'rus';

  if ($type == 'block') {//Если тип - блок
    if (file_exists("blocks/$compname/lang/$userlang/$filename")) {
      $sysTpl->config_load("blocks/$compname/lang/$userlang/$filename");
    } elseif (file_exists("blocks/$compname/lang/$deflang/$filename")) {
      $sysTpl->config_load("blocks/$compname/lang/$deflang/$filename");
    }
  } elseif ($type == 'module') {//Если тип - модуль
    if (file_exists("modules/$compname/lang/$userlang/$filename")) {
      $sysTpl->config_load("modules/$compname/lang/$userlang/$filename");
    } elseif (file_exists("modules/$compname/lang/$deflang/$filename")) {
      $sysTpl->config_load("modules/$compname/lang/$deflang/$filename");
    }
  }
  ;

  return true;
}
;

function sysDbSort($indata)
{

  foreach ($indata as $v) {
    if (!isset($v['id'])) {
      return false;
    }
  }
  $indata_sort = array();

  foreach ($indata as $v) {
    $indata_sort[$v['id']] = $v;
  }
  return $indata_sort;
}

/**
 * Возвращает массив названий таблиц с преффиксом и массив полей этих таблиц
 *
 * @param $tableNames
 * @return array $table
 */
function getCulmns($tableNames)
{
  //global $table;
  if (is_array($tableNames)) {
    foreach ($tableNames as $tableName) {
      $table[$tableName]['tableName'] = sysDBGetTable($tableName);
      $table[$tableName]['tableCulmns'] = sysDBGetColumns($table[$tableName]['tableName']);
    }
  } else {
    $table[$tableNames]['tableName'] = sysDBGetTable($tableNames);
    $table[$tableNames]['tableCulmns'] = sysDBGetColumns($table[$tableNames]['tableName']);
  }
  return $table;
} ?>
