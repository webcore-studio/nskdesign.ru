<?php
//Создает масив
//name 
function smarty_function_array($params, &$smarty)
{
extract($params);
$cleararray=array();
$smarty->assign($name, $cleararray);

}
?>