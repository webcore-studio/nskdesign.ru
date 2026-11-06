<?php
/*
 * Главное админское меню.
*/
function SYS_permissions_admin_main()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::main';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Грузим класс прав доступа
  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;

  //Грузим класс групп
  sysModClassLoad('SYS_groups');
  $SYS_groups = new SYS_groups;

  //Грузим класс пользователей
  sysModClassLoad('SYS_users');
  $SYS_users = new SYS_users;

  //Информация о правах группы
  $dbpermslist = $SYS_permissions->groups_perms_get_list();

  if (isset($dbpermslist))
    {
    //Подготовка к выводу
    foreach ($dbpermslist as $key=>$value)
      {
      $perms = $value;
      //ПРеобразовываем $gid к имени пользователя
      if ($perms['gid']==0)
        {
        $perms['gid']='Все';
        }
        elseif ($perms['gid']==-1)
          {
          $perms['gid']='Незарегистрированные';
          }
          else
            {
            $GroupInfo = $SYS_groups->get_info($perms['gid']);
            $perms['gid'] = $GroupInfo['group_displayname'];
            }
      //приводим к читаемому виду PermsLevel
      $levels_list = $SYS_permissions->permsLevelList();
      $perms['level'] = $levels_list[$perms['level']];

      //приводим к читаемому виду компанент
      if (!$perms['component'])
        {
        $perms['component']='Все';
        }

      //Ссылачки готовим
      //передвижка весов
      $groups_perms_table = sysDBGetTable('groups_perms');
      $MaxWeight = sysDbMaxWeight ($groups_perms_table);
      if ($MaxWeight>1)//Если адин нехир двигать
        {
        if ($perms['weight']>1)
          {
          $perms['weight_moveup_url']=sysModUrl ('SYS_permissions','admin','groups_perms_moveup', array ('id'=>$perms['id']));
          };

        if ($perms['weight']<$MaxWeight)
          {
          $perms['weight_movedown_url']=sysModUrl ('SYS_permissions','admin','groups_perms_movedown', array ('id'=>$perms['id']));
          };
        }
      $dbpermslist[$key]=$perms;
      };
    $sysTpl->assign('groups_perms_list', $dbpermslist);
    };



  $users_perms_table = sysDBGetTable('users_perms');
  $users_perms_column = sysDBGetColumns($users_perms_table);

  //Информация о правах пользователей
  $dbpermslist = $SYS_permissions->users_perms_get_list();

  if (isset($dbpermslist))
    {
    //Добавляем урл для редактирование и урл для удаления
    foreach ($dbpermslist as $key=>$value)
      {
      $perms = $value;
      //ПРеобразовываем $uid к имени пользователя
      $UserInfo = $SYS_users->get_info ($perms['uid']);
      $perms['uid'] = $UserInfo['user_name'];

      //приводим к читаемому виду PermsLevel
      $levels_list = $SYS_permissions->permsLevelList();
      $perms['level'] = $levels_list[$perms['level']];
      //приводим к читаемому виду компанент
      if (!$perms['component'])
        {
        $perms['component']='Все';
        }
      //Ссылачки гатовим
      //передвижка весов
      $MaxWeight = sysDbMaxWeight ($users_perms_table);
      if ($MaxWeight>1)//Если адин нехир двигать
        {
        if ($perms['weight']>1)
          {
          $perms['weight_moveup_url']=sysModUrl ('SYS_permissions','admin','users_perms_moveup', array ('id'=>$perms['id']));
          };

        if ($perms['weight']<$MaxWeight)
          {
          $perms['weight_movedown_url']=sysModUrl ('SYS_permissions','admin','users_perms_movedown', array ('id'=>$perms['id']));
          };
        }

      $dbpermslist[$key]=$perms;
      };
    $sysTpl->assign('users_perms_list', $dbpermslist);
    };



  //Информация о правах владельцев
  $owners_perms_table = sysDBGetTable('owners_perms');
  $owners_perms_column = sysDBGetColumns($owners_perms_table);
  $dbpermslist = $SYS_permissions->owners_perms_get_list();

  if (isset($dbpermslist))
    {
    //Добавляем урл для редактирование и урл для удаления
    foreach ($dbpermslist as $key=>$value)
      {
      $perms = $value;
      //ПРеобразовываем $gid к имени группы
      if ($perms['gid']==-1)
        {
        $perms['gid']='Все';
        }
        elseif ($perms['gid']==0)
          {
          $perms['gid']='Незарегистрированные';
          }
          else
            {
            $GroupInfo = $SYS_groups->get_info($perms['gid']);
            $perms['gid'] = $GroupInfo['group_displayname'];
            }

      //приводим к читаемому виду PermsLevel
      $levels_list = $SYS_permissions->permsLevelList();
      $perms['level'] = $levels_list[$perms['level']];
      //приводим к читаемому виду компанент
      if (!$perms['component'])
        {
        $perms['component']='Все';
        }
      //Ссылачки гатовим
      //передвижка весов
      $MaxWeight = sysDbMaxWeight ($owners_perms_table);
      if ($MaxWeight>1)//Если адин нехир двигать
        {
        if ($perms['weight']>1)
          {
          $perms['weight_moveup_url']=sysModUrl ('SYS_permissions','admin','owners_perms_moveup', array ('id'=>$perms['id']));
          };

        if ($perms['weight']<$MaxWeight)
          {
          $perms['weight_movedown_url']=sysModUrl ('SYS_permissions','admin','owners_perms_movedown', array ('id'=>$perms['id']));
          };
        }
      $dbpermslist[$key] = $perms;
      };
    $sysTpl->assign('owners_perms_list', $dbpermslist);
    };

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','main'),
                     'displayname'=>"Права доступа");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

