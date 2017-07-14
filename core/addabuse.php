<?
require_once("../admin/conf.php");
require_once("jshttprequest.php");
$JsHttpRequest=new JsHttpRequest("utf-8");
$host=parse_url(@$_SERVER['HTTP_REFERER']); if(@$host['host']!=@$_SERVER['HTTP_HOST'])die();
if (ctype_digit(@$_REQUEST['idmess'])>0){
	if (@$_REQUEST['send_type']!="0"){
		if (mysql_query("INSERT jb_abuse SET id_board='".$_REQUEST['idmess']."',type_abuse='".clean($_REQUEST['send_type'])."'")) $GLOBALS['_RESULT']="<span class=\"b red\">".$lang[497]."</span>";		
		else $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[98]." ".$lang[188]." ".$lang[189]."</span>";
	}else{
		$GLOBALS['_RESULT']="<br /><br /><form method=\"post\" style=\"border:1px #FFCC33 solid;padding:10px\" onsubmit=\"return false\" enctype=\"multipart/form-data\"><select name=\"typeAbuse\" id=\"typeAbuse\">";
		foreach ($abuseType as $k=>$v) $GLOBALS['_RESULT'].="<option value=\"".htmlspecialchars($v)."\">".htmlspecialchars($v)."</option>";
		$GLOBALS['_RESULT'].="</select> <a href=\"#\" class=\"large b\" onclick=\"addabuse($('typeAbuse').value,'".$_REQUEST['idmess']."');return false;\">".$lang[199]."</a></form><br /><br />";
	}
}
else $GLOBALS['_RESULT']="<br /><span class=\"red large b\">".$lang[98]."</span>";
?>
