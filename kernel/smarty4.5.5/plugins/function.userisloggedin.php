<?php
// Проверяет - залогинелсяли юзер в систему
//assign
function smarty_function_userisloggedin($params, &$smarty)
{
extract($params);
$bool = sysUserIsLoggedIn();
$smarty->assign($assign, $bool);

}
?>