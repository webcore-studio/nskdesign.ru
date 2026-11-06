<?php
/*
 * Главная админская страница
*/
function content_admin_main()   {
	sysRedirect(sysModURL('content','user','main'));
}

/*
 * Страница конфигурирования модуля 
*/
function content_admin_config_modify()  {
  
	//Прелюдие как у всех модулей
	$sysObject = 'content::admin::config::modify';

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

	// Класс шаблонов
	$sysTpl = new sysTpl;
	$sysModTpl=sysTplWay ($sysObject);
	$sysTpl->caching = false;

	// класс
	sysModClassLoad ('content');
	$content = new content;

	// Переменные модуля

	$sysTpl->assign('orig_image_width', sysModGetVar('content','orig_image_width'));
	$sysTpl->assign('orig_image_height', sysModGetVar('content','orig_image_height'));
	$sysTpl->assign('orig_image_qual', sysModGetVar('content','orig_image_qual'));
	$sysTpl->assign('orig_image_changequal', sysModGetVar('content','orig_image_changequal'));
	$sysTpl->assign('orig_image_maxsize', sysModGetVar('content','orig_image_maxsize'));

	$sysTpl->assign('image_width', sysModGetVar('content','image_width'));
	$sysTpl->assign('image_height', sysModGetVar('content','image_height'));
	$sysTpl->assign('image_qual', sysModGetVar('content','image_qual'));
	$sysTpl->assign('image_method', sysModGetVar('content','image_method'));
	
	$sysTpl->assign('thumb_width', sysModGetVar('content','thumb_width'));
	$sysTpl->assign('thumb_height', sysModGetVar('content','thumb_height'));
	$sysTpl->assign('thumb_qual', sysModGetVar('content','thumb_qual'));
	$sysTpl->assign('thumb_method', sysModGetVar('content','thumb_method'));

	$sysTpl->assign('logo_width', sysModGetVar('content','logo_width'));
	$sysTpl->assign('logo_height', sysModGetVar('content','logo_height'));
	$sysTpl->assign('logo_qual', sysModGetVar('content','logo_qual'));
	$sysTpl->assign('logo_method', sysModGetVar('content','logo_method'));

	$sysTpl->assign('logo_thumb_width', sysModGetVar('content','logo_thumb_width'));
	$sysTpl->assign('logo_thumb_height', sysModGetVar('content','logo_thumb_height'));
	$sysTpl->assign('logo_thumb_qual', sysModGetVar('content','logo_thumb_qual'));
	$sysTpl->assign('logo_thumb_method', sysModGetVar('content','logo_thumb_method'));

	$sysTpl->assign('url_control', sysModGetVar('content','url_control'));
	$sysTpl->assign('ajax_paging', sysModGetVar('content','ajax_paging'));
	$sysTpl->assign('names_control', sysModGetVar('content','names_control'));
	$sysTpl->assign('upload_avail', sysModGetVar('content','upload_avail'));
	$sysTpl->assign('order_mail', sysModGetVar('content','order_mail'));
	$sysTpl->assign('rss_doc_count', sysModGetVar('content','rss_doc_count'));
	$sysTpl->assign('rss_folders_list', unserialize(sysModGetVar('content','rss_folders_list')));
	
  
	$folders_list = $content->folder_ItemsTreeBuild(0,0);
	$sysTpl->assign('folders_list', $folders_list);
  

	//BrowseIn
	$browsein = array();
	$browsein[] = array ( 'url' => sysModURL('content', 'user', 'main'), 'displayname' => 'Контент' );
	$browsein[] = array ( 'url' => sysModURL('content', 'admin', 'config_modify'), 'displayname' => 'Конфигурация');
	$sysTpl->assign('browsein', $browsein);

	//Возвращаем результат
	$result['object'] = $sysObject;
	$result['browsein'] = $browsein;
	$result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

/*
 * Сохраняем конфигурацию настроек
*/
function content_admin_config_update()  {

	$sysObject = 'content::admin::config::update';
  
	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

	//Забираем переменные со входа 
  
	list ($orig_image_width, $orig_image_height, $orig_image_qual, $orig_image_changequal, $orig_image_maxsize) =
		sysVarCleanFromInput('orig_image_width', 'orig_image_height', 'orig_image_qual', 'orig_image_changequal', 'orig_image_maxsize');
  
	sysModSetVar ('content', 'orig_image_width', $orig_image_width);
	sysModSetVar ('content', 'orig_image_height', $orig_image_height);
	sysModSetVar ('content', 'orig_image_qual', $orig_image_qual);
	sysModSetVar ('content', 'orig_image_changequal', $orig_image_changequal);
	sysModSetVar ('content', 'orig_image_maxsize', $orig_image_maxsize);

  
	list ($image_width, $image_height, $image_qual,$image_method) =
		sysVarCleanFromInput('image_width', 'image_height', 'image_qual', 'image_method');
  
	sysModSetVar ('content', 'image_width', $image_width);
	sysModSetVar ('content', 'image_height', $image_height);
	sysModSetVar ('content', 'image_qual', $image_qual);
	sysModSetVar ('content', 'image_method', $image_method);
	
	list ($thumb_width, $thumb_height, $thumb_qual,$thumb_method) =
		sysVarCleanFromInput('thumb_width', 'thumb_height', 'thumb_qual', 'thumb_method');
  
	sysModSetVar ('content', 'thumb_width', $thumb_width);
	sysModSetVar ('content', 'thumb_height', $thumb_height);
	sysModSetVar ('content', 'thumb_qual', $thumb_qual);
	sysModSetVar ('content', 'thumb_method', $thumb_method);


	list ($logo_width, $logo_height, $logo_qual, $logo_method) =
		sysVarCleanFromInput('logo_width', 'logo_height', 'logo_qual', 'logo_method');
		
	sysModSetVar ('content', 'logo_width', $logo_width);
	sysModSetVar ('content', 'logo_height', $logo_height);
	sysModSetVar ('content', 'logo_qual', $logo_qual);
	sysModSetVar ('content', 'logo_method', $logo_method);


	list ($logo_thumb_width, $logo_thumb_height, $logo_thumb_qual, $logo_thumb_method) =
		sysVarCleanFromInput('logo_thumb_width', 'logo_thumb_height', 'logo_thumb_qual', 'logo_thumb_method');
  
	sysModSetVar ('content', 'logo_thumb_width', $logo_thumb_width);
	sysModSetVar ('content', 'logo_thumb_height', $logo_thumb_height);
	sysModSetVar ('content', 'logo_thumb_qual', $logo_thumb_qual);
	sysModSetVar ('content', 'logo_thumb_method', $logo_thumb_method);


	list ($url_control, $names_control, $order_mail, $upload_avail, $rss_doc_count, $rss_folders_list, $ajax_paging) = 
		sysVarCleanFromInput('url_control', 'names_control', 'order_mail', 'upload_avail', 'rss_doc_count', 'rss_folders_list', 'ajax_paging');
  
	sysModSetVar ('content', 'url_control', $url_control);
	sysModSetVar ('content', 'names_control', $names_control);
	sysModSetVar ('content', 'order_mail', $order_mail);
	sysModSetVar ('content', 'upload_avail', $upload_avail);
	sysModSetVar ('content', 'rss_doc_count', $rss_doc_count);
	sysModSetVar ('content', 'rss_folders_list', serialize($rss_folders_list));
	sysModSetVar ('content', 'ajax_paging', $ajax_paging);
	

  
	//Чистим tpl кеша
	sysModClassLoad ('content');
	$content = new content;
	$content->tplcache_clear();
  
	sysExtLibLoad('imageinslib');
	$sysInsImage = new sysInsImage();
	$sysInsImage->_recDelDir('files/thumbs/content/');

	sys_log('content_admin_config_update',LOG_INFO,'Save config', 'content');

	//Тело модуля
	sysShowMessage(sysModURL ('content','user','main'), 'Изменения сохранены');
	return true;
}


//
//Создание новой папки
//
function content_admin_folder_new()
  {
  //Забираем переменные совхода
  $parent_id = sysVarCleanFromInput('id');
  sysModClassLoad ('content');
  $content = new content;

  if (!is_numeric ($parent_id))
    {
    $parent_id=0;
    $parents_list=array();
    $sysObject = 'content::admin::folder::new';

    //Проверка на доступ
    if (!sysSecAuthAction($sysObject, ACCESS_ADD)) return;
    //Уровень доступа у текущего пользователя
    $curuser_sec_level = sysSecGetLevel ($sysObject);
    }
    else
      {
      //Строим имя обьекта
      $parents_list =& $content->parentsList ($parent_id);
      $sysObject = 'content::admin::folder::new';
      foreach ($parents_list as $parent)
        $sysObject.='::'.$parent['id'];

      //Получили инфу о разделе
      $folder_dbdata =& $content->folder_get_info ($parent_id);
      if (empty ($folder_dbdata)) sysException ('content_admin_folder_new', BAD_PARAM, 'Folder empty');
      //Проверка на доступ
      if (!sysSecAuthAction($sysObject, ACCESS_ADD, $folder_dbdata['uid'])) return;
      //Уровень доступа у текущего пользователя
      $curuser_sec_level = sysSecGetLevel ($sysObject, $folder_dbdata['uid']);
      }

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  sysLoadLang ($sysTpl, 'content');
  $sysTpl->caching = false;

  //Уровень доступа у текущего пользователя
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  //Построили дерево
  $folders_list =& $content->folder_ItemsTreeBuild (0, 0);
  $sysTpl->assign('folders_list', $folders_list);

  //Назночаем ключи по каторым можно производить сортировку subfolder
  $folders_table = sysDBGetTable('content_folders');
  $folders_column =  sysDBGetColumns($folders_table);
  $folder_orderslist = array();
  foreach ($folders_column as $key=>$value)
    $folder_orderslist[$key]=$key;

  unset ($folder_orderslist['subfolder_orderby']);
  unset ($folder_orderslist['subfolder_order_asc']);
  unset ($folder_orderslist['subdoc_orderby']);
  unset ($folder_orderslist['subdoc_order_asc']);
  unset ($folder_orderslist['parent_id']);
  unset ($folder_orderslist['subdoc_perpage_user']);
  unset ($folder_orderslist['subdoc_perpage_admin']);
  unset ($folder_orderslist['limit_doc_perfolder']);
  unset ($folder_orderslist['limit_doc_olderdays']);
  unset ($folder_orderslist['need_admin_active']);
  unset ($folder_orderslist['mailon_docs_change']);
  unset ($folder_orderslist['limit_doc_olderdays']);
  unset ($folder_orderslist['content']);
  $sysTpl->assign('folder_orderslist', $folder_orderslist);

  //Назночаем ключи по каторым можно производить сортировку docfolder
  $docs_table = sysDBGetTable('content_docs');
  $docs_column =  sysDBGetColumns($docs_table);
  $doc_orderslist=array();
  foreach ($docs_column as $key=>$value)
    $doc_orderslist[$key]=$key;

  unset ($doc_orderslist['parent_id']);
  unset ($doc_orderslist['fid']);
  unset ($doc_orderslist['content']);
  $sysTpl->assign('doc_orderslist', $doc_orderslist);

  //Назначаем переменные
  $sysTpl->assign('parent_id', $parent_id);
  //Откуда пришел
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  $sysTpl->assign('http_referer', $http_referer);

  //BrowseIn
  $browsein=$parents_list;
  $browsein[]=array ('url'=>sysModURL('content','admin','folder_new', array ('id'=>$parent_id)),
                     'displayname'=>'Добавление раздела');
  $sysTpl->assign('browsein', $browsein);
  //Вывод результатов
  $result['object'] = $sysObject;
  $result['browsein'] = $browsein;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Создание новой папки
*/
function content_admin_folder_create()
  {
  //Забираем переменные со входа
  $folders_table = sysDBGetTable('content_folders');
  $folders_column =  sysDBGetColumns($folders_table);
  $folder_indata = sysDBCleanFromInput ($folders_column);
  //
  if (!is_numeric ($folder_indata['parent_id']))
    sysException ('content_admin_folder_create', BAD_PARAM, 'parent_id not numeric '.$folder_indata['parent_id']);

//if (((!$folder_indata['name']) or ($folder_indata['name']=="") or ($folder_indata['name']==" ")) and ((!$folder_indata['displayname']) or ($folder_indata['displayname']=="") or ($folder_indata['displayname']==" ")))
//    sysException ('content_admin_folder_create', BAD_PARAM, 'name and displayname are empty ');


  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList ($folder_indata['parent_id']);
  $sysObject = 'content::admin::folder::create';
  foreach ($parents_list as $parent)
    $sysObject.='::'.$parent['id'];

// if ((!$folder_indata['name']) or ($folder_indata['name']=="") or ($folder_indata['name']==" ")) $folder_indata['name']= sysVarTranslit($folder_indata['displayname']);
//else {$folder_indata['name']=sysVarTranslit($folder_indata['name']);}


  //Получили инфу о разделе
  $folder_dbdata =& $content->folder_get_info ($folder_indata['parent_id']);
  if (empty ($folder_dbdata))
    {
    //Проверка на доступ
    if (!sysSecAuthAction($sysObject, ACCESS_ADD)) return;

    //Уровень доступа у текущего пользователя
    $curuser_sec_level = sysSecGetLevel ($sysObject);
    }
    else
      {
      //Проверка на доступ
      if (!sysSecAuthAction($sysObject, ACCESS_ADD, $folder_dbdata['uid'])) return;

      //Уровень доступа у текущего пользователя
      $curuser_sec_level = sysSecGetLevel ($sysObject, $folder_dbdata['uid']);
      }

  //Проверка если уровень=адд и нуна ативировать выставляем active=false
  //Инфа из базы о папке
  if (($folder_dbdata['need_admin_active']) and ($curuser_sec_level<ACCESS_ADMIN))
    $folder_indata['active'] = false;

  //Кто добавил эту папку
  $folder_indata['uid'] = sysSessionGetVar('user_id');
  
	$folder_indata['name']=$content->check_name($folder_indata['name'], $folder_indata['displayname']);

  //Вызов функ. класса для создания
  $id=$content->folder_create($folder_indata);

sys_log('content_admin_folder_create',LOG_INFO,'Creating folder',array('id'=>$id), 'content');

  //Переходим на страницу откуда были вызванны
  $http_referer = sysVarCleanFromInput('http_referer');
  if (!empty ($http_referer))
    sysShowMessage($http_referer, 'Раздел добавлен');
    else
      sysShowMessage(sysModURL ('content','user','main'), 'Раздел добавлен');
  }

/*
 * Редактирование папки
*/
function content_admin_folder_modify()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_folder_modify', BAD_PARAM, 'Id not numeric '.$id);

  $editor = sysVarCleanFromInput('editor');
  if (!$editor) {
    $editor = sysModGetVar('SYS_config', 'texteditor');
    if (!$editor) $editor = 3; // Jodit по умолчанию
  }

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList ($id);
  $sysObject = 'content::admin::folder::modify';
  foreach ($parents_list as $parent)
    $sysObject.='::'.$parent['id'];

  //Получили инфу о разделе
  $folder_dbdata =& $content->folder_get_info ($id);
  if (empty ($folder_dbdata)) sysException ('content_admin_folder_modify', BAD_PARAM, 'Folder empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $folder_dbdata['uid'])) return;

  //Шаблоны
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  sysLoadLang ($sysTpl, 'content');
  $sysTpl->caching = false;

  //Уровень доступа у текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $folder_dbdata['uid']);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  //Построили дерево
  $folders_list =& $content->folder_ItemsTreeBuild (0, 0);
  $sysTpl->assign('folders_list', $folders_list);

  //В шаблон инфу о текущем разделе
  $sysTpl->assign($folder_dbdata);

  $sysTpl->assign('editor',$editor);

  $folders_table = sysDBGetTable('content_folders');
  $folders_column = sysDBGetColumns($folders_table);

  //Назночаем ключи по каторым можно производить сортировку subfolder
  $folder_orderslist = array();
  foreach ($folders_column as $key=>$value)
    $folder_orderslist[$key]=$key;

  unset ($folder_orderslist['subfolder_orderby']);
  unset ($folder_orderslist['subfolder_order_asc']);
  unset ($folder_orderslist['subdoc_orderby']);
  unset ($folder_orderslist['subdoc_order_asc']);
  unset ($folder_orderslist['parent_id']);
  unset ($folder_orderslist['subdoc_perpage_user']);
  unset ($folder_orderslist['subdoc_perpage_admin']);
  unset ($folder_orderslist['limit_doc_perfolder']);
  unset ($folder_orderslist['limit_doc_olderdays']);
  unset ($folder_orderslist['need_admin_active']);
  unset ($folder_orderslist['mailon_docs_change']);
  unset ($folder_orderslist['limit_doc_olderdays']);
  unset ($folder_orderslist['content']);
  $sysTpl->assign('folder_orderslist', $folder_orderslist);

  //Назночаем ключи по каторым можно производить сортировку docfolder
  $docs_table = sysDBGetTable('content_docs');
  $docs_column = sysDBGetColumns($docs_table);
  $doc_orderslist = array();
  foreach ($docs_column as $key=>$value)
    $doc_orderslist[$key]=$key;

  unset ($doc_orderslist['parent_id']);
  unset ($doc_orderslist['fid']);
  unset ($doc_orderslist['content']);
  $sysTpl->assign('doc_orderslist', $doc_orderslist);

  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  $sysTpl->assign('http_referer', $http_referer);

  //BrowseIn
  $browsein = $parents_list;
  $browsein[]=array ('url'=>sysModURL('content','admin','folder_modify', array ('id'=>$id)),
                     'displayname'=>'Редактирование раздела');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] = $sysObject;
  $result['browsein'] = $browsein;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Редактирование папки
*/
function content_admin_folder_update()
  {
  $id=sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_folder_update', BAD_PARAM, 'Id not numeric '.$id);


  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList ($id);
  $sysObject = 'content::admin::folder::update';
  foreach ($parents_list as $parent)
    $sysObject.="::$parent[id]";

  //Получили инфу о разделе
  $folder_dbdata =& $content->folder_get_info ($id);
  if (empty ($folder_dbdata)) sysException ('content_admin_folder_update', BAD_PARAM, 'Folder empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $folder_dbdata['uid'])) return;

  //Забираем переменные со входа
  $folders_table = sysDBGetTable('content_folders');
  $folders_column = sysDBGetColumns($folders_table);
  $folder_indata = sysDBCleanFromInput ($folders_column);

//if (((!$folder_indata['name']) or ($folder_indata['name']=="") or ($folder_indata['name']==" ")) and ((!$folder_indata['displayname']) or ($folder_indata['displayname']=="") or ($folder_indata['displayname']==" ")))
//    sysException ('content_admin_folder_create', BAD_PARAM, 'name and displayname are empty ');

// if ((!$folder_indata['name']) or ($folder_indata['name']=="") or ($folder_indata['name']==" ")) $folder_indata['name']= sysVarTranslit($folder_indata['displayname']);
//else {$folder_indata['name']=sysVarTranslit($folder_indata['name']);}



  //Проверки
  //1 Радителя низя вкладывать в ребенка и ребнка ребенка и т.д.
  //2 Низя вкладывать самого в себя
  if ($folder_dbdata['parent_id']<>$folder_indata['parent_id'])
    {
    //Строим массив перентов директории в каторую нас перемещают
    $tfolder_parents_list =& $content->parentsList ($folder_indata['parent_id']);
    $prezent = false;
    foreach ($tfolder_parents_list as $key=>$tfolder)
      {
      if ($tfolder['id']==$folder_indata['id'])
        {
        $prezent = true;
        break;
        }
      }
    if ($prezent)
      {
      //Переходим на страницу откуда были вызванны
      $http_referer = sysVarCleanFromInput('http_referer');
      if (!empty ($http_referer))
        sysShowMessage($http_referer, 'Невозможно вложить родительскую папку в дочернюю!');
        else
          sysShowMessage(sysModURL ('content','user','main'), 'Невозможно вложить родительскую папку в дочернюю!');
      }
    }

  //Уровень доступа у текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $folder_dbdata['uid']);

  //Проверка, чтоб случайно юзер не активировал скрытый раздел
  //Инфа из базы о папке
  if (($folder_dbdata['need_admin_active']) and
    ($curuser_sec_level<ACCESS_ADMIN) and (!$folder_dbdata['active']))
    {
    $folder_indata['active'] = false;
    }
	
	
	$folder_indata['name']=$content->check_name($folder_indata['name'],$folder_indata['displayname'],$id);
	

  //Собственно обновление информации
  $content->folder_update($folder_indata, $curuser_sec_level);

  //Переходим на страницу откуда были вызванны
  $http_referer = sysVarCleanFromInput('http_referer');

  //Проверка если менялись число документов на странице, то переходим в раздел
  if ($folder_dbdata['subdoc_perpage_admin']<>$folder_indata['subdoc_perpage_admin'])
    unset ($http_referer);

sys_log('content_admin_folder_update',LOG_INFO,'Updating folder',array('id'=>$id), 'content');

  if (!empty ($http_referer))
    sysShowMessage($http_referer, 'Раздел изменен: '.$flag);
    else
      sysShowMessage(sysModURL ('content','user','main'), 'Раздел изменен');
  }

/*
 * Подтверждение удаления папки
*/
function content_admin_folder_confirm_delete()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_folder_confirm_delete', BAD_PARAM, 'Id not numeric '.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList ($id);
  $sysObject = 'content::admin::folder::confirm_delete';
  foreach ($parents_list as $parent)
    $sysObject.='::'.$parent['id'];

  //Получили инфу о разделе
  $folder_dbdata =& $content->folder_get_info ($id);
  if (empty ($folder_dbdata)) sysException ('content_admin_folder_confirm_delete', BAD_PARAM, 'Folder empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_DELETE, $folder_dbdata['uid'])) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  sysLoadLang ($sysTpl, 'content');
  $sysTpl->caching = false;

  //Уровень доступа у текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $folder_dbdata['uid']);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  //В шаблон инфу о текущем разделе
  $sysTpl->assign($folder_dbdata);

  //Куда лесть после удаления
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  $sysTpl->assign('http_referer', $http_referer);

  //BrowseIn
  $browsein = $parents_list;
  $browsein[]=array ('url'=>sysModURL('content','admin','folder_confirm_delete', array ('id'=>$id)),
                     'displayname'=>'Удаление раздела');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] = $sysObject;
  $result['browsein'] = $browsein;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Удаление папки
*/
function content_admin_folder_delete()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_folder_delete', BAD_PARAM, 'Id not numeric '.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList ($id);
  $sysObject = 'content::admin::folder::delete';
  foreach ($parents_list as $parent)
    $sysObject.='::'.$parent['id'];

  //Получили инфу о разделе
  $folder_dbdata =& $content->folder_get_info ($id);
  if (empty ($folder_dbdata)) sysException ('content_admin_folder_delete', BAD_PARAM, 'Folder empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_DELETE, $folder_dbdata['uid'])) return;


  //Вызвали class функцию для удаления
  $content->folder_delete($id);

sys_log('content_admin_folder_delete',LOG_INFO,'Deleting folder',array('id'=>$id), 'content');

  //Куда переходить после удаления
  //Два случая удаление из самого раздела али нет
  $http_referer = sysVarCleanFromInput('http_referer');
  if ((!empty ($http_referer)) and (!strpos ($http_referer,"id=$id")))
    sysShowMessage($http_referer, 'Раздел удален');
    else
      sysShowMessage(sysModURL ('content','user','main'), 'Раздел удален');
  }

/*
 * Диалог копирование раздела
*/
function content_admin_folder_copy_dg()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_folder_copy_dg', BAD_PARAM, 'Id not numeric '.$id);
  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList ($id);
  $sysObject = 'content::admin::folder::copy_dg';
  foreach ($parents_list as $parent)
    $sysObject.='::'.$parent['id'];

  //Получили инфу о разделе
  $folder_dbdata =& $content->folder_get_info ($id);
  if (empty ($folder_dbdata)) sysException ('content_admin_folder_copy_dg', BAD_PARAM, 'Folder empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADD, $folder_dbdata['uid'])) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  sysLoadLang ($sysTpl, 'content');
  $sysTpl->caching = false;

  //Уровень доступа у текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $folder_dbdata['uid']);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  //Построили дерево родителей, чстобы понять в каком корневом разделе мы находимся
  //С этого корневова раздела строим дерево документов
  $root_folder = $parents_list[0];
  if (isset ($root_folder))
    {
    //Информация о корневом разделе
    $root_folder_dbdata = $content->folder_get_info($root_folder['id']);
    //О вложенных документах
    $folders_list = $content->folder_ItemsTreeBuild($root_folder['id'], 1, $root_folder_dbdata['subfolder_orderby']);
    $folders_list = array_merge (array (0=>$root_folder_dbdata), $folders_list);
    };

  $sysTpl->assign('folders_list', $folders_list);

  //Инфа из базы о текущем разделе
  $sysTpl->assign($folder_dbdata);

  //Откуда пришел
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  $sysTpl->assign('http_referer', $http_referer);

  //BrowseIn
  $browsein=$parents_list;
  $sysTpl->assign('browsein', $browsein);
  $browsein[]=array ('url'=>sysModURL('content','user','folder_copy_dg', array ('id'=>$id)),
                     'displayname'=>'Копирование раздела');
  //Вывод результатов
  $result['object'] = $sysObject;
  $result['browsein'] = $browsein;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Копирование раздела
*/
function content_admin_folder_copy()
  {
  list ($id, $mv2folder) = sysVarCleanFromInput('id', 'mv2folder');
  if (!is_numeric ($id) && empty($mv2folder)) sysException ('content_admin_folder_copy', BAD_PARAM, 'Id not numeric '.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList ($id);
  $sysObject = 'content::admin::folder::copy';
  foreach ($parents_list as $parent)
    $sysObject.='::'.$parent['id'];

  //Получили инфу о разделе
  $folder_dbdata =& $content->folder_get_info ($id);
  if (empty ($folder_dbdata)) sysException ('content_admin_folder_copy', BAD_PARAM, 'Folder empty');

  //Проверка на доступ чтение
  if (!sysSecAuthAction($sysObject, ACCESS_ADD, $folder_dbdata['uid'])) return;

  //вызов class функцию копирования
  foreach ($mv2folder as $key=>$dst_fid)
    {
    //Проверка родителя низя копировать в ребенка
    //Строим массив перентов директории вкаторую нас копируют
    $parents_list =& $content->parentsList ($dst_fid);
    foreach ($parents_list as $key=>$tfolder)
      {
      if ($tfolder['id']==$src_fid)
        {
        //Переходим на страницу откуда были вызванны
        $http_referer=sysVarCleanFromInput('http_referer');
        sysShowMessage($http_referer, 'Невозможно скопировать родительскую папку в дочернюю!');
        }
      }

    $content->folder_copy($id, $dst_fid);
    };

sys_log('content_admin_folder_copy',LOG_INFO,'Copying folder',array('id'=>$id), 'content');

  //Переходим на страницу откуда были вызванны
  $http_referer = sysVarCleanFromInput('http_referer');

  if (!empty ($http_referer))
    sysShowMessage($http_referer, 'Копирование успешно произведено');
    else
      sysShowMessage(sysModURL ('content','user','main'), 'Копирование успешно произведено');
  }



/*
 * Увеличение вес папки
*/
function content_admin_folder_moveup ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_folder_moveup', BAD_PARAM, 'Id not numeric '.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList ($id);
  $sysObject = 'content::admin::folder::moveup';
  foreach ($parents_list as $parent)
    $sysObject.='::'.$parent['id'];

  //Получили инфу о разделе
  $folder_dbdata =& $content->folder_get_info ($id);
  if (empty ($folder_dbdata)) sysException ('content_admin_folder_moveup', BAD_PARAM, 'Folder empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $folder_dbdata['uid'])) return;

  //Подвинули вверх
  sysExtLibLoad();
  $folders_table = sysDBGetTable('content_folders');
  $folders_column = sysDBGetColumns($folders_table);
  sysDbWeightMoveUp ($folders_table, $folder_dbdata['weight'], "WHERE $folders_column[parent_id] = '$folder_dbdata[parent_id]'");

  //Возвращаемся на вызывающую страницу
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];

  //Чистим кеша
  $content->clear_cache ($parents_list);

  sysRedirect($http_referer);
  }

/*
 * Уменьшыли веса папки
*/
function content_admin_folder_movedown ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_folder_movedown', BAD_PARAM, 'Id not numeric '.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList ($id);
  $sysObject = 'content::admin::folder::movedown';
  foreach ($parents_list as $parent)
    $sysObject.='::'.$parent['id'];

  //Получили инфу о разделе
  $folder_dbdata =& $content->folder_get_info ($id);
  if (empty ($folder_dbdata)) sysException ('content_admin_folder_movedown', BAD_PARAM, 'Folder empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $folder_dbdata['uid'])) return;

  //Подвинули вниз
  sysExtLibLoad();
  $folders_table = sysDBGetTable('content_folders');
  $folders_column =  sysDBGetColumns($folders_table);
  sysDbWeightMoveDown ($folders_table, $folder_dbdata['weight'], "WHERE $folders_column[parent_id] = '$folder_dbdata[parent_id]'");

  //Возвращаемся на вызывающую страницу
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];

  //Чистим кеша
  $content->clear_cache ($parents_list);

  sysRedirect($http_referer);
  }

//
// Создание нового документа
//
function content_admin_doc_new()
  {


  sysModClassLoad ('content');
  $content = new content;

  $fid = sysVarCleanFromInput('fid');

  //Строим имя обьекта
  $parents_list =& $content->parentsList ($fid);
  $sysObject = 'content::admin::doc::new';
  foreach ($parents_list as $parent)
    $sysObject.='::'.$parent['id'];

  //Проверка на доступ
  if (is_numeric ($fid))
    {
    //Получили инфу о разделе
    $folder_dbdata =& $content->folder_get_info ($fid);
    if (empty ($folder_dbdata)) sysException ('content_admin_doc_new', BAD_PARAM, 'Folder empty');
    if (!sysSecAuthAction($sysObject, ACCESS_ADD, $folder_dbdata['uid'])) return;
    }
    else
      if (!sysSecAuthAction($sysObject, ACCESS_ADD)) return;

  $sysTpl = new sysTpl;
  // Передаём в шаблон id кода подтверждения
  if  ($enable_confirm == 1) {
    $sysTpl->assign('enable_confirm',"1");
    $sysTpl->assign('sec',$sec_code);
  }
  else {
    $sysTpl->assign('enable_confirm',"0");
  }

  $sysModTpl=sysTplWay ($sysObject);
  sysLoadLang ($sysTpl, 'content');
  $sysTpl->caching = false;

  //Уровень доступа у текущего пользователя
  if (is_numeric ($fid))
    $curuser_sec_level = sysSecGetLevel ($sysObject, $folder_dbdata['uid']);
    else
      $curuser_sec_level = sysSecGetLevel ($sysObject);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  //В шаблон инфу о текущем разделе
  $sysTpl->assign('folder_dbdata', $folder_dbdata);

  //Построили дерево родителей, чстобы понять в каком корневом разделе мы находимся
  //С этого корневова раздела строим дерево документов
  $root_folder = $parents_list[0];
  if (isset ($root_folder))
    {
    //Информация о корневом разделе
    $folder_dbdata =& $content->folder_get_info ($root_folder['id']);
//    $folders_list = $content->folder_ItemsTreeBuild ($root_folder['id'], 1, $folder_dbdata['subfolder_orderby']);
  $folders_list =& $content->folder_ItemsTreeBuild (0, 0);
  //  $folders_list = array_merge (array (0=>$folder_dbdata), $folders_list);
    }

  $sysTpl->assign('folders_list', $folders_list);

  //Если переданно id значит добавляем под папку
  if (is_numeric ($fid))
    $sysTpl->assign('fid', $fid);

  //Текущая дата
  $sysTpl->assign('pub_datetime', time());

  //Назночаем ключи по каторым можно производить сортировку dдокументов в блоке
  $docs_table = sysDBGetTable('content_docs');
  $docs_column =  sysDBGetColumns($docs_table);
  $doc_orderslist=array();
  foreach ($docs_column as $key=>$value)
    $doc_orderslist[$key]=$key;

  unset ($doc_orderslist['parent_id']);
  unset ($doc_orderslist['fid']);
  unset ($doc_orderslist['content']);
  $sysTpl->assign('doc_orderslist', $doc_orderslist);

  //BrowseIn
  $browsein = $parents_list;
  $browsein[]=array ('url'=>sysModURL('content','user','doc_new', array ('fid'=>$fid)),
                     'displayname'=>'Добавить');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] = $sysObject;
  $result['browsein'] = $browsein;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

//
// Создание нового документа
//
function content_admin_doc_create()
{

	//Забираем переменные со входа
	$docs_table = sysDBGetTable('content_docs');
	$docs_column =  sysDBGetColumns($docs_table);
 
	//Параметры документа со входа
	$doc_indata = sysDBCleanFromInput ($docs_column);

	sysModClassLoad ('content');
	$content = new content;
	
	//Строим имя обьекта
	$doc_indata['fid'] = sysVarCleanFromInput('fid');
	$parents_list =& $content->parentsList ($doc_indata['fid']);
	$sysObject = 'content::admin::doc::create';
	foreach ($parents_list as $parent)
		$sysObject.='::'.$parent['id'];

	//Принадлежность документа к разделам
	$doc_indata['fids'] = sysVarCleanFromInput('fids');
	if (!$doc_indata['fids']) 
		$doc_indata['fids'][] = $doc_indata['fid'];
	
	//Получили инфу о разделе (куда добавляли изначально)
	$folder_dbdata =& $content->folder_get_info ($doc_indata['fid']);
	if (empty ($folder_dbdata)) 
		sysException ('content_admin_folder_modify', BAD_PARAM, 'Folder empty');

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_ADD, $folder_dbdata['uid'])) return;

	//Права доступа
	$curuser_sec_level = sysSecGetLevel ($sysObject);
  
	// Грузим языковые константы для сообщения
	$sysTpl = new sysTpl;
	$sysModTpl=sysTplWay ($sysObject);
	sysLoadLang ($sysTpl, 'content');
	$sysTpl->caching = false;

  	//Проверка капчи, если надо
	if ($folder_dbdata['need_captcha']>0 && $curuser_sec_level<70)	{
		$content_captcha_string = sysSessionGetVar('content_captcha_string');
		if ($content_captcha_string!=$doc_indata['captcha_string']) 	{
			$obj = 'content::admin::doc::new';
			foreach ($parents_list as $parent)
				$obj.='::'.$parent['id']; 
			$error_html = $content->showAddError ($sysTpl->get_config_vars('captcha_fail'), $doc_indata, $folder_dbdata, $obj);

			//BrowseIn
			$browsein=$parents_list;
			$browsein[]=array ('url'=>sysModURL('content','admin','doc_new'),
							   'displayname'=>'Добавление');

			$result['browsein'] = $browsein;
			$result['object'] = $sysObject;
			$result['content'] = $error_html;
			return $result;
		}
	}

	//Дата и время добавления
  
	$pub_date = sysVarCleanFromInput('pub_date');
	$pub_time = sysVarCleanFromInput('pub_time');
	if (!empty ($pub_date)) {
		$date = $pub_date;
			if ($pub_time)
				$date .= " ".$pub_time;
			else
				$date .= " 00:00";
	}
    else
		$date = date("Y-m-d G:i");
		
	$doc_indata['pub_datetime'] = $date;


	//Добавляем информацию с какова ip был добавлен и кем
	$doc_indata['from_ip'] = sysUserIpAddr();
	$doc_indata['uid'] = sysSessionGetVar('user_id');

	//Проверка если уровень=адд и нуна ативировать выставляем active=false
	if (($folder_dbdata['need_admin_active']) and ($curuser_sec_level<ACCESS_ADMIN))
		$doc_indata['active'] = false;

	//Вызвали функцию для вставки документа
	$id = $content->doc_create($doc_indata);
	$name = $content->check_name($doc_indata['name'], $doc_indata['displayname'], $id, "doc");
	if ($name != $doc_indata['name']) {
		$doc_indata_upd = array("id" => $id, "name" => $name);
		$docs_table = sysDBGetTable('content_docs');
		sysDbUpdate($docs_table, $doc_indata_upd, "WHERE id=$id");
	}
	
	//приложенный файл (если есть) просто грузим в соответствующую папку
	$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/files/shared/docs/';
	$orderfile = $_FILES['orderfile'];
	$ext = pathinfo($orderfile['name']);

	//разрешен ли такой файл
	$allow_ext = explode(' ',sysModGetVar('content', 'upload_avail'));
	if (is_uploaded_file($orderfile['tmp_name']) &&  (in_array($ext['extension'], $allow_ext) || count($allow_ext) == 0)) 	{
		$fname = "file".$id.'.'.substr($orderfile['name'], strrpos($orderfile['name'],'.')+1);
		if (move_uploaded_file($orderfile['tmp_name'], $uploaddir.$fname)) {
			$docs_table = sysDBGetTable('content_docs');
			chmod ($uploaddir.$fname, 0666);
			$doc_indata_u['id'] = $id;
			$doc_indata_u['url'] = sysGetBaseURL()."files/shared/docs/".$fname;
			sysDbUpdate($docs_table, $doc_indata_u, "WHERE $doc_indata_u[id] = $docs_table.id");
			
		}
	}
  
  	//Логируем запись 
	sys_log('content_admin_doc_create',LOG_INFO,'Creating doc',array('id'=>$id), 'content');

	//Если надо - отправляем уведомление администратору (для уровня доступа ниже админского)  && $curuser_sec_level<70
	if ($folder_dbdata['mailon_docs_change']>0  && $curuser_sec_level < 70)	{
		sysExtLibLoad();

		//Отправляем почту на ящик админа
		$sysMailTplWay = sysTplWay ('content::mail::new_order_webcore::'.$doc_indata['fid']);
		$sysMailTpl = new sysTpl;
		$sysMailTpl->caching = false;
      
		//Кладём в письмо инфу
		$sysMailTpl->assign ($doc_indata);
		if ($doc_indata_u['url']) $sysMailTpl->assign ('url',$doc_indata_u['url']);
		$user_data=sysUserGetVars();
		$sysMailTpl->assign ('user_data',$user_data);
		$sysMailTpl->assign ('folder_displayname',$folder_dbdata['displayname']);
		$mailbody = $sysMailTpl->fetch($sysMailTplWay, 'content::mail::new_order_webcore');
		$subject = 'Добавлен новый документ на сайте '.sysModGetVar('SYS_config','site_name');
   
		$order_mail=array();
		$content_mail=sysModGetVar('content','order_mail');
		if ($content_mail && $content_mail!=' ')	
			$order_mail=explode(" ",$content_mail);
		if (count($order_mail)==0)
			$order_mail[]=sysModGetVar('SYS_config','adminmail');

		sysMail($order_mail, $subject, $mailbody, true); 
		
	}
	if ($folder_dbdata['add_doc_message'])
		$add_doc_success_message=$folder_dbdata['add_doc_message'];
	else
		$add_doc_success_message=$sysTpl->get_config_vars('add_doc_success_message');
	
	$add_doc_error_message=$sysTpl->get_config_vars('add_doc_error_message');
	

	//Завершаем работу редиректом
	if ($id) {
		sysShowMessage("/$doc_indata[fid]", $add_doc_success_message);
	}
	else  {
		sysShowMessage("/$doc_indata[fid]", $add_doc_error_message);
	}

}


//
// Редактирование документа
//
function content_admin_doc_modify()
{
	$id = sysVarCleanFromInput('id');
	if (!is_numeric ($id)) 
		sysException ('content_admin_doc_modify', BAD_PARAM, 'Id not numeric '.$id);

	$editor = sysVarCleanFromInput('editor');
	if (!$editor) {
		$editor = sysModGetVar('SYS_config', 'texteditor');
		if (!$editor) $editor = 3; // Jodit по умолчанию
	}

	//Строим имя обьекта
	sysModClassLoad ('content');
	$content = new content;
	$parents_list = $content->parentsList2 ($id,$_GET["fid"]);

	$sysObject = 'content::admin::doc::modify';
	foreach ($parents_list as $parent)    {
		if ($parent['type']=='doc') {
			$sysObject.='::doc';
		}
		$sysObject.='::'.$parent['id'];
    }

	//Инфа из базы о документе
	$doc_dbdata = $content->doc_get_info($id,0);
	if (empty ($doc_dbdata)) 
		sysException ('content_admin_doc_modify', BAD_PARAM, 'Doc empty');

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $doc_dbdata['uid'])) return;

	// Шаблоны
	$sysTpl = new sysTpl;
	$sysModTpl=sysTplWay ($sysObject);
	sysLoadLang ($sysTpl, 'content');
	$sysTpl->caching = false;

	//Права текущего пользователя
	$curuser_sec_level = sysSecGetLevel ($sysObject, $doc_dbdata['uid']);
	$sysTpl->assign('curuser_sec_level', $curuser_sec_level);


	//Построили дерево родителей, чстобы понять в каком корневом разделе мы находимся
	//С этого корневова раздела строим дерево документов

	$root_folder = $parents_list[0];
	if (isset ($root_folder))   {
		//Информация о корневом разделе
		$folder_dbdata = $content->folder_get_info ($root_folder['id']);
		//О вложенных документах
		$folders_list = $content->folder_ItemsTreeBuild($root_folder['id'], 1);
		$folders_list = array_merge (array (0=>$folder_dbdata), $folders_list);
    }
	
	$sysTpl->assign('folders_list', $folders_list);
	$sysTpl->assign('fids', $content->doc_get_parents($id));
  
	// Информацию о документе в шаблон

	//Формируем дерево вложенных документов
	if (($doc_dbdata['doc_subdoc_counter'] > 0) or ($curuser_sec_level >= ACCESS_ADMIN))    {
		$subdocs_list = $content->doc_ItemsTreeBuild($id, 0);
		$subdocs_list = array_slice ($subdocs_list, 1); //Эт чтоб самого себя не выводить
		foreach ($subdocs_list as $key=>$value) {
			$value['level'] = $value['level']-1;
			$subdocs_list[$key] = $value;
		}
		$sysTpl->assign('subdocs_list', $subdocs_list);
    }

	// Список фотографий
	$images_list = $content->image_get_list($id);

	//Формируем урлы на thumbs или создаем
	if (!empty($images_list))    {
		$image_x = sysModGetVar('content','image_width');
		$image_y = sysModGetVar('content','image_height');
		$image_jpegqual = sysModGetVar('content','image_qual');
		$image_method = sysModGetVar('content','image_method');

		$dest_x_big =sysModGetVar('content','orig_image_width');
		$dest_y_big =sysModGetVar('content','orig_image_height');

		sysExtLibLoad('imageinslib');
		$sysInsImage = new sysInsImage();

		foreach ($images_list as $key => $image)   {
			$image_data = $sysInsImage->get_thumb_info('content', 'doc', $image['doc_id'], $image['id'], $image_x, $image_y, $image_jpegqual, $image_method);
			$images_list[$key] = $images_list[$key] + $image_data;

			$image_big_data = $sysInsImage->get_thumb_info('content', 'doc', $image['doc_id'], $image['id'], $dest_x_big, $dest_y_big, 100, 'xy');
			$images_list[$key]['big']= $image_big_data; 
		}
		//Информацию в шаблон
		$sysTpl->assign('images_list', $images_list);
    }
	
	//Назначаем ключи по каторым можно производить сортировку документов в блоке
	$docs_table = sysDBGetTable('content_docs');
	$docs_column =  sysDBGetColumns($docs_table);
	$doc_orderslist = array();
	foreach ($docs_column as $key=>$value)
		$doc_orderslist[$key]=$key;
	unset ($doc_orderslist['parent_id']);
	unset ($doc_orderslist['fid']);
	unset ($doc_orderslist['content']);
	$sysTpl->assign('doc_orderslist', $doc_orderslist);


	//Откуда пришел
	$http_referer = $_SERVER['HTTP_REFERER'];
	$sysTpl->assign('http_referer', $http_referer);

	//BrowseIn
	$browsein=$parents_list;
	$browsein[]=array ('url'=>sysModURL('content','user','doc_modify', array ('id'=>$id)),
                     'displayname'=>'Редактирование');
	$sysTpl->assign('browsein', $browsein);

	// Переопределяем некоторые переменные документа
	$link = $content->get_link($id,$_GET["fid"]);
	//$active = $link["active"];
	$doc_dbdata["active"] = $link["active"];
	$sysTpl->assign($doc_dbdata);
	$sysTpl->assign('editor', $editor);
	$sysTpl->assign('fid',$_GET['fid']);

	//Вывод результатов
	$result['object'] = $sysObject;
	$result['browsein'] = $browsein;
	$result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

