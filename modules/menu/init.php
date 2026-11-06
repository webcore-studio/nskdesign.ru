<?php

function menu_init()
  {
  sysModClassLoad ('SYS_df');
  $SYS_df = new SYS_df;

  //Создаем таблицу папак
  $menu_table = sysDBGetTable ('menu');
  $SYS_df->CreateTable($menu_table, array(
    'id' =>              array('type'       => 'int',
                               'null'       => false,
                               'default'    => '1',
                               'increment'  => true,
                               'primary_key'=> true),

    'parent_id' =>       array('type'       => 'int',
                               'null'       => false,
                               'default'    => '0',
                               'index'      => true),

    'weight'=>           array('type'       => 'int',
                               'size'       => '4',
                               'null'       => false,
                               'default'    => '0',
                               'index'      => true),

    'uid'=>              array('type'       => 'int',
                               'null'       => false,
                               'default'    => -1,
                               'index'      => true),

    'item_type'=>        array('type'       => 'tinyint',
                               'size'       => '1',
                               'null'       => false,
                               'default'    => -1,
                               'index'      => true),

    'item_pattern'=>     array('type'       => 'varchar',
                               'size'       => '255',
                               'null'       => false,
                               'default'    => ''),

    'active' =>          array('type'       => 'tinyint',
                               'size'       => 1,
                               'null'       => false,
                               'default'    => 0,
                               'index'      => true),

    'subitem_counter' => array('type'       => 'int',
                               'null'       => false,
                               'default'    => 0),


    'displayname' =>     array('type'       => 'varchar',
                               'size'       => 60,
                               'null'       => false,
                               'default'    => ''),

    'description' =>     array('type'       => 'text',
                               'null'       => false,
                               'default'    => ''),

    'content' =>         array('type'       => 'text',
                               'null'       => false,
                               'default'    => ''),
    'pattern' =>     array('type'       => 'varchar',
                               'size'       => 128,
                               'null'       => false,
                               'default'    => '')));


  //Регистрируем темплеи в системе
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes();

  $SYS_themes->tpl_add (array('tpl_compname'=>'menu',
                              'tpl_name'=>'admin_main.tpl',
                              'tpl_pattern'=>'menu::admin::main',
                              'tpl_description'=>'Главная админская страница'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'menu',
                              'tpl_name'=>'admin_new.tpl',
                              'tpl_pattern'=>'menu::admin::new',
                              'tpl_description'=>'Создание нового элемента'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'menu',
                              'tpl_name'=>'admin_modify.tpl',
                              'tpl_pattern'=>'menu::admin::modify',
                              'tpl_description'=>'Редактирование элемента'));


  $SYS_themes->tpl_add (array('tpl_compname'=>'menu',
                              'tpl_name'=>'admin_confirm_delete.tpl',
                              'tpl_pattern'=>'menu::admin::confirm_delete',
                              'tpl_description'=>'Подтверждение удаления элемента'));

  //Создаем дефаултные менюшки
  sysModClassLoad('menu');
  $menu = new menu;

  // Главное меню
  $main_menu_id = $menu->create(array('item_type'=>2,
                                      'parent_id'=>0,
                                      'active'=>1,
                                      'displayname'=>'Главное меню'
                                     ));
                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $main_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Главная',
                                      'content'=>'/',
                                      ));
                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $main_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Новости',
                                      'content'=>'/news',
                                      ));
                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $main_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'О компании',
                                      'content'=>'/about',
                                      ));
 
                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $main_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Контакты',
                                      'content'=>'/contact',
                                      ));


  // Администрирование
  $admin_menu_id = $menu->create(array('item_type' => 2,
                                       'parent_id' => 0,
                                       'active' => 1,
                                       'displayname' => 'Администрирование'
                                      ));

                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $admin_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Контент',
                                      'content'=>'/index.php?module=content',
                                      ));
				 $menu->create(array('item_type' => 3,
                                      'parent_id' => $admin_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Обр. связь',
                                      'content'=>'/index.php?module=contact',
                                      ));
									  
                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $admin_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Файлы',
                                      'content'=>'/index.php?module=fm&type=admin',
                                      ));

                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $admin_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Меню',
                                      'content'=>'/index.php?module=menu&type=admin',
                                      ));


					$menu->create(array('item_type' => 3,
                                      'parent_id' => $admin_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Конфиг',
                                      'content'=>'/index.php?module=SYS_config&type=admin',
                                     ));


                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $admin_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Права',
                                      'content'=>'/index.php?module=SYS_permissions&type=admin',
                                     ));
                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $admin_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Группы',
                                      'content'=>'/index.php?module=SYS_groups&type=admin',
                                     ));
                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $admin_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Пользователи',
                                      'content'=>'/index.php?module=SYS_users&type=admin',
                                     ));
                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $admin_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Модули',
                                      'content'=>'/index.php?module=SYS_modules&type=admin',
                                     ));
                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $admin_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Блоки',
                                      'content'=>'/index.php?module=SYS_blocks&type=admin',
                                     ));
                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $admin_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Темы',
                                      'content'=>'/index.php?module=SYS_themes&type=admin',
                                     ));


                  $menu->create(array('item_type' => 3,
                                      'parent_id' => $admin_menu_id,
                                      'active'=> 1,
                                      'displayname'=>'Выход',
                                      'content'=>'/index.php?module=SYS_users&func=logout',
                                     ));

  // Initialisation successful
  return true;
  }

function menu_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function menu_delete()
  {
  //Чистим кеш переменных
  sysVarDelCached('menu_tree');
  // Deletion successful
  return true;
  }

function menu_activate()
  {
  return true;
  }

function menu_deactivate()
  {
  return true;
  }

?>