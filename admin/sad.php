<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

if(!defined('SITE')) die();
if(@$_GET['op']=="add_mess"){
	if(@$_POST['submit']){
		if(@$_POST['title'])$title=trim($_POST['title']);
		else die("<center><strong>".$lang[94].$lang[173]."</strong></center>");
		if(@$_POST['type']=="p" || @$_POST['type']=="s" || @$_POST['type']=="u" || @$_POST['type']=="o")$type=$_POST['type']; 
		else die("<center><strong>".$lang[620].$lang[173]."</strong></center>");
		if(ctype_digit(@$_POST['id_category'])>0)$id_category=$_POST['id_category'];
		else die("<center><strong>".$lang[98]." ".$lang[537].$lang[173]."</strong></center>");
		$query_root=mysql_query("SELECT child_category FROM jb_board_cat WHERE id='".$id_category."'"); cq(); 
		$data_root_cat=mysql_fetch_assoc($query_root);
		if($data_root_cat['child_category']==1)die("<center><strong>".$lang[537].$lang[173]."</strong></center>");
		if(@$_POST['text'])$text=trim($_POST['text']);
		else die("<center><strong>".$lang[95].$lang[173]."</strong></center>");
		if(@$_POST['autor'])$autor=trim($_POST['autor']);
		else die("<center><strong>".$lang[92].$lang[173]."</strong></center>");
		if(@$_POST['contacts'])$contacts=trim($_POST['contacts']); else $contacts="";
		if(@$_POST['email']){
			$email=trim(utf8_strtolower($_POST['email']));
			if(!preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/iu',$email)){die("<center><strong>".$lang[96].$lang[173]."</strong></center>");}
		} else $email="";
		if(@$_POST['url']){
			$url=trim($_POST['url']); $url=utf8_substr($url,0,$c['count_symb_url']);
			if(preg_match('/[^-a-z0-9_\.\:\/]/iu',$url)){die("<center><strong>".$lang[639].$lang[173]."</strong></center>");}
			$uarr=parse_url($url);$url=(@$uarr[host])?@$uarr[host]:@$uarr[path];
			$url=($url!="")? preg_replace("/(https:\/\/|www\.)/ui","",$url):""; $url=utf8_strtolower($url);
		} else $url="";
		if($c['add_link_to_video']=="yes" || @$_POST['video']){
			$video=$_POST['video'];
			if(utf8_strlen($video)<50 && utf8_strlen($video)>24 && preg_match("/youtube\.com/iu",$video)){
				$video_arr=parse_url($_POST['video']);
				$video_arr2=split("v=",$video_arr['query']);
				unset($video_arr2[0]);
				$video=$video_arr2[1];
			}else $video="";
		}else $video="";
		if(ctype_digit(@$_POST['time_delete'])>0)$time_delete=$_POST['time_delete'];
		else die("<center><strong>".$lang[98].$lang[173]."</strong></center>");
		if(@$_POST['prices']){
			if(ctype_digit(@$_POST['prices'])>0)$prices=$_POST['prices'];
			else die("<center><strong>".$lang[98].$lang[173]."</strong></center>");
		} else $prices="";
		if(@$_POST['tags'])$tags=$_POST['tags'];elseif($c['tags_generate']=="yes")$tags=$title;else $tags="";
		if($tags!=""){
			$keywords=array();
			$tags=preg_replace("/\s+/ums"," ",$tags);
			$tags=preg_replace("/([[:punct:]]|[[:digit:]]|(\s)+)/ui"," ",$tags);
			$arr=explode(" ",$tags);
			for($i=0;$i<count($arr);$i++){
				if(utf8_strlen($arr[$i])>3){$arr[$i]=trim($arr[$i]);$keywords[]=utf8_strtolower($arr[$i]);}
			}
			if(sizeof($keywords)!=0){
				$keywords=array_unique($keywords);shuffle($keywords);$keywords = array_slice($keywords,0,15);
				$tags=implode(', ',$keywords);$tags=clean($tags);
			}else $tags="";
		}
		$title=split_punct($title);
		$title=utf8_substr($title,0,$c['count_symb_title']);
		$title=clean($title);
		if(is_numeric($_POST['city'])){
			if(intval($_POST['city'])>"1"){
				if(@constant('JBLANG')==="en")$qcity="en_city_name";else $qcity="city_name";
				$query_city=mysql_query("SELECT ".$qcity." FROM jb_city WHERE id='".$_POST['city']."'"); cq(); 
				$data_city=mysql_fetch_assoc($query_city);
				$city_id=$_POST['city']; $city=$data_city[$qcity];
			}  else {$city=$lang[164];$city_id=1;}
		} else {$city=$lang[164];$city_id=1;}
		$text=split_punct($text);
		$text=utf8_substr($text,0,$c['count_symb_text']);
		$text=clean($text);
		$autor=utf8_substr($autor,0,$c['count_symb_autor']);
		$autor=clean($autor);
		if($contacts!=""){
			$contacts=split_punct($contacts);
			$contacts=utf8_substr($contacts,0,$c['count_symb_contacts']);
			$contacts=clean($contacts);
		}
		if(@$_POST['checkbox_top']=="1"){$check_top=" checkbox_top=1, top_time=NOW(), ";$check_select="";}
		elseif(@$_POST['checkbox_top']!="1" && @$_POST['checkbox_select']=="1"){$check_top="";$check_select=" checkbox_select=1, select_time=NOW(), ";}
		else{$check_top="";$check_select="";}
		if(@$user_data['activ']=="yes")$us_insert="user_id='".$user_data['id_user']."', ";else $us_insert="";
		$insert=mysql_query("INSERT jb_board SET id_category='".$id_category."', ".$us_insert." type='".$type."', autor='".$autor."', title='".$title."', email='".@$email."', city='".$city."', city_id='".$city_id."', url='".$url."', contacts='".$contacts."', text='".$text."', prices='".$prices."', video='".$video."', old_mess='old', checked='yes', ".$check_select.$check_top." tags='".$tags."', time_delete='".$time_delete."', date_add=NOW()");  cq(); 
		$last_id=mysql_insert_id();
		$die_del_mess="DELETE FROM jb_board WHERE id='".$last_id."' LIMIT 1";
		$die_del_img="DELETE FROM jb_photo WHERE id_message='".$last_id."'";
		if($insert){
			if($_FILES['logo']){
				if($c['upload_images']=="yes"){
					$count_img_in_array=count($_FILES['logo']['name']);
					if($c['count_images_for_users'] <= 5 && $count_img_in_array > $c['count_images_for_users']){
						die("<center><strong>".$lang[222].$lang[173]."</strong></center>");
					}
					for ($i=0;$i<$count_img_in_array;$i++){
						if($_FILES['logo']['error'][$i]==0 && $_FILES['logo']['size'][$i]>0){
							$size=getimagesize($_FILES["logo"]["tmp_name"][$i]);
							if($size['mime']=="image/gif")$ext="gif";
							elseif($size['mime']=="image/jpeg")$ext="jpeg";
							elseif($size['mime']=="image/png")$ext="png";
							else{
								mysql_query($die_del_mess);
								die("<center><strong>".$lang[226]." ".$_FILES['logo']['name'][$i]." ".$lang[227].$lang[173]."</strong></center>");
							}
							if($_FILES['logo']['size'][$i] < $c['upl_image_size']){
								$insert_img=mysql_query("INSERT jb_photo SET id_message='".$last_id."'");cq(); 
								if($insert_img)$file_id=mysql_insert_id();
								else{mysql_query($die_del_mess);die("<center><strong>".$lang[411].$lang[173]."</strong></center>");}
								if($city!=$lang[164])$vname=$city."-";else $vname="";
								$filename=utf8_substr(translit($vname.$title),0,128);
								$filename=$filename."_".$file_id.".".$ext;
								if(!@img_resize($_FILES['logo']['tmp_name'][$i],$_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename,$c['width_small_images'],1,0xFFFFFF,$ext,$size[1],$size[0],"0")){
									mysql_query($die_del_mess);mysql_query ($die_del_img); 
									die("<center><strong>".$lang[411]." ".$_FILES['logo']['name'][$i]." ".$lang[227].$lang[173]."</strong></center>");
								}
								if(!@img_resize($_FILES['logo']['tmp_name'][$i],$_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$filename,$c['width_normal_images'],0,0,$ext,$size[1],$size[0],"1")){
									if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename);
									mysql_query($die_del_mess);mysql_query ($die_del_img); 
									die("<center><strong>".$lang[411]." ".$_FILES['logo']['name'][$i]." ".$lang[227].$lang[173]."</strong></center>");
								}
								$update=mysql_query("UPDATE jb_photo SET photo_name='".$filename."' WHERE id_photo='".$file_id."' AND id_message='".$last_id."' LIMIT 1");  cq(); 
								if(!$update){
									if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename);
									if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$filename);
									mysql_query($die_del_mess);mysql_query($die_del_img); 
									die("<center><strong>".$lang[411]." ".$_FILES['logo']['name'][$i]." ".$lang[227].$lang[173]."</strong></center>");
								}
							}else{
								mysql_query($die_del_mess);
								die("<center><strong>".$lang[641]." ".$_FILES['logo']['name'][$i]." ".$lang[642].$lang[173]."</strong></center>");
							}
						} 
					}
				}else{
					mysql_query($die_del_mess);
					die("<center><strong>".$lang[228]." ".$_FILES['logo']['name'][$i]." ".$lang[642].$lang[173]."</strong></center>");
				}
			}
			if($c['cache_clear']=="auto"){
				$dirname="../cache/";
				$dir=opendir($dirname);
				while($file=readdir($dir)){
					if($file!="." && $file!=".." && $file!=".htaccess" && (utf8_substr($file,0,8) == "newlist-" || $file=="clouds_tags" || $file=="kaleidoscope" || $file=="stat" || (utf8_substr($file,0,(utf8_strlen($id_category)+2)) == "c".$id_category."-")))unlink($dirname.$file);
				} closedir ($dir);
			}
			if(@$_POST['checkbox_top']=="1") mysql_query("INSERT jb_stat_sms SET operator='admin', numb_phone='---', id_board='".$last_id."', date=NOW()");
			elseif(@$_POST['checkbox_top']!="1" && @$_POST['checkbox_select']=="1") mysql_query("INSERT jb_stat_sms SET operator='admin', numb_phone='---', id_board='".$last_id."', date=NOW()");
			echo "<br /><br /><center><h1>".$lang[229]."</h1><br /><center><strong>".$lang[645].": <br /><a href=\"".$h."c".$_POST['id_category']."-".$last_id.".html\">".$h."c".$_POST['id_category']."-".$last_id.".html/</a></strong></center><br />";
		} else {mysql_query($die_del_mess);die("<center><strong>".$lang[572].$lang[173]."</strong></center>");}
	}else{
		?><div class="addform" align="center"><form action="<?=$h?>a/?action=ads&op=add_mess" method="post" enctype="multipart/form-data" name="add_form" onsubmit="return check_fields();"><input type="hidden" name="securityCode" value="0" /><h1 class="alcenter"><?=$lang[637]?></h1><br /><br /><div class="lc"><?=$lang[123]?><span class="req">*</span></div><div class="rc"><input maxlength="<?=$c['count_symb_title']?>" type="text" name="title" size="50" value="<?=htmlspecialchars(@$_POST['title'])?>" /></div><div class="pad"></div><div class="lc"><?=$lang[163]?></div><div class="rc"><?
		if(ctype_digit(@$_POST['city']) || defined('JBCITY')){
			$getcity = (@$_POST['city'])?$_POST['city']:JBCITY;
			if(@constant('JBLANG')==="en")$qcity="en_city_name";else $qcity="city_name";
			$querycity=mysql_query("SELECT ".$qcity." FROM jb_city WHERE id='".$getcity."'");cq();
			$cccity=mysql_fetch_assoc($querycity);
			echo "<div id=\"usercity\"><span class=\"b\">".$cccity[$qcity]."</span> (<a href=\"#\" onclick=\"rootcity('usercity');return false;\">".$lang[15]."</a>)<input type=\"hidden\" name=\"city\" value=\"".$getcity."\" /></div>";
		}else{
		?><select name="city" onchange="selcity(this.value,'resultcity');"><option value="no"><?=$lang[164]?></option><?
		if(@constant('JBLANG')==="en")$qcity="en_city_name";else $qcity="city_name";
		$q_city = mysql_query("SELECT id,".$qcity." FROM jb_city WHERE parent=0 ORDER by sort_index");  cq(); 
		while($city = mysql_fetch_assoc($q_city)){
			echo "<option value=\"".$city['id']."\"";
			if(@$_POST['city'] && $city[$qcity]==$_POST['city']) echo " selected=\"selected\" ";
			echo ">".$city[$qcity]."</option>";
			}
		?></select><?
		}
		?><div id="resultcity"></div></div><div class="pad"></div><div class="lc"><?=$lang[412]?><span class="req">*</span></div><div class="rc"><select name="type"><option value="0"><?=$lang[620]?></option><option value="s" <? if(@$_POST['type']=="s") echo "selected=\"selected\""; ?>><?=$lang[414]?></option><option value="p" <? if(@$_POST['type']=="p") echo "selected=\"selected\""; ?>><?=$lang[413]?></option><option value="u" <? if(@$_POST['type']=="u") echo "selected=\"selected\""; ?>><?=$lang[800]?></option><option value="o" <? if(@$_POST['type']=="o") echo "selected=\"selected\""; ?>><?=$lang[801]?></option></select></div><div class="pad"></div><div class="lc"><?=$lang[122]?><span class="req">*</span></div><div class="rc"><?
		$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat'; 
		if(ctype_digit(@$_POST['id_category']) || ctype_digit(@$_GET['cat'])){
			$getcat = (@$_POST['id_category'])?$_POST['id_category']:$_GET['cat'];
			$querycat=mysql_query("SELECT * FROM jb_board_cat WHERE id='".$getcat."'");cq();
			$category=mysql_fetch_assoc($querycat);
			echo "<div id=\"usercat\"><span class=\"b\">".$category[$name_cat]."</span> (<a href=\"#\" onclick=\"rootcat('usercat');return false;\">".$lang[15]."</a>)<input type=\"hidden\" name=\"id_category\" value=\"".$category['id']."\" /></div>";
		}else{
			?><select name="id_category" onchange="selcat(this.value,'resultcat');"><option value="no" selected="selected"><?=$lang[99]?> &rarr;</option><?
				$query=mysql_query("SELECT * FROM jb_board_cat WHERE root_category = 0 ORDER by sort_index"); cq();
				$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat'; 
				while($category=mysql_fetch_assoc($query)) echo "<option value=\"".$category['id']."\">".$category[$name_cat]." &rarr; </option>";
			?></select><?
		}
		?><div id="resultcat"></div></div><div class="pad"></div><div class="lc"><?=$lang[111]?></div><div class="rc"><select name="time_delete"><option value="7"<? if(is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==7) echo " selected=\"selected\""; ?>>7 <?=$lang[112]?></option><option value="14"<? if(is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==14) echo " selected=\"selected\""; ?>>14 <?=$lang[112]?></option><option value="30"<? if(is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==30) echo " selected=\"selected\""; ?>>30 <?=$lang[112]?></option><option value="60"<? if(is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==60) echo " selected=\"selected\""; ?>>60 <?=$lang[112]?></option><option value="90"<? if(is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==90) echo " selected=\"selected\""; ?>>90 <?=$lang[112]?></option><option value="180"<? if(!@$_POST['time_delete'] || (is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==180)) echo " selected=\"selected\""; ?>>180 <?=$lang[112]?></option><option value="365"<? if(is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==365) echo " selected=\"selected\""; ?>>365 <?=$lang[112]?></option></select></div><div class="pad"></div><div class="lc"><?=$lang[105]?><span class="req">*</span></div><div class="rc"><textarea name="text" rows="6" cols="37"><?=htmlspecialchars(@$_POST['text'])?></textarea></div>
		<div class="pad"></div>
		<div class="lc"><?=$lang[1008]?> (<?=$lang[101010]?>)</div>
		<div class="rc"><input onkeyup="ff2(this)" maxlength="11" type="text" name="prices" size="50" value="<?=htmlspecialchars(@$_POST['prices'])?>" /></div>
		<div class="pad"></div>
		<? 
		if($c['upload_images']=="yes"){
		?><script language="JavaScript" type="text/javascript">
		<!--
		function del(n){var tab=$("tab");if(tab.rows.length==2 && n==0){document.forms["add_form"].reset();return;}
		if(tab.rows.length>2){if(n==0){return;}else if(n==1){tab.tBodies[0].deleteRow(tab.rows.length-2);}
		else{tab.tBodies[0].deleteRow(n.parentNode.parentNode.rowIndex);}}else{return;}}
		function add(){var tab=$("tab");
		var newRow=tab.tBodies[0].insertRow(tab.rows.length-1);var newCell_1=newRow.insertCell(0);newCell_1.style.border="none";
		newCell_1.innerHTML="<span><\/span>";var newfield=document.createElement("input");newfield.setAttribute("type","file");
		newfield.setAttribute("size","35");newfield.setAttribute("name","logo[]");newCell_1.appendChild(newfield);
		newRow.appendChild(newCell_1);var newCell_2=newRow.insertCell(1);var nb_2=document.createElement("input");
		nb_2.setAttribute("type","button");nb_2.setAttribute("value"," — ");nb_2.title="<?=$lang[417]?>";
		nb_2.onclick=function(){del(this);}
		newCell_2.appendChild(nb_2);newRow.appendChild(newCell_2);showIndexfd();}function showIndexfd(){var tab=$("tab");
		for(var i=0;i<tab.rows.length;i++){var fc=tab.rows[i].firstChild; fc.firstChild.innerHTML="";}}
		//-->
		</script>
		<div class="lc"><?=$lang[106]?></div><div class="rc"><?=$lang[110]?><br /><?=$lang[313].($c['upl_image_size']/1000)?>Kb<br /><?
			if($c['count_images_for_users']>=1 && $c['count_images_for_users']<=5){
				for($i=1;$i<=$c['count_images_for_users'];$i++) echo "<input type=\"file\" name=\"logo[]\" /><br />";}
			else echo "<table id=\"tab\" cellpadding=\"3\" cellspacing=\"3\"><tr><td><input size=\"35\" id=\"test\" type=\"file\" name=\"logo[]\" /></td></tr><tr><td align=\"center\"><br /><input type=\"button\" value=\"".$lang[418]."\" onclick=\"add()\" onfocus=\"this.blur()\" /></td></tr></table>";
		?></div><div class="pad"></div><?
		}
        if($c['add_link_to_video']=="yes"){
            ?><div class="lc"><?=$lang[1100]?><br /><img alt="youtube" class="absmid" src="<?=$im?>youtube_icon.png" /><a rel="nofollow" href="https://www.youtube.com/">youtube.com</a></div><div class="rc"><input maxlength="128" type="text" name="video" size="50" value="<?=htmlspecialchars(@$_POST['video'])?>" /><br /><span class="sm gray"><strong class="red"><?=$lang[1101]?></strong><br /><?=$lang[1102]?><strong> https://www.youtube.com/watch?v=.........</strong></span></div><div class="pad"></div><?
        }	
		?><div class="lc"><?=$lang[1009]?></div><div class="rc"><input maxlength="250" type="text" name="tags" size="50" value="<?=htmlspecialchars(@$_POST['tags'])?>" /></div><div class="pad"></div><div class="lc"><?=$lang[623]?><span class="req">*</span></div><div class="rc"><input maxlength="<?=$c['count_symb_autor']?>" type="text" name="autor" size="50" value="<?=htmlspecialchars(@$_POST['autor'])?>" /></div><div class="pad"></div><div class="lc"><?=$lang[196]?></div><div class="rc"><input type="text" name="email" size="50" value="<?
		if(@$_POST['email']) echo htmlspecialchars(@$_POST['email']);
		else if(defined("USER") && @$_SESSION['email']) echo htmlspecialchars($_SESSION['email']);
		?>" /></div><div class="pad"></div><div class="lc"><?=$lang[181]?></div><div class="rc"><textarea name="contacts" rows="4" cols="37"><?=htmlspecialchars(@$_POST['contacts'])?></textarea></div><div class="pad"></div><?
		if($c['add_url']=="yes"){
			?><div class="lc"><?=$lang[625]?>:</div><div class="rc"><input maxlength="<?=$c['count_symb_url']?>" type="text" name="url" size="50" value="<?=htmlspecialchars(@$_POST['url'])?>" /></div><div class="pad"></div><?
		}
		?><div class="lc"><?=$lang[420]?></div><div class="rc"><input type="checkbox" name="checkbox_select" value="1" /></div><div class="pad"></div><div class="lc"><?=$lang[421]?></div><div class="rc"><input type="checkbox" name="checkbox_top" value="1" /></div><div class="pad"></div><div align="center"><strong style="color:#FF0000"><?=$lang[206]?><br /><? if($c['anti_link']=="yes") echo $lang[204]; ?></strong><br /><br /><input name="submit" style="width:70%;" type="submit" value=" <?=$lang[155]?> " /></div></form><br /><br /></div><?
	}
}
elseif(@$_GET['op']=="edit" && ctype_digit($_GET['id_mess'])){
	if(@$_POST['submit']){
		if(@$_POST['title'])$title=trim($_POST['title']);
		else die("<center><strong>".$lang[94].$lang[173]."</strong></center>");
		if(@$_POST['type']=="p" || @$_POST['type']=="s" || @$_POST['type']=="u" || @$_POST['type']=="o")$type=$_POST['type']; 
		else die("<center><strong>".$lang[620].$lang[173]."</strong></center>");
		if(ctype_digit(@$_POST['id_category'])>0)$id_category=$_POST['id_category'];
		else die("<center><strong>".$lang[98].$lang[173]."</strong></center>");
		$query_root=mysql_query("SELECT child_category FROM jb_board_cat WHERE id='".$id_category."'"); cq(); 
		$data_root_cat=mysql_fetch_assoc($query_root);
		if($data_root_cat['child_category']==1)die("<center><strong>".$lang[537].$lang[173]."</strong></center>");
		if(@$_POST['text'])$text=trim($_POST['text']);
		else die("<center><strong>".$lang[95].$lang[173]."</strong></center>");
		if(@$_POST['autor'])$autor=trim($_POST['autor']);
		else die("<center><strong>".$lang[92].$lang[173]."</strong></center>");
		if(@$_POST['contacts'])$contacts=trim($_POST['contacts']); else $contacts="";
		if(@$_POST['email']){
			$email=trim(utf8_strtolower($_POST['email']));
			if(!preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/iu',$email)){die("<center><strong>".$lang[96].$lang[173]."</strong></center>");}
		} else $email="";
		if(@$_POST['url']){
			$url=trim($_POST['url']); $url=utf8_substr($url,0,$c['count_symb_url']);
			if(preg_match('/[^-a-z0-9_\.\:\/]/iu',$url)){die("<center><strong>".$lang[639].$lang[173]."</strong></center>");}
			$uarr=parse_url($url);$url=(@$uarr[host])?$uarr[host]:@$uarr[path];
			$url=($url!="")? preg_replace("/(https:\/\/|www\.)/ui","",$url):""; $url=utf8_strtolower($url);
		} else $url="";
		if($c['add_link_to_video']=="yes" || @$_POST['video']){
			$video=$_POST['video'];
			if(utf8_strlen($video)<50 && utf8_strlen($video)>24 && preg_match("/youtube\.com/iu",$video)){
				$video_arr=parse_url($_POST['video']);
				$video_arr2=split("v=",$video_arr['query']);
				unset($video_arr2[0]);
				$video=$video_arr2[1];
			}else $video="";
		}else $video="";
		if(ctype_digit(@$_POST['time_delete'])>0)$time_delete=$_POST['time_delete'];
		else die("<center><strong>".$lang[98].$lang[173]."</strong></center>");
		if(@$_POST['prices']){
			if(ctype_digit(@$_POST['prices'])>0)$prices=$_POST['prices'];
			else die("<center><strong>".$lang[98].$lang[173]."</strong></center>");
		} else $prices="";
		if(@$_POST['tags']) $tags=$_POST['tags'];
		else if($c['tags_generate']=="yes") $tags=$title;
		else $tags="";
		if($tags!=""){
			$keywords=array();
			$tags=preg_replace("/\s+/ums"," ",$tags);
			$tags=preg_replace("/([[:punct:]]|[[:digit:]]|(\s)+)/ui"," ",$tags);
			$arr=explode(" ",$tags);
			for($i=0;$i<count($arr);$i++){
				if(utf8_strlen($arr[$i])>3){$arr[$i]=trim($arr[$i]);$keywords[]=utf8_strtolower($arr[$i]);}
			}
			if(sizeof($keywords)!=0){
				$keywords=array_unique($keywords);shuffle($keywords);$keywords = array_slice($keywords,0,15);
				$tags=implode(', ',$keywords);$tags=clean($tags);
			}else $tags="";
		}
		$title=split_punct($title);
		$title=utf8_substr($title,0,$c['count_symb_title']);
		$title=clean($title);
		if(is_numeric($_POST['city'])){
			if(intval($_POST['city'])>"1"){
				if(@constant('JBLANG')==="en")$qcity="en_city_name";else $qcity="city_name";
				$query_city=mysql_query("SELECT ".$qcity." FROM jb_city WHERE id='".$_POST['city']."'"); cq(); 
				$data_city=mysql_fetch_assoc($query_city);
				$city_id=$_POST['city']; $city=$data_city[$qcity];
			} else {$city=$lang[164];$city_id=1;}
		} else {$city=$lang[164];$city_id=1;}
		$text=split_punct($text);
		$text=utf8_substr($text,0,$c['count_symb_text']);
		$text=clean($text);
		$autor=utf8_substr($autor,0,$c['count_symb_autor']);
		$autor=clean($autor);
		if($contacts!=""){
			$contacts=split_punct($contacts);
			$contacts=utf8_substr($contacts,0,$c['count_symb_contacts']);
			$contacts=clean($contacts);
		}
		$queryNowStatus=mysql_query("SELECT checkbox_top, checkbox_select FROM jb_board WHERE id='".$_GET['id_mess']."'"); cq(); 
                $dataNowStatus=mysql_fetch_assoc($queryNowStatus);
                if(@$_POST['checkbox_top'] == "1"){
                        if($dataNowStatus['checkbox_top'] == "1"){
                                $check_top = "";
                                $check_select = " checkbox_select='0', select_time='0000-00-00 00:00:00', ";
                        }else{
                                $check_top = " checkbox_top='1', top_time=NOW(), ";
                                $check_select = " checkbox_select='0', select_time='0000-00-00 00:00:00', ";
                        }
                }elseif(@$_POST['checkbox_select'] == "1" && @$_POST['checkbox_top'] != "1"){
                        if($dataNowStatus['checkbox_select'] == "1"){
                                $check_select = "";
                                $check_top = " checkbox_top='0', top_time='0000-00-00 00:00:00', ";
                        }else{
                                $check_select = " checkbox_select='1', select_time=NOW(), ";
                                $check_top = " checkbox_top='0', top_time='0000-00-00 00:00:00', ";
                        }
                }else{
                        $check_top = " checkbox_top='0', top_time='0000-00-00 00:00:00', ";
                        $check_select = " checkbox_select='0', checkbox_select='0000-00-00 00:00:00', ";
                }
		$insert=mysql_query("UPDATE jb_board SET id_category='".$id_category."', type='".$type."', autor='".$autor."', title='".$title."', email='".@$email."', city='".$city."', city_id='".$city_id."', url='".$url."', contacts='".$contacts."', text='".$text."', prices='".$prices."', video='".$video."', old_mess='old', checked='yes', ".$check_select.$check_top." tags='".$tags."', time_delete='".$time_delete."' WHERE id = '".$_GET['id_mess']."' LIMIT 1");  cq();
		$die_del_img="DELETE FROM jb_photo WHERE id_message='".$_GET['id_mess']."'";
		if($insert){
			if(@$_POST['del_photo']){
				$query_photo_name=mysql_query("SELECT * FROM jb_photo WHERE id_photo IN (".implode(", ",$_POST['del_photo']).")");cq(); 
				if(mysql_num_rows($query_photo_name)){
					while($photo_name=mysql_fetch_assoc($query_photo_name)){
						if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$photo_name['photo_name']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$photo_name['photo_name']);
						if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$photo_name['photo_name']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$photo_name['photo_name']);
					}
					mysql_query("DELETE FROM jb_photo WHERE id_photo IN (".implode(", ",$_POST['del_photo']).")");cq();
				}
			}
			if($_FILES['logo']){
				if($c['upload_images']=="yes"){
					$c_photo=mysql_num_rows(mysql_query("SELECT id_photo FROM jb_photo WHERE id_message='".$_GET['id_mess']."'"));cq();
					$count_img_in_array=0;
					for($i=0;$i<count($_FILES['logo']['name']);$i++){
						if($_FILES['logo']['error'][$i]==0 && $_FILES['logo']['size'][$i]>0){
							$count_img_in_array++;
					}}
					if($c['count_images_for_users']<=5&&(($count_img_in_array+$c_photo)>$c['count_images_for_users']))
					{
						die("<center><strong>".$lang[222].$lang[173]."</strong></center>");
					}
					for ($i=0;$i<$count_img_in_array;$i++){
						if($_FILES['logo']['error'][$i]==0 && $_FILES['logo']['size'][$i]>0){
							$size=getimagesize($_FILES["logo"]["tmp_name"][$i]);
							if($size['mime']=="image/gif")$ext="gif";
							elseif($size['mime']=="image/jpeg")$ext="jpeg";
							elseif($size['mime']=="image/png")$ext="png";
							else{
								die("<center><strong>".$lang[226]." ".$_FILES['logo']['name'][$i]." ".$lang[227].$lang[173]."</strong></center>");
							}	
							if($_FILES['logo']['size'][$i] < $c['upl_image_size']){
								$insert_img=mysql_query("INSERT jb_photo SET id_message='".$_GET['id_mess']."'");cq(); 
								if($insert_img)$file_id=mysql_insert_id();
								else{die("<center><strong>".$lang[411].$lang[173]."</strong></center>");}
								if(@$city!=$lang[164])$vname=$city."-";else $vname="";
								$filename=utf8_substr(translit($vname.$title),0,128);
								$filename=$filename."_".$file_id.".".$ext;
								if(!@img_resize($_FILES['logo']['tmp_name'][$i],$_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename,$c['width_small_images'],1,0xFFFFFF,$ext,$size[1],$size[0],"0")){
									die("<center><strong>".$lang[411]." ".$_FILES['logo']['name'][$i]." ".$lang[227].$lang[173]."</strong></center>");
								}
								if(!@img_resize($_FILES['logo']['tmp_name'][$i],$_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$filename,$c['width_normal_images'],0,0,$ext,$size[1],$size[0],"1")){
									if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename);
									mysql_query ($die_del_img);
									die("<center><strong>".$lang[411]." ".$_FILES['logo']['name'][$i]." ".$lang[227].$lang[173]."</strong></center>");
								}
								$update=mysql_query("UPDATE jb_photo SET photo_name='".$filename."' WHERE id_photo='".$file_id."' AND id_message='".$_GET['id_mess']."' LIMIT 1");  cq(); 
								if(!$update){
									if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename);
									if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$filename);
									mysql_query($die_del_img);									
									die("<center><strong>".$lang[411]." ".$_FILES['logo']['name'][$i]." ".$lang[227].$lang[173]."</strong></center>");
								}
							}
							else die("<center><strong>".$lang[641]." ".$_FILES['logo']['name'][$i]." ".$lang[642].$lang[173]."</strong></center>");
						} 
					}
				}
				else die("<center><strong>".$lang[228]." ".$_FILES['logo']['name'][$i]." ".$lang[642].$lang[173]."</strong></center>");
			}
			if($c['cache_clear']=="auto"){
				$dirname="../cache/";
				$dir=opendir($dirname);
				while($file=readdir($dir)){
					if($file!="." && $file!=".." && $file!=".htaccess" && ($file=="clouds_tags" || $file=="kaleidoscope" || $file=="stat" || (utf8_substr($file,0,(utf8_strlen($id_category)+2)) == "c".$id_category."-") || (utf8_substr($file,0,(utf8_strlen($_GET['id_mess'])+1)) == "mess_".$_GET['id_mess'])))unlink($dirname.$file);
				} closedir ($dir);
			}
			mysql_query("DELETE FROM jb_stat_sms WHERE id_board='".$_GET['id_mess']."'");
			if(@$_POST['checkbox_top']=="1") mysql_query("INSERT jb_stat_sms SET operator='admin', numb_phone='---', id_board='".$_GET['id_mess']."', date=NOW()");
			elseif(@$_POST['checkbox_top']!="1" && @$_POST['checkbox_select']=="1") mysql_query("INSERT jb_stat_sms SET operator='admin', numb_phone='---', id_board='".$_GET['id_mess']."', date=NOW()");
			echo "<br /><br /><center><h1>".$lang[400]."</h1><br /><center><strong>".$lang[645].": <br /><a href=\"".$h."c".$_POST['id_category']."-".$_GET['id_mess'].".html\">".$h."c".$_POST['id_category']."-".$_GET['id_mess'].".html/</a></strong></center><br />";
		}
		else die("<center><strong>".$lang[572].$lang[173]."</strong></center>");
	}else{
		$editq = mysql_query ("SELECT * FROM jb_board WHERE id = '".$_GET['id_mess']."'"); cq();
		if(mysql_num_rows($editq)){
			$edit = mysql_fetch_assoc($editq);
			?><div class="addform" align="center"><form action="<?=$h?>a/?action=ads&op=edit&id_mess=<?=$_GET['id_mess']?>" method="post" enctype="multipart/form-data" name="add_form" onsubmit="return check_fields();"><h1 class="alcenter"><?=$lang[611]?></h1><br /><br /><div class="lc"><?=$lang[123]?><span class="req">*</span></div><div class="rc"><? $edittitle=(@$_POST['title'])?htmlspecialchars(@$_POST['title']):$edit['title']; ?><input maxlength="<?=$c['count_symb_title']?>" type="text" name="title" size="50" value="<?=$edittitle?>" /></div><div class="pad"></div><div class="lc"><?=$lang[163]?></div><div class="rc"><?
			$getcity = (@$_POST['city'])?$_POST['city']:$edit['city_id'];
			if(@constant('JBLANG')=="en")$qcity="en_city_name";else $qcity="city_name";
			$querycity=mysql_query("SELECT ".$qcity." FROM jb_city WHERE id='".$getcity."'");cq();
			$cccity=mysql_fetch_assoc($querycity);
			echo "<div id=\"usercity\"><span class=\"b\">".$cccity[$qcity]."</span> (<a href=\"#\" onclick=\"rootcity('usercity');return false;\">".$lang[15]."</a>)<input type=\"hidden\" name=\"city\" value=\"".$getcity."\" /></div>";
			?><div id="resultcity"></div></div><div class="pad"></div><div class="lc"><?=$lang[412]?><span class="req">*</span></div><div class="rc"><select name="type"><option value="0"><?=$lang[620]?></option><? $edittype=(@$_POST['type'])?htmlspecialchars(@$_POST['type']):$edit['type']; ?><option value="s" <? if($edittype=="s") echo "selected=\"selected\""; ?>><?=$lang[414]?></option><option value="p" <? if($edittype=="p") echo "selected=\"selected\""; ?>><?=$lang[413]?></option><option value="u" <? if($edittype=="u") echo "selected=\"selected\""; ?>><?=$lang[800]?></option><option value="o" <? if($edittype=="o") echo "selected=\"selected\""; ?>><?=$lang[801]?></option></select></div><div class="pad"></div><div class="lc"><?=$lang[122]?><span class="req">*</span></div><div class="rc"><?
			$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat'; 
			$getcat = (@$_POST['id_category'])?$_POST['id_category']:$edit['id_category'];
			$querycat=mysql_query("SELECT * FROM jb_board_cat WHERE id='".$getcat."'");cq();
			$category=mysql_fetch_assoc($querycat);
			echo "<div id=\"usercat\"><span class=\"b\">".$category[$name_cat]."</span> (<a href=\"#\" onclick=\"rootcat('usercat');return false;\">".$lang[15]."</a>)<input type=\"hidden\" name=\"id_category\" value=\"".$category['id']."\" /></div>";
			?><div id="resultcat"></div></div><div class="pad"></div><div class="lc"><?=$lang[111]?></div><div class="rc"><select name="time_delete"><? $edittime_delete=(is_numeric(@$_POST['time_delete']))?intval(@$_POST['time_delete']):$edit['time_delete']; ?><option value="7"<? if($edittime_delete==7) echo " selected=\"selected\""; ?>>7 <?=$lang[112]?></option><option value="14"<? if($edittime_delete==14) echo " selected=\"selected\""; ?>>14 <?=$lang[112]?></option><option value="30"<? if(!@$_POST['time_delete']||$edittime_delete==30) echo " selected=\"selected\""; ?>>30 <?=$lang[112]?></option><option value="60"<? if($edittime_delete==60) echo " selected=\"selected\""; ?>>60 <?=$lang[112]?></option><option value="90"<? if($edittime_delete==90) echo " selected=\"selected\""; ?>>90 <?=$lang[112]?></option><option value="180"<? if($edittime_delete==180) echo " selected=\"selected\""; ?>>180 <?=$lang[112]?></option><option value="365"<? if($edittime_delete==365) echo " selected=\"selected\""; ?>>365 <?=$lang[112]?></option></select></div>
			<div class="pad"></div>
			<div class="lc"><?=$lang[105]?><span class="req">*</span></div>
			<div class="rc"><? $edittext=(@$_POST['text'])?htmlspecialchars($_POST['text']):$edit['text']; ?><textarea name="text" rows="6" cols="37"><?=$edittext?></textarea></div>
			<div class="pad"></div>
			<div class="lc"><?=$lang[1008]?> (<?=$lang[101010]?>)</div>
			<div class="rc"><? $editprices=(is_numeric(@$_POST['prices']))?intval($_POST['prices']):$edit['prices']; ?><input onkeyup="ff2(this)" maxlength="11" type="text" name="prices" size="50" value="<?=$editprices?>" /></div>
			<div class="pad"></div>
			
			<? 
			if($c['upload_images']=="yes"){
			?><script language="JavaScript" type="text/javascript">
			<!--
			function del(n){var tab=$("tab");if(tab.rows.length==2 && n==0){document.forms["add_form"].reset();return;}
			if(tab.rows.length>2){if(n==0){return;}else if(n==1){tab.tBodies[0].deleteRow(tab.rows.length-2);}
			else{tab.tBodies[0].deleteRow(n.parentNode.parentNode.rowIndex);}}else{return;}}
			function add(){var tab=$("tab");
			var newRow=tab.tBodies[0].insertRow(tab.rows.length-1);var newCell_1=newRow.insertCell(0);newCell_1.style.border="none";
			newCell_1.innerHTML="<span><\/span>";var newfield=document.createElement("input");newfield.setAttribute("type","file");
			newfield.setAttribute("size","35");newfield.setAttribute("name","logo[]");newCell_1.appendChild(newfield);
			newRow.appendChild(newCell_1);var newCell_2=newRow.insertCell(1);var nb_2=document.createElement("input");
			nb_2.setAttribute("type","button");nb_2.setAttribute("value"," — ");nb_2.title="<?=$lang[417]?>";
			nb_2.onclick=function(){del(this);}
			newCell_2.appendChild(nb_2);newRow.appendChild(newCell_2);showIndexfd();}function showIndexfd(){var tab=$("tab");
			for(var i=0;i<tab.rows.length;i++){var fc=tab.rows[i].firstChild; fc.firstChild.innerHTML="";}}
			//-->
			</script><div class="lc"><?=$lang[106]?></div><div class="rc"><?
			$query_img=mysql_query("SELECT * FROM jb_photo WHERE id_message = '".$edit['id']."'"); cq();  
			if(@mysql_num_rows ($query_img)){
			while ($data_img=mysql_fetch_assoc($query_img)) echo "<a href=\"".$u."normal/".$data_img['photo_name']."\" rel=\"thumbnail\"><img class=\"absmid\" src=\"".$u."small/".$data_img['photo_name']."\"></a> <input type=checkbox name=del_photo[] value=\"".$data_img['id_photo']."\"> - ".$lang[621]."<br />";
			}
			?><?=$lang[110]?><br /><?=$lang[313].($c['upl_image_size']/1000)?>Kb<br /><?
			if($c['count_images_for_users']>=1 && $c['count_images_for_users']<=5){for($i=1;$i<=$c['count_images_for_users'];$i++) echo "<input type=\"file\" name=\"logo[]\" /><br />";}
			else echo "<table id=\"tab\" cellpadding=\"3\" cellspacing=\"3\"><tr><td><input size=\"35\" id=\"test\" type=\"file\" name=\"logo[]\" /></td></tr><tr><td align=\"center\"><br /><input type=\"button\" value=\"".$lang[418]."\" onclick=\"add()\" onfocus=\"this.blur()\" /></td></tr></table>";
			?></div><div class="pad"></div><?
}
			if($c['add_link_to_video']=="yes"){
			?><div class="lc"><?=$lang[1100]?><br /><img alt="youtube" class="absmid" src="<?=$im?>youtube_icon.png" /><a rel="nofollow" href="https://www.youtube.com/">youtube.com</a></div><div class="rc"><input maxlength="128" type="text" name="video" size="50" value="<?
			if(@$_POST['video']) echo htmlspecialchars(@$_POST['video']);
			else if(@$edit['video']) echo "https://www.youtube.com/watch?v=".$edit['video'];
			?>" /><br /><span class="sm gray"><strong class="red"><?=$lang[1101]?></strong><br /><?=$lang[1102]?><strong> https://www.youtube.com/watch?v=.........</strong></span></div><div class="pad"></div><?
			}	
			?><div class="lc"><?=$lang[1009]?></div><div class="rc"><? $edittags=(@$_POST['tags'])?htmlspecialchars($_POST['tags']):$edit['tags']; ?><input maxlength="250" type="text" name="tags" size="50" value="<?=$edittags?>" /></div><div class="pad"></div><div class="lc"><?=$lang[623]?><span class="req">*</span></div><div class="rc"><? $editautor=(@$_POST['autor'])?htmlspecialchars($_POST['autor']):$edit['autor']; ?><input maxlength="<?=$c['count_symb_autor']?>" type="text" name="autor" size="50" value="<?=$editautor?>" /></div><div class="pad"></div><div class="lc"><?=$lang[196]?></div><div class="rc"><input type="text" name="email" size="50" value="<?
			if(@$_POST['email']) echo htmlspecialchars(@$_POST['email']);
			else echo $edit['email'];
			?>" /></div><div class="pad"></div><div class="lc"><?=$lang[181]?></div><div class="rc"><? $editcontacts=(@$_POST['contacts'])?htmlspecialchars($_POST['contacts']):$edit['contacts']; ?><textarea name="contacts" rows="4" cols="37"><?=@$editcontacts?></textarea></div><div class="pad"></div><?
			if($c['add_url']=="yes"){
			?><div class="lc"><?=$lang[625]?>:</div><div class="rc"><? $editurl=(@$_POST['url'])?htmlspecialchars($_POST['url']):$edit['url']; ?><input maxlength="<?=$c['count_symb_url']?>" type="text" name="url" size="50" value="<?=$editurl?>" /></div><div class="pad"></div><?
			}
			$check_select=(@$_POST['checkbox_select']=="1" || $edit['checkbox_select']=="1")?" checked ":"";
			$check_top=(@$_POST['checkbox_top']=="1" || $edit['checkbox_top']=="1")?" checked ":"";
			?><div class="lc"><?=$lang[420]?></div><div class="rc"><input type="checkbox" name="checkbox_select" value="1" <?=$check_select?> /></div><div class="pad"></div><div class="lc"><?=$lang[421]?></div><div class="rc"><input type="checkbox" name="checkbox_top" value="1" <?=$check_top?> /></div><div class="pad"></div><div align="center"><strong style="color:#FF0000"><?=$lang[206]?><br /><? if($c['anti_link']=="yes") echo $lang[204]; ?></strong><br /><br /><input name="submit" style="width:70%;" type="submit" value=" <?=$lang[12]?> " /></div></form><br /><br /></div><?
			if(defined('ALERT')) echo "<script type=\"text/javascript\">alert('".ALERT."');</script>";
		}
	}
}
elseif(@$_GET['op']=="del" && ctype_digit($_GET['id_mess'])){
	$p_del=mysql_query("SELECT id_photo,photo_name FROM jb_photo WHERE id_message = '".$_GET['id_mess']."'");cq();   
	if(@mysql_num_rows($p_del)){
		while($list=mysql_fetch_assoc($p_del)){
			if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$list['photo_name']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$list['photo_name']);
			if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$list['photo_name']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$list['photo_name']);
			mysql_query("DELETE FROM jb_photo WHERE id_photo = '".$list['id_photo']."' LIMIT 1");cq();
		}
	}
	$cat_cashe_clear=mysql_query("SELECT id_category FROM jb_board WHERE id='".$_GET['id_mess']."'");cq();
	$arr_cat_cashe_clear=mysql_fetch_assoc($cat_cashe_clear);
	mysql_query("DELETE FROM jb_board WHERE id = '".$_GET['id_mess']."' LIMIT 1");cq();
	mysql_query("DELETE FROM jb_abuse WHERE id_board = '".$_GET['id_mess']."'");cq();
	mysql_query("DELETE FROM jb_comments WHERE id_board = '".$_GET['id_mess']."'");cq();
	mysql_query("DELETE FROM jb_notes WHERE id_board = '".$_GET['id_mess']."'");cq();
	if($c['cache_clear']=="auto"){
		$dirname="../cache/";
		$dir=opendir($dirname);
		while($file=readdir($dir)){
			if($file!="." && $file!=".." && $file!=".htaccess" && (utf8_substr($file,0,8) == "newlist-" || $file=="clouds_tags" || $file=="kaleidoscope" || $file=="stat" || (utf8_substr($file,0,(utf8_strlen($arr_cat_cashe_clear['id_category'])+2)) == "c".$arr_cat_cashe_clear['id_category']."-")))unlink($dirname.$file);
		} closedir ($dir);
	}
	echo "<center><strong>".$lang[400]."</strong></center>";
}
elseif(@$_GET['op']=="ad_checked" && @$_POST['board_check']){
	$impl=implode(', ',$_POST['board_check']);
	if(@$_POST['actions_for_checkeds']=="del_checked"){
		$p_del=mysql_query("SELECT id_photo,photo_name FROM jb_photo WHERE id_message IN (".$impl.")");cq();   
		if(@mysql_num_rows($p_del)){
			while($list=mysql_fetch_assoc($p_del)){
				if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$list['photo_name']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$list['photo_name']);
				if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$list['photo_name']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$list['photo_name']);
				mysql_query("DELETE FROM jb_photo WHERE id_photo = '".$list['id_photo']."' LIMIT 1");cq();
			}
		}
		if($c['cache_clear']=="auto"){
			$ccdel=mysql_query("SELECT id_category FROM jb_board WHERE id IN (".$impl.")");cq();
			$ccdel_arr=array();
			while($list_ccdel=mysql_fetch_assoc($ccdel))$ccdel_arr[]=$list_ccdel['id_category'];
			$ccdel_arr=array_unique($ccdel_arr);
			$dirname="../cache/";
			$dir=opendir($dirname);
			while($file=readdir($dir)){
				if($file!="." && $file!=".." && $file!=".htaccess"){
					if(utf8_substr($file,0,8) == "newlist-" || $file=="clouds_tags" || $file=="kaleidoscope" || $file=="stat"){
						unlink($dirname.$file);
					}
					foreach($ccdel_arr as $k=>$v){
						if(utf8_substr($file,0,(utf8_strlen($v)+2)) == "c".$v."-"){unlink($dirname.$file);}
			}}}
			closedir ($dir);
		}
		mysql_query("DELETE FROM jb_board WHERE id IN (".$impl.")");cq();
		mysql_query("DELETE FROM jb_abuse WHERE id_board IN (".$impl.")");cq();
		mysql_query("DELETE FROM jb_comments WHERE id_board IN (".$impl.")");cq();
		mysql_query("DELETE FROM jb_notes WHERE id_board IN (".$impl.")");cq();
		echo "<center><strong>".$lang[400]."</strong></center>";
	}else{
		if(mysql_query("UPDATE jb_board SET old_mess='old', checked='yes' WHERE id IN (".$impl.")"))echo "<center><strong>".$lang[400]."</strong></center>";
		else echo "<center><strong>".$lang[98]."</strong></center>";
	}
	if($c['cache_clear']=="auto"){
		$dirname="../cache/";
		$dir=opendir($dirname);
		while($file=readdir($dir)){
			if($file!="." && $file!=".." && $file!=".htaccess" && (utf8_substr($file,0,8) == "newlist-" || $file=="clouds_tags" || $file=="kaleidoscope" || $file=="stat"))unlink($dirname.$file);
		} closedir ($dir);
	}
}
else{
	$name_cat=(defined('JBLANG')&& constant('JBLANG')=='en')?'en_name_cat':'name_cat';
	if(@$_GET['op']=="new")$subQuery="SELECT id FROM jb_board WHERE old_mess!='old' OR checked!='yes'";	
	elseif(ctype_digit(@$_GET['id_category']) && !@$_GET['op'])$subQuery="SELECT jb_board.id, jb_board_cat.".$name_cat." FROM jb_board, jb_board_cat WHERE jb_board.id_category = jb_board_cat.id AND jb_board.id_category='".$_GET['id_category']."'";	
	else $subQuery="SELECT id FROM jb_board";	
	$result=mysql_query($subQuery);cq();
	if(@$result)$total_rows=mysql_num_rows($result);
	if(@$total_rows){
		if(ctype_digit(@$_GET['page'])&& @$_GET['page']>0)$page=$_GET['page'];else $page=1;
		$tot=($total_rows-1)/$c['count_adv_on_index'];
		$total=intval($tot+1);if($page>$total) $page=$total;
		$start=$page*$c['count_adv_on_index']-$c['count_adv_on_index'];
		if(@$_GET['op']=="new")$subQuery2="SELECT jb_board.id AS board_id, jb_board.id_category, jb_board.title, jb_board.city, jb_board.checkbox_top, jb_board.checkbox_select, jb_board.autor, jb_board.text, jb_board.checked,  DATE_FORMAT(jb_board.date_add,'%d.%m.%Y') AS dateAdd, UNIX_TIMESTAMP(jb_board.date_add) as unix_time, jb_board_cat.id, jb_board_cat.".$name_cat." FROM jb_board LEFT JOIN jb_board_cat ON jb_board.id_category = jb_board_cat.id WHERE old_mess!='old' OR checked!='yes' ORDER BY board_id DESC LIMIT ".$start.",".$c['count_adv_on_index'];	
		elseif(ctype_digit(@$_GET['id_category']) && !@$_GET['op'])$subQuery2="SELECT id AS board_id, id_category, title, city, checkbox_top, checkbox_select, autor, text, checked, DATE_FORMAT(date_add,'%d.%m.%Y') AS dateAdd, UNIX_TIMESTAMP(date_add) as unix_time FROM jb_board WHERE id_category='".$_GET['id_category']."' ORDER BY board_id DESC LIMIT ".$start.", ".$c['count_adv_on_index'];	
		else $subQuery2="SELECT jb_board.id AS board_id, jb_board.id_category, jb_board.title, jb_board.city, jb_board.checkbox_top, jb_board.checkbox_select, jb_board.autor, jb_board.text, jb_board.checked, DATE_FORMAT(jb_board.date_add,'%d.%m.%Y') AS dateAdd, UNIX_TIMESTAMP(jb_board.date_add) as unix_time, jb_board_cat.".$name_cat." FROM jb_board LEFT JOIN jb_board_cat ON jb_board.id_category = jb_board_cat.id ORDER BY board_id DESC LIMIT ".$start.", ".$c['count_adv_on_index'];
		$query=mysql_query($subQuery2);cq();
		if(mysql_num_rows($query)){
			if(ctype_digit(@$_GET['id_category']) && !@$_GET['op']){
				$namecat=mysql_fetch_assoc($result);
				echo "<center><h1>".$lang[122].": ".$namecat[$name_cat]."</h1></center><br />";
			}
			elseif(@$_GET['op']=="new") echo "<center><h1>".$lang[600]."</h1></center><br />";
			else echo "<center><h1>".$lang[1043]."</h1></center><br />";
			echo "<div class=\"admcats\"><form method=\"get\" action=\"".$h."a/\"><input type=\"hidden\" name=\"action\" value=\"ads\" /><select name=\"id_category\" onchange=\"selcat(this.value,'resultcat');\"><option value=\"no\" selected=\"selected\">".$lang[99]." &rarr;</option>";
			$selectcat=mysql_query("SELECT * FROM jb_board_cat WHERE root_category = 0 ORDER by sort_index"); cq();
			$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat'; 
			while($selectcategory=mysql_fetch_assoc($selectcat)) echo "<option value=\"".$selectcategory['id']."\">".$selectcategory[$name_cat]." &rarr; </option>";
			echo "</select> <div id=\"resultcat\"></div> <input style=\"float:left; width:50px\" type=\"submit\" value=\"&rarr;\"></form></div><div style=\"clear:both\"></div>";
			echo "<form method=\"post\" name=\"city\" action=\"".$h."a/?action=ads&op=ad_checked\">";
			echo "<table class=\"sort\" align=\"center\" cellspacing=\"5\" width=\"100%\"><thead><tr bgcolor=\"#F6F6F6\"><td align=\"center\">".$lang[123]."</td><td align=\"center\">".$lang[1033]."</td><td align=\"center\">".$lang[1041]."</td><td align=\"center\">".$lang[106]."</td><td align=\"center\">".$lang[423]."</td><td align=\"center\">".$lang[127]."</td><td colspan=\"2\" align=\"center\">".$lang[126]."</td><td align=\"center\">".$lang[299]."&nbsp;&nbsp;<input type=\"checkbox\" name=\"all_boxes\" onclick=\"changeall(city);\"></td></tr></thead><tbody>";
			while($board = mysql_fetch_assoc($query)){	
				echo "<tr bgcolor=#F6F6F6><td><span class=\"sm gray\">".$board['autor']."</span><br /><a target=\"_blank\" href =\"".$h."c".$board['id_category']."-".$board['board_id'].".html\" title=\"".$board['text']."\">".$board['title']."</a></td><td align=center>";
				if($board['checked']=="no")echo "<p>0</p><img title=\"".$lang[1038]."\" alt=\"".$lang[1038]."\" src=\"".$im."ads_new.png\">";
				elseif($board['checked']=="edit")echo "<p>1</p><img title=\"".$lang[1039]."\" alt=\"".$lang[1039]."\" src=\"".$im."ads_edit.png\">";
				else echo "<p>2</p><img title=\"".$lang[1040]."\" alt=\"".$lang[1040]."\" src=\"".$im."ads_old.png\">";
				echo "</td><td align=center>";
				if($board['checkbox_top']==1)echo "<p>1</p><img title=\"".$lang[128]."\" alt=\"".$lang[128]."\" src=\"".$im."vip.gif\">";
				elseif($board['checkbox_top']!=1 && $board['checkbox_select']==1)echo "<p>0</p><img title=\"".$lang[424]."\" alt=\"".$lang[424]."\" src=\"".$im."lost.gif\">";
				echo "</td><td align=center>";
				$countphoto=mysql_result(mysql_query("select count(id_photo) from jb_photo WHERE id_message='".$board['board_id']."'"),0);cq();
				echo(@$countphoto)?$countphoto:"";
				echo "</td><td align=center>";
				$countcomments=mysql_result(mysql_query("select count(id) from jb_comments WHERE id_board='".$board['board_id']."'"),0);
				echo (@$countcomments)?"<a href=\"".$h."a/?action=comments&id_mess=".$board['board_id']."\">".$countcomments."</a>":"";
				echo "</td><td><p>".$board['unix_time']."</p>";
				if($board['dateAdd']==date("d.m.Y"))echo $lang[531];else echo $board['dateAdd'];
				echo "</td><td align=center><a href =\"".$h."a/?action=ads&op=edit&id_mess=".$board['board_id']."\"><img src=\"".$im."edit.gif\"></a></td><td align=center><a href =\"".$h."a/?action=ads&op=del&id_mess=".$board['board_id']."\" onClick='return conformdelete(this,confirmmess);'><img src=\"".$im."del.gif\"></a></td><TD align=center><input type=\"checkbox\" value=\"".$board['board_id']."\" name=\"board_check[]\" title=\"".$lang[246]."\"></TD></tr>";
			}
			echo "</tbody></table><table align=\"center\" cellspacing=\"15\"><tr><td>";
			if($total_rows>=$c['count_adv_on_index']){
				if(@$_GET['op']=="new")$subGet="&op=new";	
				elseif(ctype_digit(@$_GET['id_category']) && !@$_GET['op'])$subGet="&id_category=".$_GET['id_category'];					
				else $subGet="";
				$a="<a href=\"?action=ads".$subGet."&page=";
				if($page!=1)$pervpage=$a."1\" title=\"".$lang[174]."\">&nbsp;&nbsp;&nbsp;&#171;&nbsp;&nbsp;&nbsp;</a> ";
				if($page!=$total) $nextpage=$a.$total."\" title=\"".$lang[175]."\">&nbsp;&nbsp;&nbsp;&#187;&nbsp;&nbsp;&nbsp;</a>";		
				$pageleft="";$pageright="";
				for($i=$c['limit_pagination_on_page'];$i>=1;$i--)if($page-$i>0)$pageleft.=$a.($page-$i)."\">".($page-$i)."</a>";
				for($i=1;$i<=$c['limit_pagination_on_page'];$i++)if($page+$i<=$total)$pageright.=$a.($page+$i)."\">".($page+$i)."</a>"; 
				echo "<div class=\"pagination\">".@$pervpage.@$pageleft."<b><span class=\"current\">".$page."</span></b>".@$pageright.@$nextpage."</div>";
			}
			echo "</td><td> &nbsp; </td><td>".$lang[303].": <select name=\"actions_for_checkeds\"><option value=\"moderation_checked\" selected>".$lang[302]."<option value=\"del_checked\">".$lang[300]."</select><input type=\"submit\" value=\"ok\" onclick=\"return conformdelete(this,confirmmess);\"></td></tr></table></form>";
		}else echo "<br /><center><h1>".$lang[407]."</h1></center><br />";
	}else echo "<br /><center><h1>".$lang[407]."</h1></center><br />";
}
?>