//
// Редактирование документа
//
function content_admin_doc_update()
{
	$id = sysVarCleanFromInput('id');
	$fid = sysVarCleanFromInput('fid');
	if (!is_numeric ($id)) sysException ('content_admin_doc_modify', BAD_PARAM, 'Id not numeric '.$id);

	//Строим имя обьекта
	sysModClassLoad ('content');
	$content = new content;
	$parents_list =& $content->parentsList (null, $id);
	$sysObject = 'content::admin::doc::update';
	foreach ($parents_list as $parent)   {
		if ($parent['type'] == 'doc'){
			$sysObject.='::doc';
		}
		$sysObject .= '::'.$parent['id'];
    }

	//Инфа из базы о документе
	$doc_dbdata = $content->doc_get_info($id);
	if (empty ($doc_dbdata)) 
		sysException ('content_admin_doc_update', BAD_PARAM, 'Doc empty');
		
	$folder_dbdata = $content->folder_get_info($fid);

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $doc_dbdata['uid'])) return;

	//Забираем переменные со входа
	$docs_table = sysDBGetTable('content_docs');
	$docs_column =  sysDBGetColumns($docs_table);
	$doc_indata = sysDBCleanFromInput ($docs_column);

	//Подготовили дату к хранению в бд.

	$pub_date = sysVarCleanFromInput('pub_date');
	$pub_time = sysVarCleanFromInput('pub_time');
	if (!empty ($pub_date)) {
		$date = $pub_date;
			if ($pub_time)
				$date .= " ".$pub_time;
			else
				$date .= " 00:00";
	}
    else
		$date = date("Y-m-d G:i");
	$doc_indata['pub_datetime'] = $date;

	// Уровень доступа к документу
	$curuser_sec_level = sysSecGetLevel ($sysObject, $doc_dbdata['uid']);

	//Проверка, чтоб случайно юзер не активировал скрытый документ
	if (($folder_dbdata['need_admin_active']) and  ($curuser_sec_level < ACCESS_ADMIN) and (!$doc_dbdata['active']))   {
		$doc_indata['active'] = false;
    };

	$doc_indata['name'] = $content->check_name($doc_indata['name'], $doc_indata['displayname'], $id, "doc");

	//Обновляем документ
	$content->doc_update ($doc_indata);

	sys_log('content_admin_doc_update',LOG_INFO,'Updating doc',array('id'=>$id), 'content');
	
	//отправляем письмо автору, если нужно (для faq)
	if ($doc_indata['reply_to_author'] && $doc_indata['author_email'])	{
		sysExtLibLoad();

		//Отправляем почту на ящик админа
		$sysMailTplWay = sysTplWay ('content::mail::reply_to_author');
		$sysMailTpl = new sysTpl;
		$sysMailTpl->caching = false;
      
		//Кладём в письмо инфу
		$sysMailTpl->assign ($doc_indata);
		$mailbody = $sysMailTpl->fetch($sysMailTplWay, 'content::mail::reply_to_author');
		$subject = 'Ответ на заданный вопрос на сайте '.sysModGetVar('SYS_config','site_name');
   

		sysMail($doc_indata['author_email'], $subject, $mailbody, true,true);
		
	}

	// Грузим языковые константы для сообщения
	$sysTpl = new sysTpl;
	sysLoadLang ($sysTpl, 'content');

	//Переходим на страницу откуда были вызванны
	$http_referer = sysVarCleanFromInput('http_referer');
	
	//переходим на страницу дока
	$folder_name = $folder_dbdata['name'] ? $folder_dbdata['name'] : $folder_dbdata['id'];
	sysShowMessage("/$folder_name/$doc_indata[name].html", $sysTpl->get_config_vars('edited'));
	
	if (!empty ($http_referer))
		sysShowMessage($http_referer, $sysTpl->get_config_vars('edited'));
    else
		sysShowMessage(sysModURL ('content','user','folder_view',array('id'=>$doc_indata['fid'])), $sysTpl->get_config_vars('edited'));
}

