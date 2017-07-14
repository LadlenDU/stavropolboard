<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	//Is referer
	if(!preg_match("/^".preg_quote($script_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) die("error");

	//Is paid
	$row = $db->query_fetch_row("SELECT status FROM ".TABLES_PREFIX."_banners_in_work WHERE id = ".intval($_POST['id']));
	if($row) {
		if($row['status'] == 1 || $row['status'] == 4) {
			//Paid
			unset($_SESSION['sellbannermaster_proccess_'.intval($_POST['banner_id'])]);
		}
		echo $row['status'];
	}
?>
