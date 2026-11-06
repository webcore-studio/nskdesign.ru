{strip}
  {* Для всех да нет *}
  {array name='yes_no'}
  {array_append name='yes_no' key='1' value='да'}
  {array_append name='yes_no' key='0' value='нет'}
    <form method="POST" name="config_form" action="{mod_url modname='content' type='admin' func='config_update'}">
      <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
        <colgroup>
          <col width="35%">
          <col width="65%">
        </colgroup>

        <thead>
        <tr>
          <th colSpan="2">Настройки модуля контент</th>
        </tr>
        </thead>


        <tbody>
        <tr>
          <td class="head">Проверять короткие урлы</td>
          <td class="even">{html_radios name="url_control" options=$yes_no checked=$url_control separator=" "}</td>
        </tr>
		
		<tr>
          <td class="head">Список разрешенных расширений файлов<br><small>через пробел</small></td>
          <td class="even">
            <input type="text"  class="span7"  size="60" name="upload_avail" value="{$upload_avail}">
          </td>
        </tr>
		
		{array name='names'}
		{array_append name='names' key='1' value='транслит из неверных'}
		{array_append name='names' key='0' value='обнулять неверные'}
		
		 <tr>
          <td class="head">Обработка псевдонимов</td>
          <td class="even">{html_radios name="names_control" options=$names checked=$names_control separator=" "}</td>
        </tr>
		
		<tr>
          <td class="head">Автоматическая подгрузка содержимого следующих страниц</td>
          <td class="even">{html_radios name="ajax_paging" options=$yes_no checked=$ajax_paging separator=" "}</td>
        </tr>
		
</tbody>
</table>
<br>
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="35%">
        <col width="65%">
      </colgroup>

      <thead>
        <tr>
          <th colSpan="2">Отправка уведомлений администраторам</th>
        </tr>
      </thead>

       <tbody>

        <tr>
          <td class="head">Электронные адреса (через пробел)</td>
          <td class="even">
            <input type="text" class="span7" size="60" name="order_mail" value="{$order_mail}">
          </td>
        </tr>


      </tbody>

    </table>
	
	<br>
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="35%">
        <col width="65%">
      </colgroup>

      <thead>
        <tr>
          <th colSpan="2">Настройки RSS</th>
        </tr>
      </thead>

       <tbody>

        <tr>
          <td class="head">Количество документов в ленте</td>
          <td class="even">
            <input type="text" class="span1" size="3" name="rss_doc_count" value="{$rss_doc_count}">
          </td>
        </tr>

        <tr>
          <td class="head">Откуда выбирать документы</td>
          <td class="even">
		   {* Формируем плоский масив для вункции html_options *}
		{array name='flat_folderslist'}
		{foreach item=folder from=$folders_list}
			{array_append name='flat_folderslist' key=$folder.id value=$folder.displayname|escape|tree:$folder.level}
		{/foreach}
		
		 <select name=rss_folders_list[] multiple size=5>
      {html_options options=$flat_folderslist selected=$rss_folders_list}
      </select>
          </td>
        </tr>

       </tbody>

    </table>

    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="35%">
        <col width="65%">
      </colgroup>

      <thead>
        <tr>
          <th colSpan="2">Параметры хранения оригиналов фотографий</th>
        </tr>
      </thead>

      <tbody>

        <tr>
          <td class="head">Максимальная ширина</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="orig_image_width" value="{$orig_image_width}">
          </td>
        </tr>

        <tr>
          <td class="head">Максимальная высота</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="orig_image_height" value="{$orig_image_height}">
          </td>
        </tr>

        <tr>
          <td class="head">Качество jpg</td>
          <td class="even">
            <input type="text" class="span1" size="7" name="orig_image_qual" value="{$orig_image_qual}">
          </td>
        </tr>

        <tr>
          <td class="head">Менять качество jpg</td>
          <td class="even">
            {html_radios name="orig_image_changequal" options=$yes_no checked=$orig_image_changequal separator=" "}
          </td>
        </tr>

        <tr>
          <td class="head">Максимальный размер файла (Kb)</td>
          <td class="even">
            <input type="text"  class="span2" size="10" name="orig_image_maxsize" value="{$orig_image_maxsize}">
          </td>
        </tr>

      </tbody>

    </table>
	
