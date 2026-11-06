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
function smarty_function_show_block($params, &$smarty)
{
	extract($params);
	//$object
	//$assign
	//$block_id
	
	if (sysVarIsCached('blocks_list', 'dbblockslist'))    {
		$dbblockslist = sysVarGetCached('blocks_list', 'dbblockslist');
    }
    else    {
		$blocks_table = sysDBGetTable('blocks');
		$blocks_column = sysDBGetColumns($blocks_table);
		$sql = "SELECT * FROM $blocks_table FORCE INDEX (block_position) ORDER BY $blocks_column[block_position], $blocks_column[weight]";
		$sql_result = mysql_query($sql);
		if (mysql_errno()!=0) sysException ('sysBlockRenderAll', DATABASE_ERROR, mysql_error()."<br> $sql");
		$dbblockslist = array();
		while ($row = mysql_fetch_assoc($sql_result))   {
			$dbblockslist[] = $row;
        }
		sysVarSetCached('blocks_list', 'dbblockslist', $dbblockslist);
    }
	foreach ($dbblockslist as $block) {
		if (!$block['block_active'])   {
			continue;
        }
		$pattern="/".$block['block_pattern']."/iU";
		if (!preg_match ($pattern, $object))   {
			continue;
        }
		$block['module_object'] = $object;
		
		if ($block['id'] == $block_id) {
			$renblock = sysBlockRender($block);
			break;
		}

	}
	if (!$renblock) return;
	
	if ($assign)
		$smarty->assign($assign, $renblock);
	else 
		return $renblock;
}
?>