//
// Подтверждение удаления документа
//
function content_admin_doc_confirm_delete()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_doc_confirm_delete', BAD_PARAM, 'Id not numeric '.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
   $parents_list_browsein =& $content->parentsList2($id,$_GET["fid"]);
  $parents_list =& $content->parentsList (null, $id);
  $sysObject = 'content::admin::doc::confirm_delete';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    };

  //Инфа из базы о документе
  $doc_dbdata = $content->doc_get_info($id);
  if (empty ($doc_dbdata)) sysException ('content_admin_doc_confirm_delete', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_DELETE, $doc_dbdata['uid'])) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  sysLoadLang ($sysTpl, 'content');
  $sysTpl->caching = false;

  //Права текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $doc_dbdata['uid']);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  //Инфа в шаблон
  $sysTpl->assign($doc_dbdata);

  // Добавим в шаблон fid, очень важно
  $sysTpl->assign("fid",$_GET["fid"]); 
  //BrowseIn
  $browsein=$parents_list_browsein;
  $browsein[]=array ('url'=>sysModURL('content','user','doc_confirm_delete', array ('id'=>$id)),
                     'displayname'=>'Удаление');
  $sysTpl->assign('browsein', $browsein);
  //Вывод результатов
  $result['object'] = $sysObject;
  $result['browsein'] = $browsein;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

