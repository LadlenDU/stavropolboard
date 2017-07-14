<?php

	// Прямой доступ закрыт
	defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed.");

	/*
	 * Класс модуля
	 */
	class CTextline {
		var $version = "0.1";
		var $name = "textline";

		/*
		 * Функция, вызываемая при инсталляции модуля
		 */
		function onInstall() {
			global $db;

			// Таблицы модуля
			$db->query("CREATE TABLE IF NOT EXISTS ".TABLES_PREFIX."_textline (
						id INT NOT NULL AUTO_INCREMENT,
						title VARCHAR(255) NOT NULL DEFAULT '',
						font_size VARCHAR(8) NOT NULL DEFAULT '24px',
						simbols INT NOT NULL DEFAULT 70,
						size_x INT NOT NULL DEFAULT -100,
						size_y INT NOT NULL DEFAULT 20,
						price_1000 FLOAT NOT NULL DEFAULT 60,
						price_no_blank FLOAT NOT NULL DEFAULT 10,
						status TINYINT NOT NULL DEFAULT 0,
						PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			$db->query("CREATE TABLE IF NOT EXISTS ".TABLES_PREFIX."_textline_in_work (
						id INT NOT NULL AUTO_INCREMENT,
						textline_id INT NOT NULL DEFAULT 0,
						title TEXT(4096) NOT NULL DEFAULT '',
						target_url TEXT(2048) NOT NULL DEFAULT '',
						page_md5 VARCHAR(32) NOT NULL DEFAULT '',
						clicks_current_count INT UNSIGNED NOT NULL DEFAULT 0,
						show_current_count INT UNSIGNED NOT NULL DEFAULT 0,
						show_start_time INT UNSIGNED NOT NULL DEFAULT 0,
						show_bought_count INT UNSIGNED NOT NULL DEFAULT 1000,
						no_blank TINYINT NOT NULL DEFAULT 0,
						owner_email VARCHAR(255) NOT NULL DEFAULT '',
						turn BIGINT NOT NULL DEFAULT 0,
						status TINYINT NOT NULL DEFAULT 0,
						PRIMARY KEY (id)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			$db->query("CREATE TABLE IF NOT EXISTS ".TABLES_PREFIX."_textline_logs (
						id INT NOT NULL AUTO_INCREMENT,
						textline_id INT NOT NULL DEFAULT 0,
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
			$db->query("DROP TABLE ".TABLES_PREFIX."_textline");
			$db->query("DROP TABLE ".TABLES_PREFIX."_textline_in_work");
			$db->query("DROP TABLE ".TABLES_PREFIX."_textline_logs");
		}

		/*
		 * Функция меню модуля
		 */
		function onMenu() {
			return array(
				"places" => array(__("Text line", "textline"), "onPlaces"),
				"control" => array(__("Text line", "textline"), "onControl"),
				"logs" => array(__("Text line", "textline"), "onLogs"),
			);
		}

		/*
		 * Функция мест под рекламу
		 */
		function onPlaces() {
			global $db, $images_url, $script_url;
			if(isset($_SESSION['fxn_banner_admin']) && (isset($_POST['add_textline_place']) || isset($_POST['edit_textline']))) {
				//Add textline place
				$error_info = array();
				if(!isset($_POST['textline_title']) || $_POST['textline_title'] == "") {
					$error_info[] = __("Title can't be empty", "textline");
				}
				if(!isset($_POST['font_size']) || $_POST['font_size'] == "") {
					$error_info[] = __("Font size can't be empty", "textline");
				}
				if(!isset($_POST['textline_simbols']) || $_POST['textline_simbols'] < 1) {
					$error_info[] = __("Wrong number of simbols", "textline");
				}
				if(!isset($_POST['textline_size_x']) || !isset($_POST['textline_size_y'])) {
					$error_info[] = __("Wrong text line size", "textline");
				}
				$textline_size_x = explode("%", $_POST['textline_size_x']);
				$_POST['textline_size_x'] = (isset($textline_size_x[1]) ? -1 : 1) * $textline_size_x[0];
				$textline_size_y = explode("%", $_POST['textline_size_y']);
				$_POST['textline_size_y'] = (isset($textline_size_y[1]) ? -1 : 1) * $textline_size_y[0];
				if(!isset($_POST['textline_price_1000']) || $_POST['textline_price_1000'] < 0) {
					$error_info[] = __("Wrong text line price for 1000 views", "textline");
				}
				if(!isset($_POST['textline_no_blank']) || $_POST['textline_no_blank'] == "") {
					$error_info[] = __("Wrong text line price for target=_blank", "textline");
				}
				if(empty($error_info)) {
					if(isset($_POST['add_textline_place'])) {
						//Add place
						$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_textline (title, font_size, simbols, size_x, size_y, price_1000, price_no_blank, status)
						VALUES(
							'".$db->safe(safe($_POST['textline_title']))."',
							'".$db->safe(safe($_POST['font_size']))."',
							".intval($_POST['textline_simbols']).",
							".intval($_POST['textline_size_x']).",
							".intval($_POST['textline_size_y']).",
							".floatval($_POST['textline_price_1000']).",
							".floatval($_POST['textline_no_blank']).",
							1
						)");
						$information = __("Text line place added!", "textline");
					} else if(isset($_POST['edit_textline']) && $_POST['edit_textline'] == 'save') {
						//Change place
						$db->query("UPDATE ".TABLES_PREFIX."_textline SET title = '".$db->safe(safe($_POST['textline_title']))."', font_size = '".$db->safe(safe($_POST['font_size']))."', simbols = ".intval($_POST['textline_simbols']).", size_x = ".intval($_POST['textline_size_x']).", size_y = ".intval($_POST['textline_size_y']).", price_1000 = ".floatval($_POST['textline_price_1000']).", price_no_blank = ".floatval($_POST['textline_no_blank']).", status = ".intval($_POST['textline_status'])." WHERE id = ".intval($_POST['textline_id']));
						$information = __("Text line place changed!", "textline");
					} else if(isset($_POST['edit_textline']) && $_POST['edit_textline'] == 'delete') {
						$db->query("DELETE FROM ".TABLES_PREFIX."_textline WHERE id = ".intval($_POST['textline_id']));
						$information = __("Text line place deleted!", "textline");
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
				unlink(dirname(__FILE__)."/../../uploads/icon_".intval($_POST['id']).".ico");
				$db->query("DELETE FROM ".TABLES_PREFIX."_textline_in_work WHERE id = ".intval($_POST['id']));
				$information = __("Text line deleted!", "textline");
			} else if(isset($_POST['action_stat']) && $_POST['action_stat'] == 'save') {
				$row4 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_textline_in_work WHERE id = ".intval($_POST['id']));
				$show_start_time = "";
				if($row4['status'] != 1 && $_POST['status'] == 1) {
					$show_start_time = ", show_start_time = ".time();
				}
				$db->query("UPDATE ".TABLES_PREFIX."_textline_in_work
				SET title = '".$db->safe(safe($_POST['title']))."', target_url = '".$db->safe(safe($_POST['target_url']))."', owner_email = '".$db->safe(safe($_POST['owner_email']))."', clicks_current_count = ".intval($_POST['clicks_current_count']).", show_current_count = ".intval($_POST['show_current_count']).", show_bought_count = ".intval($_POST['show_bought_count']).", no_blank = ".intval($_POST['no_blank']).", status = ".intval($_POST['status']).$show_start_time."
				WHERE id = ".intval($_POST['id']));

				//Send moderation email
				if($row4['status'] == 4 && $_POST['status'] == 1) {
					fxn_send(safe($_POST['owner_email']), __("Text line added to rotation", "textline"), __("Text line", "textline").": ".intval($row4['id']).", ".__("successful added to rotation!", "textline")."<br /><br />".__("Text line title", "textline").": ".$row4['title']."<br />".__("Target url", "textline").": ".$row4['target_url'], $settings['website_email']);
				} else if($row4['status'] == 4 && $_POST['status'] == 5) {
					fxn_send(safe($_POST['owner_email']), __("Text line declined", "textline"), __("Text line", "textline").": ".intval($row4['id']).", ".__("declined during moderation proccess!", "textline")."<br /><br />".__("Administration will contact you to resolving the issue during few days.", "textline")."<br /><br />".__("Text line title", "textline").": ".$row4['title']."<br />".__("Target url", "textline").": ".$row4['target_url'], $settings['website_email']);
				}

				$information = __("Text line changed!", "textline");
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
				$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_textline_in_work WHERE status = 1 AND id = ".intval($_POST['id']));
				$row2 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_textline WHERE id = ".intval($row['textline_id']));
				//Email to send link
				$email = safe($row['owner_email']);
				//ROBOKASSA order number
				$inv_id = time();
				//ROBOKASSA item specification
				$Shp_c_extend_textline = $row['id'];
				//Description
				$inv_desc = urldecode($row['title']);
			}
			if($row && $row['show_bought_count'] > 0 && isset($_POST['textline_show_bought_count']) && $_POST['textline_show_bought_count'] > 0) {
				//ROBOKASSA cost
				$out_summ = number_format(floatval($row2['price_1000'] * intval($_POST['textline_show_bought_count'])), 2, '.', '');
				//ROBOKASSA extend
				$Shp_c_extend_count = intval($_POST['textline_show_bought_count']);

				foreach($Pay->payments as $object) {
					$object->onForm($email, $out_summ, $inv_id, $inv_desc, $custom_params = array("Shp_c_extend_count" => $Shp_c_extend_count, "Shp_c_extend_textline" => $Shp_c_extend_textline, "Shp_c_module" => "textline", "Shp_c_payment" => $object->name));
				}
			} else {
				echo __("Error", "textline");
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
						<th colspan="5"><?php echo __("Text line", "textline"); ?></th>
					</tr>
					<tr class='row_1'>
						<th><?php echo __("Text line title", "textline"); ?></th>
						<th><?php echo __("Clicks", "textline"); ?></th>
						<th><?php echo __("Views", "textline"); ?></th>
						<th><?php echo __("Status", "textline"); ?></th>
						<th><?php echo __("Extend", "textline"); ?></th>
					</tr>
					<?php
						$tr = 1;
						$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_textline_in_work WHERE owner_email = '".$db->safe($_GET['email'])."' ORDER BY id DESC");
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
								<?php echo $row["show_current_count"]; ?> / <?php echo $row["show_bought_count"] > 0 ? $row["show_bought_count"] : __("unlimited", "textline"); ?>
							</td>
							<td width="90">
								<?php
									$statuses = array(__("In proccess", "textline"), __("In rotation", "textline"), __("Finished", "textline"), __("Rejected", "textline"), __("Moderation", "textline"), __("Declined", "textline"));
									echo $statuses[$row["status"]];
								?>
							</td>
							<td width="250">
								<?php if($settings['selling_opened'] == 'yes' && $row["status"] == 1): ?>
									<form action="" method="post" style="margin: 0px;">
										<input type="hidden" name="id" value="<?php echo $row["id"]; ?>" />
										<input type="hidden" name="module" value="textline" />
										<input type="number" class="mini" name="textline_show_bought_count" value="1" onkeyup="if($(this).val() <= 0) $(this).val(1); $('#bought_count_<?php echo $row["id"]; ?>').text($(this).val() * 1000);" onchange="if($(this).val() <= 0) $(this).val(1); $('#bought_count_<?php echo $row["id"]; ?>').text($(this).val() * 1000);" />
										<span id="bought_count_<?php echo $row["id"]; ?>">1000</span> <?php echo __("views", "textline"); ?>
										<input type="submit" name="extend" style="width: 80px; padding: 5px; float: right; font-size: 14px;" value="<?php echo __("Extend", "textline"); ?>" />
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
			$row = $db->query_fetch_row("SELECT COUNT(id) AS c, SUM(paid_amount) AS a FROM ".TABLES_PREFIX."_textline_logs");
			return $row;
		}

		/*
		 * Функция оформления заказа
		 */
		function onCallBack($Shp_email, $out_summ, $inv_id, $payment, $my_crc, $custom_params = array()) {
			global $db, $images_url, $script_url, $settings;
			$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_textline_logs WHERE paid_md5 = '".$db->safe($my_crc)."'");
			if(!$row && !isset($custom_params['Shp_c_extend_textline'])) {
				$db->query("BEGIN");
					$db->query("UPDATE ".TABLES_PREFIX."_textline_in_work SET status = ".($settings['website_moderation'] == 'yes' ? 4 : 1)." WHERE id = ".intval($custom_params['Shp_c_textline']));
					$row = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_textline_in_work WHERE id = ".intval($custom_params['Shp_c_textline']));
					$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_textline_logs (textline_id, paid_md5, paid_time, order_id, paid_amount, paid_email, gateway, status)
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
				fxn_send($Shp_email, __("Text line added to rotation", "textline"), ($settings['website_moderation'] == 'yes' ? __("Text line paid, and will be shown after moderation.", "textline") : __("Text line paid, and will be shown after few minutes.", "textline"))."<br /><br />".__("Text line", "textline").": ".intval($row['id'])."<br /><br />".__("Text line title", "textline").": ".stripslashes($row['title'])."<br />".__("Target url", "textline").": ".$row['target_url']."<br />".__("Views", "textline").": ".($row['show_bought_count'] ? $row['show_bought_count'] : __("unlimited", "textline"))."<br />".__("Target page", "textline").": ".($row['no_blank'] == 0 ? __("_self", "textline") : __("_blank", "textline"))."<br /><br />".__("Logs", "textline").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "textline")."</a>", $settings['website_email']);

				//Send email
				fxn_send($settings['website_email'], __("Text line added to rotation", "textline"), ($settings['website_moderation'] == 'yes' ? __("Text line paid, and waiting for moderation.", "textline")."<br /><br />".__("Do you", "textline").": <a href='".$script_url."modules/textline/accept.php?hash=".$my_crc."'>".__("accept", "textline")."</a> ".__("or", "textline")." <a href='".$script_url."modules/textline/decline.php?hash=".$my_crc."'>".__("decline", "textline")."</a> ?<br /><br />" :  "")."<br /><br />".__("Text line", "textline").": ".intval($row['id'])."<br /><br />".__("Amount", "textline").": ".floatval($out_summ)."<br /><br />".__("Text line title", "textline").": ".stripslashes($row['title'])."<br />".__("Target url", "textline").": ".$row['target_url']."<br />".__("Views", "textline").": ".($row['show_bought_count'] ? $row['show_bought_count'] : __("unlimited", "textline"))."<br />".__("Target page", "textline").": ".($row['no_blank'] == 0 ? __("_self", "textline") : __("_blank", "textline"))."<br /><br />".__("Logs", "textline").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "textline")."</a>", $settings['website_email']);

				echo "ok";
			} else if(!$row && isset($custom_params['Shp_c_extend_textline'])) {
				if(isset($custom_params['Shp_c_extend_count'])){
					$db->query("UPDATE ".TABLES_PREFIX."_textline_in_work SET show_bought_count = show_bought_count + ".intval($custom_params['Shp_c_extend_count'] * 1000).", status = 1 WHERE id = ".intval($custom_params['Shp_c_extend_textline']));
				}
				$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_textline_logs (textline_id, paid_md5, paid_time, order_id, paid_amount, paid_email, gateway, status)
					VALUES(
						".intval($custom_params['Shp_c_extend_textline']).",
						'".$db->safe($my_crc)."',
						".time().",
						".intval($inv_id).",
						".floatval($out_summ).",
						'".$db->safe(safe($Shp_email))."',
						'".$db->safe(safe($payment))."',
						1
				)");

				//Send email
				fxn_send($Shp_email, __("Text line extended", "textline"), __("Text line", "textline").": ".intval($custom_params['Shp_c_extend_textline'])."<br /><br />".__("Text line extended for", "textline").": ".intval($custom_params['Shp_c_extend_count'] * 1000)." ".__("Views", "textline")."<br /><br />".__("Logs", "textline").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "textline")."</a>", $settings['website_email']);

				//Send email
				fxn_send($settings['website_email'], __("Text line extended", "textline"), __("Text line", "textline").": ".intval($custom_params['Shp_c_extend_textline'])."<br /><br />".__("Text line extended for", "textline").": ".intval($custom_params['Shp_c_extend_count'] * 1000)." ".__("Views", "textline")."<br /><br />".__("Logs", "textline").": <a href='".$script_url."user_statistics.php?email=".$Shp_email."'>".__("here", "textline")."</a>", $settings['website_email']);
			}
		}
	}
	if(isset($_SESSION['fxn_banner_admin']) && isset($_GET['textline_download_stat']) && $_GET['textline_download_stat'] == "csv") {
		// Get data
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_textline_in_work ORDER BY id DESC");

		$data = "ID,Start date,Place ID,Title,Target Url,Clicks,Views,Ordered views,_blank,Image,Status\n";
		while($row = $db->fetch($query)) {
			$data .= $row['id'].",'".date("Y-m-d h:i:s", $row['show_start_time'])."',".$row['textline_id'].",'".$row['title']."',".$row['target_url'].",".$row['clicks_current_count'].",".$row['show_current_count'].",".$row['show_bought_count'].",".$row['no_blank'].",'icon_".$row['id'].".ico',".$row['status']."\n";
		}
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=textlines.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		//Send
		die($data);
	}
	if(isset($_SESSION['fxn_banner_admin']) && isset($_GET['textline_download']) && $_GET['textline_download'] == "csv") {
		// Get data
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_textline_logs ORDER BY paid_time DESC");
		$data = "Time,Text line ID,md5,Order ID,Amount,Email,Gateway,Status\n";
		while($row = $db->fetch($query)) {
			$data .= "'".date("Y-m-d h:i:s", $row['paid_time'])."',".$row['textline_id'].",'".$row['paid_md5']."',".$row['order_id'].",".$row['paid_amount'].",'".$row['paid_email']."','".$row['gateway']."',".$row['status']."\n";
		}
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=textline_transaction.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		//Send
		die($data);
	}


?>
