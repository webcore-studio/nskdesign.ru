<?php

function SYS_users_init()
  {
  sysModClassLoad ('SYS_df');
  $SYS_df = new SYS_df;

  //Создаем таблицу пользователей
  $users_table = sysDBGetTable('users');
  $SYS_df->CreateTable($users_table, array(
    'id' =>                   array('type'        =>  'int',
                                    'size'        => 4,
                                    'null'        => false,
                                    'default'     => '0',
                                    'increment'   => true,
                                    'primary_key' => true),

    'user_gid' =>             array('type'        => 'int',
                                    'size'        => 4,
                                    'null'        => false,
                                    'index'       => true,
                                    'default'     => '-1'),

    'user_active' =>          array('type'        => 'tinyint',
                                    'size'        => 1,
                                    'null'        => false,
                                    'default'     => 0,
                                    'index'       => true),

    'user_state' =>          array('type'         => 'int',
                                    'size'        => 4,
                                    'null'        => false,
                                    'index'       => true,
                                    'default'     => '0'),

    'user_name' =>            array('type'        => 'varchar',
                                    'size'        => 60,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

    'user_displayname' =>     array('type'        => 'varchar',
                                    'size'        => 128,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

    'user_surname' =>     		array('type'        => 'varchar',
                                    'size'        => 60,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

    'user_pass' =>            array('type'        => 'varchar',
                                    'size'        => 40,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

    'user_email' =>           array('type'        => 'varchar',
                                    'size'        => 60,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

    'user_femail' =>          array('type'        => 'varchar',
                                    'size'        => 60,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_sex' =>			        array('type'        => 'tinyint', //пол 1 - мальчик, 2- девочка
                                    'null'        => false,
                                    'default'     => '0'),

    'user_birth_Day' =>	      array('type'        => 'tinyint',
                                    'null'        => false,
                                    'default'     => '0'),

    'user_birth_Month' =>     array('type'        => 'tinyint',
                                    'null'        => false,
                                    'default'     => '0'),

    'user_birth_Year' =>	    array('type'        => 'smallint',
                                    'null'        => false,
                                    'default'     => '0'),

    'user_zodiac' =>	        array('type'        => 'enum("", "aquarius", "aries", "cancer", "cap", "gemini", "leo", "libra", "pisces", "sag", "scorpio", "taurus", "virgo")',
                                    'null'        => false,
                                    'default'     => ''),

    'user_counter' =>	        array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '0'),

    'user_country' =>         array('type'        => 'varchar',
                                    'size'        => 32,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

    'user_city' =>            array('type'        => 'varchar',
                                    'size'        => 32,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),
	'user_phone' =>            array('type'        => 'varchar',
                                    'size'        => 127,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

    'user_counter' =>         array('type'        => 'int',
                                    'size'        => 4,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => '0'),

    'user_theme' =>           array('type'        => 'varchar',
                                    'size'        => 25,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

    'user_url' =>             array('type'        => 'varchar',
                                    'size'        => 120,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_icq' =>             array('type'        => 'varchar',
                                    'size'        => 10,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_lastvisit' =>       array('type'        => 'int',
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => '0'),

    'user_regdate' =>         array('type'        => 'int',
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => '0'),

    'user_foto_w' =>         array('type'        => 'smallint',
                                    'null'        => false,
                                    'unsigned'    => true,
                                    'canmodify'   => true,
                                    'default'     => '0'),

    'user_foto_h' =>         array('type'        => 'smallint',
                                    'null'        => false,
                                    'unsigned'    => true,
                                    'canmodify'   => true,
                                    'default'     => '0'),

    'user_avatar_w' =>         array('type'        => 'tinyint',
                                    'null'        => false,
                                    'unsigned'    => true,
                                    'canmodify'   => true,
                                    'default'     => '0'),

    'user_avatar_h' =>         array('type'        => 'tinyint',
                                    'null'        => false,
                                    'unsigned'    => true,
                                    'canmodify'   => true,
                                    'default'     => '0'),

    'user_profile_views' =>   array('type'        => 'mediumint',
                                    'null'        => false,
                                    'unsigned'    => true,
                                    'canmodify'   => true,
                                    'default'     => '0'),

    'user_lang' =>            array('type'        => 'varchar',
                                    'size'        => 5,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

    'user_rank' =>            array('type'        => 'varchar',
                                    'size'        => 30,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

    'user_foto' =>            array('type'        => 'varchar',
                                    'size'        => 120,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_avatar' =>          array('type'        => 'varchar',
                                    'size'        => 120,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_from' =>            array('type'        => 'varchar',
                                    'size'        => 120,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_interests' =>       array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_love_genres' =>       array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_love_films' =>       array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_love_stars' =>       array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_love_tvshows' =>       array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_love_serials' =>       array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_love_musics' =>       array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_love_games' =>       array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_sig' =>             array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_about' =>           array('type'        => 'text',
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'candelete'   => true,
                                    'default'     => ''),

    'user_actkey' =>          array('type'        => 'varchar',
                                    'size'        => 32,
                                    'null'        => false,
                                    'default'     => ''),

    'user_newpasswd' =>       array('type'        => 'varchar',
                                    'size'        => 40,
                                    'canmodify'   => true,
                                    'null'        => false,
                                    'default'     => ''),

    'user_timezone_offset' => array('type'        => 'float',
                                    'size'        => '3,1',
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => '14'),

    'user_count_privmsgin' => array('type'        => 'smallint',
                                    'null'        => false,
                                    'unsigned'    => true,
                                    'canmodify'   => true,
                                    'default'     => '0'),

    'user_count_friendsreq' =>array('type'        => 'smallint',
                                    'null'        => false,
                                    'unsigned'    => true,
                                    'canmodify'   => true,
                                    'default'     => '0'),
	'user_type' =>			array('type'        => 'varchar',
									'size'        => '2',
                                    'null'        => false,
                                    'default'     => 'f'),
	'deliver_address' =>     array('type'        => 'text',
                                    'canmodify'   => true,
                                    'default'     => ''),
	'legal_address' =>     array('type'        => 'text',
                                    'canmodify'   => true,
                                    'default'     => ''),
	'bank_details' =>     array('type'        => 'text',
                                    'canmodify'   => true,
                                    'default'     => '')));


  //Создаем таблицу сессий
  $ses_table = sysDBGetTable('session');
  $SYS_df->CreateTable($ses_table, array(
    'sessid' =>               array('type'        => 'char',
                                    'size'        => 32,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'primary_key' => true),

    'rand' =>                 array('type'        => 'int',
                                    'null'        => false,
                                    'unsigned'    => true),

    'user_id' =>              array('type'        => 'int',
                                    'null'        => false,
                                    'index'       => true,
                                    'unsigned'    => true),

    'user_gid' =>             array('type'        => 'smallint',
                                    'null'        => false,
                                    'unsigned'    => true),

    'firstused' =>            array('type'        => 'int',
                                    'null'        => false,
                                    'unsigned'    => true),

    'lastused' =>             array('type'        => 'int',
                                    'null'        => false,
                                    'index'       => true,
                                    'unsigned'    => true),

    'ipaddr' =>               array('type'        => 'char',
                                    'size'        => 15,
                                    'null'        => false,
                                    'default'     => ''),

    'vars' =>                 array('type'        => 'text',
                                    'null'        => false)));

  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;
  //Регистрируем темплеи в системе
  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'user_main_loggedin.tpl',
                              'tpl_pattern'=>'SYS_users::user::main_loggedin',
                              'tpl_description'=>'Страница залогиневшегося пользователя'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'user_main_notloggedin.tpl',
                              'tpl_pattern'=>'SYS_users::user::main_notloggedin',
                              'tpl_description'=>'Страница НЕзалогиневшегося пользователя'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'user_loginscreen.tpl',
                              'tpl_pattern'=>'SYS_users::user::loginscreen',
                              'tpl_description'=>'Ввод логина'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'user_registerscreen.tpl',
                              'tpl_pattern'=>'^SYS_users::user::registerscreen$',
                              'tpl_description'=>'Условия регистрации'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'user_registerscreen_errors.tpl',
                              'tpl_pattern'=>'^SYS_users::user::registerscreen_errors$',
                              'tpl_description'=>'Ошибка в рег. данных+форма'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'user_registerscreen_agree.tpl',
                              'tpl_pattern'=>'^SYS_users::user::registerscreen_agree$',
                              'tpl_description'=>'Форма для регистрации'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'user_register.tpl',
                              'tpl_pattern'=>'^SYS_users::user::register$',
                              'tpl_description'=>'Информация по оканчанию регистрации'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'user_lostpassscreen.tpl',
                              'tpl_pattern'=>'^SYS_users::user::lostpassscreen$',
                              'tpl_description'=>'Восстановление пароля'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'user_sendpassword.tpl',
                              'tpl_pattern'=>'^SYS_users::user::sendpassword$',
                              'tpl_description'=>'Действия для восстановления пароля'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'user_user_view.tpl',
                              'tpl_pattern'=>'^SYS_users::user::user_view',
                              'tpl_description'=>'Информация о пользователе'));


  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'admin_main.tpl',
                              'tpl_pattern'=>'SYS_users::admin::main',
                              'tpl_description'=>'Титульная админская страница'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'admin_view.tpl',
                              'tpl_pattern'=>'SYS_users::admin::view',
                              'tpl_description'=>'Список пользователей'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'admin_new.tpl',
                              'tpl_pattern'=>'^SYS_users::admin::new',
                              'tpl_description'=>'Добавление пользователя'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'admin_modify.tpl',
                              'tpl_pattern'=>'^SYS_users::admin::modify::',
                              'tpl_description'=>'Редактирование пользователя'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'admin_confirm_delete.tpl',
                              'tpl_pattern'=>'SYS_users::admin::confirm_delete',
                              'tpl_description'=>'Подтверждение удаления пользователя'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'admin_modifyconfig.tpl',
                              'tpl_pattern'=>'^SYS_users::admin::modifyconfig$',
                              'tpl_description'=>'Конфигурация'));

  //Шаблоны писем
  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'mail_reg_wait_actkey.tpl',
                              'tpl_pattern'=>'SYS_users::mail::reg_wait_actkey',
                              'tpl_description'=>'Письмо, ожидание активации пользователем'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'mail_reg_ok_msg.tpl',
                              'tpl_pattern'=>'SYS_users::mail::reg_ok_msg',
                              'tpl_description'=>'Письмо, рег.инф. + поздравления'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'mail_admin_plsactivate.tpl',
                              'tpl_pattern'=>'SYS_users::mail::admin_plsactivate',
                              'tpl_description'=>'Просьба админу активировать пользователя'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'mail_new_user_notify.tpl',
                              'tpl_pattern'=>'SYS_users::mail::new_user_notify',
                              'tpl_description'=>'Уведомление о регистрации нового пользователя'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'mail_sendpassword.tpl',
                              'tpl_pattern'=>'SYS_users::mail::sendpassword',
                              'tpl_description'=>'Высылка нового пароля пользователю'));
							  
$SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'mail_reg_group_change.tpl',
                              'tpl_pattern'=>'SYS_users::mail::group_change',
                              'tpl_description'=>'Письмо об изменении группы пользователя'));	
							  
$SYS_themes->tpl_add (array('tpl_compname'=>'SYS_users',
                              'tpl_name'=>'mail_act_resend_msg.tpl',
                              'tpl_pattern'=>'SYS_users::mail::resend_act_msg',
                              'tpl_description'=>'Напоминание об активации.'));							  
							  


  //Задаем значение переменных по умолчанию
  sysModSetVar ('SYS_users','perpage',20);
  sysModSetVar ('SYS_users','allow_register',1);
  sysModSetVar ('SYS_users','allow_lostpass',1);
  sysModSetVar ('SYS_users','minpass',6);
  sysModSetVar ('SYS_users','minuname',4);
  sysModSetVar ('SYS_users','maxuname',50);
  sysModSetVar ('SYS_users','allow_chgmail',0);
  sysModSetVar ('SYS_users','new_user_notify',1);
  sysModSetVar ('SYS_users','new_user_notify_group',1);
  sysModSetVar ('SYS_users','activation_type', 1);
  sysModSetVar ('SYS_users','activation_group',1);
  sysModSetVar ('SYS_users','uname_test_level',1);
  sysModSetVar ('SYS_users','avatar_allow_upload',1);
  sysModSetVar ('SYS_users','avatar_minposts', 25);
  sysModSetVar ('SYS_users','avatar_width',16);
  sysModSetVar ('SYS_users','avatar_height',16);
  sysModSetVar ('SYS_users','avatar_maxsize',2048);
  sysModSetVar ('SYS_users','self_delete',0);
  sysModSetVar ('SYS_users','bad_unames','root');
  sysModSetVar ('SYS_users','bad_emails','');
  sysModSetVar ('SYS_users','reg_dispdsclmr',0);
  sysModSetVar ('SYS_users','reg_disclaimer','Условия регистрации');

  // Initialisation successful
  return true;
  }

function SYS_users_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function SYS_users_delete()
  {
  // Deletion successful
  return true;
  }


function SYS_users_activate()
  {
  return true;
  }

function SYS_users_deactivate()
  {
  return false;
  }

?>
