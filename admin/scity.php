<?
if(!defined('SITE'))die();
if(@$_GET['op']=="add_city"){
	if(@$_POST['city_name']){
		if(ctype_digit(@$_POST['id_city'])>="1")$root_city=$_POST['id_city'];
		else die("<center><strong>".$lang[98]." ".$lang[81].$lang[173]."</strong></center>");
		$city_name=trim($_POST['city_name']);
		$city_name=clean($city_name);
		if(@$_POST['en_city_name'])$en_city_name=trim($_POST['en_city_name']);
		else $en_city_name=ru2en($city_name);
		$en_city_name=utf8_ucfirst(clean($en_city_name));
		if(ctype_digit(@$_POST['sort_index']))$sort_index=$_POST['sort_index'];
		else $sort_index=0;
		$query=mysql_query("INSERT jb_city SET parent='".$root_city."', city_name='".$city_name."', en_city_name='".$en_city_name."', sort_index='".$sort_index."'");cq(); 
		if($query)echo "<center><strong>".$lang[400]."</strong></center>"; 
		else die("<center><strong>".$lang[76]."</strong></center>");
	}else{
		echo $lang[83]."<div align=\"center\"><form enctype=\"multipart/form-data\" action=\"".$h."a/?action=city&op=add_city\" method=\"post\"><table><tr><td>".$lang[78].": *<br /><input type=\"text\" size=\"50\" name=\"city_name\"><br /><br />".$lang[1047]."<br /><font size=1>(".$lang[1048].")</font>:<br /><input type=\"text\" size=\"50\" name=\"en_city_name\"><br /><br />".$lang[81].":<br />";
		echo "<select name=\"id_city\"><option style=\"font-weight:bold;color:#FF0033\" value=\"0\">".$lang[82]."</option>";
		$cityes=mysql_query("SELECT id,city_name,en_city_name FROM jb_city WHERE parent=0 ORDER by sort_index");cq();
		if(defined('JBLANG')&& constant('JBLANG')=='en')$firstname='en_city_name';else $firstname='city_name';
		while($city=mysql_fetch_assoc($cityes)){
			echo "<option value=\"".$city['id']."\" style=\"font-weight:bold;\">".$city[$firstname]."</option>";
			$subcityes=mysql_query("SELECT id,city_name,en_city_name FROM jb_city WHERE parent=".$city['id']." ORDER by sort_index");cq();
			if(mysql_num_rows($subcityes)){
				while($subcity=mysql_fetch_assoc($subcityes)){
					echo "<option value=\"".$subcity['id']."\"> &nbsp; &nbsp; &nbsp; &nbsp; - ".$subcity[$firstname]."</option>";
				}
			}
		}
		echo "</select><br /><br />".$lang[802].": <br /><input type=\"text\" size=\"50\" name=\"sort_index\"><br /><br /><input name=\"submit\" type=\"submit\" value=\"".$lang[59]."\"></td></tr></table></form></div>";
	}
}
elseif(@$_GET['op']=="edit_city" && ctype_digit($_GET['id_city'])){
	if(@$_POST['city_name']){
		if(ctype_digit(@$_POST['id_city'])>="1")$root_city=$_POST['id_city'];
		else die($lang[98]." ".$lang[81].$lang[173]);
		if($_GET['id_city']==$root_city)die("<center><strong>".$lang[98]." ".$lang[670].$lang[173]."</strong></center>");
		$city_name=trim($_POST['city_name']);
		$city_name=clean($city_name);
		if(@$_POST['en_city_name'])$en_city_name=trim($_POST['en_city_name']);
		else $en_city_name=ru2en($city_name);
		$en_city_name=utf8_ucfirst(clean($en_city_name));
		if(ctype_digit(@$_POST['sort_index']))$sort_index=$_POST['sort_index'];
		else $sort_index=0;
		$query=mysql_query("UPDATE jb_city SET parent='".$root_city."', city_name='".$city_name."', en_city_name='".$en_city_name."', sort_index='".$sort_index."' WHERE id='".$_GET['id_city']."' LIMIT 1");cq(); 
		if($query)echo "<center><strong>".$lang[400]."</strong></center>";
		else die("<center><strong>".$lang[76]."</strong></center>");
	}else{
		$query=mysql_query("SELECT * FROM jb_city WHERE id='".intval($_GET['id_city'])."' LIMIT 1");cq();
		$data=mysql_fetch_assoc($query);
		echo $lang[83]."<div align=\"center\"><form enctype=\"multipart/form-data\" action=\"".$h."a/?action=city&op=edit_city&id_city=".$_GET['id_city']."\" method=\"post\"><table><tr><td>".$lang[78].": *<br /><input type=\"text\" size=\"50\" name=\"city_name\" value=\"".$data['city_name']."\"><br /><br />".$lang[1047]."<br /><font size=1>(".$lang[1048].")</font>:<br /><input type=\"text\" size=\"50\" name=\"en_city_name\" value=\"".$data['en_city_name']."\"><br /><br />".$lang[81].":<br />";
		echo "<select name=\"id_city\"><option style=\"font-weight:bold;color:#FF0033\" value=\"0\">".$lang[82]."</option>";
		$cityes=mysql_query("SELECT id,city_name,en_city_name FROM jb_city WHERE parent=0 ORDER by sort_index");cq();
		if(defined('JBLANG')&& constant('JBLANG')=='en')$firstname='en_city_name';else $firstname='city_name';
		while($city=mysql_fetch_assoc($cityes)){
			if($city['id']==$data['parent'])$selected="selected";else $selected="";
			echo "<option value=\"".$city['id']."\" style=\"font-weight:bold;\" ".$selected.">".$city[$firstname]."</option>";
			$subcityes=mysql_query("SELECT id,city_name,en_city_name FROM jb_city WHERE parent=".$city['id']." ORDER by sort_index");cq();
			if(mysql_num_rows($subcityes)){
				while($subcity=mysql_fetch_assoc($subcityes)){
					if($subcity['id']==$data['parent'])$selected="selected";else $selected="";
					echo "<option value=\"".$subcity['id']."\" ".$selected."> &nbsp; &nbsp; &nbsp; &nbsp; - ".$subcity[$firstname]."</option>";
				}
			}
		}
		echo "</select><br /><br />".$lang[802].": <br /><input type=\"text\" size=\"50\" name=\"sort_index\" value=\"".$data['sort_index']."\"><br /><br /><input name=\"submit\" type=\"submit\" value=\"".$lang[59]."\"></td></tr></table></form></div>";
	}
}
elseif(@$_GET['op']=="del_city" && ctype_digit(@$_GET['id_city']) && @$_GET['id_city']!="1"){
	$count=mysql_query("SELECT id FROM jb_city WHERE parent='".$_GET['id_city']."'");
	if(@mysql_num_rows($count))die("<center><strong>".$lang[61]."</strong></center>");
	$name=mysql_query("SELECT city_name FROM jb_city WHERE id=1");cq();
	if(!@mysql_num_rows($name))die("<center><strong>".$lang[98]."</strong></center>");
	else{
		$cityname=mysql_fetch_assoc($name);
		mysql_query("DELETE FROM jb_city WHERE id='".$_GET['id_city']."'") or die(mysql_error());cq();
		mysql_query("UPDATE jb_board SET city='".$cityname['city_name']."', city_id=1 WHERE city_id='".$_GET['id_city']."'") or die(mysql_error());cq();
		echo "<center><strong>".$lang[400]."</strong></center>";
	}
}
elseif(@$_GET['op']=="sort" && ctype_digit($_GET['id_city'])){
	if(@$_POST['city']){
		foreach ($_POST['city'] as $k=>$v){
			if(@$v=="")$v=0;
			mysql_query("UPDATE jb_city SET sort_index='".$v."' WHERE id='".$k."' LIMIT 1")or die(mysql_error());cq();
		}
		echo "<center><strong>".$lang[400]."</strong></center>";
	}else{
		$city_name=(defined('JBLANG') && constant('JBLANG')=='en')?'en_city_name':'city_name';
		$nmcat=mysql_query("SELECT ".$city_name." FROM jb_city WHERE id='".$_GET['id_city']."'");cq();
		$nmrootcat=mysql_fetch_assoc($nmcat);
		$titlecat=($_GET['id_city']=="0")?$lang[82]:$nmrootcat[$city_name];
		echo "<div align=\"center\"><h1>".$titlecat."</h1><br /><br /><form method=\"post\" action=\"".$h."a/?action=city&op=sort&id_city=".$_GET['id_city']."\"><table><tr><td bgcolor=\"#E5E5E5\" align=\"center\"><table class=\"sort\"><tr bgcolor=\"#F6F6F6\"><td align=\"center\"><strong>#ID</strong></td><td align=\"center\"><strong>".$lang[78]."</strong></td><td align=\"center\"><strong>".$lang[802]."</strong></td></tr>";		
		$cityes=mysql_query("SELECT * FROM jb_city WHERE parent='".$_GET['id_city']."' ORDER by sort_index");cq();
		while($city=mysql_fetch_assoc($cityes)){
			if(defined('JBLANG')&& constant('JBLANG')=='en')$firstname='en_city_name';else $firstname='city_name';
			echo "<tr bgcolor=\"#FFFFFF\"><td>".$city['id']."</td><td>".$city[$firstname]."</td><td><input type=\"text\" size=\"4\" name=\"city[".$city['id']."]\" value=\"".$city['sort_index']."\" /></td></tr>";		
			if(@$_GET['id_city']!="0"){
				$subcityes=mysql_query("SELECT * FROM jb_city WHERE parent=".$city['id']." ORDER by sort_index");cq();
				if(mysql_num_rows($subcityes)){
					while($subcity=mysql_fetch_assoc($subcityes)){
						echo "<tr bgcolor=\"#FFFFFF\"><td>".$subcity['id']."</td><td>".$subcity[$firstname]."</td><td><input type=\"text\" size=\"4\" name=\"city[".$subcity['id']."]\" value=\"".$subcity['sort_index']."\" /></td></tr>";		
					}
				}
			}
		}
		echo "</table><input name=\"submit\" type=\"submit\" value=\"".$lang[59]."\"><br /><br /</td></tr></table></form></div>";
	}
}
else{
	echo "<div align=\"center\"><table><tr><td bgcolor=\"#E5E5E5\"><table class=\"sort\"><tr bgcolor=\"#FFFFFF\"><td colspan=\"9\" align=\"center\"><div style=\"padding:10px\"><a href=\"".$h."a/?action=city&op=sort&id_city=0\"><strong>".$lang[1049]."</strong></a> &nbsp; <a href=\"".$h."a/?action=city&op=add_city\"><strong>".$lang[242]."</strong></a></div></td></tr><tr bgcolor=\"#F6F6F6\"><td align=\"center\">".$lang[78]."</td><td align=\"center\">".$lang[1047]."</td><td align=\"center\">".$lang[802]."</td><td colspan=\"3\" align=\"center\">".$lang[126]."</td></tr>";
	$cityes=mysql_query("SELECT * FROM jb_city WHERE parent=0 ORDER by sort_index");cq();
	while($city=mysql_fetch_assoc($cityes)){
		if(defined('JBLANG')&& constant('JBLANG')=='en'){$firstname='en_city_name';$secondname='city_name';}
		else{$firstname='city_name';$secondname='en_city_name';}
		echo "<tr bgcolor=\"#FFFFFF\"><td><strong class=\"red large\">".$city[$firstname]."</strong></td><td><span class=\"gray\">".$city[$secondname]."</span></td><td align=\"center\">".$city['sort_index']."</td><td align=\"center\"><a href=\"../a/?action=city&op=sort&id_city=".$city['id']."\"><img src=\"../images/sortcat.png\" alt=\"".$lang[1050]."\" title=\"".$lang[1050]."\" /></a></td><td align=center><a href=\"".$h."a/?action=city&op=edit_city&id_city=".$city['id']."\"><img src=\"".$im."edit.gif\" alt=\"".$lang[12]."\" title=\"".$lang[12]."\"></a></td><td align=center><a href=\"".$h."a/?action=city&op=del_city&id_city=".$city['id']."\" onclick='return conformdelete(this,confirmmess);'><img src=\"".$im."del.gif\" alt=\"".$lang[300]."\" title=\"".$lang[300]."\"></a></td></tr>";
		$subcityes=mysql_query("SELECT * FROM jb_city WHERE parent=".$city['id']." ORDER by sort_index");cq();
		if(mysql_num_rows($subcityes)){
			while($subcity=mysql_fetch_assoc($subcityes)){
				echo "<tr bgcolor=\"#FFFFFF\"><td> &nbsp; &nbsp; ".$subcity[$firstname]."</td><td><span class=\"gray\">".$subcity[$secondname]."</span></td><td align=\"center\">".$subcity['sort_index']."</td><td></td><td align=center><a href=\"".$h."a/?action=city&op=edit_city&id_city=".$subcity['id']."\"><img src=\"".$im."edit.gif\" alt=\"".$lang[12]."\" title=\"".$lang[12]."\"></a></td><td align=center><a href=\"".$h."a/?action=city&op=del_city&id_city=".$subcity['id']."\" onclick='return conformdelete(this,confirmmess);'><img src=\"".$im."del.gif\" alt=\"".$lang[300]."\" title=\"".$lang[300]."\"></a></td></tr>";
			}
		}
	}
	echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"9\" align=\"center\"><div style=\"padding:10px\"><a href=\"".$h."a/?action=city&op=sort&id_city=0\"><strong>".$lang[1049]."</strong></a> &nbsp; <a href=\"".$h."a/?action=city&op=add_city\"><strong>".$lang[242]."</strong></a></div></td></tr></table></td></tr></table></div>";
}
?>