<?php
/*
 * Главное админское меню
*/
function SYS_themes_admin_main()
  {
  $sysObject = 'SYS_themes::admin::main';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Регенирируем список тем
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;
  $SYS_themes->regen();
  //Список тем
  $themeslist = $SYS_themes->get_list();
  $sysTpl->assign('themes_list', $themeslist);

  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('SYS_themes','admin','main'),
                       'displayname'=>'Менеджер тем');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

/*
 * Главная страница управления шаблонами модулей
*/
function SYS_themes_admin_template_main()
  {
  $sysObject = 'SYS_themes::admin::template::main';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Получили список модулей и блоков в системе
  sysModClassLoad('SYS_modules');
  $SYS_modules = new SYS_modules;
  $modules_list = $SYS_modules->get_list(true);
  $sysTpl->assign('modules_list', $modules_list);

  //Список блоков в системе
  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $blocks_list = $SYS_blocks->get_list();
  $sysTpl->assign('blocks_list', $blocks_list);
  //Обьединяем два массива
  //$complist=array_merge($modules_list, $blocks_list);
  //$sysTpl->assign('complist', $complist);

  //BrowseIn
  $browsein=array();
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','main'),
                     'displayname'=>'Менеджер тем');
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','template_main'),
                     'displayname'=>"Шаблоны компонентов");
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Просмотр шаблонов темы выбранного модуля
*/
function SYS_themes_admin_template_view()
  {
  //Забираем переменные со входа
  $tpl_compname = sysVarCleanFromInput('tpl_compname');
  if (empty ($tpl_compname))  sysException ('SYS_themes_admin_template_view', BAD_PARAM);

  $sysObject = 'SYS_themes::admin::template::view::'.$tpl_compname;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;
  $tpls_list = $SYS_themes->tpl_get_list($tpl_compname);


  if (!empty ($tpls_list))
    {
    //передвижка весов
    $tpls_table = sysDBGetTable('templates');
    $tpls_column =  sysDBGetColumns($tpls_table);
    $MaxWeight = count ($tpls_list);
    foreach ($tpls_list as $key=>$tpl)
      {
      if ($MaxWeight>1)//Если адин нехир двигать
        {
        if ($tpl['weight']>1)
          {
          $tpl['weight_moveup_url'] = sysModUrl ('SYS_themes','admin','template_moveup',
              array ('id'=>$tpl['id']));
          };

        if ($tpl['weight']<$MaxWeight)
          {
          $tpl['weight_movedown_url']=sysModUrl ('SYS_themes','admin','template_movedown',
               array ('id'=>$tpl['id']));
          };
        }

      $tpls_list[$key]=$tpl;
      };
    };
  $sysTpl->assign('tpls_list', $tpls_list);

  $sysTpl->assign('tpl_compname', $tpl_compname);

  //BrowseIn
  $browsein=array();
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','main'),
                     'displayname'=>'Менеджер тем');
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','template_main'),
                     'displayname'=>"Шаблоны компонентов");
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','template_view',
                       array ('themename'=>$themename, 'tpl_compname'=>$tpl_compname)),
                     'displayname'=>"Просмотр шаблонов компонента \"$tpl_compname\"");
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Добавление шаблона заданной теми и заданнова компонента
*/
function SYS_themes_admin_template_new()
  {
  //Забрали переменные со входа
  $tpl_compname = sysVarCleanFromInput('tpl_compname');
  if (empty ($tpl_compname))  sysException ('SYS_themes_admin_template_new', BAD_PARAM);

  $sysObject = 'SYS_themes::admin::template::new::'.$tpl_compname;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  //Тело модуля
  $sysTpl->assign('tpl_compname', $tpl_compname);
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','main'),
                     'displayname'=>'Менеджер тем');
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','template_main'),
                     'displayname'=>"Шаблоны компонентов");
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','template_view',
                       array ('tpl_compname'=>$tpl_compname)),
                     'displayname'=>$tpl_compname);
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','template_new',
                       array ('tpl_compname'=>$tpl_compname)),
                     'displayname'=>"Добавление шаблона");
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  };

