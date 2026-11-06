{strip}
{include file='modules/search/themes/default/include/search_form.tpl'}


{if $curuser_sec_level>=70}
  <br>
  <center>
	<a class="btn" href="{mod_url type='admin' modname='search' func='modifyconfig'}">Конфигурация</a>
  </center>
{/if}

{/strip}