/*
 * Добавление новых прав группы
*/
function SYS_permissions_admin_groups_perms_new()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::groups_perms::new';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Получили список групп в системе
  //Грузим класс групп
  sysModClassLoad('SYS_groups');
  $SYS_groups = new SYS_groups;
  $groups_list = $SYS_groups->get_list();

  //Преобразовали к виду ключ=>значеие
  $outgroupslist=array ();
  $outgroupslist[0] = array("id" => 0, "group_displayname" => "Все");
  $outgroupslist[-1] = array("id" => -1, "group_displayname" => "Незарегистрированные");
  foreach ($groups_list as $group)
    {
    $outgroupslist[$group['id']] = 
    array("id" => $group['id'], "group_displayname" => $group['group_displayname'], "group_year" => $group['group_year']);

    //$outgroupslist[$group['id']][0] = $group['group_year'];
    };
  $sysTpl->assign('groups_list', $outgroupslist);
  //$sysTpl->assign('groups_list', $groups_list);


  //Получили список модулей в системе
  sysModClassLoad('SYS_modules');
  $SYS_modules = new SYS_modules;
  $modules_list = $SYS_modules->get_list();

  //Прелбразовали к виду ключ=>значеие
  $outmoduleslist=array();
  $outmoduleslist[0]='Все';
  foreach ($modules_list as $module)
    {
    $outmoduleslist[$module['mod_name']] = $module['mod_name'];
    };


  //Получили список блоков в системе
  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $blocks_list = $SYS_blocks->get_list();

  //Прелбразовали к виду ключ=>значеие
  $outblockslist = array();
  foreach ($blocks_list as $block)
    {
    $outblockslist[$block['block_name']] = $block['block_name'];
    };
  //Обьединили два массива
  $complist = array_merge($outmoduleslist, $outblockslist);

  $sysTpl->assign('components_list', $complist);

  //Получаем список прав доступа в системе
  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $levels_list = $SYS_permissions->permsLevelList();

  $sysTpl->assign('levels_list', $levels_list);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','main'),
                     'displayname'=>"Права доступа");
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','groups_perms_new'),
                     'displayname'=>"Новые права группы");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Созданеие новых прав группы
*/
function SYS_permissions_admin_groups_perms_create()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::groups_perms::create';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Забрали преременные со входа
  $groups_perms_table = sysDBGetTable('groups_perms');
  $groups_perms_column = sysDBGetColumns($groups_perms_table);
  $indata = sysDBCleanFromInput ($groups_perms_column);

  //Вызвали class для создания пользователя
  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->groups_perms_create($indata);

  sysShowMessage(sysModURL ('SYS_permissions','admin','main'), 'Права группы добавлены');
  }

