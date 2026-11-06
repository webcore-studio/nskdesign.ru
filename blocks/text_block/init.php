<?php

function text_block_add($blockinfo)
  {
  //Добовляем шаблон, если нету еше
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;

  $sysObject = 'text_block::display';
  $sysModTpl = sysTplWay ($sysObject);

  if (empty ($sysModTpl))
    {
    $SYS_themes->tpl_add (array('tpl_compname'=>'text_block',
                               'tpl_name'=>'block_display.tpl',
                               'tpl_pattern'=>$sysObject,
                               'tpl_description'=>'Вывод содержания блока'));
    };

  //Добовляем шаблон, если нету еше
  $sysObject = 'text_block::modify';
  $sysModTpl = sysTplWay ($sysObject);
  if (empty ($sysModTpl))
    {
    $SYS_themes->tpl_add (array('tpl_compname'=>'text_block',
                               'tpl_name'=>'block_modify.tpl',
                               'tpl_pattern'=>$sysObject,
                               'tpl_description'=>'Редактирование содержания блока'));
    };

  return true;
  }

function text_block_delete($blockinfo)
  {
  return true;
  }

function text_block_activate($blockinfo)
  {
  return true;
  }

function text_block_deactivate($blockinfo)
  {
  return true;
  }

function text_block_modify($blockinfo)
  {
  //Прелюдие как у всех модулей
  $sysObject = 'text_block::modify::'.$blockinfo[id];
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  //Содержание
  $content = sysBlockGetVar ($blockinfo['id'], 'content');
  $sysTpl->assign('content', $content);

  $result = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

function text_block_update($blockinfo)
  {
  //Забираем переменные совхода
  $content = sysVarCleanFromInput('content');
  sysBlockSetVar ($blockinfo['id'], 'content', $content);
  //Очищаем кеш шаблонов
  $sysTpl = new sysTpl;
  $sysTpl->clear_cache(null, 'text_block::display');
  }

?>