<?php

function SYS_df_init()
  {
  //Регистрируем темплеи в системе
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;
  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_df',
                             'tpl_name'=>'admin_main.tpl',
                             'tpl_pattern'=>'SYS_df::admin::main',
                             'tpl_description'=>'Главная админская страница'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_df',
                             'tpl_name'=>'admin_view.tpl',
                             'tpl_pattern'=>'SYS_df::admin::view',
                             'tpl_description'=>'Просмотр таблицы'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_df',
                             'tpl_name'=>'admin_new.tpl',
                             'tpl_pattern'=>'SYS_df::admin::new',
                             'tpl_description'=>'Новое поле'));


  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_df',
                             'tpl_name'=>'admin_confirm_delete.tpl',
                             'tpl_pattern'=>'SYS_df::admin::confirm_delete',
                             'tpl_description'=>'Удаление поля'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_df',
                             'tpl_name'=>'admin_modify.tpl',
                             'tpl_pattern'=>'SYS_df::admin::modify',
                             'tpl_description'=>'Редактирование поля'));

  // Initialisation successful
  return true;
  }

function SYS_df_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function SYS_df_delete()
  {
  // Deletion successful
  return false;
  }

function SYS_df_activate()
  {
  return true;
  }

function SYS_df_deactivate()
  {
  return false;
  }

?>