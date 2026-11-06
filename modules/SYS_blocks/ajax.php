<?php
/*
 * Главное админское меню.
*/

/*
 * Внесение изминений в блок
*/
function SYS_blocks_ajax_update()
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

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_blocks::admin::modify::'.$id;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  $blockinfo = $SYS_blocks->get_info($id);

  //Ввод стандартных данных для блока
  $sysTpl->assign($blockinfo);

  //Позиция
  $positions['l']='Слева';
  $positions['r']='Справа';
  $positions['t']='Сверху';
  $positions['b']='Снизу';
  $positions['c']='Поцентру';
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
 * Увеличение вес
*/
function SYS_blocks_ajax_moveup ()
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
function SYS_blocks_ajax_movedown ()
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