<?php
	//Access point
	define("_BOARD_VALID_", 1);

	//Settings
	require_once(dirname(__FILE__)."/../settings.php");

	//Parameters
	$Shp_c_payment = $_REQUEST["Shp_c_payment"];

	if(isset($Pay->payments[$Shp_c_payment])) {
		$Pay->payments[$Shp_c_payment]->onCallBack();
	}
?>
