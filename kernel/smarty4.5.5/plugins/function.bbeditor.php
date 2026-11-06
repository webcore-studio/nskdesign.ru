<?php
/*
 * Smarty plugin
 * $name
 * $text
 * $width, $height
 */
function smarty_function_bbeditor($params, &$smarty)
  {
  extract ($params);

  if (empty ($width)) $width=500;
  if (empty ($height)) $height=25;
  if (empty ($rows)) $rows=14;
  if (empty ($cols)) $cols=60;

  echo '<script type="text/javascript" src="/scripts/java_scripts/bbcode.js"></script>
  			<div style="border: 1px solid rgb(187, 187, 187); width: '.$width.'px; height: '.$height.'px; background-image: url(/files/shared/images/bbcode/bg.gif);">
        <div id="b_b" class="editor_button" onclick="simpletag(\'b\')"><img title="Полужирный" src="/files/shared/images/bbcode/b.gif" border="0" height="25" width="23"></div>
        <div id="b_i" class="editor_button" onclick="simpletag(\'i\')"><img title="Наклонный текст" src="/files/shared/images/bbcode/i.gif" border="0" height="25" width="23"></div>
        <div id="b_u" class="editor_button" onclick="simpletag(\'u\')"><img title="Подчеркнутый текст" src="/files/shared/images/bbcode/u.gif" border="0" height="25" width="23"></div>
        <div id="b_s" class="editor_button" onclick="simpletag(\'s\')"><img title="Зачеркнутый текст" src="/files/shared/images/bbcode/s.gif" border="0" height="25" width="23"></div>
        <div class="editor_button"><img src="/files/shared/images/bbcode/brkspace.gif" border="0" height="25" width="5"></div>

        <div id="b_left" class="editor_button" onclick="simpletag(\'left\')"><img title="Выравнивание по левому краю" src="/files/shared/images/bbcode/l.gif" border="0" height="25" width="23"></div>
        <div id="b_center" class="editor_button" onclick="simpletag(\'center\')"><img title="По центру" src="/files/shared/images/bbcode/c.gif" border="0" height="25" width="23"></div>
        <div id="b_right" class="editor_button" onclick="simpletag(\'right\')"><img title="Выравнивание по правому краю" src="/files/shared/images/bbcode/r.gif" border="0" height="25" width="23"></div>
        <div class="editor_button"><img src="/files/shared/images/bbcode/brkspace.gif" border="0" height="25" width="5"></div>


        <div id="b_size" class="editor_button" onclick="ins_size();"><img title="Размер текста" src="/files/shared/images/bbcode/size.gif" border="0" height="25" width="23"></div>
        <div id="b_color" class="editor_button" onclick="ins_color();"><img title="Цвет текста" src="/files/shared/images/bbcode/color.gif" border="0" height="25" width="23"></div>
        <div class="editor_button"><img src="/files/shared/images/bbcode/brkspace.gif" border="0" height="25" width="5"></div>

        <div id="b_quote" class="editor_button" onclick="simpletag(\'quote\')"><img title="Вставка цитаты" src="/files/shared/images/bbcode/quote.gif" border="0" height="25" width="23"></div>
        <div id="b_code" class="editor_button" onclick="simpletag(\'code\')"><img title="Код" src="/files/shared/images/bbcode/code.gif" border="0" height="25" width="23"></div>
        <div id="b_spoiler" class="editor_button" onclick="simpletag(\'spoiler\')"><img title="Скрытый текст" src="/files/shared/images/bbcode/hide.gif" border="0" height="25" width="23"></div>
        <div class="editor_button"><img src="/files/shared/images/bbcode/brkspace.gif" border="0" height="25" width="5"></div>

        <div id="b_img" class="editor_button" onclick="tag_image()"><img title="Картинка" src="/files/shared/images/bbcode/image.gif" border="0" height="25" width="23"></div>
        <div id="b_emo" class="editor_button" onclick="window.open(\'/themes/kino/html/smiles.htm\',\'newwindow\', config=\'width=455, height=480, toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=0\');"><img title="Вставка смайликов" src="/files/shared/images/bbcode/emo.gif" border="0" height="25" width="23"></div>

        <div class="editor_button" onclick="tag_url()"><img title="Вставка ссылки" src="/files/shared/images/bbcode/link.gif" border="0" height="25" width="23"></div>
        <div class="editor_button" onclick="tag_email()"><img title="Вставка E-Mail" src="/files/shared/images/bbcode/email.gif" border="0" height="25" width="23"></div>

        <div class="editor_button"><img src="/files/shared/images/bbcode/brkspace.gif" border="0" height="25" width="5"></div>

        <div class="editbclose" onclick="closeall()"><img title="Закрыть все открытые теги" src="/files/shared/images/bbcode/close.gif" border="0" height="25" width="23"></div>
      </div>
      <iframe width="154" height="104" id="cp" src="/themes/kino/html/color.html" frameborder="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" scrolling="no" style="visibility:hidden; display: none; position: absolute;"></iframe>
    	<iframe width="204" height="104" id="sizepanel" src="/themes/kino/html/size.html" frameborder="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" scrolling="no" style="visibility:hidden; display: none; position: absolute;"></iframe>';

  echo "<textarea name=\"$name\" id=\"bbcode_text\" rows=\"$rows\" cols=\"$cols\">" . htmlspecialchars($text, ENT_QUOTES) . "</textarea>";


  }

?>