/*
 * Создание шаблона заданной теми и заданнова компонента
*/
function SYS_themes_admin_template_create()
  {
  //Забрали преременные со входа
  $tpls_table = sysDBGetTable('templates');
  $tpls_column =  sysDBGetColumns($tpls_table);
  $indata = sysDBCleanFromInput ($tpls_column);
  if (empty ($indata['tpl_compname']) || empty ($indata['tpl_name']))  sysException ('SYS_themes_admin_template_create', BAD_PARAM);
  $sysObject = 'SYS_themes::admin::template::create::'.$indata['tpl_compname'].'::'.$indata['tpl_name'];
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Тело модуля
  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;
  $SYS_themes->tpl_add ($indata);

  //Мессага, что изменения сохранены
  sysShowMessage(sysModURL ('SYS_themes','admin','template_view', array ('tpl_compname'=>$indata['tpl_compname'])), 'Шаблон добавлен');
  };

/*
 * Подтверждение удаления шаблона
*/
function SYS_themes_admin_template_confirm_delete()
  {
  //Забрали переменные со входа
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_themes_admin_template_confirm_delete', BAD_PARAM, 'Id not numeric'.$id);

  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;
  $tpl = $SYS_themes->tpl_get_info($id);

  $sysObject = 'SYS_themes::admin::template::confirm_delete::'.$tpl['tpl_compname'].'::'.$tpl['tpl_name'];
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  $sysTpl->assign($tpl);

  //Добавляем наш документ
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','main'),
                     'displayname'=>'Менеджер тем');
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','template_main'),
                     'displayname'=>"Шаблоны компонентов");
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','template_view',
                       array ('tpl_compname'=>$tpl['tpl_compname'])),
                     'displayname'=>$tpl['tpl_compname']);
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','template_new',
                     array ('tpl_compname'=>$tpl['tpl_compname'])),
                     'displayname'=>"Удаление шаблона \"$tpl[tpl_name]\"");
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  };

/*
 * Собственно удаление шаблона
*/
function SYS_themes_admin_template_delete()
  {
  //Забрали переменные со входа
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_themes_admin_template_delete', BAD_PARAM, 'Id not numeric'.$id);

  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;
  $tpl = $SYS_themes->tpl_get_info($id);

  $sysObject = 'SYS_themes::admin::template::delete::'.$tpl['tpl_compname'].'::'.$tpl['tpl_name'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $SYS_themes->tpl_delete($id);

  //Мессага, что усе удалено
  sysShowMessage(sysModURL ('SYS_themes','admin','template_view', array ('tpl_compname'=>$tpl['tpl_compname'])), 'Шаблон удален');
  };

/*
 * Редактирование шаблона заданной теми и заданнова модуля
*/
function SYS_themes_admin_template_modify()
  {
  //Забрали переменные со входа
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_themes_admin_template_modify', BAD_PARAM, 'Id not numeric'.$id);

  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;
  $tpl = $SYS_themes->tpl_get_info($id);

  $sysObject = 'SYS_themes::admin::template::modify::'.$tpl['tpl_compname'].'::'.$tpl['tpl_name'];
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  $sysTpl->assign($tpl);

  //Добавляем наш документ
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','main'),
                     'displayname'=>'Менеджер тем');
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','template_main'),
                     'displayname'=>"Шаблоны компонентов");
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','template_view',
                       array ('tpl_compname'=>$tpl['tpl_compname'])),
                     'displayname'=>$tpl['tpl_compname']);
  $browsein[]=array ('url'=>sysModURL('SYS_themes','admin','template_new',
                       array ('tpl_compname'=>$tpl['tpl_compname'], 'tpl_name'=>$tpl['tpl_name'])),
                     'displayname'=>"Редактирование шаблона");
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Внесение изменений в шаблон
*/
function SYS_themes_admin_template_update()
  {
  //Забрали переменные со входа
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_themes_admin_template_update', BAD_PARAM, 'Id not numeric'.$id);

  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;
  $tpl = $SYS_themes->tpl_get_info($id);

  $sysObject = 'SYS_themes::admin::template::update::'.$tpl['tpl_compname'].'::'.$tpl['tpl_name'];
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //переменные со входа
  $tpls_table = sysDBGetTable('templates');
  $tpls_column =  sysDBGetColumns($tpls_table);
  $indata = sysDBCleanFromInput ($tpls_column);

  $SYS_themes->tpl_update($indata);

  //Мессага, что изменения сохранены
  sysShowMessage(sysModURL ('SYS_themes','admin','template_view', array ('tpl_compname'=>$tpl['tpl_compname'])), 'Изменения сохранены');
  }

