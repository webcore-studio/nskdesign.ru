<?php

function content_ajax_doc_list ()
{
	//Забираем переменные
	list ($fid, $page_current) = sysVarCleanFromInput('fid', 'page');
	if (empty($page_current))
		$page_current = 1;

	sysModClassLoad ('content');
	$content = new content;
	
	//Находим параметры раздела
	if ($fid <= 0)
		sysException ('content_ajax_folder_view', ID_NOT_EXIST, 'Folder param empty ');
	else
		$folder_dbdata = $content->folder_get_info ($fid);

	if (empty($folder_dbdata))
		sysException ('content_ajax_folder_view', ID_NOT_EXIST, "Folder not exists: id=$fid");
		
	$name = $folder_dbdata['name'];
	if (empty($name)) 
		$name = $folder_dbdata['name'] = $fid;

	//Строим имя обьекта
	$parents_list =& $content->parentsList2(0, $fid);
	$sysObject = 'content::ajax::doc::list';
	foreach ($parents_list as $parent)
		$sysObject.='::'.$parent['id'];
		
	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_READ, $folder_dbdata['uid'])) return;

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

    sysExtLibLoad('imageinslib');
    $sysInsImage = new sysInsImage();
	
	//Список документов
	$sub_docs_list = $content->doc_get_list ($fid, $curuser_sec_level, $page_current, $subdoc_orderby, $subdoc_order_asc);
	foreach ($sub_docs_list["sub_docs_list"] as $k => $doc)  {
		if ($doc['logo_id'] <= 0) 
			continue;
		$image_data = $sysInsImage->get_thumb_info('content', 'doc', $doc['id'], $doc['logo_id'], $logo_x, $logo_y, $logo_jpegqual, $logo_method);
		$sub_docs_list["sub_docs_list"][$k] = $sub_docs_list["sub_docs_list"][$k] + $image_data;
	}
	$sysTpl->assign('sub_docs_list', $sub_docs_list["sub_docs_list"]);
	$sysTpl->assign('page_total' ,$sub_docs_list["page_total"]);
	$sysTpl->assign('page_current', $sub_docs_list["page_current"]);
	$sysTpl->assign('doc_perpage', $sub_docs_list["doc_perpage"]);
	$sysTpl->assign('doc_total', $sub_docs_list["doc_total"]);
	$sysTpl->assign('subdoc_orderby', $sub_docs_list["subdoc_orderby"]);
	$sysTpl->assign('subdoc_order_asc', $sub_docs_list["subdoc_order_asc"]);
	$sysTpl->assign('weight_moveup_url', $sub_docs_list["weight_moveup_url"]);
	$sysTpl->assign('weight_movedown_url', $sub_docs_list["weight_movedown_url"]);


	//Вывод результатов
	$result['object'] =& $sysObject;
	$result['browsein'] =& $parents_list;
	$result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject."::$page_current::$subdoc_orderby::$subdoc_order_asc", $parents_list);
	return $result;
}
?>