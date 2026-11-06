<?php

function content_init()   {
  global $dbconn;
  
	sysModClassLoad ('SYS_df');
	$SYS_df = new SYS_df;
  
	sysModClassLoad ('SYS_themes');
	$SYS_themes = new SYS_themes();

	// Создаём таблицу ссылок


	$links_table = sysDBGetTable ('content_links');
	$SYS_df->CreateTable($links_table, array(
		'id' =>                   array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '1',
                                    'increment'   => true,
                                    'primary_key' => true),

		'weight' =>                array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '0',
                                    'index'       => true),

		'parent_id' =>            array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '0',
                                    'index'       => true),

		'child_id' =>            array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '0',
                                    'index'       => true),

		'uid'=>                   array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => -1,
                                    'index'       => true),

		'active' =>               array('type'        => 'tinyint',
                                    'size'        => 1,
                                    'null'        => false,
                                    'default'     => 0,
                                    'index'       => true)));

  
	//Создаем таблицу папок
	$folders_table = sysDBGetTable ('content_folders');
	$SYS_df->CreateTable($folders_table, array(
		'id' =>                   array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '1',
                                    'increment'   => true,
                                    'primary_key' => true),

		'parent_id' =>            array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '0',
                                    'index'       => true),

		'weight'=>                array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '0',
                                    'index'       => true),

		'uid'=>                   array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => -1,
                                    'index'       => true),

		'active' =>               array('type'        => 'tinyint',
                                    'size'        => 1,
                                    'null'        => false,
                                    'default'     => 0,
                                    'index'       => true),

		'counter' =>              array('type'        => 'int',
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => 1),

		'subdoc_counter' =>       array('type'        => 'int',
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => 0),

		'subfolder_counter' =>    array('type'        => 'int',
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => 0),

		'subfolder_orderby' =>    array('type'        => 'varchar',
                                    'size'        => 20,
                                    'null'        => false,
                                    'default'     => 'displayname'),

		'subfolder_order_asc' =>  array('type'        => 'tinyint',
                                    'size'        => 1,
                                    'null'        => false,
                                    'default'     => 1),

		'subdoc_orderby' =>       array('type'        => 'varchar',
                                    'size'        => 20,
                                    'null'        => false,
                                    'default'     => 'displayname'),

		'subdoc_order_asc' =>     array('type'        => 'tinyint',
                                    'size'        => 1,
                                    'null'        => false,
                                    'default'     => 1),

		'subdoc_perpage_user' =>  array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => 10),

		'subdoc_perpage_admin' => array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => 100),

		'limit_doc_perfolder' =>  array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => 0),

		'limit_doc_olderdays' =>  array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => 0),

		'need_admin_active' =>    array('type'        => 'tinyint',
                                    'size'        => 1,
                                    'null'        => false,
                                    'default'     => 1),

		'open_subfolders' =>    array('type'        => 'tinyint',
                                    'size'        => 1,
                                    'null'        => false,
                                    'default'     => 0),								
									
		'mailon_docs_change' =>   array('type'        => 'tinyint',
                                    'size'        => 1,
                                    'null'        => false,
                                    'default'     => 0),
									
		'need_captcha' =>   array('type'        => 'tinyint',
                                    'size'        => 1,
                                    'null'        => false,
                                    'default'     => 0),
	
		'displayname' =>          array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'index'      => true,
                                    'default'     => ''),

		'name' =>       	   array('type'        => 'varchar',
                                    'size'        => 128,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'index'      => true,
                                    'default'     => ''),


		'description' =>          array('type'        => 'text',
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

		'content' =>              array('type'        => 'mediumtext',
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

		'logo' =>                 array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),
		'meta_keywords' =>          array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),
		'meta_description' =>          array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),
														
		'meta_title' =>          array('type'        => 'varchar',
                                    'size'        => 128,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),
												
		'add_doc_message' =>          array('type'        => 'varchar',
                                    'size'        => 128,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),
		'tpl' =>         			 array('type'        => 'varchar',
                                    'size'        => 128,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => '')));

  
	//Создаем таблицу документов
	$docs_table = sysDBGetTable ('content_docs');
	$SYS_df->CreateTable($docs_table, array(
		'id' =>                   array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '1',
                                    'increment'   => true,
                                    'primary_key' => true),

		'fid' =>                  array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '0',
                                    'index'       => true),

		'parent_id' =>            array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '0',
                                    'index'       => true),

		'weight'=>                array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '0',
                                    'index'       => true),

		'uid'=>                   array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => -1,
                                    'index'       => true),

		'active' =>               array('type'        => 'tinyint',
                                    'size'        => 1,
                                    'null'        => false,
                                    'default'     => 0,
                                    'index'       => true),

		'doc_subdoc_counter' =>   array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => 0,
                                    'index'       => true),

		'pub_datetime' =>         array('type'        => 'datetime',
                                    'null'        => false,
                                    'default'     => '0000-00-00 00:00:00',
                                    'canmodify'   => true,
                                    'index'       => true),

		'counter' =>              array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => 1,
                                    'canmodify'   => true,
                                    'index'       => true),

		'popularity' =>              array('type'        => 'float',
                                    'null'        => true,
                                    'default'     => NULL,
                                    'canmodify'   => true),

		'from_ip' =>              array('type'        => 'varchar',
                                    'size'        => 20,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

		'displayname' =>          array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'default'     => '',
                                    'canmodify'   => true,
                                    'fulltext'    => false,
                                    'index'      => true),
		'name' =>       	   array('type'        => 'varchar',
                                    'size'        => 128,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'index'      => true,
                                    'default'     => ''),

		'description' =>           array('type'        => 'text',
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => '',
                                    'fulltext'    => false),

		'content' =>              array('type'        => 'mediumtext',
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'fulltext'    => false,
                                    'default'     => ''),

		'url' =>                  array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

		'url_counter' =>          array('type'        => 'int',
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => 1),

		'author' =>               array('type'        => 'varchar',
                                    'size'        => 120,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

		'author_email' =>         array('type'        => 'varchar',
                                    'size'        => 60,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),

		'logo' =>                 array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),
									
		'logo_id' =>                 array('type'        => 'int',
                                    'size'        => 11,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => -1),

		'misc_varchar_a' =>       array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),
		'meta_keywords' =>          array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),
		'meta_description' =>          array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),
													
		'meta_title' =>          array('type'        => 'varchar',
                                    'size'        => 128,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),
									
		'tpl' =>         			 array('type'        => 'varchar',
                                    'size'        => 128,
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => '')));

	// Создаём таблицу изображений
	$images_table = sysDBGetTable ('content_images');
	$SYS_df->CreateTable($images_table, array(
		'id' =>                   array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '-1',
                                    'increment'   => true,
                                    'primary_key' => true),

		'weight'=>                array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '0',
                                    'index'       => true),

		'doc_id' =>            array('type'        => 'int',
                                    'null'        => false,
                                    'default'     => '-1',
                                    'index'       => true),

		'image_displayname' =>          array('type'        => 'varchar',
                                    'size'        => 255,
                                    'null'        => false,
                                    'default'     => '',
                                    'canmodify'   => true,
                                    'index'      => true),

		'image_description' =>           array('type'        => 'text',
                                    'null'        => false,
                                    'canmodify'   => true,
                                    'default'     => ''),
		'image_width' => array('type'       => 'int',
                               'null'       => false,
                               'default'    => 150),

		'image_height' => array('type'      => 'int',
                               'null'       => false,
                               'default'    => 150),

		'image_time' =>  array('type'       => 'int',
								'size' => 11,
                               'null'       => false,
                               'default'    => 100),
		'image_type'=>array('type'       => 'varchar',
							'size'       => 5,
                               'null'       => false,
                               'default'    => '')));


	//индексы

  // Индексы и стандартные разделы создаются при каждой инициализации модуля.
  // Фиксированные ID используются в шаблонах и правилах доступа.
  $init_queries = array(
    "ALTER TABLE `$docs_table` ADD FULLTEXT (`displayname`, `description`, `content`)",
    "ALTER TABLE `$folders_table` ADD FULLTEXT (`displayname`, `description`, `content`)",
    "INSERT INTO `$folders_table`
      (`id`, `active`, `weight`, `subdoc_orderby`, `subdoc_order_asc`, `displayname`, `name`, `description`, `content`, `mailon_docs_change`, `add_doc_message`)
      VALUES
      (1, 1, 1, 'pub_datetime', 0, 'Новости', 'news', '', '', 0, ''),
      (2, 1, 2, 'pub_datetime', 0, 'О компании', 'about', '', '', 0, ''),
      (3, 1, 3, 'pub_datetime', 0, 'Вопрос-ответ', 'faq', '', '', 1, 'Ваш вопрос принят, спасибо!'),
      (4, 1, 4, 'pub_datetime', 0, 'Контакты', 'contacts', '', '', 0, '')"
  );
  foreach ($init_queries as $sql) {
    try {
      $result = mysqli_query($dbconn, $sql);
    } catch (mysqli_sql_exception $e) {
      sysException('content_init', DATABASE_ERROR, $e->getMessage());
      return false;
    }
    if ($result === false) {
      sysException('content_init', DATABASE_ERROR, mysqli_error($dbconn));
      return false;
    }
  }
  

	//  Шаблоны  

  //Регистрируем темплеи в системе сначала шаблоны просмотра
  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'user_main.tpl',
                              'tpl_pattern'=>'content::user::main',
                              'tpl_description'=>'Главная страница'));
  
  // Настройка
  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_config_modify.tpl',
                              'tpl_pattern'=>'content::admin::config::modify',
                              'tpl_description'=>'Настройка'));


  // RSS
  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'rss_news_list.tpl',
                              'tpl_pattern'=>'content::xml::rss::list',
                              'tpl_description'=>'RSS лента'));

  // Atom
  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'atom_news_list.tpl',
                              'tpl_pattern'=>'content::xml::atom::list',
                              'tpl_description'=>'RSS лента'));


   // Просмотр раздела и документа
  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'news/user_folder_view.tpl',
                              'tpl_pattern'=>'^content::user::folder::view::1(::|$)',
                              'tpl_description'=>'Просмотр списка новостей'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'news/user_doc_view.tpl',
                              'tpl_pattern'=>'^content::user::doc::view::1(::|$)',
                              'tpl_description'=>'Просмотр новости'));



  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'faq/user_folder_view.tpl',
                              'tpl_pattern'=>'^content::user::folder::view::3(::|$)',
                              'tpl_description'=>'Просмотр faq'));


 /* $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'feedback/user_folder_view.tpl',
                              'tpl_pattern'=>'^content::user::folder::view::4(::|$)',
                              'tpl_description'=>'Просмотр раздела Заявки'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'feedback/user_doc_view.tpl',
                              'tpl_pattern'=>'^content::user::doc::view::4(::|$)',
                              'tpl_description'=>'Просмотр заявки'));*/



  //Шаблоны документов
  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'user_folder_view.tpl',
                              'tpl_pattern'=>'content::user::folder::view',
                              'tpl_description'=>'Просмотр папки'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'user_doc_view.tpl',
                              'tpl_pattern'=>'content::user::doc::view',
                              'tpl_description'=>'Просмотр документа'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'user_subdoc_view.tpl',
                              'tpl_pattern'=>'content::user::subdoc::view',
                              'tpl_description'=>'Просмотр страницы'));

  ////////////////////////////////////////////////////////////////////////////////
  /////////////   Административная часть (шаблоны добавления и т.д.)  ////////////
  ////////////////////////////////////////////////////////////////////////////////


  // faq
  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'faq/admin_doc_new.tpl',
                              'tpl_pattern'=>'^content::admin::doc::new::3(::|$)',
                              'tpl_description'=>'Добавление faq'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'faq/admin_doc_modify.tpl',
                              'tpl_pattern'=>'^content::admin::doc::modify::3(::|$)',
                              'tpl_description'=>'Редактирование faq'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'faq/admin_doc_copy_dg.tpl',
                              'tpl_pattern'=>'^content::admin::doc::copy_dg::3(::|$)',
                              'tpl_description'=>'Копировать faq'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'faq/admin_doc_confirm_delete.tpl',
                              'tpl_pattern'=>'^content::admin::doc::confirm_delete::3(::|$)',
                              'tpl_description'=>'Подтверждение удаления faq'));
							  
	$SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'faq/mail_new_question_webcore.tpl',
                              'tpl_pattern'=>'^content::mail::new_order_webcore::3(::|$)',
                              'tpl_description'=>'Уведомление о новом вопросе'));

	$SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'faq/mail_author_reply.tpl',
                              'tpl_pattern'=>'^content::mail::reply_to_author',
                              'tpl_description'=>'Ответ на вопрос почтой'));						  
	
	
  // заявка
