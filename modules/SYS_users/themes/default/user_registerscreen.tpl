{strip}
  <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <thead>
      <tr>
        <th>Условия регистрации</th>
      </tr>
    </thead>

    <tfoot>
      <tr>
        <td class="even" align="center">
            {array name='url_vars'}
            {array_append name='url_vars' key='ref' value=$ref}
          <a href="{mod_url type='user' modname='SYS_users' func='registerscreen_agree' vars=$url_vars}">Я согласен с этими условиями.</a>
        </td>
      </tr>
      <tr>
        <td class="odd" align="center">
          <a href="/index.php">Я не согласен с этими условиями.</a>
        </td>
      </tr>
    </tfoot>

    <tbody>
      <tr>
        <td class="even">
          {$reg_disclaimer|escape|nl2br}
        </td>
      </tr>
    </tbody>
  </table>
{/strip}