/*
 * Увеличение вес шаблона
*/
function SYS_themes_admin_template_moveup ()
  {
  //Забрали переменные со входа
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_themes_admin_template_moveup', BAD_PARAM, 'Id not numeric'.$id);

  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;
  $tpl = $SYS_themes->tpl_get_info($id);
  //
  $sysObject = 'SYS_themes::admin::template::moveup::'.$tpl['tpl_compname'].'::'.$tpl['tpl_name'];
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Тело модуля
  $SYS_themes->tpl_moveup($id);

  sysRedirect(sysModURL ('SYS_themes','admin','template_view',
               array ('tpl_compname'=>$tpl['tpl_compname'])));
  }

/*
 * Уменьшыли вес шаблона
*/
function SYS_themes_admin_template_movedown ()
  {
  //Забрали переменные со входа
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_themes_admin_template_movedown', BAD_PARAM, 'Id not numeric'.$id);

  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;
  $tpl = $SYS_themes->tpl_get_info($id);
  //
  $sysObject = 'SYS_themes::admin::template::movedown::'.$tpl['tpl_compname'].'::'.$tpl['tpl_name'];
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Тело модуля
  $SYS_themes->tpl_movedown($id);

  sysRedirect(sysModURL ('SYS_themes','admin','template_view',
               array ('tpl_compname'=>$tpl['tpl_compname'])));
  }


/*
 * Активирование темы
*/
function SYS_themes_admin_theme_activate()
  {
  //Забираем переменные со входа
  $id=sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_themes_admin_theme_activate', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_themes::admin::theme::activate::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;
  $SYS_themes->activate($id);

  //Мессага
  sysShowMessage(sysModURL ('SYS_themes','admin','main'), 'Тема активирована');
  }

/*
 * ДеАктивирование темы
*/
function SYS_themes_admin_theme_deactivate()
  {
  //Забираем переменные со входа
  $id=sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_themes_admin_theme_deactivate', BAD_PARAM, 'Id not numeric');
  //
  $sysObject = 'SYS_themes::admin::theme::deactivate::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad ('SYS_themes');
  $SYS_themes = new SYS_themes;

  $theme_info = $SYS_themes->get_info($id);
 /* if (sysModGetVar('SYS_config','default_theme')==$theme_info['theme_name'])
    {
    sysShowMessage(sysModURL ('SYS_themes','admin','main'), 'Нельзя деактивировать тему "по умолчанию" !');
    }
*/
  $SYS_themes->deactivate($id);

  //Мессага
  sysShowMessage(sysModURL ('SYS_themes','admin','main'), 'Тема деактивирована');
  }
  
/*
* Изменение сортировки шаблонов
*/
function SYS_themes_admin_weight ()  
{

	$weights = sysVarCleanFromInput('weights');
	$ids = sysVarCleanFromInput('ids');
	$tpl_compname = sysVarCleanFromInput('tpl_compname');
  
	$sysObject = 'SYS_themes::admin::weights';

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_EDIT)) return;
	

	$templates_table = sysDBGetTable('templates');
	foreach ($weights as $k=>$v){
		$indata=array('weight'=>$v,'id'=>$ids[$k]);
		sysDBUpdate($templates_table,$indata,"WHERE $templates_table.id=$indata[id]");
	}

	sysVarDelCached('tpls_list', $tpl_compname);

	$http_referer = $_SERVER['HTTP_REFERER'];
	if (empty ($http_referer)) 
		$http_referer = sysModURL ('SYS_themes','admin','template_view', array('tpl_compname' => $tpl_compname));

	sysRedirect($http_referer);
 
}



?>