<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

if(!defined('SITE'))die();
if(@$_GET['op']=="createfiles"){
	mysql_query("TRUNCATE TABLE `jb_subscribe`");cq();
	$all=mysql_query("SELECT id,id_category,autor,email FROM jb_board WHERE email!='' AND autor!='' GROUP by email");cq();
	$count=mysql_num_rows($all);
    if($count){
		while($d=mysql_fetch_assoc($all)){
			$email=trim(utf8_strtolower($d['email']));
			if(preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/iu',$email)){
				mysql_query("INSERT jb_subscribe SET mail='".$email."', username='".$d['autor']."', id_board='".$d['id']."', id_cat='".$d['id_category']."'");cq();
		}}
		$countpos=mysql_result(mysql_query("select count(*) from jb_subscribe"),0);cq();
		echo "<div align=\"center\"><h1>".$lang[440]."</h1><br /><br />".$lang[446]."<br />".$lang[447].": <strong>".@$countpos."</strong>.</div>";
    }
}else{
    $allcount=mysql_num_rows(mysql_query("SELECT id FROM jb_board WHERE email !='' AND autor!='' GROUP by email"));cq();
	$countpos=mysql_result(mysql_query("select count(*) from jb_subscribe"),0);cq();
	$k=md5($c['admin_mail']);
	?><div align="center"><h1><?=$lang[440]?></h1><br /><br />
    <a class="green b" style="margin-right:20px" href="<?=$h?>a/?action=subscribe&op=createfiles"><?=$lang[441]?></a> 
    <a target="_blank" class="green b" href="<?=$h?>a/user_subscribe.php?k=<?=$k?>"><?=$lang[443]?></a>
    <br /><br /><br /><strong><?=$lang[444]?>: <?=$allcount?></strong><br />
	<?=$lang[1075]?>: <?=$countpos?><br /><br /> 	
    <br /><br /><?=$lang[442]?> <a class="gray b" href="<?=$h?>a/?action=setting"><?=$lang[2]?></a></div><?
}
?>