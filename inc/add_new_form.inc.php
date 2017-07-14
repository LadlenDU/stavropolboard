<div class="addform" align="center"><form action="<?=$h?>new.html" method="post" enctype="multipart/form-data" name="add_form" onsubmit="return check_fields();"><h1 class="alcenter">Подать объявление Ставрополь</h1><br /><br /><div class="lc"><?=$lang[123]?><span class="req">*</span></div><div class="rc"><input maxlength="<?=$c['count_symb_title']?>" type="text" name="title" size="50" value="<?=htmlspecialchars(@$_POST['title'])?>" /></div><div class="rc">
</div><div class="pad"></div><div class="lc"><?=$lang[412]?><span class="req">*</span></div><div class="rc"><select name="type"><option value="0"><?=$lang[620]?></option><option value="s" <? if (@$_POST['type']=="s") echo "selected=\"selected\""; ?>><?=$lang[414]?></option><option value="p" <? if (@$_POST['type']=="p") echo "selected=\"selected\""; ?>><?=$lang[413]?></option><option value="u" <? if (@$_POST['type']=="u") echo "selected=\"selected\""; ?>><?=$lang[800]?></option><option value="o" <? if (@$_POST['type']=="o") echo "selected=\"selected\""; ?>><?=$lang[801]?></option></select></div><div class="pad"></div><div class="lc"><?=$lang[122]?><span class="req">*</span></div><div class="rc"><?
$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat'; 
if (ctype_digit(@$_POST['id_category']) || ctype_digit(@$_GET['cat'])){
	$getcat = (@$_POST['id_category'])?$_POST['id_category']:$_GET['cat'];
	$querycat=mysql_query("SELECT * FROM jb_board_cat WHERE id='".$getcat."'");cq();
	$category=mysql_fetch_assoc($querycat);
	echo "<div id=\"usercat\"><span class=\"b\">".$category[$name_cat]."</span> (<a href=\"#\" onclick=\"rootcat('usercat');return false;\">".$lang[15]."</a>)<input type=\"hidden\" name=\"id_category\" value=\"".$category['id']."\" /></div>";
}else{
	?><select name="id_category" onchange="selcat(this.value,'resultcat');"><option value="no" selected="selected"><?=$lang[99]?> &rarr;</option><?
		$query=mysql_query("SELECT * FROM jb_board_cat WHERE root_category = 0 ORDER by sort_index"); cq();
		$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat'; 
		while($category=mysql_fetch_assoc($query)) echo "<option value=\"".$category['id']."\">".$category[$name_cat]." &rarr; </option>";
	?></select><?
}
?>
<div id="resultcat"></div></div>
<div class="pad"></div>
<div class="lc"><?=$lang[111]?></div>
<div class="rc"><select name="time_delete">
<option value="7"<? if(is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==7) echo " selected=\"selected\""; ?>>7 <?=$lang[112]?></option>
<option value="14"<? if(is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==14) echo " selected=\"selected\""; ?>>14 <?=$lang[112]?></option>
<option value="30"<? if(is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==30) echo " selected=\"selected\""; ?>>30 <?=$lang[112]?></option>
<option value="60"<? if(is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==60) echo " selected=\"selected\""; ?>>60 <?=$lang[112]?></option>
<option value="90"<? if(is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==90) echo " selected=\"selected\""; ?>>90 <?=$lang[112]?></option>
<option value="180"<? if(!@$_POST['time_delete'] || (is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==180)) echo " selected=\"selected\""; ?>>180 <?=$lang[112]?></option>
<option value="365"<? if(is_numeric(@$_POST['time_delete']) && $_POST['time_delete']==365) echo " selected=\"selected\""; ?>>365 <?=$lang[112]?></option></select></div>
<div class="pad"></div>
<div class="lc"><?=$lang[105]?><span class="req">*</span></div>
<div class="rc">
<textarea name="text" rows="15" cols="40"><?=htmlspecialchars(@$_POST['text'])?></textarea>
</div>


<div class="pad"></div>
<div class="lc"><?=$lang[1008]?> (Руб.)</div>
<div class="rc">
<input onkeyup="ff2(this)" maxlength="11" type="text" name="prices" size="50" value="<?=htmlspecialchars(@$_POST['prices'])?>" />
</div>
<div class="pad"></div>

