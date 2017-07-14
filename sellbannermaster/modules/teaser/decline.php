<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	$db->query("BEGIN");
		$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_teaser_logs WHERE paid_md5 = '".$db->safe($_GET['hash'])."'");
		if($row) {
			$row4 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_teaser_in_work WHERE id = ".intval($row['teaser_id']));
			$db->query("UPDATE ".TABLES_PREFIX."_teaser_in_work SET status = 5 WHERE id = ".intval($row['teaser_id']));
			if($row4['status'] == 4) {
				fxn_send(safe($row4['owner_email']), __("Teaser declined", "teaser"), __("Teaser", "teaser").": ID ".intval($row4['id']).", ".__("declined during moderation proccess!", "teaser")."<br /><br />".__("Administration will contact you to resolving the issue during few days.", "teaser")."<br /><br />".__("Teaser title", "teaser").": ".$row4['title']."<br />".__("Target url", "teaser").": ".$row4['target_url'], $settings['website_email']);
			}
		}
	$db->query("COMMIT");

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title><?php echo __("SellBannerMaster", "teaser"); ?></title>
		<link rel="stylesheet" href="<?php echo $script_url;?>css/style.css" type="text/css" media="all" />
	</head>
	<body>
		<div id="stat">
			<div id="top_title"><?php echo __("Teaser control", "teaser"); ?></div>
			<div id="conteiner">
				<br /><br /><div align='center'><?php echo __("Teaser declined!", "teaser"); ?></div>
			</div>
		</div>
		<div class="copyright"><?php echo $buttom_copyright; ?></div>
	</body>
</html>

