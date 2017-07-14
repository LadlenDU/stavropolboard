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
if(ctype_digit(@$_REQUEST['idboard'])>0){
	if(defined('USER') && ctype_digit(@$_COOKIE['jbusernote'])){
		$qun=mysql_query("SELECT * FROM jb_notes_user WHERE id='".$_COOKIE['jbusernote']."' LIMIT 1"); 
		if(@mysql_num_rows($qun)){
			$nud=mysql_fetch_assoc($qun);
			if(mysql_num_rows(mysql_query("SELECT id_board FROM jb_notes WHERE (id_notes_user='".@$nud['id']."' OR user_id='".$user_data['id_user']."') AND id_board='".@$_REQUEST['idboard']."' LIMIT 1"))) $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[498]." <a href=\"".$h."note.html\">".$lang[499]."</a></span>";
			else{
				mysql_query("INSERT jb_notes SET id_notes_user='".@$nud['id']."', user_id='".$user_data['id_user']."', id_board='".$_REQUEST['idboard']."'");
				mysql_query("UPDATE jb_notes_user SET expires='".(time()+7786000)."' WHERE id='".$_COOKIE['jbusernote']."'");  
				$GLOBALS['_RESULT']="<span class=\"red b\">".$lang[500]." <a href=\"".$h."note.html\">".$lang[501]."</a></span>";
		}}else{
			mysql_query("INSERT jb_notes_user SET hash='".session_id()."',expires='".(time()+7786000)."'"); 
			$last_id=mysql_insert_id();
			if(setcookie('jbusernote',$last_id,time()+7776000,"/")){
				mysql_query("INSERT jb_notes SET id_notes_user='".$last_id."', user_id='".$user_data['id_user']."', id_board='".$_REQUEST['idboard']."'");
				$GLOBALS['_RESULT']="<span class=\"red b\">".$lang[500]." <a href=\"".$h."note.html\">".$lang[501]."</a></span>";
			}else $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[98]."</span>";
	}}elseif(defined('USER') && !@$_COOKIE['jbusernote']){
		if(mysql_num_rows(mysql_query("SELECT id_board FROM jb_notes WHERE user_id='".$user_data['id_user']."' AND id_board='".@$_REQUEST['idboard']."' LIMIT 1"))) $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[498]." <a href=\"".$h."note.html\">".$lang[499]."</a></span>";
		else{
			mysql_query("INSERT jb_notes_user SET hash='".session_id()."',expires='".(time()+7786000)."'"); 
			$last_id=mysql_insert_id();
			if(setcookie('jbusernote',$last_id,time()+7776000,"/")){
				mysql_query("INSERT jb_notes SET id_notes_user='".$last_id."', user_id='".$user_data['id_user']."', id_board='".$_REQUEST['idboard']."'");
				$GLOBALS['_RESULT']="<span class=\"red b\">".$lang[500]." <a href=\"".$h."note.html\">".$lang[501]."</a></span>";
			}else $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[98]."</span>";
	}}elseif(!defined('USER') && ctype_digit(@$_COOKIE['jbusernote'])){
		$qun=mysql_query("SELECT * FROM jb_notes_user WHERE id='".$_COOKIE['jbusernote']."' LIMIT 1"); 
		if(@mysql_num_rows($qun)){
			$nud=mysql_fetch_assoc($qun);
			if(mysql_num_rows(mysql_query("SELECT id_board FROM jb_notes WHERE id_notes_user='".@$nud['id']."' AND id_board='".@$_REQUEST['idboard']."' LIMIT 1"))) $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[498]." <a href=\"".$h."note.html\">".$lang[499]."</a></span>";
			else{
				mysql_query("INSERT jb_notes SET id_notes_user='".@$nud['id']."',user_id='0',id_board='".$_REQUEST['idboard']."'");
				$GLOBALS['_RESULT']="<span class=\"red b\">".$lang[500]." <a href=\"".$h."note.html\">".$lang[501]."</a></span>";
				mysql_query("UPDATE jb_notes_user SET expires='".(time()+7786000)."' WHERE id='".$_COOKIE['jbusernote']."'");  
		}}else{
			mysql_query("INSERT jb_notes_user SET hash='".session_id()."',expires='".(time()+7786000)."'"); 
			$last_id=mysql_insert_id();
			if(setcookie('jbusernote',$last_id,time()+7776000,"/")){
				mysql_query("INSERT jb_notes SET id_notes_user='".$last_id."', user_id='0', id_board='".$_REQUEST['idboard']."'");
				$GLOBALS['_RESULT']="<span class=\"red b\">".$lang[500]." <a href=\"".$h."note.html\">".$lang[501]."</a></span>";
			}else $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[98]."</span>";
	}}elseif(!defined('USER') && !@$_COOKIE['jbusernote']){
		mysql_query("INSERT jb_notes_user SET hash='".session_id()."',expires='".(time()+7786000)."'"); 
		$last_id=mysql_insert_id();
		if(setcookie('jbusernote',$last_id,time()+7776000,"/")){
			mysql_query("INSERT jb_notes SET id_notes_user='".$last_id."', user_id='0', id_board='".$_REQUEST['idboard']."'");
			$GLOBALS['_RESULT']="<span class=\"red b\">".$lang[500]." <a href=\"".$h."note.html\">".$lang[501]."</a></span>";
		}else $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[98]."</span>";
}}else $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[98]."</span>";
?>
