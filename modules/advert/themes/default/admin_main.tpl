{strip}
{if $places_list}
	{foreach item=place from=$places_list}
		<div class="item">
			Место № {$place.id} (блок №{$place.block_id})<br>
			<b>"{$place.name}"</b>, {$place.width}x{$place.height} px
			&nbsp;&nbsp;&nbsp;
			{array name='url'}
			{array_append name='url' key='id' value=$place.id}
			<a href="{mod_url type='admin' modname='advert' func='place_modify' vars=$url}" title="Редактировать"><i class="icon-edit"></i></a>
			<a href="{mod_url type='admin' modname='advert' func='banner_new' vars=$url}" title="Добавить баннер"><i class="icon-plus"></i></a>

			{if $place.banners}
				<form action="{mod_url type='admin' modname='advert' func='item_weight'}" method="post">
				<input type="hidden" value="{$place.id}" name="place_id">
				<table class="table">
					<thead>
						<tr>
							<th>Содержимое</th>
							<th>URL</th>
							<th>ALT</th>
							<th>Актив</th>
							<th>Маска</th>
							<th>Сортировка</th>
							<th>Действия</th>
						</tr>
					</thead>
					{foreach from=$place.banners item=b}
						<tr>
							<td>
								<a href="{$b.image}" target="_blank"><img src="{$b.image}" style="max-width:150px; max-height:150px;"></a>
								<br>{$b.text}
							</td>
							<td>{$b.url}</td>
							<td>{$b.alt}</td>
							<td>{if ($b.active)}да {else}нет{/if}</td>
							<td>{$b.pattern}</td>
							<td>
								<input type="hidden" name="ids[]" value="{$b.id}">
								<input name="weights[]" class="span1" value="{$b.weight}" type="text">&nbsp;
							</td>
							<td>
								{array name='url_vars'}
								{array_append name='url_vars' key='place' value=$place.id}
								{array_append name='url_vars' key='id' value=$b.id}
								<a href="{mod_url type='admin' modname='advert' func='banner_modify' vars=$url_vars}" title="Редактировать"><i class="icon-edit"></i></a>&nbsp;
								<a href="{mod_url type='admin' modname='advert' func='banner_delete' vars=$url_vars}" title="Удалить"><i class="icon-remove"></i></a>
							</td>
						</tr>
					{/foreach}
					<tr>
						<td colspan=7><div class="pull-right"><input class="btn" type="submit" value="Сохранить сортировку"></div></td>
				</table>
				
				</form>
			{/if}
		</div>
	{/foreach}


{else}
	<div class="alert alert-error">Рекламные места не созданы</div>
{/if}

<div class="item well">
	<h3>Новое рекламное место</h3>
	<form class="form-horizontal" name="place_add" action="{mod_url type='admin' modname='advert' func='place_add'}" method="post">
		<div class="control-group">
			<label class="control-label" for="name">Название</label>
			<div class="controls">
				<input type="text" size="50" maxlength="50" name="name">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="width">Размеры</label>
			<div class="controls">
				<input type="text" size="5" name="width" class="span1"> x <input type="text" size="5"  name="height"  class="span1"> px
			</div>
		</div>
		
		<div class="control-group">
			<div class="controls">
				<input type="submit" value="Создать" class="btn">
			</div>
		</div>
	</form>
</div>



{/strip}