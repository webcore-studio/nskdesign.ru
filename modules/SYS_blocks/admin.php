<?php
/*
 * Главное админское меню.
*/
function SYS_blocks_admin_main()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_blocks::admin::main';
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Взяли список с базы
  $blocks_table = sysDBGetTable('blocks');
  $blocks_column =  sysDBGetColumns($blocks_table);
  $dbblockslist = sysDbSelect ($blocks_table, $blocks_column,
          "ORDER BY $blocks_column[block_position], $blocks_column[weight]", true);

  //Взяли список с диска
  $fileblockslist = array();
  $dh = opendir('blocks');
  while ($dir = readdir($dh))
    {
    if ((is_dir("blocks/$dir")) &&
                  ($dir != '.') &&
                  ($dir != '..') &&
                  ($dir != 'CVS') &&
                  (file_exists ("blocks/$dir/info.php")))
      {
      // Found
      $info = array();
      $info['version'] = '0';
      $info['description'] = '';
      include("blocks/$dir/info.php");
      $info['name'] = $dir;
      array_push ($fileblockslist, $info);
      }
    }
  closedir($dh);
  //Теперь в $dbblockslist - модули в бд, $fileblockslist - модули с диска
  //Отображаем список установленных модулей

  //Взяли список проинсталеных блоков
  if (isset ($dbblockslist))
    {
    //Позиция
    $positions['l']='Слева';
    $positions['r']='Справа';
    $positions['t']='Сверху';
    $positions['b']='Снизу';
    $positions['c']='Поцентру';
    $positions['a']='Реклама';
	$positions['e']='Фиксированные';

    foreach ($dbblockslist as $key=>$block)
      {
      //передвижка весов
      $MaxWeight = sysDbMaxWeight ($blocks_table, "WHERE $blocks_column[block_position] = '$block[block_position]'");
      if ($MaxWeight>1)//Если адин нехир двигать
        {
        if ($block['weight']>1)
          {
          $block['weight_moveup_url']=sysModUrl ('SYS_blocks','admin','moveup', array ('id'=>$block['id']));
          };

        if ($block['weight']<$MaxWeight)
          {
          $block['weight_movedown_url']=sysModUrl ('SYS_blocks','admin','movedown', array ('id'=>$block['id']));
          };
        }
      //Прелбразовали к человеческому виду
      $block['block_position'] = $positions[$block['block_position']];
      //Внесли изминения
      $dbblockslist[$key]=$block;
      }
    $sysTpl->assign('blocks_list_installed', $dbblockslist);
    };

  //Теперь отображаем список всех блоков в системе
  $sysTpl->assign('blocks_list_all', $fileblockslist);

  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('SYS_blocks','admin','main'),
                       'displayname'=>'Администрирование блоков');
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

/*
 * Просмотр информации о блоке
*/
function SYS_blocks_admin_info()
  {
  $block_name = sysVarCleanFromInput('block_name');
  $id = sysVarCleanFromInput('id');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_blocks::admin::info';

  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  if (is_numeric($id))
    {
    $blockinfo = $SYS_blocks->get_info ($id);
    }
    else
      {
      $blockinfo = $SYS_blocks->get_info ($block_name);
      }

  $sysTpl->assign($blockinfo);

  //BrowseIn
  $browsein=array();
  $browsein[]=array ('url'=>sysModURL('SYS_blocks','admin','main'),
                     'displayname'=>'Администрирование блоков');
  $browsein[]=array ('url'=>sysModURL('SYS_blocks','admin','main'),
                     'displayname'=>'Информация о блоке "'.$blockinfo['block_name'].'"');
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Добавление блока
*/
function SYS_blocks_admin_add()
  {
  $block_name = sysVarCleanFromInput('block_name');
  if (empty ($block_name))
    {
    sysException ('SYS_blocks_admin_add', BAD_PARAM, "\$block_name - $block_name");
    };

  $sysObject = 'SYS_blocks::admin::add::'.$name;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $SYS_blocks->add($block_name);

  sysShowMessage(sysModURL ('SYS_blocks','admin','main'), 'Блок добавлен');
  }

/*
 * Удаление блоку
*/
function SYS_blocks_admin_delete()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_blocks_admin_delete', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_blocks::admin::delete::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $SYS_blocks->delete($id);

  sysShowMessage(sysModURL ('SYS_blocks','admin','main'), 'Блок удален');
  }

