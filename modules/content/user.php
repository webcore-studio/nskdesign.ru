<?php
require_once('modules/content/include/idna_convert.class.php');

/*
 * Главная страница
*/
function content_user_main()
{
	$sysObject = 'content::user::main';

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

	//Работа с sysTpl
	$sysTpl = new  sysTpl;
	$sysModTpl = sysTplWay ($sysObject);

	if($sysTpl->is_cached($sysModTpl, $sysObject))   {
		//Возвращаем
		$result['object'] =& $sysObject;
		$result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject);
		$result['browsein'] =& $sysTpl->fetch_cached_vars();
		return $result;
    }

	sysLoadLang ($sysTpl, 'content');

	//Построили дерево разделов
	sysModClassLoad ('content');
	$content = new content;
	$folders_list =& $content->folder_ItemsTreeBuild (0, 0, 'weight');
	$sysTpl->assign('folders_list', $folders_list);

	sys_log('content', LOG_INFO, "content_user_main folders_list", $folders_list);

	//BrowseIn
	$browsein[]=array ('url'=>'/content',
                     'displayname'=>'Разделы сайта');

	//Вывод результатов
	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject, $browsein);
	$result['browsein'] =& $browsein;
	return $result;
}

//
// Просмотр раздела
//
function content_user_folder_view()
{
	//Забираем переменные
	list ($id, $page_current, $subdoc_orderby, $subdoc_order_asc, $name) = sysVarCleanFromInput('id', 'page', 'subdoc_orderby', 'subdoc_order_asc', 'name');
	if (empty($page_current))
		$page_current = 1;

	sysModClassLoad ('content');
	$content = new content;
	
	//Находим параметры раздела
	if (!$id && !$name)
		sysException ('content_user_folder_view', ID_NOT_EXIST, 'Folder param empty ');
	elseif ($id > 0)
		$folder_dbdata = $content->folder_get_info ($id);
	elseif ($name != "")
		$folder_dbdata = $content->folder_get_info_by_name ($name);
	if (empty($folder_dbdata))
		sysException ('content_user_folder_view', ID_NOT_EXIST, "Folder not exists: id=$id, name=$name");

	sys_log('content', LOG_INFO, "content_user_folder_view ", $folder_dbdata);
		
	$name = $folder_dbdata['name'];
	$id = $folder_dbdata['id'];
	if (empty($name)) 
		$name = $folder_dbdata['name'] = $id;

	//Строим имя обьекта
	$parents_list =& $content->parentsList2(0,$id);
	$sysObject = 'content::user::folder::view';
	foreach ($parents_list as $parent)
		$sysObject.='::'.$parent['id'];
		
	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_READ, $folder_dbdata['uid'])) return;

	//Проверка корректности обращения по имени страницы
	sysExtLibLoad('pluginslib');
	$sysPlugins = new sysPlugins();
		
	$page_short_url = sysModShortURL ('content', 'user', '', array('id' => $id, 'name' => $folder_dbdata['name'], 'page' => $page_current));
	$url_param = sysGetUrlParam('content');
	$sysPlugins->plugin_exe('content', 'url_param', 'get', array("url_param"=>&$url_param, "type"=>"folder"));
	sysCheckShortUrl($page_short_url, 'content', $url_param);

	//Увеличили счетчик просмотров раздела
	$content->folder_inc_view_counter($id);

	//Работа с sysTpl
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject, $folder_dbdata['tpl']);
	//Проверка в кеше
	if ($sysTpl->is_cached($sysModTpl, $sysObject."::$page_current::$subdoc_orderby::$subdoc_order_asc"))   {
		//Возвращаем
		$result['object'] =& $sysObject;
		$result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject."::$page_current::$subdoc_orderby::$subdoc_order_asc");
		$result['browsein'] =& $sysTpl->fetch_cached_vars();
		return $result;
    }

	//Языковые константы
	sysLoadLang ($sysTpl, 'content');

	//Уровень доступа у текущего пользователя
	$curuser_sec_level = sysSecGetLevel ($sysObject, $folder_dbdata['uid']);
	$sysTpl->assign('curuser_sec_level', $curuser_sec_level);

	//В шаблон инфу о текущей папке
	$sysTpl->assign($folder_dbdata);

	$logo_x = sysModGetVar('content','logo_thumb_width');
    $logo_y = sysModGetVar('content','logo_thumb_height');
    $logo_jpegqual = sysModGetVar('content','logo_thumb_qual');
    $logo_method = sysModGetVar('content','logo_thumb_method');
	
	$dest_x_big =sysModGetVar('content','image_width');
	$dest_y_big =sysModGetVar('content','image_height');
	$jpegqual_big = sysModGetVar('content','image_qual');
    $method_big = sysModGetVar('content','image_method');
	
    sysExtLibLoad('imageinslib');
    $sysInsImage = new sysInsImage();
	
	//Список под разделов
	$sub_folders_list =& $content->folder_get_list ($id, $curuser_sec_level);
  
	if ($folder_dbdata['open_subfolders']) {
		if (count($sub_folders_list)>0)
			foreach ($sub_folders_list as $key => $subfolder)	{
				$subdocs =& $content->doc_get_list ($subfolder['id'], $curuser_sec_level, 1, $subfolder['subdoc_orderby'], $subfolder['subdoc_order_asc']);
				if (count($subdocs["sub_docs_list"]) > 0)	{
					foreach ($subdocs["sub_docs_list"] as $k => $subdoc)	{
						if ($subdoc['logo_id'] <= 0) continue;
						$image_data = $sysInsImage->get_thumb_info('content', 'doc', $subdoc['id'], $subdoc['logo_id'], $logo_x, $logo_y, $logo_jpegqual, $logo_method);
						if ($image_data)
							$subdocs["sub_docs_list"][$k] = $subdocs["sub_docs_list"][$k]+$image_data;
					}
					$sub_folders_list[$key]['subdocs'] = $subdocs["sub_docs_list"];
				} 
			}
	}
	sys_log('content', LOG_INFO, "content_user_folder_view ", $sub_folders_list);

	$sysTpl->assign('sub_folders_list', $sub_folders_list);

	//Список документов
	$sub_docs_list = $content->doc_get_list ($id, $curuser_sec_level, $page_current, $subdoc_orderby, $subdoc_order_asc);
	foreach ($sub_docs_list["sub_docs_list"] as $k => $doc)  {
		if ($doc['logo_id'] <= 0) 
			continue;
		$image_data = $sysInsImage->get_thumb_info('content', 'doc', $doc['id'], $doc['logo_id'], $logo_x, $logo_y, $logo_jpegqual, $logo_method);
		$sub_docs_list["sub_docs_list"][$k] = $sub_docs_list["sub_docs_list"][$k] + $image_data;
		
		$image_big_data = $sysInsImage->get_thumb_info('content', 'doc', $doc['id'], $doc['logo_id'], $dest_x_big, $dest_y_big, $jpegqual_big, $method_big);
		$sub_docs_list["sub_docs_list"][$k]['big'] = $image_big_data; 
		
	}

	$sysTpl->assign('sub_docs_list',$sub_docs_list["sub_docs_list"]);
	$sysTpl->assign('page_total',$sub_docs_list["page_total"]);
	$sysTpl->assign('page_current',$sub_docs_list["page_current"]);
	$sysTpl->assign('doc_perpage',$sub_docs_list["doc_perpage"]);
	$sysTpl->assign('doc_total',$sub_docs_list["doc_total"]);
	$sysTpl->assign('subdoc_orderby',$sub_docs_list["subdoc_orderby"]);
	$sysTpl->assign('subdoc_order_asc',$sub_docs_list["subdoc_order_asc"]);
	$sysTpl->assign('weight_moveup_url',$sub_docs_list["weight_moveup_url"]);
	$sysTpl->assign('weight_movedown_url',$sub_docs_list["weight_movedown_url"]);

	//Если папка или документ не активен значит не выводим в дереве
	foreach ($parents_list as $key=>$parent)   {
		if (!$parent['id'])
			unset ($parents_list[$key]);
	}
	$parents_list = array_values($parents_list);


	//Загоняем в шаблон browsein
	$sysTpl->assign('browsein', $parents_list);

	//Вывод результатов
	$result['object'] =& $sysObject;
	$result['browsein'] =& $parents_list;
	$result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject."::$page_current::$subdoc_orderby::$subdoc_order_asc", $parents_list);
	$result['meta_keywords'] = $folder_dbdata['meta_keywords'];
	$result['meta_description'] =$folder_dbdata['meta_description'];
	$result['meta_title'] = $folder_dbdata['meta_title'];
	return $result;
}



