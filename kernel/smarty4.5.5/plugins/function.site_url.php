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
function smarty_function_site_url($params, &$smarty)
{
extract($params);

$result = sysGetSiteUrl();
if ($result && $assign) 
	$smarty->assign($assign, $result);
if ($result && !$assign) 
	return $result;
}
?>