<?php
/* Логика работы:

//Шаблоны модуля в который вставляется коммент
Место которое содержит список новостей и поле для добавления коммента:
<div id="mod_comment_new"></div> //Нужно для ajax
<div id="mod_comment_list"></div> //Нужно для ajax

В шаблоне где нужен список комментов вставляем:
  {comment_list modname='zozo' comm_key_a='12' comm_key_b='18' sort='time' asc='true'}

Поле для добавления коммента:
	{comment_new modname='zozo' comm_key_a='12' comm_key_b='18'} //Ключи для связки коммента с контентом например номер раздела и статья

При княпаньй на редактировании <div id="mod_comment_ajax"> - появляется форма редактирования
При княпаньй на удалении ява запрос на подтверждение удаления


//PHP код:
Для тесной интеграции с модулем(например content), модуль МОЖЕТ содержать функции:

//Вызывается до добавления коммента, нужна для валидации и дополнения введенных данных
function modname_comment_new ($comment_param)
  {
  return $comment_param;
  }

//Вызывается после добавления коммента, например для очистке кешей или увеличения счетчика комментов в чемто
function modname_comment_create ($comment_param)
  {
  return;
  }

//Также по аналогии функции modname_comment_modify, modname_comment_update,
														modname_comment_confirm_delete, modname_comment_delete


//
Шаблон списка комментариев, добавления и редактирования комментария
могут переопределятся модулем, темой и т.п.
Должны быть зарегестрированны по принцыпу:
$SYS_themes->tpl_add (array('tpl_compname'=>'modname',
                            'tpl_name'=>'admin_comment_new.tpl',
                            'tpl_pattern'=>'modname::comment::new',
                            'tpl_description'=>'Переопределяем форму создания коммента'));

*/
function comment_init()
  {
  sysModClassLoad ('SYS_df');
  $SYS_df = new SYS_df;

  //Создаем таблицу комментариев
  $comment_table = sysDBGetTable ('comment');
  $SYS_df->CreateTable($comment_table, array(
    'id' =>              array('type'       => 'int',
                               'null'       => false,
                               'default'    => '1',
                               'increment'  => true,
                               'primary_key'=> true),

    'user_id'=>          array('type'       => 'int', //Ид пользователя ост коммент
                               'null'       => false,
                               'default'    => -1,
                               'index'      => true),

    'user_displayname' =>array('type'       => 'varchar', //Имя пользователя, если постил аноним
                               'size'       => 64,
                               'null'       => false,
                               'default'    => '',
                               'index'      => true),

    'to_user_id'=>       array('type'       => 'int', //Автор статьй или новости - к которой прилепили коммент
                               'null'       => false,
                               'default'    => -1,
                               'index'      => true),

    'comm_addtime' =>   array ('type'       => 'int',
                               'null'       => false,
                               'canmodify'  => true,
                               'index'      => true),

    'comm_modtime' =>   array ('type'       => 'int',
                               'null'       => false,
                               'canmodify'  => true,
                               'index'      => true),

    'comm_modcount' =>  array ('type'       => 'tinyint',
                               'null'       => false,
                               'canmodify'  => true),

    'comm_key_a' 	 =>   array ('type'       => 'int',
                               'null'       => false,
                               'canmodify'  => true,
                               'index'      => true),

    'comm_key_b' 	 =>   array ('type'       => 'int',
                               'null'       => false,
                               'canmodify'  => true,
                               'index'      => true),

    'comm_from_ip' =>   array('type'        => 'char', //Ipшник откуда запостили коммент
                               'size'       => 16,
                               'null'       => false,
                               'canmodify'  => true,
                               'default'    => ''),

    'comm_module' =>     array('type'       => 'char', //Модуль к которому относится коммент
                               'size'       => 32,
                               'null'       => false,
                               'default'    => '',
                               'index'      => true),

    'comm_name' =>       array('type'       => 'varchar', //Имя того к чему относится коммент
                               'size'       => 255,
                               'null'       => false,
                               'default'    => ''),

    'comm_displayname' =>array('type'       => 'varchar', //Пользовательское название комментария
                               'size'       => 255,
                               'null'       => false,
                               'default'    => ''),

    'comm_view_url' =>   array('type'       => 'varchar', //Урл где можно посмотреть то к чему относится коммент
                               'size'       => 255,
                               'null'       => false,
                               'default'    => ''),

    'comm_content' =>    array('type'       => 'text', //Сам коммент
                               'null'       => false,
                               'default'    => ''),
	'comm_fields' =>    array('type'       => 'text', //Сам коммент
                               'null'       => false,
                               'default'    => '')));


  //Регистрируем темплеи в системе
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes();

	//Обычне шаблоны
  $SYS_themes->tpl_add (array('tpl_compname'=>'comment',
                              'tpl_name'=>'user_main.tpl',
                              'tpl_pattern'=>'comment::user::main',
                              'tpl_description'=>'Последнии комментарии'));

	//
  $SYS_themes->tpl_add (array('tpl_compname'=>'comment',
                              'tpl_name'=>'admin_config_modify.tpl',
                              'tpl_pattern'=>'comment::admin::config_modify',
                              'tpl_description'=>'Редактирование настроек модуля'));


	//Шаблоны под аякс
  $SYS_themes->tpl_add (array('tpl_compname'=>'comment',
                              'tpl_name'=>'ajax_preview.tpl',
                              'tpl_pattern'=>'comment::ajax::preview',
                              'tpl_description'=>'Предпросмотер отзыва'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'comment',
                              'tpl_name'=>'ajax_list.tpl',
                              'tpl_pattern'=>'comment::ajax::list',
                              'tpl_description'=>'Шаблон списка отзывов'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'comment',
                              'tpl_name'=>'ajax_new.tpl',
                              'tpl_pattern'=>'comment::ajax::new',
                              'tpl_description'=>'Создание нового отзыва'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'comment',
                              'tpl_name'=>'ajax_create.tpl',
                              'tpl_pattern'=>'comment::ajax::create',
                              'tpl_description'=>'Создание нового отзыва'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'comment',
                              'tpl_name'=>'ajax_modify.tpl',
                              'tpl_pattern'=>'comment::ajax::modify',
                              'tpl_description'=>'Редактирование отзыва'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'comment',
                              'tpl_name'=>'ajax_update.tpl',
                              'tpl_pattern'=>'comment::ajax::update',
                              'tpl_description'=>'Редактирование отзыва'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'comment',
                              'tpl_name'=>'ajax_delete.tpl',
                              'tpl_pattern'=>'comment::ajax::delete',
                              'tpl_description'=>'Удаление отзыва'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'comment',
                              'tpl_name'=>'mail_create.tpl',
                              'tpl_pattern'=>'comment::mail::create',
                              'tpl_description'=>'Уведомление о добавлении комментария'));
	//Дефолтные параметры модуля
  sysModSetVar ('comment','comment_enabled', true);
  sysModSetVar ('comment','comment_perpage', '20');

  sysModSetVar ('comment','content_min_len', '10');
  sysModSetVar ('comment','content_max_len', '5000');

  sysModSetVar ('comment','sort_by', 'addtime');
  sysModSetVar ('comment','sort_asc', false);

  sysModSetVar ('comment','flood_ip_time', '30');
  sysModSetVar ('comment','email_notify_enable', false);
  sysModSetVar ('comment','email_notify_group', '1');
  sysModSetVar ('comment','use_texteditor', '0');

  // Initialisation successful
  return true;
  }

function comment_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function comment_delete()
  {
  // Deletion successful
  return true;
  }

function comment_activate()
  {
  return true;
  }

function comment_deactivate()
  {
  return true;
  }

?>