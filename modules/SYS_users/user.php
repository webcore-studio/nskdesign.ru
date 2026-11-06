<?php
/*
 * Меню выбора, че делать.
*/
function SYS_users_user_main()
{
	sysModClassLoad ('SYS_users');
	$SYS_users = new SYS_users;

	//Два случая юзер залогинлся или нет
	if (sysUserIsLoggedIn())  {
		$user_vars = sysUserGetVars();
		//sysRedirect('/user/'.$user_vars['user_name'].'/');
		sysRedirect('/user/profile_modify');
		exit;

		$sysObject = 'SYS_users::user::main_loggedin';
		//Проверка на доступ
		if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

		$sysModTpl=sysTplWay ($sysObject);
		$sysTpl = new sysTpl;
		$sysTpl->caching = false;

		//Список  Плугинов
		$plugins_list = $SYS_users->plugin_get_list();
		$sysTpl->assign('plugins_list', $plugins_list);

		//BrowseIn
		$browsein = array();
		$browsein[]=array ('url'=>sysModURL('SYS_users','user','main'),
						 'displayname'=>'Ваш профиль');
		$sysTpl->assign('browsein', $browsein);

		$result['object'] =& $sysObject;
		$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
		$result['browsein'] =& $browsein;
		return $result;
    }
    else    {
		//Юзер не залогинился
		$sysObject = 'SYS_users::user::main_notloggedin';
		if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

		// Шаблоны
		$sysModTpl=sysTplWay ($sysObject);
		$sysTpl = new sysTpl;
		$sysTpl->caching = false;

		// Переменные модуля
		$allow_register = sysModGetVar('SYS_users','allow_register');
		$sysTpl->assign('allow_register', $allow_register);

		$sysTpl->assign('allow_lostpass', sysModGetVar('SYS_users','allow_lostpass'));

		//BrowseIn
		$browsein = array();
		if ($allow_register)
			$browsein[]=array ('url'=>sysModURL('SYS_users','user','main'),
                           'displayname'=>'Регистрация/Вход');
        else
			$browsein[]=array ('url'=>sysModURL('SYS_users','user','main'),
                             'displayname'=>'Вход для пользователей');
		$sysTpl->assign('browsein', $browsein);


		$result['object'] =& $sysObject;
		$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
		$result['browsein'] =& $browsein;
		return $result;
    }
}

/*
 * Меню ввода логина и пароля
*/
function SYS_users_user_loginscreen()
{
	//Прелюдие как у всех модулей
	$sysObject = 'SYS_users::user::loginscreen';
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

	$sysModTpl=sysTplWay ($sysObject);
	$sysTpl = new sysTpl;
	$sysTpl->caching = false;
	sysLoadLang($sysTpl,'SYS_users');

	if (sysUserIsLoggedIn()) {
		$user_info = sysUserGetVars();
		$sysTpl->assign('user_info', $user_info);
	}
	
	//нет ли сообщений
	$message = sysSessionGetVar("message");
	$http_referer = sysSessionGetVar('url');
	$sysTpl->assign('message', $message);
	$sysTpl->assign('http_referer', $http_referer);
	sysSessionDelVar('message');
	sysSessionDelVar('url');
	// Переменные модуля
	$allow_register = sysModGetVar('SYS_users','allow_register');
	$sysTpl->assign('allow_register', $allow_register);

	//BrowseIn
	$browsein = array();

	$browsein[]=array ('url'=>sysModURL('SYS_users','user','loginscreen'),'displayname'=>$sysTpl->get_config_vars('users_auth'));
	$sysTpl->assign('browsein', $browsein);

	$result['object'] =& $sysObject;
	$result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
	$result['browsein'] =& $browsein;
	return $result;
}

/*
 * Меню процесс залогиневания
*/
function SYS_users_user_login()
{
	list ($user_name, $user_pass, $user_rememberme, $user_loginkey, $http_referer) =
		sysVarCleanFromInput('user_name', 'user_pass', 'user_rememberme','user_loginkey', 'http_referer');

	$sysObject = 'SYS_users::user::login::'.$user_name;
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;
 
	sysModClassLoad ('SYS_users');
	$SYS_users = new SYS_users;
	$UserLoggedIn = $SYS_users->logIn ($user_name, $user_pass, $user_rememberme,$user_loginkey);

	$sysTpl = new sysTpl;
	$sysTpl->caching = false;
	sysLoadLang($sysTpl,'SYS_users');
	
	if (!$http_referer)
		$http_referer = $_SERVER['HTTP_REFERER'];
	if (!$http_referer)
		$http_referer = sysModGetVar("SYS_config", "site_url");

	if ($UserLoggedIn == true)   {
		sys_log('SYS_users_user_login',LOG_INFO,'Вход в систему',array('user_name'=>$user_name), "SYS_users");
		sysShowMessage($http_referer, 'Вход выполнен');
	}
    else   {
		sys_log('SYS_users_user_login', LOG_INFO, 'Неудачная попытка входа', array('user_name'=>$user_name), "SYS_users");
		sysShowMessage($http_referer, $sysTpl->get_config_vars('auth_fail'), 3);
	}
}

