<?php
/*
 * Мои серии
*/

/*
 * Информация о плугине
*/
function SYS_users_plugin_crm_client_view_info()
  {
  // Проверка на доступность
  if (!sysModAvailable('crm'))
    return;

  $user_id = sysSessionGetVar('user_id');

  $plugin_info['name'] = 'crm_client_view';
  $plugin_info['weight'] = 100;
  $plugin_info['exe_url'] = sysModURL ('crm','user','client_view', array('id'=>$user_id));
  $plugin_info['displayname'] = 'Мои заказы';
  $plugin_info['version'] = '0.98';
  $plugin_info['description'] = 'Мои заказы';
  $plugin_info['credits'] = '';
  $plugin_info['changelog'] = '';
  $plugin_info['license'] = '';
  $plugin_info['official'] = 1;
  $plugin_info['author'] = 'Илья Бердинских';
  $plugin_info['contact'] = 'stalker@2mcl.com';
  return $plugin_info;
  }

?>