//
// Удаление документа
//
function content_admin_doc_delete()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_doc_delete', BAD_PARAM, 'Id not numeric '.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList (null, $id);
  $sysObject = 'content::admin::doc::delete';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    };

  //Инфа из базы о документе
  $doc_dbdata = $content->doc_get_info($id);
  if (empty ($doc_dbdata)) sysException ('content_admin_doc_delete', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_DELETE, $doc_dbdata['uid'])) return;

  // Вызвали функцию класса для удаления и получили место куда перенаправляться
  $fid = $content->doc_delete($id);
  
sys_log('content_admin_doc_create',LOG_INFO,'Deleting doc',array('id'=>$id), 'content');

  // Грузим языковые константы для сообщения
  $sysTpl = new sysTpl;
  sysLoadLang ($sysTpl, 'content');

 // sysShowMessage(sysModURL ('content','user','folder_view',             array ('id'=>$fid)), $sysTpl->get_config_vars('deleted'));
sysShowMessage(sysModURL ('content','user','folder_view',
                 array ('id'=>$fid)), 'Документ удален');
  }

//
// Диалог управления ссылками на документ, диалог
//
function content_admin_doc_links_dg() {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_doc_links_dg', BAD_PARAM, 'Id not numeric '.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $folders = $content->get_documents_folders($id);
  
  $parents_list_browsein =& $content->parentsList2($id,$_GET["fid"]);
  //$parents_list =& $content->parentsList (null, $id);
  $parents_list =& $content->parentsList2($id,$_GET["fid"]);
  $sysObject = 'content::admin::doc::links_dg';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    };

  //Инфа из базы о документе
  $doc_dbdata = $content->doc_get_info($id);
  if (empty ($doc_dbdata)) sysException ('content_admin_doc_links_dg', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADD, $doc_dbdata['uid'])) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  sysLoadLang ($sysTpl, 'content');
  $sysTpl->caching = false;

  //Построили дерево родителей, чстобы понять в каком корневом разделе мы находимся
  //С этого корневова раздела строим дерево документов
  $root_folder = $parents_list[0];
  if (isset ($root_folder)) {
    //Информация о корневом разделе
    $folder_dbdata = $content->folder_get_info ($root_folder['id']);
    //О вложенных документах
  //  $folders_list = $content->folder_ItemsTreeBuild($root_folder['id'], 1);
   //$folders_list = array_merge (array (0=>$folder_dbdata), $folders_list);
  $folders_list =& $content->folder_ItemsTreeBuild (0, 0);
//	if ($folder_dbdata){
//       $folders_list = array_merge (array (0=>$folder_dbdata), $folders_list);
//    }
  };

  $sysTpl->assign('folders_list', $folders_list);
  $sysTpl->assign('folders', $folders);
  $sysTpl->assign('folder_id',$_GET["fid"]);
  $sysTpl->assign('id',$id);
 $sysTpl->assign('open_brunch',$parents_list);
 
  //Инфу в шаблон
  $sysTpl->assign($doc_dbdata);

  //Права текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $doc_dbdata['uid']);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  //Откуда пришел
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  $sysTpl->assign('http_referer', $http_referer);

  //BrowseIn
  $browsein = $parents_list_browsein;
  $browsein[]=array ('url'=>sysModURL('content','user','doc_links_dg', array ('id'=>$id)),
                     'displayname'=>'Управление ссылками');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] = $sysObject;

  $result['browsein'] = $browsein;
  //print $sysModTpl;
  //exit();
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);

  return $result;
}

