{strip}
<form class="form-horizontal"  name="banner_modify_form" action="{mod_url type='admin' modname='advert' func='banner_update'}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="place" value="{$place}">
	<input type="hidden" name="id" value="{$id}">
	
	<div class="control-group">
		<label class="control-label" for="banner_type">Тип баннера</label>
		<div class="controls">
			<select name="banner_type" onChange="">
				{if $banner_type=='image'}<option value="image" selected>{else}<option value="image">{/if}Рисунок</option>
				{if $banner_type=='flash'}<option value="flash" selected>{else}<option value="flash">{/if}Flash</option>
				{if $banner_type=='text'}<option value="text" selected>{else}<option value="text">{/if}Текст</option>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="banner_file">Файл баннера</label>
		<div class="controls">
			{if $banner_type=='image'}
				<img src="{$image}" style="max-width:200px; max-height:200px;">
			{elseif $banner_type=='flash'}
				<object id="banner{$id}">
					<param name=movie value="{$image}"><param name=quality value=high>
					<embed src="{$image}" quality=high   name="banner{$id}" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
					</embed>
				</object>
			{/if}
			<br>
			Изменить на: <input name="banner_file" id="banner_file" size="70" type="file">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="url">URL ссылки</label>
		<div class="controls">
			<input type="text" size="70" name="url"  value="{$url}" maxlength="100">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="alt">Название (Alt)</label>
		<div class="controls">
			<input type="text" size="70" name="alt" value="{$alt}" maxlength="100">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="text">Текст</label>
		<div class="controls">
			<textarea rows="3" cols="30" name="text">{$text|escape}</textarea>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="pattern">Маска</label>
		<div class="controls">
			<input type="text" size="70" name="pattern" value="{$pattern}" maxlength="100">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="active">Активировать</label>
		<div class="controls">
			{array name='yes_no'}
			{array_append name='yes_no' key='1' value='Да'}
			{array_append name='yes_no' key='0' value='Нет'}
			{html_radios name="active" options=$yes_no checked=$active separator=" "}
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="headcolor">Цвет заголовка</label>
		<div class="controls">
			<input type="text" size="70" name="additional[headcolor]" value="{$additional.headcolor}" maxlength="100" class="span2">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="headbackcolor">Цвет фона заголовка</label>
		<div class="controls">
			<input type="text" size="70" name="additional[headbackcolor]" value="{$additional.headbackcolor}" maxlength="100" class="span2">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="textcolor">Цвет текста</label>
		<div class="controls">
			<input type="text" size="70" name="additional[textcolor]" value="{$additional.textcolor}" maxlength="100" class="span2">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="textbackcolor">Цвет фона текста</label>
		<div class="controls">
			<input type="text" size="70" name="additional[textbackcolor]" value="{$additional.textbackcolor}" maxlength="100" class="span2">
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<input type="submit" value="Сохранить" class="btn">
		</div>
	</div>
</form>
{/strip}