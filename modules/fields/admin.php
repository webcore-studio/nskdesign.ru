<?php
/*
 * Главная админская страница
*/
function fields_admin_main()
  {
  sysRedirect(sysModURL('fields','user','main'));
  }

/*
 * Создание нового комментария
*/

function fields_admin_list()
  {
list ($fld_module,$fld_key_a,$fld_key_b)=sysVarCleanFromInput('fld_module','fld_key_a','fld_key_b');

	//Строим имя обьекта
  $sysObject = 'fields::admin::list::'.$fld_module.'::'.$fld_key_a.'::'.$fld_key_b;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

    sysModClassLoad ('fields');
    $fields = new fields;
$fields_list=$fields->get_list ($fld_module, $fld_key_a, $fld_key_b,$inh=1);

  //Инициализируем систему шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  $sysTpl->assign('fields_list',$fields_list);
  $sysTpl->assign('fld_module',$fld_module);
  $sysTpl->assign('fld_key_a',$fld_key_a);
  $sysTpl->assign('fld_key_b',$fld_key_b);


  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('fields','admin','list'),
                       'displayname'=>'Управление дополнительными полями');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject);
  $result['browsein'] =& $browsein;
  return $result;



}


function fields_admin_field_create()
  {
  //Забираем переменные со входа
  $fields_table = sysDBGetTable('fields');
  $fields_column = sysDBGetColumns($fields_table);
  $fields_indate = sysDBCleanFromInput ($fields_column);


	//Проверка разрешеныли поля
	//if (!sysModGetVar('fields', 'fields_enabled')) return;

  //Проверки
  $fields_indate['fld_key_a'] = (int)$fields_indate['fld_key_a'];
  $fields_indate['fld_key_b'] = (int)$fields_indate['fld_key_b'];
  if ($fields_indate['fld_key_a']<1 && $fields_indate['fld_key_b']<1) return;

	//Строим имя обьекта
  $sysObject = 'fields::admin::field::create::'.$fields_indate['fld_module'].'::'.$fields_indate['fld_key_a'].'::'.$fields_indate['fld_key_b'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADD)) return;


    sysModClassLoad ('fields');
    $fields = new fields;
    $fields_indate['id'] = $fields->field_create($fields_indate);

 
sysShowMessage("/index.php?module=fields&type=admin&func=list&fld_module=$fld_module&fld_key_a=$fld_key_a&fld_key_b=$fld_key_b",'Поле добавлено');

  }

/*
 * Редактирование отзыва
*/
function fields_admin_field_modify()
  {
  //Забрали переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('fields_admin_modify', BAD_PARAM, 'Id not numeric'.$id);

  //Информация о поле
  sysModClassLoad ('fields');
  $fields = new fields;
  $fields_dbdata = $fields->get_info($id);
  $param_dbdata=$fields->get_params($id);

  //Проверка разрешеныли комментарии
 // if (!sysModGetVar('fields', 'fields_enabled')) return;

  //Строим имя обьекта
  $sysObject = 'fields::admin::field::modify::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT)) return;

  //Инициализируем систему шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Уровень доступа у текущего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject);
  $sysTpl->assign('curuser_sec_level', $curuser_sec_level);


  //Рендерим
  $sysTpl->assign($fields_dbdata);
  $sysTpl->assign('param_dbdata',$param_dbdata);

 //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('fields','admin','main'),
                       'displayname'=>'Управление дополнительными полями');
  $browsein[] = array ('url'=>sysModURL('fields','admin','modify'),
                       'displayname'=>'Редактирование поля');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject);
  $result['browsein'] =& $browsein;
  return $result;

  }




function fields_admin_field_update()
  {

  //Забираем переменные со входа
  $param_table = sysDBGetTable('fields_param');
  $param_column =  sysDBGetColumns($param_table);
  $param_indata = sysDBCleanFromInput ($param_column);

$param_delete=sysVarCleanFromInput('param_delete');

$fld_displayname=sysVarCleanFromInput('fld_displayname');
 
  if (!is_numeric ($param_indata['id_field'])) sysException ('fields_admin_modify', BAD_PARAM, 'Id not numeric'.$param_indata['id_field']);

  //Информация о поле
  sysModClassLoad ('fields');
  $fields = new fields;

 
  //Строим имя обьекта
  $sysObject = 'fields::admin::field::modify::'.$param_indata['id_field'];
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT)) return;

if ($param_indata['fld_field'] || $param_indata['fld_image']) $fields->param_create($param_indata);

$fld_dbdata=$fields->get_info($param_indata['id_field']);
if (($fld_dbdata['fld_displayname']!=$fld_displayname)&&$fld_displayname!='')
{
$field_indata=array();
$field_indata['id']=$param_indata['id_field'];
$field_indata['fld_displayname']=$fld_displayname;
$fields->field_update($field_indata);
}

if ($param_delete)
	foreach ($param_delete as $k=>$v)
		$fields->param_delete($v);	

sysShowMessage("/index.php?module=fields&type=admin&func=field_modify&id=$param_indata[id_field]","Редактирование успешно произведено");
  }


