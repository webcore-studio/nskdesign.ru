<?php
error_reporting(0); // Set E_ALL for debuging

include_once './modules/fm/includes/elFinderConnector.class.php';
include_once './modules/fm/includes/elFinder.class.php';
include_once './modules/fm/includes/elFinderVolumeDriver.class.php';
include_once './modules/fm/includes/elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
// Required for FTP connector support
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';

/*
* динамическая загрузка
*/
function fm_ajax_upload()
{
	//Прелюдие как у всех модулей
	$sysObject = 'fm::ajax::upload';
	
	$modname = sysVarCleanFromInput("modname");
	$item_id = sysVarCleanFromInput("item_id");
	
	
	if ($modname == "content")
		$item_name = "doc";
		
	if ($modname == "shop")
		$item_name = "product";
		
	
	if(!empty($_FILES['file']['name'][0])) {
		$uploaded = 0;
		sysExtLibLoad('imageinslib');
		$sysInsImage = new sysInsImage();
		
		$image_x = sysModGetVar($modname, 'thumb_width');
		$image_y = sysModGetVar($modname, 'thumb_height');
		$image_jpegqual = sysModGetVar($modname, 'thumb_qual');
		$image_method = sysModGetVar($modname, 'thumb_method');
		
		foreach($_FILES['file']['name'] as $k=>$item) {
			$file = array(
					"name" => $item,
					"type" => $_FILES['file']['type'][$k],
					"tmp_name" => $_FILES['file']['tmp_name'][$k],
					"error" => $_FILES['file']['error'][$k],
					"size" => $_FILES['file']['size'][$k]);
					
		
			$image_indata = array();
			$image_indata['image_file'] = $file;
			$image_indata[$item_name."_id"] = $item_id;
			$res = $sysInsImage->image_create($image_indata, $modname, $item_name);
			if (!$res) { 
				continue;
			}
			$uploaded++;
			//делаем тумб и возвращаем
			$image_data = $sysInsImage->get_thumb_info($modname, $item_name, $item_id, $res, $image_x, $image_y, $image_jpegqual, $image_method);
			echo "<img src='".$image_data['thumb_url']."'>&nbsp;";

		}
	}

	if (!$uploaded)
		echo 0;
	exit;
}


/*
* Выбор рисунка
*/
function fm_ajax_select_image()
{
	//Прелюдие как у всех модулей
	$sysObject = 'fm::ajax::select::image';
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;
  
	//Шаблоны
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject);
	$sysTpl->caching = false;
  
	$result['object'] = $sysObject;
	$result['browsein'] = $browsein;
	$result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

function fm_ajax_tinymce()
{
	//Прелюдие как у всех модулей
	$sysObject = 'fm::ajax::tinymce';
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;
 
	//Шаблоны
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject);
	$sysTpl->caching = false;
   
	//Возвращаем результат
	$result['object'] = $sysObject;
	$result['browsein'] = $browsein;
	$result['content'] = $sysTpl->fetch($sysModTpl,$sysObject);
	return $result;
}

function fm_ajax_connector()
{
	//Прелюдие как у всех модулей
	$sysObject = 'fm::ajax::connector';
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;
	$uploadDeny = unserialize(sysModGetVar('fm', 'uploadDeny'));
	$uploadAllow = unserialize(sysModGetVar('fm', 'uploadAllow'));
	$tmbSize =  sysModGetVar('fm', 'tmbSize');
	$uploadMaxSize = sysModGetVar('fm', 'uploadMaxSize');
	
	$opts = array(
		// 'debug' => true,
		'roots' => array(
			array(
				'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
				'path'          => 'files/shared/',         // path to files (REQUIRED)
				'URL'           => dirname($_SERVER['PHP_SELF']) . 'files/shared/', // URL to files (REQUIRED)
				'accessControl' => 'access',             // disable and hide dot starting files (OPTIONAL)
				'uploadDeny' => $uploadDeny,
				'uploadAllow' => $uploadAllow,
				'tmbSize' => $tmbSize,
				'uploadMaxSize' => $uploadMaxSize*1024
			)
		)
	);
	// run elFinder
	$connector = new elFinderConnector(new elFinder($opts));
	$connector->run();
	exit;

}

/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
		:  null;                                    // else elFinder decide it itself
}

?>