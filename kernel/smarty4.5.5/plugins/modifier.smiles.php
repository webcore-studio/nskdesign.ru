<?php
function smarty_modifier_smiles($string)
  {
  $smiles = array(':D '       => '/files/shared/images/smiles/icon_biggrin.gif',
                  ':-D '      => '/files/shared/images/smiles/003.gif',
                  ':) '       =>  '/files/shared/images/smiles/icon_smile.gif',
                  ':-) '      => '/files/shared/images/smiles/001.gif',

                  ':( '       =>  '/files/shared/images/smiles/icon_sad.gif',
                  ':o '       =>  '/files/shared/images/smiles/icon_surprised.gif',
                  ':shock: '  =>  '/files/shared/images/smiles/icon_eek.gif',
                  ':? '       =>  '/files/shared/images/smiles/icon_confused.gif',

                  '8) '       =>  '/files/shared/images/smiles/icon_cool.gif',
                  ':lol: '    =>  '/files/shared/images/smiles/icon_lol.gif',
                  ':x '       =>  '/files/shared/images/smiles/icon_mad.gif',
                  ':P '       =>  '/files/shared/images/smiles/icon_razz.gif',

                  ':oops: '   =>  '/files/shared/images/smiles/icon_redface.gif',
                  ':cry: '    =>  '/files/shared/images/smiles/icon_cry.gif',
                  ':evil: '   =>  '/files/shared/images/smiles/icon_evil.gif',
                  ':twisted: '=>  '/files/shared/images/smiles/icon_twisted.gif',

                  ':roll: '   =>  '/files/shared/images/smiles/icon_rolleyes.gif',
                  ':wink: '   =>  '/files/shared/images/smiles/icon_wink.gif',
                  ';-) '      =>  '/files/shared/images/smiles/002.gif',
                  ':!: '      =>  '/files/shared/images/smiles/icon_exclaim.gif'
                 );
  foreach ($smiles as $smile=>$img_url)
    $string = str_replace ($smile, '<img src="'.$img_url.'" alt="" border="0"> ', $string);
  return $string;
  }
?>
