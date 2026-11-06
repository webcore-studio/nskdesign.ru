<?php


class advert
  {


  function places_list()
    {

    //Необходимые таблицы
    $places_table = sysDBGetTable('advert_places');

    $sql = "SELECT * FROM $places_table";

    $sql_result = mysql_query($sql);
    if (mysql_errno()!=0) sysException ('advert->places_list()', DATABASE_ERROR, mysql_error()."<br> $sql");
   
    $places_list = array();
if ($sql_result)
{
    while ($row = mysql_fetch_assoc($sql_result)) $places_list[] = $row;
    mysql_free_result($sql_result);
}
    return $places_list;
    }


  function place_create($place_indata)
    {

    //Необходимые таблицы
    $places_table = sysDBGetTable('advert_places');
   $places_column = sysDBGetColumns($places_table);

     //Вставили в базу
    $insert_id = sysDbInsert ($places_table, $place_indata);
    $insert_id = mysql_insert_id();

    return $insert_id;
    }

  function place_info($id)
    {

    //Необходимые таблицы
    $places_table = sysDBGetTable('advert_places');
    $sql = "SELECT * FROM $places_table WHERE $places_table.id=$id";

    $sql_result = mysql_query($sql);
    if (mysql_errno()!=0) sysException ('advert->places_list()', DATABASE_ERROR, mysql_error()."<br> $sql");
   
    $place_info = array();
if ($sql_result)
{
    while ($row = mysql_fetch_assoc($sql_result)) $place_info[] = $row;
    mysql_free_result($sql_result);
}
    return $place_info[0];
    }



  function banner_list()
    {

    //Необходимые таблицы
    $places_table = sysDBGetTable('advert_places');
   $places_column = sysDBGetColumns($places_table);
    $banner_table = sysDBGetTable('advert_banners');
   $banner_column = sysDBGetColumns($banner_table);


    $sql = "SELECT $places_column[id] as place_id, $places_column[name], $places_column[width],$places_column[height],$places_column[block_id] as block_id,$banner_table.* FROM ($places_table LEFT JOIN $banner_table ON $places_column[id]=$banner_column[place])";

    $sql_result = mysql_query($sql);
    if (mysql_errno()!=0) sysException ('advert->places_list()', DATABASE_ERROR, mysql_error()."<br> $sql");
   
    $result = array();
if ($sql_result)
{
    while ($row = mysql_fetch_assoc($sql_result)) $result[] = $row;
    mysql_free_result($sql_result);
}
    return $result;
    }


 function banner_info_for_place($place_id,$only_active=false)
    {

    //Необходимые таблицы
   $banner_table = sysDBGetTable('advert_banners');
   $banner_column = sysDBGetColumns($banner_table);
    $places_table = sysDBGetTable('advert_places');
   $places_column = sysDBGetColumns($places_table);

    $sql = "SELECT $banner_table.* FROM  $banner_table  WHERE $banner_column[place]=".$place_id;
	if ($only_active) $sql.="  AND $banner_column[active]>0";
	$sql.="  ORDER BY $banner_column[weight] ASC ";
    $sql_result = mysql_query($sql);
    if (mysql_errno()!=0) sysException ('advert->banner_info_for_place()', DATABASE_ERROR, mysql_error()."<br> $sql");
   
    $result = array();
if ($sql_result)
{
    while ($row = mysql_fetch_assoc($sql_result)) $result[] = $row;
    mysql_free_result($sql_result);
}

    return $result;
    }



  function banner_create($image_indata, $curuser_sec_level)
    {
    //Инфа о нашей таблице
    $banners_table = sysDBGetTable('advert_banners');
    $banners_column =  sysDBGetColumns($banners_table);


   //Вставили в базу баннер
    $insert_id = sysDbInsert ($banners_table, $image_indata);


    return $insert_id;
    }
  

  function banner_update($banner_indata)
    {
    //Инфа о нашей таблице
	$banner_indata['additional']=serialize($banner_indata['additional']);
    $banners_table = sysDBGetTable('advert_banners');
    $banners_column =  sysDBGetColumns($banners_table);

    //Вставили в базу баннер
    $result =  sysDbUpdate ($banners_table, $banner_indata, "WHERE $banners_column[id] = '$banner_indata[id]'");

    return $result;
    }
  

  function place_update($place_indata)
    {
    //Инфа о нашей таблице
    $places_table = sysDBGetTable('advert_places');
    $places_column =  sysDBGetColumns($places_table);

    //Вставили в базу баннер
    $result =  sysDbUpdate ($places_table, $place_indata, "WHERE $places_column[id] = '$place_indata[id]'");

    return $result;
    }
  


function banner_get_info ($id)
    {
    //Необходимые таблицы
    $banners_table = sysDBGetTable('advert_banners');
    $banners_column = sysDBGetColumns($banners_table);


    $sql = "
         SELECT $banners_table.*
              
            FROM $banners_table
                      WHERE $banners_column[id] = '$id'";

    $sql_result = mysql_query($sql);
    if (mysql_errno()!=0) sysException ('advert->banner_get_info', DATABASE_ERROR, mysql_error()."<br> $sql");

    $banner_dbdata = mysql_fetch_assoc($sql_result);
    mysql_free_result($sql_result);

    if (empty($banner_dbdata)) {sysException ('advert->banner_get_info', BAD_PARAM, 'Banner dbdata empty');}
	$banner_dbdata['additional']=unserialize($banner_dbdata['additional']);
	
    return $banner_dbdata;
    }

  function banner_delete($id)
    {
     //Необходимые таблицы
    $banners_table = sysDBGetTable('advert_banners');
    $banners_column = sysDBGetColumns($banners_table);

  
     sysDbDelete ($banners_table, "WHERE $banners_column[id] = '$id'");
  
    return true;
    }

  function get_block_id($place)
    {
     //Необходимые таблицы
    $places_table = sysDBGetTable('advert_places');
    $places_column = sysDBGetColumns($places_table);

    $sql = "
         SELECT $places_column[block_id]
              
            FROM $places_table
                      WHERE $places_column[id] = '$place'";

    $sql_result = mysql_query($sql);
    if (mysql_errno()!=0) sysException ('advert->get_block_id', DATABASE_ERROR, mysql_error()."<br> $sql");

    $result = mysql_fetch_assoc($sql_result);
    mysql_free_result($sql_result);

      return $result['block_id'];
      }

  }
?>