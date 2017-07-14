<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	//Is sell opened
	if($settings['selling_opened'] != 'yes') {
		$error = __("Selling disabled", "banners");
		include(dirname(__FILE__)."/error.php");
	}

	//Is referer
	if(!preg_match("/^".preg_quote($website_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) {
		$error = "Error 1";
		include(dirname(__FILE__)."/error.php");
	}

	//Is banner place enabled
	$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_banners WHERE id = ".intval($_GET['id'])." AND status = 1");
	if(!$row) {
		$error = "Error 2";
		include(dirname(__FILE__)."/error.php");
	}

	//Is time over, place free
	$db->query("UPDATE ".TABLES_PREFIX."_banners_in_work SET status = 3 WHERE status = 0 AND show_start_time + 600 < ".time());

	//Is place busy
	$row2 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_banners_in_work WHERE banner_id = ".intval($_GET['id'])." AND (cross_page_crosspage = 0 OR cross_page_crosspage = 1 AND page_md5 = '".md5($_SERVER['HTTP_REFERER'])."') AND status IN (0, 1, 4)");
	if($row2 && !isset($_SESSION['sellbannermaster_proccess_'.intval($_GET['id'])])) {
		if($row2['status'] == 1 || $row2['status'] == 4) $error = __("Place busy", "banners");
		else if($row2['status'] == 0) $error = __("Place busy for", "banners")." 10 ".__("minutes", "banners");
		include(dirname(__FILE__)."/error.php");
	}

	//Session referer
	if(!isset($_SESSION['sellbannermaster_HTTP_REFERER_'.intval($_GET['id'])]) || !preg_match("/^".preg_quote($script_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) $_SESSION['sellbannermaster_HTTP_REFERER_'.intval($_GET['id'])] = $_SERVER['HTTP_REFERER'];

	//Calculate price
	$price = 0;
	$banner_show_bought_count = 0;
	$banner_show_bought_time = 0;
	$banner_no_blank = 1;
	if($row['price_1000'] > 0 && isset($_POST['banner_views_or_days']) && $_POST['banner_views_or_days'] == 0) {
		$banner_show_bought_count = (isset($_POST['banner_show_bought_count']) ? floatval($_POST['banner_show_bought_count']) : 1);
		$price += $row['price_1000'] * $banner_show_bought_count;
	} else if($row['price_day'] > 0 && isset($_POST['banner_views_or_days']) && $_POST['banner_views_or_days'] == 1)  {
		$banner_show_bought_time = (isset($_POST['banner_show_bought_time']) ? floatval($_POST['banner_show_bought_time']) : 1);
		$price += $row['price_day'] * $banner_show_bought_time;
	} else if($row['price_1000'] > 0) {
		$banner_show_bought_count = (isset($_POST['banner_show_bought_count']) ? floatval($_POST['banner_show_bought_count']) : 1);
		$price += $row['price_1000'] * $banner_show_bought_count;
	} else if($row['price_day'] > 0) {
		$banner_show_bought_time = (isset($_POST['banner_show_bought_time']) ? floatval($_POST['banner_show_bought_time']) : 1);
		$price += $row['price_day'] * $banner_show_bought_time;
	}
	if($row['price_no_blank'] > 0 && isset($_POST['banner_no_blank']) && $_POST['banner_no_blank'] == 1) {
		$banner_no_blank = 0;
		$price += $row['price_no_blank'];
	}

	$discount_data = explode("|", $settings['website_discount']);
	if(count($discount_data) >= 2) {
		if($price >= $discount_data[0]) {
			$discount_money = explode('$', $discount_data[1]);
			unset($discount_money[count($discount_money) - 1]);
			$discount_profit = explode('%', $discount_data[1]);
			unset($discount_profit[count($discount_profit) - 1]);
			$index = floor($price / $discount_data[0]) - 1;
			if(count($discount_money) >= 1) {
				if(isset($discount_money[$index])) {
					$price -= $discount_money[$index];
				} else {
					$price -= end($discount_money);
				}
			} else if(count($discount_profit) >= 1) {
				if(isset($discount_profit[$index])) {
					$price = $price * (1 - $discount_profit[$index] / 100);
				} else {
					$price = $price * (1 - end($discount_profit) / 100);
				}
			}
		}
	}

	$price = number_format(floatval($price), 2, '.', '');

	//Buy banner proccess
	$error_info = "";
	if(isset($_POST['buy_banner'])) {
		//Is valid data
		if(!isset($_POST['banner_title']) || $_POST['banner_title'] == "") {
			$error_info = __("Banner title can't be empty", "banners");
		} else if(!isset($_POST['banner_target_url']) || !filter_var($_POST['banner_target_url'], FILTER_VALIDATE_URL)) {
			$error_info = __("Target URL not valid!", "banners");
		} else if(!isset($_POST['banner_email']) || !filter_var($_POST['banner_email'], FILTER_VALIDATE_EMAIL)) {
			$error_info = __("Email not valid!", "banners");
		} else if($price <= 0) {
			$error_info = __("Wrong data!", "banners");
		} else if(!isset($_FILES['banner_file']) || empty($_FILES['banner_file']['name'])) {
			$error_info = __("Banner file not selected!", "banners");
		} else if(!isset($_POST['banner_terms']) || $_POST['banner_terms'] != 1) {
			$error_info = __("Read and accept the terms!", "banners");
		}
		if(empty($error_info)) {
			$valid_mime_types = array(
				"image/gif",
				"image/png",
				"image/jpeg",
				"image/pjpeg",
			);
			if(!in_array($_FILES["banner_file"]["type"], $valid_mime_types)) {
				$error_info = __("Banner file mime type not valid!", "banners");
			} else if($_FILES['banner_file']['size'] > $row['weight']) {
				$error_info = __("Banner file to large!", "banners");
			} else {
				$file_info = @getimagesize($_FILES['banner_file']['tmp_name']);
				if(empty($file_info)) {
					$error_info = __("Banner image corrupt!", "banners");
				} else if($file_info[0] != $row['size_x'] || $file_info[1] != $row['size_y']) {
					$error_info = __("Wrong banner image dimension!", "banners");
				} else {
					$ext = "jpg";
					if($_FILES["banner_file"]["type"] == "image/gif") {
						$ext = "gif";
					} else if($_FILES["banner_file"]["type"] == "image/png") {
						$ext = "png";
					}
					//Reserve banner place
					$db->query("BEGIN");
						$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_banners_in_work (banner_id, title, target_url, page_md5, clicks_current_count, show_current_count,  show_start_time, show_bought_count, show_bought_time, no_blank, cross_page_crosspage, extension, owner_email, status)
						VALUES(
							".intval($row['id']).",
							'".$db->safe(safe($_POST['banner_title']))."',
							'".$db->safe(safe($_POST['banner_target_url']))."',
							'".md5($_SESSION['sellbannermaster_HTTP_REFERER_'.intval($_GET['id'])])."',
							0,
							0,
							".time().",
							".intval($banner_show_bought_count * 1000).",
							".intval($banner_show_bought_time).",
							".intval($banner_no_blank).",
							".($row['cross_page_crosspage'] == 2 ? (isset($_POST['banner_cross_page_crosspage']) && $_POST['banner_cross_page_crosspage'] == 1 ? 1 : 0) : $row['cross_page_crosspage']).",
							'".$db->safe($ext)."',
							'".$db->safe(safe($_POST['banner_email']))."',
							0
						)");
						$row3 = $db->query_fetch_row("SELECT LAST_INSERT_ID() AS id");
					$db->query("COMMIT");
					//Save banner file
					move_uploaded_file($_FILES['banner_file']['tmp_name'], dirname(__FILE__)."/../../uploads/banner_".$row3['id'].".".$ext);
					//Create buy session
					$_SESSION['sellbannermaster_proccess_'.intval($_GET['id'])] = array($row3['id'], $price);

					fxn_send(htmlspecialchars_decode($settings['website_email']), __("New banner ID", "banners").": ".intval($row3['id']), __("New banner ID", "banners")." ".$row3['id'].", ".__("added on your web site", "banners")." ".$website_url);

					//Is ok
					if(!$row3) {
						$error = __("Error, contact to admin", "banners");
						include(dirname(__FILE__)."/error.php");
					}
				}
			}
		}
	}
	//Is place reserved
	if(isset($_SESSION['sellbannermaster_proccess_'.intval($_GET['id'])])) {
		$row3 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_banners_in_work WHERE status = 0 AND id = ".intval($_SESSION['sellbannermaster_proccess_'.intval($_GET['id'])][0]));
		if(!$row3 || ($row3['show_start_time'] + 600 - time()) <= 0) {
			//Remove reserved session
			unset($_SESSION['sellbannermaster_proccess_'.intval($_GET['id'])]);
		} else {
			//Reopen payment form
			$payment_form = true;
			$price = floatval($_SESSION['sellbannermaster_proccess_'.intval($_GET['id'])][1]);
		}
	}
	$tr = 1;
	if(!isset($payment_form)):
?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<script src="<?php echo $script_url;?>js/jquery.mini.js"></script>
		<script>
			var script_url = "<?php echo $script_url;?>";
			var id = <?php echo intval($_GET['id']);?>;
			var banner_weight = <?php echo intval($row['weight']);?>;
			var price_1000 = <?php echo floatval($row['price_1000']);?>;
			var price_day = <?php echo floatval($row['price_day']);?>;
			var price_no_blank = <?php echo floatval($row['price_no_blank']);?>;
			var website_discount = "<?php echo addslashes($settings['website_discount']);?>";
			var fileselected = false;
			var price = <?php echo floatval($price);?>;
			var wrong_title = "<?php echo __("Banner title can't be empty", "banners");?>";
			var wrong_target = '<?php echo __("Target URL not valid!", "banners");?>';
			var wrong_email = '<?php echo __("Email not valid!", "banners");?>';
			var wrong_data = '<?php echo __("Wrong data!", "banners");?>';
			var wrong_file = '<?php echo __("Banner file not selected!", "banners");?>';
			var wrong_accept = '<?php echo __("Read and accept the terms!", "banners");?>';
			var wrong_large = '<?php echo __("Banner file to large!", "banners");?>';
		</script>
		<script src="<?php echo $script_url;?>modules/banners/js/buy.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/banners/css/buy.css" type="text/css" media="all" />
	</head>
	<body marginheight='0' marginwidth='0'>
		<div id="buy">
			<div id="top_title"><?php echo __("Buy banner", "banners"); ?> &laquo;<?php echo $row['title']; ?>&raquo;</div>
			<div id="conteiner">
				<br />
				<div class="alert"><?php echo empty($error_info) ? "" : $error_info;?></div>
				<br />
				<form action="" method="post" enctype="multipart/form-data">
					<table width="100%">
						<tr class='row_2'>
							<th colspan="2"><?php echo __("Fill fields and upload your banner", "banners"); ?></th>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Banner title", "banners"); ?><span><?php echo __("Set banner title", "banners"); ?></span></span></td>
							<td><input class="large" type="text" name="banner_title" value="<?php echo isset($_POST['banner_title']) ? safe($_POST['banner_title']) : ""; ?>" /></td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Target url (http://example.com)", "banners"); ?><span><?php echo __("Set target url to your website with http://", "banners"); ?></span></span></td>
							<td><input class="large" type="text" name="banner_target_url" value="<?php echo isset($_POST['banner_target_url']) ? safe($_POST['banner_target_url']) : ""; ?>" /></td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Banner file", "banners"); ?> <?php echo $row['size_x']; ?>X<?php echo $row['size_y']; ?><span><?php echo __("Choice your banner, banner must be", "banners"); ?> <?php echo __("jpg, png or gif", "banners");?>, <?php echo __("maximum", "banners"); ?> <?php echo floor($row['weight']/1024); ?> <?php echo __("Kb", "banners"); ?></span></span></td>
							<td><input class="scanner-input" type="file" id="banner_file" name="banner_file" onchange="fileselected = true;" /></td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Contact email", "banners"); ?><span><?php echo __("Will use to technical notification", "banners"); ?></span></span></td>
							<td><input class="large" type="text" name="banner_email" value="<?php echo isset($_POST['banner_email']) ? safe($_POST['banner_email']) : ""; ?>" /></td>
						</tr>
						<?php if($row['price_1000'] > 0 && $row['price_day'] > 0): ?>
							<tr class='row_<?php echo $tr;?>'>
								<td><span class="tooltip"><?php echo __("Views or Days?", "banners"); ?><span><?php echo __("Choice needed option", "banners"); ?></span></span></td>
								<td>
									<select name="banner_views_or_days" onchange="$('#banner_show_bought_count').hide(); $('#banner_show_bought_time').hide(); if(this.value == 0) $('#banner_show_bought_count').show(); else $('#banner_show_bought_time').show(); calculate_price();">
										<option value="0" <?php echo isset($_POST['banner_views_or_days']) && $_POST['banner_views_or_days'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("Views", "banners"); ?></option>
										<option value="1" <?php echo isset($_POST['banner_views_or_days']) && $_POST['banner_views_or_days'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("Days", "banners"); ?></option>
									</select>
								</td>
							</tr>
						<?php endif;?>
						<tr class='row_<?php echo $tr;?>' id="banner_show_bought_count" <?php echo $row['price_1000'] > 0 && (!isset($_POST['banner_views_or_days']) || $_POST['banner_views_or_days'] == 0) ? "style='display: table-row;'" : "style='display: none;'"; ?>>
							<td><span class="tooltip"><?php echo __("Price of 1000 views is", "banners"); ?> <?php echo $row['price_1000'];?> <?php echo __("RUB", "banners"); ?>, <?php echo __("1000*N", "banners"); ?><span><?php echo __("Views number 1000*N", "banners"); ?></span></span></td>
							<td><input type="number" class="small" name="banner_show_bought_count" value="<?php echo isset($_POST['banner_show_bought_count']) ? intval($_POST['banner_show_bought_count']) : 1; ?>" onchange="calculate_price();" onkeyup="$('#bought_count').text($(this).val()*1000); calculate_price();" /> <span id="bought_count"><?php echo isset($_POST['banner_show_bought_count']) ? intval($_POST['banner_show_bought_count'] * 1000) : 1000; ?></span> <?php echo __("views", "banners"); ?></td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>' id="banner_show_bought_time" <?php echo $row['price_day'] > 0 && (isset($_POST['banner_views_or_days']) && $_POST['banner_views_or_days'] == 1 || $row['price_1000'] <= 0) ? "style='display: table-row;'" : "style='display: none;'"; ?>>
							<td><span class="tooltip"><?php echo __("Price of 1 day is", "banners"); ?> <?php echo $row['price_day'];?> <?php echo __("RUB", "banners"); ?><span><?php echo __("Days number for views", "banners"); ?></span></span></td>
							<td><input type="number" class="small" name="banner_show_bought_time" value="<?php echo isset($_POST['banner_show_bought_time']) ? intval($_POST['banner_show_bought_time']) : 1; ?>" onchange="calculate_price();" onkeyup="calculate_price();" /> <?php echo __("days", "banners"); ?></td>
						</tr>
						<?php if($row['price_no_blank'] > 0): ?>
							<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
								<td><span class="tooltip"><?php echo __("Price of target=_self is", "banners"); ?> <?php echo $row['price_no_blank'];?> <?php echo __("RUB", "banners"); ?><span><?php echo __("Open url in the self window", "banners"); ?></span></span></td>
								<td><input type="checkbox" name="banner_no_blank" value="1" <?php echo isset($_POST['banner_no_blank']) ? "checked='checked'" : ""; ?> onchange="calculate_price();"/></td>
							</tr>
						<?php endif;?>
						<?php if($row['cross_page_crosspage'] == 2): ?>
							<tr class='row_<?php echo $tr;?>'>
								<td><span class="tooltip"><?php echo __("Current page or cross pages?", "banners"); ?><span><?php echo __("Choice where the banner will shown, in All pages Or only on current", "banners"); ?></span></span></td>
								<td>
									<select name="banner_cross_page_crosspage" onchange="$('#banner_page').hide();  if(this.value == 1) $('#banner_page').show();">
										<option value="0" <?php echo isset($_POST['banner_cross_page_crosspage']) && $_POST['banner_cross_page_crosspage'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("Cross", "banners"); ?></option>
										<option value="1" <?php echo isset($_POST['banner_cross_page_crosspage']) && $_POST['banner_cross_page_crosspage'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("Current", "banners"); ?></option>
									</select>
								</td>
							</tr>
							<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>' id="banner_page" <?php echo isset($_POST['banner_cross_page_crosspage']) && $_POST['banner_cross_page_crosspage'] == 1 ? "" : "style='display: none;'"; ?>>
								<td><?php echo __("Banner will be shown on the page only", "banners"); ?></td>
								<td><?php echo safe($_SESSION['sellbannermaster_HTTP_REFERER_'.intval($_GET['id'])]); ?></td>
							</tr>
						<?php elseif($row['cross_page_crosspage'] == 0):?>
							<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
								<td></td>
								<td><?php echo __("Banner will be shown on all pages", "banners"); ?></td>
							</tr>
						<?php elseif($row['cross_page_crosspage'] == 1):?>
							<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
								<td><?php echo __("Banner will be shown on the page only", "banners"); ?></td>
								<td><?php echo safe($_SESSION['sellbannermaster_HTTP_REFERER_'.intval($_GET['id'])]); ?></td>
							</tr>
						<?php endif;?>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><?php echo __("Terms of use", "banners"); ?></td>
							<td><input type="checkbox" name="banner_terms" value="1" <?php echo isset($_POST['banner_terms']) ? "checked='checked'" : ""; ?> /> <a class="alert" href="<?php echo $script_url;?>terms.php" target="_blank"><?php echo __("I have read and agree to the terms", "banners"); ?></a></td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td colspan="2">
								<?php
									if(count($discount_data) >= 2) {
										echo __("Discount").": ";
										$discount_money = explode('$', $discount_data[1]);
										unset($discount_money[count($discount_money) - 1]);
										$discount_profit = explode('%', $discount_data[1]);
										unset($discount_profit[count($discount_profit) - 1]);
										$disc = array();
										if(count($discount_money) >= 1) {
											foreach($discount_money as $key => $money) {
												$disc[] = ($discount_data[0] * ($key + 1)).__("RUB", "banners")." &gt;= -".$money.__("RUB", "banners");
											}
										} else if(count($discount_profit) >= 1) {
											foreach($discount_profit as $key => $money) {
												$disc[] = ($discount_data[0] * ($key + 1)).__("RUB", "banners")." &gt;= -".$money."%";
											}
										}
										echo implode(", ", $disc);
									}
								?>
							</td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><div id="banner_price" align="right"><b><?php echo __("Price total:", "banners"); ?></b> <span><?php echo number_format(floatval($price), 2, '.', '');?></span> <?php echo __("RUB", "banners"); ?></div></td>
							<td>
								<input type="hidden" name="banner_page_md5" value="<?php echo md5($_SESSION['sellbannermaster_HTTP_REFERER_'.intval($_GET['id'])]); ?>" />
								<input type="submit" name="buy_banner" onclick="if(!filled()) return false;" value="<?php echo __("Buy Banner", "banners"); ?>" />
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	</body>
</html>
<?php else: ?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<base target="_blank" />
		<script src="<?php echo $script_url;?>js/jquery.mini.js"></script>
		<script>
			var script_url = "<?php echo $script_url;?>";
			var id = <?php echo intval($row3['id']);?>;
			var banner_id = <?php echo intval($_GET['id']);?>;
			var timer = <?php echo ($row3['show_start_time'] + 600 - time());?>;
			var time_over = "<?php echo __("Time over, close window and try again.", "banners");?>";
			var successful = "<?php echo ($settings['website_moderation'] == 'yes' ? __("Payment successful! Banner will be shown after moderation.", "banners") : __("Payment successful! Banner will be shown after few minutes.", "banners"));?>";
		</script>
		<script src="<?php echo $script_url;?>modules/banners/js/buy.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/banners/css/buy.css" type="text/css" media="all" />
	</head>
	<body marginheight='0' marginwidth='0'>
		<div id="pay">
			<div id="top_title"><?php echo __("Payment page", "banners"); ?> &laquo;<?php echo $row['title']; ?>&raquo;</div>
			<div id="conteiner">
				<div class="information">
					<br />
					<div align="left"><input onclick="rebuild();" type="button" id="rebuild" value="<?php echo __("Rebuild", "banners"); ?>" /></div>
					<br />
					<div class="info"><span><?php echo __("Banner title", "banners"); ?></span><?php echo $row3['title']; ?></div>
					<div class="info"><span><?php echo __("Target url", "banners"); ?></span><?php echo $row3['target_url']; ?></div>
					<div class="info"><span><?php echo __("Views", "banners"); ?></span><?php echo ($row3['show_bought_count'] ? $row3['show_bought_count'] : __("unlimited", "banners")); ?></div>
					<div class="info"><span><?php echo __("Days", "banners"); ?></span><?php echo ($row3['show_bought_time'] ? $row3['show_bought_time'] : __("unlimited", "banners")); ?></div>
					<div class="info"><span><?php echo __("Target page", "banners"); ?></span><?php echo ($row3['no_blank'] == 0 ? __("_self", "banners") : __("_blank", "banners")); ?></div>
					<div class="info"><span><?php echo __("Show on", "banners"); ?></span><?php echo ($row3['cross_page_crosspage'] == 0 ? __("all pages", "banners") : __("selected page only", "banners") ); ?></div>
					<div class="info"><span><?php echo __("Price", "banners"); ?></span><b><?php echo $price; ?></b> <?php echo __("RUB", "banners"); ?></div>
					<div class="preview">
						<div>
							<img src="<?php echo $script_url;?>uploads/banner_<?php  echo $row3['id'];?>.<?php  echo $row3['extension'];?>?r=<?php echo rand(1, 10000000);?>" border="0" alt="<?php echo $row3['title']; ?>" title="<?php echo $row3['title']; ?>" />
						</div>
					</div>
				</div>
				<div class="gateways">
					<br />
					<br />
					<div id="timer"><?php echo __("Location reserved for:", "banners");?> <label>10:00</label> <?php echo __("minutes", "banners");?></div>
					<?php
						//Email to send link
						$email = safe($row3['owner_email']);
						//ROBOKASSA cost
						$out_summ = number_format(floatval($price), 2, '.', '');
						//ROBOKASSA order number
						$inv_id = time();
						//ROBOKASSA item specification
						$Shp_c_banner = $row3['id'];
						//Description
						$inv_desc = urlencode($row3['title']);

						foreach($Pay->payments as $object) {
							$object->onForm($email, $out_summ, $inv_id, $inv_desc, $custom_params = array("Shp_c_banner" => $Shp_c_banner, "Shp_c_module" => "banners", "Shp_c_payment" => $object->name));
						}
					?>
				</div>
			</div>
		</div>
	</body>
</html>
<?php endif; ?>
