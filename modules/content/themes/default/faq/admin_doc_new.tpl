
<script type="text/javascript" src="/scripts/tigra_calendar/calendar_db.js"></script>
<link rel="stylesheet" type="text/css" href="/scripts/tigra_calendar/calendar.css">

<script type="text/javascript" src="/scripts/vanadium/vanadium.js"></script>
<link rel="StyleSheet" href="/scripts/vanadium/vanadium.css" type="text/css">

{if $error_msg}
<div class="error" style="color:#ff0000; font-weight:bold; margin:10px 0">{$error_msg}</div>
{/if}

  <form name="doc_new_form" action="{mod_url type='admin' modname='content' func='doc_create'}" method="post">
  <input type="hidden" name="fid" value="{$fid}">
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">

        <tr>
          <th colSpan="2">Задать вопрос</th>
        </tr>


        <tr vAlign="top" align="left">
          <td class="head">Вопрос *</td>
         <td class="even"><textarea class="span7" rows=4 name="displayname" class=":required :only_on_submit" maxLength="255">{$displayname}</textarea>
			<br><small>Максимальная длина вопроса - 255 символов</small></td>
        </tr>
{if $curuser_sec_level>=70} 
        <tr vAlign="top" align="left">
          <td class="head">Ответ</td>
          <td class="even">{texteditor name="description" text=$description}</td>
        </tr>
{/if}

        {usergetvars assign=uservars}

        <tr vAlign="top" align="left">
          <td class="head">{#author#}</td>
          <td class="even"><input type="text" maxLength="120" size="50" name="author" value="{if $author}{$author}{else}{$uservars.user_displayname}{/if}" class=":required :only_on_submit"></td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#author_email#} *</td>
          <td class="even"><input type="text" maxLength="60" size="50" name="author_email" class=":email :required :only_on_submit" value="{if $author_email}{$author_email}{else}{$uservars.user_email}{/if}"></td>
        </tr>
		
		{if $folder_dbdata.need_captcha &&  $curuser_sec_level<70}
		<tr>
        <td class="head">Введите код подтверждения</td><td>
		<script language="JavaScript" type="text/javascript">
						{literal}
            function content_captcha_update()
              {
              var rndval = new Date().getTime();
              document.getElementById('content_captcha_div').innerHTML = '<img src="index.php?module=content&type=user&func=captcha_view&rndval=' + rndval + '" alt="Код безопасности" border="0" width="134" height="39">';
              return false;
              };
            {/literal}
          </script>
        	<div style="text-indent: 0px;" id="content_captcha_div">
         	<img src="{mod_url modname='content' type='user' func='captcha_view'}" alt="Код безопасности" border="0" width="134" height="39">
         </div>
         <a href="#" onclick="content_captcha_update(); return false;">Обновить код</a>
		 <br>
        	<input size="16" name="captcha_string" value="" class=":required :only_on_submit">
        
       	
        </td>
      </tr>
		{/if}


        {array name='asc_yes_no'}
        {array_append name='asc_yes_no' key='1' value=#yes#}
        {array_append name='asc_yes_no' key='0' value=#no#}

{if $curuser_sec_level>=70} 
       <tr vAlign="top" align="left">
          <td class="head">{#active#}</td>
          <td class="even">
            {html_radios name="active" options=$asc_yes_no checked=1 separator=" "}
          </td>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">{#pub_datetime#}</td>
          <td class="even">
     {*     {html_select_date time=$pub_datetime start_year="-1" end_year="+1" field_order=DMY prefix='pub_date_'}&nbsp;
          {#time#}:&nbsp;{html_select_time time=$pub_datetime display_seconds=false prefix='pub_time_'}*}
	<input type="text" name="pub_date" id="pub_date" value={$smarty.now|date_format:"%Y-%m-%d"}>
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
          </td>
        </tr>

{/if}
        <tr vAlign="top" align="center">
          <td class="even" colspan="2"><input class="btn submit" type="submit" value="Добавить" name="submit"></td>
        </tr>

    </table>
  </form>

