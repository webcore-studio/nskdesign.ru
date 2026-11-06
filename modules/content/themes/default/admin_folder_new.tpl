{strip}
  <form name="folder_new_form" action="{mod_url type='admin' modname='content' func='folder_create'}" method="post" enctype="multipart/form-data">
  <input type="hidden" name="id" value="{$id}">
  <input type="hidden" name="http_referer" value="{$http_referer}">
    <table class="outer" cellSpacing="1" cellPadding="4">

        <tr>
          <th>{#addingfolder#}</th>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#folder#}<br>

            {* ‘ормируем плоский масив дл¤ вункции html_options *}
      {array name='flat_folderslist'}
      {array_append name='flat_folderslist' key='0' value=#root#}

      {foreach item=folder from=$folders_list}
      {array_append name='flat_folderslist' key=$folder.id value=$folder.displayname|tree:$folder.level}
            {/foreach}

      <select name=parent_id class="span7">
      {html_options options=$flat_folderslist selected=$parent_id}
      </select>

    </td>
        </tr>


        <tr vAlign="top" align="left">
          <td class="head">{#folderdisplayname#}<br><input class="span7" type="text" maxLength="255" size="50" name="displayname" value="{$displayname}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#pseudonym#}<br><input class="span7" type="text" maxLength="128" size="50" name="name" value="{$name}"></td>
        </tr>


        <tr vAlign="top" align="left">
          <td class="head">{#description#}<br><textarea name="description" rows="5" class="span7">{$description}</textarea></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#content#}<br>{texteditor name="content" text=$content}</td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#logo#}<br>
            <input maxLength="255" class="span7" name="logo" value="{$logo}">&nbsp;
            {imagebrowse action="document.folder_new_form.logo.value"}
          </td>
        </tr>

        {array name='yes_no'}
        {array_append name='yes_no' key='1' value=#yes#}
        {array_append name='yes_no' key='0' value=#no#}

        <tr vAlign="top" align="left">
          <td class="head">{#active#}<br>
            {html_radios name="active" options=$yes_no checked=1 separator=" "}
          </td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#need_admin_active#}<br>
            {html_radios name="need_admin_active" options=$yes_no checked=1 separator=" "}
          </td>
        </tr>

        {array name='asc_yes_no'}
        {array_append name='asc_yes_no' key='1' value=#asc#}
        {array_append name='asc_yes_no' key='0' value=#desc#}
		
		<tr vAlign="top" align="left">
          <td class="head">{#open_subfolders#}<br>
            {html_radios name="open_subfolders" options=$yes_no checked=0 separator=" "}
          </td>
        </tr>
		
		
		<tr vAlign="top" align="left">
          <td class="head">{#mailto_admin#}<br>
            {html_radios name="mailon_docs_change" options=$yes_no checked=0 separator=" "}
          </td>
        </tr>
		
		 <tr vAlign="top" align="left">
          <td class="head">{#need_captcha#}<br>
            {html_radios name="need_captcha" options=$yes_no checked=0 separator=" "}
          </td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#subfolder_orderby#}<br>
            <select name=subfolder_orderby>
            {html_options options=$folder_orderslist selected="displayname"}
            </select>
            {html_radios name="subfolder_order_asc" options=$asc_yes_no checked=1 separator=" "}
          </td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#subdoc_orderby#}<br>
            <select name=subdoc_orderby>
            {html_options options=$doc_orderslist selected="displayname"}
            </select>
            {html_radios name="subdoc_order_asc" options=$asc_yes_no checked=1 separator=" "}
          </td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#subdoc_perpage_admin#}<br><input type="text" class="span1" maxLength="8" size="10" name="subdoc_perpage_admin" value="100"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#subdoc_perpage_user#}<br><input type="text" class="span1" maxLength="8" size="10" name="subdoc_perpage_user" value="20"></td>
        </tr>


        <tr vAlign="top" align="left">
          <td class="head">{#limit_doc_olderdays#}<br><input type="text" maxLength="8" class="span1" size="10" name="limit_doc_olderdays" value="0"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#limit_doc_perfolder#}<br><input type="text" maxLength="8" class="span1" size="10" name="limit_doc_perfolder" value="0"></td>
        </tr>
		
	  
	  <tr>
        <td class="head">{#add_doc_text#}<br>
          <input class="span7" type="text" size="100" name="add_doc_message" value="{$add_doc_message|escape}" maxlength="128">
        </td>
      </tr>
	  
	  <tr vAlign="top" align="left">
          <td class="head">Ўаблон<br><input type="text" class="span7" maxLength="128" size="60" name="tpl" value="{$tpl}"></td>
        </tr>

	</table>
		
	<div class="item">
		{include file='modules/SYS_config/themes/default/include/meta_info.tpl'}
	</div>
	
	<div>
		<input class="btn formButton" type="submit" value="{#save#}" name="submit">
	</div>
	
  </form>
{/strip}
