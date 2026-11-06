{strip}
{array name='yes_no'}
{array_append name='yes_no' key='1' value=#yes#}
{array_append name='yes_no' key='0' value=#no#}

<form action="{mod_url modname='SYS_config' type='admin' func='imagesconfig_update'}" method="post" name="imagesconfig_form" >

    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
		<colgroup>
			<col width="35%">
			<col width="65%">
		</colgroup>

		<thead>
			<tr>
			<th colSpan="2">Настройки изображений</th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<td colspan="2" align="center" class="foot">
					<input class="btn formButton" type="submit" value="Сохранить" name="button">
				</td>
			</tr>
		</tfoot>

		<tbody>
		
			<tr>
				<td class="head">Брать картинки с сайта</td>
				<td class="even">
					<input type="text" maxLength="100" size="50" name="images_from" value="{$images_from}" class="span7">
				</td>
			</tr>
			
			<tr>
				<td class="head">Водяной знак</td>
				<td class="even">{html_radios name="watermark" options=$yes_no checked=$watermark separator=" "}</td>
			</tr>

			<tr>
				<td class="head">Путь к водяному знаку</td>
				<td class="even">
					<input type="text" maxLength="100" size="50" name="watermark_file" value="{$watermark_file}"  class="span7">&nbsp;
					{imagebrowse action="document.imagesconfig_form.watermark_file.value"}
				</td>
			</tr>
			
			<tr>
				<td class="head">Положение водяного знака</td>
				<td class="even">
					По горизонтали:&nbsp;
					<select name="watermark_pos_x" class="span3">
						<option value="l" {if $watermark_pos_x=="l"}selected{/if}>Слева</option>
						<option value="c" {if $watermark_pos_x=="c"}selected{/if}>По центру</option>
						<option value="r" {if $watermark_pos_x=="r"}selected{/if}>Справа</option>
					</select>
					<br>
					По вертикали:&nbsp;
					<select name="watermark_pos_y"  class="span3">
						<option value="t" {if $watermark_pos_y=="t"}selected{/if}>Сверху</option>
						<option value="m" {if $watermark_pos_y=="m"}selected{/if}>По центру</option>
						<option value="b" {if $watermark_pos_y=="b"}selected{/if}>Снизу</option>
					</select>					
			</tr>
			
			<tr>
				<td class="head">Водяной знак по ширине от картинки</td>
				<td class="even">
					<input class="span1" type="text" maxLength="100" size="50" name="watermark_percent" value="{$watermark_percent}">&nbsp;%
				</td>
			</tr>
		
			
			
		
			<tr>
				<td class="head">Перезаписывать файлы thumb каждый раз</td>
				<td class="even">{html_radios name="thumb_replace" options=$yes_no checked=$thumb_replace separator=" "}</td>
			</tr>
			
			<tr>
				<td class="head">Ставить водяной знак для модулей</td>
				<td class="even">	
					<label><input type="checkbox" name="module_watermark[content]" value="content" {if $module_watermark.content}checked{/if}>&nbsp;content</label><br>
					<label><input type="checkbox" name="module_watermark[shop]" value="shop" {if $module_watermark.shop}checked{/if}>&nbsp;shop</label><br>
				</td>
			</tr>
			
			<tr>
				<td class="head">Не ставить водяной знак для фото меньше:</td>
				<td class="even">	
					<input type="text" value="{$min_watermark_width}" name="min_watermark_width"  class="span1"> x <input type="text" value="{$min_watermark_height}" name="min_watermark_height"  class="span1">
				</td>
			</tr>
		
			<tr>
				<td class="head">Очистить thumbs</td>
				<td class="even">	
					<label><input type="checkbox" name="clear_content_thumbs" value="1">&nbsp;модуля content</label><br>
					<label><input type="checkbox" name="clear_shop_thumbs" value="1">&nbsp;модуля shop</label><br>
					<label><input type="checkbox" name="clear_watermark_thumbs" value="1">&nbsp;водяного знака</label><br>
			
				</td>
			</tr>


		</tbody>

    </table>
</form>
{/strip}