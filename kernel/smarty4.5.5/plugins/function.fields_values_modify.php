<?php
	/*
	* Smarty plugin
	* $fld_module
	* $fld_key_a
	* $fld_key_b
	* -------------------------------------------------------------
	* {fields_values_modify fld_module=$fld_module fld_key_a=$fld_key_a fld_key_b=$fld_key_b}
	*/
	
function smarty_function_fields_values_modify($params, &$smarty)	{
	
	// Проверка наличия модуля
	if (!sysModAvailable ('fields')) return '';

	//Загружаем модуль
	sysModLoad('fields', 'ajax');

	//Вызываем функцию
	$mod_param = array('fld_name' => $params['fld_name'],
							 'fld_module' => $params['fld_module'],
	                   'fld_key_a' =>  $params['fld_key_a'],
	                   'fld_key_b' =>  $params['fld_key_b']);
							 

	$modresult =& sysModFunc('fields', 'ajax', 'values_modify', $mod_param);

	return $modresult['content'];
}

?>