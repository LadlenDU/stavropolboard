<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

if(!defined('SITE'))die();
if(@$_POST['subject'] && @$_POST['to'] && @$_POST['from'] && @$_POST['message']){
	if(sendmailer($_POST['to'],$_POST['from'],$_POST['subject'],$_POST['message']))echo "<center><h1 class=\"red\">".$lang[186]."</h1></center><br /><br />";else echo "<center><h1 class=\"red\">".$lang[187]."</h1></center><br /><br />";
}
?><script language="javascript">function bbbbbt(text,field){$(field).value=text;$(field).focus();}</script><div align="center"><h1><?=$lang[1076]?></h1><br /><br /><form method="post" action="<?=$h?>a/?action=sendmail"><?=$lang[196]?>:<br /><input id="from" type="text" name="from" size="70"><br /><a style="border-bottom:1px dashed #3399FF; text-decoration:none" href="javascript:bbbbbt('net-otveta@<?=$_SERVER['HTTP_HOST']?>','from')">net-otveta@<?=$_SERVER['HTTP_HOST']?></a> &nbsp; &nbsp; <a style="border-bottom:1px dashed #3399FF; text-decoration:none" href="javascript:bbbbbt('<?=$c['admin_mail']?>','from')"><?=$c['admin_mail']?></a><br /><br /><br /><?=$lang[1078]?><br /><input type="text" name="to" size="70"><br /><br /><br /><?=$lang[197]?><br /><input id="subject" type="text" name="subject" size="70"><br /><br /><br /><?=$lang[198]?><br /><textarea cols="53" rows="9" name="message"></textarea><br /><br /><br /><input style="width:400px" type="submit" value="<?=$lang[199]?>"></form></div>