/*
 * Меню процесс РАЗлогинивания
*/
function SYS_users_user_logout()
{
	$sysObject = 'SYS_users::user::logout';

	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;
 
	sysModClassLoad ('SYS_users');
	$SYS_users = new SYS_users;
	$user_vars  = sysUserGetVars();
	$UserLoggedOut = $SYS_users->logOut();

	$sysTpl = new sysTpl;
	$sysTpl->caching = false;
	sysLoadLang($sysTpl,'SYS_users');
	
	$http_referer = $_SERVER['HTTP_REFERER'];
	if (!$http_referer)
		$http_referer = sysModGetVar("SYS_config", "site_url");
	
	$logout_to_main = true;
	if ($logout_to_main)
		$http_referer = sysModGetVar("SYS_config", "site_url");

	if ($UserLoggedOut == true)   {
		sys_log('SYS_users_user_logout', LOG_INFO, 'Успешный выход', array('user_name'=>$user_vars['user_name'], 'id'=>$user_vars['id']), "SYS_users");
		sysShowMessage($http_referer, "Вы вышли из системы");
    }
    else    {
		sys_log('SYS_users_user_logout', LOG_INFO, 'Выход не удался', array('user_name'=>$user_vars['user_name'], 'id'=>$user_vars['id']), "SYS_users");
		sysShowMessage($http_referer, $sysTpl->get_config_vars('logout_fail'));
    }
}

/*
 * Меню регистрация пользователя
*/
function SYS_users_user_registerscreen()
{
	//Прелюдие как у всех модулей
	$sysObject = 'SYS_users::user::registerscreen';
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

	$sysModTpl=sysTplWay ($sysObject);
	$sysTpl = new sysTpl;
	$sysTpl->caching = false;
	sysLoadLang($sysTpl,'SYS_users');

	//Проверки
	//1 разрешина ли регистрация ?
	if (!sysModGetVar('SYS_users','allow_register'))
		sysShowMessage('/', $sysTpl->get_config_vars('register_not_avail'));

	$ref = sysVarCleanFromInput('ref');

	//2 если пользователь уже залогинен - перебрасываем на профиль
	$user_id = sysSessionGetVar('user_id');
	if ($user_id>0) sysRedirect('/user/profile_modify');

	//3 Если нужно показывать условия регистрации показываем, или выводим форму для регистрации
	if (!sysModGetVar('SYS_users','reg_dispdsclmr'))
		if ($ref)
			sysRedirect(sysModURL ('SYS_users','user','registerscreen_agree',array ('ref'=>$ref)));
		else
			sysRedirect(sysModURL ('SYS_users','user','registerscreen_agree'));


	//Тело модуля
	$sysTpl->assign('reg_disclaimer', sysModGetVar('SYS_users','reg_disclaimer'));
	$sysTpl->assign('ref',$ref);

	//BrowseIn
	$browsein = array();
	$browsein[]=array ('url'=>sysModURL('SYS_users','user','main'),
                     'displayname'=>'Регистрация/Вход');
	$browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
                     'displayname'=>'Условия регистрации');
	$sysTpl->assign('browsein', $browsein);

	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	$result['browsein'] =& $browsein;
	return $result;
}

