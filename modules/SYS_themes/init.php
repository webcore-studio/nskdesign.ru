<?php

function SYS_themes_init()
  {
  //Создаем таблицу тем
  $themes_table = sysDBGetTable ('themes');
  sysModClassLoad ('SYS_df');
  $SYS_df = new SYS_df();
  $SYS_df->CreateTable($themes_table, array(
    'id' =>               array('type'        => 'int',
                                'null'        => false,
                                'default'     => '0',
                                'increment'   => true,
                                'primary_key' => true),

    'weight' =>           array('type'        => 'int',
                                'null'        => false,
                                'index'       => true,
                                'default'     => '0'),

    'theme_name' =>       array('type'        => 'varchar',
                                'size'        => 25,
                                'null'        => false,
                                'index'       => true,
                                'default'     => ''),

    'theme_displayname'=> array('type'        => 'varchar',
                                'size'        => 60,
                                'null'        => false,
                                'default'     => ''),

    'theme_active' =>     array('type'        => 'int',
                                'null'        => false,
                                'index'       => true,
                                'default'     => 1)));

  //Создаем таблицу темплеев
  $templates_table=sysDBGetTable ('templates');
  $SYS_df->CreateTable($templates_table, array(
    'id' =>               array('type'        => 'int',
                                'null'        => false,
                                'default'     => '0',
                                'increment'   => true,
                                'primary_key' => true),

    'weight' =>           array('type'        => 'int',
                                'null'        => false,
                                'index'       => true,
                                'default'     => '0'),

    'tpl_type' =>         array('type'        => 'varchar',
                                'size'        => 6,
                                'null'        => false,
                                'default'     => 'module'),

    'tpl_compname' =>     array('type'        => 'varchar',
                                'size'        => 60,
                                'null'        => false,
                                'index'       => true,
                                'default'     => ''),

    'tpl_name' =>         array('type'        => 'varchar',
                                'size'        => 60,
                                'null'        => false,
                                'default'     => ''),

    'tpl_description' =>  array('type'        => 'varchar',
                                'size'        => 255,
                                'null'        => false,
                                'default'     => ''),

    'tpl_pattern' =>      array('type'        => 'varchar',
                                'size'        => 255,
                                'null'        => false,
                                'default'     => '')));

  //Регистрируем темплеи в системе
  include_once ('modules/SYS_themes/class.php');//Это потому что на момент инсталяции функция sysModLoad еше не сушествует
  $SYS_themes = new SYS_themes();

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_themes',
                              'tpl_name'=>'admin_main.tpl',
                              'tpl_pattern'=>'SYS_themes::admin::main',
                              'tpl_description'=>'Шаблон титульной страницы'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_themes',
                              'tpl_name'=>'admin_template_main.tpl',
                              'tpl_pattern'=>'SYS_themes::admin::template::main',
                              'tpl_description'=>'Главная страница шаблонов компонентов'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_themes',
                              'tpl_name'=>'admin_template_new.tpl',
                              'tpl_pattern'=>'SYS_themes::admin::template::new',
                              'tpl_description'=>'Шаблон создания шаблона'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_themes',
                              'tpl_name'=>'admin_template_view.tpl',
                              'tpl_pattern'=>'SYS_themes::admin::template::view',
                              'tpl_description'=>'Просмотр шаблонов темы компонента'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_themes',
                              'tpl_name'=>'admin_template_modify.tpl',
                              'tpl_pattern'=>'SYS_themes::admin::template::modify',
                              'tpl_description'=>'Редактирование шаблона'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'SYS_themes',
                              'tpl_name'=>'admin_template_confirm_delete.tpl',
                              'tpl_pattern'=>'SYS_themes::admin::template::confirm_delete',
                              'tpl_description'=>'Удаление шаблона'));

  // Initialisation successful
  return true;
  }

function SYS_themes_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function SYS_themes_delete()
  {
  // Deletion successful
  return false;
  }


function SYS_themes_activate()
  {
  return true;
  }

function SYS_themes_deactivate()
  {
  return false;
  }

?>