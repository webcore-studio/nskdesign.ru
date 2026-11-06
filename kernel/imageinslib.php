<?php
require_once 'imagelib.php';
class sysInsImage extends sysImage {
  
	var $tmpdir = 'var/tmp/';
	var $imagedir = 'files/images/';
	
	function get_thumb_info($module = 'shop', $type = 'product', $item_id, $image_id, $dest_x = 200, $dest_y = 200, $jpegqual = 100, $how = 'xy', $replace = null) 
	{
	
		$image_data = $this->image_get_info ($image_id, $module, $type);

		$dest_type = $image_data['image_type'];
		
		if (!$dest_type) 
			$dest_type = "jpg";
			
     
		$thumb_cacheid = $this->image_get_thumb_cacheid($module,$type,$item_id, $image_id, $dest_x, $dest_y, $dest_type);
		$thumb_url = $this->get_thumb_url($thumb_cacheid);
		
		if ($replace === NULL)
			$replace = sysModGetVar('SYS_config', 'thumb_replace');

		if ($thumb_url && !$replace)  { //тумб есть и заменять не надо
			$result['thumb_url'] = $thumb_url;
        }
        else    { //Создаем
			$changequal	= ($jpegqual < 100 );
			if (!$this->image_make_thumb($module, $type, $image_id, $dest_x, $dest_y, $jpegqual, $changequal, $dest_type, $how)) {
				sys_log('sysInsImage->get_thumb_info',LOG_ERR,'Cant make thumb '.$thumb_cacheid);
				return array();
			}
			$thumb_url = $this->get_thumb_url($thumb_cacheid);
			$result['thumb_url'] = $thumb_url;
        }

		// Теперь подгружаем инфу о настоешем размере файла
		$image_file = $this->cacheid2diskway($thumb_cacheid);
		if (file_exists($image_file)) {
			$disk_image_info = @getimagesize ($image_file);
			$result['image_w'] = $disk_image_info[0];
			$result['image_h'] = $disk_image_info[1];
        }

		return $result;
	} 
	
	
	function get_thumbs_info($module = 'shop', $type = 'product', $item_list, $dest_x = 200, $dest_y = 200, $jpegqual = 100, $how = 'xy', $replace = null) 
	{
		
		
		$images_data = $this->images_get_info ($item_list, $module, $type);
		
		
		foreach ($images_data as $image_data) {
			$result = array();

			$dest_type = $image_data['image_type'];
			
			if (!$dest_type) 
				$dest_type = "jpg";
				
	 
			$thumb_cacheid = $this->image_get_thumb_cacheid($module, $type, $image_data[$type."_id"], $image_data['id'], $dest_x, $dest_y, $dest_type);
			$thumb_url = $this->get_thumb_url($thumb_cacheid);
			
			
			
			if ($replace === NULL)
				$replace = sysModGetVar('SYS_config', 'thumb_replace');

			if ($thumb_url && !$replace)  { //тумб есть и заменять не надо
				$result['thumb_url'] = $thumb_url;
			}
			else    { //Создаем
				$changequal	= ($jpegqual < 100 ) ? 1 : 0;
				if (!$this->image_make_thumb($module, $type, $image_data['id'], $dest_x, $dest_y, $jpegqual, $changequal, $dest_type, $how)) {
					sys_log('sysInsImage->get_thumb_info',LOG_ERR,'Cant make thumb '.$thumb_cacheid);
					continue;
				}
				$thumb_url = $this->get_thumb_url($thumb_cacheid);
				$result['thumb_url'] = $thumb_url;
			}
			
			// Теперь подгружаем инфу о настоешем размере файла
			$image_file = $this->cacheid2diskway($thumb_cacheid);
			if (file_exists($image_file)) {
				$disk_image_info = @getimagesize ($image_file);
				$result['image_w'] = $disk_image_info[0];
				$result['image_h'] = $disk_image_info[1];
			}
			foreach ($item_list as &$item) {
				if ($item['logo_id'] == $image_data['id']) {
					$item['image_w'] = $result['image_w'];
					$item['image_h'] = $result['image_h'];
					$item['thumb_url'] = $result['thumb_url'];
				}
			}
			
		}
				
		
		
		return $item_list;
		
	} 
	

