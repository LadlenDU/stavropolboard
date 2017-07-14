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
$GLOBALS['_RESULT']="";
if(ctype_digit(@$_REQUEST['r_n']) && @$_REQUEST['r_n']>0 && @$_REQUEST['r_n']<25 && ctype_digit(@$_REQUEST['r_c'])>=0 && ctype_digit(@$_REQUEST['r_r'])>=0){
	$data=file_get_contents($h."export.php?t=php&n=".$_REQUEST['r_n']."&c=".$_REQUEST['r_c']."&r=".$_REQUEST['r_r']);
	$GLOBALS['_RESULT'] .= "<div class=\"alcenter clear\"><h3>".$lang[658]."</h3></div>";
	$GLOBALS['_RESULT'] .= $data;
} else $GLOBALS['_RESULT']=$lang[1032];
?>
