<?php
function userlogin_block_display($blockinfo)
  {
  //Прелюдие как у всех блоков
  $sysObject = 'userlogin_block::display::'.$blockinfo['id'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return true;

      $sysModTpl = sysTplWay ($sysObject, $blockinfo['block_tpl']);
      $sysTpl = new sysTpl;
      $sysTpl->cache_lifetime = -1;

      //Прверка в кеше
      if($sysTpl->is_cached($sysModTpl, $sysObject))
        {
        //Возвращаем результат
        $result['block_content'] = $sysTpl->fetch($sysModTpl, $sysObject);
        return $result;
        };
		
		if (sysUserIsLoggedIn())
		{
			$user_vars=sysUserGetVars();
			$sysTpl->assign('loggedin',true);
			$sysTpl->assign('user_vars',$user_vars);
		}

      //ВЫводим содержание блока
      $result['block_content'] = $sysTpl->fetch($sysModTpl, $sysObject);
      return $result;
     
  } 
?>