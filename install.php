<?php
/*
 * Порядок инсталяции
 * 1. Выбор языка
 * 2. Лицензионное соглашение
 * 3. Проверка системы
 * 4. Запрос параметров базы данных. -> Инсталяция системы
 * 5. Создание учетной записи администратора ->....
 * 6. Поздравление
*/

//Загрузили стистему, шаблоны к сожелению мы юзать не умеем
define('SYS_INSTALL_MODE', true);
include_once 'kernel/API.php';

//Конфигурим PHP
set_time_limit(600);

//В зависимости от стадии инсталяции выдаем соответствуюшую страницу
$sys_install_step = sysVarCleanFromInput ('sys_install_step');
switch ($sys_install_step)
  {
  case 'lang_select'://Левые блоки
      $step_result = lang_select();
      break;
  case 'license':
      $step_result = license();
      break;
  case 'check_system':
      $step_result = check_system();
      break;
  case 'sys_setup':
      $step_result = sys_setup();
      break;
  case 'sys_install':
      $step_result = sys_install();
      break;
  case 'admin_setup':
      $step_result = admin_setup();
      break;
  default:
      $step_result = lang_select();
  }

//Вывод результатов
$page_head = implode ('', file ('install/tpl/head.htm'));
$page_foot = implode ('', file ('install/tpl/foot.htm'));
echo $page_head.$step_result.$page_foot;



/*
 * Функции - Шаги инсталяции
*/

/*
 * Выбор языка инсталяции и системы
 * Не реализованно
*/
function lang_select()
  {
  $html = implode ('', file ('install/tpl/lang_select.htm'));
  return $html;
  }

/*
 * Лицензионное соглашение
*/
function license()
  {
  $html = implode ('', file ('install/tpl/license.htm'));
  return $html;
  }

/*
 * Проверка системы
*/
function check_system()
  {
  $error = false;
  $sysTpl = new basicSysTpl;

  //Версия PHP
  if (version_compare(phpversion(), '4.3.2', '>='))
    {
    $sysTpl->assign ('php_state', '<td>Ok</td><td>Версия PHP 4.3.2 или выше, у Вас установлена '.phpversion().'</td>');
    }
    else
      {
      $sysTpl->assign ('php_state', '<td>Error</td><td>Версия PHP 4.3.2 или выше, у Вас установлена '.phpversion().'</td>');
      $error = true;
      }

  //Возможность записи в ./var/configs/system.php
  if (is_writable ('./var/configs/system.php'))
    {
    $sysTpl->assign ('config_state', '<td>Ok</td><td>Инсталятор может писать в ./var/configs/system.php</td>');
    }
    else
      {
      $sysTpl->assign ('config_state', '<td>Error</td><td>Инсталятор НЕ может писать в ./var/configs/system.php</td>');
      $error = true;
      }

  //Возможность записи в ./var/log
  if (is_writable ('./var/log'))
    {
    $sysTpl->assign ('log_state', '<td>Ok</td><td>Инсталятор может писать в ./var/log</td>');
    }
    else
      {
      $sysTpl->assign ('log_state', '<td>Error</td><td>Инсталятор НЕ может писать в ./var/log</td>');
      $error = true;
      }


  //Возможность записи в ./var/modules
  if (is_writable ('./var/modules'))
    {
    $sysTpl->assign ('modules_state', '<td>Ok</td><td>Инсталятор может писать в ./var/modules</td>');
    }
    else
      {
      $sysTpl->assign ('modules_state', '<td>Error</td><td>Инсталятор НЕ может писать в ./var/modules</td>');
      $error = true;
      }

  //Возможность записи в ./cache/vars
  if (is_writable ('./cache/vars'))
    {
    $sysTpl->assign ('vars_state', '<td>Ok</td><td>Инсталятор может писать в ./cache/vars</td>');
    }
    else
      {
      $sysTpl->assign ('vars_state', '<td>Error</td><td>Инсталятор НЕ может писать в ./cache/vars</td>');
      $error = true;
      }


  //Возможность записи в ./cache/templates
  if (is_writable ('./cache/templates'))
    {
    $sysTpl->assign ('templates_state', '<td>Ok</td><td>Инсталятор может писать в ./cache/templates</td>');
    }
    else
      {
      $sysTpl->assign ('templates_state', '<td>Error</td><td>Инсталятор НЕ может писать в ./cache/templates</td>');
      $error = true;
      }

  //Возможность записи в ./cache/content
  if (is_writable ('./cache/content'))
    {
    $sysTpl->assign ('content_state', '<td>Ok</td><td>Инсталятор может писать в ./cache/content</td>');
    }
    else
      {
      $sysTpl->assign ('content_state', '<td>Error</td><td>Инсталятор НЕ может писать в ./cache/content</td>');
      $error = true;
      }

  //Если ошибка, перепровряем......
  if ($error)
    {
    $sysTpl->assign ('install_step_name', 'check_system');
    $sysTpl->assign ('install_btn_name', 'Проверить');
    }
    else
      {
      $sysTpl->assign ('install_step_name', 'sys_setup');
      $sysTpl->assign ('install_btn_name', 'Продолжить');
      }

  //Все рендерим и выводим
  $html = $sysTpl->fetch ('install/tpl/check_system.htm');

  return $html;
  }

