<?php
/*
 * Разлогиневание пользователя
*/

/*
 * Информация о плугине
*/
function SYS_users_plugin_logout_info()
  {
  // Проверка на доступность
  $plugin_info['name'] = 'logout';
  $plugin_info['weight'] = 10000;
  $plugin_info['exe_url'] = sysModURL ('SYS_users','user','logout');
  $plugin_info['displayname'] = 'Выход';
  $plugin_info['version'] = '0.1';
  $plugin_info['description'] = 'Выход пользователя из системы';
  $plugin_info['credits'] = '';
  $plugin_info['changelog'] = '';
  $plugin_info['license'] = '';
  $plugin_info['official'] = 1;
  $plugin_info['author'] = 'Илья Бердинских';
  $plugin_info['contact'] = 'elite@cherkassy.org';
  return $plugin_info;
  }
  
?>