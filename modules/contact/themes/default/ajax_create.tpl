{strip}
{if !$err_no}
	<div class="alert alert-success">
		{$after_send_text}
		
	</div>
{else}
	{literal}
		<script>
		contact_captcha_update();
		document.getElementById('captcha_string').value='';
		</script>
	{/literal}
	<div class="alert alert-error">
		<p>Извините, Вы ввели неверный код безопасности!</p>
	</div>

 {/if}

{/strip}