<?php
/*
 * Главная админская страница
*/
function contact_admin_main()
{
	$sysObject = 'contact::admin::main';

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

	//Работа с sysTpl
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject);
	$sysTpl->caching = false;

	//Выводим список последних сообшений
	sysModClassLoad ('contact');
	$contact = new contact;
	
	$contact_list = $contact->get_list();
	$sysTpl->assign('contact_list', $contact_list);

	//BrowseIn
	$browsein = array();
	$browsein[] = array ('url'=>sysModURL('contact','user','main'),
                       'displayname'=>'Обратная связь');
	$browsein[]=array ('url'=>sysModURL('contact','admin','main'),
                     'displayname'=>'Администрирование');
	$browsein[]=array ('url'=>sysModURL('contact','admin','main'),
                     'displayname'=>'Последние сообщения');
	$sysTpl->assign('browsein', $browsein);

	//Вывод результатов
	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject);
	$result['browsein'] =& $browsein;
	return $result;
}

/*
 * Создание нового комментария
*/
function contact_admin_create()
  {
  //Забираем переменные со входа
  $contact_table = sysDBGetTable('contact');
  $contact_column = sysDBGetColumns($contact_table);
  $contact_indate = sysDBCleanFromInput ($contact_column);

  $preview = sysVarCleanFromInput('preview');

	//Проверка разрешеныли комментарии
	if (!sysModGetVar('contact', 'contact_enabled')) return;

	// Проверка наличия модулей
  if (!sysModAvailable ('contact')) return;
  if (!sysModAvailable ($contact_indate['comm_module'])) return;

  //Проверки
  $contact_indate['comm_key_a'] = (int)$contact_indate['comm_key_a'];
  $contact_indate['comm_key_b'] = (int)$contact_indate['comm_key_b'];
  if ($contact_indate['comm_key_a']<1 && $contact_indate['comm_key_b']<1) return;

	//Строим имя обьекта
  $sysObject = 'contact::admin::create::'.$contact_indate['comm_module'];
  if ($contact_indate['comm_key_a']) $sysObject.='::'.$contact_indate['comm_key_a'];
  if ($contact_indate['comm_key_b']) $sysObject.='::'.$contact_indate['comm_key_b'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADD)) return;

  //Дополняем информацию о комменте
  $contact_indate['user_id'] = sysSessionGetVar('user_id');
  $contact_indate['comm_addtime'] = time();
  $contact_indate['comm_modtime'] = $contact_indate['comm_addtime'];
  $contact_indate['comm_modcount'] = 0;
  $contact_indate['comm_from_ip'] = sysUserIpAddr();
  if ($contact_indate['user_id']>0) unset($contact_indate['user_displayname']); //Юнсетим имя если есть ид пользователя

	//загружаем модулю которому адресован коммент
  sysModClassLoad ($contact_indate['comm_module']);
  $tomodule = new $contact_indate['comm_module'];

	//Пробуем передать управление модулю которому адресован коммент для пред обработки
	$prefilter_func = $contact_indate['comm_module'].'_contact_new';
  if (function_exists($prefilter_func)) $contact_indate = $prefilter_func($contact_indate);

  //Создаем комментарий
  if (!$preview)
    {
    sysModClassLoad ('contact');
    $contact = new contact;
    $contact_indate['id'] = $contact->create($contact_indate);

  	//Пробуем передать управление модулю которому адресован коммент для пост действий
  	$postfilter_func = $contact_indate['comm_module'].'_contact_create';
    if (function_exists($postfilter_func)) $postfilter_func($contact_indate);
    }

  //Инициализируем систему шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Рендерим
  $sysTpl->assign($contact_indate);
  $sysTpl->assign('preview', $preview);
  $content =& $sysTpl->fetch($sysModTpl,$sysObject);
  echo $content;

	sysTerminate();
	exit;

  return;
  }