/*
 * Просмотр документа
*/
function content_user_doc_view()  
{
  
	//переменные со входа
	list ($id, $doc_part_current,$fid, $fname, $dname) = sysVarCleanFromInput('id', 'part', 'fid', 'fname', 'dname');
  
	//Для page_break  какая часть документа открыта
	if (empty($doc_part_current)){
		$doc_part_current = 1;
	}  

	sysModClassLoad ('content');
	$content = new content;

	//Находим параметры раздела
	if (!$fid && !$fname)
		sysException ('content_user_doc_view', ID_NOT_EXIST, 'Folder param empty ');
	elseif ($fid > 0)
		$folder_dbdata = $content->folder_get_info ($fid);
	elseif ($fname != "")
		$folder_dbdata = $content->folder_get_info_by_name ($fname);
	if (empty($folder_dbdata))
		sysException ('content_user_doc_view', ID_NOT_EXIST, "Folfer not exists: fid=$fid, fname=$fname");
		
	$fname = $folder_dbdata['name'];
	$fid = $folder_dbdata['id'];
	if (empty($fname)) 
		$folder_dbdata['name'] = $fname = $fid;


		//Находим параметры документа
	if (!$id && !$dname)
		sysException ('content_user_doc_view', ID_NOT_EXIST, 'Doc param empty ');
	elseif ($id > 0)
		$doc_view_info = $content->doc_get_info ($id, $doc_part_current);
	elseif ($dname != "")
		$doc_view_info = $content->doc_get_info_by_name ($dname, $doc_part_current);
	if (empty($doc_view_info))
		sysException ('content_user_doc_view', ID_NOT_EXIST, "Doc not exists: id=$id, dname=$dname");
		
	$dname = $doc_view_info['name'];
	$id = $doc_view_info['id'];
	
	if (empty($doc_view_info['name']) ) 
		$dname = $doc_view_info['name'] = $id;

	//проверяем, лежит ли этот документ в этом разделе
	if (!$content->exists_links($id, $fid)) {
		sysException ('content_user_doc_view', ID_NOT_EXIST, "Link not exists: doc $id in folder $fid");
	}

 	//Проверка корректности обращения по имени страницы
	sysExtLibLoad('pluginslib');
	$sysPlugins = new sysPlugins();
		
	$page_short_url = sysModShortURL ('content', 'user', $fname, array('id' => $id, 'name' => $doc_view_info['name']), $postfix = true);
	$url_param = sysGetUrlParam('content');
	$sysPlugins->plugin_exe('content', 'url_param', 'get', array("url_param"=>&$url_param, "type"=>"doc"));
	sysCheckShortUrl($page_short_url, 'content', $url_param);

	//Строим имя обьекта
	if ($fid)  {
		//если задан каталог
		$parents_list =& $content->parentsList2 ($id, $fid);
	}
	else {
		$parents_list =& $content->parentsList ($id);
	}
	$sysObject = 'content::user::doc::view';
  
	foreach ($parents_list as $parent)    {
		if ($parent['type'] == "doc") {
			$sysObject.='::doc';
		}
		$sysObject.='::'.$parent['id'];
    }

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_READ, $doc_view_info['uid'])) return;

	//Увеличили счетчик просмотров
	if ($doc_part_current==1)
		$content->doc_inc_view_counter($id);

	//Увеличили популярность
	$content->doc_inc_popularity($id);

	//Работа с sysTpl
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject, $doc_view_info['tpl']);

	if ($sysTpl->is_cached($sysModTpl,$sysObject))    {
		//Возвращаем
		$result['object'] =& $sysObject;
		$result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject);
		$result['browsein'] =& $sysTpl->fetch_cached_vars();
		return $result;
    }

	//фотки
	$images_list = $content->image_get_list($id);

	//Формируем урлы на thumbs или создаем
	if (count($images_list)>0)	{
		
		$image_x =sysModGetVar('content','image_width');
		$image_y = sysModGetVar('content','image_height');
		$image_jpegqual = sysModGetVar('content','image_qual');
		$image_method = sysModGetVar('content','image_method');

		$logo_x =sysModGetVar('content','logo_width');
		$logo_y =sysModGetVar('content','logo_height');
		$logo_jpegqual = sysModGetVar('content','logo_qual');
		$logo_method = sysModGetVar('content','logo_method');

		sysExtLibLoad('imageinslib');
		$sysInsImage = new sysInsImage();

		foreach ($images_list as $key => $image)	{
			if ($doc_view_info['logo_id'] == $image['id']) 	{
				$logo_data = $sysInsImage->get_thumb_info('content', 'doc', $image['doc_id'], $image['id'], $logo_x, $logo_y, $logo_jpegqual, $logo_method);
				$doc_view_info['thumb_url'] = $logo_data['thumb_url'];
				$logo_big_data  =$sysInsImage->get_thumb_info('content', 'doc', $image['doc_id'], $image['id'], $image_x, $image_y, $image_jpegqual, $image_method);
				$doc_view_info['thumb_url_big'] = $logo_big_data['thumb_url'];
				unset($images_list[$key]);
			}
			else {
				$image_data=$sysInsImage->get_thumb_info('content', 'doc', $image['doc_id'], $image['id'], $logo_x, $logo_y, $logo_jpegqual, $logoe_method);
				$images_list[$key] = $images_list[$key] + $image_data;

				$image_big_data=$sysInsImage->get_thumb_info('content', 'doc', $image['doc_id'], $image['id'], $image_x, $image_y, $image_jpegqual, $image_method);
				$images_list[$key]['big'] = $image_big_data; 
			}
			
		}
 
		//Информацию в шаблон
		$sysTpl->assign('images_list', $images_list);

	}
	
	//встречается ли в контенте тэг для вывода картинок
	if (strpos($doc_view_info['content'], '{images}') === false) 
		$images_flag = false;
	else $images_flag = true;

	$sysTpl->assign('images_flag', $images_flag); 

	if (($images_flag) and (!$images_list))
		$doc_view_info['content'] = str_replace('{images}', '', $doc_view_info['content']);

	//Список документов для следующей-предыдущей страниц
	/*
	$t = 0;
	$sub_docs= $content->doc_get_list ($fid, $curuser_sec_level, 1, null, null,65500);
	$sub_docs_list = $sub_docs['sub_docs_list'];
	if (count($sub_docs_list)>0) {
foreach ($sub_docs_list as $k=>$doc)
	if ($doc['id']==$id) {$t=$k; break;}
$sysTpl->assign('current',$t+1); 

$doc_first=array();
	if ($sub_docs_list[0]['name']) $doc_first['link']='/'.$fname.'/'.$sub_docs_list[0]['name'].'.html';
	else $doc_first['link']='/'.$fname.'/'.$sub_docs_list[0]['id'].'.html';
$doc_first['displayname']=$sub_docs_list[0]['displayname'];
$sysTpl->assign('doc_first',$doc_first); 

if ($t+1<count($sub_docs_list))
{
$doc_next=array();
if ($sub_docs_list[$t+1]['name']) $doc_next['link']='/'.$fname.'/'.$sub_docs_list[$t+1]['name'].'.html';
else $doc_next['link']='/'.$fname.'/'.$sub_docs_list[$t+1]['id'].'.html';
$doc_next['displayname']=$sub_docs_list[$t+1]['displayname'];
$sysTpl->assign('doc_next',$doc_next); 
}
if ($t>0)
{
$doc_prev=array();
if ($sub_docs_list[$t-1]['name']) $doc_prev['link']='/'.$fname.'/'.$sub_docs_list[$t-1]['name'].'.html';
else $doc_prev['link']='/'.$fname.'/'.$sub_docs_list[$t-1]['id'].'.html';
$doc_prev['displayname']=$sub_docs_list[$t-1]['displayname'];
$sysTpl->assign('doc_prev',$doc_prev); 
}

}
*/
	//Грузим язык
	sysLoadLang ($sysTpl, 'content');

	//Уровень доступа у текущего пользователя
	$curuser_sec_level = sysSecGetLevel ($sysObject, $doc_view_info['uid']);
	$sysTpl->assign('curuser_sec_level', $curuser_sec_level);
	
	// Информацию о документе в шаблон
	$sysTpl->assign($doc_view_info);
	$sysTpl->assign('folder_dbdata', $folder_dbdata);
  
	//Если папка или документ не активен значит не выводим в дереве
	foreach ($parents_list as $key=>$parent)    {
		if (!$parent['active'])
		unset ($parents_list[$key]);
    }

	$parents_list = array_values($parents_list);

	$sysTpl->assign('browsein', $parents_list);
	$sysTpl->assign('subpages_tree_tag', '{subpages_tree}');
	$sysTpl->assign('subpages_list_tag', '{subpages_list}');
	$sysTpl->assign('images_list_tag', '{images}');
	$sysTpl->assign('fid',$fid);
	$sysTpl->assign('fname',$fname);
  
	//Вывод результатов
	$result['browsein'] =& $parents_list;
	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject, $parents_list);
	$result['meta_keywords'] = $doc_view_info['meta_keywords'];
	$result['meta_description'] = $doc_view_info['meta_description'];
	$result['meta_title'] = $doc_view_info['meta_title'];

	return $result;
}

