<?
if(!defined('SITE')) die();
function reqcat($id,$sub,$get_id=""){
$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat';
$queryc=mysql_query("SELECT * FROM jb_board_cat WHERE root_category='".$id."' ORDER by sort_index"); cq();
if(@mysql_num_rows($queryc)){
while($squeryc=mysql_fetch_assoc($queryc)){
if($squeryc['child_category']!=0 || $sub==0)$optstyle=" style=\"font-weight:bold\" ";else $optstyle="";
if($get_id!="" && $get_id==$squeryc['id'])$tag_selected=" selected ";else $tag_selected="";
echo "<option ".$optstyle." value=\"".$squeryc['id']."\" ".$tag_selected.">";
for($i=0;$i<$sub;$i++){echo "&nbsp;-&nbsp;";}
echo $squeryc[$name_cat]."</option>";
if($squeryc['child_category']!=0)reqcat($squeryc['id'],$sub+1,$get_id);
}}}
if(@$_GET['op']=="add_category"){
	if(@$_POST['name_cat'] && @$_POST['description']){
		if($_POST['name_cat']=="")die("<center><strong>".$lang[72]."</strong></center>");
		if($_POST['description']=="")die("<center><strong>".$lang[73]."</strong></center>");
		if(ctype_digit(@$_POST['id_category'])>=0)$root_category=$_POST['id_category'];
		else die("<center><strong>".$lang[98]." ".$lang[537].$lang[173]."</strong></center>");
		$child_category=0;
		$name_cat=trim($_POST['name_cat']);
		$name_cat=clean($name_cat);
		$description=trim($_POST['description']);
		$description=clean($description);
		if(@$_POST['en_name_cat'])$en_name_cat=trim($_POST['en_name_cat']);
		else $en_name_cat=translit($name_cat);
		$en_name_cat=clean($en_name_cat);
		if(ctype_digit(@$_POST['sort_index']))$sort_index=$_POST['sort_index'];
		else $sort_index=0;
		$query=mysql_query("INSERT jb_board_cat SET root_category='".$root_category."', child_category='".$child_category."', name_cat='".$name_cat."', en_name_cat='".$en_name_cat."', description='".$description."', sort_index='".$sort_index."'");cq(); 
		if($query){
			if($_FILES['logo']){
				$last_id=mysql_insert_id();
				$die_del_mess="DELETE FROM jb_board_cat WHERE id='".$last_id."' LIMIT 1";
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
						$vname=$name_cat;
						$filename=utf8_substr(translit($vname),0,128);
						$filename=$filename."_".$file_id.".".$ext;

						if(!@img_resize($_FILES['logo']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$filename,$c['width_cat_images'],0,0,$ext,$size[1],$size[0])){
							if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$filename);
							mysql_query($die_del_mess);cq();
							die("<center><strong>".$lang[411]." ".$_FILES['logo']['name']." ".$lang[227].$lang[173]."</strong></center>");
						}
						$update=mysql_query("UPDATE jb_board_cat SET img='".$filename."' WHERE id='".$last_id."' LIMIT 1");cq();
						if(!$update){
							if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$filename);
							mysql_query($die_del_mess); cq();
							die("<center><strong>".$lang[411]." ".$_FILES['logo']['name']." ".$lang[227].$lang[173]."</strong></center>");
						}
					}else{
						mysql_query($die_del_mess);cq();
						die("<center><strong>".$lang[641]." ".$_FILES['logo']['name']." ".$lang[642].$lang[173]."</strong></center>");
					}
				} 
			}
			mysql_query("UPDATE jb_board_cat SET child_category=1 WHERE id='".$root_category."' LIMIT 1");cq();
			echo "<center><strong>".$lang[400]."</strong></center>";
		}else die("<center><strong>".$lang[76]."</strong></center>");
	}else{
		echo $lang[83]."<div align=\"center\"><form enctype=\"multipart/form-data\" action=\"".$h."a/?action=category&op=add_category\" method=\"post\"><table><tr><td>".$lang[78].": *<br /><input type=\"text\" size=\"50\" name=\"name_cat\"><br /><br />".$lang[1047]."<br /><font size=1>(".$lang[1048].")</font>:<br /><input type=\"text\" size=\"50\" name=\"en_name_cat\"><br /><br />".$lang[79].": *<br /><input type=\"text\" size=\"50\" name=\"description\"><br /><br />".$lang[80].":<br /><input size=\"39\" type=\"file\" name=\"logo\"><br /><br />".$lang[81].":<br /><select name=\"id_category\"><option style=\"font-weight:bold;color:#FF0033\" value=\"0\">".$lang[82]."</option>";
		reqcat(0,0);
		echo "</select><br /><br />".$lang[802].": <br /><input type=\"text\" size=\"50\" name=\"sort_index\"><br /><br /><input name=\"submit\" type=\"submit\" value=\"".$lang[59]."\"></td></tr></table></form></div>";
	}
}
elseif(@$_GET['op']=="edit_category" && ctype_digit($_GET['id_cat'])){
	if(@$_POST['name_cat'] && @$_POST['description']){
		if($_POST['name_cat']=="")die("<center><strong>".$lang[72]."</strong></center>");
		if($_POST['description']=="")die("<center><strong>".$lang[73]."</strong></center>");
		if(ctype_digit(@$_POST['id_category'])>=0)$root_category=$_POST['id_category'];
		else die("<center><strong>".$lang[98]." ".$lang[537].$lang[173]."</strong></center>");
		if($_GET['id_cat']==$root_category) die("<center><strong>".$lang[98]." ".$lang[670].$lang[173]."</strong></center>");
		$child_category=0;
		$name_cat=trim($_POST['name_cat']);
		$name_cat=clean($name_cat);
		$description=trim($_POST['description']);
		$description=clean($description);
		if(@$_POST['en_name_cat'])$en_name_cat=trim($_POST['en_name_cat']);
		else $en_name_cat=translit($name_cat);
		$en_name_cat=clean($en_name_cat);
		if(ctype_digit(@$_POST['sort_index']))$sort_index=$_POST['sort_index'];
		else $sort_index=0;
		$oldrootcategory=mysql_query("SELECT root_category FROM jb_board_cat WHERE id='".$_GET['id_cat']."' LIMIT 1");cq();
		$old_root_category=mysql_fetch_assoc($oldrootcategory);
		$oldroot=mysql_query("SELECT id FROM jb_board_cat WHERE root_category='".$old_root_category['root_category']."'");cq(); //
		$count_oldroot=mysql_num_rows($oldroot);
		if($count_oldroot<=1)mysql_query("UPDATE jb_board_cat SET child_category=0 WHERE id='".$old_root_category['root_category']."' LIMIT 1");cq();
		mysql_query("UPDATE jb_board_cat SET child_category=1 WHERE id='".$root_category."' LIMIT 1");cq();
		$query=mysql_query("UPDATE jb_board_cat SET root_category='".$root_category."', name_cat='".$name_cat."', en_name_cat='".$en_name_cat."', description='".$description."', sort_index='".$sort_index."' WHERE id='".$_GET['id_cat']."' LIMIT 1");cq(); 
		if($query){
			if(@$_FILES['logo']){
				$last_id=$_GET['id_cat'];
				if($_FILES['logo']['error']==0 && $_FILES['logo']['size']>0){
					$size=getimagesize($_FILES["logo"]["tmp_name"]);
					if($size['mime']=="image/gif")$ext="gif";
					elseif($size['mime']=="image/jpeg")$ext="jpeg";
					elseif($size['mime']=="image/png")$ext="png";
					else die("<center><strong>".$lang[226]." ".$_FILES['logo']['name']." ".$lang[227].$lang[173]."</strong></center>");
					if($_FILES['logo']['size'] < $c['upl_image_size']){
						$file_id=$last_id;
						$vname=$name_cat;
						$filename=utf8_substr(translit($vname),0,128);
						$filename=$filename."_".$file_id.".".$ext;
						if(!@img_resize($_FILES['logo']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$filename,$c['width_cat_images'],0,0,$ext,$size[1],$size[0])){
							if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$filename);
							die("<center><strong>".$lang[411]." ".$_FILES['logo']['name']." ".$lang[227].$lang[173]."</strong></center>");
						}
						$update=mysql_query("UPDATE jb_board_cat SET img='".$filename."' WHERE id='".$last_id."' LIMIT 1");cq();
						if(!$update){
							if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$filename);
							die("<center><strong>".$lang[411]." ".$_FILES['logo']['name']." ".$lang[227].$lang[173]."</strong></center>");
						}
					}else die("<center><strong>".$lang[641]." ".$_FILES['logo']['name']." ".$lang[642].$lang[173]."</strong></center>");
				} 
			}
			echo "<center><strong>".$lang[400]."</strong></center>";
		}else die("<center><strong>".$lang[76]."</strong></center>");
	}else{
		$query=mysql_query("SELECT * FROM jb_board_cat WHERE id='".intval($_GET['id_cat'])."' LIMIT 1");cq();
		$data=mysql_fetch_assoc($query);
		echo $lang[85]."<div align=\"center\"><form enctype=\"multipart/form-data\" action=\"".$h."a/?action=category&op=edit_category&id_cat=".$_GET['id_cat']."\" method=\"post\"><table><tr><td>".$lang[78].": *<br /><input type=\"text\" size=\"50\" name=\"name_cat\" value=\"".$data['name_cat']."\"><br /><br />".$lang[1047]."<br /><font size=1>(".$lang[1048].")</font>:<br /><input type=\"text\" size=\"50\" name=\"en_name_cat\" value=\"".$data['en_name_cat']."\"><br /><br />".$lang[79].": *<br /><input type=\"text\" size=\"50\" name=\"description\" value=\"".$data['description']."\"><br /><br />";
		if(@$data['img']) echo "<img src=\"".$u."cat/".$data['img']."\"> <a href=\"".$h."a/?action=category&op=del_image&id_cat=".$_GET['id_cat']."\" onclick=\"return conformdelete(this,confirmmess);\"><img src=\"".$im."image_remove.png\" alt=\"".$lang[107]."\" title=\"".$lang[107]."\"></a><br /><br />";
		else echo $lang[80].":<br /><input size=\"39\" type=\"file\" name=\"logo\"><br /><br />";
		echo $lang[81].":<br /><select name=\"id_category\"><option style=\"font-weight:bold;color:#FF0033\" value=\"0\">".$lang[82]."</option>";
		reqcat(0,0,$data['root_category']);
		echo "</select><br /><br />".$lang[802].": <br /><input type=\"text\" size=\"50\" name=\"sort_index\" value=\"".$data['sort_index']."\"><br /><br /><input name=\"submit\" type=\"submit\" value=\"".$lang[59]."\"></td></tr></table></form></div>";
	}
}
elseif(@$_GET['op']=="del_image" && ctype_digit($_GET['id_cat'])){
	$icon=mysql_query("SELECT img FROM jb_board_cat WHERE id='".$_GET['id_cat']."'");
	if(mysql_num_rows($icon)){
		$caticon=mysql_fetch_assoc($icon);
		if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$caticon['img']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$caticon['img']);
		$icon_update=mysql_query("UPDATE jb_board_cat set img='' WHERE id='".$_GET['id_cat']."' LIMIT 1");
		echo($icon_update)?"<center><strong>".$lang[63]."</strong></center>":die("<center><strong>".$lang[64]."</strong></center>");
	}
}
elseif(@$_GET['op']=="del_category" && ctype_digit($_GET['id_cat'])){
	$count=mysql_query("SELECT id FROM jb_board_cat WHERE root_category='".$_GET['id_cat']."'");
	if(@mysql_num_rows($count))die("<center><strong>".$lang[61]."</strong></center>");
	else{
		$ads=mysql_query("SELECT id FROM jb_board WHERE id_category='".$_GET['id_cat']."'");
		if(mysql_num_rows($ads)){
			while($listads=mysql_fetch_assoc($ads)){
				$p_del=mysql_query("SELECT id_photo,photo_name FROM jb_photo WHERE id_message='".$listads['id']."'");   
				if(@mysql_num_rows($p_del)){
					while($list=mysql_fetch_assoc($p_del)){
						if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$list['photo_name']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$list['photo_name']);
						if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$list['photo_name']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$list['photo_name']);
					}
					mysql_query("DELETE FROM jb_photo WHERE id_message='".$listads['id']."'");
				}
				mysql_query("DELETE FROM jb_abuse WHERE id_board='".$listads['id']."'");
				mysql_query("DELETE FROM jb_comments WHERE id_board='".$listads['id']."'");
				mysql_query("DELETE FROM jb_notes WHERE id_board='".$listads['id']."'");
			}
			mysql_query("DELETE FROM jb_board WHERE id_category='".$_GET['id_cat']."'");
		}
		$datac=mysql_query("SELECT root_category, img FROM jb_board_cat WHERE id='".$_GET['id_cat']."'");
		$dcat=mysql_fetch_assoc($datac);
		if(@$dcat['img'] && file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$dcat['img']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/cat/".$dcat['img']);
		if(mysql_query("DELETE FROM jb_board_cat WHERE id='".$_GET['id_cat']."' LIMIT 1")){
			$query_root=mysql_query("SELECT id FROM jb_board_cat WHERE root_category='".$dcat['root_category']."'");
			$count_query_root=mysql_num_rows($query_root);
			if($count_query_root==0)mysql_query("UPDATE jb_board_cat SET child_category=0 WHERE id='".$dcat['root_category']."' LIMIT 1");cq();
			echo "<center><strong>".$lang[400]."</strong></center>";
		}else die("<center><strong>".$lang[64]."</strong></center>");
	}
	if($c['cache_clear']=="auto"){
		$dirname="../cache/";
		$dir=opendir($dirname);
		while($file=readdir($dir)){
			if($file!="." && $file!=".." && $file!=".htaccess" && (utf8_substr($file,0,8) == "newlist-" || $file=="clouds_tags" || $file=="kaleidoscope" || $file=="stat" || (utf8_substr($file,0,(utf8_strlen($_GET['id_cat'])+2)) == "c".$_GET['id_cat']."-")))unlink($dirname.$file);
		} closedir ($dir);
	}
}
elseif(@$_GET['op']=="sort" && ctype_digit($_GET['id_cat'])){
	if(@$_POST['cat']){
		foreach ($_POST['cat'] as $k=>$v){
			if(@$v=="")$v=0;
			mysql_query("UPDATE jb_board_cat SET sort_index='".$v."' WHERE id='".$k."' LIMIT 1")or die(mysql_error());cq();
		}
		echo "<center><strong>".$lang[400]."</strong></center>";
	}else{
		$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat';
		$nmcat=mysql_query("SELECT ".$name_cat." FROM jb_board_cat WHERE id='".$_GET['id_cat']."'");cq();
		$nmrootcat=mysql_fetch_assoc($nmcat);
		$titlecat=($_GET['id_cat']=="0")?$lang[82]:$nmrootcat[$name_cat];
		echo "<div align=\"center\"><h1>".$titlecat."</h1><br /><br /><form method=\"post\" action=\"".$h."a/?action=category&op=sort&id_cat=".$_GET['id_cat']."\"><table><tr><td bgcolor=\"#E5E5E5\" align=\"center\"><table class=\"sort\"><tr bgcolor=\"#F6F6F6\"><td align=\"center\"><strong>#ID</strong></td><td align=\"center\"><strong>".$lang[122]."</strong></td><td align=\"center\"><strong>".$lang[802]."</strong></td></tr>";
		$categories=mysql_query("SELECT id, ".$name_cat.", sort_index FROM jb_board_cat WHERE root_category='".$_GET['id_cat']."' ORDER by sort_index");cq();
		while($category=mysql_fetch_assoc($categories)){
			echo "<tr bgcolor=\"#FFFFFF\"><td>".$category['id']."</td><td>".$category[$name_cat]."</td><td><input type=\"text\" size=\"4\" name=\"cat[".$category['id']."]\" value=\"".$category['sort_index']."\" /></td></tr>";			
		}
		echo "</table><input name=\"submit\" type=\"submit\" value=\"".$lang[59]."\"><br /><br /</td></tr></table></form></div>";
	}
}
else{
	echo "<div align=\"center\"><table><tr><td bgcolor=\"#E5E5E5\"><table class=\"sort\"><tr bgcolor=\"#FFFFFF\"><td colspan=\"9\" align=\"center\"><div style=\"padding:10px\"><a href=\"".$h."a/?action=category&op=sort&id_cat=0\"><strong>".$lang[1044]."</strong></a> &nbsp; <a href=\"".$h."a/?action=category&op=add_category\"><strong>".$lang[67]."</strong></a></div></td></tr><tr bgcolor=\"#F6F6F6\"><td align=\"center\">".$lang[122]."</td><td align=\"center\">".$lang[1047]."</td><td align=\"center\">".$lang[79]."</td><td align=\"center\">".$lang[802]."</td><td align=\"center\">".$lang[425]."</td><td colspan=\"4\" align=\"center\">".$lang[126]."</td></tr>";	
	$GLOBALS['lang89']=$lang[89];
	$GLOBALS['lang1046']=$lang[1046];
	$GLOBALS['lang1045']=$lang[1045];
	$GLOBALS['lang12']=$lang[12];
	$GLOBALS['lang300']=$lang[300];
	function listcat2($id,$sub){
		$subcategories=mysql_query("SELECT * FROM jb_board_cat WHERE root_category=$id ORDER by sort_index"); cq(); 
		while($subcategory=mysql_fetch_assoc($subcategories)){	
			if(defined('JBLANG')&& constant('JBLANG')=='en'){$firstnamecat=$subcategory['en_name_cat'];$secondnamecat=$subcategory['name_cat'];}
			else{$firstnamecat=$subcategory['name_cat'];$secondnamecat=$subcategory['en_name_cat'];}
			if($sub=="2") $subclass="class=\"subclass\"";else $subclass="";
			echo "<tr bgcolor=\"#FFFFFF\">";
			echo "<td>";
			for($i=0;$i<$sub;$i++)echo "&nbsp; &nbsp; &nbsp;";
			$sp="strong";
			if($subcategory['child_category']==0){
				echo "<a href=\"../a/?action=ads&id_category=".$subcategory['id']."\"><img src=\"../images/viewadsfromcat.png\" alt=\"".$GLOBALS['lang1046']."\" title=\"".$GLOBALS['lang1046']."\" class=\"absmid\" /></a> ";
				$sp="span";
			}
			echo "<".$sp." ".$subclass.">".$firstnamecat."</".$sp."></td><td><span class=\"gray\">".$secondnamecat."</span></td><td>".$subcategory['description']."</td><td align=\"center\">".$subcategory['sort_index']."</td><td align=\"center\">";
			echo (@$subcategory['img'])?"<img src=\"../upload/cat/".$subcategory['img']."\">":"";
			echo "</td><td align=\"center\">";
			echo (@$subcategory['img'])?"<a href=\"../a/?action=category&op=del_image&id_cat=".$subcategory['id']."\" onclick='return conformdelete(this,confirmmess);'><img src=\"../images/image_remove.png\" alt=\"".$GLOBALS['lang89']."\" title=\"".$GLOBALS['lang89']."\" /></a>":"";
			echo "</td><td align=\"center\">";
			echo ($subcategory['child_category']!=0)?"<a href=\"../a/?action=category&op=sort&id_cat=".$subcategory['id']."\"><img src=\"../images/sortcat.png\" alt=\"".$GLOBALS['lang1045']."\" title=\"".$GLOBALS['lang1045']."\" /></a>":"";
			echo "</td><td align=center><a href=\"../a/?action=category&op=edit_category&id_cat=".$subcategory['id']."\"><img src=\"../images/edit.gif\" alt=\"".$GLOBALS['lang12']."\" title=\"".$GLOBALS['lang12']."\"></a></td><td align=center><a href=\"../a/?action=category&op=del_category&id_cat=".$subcategory['id']."\" onclick='return conformdelete(this,confirmmess);'><img src=\"../images/del.gif\" alt=\"".$GLOBALS['lang300']."\" title=\"".$GLOBALS['lang300']."\"></a></td></tr>";
			if($subcategory['child_category']==1){listcat2($subcategory['id'],$sub+1);}
		}
	}
	$categories=mysql_query("SELECT * FROM jb_board_cat WHERE root_category=0 ORDER by sort_index");cq();
	while($category=mysql_fetch_assoc($categories)){
		echo "<tr bgcolor=\"#FFFFFF\">";
		if(defined('JBLANG')&& constant('JBLANG')=='en'){$firstnamecat=$category['en_name_cat'];$secondnamecat=$category['name_cat'];}
		else{$firstnamecat=$category['name_cat'];$secondnamecat=$category['en_name_cat'];}
		echo "<td><strong class=\"red large\">".$firstnamecat."</strong></td><td><span class=\"gray\">".$secondnamecat."</span></td><td>".$category['description']."</td><td align=\"center\">".$category['sort_index']."</td><td align=\"center\">";echo (@$category['img'])?"<img src=\"".$u."cat/".$category['img']."\">":"";
		echo "</td><td align=\"center\">";
		echo (@$category['img'])?"<a href=\"".$h."a/?action=category&op=del_image&id_cat=".$category['id']."\"  onclick='return conformdelete(this,confirmmess);'><img src=\"".$im."image_remove.png\" alt=\"".$lang[89]."\" title=\"".$lang[89]."\" /></a>":"";
		echo "</td><td align=\"center\">";
		echo ($category['child_category']!=0)?"<a href=\"".$h."a/?action=category&op=sort&id_cat=".$category['id']."\"><img src=\"".$im."sortcat.png\" alt=\"".$lang[1045]."\" title=\"".$lang[1045]."\" /></a>":"";
		echo "</td><td align=center><a href=\"".$h."a/?action=category&op=edit_category&id_cat=".$category['id']."\"><img src=\"".$im."edit.gif\" alt=\"".$lang[12]."\" title=\"".$lang[12]."\">Edit</a></td><td align=center><a href=\"".$h."a/?action=category&op=del_category&id_cat=".$category['id']."\" onclick='return conformdelete(this,confirmmess);'><img src=\"".$im."del.gif\" alt=\"".$lang[300]."\" title=\"".$lang[300]."\">Delete</a></td></tr>";
		if($category['child_category']==1)listcat2($category['id'],1);
	}
	echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"9\" align=\"center\"><div style=\"padding:10px\"><a href=\"".$h."a/?action=category&op=sort&id_cat=0\"><strong>".$lang[1044]."</strong></a> &nbsp; <a href=\"".$h."a/?action=category&op=add_category\"><strong>".$lang[67]."</strong></a></div></td></tr></table></td></tr></table></div>";
}
?>