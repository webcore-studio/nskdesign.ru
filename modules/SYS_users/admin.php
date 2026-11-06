<?php
/*
 * Главное админское меню.
*/
function SYS_users_admin_main()
  {
  $sysObject = 'SYS_users::admin::main';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;
sysLoadLang($sysTpl,'SYS_users');
  //Тело модуля
  //Отображаем список существкющих групп
  sysModClassLoad ('SYS_groups');
  $SYS_groups = new SYS_groups;
  $groups_list = $SYS_groups->get_list();

  if (!empty ($groups_list))
    $sysTpl->assign('groups_list', $groups_list);

  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('SYS_users','admin','main'),
                       'displayname'=>'Пользователи');
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

/*
 * Добавление пользователя в систему
*/
function SYS_users_admin_new()
  {
  $sysObject = 'SYS_users::admin::new';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Тело модуля

  //Отображаем список существкющих групп
  sysModClassLoad ('SYS_groups');
  $SYS_groups = new SYS_groups;
  $groups_list = $SYS_groups->get_list();

  if (!empty ($groups_list))
    {
    $sysTpl->assign('groups_list', $groups_list);
    };

  //группа по умолчанию
  $sysTpl->assign('default_group', sysModGetVar('SYS_config','default_group'));

  sysExtLibLoad();
  //Получаем список доступных времянных зон
  $tzinfo = sysGetTzList();
  $sysTpl->assign('tzinfo', $tzinfo);
  $sysTpl->assign('default_tz', sysModGetVar('SYS_config','default_tz'));

  //Получаем список доступных языков системы
  $lang_list = sysGetLangList();
  $sysTpl->assign('lang_list', $lang_list);

  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('SYS_users','admin','main'),
                       'displayname'=>'Пользователи');
  $browsein[] = array ('url'=>sysModURL('SYS_users','admin','new'),
                       'displayname'=>'Добавление пользователя');
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

/*
 * Просмотр пользователей в группе или результатов поиска
*/
function SYS_users_admin_view()
{
	$user_gid = sysVarCleanFromInput('user_gid');
	$orderby = sysVarCleanFromInput('orderby');
	$order_asc = sysVarCleanFromInput('order_asc');
	$page = sysVarCleanFromInput('page');
	$filters = sysVarCleanFromInput('filters');
	$output = sysVarCleanFromInput('output');

	if (!is_numeric ($page)|| !$page) 
		$page=1;
	$perpage = sysModGetVar('SYS_users','perpage');

	//Прелюдие как у всех модулей
	$sysObject = 'SYS_users::admin::view';
	if ($user_gid)
		$sysObject .='::'.$user_gid;
		
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

	$sysTpl = new sysTpl;
	$sysModTpl=sysTplWay ($sysObject);
	$sysTpl->caching = false;
	sysLoadLang($sysTpl,'SYS_users');

	//Тело модуля
	sysModClassLoad ('SYS_users');
	$SYS_users = new SYS_users;
	
   if ($output) {
	$users_list = $SYS_users->get_list($user_gid, 'user_regdate', 0, 1 ,65534, $filters);
	$users_output = array();
	foreach ($users_list as $user) {
		if ($user['user_surname'])
			$users_output[] = array("email"=>$user['user_email'], "name"=>$user['user_surname']." ".$user['user_firstname']." ".$user['user_secondname']);
		else
			$users_output[] = array("email"=>$user['user_email'], "name"=>$user['user_displayname']);
	}
		sysArrToCSV($users_output, "./files/users/user_list.csv");
  }
  else {
	$users_list = $SYS_users->get_list($user_gid,$orderby,$order_asc,$page,$perpage, $filters);
	$users_count = $SYS_users->get_count($user_gid, $filters);
}
	
	$users_count_na = $SYS_users->get_count($user_gid, 'not_active');
	
	if (!empty ($users_list))
		$sysTpl->assign('users_list', $users_list);
	$sysTpl->assign('users_count', $users_count);
	$sysTpl->assign('users_count_na', $users_count_na);
	
    $sysTpl->assign('orderby', $orderby);
    $sysTpl->assign('order_asc', $order_asc);
	$sysTpl->assign('user_gid', $user_gid);
	$sysTpl->assign('page_current', $page);
	$sysTpl->assign('filters', $filters);
	
	$page_total = ceil($users_count/$perpage);
	$sysTpl->assign('page_total', $page_total);

	//Список существкющих групп
	sysModClassLoad ('SYS_groups');
	$SYS_groups = new SYS_groups;
	$groups_list = $SYS_groups->get_list();
	$groups_sort = array();
	foreach ($groups_list as $k=>$group) {
		$groups_sort[$group['id']] = $group;
		if (!is_array($user_gid) && $group['id']==$user_gid) {
			$group_name[] = $group['group_displayname'];
			$groups_sort[$group['id']]['checked'] = 1;
		}
		elseif (is_array($user_gid) && in_array($group['id'], $user_gid)) {
			$group_name[] = $group['group_displayname'];
			$groups_sort[$group['id']]['checked'] = 1;
		}
	}
	$sysTpl->assign('groups_list', $groups_sort);
	if (!$group_name[1])
		$group_displayname = $group_name[0];
 	$sysTpl->assign('group_displayname', $group_displayname);
	
	//BrowseIn
	$browsein[]=array ('url'=>sysModURL('SYS_users','admin','main'),
                     'displayname'=>"Пользователи");
	if ($group_displayname) {
		$browsein[]=array ('url'=>sysModURL('SYS_users','admin','view',array ('user_gid'=>$gid)),
                     'displayname'=>"Просмотр группы \"$group_displayname\"");
	}
	$sysTpl->assign('browsein', $browsein);

	//Возвращаем результат
	$result['object'] =& $sysObject;
	$result['browsein'] =& $browsein;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
  }
