<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

define('SITE',true);
include("../admin/conf.php");
if($_GET['k']!=md5($c['admin_mail']))die();
$all=mysql_query("SELECT * FROM jb_subscribe LIMIT ".$c['subscribe_limit']);cq();
$countmail=0;
while($d=mysql_fetch_assoc($all)){
	$subject=$c['subscribe_theme'];
	$subject=str_replace("[HOST]",$h,$subject);
	$subject=str_replace("[REGISTER_PAGE]",$h."register.html",$subject);
	$subject=str_replace("[USER_NAME]",$d['username'],$subject);
	$subject=str_replace("[USER_ADS]",$h."c".$d['id_cat']."-".$d['id_board'].".html",$subject);
	$msg=$c['subscribe_text'];
	$msg=str_replace("[HOST]",$h,$msg);
	$msg=str_replace("[REGISTER_PAGE]",$h."register.html",$msg);
	$msg=str_replace("[USER_NAME]",$d['username'],$msg);
	$msg=str_replace("[USER_ADS]",$h."c".$d['id_cat']."-".$d['id_board'].".html",$msg);
	$from=(@$c['subscribe_from']!="")?$c['subscribe_from']:$c['admin_mail'];
	if(sendmailer($d['mail'],$from,$subject,$msg)){
		mysql_query("DELETE FROM jb_subscribe WHERE id='".$d['id']."' LIMIT 1");$countmail++;sleep($c['subscribe_sleep']);
	}
}
echo $lang[1074].": ".$countmail;
?>