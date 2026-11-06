<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_modifier_autosmile($string)
	{
	$replace_arr = array('O:-)'  => 'aa.gif',
							         'O:)'   => 'aa.gif',
//								       'O+)'   => 'aa.gif',
//        							 'O=)'   => 'aa.gif',
//        							 '0:-)'  => 'aa.gif',
//        							 '0:)'   => 'aa.gif',
//        							 '0+)'   => 'aa.gif',
//        							 '0=)'   => 'aa.gif',

        							 ' :-)'   => 'ab.gif',
        							 ' :)'    => 'ab.gif',
//        							 '=)'    => 'ab.gif',
//        							 '+)'    => 'ab.gif',

        							 ':-('   => 'ac.gif',
        							 ':('    => 'ac.gif',
//        							 '+('    => 'ac.gif',
//        							 '=('    => 'ac.gif',

        							 ';-)'   => 'ad.gif',
        							 ';)'    => 'ad.gif',
//        							 '^_~'   => 'ad.gif',

        							 ':-P'   => 'ae.gif',
        							 ':P'    => 'ae.gif',
        							 ':-p'   => 'ae.gif',
        							 ':p'    => 'ae.gif',
//        							 '+P'    => 'ae.gif',
//        							 '=P'    => 'ae.gif',
//        							 '+p'    => 'ae.gif',
//        							 '=p'    => 'ae.gif',
//        							 ':-b'   => 'ae.gif',
//        							 ':b'    => 'ae.gif',
//        							 '+b'    => 'ae.gif',
//        							 '=b'    => 'ae.gif',

        							 '8-)'   => 'af.gif',
        							 ' 8)'    => 'af.gif',
        							 'B)'    => 'af.gif',

        							 ':-D'   => 'ag.gif',
        							 ':D'    => 'ag.gif',
        							 '+D'    => 'ag.gif',
        							 '=D'    => 'ag.gif',

        							 ':-['   => 'ah.gif',
        							 ':['    => 'ah.gif',
//        							 ';\'>'  => 'ah.gif',
//        							 ';-.'   => 'ah.gif',

        							 '=-O'   => 'ai.gif',
        							 '=O'    => 'ai.gif',
        							 '=-o'   => 'ai.gif',
        							 '=0'    => 'ai.gif',
//        							 'O_O'   => 'ai.gif',
//        							 'O_o'   => 'ai.gif',
//        							 'o_O'   => 'ai.gif',
//        							 'O_0'   => 'ai.gif',
//        							 'o_0'   => 'ai.gif',
//        							 '0_O'   => 'ai.gif',
//        							 '0_o'   => 'ai.gif',

        							 ':-*'   => 'aj.gif',
        							 ':*'    => 'aj.gif',
//        							 ':-{}'  => 'aj.gif',
//        							 ':{}'   => 'aj.gif',
//        							 '+{}'   => 'aj.gif',
//        							 '={}'   => 'aj.gif',
//        							 '^.^'   => 'aj.gif',

        							 ':\'('  => 'ak.gif',
        							 ':-\'(' => 'ak.gif',

        							 ':-X'   => 'al.gif',
        							 ':-x'   => 'al.gif',
//        							 'X:'    => 'al.gif',
//        							 'x:'    => 'al.gif',
//        							 ':-#'   => 'al.gif',
//        							 ':#'    => 'al.gif',

        							 '>:o'   => 'am.gif',
        							 '>:O'   => 'am.gif',
//        							 '>+O'   => 'am.gif',
//        							 '>:o'   => 'am.gif',
//        							 '>+o'   => 'am.gif',
//        							 ':-@'   => 'am.gif',

        							 ':-|'   => 'an.gif',
        							 ':|'    => 'an.gif',
//        							 '=|'    => 'an.gif',

        							 ':-\\'  => 'ao.gif',
        							 ':-/'   => 'ao.gif',
        							 ':\\'   => 'ao.gif',
        							 '*BEEE*'=> 'ao.gif',

        							 '*JOKINGLY*' => 'ap.gif',
//        							 '8P'    => 'ap.gif',
//        							 '8p'    => 'ap.gif',

        							 ']:->'  => 'aq.gif',
        							 '}:->'  => 'aq.gif',
        							 ']:>'   => 'aq.gif',
        							 '}:>'   => 'aq.gif',
//        							 '>:-]'  => 'aq.gif',
//        							 '>:]'   => 'aq.gif',
//        							 '*DIABLO*' => 'aq.gif',

        							 '[:-}'  => 'ar.gif',
        							 '[:}'   => 'ar.gif',

        							 '*KISSED*' => 'as.gif',

        							 ':-!'   => 'at.gif',
        							 ';-!'   => 'at.gif',
        							 ':!'    => 'at.gif',
        							 ';!'    => 'at.gif',
//        							 ':-~'   => 'at.gif',
//        							 ';-~'   => 'at.gif',

        							 '*TIRED*' => 'au.gif',
        							 '|-0'   => 'au.gif',

        							 '*STOP*'=> 'av.gif',

        							 '*KISSING*' => 'aw.gif',

        							 '@}->--'    => 'ax.gif',
        							 '@}-:--'    => 'ax.gif',
//        							 '@>}--`---' => 'ax.gif',

        							 '*THUMBS UP*' => 'ay.gif',
        							 '*GOOD*'      => 'ay.gif',

        							 '*DRINK*'    => 'az.gif',

        							 '*IN LOVE*'  => 'ba.gif',

        							 '@='         => 'bb.gif',

        							 '*HELP*'     => 'bc.gif',

        							 '\m/'        => 'bd.gif',

        							 '%)'    => 'be.gif',
        							 '%-)'   => 'be.gif',
        							 ':$'    => 'be.gif',

        							 '*OK*'  => 'bf.gif',

        							 '*WASSUP*' => 'bg.gif',
        							 '*SUP*'    => 'bg.gif',

        							 '*SORRY*'    => 'bh.gif',

        							 '*BRAVO*'    => 'bi.gif',

        							 '*ROFL*'     => 'bj.gif',

        							 '*PARDON*'   => 'bk.gif',
        							 '=]'         => 'bk.gif',

        							 '*NO*'       => 'bl.gif',

        							 '*CRAZY*'    => 'bm.gif',

        							 '*DONT_KNOW*' => 'bn.gif',
        							 '*UNKNOWN*'   => 'bn.gif',

        							 '*DANCE*'    => 'bo.gif',

        							 '*YAHOO*'    => 'bp.gif',
//        							 '*YAHOO!*'    => 'bp.gif',

        							 ';D'         => 'bq.gif',
        							 '*ACUTE*'    => 'bq.gif',

        							 '*BB*'    => 'br.gif',
        							 '*BYE*'    => 'bs.gif',
        							 '*HI*'    => 'bt.gif',
        							 '*HAPPY*'    => 'bu.gif',
        							 '*LOL*'    => 'bv.gif',
        							 '*SCRATCH*'    => 'bw.gif',
        							 '*YEEES!*'    => 'bx.gif',
        							 '*SMOKE*'    => 'by.gif',
        							 '*BOSS*'    => 'bz.gif',
        							 '*SARCASTIC*'    => 'ca.gif',
        							 '*BOAST*'    => 'cb.gif',
        							 '*db*'    => 'cd.gif',

        							 '*HOHO*'    => 'ce.gif',
        							 '*SHOUT*'    => 'cf.gif',
        							 '*VAVA*'    => 'cg.gif',
        							 '*CENSORED*'    => 'ch.gif',
        							 '*SEARCH*'    => 'ci.gif',
        							 '*BEACH*'    => 'cj.gif',
        							 '*FOCUS*'    => 'ck.gif',
        							 '*HUNTER*'    => 'cl.gif',

        							 '*GIRL_CRY*'    => 'cm.gif',
        							 '*GIRL_CRAY*'    => 'cm.gif',

        							 '*GIRL_CRAZY*'    => 'cn.gif',
        							 '*HOSPITAL*'    => 'co.gif',
        							 '*GIRL_IN_LOVE*'    => 'cp.gif',
        							 '*PINKGLASSES*'    => 'cq.gif',
        							 '*HYSTERIC*'    => 'cr.gif',
        							 '*TENDER*'    => 'cs.gif',
        							 '*SPRUSE_UP*'    => 'ct.gif',
        							 '*FLIRT*'    => 'cu.gif',
        							 '*GIVE_HEART*'    => 'cv.gif',
        							 '*CURTSEY*'    => 'cw.gif',
        							 '*FEMINIST*'    => 'cx.gif',
        							 '*GIRL_DRINK*'    => 'cy.gif',

        							 '*HAHA*'    => 'cz.gif',
        							 '*IMPOSSIBLE*'    => 'da.gif',
        							 '*SIGH*'    => 'db.gif',

        							 'X-)'    => 'dc.gif',
        							 'X)'    => 'dc.gif',

        							 '*SLOW*'    => 'dd.gif',

        							 '*MOIL*'    => 'de.gif',
        							 '*JOB*'    => 'de.gif',

        							 '*YES*'    => 'df.gif',
        							 '*MEGA_SHOK*'    => 'dg.gif',
        							 '*THANK*'    => 'dh.gif',
        							 '*KING*'    => 'di.gif',
        							 '*LAZY*'    => 'dj.gif',
        							 '*FRIEND*'    => 'dk.gif',
        							 '*PUNISH*'    => 'dl.gif',
        							 '*WIZARD*'    => 'dm.gif',
        							 '*V*'    => 'dn.gif',
        							 '*SPITEFUL*'    => 'do.gif',
        							 '*TEASE*'    => 'dp.gif',
        							 '*SCARE*'    => 'dr.gif',
        							 '*THIS*'    => 'ds.gif',
        							 '*PAINT*'    => 'dt.gif',
        							 '*TRAINING*'    => 'du.gif',
        							 '*PARTY*'    => 'dv.gif',

        							 'гафно'    => 'censor.gif',
        							 'гавно'    => 'censor.gif',
        							 'говно'    => 'censor.gif',
        							 'сраная'    => 'censor.gif',
        							 'дерьмо'    => 'censor.gif',
        							 'дебилы'    => 'censor.gif',
        							 'отстой'    => 'censor.gif',
        							 'нахуя'    => 'censor.gif',
        							 'херню'    => 'censor.gif',

        							 'кале '    => 'censor.gif',
        							 );

	foreach ($replace_arr as $s=>$r)
	  {
	  $string = str_replace($s, '<img src="/files/shared/images/smiles/basic/'.$r.'" alt="'.$s.'" border="0">', $string);
	  }

	return $string;
	}



?>
