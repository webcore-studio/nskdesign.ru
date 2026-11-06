{strip}

<table class="table">
    {foreach item=contact from=$contact_list}
        <tr id="contact_id_{$contact.id}">
			<td>
				<div class="date item10">{$contact.contact_addtime|date_format:"%d.%m.%Y, %H:%M"}</div>
				<div class="item">{$contact.contact_content|nl2br}</div>
					<div>
						Отправил: &nbsp;
							<i>
							{if $contact.user_id>0}
								{if $contact.user_id_displayname}
									{$contact.user_id_displayname|truncate:100|escape}&nbsp;(<a href="/user/{$contact.user_id_name}">{$contact.user_id_name}</a>)
								{else}
									<a href="/user/{$contact.user_id_name}">{$contact.user_id_name}</a>
								{/if}
							{else}
								{$contact.user_displayname}
							{/if}
							</i>
					</div>
					<div>E-mail: &nbsp;
							<i>
							{if $contact.user_email}
								<a href="mailto:{$contact.user_email}">{$contact.user_email}</a>
							{elseif $contact.user_id_email}
								<a href="mailto:{$contact.user_id_email}">{$contact.user_id_email}</a>
							{/if}
					</div>
					<div>Телефон: &nbsp;
							<i>
							{if $contact.user_phone}
								{$contact.user_phone}
							{elseif $contact.user_id_user_phone}
								{$contact.user_id_email}
							{/if}
					</div>
					<div>
						IP: &nbsp;
						<i>{$contact.contact_from_ip}</i>
					</div>
				</div>
			</td>

        </tr>
      {/foreach}
    </tbody>
</table>

{if $curuser_sec_level>=70}
    <a class="btn" href="{mod_url type='admin' modname='contact' func='config_modify'}">Конфигурация</a>
{/if}
{/strip}