<?php

/*
 * Живой поиск по сайту
*/
function search_ajax_view()
  {
  $search_string = sysVarCleanFromInput('search_string');
  $search_string = video_ajax_utf8_urldecode($search_string);
  $sysObject = 'search::ajax::view::'.$search_string;
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

  //Темы
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  $sysTpl->assign('search_string', $search_string);

  $search_string = sysVarPrepForStore($search_string);

  //Выполняем поиск
  if (strlen($search_string)>=2)
    {
    $search_result = array();
    $args['search_string'] = trim($search_string);
    sysModClassLoad('search');
    $search = new search;
    $search_result = $search->plugin_exe_livesearch_all($args);

    //Все в шаблон
    $sysTpl->assign($search_result);
    }

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl,$sysObject);
  return $result;
  }

/*
 * Декодирование utf8
*/
function video_ajax_utf8_urldecode($str, $to = 'utf-8')
  {
  $to = strtolower($to);
  if ($to == 'utf-8')
    {
    $str = preg_replace( '#%u([0-9A-F]{1,4})#ie', "decode_to_utf8(hexdec('\\1'))", utf8_encode($str) );
    $str = urldecode ($str);
     }
     else
       {
      $str = preg_replace( '#%u([0-9A-F]{1,4})#ie', "'&#' . hexdec('\\1') . ';'", $str );
      $str = urldecode ($str);
       $str = @html_entity_decode($str, ENT_NOQUOTES, $to);
      }

  return $str;
  }

?>