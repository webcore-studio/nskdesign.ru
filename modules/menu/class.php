<?php

class menu
  {
  /*
   * Переменные
  */

  /*
   * Функции
  */

  /*
   * Возвращает масив дерева папак в виде придатном для отображения
   * Вызывает себя рекурсивно
   * parent_id
   * start_level
   * only_active
   *
  */
  function ItemsTreeBuild($parent_id, $start_level=0, $only_active=false)
    {
    //Кешируем дерево целиком
    //проверяем если есть в кеше возвращаем из кеша
  if (sysVarIsCached('menu_tree', "$parent_id::$start_level::$only_active"))
     {
     return (sysVarGetCached('menu_tree', "$parent_id::$start_level::$only_active"));
     };





$menu_table = sysDBGetTable('menu');
    $menu_column =  sysDBGetColumns($menu_table);
    if ($only_active)
      {
      $menu_dbdate = sysDbSelect ($menu_table, $menu_column,
        "where $menu_column[parent_id] = '$parent_id' AND $menu_column[active]=1 ORDER BY $menu_column[weight]", true);
      }
      else
        {
        $menu_dbdate = sysDbSelect ($menu_table, $menu_column,
          "where $menu_column[parent_id] = '$parent_id' ORDER BY $menu_column[weight]", true);
        };
    $result = array();

    if (isset ($menu_dbdate))
      {
      //А туть мы чешим репу как веса двигать
      /* ??? Бред, если глюки не вылезут - удалить
      if ($only_active)
        {
        $MaxWeight=sysDbMaxWeight ($menu_table, "WHERE $menu_column[parent_id]=$parent_id AND $menu_column[active]=1");
        $MaxWeight = count($menu_dbdate);
        }
        else
          {
          $MaxWeight=sysDbMaxWeight ($menu_table, "WHERE $menu_column[parent_id]=$parent_id");
          };
      */
      $MaxWeight = count($menu_dbdate);

      foreach ($menu_dbdate as $key=>$menu)
        {
        if ($MaxWeight>1)
          {
          if ($menu['weight']>1)
            {
            $menu['weight_moveup_url'] = sysModUrl ('menu','admin','moveup', array ('id'=>$menu['id']));
            };

          if ($menu['weight']<$MaxWeight)
            {
            $menu['weight_movedown_url'] = sysModUrl ('menu','admin','movedown', array ('id'=>$menu['id']));
            };
          $menu_dbdate[$key] = $menu;
          }
        };

      //Формируем результат и вызываем себя рекурсивно
      foreach ($menu_dbdate as $menu)
        {
        //добавляем в результирующий масив
        //пробегаем по найденным документам вызывая себя рекурсивно
        $menu['level'] = $start_level;
        $menu['parentfolder_orderby'] = $subfolder_orderby;
        array_push ($result, $menu);

        //Если у документа есть вложенные документы вызываем себя рекурсивно
       // if ($menu['subitem_counter']>0)
       //   {
          $recresult = $this->ItemsTreeBuild($menu['id'], ($start_level+1), $only_active);
          if (!empty ($recresult))
            {
            $result = array_merge ($result, $recresult);
            }
        //  }
        }
      }

    sysVarSetCached('menu_tree', "$parent_id::$start_level::$only_active", $result);
    return $result;
    }

  /*
   * @desc Удаляет элемент
   * @return bool
   * @param id integer
  */
  function delete($id)
    {
      global $dbconn;
    sysExtLibLoad();
    //Получаем список вложенных папок
    $menu_list = $this->ItemsTreeBuild($id);
    //Добавляем текущую папку
    $menu_list[]=array ('id'=>$id);

    $menu_table = sysDBGetTable('menu');
    $menu_column = sysDBGetColumns($menu_table);
    //Инфа о текущей папке
    $menu_dbdate = sysDbSelect ($menu_table, $menu_column, "WHERE $menu_column[id] = '$id'");
    //Уменьшение счетчик родительского раздела если раздел активен
    if ($menu_dbdate['active'])
      {
      $query = "UPDATE $menu_table SET subitem_counter = subitem_counter-1
            WHERE id = ".sysVarPrepForStore($menu_dbdate['parent_id']);

      $dbconn->query($query);
      if (mysqli_connect_errno()!=0) sysException ('menu->delete', DATABASE_ERROR, mysqli_connect_error()."<br> $query");
      };
    //Убиваем все что движеться
    foreach ($menu_list as $menu)
      {
      sysDbDelete ($menu_table, "WHERE $menu_column[id] = '".$menu['id']."'");
      };
    //Пересчитываем веса
    sysDbWeightDelete ($menu_table, $menu_dbdate['weight'], "WHERE $menu_column[parent_id] = '".$menu_dbdate['parent_id']."'");

    //Чистим кеш переменных и модуля
    sysVarDelCached('menu_tree');
    return true;
    }

  /*
   * Возвращает масив родителей Элимента
   * id
  */
  function ItemParentsList($id)
    {

      global $dbconn;

    $parents_list = array();
    if (is_numeric($id))
      {
      $items_table = sysDBGetTable('menu');
      $items_column =  sysDBGetColumns($items_table);
      //Делаем выборку
      $sql = "SELECT $items_column[id], $items_column[displayname], $items_column[parent_id]
                FROM $items_table WHERE $items_column[id] = '$id'";
      $sql_result = $dbconn->query($sql);
      if (mysqli_connect_errno()!=0) sysException ('menu->ItemParentsList', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
      list($id, $displayname, $parent_id) = mysqli_fetch_row($sql_result);
      mysqli_free_result($sql_result);
      //Нашли первыэ элимент
      $parent_info = array();
      $parent_info['id'] = $id;
      $parent_info['displayname'] = $displayname;
      $parents_list[] = $parent_info;
      while ($parent_id!=0)
        {
        //Делаем выборку
        $sql = "SELECT $items_column[id], $items_column[displayname], $items_column[parent_id]
                  FROM $items_table WHERE $items_column[id] = '$parent_id'";
        $sql_result = $dbconn->query($sql);
        if (mysqli_connect_errno()!=0) sysException ('menu->ItemParentsList', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

        list($id, $displayname, $parent_id) = mysqli_fetch_row($sql_result);
        mysqli_free_result($sql_result);
        //Нашли пркдведущий элимент
        $parent_info = array();
        $parent_info['id']=$id;
        $parent_info['displayname'] = $displayname;
        $parents_list[] = $parent_info;
        }
      }

    return array_reverse ($parents_list);
    }

  /*
   * Добавление элимента
   * item_indate
  */
  function create ($item_indate)
    {
      global $dbconn;

    $items_table = sysDBGetTable('menu');
    $items_column = sysDBGetColumns($items_table);

    $MaxWeight = sysDbMaxWeight ($items_table, "WHERE $items_column[parent_id] = '".$item_indate['parent_id']."'");
    $MaxWeight++;
    $item_indate['weight']=$MaxWeight;

    //Вставили в базу
    sysDbInsert ($items_table, $item_indate);
    $insert_id = $dbconn->insert_id;

    //Увеличили счетчик itemov если item активен
    if ($item_indate['active'])
      {
      $query = "UPDATE $items_table SET subitem_counter = subitem_counter+1
          WHERE id=".sysVarPrepForStore($item_indate['parent_id']);
      $dbconn->query($query);
      if (mysqli_connect_errno()!=0) sysException ('menu->create', DATABASE_ERROR, mysqli_connect_error()."<br> $query");
      };

    //Чистим кеш переменных и модуля
    sysVarDelCached('menu_tree');
    return $insert_id;
    }

  /**
   * @desc Информация о элементе
   * @return array
   * @param id integer
  */
  function get_info ($id)
    {
    $items_table = sysDBGetTable('menu');
    $items_column = sysDBGetColumns($items_table);
    $item_dbdate = sysDbSelect ($items_table, $items_column, "WHERE $items_column[id]= '$id'");
    //Опредиляем контент в зависимости от типа элемента
    $item_dbdate['content'.$item_dbdate['item_type']] = $item_dbdate['content'];
    unset ($item_dbdate['content']);
    return $item_dbdate;
    }

  /*
   * Обновление элимента
   * item_indate
  */
  function update ($item_indate)
    {
      global $dbconn;
    $items_table = sysDBGetTable('menu');
    $items_column = sysDBGetColumns($items_table);
    $item_dbdate = $this->get_info($item_indate['id']);

    //Если parent_id изменился пересчитываем веса
    if ($item_dbdate['parent_id']<>$item_indate['parent_id'])
      {
      //Проверки
      //1 Радителя низя вкладывать в ребенка и ребнка ребенка и т.д.
      //2 Низя вкладывать самого в себя

      //Строим массив перентов директории в каторую нас перемещают
      $item_parents_list = $this->ItemParentsList($item_indate['parent_id']);
      $prezent = false;
      foreach ($item_parents_list as $key=>$item)
        {
        if ($item['id']==$item_indate['id'])
          {
          $prezent = true;
          break;
          }
        }

      if (!$prezent)
        {
        sysExtLibLoad();
        //Удалили старый вес
        sysDbWeightDelete ($items_table, $item_dbdate['weight'],
            "WHERE $items_column[parent_id] = '".$item_dbdate['parent_id']."' AND $items_column[id]<>'".$item_indate['id']."'");
        //Находим и задаем самый большой вес
        $MaxWeight = sysDbMaxWeight ($items_table, "WHERE $items_column[parent_id] = '".$item_indate['parent_id']."'");
        $MaxWeight++;
        $item_indate ['weight']=$MaxWeight;
        }
        else
          {
          $item_indate['parent_id'] = $item_dbdate['parent_id'];
          return false;
          };
      // Меняем счетчик вложенных документов
      //Увеличили счетчик itemov если item активен
      if ($item_indate['active'])
        {
        $query = "UPDATE $items_table SET subitem_counter = subitem_counter+1
            WHERE id=".sysVarPrepForStore($item_indate['parent_id']);
        $dbconn->query($query);
        if (mysqli_connect_errno()!=0) sysException ('menu->create', DATABASE_ERROR, mysqli_connect_error()."<br> $query");
        };
      //Уменьшили счетчик itemov если item был активен
      if ($item_dbdate['active'])
        {
        $query = "UPDATE $items_table SET subitem_counter = subitem_counter-1
            WHERE id=".sysVarPrepForStore($item_dbdate['parent_id']);
        $dbconn->query($query);
        if (mysqli_connect_errno()!=0) sysException ('menu->create', DATABASE_ERROR, mysqli_connect_error()."<br> $query");
        };
      }
      else
        {//Если предок не поменялся, смотрим не измениласьли активность
        //Увеличили счетчик itemov если item стал активен
        if ($item_indate['active'] && !$item_dbdate['active'])
          {
          $query = "UPDATE $items_table SET subitem_counter = subitem_counter+1
              WHERE id=".sysVarPrepForStore($item_indate['parent_id']);
          $dbconn->query($query);
          if (mysqli_connect_errno()!=0) sysException ('menu->create', DATABASE_ERROR, mysqli_connect_error()."<br> $query");
          }
        //Уменьшишли, если элимент деактивировали
        if (!$item_indate['active'] && $item_dbdate['active'])
          {
          $query = "UPDATE $items_table SET subitem_counter = subitem_counter-1
              WHERE id=".sysVarPrepForStore($item_indate['parent_id']);
          $dbconn->query($query);
          if (mysqli_connect_errno()!=0) sysException ('menu->create', DATABASE_ERROR, mysqli_connect_error()."<br> $query");
          }
        };

    //Вставили в базу
    sysDbUpdate ($items_table, $item_indate, "WHERE $items_column[id] = '".$item_indate['id']."'");
    //Чистим кеш переменных и модуля
    sysVarDelCached('menu_tree');
    return true;
    }

  /**
   * @desc Увеличение веса элемента
   * @return bool
   * @param id integer
  */
  function moveup($id)
    {
    sysExtLibLoad();
    //Тело модуля
    $items_table = sysDBGetTable('menu');
    $items_column = sysDBGetColumns($items_table);
    //Оредиляем старый вес
    $item_dbdate = sysDbSelect ($items_table, $items_column, "WHERE $items_column[id] = '$id'");
    //Подвинули вверх
    sysDbWeightMoveUp ($items_table, $item_dbdate['weight'], "WHERE $items_column[parent_id] = '".$item_dbdate['parent_id']."'");
    //Чистим кеш переменных и модуля
    sysVarDelCached('menu_tree');
    return true;
    }

  /**
   * @desc Уменьшыли веса элемента
   * @return bool
   * @param id integer
  */
  function movedown($id)
    {
    sysExtLibLoad();
    $items_table = sysDBGetTable('menu');
    $items_column = sysDBGetColumns($items_table);
    //Оредиляем старый вес
    $item_dbdate = sysDbSelect ($items_table, $items_column, "WHERE $items_column[id] = '$id'");
    //Подвинули вниз
    sysDbWeightMoveDown ($items_table, $item_dbdate['weight'], "WHERE $items_column[parent_id] = '".$item_dbdate['parent_id']."'");
    //Чистим кеш переменных и модуля
    sysVarDelCached('menu_tree');
    return true;
    }
 

  }

?>