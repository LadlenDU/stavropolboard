<?
#!/usr/local/bin/php
define('SITE',true);
include("../admin/conf.php");
$query=mysql_query ("SELECT id,id_category,autor,title,email from jb_board WHERE send_notice_day=0 AND email !='' AND DATE(date_add) + INTERVAL time_delete DAY <= CURDATE() + INTERVAL ".$c['day_for_notice_autor']." DAY")or die();
$count=mysql_num_rows($query);
if(@$count){
	while($user=mysql_fetch_assoc($query)){
		$msg=$lang[559]." ".$user['autor'].".\r\n".$lang[560].": ".$user['title'].") ".$lang[561]." ".$h." . ".$lang[562]." ".$h."c".$user['id_category']."-".$user['id'].".html \r\n-----------------------------------------".$lang[563];
		sendmailer($user['email'],$c['admin_mail'],$lang[558],$msg);
		$upd=mysql_query("UPDATE jb_board SET send_notice_day='1' WHERE id='".$user['id']."'");
}}
mysql_query("UPDATE jb_maintenance SET notice_expired=NOW() WHERE id=0");
echo $lang[1074].": ".$count;
?>