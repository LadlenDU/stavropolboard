<div class="form-wrapper">
<center>
<h1><a href="<?=$h?>">Доска объявлений Ставрополя</a></h1>
<a href='https://stavropolboard.ru/' title="Доска объявлений Ставрополя"><img src="images/logo.png" width="100%"></a>
</center>
<div class="form-wrapper">
<h3><?=$lang[602]?></h3>
</div>
<div class="form-wrap1">
  <a href="<?=$h?>"><?=$lang[1148]?></a>
  <a href="<?=$h?>new.html"><?=$lang[595]?></a> 
  <a href="<?=$h?>newlist.html"><?=$lang[658]?></a>
  <a href="<?=$h?>search.html"><?=$lang[146]?></a>
  <a href="<?=$h?>informers.html"><?=$lang[1014]?></a>
  <a href="<?=$h?>contacts.html"><?=$lang[139]?></a>
  <a target="_blank" href="<?=$h?>forum">Форум</a>
  <?
$query_pages=mysql_query ("SELECT id,title FROM jb_page WHERE menu='yes' ORDER by sort_index"); cq();  
if(@mysql_num_rows(@$query_pages)){
	while($page=mysql_fetch_assoc($query_pages))echo "<a href=\"".$h."p".$page['id'].".html\">".$page['title']."</a>";
	
}
?>
</div>
</div>