<?php
//Count elements in a variable
//var
//assign
function smarty_function_count($params, &$smarty)
  {
  extract($params);

  $count = count($var);

  if (!empty($assign))
    $smarty->assign($assign, $count);
    else
      return $count;
  }
?>
