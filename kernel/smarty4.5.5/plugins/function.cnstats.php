<?php
//Count elements in a variable
//var
//assign
function smarty_function_cnstats($params, &$smarty)
  {
 	include_once "cnstats/cnt.php";

	//Cnstats bug fix
	sysDBInit();
  }
?>
