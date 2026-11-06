<?php

  /*
   * Проверка е-mail. 
   *
   * Полная проверка e-mail. Синтаксическая проверка регулярным выражением и проверка домена на существование.
  */
function SYS_users_ajax_checkmail($Email)
  {
    //Прелюдие как у всех модулей
	$sysObject = 'SYS_users::ajax::check_email';
	
	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

	$sysModTpl=sysTplWay ($sysObject);
	$sysTpl = new sysTpl;
	$sysTpl->caching = false;
	sysLoadLang ($sysTpl, 'SYS_users');
	
	//подключаем класс
	sysModClassLoad ('SYS_users');
	$SYS_users = new SYS_users;
	
    //забираем е-mail со входа	
	$Email = sysVarCleanFromInput('user_email');
	$perhaps_domain = null; //очищаем переменную возможного домена
	
	//проверяем существование пользователя с таким e-mail в БД
	$CheckInDB = $SYS_users->check_exist_user ($Email);  //выводит информацию о пользователе (массив), если он есть в базе

	$id_LogIn_user = sysSessionGetVar('user_id'); 
	
    if ((!$CheckInDB) or ($id_LogIn_user ==$CheckInDB['id'])) //если пользователь не существует в базе или уже залогинен
	{  $CheckInDB = null;
	  //проверка синтаксиса
	  if (!$SYS_users->check_syntax($Email)) //если  ошибка
		  $SyntaxError = $sysTpl->get_config_vars('SyntaxError'); 
		  
	  else { 
	  	  list($Username, $Domain) = split("@",$Email);
		  
		  //проверяем сходство домена пользователя с популярными почтовыми доменами
		  $perhaps_domain = $SYS_users->check_domain($Domain, $Username); 
		  
		  //проверка существования домена
		  if (!@getmxrr($Domain, $MXHost))//домен не существует 		  
		  	  $DomainNotExist = $sysTpl->get_config_vars('DomainNotExist'). $perhaps_domain; 
			  
		  else //ошибок нет
		    $Correct = true;
		    
		} 
	}
	else $CheckInDB = $sysTpl->get_config_vars('UserExistInDB'); //пользователь уже существует в БД
	
	$sysTpl->assign('perhaps_domain', $perhaps_domain);
	$sysTpl->assign('SyntaxError', $SyntaxError);
	$sysTpl->assign('DomainNotExist', $DomainNotExist);
	$sysTpl->assign('CheckInDB', $CheckInDB);
	$sysTpl->assign('Correct', $Correct);
	
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
	
	return $result;
  }