/*
 * Редактирование нового комментария
*/
function fields_admin_update()
  {
  //Забираем переменные со входа
  $fields_table = sysDBGetTable('fields');
  $fields_column = sysDBGetColumns($fields_table);
  $fields_indate = sysDBCleanFromInput ($fields_column);
  if (!is_numeric ($fields_indate['id'])) sysException ('fields_admin_update', BAD_PARAM, 'Id not numeric'.$fields_indate['id']);

  $preview = sysVarCleanFromInput('preview');

  //Информация о комментарии
  sysModClassLoad ('fields');
  $fields = new fields;
  $fields_dbdate = $fields->get_info($fields_indate['id']);

	//Проверка разрешеныли комментарии
	if (!sysModGetVar('fields', 'fields_enabled')) return;

	// Проверка наличия модулей
  if (!sysModAvailable ('fields')) return;
  if (!sysModAvailable ($fields_dbdate['comm_module'])) return;

	//Строим имя обьекта
  $sysObject = 'fields::admin::update::'.$fields_dbdate['comm_module'];
  if ($fields_dbdate['comm_key_a']) $sysObject.='::'.$fields_dbdate['comm_key_a'];
  if ($fields_dbdate['comm_key_b']) $sysObject.='::'.$fields_dbdate['comm_key_b'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $fields_dbdate['user_id'])) return;

  //Дополняем информацию о комменте
  $fields_dbdate['comm_modtime'] = time();
  $fields_dbdate['comm_modcount'] = $fields_dbdate['comm_modcount']+1;
  $fields_dbdate['comm_from_ip'] = sysUserIpAddr();

  $fields_dbdate['comm_displayname'] = $fields_indate['comm_displayname'];
  $fields_dbdate['comm_content'] = $fields_indate['comm_content'];

	//загружаем модулю которому адресован коммент
  sysModClassLoad ($fields_dbdate['comm_module']);
  $tomodule = new $fields_dbdate['comm_module'];

	//Пробуем передать управление модулю которому адресован коммент для пред обработки
	$prefilter_func = $fields_dbdate['comm_module'].'_fields_modify';
  if (function_exists($prefilter_func)) $fields_dbdate = $prefilter_func($fields_dbdate);

  //Создаем комментарий
  if (!$preview)
    {
    sysModClassLoad ('fields');
    $fields = new fields;
    $fields->update($fields_dbdate);

  	//Пробуем передать управление модулю которому адресован коммент для пост действий
  	$postfilter_func = $fields_dbdate['comm_module'].'_fields_update';
    if (function_exists($postfilter_func)) $postfilter_func($fields_dbdate);
    }

  //Инициализируем систему шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Рендерим
  $sysTpl->assign($fields_dbdate);
  $sysTpl->assign('preview', $preview);
  $content =& $sysTpl->fetch($sysModTpl,$sysObject);
  echo $content;

	sysTerminate();
	exit;

  return;
  }

/*
 * Удаление отзыва
*/
function fields_admin_delete()
  {
  //Забрали переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('fields_admin_delete', BAD_PARAM, 'Id not numeric'.$id);

  //Информация о комментарии
  sysModClassLoad ('fields');
  $fields = new fields;
  $fields_dbdate = $fields->get_info($id);

  //Проверка разрешеныли комментарии
  if (!sysModGetVar('fields', 'fields_enabled')) return;

  // Проверка наличия модулей
  if (!sysModAvailable ('fields')) return;
  if (!sysModAvailable ($fields_dbdate['comm_module'])) return;

  //Строим имя обьекта
  $sysObject = 'fields::admin::delete::'.$fields_dbdate['comm_module'];
  if ($fields_dbdate['comm_key_a']) $sysObject.='::'.$fields_dbdate['comm_key_a'];
  if ($fields_dbdate['comm_key_b']) $sysObject.='::'.$fields_dbdate['comm_key_b'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_DELETE, $fields_dbdate['user_id'])) return;

  //загружаем модулю которому адресован коммент
  sysModClassLoad ($fields_dbdate['comm_module']);
  $tomodule = new $fields_dbdate['comm_module'];

  //Пробуем передать управление модулю которому адресован коммент для пред обработки
  $prefilter_func = $fields_dbdate['comm_module'].'_fields_confirm_delete';
  if (function_exists($prefilter_func)) $prefilter_func($fields_dbdate);

  //Удаляем комментарий
  $fields->delete($id);

  //Пробуем передать управление модулю которому адресован коммент для пост действий
  $postfilter_func = $fields_dbdate['comm_module'].'_fields_delete';
  if (function_exists($postfilter_func)) $postfilter_func($fields_dbdate);

  //Инициализируем систему шаблонов
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Рендерим
  $sysTpl->assign($fields_dbdate);
  $content =& $sysTpl->fetch($sysModTpl,$sysObject);
  echo $content;

	sysTerminate();
	exit;

  return;
  }

