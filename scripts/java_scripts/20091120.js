/*---------- все ява скрипты 1м файлом, версия 2009.05.10 ----------*/


/*---------------------------- search.js ----------------------------*/
var search_cache_results        = new Array();
var search_prev_string          = '';

/*
 * Нажата клавиша, возможно навигация
*/
function search_onkeydown(event)
  {
  var LiveText = document.getElementById('mod_search_list');

  if (navigator.userAgent.indexOf("Safari") > 0 || navigator.product == "Gecko")
    {isIE = false;}
     else {isIE = true;}

  //Отрабатываем нажатие клавиш "стрелка влево", "стрелка вправо", "enter"
  if (event.keyCode == 37 || event.keyCode == 39)
    {//Ниче не делаем
    return true;
    }

  //Отрабатываем нажатие клавиш "escape"
  if (event.keyCode == 27)
    {
    //Скрываем результаты поиска
    search_hide();
    return true;
    }

  //Отрабатываем нажатие клавиш "enter"
  if (event.keyCode == 13)
    {
    highlight = document.getElementById("LiveTextHighlight");
    if (highlight)
      {
      search_clicked(highlight);
      //Скрываем результаты поиска
      }
    search_hide();
    return true;
    }

  //Отрабатываем нажатие клавиши "стрелка вниз"
  if (event.keyCode == 40 )
    {
    highlight = document.getElementById("LiveTextHighlight");
    //Если никакаой элемент не выделен
    if (!highlight)
      {
      var tbody = LiveText.getElementsByTagName("TBODY").item(0);
      highlight = tbody.firstChild;
      }
      else
        {
        highlight.removeAttribute("id");
        highlight = highlight.nextSibling;
        }

    if (highlight)
      {
      highlight.setAttribute("id","LiveTextHighlight");
      value = highlight.getAttribute("_value");
      //LTSetSelectedValue(LiveTextId, value);
      }

    if (!isIE) { event.preventDefault(); }
    return true;
    }

  //Отрабатываем нажатие клавиши "стрелка вверх"
  if (event.keyCode == 38 )
    {
    highlight = document.getElementById("LiveTextHighlight");
    //Если никакаой элемент не выделен
    if (!highlight)
      {
      var tbody = LiveText.getElementsByTagName("TBODY").item(0);
      highlight = tbody.lastChild;
      }
      else
        {
        highlight.removeAttribute("id");
        highlight = highlight.previousSibling;
        }

    if (highlight)
      {
      highlight.setAttribute("id","LiveTextHighlight");
      value = highlight.getAttribute("_value");
      //LTSetSelectedValue(LiveTextId, value);
      }

    if (!isIE) { event.preventDefault(); }
    return true;
    }

  return true;
  }

/*
 * Отпущенна клавиша, производим поиск
*/
function search_onkeyup(event)
  {
  var LiveText = document.getElementById('mod_search_list');
  var search_string = document.getElementById('mod_search_string').value;

	//Проверки
  if (event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 40 || event.keyCode == 38)
    {//Ниче не делаем
    return true;
    }

  if (search_string.length<2)
    {
    search_hide();
    return true;
    }

	//Ищим туже строку
  if (search_prev_string==search_string)
  	{
  	search_show();
  	return true;
  	}

  //Проверка в кэше
  if (search_cache_results[ search_string ]!=undefined && search_cache_results[ search_string ]!='')
    {
    document.getElementById('mod_search_list').innerHTML = search_cache_results[ search_string ];
    search_show();
  	return true;
    }


  //Производим поиск
  search_prev_string = search_string;
  var search_ajax = new ajax();
  var varsString = '';

 	search_ajax.execute = true;
  search_ajax.requestFile = '/index.php';
  search_ajax.method = 'GET';
  search_ajax.setVar('module', 'search');
  search_ajax.setVar('type',   'ajax');
  search_ajax.setVar('func',   'view');
  search_ajax.setVar('search_string', search_ajax.encodeVAR(search_string));

	search_ajax.element = 'mod_search_list';
	search_ajax.sendAJAX(varsString);

	return true;
  }

