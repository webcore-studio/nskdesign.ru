{strip}
  {foreach item=module from=$modules_list}
    <a href="{mod_url type='user' modname=$module.mod_name}">{$module.mod_displayname}</a><br>
  {/foreach}
{/strip}