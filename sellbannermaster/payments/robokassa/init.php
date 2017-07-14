<?php

	// Прямой доступ закрыт
	defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed.");

	/*
	 * Класс модуля
	 */
	class CRobokassa {
		var $version = "0.1";
		var $name = "robokassa";
		var $params = array();

		/*
		 * Функция, вызываемая при инсталляции модуля
		 */
		function onInstall() {
			global $db;
			$db->query('INSERT IGNORE INTO '.TABLES_PREFIX.'_payments (name, params, status, ordering)
				VALUES
					(\'robokassa\', \'a:5:{s:9:"mrh_login";a:2:{i:0;s:15:"ROBOKASSA Login";i:1;s:0:"";}s:9:"mrh_pass1";a:2:{i:0;s:20:"ROBOKASSA Password 1";i:1;s:0:"";}s:9:"mrh_pass2";a:2:{i:0;s:20:"ROBOKASSA Password 2";i:1;s:0:"";}s:7:"culture";a:2:{i:0;s:18:"ROBOKASSA language";i:1;s:2:"ru";}s:7:"in_curr";a:2:{i:0;s:18:"ROBOKASSA currency";i:1;s:4:"WMRM";}}\', 0, 1)');
		}

		/*
		 * Функция, вызываемая при удалении модуля
		 */
		function onUnInstall() {
			global $db;
			// Удаляем таблицы
			$db->query("DELETE FROM ".TABLES_PREFIX."_payments WHERE name = 'robokassa'");
		}

		/*
		 * Функция палатежной формы
		 */
		function onFail() {
			global $db, $images_url, $settings, $buttom_copyright, $script_url;
			require_once(dirname(__FILE__)."/fail.php");
		}

		/*
		 * Функция палатежной формы
		 */
		function onSuccess() {
			global $db, $images_url, $settings, $buttom_copyright, $script_url;
			require_once(dirname(__FILE__)."/success.php");
		}

		/*
		 * Функция палатежной формы
		 */
		function onForm($email, $out_summ, $inv_id, $inv_desc, $custom_params = array()) {
			global $db, $images_url, $settings;
			//Get payment gateway params
			if($this->params['status']) {
				$params = unserialize($this->params['params']);
				foreach($params as $key => $data) {
					$$key = $data[1];
				}
				$cp = array();
				foreach($custom_params as $key => $data) {
					$cp[] = $key."=".$data;
				}
				//ROBOKASSA sign
				$crc  = md5($mrh_login.":".$out_summ.":".$inv_id.":".$mrh_pass1.(!empty($cp) ? ":".implode(":", $cp) : "").":Shp_email=".$email);
				?>
				<br />
				<img src="<?php echo $images_url;?>/robokassa.png" border="0" alt="robokassa" />
				<br />
				<script language=JavaScript src='https://merchant.roboxchange.com/Handler/MrchSumPreview.ashx?MrchLogin=<?php echo $mrh_login; ?>&OutSum=<?php echo $out_summ; ?>&InvId=<?php echo $inv_id; ?><?php echo (!empty($cp) ? "&".implode("&", $cp) : ""); ?>&Shp_email=<?php echo $email; ?>&IncCurrLabel=<?php echo $in_curr; ?>&Desc=<?php echo $inv_desc; ?>&SignatureValue=<?php echo $crc; ?>&Culture=<?php echo $culture; ?>&Encoding=UTF-8'></script>
				<?php
			}
		}

		/*
		 * Функция оформления заказа
		 */
		function onCallBack() {
			global $db, $images_url, $script_url, $settings, $Mod;

			//Get payment gateway params
			$row4 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_payments WHERE name = 'robokassa'");
			if(!$row4['status']) die("Error");

			$params = unserialize($row4['params']);
			foreach($params as $key => $data) {
				$$key = $data[1];
			}

			//Parameters
			$out_summ = $_REQUEST["OutSum"];
			$inv_id =  $_REQUEST["InvId"];
			$Shp_email = $_REQUEST["Shp_email"];
			$Shp_c_module = $_REQUEST["Shp_c_module"];
			$crc = $_REQUEST["SignatureValue"];

			$crc = strtolower($crc);

			$cp = array();
			$cp_str = array();
			foreach($_REQUEST as $name => $value) {
				if(preg_match("/^Shp_c_[a-zA-Z0-9]+/", $name)) {
					$cp[$name] = $value;
					$cp_str[] = ":".$name."=".$value;
				}
			}

			sort($cp_str);

			$my_crc = strtolower(md5("$out_summ:$inv_id:$mrh_pass2".implode("", $cp_str).":Shp_email=$Shp_email"));

			//Check sign
			if($my_crc != $crc) {
				die("bad sign\n");
			}

			if(isset($Mod->modules[$Shp_c_module])) {
				$Mod->modules[$Shp_c_module]->onCallBack($Shp_email, $out_summ, $inv_id, "robokassa", $my_crc, $cp);
			}

		}
	}
?>
