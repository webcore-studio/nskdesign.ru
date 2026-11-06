<form method=POST action="{mod_url type='ajax' modname='fields' func='field_create'}" method="post">
<h2>Создание нового поля</h2>
<br>
<p>Название: <input type="text" name="fld_displayname" value="" id="fld_displayname">
<p>Тип: 
<select name="fld_type" id="fld_type">
<option value="text" selected>text</option>
<option value="radiobutton">radiobutton</option>
<option value="checkbox">checkbox</option>
<option value="select">select</option>
<option value="multiselect">multiselect</option>
<option value="file">file</option>
</select>
<p>Наследование: 
<select name="fld_inh" id="fld_inh">
<option value="2" selected>Элемент и его потомки</option>
<option value="0">Только элемент</option>
<option value="1">Только потомки</option>
<p><input type="submit" name="submit" value="Добавить поле" id="submit">
</form>