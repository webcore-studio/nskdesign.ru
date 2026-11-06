<?php
/*
 * Мои серии
*/

/*
 * Информация о плугине
*/
function SYS_users_plugin_foto_myfoto_view_info()
  {
  // Проверка на доступность
  if (!sysModAvailable('foto'))
    {
    return;
    }

  $user_id = sysSessionGetVar('user_id');

  $plugin_info['name'] = 'myfoto_view';
  $plugin_info['weight'] = 100;
  $plugin_info['exe_url'] = sysModURL ('foto','user','authors_view', array('id'=>$user_id, 'v_as_mf'=>1));
  $plugin_info['displayname'] = 'Мои фотографии';
  $plugin_info['version'] = '0.1';
  $plugin_info['description'] = 'Мои фотографии';
  $plugin_info['credits'] = '';
  $plugin_info['changelog'] = '';
  $plugin_info['license'] = '';
  $plugin_info['official'] = 1;
  $plugin_info['author'] = 'Илья Бердинских';
  $plugin_info['contact'] = 'elite@cherkassy.org';
  return $plugin_info;
  }

?>