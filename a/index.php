<?
define('SITE',true);
include("../admin/conf.php");
$admhead="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"https://www.w3.org/1999/xhtml\"><head><title>".$c['user_title']."</title><meta https-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><link type=\"ico\" rel=\"shortcut icon\" href=\"".$im."favicon.ico\" /><script type=\"text/javascript\">var servername='".$h."';</script>".$stylecss.$mainjs."<script type=\"text/javascript\" src=\"".$im."admmain.js\"></script><script type=\"text/javascript\">var confirmmess='".$lang[172]."';</script></head><body>";
$admin_login_form="<center><form method=post><br /><br /><br /><br /><br /><br /><table width=\"35%\" align=\"center\" cellpadding=\"10\" bgcolor=\"#EEEEEE\"><tr><td colspan=\"2\" align=\"center\"><br /><strong>".$lang[209]."</strong><br /><br /></td></tr><tr><td align=\"right\" width=\"40%\">".$lang[13]."</td><td align=\"left\" width=\"60%\"><input type=\"text\" name=\"login\"></td></tr><tr><td align=\"right\">".$lang[14]."</td><td align=\"left\"><input type=\"password\" name=\"password\"></td></tr><tr><td colspan=\"2\" align=\"center\"><br /><input type=\"submit\" value=\"".$lang[59]."\"><br /><br /></td></tr></table></form><br /><br /><a href=\"".$h."\">".$lang[84]."</a></center>";
$admmenu="<table width=\"100%\" cellspacing=\"10\"><tr><td colspan=\"2\" align=\"center\"><h1 class=\"orange\">".$lang[209]."</h1><br /></td></tr><tr><td style=\"white-space:nowrap; padding:5px; line-height:25px; background-color:#FFFFE8;\"><div class=\"admlink\"><img class=\"absmid\" src=\"".$im."ahome.png\" /> <a href=\"".$h."a/\">".$lang[1]."</a><br /><img class=\"absmid\" src=\"".$im."aboard.png\" /> <a target=\"_blank\" href=\"".$h."\">".$lang[84]."</a><br /><img class=\"absmid\" src=\"".$im."aconf.png\" /> <a href=\"".$h."a/?action=setting\">".$lang[2]."</a><br /><img class=\"absmid\" src=\"".$im."aprofile.png\" /> <a href=\"".$h."a/?action=profile\">".$lang[3]."</a><br /><img class=\"absmid\" src=\"".$im."alogout.png\" /> <a href=\"".$h."a/?action=logout\">".$lang[4]."</a><br /></div><div class=\"selectlang\"> <a href=\"".$h."ru.html\" title=\"".$lang[1001]."\"><img class=\"absmid\" alt=\"".$lang[1001]."\" src=\"".$im."ru.gif\" /></a> <a title=\"".$lang[1002]."\" href=\"".$h."en.html\"><img class=\"absmid\" alt=\"".$lang[1002]."\" src=\"".$im."en.gif\" /></a></div></td><td width=\"100%\"><div class=\"admmenu\" align=\"center\"><table width=\"100%\"><tr><td colspan=\"2\" class=\"whitebg\"><div id=\"new\"><a onclick=\"get_new_info('new');return false\" href=\"#\" class=\"red b\">".$lang[1042]."</a></div></td><td width=\"10%\" align=\"center\"><a href=\"".$h."a/?action=ads\"><img class=\"absmid\" src=\"".$im."aads.png\" /><br /><br />".$lang[263]."</a> <a href=\"".$h."a/?action=ads&op=add_mess\"><img alt=\"".$lang[155]."\" title=\"".$lang[155]."\" class=\"absmid\" src=\"".$im."new.gif\" /></a></td><td width=\"10%\" align=\"center\"><a href=\"".$h."a/?action=category\"><img class=\"absmid\" src=\"".$im."acats.png\" /><br /><br />".$lang[598]."</a></td><td width=\"10%\" align=\"center\"><a href=\"".$h."a/?action=city\"><img class=\"absmid\" src=\"".$im."acity.png\" /><br /><br />".$lang[230]."</a></td></tr><tr><td width=\"10%\" align=\"center\"><a href=\"".$h."a/?action=content\"><img class=\"absmid\" src=\"".$im."acontent.png\" /><br /><br />".$lang[664]."</a> <a href=\"".$h."a/?action=content&op=add\"><img alt=\"".$lang[154]."\" title=\"".$lang[154]."\" class=\"absmid\" src=\"".$im."new.gif\" /></a></td><td width=\"10%\" align=\"center\"><a href=\"".$h."a/?action=news\"><img class=\"absmid\" src=\"".$im."anews.png\" /><br /><br />".$lang[286]."</a> <a href=\"".$h."a/?action=news&op=add\"><img alt=\"".$lang[154]."\" title=\"".$lang[154]."\" class=\"absmid\" src=\"".$im."new.gif\" /></a></td><td width=\"10%\" align=\"center\"><a href=\"".$h."a/?action=comments\"><img class=\"absmid\" src=\"".$im."acomment.png\" /><br /><br />".$lang[423]."</a></td><td width=\"10%\" align=\"center\"><a href=\"".$h."a/?action=abuse\"><img class=\"absmid\" src=\"".$im."aabuse.png\" /><br />".$lang[667]."</a></td><td width=\"10%\" align=\"center\"><a href=\"".$h."a/?action=ads_search\"><img class=\"absmid\" src=\"".$im."aadssearch.png\" /><br /><br />".$lang[820]."</a></td></tr><tr><td width=\"10%\" align=\"center\"><a href=\"".$h."a/?action=money_stats\"><img class=\"absmid\" src=\"".$im."amoney_stats.png\" /><br />".$lang[439]."</a></td><td width=\"10%\" align=\"center\"><a href=\"".$h."a/?action=stat\"><img class=\"absmid\" src=\"".$im."astat.png\" /><br /><br />".$lang[143]."</a></td><td width=\"10%\" align=\"center\"><a href=\"".$h."a/?action=maintenance\"><img class=\"absmid\" src=\"".$im."acron.png\" /><br /><br />".$lang[1079]."</a></td><td width=\"10%\" align=\"center\"><a href=\"".$h."a/?action=subscribe\"><img class=\"absmid\" src=\"".$im."anotification.png\" /><br /><br />".$lang[440]."</a></td><td width=\"10%\" align=\"center\"><a href=\"".$h."a/?action=sendmail\"><img class=\"absmid\" src=\"".$im."asendmail.png\" /><br /><br />".$lang[1076]."</a></td></tr></table></div></td></tr></table><br /><br /><br />";
if(@$_POST['login']&& @$_POST['password']&& !@$_SESSION['login']&& !@$_SESSION['password']){
	$adm=mysql_query("SELECT * FROM jb_admin"); cq(); 
	$admdata=mysql_fetch_assoc($adm);
	if($_POST['login']==$admdata['login']&& md5($_POST['password'])==$admdata['password']){
		$_SESSION['login']=$_POST['login'];$_SESSION['password']=$_POST['password'];
	}else echo $lang[0];
}
if(@$_SESSION['login']&& @$_SESSION['password']){
	$admins=mysql_query("SELECT * FROM jb_admin");cq();
	$adminsdata=mysql_fetch_assoc($admins);
	if($_SESSION['login']==$adminsdata['login']&& md5($_SESSION['password'])==$adminsdata['password']){
		echo $admhead.$admmenu;
		if(isset($_GET['action'])){
			if($_GET['action']=="ads")require_once("../admin/sad.php");
			if($_GET['action']=="category")require_once("../admin/scategory.php");
			if($_GET['action']=="city")require_once("../admin/scity.php");
			if($_GET['action']=="content")require_once("../admin/scontent.php");
			if($_GET['action']=="news")require_once("../admin/snews.php");
			if($_GET['action']=="comments")require_once("../admin/scomments.php");
			if($_GET['action']=="abuse")require_once("../admin/sabuse.php");
			if($_GET['action']=="ads_search")require_once("../admin/sads_search.php");
			if($_GET['action']=="money_stats")require_once("../admin/smoney_stats.php");
			if($_GET['action']=="subscribe")require_once("../admin/ssubscribe.php");
			if($_GET['action']=="sendmail")require_once("../admin/ssendmail.php");
			if($_GET['action']=="stat")require_once("../admin/sstat.php");
			if($_GET['action']=="maintenance")require_once("../admin/smaintenance.php");
			if($_GET['action']=="profile")require_once("../admin/sprofile.php");
			if($_GET['action']=="setting")require_once("../admin/ssetting.php");
		}
		echo "<br /><br /><br /><center><span class=\"sm lgray\">Запросов к SQL-серверу: <strong>".$GLOBALS['cq']."</strong> &nbsp; &nbsp; &nbsp; &nbsp; Сервер сгенерировал страницу за <strong>".utf8_substr(gentime(),0,6)." сек.</strong></span></center><br /><br /></body></html>";
	}else echo $lang[0];
}else echo $admhead.$admin_login_form;
if(@$_GET['action']=="logout"){											
	unset($_SESSION['login'],$_SESSION['password']);
	session_unset(@$_SESSION['login']);session_unset(@$_SESSION['password']);
	setcookie('login','1',1,"/");setcookie('password','1',1,"/");
	setcookie('PHPSESSID','',1,"/");
	header("location: ".$h);
}
?>