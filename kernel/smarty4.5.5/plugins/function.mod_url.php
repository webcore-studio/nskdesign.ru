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
function smarty_function_mod_url($params, &$smarty)
{
if (isset ($params['vars']))
  {
  $result = sysModURL ($params['modname'], $params['type'], $params['func'], $params['vars']);
  }
  else
    {
    $result = sysModURL ($params['modname'], $params['type'], $params['func']);
    };
return $result;
}
?>