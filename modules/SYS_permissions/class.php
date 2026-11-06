<?php

class SYS_permissions
  {
  /*
   * Переменные
  */



  /*
   * Функции
  */

  //////////////////////////////////////////////////////////////////////////////
  /*
   * Информация о правах групп
  */
  function groups_perms_get_list()
    {
    $groups_perms_table = sysDBGetTable('groups_perms');
    $groups_perms_column = sysDBGetColumns($groups_perms_table);
    $dbpermslist = sysDbSelect ($groups_perms_table, $groups_perms_column, "ORDER BY $groups_perms_column[weight]", true);
    return ($dbpermslist);
    }

  /*
   * Информация о правах группы
  */
  function groups_perms_get_info($id)
    {
    $groups_perms_table = sysDBGetTable('groups_perms');
    $groups_perms_column = sysDBGetColumns($groups_perms_table);
    $info = sysDbSelect ($groups_perms_table, $groups_perms_column, "WHERE $groups_perms_column[id]='$id'");
    return ($info);
    }

  /*
   * Возврашает ассоциативный массив уровень прав->  Человеческое название
  */
  function permsLevelList()
    {
    $levels_list['ACCESS_NONE']='NONE';
    $levels_list['ACCESS_OVERVIEW']='OVERVIEW';
    $levels_list['ACCESS_READ']='READ';
    $levels_list['ACCESS_COMMENT']='COMMENT';
    $levels_list['ACCESS_ADD']='ADD';
    $levels_list['ACCESS_EDIT']='EDIT';
    $levels_list['ACCESS_DELETE']='DELETE';
    $levels_list['ACCESS_ADMIN']='ADMIN';
    return ($levels_list);
    }

  /*
  * Создает права группы
  */
  function groups_perms_create($args)
    {
    //Делаем вставку информаии в базу
    $groups_perms_table = sysDBGetTable('groups_perms');
    $groups_perms_column = sysDBGetColumns($groups_perms_table);

    //Находим и задаем самый большой вес
    $MaxWeight = sysDbMaxWeight ($groups_perms_table);
    $MaxWeight++;
    $args ['weight'] = $MaxWeight;

    sysDbInsert ($groups_perms_table, $args);
    //Очищаем кеш прав доступа
    sysVarDelCached('sec_levels', 'groups_perms');
    return (mysql_insert_id());
    }

  /*
   * Внесение изменений в базу данных
  */
  function groups_perms_update($args)
    {
    $groups_perms_table = sysDBGetTable('groups_perms');
    $groups_perms_column = sysDBGetColumns($groups_perms_table);
    //Добавляем элимент в базу.
    unset ($args ['weight']);//Подстраховка
    sysDbUpdate ($groups_perms_table, $args, "WHERE $groups_perms_column[id]='$args[id]'");
    //Очищаем кеш прав доступа группы
    sysVarDelCached('sec_levels', 'groups_perms');
    return true;
    }

  /*
   * Удаление праила группв
  */
  function groups_perms_delete($id)
    {
    sysExtLibLoad();
    $dbdata = $this->groups_perms_get_info ($id);
    //Удаление
    $groups_perms_table = sysDBGetTable('groups_perms');
    $groups_perms_column = sysDBGetColumns($groups_perms_table);
    sysDbDelete ($groups_perms_table, "WHERE $groups_perms_column[id]='$id'");
    sysDbWeightDelete ($groups_perms_table, $dbdata['weight'],'');
    //Очищаем кеш прав доступа группы
    sysVarDelCached('sec_levels', 'groups_perms');
    return true;
    }

  /*
   * Увеличение вес правила группы
  */
  function groups_perms_moveup($id)
    {
    sysExtLibLoad();
    $groups_perms_table = sysDBGetTable('groups_perms');
    $groups_perms_column = sysDBGetColumns($groups_perms_table);
    $dbdata = $this->groups_perms_get_info ($id);

    sysDbWeightMoveUp ($groups_perms_table, $dbdata['weight']);

    sysVarDelCached('sec_levels', 'groups_perms');
    return true;
    }

  /*
   * Уменьшыли вес правила группы
  */
  function groups_perms_movedown($id)
    {
    sysExtLibLoad();
    $groups_perms_table = sysDBGetTable('groups_perms');
    $groups_perms_column = sysDBGetColumns($groups_perms_table);
    $dbdata = $this->groups_perms_get_info ($id);

    sysDbWeightMoveDown ($groups_perms_table, $dbdata['weight']);

    sysVarDelCached('sec_levels', 'groups_perms');
    return true;
    }

  //////////////////////////////////////////////////////////////////////////////

  /*
   * Информация о правах пользователей
  */
  function users_perms_get_list()
    {
    $users_perms_table = sysDBGetTable('users_perms');
    $users_perms_column = sysDBGetColumns($users_perms_table);
    $dbpermslist = sysDbSelect ($users_perms_table, $users_perms_column, "ORDER BY $users_perms_column[weight]", true);
    return ($dbpermslist);
    }

  /*
   * Информация о правах пользователей
  */
  function users_perms_get_info($id)
    {
    $users_perms_table = sysDBGetTable('users_perms');
    $users_perms_column = sysDBGetColumns($users_perms_table);
    $info = sysDbSelect ($users_perms_table, $users_perms_column, "WHERE $users_perms_column[id]='$id'");
    return ($info);
    }

  /*
   * Создаем правило безопасности пользователей
  */
  function users_perms_create($args)
    {
    sysExtLibLoad();
    $users_perms_table = sysDBGetTable('users_perms');
    $users_perms_column = sysDBGetColumns($users_perms_table);
    //Находим и задаем самый большой вес
    $MaxWeight = sysDbMaxWeight ($users_perms_table);
    $MaxWeight++;
    $args['weight'] = $MaxWeight;
    //Добавляем элимент в базу.
    sysDbInsert ($users_perms_table, $args);
    //Удаляем из кеша
    sysVarDelCached('sec_levels', 'users_perms');
    return (mysql_insert_id());
    }

  /*
   * Обновляет правило безопасности пользователей
  */
  function users_perms_update($args)
    {
    $users_perms_table = sysDBGetTable('users_perms');
    $users_perms_column = sysDBGetColumns($users_perms_table);
    //Добавляем элимент в базу.
    unset ($args ['weight']);//Подстраховка
    sysDbUpdate ($users_perms_table, $args, "WHERE $users_perms_column[id] = '$args[id]'");
    //Удаляем из кеша
    sysVarDelCached('sec_levels', 'users_perms');
    return true;
    }

  /*
   * Обновляет правило безопасности пользователей
  */
  function users_perms_delete($id)
    {
    sysExtLibLoad();
    $users_perms_table = sysDBGetTable('users_perms');
    $users_perms_column = sysDBGetColumns($users_perms_table);
    $dbdata = $this->users_perms_get_info ($id);
    sysDbDelete ($users_perms_table, "WHERE $users_perms_column[id]='$id'");
    sysDbWeightDelete ($users_perms_table, $dbdata['weight']);
    //Удаляем из кеша
    sysVarDelCached('sec_levels', 'users_perms');
    return true;
    }

  /*
   * Увеличение вес правила пользователя
  */
  function users_perms_moveup($id)
    {
    sysExtLibLoad();
    $users_perms_table = sysDBGetTable('users_perms');
    $users_perms_column = sysDBGetColumns($users_perms_table);
    $dbdata = $this->users_perms_get_info ($id);
    sysDbWeightMoveUp ($users_perms_table, $dbdata['weight']);
    sysVarDelCached('sec_levels', 'users_perms');
    return true;
    }

  /*
   * Уменьшыли вес правила пользователя
  */
  function users_perms_movedown($id)
    {
    sysExtLibLoad();
    $users_perms_table = sysDBGetTable('users_perms');
    $users_perms_column = sysDBGetColumns($users_perms_table);
    $dbdata = $this->users_perms_get_info ($id);
    sysDbWeightMoveDown ($users_perms_table, $dbdata['weight']);
    sysVarDelCached('sec_levels', 'users_perms');
    return true;
    }

  //////////////////////////////////////////////////////////////////////////////

  /*
   * Информация о правах владельцев
  */
  function owners_perms_get_list()
    {
    $owners_perms_table = sysDBGetTable('owners_perms');
    $owners_perms_column = sysDBGetColumns($owners_perms_table);
    $dbpermslist = sysDbSelect ($owners_perms_table, $owners_perms_column, "ORDER BY $owners_perms_column[weight]", true);
    return ($dbpermslist);
    }

  /*
   * Информация о правеле владельца
  */
  function owners_perms_get_info($id)
    {
    $owners_perms_table = sysDBGetTable('owners_perms');
    $owners_perms_column = sysDBGetColumns($owners_perms_table);
    $info = sysDbSelect ($owners_perms_table, $owners_perms_column, "WHERE $owners_perms_column[id]='$id'");
    return ($info);
    }

  /*
   * Добавили правило владельца в систему
  */
  function owners_perms_create($param)
    {
    //Забрали преременные со входа
    $owners_perms_table = sysDBGetTable('owners_perms');
    $owners_perms_column = sysDBGetColumns($owners_perms_table);

    //Находим и задаем самый большой вес
    $MaxWeight = sysDbMaxWeight ($owners_perms_table);
    $MaxWeight++;
    $param['weight'] = $MaxWeight;

    //Добавляем элимент в базу.
    sysDbInsert ($owners_perms_table, $param);
    //Чистим кеш
    sysVarDelCached('sec_levels', 'owners_perms');
    return (mysql_insert_id());
    }

  /*
   * Обновили правило владельца в системе
  */
  function owners_perms_update($param)
    {
    //Забрали преременные со входа
    $owners_perms_table = sysDBGetTable('owners_perms');
    $owners_perms_column = sysDBGetColumns($owners_perms_table);

    unset ($param['weight']);//Подстраховка
    sysDbUpdate ($owners_perms_table, $param, "WHERE $owners_perms_column[id]='$param[id]'");

    //Чистим кеш
    sysVarDelCached('sec_levels', 'owners_perms');
    return true;
    }

  /*
   * Удаление правила владельца
  */
  function owners_perms_delete($id)
    {
    sysExtLibLoad();
    //Забрали преременные со входа
    $owners_perms_table = sysDBGetTable('owners_perms');
    $owners_perms_column = sysDBGetColumns($owners_perms_table);

    $dbdata = sysDbSelect ($owners_perms_table, "WHERE $owners_perms_column[id]='$id'");
    sysDbDelete ($owners_perms_table, "WHERE $owners_perms_column[id]='$id'");
    sysDbWeightDelete ($owners_perms_table, $dbdata['weight']);
    //Чистим кеш
    sysVarDelCached('sec_levels', 'owners_perms');
    return true;
    }

  /*
   * Увеличение вес правила владельца
  */
  function owners_perms_moveup($id)
    {
    sysExtLibLoad();
    $owners_perms_table = sysDBGetTable('owners_perms');
    $owners_perms_column = sysDBGetColumns($owners_perms_table);
    $dbdata = $this->owners_perms_get_info ($id);
    sysDbWeightMoveUp ($owners_perms_table, $dbdata['weight']);
    sysVarDelCached('sec_levels', 'owners_perms');
    return true;
    }

  /*
   * Уменьшение веса правила владельца
  */
  function owners_perms_movedown($id)
    {
    sysExtLibLoad();
    $owners_perms_table = sysDBGetTable('owners_perms');
    $owners_perms_column = sysDBGetColumns($owners_perms_table);
    $dbdata = $this->owners_perms_get_info ($id);
    sysDbWeightMoveDown ($owners_perms_table, $dbdata['weight']);
    sysVarDelCached('sec_levels', 'owners_perms');
    return true;
    }

  }

?>