<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	$db->query("BEGIN");
		$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_textline_logs WHERE paid_md5 = '".$db->safe($_GET['hash'])."'");
		if($row) {
			$row4 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_textline_in_work WHERE id = ".intval($row['textline_id']));
			$db->query("UPDATE ".TABLES_PREFIX."_textline_in_work SET status = 1 WHERE id = ".intval($row['textline_id']));
			if($row4['status'] == 4) {
				fxn_send(safe($row4['owner_email']), __("Text line added to rotation", "textline"), __("Text line", "textline").": ID ".intval($row4['id']).", ".__("successful added to rotation!", "textline")."<br /><br />".__("Text line title", "textline").": ".$row4['title']."<br />".__("Target url", "textline").": ".$row4['target_url'], $settings['website_email']);
			}
		}
	$db->query("COMMIT");

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title><?php echo __("SellBannerMaster", "textline"); ?></title>
		<link rel="stylesheet" href="<?php echo $script_url;?>css/style.css" type="text/css" media="all" />
	</head>
	<body>
		<div id="stat">
			<div id="top_title"><?php echo __("Text line control", "textline"); ?></div>
			<div id="conteiner">
				<br /><br /><div align='center'><?php echo __("Text line accepted!", "textline"); ?></div>
			</div>
		</div>
		<div class="copyright"><?php echo $buttom_copyright; ?></div>
	</body>
</html>

