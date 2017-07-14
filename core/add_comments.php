<?

require_once("../admin/conf.php");
require_once("jshttprequest.php");
$JsHttpRequest=new JsHttpRequest("utf-8");
$host=parse_url(@$_SERVER['HTTP_REFERER']); if(@$host['host']!=@$_SERVER['HTTP_HOST'])die();
$frm="<form method=\"post\" style=\"border:1px #FFCC33 solid;padding:10px\" onsubmit=\"return false\" enctype=\"multipart/form-data\"><input style=\"color:#999999\" size=\"30\" name=\"autor\" id=\"autor\" type=\"text\" value=\"\" /> - ".$lang[505]."<br /><textarea style=\"color:#999999\" rows=\"7\" cols=\"40\" name=\"text\" id=\"text\"></textarea> - ".$lang[506]."<br /><a href=\"#\" class=\"large b\" onclick=\"add_comments('".@$_REQUEST['idmess']."',$('autor').value,$('text').value);return false;\">".$lang[199]."</a></form>";
if (ctype_digit(@$_REQUEST['idmess'])>0){
	if(@$_REQUEST['send_autor']!="0" && @$_REQUEST['send_text']!="0" && @$_REQUEST['send_autor']!="" && @$_REQUEST['send_text']!="")	{
		if ($c['edit_comments']=="yes")$moder="new"; else $moder="old";
		$_REQUEST['send_text']=utf8_substr($_REQUEST['send_text'],0,22255);
		if (mysql_query("INSERT jb_comments SET id_board='".$_REQUEST['idmess']."',autor='".clean($_REQUEST['send_autor'])."',text='".clean($_REQUEST['send_text'])."', old_mess='".$moder."', date=NOW()")){
			if(file_exists("../cache/mess_".$_REQUEST['idmess'].JBLANG))unlink("../cache/mess_".$_REQUEST['idmess'].JBLANG);
			$GLOBALS['_RESULT']="<span class=\"b red\">".$lang[571]."</span>";
		}else $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[98]." ".$lang[188]." ".$lang[189]."</span>";
	}else $GLOBALS['_RESULT']=$frm;
}else $GLOBALS['_RESULT']="<br /><span class=\"red large b\">".$lang[98]."</span>";
?>
