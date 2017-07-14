<div class="form-wrapper">
<form action="<?=$h?>"><input type="hidden" id="search" name="op" value="search" /><input name="query" type="text" id="search" size="15" value="<?=$lang[156]?>" onfocus="this.value=''" onblur="if (this.value==''){this.value='<?=$lang[156]?>'}" /><input id="submit" type="submit" value="<?=$lang[1126]?>" /><div id="toggle_s"><div id="toggle_s_close"><a style="text-decoration:none;" class="sm b" onclick="toggle_s_close();return false;" href="#" title="close">X</a></div>
</div>
</form>
</div>

<div class="form-wrapper">
<div class="cornhc">
<h3>VIP объявления</h3>
</div>
<center>
<div class="lvip">
<?=$lang[1154]?>
</div>
<?
$query_vip=mysql_query("SELECT jb_board.id, jb_board.id_category, jb_board.title, jb_board.text, jb_photo.photo_name FROM jb_board LEFT JOIN jb_photo ON jb_board.id = jb_photo.id_message WHERE jb_board.old_mess='old' AND jb_board.checkbox_top=1 GROUP by jb_board.id ORDER by jb_board.checkbox_top DESC, jb_board.top_time DESC, jb_board.id DESC LIMIT ".$c['count_print_vip']);cq();
if(mysql_num_rows($query_vip)){
	echo "<div class=\"lvip\"><div class=\"alcenter\"><a class=\"red b\" href=\"".$h."p14.html\" title=\"".$lang[1097]."\">".$lang[1097]."</a></div><br />";	
	while($vip_ads=mysql_fetch_assoc($query_vip)){
		if(utf8_strlen($vip_ads['text'])>100)$vip_ads['text']=utf8_substr($vip_ads['text'],0,97)."...";
		if(@$vip_ads['photo_name']!="")$vip_ads_img="<br /><img alt=\"".$vip_ads['title']."\" src=\"".$u."small/".$vip_ads['photo_name']."\" width=\"280\" height=\"280\" />";else $vip_ads_img="";
		echo "<p><a href=\"".$h."c".$vip_ads['id_category']."-".$vip_ads['id'].".html\">".$vip_ads['title']."</a><br />".$vip_ads_img."</p><div>".$vip_ads['text']."</div>";
	}
	echo "</div>";
}
?>
</center>
</div>