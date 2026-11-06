<?php
/**
 * Smarty plugin
 
 */

/**
 * Smarty {html_input_date} plugin
 *
 * Type:     function<br>
 * Name:     html_input_date<br>
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_html_input_date($params, &$smarty)
{

	$html_result = '<script type="text/javascript" src="/scripts/tigra_calendar/calendar_db.js"></script><link rel="stylesheet" type="text/css" href="/scripts/tigra_calendar/calendar.css">';

	if ($params['date'])
		$date = $params['date'];
	else
		$date = time();
	
	if (!is_int($date))
		$date = strtotime($date);
		
	$name = $params['name'];
	if (!$name)
		$name = "pub_date";
	$class = $params['class'];
	$style = $params['style'];
		
	$date = date("Y-m-d", $date);
	$html_result .= '<input type="text" name="'.$name.'" class="'.$class.'" style="'.$style.'" id="'.$name.'" value="'.$date.'">';
	
	$html_result .= '<script language="JavaScript">

	var A_CALTPL = {
		"months" : ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
		"weekdays" : ["вс", "пн", "вт", "ср", "чт", "пт", "сб"],
		"yearscroll": true,
		"weekstart": 1,
		"centyear"  : 70,
		"imgpath" : "/scripts/tigra_calendar/img/"
	}

	new tcal ({
		// if referenced by ID then form name is not required
		"controlname": "'.$name.'"
	}, A_CALTPL);
	</script>';


    return $html_result;
}



?>
