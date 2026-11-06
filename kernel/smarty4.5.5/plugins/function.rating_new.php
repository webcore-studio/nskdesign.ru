<?php
/*
 * Smarty plugin
 * $modname
 * $rating_key_a
 * $rating_key_b
 * -------------------------------------------------------------
 * {rating_new modname='zozo' rating_key_a='12' rating_key_b='18'}
 */
function smarty_function_rating_new($params, &$smarty)
	{
  //Проверка разрешеныли комментарии
  if (!sysModGetVar('rating', 'rating_enabled')) return'';

  // Проверка наличия модуля
  if (!sysModAvailable ('rating')) return '';

  //Загружаем модуль
	sysModLoad('rating','ajax');

	//Вызываем функцию
	$mod_param = array('rating_module' => $params['modname'],
	                   'rating_key_a' =>  $params['rating_key_a'],
	                   'rating_key_b' =>  $params['rating_key_b']);

	$modresult =& sysModFunc('rating', 'ajax', 'new', $mod_param);

  return $modresult['content'];
  }

?>