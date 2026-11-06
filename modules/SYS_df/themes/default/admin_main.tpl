{strip}
{*Подгатавливаем масив к отображению в html_table*}
  {foreach item = table_name from=$tables_list}
    {* Формируем html урл*}
    {capture name=htmlurl}
      {* Формируем масив переменные для урла *}
      {array name='url_vars'}
      {array_append name='url_vars' key='table_name' value=$table_name}
      <a href="{mod_url type='admin' modname='SYS_df' func='view' vars=$url_vars}">{$table_name|escape}</a>
    {/capture}
    {array_append name="display_tables_list" value=$smarty.capture.htmlurl"}
  {/foreach}
  {*************************************************}

  {html_table loop=$display_tables_list cols=4 table_attr='class="outer" cellSpacing="1" cellPadding="4" width="100%"'
            td_attr='class="even"' inner='rows' th= "Доступные таблицы"}
{/strip}