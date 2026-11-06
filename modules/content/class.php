<?php
class content {
  /*
   * Переменные
  */



//
// Функции
//

//
// Получаем конфигурацию сайта
//
function get_content_config() {
  global $dbconn;
    $config_table = sysDBGetTable('content_config');
    $sql = "SELECT * FROM ".$config_table;

    $sql_result = mysqli_query($dbconn, $sql);
      if (mysqli_connect_errno()) sysException ('content->get_content_config', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
    for ( $data=array(); $row=mysqli_fetch_assoc($sql_result); $data[]=$row );
    $configs = array();

    for ($i = 0; $i < count($data); $i++) {
        $new = array(reset($data[$i]) => next($data[$i]) );
    $configs += $new;
    }

    return $configs;
  }

//
// Сохраняет настройки
//
function save_config($name,$value) {
  global $dbconn;
    $config_table = sysDBGetTable('content_config');
    $sql = "UPDATE ".$config_table." SET config_value = '".$value."' WHERE config_name = '".$name."'";
    mysqli_query($dbconn, $sql);
      if (mysqli_connect_errno()) sysException ('content->save_config', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
            
    return true;
  }

//
// Узнаём, к каким подразделам относится документ
//
function get_documents_folders($id) {
  global $dbconn;
    $links_table = sysDBGetTable('content_links');
    $sql = "SELECT parent_id FROM ".$links_table."
            WHERE child_id = ".$id;
    $sql_result = mysqli_query($dbconn, $sql );
      if (mysqli_connect_errno()) sysException ('content->get_documents_links', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

    for ( $data=array(); $row=mysqli_fetch_assoc($sql_result); $data[]=$row );
    $count = count($data);
    $folders = array();
    for ($i = 0; $i < $count; $i++)
        $folders = array_merge($folders,array($data[$i]["parent_id"]));

    return $folders;
}

//
// Функция получает два параметра, номер документа child_id и номер каталога parent_id
// и возврашает true если такая ссылка существует и false в противном случае.
//
function exists_links($child_id,$parent_id) {
  global $dbconn;
    $links_table = sysDBGetTable('content_links');
    $sql = "SELECT * FROM ".$links_table."
            WHERE child_id = ".$child_id."
            AND parent_id = ".$parent_id;
    $sql_result = mysqli_query($dbconn, $sql );
      if (mysqli_connect_errno()) sysException ('content->exists_links', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

    return mysqli_num_rows( $sql_result );
}

//
// Функция возврашает массив данных о ссылке
//
function get_link($child_id,$parent_id) {
  global $dbconn;
    $links_table = sysDBGetTable('content_links');
    $link = array();
    $sql = "SELECT * FROM `".$links_table."`
            WHERE `child_id` = ".$child_id."
            AND `parent_id` = ".$parent_id;
    $sql_result = mysqli_query($dbconn, $sql );

            if (mysqli_connect_errno()) sysException ('content->get_link', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
    // Если ссылки нет, то возвращаем false
    if ( mysqli_num_rows( $sql_result ) == 0 ) return false;

    for ( $data=array(); $row=mysqli_fetch_assoc($sql_result); $data[]=$row );
        $link["id"] = $data[0]["id"];
        $link["child_id"] = $data[0]["child_id"];
        $link["weight"] = $data[0]["weight"];
        $link["parent_id"] = $data[0]["parent_id"];
        $link["active"] = $data[0]["active"];
        $link["uid"] = $data[0]["uid"];

    return $link;
}


//
// Переделываем ссылки на документ
// id - id документа в content_docs
// fids - массив, в котором индекс - это номер узла, значение либо 1 либо 0 (есть или нет ссылки)
// fid - значение  parent_id в content_links для нашего документа
//
function doc_links($id,$fids,$fid) {
  global $dbconn;
    $links_table = sysDBGetTable('content_links');
    $folders_table = sysDBGetTable('content_folders');
    $docs_table = sysDBGetTable('content_docs');

    // Составим списки ссылок на добавление и удаление.
    // Начинаем цикл с еденицы, так как в корне документов быть не может
    $add = array();
    $delete = array();
    //for($i = 1; $i < count($fids); $i++)
	foreach($fids as $key => $value)
	{
        if ( $value == 1 )
            $add = array_merge($add,array($key));
        elseif ($value == 0 )
            $delete = array_merge($delete,array($key));
    }

    // Получаем данные на исходный документ.
    $curdoc = $this->get_link($id,$fid);


    // Добавляем ссылки.
    for ( $i = 0; $i < count($add); $i++ ) {
        // Добавляем ссылку в раздел в котором её ещё нет и в который требуется.
        if (!$this->exists_links($id,$add[$i])) {
            // Находим и задаем самый большой вес
            $MaxWeight = sysDbMaxWeight ($links_table, "WHERE parent_id = ".$add[$i]);
            $MaxWeight++;

            $sql = "INSERT INTO ".$links_table."
            (weight,parent_id,child_id,uid,active)
            VALUES
            (
             ".$MaxWeight.",
             ".$add[$i].",
             ".$id.",
             ".$curdoc["uid"].",
             ".$curdoc["active"]."
            )";
            mysqli_query($dbconn, $sql );
            if (mysqli_connect_errno()) sysException ('content->doc_links', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

            // Если документ активен, то увеличиваем счётчик раздела, в котором он находится.
            if ($curdoc['active']) {
                $sql = "UPDATE ".$folders_table." SET subdoc_counter = subdoc_counter + 1
                        WHERE id = ".$add[$i];
                mysqli_query($dbconn, $sql );
                if (mysqli_connect_errno()) sysException ('content->doc_links', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
            }
        }
    } // Всё, добавили ссылки.

    // А теперь удаляем ссылки.
    for ( $i = 0; $i < count($delete); $i++ ) {
        // Получаем информацию о ссылке.
        $link_info = $this->get_link($id,$delete[$i]);
        // Если ссылка существует, то продолжаем
        if ($link_info != false) {
            // Если ссылка активна, то уменьшаем счётчик в каталоге, к которому она принадлежит.
            if ($link_info['active']) {
                $sql = "UPDATE ".$folders_table." SET subdoc_counter = subdoc_counter - 1
                        WHERE id = ".$link_info["parent_id"];
                mysqli_query($dbconn, $sql );
                if (mysqli_connect_errno()) sysException ('content->doc_links', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
            }
            // Собственно, удаляем саму ссылку.
            $sql = "DELETE FROM ".$links_table."
            WHERE child_id = ".$id."
            AND parent_id = ".$link_info["parent_id"];
            mysqli_query($dbconn, $sql);
            if (mysqli_connect_errno()) sysException ('content->doc_links', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

            // Считаем количество оставшихся ссылок на документ
            $sql = "SELECT id FROM ".$links_table."
                    WHERE child_id = ".$id;
            $result = mysqli_query($dbconn, $sql);
            if (mysqli_connect_errno()) sysException ('content->doc_links', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
            $links_count = mysqli_num_rows($result);

            // Если ссылок больше нет, то документ удаляем
         /*   if ( $links_count == 0 ) {
                $sql = "DELETE FROM ".$docs_table."
                        WHERE id = ".$id;
                mysql_query($sql);
                if (mysql_errno() != 0) sysException ('content->doc_links', DATABASE_ERROR, mysql_error()."<br/> $sql");

                // Информация о документе, для очистки кэша
                $doc_info = $this->doc_get_info($id);
                $parents_list =& $this->parentsList (null, $doc_info['id']);
                $this->clear_cache ($parents_list);
            }*/
        }
    }
}

// Возвращает масив родителей для документа id в директории fid (смотрим в links_table)
  function parentsList2($id, $fid) {
    $folders_table = sysDBGetTable('content_folders');
    $folders_column =  sysDBGetColumns($folders_table);
    $docs_table = sysDBGetTable('content_docs');
    $docs_column =  sysDBGetColumns($docs_table);
    $links_table = sysDBGetTable('content_links');
    $links_column =  sysDBGetColumns($links_table);

    global $dbconn;

     // Получаем документ
     $parents_list=array();
     $sql = "SELECT ".$docs_column['id'].",".$docs_column['displayname']." ,".$docs_column['name']." as dname,".$folders_column['name']." as fname ".
	" FROM ".$docs_table.",".$folders_table." WHERE ".$docs_column['id']." = ".$id." AND ".$folders_column['id']."=".$fid;

  $sql_result = mysqli_query($dbconn, $sql);
  if (mysqli_connect_errno()) sysException ('content->parentsList2', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

     list($_id, $displayname,$dname,$fname) = mysqli_fetch_row($sql_result);
        mysqli_free_result($sql_result);


        $parent_info = array();
        $parent_info['id'] = $_id;
        $parent_info['type'] = 'doc';
   //     $parent_info['url'] = sysModURL('content','user','doc_view',array ('id'=>$_id,'fid'=>$fid));
if (($dname) and ($fname)) $parent_info['url'] = "/".$fname."/".$dname.".html";
elseif ((!$dname) and ($fname)) $parent_info['url'] = "/".$fname."/".$_id.".html";
elseif (($dname) and (!$fname)) $parent_info['url'] = "/".$fid."/".$dname.".html";
elseif ((!$dname) and (!$fname)) $parent_info['url'] = "/".$fid."/".$_id.".html";

        $parent_info['displayname'] = $displayname;

     $sql = "SELECT active FROM ".$links_table."
             WHERE child_id = ".$id."
             AND parent_id = ".$fid;
     $sql_result = mysqli_query($dbconn, $sql);
        if (mysqli_connect_errno()) sysException ('content->parentsList2', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

     list($active) = mysqli_fetch_row($sql_result);
     mysqli_free_result($sql_result);
        $parent_info['active'] = $active;
     $parents_list[] = $parent_info;

     // Получаем все остальные
     $parent_id = $fid;
     while ( $parent_id != 0 ) {
     // Выбираем все каталоги
        $sql = "SELECT id,displayname,parent_id,active,name FROM ".$folders_table." WHERE id = ".$parent_id;
        $sql_result = mysqli_query($dbconn, $sql);
        if (mysqli_connect_errno()) sysException ('content->parentsList2', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

        list($_id, $displayname, $parent_id, $active,$name) = mysqli_fetch_row($sql_result);
        mysqli_free_result($sql_result);

        $parent_info = array();
        $parent_info['id'] = $_id;
        $parent_info['type'] = 'folder';
   //     $parent_info['url'] = sysModURL('content','user','folder_view',array ('id'=>$_id));
if ($name) $parent_info['url'] = '/'.$name;
else $parent_info['url'] = '/'.$_id;

        $parent_info['displayname'] = $displayname;
        $parent_info['active'] = $active;
        $parents_list[] = $parent_info;
     }

     $parents_list = array_reverse ($parents_list);
//     sys_log('content->parentsList2', LOG_DEBUG, "", $parents_list);
     return $parents_list;
  }

//
// Возвращает масив родителей (папки, документа или поддокумента)
// fid
// did
// sdid
//
  function parentsList($fid = null, $did = null, $sdid = null)
    {
    $parents_list=array();

    //Кешируем в памяти
    //static $loaded = array();
    //if (isset ($loaded[$fid.$did.$sdid]))  return $loaded[$fid.$did.$sdid];

    global $dbconn;
    //Нужны почти всегда
    $docs_table = sysDBGetTable('content_docs');
    $docs_column =  sysDBGetColumns($docs_table);

    //Если переданно ID страницы выгребаем его из базы
    if (is_numeric($sdid))
      {
      //Делаем выборку
      $sql = "SELECT $docs_column[id], $docs_column[displayname], $docs_column[parent_id], $docs_column[fid], $docs_column[active]
                FROM $docs_table WHERE $docs_column[id]= '$sdid'";
      $sql_result = mysqli_query($dbconn, $sql);
      
      if (mysqli_connect_errno()) sysException ('content->parentsList', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

      list($id, $displayname, $parent_id, $fid, $active) = mysqli_fetch_row($sql_result);
      mysqli_free_result($sql_result);

      //Нашли первыэ элимент
      $parent_info = array();
      $parent_info['id'] = $id;
      $parent_info['type'] = 'subdoc';
      $parent_info['url'] = sysModURL('content','user','subdoc_view',array ('id'=>$id));
      $parent_info['displayname'] = $displayname;
      $parent_info['active'] = $active;
      $parents_list[] = $parent_info;
      while ($parent_id!=0)
        {
        //Делаем выборку
        $sql = "SELECT $docs_column[id], $docs_column[displayname], $docs_column[parent_id], $docs_column[active]
                  FROM $docs_table WHERE $docs_column[id]='$parent_id'";
        $sql_result = mysqli_query($dbconn, $sql);
        if (mysqli_connect_errno()) sysException ('content->parentsList', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

        list($id, $displayname, $parent_id, $active) = mysqli_fetch_row($sql_result);
        mysqli_free_result($sql_result);

        //Нашли пркдведущий элимент
        $parent_info = array();
        $parent_info['id'] = (int)$id;
        $parent_info['type'] = 'subdoc';
        $parent_info['url'] = sysModURL('content','user','subdoc_view',array ('id'=>$id));
        $parent_info['displayname'] = $displayname;
        $parent_info['active'] = $active;
        $parents_list[] = $parent_info;
        };
      //Последний под документ - это документ задаем тип соответственно
      $parent_info = $parents_list[count ($parents_list)-1];
      $parent_info['type'] = 'doc';
      $parent_info['url'] = sysModURL('content','user','doc_view',array ('id'=>$parent_info['id']));
      $parents_list[count ($parents_list)-1] = $parent_info;
      //Теперь пусть fid достраивает папки fid
      };

    //Если переданно ID документа выгребаем его из базы
    if (is_numeric($did))
      {
      //Делаем выборку
      $sql = "SELECT $docs_column[id], $docs_column[displayname], $docs_column[parent_id], $docs_column[fid], $docs_column[active]
                FROM $docs_table WHERE $docs_column[id] = '$did'";
      $sql_result = mysqli_query($dbconn, $sql);
      if (mysqli_connect_errno()) sysException ('content->parentsList', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

      list($id, $displayname, $parent_id, $fid, $active) = mysqli_fetch_row($sql_result);
      mysqli_free_result($sql_result);

      //Нашли наш документ
      $parent_info = array();
      $parent_info['id'] = (int)$id;
      $parent_info['type'] = 'doc';
      $parent_info['url'] = sysModURL('content','user','doc_view',array ('id'=>$id));
      $parent_info['displayname'] = $displayname;
      $parent_info['active'] = $active;
      $parents_list[] = $parent_info;
      //Теперь пусть fid достраивает папки fid

      };

    //Теперm если переданно fid выгребаем список родительских папок
    if (is_numeric($fid))
      {
      $folders_table = sysDBGetTable('content_folders');
      $folders_column =  sysDBGetColumns($folders_table);
      //Делаем выборку
      $sql = "SELECT $folders_column[id], $folders_column[displayname], $folders_column[name], $folders_column[parent_id], $folders_column[active]
                FROM $folders_table WHERE $folders_column[id]='$fid'";
      $sql_result = mysqli_query($dbconn, $sql);
      if (mysqli_connect_errno()) sysException ('content->parentsList', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

      list($id, $displayname, $name, $parent_id, $active) = mysqli_fetch_row($sql_result);
      mysqli_free_result($sql_result);

      //Нашли первыэ элимент
      $parent_info=array();
      $parent_info['id'] = (int)$id;
	  $parent_info['name'] = $name;
      $parent_info['type'] = 'folder';
	  if ($name) $parent_info['url']="/$name";
		else $parent_info['url']="/$id";
      $parent_info['displayname'] = $displayname;
      $parent_info['active'] = $active;
      $parents_list[] = $parent_info;
      while ($parent_id!=0)
        {
        //Делаем выборку
        $sql = "SELECT $folders_column[id], $folders_column[displayname], $folders_column[parent_id], $folders_column[active]
                  FROM $folders_table WHERE $folders_column[id]='$parent_id'";
        $sql_result = mysqli_query($dbconn, $sql);
        if (mysqli_connect_errno()) sysException ('content->parentsList', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

        list($id, $displayname, $parent_id, $active) = mysqli_fetch_row($sql_result);
        mysqli_free_result($sql_result);

        //Нашли пркдведущий элимент
        $parent_info = array();
        $parent_info['id'] = (int)$id;
        $parent_info['type'] = 'folder';
        $parent_info['url'] = sysModURL('content','user','folder_view',array ('id'=>$id));
        $parent_info['displayname'] = $displayname;
        $parent_info['active'] = $active;
        $parents_list[] = $parent_info;
        };
      };
    $parents_list = array_reverse ($parents_list);
    //Кладем в кеш
    //$loaded[$fid.$did.$sdid] = $parents_list;
//	sys_log('content->parentsList', LOG_DEBUG, "", $parents_list);
    return $parents_list;
    }

  /*
   * Увеличение счетчика скачиваний и возврашает урл
  */
  function url_counter($id)
    {
      global $dbconn;
    //Увеличили счетчик
    $docs_table = sysDBGetTable('content_docs');
    $sql = "UPDATE $docs_table SET url_counter = url_counter + 1
            WHERE id = '".sysVarPrepForStore($id)."'";
    mysqli_query($dbconn, $sql);
    if (mysqli_connect_errno()) sysException ('content->url_counter', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

    //получили информацию об урл
    $sql = "SELECT url
                FROM $docs_table
                WHERE id = '$id'";
    $sql_result = mysqli_query($dbconn, $sql);
      if (mysqli_connect_errno()) sysException ('content->url_counter', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
    list ($url) = mysqli_fetch_row($sql_result);
    mysqli_free_result($sql_result);

    return $url;
    }

  /*
   * Чистим кеши передаеться
   * $parents_list
  */
  function clear_cache($parents_list)
    {
    $sysTpl = new sysTpl;

    //Чистим кеш титульной старинцы
    $sysTpl->clear_cache(null, 'content::user::main');
    //Чистим кеш блока
    $sysTpl->clear_cache(null, 'xcontent_block');
    $sysTpl->clear_cache(null, 'search_block');

    //Чистим все кеша разделов
    //Просмотр папки
    $sysTpl->clear_cache(null, 'content::user::folder::view');
    //Просмотр документов
    $sysTpl->clear_cache(null, 'content::user::doc::view');
    //Просмотр вложенных документов
    $sysTpl->clear_cache(null, 'content::user::subdoc::view');
    return true;
    }

	/*
	* Возвращает информацию о разделе
	*/
	function folder_get_info($fid)
    {
		static $loaded = array();
		if ($loaded[$fid]) 
			return $loaded[$fid];
		$folders_table = sysDBGetTable('content_folders');
		$folders_column =  sysDBGetColumns($folders_table);
		$loaded[$fid] = sysDbSelect ($folders_table, $folders_column, "WHERE $folders_column[id] = '".sysVarPrepForStore($fid)."'");
		return $loaded[$fid];
    }
	
	/*
	* Возвращает информацию о разделе
	*/
	function folder_get_info_by_name($fname)
    {
		static $loaded = array();
		if ($loaded["$fname"]) 
			return $loaded["$fname"];
		$folders_table = sysDBGetTable('content_folders');
		$folders_column =  sysDBGetColumns($folders_table);
		$loaded[$fname] = sysDbSelect ($folders_table, $folders_column, "WHERE $folders_column[name] = '".sysVarPrepForStore($fname)."'");
		return $loaded[$fname];
    }

  //
  // Функция возвращает список подразделов в разделе.
  //
  function folder_get_list($id, $curuser_sec_level)
    {
    $folder_dbdata = $this->folder_get_info ($id);
    //Если есть вложенные папки рботаем с ними может есть скрытые а я всетаки админ

      $folders_table = sysDBGetTable('content_folders');
      $folders_column = sysDBGetColumns($folders_table);

      //Получили список вложенных папок (Админ видит невидимое)
      //Сортировать по возрастанию / убыванию
      if ($folder_dbdata['subfolder_order_asc'])
        $order=' ASC';
        else
          $order=' DESC';

      if ( $folder_dbdata['subfolder_orderby'] == 'weight' )
        $order='';

      if ( $curuser_sec_level >= ACCESS_ADMIN )
        {
        $sub_folders_list = sysDbSelect ($folders_table, $folders_column,
          "WHERE $folders_column[parent_id] = '$id'
          ORDER BY $folder_dbdata[subfolder_orderby] $order", true);
        }
        else
          {
          $sub_folders_list = sysDbSelect ($folders_table, $folders_column,
            "WHERE $folders_column[parent_id] = '$id' AND $folders_column[active]=1
            ORDER BY $folder_dbdata[subfolder_orderby] $order", true);
          };
//смотрим - если нет имени, то заменяем его идшником
if ($sub_folders_list)
     foreach ($sub_folders_list as $key=>$folder)
	if  (empty($folder['name']) or $folder['name']=='' or $folder['name']==' ') $sub_folders_list[$key]['name']=$folder['id'];


      //А туть мы чешим репу как веса двигать
      if (($curuser_sec_level>=ACCESS_EDIT) and ($folder_dbdata['subfolder_orderby'] == 'weight' ) and (isset ($sub_folders_list)))
        {
        $MaxWeight = sysDbMaxWeight ($folders_table, "WHERE $folders_column[parent_id] = '$id'");
        foreach ($sub_folders_list as $key=>$folder)
          {
          if ($MaxWeight > 1) //Если один нехир двигать
             {
            if ( $folder['weight'] > 1 )
              $folder['weight_moveup_url'] = sysModUrl ('content','admin','folder_moveup', array ('id'=>$folder['id']));
            if ($folder['weight']<$MaxWeight)
              $folder['weight_movedown_url'] = sysModUrl ('content','admin','folder_movedown', array ('id'=>$folder['id']));
            $sub_folders_list[$key]=$folder;
            }
          }
        }
      //С разделами все
      return $sub_folders_list;
    

    }

  /*
   * Создаем раздел
  */
  function folder_create($folder_indata)
    {
      global $dbconn;
    $folders_table = sysDBGetTable('content_folders');
    $folders_column =  sysDBGetColumns($folders_table);

    //Находим и задаем самый большой вес
    $MaxWeight = sysDbMaxWeight ($folders_table, "WHERE $folders_column[parent_id] = '$folder_indata[parent_id]'");
    $MaxWeight++;
    $folder_indata['weight'] = $MaxWeight;

    //Вставили в базу
    $insert_id = sysDbInsert ($folders_table, $folder_indata);

    //Увеличили счетчик разделов если раздел активен
    if ($folder_indata['active'])
      {
      $sql = "UPDATE $folders_table SET subfolder_counter=subfolder_counter+1
            WHERE id = '".sysVarPrepForStore($folder_indata['parent_id'])."'";
      $sql_result = mysqli_query($dbconn, $sql);
      if (mysqli_connect_errno()) sysException ('content->folder_create', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
      }

    //Чистим кеша
    $parents_list =& $this->parentsList ($folder_indata['parent_id']);
    $this->clear_cache ($parents_list);
    return $insert_id;
    }

  /*
   * Обновляем информацию о разделе
  */
  function folder_update($folder_indata)
    {
      global $dbconn;
    sysExtLibLoad();
    $folders_table = sysDBGetTable('content_folders');
    $folders_column = sysDBGetColumns($folders_table);

    //Информация о раздели из базы
    $folder_dbdata = $this->folder_get_info($folder_indata['id']);

    //Если parent_id изменился пересчитываем веса
    if ($folder_dbdata['parent_id']<>$folder_indata['parent_id'])
      {
      //Проверки
      //1 Радителя низя вкладывать в ребенка и ребнка ребенка и т.д.
      //2 Низя вкладывать самого в себя

      //Строим массив перентов директории вкаторую нас перемещают
      $tfolder_parents_list =& $this->parentsList ($folder_indata['parent_id']);

      $prezent = false;
      foreach ($tfolder_parents_list as $key=>$tfolder)
        {
        if ($tfolder['id']==$folder_indata['id'])
          {
          $prezent = true;
          break;
          }
        }

      if (!$prezent)
        {
        //Удалили старый вес
        sysDbWeightDelete ($folders_table, $folder_dbdata['weight'],
            "WHERE $folders_column[parent_id] = '$folder_dbdata[parent_id]' AND $folders_column[id]<>'$folder_indata[id]'");
        //Находим и задаем самый большой вес
        $MaxWeight = sysDbMaxWeight ($folders_table, "WHERE $folders_column[parent_id] = '$folder_indata[parent_id]'");
        $MaxWeight++;
        $folder_indata ['weight']=$MaxWeight;
        }
        else
          {
          sysException ('content->folder_update', BAD_PARAM, 'Нельзя вложит родительскую папку в дочернюю', $folder_indata);
          }

        //Если изменился раздел - правим счетчики документов
        if ($folder_dbdata['active'])
          {
          //Уменьшили счетчик старова раздела если документ активен
          $sql = "UPDATE $folders_table SET subfolder_counter=subfolder_counter-1
                  WHERE id = '".sysVarPrepForStore($folder_dbdata['parent_id'])."'";
          mysqli_query($dbconn, $sql);
          if (mysqli_connect_errno()) sysException ('content->folder_update', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
          }

        if ($folder_indata['active'])
          {
          //Увеличили счетчик нового раздела если документ активен
          $sql = "UPDATE $folders_table SET subfolder_counter=subfolder_counter+1
                  WHERE id = '".sysVarPrepForStore($folder_indata['parent_id'])."'";
          mysqli_query($dbconn, $sql);
          if (mysqli_connect_errno()) sysException ('content->folder_update', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
          }
        }
        else//Раздел не изменился
          {
          //Проверка если был активным а стал неактивным
          if (($folder_dbdata['active']) and (!$folder_indata['active']))
            {
            //Уменьшили счетчик старова раздела если документ активен
            $sql = "UPDATE $folders_table SET subfolder_counter=subfolder_counter-1
                    WHERE id = '".sysVarPrepForStore($folder_indata['parent_id'])."'";
            mysqli_query($dbconn, $sql);
            if (mysqli_connect_errno()) sysException ('content->folder_update', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
            }
          //Проверка если был неактивным а стал активным
          if ((!$folder_dbdata['active']) and ($folder_indata['active']))
            {
            //Уменьшили счетчик старова раздела если документ активен
            $sql = "UPDATE $folders_table SET subfolder_counter=subfolder_counter+1
                    WHERE id = '".sysVarPrepForStore($folder_indata['parent_id'])."'";
            mysqli_query($dbconn, $sql);
            if (mysqli_connect_errno()) sysException ('content->folder_update', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
            }
          }

    //Вставили в базу
    sysDbUpdate ($folders_table, $folder_indata, "WHERE $folders_column[id] = '$folder_indata[id]'");

    //Чистим кеша
    $parents_list =& $this->parentsList ($folder_indata['parent_id']);
    $this->clear_cache ($parents_list);
    return true;
    }

  /*
   * Удаляет раздел с вложенными подразделами  и документами
  */
  function folder_delete($id)
    {
      global $dbconn;
    //Получаем список вложенных папок
    $folders_list = $this->folder_ItemsTreeBuild($id);
    //Добавляем текущую папку
    $folders_list[]=array ('id'=>$id);

    //Удаляем вложенные документы и папку
    $docs_table = sysDBGetTable('content_docs');
    $docs_column =  sysDBGetColumns($docs_table);
    $folders_table = sysDBGetTable('content_folders');
    $folders_column =  sysDBGetColumns($folders_table);

    $links_table = sysDBGetTable('content_links');
    $links_column =  sysDBGetColumns($links_table);

    //Инфа о текущей папке
    $folder_dbdata = $this->folder_get_info($id);
    //Уменьшение счетчик родительского раздела если раздел активен
    if ($folder_dbdata['active'])
      {
      $sql = "UPDATE $folders_table SET subfolder_counter=subfolder_counter-1
              WHERE id=".sysVarPrepForStore($folder_dbdata['parent_id']);
      mysqli_query($dbconn, $sql);
      if (mysqli_connect_errno()) sysException ('content->folder_delete', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
      };
    //Убиваем все что движеться

$where="(".$links_column['parent_id']."=".$folders_list[0]['id'];
    foreach ($folders_list as $k=>$folder)
      {
      //Документы
   //   sysDbDelete ($docs_table, "WHERE $docs_column[fid] = '$folder[id]'");
      //Папки
$where.=" OR ".$links_column['parent_id']."=".$folder['id'];
      sysDbDelete ($folders_table, "WHERE $folders_column[id] = '$folder[id]'");

      };
$where.=") ";
$sql="DELETE ".$links_table.".*,".$docs_table.".* FROM ".$links_table.",".$docs_table." WHERE ".$where." AND ".$links_column['child_id']."=".$docs_column['id'];
            mysqli_query($dbconn, $sql);
            if (mysqli_connect_errno()) sysException ('content->folder_delete', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");


    //Пересчитываем веса
    sysExtLibLoad();
    sysDbWeightDelete ($folders_table, $folder_dbdata['weight'], "WHERE $folders_column[parent_id] = '$folder_dbdata[parent_id]'");

    //Чистим кеша
    $parents_list =& $this->parentsList ($id);
    $this->clear_cache ($parents_list);
    return true;
    }

  /*
   * Копирование раздела
  */
  function folder_copy($src_fid, $dst_fid)
    {
      global $dbconn;
    //Проверка на чьтение
    $parents_list = $this->ParentsList($src_fid);
    $sysObject = 'content::user::folder::view';
    foreach ($parents_list as $parent)
      $sysObject.='::'.$parent['id'];
    //Получили инфу о разделе
    $folder_dbdata =& $this->folder_get_info ($src_fid);
    if (!sysSecAuthAction($sysObject, ACCESS_READ, $folder_dbdata['uid']))  return false;

    //Проверка на запись
    $parents_list = $this->ParentsList($dst_fid);
    $sysObject = 'content::admin::folder::copy';
    foreach ($parents_list as $parent)
      $sysObject.='::'.$parent['id'];
    //Получили инфу о разделе
    $folder_dbdata =& $this->folder_get_info ($dst_fid);
    $curuser_sec_level = sysSecGetLevel ($sysObject, $folder_dbdata['uid']);
    if ($curuser_sec_level<ACCESS_ADD)  return false;

    //Проверка родителя низя копировать в ребенка
    //Строим массив перентов директории вкаторую нас копируют
    foreach ($parents_list as $key=>$tfolder)
      {
      if ($tfolder['id']==$src_fid)
        sysException ('content->folder_copy', BAD_PARAM, 'Нельзя копировать родительскую папку в дочернюю');
      }

    $folders_table = sysDBGetTable('content_folders');
    $folders_column =  sysDBGetColumns($folders_table);
    $docs_table = sysDBGetTable('content_docs');
    $docs_column =  sysDBGetColumns($docs_table);

    //Инфа из базы о копируемом разделе
    $folder_dbdata = $this->folder_get_info($src_fid);

    //Подготовка к копированию
    $folder_dbdata['parent_id'] = $dst_fid;
    unset ($folder_dbdata['id']);
    unset ($folder_dbdata['weight']);
    $folder_dbdata['subfolder_counter'] = 0;
    $folder_dbdata['subdoc_counter'] = 0;
    //Проверка если уровень=адд и нуна ативировать выставляем active=false
    //Инфа из базы о папке
    $dst_folder_dbdata = $this->folder_get_info($dst_fid);
    if (($dst_folder_dbdata['need_admin_active']) and ($curuser_sec_level<ACCESS_ADMIN))
      $folder_dbdata['active'] = false;

    //Находим и задаем самый большой вес
    $MaxWeight = sysDbMaxWeight ($folders_table, "WHERE $folders_column[parent_id] = '$folder_dbdata[parent_id]'");
    $MaxWeight++;
    $folder_dbdata['weight']=$MaxWeight;

    //Увеличили счетчик разделов если раздел активен
    if ($folder_dbdata['active'])
      {
      $sql = "UPDATE $folders_table SET subfolder_counter=subfolder_counter+1
            WHERE id=".sysVarPrepForStore($folder_dbdata['parent_id']);
      $sql_result = mysqli_query($dbconn, $sql);
      
      if (mysqli_connect_errno()) sysException ('content->folder_copy', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
      };

    //Вставили в базу
    sysDbInsert ($folders_table, $folder_dbdata);

    $dst_fid = mysqli_insert_id($dbconn);

    //Теперь рекурсивно копируем все вложенные документы
    //Список документов в текущем документе - копируем
    $docs_list = sysDbSelect ($docs_table, $docs_column, "WHERE $docs_column[fid] = '$src_fid'", true);
    if ($docs_list)
      {
      foreach ($docs_list as $doc)
        $this->doc_copy($doc['id'], $dst_fid);
      }

    //Теперь рекурсивно копируем все вложенные Разделы
    //Список документов в текущем документе - копируем
    $folders_list = sysDbSelect ($folders_table, $folders_column,
      "WHERE $folders_column[parent_id] = '$src_fid'", true);

    if ($folders_list)
      {
      foreach ($folders_list as $folder)
        $this->folder_copy($folder['id'], $dst_fid);
      }

    //Чистим кеша
    $this->clear_cache ($parents_list);
    return true;
    }

  /*
   * Возвращает масив дерева папак в виде придатном для отображения
   * Вызывает себя рекурсивно
   * parent_id
   * start_level
   * subfolder_orderby
  */
  function folder_ItemsTreeBuild($parent_id, $start_level = 0, $subfolder_orderby = 'weight')
    {
    $curuser_sec_level = sysSecGetLevel ('content::admin');

    $folders_table = sysDBGetTable('content_folders');
    $folders_column =  sysDBGetColumns($folders_table);
    $folder_dbdata = sysDbSelect ($folders_table, $folders_column,
      "where $folders_column[parent_id] = '$parent_id' ORDER BY $folders_column[$subfolder_orderby]", true);
    $result=array();

    sys_log('content', LOG_INFO, "folder_ItemsTreeBuild folder_dbdata", $folder_dbdata);

    if (isset ($folder_dbdata))
      {
      //Если сортировка по весу расставляем веса
      if ($subfolder_orderby=='weight')
        {
        //А туть мы чешим репу как веса двигать
        $MaxWeight=sysDbMaxWeight ($folders_table, "WHERE $folders_column[parent_id]='$parent_id'");
        foreach ($folder_dbdata as $key=>$folder)
          {
          if (($MaxWeight>1) and ($subfolder_orderby=='weight'))//Если один нехир двигать
            {
            if ($folder['weight']>1)
              {
              $folder['weight_moveup_url']=sysModUrl ('content','admin','folder_moveup', array ('id'=>$folder['id']));
              };

            if ($folder['weight']<$MaxWeight)
              {
              $folder['weight_movedown_url']=sysModUrl ('content','admin','folder_movedown', array ('id'=>$folder['id']));
              };
            $folder_dbdata[$key]=$folder;
            }
          };
        };

      //Формируем результат и вызываем себя рекурсивно
      foreach ($folder_dbdata as $folder)
        {
        //добавляем в результирующий масив
        //пробегаем по найденным документам вызывая себя рекурсивно
        $folder['level']=$start_level;
        $folder['parentfolder_orderby']=$subfolder_orderby;
        array_push ($result, $folder);

        //Если у документа есть вложенные документы вызываем себя рекурсивно
        if (($folder['subfolder_counter']>0) or ($curuser_sec_level>=ACCESS_ADMIN))
          {
          $recresult = $this->folder_ItemsTreeBuild ($folder['id'], $start_level+1, $folder['subfolder_orderby']);
          if (!empty ($recresult))
            {
            $result = array_merge ($result, $recresult);
            }
          }
        }
      }

    return $result;
    }

  /*
   * Увеличиваем счетчик просмотров раздела
  */
  function folder_inc_view_counter($fid)
    {
      global $dbconn;
    $folders_table = sysDBGetTable('content_folders');
    $sql = "UPDATE $folders_table SET counter=counter+1
          WHERE id = '".sysVarPrepForStore($fid)."'";
    mysqli_query($dbconn, $sql);
        if (mysqli_connect_errno()) sysException ('content->folder_inc_view_counter', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

    return true;
    }

/*
   * пересчитываем популярность документа
  */
  function doc_inc_popularity($did)
    {
    global $dbconn;
    $now_date  = time();
    $doc_dbdata=$this->doc_get_info($did);
    $create_date= strtotime($doc_dbdata['pub_datetime']);

    $age= floor(($now_date-$create_date)/86400)+1;
 if ($age==0) $age=1;
	$popular=$doc_dbdata['counter']/$age;

    $docs_table = sysDBGetTable('content_docs');

    $sql = "UPDATE $docs_table SET popularity=".$age." WHERE id=".sysVarPrepForStore($did);
    mysqli_query($dbconn, $sql);
      if (mysqli_connect_errno()) sysException ('content->doc_inc_popularity', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
    return true;
    }

  //
  // Добавляем документ
  //
  function doc_create($doc_indata)
    {
      global $dbconn;
    $docs_table = sysDBGetTable('content_docs');
    $links_table = sysDBGetTable('content_links');
    $folders_table = sysDBGetTable('content_folders');

   $docs_column = sysDBGetColumns($docs_table);
   $links_column = sysDBGetColumns($links_table);

    // Находим и задаем самый большой вес
    $MaxWeight = sysDbMaxWeight ($links_table, "WHERE parent_id = ".$doc_indata["fid"]);
    $MaxWeight++;

    // $MaxWeight = sysDbMaxWeight ($docs_table, "WHERE $docs_column[fid] = '$doc_indata[fid]'");
    // $MaxWeight++;
    // $doc_indata['weight'] = $MaxWeight;

    // Вставляем документ в базу
  /*  $sql = "INSERT INTO ".$docs_table."
            (pub_datetime,from_ip,displayname,description,content,author,logo,url,author_email,misc_varchar_a)
            VALUES
            (
            '".$doc_indata["pub_datetime"]."',
            '".$_SERVER['REMOTE_ADDR']."',
            '".$doc_indata["displayname"]."',
            '".$doc_indata["description"]."',
            '".$doc_indata["content"]."',
            '".$doc_indata["author"]."',
            '".$doc_indata["logo"]."',
            '".$doc_indata["url"]."',
            '".$doc_indata["author_email"]."',
        '".$doc_indata["misc_varchar_a"]."'
            );";
    mysql_query( $sql );
    if (mysql_errno()!=0)
            sysException ('content->doc_create', DATABASE_ERROR, mysql_error()."<br/> $sql");

    $last_id = mysql_insert_id();*/
    $last_id = sysDBInsert($docs_table,$doc_indata);

    // Вставляем запись в links.
    // Используем текущий раздел.
    /*$sql = "INSERT INTO ".$links_table."
                (parent_id,child_id,active,weight,uid)
                VALUES
                (
                '".$doc_indata["fid"]."',
                '".$last_id."',
                '".$doc_indata["active"]."',
                '".$MaxWeight."',
                '".$doc_indata["uid"]."'
                );";
    mysql_query( $sql );
        if (mysql_errno()!=0)
            sysException ('content->doc_create', DATABASE_ERROR, mysql_error()."<br> $sql");

    // Увеличиваем счётчик в folders если документ активен
    if ($doc_indata['active']) {
    $sql = "UPDATE ".$folders_table." SET subdoc_counter = subdoc_counter + 1
            WHERE id = ".$doc_indata["fid"];
    mysql_query( $sql );
    if (mysql_errno()!=0)
        sysException ('content->doc_create', DATABASE_ERROR, mysql_error()."<br> $sql");
    }*/

    // Вставляем записи в links.
    // Используем все выбранные разделы.

    for ($i = 0; $i < count($doc_indata["fids"]); $i++) {
        // Вставляем запись в links
        $sql = "INSERT INTO ".$links_table."
                (parent_id,child_id,active,weight)
                VALUES
                (
                '".$doc_indata["fids"][$i]."',
                '".$last_id."',
                '".$doc_indata["active"]."',
                '".$MaxWeight."'
                );";
        mysqli_query($dbconn, $sql );
          if (mysqli_connect_errno()) sysException ('content->doc_create', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

        // Увеличиваем счётчик в folders если документ активен
        // Сомнительная процедура, можно просто считать количество документов
        // Которые относятся к данному разделу и активны, работает быстро, очень быстро
        // и ошибок меньше.
        if ($doc_indata['active']) {
        $sql = "UPDATE ".$folders_table." SET subdoc_counter = subdoc_counter + 1
            WHERE id = ".$doc_indata["fids"][$i];
        mysqli_query($dbconn, $sql );
        if (mysqli_connect_errno()) sysException ('content->doc_create', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
        }
    }

//проверяем, если документ в приоритете, снимаем галку приоритета с другого  документа в этом разделе.
if ($doc_indata['misc_varchar_a']==1)
{
         $sql1 = "SELECT $docs_column[id] FROM $docs_table, $links_table  
                    WHERE $docs_column[id]=$links_column[child_id] AND $links_column[parent_id]=".$_POST["fid"]." AND $docs_column[misc_varchar_a]=1";
    
	$result = mysqli_query($dbconn, $sql1);
	$row = mysqli_fetch_row($result);
	if ($row)
	{
       $sql2 = "UPDATE ".$docs_table." SET misc_varchar_a = 0 WHERE id = ".$row[0];
              mysqli_query($dbconn, $sql2 );
              if (mysqli_connect_errno()) sysException ('content->doc_create', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
	}
}


    // Чистим кэш
    $parents_list =& $this->parentsList ($doc_indata['fid']);
    $this->clear_cache ($parents_list);
    return $last_id;
    }

	//
	// Информация о документе
	//
	function doc_get_info($id, $doc_part_current=1)
    {
		//Получили инфу о документе
		$docs_table = sysDBGetTable('content_docs');
		$docs_column = sysDBGetColumns($docs_table);
		$doc_dbdata = sysDbSelect ($docs_table, $docs_column, "WHERE $docs_column[id]=".sysVarPrepForStore($id));
		if (empty($doc_dbdata))
			return false;

		//Page break
		$content_pages = explode( '{page_break}', $doc_dbdata['content']);
		$doc_part_total = count($content_pages);

		//Загоняем в шаблон
		$doc_dbdata['doc_part_total'] = $doc_part_total;
		$doc_dbdata['doc_part_current'] = $doc_part_current;
		if ($doc_part_current>0)
			$doc_dbdata['content'] = $content_pages[$doc_part_current-1];

		//Строим дерево вложенных документов, если нужно
		if (($doc_dbdata['doc_subdoc_counter']))  {
			$subdocs_list = $this->doc_ItemsTreeBuild($doc_dbdata['id'],0);
			$subdocs_list = array_slice ($subdocs_list, 1);
			foreach ($subdocs_list as $key => $value) {//Уменьщаем level на 1
				$value['level'] = $value['level']-1;
				$subdocs_list[$key] = $value;
			}
			$doc_dbdata['subdocs_list'] = $subdocs_list;
		}
		return $doc_dbdata;
    }
	
	//
	// Информация о документе
	//
	function doc_get_info_by_name($name, $doc_part_current=1)
    {
		//Получили инфу о документе
		$docs_table = sysDBGetTable('content_docs');
		$docs_column = sysDBGetColumns($docs_table);
		$doc_dbdata = sysDbSelect ($docs_table, $docs_column, "WHERE $docs_column[name]='".sysVarPrepForStore($name)."'");
		if (empty($doc_dbdata))
			return false;
		$id = $doc_dbdata['id'];
		//Page break
		$content_pages = explode( '{page_break}', $doc_dbdata['content']);
		$doc_part_total = count($content_pages);

		//Загоняем в шаблон
		$doc_dbdata['doc_part_total'] = $doc_part_total;
		$doc_dbdata['doc_part_current'] = $doc_part_current;
		if ($doc_part_current>0)
			$doc_dbdata['content'] = $content_pages[$doc_part_current-1];

		//Строим дерево вложенных документов, если нужно
		if (($doc_dbdata['doc_subdoc_counter']))  {
			$subdocs_list = $this->doc_ItemsTreeBuild($doc_dbdata['id'],0);
			$subdocs_list = array_slice ($subdocs_list, 1);
			foreach ($subdocs_list as $key => $value) {//Уменьщаем level на 1
				$value['level'] = $value['level']-1;
				$subdocs_list[$key] = $value;
			}
			$doc_dbdata['subdocs_list'] = $subdocs_list;
		}
		return $doc_dbdata;
    }

	//
	// Возвращает список документов в разделе
	//
	function doc_get_list($id, $curuser_sec_level, $page_current=1, $subdoc_orderby=null, $subdoc_order_asc=null)    {

		$folder_dbdata = $this->folder_get_info ($id);

    global $dbconn;
 
		//Считаем лимит $page_current
		if ($curuser_sec_level>=ACCESS_ADMIN)  {
			$doc_perpage = $folder_dbdata['subdoc_perpage_admin'];
        }  else  {
			$doc_perpage = $folder_dbdata['subdoc_perpage_user'];
        }
   		$limit_from = $doc_perpage*($page_current-1);

		//Берем вложенные документы
		$docs_table = sysDBGetTable('content_docs');
		$docs_column =  sysDBGetColumns($docs_table);
		$links_table = sysDBGetTable('content_links');
		$links_columns =  sysDBGetColumns($links_table);
		$folders_table = sysDBGetTable('content_folders');

		//Учитываем  limit_doc_olderdays  limit_doc_perfolder
		$now = date("Y-m-d H:i:00",time()+60);
		if ($folder_dbdata['limit_doc_olderdays'])  {//Находим максимальную дату до которой доживают документы
			$curdate = time();
			$ttl = $folder_dbdata['limit_doc_olderdays'];
			$exp_date = $curdate-($ttl*3600*24);
			$exp_date = date ("Y-m-d H:i:00", $exp_date);
        }

		// Расчитываем лимит документов в папке
		if ($folder_dbdata['limit_doc_perfolder']) {
			if ($folder_dbdata['limit_doc_perfolder'] < ($limit_from+$doc_perpage))  {
				$doc_limit = $folder_dbdata['limit_doc_perfolder']-$limit_from;
			}  else   {
				$doc_limit = $doc_perpage;
            }
        }  else  {
			$doc_limit = $doc_perpage;
        }


		//Получили список вложенных документов (Админ видит невидимое)
		//Сортировать по возрастанию / убыванию
		//Учитываем что на сортировку могли повлиять входные переменные
		if (!empty ($subdoc_orderby))  {
			$folder_dbdata['subdoc_orderby'] = sysVarPrepForStore($subdoc_orderby);
			$folder_dbdata['subdoc_order_asc'] = sysVarPrepForStore($subdoc_order_asc);
        }

		if ($folder_dbdata['subdoc_order_asc']) {
			$order = ' ASC';
        }  else   {
          $order = ' DESC';
        }

		if ($folder_dbdata['subdoc_orderby']=='weight')  {
			$order = '';
        }
		
		// если сортировка по weight, id
        if ($folder_dbdata["subdoc_orderby"] == "weight" or $folder_dbdata["subdoc_orderby"] == "id")  {
			$folder_dbdata["subdoc_orderby"] = $links_table.".".$folder_dbdata["subdoc_orderby"];
		}

		//Собственно выборка
		if ( $curuser_sec_level >= 70 ) {
		  
			$sql = "SELECT
                ".$docs_table.".*,
                ".$links_table.".active as active,
                ".$links_table.".weight as weight
                FROM ".$docs_table.", ".$links_table."
                WHERE ".$links_table.".parent_id = ".$id."
                AND ".$docs_table.".id = ".$links_table.".child_id
                ORDER BY ".$folder_dbdata["subdoc_orderby"]." ".$order."
                LIMIT ".$limit_from.", ".$doc_perpage;
		}  else {
			$exp_sql = !empty($exp_date) ? "AND ".$docs_table.".pub_datetime > '".$exp_date."'" : "";
			$sql = "SELECT
                ".$docs_table.".*,
                ".$links_table.".active as active
                FROM ".$docs_table.", ".$links_table."
                WHERE ".$links_table.".parent_id = ".$id."
                AND ".$docs_table.".id = ".$links_table.".child_id
                ".$exp_sql."
                AND ".$docs_table.".pub_datetime < '".$now."'
                AND ".$links_table.".active = 1
                ORDER BY ".$folder_dbdata["subdoc_orderby"]." ".$order."
                LIMIT ".$limit_from.", ".$doc_limit;
		}
      
		$sql_result = mysqli_query($dbconn, $sql);
		if ($sql_result === false) sysException ('content->doc_get_list', DATABASE_ERROR, mysqli_error($dbconn)."<br> $sql");

		$data = array();
		if ($sql_result instanceof mysqli_result) {
			while ($row = mysqli_fetch_assoc($sql_result)) {
				$data[] = $row;
			}
			mysqli_free_result($sql_result);
		}
		$sub_docs_list = $data;

		//проверяем, если у доков нет имен, то подставляем идшники

        foreach ($sub_docs_list as $key => $doc) {
			if (!$sub_docs_list[$key]['name']) 
				$sub_docs_list[$key]['name']=$sub_docs_list[$key]['id'];
		}


		//А туть мы чешим репу как веса двигать
		if (($curuser_sec_level>=ACCESS_EDIT) && ($folder_dbdata['subdoc_orderby']==$links_table.".weight") && (isset ($sub_docs_list)))  {
			$MaxWeight = sysDbMaxWeight ($links_table, "WHERE $links_table.parent_id = '".sysVarPrepForStore($id)."'");
			
			foreach ($sub_docs_list as $key => $doc)   {
				if ($MaxWeight>1) {//Если один нехир двигать
					if ($doc['weight']>1)  {
						$doc['weight_moveup_url'] = sysModUrl ('content','admin','doc_moveup', array ('id'=>$doc['id'],'fid'=>$id));
					}
					if ($doc['weight']<$MaxWeight)  {
						$doc['weight_movedown_url'] = sysModUrl ('content','admin','doc_movedown', array ('id'=>$doc['id'],'fid'=>$id));
		            }
					$sub_docs_list[$key]=$doc;
	
				}
			}
        }

		//Считаем число документов в базе (Админ видит невидимое)
		if ($folder_dbdata['limit_doc_perfolder']) {
			$limit = "LIMIT 0,".$folder_dbdata['limit_doc_perfolder'];
        } else  {
			$limit='';
        }

		if ($curuser_sec_level>=ACCESS_ADMIN)  {
			$sql = "SELECT COUNT(*) FROM ".$links_table." WHERE parent_id = ".$id;
        }  else	 {
			$exp_sql = !empty($exp_date) ? "AND ".$docs_table.".pub_datetime > '".$exp_date."'" : "";
			$sql = "SELECT COUNT(*) FROM ".$links_table.", ".$docs_table."
                  WHERE ".$links_table.".parent_id = ".$id."
                  AND ".$docs_table.".id = ".$links_table.".child_id
                  AND ".$links_table.".active = 1
                  ".$exp_sql."
                  AND ".$docs_table.".pub_datetime < '".$now."' ".$limit;
        }
      
		$sql_result = mysqli_query($dbconn, $sql);
		if ($sql_result === false) sysException ('content->doc_get_list', DATABASE_ERROR, mysqli_error($dbconn)."<br> $sql");

		$doc_total = 0;
		if ($sql_result instanceof mysqli_result) {
			list($doc_total) = mysqli_fetch_row($sql_result);
			mysqli_free_result($sql_result);
		}

		//Считаем число страниц всего
		$page_total = floor($doc_total/$doc_perpage);
		if (($page_total*$doc_perpage) < $doc_total)
			$page_total++;

		$folder_dbdata['page_total'] =& $page_total;
		$folder_dbdata['page_current'] =& $page_current;
		$folder_dbdata['doc_perpage'] =& $doc_perpage;
		$folder_dbdata['doc_total'] =& $doc_total;
		$folder_dbdata['sub_docs_list'] =& $sub_docs_list;
		return $folder_dbdata;

    }

    /**
    * @desc Возвращает коды разделов, в которые входит документ
    */
    function doc_get_parents($doc_id) {

        $links_table = sysDBGetTable('content_links');
        $links_columns =  sysDBGetColumns($links_table);
        $result = sysDbSelect ($links_table, array($links_columns['parent_id']),
          "WHERE {$links_columns['child_id']} = '$doc_id'");

        foreach ($result as $k=>$v) {
            $result[$k] = $v['parent_id'];
        }

        return $result;
    }

	//
	// Изменяем документ
	//
	function doc_update($doc_indata)
    {
      global $dbconn;
		sysExtLibLoad();
		$docs_table = sysDBGetTable('content_docs');
		$links_table = sysDBGetTable('content_links');
		$folders_table = sysDBGetTable('content_folders');

		$link = $this->get_link($_POST["id"],$_POST["fid"]);
		$active = $link['active'];

		$docs_column =  sysDBGetColumns($docs_table);
		$links_column =  sysDBGetColumns($links_table);
		if ( $active != $_POST["active"] ) {
			// Изменяем активность
			$sql = "UPDATE ".$links_table." SET active = ".$_POST["active"]."
                WHERE parent_id = ".$link["parent_id"]."
                AND child_id = ".$_POST["id"];
			mysqli_query($dbconn, $sql );
        if (mysqli_connect_errno()) sysException ('content->doc_update', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");

			// Если документ стал активным, то увеличиваем счётчик
			if ($_POST["active"] == 1) {
				$sql = "UPDATE ".$folders_table." SET subdoc_counter = subdoc_counter + 1
                    WHERE id = ".$link["parent_id"];
mysqli_query($dbconn, $sql );
        if (mysqli_connect_errno()) sysException ('content->doc_update', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
			}
			// Если документ стал не активен, то уменьшаем счётчик
			elseif ($_POST["active"] == 0) {
				$sql = "UPDATE ".$folders_table." SET subdoc_counter = subdoc_counter - 1
                    WHERE id = ".$link["parent_id"];
                    mysqli_query($dbconn, $sql );
                    if (mysqli_connect_errno()) sysException ('content->doc_update', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
			}
		}	

		//Вставили в базу
		sysDbUpdate ($docs_table, $doc_indata, "WHERE $docs_column[id] = '$doc_indata[id]'");

		//Чистим кеша
		$parents_list =& $this->parentsList ($doc_indata['fid']);
		$this->clear_cache ($parents_list);
		return true;
    }

//
// Удаляем документ
//
  function doc_delete($id)
    {
      global $dbconn;
    sysExtLibLoad();

    $docs_table = sysDBGetTable('content_docs');
    $links_table = sysDBGetTable('content_links');
    $folders_table = sysDBGetTable('content_folders');

    //Инфа из базы о доументе
    $doc_info = $this->doc_get_info($id);

    // Раздел, в котором находится документ
    $fid = $_POST["fid"];
    //Инфа из базы о папке
    $folder_dbdata = $this->folder_get_info($doc_info['fid']);

    // Для начала узнаем активен документ или нет
    $sql = "SELECT active FROM ".$links_table."
            WHERE child_id = ".$id."
            AND parent_id = ".$fid;
    $result = mysqli_query($dbconn, $sql);
       if (mysqli_connect_errno()) sysException ('content->doc_delete', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
   
      // Пока не знаю на что заменить
   //   $active = mysql_result($result,0,"active");

    // Если документ активен, то уменьшаем счётчик документов
    if ( $active ) {
      $sql = "UPDATE ".$folders_table."
              SET subdoc_counter = subdoc_counter - 1
              WHERE id = ".$fid;
              $result = mysqli_query($dbconn, $sql);
              if (mysqli_connect_errno()) sysException ('content->doc_delete', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
        
    }

    // Удаляем ссылку на документ
    $sql = "DELETE FROM ".$links_table."
            WHERE child_id = ".$id."
            AND parent_id = ".$fid;
            $result = mysqli_query($dbconn, $sql);
            if (mysqli_connect_errno()) sysException ('content->doc_delete', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
      

    // Считаем количество оставшихся ссылок на документ
    $sql = "SELECT id FROM ".$links_table."
            WHERE child_id = ".$id;
            $result = mysqli_query($dbconn, $sql);
            if (mysqli_connect_errno()) sysException ('content->doc_delete', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
      
    $links_count = mysqli_num_rows($result);

    // Если ссылок больше нет, то документ удаляем
    if ( $links_count == 0 ) {
        $sql = "DELETE FROM ".$docs_table."
                WHERE id = ".$id;
                $result = mysqli_query($dbconn, $sql);
                if (mysqli_connect_errno()) sysException ('content->doc_delete', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
          
    }

    // Чистим кэш
    $parents_list =& $this->parentsList (null, $doc_info['id']);
    $this->clear_cache ($parents_list);

    return $fid;
    }

  //
  // Копирование документа
  //
  function doc_copy($src_did, $dst_fid)
    {

    //Проверка на чтение
    $parents_list =& $this->parentsList (null, $src_did);
    $sysObject = 'content::user::doc::view';
    foreach ($parents_list as $parent)
      {
      if ($parent['type']=='doc'){$sysObject.='::doc';};
      $sysObject.='::'.$parent['id'];
      };

    //Инфа из базы о копируемом документе
    $doc_dbdata = $this->doc_get_info($src_did,0);
    if (!sysSecAuthAction($sysObject, ACCESS_READ, $doc_dbdata['uid'])) return false;

    $link = $this->get_link($src_did,$_POST["fid"]);
    $active = $link["active"];
    $doc_dbdata["active"] = $active;
    $doc_dbdata["fids"] = array($dst_fid);
    /*echo "<pre>";
    print_r($doc_dbdata);
    echo "</pre>";
    exit();*/

    //Проверка на запись
    $parents_list =& $this->parentsList ($dst_fid);
    $sysObject = 'content::admin::doc::copy';
    foreach ($parents_list as $parent)
      {
      $sysObject.='::'.$parent['id'];
      };
    //Получили инфу о разделе
    $folder_dbdata =& $this->folder_get_info ($dst_fid);
    if (!sysSecAuthAction($sysObject, ACCESS_ADD, $folder_dbdata['uid'])) return false;
    $curuser_sec_level = sysSecGetLevel ($sysObject, $folder_dbdata['uid']);

    //Подготовка к копированию
    $doc_dbdata['fid'] = $dst_fid;
    unset ($doc_dbdata['id']);
    unset ($doc_dbdata['weight']);
    $doc_dbdata['doc_subdoc_counter'] = 0;

    //Проверка если уровень = адд и нуна ативировать выставляем active=false
    if ( ($folder_dbdata['need_admin_active'] ) and ($curuser_sec_level < ACCESS_ADMIN))
      $doc_dbdata['active'] = false;


    $this->doc_create($doc_dbdata);

    //Теперь рекурсивно копируем все вложенные документы
    //Список документов в текущем документе - копируем
    /*$docs_list = sysDbSelect ($docs_table, $docs_column, "WHERE $docs_column[parent_id] = '$src_did' ORDER BY $docs_column[weight]", true);

    $dst_did = mysql_insert_id();

    if ($docs_list)
      {
      foreach ($docs_list as $subdoc)
        {
        $this->subdoc_copy($subdoc[id], $dst_did);
        };
      }*/

    //Чистим кеша
    $this->clear_cache ($parents_list);
    sys_log ('content->doc_copy', LOG_DEBUG, "Документ \"$src_did\" скопированн в папку \"$dst_fid\"");
    return true;
    }

  /*
   * Возвращает масив дерева документов в документе в виде придатном для отображения
   * Вызывает себя рекурсивно
   * parent_id
   * start_level
  */
  function doc_ItemsTreeBuild($parent_id, $start_level=0)
    {
    $curuser_sec_level = sysSecGetLevel ('content::admin');

    if ($start_level==0)
      {
      $docs_table = sysDBGetTable('content_docs');
      $docs_column = sysDBGetColumns($docs_table);
      $doc_dbdata = sysDbSelect ($docs_table, $docs_column,
        "where $docs_column[id] = '$parent_id' ORDER BY $docs_column[weight]", true);
      }
      else
        {
        $docs_table = sysDBGetTable('content_docs');
        $docs_column =  sysDBGetColumns($docs_table);
        $doc_dbdata = sysDbSelect ($docs_table, $docs_column,
          "where $docs_column[parent_id] = '$parent_id' ORDER BY $docs_column[weight]", true);
        };

    $result = array();
    if (isset ($doc_dbdata))
      {
      //А туть мы чешим репу как веса двигать
      $MaxWeight = sysDbMaxWeight ($docs_table, "WHERE $docs_column[parent_id]='$parent_id'");
      foreach ($doc_dbdata as $key=>$doc)
        {
        if ($MaxWeight>1)//Если один нехир двигать
           {
          if ($doc['weight']>1)
             {
             $doc['weight_moveup_url']=sysModUrl ('content','admin','subdoc_moveup', array ('id'=>$doc['id']));
            };

          if ($doc['weight']<$MaxWeight)
            {
            $doc['weight_movedown_url']=sysModUrl ('content','admin','subdoc_movedown', array ('id'=>$doc['id']));
            };
          $doc_dbdata[$key]=$doc;
          }
        };

      //Формируем результат и вызываем себя рекурсивно
      foreach ($doc_dbdata as $doc)
        {
        //добавляем в результирующий масив
        //пробегаем по найденным документам вызывая себя рекурсивно
        $doc['level'] = $start_level;
        array_push ($result, $doc);
        //Если у документа есть вложенные документы вызываем себя рекурсивно
        if (($doc['doc_subdoc_counter']>0) or ($curuser_sec_level>=ACCESS_ADMIN))
          {
          $recresult = $this->doc_ItemsTreeBuild($doc['id'], $start_level+1);
          if (!empty ($recresult))
            {
            $result = array_merge ($result, $recresult);
            }
          }
        }
      }

    return $result;
    }

  /*
   * Увеличиваем счетчик просмотров документа
  */
  function doc_inc_view_counter($did)
    {
      global $dbconn;
    $docs_table = sysDBGetTable('content_docs');
    $sql = "UPDATE $docs_table SET counter=counter+1
          WHERE id=".sysVarPrepForStore($did);
    mysqli_query($dbconn, $sql);
    if (mysqli_connect_errno()) sysException ('content->doc_inc_view_counter', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
    return true;
    }

  /*
   * Добовляем вложенный документ
  */
  function subdoc_create($doc_indata)
    {
      global $dbconn;
    //Находим и задаем самый большой вес
    $docs_table = sysDBGetTable('content_docs');
    $docs_column =  sysDBGetColumns($docs_table);
    $MaxWeight = sysDbMaxWeight ($docs_table, "WHERE $docs_column[parent_id] = '$doc_indata[parent_id]'");
    $MaxWeight++;
    $doc_indata ['weight'] = $MaxWeight;

    //Вставили в базу
    sysDbInsert ($docs_table, $doc_indata);

    //Увеличили счетчик вложенных документов если документ активен
    if ($doc_indata['active'])
      {
      $sql = "UPDATE $docs_table SET doc_subdoc_counter=doc_subdoc_counter+1
            WHERE id = '".sysVarPrepForStore($doc_indata['parent_id'])."'";
      mysqli_query($dbconn, $sql);
        if (mysqli_connect_errno()) sysException ('content->subdoc_create', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
      };

    //Чистим кеша
    $parents_list =& $this->parentsList (null, null, $doc_indata['id']);
    $this->clear_cache ($parents_list);
    return true;
    }

  /*
   * Добовляем вложенный документ
  */
  function subdoc_update($doc_indata)
    {
      global $dbconn;
    sysExtLibLoad();
    $docs_table = sysDBGetTable('content_docs');
    $docs_column = sysDBGetColumns($docs_table);

    //Инфа о документе из базы
    $doc_dbdata = $this->doc_get_info ($doc_indata['id']);

    //Если parent_id изменился пересчитываем веса
    if ($doc_dbdata['parent_id']<>$doc_indata['parent_id'])
      {
      //Проверка на вкладывание самого в себя
      //Строим дерево куда нас перемещают и смотрим нетли там нас
      $target_subdoc_parents_list = $this->ParentsList(null, null, $doc_indata['parent_id']);
      foreach ($target_subdoc_parents_list as $key=>$value)
        {
        if ($doc_dbdata['id']==$value)
          {
          sysException ('content->subdoc_update', BAD_PARAM, 'Низя вложит родительскую папку в дочернюю');
          };
        };

      //Удалили старый вес
      sysDbWeightDelete ($docs_table, $doc_dbdata['weight'],
          "WHERE $docs_column[parent_id] = '$doc_dbdata[parent_id]' AND $docs_column[id]<>'$doc_dbdata[id]'");

      //Находим и задаем самый большой вес
      $MaxWeight=sysDbMaxWeight ($docs_table, "WHERE $docs_column[parent_id] = '$doc_indata[parent_id]'");
      $MaxWeight++;
      $doc_indata ['weight'] = $MaxWeight;
      //Если изменился раздел - правим счетчики документов

      if ($doc_dbdata['active'])
        {
        //Уменьшили счетчик старова раздела если документ активен
        $sql = "UPDATE $docs_table SET doc_subdoc_counter=doc_subdoc_counter-1
              WHERE id=".sysVarPrepForStore($doc_dbdata['parent_id']);
        mysqli_query($dbconn, $sql);
        if (mysqli_connect_errno()) sysException ('content->subdoc_update', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
        };
      if ($doc_indata['active'])
        {
        //Увеличили счетчик нового раздела если документ активен
        $sql = "UPDATE $docs_table SET doc_subdoc_counter=doc_subdoc_counter+1
              WHERE id=".sysVarPrepForStore($doc_indata['parent_id']);
        mysqli_query($dbconn, $sql);
        if (mysqli_connect_errno()) sysException ('content->subdoc_update', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
        };
      }
      else//Раздел не изменился
        {

        //Проверка если был активным а стал неактивным
        if (($doc_dbdata['active']) and (!$doc_indata['active']))
          {
          $sql = "UPDATE $docs_table SET doc_subdoc_counter=doc_subdoc_counter-1
              WHERE id=".sysVarPrepForStore($doc_indata['parent_id']);
          mysqli_query($dbconn, $sql);
          if (mysqli_connect_errno()) sysException ('content->subdoc_update', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
          };
        //Проверка если был неактивным а стал активным
        if ((!$doc_dbdata['active']) and ($doc_indata['active']))
          {
          //Увеличили счетчик старова раздела если документ активен
          $sql = "UPDATE $docs_table SET doc_subdoc_counter=doc_subdoc_counter+1
                WHERE id=".sysVarPrepForStore($doc_indata['parent_id']);
          mysqli_query($dbconn, $sql);
          if (mysqli_connect_errno()) sysException ('content->subdoc_update', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
          };

        };


    //Вставили в базу
    sysDbUpdate ($docs_table, $doc_indata, "WHERE $docs_column[id] = '$doc_indata[id]'");

    //Чистим кеша
    $parents_list =& $this->parentsList (null, null, $doc_indata['id']);
    $this->clear_cache ($parents_list);
    return true;
    }

  /*
   * Удаляем документ вложенный в документ
  */
  function subdoc_delete($id)
    {
      global $dbconn;
    sysExtLibLoad();

    $docs_table = sysDBGetTable('content_docs');
    $docs_column =  sysDBGetColumns($docs_table);
    //Здесь мы получаем список всех вложенных страниц фключая текущую
    //Дерево страниц
    $subdocs_list = $this->doc_ItemsTreeBuild($id, 0);
    foreach ($subdocs_list as $key=>$doc)
      {
      //собственно удаление
      sysDbDelete ($docs_table, "WHERE $docs_column[id] ='$doc[id]'");
      };

    //$doc_info - документ на катором клацнули удалять
    $doc_info = $subdocs_list[0];

    sysDbWeightDelete ($docs_table, $doc_info['weight'],
       "WHERE $docs_column[parent_id] = $doc_info[parent_id]");
    //Уменьшили счетчик вложенных документов если документ активен
    if ($doc_info['active'])
      {
      $sql = "UPDATE $docs_table SET doc_subdoc_counter=doc_subdoc_counter-1
            WHERE id=".sysVarPrepForStore($doc_info['parent_id']);
      mysqli_query($dbconn, $sql);
      if (mysqli_connect_errno()) sysException ('content->subdoc_delete', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
      };

    //Чистим кеша
    $parents_list =& $this->parentsList (null, null, $doc_info['id']);
    $this->clear_cache ($parents_list);
    return true;
    }

  /*
   * Информация о документе
  */
  function subdoc_get_info($id)
    {
    $docs_table = sysDBGetTable('content_docs');
    $docs_column = sysDBGetColumns($docs_table);
    $doc_dbdata = sysDbSelect ($docs_table, $docs_column, "WHERE $docs_column[id]= '$id'");
    return $doc_dbdata;
    }

  /*
   * Возвращает информацию о вложенном документе
  */
  function subdoc_get_view_info($id, $rootdoc_id, $doc_part_current=1)
    {
    //Получили инфу о документе
    $docs_table = sysDBGetTable('content_docs');
    $docs_column =  sysDBGetColumns($docs_table);
    $doc_dbdata = sysDbSelect ($docs_table, $docs_column, "WHERE $docs_column[id] = '$id'");

    //Page break
    $content_pages = explode( '{page_break}', $doc_dbdata['content']);
    $doc_part_total = count($content_pages);
    //Загоняем в шаблон
    $doc_dbdata['doc_part_total'] = $doc_part_total;
    $doc_dbdata['doc_part_current'] = $doc_part_current;
    $doc_dbdata['content'] = $content_pages[$doc_part_current-1];

    //Строим дерево вложенных документов, если нужно
    if ($doc_dbdata['doc_subdoc_counter'])
      {
      $subdocs_list = $this->doc_ItemsTreeBuild($doc_dbdata['id'], 0);
      $subdocs_list = array_slice ($subdocs_list, 1);
      foreach ($subdocs_list as $key=>$value)//Уменьщаем level на 1
        {
        $value['level'] = $value['level']-1;
        $subdocs_list[$key] = $value;
        };
      $doc_dbdata['subdocs_list'] = $subdocs_list;
      };

    //Строим навигационные элименты - вперед назад в документ вверх
    //Информация о корневом документе
    $rootdoc_info = sysDbSelect ($docs_table, $docs_column, "WHERE $docs_column[id] = '$rootdoc_id'");
    $doc_dbdata['rootdoc_info'] = $rootdoc_info;
    //Информация о следующем и предвидущем элименте
    $updoc_info = sysDbSelect ($docs_table, $docs_column, "WHERE $docs_column[id] = '$doc_dbdata[parent_id]'");
    $doc_dbdata['updoc_info'] = $updoc_info;
    //Строим дерево родителя
    $rootdocs_list = $this->doc_ItemsTreeBuild($rootdoc_id, 0);
    //Находим эллимент за нами
    for ($i=0;$i<count($rootdocs_list);$i++)
      {
      if ($rootdocs_list[$i]['id']==$doc_dbdata['id'])
        {
        $doc_dbdata['prevdoc_info'] = $rootdocs_list[$i-1];
        $doc_dbdata['nextdoc_info'] = $rootdocs_list[$i+1];
        }
      }
    return $doc_dbdata;
    }

  /*
   * Копирование документа вложенного в документ
  */
  function subdoc_copy($src_did, $dst_did)
    {
    //Проверка на чьтение
    $parents_list =& $this->parentsList (null, null, $src_did);
    $sysObject = 'content::user::subdoc::view';
    foreach ($parents_list as $parent)
      {
      if ($parent['type']=='doc'){$sysObject.='::doc';};
      $sysObject.='::'.$parent['id'];
      };
    //Инфа из базы о копируемом документе
    $doc_dbdata = $this->doc_get_info($src_did,0);
    if (!sysSecAuthAction($sysObject, ACCESS_READ, $doc_dbdata['uid'])) return false;

    //Строим имя обьекта (туда куда нас копируют)
    $parents_list =& $this->parentsList (null, null, $dst_did);
    $sysObject = 'content::user::subdoc::copy';
    foreach ($parents_list as $parent)
      {
      if ($parent['type']=='doc'){$sysObject.='::doc';};
      $sysObject.='::'.$parent['id'];
      };
    //Инфа из базы о документе, куда копируют
    $targetdoc_dbdata = $this->subdoc_get_info($dst_did,0);
    $curuser_sec_level = sysSecGetLevel ($sysObject, $targetdoc_dbdata['uid']);
    if ($curuser_sec_level<ACCESS_ADD) return false;

    $docs_table = sysDBGetTable('content_docs');
    $docs_column =  sysDBGetColumns($docs_table);
    $folders_table = sysDBGetTable('content_folders');
    $folders_column =  sysDBGetColumns($folders_table);

    //Подготовка к копированию
    $doc_dbdata['fid'] = $targetdoc_dbdata['fid'];
    $doc_dbdata['parent_id'] = $dst_did;
    unset ($doc_dbdata['id']);
    unset ($doc_dbdata['weight']);
    $doc_dbdata['doc_subdoc_counter'] = 0;

    //Проверка если уровень=адд и нуна ативировать выставляем active=false
    //Инфа из базы о папке
    $folder_dbdata = $this->folder_get_info($targetdoc_dbdata['fid']);
    if (($folder_dbdata['need_admin_active']) and ($curuser_sec_level<ACCESS_ADMIN))
      $doc_dbdata['active']=false;

    //Находим и задаем самый большой вес
    $MaxWeight=sysDbMaxWeight ($docs_table, "WHERE $docs_column[parent_id] = '$dst_did'");
    $MaxWeight++;
    $doc_dbdata['weight']=$MaxWeight;

    //Увеличили счетчик вложенных документов если документ активен
    if ($doc_dbdata['active'])
      {
      $sql = "UPDATE $docs_table SET doc_subdoc_counter=doc_subdoc_counter+1
            WHERE id=".sysVarPrepForStore($doc_dbdata['parent_id']);
      mysql_query($sql);
      if (mysql_errno()!=0) sysException ('content->subdoc_copy', DATABASE_ERROR, mysql_error()."<br> $sql");
      
      };

    //Вставили в базу
    sysDbInsert ($docs_table, $doc_dbdata);

    //Теперь рекурсивно копируем все вложенные документы
    //Список документов в текущем документе - копируем
    $docs_list = sysDbSelect ($docs_table, $docs_column, "WHERE $docs_column[parent_id] = '$src_did' ORDER BY $docs_column[weight] ", true);

    $dst_did = mysql_insert_id();
    if ($docs_list)
      {
      foreach ($docs_list as $subdoc)
        $this->subdoc_copy($subdoc['id'], $dst_did);
      };


    //Чистим кеша
    $this->clear_cache ($parents_list);
    return true;
    }


  /**
   * @return array
   * @param $doc_id int
   * @desc Возврашает список фотографий 
  */
  function image_get_list ($doc_id)
    {
    //Необходимые таблицы
    $images_table = sysDBGetTable('content_images');
    $images_column = sysDBGetColumns($images_table);
    $images_list = sysDbSelect ($images_table, $images_column,
        "WHERE $images_column[doc_id] = '$doc_id'
           ORDER BY $images_column[weight] ASC", true);

    //А туть мы чешим репу как веса двигать
    if (!empty($images_list))
      {
      $MaxWeight = count($images_list);

      foreach ($images_list as $key=>$image)
        {
        if ($MaxWeight>1)
          {
          if (($image['weight']>1))
            $image['weight_moveup_url'] = sysModUrl ('content','admin','image_moveup', array ('id'=>$image['id']));
          if ($image['weight']<$MaxWeight)
            $image['weight_movedown_url'] = sysModUrl ('content','admin','image_movedown', array ('id'=>$image['id']));
          $images_list[$key] = $image;

          if ($MaxWeight==$image['weight'])

          {$images_list[$key]['MAXIMUM']='1';

          }
          }
          else {
                  if($MaxWeight==1)    $images_list[$key]['MAXIMUM']='1';

               }

        }
      }

    return $images_list;
    }

  
  function image_moveup($id)
    {
    //Опредиляем старый вес
    $image_dbdata = $this->image_get_info($id);

 
    //Подвинули вверх
    sysExtLibLoad();
    $image_table = sysDBGetTable('content_images');
    $image_column =  sysDBGetColumns($image_table);
    sysDbWeightMoveUp ($image_table, $image_dbdata['weight'],
                       "WHERE $image_column[doc_id] = '$image_dbdata[doc_id]'");

    return true;
    }

  function image_movedown($id)
    {
    //Опредиляем старый вес
    $image_dbdata = $this->image_get_info($id);

    //Подвинули вверх
    sysExtLibLoad();
    $image_table = sysDBGetTable('content_images');
    $image_column =  sysDBGetColumns($image_table);
          sysDbWeightMoveDown ($image_table, $image_dbdata['weight'],
                                          "WHERE $image_column[doc_id] = '$image_dbdata[doc_id]'");

    return true;
    }
  /*
   * Чистим кеша страниц
  */
  function tplcache_clear($cache_key = null)
    {
    $sysTpl = new sysTpl;

    //Чистим переданный кеш
    if ($cache_key)
      $sysTpl->clear_cache(null, $cache_key);

    //Чистим весь кеш модуля
    $sysTpl->clear_cache(null, 'content');

    return true;
    }


	function name_for_id($type = 'doc',$id)
    {
		if ($type == 'folder') 
			$table = sysDBGetTable('content_folders');
		else 
			$table = sysDBGetTable('content_docs');

		$sql_name = "SELECT name FROM ".$table." WHERE id=".$id;
		$name_result = mysql_query($sql_name);
		$row = mysql_fetch_row($name_result);
		$dname = $row[0];
		mysql_free_result($name_result);

		return $dname;
    }


	function id_for_name($type = 'doc',$dname)
    {
		if ($type == 'folder') 
			$table = sysDBGetTable('content_folders');
		else 
			$table = sysDBGetTable('content_docs');

		$sql_id = "SELECT id FROM ".$table." WHERE name='".$dname."'";
		$id_result = mysql_query($sql_id);
		$row = mysql_fetch_row($id_result);
		$id = $row[0];
		mysql_free_result($id_result);

		return $id;
    }
	
	function showAddError($error_msg, $doc_data, $folder_data, $object)    
	{
		$sysModTpl = sysTplWay ($object);
		$sysObject = 'content::admin::doc::data';
		$sysTpl = new sysTpl;
		$sysTpl->caching = false;
		sysLoadLang ($sysTpl, 'content');

		$sysTpl->assign ('doc_data', $doc_data);
		$sysTpl->assign ('error_msg', $error_msg);
		$sysTpl->assign ('folder_dbdata', $folder_data);

		sysExtLibLoad();

		$result = $sysTpl->fetch($sysModTpl,$sysObject);
		return $result;
    }
	
	function check_name ($name, $displayname, $id = null, $item_type = "folder") 
	{
	
    sys_log('check_name',LOG_DEBUG,'check_name',array('item_type'=>$item_type), 'content');

		$names_control = sysModGetVar('content', 'names_control');
		
		//убираем точку
		if (!$name || is_numeric($name)) {
			
			$name = str_replace(".", "", sysVarTranslit($displayname));
			
      //if ($id)				$name .= "-".$id;

      //если передали ид, то прибавляем ид, если нет - случайное число
      $salt = ($id > 0) ? $id : rand(100,9999);
      $name .= "-".$salt;
        sys_log('check_name1',LOG_DEBUG,'check_name',array('name'=>$name), 'content');

		} 
		else {
      sys_log('check_name2',LOG_DEBUG,'check_name',array('item_type'=>$item_type), 'content');
		
			if (!$names_control) {
				//проверка, не ввели ли русские символы в псевдоним
				$pattern = '([а-яА-Я]+)'; 
				if (preg_match($pattern, $name))
					$name = sysVarTranslit($displayname);
			}
    }	

			//чистим остальное
			$name = str_replace(".", "", sysVarTranslit($name));
			
			//проверяем, нет ли уже такого имени в базе документоов
			if ($item_type == "doc") {
				$docs_table = sysDBGetTable('content_docs');
				$docs_column = array('id' => "$docs_table.id");
				$exists_data = sysDbSelect($docs_table, $docs_column, "WHERE name = '$name' and id != $id", true);
				if ($exists_data[0]['id']>0) {
					//если передали ид, то прибавляем ид, если нет - случайное число
					$salt = ($id > 0) ? $id : rand(100,9999);
					$name .= "-".$salt;
				}
			}

      sys_log('0-check_name', LOG_DEBUG, "item_type", $item_type, 'content');

      //проверяем, нет ли уже такого имени в базе разделов
			if ($item_type == "folder") {
				$folders_table = sysDBGetTable('content_folders');
				$folders_column = array('id' => "$folders_table.id");

        // Если емеется каалог и мы его редактируем - ничего не добавляем
        if ($id) $check_id = "and id != '$id'";

				$exists_data = sysDbSelect($folders_table, $folders_column, "WHERE name = '$name' $check_id", true);
        sys_log('11-check_name', LOG_DEBUG, "check_id", $id, 'content');
        sys_log('1-check_name', LOG_DEBUG, "exists_data", $exists_data, 'content');
				if ($exists_data[0]['id']>0) {
					//если передали ид, то прибавляем ид, если нет - случайное число
					$salt = ($id > 0) ? $id : rand(100,9999);
					$name .= "-".$salt;
				}
			}

	//	} //

    sys_log('check_name 9',LOG_DEBUG,'check_name',array('name'=>$name), 'content');
		
		return $name;
	}
	

  }
?>
