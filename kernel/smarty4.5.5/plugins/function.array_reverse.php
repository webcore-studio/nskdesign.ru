<?php

function smarty_function_array_reverse($params, &$smarty)
{
extract($params);

$arraydate = $smarty->get_template_vars($name);
if (is_array($arraydate))
  $arraydate=array_reverse ($arraydate);

$smarty->assign($name, $arraydate);
}
?>