<?php
/*
 * Меню выбора, че конфигурить.
*/
function SYS_config_admin_main()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_config::admin::main';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //BrowseIn
  $browsein = array();
  $browsein[]=array ('url'=>sysModURL('SYS_config','admin','main'),
                     'displayname'=>'Конфигурация сайта');
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

/*
 * Конфигуриррование главных настроек сайта
*/
function SYS_config_admin_mainconfig_modify()
  {
  $sysObject = 'SYS_config::admin::mainconfig::modify';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Загрузка языка + инициализация шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;
  sysLoadLang($sysTpl, 'SYS_config', 'module');

  //Грузим необходимые нам классы
  sysExtLibLoad();
  sysModClassLoad ('SYS_modules'); $SYS_modules = new SYS_modules;
  sysModClassLoad ('SYS_themes');  $SYS_themes = new SYS_themes;
  sysModClassLoad ('SYS_groups');  $SYS_groups = new SYS_groups;

  $sysTpl->assign('site_name', sysModGetVar('SYS_config','site_name'));
  $sysTpl->assign('site_slogan', sysModGetVar('SYS_config','site_slogan'));
  $sysTpl->assign('site_url', sysModGetVar('SYS_config','site_url'));
   $sysTpl->assign('site_logo', sysModGetVar('SYS_config','site_logo'));
  $sysTpl->assign('adminmail', sysModGetVar('SYS_config','adminmail'));
  //Получаем список доступных языков системы
  $langlist = sysGetLangList();
  //назначаем константы
  foreach ($langlist as $key=>$lang)
    {
    $langlist[$key] = $sysTpl->get_config_vars ($key);
    };

  $sysTpl->assign('lang', sysModGetVar('SYS_config','lang'));
  $sysTpl->assign('langlist', $langlist);

  // доступные модули
  $modlist = $SYS_modules->get_list();
  //sys_log ('SYS_config_admin_mainconfig_modify', LOG_DEBUG, 'Доступные модули', $modlist);
  // преабразовываем к 2d
  $flatmodlist = array(''=>$sysTpl->get_config_vars('no'));
  foreach ($modlist as $mod)
    {
    $flatmodlist[$mod['mod_name']]=$mod['mod_displayname'];
    };
  $sysTpl->assign('modlist', $flatmodlist);
  $sysTpl->assign('startpage', sysModGetVar('SYS_config','startpage'));

  //Получаем список доступных времянных зон
  $tzinfo = sysGetTzList();
  $sysTpl->assign('tzinfo', $tzinfo);
  $sysTpl->assign('server_tz', sysModGetVar('SYS_config','server_tz'));
  $sysTpl->assign('default_tz', sysModGetVar('SYS_config','default_tz'));

  //Берем список тем
  $themelist = $SYS_themes->get_list();
  // преабразовываем к 2d
  $flatthemelist=array();
  foreach ($themelist as $theme)
    {
    $flatthemelist[$theme['theme_name']]=$theme['theme_displayname'];
    };
  $sysTpl->assign('themelist', $flatthemelist);
  $sysTpl->assign('default_theme', sysModGetVar('SYS_config','default_theme'));

  //Список групп
  $grouplist = $SYS_groups->get_list();
  //sys_log ('SYS_config_admin_mainconfig_modify', LOG_DEBUG, 'Список групп в системе', $grouplist);
  $flatgrouplist=array();
  foreach ($grouplist as $group)
    {
    $flatgrouplist[$group['id']] = $group['group_displayname'];
    };
  $sysTpl->assign('grouplist', $flatgrouplist);
  $sysTpl->assign('default_group', sysModGetVar('SYS_config','default_group'));
  // debug
  $sysTpl->assign('debug', sysModGetVar('SYS_config','debug'));
  // GZIP
  $sysTpl->assign('gzip_compression', sysModGetVar('SYS_config','gzip_compression'));
  // SendErrorMessages
  $sysTpl->assign('mailerrormessages', sysModGetVar('SYS_config','mailerrormessages'));
  // check_referer
  $sysTpl->assign('check_referer', sysModGetVar('SYS_config','check_referer'));
  //onException
  $onExceptionList=array ();
  $onExceptionList[0] = $sysTpl->get_config_vars('onException_0');
  $onExceptionList[1] = $sysTpl->get_config_vars('onException_1');
  $onExceptionList[2] = $sysTpl->get_config_vars('onException_2');
  $onExceptionList[3] = $sysTpl->get_config_vars('onException_3');
  $sysTpl->assign('onExceptionList', $onExceptionList);
  $sysTpl->assign('onException', sysModGetVar('SYS_config','onException'));
  //Сессии
  $sysTpl->assign('sesdays', sysModGetVar('SYS_config','sesdays'));
  $sysTpl->assign('sesinactivemins', sysModGetVar('SYS_config','sesinactivemins'));
  //intranet
  $sysTpl->assign('intranet', sysModGetVar('SYS_config','intranet'));
  //closesite
  $sysTpl->assign('closesite', sysModGetVar('SYS_config','closesite'));
  $sysTpl->assign('accesskey', sysModGetVar('SYS_config','accesskey'));
  $sysTpl->assign('closesite_okgrp', sysModGetVar('SYS_config','closesite_okgrp'));
  $sysTpl->assign('closesite_text', sysModGetVar('SYS_config','closesite_text'));
  //enable_badips
  $sysTpl->assign('enable_badips', sysModGetVar('SYS_config','enable_badips'));
  $sysTpl->assign('bad_ips', sysModGetVar('SYS_config','bad_ips'));
  $sysTpl->assign('time_log', sysModGetVar('SYS_config','time_log'));
  //Text editor
  $texteditorlist=array ();
  $texteditorlist[0] = $sysTpl->get_config_vars('no');
  $texteditorlist[1] = $sysTpl->get_config_vars('simple');
  $texteditorlist[2] = $sysTpl->get_config_vars('advanced');
  $texteditorlist[3] = $sysTpl->get_config_vars('word_style');
  $texteditorlist[4] = $sysTpl->get_config_vars('full');
  $texteditorlist[5] = $sysTpl->get_config_vars('fckeditor');
  $sysTpl->assign('texteditorlist', $texteditorlist);
  $sysTpl->assign('texteditor', sysModGetVar('SYS_config','texteditor'));
  //Shorturls
  $sysTpl->assign('shorturls', sysModGetVar('SYS_config','shorturls'));
  //exit;
  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_config','admin','main'),
                     'displayname'=>$sysTpl->get_config_vars('site_config'));
  $browsein[]=array ('url'=>sysModURL('SYS_config','admin','mainconfig_modify'),
                     'displayname'=>$sysTpl->get_config_vars('site_mainconfig'));
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Сохраняем конфигурацию главных настроек
*/
function SYS_config_admin_mainconfig_update()
  {
  $sysObject = 'SYS_config::admin::mainconfig::update';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  list ($site_name, $site_slogan, $site_url, $site_logo, $adminmail, $lang, $startpage,
        $server_tz, $default_tz, $default_theme, $default_group,
        $gzip_compression, $mailerrormessages, $check_referer,
        $onException, $sesdays, $sesinactivemins, $intranet,
        $closesite, $accesskey, $closesite_okgrp, $closesite_text, $enable_badips,
        $bad_ips, $texteditor, $shorturls, $debug, $time_log)=
      sysVarCleanFromInput('site_name', 'site_slogan', 'site_url', 'site_logo','adminmail', 'lang', 'startpage',
        'server_tz', 'default_tz', 'default_theme', 'default_group',
        'gzip_compression', 'mailerrormessages', 'check_referer',
        'onException', 'sesdays', 'sesinactivemins', 'intranet',
        'closesite', 'accesskey', 'closesite_okgrp', 'closesite_text', 'enable_badips',
        'bad_ips', 'texteditor', 'shorturls', 'debug', 'time_log');


  sysModSetVar ('SYS_config','site_name', $site_name);
  sysModSetVar ('SYS_config','site_slogan', $site_slogan);
  $site_url = ltrim($site_url,'/'); 
  if (strpos($site_url, "http://")===false)
	$site_url = '/'.$site_url;
  sysModSetVar ('SYS_config','site_url', $site_url);
  sysModSetVar ('SYS_config','site_logo', $site_logo);
  sysModSetVar ('SYS_config','adminmail', $adminmail);
  sysModSetVar ('SYS_config','lang', $lang);
  sysModSetVar ('SYS_config','startpage', $startpage);
  sysModSetVar ('SYS_config','server_tz', $server_tz);
  sysModSetVar ('SYS_config','default_tz', $default_tz);
  sysModSetVar ('SYS_config','default_theme', $default_theme);
  sysModSetVar ('SYS_config','default_group', $default_group);
  sysModSetVar ('SYS_config','debug', $debug);
  sysModSetVar ('SYS_config','gzip_compression', $gzip_compression);
  sysModSetVar ('SYS_config','mailerrormessages', $mailerrormessages);
  sysModSetVar ('SYS_config','check_referer', $check_referer);
  sysModSetVar ('SYS_config','onException', $onException);
  sysModSetVar ('SYS_config','sesdays', $sesdays);
  sysModSetVar ('SYS_config','sesinactivemins', $sesinactivemins);
  sysModSetVar ('SYS_config','intranet', $intranet);
  sysModSetVar ('SYS_config','closesite', $closesite);
  sysModSetVar ('SYS_config','accesskey', $accesskey);
  
  sysModSetVar ('SYS_config','closesite_okgrp', $closesite_okgrp);
  sysModSetVar ('SYS_config','closesite_text', $closesite_text);
  sysModSetVar ('SYS_config','enable_badips', $enable_badips);
  sysModSetVar ('SYS_config','bad_ips', $bad_ips);
  sysModSetVar ('SYS_config','texteditor', $texteditor);
  sysModSetVar ('SYS_config','shorturls', $shorturls);
   sysModSetVar ('SYS_config','time_log', $time_log);

  //Язык грузим
  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;
  sysLoadLang($sysTpl, 'SYS_config', 'module');

  sysShowMessage(sysModURL ('SYS_config','admin','mainconfig_modify'), $sysTpl->get_config_vars('saved'));
  return true;
  }

