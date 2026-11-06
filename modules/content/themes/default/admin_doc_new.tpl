  <form name="doc_new_form" action="{mod_url type='admin' modname='content' func='doc_create'}" method="post" enctype="multipart/form-data">
  <input type="hidden" name="fid" value="{$fid}">
    <table class="outer" cellSpacing="1" cellPadding="4">

        <tr>
          <th>{#addingdoc#}</th>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#folder#}<br>
            {* Формируем плоский масив для вункции html_options *}
            {array name='flat_folderslist'}

            {foreach item=folder from=$folders_list}
            {array_append name='flat_folderslist' key=$folder.id value=$folder.displayname|tree:$folder.level}
            {/foreach}

            <select name="fids[]" size="10" multiple="multiple" class="span7">
            {html_options options=$flat_folderslist selected=$fid}
            </select>

            </td>
        </tr>


        <tr vAlign="top" align="left">
          <td class="head">{#docdisplayname#}<br><input type="text" class="span7" maxLength="255" size="60" name="displayname" value="{$displayname}"></td>
        </tr>
       <tr vAlign="top" align="left">
          <td class="head">{#pseudonym#}<br><input type="text" class="span7" maxLength="128" size="50" name="name" value="{$name}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#description#}<br><textarea name="description" rows="5" class="span7" >{$description}</textarea></td>
        </tr>

        <tr vAlign="top" align="left">
			<td class="head">
				{#content#}<br>
				{texteditor name="content" text=$content}

			</td>
        </tr>
		

        {array name='yes_no'}
        {array_append name='yes_no' key='1' value=#yes#}
        {array_append name='yes_no' key='0' value=#no#}

        {array name='asc_yes_no'}
        {array_append name='asc_yes_no' key='1' value=#asc#}
        {array_append name='asc_yes_no' key='0' value=#desc#}


        {usergetvars assign=uservars}
 

        <tr vAlign="top" align="left">
          <td class="head">{#author#}<br><input type="text" class="span7" maxLength="120" size="50" name="author" value=""></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#author_email#}<br><input type="text" class="span7" maxLength="60" size="50" name="author_email" value=""></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#author_url#}<br><input type="text" class="span7" maxLength="255" size="50" name="url" value=""></td>
        </tr>

        {array name='asc_yes_no'}
        {array_append name='asc_yes_no' key='1' value='Да'}
        {array_append name='asc_yes_no' key='0' value='Нет'}

        <tr vAlign="top" align="left">
          <td class="head">{#active#}<br>
            {html_radios name="active" options=$asc_yes_no checked=1 separator=" "}
          </td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#priority#}<br>
            <input type="checkbox" name="misc_varchar_a">
          </td>
        </tr>
		
		<tr vAlign="top" align="left">
          <td class="head">Шаблон<br><input type="text" class="span7" maxLength="128" size="60" name="tpl" value="{$tpl}"></td>
        </tr>
		
				{if $folder_dbdata.need_captcha &&  $curuser_sec_level<70}
		<tr>
        <td class="head">Введите код подтверждения<br>
		<script language="JavaScript" type="text/javascript">
						{literal}
            function content_captcha_update()
              {
              var rndval = new Date().getTime();
              document.getElementById('content_captcha_div').innerHTML = '<img src="index.php?module=content&type=user&func=captcha_view&rndval=' + rndval + '" alt="Код безопасности" border="0" width="134" height="39">';
              return false;
              };
            {/literal}
          </script>
        	<div style="text-indent: 0px;" id="content_captcha_div">
         	<img src="{mod_url modname='content' type='user' func='captcha_view'}" alt="Код безопасности" border="0" width="134" height="39">
         </div>
         <a href="#" onclick="content_captcha_update(); return false;">Обновить код</a>
		 <br>
        	<input type="text" class="span2" size="16" name="captcha_string" value="">
        
       	
        </td>
      </tr>
		{/if}

        <tr vAlign="top" align="left">
          <td class="head">{#pub_datetime#}<br>
			{html_input_date class="span2" name="pub_date"}&nbsp;
			<input type="text" class="span1" name="pub_time" value="{$smarty.now|date_format:'%H:%M'}">
          </td>
        </tr>
	</table>
		
	<div class="item">
		{include file='modules/SYS_config/themes/default/include/meta_info.tpl'}
	</div>
	
	<div>
		<input class="btn formButton" type="submit" value="{#save#}" name="submit">
	</div>
	
  </form>