/*
 * Параметры базы данных
*/
function sys_setup()
  {
  //Все рендерим и выводим
  $sysTpl = new basicSysTpl;
  $sysTpl->assign ('error_msg', '');
  $html = $sysTpl->fetch ('install/tpl/sys_setup.htm');
  return $html;
  }

/*
 * Собствеено инстал
*/
function sys_install()
  {
  global $dbconn;
  //Переменные со входа
  list ($db_host, $db_username, $db_password, $db_name,
        $db_tableprefix, $create_db, $db_setnames,$var_caching, $default_file_perms,
        $default_dir_perms, $systpl_use_sub_dirs, $systpl_cache_lifetime)
          = sysVarCleanFromInput('db_host', 'db_username', 'db_password', 'db_name',
                                 'db_tableprefix', 'create_db', 'db_setnames','var_caching', 'default_file_perms',
                                 'default_dir_perms', 'systpl_use_sub_dirs', 'systpl_cache_lifetime');
								 
	$locale = 'ru_RU.UTF-8';

  error_reporting  (E_ERROR);
  $sysTpl = new basicSysTpl;

  //Проверяем можемли подключиться к бд
  $dbconn = mysql_connect ($db_host, $db_username, $db_password);
  if (mysql_errno()!=0)
    {
    // Сообшение об ошибке + запрос параметров
    $sysTpl->assign ('error_msg', 'Ошибка соединения с базой данных ! '.mysql_error());
    $html = $sysTpl->fetch ('install/tpl/sys_setup.htm');
    return $html;
    }

  //Выбираем базу
  if ($create_db)
    {
    $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
    mysql_query($sql);
    if (mysql_errno()==0)  mysql_select_db ($db_name);
    }
    else
      {
      mysql_select_db ($db_name);
      }
  if (mysql_errno()!=0)
    {
    // Сообшение об ошибке + запрос параметров
    $sysTpl->assign ('error_msg', 'Ошибка ! '.mysql_error());
    $html = $sysTpl->fetch ('install/tpl/sys_setup.htm');
    return $html;
    }
	mysql_set_charset ($db_setnames);

  //Создаем конфигурационный файл
  $sysTpl = new basicSysTpl;
  $sysTpl->assign ('db_host', $db_host);
  $sysTpl->assign ('db_username', $db_username);
  $sysTpl->assign ('db_password', $db_password);
  $sysTpl->assign ('db_name', $db_name);
  $sysTpl->assign ('db_tableprefix', $db_tableprefix);
  $sysTpl->assign ('db_setnames', $db_setnames);
  $sysTpl->assign ('locale', $locale);
  $sysTpl->assign ('var_caching', $var_caching);
  $sysTpl->assign ('default_file_perms', $default_file_perms);
  $sysTpl->assign ('default_dir_perms', $default_dir_perms);
  $sysTpl->assign ('systpl_use_sub_dirs', $systpl_use_sub_dirs);
  $sysTpl->assign ('systpl_cache_lifetime', (int)$systpl_cache_lifetime);
  $file_content = $sysTpl->fetch ('install/tpl/system_php.htm');
  if (!$fd = fopen('./var/configs/system.php', 'w'))
    {
    // Сообшение об ошибке + запрос параметров
    $sysTpl->assign ('error_msg', 'Ошибка открытия файла ./var/configs/system.php !');
    $html = $sysTpl->fetch ('install/tpl/sys_setup.htm');
    return $html;
    }
  if (!fwrite($fd, $file_content))
    {
    // Сообшение об ошибке + запрос параметров
    $sysTpl->assign ('error_msg', 'Ошибка записи в файла ./var/configs/system.php !');
    $html = $sysTpl->fetch ('install/tpl/sys_setup.htm');
    return $html;
    }
  fclose($fd);

  //Чистим кеша системы на случай установки поверх...
  recUnlink ('./var/log', false);
  recUnlink ('./var/session', false);
  recUnlink ('./var/modules', false);
  recUnlink ('./cache/vars', false);
  recUnlink ('./cache/templates', false);
  recUnlink ('./cache/content', false);

  //Из него читаем базовые настройки системы
  global $sysConfig;
  include_once ('./var/configs/system.php');

  //Создаем таблицы и инсталим систему
  // Создаем таблицу таблиц
  include_once ('./modules/SYS_df/class.php');//sysModClassLoad
  $SYS_df = new SYS_df();
  $tables_tables = sysDBGetTable ('tables');
  $SYS_df->CreateTable($tables_tables, array(
      'id' =>                 array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '0',
                                    'increment'   => true,
                                    'primary_key' => true),

      'table_name' =>         array('type'       => 'varchar',
                                    'size'       => 64,
                                    'null'       => false,
                                    'index'       => true,
                                    'default'    => ''),

      'table_field' =>        array('type'       => 'varchar',
                                    'size'       => 64,
                                    'null'       => false,
                                    'index'       => true,
                                    'default'    => ''),

      'table_type' =>         array('type'       => 'varchar',
                                    'size'       => 64,
                                    'null'       => false,
                                    'default'    => ''),

      'table_size'  =>        array('type'       => 'varchar',
                                    'size'       => '64',
                                    'null'       => false,
                                    'default'    => '0'),

      'table_default'=>       array('type'       => 'varchar',
                                    'size'       => '255',
                                    'null'       => false,
                                    'default'    => '0'),

      'table_null'=>          array('type'       => 'tinyint',
                                    'size'       => '4',
                                    'null'       => false,
                                    'default'    => '0'),

      'table_unsigned' =>     array('type'       => 'tinyint',
                                    'size'       => '4',
                                    'null'       => false,
                                    'default'    => '0'),

      'table_increment'=>     array('type'       => 'tinyint',
                                    'size'       => '4',
                                    'null'       => false,
                                    'default'    => '0'),

      'table_primary_key' =>  array('type'       => 'tinyint',
                                    'size'       => '4',
                                    'null'       => false,
                                    'default'    => '0'),

      'table_index'=>         array('type'       => 'tinyint',
                                    'size'       => '4',
                                    'null'       => false,
                                    'default'    => '0'),

      'table_unique'=>        array('type'       => 'tinyint',
                                    'size'       => '4',
                                    'null'       => false,
                                    'default'    => '0'),

      'table_fulltext'=>      array('type'       => 'tinyint',
                                    'size'       => '4',
                                    'null'       => false,
                                    'default'    => '0'),

      'table_candelete'=>     array('type'       => 'tinyint',
                                    'size'       => '1',
                                    'null'       => false,
                                    'default'    => '0'),

      'table_canmodify'=>     array('type'       => 'tinyint',
                                    'size'       => '1',
                                    'null'       => false,
                                    'default'    => '0')));

	//Инсталируем SYS_modules
	include_once ('modules/SYS_modules/init.php');
	SYS_modules_init();

	//Инсталируем оставшиеся модули
	include_once ('modules/SYS_modules/class.php');//sysModClassLoad ('SYS_modules'); Грузим класс управления модулями
	$SYS_modules = new SYS_modules();

	//Стадия 1 через SYS_modules
	$SYS_modules->regen(); //Регенирировали список модулей
	$SYS_modules->activate('SYS_modules');

	//Стадия 2 SYS_themes + SYS_modules
	$SYS_modules->initialise('SYS_themes');
	$SYS_modules->activate('SYS_themes');
  
	//Настройки тем SYS_modules
	//Регистрируем темплеи SYS_modules в системе
	$SYS_themes = new SYS_themes();
	$SYS_themes->tpl_add (array('tpl_compname'=>'SYS_modules',
                              'tpl_name'=>'admin_main.tpl',
                              'tpl_pattern'=>'SYS_modules::admin::main',
                              'tpl_description'=>'Шаблон титульной страницы'));

	$SYS_themes->tpl_add (array('tpl_compname'=>'SYS_modules',
                              'tpl_name'=>'admin_info.tpl',
                              'tpl_pattern'=>'SYS_modules::admin::info',
                              'tpl_description'=>'Информация о модуле'));

	//Динамические поля
	$SYS_modules->initialise('SYS_df');
	$SYS_modules->activate('SYS_df');

	//Блоки
	$SYS_modules->initialise('SYS_blocks');
	$SYS_modules->activate('SYS_blocks');

	//Конфигуратор сайта
	$SYS_modules->initialise('SYS_config');
	$SYS_modules->activate('SYS_config');

	//Группы
	$SYS_modules->initialise('SYS_groups');
	$SYS_modules->activate('SYS_groups');
  
	//Пользователи
	$SYS_modules->initialise('SYS_users');
	$SYS_modules->activate('SYS_users');


	//Безопасность
	$SYS_modules->initialise('SYS_permissions');
	$SYS_modules->activate('SYS_permissions');

	//Стадия 3 все остальное
	//Контент
	$SYS_modules->initialise('content');
	$SYS_modules->activate('content');
	
	//Меню
	$SYS_modules->initialise('menu');
	$SYS_modules->activate('menu');
 
	//Файлы
	$SYS_modules->initialise('fm');
	$SYS_modules->activate('fm');
  
  //Поиск
	$SYS_modules->initialise('search');
	$SYS_modules->activate('search');
	
	//Обратная связь
	$SYS_modules->initialise('contact');
	$SYS_modules->activate('contact');



  /*
   * Дефайлтные настройки
  */

	//Группы
	sysModClassLoad ('SYS_groups');
	$SYS_groups = new SYS_groups;
	$admin_group_id = $SYS_groups->create (array ('group_displayname' => 'Администраторы',
                                                'group_description' => 'Администраторы сайта'));
	$users_group_id = $SYS_groups->create (array ('group_displayname' => 'Пользователи',
                                                'group_description' => 'Пользователи сайта'));


	//Добавляем блоки 
	sysModClassLoad ('SYS_blocks');
	$SYS_blocks = new SYS_blocks;
 
	//главное меню

	$mmid = $SYS_blocks->add ('menu_block');
	$param = $SYS_blocks->get_info($mmid);
	$param['block_position'] = 't';
	$SYS_blocks->update ($param);
	sysBlockSetVar ($mmid, 'parent_id', 1);
	sysBlockSetVar ($mmid, 'menu_type', 1);
	sysBlockSetVar ($mmid, 'template', 'horizontal_default');
	$SYS_blocks->activate ($mmid);
 
	//админское меню
	$mid = $SYS_blocks->add ('menu_block');
	$param = array('block_position' => 'e', 'id' => $mid, 'block_tpl' => 'admin_menu_display.tpl');
	$SYS_blocks->update ($param);
	sysBlockSetVar ($mid, 'parent_id', 6);
	sysBlockSetVar ($mid, 'menu_type', 1);
	sysBlockSetVar ($mid, 'template', 'vertical_default');
	$SYS_blocks->activate ($mid);


	//userlogin_block
	$uid = $SYS_blocks->add ('userlogin_block');
	$SYS_blocks->activate ($uid);
	$param = array('block_position'=>'t', 'id' => $uid);
	$SYS_blocks->update ($param);
	
	//contact_block
	$cid = $SYS_blocks->add ('contact_block');
	$SYS_blocks->activate ($cid);
	$param = array('block_position'=>'b', 'id' => $cid, 'block_pattern' => 'content::user::folder::view::4(::|$)');
	$SYS_blocks->update ($param);

 /*
  //xcontent_block
$xid = $SYS_blocks->add ('xcontent_block');
$SYS_blocks->activate ($xid);

$param=array();
$param['block_position']='c';
$param['block_pattern']='MainPage';
$param['id']=$xid;
$SYS_blocks->update ($param);

  sysBlockSetVar ($xid, 'folder_id', 1);
  sysBlockSetVar ($xid, 'doc_orderby', 'pub_datetime');
  sysBlockSetVar ($xid, 'doc_order_asc', 0);
   sysBlockSetVar ($xid, 'doc_count', 10);
    sysBlockSetVar ($xid, 'include_subfolders', 1);
    sysBlockSetVar ($xid, 'block_ttl', 60);
*/

	//Добавляем правила безопасности
	sysModClassLoad ('SYS_permissions');
	$SYS_permissions = new SYS_permissions;
	$SYS_permissions->groups_perms_create (array (
                             'gid'         => $admin_group_id,
                             'description' => 'Администраторы - максимальный уровень доступа к системе.',
                             'component'   => 0,
                             'pattern'     => '.*',
                             'level'       => ACCESS_ADMIN));

	$SYS_permissions->groups_perms_create (array (
                             'gid'         => 0,
                             'description' => 'Отображение капчи',
                             'component'   => 0,
                             'pattern'     => 'ajax::captcha_view',
                             'level'       => ACCESS_ADD));
							 
	$SYS_permissions->groups_perms_create (array (
                             'gid'         => 0,
                             'description' => 'Запрет просмотра заявок',
                             'component'   => 'content',
                             'pattern'     => 'user::doc::view::4',
                             'level'       => ACCESS_NONE));
							 

	$SYS_permissions->groups_perms_create (array (
                             'gid'         => 0,
                             'description' => 'Создание заявки и вопроса FAQ',
                             'component'   => 'content',
                             'pattern'     => 'admin::doc::.*::(3|4)',
                             'level'       => ACCESS_ADD));

	$SYS_permissions->groups_perms_create (array (
                             'gid'         => 0,
                             'description' => 'Меню админа',
                             'component'   => 'menu_block',
                             'pattern'     => 'display::'.$mid,
                             'level'       => ACCESS_NONE));

	$SYS_permissions->groups_perms_create (array (
                             'gid'         => 0,
                             'description' => 'Все - только чтение.',
                             'component'   => 0,
                             'pattern'     => '.*',
                             'level'       => ACCESS_READ));


    
	$sysTpl = new basicSysTpl;
   
	// $html = $sysTpl->fetch ('install/tpl/theme_setup.htm');
	$html = $sysTpl->fetch ('install/tpl/admin_setup.htm');
	return $html;
}



