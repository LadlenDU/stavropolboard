<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	//Is referer
	if(!preg_match("/^".preg_quote($script_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) die("error");

	//Banner cancel
	if(isset($_SESSION['sellbannermaster_proccess_'.intval($_POST['banner_id'])])) {
		$db->query("UPDATE ".TABLES_PREFIX."_banners_in_work SET status = 3 WHERE id = ".intval($_SESSION['sellbannermaster_proccess_'.intval($_POST['banner_id'])][0]));
		unset($_SESSION['sellbannermaster_proccess_'.intval($_POST['banner_id'])]);
	}
?>
