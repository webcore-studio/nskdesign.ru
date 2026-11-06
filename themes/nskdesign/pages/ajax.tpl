
{if $message}
{literal}<script type="text/javascript">$(document).ready(function(){$("#ajaxinfo").delay(1000).fadeOut('slow');})</script>{/literal}
<div id="ajaxinfo" class="alert alert-success alert-block" style="position:absolute;  right:10px; top:10px;  min-width:400px; min-height:30px; z-index:10000;">
<button type="button" class="close" data-dismiss="alert" onclick="$(this).parent().hide();">&times;</button>
{$message}
</div>
{/if}
<span></span>