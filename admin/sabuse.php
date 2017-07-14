<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

if(!defined('SITE'))die();
if (@$_GET['op']=="ad_checked" && @$_POST['board_check']){
	$impl=implode(', ',$_POST['board_check']);
	mysql_query("DELETE FROM jb_abuse WHERE id IN (".$impl.")") or die(mysql_error());
	echo "<center><strong>".$lang[400]."</strong></center>";
}else{
	$query=mysql_query("SELECT jb_abuse.id AS abuse_id, jb_abuse.id_board, jb_abuse.type_abuse, jb_board.id_category, jb_board.title, jb_board.text, DATE_FORMAT(jb_board.date_add,'%d.%m.%Y') AS dateAdd FROM jb_abuse LEFT JOIN jb_board ON jb_abuse.id_board = jb_board.id ORDER BY abuse_id DESC");cq();
	if(mysql_num_rows($query)){
		echo "<center><h1>".$lang[402]."</h1></center><br /><div align=\"center\"><form method=\"post\" name=\"city\" action=\"".$h."a/?action=abuse&op=ad_checked\"><table class=\"sort\" align=\"center\" cellspacing=\"5\"><tr bgcolor=\"#F6F6F6\"><td align=\"center\">".$lang[529]."</td><td align=\"center\">".$lang[433]."</td><td align=\"center\">".$lang[299]." &nbsp;<input type=\"checkbox\" name=\"all_boxes\" onclick=\"changeall(city);\"></td></tr>";
		while($line=mysql_fetch_assoc($query)) echo "<tr bgcolor=\"#FFFFFF\"><td>".$line['type_abuse']."</td><td><a title=\"".$lang[549]."\" href=\"".$h."a/?action=ads&op=edit&id_mess=".$line['id_board']."\"><img src=\"".$im."edit.gif\" alt=\"".$lang[549]."\" class=\"absmid\" /></a> <a target=\"_blank\" href=\"".$h."c".$line['id_category']."-".$line['id_board'].".html\" title=\"".$line['text']."\">".$line['title']."</a></td><td align=\"center\"><input type=\"checkbox\" value=\"".$line['abuse_id']."\" name=\"board_check[]\" title=\"".$lang[246]."\"></td></tr>";
		echo "</table><table align=\"center\" cellspacing=\"15\"><tr><td></td><td> &nbsp; </td><td><input type=\"submit\" name=\"actions_for_checkeds\" value=\"".$lang[1060]."\" onclick=\"return conformdelete(this,confirmmess);\"></td></tr></table></form></div>";
	} else echo "<center><strong>".$lang[407]."</strong></center>";
}
?>