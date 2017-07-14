<?php

	// Прямой доступ закрыт
	defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed.");

	/*
	 * Класс модуля Modules
	 */
	class CModules {

		var $modules = array();

		/*
		 * Конструктор
		 */
		function __construct() {
			global $db;
			// Считываем все подключенные модули
			$result = $db->query("SELECT * FROM ".TABLES_PREFIX."_modules WHERE status = 1 ORDER BY ordering DESC");
			while($row = $db->fetch($result)) {
				// Подключаем модуль
				$name = $row['name'];
				$class = "C".ucfirst($name);
				if(file_exists(dirname(__FILE__)."/".$name."/init.php")) {
					require_once(dirname(__FILE__)."/".$name."/init.php");
					// Подключение
					$this->modules[$name] = new $class();
				}
			}
		}

		/*
		 * Функция редактирования модулей
		 */
		function onModules() {
			global $db, $Pay;
			//Install uninstall
			if(isset($_POST['install']) && file_exists(dirname(__FILE__)."/".str_replace("..", "", $_POST['name'])."/init.php")) {
				$db->query("INSERT IGNORE INTO ".TABLES_PREFIX."_modules(name, status, ordering)
							VALUES('".$db->safe($_POST['name'])."', 1, 1)");
				require_once(dirname(__FILE__)."/".str_replace("..", "", $_POST['name'])."/init.php");
				$class = "C".ucfirst(safe($_POST['name']));
				$mod = new $class();
				$mod->onInstall();
				$_SESSION['information'] = $information = __("Module installed!");
				die('<META HTTP-EQUIV="refresh" CONTENT="0" />');
			} else if(isset($_POST['uninstall'])) {
				$this->modules[$_POST['name']]->onUnInstall();
				$db->query("DELETE FROM ".TABLES_PREFIX."_modules WHERE name = '".$db->safe($_POST['name'])."'");
				$_SESSION['information'] = $information = __("Module unInstalled!");
				die('<META HTTP-EQUIV="refresh" CONTENT="0" />');
			} else if(isset($_POST['installpayment']) && file_exists(dirname(__FILE__)."/../payments/".str_replace("..", "", $_POST['name'])."/init.php")) {
				require_once(dirname(__FILE__)."/../payments/".str_replace("..", "", $_POST['name'])."/init.php");
				$class = "C".ucfirst(safe(str_replace("-", "", $_POST['name'])));
				$mod = new $class();
				$mod->onInstall();
				$_SESSION['information'] = $information = __("Payment module installed!");
				die('<META HTTP-EQUIV="refresh" CONTENT="0" />');
			} else if(isset($_POST['uninstallpayment'])) {
				if(!isset($Pay->payments[$_POST['name']])) {
					$class = "C".ucfirst(str_replace("-", "", $_POST['name']));
					if(file_exists(dirname(__FILE__)."/../payments/".str_replace("..", "", $_POST['name'])."/init.php")) {
						require_once(dirname(__FILE__)."/../payments/".str_replace("..", "", $_POST['name'])."/init.php");
						// Подключение
						$pay = new $class();
						$pay->onUnInstall();
					}
				} else {
					$Pay->payments[$_POST['name']]->onUnInstall();
				}
				$_SESSION['information'] = $information = __("Payment module unInstalled!");
				die('<META HTTP-EQUIV="refresh" CONTENT="0" />');
			}

			// Загрузка списка установленных модулей
			$result = $db->query("SELECT * FROM ".TABLES_PREFIX."_modules ORDER BY status DESC, ordering DESC");
			$installed = array();
			if(isset($_SESSION['information'])) {
				$information = $_SESSION['information'];
				unset($_SESSION['information']);
			}
			?>
			<div id="information"><?php echo isset($information) ? $information : "";?></div>
			<br />
			<table id="param">
				<tr>
					<th><?php echo __("Modules");?></th>
					<th><?php echo __("Action");?></th>
				</tr>
			<?php
			$tr = 0;
			while($row = $db->fetch($result)) {
				$tr = 1 - $tr;
				$installed[$row['name']] = $row;
				?>
				<tr class="row_<?php echo $tr;?>">
					<td style="text-align: left;">
						<?php echo __(ucfirst($row['name']), $row['name']);?>
					</td>
					<td>
						<form action="" method="post">
							<input type="hidden" value="<?php echo $row['name'];?>" name="name" />
							<input style="width: 120px; padding: 1px 15px 1px 15px;" type="submit" value="<?php echo __("UnInstall");?>" name="uninstall" onclick="return confirm('<?php echo __("Are you sure?"); ?>');" />
						</form>
					</td>
				</tr>
				<?php
			}

			if($handle = opendir(dirname(__FILE__))) {
				while(false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && $file != "index.html" && $file != "modules.php") {
						$name = $file;
						if(!isset($installed[$name]) && is_dir(dirname(__FILE__)."/".$name) && file_exists(dirname(__FILE__)."/".$name."/init.php")) {
							$tr = 1 - $tr;
							?>
							<tr class="row_<?php echo $tr;?>">
								<td style="text-align: left;">
									<?php echo __(ucfirst($name), $name);?>
								</td>
								<td>
									<form action="" method="post">
										<input type="hidden" value="<?php echo $name;?>" name="name" />
										<input style="width: 120px; padding: 1px 15px 1px 15px;" type="submit" value="<?php echo __("Install");?>" name="install" />
									</form>
								</td>
							</tr>
							<?php
						}
					}
				}
				closedir($handle);
			}
			?>
				<tr>
					<th><?php echo __("Payment modules");?></th>
					<th><?php echo __("Action");?></th>
				</tr>
			<?php
			$result = $db->query("SELECT * FROM ".TABLES_PREFIX."_payments ORDER BY status DESC, ordering DESC");
			while($row = $db->fetch($result)) {
				$tr = 1 - $tr;
				$installed[$row['name']] = $row;
				?>
				<tr class="row_<?php echo $tr;?>">
					<td style="text-align: left;">
						<?php echo __(ucfirst($row['name']), $row['name']);?>
					</td>
					<td>
						<form action="" method="post">
							<input type="hidden" value="<?php echo $row['name'];?>" name="name" />
							<input style="width: 120px; padding: 1px 15px 1px 15px;" type="submit" value="<?php echo __("UnInstall");?>" name="uninstallpayment" onclick="return confirm('<?php echo __("Are you sure?"); ?>');" />
						</form>
					</td>
				</tr>
				<?php
			}
			if($handle = opendir(dirname(__FILE__)."/../payments")) {
				while(false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && $file != "index.html" && $file != "payments.php" && $file != "result.php" && $file != "success.php" && $file != "fail.php") {
						$name = $file;
						if(!isset($installed[$name]) && is_dir(dirname(__FILE__)."/../payments/".$name) && file_exists(dirname(__FILE__)."/../payments/".$name."/init.php")) {
							$tr = 1 - $tr;
							?>
							<tr class="row_<?php echo $tr;?>">
								<td style="text-align: left;">
									<?php echo __(ucfirst($name), $name);?>
								</td>
								<td>
									<form action="" method="post">
										<input type="hidden" value="<?php echo $name;?>" name="name" />
										<input style="width: 120px; padding: 1px 15px 1px 15px;" type="submit" value="<?php echo __("Install");?>" name="installpayment" />
									</form>
								</td>
							</tr>
							<?php
						}
					}
				}
				closedir($handle);
			}
			?>
			</table>
			<?php
		}

	}

	//Объект модулей
	$Mod = new CModules();

?>
