<?php
header('Content-type: text/css');
header("Cache-Control: must-revalidate");
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 1209600) . ' GMT');
$hb=get_option('imdbheadbg','FFCC00');
$hf=get_option('imdbheadfg','FFFFFF');
$bb=get_option('imdbbodybg','F4F3D9');
$bf=get_option('imdbbodyfg','333333');
$ic=get_option('imdbcorner',5);
echo '#imdbinfo {border-collapse: separate !important;clear:both;border:solid 1px #'.$hb.';background:#'.$bb.';border-radius:'.$ic.'px}
#imdbinfo th{line-height:1.3em;font-size:1.2em;text-align:center;color:#'.$hf.';background:#'.$hb.';padding:2px;border-radius:'.$ic.'px}
#imdbinfo td{vertical-align:top;text-align:justify;padding:5px;color:#'.$bf.';}
#imdbimg{width:160px;height:225px;text-align:center}
#imdbimg img{max-width:100%;max-height:100%;border:solid thick #999999;}';
?>