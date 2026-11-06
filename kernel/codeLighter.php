<?php
/**
* TEXT HTML's TAGS EMULATOR
* Compatibility: PHP >= 4.1
* Page Example: http://www.phpclasses.org/browse/package/1379.html
* ---------------------------------------------------------------------
* LAST FIXED: 	multiple URL inline, increase speed convertion, adjusted code, color and list tags, unclosed /* comment.
*		Totally rewrote and fixed wrap_convert() function with url/img/mail preservation.
* LAST ADDS: 	[AS] tag with different highlight FlashMX like
*		you can add one or more personal tags
* LAST CHANGES: IMG now use [IMG="{url}"]{comment}[/IMG] as quote and url.
*		Now [PHP] & [AS] use monospace font on.
*		Now are right both [URL="{URL}"] , [URL={URL}]
*
* ---------------------------------------------------------------------
* WHAT IS THIS:
* HTML 4.01 W3C Strict Standard VAlidated tags emulator with:
* 	personalizable style sheet
*	great [tag] engine
*	JavaScript crossbrowser for checks and helps
*	automatic form / textarea / buttons generation
*
* ---------------------------------------------------------------------
* HOW TO USE:
* $codeLighter = new codeLighter();
* echo $codeLighter->convert($text_var);
* WARNING: the better way to use this script is give an htmlentities($text_var);
* so if you haven't converted $text_var you need to do this.
* Example:
*	echo $codeLighter->convert( htmlentities($text_var) );
*
* ---------------------------------------------------------------------
* WHAT YOU CAN DO WITH THIS:
* Accepted tags: B, U, I, CODE, PHP, AS, QUOTE, LIST, MAIL, URL, IMG, FONT, SIZE, COLOR, CENTER, RIGHT
* B => [b]{TEXT}[/b]
* U => [u]{TEXT}[/u]
* I => [i]{TEXT}[/i]
* CENTER => [center]{center text}[/center]
* RIGHT => [right]{right text}[/right]
* CODE => [code]{code text}[/code]
* PHP => [php]{php text}[/php]
* ACTIONSCRIPT => [as]{action script text}[/as]
* QUOTE => [quote]{quoted text}[/quote] | [quote="posted from"]{quoted text}[/quote]
* LIST => [list][*]{TEXT}[/list] | [list=1][*]{TEXT}[/list]
*	type:	NULL => default, unordered list
* 		1 => numeric ordered list
*		[ for alphabetic list write to W3.org ... they wrote "Safe for kids" with DL sintax ... ]
*
* 	Example: "[list]
*		  [*]something
*		  [*]something else
* 		  [/list]"
* 	Example: "[list=1]
*		  [*]something
*		  [*]something else
* 		  [/list]"
* MAIL => {valid email addres} | [mail]{valid email addres}[/mail] | [mail={valid email addres}]{TEXT}[/mail]
* 	Example: "[mail]andrea@3site.it[/mail]"
* 	Example: "[mail=andrea@3site.it]my email[/mail]"
* URL => {valid url addres} | [url]{valid url addres}[/url] | [url="{valid url addres}"]{TEXT}[/url] | [url={valid url addres}]{TEXT}[/url]
* 	Example: "[url]http://www.3site.it/[/url]"
* 	Example: "[url="http://www.3site.it/"]hello world![/url]"
* 	Example: "[url=http://www.3site.it/]hello world![/url]"
* IMG => [img]{valid url addres with img}[/img] | [img="{valid url addres with img}"]{ALT/TITLE}[/img] | [img={WIDTHxHEIGHT}]{valid url addres with img}[/img]
* 	Example: "[img]http://www.3site.it/grafica/3site1.gif[/img]"
* 	Example: "[img="http://www.3site.it/grafica/3site1.gif"]this is my site's logo[/img]"
*
* 	Example: "[IMG=100x38]http://www.3site.it/grafica/3site1.gif[/IMG]"
*	NOTE: you need to auth this last type of parsing with $codeLighter->imgSize(maxWidth, maxHeight); function.
* FONT => [font={valid font type}]{TEXT}[/font]
* 	Example: "[font=courier]hello[/font] [font=arial]world[/font] !"
*
* SIZE => [size={number between 1 and 9}]{TEXT}[/size]
* 	Example: "[size=1]hello[/size] [size=9]world[/size] !"
* COLOR => [color={valid hex value}]{TEXT}[/color] | [color=#{valid hex value}]{TEXT}[/color] | [color={valid color name}]{TEXT}[/color]
* 	Example: "[color=#002255]hello[/color] [color=red]world[/color] !"
*
* ---------------------------------------------------------------------
* INTERNAL VARIABLES
* $codeLighter->maxUrlLenght;
*	This variable set max url length for validated link.
*	DEFAULT: 100
*	NOTE: you need to set this before ->convert() function.
*
* TEXTAREA SETTINGS [ optional ]
*
* $codeLighter->textareaButtons;
* 	This variable will return a buttons list with text's shortcuts dedicated for a textArea with id="codeLighterInternalTextarea"
*	NOTE: you need to print $codeLighter->jsLighter; to enable JavaScript buttons functions
*       SHORTCUTS: [B] [U] [I] [IMG] [CODE] [PHP] [URL] [MAIL]
*	NOTE: You can specify different buttons with $codeLighter->area_buttons;
*
* 	Example:
*		echo $codeLighter->jsLighter; // dedicated JavaScript functions ofr these buttons
*		echo $codeLighter->textareaButtons; // buttons
*
* $codeLighter->area_buttons;
*	This variable modify buttons at the top of TextArea.
*	WARNING: You need to inizialize the textarea with $codeLighter->createTextareaLighter(); if you 'll not use automatic form's creator.

* 	DEFAULT: Array('B', 'U', 'I', 'IMG', 'CENTER', 'CODE', 'PHP', 'AS');
*	MAX USAGE: Array('MAIL', 'URL', 'PHP', 'CODE', 'QUOTE', 'IMG', 'LIST', 'B', 'I', 'U', 'CENTER', 'RIGHT', 'AS');
*	NOTE: LIST need [*] simbol
*
* 	Example:
* 	$codeLighter->area_buttons = Array('PHP', 'CODE', 'B', 'I', 'U'); // will create only these ordered buttons
*	NOTE: You must specify this variable befor textarea print.
*
*	Example with no automatic form generator:
* 	$codeLighter->area_buttons = Array('PHP', 'CODE', 'B', 'I', 'U'); // will create only these ordered buttons
*	$codeLighter->createTextareaLighter();
*	echo $codeLighter->textareaButtons;
*	echo $codeLighter->textarea;
*
* $codeLighter->textarea;
*	This variable will return a dedicated CSS textarea's class style with:
*		class="codeLighterTextarea"
*		id="codeLighterInternalTextarea"
*		name="codeLighterText"
*		clos="74"
*		rows="10"
*
* @Author		Andrea Giammarchi
* @Alias		andr3a
* @Site			http://www.3site.it
* @Mail			andrea@3site.it
* @Version		0.2c
* @Begin		31/10/2003
* @lastModify		08/02/2004 18:48
* @Demo			http://www.3site.it/varie/codeLighter/index.php
*/
Class codeLighter {
	var $style, $output, $jsLighter, $textarea, $textareaButtons, $fake, $restriction, $newRestriction, $textarea_check, $area_buttons, $codeConverter;
	var $css_code, $css_a_link, $css_a_visited, $css_a_hover, $css_quote, $css_php, $css_img, $css_button, $css_textarea, $css_textarea_span;
	var $maxUrlLenght, $maxImgWidth, $maxImgHeight;
	var $preg_a, $preg_b, $preg_c, $preg_d, $preg_e, $preg_f;
	/**
	* Public constructor.
	* @Example		$codeLighter = new codeLighter();
	*/
	function codeLighter() {
		$this->newRestriction = Array();
		$this->codeConverter = Array();
		$this->maxImgWidth = false;
		$this->maxImgHeight = false;
		$this->maxUrlLenght = 100;
		$this->jsAddLighter();
		$this->createTextareaLighter();
		$this->fake = 0;
		$def_rest = Array("mail", "url", "php", "as", "code", "quote", "font", "size", "color", "img", "list", "b", "i", "u", "center", "right");
		$this->restrict($def_rest);
		$this->css_code = "
			border: 1px solid silver;
			padding: 8pt;
			font-family: courier, monospace, sans-serif;
			font-size: 8pt;
			color: #000033;
			background-color: #FAFAFA;
		";
		$this->css_a_link = "
			text-decoration: none;
			color: #000080;
			font-family: Verdana, Helvetica, sans-serif;
		";
		$this->css_a_visited = "
			text-decoration: none;
			color: #002255;
			font-family: Verdana, Helvetica, sans-serif;
		";
		$this->css_a_hover = "
			text-decoration: underline;
			color: #0000AA;
			font-family: Verdana, Helvetica, sans-serif;
		";
		$this->css_quote = "
			width: 100%;
			height: 1%;
			border: 1px solid silver;
			padding: 8pt;
			font-family: Verdana, Helvetica, sans-serif;
			font-size: 8pt;
			color: #000000;
			background-color: #F1F1F1;
		";
		$this->css_php = "
			border: 1px solid silver;
			padding: 8px;
			font-family: courier, monospace, sans-serif;
			font-size: 8pt;
			color: #000000;
			background-color: #FDFDFD;
		";
		$this->css_img = "
			border: 0px;
		";
		$this->css_button = "
			border: 1px solid silver;
			font-family: Verdena, Helvetica, sans-serif;
			font-weight: bold;
			color: #787878;
			background-color: #EFEFEF;
			font-size: 7pt;
		";
		$this->css_textarea = "
			border: 1px solid silver;
			font-family: Verdena, Helvetica, sans-serif;
			font-size: 9pt;
		";
		$this->css_textarea_span = "
			font-family: Verdana, Helvetica, sans-serif;
			font-size: 7pt;
			font-weight: normal;
		";
		$this->setCSS("code", $this->css_code);
		$this->setCSS("a:link", $this->css_a_link);
		$this->setCSS("a:visited", $this->css_a_visited);
		$this->setCSS("a:hover", $this->css_a_hover);
		$this->setCSS("php", $this->css_php);
		$this->setCSS("img", $this->css_img);
		$this->setCSS("button", $this->css_button);
		$this->setCSS("textarea", $this->css_textarea);
		$this->setCSS("textarea_check", $this->css_textarea_span);
		$this->textarea_check = "Opened Tags";
		$this->area_buttons = Array('B', 'U', 'I', 'IMG', 'CENTER', 'CODE', 'PHP', 'AS');
		$this->preg_a = "/(?i)(^|\ |\n|\r|\t)(http|https|ftp|irc|ed2k|gopher|telnet|gopher|telnet)(\:\/\/)([^\[\<\>[:space:]]+)($|\ |\n|\r|\t)/";
		$this->preg_b = "/(?i)(^|\ |\n|\r|\t)(w){3}(.)([^\[\<\>[:space:]]+)($|\ |\n|\r|\t)/";
		$this->preg_c = "/(?i)\[url\=(\&quot;|\")(http|https|ftp|irc|ed2k|gopher|telnet|gopher|telnet)(\:\/\/)([^\<\>[:space:]]+?)(\&quot;|\")\]([^\<\>]+?)(\[\/url\])/i";
		$this->preg_d = "/(?i)(\[url\=)(w){3}(\.)([^\[\<\>[:space:]]+?)(\])([^\"\<\>]+?)(\[\/url\])/i";
		$this->preg_e = "/(?i)(\[url\])(http|https|ftp|irc|ed2k|gopher|telnet)(\:\/\/)([^\<\>[:space:]]+?)(\[\/url\])/i";
		$this->preg_f = "/(?i)\[url\]www.([^\<\>[:space:]]+?)\[\/url\]/i";
		$this->preg_g = "/(?i)\[url\=(http|https|ftp|irc|ed2k|gopher|telnet|gopher|telnet)(\:\/\/)([^\<\>[:space:]]+?)\]([^\<\>]+?)(\[\/url\])/i";
		$this->createTextareaLighter();
	}
	/**
	* Public, use to change defaults CSS.
	*
	* $codeLighter->setCSS($type, $value);
	*		With this function you can modify CSS Style
	*		Print in your output (in <head> </head> tags to respect W3C HTML 4.01 standard)
	*		the variable $codeLighter->style;
	*		Example:
	*			echo $codeLighter->style;
	*
	*		$type => code - a:link - a:visited - a:hover - quote - php - img - button - textarea - textarea_check
	* 		NOTE:	these styles have the same CSS information for:
	*				code => table
	*				quote => table
	*				php => table
	*				img => img
	*				textarea => textarea
	*				textarea_check => span
	*				button => input
	*				a:link => a:link
	*				a:visited => a:visited
	*				a:hover => a:hover
	*
	*		$value => valid CSS information
	*
	*		Example:
	*		$CSS_code = "
	*			width: 100%;
	*			height: 1%;
	*			border: 1px solid silver;
	*			padding: 8pt;
	*			font-family: Verdana, Helvetica, sans-serif;
	*			font-size: 8pt;
	*			color: #000033;
	*			background-color: #FAFAFA;
	*		";
	*		$codeLighter->setCSS("code", $CSS_code);
	*		NOTE: if you want change default CSS class values you must use setCSS , then print $codeLighter->style;
	*
	* @Param	String		what type of CSS you want change ( code, a:link, a:visited, a:hover, quote, php, as, img, button, textarea, textarea_check )
	* @Param	String		valid CSS information.
	*/
	function setCSS($type, $def) {
		if( strtolower($type) == "code" ) {
			$this->css_code = $def;
		}
		elseif( strtolower($type) == "a:link" ) {
			$this->css_a_link = $def;
		}
		elseif( strtolower($type) == "a:visited" ) {
			$this->css_a_visited = $def;
		}
		elseif( strtolower($type) == "a:hover" ) {
			$this->css_a_hover = $def;
		}
		elseif( strtolower($type) == "quote" ) {
			$this->css_quote = $def;
		}
		elseif( strtolower($type) == "php" || strtolower($type) == "as" ) {
			$this->css_php = $def;
		}
		elseif( strtolower($type) == "img" ) {
			$this->css_img = $def;
		}
		elseif( strtolower($type) == "button" ) {
			$this->css_button = $def;
		}
		elseif( strtolower($type) == "textarea" ) {
			$this->css_textarea = $def;
		}
		elseif( strtolower($type) == "textarea_check" ) {
			$this->css_textarea_span = $def;
		}
		else {
			die("<br /><b>WARNING:</b> \$type given ( <b>{$type}</b> ) doesn't exist.<br />Accepted types: code, a:link, a:visited, a:hover, quote, php, img, button, textarea .<br />");
		}
		$this->style = "
		<style type=\"text/css\">
		pre.codeLighterCode {".$this->css_code."}
		table.codeLighterQuote {".$this->css_quote."}
		pre.codeLighterPHP {".$this->css_php."}
		img.codeLighterImg {".$this->css_img."}
		a.codeLighterLink:link {".$this->css_a_link."}
		a.codeLighterLink:visited {".$this->css_a_visited."}
		a.codeLighterLink:hover {".$this->css_a_hover."}
		textarea.codeLighterTextarea {".$this->css_textarea."}
		input.codeLighterButton {".$this->css_button."}
		span.codeLighterSpan {".$this->css_textarea_span."}
		</style>
		";
		return true;
	}
	/**
	* Public, use to add one or more tags to converter.
	* 2 ways to add new tags
	* 	$codeLighter->addTag( "myTag", Array("#notes", "#variables", "#strings", "#generic") );
	*	$codeLighter->addTag( "myTag", "font-style: myStyle; border: myBorder;" );
	* If you need another language to highlight use first method.
	* If you need a table with a style ( not indented, not highlighted ) use second method BEFORE echo your
	* $codeLighter->style;
	* @Param	String		internal tag name. Example "python" ( to convert [python]{code}[/python] )
	* @Param	Array/String	Array with 4 colors ( "#notes", "#variables", "#strings", "#generic" ) for code
	*				highlighted, String with regular CSS style for generic persola table
	*/
	function addTag( $tagName, $tagStyle ) {
		$tagName = strtolower( $tagName );
		$this->newRestriction[] = Array( $tagName , $tagStyle );
		if( !is_Array( $tagStyle ) ) {
			$newStyle = explode( "</style>", $this->style );
			$newStyle = $newStyle[0];
			$newset = "table.codeLighter{$tagName} { {$tagStyle} }";
			$this->style = $newStyle.$newset."
			</style>
			";
		}
	}
	/**
	* Public, use to allow [IMG=width_X_height]{IMAGE URL}[/IMG] tag
	*
	* $codeLighter->imgSize($maxWidth, $maxHeight);
	*	This function allow parser to manipolate size of imgages.
	*	Converter will accept a tag like this:
	*	[IMG=100x38]http://www.3site.it/grafica/3site1.gif[/IMG]
	*	$maxWidth -> integer that will be max apceptable width value for an img
	*	$maxHeigh -> integer that will be max apceptable height value for an img
	*	NOTE: if parameters are above these value, the image will be converted with these for width or height or width & height.
	* @Param	int		Max width
	* @Param	int		Max height
	*/
	function imgSize($w, $h) {
		$this->maxImgWidth = $w;
		$this->maxImgHeight = $h;
		$this->restriction[] = "PrivateCheckImgSize";
	}
	function globalRegCheck( $t ) {
		$preg_modeMail = "(([a-z0-9]+[\._\-]?){1,3}([a-z0-9])*\@([a-z0-9]+[\._\-]?){1,3}([a-z0-9])*\.[a-z]{2,6})";
	        $preg_mail1 = "/(?i)(\[mail\])?{$preg_modeMail}(\[\/mail\])?/";
		$preg_mail2 = "/(?i)\[mail\={$preg_modeMail}\]/";
		$preg_imgModel = "(http|https|ftp)(\:\/\/)([^\<\>[:space:]]+?)";
	        $preg_img1 = "/(?i)\[img\]{$preg_imgModel}\[\/img\]/i";
	        $preg_img2 = "/(?i)\[img\=(\&quot;|\"){$preg_imgModel}(\&quot;|\")\]/i";
	        $preg_img3 = "/(?i)\[img\=([0-9]{1,}x[0-9]{1,})\]{$preg_imgModel}\[\/img\]/i";
		$preg_urlModel = "(http|https|ftp|irc|ed2k|gopher|telnet)(\:\/\/)([^\[\<\>[:space:]]+?)";
		$preg_url1 = "/(?i)^{$preg_urlModel}/i";
		$preg_url2 = "/(?i)^((w){3}(.)([^\[\<\>[:space:]]+))/i";
		$preg_url3 = "/(?i)(\[url\=)(w){3}(\.)([^\[\<\>[:space:]]+?)(\])/i";
		$preg_url4 = "/(?i)\[url\=(\&quot;|\")?{$preg_urlModel}(\&quot;|\")?\]/i";
		$preg_url5 = "/(?i)(\[url\]){$preg_urlModel}(\[\/url\])/i";
		$preg_url6 = "/(?i)\[url\]www.([^\<\>[:space:]]+?)\[\/url\]/i";
		if( preg_match($preg_url6, $t) || preg_match($preg_url5, $t) || preg_match($preg_url4, $t) || preg_match($preg_url3, $t) || preg_match($preg_url2, $t) || preg_match($preg_url1, $t) || preg_match($preg_modeMail, $t) || preg_match($preg_mail1, $t) || preg_match($preg_mail2, $t) || preg_match($preg_img1, $t) || preg_match($preg_img2, $t) || preg_match($preg_img3, $t) ) {
			return true;
		}
		else {
			return false;
		}
	}
	function wrapConvert( $text, $size, $force = true, $tag = " " ) {
		$withN = explode("\n\r", trim($text));
		$globalWrap = "";
		for( $a=0; $za=count($withN), $a < $za; $a++ ) {
			$ex = explode(" ", trim($withN[$a]));
			for( $b=0; $zb = count($ex), $b < $zb; $b++ ) {
				if( $this->globalRegCheck($ex[$b]) ) {
					$globalWrap .= $ex[$b];
				}
				else {
					$provvisory = "";
					$where = 0;
					$ex[$b] = $this->unhtmlentities( $ex[$b] );
					if($force == false) {
						if( strlen($ex[$b]) > $size && !is_numeric($ex[$b]) ) {
							for($c=0; $zc = (ceil(strlen($ex[$b])/$size)), $c < $zc; $c++) {
								$provvisory .= substr($ex[$b],$where,$size).$tag;
								$where += $size;
							}
						}
						else {
							$provvisory = $ex[$b];
						}
					}
					else {
						if( strlen($ex[$b])>$size ) {
							for($c=0; $zd = (ceil(strlen($ex[$b])/$size)), $c < $zd; $c++) {
								$provvisory .= substr($ex[$b],$where,$size).$tag;
								$where += $size;
							}
						}
						else {
							$provvisory = $ex[$b];
						}
					}
					//$globalWrap .= htmlentities( $provvisory );
					$globalWrap .= $provvisory;
				}
				$globalWrap .= " ";
			}
			//$globalWrap .= "\n\r";
		}
		return $globalWrap;
	}
	/**
	* Public, use to modify text without delete valid urls , img or emails tags
	*
	* $codeLighter->wrap_convert($text_var, $max_length [, $convert_numerics [, $separator]]);
	*	This function will parse the text as ->convert(); but you can specify max words lenght.
	*	Such PHP's wordwrap function, this method allow you to chose max lenght of a word and separator type.
	*
	*	$text_var -> text with htmlentities(); // as needed to ->convert();
	*	$max_length -> max words length // will truncate with chosed separator
	*	$convert_numerics -> DEFAULT: true // will truncate numbers too
	*			     with false value will not truncate numbers
	*	$separator -> DEFAULT: " " // only trybcated words will have this kind of separator [ not as wordwrap, separate everything ... ]
	* 	NOTE: this kind of converter doesn't truncate links/emails, use ->maxUrlLenght to manage links length
	* @Param	String		your not yet converted string
	* @Param	int		max word length
	* @Param	boolean		force numeric convertion ( default: true )
	* @Param	String		separator tag ( default: " " )
	*/
	function wrap_convert( $text, $size, $force = true, $tag = " " ) {
		return $this->convert( $this->wrapConvert($text, $size, $force, $tag) );
	}
	function ereg_engine($st, $a_name, $pre, $suf) {
		while( preg_match( "/(?i)\[{$a_name}\]([^\a]+?)\[\/{$a_name}\]/i",  $st ) ) {
			$st = preg_replace("/(?i)\[{$a_name}\]([^\a]+?)\[\/{$a_name}\]/i", $pre."\\1".$suf, $st);
		}
		return $st;
	}
	function case_insensitive_check($st, $tag) {
		$st = preg_replace( "/(?i)\[{$tag}\]([^\a]+?)\[\/{$tag}\]/i", "[{$tag}]\\1[/{$tag}]", $st );
		return $st;
	}
	function unhtmlentities($mystring) {
		$phpv = explode(".", phpversion());
		if($phpv[0]==4 && $phpv[1]<3) {
			// FROM: www.php.net
			$trans_tbl = get_html_translation_table(HTML_ENTITIES);
			$trans_tbl = array_flip ($trans_tbl);
			return strtr($mystring, $trans_tbl);
		}
		else {
			return html_entity_decode($mystring);
		}
	}
	function codeModifier( $mod, &$st, &$pre_table_desc, $c1="#FF8000", $c2="#0000BB", $c3="#DD0000", $c4="#007700", $btw = false ) {
		if( in_Array( $mod, $this->restriction ) || $btw != false ) {
			preg_match_all("/(?i)\[{$mod}\]([^\a]+?)\[\/{$mod}\]/i", $st, $php);
			$countPhp = count( $php[1] );
			for( $a = 0; $a < $countPhp; $a++ ) {
				$elaborate = trim($php[1][$a]);
				$check_add_pre = false;
				$check_add_suf = false;
				if((strpos($elaborate, "&lt;?php") === false) && (strpos($elaborate, "&lt;?PHP") === false) && (strpos($elaborate,"&lt;?") === false)) {
					$elaborate = "<?php\n".trim($elaborate);
					$check_add_pre = true;
				}
				if((strpos($elaborate, "?&gt;") === false)) {
					$elaborate = $elaborate."?>";
					$check_add_suf = true;
				}
				$preserve = md5(" \n\r");
				$elaborate = preg_replace("/( )([\\n\\r]+){1}( )/", $preserve, $elaborate);
				$elaborate = preg_replace("/( )([\\n\\r^ ]+){1}/", $preserve, $elaborate);
				$elaborate .= "*/";
				$elaborate = highlight_string( $this->unhtmlentities( $elaborate ), true );
				if( $check_add_pre == true ) {
					$elaborate = str_replace("<font color=\"#0000BB\">&lt;?php<br /></font>", "", $elaborate);
					$elaborate = str_replace("&lt;?php<br />", "", $elaborate);
				}
				if( $check_add_suf == true ) {
					$elaborate = str_replace("<font color=\"#0000BB\">?&gt;</font>", "", $elaborate);
					$elaborate = str_replace("?&gt;", "", $elaborate);
				}
				$elaborate = preg_replace("/(\\n|\\r)/", "", $elaborate );
				$elaborate = str_replace($preserve, "\r", $elaborate );
				$elaborate = str_replace("<font color=\"", "<span style=\"color: ", $elaborate);
				$elaborate = str_replace("#FF8000", $c1, $elaborate);
				$elaborate = str_replace("#0000BB", $c2, $elaborate);
				$elaborate = str_replace("#DD0000", $c3, $elaborate);
				$elaborate = str_replace("#007700", $c4, $elaborate);
				$elaborate = str_replace("</font>", "</span>", $elaborate);
				$elaborate = str_replace("<code>", "", $elaborate);
				$elaborate = str_replace("</code>", "", $elaborate);
				if( substr($elaborate, ( strlen($elaborate) - 9 ), 9) == "*/</span>" ) {
					$elaborate = substr($elaborate, 0, ( strlen($elaborate) - 9 ) );
				}
				$st = $this->case_insensitive_check($st, "php");
				$pre = "{$pre_table_desc}".strtoupper($mod)."</span><pre class=\"codeLighterPHP\">";
				$toChange = md5("[{$mod}]{$php[1][$a]}[/{$mod}]");
				$this->codeConverter[] = Array( $toChange, $pre.$elaborate."</pre>" );
				$st = str_replace( "[{$mod}]{$php[1][$a]}[/{$mod}]", $toChange, $st);
			}
		}
	}
	/**
	* Global function.
	* Converter engine.
	*
	* @Params	no-params	don't need params.
	*/
	function convert($st) {
		if(get_magic_quotes_gpc()) {
			$st = stripslashes($st);
		}
		$pre_table_desc = "<span style=\"font-family: Verdana, Helvetica, sans-serif; font-size: 7pt; font-style: normal; text-decoration: none; font-weight: bold;\">";
		$this->codeModifier( "php", $st, $pre_table_desc );
		$this->codeModifier( "as", $st, $pre_table_desc, "#898989", "#000080", "#0000EE", "#000000" );
		if(in_Array("url", $this->restriction)) {
			$preg_searc = Array(
			"/(?i)(^|\ |\n|\r|\t)(ed2k\:\/\/\|file\|)([^\|\<\>[:space:]]+)(\|[^\[\<\>[:space:]]+)($|\ |\n|\r|\t)/",
			$this->preg_b,
			$this->preg_d,
			$this->preg_a,
			$this->preg_e,
			$this->preg_f
			);
			$preg_replace = Array(
			"\\1<a class=\"codeLighterLink\" href=\"\\2\\3\\4\" onClick=\"window.open(this.href, '_blank'); return false;\">\\3</a>\\5",
			"\\1<a class=\"codeLighterLink\" href=\"http://www\\3\\4\" onClick=\"window.open(this.href, '_blank'); return false;\">www\\3\\4</a>\\5",
			"<a class=\"codeLighterLink\" href=\"http://www\\3\\4\" onClick=\"window.open(this.href, '_blank'); return false;\">\\6</a>",
			"\\1[url]\\2\\3\\4[/url]\\5",
			"<a class=\"codeLighterLink\" href=\"\\2\\3\\4\" onClick=\"window.open(this.href, '_blank'); return false;\">\\2\\3\\4</a>",
			"<a class=\"codeLighterLink\" href=\"http://www.\\1\" onClick=\"window.open(this.href, '_blank'); return false;\">www.\\1</a>"
			);
			$st = preg_replace($preg_searc, $preg_replace, $st);
		}
		$array_lighter = Array(
			Array(
			"name" => "i",
			"pre" => "<i>",
			"suf" => "</i>",
			),
			Array(
			"name" => "u",
			"pre" => "<span style=\"text-decoration: underline;\">",
			"suf" => "</span>",
			),
			Array(
			"name" => "b",
			"pre" => "<b>",
			"suf" => "</b>",
			),
			Array(
			"name" => "code",
			"pre" => "{$pre_table_desc}code</span><pre class=\"codeLighterCode\">",
			"suf" => "</pre>",
			),
			Array(
			"name" => "quote",
			"pre" => "{$pre_table_desc}quote</span><table class=\"codeLighterQuote\"><tr valign=\"top\"><td align=\"left\">",
			"suf" => "</td></tr></table>",
			)
		);
		for($a=0; $a<count($array_lighter); $a++) {
			$a_name = $array_lighter[$a]["name"];
			if( in_Array($a_name, $this->restriction) ) {
				$st = $this->ereg_engine($st, $a_name, $array_lighter[$a]["pre"], $array_lighter[$a]["suf"]);
			}
	 	}
		$r = "([a-z0-9]+[\._\-]?){1,3}([a-z0-9])*";
		$reg_mail2 = "({$r}\@{$r}\.[a-z]{2,6})";
		$reg_mail = "(^|\ |\n|\r|\t){$reg_mail2}(\ |\n|\r|\t|$)";
		if(in_Array("mail", $this->restriction)) {
			$st = preg_replace("/(?i)".$reg_mail."/", "\\1[mail]\\2[/mail]\\7", $st);
		}
		$elements = Array(
			Array("url", $this->preg_c, "<a class=\"codeLighterLink\" href=\"\\2\\3\\4\" onClick=\"window.open(this.href, '_blank'); return false;\">\\6</a>"),
			Array("url", $this->preg_g, "<a class=\"codeLighterLink\" href=\"\\1\\2\\3\" onClick=\"window.open(this.href, '_blank'); return false;\">\\4</a>"),
			Array("mail", "/(?i)\[mail\={$reg_mail2}\](.+?)\[\/mail\]/i", "<a class=\"codeLighterLink\" href=\"mailto:\\1\">\\6</a>"),
			Array("mail", "/(?i)\[mail\]{$reg_mail2}\[\/mail\]/i", "<a class=\"codeLighterLink\" href=\"mailto:\\1\">\\1</a>"),
			Array("center", "/(?i)\[center\]([^\a]+?)\[\/center\]/i", "<div style=\"text-align: center;\">\\1</div>"),
			Array("quote", "/(?i)\[quote\=(&quot;|\")([^\n\r\t\<\>]+?)(&quot;|\")\]([^\a]+?)\[\/quote\]/i", "{$pre_table_desc}Цитата</span><br /> &nbsp;<table class=\"codeLighterQuote\"><tr valign=\"top\"><td align=\"left\"><b>\\2</b><br />\\4</td></tr></table>"),
			Array("right", "/(?i)\[right\]([^\a]+?)\[\/right\]/i", "<div style=\"text-align: right;\">\\1</div>"),
			Array("size", "/(?i)\[size\=([1-9])\]([^\a]+?)\[\/size\]/i", "<span style=\"font-size: \\1pt;\">\\2</span>"),
			Array("color", "/(?i)\[color\=([\#]?[0-9a-f]{3}|[\#]?[0-9a-f]{6}|[a-z]{3,})\]([^\a]+?)\[\/color\]/i", "<span style=\"color: \\1;\">\\2</span>"),
			Array("font", "/(?i)\[font\=([a-z]+)\]([^\a]+?)\[\/font\]/i", "<span style=\"font-family: \\1, Helvetica, sans-serif;\">\\2</span>"),
			Array("img", "/(?i)\[img\=(\&quot;|\")(http|https|ftp|irc|ed2k|gopher|telnet)?(\:\/\/)?([^\<\>[:space:]]+)(\&quot;|\")\](.+?)\[\/img\]/i", "<img class=\"codeLighterImg\" src=\"\\2\\3\\4\" alt=\"\\6\" title=\"\\6\" />")
		);
		for( $elm = 0; $elm < count( $elements ); $elm++ ) {
			$element = $elements[$elm][0];
			if( in_Array($element, $this->restriction ) ) {
				while( preg_match($elements[$elm][1], $st ) ) {
					$st = preg_replace( $elements[$elm][1], $elements[$elm][2], $st );
				}
				if( $element == "size" ) {
					$codeLighter_private_replace_callback = create_function(
					'$replacement',
					'return $replacement[2] == 1 ? $replacement[1]."7".$replacement[3] : $replacement[1].( 7 + ceil($replacement[2]/2) + $replacement[2] ).$replacement[3];'
					);
					$st = preg_replace_callback("/(\<span style\=\"font-size: )([1-9])(pt;\"\>)/", "$codeLighter_private_replace_callback", $st);

				}
			}
		}
		if( in_Array("code", $this->restriction) ) {
			$st = preg_replace( "/(\<pre class\=\"codeLighterCode\"\>)([[:space:]]*)([^\a]+?)([[:space:]]*)(\<\/pre\>)/i", "\\1\\3\\5", $st );
		}
		if( in_Array("img", $this->restriction) ) {
			$st = preg_replace("/(?i)\[img\](http|https|ftp)(\:\/\/)([^\<\>[:space:]]+?)\[\/img\]/i", "<img class=\"codeLighterImg\" src=\"\\1\\2\\3\" alt=\"\" title=\"\" />", $st);
		}
		if( in_Array("PrivateCheckImgSize", $this->restriction) ) {
			$codeLighter_private_replace_callback = create_function(
			'$replacement',
			'$sizes = str_replace("X", "x", $replacement[1]);
			$sizes = explode("x", $sizes);
			if($sizes[0]>0 && $sizes[0]<='.$this->maxImgWidth.' && $sizes[1]>0 && $sizes[1]<='.$this->maxImgHeight.') {
				return "<img class=\"codeLighterImg\" src=\"".$replacement[2].$replacement[3].$replacement[4]."\" alt=\"\" title=\"\" style=\"width: ".$sizes[0]."px; height: ".$sizes[1]."px;\" />";
			}
			elseif($sizes[0]>'.$this->maxImgWidth.' && $sizes[1]<='.$this->maxImgHeight.') {
				return "<img class=\"codeLighterImg\" src=\"".$replacement[2].$replacement[3].$replacement[4]."\" alt=\"\" title=\"\" style=\"width: '.$this->maxImgWidth.'px; height: ".$sizes[1]."px;\" />";
			}
			elseif($sizes[0]<='.$this->maxImgWidth.' && $sizes[1]>'.$this->maxImgHeight.') {
				return "<img class=\"codeLighterImg\" src=\"".$replacement[2].$replacement[3].$replacement[4]."\" alt=\"\" title=\"\" style=\"width: ".$sizes[0]."px; height: '.$this->maxImgHeight.'px;\" />";
			}
			else {
				return "[img=".$sizes[0]."x".$sizes[1]."]".$replacement[2].$replacement[3].$replacement[4]."[/img]";
			}');
			$st = preg_replace_callback("/(?i)\[img\=([0-9]{1,}x[0-9]{1,})\](http|https|ftp)(\:\/\/)([^\<\>[:space:]]+?)\[\/img\]/i", "$codeLighter_private_replace_callback", $st);
		}
		if( in_Array("list", $this->restriction) ) {
			$codeLighter_private_replace_callback = create_function(
			'$replacement',
			'return "<ol>".str_replace("[*]", "<li>", $replacement[2])."</ol>";'
			);
			$st = preg_replace_callback("/(?i)(\[list\=1\])([^\a]+?)(\[\/list\])/i", "$codeLighter_private_replace_callback", $st);
			$codeLighter_private_replace_callback = create_function(
			'$replacement',
			'return "<ul>".str_replace("[*]", "<li>", $replacement[2])."</ul>";'
			);
			$st = preg_replace_callback("/(?i)(\[list\])([^\a]+?)(\[\/list\])/i", "$codeLighter_private_replace_callback", $st);
		}
		if(in_Array("mail", $this->restriction)) {
			$preg_searc = "/(\<a class\=\"codeLighterLink\" href\=\"mailto\:)([^\a]+?)(\"\>)([^\a]+?)(\<\/a\>)/i";
			$st = $this->replaceCallbackMailUrl( $preg_searc, $st );
		}
		if(in_Array("url", $this->restriction)) {
			$preg_searc = "/(\<a class\=\"codeLighterLink\" href\=\")([^\a]+?)(\" onClick\=\"window.open\(this.href, '_blank'\); return false;\"\>)([^\a]+?)(\<\/a\>)/i";
			$st = $this->replaceCallbackMailUrl( $preg_searc, $st );
		}
		$a = count( $this->newRestriction );
		if( $a > 0 ) {
			$pre_table_desc = "<span style=\"font-family: Verdana, Helvetica, sans-serif; font-size: 7pt; font-style: normal; text-decoration: none; font-weight: bold;\">";
			for( $b = 0; $b < $a; $b++ ) {
			        if( is_Array( $this->newRestriction[$b][1] ) ) {
					$col1 = $this->newRestriction[$b][1][0];
					$col2 = $this->newRestriction[$b][1][1];
					$col3 = $this->newRestriction[$b][1][2];
					$col4 = $this->newRestriction[$b][1][3];
					$this->codeModifier( $this->newRestriction[$b][0], $st, $pre_table_desc , $col1, $col2, $col3, $col4, true );
				}
				else {
					$pre = "{$pre_table_desc}{$this->newRestriction[$b][0]}</span><table class=\"codeLighter{$this->newRestriction[$b][0]}\"><tr valign=\"top\"><td align=\"left\">";
					$st = $this->ereg_engine($st, $this->newRestriction[$b][0], $pre, "</td></tr></table>");
				}
			}
		}
		for( $a=0; $a<count($this->codeConverter); $a++ ) {
			$st = str_replace( $this->codeConverter[$a][0], $this->codeConverter[$a][1], $st );
		}
		$this->output = preg_replace( "/(\<br\ \/\>\<br\ \/\>|\<br\ \/\>\\n|\<br\ \/\>\\n\\r)/", " <br /> <br />", str_replace("<ul><br />", "<ul>", nl2br($st) ) );
		unset($st);
		return($this->output);
	}
	function replaceCallbackMailUrl( $preg_searc, $st ) {
		$codeLighter_private_replace_callback = create_function(
		'$replacement',
		'if( $replacement[2] == $replacement[4] && strlen($replacement[4]) > '.$this->maxUrlLenght.' ) {
			$modded = strlen($replacement[2]) > '.$this->maxUrlLenght.' ? substr($replacement[2], 0, 20)."...".substr($replacement[2], strlen($replacement[2])-20, 20) : $replacement[2];
			return $replacement[1].$replacement[2].$replacement[3].$modded.$replacement[5];
		}
		else {
			return $replacement[1].$replacement[2].$replacement[3].$replacement[4].$replacement[5];
		}'
		);
		return preg_replace_callback($preg_searc, "$codeLighter_private_replace_callback", $st);
	}
	function jsAddLighter() {
		$this->jsLighter = "
		<script type=\"text/javascript\">
			var codeLighter = function() {
				// andr3a [ www.3site.it ] [ andrea@3site.it ]
				var used = Array();
				function codeLighter_add(str) {
					var codeLighterTextarea_add = '';
					var to_span = 'novalue';
					if(!document.getElementById('codeLighterInternalTextarea')) {
						alert('You need a TEXTAREA with id=\"codeLighterInternalTextarea\" to use these buttons!');
					}
					if(document.getElementById('codeLighterTextareaSpanChecker')!=null) {
						to_span = document.getElementById('codeLighterTextareaSpanChecker').innerHTML;
						to_span = parseInt(to_span);
					}
					if(document.getElementById('codeLighter' + str).value == '['+str+']') {
						codeLighterTextarea_add = '['+str+']';
						document.getElementById('codeLighter' + str).value = '[/'+str+']';
						this.total.push('[/'+str+']');
						if(to_span!='novalue') {
							document.getElementById('codeLighterTextareaSpanChecker').innerHTML = (to_span+1);
						}
					}
					else {
						for(var a=(this.total.length-1); a>=0; a--) {
							codeLighterTextarea_add += this.total[a];
							var my_str = this.total[a].substr(2, (this.total[a].length-3));
							document.getElementById('codeLighter' + my_str).value = '['+my_str+']';
							if(to_span!='novalue') {
								to_span--;
							}
							if(my_str==str) {
								break;
							}
						}
						if(this.total.length==0) {
							codeLighterTextarea_add += '[/'+str+']';
							document.getElementById('codeLighter' + str).value = '['+str+']';
							if(to_span!='novalue') {
								to_span--;
							}
						}
						this.total = Array();
						if(to_span!='novalue') {
							document.getElementById('codeLighterTextareaSpanChecker').innerHTML = to_span;
						}
					}
					document.getElementById('codeLighterInternalTextarea').value += codeLighterTextarea_add;
					document.getElementById('codeLighterInternalTextarea').focus();
					return true;
				}
				function codeLighter_closeTags(ar) {
					var to_span = 'novalue';
					for(var a=(ar.length-1); a>=0; a--) {
						document.getElementById('codeLighterInternalTextarea').value += ar[a];
						var my_str = ar[a].substr(2, (ar[a].length-3));
						document.getElementById('codeLighter' + my_str).value = '['+my_str+']';
					}
					this.total = Array();
					if(document.getElementById('codeLighterTextareaSpanChecker')!=null) {
						to_span = document.getElementById('codeLighterTextareaSpanChecker').innerHTML;
						to_span = parseInt(to_span);
					}
					if(to_span!='novalue') {
						document.getElementById('codeLighterTextareaSpanChecker').innerHTML = '0';
					}
					document.getElementById('codeLighterInternalTextarea').focus();
				}
				function codeLighter_maxChars() {
					if(this.counter!=false) {
						var check_len = document.getElementById('codeLighterInternalTextarea').value.length;
						var rest_len = 0;
						if ( check_len >= this.counter ) {
							document.getElementById('codeLighterInternalTextarea').value = document.getElementById('codeLighterInternalTextarea').value.substring(0, this.counter);
						}
						else {
							rest_len = this.counter - check_len;
						}
						document.getElementById('codeLighterTextareaSpanCounter').innerHTML = rest_len;
					}
					else {
						document.getElementById('codeLighterTextareaSpanCounter').innerHTML = document.getElementById('codeLighterInternalTextarea').value.length;
					}
				}
				this.total = used;
				this.add = codeLighter_add;
				this.close = codeLighter_closeTags;
				this.maxChars = codeLighter_maxChars;
				this.counter = false;
			}
			var codeLighter = new codeLighter();
		</script>
		";
		return true;
	}
	/**
	* Public, textarea javascript check for text's max length
	* $codeLighter->setCounter($maxChars);
	*	This setting allow textarea counter for text used on textarea.
	*	If this isn't setted / printed , the counter will check only total chars used on TextArea.
	*	NOTE: it works only with this class JavaScript and this class' textarea with this class' JavaScript.
	*	WARNING: it works only after printed JavaScript with codeLighter object, so you can print this value only after the textarea creation.
	* @Param	int		Max chars in textarea
	*/
	function setCounter($max) {
		return ("
		<script type=\"text/javascript\">
			codeLighter.counter = {$max};
		</script>
		");
	}
	/**
	* Public, to modify default textarea.
	* $codeLighter->createTextareaLighter($to_area, $mouse_overBG, $mouse_outBG, $mouse_overCL, $mouse_outCL);
	*	This function allow you to modify textarea value [ only $codeLighter->textarea ] and buttons output [ only $codeLighter->textareaButtons ]
	*		$to_area => DEFAULT: ""; // text into textarea
	*		$mouse_overBG => DEFAULT: "#FAFAFA"; // button MouseOVER background color
	*		$mouse_outBG => DEFAULT: "#EFEFEF"; // button MouseOUT background color
	*		$mouse_overCL => DEFAULT: "#565656"; // button MouseOVER text color
	*		$mouse_outCL => DEFAULT: "#787878"; // button MouseOUT text color
	*	Example:
	*		$codeLighter->createTextareaLighter( htmlentities( $_POST["codeLighterText"] ) );
	*		echo $codeLighter->textarea;
	*	NOTE: If you want use this not "full optional" textArea but you want use the JavaScript
	*	      tag checker with number of opened tags, you can create by yourself
	*	      a span tag with this id name: "codeLighterTextareaSpanChecker"
	*	      This element will be numeric with total tags opened and not closed on textarea.
	*	      NOTE: you need to print $codeLighter->jsLighter; to use checker function
	*	WARNING: this is the class's inizilizator, you must youse to apply changes on buttons or dedicated CSS
	* @Param	String		Text to show inside textarea ( default: "" )
	* @Param	String		Valid HEX info for button's mouse over ( background HTML color ) ( default: #FAFAFA )
	* @Param	String		Valid HEX info for button's mouse out ( background HTML color ) ( default: #EFEFEF )
	* @Param	String		Valid HEX info for button's mouse over ( text HTML color ) ( default: #565656 )
	* @Param	String		Valid HEX info for button's mouse out ( text HTML color ) ( default: #787878 )
	*/
	function createTextareaLighter( $to_area = "", $mouse_overBG = "#FAFAFA", $mouse_outBG = "#EFEFEF", $mouse_overCL = "#565656", $mouse_outCL = "#787878", $cols = "74", $rows = "10" ) {
		if( preg_match( "/^[0-9]{1,3}$/", $mouse_overBG) ) {
			$cols = $mouse_overBG;
			$mouse_overBG = "#FAFAFA";
		}
		if( preg_match( "/^[0-9]{1,3}$/", $mouse_outBG) ) {
			$rows = $mouse_outBG;
			$mouse_outBG = "#EFEFEF";
		}
		$this->textareaButtons = "";
		for($a=0; $a<count($this->area_buttons); $a++) {
			$this->textareaButtons .= "<input id=\"codeLighter".$this->area_buttons[$a]."\" class=\"codeLighterButton\" type=\"button\" value=\"[".$this->area_buttons[$a]."]\" onClick=\"codeLighter.add('".$this->area_buttons[$a]."');\" onMouseOver=\"this.style.background='{$mouse_overBG}'; this.style.color='{$mouse_overCL}';\" onMouseOut=\"this.style.background='{$mouse_outBG}'; this.style.color='{$mouse_outCL}';\" />
			";
		}
		$this->textareaButtons .= "<input class=\"codeLighterButton\" type=\"button\" value=\"[ * ]\" onClick=\"codeLighter.close(codeLighter.total);\" onMouseOver=\"this.style.background='{$mouse_overBG}'; this.style.color='{$mouse_overCL}';\" onMouseOut=\"this.style.background='{$mouse_outBG}'; this.style.color='{$mouse_outCL}';\" />
			";
		if(get_magic_quotes_gpc()) {
			$to_area = stripslashes($to_area);
		}
		$this->textarea = "
		<br /><textarea class=\"codeLighterTextarea\" id=\"codeLighterInternalTextarea\" name=\"codeLighterText\" onKeyUp=\"codeLighter.maxChars();\" cols=\"{$cols}\" rows=\"{$rows}\">".$to_area."</textarea>";
		return true;
	}
	/**
	* Public, to modify default form.
	* $codeLighter->form($action, $value, $method);
	*	This function return entire FORM with textarea and JavaScript functions.
	*		$action => DEFAULT: $_SERVER["PHP_SELF"]; // form action
	*		$value => DEFAULT: "SEND"; // submit button value
	*		$method => DEFAULT: "POST"; // form method
	*	NOTE: if you need to modify form style, use this class name for your CSS styleSheet: codeLighterAutoForm
	*	NOTE: with this function you have $codeLighter->textarea_check; variable to set tags checker text value [ DEFAULT: "OPENED TAGS" ]
	*
	*	Example:
	*		$codeLighter->textarea_check = "For this post you've opened these tags:";
	* @Param	String		Where will be posted inserted text ( default: $_SERVER["PHP_SELF"] )
	* @Param	String		Submit text value ( default: SEND )
	* @Param	String		Method to send form ( default: POST )
	*/
	function form($action = "", $value = "SEND", $method = "POST") {
		$action = $action == "" ? $_SERVER["PHP_SELF"] : $action;
		$form_auto = $this->jsLighter."
		<FORM CLASS=\"codeLighterAutoForm\" name=\"codeLighterFormName\" METHOD=\"{$method}\" ACTION=\"{$action}\" ENCTYPE=\"multipart/form-data\">
		<DIV STYLE=\"padding: 0px;\">".$this->textareaButtons.$this->textarea."<br />
		<input class=\"codeLighterButton\" type=\"submit\" value=\"{$value}\" />
		<span class=\"codeLighterSpan\">[ ".$this->textarea_check."</span>
		<span id=\"codeLighterTextareaSpanChecker\" class=\"codeLighterSpan\">0</span>
		<span class=\"codeLighterSpan\"> ]</span>
		<span class=\"codeLighterSpan\"> [ </span>
		<span id=\"codeLighterTextareaSpanCounter\" class=\"codeLighterSpan\">0</span>
		<span class=\"codeLighterSpan\"> ]</span></DIV>
		</FORM>
		";
		return $form_auto;
	}
	/**
	* Public, choose what tag you want enable / parse
	*
	* $codeLighter->restrict($restrict_array);
	*		With this function you can specify what kind of tags you want to parse
	*
	*		Example:
	*			$restrict_array = Array("code", "b");
	*			$codeLighter->restrict($restrict_array);
	*			// will parse only [code] or [b]
	* @Param	Array		Parse tags list
	*/
	function restrict($restrict_array) {
		if(!is_Array($restrict_array)) {
			$restrict_array = Array( strtolower($restrict_array) );
		}
		$this->restriction = $restrict_array;
		for($a=0; $a<count($this->restriction); $a++) {
			$this->restriction[$a] = strtolower($this->restriction[$a]);
		}
		return true;
	}
}
?>