/*
 * Конфигуриррование мета информации
*/
function SYS_config_admin_metaconfig_modify()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_config::admin::metaconfig::modify';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;
  //
  $sysTpl->assign('meta_keywords', sysModGetVar('SYS_config','meta_keywords'));
  $sysTpl->assign('meta_description', sysModGetVar('SYS_config','meta_description'));
  $sysTpl->assign('meta_robots', sysModGetVar('SYS_config','meta_robots'));
  $sysTpl->assign('meta_robots_list', array ('index,follow'=>'Индексировать, Отслеживать',
                                             'noindex,follow'=>'Не индексировать, Отслеживать',
                                             'index,nofollow'=>'Индексировать, Не отслеживать',
                                             'noindex,nofollow'=>'Не индексировать, Не отслеживать'));
  $sysTpl->assign('meta_rating', sysModGetVar('SYS_config','meta_rating'));
  $sysTpl->assign('meta_rating_list', array ('general'=>'Обычный',
                                             '14 years'=>'14 лет',
                                             'restricted'=>'Ограниченный',
                                             'mature'=>'Для взрослых'));
  $sysTpl->assign('meta_author', sysModGetVar('SYS_config','meta_author'));
  $sysTpl->assign('meta_copyright', sysModGetVar('SYS_config','meta_copyright'));
  $sysTpl->assign('footer', sysModGetVar('SYS_config','footer'));

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_config','admin','main'),
                     'displayname'=>"Конфигурация сайта");
  $browsein[]=array ('url'=>sysModURL('SYS_config','admin','metaconfig_modify'),
                     'displayname'=>"МЕТА-тэги и нижний колонтитул");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  };

