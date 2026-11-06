<?php

class SYS_users
  {
  /*
   * Переменные
  */



  /*
   * Функции
  */


  /*
   * Сравнивает переданные пароли и true в случае если пароли идентичны
  */
	function _comparePasswords($givenpass, $realpass, $username)
    {
		$compare2text = false; //Не менять !
		
		$givenpass = trim($givenpass); // Обрезаем пробелы по краям для профилактики
		$md5pass = md5($givenpass);
		if (strcmp($md5pass, $realpass) == 0)      {
			return $md5pass;
		}
		elseif ($compare2text && strcmp($givenpass, $realpass) == 0)  {
			$this->_updateUserPass($username, $md5pass);
			return $md5pass;
        }
		sys_log('_comparePasswords', LOG_INFO, 'Неверный пароль', $username);
		return false;
    }

	/*
	* Изменение пароля пользователя
	*/
	function _updateUserPass($username, $md5pass)
    {
		global $dbconn;

		$users_table = sysDBGetTable('users');
		$users_column =  sysDBGetColumns($users_table);
		$sql = "UPDATE $users_table
			SET $users_column[user_pass] = '".sysVarPrepForStore($md5pass)."'
            WHERE $users_column[user_name]='".sysVarPrepForStore($username)."'";
		$dbconn->query($sql);
		if (mysqli_connect_errno()!=0)   {
			sysException ('_updateUserPass', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
		}
    }

  /*
   * Процедура залогиневания пользователя в систему
  */
	function logIn($name, $pass, $rememberme, $loginkey='')
    {
		if (!sysUserIsLoggedIn()) {
			sysExtLibLoad('pluginslib');
			$sysPlugins = new sysPlugins();

			global $dbconn;

			sys_log('SYS_users', LOG_INFO, "logIn ", $name);
			
			//Временно выключаем все плагины
			//$indata = $sysPlugins -> plugin_exe('SYS_users', 'login', 'before', array());
			
			if ($loginkey)	{

				  // Получаем информацию о пользователе
				  $users_table = sysDBGetTable('users');
				  $users_column =  sysDBGetColumns($users_table);
				  $sql = "SELECT   $users_column[id],
								   $users_column[user_gid],
								   $users_column[user_pass],
								   $users_column[user_theme],
								   $users_column[user_lang]
							FROM $users_table
							WHERE $users_column[user_loginkey] = '" . sysVarPrepForStore($loginkey) ."' AND
								  $users_column[user_active]='1'";
				  //$sql_result = $dbconn->query($sql);
				  $sql_result = sysDbSelect($users_table, $users_column, $sql);

				  //if (mysqli_connect_errno()!=0) sysException ('SYS_users->logIn', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

				  $num_rows = mysqli_num_rows($sql_result);
				  if ($num_rows!=1) return false;

				  list($id, $gid, $realpass, $theme, $user_lang) = mysqli_fetch_row ($sql_result);
				  mysqli_free_result($sql_result);

				  // Заносим в сессию информцию о пользователе
				  sysSessionSetVar('user_id', (int)$id);
				  sysSessionSetVar('user_gid', (int)$gid);
				  sysSessionSetVar('user_theme', $theme);
				  sysSessionSetVar('user_lang', $user_lang);

			}
			else 	{
				// Получаем информацию о пользователе
				$users_table = sysDBGetTable('users');
				$users_column =  sysDBGetColumns($users_table);
				$sql = "SELECT   $users_column[id],
							   $users_column[user_gid],
							   $users_column[user_pass],
							   $users_column[user_theme],
							   $users_column[user_lang]
						FROM $users_table
						WHERE $users_column[user_name] = '" . sysVarPrepForStore($name) ."' AND
							  $users_column[user_active]='1'";
				
				//$sql_result = $dbconn->query($sql);

				$sql_result = mysqli_query($dbconn, $sql);

				//$sql_result = sysDbSelect($users_table, $users_column, $sql);

				//if (mysqli_connect_errno()!=0) 					sysException ('SYS_users->logIn', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

				$num_rows = mysqli_num_rows($sql_result);
				if ($num_rows!=1) 
					return false;

				list($id, $gid, $realpass, $theme, $user_lang) = mysqli_fetch_row ($sql_result);
				mysqli_free_result($sql_result);

				// Проверка паролей
				if (!$this->_comparePasswords($pass, $realpass, $name))     {
					$this->set_counter();
					return false;
				}

				// Заносим в сессию информцию о пользователе
				sysSessionSetVar('user_id', (int)$id);
				sysSessionSetVar('user_gid', (int)$gid);
				sysSessionSetVar('user_theme', $theme);
				sysSessionSetVar('user_lang', $user_lang);

				if (!empty($rememberme))     {
					sysSessionSetVar('user_rememberme', 1);
				}
			}
		}
		
		//Временно выключаем все плагины
		//$result = $sysPlugins -> plugin_exe('SYS_users', 'login', 'after', array('indata' => $indata, 'login' => 1));
		
		sys_log('SYS_users->logIn', LOG_INFO, 'logIn', $id);
		
		return true;
    }

	/*
	* Процедура выхода пользователя из системы
	*/
	function logOut($user_id = null)
    {
		if ($user_id > 0) {
			$session_table = sysDBGetTable('session');
			$session_column = sysDBGetColumns($session_table);
			sysDbDelete($session_table, "WHERE $session_column[user_id] = $user_id");
			return true;

		}
		elseif (sysUserIsLoggedIn())   {
			sysSessionSetVar('user_id', 0);
			sysSessionSetVar('user_gid', -1);
			sysSessionDelVar('user_rememberme');
			sysSessionSetVar('attempt', 0);
			return true;
		}
		return false;
    }


  /*
	* Возвращает список пользователей в группе
	*/
	function get_list($user_gid = null, $orderby = 'user_regdate', $order_asc = 0, $page = 0, $perpage = 0, $which = 'all')    
	{
 		$users_table = sysDBGetTable('users');
		$users_column =  sysDBGetColumns($users_table);
		
		$from = $users_table;
		
		if (sysModAvailable('shop')) {
			$payments_table = sysDBGetTable('shop_payments');
			$payments_column = sysDBGetColumns($payments_table);
			$users_column['payment_count'] = "count($payments_column[id]) as payment_count";
			$users_column['lastpayment_date'] = "max($payments_column[payment_time]) as lastpayment_date";
			$from = "$users_table left join $payments_table on $users_column[id]=$payments_column[user_id]";
			
		}
			
		if ($orderby) $order = "ORDER BY $orderby ";
		if ($order_asc == 1) $order .= " DESC ";
		if ($page && $perpage) $order .= " LIMIT ".($page-1)*$perpage.", $perpage";

		$where = array();

		if (is_array($which)) {
			if ($which['user_email']) {
				$where[] = " ($users_column[user_name] LIKE '%$which[user_email]%' OR $users_column[user_email] LIKE '%$which[user_email]%' OR $users_column[user_displayname] LIKE '%$which[user_email]%' || $users_column[user_firstname] LIKE '%$which[user_email]%' || $users_column[user_secondname] LIKE '%$which[user_email]%' || $users_column[user_surname] LIKE '%$which[user_email]%') ";
			}
			if ($which['id']) {
				$where[] = " $users_column[id]=$which[id] ";
			}
			if ($which['paid']) {
				$where[] = " $users_column[shop_balans]>0 ";
			}
			
			if ($which['date_from']) {
				$where[] = " $users_column[user_regdate] > ".strtotime($which['date_from']);
			}
			if ($which['date_to']) {
				$where[] = " $users_column[user_regdate] < ".strtotime($which['date_to']);
			}
		} else {
			if ($which == "not_active")
				$where[] = " $users_column[user_active]=0 AND $users_column[user_actkey]!='' AND $users_column[user_state]=1 ";
			if ($which == "active")
				$where[] = " $users_column[user_active]=1 AND $users_column[user_actkey]=='' AND $users_column[user_state]=0 ";
		}
		if (isset ($user_gid)) {
			if (is_array($user_gid))
				$where[]="$users_column[user_gid] IN (".implode(",", $user_gid).")";
			else
				$where[]="$users_column[user_gid] = '$user_gid' ";
		}
 
		if (count($where)>0) 
			$where_sql = "WHERE ".implode(' AND ',$where);
			
			
		if (sysModAvailable('shop')) {
			$where_sql .= " GROUP BY $users_column[id] ";
		}
		$where_sql .= $order;
		
    
		$users_list = sysDbSelect ($from, $users_column, $where_sql, true);
		return $users_list;
    }
	
	/*
	* Возвращает кол-во пользователей по условиям
	*/
	
	function get_count($user_gid = null, $which = 'all') 	
	{
		
		$users_table = sysDBGetTable('users');
		$users_column =  sysDBGetColumns($users_table);
		$search_column['count'] = "count($users_column[id]) as count";

		$where = array();
	
		if (isset ($user_gid)) {
			if (is_array($user_gid))
				$where[]="$users_column[user_gid] IN (".implode(",", $user_gid).")";
			else
				$where[]="$users_column[user_gid] = '$user_gid' ";
		}

		if (is_array($which)) {
			if ($which['user_email']) {
				$where[] = " ($users_column[user_name] LIKE '%$which[user_email]%' OR $users_column[user_email] LIKE '%$which[user_email]%' OR $users_column[user_displayname] LIKE '%$which[user_email]%' || $users_column[user_firstname] LIKE '%$which[user_email]%' || $users_column[user_secondname] LIKE '%$which[user_email]%' || $users_column[user_surname] LIKE '%$which[user_email]%') ";
			}
			if ($which['id']) {
				$where[] = " $users_column[id]=$which[id] ";
			}
		} else {
			if ($which == "not_active")
				$where[] = " $users_column[user_active]=0 AND $users_column[user_actkey]!='' AND $users_column[user_state]=1 ";
			if ($which == "active")
				$where[] = " $users_column[user_active]=1 AND $users_column[user_actkey]=='' AND $users_column[user_state]=0 ";
		}

		if (count($where) > 0) $where_sql = "WHERE ".implode(' AND ',$where);
		

		$usercount = sysDbSelect ($users_table, $search_column, $where_sql, false);
		return $usercount['count'];
    }
	

  /*
   * Возвращает информацию о пользователе в системе
  */
	function get_info($user_id)
    {
		$users_table = sysDBGetTable('users');
		$users_column =  sysDBGetColumns($users_table);
		$user_info = sysDbSelect ($users_table, $users_column,
                    "WHERE $users_column[id] = '$user_id'");
		//$user_info['legal_address'] = unserialize($user_info['legal_address']);
		$user_info['deliver_address'] = unserialize($user_info['deliver_address']);
		$user_info['bank_details'] = unserialize($user_info['bank_details']);
		
		// Достаем название группы
		sysModClassLoad ('SYS_groups');
		$SYS_groups = new SYS_groups;
		$user_info['user_gid_name'] = $SYS_groups->get_info($user_info['user_gid']);
		
		
		return $user_info;
    }

  /*
   * Создает пользователя в системе
  */
  function create($param)
    {
		global $dbconn;
    // Проверки
    $users_table = sysDBGetTable('users');
    $users_column =  sysDBGetColumns($users_table);

    //9. Нету ли такова имени пользователя
    $sql = "SELECT '$users_column[user_name]' FROM $users_table WHERE $users_column[user_name]='$param[user_name]'";
    $sql_result = $dbconn->query($sql);
    if (mysqli_connect_errno()!=0) sysException ('SYS_users->create', BAD_PARAM, mysqli_connect_error()."<br> $sql");
    $RecordCount = mysqli_num_rows($sql_result);
    mysqli_free_result($sql_result);
    if ($RecordCount>0) return false;

    //Делаем вставку информаии в базу
    $users_table = sysDBGetTable('users');
    if (empty($param['user_regdate'])) $param['user_regdate'] = time();
	
	if (isset($param['bank_details']))
		$param['bank_details'] = serialize($param['bank_details']);
		
    $uid = sysDbInsert ($users_table, $param);
    return $uid;
    }

  /*
   * Обновление информации о пользователе в системе
  */
	function update($param)
    {
		
		sys_log('SYS_users_admin_update start', LOG_INFO, 'start', $param, "SYS_users");
		
		// Если пароль не задан - не обновляем
		if (empty ($param['user_pass']))
			unset ($param['user_pass']);
		else  
			$param['user_pass']=md5($param['user_pass']);

		//Старая информация о пользователе
		$olduser_info = $this->get_info($param['id']);
		
		//если пароль есть и не сходится со старым - логируем и чистим нужные сессии
		if (isset($param['user_pass']) && $param['user_pass'] != $olduser_info['user_pass']) {
			sys_log('SYS_users_admin_updatet', LOG_INFO, 'Обновление пароля', array('user_name'=>$olduser_info['user_name'], 'id'=>$param['id']), "SYS_users");
			
			$user_vars = sysUserGetVars();
			$curuser_id= $user_vars['id'];
			
			if ($curuser_id == $param['id']) {
				$session_table = sysDBGetTable('session');
				$session_column = sysDBGetColumns($session_table);
				$sessid = session_id();
				sysDbDelete($session_table, "WHERE $session_column[sessid]!='$sessid' AND $session_column[user_id]=$curuser_id");
			}
			else
				$this->logOut($param['id']);
		}

		if (is_array($param['legal_address']))
			$param['legal_address'] = serialize($param['legal_address']);
		if (is_array($param['deliver_address']))
			$param['deliver_address'] = serialize($param['deliver_address']);
		if (is_array($param['bank_details']))
			$param['bank_details'] = serialize($param['bank_details']);
		
		//Обновление в базе информации о пользователе
		$users_table = sysDBGetTable('users');
		$users_column =  sysDBGetColumns($users_table);
		sysDbUpdate ($users_table, $param, "WHERE $users_column[id] = $param[id]");

		//Теперь меняем переменные которые храняться в сессии
		if (sysSessionGetVar('user_id')==$param['id'])      {
			if ($param['user_gid'])
				sysSessionSetVar('user_gid', $param['user_gid']);
			if ($param['user_theme'])
				sysSessionSetVar('user_theme', $param['user_theme']);
			if ($param['user_lang'])
				sysSessionSetVar('user_lang', $param['user_lang']);
			if (isset($param['user_active']) && $param['user_active'] != $olduser_info['user_active'])      {
				session_destroy(); //Если стал не активным, прибили ссесию
			}
		}
		else   {
			if ($param['user_gid'] != $olduser_info['user_gid'] &&
				$param['user_theme'] != $olduser_info['user_theme'] &&
				$param['user_active'] != $olduser_info['user_active'] &&
				$param['user_lang'] != $olduser_info['user_lang']) {
					$session_table = sysDBGetTable('session');
					$session_column = sysDBGetColumns($session_table);

					$sql = "DELETE FROM $session_table WHERE $session_column[user_id] = '".sysVarPrepForStore($param['id'])."'";
					//Логируем выборку
					global $dbg_sql_queries;
					$dbg_sql_queries[] = array('sql_query'=>$sql);
			}
        }

		sys_log('SYS_users_admin_update', LOG_INFO, 'DELETE', array('user_name'=>$olduser_info['user_name'], 'id'=>$param['id']), "SYS_users");
		
		return true;
    }

  /*
   * Удаление пользователей из системы
  */
	function delete($id)
    {
		//Тупа удаляем из базы
		$users_table = sysDBGetTable('users');
		$users_column =  sysDBGetColumns($users_table);
		sysDbDelete ($users_table, "WHERE $users_column[id] = $id");
		return true;
    }

  /*
   * Проверка правильности ввода емайла
  */
	function checkEmail($email)
    {
		if (!$email || !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i",$email))     {
			return false;
		}
		else   {
			return true;
        }
    }

  /*
   * Возврашает html -> Вывод ошибки+формы при вводе неправильных регистрационных данных
   * $error_msg
   * $user_data
  */
	function showRegError($error_msg, $user_data)
    {
    $sysModTpl = sysTplWay ('SYS_users::user::registerscreen_errors');
    $sysObject = 'SYS_users::user::register';
    $sysTpl = new sysTpl;
    $sysTpl->caching = false;
sysLoadLang ($sysTpl, 'SYS_users');

    $sysTpl->assign ($user_data);
    $sysTpl->assign ('error_msg', $error_msg);
	$sysTpl->assign('need_captcha', sysModGetVar('SYS_users','need_captcha'));

    sysExtLibLoad();

    //Получаем список доступных времянных зон
    $tzinfo = sysGetTzList();
    $sysTpl->assign('tzinfo',$tzinfo);
    $sysTpl->assign('default_tz', sysModGetVar('SYS_config','default_tz'));

    //Получаем список доступных языков системы
    $lang_list = sysGetLangList();
    $sysTpl->assign('lang_list', $lang_list);

    $result = $sysTpl->fetch($sysModTpl,$sysObject);
    return $result;
    }

  /*
   * Высылает поздравления и регистрационную информацию пользователю, после регистрации
   * $user_data
  */
  function sendRegOkMsg($user_data)
    {
    $sysMailTplWay = sysTplWay ('SYS_users::mail::reg_ok_msg');
    $sysMailTpl = new sysTpl;
    $sysMailTpl->caching = false;


$user_data['url']=sysGetBaseURL();
$user_data['url']=substr($user_data['url'],0,strlen($user_data['url'])-1);

    $sysMailTpl->assign ($user_data);

    $mailbody = $sysMailTpl->fetch($sysMailTplWay, 'SYS_users::mail::reg_ok_msg');
    $subject = 'Регистрационная информация пользователя "'.$user_data['user_name'].'"';

    //Отправка мессаги
    sysExtLibLoad();
    if (sysMail($user_data['user_email'], $subject, $mailbody, true))
      {
      return true;
      }
      else
        {
        return false;
        }
    }
	
	  /*
   * Напоминание о необходимости активации
   * $user_data
  */
  function resendActMsg($user_data)
    {
    $sysMailTplWay = sysTplWay ('SYS_users::mail::resend_act_msg');
    $sysMailTpl = new sysTpl;
    $sysMailTpl->caching = false;


	$user_data['url']=sysGetBaseURL();
	$user_data['url']=substr($user_data['url'],0,strlen($user_data['url'])-1);

    $sysMailTpl->assign ($user_data);

    $mailbody = $sysMailTpl->fetch($sysMailTplWay, 'SYS_users::mail::reg_ok_msg');
    $subject = 'Напоминание об активации пользователя "'.$user_data['user_name'].'"';

    //Отправка мессаги
    sysExtLibLoad();
    if (sysMail($user_data['user_email'], $subject, $mailbody, true))
      {
      return true;
      }
      else
        {
        return false;
        }
    }

  /*
   * Высылает сообшение администратору о регистрации нового пользователя
   * $user_data
  */
  function newUserNotify($user_data)
    {
    //Проверка, нужноли слать уведомления
    if (!sysModGetVar('SYS_users','new_user_notify'))
      {
      return true;
      };

    $sysMailTplWay = sysTplWay ('SYS_users::mail::new_user_notify');
    $sysMailTpl = new sysTpl;
    $sysMailTpl->caching = false;
    $sysMailTpl->assign ($user_data);

    $mailbody = $sysMailTpl->fetch($sysMailTplWay, 'SYS_users::new_user_notify');
    $subject = 'Зарегистрирован новый пользователь "'.$user_data['user_name'].'"';

    //Формируем список пользователей, каторым рассылать уведомления
    $new_user_notify_group = sysModGetVar('SYS_users','new_user_notify_group');

    $users_table = sysDBGetTable('users');
    $users_column =  sysDBGetColumns($users_table);
   // $new_user_notify_list = sysDbSelect($users_table, $users_column, "WHERE $users_column[user_gid]='$new_user_notify_group'", true);

    //$notify_emails_list = array();
    //foreach ($new_user_notify_list as $user)
    //  {
    //  $notify_emails_list[] = $user['user_email'];
    //  };
	$notify_emails_list = array();
	$notify_emails_list[] = sysModGetVar('SYS_config','adminmail');
    //Отправка мессаги
    if (sysMail($notify_emails_list, $subject, $mailbody, true))
      {
      return true;
      }
      else
        {
        return false;
        }
    }







  /**
   * @return array
   * @desc Возврашает список доступных плугинов
  */
  function plugin_get_list()
    {
    $plugins_list = array();
    $dh = opendir('modules/SYS_users/plugins');
    while ($dir = readdir($dh))
      {
      if ((is_dir("modules/SYS_users/plugins/$dir")) &&
                    ($dir != '.') &&
                    ($dir != '..') &&
                    ($dir != 'CVS') &&
                    (file_exists ("modules/SYS_users/plugins/$dir/plugin.php")))
        {
        // Нашли
        // Загружаем информацию о плугин
        $plugin_info = $this->plugin_get_info($dir);
        if ($plugin_info)
          {
          array_push ($plugins_list, $plugin_info);
          }
        }
      }
    closedir($dh);

    //Сортировка
    usort($plugins_list, array ('SYS_users', '_plugin_sort_weight_asc'));
    return $plugins_list;
    }

  function _plugin_sort_weight_asc($elt1, $elt2)
    {
    if ($elt1['weight'] == $elt2['weight']) return 0;
    return ($elt1['weight'] < $elt2['weight']) ? -1 : 1;
    }

  /**
   * @return bool
   * @param $plugin_name string
   * @desc Загружает плугин
  */
  function plugin_load($plugin_name)
    {
    $plugin_name = sysVarPrepForOS($plugin_name);
    static $loaded = array();

    //Много кратно не загружаем
    if ($loaded[$plugin_name]) return true;

    //Небольшая проверка
    if (file_exists ("modules/SYS_users/plugins/$plugin_name/plugin.php"))
      {
      include_once("modules/SYS_users/plugins/$plugin_name/plugin.php");
      $loaded[$plugin_name] = true;
      return true;
      }

    return false;
    }

  /**
   * @return array
   * @param $plugin_name string
   * @desc Возврашает информацию о плугине
  */
  function plugin_get_info($plugin_name)
    {
    static $loaded = array();

    //Много кратно не загружаем
    if ($loaded[$plugin_name]) return $loaded[$plugin_name];

    //Небольшая проверка + загрузка плугина
    if ($this->plugin_load($plugin_name))
      {
      //Вызыв функции
      $func = 'SYS_users_plugin_'.$plugin_name.'_info';
      $plugin_info = $func();
      //Проверка плугина на доступность
      if (!isset ($plugin_info)) return;
      $plugin_info['name'] = $plugin_name; //Доп зашита
      $loaded[$plugin_name] = $plugin_info;
      return $plugin_info;
      }

    return false;
    }

  function set_counter()
    {
$count=sysSessionGetVar('attempt');
$count++;
sysSessionSetVar('attempt',$count);

        return true;
    }

  function is_flood()
    {


$count=sysSessionGetVar('attempt');
if ($count>2) 
{
$flood_time = 30;//минимальный интервал между залогиниваниями
if ($count>4) $flood_time = 60;
if ($count>9) $flood_time = 600;
    $last_log_time = (int)sysSessionGetVar('last_log_time');
    $now = time();
    if ($now - $last_log_time <$flood_time)
    return false;
}
if ($count>1) sysSessionSetVar('last_log_time',time());
return true;

    }
	
	/*
   * Проверяем существование пользователя в базе по е-mail
  */
  function check_exist_user ($user_email)
    {
    $users_table = sysDBGetTable('users');
    $users_column =  sysDBGetColumns($users_table);
    $user_exist = sysDbSelect ($users_table, $users_column,
                    "WHERE $users_column[user_email] = '$user_email'");
    return $user_exist;
	/*
	  $users_table = sysDBGetTable('users');
      $users_column =  sysDBGetColumns($users_table);
      $sql = "SELECT   $users_column[id],
                       $users_column[user_pass],
                       $users_column[user_theme],
                       $users_column[user_lang],
	                   $users_column[user_email]
                FROM $users_table
                WHERE $users_column[user_email] = '$user_email'";
      $sql_result = $dbconn->query($sql);
	   return $sql_result;*/
    }
	
  /*
   * Сравнение с популярными почтовыми доменами.
   *
   * В случае несуществования домена проверяем сходство написания домена пользователя с популярными почтовыми доменами.
  */	
  function check_domain($Domain, $Username) 
  {
    $check_in=0;
  
    similar_text($Domain, "mail.ru", $percent); //сравнение строк $Domain и "mail.ru". $percent - процент схожести строк.
    if (($percent>80) and ($percent<100))
  	{ 
	  $check_in = 1;
	  return " Возможно вы имели в виду: " . "<a href='javascript:void(0)' onclick='getText(this)' id='text0'>$Username@mail.ru</a>";
	}
	  
    similar_text($Domain, "rambler.ru", $percent);
    if (($percent>80) and ($percent<100))
	{ 
	  $check_in = 1;
	  return " Возможно, вы имели в виду: "."<a href='javascript:void(0)' onclick='getText(this)' id='text0'>$Username@rambler.ru</a>";
	}
	
    similar_text($Domain, "gmail.com", $percent);
    if (($percent>80) and ($percent<100))
	{ 
	  $check_in = 1;
	  return " Возможно, вы имели в виду: "."<a href='javascript:void(0)' onclick='getText(this)' id='text0'>$Username@gmail.com</a>";
	}
  
    similar_text($Domain, "yandex.ru", $percent);
    if (($percent>80) and ($percent<100))
	{ 
	$check_in = 1;
	return " Возможно, вы имели в виду: "."<a href='javascript:void(0)' onclick='getText(this)' id='text0'>$Username@yandex.ru</a>";
	}
	
    if ($check_in ==0)
		return "";
  }
  
  /*
   * Синтаксическая проверка е-mail регулярным выражением.
  */
  	function check_syntax($Email)
  {
	$pattern = "/^[._-a-zA-Z0-9-]+@[.a-zA-Z0-9-]+.[a-z]{2,6}$/i";
     return preg_match($pattern, $Email);
  }	



  }

?>
