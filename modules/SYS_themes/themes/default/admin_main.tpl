{strip}
  <table class="outer table" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="20%">
        <col width="10%">
        <col width="50%">
        <col width="10%">
        <col width="10%">
      </colgroup>

    <thead>
      <tr align="middle">
        <th colspan="5">Темы</th>
      </tr>

      <tr align="middle">
        <td class="head">Имя</td>
        <td class="head">Версия</td>
        <td class="head">Описание</td>
        <td class="head">Статус</td>
        <td class="head">Действие</td>
      </td>
    </thead>

    <tfoot>
      <tr>
        <td colspan="5" class="foot" align="center">
          <a class="btn" href="{mod_url type='admin' modname='SYS_themes' func='template_main'}">Шаблоны компонентов</a>
        </td>
      </tr>
    </tfoot>


    <tbody>
    {foreach item=theme from=$themes_list}
    {cycle name="themlist" values="even,odd" assign="class" print=false}
      <tr align="middle">
        <td class="{$class}" align="left">{$theme.theme_displayname} ({$theme.theme_name})</td>

        <td align="center" class="{$class}">{$theme.theme_version}</td>
        <td align="left" class="{$class}">{$theme.theme_description}</td>


        {* Состояние текущей темы*}
        <td class="{$class}">
          {if $theme.theme_active}
            Активна
          {else}
            Неактивна
          {/if}
        </td>
        <td class="{$class}">
          {* действия возможные над текущей темой *}
            {array name='url_vars'}
            {array_append name='url_vars' key='id' value=$theme.id}

          {if $theme.theme_active}
            <a href="{mod_url type='admin' modname='SYS_themes' func='theme_deactivate' vars=$url_vars}" title="Деактивировать" onclick="return confirm ('Вы точно хотите деактивировать тему?')"><i class="icon-stop"></i></a>&nbsp;
          {else}
            <a href="{mod_url type='admin' modname='SYS_themes' func='theme_activate' vars=$url_vars}" title="Активировать"><i class="icon-play"></i></a>&nbsp;
          {/if}

        </td>
      </tr>
    {/foreach}
    </tbody>

  </table>
{/strip}