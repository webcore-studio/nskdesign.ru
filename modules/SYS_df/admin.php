<?php
/*
 * Модуль - чуть изврашенный :))
 * С динамическими полями работать не умеет, т.к. выступает в качестве промежуточьной
 * прослойки доступа к классу системы.
 * Display функции работают с именами полей, в класс загоняються в формате класса
*/

/*
 * Главная админская страница
*/
function SYS_df_admin_main()
  {
  //Прелюдие как у всех модулей
  $sysObject = 'SYS_df::admin::main';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  sysModClassLoad('SYS_df');
  $SYS_df = new SYS_df;
  $tables_list = $SYS_df->get_tables_list();

  $sysTpl->assign('tables_list', $tables_list);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_df','admin','main'),
                     'displayname'=>'Динамические поля');
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

/*
 * Просмотр таблицы
*/
function SYS_df_admin_view()
  {
  $table_name = sysVarCleanFromInput('table_name');
  if (empty ($table_name)) sysException ('SYS_df_admin_view', BAD_PARAM, 'Tablename empty');

  //Прелюдие как у всех модулей
  $sysObject = 'SYS_df::admin::view::'.$table_name;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  $sysTpl->assign('table_name', $table_name);

  sysModClassLoad('SYS_df');
  $SYS_df = new SYS_df;
  $fields_list = $SYS_df->get_fields_list($table_name);

  $sysTpl->assign('table_fields', $fields_list);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_df','admin','main'),
                     'displayname'=>"Динамические поля");
  $browsein[]=array ('url'=>sysModURL('SYS_df','admin','view',array ('table_name'=>$table_name)),
                     'displayname'=>" Редактирование таблицы \"$table_name\"");
  $sysTpl->assign('browsein', $browsein);

  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Новое поле
*/
function SYS_df_admin_new()
  {
  $table_name = sysVarCleanFromInput('table_name');
  if (empty ($table_name)) sysException ('SYS_df_admin_new', BAD_PARAM, 'Tablename empty');

  $sysObject = 'SYS_df::admin::new::'.$table_name;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysTpl = new sysTpl;
  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl->caching = false;

  $sysTpl->assign('table_name', $table_name);

  $sysTpl->assign('table_types', array ('VARCHAR'=>'VARCHAR',
                                        'TINYINT'=>'TINYINT',
                                        'TEXT'=>'TEXT',
                                        'DATE'=>'DATE',
                                        'SMALLINT'=>'SMALLINT',
                                        'MEDIUMINT'=>'MEDIUMINT',
                                        'INT'=>'INT',
                                        'BIGINT'=>'BIGINT',
                                        'FLOAT'=>'FLOAT',
                                        'DOUBLE'=>'DOUBLE',
                                        'DECIMAL'=>'DECIMAL',
                                        'DATETIME'=>'DATETIME',
                                        'TIMESTAMP'=>'TIMESTAMP',
                                        'TIME'=>'TIME',
                                        'YEAR'=>'YEAR',
                                        'CHAR'=>'CHAR',
                                        'TINYBLOB'=>'TINYBLOB',
                                        'TINYTEXT'=>'TINYTEXT',
                                        'BLOB'=>'BLOB',
                                        'MEDIUMBLOB'=>'MEDIUMBLOB',
                                        'MEDIUMTEXT'=>'MEDIUMTEXT',
                                        'LONGBLOB'=>'LONGBLOB',
                                        'LONGTEXT'=>'LONGTEXT',
                                        'ENUM'=>'ENUM',
                                        'SET'=>'SET'));

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_df','admin','main'),
                     'displayname'=>"Динамические поля");
  $browsein[]=array ('url'=>sysModURL('SYS_df','admin','view', array ('table_name'=>$table_name)),
                     'displayname'=>$table_name);
  $browsein[]=array ('url'=>sysModURL('SYS_df','admin','new', array ('table'=>$table_name)),
                     'displayname'=>"Добавление поля в таблицу \"$table_name\"");
  $sysTpl->assign('browsein', $browsein);


  //Возвращаем результат
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Добавления поля в таблицу
*/
function SYS_df_admin_create()
  {
  //переменные со входа
  $tables_table = sysDBGetTable('tables');
  $tables_column = sysDBGetColumns($tables_table);
  $indata = sysDBCleanFromInput ($tables_column);
  $indata['table_candelete'] = true;
  $indata['table_canmodify']=true;
  //Ключи Преобразуем
  $key = sysVarCleanFromInput('key');
  if ($key=='table_index'){$indata['table_index']=true;};
  if ($key=='table_unique'){$indata['table_unique']=true;};

  //проверки
  if (empty ($indata['table_name'])) sysException ('SYS_df_admin_create', BAD_PARAM, 'Table name empty');
  if (empty ($indata['table_field'])) sysException ('SYS_df_admin_create', BAD_PARAM, 'Table field empty');

  //Имя обьекта
  $sysObject = 'SYS_df::admin::create::'.$indata['table_name'].'::'.$indata['table_field'];
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Вызываем функцию ядра для создания поля
  sysModClassLoad('SYS_df');
  $SYS_df = new SYS_df;
  $SYS_df->CreateField(array ('table'       =>$indata['table_name'],
                              'field'       =>$indata['table_field'],
                              'type'        =>$indata['table_type'],
                              'size'        =>$indata['table_size'],
                              'default'     =>$indata['table_default'],
                              'null'        =>$indata['table_null'],
                              'unsigned'    =>$indata['table_unsigned'],
                              'increment'   =>$indata['table_increment'],
                              'primary_key' =>$indata['table_primary_key'],
                              'index'       =>$indata['table_index'],
                              'unique'      =>$indata['table_unique'],
                              'fulltext'    =>$indata['table_fulltext'],
                              'candelete'   =>$indata['table_candelete'],
                              'canmodify'   =>$indata['table_canmodify']
                            ));

  sysShowMessage(sysModURL ('SYS_df', 'admin', 'view', array ('table_name'=>$indata['table_name'])), 'Поле добавленно');
  }

