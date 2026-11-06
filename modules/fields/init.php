<?php
/* Логика работы:


В шаблоне где нужны дополнительные поля вставляем:
  {fields modname='zozo' comm_key_a='12' comm_key_b='18'}


//PHP код:
Для тесной интеграции с модулем(например content), модуль МОЖЕТ содержать функции:


// Шаблоны полей могут переопределятся модулем, темой и т.п.
Должны быть зарегистрированы по принцыпу:
$SYS_themes->tpl_add (array('tpl_compname'=>'modname',
                            'tpl_name'=>'admin_fields_new.tpl',
                            'tpl_pattern'=>'modname::fields::new',
                            'tpl_description'=>'Переопределяем форму создания поля'));

*/
function fields_init()  {
  
	sysModClassLoad ('SYS_df');
	$SYS_df = new SYS_df;

	//Создаем таблицу доп.полей
	$fields_table = sysDBGetTable ('fields');
	$SYS_df->CreateTable($fields_table, array(
		'id' =>              array('type'       => 'int',
                               'null'       => false,
                               'default'    => '1',
                               'increment'  => true,
                               'primary_key'=> true),
	'fld_name' =>array('type'       => 'varchar', 
                               'size'       => 32,
                               'null'       => false,
                               'default'    => '',
                               'index'      => true),
							   
		'fld_displayname' =>array('type'       => 'varchar', 
                               'size'       => 64,
                               'null'       => false,
                               'default'    => '',
                               'index'      => true),
							   
		'fld_module' =>     array('type'       => 'char', //Модуль к которому относится 
                               'size'       => 32,
                               'null'       => false,
                               'default'    => '',
                               'index'      => true),

		'fld_type' =>     	array('type'       => 'char', 
                               'size'       => 32,
                               'null'       => false,
                               'default'    => '',
                               'index'      => true),
							   

		'fld_key_a' 	 =>   array ('type'       => 'int',
                               'null'       => false,
                               'canmodify'  => true,
                               'index'      => true),

		'fld_key_b' 	 =>   array ('type'       => 'int',
                               'null'       => false,
                               'canmodify'  => true,
                               'index'      => true),

		'fld_inherit' 	 =>   array ('type'       => 'tinyint',
								'size'       => 1,
								'default'    => '1')));
								
								
	//Создаем таблицу вариантов значений доп.полей
	$param_table = sysDBGetTable ('fields_param');
	$SYS_df->CreateTable($param_table, array(
		'id' =>              array('type'       => 'int',
                               'null'       => false,
                               'default'    => '1',
                               'increment'  => true,
                               'primary_key'=> true),
							   
		'id_field' =>       array('type'       => 'int',
                               'null'       => false,
                               'index'	=> true),

		'fld_key_a' 	 =>   array ('type'       => 'int',
                               'null'       => false,
                               'canmodify'  => true,
                               'index'      => true),

		'fld_key_b' 	 =>   array ('type'       => 'int',
                               'null'       => false,
                               'canmodify'  => true,
                               'index'      => true),
								
		'fld_value' =>array('type'       => 'varchar', 
                               'size'       => 64,
                               'null'       => false,
                               'index'      => true)));
							   

	//Создаем таблицу фактических вариантов значений доп.полей
	$values_table = sysDBGetTable ('fields_values');
	$SYS_df->CreateTable($values_table, array(
		'id' =>              array('type'       => 'int',
                               'null'       => false,
                               'default'    => '1',
                               'increment'  => true,
                               'primary_key'=> true),
							   
		'id_field' =>       array('type'       => 'int',
                               'null'       => false,
                               'index'	=> true),

		'fld_field' =>array('type'       => 'varchar', 
                               'size'       => 64,
                               'null'       => false,
                               'index'      => true),
							   
		'fld_default' 	 =>   array ('type'       => 'tinyint',
								'size'       => 1,
								'default'    => '0'),
								
		'fld_image' =>array('type'       => 'varchar', 
                               'size'       => 64,
                               'null'       => false,
                               'default'    => '',
                               'index'      => true)));
							   
							   
							   
  //Регистрируем темплеи в системе
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes();

	//Обычне шаблоны
  $SYS_themes->tpl_add (array('tpl_compname'=>'fields',
                              'tpl_name'=>'user_main.tpl',
                              'tpl_pattern'=>'fields::user::main',
                              'tpl_description'=>'Главная страница модуля'));

	//
  $SYS_themes->tpl_add (array('tpl_compname'=>'fields',
                              'tpl_name'=>'admin_config_modify.tpl',
                              'tpl_pattern'=>'fields::admin::config_modify',
                              'tpl_description'=>'Редактирование настроек модуля'));


	//Шаблоны под аякс
  $SYS_themes->tpl_add (array('tpl_compname'=>'fields',
                              'tpl_name'=>'ajax_manage.tpl',
                              'tpl_pattern'=>'fields::ajax::manage',
                              'tpl_description'=>'Добавление возможности управления доп. полями'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'fields',
                              'tpl_name'=>'admin_list.tpl',
                              'tpl_pattern'=>'fields::admin::list',
                              'tpl_description'=>'Список доп. полей для объекта'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'fields',
                              'tpl_name'=>'ajax_new.tpl',
                              'tpl_pattern'=>'fields::ajax::new',
                              'tpl_description'=>'Создание нового отзыва'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'fields',
                              'tpl_name'=>'ajax_create.tpl',
                              'tpl_pattern'=>'fields::ajax::create',
                              'tpl_description'=>'Создание нового отзыва'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'fields',
                              'tpl_name'=>'ajax_modify.tpl',
                              'tpl_pattern'=>'fields::ajax::modify',
                              'tpl_description'=>'Редактирование отзыва'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'fields',
                              'tpl_name'=>'ajax_update.tpl',
                              'tpl_pattern'=>'fields::ajax::update',
                              'tpl_description'=>'Редактирование отзыва'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'fields',
                              'tpl_name'=>'ajax_delete.tpl',
                              'tpl_pattern'=>'fields::ajax::delete',
                              'tpl_description'=>'Удаление отзыва'));
							  
	 $SYS_themes->tpl_add (array('tpl_compname'=>'fields',
                              'tpl_name'=>'ajax_values.tpl',
                              'tpl_pattern'=>' fields::ajax::values',
                              'tpl_description'=>'Просмотр списка значений доп. полей для объекта'));
							  
			 

  // Initialisation successful
  return true;
  }

function fields_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function fields_delete()
  {
  // Deletion successful
  return true;
  }

function fields_activate()
  {
  return true;
  }

function fields_deactivate()
  {
  return true;
  }

?>