<?php
/*
 * Главная админская страница
*/
function comment_user_main()
  {
	global $dbconn;

  $sysObject = 'comment::user::main';

  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return;

  //Работа с sysTpl
  $sysTpl = new sysTpl;
  $sysModTpl = sysTplWay ($sysObject);
  $sysTpl->caching = false;

  //Выводим список последних комментариев
  sysModClassLoad ('comment');
  $comment = new comment;

  //Общая информация о таблицах и столбцах
  $comment_table = sysDBGetTable('comment');
  $comment_column = sysDBGetColumns($comment_table);

  $users_table = sysDBGetTable ('users');
  $users_column = sysDBGetColumns($users_table);

	//Выборка
  $sql = "SELECT $comment_table.*,
                 $users_column[id] as user_id, $users_column[user_name] as user_id_name,
                 $users_column[user_displayname] as user_id_displayname,
                 $users_column[user_regdate] as user_id_regdate,
                 $users_column[user_avatar_w] as user_id_avatar_w, $users_column[user_avatar_h] as user_id_avatar_h,
                 $users_column[user_avatar] as user_id_avatar, $users_column[user_rank] as user_id_rank
            FROM $comment_table
              LEFT JOIN $users_table ON $comment_table.user_id = $users_table.id
                ORDER BY $comment_column[comm_addtime] DESC
                	LIMIT 50";

  $mysql_result = $dbconn->query($sql);
  if (mysqli_connect_errno()!=0) sysException ('comment_user_main', DATABASE_ERROR, mysqli_connect_error()."<br> $sql");
  $comment_list = array();
  while ($row = mysqli_fetch_assoc($mysql_result)) $comment_list[] = $row;
  mysqli_free_result($mysql_result);

  
  	sysModClassLoad ('content');
    $content = new content;
	if (sysModAvailable('polls'))
	{
		sysModClassLoad ('polls');
		$polls = new polls;
	}
	if (sysModAvailable('shop'))
	{
		sysModClassLoad ('shop');
		$shop = new shop;
	}
	foreach ($comment_list as $key=>$comment)
	{
		if ($comment['comm_fields'])
			$comment_list[$key]['comm_fields'] = unserialize($comment['comm_fields']);
		if ($comment['comm_module']=='content')
		{
			$doc_info=$content->doc_get_info($comment['comm_key_b']);
			if (empty($doc_info)) {unset($comments_list[$key]);continue;}
			if (!$doc_info['name'] or $doc_info['name']=='' or $doc_info['name']==' ') $doc_info['name']=$doc_info['id'];
			if ($comment['comm_key_a']) $folder_info=$content->folder_get_info($comment['comm_key_a']);
				else 
				{
					$folders=$content->get_documents_folders($comment['comm_key_b']);
					$folder_info=$content->folder_get_info($folders[0]);	
				}
			if (empty($folder_info)) {unset($comments_list[$key]);continue;}
			if (!$folder_info['name'] or $folder_info['name']=='' or $folder_info['name']==' ') $folder_info['name']=$folder_info['id'];
			$comment_list[$key]['doc_info']=$doc_info;
			$comment_list[$key]['folder_info']=$folder_info;
		}
		else if ($comment['comm_module']=='shop')
		{
			$product_info=$shop->product_get_info($comment['comm_key_b'],$comment['comm_key_a']);
			$comment_list[$key]['product_info']=$product_info;
		}
		
		else if ($comment['comm_module']=='polls')
		{
			$poll_info=$polls->poll_get_info($comment['comm_key_b']);
			$comment_list[$key]['poll_info']=$poll_info;
		}
	}
	
  
  $sysTpl->assign('comment_list', $comment_list);

  //BrowseIn
  $browsein = array();
  $browsein[] = array ('url'=>sysModURL('comment','user','main'),
                       'displayname'=>'Последние отзывы');
  $sysTpl->assign('browsein', $browsein);

  //Вывод результатов
  $result['object'] =& $sysObject;
  $result['content'] =& $sysTpl->fetch($sysModTpl, $sysObject);
  $result['browsein'] =& $browsein;
  return $result;
  }

?>