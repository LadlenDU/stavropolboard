<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

define('SITE',true);
include("../admin/conf.php");
$queryv=mysql_query("SELECT id FROM jb_board WHERE checkbox_top='1' AND DATE(top_time) + INTERVAL ".$c['top_status_days']." DAY < CURDATE() + INTERVAL 1 DAY");
$countv=mysql_num_rows($queryv);
if($countv){
	$where_in_v="0";
	while($clearv=mysql_fetch_assoc($queryv))$where_in_v.=",".$clearv['id'];
	mysql_query("UPDATE jb_board SET checkbox_top='0',top_time='0',send_notice_vip_sms='0' WHERE id IN (".$where_in_v.")");
}
$querys=mysql_query("SELECT id from jb_board WHERE checkbox_select = 1 AND DATE(select_time) + INTERVAL ".$c['select_status_days']." DAY < CURDATE() + INTERVAL 1 DAY");
$countsel=mysql_num_rows($querys);
if ($countsel){
	$where_in_s="0";
	while($clears=mysql_fetch_assoc($querys))$where_in_s.=",".$clears['id'];
	mysql_query("UPDATE jb_board SET checkbox_select='0',select_time='0',send_notice_select_sms='0' WHERE id IN (".$where_in_s.")");
}
mysql_query("UPDATE jb_maintenance SET recosted_paid=NOW() WHERE id=0");
echo $lang[193].". ".(@$countv+@$countsel)." ".PluralForm((@$countv+@$countsel),$lang[262],$lang[263],$lang[264]);
?>