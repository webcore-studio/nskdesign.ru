<?php

function SYS_modules_init()
  {
  sysModClassLoad ('SYS_df');
  $SYS_df = new SYS_df();
  //Создаем таблицу переменных модуля
  $module_varstable = sysDBGetTable ('module_vars');
  $SYS_df->CreateTable($module_varstable, array(
    'id' =>     array('type'        => 'int',
                      'null'        => false,
                      'default'     => '0',
                      'increment'   => true,
                      'primary_key' => true),

    'modname' => array('type'       => 'varchar',
                       'size'       => 64,
                       'null'       => false,
                       'index'       => true,
                       'default'    => ''),

    'name' =>   array( 'type'       => 'varchar',
                       'size'       => 64,
                       'null'       => false,
                       'index'       => true,
                       'default'    => ''),

    'value' =>   array('type'       => 'longtext',
                       'null'       => false,
                       'default'    => '')));

  //Создаем таблицу модулей системы
  $modules_table = sysDBGetTable ('modules');
  $SYS_df->CreateTable($modules_table, array(
    'id' =>             array('type'       => 'int',
                              'null'       => false,
                              'default'    => '0',
                              'increment'  => true,
                              'primary_key'=> true),

    'mod_name' =>       array('type'       => 'varchar',
                              'size'       => 64,
                              'null'       => false,
                              'index'       => true,
                              'default'    => ''),

    'mod_type' =>       array('type'       => 'varchar',
                              'size'       => 8,
                              'null'       => false,
                              'default'    => ''),

    'mod_displayname' =>array('type'       => 'varchar',
                              'size'       => 255,
                              'null'       => false,
                              'default'    => ''),

    'mod_description' =>array('type'       => 'varchar',
                              'size'       => 255,
                              'null'       => false,
                              'default'    => ''),

    'mod_version' =>    array('type'       => 'varchar',
                              'size'       => 10,
                              'null'       => false,
                              'default'    => ''),

    'mod_state' =>      array('type'       => 'tinyint',
                              'size'       => 1,
                              'null'       => false,
                              'index'       => true,
                              'default'    => 0),

    'mod_credits' =>    array('type'       => 'text',
                              'null'       => false,
                              'default'    => ''),


    'mod_changelog' =>  array('type'       => 'text',
                              'null'       => false,
                              'default'    => ''),

    'mod_license' =>    array('type'       => 'text',
                              'null'       => false,
                              'default'    => ''),

    'mod_official' =>   array('type'       => 'text',
                              'null'       => false,
                              'default'    => ''),

    'mod_author' =>     array('type'       => 'text',
                              'null'       => false,
                              'default'    => ''),

    'mod_contact' =>    array('type'       => 'text',
                              'null'       => false,
                              'default'    => ''),

    'mod_dependency' => array('type'       => 'text',
                              'null'       => false,
                              'default'    => '')));
  /* Темплеи этого модуля регистрируютца из инсталятора
  //Регистрируем темплеи в системе
  systpl_add (array('tpl_compname'=>'SYS_modules',
                   'tpl_name'=>'admin_main.tpl',
                   'tpl_pattern'=>'SYS_modules::admin::main',
                   'tpl_description'=>'Шаблон титульной страницы'));

  systpl_add (array('tpl_compname'=>'SYS_modules',
                   'tpl_name'=>'admin_info.tpl',
                   'tpl_pattern'=>'SYS_modules::admin::info',
                   'tpl_description'=>'Информация о модуле'));
  */
  // Initialisation successful
  return true;
  }

function SYS_modules_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function SYS_modules_delete()
  {
  return false;
  }


function SYS_modules_activate()
  {
  return true;
  };

function SYS_modules_deactivate()
  {
  return false;
  };

?>