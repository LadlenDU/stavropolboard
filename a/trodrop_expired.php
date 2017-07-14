<?
#!/usr/local/bin/php
define('SITE',true);
include("../admin/conf.php");
$del=mysql_query("SELECT id FROM jb_board WHERE DATE(date_add) + INTERVAL time_delete DAY < CURDATE()"); 
$count=mysql_num_rows($del);
if(@$count){
	while($q=mysql_fetch_assoc($del)){
		$p_del=mysql_query("SELECT id_photo,photo_name FROM jb_photo WHERE id_message = '".$q['id']."'");   
		if(@mysql_num_rows($p_del)){
			while($list=mysql_fetch_assoc($p_del)){
				if(file_exists("../upload/small/".$list['photo_name']))unlink("../upload/small/".$list['photo_name']);
				if(file_exists("../upload/normal/".$list['photo_name']))unlink("../upload/normal/".$list['photo_name']);
				mysql_query("DELETE FROM jb_photo WHERE id_photo='".$list['id_photo']."' LIMIT 1");
		}}
		mysql_query("DELETE FROM jb_board WHERE id='".$q['id']."'");
		mysql_query("DELETE FROM jb_abuse WHERE id_board='".$q['id']."'");
		mysql_query("DELETE FROM jb_comments WHERE id_board='".$q['id']."'");
		mysql_query("DELETE FROM jb_notes WHERE id_board=".$q['id']."'");
	}
}
echo $lang[400].". ".$count." ".PluralForm($count,$lang[262],$lang[263],$lang[264])." ".$lang[63];
mysql_query("UPDATE jb_maintenance SET drop_expired=NOW() WHERE id=0");
?>