/*
 * Сохраняем конфигурацию настроек Метатегов
*/
function SYS_config_admin_metaconfig_update()
  {
  $sysObject = 'SYS_config::admin::metaconfig::update';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Забираем переменные со входа
  list ($meta_keywords, $meta_description, $meta_robots, $meta_rating,
        $meta_author, $meta_copyright, $footer)=
      sysVarCleanFromInput('meta_keywords', 'meta_description', 'meta_robots', 'meta_rating',
          'meta_author', 'meta_copyright', 'footer');

  sysModSetVar ('SYS_config', 'meta_keywords', $meta_keywords);
  sysModSetVar ('SYS_config', 'meta_description', $meta_description);
  sysModSetVar ('SYS_config', 'meta_robots', $meta_robots);
  sysModSetVar ('SYS_config', 'meta_rating', $meta_rating);
  sysModSetVar ('SYS_config', 'meta_author', $meta_author);
  sysModSetVar ('SYS_config', 'meta_copyright', $meta_copyright);
  sysModSetVar ('SYS_config', 'footer', $footer);

  //Тело модуля
  sysShowMessage(sysModURL ('SYS_config','admin','metaconfig_modify'), 'Изменения сохранены');
  return true;
  }

/*  Это старый фрагмент кода, он еше не доработанн


 * Конфигуриррование цензурироания слов

function SYS_config_admin_censorconfig_modify()
{
//Прелюдие как у всех модулей
$sysObject="SYS_config::admin::censorconfig::modify";
//Проверка на доступ
if (!sysSecAuthAction($sysObject, ACCESS_ADMIN))
  {
  $result[object]=$sysObject;
  $result[content]=_NOAUTH;
  return $result;
  };

$sysTpl = new sysTpl;
$sysModTpl=sysTplWay ($sysObject);
$sysTpl->caching = false;

$sysTpl->assign('censor_enable', sysModGetVar('SYS_config','censor_enable'));
$sysTpl->assign('censor_words', sysModGetVar('SYS_config','censor_words'));
$sysTpl->assign('censor_replace', sysModGetVar('SYS_config','censor_replace'));

//BrowseIn
$browsein[]=array ('url'=>sysModURL('SYS_config','admin','main'),
                   'displayname'=>"Конфигурация сайта");
$browsein[]=array ('url'=>sysModURL('SYS_config','admin','metaconfig_modify'),
                   'displayname'=>"Параметры цензуры слов");
$sysTpl->assign('browsein', $browsein);

//Возвращаем результат
$result[object]=$sysObject;
$result[browsein]=$browsein;
$result[content]=$sysTpl->fetch($sysModTpl,$sysObject);
return $result;
};

/*
 * Сохраняем конфигурацию настроек цензуры

function SYS_config_admin_censorconfig_update()
{
$sysObject="SYS_config::admin::censorconfig::update";
//Проверка на доступ
if (!sysSecAuthAction($sysObject, ACCESS_ADMIN))
  {
  $result[object]=$sysObject;
  $result[content]=_NOAUTH;
  return $result;
  };

//Забираем переменные со входа
list ($censor_enable, $censor_words, $censor_replace)=
    sysVarCleanFromInput('censor_enable', 'censor_words', 'censor_replace');

sysModSetVar ('SYS_config', 'censor_enable', $censor_enable);
sysModSetVar ('SYS_config', 'censor_words', $censor_words);
sysModSetVar ('SYS_config', 'censor_replace', $censor_replace);

//Тело модуля
sysShowMessage(sysModURL ('SYS_config','admin','censorconfig_modify'), 'Изменения сохранены');
}
*/