/*  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'feedback/admin_doc_new.tpl',
                              'tpl_pattern'=>'^content::admin::doc::new::4(::|$)',
                              'tpl_description'=>'Создание заявки'));



  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'feedback/mail_new_order_webcore.tpl',
                              'tpl_pattern'=>'^content::mail::new_order_webcore',
                              'tpl_description'=>'Почта владельцу сайта'));*/

  //Создание разделов, везде одинаковое
  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_folder_new.tpl',
                              'tpl_pattern'=>'content::admin::folder::new',
                              'tpl_description'=>'Создание новой папки'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_folder_modify.tpl',
                              'tpl_pattern'=>'content::admin::folder::modify',
                              'tpl_description'=>'Редактирование папки'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_folder_copy_dg.tpl',
                              'tpl_pattern'=>'content::admin::folder::copy_dg',
                              'tpl_description'=>'Копирование папки'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_folder_confirm_delete.tpl',
                              'tpl_pattern'=>'content::admin::folder::confirm_delete',
                              'tpl_description'=>'Подтверждение удаления папки'));





  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_doc_new.tpl',
                              'tpl_pattern'=>'content::admin::doc::new',
                              'tpl_description'=>'Создание нового документа'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_doc_modify.tpl',
                              'tpl_pattern'=>'content::admin::doc::modify',
                              'tpl_description'=>'Редактирование документа'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_doc_copy_dg.tpl',
                              'tpl_pattern'=>'content::admin::doc::copy_dg',
                              'tpl_description'=>'Копирование документа'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_doc_links_dg.tpl',
                              'tpl_pattern'=>'content::admin::doc::links_dg',
                              'tpl_description'=>'Управление ссылками на документ'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_doc_confirm_delete.tpl',
                              'tpl_pattern'=>'content::admin::doc::confirm_delete',
                              'tpl_description'=>'Подтверждение удаления документа'));



  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_subdoc_new.tpl',
                              'tpl_pattern'=>'content::admin::subdoc::new',
                              'tpl_description'=>'Создание нового документа вложенного в документ'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_subdoc_modify.tpl',
                              'tpl_pattern'=>'content::admin::subdoc::modify',
                              'tpl_description'=>'Редактирование документа вложенного в документ'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_subdoc_confirm_delete.tpl',
                              'tpl_pattern'=>'content::admin::subdoc::confirm_delete',
                              'tpl_description'=>'Подтверждение удаления документа в документе'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'rss_news_list.tpl',
                              'tpl_pattern'=>'content::rss::news::list',
                              'tpl_description'=>'RSS'));


  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_image_modify.tpl',
                              'tpl_pattern'=>'content::admin::image::modify',
                              'tpl_description'=>'Редактирование фото'));

  $SYS_themes->tpl_add (array('tpl_compname'=>'content',
                              'tpl_name'=>'admin_image_confirm_delete.tpl',
                              'tpl_pattern'=>'content::admin::image::confirm_delete',
                              'tpl_description'=>'Подтверждение удаления фото'));



  // Переменные модуля
  sysModSetVar ('content', 'orig_image_width', '2048');
  sysModSetVar ('content', 'orig_image_height', '1536');
  sysModSetVar ('content', 'orig_image_qual', '100');
  sysModSetVar ('content', 'orig_image_changequal', false);
  sysModSetVar ('content', 'orig_image_maxsize', '4096');

  sysModSetVar ('content', 'image_width', '278');
  sysModSetVar ('content', 'image_height', '173');
  sysModSetVar ('content', 'image_qual', '100');
  sysModSetVar ('content', 'image_method', 'xy');
  
  sysModSetVar ('content', 'thumb_width', '278');
  sysModSetVar ('content', 'thumb_height', '173');
  sysModSetVar ('content', 'thumb_qual', '100');
  sysModSetVar ('content', 'thumb_method', 'xy');
  
  sysModSetVar ('content', 'logo_thumb_width', '278');
  sysModSetVar ('content', 'logo_thumb_height', '173');
  sysModSetVar ('content', 'logo_thumb_qual', '100');
  sysModSetVar ('content', 'logo_thumb_method', 'xy');
  
  sysModSetVar ('content', 'logo_width', '278');
  sysModSetVar ('content', 'logo_height', '173');
  sysModSetVar ('content', 'logo_qual', '100');
  sysModSetVar ('content', 'logo_method', 'xy');

  sysModSetVar ('content', 'url_control', '0');
  sysModSetVar ('content', 'names_control', '0');
  sysModSetVar ('content', 'upload_avail', 'doc docx xls xlsx jpg png txt gif pdf rtf bmp jpeg tif');
  sysModSetVar ('content', 'resize_type', 'ex');
  sysModSetVar ('content', 'ajax_paging', '0');
  
  
  sysModSetVar ('content', 'rss_doc_count', '10');
  sysModSetVar ('content', 'rss_folder_list', serialize(array(0 => 1)));

  sysVarDelCached('content_folder_tree'); //Чистим кеш переменных



	// Initialisation successful
	return true;
}

function content_upgrade($oldversion)  {
	// Update successful
	return false;
}

function content_delete()  {
	// Deletion successful
	return true;
}


function content_activate()  {
	return true;
}

function content_deactivate()  {
	return true;
}

?>
