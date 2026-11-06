<?php
/*
 * Отправка письма
*/
function sysMail($to, $subject, $body, $is_html=false)
{
	//Значит создали обьект
	require_once('kernel/phpmailer/class.phpmailer.php');
	$mail = new PHPMailer();

	//Перекодируем все в кои8
	//$subject = convert_cyr_string($subject,"w","k");
	//$body = convert_cyr_string($body,"w","k");

	//Конфигурируем базовые настройки
	$mail->Sender = sysModGetVar('SYS_config','mail_from');
	$mail->From = sysModGetVar('SYS_config','mail_from');
	$mail->FromName = sysModGetVar('SYS_config','mail_fromname');
	//$mail->FromName = convert_cyr_string(sysModGetVar('SYS_config','mail_fromname'),"w","k");
	//$mail->CharSet = 'koi8-r';

  if ($sysConfig['site.encoding']=='utf-8') $mail->CharSet = 'utf-8';
else $mail->CharSet = 'utf-8';
	
	//$mail->WordWrap = $wordwrap;
	//$mail->Priority = $priority;
	//$mail->Encoding = $encoding;

	//Составляем письмо
	$mail->IsHTML($is_html);
	$mail->Subject  =  $subject;
	$mail->Body     =  $body;
	
	//Может быть один емайл, а может быть и много :)) и каждый надо проверить - он емайл или список емайлов..
	$mail_indata = array();
	if (is_array ($to))    {
		$mail_indata = $to;
    }
    else {
		$mail_indata = array($to);
    }
	
	$mail_check = array();
	foreach ($mail_indata as $k=>$amail) {
		$mail_parse = preg_split("/[ ,;]+/", $amail, -1, PREG_SPLIT_NO_EMPTY);
		$mail_check = array_merge($mail_check, $mail_parse);
	}
	
	$mail_check = array_unique($mail_check);
	
	foreach ($mail_check as $amail)
		$mail->AddAddress($amail);

	//Конфигурируем метод отправки
	$mail_method = sysModGetVar('SYS_config','mail_method');

	//СМТП
	if ($mail_method == 'smtp')   {
		$mail->IsSMTP();
		$mail->Host = sysModGetVar('SYS_config','mail_smtphost');
		$mail->SMTPAuth = false;
    }

	//smtpauth
	if ($mail_method == 'smtpauth')   {
		$mail->IsSMTP();
		$mail->Host = sysModGetVar('SYS_config','mail_smtphost');
		$mail->SMTPAuth = true; // turn on SMTP authentication
		$mail->Username = sysModGetVar('SYS_config','mail_smtpuser');
		$mail->Password = sysModGetVar('SYS_config','mail_smtppass');
    }

	//sendmail
	if ($mail_method == 'sendmail')  {
		$mail->IsSendmail();
		$mail->Sendmail = sysModGetVar('SYS_config','mail_sendmailpath');
    }

	//qmail
	if ($mail_method == 'qmail')   {
		$mail->IsQmail();
    }

	//PHP mail()
	if ($mail_method == 'mail')  {
		$mail->IsMail();
    }
	
	//кладем все в файлик, если включено логирование
	$save_log = sysModGetVar('SYS_config', 'mail_savelog');
	if ($save_log) {
		$tosave = array();
		$tosave[] = "FROM: ".$mail->FromName."(".$mail->From.")";

		
		$toto = "";
		foreach ($mail->to as $to)
			$toto.=$to[0].",";
		$tosave[] = "TO: ".$toto;
		$tosave[] = "SUBJECT:".$mail->Subject;
		$tosave[] = "";
		$tosave[] = $mail->Body;

		$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/files/mail/';
		$fname = "letter_".date("d_m_y___H_i___U").".html";
	}

	if ($mail->Send())  {
		if ($save_log)
			file_put_contents($uploaddir.'success/'.$fname,implode("\n\t",$tosave));	
		return true;
    }
	
	if ($save_log)
		file_put_contents($uploaddir.'error/'.$fname,implode("\n\t",$tosave));
	return false;
}

/* Увеличиваем вес элимента в базе
 * Пераетса массив в формате key=>value
 */