/*
 * Отображаем результаты поиска
*/
function search_show()
  {
  //Показываем только если резултат поиска не пустой
  if (document.getElementById('mod_search_list').innerHTML=='') return true;
  document.getElementById('mod_search_list').style.display = 'block';
  return true;
  }

/*
 * Скрываем результаты поиска
*/
function search_hide()
  {
  document.getElementById('mod_search_list').style.display = 'none';

  document.getElementById('mod_search_list').innerHTML='';

  return true;
  }

/**
 * Клик по строке в результате поиска
 */
function search_clicked(el)
  {
  highlight = document.getElementById("LiveTextHighlight");
  if (highlight)  highlight.removeAttribute("id");

	//Заменяем текст введенный пользователем
  var value = el.getAttribute('_value');
 	search_selected(value);
	search_hide();

  return true;
  }

/**
 * Подсвечиваем строку в результатах поиска
 */
function search_hover(el)
  {
  highlight = document.getElementById("LiveTextHighlight");
  if (highlight) highlight.removeAttribute("id");

  el.setAttribute("id","LiveTextHighlight");
	return true;
  }

/**
 * Меняем текст введенный пользователем на выбранный кликом или по энтер
 */
function search_selected(value)
  {
	document.getElementById('mod_search_string').value=value;
	return true;
  }

/*
 * Кэшируем в браузере результат поиска
*/
function search_cache_set(search_string)
  {
  if (document.getElementById('mod_search_list').innerHTML!='' && search_string!='')
    search_cache_results[ search_string ] = document.getElementById('mod_search_list').innerHTML;
  return true;
  }


/*---------------------------- ajax.js ----------------------------*/
function showBusyLayer() {
		var busyLayer = document.getElementById("busy_layer");

	if (window.innerHeight && window.scrollMaxY) {
		yScroll = window.innerHeight + window.scrollMaxY;
		var deff = document.documentElement;
		var hff = (deff&&deff.clientHeight) || document.body.clientHeight || window.innerHeight || self.innerHeight;
		yScroll -= (window.innerHeight - hff);
	} else if (document.body.scrollHeight > document.body.offsetHeight || document.body.scrollWidth > document.body.offsetWidth){
		yScroll = document.body.scrollHeight;
	} else {
		yScroll = document.body.offsetHeight;
  	}

	if (busyLayer != null) {
		busyLayer.style.visibility = "visible";
		busyLayer.style.height = yScroll;
	}
};

function hideBusyLayer() {
		var busyLayer = document.getElementById("busy_layer");
		if (busyLayer != null) {
			busyLayer.style.visibility = "hidden";
			busyLayer.style.height = "0px";
		}
};

function _get_obj_toppos(obj)
{
	var top = obj.offsetTop;

	while( (obj = obj.offsetParent) != null )
	{
		top += obj.offsetTop;
	}

	return top;
};

