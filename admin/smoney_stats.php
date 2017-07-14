<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

if (!defined('SITE')) die();
mysql_query("DELETE FROM jb_stat_wm WHERE completed='no' AND DATE(date) + INTERVAL 1 DAY < CURDATE()");cq();
?><div align="center"><h1><?=$lang[439]?></h1><br /><br /><a class="b" href="<?=$h?>a/?action=money_stats&op=online"><?=$lang[1122]?></a> <a style="margin-left:50px;" class="b" href="<?=$h?>a/?action=money_stats&op=archive"><?=$lang[1123]?></a></div><br /><br /><?
if(@$_GET['op']=="online"){
	$result=mysql_query("SELECT id FROM jb_board WHERE checkbox_select=1 OR checkbox_top=1");cq();
	if(@$result)$total_rows=mysql_num_rows($result);
	if(@$total_rows){
		if(ctype_digit(@$_GET['page'])&& @$_GET['page']>0)$page=$_GET['page'];else $page=1;
		$tot=($total_rows-1)/$c['count_adv_on_index'];
		$total=intval($tot+1);if($page>$total) $page=$total;
		$start=$page*$c['count_adv_on_index']-$c['count_adv_on_index'];
		$query = mysql_query ("SELECT jb_board.id board_id, jb_board.id_category, jb_board.title, jb_board.checkbox_top, jb_board.checkbox_select, jb_stat_sms.id sms_id, jb_stat_sms.numb_phone, jb_stat_sms.operator,  jb_stat_wm.id wm_id, jb_stat_wm.wmid, jb_stat_wm.purse, UNIX_TIMESTAMP(jb_board.top_time) as unix_top_time, DATE_FORMAT(jb_board.top_time, '%d.%m.%Y') datetop, UNIX_TIMESTAMP(jb_board.select_time) as unix_select_time, DATE_FORMAT(jb_board.select_time, '%d.%m.%Y')dateselect FROM jb_board LEFT JOIN jb_stat_sms ON (jb_board.id = jb_stat_sms.id_board) LEFT JOIN jb_stat_wm ON (jb_board.id = jb_stat_wm.id_board) WHERE jb_board.checkbox_select = 1 OR jb_board.checkbox_top = 1 GROUP BY board_id ORDER BY board_id DESC LIMIT ".$start.", ".$c['count_adv_on_index']) or die(mysql_error()); cq();		
		if (mysql_num_rows ($query)){
			echo "<center><H4>".$lang[448]."</h4></center><br /><br /><form method=\"post\" name=\"city\" action=\"".$h."a/?action=money_stats&op=ad_checked\"><table class=\"sort\" align=\"center\" cellspacing=\"5\"><tr bgcolor=\"#DADADA\"><td>".$lang[449]."</td><td align=center>".$lang[529]."</td><td align=center>User</td><td align=center>".$lang[454]."</td><td align=center>".$lang[665]."</td><td colspan=\"2\" align=\"center\">".$lang[1070]."</td><td align=center>".$lang[1071]." ".$lang[299]."&nbsp;&nbsp;<span class=\"small\"><input type=\"checkbox\" name=\"all_boxes\" onclick=\"changeall_moneystat(city);\"></span></td></tr>";
			while ($data = mysql_fetch_assoc ($query)){
				if(@$data['sms_id']||@$data['wm_id']){
					if(@$data['numb_phone'])$trbgcol="#DAFFCC";
					else $trbgcol="#FFFFCC";
					echo "<tr bgcolor=\"".$trbgcol."\"><td><a target=\"_blank\" href=\"".$h."c".$data['id_category']."-".$data['board_id'].".html\">".$data['title']."</a></td><td align=center>";
					if($data['checkbox_top']=="1")echo "<img title=\"".$lang[128]."\" alt=\"".$lang[128]."\" src=\"".$im."vip.gif\">";
					else echo "<img title=\"".$lang[424]."\" alt=\"".$lang[424]."\" src=\"".$im."lost.gif\">";
					echo "</td><td align=center>";
					if(@$data['numb_phone'] && @$data['numb_phone']!="---")echo $data['numb_phone']."<br /><span class=\"sm\">(".@$data['operator'].")</span>";
					elseif(@$data['wmid'])echo @$data['purse']."<br /><span class=\"sm\">(WMID:".$data['wmid'].")</span>";//
					else echo "---";
					echo "</td><td align=center>";
					if($data['datetop']!="00.00.0000")echo $data['datetop'];
					elseif($data['dateselect']!="00.00.0000")echo $data['dateselect'];
					echo "</td><td align=center>";
					if($data['checkbox_top']=="1") echo strftime('%d.%m.%Y',$c['top_status_days'] * 86400 + $data['unix_top_time']);
					else echo strftime('%d.%m.%Y',$c['select_status_days'] * 86400 + $data['unix_select_time']);
					echo "</td><td align=center><a title=\"".$lang[12]." ".$lang[262]."\" href =\"".$h."a/?action=ads&op=edit&id_mess=".$data['board_id']."\"><img alt=\"".$lang[12]." ".$lang[262]."\" src=\"".$im."edit.gif\"></a></td><td align=center><a title=\"".$lang[300]." ".$lang[262]."\" href =\"".$h."a/?action=ads&op=del&id_mess=".$data['board_id']."\" onclick='return conformdelete(this,confirmmess);'><img alt=\"".$lang[300]." ".$lang[262]."\" src=\"".$im."del.gif\"></a></td>";
					if(@$data['sms_id']){$idstat=$data['sms_id'];$typestat="sms_arr";}
					elseif(@$data['wm_id']){$idstat=$data['wm_id'];$typestat="wm_arr";}
					echo "<td align=center><a href =\"".$h."a/?action=money_stats&op=del&id=".$idstat."&type=".$typestat."\" onclick='return conformdelete(this,confirmmess);' title=\"".$lang[1072]."\"><img alt=\"".$lang[1072]."\" src=\"".$im."del.gif\"></a> &nbsp; ".$lang[1036]." &nbsp; <input type=\"checkbox\" value=\"".$idstat."\" name=\"".$typestat."[]\" title=\"".$lang[246]."\"></td></tr>";
				}
			}
			echo "<tr><td colspan=\"3\">";
			if ($total_rows>=$c['count_adv_on_index']){
				$a="<a href=\"?action=money_stats&op=online&page=";
				if($page!=1)$pervpage=$a."1\" title=\"".$lang[174]."\">&nbsp;&nbsp;&nbsp;&#171;&nbsp;&nbsp;&nbsp;</a> ";
				if($page!=$total) $nextpage=$a.$total."\" title=\"".$lang[175]."\">&nbsp;&nbsp;&nbsp;&#187;&nbsp;&nbsp;&nbsp;</a>";		
				$pageleft="";$pageright="";
				for($i=$c['limit_pagination_on_page'];$i>=1;$i--)if($page-$i>0)$pageleft.=$a.($page-$i)."\">".($page-$i)."</a>";
				for($i=1;$i<=$c['limit_pagination_on_page'];$i++)if($page+$i<=$total)$pageright.=$a.($page+$i)."\">".($page+$i)."</a>"; 
				echo "<div class=\"pagination\">".@$pervpage.@$pageleft."<b><span class=\"current\">".$page."</span></b>".@$pageright.@$nextpage."</div>";
			}
			echo "</td><td align=\"right\" colspan=\"5\">".$lang[303].": <select name=\"actions_for_checkeds\"><option value=\"del_checked\">".$lang[300]."</option></select> <input type=\"submit\" value=\"ok\" onclick=\"return conformdelete(this,confirmmess);\"></td></tr></table></form></div>";
		}else echo "<br /><center><h1>".$lang[407]."</h1></center><br />";
	}else echo "<br /><center><h1>".$lang[407]."</h1></center><br />";
}
elseif(@$_GET['op']=="archive"){
	$sms_stat_num=mysql_result(mysql_query("SELECT COUNT(*) FROM jb_stat_sms"),0);cq();
	$wm_stat_num=mysql_result(mysql_query("SELECT COUNT(*) FROM jb_stat_wm WHERE completed='yes'"),0);cq();
	$total_rows=$sms_stat_num+$wm_stat_num;
	if(@$total_rows!="0"){
		if(ctype_digit(@$_GET['page'])&& @$_GET['page']>0)$page=$_GET['page'];else $page=1;
		$tot=($total_rows-1)/$c['count_adv_on_index'];
		$total=intval($tot+1);if($page>$total) $page=$total;
		$start=$page*$c['count_adv_on_index']-$c['count_adv_on_index'];
		$query = mysql_query ("(SELECT jb_board.id board_id, jb_board.id_category, jb_board.title, jb_board.checkbox_top, jb_board.checkbox_select, jb_stat_sms.id as stat_id, 's' AS stat_type, jb_stat_sms.numb_phone as userinfo, jb_stat_sms.operator as userinfo2, UNIX_TIMESTAMP(jb_board.top_time) as unix_top_time, DATE_FORMAT(jb_board.top_time, '%d.%m.%Y') datetop, UNIX_TIMESTAMP(jb_board.select_time) as unix_select_time, DATE_FORMAT(jb_board.select_time, '%d.%m.%Y')dateselect FROM jb_stat_sms LEFT JOIN jb_board ON (jb_board.id = jb_stat_sms.id_board ) ) UNION ALL  (SELECT jb_board.id board_id, jb_board.id_category, jb_board.title, jb_board.checkbox_top, jb_board.checkbox_select, jb_stat_wm.id stat_id, 'w' AS stat_type, jb_stat_wm.wmid as userinfo, jb_stat_wm.purse as userinfo2, UNIX_TIMESTAMP(jb_board.top_time) as unix_top_time, DATE_FORMAT(jb_board.top_time, '%d.%m.%Y') datetop, UNIX_TIMESTAMP(jb_board.select_time) as unix_select_time, DATE_FORMAT(jb_board.select_time, '%d.%m.%Y')dateselect FROM jb_stat_wm LEFT JOIN jb_board ON (jb_board.id = jb_stat_wm.id_board ) WHERE completed='yes' ) ORDER by stat_id DESC LIMIT ".$start.", ".$c['count_adv_on_index']) or die(mysql_error()); cq();
		if (mysql_num_rows ($query)){
			echo "<form method=\"post\" name=\"city\" action=\"".$h."a/?action=money_stats&op=ad_checked\"><table class=\"sort\" align=\"center\" cellspacing=\"5\"><tr bgcolor=\"#DADADA\"><td>".$lang[449]."</td><td align=center>User</td><td align=center>".$lang[454]."</td><td align=center>".$lang[665]."</td><td colspan=\"2\" align=\"center\">".$lang[1070]."</td><td align=center>".$lang[1071]." ".$lang[299]."&nbsp;&nbsp;<span class=\"small\"><input type=\"checkbox\" name=\"all_boxes\" onclick=\"changeall_moneystat(city);\"></span></td></tr>";
			while ($data = mysql_fetch_assoc ($query)){
				if($data['stat_type']=="s"){
					if($data['userinfo2']=="admin")$trbgcol="#F9F9F9";
					else $trbgcol="#DAFFCC";
				} else $trbgcol="#FFFFCC";
				echo "<tr bgcolor=\"".$trbgcol."\"><td>";
				if(@$data['checkbox_top'] || @$data['checkbox_select']){
					if($data['checkbox_top']=="1")echo "<img alt=\"".$lang[128]."\" src=\"".$im."vip.gif\" class=\"absmid\">";
					else echo "<img alt=\"".$lang[424]."\" src=\"".$im."lost.gif\" class=\"absmid\">";
				}
				if(@$data['title'] && @$data['board_id']) echo " <a target=\"_blank\" href=\"".$h."c".$data['id_category']."-".$data['board_id'].".html\">".$data['title']."</a>";
				else echo "<span class=\"sm gray\">".$lang[63]."</span>";
				echo "</td><td align=center>";
				if(@$data['userinfo2']=="admin")echo $data['userinfo2'];
				else{
					if($data['stat_type']=="s")$sttype="tel:";
					elseif($data['stat_type']=="w")$sttype="WMID:";
					echo "<strong>".$data['userinfo2']."</strong><br /><span class=\"sm\">".$sttype.$data['userinfo']."</span>";
				}
				echo "</td><td align=center>";
				if($data['datetop']!="00.00.0000")echo $data['datetop'];
				elseif($data['dateselect']!="00.00.0000")echo $data['dateselect'];
				echo "</td><td align=center>";
				if($data['checkbox_top']=="1") echo strftime('%d.%m.%Y',$c['top_status_days'] * 86400 + $data['unix_top_time']);
				elseif($data['checkbox_select']=="1") echo strftime('%d.%m.%Y',$c['select_status_days'] * 86400 + $data['unix_select_time']);
				echo "</td><td align=center>";
				if(@$data['board_id']) echo "<a title=\"".$lang[12]." ".$lang[262]."\" href =\"".$h."a/?action=ads&op=edit&id_mess=".$data['board_id']."\"><img alt=\"".$lang[12]." ".$lang[262]."\" src=\"".$im."edit.gif\"></a></td><td align=center><a title=\"".$lang[300]." ".$lang[262]."\" href =\"".$h."a/?action=ads&op=del&id_mess=".$data['board_id']."\" onclick='return conformdelete(this,confirmmess);'><img alt=\"".$lang[300]." ".$lang[262]."\" src=\"".$im."del.gif\"></a>";
				else echo "</td><td>";
				echo "</td>";
				if(@$data['stat_type']=="s")$typestat="sms_arr"; else $typestat="wm_arr";
				echo "<td align=center><a href =\"".$h."a/?action=money_stats&op=del&id=".$data['stat_id']."&type=".$typestat."\" onclick='return conformdelete(this,confirmmess);' title=\"".$lang[1072]."\"><img alt=\"".$lang[1072]."\" src=\"".$im."del.gif\"></a> &nbsp; ".$lang[1036]." &nbsp; <input type=\"checkbox\" value=\"".$data['stat_id']."\" name=\"".$typestat."[]\" title=\"".$lang[246]."\"></td></tr>";
			}
			echo "<tr><td colspan=\"3\">";
			if ($total_rows>=$c['count_adv_on_index']){
				$a="<a href=\"?action=money_stats&op=archive&page=";
				if($page!=1)$pervpage=$a."1\" title=\"".$lang[174]."\">&nbsp;&nbsp;&nbsp;&#171;&nbsp;&nbsp;&nbsp;</a> ";
				if($page!=$total) $nextpage=$a.$total."\" title=\"".$lang[175]."\">&nbsp;&nbsp;&nbsp;&#187;&nbsp;&nbsp;&nbsp;</a>";		
				$pageleft="";$pageright="";
				for($i=$c['limit_pagination_on_page'];$i>=1;$i--)if($page-$i>0)$pageleft.=$a.($page-$i)."\">".($page-$i)."</a>";
				for($i=1;$i<=$c['limit_pagination_on_page'];$i++)if($page+$i<=$total)$pageright.=$a.($page+$i)."\">".($page+$i)."</a>"; 
				echo "<div class=\"pagination\">".@$pervpage.@$pageleft."<b><span class=\"current\">".$page."</span></b>".@$pageright.@$nextpage."</div>";
			}
			echo "</td><td align=\"right\" colspan=\"5\">".$lang[303].": <select name=\"actions_for_checkeds\"><option value=\"del_checked\">".$lang[300]."</option></select> <input type=\"submit\" value=\"ok\" onclick=\"return conformdelete(this,confirmmess);\"></td></tr></table></form></div>";
		}else echo "<br /><center><h1>".$lang[407]."</h1></center><br />";
	}else echo "<br /><center><h1>".$lang[407]."</h1></center><br />";	
}
elseif(@$_GET['op']=="del" && ctype_digit($_GET['id'])){
	if($_GET['type']=="sms_arr") mysql_query("DELETE FROM jb_stat_sms WHERE id='".$_GET['id']."' LIMIT 1") or die(mysql_error());
	elseif($_GET['type']=="wm_arr") mysql_query("DELETE FROM jb_stat_wm WHERE id='".$_GET['id']."' LIMIT 1") or die(mysql_error());
	echo "<center><strong>".$lang[400]."</strong></center>";
}
elseif(@$_GET['op']=="ad_checked" && (@$_POST['sms_arr'] || @$_POST['wm_arr'])){
	if(@$_POST['sms_arr']){
		$impl=implode(', ',$_POST['sms_arr']);
		mysql_query("DELETE FROM jb_stat_sms WHERE id IN (".$impl.")") or die(mysql_error());cq();
	}
	elseif(@$_POST['wm_arr']){
		$impl=implode(', ',$_POST['wm_arr']);
		mysql_query("DELETE FROM jb_stat_wm WHERE id IN (".$impl.")") or die(mysql_error());cq();
	}else die("ERROR_1");
	echo "<center><strong>".$lang[400]."</strong></center>";
}
?>