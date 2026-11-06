<?php
/*
 * Главная админская страница
*/
function advert_admin_main()
{
	$sysObject = 'advert::admin::main';

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

	//Работа с sysTpl
	$sysTpl = new sysTpl;
	$sysModTpl = sysTplWay ($sysObject);
	$sysTpl->caching = false;


	sysModClassLoad ('advert');
	$advert = new advert;

	sysModClassLoad ('SYS_blocks');
	$SYS_blocks = new SYS_blocks;


	$user_gid = sysSessionGetVar('user_gid');
	$curuser_sec_level = sysSecGetLevel ($sysObject);


	$places_list = $advert->places_list();

	$banner_list=array();

	foreach ($places_list as $key => $place) {
		$inf=$advert->banner_info_for_place($place['id']);
		$places_list[$key]['banners']=$inf;
	}

	$sysTpl->assign('places_list', $places_list);


	//BrowseIn
	$browsein = array();
	$browsein[] = array ('url'=>sysModURL('advert','admin','main'),
                       'displayname'=>'Реклама');
	$sysTpl->assign('browsein', $browsein);

	//Вывод результатов
	$result['object'] =& $sysObject;
	$result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject);
	$result['browsein'] =& $browsein;
	return $result;
}


function advert_admin_place_add()
  {
  //Забираем переменные со входа
  $places_table = sysDBGetTable('advert_places');
  $places_column = sysDBGetColumns($places_table);
  $item_indata = sysDBCleanFromInput ($places_column);

  sysModClassLoad ('advert');
  $advert = new advert;

  $sysObject = 'advert::admin::place::add';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADMIN)) return;

//Создаем блок
  sysModClassLoad('SYS_blocks');
  $SYS_blocks = new SYS_blocks;
  $aid = $SYS_blocks->add ('advert_block');
	$item_indata['block_id']=$aid; 

//Создаем место
  $place_id=$advert->place_create($item_indata);


//Записываем в блок ид места
  sysBlockSetVar ($aid, 'place', $place_id);



  sysShowMessage(sysModURL ('advert','admin','main'), 'Рекламное место добавлено');


  }

function advert_admin_place_modify()
  {
  //Забираем переменные совхода
  $id = sysVarCleanFromInput('id');

  if (!is_numeric ($id)) sysException ('advert_admin_place_modify', BAD_PARAM, 'Id not numeric');


  sysModClassLoad ('advert');
  $advert = new advert;

$place_info=$advert->place_info($id);

  $sysObject = 'advert::admin::place::modify::'.$id;

  //Проверка на доступ
 if (!sysSecAuthAction($sysObject, ACCESS_EDIT)) return;




  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  $sysTpl->assign($place_info);


  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('advert','admin','main'),
                       'displayname'=>'Реклама');
  $browsein[] = array ('url'=>sysModURL('advert','admin','place_modify', array('id'=>$id)),
                       'displayname'=>'Редактирование места');
  $sysTpl->assign('browsein', $browsein);



  //Вывод результатов
  $result['object'] =& $sysObject;
 $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);

  return $result;

  }


function advert_admin_place_update()
  {

   //Забираем переменные со входа
  $places_table = sysDBGetTable('advert_places');
  $places_column =  sysDBGetColumns($places_table);
  
  //Параметры документа со входа
  $place_indata = sysDBCleanFromInput ($places_column);

 sysModClassLoad ('advert');
  $advert = new advert;

  //Строим имя обьекта

  $sysObject = 'advert::admin::place::update::'.$place_indata['id'];

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;


  
  //Собственно обновление информации
  $result = $advert->place_update($place_indata);
   if (!$result)
    {
        sysShowMessage(sysModURL ('advert','admin','main'), 'Ошибка редактирования рекламного места', 3);
}
 

      sysShowMessage(sysModURL ('advert','admin','main'), 'Рекламное место отредактировано', 3);

}


/*
 * Добавление баннера
*/
function advert_admin_banner_new()
  {
$place=sysVarCleanFromInput('id');

// if (!is_numeric ($place_id)


  sysModClassLoad ('advert');
  $advert = new advert;

  //Строим имя обьекта
  $sysObject = 'advert::admin::banner::new::'.$place;

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADD)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

    $sysTpl->assign('place', $place); 
  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('advert','admin','main'),
                       'displayname'=>'Реклама');
  $browsein[] = array ('url'=>sysModURL('advert','admin','banner_new', array('place'=>$place)),
                       'displayname'=>'Добавление баннера');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

