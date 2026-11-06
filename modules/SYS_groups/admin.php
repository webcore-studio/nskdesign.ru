<?php
/*
 * Меню выбора, че конфигурить.
*/
function SYS_groups_admin_main()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_groups::admin::main';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;
  //Тело модуля

  //Отображаем список существкющих групп
  sysModClassLoad ('SYS_groups');
  $SYS_groups = new SYS_groups;

	sysModClassLoad ('SYS_users');
	$SYS_users = new SYS_users;

  $groups_list = $SYS_groups->get_list();

  if (isset ($groups_list))
    {
      foreach ($groups_list as &$group) {
        $group['count'] = $SYS_users->get_count($group['id'], $filters);
        
      }

    $sysTpl->assign('groups_list', $groups_list);
    };

  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('SYS_groups','admin','main'),
                       'displayname'=>'Группы');

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

/*
 * Создание новой группы
*/
function SYS_groups_admin_create()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_groups::admin::create';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Тело модуля
  //Забираем переменные со входа
  $groups_table = sysDBGetTable ('groups');
  $groups_column = sysDBGetColumns($groups_table);
  $indata = sysDBCleanFromInput ($groups_column);

  //Проверки
  if (empty ($indata['group_displayname']))
    sysShowMessage(sysModURL ('SYS_groups','admin','main'), 'Не указанно имя группы');

  //Вызвали class для создания группы
  sysModClassLoad ('SYS_groups');
  $SYS_groups = new SYS_groups;
  $SYS_groups->create($indata);

  sysShowMessage(sysModURL ('SYS_groups','admin','main'), 'Группа создана');
  }

/*
 * Меню выбора, че конфигурить.
*/
function SYS_groups_admin_modify()
  {
  //Забираем переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_groups_admin_modify', BAD_PARAM, 'Id not numeric');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_groups::admin::modify::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Инициализация системы шаблонов +проверка в кеше
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Тело модуля
  sysModClassLoad ('SYS_groups');
  $SYS_groups = new SYS_groups;
  $dbdata = $SYS_groups->get_info($id);

  $sysTpl->assign($dbdata);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_groups','admin','main'),
                     'displayname'=>"Группы");
  $browsein[]=array ('url'=>sysModURL('SYS_groups','admin','view',array ('id'=>$id)),
                     'displayname'=>"Редактирование группы \"$dbdata[group_displayname]\"");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Внесение изминений в группу
*/
function SYS_groups_admin_update()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_groups_admin_update', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_groups::admin::update::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Забираем переменные со входа
  $groups_table = sysDBGetTable ('groups');
  $groups_column = sysDBGetColumns($groups_table);
  $indata = sysDBCleanFromInput ($groups_column);

  sysModClassLoad ('SYS_groups');
  $SYS_groups = new SYS_groups;
  $SYS_groups->update($indata);

  sysShowMessage(sysModURL ('SYS_groups','admin','main'), 'Изменения сохранены');
  }

/*
 * Подтверждение удаления группы
*/
function SYS_groups_admin_confirm_delete()
  {
  //Забираем переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_groups_admin_confirm_delete', BAD_PARAM, 'Id not numeric');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_groups::admin::confirm_delete::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  //Делаем выборку
  sysModClassLoad ('SYS_groups');
  $SYS_groups = new SYS_groups;
  $dbdata = $SYS_groups->get_info($id);

  $sysTpl->assign($dbdata);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_groups','admin','main'),
                     'displayname'=>"Группы");
  $browsein[]=array ('url'=>sysModURL('SYS_groups','admin','view',array ('id'=>$id)),
                     'displayname'=>"Удаление группы \"$dbdata[group_displayname]\"");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Удаление группы :((
*/
function SYS_groups_admin_delete()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_groups_admin_delete', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_groups::admin::delete::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Удаление
  sysModClassLoad ('SYS_groups');
  $SYS_groups = new SYS_groups;
  $SYS_groups->delete($id);

  sysShowMessage(sysModURL ('SYS_groups','admin','main'), "Группа удалена");
  return true;
  }

?>