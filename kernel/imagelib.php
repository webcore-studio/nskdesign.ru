<?php
/*
 * Класс работы с изображениями
*/
class sysImage  {
	//Конфиг
	var $cachedir = 'files/thumbs/';
	var $thumb_url_prefix = '/files/thumbs/';
    
	/**
	* @return bool
	* @param $sourcefile string
	* @param $targetfile string
	* @param $dest_x int
	* @param $dest_y int
	* @param $jpegqual int
	* @param $changequal bool
	* @param $dest_type string
	* @desc Вписывание рисунка и масштабирование
	*/
	function resizeToFile ($sourcefile, $targetfile, $dest_x = 120, $dest_y = 120, $jpegqual = 80, $changequal = false, $dest_type = null, $how = "xy")    
	{
    
		// Проверка наличия модуля GD
		$testGD = get_extension_funcs("gd"); // Grab function list
		if (!$testGD) {
			sys_log('sysImage->resizeToFile', LOG_ERR, 'GD not even installed ');
			return;
		}
    
		if (in_array ("imagegd2",$testGD)) {// Проверка версии
			$gd_version = ">2";
		}
  
		//Опредиляем размер и тип рисунка
		$imginfo = getimagesize($sourcefile);
		$source_x  = $imginfo[0];
		$source_y  = $imginfo[1];
		
		switch($imginfo[2])      {
			case 1: $type = IMG_GIF; break;
			case 2: $type = IMG_JPG; break;
			case 3: $type = IMG_PNG; break;
			case 4: $type = IMG_WBMP; break;
			default: sys_log('sysImage->resizeToFile', LOG_ERR, 'Unknow file type - '.$imginfo[2]); return;  break; //Исключительная ситуация неизвестный тип
		}
		//Читаем файл
		switch($type)  {
		
			case IMG_GIF:
				if (!function_exists('imagecreatefromgif')) {
					sys_log('sysImage->resizeToFile', LOG_ERR, 'Function not exists imagecreatefromgif'); 
					return; 
				}
				$srcImage = @imagecreatefromgif($sourcefile);
				break;
      
			case IMG_JPG:
				if (!function_exists('imagecreatefromjpeg')){
					sys_log('sysImage->resizeToFile', LOG_ERR, 'Function not exists imagecreatefromjpeg'); 
					return; 
				}
				$srcImage = @ImageCreateFromJpeg($sourcefile);
				break;
      
			case IMG_PNG:
				if (!function_exists('imagecreatefrompng')) {
					sys_log('sysImage->resizeToFile', LOG_ERR, 'Function not exists imagecreatefrompng'); 
					return false;
				}
				$srcImage = @imagecreatefrompng($sourcefile);
				break;
				
			case IMG_WBMP:
				if (!function_exists('imagecreatefromwbmp')){
					sys_log('sysImage->resizeToFile', LOG_ERR, 'Function not exists imagecreatefrombmp'); 
					return false; 
				}
				$srcImage = @imagecreatefromwbmp($sourcefile);
				break;
  
			default: 
				sys_log('sysImage->resizeToFile', LOG_ERR, 'Unknow file type - '.$type); 
				return; 
		}
		

		//Расчитываем коэфициенты масштабирования
		if ($dest_x > 0) {
			$ratio_x = $source_x / $dest_x;
		}
    
		if ($dest_y > 0) {
			$ratio_y = $source_y / $dest_y;
		}
		
		switch($how)  {
		
			// Если пережимаем по большему параметру
			case 'xy':
			
				// Нам нужен больший коэфициент для того чтобы вписать
				if ($ratio_x > $ratio_y) {
					$ratio = $ratio_x;
					$dest_y = $source_y / $ratio;
				}
				else  {
					$ratio = $ratio_y;
					$dest_x = $source_x / $ratio;
				}
				break;
			
			
			// Если пережимаем по ширине
			case 'x':
				$ratio = $ratio_x;
				$dest_y = $source_y / $ratio;
			
			break;
				
			// Если пережимаем по высоте
			case 'y':
				$ratio = $ratio_y;
				$dest_x = $source_x / $ratio;
				break;
	
			// если делаем точный размер
			case 'ex':
			
				if ($ratio_x >= 1)
					$dest_x_fin = $dest_x;
				else
					$dest_x_fin = $source_x;
					
				if ($ratio_y >= 1)	
					$dest_y_fin = $dest_y;
				else
					$dest_y_fin = $source_y;
					
				
				if ($ratio_x < $ratio_y)  {
					$ratio = $ratio_x;
					$dest_y = $source_y / $ratio;

				}
				else   {
					$ratio = $ratio_y;
					$dest_x = $source_x / $ratio;
				}
				break;
		}

 

  
		if ($ratio <= 1) { 
			if (($changequal &&  $type == IMG_JPG) || $how == 'ex')  { 
				$dest_x = $source_x;
				$dest_y = $source_y;
			}
			else  { //Ничего не делаем
				return (copy ($sourcefile, $targetfile));
			}          
		}
	  

		/*
		* Собственно масштабирование при необходимости
		*/
		if (1) {
			if ($gd_version == '>2')   {
			
				$destImage1 = @imagecreatetruecolor($dest_x, $dest_y);
				
				if ($type == IMG_PNG) 	{
					//Отключаем режим сопряжения цветов
					imageAlphaBlending($destImage1, false);
					//Включаем сохранение альфа канала
					imageSaveAlpha($destImage1, true);
				}
				
				if ($type == IMG_GIF) 	{
					$transparent_source_index = imagecolortransparent($srcImage);
					//Проверяем наличие прозрачности
					if($transparent_source_index !== -1){
						$transparent_color = imagecolorsforindex($srcImage, $transparent_source_index);
					 
						//Добавляем цвет в палитру нового изображения, и устанавливаем его как прозрачный
						$transparent_destination_index = imagecolorallocate($destImage1, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
						imagecolortransparent($destImage1, $transparent_destination_index);

						//На всякий случай заливаем фон этим цветом
						imagefill($destImage1, 0, 0, $transparent_destination_index);
					}

				}
				
				imagecopyresampled($destImage1, $srcImage, 0, 0, 0, 0, $dest_x, $dest_y, $source_x, $source_y);
			}
			else  {
				$destImage1 = @imagecreate($dest_x, $dest_y);
				imagecopyresized ($destImage1, $srcImage, 0, 0, 0, 0, $dest_x, $dest_y, $source_x, $source_y);
			}
		}
		
		//а теперь обрезаем, если надо
		if ($how == 'ex') {
			if ($gd_version == '>2')      
				$destImage = @imagecreatetruecolor($dest_x_fin, $dest_y_fin);
			else     
				$destImage = @imagecreate($dest_x_fin, $dest_y_fin);

			//теперь рассчитываем, где центральная часть, откуда вырезать
			$x_start = ($dest_x - $dest_x_fin) / 2;
			$y_start = ($dest_y - $dest_y_fin) / 2;

			@imagecopy($destImage, $destImage1, 0, 0, $x_start, $y_start, $dest_x_fin, $dest_y_fin);

		}
		else 
			$destImage = $destImage1;
  
		
		// Создание и сохранение выходного рисунка
		//Если файл назначения существует, удаляем
		if (file_exists($targetfile))  {
			unlink ($targetfile);
      }

		if ($dest_type) {//тип выходного файла
			$type = $dest_type;
		}
		
		switch($type)  {
			case IMG_GIF: imagegif($destImage, $targetfile); break;
			case IMG_JPG: imagejpeg($destImage, $targetfile, $jpegqual); break;
			case IMG_PNG: imagepng($destImage, $targetfile); break;
			case IMG_WBMP: imagewbmp($destImage, $targetfile); break;
      }

		// Очистка памяти
		imagedestroy($srcImage);
		imagedestroy($destImage);

		return true;
   }
    
	/**
	* @return bool
	* @param $sourcefile string
	* @param $thumb_cacheid string
	* @param $dest_x int
	* @param $dest_y int
	* @param $jpegqual int
	* @param $changequal bool
	* @desc Создает thumb
	*/
	function make_thumb ($sourcefile, $thumb_cacheid, $dest_x = 120, $dest_y = 120, $jpegqual = 100, $changequal = false, $how = 'xy', $dest_type)   
	{
   
		//Нашли имя файла куда сохраняем
		$targetfile = $this->cacheid2diskway($thumb_cacheid);

  
		//Нашли имя директории куда сохраняем
		$targetdir =  dirname($targetfile);

		// Права по умолчанию на файлы и директории
		global $sysConfig;
		$dir_perms = $sysConfig['default.dir.perms'];
		$file_perms = $sysConfig['default.file.perms'];
    
		//Создаем директорию
		if (!$this->_mkdir ($targetdir, $dir_perms)) {
			sys_log('sysImage->make_thumb', LOG_ERR, 'Cannot create dir - '.$targetdir); 
			return;
		}
		
   
		//Масштабируем
		switch($dest_type)      {
			case 'gif': $dest_type = IMG_GIF; break;
			case 'jpg': $dest_type = IMG_JPG; break;
			case 'png': $dest_type = IMG_PNG; break;
			case 'bmp': $dest_type = IMG_WBMP; break;
			default: $dest_type = null; break; 
		}
		$this->resizeToFile ($sourcefile, $targetfile, $dest_x, $dest_y, $jpegqual, $changequal, $dest_type, $how);
		
		//если надо - вешаем водяной знак
		$watermark = sysModGetVar('SYS_config','watermark');
		$module_watermark = unserialize(sysModGetVar('SYS_config','module_watermark'));
		
		$modulearr = explode('::', $thumb_cacheid);
		$modname = $modulearr[0];

		if ($watermark && ($module_watermark && in_array($modname, $module_watermark) || !$module_watermark ))	{
			$sourse_water = sysGetSiteUrl().sysModGetVar('SYS_config','watermark_file');
			$info_water  = getimagesize($sourse_water);
			if ($info_water) {
				$water_x  = $info_water[0];
				$water_y  = $info_water[1];
				
				$info_img  = getimagesize($targetfile);
				$img_x  = $info_img[0];
				$img_y  = $info_img[1];
				
				$watermark_pos_x = sysModGetVar('SYS_config','watermark_pos_x');
				$watermark_pos_y = sysModGetVar('SYS_config','watermark_pos_y');
				
				$min_watermark_width = sysModGetVar('SYS_config', 'min_watermark_width');
				$min_watermark_height = sysModGetVar('SYS_config', 'min_watermark_height');
				
				if ($img_x > $min_watermark_width && $img_y > $min_watermark_height)			{
					
					$watermark_percent = sysModGetVar('SYS_config','watermark_percent');
					if ($watermark_percent > 0 && $watermark_percent < 100)
						$img_x = round($img_x*$watermark_percent/100);
					$tmpway = $_SERVER['DOCUMENT_ROOT']."/files/thumbs/watermark/water_".$img_x.".png";
					if (!$this->_mkdir ("/files/thumbs/watermark/", 0771)) {
						sys_log('sysImage->make_thumb', LOG_ERR, 'Cannot create dir - /files/thumbs/watermark/'); 
					}
					
					if (!file_exists($tmpway))
						$this->resizeToFile($sourse_water, $tmpway, $img_x-50, $img_y,100, false, IMG_PNG, 'xy');
					
					//определяем тип исходного рисунка
					$targetimginfo = getimagesize($targetfile);
					switch($targetimginfo[2])      {
						case 1: $type = IMG_GIF; break;
						case 2: $type = IMG_JPG; break;
						case 3: $type = IMG_PNG; break;
						case 4: $type = IMG_WBMP; break;
						default: sys_log('sysImage->resizeToFile', LOG_ERR, 'Unknow file type - '.$imginfo[2]); return;  break; //Исключительная ситуация неизвестный тип
					}
					
					$imgwater = $this->create_watermark($targetfile, $tmpway, 100, false, $watermark_pos_x, $watermark_pos_y, $type);
					
					switch($type)  {
						case IMG_GIF: imagegif($imgwater, $targetfile); break;
						case IMG_JPG: imagejpeg($imgwater, $targetfile, $jpegqual); break;
						case IMG_PNG: imagepng($imgwater, $targetfile); break;
						case IMG_WBMP: imagewbmp($imgwater, $targetfile); break;
						default: imagejpeg($imgwater, $targetfile, $jpegqual); break;
					}
					//imagejpeg($imgwater, $targetfile, $jpegqual);
				}

			}
		}
    
		//Навешиваем права
		chmod($targetfile, $file_perms);
        
		return true;
    }
    
	/**
	* @return string
	* @param $thumb_cacheid string
	* @desc Возврашает урл к тумбу  
	*/
	function get_thumb_url ($thumb_cacheid)   
	{
		$thumb_file = $this->cacheid2diskway($thumb_cacheid);
    	// print_r($thumb_file);exit;
		if (!file_exists($thumb_file)) return; //Тумба - нету
    
		//Формируем урл
		$thumb_file = $this->thumb_url_prefix;
		$thumb_file .= str_replace('::', '/', $thumb_cacheid);
		//$thumb_file .= '.jpg';
		return $thumb_file;
    }
    
	/**
	* @return string
	* @param $thumb_cacheid string
	* @desc Возврашает расположение тумба на диске
	*/
	function cacheid2diskway ($thumb_cacheid)  
	{
		$targetfile = $this->cachedir;
		$targetfile .= str_replace('::', '/', $thumb_cacheid);
		//$targetfile .= '.jpg';
		return $targetfile;
    }
    
	/**
	* @return bool
	* @param $targetdir string
	* @param $dir_perms string
	* @desc Создает директорию - если родительская директория не сушествует то она тоже создаеться
	*/
	function _mkdir ($targetdir, $dir_perms)
    {
		$path = '';
		$result = false;
		$dirs = explode('/', $targetdir);
		foreach ($dirs as $dir)   {
			if ($path) 
				$path.='/'.$dir;
			else
				$path = $dir;
			if (!file_exists($path)) 
				$result = mkdir($path, $dir_perms);
			else 
				$result = true;
		}
		return $result;
    }
    
	/*
	* Рекурсивно удаляет дирректорию и все ее содержимое
	* $dir
	*/
	function _recDelDir($dir)
    {
		$correct = 1;
		$handle  = @opendir($dir);
 
		while($file = @readdir($handle))    {
			if ($file != "." && $file != "..") { //Всякая хрень нам не нужна
				if(is_dir("$dir/$file"))   {
					//Вызываем себя рекурсивно для удалени вложенной папки
					$this->_recDelDir("$dir/$file");
				}
				else {
					if (!@unlink("$dir/$file")) 
						$correct = 0;
				}
			}
		}
		@closedir($handle);
		if(!@rmdir($dir)) $correct = 0;
  
		return $correct;
    }
    
	function clear_cache ($thumb_cacheid = null)
    {
		$diskway = $this->cacheid2diskway($thumb_cacheid);
		// Определяем тип ключа файл или директория
		if (strrpos($thumb_cacheid, '.') && file_exists($diskway))   {
			return (unlink(cacheid2diskway($thumb_cacheid)));
		}
		else { //Дирректория - удаляем рекурсивно
			return ($this->_recDelDir($diskway));
        }
    }
	
	# given two images, return a blended watermarked image

	function create_watermark($main_img_obj, $watermark_img_obj, $alpha_level = 100 ,$vertical = false, $h_position = "c", $v_position = "b", $type) 
	{
	
		switch($type)  {
		
			case IMG_GIF:
				$main_img_obj = @imagecreatefromgif($main_img_obj);
				break;
      
			case IMG_JPG:
				$main_img_obj = @ImageCreateFromJpeg($main_img_obj);
				break;
      
			case IMG_PNG:
				$main_img_obj = @imagecreatefrompng($main_img_obj);
				break;
				
			case IMG_WBMP:
				$main_img_obj = @imagecreatefromwbmp($main_img_obj);
				break;
  
			default: 
				sys_log('sysImage->create_watermark', LOG_ERR, 'Unknow file type - '.$type); 
				return; 
		}
		
		$watermark_img_obj = imagecreatefrompng($watermark_img_obj);
		$alpha_level	/= 100;	# convert 0-100 (%) alpha to decimal

		# calculate our images dimensions
		$main_img_obj_w	= imagesx( $main_img_obj );
		$main_img_obj_h	= imagesy( $main_img_obj );
		$watermark_img_obj_w	= imagesx( $watermark_img_obj );
		$watermark_img_obj_h	= imagesy( $watermark_img_obj );


		# determine center position coordinates
		if ($h_position == "c") {
				$main_img_obj_min_x	= floor( ( $main_img_obj_w / 2 ) - ( $watermark_img_obj_w / 2 ) );
				$main_img_obj_max_x	= ceil( ( $main_img_obj_w / 2 ) + ( $watermark_img_obj_w / 2 ) );
		}
		
		if ($h_position == "l") {
				$main_img_obj_min_x	= 10;
				$main_img_obj_max_x	= $watermark_img_obj_w + 10;
		}
		
		if ($h_position == "r") {
				$main_img_obj_min_x	= $main_img_obj_w - $watermark_img_obj_w - 10;
				$main_img_obj_max_x	= $main_img_obj_w - 10;
		}
		
		if ($v_position == "m") {
				$main_img_obj_min_y	= floor( ( $main_img_obj_h / 2 ) - ( $watermark_img_obj_h / 2 ) );
				$main_img_obj_max_y	= ceil( ( $main_img_obj_h / 2 ) + ( $watermark_img_obj_h / 2 ) ); 
		}
		
		if ($v_position == "b") {
				$main_img_obj_min_y	= floor($main_img_obj_h - $watermark_img_obj_h-10);
				$main_img_obj_max_y	= $main_img_obj_h-10; 
		}
		
		if ($v_position=="t") {
				$main_img_obj_min_y	= 10;
				$main_img_obj_max_y	= $watermark_img_obj_h + 10; 
		}
		# create new image to hold merged changes
		$return_img	= imagecreatetruecolor( $main_img_obj_w, $main_img_obj_h );

		# walk through main image
		for($y = 0; $y < $main_img_obj_h; $y++) {
			for( $x = 0; $x < $main_img_obj_w; $x++ ) {
				$return_color	= NULL;

				# determine the correct pixel location within our watermark
				$watermark_x	= $x - $main_img_obj_min_x;
				$watermark_y	= $y - $main_img_obj_min_y;

				# fetch color information for both of our images
				$main_rgb = imagecolorsforindex( $main_img_obj, imagecolorat( $main_img_obj, $x, $y ) );

				# if our watermark has a non-transparent value at this pixel intersection
				# and we're still within the bounds of the watermark image
				if (	$watermark_x >= 0 && $watermark_x < $watermark_img_obj_w &&
							$watermark_y >= 0 && $watermark_y< $watermark_img_obj_h ) {
					$watermark_rbg = imagecolorsforindex( $watermark_img_obj, imagecolorat( $watermark_img_obj, $watermark_x, $watermark_y ) );

					# using image alpha, and user specified alpha, calculate average
					$watermark_alpha	= round( ( ( 127 - $watermark_rbg['alpha'] ) / 127 ), 2 );
					$watermark_alpha	= $watermark_alpha * $alpha_level;

					# calculate the color 'average' between the two - taking into account the specified alpha level
					$avg_red		= $this->_get_ave_color( $main_rgb['red'],		$watermark_rbg['red'],		$watermark_alpha );
					$avg_green	= $this->_get_ave_color( $main_rgb['green'],	$watermark_rbg['green'],	$watermark_alpha );
					$avg_blue		= $this->_get_ave_color( $main_rgb['blue'],	$watermark_rbg['blue'],		$watermark_alpha );

					# calculate a color index value using the average RGB values we've determined
					$return_color	= $this->_get_image_color( $return_img, $avg_red, $avg_green, $avg_blue );

				# if we're not dealing with an average color here, then let's just copy over the main color
				} else {
					$return_color	= imagecolorat( $main_img_obj, $x, $y );

				} # END if watermark

				# draw the appropriate color onto the return image
				imagesetpixel( $return_img, $x, $y, $return_color );

			} # END for each X pixel
		} # END for each Y pixel

		# return the resulting, watermarked image for display
		return $return_img;

	} # END create_watermark()

	# average two colors given an alpha
	function _get_ave_color( $color_a, $color_b, $alpha_level ) 
	{
		return round( ( ( $color_a * ( 1 - $alpha_level ) ) + ( $color_b	* $alpha_level ) ) );
	} # END _get_ave_color()

	# return closest pallette-color match for RGB values
	function _get_image_color($im, $r, $g, $b) 
	{
		$c=imagecolorexact($im, $r, $g, $b);
		if ($c!=-1) return $c;
		$c=imagecolorallocate($im, $r, $g, $b);
		if ($c!=-1) return $c;
		return imagecolorclosest($im, $r, $g, $b);
	} # EBD _get_image_color()
    
}

?>