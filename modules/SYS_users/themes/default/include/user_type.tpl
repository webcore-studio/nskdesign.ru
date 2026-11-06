 {* основные данные *}
 
			<div class="control-group">
				<label class="control-label" for="user_type">Тип&nbsp;<b>*</b></label>
				<div class="controls">
					<label class="radio inline"><input type="radio" name="user_type" value="f" {if $user_type=="f" || !$user_type} checked{/if}>Частный клиент</label>
					<label class="radio inline"><input type="radio" name="user_type" value="ip" {if $user_type=="ip"} checked{/if}>ИП</label>
					<label class="radio inline"><input type="radio" name="user_type" value="u" {if $user_type=="u"} checked{/if}>Юр. лицо</label>
				</div>
			</div>

