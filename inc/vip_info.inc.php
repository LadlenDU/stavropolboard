<?
//########################## Настройки #########################################
$vip = 13011; //id услуги для vip
$sel = 13010; //id услуги для выделения
$language = 'ru'; //язык по умолчанию. Возможны значения 'ru','ua','en','lt'
$jquery = 'yes'; //если на сайте уже используется jQuery, необходимо поставить значение 'no'
$charset = 'UTF-8'; //Кодировка страниц сайта. По умолчанию UTF-8
$css = 'https://form.smsbill.com.ua//serviceform/getpassword/popup_v2.css'; //путь к файлу css, описывающий дизайн
//##############################################################################



if($c['wm_money_service']=="yes"){
	mysql_query("INSERT INTO jb_stat_wm SET id_board='".$_GET['id_mess']."',date=NOW()") or die(mysql_error());
	$last_id=mysql_insert_id();
}
?>
<div class="form-wrapper">
<h1 class="orange alcenter"><?=$lang[1127]?></h1>
<span class="gray"><?=$lang[1128]?> <a href="<?=$h?>p14.html" target="_blank"><?=$lang[1129]?></a>. <?=$lang[1153]?></span>
<br /><br /><br />
<div align="center">
	<img src="<?=$im?>vip.gif" alt="<?=$lang[1130]?>" class="absmid" /> 
	<a class="red b" style="margin-right:20px; border-bottom:1px dashed; text-decoration:none" href="?do=vip"><?=$lang[1130]?></a> 
	<img src="<?=$im?>lost.gif" alt="<?=$lang[1131]?>" class="absmid" /> 
	<a class="green b" style="border-bottom:1px dashed; text-decoration:none" href="?do=sel" ><?=$lang[1131]?></a>
	<div id="1" style="padding:5px; margin:20px;">
	<div id="sms_vip" style="text-align: left;">
	<?
