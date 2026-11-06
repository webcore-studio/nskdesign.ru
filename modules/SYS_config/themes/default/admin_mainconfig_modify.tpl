{strip}
  {* Для всех да нет *}
  {array name='yes_no'}
  {array_append name='yes_no' key='1' value=#yes#}
  {array_append name='yes_no' key='0' value=#no#}
    <form method="POST" name="mainconfig_form" action="{mod_url modname='SYS_config' type='admin' func='mainconfig_update'}">
      <table class="outer" cellSpacing="1" cellPadding="4" width="100%">
        <colgroup>
          <col width="35%">
          <col width="65%">
        </colgroup>

        <thead>
        <tr>
          <th colSpan="2">{#site_mainconfig#}</th>
        </tr>
        </thead>

        <tfoot>
        <tr>
          <td colspan="2" align="center" class="foot">
            <input class="btn" type="submit" value="{#save#}" name="button">
          </td>
        </tr>
        </tfoot>

        <tbody>

        <tr>
          <td class="head" colspan=2 style="border-bottom:1px solid #ccc; padding:5px;"><b>Отображение сайта</b></td>
        </tr>

        <tr>
          <td class="head">{#site_name#}</td>
          <td class="even"><input type="text" name="site_name" size="50" value="{$site_name|escape}"></td>
        </tr>

        <tr>
          <td class="head">{#site_slogan#}</td>
          <td class="even"><input type="text" name="site_slogan" size="50" value="{$site_slogan|escape}"></td>
        </tr>

        <tr>
          <td class="head">{#site_url#}</td>
          <td class="even"><input type="text" name="site_url" size="50" value="{$site_url|escape}"></td>
        </tr>

        <tr>
          <td class="head">{#adminmail#}</td>
          <td class="even"><input type="text" name="adminmail" size="50" value="{$adminmail|escape}"></td>
        </tr>

        <tr>
          <td class="head">{#lang#}</td>
          <td class="even">
            <select name=lang>
            {html_options options=$langlist selected=$lang}
            </select>
          </td>
        </tr>

        <tr>
          <td class="head">Логотип</td>
          <td class="even"><input type="text" maxLength="100" size="50" name="site_logo" value="{$site_logo}">&nbsp;
            {*imagebrowse action="document.mainconfig_form.site_logo.value"*}</td>
        </tr>



        <tr>
          <td class="head">{#startpage#}</td>
          <td class="even">
            <select name=startpage>
              {html_options options=$modlist selected=$startpage}
            </select>
          </td>
        </tr>

        <tr>
          <td class="head">{#default_theme#}</td>
          <td class="even">
            <select name=default_theme>
              {html_options options=$themelist selected=$default_theme}
            </select>
          </td>
        </tr>





        <tr>
          <td class="head" colspan=2 style="border-bottom:1px solid #ccc; padding:5px;padding-top:40px"><b>Технические параметры</b></td>
        </tr>

        <tr>
          <td class="head">{#texteditor#}</td>
          <td class="even">
            <select name=texteditor>
              {html_options options=$texteditorlist selected=$texteditor}
            </select>
          </td>
        </tr>

        <tr>
          <td class="head">{#onException#}</td>
          <td class="even">
            <select name=onException>
              {html_options options=$onExceptionList selected=$onException}
            </select>
          </td>
        </tr>


        <tr>
          <td class="head">{#intranet#}</td>
          <td class="even">
            {html_radios name="intranet" options=$yes_no checked=$intranet separator=" "}
          </td>
        </tr>


        <tr>
          <td class="head">{#debug#}</td>
          <td class="even">
            {html_radios name="debug" options=$yes_no checked=$debug separator=" "}
          </td>
        </tr>


        <tr>
          <td class="head">{#gzip_compression#}</td>
          <td class="even">
            {html_radios name="gzip_compression" options=$yes_no checked=$gzip_compression separator=" "}
          </td>
        </tr>

   {*     <tr>
          <td class="head">{#shorturls#}</td>
          <td class="even">
            {html_radios name="shorturls" options=$yes_no checked=$shorturls separator=" "}
          </td>
        </tr> *}


        <tr>
          <td class="head">{#check_referer#}</td>
          <td class="even">
            {html_radios name="check_referer" options=$yes_no checked=$check_referer separator=" "}
          </td>
        </tr>

        <tr>
          <td class="head">{#mailerrormessages#}</td>
          <td class="even">
            {html_radios name="mailerrormessages" options=$yes_no checked=$mailerrormessages separator=" "}
          </td>
        </tr>



        <tr>
          <td class="head">{#server_tz#}</td>
          <td class="even">
            <select name=server_tz>
              {html_options options=$tzinfo selected=$server_tz}
            </select>
          </td>
        </tr>

        <tr>
          <td class="head">{#default_tz#}</td>
          <td class="even">
            <select name=default_tz>
              {html_options options=$tzinfo selected=$default_tz}
            </select>
          </td>
        </tr>


        <tr>
          <td class="head" colspan=2  style="border-bottom:1px solid #ccc; padding:5px;padding-top:40px"><b>Настройки посетителей</b></td>
        </tr>


        <tr>
          <td class="head">{#default_group#}</td>
          <td class="even">
            <select name=default_group>
              {html_options options=$grouplist selected=$default_group}
            </select>
          </td>
        </tr>

        <tr>
          <td class="head">{#sesdays#}</td>
          <td class="even"><input type="text" name="sesdays" size="5" value="{$sesdays}"></td>
        </tr>

        <tr>
          <td class="head">{#sesinactivemins#}</td>
          <td class="even"><input type="text" name="sesinactivemins" size="5" value="{$sesinactivemins}"></td>
        </tr>




        <tr>
          <td class="head" colspan=2  style="border-bottom:1px solid #ccc; padding:5px;padding-top:40px"><b>Доступ к сайту</b></td>
        </tr>


        <tr>
          <td class="head">{#closesite#}</td>
          <td class="even">{html_radios name="closesite" options=$yes_no checked=$closesite separator=" "}</td>
        </tr>
		
		 <tr>
          <td class="head">{#accesskey#}</td>
          <td class="even"><input type="text" name="accesskey" value="{$accesskey}"><br><small>{#accesskey_text#}</small></td>
        </tr>

        <tr>
          <td class="head">{#closesite_okgrp#}</td>
          <td class="even">
            <select name=closesite_okgrp>
              {html_options options=$grouplist selected=$closesite_okgrp}
            </select>
          </td>
        </tr>

        <tr>
          <td class="head">{#closesite_text#}</td>
          <td class="even"><textarea name="closesite_text" rows="10" cols="50">{$closesite_text|escape}</textarea></td>
        </tr>

        <tr>
          <td class="head">{#enable_badips#}</td>
          <td class="even">{html_radios name="enable_badips" options=$yes_no checked=$enable_badips separator=" "}</td>
        </tr>

        <tr>
          <td class="head">{#bad_ips#}</td>
          <td class="even">
            <textarea name="bad_ips" rows="5" cols="50">{$bad_ips|escape}</textarea>
          </td>
        </tr>
		
		 <tr>
          <td class="head">Лог времени генерации страниц</td>
          <td class="even">{html_radios name="time_log" options=$yes_no checked=$time_log separator=" "}</td>
        </tr>
		
        </tbody>

      </table>
    </form>
{/strip}