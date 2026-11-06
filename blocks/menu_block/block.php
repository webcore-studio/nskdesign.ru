<?php
function menu_block_display($blockinfo)
{
  sys_log('menu_block_display', LOG_DEBUG, "blockinfo", $blockinfo, "menu_block");

  if (!sysModAvailable('menu')) {//Модуль недоступен
    return true;
  }
  ;


  //Переменные блока
  $parent_id = (int) sysBlockGetVar($blockinfo['id'], 'parent_id');
  $menu_type = sysBlockGetVar($blockinfo['id'], 'menu_type');
  $sysTpl = new sysTpl;
  $sysTpl->cache_lifetime = -1;
  $template = sysBlockGetVar($blockinfo['id'], 'template');

  //Построили дерево разделов
  //проверяем если есть в кеше берем из кеша
/* if (sysVarIsCached('menu_tree', "$parent_id::-1::".true))
      {
      $items_list = sysVarGetCached('menu_tree', "$parent_id::-1::".true);
      }
      else 
        {*/

  sys_log('menu_block_display', LOG_DEBUG, "parent_id", $parent_id, "menu_block");

  sysModClassLoad('menu');
  $menu = new menu;
  $items_list = $menu->ItemsTreeBuild($parent_id, -1, true);

  sys_log('menu_block_display', LOG_DEBUG, "items_list", $items_list, "menu_block");

  //Если пусто, значит блок еше не настроен
  if (empty($items_list))
    return true;

  // Определяем id текушего элемента
  global $HTTP_SERVER_VARS;
  $cur_uri = $HTTP_SERVER_VARS['REQUEST_URI'];
  //  $cur_uri = ltrim($uri, $BaseURI);
  $cur_item_id = 0;

  //объект - страница, на которую выводим менюшку
  $parent_tree = explode('::', $blockinfo['module_object']);

  /*
  foreach ($items_list as $key=>$item)
  {

  if ($item['item_type']==3)
  {

  if ($cur_uri==$item['content'])
  {
  $cur_item_id = $item['id'];
  break;
  }

  $str=$item['content'];
  parse_str($str,$out);
  $key=array_search($out['id'],$parent_tree);

  if (($key) and ($out['/index_php?module']==$parent_tree[0]))
  {
  //$cur_item_ids=array_slice($parent_tree,$key);
  $cur_item_id = $item['id'];
  break;
  }

  }
  }
  */
  $cur_item_ids = array();
  foreach ($items_list as $key => $item) {
    //если нет маски, проверяем только прямое совпадение урла
    if (!$item['pattern']) {
      if ($cur_uri == $item['content']) {
        $cur_item_id = $item['id'];
        $cur_item_ids[] = $item['id'];
        $items_list[$key]['selected'] = 1;
      }
    } else
    //проверяем по маске
    {
      $pattern = "/" . $item['pattern'] . "/iU";
      if (preg_match($pattern, $blockinfo['module_object'])) {
        $cur_item_id = $item['id'];
        $cur_item_ids[] = $item['id'];
        $items_list[$key]['selected'] = 1;
      }

    }
  }

  //Рассматриваем 2 случая тип меню 1 или 2

  //Тип 1 - менюшка быстренькая
  if ($menu_type == 1) {
    $sysObject = 'menu_block::display::' . $blockinfo['id'] . '::' . $cur_item_id;

    //Проверка на доступ
    if (!sysSecAuthAction($sysObject, ACCESS_READ))
      return true;

    $sysModTpl = sysTplWay($sysObject, $blockinfo['block_tpl']);

    /*    if ($sysTpl->is_cached ($sysModTpl, $sysObject))
          {
          //Возвращаем
          $result['block_object'] = $sysObject;
          $result['block_content'] = $sysTpl->fetch($sysModTpl,$sysObject);
          return $result;
          };
    */
  }

  //Меню разварачиваеться на расскрытой странице (Тормазнутое)
  if ($menu_type == 2) {
    // Какой элемент развернут (unwrap)
    $unwrap_item_id = 0;
    foreach ($items_list as $item) {
      if (!empty($item['item_pattern']) && preg_match("/" . $item['item_pattern'] . "/iU", $blockinfo['module_object'])) {
        $unwrap_item_id = $item['id'];
        break;
      }
    }
    ;
    //Если под маску ниче не попало, пробуем может хоть по урлу чет подходит
    if ($unwrap_item_id == 0 && $cur_item_id > 0)
      $unwrap_item_id = $cur_item_id;

    //Имя :))
    $sysObject = 'menu_block::display::' . $blockinfo['id'] . '::' . $cur_item_id . '::' . $unwrap_item_id;

    //Проверка на доступ
    if (!sysSecAuthAction($sysObject, ACCESS_READ))
      return true;

    $sysModTpl = sysTplWay($sysObject, $blockinfo['block_tpl']);
    if ($sysTpl->is_cached($sysModTpl, $sysObject)) {
      //Возвращаем
      $result['block_object'] = $sysObject;
      $result['block_content'] = $sysTpl->fetch($sysModTpl, $sysObject);

      sys_log('menu_block_display', LOG_DEBUG, "result из кеша", $result, "menu_block");

      return $result;
    }
    ;

    //Дыбы не делать лишних калсов к бд извращаемся
    //Находим всех предков
    $parents_list = array();
    $parents_list[$parent_id] = true;

    //А естли смысл продолжать дальше?
    if ($unwrap_item_id > 0) {
      while ($unwrap_item_id != $parent_id) {
        //опредиляем какой у parent_item_id parent_item_id
        foreach ($items_list as $item) {
          if ($item['id'] == $unwrap_item_id) {
            $unwrap_item_id = $item['parent_id'];
            $parents_list[$item['id']] = true;
            break;
          }
        }
      }
    }

    //3 Удаляем все документы предки которых неявляються предками нашего документа или корневого
    foreach ($items_list as $key => $item) {
      if ($parents_list[$item['parent_id']] != true) {
        unset($items_list[$key]);
      }
      ;
    }
  }

  //HTML 4.01 У нас урл а не хтмл, посему преобразовываем
  foreach ($items_list as $key => $value) {
    if ($value['item_type'] == 3) {
      $items_list[$key]['content'] = str_replace('&', '&amp;', $value['content']);
    }
  }


  //перестраиваем дерево меню с учетом вложенности
  $par = $items_list[0]['parent_id'];
  foreach ($items_list as $key => $value)
    if ($value['parent_id'] != $par) {
      $parent = $value['parent_id'];
      foreach ($items_list as $k => $v)
        if ($v['id'] == $parent) {
          $kid = $k;
          break;
        }
      $items_list[$kid]['inner'][] = $value;
      unset($items_list[$key]);
    }


  if (!isset($list))
    $list = array();
  if (!isset($out))
    $out = array();

  sys_log('menu_block_display', LOG_DEBUG, "blockinfo 2", $blockinfo, "menu_block");

  sys_log('menu_block_display', LOG_DEBUG, "sysObject", $sysObject, "menu_block");

  sys_log('menu_block_display', LOG_DEBUG, "sysModTpl", $sysModTpl, "menu_block");

  $sysTpl->assign($blockinfo);
  $sysTpl->assign('unwrap_item_id', $unwrap_item_id ?? 0);
  $sysTpl->assign('cur_item_id', $cur_item_id ?? 0);
  $sysTpl->assign('cur_item_ids', $cur_item_ids ?? array());
  $sysTpl->assign('items_list', $items_list ?? array());
  $sysTpl->assign('template', $template ?? '');
  $sysTpl->assign('list', $list);

  $sysTpl->assign('out', $out);
  $sysTpl->assign('parent', $parent_tree[0] ?? '');
  $result['block_content'] = $sysTpl->fetch($sysModTpl, $sysObject);
  return $result;
}

?>