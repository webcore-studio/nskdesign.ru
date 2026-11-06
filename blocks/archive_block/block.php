<?php

function archive_block_display($blockinfo) {
  
	if (!sysModAvailable ('shop'))
		return true;

	//Строим имя
	$sysObject = 'archive_block::display::'.$blockinfo['id'];

 	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) 
		return true;

	$sysModTpl = sysTplWay ($sysObject, $blockinfo['block_tpl']);
	$sysTpl = new sysTpl;
	$sysTpl->cache_lifetime = $block_ttl*60;
	$sysTpl->caching = 0;


	$dir = '/files/a';
	$dirf = $_SERVER['DOCUMENT_ROOT'].$dir;
	$handle  = @opendir($dirf);
	$files = array();
    while($file = @readdir($handle)) {
		if (is_file("$dirf/$file") && filesize("$dirf/$file") > 0) {
			$files[$file] = $dir."/".$file;
		}
	}

	// список разделов, сопоставляем
	$cat_table = sysDBGetTable('shop_cat');
	$cat_column =  sysDBGetColumns($cat_table);
	$ids = sysDbSelect($cat_table, $cat_column, "WHERE $cat_column[cat_active] > 0", true);
	
	$file_list = array();
	foreach ($ids as $v) {
		if (isset($files["ocsistyle".$v['id'].".zip"])) {
			$v['filename'] = "ocsistyle".$v['id'].".zip";
			$file_list[] = $v;
		}
			
	}
	
	//Назначаем переменные 
	$sysTpl->assign('dir', $dir);
	$sysTpl->assign('file_list', $file_list);
	
	//Информацию о блоке
	$sysTpl->assign('blockinfo', $blockinfo);

	//The End
	$result['block_content'] = $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}


?>
