<?php
/*
 * Главное польовательская страница :))
*/
function SYS_themes_user_main()
  {
  sysRedirect(sysModURL('SYS_themes','admin','main'));
  }

/*
 * Изменение темы текушего пользователя
*/
function SYS_themes_user_changetheme()
  {
  $sysObject = 'SYS_themes::user::changetheme';
  //Проверка на доступ
  if (!sysSecAuthAction($sysObject, ACCESS_READ)) return false;

  $theme_name = sysVarCleanFromInput('theme_name');
  if (empty ($theme_name)) sysException ('SYS_themes_user_changetheme', BAD_PARAM, 'Theme name empty '.$theme_name);

  if (sysThemeAvailable($theme_name))
    {
    sysSessionSetVar('user_theme', $theme_name);
    if (sysUserLoggedIn())
      {
      sysUserSetVar('user_theme', $theme_name);
      }
    }

  global $HTTP_SERVER_VARS;
  $http_referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
  sysRedirect($http_referer);
  }

?>