function center_div()
{
	this.divname = '';
	this.divobj  = '';
};
center_div.prototype.clear_div = function()
{
	try
	{
		if ( ! this.divobj )
		{
			return;
		}
		else
		{
			this.divobj.style.display  = 'none';
		}
	}
	catch(e)
	{
		return;
	}
};
center_div.prototype.Ywindow = function()
{
	var scrollY = 0;

	if ( document.documentElement && document.documentElement.scrollTop )
	{
		scrollY = document.documentElement.scrollTop;
	}
	else if ( document.body && document.body.scrollTop )
	{
		scrollY = document.body.scrollTop;
	}
	else if ( window.pageYOffset )
	{
		scrollY = window.pageYOffset;
	}
	else if ( window.scrollY )
	{
		scrollY = window.scrollY;
	}

	return scrollY;
};
center_div.prototype.move_div = function()
{
	try
	{
		this.divobj = document.getElementById( this.divname );
	}
	catch(e)
	{
		return;
	}

	var my_width  = 0;
	var my_height = 0;

	if ( typeof( window.innerWidth ) == 'number' )
	{
		my_width  = window.innerWidth;
		my_height = window.innerHeight;
	}
	else if ( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) )
	{
		my_width  = document.documentElement.clientWidth;
		my_height = document.documentElement.clientHeight;
	}
	else if ( document.body && ( document.body.clientWidth || document.body.clientHeight ) )
	{
		my_width  = document.body.clientWidth;
		my_height = document.body.clientHeight;
	}

	this.divobj.style.position = 'absolute';
	this.divobj.style.display  = 'block';
	this.divobj.style.zIndex   = 99;

	var divheight = parseInt( this.divobj.style.Height );
	var divwidth  = parseInt( this.divobj.style.Width );

	divheight = divheight ? divheight : 50;
	divwidth  = divwidth  ? divwidth  : 200;

	var scrolly = this.Ywindow();

	var setX = ( my_width  - divwidth  ) / 2;
	var setY = ( my_height - divheight ) / 2 + scrolly-50;

	setX = ( setX < 0 ) ? 0 : setX;
	setY = ( setY < 0 ) ? 0 : setY;

	this.divobj.style.left = setX + "px";
	this.divobj.style.top  = setY + "px";
};

