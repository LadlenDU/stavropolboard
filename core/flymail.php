<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

$host=parse_url(@$_SERVER['HTTP_REFERER']);
if(@$host['host']!=@$_SERVER['HTTP_HOST']){header('https/1.0 404 Not Found');die();}
require_once("../admin/conf.php");
if(ctype_digit(@$_GET['id_mess'])){
	$query_email=mysql_query("SELECT email FROM jb_board WHERE id=".$_GET['id_mess']." LIMIT 1");
	if(mysql_num_rows($query_email)){
		$m=mysql_fetch_assoc($query_email);
		if($m['email']!=""){
			$png=imagecreate(560,20);
			$bgc=imagecolorallocate($png,255,255,255);
			$textc=imagecolorallocate($png,0,0,0);
			imagestring($png,3,2,2,$m['email'],$textc);
			header("content-type: image/png");
			imagegif($png);
}}} else {header('https/1.0 404 Not Found');die();}
?>