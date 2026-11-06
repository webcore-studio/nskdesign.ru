<?php

function advert_init()
{
	sysModClassLoad ('SYS_df');
	$SYS_df = new SYS_df;

	//Создаем таблицу мест под баннеры
	$place_table = sysDBGetTable ('advert_places');
	$SYS_df->CreateTable($place_table, array(
		'id' =>              array('type'       => 'int',
                               'size'       => 11,
                               'null'       => false,
                               'default'    => '1',
                               'increment'  => true,
                               'primary_key'=> true),

		'name' =>              array('type'       => 'varchar',
                               'size'       => 20,
                                'null'       => false,
                               'default'    => ''),

		'width' =>              array('type'       => 'int',
                             	  'size'       => 11,
                              	  'null'       => false),
		
		'height' =>              array('type'       => 'int',
                               	   'size'       => 11,
                              	   'null'       => false),

		'block_id' =>              array('type'       => 'int',
                               	   'size'       => 11,
                              	   'null'       => false)));

 
	//Создаем таблицу баннеров
	$images_table = sysDBGetTable ('advert_banners');
	$SYS_df->CreateTable($images_table, array(
		'id' =>              array('type'       => 'int',
                               'size'       => 11,
                               'null'       => false,
                               'default'    => '1',
                               'increment'  => true,
                               'primary_key'=> true),

		'weight' =>             array('type'       => 'int',
                             	  'size'       => 11,
                              	  'null'       => false,
								  'default'    => '1'),
								  
		'image' =>      	 array('type'       => 'varchar',
                               'size'       => 100,
                               'null'       => false,
                               'default'    => '0'),

		'active' =>    	array('type'       => 'tinyint',
                               'size'       => 1,
                               'null'       => false,
                               'default'    => 0,
                               'index'      => true),


		'url'=>		array('type'       => 'varchar',
                               'size'       => 100,
                               'null'       => true,
                               'default'    => NULL),

		'alt'=>		array('type'       => 'varchar',
                               'size'       => 100,
                               'null'       => true,
                               'default'    => NULL),


		'text'=>		array('type'       => 'varchar',
                               'size'       => 255,
                               'null'       => true,
                               'default'    => NULL),

		'place' =>              array('type'       => 'int',
                             	  'size'       => 11,
                              	  'null'       => false),
	
		'pattern'=>		array('type'       => 'varchar',
                               'size'       => 255,
                               'default'    => ".*"),

		'banner_type'=>		array('type'       => 'varchar',
                               'size'       => 50,
                               'default'    => "image"),
	
		'additional'=>		array('type'       => 'text')));


	//Регистрируем темплеи в системе
	sysModClassLoad ('SYS_themes');
	$SYS_themes = new SYS_themes();

	//Административные
	$SYS_themes->tpl_add (array('tpl_compname'=>'advert',
                              'tpl_name'=>'admin_main.tpl',
                              'tpl_pattern'=>'advert::admin::main',
                              'tpl_description'=>'Главная админская страница'));

	$SYS_themes->tpl_add (array('tpl_compname'=>'advert',
                              'tpl_name'=>'admin_banner_modify.tpl',
                              'tpl_pattern'=>'advert::admin::banner::modify',
                              'tpl_description'=>'Редактирование баннера'));

	$SYS_themes->tpl_add (array('tpl_compname'=>'advert',
                              'tpl_name'=>'admin_banner_new.tpl',
                              'tpl_pattern'=>'advert::admin::banner::new',
                              'tpl_description'=>'Добавление баннера'));

	$SYS_themes->tpl_add (array('tpl_compname'=>'advert',
                              'tpl_name'=>'admin_place_modify.tpl',
                              'tpl_pattern'=>'advert::admin::place::modify',
                              'tpl_description'=>'Редактирование места'));




	return true;
}

function advert_upgrade($oldversion)
{
	// Update successful
	return false;
}

function advert_delete()
{
	//Удаляем таблицы
	sysModClassLoad('SYS_df');  
	$SYS_df = new SYS_df();
	$places_table = sysDBGetTable ('advert_places');  
	$SYS_df->DeleteTable($places_table);
	$banners_table = sysDBGetTable ('advert_banners');  
	$SYS_df->DeleteTable($banners_table);

	return true;
}

function advert_activate()
{
	return true;
}

function advert_deactivate()
{
	return true;
}

?>