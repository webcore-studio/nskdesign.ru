<?php

function fm_init()
{
	sysModClassLoad ('SYS_df');
	$SYS_df = new SYS_df;
	sysModClassLoad ('SYS_themes');
	$SYS_themes = new SYS_themes();
  
	//Регистрируем темплеи в системе
	$SYS_themes->tpl_add (array('tpl_compname'=>'fm',
                              'tpl_name'=>'admin_main.tpl',
                              'tpl_pattern'=>'fm::admin::main',
                              'tpl_description'=>'Админская титульная страница'));

 
  
	$SYS_themes->tpl_add (array('tpl_compname'=>'fm',
                              'tpl_name'=>'admin_modifyconfig.tpl',
                              'tpl_pattern'=>'fm::admin::modifyconfig',
                              'tpl_description'=>'Конфигурация'));


	$SYS_themes->tpl_add (array('tpl_compname'=>'fm',
                              'tpl_name'=>'ajax_select_image.tpl',
                              'tpl_pattern'=>'fm::ajax::select::image',
                              'tpl_description'=>'Вставка рисунка в документ'));
							  
	$SYS_themes->tpl_add (array('tpl_compname'=>'fm',
                              'tpl_name'=>'ajax_tinymce.tpl',
                              'tpl_pattern'=>'fm::ajax::tinymce',
                              'tpl_description'=>'Вставка рисунка в документ'));

	return true;
}

function fm_upgrade($oldversion)
  {
  // Update successful
  return false;
  }

function fm_delete()
  {
  // Deletion successful
  return true;
} 

function fm_activate()
  {
  return true;
  }

function fm_deactivate()
  {
  return true;
  }

?>