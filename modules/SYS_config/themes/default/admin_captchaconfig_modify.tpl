{array name='yes_no'}
{array_append name='yes_no' key='1' value='Да'}
{array_append name='yes_no' key='0' value='Нет'}

{* Разрещенные символы *}
{array name='captcha_types'}
{array_append name='captcha_types' key='mixed'  value='Все'}
{array_append name='captcha_types' key='chars'  value='Буквы'}
{array_append name='captcha_types' key='digits' value='Цифры'}

{strip}
  <form action="{mod_url modname='SYS_config' type='admin' func='captchaconfig_update'}" method="post">

    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <colgroup>
        <col width="35%">
        <col width="65%">
      </colgroup>

      <thead>
        <tr>
          <th colSpan="2">Настройка капчи</th>
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
          <td class="head">Шрифт капчи</td>
          <td class="even">
             <select name="captcha_font">
              {html_options options=$font_list selected=$captcha_font}
            </select>
          </td>
        </tr>

        <tr>
          <td class="head">Случайный шрифт для каждого символа</td>
          <td class="even">
             {html_radios name="captcha_font_random" options=$yes_no checked=$captcha_font_random separator=" "}
          </td>
        </tr>

        <tr>
          <td class="head">Количество символов</td>
          <td class="even">
            <input type="text" class="span1" maxLength="3" size="5" name="captcha_length" value="{$captcha_length|escape}">
          </td>
        </tr>
        <tr>
          <td class="head">Тип выводимых символов</td>
          <td class="even">
            <select name="captcha_type">
              {html_options options=$captcha_types selected=$captcha_type}
            </select>
          </td>
        </tr>
        <tr>
          <td class="head">Высота картинки</td>
          <td class="even">
            <input type="text" class="span1" maxLength="4" size="5" name="captcha_height" value="{$captcha_height|escape}"> (точек)
          </td>
        </tr>
        <tr>
          <td class="head">Ширина картинки</td>
          <td class="even">
            <input type="text" class="span1"  maxLength="4" size="5" name="captcha_width" value="{$captcha_width|escape}"> (точек)
          </td>
        </tr>

        <tr>
          <td class="head">Минимальный размер шрифта</td>
          <td class="even">
				<input type="text" class="span1" maxLength="3" size="5" name="captcha_font_size_min" value="{$captcha_font_size_min|escape}">
          </td>
        </tr>

        <tr>
          <td class="head">Максимальный размер шрифта</td>
          <td class="even">
				<input type="text" class="span1"  maxLength="3" size="5" name="captcha_font_size_max" value="{$captcha_font_size_max|escape}">
          </td>
        </tr>

        <tr>
          <td class="head">Минимальный угол наклона шрифта</td>
          <td class="even">
				<input type="text" class="span1"  maxLength="3" size="5" name="captcha_font_angle_min" value="{$captcha_font_angle_min|escape}">
          </td>
        </tr>

        <tr>
          <td class="head">Максимальный угол наклона шрифта</td>
          <td class="even">
				<input type="text" class="span1"  maxLength="3" size="5" name="captcha_font_angle_max" value="{$captcha_font_angle_max|escape}">
          </td>
        </tr>

        <tr>
          <td class="head">Учитывать регистр</td>
          <td class="even">
            {html_radios name="captcha_case" options=$yes_no checked=$captcha_case separator=" "}
          </td>
        </tr>

        <tr>
          <td class="head">Случайный цвет символов</td>
          <td class="even">
            {html_radios name="captcha_LetterColor" options=$yes_no checked=$captcha_LetterColor separator=" "}
          </td>
        </tr>


        <tr>
          <td class="head">Цвет фона</td>
          <td class="even">
            R: <input type="text" class="span1"  maxLength="3" size="5" name="captcha_bgColorRed" value="{$captcha_bgColorRed|escape}">&nbsp;&nbsp;
            G: <input type="text" class="span1"  maxLength="3" size="5" name="captcha_bgColorGreen" value="{$captcha_bgColorGreen|escape}">&nbsp;&nbsp;
            B: <input type="text" class="span1"  maxLength="3" size="5" name="captcha_bgColorBlue" value="{$captcha_bgColorBlue|escape}">
          </td>
        </tr>

        <tr>
          <td class="head">Рисовать сетку</td>
          <td class="even">
            {html_radios name="captcha_drawgrid" options=$yes_no checked=$captcha_drawgrid separator=" "}
          </td>
        </tr>
        <tr>
          <td class="head">Расстояние между линиями сетки</td>
          <td class="even">
            <input type="text" class="span1"  maxLength="5" size="5" name="captcha_grid" value="{$captcha_grid|escape}">
          </td>
        </tr>

        <tr>
          <td class="head">Случайный цвет сетки</td>
          <td class="even">
            {html_radios name="captcha_randomGridColor" options=$yes_no checked=$captcha_randomGridColor separator=" "}
          </td>
        </tr>

        <tr>
          <td class="head">Процент случайных точек</td>
          <td class="even">
            <input type="text" class="span1"  maxLength="2" size="5" name="captcha_dotsPercent" value="{$captcha_dotsPercent|escape}">
          </td>
        </tr>

        <tr>
          <td class="head">Пример кода безопасности</td>
          <td class="even">
          	<img src="{mod_url modname='SYS_config' type='admin' func='captchaconfig_preview'}" alt="Пример кода безобасности" border="0">
          </td>
        </tr>

      </tbody>

    </table>
  </form>
{/strip}