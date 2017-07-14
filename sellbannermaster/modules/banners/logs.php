<?php defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed."); ?>
<?php
	if(isset($_POST['action']) && is_numeric($_POST['action'])) {
		$db->query("DELETE FROM ".TABLES_PREFIX."_banners_logs WHERE id = ".intval($_POST['action']));
		$information = __("Deleted!", "banners");
	}
	if(isset($_GET['clear']) && $_GET['clear'] == 'yes') {
		$db->query("DELETE FROM ".TABLES_PREFIX."_banners_logs WHERE 1");
		$information = __("Cleared!", "banners");
	}
	?>
	<div id="information"><?php echo isset($information) ? $information : "";?></div>
	<br />
	<div id="top_actions"><a href="?tab=logs&mod=banners&func=onLogs&banner_download=csv" target="_blank"><?php echo __("Download CSV", "banners"); ?></a> | <a href="?tab=logs&mod=banners&func=onLogs&clear=yes" onclick="return confirm('<?php echo __("Are you sure?", "banners"); ?>');"><font color="ff3333"><?php echo __("Clear ALL!!!", "banners"); ?></font></a></div>
	<br />
		<table width="100%" id="banner">
			<tr class='row_1'><th><?php echo __("Date", "banners"); ?></th><th><?php echo __("Banner ID", "banners"); ?></th><th><?php echo __("Order ID", "banners"); ?></th><th><?php echo __("Paid amount", "banners"); ?></th><th><?php echo __("Buyer email", "banners"); ?></th><th><?php echo __("Gateway", "banners"); ?></th><th><?php echo __("Delete", "banners"); ?></th></tr>
	<?php
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_banners_logs ORDER BY paid_time DESC LIMIT ".(isset($_GET['p']) ? intval($_GET['p'] * 15) : 0).",15");
		$row2 = $db->query_fetch_row("SELECT COUNT(id) AS c, SUM(paid_amount) AS a FROM ".TABLES_PREFIX."_banners_logs");
		$tr = 1;
		$transactions = $row2['c'];
		$amount = $row2['a'];
		while($row = $db->fetch($query)) {
			$tr = 1 - $tr;
	?>
		<tr class='row_<?php echo $tr;?>'>
			<td><?php echo date("Y-m-d h:i:s", $row['paid_time']); ?></td>
			<td><?php echo $row['banner_id']; ?></td>
			<td><?php echo $row['order_id']; ?></td>
			<td><?php echo $row['paid_amount']; ?></td>
			<td><?php echo $row['paid_email']; ?></td>
			<td><?php echo $row['gateway']; ?></td>
			<td>
				<form action='' method='post'>
					<input type='hidden' id='action<?php echo $row['id'];?>' name='action' value=''/>
					<input type='image' onmousedown='if(confirm("<?php echo __("Are you sure?", "banners"); ?>")) {  document.getElementById("action<?php echo $row['id'];?>").value = "<?php echo $row['id'];?>"; this.form.submit();}' src='<?php echo $images_url;?>/delete.png'/>
				</form>
			</td>
		</tr>
	<?php
		}
?>
	<tr class='row_2'>
		<td colspan="3"><b><?php echo __("Total earned", "banners"); ?>:</b></td>
		<td><b><?php echo floatval($amount); ?></b> <?php echo __("RUB", "banners"); ?></td>
		<td colspan="3"></td>
	</tr>
</table>
