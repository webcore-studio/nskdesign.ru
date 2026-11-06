<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     cat
 * Version:  1.0
 * Date:     Feb 24, 2003
 * Author:   Monte Ohrt <monte@ispi.net>
 * Purpose:  catentate a value to a variable
 * Input:    string to catenate
 * Example:  {$var|cat:"foo"}
 * -------------------------------------------------------------
 */
function smarty_modifier_tree($string, $level, $start_str='+--', $end_str='', $delimiter = '&nbsp;&nbsp;&nbsp;&nbsp;')
{
$level++;
$str='';

//Сначала пустые отступы
for ($i=0; $i < $level-1; $i++)
  {
  $str.=$delimiter;
  };
//Теперь красиво плюсики
if ($level>0)
  {
  $str.=$start_str;
  };

return $str . $string . $end_str;
}

/* vim: set expandtab: */

?>
