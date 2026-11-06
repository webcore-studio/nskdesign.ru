<?php
/*
 * Smarty plugin
 * $name
 * $text
 * $width, $height
 */
function smarty_function_texteditor($params, &$smarty)
{
  $result = '';

  // Превращаем каждую пару массива $params ключ=>значение в переменную текущего контекста.
  extract($params);

  if (!isset($editor) || $editor === '' || $editor === null) {
    $editor = sysModGetVar('SYS_config', 'texteditor');
  }
  $editor = (int) $editor;
  if ($editor === 0) {
    $editor = 3; // Дефолтный современный редактор - Jodit
  }
  if (empty($width)) {
    $width = 550;
  }
  ;
  if (empty($height)) {
    $height = 400;
  }
  ;

  /*
  if (($editor==1) and (IsCompatible()))
    {
    $result="
    <SCRIPT language=\"javascript\" src=\"scripts/java_scripts/FCKeditor/fckeditor.js\"></SCRIPT>
    <INPUT type=\"hidden\" name=\"$name\" value=\"" . htmlspecialchars($text, ENT_QUOTES) . "\">
    <SCRIPT language=\"javascript\">
    <!--
    oFCKeditor = new FCKeditor('$name') ;
    oFCKeditor.Width      = '520' ;
    oFCKeditor.Height     = 320 ;
    oFCKeditor.ToolbarSet = 'Default' ;
    oFCKeditor.Value      = '';
    oFCKeditor.CanUpload = false;  // Overrides fck_config.js default configuration
    oFCKeditor.CanBrowse = false;  // Overrides fck_config.js default configuration
    oFCKeditor.Create() ;
    //-->
    </SCRIPT>
    ";
    }
    else
      {
      $result="
      <TEXTAREA name=\"$name\" rows=\"20\" cols=\"80\" wrap=\"virtual\">" . htmlspecialchars($text, ENT_QUOTES) . "</TEXTAREA>
      ";
      };
  */
  // TinyMCE (legacy editor)
  if ($editor == 2 || $editor == 4) {
    $TinyMCE = new TinyMCE;
    $TinyMCE->Value = $text;
    $TinyMCE->CreateTinyMCE($name, $width, $height, $editor);
  }
  // Jodit (new editor)
  elseif ($editor == 3) {
    $JoditEditor = new JoditEditor;
    $JoditEditor->Value = $text;
    $JoditEditor->CreateJodit($name, $width, $height);
  }
  // CKEditor 5
  elseif ($editor == 5) {
    $FCKeditor = new FCKeditor;
    $FCKeditor->Value = $text;
    $FCKeditor->CreateFCKeditor($name, $width, $height);
  }
  // Остальные случаи (например, 1 - нет редактора)
  else {
    echo "<TEXTAREA name=\"$name\" rows=\"22\" cols=\"60\" style=\"WIDTH: $width; HEIGHT: $height\" wrap=\"virtual\">" . $text . "</TEXTAREA>";
  }

  return $result;
}

class JoditEditor
{
  var $Value;

  function __construct()
  {
    $this->Value = '';
  }