/*
 * Редактирование прав группы
*/
function SYS_permissions_admin_groups_perms_modify()
  {
  $id=sysVarCleanFromInput ('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_groups_perms_modify', BAD_PARAM, 'Id not numeric');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::groups_perms::modify';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Получили список групп в системе
  sysModClassLoad('SYS_groups');
  $SYS_groups = new SYS_groups;
  $groups_list = $SYS_groups->get_list();

  //Прелбразовали к виду ключ=>значеие
  foreach ($groups_list as $group)
    {
    $outgroupslist[0]='Все';
    $outgroupslist[-1]='Незарегистрированные';
    $outgroupslist[$group['id']] = $group['group_displayname'];
    };
  $sysTpl->assign('groups_list', $outgroupslist);

  //Получили список модулей в системе
  sysModClassLoad('SYS_modules');
  $SYS_modules = new SYS_modules;
  $modules_list = $SYS_modules->get_list();
  //Прелбразовали к виду ключ=>значеие
  foreach ($modules_list as $module)
    {
    $outmoduleslist[0]='Все';
    $outmoduleslist[$module['mod_name']] = $module['mod_name'];
    };

  //Получили список блоков в системе
  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $blocks_list = $SYS_blocks->get_list();
  //Прелбразовали к виду ключ=>значеие
  $outblockslist=array();
  foreach ($blocks_list as $block)
    {
    $outblockslist[$block['block_name']] = $block['block_name'];
    };

  //Обьединили два массива
  $complist = array_merge($outmoduleslist, $outblockslist);

  $sysTpl->assign('components_list', $complist);

  //Получаем список прав доступа в системе
  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $levels_list = $SYS_permissions->permsLevelList();
  $sysTpl->assign('levels_list', $levels_list);

  //Выгребли информацию из БД
  $dbdata = $SYS_permissions->groups_perms_get_info($id);
  $sysTpl->assign($dbdata);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','main'),
                     'displayname'=>"Права доступа");
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','groups_perms_new', array('id'=>$id)),
                     'displayname'=>"Редактирование прав группы");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Обновление прав группы
*/
function SYS_permissions_admin_groups_perms_update()
  {
  //Забрали преременные со входа
  $groups_perms_table = sysDBGetTable('groups_perms');
  $groups_perms_column = sysDBGetColumns($groups_perms_table);
  $indata = sysDBCleanFromInput ($groups_perms_column);
  if (!is_numeric ($indata['id'])) sysException ('SYS_permissions_admin_groups_perms_update', BAD_PARAM, 'Id not numeric');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::groups_perms::update::'.$indata['id'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->groups_perms_update($indata);

  sysShowMessage(sysModURL ('SYS_permissions','admin','main'), 'Права группы изменены ');
  }

/*
 * Подтверждение удаления группы
*/
function SYS_permissions_admin_groups_perms_confirm_delete()
  {
  //Забираем переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_groups_perms_confirm_delete', BAD_PARAM, 'Id not numeric');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::groups_perms::confirm_delete::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Инициализация системы шаблонов +проверка в кеше
  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Выгребли информацию из БД
  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $dbdata = $SYS_permissions->groups_perms_get_info($id);

  //приводим к читаемому виду компанент
  if (!$dbdata['component'])
    {
    $dbdata['component']='Все';
    }

  $sysTpl->assign($dbdata);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','main'),
                     'displayname'=>"Права доступа");
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','confirm_delete', array('id'=>id)),
                     'displayname'=>"Удаление прав группы");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Удаление правила группы
*/
function SYS_permissions_admin_groups_perms_delete ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_groups_perms_delete', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_permissions::admin::groups_perms::delete::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Тело модуля
  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->groups_perms_delete($id);

  //
  sysShowMessage(sysModURL ('SYS_permissions','admin','main'), 'Правило удалено');
  }

/*
 * Увеличение вес правила группы
*/
function SYS_permissions_admin_groups_perms_moveup ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_groups_perms_moveup', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_permissions::admin::groups_perms::moveup::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->groups_perms_moveup($id);

  sysRedirect(sysModURL ('SYS_permissions','admin','main'));
  }

/*
 * Уменьшыли вес правила группы
*/
function SYS_permissions_admin_groups_perms_movedown ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_groups_perms_movedown', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_permissions::admin::groups_perms::movedown::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->groups_perms_movedown($id);

  sysRedirect(sysModURL ('SYS_permissions','admin','main'));
  }




