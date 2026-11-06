<?php

function online_block_add($blockinfo)
  {
  //Добовляем шаблон, если нету еше
  $sysObject = 'online_block::display';
  $sysModTpl = sysTplWay ($sysObject);
  if (empty ($sysModTpl))
    {
    //Добовляем шаблон, если нету еше
    sysModClassLoad ('SYS_themes');
    $SYS_themes = new SYS_themes;      
    $SYS_themes->tpl_add (array('tpl_compname'=>'online_block',
                                'tpl_name'=>'block_display.tpl',
                                'tpl_pattern'=>$sysObject,
                                'tpl_description'=>'Вывод содержания блока'));
    }
  return true;
  }

function online_block_delete($blockinfo)
  {
  return true;
  }

function online_block_activate($blockinfo)
  {
  return true;
  }

function online_block_deactivate($blockinfo)
  {
  return true;
  }

?>