<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

require_once("../admin/conf.php");
require_once("jshttprequest.php");
$JsHttpRequest=new JsHttpRequest("utf-8");
echo "<script type=\"text/javascript\">var servername='".$h."';</script>
<script type=\"text/javascript\" src=\"".$im."main.js\"></script>";
if(@constant('JBLANG')==="en")$qcity="en_city_name";else $qcity="city_name";
$query=mysql_query("SELECT id,".$qcity." FROM jb_city WHERE parent = 0 ORDER by sort_index"); cq();
if (mysql_num_rows($query)){
	$sub_c="<select name=\"city\" onchange=\"selcity(this.value,'resultcity');\"><option value=\"no\" selected=\"selected\">".$lang[164]." &rarr;</option>";
	while($sublist=mysql_fetch_assoc($query)) $sub_c .= "<option value=\"".$sublist['id']."\">".$sublist[$qcity]."</option>";
	$sub_c .= "</select>";
}
$GLOBALS['_RESULT'] = $sub_c;
?>