{strip}
  <form name="subdoc_modify_form" action="{mod_url type='admin' modname='content' func='subdoc_update'}" method="post">
  <input type="hidden" name="id" value="{$id}">
  <input type="hidden" name="http_referer" value="{$http_referer}">
    <table class="outer" cellSpacing="1" cellPadding="4">


        <tr>
          <th colSpan="2">{#editingpage#}</th>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#folder#}</td>
          <td class="even">

            {* Формируем плоский масив для вункции html_options *}
      {array name='flat_subdocslist'}

      {foreach item=subdoc from=$doc_subdocs_list}
      {array_append name='flat_subdocslist' key=$subdoc.id value=$subdoc.displayname|tree:$subdoc.level}
            {/foreach}

      {array_append name='flat_subdocslist' key=$rootdoc_info.id value=#doc#}

      <select name=parent_id>
      {html_options options=$flat_subdocslist selected=$parent_id}
      </select>

    </td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#pagedisplayname#}</td>
          <td class="even"><input maxLength="255" size="60" name="displayname" value="{$displayname|escape}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#description#}</td>
          <td class="even"><textarea name="description" rows="5" cols="78">{$description|escape}</textarea></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#content#}</td>
          <td class="even">{texteditor name="content" text=$content}
            <br>
              {#for_page_break#}
          </td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#logo#}</td>
          <td class="even">
            <input maxLength="60" size="50" name="logo" value="{$logo}">&nbsp;
            {imagebrowse action="document.subdoc_modify_form.logo.value"}
          </td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#author#}</td>
          <td class="even"><input maxLength="60" size="50" name="author" value="{$author}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#author_email#}</td>
          <td class="even"><input maxLength="60" size="50" name="author_email" value="{$author_email}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#author_url#}</td>
          <td class="even"><input maxLength="60" size="50" name="url" value="{$url}"></td>
        </tr>

        {array name='asc_yes_no'}
        {array_append name='asc_yes_no' key='1' value=#yes#}
        {array_append name='asc_yes_no' key='0' value=#no#}

        <tr vAlign="top" align="left">
          <td class="head">{#active#}</td>
          <td class="even">
            {html_radios name="active" options=$asc_yes_no checked=$active separator=" "}
          </td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#pub_datetime#}</td>
          <td class="even">
          {html_select_date time=$pub_datetime start_year="-1" end_year="+1" field_order=DMY prefix='pub_date_'}&nbsp;
          {#time#}: {html_select_time time=$pub_datetime display_seconds=false prefix='pub_time_'}
          </td>
        </tr>


        <tr vAlign="top" align="center">
          <td class="foot" colspan="2">
            <input class="formButton" type="submit" value="{#edit#}" name="submit">
          </td>
        </tr>

    </table>
  </form>


{* Дальше идут фишки для редактирования документы вложенные в документ *}
<br>
<table class="outer" cellSpacing="1" cellPadding="4">
<tr>
  <th colSpan="4">{#pagesindoc#}</th>
</tr>
  {if $subdocs_list}

  {foreach item=subdoc from=$subdocs_list}

    {* Формируем масив переменные для урла *}
    {array name='url_vars'}
    {array_append name='url_vars' key='id' value=$subdoc.id}

  <tr>

    <td class="even" nowrap width="60%">
      {* Формируем масив переменные для урла *}
      <a href="{mod_url type='user' modname='content' func='subdoc_view' vars=$url_vars}">{$subdoc.displayname|escape|tree:$subdoc.level}</a>
    </td>

    <td class="even" align="center" nowrap width="5%">
      {if $subdoc.weight_moveup_url}
      <a href="{$subdoc.weight_moveup_url}"><img border="0" src="files/shared/images/system/up.gif"></a>
      {/if}

      {if $subdoc.weight_movedown_url}
      <a href="{$subdoc.weight_movedown_url}"><img border="0" src="files/shared/images/system/down.gif"></a>
      {/if}
    </td>

    <td class="even" align="center" nowrap width="35%">
        <a href="{mod_url type='admin' modname='content' func='subdoc_modify' vars=$url_vars}">{#edit#}</a>&nbsp;|&nbsp;
        <a href="{mod_url type='admin' modname='content' func='subdoc_new' vars=$url_vars}">{#add#}</a>&nbsp;|&nbsp;
        <a href="{mod_url type='admin' modname='content' func='subdoc_confirm_delete' vars=$url_vars}">{#delete#}</a>
    </td>

  </tr>

  {/foreach}

  <tr>
    <td colSpan="4" align="left" class="even">
      {#for_subpages_tree#}
    <br>
      {#for_subpages_list#}
    </td>
  </tr>


  {else}
  <tr>
    <td colSpan="4" align="center" class="even">Нет страниц вложенных в документ</td>
  </tr>
  {/if}

</table>
<br>
{* URL добавление документа/раздела *}
   {if $curuser_sec_level>=70}
     <center>
        [&nbsp;
        {* Формируем масив переменные для урла *}
        {array name='url_vars'}
        {array_append name='url_vars' key='id' value=$id}
        <a href="{mod_url type='admin' modname='content' func='subdoc_new' vars=$url_vars}">{#addpage#}</a>
        &nbsp;]
     </center>
  {/if}

{/strip}