<?php
function archive_block_add($blockinfo)
  {
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;

  //Добавляем шаблон, если нет еше
  $sysObject = 'archive_block::display';
  $sysModTpl = sysTplWay ($sysObject);

  if (empty ($sysModTpl))
    {
    $SYS_themes->tpl_add (array('tpl_compname'=>'archive_block',
                                'tpl_name'=>'block_display.tpl',
                                'tpl_pattern'=>$sysObject,
                                'tpl_description'=>'Вывод содержания блока'));
    };
 
  return true;
  }

function archive_block_delete($blockinfo)
  {
  return true;
  }

function archive_block_activate($blockinfo)
  {
  return true;
  }

function archive_block_deactivate($blockinfo)
  {
  return true;
  };

?>
