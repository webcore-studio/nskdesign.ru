<?php
/*
 * Редирект
*/
function comment_ajax_main()
{
	sysRedirect(sysModURL('comment','user','main'));
}

/*
 * Список комментариев
*/
function comment_ajax_list($params=null)
{
	//Если не переданны параметры, собираем совхода
	if (empty($params))   {
		list ($params['comm_module'], $params['comm_key_a'], $params['comm_key_b']) = sysVarCleanFromInput('comm_module', 'comm_key_a', 'comm_key_b');
    }

	//Проверка разрешеныли комментарии
	if (!sysModGetVar('comment', 'comment_enabled')) return;

	// Проверка наличия модулей
	if (!sysModAvailable ('comment')) return;
	if (!sysModAvailable ($params['comm_module'])) return;

	//Проверки
	$params['comm_key_a'] = (int)$params['comm_key_a'];
	$params['comm_key_b'] = (int)$params['comm_key_b'];
	if ($params['comm_key_a'] < 1 && $params['comm_key_b'] < 1) return;
	if (!is_numeric ($params['page'])) $params['page'] = 1;

	//Проверка доступа
	$secObject = 'comment::ajax::list::'.$params['comm_module'];
	if ($params['comm_key_a']) $secObject.='::'.$params['comm_key_a'];
	if ($params['comm_key_b']) $secObject.='::'.$params['comm_key_b'];
	if (!sysSecAuthAction($secObject, ACCESS_READ)) return;

	//Загружаем класс тем
	$sysTpl = new sysTpl;
	$sysTpl->caching = false;

	//Смотрим может модуль переопределил шаблон
	$sysObject = $params['comm_module'].'::user::comment::list';
	if ($params['comm_key_a']) $sysObject.='::'.$params['comm_key_a'];
	if ($params['comm_key_b']) $sysObject.='::'.$params['comm_key_b'];
	$sysModTpl = sysTplWay ($sysObject);

	//Если нет - берем наш шаблон
	if (!$sysModTpl)   {
		$sysObject = $secObject;
		$sysModTpl = sysTplWay ($sysObject);
    }

	//Параметры сортировки
	if ($params['sort']!='addtime' && $params['sort']!='modtime')   {
		$params['sort'] = sysModGetVar('comment', 'sort_by');
		$params['asc'] =  sysModGetVar('comment', 'sort_asc');
    }

	//Комментов на страницу
	$comment_perpage = sysModGetVar('comment', 'comment_perpage');


	//Загружаем модуль работы с комментариями
	sysModClassLoad ('comment');
	$comment = new comment;

	//Список комментов
	$comment_list = $comment->get_list($params['comm_module'], $params['comm_key_a'], $params['comm_key_b'],
                                     $params['page'], $comment_perpage, $params['sort'], $params['asc']);


	//Уровень доступа у текущего пользователя
	$curuser_sec_level = sysSecGetLevel ($secObject);
	$sysTpl->assign('curuser_sec_level', $curuser_sec_level);

	//Ид текущего пользователя
	$sysTpl->assign('curuser_id', sysSessionGetVar('user_id'));

	//Реднерим
	$sysTpl->assign('comment_list', $comment_list);

	//Вывод результатов
	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

/*
 * Предпросмотер отзыва
*/
function comment_ajax_preview()
  {
    global $dbconn;
  //Забираем переменные со входа
  $comment_table = sysDBGetTable('comment');
  $comment_column = sysDBGetColumns($comment_table);
  $comment_indate = sysDBCleanFromInput ($comment_column);

  //Проверка разрешеныли комментарии
  if (!sysModGetVar('comment', 'comment_enabled')) return '';

  // Проверка наличия модулей
  if (!sysModAvailable ('comment')) return '';
  if (!sysModAvailable ($comment_indate['comm_module'])) return '';

  //Загружаем модуль работы с комментариями
  sysModClassLoad ('comment');
  $comment = new comment;

  //Декодируем utf8
  $comment_indate['user_displayname'] = $comment->utf8_urldecode($comment_indate['user_displayname']);
  $comment_indate['comm_displayname'] = $comment->utf8_urldecode($comment_indate['comm_displayname']);
  $comment_indate['comm_content'] = $comment->utf8_urldecode($comment_indate['comm_content']);

  //Проверки
  $comment_indate['comm_key_a'] = (int)$comment_indate['comm_key_a'];
  $comment_indate['comm_key_b'] = (int)$comment_indate['comm_key_b'];
  if ($comment_indate['comm_key_a']<1 && $comment_indate['comm_key_b']<1) return '';

  //Проверка на доступ
  $secObject = 'comment::ajax::preview::'.$comment_indate['comm_module'];
  if ($comment_indate['comm_key_a']) $secObject.='::'.$comment_indate['comm_key_a'];
  if ($comment_indate['comm_key_b']) $secObject.='::'.$comment_indate['comm_key_b'];
  if (!sysSecAuthAction($secObject, ACCESS_ADD)) return '';

  //Загружаем класс тем
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  //Смотрим может модуль переопределил шаблон
  $sysObject = $comment_indate['comm_module'].'::ajax::comment::preview';
  if ($comment_indate['comm_key_a']) $sysObject.='::'.$comment_indate['comm_key_a'];
  if ($comment_indate['comm_key_b']) $sysObject.='::'.$comment_indate['comm_key_b'];
  $sysModTpl = sysTplWay ($sysObject);

  //Если нет - берем наш шаблон
  if (!$sysModTpl)
    {
    $sysObject = $secObject;
    $sysModTpl = sysTplWay ($sysObject);
    }

  //Параметры в шаблон
  $sysTpl->assign($comment_indate);
  $curuser_id = sysSessionGetVar('user_id');
  $sysTpl->assign('curuser_id', $curuser_id);
  //Берем информацию о текущем пользователе
  if ($curuser_id>0)
    {
		//Выборка
    $users_table = sysDBGetTable ('users');
    $users_column = sysDBGetColumns($users_table);

    $sql = "SELECT
                   $users_column[id] as user_id, $users_column[user_name] as user_id_name,
                   $users_column[user_displayname] as user_id_displayname,
                   $users_column[user_regdate] as user_id_regdate,
                   $users_column[user_avatar_w] as user_id_avatar_w, $users_column[user_avatar_h] as user_id_avatar_h,
                   $users_column[user_avatar] as user_id_avatar, $users_column[user_rank] as user_id_rank
              FROM $users_table
                  WHERE $users_column[id] = '$curuser_id'";
    $mysql_result = $dbconn->query($sql);
    if (mysqli_connect_errno()!=0) sysException ('comment_ajax_preview', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
		$row = mysqli_fetch_assoc($mysql_result);
		$sysTpl->assign($row);
    mysqli_free_result($mysql_result);
    }

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }


/*
 * Создание отзыва
*/
function comment_ajax_new($params = null)
{
	//Забираем переменные
	if (empty($params))	{
		list ($params['comm_module'],$params['comm_key_a'],$params['comm_key_b']) = sysVarCleanFromInput('comm_module','comm_key_a','comm_key_b');
    }

    

	//Проверка разрешеныли комментарии
	if (!sysModGetVar('comment', 'comment_enabled')) return;

	// Проверка наличия модулей
	if (!sysModAvailable ('comment')) return;
	if (!sysModAvailable ($params['comm_module'])) return;

	//Проверки
	$params['comm_key_a'] = (int)$params['comm_key_a'];
	$params['comm_key_b'] = (int)$params['comm_key_b'];
	if ($params['comm_key_a'] < 1 && $params['comm_key_b'] < 1) return;

	//Проверка на доступ
	$secObject = 'comment::ajax::new::'.$params['comm_module'];
	if ($params['comm_key_a']) $secObject.='::'.$params['comm_key_a'];
	if ($params['comm_key_b']) $secObject.='::'.$params['comm_key_b'];
	if (!sysSecAuthAction($secObject, ACCESS_ADD)) return '';

  

	//Загружаем класс тем
	$sysTpl = new sysTpl;
	$sysTpl->caching = false;
  
	//Смотрим может модуль переопределил шаблон
	$sysObject = $params['comm_module'].'::admin::comment::new';
	if ($params['comm_key_a']) $sysObject.='::'.$params['comm_key_a'];
	if ($params['comm_key_b']) $sysObject.='::'.$params['comm_key_b'];
	$sysModTpl = sysTplWay ($sysObject);
  
	//Если нет - берем наш шаблон
	if (!$sysModTpl)   {
		$sysObject = $secObject;
		$sysModTpl = sysTplWay ($sysObject);
    }
    
	//Параметры в шаблон
	$sysTpl->assign($params);

	$user_id = sysSessionGetVar('user_id');
	$user_info = sysUserGetVars();
	$sysTpl->assign('curuser_id', $user_id);

	//Имя под которым представлялся гость
	if ($user_id < 1) 
		$sysTpl->assign('user_displayname', sysSessionGetVar('guest_displayname'));
	elseif ($user_info['user_displayname']) 
		$sysTpl->assign('user_displayname', $user_info['user_displayname']);
	else 
		$sysTpl->assign('user_displayname', $user_info['user_name']);

	//Настройки в шаблон
	$sysTpl->assign('captcha_enabled',sysModGetVar('comment', 'captcha_enabled'));
	$sysTpl->assign('smiles_enabled',sysModGetVar('comment', 'smiles_enabled'));


	//Вывод результатов
	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

/*
 * Создание нового комментария
*/
function comment_ajax_create()
{
	//Забираем переменные со входа
	$comment_table = sysDBGetTable('comment');
	$comment_column = sysDBGetColumns($comment_table);
	//$comment_indate = sysDBCleanFromInput ($comment_column);

	$comment_indate['user_displayname'] = sysVarCleanFromInput('user_displayname');
	$comment_indate['comm_content'] = sysVarCleanFromInput('comm_content');
	$comment_indate['comm_displayname'] = sysVarCleanFromInput('comm_displayname');
	
  $comment_indate['comm_displayname'] = sysVarCleanFromInput('comm_displayname');

  $comment_indate['rating_module'] = $comment_indate['comm_module'] = sysVarCleanFromInput('comm_module');
  $comment_indate['rating_key_a'] = $comment_indate['comm_key_a'] = sysVarCleanFromInput('comm_key_a');
	$comment_indate['rating_key_b'] = $comment_indate['comm_key_b'] = sysVarCleanFromInput('comm_key_b');
	
  $comment_indate['rating_ball'] = sysVarCleanFromInput('comment_rating_ball');
	
	$indata = $_POST;

  sys_log('create',LOG_INFO,'create comment_indate', $comment_indate, "comment");

  sys_log('create',LOG_INFO,'create indata', $indata, "comment");

	//$indata['user_displayname'] = sysVarCleanFromInput('user_displayname');
	//$indata['comm_content'] = sysVarCleanFromInput('comm_content');
	//$indata = sysDBCleanFromInput ($comment_column);

	list($preview, $captcha_string) = sysVarCleanFromInput('preview', 'captcha_string');

	//Загружаем модуль работы с комментариями
	sysModClassLoad ('comment');
	$comment = new comment;

    //Декодируем utf8
    $comment_indate['user_displayname'] = strip_tags($comment->utf8_urldecode($comment_indate['user_displayname']));
    $comment_indate['comm_displayname'] = strip_tags($comment->utf8_urldecode($comment_indate['comm_displayname']));
    $comment_indate['comm_content'] = strip_tags($comment->utf8_urldecode($comment_indate['comm_content']));
    $comment_indate['captcha_string'] = $comment->utf8_urldecode($captcha_string);
  
	//Строим имя обьекта
	$sysObject = 'comment::ajax::create::'.$comment_indate['comm_module'];
	if ($comment_indate['comm_key_a']) $sysObject.='::'.$comment_indate['comm_key_a'];
	if ($comment_indate['comm_key_b']) $sysObject.='::'.$comment_indate['comm_key_b'];

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_ADD)) return;

  

	//Проверяем введенный текст
	$user_id = sysSessionGetVar('user_id');
	$err_no = 0;

	//1. Captcha
	// if ($user_id<1 && sysModGetVar('comment', 'captcha_enabled'))
	if (sysModGetVar('comment', 'captcha_enabled') && $user_id < 1)  	{
		$ses_comment_captcha_string = sysSessionGetVar('comment_captcha_string');
		if ($ses_comment_captcha_string!=$captcha_string)
			$err_no=1; //Неверный код безопасности
  	}

	//Мин длина отзыва
	if (strlen($comment_indate['comm_content'])<sysModGetVar('comment', 'content_min_len'))   {
		$err_no=2; //Отзыв очень короткий
    }

	//Макс длина отзыва
	if (strlen($comment_indate['comm_content'])>sysModGetVar('comment', 'content_max_len'))   {
		$err_no = 3; //Отзыв очень длинный
    }

	$fields = array();
	foreach ($indata as $k=>$item)
		if (!isset($comment_indate[$k]))
			$fields[$k] = $item;
	if ($fields)
		$comment_indate['comm_fields'] = serialize($fields);

  sys_log('create',LOG_INFO,'Ошибки', $err_no, "comment");
		
	//Создаем комментарий если нет ощибок
	if (!$err_no)   {

    sys_log('create',LOG_INFO,'create', $comment_indate, "comment");

		$comment->create($comment_indate);
		sysSessionDelVar('comment_captcha_string');
    }
	
	//отправляем уведомление о создании комментария
	sysExtLibLoad();

	//Отправляем почту на ящик админа
	$sysMailTplWay = sysTplWay ('comment::mail::create');
	$sysMailTpl = new sysTpl;
	$sysMailTpl->caching = false;
      
	//Кладём в письмо инфу
	$sysMailTpl->assign ($indata);
	$mailbody = $sysMailTpl->fetch($sysMailTplWay, 'comment::mail::create');
	if ($indata['subject'])
		$subject = $indata['subject'];
	else
		$subject = 'Добавлен новый отзыв на сайте '.sysModGetVar('SYS_config', 'site_name');
		
	/*$order_mail = sysVarCleanFromInput('order_mail');
		
	if (!$order_mail) {
		$order_mail = array();
		$content_mail = sysModGetVar('content','order_mail');
		if ($content_mail && $content_mail!=' ')	
			$order_mail = explode(" ",$content_mail);
		if (count($order_mail) == 0)
			$order_mail[] = sysModGetVar('SYS_config','adminmail');
		if ($indata['order_dup'])
			$order_mail[] = $indata['order_dup'];
	}

	$res = sysMail($order_mail, $subject, $mailbody, true, true); 
*/
	//Имя под которым представлялся гость
	if ($user_id < 1) 
		sysSessionSetVar('guest_displayname', $comment_indate['user_displayname']);

	//Инициализируем систему шаблонов
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject);
	$sysTpl->caching = false;

	//Рендерим
	$sysTpl->assign($comment_indate);
	$sysTpl->assign('preview', $preview);
	$sysTpl->assign('err_no', $err_no);

	//Вывод результатов
	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

/*
 * Редактирование отзыва
*/
function comment_ajax_modify()
  {
  //Забрали переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('comment_ajax_modify', BAD_PARAM, 'Id not numeric'.$id);

  //Информация о комментарии
  sysModClassLoad ('comment');
  $comment = new comment;
  $comment_dbdate = $comment->get_info($id);

  //Проверка разрешеныли комментарии
  if (!sysModGetVar('comment', 'comment_enabled')) return;

  // Проверка наличия модулей
  if (!sysModAvailable ('comment')) return;
  if (!sysModAvailable ($comment_dbdate['comm_module'])) return;

  //Строим имя обьекта
  $sysObject = 'comment::ajax::modify::'.$comment_dbdate['comm_module'];
  if ($comment_dbdate['comm_key_a']) $sysObject.='::'.$comment_dbdate['comm_key_a'];
  if ($comment_dbdate['comm_key_b']) $sysObject.='::'.$comment_dbdate['comm_key_b'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $comment_dbdate['user_id'])) return;

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
  $sysTpl->assign($comment_dbdate);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Редактирование нового комментария
*/
function comment_ajax_update()
  {
  //Забираем переменные со входа
  $comment_table = sysDBGetTable('comment');
  $comment_column = sysDBGetColumns($comment_table);
  $comment_indate = sysDBCleanFromInput ($comment_column);
  if (!is_numeric ($comment_indate['id'])) sysException ('comment_ajax_update', BAD_PARAM, 'Id not numeric'.$comment_indate['id']);

  $preview = sysVarCleanFromInput('preview');

  //Загружаем модуль работы с комментариями
  sysModClassLoad ('comment');
  $comment = new comment;

  //Информация о комментарии
  $comment_dbdate = $comment->get_info($comment_indate['id']);


  //Декодируем utf8
  $comment_indate['user_displayname'] = $comment->utf8_urldecode($comment_indate['user_displayname'], 'utf-8');
  $comment_indate['comm_displayname'] = $comment->utf8_urldecode($comment_indate['comm_displayname'], 'utf-8');
 $comment_indate['comm_content'] = $comment->utf8_urldecode($comment_indate['comm_content'], 'utf-8');
  

	//Строим имя обьекта
  $sysObject = 'comment::ajax::update::'.$comment_dbdate['comm_module'];
  if ($comment_dbdate['comm_key_a']) $sysObject.='::'.$comment_dbdate['comm_key_a'];
  if ($comment_dbdate['comm_key_b']) $sysObject.='::'.$comment_dbdate['comm_key_b'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $comment_dbdate['user_id'])) return;

  //Создаем комментарий
  if (!$preview)
    {
    $comment->update($comment_indate);
    }

  //Инициализируем систему шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Рендерим
  $sysTpl->assign($comment_dbdate);
  $sysTpl->assign('preview', $preview);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Удаление отзыва
*/
function comment_ajax_delete()
  {
  //Забрали переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('comment_ajax_delete', BAD_PARAM, 'Id not numeric'.$id);

  //Информация о комментарии
  sysModClassLoad ('comment');
  $comment = new comment;
  $comment_dbdate = $comment->get_info($id);

  //Строим имя обьекта
  $sysObject = 'comment::ajax::delete::'.$comment_dbdate['comm_module'];
  if ($comment_dbdate['comm_key_a']) $sysObject.='::'.$comment_dbdate['comm_key_a'];
  if ($comment_dbdate['comm_key_b']) $sysObject.='::'.$comment_dbdate['comm_key_b'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_DELETE, $comment_dbdate['user_id'])) return;

  //Удаляем комментарий
  $comment->delete($id);

  //Инициализируем систему шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Рендерим
  $sysTpl->assign($comment_dbdate);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Показ картинки кипчи
*/
function comment_ajax_captcha_view()
  {
  //Проверка на доступ
  $sysObject = 'comment::ajax::captcha_view';
  if (!sysSecAuthAction($sysObject, ACCESS_ADD)) return;

	//Рисуем капчу
  sysExtLibLoad('captcha');
	$captcha = new sysCaptcha();
	$captcha -> display();

	//Кладем в переменную сессии нарисованные букавки
	sysSessionSetVar('comment_captcha_string',$captcha -> getString());

  sysTerminate;
  exit;
  };
  
  
/*
 * Количество комментариев
*/
function comment_ajax_count($params=null)
  {
  //Если не переданны параметры, собираем совхода
  if (empty($params))
    {
    list ($params['comm_module'],$params['comm_key_a'],$params['comm_key_b'])
      = sysVarCleanFromInput('comm_module','comm_key_a','comm_key_b');
    }

  //Проверка разрешеныли комментарии
  if (!sysModGetVar('comment', 'comment_enabled')) return'';

  // Проверка наличия модулей
  if (!sysModAvailable ('comment')) return '';
  if (!sysModAvailable ($params['comm_module'])) return '';


  
  //Проверки
  $params['comm_key_a'] = (int)$params['comm_key_a'];
  $params['comm_key_b'] = (int)$params['comm_key_b'];
  if ($params['comm_key_a']<1 && $params['comm_key_b']<1) return '';

  //Проверка доступа
  $secObject = 'comment::ajax::count::'.$params['comm_module'];
  if ($params['comm_key_a']) $secObject.='::'.$params['comm_key_a'];
  if ($params['comm_key_b']) $secObject.='::'.$params['comm_key_b'];
   if (!sysSecAuthAction($secObject, ACCESS_READ)) return '';

  //Загружаем класс тем
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  //Смотрим может модуль переопределил шаблон
  $sysObject = $params['comm_module'].'::user::comment::count';
  if ($params['comm_key_a']) $sysObject.='::'.$params['comm_key_a'];
  if ($params['comm_key_b']) $sysObject.='::'.$params['comm_key_b'];
  $sysModTpl = sysTplWay ($sysObject);

  //Если нет - берем наш шаблон
  if (!$sysModTpl)
    {
    $sysObject = $secObject;
    $sysModTpl = sysTplWay ($sysObject);
    }

 

  //Загружаем модуль работы с комментариями
  sysModClassLoad ('comment');
  $comment = new comment;

  //количество комментов
  $comment_count = $comment->get_count($params['comm_module'], $params['comm_key_a'], $params['comm_key_b']);

  //Уровень доступа у текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($secObject);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  //Ид текущего пользователя
  $sysTpl->assign('curuser_id', sysSessionGetVar('user_id'));

	//Реднерим
  $sysTpl->assign('comment_count', $comment_count);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }


?>