function advert_admin_banner_add()
  {
  //Забираем переменные со входа
  $banner_table = sysDBGetTable('advert_banners');
  $banner_column =  sysDBGetColumns($banner_table);
  
  //Параметры документа со входа
  $banner_indata = sysDBCleanFromInput ($banner_column);


$uploaddir1 = $_SERVER['DOCUMENT_ROOT'];
$uploaddir2='/files/img/';

$banner_file=$_FILES['banner_file'];
if (is_uploaded_file($banner_file['tmp_name']))
{
$fname=$banner_file['name'];
if (file_exists($uploaddir1.$uploaddir2.$fname)) {$fname='_'.$fname;}
if (move_uploaded_file($banner_file['tmp_name'],$uploaddir1.$uploaddir2.$fname))
{
chmod ($uploaddir1.$uploaddir2.$fname, 0666);
$banner_indata['image']=$uploaddir2.$fname;

}
}


  sysModClassLoad ('advert');
  $advert = new advert;

  $sysObject = 'advert::admin::banner::add::'.$banner_indata['place'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_ADD)) return;


  //Уровень прав текушего пользователя
  $curuser_sec_level = sysSecGetLevel ($sysObject);

 
  //Вызвали функцию для вставки баннера
  $aid = $advert->banner_create ($banner_indata, $curuser_sec_level);
  

  if (!aid)
        sysShowMessage(sysModURL ('advert','admin','main'), 'Ошибка добавления баннера', 3);
	
	  //активируем  блок для этого места
	$bid=$advert->get_block_id($banner_indata['place']);
	sysModClassLoad('SYS_blocks');
	$SYS_blocks = new SYS_blocks;
	$SYS_blocks->activate ($bid);



      sysShowMessage(sysModURL ('advert','admin','main'), 'Баннер добавлен', 3);
  }


function advert_admin_banner_modify()
  {
  //Забираем переменные совхода
  $id = sysVarCleanFromInput('id');
  $place= sysVarCleanFromInput('place');
  if (!is_numeric ($id)) sysException ('advert_admin_banner_modify', BAD_PARAM, 'Id not numeric');


  sysModClassLoad ('advert');
  $advert = new advert;

  //Информация о баннере
  $banner_dbdata = $advert->banner_get_info($id);


  //Строим имя обьекта

  $sysObject = 'advert::admin::banner::modify::'.$place.'::'.$id;

  //Проверка на доступ
 if (!sysSecAuthAction($sysObject, ACCESS_EDIT)) return;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  $sysTpl->assign($banner_dbdata);


  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('advert','admin','main'),
                       'displayname'=>'Реклама');
  $browsein[] = array ('url'=>sysModURL('advert','admin','banner_modify', array('place'=>$place,'id'=>$id)),
                       'displayname'=>'Редактирование баннера');
  $sysTpl->assign('browsein', $browsein);


  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['browsein'] =& $browsein;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }



function advert_admin_banner_update()
  {

   //Забираем переменные со входа
  $banner_table = sysDBGetTable('advert_banners');
  $banner_column =  sysDBGetColumns($banner_table);
  
  //Параметры документа со входа
  $banner_indata = sysDBCleanFromInput ($banner_column);


 sysModClassLoad ('advert');
  $advert = new advert;

  //Строим имя обьекта

  $sysObject = 'advert::admin::banner::update::'.$banner_indata['place'].'::'.$banner_indata['id'];

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

$banner_file=$_FILES['banner_file'];
if ($banner_file)
{
$uploaddir1 = $_SERVER['DOCUMENT_ROOT'];
$uploaddir2='/files/img/';


if (is_uploaded_file($banner_file['tmp_name']))
{
$fname=$banner_file['name'];
if (file_exists($uploaddir1.$uploaddir2.$fname)) {$fname='_'.$fname;}
if (move_uploaded_file($banner_file['tmp_name'],$uploaddir1.$uploaddir2.$fname))
{
chmod ($uploaddir1.$uploaddir2.$fname, 0666);
$banner_indata['image']=$uploaddir2.$fname;

}
}
}

  
  //Собственно обновление информации
  $result = $advert->banner_update($banner_indata);
   if (!$result)
    {
        sysShowMessage(sysModURL ('advert','admin','main'), 'Ошибка редактирования баннера', 3);
}
 
 
      sysShowMessage(sysModURL ('advert','admin','main'), 'Баннер отредактирован', 3);

}


function advert_admin_banner_delete()
  {
  $id = sysVarCleanFromInput('id');
  $place = sysVarCleanFromInput('id');
  if (!is_numeric ($id)) sysException ('advertt_admin_banner_delete', BAD_PARAM, 'Id not numeric '.$id);

  sysModClassLoad ('advert');
  $advert = new advert;

  //Строим имя обьекта

  $sysObject = 'advert::admin::banner::delete::'.$place.'::'.$id;

  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

   //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_DELETE)) return;


  //Вызвали class функцию для удаления
  $advert->banner_delete($id);

      sysShowMessage(sysModURL ('advert','admin','main'), 'Баннер удален', 3);
  }
  
  /*
* Изменение сортировки элементов
*/
function advert_admin_item_weight ()  {

	$place_id = sysVarCleanFromInput('place_id');
	$weights = sysVarCleanFromInput('weights');
	$ids = sysVarCleanFromInput('ids');
	
	$items_table = sysDBGetTable('advert_banners');
 
	$sysObject = 'advert::admin::item::weights';

	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_EDIT)) return;
	
	foreach ($ids as $k => $id) {
		sysDbUpdate($items_table, array('weight'=>$weights[$k]), "WHERE $items_table.id=$id");
	}
	
	$http_referer = $_SERVER['HTTP_REFERER'];
	sysRedirect($http_referer);
 
}

?>