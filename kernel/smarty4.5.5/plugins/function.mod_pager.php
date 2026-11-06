<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.eightball.php
 * Type:     function
 * Name:     eightball
 * Purpose:  outputs a random magic answer
 * -------------------------------------------------------------
 */
function smarty_function_mod_pager($params, &$smarty)
{
	$html = '<div class="pagination"><ul>';
	$page_current = $params['page_current'];
	$page_total = $params['page_total'];
	$modname = $params['modname'];
	$name = $params['name'];
	$count = $params['count'];
	if (!$count)
		$count = 10;
		
	$show_page = array();
	$page = 1;
	if ($page_total > $count ) {
		while ($page <= $page_total) {
			$show_page[$page] = 0;
			
			if ($page == $page_current - 3 || $page == $page_current + 3)
				$show_page[$page] = -1;
			if ($page == 1 || $page == $page_total)
				$show_page[$page] = 1;
			if ($page == $page_current - 2 || $page == $page_current - 1 || $page == $page_current || $page == $page_current +1 || $page == $page_current + 2)
				$show_page[$page] = 1;
			
			$page++;
		}
		
		if ($page_current == 1) {
			$show_page[4] = $show_page[5] = 1;
			$show_page[6] = -1;
		}
		
		if ($page_current == 2) {
			$show_page[5] = 1;
			$show_page[6] = -1;
		}
		
		if ($page_current == $page_total) {
			$show_page[$page_current-3] = $show_page[$page_current-4] = 1;
			$show_page[$page_current-5] = -1;
		}
		
		if ($page_current == $page_total - 1) {
			$show_page[$page_current-3] =  1;
			$show_page[$page_current-4] = -1;
		}
		
	}
	
	if ($modname == 'content')
		$baseurl = "/$name";
	else
		$baseurl = "/$modname/$name";
		
	$html = '<div class="pagination"><ul>';
	
	if ($page_current==1)
		$html .= "<li class='disabled'><a href='$baseurl' title='Предыдущая страница'><</a></li>";
	else {
		$page_prev = $page_current - 1;
		$html .= "<li><a href='$baseurl/$page_prev' title='Предыдущая страница'><</a></li>";
	}
	
	
	for ($i = 1; $i <= $page_total; $i++) {
		if ($page_total > $count && $show_page[$i] == 0) {
			continue;
		}
		
		if ($page_total > $count && $show_page[$i] == -1) 
			$html .= '<li><span>...</span></li>';
			
		if ($page_total > $count && $show_page[$i] == 1 || $page_total <= 10)	{		
		
			if ($i == $page_current)
				$html .= "<li class='active'><a href='$baseurl/$i'>$i</a></li>";
			else
				$html .= "<li><a href='$baseurl/$i' >$i</a></li>";
			}
		
	}
	
	if ($page_current==$page_total)
		$html .= "<li class='disabled'><a href='$baseurl'  title='Следующая страница'>></a></li>";
	else {
		$page_next = $page_current + 1;
		$html .= "<li><a href='$baseurl/$page_next'  title='Следующая страница'>></a></li>";
	}
	
	$html .= '</ul></div>';

	echo $html;
}
?>