//
// Физическая работа с ссылками на документ.
//
function content_admin_doc_links()
  {
  //list ($id, $mv2folder) = sysVarCleanFromInput('id', 'mv2folder');
  list ($id, $fids) = sysVarCleanFromInput('id', 'fid');
  $vars['id']=$id;
  $vars['fids']=$fids;
  $vars['folder_id']=$_POST["folder_id"];
  //sys_log('content_admin_doc_links', LOG_DEBUG, "", $vars);
  if (!is_numeric ($id) && empty($mv2folder)) sysException ('content_admin_doc_copy', BAD_PARAM, 'Id not numeric '.$id);
  
  // Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList (null, $id);
  $sysObject = 'content::admin::doc::links';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    };


  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADD, $doc_dbdata['uid'])) return;

  // Вот тут производим махинации над ссылками
  // Передаём id документа, ссылки и id каталога, в котором храниться документ.
  $content->doc_links($id,$fids,$_POST["folder_id"]);

sys_log('content_admin_doc_links',LOG_INFO,'Linking doc',array('id'=>$id), 'content');

  //Переходим на страницу откуда были вызванны
  $http_referer = sysVarCleanFromInput('http_referer');

  if (!empty ($http_referer))
    sysShowMessage($http_referer, 'Изменение ссылок успешно произведено');
    else
      sysShowMessage(sysModURL ('content','user','main'), 'Изменение ссылок успешно произведено');
  }

