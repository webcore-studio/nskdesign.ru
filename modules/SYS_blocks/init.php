<?php

function SYS_blocks_init()
  {
  sysModClassLoad ('SYS_df');
  $SYS_df = new SYS_df;

  //Создаем таблицу
  $blocks_table = sysDBGetTable ('blocks');
  $SYS_df->CreateTable($blocks_table, array(
    'id' =>                 array('type'        => 'int',
                                  'null'        => false,
                                  'default'     => '0',
                                  'increment'   => true,
                                  'primary_key' => true),

    'weight' =>             array('type'        => 'int',
                                  'size'        => 1,
                                  'null'        => false,
                                  'index'       => true,
                                  'default'     => 0),

    'block_name' =>         array('type'        => 'varchar',
                                  'size'        => 60,
                                  'null'        => false,
                                  'default'     => ''),

    'block_displayname' =>  array('type'        => 'varchar',
                                  'size'        => 60,
                                  'null'        => false,
                                  'default'     => ''),

    'block_comment' =>  array('type'        => 'varchar',
                                  'size'        => 60,
                                  'null'        => false,
                                  'default'     => ''),

    'block_pattern' =>      array('type'        => 'varchar',
                                  'size'        => 255,
                                  'null'        => false,
                                  'default'     => ''),

    'block_content' =>      array('type'        => 'text',
                                  'null'        => false,
                                  'default'     => ''),

    'block_position' =>     array('type'        => 'varchar',
                                  'size'        => 10,
                                  'null'        => false,
                                  'index'       => true,
                                  'default'     => 'l'),

    'block_active' =>       array('type'        => 'tinyint',
                                  'size'        => 1,
                                  'null'        => false,
                                  'index'       => true,
                                  'default'     => 0),

    'block_refresh' =>      array('type'        => 'int',
                                  'null'        => false,
                                  'default'     => 0),

    'block_last_update' =>  array('type'        => 'varchar',
                                  'size'        => 14,
                                  'null'        => true),

    'block_lang' =>         array('type'        => 'varchar',
                                  'size'        => 5,
                                  'null'        => false,
                                  'index'       => true,
                                  'default'     => ''),
	'block_tpl' =>      	array('type'        => 'varchar',
                                  'size'        => 255,
                                  'null'        => false,
                                  'default'     => ''),));

  //Регистрируем темплеи в системе
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes();
  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_blocks',
                             'tpl_name'=>'admin_main.tpl',
                             'tpl_pattern'=>'SYS_blocks::admin::main',
                             'tpl_description'=>'Админская титульная страница'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_blocks',
                             'tpl_name'=>'admin_info.tpl',
                             'tpl_pattern'=>'SYS_blocks::admin::info',
                             'tpl_description'=>'Информация о блоке'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_blocks',
                             'tpl_name'=>'admin_modify.tpl',
                             'tpl_pattern'=>'SYS_blocks::admin::modify',
                             'tpl_description'=>'Редактирование блока'));


  // Initialisation successful
  return true;
  }

function SYS_blocks_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function SYS_blocks_delete()
  {
  // Deletion successful
  return false;
  }

function SYS_blocks_activate()
  {
  return true;
  }

function SYS_blocks_deactivate()
  {
  return false;
  }

?>