/*
 * Ввод регистрационных данных
*/
function SYS_users_user_registerscreen_agree()
{
	//Прелюдие как у всех модулей
	$sysObject = 'SYS_users::user::registerscreen_agree';
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

	$sysModTpl=sysTplWay ($sysObject);
	$sysTpl = new sysTpl;
	$sysTpl->caching = false;
	sysLoadLang ($sysTpl, 'SYS_users');

	if (sysUserIsLoggedIn())
	{
		$user_info=sysUserGetVars();
		$sysTpl->assign('user_info',$user_info);
		
	}
	else
	{
		//разрешина ли регистрация ?
		if (!sysModGetVar('SYS_users','allow_register'))
			sysShowMessage('/', $sysTpl->get_config_vars('register_not_avail'));

		sysExtLibLoad();

		//Получаем список доступных времянных зон
		$tzinfo = sysGetTzList();
		$sysTpl->assign('tzinfo',$tzinfo);
		$sysTpl->assign('default_tz', sysModGetVar('SYS_config','default_tz'));
		$sysTpl->assign('ref',$ref);

		//Получаем список доступных языков системы
		$lang_list = sysGetLangList();
		$sysTpl->assign('lang_list', $lang_list);

		//Определяем ip, страну, город
		$ipaddr = sysUserIpAddr();

		//sysExtLibLoad('cngeoip');
		//$geoip_array = cngeoip_lookup_ip_cp1251($ip);

		//McLaut temp hit Patch
		//if (preg_match('/^192\.168\./',$ipaddr) || preg_match('/^10\./',$ipaddr)) $geoip_array = cngeoip_lookup_ip_cp1251('193.0.240.48');
		//$sysTpl->assign('user_country', $geoip_array[5]);
		//$sysTpl->assign('user_city', $geoip_array[0]);
	}
	$sysTpl->assign('need_captcha', sysModGetVar('SYS_users','need_captcha'));

	//BrowseIn
	$browsein = array();
	$browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
                     'displayname'=>$sysTpl->get_config_vars('registration'));
	$sysTpl->assign('browsein', $browsein);

	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	$result['browsein'] =& $browsein;
	return $result;
}