/*
 * Конфигуриррование почты
*/
function SYS_config_admin_mailconfig_modify()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_config::admin::mailconfig::modify';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;
  sysLoadLang($sysTpl, 'SYS_config', 'module');
  //
  $sysTpl->assign('mail_from', sysModGetVar('SYS_config','mail_from'));
  $sysTpl->assign('mail_fromname', sysModGetVar('SYS_config','mail_fromname'));
  $sysTpl->assign('mail_method', sysModGetVar('SYS_config','mail_method'));
  $sysTpl->assign('mail_method_list', array ('mail'=>'PHP mail()',
                                        'sendmail'=>'sendmail',
                                        'qmail'=>'qmail',
                                        'smtp'=>'SMTP',
                                        'smtpauth'=>'SMTPAuth'));
										
$sysTpl->assign('mail_savelog', sysModGetVar('SYS_config','mail_savelog'));
  $sysTpl->assign('mail_sendmailpath', sysModGetVar('SYS_config','mail_sendmailpath'));
  $sysTpl->assign('mail_smtphost', sysModGetVar('SYS_config','mail_smtphost'));
  $sysTpl->assign('mail_smtpuser', sysModGetVar('SYS_config','mail_smtpuser'));
  $sysTpl->assign('mail_smtppass', sysModGetVar('SYS_config','mail_smtppass'));

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_config','admin','main'),
                     'displayname'=>"Конфигурация сайта");
  $browsein[]=array ('url'=>sysModURL('SYS_config','admin','mailconfig_modify'),
                     'displayname'=>"Настройка почты");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  };

