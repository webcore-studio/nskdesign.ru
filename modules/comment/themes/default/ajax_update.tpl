{strip}
 	{* выводим листинг *}
 	{comment_list modname=$comm_module comm_key_a=$comm_key_a comm_key_b=$comm_key_b}

 	{* скролируем *}
 	<script language="javascript" type="text/javascript">
	 	comment_scrool( 'comment_id_{$id}' );
  </script>
{/strip}