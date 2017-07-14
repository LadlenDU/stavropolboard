<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

require_once("../../admin/conf.php");
require_once("../../core/jshttprequest.php");
$JsHttpRequest=new JsHttpRequest("utf-8");
$host=parse_url(@$_SERVER['HTTP_REFERER']);if(@$host['host']!=@$_SERVER['HTTP_HOST'])die();
if (@$_REQUEST['r_n']=="new"){
	$r_info="";
	$new_ads=mysql_query("SELECT id FROM jb_board WHERE old_mess!='old' OR checked!='yes'");
	$count_new_ads=mysql_num_rows($new_ads);
	if(@$count_new_ads)$r_info.="<a href=\"".$h."a/?action=ads&op=new\">".$lang[600].": ".$count_new_ads."</a><br />";
	$new_comments=mysql_query("SELECT id FROM jb_comments WHERE old_mess!='old' OR checked!='yes'");
	$count_new_comments=mysql_num_rows($new_comments);
	if(@$count_new_comments)$r_info.="<a href=\"".$h."a/?action=comments&op=new\">".$lang[666].": ".$count_new_comments."</a><br />";
	$new_abuse=mysql_query("SELECT id_board FROM jb_abuse");
	$count_new_abuse=mysql_num_rows($new_abuse);
	if(@$count_new_abuse)$r_info.="<a href=\"".$h."a/?action=abuse&op=new\">".$lang[667].": ".$count_new_abuse."</a><br />";
	$new_news=mysql_query("SELECT id FROM jb_news WHERE old_mess!='old'");
	$count_new_news=mysql_num_rows($new_news);
	if(@$count_new_news)$r_info.="<a href=\"".$h."a/?action=news&op=new\">".$lang[1051].": ".$count_new_news."</a><br />";
}else $r_info="<br /><span class=\"red large b\">".$lang[98]."</span>";
if($r_info=="")$GLOBALS['_RESULT']=$lang[1052]." :(";else $GLOBALS['_RESULT']=$r_info;
?>