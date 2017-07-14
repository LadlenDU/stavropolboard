<div class="form-wrapper">
<?
$query_kaleidoscope=mysql_query("SELECT A.id_photo, A.photo_name, B.id, B.id_category, B.title FROM jb_photo A, jb_board B WHERE A.id_message=B.id AND B.old_mess='old' GROUP by A.id_message ORDER by RAND() LIMIT ".$c['count_show_img_kaleidoscope']);cq(); 
if(mysql_num_rows($query_kaleidoscope)){
	echo "<div class=\"form-wrapper\"><h3>".$lang[811]."</h3></div><div class=\"kaleidoscope\">";
	while($kaleidoscope=mysql_fetch_assoc($query_kaleidoscope))echo "<a title=\"".$kaleidoscope['title']."\" href=\"".$h."c".$kaleidoscope['id_category']."-".$kaleidoscope['id'].".html\"><img alt=\"".$kaleidoscope['title']."\" src=\"".$u."small/".$kaleidoscope['photo_name']."\" /></a>";
	echo "</div>";
}
?>
</div>