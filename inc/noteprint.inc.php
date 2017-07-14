<div class="form-wrapper">
<?
if (@$_POST['board_check']){
	$impl=implode(', ',$_POST['board_check']);
	$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat';
	$query_mess = mysql_query("SELECT jb_board.id AS board_id, UNIX_TIMESTAMP(jb_board.date_add) as unix_time, DATE_FORMAT(jb_board.date_add,'%d.%m.%Y') as dat, jb_board.*, jb_board_cat.id, jb_board_cat.root_category, jb_board_cat.".$name_cat.", jb_city.city_name, jb_city.en_city_name FROM jb_board LEFT JOIN jb_board_cat ON jb_board.id_category = jb_board_cat.id LEFT JOIN jb_city ON jb_board.city_id = jb_city.id WHERE jb_board.id IN (".$impl.") AND jb_board.old_mess='old'"); cq();
	if (mysql_num_rows ($query_mess)){
		?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="https://www.w3.org/1999/xhtml"><head><title><?=(defined('USTITLE'))?USTITLE:$c['user_title']?></title><meta https-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="keywords" content="<?=(defined('USKEYWORDS'))?USKEYWORDS:$c['user_keywords']?>" /><meta name="description" content="<?=(defined('USDESCRIPTION'))?USDESCRIPTION:$c['user_description']?>" /><?=$stylecss?><script language="javascript">function printpage(){window.print();}</script></head><body><div style="margin:20px;"><img src="<?=$im?>logo.gif" /><br /><br /><?
		while($ads=mysql_fetch_assoc($query_mess)){
			$printcontacts = (@$ads['contacts'])?"<br />".nl2br($ads['contacts']):"";
			$printmail = (@$ads['email'])?"<br /><img src=\"flymail-".$ads['board_id'].".png\">":"";
			$printurl = (@$ads['url'])?"<br />".$lang[546].": <u>www.".$ads['url']."</u>":"";
			if (($ads['time_delete']*86400+$ads['unix_time']) > time()){
				if ($ads['dat']==date("d.m.Y")) $printdate=$lang[542];
				else $printdate=$lang[127].": ".$ads['dat']." ".$lang[543].".";
				$printdate.=" (".$lang[544].": ".strftime ( '%d.%m.%Y', $ads['time_delete'] * 86400 + $ads['unix_time'])." ".$lang[543].".)";
			}else{
				$printdate=$lang[1013];
				if ($c['view_nonactiv_contacts']=="no"){$printcontacts="";$printmail="";$printurl="";}
			}
			if($ads['type']=="s")$type_tit=$lang[414];elseif($ads['type']=="p")$type_tit=$lang[413];
			elseif($ads['type']=="u")$type_tit=$lang[800];else $type_tit=$lang[801];
			$printprice=($ads['price']!=0)?"<br />".$lang[1008].": <span class=\"b orange\">".$ads['price']." ".$lang[1010]."</span><br />":"";
			$printcity=($ads['city']==$lang[164])?"":"".$lang[220].": <span class=\"b\">".$ads['city']."</span>";
			$printmail=(@$ads['email'])?"<img align=\"left\" src=\"flymail-".$ads['board_id'].".png\"><div class=\"clear\"></div>":"";
			$photo=mysql_query("SELECT photo_name FROM jb_photo WHERE id_message='".$ads['board_id']."'");  
			if(@mysql_num_rows($photo)){
				$printphoto="<br /><br />";
				while($list_photo=mysql_fetch_assoc($photo))$printphoto.="<div style=\"float:left; margin:3px\"><img src=\"".$u."small/".$list_photo['photo_name']."\" /></div>";
				$printphoto.="<div class=\"clear\"></div>";
			}
			echo "<div class=\"sm gray\">".$lang[122].": ".$ads['name_cat']."<br /><img class=\"absmid\" alt=\"".$type_tit."\" src=\"".$im."type".$ads['type'].".gif\" /> ".$printdate."</div><br /><br /><div class=\"alcenter\"><h1>".$ads['title']."</h1></div><br />".nl2br($ads['text'])."<br />".$printprice.$printcity.@$printcontacts."<br />".@$printurl.@$printmail.@$printphoto."<hr /><br /><br /><br /";
		}
		?><div class="alcenter"><br /><br /><br /><?=$lang[568]?> <?=$h?><br /><br /><input onclick="printpage()" type="button" value="<?=$lang[569]?>"></div></div></body></html><?
	}
}else {header("location: ".$h);die();}
?>
</div>