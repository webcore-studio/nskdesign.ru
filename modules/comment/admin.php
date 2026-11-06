<?php
/*
 * Главная админская страница
*/
function comment_admin_main()
  {
  sysRedirect(sysModURL('comment','user','main'));
  }

/*
 * Создание нового комментария
*/
function comment_admin_create()
  {
  //Забираем переменные со входа
  $comment_table = sysDBGetTable('comment');
  $comment_column = sysDBGetColumns($comment_table);
  $comment_indate = sysDBCleanFromInput ($comment_column);

  $preview = sysVarCleanFromInput('preview');

	//Проверка разрешеныли комментарии
	if (!sysModGetVar('comment', 'comment_enabled')) return;

	// Проверка наличия модулей
  if (!sysModAvailable ('comment')) return;
  if (!sysModAvailable ($comment_indate['comm_module'])) return;

  //Проверки
  $comment_indate['comm_key_a'] = (int)$comment_indate['comm_key_a'];
  $comment_indate['comm_key_b'] = (int)$comment_indate['comm_key_b'];
  if ($comment_indate['comm_key_a']<1 && $comment_indate['comm_key_b']<1) return;

	//Строим имя обьекта
  $sysObject = 'comment::admin::create::'.$comment_indate['comm_module'];
  if ($comment_indate['comm_key_a']) $sysObject.='::'.$comment_indate['comm_key_a'];
  if ($comment_indate['comm_key_b']) $sysObject.='::'.$comment_indate['comm_key_b'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADD)) return;

  //Дополняем информацию о комменте
  $comment_indate['user_id'] = sysSessionGetVar('user_id');
  $comment_indate['comm_addtime'] = time();
  $comment_indate['comm_modtime'] = $comment_indate['comm_addtime'];
  $comment_indate['comm_modcount'] = 0;
  $comment_indate['comm_from_ip'] = sysUserIpAddr();
  if ($comment_indate['user_id']>0) unset($comment_indate['user_displayname']); //Юнсетим имя если есть ид пользователя

	//загружаем модулю которому адресован коммент
  sysModClassLoad ($comment_indate['comm_module']);
  $tomodule = new $comment_indate['comm_module'];

	//Пробуем передать управление модулю которому адресован коммент для пред обработки
	$prefilter_func = $comment_indate['comm_module'].'_comment_new';
  if (function_exists($prefilter_func)) $comment_indate = $prefilter_func($comment_indate);

  //Создаем комментарий
  if (!$preview)
    {
    sysModClassLoad ('comment');
    $comment = new comment;
    $comment_indate['id'] = $comment->create($comment_indate);

  	//Пробуем передать управление модулю которому адресован коммент для пост действий
  	$postfilter_func = $comment_indate['comm_module'].'_comment_create';
    if (function_exists($postfilter_func)) $postfilter_func($comment_indate);
    }

  //Инициализируем систему шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Рендерим
  $sysTpl->assign($comment_indate);
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
function comment_admin_modify()
  {
  //Забрали переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('comment_admin_modify', BAD_PARAM, 'Id not numeric'.$id);

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
  $sysObject = 'comment::admin::modify::'.$comment_dbdate['comm_module'];
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
  $content =& $sysTpl->fetch($sysModTpl,$sysObject);
  echo $content;

	sysTerminate();
	exit;

  return;
  }

/*
 * Редактирование нового комментария
*/
function comment_admin_update()
  {
  //Забираем переменные со входа
  $comment_table = sysDBGetTable('comment');
  $comment_column = sysDBGetColumns($comment_table);
  $comment_indate = sysDBCleanFromInput ($comment_column);
  if (!is_numeric ($comment_indate['id'])) sysException ('comment_admin_update', BAD_PARAM, 'Id not numeric'.$comment_indate['id']);

  $preview = sysVarCleanFromInput('preview');

  //Информация о комментарии
  sysModClassLoad ('comment');
  $comment = new comment;
  $comment_dbdate = $comment->get_info($comment_indate['id']);

	//Проверка разрешеныли комментарии
	if (!sysModGetVar('comment', 'comment_enabled')) return;

	// Проверка наличия модулей
  if (!sysModAvailable ('comment')) return;
  if (!sysModAvailable ($comment_dbdate['comm_module'])) return;

	//Строим имя обьекта
  $sysObject = 'comment::admin::update::'.$comment_dbdate['comm_module'];
  if ($comment_dbdate['comm_key_a']) $sysObject.='::'.$comment_dbdate['comm_key_a'];
  if ($comment_dbdate['comm_key_b']) $sysObject.='::'.$comment_dbdate['comm_key_b'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $comment_dbdate['user_id'])) return;

  //Дополняем информацию о комменте
  $comment_dbdate['comm_modtime'] = time();
  $comment_dbdate['comm_modcount'] = $comment_dbdate['comm_modcount']+1;
  $comment_dbdate['comm_from_ip'] = sysUserIpAddr();

  $comment_dbdate['comm_displayname'] = $comment_indate['comm_displayname'];
  $comment_dbdate['comm_content'] = $comment_indate['comm_content'];

	//загружаем модулю которому адресован коммент
  sysModClassLoad ($comment_dbdate['comm_module']);
  $tomodule = new $comment_dbdate['comm_module'];

	//Пробуем передать управление модулю которому адресован коммент для пред обработки
	$prefilter_func = $comment_dbdate['comm_module'].'_comment_modify';
  if (function_exists($prefilter_func)) $comment_dbdate = $prefilter_func($comment_dbdate);

  //Создаем комментарий
  if (!$preview)
    {
    sysModClassLoad ('comment');
    $comment = new comment;
    $comment->update($comment_dbdate);

  	//Пробуем передать управление модулю которому адресован коммент для пост действий
  	$postfilter_func = $comment_dbdate['comm_module'].'_comment_update';
    if (function_exists($postfilter_func)) $postfilter_func($comment_dbdate);
    }

  //Инициализируем систему шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Рендерим
  $sysTpl->assign($comment_dbdate);
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
function comment_admin_delete()
  {
  //Забрали переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('comment_admin_delete', BAD_PARAM, 'Id not numeric'.$id);

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
  $sysObject = 'comment::admin::delete::'.$comment_dbdate['comm_module'];
  if ($comment_dbdate['comm_key_a']) $sysObject.='::'.$comment_dbdate['comm_key_a'];
  if ($comment_dbdate['comm_key_b']) $sysObject.='::'.$comment_dbdate['comm_key_b'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_DELETE, $comment_dbdate['user_id'])) return;

  //загружаем модулю которому адресован коммент
  sysModClassLoad ($comment_dbdate['comm_module']);
  $tomodule = new $comment_dbdate['comm_module'];

  //Пробуем передать управление модулю которому адресован коммент для пред обработки
  $prefilter_func = $comment_dbdate['comm_module'].'_comment_confirm_delete';
  if (function_exists($prefilter_func)) $prefilter_func($comment_dbdate);

  //Удаляем комментарий
  $comment->delete($id);

  //Пробуем передать управление модулю которому адресован коммент для пост действий
  $postfilter_func = $comment_dbdate['comm_module'].'_comment_delete';
  if (function_exists($postfilter_func)) $postfilter_func($comment_dbdate);

  //Инициализируем систему шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Рендерим
  $sysTpl->assign($comment_dbdate);
  $content =& $sysTpl->fetch($sysModTpl,$sysObject);
  echo $content;

	sysTerminate();
	exit;

  return;
  }

