<div class="block block_center">
	<ul class="mp">
		{foreach from=$file_list item=file}
			<li>
				<div>
					<img src="{$file.cat_logo}">
					<div class="cat_name">
					<a href="{$dir}/{$file.filename}"><span>{$file.cat_displayname} {$file.cat_description|strip_tags}</span></a>
					<a href="{$dir}/{$file.filename}">Скачать архив</a>
					</div>
					
				</div>
			</li>
		{/foreach}
	</ul>
</div>

