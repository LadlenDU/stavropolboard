<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

define('SITE',true);
include("../admin/conf.php");
$dirname="../cache/";
$d=opendir($dirname);
while($f=readdir($d)){if($f!="."&&$f!=".."&&$f!=".htaccess"&&$f!="exp")unlink($dirname.$f);}
closedir($d);
mysql_query("UPDATE jb_maintenance SET clear_scache_b=NOW() WHERE id=0");
echo $lang[400];
?>