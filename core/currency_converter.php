<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

require_once("../admin/conf.php");
require_once("jshttprequest.php");
$JsHttpRequest=new JsHttpRequest("utf-8");
$host=parse_url(@$_SERVER['HTTP_REFERER']); if(@$host['host']!=@$_SERVER['HTTP_HOST'])die();
if(ctype_digit(@$_REQUEST['sum'])>0 && ($_REQUEST['type']=="rub" || $_REQUEST['type']=="eur" || $_REQUEST['type']=="uah")){
	$url="https://www.google.com/search?q=".$_REQUEST['sum']."+usd+in+".$_REQUEST['type'];
	$cinit=curl_init();
	curl_setopt($cinit,CURLOPT_URL,$url);
	curl_setopt($cinit,CURLOPT_HEADER,0);
	curl_setopt($cinit,CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
	curl_setopt($cinit,CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($cinit,CURLOPT_RETURNTRANSFER,1);
	$text=curl_exec($cinit);
	curl_close($cinit);
	$pos_begin=strpos($text,'<img src=/images/calc_img.gif width=40 height=30 alt="">'); 
	$text=substr($text,$pos_begin);$pos_end=strpos($text,"Rates provided for information only");
	$text=substr($text,0,$pos_end-1);$text=strip_tags($text);
	$GLOBALS['_RESULT']="<span class=\"red b\">".$text."</span>";
}else $GLOBALS['_RESULT']=$lang[98];
?>
