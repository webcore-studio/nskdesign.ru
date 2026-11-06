<?php

function SYS_config_init()
  {
  //Задаем переменные модуля по умолчанию
  //Основные настройки
  sysModSetVar ('SYS_config','site_name', 'Наименование сайта');
  sysModSetVar ('SYS_config','site_slogan', 'Слоган');
  sysModSetVar ('SYS_config','site_url', '/');
  sysModSetVar ('SYS_config','adminmail', '');
  sysModSetVar ('SYS_config','lang', 'rus');
  sysModSetVar ('SYS_config','startpage', '');
  sysModSetVar ('SYS_config','server_tz', '14');
  sysModSetVar ('SYS_config','default_tz', '14');
  sysModSetVar ('SYS_config','default_theme', 'sleekbluey');
  sysModSetVar ('SYS_config','default_group', '2');
  sysModSetVar ('SYS_config','gzip_compression', '1');
  sysModSetVar ('SYS_config','mailerrormessages', '1');
  sysModSetVar ('SYS_config','check_referer', '0');
  sysModSetVar ('SYS_config','onException', '3');
  sysModSetVar ('SYS_config','sesdays', '14');
  sysModSetVar ('SYS_config','sesinactivemins', '10');
  sysModSetVar ('SYS_config','intranet', '0');
  sysModSetVar ('SYS_config','debug', 1);
  sysModSetVar ('SYS_config','closesite', '0');
  sysModSetVar ('SYS_config','closesite_okgrp', '1');
  sysModSetVar ('SYS_config','closesite_text', '');
  sysModSetVar ('SYS_config','enable_badips', '0');
  sysModSetVar ('SYS_config','bad_ips', '');
  sysModSetVar ('SYS_config','texteditor', '4');
  sysModSetVar ('SYS_config','shorturls', '0');

  //Настройки почьты
  sysModSetVar ('SYS_config','mail_sendmailpath', '/usr/sbin/sendmail');
  sysModSetVar ('SYS_config','mail_method', 'mail');

  //Настройки капчи
  sysModSetVar ('SYS_config', 'captcha_font',		'Price.TTF');
  
  sysModSetVar ('SYS_config', 'captcha_length',		 				'6');
  sysModSetVar ('SYS_config', 'captcha_height',		 				'40');
  sysModSetVar ('SYS_config', 'captcha_width',		 				'100');
  sysModSetVar ('SYS_config', 'captcha_font_size_min',		'18');

  sysModSetVar ('SYS_config', 'captcha_font_size_max',		'20');
  sysModSetVar ('SYS_config', 'captcha_font_angle_min',		'-10');
  sysModSetVar ('SYS_config', 'captcha_font_angle_max',		'30');
  sysModSetVar ('SYS_config', 'captcha_case',		 					'0');

  sysModSetVar ('SYS_config', 'captcha_LetterColor',		 	'1');
  sysModSetVar ('SYS_config', 'captcha_bgColorRed',		 		'240');
  sysModSetVar ('SYS_config', 'captcha_bgColorGreen',		 	'240');
  sysModSetVar ('SYS_config', 'captcha_bgColorBlue',		 	'240');

  sysModSetVar ('SYS_config', 'captcha_drawgrid',		 			'0');
  sysModSetVar ('SYS_config', 'captcha_grid',		 					'10');
  sysModSetVar ('SYS_config', 'captcha_randomGridColor',	'1');
  sysModSetVar ('SYS_config', 'captcha_dotsPercent',		 	'15');

  sysModSetVar ('SYS_config', 'captcha_type',						 	'digits');

  //Регистрируем темплеи в системе
  sysModClassLoad('SYS_themes');
  $SYS_themes = new SYS_themes();
  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_config',
                             'tpl_name'=>'admin_main.tpl',
                             'tpl_pattern'=>'^SYS_config::admin::main$',
                             'tpl_description'=>'Главная админская страница'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_config',
                             'tpl_name'=>'admin_mainconfig_modify.tpl',
                             'tpl_pattern'=>'SYS_config::admin::mainconfig::modify',
                             'tpl_description'=>'Основные настройки'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_config',
                             'tpl_name'=>'admin_metaconfig_modify.tpl',
                             'tpl_pattern'=>'SYS_config::admin::metaconfig::modify',
                             'tpl_description'=>'МЕТА-тэги'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_config',
                             'tpl_name'=>'admin_censorconfig_modify.tpl',
                             'tpl_pattern'=>'SYS_config::admin::censorconfig::modify',
                             'tpl_description'=>'Параметры цензуры слов'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_config',
                             'tpl_name'=>'admin_mailconfig_modify.tpl',
                             'tpl_pattern'=>'SYS_config::admin::mailconfig::modify',
                             'tpl_description'=>'Настройка почты'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_config',
                             'tpl_name'=>'admin_captchaconfig_modify.tpl',
                             'tpl_pattern'=>'SYS_config::admin::captchaconfig::modify',
                             'tpl_description'=>'Настройки капчи'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_config',
                             'tpl_name'=>'admin_imagesconfig_modify.tpl',
                             'tpl_pattern'=>'SYS_config::admin::imagesconfig::modify',
                             'tpl_description'=>'Настройки изображений'));
							 
$SYS_themes->tpl_add (array('tpl_compname'=>'SYS_config',
                             'tpl_name'=>'admin_errorpage_modify.tpl',
                             'tpl_pattern'=>'SYS_config::admin::errorpage::modify',
                             'tpl_description'=>'Настройки страниц ошибок'));
							 
$SYS_themes->tpl_add (array('tpl_compname'=>'SYS_config',
                             'tpl_name'=>'user_errorpage_view.tpl',
                             'tpl_pattern'=>'SYS_config::user::errorpage::view',
                             'tpl_description'=>'Отображение страниц ошибок'));
							 
  // Initialisation successful
  return true;
  }

function SYS_config_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function SYS_config_delete()
  {
  // Deletion successful
  return false;
  }

function SYS_config_activate()
  {
  return true;
  };

function SYS_config_deactivate()
  {
  return false;
  };

?>