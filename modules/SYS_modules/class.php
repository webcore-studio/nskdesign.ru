<?php

class SYS_modules
  {
  /*
   * Переменные
  */



  /*
   * Функции
  */


  /*
   * Возврашает список модулей в системе
  */
  function get_list ($all = true)
    {
    $modules_table = sysDBGetTable('modules');
    $modules_column =  sysDBGetColumns($modules_table);

    //Выборка в зависимости от того все или нет
    if ($all)
      {
      $modules_list =& sysDbSelect ($modules_table, $modules_column, '');
      }
      else
        {
        $modules_list =& sysDbSelect ($modules_table, $modules_column,
          "WHERE $modules_column[mod_state]='".MODULE_STATE_ACTIVE."' ORDER BY $modules_column[mod_displayname]", true);
        };

    //Вернули результат
    return $modules_list;
    }


  /*
   * Регенирировать список модулей в системе
  */
  function regen ()
    {
    //Взяли список с базы
    $modules_table = sysDBGetTable('modules');
    $modules_column =  sysDBGetColumns($modules_table);
    $dbmoduleslist = sysDbSelect ($modules_table, $modules_column,
                                  "ORDER BY $modules_column[mod_name]", true );

    //Взяли список с диска
    $filemoduleslist = array();
    $dh = opendir('modules');
    while ($dir = readdir($dh))
      {
      unset($modtype);
      if ((is_dir("modules/$dir")) &&
                    ($dir != '.') &&
                    ($dir != '..') &&
                    ($dir != 'CVS') &&
                    (file_exists ("modules/$dir/info.php")))
        {
        // Found a directory
        $info = array();
        $info['mod_version'] = '0';
        $info['mod_description'] = '';
        include ("modules/$dir/info.php");
        $info['mod_name'] = $dir;
        array_push ($filemoduleslist, $info);
        }
      }
    closedir($dh);
    //Теперь в $dbmoduleslist - модули в бд, $filemoduleslist - модули с диска

    if (isset ($dbmoduleslist))
      {
      foreach ($dbmoduleslist as $module)
        {
        //Проверяем наместели модуль
        if (!file_exists ("modules/$module[mod_name]/info.php"))
          {
          //А может убрать из списка вообще ??
          if ($module['mod_state']==MODULE_STATE_UNINITIALISED)
            {
            sysDbDelete ($modules_table, "WHERE $modules_column[id]='$module[id]'");
            };
          //Удаляем из кеша
          sysVarDelCached('modavailable', $module['mod_name']);
          };
        };
      };


    //Добавляем новые найденные на диске
    foreach ($filemoduleslist as $module)
      {
      //Проверка на повторяемость
      if (isset ($dbmoduleslist))
        {
        foreach ($dbmoduleslist as $dbmod)
          {
          $prezent=false;
          if ($dbmod['mod_name']==$module['mod_name'])
            {//Уже есть
            $prezent=true;
            break;
            };
          };
        };

      if (!$prezent)
        {
        $module['mod_state'] = MODULE_STATE_UNINITIALISED;
        // New module
        sysDbInsert ($modules_table, $module);
        }
      }

    return true;
    }


  /*
   * Возврашает информацию о модуле в системе
  */
  function get_info ($mod_id)
    {
    $modules_table = sysDBGetTable('modules');
    $modules_column = sysDBGetColumns($modules_table);
    $module_info =& sysDbSelect ($modules_table, $modules_column,
                                "WHERE $modules_column[id]='".sysVarPrepForStore($mod_id)."'");
    if (empty ($module_info))
      {
      sysException ('SYS_modules->get_info', 'BAD_PARAM');
      };
    return $module_info;
    }

  /*
   * Инсталяция модуля $id или $name
  */
  function initialise($param)
    {
    if (is_numeric ($param))
      {
      $id = $param;
      }
      else
        {
        $name = $param;
        };

    //Получили список столбцов модуля
    $modules_table = sysDBGetTable('modules');
    $modules_column = sysDBGetColumns($modules_table);

    // Get module information
    if (empty ($name))
      {
      $modinfo = sysDbSelect($modules_table, $modules_column, "WHERE $modules_column[id]='$id'");
      }
      else
        {
        $modinfo['mod_name'] = $name;
        };

    // Module initialisation function
    $initfile='modules/'.$modinfo['mod_name'].'/init.php';
    if (file_exists ($initfile))
      {
      include_once("$initfile");
      }
      else
        {
        sysException ('SYS_modules->initialise', MODULE_FILE_NOT_EXIST);
        };
    //Передаем управление модулю
    $func = $modinfo['mod_name'] . '_init';
    if (function_exists($func))
      {
      if ($func() != true)
        {
        sysException ('SYS_modules->initialise', MODULE_FUNCTION_NOT_EXIST, 'Ошибка инициализации модуля');
        }
      }
    //
    $modinfo['mod_state'] = MODULE_STATE_INACTIVE;
    if (empty ($name))// унас два значения
      {
      sysDbUpdate ($modules_table, $modinfo, "WHERE $modules_column[id]='$id'");
      }
      else
        {
        sysDbUpdate ($modules_table, $modinfo, "WHERE $modules_column[mod_name]='$name'");
        };
    return true;
    }

  /*
   * Активация модуля $id или $name
  */
  function activate($param)
    {
    if (is_numeric ($param))
      {
      $id = $param;
      }
      else
        {
        $name = $param;
        };

    //Получили список столбцов модуля
    $modules_table  = sysDBGetTable('modules');
    $modules_column =  sysDBGetColumns($modules_table);
    // Get module information
    if (empty ($name))
      {
      $modinfo = sysDbSelect($modules_table, $modules_column, "WHERE $modules_column[id]='$id'");
      }
      else
        {
        $modinfo=sysDbSelect($modules_table, $modules_column, "WHERE $modules_column[mod_name]='$name'");
        //$modinfo['mod_name'] = $name; ????????????????????
        //$id = $modinfo['id'];
        };

    // Module initialisation function
    $initfile='modules/'.$modinfo['mod_name'].'/init.php';
    if (file_exists ($initfile))
      {
      include_once("$initfile");
      }
      else
        {
        sysException ('SYS_modules->activate', MODULE_FILE_NOT_EXIST);
        };
    //Передаем управление модулю
    $func = $modinfo['mod_name'] . '_activate';
    if (function_exists($func))
      {
      if ($func() != true)
        {
        sysException ('SYS_modules->activate', MODULE_FUNCTION_NOT_EXIST);
        }
      }
    //
    $modinfo['mod_state'] = MODULE_STATE_ACTIVE;
    sysDbUpdate ($modules_table, $modinfo, "WHERE $modules_column[id]='$modinfo[id]'");
    //Чистим кеш
    sysVarDelCached('modavailable', $modinfo['mod_name']);
    //Очищаем кеш блока "модули"
    $sysTpl = new sysTpl;
    $sysTpl->clear_cache(null, 'modules_block');
    return true;
    }

  /*
   * Удаление модуля $id
  */
  function delete($id)
    {
    //Получили список столбцов модуля
    $modules_table  = sysDBGetTable('modules');
    $modules_column =  sysDBGetColumns($modules_table);
    // Get module information
    $modinfo = sysDbSelect($modules_table, $modules_column, "WHERE $modules_column[id]='$id'");
    // Module initialisation function
    $initfile='modules/'.$modinfo['mod_name'].'/init.php';
    if (file_exists ($initfile))
      {
      include_once("$initfile");
      }
      else
        {
        sysException ('SYS_modules->delete', MODULE_FILE_NOT_EXIST);
        };
    //Передаем управление модулю
    $func = $modinfo['mod_name'] . '_delete';
    if (function_exists($func))
      {
      if ($func() != true)
        {
        sysException ('SYS_modules->delete', MODULE_FUNCTION_NOT_EXIST);
        }
      }
    //
    $modinfo['mod_state'] = MODULE_STATE_UNINITIALISED;
    sysDbUpdate ($modules_table, $modinfo, "WHERE $modules_column[id]='$id'");
    // Delete any module variables that the module cleanup function might
    // have missed
    $module_vars_table = sysDBGetTable('module_vars');
    $module_vars_column =  sysDBGetColumns($module_vars_table);
    sysDbDelete ($module_vars_table,
        "WHERE $module_vars_column[modname] = '" . sysVarPrepForStore($modinfo['mod_name'])."'");
    //Очищаем кеш шаблонов
    $sysTpl = new sysTpl;
    $sysTpl->clear_cache(null, $modinfo['mod_name']);
    //Удаляем темплеи
    $tpls_table = sysDBGetTable('templates');
    $tpls_column =  sysDBGetColumns($tpls_table);
    sysDbDelete ($tpls_table, "WHERE $tpls_column[tpl_compname]='".$modinfo['mod_name']."'");
    // Удаляем кеш темплеев
    sysVarDelCached('tpls_list', $modinfo['mod_name']);
    return true;
    }

  /*
   * Деактивация модуля модуля $id
  */
  function deactivate($id)
    {
    //Получили список столбцов модуля
    $modules_table  = sysDBGetTable('modules');
    $modules_column =  sysDBGetColumns($modules_table);
    // Get module information
    $modinfo = sysDbSelect($modules_table, $modules_column, "WHERE $modules_column[id]='$id'");
    // Module initialisation function
    $initfile = 'modules/'.$modinfo['mod_name'].'/init.php';
    if (file_exists ($initfile))
      {
      include_once("$initfile");
      }
      else
        {
        sysException ('SYS_modules->deactivate', MODULE_FILE_NOT_EXIST);
        };
    //Передаем управление модулю
    $func = $modinfo['mod_name'] . '_activate';
    if (function_exists($func))
      {
      if ($func() != true)
        {
        sysException ('SYS_modules->deactivate', MODULE_FUNCTION_NOT_EXIST);
        }
      }
    //
    $modinfo['mod_state'] = MODULE_STATE_INACTIVE;
    sysDbUpdate ($modules_table, $modinfo, "WHERE $modules_column[id]='$id'");

    //Чистим кеш
    sysVarDelCached('modavailable', $modinfo['mod_name']);
    //Очищаем кеш блока "модули"
    $sysTpl = new sysTpl;
    $sysTpl->clear_cache(null, 'modules_block');
    //очищаем кеш отключаемого модуля
    $sysTpl->clear_cache(null, $modinfo['mod_name']);

    return true;
    }

  }

?>