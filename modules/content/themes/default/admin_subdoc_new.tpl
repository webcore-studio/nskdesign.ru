{strip}
  <form name="subdoc_new_form" action="{mod_url type='admin' modname='content' func='subdoc_create'}" method="post">
  <input type="hidden" name="id" value="{$id}">
  <input type="hidden" name="http_referer" value="{$http_referer}">
    <table class="outer" cellSpacing="1" cellPadding="4">

        <tr>
          <th colSpan="2">{#addingpage#}</th>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#folder#}</td>
          <td class="even">


            {* Формируем плоский масив для вункции html_options *}
      {array name='flat_subdocslist'}

      {foreach item=subdoc from=$subdocs_list}
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
          <td class="even"><input maxLength="255" size="60" name="displayname" value="{$displayname}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#description#}</td>
          <td class="even"><textarea name="description" rows="5" cols="78">{$description}</textarea></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#content#}</td>
          <td class="even">{texteditor name="content" text=$content}</td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#logo#}</td>
          <td class="even">
            <input maxLength="60" size="50" name="logo" value="{$logo}">&nbsp;
            {imagebrowse action="document.subdoc_new_form.logo.value"}
          </td>
        </tr>

        {usergetvars assign=uservars}

        <tr vAlign="top" align="left">
          <td class="head">{#author#}</td>
          <td class="even"><input maxLength="60" size="50" name="author" value="{$uservars.displayname}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#author_email#}</td>
          <td class="even"><input maxLength="60" size="50" name="author_email" value="{$uservars.femail}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#author_url#}</td>
          <td class="even"><input maxLength="60" size="50" name="url" value="{$uservars.url}"></td>
        </tr>

        {array name='asc_yes_no'}
        {array_append name='asc_yes_no' key='1' value=#yes#}
        {array_append name='asc_yes_no' key='0' value=#no#}

        <tr vAlign="top" align="left">
          <td class="head">{#active#}</td>
          <td class="even">
            {html_radios name="active" options=$asc_yes_no checked=1 separator=" "}
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
            <input class="formButton" type="submit" value="{#add#}" name="submit">
          </td>
        </tr>


    </table>
  </form>
{/strip}