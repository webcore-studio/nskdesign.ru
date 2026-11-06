<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {sites_links} function plugin
 *
 * File:       function.sites_links.php<br>
 * Type:       function<br>
 * Name:       sites_links<br>
 */

function smarty_function_sites_links($params, &$smarty){
    extract($params);
    $sites = array_map('trim', explode("\n", $params['sites']));
    $icon = 'http://favicon.yandex.net/favicon/';
    foreach ($sites as $key => $site) {
        if($site){
            $site = parse_url($site);
            $result[] = array(
                'href' => ($site['scheme']?$site['scheme']:'http')
                    .'://'.$site['host'].$site['path'],
                'name' => ($site['host']?$site['host'].$site['path']:$site['path']),
                'icon' => $icon.($site['host']?$site['host']:$site['path'])
            );
        }
    }
    
    if($params['assign']) $smarty->assign($assign, $result);
    else return $result;
}