<? 
if ($c['upload_images'] == "yes"){
?><script language="JavaScript" type="text/javascript">
<!--
function del(n){var tab=$("tab");if(tab.rows.length==2 && n==0){document.forms["add_form"].reset();return;}
if(tab.rows.length>2){if(n==0){return;}else if(n==1){tab.tBodies[0].deleteRow(tab.rows.length-2);}
else{tab.tBodies[0].deleteRow(n.parentNode.parentNode.rowIndex);}}else{return;}}
function add(){var tab=$("tab");var newRow=tab.tBodies[0].insertRow(tab.rows.length-1);var newCell_1=newRow.insertCell(0);newCell_1.style.border="none";newCell_1.innerHTML="<span><\/span>";var newfield=document.createElement("input");newfield.setAttribute("type","file");newfield.setAttribute("size","35");newfield.setAttribute("name","logo[]");newCell_1.appendChild(newfield);newRow.appendChild(newCell_1);var newCell_2=newRow.insertCell(1);var nb_2=document.createElement("input");nb_2.setAttribute("type","button");nb_2.setAttribute("value"," — ");nb_2.title="<?=$lang[417]?>";nb_2.onclick=function(){del(this);}
newCell_2.appendChild(nb_2);newRow.appendChild(newCell_2);showIndexiu();}
function showIndexiu(){var tab=$("tab");for(var i=0;i<tab.rows.length;i++){var fc=tab.rows[i].firstChild; fc.firstChild.innerHTML="";}}
//-->
</script><div class="lc"><?=$lang[106]?></div><div class="rc"><?=$lang[110]?><br /><?=$lang[313].($c['upl_image_size']/1000)?>Kb<br /><?
	if($c['count_images_for_users']>=1 && $c['count_images_for_users']<=5){
		for($i=1;$i<=$c['count_images_for_users'];$i++) echo "<input type=\"file\" name=\"logo[]\" /><br />";}
	else echo "<table id=\"tab\" cellpadding=\"3\" cellspacing=\"3\"><tr><td><input size=\"35\" id=\"test\" type=\"file\" name=\"logo[]\" /></td></tr><tr><td align=\"center\"><br /><input type=\"button\" value=\"".$lang[418]."\" onclick=\"add()\" onfocus=\"this.blur()\" /></td></tr></table>";
?></div><div class="pad"></div><?
}
if ($c['add_link_to_video']=="yes"){
	?><div class="lc"><?=$lang[1100]?><br /><img alt="youtube" class="absmid" src="<?=$im?>youtube_icon.png" /><a rel="nofollow" href="https://www.youtube.com/">youtube.com</a></div><div class="rc"><input maxlength="128" type="text" name="video" size="50" value="<?=htmlspecialchars(@$_POST['video'])?>" /><br /><span class="sm gray"><strong class="red"><?=$lang[1101]?></strong><br /><?=$lang[1102]?><strong> https://www.youtube.com/watch?v=.........</strong></span></div><div class="pad"></div><?
}	
?><div class="lc"><?=$lang[1009]?></div><div class="rc"><input maxlength="250" type="text" name="tags" size="50" value="<?=htmlspecialchars(@$_POST['tags'])?>" /></div><div class="pad"></div><div class="lc"><?=$lang[623]?><span class="req">*</span></div><div class="rc"><input maxlength="<?=$c['count_symb_autor']?>" type="text" name="autor" size="50" value="<?=htmlspecialchars(@$_POST['autor'])?>" /></div><div class="pad"></div><div class="lc"><?=$lang[196]?></div><div class="rc"><input type="text" name="email" size="50" value="<?
if (@$_POST['email']) echo htmlspecialchars(@$_POST['email']);
else if (defined("USER") && @$_SESSION['email']) echo htmlspecialchars($_SESSION['email']);
?>" /></div><div class="pad"></div><div class="lc"><?=$lang[181]?></div><div class="rc"><textarea name="contacts" rows="4" cols="40"><?=htmlspecialchars(@$_POST['contacts'])?></textarea></div><div class="pad"></div><?
################################### URL только для зарегистрированых #############################################
if ($c['add_url']=="yes"){
if(!defined('USER')){
                ?><div class="lc">Адрес Вашего сайта:</div><div class="rc">    
   Адрес сайта можно оставить только зарегистрированным пользователям. <a href="<?=$h?>register.html">Зарегистрироваться</a>    
    </div><div class="pad"></div>
                <?}
            else {
                if(@$user_data['activ'] == "no") {?><div class="lc">Адрес Вашего сайта:</div><div class="rc">    
   Адрес сайта можно оставить только зарегистрированным пользователям. <a href="<?=$h?>register.html">Зарегистрироваться</a>    
    </div><div class="pad"></div>
                <?                
                }
                else {
                    
    ?><div class="lc"><?=$lang[625]?>:</div><div class="rc"><input maxlength="<?=$c['count_symb_url']?>" type="text" name="url" size="50" value="<?=htmlspecialchars(@$_POST['url'])?>" /></div><div class="pad"></div><?

                }
            }}
####################################################################################################################
if ($c['captcha']=="yes"){
	?><div class="lc"><?=$lang[203]?><span class="req">*</span></div><div class="rc"><img alt="<?=$lang[203]?>" class="absmid" id="hello_bot" src="code.gif?<?=microtime()?>" /><input id="cptch" type="text" name="securityCode" /><br /><a href="#" onclick="document.getElementById('hello_bot').src='code.gif?'+Math.random();return false;"><?=$lang[2031]?></a></div><div class="pad"></div><?
}
?><div align="center"><strong style="color:#FF0000"><?=$lang[206]?><br /><? if ($c['anti_link'] == "yes") echo $lang[204]; ?></strong><br /><br /><input name="submit" style="width:70%;" type="submit" value=" <?=$lang[155]?> " /></div></form><br /><br /></div><?
if (defined('ALERT')) echo "<script type=\"text/javascript\">alert('".ALERT."');$('cptch').className=\"err\";</script>";
?>