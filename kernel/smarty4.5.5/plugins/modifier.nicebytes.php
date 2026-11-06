<?php

function smarty_modifier_nicebytes($bytes)
	{
  $unim = array("B","KB","MB","GB","TB","PB");
  $c = 0;
  while ($bytes>=1024)
    {
    $c++;
    $bytes = $bytes/1024;
    }
  //Округляем mb
  //if ($c==2) return round($bytes).' '.$unim[$c];
  return number_format($bytes,($c ? 2 : 0),".",",")." ".$unim[$c];
	}

?>