/*
 * Подтверждение удаления поля
*/
function SYS_df_admin_confirm_delete()
  {
  //Забрали переменные со входа
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('SYS_df_admin_confirm_delete', BAD_PARAM, 'Id not numeric');

  //Выборка
  sysModClassLoad('SYS_df');
  $SYS_df = new SYS_df;
  $dbdata = $SYS_df->get_info($id);
  $tpldata = array ('table_name' => $dbdata['table'],
                    'table_field' => $dbdata['field'],
                    'table_type' => $dbdata['type'],
                    'table_size' => $dbdata['size'],
                    'table_default' => $dbdata['default'],
                    'table_null' => $dbdata['null'],
                    'table_unsigned' => $dbdata['unsigned'],
                    'table_increment' => $dbdata['increment'],
                    'table_primary_key' => $dbdata['primary_key'],
                    'table_index' => $dbdata['index'],
                    'table_unique' => $dbdata['unique'],
                    'table_fulltext' => $dbdata['fulltext'],
                    'table_candelete' => $dbdata['candelete'],
                    'table_canmodify' => $dbdata['canmodify']
                   );
  //

  $sysObject = "SYS_df::admin::confirm_delete::$tpldata[table_name]::$tpldata[table_field]";
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  $sysModTpl=sysTplWay ($sysObject);
  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  $sysTpl->assign($tpldata);

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_df','admin','main'),
                     'displayname'=>"Динамические поля");
  $browsein[]=array ('url'=>sysModURL('SYS_df','admin','view', array ('table_name'=>$tpldata['table_name'])),
                     'displayname'=>$tpldata['table_name']);
  $browsein[]=array ('url'=>sysModURL('SYS_df','admin','confirm_delete', array ('id'=>$tpldata['id'])),
                     'displayname'=>"Удаление поля из таблицы \"$tpldata[table_name]\"");
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  };

/*
 * Удаление поля
*/
function SYS_df_admin_delete()
  {
  //Забрали переменные со входа
  list ($table_name, $table_field) = sysVarCleanFromInput('table_name', 'table_field');

  if (empty ($table_name) || empty ($table_field))//Проверка
    {
    sysException ('SYS_df_admin_delete', BAD_PARAM);
    };

  $sysObject = "SYS_df::admin::delete::$table_name::$table_field";
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Функция ядра для удаления полей
  sysModClassLoad('SYS_df');
  $SYS_df = new SYS_df;
  $SYS_df->DeleteField ($table_name, $table_field);

  //Мессага, что усе удалено
  sysShowMessage(sysModURL ('SYS_df', 'admin', 'view', array ('table_name'=>$table_name)), 'Поле удалено');
  };

