<?php defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed."); ?>
	<div id="information"><?php echo isset($information) ? $information : "";?></div>
	<br />
	<div id="top_actions"><a href="?tab=control&mod=banners&func=onControl&banner_download_stat=csv" target="_blank"><?php echo __("Download CSV", "banners"); ?></a></div>
	<br />
	<table width="100%">
		<tr class='row_1'>
			<th><a href="?tab=control&mod=banners&func=onControl&order=id"><?php echo __("ID", "banners"); ?></a></th>
			<th><a href="?tab=control&mod=banners&func=onControl&order=show_start_time"><?php echo __("Start date", "banners"); ?></a></th>
			<th><a href="?tab=control&mod=banners&func=onControl&order=banner_id"><?php echo __("Place ID", "banners"); ?></a></th>
			<th><a href="?tab=control&mod=banners&func=onControl&order=title"><?php echo __("Title", "banners"); ?></a></th>
			<th><a href="?tab=control&mod=banners&func=onControl&order=target_url"><?php echo __("Target Url", "banners"); ?> <?php echo __("Email", "banners"); ?></a></th>
			<th><a href="?tab=control&mod=banners&func=onControl&order=show_current_count"><?php echo __("Views", "banners"); ?></a> <a href="?tab=control&mod=banners&func=onControl&order=clicks_current_count"><?php echo __("Clicks", "banners"); ?></a></th>
			<th><a href="?tab=control&mod=banners&func=onControl&order=show_bought_count"><?php echo __("Order views", "banners"); ?></a> <a href="?tab=control&mod=banners&func=onControl&order=show_bought_time"><?php echo __("Order days", "banners"); ?></a></th>
			<th><a href="?tab=control&mod=banners&func=onControl&order=no_blank"><?php echo __("_blank", "banners"); ?></a></th>
			<th><a href="?tab=control&mod=banners&func=onControl&order=cross_page_crosspage"><?php echo __("Shown", "banners"); ?></a></th>
			<th><?php echo __("Image", "banners"); ?></th>
			<th><a href="?tab=control&mod=banners&func=onControl&order=status"><?php echo __("Status", "banners"); ?></a></th>
			<th><?php echo __("Edit", "banners"); ?></th>
		</tr>
	<?php
		$ad = !isset($_SESSION['sellbannermaster_ad']) ? "DESC" : $_SESSION['sellbannermaster_ad'];
		$order_allowed = array("id", "show_start_time", "banner_id", "title", "target_url", "clicks_current_count", "show_current_count", "show_bought_count", "show_bought_time", "no_blank", "cross_page_crosspage", "status");
		if(isset($_GET['order']) && isset($_SESSION['sellbannermaster_order']) && $_GET['order'] == $_SESSION['sellbannermaster_order']) {
			$_SESSION['sellbannermaster_ad'] = $_SESSION['sellbannermaster_ad'] == "DESC" ? "ASC" : "DESC";
		} else if(isset($_GET['order']) && in_array($_GET['order'], $order_allowed)) {
			$_SESSION['sellbannermaster_order'] = $_GET['order'];
		} else {
			$_SESSION['sellbannermaster_order'] = "id";
		}
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_banners_in_work ORDER BY ".$db->safe($_SESSION['sellbannermaster_order'])." ".$ad." LIMIT ".(isset($_GET['p']) ? intval($_GET['p'] * 15) : 0).",15");
		$row2 = $db->query_fetch_row("SELECT COUNT(id) AS c FROM ".TABLES_PREFIX."_banners_in_work");
		$tr = 1;
		$transactions = $row2['c'];
		while($row = $db->fetch($query)) {
			$tr = 1 - $tr;
	?>
	<form action='' method='post' enctype="multipart/form-data">
		<tr class='row_<?php echo $tr;?>'>
			<td><?php echo $row['id']; ?></td>
			<td class="little"><?php echo date("Y-m-d h:i:s", $row['show_start_time']); ?></td>
			<td><?php echo $row['banner_id']; ?></td>
			<td><input class="small" type="text" name="title" value="<?php echo $row['title']; ?>" /></td>
			<td><input class="small" type="text" name="target_url" value="<?php echo $row['target_url']; ?>" /><input class="small" type="text" name="owner_email" value="<?php echo $row['owner_email']; ?>" /></td>
			<td><input class="mini" type="text" name="show_current_count" value="<?php echo $row['show_current_count']; ?>" /><input class="mini" type="text" name="clicks_current_count" value="<?php echo $row['clicks_current_count']; ?>" /></td>
			<td><input class="mini" type="text" name="show_bought_count" value="<?php echo $row['show_bought_count']; ?>" /><input class="mini" type="text" name="show_bought_time" value="<?php echo $row['show_bought_time']; ?>" /></td>
			<td>
				<select name="no_blank" class="mini little">
					<option value="0" <?php echo $row['no_blank'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("No", "banners");?></option>
					<option value="1" <?php echo $row['no_blank'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("Yes", "banners");?></option>
				</select>
			<td>
				<select name="cross_page_crosspage" class="mini little">
					<option value="0" <?php echo $row['cross_page_crosspage'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("All", "banners");?></option>
					<option value="1" <?php echo $row['cross_page_crosspage'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("Page", "banners");?></option>
				</select>
			</td>
			<td>
				<a href="<?php echo $script_url;?>uploads/banner_<?php echo $row['id']; ?>.<?php echo $row['extension']; ?>" target="_blank" ><img src='<?php echo $images_url;?>/image.png' border="0" alt="" /></a>
				<div class="file_upload_div"><input type="file" name="file_upload"></div>
			</td>
			<td>
				<select name="status" class="mini little">
					<option value="0" <?php echo $row['status'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("In proccess", "banners");?></option>
					<option value="1" <?php echo $row['status'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("In rotation", "banners");?></option>
					<option value="4" <?php echo $row['status'] == 4 ? "selected='selected'" : ""; ?>><?php echo __("Moderation", "banners");?></option>
					<option value="2" <?php echo $row['status'] == 2 ? "selected='selected'" : ""; ?>><?php echo __("Finished", "banners");?></option>
					<option value="3" <?php echo $row['status'] == 3 ? "selected='selected'" : ""; ?>><?php echo __("Rejected", "banners");?></option>
					<option value="5" <?php echo $row['status'] == 5 ? "selected='selected'" : ""; ?>><?php echo __("Declined", "banners");?></option>
				</select>
			</td>
			<td width="60">
				<input type='hidden' id='id' name='id' value='<?php echo $row['id'];?>'/>
				<input type='hidden' id='action_stat<?php echo $row['id'];?>' name='action_stat' value=''/>
				<input type='image' onmousedown='if(confirm("<?php echo __("Are you sure?", "banners"); ?>")) {  document.getElementById("action_stat<?php echo $row['id'];?>").value = "delete"; this.form.submit();}' src='<?php echo $images_url;?>/delete.png'/>
				<input type='image' onmousedown='document.getElementById("action_stat<?php echo $row['id'];?>").value = "save"; this.form.submit();' src='<?php echo $images_url;?>/save.png'/>
			</td>
		</tr>
	</form>
	<?php
		}
?>
</table>