//
// Диалог копирования документа
//
function content_admin_doc_copy_dg()
  {
  
  list($id,$fid) = sysVarCleanFromInput('id','fid');
  if (!is_numeric ($id)) sysException ('content_admin_doc_copy_dg', BAD_PARAM, 'Id not numeric '.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list_browsein =& $content->parentsList2($id,$fid);
  $parents_list =& $content->parentsList (null, $id);
  //$parents_list = & $content->parentsList2($id,$fid);

 
  $sysObject = 'content::admin::doc::copy_dg';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    };

  //Инфа из базы о документе
  $doc_dbdata = $content->doc_get_info($id);
  if (empty ($doc_dbdata)) sysException ('content_admin_doc_copy_dg', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADD, $doc_dbdata['uid'])) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  sysLoadLang ($sysTpl, 'content');
  $sysTpl->caching = false;

  //Построили дерево родителей, чстобы понять в каком корневом разделе мы находимся
  //С этого корневова раздела строим дерево документов
  $root_folder = $parents_list[0];
  if (isset ($root_folder)) {
    //Информация о корневом разделе
    $folder_dbdata = $content->folder_get_info ($root_folder['id']);
    //О вложенных документах
    $folders_list = $content->folder_ItemsTreeBuild($root_folder['id'], 1);
    $folders_list = array_merge (array (0=>$folder_dbdata), $folders_list);
  };

  $sysTpl->assign('folders_list', $folders_list);

  //Инфу в шаблон
  $sysTpl->assign($doc_dbdata);

  //Права текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $doc_dbdata['uid']);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  //Откуда пришел
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  $sysTpl->assign('http_referer', $http_referer);
  $sysTpl->assign('fid', $fid);

  //BrowseIn
  $browsein = $parents_list_browsein;
  $browsein[]=array ('url'=>sysModURL('content','user','doc_copy_dg', array ('id'=>$id)),
                     'displayname'=>'Копирование');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] = $sysObject;
  $result['browsein'] = $browsein;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

//
// Физическое копирование документа.
//
function content_admin_doc_copy()
  {
  list ($id, $mv2folder) = sysVarCleanFromInput('id', 'mv2folder');
  if (!is_numeric ($id) && empty($mv2folder)) sysException ('content_admin_doc_copy', BAD_PARAM, 'Id not numeric '.$id);

  // Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList (null, $id);
  $sysObject = 'content::admin::doc::copy';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    };

  // Инфа из базы о документе
  $doc_dbdata = $content->doc_get_info($id);
  if (empty ($doc_dbdata)) sysException ('content_admin_doc_copy', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADD, $doc_dbdata['uid'])) return;

  //вызов функции classa копирования
  foreach ($mv2folder as $key=>$dst_fid)
    $content->doc_copy ($id, $dst_fid);


sys_log('content_admin_doc_copy',LOG_INFO,'Copy doc',array('id'=>$id), 'content');

  //Переходим на страницу откуда были вызванны
  $http_referer = sysVarCleanFromInput('http_referer');

  if (!empty ($http_referer))
    sysShowMessage($http_referer, 'Копирование успешно произведено');
    else
      sysShowMessage(sysModURL ('content','user','main'), 'Копирование успешно произведено');
  }


/*
 * Увеличение вес документа
*/
function content_admin_doc_moveup ()
  {
  $id = sysVarCleanFromInput('id');
  $fid = sysVarCleanFromInput('fid');
  if (!is_numeric ($id)) sysException ('content_admin_doc_moveup', BAD_PARAM, 'Id not numeric '.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;

  $sysObject = 'content::admin::doc::moveup'.$fid.'::'.$id;

  //Инфа из базы о документе

$doc_dbdata = $content->get_link($id,$fid);
  if (empty ($doc_dbdata)) sysException ('content_admin_doc_moveup', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
 if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $doc_dbdata['uid'])) return;

  //Подвинули вверх
  sysExtLibLoad();
 $links_table = sysDBGetTable('content_links');
  $links_column =  sysDBGetColumns($links_table);
  sysDbWeightMoveUp ($links_table, $doc_dbdata['weight'], "WHERE $links_column[parent_id] = '$fid'");

  //Чистим кеша
  $content->clear_cache ($parents_list);

  //Возвращаемся на вызывающую страницу
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  sysRedirect($http_referer);
  }

/*
 * Уменьшаем вес документа
*/
function content_admin_doc_movedown ()
  {
  $id = sysVarCleanFromInput('id');
  $fid = sysVarCleanFromInput('fid');
  if (!is_numeric ($id)) sysException ('content_admin_doc_movedown', BAD_PARAM, 'Id not numeric '.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
 // $parents_list =& $content->parentsList (null, $id);
  $sysObject = 'content::admin::doc::movedown::'.$fid.'::'.$id;
/*  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    };
*/
  //Инфа из базы о документе
//  $doc_dbdata = $content->doc_get_info($id);
$doc_dbdata = $content->get_link($id,$fid);
//  if (empty ($doc_dbdata)) sysException ('content_admin_doc_movedown', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
//  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $doc_dbdata['uid'])) return;

  //Подвинули вверх
  $links_table = sysDBGetTable('content_links');
  $links_column =  sysDBGetColumns($links_table);
  sysExtLibLoad();
  sysDbWeightMoveDown ($links_table, $doc_dbdata['weight'], "WHERE $links_column[parent_id] = '$fid'");

  //Чистим кеша
 // $content->clear_cache ($parents_list);

  //Возвращаемся на вызывающую страницу
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  sysRedirect($http_referer);
  }



/*
 * Создание нового документа вложенного в документ
*/
function content_admin_subdoc_new()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_subdoc_new', BAD_PARAM, 'Id not numeric '.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList (null, null, $id);
  $sysObject = 'content::admin::subdoc::new';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    //Попутно находим Id корневого документа
    if ($parent['type']=='doc')
      $rootdoc_id = $parent['id'];
    };

  //Инфа из базы о документе
  $doc_dbdata = $content->doc_get_info($id);
  if (empty ($doc_dbdata)) sysException ('content_admin_subdoc_new', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADD, $doc_dbdata['uid'])) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  sysLoadLang ($sysTpl, 'content');
  $sysTpl->caching = false;

  //Права текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $doc_dbdata['uid']);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  //Инфа из базы о корневом документе
  $rootdoc_info = $content->doc_get_info ($rootdoc_id);
  $sysTpl->assign('rootdoc_info', $rootdoc_info);

  //Текущая дата
  $sysTpl->assign('pub_datetime', time());

  //Id документа предка
  $sysTpl->assign('parent_id', $id);

  //Дерево страниц
  $subdocs_list = $content->doc_ItemsTreeBuild ($rootdoc_id, 0);
  $sysTpl->assign('subdocs_list', $subdocs_list);

  //Откуда пришел
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  $sysTpl->assign('http_referer', $http_referer);

  //BrowseIn
  $browsein = $parentslist;
  $browsein=$parents_list;
  $browsein[]=array ('url'=>sysModURL('content','admin','subdoc_new', array ('id'=>$id)),
                   'displayname'=>"Добавление страницы в документ");
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] = $sysObject;
  $result['browsein'] = $browsein;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Создание нового документа
*/
function content_admin_subdoc_create()
  {
  //Забираем переменные со входа
  $docs_table = sysDBGetTable('content_docs');
  $docs_column =  sysDBGetColumns($docs_table);
  $doc_indata=sysDBCleanFromInput ($docs_column);
  if (!is_numeric ($doc_indata['parent_id'])) sysException ('content_admin_subdoc_new', BAD_PARAM, 'Id not numeric ');

  sysModClassLoad ('content');
  $content = new content;

  //Получили iнфу о родителе
  $parent_dbdata = $content->doc_get_info ($doc_indata['parent_id']);
  if (empty ($parent_dbdata)) sysException ('content_admin_subdoc_create', BAD_PARAM, 'Doc empty');
  //Это в какой папке находиться
  $doc_indata['fid'] = $parent_dbdata['fid'];

  //Строим имя обьекта
  $parents_list =& $content->parentsList (null, null, $doc_indata['id']);
  $sysObject = 'content::user::subdoc::create';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    //Попутно находим Id корневого документа
    if ($parent['type']=='doc')
      $rootdoc_id = $parent['id'];
    }

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADD, $parent_dbdata['uid'])) return;

  //Подготовили дату к хранению в бд.
  list ($pub_date_Day, $pub_date_Month, $pub_date_Year,
        $pub_time_Hour, $pub_time_Minute)=
    sysVarCleanFromInput('pub_date_Day', 'pub_date_Month', 'pub_date_Year',
        'pub_time_Hour','pub_time_Minute');
  $date = $pub_date_Year.'-'.$pub_date_Month.'-'.$pub_date_Day.' '.$pub_time_Hour.':'.$pub_time_Minute;

  $doc_indata['pub_datetime'] = $date;

  //Вставляем информацию с какова ip был добавленн
  $doc_indata['from_ip']=sysUserIpAddr();
  $doc_indata['uid'] = sysSessionGetVar('user_id');

  //Права текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $parent_dbdata['uid']);

  //Проверка если уровень=адд и нуна ативировать выставляем active=false
  $folder_dbdata = $content->folder_get_info ($doc_indata['fid']);
  if (empty ($folder_dbdata)) sysException ('content_admin_subdoc_create', BAD_PARAM, 'Folder empty');
  if (($folder_dbdata['need_admin_active']) and ($curuser_sec_level<ACCESS_ADMIN))
    $doc_indata['active'] = false;

  // Создали поддокумент
  $content->subdoc_create ($doc_indata);

  //Переходим на страницу откуда были вызванны
  $http_referer=sysVarCleanFromInput('http_referer');
  if (!empty ($http_referer))
    sysShowMessage($http_referer, 'Страница добавленна');
    else
      sysShowMessage(sysModURL ('content','admin','doc_modify', array ('id'=>$rootdoc_id)), 'Страница добавленна');
  }