<br>
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="35%">
        <col width="65%">
      </colgroup>

      <thead>
        <tr>
          <th colSpan="2">Параметры отображения полноразмерных фотографий</th>
        </tr>
      </thead>

      <tbody>

        <tr>
          <td class="head">Максимальная ширина</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="image_width" value="{$image_width}">
          </td>
        </tr>

        <tr>
          <td class="head">Максимальная высота</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="image_height" value="{$image_height}">
          </td>
        </tr>

        <tr>
          <td class="head">Качество jpg</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="image_qual" value="{$image_qual}">
          </td>
        </tr>

        <tr>
          <td class="head">Способ формирования</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="image_method" value="{$image_method}">
          </td>
        </tr>

      </tbody>

    </table>

<br>
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="35%">
        <col width="65%">
      </colgroup>

      <thead>
        <tr>
          <th colSpan="2">Параметры отображения фотографий</th>
        </tr>
      </thead>

      <tbody>

        <tr>
          <td class="head">Максимальная ширина</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="thumb_width" value="{$thumb_width}">
          </td>
        </tr>

        <tr>
          <td class="head">Максимальная высота</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="thumb_height" value="{$thumb_height}">
          </td>
        </tr>

        <tr>
          <td class="head">Качество jpg</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="thumb_qual" value="{$thumb_qual}">
          </td>
        </tr>

        <tr>
          <td class="head">Способ формирования</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="thumb_method" value="{$thumb_method}">
          </td>
        </tr>

      </tbody>

    </table>


<br>
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="35%">
        <col width="65%">
      </colgroup>

      <thead>
        <tr>
          <th colSpan="2">Параметры отображения логотипов вложенных документов и подразделов в разделе</th>
        </tr>
      </thead>

       <tbody>

        <tr>
          <td class="head">Максимальная ширина</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="logo_thumb_width" value="{$logo_thumb_width}">
          </td>
        </tr>

        <tr>
          <td class="head">Максимальная высота</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="logo_thumb_height" value="{$logo_thumb_height}">
          </td>
        </tr>

        <tr>
          <td class="head">Качество jpg</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="logo_thumb_qual" value="{$logo_thumb_qual}">
          </td>
        </tr>

        <tr>
          <td class="head">Способ формирования</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="logo_thumb_method" value="{$logo_thumb_method}">
          </td>
        </tr>



      </tbody>

    </table>

<br>
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="35%">
        <col width="65%">
      </colgroup>

      <thead>
        <tr>
          <th colSpan="2">Параметры отображения логотипа в документе и разделе </th>
        </tr>
      </thead>

       <tbody>

        <tr>
          <td class="head">Максимальная ширина</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="logo_width" value="{$logo_width}">
          </td>
        </tr>

        <tr>
          <td class="head">Максимальная высота</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="logo_height" value="{$logo_height}">
          </td>
        </tr>

        <tr>
          <td class="head">Качество jpg</td>
          <td class="even">
            <input type="text"  class="span1" size="7" name="logo_qual" value="{$logo_qual}">
          </td>
        </tr>

        <tr>
          <td class="head">Способ формирования</td>
          <td class="even">
            <input type="text" class="span1" size="7" name="logo_method" value="{$logo_method}">
          </td>
        </tr>



      </tbody>
	  
	  <tfoot>
        <tr>
          <td class="foot" colSpan="2" align="center">
            <input class="btn formButton" type="submit" value="Сохранить" name="button">
          </td>
        </tr>
      </tfoot>

    </table>



  </form>


    </form>
{/strip}