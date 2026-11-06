<?php
/*
 * Smarty plugin
 * $modname
 * $comm_key_a
 * $comm_key_b
 * $sort
 * $asc
 * $page
 * -------------------------------------------------------------
 * {comment_list modname='zozo' comm_key_a='12' comm_key_b='18' sort='time' asc='true'}
 */
function smarty_function_comment_list($params, &$smarty)
	{
  //Проверка разрешеныли комментарии
  if (!sysModGetVar('comment', 'comment_enabled')) return'';

  // Проверка наличия модуля
  if (!sysModAvailable ('comment')) return '';

  //Загружаем модуль
  sysModLoad('comment', 'ajax');

	//Вызываем функцию
	$mod_param = array('comm_module' => $params['modname'],
	                   'comm_key_a' =>  $params['comm_key_a'],
	                   'comm_key_b' =>  $params['comm_key_b']);

	$modresult =& sysModFunc('comment', 'ajax', 'list', $mod_param);

  return $modresult['content'];
  }

?>