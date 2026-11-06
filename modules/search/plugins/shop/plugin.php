<?php
/*
 * Поиск в товарах
*/

/*
 * Информация о плугине
*/
function search_plugin_shop_info()
{
	$plugin_info['name'] = 'shop';
	$plugin_info['displayname'] = 'Поиск в магазине';
	$plugin_info['version'] = '0.1';
	$plugin_info['description'] = 'Плугин для поиска в магазине';
	$plugin_info['credits'] = '';
	$plugin_info['changelog'] = '';
	$plugin_info['license'] = '';
	$plugin_info['official'] = 1;
	$plugin_info['author'] = '';
	$plugin_info['contact'] = '';
	return $plugin_info;
}

/*
 * Функция поиска
*/
function search_plugin_shop_search($args)
{
	if (!sysModAvailable ('shop'))  return array();

	$search_string = $args['search_string'];
	$price_from = $args['search_param']['price_from'];
	$price_to = $args['search_param']['price_to'];
	$vendor_id = $args['search_param']['vendor_id'];
	$cat_id = $args['search_param']['cat_id'];
	if (isset($args['curuser_sec_level']))
		$curuser_sec_level = $args['curuser_sec_level'];
	else
		$curuser_sec_level = sysSecGetLevel ("shop::user::search");

	sysModClassLoad ('shop');
	$shop = new shop;
	

	$products_table = sysDBGetTable ('shop_products');
	$products_column = sysDBGetColumns ($products_table);
	$links_table = sysDBGetTable ('shop_links');
	$links_column = sysDBGetColumns ($links_table);

	
	
	$select_from = "$products_table LEFT JOIN $links_table  ON $products_column[id] = $links_column[child_id]";
	if ($curuser_sec_level < ACCESS_EDIT)
		$select_from .= " AND $links_column[active] = 1";
		
	$select_columns = array('id' => $products_column['id'],
							'product_code' => $products_column['product_code'],
							'product_displayname' => $products_column['product_displayname'],
							'product_description' => $products_column['product_description'],
							'product_content' => $products_column['product_content'],
							'logo_id' => $products_column['logo_id'],
							'product_price_sale' => $products_column['product_price_sale'],
							'product_price_discount' => $products_column['product_price_discount'],
//							'product_new' => $products_column['product_new'],
//							'product_leader' => $products_column['product_leader'],
							'cat_id' => "$links_column[parent_id] as cat_id",
							'product_active' => "$links_column[active] as product_active");
	
	$select_cond = array();
	if ($search_string) {
		$select_cond[] = "($select_columns[product_displayname] LIKE '%$search_string%' OR 
							$select_columns[product_description] LIKE '%$search_string%'  OR 
							$select_columns[product_content] LIKE '%$search_string%' OR
							$select_columns[product_code] LIKE '%$search_string%'
							)";
		//$select_cond[] = "($select_columns[product_code] LIKE '%$search_string%')";
	}	

	if ($price_from) {
		$select_cond[] = "$select_columns[product_price_sale] >= $price_from";
	}

	if ($price_to) {
		$select_cond[] = "$select_columns[product_price_sale] <= $price_to";
	}	

	if ($vendor_id) {
		$select_cond[] = "$products_column[vendor_id] = $vendor_id";
	}	
	if ($cat_id) {
		$select_cat_cond = " $links_column[parent_id] = $cat_id ";
		$cats_list = $shop->cat_ItemsTreeBuild ($cat_id, $start_level = 0, $include_subcat = true);
		foreach ($cats_list as $cat) 
			$select_cat_cond .= " OR $links_column[parent_id] = $cat[id] ";
		$select_cond[] = $select_cat_cond;
	}

	if ($curuser_sec_level < ACCESS_EDIT) {
		$select_cond[] = " $links_column[active] = 1 ";
	}
		
	$result = sysDbSelect($select_from, $select_columns, "WHERE (".implode(" AND ", $select_cond).") GROUP BY $products_column[id] ", $multi = true);
	
	if (empty($result)) return array();

	$dest_x = sysModGetVar('shop', 'cat_logo_width');
	$dest_y = sysModGetVar('shop', 'cat_logo_height');
	$jpegqual = sysModGetVar('shop', 'cat_logo_qual');
	$how = sysModGetVar('shop','cat_logo_method');
	$modname = 'shop';
	$item_type = 'product';

    sysExtLibLoad('imageinslib');
    $sysInsImage = new sysInsImage();
	
	sysExtLibLoad('pluginslib');
	$sysPlugins = new sysPlugins();
	

	foreach ($result as $k => $product) {
		
		//размеры
		$result[$k]['unit_list'] = $shop->unit_get_list($product['id']);
		//доп.поля
		$result[$k]['fields'] = $sysPlugins -> plugin_exe('shop', 'fields', 'values',  array('id' => $product['id'], 'cat_id' => 0));
		
		//цена
		$result[$k]['product_price'] = $shop->get_user_price($product['id']);
		
		if ($product['logo_id'] <= 0) continue;
		$image_data = $sysInsImage->get_thumb_info($modname ,$item_type, $product['id'], $product['logo_id'], $dest_x, $dest_y, $jpegqual, $how);
		$result[$k]['thumb_url'] = $image_data['thumb_url'];
	}
	
	return ($result);
}

/*
 * Функция живого поиска
*/
function search_plugin_shop_livesearch($args)
{
  
	if (!sysModAvailable ('shop'))  return array();

	$search_string = $args['search_string'];

	$products_table = sysDBGetTable ('shop_products');
	$products_column = sysDBGetColumns ($products_table);
	$links_table = sysDBGetTable ('shop_links');
	$links_column = sysDBGetColumns ($links_table);
	$search_column = array('product_code' => "DISTINCT $products_column[product_displayname] as displayname");
	$from = "$products_table INNER JOIN $links_table ON $links_column[child_id]=$products_column[id] AND $links_column[active]>0";
  
	$result = sysDbSelect($from, $search_column, "WHERE $products_column[product_code] LIKE ('%$search_string%') OR $products_column[product_displayname] LIKE ('%$search_string%') LIMIT 0, 10", true);
	return ($result);
}

/*
 * Функция живого поиска
*/
function search_plugin_shop_param()
{
  
	if (!sysModAvailable ('shop'))  return array();

	// Грузим класс
	sysModClassLoad ('shop');
	$shop = new shop;
	
	// Список производителей
	$param['vendors_list'] = $shop->vendor_get_list();

	return $param;
}

?>