/*
 * Добавление новых прав пользователя
*/
function SYS_permissions_admin_users_perms_new()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::users_perms::new';
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Получили список пользователей в системе
  sysModClassLoad('SYS_users');
  $SYS_users = new SYS_users;
  $users_list = $SYS_users->get_list();

  //Прелбразовали к виду ключ=>значеие
  $outuserslist=array();
  foreach ($users_list as $user)
    {
    $outuserslist[$user['id']]=$user['user_name'];
    };
  $sysTpl->assign('users_list', $outuserslist);

  //Получили список модулей в системе
  sysModClassLoad('SYS_modules');
  $SYS_modules = new SYS_modules;
  $modules_list = $SYS_modules->get_list();
  //Прелбразовали к виду ключ=>значеие
  $outmoduleslist=array();
  $outmoduleslist[0]='Все';
  foreach ($modules_list as $module)
    {
    $outmoduleslist[$module['mod_name']]=$module['mod_name'];
    };

  //Получили список блоков в системе
  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $blocks_list = $SYS_blocks->get_list();
  //Прелбразовали к виду ключ=>значеие
  $outblockslist=array();
  foreach ($blocks_list as $block)
    {
    $outblockslist[$block['block_name']]=$block['block_name'];
    };
  //Обьединили два массива
  $complist = array_merge($outmoduleslist, $outblockslist);
  //В шаблон
  $sysTpl->assign('components_list', $complist);

  //Получаем список прав доступа в системе
  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $levels_list = $SYS_permissions->permsLevelList();
  $sysTpl->assign('levels_list', $levels_list);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','main'),
                     'displayname'=>"Права доступа");
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','users_perms_new'),
                     'displayname'=>"Новые права пользователя");
  $sysTpl->assign('browsein', $browsein);


  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Созданеие новых прав пользователя
*/
function SYS_permissions_admin_users_perms_create()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::users_perms::create';
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Забрали преременные со входа
  $users_perms_table = sysDBGetTable('users_perms');
  $users_perms_column = sysDBGetColumns($users_perms_table);
  $indata = sysDBCleanFromInput ($users_perms_column);

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->users_perms_create($indata);

  sysShowMessage(sysModURL ('SYS_permissions','admin','main'), 'Права пользователя добавлены ');
  }

/*
 * Редактирование прав пользователя
*/
function SYS_permissions_admin_users_perms_modify()
  {
  $id = sysVarCleanFromInput ('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_users_perms_modify', BAD_PARAM, 'Id not numeric');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::users_perms::modify::'.$id;
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Получили список пользователей в системе
  sysModClassLoad('SYS_users');
  $SYS_users = new SYS_users;
  $users_list = $SYS_users->get_list();

  //Прелбразовали к виду ключ=>значеие
  $outuserslist=array ();
  foreach ($users_list as $user)
    {
    $outuserslist[$user['id']]=$user['user_name'];
    };
  $sysTpl->assign('users_list', $outuserslist);

  //Получили список модулей в системе
  sysModClassLoad('SYS_modules');
  $SYS_modules = new SYS_modules;
  $modules_list = $SYS_modules->get_list();

  //Прелбразовали к виду ключ=>значеие
  $outmoduleslist=array ();
  $outmoduleslist[0]='Все';
  foreach ($modules_list as $module)
    {
    $outmoduleslist[$module['mod_name']]=$module['mod_name'];
    };

  //Получили список блоков в системе
  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $blocks_list = $SYS_blocks->get_list();

  //Прелбразовали к виду ключ=>значеие
  $outblockslist=array();
  foreach ($blocks_list as $block)
    {
    $outblockslist[$block['block_name']]=$block['block_name'];
    };
  //Обьединили два массива
  $complist=array_merge($outmoduleslist, $outblockslist);
  $sysTpl->assign("components_list", $complist);

  //Получаем список прав доступа в системе
  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $levels_list = $SYS_permissions->permsLevelList();
  $sysTpl->assign('levels_list', $levels_list);

  //Выгребли информацию из БД
  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $dbdata = $SYS_permissions->users_perms_get_info($id);
  $sysTpl->assign($dbdata);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','main'),
                     'displayname'=>"Права доступа");
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','users_perms_modify',array('id'=>$id)),
                     'displayname'=>"Редактирование прав пользователя");
  $sysTpl->assign('browsein', $browsein);


  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Обновление прав пользователя
*/
function SYS_permissions_admin_users_perms_update()
  {
  $users_perms_table = sysDBGetTable('users_perms');
  $users_perms_column = sysDBGetColumns($users_perms_table);
  $indata = sysDBCleanFromInput ($users_perms_column);

  if (!is_numeric ($indata['id'])) sysException ('SYS_permissions_admin_users_perms_update', BAD_PARAM, 'Id not numeric');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::users_perms::update::'.$indata['id'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->users_perms_update($indata);

  sysShowMessage(sysModURL ('SYS_permissions','admin','main'), 'Права пользователя изменены');
  }

