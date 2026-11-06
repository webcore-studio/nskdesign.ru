<?php

function html_block_add($blockinfo)
{
	//Добавляем шаблон, если нет еше
	sysModClassLoad ('SYS_themes');
	$SYS_themes = new SYS_themes;

	$sysObject = 'html_block::display';
	$sysModTpl = sysTplWay ($sysObject);

	if (empty ($sysModTpl))  {
		$SYS_themes->tpl_add (array('tpl_compname'=>'html_block',
                                'tpl_name'=>'block_display.tpl',
                                'tpl_pattern'=>$sysObject,
                                'tpl_description'=>'Вывод содержания блока'));
    }

	//Добавляем шаблон, если нет еше
	$sysObject = 'html_block::modify';
	$sysModTpl = sysTplWay ($sysObject);
	if (empty ($sysModTpl))    {
		$SYS_themes->tpl_add (array('tpl_compname'=>'html_block',
                                'tpl_name'=>'block_modify.tpl',
                                'tpl_pattern'=>$sysObject,
                                'tpl_description'=>'Редактирование содержания блока'));
    }

	return true;
}

function html_block_delete($blockinfo)
{
	return true;
}

function html_block_activate($blockinfo)
{
	return true;
}

function html_block_deactivate($blockinfo)
{
	return true;
}

function html_block_modify($blockinfo)
{
	$wysiwyg = sysVarGetFromInput('wysiwyg');
	$content = sysBlockGetVar ($blockinfo['id'], 'content');
  
	//Прелюдие как у всех модулей
	$sysObject = 'html_block::modify::'.$blockinfo[id];
	$sysModTpl=sysTplWay ($sysObject);
	$sysTpl = new sysTpl;
	$sysTpl->caching = false;

	//Содержание
  
	if (!isset($wysiwyg))  
		$wysiwyg = sysBlockGetVar ($blockinfo['id'], 'wysiwyg');
	
	$sysTpl->assign('content', $content);
	$sysTpl->assign('wysiwyg', $wysiwyg);
	$sysTpl->assign('id', $blockinfo['id']);

	$result = $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

function html_block_update($blockinfo)
{
	//Забираем переменные совхода
	//Переменная не очишенная, может содержать JAVA !!!!
	$content = sysVarGetFromInput('content'); //Лутчше не делайте так
	$wysiwyg = sysVarGetFromInput('wysiwyg'); 
	sysBlockSetVar ($blockinfo['id'], 'content', $content);
	sysBlockSetVar ($blockinfo['id'], 'wysiwyg', $wysiwyg);
	//Очищаем кеш шаблонов
	$sysTpl = new sysTpl;
	$sysTpl->clear_cache(null, 'html_block::display');
	return true;
}

?>