/*
 * Конфигуриррование настроек модуля
*/
function fields_admin_config_modify()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'fields::admin::config_modify';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

	//Шаблоны
  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Список групп
  sysModClassLoad ('SYS_groups');  $SYS_groups = new SYS_groups;
  $groups_list = $SYS_groups->get_list();
  $flat_groups_list=array();
  foreach ($groups_list as $group) $flat_groups_list[$group['id']] = $group['group_displayname'];
  $sysTpl->assign('groups_list', $flat_groups_list);

  //Text editor
  $texteditorlist=array ();
  $texteditorlist[0] = 'Нет';
  $texteditorlist[1] = 'WYSIWYG';
  $sysTpl->assign('texteditorlist', $texteditorlist);

  //Пошел вывод переменных
  $sysTpl->assign('fields_enabled', 			 sysModGetVar('fields', 'fields_enabled'));
  $sysTpl->assign('captcha_enabled', 			 sysModGetVar('fields', 'captcha_enabled'));
  $sysTpl->assign('fields_perpage', 			 sysModGetVar('fields', 'fields_perpage'));

  $sysTpl->assign('content_min_len', 			 sysModGetVar('fields', 'content_min_len'));
  $sysTpl->assign('content_max_len',			 sysModGetVar('fields', 'content_max_len'));

  $sysTpl->assign('sort_by', 							 sysModGetVar('fields', 'sort_by'));
  $sysTpl->assign('sort_asc',							 sysModGetVar('fields', 'sort_asc'));

  $sysTpl->assign('flood_ip_time',				 sysModGetVar('fields', 'flood_ip_time'));
  $sysTpl->assign('email_notify_enable',	 sysModGetVar('fields', 'email_notify_enable'));
  $sysTpl->assign('email_notify_group',		 sysModGetVar('fields', 'email_notify_group'));
  $sysTpl->assign('use_texteditor',				 sysModGetVar('fields', 'use_texteditor'));

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('fields','user','main'),
                     'displayname'=>'Комментарии');
  $browsein[]=array ('url'=>sysModURL('fields','admin','config_modify'),
                     'displayname'=>'Конфигурация');
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  };

/*
 * Сохраняем конфигурацию главных настроек
*/
function fields_admin_config_update()
  {
  $sysObject = 'fields::admin::update_config';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  list ($fields_enabled, $captcha_enabled, $fields_perpage, $content_min_len, $content_max_len, $sort_by, $sort_asc,
        $flood_ip_time, $email_notify_enable, $email_notify_group, $use_texteditor)=
      sysVarCleanFromInput('fields_enabled', 'captcha_enabled', 'fields_perpage', 'content_min_len', 'content_max_len', 'sort_by', 'sort_asc',
        'flood_ip_time', 'email_notify_enable', 'email_notify_group', 'use_texteditor');


  sysModSetVar ('fields','fields_enabled', $fields_enabled);
  sysModSetVar ('fields','captcha_enabled', $captcha_enabled);
  sysModSetVar ('fields','fields_perpage', $fields_perpage);

  sysModSetVar ('fields','content_min_len', $content_min_len);
  sysModSetVar ('fields','content_max_len', $content_max_len);

  sysModSetVar ('fields','sort_by', $sort_by);
  sysModSetVar ('fields','sort_asc', $sort_asc);

  sysModSetVar ('fields','flood_ip_time', $flood_ip_time);
  sysModSetVar ('fields','email_notify_enable', $email_notify_enable);
  sysModSetVar ('fields','email_notify_group', $email_notify_group);
  sysModSetVar ('fields','use_texteditor', $use_texteditor);

  sysShowMessage(sysModURL ('fields','user','main'), 'Редактирование успешно произведено');
  return true;
  }
  
  
  
function fields_admin_values_update()  {
  
	//Забираем переменные со входа
	list($fld_modname, $fld_key_a, $fld_key_b) = sysVarCleanFromInput("fld_modname", "fld_key_a", "fld_key_b");
	$fld = $_POST['fld'];
 
	//Информация о комментарии
	sysModClassLoad ('fields');
	$fields = new fields;
	
	// Проверка наличия модулей
	if (!sysModAvailable ('fields')) return;
	if (!sysModAvailable ($fld_modname)) return;

	//Строим имя обьекта
	$sysObject = 'fields::admin::values::update::'.$fld_modname;
	if ($fld_key_a) $sysObject.='::'.$fld_key_a;
	if ($fld_key_b) $sysObject.='::'.$fld_key_b;
	
	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_EDIT, $fields_dbdate['user_id'])) return;

	//загружаем модулю которому адресовано поле и смотрим, есть ли кому обработать - плагин
	
	sysExtLibLoad('pluginslib');
	$sysPlugins = new sysPlugins();

	//Пробуем передать управление модулю которому адресован коммент для пред обработки
	$sysPlugins ->plugin_exe($fld_modname,'fields','values_update', array('fld_key_a' => $fld_key_a, 'fld_key_b' => $fld_key_b, 'fld' => $fld));
	
	sysRedirect($_SERVER['HTTP_REFERER']);
	exit;
}

?>