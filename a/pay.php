<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

define('SITE',true);
include("../admin/conf.php");
$er="Inconsistent parameters<br />";
$pre_type=clean($_POST['type']);
$endlast_id=intval($_POST['last_id']);
$endtype=clean($_POST['type']);
$endid_board=intval($_POST['id_board']);
$endlast_id=intval($_POST['last_id']);
$clbd="DELETE FROM jb_stat_wm WHERE id='".$endlast_id."' LIMIT 1";
if(@$_POST['LMI_PREREQUEST']=='1'){
	if($pre_type=="vip"){if($_POST['LMI_PAYMENT_AMOUNT']!=$c['wmprice_vip']){mysql_query($clbd);die($er."<br />1");}}
	elseif($pre_type=="sel"){if($_POST['LMI_PAYMENT_AMOUNT']!=$c['wmprice_select']){mysql_query($clbd);die($er."<br />2");}}
	else {mysql_query($clbd);die($er."<br />3");}
	mysql_query("SELECT id,id_category FROM jb_board WHERE id='".intval($_POST['id_board'])."'") or die($er."<br />4<br />".mysql_error());
	mysql_query("SELECT id FROM jb_stat_wm WHERE id='".intval($_POST['last_id'])."' AND completed='no'") or die(mysql_error()."<br />5");
	mysql_query("UPDATE jb_stat_wm SET type='".$pre_type."',date=NOW() WHERE id='".intval($_POST['last_id'])."' LIMIT 1") or die(mysql_error()."<br />6");
	echo "YES";
}
elseif(!@$_POST['LMI_PREREQUEST'] && @$_POST['LMI_SYS_TRANS_NO']){
	if($endtype=="vip"){if($_POST['LMI_PAYMENT_AMOUNT']!=$c['wmprice_vip']){mysql_query($clbd);die($er."<br />7");}}
	elseif($endtype=="sel"){if($_POST['LMI_PAYMENT_AMOUNT']!=$c['wmprice_select']){mysql_query($clbd);die($er."<br />8");}}
	else {mysql_query($clbd);die($er."<br />9");}
	$my_hash = $_POST['LMI_PAYEE_PURSE'].$_POST['LMI_PAYMENT_AMOUNT'].$_POST['LMI_PAYMENT_NO'].$_POST['LMI_MODE'].$_POST['LMI_SYS_INVS_NO'].$_POST['LMI_SYS_TRANS_NO'].$_POST['LMI_SYS_TRANS_DATE'].$_POST['LMI_SECRET_KEY'].$_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM'];
	$my_md5hash=md5($my_hash);
	$my_md5hash=utf8_uppercase($my_md5hash);
	if($my_md5hash != $_POST['LMI_HASH']){mysql_query($clbd);die("Incorrect signature<br />10");}
	mysql_query("SELECT id,id_category FROM jb_board WHERE id='".$endid_board."'") or die($er."<br />11<br />".mysql_error());
	mysql_query("SELECT id FROM jb_stat_wm WHERE id='".$endlast_id."' AND completed='no'") or die($er."<br />12<br />".mysql_error());
	mysql_query("UPDATE jb_stat_wm SET purse='".$_POST['LMI_PAYER_PURSE']."', wmid='".$_POST['LMI_PAYER_WM']."', type='".$endtype."', completed='yes', id_board='".$endid_board."', date=NOW() WHERE id='".$endlast_id."' LIMIT 1") or die(mysql_error()."<br />13");
	if($endtype=="vip") mysql_query("UPDATE jb_board SET checkbox_top=1, top_time=NOW() WHERE id='".$endid_board."' LIMIT 1") or die(mysql_error()."<br />13-3");
	elseif($endtype=="sel") mysql_query("UPDATE jb_board SET checkbox_select=1, select_time=NOW() WHERE id='".$endid_board."' LIMIT 1") or die(mysql_error()."<br />13-1");
	else {mysql_query($clbd);die("13-2");}
}
else {mysql_query($clbd);die("14");}
?>