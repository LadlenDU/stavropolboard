<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

if(!defined('SITE'))die();
if(@$_GET['type']){
	$name_cat=(defined('JBLANG')&& constant('JBLANG')=='en')?'en_name_cat':'name_cat';
	$where_search=" WHERE ";
	if($_GET['type']=="word"){
		$gquery=trim($_GET['word']);
		$gquery=strip_tags_smart($gquery);
		$gquery=preg_replace("/[^a-zа-яЁё0-9\s]+/umi","",$gquery); 
		$gquery=htmlspecialchars($gquery);
		$gquery=cleansql($gquery);
		if(utf8_strlen($gquery)<3)die($lang[158]);
		if(@$_GET['logic']=="and")$logic=" AND ";else $logic=" OR ";
		if (@$_GET['title']=="on")$where_title=" jb_board.title LIKE '%".$gquery."%' ".$logic;
		if (@$_GET['text']=="on")$where_text=" jb_board.text LIKE '%".$gquery."%' ".$logic;
		if (@$_GET['autor']=="on")$where_autor=" jb_board.autor LIKE '%".$gquery."%' ".$logic;
		if (@$_GET['contacts']=="on")$where_contacts=" jb_board.contacts LIKE '%".$gquery."%' ".$logic;
		if (@$_GET['email']=="on")$where_email=" jb_board.email LIKE '%".$gquery."%' ".$logic;
		if (@$_GET['tags']=="on")$where_tags=" jb_board.tags LIKE '%".$gquery."%' ".$logic;
		if (@$_GET['url']=="on")$where_url=" jb_board.url LIKE '%".$gquery."%' ".$logic;
		$where_search.=@$where_title.@$where_text.@$where_autor.@$where_contacts.@$where_email.@$where_tags.@$where_url;
		if($where_search==" WHERE ")die("<center><strong>".$lang[98]." ".$lang[305]."</strong></center>");
		$where_search=utf8_substr($where_search,0,-4); 
	}
	elseif($_GET['type']=="id" && ctype_digit(@$_GET['word']))$where_search.=" jb_board.id='".$_GET['word']."' ";
	elseif($_GET['type']=="commercial" && (@$_GET['vip']=="on" || @$_GET['select']=="on")){
		if(@$_GET['vip']=="on" && @$_GET['select']=="on")$where_search.=" jb_board.checkbox_top=1 OR jb_board.checkbox_select=1";
 		elseif(@$_GET['vip']=="on" && @$_GET['select']!="on")$where_search.=" jb_board.checkbox_top=1";
		elseif(@$_GET['vip']!="on" && @$_GET['select']=="on")$where_search.=" jb_board.checkbox_select=1";
	}
	else die("<center><strong>".$lang[98]." ".$lang[305]."</strong></center>");
	$result=mysql_query("SELECT id FROM jb_board ".$where_search);cq();
	if(@$result)$total_rows=mysql_num_rows($result);
	if(@$total_rows){
		if(ctype_digit(@$_GET['page'])&& @$_GET['page']>0)$page=$_GET['page'];else $page=1;
		$tot=($total_rows-1)/$c['count_adv_on_index'];
		$total=intval($tot+1);if($page>$total) $page=$total;
		$start=$page*$c['count_adv_on_index']-$c['count_adv_on_index'];
		$query=mysql_query("SELECT jb_board.id AS board_id, jb_board.id_category, jb_board.title, jb_board.city, jb_board.checkbox_top, jb_board.checkbox_select, jb_board.autor, jb_board.text, jb_board.checked,  DATE_FORMAT(jb_board.date_add,'%d.%m.%Y') AS dateAdd, UNIX_TIMESTAMP(jb_board.date_add) as unix_time, jb_board_cat.id, jb_board_cat.".$name_cat." FROM jb_board LEFT JOIN jb_board_cat ON jb_board.id_category = jb_board_cat.id ".$where_search." ORDER BY board_id DESC LIMIT ".$start.",".$c['count_adv_on_index']);cq();
		if(mysql_num_rows($query)){
			echo "<form method=\"post\" name=\"city\" action=\"".$h."a/?action=ads&op=ad_checked\">";
			echo "<table class=\"sort\" align=\"center\" cellspacing=\"5\" width=\"100%\"><thead><tr bgcolor=\"#F6F6F6\"><td align=\"center\">".$lang[123]."</td><td align=\"center\">".$lang[1033]."</td><td align=\"center\">".$lang[1041]."</td><td align=\"center\">".$lang[106]."</td><td align=\"center\">".$lang[423]."</td><td align=\"center\">".$lang[127]."</td><td colspan=\"2\" align=\"center\">".$lang[126]."</td><td align=\"center\">".$lang[299]."&nbsp;&nbsp;<input type=\"checkbox\" name=\"all_boxes\" onclick=\"changeall(city);\"></td></tr></thead><tbody>";
			while($board = mysql_fetch_assoc($query)){	
				echo "<tr bgcolor=\"#F9F9F9\"><td><span class=\"sm gray\">".$board['autor']."</span><br /><a target=\"_blank\" href =\"".$h."c".$board['id_category']."-".$board['board_id'].".html\" title=\"".$board['text']."\">".$board['title']."</a></td><td align=center>";
				if($board['checked']=="no")echo "<p>0</p><img title=\"".$lang[1038]."\" alt=\"".$lang[1038]."\" src=\"".$im."ads_new.png\">";
				elseif($board['checked']=="edit")echo "<p>1</p><img title=\"".$lang[1039]."\" alt=\"".$lang[1039]."\" src=\"".$im."ads_edit.png\">";
				else echo "<p>2</p><img title=\"".$lang[1040]."\" alt=\"".$lang[1040]."\" src=\"".$im."ads_old.png\">";
				echo "</td><td align=center>";
				if($board['checkbox_top']==1)echo "<p>1</p><img title=\"".$lang[128]."\" alt=\"".$lang[128]."\" src=\"".$im."vip.gif\">";
				elseif($board['checkbox_top']!=1 && $board['checkbox_select']==1)echo "<p>0</p><img title=\"".$lang[424]."\" alt=\"".$lang[424]."\" src=\"".$im."lost.gif\">";
				echo "</td><td align=center>";
				$countphoto=mysql_result(mysql_query("select count(id_photo) from jb_photo WHERE id_message='".$board['board_id']."'"),0);cq();
				echo(@$countphoto)?$countphoto:"";
				echo "</td><td align=center>";
				$countcomments=mysql_result(mysql_query("select count(id) from jb_comments WHERE id_board='".$board['board_id']."'"),0);
				echo (@$countcomments)?"<a href=\"".$h."a/?action=comments&id_mess=".$board['board_id']."\">".$countcomments."</a>":"";
				echo "</td><td><p>".$board['unix_time']."</p>";
				if($board['dateAdd']==date("d.m.Y"))echo $lang[531];else echo $board['dateAdd'];
				echo "</td><td align=center><a href =\"".$h."a/?action=ads&op=edit&id_mess=".$board['board_id']."\"><img src=\"".$im."edit.gif\"></a></td><td align=center><a href =\"".$h."a/?action=ads&op=del&id_mess=".$board['board_id']."\" onClick='return conformdelete(this,confirmmess);'><img src=\"".$im."del.gif\"></a></td><td align=center><input type=\"checkbox\" value=\"".$board['board_id']."\" name=\"board_check[]\" title=\"".$lang[246]."\"></td></tr>";
			}
			echo "</tbody></table><table align=\"center\" cellspacing=\"15\"><tr><td>";
			if ($total_rows>=$c['count_adv_on_index']){
				$a="<a href=\"".$h."a/?action=ads_search";
				if(@$_GET['type'])$a.="&type=".@$_GET['type'];
				if(@$_GET['word'])$a.="&word=".@$_GET['word'];
				if(@$_GET['title'])$a.="&title=".@$_GET['title'];
				if(@$_GET['text'])$a.="&text=".@$_GET['text'];
				if(@$_GET['autor'])$a.="&autor=".@$_GET['autor'];
				if(@$_GET['contacts'])$a.="&contacts=".@$_GET['contacts'];
				if(@$_GET['email'])$a.="&email=".@$_GET['email'];
				if(@$_GET['tags'])$a.="&tags=".@$_GET['tags'];
				if(@$_GET['url'])$a.="&url=".@$_GET['url'];
				if(@$_GET['vip'])$a.="&vip=".@$_GET['vip'];
				if(@$_GET['select'])$a.="&select=".@$_GET['select'];
				if(@$_GET['logic'])$a.="&logic=".$_GET['logic'];
				$a.="&page=";
				if($page!=1)$pervpage=$a."1\" title=\"".$lang[174]."\">&nbsp;&nbsp;&#171;&nbsp;&nbsp;</a> ";
				if($page!=$total)$nextpage=$a.$total."\" title=\"".$lang[175]."\">&nbsp;&nbsp;&#187;&nbsp;&nbsp;</a>";		
				$pageleft="";$pageright="";
				for($i=$c['limit_pagination_on_page'];$i>=1;$i--)if($page-$i>0)$pageleft.=$a.($page-$i)."\">".($page-$i)."</a>";
				for($i=1;$i<=$c['limit_pagination_on_page'];$i++)if($page+$i<=$total)$pageright.=$a.($page+$i)."\">".($page+$i)."</a>"; 
				echo "<div class=\"pagination\">".@$pervpage.@$pageleft."<b><span class=\"current\">".$page."</span></b>".@$pageright.@$nextpage."</div>";
			}
			echo "</td><td> &nbsp; </td><td>".$lang[303].": <select name=\"actions_for_checkeds\"><option value=\"del_checked\">".$lang[300]."</select><input type=\"submit\" value=\"ok\" onclick=\"return conformdelete(this,confirmmess);\"></td></tr></table></form>";
		}else echo "<br /><center><h1>".$lang[407]."</h1></center><br />";
	}else echo "<br /><center><h1>".$lang[407]."</h1></center><br />";
}
else
{ 
	?><div align="center"><h1><?=$lang[820]?></h1><br /><br /><span style="margin:20px;"><a class="green b" href="#" onclick="details2('search_1');return false;"><?=$lang[1066]?></a> </span><span style="margin:20px;"><a class="green b" href="#" onclick="details2('search_2');return false;"><?=$lang[1067]?></a> </span><span style="margin:20px;"><a class="green b" href="#" onclick="details2('search_3');return false;"><?=$lang[1068]?></a></span><div style="margin:20px"><div id="search_1" style="display:none;"><form method="get" action="<?=$h?>a/"><input type="hidden" name="action" value="ads_search" /><input type="hidden" name="type" value="id" /><table width="40%" cellpadding="10" cellspacing="10"><tr><td><strong>#ID:</strong><br /><br /><input type="text" name="word" size="50%" /></td></tr><tr><td align="center"><input style="width:100px" type="submit" value="<?=$lang[156]?>" /></td></tr></table></form></div><div id="search_2" style="display:none;"><form method="get" action="<?=$h?>a/?action=ads_search"><input type="hidden" name="action" value="ads_search" /><input type="hidden" name="type" value="word" /><table width="40%" cellpadding="10" cellspacing="10"><tr><td><strong><?=$lang[655]?>:</strong><br /><br /><input type="text" name="word" size="50%" /></td></tr><tr><td><strong><?=$lang[36]?>:</strong><br /><br /><input type="checkbox" name="title" checked="checked" /> <?=$lang[161]?><br /><input type="checkbox" name="text" /> <?=$lang[162]?><br /><input type="checkbox" name="autor" /> <?=$lang[821]?><br /><input type="checkbox" name="contacts" /> <?=$lang[822]?><br /><input type="checkbox" name="email" /> <?=$lang[150]?><br /><input type="checkbox" name="tags" /> <?=$lang[1009]?><br /><input type="checkbox" name="url" /> <?=$lang[546]?><br /><br /></td></tr><tr><td><strong><?=$lang[1034]?></strong>: &nbsp;  &nbsp; <?=$lang[1036]?>: <input type="radio" name="logic" value="or" checked="checked" /> &nbsp; <?=$lang[1035]?>: <input type="radio" name="logic" value="and" /> &nbsp; &nbsp; (<a href="#" onclick="alert('<?=$lang[1065]?>');return false;"><?=$lang[1061]?></a>)<br /><br /></td></tr><tr><td align="center"><input style="width:100px" type="submit" value="<?=$lang[156]?>" /></td></tr></table></form></div><div id="search_3" style="display:none;"><form method="get" action="<?=$h?>a/?action=ads_search"><input type="hidden" name="action" value="ads_search" /><input type="hidden" name="type" value="commercial" /><table width="40%" cellpadding="10" cellspacing="10"><tr><td><strong><?=$lang[1062]?>:</strong><br /><br /><input type="checkbox" name="vip"> <?=$lang[1063]?><br /><input type="checkbox" name="select"> <?=$lang[1064]?><br /></td></tr><tr><td align="center"><input style="width:100px" type="submit" value="<?=$lang[156]?>" /></td></tr></table></form></div></div></div><?
}
?>