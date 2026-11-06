{strip}
<div style="float:left; margin-right: 5px;" id="RegistrResultsImg">

  {if $CheckInDB|| $SyntaxError || $DomainNotExist}
    <img src='/modules/SYS_users/images/wrong.png'>
  {/if}
  
  {if $Correct && !$perhaps_domain}
    <img src='/modules/SYS_users/images/right.png'>
  {/if}
   
     {if $perhaps_domain && !$DomainNotExist} {*если домен существует но похож на популярный*}
	   <img src='/modules/SYS_users/images/question.png'>
    {/if}
 </div>
 
{*если введен неправильный е-mail*}
{if $DomainNotExist || $SyntaxError || $CheckInDB}  

	<script  type="text/javascript"> 
		$("input[type=submit]").attr("disabled","disabled");

    </script>
  
{else}

   <script  type="text/javascript"> 
     $("input[type=submit]").removeAttr("disabled");
   </script>
{/if}

 
<div id="RegistrResults" style="font-size: 11px;">

    {if $CheckInDB}
      <p>{$CheckInDB}</p>
    {/if}
  
    {if $SyntaxError}
       <p>{$SyntaxError}</p>
    {/if}
  
    {if $DomainNotExist}
      <p>{$DomainNotExist}</p>
    {/if}
  
    {if $perhaps_domain && !$DomainNotExist} {*если домен существует но похож на популярный*}
      <p>{$perhaps_domain}</p>
    {/if}
	
</div>
{/strip}
