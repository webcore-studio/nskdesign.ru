{strip}
<table class="outer" cellSpacing="1" cellPadding="4" width="500" align="center">
  <thead>
    <tr>
      <th colspan="4">Ваш профиль</th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td class="odd">
				<ul>
  				{foreach item=plugin from=$plugins_list}
    				<li><a href="{$plugin.exe_url}" title="{$plugin.description}">{$plugin.displayname}</a>
  				{/foreach}
				</ul>
      </td>
    </tr>
  </tbody>
</table>
{/strip}