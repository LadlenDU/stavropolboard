<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	// Is referer
	if(!preg_match("/^".preg_quote($script_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) die("error");

	//Viewed
	$db->query("UPDATE ".TABLES_PREFIX."_banners_in_work SET show_current_count = show_current_count + 1 WHERE id = ".intval($_POST['id'])." AND status = 1 AND (cross_page_crosspage = 0 OR cross_page_crosspage = 1 AND page_md5 = '".$db->safe($_POST['referer'])."')");

	//Is finish
	$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_banners_in_work WHERE id = ".intval($_POST['id'])." AND status = 1 AND (cross_page_crosspage = 0 OR cross_page_crosspage = 1 AND page_md5 = '".$db->safe($_POST['referer'])."') AND (show_bought_count > 0 AND show_bought_count > show_current_count OR show_start_time > 0 AND (show_start_time + show_bought_time * 86400) > ".time().")");
	if(!$row) {
		//Finish
		$db->query("UPDATE ".TABLES_PREFIX."_banners_in_work SET status = 2 WHERE id = ".intval($_POST['id']));
		//Send email
		fxn_send($row['banner_email'], __("Banner", "banners").": ".$row['title'], __("Your banner on web site", "banners")." ".$website_url." ".__("removed from rotation.", "banners"), $settings['website_email']);
	}
?>
