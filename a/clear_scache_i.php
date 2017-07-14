<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

define('SITE',true);
include("../admin/conf.php");
$arr_dir=array("../cache/exp/js/","../cache/exp/php/","../cache/exp/rss/");
foreach($arr_dir as $k=>$v){
	$dirname=$v;$d=opendir($dirname);while($f=readdir($d)){if($f!="."&&$f!="..")unlink($dirname.$f);}closedir($d);
}
mysql_query("UPDATE jb_maintenance SET clear_scache_i=NOW() WHERE id=0");
echo $lang[400];
?>