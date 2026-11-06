<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_modifier_translit($text)
  {
  return sysVarTranslit($text);
  }

/* vim: set expandtab: */

?>