/*
 * Собственно регистрация
*/
function SYS_users_user_register()
  {
    global $dbconn;
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_users::user::register';
  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;


  //Забираем переменные со входа
  $users_table = sysDBGetTable('users');
  $users_column =  sysDBGetColumns($users_table);
  $dbindate = sysDBCleanFromInput($users_column);
 if (empty($dbindate['user_name'])) $dbindate['user_name']=$dbindate['user_email'];
 

	

 $submit=sysVarCleanFromInput('submit');
 if (!$submit)
	sysRedirect(sysGetSiteUrl().'registerscreen');
 
  //Очистительный фильтр
  //$dbindate['user_name'] = strtolower($dbindate['user_name']);
  foreach ($dbindate as $key=>$value)
	if (!is_array($value))
		$dbindate[$key] = trim($value);
	

  //Проверки
  sysModClassLoad ('SYS_users');
  $SYS_users = new SYS_users;

  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;
sysLoadLang ($sysTpl, 'SYS_users');

  //1. Разрешина ли регистрация ?
  if (!sysModGetVar('SYS_users','allow_register'))
    {
    sysShowMessage('/', $sysTpl->get_config_vars('register_not_avail'));
    };
  //2. Емайл правильность ввода
  $valid_email = $SYS_users->checkEmail($dbindate['user_email']);
  if (!$valid_email)
    {
    $error_html = $SYS_users->showRegError ($sysTpl->get_config_vars('email_not_valid'), $dbindate);

    //BrowseIn
    $browsein = array();
    $browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
                       'displayname'=>$sysTpl->get_config_vars('registration'));

    $result['browsein'] =& $browsein;
    $result['object'] =& $sysObject;
    $result['content'] =& $error_html;
    return $result;
    };

  //3. Емайл разрешен ли такой емайл
  $bad_emails = sysModGetVar('SYS_users', 'bad_emails');
  $bad_emails = explode ("\n", $bad_emails);
  foreach ($bad_emails as $bad)
    {
    $bad = trim ($bad);
    if (!empty($bad) && preg_match("/".$bad."/i", $dbindate['user_email']))
      {
      $error_html = $SYS_users->showRegError ($sysTpl->get_config_vars('email_not_valid'), $dbindate);

      //BrowseIn
      $browsein = array();

    $browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
                       'displayname'=>$sysTpl->get_config_vars('registration'));
      $sysTpl->assign('browsein', $browsein);

      $result['browsein'] =& $browsein;
      $result['object'] =& $sysObject;
      $result['content'] =& $error_html;
      return $result;
      }
    }

  //4. Может такой емайл уже зарегистрированн
  //Делаем выборку
  $sql = "SELECT '$users_column[user_email]' FROM $users_table WHERE $users_column[user_email]='$dbindate[user_email]'";
  $sql_result = $dbconn->query($sql);
  if (mysqli_connect_errno()!=0)
    {
    sysException ('SYS_users_user_register', BAD_PARAM, mysqli_connect_error()."<br> $sql");
    };
  $RecordCount = mysqli_num_rows($sql_result);
  mysqli_free_result($sql_result);
  if ($RecordCount>0)
    {
    $error_html = $SYS_users->showRegError ($sysTpl->get_config_vars('email_exists'), $dbindate);

    //BrowseIn
    $browsein = array();

    $browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
                       'displayname'=>$sysTpl->get_config_vars('registration'));

    $result['browsein'] =& $browsein;
    $result['object'] = $sysObject;
    $result['content'] = $error_html;
    return $result;
    };

	//5. Проверки логина на правильность
	$strict = '-a-zA-Zа-яА-Я0-9_';
	$medium = $strict."<>,.$%#@!";
	$loose = $medium."'\"?{}[]()^&\*`~;:\\+=";
	switch (sysModGetVar('SYS_users','uname_test_level'))    {
		case 0:
			$restriction = $strict;
			break;
		case 1:
			$restriction = $medium;
			break;
		case 2:
			$restriction = $loose;
			break;
    }
	$pattern="/[^".$restriction."]/";
	if ((!isset($dbindate['user_name'])) || ($dbindate['user_name'] == '') || (preg_match($pattern,$dbindate['user_name'])))    {
		$error_html = $SYS_users->showRegError($sysTpl->get_config_vars('login_fail'), $dbindate);

		//BrowseIn
		$browsein = array();

		$browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
						   'displayname'=>$sysTpl->get_config_vars('registration'));

		$result['browsein'] =& $browsein;
		$result['object'] = $sysObject;
		$result['content'] = $error_html;
		return $result;
    }

  //6. Проверка чтоб ник был не слишком длинный
  if (strlen($dbindate['user_name']) > sysModGetVar('SYS_users','maxuname'))
    {
    $error_html = $SYS_users->showRegError ($sysTpl->get_config_vars('login_long').' '.sysModGetVar('SYS_users','maxuname').' '.$sysTpl->get_config_vars('symbols'), $dbindate);

    //BrowseIn
    $browsein = array();


    $browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
                       'displayname'=>$sysTpl->get_config_vars('registration'));

    $result['browsein'] =& $browsein;
    $result['object'] = $sysObject;
    $result['content'] = $error_html;
    return $result;
    }

  //7. Проверка чтоб ник был не слишком короткий
  if (strlen($dbindate['user_name']) < sysModGetVar('SYS_users','minuname'))
    {
    $error_html = $SYS_users->showRegError ($sysTpl->get_config_vars('login_short').' '.sysModGetVar('SYS_users','minuname').' '.$sysTpl->get_config_vars('symbols'), $dbindate);

    //BrowseIn
    $browsein = array();

    $browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
                       'displayname'=>$sysTpl->get_config_vars('registration'));

    $result['browsein'] =& $browsein;
    $result['object'] = $sysObject;
    $result['content'] = $error_html;
    return $result;
    }

  //8. Можноли использовать такое имя
  $bad_unames = sysModGetVar('SYS_users','bad_unames');
  $bad_unames = explode ("\n",$bad_unames);
  foreach ($bad_unames as $bad)
    {
    $bad = trim ($bad);
    if (!empty($bad) && preg_match("/".$bad."/i", $dbindate['user_name']))
      {
      $error_html = $SYS_users->showRegError ($sysTpl->get_config_vars('login_fail'), $dbindate);

      //BrowseIn
      $browsein = array();
    $browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
                       'displayname'=>$sysTpl->get_config_vars('registration'));

      $result['browsein'] =& $browsein;
      $result['object'] = $sysObject;
      $result['content'] = $error_html;
      return $result;
      }
    }

  //9. Нетули такова имени пользователя
  //Делаем выборку !!!
  $sql = "SELECT '$users_column[user_name]' FROM $users_table WHERE $users_column[user_name]='$dbindate[user_name]'";
  $sql_result = $dbconn->query($sql);
  if (mysqli_connect_errno()!=0) sysException ('SYS_users_user_register', BAD_PARAM, mysqli_connect_error()."<br> $sql");
  $RecordCount = mysqli_num_rows($sql_result);
  mysqli_free_result($sql_result);
  if ($RecordCount>0)
    {
    $error_html = $SYS_users->showRegError ($sysTpl->get_config_vars('login_exists'), $dbindate);

    //BrowseIn
    $browsein = array();
    $browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
                       'displayname'=>$sysTpl->get_config_vars('registration'));

    $result['browsein'] =& $browsein;
    $result['object'] = $sysObject;
    $result['content'] = $error_html;
    return $result;
    };

  //10. Проверка на минимальную длину пароля
  if (strlen($dbindate['user_pass']) < sysModGetVar('SYS_users','minpass'))
    {
    $error_html = $SYS_users->showRegError ($sysTpl->get_config_vars('password_short').' '.sysModGetVar('SYS_users','minpass').' '.$sysTpl->get_config_vars('symbols'), $dbindate);

    //BrowseIn
    $browsein = array();
    $browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
                       'displayname'=>$sysTpl->get_config_vars('registration'));

    $result['browsein'] =& $browsein;
    $result['object'] = $sysObject;
    $result['content'] = $error_html;
    return $result;
    }
 //$dbindate['user_pass']=md5 ($dbindate['user_pass']);

  //11. Проверка на указание пола