/*
 * Конфигуриррование настроек модуля
*/
function comment_admin_config_modify()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'comment::admin::config_modify';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

	//Шаблоны
  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Список групп
  sysModClassLoad ('SYS_groups');  $SYS_groups = new SYS_groups;
  $groups_list = $SYS_groups->get_list();
  $flat_groups_list=array();
  foreach ($groups_list as $group) $flat_groups_list[$group['id']] = $group['group_displayname'];
  $sysTpl->assign('groups_list', $flat_groups_list);

  //Text editor
  $texteditorlist=array ();
  $texteditorlist[0] = 'Нет';
  $texteditorlist[1] = 'WYSIWYG';
  $sysTpl->assign('texteditorlist', $texteditorlist);

  //Пошел вывод переменных
  $sysTpl->assign('comment_enabled', 			 sysModGetVar('comment', 'comment_enabled'));
  $sysTpl->assign('captcha_enabled', 			 sysModGetVar('comment', 'captcha_enabled'));
  $sysTpl->assign('smiles_enabled', 			 sysModGetVar('comment', 'smiles_enabled'));
  $sysTpl->assign('comment_perpage', 			 sysModGetVar('comment', 'comment_perpage'));

  $sysTpl->assign('content_min_len', 			 sysModGetVar('comment', 'content_min_len'));
  $sysTpl->assign('content_max_len',			 sysModGetVar('comment', 'content_max_len'));

  $sysTpl->assign('sort_by', 							 sysModGetVar('comment', 'sort_by'));
  $sysTpl->assign('sort_asc',							 sysModGetVar('comment', 'sort_asc'));

  $sysTpl->assign('flood_ip_time',				 sysModGetVar('comment', 'flood_ip_time'));
  $sysTpl->assign('email_notify_enable',	 sysModGetVar('comment', 'email_notify_enable'));
  $sysTpl->assign('email_notify_group',		 sysModGetVar('comment', 'email_notify_group'));
  $sysTpl->assign('use_texteditor',				 sysModGetVar('comment', 'use_texteditor'));

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('comment','user','main'),
                     'displayname'=>'Комментарии');
  $browsein[]=array ('url'=>sysModURL('comment','admin','config_modify'),
                     'displayname'=>'Конфигурация');
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  };

/*
 * Сохраняем конфигурацию главных настроек
*/
function comment_admin_config_update()
  {
  $sysObject = 'comment::admin::update_config';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  list ($comment_enabled, $captcha_enabled,$smiles_enabled, $comment_perpage, $content_min_len, $content_max_len, $sort_by, $sort_asc,
        $flood_ip_time, $email_notify_enable, $email_notify_group, $use_texteditor)=
      sysVarCleanFromInput('comment_enabled', 'captcha_enabled', 'smiles_enabled', 'comment_perpage', 'content_min_len', 'content_max_len', 'sort_by', 'sort_asc',
        'flood_ip_time', 'email_notify_enable', 'email_notify_group', 'use_texteditor');


  sysModSetVar ('comment','comment_enabled', $comment_enabled);
  sysModSetVar ('comment','captcha_enabled', $captcha_enabled);
  sysModSetVar ('comment','smiles_enabled', $smiles_enabled);
  sysModSetVar ('comment','comment_perpage', $comment_perpage);

  sysModSetVar ('comment','content_min_len', $content_min_len);
  sysModSetVar ('comment','content_max_len', $content_max_len);

  sysModSetVar ('comment','sort_by', $sort_by);
  sysModSetVar ('comment','sort_asc', $sort_asc);

  sysModSetVar ('comment','flood_ip_time', $flood_ip_time);
  sysModSetVar ('comment','email_notify_enable', $email_notify_enable);
  sysModSetVar ('comment','email_notify_group', $email_notify_group);
  sysModSetVar ('comment','use_texteditor', $use_texteditor);

  sysShowMessage(sysModURL ('comment','user','main'), 'Редактирование успешно произведено');
  return true;
  }

?>