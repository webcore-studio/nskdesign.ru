<?php

function xcontent_block_display($blockinfo)  
{
  
	if (!sysModAvailable ('content'))
		return true;

	//Забираем переменные блока
	$folder_id = unserialize(sysBlockGetVar ($blockinfo['id'], 'folder_id'));
	$block_ttl = sysBlockGetVar ($blockinfo['id'], 'block_ttl');

	if (!is_array($folder_id) && is_numeric($folder_id))
		$folder_id = array( 0 => $folder_id );
	if (!is_array($folder_id) && !is_numeric($folder_id))
		$folder_id = array( 0 => 0 );

	//Строим имя и опредиляем если нужно текущий раздел
	if (!in_array(-1, $folder_id))    {
		$sysObject = 'xcontent_block::display::'.$blockinfo['id'];
    }
    else  {
		//Определяем текущий раздел
		list($module, $func, $type) = sysVarCleanFromInput('module', 'func', 'type');
		
		//Проверка эт content ?
		if ($module != 'content')        {
			return true;
        }
        elseif ($func == 'folder_view') {
			$fid = sysVarCleanFromInput('id');
			if (!$fid) {
				$fid = $content-> get_fid (sysVarCleanFromInput('name'));
			}
			$folder_id[] = $fid;
			$sysObject = 'xcontent_block::display::'.$blockinfo['id'].'::'.sysVarCleanFromInput('id');
        }
		elseif ($func == 'doc_view') {
            $cur_id = sysVarCleanFromInput('id');
			if (!$cur_id)
				$cur_id =  $content-> get_did (sysVarCleanFromInput('dname'));
			
			$fid = sysVarCleanFromInput('fid');
			if (!$fid) {
				$fid = $content-> get_fid (sysVarCleanFromInput('fname'));
			}
            $folder_id[] = $fid;
            $sysObject = 'xcontent_block::display::'.$blockinfo['id'].'::'.sysVarCleanFromInput('fid');
        }
		
        elseif ($func == 'subdoc_view') {
            $folder_id[] = sysVarCleanFromInput('fid');
            $sysObject = 'xcontent_block::display::'.$blockinfo['id'].'::'.sysVarCleanFromInput('fid');
        }
        elseif (($func == 'main') && ($type == 'user')) {
            $folder_id = array(0 => 0);
            $sysObject = 'xcontent_block::display::'.$blockinfo['id'].'::0';
        }
        else   {
            return true;
        }
    }

    if (!sysSecAuthAction($sysObject, ACCESS_READ)) return true;

	$sysModTpl = sysTplWay ($sysObject, $blockinfo['block_tpl']);
	$sysTpl = new sysTpl;
	$sysTpl->cache_lifetime = $block_ttl*60;

	if($sysTpl->is_cached($sysModTpl, $sysObject))   {
		//Возвращаем результат
		$result['block_content'] = $sysTpl->fetch($sysModTpl,$sysObject);
		return $result;
    }

	//переменные блока
	$doc_count = sysBlockGetVar ($blockinfo['id'], 'doc_count');
	//$include_subfolders = sysBlockGetVar ($blockinfo['id'], 'include_subfolders');
	$doc_orderby = sysBlockGetVar ($blockinfo['id'], 'doc_orderby');
	$doc_order_asc = sysBlockGetVar ($blockinfo['id'], 'doc_order_asc');
	if (!is_numeric($doc_count))
		$doc_count = 10;
	if (empty($doc_orderby)) {
		$doc_orderby = 'pub_datetime';
		$doc_order_asc =  0;
	}

	//Берем вложенные документы
	$docs_table = sysDBGetTable('content_docs');
	$docs_column =  sysDBGetColumns($docs_table);
	$links_table = sysDBGetTable('content_links');
	$links_column =  sysDBGetColumns($links_table);
	$folders_table = sysDBGetTable('content_folders');
	$folders_column =  sysDBGetColumns($folders_table);

	//Учитываем  limit_doc_olderdays  limit_doc_perfolder
	$now = date("Y-m-d H:i:00");
	//документы за последние полгода если надо
	//$tp = time()-6*30*24*60*60;
	//$tp = date("Y-m-d H:i:00",$tp);
	$tp = 0;

	//Сортировать по возрастанию / убыванию
	if ($doc_order_asc) $order = ' ASC';
    else $order = ' DESC';
	
	$where = array();
	
	//ограничения по разделам
	if (count($folder_id)>0 && !in_array(0 ,$folder_id)) {
		$wherelinks = array();
		foreach ($folder_id as $f)
			$wherelinks[] = "$links_column[parent_id] = $f ";
		$where[] = "(".implode(" OR ",$wherelinks).")";
	}

	//sysModClassLoad ('content');
    //$content = new content;
	
	$where[] = " $docs_column[pub_datetime] < '$now' ";
	$where[] = " $docs_column[pub_datetime] > '$tp' ";
	$where[] = " $links_column[active] = 1 ";

	if ($doc_orderby == 'misc_varchar_a') {
		$where[] = " $docs_column[misc_varchar_a] = 1 ";
		$doc_orderby = $docs_column['pub_datetime'];
	}
	
	//если функция просмотра документа, то не текущий документ
	if ($cur_id > 0) 
		$where[] = "($docs_column[id]!=$cur_id)";
	
	$where = implode(" AND ",$where);
		
	//выводим документы без повторений
	$where .= " GROUP BY $docs_column[id] ";
 
	//сортировка
	$where .= " ORDER BY  $doc_orderby $order ";

	//количество
	$where .= " LIMIT 0, $doc_count ";
		
	$mtm = explode(' ',microtime());
	$starttime = $mtm[1] + $mtm[0];

	$sql = "
		SELECT $docs_table.*, $folders_column[displayname] as folder_displayname, $folders_column[name] as folder_name, $links_column[parent_id] as fid
		FROM $docs_table, $links_table, $folders_table
		WHERE $docs_column[id] = $links_column[child_id] AND $links_column[parent_id] = $folders_column[id] AND $where ";
  
	$sql_result = mysql_query($sql);
	if ($sql_result) {    
		for ($data = array(); $row = mysql_fetch_assoc($sql_result); $data[] = $row);
			$docs_list = $data;
	}

	//Логируем выборку
	$mtm = explode(' ',microtime());
	$endtime = $mtm[1] + $mtm[0];
	$totaltime = ($endtime - $starttime);
	
	global $dbg_sql_queries;
	$dbg_sql_queries[] = array('sql_query' => $sql, 'totaltime' => $totaltime);
	

	//Если нет документов, блок невыводим вообще
	if (empty ($docs_list))
		return true;


	//ресайз логотипов
	$logo_width = sysBlockGetVar ($blockinfo['id'], 'logo_width');
	$logo_height = sysBlockGetVar ($blockinfo['id'], 'logo_height');
	$logo_qual = sysBlockGetVar ($blockinfo['id'], 'logo_qual');
	$logo_method = sysBlockGetVar ($blockinfo['id'], 'logo_method');

	sysExtLibLoad('imageinslib');
	$sysInsImage = new sysInsImage();

 
	foreach ($docs_list as $k => $doc)
	{
		if (!$doc['folder_name']) $docs_list[$k]['folder_name'] = $doc['fid'];
		if (!$doc['name']) $docs_list[$k]['name'] = $doc['id'];
		if ($doc['logo_id'] <= 0) continue;
		
		$image_data = $sysInsImage -> get_thumb_info($module = 'content', $type = 'doc', $doc['id'], $doc['logo_id'], $logo_width, $logo_height, $logo_qual, $logo_method);
		$docs_list[$k] = $docs_list[$k] + $image_data;
	}

	//получаем список раздело
	sysModClassLoad ('content');
	$content = new content;
	$folder_list = array();
	foreach ($folder_id as $f) {
		$folder_list[$f] =$content->folder_get_info ($f);
	}

	

	//С документами все Загоняем в шаблон
	$sysTpl->assign('docs_list', $docs_list);
	$sysTpl->assign('folder_id', $folder_id);
	$sysTpl->assign('folder_list', $folder_list);
	//Назначяем переменные (настройки блока)
	$sysTpl->assign('doc_count', $doc_count);
	$sysTpl->assign('doc_orderby', $doc_orderby);
	$sysTpl->assign('doc_order_asc', $doc_order_asc);
	$sysTpl->assign('images_list_tag', '{images}');

	//Информацию о блоке
	$sysTpl->assign('blockinfo', $blockinfo);

	//The End
	$result['block_content'] = $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}


?>