/*  if ($dbindate['user_sex']!=1 && $dbindate['user_sex']!=2)
    {
    $error_html = $SYS_users->showRegError ('Извините, Вы не указали Ваш пол', $dbindate);

    //BrowseIn
    $browsein = array();
    $browsein[]=array ('url'=>sysModURL('SYS_users','user','main'),
                       'displayname'=>'Регистрация/Вход');
    $browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
                       'displayname'=>'Регистрация');

    $result[browsein] =& $browsein;
    $result[object] = $sysObject;
    $result[content] = $error_html;
    return $result;
    }
*/

	//12. Проверка на правильный ввод капчи
	if (sysModGetVar('SYS_users','need_captcha')) {
		$captcha_string = sysVarCleanFromInput('captcha_string');
		$ses_comment_captcha_string = sysSessionGetVar('SYS_users_captcha_string');
		if ($ses_comment_captcha_string != $captcha_string)   {
			$error_html = $SYS_users->showRegError ($sysTpl->get_config_vars('captcha_fail'), $dbindate);

			//BrowseIn
			$browsein = array();
			$browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
							   'displayname'=>$sysTpl->get_config_vars('registration'));

			$result['browsein'] =& $browsein;
			$result['object'] = $sysObject;
			$result['content'] = $error_html;
			return $result;
		}
	}

  //С проверками все собственно добавление
  /* Че значит статус
  0 - ниче не предполагаеться
  1 - Сам должен активироваться
  2 - Сам себе меняет пароль
  3 - Ждет активации администратором
  */

  //Добавляем дефаултные настройки
  $dbindate['user_avatar'] = 'blank.gif';
  $dbindate['user_active'] = false;
  $dbindate['user_state'] = 0;
  $dbindate['user_regdate'] = time();
  if (!$dbindate['user_gid'])
	$dbindate['user_gid'] = sysModGetVar('SYS_config','default_group');
	
  $dbindate['user_rank'] = 0;
  $dbindate['user_theme'] = sysModGetVar('SYS_config','default_theme');
  $dbindate['user_lang'] = sysModGetVar('SYS_config','lang');
  $dbindate['user_timezone_offset'] = sysModGetVar('SYS_config','default_tz');
  //$dbindate[pass] = md5 ($dbindate[pass]);
  $dbindate['id_1c']='sc'.rand(100000,999999);
  //Добавляем в зависимости от типа активации
  $activation_type = sysModGetVar('SYS_users','activation_type');

  sysExtLibLoad();

