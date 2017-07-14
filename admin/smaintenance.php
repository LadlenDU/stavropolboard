<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

if(!defined('SITE'))die();
$query=mysql_query("SELECT * FROM jb_maintenance WHERE id=0"); 
$d=mysql_fetch_assoc($query);
?>
<div align="center">
<h1><?=$lang[1079]?></h1><br />
<table class="sort">
<tr bgcolor="#F6F6F6">
<td style="padding:10px">
<h3 class="alcenter"><?=$lang[1088]?></h3>
<ul style="margin:20px;line-height:20px;">
<li><a target="_blank" href="<?=$h?>a/notice_expired.php"><?=$lang[1081]?></a> <span class="sm"><?=$lang[1094]?>: <strong><?=$d['notice_expired']?></strong></span></li>
<li><a target="_blank" href="<?=$h?>a/notice_paid.php"><?=$lang[1083]?></a> <span class="sm"><?=$lang[1094]?>: <strong><?=$d['notice_paid']?></strong></span></li></ul></td></tr>
<tr bgcolor="#F6F6F6">
<td style="padding:10px">
<h3 class="alcenter"><?=$lang[1089]?></h3>
<ul style="margin:20px;line-height:20px;">
<li><a target="_blank" href=""><?=$lang[1080]?></a> <span class="sm"><?=$lang[1094]?>: <strong><?=$d['drop_expired']?></strong></span></li>
<li><a target="_blank" href="<?=$h?>a/recosted_paid.php"><?=$lang[1082]?></a> <span class="sm"><?=$lang[1094]?>: <strong><?=$d['recosted_paid']?></strong></span></li></ul></td></tr>
<tr bgcolor="#F6F6F6">
<td style="padding:10px">
<h3 class="alcenter">Sitemap</h3>
<ul style="margin:20px;line-height:20px;">
<li><a target="_blank" href=""><?=$lang[1095]?></a> <span class="sm"><?=$lang[1094]?>: <strong><?=$d['create_sitemap']?></strong></span></li>
<li><a class="sm gray" target="_blank" href="">Link to sitemap.xml</a></li></ul></td></tr>
<tr bgcolor="#F6F6F6">
<td style="padding:10px">
<h3 class="alcenter"><?=$lang[1090]?></h3>
<ul style="margin:20px;line-height:20px;">
<li><a target="_blank" href="<?=$h?>a/clear_scache_b.php"><?=$lang[1091]?></a> <span class="sm"><?=$lang[1094]?>: <strong><?=$d['clear_scache_b']?></strong></span></li>
<li><a target="_blank" href="<?=$h?>a/clear_scache_i.php"><?=$lang[1092]?></a> <span class="sm"><?=$lang[1094]?>: <strong><?=$d['clear_scache_i']?></strong></span></li>
<li><a target="_blank" href="<?=$h?>a/clear_kcache.php"><?=$lang[1093]?></a> <span class="sm"><?=$lang[1094]?>: <strong><?=$d['clear_kcache']?></strong></span></li></ul></td></tr></table></div>