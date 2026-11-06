<script type="text/javascript" src="/scripts/tigra_calendar/calendar_db.js"></script>
<link rel="stylesheet" type="text/css" href="/scripts/tigra_calendar/calendar.css">

{strip}

  <form name="doc_modify_form" action="{mod_url type='admin' modname='content' func='doc_update'}" method="post">
  <input type="hidden" name="id" value="{$id}">
  <input type="hidden" name="fid" value="{$fid}">
  <input type="hidden" name="http_referer" value="{$http_referer}">
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">

        <tr>
          <th colSpan="2">{#editfaq#}</th>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">Вопрос</td>
          <td class="even"><input type="text" maxLength="255" size="60" name="displayname" value="{$displayname}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">Ответ</td>
          <td class="even">{texteditor name="description" text=$description}</td>
        </tr>
		
		 <tr vAlign="top" align="left">
          <td class="head">Отправить ответ автору на e-mail</td>
          <td class="even"><input type="checkbox" value="1" name="reply_to_author"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#author#}</td>
          <td class="even"><input type="text" maxLength="120" size="50" name="author" value="{$author}"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#author_email#}</td>
          <td class="even"><input type="text" maxLength="60" size="50" name="author_email" value="{$author_email}"></td>
        </tr>

        {array name='asc_yes_no'}
        {array_append name='asc_yes_no' key='1' value=#yes#}
        {array_append name='asc_yes_no' key='0' value=#no#}

        <tr vAlign="top" align="left">
          <td class="head">{#active#}</td>
          <td class="even">
            {html_radios name="active" options=$asc_yes_no checked=$active separator=" "}
          </td>
        </tr>

       <tr vAlign="top" align="left">
          <td class="head">{#pub_datetime#}</td><td>
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
        {*  {#time#}: {html_select_time time=$pub_datetime display_seconds=false prefix='pub_time_'}*}
          </td>
        </tr>


        <tr vAlign="top" align="center">
          <td class="even" colspan="2"><input class="formButton" type="submit" value="{#edit#}" name="submit"></td>
        </tr>

    </table>
  </form>

{/strip}