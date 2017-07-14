<?php

	// Прямой доступ закрыт
	defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed.");

	/*
	 * Класс модуля
	 */
	class CBanners {
		var $version = "0.1";
		var $name = "banners";

		/*
		 * Функция, вызываемая при инсталляции модуля
		 */
		function onInstall() {
			global $db;

			// Таблицы модуля
			$db->query("CREATE TABLE IF NOT EXISTS ".TABLES_PREFIX."_banners (
						id INT NOT NULL AUTO_INCREMENT,
						title VARCHAR(255) NOT NULL DEFAULT '',
						size_x INT UNSIGNED NOT NULL DEFAULT 100,
						size_y INT UNSIGNED NOT NULL DEFAULT 100,
						weight INT UNSIGNED NOT NULL DEFAULT 50000,
						price_1000 FLOAT NOT NULL DEFAULT 60,
						price_day FLOAT NOT NULL DEFAULT 6,
						price_no_blank FLOAT NOT NULL DEFAULT 10,
						cross_page_crosspage TINYINT NOT NULL DEFAULT 2,
						status TINYINT NOT NULL DEFAULT 0,
						PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			$db->query("CREATE TABLE IF NOT EXISTS ".TABLES_PREFIX."_banners_in_work (
						id INT NOT NULL AUTO_INCREMENT,
						banner_id INT NOT NULL DEFAULT 0,
						title VARCHAR(255) NOT NULL DEFAULT '',
						target_url TEXT(2048) NOT NULL DEFAULT '',
						page_md5 VARCHAR(32) NOT NULL DEFAULT '',
						clicks_current_count INT UNSIGNED NOT NULL DEFAULT 0,
						show_current_count INT UNSIGNED NOT NULL DEFAULT 0,
						show_start_time INT UNSIGNED NOT NULL DEFAULT 0,
						show_bought_count INT UNSIGNED NOT NULL DEFAULT 1000,
						show_bought_time INT UNSIGNED NOT NULL DEFAULT 86400,
						no_blank TINYINT NOT NULL DEFAULT 0,
						cross_page_crosspage TINYINT NOT NULL DEFAULT 0,
						extension VARCHAR(5) NOT NULL DEFAULT 'jpg',
						owner_email VARCHAR(255) NOT NULL DEFAULT '',
						status TINYINT NOT NULL DEFAULT 0,
						PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			$db->query("CREATE TABLE IF NOT EXISTS ".TABLES_PREFIX."_banners_logs (
						id INT NOT NULL AUTO_INCREMENT,
						banner_id INT NOT NULL DEFAULT 0,
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
			$db->query("DROP TABLE ".TABLES_PREFIX."_banners");
			$db->query("DROP TABLE ".TABLES_PREFIX."_banners_in_work");
			$db->query("DROP TABLE ".TABLES_PREFIX."_banners_logs");
		}

		/*
		 * Функция меню модуля
		 */
		function onMenu() {
			return array(
				"places" => array(__("Banners", "banners"), "onPlaces"),
				"control" => array(__("Banners", "banners"), "onControl"),
				"logs" => array(__("Banners", "banners"), "onLogs"),
			);
		}

		/*
		 * Функция мест под рекламу
		 */
		function onPlaces() {
			global $db, $images_url, $script_url;
			if(isset($_SESSION['fxn_banner_admin']) && (isset($_POST['add_banner_place']) || isset($_POST['edit_banner']))) {
				//Add banner place
				$error_info = array();
				if(!isset($_POST['banner_title']) || $_POST['banner_title'] == "") {
					$error_info[] = __("Title can't be empty", "banners");
				}
				if(!isset($_POST['banner_size_x']) || !isset($_POST['banner_size_y']) || $_POST['banner_size_x'] <= 0 || $_POST['banner_size_y'] <= 0) {
					$error_info[] = __("Wrong banner size", "banners");
				}
				if(!isset($_POST['banner_weight']) || $_POST['banner_weight'] <= 0) {
					$error_info[] = __("Wrong banner weight", "banners");
				}
				if(!isset($_POST['banner_price_1000']) || $_POST['banner_price_1000'] < 0) {
					$error_info[] = __("Wrong banner price for 1000 views", "banners");
				}
				if(!isset($_POST['banner_price_day']) || $_POST['banner_price_day'] < 0) {
					$error_info[] = __("Wrong banner price for day views", "banners");
				}
				if(!isset($_POST['banner_no_blank']) || $_POST['banner_no_blank'] == "") {
					$error_info[] = __("Wrong banner price for target=_blank", "banners");
				}
				if(!isset($_POST['banner_cross_page_crosspage']) || !in_array(intval($_POST['banner_cross_page_crosspage']), array(0, 1, 2))) {
					$error_info[] = __("Wrong banner cross no cross page parameter", "banners");
				}
				if(empty($error_info)) {
					if(isset($_POST['add_banner_place'])) {
						//Add place
						$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_banners (title, size_x, size_y, weight, price_1000,  price_day, price_no_blank, cross_page_crosspage, status)
						VALUES(
							'".$db->safe(safe($_POST['banner_title']))."',
							".intval($_POST['banner_size_x']).",
							".intval($_POST['banner_size_y']).",
							".intval($_POST['banner_weight']).",
							".floatval($_POST['banner_price_1000']).",
							".floatval($_POST['banner_price_day']).",
							".floatval($_POST['banner_no_blank']).",
							".intval($_POST['banner_cross_page_crosspage']).",
							1
						)");
						$information = __("Banner place added!", "banners");
					} else if(isset($_POST['edit_banner']) && $_POST['edit_banner'] == 'save') {
						//Change place
						$db->query("UPDATE ".TABLES_PREFIX."_banners SET title = '".$db->safe(safe($_POST['banner_title']))."', size_x = ".intval($_POST['banner_size_x']).", size_y = ".intval($_POST['banner_size_y']).", weight = ".intval($_POST['banner_weight']).", price_1000 = ".floatval($_POST['banner_price_1000']).",  price_day = ".floatval($_POST['banner_price_day']).", price_no_blank = ".floatval($_POST['banner_no_blank']).", cross_page_crosspage = ".intval($_POST['banner_cross_page_crosspage']).", status = ".intval($_POST['banner_status'])." WHERE id = ".intval($_POST['banner_id']));
						$information = __("Banner place changed!", "banners");
					} else if(isset($_POST['edit_banner']) && $_POST['edit_banner'] == 'delete') {
						$db->query("DELETE FROM ".TABLES_PREFIX."_banners WHERE id = ".intval($_POST['banner_id']));
						$information = __("Banner place deleted!", "banners");
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
				$row3 = $db->query_fetch_row("SELECT extension, id FROM ".TABLES_PREFIX."_banners_in_work WHERE id = ".intval($_POST['id']));
				unlink(dirname(__FILE__)."/../../uploads/banner_".$row3['id'].".".$row3['extension']);
				$db->query("DELETE FROM ".TABLES_PREFIX."_banners_in_work WHERE id = ".intval($_POST['id']));
				$information = __("Banner deleted!", "banners");
			} else if(isset($_POST['action_stat']) && $_POST['action_stat'] == 'save') {

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

					move_uploaded_file($_FILES['file_upload']['tmp_name'], dirname(__FILE__)."/../../uploads/banner_".intval($_POST['id']).".".$ext);
				}

				$row4 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_banners_in_work WHERE id = ".intval($_POST['id']));
				$show_start_time = "";
				if($row4['status'] != 1 && $_POST['status'] == 1) {
					$show_start_time = ", show_start_time = ".time();
				}
				$extension = "";
				if(!empty($ext)) {
					$extension = ", extension = '".$ext."'";
				}

				$db->query("UPDATE ".TABLES_PREFIX."_banners_in_work
				SET title = '".$db->safe(safe($_POST['title']))."', target_url = '".$db->safe(safe($_POST['target_url']))."', owner_email = '".$db->safe(safe($_POST['owner_email']))."', clicks_current_count = ".intval($_POST['clicks_current_count']).", show_current_count = ".intval($_POST['show_current_count']).", show_bought_count = ".intval($_POST['show_bought_count']).", show_bought_time = ".intval($_POST['show_bought_time']).", no_blank = ".intval($_POST['no_blank']).", cross_page_crosspage = ".intval($_POST['cross_page_crosspage']).", status = ".intval($_POST['status']).$show_start_time.$extension."
				WHERE id = ".intval($_POST['id']));

				//Send moderation email
				if($row4['status'] == 4 && $_POST['status'] == 1) {
					fxn_send(safe($_POST['owner_email']), __("Banner added to rotation", "banners"), __("Banner", "banners").": ".intval($row4['id']).", ".__("successful added to rotation!", "banners")."<br /><br />".__("Banner title", "banners").": ".$row4['title']."<br />".__("Target url", "banners").": ".$row4['target_url'], $settings['website_email']);
				} else if($row4['status'] == 4 && $_POST['status'] == 5) {
					fxn_send(safe($_POST['owner_email']), __("Banner declined", "banners"), __("Banner", "banners").": ".intval($row4['id']).", ".__("declined during moderation proccess!", "banners")."<br /><br />".__("Administration will contact you to resolving the issue during few days.", "banners")."<br /><br />".__("Banner title", "banners").": ".$row4['title']."<br />".__("Target url", "banners").": ".$row4['target_url'], $settings['website_email']);
				}

				$information = __("Banner changed!", "banners");
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
		 * Функция статистики пользователя
		 */
		function onUserStatistics() {
			global $db, $settings;
			?>
				<table width="100%" id="banners">
					<tr class='row_2'>
						<th colspan="6"><?php echo __("Banners", "banners"); ?></th>
					</tr>
					<tr class='row_1'>
						<th><?php echo __("Banner title", "banners"); ?></th>
						<th><?php echo __("Clicks", "banners"); ?></th>
						<th><?php echo __("Views", "banners"); ?></th>
						<th><?php echo __("Time in days", "banners"); ?></th>
						<th><?php echo __("Status", "banners"); ?></th>
						<th><?php echo __("Extend", "banners"); ?></th>
					</tr>
					<?php
						$tr = 1;
						$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_banners_in_work WHERE owner_email = '".$db->safe($_GET['email'])."' ORDER BY id DESC");
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
								<?php echo $row["show_current_count"]; ?> / <?php echo $row["show_bought_count"] > 0 ? $row["show_bought_count"] : __("unlimited", "banners"); ?>
							</td>
							<td>
								<?php echo floor((time() - $row["show_start_time"]) / 86400) > $row["show_bought_time"] ? $row["show_bought_time"] : floor((time() - $row["show_start_time"]) / 86400); ?> / <?php echo $row["show_bought_time"] > 0 ? $row["show_bought_time"] : __("unlimited", "banners"); ?>
							</td>
							<td width="90">
								<?php
									$statuses = array(__("In proccess", "banners"), __("In rotation", "banners"), __("Finished", "banners"), __("Rejected", "banners"), __("Moderation", "banners"), __("Declined", "banners"));
									echo $statuses[$row["status"]];
								?>
							</td>
							<td width="250">
								<?php if($settings['selling_opened'] == 'yes' && $row["status"] == 1): ?>
									<form action="" method="post" style="margin: 0px;">
										<input type="hidden" name="id" value="<?php echo $row["id"]; ?>" />
										<input type="hidden" name="module" value="banners" />
										<?php if($row["show_bought_count"] > 0): ?>
											<input type="number" class="mini" name="banner_show_bought_count" value="1" onkeyup="if($(this).val() <= 0) $(this).val(1); $('#bought_count_<?php echo $row["id"]; ?>').text($(this).val() * 1000);" onchange="if($(this).val() <= 0) $(this).val(1); $('#bought_count_<?php echo $row["id"]; ?>').text($(this).val() * 1000);" />
											<span id="bought_count_<?php echo $row["id"]; ?>">1000</span> <?php echo __("views", "banners"); ?>
										<?php else: ?>
											<input type="number" class="mini" name="banner_show_bought_time" value="1" onchange="if($(this).val() <= 0) $(this).val(1);" />
											<?php echo __("days", "banners"); ?>
										<?php endif;?>
										<input type="submit" name="extend" style="width: 80px; padding: 5px; float: right; font-size: 14px;" value="<?php echo __("Extend", "banners"); ?>" />
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
		 * Функция продления
		 */
		function onUserExtend() {
			global $db, $settings, $Pay;
			if($settings['selling_opened'] != 'yes') return;
			$row = false;
			if(isset($_POST['id'])) {
				$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_banners_in_work WHERE status = 1 AND id = ".intval($_POST['id']));
				$row2 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_banners WHERE id = ".intval($row['banner_id']));
				//Email to send link
				$email = safe($row['owner_email']);
				//ROBOKASSA order number
				$inv_id = time();
				//ROBOKASSA item specification
				$Shp_c_extend_banner = $row['id'];
				//Description
				$inv_desc = urldecode($row['title']);
			}
			if($row && $row['show_bought_count'] > 0 && isset($_POST['banner_show_bought_count']) && $_POST['banner_show_bought_count'] > 0) {
				//ROBOKASSA cost
				$out_summ = number_format(floatval($row2['price_1000'] * intval($_POST['banner_show_bought_count'])), 2, '.', '');
				//ROBOKASSA extend
				$Shp_c_extend_count = intval($_POST['banner_show_bought_count']);

				foreach($Pay->payments as $object) {
					$object->onForm($email, $out_summ, $inv_id, $inv_desc, $custom_params = array("Shp_c_extend_banner" => $Shp_c_extend_banner, "Shp_c_extend_count" => $Shp_c_extend_count, "Shp_c_module" => "banners", "Shp_c_payment" => $object->name));
				}
			} else if($row && $row['show_bought_time'] > 0 && isset($_POST['banner_show_bought_time']) && $_POST['banner_show_bought_time'] > 0) {
				//ROBOKASSA cost
				$out_summ = number_format(floatval($row2['price_day'] * intval($_POST['banner_show_bought_time'])), 2, '.', '');
				//ROBOKASSA extend
				$Shp_c_extend_days = intval($_POST['banner_show_bought_time']);

				foreach($Pay->payments as $object) {
					$object->onForm($email, $out_summ, $inv_id, $inv_desc, $custom_params = array("Shp_c_extend_banner" => $Shp_c_extend_banner, "Shp_c_extend_days" => $Shp_c_extend_days, "Shp_c_module" => "banners", "Shp_c_payment" => $object->name));
				}

			} else {
				echo __("Error", "banners");
			}
		}

		/*
		 * Функция dashboard
		 */
		function onDashboard() {
			global $db;
			$row = $db->query_fetch_row("SELECT COUNT(id) AS c, SUM(paid_amount) AS a FROM ".TABLES_PREFIX."_banners_logs");
			return $row;
		}

		/*
		 * Функция оформления заказа
		 */
		function onCallBack($Shp_email, $out_summ, $inv_id, $payment, $my_crc, $custom_params = array()) {
			global $db, $images_url, $script_url, $settings;
			$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_banners_logs WHERE paid_md5 = '".$db->safe($my_crc)."'");
			if(!$row && !isset($custom_params['Shp_c_extend_banner'])) {
				$db->query("BEGIN");
					$db->query("UPDATE ".TABLES_PREFIX."_banners_in_work SET status = ".($settings['website_moderation'] == 'yes' ? 4 : 1)." WHERE id = ".intval($custom_params['Shp_c_banner']));
					$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_banners_in_work WHERE id = ".intval($custom_params['Shp_c_banner']));
					$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_banners_logs (banner_id, paid_md5, paid_time, order_id, paid_amount, paid_email, gateway, status)
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
				fxn_send($Shp_email, __("Banner added to rotation", "banners"), ($settings['website_moderation'] == 'yes' ? __("Banner paid, and will be shown after moderation.", "banners") : __("Banner paid, and will be shown after few minutes.", "banners"))."<br /><br />".__("Banner", "banners").": ".intval($row['id'])."<br /><br />".__("Banner title", "banners").": ".stripslashes($row['title'])."<br />".__("Target url", "banners").": ".$row['target_url']."<br />".__("Views", "banners").": ".($row['show_bought_count'] ? $row['show_bought_count'] : __("unlimited", "banners"))."<br />".__("Days", "banners").": ".($row['show_bought_time'] ? $row['show_bought_time'] : __("unlimited", "banners"))."<br />".__("Target page", "banners").": ".($row['no_blank'] == 0 ? __("_self", "banners") : __("_blank", "banners"))."<br />".__("Show on", "banners").": ".($row['cross_page_crosspage'] == 0 ? __("all pages", "banners") : __("selected page only", "banners") )."<br /><br />".__("Logs", "banners").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "banners")."</a><br /><br /><img src='".$script_url."uploads/banner_".$row['id'].".".$row['extension']."' border='0' />", $settings['website_email']);

				//Send email
				fxn_send($settings['website_email'], __("Banner added", "banners"), ($settings['website_moderation'] == 'yes' ? __("Banner paid, and waiting for moderation.", "banners")."<br /><br />".__("Do you", "banners").": <a href='".$script_url."modules/banners/accept.php?hash=".$my_crc."'>".__("accept", "banners")."</a> ".__("or", "banners")." <a href='".$script_url."modules/banners/decline.php?hash=".$my_crc."'>".__("decline", "banners")."</a> ?<br /><br />" :  "").__("Banner", "banners").": ".intval($row['id'])."<br /><br />".__("Amount", "banners").": ".floatval($out_summ)."<br /><br />".__("Banner title", "banners").": ".stripslashes($row['title'])."<br />".__("Target url", "banners").": ".$row['target_url']."<br />".__("Views", "banners").": ".($row['show_bought_count'] ? $row['show_bought_count'] : __("unlimited", "banners"))."<br />".__("Days", "banners").": ".($row['show_bought_time'] ? $row['show_bought_time'] : __("unlimited", "banners"))."<br />".__("Target page", "banners").": ".($row['no_blank'] == 0 ? __("_self", "banners") : __("_blank", "banners"))."<br />".__("Show on", "banners").": ".($row['cross_page_crosspage'] == 0 ? __("all pages", "banners") : __("selected page only", "banners") )."<br /><br />".__("Logs", "banners").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "banners")."</a><br /><br /><img src='".$script_url."uploads/banner_".$row['id'].".".$row['extension']."' border='0' />", $settings['website_email']);

				echo "ok";
			} else if(!$row && isset($custom_params['Shp_c_extend_banner'])) {
				if(isset($custom_params['Shp_c_extend_count'])){
					$db->query("UPDATE ".TABLES_PREFIX."_banners_in_work SET show_bought_count = show_bought_count + ".intval($custom_params['Shp_c_extend_count'] * 1000).", status = 1 WHERE id = ".intval($custom_params['Shp_c_extend_banner']));
				} else if(isset($custom_params['Shp_c_extend_days'])) {
					$db->query("UPDATE ".TABLES_PREFIX."_banners_in_work SET show_bought_time = show_bought_time + ".intval($custom_params['Shp_c_extend_days']).", status = 1 WHERE id = ".intval($custom_params['Shp_c_extend_banner']));
				}
				$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_banners_logs (banner_id, paid_md5, paid_time, order_id, paid_amount, paid_email, gateway, status)
					VALUES(
						".intval($custom_params['Shp_c_extend_banner']).",
						'".$db->safe($my_crc)."',
						".time().",
						".intval($inv_id).",
						".floatval($out_summ).",
						'".$db->safe(safe($Shp_email))."',
						'".$db->safe(safe($payment))."',
						1
				)");

				//Send email
				fxn_send($Shp_email, __("Banner extended", "banners"), __("Banner", "banners").": ".intval($custom_params['Shp_c_extend_banner'])."<br /><br />".__("Banner extended for", "banners").": ".(isset($custom_params['Shp_c_extend_count']) ? intval($custom_params['Shp_c_extend_count'] * 1000)." ".__("Views", "banners") : intval($custom_params['Shp_c_extend_days'])." ".__("Days", "banners"))."<br /><br />".__("Logs", "banners").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "banners")."</a>", $settings['website_email']);

				//Send email
				fxn_send($settings['website_email'], __("Banner extended", "banners"), __("Banner", "banners").": ".intval($custom_params['Shp_c_extend_banner'])."<br /><br />".__("Banner extended for", "banners").": ".(isset($custom_params['Shp_c_extend_count']) ? intval($custom_params['Shp_c_extend_count'] * 1000)." ".__("Views", "banners") : intval($custom_params['Shp_c_extend_days'])." ".__("Days", "banners"))."<br /><br />".__("Logs", "banners").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "banners")."</a>", $settings['website_email']);

			}
		}
	}

	if(isset($_SESSION['fxn_banner_admin']) && isset($_GET['banner_download_stat']) && $_GET['banner_download_stat'] == "csv") {
		// Get data
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_banners_in_work ORDER BY id DESC");

		$data = "ID,Start date,Place ID,Title,Page md5,Target Url,Clicks,Views,Ordered views,Ordered days,_blank,Shown,Image,Status\n";
		while($row = $db->fetch($query)) {
			$data .= $row['id'].",'".date("Y-m-d h:i:s", $row['show_start_time'])."',".$row['banner_id'].",'".$row['title']."','".$row['page_md5']."',".$row['target_url'].",".$row['clicks_current_count'].",".$row['show_current_count'].",".$row['show_bought_count'].",".$row['show_bought_time'].",".$row['no_blank'].",".$row['cross_page_crosspage'].",'banner_".$row['id'].".".$row['extension']."',".$row['status']."\n";
		}
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=banners.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		//Send
		die($data);
	}
	if(isset($_SESSION['fxn_banner_admin']) && isset($_GET['banner_download']) && $_GET['banner_download'] == "csv") {
		// Get data
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_banners_logs ORDER BY paid_time DESC");
		$data = "Time,Banner ID,md5,Order ID,Amount,Email,Gateway,Status\n";
		while($row = $db->fetch($query)) {
			$data .= "'".date("Y-m-d h:i:s", $row['paid_time'])."',".$row['banner_id'].",'".$row['paid_md5']."',".$row['order_id'].",".$row['paid_amount'].",'".$row['paid_email']."','".$row['gateway']."',".$row['status']."\n";
		}
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=banner_transaction.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		//Send
		die($data);
	}

?>
