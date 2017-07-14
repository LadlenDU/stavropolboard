<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/settings.php");

?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="shortcut icon" type="x-image" href="<?php echo $script_url;?>/favicon.ico" />
		<title><?php echo __("SellBannerMaster"); ?></title>
		<link rel="stylesheet" href="<?php echo $script_url;?>css/style.css" type="text/css" media="all" />
	</head>
	<body>
		<div id="stat">
			<div id="top_title"><?php echo __("Admin Panel"); ?></div>
			<div id="conteiner">
				<?php
					if(!isset($settings['website_discount'])) {
						$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_settings (settings_name, settings_value, settings_type, settings_attributes, settings_title, settings_description, settings_ordering)
									VALUES ('website_discount', '', 'combine', '', 'Discount', 'Discount:<br /><b>100|10$</b> - discount 10$ if price more then 100$,<br /><b>100|10$15$20$</b> - discount (10$ more then 100$, 15$ more then 200$ and 20$ more then 300$),<br /><b>100|10%</b> - discount 10% if price more then 100$,<br /><b>100|10%15%20%</b> - discount (10% more then 100$, 15% more then 200$ and 20% more then 300$)', 7)");
						?>
							<h3 align="center"><?php echo __("Update successful!");?></h3>
						<?php
					} else {
						?>
							<h3 align="center"><?php echo __("Already updated!");?></h3>
						<?php
					}
				?>
			</div>
		</div>
	</body>
</html>

