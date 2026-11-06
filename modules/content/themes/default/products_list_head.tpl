<table class="product-table">
	{if $settings.General.inventory_tracking == "Y"}
		<colgroup>
			<col width="180">
			<col width="2">
			<col width="80">
			<col width="2">
			<col width="65">
			<col width="2">
			<col width="65">
			<col width="2">
			<col width="130">
			<col width="2">
			<col width="150">
			<col width="2">
			<col width="132">
		</colgroup>
		<tr>
			<th class="bd-color01" rowspan="2"><span>Тип</span></th>
			<th class="table-spacer" rowspan="2"></th>
			<th class="bd-color02" rowspan="2"><span>В упаковке</span></th>
			<th class="table-spacer" rowspan="2"></th>
			<th class="bd-color03 bd0" colspan="3"><span>Остаток на складе</span></th>
			<th class="table-spacer" rowspan="2"></th>

			<th class="bd-color04" rowspan="2"><span>Цена с НДС, руб.<br><span style="font-weight:normal; font-size:90%">при заказе до 50 тыс.<br>со скидкой 5%<br>Шт / Упак</span></span></th>
			<th class="table-spacer" rowspan="2"></th>
			<th class="bd-color04" rowspan="2"><span>Крупнооптовая цена, руб.<br><span style="font-weight:normal; font-size:90%">при заказе свыше 50 тыс.<br>Шт / Упак</span></span></th>
			<th class="table-spacer" rowspan="2"></th>
			<th class="bd-color05" style="width:131px" rowspan="2"><span>Заказать</span></th>
		</tr>
		<tr>
			<th class="bd-color03"><span style="font-weight:normal; font-size:90%">Общий</span></th>
			<th class="table-spacer"></th>
			<th class="bd-color03"><span style="font-weight:normal;font-size:90%">{$storehouse_name}</span></th>
		</tr>
	{else}
		<colgroup>
			<col width="180">
			<col width="2">
			<col width="80">
			<col width="2">
			<col width="130">
			<col width="2">
			<col width="150">
			<col width="2">
			<col width="131">
		</colgroup>
		<tr>
			<th class="bd-color01"><span>Тип</span></th>
			<th class="table-spacer"></th>
			<th class="bd-color02"><span>В упаковке</span></th>
			<th class="table-spacer"></th>
			<th class="bd-color04"><span>Цена с НДС, руб.<br><span style="font-weight:normal; font-size:90%">при заказе до 50 тыс.<br>со скидкой 5%<br>Шт / Упак</span></span></th>
			<th class="table-spacer"></th>
			<th class="bd-color04"><span>Крупнооптовая цена, руб.<br><span style="font-weight:normal; font-size:90%">при заказе свыше 50 тыс.<br>Шт / Упак</span></span></th>
			<th class="table-spacer"></th>
			<th class="bd-color05" style="width:131px" ><span>Заказать</span></th>
		</tr>

	{/if}