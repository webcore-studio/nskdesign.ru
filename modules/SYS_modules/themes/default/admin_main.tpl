{strip}
  <table class="table outer" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="20%">
        <col width="47%">
        <col width="10%">
        <col width="10%">
        <col width="10%">
        <col width="3%">
      </colgroup>

    <thead>
      <tr align="middle">
        <th colspan="6">Модули</th>
      </tr>

      <tr align="middle">
        <td class="head" nowrap>Отображаемое имя</td>
        <td class="head">Описание</td>
        <td class="head">Версия</td>
        <td class="head">Тип</td>
        <td class="head">Состояние</td>
        <td class="head">Действие</td>
      </tr>
    </thead>

    <tbody>
      {foreach item=module from=$modules_list}
      {cycle name="mod" values="even,odd" assign="class" print=false}
      <tr align="middle">
        <td class="{$class}" align="left">
			{if $module.mod_type == "kernel"}
				<a href="/index.php?module={$module.mod_name}&type=admin">{$module.mod_displayname|escape}</a>
			{else}
				<a href="/index.php?module={$module.mod_name}&type={$module.mod_type}">{$module.mod_displayname|escape}</a>
			{/if}
		</td>
        <td class="{$class}" align="left">{$module.mod_description|escape}</td>
        <td class="{$class}">{$module.mod_version|escape}</td>
        <td class="{$class}">{$module.mod_type|escape}</td>
        <td class="{$class}">{$module.mod_state|escape}</td>
        <td class="{$class}">
           {* В зависимости от состаяния модуля выводим действия *}
            {array name='url_vars'}
            {array_append name='url_vars' key='id' value=$module.id}

              <a href="{mod_url type='admin' modname='SYS_modules' func='info' vars=$url_vars}" title="Информация"><i class="icon-info-sign"></i></a>&nbsp;

            {if $module.mod_type!='kernel'}{* Модуля ядра трогать низя *}

              {if $module.mod_state=='ACTIVE'}
                <a href="{mod_url type='admin' modname='SYS_modules' func='deactivate' vars=$url_vars}" title="Деактивировать" onclick="return confirm ('Вы точно хотите деактивировать модуль?')"><i class="icon-stop"></i></a>&nbsp;
              {elseif $module.mod_state=='INACTIVE'}
                <a href="{mod_url type='admin' modname='SYS_modules' func='activate' vars=$url_vars}" title="Активировать"><i class="icon-play"></i></a>&nbsp;
                <a href="{mod_url type='admin' modname='SYS_modules' func='delete' vars=$url_vars}" title="Удалить" onclick="return confirm ('Вы точно хотите удалить модуль? Это повлечет сброс всех настроек, шаблонов и возможно удаление данных из БД')"><i class="icon-remove"></i></a>&nbsp;
              {elseif $module.mod_state=='UNINITIALISED'}
                <a href="{mod_url type='admin' modname='SYS_modules' func='initialise' vars=$url_vars}" title="Инициализация"><i class="icon-inbox"></i></a>
              {/if}

            {/if}

        </td>
      </tr>
      {/foreach}
    </tbody>

  </table>
{/strip}