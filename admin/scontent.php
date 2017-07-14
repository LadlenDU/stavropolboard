<script type="text/javascript" src="https://stavropolboard.ru/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    window.onload = function()
    {
        CKEDITOR.replace('text', {
            toolbar: 'Basic',
        });
    };
</script>
<?
if(!defined('SITE')) die();
if(@$_GET['op']=="add"){
	if(@$_POST['text'] && @$_POST['title']){
		$title=trim($_POST['title']);$title=clean($title);
		$text=trim($_POST['text']);$text=cleansql($text);
		if(@$_POST['keywords']){$keywords=trim($_POST['keywords']);$keywords=clean($keywords);}else $keywords="";
		if(@$_POST['descr']){$descr=trim($_POST['descr']);$descr=clean($descr);}else $descr="";
		if(ctype_digit(@$_POST['sort_index']))$sort_index=$_POST['sort_index'];else $sort_index=0;
		if(mysql_query("INSERT jb_page SET title='".$title."', text='".$text."', keywords='".$keywords."', descr='".$descr."', menu='".$_POST['menu']."', sort_index='".$sort_index."'"))
		echo "<center><strong>".$lang[224]."</strong></center><br /><br /><br />";
		else echo "<center><strong>".$lang[98]."</strong></center><br /><br /><br />";
	}
	else echo "<div align=\"center\"><strong>".$lang[435]."</strong><br /><br /><form name=\"form\" method=\"post\"  action=\"".$h."a/?action=content&op=add\"><table cellpadding=\"10\" width=\"90%\"><tr><td width=\"20%\">".$lang[78].": </td><td><input type=\"text\" name=\"title\" size=\"60\"></td></tr><tr><td>".$lang[1054].": </td><td><input type=\"text\" name=\"keywords\" size=\"60\"></td></tr><tr><td>".$lang[1055].": </td><td><input type=\"text\" name=\"descr\" size=\"60\"></td></tr><tr><td>".$lang[436]." </td><td>".$lang[119]." - <input type=\"radio\" name=\"menu\" value=\"yes\" checked> &nbsp; ".$lang[120]." - <input type=\"radio\" name=\"menu\" value=\"no\"></td></tr><tr><td>".$lang[287]." <font size=1>(".$lang[1057].")</font></td><td><textarea name=\"text\" rows=\"20\" cols=\"80\"></textarea></td></tr><tr><td>".$lang[802]."</td><td><input type=\"text\" size=\"60\" name=\"sort_index\"></td></tr><tr><td colspan=\"2\"><br /><input style=\"width:100%\" type=\"submit\" value=\"".$lang[59]."\"></td></tr></table></form></div>";
}
elseif(@$_GET['op']=="edit" && ctype_digit(@$_GET['id_page'])){
	if(@$_POST['text'] && @$_POST['title']){
		$title=trim($_POST['title']);$title=clean($title);
		$text=trim($_POST['text']);$text=cleansql($text);
		if(@$_POST['keywords']){$keywords=trim($_POST['keywords']);$keywords=clean($keywords);}else $keywords="";
		if(@$_POST['descr']){$descr=trim($_POST['descr']);$descr=clean($descr);}else $descr="";
		if(ctype_digit(@$_POST['sort_index']))$sort_index=$_POST['sort_index'];else $sort_index=0;
		if(mysql_query("UPDATE jb_page SET title='".$title."', text='".$text."', keywords='".$keywords."', descr='".$descr."', menu='".$_POST['menu']."', sort_index='".$sort_index."' WHERE id='".$_GET['id_page']."' LIMIT 1"))
		echo "<center><strong>".$lang[193]."</strong></center><br /><br /><br />";
		else echo "<center><strong>".$lang[98]."</strong></center><br /><br /><br />";
	}
	else{
		$query=mysql_query("SELECT * FROM jb_page WHERE id='".$_GET['id_page']."'");
		if($query) $line=mysql_fetch_assoc($query);
		echo "<div align=\"center\"><strong>".$lang[1142]."</strong><br /><br /><form name=\"form\" method=\"post\"  action=\"".$h."a/?action=content&op=edit&id_page=".$_GET['id_page']."\"><table cellpadding=\"10\" width=\"90%\"><tr><td width=\"20%\">".$lang[78].": </td><td><input type=\"text\" name=\"title\" size=\"60\" value=\"".$line['title']."\"></td></tr><tr><td>".$lang[1054].": </td><td><input type=\"text\" name=\"keywords\" size=\"60\" value=\"".$line['keywords']."\"></td></tr><tr><td>".$lang[1055].": </td><td><input type=\"text\" name=\"descr\" size=\"60\" value=\"".$line['descr']."\"></td></tr><tr><td>".$lang[436]." </td><td>".$lang[119]." - <input type=\"radio\" name=\"menu\" value=\"yes\"";
		if($line['menu']=="yes")echo " checked";
		echo "> &nbsp; ".$lang[120]." - <input type=\"radio\" name=\"menu\" value=\"no\"";
		if($line['menu']=="no")echo " checked";
		echo "></td></tr><tr><td>".$lang[287]."  <font size=1>(".$lang[1057].")</font></td><td><textarea name=\"text\" rows=\"20\" cols=\"80\">".htmlspecialchars($line['text'])."</textarea></td></tr><tr><td>".$lang[802]."</td><td><input type=\"text\" size=\"60\" name=\"sort_index\" value=\"".$line['sort_index']."\"></td></tr><tr><td colspan=\"2\"><br /><input style=\"width:100%\" type=\"submit\" value=\"".$lang[59]."\"></td></tr></table></form></div>";
	}
}
elseif(@$_GET['op']=="del" && ctype_digit($_GET['id_page'])){
	if(mysql_query("DELETE FROM jb_page WHERE id='".$_GET['id_page']."' LIMIT 1")) 
	echo "<center><strong>".$lang[239]."</strong></center><br /><br /><br />";
	else echo "<center><strong>".$lang[98]."</strong></center><br /><br /><br />";
}
else{
	echo "<br /><br /><br /><div align=\"center\"><table><tr><td bgcolor=\"#E5E5E5\"><table class=\"sort\"><tr bgcolor=\"#FFFFFF\"><td colspan=\"9\" align=\"center\"><div style=\"padding:10px\"><a href=\"".$h."a/?action=content&op=add\"><strong>".$lang[435]."</strong></a></div></td></tr><tr bgcolor=\"#F6F6F6\"><td align=\"center\">".$lang[123]."</td><td align=\"center\">".$lang[436]."</td><td align=\"center\">".$lang[802]."</td><td colspan=\"2\" align=\"center\">".$lang[126]."</td></tr>";
	$query=mysql_query("SELECT id, title, menu, sort_index FROM jb_page ORDER by sort_index");
	while($line=mysql_fetch_assoc($query)){
		if($line['menu']=="yes") $menu="<img src=\"".$im."yes.gif\">"; else $menu="<img src=\"".$im."close.gif\">";
		echo "<tr bgcolor=\"#FFFFFF\"><td><a href=\"".$h."p".$line['id'].".html\">".$line['title']."</a></td><td align=center>".$menu."</td><td align=\"center\">".$line['sort_index']."</td><td align=center><a title=".$lang[12]." href=\"".$h."a/?action=content&op=edit&id_page=".$line['id']."\"><img src=\"".$im."edit.gif\"></a></td><td align=center><a onclick='return conformdelete(this,confirmmess);' title=".$lang[300]." href=\"".$h."a/?action=content&op=del&id_page=".$line['id']."\"><img src=\"".$im."del.gif\"></a></td></tr>";
	}
	echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"9\" align=\"center\"><div style=\"padding:10px\"><a href=\"".$h."a/?action=content&op=add\"><strong>".$lang[435]."</strong></a></div></td></tr></table></td></tr></table></div>";
}
?>