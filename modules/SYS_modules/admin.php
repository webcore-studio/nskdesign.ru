<?php
/*
 * Главное админское меню.
*/
function SYS_modules_admin_main()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_modules::admin::main';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Грузим наш класс управления
  sysModClassLoad('SYS_modules');
  $SYS_modules = new SYS_modules;

  //Перестраиваем список модулей в системе
  $moduleslist = $SYS_modules->regen();

  //Отображаем список установленных модулей
  $moduleslist = $SYS_modules->get_list();

  foreach ($moduleslist as $key=>$module)
    {
    //Поверхностно проверяем модули, всели файлы на месте
    if (!file_exists ("modules/$module[mod_name]/info.php"))
      {
      $module['mod_state'] = _SYSMODULE_STATE_MISSING;
      };

    //Приобразовали из id к человеческаму виду Состояние
    switch ($module['mod_state'])
      {
      case MODULE_STATE_NA:
        $module['mod_state']='NA';
        break;
      case MODULE_STATE_UNINITIALISED:
        $module['mod_state']='UNINITIALISED';
        break;
      case MODULE_STATE_INACTIVE:
        $module['mod_state']='INACTIVE';
        break;
      case MODULE_STATE_ACTIVE:
        $module['mod_state']='ACTIVE';
        break;
      case MODULE_STATE_MISSING:
        $module['mod_state']='MISSING';
        break;
      };

    //Внесли изминения
    $moduleslist[$key]=$module;
    }

  $sysTpl->assign('modules_list', $moduleslist);

  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('SYS_modules','admin','main'),
                       'displayname'=>'Администрирование модулей');
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

/*
 * Просмотр информации о модуле
*/
function SYS_modules_admin_info()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_modules_admin_info', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_modules::admin::info::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;
  //Тело модуля

  //Грузим наш класс
  sysModClassLoad('SYS_modules');
  $SYS_modules = new SYS_modules;
  $module_info = $SYS_modules->get_info($id);

  $sysTpl->assign ($module_info);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_modules','admin','main'),
                     'displayname'=>"Администрирование модулей");
  $browsein[]=array ('url'=>sysModURL('SYS_modules','admin','info', array ('id'=>$module_info['id'])),
                     'displayname'=>"Информация о модуле \"".$module_info['mod_name'].'"');
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Инициализация модуля
*/
function SYS_modules_admin_initialise()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_modules_admin_initialise', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_modules::admin::initialise::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Тело модуля
  sysModClassLoad('SYS_modules');
  $SYS_modules = new SYS_modules;
  $SYS_modules->initialise($id);

  sysShowMessage(sysModURL ('SYS_modules','admin','main'), 'Модуль проинициализирован');
  }

/*
 * Удаление модуля
*/
function SYS_modules_admin_delete()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_modules_admin_delete', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_modules::admin::delete::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Тело модуля
  sysModClassLoad('SYS_modules');
  $SYS_modules = new SYS_modules;
  $SYS_modules->delete($id);

  //
  sysShowMessage(sysModURL ('SYS_modules','admin','main'), 'Модуль удален');
  }

/*
 * Активация модуля
*/
function SYS_modules_admin_activate()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_modules_admin_activate', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_modules::admin::activate::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Тело модуля
  sysModClassLoad('SYS_modules');
  $SYS_modules = new SYS_modules;
  $SYS_modules->activate($id);

  sysShowMessage(sysModURL ('SYS_modules','admin','main'), 'Модуль активирован');
  }

/*
 * ДеАктивация модуля
*/
function SYS_modules_admin_deactivate()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_modules_admin_deactivate', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_modules::admin::deactivate::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_modules');
  $SYS_modules = new SYS_modules;
  $SYS_modules->deactivate($id);

  sysShowMessage(sysModURL ('SYS_modules','admin','main'), 'Модуль деактивирован');
  }

?>