function ajax(file)
  {
	this.AjaxFailedAlert = "AJAX not supported.\n";
	this.requestFile = file;
	this.method = "POST";
	this.URLString = "";
	this.encodeURIString = true;
	this.execute = false;
	this.add_html = false;
	this.loading_fired		= 0;
	this.centerdiv          = null;

	this.onLoading = function() { };
	this.onLoaded = function() { };
	this.onInteractive = function() { };
	this.onCompletion = function() { };

	this.onShow = function( message )
  	{
		if ( ! this.loading_fired )
		  {
			this.loading_fired = 1;

			//------------------------------------------------
			// Change text?
			//------------------------------------------------

  		if ( message )
		    {
   			document.getElementById( 'loading-layer-text' ).innerHTML = message;
		    }
  		this.centerdiv         = new center_div();
  		this.centerdiv.divname = 'loading-layer';
  		this.centerdiv.move_div();
  		}

    return;
    };

	this.onHide = function()
	  {
		try
		  {
			if ( this.centerdiv && this.centerdiv.divobj )
			  {
				this.centerdiv.clear_div();
			  }
		  }
		  catch(e)
		    {
		    }
    this.loading_fired = 0;
    return;
    };


	this.createAJAX = function() {
		try {
			this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (err) {
				this.xmlhttp = null;
			}
		}
		if(!this.xmlhttp && typeof XMLHttpRequest != "undefined")
			this.xmlhttp = new XMLHttpRequest();
		if (!this.xmlhttp){
			this.failed = true;
		}
	};

	this.setVar = function(name, value){
		if (this.URLString.length < 3){
			this.URLString = name + "=" + value;
		} else {
			this.URLString += "&" + name + "=" + value;
		}
	};

	this.encVar = function(name, value){
		var varString = encodeURIComponent(name) + "=" + encodeURIComponent(value);
	return varString;
	};

	this.encodeURLString = function(string){
		varArray = string.split('&');
		for (i = 0; i < varArray.length; i++){
			urlVars = varArray[i].split('=');
			if (urlVars[0].indexOf('amp;') != -1){
				urlVars[0] = urlVars[0].substring(4);
			}
			varArray[i] = this.encVar(urlVars[0],urlVars[1]);
		}
	return varArray.join('&');
	};

	this.encodeVAR = function(url){

	url = url.toString();

	var regcheck = url.match(/[\x90-\xFF]/g);

	if ( regcheck )
	{
		for (var i = 0; i < i.length; i++)
		{
			url = url.replace(regcheck[i], '%u00' + (regcheck[i].charCodeAt(0) & 0xFF).toString(16).toUpperCase());
		}
	}

	return escape(url).replace(/\+/g, "%2B");
	};

	this.runResponse = function(){

                        var milisec = new Date;
                        var jsfound = false;
                        milisec = milisec.getTime();

                        var js_reg = /<script.*?>(.|[\r\n])*?<\/script>/ig;

                        var js_str = js_reg.exec(this.response);
                        if (js_str != null) {

						var js_arr = new Array(js_str.shift());
                        var jsfound = true;

                        while(js_str) {
                                js_str = js_reg.exec(this.response);
                                if (js_str != null) js_arr.push(js_str.shift());
                        }

                          for(var i=0; i<js_arr.length;i++) {
                                this.response = this.response.replace(js_arr[i],'<span id="'+milisec+i+'" style="display:none;"></span>');
                          }
						}
                            if ( this.add_html ) {
                                this.elementObj.innerHTML += this.response;
                            } else {
                                this.elementObj.innerHTML = this.response;
                            }

                        if (jsfound) {

                        var js_content_reg = /<script.*?>((.|[\r\n])*?)<\/script>/ig;

                        for (i = 0; i < js_arr.length; i++) {
                                var mark_node = document.getElementById(milisec+''+i);
                                var mark_parent_node = mark_node.parentNode;
                                mark_parent_node.removeChild(mark_node);

                                js_content_reg.lastIndex = 0;
                                var js_content = js_content_reg.exec(js_arr[i]);
                                var script_node = mark_parent_node.appendChild(document.createElement('script'));
							    script_node.text = js_content[1];

                                var script_params_str = js_arr[i].substring(js_arr[i].indexOf(' ',0),js_arr[i].indexOf('>',0));
                                var params_arr = script_params_str.split(' ');

								if (params_arr.length > 1) {
                                   for (var j=0;j< params_arr.length; j++ )        {

                                        if(params_arr[j].length > 0){
                                                var param_arr = params_arr[j].split('=');
                                                param_arr[1] = param_arr[1].substr(1,(param_arr[1].length-2));
                                                script_node.setAttribute(param_arr[0],param_arr[1]);
                                        }

                                  }
								}

                          }
                        }
	};



	this.sendAJAX = function(urlstring){
		this.responseStatus = new Array(2);
		if(this.failed && this.AjaxFailedAlert){
			alert(this.AjaxFailedAlert);
		} else {
			if (urlstring){
				if (this.URLString.length){
					this.URLString = this.URLString + "&" + urlstring;
				} else {
					this.URLString = urlstring;
				}
			}
			if (this.encodeURIString){
				var timeval = new Date().getTime();
				this.URLString = this.encodeURLString(this.URLString);
				this.setVar("rndval", timeval);
			}
			if (this.element) { this.elementObj = document.getElementById(this.element); }
			if (this.xmlhttp) {
				var self = this;
				if (this.method == "GET") {
					var totalurlstring = this.requestFile + "?" + this.URLString;
					this.xmlhttp.open(this.method, totalurlstring, true);
				} else {
					this.xmlhttp.open(this.method, this.requestFile, true);
				}
				if (this.method == "POST"){
  					try {
						this.xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
					} catch (e) {}
				}

				this.xmlhttp.send(this.URLString);
				this.xmlhttp.onreadystatechange = function() {
					switch (self.xmlhttp.readyState){
						case 1:
							self.onLoading();
						break;
						case 2:
							self.onLoaded();
						break;
						case 3:
							self.onInteractive();
						break;
						case 4:
							self.response = self.xmlhttp.responseText;
							self.responseXML = self.xmlhttp.responseXML;
							self.responseStatus[0] = self.xmlhttp.status;
							self.responseStatus[1] = self.xmlhttp.statusText;
						    self.onHide();
							self.onCompletion();
							if (self.elementObj) {
								var elemNodeName = self.elementObj.nodeName;
								elemNodeName = elemNodeName.toLowerCase();
								if (elemNodeName == "input" || elemNodeName == "select" || elemNodeName == "option" || elemNodeName == "textarea"){
									if (self.response == 'error') { alert('Access denied'); } else {
                                    if ( self.add_html ) {
									self.elementObj.value += self.response;
                                    } else { self.elementObj.value = self.response;}
									}
								} else {
									if (self.response == 'error') { alert('Access denied'); } else {
									if(self.execute) { self.runResponse(); } else {
                                        if ( self.add_html ) {

                                           self.elementObj.innerHTML += self.response;

                                        } else	{ self.elementObj.innerHTML = self.response; }
                                      }
									}
								}
							}
							self.URLString = "";
						break;
					}
				};
			}
		}
	};
this.createAJAX();
};


