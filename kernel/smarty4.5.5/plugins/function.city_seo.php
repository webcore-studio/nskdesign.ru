<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {city_seo} function plugin
 *
 * File:       function.city_seo.php<br>
 * Type:       function<br>
 * Name:       city_seo<br>
 */

function smarty_function_city_seo($params, &$smarty){
    extract($params);
    
    if(!$params['info'][aliases]&&$params['info'][cities_name]){
        $params['info'][aliases] = array(
            0=>$params['info'][cities_name],
            1=>$params['info'][cities_name],
            2=>$params['info'][cities_name],
            3=>$params['info'][cities_name],
            4=>$params['info'][cities_name],
            5=>$params['info'][cities_name]
        );
    }
    if(!$params['info'][aliases]&&!$params['info'][cities_name]){
        $params['info'][aliases] = array(
            0=>'Россия',
            1=>'России',
            2=>'России',
            3=>'Россию',
            4=>'Россией',
            5=>'России'
        );
    }
    if($params['info'][aliases]){
        foreach ($params['info'][aliases] as $key => $aliase) {
            $params['text'] = str_replace('#city['.$key.']#', $aliase, $params['text']);
        }
    }
    if($params['assign']) $smarty->assign($assign, $params['text']);
    else return $params['text'];
}

