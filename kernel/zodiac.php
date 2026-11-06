<?
/*
 * Класс для определения знака зодиака по дню рождения
  id signs        date start        date end
  1  aquarius    from 21 Gennaio   to 18 Febbraio
  2  aries       from 21 Marzo     to 20 Aprile
  3  cancer      from 22 Giugno    to 22 luglio
  4  cap         from 22 Dicembre  to 20 gennaio
  5  gemini      from 22 Maggio    to 22 giugno
  6  leo         from 22 Luglio    to 21 agosto
  7  libra       from 24 Settembre to 23 ottobre
  8  pisces      from 19 Febbraio  to 20 marzo
  9  sag         from 22 Novembre  to 22 dicembre
  10 scorpio     from 24 Ottobre   to 21 novembre
  11 taurus      from 21 Aprile    to 21 Maggio
  12	virgo			  from 24 Agosto 		to 23 settembre

  водолей – aquarius [ 21 января – 18 февраля ]
  рыбы – pisces [ 19 февраля – 20 марта ]
  овен – aries [ 21 марта – 19 апреля ]
  телец – taurus [ 20 апреля – 20 мая ]
  близнецы – gemini [ 21 мая – 21 июня ]
  рак – cancer [ 22 июня – 22 июля ]
  лев – leo [ 23 июля – 21 августа ]
  дева – virgo [ 22 августа – 21 сентября ]
  весы – libra [ 22 сентября – 22 октября ]
  скорпион – scorpio [ 23 октября – 21 ноября ]
  cтрелец – sagittarius [ 22 ноября – 21 декабря ]
  козерог – capricorn [ 22 декабря – 20 января ]
*/

class sysZodiac
  {
	function zodiac_get($month, $day)
	  {
		if (($day > 31) || ($day < 0)) return false;
		if (($month > 12)	|| ($month < 0)) return false;

		switch ($month)
		  {
			case 1:
				$zodiac	= ($day <=20)	? 'cap' : 'aquarius';
			break;
			case 2:
				if ($day > 29) return false;
				$zodiac	= ($day <=18)	? 'aquarius' : 'pisces';
			break;
			case 3:
				$zodiac	= ($day <=20)	? 'pisces' : 'aries';
			break;
			case 4:
				if ($day > 30) return false;
				$zodiac	= ($day <=20)	? 'aries' : 'taurus';
			break;
			case 5:
				$zodiac	= ($day <=21)	? 'taurus' : 'gemini';
			break;
			case 6:
				if ($day > 30) return false;
				$zodiac	= ($day <=22)	? 'gemini' : 'cancer';
			break;
			case 7:
				$zodiac	= ($day <=22)	? 'cancer' : 'leo';
			break;
			case 8:
				$zodiac	= ($day <=21)	? 'leo' : 'virgo';
			break;
			case 9:
				if ($day > 30) return false;
				$zodiac	= ($day <=23)	? 'virgo' : 'libra';
			break;
			case 10:
				$zodiac	= ($day <=23)	? 'libra' : 'scorpio';
			break;
			case 11:
				if ($day > 30) return false;
				$zodiac	= ($day <=21)	? 'scorpio' : 'sag';
			break;
			case 12:
				$zodiac	= ($day <=22)	? 'sag' : 'cap';
			break;
  		}

  	return $zodiac;
  	}
  }