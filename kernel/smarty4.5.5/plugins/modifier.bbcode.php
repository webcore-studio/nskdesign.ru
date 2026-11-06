<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
function smarty_modifier_bbcode($string)
  {
  //
  sysExtLibLoad('bbcode');
  $bbcode = new bbcode();

  //Основные
  $bbcode->add_tag(array('Name'=>'b','HtmlBegin'=>'<span style="font-weight: bold;">','HtmlEnd'=>'</span>'));
  $bbcode->add_tag(array('Name'=>'i','HtmlBegin'=>'<span style="font-style: italic;">','HtmlEnd'=>'</span>'));
  $bbcode->add_tag(array('Name'=>'u','HtmlBegin'=>'<span style="text-decoration: underline;">','HtmlEnd'=>'</span>'));
  $bbcode->add_tag(array('Name'=>'s','HtmlBegin'=>'<span style="text-decoration: line-through;">','HtmlEnd'=>'</span>'));

	//Выравнивание
  $bbcode->add_tag(array('Name'=>'align','HtmlBegin'=>'<div style="text-align: %%P%%">','HtmlEnd'=>'</div>','HasParam'=>true,'ParamRegex'=>'(center|right|left)'));
  $bbcode->add_tag(array('Name'=>'left','HtmlBegin'=>'<div style="text-align: left">','HtmlEnd'=>'</span>'));
  $bbcode->add_tag(array('Name'=>'center','HtmlBegin'=>'<div style="text-align: center">','HtmlEnd'=>'</span>'));
  $bbcode->add_tag(array('Name'=>'right','HtmlBegin'=>'<div style="text-align: right">','HtmlEnd'=>'</span>'));

	//Размер, цвет
  $bbcode->add_tag(array('Name'=>'size','HasParam'=>true,'HtmlBegin'=>'<span style="font-size: %%P%%;">','HtmlEnd'=>'</span>','ParamRegex'=>'[0-9]+'));
  $bbcode->add_tag(array('Name'=>'color','HasParam'=>true,'ParamRegex'=>'[A-Za-z0-9#]+','HtmlBegin'=>'<span style="color: %%P%%;">','HtmlEnd'=>'</span>','ParamRegexReplace'=>array('/^[A-Fa-f0-9]{6}$/'=>'#$0')));

	//Код, цитата, споллер
	$string = str_replace('[quote]', '[quote=""]', $string);
  $bbcode->add_tag(array('Name'=>'quote','HasParam'=>true,'HtmlBegin'=>'<span style="font-family: Verdana,Helvetica,sans-serif; font-size: 7pt; font-style: normal; text-decoration: none; font-weight: bold;">Цитата</span><table class="codeLighterQuote"><tbody><tr valign="top"><td align="left">','HtmlEnd'=>'</td></tr></tbody></table>'));

  $bbcode->add_tag(array('Name'=>'code','HtmlBegin'=>'<span style="font-family: Verdana,Helvetica,sans-serif; font-size: 7pt; font-style: normal; text-decoration: none; font-weight: bold;">Код</span><table class="codeLighterQuote"><tbody><tr valign="top"><td align="left">','HtmlEnd'=>'</td></tr></tbody></table>'));

	//Прочее
  $bbcode->add_tag(array('Name'=>'link','HasParam'=>true,'HtmlBegin'=>'<a href="%%P%%">','HtmlEnd'=>'</a>'));
  $bbcode->add_alias('url','link');

  $bbcode->add_tag(array('Name'=>'email','HasParam'=>true,'HtmlBegin'=>'<a href="mailto:%%P%%">','HtmlEnd'=>'</a>'));
  //$bbcode->add_tag(array('Name'=>'bg','HasParam'=>true,'HtmlBegin'=>'<span style="background: %%P%%;">','HtmlEnd'=>'</span>','ParamRegex'=>'[A-Za-z0-9#]+'));

  return $bbcode->parse_bbcode($string);
  }


?>
