<?
$query_city=($city>1)?" AND jb_board.city_id = ".$city:"";
if($cat!=0){
	$GLOBALS['searchcat']="";
	function listcat3($id,$sub){
		$categories=mysql_query("SELECT id, child_category FROM jb_board_cat WHERE root_category=".$id); cq(); 
		$count=0;
		while($category=mysql_fetch_assoc($categories)){	
			if($category['child_category']==1)listcat3($category['id'],$sub+1);
			else{
				if($count==0)$delimiter="";else $delimiter=",";
				$GLOBALS['searchcat'].=$delimiter.$category['id'];
			} $count++;
	}}
	$categories=mysql_query("SELECT child_category FROM jb_board_cat WHERE id='".$cat."'");cq();
	if(mysql_num_rows($categories)){
		while($category=mysql_fetch_assoc($categories)){
			if($category['child_category']==1)listcat3($cat,1);
			else $GLOBALS['searchcat'].=$cat;
	}}
	else die("ERROR_2");
}
if(@$GLOBALS['searchcat']!="")$querycat=" AND id_category IN (".$GLOBALS['searchcat'].") ";else $querycat="";
if(@constant('EXP_TYPE')==="js" || @constant('EXP_TYPE')==="php"){
	$last_add=mysql_query("SELECT id, id_category, title, city, DATE_FORMAT(date_add,'%d.%m.%Y') AS dateAdd FROM jb_board WHERE old_mess = 'old' ".$query_city." ".$querycat." ORDER BY id DESC LIMIT ".$number); cq();
	if(@constant('EXP_TYPE')==="js")	{
		echo "document.write('<div class=\"b_inf_width b_inf_date_size b_inf_date_color\"><br />');";
		while($last=mysql_fetch_assoc($last_add)){
			$printdate=($last['dateAdd']==date("d.m.Y"))?$lang[531]:$last['dateAdd'];
			$printcity=($city!="1")?", ".$last['city']:"";
			echo "document.write('".$printdate." <a target=\"_blank\" class=\"b_inf_text_size b_inf_text_color\" href=\"".$h."c".$last['id_category']."-".$last['id'].".html\">".$last['title']."</a>".$printcity."<br />');";
		}
		echo "document.write('</div><div style=\"clear:both;padding:0;text-align:center;\"><br /><a style=\"font-size:10px;\" href=\"".$h."\">".$lang[568]."</a><br /></div>');";
	}else{
		echo "<div class='b_inf_width b_inf_date_size b_inf_date_color'><br />";
		while($last=mysql_fetch_assoc($last_add)){
			$printdate=($last['dateAdd']==date("d.m.Y"))?$lang[531]:$last['dateAdd'];
			$printcity=($city!="1")?", ".$last['city']:"";
			echo $printdate." <a target='_blank' class='b_inf_text_size b_inf_text_color' href='".$h."c".$last['id_category']."-".$last['id'].".html'>".$last['title']."</a>".$printcity."<br />";
		}
		echo "</div><div style=\"clear:both;padding:0;text-align:center;\"><br /><a style=\"font-size:10px;\" href=\"".$h."\">".$lang[568]."</a><br /></div>";
	}
}
elseif(@constant('EXP_TYPE')==="rss"){
	$query_end=mysql_query("SELECT id, id_category, title, text, city, autor, date_add FROM jb_board WHERE old_mess = 'old' ".$query_city." ".$querycat." ORDER BY id DESC LIMIT ".$number); cq();
	if (@$query_end){
		header('Content-type: application/xml; charset=utf-8');
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
		echo "<rss version=\"2.0\" xmlns:atom=\"https://www.w3.org/2005/Atom\">";
		echo "<channel>";
		echo "<title>".$c['user_title']."</title>";
		echo "<link>".$h."</link>";
		echo "<description>".$lang[658]."</description>";
		echo "<generator>JBoard</generator>";
		echo "<managingEditor>".$c['admin_mail']." (Administrator)</managingEditor>";
		echo "<image>";
		echo "<url>".$im."logo.gif</url>";
		echo "<link>".$h."</link>";
		echo "<title>".$c['user_title']."</title>";
		echo "</image>";
		while($arr=mysql_fetch_assoc($query_end)){
			echo "<item>";
			echo "<title>".$arr['title']."</title>";
			echo "<link>".$h."c".$arr['id_category']."-".$arr['id'].".html</link>";
			echo "<description>".utf8_substr($arr['text'],0,300)."...</description>";
			echo "<author>no@no.com (".$arr['autor'].")</author>";
			echo "<pubDate>".date("r",strtotime($arr['date_add']))."</pubDate>";
			echo "</item>";
		}
		echo "</channel>";
		echo "</rss>";
	}
}else die("ERROR_1");
?>