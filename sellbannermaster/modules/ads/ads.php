<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	//Is referer
	if(!preg_match("/^".preg_quote($website_url, "/")."/i", $_SERVER['HTTP_REFERER'])) die();

	//Is ads place here
	$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_ads WHERE id = ".intval($_GET['id'])." AND status = 1");
	if(!$row) die();

	//Is place free
	$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_ads_in_work WHERE ads_id = ".intval($_GET['id'])." AND status = 1 ORDER BY id DESC LIMIT ".$row['ads_number']);

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
		<script src="<?php echo $script_url;?>modules/ads/js/action.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/ads/css/buy.css" type="text/css" media="all" />
		<style>
			.ads_ad {
				font-size: <?php echo $row['font_size'];?>;
			}
		</style>
	</head>
	<body marginheight='0' marginwidth='0' id="ads_body">
		<table width="100%" height="100%">
			<?php while($row = $db->fetch($query)) { ?>
				<tr>
					<td align="left">
						<a class="ads_ad" rel="<?php echo $row['id'];?>" target="<?php echo $row['no_blank'] == 0 ? "_top" : "_blank" ;?>" href="<?php echo $row['target_url'];?>">
							<img src="<?php echo $script_url;?>uploads/ad_<?php echo $row['id'];?>.ico" border="0" alt="<?php echo $row['title'];?>" />
							<?php echo stripslashes($row['title']);?>
						</a>
					</td>
				</tr>
			<?php } ?>
			<tr>
				<td align="right">
					<?php if($settings['selling_opened'] == 'yes'):?>
						&sect;
						<a id="ads_sell" href="javascript:">
							<?php echo __("Buy ad without registration!", "ads");?>
						</a>
					<?php endif;?>
				</td>
			</tr>
		</table>
	</body>
</html>
