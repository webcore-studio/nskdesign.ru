<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {sites_links} function plugin
 *
 * File:       function.metro_links.php<br>
 * Type:       function<br>
 * Name:       metro_links<br>
 */

function smarty_function_metro_links($params, &$smarty){
    extract($params);
    $metro = array_map('trim', explode("\n", $params['metro']));
    foreach ($metro  as $key => $mo) {
        if($mo){
            $result[] = array(
                'name' => $mo, 
                'icon' => 'metro1.png'
            );
        }
    }
    
    if($params['assign']) $smarty->assign($assign, $result);
    else return $result;
}