/*
 * Редактирование отзыва
*/
function contact_admin_modify()
  {
  //Забрали переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('contact_admin_modify', BAD_PARAM, 'Id not numeric'.$id);

  //Информация о комментарии
  sysModClassLoad ('contact');
  $contact = new contact;
  $contact_dbdate = $contact->get_info($id);

  //Проверка разрешеныли комментарии
  if (!sysModGetVar('contact', 'contact_enabled')) return;

  // Проверка наличия модулей
  if (!sysModAvailable ('contact')) return;
  if (!sysModAvailable ($contact_dbdate['comm_module'])) return;

  //Строим имя обьекта
  $sysObject = 'contact::admin::modify::'.$contact_dbdate['comm_module'];
  if ($contact_dbdate['comm_key_a']) $sysObject.='::'.$contact_dbdate['comm_key_a'];
  if ($contact_dbdate['comm_key_b']) $sysObject.='::'.$contact_dbdate['comm_key_b'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $contact_dbdate['user_id'])) return;

  //Инициализируем систему шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Уровень доступа у текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  //Ид текущего пользователя
  $sysTpl->assign('curuser_id', sysSessionGetVar('user_id'));

  //Рендерим
  $sysTpl->assign($contact_dbdate);
  $content =& $sysTpl->fetch($sysModTpl,$sysObject);
  echo $content;

	sysTerminate();
	exit;

  return;
  }

/*
 * Редактирование нового комментария
*/
function contact_admin_update()
  {
  //Забираем переменные со входа
  $contact_table = sysDBGetTable('contact');
  $contact_column = sysDBGetColumns($contact_table);
  $contact_indate = sysDBCleanFromInput ($contact_column);
  if (!is_numeric ($contact_indate['id'])) sysException ('contact_admin_update', BAD_PARAM, 'Id not numeric'.$contact_indate['id']);

  $preview = sysVarCleanFromInput('preview');

  //Информация о комментарии
  sysModClassLoad ('contact');
  $contact = new contact;
  $contact_dbdate = $contact->get_info($contact_indate['id']);

	//Проверка разрешеныли комментарии
	if (!sysModGetVar('contact', 'contact_enabled')) return;

	// Проверка наличия модулей
  if (!sysModAvailable ('contact')) return;
  if (!sysModAvailable ($contact_dbdate['comm_module'])) return;

	//Строим имя обьекта
  $sysObject = 'contact::admin::update::'.$contact_dbdate['comm_module'];
  if ($contact_dbdate['comm_key_a']) $sysObject.='::'.$contact_dbdate['comm_key_a'];
  if ($contact_dbdate['comm_key_b']) $sysObject.='::'.$contact_dbdate['comm_key_b'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $contact_dbdate['user_id'])) return;

  //Дополняем информацию о комменте
  $contact_dbdate['comm_modtime'] = time();
  $contact_dbdate['comm_modcount'] = $contact_dbdate['comm_modcount']+1;
  $contact_dbdate['comm_from_ip'] = sysUserIpAddr();

  $contact_dbdate['comm_displayname'] = $contact_indate['comm_displayname'];
  $contact_dbdate['comm_content'] = $contact_indate['comm_content'];

	//загружаем модулю которому адресован коммент
  sysModClassLoad ($contact_dbdate['comm_module']);
  $tomodule = new $contact_dbdate['comm_module'];

	//Пробуем передать управление модулю которому адресован коммент для пред обработки
	$prefilter_func = $contact_dbdate['comm_module'].'_contact_modify';
  if (function_exists($prefilter_func)) $contact_dbdate = $prefilter_func($contact_dbdate);

  //Создаем комментарий
  if (!$preview)
    {
    sysModClassLoad ('contact');
    $contact = new contact;
    $contact->update($contact_dbdate);

  	//Пробуем передать управление модулю которому адресован коммент для пост действий
  	$postfilter_func = $contact_dbdate['comm_module'].'_contact_update';
    if (function_exists($postfilter_func)) $postfilter_func($contact_dbdate);
    }

  //Инициализируем систему шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Рендерим
  $sysTpl->assign($contact_dbdate);
  $sysTpl->assign('preview', $preview);
  $content =& $sysTpl->fetch($sysModTpl,$sysObject);
  echo $content;

	sysTerminate();
	exit;

  return;
  }

