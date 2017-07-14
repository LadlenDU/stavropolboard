<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/settings.php");

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title><?php echo __("SellBannerMaster"); ?></title>
		<script src="<?php echo $script_url;?>js/jquery.mini.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>css/style.css" type="text/css" media="all" />
	</head>
	<body>
		<div id="stat">
			<div id="top_title"><?php echo __("Logs"); ?></div>
			<div id="conteiner">
				<br />
				<a href="<?php echo $website_url;?>" target="_blank"><?php echo $website_url;?></a>
				/
				<a href=""><?php echo __("Refresh");?></a>
				<br />
				<br />
				<?php
					if(isset($_POST['extend'])) {
						if(isset($_POST['module']) && method_exists($Mod->modules[$_POST['module']], "onUserExtend")) {
						?>
							<a href=""><?php echo __("Back");?></a>
							<div class="gateways">
								<?php $Mod->modules[$_POST['module']]->onUserExtend(); ?>
							</div>
						<?php }
					} else {
						foreach($Mod->modules as $name => $mod) {
							if(method_exists($mod, "onUserStatistics")) {
								$mod->onUserStatistics();
							}
						}
					}
				?>
				<br />
				<br />
			</div>
		</div>
		<div class="copyright"><?php echo $buttom_copyright; ?></div>
	</body>
</html>