/*
 * Активация блока
*/
function SYS_blocks_admin_activate()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_blocks_admin_activate', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_blocks::admin::activate::'.$id;

  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $SYS_blocks->activate($id);

  sysShowMessage(sysModURL ('SYS_blocks','admin','main'), 'Блок активирован');
  }

/*
 * ДеАктивация 
*/
function SYS_blocks_admin_deactivate()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_blocks_admin_deactivate', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_blocks::admin::deactivate::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $SYS_blocks->deactivate($id);

  sysShowMessage(sysModURL ('SYS_blocks','admin','main'), 'Блок деактивирован');
  };

/*
 * Редактирование информации о блоке
*/
function SYS_blocks_admin_modify()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_blocks_admin_modify', BAD_PARAM, 'Id not numeric');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_blocks::admin::modify::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Тело модуля
  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $blockinfo = $SYS_blocks->get_info($id);

  //Ввод стандартных данных для блока
  $sysTpl->assign($blockinfo);

  //Позиция
  $positions['l']='Слева';
  $positions['r']='Справа';
  $positions['t']='Сверху';
  $positions['b']='Снизу';
  $positions['c']='По центру';
  $positions['e']='Фиксированные';
  $sysTpl->assign('positions', $positions);

  //Получаем список доступных языков системы
  sysExtLibLoad();
  $langlist = sysGetLangList();
  $sysTpl->assign('langlist', $langlist);

  //Предаем управление модулю для запроса параметров спецыфических для модуля
  $initfile='blocks/'.$blockinfo['block_name'].'/init.php';
  if (file_exists ($initfile))
    {
    include_once($initfile);
    };
  //Передаем управление блоку
  $func = $blockinfo['block_name'] . '_modify';
  if (function_exists($func))
    {
    if (($blockModifyInput=$func($blockinfo)) == true)
      {
      $sysTpl->assign('blockModifyInput', $blockModifyInput);
      }
    }

  //BrowseIn
  $browsein=array();
  $browsein[]=array ('url'=>sysModURL('SYS_blocks','admin','main'),
                     'displayname'=>'Администрирование блоков');
  $browsein[]=array ('url'=>sysModURL('SYS_blocks','admin','main'),
                     'displayname'=>'Редактирование блока "'.$blockinfo['block_name'].'"');
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Внесение изминений в блок
*/
function SYS_blocks_admin_update()
  {
  //Забираем переменные
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_blocks_admin_update', BAD_PARAM, 'Id not numeric');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_blocks::admin::update::'.$id;
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Забираем переменные со входа
  $blocks_table = sysDBGetTable('blocks');
  $blocks_column = sysDBGetColumns($blocks_table);
  $dbindata = sysDBCleanFromInput($blocks_column);

  //Тело модуля
  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $SYS_blocks->update($dbindata);

  sysShowMessage(sysModURL ('SYS_blocks','admin','main'), 'Изменения сохранены');
  }

/*
 * Увеличение вес
*/
function SYS_blocks_admin_moveup ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_blocks_admin_moveup', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_blocks::admin::moveup::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Тело модуля
  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $SYS_blocks->moveup($id);

  sysRedirect(sysModURL ('SYS_blocks','admin','main'));
  }

/*
 * Уменьшыли вес
*/
function SYS_blocks_admin_movedown ()
  {
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_blocks_admin_movedown', BAD_PARAM, 'Id not numeric');

  $sysObject = 'SYS_blocks::admin::movedown::'.$id;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Тело модуля
  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $SYS_blocks->movedown($id);

  sysRedirect(sysModURL ('SYS_blocks','admin','main'));
  }

?>