$dbindate['url']=sysGetBaseURL();
$dbindate['url']=substr($dbindate['url'],0,strlen($dbindate['url'])-1);

  if ($activation_type==0)// Активация пользователем
    {


    $dbindate['user_actkey'] = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
    $dbindate['user_state'] = 1; //Сам должен активироваться
    //Вставляем инфу в базу

    $dbindate['id'] = $SYS_users->create($dbindate); //ID которое досталось пользователю

    //Готовим письмо
    $sysMailTplWay = sysTplWay ('SYS_users::mail::reg_wait_actkey');
    $sysMailTpl = new sysTpl;
    $sysMailTpl->caching = false;
    $sysMailTpl->assign ($dbindate);
    $sysMailTpl->assign ('activation_type', $activation_type);

    $mailbody = $sysMailTpl->fetch($sysMailTplWay, 'SYS_users::mail::reg_wait_actkey');
    $subject = 'Код активации пользователя "'.$dbindate['user_name'].'"';
    sysMail($dbindate['user_email'], $subject, $mailbody, true);
    };



  if ($activation_type==1)// Автоматическая активация
    {
    $dbindate['user_state'] = 0; //ниче не предполагаеться
    $dbindate['user_active'] = true;

	//Отсылаем письмо с поздравлениями
    $SYS_users->sendRegOkMsg($dbindate);
	
	
    //Вставляем инфу в базу
    $dbindate['user_pass'] = md5 ($dbindate['user_pass']); //Шифруем пароль
	$dbindate['id']=$SYS_users->create($dbindate);

    //Уведомляем администратора
    $SYS_users->newUserNotify($dbindate);
	
    };


  if ($activation_type==2)  // Активация администратором
    {
    $dbindate['user_actkey'] = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
    $dbindate['user_state'] = 3; //Ждет активации администратором
    $dbindate['user_active'] = false;

    //Вставляем инфу в базу
    $dbindate['id']=$SYS_users->create($dbindate);

    //Отсылаем письмо админу с просьбой активировать юзера
    $sysMailTplWay = sysTplWay ('SYS_users::mail::admin_plsactivate');
    $sysMailTpl = new sysTpl;
    $sysMailTpl->caching = false;
    $sysMailTpl->assign ($dbindate);

    $mailbody = $sysMailTpl->fetch($sysMailTplWay, 'SYS_users::mail::admin_plsactivate');
    $subject = 'Активация нового пользователя "'.$dbindate['user_name'].'"';

    //Отправка мессаги всем админам (занимаюшимся активацией)
    //$activation_group = sysModGetVar('SYS_users','activation_group');
    //$act_user_list = sysDbSelect($users_table, $users_column, "WHERE $users_column[gid]='$activation_group'", true);

    //$act_emails = array();
    //foreach ($act_user_list as $user)
    //  {
     // $act_emails[] = $user['user_email'];
    //  };
	$act_emails = array();
	$act_emails[]=sysModGetVar('SYS_config','adminmail');
    sysMail($act_emails, $subject, $mailbody, true);
    };
	
		

	//Вывод страницы с предоставлением возможных дальнейших действий
	$sysModTpl=sysTplWay ($sysObject);
	$sysTpl = new sysTpl;
	$sysTpl->caching = false;

	//Предоставляем инфу о пользователе
	$sysTpl->assign ($dbindate);
	$sysTpl->assign ('activation_type', $activation_type);


	//BrowseIn
	$browsein = array();
	$browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
                     'displayname'=>$sysTpl->get_config_vars('registration'));
	$browsein[]=array ('url'=>sysModURL('SYS_users','user','registerscreen'),
                     'displayname'=>"Регистрация прошла успешно");
					 
	$sysTpl->assign('browsein', $browsein);

	$result['browsein'] = $browsein;
	$result['object'] = $sysObject;
	$result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

/*
 * Активация пользователя
 * Функция универсальная используеться как админами так и пользователями
*/
function SYS_users_user_reg_act()
  {
  //Безопасность
  $sysObject = 'SYS_users::user::reg_act';
//  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;
   if (!sysSecAuthAction($sysObject, ACCESS_READ))   sysShowMessage('/', 'Недостаточно прав доступа!');

  //Грузим класс
  sysModClassLoad ('SYS_users');
  $SYS_users = new SYS_users;

  //Переменные со входа
  $user_id = sysVarCleanFromInput('user_id');
  if (!is_numeric ($user_id))//Проверка на интегер
    {
    sysException ('SYS_users_user_reg_act', BAD_PARAM, 'user_id not numeric'.$user_id);
    };

  $user_actkey = sysVarCleanFromInput('user_actkey');
  if (empty ($user_actkey))//Проверка на наличие вообше
    {
    sysShowMessage(sysModURL ('SYS_users','user','main'), 'Код активации не верен!', 3);
    };

  //Получили список столбцов модуля
  $users_table = sysDBGetTable('users');
  $users_column =  sysDBGetColumns($users_table);

  //Проверка может ли текуший чел производить активацию
  $activation_type = sysModGetVar('SYS_users','activation_type');
  $can_activate = false;
  if (sysSecAuthAction($sysObject, ACCESS_ADMIN))
    {
    //Админ может
    $can_activate = true;
    };
  if ($activation_type != 2)
    {
    //Всем остальным тоже разрешина, кроме случаев когда активацию проводит админ
    $can_activate = true;
    };


  if ($can_activate) //Активация разрешина
    {
    //Проверяем правильность ключа и состояния
    $user_data = sysDbSelect($users_table, $users_column, "WHERE $users_column[id]='$user_id' AND $users_column[user_actkey]='$user_actkey'");
	$user_data['deliver_address']=unserialize($user_data['deliver_address']);
	$user_data['legal_address']=unserialize($user_data['legal_address']);
	$user_data['bank_details']=unserialize($user_data['bank_details']);
    if (!empty ($user_data))
      {//Все правильно высылаем письма
      //Отсылаем письмо с поздравлениями
      $SYS_users->sendRegOkMsg($user_data);

      //Уведомляем администратора
      $SYS_users->newUserNotify($user_data);

      //Шифруем пароль и помечае экаунт как активный
      $user_data['user_state'] = 0; //ниче не предполагаеться
      $user_data['user_active'] = true;
      $user_data['user_actkey'] = '';
      $user_data['user_pass'] = md5 ($user_data['user_pass']);
      sysDbUpdate ($users_table, $user_data,
          "WHERE $users_column[id]='$user_id'");

		  
      sysShowMessage('/', 'Активация успешно произведена', 3);
      }
      else
        {
        // Проверка если что-то, может пользователь уже активированн ??
        $user_data = sysDbSelect($users_table, $users_column, "WHERE $users_column[id]='$user_id' AND $users_column[user_active]='1'");
        if (!empty ($user_data))
          {
          sysShowMessage('/', 'Выбранная учетная запись уже активирована!', 3);
          }
          else
            {
            sysShowMessage('/', 'Код активации не верен!', 3);
            };
        };
    };

  //В противном случае редирект на главную страницу модуля
  sysRedirect(sysModURL ('/'));
  }

