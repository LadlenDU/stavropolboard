<?php defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed."); ?>
<?php
	if(isset($_POST['action']) && is_numeric($_POST['action'])) {
		$db->query("DELETE FROM ".TABLES_PREFIX."_ads_logs WHERE id = ".intval($_POST['action']));
		$information = __("Deleted!", "ads");
	}
	if(isset($_GET['clear']) && $_GET['clear'] == 'yes') {
		$db->query("DELETE FROM ".TABLES_PREFIX."_ads_logs WHERE 1");
		$information = __("Cleared!", "ads");
	}
	?>
	<div id="information"><?php echo isset($information) ? $information : "";?></div>
	<br />
	<div id="top_actions"><a href="?tab=logs&mod=ads&func=onLogs&ads_download=csv" target="_blank"><?php echo __("Download CSV", "ads"); ?></a> | <a href="?tab=logs&mod=ads&func=onLogs&clear=yes" onclick="return confirm('<?php echo __("Are you sure?", "ads"); ?>');"><font color="ff3333"><?php echo __("Clear ALL!!!", "ads"); ?></font></a></div>
	<br />
		<table width="100%" id="banner">
			<tr class='row_1'><th><?php echo __("Date", "ads"); ?></th><th><?php echo __("Ad ID", "ads"); ?></th><th><?php echo __("Order ID", "ads"); ?></th><th><?php echo __("Paid amount", "ads"); ?></th><th><?php echo __("Buyer email", "ads"); ?></th><th><?php echo __("Gateway", "ads"); ?></th><th><?php echo __("Delete", "ads"); ?></th></tr>
	<?php
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_ads_logs ORDER BY paid_time DESC LIMIT ".(isset($_GET['p']) ? intval($_GET['p'] * 15) : 0).",15");
		$row2 = $db->query_fetch_row("SELECT COUNT(id) AS c, SUM(paid_amount) AS a FROM ".TABLES_PREFIX."_ads_logs");
		$tr = 1;
		$transactions = $row2['c'];
		$amount = $row2['a'];
		while($row = $db->fetch($query)) {
			$tr = 1 - $tr;
	?>
		<tr class='row_<?php echo $tr;?>'>
			<td><?php echo date("Y-m-d h:i:s", $row['paid_time']); ?></td>
			<td><?php echo $row['ads_id']; ?></td>
			<td><?php echo $row['order_id']; ?></td>
			<td><?php echo $row['paid_amount']; ?></td>
			<td><?php echo $row['paid_email']; ?></td>
			<td><?php echo $row['gateway']; ?></td>
			<td>
				<form action='' method='post'>
					<input type='hidden' id='action<?php echo $row['id'];?>' name='action' value=''/>
					<input type='image' onmousedown='if(confirm("<?php echo __("Are you sure?", "ads"); ?>")) {  document.getElementById("action<?php echo $row['id'];?>").value = "<?php echo $row['id'];?>"; this.form.submit();}' src='<?php echo $images_url;?>/delete.png'/>
				</form>
			</td>
		</tr>
	<?php
		}
?>
	<tr class='row_2'>
		<td colspan="3"><b><?php echo __("Total earned", "ads"); ?>:</b></td>
		<td><b><?php echo floatval($amount); ?></b> <?php echo __("RUB", "ads"); ?></td>
		<td colspan="3"></td>
	</tr>
</table>