/*
 * Создание нового пользователя
*/
function SYS_users_admin_create()
  {
  $user_gid = sysVarCleanFromInput('user_gid');
  if (!is_numeric ($user_gid)) sysException ('SYS_users_admin_create', BAD_PARAM, 'Gid not numeric');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_users::admin::create::'.$user_gid;

  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Получили список переданных переменных для базы данных
  $users_table = sysDBGetTable('users');
  $users_column =  sysDBGetColumns($users_table);
  $dbinvar = sysDBCleanFromInput($users_column);
  $dbinvar['user_pass']=md5($dbinvar['user_pass']);

  sysModClassLoad ('SYS_users');
  $SYS_users = new SYS_users;
  $SYS_users->create($dbinvar);

  sysShowMessage(sysModURL ('SYS_users','admin','view', array ('user_gid'=>$user_gid)), 'Пользователь добавлен');
  }

/*
 * Меню редактирования пользователей
*/
function SYS_users_admin_modify()
{
   	$user_vars = sysUserGetVars();
	$curuser_id= $user_vars['id'];
	  	
	//Забираем переменные
	$id = sysVarCleanFromInput('id');
  
	if (!$id)  {
		$id = $curuser_id;
	}
	
	if (!is_numeric ($id)) 	sysException ('SYS_users_admin_modify', BAD_PARAM, 'Id not numeric');

	//Информация о пользователе
	sysModClassLoad ('SYS_users');
	$SYS_users = new SYS_users;
	$dbdate = $SYS_users->get_info($id);

	//Прелюдие как у всех модулей
	$sysObject = 'SYS_users::admin::modify::'.$dbdate['user_gid'].'::'.$dbdate['id'];
	if (($id != $curuser_id && !sysSecAuthAction($sysObject, ACCESS_ADMIN)) || ($id == $curuser_id && !sysSecAuthAction($sysObject, ACCESS_EDIT)))	return;
	
	//Уровень доступа у текущего пользователя
    $curuser_sec_level = sysSecGetLevel ($sysObject);

	//Инициализация системы шаблонов
	$sysTpl = new sysTpl;
	$sysModTpl=sysTplWay ($sysObject);
	$sysTpl->caching = false;
	sysLoadLang($sysTpl,'SYS_users');

	//Тело модуля
	//Выводим параметры
	$sysTpl->assign($dbdate);

	//Получаем список доступных времянных зон
	sysExtLibLoad();
	$tzinfo = sysGetTzList();
	$sysTpl->assign('tzinfo', $tzinfo);

	//Список существкющих групп
	sysModClassLoad ('SYS_groups');
	$SYS_groups = new SYS_groups;
	$groups_list = $SYS_groups->get_list();
	$sysTpl->assign('groups_list', $groups_list);

	//Находим имя текущей группы
	foreach ($groups_list as $group)	{
		if ($group['id'] == $dbdate['user_gid'])  {
			$group_name = $group['group_displayname'];
			break;
		}
    }

	//Получаем список доступных языков системы
	$langlist = sysGetLangList();
	$sysTpl->assign('langlist', $langlist);
  
	if ($id != $curuser_id || $curuser_sec_level >= ACCESS_ADMIN)		{
		//BrowseIn
		$browsein[]=array ('url'=>sysModURL('SYS_users','admin','main'),
                     'displayname'=>"Пользователи");
		$browsein[]=array ('url'=>sysModURL('SYS_users','admin','view',array ('user_gid'=>$dbdate['user_gid'])),
                     'displayname'=>"Просмотр группы \"$group_name\"");
		$browsein[]=array ('url'=>sysModURL('SYS_users','admin','modify',array ('id'=>$dbdate['id'])),
	                     'displayname'=>"Редактирование пользователя ".$dbdate['user_name']);
    }
	else {
		$browsein[]=array ('url'=>sysModURL('SYS_users','admin','modify',array ('id'=>$dbdate['id'])),
	                     'displayname'=>"Редактирование пользователя ".$dbdate['user_name']);
	}
	$sysTpl->assign('browsein', $browsein);

	//Возвращаем результат
	$result['object'] =& $sysObject;
	$result['browsein'] =& $browsein;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

/*
 * Внесение изминений пользователя
*/
function SYS_users_admin_update()
{
 	$user_vars = sysUserGetVars();
	$curuser_id= $user_vars['id'];
	  	
	//Забираем переменные
	$id = sysVarCleanFromInput('id');
	if (!is_numeric ($id)) sysException ('SYS_users_admin_update', BAD_PARAM, 'Id not numeric');

	//Старая информация о пользователе
	sysModClassLoad ('SYS_users');
	$SYS_users = new SYS_users;
	$user_info = $SYS_users->get_info($id);


	//Проверка на доступ
	$sysObject = 'SYS_users::admin::update::'.$user_info['user_gid'].'::'.$user_info['id'];
	if ($id != $curuser_id) {
		if (!sysSecAuthAction($sysObject, ACCESS_ADMIN))   return;
	}
	else {
      if (!sysSecAuthAction($sysObject, ACCESS_EDIT))   return;
	}

	//Входные данные
	$users_table = sysDBGetTable('users');
	$users_column = sysDBGetColumns($users_table);
	$dbindata = sysDBCleanFromInput($users_column);
	if (!isset($dbindata['user_name']))
		$dbindata['user_name'] = $dbindata['user_email'];
	$send_info_mail = sysVarCleanFromInput('send_info_mail');

	//Внесение изменений
	$SYS_users->update($dbindata);
	
	//логируем
	sys_log('SYS_users_admin_update', LOG_INFO, 'Обновление данных пользователя', array('user_name'=>$user_vars['user_name'], 'id'=>$user_vars['id']), "SYS_users");
  
	if ($dbindata['user_state'] == 0 AND $user_info['user_state'] > 0) {
		$dbindata['user_active'] = true;
		$dbindata['user_actkey'] = '';
		if (!$dbindata['user_pass'])
			$dbindata['user_pass'] = $user_info['user_pass'];
		$SYS_users->sendRegOkMsg($dbindata);
	}

 
  
   if ($send_info_mail)   {
	   sysModClassLoad('SYS_groups');
	   $SYS_groups=new SYS_groups;
	   $group_info=$SYS_groups->get_info($dbindata['user_gid']);
	   sysExtLibLoad();
		//Готовим письмо
		$sysMailTplWay = sysTplWay ('SYS_users::mail::group_change');
		$sysMailTpl = new sysTpl;
		$sysMailTpl->caching = false;
		$sysMailTpl->assign ('user_info',$user_info);
		$sysMailTpl->assign ('group_info',$group_info);

		$mailbody = $sysMailTpl->fetch($sysMailTplWay, 'SYS_users::mail::group_change');
		$subject = 'Изменены ваши настройки на сайте '.sysModGetVar('SYS_config','site_url');
		sysMail($user_info['user_email'], $subject, $mailbody, true);
   }
  
	if ($id != $curuser_id)
		sysShowMessage(sysModURL ('SYS_users','admin','view',array('user_gid'=>$dbindata['user_gid'])), 'Измeнения сохранены');
	else
		sysShowMessage(sysModGetVar("SYS_config", "site_url"), 'Изменения сохранены');
  
}

/*
 * Подтверждение удаления группы
*/
function SYS_users_admin_confirm_delete()
  {
  //Забираем переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_users_admin_confirm_delete', BAD_PARAM, 'Id not numeric');

  //Информация о пользователе
  sysModClassLoad ('SYS_users');
  $SYS_users = new SYS_users;
  $dbdate = $SYS_users->get_info($id);

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_users::admin::confirm_delete::'.$dbdate['user_gid'].'::'.$dbdate['id'];
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;
 
  //Инициализация системы шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Тело модуля
  $sysTpl->assign($dbdate);

  //Список существкющих групп
  sysModClassLoad ('SYS_groups');
  $SYS_groups = new SYS_groups;
  $groups_list = $SYS_groups->get_list();

  //Находим имя текущей группы
  foreach ($groups_list as $group)
    {
    if ($group['id']==$dbdate['user_gid'])
      {
      $group_name = $group['group_displayname'];
      break;
      }
    }

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_users','admin','main'),
                     'displayname'=>"Пользователи");
  $browsein[]=array ('url'=>sysModURL('SYS_users','admin','view',array ('user_gid'=>$dbdate['user_gid'])),
                     'displayname'=>"Просмотр группы \"$group_name\"");
  $browsein[]=array ('url'=>sysModURL('SYS_users','admin','confirm_delete',array ('id'=>$dbdate['id'])),
                     'displayname'=>"Удаление пользователя \"$dbdate[user_name]\"");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Удаление пользователя :((
*/
function SYS_users_admin_delete()
  {
  //Забираем переменные со входа
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_users_admin_delete', BAD_PARAM, 'Id not numeric');

  //Информация о пользователе
  sysModClassLoad ('SYS_users');
  $SYS_users = new SYS_users;
  $dbdate = $SYS_users->get_info($id);

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_users::admin::delete::'.$dbdate['user_gid'].'::'.$dbdate['id'];
 if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $SYS_users->delete($id);

  sysShowMessage(sysModURL ('SYS_users','admin','view',array ('user_gid'=>$dbdate['user_gid'])), "Пользователь удален");
  return true;
  }

/*
 * Конфигуриррование настроек пользователей
*/
function SYS_users_admin_modifyconfig()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_users::admin::modifyconfig';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;
  
  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Список групп
  sysModClassLoad ('SYS_groups');
  $SYS_groups = new SYS_groups;
  $groups_list = $SYS_groups->get_list();
  $flatgroups_list=array();
  foreach ($groups_list as $group)
    {
    $flatgroups_list[$group['id']] = $group['group_displayname'];
    };

  $sysTpl->assign('groups_list', $flatgroups_list);
  //Пошел вывод переменных
  $sysTpl->assign('perpage', sysModGetVar('SYS_users','perpage'));
  $sysTpl->assign('need_captcha', sysModGetVar('SYS_users','need_captcha'));
  
  $sysTpl->assign('allow_register', sysModGetVar('SYS_users','allow_register'));
  $sysTpl->assign('allow_lostpass', sysModGetVar('SYS_users','allow_lostpass'));
  $sysTpl->assign('minpass', sysModGetVar('SYS_users','minpass'));
  $sysTpl->assign('minuname', sysModGetVar('SYS_users','minuname'));
  $sysTpl->assign('maxuname', sysModGetVar('SYS_users','maxuname'));
  $sysTpl->assign('allow_chgmail', sysModGetVar('SYS_users','allow_chgmail'));
  $sysTpl->assign('new_user_notify', sysModGetVar('SYS_users','new_user_notify'));
  $sysTpl->assign('new_user_notify_group', sysModGetVar('SYS_users','new_user_notify_group'));

  $sysTpl->assign('activation_types', array(
        0 => 'Требуется активация пользователем (рекомендуется)',
        1 => 'Автоматическая активация',
        2 => 'Активация администраторами'));

  $sysTpl->assign('activation_type', sysModGetVar('SYS_users','activation_type'));
  $sysTpl->assign('activation_group', sysModGetVar('SYS_users','activation_group'));

  $sysTpl->assign('uname_test_levels', array(
        0 => 'Жесткий (только буквы и цифры)',
        1 => 'Средний',
        2 => 'Минимальный'));

  $sysTpl->assign('uname_test_level', sysModGetVar('SYS_users','uname_test_level'));

  $sysTpl->assign('avatar_allow_upload', sysModGetVar('SYS_users','avatar_allow_upload'));
  $sysTpl->assign('avatar_minposts', sysModGetVar('SYS_users','avatar_minposts'));
  $sysTpl->assign('avatar_width', sysModGetVar('SYS_users','avatar_width'));
  $sysTpl->assign('avatar_height', sysModGetVar('SYS_users','avatar_height'));
  $sysTpl->assign('avatar_maxsize', sysModGetVar('SYS_users','avatar_maxsize'));

  $sysTpl->assign('self_delete', sysModGetVar('SYS_users','self_delete'));
  $sysTpl->assign('bad_unames', sysModGetVar('SYS_users','bad_unames'));
  $sysTpl->assign('bad_emails', sysModGetVar('SYS_users','bad_emails'));

  $sysTpl->assign('reg_dispdsclmr', sysModGetVar('SYS_users','reg_dispdsclmr'));
  $sysTpl->assign('reg_disclaimer', sysModGetVar('SYS_users','reg_disclaimer'));

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_users','admin','main'),
                     'displayname'=>"Пользователи");
  $browsein[]=array ('url'=>sysModURL('SYS_users','admin','modifyconfig'),
                     'displayname'=>"Конфигурация");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  };

/*
 * Сохраняем конфигурацию настроек пользователя
*/
function SYS_users_admin_updateconfig()
  {
  $sysObject = 'SYS_users::admin::updateconfig';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;
 
  //Забираем переменные со входа
  list ($perpage, $need_captcha, $allow_register, $allow_lostpass, $minpass, $minuname, $maxuname, $allow_chgmail, $new_user_notify, $new_user_notify_group,
        $activation_type, $activation_group, $uname_test_level, $avatar_allow_upload, $avatar_minposts,
        $avatar_width, $avatar_height, $avatar_maxsize, $self_delete, $bad_unames,
        $bad_emails, $reg_dispdsclmr, $reg_disclaimer)=
      sysVarCleanFromInput('perpage', 'need_captcha', 'allow_register', 'allow_lostpass', 'minpass', 'minuname', 'maxuname', 'allow_chgmail', 'new_user_notify', 'new_user_notify_group',
        'activation_type', 'activation_group', 'uname_test_level', 'avatar_allow_upload', 'avatar_minposts',
        'avatar_width', 'avatar_height', 'avatar_maxsize', 'self_delete', 'bad_unames',
        'bad_emails', 'reg_dispdsclmr', 'reg_disclaimer');

  sysModSetVar ('SYS_users','perpage',$perpage);
   sysModSetVar ('SYS_users','need_captcha',$need_captcha);
  
  sysModSetVar ('SYS_users','allow_register',$allow_register);
  sysModSetVar ('SYS_users','allow_lostpass',$allow_lostpass);
  sysModSetVar ('SYS_users','minpass',$minpass);
  sysModSetVar ('SYS_users','minuname',$minuname);
  sysModSetVar ('SYS_users','maxuname',$maxuname);
  sysModSetVar ('SYS_users','allow_chgmail',$allow_chgmail);
  sysModSetVar ('SYS_users','new_user_notify',$new_user_notify);
  sysModSetVar ('SYS_users','new_user_notify_group',$new_user_notify_group);
  sysModSetVar ('SYS_users','activation_type',$activation_type);
  sysModSetVar ('SYS_users','activation_group',$activation_group);
  sysModSetVar ('SYS_users','uname_test_level', $uname_test_level);
  sysModSetVar ('SYS_users','avatar_allow_upload',$avatar_allow_upload);
  sysModSetVar ('SYS_users','avatar_minposts',$avatar_minposts);
  sysModSetVar ('SYS_users','avatar_width',$avatar_width);
  sysModSetVar ('SYS_users','avatar_height',$avatar_height);
  sysModSetVar ('SYS_users','avatar_maxsize',$avatar_maxsize);
  sysModSetVar ('SYS_users','self_delete',$self_delete);
  sysModSetVar ('SYS_users','bad_unames',$bad_unames);
  sysModSetVar ('SYS_users','bad_emails',$bad_emails);
  sysModSetVar ('SYS_users','reg_dispdsclmr',$reg_dispdsclmr);
  sysModSetVar ('SYS_users','reg_disclaimer',$reg_disclaimer);

  //Тело модуля
  sysShowMessage(sysModURL ('SYS_users','admin','modifyconfig'), 'Изменения сохранены');
  }
  
  /* 
 * Напоминание неактивированным пользователям
*/
function SYS_users_admin_act_resend()
  {
  //Безопасность
  $sysObject = 'SYS_users::admin::act_resend';
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;
  
  //Грузим класс
  sysModClassLoad ('SYS_users');
  $SYS_users = new SYS_users;

  //Переменные со входа
  $user_id = sysVarCleanFromInput('user_id');
  $user_gid = sysVarCleanFromInput('user_gid');
  
  $users_list=array();
  if ($user_id)
	{
	$user_info=$SYS_users->get_info($user_id);
	if ($user_info['user_actkey']!='' AND $user_info['user_state']==1)
		$users_list[0]=$user_info;
	}
  elseif ($user_gid)
	$users_list=$SYS_users->get_list($user_gid,'user_regdate',0,1,65534,'not_active');
  else 
	$users_list=$SYS_users->get_list(null,'user_regdate',0,1,65534,'not_active');
	
	if (count($users_list)==0) sysShowMessage(sysModURL ('SYS_users','admin','main'), 'Нет пользователей для активации!', 3);
	
	
 foreach ($users_list as $k=>$user)
 {
 $SYS_users->resendActMsg($user);
 }
 
 sysShowMessage(sysModURL ('SYS_users','admin','main'), 'Напоминания разосланы '.count($users_list).' пользователям', 3);

}


?>
