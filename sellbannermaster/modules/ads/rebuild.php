<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	//Is referer
	if(!preg_match("/^".preg_quote($script_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) die("error");

	//Ad cancel
	if(isset($_SESSION['selladsmaster_proccess_'.intval($_POST['ads_id'])])) {
		$db->query("UPDATE ".TABLES_PREFIX."_ads_in_work SET status = 3 WHERE id = ".intval($_SESSION['selladsmaster_proccess_'.intval($_POST['ads_id'])][0]));
		unset($_SESSION['selladsmaster_proccess_'.intval($_POST['ads_id'])]);
	}
?>
