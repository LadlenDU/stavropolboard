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
		$error = __("Selling disabled", "teaser");
		include(dirname(__FILE__)."/error.php");
	}

	//Is referer
	if(!preg_match("/^".preg_quote($website_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) {
		$error = "Error 1";
		include(dirname(__FILE__)."/error.php");
	}

	//Is teaser place enabled
	$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_teaser WHERE id = ".intval($_GET['id'])." AND status = 1");
	if(!$row) {
		$error = "Error 2";
		include(dirname(__FILE__)."/error.php");
	}

	//Session referer
	if(!isset($_SESSION['sellteaser_HTTP_REFERER_'.intval($_GET['id'])]) || !preg_match("/^".preg_quote($script_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) $_SESSION['sellteaser_HTTP_REFERER_'.intval($_GET['id'])] = $_SERVER['HTTP_REFERER'];

	//Calculate price
	$price = 0;
	$teaser_show_bought_count = 0;
	$teaser_no_blank = 1;
	if($row['price_1000'] > 0) {
		$teaser_show_bought_count = (isset($_POST['teaser_show_bought_count']) ? floatval($_POST['teaser_show_bought_count']) : 1);
		$price += $row['price_1000'] * $teaser_show_bought_count;
	}
	if($row['price_no_blank'] > 0 && isset($_POST['teaser_no_blank']) && $_POST['teaser_no_blank'] == 1) {
		$teaser_no_blank = 0;
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

	//Buy teaser proccess
	$error_info = "";
	if(isset($_POST['buy_teaser'])) {
		//Is valid data
		if(!isset($_POST['teaser_title']) || $_POST['teaser_title'] == "") {
			$error_info = __("Teaser title can't be empty!", "teaser");
		} else if(isset($_POST['teaser_title']) && mb_strlen($_POST['teaser_title'], "UTF-8") > $row['simbols']) {
			$error_info = __("Wrong teaser length!", "teaser");
		} else if(!isset($_POST['teaser_target_url']) || !filter_var($_POST['teaser_target_url'], FILTER_VALIDATE_URL)) {
			$error_info = __("Target URL not valid!", "teaser");
		} else if(!isset($_POST['teaser_email']) || !filter_var($_POST['teaser_email'], FILTER_VALIDATE_EMAIL)) {
			$error_info = __("Email not valid!", "teaser");
		} else if($price <= 0) {
			$error_info = __("Wrong data!", "teaser");
		} else if(!isset($_FILES['teaser_file']) || empty($_FILES['teaser_file']['name'])) {
			$error_info = __("Teaser file not selected!", "teaser");
		} else if(!isset($_POST['teaser_terms']) || $_POST['teaser_terms'] != 1) {
			$error_info = __("Read and accept the terms!", "teaser");
		}
		if(empty($error_info)) {
			$valid_mime_types = array(
				"image/gif",
				"image/png",
				"image/jpeg",
				"image/pjpeg",
			);
			if(!in_array($_FILES["teaser_file"]["type"], $valid_mime_types)) {
				$error_info = __("Teaser file mime type not valid!", "teaser");
			} else if($_FILES['teaser_file']['size'] > $row['weight']) {
				$error_info = __("Teaser file to large!", "teaser");
			} else {
				$file_info = @getimagesize($_FILES['teaser_file']['tmp_name']);
				if(empty($file_info)) {
					$error_info = __("Teaser image corrupt!", "teaser");
				} else if($file_info[0] != $row['size_x'] || $file_info[1] != $row['size_y']) {
					$error_info = __("Wrong teaser image dimension!", "teaser");
				} else {
					$ext = "jpg";
					if($_FILES["teaser_file"]["type"] == "image/gif") {
						$ext = "gif";
					} else if($_FILES["teaser_file"]["type"] == "image/png") {
						$ext = "png";
					}
					//Reserve teaser place
					$db->query("BEGIN");
						$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_teaser_in_work (teaser_id, title, target_url, clicks_current_count, show_current_count,  show_start_time, show_bought_count, no_blank,  extension, owner_email, status)
						VALUES(
							".intval($row['id']).",
							'".$db->safe(safe($_POST['teaser_title']))."',
							'".$db->safe(safe($_POST['teaser_target_url']))."',
							0,
							0,
							".time().",
							".intval($teaser_show_bought_count * 1000).",
							".intval($teaser_no_blank).",
							'".$db->safe($ext)."',
							'".$db->safe(safe($_POST['teaser_email']))."',
							0
						)");
						$row3 = $db->query_fetch_row("SELECT LAST_INSERT_ID() AS id");
					$db->query("COMMIT");
					//Save teaser file
					move_uploaded_file($_FILES['teaser_file']['tmp_name'], dirname(__FILE__)."/../../uploads/teaser_".$row3['id'].".".$ext);
					//Create buy session
					$_SESSION['sellteasermaster_proccess_'.intval($_GET['id'])] = array($row3['id'], $price);

					fxn_send(htmlspecialchars_decode($settings['website_email']), __("New teaser ID", "teaser").": ".intval($row3['id']), __("New teaser ID", "teaser")." ".$row3['id'].", ".__("added on your web site", "teaser")." ".$website_url);

					//Is ok
					if(!$row3) {
						$error = __("Error, contact to admin", "teaser");
						include(dirname(__FILE__)."/error.php");
					}
				}
			}
		}
	}
	//Is place reserved
	if(isset($_SESSION['sellteasermaster_proccess_'.intval($_GET['id'])])) {
		$row3 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_teaser_in_work WHERE status = 0 AND id = ".intval($_SESSION['sellteasermaster_proccess_'.intval($_GET['id'])][0]));
		//Reopen payment form
		if($row3) {
			$payment_form = true;
			$price = floatval($_SESSION['sellteasermaster_proccess_'.intval($_GET['id'])][1]);
		} else {
			unset($_SESSION['sellteasermaster_proccess_'.intval($_GET['id'])]);
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
			var price_1000 = <?php echo floatval($row['price_1000']);?>;
			var price_no_blank = <?php echo floatval($row['price_no_blank']);?>;
			var price = <?php echo floatval($price);?>;
			var website_discount = "<?php echo addslashes($settings['website_discount']);?>";
			var title_length = <?php echo intval($row['simbols']);?>;
			var fileselected = false;
			var wrong_title = "<?php echo __("Teaser title can't be empty!", "teaser");?>";
			var wrong_length = "<?php echo __("Wrong teaser length!", "teaser");?>";
			var wrong_target = '<?php echo __("Target URL not valid!", "teaser");?>';
			var wrong_email = '<?php echo __("Email not valid!", "teaser");?>';
			var wrong_data = '<?php echo __("Wrong data!", "teaser");?>';
			var wrong_accept = '<?php echo __("Read and accept the terms!", "teaser");?>';
			var wrong_file = '<?php echo __("Teaser file not selected!", "teaser");?>';
		</script>
		<script src="<?php echo $script_url;?>modules/teaser/js/buy.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/teaser/css/buy.css" type="text/css" media="all" />
	</head>
	<body marginheight='0' marginwidth='0'>
		<div id="buy">
			<div id="top_title"><?php echo __("Buy teaser", "teaser"); ?> &laquo;<?php echo $row['title']; ?>&raquo;</div>
			<div id="conteiner">
				<br />
				<div class="alert"><?php echo empty($error_info) ? "" : $error_info;?></div>
				<br />
				<form action="" method="post" enctype="multipart/form-data">
					<table width="100%">
						<tr class='row_2'>
							<th colspan="2"><?php echo __("Fill fields", "teaser"); ?></th>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Teaser title", "teaser"); ?><span><?php echo __("Set Teaser title", "teaser"); ?></span></span></td>
							<td><input class="large" type="text" name="teaser_title" value="<?php echo isset($_POST['teaser_title']) ? safe($_POST['teaser_title']) : ""; ?>" onkeypress="return check_title_length(event.charCode);" onkeyup="$('#title_length').text(title_length - $('input[name=teaser_title]').val().length);" /> <span id="title_length"><?php echo $row['simbols'];?></span></td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Target url (http://example.com)", "teaser"); ?><span><?php echo __("Set target url to your website with http://", "teaser"); ?></span></span></td>
							<td><input class="large" type="text" name="teaser_target_url" value="<?php echo isset($_POST['teaser_target_url']) ? safe($_POST['teaser_target_url']) : ""; ?>" /></td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Teaser file", "teaser"); ?> <?php echo $row['size_x']; ?>X<?php echo $row['size_y']; ?><span><?php echo __("Choice your teaser, teaser must be", "teaser"); ?> <?php echo __("jpg, png or gif", "teaser");?>, <?php echo __("maximum", "teaser"); ?> <?php echo floor($row['weight']/1024); ?> <?php echo __("Kb", "teaser"); ?></span></span></td>
							<td><input class="scanner-input" type="file" id="teaser_file" name="teaser_file" onchange="fileselected = true;" /></td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Contact email", "teaser"); ?><span><?php echo __("Will use to technical notification", "teaser"); ?></span></span></td>
							<td><input class="large" type="text" name="teaser_email" value="<?php echo isset($_POST['teaser_email']) ? safe($_POST['teaser_email']) : ""; ?>" /></td>
						</tr>
						<tr class='row_<?php echo $tr;?>' id="teaser_show_bought_count">
							<td><span class="tooltip"><?php echo __("Price of 1000 views is", "teaser"); ?> <?php echo $row['price_1000'];?> <?php echo __("RUB", "teaser"); ?>, <?php echo __("1000*N", "teaser"); ?><span><?php echo __("Views number 1000*N", "teaser"); ?></span></span></td>
							<td><input type="number" class="small" name="teaser_show_bought_count" value="<?php echo isset($_POST['teaser_show_bought_count']) ? intval($_POST['teaser_show_bought_count']) : 1; ?>" onchange="calculate_price();" onkeyup="$('#bought_count').text($(this).val()*1000); calculate_price();" /> <span id="bought_count"><?php echo isset($_POST['teaser_show_bought_count']) ? intval($_POST['teaser_show_bought_count'] * 1000) : 1000; ?></span> <?php echo __("views", "teaser"); ?></td>
						</tr>
						<?php if($row['price_no_blank'] > 0): ?>
							<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
								<td><span class="tooltip"><?php echo __("Price of target=_self is", "teaser"); ?> <?php echo $row['price_no_blank'];?> <?php echo __("RUB", "teaser"); ?><span><?php echo __("Open url in the self window", "teaser"); ?></span></span></td>
								<td><input type="checkbox" name="teaser_no_blank" value="1" <?php echo isset($_POST['teaser_no_blank']) ? "checked='checked'" : ""; ?> onchange="calculate_price();"/></td>
							</tr>
						<?php endif;?>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><?php echo __("Terms of use", "teaser"); ?></td>
							<td><input type="checkbox" name="teaser_terms" value="1" <?php echo isset($_POST['teaser_terms']) ? "checked='checked'" : ""; ?> /> <a class="alert" href="<?php echo $script_url;?>terms.php" target="_blank"><?php echo __("I have read and agree to the terms", "teaser"); ?></a></td>
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
												$disc[] = ($discount_data[0] * ($key + 1)).__("RUB", "teaser")." &gt;= -".$money.__("RUB", "teaser");
											}
										} else if(count($discount_profit) >= 1) {
											foreach($discount_profit as $key => $money) {
												$disc[] = ($discount_data[0] * ($key + 1)).__("RUB", "teaser")." &gt;= -".$money."%";
											}
										}
										echo implode(", ", $disc);
									}
								?>
							</td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><div id="teaser_price" align="right"><b><?php echo __("Price total:", "teaser"); ?></b> <span><?php echo ceil($price);?></span> <?php echo __("RUB", "teaser"); ?></div></td>
							<td>
								<input type="submit" name="buy_teaser" onclick="if(!filled()) return false;" value="<?php echo __("Buy Teaser", "teaser"); ?>" />
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
			var teaser_id = <?php echo intval($_GET['id']);?>;
			var successful = "<?php echo ($settings['website_moderation'] == 'yes' ? __("Payment successful! Teaser will be shown after moderation.", "teaser") : __("Payment successful! Teaser will be shown after few minutes.", "teaser"));?>";
		</script>
		<script src="<?php echo $script_url;?>modules/teaser/js/buy.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/teaser/css/buy.css" type="text/css" media="all" />
	</head>
	<body marginheight='0' marginwidth='0'>
		<div id="pay">
			<div id="top_title"><?php echo __("Payment page", "teaser"); ?> &laquo;<?php echo $row['title']; ?>&raquo;</div>
			<div id="conteiner">
				<div class="information">
					<br />
					<div align="left"><input onclick="rebuild();" type="button" id="rebuild" value="<?php echo __("Rebuild", "teaser"); ?>" /></div>
					<br />
					<div class="info"><span><?php echo __("Teaser title", "teaser"); ?>:</span><p style="display: inline; padding: 0px; word-wrap: break-word;"><?php echo stripslashes($row3['title']); ?></p></div>
					<div class="info"><span><?php echo __("Target url", "teaser"); ?>:</span><?php echo $row3['target_url']; ?></div>
					<div class="info"><span><?php echo __("Views", "teaser"); ?>:</span><?php echo ($row3['show_bought_count'] ? $row3['show_bought_count'] : __("unlimited", "teaser")); ?></div>
					<div class="info"><span><?php echo __("Target page", "teaser"); ?>:</span><?php echo ($row3['no_blank'] == 0 ? __("_self", "teaser") : __("_blank", "teaser")); ?></div>
					<div class="info"><span><?php echo __("Price", "teaser"); ?>:</span><b><?php echo $price; ?></b> <?php echo __("RUB", "teaser"); ?></div>
					<div class="preview">
						<div>
							<img src="<?php echo $script_url;?>uploads/teaser_<?php  echo $row3['id'];?>.<?php  echo $row3['extension'];?>?r=<?php echo rand(1, 10000000);?>" border="0" alt="<?php echo $row3['title']; ?>" title="<?php echo $row3['title']; ?>" />
						</div>
					</div>
				</div>
				<div class="gateways">
					<br />
					<br />
					<?php
						//Email to send link
						$email = safe($row3['owner_email']);
						//ROBOKASSA cost
						$out_summ = number_format(floatval($price), 2, '.', '');
						//ROBOKASSA order number
						$inv_id = time();
						//ROBOKASSA item specification
						$Shp_c_teaser = $row3['id'];
						//Description
						$inv_desc = urlencode($row3['title']);

						foreach($Pay->payments as $object) {
							$object->onForm($email, $out_summ, $inv_id, $inv_desc, $custom_params = array("Shp_c_module" => "teaser", "Shp_c_payment" => $object->name, "Shp_c_teaser" => $Shp_c_teaser));
						}
					?>
				</div>
			</div>
		</div>
	</body>
</html>
<?php endif; ?>
