<div class="form-wrapper">

<?
if($c['add_new_news']!="yes"){header("location: ".$h."news.html");}
else{
	if(@$_POST['short'] && @$_POST['full'] && @$_POST['title']){
		if ($c['captcha']=="yes"){
			if(@$_POST['securityCode']&& @$_SESSION['securityCode']){
				if(utf8_strtolower($_POST['securityCode'])!=utf8_strtolower($_SESSION['securityCode']))die("<center><strong>".$lang[116]."</strong><br />".$lang[74]."</center>");
				$_SESSION['securityCode']=md5($_POST['title']);
			} 
		}
		$title=trim($_POST['title']);$title=clean($title);
		$translit=translit($_POST['title']);
		$short=trim($_POST['short']);$short=clean($short);
		$full=trim($_POST['full']);$full=cleansql($full);
		if(@$_POST['keywords']){$keywords=trim($_POST['keywords']);$keywords=clean($keywords);}else $keywords="";
		if(@$_POST['descr']){$descr=trim($_POST['descr']);$descr=clean($descr);}else $descr="";
		if(@$_POST['autor']){$autor=trim($_POST['autor']);$autor=clean($autor);}else $autor="";
		$insert=mysql_query("INSERT jb_news SET title='".$title."', autor='".$autor."', translit='".$translit."', short='".$short."', full='".$full."', keywords='".$keywords."', descr='".$descr."', old_mess='new', date=NOW()");
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
						}else{
							$update=mysql_query("UPDATE jb_news SET logo='".$filename."' WHERE id='".$last_id."' LIMIT 1");cq();
							if(!$update){
								if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$filename))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/news/".$filename);
								mysql_query($die_del_mess); cq();
								die("<center><strong>".$lang[411]." ".$_FILES['logo']['name']." ".$lang[227].$lang[173]."</strong></center>");
					}}}else{
						mysql_query($die_del_mess);cq();
						die("<center><strong>".$lang[641]." ".$_FILES['logo']['name']." ".$lang[642].$lang[173]."</strong></center>");
			}}}
			echo "<center><strong>".$lang[1099]."</strong></center>";
		}else echo "<center><strong>".$lang[98]."</strong></center>";
	}else{
		?><div class="addform" align="center"><form action="<?=$h?>addnews.html" method="post" enctype="multipart/form-data" name="add_form" onsubmit="return check_fields_news();"><h1 class="alcenter"><?=$lang[292]?></h1><br /><br /><div class="lc"><?=$lang[78]?><span class="req">*</span></div><div class="rc"><input type="text" name="title" size="50" /></div><div class="pad"></div><div class="lc"><?=$lang[1056]?><span class="req">*</span></div><div class="rc"><textarea name="short" rows="2" cols="38"></textarea></div><div class="pad"></div><div class="lc"><?=$lang[1054]?></div><div class="rc"><input type="text" name="keywords" size="50" /></div><div class="pad"></div><div class="lc"><?=$lang[1055]?></div><div class="rc"><input type="text" name="descr" size="50" /></div><div class="pad"></div>
		<div class="lc"><?=$lang[287]?><span class="req">*</span></div><div class="rc"><textarea name="full" rows="6" cols="38"></textarea></div><div class="pad"></div>
		<div class="lc"><?=$lang[100]?></div><div class="rc"><input type="text" name="autor" size="50" /></div><div class="pad"></div>
		<?
	if($c['upload_images']=="yes"){
		?><div class="lc"><?=$lang[223]?></div><div class="rc"><input type="file" name="logo" /></div><div class="pad"></div><?
	}
	if ($c['captcha']=="yes"){
		?><div class="lc"><?=$lang[203]?><span class="req">*</span></div><div class="rc"><img alt="<?=$lang[203]?>" class="absmid" id="hello_bot" src="code.gif?<?=microtime()?>" /><input id="cptch" type="text" name="securityCode" /><br /><a href="#" onclick="document.getElementById('hello_bot').src='code.gif?'+Math.random();return false;"><?=$lang[2031]?></a></div><div class="pad"></div><?
	}
	?><div align="center"><strong style="color:#FF0000"><?=$lang[206]?></strong><br /><br /><input name="submit" style="width:70%;" type="submit" value=" <?=$lang[155]?> " /></div></form></div><?
	}
}
?>
</div>