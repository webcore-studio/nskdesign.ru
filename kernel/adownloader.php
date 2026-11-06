<?php
/*
 * Продвинутый даунлоадер
*/
class adownloader
  {
  /*
   * Дефолтные настройки
  */
 	var $debug						      = true;
 	var $proxy_list_url 	      = 'http://pchecker.kinodb.org/proxy5/filter/?key=a3h9q1ad&worked=1&time=30&post=1&referer=1&cookies=1&anon=1';

 	var $CURLOPT_CONNECTTIMEOUT = 15;
 	var $CURLOPT_TIMEOUT        = 40;
 	var $CURLOPT_RETRYS         = 10;
 	var $CURLOPT_DLMINSIZE      = 256;
 	var $CURLOPT_USECOOKIE      = false;

 	var $CURLOPT_AUTOPROXY      = true;
 	var $CURLOPT_AUTOAGENT      = true;

 	var $CURLOPT_CACHING	      = false;
 	var $CURLOPT_CACHETTL       = 86400; //Сутки

  /*
   * Загрузка страницы
  */
  function download ($url, $options=null)
    {
    $url = trim ($url,'/');
    //Проверки
    if (empty($url) || $url=='' || strpos($url, 'http://')===false)
    	{
    	$this->log(LOG_WARNING, 'Передан кривой $url - '.$url);
      return false;
      }

    //Проверяем наличие в кеше
    if ($this->CURLOPT_CACHING)
      {
      $cache_result = $this->cache_get($url, $options);
      if ($cache_result) return $cache_result; //Есть в кеше возвращаем
      }

    // инициализация сеанса
    $ch = curl_init();

    // Подготавливаем файл кукисов
    $host_name  = $this->extract_host_name($url);
    $cookiedir  = './cache/adownloader/cookie/';
    $cookiefile = $cookiedir.$host_name.'.file';

    //Создаем директорию если нет
    if (!file_exists($cookiedir))
      {
      //Атрибуты
      global $sysConfig;
      $file_perms = $sysConfig['default.file.perms'];
      $dir_perms = $sysConfig['default.dir.perms'];
      mkdir($cookiedir, $dir_perms, true);
      }

		if ($this->CURLOPT_USECOOKIE)
		  {
      curl_setopt($ch, CURLOPT_COOKIEFILE,   $cookiefile);
      curl_setopt($ch, CURLOPT_COOKIEJAR,    $cookiefile);
      }

    // установка URL и других необходимых параметров
    curl_setopt($ch, CURLOPT_URL,            $url);
    curl_setopt($ch, CURLOPT_REFERER,        'http://'.$host_name);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->CURLOPT_CONNECTTIMEOUT);
    curl_setopt($ch, CURLOPT_TIMEOUT,        $this->CURLOPT_TIMEOUT);
    curl_setopt($ch, CURLOPT_HEADER,         1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    // Устанавливаем параметры переданные пользователем
    if (!empty($options))
      {
      foreach ($options as $option_name=>$option_value)
				curl_setopt($ch, $option_name, $option_value);
      }


    //Производим загрузку

    for ($retry=0; $retry < $this->CURLOPT_RETRYS; $retry++) //Выполняем до CURLOPT_RETRYS
      {
      // Параметр анонимности
  	 	if ($this->CURLOPT_AUTOAGENT) //Юзер агент
        curl_setopt($ch, CURLOPT_USERAGENT, $this->agent_get_rand());
  	 	  else
          curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; Maxthon; .NET CLR 1.1.4322; .NET CLR 2.0.50727)');

  	 	if ($this->CURLOPT_AUTOPROXY) //Прокси
  	 	  {
  	 	  $proxy_rand = $this->proxy_get_rand();
  	 	  sys_log('adownloader', LOG_INFO, "Using proxy - $proxy_rand");
        curl_setopt($ch, CURLOPT_PROXY, $proxy_rand);
        }

      // загрузка страницу
      $response = curl_exec($ch);

      // Проверили корректность отработки
      $curl_errno = curl_errno($ch);
      if ($curl_errno==CURLE_OK)
        {
        // Распарсили ответ
        list($response_headers, $response_body) = explode("\r\n\r\n",$response,2);

        // Парсим код ответа
        $header_lines = explode ("\r\n", $response_headers);
        // Парсим первую строку
        $header_line = explode (' ', $header_lines[0]);
        $response_code = (int)trim($header_line[1]);

        //Подготавливаем к возвращению результат
        $result = array ('response_code'    => $response_code,
                         'response_cached'  => false,
                         'response_headers' => $response_headers,
                         'response_info'    => curl_getinfo($ch),
                         'response_body'    => $response_body);

        //Логические проверки

        //Коды сообщающие об успехе операции
        if ($response_code==200 || $response_code==404 || $response_code==302 || $response_code==301)
          {
          if ($response_code==200) //При коде 200 - доп проверки
            {
            //Проверка содержимого на корректность загрузки
            if ($result['response_info']['size_download'] < $this->CURLOPT_DLMINSIZE)
              {
              $result['response_code'] = 18; //CURLE_PARTIAL_FILE :)
              continue;
              }

            //Фактического размера на соответствие размера в заголовке
            if ($result['response_info']['download_content_length'] >0 &&
            		$result['response_info']['download_content_length'] != $result['response_info']['size_download'])
              {
              $result['response_code'] = 18; //CURLE_PARTIAL_FILE :)
              continue;
              }
            }

          //Проверка на стоп слова
          if ($this->have_stop_word($url, $result['response_body']))
            {
            $result['response_code'] = 18; //CURLE_PARTIAL_FILE :)
            continue;
            }

	        //Если все корректно - выходим из цыкла
	        break;
          }
        }
      }

    // завершение сеанса и освобождение ресурсов
    curl_close($ch);


    //Кэшируем страниц
    if ($this->CURLOPT_CACHING)
      {
	    if ($response_code==200 || $response_code==404 || $response_code==302 || $response_code==301)
	      $this->cache_set ($url, $options, $result);
	    }

    return $result;
    }

  /*
   * Основная функция выполняющая многопотоковую заргузку
  */
  function multi_download ($urls, $options=null)
    {
    //Проверки
    if (empty($urls) && !is_array($urls))	return false;

    //Результирующий массив
    $result = array();

    for ($retry=0; $retry < $this->CURLOPT_RETRYS; $retry++) //Выполняем до CURLOPT_RETRYS
      {
      //Производим загрузку
      $subresult = $this->multi_sub_download($urls, $options);

      //Проходим по результатам и если все удачно - удаляем из списка урлов
      foreach ($subresult as $url=>$url_result)
        {
        $response_code = $url_result['response_code'];

        if ($response_code==200 || $response_code==404 || $response_code==302 || $response_code==301)
          {
          //Урл успешно скачан
          $result[$url] = $url_result;
          //Удаляем из задач
          foreach ($urls as $key=>$value)
            if ($value==$url) unset($urls[$key]);
          }
        }

      //Если урлов нет - значит хэппи энд
      if (sizeof($urls)<1) break;
      }

    return $result;
    }

  /*
   * Под функция выполняющая многопотоковую заргузку
  */
  function multi_sub_download ($urls, $options=null)
    {
    //Проверки
    if (empty($urls) && !is_array($urls))	return false;

		// Набор курл дескрипторов
	  $curly = array();

	  // data to be returned
	  $result = array();

	  // multi handle
	  $mh = curl_multi_init();

		//Проходим по всем урлам
    foreach ($urls as $url_id=>$url)
      {
	    $url = trim ($url,'/');

	    //Проверки
	    if (empty($url) || $url=='' || strpos($url, 'http://')===false)
	    	{
	    	$this->log(LOG_WARNING, 'Передан кривой $url - '.$url);
	      continue;
	      }

      //Проверяем наличие в кеше
      if ($this->CURLOPT_CACHING)
        {
        $cache_result = $this->cache_get($url, $options);
        if ($cache_result)
          {
          $result[$url] = $cache_result;
		      continue;
          }
        }

	    // инициализация сеанса
	    $ch = curl_init();

      // Подготавливаем файл кукисов
      $host_name  = $this->extract_host_name($url);
      $cookiedir  = './cache/adownloader/cookie/';
      $cookiefile = $cookiedir.$host_name.'.file';

      //Создаем директорию если нет
      if (!file_exists($cookiedir))
        {
        //Атрибуты
        global $sysConfig;
        $file_perms = $sysConfig['default.file.perms'];
        $dir_perms = $sysConfig['default.dir.perms'];
        mkdir($cookiedir, $dir_perms, true);
        }

  		if ($this->CURLOPT_USECOOKIE)
  		  {
        curl_setopt($ch, CURLOPT_COOKIEFILE,   $cookiefile);
        curl_setopt($ch, CURLOPT_COOKIEJAR,    $cookiefile);
        }

      // установка URL и других необходимых параметров
      curl_setopt($ch, CURLOPT_URL,            $url);
      curl_setopt($ch, CURLOPT_REFERER,        'http://'.$host_name);
  		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->CURLOPT_CONNECTTIMEOUT);
      curl_setopt($ch, CURLOPT_TIMEOUT,        $this->CURLOPT_TIMEOUT);
      curl_setopt($ch, CURLOPT_HEADER,         1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

      // Параметр анонимности
  	 	if ($this->CURLOPT_AUTOAGENT) //Юзер агент
        curl_setopt($ch, CURLOPT_USERAGENT, $this->agent_get_rand());
  	 	  else
          curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; Maxthon; .NET CLR 1.1.4322; .NET CLR 2.0.50727)');

  	 	if ($this->CURLOPT_AUTOPROXY) //Прокси
  	 	  {
  	 	  $proxy_rand = $this->proxy_get_rand();
  	 	  sys_log('adownloader', LOG_INFO, "Using proxy - $proxy_rand");
        curl_setopt($ch, CURLOPT_PROXY, $proxy_rand);
        }

      // Устанавливаем параметры переданные пользователем
      if (!empty($options))
        {
        foreach ($options as $option_name=>$option_value)
  				curl_setopt($ch, $option_name, $option_value);
        }

      //Добавляем заголовок запрса в масив запросов
      $curly[$url] = $ch;
      curl_multi_add_handle($mh, $curly[$url]);
      }

    //Производим многопоточную загрузку
	  $running = null;
	  do
	    {
	    curl_multi_exec($mh, $running);
			usleep(1);
		  }
		  while($running > 0);

	  //Складываем результат загрузки в массив
	  foreach($curly as $url => $ch)
	    {
	    //Результат загрузки
	    $response = curl_multi_getcontent($ch);

      // Проверили корректность отработки
      $curl_errno = curl_errno($ch);
      if ($curl_errno==CURLE_OK)
        {
        // Распарсили ответ
        list($response_headers, $response_body) = explode("\r\n\r\n",$response,2);

        // Парсим код ответа
        $header_lines = explode ("\r\n", $response_headers);
        // Парсим первую строку
        $header_line = explode (' ', $header_lines[0]);
        $response_code = (int)trim($header_line[1]);

        //Подготавливаем к возвращению результат
        $url_result = array ('response_code'    => $response_code,
                   		       'response_cached'  => false,
                             'response_headers' => $response_headers,
                             'response_info'    => curl_getinfo($ch),
                             'response_body'    => $response_body);

        //Логические проверки

        //Коды сообщающие об успехе операции
        if ($response_code==200)
          {
          //Проверка содержимого на корректность загрузки
          if ($url_result['response_info']['size_download'] < $this->CURLOPT_DLMINSIZE)
            {
            $url_result['response_code'] = 18; //CURLE_PARTIAL_FILE :)
            }

          //Фактического размера на соответствие размера в заголовке
          if ($url_result['response_info']['download_content_length'] >0 &&
          		$url_result['response_info']['download_content_length'] != $url_result['response_info']['size_download'])
            {
            $url_result['response_code'] = 18; //CURLE_PARTIAL_FILE :)
            }
          }

        //Проверка на стоп слова
        if ($this->have_stop_word($url, $url_result['response_body']))
          $url_result['response_code'] = 18; //CURLE_PARTIAL_FILE :)

        }
        else //Банальная ошибка загрузки
          {
          //Подготавливаем к возвращению результат
          $url_result = array ('response_code'    => $curl_errno,
                     		       'response_cached'  => false,
                               'response_info'    => curl_getinfo($ch));
          }

      //Помещаем в резльтирующий массив
      $result[$url] = $url_result;

      //Кэшируем страницу
      if ($this->CURLOPT_CACHING)
        {
  	    if ($response_code==200 || $response_code==404 || $response_code==302 || $response_code==301)
  	      $this->cache_set ($url, $options, $url_result);
  	    }

			//
 	    curl_multi_remove_handle($mh, $ch);
		  }

	  //Освобаждаем всю память
	  curl_multi_close($mh);

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
  function cache_set ($url, $options, $download_result)
    {
    //Проверка включенноли кеширование
    if (!$this->CURLOPT_CACHING) return false;

    //Если неверный ответ - не кешируем страницу
    if ($download_result['response_code']!=200 && $download_result['response_code']!=404 && $download_result['response_code']!=302 && $download_result['response_code']!=301) return false;
    sys_log('video->video_downloader', LOG_DEBUG, 'Положили в кэш url: '.$url);

    //Допалняем имя $postfields
    if (!empty($options[CURLOPT_POSTFIELDS])) $url.= '?'.$options[CURLOPT_POSTFIELDS];

		//Информируем что файл был закешированн
    $download_result['response_cached'] = true;

    //На основании ключа - сохраняем
    $url_sha = sha1($url);
    $filedir  = './cache/adownloader/files/'.$url_sha[0].'/'.$url_sha[1].'/'.$url_sha[2].'/';
    $filename = $filedir.$url_sha;

    //Создаем директорию если нет
    if (!file_exists($filedir))
      {
      //Атрибуты
      global $sysConfig;
      $file_perms = $sysConfig['default.file.perms'];
      $dir_perms = $sysConfig['default.dir.perms'];
      mkdir($filedir, $dir_perms, true);
      }

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
  function cache_get ($url, $options)
    {
    //Проверка включенноли кеширование
    if (!$this->CURLOPT_CACHING) return false;

    //Допалняем имя $postfields
    if (!empty($options[CURLOPT_POSTFIELDS])) $url.= '?'.$options[CURLOPT_POSTFIELDS];

    //Имя файла
    $url_sha = sha1($url);
    $filedir  = './cache/adownloader/files/'.$url_sha[0].'/'.$url_sha[1].'/'.$url_sha[2].'/';
    $filename = $filedir.$url_sha;

    //Проверяем сущестоввание файла
    if (!file_exists($filename)) return false; //Если нет файла - значит пусто

    //Проверяем ttl
    $mod_time = filemtime($filename);
    if ($mod_time + $this->CURLOPT_CACHETTL < time()) return false; //Время жизни истекло

    //Загружаем файл из кеша
    $handle = fopen($filename, 'rb');
    $download_result = unserialize (fread($handle, filesize($filename)));
    fclose($handle);

    //На всякий случай еше раз подчеркиваем что файл из кеша
    $download_result['response_cached'] = true;

    return $download_result;
    }

  /*
   * Логируем
  */
  function log ($level=LOG_DEBUG, $message='', $vars=null)
    {
    if (!$this->debug) return false; // Отладка отключенна

		sys_log ('adownloader', $level=LOG_DEBUG, $message='', $vars=null);
    return true;
    }


  /*
   * Возвращает случайный юзер агент
  */
  function agent_get_rand ()
    {
    $agents = array('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)',
                    'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; ru) Opera 8.01',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows 98)',
                    'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.6) Gecko/20050225 Firefox/1.0.1',
                    'Mozilla/4.0 (compatible; MSIE 5.01; Windows 98)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; MyIE2; .NET CLR 1.1.4322)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Maxthon)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1) Opera 7.50 [en]',
                    'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.7.10) Gecko/20050717 Firefox/1.0.6',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 3.0 (build 00614))',
                    'Mozilla/5.0 (Windows; U; Windows NT 5.0; ru-RU; rv:1.7.10) Gecko/20050717 Firefox/1.0.6',
                    'Opera/6.01 (Windows 2000; U) [ru]',
                    'Mozilla/5.0 (Windows; U; Windows NT 5.0; ru-RU; rv:1.7.7) Gecko/20050414 Firefox/1.0.3',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; FunWebProducts-MyWay; MRA 4.2 (build 01102))',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; snprtz|dialno; HbTools 4.7.0)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; .NET CLR 1.1.4322)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; MRA 4.0 (build 00768))',
                    'Mozilla/4.0 (compatible; MSIE 5.5; Windows 98; Win 9x 4.90; OptusIE55-31)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 4.1 (build 00961); .NET CLR 1.1.4322)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; .NET CLR 1.1.4322)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 4.1 (build 00975); .NET CLR 1.1.4322)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 4.2 (build 01102))',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Hotbar 4.6.1)',
                    'Mozilla/4.0 (compatible; MSIE 5.0; Windows 98)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows 2000) Opera 7.0 [en]',
                    'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)',
                    'Mozilla/4.0 (compatible; MSIE 5.0; Windows 98; DigExt)',
                    'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.8a5) Gecko/20041122');
    return $agents[rand(0, sizeof($agents)-1)];
    }

  /*
   * Возвращает случайный прокси
  */
  function proxy_get_rand ()
    {
    //Кешируем список прокси серверов
    static $proxys = array();
    if (empty($proxys)) $proxys = file($this->proxy_list_url);

    return trim($proxys[rand(0, sizeof($proxys)-1)]);
    }

  /*
   * Проверка загруженного содержимого на предмет стоп слов
  */
  function have_stop_word ($url, $html)
    {
    $stop_word = array('Sorry, the requested page is unavailable',
    									 'Извините, для данного IP адреса доступ запрещен',
    							     'Blocked URL',);

		foreach ($stop_word as $word)
		  if (stripos($html, $word) !== false) return true;

		return false;
    }

  }


?>