<?
#!/usr/local/bin/php
define('SITE',true);
include("../admin/conf.php");
$queryv=mysql_query("SELECT id,id_category,email,autor,title from jb_board WHERE checkbox_top='1' AND email != '' AND send_notice_vip_sms='0' AND (DATE(top_time)+ INTERVAL ".$c['top_status_days']." DAY)- CURDATE() <= ".$c['day_for_notice_autor']);
$countv=mysql_num_rows($queryv);
if(@$countv){
	while($userv=mysql_fetch_assoc($queryv)){
		$msg=$lang[559]." ".$userv['autor'].".".$lang[565]." ".$userv['title'].") ".$lang[561]." ".$h.". ".$lang[562]." ".$h."c".$userv['id_category']."-".$userv['id'].".html ".$lang[563]; 
		sendmailer($userv['email'],$c['admin_mail'],$lang[564],$msg);
		$upd=mysql_query("UPDATE jb_board SET send_notice_vip_sms='1' WHERE id='".$userv['id']."'");  
}}
$querys=mysql_query("SELECT id,id_category,email,autor,title from jb_board WHERE checkbox_select='1' AND email != '' AND send_notice_select_sms='0' AND (DATE(select_time)+ INTERVAL ".$c['select_status_days']." DAY)- CURDATE()<=".$c['day_for_notice_autor']);
$counts=mysql_num_rows($querys);
if($counts){
	while($usersel=mysql_fetch_assoc($querys))	{
		$msg=$lang[559]." ".$usersel['autor'].".".$lang[567]." ".$usersel['title'].") ".$lang[561]." ".$h.". ".$lang[562]." ".$h."c".$usersel['id_category']."-".$usersel['id'].".html ".$lang[563]; 
		sendmailer($usersel['email'],$c['admin_mail'],$lang[566],$msg);
		$upd=mysql_query("UPDATE jb_board SET send_notice_select_sms='1' WHERE id='".$usersel['id']."'");  
}}
mysql_query("UPDATE jb_maintenance SET notice_paid=NOW() WHERE id=0");
echo $lang[1074].": ".(@$countv+@$counts);
?>