	function image_get_thumb_cacheid($module, $type, $product_id, $image_id=null, $img_w, $img_y,$dest_type)     {
		if ($image_id)
    		$thumb_cacheid = $module.'::'.$type.'::'.$product_id.'::'.$image_id.'::'.$img_w.'x'.$img_y.'.'.$dest_type;
		else 
    		$thumb_cacheid = $module.'::'.$type.'::'.$product_id.'::'.$img_w.'x'.$img_y.'.'.$dest_type;
		return $thumb_cacheid;
    }


	function image_make_thumb ($module='shop', $type = 'product', $image_id, $dest_x = 200, $dest_y = 200, $jpegqual = 100, $changequal = false, $dest_type, $how) 
	{
		
		//Инфа из базы о рисунке
		$images_table = sysDBGetTable($module.'_images');
		$images_column =  sysDBGetColumns($images_table);
		unset($images_column['image_file']);
		$image = sysDbSelect ($images_table, $images_column, "WHERE $images_column[id] = $image_id");
		
		if (empty($image))   {
			sys_log('sysInsImage->image_make_thumb',LOG_ERR,'Image doesnt exists'.$image_id.'( '.$module.')');
			return;
		}
  
		if (!$dest_type)
			$dest_type = $image['image_type'];
			
		if (!$type) {
			if ($module == 'shop') 
				$type = 'product'; 
			elseif ($module == 'realty') 
			$type = 'position'; 

			else 
				$type='doc';
		}
			
			
		$image_path = $this->imagedir.$module."/".$type."/".$image_id.".".$dest_type;
		if (!$handle = fopen($image_path, 'r')) {
			sys_log('sysInsImage->image_make_thumb',LOG_ERR, 'Cannot open temp file - '.$image_path);
			return false; 
		}
		
		
		$thumb_cacheid = $this->image_get_thumb_cacheid($module, $type, $image[$type.'_id'], $image['id'], $dest_x, $dest_y, $dest_type);
		$result = $this->make_thumb($image_path, $thumb_cacheid, $dest_x, $dest_y, $jpegqual, $changequal, $how, $dest_type);
  
		return $result;
		
		
    }
	
