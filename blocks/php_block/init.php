<?php

function php_block_add($blockinfo)
  {
  //Добовляем шаблон, если нету еше
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;

  $sysObject = 'php_block::display';
  $sysModTpl = sysTplWay ($sysObject);

  if (empty ($sysModTpl))
    {
    $SYS_themes->tpl_add (array('tpl_compname'=>'php_block',
                                'tpl_name'=>'block_display.tpl',
                                'tpl_pattern'=>$sysObject,
                                'tpl_description'=>'Вывод содержания блока'));
    };

  //Добовляем шаблон, если нету еше
  $sysObject = 'php_block::modify';
  $sysModTpl = sysTplWay ($sysObject);
  if (empty ($sysModTpl))
    {
    $SYS_themes->tpl_add (array('tpl_compname'=>'php_block',
                                'tpl_name'=>'block_modify.tpl',
                                'tpl_pattern'=>$sysObject,
                                'tpl_description'=>'Редактирование содержания блока'));
    };

  return true;
  }

function php_block_delete($blockinfo)
  {
  return true;
  }

function php_block_activate($blockinfo)
  {
  return true;
  }

function php_block_deactivate($blockinfo)
  {
  return true;
  }

function php_block_modify($blockinfo)
  {
  //Прелюдие как у всех модулей
  $sysObject = 'php_block::modify::'.$blockinfo[id];
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  //Содержание
  $content = sysBlockGetVar ($blockinfo['id'], 'content');
  $sysTpl->assign('content', $content);

  $result = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

function php_block_update($blockinfo)
  {
  //Забираем переменные совхода
  //Переменная не очишенная, может содержать JAVA !!!!
  $content = sysVarGetFromInput('content'); //Лутчше не делайте так
  $content = strtr($content,array('print'=>'return','echo'=>'return'));
  sysBlockSetVar ($blockinfo['id'], 'content', $content);
  //Очищаем кеш шаблонов
  $sysTpl = new sysTpl;
  $sysTpl->clear_cache(null, 'php_block::display');
  return true;
  }

?>