/*
 * Подтверждение удаления прав пользователя
*/
function SYS_permissions_admin_users_perms_confirm_delete()
  {
  //Забираем переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_users_perms_confirm_delete', BAD_PARAM, 'Id not numeric');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::users_perms::confirm_delete::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Инициализация системы шаблонов +проверка в кеше
  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Выгребли информацию
  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $dbdata = $SYS_permissions->users_perms_get_info($id);

  //приводим к читаемому виду компанент
  if (!$dbdata['component'])
    {
    $dbdata['component']='Все';
    }

  $sysTpl->assign($dbdata);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','main'),
                     'displayname'=>"Права доступа");
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','users_perms_confirm_delete',array('id'=>$id)),
                     'displayname'=>"Удаление прав пользователя");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Удаление правила пользователя
*/
function SYS_permissions_admin_users_perms_delete ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_users_perms_delete', BAD_PARAM, 'Id not numeric');

  //Проверка на доступ
  $sysObject = 'SYS_permissions::admin::users_perms::delete::'.$id;
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->users_perms_delete($id);
  //
  sysShowMessage(sysModURL ('SYS_permissions','admin','main'), 'Правило удалено');
  }

/*
 * Увеличение вес правила пользователя
*/
function SYS_permissions_admin_users_perms_moveup ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_users_perms_moveup', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_permissions::admin::users_perms::moveup::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->users_perms_moveup($id);

  sysRedirect(sysModURL ('SYS_permissions','admin','main'));
  }

/*
 * Уменьшыли вес правила пользователя
*/
function SYS_permissions_admin_users_perms_movedown ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_users_perms_movedown', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_permissions::admin::users_perms::movedown::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->users_perms_movedown($id);

  sysRedirect(sysModURL ('SYS_permissions','admin','main'));
  }







/*
 * Добавление новых прав владельцев
*/
function SYS_permissions_admin_owners_perms_new()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::owners_perms::new';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Получили список групп в системе
  sysModClassLoad('SYS_groups');
  $SYS_groups = new SYS_groups;
  $groups_list = $SYS_groups->get_list();
  //Прелбразовали к виду ключ=>значеие
  $outgroupslist=array();
  $outgroupslist[-1]='Все';
  $outgroupslist[0]='Незарегистрированные';
  foreach ($groups_list as $group)
    {
    $outgroupslist[$group['id']] = $group['group_displayname'];
    };
  $sysTpl->assign('groups_list', $outgroupslist);

  //Получили список модулей в системе
  sysModClassLoad('SYS_modules');
  $SYS_modules = new SYS_modules;
  $modules_list = $SYS_modules->get_list();
  //Прелбразовали к виду ключ=>значеие
  $outmoduleslist=array ();
  $outmoduleslist[0]='Все';
  foreach ($modules_list as $module)
    {
    $outmoduleslist[$module['mod_name']] = $module['mod_name'];
    };

  //Получили список блоков в системе
  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $blocks_list = $SYS_blocks->get_list();
  //Прелбразовали к виду ключ=>значеие
  $outblockslist=array();
  foreach ($blocks_list as $block)
    {
    $outblockslist[$block['block_name']] = $block['block_name'];
    };
  //Обьединили два массива
  $complist=array_merge($outmoduleslist, $outblockslist);
  //В шаблон
  $sysTpl->assign('components_list', $complist);

  //Получаем список прав доступа в системе
  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $levels_list = $SYS_permissions->permsLevelList();
  $sysTpl->assign('levels_list', $levels_list);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','main'),
                     'displayname'=>"Права доступа");
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','owners_perms_new'),
                     'displayname'=>"Новые права владельца");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Созданеие новых прав пользователя
*/
function SYS_permissions_admin_owners_perms_create()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::owners_perms::create';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Забрали преременные со входа
  $owners_perms_table = sysDBGetTable('owners_perms');
  $owners_perms_column = sysDBGetColumns($owners_perms_table);
  $indata = sysDBCleanFromInput ($owners_perms_column);

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->owners_perms_create($indata);

  sysShowMessage(sysModURL ('SYS_permissions','admin','main'), 'Права владельца добавленны');
  }

