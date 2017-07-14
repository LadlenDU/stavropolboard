<link href="../images/style.css" rel="stylesheet" type="text/css" />
<div class="form-wrapper">
<div itemscope itemtype="https://schema.org/Organization">
<span itemprop="name">StavropolBoard</span>
Контакты:
<div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
Адрес:
<span itemprop="addressRegion">Ставропольский край</span>,
<span itemprop="addressLocality">город Ставрополь</span>,
<span itemprop="streetAddress">улица Пирогова, 56</span>,
Почтовый индекс:<span itemprop="postalCode">355042</span>,
</div>
Телефон:<span itemprop="telephone">+7-924-139-16-02</span>,
Электронная почта: <span itemprop="email">info@stavropolboard.ru</span>
</div>
</div>

<div class="form-wrapper">
<?
echo "<center><h2>".$lang[254]."</h2></center>";
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
</div>