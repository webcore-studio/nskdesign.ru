<?php
/*
 * Модуль отображающий контактную информацию
*/
function contact_init()
  {
  sysModClassLoad ('SYS_df');
  $SYS_df = new SYS_df;

  //Создаем таблицу контактов
  $contact_table = sysDBGetTable ('contact');
  $SYS_df->CreateTable($contact_table, array(
    'id' =>              array('type'       => 'int',
                               'null'       => false,
                               'default'    => '1',
                               'increment'  => true,
                               'primary_key'=> true),

    'user_id'=>          array('type'       => 'int', //Ид пользователя ост коммент
                               'null'       => false,
                               'default'    => -1,
                               'index'      => true),

    'rec_id'=>           array('type'       => 'int', //Группа получателей
                               'null'       => false,
                               'default'    => -1,
                               'index'      => true),

    'user_displayname' =>array('type'       => 'varchar', //Имя пользователя, если постил аноним
                               'size'       => 64,
                               'null'       => false,
                               'default'    => '',
                               'index'      => true),

    'user_email'       =>array('type'       => 'varchar',
                               'size'       => 64,
                               'null'       => false,
                               'default'    => '',
                               'index'      => true),

    'contact_addtime' =>   array ('type'       => 'int',
                               'null'       => false,
                               'canmodify'  => true,
                               'index'      => true),

    'contact_from_ip' =>   array('type'        => 'char', //Ipшник откуда запостили
                               'size'       => 16,
                               'null'       => false,
                               'canmodify'  => true,
                               'default'    => ''),

    'contact_displayname' =>array('type'       => 'varchar', //Тема контакта
                               'size'       => 255,
                               'null'       => false,
                               'default'    => ''),

    'contact_content' =>    array('type'       => 'text', //Содержимое сообшения
                               'null'       => false,
                               'default'    => '')));


  //Регистрируем темплеи в системе
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes();

	//Обычне шаблоны
  $SYS_themes->tpl_add (array('tpl_compname'=>'contact',
                              'tpl_name'=>'user_main.tpl',
                              'tpl_pattern'=>'contact::user::main',
                              'tpl_description'=>'Добавление сообшения'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'contact',
                              'tpl_name'=>'admin_main.tpl',
                              'tpl_pattern'=>'contact::admin::main',
                              'tpl_description'=>'Последние отправленные сообшения'));

	//
  $SYS_themes->tpl_add (array('tpl_compname'=>'contact',
                              'tpl_name'=>'admin_config_modify.tpl',
                              'tpl_pattern'=>'contact::admin::config_modify',
                              'tpl_description'=>'Редактирование настроек модуля'));


	//Шаблоны под аякс
  $SYS_themes->tpl_add (array('tpl_compname'=>'contact',
                              'tpl_name'=>'ajax_create.tpl',
                              'tpl_pattern'=>'contact::ajax::create',
                              'tpl_description'=>'Создание нового сообшения'));

  //Отправка письма
  $SYS_themes->tpl_add (array('tpl_compname'=>'contact',
                              'tpl_name'=>'mail_message.tpl',
                              'tpl_pattern'=>'contact::mail::message',
                              'tpl_description'=>'Шаблон письма'));


	//Дефолтные параметры модуля
  sysModSetVar ('contact','email_notify_enable', true);
  sysModSetVar ('contact','captcha_enabled', true);
  sysModSetVar ('contact','before_send_text', '<p align="justify">Отправьте сообщение через форму обратной связи.</p>');
  sysModSetVar ('contact','after_send_text',  '<p align="justify">Спасибо. Ваше сообщение успешно отправлено.</p>');
  sysModSetVar ('contact','rec_list',  '');

  // Initialisation successful
  return true;
  }

function contact_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function contact_delete()
  {
  // Deletion successful
  return true;
  }

function contact_activate()
  {
  return true;
  }

function contact_deactivate()
  {
  return true;
  }

?>