function sysDBWeightMoveUp ($systable, $weight, $where='')
  {
  //Если самый первый некуда уже двигать
  if ($weight==1){return true;};

  $column = sysDBGetColumns($systable);
  //Подготавливаем $where
  $where=str_replace('WHERE ','',$where);

  if ($where!='')
    {
    $where="AND $where";
    };
  //Передвигаемый элимент
  $UpedEliment=sysDbSelect ($systable, $column, "WHERE $column[weight]='$weight' $where");
  //Предвидущий элимент
  $PreviusEliment=sysDbSelect ($systable, $column, "WHERE $column[weight]='".($weight-1)."' $where");
  //
  //Собственно сама растусовка
  $PreviusEliment['weight']=$PreviusEliment['weight']+1;
  $UpedEliment['weight']=$UpedEliment['weight']-1;

  sysDbUpdate ($systable, $PreviusEliment, "WHERE $column[id]='".$PreviusEliment['id']."'");
  sysDbUpdate ($systable, $UpedEliment, "WHERE $column[id]='".$UpedEliment['id']."'");
  return true;
  };

/* Увеличиваем вес элимента в базе
 * Пераетса массив в формате key=>value
 */
function sysDBWeightMoveDown ($systable, $weight, $where='')
  {
  //Если самый нижний некуда уже двигать
  $MaxWeight=sysDbMaxWeight ($systable);
  if ($weight==$MaxWeight){return true;};

  $column =  sysDBGetColumns($systable);
  //Подготавливаем $where
  $where=str_replace('WHERE ','',$where);
  if ($where!='')
    {
    $where="AND $where";
    };

  //Передвигаемый элимент
  $UpedEliment=sysDbSelect ($systable, $column, "WHERE $column[weight]='$weight' $where");
  //Следующий элимент
  $NextEliment=sysDbSelect ($systable, $column, "WHERE $column[weight]='".($weight+1)."' $where");
  //
  //Собственно сама растусовка
  $NextEliment['weight']=$NextEliment['weight']-1;
  $UpedEliment['weight']=$UpedEliment['weight']+1;

  sysDbUpdate ($systable, $NextEliment, "WHERE $column[id]='".$NextEliment['id']."'");
  sysDbUpdate ($systable, $UpedEliment, "WHERE $column[id]='".$UpedEliment['id']."'");
  return true;
  };

