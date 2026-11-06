<?php

function SYS_permissions_init()
  {
  sysModClassLoad ('SYS_df');
  $SYS_df = new SYS_df;

  //Создаем таблицу прав группы
  $groups_perms = sysDBGetTable ('groups_perms');
  $SYS_df->CreateTable($groups_perms, array(
    'id' =>           array('type'        => 'int',
                            'null'        => false,
                            'default'     => '0',
                            'increment'   => true,
                            'primary_key' => true),

     'gid' =>         array('type'        => 'int',
                            'null'        => false,
                            'index'       => true,
                            'default'     => '0'),

    'weight'=>        array('type'        => 'int',
                            'size'        => '4',
                            'null'        => false,
                            'index'       => true,
                            'default'     => '0'),

    'description' =>  array('type'        => 'varchar',
                            'size'        => 255,
                            'null'        => false,
                            'default'     => ''),

    'component' =>    array('type'        => 'varchar',
                            'size'        => 255,
                            'null'        => false,
                            'index'       => true,
                            'default'     => ''),

    'pattern' =>      array('type'        => 'varchar',
                            'size'        => 255,
                            'null'        => false,
                            'default'     => ''),

    'level'  =>       array('type'        => 'int',
                            'size'        => '4',
                            'null'        => false,
                            'default'     => '0')));

  //Создаем таблицу прав ползователей
  $users_perms =sysDBGetTable ('users_perms');
  $SYS_df->CreateTable($users_perms, array(
    'id' =>           array('type'        => 'int',
                            'null'        => false,
                            'default'     => '0',
                            'increment'   => true,
                            'primary_key' => true),

     'uid' =>         array('type'        => 'int',
                            'null'        => false,
                            'index'       => true,
                            'default'     => '0'),

    'weight'=>        array('type'        => 'int',
                            'size'        => '4',
                            'null'        => false,
                            'index'       => true,
                            'default'     => '0'),

    'description' =>  array('type'        => 'varchar',
                            'size'        => 255,
                            'null'        => false,
                            'default'     => ''),

    'component' =>    array('type'        => 'varchar',
                            'size'        => 255,
                            'null'        => false,
                            'index'       => true,
                            'default'     => ''),

    'pattern' =>      array('type'        => 'varchar',
                            'size'        => 255,
                            'null'        => false,
                            'default'     => ''),

    'level'  =>       array('type'        => 'int',
                            'size'        => '4',
                            'null'        => false,
                            'default'     => '0')));

  //Создаем таблицу прав владельцев
  $owners_perms=sysDBGetTable ('owners_perms');
  $SYS_df->CreateTable($owners_perms, array(
    'id' =>           array('type'        => 'int',
                            'null'        => false,
                            'default'     => '0',
                            'increment'   => true,
                            'primary_key' => true),

     'gid' =>         array('type'        => 'int',
                            'null'        => false,
                            'index'       => true,
                            'default'     => '0'),

     'weight'=>       array('type'        => 'int',
                            'size'        => '4',
                            'null'        => false,
                            'index'       => true,
                            'default'     => '0'),

    'description' =>  array('type'        => 'varchar',
                            'size'        => 255,
                            'null'        => false,
                            'default'     => ''),

    'component' =>    array('type'        => 'varchar',
                            'size'        => 255,
                            'null'        => false,
                            'index'       => true,
                            'default'     => ''),

    'pattern' =>      array('type'        => 'varchar',
                            'size'        => 255,
                            'null'        => false,
                            'default'     => ''),

    'level'  =>       array('type'        => 'int',
                            'size'        => '4',
                            'null'        => false,
                            'default'     => '0')));

  //Регистрируем темплеи в системе
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;
  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_permissions',
                              'tpl_name'=>'admin_main.tpl',
                              'tpl_pattern'=>'SYS_permissions::admin::main',
                              'tpl_description'=>'Админская титульная страница'));



  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_permissions',
                              'tpl_name'=>'admin_groups_perms_new.tpl',
                              'tpl_pattern'=>'SYS_permissions::admin::groups_perms::new',
                              'tpl_description'=>'Новые права группы'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_permissions',
                              'tpl_name'=>'admin_groups_perms_modify.tpl',
                              'tpl_pattern'=>'SYS_permissions::admin::groups_perms::modify',
                              'tpl_description'=>'Редактирование прав группы'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_permissions',
                              'tpl_name'=>'admin_groups_perms_confirm_delete.tpl',
                              'tpl_pattern'=>'SYS_permissions::admin::groups_perms::confirm_delete',
                              'tpl_description'=>'Удаление прав группы'));


  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_permissions',
                              'tpl_name'=>'admin_users_perms_new.tpl',
                              'tpl_pattern'=>'SYS_permissions::admin::users_perms::new',
                              'tpl_description'=>'Новые права пользователя'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_permissions',
                              'tpl_name'=>'admin_users_perms_modify.tpl',
                              'tpl_pattern'=>'SYS_permissions::admin::users_perms::modify',
                              'tpl_description'=>'Редактирование прав пользователей'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_permissions',
                              'tpl_name'=>'admin_users_perms_confirm_delete.tpl',
                              'tpl_pattern'=>'SYS_permissions::admin::users_perms::confirm_delete',
                              'tpl_description'=>'Удаление прав пользователя'));


  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_permissions',
                              'tpl_name'=>'admin_owners_perms_new.tpl',
                              'tpl_pattern'=>'SYS_permissions::admin::owners_perms::new',
                              'tpl_description'=>'Добавление прав владельца'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_permissions',
                              'tpl_name'=>'admin_owners_perms_modify.tpl',
                              'tpl_pattern'=>'SYS_permissions::admin::owners_perms::modify',
                              'tpl_description'=>'Редактирование прав владельца'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_permissions',
                              'tpl_name'=>'admin_owners_perms_confirm_delete.tpl',
                              'tpl_pattern'=>'SYS_permissions::admin::owners_perms::confirm_delete',
                              'tpl_description'=>'Удаление прав владельца'));

  // Initialisation successful
  return true;
  }

function SYS_permissions_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function SYS_permissions_delete()
  {
  // Deletion successful
  return false;
  }

function SYS_permissions_activate()
  {
  return true;
  }

function SYS_permissions_deactivate()
  {
  return false;
  }

?>