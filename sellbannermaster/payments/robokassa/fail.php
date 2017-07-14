<?php

	// Прямой доступ закрыт
	defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed.");

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title><?php echo __("SellBannerMaster", "robokassa"); ?></title>
		<link rel="stylesheet" href="<?php echo $script_url;?>css/style.css" type="text/css" media="all" />
	</head>
	<body>
		<div id="stat">
			<div id="top_title"><?php echo __("Payment rejected!", "robokassa"); ?></div>
			<div id="conteiner">
				<br /><br /><div align='center'><?php echo __("Payment rejected!", "robokassa"); ?></div>
			</div>
		</div>
		<div class="copyright"><?php echo $buttom_copyright; ?></div>
	</body>
</html>