  function CreateJodit($instanceName, $width, $height)
  {
    $value = htmlspecialchars($this->Value, ENT_QUOTES);

    if (!sysUserIsLoggedIn()) {
      echo '<TEXTAREA name="' . $instanceName . '" rows="22" cols="78" style="WIDTH: ' . $width . '; HEIGHT: ' . $height . '" wrap="virtual">' . $value . '</TEXTAREA>';
      return;
    }
    $width = (int) $width;
    $height = (int) $height;

    if ($width <= 0) {
      $width = 550;
    }

    if ($height <= 0) {
      $height = 400;
    }

    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@4.7.6/es5/jodit.min.css" />';
    echo '<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jodit@4.7.6/es5/jodit.min.js"></script>';
    echo '<textarea class="joditEditor" name="' . $instanceName . '" rows="22" cols="78" style="width:' . $width . 'px; height:' . $height . 'px" wrap="virtual">' . $value . '</textarea>';
    echo '<script type="text/javascript">';
    echo 'if (window.Jodit) {';
    echo 'var joditInstance = Jodit.make("textarea[name=\"' . $instanceName . '\"]", {';
    echo 'uploader:{url:"/scripts/jodit_connector.php?action=fileUpload",filesVariableName:function(){return "files";}},';
    echo 'filebrowser:{ajax:{url:"/scripts/jodit_connector.php"}},';
    echo 'toolbarAdaptive:true,';
    echo 'buttons:["bold","italic","underline","strikethrough","|","ul","ol","|","table","|","link","image","file","|","font","fontsize","brush","paragraph","|","align","outdent","indent","|","undo","redo","|","source","fullsize","dots"],';
    echo 'buttonsMD:["bold","italic","underline","|","ul","ol","|","table","|","link","image","file","|","font","fontsize","paragraph","|","align","|","undo","redo","|","source","dots"],';
    echo 'buttonsSM:["bold","italic","|","ul","ol","|","link","image","|","font","paragraph","|","undo","redo","|","source","dots"],';
    echo 'buttonsXS:["bold","italic","|","ul","ol","|","link","image","|","undo","redo","source","dots"],';
    echo 'cleanHTML:{fillEmptyParagraph:false,removeEmptyElements:false,replaceNBSP:false,allowTags:false,denyTags:false,removeOnError:false,removeEventAttributes:false,safeJavaScriptLink:false,safeLinksTarget:false},';
    echo 'dtd:{removeExtraBr:false,checkBlockNesting:false},';
    echo 'defaultActionOnPaste:"insert_as_html",';
    echo 'askBeforePasteHTML:false,';
    echo 'height:' . $height . ',';
    echo 'width:' . $width;
    echo '});';
    echo 'if (!window.__gorskyJoditUrlPickerInit) {';
    echo 'window.__gorskyJoditUrlPickerInit = true;';
    echo 'window.__gorskyJoditEditors = window.__gorskyJoditEditors || {};';

    echo 'window.__gorskyOpenFileBrowserForInput = function(editor, input) {';
    echo 'if (!editor) { return; }';
    echo 'if (editor.s && typeof editor.s.save === "function") { editor.s.save(); }';
    echo 'var applyUrl = function(rawData) {';
    echo 'var url = "";';
    echo 'if (typeof rawData === "string") { url = rawData; }';
    echo 'else if (rawData && typeof rawData.fileURL === "string") { url = rawData.fileURL; }';
    // Вот здесь исправленная логика
    echo 'else if (rawData && rawData.files && rawData.files.length) {';
    echo '  var baseUrl = rawData.baseurl || "";'; // Если baseurl пустой, берем просто пустую строку
    echo '  url = baseUrl + rawData.files[0];';
    echo '}';
    echo 'else if (rawData && rawData.path) { url = rawData.path; }';
    echo 'if (!url) { console.error("URL не сформирован", rawData); return; }';

    echo 'window.setTimeout(function() {';
    echo 'try {';
    echo 'if (editor.s && typeof editor.s.restore === "function") { editor.s.restore(); }';
    echo 'if (typeof editor.focus === "function") { editor.focus(); }';

    echo 'var selText = editor.s.sel ? editor.s.sel.toString() : "";';
    echo 'if (selText.length === 0) {';
    echo 'editor.s.insertHTML("<a href=\\"" + url + "\\">" + url + "</a>");';
    echo '} else {';
    echo 'editor.execCommand("createLink", false, url);';
    echo '}';

    echo '} catch (e) { console.error("Ошибка при вставке ссылки:", e); }';
    echo '}, 50);';
    echo '};';

    echo 'try {';
    echo 'if (typeof editor.filebrowser.open === "function") {';
    echo 'var browser = editor.filebrowser.open(applyUrl);';
    echo 'if (browser && typeof browser.on === "function") {';
    echo 'browser.on("select", applyUrl);';
    echo 'browser.on("dblclick", applyUrl);';
    echo '}';
    echo '} else if (typeof editor.filebrowser.show === "function") {';
    echo 'editor.filebrowser.show(applyUrl);';
    echo '}';
    echo '} catch (e) { console.error("Ошибка при открытии FileBrowser:", e); }';
    echo '};';

    echo 'window.__gorskyResolveEditorForInput = function(fallbackEditor, urlInput) {';
    echo 'if (urlInput && typeof urlInput.closest === "function" && window.__gorskyJoditEditors) {';
    echo 'var owner = urlInput.closest("[data-editor_id]");';
    echo 'if (owner) {';
    echo 'var ownerId = owner.getAttribute("data-editor_id");';
    echo 'if (ownerId && window.__gorskyJoditEditors[ownerId]) { return window.__gorskyJoditEditors[ownerId]; }';
    echo '}';
    echo '}';
    echo 'return fallbackEditor;';
    echo '};';
    echo 'window.__gorskyAttachUrlPickerButton = function(editor, root) {';
    echo 'if (!root) { return; }';
    echo 'var inputs = root.querySelectorAll("input[name=\\"url\\"][data-ref=\\"url_input\\"]");';
    echo 'if (!inputs || !inputs.length) { return; }';
    echo 'for (var i = 0; i < inputs.length; i += 1) {';
    echo 'var urlInput = inputs[i];';
    echo 'if (!urlInput || urlInput.getAttribute("data-url-picker-bound") === "1") { continue; }';
    echo 'urlInput.setAttribute("data-url-picker-bound", "1");';
    echo 'var button = document.createElement("button");';
    echo 'button.type = "button";';
    echo 'button.className = "jodit-ui-button";';
    echo 'button.setAttribute("aria-label", "Вставить ссылку");';
    echo 'button.style.marginLeft = "8px";';
    echo 'button.innerHTML = "<span class=\\"jodit-ui-button__icon\\"><svg xmlns=\\"http://www.w3.org/2000/svg\\" viewBox=\\"0 0 1792 1792\\" class=\\"jodit-icon_folder jodit-icon\\"><path d=\\"M1728 608v704q0 92-66 158t-158 66h-1216q-92 0-158-66t-66-158v-960q0-92 66-158t158-66h320q92 0 158 66t66 158v32h672q92 0 158 66t66 158z\\"></path></svg></span>";';
    echo 'var inputEditor = window.__gorskyResolveEditorForInput(editor, urlInput);';
    echo '(function(currentInput, currentEditor, currentButton) {';
    echo 'var stopEvent = function(e) {';
    echo 'e.preventDefault();';
    echo 'e.stopPropagation();';
    echo 'if (typeof e.stopImmediatePropagation === "function") { e.stopImmediatePropagation(); }';
    echo '};';
    echo 'currentButton.addEventListener("pointerdown", stopEvent, true);';
    echo 'currentButton.addEventListener("mousedown", stopEvent, true);';
    echo 'currentButton.addEventListener("mouseup", stopEvent, true);';
    echo 'currentButton.addEventListener("click", function(e) {';
    echo 'stopEvent(e);';
    echo 'var buttonEditor = window.__gorskyResolveEditorForInput(currentEditor, currentInput);';
    echo 'window.__gorskyOpenFileBrowserForInput(buttonEditor, currentInput);';
    echo '});';
    echo '})(urlInput, inputEditor, button);';
    echo 'var holder = urlInput.parentNode;';
    echo 'if (!holder) { continue; }';
    echo 'if (holder.style.display !== "flex") {';
    echo 'holder.style.display = "flex";';
    echo 'holder.style.alignItems = "center";';
    echo '}';
    echo 'holder.appendChild(button);';
    echo '}';
    echo '};';
    echo 'window.__gorskyObserveJoditDialogs = function(editor) {';
    echo 'var observer = new MutationObserver(function() {';
    echo 'window.__gorskyAttachUrlPickerButton(editor, document);';
    echo '});';
    echo 'observer.observe(document.body, { childList: true, subtree: true });';
    echo 'window.__gorskyAttachUrlPickerButton(editor, document);';
    echo '};';
    echo '}';
    echo 'if (joditInstance && joditInstance.id) { window.__gorskyJoditEditors[String(joditInstance.id)] = joditInstance; }';
    echo 'if (window.__gorskyObserveJoditDialogs) { window.__gorskyObserveJoditDialogs(joditInstance); }';
    echo '}';
    echo '</script>';
  }
}