/*
 * Создание темы
*/
function theme_setup ()
  {
  //Переменные со входа
  $default_theme= sysVarCleanFromInput('default_theme');

  sysInit();
  sysModSetVar ('SYS_config','default_theme', $default_theme);
  sysModSetVar ('SYS_config','site_logo', '/themes/'.$default_theme.'/images/logo.png');

  include_once ('modules/SYS_themes/class.php'); //Грузим класс управления модулями
  $SYS_themes = new SYS_themes;

  //Регенирировали список тем в системе
  $SYS_themes->regen();


  $sysTpl = new basicSysTpl;
  $html = $sysTpl->fetch ('install/tpl/admin_setup.htm');
  return $html;
  }





/*
 * Создание админа
*/
function admin_setup ()
  {
  //Переменные со входа
  list ($user_name, $user_displayname, $user_pass, $user_email)
          = sysVarCleanFromInput('user_name', 'user_displayname', 'user_pass', 'user_email');
  sysInit();

	//Если все прошло успешно создаем тему
	sysModSetVar ('SYS_config','default_theme', 'develope');
 
  //Регенирировали список тем в системе
  include_once ('modules/SYS_themes/class.php'); //Грузим класс управления модулями
  $SYS_themes = new SYS_themes;
  $SYS_themes->regen();
									  
  
  
  sysModClassLoad ('SYS_users');
  $SYS_users = new SYS_users;
  $SYS_users->create (array ('user_gid'         => 1,
                             'user_active'      => 1,
                             'user_name'        => $user_name,
                             'user_displayname' => $user_displayname,
                             'user_pass'        => md5 ($user_pass),
                             'user_email'       => $user_email,
                             'user_femail'      => $user_email,
                             'user_theme'       => 'develope'));

  //Если все прошло успешно выводим поздравления
  $sysTpl = new basicSysTpl;
  $html = $sysTpl->fetch ('install/tpl/setup_finished.htm');
  return $html;
  }


/*
 * Простенький класс работы с шаблонами,
 * только для тех случаев где нормальная система шаблонов не работает
*/
class basicSysTpl
  {
  var $_tpl_vars = array();
  /*
   * Добавление переменной в шаблон
  */
  function assign ($var_name, $var_value = null)
    {
    $this->_tpl_vars[$var_name] = $var_value;
    }

  /*
   * Рендеринг страницы
  */
  function fetch ($resource_name)
    {
    $page_content = implode ('', file ($resource_name));
    foreach ($this->_tpl_vars as $var_name=>$var_value)
      $page_content = str_replace("{\$$var_name}", $var_value, $page_content);
    return $page_content;
    }
  }

/*
 * Рекурсивное удаление дирректории и всего ее содержимого
*/
function recUnlink($file, $deleteStartFile = true)
  {
  chmod($file,0777);
  if (is_dir($file))
    {
    $handle = opendir($file);
    while($filename = readdir($handle))
      {
      if ($filename != "." && $filename != "..")
        {
        recUnlink($file."/".$filename, true);
        }
      }
    closedir($handle);
    if ($deleteStartFile)
      {
      rmdir($file);
      }
    }
    else
      {
      if ($deleteStartFile)
        {
        unlink($file);
        }
      }
  }



?>
