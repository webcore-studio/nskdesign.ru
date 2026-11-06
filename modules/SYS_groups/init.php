<?php

function SYS_groups_init()
  {
  //Создаем таблицу
  sysModClassLoad('SYS_df');
  $SYS_df = new SYS_df;

  $groupstable = sysDBGetTable ('groups');
  $SYS_df->CreateTable($groupstable, array(
    'id' =>                array('type'        => 'int',
                                 'null'        => false,
                                 'default'     => '0',
                                 'increment'   => true,
                                 'primary_key' => true),

    'group_displayname' => array('type'        => 'varchar',
                                 'size'        => 60,
                                 'null'        => false,
                                 'default'     => ''),

    'group_description' => array('type'        => 'varchar',
                                 'size'        => 255,
                                 'null'        => false,
                                 'default'     => '')));

  //Регистрируем темплеи в системе
  sysModClassLoad('SYS_themes');
  $SYS_themes = new SYS_themes;

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_groups',
                              'tpl_name'=>'admin_main.tpl',
                              'tpl_pattern'=>'SYS_groups::admin::main',
                              'tpl_description'=>'Главная админская страница'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_groups',
                              'tpl_name'=>'admin_modify.tpl',
                              'tpl_pattern'=>'SYS_groups::admin::modify',
                              'tpl_description'=>'Редактирование группы'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_groups',
                              'tpl_name'=>'admin_confirm_delete.tpl',
                              'tpl_pattern'=>'SYS_groups::admin::confirm_delete',
                              'tpl_description'=>'Подтверждение удаления группы'));

  // Initialisation successful
  return true;
  }

function SYS_groups_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function SYS_groups_delete()
  {
  // Deletion successful
  return false;
  }

function SYS_groups_activate()
  {
  return true;
  }

function SYS_groups_deactivate()
  {
  return false;
  }

?>