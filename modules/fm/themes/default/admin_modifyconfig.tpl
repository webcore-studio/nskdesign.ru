{strip}

{* Для всех да нет*}
{array name='yes_no'}
{array_append name='yes_no' key='1' value='Да'}
{array_append name='yes_no' key='0' value='Нет'}


      <form action="{mod_url type='admin' modname='fm' func='updateconfig'}" method="post">
        <input type="hidden" name="http_referer" value="{$http_referer}">
        <table cellSpacing="1" cellPadding="4" width="95%" align="center">

          <tr>
            <td>
              Максимальный размер файла
            </td>
            <td>
              <input type="text" maxLength="10" name="uploadMaxSize" size="10" class="span2" value="{$uploadMaxSize}">
              &nbsp;Кб
            </td>
          </tr>

          <tr>
            <td>
              Запрещенные файлы
            </td>
            <td>
              <textarea rows="5" name="uploadDeny" cols="30">{$uploadDeny|escape}</textarea>
            </td>
          </tr>
		  
		  <tr>
            <td>
              Разрешенные файлы
            </td>
            <td>
              <textarea rows="5" name="uploadAllow" cols="30">{$uploadAllow|escape}</textarea>
            </td>
          </tr>

          <tr>
            <td>
              Размер тумба
            </td>
            <td>
              <input type="text" maxLength="10" name="tmbSize" size="10" class="span1" VALUE="{$tmbSize}"> px
            </td>
          </tr>


          <tr>
            <td align="right" width="50%" nowrap>
              <input class="btn" type="submit" value="Сохранить">
            </td>
           </FORM>
			<td>
            <input class="btn" type="button" value="Отмена" onClick="window.location.href='{$http_referer}';">
		</td>
          </tr>

        </table>

{/strip}
