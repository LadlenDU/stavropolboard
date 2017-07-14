<div class="form-wrapper">
<?
$query_news=mysql_query ("SELECT id,title,translit,short FROM jb_news WHERE old_mess='old' ORDER by id DESC LIMIT ".$c['count_print_news']);cq();
if(mysql_num_rows($query_news)){
	echo "<div class=\"cornhc\"><h3>".$lang[286]."</h3></div><div class=\"cornhr\"></div><div class=\"lnews\">";
	while($news_announcement=mysql_fetch_assoc($query_news))echo "<p class=\"news\"><a href=\"".$h."n".$news_announcement['id']."-".$news_announcement['translit'].".html\">".$news_announcement['title']."</a></p><div>".$news_announcement['short']."</div>";
	echo "<div class=\"alcenter\"><a class=\"red\" href=\"".$h."news.html\" title=\"".$lang[295]."\">".$lang[295]."</a> ";
	if($c['add_new_news']=="yes")echo "| <a class=\"green\" href=\"".$h."addnews.html\">".$lang[294]."</a>";
	echo "</div></div>";
}
?>
</div>