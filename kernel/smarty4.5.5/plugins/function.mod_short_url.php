<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.eightball.php
 * Type:     function
 * Name:     eightball
 * Purpose:  outputs a random magic answer
 * -------------------------------------------------------------
 */
function smarty_function_mod_short_url($params, &$smarty)
{
	$result = sysModShortURL ($params['modname'], $params['type'], $params['second'], array('id' => $params['id'], 'name' => $params['name'], 'page' => $params['page']), $params['postfix']);
	return $result;
}
?>