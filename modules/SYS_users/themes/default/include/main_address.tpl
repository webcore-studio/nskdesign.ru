	<div class="control-group">
		<div class="controls"><h3>Адрес доставки</h3></div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="index">Индекс</label>
		<div class="controls"><input  type="text" maxLength="8" size="30" name="deliver_address[index]" value="{$deliver_address.index}" class="span2"></div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="region">Регион&nbsp;<b>*</b></label>
		<div class="controls"><input type="text" maxLength="25" size="30" name="deliver_address[region]" value="{if $deliver_address.region}{$deliver_address.region}{else}{sess_getvar name='region'}{/if}" class="span5 required"></div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="district">Район</label>
		<div class="controls"><input type="text" maxLength="25" size="30" name="deliver_address[district]" value="{$deliver_address.district}" class="span5"></div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="city">Город&nbsp;<b>*</b></label>
		<div class="controls"><input type="text" maxLength="25" size="30" name="deliver_address[city]" value="{if $deliver_address.city}{$deliver_address.city}{else}{sess_getvar name='city'}{/if}" class="span5  required"></div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="street">Улица&nbsp;<b>*</b></label>
		<div class="controls"><input type="text" maxLength="50" size="30" name="deliver_address[street]" value="{$deliver_address.street}" class="span5  required"></div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="house">Дом, корпус&nbsp;<b>*</b></label>
		<div class="controls"><input type="text" maxLength="25" size="30" name="deliver_address[house]" value="{$deliver_address.house}" class="span2 required"></div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="office">Квартира, офис</label>
		<div class="controls"><input type="text" maxLength="25" size="30" name="deliver_address[office]" value="{$deliver_address.office}" class="span2"></div>
	</div>
	