/*
 * Сохраняем конфигурацию настроек почты
*/
function SYS_config_admin_mailconfig_update()
  {
  $sysObject = 'SYS_config::admin::mailconfig::update';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Забираем переменные со входа
  list ($mail_from, $mail_fromname, $mail_method, $mail_savelog, $mail_sendmailpath,
        $mail_smtphost, $mail_smtpuser, $mail_smtppass)=
      sysVarCleanFromInput('mail_from', 'mail_fromname', 'mail_method', 'mail_savelog', 'mail_sendmailpath',
          'mail_smtphost', 'mail_smtpuser', 'mail_smtppass');

  sysModSetVar ('SYS_config', 'mail_from', $mail_from);
  sysModSetVar ('SYS_config', 'mail_fromname', $mail_fromname);
  sysModSetVar ('SYS_config', 'mail_method', $mail_method);
  sysModSetVar ('SYS_config', 'mail_savelog', $mail_savelog);
  sysModSetVar ('SYS_config', 'mail_sendmailpath', $mail_sendmailpath);
  sysModSetVar ('SYS_config', 'mail_smtphost', $mail_smtphost);
  sysModSetVar ('SYS_config', 'mail_smtpuser', $mail_smtpuser);
  sysModSetVar ('SYS_config', 'mail_smtppass', $mail_smtppass);
  

  //Тело модуля
  sysShowMessage(sysModURL ('SYS_config','admin','mailconfig_modify'), 'Изменения сохранены');
  return true;
  }