/*
 * Удаление отзыва
*/
function contact_admin_delete()
  {
  //Забрали переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('contact_admin_delete', BAD_PARAM, 'Id not numeric'.$id);

  //Информация о комментарии
  sysModClassLoad ('contact');
  $contact = new contact;
  $contact_dbdate = $contact->get_info($id);

  //Проверка разрешеныли комментарии
  if (!sysModGetVar('contact', 'contact_enabled')) return;

  // Проверка наличия модулей
  if (!sysModAvailable ('contact')) return;
  if (!sysModAvailable ($contact_dbdate['comm_module'])) return;

  //Строим имя обьекта
  $sysObject = 'contact::admin::delete::'.$contact_dbdate['comm_module'];
  if ($contact_dbdate['comm_key_a']) $sysObject.='::'.$contact_dbdate['comm_key_a'];
  if ($contact_dbdate['comm_key_b']) $sysObject.='::'.$contact_dbdate['comm_key_b'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_DELETE, $contact_dbdate['user_id'])) return;

  //загружаем модулю которому адресован коммент
  sysModClassLoad ($contact_dbdate['comm_module']);
  $tomodule = new $contact_dbdate['comm_module'];

  //Пробуем передать управление модулю которому адресован коммент для пред обработки
  $prefilter_func = $contact_dbdate['comm_module'].'_contact_confirm_delete';
  if (function_exists($prefilter_func)) $prefilter_func($contact_dbdate);

  //Удаляем комментарий
  $contact->delete($id);

  //Пробуем передать управление модулю которому адресован коммент для пост действий
  $postfilter_func = $contact_dbdate['comm_module'].'_contact_delete';
  if (function_exists($postfilter_func)) $postfilter_func($contact_dbdate);

  //Инициализируем систему шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Рендерим
  $sysTpl->assign($contact_dbdate);
  $content =& $sysTpl->fetch($sysModTpl,$sysObject);
  echo $content;

	sysTerminate();
	exit;

  return;
  }

/*
 * Конфигуриррование настроек модуля
*/
function contact_admin_config_modify()
{
	//Прелюдие как у всех модулей
	$sysObject = 'contact::admin::config_modify';

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

	//Шаблоны
	$sysTpl = new sysTpl;
	$sysModTpl=sysTplWay ($sysObject);
	$sysTpl->caching = false;

	//Пошел вывод переменных
	$sysTpl->assign('email_notify_enable',		 sysModGetVar('contact', 'email_notify_enable'));
	$sysTpl->assign('captcha_enabled', 				 sysModGetVar('contact', 'captcha_enabled'));
	$sysTpl->assign('before_send_text', 			 sysModGetVar('contact', 'before_send_text'));
	$sysTpl->assign('after_send_text', 				 sysModGetVar('contact', 'after_send_text'));


	$rec_list = unserialize(sysModGetVar('contact', 'rec_list'));
	if (empty($rec_list)) 
		$rec_list = array(	array('rec_name'=>'', 'rec_emails'=>''), 
						array('rec_name'=>'','rec_emails'=>''), 
						array('rec_name'=>'','rec_emails'=>''));
	$sysTpl->assign('rec_list', $rec_list);

	$rec_default = sysModGetVar('contact', 'rec_default');
	if (empty($rec_default) and !empty ($rec_list)) 
		$rec_default = 1;
  
	$sysTpl->assign('rec_default',$rec_default);

	//BrowseIn
	$browsein[]=array ('url'=>sysModURL('contact','user','main'),
                     'displayname'=>'Обратная связь');
	$browsein[]=array ('url'=>sysModURL('contact','admin','main'),
                     'displayname'=>'Администрирование');
	$browsein[]=array ('url'=>sysModURL('contact','admin','config_modify'),
                     'displayname'=>'Конфигурация');
	$sysTpl->assign('browsein', $browsein);

	//Возвращаем результат
	$result['object'] =& $sysObject;
	$result['browsein'] =& $browsein;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

/*
 * Сохраняем конфигурацию главных настроек
*/
function contact_admin_config_update()
{
	$sysObject = 'contact::admin::update_config';

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

	list ($email_notify_enable, $captcha_enabled, $before_send_text, $after_send_text, $rec_name, $rec_emails, $rec_default)=
		sysVarCleanFromInput('email_notify_enable', 'captcha_enabled', 'before_send_text','after_send_text', 'rec_name', 'rec_emails', 'rec_default');

	//Формируем таблицу получателей
	$rec_list = array();
	foreach ($rec_name as $key=>$value)
		if (!empty($value) and !empty($rec_emails[$key])) 
			$rec_list[]=array('rec_name'=>$value, 'rec_emails'=>$rec_emails[$key]);
	
	if (!empty($rec_list) and ($rec_default==0)) 
		$rec_default=1;

	sysModSetVar ('contact','email_notify_enable', $email_notify_enable);
	sysModSetVar ('contact','captcha_enabled', 		 $captcha_enabled);
	sysModSetVar ('contact','before_send_text',    $before_send_text);
	sysModSetVar ('contact','after_send_text',     $after_send_text);
	sysModSetVar ('contact','rec_list',     			 serialize($rec_list));
	sysModSetVar ('contact','rec_default',     			 $rec_default);

	sysShowMessage(sysModURL ('contact','admin','main'), 'Редактирование успешно произведено');
	return true;
}

?>