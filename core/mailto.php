<?
require_once("../admin/conf.php");
require_once("jshttprequest.php");
$JsHttpRequest=new JsHttpRequest("utf-8");
$host=parse_url(@$_SERVER['HTTP_REFERER']); if(@$host['host']!=@$_SERVER['HTTP_HOST'])die();
$formcode="<br /><br /><form method=\"post\" style=\"border:1px #FFCC33 solid;padding:10px\" onsubmit=\"return false\" enctype=\"multipart/form-data\">".$lang[196]."<br /><input size=\"48\" id=\"send_email\" name=\"send_email\" type=\"text\"><br /><br />".$lang[198]."<br /><textarea cols=\"37\" rows=\"4\" id=\"send_text\" name=\"send_text\"></textarea><br /><br />".$lang[203]." (<a href=\"#\" onclick=\"document.getElementById('hello_bot').src='code.gif?'+Math.random();return false;\">".$lang[2031]."</a>)<br /><br /><img alt=\"".$lang[203]."\" id=\"hello_bot\" src=\"code.gif?".microtime()."\" /><br /><input id=\"securityCode\" type=\"text\" name=\"securityCode\" size=\"25\"><br /><br /><a href=\"#\" class=\"large b\" onclick=\"sendFormMailToUser($('send_email').value, $('send_text').value, $('securityCode').value, ".$_REQUEST['idmess'].");return false;\">".$lang[199]."</a></form><br /><br />";
if (ctype_digit(@$_REQUEST['idmess'])>0){
	if (@$_REQUEST['send_text']){
		if (@$_REQUEST['securityCode'] && trim(@$_REQUEST['send_email'])!="" && trim(@$_REQUEST['send_text'])!=""){
			if(@$_SESSION['securityCode'] && utf8_strtolower($_POST['securityCode'])==utf8_strtolower($_SESSION['securityCode'])){
				$email=trim($_REQUEST['send_email']);
				if (!preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/iu',$email)){$GLOBALS['_RESULT']="<br /><span class=\"red large b\">".$lang[185]."</span>".$formcode;die();}
				$query = mysql_query("SELECT email FROM jb_board WHERE id='".$_REQUEST['idmess']."'");
				$dmail=mysql_fetch_assoc($query);
				if(@sendmailer($dmail['email'],$email,$lang[1012],$_REQUEST['send_text'])){$GLOBALS['_RESULT']="<br /><span class=\"red b\">".$lang[186]."</span>";die();}
				else{$GLOBALS['_RESULT']="<br /><span class=\"red large b\">".$lang[187]." ".$lang[188]." ".$lang[189]."</span>".$formcode;die();}
				$_SESSION['securityCode']=md5($_POST['send_text']);
			} else $GLOBALS['_RESULT']="<br /><span class=\"red large b\">".$lang[116]."</span>".$formcode;
		} else $GLOBALS['_RESULT']="<br /><span class=\"red large b\">".$lang[255]."</span>".$formcode;
	} else $GLOBALS['_RESULT']=$formcode;
} else $GLOBALS['_RESULT']="<br /><span class=\"red large b\">".$lang[581]."</span>";
?>
