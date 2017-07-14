<?php

	// Прямой доступ закрыт
	defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed.");

	/*
	 * Класс модуля Modules
	 */
	class CPayments {

		var $payments = array();

		/*
		 * Конструктор
		 */
		function __construct() {
			global $db;
			// Считываем все подключенные модули
			$result = $db->query("SELECT * FROM ".TABLES_PREFIX."_payments WHERE status = 1 ORDER BY ordering ASC");
			while($row = $db->fetch($result)) {
				// Подключаем модуль
				$name = $row['name'];
				$class = "C".ucfirst(str_replace("-", "", $name));
				if(file_exists(dirname(__FILE__)."/".$name."/init.php")) {
					require_once(dirname(__FILE__)."/".$name."/init.php");
					// Подключение
					$this->payments[$name] = new $class();
					$this->payments[$name]->params = $row;
				}
			}
		}


		/*
		 * Функция шлюзов
		 */
		function onPayments() {
			global $db, $images_url, $script_url;
			if(isset($_SESSION['fxn_banner_admin']) && isset($_POST['save_payments'])) {
				//Save payments
				$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_payments ORDER BY ordering");
				while($row = $db->fetch($query)) {
					$params = unserialize($row['params']);
					foreach($params as $key => $data) {
						$params[$key][1] = safe($_POST[$key]);
					}
					$params = serialize($params);
					$db->query("UPDATE ".TABLES_PREFIX."_payments SET params = '".$db->safe($params)."', status = ".(isset($_POST['status_'.$row['id']]) ? 1 : 0)." WHERE id = ".$row['id']);
				}
				$information = __("Payments configuration saved!");
			}
			?>
			<div id="information"><?php echo isset($information) ? $information : "";?></div>
			<form action="" method="post" >
				<br />
				<br />
				<table width="100%" id="param">
					<?php
						$tr = 1;
						$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_payments ORDER BY ordering");
						while($row = $db->fetch($query)) {
							$tr = 1 - $tr;
							?>
								<tr class='row_2'>
									<th colspan="2"><?php echo ucfirst($row['name']); ?></th>
								</tr>
								<tr class='row_<?php echo $tr;?>'>
									<td><?php echo __("Enabled"); ?></td>
									<td>
										<input type="checkbox" name="status_<?php echo $row['id']; ?>" <?php echo $row['status'] ? "checked='checked'" : "" ?> value="1" />
									</td>
								</tr>
							<?php
								$params = unserialize($row['params']);
								foreach($params as $key => $data):
									$tr = 1 - $tr;
							?>
								<tr class='row_<?php echo $tr;?>'>
									<td><?php echo __($data[0], $row['name']);?></td>
									<td>
										<input type="text" name="<?php echo $key; ?>" value="<?php echo $data[1]; ?>" />
									</td>
								</tr>
							<?php endforeach; ?>
					<?php
						}
					?>
					<tr>
						<td></td>
						<td><input type="submit" name="save_payments" value="<?php echo __("Save"); ?>" /></td>
					</tr>
				</table>
			</form>
			<?php
		}
	}

	//Объект
	$Pay = new CPayments();

?>
