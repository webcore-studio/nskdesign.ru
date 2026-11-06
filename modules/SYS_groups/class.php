<?php

class SYS_groups
  {
  /*
   * Переменные
  */



  /*
   * Функции
  */


  /*
   * Возврашает список груп в системе
  */
  function get_list()
    {
    $groups_table = sysDBGetTable ('groups');
    $groups_column = sysDBGetColumns($groups_table);
  
    //Вынести в настройку - по какому полю сортировать 25.06.2019 minalexpro
    $groups_list =& sysDbSelect ($groups_table, $groups_column, "ORDER BY $groups_column[id] DESC", true);

    return $groups_list;
    }

  /*
   * Возврашает информацию о группе
  */
  function get_info($id)
    {
    $groups_table = sysDBGetTable ('groups');
    $groups_column = sysDBGetColumns($groups_table);
    $GroupInfo =& sysDbSelect ($groups_table, $groups_column, "WHERE $groups_column[id]='$id'");
    return $GroupInfo;
    }

  /*
   * Создаем группу в системе
  */
  function create($data)
    {
    $groups_table = sysDBGetTable ('groups');
    $gid = sysDbInsert ($groups_table, $data);
    return $gid;
    }

  /*
   * Обновление информации о группе в системе
  */
  function update($data)
    {
    $groups_table = sysDBGetTable ('groups');
    $groups_column = sysDBGetColumns($groups_table);
    sysDbUpdate ($groups_table, $data, "WHERE $groups_column[id] = '$data[id]'");
    return true;
    }

  /*
   * Удаление группы из системы
  */
  function delete($id)
    {
    $groups_table = sysDBGetTable ('groups');
    $groups_column = sysDBGetColumns($groups_table);
    sysDbDelete ($groups_table, "WHERE $groups_column[id] = '$id'");
    return true;
    }

  }

?>