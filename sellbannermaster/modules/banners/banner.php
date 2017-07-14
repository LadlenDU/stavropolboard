<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	//Is referer
	if(!preg_match("/^".preg_quote($website_url, "/")."/i", $_SERVER['HTTP_REFERER'])) die();

	//Is banner place here
	$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_banners WHERE id = ".intval($_GET['id'])." AND  status = 1");
	if(!$row) die();

	//Is place free
	$row2 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_banners_in_work WHERE banner_id = ".intval($_GET['id'])." AND status IN (0, 1, 4) AND (cross_page_crosspage = 0 OR cross_page_crosspage = 1 AND page_md5 = '".md5($_SERVER['HTTP_REFERER'])."') ORDER BY cross_page_crosspage DESC");
	if($row2 && $row2['status'] == 1):

?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<script src="<?php echo $script_url;?>js/jquery.mini.js"></script>
		<script>
			var script_url = "<?php echo $script_url;?>";
			var id = <?php echo intval($row2['id']);?>;
			var referer = "<?php echo md5($_SERVER['HTTP_REFERER']); ?>";
		</script>
		<script src="<?php echo $script_url;?>modules/banners/js/action.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/banners/css/buy.css" type="text/css" media="all" />
	</head>
	<body marginheight='0' marginwidth='0'>
		<a id="banner" target="<?php echo $row2['no_blank'] == 0 ? "_top" : "_blank" ;?>" href="<?php echo $row2['target_url'];?>" title="<?php echo $row2['title'];?>"><img src="<?php echo $script_url;?>uploads/banner_<?php echo $row2['id'];?>.<?php echo $row2['extension'];?>" border="0" alt="" /></a>
	</body>
</html>
<?php elseif($row2 && $row2['status'] == 0): ?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<script src="<?php echo $script_url;?>js/jquery.mini.js"></script>
		<script>
			var script_url = "<?php echo $script_url;?>";
			var id = <?php echo intval($_GET['id']);?>;
			var reserved = <?php echo isset($_SESSION['sellbannermaster_proccess_'.intval($_GET['id'])]) ? 1 : 0;?>;
		</script>
		<script src="<?php echo $script_url;?>modules/banners/js/empty.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/banners/css/buy.css" type="text/css" media="all" />
	</head>
	<body marginheight='0' marginwidth='0'>
		<?php if($settings['selling_opened'] == 'yes'):?>
			<a id="banner" href="javascript:">
				<span><?php echo __("Buy banner without registration! 2 minutes proccess!", "banners");?></span>
				<?php if(isset($_SESSION['sellbannermaster_proccess_'.intval($_GET['id'])])):?>
					<br />
					<?php echo __("Location reserved for 10 minutes", "banners");?>
				<?php endif;?>
			</a>
		<?php endif;?>
	</body>
</html>
<?php elseif($row2 && $row2['status'] == 4): ?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<script src="<?php echo $script_url;?>js/jquery.mini.js"></script>
		<script>
			var script_url = "<?php echo $script_url;?>";
			var id = <?php echo intval($_GET['id']);?>;
			var reserved = 1;
		</script>
		<script src="<?php echo $script_url;?>modules/banners/js/empty.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/banners/css/buy.css" type="text/css" media="all" />
	</head>
	<body marginheight='0' marginwidth='0'>
		<?php if($settings['selling_opened'] == 'yes'):?>
			<a id="banner" href="javascript:">
				<?php echo __("Location reserved", "banners");?>
			</a>
		<?php endif;?>
	</body>
</html>
<?php else: ?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<script src="<?php echo $script_url;?>js/jquery.mini.js"></script>
		<script>
			var script_url = "<?php echo $script_url;?>";
			var id = <?php echo intval($_GET['id']);?>;
			var reserved = <?php echo isset($_SESSION['sellbannermaster_proccess_'.intval($_GET['id'])]) ? 1 : 0;?>;
		</script>
		<script src="<?php echo $script_url;?>modules/banners/js/empty.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/banners/css/buy.css" type="text/css" media="all" />
	</head>
	<body marginheight='0' marginwidth='0'>
		<?php if($settings['selling_opened'] == 'yes'):?>
			<a id="banner" href="javascript:">
				<span><?php echo __("Buy banner without registration! 2 minutes proccess!", "banners");?></span>
				<?php if(isset($_SESSION['sellbannermaster_proccess_'.intval($_GET['id'])])):?>
					<br />
					<?php echo __("Location reserved for 10 minutes", "banners");?>
				<?php endif;?>
			</a>
		<?php endif;?>
	</body>
</html>
<?php endif;?>
