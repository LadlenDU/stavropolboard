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
		$error = __("Selling disabled", "textline");
		include(dirname(__FILE__)."/error.php");
	}

	//Is referer
	if(!preg_match("/^".preg_quote($website_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) {
		$error = "Error 1";
		include(dirname(__FILE__)."/error.php");
	}

	//Is textline place enabled
	$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_textline WHERE id = ".intval($_GET['id'])." AND status = 1");
	if(!$row) {
		$error = "Error 2";
		include(dirname(__FILE__)."/error.php");
	}

	//Session referer
	if(!isset($_SESSION['selltextline_HTTP_REFERER_'.intval($_GET['id'])]) || !preg_match("/^".preg_quote($script_url, "/").".*/i", $_SERVER['HTTP_REFERER'])) $_SESSION['selltextline_HTTP_REFERER_'.intval($_GET['id'])] = $_SERVER['HTTP_REFERER'];

	//Calculate price
	$price = 0;
	$textline_show_bought_count = 0;
	$textline_no_blank = 1;
	if($row['price_1000'] > 0) {
		$textline_show_bought_count = (isset($_POST['textline_show_bought_count']) ? floatval($_POST['textline_show_bought_count']) : 1);
		$price += $row['price_1000'] * $textline_show_bought_count;
	}
	if($row['price_no_blank'] > 0 && isset($_POST['textline_no_blank']) && $_POST['textline_no_blank'] == 1) {
		$textline_no_blank = 0;
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

	//Buy textline proccess
	$error_info = "";
	if(isset($_POST['buy_textline'])) {
		//Is valid data
		if(!isset($_POST['textline_title']) || $_POST['textline_title'] == "") {
			$error_info = __("Text line title can't be empty!", "textline");
		} else if(isset($_POST['textline_title']) && mb_strlen($_POST['textline_title'], "UTF-8") > $row['simbols']) {
			$error_info = __("Wrong text line length!", "textline");
		} else if(!isset($_POST['textline_target_url']) || !filter_var($_POST['textline_target_url'], FILTER_VALIDATE_URL)) {
			$error_info = __("Target URL not valid!", "textline");
		} else if(!isset($_POST['textline_email']) || !filter_var($_POST['textline_email'], FILTER_VALIDATE_EMAIL)) {
			$error_info = __("Email not valid!", "textline");
		} else if($price <= 0) {
			$error_info = __("Wrong data!", "textline");
		} else if(!isset($_POST['textline_terms']) || $_POST['textline_terms'] != 1) {
			$error_info = __("Read and accept the terms!", "textline");
		}
		if(empty($error_info)) {
			//Reserve textline place
			$db->query("BEGIN");
				$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_textline_in_work (textline_id, title, target_url, clicks_current_count, show_current_count,  show_start_time, show_bought_count, no_blank, owner_email, status)
				VALUES(
					".intval($row['id']).",
					'".$db->safe(safe($_POST['textline_title']))."',
					'".$db->safe(safe($_POST['textline_target_url']))."',
					0,
					0,
					".time().",
					".intval($textline_show_bought_count * 1000).",
					".intval($textline_no_blank).",
					'".$db->safe(safe($_POST['textline_email']))."',
					0
				)");
				$row3 = $db->query_fetch_row("SELECT LAST_INSERT_ID() AS id");
			$db->query("COMMIT");
			//Save textline file

			$content = get_data($_POST['textline_target_url'], $status);
			$icon = false;
			if($status === 200 && preg_match("/<link\s+.*?href\s*=\s*[\"']?(.*?favicon.ico)/i", $content, $match)) {
				$parts = explode("/", $match[1]);
				if($parts[0] == "http:" || $parts[0] == "https:") {
					$iurl = $match[1];
				} else {
					$parts = explode("/", $_POST['textline_target_url']);
					$iurl = $parts[0]."//".$parts[2]."/".str_replace("./", "", $match[1]);
				}
				$icon = grab_image($iurl, dirname(__FILE__)."/../../uploads/icon_".$row3['id'].".ico");
			}
			if(!$icon) {
				copy(dirname(__FILE__)."/../../favicon.ico", dirname(__FILE__)."/../../uploads/icon_".$row3['id'].".ico");
			}

			//Create buy session
			$_SESSION['selltextlinemaster_proccess_'.intval($_GET['id'])] = array($row3['id'], $price);

			fxn_send(htmlspecialchars_decode($settings['website_email']), __("New text line ID", "textline").": ".intval($row3['id']), __("New text line ID", "textline")." ".$row3['id'].", ".__("added on your web site", "textline")." ".$website_url);

			//Is ok
			if(!$row3) {
				$error = __("Error, contact to admin", "textline");
				include(dirname(__FILE__)."/error.php");
			}
		}
	}
	//Is place reserved
	if(isset($_SESSION['selltextlinemaster_proccess_'.intval($_GET['id'])])) {
		$row3 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_textline_in_work WHERE status = 0 AND id = ".intval($_SESSION['selltextlinemaster_proccess_'.intval($_GET['id'])][0]));
		//Reopen payment form
		if($row3) {
			$payment_form = true;
			$price = floatval($_SESSION['selltextlinemaster_proccess_'.intval($_GET['id'])][1]);
		} else {
			unset($_SESSION['selltextlinemaster_proccess_'.intval($_GET['id'])]);
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
			var wrong_title = "<?php echo __("Text line title can't be empty!", "textline");?>";
			var wrong_length = "<?php echo __("Wrong text line length!", "textline");?>";
			var wrong_target = '<?php echo __("Target URL not valid!", "textline");?>';
			var wrong_email = '<?php echo __("Email not valid!", "textline");?>';
			var wrong_data = '<?php echo __("Wrong data!", "textline");?>';
			var wrong_accept = '<?php echo __("Read and accept the terms!", "textline");?>';
		</script>
		<script src="<?php echo $script_url;?>modules/textline/js/buy.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/textline/css/buy.css" type="text/css" media="all" />
	</head>
	<body marginheight='0' marginwidth='0'>
		<div id="buy">
			<div id="top_title"><?php echo __("Buy text line", "textline"); ?> &laquo;<?php echo $row['title']; ?>&raquo;</div>
			<div id="conteiner">
				<br />
				<div class="alert"><?php echo empty($error_info) ? "" : $error_info;?></div>
				<br />
				<form action="" method="post">
					<table width="100%">
						<tr class='row_2'>
							<th colspan="2"><?php echo __("Fill fields", "textline"); ?></th>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Text line title", "textline"); ?><span><?php echo __("Set Text line title", "textline"); ?></span></span></td>
							<td><input class="large" type="text" name="textline_title" value="<?php echo isset($_POST['textline_title']) ? safe($_POST['textline_title']) : ""; ?>" onkeypress="return check_title_length(event.charCode);" onkeyup="$('#title_length').text(title_length - $('input[name=textline_title]').val().length);" /> <span id="title_length"><?php echo $row['simbols'];?></span></td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Target url (http://example.com)", "textline"); ?><span><?php echo __("Set target url to your website with http://", "textline"); ?></span></span></td>
							<td><input class="large" type="text" name="textline_target_url" value="<?php echo isset($_POST['textline_target_url']) ? safe($_POST['textline_target_url']) : ""; ?>" /></td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><span class="tooltip"><?php echo __("Contact email", "textline"); ?><span><?php echo __("Will use to technical notification", "textline"); ?></span></span></td>
							<td><input class="large" type="text" name="textline_email" value="<?php echo isset($_POST['textline_email']) ? safe($_POST['textline_email']) : ""; ?>" /></td>
						</tr>
						<tr class='row_<?php echo $tr;?>' id="textline_show_bought_count">
							<td><span class="tooltip"><?php echo __("Price of 1000 views is", "textline"); ?> <?php echo $row['price_1000'];?> <?php echo __("RUB", "textline"); ?>, <?php echo __("1000*N", "textline"); ?><span><?php echo __("Views number 1000*N", "textline"); ?></span></span></td>
							<td><input type="number" class="small" name="textline_show_bought_count" value="<?php echo isset($_POST['textline_show_bought_count']) ? intval($_POST['textline_show_bought_count']) : 1; ?>" onchange="calculate_price();" onkeyup="$('#bought_count').text($(this).val()*1000); calculate_price();" /> <span id="bought_count"><?php echo isset($_POST['textline_show_bought_count']) ? intval($_POST['textline_show_bought_count'] * 1000) : 1000; ?></span> <?php echo __("views", "textline"); ?></td>
						</tr>
						<?php if($row['price_no_blank'] > 0): ?>
							<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
								<td><span class="tooltip"><?php echo __("Price of target=_self is", "textline"); ?> <?php echo $row['price_no_blank'];?> <?php echo __("RUB", "textline"); ?><span><?php echo __("Open url in the self window", "textline"); ?></span></span></td>
								<td><input type="checkbox" name="textline_no_blank" value="1" <?php echo isset($_POST['textline_no_blank']) ? "checked='checked'" : ""; ?> onchange="calculate_price();"/></td>
							</tr>
						<?php endif;?>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><?php echo __("Terms of use", "textline"); ?></td>
							<td><input type="checkbox" name="textline_terms" value="1" <?php echo isset($_POST['textline_terms']) ? "checked='checked'" : ""; ?> /> <a class="alert" href="<?php echo $script_url;?>terms.php" target="_blank"><?php echo __("I have read and agree to the terms", "textline"); ?></a></td>
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
												$disc[] = ($discount_data[0] * ($key + 1)).__("RUB", "textline")." &gt;= -".$money.__("RUB", "textline");
											}
										} else if(count($discount_profit) >= 1) {
											foreach($discount_profit as $key => $money) {
												$disc[] = ($discount_data[0] * ($key + 1)).__("RUB", "textline")." &gt;= -".$money."%";
											}
										}
										echo implode(", ", $disc);
									}
								?>
							</td>
						</tr>
						<tr class='row_<?php echo $tr; $tr = 1 - $tr;?>'>
							<td><div id="textline_price" align="right"><b><?php echo __("Price total:", "textline"); ?></b> <span><?php echo ceil($price);?></span> <?php echo __("RUB", "textline"); ?></div></td>
							<td>
								<input type="submit" name="buy_textline" onclick="if(!filled()) return false;" value="<?php echo __("Buy Text line", "textline"); ?>" />
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
			var textline_id = <?php echo intval($_GET['id']);?>;
			var successful = "<?php echo ($settings['website_moderation'] == 'yes' ? __("Payment successful! Text line will be shown after moderation.", "textline") : __("Payment successful! Text line will be shown after few minutes.", "textline"));?>";
		</script>
		<script src="<?php echo $script_url;?>modules/textline/js/buy.js"></script>
		<link rel="stylesheet" href="<?php echo $script_url;?>modules/textline/css/buy.css" type="text/css" media="all" />
	</head>
	<body marginheight='0' marginwidth='0'>
		<div id="pay">
			<div id="top_title"><?php echo __("Payment page", "textline"); ?> &laquo;<?php echo $row['title']; ?>&raquo;</div>
			<div id="conteiner">
				<div class="information">
					<br />
					<div align="left"><input onclick="rebuild();" type="button" id="rebuild" value="<?php echo __("Rebuild", "textline"); ?>" /></div>
					<br />
					<div class="info"><span><img src="<?php echo $script_url;?>uploads/icon_<?php  echo $row3['id'];?>.ico?r=<?php echo rand(1, 10000000);?>" border="0" alt="<?php echo stripslashes($row3['title']); ?>" /></div>
					<div class="info"><span><?php echo __("Text line title", "textline"); ?>:</span><p style="display: inline; padding: 0px; word-wrap: break-word;"><?php echo stripslashes($row3['title']); ?></p></div>
					<div class="info"><span><?php echo __("Target url", "textline"); ?>:</span><?php echo $row3['target_url']; ?></div>
					<div class="info"><span><?php echo __("Views", "textline"); ?>:</span><?php echo ($row3['show_bought_count'] ? $row3['show_bought_count'] : __("unlimited", "textline")); ?></div>
					<div class="info"><span><?php echo __("Target page", "textline"); ?>:</span><?php echo ($row3['no_blank'] == 0 ? __("_self", "textline") : __("_blank", "textline")); ?></div>
					<div class="info"><span><?php echo __("Price", "textline"); ?>:</span><b><?php echo $price; ?></b> <?php echo __("RUB", "textline"); ?></div>
					<div class="preview">
						<div class="info"><span><?php echo __("In rotation", "textline"); ?>:</span></div>
						<?php
							$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_textline_in_work WHERE textline_id = ".intval($_GET['id'])." AND status = 1");
							while($row = $db->fetch($query)) {
								?>
									<a class="textline" target="<?php echo $row['no_blank'] == 0 ? "_top" : "_blank" ;?>" href="<?php echo $row['target_url'];?>">
										<img src="<?php echo $script_url;?>uploads/icon_<?php echo $row['id'];?>.ico" border="0" alt="<?php echo $row['title'];?>" />
										<p style="display: inline; padding: 0px; word-wrap: break-word;"><?php echo $row['title'];?></p>
									</a>
								<?php
							}
						?>
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
						$Shp_c_textline = $row3['id'];
						//Description
						$inv_desc = urlencode($row3['title']);

						foreach($Pay->payments as $object) {
							$object->onForm($email, $out_summ, $inv_id, $inv_desc, $custom_params = array("Shp_c_module" => "textline", "Shp_c_payment" => $object->name, "Shp_c_textline" => $Shp_c_textline));
						}
					?>
				</div>
			</div>
		</div>
	</body>
</html>
<?php endif; ?>
