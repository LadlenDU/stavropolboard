<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

require_once("../admin/conf.php");
if (@$_GET['l']){
	if (setcookie('jbnocache','1',time()+60,"/")){
		if (@$_GET['l']=="ru") setcookie('jblang','ru',time()+77760000,"/");
		else setcookie ('jblang','en',time()+77760000,"/");
		$host = parse_url(@$_SERVER['HTTP_REFERER']);
		if(@$host['host']==@$_SERVER['HTTP_HOST']) header("location: ".$_SERVER['HTTP_REFERER']);
		else header("location: ".$h);
	} else die("Error. Cookie dont work.");
}else {header('https/1.0 404 Not Found');die();}
?>