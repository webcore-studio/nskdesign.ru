<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {sites_links} function plugin
 *
 * File:       function.phone_links.php<br>
 * Type:       function<br>
 * Name:       phone_links<br>
 */

function smarty_function_phone_links($params, &$smarty){
    extract($params);
    $phones = array_map('trim', explode("\n", $params['phones']));
    foreach ($phones  as $key => $phone) {
        if($phone){
            $result[] = array(
                'name' => $phone, 
                'icon' => 'phone.png'
            );
        }
    }
    
    if($params['assign']) $smarty->assign($assign, $result);
    else return $result;
}

