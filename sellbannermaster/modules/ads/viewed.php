<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	// Is referer
	if(!preg_match("/^".preg_quote($script_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) die("error");

	$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_ads WHERE id = ".intval($_POST['id'])." AND status = 1");

	//Viewed
	$db->query("UPDATE ".TABLES_PREFIX."_ads_in_work SET show_current_count = show_current_count + 1 WHERE ads_id = ".intval($_POST['id'])." AND status = 1 ORDER BY id DESC LIMIT ".$row['ads_number']);

?>
