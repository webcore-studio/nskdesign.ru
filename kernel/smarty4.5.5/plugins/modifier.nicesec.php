<?php

function smarty_modifier_nicesec($bytes)
	{
  $unim = array("сек.","мин.","час.");
  $c = 0;
  while ($bytes>=60)
    {
    $c++;
    $bytes = $bytes/60;
    }
  //Округляем mb
  //if ($c==2) return round($bytes).' '.$unim[$c];
  return number_format($bytes,($c ? 2 : 0),":",",")." ".$unim[$c];
	}

?>
