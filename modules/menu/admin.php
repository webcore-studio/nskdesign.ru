<?php
/*
 * Главная админская страница
*/
function menu_admin_main()
  {

	
  $sysObject = 'menu::admin::main';

	sys_log('Зашли в menu_admin_main', LOG_DEBUG, " + ", $sysObject, "menu");
	
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;
  
  
  
  sys_log('Проверили доступ', LOG_DEBUG, " + ", $vars, "menu");

  //Работа с sysTpl
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;
  
  sys_log('Прошли sysTpl', LOG_DEBUG, " + ", $vars, "menu");

  //Построили дерево разделов
  sysModClassLoad ('menu');
  $menu = new menu;
  $items_list = $menu->ItemsTreeBuild (0, 0, false);
  $sysTpl->assign('items_list', $items_list);

  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('menu','admin','main'),
                       'displayname'=>'Меню');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] = $sysObject;
  $result['content'] = $sysTpl->fetch($sysModTpl, $sysObject);
  $result['browsein'] = $browsein;
  return $result;
  }

/*
 * Создание нового элемента
*/
function menu_admin_new()
  {
  //Забираем переменные совхода
  $parent_id = sysVarCleanFromInput('id');
  if (!is_numeric ($parent_id))
    {
    $parent_id=0;
    }
  $sysObject = 'menu::admin::new::'.$parent_id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Тело модуля
  //Построили дерево
  sysModClassLoad ('menu');
  $menu = new menu;
  $items_list = $menu->ItemsTreeBuild (0, 0, false);
  $sysTpl->assign('items_list', $items_list);

  //Назначаем переменные
  $sysTpl->assign('parent_id', $parent_id);

  //BrowseIn
  $browsein = $parents_list;
  $browsein[] = array ('url'=>sysModURL('menu','admin','main'),
                       'displayname'=>'Меню');
  //Добавляем название операции
  $browsein[] = array ('url'=>sysModURL('menu','admin','item_new', array ('id'=>$parent_id)),
                       'displayname'=>'Добавление элемента');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] = $sysObject;
  $result['browsein'] = $browsein;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Создание новой папки
*/
function menu_admin_create()
  {
  //Забираем переменные со входа
  $items_table = sysDBGetTable('menu');
  $items_column = sysDBGetColumns($items_table);
  $item_indate = sysDBCleanFromInput ($items_column);
  //
  if (!is_numeric ($item_indate['parent_id']))
    {
    sysException ('menu_admin_create', BAD_PARAM, 'parent_id not numeric '.$item_indate['parent_id']);
    };
  $sysObject = 'menu::admin::create::'.$parent_id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Кто добавил этот элемент
  $item_indate['uid'] = sysSessionGetVar('user_id');
  //Опредиляем контент в зависимости от типа элемента
  $item_indate['content'] = sysVarGetFromInput('content'.$item_indate['item_type']);

  sysModClassLoad ('menu');
  $menu = new menu;
  $menu->create($item_indate);

  $sysTpl = new sysTpl;
  $sysTpl->clear_cache(null, 'menu_block::display');

  sysShowMessage(sysModURL ('menu','admin','main'), 'Элемент добавлен');
  }

/*
 * Редактирование элемента
*/
function menu_admin_modify()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('menu_admin_modify', BAD_PARAM, 'Id not numeric'.$id);
  $sysObject = 'menu::admin::modify::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Построили дерево
  sysModClassLoad ('menu');
  $menu = new menu;
  $items_list = $menu->ItemsTreeBuild (0, 0, false);
  $sysTpl->assign('items_list', $items_list);

  //Инфа из базы
  $item_dbdate = $menu->get_info($id);
  $sysTpl->assign($item_dbdate);


  //BrowseIn
  $browsein = array();
  $browsein[]=array ('url'=>sysModURL('menu','admin','main'),
                     'displayname'=>'Меню');
  $browsein[]=array ('url'=>sysModURL('menu','admin','modify', array ('id'=>$id)),
                     'displayname'=>'Редактирование элемента');
  $sysTpl->assign('browsein', $browsein);


  //Вывод результатов
  $result['object'] = $sysObject;
  $result['browsein'] = $browsein;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Внесение изменений в элемент
