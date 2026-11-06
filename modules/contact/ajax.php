<?php
/*
 * Редирект
*/
function contact_ajax_main()
  {
  sysRedirect(sysModURL('contact','admin','main'));
  }

/*
 * Отправка сообшения
*/
function contact_ajax_create()
{
	//Забираем переменные со входа
	$contact_table = sysDBGetTable('contact');
	$contact_column = sysDBGetColumns($contact_table);
	$contact_indate = sysDBCleanFromInput ($contact_column);
	
	//Загружаем модуль
	sysModClassLoad ('contact');
	$contact = new contact;
	
	$captcha_string = sysVarCleanFromInput('captcha_string');

	//Строим имя обьекта
	$sysObject = 'contact::ajax::create';

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;


	//Проверяем введенный текст
	$user_id = sysSessionGetVar('user_id');
	$err_no = 0;

	//1. Captcha
	if ($user_id<1 && sysModGetVar('contact', 'captcha_enabled'))  	{
		$ses_contact_captcha_string = sysSessionGetVar('contact_captcha_string');
		if ($ses_contact_captcha_string!=$captcha_string)
			$err_no=1; //Неверный код безопасности
  	}

	//Имя под которым представлялся гость
	if ($user_id < 1) 
		sysSessionSetVar('guest_displayname', $contact_indate['user_displayname']);
		
	// определяем, кому по дефолту отправлять
	if (!$contact_indate['rec_id']) 
		$contact_indate['rec_id']= sysModGetVar('contact', 'rec_default')-1;

	//Создаем сообшение
	if (!$err_no) {
		$contact->create($contact_indate);
	
		//Готовим письмо
		$sysMailTplWay = sysTplWay ('contact::mail::message');
		$sysMailTpl = new sysTpl;
		$sysMailTpl->caching = false;
		$sysMailTpl->assign ($contact_indate);

		$mailbody = $sysMailTpl->fetch($sysMailTplWay, 'contact::mail::message');
		$subject = 'Обратная связь, '.$contact_indate['contact_displayname'];
		
		sysExtLibLoad();
	    $rec_list = unserialize(sysModGetVar('contact', 'rec_list'));

		$mail_list = $rec_list[$contact_indate['rec_id']]['rec_emails'];
		if (!$mail_list)
			$mail_list = sysModGetVar('SYS_config', 'adminmail');

			sysMail($mail_list, $subject, $mailbody, true);
		  	
	}

	//Инициализируем систему шаблонов
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject);
	$sysTpl->caching = false;

	//Рендерим
	$sysTpl->assign('preview', $preview);
	$sysTpl->assign('err_no', $err_no);

	//Пошел вывод переменных
	$sysTpl->assign('email_notify_enable',     sysModGetVar('contact', 'email_notify_enable'));
	$sysTpl->assign('captcha_enabled',          sysModGetVar('contact', 'captcha_enabled'));
	$sysTpl->assign('before_send_text', 			 sysModGetVar('contact', 'before_send_text'));
	$sysTpl->assign('after_send_text', 				 sysModGetVar('contact', 'after_send_text'));

	$rec_list = sysModGetVar('contact', 'rec_list');
	$sysTpl->assign('rec_list', 	$rec_list);
	$sysTpl->assign('curuser_id',$user_id);

	//Вывод результатов
	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	echo $result['content']; exit;
	return $result;
}

/*
 * Показ картинки кипчи
*/
function contact_ajax_captcha_view()
  {
  //Проверка на доступ
  $sysObject = 'contact::ajax::captcha_view';
  if (!sysSecAuthAction($sysObject, ACCESS_ADD)) return;

	//Рисуем капчу
  sysExtLibLoad('captcha');
	$captcha = new sysCaptcha();
	$captcha -> display();

	//Кладем в переменную сессии нарисованные букавки
	sysSessionSetVar('contact_captcha_string',$captcha -> getString());

  sysTerminate;
  exit;
  };
  
  /*
 * 
*/
function contact_ajax_form()
{
	$sysObject = 'contact::ajax::form';
	
	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;
	
	//Работа с sysTpl
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject);
	$sysTpl->caching = false;
	
	//Вывод результатов
	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject);
	$result['browsein'] =& $browsein;
	return $result;
  
}


?>