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
function smarty_function_config_css($params, &$smarty)
{
	
	$css = sysModGetVar ('SYS_config', 'stylesheet');
	if (!$css)
		return "";
	
	$style_list = "";
	$css = explode("\r\n", $css);
	foreach ($css as $c) {
		$style_list .= '<link rel="StyleSheet" href="'.$c.'" type="text/css">'."\r\n";
	}

		
	if ($params['assign'])
		$smarty->assign($params['assign'], $style_list);
	else
		return $style_list;
}

?>