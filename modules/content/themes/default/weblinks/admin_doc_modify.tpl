 <form name="doc_modify_form" action="{mod_url type='admin' modname='content' func='doc_update'}" method="post"  enctype="multipart/form-data">
  <input type="hidden" name="id" value="{$id}">
  <input type="hidden" name="fid" value="{$fid}">
  <input type="hidden" name="http_referer" value="{$http_referer}">

    <table class="outer" cellSpacing="1" cellPadding="4">

        <tr>
          <th>{#editweblink#}</th>
        </tr>


        <tr vAlign="top" align="left">
          <td class="head">{#title#}<br><input maxLength="255" size="60" name="displayname" value="{$displayname|escape}"></td>
        </tr>


        <tr vAlign="top" align="left">
          <td class="head">{#description#}<br><textarea name="description" rows="5" cols="60">{$description|escape}</textarea></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">Адрес сайта<br><input maxLength="255" size="50" name="url" value="{$url}"></td>
        </tr>


        <tr vAlign="top" align="left">
          <td class="head">{#logo#}<br>
            <input maxLength="255" size="50" name="logo" value="{$logo}">&nbsp;
            {imagebrowse action="document.doc_modify_form.logo.value"}
          </td>
        </tr>


        <tr vAlign="top" align="left">
          <td class="head">{#author#}<br><input maxLength="60" size="50" name="author" value="{$author}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#author_email#}<br><input maxLength="60" size="50" name="author_email" value="{$author_email}"></td>
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
          <td class="head">{#pub_datetime#}<br>
     {*     {html_select_date time=$pub_datetime start_year="-1" end_year="+1" field_order=DMY prefix='pub_date_'}&nbsp;*}

	<input type="text" name="pub_date" id="pub_date" value={$pub_datetime|date_format:"%Y-%m-%d"} >
{literal}
	<script language="JavaScript">

	var A_CALTPL = {
		'months' : ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
		'weekdays' : ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб'],
		'yearscroll': true,
		'weekstart': 1,
		'centyear'  : 70,
		'imgpath' : '/scripts/tigra_calendar/img/'
	}
	
	new tcal ({
		// if referenced by ID then form name is not required
		'controlname': 'pub_date'
	}, A_CALTPL);

	
	</script>

{/literal}
          {#time#}: {html_select_time time=$pub_datetime display_seconds=false prefix='pub_time_'}
          </td>
        </tr>


        <tr vAlign="top" align="center">
          <td class="foot">
            <input type="submit" value="{#edit#}" name="submit">
          </td>
        </tr>

    </table>
  </form>





