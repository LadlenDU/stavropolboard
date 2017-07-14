<?php defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed."); ?>
	<div id="information"><?php echo isset($information) ? $information : "";?></div>
	<br />
	<div id="top_actions"><a href="?tab=control&mod=textline&func=onControl&textline_download_stat=csv" target="_blank"><?php echo __("Download CSV", "textline"); ?></a></div>
	<br />
	<table width="100%">
		<tr class='row_1'>
			<th><a href="?tab=control&mod=textline&func=onControl&order=id"><?php echo __("ID", "textline"); ?></a></th>
			<th><a href="?tab=control&mod=textline&func=onControl&order=show_start_time"><?php echo __("Start date", "textline"); ?></a></th>
			<th><a href="?tab=control&mod=textline&func=onControl&order=textline_id"><?php echo __("Place ID", "textline"); ?></a></th>
			<th><a href="?tab=control&mod=textline&func=onControl&order=title"><?php echo __("Text line", "textline"); ?></a></th>
			<th><a href="?tab=control&mod=textline&func=onControl&order=target_url"><?php echo __("Target Url", "textline"); ?><br /><?php echo __("Email", "textline"); ?></a></th>
			<th><a href="?tab=control&mod=textline&func=onControl&order=show_current_count"><?php echo __("Views", "textline"); ?></a> <a href="?tab=control&mod=textline&func=onControl&order=clicks_current_count"><?php echo __("Clicks", "textline"); ?></a></th>
			<th><a href="?tab=control&mod=textline&func=onControl&order=show_bought_count"><?php echo __("Order views", "textline"); ?></a></th>
			<th><a href="?tab=control&mod=textline&func=onControl&order=no_blank"><?php echo __("_blank", "textline"); ?></a></th>
			<th><?php echo __("Icon", "textline"); ?></th>
			<th><a href="?tab=control&mod=textline&func=onControl&order=status"><?php echo __("Status", "textline"); ?></a></th>
			<th><?php echo __("Edit", "textline"); ?></th>
		</tr>
	<?php
		$ad = !isset($_SESSION['selltextlinemaster_ad']) ? "DESC" : $_SESSION['selltextlinemaster_ad'];
		$order_allowed = array("id", "show_start_time", "textline_id", "title", "target_url", "clicks_current_count", "show_current_count", "show_bought_count", "no_blank", "status");
		if(isset($_GET['order']) && isset($_SESSION['selltextlinemaster_order']) && $_GET['order'] == $_SESSION['selltextlinemaster_order']) {
			$_SESSION['selltextlinemaster_ad'] = $_SESSION['selltextlinemaster_ad'] == "DESC" ? "ASC" : "DESC";
		} else if(isset($_GET['order']) && in_array($_GET['order'], $order_allowed)) {
			$_SESSION['selltextlinemaster_order'] = $_GET['order'];
		} else {
			$_SESSION['selltextlinemaster_order'] = "id";
		}
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_textline_in_work ORDER BY ".$db->safe($_SESSION['selltextlinemaster_order'])." ".$ad." LIMIT ".(isset($_GET['p']) ? intval($_GET['p'] * 15) : 0).",15");
		$row2 = $db->query_fetch_row("SELECT COUNT(id) AS c FROM ".TABLES_PREFIX."_textline_in_work");
		$tr = 1;
		$transactions = $row2['c'];
		while($row = $db->fetch($query)) {
			$tr = 1 - $tr;
	?>
	<form action='' method='post'>
		<tr class='row_<?php echo $tr;?>'>
			<td><?php echo $row['id']; ?></td>
			<td class="little"><?php echo date("Y-m-d h:i:s", $row['show_start_time']); ?></td>
			<td><?php echo $row['textline_id']; ?></td>
			<td><input class="little medium" type="text" name="title" value="<?php echo $row['title']; ?>" /></td>
			<td><input class="little small" type="text" name="target_url" value="<?php echo $row['target_url']; ?>" /><input class="little small" type="text" name="owner_email" value="<?php echo $row['owner_email']; ?>" /></td>
			<td><input class="mini" type="text" name="show_current_count" value="<?php echo $row['show_current_count']; ?>" /><input class="mini" type="text" name="clicks_current_count" value="<?php echo $row['clicks_current_count']; ?>" /></td>
			<td><input class="mini" type="text" name="show_bought_count" value="<?php echo $row['show_bought_count']; ?>" /></td>
			<td>
				<select name="no_blank" class="mini little">
					<option value="0" <?php echo $row['no_blank'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("No", "textline");?></option>
					<option value="1" <?php echo $row['no_blank'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("Yes", "textline");?></option>
				</select>
			<td><img src='<?php echo $script_url;?>uploads/icon_<?php echo $row['id']; ?>.ico' border="0" alt="" /></td>
			<td>
				<select name="status" class="mini little">
					<option value="0" <?php echo $row['status'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("In proccess", "textline");?></option>
					<option value="1" <?php echo $row['status'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("In rotation", "textline");?></option>
					<option value="4" <?php echo $row['status'] == 4 ? "selected='selected'" : ""; ?>><?php echo __("Moderation", "textline");?></option>
					<option value="2" <?php echo $row['status'] == 2 ? "selected='selected'" : ""; ?>><?php echo __("Finished", "textline");?></option>
					<option value="3" <?php echo $row['status'] == 3 ? "selected='selected'" : ""; ?>><?php echo __("Rejected", "textline");?></option>
				</select>
			</td>
			<td width="60">
				<input type='hidden' id='id' name='id' value='<?php echo $row['id'];?>'/>
				<input type='hidden' id='action_stat<?php echo $row['id'];?>' name='action_stat' value=''/>
				<input type='image' onmousedown='if(confirm("<?php echo __("Are you sure?", "textline"); ?>")) {  document.getElementById("action_stat<?php echo $row['id'];?>").value = "delete"; this.form.submit();}' src='<?php echo $images_url;?>/delete.png'/>
				<input type='image' onmousedown='document.getElementById("action_stat<?php echo $row['id'];?>").value = "save"; this.form.submit();' src='<?php echo $images_url;?>/save.png'/>
			</td>
		</tr>
	</form>
	<?php
		}
?>
</table>
