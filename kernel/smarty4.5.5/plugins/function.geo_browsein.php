<?php
/*
 * Smarty plugin
 * $date
 * $delimiter
 * $a_attr
 * $show_last
 * $href_last
 * $lastname_only
 * -------------------------------------------------------------
  $result = $last_item['displayname'];
        $result.="<a $a_attr href=\"".$item['url']."\">".$item['displayname']."</a>$delimiter";
          $result.="<a $a_attr href=\"".$item['url']."\">".$item['displayname']."</a>";
          $result.="<a href=\"".$item['url']."\">".$item['displayname']."</a>$delimiter";
            $result.="<a href=\"".$item['url']."\">".$item['displayname']."</a>";
      $result.=$item['displayname'];

sysModClassLoad ('address');
$address = new address();
$domain = explode('.', $_SERVER[HTTP_HOST]);
$translate = $address->getByTranslate($domain[0]);
$host = 'http://'.$domain[count($domain)-2].'.'.$domain[count($domain)-1];
if (!isset ($delimiter))
  {
  $delimiter = "&nbsp;<span style=\"font-weight:bold;\">&raquo;</span>&nbsp;";
  };

if (isset($show_last) && !$show_last)
  {//Åñëè show_last = false íå ïîêàçûâàåì ïîñëåäíèé ýëèìåíò
  array_pop ($date);
  };

if ($lastname_only)
  {//Åñëè òîëüêî ïîñëåäíèé
  $last_item = array_pop ($date);
  $result = $last_item[displayname];
    if($params[assign]) $smarty->assign($assign, $result);
    else return $result;
  }else{

//Ôîðìèðóåì ñïèñîê
$result='';
$date = array_reverse($date);
if($translate) $date[] = array(
     'type'=>'root',
     'displayname'=>$translate[cities_name],
     'url'=>'/'
 );
$date[] = array(
     'type'=>'root',
     'displayname'=>'<i class="icon-home"></i>',
     'url'=>$host
  );
$date = array_reverse($date);
if(count($date)<=1) return false;

for ($i=0; $i<count($date); $i++)
  {
  $item=$date[$i];
  if ((($i+1) < count($date)) || $href_last)
    {
    if ($a_attr)//ôîðìèðóåì ñ äîïîëíèòåëüíûìè àòðèáóòàìè href
      {
      if (($i+1) != count($date)) //Åñëè íå ïîñëåäíèé âûâîäèì ðàçäåëèòåëü
        {
        $result.="<a $a_attr href=\"$item[url]\">$item[displayname]</a>$delimiter";
        }
        else
          {//Åñëè ïîñëåäíèé áåç ðàçäåëèòåëÿ
          $result.="<a $a_attr href=\"$item[url]\">$item[displayname]</a>";
          };
      }
      else//ôîðìèðóåì áåç äîïîëíèòåëüíûõ àòðèáóòîâ href
        {
        if (($i+1) != count($date)) //Åñëè íå ïîñëåäíèé âûâîäèì ðàçäåëèòåëü
          {
          $result.="<a href=\"$item[url]\">$item[displayname]</a>$delimiter";
          }
          else
            {//Åñëè ïîñëåäíèé áåç ðàçäåëèòåëÿ
            $result.="<a href=\"$item[url]\">$item[displayname]</a>";
            };
        };
    }
    else//âûâîäèì ïðîñòî íàçâàíèå
      {
      $result.=$item[displayname];
      };
  };
if($params[assign]) $smarty->assign($assign, $result);
else return $result;
  }
}

?>