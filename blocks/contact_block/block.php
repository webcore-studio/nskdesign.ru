<?php

function contact_block_display(&$blockinfo)
{
	//Прелюдие как у всех блоков
	$sysObject = 'contact_block::display::'.$blockinfo['id'];
	
	if (!sysModAvailable ('contact'))
		return true;

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return true;
	
	//Работа с sysTpl
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject, $blockinfo['block_tpl']);
	$sysTpl->caching = false;
	
	$show_to = sysBlockGetVar ($blockinfo['id'], 'show_to');
	$show_theme = sysBlockGetVar ($blockinfo['id'], 'show_theme');
	
	$sysTpl->assign('show_to', $show_to);
	$sysTpl->assign('show_theme', $show_theme);

	$sysTpl->assign('email_notify_enable',     sysModGetVar('contact', 'email_notify_enable'));
	$sysTpl->assign('captcha_enabled',          sysModGetVar('contact', 'captcha_enabled'));
	$sysTpl->assign('before_send_text',        sysModGetVar('contact', 'before_send_text'));
	$sysTpl->assign('after_send_text',          sysModGetVar('contact', 'after_send_text'));
	$sysTpl->assign('rec_default',          sysModGetVar('contact', 'rec_default'));

	$rec_list = unserialize(sysModGetVar('contact', 'rec_list'));
	$sysTpl->assign('rec_list', $rec_list);

	//Ид текущего пользователя
	$sysTpl->assign('curuser_id', sysSessionGetVar('user_id'));

	$result['block_content'] = $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

?>