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
function smarty_function_sess_getvar($params, &$smarty)
{
extract($params);

$result = sysSessionGetVar ($name);
if ($assign)
	$smarty->assign($assign, $result);
else return $result;
}
?>