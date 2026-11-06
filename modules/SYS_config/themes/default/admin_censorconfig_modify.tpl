{strip}
{* Для всех да нет*}
{array name='yes_no'}
{array_append name='yes_no' key='1' value='Да'}
{array_append name='yes_no' key='0' value='Нет'}

<h2 class="browsein">{browsein date=$browsein}</h2>

  <form action="{mod_url modname='SYS_config' type='admin' func='censorconfig_update'}" method="post">
    <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
      <tbody>
        <tr>
          <th colSpan="2">Параметры цензуры слов</th>
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">Разрешить цензуру нежелательных слов?</td>
          <td class="even">
            {html_radios name="censor_enable" options=$yes_no checked=$censor_enable separator=" "}
        </tr>

        <tr vAlign="top" align="left">
          <td class="head">Слова для цензуры<br>
      Не чувствительно к регистру.</td>
          <td class="even"><textarea name="censor_words" rows="5" cols="50">{$censor_words|escape}</textarea></td>
        </tr>


        <tr vAlign="top" align="left">
          <td class="head">Плохие слова будут заменены на:</td>
          <td class="even"><input maxLength="255" size="50" value="{$censor_replace|escape}" name="censor_replace"></td>
        </tr>


        <tr vAlign="top" align="left">
          <td  colspan="2" align="center" class="even"><input class="formButton" type="submit" value="Выполнить!" name="button"></td>
        </tr>

      </tbody>
    </table>
  </form>
{/strip}