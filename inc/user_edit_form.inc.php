<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

$editq = mysql_query ("SELECT * FROM jb_board WHERE id = '".$_GET['id_mess']."'"); cq();
if (mysql_num_rows($editq)){
	$edit = mysql_fetch_assoc($editq);
	?><div class="addform" align="center"><form action="<?=$h?>cpanel-<?=$_GET['id_mess']?>-edit.html" method="post" enctype="multipart/form-data" name="add_form" onsubmit="return check_fields();"><h1 class="alcenter"><?=$lang[611]?></h1><br /><br /><div class="lc"><?=$lang[123]?><span class="req">*</span></div><div class="rc"><? $edittitle=(@$_POST['title'])?htmlspecialchars(@$_POST['title']):$edit['title']; ?><input maxlength="<?=$c['count_symb_title']?>" type="text" name="title" size="50" value="<?=$edittitle?>" /></div><div class="pad"></div><div class="lc"><?=$lang[163]?></div><div class="rc"><?
$getcity=(@$_POST['city'])?$_POST['city']:$edit['city_id'];
if(@constant('JBLANG')==="en")$qcity="en_city_name";else $qcity="city_name";
$querycity=mysql_query("SELECT ".$qcity." FROM jb_city WHERE id='".$getcity."'");cq();
$cccity=mysql_fetch_assoc($querycity);
echo "<div id=\"usercity\"><span class=\"b\">".$cccity[$qcity]."</span> (<a href=\"#\" onclick=\"rootcity('usercity');return false;\">".$lang[15]."</a>)<input type=\"hidden\" name=\"city\" value=\"".$getcity."\" /></div>";
?><div id="resultcity"></div></div><div class="pad"></div><div class="lc"><?=$lang[412]?><span class="req">*</span></div><div class="rc"><select name="type"><option value="0"><?=$lang[620]?></option><? $edittype=(@$_POST['type'])?htmlspecialchars(@$_POST['type']):$edit['type']; ?><option value="s" <? if($edittype=="s") echo "selected=\"selected\""; ?>><?=$lang[414]?></option><option value="p" <? if($edittype=="p") echo "selected=\"selected\""; ?>><?=$lang[413]?></option><option value="u" <? if($edittype=="u") echo "selected=\"selected\""; ?>><?=$lang[800]?></option><option value="o" <? if($edittype=="o") echo "selected=\"selected\""; ?>><?=$lang[801]?></option></select></div><div class="pad"></div><div class="lc"><?=$lang[122]?><span class="req">*</span></div><div class="rc"><?
$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat'; 
$getcat = (@$_POST['id_category'])?$_POST['id_category']:$edit['id_category'];
$querycat=mysql_query("SELECT * FROM jb_board_cat WHERE id='".$getcat."'");cq();
$category=mysql_fetch_assoc($querycat);
echo "<div id=\"usercat\"><span class=\"b\">".$category[$name_cat]."</span> (<a href=\"#\" onclick=\"rootcat('usercat');return false;\">".$lang[15]."</a>)<input type=\"hidden\" name=\"id_category\" value=\"".$category['id']."\" /></div>";
?><div id="resultcat"></div></div><div class="pad"></div><div class="lc"><?=$lang[111]?></div><div class="rc"><select name="time_delete"><? $edittime_delete=(is_numeric(@$_POST['time_delete']))?intval(@$_POST['time_delete']):$edit['time_delete']; ?><option value="7"<? if($edittime_delete==7) echo " selected=\"selected\""; ?>>7 <?=$lang[112]?></option><option value="14"<? if($edittime_delete==14) echo " selected=\"selected\""; ?>>14 <?=$lang[112]?></option><option value="30"<? if(!@$_POST['time_delete']||$edittime_delete==30) echo " selected=\"selected\""; ?>>30 <?=$lang[112]?></option><option value="60"<? if($edittime_delete==60) echo " selected=\"selected\""; ?>>60 <?=$lang[112]?></option><option value="90"<? if($edittime_delete==90) echo " selected=\"selected\""; ?>>90 <?=$lang[112]?></option><option value="180"<? if($edittime_delete==180) echo " selected=\"selected\""; ?>>180 <?=$lang[112]?></option><option value="365"<? if($edittime_delete==365) echo " selected=\"selected\""; ?>>365 <?=$lang[112]?></option></select></div><div class="pad"></div><div class="lc"><?=$lang[105]?><span class="req">*</span></div><div class="rc"><? $edittext=(@$_POST['text'])?htmlspecialchars($_POST['text']):$edit['text']; ?><textarea name="text" rows="6" cols="37"><?=$edittext?></textarea></div><div class="pad"></div><div class="lc"><?=$lang[1008]?> (<?=$lang[1010]?>)</div><div class="rc"><? $editprice=(is_numeric(@$_POST['prices']))?intval($_POST['prices']):$edit['prices']; ?><input onkeyup="ff2(this)" maxlength="11" type="text" name="prices" size="50" value="<?=$editprice?>" /></div><div class="pad"></div><?
if ($c['upload_images'] == "yes"){
?><script language="JavaScript" type="text/javascript">
<!--
function del(n){var tab=$("tab");if(tab.rows.length==2 && n==0){document.forms["add_form"].reset();return;}
if(tab.rows.length>2){if(n==0){return;}else if(n==1){tab.tBodies[0].deleteRow(tab.rows.length-2);}
else{tab.tBodies[0].deleteRow(n.parentNode.parentNode.rowIndex);}}else{return;}}
function add(){var tab=$("tab");var newRow=tab.tBodies[0].insertRow(tab.rows.length-1);var newCell_1=newRow.insertCell(0);newCell_1.style.border="none";newCell_1.innerHTML="<span><\/span>";var newfield=document.createElement("input");newfield.setAttribute("type","file");newfield.setAttribute("size","35");newfield.setAttribute("name","logo[]");newCell_1.appendChild(newfield);newRow.appendChild(newCell_1);var newCell_2=newRow.insertCell(1);var nb_2=document.createElement("input");nb_2.setAttribute("type","button");nb_2.setAttribute("value"," — ");nb_2.title="<?=$lang[417]?>";nb_2.onclick=function(){del(this);}
newCell_2.appendChild(nb_2);newRow.appendChild(newCell_2);showIndexhq();}function showIndexhq(){var tab=$("tab");
for(var i=0;i<tab.rows.length;i++){var fc=tab.rows[i].firstChild; fc.firstChild.innerHTML="";}}
//-->
</script><div class="lc"><?=$lang[106]?></div><div class="rc"><?
$query_img=mysql_query("SELECT * FROM jb_photo WHERE id_message = '".$edit['id']."'"); cq();  
if(@mysql_num_rows ($query_img)){
while($data_img=mysql_fetch_assoc($query_img)) echo "<a href=\"".$u."normal/".$data_img['photo_name']."\" rel=\"thumbnail\"><img class=\"absmid\" src=\"".$u."small/".$data_img['photo_name']."\"></a> <input type=checkbox name=del_photo[] value=\"".$data_img['id_photo']."\"> - ".$lang[621]."<br />";
}
?><?=$lang[110]?><br /><?=$lang[313].($c['upl_image_size']/1000)?>Kb<br /><?
if($c['count_images_for_users']>=1 && $c['count_images_for_users']<=5){for($i=1;$i<=$c['count_images_for_users'];$i++) echo "<input type=\"file\" name=\"logo[]\" /><br />";}
else echo "<table id=\"tab\" cellpadding=\"3\" cellspacing=\"3\"><tr><td><input size=\"35\" id=\"test\" type=\"file\" name=\"logo[]\" /></td></tr><tr><td align=\"center\"><br /><input type=\"button\" value=\"".$lang[418]."\" onclick=\"add()\" onfocus=\"this.blur()\" /></td></tr></table>";
?></div><div class="pad"></div><?
}
if ($c['add_link_to_video']=="yes"){
?><div class="lc"><?=$lang[1100]?><br /><img alt="youtube" class="absmid" src="<?=$im?>youtube_icon.png" /><a rel="nofollow" href="https://www.youtube.com/">youtube.com</a></div><div class="rc"><input maxlength="128" type="text" name="video" size="50" value="<?
if (@$_POST['video']) echo htmlspecialchars(@$_POST['video']);
elseif(@$edit['video']) echo "https://www.youtube.com/watch?v=".$edit['video'];
?>" /><br /><span class="sm gray"><strong class="red"><?=$lang[1101]?></strong><br /><?=$lang[1102]?><strong> https://www.youtube.com/watch?v=.........</strong></span></div><div class="pad"></div><?
}	
?><div class="lc"><?=$lang[1009]?></div><div class="rc"><? $edittags=(@$_POST['tags'])?htmlspecialchars($_POST['tags']):$edit['tags']; ?><input maxlength="250" type="text" name="tags" size="50" value="<?=$edittags?>" /></div><div class="pad"></div><div class="lc"><?=$lang[623]?><span class="req">*</span></div><div class="rc"><? $editautor=(@$_POST['autor'])?htmlspecialchars($_POST['autor']):$edit['autor']; ?><input maxlength="<?=$c['count_symb_autor']?>" type="text" name="autor" size="50" value="<?=$editautor?>" /></div><div class="pad"></div><div class="lc"><?=$lang[196]?></div><div class="rc"><input type="text" name="email" size="50" value="<?
if (@$_POST['email']) echo htmlspecialchars(@$_POST['email']);
else echo $edit['email'];
?>" /></div><div class="pad"></div><div class="lc"><?=$lang[181]?></div><div class="rc"><? $editcontacts=(@$_POST['contacts'])?htmlspecialchars($_POST['contacts']):$edit['contacts']; ?><textarea name="contacts" rows="4" cols="37"><?=@$editcontacts?></textarea></div><div class="pad"></div><?
if ($c['add_url']=="yes")
{
?><div class="lc"><?=$lang[625]?>:</div><div class="rc"><? $editurl=(@$_POST['url'])?htmlspecialchars($_POST['url']):$edit['url']; ?><input maxlength="<?=$c['count_symb_url']?>" type="text" name="url" size="50" value="<?=$editurl?>" /></div><div class="pad"></div><?
}
?>
<div align="center"><strong style="color:#FF0000"><?=$lang[206]?><br /><? if ($c['anti_link'] == "yes") echo $lang[204]; ?></strong><br /><br /><input name="submit" style="width:70%;" type="submit" value=" <?=$lang[12]?> " /></div></form><br /><br /></div><?
if (defined('ALERT')) echo "<script type=\"text/javascript\">alert('".ALERT."');</script>";
}else {header ("location: ".$h."cpanel.html");}
?>
