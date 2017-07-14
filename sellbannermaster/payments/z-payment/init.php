<?php

	// Прямой доступ закрыт
	defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed.");

	/*
	 * Класс модуля
	 */
	class CZpayment {
		var $version = "0.1";
		var $name = "z-payment";
		var $params = array();

		/*
		 * Функция, вызываемая при инсталляции модуля
		 */
		function onInstall() {
			global $db;
			$db->query('INSERT IGNORE INTO '.TABLES_PREFIX.'_payments (name, params, status, ordering)
				VALUES
					(\'z-payment\', \'a:4:{s:8:"IdShopZP";a:2:{i:0;s:12:"Z-Payment ID";i:1;s:0:"";}s:11:"SecretKeyZP";a:2:{i:0;s:20:"Z-Payment Secret Key";i:1;s:0:"";}s:9:"InitialZP";a:2:{i:0;s:21:"Z-Payment Initial Key";i:1;s:0:"";}s:12:"ResultMethod";a:2:{i:0;s:17:"Result URL method";i:1;s:4:"POST";}}\', 0, 2)');
		}

		/*
		 * Функция, вызываемая при удалении модуля
		 */
		function onUnInstall() {
			global $db;
			// Удаляем таблицы
			$db->query("DELETE FROM ".TABLES_PREFIX."_payments WHERE name = 'z-payment'");
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
			global $db, $images_url;
			if($this->params['status']) {
				$params = unserialize($this->params['params']);
				foreach($params as $key => $data) {
					$$key = $data[1];
				}
				//Z-Payment signs
				$crc_zp = md5($IdShopZP.$inv_id.$out_summ.$InitialZP);
				$crc_extend_zp = md5($IdShopZP.$inv_id.$out_summ.$InitialZP.$email.implode("", $custom_params));
				?>
				<br />
				<img src="<?php echo $images_url;?>/z-payment.jpg" border="0" alt="z-payment" />
				<br />
				<form name="pay" method="post" action="https://z-payment.com/merchant.php" target="_blank">
					<input name="LMI_PAYMENT_NO" type="hidden" value="<?php echo $inv_id; ?>" />
					<input name="LMI_PAYMENT_AMOUNT" type="hidden" value="<?php echo $out_summ; ?>" />
					<input name="CLIENT_MAIL" type="hidden" value="<?php echo $email; ?>" />
					<input name="BUYER_MAIL" type="hidden" value="<?php echo $email; ?>" />
					<input name="LMI_PAYMENT_DESC" type="hidden" value="<?php echo urldecode($inv_desc); ?>" />
					<?php
					foreach($custom_params as $key => $data) {
						?>
						<input name="<?php echo $key; ?>" type="hidden" value="<?php echo $data; ?>" />
						<?php
					}
					?>
					<input name="LMI_PAYEE_PURSE" type="hidden" value="<?php echo $IdShopZP; ?>" />
					<input name="ZP_SIGN" type="hidden" value="<?php echo $crc_zp; ?>">
					<input name="EXTENDED_SIGN" type="hidden" value="<?php echo $crc_extend_zp; ?>">
					<input type="submit" id="zpayment" value="Z-Payment" />
				</form>
				<?php
			}
		}

		/*
		 * Функция оформления заказа
		 */
		function onCallBack() {
			global $db, $images_url, $script_url, $settings, $Mod;

			//Get payment gateway params
			$row4 = $db->query_fetch_row("SELECT * FROM ".TABLES_PREFIX."_payments WHERE name = 'z-payment'");
			if(!$row4['status']) die("Error");

			$params = unserialize($row4['params']);
			foreach($params as $key => $data) {
				$$key = $data[1];
			}

			//Устанавливаем метод приема данных
			if($ResultMethod == 'POST') $HTTP = $_POST;
			else $HTTP = $_GET;
			$HTTP = $_REQUEST;
			//Преобразуем массив в переменные
			$cp = array();
			foreach($HTTP as $Key => $Value) {
				if(preg_match("/^Shp_c_[a-zA-Z0-9]+/", $Key)) {
					$cp[$Key] = $Value;
				}
				$$Key = $Value;
			}

			//Проверяем номер магазина
			if($LMI_PAYEE_PURSE != $IdShopZP) {
				die("ERR: Id магазина не соответсвует настройкам сайта!");
			}

			//Предварительный запрос на проведение платежа?
			if($LMI_PREREQUEST==1) {

				//Разрешаем оплату
				echo 'YES';

			} else {
				$legal = false;
				if(isset($LMI_SECRET_KEY)) {
					// Если ключ совпадает, занчит все ОК, проводим заказ
					if($LMI_SECRET_KEY==$SecretKeyZP) {
						$legal = true;
					} else {
						//Отмена заказа
						echo 'NO';
					}
				} else {
					// Ключ не был передан, требуется проверить контрольный хеш запроса
					//Расчет контрольного хеша из полученных переменных и Ключа мерчанта
					$CalcHash = md5($LMI_PAYEE_PURSE.number_format($LMI_PAYMENT_AMOUNT, 2, ".", "").$LMI_PAYMENT_NO.$LMI_MODE.$LMI_SYS_INVS_NO.$LMI_SYS_TRANS_NO.$LMI_SYS_TRANS_DATE.$SecretKeyZP.$LMI_PAYER_PURSE.$LMI_PAYER_WM);
					//Сравниваем значение расчетного хеша с полученным
					if($LMI_HASH == strtoupper($CalcHash)) {
						$legal = true;
					} else {
						//Отмена заказа
						echo 'NO';
					}
				}
				if($legal) {
					//Подтверждение оплаты заказа
					$my_crc = md5($IdShopZP.$LMI_PAYMENT_NO.$LMI_PAYMENT_AMOUNT.$SecretKeyZP);
					$my_extend_crc = md5($IdShopZP.$LMI_PAYMENT_NO.number_format($LMI_PAYMENT_AMOUNT, 2, ".", "").$InitialZP.$BUYER_MAIL.implode("", $cp));

					//Check sign
					if($my_extend_crc != $EXTENDED_SIGN) {
						die("bad sign\n");
					}

					if(isset($Mod->modules[$Shp_c_module])) {
						$Mod->modules[$Shp_c_module]->onCallBack($BUYER_MAIL, $LMI_PAYMENT_AMOUNT, $LMI_PAYMENT_NO, "z-payment", $my_extend_crc, $cp);
					}
					//Все прошло успешно
					if($Result) echo 'YES';
				}
			}

		}
	}

?>
