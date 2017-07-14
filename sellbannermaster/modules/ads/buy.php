<?php

	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../../settings.php");

	function get_data($url, &$status) {
		global $website_url;
		$ch = curl_init();
		$timeout = 60;
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i586; rv:31.0) Gecko/20100101 Firefox/31.0');
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_REFERER, $website_url);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_COOKIEJAR, '-');
		$data = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $data;
	}

	function grab_image($url, $saveto){
		global $website_url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i586; rv:31.0) Gecko/20100101 Firefox/31.0');
		curl_setopt($ch, CURLOPT_REFERER, $website_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		$raw=curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close ($ch);
		if(file_exists($saveto)){
			unlink($saveto);
		}
		$fp = fopen($saveto, 'x');
		fwrite($fp, $raw);
		fclose($fp);
		return $status === 200 ? true : false;
	}

	//Is sell opened
	if($settings['selling_opened'] != 'yes') {
		$error = __("Selling disabled", "ads");
		include(dirname(__FILE__)."/error.php");
	}

	//Is referer
	if(!preg_match("/^".preg_quote($website_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) {
		$error = "Error 1";
		include(dirname(__FILE__)."/error.php");
	}

	//Is ads place enabled
	$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_ads WHERE id = ".intval($_GET['id'])." AND status = 1");
	if(!$row) {
		$error = "Error 2";
		include(dirname(__FILE__)."/error.php");
	}

	//Session referer
	if(!isset($_SESSION['sellads_HTTP_REFERER_'.intval($_GET['id'])]) || !preg_match("/^".preg_quote($script_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) $_SESSION['sellads_HTTP_REFERER_'.intval($_GET['id'])] = $_SERVER['HTTP_REFERER'];

	//Calculate price
	$price = 0;
	$ads_no_blank = 1;
	if($row['price_ad'] > 0) {
		$price += $row['price_ad'];
	}
	if($row['price_no_blank'] > 0 && isset($_POST['ads_no_blank']) && $_POST['ads_no_blank'] == 1) {
		$ads_no_blank = 0;
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

	//Buy ads proccess
	$error_info = "";
	if(isset($_POST['buy_ads'])) {
		//Is valid data
		if(!isset($_POST['ads_title']) || $_POST['ads_title'] == "") {
			$error_info = __("Ad title can't be empty!", "ads");
		} else if(isset($_POST['ads_title']) && mb_strlen($_POST['ads_title'], "UTF-8") > $row['simbols']) {
			$error_info = __("Wrong ad length!", "ads");
		} else if(!isset($_POST['ads_target_url']) || !filter_var($_POST['ads_target_url'], FILTER_VALIDATE_URL)) {
			$error_info = __("Target URL not valid!", "ads");
		} else if(!isset($_POST['ads_email']) || !filter_var($_POST['ads_email'], FILTER_VALIDATE_EMAIL)) {
			$error_info = __("Email not valid!", "ads");
		} else if($price <= 0) {
			$error_info = __("Wrong data!", "ads");
		} else if(!isset($_POST['ads_terms']) || $_POST['ads_terms'] != 1) {
			$error_info = __("Read and accept the terms!", "ads");
		}
		if(empty($error_info)) {
			//Reserve ads place
			$db->query("BEGIN");
				$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_ads_in_work (ads_id, title, target_url, clicks_current_count, show_current_count,  show_start_time, no_blank, owner_email, status)
				VALUES(
					".intval($row['id']).",
					'".$db->safe(safe($_POST['ads_title']))."',
					'".$db->safe(safe($_POST['ads_target_url']))."',
					0,
					0,
					".time().",
					".intval($ads_no_blank).",
					'".$db->safe(safe($_POST['ads_email']))."',
					0
				)");
				$row3 = $db->query_fetch_row("SELECT LAST_INSERT_ID() AS id");
			$db->query("COMMIT");
			//Save ads file

			$content = get_data($_POST['ads_target_url'], $status);
			$icon = false;
			if($status === 200 && preg_match("/<link\s+.*?href\s*=\s*[\"']?(.*?favicon.ico)/i", $content, $match)) {
				$parts = explode("/", $match[1]);
				if($parts[0] == "http:" || $parts[0] == "https:") {
					$iurl = $match[1];
				} else {
					$parts = explode("/", $_POST['ads_target_url']);
					$iurl = $parts[0]."//".$parts[2]."/".str_replace("./", "", $match[1]);
				}
				$icon = grab_image($iurl, dirname(__FILE__)."/../../uploads/ad_".$row3['id'].".ico");
			}
			if(!$icon) {
				copy(dirname(__FILE__)."/../../favicon.ico", dirname(__FILE__)."/../../uploads/ad_".$row3['id'].".ico");
			}

			//Create buy session
			$_SESSION['selladsmaster_proccess_'.intval($_GET['id'])] = array($row3['id'], $price);

			fxn_send(htmlspecialchars_decode($settings['website_email']), __("New ad ID", "ads").": ".intval($row3['id']), __("New ad ID", "ads")." ".$row3['id'].", ".__("added on your web site", "ads")." ".$website_url);

			//Is ok
			if(!$row3) {
				$error = __("Error, contact to admin", "ads");
				include(dirname(__FILE__)."/error.php");
			}
		}
	}
	//Is place reserved
	if(isset($_SESSION['selladsmaster_proccess_'.intval($_GET['id'])])) {
		$row3 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_ads_in_work WHERE status = 0 AND id = ".intval($_SESSION['selladsmaster_proccess_'.intval($_GET['id'])][0]));
		//Reopen payment form
		if($row3) {
			$payment_form = true;
			$price = floatval($_SESSION['selladsmaster_proccess_'.intval($_GET['id'])][1]);
		} else {
			unset($_SESSION['selladsmaster_proccess_'.intval($_GET['id'])]);
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
			var price_ad = <?php echo floatval($row['price_ad']);?>;
			var price_no_blank = <?php echo floatval($row['price_no_blank']);?>;
			var price = <?php echo floatval($price);?>;
			var website_discount = "<?php echo addslashes($settings['website_discount']);?>";
			var title_length = <?php echo intval($row['simbols']);?>;
			var wrong_title = "<?php echo __("Ad title can't be empty!", "ads");?>";
			var wrong_length = "<?php echo __("Wrong ad length!", "ads");?>";
			var wrong_target = '<?php echo __("Target URL not valid!", "ads");?>';
			var wrong_email = '<?php echo __("Email not valid!", "ads");?>';
			var wrong_data = '<?php echo __("Wrong data!", "ads");?>';
			var wrong_accept = '<?php echo __("Read and accept the terms!", "ads");?>';
		</script>
		<script src="<?php echo $script_url;?>modules/ads/js/buy.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/ads/css/buy.css" type="text/css" media="all" />
	</head>
	<body marginheight='0' marginwidth='0'>
		<div id="buy">
			<div id="top_title"><?php echo __("Buy ad", "ads"); ?> &laquo;<?php echo $row['title']; ?>&raquo;</div>
			<div id="conteiner">
				<br />
				<div class="alert"><?php echo empty($error_info) ? "" : $error_info;?></div>
				<br />
				<form action="" method="post">
					<table width="100%">
						<tr class='row_2'>
							<th colspan="2"><?php echo __("Fill fields", "ads"); ?></th>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Ad title", "ads"); ?><span><?php echo __("Set ad title", "ads"); ?></span></span></td>
							<td><input class="large" type="text" name="ads_title" value="<?php echo isset($_POST['ads_title']) ? safe($_POST['ads_title']) : ""; ?>" onkeypress="return check_title_length(event.charCode);" onkeyup="$('#title_length').text(title_length - $('input[name=ads_title]').val().length);" /> <span id="title_length"><?php echo $row['simbols'];?></span></td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Target url (http://example.com)", "ads"); ?><span><?php echo __("Set target url to your website with http://", "ads"); ?></span></span></td>
							<td><input class="large" type="text" name="ads_target_url" value="<?php echo isset($_POST['ads_target_url']) ? safe($_POST['ads_target_url']) : ""; ?>" /></td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Contact email", "ads"); ?><span><?php echo __("Will use to technical notification", "ads"); ?></span></span></td>
							<td><input class="large" type="text" name="ads_email" value="<?php echo isset($_POST['ads_email']) ? safe($_POST['ads_email']) : ""; ?>" /></td>
						</tr>
						<?php if($row['price_no_blank'] > 0): ?>
							<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
								<td><span class="tooltip"><?php echo __("Price of target=_self is", "ads"); ?> <?php echo $row['price_no_blank'];?> <?php echo __("RUB", "ads"); ?><span><?php echo __("Open url in the self window", "ads"); ?></span></span></td>
								<td><input type="checkbox" name="ads_no_blank" value="1" <?php echo isset($_POST['ads_no_blank']) ? "checked='checked'" : ""; ?> onchange="calculate_price();"/></td>
							</tr>
						<?php endif;?>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><?php echo __("Terms of use", "ads"); ?></td>
							<td><input type="checkbox" name="ads_terms" value="1" <?php echo isset($_POST['ads_terms']) ? "checked='checked'" : ""; ?> /> <a class="alert" href="<?php echo $script_url;?>terms.php" target="_blank"><?php echo __("I have read and agree to the terms", "ads"); ?></a></td>
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
												$disc[] = ($discount_data[0] * ($key + 1)).__("RUB", "ads")." = -".$money.__("RUB", "ads");
											}
										} else if(count($discount_profit) >= 1) {
											foreach($discount_profit as $key => $money) {
												$disc[] = ($discount_data[0] * ($key + 1)).__("RUB", "ads")." = -".$money."%";
											}
										}
										echo implode(", ", $disc);
									}
								?>
							</td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><div id="ads_price" align="right"><b><?php echo __("Price total:", "ads"); ?></b> <span><?php echo ceil($price);?></span> <?php echo __("RUB", "ads"); ?></div></td>
							<td>
								<input type="submit" name="buy_ads" onclick="if(!filled()) return false;" value="<?php echo __("Buy Ad", "ads"); ?>" />
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
			var ads_id = <?php echo intval($_GET['id']);?>;
			var successful = "<?php echo ($settings['website_moderation'] == 'yes' ? __("Payment successful! Ad will be shown after moderation.", "ads") : __("Payment successful! Ad will be shown after few minutes.", "ads"));?>";
		</script>
		<script src="<?php echo $script_url;?>modules/ads/js/buy.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/ads/css/buy.css" type="text/css" media="all" />
	</head>
	<body marginheight='0' marginwidth='0'>
		<div id="pay">
			<div id="top_title"><?php echo __("Payment page", "ads"); ?> &laquo;<?php echo $row['title']; ?>&raquo;</div>
			<div id="conteiner">
				<div class="information">
					<br />
					<div align="left"><input onclick="rebuild();" type="button" id="rebuild" value="<?php echo __("Rebuild", "ads"); ?>" /></div>
					<br />
					<div class="info"><span><img src="<?php echo $script_url;?>uploads/ad_<?php  echo $row3['id'];?>.ico?r=<?php echo rand(1, 10000000);?>" border="0" alt="<?php echo stripslashes($row3['title']); ?>" /></div>
					<div class="info"><span><?php echo __("Ad title", "ads"); ?>:</span><p style="display: inline; padding: 0px; word-wrap: break-word;"><?php echo stripslashes($row3['title']); ?></p></div>
					<div class="info"><span><?php echo __("Target url", "ads"); ?>:</span><?php echo $row3['target_url']; ?></div>
					<div class="info"><span><?php echo __("Target page", "ads"); ?>:</span><?php echo ($row3['no_blank'] == 0 ? __("_self", "ads") : __("_blank", "ads")); ?></div>
					<div class="info"><span><?php echo __("Price", "ads"); ?>:</span><b><?php echo $price; ?></b> <?php echo __("RUB", "ads"); ?></div>
				</div>
				<div class="gateways">
					<br />
					<br />
					<?php
						//Email to send ad
						$email = safe($row3['owner_email']);
						//ROBOKASSA cost
						$out_summ = number_format(floatval($price), 2, '.', '');
						//ROBOKASSA order number
						$inv_id = time();
						//ROBOKASSA item specification
						$Shp_c_ads = $row3['id'];
						//Description
						$inv_desc = urlencode($row3['title']);

						foreach($Pay->payments as $object) {
							$object->onForm($email, $out_summ, $inv_id, $inv_desc, $custom_params = array("Shp_c_ads" => $Shp_c_ads, "Shp_c_module" => "ads", "Shp_c_payment" => $object->name));
						}
					?>
				</div>
			</div>
		</div>
	</body>
</html>
<?php endif; ?>
