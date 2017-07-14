<div class="form-wrapper">
<?
if(defined("COUNT_USER_NOTES")){
	if(@$_POST['board_check'] && @$_POST['del']){
		$impl=implode(', ',$_POST['board_check']);
		if(ctype_digit(@$_COOKIE['jbusernote']) && @$_COOKIE['jbusernote']>0 && !defined('USER'))$valid_id_notes_user="id_notes_user='".$_COOKIE['jbusernote']."'";		
		elseif(!@$_COOKIE['jbusernote'] && defined('USER'))$valid_id_notes_user="user_id='".$user_data['id_user']."'";
		elseif(ctype_digit(@$_COOKIE['jbusernote']) && @$_COOKIE['jbusernote']>0 && defined('USER'))$valid_id_notes_user="(id_notes_user='".$_COOKIE['jbusernote']."' OR user_id='".$user_data['id_user']."')";
		if(mysql_query("DELETE FROM jb_notes WHERE ".$valid_id_notes_user." AND id_board IN (".$impl.")"))  echo "<center><h2>".$lang[646]."</h2></center>";
		else echo "<center><h2>".$lang[647]."</h2></center>";
		echo "<center><a href=\"".$h."note.html\">".$lang[648]."</a></center>";
	}else{
		$arrBoardId=array();
		while($note=mysql_fetch_assoc($qucn))$arrBoardId[]=$note['id_board'];
		$arrBoardId=implode(",",$arrBoardId);
		$limit=5;
		$tot=(COUNT_USER_NOTES-1)/$limit;
		$total=intval($tot+1);
		if(ctype_digit(@$_GET['page']) && @$_GET['page']>0) $page=$_GET['page'];
		else $page=1;
		if($page>$total) $page=$total;
		$start=$page*$limit-$limit;
		$name_cat=(defined('JBLANG')&& constant('JBLANG')=='en')?'en_name_cat':'name_cat';
		$query=mysql_query("SELECT jb_board.id AS board_id, jb_board.id_category, jb_board.title, jb_board.time_delete, DATE_FORMAT(jb_board.date_add,'%d.%m.%Y') AS dateAdd, UNIX_TIMESTAMP(jb_board.date_add) as unix_time, jb_board.checkbox_top, jb_board.checkbox_select, jb_board_cat.id, jb_board_cat.".$name_cat." FROM jb_board LEFT JOIN jb_board_cat ON jb_board.id_category=jb_board_cat.id WHERE jb_board.id IN (".$arrBoardId.") ORDER BY jb_board.id DESC LIMIT ".$start.", ".$limit); cq();
		if(@mysql_num_rows ($query)){
			?><script language="JavaScript">var confirmmess='<?=$lang[172]?>';var alert_no_value='<?=$lang[659]?>';</script><center><h2 class="orange"><?=$lang[650]?> (<?=COUNT_USER_NOTES?>)</h2></center><br /><form method="post" action="<?=$h?>note.html" target="_self" name="note_form" onsubmit="return check_fields_note();"><div class="stradv b orange"><div class="cp1 alcenter"><?=$lang[123]?></div><div class="cp2 alcenter"><?=$lang[406]?> <input type="checkbox" onclick="checkall(this)" /></div><div class="cp3 alcenter">&nbsp;</div><div class="cp4 alcenter"><?=$lang[127]?></div><div class="clear"></div></div><?
			while ($last=mysql_fetch_assoc($query)){
				?><div class="<?=smsclass($last['checkbox_top'],$last['checkbox_select'])?>"><div class="cp1"><a class="b" title="<?=$last['title']?>" href="<?="c".$last['id_category']."-".$last['board_id']?>.html"><?=$last['title']?></a><br /><span class="lgray sm"><?=$lang[122]?>: <?=$last[$name_cat]?><br /><?=$lang[544].": ".strftime('%d.%m.%Y',$last['time_delete']*86400+$last['unix_time'])?></span></div><div class="cp2 alcenter"><input type="checkbox" value="<?=$last['board_id']?>" name="board_check[]" /></div><div class="cp3 alcenter"><? if(($c['money_service']=="yes" || $c['wm_money_service']=="yes") && ($last['checkbox_top']=="0" || $last['checkbox_select']=="0"))echo "<a href=\"vip".$last['board_id'].".html\" title=\"".$lang[510]."\"><img class=\"absmid\" src=\"".$im."vip.gif\" alt=\"".$lang[510]."\" /></a>"; else echo "&nbsp;"; ?></div><div class="cp4 alcenter"><? echo($last['dateAdd']==date("d.m.Y"))?$lang[531]:$last['dateAdd'];?></div><div class="clear"></div></div><? 
			}
			?><div style="text-align:right"><?=$lang[651]?>: <input onclick="this.form.action='<?=$h?>noteprint.html'; this.form.target='_blank';" type="submit" value="<?=$lang[652]?>" name="print" /> <input onclick="return conformdelete(this,confirmmess);" type="submit" value="<?=$lang[653]?>" name="del" /></div></form><br /><br /><?
				if(COUNT_USER_NOTES>=$limit){
				$a="<a href=\"note-p";
				if($page!=1)$pervpage=$a."1.html\" title=\"".$lang[174]."\">&nbsp;&nbsp;&#171;&nbsp;&nbsp;</a> ";
				if($page!=$total) $nextpage=$a.$total.".html\" title=\"".$lang[175]."\">&nbsp;&nbsp;&#187;&nbsp;&nbsp;</a>";		
				$pageleft="";$pageright="";
				for($i=$c['limit_pagination_on_page'];$i>=1;$i--)if($page-$i>0)$pageleft.=$a.($page-$i).".html\">".($page-$i)."</a>";
				for($i=1;$i<=$c['limit_pagination_on_page'];$i++)if($page+$i<=$total)$pageright.=$a.($page+$i).".html\">".($page+$i)."</a>"; 
				echo "<div class=\"pagination\">".@$pervpage.@$pageleft."<b><span class=\"current\">".$page."</span></b>".@$pageright.@$nextpage."</div>";
		}}else echo "<center><h2 class=\"red\">".$lang[98]."</h1></center>";
}}else echo "<center><h2 class=\"red\">".$lang[654]."</h1><br /><a href=\"".$h."new.html\">".$lang[155]."</a></center>";
?>
</div>