<?php defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed."); ?>
	<div id="information"><?php echo isset($information) ? $information : "";?></div>
	<br />
	<div id="top_actions"><a href="?tab=control&mod=ads&func=onControl&ads_download_stat=csv" target="_blank"><?php echo __("Download CSV", "ads"); ?></a></div>
	<br />
	<table width="100%">
		<tr class='row_1'>
			<th><a href="?tab=control&mod=ads&func=onControl&order=id"><?php echo __("ID", "ads"); ?></a></th>
			<th><a href="?tab=control&mod=ads&func=onControl&order=show_start_time"><?php echo __("Start date", "ads"); ?></a></th>
			<th><a href="?tab=control&mod=ads&func=onControl&order=ads_id"><?php echo __("Place ID", "ads"); ?></a></th>
			<th><a href="?tab=control&mod=ads&func=onControl&order=title"><?php echo __("Ad", "ads"); ?></a></th>
			<th><a href="?tab=control&mod=ads&func=onControl&order=target_url"><?php echo __("Target Url", "ads"); ?><br /><?php echo __("Email", "ads"); ?></a></th>
			<th><a href="?tab=control&mod=ads&func=onControl&order=show_current_count"><?php echo __("Views", "ads"); ?></a> <a href="?tab=control&mod=ads&func=onControl&order=clicks_current_count"><?php echo __("Clicks", "ads"); ?></a></th>
			<th><a href="?tab=control&mod=ads&func=onControl&order=no_blank"><?php echo __("_blank", "ads"); ?></a></th>
			<th><?php echo __("Icon", "ads"); ?></th>
			<th><a href="?tab=control&mod=ads&func=onControl&order=status"><?php echo __("Status", "ads"); ?></a></th>
			<th><?php echo __("Edit", "ads"); ?></th>
		</tr>
	<?php
		$ad = !isset($_SESSION['selladsmaster_ad']) ? "DESC" : $_SESSION['selladsmaster_ad'];
		$order_allowed = array("id", "show_start_time", "ads_id", "title", "target_url", "clicks_current_count", "show_current_count", "no_blank", "status");
		if(isset($_GET['order']) && isset($_SESSION['selladsmaster_order']) && $_GET['order'] == $_SESSION['selladsmaster_order']) {
			$_SESSION['selladsmaster_ad'] = $_SESSION['selladsmaster_ad'] == "DESC" ? "ASC" : "DESC";
		} else if(isset($_GET['order']) && in_array($_GET['order'], $order_allowed)) {
			$_SESSION['selladsmaster_order'] = $_GET['order'];
		} else {
			$_SESSION['selladsmaster_order'] = "id";
		}
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_ads_in_work ORDER BY ".$db->safe($_SESSION['selladsmaster_order'])." ".$ad." LIMIT ".(isset($_GET['p']) ? intval($_GET['p'] * 15) : 0).",15");
		$row2 = $db->query_fetch_row("SELECT COUNT(id) AS c FROM ".TABLES_PREFIX."_ads_in_work");
		$tr = 1;
		$transactions = $row2['c'];
		while($row = $db->fetch($query)) {
			$tr = 1 - $tr;
	?>
	<form action='' method='post'>
		<tr class='row_<?php echo $tr;?>'>
			<td><?php echo $row['id']; ?></td>
			<td class="little"><?php echo date("Y-m-d h:i:s", $row['show_start_time']); ?></td>
			<td><?php echo $row['ads_id']; ?></td>
			<td><input class="little medium" type="text" name="title" value="<?php echo $row['title']; ?>" /></td>
			<td><input class="little medium" type="text" name="target_url" value="<?php echo $row['target_url']; ?>" /><input class="little medium" type="text" name="owner_email" value="<?php echo $row['owner_email']; ?>" /></td>
			<td><input class="mini" type="text" name="show_current_count" value="<?php echo $row['show_current_count']; ?>" /><input class="mini" type="text" name="clicks_current_count" value="<?php echo $row['clicks_current_count']; ?>" /></td>
			<td>
				<select name="no_blank" class="mini little">
					<option value="0" <?php echo $row['no_blank'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("No", "ads");?></option>
					<option value="1" <?php echo $row['no_blank'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("Yes", "ads");?></option>
				</select>
			<td><img src='<?php echo $script_url;?>uploads/ad_<?php echo $row['id']; ?>.ico' border="0" alt="" /></td>
			<td>
				<select name="status" class="mini little">
					<option value="0" <?php echo $row['status'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("In proccess", "ads");?></option>
					<option value="1" <?php echo $row['status'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("In rotation", "ads");?></option>
					<option value="4" <?php echo $row['status'] == 4 ? "selected='selected'" : ""; ?>><?php echo __("Moderation", "ads");?></option>
					<option value="2" <?php echo $row['status'] == 2 ? "selected='selected'" : ""; ?>><?php echo __("Finished", "ads");?></option>
					<option value="3" <?php echo $row['status'] == 3 ? "selected='selected'" : ""; ?>><?php echo __("Rejected", "ads");?></option>
				</select>
			</td>
			<td width="60">
				<input type='hidden' id='id' name='id' value='<?php echo $row['id'];?>'/>
				<input type='hidden' id='action_stat<?php echo $row['id'];?>' name='action_stat' value=''/>
				<input type='image' onmousedown='if(confirm("<?php echo __("Are you sure?", "ads"); ?>")) {  document.getElementById("action_stat<?php echo $row['id'];?>").value = "delete"; this.form.submit();}' src='<?php echo $images_url;?>/delete.png'/>
				<input type='image' onmousedown='document.getElementById("action_stat<?php echo $row['id'];?>").value = "save"; this.form.submit();' src='<?php echo $images_url;?>/save.png'/>
			</td>
		</tr>
	</form>
	<?php
		}
?>
</table>