/*
 * Редактирование поля
*/
function SYS_df_admin_modify()
  {
  //Забрали переменные со входа
  $id = sysVarCleanFromInput('id');
  if (!is_numeric ($id))//Проверка на интегер
    {
    if (!is_numeric ($id)) sysException ('SYS_df_admin_modify', BAD_PARAM, 'Id not numeric');
    };
  //Выборка
  sysModClassLoad('SYS_df');
  $SYS_df = new SYS_df;
  $dbdata = $SYS_df->get_info($id);
  $tpldata = array ('id' => $dbdata['id'],
                    'table_name' => $dbdata['table'],
                    'table_field' => $dbdata['field'],
                    'table_type' => $dbdata['type'],
                    'table_size' => $dbdata['size'],
                    'table_default' => $dbdata['default'],
                    'table_null' => $dbdata['null'],
                    'table_unsigned' => $dbdata['unsigned'],
                    'table_increment' => $dbdata['increment'],
                    'table_primary_key' => $dbdata['primary_key'],
                    'table_index' => $dbdata['index'],
                    'table_unique' => $dbdata['unique'],
                    'table_fulltext' => $dbdata['fulltext'],
                    'table_candelete' => $dbdata['candelete'],
                    'table_canmodify' => $dbdata['canmodify']
                   );
  //
  $sysObject = "SYS_df::admin::modify::$tpldata[table_name]::$tpldata[table_field]";
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;
  $sysModTpl = sysTplWay ($sysObject);

  $sysTpl = new sysTpl;
  $sysTpl->caching = false;

  //Ассигним
  $sysTpl->assign($tpldata);
  $sysTpl->assign('field_types', array ('VARCHAR'=>'VARCHAR',
                                        'TINYINT'=>'TINYINT',
                                        'TEXT'=>'TEXT',
                                        'DATE'=>'DATE',
                                        'SMALLINT'=>'SMALLINT',
                                        'MEDIUMINT'=>'MEDIUMINT',
                                        'INT'=>'INT',
                                        'BIGINT'=>'BIGINT',
                                        'FLOAT'=>'FLOAT',
                                        'DOUBLE'=>'DOUBLE',
                                        'DECIMAL'=>'DECIMAL',
                                        'DATETIME'=>'DATETIME',
                                        'TIMESTAMP'=>'TIMESTAMP',
                                        'TIME'=>'TIME',
                                        'YEAR'=>'YEAR',
                                        'CHAR'=>'CHAR',
                                        'TINYBLOB'=>'TINYBLOB',
                                        'TINYTEXT'=>'TINYTEXT',
                                        'BLOB'=>'BLOB',
                                        'MEDIUMBLOB'=>'MEDIUMBLOB',
                                        'MEDIUMTEXT'=>'MEDIUMTEXT',
                                        'LONGBLOB'=>'LONGBLOB',
                                        'LONGTEXT'=>'LONGTEXT',
                                        'ENUM'=>'ENUM',
                                        'SET'=>'SET'));

  //BrowseIn
  $browsein[]=array ('url'=>sysModURL('SYS_df','admin','main'),
                     'displayname'=>"Динамические поля");
  $browsein[]=array ('url'=>sysModURL('SYS_df','admin','view', array ('table_name'=>$tpldata['table_name'])),
                     'displayname'=>$tpldata['table_name']);
  $browsein[]=array ('url'=>sysModURL('SYS_df','admin','modify', array ('id'=>$tpldata['id'])),
                     'displayname'=>"Редактирование поля таблицы \"$tpldata[table_name]\"");
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  };

/*
 * Обновление поля в таблицу
*/
function SYS_df_admin_update()
  {
  //переменные со входа
  $tables_table = sysDBGetTable('tables');
  $tables_column = sysDBGetColumns($tables_table);
  $indata = sysDBCleanFromInput ($tables_column);
  //Ключи Преобразуем
  $key = sysVarCleanFromInput('key');
  if ($key=='table_index'){$indata['table_index']=true;};
  if ($key=='table_unique'){$indata['table_unique']=true;};

  //проверки
  if (empty ($indata['table_name'])) sysException ('SYS_df_admin_update', BAD_PARAM, 'Table name empty');
  if (empty ($indata['table_field'])) sysException ('SYS_df_admin_update', BAD_PARAM, 'Table field empty');

  //Выгребли старую информацию
  sysModClassLoad('SYS_df');
  $SYS_df = new SYS_df;
  $dbdata = $SYS_df->get_info($indata['id']);


  //Имя обьекта
  $sysObject = "SYS_df::admin::update::$dbdata[table]::$dbdata[field]";
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

  //Функция ядра для редакирование поля
  $SYS_df->UpdateField(array ('id'       =>$indata['id'],
                              'table'       =>$indata['table_name'],
                              'field'       =>$indata['table_field'],
                              'type'        =>$indata['table_type'],
                              'size'        =>$indata['table_size'],
                              'default'     =>$indata['table_default'],
                              'null'        =>$indata['table_null'],
                              'unsigned'    =>$indata['table_unsigned'],
                              'increment'   =>$indata['table_increment'],
                              'primary_key' =>$indata['table_primary_key'],
                              'index'       =>$indata['table_index'],
                              'unique'      =>$indata['table_unique'],
                              'fulltext'    =>$indata['table_fulltext']
                            ));

  sysShowMessage(sysModURL ('SYS_df', 'admin', 'view', array ('table_name'=>$dbdata['table'])), 'Поле изменено');
  }


?>