/*
 * Конфигуриррование капчи
*/
function SYS_config_admin_captchaconfig_modify()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_config::admin::captchaconfig::modify';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //
  $sysTpl->assign('captcha_font', 					sysModGetVar('SYS_config','captcha_font'));
  $sysTpl->assign('captcha_font_random', 					sysModGetVar('SYS_config','captcha_font_random'));
  $sysTpl->assign('captcha_length', 					sysModGetVar('SYS_config','captcha_length'));
  $sysTpl->assign('captcha_height', 					sysModGetVar('SYS_config','captcha_height'));
  $sysTpl->assign('captcha_width', 						sysModGetVar('SYS_config','captcha_width'));
  $sysTpl->assign('captcha_font_size_min', 		sysModGetVar('SYS_config','captcha_font_size_min'));
  $sysTpl->assign('captcha_font_size_max', 		sysModGetVar('SYS_config','captcha_font_size_max'));
  $sysTpl->assign('captcha_font_angle_min', 	sysModGetVar('SYS_config','captcha_font_angle_min'));
  $sysTpl->assign('captcha_font_angle_max', 	sysModGetVar('SYS_config','captcha_font_angle_max'));
  $sysTpl->assign('captcha_case', 						sysModGetVar('SYS_config','captcha_case'));
  $sysTpl->assign('captcha_LetterColor', 			sysModGetVar('SYS_config','captcha_LetterColor'));
  $sysTpl->assign('captcha_bgColorRed', 			sysModGetVar('SYS_config','captcha_bgColorRed'));
  $sysTpl->assign('captcha_bgColorGreen',			sysModGetVar('SYS_config','captcha_bgColorGreen'));
  $sysTpl->assign('captcha_bgColorBlue', 			sysModGetVar('SYS_config','captcha_bgColorBlue'));
  $sysTpl->assign('captcha_drawgrid', 				sysModGetVar('SYS_config','captcha_drawgrid'));
  $sysTpl->assign('captcha_grid', 						sysModGetVar('SYS_config','captcha_grid'));
  $sysTpl->assign('captcha_randomGridColor',	sysModGetVar('SYS_config','captcha_randomGridColor'));
  $sysTpl->assign('captcha_dotsPercent', 			sysModGetVar('SYS_config','captcha_dotsPercent'));
  $sysTpl->assign('captcha_type', 						sysModGetVar('SYS_config','captcha_type'));

//список шрифтов на диске
    //Взяли список с диска
    $font_list = array();
    $dh = opendir('files/fonts');

   while (false !== ($dir = readdir($dh)))  
      if ((is_file("files/fonts/$dir")) && (strripos($dir,".ttf"))!==false)
        $font_list[$dir]=$dir;

    closedir($dh);

  $sysTpl->assign('font_list', $font_list);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_config','admin','main'),
                     'displayname'=>"Конфигурация сайта");
  $browsein[]=array ('url'=>sysModURL('SYS_config','admin','mailconfig_modify'),
                     'displayname'=>"Настройка капчи");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  };

/*
 * Сохраняем конфигурацию настроек капчи
*/
function SYS_config_admin_captchaconfig_update()
  {
  $sysObject = 'SYS_config::admin::captchaconfig::update';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Забираем переменные со входа
  list ($captcha_font,$captcha_font_random,$captcha_length, $captcha_height, $captcha_width, $captcha_font_size_min,
  			$captcha_font_size_max, $captcha_font_angle_min, $captcha_font_angle_max, $captcha_case,
				$captcha_LetterColor, $captcha_bgColorRed, $captcha_bgColorGreen, $captcha_bgColorBlue,
				$captcha_drawgrid, $captcha_grid, $captcha_randomGridColor, $captcha_dotsPercent,
				$captcha_type)=
      sysVarCleanFromInput('captcha_font','captcha_font_random','captcha_length', 'captcha_height', 'captcha_width', 'captcha_font_size_min',
  			'captcha_font_size_max', 'captcha_font_angle_min', 'captcha_font_angle_max', 'captcha_case',
				'captcha_LetterColor', 'captcha_bgColorRed', 'captcha_bgColorGreen', 'captcha_bgColorBlue',
				'captcha_drawgrid', 'captcha_grid', 'captcha_randomGridColor', 'captcha_dotsPercent',
				'captcha_type');

  sysModSetVar ('SYS_config', 'captcha_font',		 				$captcha_font);
  sysModSetVar ('SYS_config', 'captcha_font_random',		 			$captcha_font_random);
  sysModSetVar ('SYS_config', 'captcha_length',		 				$captcha_length);
  sysModSetVar ('SYS_config', 'captcha_height',		 				$captcha_height);
  sysModSetVar ('SYS_config', 'captcha_width',		 				$captcha_width);
  sysModSetVar ('SYS_config', 'captcha_font_size_min',		$captcha_font_size_min);

  sysModSetVar ('SYS_config', 'captcha_font_size_max',		$captcha_font_size_max);
  sysModSetVar ('SYS_config', 'captcha_font_angle_min',		$captcha_font_angle_min);
  sysModSetVar ('SYS_config', 'captcha_font_angle_max',		$captcha_font_angle_max);
  sysModSetVar ('SYS_config', 'captcha_case',		 					$captcha_case);

  sysModSetVar ('SYS_config', 'captcha_LetterColor',		 	$captcha_LetterColor);
  sysModSetVar ('SYS_config', 'captcha_bgColorRed',		 		$captcha_bgColorRed);
  sysModSetVar ('SYS_config', 'captcha_bgColorGreen',		 	$captcha_bgColorGreen);
  sysModSetVar ('SYS_config', 'captcha_bgColorBlue',		 	$captcha_bgColorBlue);

  sysModSetVar ('SYS_config', 'captcha_drawgrid',		 			$captcha_drawgrid);
  sysModSetVar ('SYS_config', 'captcha_grid',		 					$captcha_grid);
  sysModSetVar ('SYS_config', 'captcha_randomGridColor',	$captcha_randomGridColor);
  sysModSetVar ('SYS_config', 'captcha_dotsPercent',		 	$captcha_dotsPercent);

  sysModSetVar ('SYS_config', 'captcha_type',						 	$captcha_type);

  //Тело модуля
  sysShowMessage(sysModURL ('SYS_config','admin','captchaconfig_modify'), 'Изменения сохранены');
  return true;
  }

