<?
require_once("../admin/conf.php");
require_once("jshttprequest.php");
$JsHttpRequest=new JsHttpRequest("utf-8");
echo "<script type=\"text/javascript\">var servername='".$h."';</script>
<script type=\"text/javascript\" src=\"".$im."main.js\"></script>";
$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat';
if (ctype_digit(@$_REQUEST['id_root'])>0 && ctype_alpha($_REQUEST['id_place'])){
	$query=mysql_query("SELECT id, child_category, ".$name_cat." FROM jb_board_cat WHERE root_category = '".@$_REQUEST['id_root']."' ORDER by sort_index"); cq();
	if (mysql_num_rows($query)){
		$place = "a".$_REQUEST['id_place'];
		$sub_cat="<select name=\"id_category\" onchange=\"selcat(this.value,'".$place."');\"><option value=\"no\" selected=\"selected\">".$lang[537]."</option>";
		while($sublist=mysql_fetch_assoc($query)){
			if ($sublist['child_category']==1) $arr="&rarr;"; else $arr="";
			$sub_cat .= "<option value=\"".$sublist['id']."\">".$sublist[$name_cat]." ".@$arr."</option>";
		}
		$sub_cat .= "</select>";
	}
	if (@$place) $sub_cat .= "<div id=\"".$place."\"></div>";
	$GLOBALS['_RESULT']=(@$sub_cat)?$sub_cat:"";
} else $GLOBALS['_RESULT']="";







?>