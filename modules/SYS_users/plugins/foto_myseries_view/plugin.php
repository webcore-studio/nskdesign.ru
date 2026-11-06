<?php
/*
 * Мои серии
*/

/*
 * Информация о плугине
*/
function SYS_users_plugin_foto_myseries_view_info()
  {
  // Проверка на доступность
  if (!sysModAvailable('foto'))
    {
    return;
    }
  $plugin_info['name'] = 'myseries_view';
  $plugin_info['weight'] = 110;
  $plugin_info['exe_url'] = sysModURL ('foto','user','myseries_list');
  $plugin_info['displayname'] = 'Мои серии';
  $plugin_info['version'] = '0.1';
  $plugin_info['description'] = 'Мои серии';
  $plugin_info['credits'] = '';
  $plugin_info['changelog'] = '';
  $plugin_info['license'] = '';
  $plugin_info['official'] = 1;
  $plugin_info['author'] = 'Илья Бердинских';
  $plugin_info['contact'] = 'elite@cherkassy.org';
  return $plugin_info;
  }

?>