// Класс для подключения редактора FCKeditor (CKEditor 5).
class FCKeditor
{
  var $Value;

  function __construct()
  {
    $this->Value = '';
  }

  function CreateFCKeditor($instanceName, $width, $height)
  {
    if ($this->IsCompatible()) {
      $value = htmlspecialchars($this->Value, ENT_QUOTES);
      echo '<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/48.0.0/ckeditor5.css">';
      echo "<style>.ck-editor__editable_inline { min-height: {$height}px; }</style>";
      echo "<textarea id=\"ckeditor_{$instanceName}\" name=\"$instanceName\" rows=\"22\" cols=\"78\" style=\"width: $width; height: $height\" wrap=\"virtual\">" . $value . "</textarea>";
      echo '<script src="https://cdn.ckeditor.com/ckeditor5/48.0.0/ckeditor5.umd.js"></script>';
      echo "<script>
        const { ClassicEditor, Autosave, Essentials, Paragraph, Autoformat, TextTransformation, LinkImage, Link, ImageBlock, ImageToolbar, BlockQuote, Bold, CloudServices, ImageUpload, ImageInsertViaUrl, AutoImage, Table, TableToolbar, Emoji, Mention, Heading, ImageTextAlternative, ImageCaption, ImageStyle, Indent, IndentBlock, ImageInline, Italic, List, MediaEmbed, TableCaption, TodoList, Underline, Strikethrough, Code, Subscript, Superscript, FontBackgroundColor, FontColor, FontFamily, FontSize, Highlight, HorizontalLine, CodeBlock, Alignment } = CKEDITOR;
        ClassicEditor
            .create( document.querySelector( '#ckeditor_{$instanceName}' ), {
                licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE4MDc3NDcxOTksImp0aSI6ImI4OGMzZmU4LWViMDUtNDFlZi1hMWMxLTMyNTMxMTlmYmY0YyIsImxpY2Vuc2VkSG9zdHMiOlsidWstZ29yc2t5LnJ1Il0sInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiXSwiZmVhdHVyZXMiOlsiRFJVUCIsIkUyUCIsIkUyWuJdLCJyZW1vdmVGZWF0dXJlcyI6WyJQQiIsIlJGIiwiU0NIIiwiVENQIiwiVEwiLCJUQ1IiLCJJUiIsIlNVQSIsIkI2NEEiLCJMUCIsIkhFIiwiUkVEIiwiUEZPIiwiV0MiLCJGQVIiLCJCS00iLCJGUEgiLCJPUkUiXSwidmMiOiI5NzA5MGM0ZCJ9.a2rDvLL_75tWivKHyjMLrkFueivRsSPbxnb1BMmFHq4fmX5ZAoiR1xd9aiMOQh-bLuWZNPqQ9gnD9Y8-Mka13A',
                plugins: [
					Alignment,
							Autoformat,
							AutoImage,
							Autosave,
							BlockQuote,
							Bold,
							CloudServices,
							Code,
							CodeBlock,
							Emoji,
							Essentials,
							FontBackgroundColor,
							FontColor,
							FontFamily,
							FontSize,
							Heading,
							Highlight,
							HorizontalLine,
							ImageBlock,
							ImageCaption,
							ImageInline,
							ImageInsertViaUrl,
							ImageStyle,
							ImageTextAlternative,
							ImageToolbar,
							ImageUpload,
							Indent,
							IndentBlock,
							Italic,
							Link,
							LinkImage,
							List,
							MediaEmbed,
							Mention,
							Paragraph,
							Strikethrough,
							Subscript,
							Superscript,
							Table,
							TableCaption,
							TableToolbar,
							TextTransformation,
							TodoList,
							Underline
				],
                toolbar: [
					
					'undo',
								'redo',
								'|',
								'heading',
								'|',
								'fontSize',
								'fontFamily',
								'fontColor',
								'fontBackgroundColor',
								'|',
								'bold',
								'italic',
								'underline',
								'strikethrough',
								'subscript',
								'superscript',
								'code',
								'|',
								'emoji',
								'horizontalLine',
								'link',
								'mediaEmbed',
								'insertTable',
								'highlight',
								'blockQuote',
								'codeBlock',
								'|',
								'alignment',
								'|',
								'bulletedList',
								'numberedList',
								'todoList',
								'outdent',
								'indent'
				],
                fontFamily: {
                    supportAllValues: true
                },
                fontSize: {
                    options: [10, 12, 14, 'default', 18, 20, 22],
                    supportAllValues: true
                },
                heading: {
                    options: [
                        {
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: 'Heading 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        },
                        {
                            model: 'heading4',
                            view: 'h4',
                            title: 'Heading 4',
                            class: 'ck-heading_heading4'
                        },
                        {
                            model: 'heading5',
                            view: 'h5',
                            title: 'Heading 5',
                            class: 'ck-heading_heading5'
                        },
                        {
                            model: 'heading6',
                            view: 'h6',
                            title: 'Heading 6',
                            class: 'ck-heading_heading6'
                        }
                    ]
                },
                image: {
                    toolbar: ['toggleImageCaption', 'imageTextAlternative', '|', 'imageStyle:inline', 'imageStyle:wrapText', 'imageStyle:breakText']
                },
                link: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: 'https://',
                    decorators: {
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                mention: {
                    feeds: [
                        {
                            marker: '@',
                            feed: [
                                /* See: https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html */
                            ]
                        }
                    ]
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                }

            } )
            .then( editor => {
                window.editor_{$instanceName} = editor;
                
                // Ensure textarea is updated before form submit
                const form = document.querySelector( '#ckeditor_{$instanceName}' ).form;
                if(form) {
                    form.addEventListener('submit', function() {
                        document.querySelector('#ckeditor_{$instanceName}').value = editor.getData();
                    });
                }
            } )
            .catch( error => {
                console.error( error );
            } );
      </script>";
    } else {
      echo "<TEXTAREA name=\"$instanceName\" rows=\"22\" cols=\"78\" style=\"WIDTH: $width; HEIGHT: $height\" wrap=\"virtual\">" . $this->Value . "</TEXTAREA>";
    }
  }

  function IsCompatible()
  {
    return true; // CKEditor 5 is modern
  }
}
// 20.07.2007 sairus
// Класс для использования мультибраузерного редактора TinyMCE
class TinyMCE
{
  var $ToolbarSet;
  var $Value;
  var $CanUpload;
  var $CanBrowse;
  // констуктор
  function __construct()
  {
    $this->ToolbarSet = '';
    $this->Value = '';
    $this->CanUpload = 'none';
    $this->CanBrowse = 'none';
  }

  // констуктор в стиле PHP5 ()
  /*__construct()
  {
      $this->ToolbarSet = '';
      $this->Value = '';
      $this->CanUpload = 'none';
      $this->CanBrowse = 'none';
  }
  */
  function CreateTinyMCE($instanceName, $width, $height, $editor = 1)
  {
    // Проверка на совместимость с другими браузерами или ещё чем-либо. Здесь просто заглушка на
    // случай "А вдруг кто-то из под lynx зайдёт:-)))"
    // Случай совместимости со всем использоваемым клиентом программным обеспечением.
    if ($this->IsCompatible()) {
      // Подключаем автономную Open-Source версию TinyMCE через Cloudflare cdnjs с русской локализацией
      echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>';
      echo "<script>
        tinymce.init({
            selector: 'textarea.WYSIWYG[name=\"$instanceName\"]',
            language: 'ru',
            language_url: '/scripts/tinymce/js/tinymce/langs/ru.js',
            plugins: [
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen', 'insertdatetime',
                'media', 'table', 'emoticons', 'help'
            ],
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                'forecolor backcolor emoticons | help',
            branding: false,
            promotion: false
        });
      </script>";

      // Сталкер путь
      $this->Value = htmlspecialchars($this->Value, ENT_QUOTES);

      // class="mceEditor WYSIWYG" - указывает на то, что это текстовое поле будет обрабатываться
      // TinyMCE

      echo "<textarea class=\"mceEditor WYSIWYG\" name=\"$instanceName\" rows=\"22\" cols=\"78\" style=\"width: 100%; height: $height\" wrap=\"virtual\">" . $this->Value . "</textarea>";

    }
    // Случай несовместимости с чем-либо.
    else {
      echo "<TEXTAREA name=\"$instanceName\" rows=\"22\" cols=\"78\" style=\"WIDTH: $width; HEIGHT: $height\" wrap=\"virtual\">" . $this->Value . "</TEXTAREA>";
    }
  }

  // Функция проверки на совместимость
  function IsCompatible()
  {
    return true;
  }
}
?>