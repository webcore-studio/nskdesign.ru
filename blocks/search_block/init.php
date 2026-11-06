<?php

function search_block_add($blockinfo)
  {
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;

  //Добовляем шаблон, если нету еше
  $sysObject = 'search_block::display';
  $sysModTpl = sysTplWay ($sysObject);
  if (empty ($sysModTpl))
    {
    $SYS_themes->tpl_add (array('tpl_compname'=>'search_block',
                                'tpl_name'=>'block_display.tpl',
                                'tpl_pattern'=>$sysObject,
                                'tpl_description'=>'Вывод содержания блока'));
    }

  return true;
  }

function search_block_delete($blockinfo)
  {
  return true;
  }

function search_block_activate($blockinfo)
  {
  return true;
  }

function search_block_deactivate($blockinfo)
  {
  return true;
  }

?>