{strip}
<form class="form-horizontal" name="banner_new_form" action="{mod_url type='admin' modname='advert' func='banner_add'}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="place" value="{$place}">
	
	<div class="control-group">
		<label class="control-label" for="banner_type">Тип баннера</label>
		<div class="controls">
			<select name="banner_type">
				<option value="imagetext" selected>Текст с заголовком + фон</option>
				<option value="image" selected>Рисунок</option>
				<option value="flash" >Flash</option>
				<option value="text">Текст</option>
			</select>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="banner_file">Файл баннера</label>
		<div class="controls">
			<input name="banner_file" id="banner_file" size="70" type="file" style="visibility:visible;">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="url">URL ссылки</label>
		<div class="controls">
			<input type="text" size="70" name="url" value="" maxlength="100">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="alt">Название (Alt)</label>
		<div class="controls">
			<input type="text" size="70" name="alt" value="" maxlength="100">
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
			<input type="text" size="70" name="pattern" value="MainPage" maxlength="100">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="pattern">Активировать</label>
		<div class="controls">
			{array name='yes_no'}
			{array_append name='yes_no' key='1' value='Да'}
			{array_append name='yes_no' key='0' value='Нет'}
			{html_radios name="active" options=$yes_no checked=1 separator=" "}
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<input type="submit" value="Добавить" class="btn">
		</div>
	</div>
	
</form>
{/strip}