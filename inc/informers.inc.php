<div class="form-wrapper">
<script type="text/javascript" language="javascript">
//<![CDATA[
function printcode_none(){$("informer_code").style.display='none';$("informer_preview").style.display='none';}
function printcode_js(){$("informer_code").style.display='block';$("informer_code").innerHTML="&lt;style&gt;<br />"+".b_inf_width{width:<b>100%</b>;}/*<?=$lang[1018]?>*/<br />"+".b_inf_text_size{font-size:<b>100%</b>;}/*<?=$lang[1019]?>*/<br />"+".b_inf_text_color{color:<b>#333333</b>;}/*<?=$lang[1020]?>*/<br />"+".b_inf_date_size{font-size:<b>80%</b>;}/*<?=$lang[1022]?>*/<br />"+".b_inf_date_color{color:<b>#666666</b>;}/*<?=$lang[1023]?>*/<br />"+"&lt;/style&gt;<br />"+"&lt;script type=&quot;text/javascript&quot; src=&quot;<?=$h?>export.php?t=js&amp;n="+$("count").options[$("count").selectedIndex].value+"&amp;c="+$("cat").options[$("cat").selectedIndex].value+"&amp;r="+$("region").options[$("region").selectedIndex].value+"&quot;&gt;&lt;/script&gt;";}
function printcode_php(){$("informer_code").style.display='block';$("informer_code").innerHTML="echo &quot;&lt;style&gt;&quot;;<br />"+"echo &quot;.b_inf_width{width:<b>100%</b>;}&quot;;/*<?=$lang[1018]?>*/<br />"+"echo &quot;.b_inf_text_size{font-size:<b>100%</b>;}&quot;;/*<?=$lang[1019]?>*/<br />"+"echo &quot;.b_inf_text_color{color:<b>#333333</b>;}&quot;;/*<?=$lang[1020]?>*/<br />"+"echo &quot;.b_inf_date_size{font-size:<b>80%</b>;}&quot;;/*<?=$lang[1022]?>*/<br />"+"echo &quot;.b_inf_date_color{color:<b>#666666</b>;}&quot;;/*<?=$lang[1023]?>*/<br />"+"echo &quot;&lt;/style&gt;&quot;;<br />"+"readfile(&quot;<?=$h?>export.php?t=php&amp;n="+$("count").options[$("count").selectedIndex].value+"&amp;c="+$("cat").options[$("cat").selectedIndex].value+"&amp;r="+$("region").options[$("region").selectedIndex].value+"&quot;);";}
//]]>
</script>
<center><h1><?=$lang[1014]?></h1></center><br /><?=$lang[1025]?>.<br /><br /><span class="orange b large"><?=$lang[1024]?>:</span><br /><br /><select name="count" id="count" style="width:250px;" onchange="printcode_none();return false;"><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option></select> - <?=$lang[1026]?><br /><select name="cat" id="cat" style="width:250px;" onchange="printcode_none();return false;"><option value="0"><?=$lang[539]?></option><?
$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?"en_name_cat":"name_cat";
$categories = mysql_query("SELECT id,".$name_cat." FROM jb_board_cat WHERE root_category=0 ORDER by sort_index");  cq();
while($category = mysql_fetch_assoc($categories)) echo "<option value=\"".$category['id']."\">".$category[$name_cat]."</option>";
?></select> - <?=$lang[122]?><br /><select name="region" id="region" style="width:250px;" onchange="printcode_none();return false;"><option value="0"><?=$lang[164]?></option><?
if(@constant('JBLANG')==="en")$qcity="en_city_name";else $qcity="city_name";
$q_city=mysql_query("SELECT id,".$qcity." FROM jb_city WHERE parent=0 ORDER by sort_index");cq(); 
while($city=mysql_fetch_assoc($q_city)){
	echo "<optgroup label=\"".$city[$qcity]."\">";
	$q_city_ch=mysql_query("SELECT id,".$qcity." FROM jb_city WHERE parent='".$city['id']."' ORDER by sort_index");cq();
	if(mysql_num_rows($q_city_ch)){
		while($city_ch=mysql_fetch_assoc($q_city_ch)){
			echo "<option value=\"".$city_ch['id']."\"";
			if(defined('JBCITY') && $city_ch['id']==JBCITY) echo " selected=\"selected\" ";
			echo "> &nbsp; &nbsp; &nbsp; &nbsp; ".$city_ch[$qcity]."</option>";
	}}
	echo "</optgroup>";
}
?></select> - <?=$lang[163]?><br /><br /><span class="red"><?=$lang[1028]?> <a class="green b" style="text-decoration:none; border-bottom: 1px dashed;" href="#" onclick="printcode_js();return false;">JavaScript</a> <?=$lang[1029]?> <a class="dgray b" style="text-decoration:none; border-bottom: 1px dashed;" href="#" onclick="printcode_php();return false;">PHP</a> <?=$lang[1030]?>.</span><br /><br /><div id="informer_code"></div><br /><a class="orange b large" style="text-decoration:none; border-bottom: 1px dashed;" href="#" onclick='print_preview($("count").options[$("count").selectedIndex].value, $("cat").options[$("cat").selectedIndex].value, $("region").options[$("region").selectedIndex].value); return false;'><?=$lang[1031]?></a><br /><br /><div id="informer_preview"></div><br /><br />
</div>