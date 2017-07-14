<?
require_once("../admin/conf.php");
require_once("jshttprequest.php");
$JsHttpRequest=new JsHttpRequest("utf-8");
echo "<script type=\"text/javascript\">var servername='".$h."';</script>
<script type=\"text/javascript\" src=\"".$im."main.js\"></script>";
$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat';
$query=mysql_query("SELECT id, child_category, ".$name_cat." FROM jb_board_cat WHERE root_category = 0 ORDER by sort_index"); cq();
if (mysql_num_rows($query)){
	$sub_cat="<select name=\"id_category\" onchange=\"selcat(this.value,'resultcat');\"><option value=\"no\" selected=\"selected\">".$lang[99]." &rarr;</option>";
	while($sublist=mysql_fetch_assoc($query)){
		if ($sublist['child_category']==1) $arr="&rarr;"; else $arr="";
		$sub_cat .= "<option value=\"".$sublist['id']."\">".$sublist[$name_cat]." ".@$arr."</option>";
	}
	$sub_cat .= "</select>";
}
$GLOBALS['_RESULT'] = $sub_cat;
?>