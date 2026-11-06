{*Если графический баннер*}
{if $obj.banner_type=='image'}

{if $obj.url}
<a href="{$obj.url}" target=_blank><img src="{$obj.image}" alt="{$obj.alt}" width="{$obj.width}" height="{$obj.height}"></a>
{else}
<img src="{$obj.image}" alt="{$obj.alt}" width="{$obj.width}" height="{$obj.height}">
{/if}
<div style="padding:5px; background-color: {$obj.additional.headbackcolor}; color: {$obj.additional.headcolor}; width:150px;">{$obj.alt}</div>
<div style="padding:5px; background-color: {$obj.additional.textbackcolor}; color: {$obj.additional.textcolor};width:150px;">{$obj.text}</div>

{/if}

{*Если flash*}
{if $banner_type=='flash'}
{if $url}
<a href="{$url}" target=_blank>
<object id="banner{$id}" width="{$width}" height="{$height}">
<param name=movie value="{$image}"><param name=quality value=high>
<embed src="{$image}" quality=high   name="banner{$id}"width="{$width}" height="{$height}"
type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
</embed></object>
</a>
{else}
<object id="banner{$id}" width="{$width}" height="{$height}">
<param name=movie value="{$image}"><param name=quality value=high>
<embed src="{$image}" quality=high   name="banner{$id}"width="{$width}" height="{$height}"
type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
</embed></object>
{/if}
{if $text}
<br><a href="{$url}" target=_blank>{$text}</a>
{/if}
{/if}


{*Если текст*}
{if $obj.banner_type=='text'}
{if $obj.url}<a href="{$obj.url}" target=_blank>{$obj.text}</a>{else}{$obj.text}{/if}
{/if}