/*
 * Меню востановления пароля
*/
function SYS_users_user_lostpassscreen()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_users::user::lostpassscreen';
//  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;
   if (!sysSecAuthAction($sysObject, ACCESS_READ))   sysShowMessage('/', 'Недостаточно прав доступа!');

  //Проверки
  //1 если пользователь уже залогинен - перебрасываем на профиль
  $user_id = sysSessionGetVar('user_id');
  if ($user_id>0) sysRedirect('/user/profile_modify');

  //2 разрешино ли востановление
  if (!sysModGetVar('SYS_users','allow_lostpass')) sysShowMessage('/', 'Извините, эта функция недоступна!');
   

  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  //Тело модуля

  //BrowseIn
  $browsein = array();
  $browsein[]=array ('url'=>'/loginscreen',
                     'displayname'=>'Регистрация/Вход');
  $browsein[]=array ('url'=>'/registerscreen',
                     'displayname'=>'Прислать новый пароль');
  $sysTpl->assign('browsein', $browsein);
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

/*
 * Меню востановления пароля
*/
function SYS_users_user_sendpassword()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_users::user::sendpassword';
  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

  //Проверки
  //1 разрешино ли востановление
  if (!sysModGetVar('SYS_users','allow_lostpass'))
    sysShowMessage('/', 'Извините, эта функция недоступна!');

  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  sysExtLibLoad();



  //Тело модуля
  $user_email = sysVarPrepForStore(sysVarCleanFromInput('user_email'));
  //$user_name = sysVarCleanFromInput('user_name');


  $users_table = sysDBGetTable('users');
  $users_column =  sysDBGetColumns($users_table);
  
  $user_info = sysDbSelect($users_table, $users_column, "WHERE $users_column[user_email]='$user_email'");

  if (empty ($user_info))
    {
    sysShowMessage('/lostpassscreen', 'Не найдено учетных записей с такими логином и адресом e-mail.');
    };

  if (!$user_info['user_active'])
    {
    sysShowMessage('/lostpassscreen', 'Извините, но пароль не может быть выслан, так как ваша учётная запись неактивна.');
    };

  //Обнавляем инфу в базе данных
  $user_info['user_actkey'] = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
  $user_newpasswd = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
  $user_info['user_newpasswd'] = md5($user_newpasswd);
  sysDbUpdate ($users_table, $user_info,
          "WHERE $users_column[id]='$user_info[id]'");

$user_info['url']=sysGetBaseURL();
$user_info['url']=substr($user_info['url'],0,strlen($user_info['url'])-1);

  //Шлем письмо
  $sysMailTplWay = sysTplWay ('SYS_users::mail::sendpassword');
  $sysMailTpl = new sysTpl;
  $sysMailTpl->caching = false;
  $user_info['user_newpasswd'] = $user_newpasswd;
  $sysMailTpl->assign ($user_info);

  $mailbody = $sysMailTpl->fetch($sysMailTplWay, 'SYS_users::mail::sendpassword');
  $subject = 'Запрос на смену пароля';
  sysMail($user_info['user_email'], $subject, $mailbody, true);


  //BrowseIn
  $browsein = array();
  $browsein[]=array ('url'=>'/loginscreen',
                     'displayname'=>'Регистрация/Вход');
  $browsein[]=array ('url'=>'/sendpassword',
                     'displayname'=>'Новый пароль был создан');
  $sysTpl->assign('browsein', $browsein);

  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