/*
 * Редактированние документа вложенного в документ
*/
function content_admin_subdoc_modify()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_subdoc_new', BAD_PARAM, 'Id not numeric '.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList (null, null, $id);
  $sysObject = 'content::admin::subdoc::modify';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    //Попутно находим Id корневого документа
    if ($parent['type']=='doc')
      $rootdoc_id=$parent['id'];
    };

  //Инфа из базы о документе
  $doc_dbdata = $content->doc_get_info($id);
  if (empty ($doc_dbdata)) sysException ('content_admin_subdoc_modify', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $doc_dbdata['uid'])) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  sysLoadLang ($sysTpl, 'content');
  $sysTpl->caching = false;

  //Права текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $doc_dbdata['uid']);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  //Инфа из базы о корневом документе
  $rootdoc_info = $content->doc_get_info ($rootdoc_id);
  $sysTpl->assign('rootdoc_info', $rootdoc_info);

  //Текущая дата
  $sysTpl->assign('pub_datetime', time());

  //Инфа о самом документе
  $sysTpl->assign($doc_dbdata);

  //Дерево страниц
  $subdocs_list = $content->doc_ItemsTreeBuild($rootdoc_id, 0);
  $sysTpl->assign('doc_subdocs_list', $subdocs_list);

  //Формируем дерево вложенных документов
  if ($curdoc_dbdata['doc_subdoc_counter']>0)
    {
    $subdoc_subdocs_list = $content->doc_ItemsTreeBuild($id, 0);
    $subdoc_subdocs_list = array_slice ($subdoc_subdocs_list, 1);//Эт чтоб самого себя не выводить
    foreach ($subdoc_subdocs_list as $key=>$value)//Уменьщаем level на 1
      {
      $value['level'] = $value['level']-1;
      $subdoc_subdocs_list[$key] = $value;
      };
    $sysTpl->assign('subdoc_subdocs_list', $subdoc_subdocs_list);
    };


  //Откуда пришел
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  $sysTpl->assign('http_referer', $http_referer);

  //BrowseIn
  $browsein = $parentslist;
  $browsein=$parents_list;
  $browsein[]=array ('url'=>sysModURL('content','admin','subdoc_modify', array ('id'=>$id)),
                     'displayname'=>"Редактированние страницы");
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] = $sysObject;
  $result['browsein'] = $browsein;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Внесение изменений в вложенный документ
*/
function content_admin_subdoc_update()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_subdoc_update', BAD_PARAM, 'Id not numeric '.$id);

  sysModClassLoad ('content');
  $content = new content;

  //Строим имя обьекта
  $parents_list =& $content->parentsList (null, null, $id);
  $sysObject = 'content::admin::subdoc::update';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    //Попутно находим Id корневого документа
    if ($parent['type']=='doc')
      $rootdoc_id=$parent['id'];
    };

  //Инфа из базы о документе
  $doc_dbdata = $content->doc_get_info($id);
  if (empty ($doc_dbdata)) sysException ('content_admin_subdoc_update', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $doc_dbdata['uid'])) return;

  //Забираем переменные со входа
  $docs_table = sysDBGetTable('content_docs');
  $docs_column = sysDBGetColumns($docs_table);
  $doc_indata = sysDBCleanFromInput ($docs_column);

  //Подготовили дату к хранению в бд.
  list ($pub_date_Day, $pub_date_Month, $pub_date_Year,
        $pub_time_Hour, $pub_time_Minute)=
    sysVarCleanFromInput('pub_date_Day', 'pub_date_Month', 'pub_date_Year',
        'pub_time_Hour','pub_time_Minute');
  $date = $pub_date_Year.'-'.$pub_date_Month.'-'.$pub_date_Day.' '.$pub_time_Hour.':'.$pub_time_Minute;
  $doc_indata['pub_datetime'] = $date;

  //Права текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $doc_dbdata['uid']);

  //Проверка на вкладывание маму в ребенка ;))
  if ($doc_dbdata['parent_id']<>$doc_dbdata['parent_id'])
    {
    //Проверка на вкладывание самого в себя
    //Строим дерево куда нас перемещают и смотрим нетли там нас
    $target_subdoc_parents_list = $content->ParentsList(null, null, $doc_indata['parent_id']);
    foreach ($target_subdoc_parents_list as $key=>$value)
      {
      if ($doc_dbdata['id']==$value)
        sysShowMessage(sysModURL ('content','admin','doc_modify', array('id'=>$rootdoc_id)), 'Нельзя вложить родительскую папку в дочернюю !!!');
      }
    }

  //Проверка, чтоб случайно юзер не активировал скрытый документ
  //Помоему, лутчше привязать к проверке активации корневого документа
  //Инфа из базы о папке
  $folder_dbdata = $content->folder_get_info ($doc_dbdata['fid']);
  if (empty ($folder_dbdata)) sysException ('content_admin_subdoc_update', BAD_PARAM, 'Folder empty');
  if (($folder_dbdata['need_admin_active']) and
      ($curuser_sec_level<ACCESS_ADMIN) and (!$doc_dbdata['active']))
    {
    $doc_indata['active'] = false;
    };

  //Функция класса для обновления
  $content->subdoc_update($doc_indata);

  //Переходим на страницу откуда были вызванны
  $http_referer = sysVarCleanFromInput('http_referer');
  if (!empty ($http_referer))
    sysShowMessage($http_referer, 'Страница изменена');
    else
      sysShowMessage(sysModURL ('content','admin','doc_modify', array('id'=>$rootdoc_id)), 'Страница изменена');
  }

/*
 * Подтверждение удаления документа в документе
*/
function content_admin_subdoc_confirm_delete()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_subdoc_update', BAD_PARAM, 'Id not numeric '.$id);

  sysModClassLoad ('content');
  $content = new content;

  //Строим имя обьекта
  $parents_list =& $content->parentsList (null, null, $id);
  $sysObject = 'content::admin::subdoc::confirm_delete';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    //Попутно находим Id корневого документа
    if ($parent['type']=='doc')
      $rootdoc_id=$parent['id'];
    };

  //Инфа из базы о документе
  $doc_dbdata = $content->doc_get_info($id);
  if (empty ($doc_dbdata)) sysException ('content_admin_subdoc_confirm_delete', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_DELETE, $doc_dbdata['uid'])) return;

  // Шаблоны
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  sysLoadLang ($sysTpl, 'content');
  $sysTpl->caching = false;

  //Права текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $doc_dbdata['uid']);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  //Инфа из базы
  $sysTpl->assign($doc_dbdata);

  //BrowseIn
  $browsein=$parents_list;
  $browsein[]=array ('url'=>sysModURL('content','admin','subdoc_confirm_delete', array ('id'=>$id)),
                     'displayname'=>"Удаление страницы");
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] = $sysObject;
  $result['browsein'] = $browsein;
  $result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Удаление документа
*/
function content_admin_subdoc_delete()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_subdoc_delete', BAD_PARAM, 'Id not numeric '.$id);

  sysModClassLoad ('content');
  $content = new content;

  //Строим имя обьекта
  $parents_list =& $content->parentsList (null, null, $id);
  $sysObject = 'content::user::subdoc::delete';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    //Попутно находим Id корневого документа
    if ($parent['type']=='doc')
      $rootdoc_id = $parent['id'];
    };

  //Инфа из базы о документе
  $doc_dbdata = $content->doc_get_info($id);
  if (empty ($doc_dbdata)) sysException ('content_admin_subdoc_delete', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_DELETE, $doc_dbdata['uid'])) return;

  $content->subdoc_delete ($id);

  sysShowMessage(sysModURL ('content','admin','doc_modify', array ('id'=>$rootdoc_id)), 'Страница удалена');
  }


/*
 * Увеличение вес страницы
*/
function content_admin_subdoc_moveup ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_subdoc_moveup', BAD_PARAM, 'Id not numeric '.$id);

  sysModClassLoad ('content');
  $content = new content;
  sysExtLibLoad();

  //Строим имя обьекта
  $parents_list =& $content->parentsList (null, null, $id);
  $sysObject = 'content::admin::subdoc::moveup';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']=='doc'){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    //Попутно находим Id корневого документа
    if ($parent['type']=='doc')
      $rootdoc_id = $parent['id'];
    }

  //Инфа из базы о документе
  $doc_dbdata = $content->doc_get_info($id);
  if (empty ($doc_dbdata)) sysException ('content_admin_subdoc_moveup', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $doc_dbdata['uid'])) return;

  //Подвинули вверх
  $docs_table = sysDBGetTable('content_docs');
  $docs_column =  sysDBGetColumns($docs_table);
  sysDbWeightMoveUp ($docs_table, $doc_dbdata['weight'], "WHERE $docs_column[parent_id] = '$doc_dbdata[parent_id]'");

  //Чистим кеша
  $content->clear_cache ($parents_list);

  //Возвращаемся на вызывающую страницу
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  sysRedirect($http_referer);
  }

/*
 * Уменьшаем вес страницы
*/
function content_admin_subdoc_movedown ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_subdoc_moveup', BAD_PARAM, 'Id not numeric '.$id);

  sysModClassLoad ('content');
  $content = new content;
  sysExtLibLoad();

  //Строим имя обьекта
  $parents_list =& $content->parentsList (null, null, $id);
  $sysObject = 'content::admin::subdoc::movedown';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']==doc){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    //Попутно находим Id корневого документа
    if ($parent['type']=='doc')
      $rootdoc_id = $parent['id'];
    };

  //Инфа из базы о документе
  $doc_dbdata = $content->doc_get_info($id);
  if (empty ($doc_dbdata)) sysException ('content_admin_doc_modify', BAD_PARAM, 'Doc empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $doc_dbdata['uid'])) return;

  //Подвинули вверх
  $docs_table = sysDBGetTable('content_docs');
  $docs_column =  sysDBGetColumns($docs_table);
  sysDbWeightMoveDown ($docs_table, $doc_dbdata['weight'], "WHERE $docs_column[parent_id] = '$doc_dbdata[parent_id]'");

  //Чистим кеша
  $content->clear_cache ($parents_list);

  //Возвращаемся на вызывающую страницу
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  sysRedirect($http_referer);
  }


/*
 * Добавление фотографии
*/
function content_admin_image_create()
{
	//Забираем переменные со входа
	$images_table = sysDBGetTable('content_images');
	$images_column =  sysDBGetColumns($images_table);

	//Параметры документа со входа
	$image_indata = sysDBCleanFromInput ($images_column);

	//$image_indata['image_file'] = sysVarCleanFromInput('image_file');
	$image_indata['image_file'] = $_FILES['image_file'];

	$sysObject = 'content::admin::image::create';

	 //Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_ADD)) return;

	// Грузим класс
	sysExtLibLoad('imageinslib');
	$sysInsImage = new sysInsImage();
	
	$res = $sysInsImage->image_create($image_indata, "content", "doc");

	sys_log('content_admin_image_create',LOG_INFO,'Creating image',array('doc_id'=>$image_indata['doc_id']), 'content');

	settype($image_indata['doc_id'],"integer");
	$http_referer = sysVarCleanFromInput('http_referer');
	if (!$http_referer)
		$http_referer = $_SERVER['HTTP_REFERER'];

	if (empty ($http_referer)) {
		$fids = $content-> get_documents_folders($image_indata['doc_id']);
		if ($fids) {
			$fid = $fids[0];
			$http_referer = sysModURL ('content','admin','doc_modify',array('id'=>$image_dbdata['doc_id'], 'fid' => $fid));
		}
			
	}
  

    sysShowMessage($http_referer, 'Добавление успешно произведено');
 
}