/*---------------------------- calendar.js ----------------------------*/

function doCalendar(a,b)
{
var c=new ajax();
var d='';
c.setVar('module','calendar');
c.setVar('type','ajax');
c.setVar('func','view');
c.setVar('year',b);
c.setVar('month',a);
c.requestFile='/index.php';
c.method='GET';
c.element='calendar-layer';
c.sendAJAX(d)
};


/*---------------------------- comment.js ----------------------------*/

var comment_cache_newtpl='';
var comment_cache_listtpl='';

function comment_create(a,b,c,d)
{
var e=document.getElementById('comment_new_form');

if(a=='cancel')
	{
	var f=new ajax();
	var g='';
	f.requestFile='/index.php';
	f.method='GET';
	f.setVar('module','comment');
	f.setVar('type','ajax');
	f.setVar('func','list');
	f.setVar('comm_module',b);
	f.setVar('comm_key_a',c);
	f.setVar('comm_key_b',d);
	f.onCompletion(comment_scrool('mod_comment_list'));
	f.element='mod_comment_list';
	f.sendAJAX(g);
	if(e.user_displayname)e.user_displayname.value='';
	e.comm_displayname.value='';
	e.comm_content.value='';
	return false
	}

if(e.user_displayname&&e.user_displayname.value=='')
	{
	alert('Вы не указали Ваше имя.');
	return false
	}

if(e.comm_content.value=='')
	{
	alert('Вы не написали текст комментария.');
	return false
	}

if(a=='preview')
	{
	var f=new ajax();
	var g='';
	f.requestFile='/index.php';
	f.method='POST';
	f.setVar('module','comment');
	f.setVar('type','ajax');
	f.setVar('func','preview');
	f.setVar('comm_module',b);
	f.setVar('comm_key_a',c);
	f.setVar('comm_key_b',d);
	if(e.user_displayname)f.setVar('user_displayname',f.encodeVAR(e.user_displayname.value));
	f.setVar('comm_displayname',f.encodeVAR(e.comm_displayname.value));
	f.setVar('comm_content',f.encodeVAR(e.comm_content.value));
	f.onShow('Предварительный просмотр');
	f.onCompletion(comment_scrool('mod_comment_list'));
	f.element='mod_comment_list';
	f.sendAJAX(g);
	return false
	}

if(a=='create')
	{
	var f=new ajax();
	var g='';
	f.requestFile='/index.php';
	f.method='POST';
	f.setVar('module','comment');
	f.setVar('type','ajax');
	f.setVar('func','create');
	f.setVar('comm_module',b);
	f.setVar('comm_key_a',c);
	f.setVar('comm_key_b',d);
	if(e.user_displayname)f.setVar('user_displayname',f.encodeVAR(e.user_displayname.value));
	if(e.captcha_string)f.setVar('captcha_string',f.encodeVAR(e.captcha_string.value));
	f.setVar('comm_displayname',f.encodeVAR(e.comm_displayname.value));
	f.setVar('comm_content',f.encodeVAR(e.comm_content.value));f.onShow('Публикация отзыва');
	f.execute=true;
	f.element='mod_comment_list';
	f.sendAJAX(g);
	return false
	}

return false
};




