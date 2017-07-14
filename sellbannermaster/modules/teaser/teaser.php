<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	//Is referer
	if(!preg_match("/^".preg_quote($website_url, "/")."/i", $_SERVER['HTTP_REFERER'])) die();

	//Is teaser place here
	$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_teaser WHERE id = ".intval($_GET['id'])." AND status = 1");
	if(!$row) die();

	//Is place free
	$db->query("BEGIN");
		$row3 = $db->query_fetch_row("SELECT MAX(turn) AS t FROM ".TABLES_PREFIX."_teaser_in_work WHERE teaser_id = ".intval($_GET['id'])." AND status = 1");
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_teaser_in_work WHERE teaser_id = ".intval($_GET['id'])." AND status = 1 ORDER BY turn ASC LIMIT ".$row['teaser_number']);
	$db->query("COMMIT");
?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<script src="<?php echo $script_url;?>js/jquery.mini.js"></script>
		<script>
			var script_url = "<?php echo $script_url;?>";
			var id = <?php echo intval($_GET['id']);?>;
			var referer = "<?php echo md5($_SERVER['HTTP_REFERER']); ?>";
		</script>
		<script src="<?php echo $script_url;?>modules/teaser/js/action.js"></script>
		<script src="//cdn.jsdelivr.net/jquery.marquee/1.3.1/jquery.marquee.min.js" type="text/javascript"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/teaser/css/buy.css" type="text/css" media="all" />
		<style>
			<?php if($row['text_place'] == 1):?>
				.teaser_teaser {
					float: left;
					width: <?php echo ($row['size_x'] + 8);?>px;
					height: <?php echo ($row['size_y'] + 8 + $row['text_block']);?>px;
					text-align: center;
					text-decoration: none;
					padding: 5px;
					border: 1px solid #cccccc;
					margin: 5px;
				}
				.teaser_teaser p {
					display: block;
					padding: 0px;
					margin: 0px;
					height: <?php echo $row['text_block'];?>px;
					font-size: <?php echo $row['font_size'];?>;
					word-wrap: break-word;
					text-align: left;
					overflow-y: hidden;
				}
			<?php else: ?>
				.teaser_teaser {
					float: left;
					width: <?php echo ($row['size_x'] + $row['text_block'] + 12);?>px;
					height: <?php echo ($row['size_y']);?>px;
					text-align: left;
					text-decoration: none;
					padding: 5px;
					border: 1px solid #cccccc;
					margin: 5px;
					overflow-y: hidden;
				}
				.teaser_teaser img {
					float: left;
				}
				.teaser_teaser p {
					float: left;
					padding: 0px 0px 0px 10px;
					margin: 0px;
					width: <?php echo $row['text_block'];?>px;
					font-size: <?php echo $row['font_size'];?>;
					word-wrap: break-word;
					text-align: left;
				}
			<?php endif; ?>
		</style>
	</head>
	<body marginheight='0' marginwidth='0' id="teaser_body">
		<?php $first = true; while($row = $db->fetch($query)) {
			if($first) {
				$db->query("UPDATE ".TABLES_PREFIX."_teaser_in_work SET turn = 1 + ".$row3['t']." WHERE id = ".intval($row['id']));
				$first = false;
			}
		?>
		<a class="teaser_teaser" rel="<?php echo $row['id'];?>" target="<?php echo $row['no_blank'] == 0 ? "_top" : "_blank" ;?>" href="<?php echo $row['target_url'];?>">
			<img src="<?php echo $script_url;?>uploads/teaser_<?php echo $row['id'];?>.<?php echo $row['extension'];?>" border="0" alt="<?php echo $row['title'];?>" />
			<p><?php echo stripslashes($row['title']);?></p>
		</a>
		<?php } ?>
		<?php if($settings['selling_opened'] == 'yes'):?>
			<a id="teaser_sell" href="javascript:">
				<span id="teaser_sect">&sect;</span>
				<?php echo __("Buy teaser, without registration!", "teaser");?>
			</a>
		<?php endif;?>
	</body>
</html>
