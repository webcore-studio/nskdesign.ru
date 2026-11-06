<?php
class SYS_themes
  {
  /*
   * Переменные
  */



  /*
   * Функции
  */


  /*
   * Функция добавления шаблона в систему шаблонов
  */
  function tpl_add($args)
    {
    //проверка на передачу данных
    $tpls_table = sysDBGetTable('templates');
    $tpls_column =  sysDBGetColumns($tpls_table);

    //Находим и задаем самый большой вес
    $MaxWeight = sysDbMaxWeight ($tpls_table, "WHERE $tpls_column[tpl_compname]='$args[tpl_compname]'");
    $MaxWeight++;
    $args['weight']=$MaxWeight;

    //Добавляем элимент в базу.
    sysDbInsert ($tpls_table, $args);

    // Удаляем кеш темплеев
    sysVarDelCached('tpls_list', $args['tpl_compname']);

    return true;
    }

  /*
   * Функция удаления шаблога из системы
  */
  function tpl_delete($id)
    {
    //Информация о шаблоне
    $tpl = $this->tpl_get_info($id);

    //проверка на передачу данных
    $tpls_table = sysDBGetTable('templates');
    $tpls_column =  sysDBGetColumns($tpls_table);

    //удаляем записи в базе
    sysDbDelete ($tpls_table, "WHERE $tpls_column[id]='$tpl[id]'");
    sysExtLibLoad();
    sysDbWeightDelete ($tpls_table, $tpl['weight'], "WHERE $tpls_column[tpl_compname]='$tpl[tpl_compname]'");

    // Удаляем кеш темплеев
    sysVarDelCached('tpls_list', $tpl['tpl_compname']);
    return true;
    }

  /*
   * Функция обновления информации в системе о шаблоне
  */
  function tpl_update($param)
    {
    $tpls_table = sysDBGetTable('templates');
    $tpls_column =  sysDBGetColumns($tpls_table);
    sysDbUpdate ($tpls_table, $param, "WHERE $tpls_column[id]='$param[id]'");
    // Удаляем кеш темплеев
    sysVarDelCached('tpls_list', $param['tpl_compname']);
    return true;
    }

  /*
   * Функция увеличения веса шаблона
  */
  function tpl_moveup($id)
    {
    //Информация о шаблоне
    $tpl = $this->tpl_get_info($id);

    sysExtLibLoad();
    $tpls_table = sysDBGetTable('templates');
    $tpls_column =  sysDBGetColumns($tpls_table);
    sysDbWeightMoveUp ($tpls_table, $tpl['weight'], "WHERE $tpls_column[tpl_compname]='$tpl[tpl_compname]'");

    // Удаляем кеш темплеев
    sysVarDelCached('tpls_list', $tpl['tpl_compname']);
    return true;
    }

  /*
   * Функция уменьшения веса шаблона
  */
  function tpl_movedown($id)
    {
    //Информация о шаблоне
    $tpl = $this->tpl_get_info($id);

    sysExtLibLoad();
    $tpls_table = sysDBGetTable('templates');
    $tpls_column =  sysDBGetColumns($tpls_table);
    sysDbWeightMoveDown ($tpls_table, $tpl['weight'], "WHERE $tpls_column[tpl_compname]='$tpl[tpl_compname]'");

    // Удаляем кеш темплеев
    sysVarDelCached('tpls_list', $tpl['tpl_compname']);
    return true;
    }

  /*
   * Вункция возврашает список шаблонов компанента
  */
  function tpl_get_list($comp_name)
    {
    $tpls_table = sysDBGetTable('templates');
    $tpls_column =  sysDBGetColumns($tpls_table);
    //Делаем выборку
    $result = sysDbSelect ($tpls_table, $tpls_column, "WHERE $tpls_column[tpl_compname]='$comp_name'
                                             ORDER BY $tpls_column[weight]", true);
    return ($result);
    }

  /*
   * Вункция возврашает информацию о шаблоне ID
  */
  function tpl_get_info($id)
    {
    $tpls_table = sysDBGetTable('templates');
    $tpls_column =  sysDBGetColumns($tpls_table);
    //Делаем выборку
    $result = sysDbSelect ($tpls_table, $tpls_column, "WHERE ($tpls_column[id]='$id')");
    return ($result);
    }



  /*
   * Вункция регенирирования тем
   * Что делает:
   * 1. Ишит темы на диске и добовляет в базу
   * 2. Если на диске нету удаляет из базы
  */
  function regen()
    {
    //Взяли список с базы
    $themes_table = sysDBGetTable('themes');
    $themes_column = sysDBGetColumns($themes_table);
    $dbthemes_list = sysDbSelect ($themes_table, $themes_column, "ORDER BY $themes_column[theme_name]", true);

    //Взяли список с диска
    $filethemes_list = array();
    $dh = opendir('themes');
    while ($dir = readdir($dh))
      {
      if ((is_dir("themes/$dir")) &&
                    ($dir != '.') &&
                    ($dir != '..') &&
                    ($dir != 'CVS') &&
                    (file_exists ("themes/$dir/theme.php")))
        {
        // Found a directory
        $info = array();
        include("themes/$dir/theme.php");
        $info['theme_name']=$dir;
        array_push ($filethemes_list, $info);
        }
      }
    closedir($dh);

    //Теперь в $dbthemes_list - модули в бд, $filethemeslist - модули с диска

    //Проверяем тему, всели файлы на месте
    if (isset ($dbthemes_list))
      {
      foreach ($dbthemes_list as $theme)
        {
        if (!file_exists ("themes/$theme[theme_name]/theme.php"))
          {
           //удаляем тему
          sysDbDelete ($themes_table, "WHERE $themes_column[theme_name]='$theme[theme_name]'");
          //Очистили кеш переменных
          sysVarDelCached('tpls_list', 'pagetpl_'.$theme['theme_name']);
          //Очищаем кеш шаблонов блока
          $sysTpl = new sysTpl;
          $sysTpl->clear_cache(null, 'themes_block');
          // Чистим кеш переменных
          sysVarDelCached('themes_list', 'themes');
          };
        };
      };

    //Добавляем новые найденные на диске
    foreach ($filethemes_list as $theme)
      {
      //Проверка на повторяемость
      if (isset ($dbthemes_list))
        {
        foreach ($dbthemes_list as $dbtheme)
          {
          $prezent=false;
          if ($dbtheme['theme_name']==$theme['theme_name'])
            {//Уже есть
            $prezent=true;
            break;
            };
          };
        };

      if (!$prezent)
        {
	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	if ($theme['theme_name']==sysModGetVar('SYS_config','default_theme'))  $theme['theme_active']=true;
	else
        $theme['theme_active']=false;//По умолчаню сразу неактивна
        // New theme
        sysDbInsert ($themes_table, $theme);
        //Регенирация шаблонов
        //Очищаем кеш шаблонов блока
        $sysTpl = new sysTpl;
        $sysTpl->clear_cache(null, 'themes_block');
        // Чистим кеш переменных
        sysVarDelCached('themes_list', 'themes');
        };
      };
    }

  /*
   * Возврашает список тем в системе
  */
  function get_list()
    {
    $themes_table = sysDBGetTable('themes');
    $themes_column = sysDBGetColumns($themes_table);
    $themeslist = sysDbSelect ($themes_table, $themes_column, "ORDER BY $themes_column[theme_name]", true);

    foreach ($themeslist as $key=>$info)
      {
      //Полная информация о теме
      include("themes/$info[theme_name]/theme.php");
      $themeslist[$key] = $info;
      };
    return $themeslist;
    }

  /*
   * Возврашает информацию о теме в системе
  */
  function get_info($id)
    {
    $themes_table = sysDBGetTable('themes');
    $themes_column = sysDBGetColumns($themes_table);
    $info = sysDbSelect ($themes_table, $themes_column, "WHERE $themes_column[id] = '$id'");
    return $info;
    }

  /*
   * Активирование темы
  */
  function activate($id)
    {
    $themes_table = sysDBGetTable('themes');
    $themes_column =  sysDBGetColumns($themes_table);

    sysDbUpdate ($themes_table, array ('theme_active'=>true),
                 "WHERE $themes_column[id]=$id");
    //Очищаем кеш шаблонов блока
    $sysTpl = new sysTpl;
    $sysTpl->clear_cache(null, 'themes_block');
    // Чистим кеш переменных
    sysVarDelCached('themes_list', 'themes');

    return true;
    }

  /*
   * Деактивирование темы
  */
  function deactivate($id)
    {
    $themes_table = sysDBGetTable('themes');
    $themes_column =  sysDBGetColumns($themes_table);

    sysDbUpdate ($themes_table, array ('theme_active'=>false),
                 "WHERE $themes_column[id]=$id");
    //Очищаем кеш шаблонов блока
    $sysTpl = new sysTpl;
    $sysTpl->clear_cache(null, 'themes_block');
    // Чистим кеш переменных
    sysVarDelCached('themes_list', 'themes');

    return true;
    }


  }

?>