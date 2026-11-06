<?php
/*
 * Класс работы с плугинами
*/
class sysPlugin
  {

  /*
   * Возврашает список плугинов в системе
  */
  function get_list ($mod_name)
    {
    if (empty ($mod_name)) sysException ('sysPlugin->get_list', BAD_PARAM, 'Empty $mod_name');
    $mod_name = sysVarPrepForOS($mod_name);

    $plugins_list = array();
    $dh = @opendir('modules/'.$mod_name.'/plugins');
    while ($dir = @readdir($dh))
      {
      if ((is_dir("modules/$mod_name/plugins/$dir")) &&
                    ($dir != '.') &&
                    ($dir != '..') &&
                    ($dir != 'CVS') &&
                    (file_exists ("modules/$mod_name/plugins/$dir/plugin.php")))
        {
        // Нашли
        // Загружаем информацию о плугин
        $plugin_info = $this->get_info($mod_name, $dir);
        if (!empty ($plugin_info))
          array_push ($plugins_list, $plugin_info);
        }
      }
    @closedir($dh);
    return $plugins_list;
    }

  /**
   * @desc Загружает плугин
  */
  function plugin_load($mod_name, $plugin_name)
    {
    if (empty ($mod_name)) sysException ('sysPlugin->plugin_load', BAD_PARAM, 'Empty $mod_name');
    if (empty ($plugin_name)) sysException ('sysPlugin->plugin_load', BAD_PARAM, 'Empty $plugin_name');
    $mod_name = sysVarPrepForOS($mod_name);
    $plugin_name = sysVarPrepForOS($plugin_name);

    static $loaded = array();
    //Много кратно не загружаем
    if ($loaded[$mod_name][$plugin_name]) return true;

    //Небольшая проверка
    if (file_exists ("modules/$mod_name/plugins/$plugin_name/plugin.php"))
      {
      include_once("modules/$mod_name/plugins/$plugin_name/plugin.php");
      $loaded[$mod_name][$plugin_name] = false;
      return true;
      }

    return false;
    }

  /*
   * Возврашает инфу о плугине
  */
  function get_info ($mod_name, $plugin_name)
    {
    if (empty ($mod_name)) sysException ('sysPlugin->get_info', BAD_PARAM, 'Empty $mod_name');
    if (empty ($plugin_name)) sysException ('sysPlugin->get_info', BAD_PARAM, 'Empty $plugin_name');
    $mod_name = sysVarPrepForOS($mod_name);
    $plugin_name = sysVarPrepForOS($plugin_name);

    static $loaded = array();

    //Много кратно не собираем инфу
    if ($loaded[$mod_name][$plugin_name]) return $loaded[$mod_name][$plugin_name];

    //Небольшая проверка + загрузка плугина
    if ($this->plugin_load($mod_name, $plugin_name))
      {
      //Вызыв функции
      $func = $mod_name.'_plugin_'.$plugin_name.'_info';
      if (function_exists($func))
        $plugin_info = $func();
        else
          return false;

      $plugin_info['name'] = $plugin_name; //Доп зашита
      $loaded[$mod_name][$plugin_name] = $plugin_info;
      return $plugin_info;
      }

    return false;
    }

  /*
   * Диалоговое окно, вводимые данные для плугина
  */
  function modify ($mod_name, $plugin_name)
    {
    if (empty ($mod_name)) sysException ('sysPlugin->modify', BAD_PARAM, 'Empty $mod_name');
    if (empty ($plugin_name)) sysException ('sysPlugin->modify', BAD_PARAM, 'Empty $plugin_name');
    $mod_name = sysVarPrepForOS($mod_name);
    $plugin_name = sysVarPrepForOS($plugin_name);

    if ($this->plugin_load($mod_name, $plugin_name))
      {
      //Вызыв функции
      $func = $mod_name.'_plugin_'.$plugin_name.'_modify';
      if (function_exists($func))
        $plugin_modify_input = $func(null);
        else
          return false;

      return $plugin_modify_input;
      }

    return false;
    }

  /*
   * Вне сение изменений ы настройку плугина
  */
  function update ($mod_name, $plugin_name)
    {
    if (empty ($mod_name)) sysException ('sysPlugin->update', BAD_PARAM, 'Empty $mod_name');
    if (empty ($plugin_name)) sysException ('sysPlugin->update', BAD_PARAM, 'Empty $plugin_name');
    $mod_name = sysVarPrepForOS($mod_name);
    $plugin_name = sysVarPrepForOS($plugin_name);

    if ($this->plugin_load($mod_name, $plugin_name))
      {
      //Вызыв функции
      $func = $mod_name.'_plugin_'.$plugin_name.'_update';
      if (function_exists($func))
        $func(null);
        else
          return false;

      return true;
      }

    return false;
    }

  /*
   * Выполнение плугина
  */
  function exec ($mod_name, $plugin_name)
    {
    if (empty ($mod_name)) sysException ('sysPlugin->exec', BAD_PARAM, 'Empty $mod_name');
    if (empty ($plugin_name)) sysException ('sysPlugin->exec', BAD_PARAM, 'Empty $plugin_name');
    $mod_name = sysVarPrepForOS($mod_name);
    $plugin_name = sysVarPrepForOS($plugin_name);

    if ($this->plugin_load($mod_name, $plugin_name))
      {
      //Вызыв функции
      $func = $mod_name.'_plugin_'.$plugin_name.'_exec';
      if (function_exists($func))
        $result = $func(null);
        else
          return false;

      return $result;
      }

    return false;
    }

  }

?>