{strip}
<form action="/index.php"  method="get" name="search">
<input type="hidden" name="module" value="search">
<input type="hidden" name="func" value="view">
<input name="result_orderby" value="score" type="hidden">
<input name="result_order_asc" value="0" type="hidden">

<div class="input-append">
	<input class="span2" type="text" id="mod_search_string" autocomplete="off" onkeyup="search_onkeyup(event);" onkeydown="search_onkeydown(event);" onblur="search_hide();" maxlength="100" size="20" name="search_string" value="">
	<button class="btn" type="submit" style="height:30px"><img src="/modules/search/images/lupa.gif" ></button>
</div>
<div id="mod_search_list" class="LiveTextResult"></div>	

</form>

{/strip}