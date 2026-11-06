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
function smarty_function_user_theme($params, &$smarty)
{
$result = sysUserTheme ();
if ($params['assign'])
	$smarty->assign($params['assign'], $result);
else return $result;
}
?>