*/
function menu_admin_update()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('menu_admin_update', BAD_PARAM, 'Id not numeric'.$id);
  $sysObject = 'menu::admin::update::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Забираем переменные со входа
  $items_table = sysDBGetTable('menu');
  $items_column = sysDBGetColumns($items_table);
  $item_indate = sysDBCleanFromInput ($items_column);

  //Опредиляем контент в зависимости от типа элемента
  $item_indate['content'] = sysVarGetFromInput('content'.$item_indate['item_type']);

  //Информация о элементе из базы
  sysModClassLoad ('menu');
  $menu = new menu;
  $item_dbdate = $menu->get_info($id);

  //Проверки
  //1 Радителя низя вкладывать в ребенка и ребнка ребенка и т.д.
  //2 Низя вкладывать самого в себя
  if ($item_dbdate['parent_id']<>$item_indate['parent_id'])
    {
    //Строим массив перентов директории вкаторую нас перемещают
    $item_parents_list = $menu->ItemParentsList($item_indate['parent_id']);
    foreach ($item_parents_list as $key=>$item)
      {
      if ($item['id']==$item_indate['id'])
        {
        sysShowMessage(sysModURL ('menu','admin','main'), 'Нельзя вложить родительский элемент в дочерний !!!');
        }
      }
    }

  $menu->update($item_indate);

  $sysTpl = new sysTpl;
  $sysTpl->clear_cache(null, 'menu_block::display');

  sysShowMessage(sysModURL ('menu','admin','main'), 'Элемент изменен');
  }

/*
 * Подтверждение удаления элемента
*/
function menu_admin_confirm_delete()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('menu_admin_confirm_delete', BAD_PARAM, 'Id not numeric'.$id);
  $sysObject = 'menu::admin::confirm_delete::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;
  //Тело модуля

  //Инфа из базы
  sysModClassLoad ('menu');
  $menu = new menu;
  $item_dbdate = $menu->get_info($id);
  $sysTpl->assign($item_dbdate);


  //BrowseIn
  $browsein = array();
  $browsein[]=array ('url'=>sysModURL('menu','admin','main'),
                     'displayname'=>'Меню');
  $browsein[]=array ('url'=>sysModURL('menu','admin','confirm_delete', array ('id'=>$id)),
                     'displayname'=>'Удаление элемента');
  $sysTpl->assign('browsein', $browsein);
  //Вывод результатов
  $result['object'] = $sysObject;
  $result['browsein'] = $browsein;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Удаление папки
*/
function menu_admin_delete()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('menu_admin_delete', BAD_PARAM, 'Id not numeric'.$id);
  $sysObject = 'menu::admin::delete::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Вызвали API функцию для удаления
  sysModClassLoad ('menu');
  $menu = new menu;
  $menu->delete($id);

  $sysTpl = new sysTpl;
  $sysTpl->clear_cache(null, 'menu_block::display');

  sysShowMessage(sysModURL ('menu','admin','main'), 'Элемент удален');
  }

/*
 * Увеличение вес элемента
*/
function menu_admin_moveup ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('menu_admin_moveup', BAD_PARAM, 'Id not numeric'.$id);
  $sysObject = 'menu::admin::moveup::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad ('menu');
  $menu = new menu;
  $menu->moveup($id);

  $sysTpl = new sysTpl;
  $sysTpl->clear_cache(null, 'menu_block::display');

  sysRedirect (sysModURL ('menu','admin','main'));
  }

/*
 * Уменьшыли веса элемента
*/
function menu_admin_movedown ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('menu_admin_movedown', BAD_PARAM, 'Id not numeric'.$id);

  $sysObject = 'menu::admin::movedown::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad ('menu');
  $menu = new menu;
  $menu->movedown($id);

  $sysTpl = new sysTpl;
  $sysTpl->clear_cache(null, 'menu_block::display');

  sysRedirect (sysModURL ('menu','admin','main'));
  }

?>