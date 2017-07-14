<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	$db->query("BEGIN");
		$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_ads_logs WHERE paid_md5 = '".$db->safe($_GET['hash'])."'");
		if($row) {
			$row4 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_ads_in_work WHERE id = ".intval($row['ads_id']));
			$db->query("UPDATE ".TABLES_PREFIX."_ads_in_work SET status = 5 WHERE id = ".intval($row['ads_id']));
			if($row4['status'] == 4) {
				fxn_send(safe($row4['owner_email']), __("Ad declined", "ads"), __("Ad", "ads").": ID ".intval($row4['id']).", ".__("declined during moderation proccess!", "ads")."<br /><br />".__("Administration will contact you to resolving the issue during few days.", "ads")."<br /><br />".__("Ad title", "ads").": ".$row4['title']."<br />".__("Target url", "ads").": ".$row4['target_url'], $settings['website_email']);
			}
		}
	$db->query("COMMIT");

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title><?php echo __("SellBannerMaster", "ads"); ?></title>
		<link rel="stylesheet" href="<?php echo $script_url;?>css/style.css" type="text/css" media="all" />
	</head>
	<body>
		<div id="stat">
			<div id="top_title"><?php echo __("Ad control", "ads"); ?></div>
			<div id="conteiner">
				<br /><br /><div align='center'><?php echo __("Ad declined!", "ads"); ?></div>
			</div>
		</div>
		<div class="copyright"><?php echo $buttom_copyright; ?></div>
	</body>
</html>