/*
 * Меню подтверждение изменения пароля
*/
function SYS_users_user_pass_act()
  {
  //Безопасность
  $sysObject = 'SYS_users::user::pass_act';
  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;
   
  //Переменные со входа
  $user_id = sysVarCleanFromInput('user_id');
  if (!is_numeric ($user_id))//Проверка на интегер
    {
    sysException ('SYS_users_user_pass_act', BAD_PARAM, 'user_id not numeric'.$user_id);
    };

  $user_actkey = sysVarCleanFromInput('user_actkey');
  if (empty ($user_actkey))//Проверка на наличие вообше
    {
    sysShowMessage(sysModURL ('SYS_users','user','main'), 'Код активации не верен!', 3);
    };

  //Получили список столбцов модуля
  $users_table = sysDBGetTable('users');
  $users_column =  sysDBGetColumns($users_table);
  //sysModAPILoad ('SYS_users','user');

  //Проверяем правильность ключа и состояния
  $user_data = sysDbSelect($users_table, $users_column,
          "WHERE $users_column[id]='$user_id' AND
                 $users_column[user_actkey]='$user_actkey' AND
                 $users_column[user_newpasswd] <> ''");

  if (!empty ($user_data))
      {//Все правильно, обновляем пароль
      //Шифруем пароль и помечае экаунт как активный
      $user_data['user_pass'] = $user_data['user_newpasswd'];
      $user_data['user_newpasswd'] = '';
      $user_data['user_actkey'] = '';
      sysDbUpdate ($users_table, $user_data,
          "WHERE $users_column[id]='$user_id'");

      sysShowMessage(sysModURL ('SYS_users','user','loginscreen'), 'Активация успешно произведена', 3);
      }
      else
        {
        // Проверка если что-то, может пароль уже активированн ??
        $user_data = sysDbSelect($users_table, $users_column, "WHERE $users_column[id]='$user_id' AND $users_column[user_newpasswd]=''");
        if (!empty ($user_data))
          {
          sysShowMessage(sysModURL ('SYS_users','user','main'), 'Новый пароль выбранной учетной записи уже активирован!', 3);
          }
          else
            {
            sysShowMessage(sysModURL ('SYS_users','user','main'), 'Код активации не верен!', 3);
            };
        };
  }


/*
 * Вывод информации о пользователе
*/
function SYS_users_user_user_view()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id))  sysException ('SYS_users_user_user_view', BAD_PARAM, 'Id not numeric'.$id);

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_users::user::user_view::'.$id;
  
  $userVars = sysUserGetVars();
	  if(!isset($userVars['id']) || ($userVars['user_gid']!=1 && $userVars['id']!=$id)){
		  header('location: /');
	  }

 if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

 global $db;
 $tours = $db ->where('p.user_id',$id,'=')
   ->join('sys_travel_links l',"p.id=l.child_id")
   ->get('sys_travel_products p',null,array('p.id','p. product_displayname','p. product_name','p.created_at','l.parent_id as cat_id'));
 sysModClassLoad ('travel');
 $travel = new travel;
 $booked = $travel::getBookedTours($id);
 $total_sum = $booked['sum'];
 unset($booked['sum']);


  $sysModTpl = sysTplWay ($sysObject);

  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  //Тело модуля
  $users_table = sysDBGetTable('users');
  $users_column =  sysDBGetColumns($users_table);
  $user_dbdata = sysDbSelect ($users_table, $users_column,
      "where $users_column[id] = ".sysVarPrepForStore($id));
  unset ($user_dbdata['user_pass']);
  $sysTpl->assign ($user_dbdata);

  //BrowseIn
  $browsein = array();
  $browsein[]=array ('url'=>sysModURL('SYS_users','user','view'),
                     'displayname'=>'Профиль пользователя');
  $sysTpl->assign('browsein', $browsein);

  if (isset($booked)) {
	  $sysTpl->assign('booked',$booked);
	  $sysTpl->assign('total_sum',$total_sum);
  }

  $result['object'] = $sysObject;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  $result['browsein'] = $browsein;
  return $result;
  }

/*
 * Показ картинки кипчи
*/
function SYS_users_user_captcha_view()
  {
  //Проверка на доступ
  $sysObject = 'SYS_users::user::captcha_view';
  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

	//Рисуем капчу
  sysExtLibLoad('captcha');
	$captcha = new sysCaptcha();
	$captcha -> display();

	//Кладем в переменную сессии нарисованные букавки
	sysSessionSetVar('SYS_users_captcha_string',$captcha -> getString());

  sysTerminate;
  exit;
  };

?>
