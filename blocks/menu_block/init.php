<?php

function menu_block_add($blockinfo)
{
  //Добовляем шаблон, если нету еше
  sysModClassLoad('SYS_themes');
  $SYS_themes = new SYS_themes;

  $sysObject = 'menu_block::display';
  $sysModTpl = sysTplWay($sysObject);
  if (empty($sysModTpl)) {

    $SYS_themes->tpl_add(array(
      'tpl_compname' => 'menu_block',
      'tpl_name' => 'block_display.tpl',
      'tpl_pattern' => $sysObject,
      'tpl_description' => 'Вывод содержания блока'
    ));
  }
  ;




  $sysObject = 'menu_block::modify';
  $sysModTpl = sysTplWay($sysObject);
  if (empty($sysModTpl)) {
    $SYS_themes->tpl_add(array(
      'tpl_compname' => 'menu_block',
      'tpl_name' => 'block_modify.tpl',
      'tpl_pattern' => $sysObject,
      'tpl_description' => 'Редактирование блока'
    ));
  }
  ;

  return true;
}

function menu_block_delete($blockinfo)
{
  $sysTpl = new sysTpl;
  $sysTpl->clear_cache(null, "menu_block::display::{$blockinfo['id']}");
  return true;
}

function menu_block_activate($blockinfo)
{
  return true;
}

function menu_block_deactivate($blockinfo)
{
  $sysTpl = new sysTpl;
  $sysTpl->clear_cache(null, "menu_block::display::{$blockinfo['id']}");
  return true;
}

function menu_block_modify($blockinfo)
{
  //Прелюдие как у всех модулей
  $sysObject = 'menu_block::modify';
  $sysModTpl = sysTplWay($sysObject);
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  //Тип меню
  $menu_type = sysBlockGetVar($blockinfo['id'], 'menu_type');
  $sysTpl->assign('menu_type', $menu_type);

  //Типы меню
  $menutypes_list = array(1 => 'Всегда развернуто', 2 => 'Разворачиваюшееся');
  $sysTpl->assign('menutypes_list', $menutypes_list);

  //Список шаблонов
  $templates_list = array();
  $templates_list['vertical_default'] = 'Вертикальное';
  $templates_list['horizontal_default'] = 'Горизонтальное';
  $templates_list['plus_1'] = 'Дополнительное 1';
  $templates_list['plus_2'] = 'Дополнительное 2';
  $sysTpl->assign('templates_list', $templates_list);

  //Текущий шаблон
  $template = sysBlockGetVar($blockinfo['id'], 'template');
  $sysTpl->assign('template', $template);

  //Построили дерево разделов
  sysModClassLoad('menu', 'user');
  $menu = new menu;
  $items_list = $menu->ItemsTreeBuild(0, 0);
  $sysTpl->assign('items_list', $items_list);

  //Текущий элемент
  $parent_id = sysBlockGetVar($blockinfo['id'], 'parent_id');
  $sysTpl->assign('parent_id', $parent_id);

  $result = $sysTpl->fetch($sysModTpl, $sysObject);
  return $result;
}

function menu_block_update($blockinfo)
{
  //Забираем переменные совхода
  $parent_id = sysVarCleanFromInput('parent_id');
  $menu_type = sysVarCleanFromInput('menu_type');
  $template = sysVarCleanFromInput('template');
  sysBlockSetVar($blockinfo['id'], 'parent_id', $parent_id);
  sysBlockSetVar($blockinfo['id'], 'menu_type', $menu_type);
  sysBlockSetVar($blockinfo['id'], 'template', $template);

  //Чистим кеш
  $sysTpl = new sysTpl;
  $sysTpl->clear_cache(null, "menu_block::display::{$blockinfo['id']}");
}

?>