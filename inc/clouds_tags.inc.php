<?
$skolko = 20; ### сколько тегов выводить
$arr_stopwords = array('состоян','прод','рубл','предлага','https','quot','гривен','размер','цен','купл'); ### стоп-слова-паразиты, которые нужно исключить из тучи. писать без пробелов через запятую
$minlenword = 3; ### минимальная длина слова
$maxlenword = 12; ### максимальная длина слова
$clwidth = 210; ### ширина тучи
$clheight = 220; ### высота тучи
$clcolor = "#000000"; ### цвет фона тучи
$cltagcolor = "444444"; ### Цвет тегов
$clspeed = 900; ### Скорость вращения

$lim_q=$skolko*3;
$query_tags=mysql_query("SELECT tags FROM jb_board WHERE tags !='' AND old_mess='old' ORDER by RAND() LIMIT ".$lim_q);cq();
if(mysql_num_rows($query_tags)){
	while($d=mysql_fetch_assoc($query_tags))@$arr.=trim($d['tags']).",";
	foreach($arr_stopwords as $sw)$arr=preg_replace("/{$sw}/ui","#@%%@#",$arr);
	$tags=explode(',',$arr);$tags=array_unique($tags);$qt=array();
	foreach($tags as $tag){
		$tag=trim($tag);
		if (strrpos($tag,"#@%%@#")===false && strrpos($tag," ")===false && utf8_strlen($tag)>$minlenword && utf8_strlen($tag)<$maxlenword){
			if (!preg_match("/[0-9]/ui",$tag))$qt[]=$tag;
	}}
	$qt=array_slice($qt,0,$skolko);$itags="<tags>";
	foreach($qt as $n)$itags.="<a style=\"font-size:13pt\" href=\"".$h."?op=search&amp;query=".$n."\">".$n."</a> ";
	$itags.="</tags>";
	?><div id="tags"><?=$itags?><script type="text/javascript">var widget_so=new SWFObject("<?=$im?>tagcloud.swf?r="+Math.floor(Math.random()*9999999),"tagcloudflash","<?=$clwidth?>","<?=$clheight?>","9","<?=$clcolor?>");widget_so.addParam("allowScriptAccess","always");widget_so.addParam("wmode","transparent");widget_so.addVariable("tcolor","<?=$cltagcolor?>");widget_so.addVariable("tspeed","<?=$clspeed?>");widget_so.addVariable("distr","true");widget_so.addVariable("mode","tags");widget_so.addVariable("tagcloud","<?=urlencode($itags)?>");widget_so.write("tags");</script></div><?
}
?>