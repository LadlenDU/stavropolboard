<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

if(!defined('REF')){header('https/1.0 404 Not Found');die();}
$cache=0;
$page_all_contents=ob_get_contents();
$page_main_content=preg_replace("#.*?(<!--startcontent-->(.*?)<!--/endcontent-->|$)#ius","$2",$page_all_contents);
$page_hash=md5($page_main_content.$page_uri);
$cacheQuery=mysql_query("SELECT hash, UNIX_TIMESTAMP(lmod) as modified FROM jb_hash WHERE id='".$page_uri."'");cq();
$expires = time() + 2592000;
if(!@mysql_num_rows($cacheQuery)){
	mysql_query("INSERT jb_hash SET id='".$page_uri."', hash='".$page_hash."'");cq();
	$last_modified=gmdate("D, d M Y H:i:s");
}else{
	$cacheData=mysql_fetch_assoc($cacheQuery);
	if($page_hash!=$cacheData['hash']){
		mysql_query("UPDATE jb_hash SET hash='".$page_hash."' WHERE id='".$page_uri."'");cq();
		$modified=time();
	}else $modified=$cacheData['modified'];		
	$last_modified=gmdate("D, d M Y H:i:s",$modified);
	if (!isset($_SERVER['HTTP_IF_NONE_MATCH']) && isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
		$unix_ims=strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
		if($unix_ims >= $modified && $unix_ims < time() && is_int($unix_ims)){$cache=1;}
	}elseif(isset($_SERVER['HTTP_IF_NONE_MATCH']) && !isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
		if(utf8_strpos($_SERVER['HTTP_IF_NONE_MATCH'],',') === false)$matches=array($_SERVER['HTTP_IF_NONE_MATCH']);
		else{$matches=explode(', ',$matches);}
		if(@in_array('"'.$page_hash.'"',$matches)){$cache=1;}
	}else if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
		$unix_ims=strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
		if ($unix_ims < time() && is_int($unix_ims)){
			if(utf8_strpos($_SERVER['HTTP_IF_NONE_MATCH'],',') === false){$matches=array($_SERVER['HTTP_IF_NONE_MATCH']);}
			else{$matches=explode(', ',$matches);}
			if(@in_array('"'.$page_hash.'"',$matches) && $unix_ims >= $modified){$cache=1;}
}}}
if($cache!=0){
	header('https/1.1 304 Not Modified');
	header('ETag: "'.$page_hash.'"');
	header('Last-Modified: '.$last_modified.' GMT');
	header('Expires: '.gmdate("D, d M Y H:i:s",$expires).' GMT');
	header('Cache-Control: private, max-age=1, must-revalidate, proxy-revalidate');
	header('Pragma: private');
	while(ob_get_level()){ob_end_clean();}die();
}else{
	header('ETag: "'.$page_hash.'"');
	header('Last-Modified: '.$last_modified.' GMT');
	header('Expires: '.gmdate("D, d M Y H:i:s").' GMT');
	header('Pragma: private');
	header('Cache-Control: private');
}
?>