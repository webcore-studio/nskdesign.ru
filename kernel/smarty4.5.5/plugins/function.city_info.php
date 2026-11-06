<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {city_info} function plugin
 *
 * File:       function.city_info.php<br>
 * Type:       function<br>
 * Name:       city_info<br>
 */

function smarty_function_city_info($params, &$smarty){
    extract($params);
    sysModClassLoad ('address');
    $address = new address();
    $domain = explode('.', $_SERVER['HTTP_HOST']);
    $city = $address->getByTranslate($domain[0]);
    $smarty->assign($assign, $city);
}

?>
