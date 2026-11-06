{strip}
    {* сортировка страниц *}
        {* Формируем масив переменные для урла *}
        {array name='url_vars'}
        {array_append name='url_vars' key='id' value=$id}

          {* displayname *}
          {array_append name='url_vars' key='subdoc_orderby' value='displayname'}
          {array_append name='url_vars' key='subdoc_order_asc' value=true}
          {if $subdoc_orderby=='displayname' and $subdoc_order_asc==true}
            <b>{#orderby_displayname#}</b>&nbsp;(<b>{#asc_letter#}</b>/
          {else}
            <a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">{#orderby_displayname#}</a>&nbsp;
            (<a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">{#asc_letter#}</a>/
          {/if}
          {if $subdoc_orderby=='displayname' and $subdoc_order_asc==false}
          <b>{#desc_letter#}</b>)&nbsp;
          {else}
          {array_append name='url_vars' key='subdoc_order_asc' value=false}
            <a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">{#desc_letter#}</a>)&nbsp;
          {/if}

          {* author *}
          {array_append name='url_vars' key='subdoc_orderby' value='author'}
          {array_append name='url_vars' key='subdoc_order_asc' value=true}
          {if $subdoc_orderby=='author' and $subdoc_order_asc==true}
            <b>{#orderby_author#}</b>&nbsp;(<b>{#asc_letter#}</b>/
          {else}
            <a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">{#orderby_author#}</a>&nbsp;
            (<a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">{#asc_letter#}</a>/
          {/if}
          {if $subdoc_orderby=='author' and $subdoc_order_asc==false}
          <b>{#desc_letter#}</b>)&nbsp;
          {else}
          {array_append name='url_vars' key='subdoc_order_asc' value=false}
            <a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">{#desc_letter#}</a>)&nbsp;
          {/if}


          {* pub_datetime *}
          {array_append name='url_vars' key='subdoc_orderby' value='pub_datetime'}
          {array_append name='url_vars' key='subdoc_order_asc' value=true}
          {if $subdoc_orderby=='pub_datetime' and $subdoc_order_asc==true}
            <b>{#orderby_pub_datetime#}</b>&nbsp;(<b>{#asc_letter#}</b>/
          {else}
            <a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">{#orderby_pub_datetime#}</a>&nbsp;
            (<a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">{#asc_letter#}</a>/
          {/if}
          {if $subdoc_orderby=='pub_datetime' and $subdoc_order_asc==false}
          <b>{#desc_letter#}</b>)&nbsp;
          {else}
          {array_append name='url_vars' key='subdoc_order_asc' value=false}
            <a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">{#desc_letter#}</a>)&nbsp;
          {/if}

          {* counter *}
          {array_append name='url_vars' key='subdoc_orderby' value='counter'}
          {array_append name='url_vars' key='subdoc_order_asc' value=true}
          {if $subdoc_orderby=='counter' and $subdoc_order_asc==true}
            <b>{#orderby_counter#}</b>&nbsp;(<b>{#asc_letter#}</b>/
          {else}
            <a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">{#orderby_counter#}</a>&nbsp;
            (<a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">{#asc_letter#}</a>/
          {/if}
          {if $subdoc_orderby=='counter' and $subdoc_order_asc==false}
          <b>{#desc_letter#}</b>)&nbsp;
          {else}
          {array_append name='url_vars' key='subdoc_order_asc' value=false}
            <a href="{mod_url type='user' modname='content' func='folder_view' vars=$url_vars}">{#desc_letter#}</a>)&nbsp;
          {/if}

{/strip}