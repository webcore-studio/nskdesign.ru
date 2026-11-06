{if !empty($blocks.exact)}
  {foreach item=block from=$blocks.exact}
    {if !empty($block.block_tpl) && $block.block_tpl == 'admin_menu_display.tpl'}{$block.block_content}{/if}
  {/foreach}
{/if}