	function images_make_thumb ($module='shop', $type = 'product', $ids, $dest_x = 200, $dest_y = 200, $jpegqual = 100, $changequal = false, $dest_type, $how) 
	{
		if (empty($ids))
			return false;
    
		//Инфа из базы о рисунке
		$images_table = sysDBGetTable($module.'_images');
		$images_column =  sysDBGetColumns($images_table);
		unset($images_column['image_file']);
		
		
		$images = sysDbSelect ($images_table, $images_column, "WHERE $images_column[id] IN (".implode(",", $ids).")", true);
        
		
		if (empty($images))   {
			sys_log('sysInsImage->images_make_thumb', LOG_ERR, "Images doesnt exists $module");
			return;
		}
  
		if (!$dest_type)
			$dest_type = $image['image_type'];
			
			if (!$type) {
				if ($module == 'shop') 
					$type = 'product'; 
				elseif ($module == 'realty') 
				$type = 'position'; 
	
				else 
					$type='doc';
			}
			
		$result = array();
		foreach ($images as $image) {
			$image_path = $this->imagedir.$module."/".$type."/".$image['id'].".".$dest_type;
			if (!$handle = fopen($image_path, 'r')) {
				sys_log('sysInsImage->image_make_thumb',LOG_ERR, 'Cannot open temp file - '.$image_path);
				return false; 
			}
			
			
			$thumb_cacheid = $this->image_get_thumb_cacheid($module, $type, $image[$type.'_id'], $image['id'], $dest_x, $dest_y, $dest_type);
			$result[] = $this->make_thumb($image_path, $thumb_cacheid, $dest_x, $dest_y, $jpegqual, $changequal, $how);
		}
  
		return $result;
		
		
    }
	
	
	function image_create ($image_indata, $modname, $item_type)
	{
		if ($modname!='content' && $modname!='shop' && $modname!='catalog' && $modname!='realty' && $modname!='travel')
			return false;
			
		//Инфа о нашей таблице
		$images_table = sysDBGetTable($modname.'_images');
		$images_column =  sysDBGetColumns($images_table);
		
		$item_id = $item_type."_id";

		//Находим и задаем самый большой вес
		$MaxWeight = sysDbMaxWeight ($images_table, "WHERE $images_column[$item_id] = '$image_indata[$item_id]'");
		$MaxWeight++;
		$image_indata['weight'] = $MaxWeight;

		// Проверка файла на тип
		$disk_image_info = getimagesize ($image_indata['image_file']['tmp_name']);
		if (($disk_image_info['mime']!='image/gif')  and ($disk_image_info['mime']!='image/jpeg') and ($disk_image_info['mime']!='image/png'))		{
			sys_log ('sysInsImage->image_create', LOG_WARNING, 'Тип файла не image/jpeg, gif', $disk_image_info);
			return false;
		}
  
		$image_indata['image_width'] = $disk_image_info[0];
		$image_indata['image_height'] = $disk_image_info[1];
		$image_indata['image_size'] = filesize($image_indata['image_file']['tmp_name']);
		$image_indata['image_time'] = time();
		
		switch($disk_image_info[2])      {
			case 1: $image_type = "gif"; $dest_type = IMG_GIF; break;
			case 2: $image_type = "jpg"; $dest_type = IMG_JPG; break;
			case 3: $image_type = "png"; $dest_type = IMG_PNG; break;
			case 4: $image_type = "bmp"; $dest_type = IMG_WBMP; break;
			default: sys_log ('sysInsImage->image_create', LOG_WARNING, 'Тип файла не image', $disk_image_info);  return false;  break; 
		}
			
		$image_indata['image_type'] = $image_type;

		
		
		
		$image_file = $image_indata['image_file'];
		unset($image_indata['image_file']);
		
		sys_log ('sysInsImage->image_create', LOG_WARNING, ' image', $image_indata);

		sys_log ('sysInsImage->image_create', LOG_WARNING, ' images_table', $images_table);
	
		//Вставили в базу инфу о фотографии товара
		$insert_id = sysDbInsert ($images_table, $image_indata);
		
		$uploaddir = $_SERVER['DOCUMENT_ROOT']."/files/images/$modname/$item_type/".$insert_id.".".$image_type;
		move_uploaded_file($image_file['tmp_name'], $uploaddir);

		

		return $insert_id;
		//return true;
	}
	
