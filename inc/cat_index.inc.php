<div class="form-wrapper">
<?
$GLOBALS['cccount'] = 0;
if (defined('JBCITY')) $GLOBALS['subQuery'] = ' AND city_id = '.JBCITY; else $GLOBALS['subQuery'] = '';
function listcat2($id,$sub){
	$categories = mysql_query("SELECT id, child_category, name_cat, en_name_cat FROM jb_board_cat WHERE root_category = $id ORDER by sort_index"); cq(); 
	while($category = mysql_fetch_assoc($categories)){	
		$name_cat = (defined('JBLANG') && constant('JBLANG')=='en') ? $category['en_name_cat'] : $category['name_cat'];
		$count_ads = mysql_result(mysql_query("SELECT COUNT(id) from jb_board WHERE id_category='".$category['id']."' AND old_mess='old'".$GLOBALS['subQuery']), 0);cq();
		if($sub=="2") $subclass="class=\"subclass\"";else $subclass="";
		echo "";
		$GLOBALS['cccount'] = $GLOBALS['cccount'] + $count_ads;
		if($category['child_category']==1){listcat2($category['id'],$sub+1);} 
	}
}
$categories = mysql_query("SELECT id, child_category, name_cat,en_name_cat,img FROM jb_board_cat WHERE root_category=0 ORDER by sort_index");  cq();
$num_rows = @mysql_num_rows($categories);
$count_field=round($num_rows/2);$td=0;
echo "<div style=\"float:left;width:50%;\" class=\"index_cat gray sm\">"; 
while($category = @mysql_fetch_assoc($categories)){
	$name_cat = (defined('JBLANG') && constant('JBLANG')=='en') ? $category['en_name_cat'] : $category['name_cat'];
	echo (@$category['img'])?"<img alt=\"".$name_cat."\" class=\"rootcatimg\" src=\"".$u."cat/".$category['img']."\" />":"";
	if($category['child_category']==1){
		echo "<div class=\"form-wrap1\"><a href=\"c".$category['id'].".html\">".$name_cat."</a></div>";
		listcat2 ($category ['id'],1 );
		echo "<br />";
	}
	else echo "<div class=\"form-wrap1\"><a href=\"c".$category['id'].".html\">".$name_cat."</a></div><br />";
	$td++;if($td>=$count_field){echo "</div><div style=\"float:right;width:50%;\" class=\"index_cat gray sm\">";$td=0;}
}
echo "</div><div class=\"clear\"></div>";
?>
</div>