if($c['money_service']=="yes" && isset($_GET['do']) && $_GET['do'] == "vip"){
			$smsbill = new SMSBill();
			$smsbill->setServiceId($vip);
			$smsbill->useEncoding($charset);
			$smsbill->useHeader('no');
			$smsbill->useCss($css);
			$smsbill->useJQuery($jquery);
			$smsbill->useLang($language);
			if (isset($_REQUEST['smsbill_password'])) {
				if (!$smsbill->checkPassword($_REQUEST['smsbill_password'])) { 
					//пароль не верный 
					echo '<b style="color:red">This is a wrong password. Please, come back and try once more.</b><br /><br />';
					} else { 
						//пароль верный
						mysql_query("UPDATE jb_board 
									SET checkbox_top=1, top_time=NOW(), time_delete=time_delete + ".$c['top_status_days']." 
									WHERE id='".$_GET['id_mess']."' LIMIT 1");
					echo 'Operation completed successfully.<br /><br />';
					}
			} else {
				//показать форму т.к. пароль не введен
				echo $smsbill->getForm();
			}
	
} ?> </div> 
<?
if($c['money_service']=="yes" && $c['wm_money_service']=="yes" && isset($_GET['do']) && $_GET['do'] == "vip") echo "<br /><h1 class=\"red\">ИЛИ</h1><br />";
if($c['wm_money_service']=="yes" && isset($_GET['do']) && $_GET['do'] == "vip"){
$pay_descr=$lang[1136].$lang[1141]." ".$_GET['id_mess']." ".$lang[1137];
?>
<div style="padding:10px;border: 2px dashed #F60;background-color:#FFFDF2">
<?=$lang[1134]?> <a href="https://www.webmoney.ru/rus/index.shtml" rel="nofollow" target="_blank">WebMoney</a><br />
<span class="sm"><?=$lang[1135]?> <strong><?=$c['wmprice_vip']?> <?=$c['wm_type']?></strong></span><br /><br />
<form accept-charset="windows-1251" method="post" action="https://merchant.webmoney.ru/lmi/payment.asp">
<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?=$c['wmprice_vip']?>" />
<input type="hidden" name="LMI_PAYMENT_DESC" value="<?=$pay_descr?>" />
<input type="hidden" name="LMI_PAYMENT_NO" value="<?=$last_id?>" />
<input type="hidden" name="LMI_PAYEE_PURSE" value="<?=$c['wm_purse']?>" />
<?
if($c['wm_mode']=="on")echo "<input type=\"hidden\" name=\"LMI_SIM_MODE\" value=\"0\" />";
?>
<input type="hidden" name="id_board" value="<?=$_GET['id_mess']?>" />
<input type="hidden" name="type" value="vip" />
<input type="hidden" name="last_id" value="<?=$last_id?>" />
<input type="submit" value="<?=$lang[1138]?> (<?=$c['wmprice_vip']?> <?=$c['wm_type']?>)" /></form></div>
<br />
<div style="padding:10px;border: 2px dashed #F60;background-color:#FFFDF2">
<?=$lang[1134]?> <a href="https://robokassa.ru/ru/" rel="nofollow" target="_blank">Robokassa</a><br />
<span class="sm"><?=$lang[1135]?> <strong><?=$c['wmprice_vip']?> руб.</strong></span><br /><br />
<? // регистрационная информация (логин, пароль #1) 
// registration info (login, password #1) 
$mrh_login = "stavropolboard"; 
$mrh_pass1 = "Gb1i4I2y6wxyMB3QefVW";

// номер заказа 
// number of order 
$inv_id = 0;

// описание заказа 
// order description 
$inv_desc = "Присвоение VIP-статуса";

// сумма заказа 
// sum of order 
$out_summ = "15.00";

// тип товара 
// code of goods 
$shp_item = 1;

// язык 
// language 
$culture = "ru";

// кодировка 
// encoding 
$encoding = "utf-8";

// формирование подписи 
// generate signature 
$crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:shp_Item=$shp_item");

// HTML-страница с кассой 
// ROBOKASSA HTML-page 
print "<html><script language=JavaScript ". "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormSS.js?". "MerchantLogin=$mrh_login&OutSum=$out_summ&InvoiceID=$inv_id". "&Description=$inv_desc&SignatureValue=$crc&shp_Item=$shp_item". "&Culture=$culture&Encoding=$encoding'></script></html>"; 
?>
</div>
<br />
<?=$lang[1150]?>
<br /><br />
<?
echo "<center>".$lang[254]."</center>";
if(@$_POST['submit']){
	if(@$_POST['message']&& @$_POST['email']&& @$_POST['securityCode']){
		if(@$_SESSION['securityCode']){
			if(utf8_strtolower($_POST['securityCode'])!=utf8_strtolower($_SESSION['securityCode']))die("<br /><br /><br /><center><span class=\"red b\">".$lang[116]."</span><br /><br /><span class=\"b\">".$lang[74]."</span></center>");
			if(!preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/iu',$_POST['email']))die("<br /><br /><br /><center><span class=\"red b\">".$lang[582]."</span><br /><br /><span class=\"b\">".$lang[74]."</span></center>");
			if(sendmailer($c['admin_mail'],"<".$_POST['email'].">",$lang[649],$_POST['message']))echo "<br /><br /><br /><center><span class=\"red b\">".$lang[186]."</span><br /><br /><span class=\"b\">".$lang[173]."</span></center>";
			else echo "<br /><br /><br /><center><span class=\"red b\">".$lang[187]."</span><br /><br /><span class=\"b\">".$lang[173]."</span></center>";
			$_SESSION['securityCode']=md5($_POST['message']);
		}else echo "<br /><br /><br /><center><span class=\"red b\">".$lang[255]."</span><br /><br /><span class=\"b\">".$lang[74]."</span></center>";
	}else echo "<br /><br /><br /><center><span class=\"red b\">".$lang[255]."</span><br /><br /><span class=\"b\">".$lang[74]."</span></center>";
}else{
	?><div class="addform" align="center"><br /><br /><br /><form action="<?=$h?>contacts.html" method="post"><div class="lc"><?=$lang[196]?><span class="req">*</span></div><div class="rc"><input type="text" name="email" size="50" value="<?=htmlspecialchars(@$_POST['email'])?>" /></div><div class="pad"></div><div class="lc"><?=$lang[105]?><span class="req">*</span></div><div class="rc"><textarea cols="38" rows="6" name="message"><?=htmlspecialchars(@$_POST['message'])?></textarea></div><div class="pad"></div><div class="lc"><?=$lang[203]?><span class="req">*</span></div><div class="rc"><img alt="<?=$lang[203]?>" class="absmid" id="hello_bot" src="code.gif?<?=microtime()?>" /><input id="cptch" type="text" name="securityCode" /><br /><a href="#" onclick="document.getElementById('hello_bot').src='code.gif?'+Math.random();return false;"><?=$lang[2031]?></a></div><div class="pad"></div><input name="submit" style="width:70%;" type="submit" value=" <?=$lang[199]?> " /></form></div><?
}
?>
<?	
}
?>
</div>
<div id="2" style="padding:5px; margin:20px;">
<div id="sms_sel" style="text-align: center;">
<?
if($c['money_service']=="yes" && isset($_GET['do']) && $_GET['do'] == "sel"){
			$smsbill = new SMSBill();
			$smsbill->setServiceId($sel);
			$smsbill->useEncoding($charset);
			$smsbill->useHeader('no');
			$smsbill->useCss($css);
			$smsbill->useJQuery($jquery);
			$smsbill->useLang($language);
			if (isset($_REQUEST['smsbill_password'])) {
				if (!$smsbill->checkPassword($_REQUEST['smsbill_password'])) { 
					//пароль не верный 
					echo '<b style="color:red">This is a wrong password. Please, come back and try once more.</b><br /><br />';
					} else { 
						//пароль верный
					mysql_query("UPDATE jb_board SET checkbox_select=1,
													select_time=NOW(),
													time_delete=time_delete + ".$c['select_status_days']." 
													WHERE id='".$_GET['id_mess']."' LIMIT 1");
					echo "Operation completed successfully.<br /><br />";
					}
			} else {
				//показать форму т.к. пароль не введен
				echo $smsbill->getForm();
			}
	
} ?> 
</div> 
<?
if($c['money_service']=="yes" && $c['wm_money_service']=="yes" && isset($_GET['do']) && $_GET['do'] == "sel") echo "<br /><h1 class=\"red\">ИЛИ</h1><br />";
if($c['wm_money_service']=="yes" && isset($_GET['do']) && $_GET['do'] == "sel"){
	$pay_descr=$lang[1140].$lang[1141]." ".$_GET['id_mess'];
?>
<div style="padding:10px;border: 2px dashed #090;background-color:#F5FFF2">
<?=$lang[1134]?> <a href="https://www.webmoney.ru/rus/index.shtml" rel="nofollow">WebMoney</a><br />
<span class="sm"><?=$lang[1135]?> <strong><?=$c['wmprice_select']?> <?=$c['wm_type']?></strong></span><br /><br />
<form accept-charset="windows-1251" method="post" action="https://merchant.webmoney.ru/lmi/payment.asp">
<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?=$c['wmprice_select']?>" />
<input type="hidden" name="LMI_PAYMENT_DESC" value="<?=$pay_descr?>" />
<input type="hidden" name="LMI_PAYMENT_NO" value="<?=$last_id?>" />
<input type="hidden" name="LMI_PAYEE_PURSE" value="<?=$c['wm_purse']?>" />
<?
if($c['wm_mode']=="on")echo "<input type=\"hidden\" name=\"LMI_SIM_MODE\" value=\"0\" />";
?>
<input type="hidden" name="id_board" value="<?=$_GET['id_mess']?>" />
<input type="hidden" name="type" value="sel" />
<input type="hidden" name="last_id" value="<?=$last_id?>" />
<input type="submit" value="<?=$lang[1138]?> (<?=$c['wmprice_select']?> <?=$c['wm_type']?>)" />
</form>
</div>
<br />
<div style="padding:10px;border: 2px dashed #090;background-color:#F5FFF2">
<?=$lang[1134]?> <a href="https://robokassa.ru/ru/" rel="nofollow" target="_blank">Robokassa</a><br />
<span class="sm"><?=$lang[1135]?> <strong><?=$c['wmprice_select']?> руб.</strong></span><br /><br />
<? // регистрационная информация (логин, пароль #1) 
// registration info (login, password #1) 
$mrh_login = "stavropolboard"; 
$mrh_pass1 = "Gb1i4I2y6wxyMB3QefVW";

// номер заказа 
// number of order 
$inv_id = 0;

// описание заказа 
// order description 
$inv_desc = "Выделение объявления";

// сумма заказа 
// sum of order 
$out_summ = "5.00";

// тип товара 
// code of goods 
$shp_item = 2;

// язык 
// language 
$culture = "ru";

// кодировка 
// encoding 
$encoding = "utf-8";

// формирование подписи 
// generate signature 
$crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:shp_Item=$shp_item");

// HTML-страница с кассой 
// ROBOKASSA HTML-page 
print "<html><script language=JavaScript ". "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormSS.js?". "MerchantLogin=$mrh_login&OutSum=$out_summ&InvoiceID=$inv_id". "&Description=$inv_desc&SignatureValue=$crc&shp_Item=$shp_item". "&Culture=$culture&Encoding=$encoding'></script></html>"; 
?>
</div>
<br />
<?=$lang[1150]?>
<br /><br />
<?
echo "<center>".$lang[254]."</center>";
if(@$_POST['submit']){
	if(@$_POST['message']&& @$_POST['email']&& @$_POST['securityCode']){
		if(@$_SESSION['securityCode']){
			if(utf8_strtolower($_POST['securityCode'])!=utf8_strtolower($_SESSION['securityCode']))die("<br /><br /><br /><center><span class=\"red b\">".$lang[116]."</span><br /><br /><span class=\"b\">".$lang[74]."</span></center>");
			if(!preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/iu',$_POST['email']))die("<br /><br /><br /><center><span class=\"red b\">".$lang[582]."</span><br /><br /><span class=\"b\">".$lang[74]."</span></center>");
			if(sendmailer($c['admin_mail'],"<".$_POST['email'].">",$lang[649],$_POST['message']))echo "<br /><br /><br /><center><span class=\"red b\">".$lang[186]."</span><br /><br /><span class=\"b\">".$lang[173]."</span></center>";
			else echo "<br /><br /><br /><center><span class=\"red b\">".$lang[187]."</span><br /><br /><span class=\"b\">".$lang[173]."</span></center>";
			$_SESSION['securityCode']=md5($_POST['message']);
		}else echo "<br /><br /><br /><center><span class=\"red b\">".$lang[255]."</span><br /><br /><span class=\"b\">".$lang[74]."</span></center>";
	}else echo "<br /><br /><br /><center><span class=\"red b\">".$lang[255]."</span><br /><br /><span class=\"b\">".$lang[74]."</span></center>";
}else{
	?><div class="addform" align="center"><br /><br /><br /><form action="<?=$h?>contacts.html" method="post"><div class="lc"><?=$lang[196]?><span class="req">*</span></div><div class="rc"><input type="text" name="email" size="50" value="<?=htmlspecialchars(@$_POST['email'])?>" /></div><div class="pad"></div><div class="lc"><?=$lang[105]?><span class="req">*</span></div><div class="rc"><textarea cols="38" rows="6" name="message"><?=htmlspecialchars(@$_POST['message'])?></textarea></div><div class="pad"></div><div class="lc"><?=$lang[203]?><span class="req">*</span></div><div class="rc"><img alt="<?=$lang[203]?>" class="absmid" id="hello_bot" src="code.gif?<?=microtime()?>" /><input id="cptch" type="text" name="securityCode" /><br /><a href="#" onclick="document.getElementById('hello_bot').src='code.gif?'+Math.random();return false;"><?=$lang[2031]?></a></div><div class="pad"></div><input name="submit" style="width:70%;" type="submit" value=" <?=$lang[199]?> " /></form></div><?
}
?>
<?	
}
?>
</div>
</div>
</div>