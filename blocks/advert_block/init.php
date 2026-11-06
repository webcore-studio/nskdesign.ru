<?php

function advert_block_add($blockinfo)
  {
  //Добовляем шаблон, если нету еше
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;

  $sysObject = 'advert_block::display';
  $sysModTpl = sysTplWay ($sysObject);

  if (empty ($sysModTpl))
    {
    $SYS_themes->tpl_add (array('tpl_compname'=>'advert_block',
                               'tpl_name'=>'block_display.tpl',
                               'tpl_pattern'=>$sysObject,
                               'tpl_description'=>'Вывод содержания блока'));
    };

  //Добовляем шаблон, если нету еше
  $sysObject = 'advert_block::modify';
  $sysModTpl = sysTplWay ($sysObject);
  if (empty ($sysModTpl))
    {
    $SYS_themes->tpl_add (array('tpl_compname'=>'advert_block',
                               'tpl_name'=>'block_modify.tpl',
                               'tpl_pattern'=>$sysObject,
                               'tpl_description'=>'Редактирование содержания блока'));
    };


  return true;
  }

function advert_block_delete($blockinfo)
  {
  return true;
  }

function advert_block_activate($blockinfo)
  {
  sysBlockSetVar ($blockinfo['id'], 'place', '0');

  }
  
function advert_block_deactivate($blockinfo)
  {
  return true;
  }

function advert_block_modify($blockinfo)
  {
  //Прелюдие как у всех модулей
  $sysObject = 'advert_block::modify::'.$blockinfo[id];
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  $place = sysBlockGetVar ($blockinfo['id'],'place');

	if ((empty ($place )) && (!is_numeric ($place )))   {
		$place = 0;
	}

  sysBlockSetVar ($blockinfo['id'], 'place ', $place );

  //Содержание
  $content = sysBlockGetVar ($blockinfo['id'], 'content');
  
  $sysTpl->assign('content', $content);
  $sysTpl->assign('place', $place);

  $result = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
return true;
  }

function advert_block_update($blockinfo)
  {
  //Забираем переменные совхода
   $place  = sysVarCleanFromInput('place');
  //Кеш будет тормазить низя меньше 1
  /*if ($block_ttl<1)  {
	$block_ttl=1;
  };*/
  sysBlockSetVar ($blockinfo['id'], 'place', $place);
    //Очищаем кеш шаблонов
  $sysTpl = new sysTpl;
  $sysTpl->clear_cache(null, 'advert_block::display');
  return true;
  }

?>