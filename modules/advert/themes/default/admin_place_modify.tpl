{strip}
<form class="form-horizontal" name="place_modify_form" action="{mod_url type='admin' modname='advert' func='place_update'}" method="post">
	<input type="hidden" name="id" value="{$id}">

	<div class="control-group">
		<div class="controls">
			<b>Место № {$id}</b>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="name">Название</label>
		<div class="controls">
			<input type="text" size="70" name="name" value="{$name|escape}" maxlength="100">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="width">Размеры</label>
		<div class="controls">
			<input type="text" size="5" value="{$width}" name="width" class="span1"> x <input type="text" size="5"  name="height" value="{$height}"   class="span1"> px
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<input type="submit" class="btn" value="Сохранить">
		</div>
	</div>
</form>
{/strip}