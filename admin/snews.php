<script type="text/javascript" src="https://stavropolboard.ru/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    window.onload = function()
    {
        CKEDITOR.replace('full', {
            toolbar: 'Basic',
        });
    };
</script>
<?

if(!defined('SITE')) die();
if(@$_GET['op']=="add"){
	if(@$_POST['short'] && @$_POST['full'] && @$_POST['title'])	{
		$title=trim($_POST['title']);$title=clean($title);
		$translit=translit($_POST['title']);
		$short=trim($_POST['short']);$short=clean($short);
		$full=trim($_POST['full']);$full=cleansql($full);
		if(@$_POST['keywords']){$keywords=trim($_POST['keywords']);$keywords=clean($keywords);}else $keywords="";
		if(@$_POST['descr']){$descr=trim($_POST['descr']);$descr=clean($descr);}else $descr="";
		if(@$_POST['autor']){$autor=trim($_POST['autor']);$autor=clean($autor);}else $autor="";
		$insert=mysql_query("INSERT jb_news SET title='".$title."', autor='".$autor."', translit='".$translit."', short='".$short."', full='".$full."', keywords='".$keywords."', descr='".$descr."', old_mess='old', date=NOW()");
		if($insert){
			if(@$_FILES['logo']){
				$last_id=mysql_insert_id();
				$die_del_mess="DELETE FROM jb_news WHERE id='".$last_id."' LIMIT 1";
				if($_FILES['logo']['error']==0 && $_FILES['logo']['size']>0){
					$size=getimagesize($_FILES["logo"]["tmp_name"]);
					if($size['mime']=="image/gif")$ext="gif";
					elseif($size['mime']=="image/jpeg")$ext="jpeg";
					elseif($size['mime']=="image/png")$ext="png";
					else{
						mysql_query($die_del_mess);cq();
						die("<center><strong>".$lang[226]." ".$_FILES['logo']['name']." ".$lang[227].$lang[173]."</strong></center>");
					}
					if($_FILES['logo']['size'] < $c['upl_image_size']){
						$file_id=$last_id;
						$vname=$title;
						$filename=utf8_substr(translit($vname),0,128);
						$filename=$filename."_".$file_id.".".$ext;
						if(!@img_resize($_FILES['logo']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$filename,$c['width_news_images'],0,0,$ext,$size[1],$size[0])){
							if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$filename);
							mysql_query($die_del_mess);cq();
							die("<center><strong>".$lang[411]." ".$_FILES['logo']['name']." ".$lang[227].$lang[173]."</strong></center>");
						}
						$update=mysql_query("UPDATE jb_news SET logo='".$filename."' WHERE id='".$last_id."' LIMIT 1");cq();
						if(!$update){
							if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$filename);
							mysql_query($die_del_mess); cq();
							die("<center><strong>".$lang[411]." ".$_FILES['logo']['name']." ".$lang[227].$lang[173]."</strong></center>");
						}
					}else{
						mysql_query($die_del_mess);cq();
						die("<center><strong>".$lang[641]." ".$_FILES['logo']['name']." ".$lang[642].$lang[173]."</strong></center>");
					}
				} 
			}
			echo "<center><strong>".$lang[400]."</strong></center>";
		}else echo "<center><strong>".$lang[98]."</strong></center>";
	}else echo "<div align=\"center\"><strong>".$lang[292]."</strong><br /><br /><form name=\"form\" method=\"post\" action=\"".$h."a/?action=news&op=add\" enctype=\"multipart/form-data\"><table cellpadding=\"10\" width=\"90%\"><tr><td width=\"20%\">".$lang[78].": </td><td><input type=\"text\" name=\"title\" size=\"60\"></td></tr><tr><td width=\"20%\">".$lang[100].": </td><td><input type=\"text\" name=\"autor\" size=\"60\"></td></tr><tr><td width=\"20%\">".$lang[1056].": </td><td><textarea name=\"short\" rows=\"4\" cols=\"60\"></textarea></td></tr><tr><td>".$lang[287]." <font size=1>(".$lang[1057].")</font></td><td><textarea name=\"full\" rows=\"10\" cols=\"60\"></textarea></td></tr><tr><td>".$lang[1054].": </td><td><input type=\"text\" name=\"keywords\" size=\"60\"></td></tr><tr><td>".$lang[1055].": </td><td><input type=\"text\" name=\"descr\" size=\"60\"></td></tr><tr><td>".$lang[223]." </td><td><input type=\"file\" name=\"logo\" /></td></tr><tr><td colspan=\"2\"><br /><input style=\"width:100%\" type=\"submit\" value=\"".$lang[59]."\"></td></tr></table></form></div>";
}
elseif(@$_GET['op']=="edit" && ctype_digit(@$_GET['id_news'])){
	if(@$_POST['short'] && @$_POST['full'] && @$_POST['title']){
		$title=trim($_POST['title']);$title=clean($title);
		$translit=translit($_POST['title']);
		$short=trim($_POST['short']);$short=clean($short);
		$full=trim($_POST['full']);$full=cleansql($full);
		if(@$_POST['keywords']){$keywords=trim($_POST['keywords']);$keywords=clean($keywords);}else $keywords="";
		if(@$_POST['descr']){$descr=trim($_POST['descr']);$descr=clean($descr);}else $descr="";
		if(@$_POST['autor']){$autor=trim($_POST['autor']);$autor=clean($autor);}else $autor="";
		$insert=mysql_query("UPDATE jb_news SET title='".$title."', autor='".$autor."', translit='".$translit."', short='".$short."', full='".$full."', keywords='".$keywords."', descr='".$descr."', old_mess='old' WHERE id='".$_GET['id_news']."' LIMIT 1");
		if($insert){
			if(@$_FILES['logo']){
				$last_id=$_GET['id_news'];
				if($_FILES['logo']['error']==0 && $_FILES['logo']['size']>0){
					$size=getimagesize($_FILES["logo"]["tmp_name"]);
					if($size['mime']=="image/gif")$ext="gif";
					elseif($size['mime']=="image/jpeg")$ext="jpeg";
					elseif($size['mime']=="image/png")$ext="png";
					else die("<center><strong>".$lang[226]." ".$_FILES['logo']['name']." ".$lang[227].$lang[173]."</strong></center>");
					if($_FILES['logo']['size'] < $c['upl_image_size']){
						$file_id=$last_id;
						$vname=$title;
						$filename=utf8_substr(translit($vname),0,128);
						$filename=$filename."_".$file_id.".".$ext;
						if(!@img_resize($_FILES['logo']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$filename,$c['width_news_images'],1,0xFFFFFF,$ext,$size[1],$size[0])){
							if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$filename);
							die("<center><strong>".$lang[411]." ".$_FILES['logo']['name']." ".$lang[227].$lang[173]."</strong></center>");
						}
						$update=mysql_query("UPDATE jb_news SET logo='".$filename."' WHERE id='".$last_id."' LIMIT 1");cq();
						if(!$update){
							if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$filename);
							die("<center><strong>".$lang[411]." ".$_FILES['logo']['name']." ".$lang[227].$lang[173]."</strong></center>");
						}
					}else die("<center><strong>".$lang[641]." ".$_FILES['logo']['name']." ".$lang[642].$lang[173]."</strong></center>");
				} 
			}
			echo "<center><strong>".$lang[400]."</strong></center>";
		}else echo "<center><strong>".$lang[98]."</strong></center>";
	}else{
		$query=mysql_query("SELECT * FROM jb_news WHERE id='".$_GET['id_news']."'");
		if($query) $line=mysql_fetch_assoc($query);
		echo "<div align=\"center\"><strong>".$lang[293]."</strong><br /><br /><form name=\"form\" method=\"post\" action=\"".$h."a/?action=news&op=edit&id_news=".$_GET['id_news']."\" enctype=\"multipart/form-data\"><table cellpadding=\"10\" width=\"90%\"><tr><td width=\"20%\">".$lang[78].": </td><td><input type=\"text\" name=\"title\" size=\"60\" value=\"".$line['title']."\"></td></tr><tr><td width=\"20%\">".$lang[100].": </td><td><input type=\"text\" name=\"autor\" size=\"60\" value=\"".$line['autor']."\"></td></tr><tr><td width=\"20%\">".$lang[1056].": </td><td><textarea name=\"short\" rows=\"4\" cols=\"60\">".$line['short']."</textarea></td></tr><tr><td>".$lang[287]." <font size=1>(".$lang[1057].")</font></td><td><textarea name=\"full\" rows=\"10\" cols=\"60\">".htmlspecialchars($line['full'])."</textarea></td></tr><tr><td>".$lang[1054].": </td><td><input type=\"text\" name=\"keywords\" size=\"60\" value=\"".$line['keywords']."\"></td></tr><tr><td>".$lang[1055].": </td><td><input type=\"text\" name=\"descr\" size=\"60\" value=\"".$line['descr']."\"></td></tr><tr><td>".$lang[223]." </td><td>";
		if(@$line['logo']) echo "<img src=\"".$u."news/".$line['logo']."\"> <a href=\"".$h."a/?action=news&op=del_image&id_news=".$_GET['id_news']."\" onclick=\"return conformdelete(this,confirmmess);\"><img src=\"".$im."image_remove.png\" alt=\"".$lang[107]."\" title=\"".$lang[107]."\"></a><br /><br />";
		else echo "<input type=\"file\" name=\"logo\" />";
		echo "</td></tr><tr><td colspan=\"2\"><br /><input style=\"width:100%\" type=\"submit\" value=\"".$lang[59]."\"></td></tr></table></form></div>";
	}
}
elseif(@$_GET['op']=="del" && ctype_digit($_GET['id_news'])){
	$icon=mysql_query("SELECT logo FROM jb_news WHERE id='".$_GET['id_news']."'");
	if(mysql_num_rows($icon)){
		$caticon=mysql_fetch_assoc($icon);
		if(@file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$caticon['logo']))@unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$caticon['logo']);
	}
	if(mysql_query("DELETE FROM jb_news WHERE id='".$_GET['id_news']."' LIMIT 1")) 
	echo "<center><strong>".$lang[239]."</strong></center>";
	else echo "<center><strong>".$lang[98]."</strong></center>";
}
elseif(@$_GET['op']=="del_image" && ctype_digit($_GET['id_news'])){
	$icon=mysql_query("SELECT logo FROM jb_news WHERE id='".$_GET['id_news']."'");
	if(mysql_num_rows($icon)){
		$caticon=mysql_fetch_assoc($icon);
		if(@file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$caticon['logo']))@unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$caticon['logo']);
		$icon_update=mysql_query("UPDATE jb_news set logo='' WHERE id='".$_GET['id_news']."' LIMIT 1");
		echo($icon_update)?"<center><strong>".$lang[63]."</strong></center>":die("<center><strong>".$lang[64]."</strong></center>");
	}
}
else{
	if(@$_GET['op']=="new")$sbquery=" WHERE old_mess='new' ";else $sbquery="";
	$result=mysql_query("SELECT id FROM jb_news ".$sbquery);cq();
	if(@$result)$total_rows=mysql_num_rows($result);
	if(@$total_rows){
		if(ctype_digit(@$_GET['page'])&& @$_GET['page']>0)$page=$_GET['page'];else $page=1;
		$tot=($total_rows-1)/$c['count_adv_on_index'];
		$total=intval($tot+1);if($page>$total) $page=$total;
		$start=$page*$c['count_adv_on_index']-$c['count_adv_on_index'];
		echo "<br /><br /><br /><div align=\"center\"><table><tr><td bgcolor=\"#E5E5E5\"><table class=\"sort\"><tr bgcolor=\"#FFFFFF\"><td colspan=\"9\" align=\"center\"><div style=\"padding:10px\"><a href=\"".$h."a/?action=news&op=add\"><strong>".$lang[294]."</strong></a></div></td></tr><tr bgcolor=\"#F6F6F6\"><td align=center>".$lang[127]."</td><td align=\"center\">".$lang[1033]."</td><td align=\"center\">".$lang[123]."</td><td align=\"center\">".$lang[100]."</td><td align=\"center\">".$lang[1056]."</td><td align=center>".$lang[223]."</td><td colspan=\"3\" align=\"center\">".$lang[126]."</td></tr>";
		$query=mysql_query("SELECT id, title, autor, translit, short, logo, old_mess, date FROM jb_news ".$sbquery." ORDER by id DESC LIMIT ".$start.",".$c['count_adv_on_index']);cq();
		while($line=mysql_fetch_assoc($query)){
			echo "<tr bgcolor=\"#FFFFFF\"><td align=center>".$line['date']."</td><td align=center>";
			if($line['old_mess']=="new")echo "<img title=\"".$lang[1038]."\" alt=\"".$lang[1038]."\" src=\"".$im."ads_new.png\">";
			else echo "<img title=\"".$lang[1040]."\" alt=\"".$lang[1040]."\" src=\"".$im."ads_old.png\">";
			echo "</td><td><a target=\"_blank\" href=\"".$h."n".$line['id']."-".$line['translit'].".html\">".$line['title']."</a></td><td align=center>".@$line['autor']."</td><td align=center>".$line['short']."</td><td align=center>";if(@$line['logo'])echo "<img src=\"".$u."news/".$line['logo']."\" />";echo "</td><td align=center><a title=".$lang[12]." href=\"".$h."a/?action=news&op=edit&id_news=".$line['id']."\"><img src=\"".$im."edit.gif\"></a></td><td align=center><a onclick='return conformdelete(this,confirmmess);' title=".$lang[300]." href=\"".$h."a/?action=news&op=del&id_news=".$line['id']."\"><img src=\"".$im."del.gif\"></a></td></tr>";
		}
		echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"9\" align=\"center\"><div style=\"padding:10px\"><a href=\"".$h."a/?action=news&op=add\"><strong>".$lang[294]."</strong></a></div></td></tr></table></td></tr></table></div>";
		if ($total_rows>=$c['count_adv_on_index']){
			if(@$_GET['op']=="new")$subGet="&op=new";	
			$a="<a href=\"?action=news".@$subGet."&page=";
			if($page!=1)$pervpage=$a."1\" title=\"".$lang[174]."\">&nbsp;&nbsp;&nbsp;&#171;&nbsp;&nbsp;&nbsp;</a> ";
			if($page!=$total) $nextpage=$a.$total."\" title=\"".$lang[175]."\">&nbsp;&nbsp;&nbsp;&#187;&nbsp;&nbsp;&nbsp;</a>";		
			$pageleft="";$pageright="";
			for($i=$c['limit_pagination_on_page'];$i>=1;$i--)if($page-$i>0)$pageleft.=$a.($page-$i)."\">".($page-$i)."</a>";
			for($i=1;$i<=$c['limit_pagination_on_page'];$i++)if($page+$i<=$total)$pageright.=$a.($page+$i)."\">".($page+$i)."</a>"; 
			echo "<div class=\"pagination\">".@$pervpage.@$pageleft."<b><span class=\"current\">".$page."</span></b>".@$pageright.@$nextpage."</div>";
		}
	}
}
?>