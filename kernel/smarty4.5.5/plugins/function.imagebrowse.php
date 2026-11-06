<?php
/*
 * Smarty plugin
 * $name
 * $action
 */
function smarty_function_imagebrowse($params, &$smarty)
  {
  extract ($params);

  // Тут вызывается устаревший скрипт, который сейчас не используется, т.к. устарел и взломан
  $result = "<script language=\"javascript\" src=\"scripts/java_scripts/FCKeditor/js/fck_config.js\"></script>
<script language=\"javascript\" type=\"text/javascript\">
<!--
function setImage(selected_image)
  {
  $action = selected_image;
  }
function openNewWindow(sURL, sName, iWidth, iHeight, bResizable, bScrollbars)
  {
  var iTop  = (screen.height - iHeight) / 2 ;
  var iLeft = (screen.width  - iWidth) / 2 ;
  var sOptions = \"toolbar=no\" ;
  sOptions += \",width=\" + iWidth ;
  sOptions += \",height=\" + iHeight ;
  sOptions += \",resizable=\"  + (bResizable  ? \"yes\" : \"no\") ;
  sOptions += \",scrollbars=\" + (bScrollbars ? \"yes\" : \"no\") ;
  sOptions += \",left=\" + iLeft ;
  sOptions += \",top=\" + iTop ;
  var oWindow = window.open(sURL, sName, sOptions)
  oWindow.focus();
  return oWindow ;
  }
function browserServer()
  {
  var sBrowseURL = config.ImageBrowserURL ;
  var iBrowseWindowWidth  = config.ImageBrowserWindowWidth ;
  var iBrowseWindowHeight = config.ImageBrowserWindowHeight ;
  var oWindow = openNewWindow(sBrowseURL, \"BrowseWindow\", iBrowseWindowWidth, iBrowseWindowHeight);
  oWindow.setImage = setImage ;
  }
//-->
</script>
<input type=\"button\" value=\"Выбрать...\" onclick=\"browserServer();\">";

  return $result;
  }

?>