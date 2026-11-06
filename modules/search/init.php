<?php

function search_init()
  {
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes();

  //Регистрируем темплеи в системе

  //Ajax часть
  $SYS_themes->tpl_add (array('tpl_compname'=>'search',
                              'tpl_name'=>'ajax_view.tpl',
                              'tpl_pattern'=>'^search::ajax::view',
                              'tpl_description'=>'Результаты поиска'));

  //Пользовательская часть
  $SYS_themes->tpl_add (array('tpl_compname'=>'search',
                              'tpl_name'=>'user_main.tpl',
                              'tpl_pattern'=>'^search::user::main',
                              'tpl_description'=>'Список текущих опросов'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'search',
                              'tpl_name'=>'user_view.tpl',
                              'tpl_pattern'=>'^search::user::view',
                              'tpl_description'=>'Результаты поиска'));

  //Административная
  $SYS_themes->tpl_add (array('tpl_compname'=>'search',
                              'tpl_name'=>'admin_modifyconfig.tpl',
                              'tpl_pattern'=>'^search::admin::modifyconfig$',
                              'tpl_description'=>'Конфигурация'));

  //Задаем переменные модуля по умолчанию
  sysModSetVar ('search','search_string_min', '3');
  sysModSetVar ('search','use_livesearch', '1');
  sysModSetVar ('search','result_perpage_list', '10,20,30,50');
  sysModSetVar ('search','result_perpage', '20');
  sysModSetVar ('search','result_orderby', 'score');
  sysModSetVar ('search','result_order_asc', '0');

  // Initialisation successful
  return true;
  }

function search_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function search_delete()
  {
  // Deletion successful
  return true;
  }

function search_activate()
  {
  return true;
  }

function search_deactivate()
  {
  return true;
  }

?>