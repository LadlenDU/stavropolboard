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
$frm="<form method=\"post\" style=\"border:1px #FFCC33 solid;padding:10px\" onsubmit=\"return false\" enctype=\"multipart/form-data\"><input style=\"color:#999999\" maxlength=64 name=\"from\" id=\"from\" type=\"text\" value=\"\" /> - ".$lang[196]."<br /><input style=\"color:#999999\" maxlength=64 name=\"to\" id=\"to\" type=\"text\" value=\"\" /> - ".$lang[540]."<br /><a href=\"#\" class=\"large b\" onclick=\"mail_friends($('to').value,$('from').value,'".@$_REQUEST['idcat']."','".@$_REQUEST['idmess']."');return false;\">".$lang[199]."</a></form>";
if (ctype_digit(@$_REQUEST['idcat'])>0 && ctype_digit(@$_REQUEST['idmess'])>0){
	if (@$_REQUEST['send_to']!="0" && @$_REQUEST['send_from']!="0"){
		if (!preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/iu',$_REQUEST['send_to'])) $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[575]."</span><br />".$frm;
		else{
			if (!preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/iu',$_REQUEST['send_from'])) $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[575]."</span><br />".$frm;
			else{
				$msg=$lang[578]." ".$h." ".$lang[579].": ".$h."c".$_REQUEST['idcat']."-".$_REQUEST['idmess'].".html";
				if(sendmailer($_REQUEST['send_to'],"<".$_REQUEST['send_from'].">",$lang[577],$msg)) $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[580]."</span>";
				else $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[581]."</span>";
			}
		}
	} else $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[255]."</span><br />".$frm;
} else $GLOBALS['_RESULT']="<br /><span class=\"red b\">".$lang[98]."</span>";
?>