/*
 * Редактирование прав владельца
*/
function SYS_permissions_admin_owners_perms_modify()
  {
  $id = sysVarCleanFromInput ('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_owners_perms_modify', BAD_PARAM, 'Id not numeric');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::owners_perms::modify';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Получили список групп в системе
  sysModClassLoad('SYS_groups');
  $SYS_groups = new SYS_groups;
  $groups_list = $SYS_groups->get_list();

  //Прелбразовали к виду ключ=>значеие
  $outgroupslist=array();
  $outgroupslist[-1]='Все';
  $outgroupslist[0]='Незарегистрированные';
  foreach ($groups_list as $group)
    {
    $outgroupslist[$group['id']] = $group['group_displayname'];
    };
  $sysTpl->assign('groups_list', $outgroupslist);

  //Получили список модулей в системе
  sysModClassLoad('SYS_modules');
  $SYS_modules = new SYS_modules;
  $modules_list = $SYS_modules->get_list();

  //Прелбразовали к виду ключ=>значеие
  $outmoduleslist=array();
  $outmoduleslist[0]='Все';
  foreach ($modules_list as $module)
    {
    $outmoduleslist[$module['mod_name']]=$module['mod_name'];
    };

  //Получили список блоков в системе
  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $blocks_list = $SYS_blocks->get_list();
  //Прелбразовали к виду ключ=>значеие
  $outblockslist=array();
  foreach ($blocks_list as $block)
    {
    $outblockslist[$block['block_name']] = $block['block_name'];
    };
  //Обьединили два массива
  $complist = array_merge($outmoduleslist, $outblockslist);
  //В шаблон
  $sysTpl->assign('components_list', $complist);

  //Получаем список прав доступа в системе
  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $levels_list = $SYS_permissions->permsLevelList();
  $sysTpl->assign('levels_list', $levels_list);

  //Выгребли информацию из БД
  $dbdata = $SYS_permissions->owners_perms_get_info($id);
  $sysTpl->assign($dbdata);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','main'),
                     'displayname'=>"Права доступа");
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','owners_perms_modify', array ('id'=>$id)),
                     'displayname'=>"Редактирование прав владельцев");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Обновление прав пользователя
*/
function SYS_permissions_admin_owners_perms_update()
  {
  //Тело модуля
  $owners_perms_table = sysDBGetTable('owners_perms');
  $owners_perms_column = sysDBGetColumns($owners_perms_table);
  $indata = sysDBCleanFromInput ($owners_perms_column);

  if (!is_numeric ($indata['id'])) sysException ('SYS_permissions_admin_owners_perms_update', BAD_PARAM, 'Id not numeric');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_permissions::admin::owners_perms::update::'.$indata['id'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->owners_perms_update($indata);

  sysShowMessage(sysModURL ('SYS_permissions','admin','main'), 'Права владельца изменены ');
  }

/*
 * Подтверждение удаления прав владельца
*/
function SYS_permissions_admin_owners_perms_confirm_delete()
  {
  //Забираем переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_owners_perms_confirm_delete', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_permissions::admin::owners_perms::confirm_delete::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Инициализация системы шаблонов +проверка в кеше
  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $dbdata = $SYS_permissions->owners_perms_get_info($id);
  //приводим к читаемому виду компанент
  if (!$dbdata['component'])
    {
    $dbdata['component']='Все';
    }
  $sysTpl->assign($dbdata);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','main'),
                     'displayname'=>"Права доступа");
  $browsein[]=array ('url'=>sysModURL('SYS_permissions','admin','owners_perms_confirm_delete', array ('id'=>$id)),
                     'displayname'=>"Удаление прав владельца");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Удаление правила владельца
*/
function SYS_permissions_admin_owners_perms_delete ()
  {
  $id=sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_owners_perms_delete', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_permissions::admin::owners_perms::delete::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->owners_perms_delete($id);

  sysShowMessage(sysModURL ('SYS_permissions','admin','main'), 'Правило удалено');
  }

/*
 * Увеличение вес правила пользователя
*/
function SYS_permissions_admin_owners_perms_moveup ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_owners_perms_moveup', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_permissions::admin::owners_perms::moveup::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->owners_perms_moveup($id);

  sysRedirect(sysModURL ('SYS_permissions','admin','main'));
  }

/*
 * Уменьшыли вес правила пользователя
*/
function SYS_permissions_admin_owners_perms_movedown ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_permissions_admin_owners_perms_movedown', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_permissions::admin::owners_perms::movedown::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_permissions');
  $SYS_permissions = new SYS_permissions;
  $SYS_permissions->owners_perms_movedown($id);

  sysRedirect(sysModURL ('SYS_permissions','admin','main'));
  }

?>