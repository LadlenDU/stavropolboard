<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

if(!defined('SITE'))die();
if(@$_POST['login'] && @$_POST['password']){
	$adm_login=clean($_POST['login']);
	$adm_password=clean($_POST['password']);
	$_SESSION['login']=$adm_login;
	$_SESSION['password']=$adm_password;
	$change=mysql_query("UPDATE jb_admin SET login='".$adm_login."', password='".md5($adm_password)."'"); 
	echo($change)?"<center><strong>".$lang[193]."</strong></center>":"<center><strong>".$lang[98]."</strong></center>";
}else{
	$query=mysql_query("SELECT * FROM jb_admin");
	$profile=mysql_fetch_assoc($query);
	?><div align="center"><h1><?=$lang[3]?></h1><br /><form method="post" action="<?=$h?>a/?action=profile"><table class="sort"><tr bgcolor="#F6F6F6"><td><?=$lang[13]?>:</td><td><input name="login" type="text" value="<?=$profile['login']?>"></td></tr><tr bgcolor="#F6F6F6"><td><?=$lang[14]?>:</td><td><input name="password" type="password" value="********"></td></tr><tr><td colspan="2" align="center"><input type="submit" value="<?=$lang[15]?>"></td></tr></table></form></div><?
}
?>