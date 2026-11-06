<?php
/*
 * Главная страница
*/
function search_admin_main()
  {
  sysRedirect(sysModURL('search','user','main'));
  }

/*
 * Конфигурация
*/
function search_admin_modifyconfig()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'search::admin::modifyconfig';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Инициализируем шаблоны
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  sysLoadLang ($sysTpl, 'search');
  $sysTpl->caching = false;

  sysModClassLoad('search');
  $search = new search;

  //Список возможных вариантов сортировки
  $sort_orders_list = $search->plugin_sort_orders_list();
  $sysTpl->assign('sort_orders_list', $sort_orders_list);
  // Переменные модуля
  $sysTpl->assign('search_string_min', sysModGetVar('search','search_string_min'));
  $sysTpl->assign('use_livesearch', sysModGetVar('search','use_livesearch'));
  $sysTpl->assign('result_perpage_list', sysModGetVar('search','result_perpage_list'));
  $sysTpl->assign('result_perpage', sysModGetVar('search','result_perpage'));
  $sysTpl->assign('result_orderby', sysModGetVar('search','result_orderby'));
  $sysTpl->assign('result_order_asc', sysModGetVar('search','result_order_asc'));

  //BrowseIn
  $browsein = array();
  $browsein[]=array ('url'=>sysModURL('search','user','main'),
                     'displayname'=>'Поиск');
  $browsein[]=array ('url'=>sysModURL('search','admin','modifyconfig'),
                     'displayname'=>'Опции поиска');
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

/*
 * Конфигурация
*/
function search_admin_updateconfig()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'search::admin::updateconfig';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  list ($search_string_min, $use_livesearch, $result_perpage_list, $result_perpage,
                                 $result_orderby, $result_order_asc) =
      sysVarCleanFromInput('search_string_min', 'use_livesearch', 'result_perpage_list', 'result_perpage',
                                 'result_orderby', 'result_order_asc');

  // Сохраняем параметры
  sysModSetVar ('search','use_livesearch', trim($use_livesearch));
  sysModSetVar ('search','search_string_min', trim($search_string_min));
  sysModSetVar ('search','result_perpage_list', trim($result_perpage_list));
  sysModSetVar ('search','result_perpage', trim($result_perpage));
  sysModSetVar ('search','result_orderby', trim($result_orderby));
  sysModSetVar ('search','result_order_asc', trim($result_order_asc));

  //Возвращаем результат
  sysShowMessage(sysModURL ('search','admin','modifyconfig'), 'Изменения сохранены');
  }
?>