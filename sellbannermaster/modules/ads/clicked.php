<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	//Is referer
	if(!preg_match("/^".preg_quote($script_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) die("error");

	//Clicked
	$db->query("UPDATE ".TABLES_PREFIX."_ads_in_work SET clicks_current_count = clicks_current_count + 1 WHERE id = ".intval($_POST['id'])." AND status = 1");
?>
