<div class="form-wrapper"> 
<?
if(@$_POST['submit']){
	$host=parse_url($_SERVER['HTTP_REFERER']); if($host['host']!=$_SERVER['HTTP_HOST'])die();
	if(@$_POST['title'])$title=trim($_POST['title']);
	else{define("ALERT",$lang[94]);require_once("add_new_form.inc.php");die();}
	if(@$_POST['type']=="p" || @$_POST['type']=="s" || @$_POST['type']=="u" || @$_POST['type']=="o")$type=$_POST['type']; 
	else{define("ALERT",$lang[620]);require_once("add_new_form.inc.php");die();}
	if(ctype_digit(@$_POST['id_category'])>0)$id_category=$_POST['id_category'];
	else{define("ALERT",$lang[98]." ".$lang[537]);require_once("add_new_form.inc.php");die();}
	$query_root=mysql_query("SELECT child_category FROM jb_board_cat WHERE id='".$id_category."'"); cq(); 
    $data_root_cat=mysql_fetch_assoc($query_root);
    if($data_root_cat['child_category']==1){define("ALERT",$lang[537]);require_once("add_new_form.inc.php");die();} 
	if(@$_POST['text'])$text=trim($_POST['text']);
	else{define("ALERT",$lang[95]);require_once("add_new_form.inc.php");die();}
	if(@$_POST['autor'])$autor=trim($_POST['autor']);
	else{define("ALERT",$lang[92]);require_once("add_new_form.inc.php");die();}
	if(@$_POST['contacts'])$contacts=trim($_POST['contacts']); else $contacts="";
	if($c['captcha']=="yes"){
		if(@$_POST['securityCode'] && @$_SESSION['securityCode'])
		{
			if(utf8_strtolower($_POST['securityCode'])!=utf8_strtolower($_SESSION['securityCode'])){define("ALERT",$lang[116]);require_once("add_new_form.inc.php");die();}
			$_SESSION['securityCode']=md5($_POST['title']);
	}}
	if(@$c['stop_words']!=""){
		$arr_stopwords=explode(",",$c['stop_words']);
		foreach($arr_stopwords as $value) if(preg_match("/$value/iu",$title.$autor.$text.$contacts.@$_POST['tags'])){define("ALERT",$lang[806]);require_once("add_new_form.inc.php");die();}		
	}
	if($c['anti_link']=="yes"){
		if($c['add_url']=="yes") $anti_link=$lang[629]; else $anti_link=$lang[204];
		$stri = $autor." ".$title." ".$text." ".$contacts;
		if(preg_match("/href|https|www|\.ru|\.com|\.net|\.org/iu",$stri)){define("ALERT",$anti_link);require_once("add_new_form.inc.php");die();}
	}
	if($c['add_link_to_video']=="yes" || @$_POST['video']){
		$video=$_POST['video'];
		if(utf8_strlen($video)<50 && utf8_strlen($video)>24 && preg_match("/youtube\.com/iu",$video)){
			$video_arr=parse_url($_POST['video']);
			$video_arr2=split("v=",$video_arr['query']);
			unset($video_arr2[0]);
			$video=$video_arr2[1];
		}else $video="";
	}else $video="";
	if(@$_POST['email']){
		$email=trim(utf8_strtolower($_POST['email']));
		if(!preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/iu',$email)){define("ALERT",$lang[96]);require_once("add_new_form.inc.php");die();}
	} else $email="";
	if(@$_POST['url']){
		$url=trim($_POST['url']); $url=utf8_substr($url,0,$c['count_symb_url']);
		if(preg_match('/[^-a-z0-9_\.\:\/]/iu',$url)){define("ALERT",$lang[639]);require_once("add_new_form.inc.php");die();}
		$uarr=parse_url($url);$url=(@$uarr[host])?@$uarr[host]:@$uarr[path];
		$url=($url!="")? preg_replace("/(https:\/\/|www\.)/ui","",$url):""; $url=utf8_strtolower($url);
	} else $url="";
	if(ctype_digit(@$_POST['time_delete'])>0)$time_delete=$_POST['time_delete'];
	else{define("ALERT",$lang[98]);require_once("add_new_form.inc.php");die();}
	if(@$_POST['price']){
		if(ctype_digit(@$_POST['price'])>0)$price=$_POST['price'];
		else{define("ALERT",$lang[98]);require_once("add_new_form.inc.php");die();}
	} else $price="";
	if(@$_POST['prices']){
		if(ctype_digit(@$_POST['prices'])>0)$prices=$_POST['prices'];
		else{define("ALERT",$lang[98]);require_once("add_new_form.inc.php");die();}
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
	if($c['edit_message']=="yes")$moder="new"; else $moder="old";
	$query_dubl=mysql_query("SELECT id FROM jb_board WHERE autor='".$autor."' AND title='".$title."' AND text='".$text."' LIMIT 1");
	if(mysql_num_rows($query_dubl)){define("ALERT",$lang[296]);require_once("add_new_form.inc.php");die();}cq(); 
	if(@$user_data['activ']=="yes")$us_insert="user_id='".$user_data['id_user']."', ";else $us_insert="";
	$insert=mysql_query("INSERT jb_board SET id_category='".$id_category."', ".$us_insert." type='".$type."', autor='".$autor."', title='".$title."', email='".@$email."', url='".$url."', contacts='".$contacts."', text='".$text."', prices='".$prices."', price='".$price."', video='".$video."', old_mess='".$moder."', tags='".$tags."', time_delete='".$time_delete."', date_add=NOW()");  cq(); 
	$last_id=mysql_insert_id();
	$die_del_mess="DELETE FROM jb_board WHERE id='".$last_id."' LIMIT 1";
	$die_del_img="DELETE FROM jb_photo WHERE id_message='".$last_id."'";
	if($insert){
		if($_FILES['logo']){
			if($c['upload_images']=="yes"){
				$count_img_in_array=count($_FILES['logo']['name']);
				if($c['count_images_for_users'] <= 5 && $count_img_in_array > $c['count_images_for_users']){
					define("ALERT",$lang[222]);mysql_query($die_del_mess);require_once("add_new_form.inc.php");die();
				}
				for ($i=0;$i<$count_img_in_array;$i++){
					if($_FILES['logo']['error'][$i]==0 && $_FILES['logo']['size'][$i]>0){
						$size=getimagesize($_FILES["logo"]["tmp_name"][$i]);
						if($size['mime']=="image/gif")$ext="gif";
						elseif($size['mime']=="image/jpeg")$ext="jpeg";
						elseif($size['mime']=="image/png")$ext="png";
						else{
							define("ALERT",$lang[226]." ".$_FILES['logo']['name'][$i]." ".$lang[227]."");
							mysql_query($die_del_mess);require_once("add_new_form.inc.php");die();
						}
						if($_FILES['logo']['size'][$i] < $c['upl_image_size']){
							$insert_img=mysql_query("INSERT jb_photo SET id_message='".$last_id."'");cq(); 
							if($insert_img)$file_id=mysql_insert_id();
							else{define("ALERT",$lang[411]);mysql_query($die_del_mess);require_once("add_new_form.inc.php");die();}
							if(@$city!=$lang[164])$vname=$city."-";else $vname="";
							$filename=utf8_substr(translit($vname.$title),0,128);
							$filename=$filename."_".$file_id.".".$ext;
							if(!@img_resize($_FILES['logo']['tmp_name'][$i],$_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename,$c['width_small_images'],1,0xFFFFFF,$ext,$size[1],$size[0],"0")){
								define("ALERT",$lang[411]." ".$_FILES['logo']['name'][$i]." ".$lang[227]."");
								mysql_query($die_del_mess);mysql_query ($die_del_img); 
								require_once("add_new_form.inc.php");die();
							}
							if(!@img_resize($_FILES['logo']['tmp_name'][$i],$_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$filename,$c['width_normal_images'],0,0,$ext,$size[1],$size[0],"1")){
								define("ALERT",$lang[411]." ".$_FILES['logo']['name'][$i]." ".$lang[227]."");
								if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename);
								mysql_query($die_del_mess);mysql_query ($die_del_img); 
								require_once("add_new_form.inc.php");die();
							}
							$update=mysql_query("UPDATE jb_photo SET photo_name='".$filename."' WHERE id_photo='".$file_id."' AND id_message='".$last_id."' LIMIT 1");  cq(); 
							if(!$update){
								define("ALERT",$lang[411]." ".$_FILES['logo']['name'][$i]." ".$lang[227]."");
								if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$filename);
								if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$filename);
								mysql_query($die_del_mess);mysql_query($die_del_img); 
								require_once("add_new_form.inc.php");die();
						}}else{
							define("ALERT",$lang[641]." ".$_FILES['logo']['name'][$i]." ".$lang[642]);
							mysql_query($die_del_mess);require_once("add_new_form.inc.php");die();
			}}}}else{
				define("ALERT",$lang[228]." ".$_FILES['logo']['name'][$i]." ".$lang[642]);
				mysql_query($die_del_mess);require_once("add_new_form.inc.php");die();
		}}
		if($c['admin_mail'] && $c['mail_about_new_mess']=="yes"){
			$subject=$lang[215]." ".$h." ".$lang[216]; 
			sendmailer($c['admin_mail'],$c['admin_mail'],$subject,$lang[217]);
		}
		if($c['cache_clear']=="auto"){
			$dirname="./cache/";
			$dir=opendir($dirname);
			while($file=readdir($dir)){
				if($file!="." && $file!=".." && $file!=".htaccess" && (utf8_substr($file,0,8)=="newlist-" || $file=="clouds_tags" || $file=="kaleidoscope" || $file=="stat" || (utf8_substr($file,0,(utf8_strlen($id_category)+2))=="c".$id_category."-")))unlink($dirname.$file);
			}
			closedir($dir);
		}
		echo "<br /><br /><center><h1>".$lang[229]."</h1>";
		if($c['edit_message']=="yes") echo "<br />".$lang[205];
		else echo "<br /><center><strong>".$lang[645].": <br /><a href=\"".$h."c".$_POST['id_category']."-".$last_id.".html\">".$h."c".$_POST['id_category']."-".$last_id.".html/</a></strong></center><br />";
		if($c['money_service']=="yes") echo "<h4>".$lang[632]."<br /><a href=\"".$h."vip".$last_id.".html\">".$lang[635]."</a>.</h4><br />";
		?>
<br /><br /><h4><a href="<?=$h?>"><?=$lang[636]?> &rarr;</a></h4><h4><a href="<?=$h?>new.html">&larr; <?=$lang[155]?></a></h4><br /><br /></center><?
	}else{define("ALERT",$lang[572]);mysql_query($die_del_mess);require_once("add_new_form.inc.php");die();}
}else require_once("add_new_form.inc.php");
?>
</div>