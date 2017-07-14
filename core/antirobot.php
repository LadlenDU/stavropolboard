<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

$wdth=160;
$hght=60;
$cch=4;
$font='../images/bent_titul.ttf';
$alpha_bg=120;
$fontsize=8;
$let=array('1','2','3','4','5','6','7','8','9','0');
session_start();
$host=parse_url(@$_SERVER['HTTP_REFERER']);
if(@$host['host']!=@$_SERVER['HTTP_HOST']){header('https/1.0 404 Not Found');die();}
$fntsz=intval($hght/(($hght/$wdth)*$fontsize));$cc=array();
$src=imagecreatetruecolor($wdth,$hght);$fon=imagecolorallocate($src,255,255,255);imagefill($src,0,0,$fon);
for($i=0;$i<$cch;$i++){$hw=1;$color=imagecolorallocatealpha($src,0,0,0,75);$letter=$let[rand(0,sizeof($let)-1)];
	$size=rand($fntsz*2.1-1,$fntsz*2.1+1);
	$x=(empty($x)) ? $wdth*0.08 : $x+($wdth*0.8)/$cch+rand(0,$wdth*0.01);
	$y=($hw==rand(1,2))?(($hght*1.15*3)/4)+ rand(0,$hght*0.02):(($hght*1.15*3)/4)- rand(0,$hght*0.02);
	$angle=rand(20,40);$cc[]=$letter;
	if($hw==rand(1,2))$angle=rand(355,340);imagettftext($src,$size,$angle,$x,$y,$color,$font,$letter);
}
$_SESSION['securityCode']=implode('',$cc);$timemodified=time();
header('ETag: "'.md5($timemodified).'"');
header('Last-Modified: '.gmdate("D, d M Y H:i:s",$timemodified).' GMT');
header('Expires: Fri, 22 May 2009 14:37:18 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header("Content-type: image/gif");
imagejpeg($src);imagedestroy($src);
?>