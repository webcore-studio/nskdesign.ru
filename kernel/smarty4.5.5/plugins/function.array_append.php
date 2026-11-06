<?php
//Присоединение элимента к массиву
//name 
//key
//value
function smarty_function_array_append($params, &$smarty)
{
extract($params);

$arraydate = $smarty->get_template_vars($name);

if (!is_array($arraydate))
  {
  $arraydate=array();
  };

if (isset ($key))
  {
  $arraydate[$key]=$value;
  }
  else
    {
    $arraydate[]=$value;
    };
//array_push ($arraydate, $var);
$smarty->assign($name, $arraydate);

}
?>