/*
 * Просмотр страницы вложенной в документ
*/
function content_user_subdoc_view()
  {
  list ($id, $doc_part_current) = sysVarCleanFromInput('id', 'part');
  if (empty($doc_part_current)) $doc_part_current=1;
  if (!is_numeric ($id)) sysException ('content_user_subdoc_view', BAD_PARAM, 'Id not numeric'.$id);

  //Строим имя обьекта
  sysModClassLoad ('content');
  $content = new content;
  $parents_list =& $content->parentsList (null, null, $id);
  $sysObject = 'content::user::subdoc::view';
  foreach ($parents_list as $parent)
    {
    if ($parent['type']==doc){$sysObject.='::doc';};
    $sysObject.='::'.$parent['id'];
    //Попутно находим Id корневого документа
    if ($parent['type']=='doc')
      $rootdoc_id = $parent['id'];
    }

  //Информация о документе
  $sub_doc_view_info = $content->subdoc_get_view_info($id, $rootdoc_id, $doc_part_current);
  if (empty ($sub_doc_view_info)) sysException ('content_user_subdoc_view', BAD_PARAM, 'SubDoc empty');

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_READ, $sub_doc_view_info['uid'])) return;

  //Увеличили счетчик просмотров
  if ($doc_part_current==1)
    $content->doc_inc_view_counter ($id);

  //Работа с sysTpl
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);

  if($sysTpl->is_cached($sysModTpl,$sysObject))
    {
    //Возвращаем
    $result['object'] =& $sysObject;
    $result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject);
    $result['browsein'] =& $sysTpl->fetch_cached_vars();
    return $result;
    };

  // Загружаем языковые константы
  sysLoadLang ($sysTpl, 'content');

  //Уровень доступа у текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject, $sub_doc_view_info['uid']);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);

  // Информацию о поддокументе в шаблон
  $sysTpl->assign($sub_doc_view_info);

  //BrowseIn
  //Если папка или документ не активен значит не выводим в дереве
  foreach ($parents_list as $key=>$parent)
    {
    if (!$parent['active'])
      unset ($parents_list[$key]);
    }
  $parents_list = array_values($parents_list);
  $sysTpl->assign('browsein', $parents_list);

  $sysTpl->assign('subpages_tree_tag', '{subpages_tree}');
  $sysTpl->assign('subpages_list_tag', '{subpages_list}');

  //Вывод результатов
  $result['browsein'] =& $parents_list;
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject, $parents_list);
  $result['meta_keywords'] =$sub_doc_view_info['displayname'];
  $result['meta_description'] =$sub_doc_view_info['displayname'];
  return $result;
  }

/*
 * Увеличение счетчика скачиваний
*/
function content_user_url_counter()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('content_user_url_counter', BAD_PARAM, 'Id not numeric'.$id);

  sysModClassLoad ('content');
  $content = new content;
  $url = $content->url_counter($id);
  
 $url = "".$url;

	$idn = new idna_convert(array('idn_version'=>2008));
	$url=stripslashes($url);
	$url=$idn->encode($url);


Header("Location: http://".$url);
exit;
  };
  
    
  /*
 * Показ картинки кипчи
*/
function content_user_captcha_view()
  {
  //Проверка на доступ
  $sysObject = 'content::user::captcha_view';
 if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

	//Рисуем капчу
  sysExtLibLoad('captcha');
	$captcha = new sysCaptcha();
	$captcha -> display();

	//Кладем в переменную сессии нарисованные букавки
	sysSessionSetVar('content_captcha_string',$captcha -> getString());

  sysTerminate;
  exit;
  };


?>