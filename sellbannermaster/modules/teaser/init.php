<?php

	// Прямой доступ закрыт
	defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed.");

	/*
	 * Класс модуля
	 */
	class CTeaser {
		var $version = "0.1";
		var $name = "teaser";

		/*
		 * Функция, вызываемая при инсталляции модуля
		 */
		function onInstall() {
			global $db;

			// Таблицы модуля
			$db->query("CREATE TABLE IF NOT EXISTS ".TABLES_PREFIX."_teaser (
						id INT NOT NULL AUTO_INCREMENT,
						title VARCHAR(255) NOT NULL DEFAULT '',
						teaser_number INT NOT NULL DEFAULT 5,
						text_place TINYINT NOT NULL DEFAULT 0,
						text_block INT NOT NULL DEFAULT 50,
						font_size VARCHAR(8) NOT NULL DEFAULT '24px',
						simbols INT NOT NULL DEFAULT 70,
						size_x INT UNSIGNED NOT NULL DEFAULT 100,
						size_y INT UNSIGNED NOT NULL DEFAULT 100,
						weight INT UNSIGNED NOT NULL DEFAULT 50000,
						price_1000 FLOAT NOT NULL DEFAULT 60,
						price_no_blank FLOAT NOT NULL DEFAULT 10,
						status TINYINT NOT NULL DEFAULT 0,
						PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			$db->query("CREATE TABLE IF NOT EXISTS ".TABLES_PREFIX."_teaser_in_work (
						id INT NOT NULL AUTO_INCREMENT,
						teaser_id INT NOT NULL DEFAULT 0,
						title TEXT(4096) NOT NULL DEFAULT '',
						target_url TEXT(2048) NOT NULL DEFAULT '',
						page_md5 VARCHAR(32) NOT NULL DEFAULT '',
						clicks_current_count INT UNSIGNED NOT NULL DEFAULT 0,
						show_current_count INT UNSIGNED NOT NULL DEFAULT 0,
						show_start_time INT UNSIGNED NOT NULL DEFAULT 0,
						show_bought_count INT UNSIGNED NOT NULL DEFAULT 1000,
						no_blank TINYINT NOT NULL DEFAULT 0,
						extension VARCHAR(5) NOT NULL DEFAULT 'jpg',
						owner_email VARCHAR(255) NOT NULL DEFAULT '',
						turn BIGINT NOT NULL DEFAULT 0,
						status TINYINT NOT NULL DEFAULT 0,
						PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			$db->query("CREATE TABLE IF NOT EXISTS ".TABLES_PREFIX."_teaser_logs (
						id INT NOT NULL AUTO_INCREMENT,
						teaser_id INT NOT NULL DEFAULT 0,
						paid_md5 VARCHAR(32) NOT NULL DEFAULT '',
						paid_time INT UNSIGNED NOT NULL DEFAULT 0,
						order_id INT UNSIGNED NOT NULL DEFAULT 0,
						paid_amount FLOAT NOT NULL DEFAULT 0,
						paid_email VARCHAR(255) NOT NULL DEFAULT '',
						gateway VARCHAR(32) NOT NULL DEFAULT '',
						status TINYINT NOT NULL DEFAULT 0,
						PRIMARY KEY (id),
						UNIQUE (paid_md5)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		}

		/*
		 * Функция, вызываемая при удалении модуля
		 */
		function onUnInstall() {
			global $db;
			// Удаляем таблицы
			$db->query("DROP TABLE ".TABLES_PREFIX."_teaser");
			$db->query("DROP TABLE ".TABLES_PREFIX."_teaser_in_work");
			$db->query("DROP TABLE ".TABLES_PREFIX."_teaser_logs");
		}

		/*
		 * Функция меню модуля
		 */
		function onMenu() {
			return array(
				"places" => array(__("Teaser", "teaser"), "onPlaces"),
				"control" => array(__("Teaser", "teaser"), "onControl"),
				"logs" => array(__("Teaser", "teaser"), "onLogs"),
			);
		}

		/*
		 * Функция мест под рекламу
		 */
		function onPlaces() {
			global $db, $images_url, $script_url;
			if(isset($_SESSION['fxn_banner_admin']) && (isset($_POST['add_teaser_place']) || isset($_POST['edit_teaser']))) {
				//Add teaser place
				$error_info = array();
				//Check
				if(!isset($_POST['teaser_title']) || $_POST['teaser_title'] == "") {
					$error_info[] = __("Title can't be empty", "teaser");
				}
				if(!isset($_POST['text_block']) || $_POST['text_block'] == "") {
					$error_info[] = __("Block of text height can't be empty", "teaser");
				}
				if(!isset($_POST['teaser_number']) || $_POST['teaser_number'] < 1) {
					$error_info[] = __("Wrong number of teasers", "teaser");
				}
				if(!isset($_POST['font_size']) || $_POST['font_size'] == "") {
					$error_info[] = __("Font size can't be empty", "teaser");
				}
				if(!isset($_POST['teaser_weight']) || $_POST['teaser_weight'] <= 0) {
					$error_info[] = __("Wrong teaser file size", "teaser");
				}
				if(!isset($_POST['teaser_simbols']) || $_POST['teaser_simbols'] < 1) {
					$error_info[] = __("Wrong number of simbols", "teaser");
				}
				if(!isset($_POST['teaser_size_x']) || !isset($_POST['teaser_size_y'])) {
					$error_info[] = __("Wrong teaser size", "teaser");
				}
				$teaser_size_x = explode("%", $_POST['teaser_size_x']);
				$_POST['teaser_size_x'] = (isset($teaser_size_x[1]) ? -1 : 1) * $teaser_size_x[0];
				$teaser_size_y = explode("%", $_POST['teaser_size_y']);
				$_POST['teaser_size_y'] = (isset($teaser_size_y[1]) ? -1 : 1) * $teaser_size_y[0];
				if(!isset($_POST['teaser_price_1000']) || $_POST['teaser_price_1000'] < 0) {
					$error_info[] = __("Wrong teaser price for 1000 views", "teaser");
				}
				if(!isset($_POST['teaser_no_blank']) || $_POST['teaser_no_blank'] == "") {
					$error_info[] = __("Wrong teaser price for target=_blank", "teaser");
				}
				if(empty($error_info)) {
					if(isset($_POST['add_teaser_place'])) {
						//Add place
						$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_teaser (title, text_place, text_block, teaser_number, font_size, simbols, weight, size_x, size_y, price_1000, price_no_blank, status)
						VALUES(
							'".$db->safe(safe($_POST['teaser_title']))."',
							".intval($_POST['text_place']).",
							".intval($_POST['text_block']).",
							".intval($_POST['teaser_number']).",
							'".$db->safe(safe($_POST['font_size']))."',
							".intval($_POST['teaser_simbols']).",
							".intval($_POST['teaser_weight']).",
							".intval($_POST['teaser_size_x']).",
							".intval($_POST['teaser_size_y']).",
							".floatval($_POST['teaser_price_1000']).",
							".floatval($_POST['teaser_no_blank']).",
							1
						)");
						$information = __("Teaser place added!", "teaser");
					} else if(isset($_POST['edit_teaser']) && $_POST['edit_teaser'] == 'save') {
						//Change place
						$db->query("UPDATE ".TABLES_PREFIX."_teaser SET title = '".$db->safe(safe($_POST['teaser_title']))."', text_place = ".intval($_POST['text_place']).", text_block = ".intval($_POST['text_block']).", teaser_number = ".intval($_POST['teaser_number']).", font_size = '".$db->safe(safe($_POST['font_size']))."', simbols = ".intval($_POST['teaser_simbols']).", weight = ".intval($_POST['teaser_weight']).", size_x = ".intval($_POST['teaser_size_x']).", size_y = ".intval($_POST['teaser_size_y']).", price_1000 = ".floatval($_POST['teaser_price_1000']).", price_no_blank = ".floatval($_POST['teaser_no_blank']).", status = ".intval($_POST['teaser_status'])." WHERE id = ".intval($_POST['teaser_id']));
						$information = __("Teaser place changed!", "teaser");
					} else if(isset($_POST['edit_teaser']) && $_POST['edit_teaser'] == 'delete') {
						$db->query("DELETE FROM ".TABLES_PREFIX."_teaser WHERE id = ".intval($_POST['teaser_id']));
						$information = __("Teaser place deleted!", "teaser");
					}
				}
			}
			require_once(dirname(__FILE__)."/places.php");
		}

		/*
		 * Функция контроля
		 */
		function onControl() {
			global $db, $images_url, $script_url, $settings;
			if(isset($_POST['action_stat']) && $_POST['action_stat'] == 'delete') {
				//Delete
				$row3 = $db->query_fetch_row("SELECT extension, id FROM ".TABLES_PREFIX."_teaser_in_work WHERE id = ".intval($_POST['id']));
				unlink(dirname(__FILE__)."/../../uploads/teaser_".$row3['id'].".".$row3['extension']);
				$db->query("DELETE FROM ".TABLES_PREFIX."_teaser_in_work WHERE id = ".intval($_POST['id']));
				$information = __("Teaser deleted!", "teaser");
			} else if(isset($_POST['action_stat']) && $_POST['action_stat'] == 'save') {

				//Save
				$ext = "";
				if(!empty($_FILES['file_upload']['tmp_name'])) {
					$ext = "jpg";

					if($_FILES["file_upload"]["type"] == "image/gif") {
						$ext = "gif";
					} else if($_FILES["file_upload"]["type"] == "image/png") {
						$ext = "png";
					} else if($_FILES["file_upload"]["type"] == "application/swf") {
						$ext = "swf";
					}

					move_uploaded_file($_FILES['file_upload']['tmp_name'], dirname(__FILE__)."/../../uploads/teaser_".intval($_POST['id']).".".$ext);
				}

				$row4 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_teaser_in_work WHERE id = ".intval($_POST['id']));
				$show_start_time = "";
				if($row4['status'] != 1 && $_POST['status'] == 1) {
					$show_start_time = ", show_start_time = ".time();
				}

				$extension = "";
				if(!empty($ext)) {
					$extension = ", extension = '".$ext."'";
				}

				$db->query("UPDATE ".TABLES_PREFIX."_teaser_in_work
				SET title = '".$db->safe(safe($_POST['title']))."', target_url = '".$db->safe(safe($_POST['target_url']))."', owner_email = '".$db->safe(safe($_POST['owner_email']))."', clicks_current_count = ".intval($_POST['clicks_current_count']).", show_current_count = ".intval($_POST['show_current_count']).", show_bought_count = ".intval($_POST['show_bought_count']).", no_blank = ".intval($_POST['no_blank']).", status = ".intval($_POST['status']).$show_start_time.$extension."
				WHERE id = ".intval($_POST['id']));

				//Send moderation email
				if($row4['status'] == 4 && $_POST['status'] == 1) {
					fxn_send(safe($_POST['owner_email']), __("Teaser added to rotation", "teaser"), __("Teaser", "teaser").": ".intval($row4['id']).", ".__("successful added to rotation!", "teaser")."<br /><br />".__("Teaser title", "teaser").": ".$row4['title']."<br />".__("Target url", "teaser").": ".$row4['target_url'], $settings['website_email']);
				} else if($row4['status'] == 4 && $_POST['status'] == 5) {
					fxn_send(safe($_POST['owner_email']), __("Teaser declined", "teaser"), __("Teaser", "teaser").": ".intval($row4['id']).", ".__("declined during moderation proccess!", "teaser")."<br /><br />".__("Administration will contact you to resolving the issue during few days.", "teaser")."<br /><br />".__("Teaser title", "teaser").": ".$row4['title']."<br />".__("Target url", "teaser").": ".$row4['target_url'], $settings['website_email']);
				}

				$information = __("Teaser changed!", "teaser");
			}
			require_once(dirname(__FILE__)."/control.php");
		}

		/*
		 * Функция логов
		 */
		function onLogs() {
			global $db, $images_url, $script_url;
			require_once(dirname(__FILE__)."/logs.php");
		}

		/*
		 * Функция продления
		 */
		function onUserExtend() {
			global $db, $settings, $Pay;
			if($settings['selling_opened'] != 'yes') return;
			$row = false;
			if(isset($_POST['id'])) {
				$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_teaser_in_work WHERE status = 1 AND id = ".intval($_POST['id']));
				$row2 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_teaser WHERE id = ".intval($row['teaser_id']));
				//Email to send link
				$email = safe($row['owner_email']);
				//ROBOKASSA order number
				$inv_id = time();
				//ROBOKASSA item specification
				$Shp_c_extend_teaser = $row['id'];
				//Description
				$inv_desc = urldecode($row['title']);
			}
			if($row && $row['show_bought_count'] > 0 && isset($_POST['teaser_show_bought_count']) && $_POST['teaser_show_bought_count'] > 0) {
				//ROBOKASSA cost
				$out_summ = number_format(floatval($row2['price_1000'] * intval($_POST['teaser_show_bought_count'])), 2, '.', '');
				//ROBOKASSA extend
				$Shp_c_extend_count = intval($_POST['teaser_show_bought_count']);

				foreach($Pay->payments as $object) {
					$object->onForm($email, $out_summ, $inv_id, $inv_desc, $custom_params = array("Shp_c_extend_count" => $Shp_c_extend_count, "Shp_c_extend_teaser" => $Shp_c_extend_teaser, "Shp_c_module" => "teaser", "Shp_c_payment" => $object->name));
				}
			} else {
				echo __("Error", "teaser");
			}
		}

		/*
		 * Функция статистики пользователя
		 */
		function onUserStatistics() {
			global $db, $settings;
			?>
				<table width="100%" id="banners">
					<tr class='row_2'>
						<th colspan="5"><?php echo __("Teaser", "teaser"); ?></th>
					</tr>
					<tr class='row_1'>
						<th><?php echo __("Teaser title", "teaser"); ?></th>
						<th><?php echo __("Clicks", "teaser"); ?></th>
						<th><?php echo __("Views", "teaser"); ?></th>
						<th><?php echo __("Status", "teaser"); ?></th>
						<th><?php echo __("Extend", "teaser"); ?></th>
					</tr>
					<?php
						$tr = 1;
						$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_teaser_in_work WHERE owner_email = '".$db->safe($_GET['email'])."' ORDER BY id DESC");
						while($row = $db->fetch($query)) {
						$tr = 1 - $tr;
					?>
						<tr class='row_<?php echo $tr;?>'>
							<td style="text-align: left;" width="350">
								<p style="display: block; width: 350px; padding: 0px; word-wrap: break-word;"><?php echo stripslashes($row["title"]); ?></p>
							</td>
							<td>
								<?php echo $row["clicks_current_count"]; ?>
							</td>
							<td>
								<?php echo $row["show_current_count"]; ?> / <?php echo $row["show_bought_count"] > 0 ? $row["show_bought_count"] : __("unlimited", "teaser"); ?>
							</td>
							<td width="90">
								<?php
									$statuses = array(__("In proccess", "teaser"), __("In rotation", "teaser"), __("Finished", "teaser"), __("Rejected", "teaser"), __("Moderation", "teaser"), __("Declined", "teaser"));
									echo $statuses[$row["status"]];
								?>
							</td>
							<td width="250">
								<?php if($settings['selling_opened'] == 'yes' && $row["status"] == 1): ?>
									<form action="" method="post" style="margin: 0px;">
										<input type="hidden" name="id" value="<?php echo $row["id"]; ?>" />
										<input type="hidden" name="module" value="teaser" />
										<input type="number" class="mini" name="teaser_show_bought_count" value="1" onkeyup="if($(this).val() <= 0) $(this).val(1); $('#bought_count_<?php echo $row["id"]; ?>').text($(this).val() * 1000);" onchange="if($(this).val() <= 0) $(this).val(1); $('#bought_count_<?php echo $row["id"]; ?>').text($(this).val() * 1000);" />
										<span id="bought_count_<?php echo $row["id"]; ?>">1000</span> <?php echo __("views", "teaser"); ?>
										<input type="submit" name="extend" style="width: 80px; padding: 5px; float: right; font-size: 14px;" value="<?php echo __("Extend", "teaser"); ?>" />
									</form>
								<?php endif;?>
							</td>
						</tr>
					<?php
						}
					?>
				</table>
			<?php
		}

		/*
		 * Функция dashboard
		 */
		function onDashboard() {
			global $db;
			$row = $db->query_fetch_row("SELECT COUNT(id) AS c, SUM(paid_amount) AS a FROM ".TABLES_PREFIX."_teaser_logs");
			return $row;
		}

		/*
		 * Функция оформления заказа
		 */
		function onCallBack($Shp_email, $out_summ, $inv_id, $payment, $my_crc, $custom_params = array()) {
			global $db, $images_url, $script_url, $settings;
			$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_teaser_logs WHERE paid_md5 = '".$db->safe($my_crc)."'");
			if(!$row && !isset($custom_params['Shp_c_extend_teaser'])) {
				$db->query("BEGIN");
					$db->query("UPDATE ".TABLES_PREFIX."_teaser_in_work SET status = ".($settings['website_moderation'] == 'yes' ? 4 : 1)." WHERE id = ".intval($custom_params['Shp_c_teaser']));
					$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_teaser_in_work WHERE id = ".intval($custom_params['Shp_c_teaser']));
					$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_teaser_logs (teaser_id, paid_md5, paid_time, order_id, paid_amount, paid_email, gateway, status)
					VALUES(
						".intval($row['id']).",
						'".$db->safe($my_crc)."',
						".time().",
						".intval($inv_id).",
						".floatval($out_summ).",
						'".$db->safe(safe($Shp_email))."',
						'".$db->safe(safe($payment))."',
						1
					)");
				$db->query("COMMIT");

				//Send email
				fxn_send($Shp_email, __("Teaser added to rotation", "teaser"), ($settings['website_moderation'] == 'yes' ? __("Teaser paid, and will be shown after moderation.", "teaser") : __("Teaser paid, and will be shown after few minutes.", "teaser"))."<br /><br />".__("Teaser", "teaser").": ".intval($row['id'])."<br /><br />".__("Teaser title", "teaser").": ".stripslashes($row['title'])."<br />".__("Target url", "teaser").": ".$row['target_url']."<br />".__("Views", "teaser").": ".($row['show_bought_count'] ? $row['show_bought_count'] : __("unlimited", "teaser"))."<br />".__("Target page", "teaser").": ".($row['no_blank'] == 0 ? __("_self", "teaser") : __("_blank", "teaser"))."<br /><br />".__("Logs", "teaser").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "teaser")."</a><br /><br /><img src='".$script_url."uploads/teaser_".$row['id'].".".$row['extension']."' border='0' />", $settings['website_email']);

				//Send email
				fxn_send($settings['website_email'], __("Teaser added to rotation", "teaser"), ($settings['website_moderation'] == 'yes' ? __("Teaser paid, and waiting for moderation.", "teaser")."<br /><br />".__("Do you", "teaser").": <a href='".$script_url."modules/teaser/accept.php?hash=".$my_crc."'>".__("accept", "teaser")."</a> ".__("or", "teaser")." <a href='".$script_url."modules/teaser/decline.php?hash=".$my_crc."'>".__("decline", "teaser")."</a> ?<br /><br />" :  "")."<br /><br />".__("Teaser", "teaser").": ".intval($row['id'])."<br /><br />".__("Amount", "teaser").": ".floatval($out_summ)."<br /><br />".__("Teaser title", "teaser").": ".stripslashes($row['title'])."<br />".__("Target url", "teaser").": ".$row['target_url']."<br />".__("Views", "teaser").": ".($row['show_bought_count'] ? $row['show_bought_count'] : __("unlimited", "teaser"))."<br />".__("Target page", "teaser").": ".($row['no_blank'] == 0 ? __("_self", "teaser") : __("_blank", "teaser"))."<br /><br />".__("Logs", "teaser").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "teaser")."</a><br /><br /><img src='".$script_url."uploads/teaser_".$row['id'].".".$row['extension']."' border='0' />", $settings['website_email']);

				echo "ok";
			} else if(!$row && isset($custom_params['Shp_c_extend_teaser'])) {
				if(isset($custom_params['Shp_c_extend_count'])){
					$db->query("UPDATE ".TABLES_PREFIX."_teaser_in_work SET show_bought_count = show_bought_count + ".intval($custom_params['Shp_c_extend_count'] * 1000).", status = 1 WHERE id = ".intval($custom_params['Shp_c_extend_teaser']));
				}
				$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_teaser_logs (teaser_id, paid_md5, paid_time, order_id, paid_amount, paid_email, gateway, status)
					VALUES(
						".intval($custom_params['Shp_c_extend_teaser']).",
						'".$db->safe($my_crc)."',
						".time().",
						".intval($inv_id).",
						".floatval($out_summ).",
						'".$db->safe(safe($Shp_email))."',
						'".$db->safe(safe($payment))."',
						1
				)");

				//Send email
				fxn_send($Shp_email, __("Teaser extended", "teaser"), __("Teaser", "teaser").": ".intval($custom_params['Shp_c_extend_teaser'])."<br /><br />".__("Teaser extended for", "teaser").": ".intval($custom_params['Shp_c_extend_count'] * 1000)." ".__("Views", "teaser")."<br /><br />".__("Logs", "teaser").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "teaser")."</a>", $settings['website_email']);

				//Send email
				fxn_send($settings['website_email'], __("Teaser extended", "teaser"), __("Teaser", "teaser").": ".intval($custom_params['Shp_c_extend_teaser'])."<br /><br />".__("Teaser extended for", "teaser").": ".intval($custom_params['Shp_c_extend_count'] * 1000)." ".__("Views", "teaser")."<br /><br />".__("Logs", "teaser").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "teaser")."</a>", $settings['website_email']);
			}
		}
	}
	if(isset($_SESSION['fxn_banner_admin']) && isset($_GET['teaser_download_stat']) && $_GET['teaser_download_stat'] == "csv") {
		// Get data
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_teaser_in_work ORDER BY id DESC");

		$data = "ID,Start date,Place ID,Title,Target Url,Clicks,Views,Ordered views,_blank,Image,Status\n";
		while($row = $db->fetch($query)) {
			$data .= $row['id'].",'".date("Y-m-d h:i:s", $row['show_start_time'])."',".$row['teaser_id'].",'".$row['title']."',".$row['target_url'].",".$row['clicks_current_count'].",".$row['show_current_count'].",".$row['show_bought_count'].",".$row['no_blank'].",'teaser_".$row['id'].".".$row['extension']."',".$row['status']."\n";
		}
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=teasers.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		//Send
		die($data);
	}
	if(isset($_SESSION['fxn_banner_admin']) && isset($_GET['teaser_download']) && $_GET['teaser_download'] == "csv") {
		// Get data
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_teaser_logs ORDER BY paid_time DESC");
		$data = "Time,Teaser ID,md5,Order ID,Amount,Email,Gateway,Status\n";
		while($row = $db->fetch($query)) {
			$data .= "'".date("Y-m-d h:i:s", $row['paid_time'])."',".$row['teaser_id'].",'".$row['paid_md5']."',".$row['order_id'].",".$row['paid_amount'].",'".$row['paid_email']."','".$row['gateway']."',".$row['status']."\n";
		}
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=teaser_transaction.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		//Send
		die($data);
	}

?>
