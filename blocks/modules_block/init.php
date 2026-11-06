<?php

function modules_block_add($blockinfo)
  {
  //Добовляем шаблон, если нету еше
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;

  $sysObject = 'modules_block::display';
  $sysModTpl = sysTplWay ($sysObject);

  if (empty ($sysModTpl))
    {
    $SYS_themes->tpl_add (array('tpl_compname'=>'modules_block',
                               'tpl_name'=>'block_display.tpl',
                               'tpl_pattern'=>$sysObject,
                               'tpl_description'=>'Вывод содержания блока'));
    };
  return true;
  }

function modules_block_delete($blockinfo)
{
return true;
}

function modules_block_activate($blockinfo)
{
return true;
};

function modules_block_deactivate($blockinfo)
{
return true;
};

?>