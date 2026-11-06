{strip}

{if $captcha_enabled && $curuser_id<1}
{literal}
<script>
comment_captcha_update();
</script>
{/literal}

{/if}

	{if !$err_no}
   	{* Выводим листинг *}
   	{comment_list modname=$comm_module comm_key_a=$comm_key_a comm_key_b=$comm_key_b}

   	{* Ява обработчик *}
   	<script language="javascript" type="text/javascript">
  	 	comment_scrool('mod_comment_list');

  		var form = document.getElementById('comment_new_form');

      if (form.captcha_string)
      	form.captcha_string.value='';
      form.comm_displayname.value='';
      form.comm_content.value='';

      comment_captcha_update();
    </script>
  {elseif $err_no==1}
  	<br>
  	<p align="center"><b><font color="#FF0000" size="4">Извините, но Вы ввели неверный код безопасности.</font></b></p>
  	<br>
   	<script language="javascript" type="text/javascript">
  	 	comment_scrool('mod_comment_list');
    </script>
  {elseif $err_no==2}
  	<br>
  	<p align="center"><b><font color="#FF0000" size="4">Извините, но Ваш отзыв очень короткий.</font></b></p>
  	<br>
   	<script language="javascript" type="text/javascript">
  	 	comment_scrool('mod_comment_list');
    </script>
  {elseif $err_no==3}
  	<br>
  	<p align="center"><b><font color="#FF0000" size="4">Извините, но Ваш отзыв очень длинный.</font></b></p>
  	<br>
   	<script language="javascript" type="text/javascript">
  	 	comment_scrool('mod_comment_list');
    </script>
  {/if}

{/strip}