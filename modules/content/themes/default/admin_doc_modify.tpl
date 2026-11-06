<script type="text/javascript" src="/scripts/java_scripts/jquery.liTranslit.js"></script>
<script type="text/javascript" src="/modules/fm/scripts/script.js"></script>


 <form name="doc_modify_form" action="{mod_url type='admin' modname='content' func='doc_update'}" method="post"  enctype="multipart/form-data">
  <input type="hidden" name="id" value="{$id}">
  <input type="hidden" name="fid" value="{$fid}">
  <input type="hidden" name="http_referer" value="{$http_referer}">

    <table class="outer" cellSpacing="1" cellPadding="4">

        <tr>
          <th>{#editingdoc#}</th>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#docdisplayname#}<br><input type="text" class="span7" maxLength="255" size="60" name="displayname" value="{$displayname|escape}"></td>
        </tr>
       <tr vAlign="top" align="left">
          <td class="head">{#pseudonym#}<br><input type="text"  class="span7" maxLength="128" size="50" name="name" value="{$name}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#description#}<br><textarea name="description" rows="5" class="span7">{$description|escape}</textarea></td>
        </tr>



        <tr vAlign="top" align="left">
          <td class="head">{#content#}<br>
		{if $editor != 1}
            {texteditor name="content" text=$content editor=$editor}
		{else}
			<textarea name="content" rows="22" cols="60">{$content}</textarea>
		{/if}
		<br> 
		{if $images_list}<i>Для вывода в тело документа галереи прикрепленных изображений, вставьте в выбранное место тег {literal}<b>{images}</b>{/literal}</i>{/if}
          </td>
        </tr>

       <tr vAlign="top" align="left">
			<td class="head">{#editor#}<br>
				<select name="editor" onChange="window.location='/index.php?module=content&type=admin&func=doc_modify&id={$id}&fid={$fid}&editor='+this.value">
					<option value="3" {if $editor==3}selected{/if}>Jodit (Рекомендуемый)</option>
					<option value="5" {if $editor==5}selected{/if}>CKEditor 5</option>
					<option value="2" {if $editor==2 || $editor==4 || $editor==0 || !$editor}selected{/if}>TinyMCE</option>
					<option value="1" {if $editor==1}selected{/if}>нет</option>
				</select>
			</td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#logo#}<br>          
            {* Формируем плоский масив для вункции html_options *}
            {array name='flat_imageslist'}
            {array_append name='flat_imageslist' key='-1' value=#no#}
            {foreach item=image from=$images_list}
              {if $image.image_displayname}{array_append name='flat_imageslist' key=$image.id value=$image.image_displayname}
		{else}{array_append name='flat_imageslist' key=$image.id value='&nbsp;-&nbsp;'}{/if}
            {/foreach}
            <select name="logo_id" class="span7">
              {html_options options=$flat_imageslist selected=$logo_id}

            </select>
  
          </td>
        </tr>


        {array name='yes_no'}
        {array_append name='yes_no' key='1' value=#yes#}
        {array_append name='yes_no' key='0' value=#no#}

        {array name='asc_yes_no'}
        {array_append name='asc_yes_no' key='1' value=#asc#}
        {array_append name='asc_yes_no' key='0' value=#desc#}


        <tr vAlign="top" align="left">
          <td class="head">{#author#}<br><input type="text" maxLength="120" class="span7" name="author" value="{$author}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#author_email#}<br><input type="text" maxLength="60" class="span7" name="author_email" value="{$author_email}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#author_url#}<br><input type="text" maxLength="255" class="span7" name="url" value="{$url}"></td>
        </tr>

        {array name='asc_yes_no'}
        {array_append name='asc_yes_no' key='1' value='Да'}
        {array_append name='asc_yes_no' key='0' value='Нет'}

        <tr vAlign="top" align="left">
          <td class="head">{#active#}<br>
            {html_radios name="active" options=$asc_yes_no checked=$active separator=" "}
          </td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#priority#}<br>
			<input type="hidden" name="misc_varchar_a" value="0">
            <input type="checkbox" name="misc_varchar_a" {if $misc_varchar_a}checked{/if} value="1">
         </td>
        </tr>
		
		<tr vAlign="top" align="left">
          <td class="head">Шаблон<br><input type="text" class="span7" maxLength="128" size="60" name="tpl" value="{$tpl}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#pub_datetime#}<br>
			{html_input_date class="span2" name="pub_date" date=$pub_datetime}&nbsp;
			<input type="text" class="span1" name="pub_time" value="{$pub_datetime|date_format:'%H:%M'}">
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

<br>

{* фотки *}

<div class="images_list">
<h2>Фотографии</h2>
	<table class="table object_list">
    {foreach item=image from=$images_list}
        {* Формируем масив переменные для урла *}
        {array name='url_vars'}
        {array_append name='url_vars' key='id' value=$image.id}
		<form name="image_update_form_{$image.id}" action="{mod_url modname='content' type='admin' func='image_update'}" method="post" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{$image.id}">
			<input type="hidden" name="doc_id" value="{$id}">
			<tr>
				<td>
					<input type="text" class="span1" name="image_weight" value="{$image.weight}">
				</td>
				<td>
					<a href="{$image.big.thumb_url}" rel="lightbox"><img src="{$image.thumb_url}" alt="{$image.image_displayname|escape}"></a>
				</td>
				
				<td>
					<label>Название:</label> <input type="text" class="span5"  name="image_displayname" value="{$image.image_displayname|escape}">
					<br>
					<label>Описание:</label>  <textarea class="span5" name="image_description">{$image.image_description|escape}</textarea>
				</td>
				
				<td style="width:150px">
					<input class="btn" type="submit" value="Сохранить">
					<a class="btn" type="button" href="{mod_url type='admin' modname='content' func='image_delete' vars=$url_vars}" onclick="return confirm ('Удалить изображение?')">Удалить</button>
				</td>
			</tr>
		</form>
		{foreachelse}
			<div class="alert alert-danger">Нет фотографий</div>
		{/foreach}
</table>
</div>

 <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
        <tr>
          <th colSpan="2">Добавление фото</th>
        </tr>
		
		<tr>
			<td colspan=2>
<form action="">
	<input type="hidden" id="modname" value="content">
		<input type="hidden" id="item_id" value="{$id}">
      <div id="dropZone" class="item">
        Для загрузки перетащите файл сюда.
      </div>
	  <div id="upload_result"></div>
</form> 
<div class="demo1_translit" style="display:none"></div>
<div class="demo2_translit" style="display:none"></div>

<form name="image_new_form" action="{mod_url modname='content' type='admin' func='image_create'}" method="post" enctype="multipart/form-data">
  <input type="hidden" name="doc_id" value="{$id}">
  {array name='image_url_vars'}
  {array_append name='image_url_vars' key='id' value=$id}
  {array_append name='image_url_vars' key='fid' value=$fid}
  <input type="hidden" name="http_referer" value="{mod_url modname='content' type='admin' func='doc_modify' vars=$image_url_vars}">
   </td></tr>

        <tr>
          <td class="head">Название</td>
          <td class="even"><input type="text" class="span6" maxLength="255" size="50" name="image_displayname" value="{$image_displayname}"></td>
        </tr>

        <tr>
          <td class="head">Описание</td>
          <td class="even"><textarea name="image_description" rows="5" cols="50" class="span6">{$image_description}</textarea></td>
        </tr>

        <tr>
          <td class="head">Файл</td>
          <td class="even"><input name="image_file" size="60" type="file"></td>
        </tr>

        <tr>
          <td class="foot" colspan="2" align="center">
            <input type="submit" value="Добавить" name="submit" class="btn">
          </td>
        </tr>

    </table>
	<i>Максимальный размер изображения для загрузки: {mod_getvar modname="content" name="orig_image_width"} x {mod_getvar modname="content" name="orig_image_height"} px, {mod_getvar modname="content" name="orig_image_maxsize"} kb</i>
  </form>

