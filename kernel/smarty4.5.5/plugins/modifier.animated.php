<?php
function smarty_modifier_animated($string)
  {
  $smiles = array(':D '       		=> '/files/shared/images/smiles/animated/ag.gif',
                  ':) '       		=>  '/files/shared/images/smiles/animated/ab.gif',
                  ':( '       		=>  '/files/shared/images/smiles/animated/ac.gif',
				  ':lol: '    		=>  '/files/shared/images/smiles/animated/bj.gif',
				  ':cry: '       		=>  '/files/shared/images/smiles/animated/ak.gif',
                  ':yes: '    		=>  '/files/shared/images/smiles/animated/bs.gif',
                  ':shock: '  		=>  '/files/shared/images/smiles/animated/ai.gif',
                  ':hi: '     		=>  '/files/shared/images/smiles/animated/bq.gif',
                  '8) '       		=>  '/files/shared/images/smiles/animated/af.gif',
                  ';) '       		=>  '/files/shared/images/smiles/animated/ad.gif',
                  ':kiss: '   		=>  '/files/shared/images/smiles/animated/aj.gif',
                  ':thumbs_up: ' 	=>  '/files/shared/images/smiles/animated/ay.gif',
                  ':crazy: '    	=>  '/files/shared/images/smiles/animated/bm.gif',
                  ':evil: '   		=>  '/files/shared/images/smiles/animated/aq.gif',
                  ':oops: '			=>  '/files/shared/images/smiles/animated/ah.gif',
                  ':P '   			=>  '/files/shared/images/smiles/animated/ae.gif',
                  ':wall: '   		=>  '/files/shared/images/smiles/animated/bu.gif',
                  ':no: '     		=>  '/files/shared/images/smiles/animated/bl.gif',
                  ':pardon: '      	=>  '/files/shared/images/smiles/animated/bk.gif',
				  ':bravo: '      	=>  '/files/shared/images/smiles/animated/bi.gif',
                 );
  foreach ($smiles as $smile=>$img_url)
    $string = str_replace ($smile, '<img src="'.$img_url.'" alt="" border="0"> ', $string);
  return $string;
  }
?>
