<?php
/*
 * Редирект
*/
function content_xml_main()
  {
  sysRedirect('/content');
  }

/*
 * Список комментариев
*/
function content_xml_rss_list()
  {
  $sysObject = 'content::xml::rss::list';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

  //Работа с sysTpl
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;
   
//  Количество документов в ленте
$doc_count = 10;

//из какого раздела берем новости
$folder_id=1;


  //Учитываем  limit_doc_olderdays  limit_doc_perfolder
  $now = date("Y-m-d H:i:00");

//Таблицы
  $docs_table = sysDBGetTable('content_docs');
  $docs_column =  sysDBGetColumns($docs_table);
  $links_table = sysDBGetTable('content_links');
  $links_column =  sysDBGetColumns($links_table);
  $folders_table = sysDBGetTable('content_folders');
  $folders_column =  sysDBGetColumns($folders_table);

     $order=' DESC';
  $where = ' ('.$links_column['parent_id'].' = '.$folder_id;

    sysModClassLoad ('content');
    $content = new content;
    $folders_list = $content->folder_ItemsTreeBuild($folder_id, 0);
    foreach ($folders_list as $folder)
      $where .= ' OR '.$links_column['parent_id'].' = '.$folder['id'];

  $where.=')';
  
 // $where.=" AND $docs_column[pub_datetime]<'$now' AND $links_column[active]=1";
$doc_orderby=$docs_column['pub_datetime'];

 //Если документ уже показан в контенте, то его не показываем
  $where.=' ORDER BY  '.$doc_orderby.' '.$order.' LIMIT 0, '.$doc_count;


//сам запрос

$sql='SELECT '.$docs_table.'.*,'.$folders_table.'.displayname as folder_displayname, '.$folders_table.'.name as folder_name, '.$links_table.'.*'.' FROM '.$docs_table.','.$links_table.','.$folders_table.' WHERE '.$docs_table.'.id='.$links_table.'.child_id AND '.$links_table.'.parent_id='.$folders_table.'.id AND '.$where;
        $sql_result = mysql_query($sql);

if ($sql_result)
 {    
 for ( $data=array(); $row=mysql_fetch_assoc($sql_result); $data[]=$row );
$docs_list=$data;
}




  $sysTpl->assign('docs_list', $docs_list);
  

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }


function content_xml_atom_list()
  {
  $sysObject = 'content::xml::atom::list';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

  //Работа с sysTpl
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;
   
//  Количество документов в ленте
$doc_count = 10;

//из какого раздела берем новости
$folder_id=1;


  //Учитываем  limit_doc_olderdays  limit_doc_perfolder
  $now = date("Y-m-d H:i:00");

//Таблицы
  $docs_table = sysDBGetTable('content_docs');
  $docs_column =  sysDBGetColumns($docs_table);
  $links_table = sysDBGetTable('content_links');
  $links_column =  sysDBGetColumns($links_table);
  $folders_table = sysDBGetTable('content_folders');
  $folders_column =  sysDBGetColumns($folders_table);

     $order=' DESC';
  $where = ' ('.$links_column['parent_id'].' = '.$folder_id;

    sysModClassLoad ('content');
    $content = new content;
    $folders_list = $content->folder_ItemsTreeBuild($folder_id, 0);
    foreach ($folders_list as $folder)
      $where .= ' OR '.$links_column['parent_id'].' = '.$folder['id'];

  $where.=')';
  
 // $where.=" AND $docs_column[pub_datetime]<'$now' AND $links_column[active]=1";
$doc_orderby=$docs_column['pub_datetime'];

 //Если документ уже показан в контенте, то его не показываем
  $where.=' ORDER BY  '.$doc_orderby.' '.$order.' LIMIT 0, '.$doc_count;


//сам запрос

$sql='SELECT '.$docs_table.'.*,'.$folders_table.'.displayname as folder_displayname, '.$folders_table.'.name as folder_name, '.$links_table.'.*'.' FROM '.$docs_table.','.$links_table.','.$folders_table.' WHERE '.$docs_table.'.id='.$links_table.'.child_id AND '.$links_table.'.parent_id='.$folders_table.'.id AND '.$where;
        $sql_result = mysql_query($sql);

if ($sql_result)
 {    
 for ( $data=array(); $row=mysql_fetch_assoc($sql_result); $data[]=$row );
$docs_list=$data;
}




  $sysTpl->assign('docs_list', $docs_list);
  

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }




?>