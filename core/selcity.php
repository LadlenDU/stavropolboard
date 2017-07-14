<?
require_once("../admin/conf.php");
require_once("jshttprequest.php");
$JsHttpRequest=new JsHttpRequest("utf-8");
echo "<script type=\"text/javascript\">var servername='".$h."';</script>
<script type=\"text/javascript\" src=\"".$im."main.js\"></script>";
if(@constant('JBLANG')==="en")$qcity="en_city_name";else $qcity="city_name";
if (ctype_digit(@$_REQUEST['id_root']) && @$_REQUEST['id_root']>"1" && ctype_alpha($_REQUEST['id_place'])){
	$query = mysql_query("SELECT id,".$qcity." FROM jb_city WHERE parent='".@$_REQUEST['id_root']."' ORDER by sort_index");cq();
	if (mysql_num_rows($query)){
		$place = "a".$_REQUEST['id_place'];
		$sub_c="<select name=\"city\" onchange=\"selcity(this.value,'".$place."');\"><option value=\"no\" selected=\"selected\">".$lang[163]."</option>";
		while($sublist=mysql_fetch_assoc($query)) $sub_c.="<option value=\"".$sublist['id']."\">".$sublist[$qcity]."</option>";
		$sub_c .= "</select>";
	}
	if (@$place)$sub_c.="<div id=\"".$place."\"></div>";
	$GLOBALS['_RESULT']=(@$sub_c)?$sub_c:"";
} else $GLOBALS['_RESULT']="";
?>
