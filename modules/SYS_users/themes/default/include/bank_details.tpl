{* Банковские реквизиты *}
<div class="borderitem item" id="bank_details" {if $user_type=='f' || !$user_type}style="display:none;"{/if}>
<table class="outer" cellSpacing="1" cellPadding="4" width="100%">
    <colgroup>
      <col width="30%">
      <col width="70%">
    </colgroup>
	
	<tr>
        <td class="head" colspan=2><h3>Банковские реквизиты<span id="bank_for_ip"> *</span></h3></td>
    </tr>
	
	<tr>
        <td class="right">ИНН</td>
        <td class="even"><input type="text" maxLength="25" size="30" name="bank_details[inn]" value="{$bank_details.inn}" class="required"></td>
    </tr>
	

	<tr>
        <td class="right">КПП</td>
        <td class="even"><input type="text" maxLength="25" size="30" name="bank_details[kpp]" value="{$bank_details.kpp}" class="required"></td>
    </tr>
		
	<tr>
        <td class="right">БИК</td>
        <td class="even"><input type="text" maxLength="25" size="30" name="bank_details[bik]" value="{$bank_details.bik}" class="required"></td>
    </tr>

	
	<tr>
        <td class="right">ОГРН / ОГРНИП</td>
        <td class="even"><input type="text" maxLength="25" size="30" name="bank_details[ogrn]" value="{$bank_details.ogrn}" class="required"></td>
    </tr>
	

	<tr>
        <td class="right">Р/сч</td>
        <td class="even"><input type="text" maxLength="25" size="30" name="bank_details[rs]" value="{$bank_details.rs}" class="required"></td>
    </tr>
	
	<tr>
        <td class="right">Наименование банка</td>
        <td class="even"><input type="text" size="30" name="bank_details[bank]" value="{$bank_details.bank}" class="required"></td>
    </tr>
	
	<tr>
        <td class="right">К/с</td>
        <td class="even"><input type="text" type="text" maxLength="25" size="30" name="bank_details[ks]" value="{$bank_details.ks}" class="required"></td>
    </tr>

</table>
</div>