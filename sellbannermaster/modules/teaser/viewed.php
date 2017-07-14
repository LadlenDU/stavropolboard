<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	// Is referer
	if(!preg_match("/^".preg_quote($script_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) die("error");

	//Viewed
	$db->query("UPDATE ".TABLES_PREFIX."_teaser_in_work SET show_current_count = show_current_count + 1 WHERE id = ".intval($_POST['id'])." AND status = 1");

	//Is finish
	$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_teaser_in_work WHERE id = ".intval($_POST['id'])." AND status = 1 AND show_bought_count > show_current_count");
	if(!$row) {
		//Finish
		$db->query("UPDATE ".TABLES_PREFIX."_teaser_in_work SET status = 2 WHERE id = ".intval($_POST['id']));
		//Send email
		fxn_send($row['teaser_email'], __("Teaser", "teaser").": ".$row['title'], __("Your teaser on web site", "teaser")." ".$website_url." ".__("removed from rotation.", "teaser"), $settings['website_email']);
	}
?>