function comment_modify(a){if(comment_cache_newtpl=='')comment_cache_newtpl=document.getElementById('mod_comment_new').innerHTML;comment_cache_listtpl=document.getElementById('mod_comment_list').innerHTML;var b=new ajax();var c='';b.onShow('Редактирование отзыва');b.setVar('module','comment');b.setVar('type','ajax');b.setVar('func','modify');b.setVar('id',a);b.requestFile='/index.php';b.method='GET';b.onCompletion(comment_scrool('mod_comment_new'));b.element='mod_comment_new';b.sendAJAX(c);return false}function comment_update(a,b,c,d,e){var f=document.getElementById('comment_modify_form');if(a=='cancel'){if(document.getElementById('mod_comment_list').innerHTML!=comment_cache_listtpl)document.getElementById('mod_comment_list').innerHTML=comment_cache_listtpl;comment_scrool('comment_id_'+b);if(comment_cache_newtpl!='')document.getElementById('mod_comment_new').innerHTML=comment_cache_newtpl;return false}if(f.user_displayname&&f.user_displayname.value==''){alert('Вы не указали Ваше имя.');return false}if(f.comm_content.value==''){alert('Вы не написали текст комментария.');return false}if(a=='preview'){var g=new ajax();var h='';g.requestFile='/index.php';g.method='POST';g.setVar('module','comment');g.setVar('type','ajax');g.setVar('func','preview');g.setVar('id',b);g.setVar('comm_module',c);g.setVar('comm_key_a',d);g.setVar('comm_key_b',e);if(f.user_displayname)g.setVar('user_displayname',g.encodeVAR(f.user_displayname.value));g.setVar('comm_displayname',g.encodeVAR(f.comm_displayname.value));g.setVar('comm_content',g.encodeVAR(f.comm_content.value));g.onShow('Предварительный просмотр');g.onCompletion(comment_scrool('mod_comment_list'));g.element='mod_comment_list';g.sendAJAX(h);return false}if(a=='update'){var g=new ajax();var h='';g.requestFile='/index.php';g.method='POST';g.setVar('module','comment');g.setVar('type','ajax');g.setVar('func','update');g.setVar('id',b);g.setVar('comm_module',c);g.setVar('comm_key_a',d);g.setVar('comm_key_b',e);if(f.user_displayname)g.setVar('user_displayname',g.encodeVAR(f.user_displayname.value));g.setVar('comm_displayname',g.encodeVAR(f.comm_displayname.value));g.setVar('comm_content',g.encodeVAR(f.comm_content.value));g.onShow('Редактирование отзыва');g.execute=true;g.element='mod_comment_list';g.sendAJAX(h);if(comment_cache_newtpl!='')document.getElementById('mod_comment_new').innerHTML=comment_cache_newtpl;return false}return false};function comment_delete(a){var b=confirm('Вы действительно хотите удалить отзыв?');if(b){if(comment_cache_newtpl!='')document.getElementById('mod_comment_new').innerHTML=comment_cache_newtpl;var c=new ajax();var d='';c.setVar('module','comment');c.setVar('type','ajax');c.setVar('func','delete');c.setVar('id',a);c.requestFile='/index.php';c.method='GET';c.element='mod_comment_list';c.onShow('Удаление отзыва');c.sendAJAX(d)}return false}function comment_scrool(a){var b=document.getElementById(a);var c=_get_obj_toppos(b);if(c)scroll(0,c-50)};function comment_captcha_update(){var a=new Date().getTime();document.getElementById('comment_captcha_div').innerHTML='<img src="index.php?module=comment&type=ajax&func=captcha_view&rndval='+a+'" alt="Код безопасности" border="0">';return false};