/*
 * Просмотркапчи
*/
function SYS_config_admin_captchaconfig_preview()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_config::admin::captchaconfig::preview';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysExtLibLoad('captcha');
	$captcha = new sysCaptcha();
	$captcha -> display();

  sysTerminate;
  exit;
  };

 /*
 * Конфигурирование мета информации
*/
function SYS_config_admin_imagesconfig_modify()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_config::admin::imagesconfig::modify';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;
  sysLoadLang($sysTpl, 'SYS_config', 'module');
  //
  $sysTpl->assign('images_from', sysModGetVar('SYS_config','images_from'));
   $sysTpl->assign('module_watermark', unserialize(sysModGetVar('SYS_config','module_watermark')));
  $sysTpl->assign('watermark', sysModGetVar('SYS_config','watermark'));
  $sysTpl->assign('watermark_file', sysModGetVar('SYS_config','watermark_file'));
  $sysTpl->assign('thumb_replace', sysModGetVar('SYS_config','thumb_replace'));
  $sysTpl->assign('watermark_pos_x', sysModGetVar('SYS_config','watermark_pos_x'));
  $sysTpl->assign('watermark_pos_y', sysModGetVar('SYS_config','watermark_pos_y'));
    $sysTpl->assign('watermark_percent', sysModGetVar('SYS_config','watermark_percent'));

  
  $sysTpl->assign('min_watermark_width', sysModGetVar('SYS_config','min_watermark_width'));
  $sysTpl->assign('min_watermark_height', sysModGetVar('SYS_config','min_watermark_height'));
 
  //BrowseIn
   $browsein[]=array ('url'=>sysModURL('SYS_config','admin','main'),
                     'displayname'=>$sysTpl->get_config_vars('site_config'));
   $browsein[]=array ('url'=>sysModURL('SYS_config','admin','main'),
                     'displayname'=>$sysTpl->get_config_vars('imagesconfig'));
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  };

