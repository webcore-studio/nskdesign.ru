{strip}
  <form action="{mod_url modname='SYS_config' type='admin' func='metaconfig_update'}" method="post">

    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="35%">
        <col width="65%">
      </colgroup>

      <thead>
        <tr>
          <th colSpan="2">МЕТА-тэги и нижний колонтитул</th>
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
          <td class="head">МЕТА-тэг ключевых слов</td>
          <td class="even">
            <textarea name="meta_keywords" rows="5" cols="50">{$meta_keywords|escape}</textarea>
          </td>
        </tr>

        <tr>
          <td class="head">МЕТА-тэг описания</td>
          <td class="even">
            <textarea name="meta_description" rows="5" cols="50">{$meta_description|escape}</textarea>
          </td>
        </tr>

        <tr>
          <td class="head">МЕТА-тэг роботов</td>
          <td class="even">
            <select size="1" name="meta_robots">
              {html_options options=$meta_robots_list selected=$meta_robots}
            </select>
          </td>
        </tr>

        <tr>
          <td class="head">МЕТА-тэг Рейтинга</td>
          <td class="even">
            <select size="1" name="meta_rating">
              {html_options options=$meta_rating_list selected=$meta_rating}
            </select>
          </td>
        </tr>

        <tr>
          <td class="head">МЕТА-тэг Автора</td>
          <td class="even">
            <input type="text" maxLength="255" size="50" value="{$meta_author|escape}" name="meta_author">
          </td>
        </tr>

        <tr>
          <td class="head">МЕТА-тэг авторского права</td>
          <td class="even">
            <input  type="text"  maxLength="255" size="50" value="{$meta_copyright|escape}" name="meta_copyright">
          </td>
        </tr>


        <tr>
          <td class="head">Нижний колонтитул</td>
          <td class="even">
            <textarea name="footer" rows="5" cols="50">{$footer}</textarea>
          </td>
        </tr>
      </tbody>

    </table>
  </form>
{/strip}