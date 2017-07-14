<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	//Is referer
	if(!preg_match("/^".preg_quote($website_url, "/")."/i", $_SERVER['HTTP_REFERER'])) die();

	//Is textline place here
	$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_textline WHERE id = ".intval($_GET['id'])." AND status = 1");
	if(!$row) die();

	//Is place free
	$db->query("BEGIN");
		$row3 = $db->query_fetch_row("SELECT MAX(turn) AS t FROM ".TABLES_PREFIX."_textline_in_work WHERE textline_id = ".intval($_GET['id'])." AND status = 1");
		$row2 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_textline_in_work WHERE textline_id = ".intval($_GET['id'])." AND status = 1 ORDER BY turn ASC");
		if($row2) $db->query("UPDATE ".TABLES_PREFIX."_textline_in_work SET turn = 1 + ".$row3['t']." WHERE id = ".intval($row2['id']));
	$db->query("COMMIT");
	if($row2):

?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<script src="<?php echo $script_url;?>js/jquery.mini.js"></script>
		<script>
			var script_url = "<?php echo $script_url;?>";
			var id = <?php echo intval($_GET['id']);?>;
			var textline_id = <?php echo intval($row2['id']);?>;
			var referer = "<?php echo md5($_SERVER['HTTP_REFERER']); ?>";
			$(window).load(function() {
				$('.marquee').css({
					width: $('.marquee_td').width() - $('.textline_sell').width()
				});
				$('.marquee').marquee({
					duplicated: false,
					duration: 12000
				});
				$(".marquee").hover(function () {
					$(this).marquee('pause');
				}, function () {
					$(this).marquee('resume');
				});
			});
		</script>
		<script src="<?php echo $script_url;?>modules/textline/js/action.js"></script>
		<script src="//cdn.jsdelivr.net/jquery.marquee/1.3.1/jquery.marquee.min.js" type="text/javascript"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/textline/css/buy.css" type="text/css" media="all" />
		<style>
			#textline {
				font-size: <?php echo $row['font_size'];?>;
			}
		</style>
	</head>
	<body marginheight='0' marginwidth='0' id="textline_body">
		<table width="100%" height="100%">
			<tr>
				<td align="left" class="marquee_td">
					<a target="<?php echo $row2['no_blank'] == 0 ? "_top" : "_blank" ;?>" href="<?php echo $row2['target_url'];?>">
						<div class="marquee">
							<span id="textline">
								<img src="<?php echo $script_url;?>uploads/icon_<?php echo $row2['id'];?>.ico" border="0" alt="<?php echo $row2['title'];?>" />
								<?php echo stripslashes($row2['title']);?>
							</span>
						</div>
					</a>
				</td>
				<td align="right" width="350">
					<?php if($settings['selling_opened'] == 'yes'):?>
						&sect;
						<a id="textline_sell" href="javascript:">
							<?php echo __("Buy text line without registration! 2 minutes proccess!", "textline");?>
						</a>
					<?php endif;?>
				</td>
			</tr>
		</table>
	</body>
</html>
<?php else:?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<script src="<?php echo $script_url;?>js/jquery.mini.js"></script>
		<script>
			var script_url = "<?php echo $script_url;?>";
			var id = <?php echo intval($_GET['id']);?>;
			var referer = "<?php echo md5($_SERVER['HTTP_REFERER']); ?>";
		</script>
		<script src="<?php echo $script_url;?>modules/textline/js/action.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/textline/css/buy.css" type="text/css" media="all" />
	</head>
	<body marginheight='0' marginwidth='0' id="textline_body">
		<table width="100%" height="100%">
			<tr>
				<td align="left">
					<a id="textline" href="javascript:"></a>
				</td>
				<td align="right">
					<?php if($settings['selling_opened'] == 'yes'):?>
						<a id="textline_sell" href="javascript:">
							<?php echo __("Buy text line without registration! 2 minutes proccess!", "textline");?>
						</a>
					<?php endif;?>
				</td>
			</tr>
		</table>
	</body>
</html>
<?php endif;?>
