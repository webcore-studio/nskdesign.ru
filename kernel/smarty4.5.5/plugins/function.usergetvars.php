<?php
/*
 * Smarty plugin
 * assign
 * uid
 */
function smarty_function_usergetvars($params, &$smarty)
{
extract($params);

if (isset ($uid))
  {
  $vars = sysUserGetVars($uid);
  }
  else
    {
    $vars = sysUserGetVars();
    };
$smarty->assign($assign, $vars);

}
?>