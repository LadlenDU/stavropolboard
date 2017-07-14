<?
require_once("admin/conf.php");
if(ctype_digit(@$_GET['n']) && @$_GET['n']>0 && @$_GET['n']<25 && ctype_digit(@$_GET['c'])>=0 && ctype_digit(@$_GET['r'])>=0){
	if(@$_GET['t']=="php")define("EXP_TYPE","php");
	elseif(@$_GET['t']=="js")define("EXP_TYPE","js");
	elseif(@$_GET['t']=="rss")define("EXP_TYPE","rss");else die();
	$number=$_GET['n']; if (!preg_match("|^[\d]+$|", $number)){exit ("неверный формат запроса");} 
    	$cat=$_GET['c']; if (!preg_match("|^[\d]+$|", $cat)){exit ("неверный формат запроса");} 
    	$city=$_GET['r']; if (!preg_match("|^[\d]+$|", $city)) {exit ("неверный формат запроса");}
	$page_uri=EXP_TYPE."_".$number."_city".$city."_cat".$cat;	
	if($JBSCACHE=="1"){
		$flnm=$c_exp_dir.EXP_TYPE."/".$page_uri;
		ob_start();
		if(!$cachedata=readData($flnm,$JBSCACHE_exp_expire)){
			require_once("inc/exp.inc.php");
			$cachedata=ob_get_contents();ob_clean();
			writeData($flnm,$cachedata);
		}echo $cachedata;
	} else require_once("inc/exp.inc.php");
} else die($lang[1032]);
?>