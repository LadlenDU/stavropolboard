<?
if (ctype_digit(@$_GET['id'])){
	$host=parse_url(@$_SERVER['HTTP_REFERER']);if(@$host['host']!=@$_SERVER['HTTP_HOST']){header("location: ".$h);die();}
	require_once("../admin/conf.php");
	$query=mysql_query("SELECT url FROM jb_board WHERE id='".intval($_GET['id'])."'");
	if (@mysql_num_rows($query)){
		mysql_query("UPDATE jb_board SET click=click+1 WHERE id='".intval($_GET['id'])."' LIMIT 1");
		$d=mysql_fetch_assoc($query);
		header("location: https://".$d['url']);
	} else {header("location: ".$h);die();}
} else {header("location: ".$h);die();}
?>
