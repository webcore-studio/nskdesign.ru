<?php
/*
 * Главная админская страница
*/
function contact_user_main()
  {
  $sysObject = 'contact::user::main';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

  //Работа с sysTpl
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Выводим список последних сообшений
  sysModClassLoad ('contact');
  $contact = new contact;

  //Общая информация о таблицах и столбцах
  /*$contact_table = sysDBGetTable('contact');
  $contact_column = sysDBGetColumns($contact_table);

  $users_table = sysDBGetTable ('users');
  $users_column = sysDBGetColumns($users_table);*/

  //Пошел вывод переменных
  $sysTpl->assign('email_notify_enable',     sysModGetVar('contact', 'email_notify_enable'));
  $sysTpl->assign('captcha_enabled',          sysModGetVar('contact', 'captcha_enabled'));
  $sysTpl->assign('before_send_text', 			 sysModGetVar('contact', 'before_send_text'));
  $sysTpl->assign('after_send_text', 				 sysModGetVar('contact', 'after_send_text'));

  $rec_list = unserialize(sysModGetVar('contact', 'rec_list'));
  $sysTpl->assign('rec_list', $rec_list);

  //Ид текущего пользователя
  $sysTpl->assign('curuser_id', sysSessionGetVar('user_id'));

  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('contact','user','main'),
                       'displayname'=>'Обратная связь');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

?>