<div class="form-wrapper">
<script type="text/javascript" language="javascript">
//<![CDATA[
function rsshref(){
	$('rss_link').href=servername+'export.php?t=rss&n='+$("count").options[$("count").selectedIndex].value+'&c='+$("cat").options[$("cat").selectedIndex].value+'&r='+$("region").options[$("region").selectedIndex].value;
}
//]]>
</script>
<center><h1><?=$lang[1147]?></h1></center><br /><span class="orange b large"><?=$lang[1024]?>:</span><br /><br /><select name="count" id="count" style="width:250px;"><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option></select> - <?=$lang[1026]?><br /><select name="cat" id="cat" style="width:250px;"><option value="0"><?=$lang[539]?></option><?
$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?"en_name_cat":"name_cat";
$categories = mysql_query("SELECT id,".$name_cat." FROM jb_board_cat WHERE root_category=0 ORDER by sort_index");  cq();
while($category = mysql_fetch_assoc($categories)) echo "<option value=\"".$category['id']."\">".$category[$name_cat]."</option>";
?></select> - <?=$lang[122]?><br /><select name="region" id="region" style="width:250px;"><option value="0"><?=$lang[164]?></option><?
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
?></select> - <?=$lang[163]?><br /><br /><a target="_blank" id="rss_link" class="orange b large" style="text-decoration:none; border-bottom: 1px dashed;" href="#" onclick="rsshref();"><?=$lang[1027]?></a>
</div>