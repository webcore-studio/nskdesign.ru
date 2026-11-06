<?php

function search_block_display($blockinfo)
  {
  //Прелюдие как у всех модулей
  $sysObject = 'search_block::display::'.$blockinfo['id'];

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_READ))
    {
    return true;
    }

  $sysModTpl = sysTplWay ($sysObject, $blockinfo['block_tpl']);
  $sysTpl = new sysTpl;
  $sysTpl->caching = -1;

  //Прверка в кеше
  if($sysTpl->is_cached($sysModTpl,$sysObject))
    {
    //Возвращаем результат
    $result['block_content'] = $sysTpl->fetch($sysModTpl,$sysObject);
    return $result;
    };

  //Рендеринг
  $sysTpl->assign('blockinfo', $blockinfo);

  //ВЫводим содержание блока
  $result['block_content'] = $sysTpl->fetch($sysModTpl, $sysObject, $poll_stoped);
  return $result;
  }


?>