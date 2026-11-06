<?php

function advert_block_display($blockinfo)
{
	$sysObject = 'advert_block::display::'.$blockinfo['id'];
	$curuser_sec_level = sysSecGetLevel($sysObject);
	//Проверка на доступ
	if (!sysSecAuthAction($sysObject, ACCESS_READ)) return true;
  
	$sysTpl = new sysTpl;
	$sysTpl->cache_lifetime = -1;
	$sysModTpl = sysTplWay ($sysObject);
	//Содержание
	sysModClassLoad ('advert');
	$advert = new advert;

	$place_id = sysBlockGetVar ($blockinfo[id],'place');
	$banner_list = $advert->banner_info_for_place($place_id, $only_active = true);
	
	foreach ($banner_list as $k => $b) {
		$pattern="/".$b['pattern']."/iU";
		if (!preg_match ($pattern, $blockinfo['module_object'])) unset($banner_list[$k]);
		else $banner_list[$k]['additional']=unserialize($banner_list[$k]['additional']);
	}
	
	$banner_count = count($banner_list);
	if ($banner_count ==0)
		return true;

	if ($banner_count>1) $r=rand(0,$banner_count-1);
		else $r=0;
		
	//rsort($banner_list);
	
	$obj=$banner_list[$r];

  $sysTpl->assign('obj',$obj);
  $sysTpl->assign('banner_list',$banner_list);
  $sysTpl->assign('place_id', $place_id);

    //Информацию о блоке
  $sysTpl->assign('blockinfo', $blockinfo);
  $result['block_content'] = $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

?>