/*
 * Удаления веса из таблицы
*/
function sysDBWeightDelete ($systable, $weight, $where='')
  {
    global $dbconn;
  //Получили столбцы
  $column = sysDBGetColumns($systable);
  //Подготавливаем $where
  $where=str_replace('WHERE ','',$where);
  if ($where!='')
    {
    $where="AND $where";
    };

  $sql = "UPDATE $systable SET $column[weight]=$column[weight]-1
          WHERE $column[weight]>'$weight' $where";

  mysqli_query($dbconn, $sql);

  if (mysqli_connect_errno()) sysException ('sysDBWeightDelete', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
  return true;
  };

/*
 * Возвращает спиисок временных зон
*/
function sysGetTzList ()
  {
  /*
   * Timezone information
   */
  $tzinfo = array('0'    => '(GMT -12:00 hours) Eniwetok, Kwajalein',
                '1'    => '(GMT -11:00 hours) Midway Island, Samoa',
                '2'    => '(GMT -10:00 hours) Hawaii',
                '3'    => '(GMT -9:00 hours) Alaska',
                '4'    => '(GMT -8:00 hours) Pacific Time (US & Canada)',
                '5'    => '(GMT -7:00 hours) Mountain Time (US & Canada)',
                '6'    => '(GMT -6:00 hours) Central Time (US & Canada), Mexico City',
                '7'    => '(GMT -5:00 hours) Eastern Time (US & Canada), Bogota, Lima, Quito',
                '8'    => '(GMT -4:00 hours) Atlantic Time (Canada), Caracas, La Paz',
                '8.5'  => '(GMT -3:30 hours) Newfoundland',
                '9'    => '(GMT -3:00 hours) Brazil, Buenos Aires, Georgetown',
                '10'   => '(GMT -2:00 hours) Mid-Atlantic',
                '11'   => '(GMT -1:00 hours) Azores, Cape Verde Islands',
                '12'   => '(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia',
                '13'   => '(GMT +1:00 hours) CET(Central Europe Time), Brussels, Copenhagen',
                '14'   => '(GMT +2:00 hours) EET(Eastern Europe Time), Kaliningrad, South Africa',
                '15'   => '(GMT +3:00 hours) Baghdad, Kuwait, Riyadh, Moscow, St. Petersburg',
                '15.5' => '(GMT +3:30 hours) Tehran',
                '16'   => '(GMT +4:00 hours) Abu Dhabi, Muscat, Baku, Tbilisi',
                '16.5' => '(GMT +4:30 hours) Kabul',
                '17'   => '(GMT +5:00 hours) Ekaterinburg, Islamabad, Karachi, Tashkent',
                '17.5' => '(GMT +5:30 hours) Bombay, Calcutta, Madras, New Delhi',
                '18'   => '(GMT +6:00 hours) Almaty, Dhaka, Colombo',
                '19'   => '(GMT +7:00 hours) Bangkok, Hanoi, Jakarta',
                '20'   => '(GMT +8:00 hours) Beijing, Perth, Singapore, Hong Kong, Chongqing',
                '21'   => '(GMT +9:00 hours) Tokyo, Seoul, Osaka, Sapporo, Yakutsk',
                '21.5' => '(GMT +9:30 hours) Adelaide, Darwin',
                '22'   => '(GMT +10:00 hours) EAST(East Australian Standard)',
                '23'   => '(GMT +11:00 hours) Magadan, Solomon Islands, New Caledonia',
                '24'   => '(GMT +12:00 hours) Auckland, Wellington, Fiji, Kamchatka');
  return ($tzinfo);
  };

/*
 * Возвращает спиисок языков в системе
*/
function sysGetLangList()
  {
  $LangList=array();
  //Доступные языки
  $handle = opendir('lang');
  while ($f = readdir($handle))
    {
    if ($f != '.' && $f != '..' && $f != 'CVS' && !preg_match("/[.]/",$f))
      {
//      $LangList[$f] = $alllang[$f];
      $LangList[$f] = $f;
      }
    }
  closedir($handle);
//  sort($LangList);
  return ($LangList);
  };

////////////////////////////////////////////////////////////////////////////////
/*
 * Клас, который выполняет все функции загрузки с внешних сайтов
*/
////////////////////////////////////////////////////////////////////////////////

class downloader
  {
  /*
   * Загрузка страницы
  */
  function download ($url, $referer='', $force=false, $postfields=null, $followlocation=false)
    {
    //Проверяем наличие в кеше
    if (!$force)
      {
      $cache_result = $this->cache_get($url, $postfields);
      if ($cache_result) return $cache_result; //Есть в кеше возвращаем
      }

    // инициализация сеанса
    $ch = curl_init();

    // Подготавливаем файл кукисов
    $host_name = $this->extract_host_name($url);
    if (!file_exists('./cache/downloader/'.$host_name))
      {
      //Атрибуты
      global $sysConfig;
      $file_perms = $sysConfig['default.file.perms'];
      $dir_perms = $sysConfig['default.dir.perms'];
      mkdir('./cache/downloader/'.$host_name, $dir_perms, true);
      }
    $cookiefile = './cache/downloader/'.$host_name.'/cookie.file';
    curl_setopt($ch, CURLOPT_COOKIEFILE,   $cookiefile);
    curl_setopt($ch, CURLOPT_COOKIEJAR,    $cookiefile);

    // установка URL и других необходимых параметров
    curl_setopt($ch, CURLOPT_URL,            $url);
    curl_setopt($ch, CURLOPT_REFERER,        $referer);
    curl_setopt($ch, CURLOPT_TIMEOUT,        20);
    curl_setopt($ch, CURLOPT_HEADER,         1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $followlocation);
    curl_setopt($ch, CURLOPT_TIMEOUT,        60);
    curl_setopt($ch, CURLOPT_USERAGENT,      'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; Maxthon; .NET CLR 1.1.4322; .NET CLR 2.0.50727)');
    curl_setopt($ch, CURLOPT_PROXY,          'nocona.no-ip.org:3181');

    // Возможно надо передать post параметры
    if (!empty($postfields))
      {
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
      }

    // загрузка страницу
    $response = curl_exec($ch);

    // Проверили корректность отработки
    $curl_errno = curl_errno($ch);
    if ($curl_errno!=CURLE_OK)
      {
      sys_log('video->video_downloader', LOG_WARNING, 'Ощибка загрузки страницы попытка 1, code: '.$curl_errno.' ('.curl_error($ch).') url: '.$url.' referer: '.$referer);
      //Делаем 2й ретрай
      $response = curl_exec($ch);
      $curl_errno = curl_errno($ch);
      if ($curl_errno!=CURLE_OK)
        {
        $result = array ('response_code'    => $curl_errno,
                         'response_cached'  => false);
        sys_log('video->video_downloader', LOG_WARNING, 'Ощибка загрузки страницы попытка 2, code: '.$curl_errno.' ('.curl_error($ch).') url: '.$url.' referer: '.$referer);
        }
      return $result;
      }

    // Распарсили ответ
    list($response_headers,$response_body) = explode("\r\n\r\n",$response,2);

    // Парсим код ответа
    $header_lines = explode ("\r\n", $response_headers);
    // Парсим первую строку
    $header_line = explode (' ', $header_lines[0]);
    $response_code = (int)trim($header_line[1]);

    // завершение сеанса и освобождение ресурсов
    curl_close($ch);

    //Подготавливаем к возвращению результат
    $result = array ('response_code'    => $response_code,
                     'response_cached'  => false,
                     'response_headers' => $response_headers,
                     'response_body'    => $response_body);

    //Отрабатываем перегрузки серверов
    if (md5($result['response_body'])=='921fe45d9d561f58bbb0b9ae4e94dfa2') //форум временно отключен
      $result['response_code'] = 503;

    //Кэшируем страницу или пишим в лог ошибку
    if ($response_code==200 || $response_code==404 || $response_code==302 || $response_code==301) $this->cache_set ($url, $result, $postfields);
     else sys_log('video->video_downloader', LOG_WARNING, 'Ощибка загрузки страницы, code: '.$response_code.' url: '.$url.' referer: '.$referer);

    return $result;
    }

  /*
   * Извлекаем имя домена из урла
  */
  function extract_host_name ($url)
    {
    // Извлекаем имя хоста из URL
    $matches = array();
    preg_match("/^(http:\/\/)?([^\/]+)/i", $url, $matches);
    $host_name = $matches[2];
    return $host_name;
    }

  /*
   * Извлекаем имя файла и гет параметры из урла
  */
  function extract_file_name ($url)
    {
    // Извлекаем имя хоста из URL
    $matches = array();
    preg_match("/^(http:\/\/)?([^\/]+)(.*)/i", $url, $matches);
    $file_name = trim ($matches[3], '/');
    return $file_name;
    }

  /*
   * Сохранение страницы в кеше
  */
  function cache_set ($url, $download_result, $postfields)
    {
    //Допалняем имя $postfields
    if (!empty($postfields)) $url.= '?'.$postfields;
    //Если неверный ответ - не кешируем страницу
    if ($download_result['response_code']!=200 && $download_result['response_code']!=404 && $download_result['response_code']!=302 && $download_result['response_code']!=301) return false;
    sys_log('video->video_downloader', LOG_DEBUG, 'Положили в кэш url: '.$url);

    $download_result['response_cached'] = true;

    $host_name = $this->extract_host_name($url);
    if (strlen($url)>200) $filename = './cache/downloader/'.$host_name.'/'.sysVarPrepForOS(sha1($this->extract_file_name($url)));
      else $filename = './cache/downloader/'.$host_name.'/'.sysVarPrepForOS($this->extract_file_name($url));

    $handle = fopen($filename, 'w');
    fwrite($handle, serialize($download_result));
    fclose($handle);

    //Устанавливаем атрибуты
    global $sysConfig;
    $file_perms = $sysConfig['default.file.perms'];
    chmod($filename, $file_perms);
    return true;
    }

  /*
   * Забираем страницу из кеша
  */
  function cache_get ($url, $postfields)
    {
    //Допалняем имя $postfields
    if (!empty($postfields)) $url.= '?'.$postfields;
    //Проверяем сущестоввание файла
    $host_name = $this->extract_host_name($url);
    if (strlen($url)>200) $filename = './cache/downloader/'.$host_name.'/'.sysVarPrepForOS(sha1($this->extract_file_name($url)));
      else $filename = './cache/downloader/'.$host_name.'/'.sysVarPrepForOS($this->extract_file_name($url));
    if (!file_exists($filename)) return; //Если нет файла - значит пусто

    //Загружаем файл из кеша
    $handle = fopen($filename, 'rb');
    $download_result = unserialize (fread($handle, filesize($filename)));
    fclose($handle);

    //На всякий случай еше раз подчеркиваем что файл из кеша
    $download_result['response_cached'] = true;

    return $download_result;
    }

  }


?>