<?php

function contact_block_add($blockinfo)
{
	//Добовляем шаблон, если нету еше
	sysModClassLoad ('SYS_themes');
	$SYS_themes = new SYS_themes;

	$sysObject = 'contact_block::display';
	$sysModTpl = sysTplWay ($sysObject);

	if (empty ($sysModTpl))   {
		$SYS_themes->tpl_add (array('tpl_compname'=>'contact_block',
                                'tpl_name'=>'block_display.tpl',
                                'tpl_pattern'=>$sysObject,
                                'tpl_description'=>'Вывод содержания блока'));
    }

	//Добовляем шаблон, если нету еше
	$sysObject = 'contact_block::modify';
	$sysModTpl = sysTplWay ($sysObject);
	if (empty ($sysModTpl))   {
		$SYS_themes->tpl_add (array('tpl_compname'=>'contact_block',
                                'tpl_name'=>'block_modify.tpl',
                                'tpl_pattern'=>$sysObject,
                                'tpl_description'=>'Редактирование содержания блока'));
    }

	return true;
}

function contact_block_delete($blockinfo)
{
	return true;
}

function contact_block_activate($blockinfo)
{
	return true;
}

function contact_block_deactivate($blockinfo)
{
	return true;
}

function contact_block_modify($blockinfo)
{
	if (!sysModAvailable ('contact'))   {
		return true;
    }
	
	//Прелюдие как у всех модулей
	$sysObject = 'contact_block::modify::'.$blockinfo['id'];
	$sysModTpl = sysTplWay ($sysObject);
	$sysTpl = new sysTpl;
	$sysTpl->caching = 0;
  
	//Забираем переменные блока
	$show_to = sysBlockGetVar ($blockinfo['id'], 'show_to');
	$show_theme = sysBlockGetVar ($blockinfo['id'], 'show_theme');
	
	$sysTpl->assign('show_to', $show_to);
	$sysTpl->assign('show_theme', $show_theme);
	
	$result=$sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

function contact_block_update($blockinfo)
{
	//Забираем переменные совхода
	list ($show_to, $show_theme) =  sysVarCleanFromInput('show_to', 'show_theme');
	sysBlockSetVar ($blockinfo['id'], 'show_to', $show_to);
	sysBlockSetVar ($blockinfo['id'], 'show_theme', $show_theme);
	
	//Очищаем кеш шаблонов
	$sysTpl = new sysTpl;
	$sysTpl->clear_cache(null, 'contact_block::display');
	return true;
}

?>