	function image_update ($image_indata, $modname, $item_type)
	{
		if ($modname!='content' && $modname!='shop' && $modname!='catalog' && $modname!='travel')
			return false;
	
		//Инфа о нашей таблице
		$images_table = sysDBGetTable($modname.'_images');
		$images_column =  sysDBGetColumns($images_table);
		
		$item_id = $item_type."_id";
		
		//Инфа о фотографие
		$image_dbdata = $this->image_get_info($image_indata['id'], $modname);
		
		
		// Зашита от случайного редактирования
		//unset ($image_indata['weight']);
		
		//Подготовка фотки к хранению
		if (!empty ($image_indata['image_file']['tmp_name']))      {
			
			$image_file = $image_indata['image_file'];
      
			// Проверка файла на тип
			$disk_image_info = getimagesize ($image_file['tmp_name']);
			if (($disk_image_info['mime']!='image/gif')  and ($disk_image_info['mime']!='image/jpeg')  and ($disk_image_info['mime']!='image/png'))    {
				sys_log ('sysInsImage->image_create', LOG_WARNING, 'Тип файла не image/jpeg, gif, png', $disk_image_info);
				return false;
      
			}


		
			$image_indata['image_width'] = $disk_image_info[0];
			$image_indata['image_height'] = $disk_image_info[1];
			$image_indata['image_size'] = filesize($image_indata['image_file']['tmp_name']);
			$image_indata['image_time'] = time();
			
			switch($disk_image_info[2])      {
				case 1: $image_type = "gif"; $dest_type = IMG_GIF; break;
				case 2: $image_type = "jpg"; $dest_type = IMG_JPG; break;
				case 3: $image_type = "png"; $dest_type = IMG_PNG; break;
				case 4: $image_type = "bmp"; $dest_type = IMG_WBMP; break;
				default: sys_log ('sysInsImage->image_create', LOG_WARNING, 'Тип файла не image', $disk_image_info);  
				return false;  break; 
			}
			
			$image_indata['image_type'] = $image_type;
			
			$uploaddir = $_SERVER['DOCUMENT_ROOT']."/files/images/$modname/$item_type/".$image_indata['id'].".".$image_type;
			$res = unlink($uploaddir);
			$moved = move_uploaded_file($image_file['tmp_name'], $uploaddir);
		}
		unset($image_indata['image_file']);
		


		//Обновление в базе
		sysDbUpdate ($images_table, $image_indata, "WHERE $images_column[id] = '$image_indata[id]'");

		//Чистим кеш тумбов
		$this->clear_cache("$modname::$item_type::".$image_dbdata[$item_id].'::'.$image_dbdata['id']);

		return true;

	}
	
	function image_get_info ($id, $modname, $image_type = null)
    {
		//Необходимые таблицы
		$images_table = sysDBGetTable($modname.'_images');
		$images_column = sysDBGetColumns($images_table);
		unset ($images_column['image_file']);

		$where = "WHERE $images_column[id] = '$id' ";
		if ($image_type && isset($images_column['parent_type']))
			$where .= " AND $images_column[parent_type] = '$image_type'";
		$image_dbdata = sysDbSelect ($images_table, $images_column, $where);
		return $image_dbdata;
    }
	
	
	function images_get_info ($ids, $modname, $image_type = null)
    {
		//Необходимые таблицы
		$images_table = sysDBGetTable($modname.'_images');
		$images_column = sysDBGetColumns($images_table);
		unset ($images_column['image_file']);
		
		$ids_list = "0";
		foreach ($ids as $item)
			if ($item['logo_id']>0)
				$ids_list .= ", $item[logo_id]";

		$where = "WHERE $images_column[id] in ($ids_list) ";
		if ($image_type && isset($images_column['parent_type']))
			$where .= " AND $images_column[parent_type] = '$image_type'";
		$images_list = sysDbSelect ($images_table, $images_column, $where, true);
		return $images_list;
    }
	
	
	function image_delete ($id, $modname, $item_type)
	{
		if ($modname!='content' && $modname!='shop' && $modname!='catalog' && $modname!='travel')
			return false;
			
		//Инфа о нашей таблице
		$images_table = sysDBGetTable($modname.'_images');
		$images_column =  sysDBGetColumns($images_table);
		
		$item_id = $item_type."_id";
		
		//Инфа о фотографие
		$image_dbdata = $this->image_get_info($id, $modname);
		
		//echo $image_dbdata[$item_id]; exit;
		//Удаление
		sysDbDelete ($images_table, "WHERE $images_column[id]=$id");
		
		sysExtLibLoad();
		sysDbWeightDelete ($images_table, $image_dbdata['weight'],  "WHERE $images_column[$item_id] = $image_dbdata[$item_id]");
		
	
		//удаление файла
		$uploaddir = $_SERVER['DOCUMENT_ROOT']."/files/images/$modname/$item_type/".$id.".".$image_dbdata['image_type'];
		unlink($uploaddir);

		//Чистим кеш тумбов
		$this->clear_cache("$modname::$item_type::".$image_dbdata[$item_id].'::'.$image_dbdata['id']);

		return true;
		
	}



}
?>