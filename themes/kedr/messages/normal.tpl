<html>
<head>
<title>{mod_getvar modname='SYS_config' name='site_name'} :: {mod_getvar modname='SYS_config' name='site_slogan'}</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="{$time}; url={$url}" />
<style type="text/css">
    body {ldelim}background-color : #E7EFF8; font-size: 12px; font-family: Trebuchet MS,Verdana, Arial, Helvetica, sans-serif; margin: 0px;{rdelim}
    .redirect {ldelim}width: 70%; margin: 110px; text-align: center; padding: 15px; border: #e0e0e0 1px solid; color: #666666; background-color: #f6f6f6;{rdelim}
    .redirect a:link {ldelim}color: #666666; text-decoration: none; font-weight: bold;{rdelim}
    .redirect a:visited {ldelim}color: #666666; text-decoration: none; font-weight: bold;{rdelim}
    .redirect a:hover {ldelim}color: #999999; text-decoration: underline; font-weight: bold;{rdelim}
</style>

</head>
<body>
<div align="center">
<div class="redirect">
  <span style="font-size: 16px; font-weight: bold;">{$message}</span>
  <hr style="height: 3px; border: 2px #D9E6F4 solid; width: 95%;" />
  <p>Если страница автоматически не перезагрузится, пожалуйста нажмите <a href='{$url}'>сюда</a></p>
</div>
</div>
</body>
</html>