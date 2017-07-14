<?php

	// Прямой доступ закрыт
	defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed.");

	/*
	 * Класс модуля
	 */
	class CAds {
		var $version = "0.1";
		var $name = "ads";

		/*
		 * Функция, вызываемая при инсталляции модуля
		 */
		function onInstall() {
			global $db;

			// Таблицы модуля
			$db->query("CREATE TABLE IF NOT EXISTS ".TABLES_PREFIX."_ads (
						id INT NOT NULL AUTO_INCREMENT,
						title VARCHAR(255) NOT NULL DEFAULT '',
						simbols INT NOT NULL DEFAULT 70,
						font_size VARCHAR(8) NOT NULL DEFAULT '24px',
						ads_number INT NOT NULL DEFAULT 5,
						size_x INT NOT NULL DEFAULT -100,
						size_y INT NOT NULL DEFAULT 20,
						price_ad FLOAT NOT NULL DEFAULT 60,
						price_no_blank FLOAT NOT NULL DEFAULT 10,
						status TINYINT NOT NULL DEFAULT 0,
						PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			$db->query("CREATE TABLE IF NOT EXISTS ".TABLES_PREFIX."_ads_in_work (
						id INT NOT NULL AUTO_INCREMENT,
						ads_id INT NOT NULL DEFAULT 0,
						title TEXT(4096) NOT NULL DEFAULT '',
						target_url TEXT(2048) NOT NULL DEFAULT '',
						page_md5 VARCHAR(32) NOT NULL DEFAULT '',
						clicks_current_count INT UNSIGNED NOT NULL DEFAULT 0,
						show_current_count INT UNSIGNED NOT NULL DEFAULT 0,
						show_start_time INT UNSIGNED NOT NULL DEFAULT 0,
						no_blank TINYINT NOT NULL DEFAULT 0,
						owner_email VARCHAR(255) NOT NULL DEFAULT '',
						status TINYINT NOT NULL DEFAULT 0,
						PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			$db->query("CREATE TABLE IF NOT EXISTS ".TABLES_PREFIX."_ads_logs (
						id INT NOT NULL AUTO_INCREMENT,
						ads_id INT NOT NULL DEFAULT 0,
						paid_md5 VARCHAR(32) NOT NULL DEFAULT '',
						paid_time INT UNSIGNED NOT NULL DEFAULT 0,
						order_id INT UNSIGNED NOT NULL DEFAULT 0,
						paid_amount FLOAT NOT NULL DEFAULT 0,
						paid_email VARCHAR(255) NOT NULL DEFAULT '',
						gateway VARCHAR(32) NOT NULL DEFAULT '',
						status TINYINT NOT NULL DEFAULT 0,
						PRIMARY KEY (id),
						UNIQUE (ads_id),
						UNIQUE (paid_md5)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		}

		/*
		 * Функция, вызываемая при удалении модуля
		 */
		function onUnInstall() {
			global $db;
			// Удаляем таблицы
			$db->query("DROP TABLE ".TABLES_PREFIX."_ads");
			$db->query("DROP TABLE ".TABLES_PREFIX."_ads_in_work");
			$db->query("DROP TABLE ".TABLES_PREFIX."_ads_logs");
		}

		/*
		 * Функция меню модуля
		 */
		function onMenu() {
			return array(
				"places" => array(__("Ads", "ads"), "onPlaces"),
				"control" => array(__("Ads", "ads"), "onControl"),
				"logs" => array(__("Ads", "ads"), "onLogs"),
			);
		}

		/*
		 * Функция мест под рекламу
		 */
		function onPlaces() {
			global $db, $images_url, $script_url;
			if(isset($_SESSION['fxn_banner_admin']) && (isset($_POST['add_ads_place']) || isset($_POST['edit_ads']))) {
				//Add ads place
				$error_info = array();
				if(!isset($_POST['ads_title']) || $_POST['ads_title'] == "") {
					$error_info[] = __("Title can't be empty", "ads");
				}
				if(!isset($_POST['font_size']) || $_POST['font_size'] == "") {
					$error_info[] = __("Font size can't be empty", "ads");
				}
				if(!isset($_POST['ads_simbols']) || $_POST['ads_simbols'] < 1) {
					$error_info[] = __("Wrong number of simbols", "ads");
				}
				if(!isset($_POST['ads_number']) || $_POST['ads_number'] < 1) {
					$error_info[] = __("Wrong number of ads number", "ads");
				}
				if(!isset($_POST['ads_size_x']) || !isset($_POST['ads_size_y'])) {
					$error_info[] = __("Wrong ad size", "ads");
				}
				$ads_size_x = explode("%", $_POST['ads_size_x']);
				$_POST['ads_size_x'] = (isset($ads_size_x[1]) ? -1 : 1) * $ads_size_x[0];
				$ads_size_y = explode("%", $_POST['ads_size_y']);
				$_POST['ads_size_y'] = (isset($ads_size_y[1]) ? -1 : 1) * $ads_size_y[0];
				if(!isset($_POST['ads_price_ad']) || $_POST['ads_price_ad'] < 0) {
					$error_info[] = __("Wrong ad price for 1000 views", "ads");
				}
				if(!isset($_POST['ads_no_blank']) || $_POST['ads_no_blank'] == "") {
					$error_info[] = __("Wrong ad price for target=_blank", "ads");
				}
				if(empty($error_info)) {
					if(isset($_POST['add_ads_place'])) {
						//Add place
						$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_ads (title, font_size, simbols, ads_number, size_x, size_y, price_ad, price_no_blank, status)
						VALUES(
							'".$db->safe(safe($_POST['ads_title']))."',
							'".$db->safe(safe($_POST['font_size']))."',
							".intval($_POST['ads_simbols']).",
							".intval($_POST['ads_number']).",
							".intval($_POST['ads_size_x']).",
							".intval($_POST['ads_size_y']).",
							".floatval($_POST['ads_price_ad']).",
							".floatval($_POST['ads_no_blank']).",
							1
						)");
						$information = __("Ad place added!", "ads");
					} else if(isset($_POST['edit_ads']) && $_POST['edit_ads'] == 'save') {
						//Change place
						$db->query("UPDATE ".TABLES_PREFIX."_ads SET title = '".$db->safe(safe($_POST['ads_title']))."', font_size = '".$db->safe(safe($_POST['font_size']))."', simbols = ".intval($_POST['ads_simbols']).", ads_number = ".intval($_POST['ads_number']).", size_x = ".intval($_POST['ads_size_x']).", size_y = ".intval($_POST['ads_size_y']).", price_ad = ".floatval($_POST['ads_price_ad']).", price_no_blank = ".floatval($_POST['ads_no_blank']).", status = ".intval($_POST['ads_status'])." WHERE id = ".intval($_POST['ads_id']));
						$information = __("Ad place changed!", "ads");
					} else if(isset($_POST['edit_ads']) && $_POST['edit_ads'] == 'delete') {
						$db->query("DELETE FROM ".TABLES_PREFIX."_ads WHERE id = ".intval($_POST['ads_id']));
						$information = __("Ad place deleted!", "ads");
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
				unlink(dirname(__FILE__)."/../../uploads/ad_".intval($_POST['id']).".ico");
				$db->query("DELETE FROM ".TABLES_PREFIX."_ads_in_work WHERE id = ".intval($_POST['id']));
				$information = __("Ad deleted!", "ads");
			} else if(isset($_POST['action_stat']) && $_POST['action_stat'] == 'save') {
				$row4 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_ads_in_work WHERE id = ".intval($_POST['id']));
				$show_start_time = "";
				if($row4['status'] != 1 && $_POST['status'] == 1) {
					$show_start_time = ", show_start_time = ".time();
				}
				$db->query("UPDATE ".TABLES_PREFIX."_ads_in_work
				SET title = '".$db->safe(safe($_POST['title']))."', target_url = '".$db->safe(safe($_POST['target_url']))."', owner_email = '".$db->safe(safe($_POST['owner_email']))."', clicks_current_count = ".intval($_POST['clicks_current_count']).", show_current_count = ".intval($_POST['show_current_count']).", no_blank = ".intval($_POST['no_blank']).", status = ".intval($_POST['status']).$show_start_time."
				WHERE id = ".intval($_POST['id']));

				//Send moderation email
				if($row4['status'] == 4 && $_POST['status'] == 1) {
					fxn_send(safe($_POST['owner_email']), __("Ad added to rotation", "ads"), __("Ad", "ads").": ".intval($row4['id']).", ".__("successful added to rotation!", "ads")."<br /><br />".__("Ad title", "ads").": ".$row4['title']."<br />".__("Target url", "ads").": ".$row4['target_url'], $settings['website_email']);
				} else if($row4['status'] == 4 && $_POST['status'] == 5) {
					fxn_send(safe($_POST['owner_email']), __("Ad declined", "ads"), __("Ad", "ads").": ".intval($row4['id']).", ".__("declined during moderation proccess!", "ads")."<br /><br />".__("Administration will contact you to resolving the issue during few days.", "ads")."<br /><br />".__("Ad title", "ads").": ".$row4['title']."<br />".__("Target url", "ads").": ".$row4['target_url'], $settings['website_email']);
				}

				$information = __("Ad changed!", "ads");
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
				$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_ads_in_work WHERE status = 1 AND id = ".intval($_POST['id']));
				$row2 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_ads WHERE id = ".intval($row['ads_id']));
				//Email to send ad
				$email = safe($row['owner_email']);
				//ROBOKASSA order number
				$inv_id = time();
				//ROBOKASSA item specification
				$Shp_c_extend_ad = $row['id'];
				//Description
				$inv_desc = urldecode($row['title']);
			}
			if($row) {
				//ROBOKASSA cost
				$out_summ = number_format(floatval($row2['price_ad']), 2, '.', '');

				foreach($Pay->payments as $object) {
					$object->onForm($email, $out_summ, $inv_id, $inv_desc, $custom_params = array("Shp_c_extend_ad" => $Shp_c_extend_ad, "Shp_c_module" => "ads", "Shp_c_payment" => $object->name));
				}
			} else {
				echo __("Error", "ads");
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
						<th colspan="5"><?php echo __("Ads", "ads"); ?></th>
					</tr>
					<tr class='row_1'>
						<th><?php echo __("Ad title", "ads"); ?></th>
						<th><?php echo __("Clicks", "ads"); ?></th>
						<th><?php echo __("Views", "ads"); ?></th>
						<th><?php echo __("Status", "ads"); ?></th>
						<th><?php echo __("Up to top", "ads"); ?></th>
					</tr>
					<?php
						$tr = 1;
						$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_ads_in_work WHERE owner_email = '".$db->safe($_GET['email'])."' ORDER BY id DESC");
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
								<?php echo $row["show_current_count"]; ?>
							</td>
							<td width="90">
								<?php
									$statuses = array(__("In proccess", "ads"), __("In rotation", "ads"), __("Finished", "ads"), __("Rejected", "ads"), __("Moderation", "ads"), __("Declined", "ads"));
									echo $statuses[$row["status"]];
								?>
							</td>
							<td width="250">
								<?php if($settings['selling_opened'] == 'yes' && $row["status"] == 1): ?>
									<form action="" method="post" style="margin: 0px;">
										<input type="hidden" name="id" value="<?php echo $row["id"]; ?>" />
										<input type="hidden" name="module" value="ads" />
										<input type="submit" name="extend" style="width: 120px; padding: 5px; float: right; font-size: 14px;" value="<?php echo __("Up to top", "ads"); ?>" />
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
			$row = $db->query_fetch_row("SELECT COUNT(id) AS c, SUM(paid_amount) AS a FROM ".TABLES_PREFIX."_ads_logs");
			return $row;
		}

		/*
		 * Функция оформления заказа
		 */
		function onCallBack($Shp_email, $out_summ, $inv_id, $payment, $my_crc, $custom_params = array()) {
			global $db, $images_url, $script_url, $settings;
			$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_ads_logs WHERE paid_md5 = '".$db->safe($my_crc)."'");
			if(!$row && !isset($custom_params['Shp_c_extend_ad'])) {
				$db->query("BEGIN");
					$db->query("UPDATE ".TABLES_PREFIX."_ads_in_work SET status = ".($settings['website_moderation'] == 'yes' ? 4 : 1)." WHERE id = ".intval($custom_params['Shp_c_ads']));
					$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_ads_in_work WHERE id = ".intval($custom_params['Shp_c_ads']));
					$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_ads_logs (ads_id, paid_md5, paid_time, order_id, paid_amount, paid_email, gateway, status)
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
				fxn_send($Shp_email, __("Ad added to rotation", "ads"), ($settings['website_moderation'] == 'yes' ? __("Ad paid, and will be shown after moderation.", "ads") : __("Ad paid, and will be shown after few minutes.", "ads"))."<br /><br />".__("Ad", "ads").": ".intval($row['id'])."<br /><br />".__("Ad title", "ads").": ".stripslashes($row['title'])."<br />".__("Target url", "ads").": ".$row['target_url']."<br />".__("Target page", "ads").": ".($row['no_blank'] == 0 ? __("_self", "ads") : __("_blank", "ads"))."<br /><br />".__("Logs", "ads").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "ads")."</a>", $settings['website_email']);

				//Send email
				fxn_send($settings['website_email'], __("Ad added to rotation", "ads"), ($settings['website_moderation'] == 'yes' ? __("Ad paid, and waiting for moderation.", "ads")."<br /><br />".__("Do you", "ads").": <a href='".$script_url."modules/ads/accept.php?hash=".$my_crc."'>".__("accept", "ads")."</a> ".__("or", "ads")." <a href='".$script_url."modules/ads/decline.php?hash=".$my_crc."'>".__("decline", "ads")."</a> ?<br /><br />" :  "")."<br /><br />".__("Ad", "ads").": ".intval($row['id'])."<br /><br />".__("Amount", "ads").": ".floatval($out_summ)."<br /><br />".__("Ad title", "ads").": ".stripslashes($row['title'])."<br />".__("Target url", "ads").": ".$row['target_url']."<br />".__("Target page", "ads").": ".($row['no_blank'] == 0 ? __("_self", "ads") : __("_blank", "ads"))."<br /><br />".__("Logs", "ads").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "ads")."</a>", $settings['website_email']);

				echo "ok";
			} else if(!$row && isset($custom_params['Shp_c_extend_ad'])) {
				$db->query("BEGIN");
					$row2 = $db->query_fetch_row("SELECT MAX(id) AS mid FROM ".TABLES_PREFIX."_ads_in_work");
					$db->query("UPDATE ".TABLES_PREFIX."_ads_in_work SET id = ".intval($row2['mid'] + 1).", status = 1 WHERE id = ".intval($custom_params['Shp_c_extend_ad']));
					$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_ads_logs (ads_id, paid_md5, paid_time, order_id, paid_amount, paid_email, gateway, status)
					VALUES(
						".intval($row2['mid'] + 1).",
						'".$db->safe($my_crc)."',
						".time().",
						".intval($inv_id).",
						".floatval($out_summ).",
						'".$db->safe(safe($Shp_email))."',
						'".$db->safe(safe($payment))."',
						1
					)");
				$db->query("COMMIT");
				rename(dirname(__FILE__)."/../../uploads/ad_".intval($custom_params['Shp_c_extend_ad']).".ico", dirname(__FILE__)."/../../uploads/ad_".($row2['mid'] + 1).".ico");

				//Send email
				fxn_send($Shp_email, __("Ad upped to top", "ads"), __("Ad", "ads").": ".intval($custom_params['Shp_c_extend_ad'])."<br /><br />".__("Ad upped to top", "ads")."<br /><br />".__("Logs", "ads").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "ads")."</a>", $settings['website_email']);

				//Send email
				fxn_send($settings['website_email'], __("Ad upped to top", "ads"), __("Ad", "ads").": ".intval($custom_params['Shp_c_extend_ad'])."<br /><br />".__("Ad upped to top", "ads")."<br /><br />".__("Logs", "ads").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "ads")."</a>", $settings['website_email']);
			}
		}
	}
	if(isset($_SESSION['fxn_banner_admin']) && isset($_GET['ads_download_stat']) && $_GET['ads_download_stat'] == "csv") {
		// Get data
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_ads_in_work ORDER BY id DESC");

		$data = "ID,Start date,Place ID,Title,Target Url,Clicks,Views,_blank,Image,Status\n";
		while($row = $db->fetch($query)) {
			$data .= $row['id'].",'".date("Y-m-d h:i:s", $row['show_start_time'])."',".$row['ads_id'].",'".$row['title']."',".$row['target_url'].",".$row['clicks_current_count'].",".$row['show_current_count'].",".$row['no_blank'].",'ad_".$row['id'].".ico',".$row['status']."\n";
		}
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=ads.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		//Send
		die($data);
	}
	if(isset($_SESSION['fxn_banner_admin']) && isset($_GET['ads_download']) && $_GET['ads_download'] == "csv") {
		// Get data
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_ads_logs ORDER BY paid_time DESC");
		$data = "Time,Ad ID,md5,Order ID,Amount,Email,Gateway,Status\n";
		while($row = $db->fetch($query)) {
			$data .= "'".date("Y-m-d h:i:s", $row['paid_time'])."',".$row['ads_id'].",'".$row['paid_md5']."',".$row['order_id'].",".$row['paid_amount'].",'".$row['paid_email']."','".$row['gateway']."',".$row['status']."\n";
		}
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=ads_transaction.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		//Send
		die($data);
	}

?>
