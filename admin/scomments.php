<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

if(!defined('SITE')) die();
if(@$_GET['op']=="edit" && ctype_digit(@$_GET['id_mess'])){
	if(@$_POST['text']){
		if(@$_POST['autor']){$autor=trim($_POST['autor']);$autor=clean($autor);}else $autor="";
		$text=trim($_POST['text']);$text=clean($text);
		if(mysql_query("UPDATE jb_comments SET autor='".$autor."', text='".$text."', old_mess='old', checked='yes' WHERE id='".$_GET['id_mess']."' LIMIT 1"))
		echo "<center><strong>".$lang[400]."</strong></center><br /><br /><br />";
		else echo "<center><strong>".$lang[98]."</strong></center><br /><br /><br />";
	}else{
		$query=mysql_query("SELECT autor, text FROM jb_comments WHERE id='".$_GET['id_mess']."'");
		if($query) $line=mysql_fetch_assoc($query);
		echo "<div align=\"center\"><strong>".$lang[1058]."</strong><br /><br /><form name=\"form\" method=\"post\" action=\"".$h."a/?action=comments&op=edit&id_mess=".$_GET['id_mess']."\"><table cellpadding=\"10\"><tr>
		<td width=\"20%\">".$lang[100].": </td><td><input type=\"text\" name=\"autor\" size=\"60\" value=\"".$line['autor']."\"></td></tr>
		<tr><td>".$lang[287].": </td><td><textarea name=\"text\" cols=\"46\">".$line['text']."</textarea></td></tr>
		<tr><td colspan=\"2\"><br /><input style=\"width:100%\" type=\"submit\" value=\"".$lang[59]."\"></td></tr>
		</table></form></div>";
	}
}
elseif(@$_GET['op']=="del" && ctype_digit($_GET['id_mess'])){
	if(mysql_query("DELETE FROM jb_comments WHERE id='".$_GET['id_mess']."' LIMIT 1")) 
	echo "<center><strong>".$lang[400]."</strong></center><br /><br /><br />";
	else echo "<center><strong>".$lang[98]."</strong></center><br /><br /><br />";
}
elseif (@$_GET['op']=="ad_checked" && @$_POST['board_check']){
	$impl=implode(', ',$_POST['board_check']);
	if (@$_POST['actions_for_checkeds']=="del_checked"){
		mysql_query("DELETE FROM jb_comments WHERE id IN (".$impl.")") or die(mysql_error());
		echo "<center><strong>".$lang[400]."</strong></center>";
	}else{
		mysql_query("UPDATE jb_comments SET old_mess='old', checked='yes' WHERE id IN (".$impl.")") or die(mysql_error());
		echo "<center><strong>".$lang[400]."</strong></center>";
	}
}
else{
	if(@$_GET['op']=="new")$q_where=" WHERE jb_comments.old_mess!='old' OR jb_comments.checked!='yes'";	
	elseif(ctype_digit(@$_GET['id_mess']) && !@$_GET['op'])$q_where=" WHERE jb_comments.id_board='".$_GET['id_mess']."'";
	else $q_where="";
	$result=mysql_query("SELECT id FROM jb_comments ".$q_where);cq();
	if(@$result)$total_rows=mysql_num_rows($result);
	if(@$total_rows){
		if(ctype_digit(@$_GET['page'])&& @$_GET['page']>0)$page=$_GET['page'];else $page=1;
		$tot=($total_rows-1)/$c['count_adv_on_index'];
		$total=intval($tot+1);if($page>$total)$page=$total;
		$start=$page*$c['count_adv_on_index']-$c['count_adv_on_index'];
		$query=mysql_query("SELECT jb_comments.id AS comments_id, jb_comments.id_board, jb_comments.autor, jb_comments.text AS comments_text, jb_comments.checked, DATE_FORMAT(jb_comments.date,'%d.%m.%Y') AS comments_dateAdd, jb_board.title, jb_board.id_category, jb_board.text, DATE_FORMAT(jb_board.date_add,'%d.%m.%Y') AS dateAdd FROM jb_comments LEFT JOIN jb_board ON jb_comments.id_board = jb_board.id ".$q_where." ORDER BY comments_id DESC LIMIT ".$start.",".$c['count_adv_on_index']);cq();
		if(mysql_num_rows($query)){
			echo "<center><h1>".$lang[423]."</h1></center><br /><div align=\"center\"><form method=\"post\" name=\"city\" action=\"".$h."a/?action=comments&op=ad_checked\"><table class=\"sort\" align=\"center\" cellspacing=\"5\"><tr bgcolor=\"#F6F6F6\"><td align=\"center\">".$lang[127]."</td><td align=\"center\">".$lang[1033]."</td><td align=\"center\">".$lang[100]."</td><td align=\"center\">".$lang[287]."</td><td align=\"center\">".$lang[433]."</td><td colspan=\"2\" align=\"center\">".$lang[126]."</td><td align=\"center\">".$lang[299]." &nbsp;<input type=\"checkbox\" name=\"all_boxes\" onclick=\"changeall(city);\"></td></tr>";
			while($line=mysql_fetch_assoc($query)){
				echo "<tr bgcolor=\"#FFFFFF\"><td align=\"center\">".$line['comments_dateAdd']."</td><td>";
				if($line['checked']=="no")echo "<img title=\"".$lang[1038]."\" alt=\"".$lang[1038]."\" src=\"".$im."ads_new.png\">";
				else echo "<img title=\"".$lang[1040]."\" alt=\"".$lang[1040]."\" src=\"".$im."ads_old.png\">";
				echo "</td><td>".$line['autor']."</td><td>".$line['comments_text']."</td><td><a target=\"_blank\" href=\"".$h."c".$line['id_category']."-".$line['id_board'].".html\" title=\"".$line['text']."\">".$line['title']."</a> <font size=1>(".$lang[1059]." ".$line['dateAdd'].")</font></td><td align=\"center\"><a title=".$lang[12]." href=\"".$h."a/?action=comments&op=edit&id_mess=".$line['comments_id']."\"><img src=\"".$im."edit.gif\"></a></td><td align=\"center\"><a onclick='return conformdelete(this,confirmmess);' title=".$lang[300]." href=\"".$h."a/?action=comments&op=del&id_mess=".$line['comments_id']."\"><img src=\"".$im."del.gif\"></a></td><td align=\"center\"><input type=\"checkbox\" value=\"".$line['comments_id']."\" name=\"board_check[]\" title=\"".$lang[246]."\"></td></tr>";
			}
			echo "</table><table align=\"center\" cellspacing=\"15\"><tr><td>";
			if ($total_rows>=$c['count_adv_on_index']){
				if(@$_GET['op']=="new")$subGet="&op=new";	
				elseif(ctype_digit(@$_GET['id_mess']) && !@$_GET['op'])$subGet="&id_mess=".$_GET['id_mess'];					
				else $subGet="";
				$a="<a href=\"?action=comments".$subGet."&page=";
				if($page!=1)$pervpage=$a."1\" title=\"".$lang[174]."\">&nbsp;&nbsp;&nbsp;&#171;&nbsp;&nbsp;&nbsp;</a> ";
				if($page!=$total) $nextpage=$a.$total."\" title=\"".$lang[175]."\">&nbsp;&nbsp;&nbsp;&#187;&nbsp;&nbsp;&nbsp;</a>";		
				$pageleft="";$pageright="";
				for($i=$c['limit_pagination_on_page'];$i>=1;$i--)if($page-$i>0)$pageleft.=$a.($page-$i)."\">".($page-$i)."</a>";
				for($i=1;$i<=$c['limit_pagination_on_page'];$i++)if($page+$i<=$total)$pageright.=$a.($page+$i)."\">".($page+$i)."</a>"; 
				echo "<div class=\"pagination\">".@$pervpage.@$pageleft."<b><span class=\"current\">".$page."</span></b>".@$pageright.@$nextpage."</div>";
			}
			echo "</td><td> &nbsp; </td><td>".$lang[303].": <select name=\"actions_for_checkeds\"><option value=\"moderation_checked\" selected>".$lang[302]."<option value=\"del_checked\">".$lang[300]."</select><input type=\"submit\" value=\"ok\" onclick=\"return conformdelete(this,confirmmess);\"></td></tr></table></form></div>";
		}else echo "<center><strong>".$lang[407]."</strong></center>";
	}else echo "<center><strong>".$lang[407]."</strong></center>";
}
?>