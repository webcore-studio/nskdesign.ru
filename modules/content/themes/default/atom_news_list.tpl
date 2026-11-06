<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
	<title>КОТЭС - Внутрикорпоративный сайт</title>
	<link href="http://intranet.cotes.ru/"/>
	<updated>2011-08-05T23:38:56Z</updated>
	<id>http://intranet.cotes.ru/atom</id>
	{if $docs_list}
	{foreach item=doc from=$docs_list}
	<entry>

		<title>{$doc.displayname}</title>
		<link href="http://intranet.cotes.webcore.ru/{$doc.folder_name}/{$doc.id}.html"/>
		<id>http://intranet.cotes.webcore.ru/{$doc.folder_name}/{$doc.id}.html</id>
		<updated>{$doc.pub_datetime|date_format:"%a, %d %b %Y %H:%M:%S %z"}</updated>
		<summary>{$doc.decription|escape}</summary>{$doc.decription|escape}
	</entry>
	{/foreach}
	{/if}
</feed>