/*
 * Редактирование фотографии
*/
function content_admin_image_modify()
{
	//Забираем переменные со входа
	$id = sysVarCleanFromInput('id');
	$fid = sysVarCleanFromInput('fid');
	if (!is_numeric ($id)) sysException ('content_admin_image_modify', BAD_PARAM, 'Id not numeric');

	//Грузим класс
	sysModClassLoad ('content');
	$content = new content;

	// Информация о фотке
	sysExtLibLoad('imageinslib');
	$sysInsImage = new sysInsImage();
	$image_dbdata = $sysInsImage->image_get_info($id, 'shop');
	
	//Информация о документе
	$doc_dbdata = $shop->doc_get_info($image_dbdata['doc_id'], $fid);

	//Строим имя обьекта
	$sysObject = 'content::admin::image::modify';
	
	$parents_list = $content->parentsList2($image_dbdata['doc_id'], $fid);

	foreach ($parents_list as $parent)   {
		if ($parent['type']=='doc') {
			$sysObject.='::image';
		}
		$sysObject.='::'.$parent['id'];
    }
	$sysObject .= "::image::$id";

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_EDIT)) return;

	// Шаблоны
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject);
	$sysTpl->caching = false;

	//Дату в шаблон
	$sysTpl->assign($image_dbdata);

	$http_referer = $_SERVER['HTTP_REFERER'];
	$sysTpl->assign('http_referer', $http_referer);

	//BrowseIn
	$browsein = array();

	$browsein[] = array ('url'=>sysModURL('content','admin','image_modify', array('id'=>$image_dbdata['id'])),
                       'displayname'=>'Редактирование фото');
	$sysTpl->assign('browsein', $browsein);

	//Вывод результатов
	$result['object'] = $sysObject;
	$result['browsein'] = $browsein;
	$result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

/*
 * Редактирование фотографии товара
*/
function content_admin_image_update()
{
	//Забираем переменные со входа
	$images_table = sysDBGetTable('content_images');
	$images_column =  sysDBGetColumns($images_table);

	//Параметры со входа
	$image_indata = sysDBCleanFromInput ($images_column);

	$sysObject = 'content::admin::image::update';

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_EDIT)) return;

	sysModClassLoad ('content');
	$content = new content;

	//Информация о фотографии
	sysExtLibLoad('imageinslib');
	$sysInsImage = new sysInsImage();
	$image_dbdata = $sysInsImage->image_get_info($image_indata['id'], 'content');

	//Вызвали функцию для вставки 
	$sysInsImage->image_update ($image_indata, 'content', 'doc');


	if ($image_indata['image_file']['tmp_name'])
		sys_log('content_admin_image_update', LOG_INFO, 'Updating image',array('id'=>$image_indata['id'],'doc_id'=>$image_dbdata['doc_id']), 'content');
	else
		sys_log('content_admin_image_update',LOG_INFO,'Updating image text',array('id'=>$image_indata['id'],'doc_id'=>$image_dbdata['doc_id']),'content');

	//Переходим на страницу откуда были вызванны
	$http_referer = sysVarCleanFromInput('http_referer');
  
	if (empty ($http_referer)) {
		$fids = $content->get_documents_folders($image_indata['doc_id']);
		if ($fids) {
			$fid = $fids[0];
			$http_referer = sysModURL ('content','admin','doc_modify',array('id'=>$image_dbdata['doc_id'], 'fid' => $fid));
		}
			
	}
 
    sysShowMessage($http_referer, 'Редактирование успешно произведено');
  
}


  /*
 * Увеличение веса фотографии
*/
function content_admin_image_moveup ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_image_moveup', BAD_PARAM, 'Id not numeric '.$id);

  sysModClassLoad ('content');
  $content = new content;

  //Строим имя обьекта
  $sysObject = 'content::admin::image::moveup';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Информация о категорие
  $image_dbdata = $content->image_get_info($id);

  //Передвижка веса
  $content->image_moveup($id);

  //Чистим tpl кеша
  $content->tplcache_clear();

  //Возвращаемся на вызывающую страницу
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  if (!empty ($http_referer))
    sysRedirect($http_referer);
    else
      sysRedirect(sysModURL ('content','user','main'));
  }

/*
 * Уменьшение веса фотографии
*/
function content_admin_image_movedown ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_admin_image_movedown', BAD_PARAM, 'Id not numeric '.$id);

  sysModClassLoad ('content');
  $content = new content;

  //Строим имя обьекта
  $sysObject = 'content::admin::image::movedown';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Информация о фотографие
  $image_dbdata = $content->image_get_info($id);

  //Передвижка веса
  $content->image_movedown($id);

  //Чистим tpl кеша
  $content->tplcache_clear();

  //Возвращаемся на вызывающую страницу
  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  if (!empty ($http_referer))
    sysRedirect($http_referer);
    else
      sysRedirect(sysModURL ('content','user','main'));
  }



  /*
 * Удаление фотографии    подтверждение
*/
function content_admin_image_confirm_delete()
{
	//Забираем переменные совхода
	$id = sysVarCleanFromInput('id');
	if (!is_numeric ($id)) sysException ('content_admin_image_confirm_delete', BAD_PARAM, 'Id not numeric');

	//Грузим класс
	sysModClassLoad ('content');
	$content = new content;

	sysExtLibLoad('imageinslib');
	$sysInsImage = new sysInsImage();
	$image_dbdata = $sysInsImage->image_get_info($id, 'content');

	$sysObject = 'content::admin::image::confirm_delete';


	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_DELETE)) return;

	// Класс шаблона
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject);
	$sysTpl->caching = false;

	// Информация
	$sysTpl->assign($image_dbdata);

	$http_referer = $_SERVER['HTTP_REFERER'];
	$sysTpl->assign('http_referer', $http_referer);
	
	//BrowseIn
	$browsein = array();
	$browsein[] = array ('url'=>sysModURL('content','user','main'),
                       'displayname'=>'Контент');

	$browsein[] = array ('url'=>sysModURL('content','admin','image_confirm_delete', array('id'=>$id)),
                       'displayname'=>'Удаление');
	$sysTpl->assign('browsein', $browsein);

	//Вывод результатов
	$result['object'] = $sysObject;
	$result['browsein'] = $browsein;
	$result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

/*
 * Удаление фотографии товара
*/
function content_admin_image_delete()
{
	//Забираем переменные совхода
	$id = sysVarCleanFromInput('id');
	if (!is_numeric ($id)) sysException ('content_admin_image_delete', BAD_PARAM, 'Id not numeric');
	$http_referer =  sysVarCleanFromInput('http_referer');
	$sysObject = 'content::admin::image::delete';

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_DELETE)) return;

	// Класс 
	sysModClassLoad ('content');
	$content = new content;
	sysExtLibLoad('imageinslib');
	$sysInsImage = new sysInsImage();
	$image_dbdata = $sysInsImage->image_get_info($id, 'content');

	//Вызвали функцию clasa для удаления
	$sysInsImage->image_delete($id, 'content', 'doc');

	sys_log('content_admin_image_delete',LOG_INFO,'Deletind image',array('id'=>$id, 'doc_id'=>$image_dbdata['doc_id']), "content");

	
	if (empty ($http_referer)) {
		$fids = $content-> get_documents_folders($image_dbdata['doc_id']);
		if ($fids) {
			$fid = $fids[0];
			$http_referer = sysModURL ('content','admin','doc_modify',array('id'=>$image_dbdata['doc_id'], 'fid' => $fid));
		}
			
	}
	
	sysShowMessage($http_referer, 'Удаление успешно произведено');

}

/*
* Изменение сортировки разделов
*/
function content_admin_folder_weight ()  {

	$weights = sysVarCleanFromInput('weights');
	$ids = sysVarCleanFromInput('ids');
  
	$sysObject = 'content::admin::folder::weight';

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_EDIT)) return;
	

	$folder_table=sysDBGetTable('content_folders');
	foreach ($weights as $k=>$v){
		$indata=array('weight'=>$v,'id'=>$ids[$k]);
		sysDBUpdate($folder_table,$indata,"WHERE id=$indata[id]");
	}

	$http_referer = $_SERVER['HTTP_REFERER'];
	if (empty ($http_referer)) $http_referer='/shop';

	sysRedirect($http_referer);
 
}


//
// Создание новой заявки order
//
function content_admin_order_create() {

  //Забираем переменные со входа
  $docs_table = sysDBGetTable('content_docs');
  $docs_column =  sysDBGetColumns($docs_table);
  //Параметры документа со входа

 // $doc_indata = sysDBCleanFromInput ($docs_column);
  // Получаем POST данные, а не какие-то там непонятные значения.
  $doc_indata = $_POST;


$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/files/shared/orders/';

$orderfile=$_FILES['orderfile'];
if (is_uploaded_file($orderfile['tmp_name']))
{
$fname=date("\f\i\l\e_dmy_His").'.'.substr($orderfile['name'],strrpos($orderfile['name'],'.')+1);

if (move_uploaded_file($orderfile['tmp_name'],$uploaddir.$fname))
{
//$doc_indata['content']=$doc_indata['content']."Примечение: приложенный файл доступен по ссылке: ".sysGetBaseURL()."files/shared/orders/".$fname;
chmod ($uploaddir.$fname, 0666);
$doc_indata['url']=sysGetBaseURL()."files/shared/orders/".$fname;


}
}


if (!$doc_indata['fids'] or count($doc_indata['fids'])==0) 
	{
	$fold=array();
	$fold[0]=$doc_indata['fid'];
	$doc_indata['fids']=$fold;
	}

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;


  $parents_list =& $content->parentsList ($doc_indata['fid']);
  $sysObject = 'content::admin::doc::create';
  foreach ($parents_list as $parent)
    $sysObject.='::'.$parent['id'];

  //Получили инфу о разделе
  $folder_dbdata =& $content->folder_get_info ($doc_indata['fid']);
  if (empty ($folder_dbdata)) sysException ('content_admin_folder_modify', BAD_PARAM, 'Folder empty');

  //Уровень доступа у текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $folder_dbdata['uid']);

  //Добавляем информацию с какова ip был добавленн
  $doc_indata['from_ip'] = sysUserIpAddr();
  $doc_indata['uid'] = sysSessionGetVar('user_id');

  $date = date("Y-m-d G:i");
  $doc_indata['pub_datetime'] = $date;

     $doc_indata['active'] = true;


  //Вызвали функцию для вставки документа
  $content->order_create ($doc_indata);

   // Грузим языковые константы для сообщения
  $sysTpl = new sysTpl;
  sysLoadLang ($sysTpl, 'content');
  
  sysShowMessage("/index.php", $sysTpl->get_config_vars('added_zakaz'), 5);
  }


?>
