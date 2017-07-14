<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

require_once("../admin/conf.php");
if (@$_POST['reset_city']){
	if(setcookie("jbcity","1",1,"/")){ ?><script type="text/javascript">opener.window.location.reload();window.close();</script><? }
	else die("Error. Cookie dont work.");
}
else if (ctype_digit(@$_POST['city'])){
	if(setcookie("jbcity",$_POST['city'],time()+77760000,"/")){ ?><script type="text/javascript">opener.window.location.reload();window.close();</script><? }
	else die("Error. Cookie dont work.");
}else{
	require_once("jshttprequest.php");
	$JsHttpRequest=new JsHttpRequest("utf-8");
	echo "<script type=\"text/javascript\">var servername='".$h."';</script><script type=\"text/javascript\" src=\"".$im."main.js\"></script>";
	if(@constant('JBLANG')==="en") $qcity="en_city_name"; else $qcity="city_name";
	if (ctype_digit(@$_REQUEST['rootcity'])){
		if (@$_REQUEST['rootcity'] != "0"){
			$query=mysql_query("SELECT id, ".$qcity." FROM jb_city WHERE parent='".$_REQUEST['rootcity']."' ORDER by sort_index");
			$GLOBALS['_RESULT'] = "<select name=\"city\">";
			while($city=mysql_fetch_assoc($query)) $GLOBALS['_RESULT'].="<option value=\"".$city['id']."\">".$city[$qcity]."</option>";
			$GLOBALS['_RESULT'].="</select><input type=\"submit\" value=\"".$lang[59]."\">";
		}else $GLOBALS['_RESULT'].="<img hspace=\"20\" src=\"".$im."load.gif\">";
	}else{
		$query=mysql_query("SELECT id,".$qcity." FROM jb_city WHERE parent=0 ORDER by sort_index");
		echo "<form action=\"\" method=\"post\"><div style=\"float:left\"><select name=\"parentcity\" onchange=\"changecity(this.value);\"><option value=\"0\">".$lang[163]."</option>";
		while($parentcity=mysql_fetch_assoc($query))echo "<option value=\"".$parentcity['id']."\">".$parentcity[$qcity]." &rarr; </option>";
		echo "</select></div><div id=\"result\"></div><br /><br /><input name=\"reset_city\" type=\"submit\" value=\"".$lang[1005]."\"></form>";
	}
}
?>