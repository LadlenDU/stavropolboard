<div class="form-wrapper">
<?
if(ctype_digit(@$_GET['id'])){
	if(@$news_arr['logo']){$isize=getimagesize($u."news/".$news_arr['logo']);$imsize="width=\"".$isize[0]."\" height=\"".$isize[1]."\"";} else $imsize="";
	?><div class="news_title"><h1><?=$news_arr['title']?></h1></div><div class="news_full"><?=(@$news_arr['logo'])?"<img class=\"news_logo\" alt=\"".$news_arr['title']."\" src=\"".$u."news/".$news_arr['logo']."\" ".$imsize." />":""?><?=nl2br($news_arr['full'])?></div><?
	if(@$news_arr['autor'])echo "<div class=\"news_autor\">".$lang[100]." ".$news_arr['autor']."</div>";
	?><div class="news_date"><?=$news_arr['dat']?><br /><?=$lang[862]?>: <?=$news_arr['hits']?></div></br><div class="clear"></div>
<div class="form-wrapper">
<h3>Комментарии и отзывы</h3>
</div>
<?
if($c['view_comments']=="yes"){
	$query_comments=mysql_query("SELECT id,autor,text,DATE_FORMAT(date,'%d.%m.%Y') as dat FROM jb_comments WHERE id_board='".$news_arr['id']."' AND old_mess='old' ORDER by id DESC");cq();
	if($query_comments)$count_comments=mysql_num_rows($query_comments);
	$count_c=($count_comments)?"(".$count_comments.")":"";
	if(@$count_comments){
		echo "<div style=\"padding:3px;\"><img src=\"".$im."comments.gif\" class=\"absmid\" alt=\"".$lang[555]."\" /> <a class=\"dgray\" href=\"#\" onclick=\"details('d111');return false;\">".$lang[555]."</a> <span class=\"sm gray\">".$count_c."</span></div><div id=\"d111\">";
		while($comments=mysql_fetch_assoc($query_comments)) echo "<div class=\"comments\"><img src=\"".$im."comments.gif\" class=\"absmid\" alt=\"".$comments['dat']."\" /> <span class=\"gray\">".$comments['dat']." <strong>".$comments['autor']."</strong></span><br /><br />".nl2br($comments['text'])."</div>";
		echo "</div>";
	}
}
if($c['add_comments']=="yes") echo "<div style=\"padding:3px;\" id=\"add_comments\"><img src=\"".$im."comment.gif\" class=\"absmid\" alt=\"".$lang[557]."\" /> <a class=\"dgray\" href=\"#\" onclick=\"add_comments('".$news_arr['id']."',0,0);return false;\">".$lang[557]."</a></div>";
echo "</div>";
?><div class="news_links"><a class="red" href="<?=$h?>news.html" title="<?=$lang[295]?>"><?=$lang[295]?></a>
<?
	if($c['add_new_news']=="yes")echo " | <a class=\"green\" href=\"".$h."addnews.html\" title=\"".$lang[294]."\">".$lang[294]."</a> <img class=\"absmid\" src=\"".$im."plus.png\" alt=\"".$lang[294]."\" />";
	echo "</div>";
	$query_news=mysql_query("UPDATE jb_news SET hits=hits+1 WHERE id='".$_GET['id']."' LIMIT 1");cq(); 
}else{
	echo "<center><h1>".$lang[142]."</h1></center><br /><br />";
	$result=mysql_query("SELECT id FROM jb_news WHERE old_mess='old'");cq();
	if(@$result)$total_rows=mysql_num_rows($result);
	if(@$total_rows){
		if(ctype_digit(@$_GET['page'])&& @$_GET['page']>0)$page=$_GET['page'];else $page=1;
		$tot=($total_rows-1)/$c['count_news_in_page'];
		$total=intval($tot+1);if($page>$total)$page=$total;
		$start=$page*$c['count_news_in_page']-$c['count_news_in_page'];
		$query_news=mysql_query("SELECT id, title, autor, translit, logo, short, DATE_FORMAT(jb_news.date,'%d.%m.%Y') as dat FROM jb_news WHERE old_mess='old' ORDER BY id DESC LIMIT ".$start.",".$c['count_news_in_page']);cq();
		if(mysql_num_rows($query_news)){
			while($news_arr=mysql_fetch_assoc($query_news)){
				if(@$news_arr['logo']){$isize=getimagesize($u."news/".$news_arr['logo']);$imsize="width=\"".$isize[0]."\" height=\"".$isize[1]."\"";}
				else $imsize="";
				if(@$news_arr['autor'])$print_autor="<br />".$lang[100].": ".$news_arr['autor'];else $print_autor="";
				?><div class="news_list"><div class="news_date"><?=$news_arr['dat']?><?=$print_autor?></div><br /><a class="news_list_title" href="<?=$h?>n<?=$news_arr['id']?>-<?=$news_arr['translit']?>.html"><?=$news_arr['title']?></a><br /><br /><div class="news_full"><?=(@$news_arr['logo'])?"<img class=\"news_logo\" alt=\"".$news_arr['title']."\" src=\"".$u."news/".$news_arr['logo']."\" ".$imsize." />":""?><?=nl2br($news_arr['short'])?><br /><br /><a href="<?=$h?>n<?=$news_arr['id']?>-<?=$news_arr['translit']?>.html"><?=$lang[91]?></a> &rarr;</div><div class="clear"></div></div>				
				<?
			}
			if ($total_rows>=$c['count_news_in_page']){
				$a="<a href=\"".$h."news-p";
				if($page!=1)$pervpage=$a."1.html\" title=\"".$lang[174]."\">&nbsp;&nbsp;&nbsp;&#171;&nbsp;&nbsp;&nbsp;</a> ";
				if($page!=$total)$nextpage=$a.$total.".html\" title=\"".$lang[175]."\">&nbsp;&nbsp;&nbsp;&#187;&nbsp;&nbsp;&nbsp;</a>";		
				$pageleft="";$pageright="";
				for($i=$c['limit_pagination_on_page'];$i>=1;$i--)if($page-$i>0)$pageleft.=$a.($page-$i).".html\">".($page-$i)."</a>";
				for($i=1;$i<=$c['limit_pagination_on_page'];$i++)if($page+$i<=$total)$pageright.=$a.($page+$i).".html\">".($page+$i)."</a>"; 
				
				echo "<div class=\"pagination\">".@$pervpage.@$pageleft."<b><span class=\"current\">".$page."</span></b>".@$pageright.@$nextpage."</div>";
			}								
			if($c['add_new_news']=="yes") echo "<div class=\"news_links\"><a class=\"green\" href=\"".$h."addnews.html\" title=\"".$lang[294]."\">".$lang[294]."</a> <img class=\"absmid\" src=\"".$im."plus.png\" alt=\"".$lang[294]."\" /></div>";
		}
	}
}
?>
</div>