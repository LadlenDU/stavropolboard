<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

require_once("../../admin/conf.php");
$sus=iconv("utf-8","windows-1251",$lang[494]);
$fail_script=iconv("utf-8","windows-1251",$lang[495]);
$fail_code=iconv("utf-8","windows-1251",$lang[496]);
if(ctype_digit(@$_REQUEST['txt']) && (utf8_strtolower(@$_REQUEST['pref'])== utf8_strtolower($c['top_prefix']) || utf8_strtolower(@$_REQUEST['pref'])== utf8_strtolower($c['select_prefix']))){
	$id=$_REQUEST['txt'];
	$num_r=mysql_query("SELECT id,id_category FROM jb_board WHERE id='".$id."'");
	if(@mysql_num_rows($num_r))	{
		if(@$_REQUEST['op'])$operator=clean($_REQUEST['op']);
		if(@$_REQUEST['to'])$numb_phone=clean($_REQUEST['to']);
		elseif(@$_REQUEST['phone'])$numb_phone=clean($_REQUEST['phone']);
		$prefix=utf8_strtolower($_REQUEST['pref']);
		if(@$_REQUEST['from'])$short_number=htmlspecialchars($_REQUEST['from']);
		elseif(@$_REQUEST['sn'])$short_number=htmlspecialchars($_REQUEST['sn']);
		$data_query="INSERT jb_stat_sms SET numb_phone='".@$numb_phone."', operator='".@$operator."', id_board='".$id."', date=NOW()";
		if($prefix == utf8_strtolower($c['top_prefix'])){
			if($short_number != $c['top_number'])die("sms=".$fail_code." 3");
			if(mysql_query("UPDATE jb_board SET checkbox_top=1,top_time=NOW(),time_delete=time_delete + ".$c['top_status_days']." WHERE id='".$id."' LIMIT 1")){
				$query=mysql_query($data_query);
				echo "sms=".$sus;
			}else die("sms=".$fail_script." 1");
		}elseif($prefix == utf8_strtolower($c['select_prefix'])){
			if($short_number != $c['select_number'])die("sms=".$fail_code." 4");
			if(mysql_query("UPDATE jb_board SET checkbox_select=1,select_time=NOW(),time_delete=time_delete + ".$c['select_status_days']." WHERE id='".$id."' LIMIT 1")){
				$query=mysql_query ($data_query);
				echo "sms=".$sus;
			}else die("sms=".$fail_script." 2");
		}else die("sms=".$fail_code." 5");
	}else die("sms=".$fail_code." 6");
}else die("sms=".$fail_code." 7");
$list_ccdel=mysql_fetch_assoc($num_r);
$dirname="../../cache/";
$dir=opendir($dirname);
while($file=readdir($dir)){
	if($file!="." && $file!=".." && $file!=".htaccess"){
		if(utf8_substr($file,0,8)=="newlist-" || utf8_substr($file,0,(utf8_strlen($list_ccdel['id_category'])+2))=="c".$list_ccdel['id_category']."-")unlink($dirname.$file);
}}
closedir ($dir);
?>