/*
 * Сохраняем конфигурацию настроек Метатегов
*/
function SYS_config_admin_imagesconfig_update()  {
  
	$sysObject = 'SYS_config::admin::imagesconfig::update';
  
	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

	//Забираем переменные со входа
	
	list ($watermark, $module_watermark, $watermark_file, $watermark_pos_x, $watermark_pos_y, $watermark_percent, $thumb_replace, $min_watermark_width, $min_watermark_height, $images_from) = sysVarCleanFromInput('watermark', 'module_watermark', 'watermark_file', 'watermark_pos_x', 'watermark_pos_y', 'watermark_percent', 'thumb_replace', 'min_watermark_width', 'min_watermark_height', 'images_from');
	$module_watermark = $_POST['module_watermark'];
	
	list ($clear_content_thumbs, $clear_shop_thumbs,$clear_foto_thumbs, $clear_watermark_thumbs) = sysVarCleanFromInput('clear_content_thumbs', 'clear_shop_thumbs','clear_foto_thumbs', 'clear_watermark_thumbs');
	

	if (sysModGetVar('SYS_config','watermark_percent') != $watermark_percent) {
		 $clear_watermark_thumbs = true;
	}
	sysModSetVar ('SYS_config', 'watermark', $watermark);
	sysModSetVar ('SYS_config', 'watermark_file', $watermark_file);
	sysModSetVar ('SYS_config', 'module_watermark', serialize($module_watermark));
	sysModSetVar ('SYS_config', 'watermark_pos_x', $watermark_pos_x);
	sysModSetVar ('SYS_config', 'watermark_pos_y', $watermark_pos_y);
	
	sysModSetVar ('SYS_config', 'watermark_percent', $watermark_percent);
	sysModSetVar ('SYS_config', 'thumb_replace', $thumb_replace);
	sysModSetVar ('SYS_config', 'images_from', $images_from);
	sysModSetVar ('SYS_config', 'min_watermark_width', $min_watermark_width);
	sysModSetVar ('SYS_config', 'min_watermark_height', $min_watermark_height);
	
  
	
	
	sysExtLibLoad('imagelib');
    $sysImage = new sysImage();
	
	//очищаем тумбы 
	if ($clear_content_thumbs)
		$sysImage->clear_cache ("content");
		
	if ($clear_shop_thumbs)
		$sysImage->clear_cache ("shop");
		
	if ($clear_foto_thumbs)
		$sysImage->clear_cache ("foto");
		
	if ($clear_watermark_thumbs)
		$sysImage->clear_cache ("watermark");



	
	$sysTpl = new sysTpl;
	$sysModTpl=sysTplWay ($sysObject);
	$sysTpl->caching = false;
	sysLoadLang($sysTpl, 'SYS_config', 'module');

	sysShowMessage(sysModURL ('SYS_config','admin','imagesconfig_modify'),  $sysTpl->get_config_vars('saved'));
	exit;
}  
   /*
 * Конфигуриррование страниц ошибок
*/
function SYS_config_admin_errorpage_modify()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_config::admin::errorpage::modify';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;
  sysLoadLang($sysTpl, 'SYS_config', 'module');
  //
  $sysTpl->assign('page404', sysModGetVar('SYS_config','page404'));
  $sysTpl->assign('page_error', sysModGetVar('SYS_config','page_error'));

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_config','admin','main'),
                     'displayname'=>$sysTpl->get_config_vars('siteconfig'));
  $browsein[]=array ('url'=>sysModURL('SYS_config','admin','errorpage_modify'),
                     'displayname'=>$sysTpl->get_config_vars('errorpage'));
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  };

/*
 * Сохраняем конфигурацию страниц ошибок
*/
function SYS_config_admin_errorpage_update()  {
  
	$sysObject = 'SYS_config::admin::errorpage::update';
  
	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

	//Забираем переменные со входа
	list ($page404, $page_error) = sysVarCleanFromInput('page404', 'page_error');
	sysModSetVar ('SYS_config', 'page404', $page404);
	sysModSetVar ('SYS_config', 'page_error', $page_error);

  
	$sysTpl = new sysTpl;
	sysLoadLang($sysTpl, 'SYS_config', 'module');

	//Тело модуля
	sysShowMessage(sysModURL ('SYS_config','admin','errorpage_modify'), $sysTpl->get_config_vars('saved'));
	return true;
}

?>