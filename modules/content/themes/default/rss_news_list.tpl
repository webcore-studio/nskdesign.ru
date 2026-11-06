<?xml version="1.0" encoding="utf-8"?>

<rss version="2.0">
  <channel>
    <title>Газета Свидетель</title>
    <copyright>Copyright Газета Свидетель</copyright>
    <link>http://svidetel.ru/</link>
    <language>ru-ru</language>
    <webMaster>{mod_getvar|escape modname='SYS_config' name='adminmail'} (WebMaster)</webMaster>
    <description>Новости</description>
    <category>Новости</category>
    <ttl>15</ttl>
  {if $docs_list}
      {foreach item=doc from=$docs_list}
        <item>
          <title>{$doc.displayname|escape}</title>
          <link>http://svidetel.cx9.ru/{$doc.folder_name}/{$doc.id}.html</link>
          <guid>{$doc.folder_displayname}/{$doc.id}.html</guid>
          <description>{$doc.decription|escape}</description>
          <pubDate>{$doc.pub_datetime|date_format:"%a, %d %b %Y %H:%M:%S %z"}</pubDate>
        </item>
      {/foreach}
    {/if}
  </channel>
</rss>
