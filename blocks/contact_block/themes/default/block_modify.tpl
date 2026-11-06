{array name='yes_no'}
{array_append name='yes_no' key='1' value='Да'}
{array_append name='yes_no' key='0' value='Нет'}
    
	<tr vAlign="top" align="left">
        <td class="head">Показывать получателя</td>
        <td class="even">
            {html_radios name="show_to" options=$yes_no checked=$show_to separator="&nbsp;"}
		</td>
    </tr>
	
	<tr vAlign="top" align="left">
        <td class="head">Показывать тему сообщения</td>
        <td class="even">
            {html_radios name="show_theme" options=$yes_no checked=$show_theme separator="&nbsp;"}
		</td>
    </tr>
	