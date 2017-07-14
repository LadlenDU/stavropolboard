<?php defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed."); ?>
<div id="information"><?php echo isset($information) ? $information : "";?></div>
<br />
<br />
<form action="" method="post" >
	<table width="100%" id="param">
		<tr class='row_2'>
			<th colspan="2"><?php echo __("Add banner place", "banners"); ?></th>
		</tr>
		<?php if(isset($error_info) && !empty($error_info)): ?>
			<td colspan="2"><font color="ff3333"><?php echo implode("<br />", $error_info); ?></font></td>
		<?php endif;?>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Place title", "banners"); ?><span><?php echo __("Banner place title", "banners"); ?></span></span></td>
			<td><input class="large" type="text" name="banner_title" value="<?php echo isset($_POST['banner_title']) ? safe($_POST['banner_title']) : "Banner"; ?>" /></td>
		</tr>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Banner size width, height", "banners"); ?><span><?php echo __("Banner size (width, height) in pixels", "banners"); ?></span></span></td>
			<td>
				<input class="mini" type="text" name="banner_size_x" value="<?php echo isset($_POST['banner_size_x']) ? intval($_POST['banner_size_x']) : "100"; ?>" />
				<input class="mini" type="text" name="banner_size_y" value="<?php echo isset($_POST['banner_size_y']) ? intval($_POST['banner_size_y']) : "100"; ?>" />
			</td>
		</tr>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Banner max size in bytes", "banners"); ?><span><?php echo __("Banner max size in bytes", "banners"); ?><br /><?php echo __("100.000 bytes = 97 Kb", "banners"); ?></span></span></td>
			<td><input class="small" type="text" name="banner_weight" value="<?php echo isset($_POST['banner_weight']) ? intval($_POST['banner_weight']) : "100000"; ?>" /></td>
		</tr>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Banner price for 1000 views, if 0 will not sell", "banners"); ?><span><?php echo __("Banner price for 1000 views, if banner will sells only by days set 0", "banners"); ?></span></span></td>
			<td><input class="small" type="text" name="banner_price_1000" value="<?php echo isset($_POST['banner_price_1000']) ? floatval($_POST['banner_price_1000']) : "60"; ?>" /><?php echo __("RUB"); ?></td>
		</tr>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Banner price for day, if 0 will not sell", "banners"); ?><span><?php echo __("Banner price for day, if banner will sells only by views set 0", "banners"); ?></span></span></td>
			<td><input class="small" type="text" name="banner_price_day" value="<?php echo isset($_POST['banner_price_day']) ? floatval($_POST['banner_price_day']) : "50"; ?>" /><?php echo __("RUB"); ?></td>
		</tr>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Banner price for target=_self", "banners"); ?><span><?php echo __("If 0 - not sell and open in new window, if -1 not sell and open in self window", "banners"); ?></span></span></td>
			<td><input class="small" type="text" name="banner_no_blank" value="<?php echo isset($_POST['banner_no_blank']) ? floatval($_POST['banner_no_blank']) : "10"; ?>" /><?php echo __("RUB"); ?></td>
		</tr>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Selling variant", "banners"); ?><span><?php echo __("Choice: All for cross pages, Page for one page only, Or user can choice by himself", "banners"); ?></span></span></td>
			<td>
				<select name="banner_cross_page_crosspage" class="mini">
					<option value="0" <?php echo isset($_POST['banner_cross_page_crosspage']) && $_POST['banner_cross_page_crosspage'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("All", "banners");?></option>
					<option value="1" <?php echo isset($_POST['banner_cross_page_crosspage']) && $_POST['banner_cross_page_crosspage'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("Page", "banners");?></option>
					<option value="2" <?php echo !isset($_POST['banner_cross_page_crosspage']) || $_POST['banner_cross_page_crosspage'] == 2 ? "selected='selected'" : ""; ?>><?php echo __("User choice", "banners");?></option>
				</select>
			</td>
		</tr>
		<tr class='row_1'>
			<td></td>
			<td><input type="submit" name="add_banner_place" value="<?php echo __("Add Banner place", "banners"); ?>" /></td>
		</tr>
	</table>
</form>
<br />
<br />
<table width="100%" id="banners">
	<tr class='row_2'>
		<th colspan="10"><?php echo __("Banners places", "banners"); ?></th>
	</tr>
	<tr class='row_1'>
		<th><span class="tooltip"><?php echo __("Banner title", "banners"); ?><span><?php echo __("Banner place title", "banners"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Width", "banners"); ?><span><?php echo __("Banner width in pixels", "banners"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Height", "banners"); ?><span><?php echo __("Banner height in pixels", "banners"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Weight bytes", "banners"); ?><span><?php echo __("Banner max size in bytes", "banners"); ?><br /><?php echo __("100.000 bytes = 97 Kb", "banners"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Price 1000", "banners"); ?><span><?php echo __("Banner price for 1000 views, if banner will sells only by days set 0", "banners"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Price Day", "banners"); ?><span><?php echo __("Banner price for day, if banner will sells only by views set 0", "banners"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Price _self", "banners"); ?><span><?php echo __("If 0 - not sell and open in new window, if -1 not sell and open in self window", "banners"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Cross type", "banners"); ?><span><?php echo __("Choice: All for cross pages, Page for one page only, Or user can choice by himself", "banners"); ?></span></span></th>
		<th><?php echo __("Status", "banners"); ?></th>
		<th><?php echo __("Edit", "banners"); ?></th>
	</tr>
	<?php
		$tr = 1;
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_banners ORDER BY id");
		while($row = $db->fetch($query)) {
		$tr = 1 - $tr;
	?>
		<form action="" method="post" >
			<tr class='row_<?php echo $tr;?>'>
				<td><input class="large blue" type="text" name="banner_title" value="<?php echo $row['title']; ?>" /></td>
				<td><input class="mini" type="text" name="banner_size_x" value="<?php echo $row['size_x']; ?>" /></td>
				<td><input class="mini" type="text" name="banner_size_y" value="<?php echo $row['size_y']; ?>" /></td>
				<td><input class="mini" type="text" name="banner_weight" value="<?php echo $row['weight']; ?>" /></td>
				<td><input class="mini" type="text" name="banner_price_1000" value="<?php echo $row['price_1000']; ?>" /></td>
				<td><input class="mini" type="text" name="banner_price_day" value="<?php echo $row['price_day']; ?>" /></td>
				<td><input class="mini" type="text" name="banner_no_blank" value="<?php echo $row['price_no_blank']; ?>" /></td>
				<td>
					<select name="banner_cross_page_crosspage" class="mini">
						<option value="0" <?php echo $row['cross_page_crosspage'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("All", "banners");?></option>
						<option value="1" <?php echo $row['cross_page_crosspage'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("Page", "banners");?></option>
						<option value="2" <?php echo $row['cross_page_crosspage'] == 2 ? "selected='selected'" : ""; ?>><?php echo __("User choice", "banners");?></option>
					</select>
				</td>
				<td>
					<select name="banner_status" class="mini">
						<option value="0" <?php echo $row['status'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("Off", "banners");?></option>
						<option value="1" <?php echo $row['status'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("On", "banners");?></option>
					</select>
				</td>
				<td width="70">
					<input type="hidden" name="banner_id" value="<?php echo $row['id']; ?>" />
					<input type="hidden" id="edit_banner_<?php echo $row['id']; ?>" name="edit_banner" value="" />
					<input type="image" onmousedown="if(confirm('<?php echo __("Are you sure?", "banners"); ?>')) { $('#edit_banner_<?php echo $row['id']; ?>').val('delete'); this.form.submit();}" src="<?php echo $images_url; ?>/delete.png" />
					<input type="image" onmousedown="$('#edit_banner_<?php echo $row['id']; ?>').val('save');" src="<?php echo $images_url; ?>/save.png" />
				</td>
			</tr>
			<tr class='row_<?php echo $tr;?>'>
				<td>
					<?php echo __("Place ID", "banners"); ?>: <?php echo $row["id"]; ?>
					<br />
					<?php echo __("Copy and past the code to your template:", "banners"); ?>
				</td>
				<td colspan="9"><textarea cols="96" readonly onclick="this.select();"><iframe width="<?php echo $row["size_x"]; ?>" height="<?php echo $row["size_y"]; ?>" src="<?php echo $script_url."modules/banners/banner.php?id=".$row["id"]; ?>" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></textarea></td>
			</tr>
			<tr class='row_3'>
				<td colspan="10"></td>
			</tr>
		</form>
	<?php
		}
	?>
</table>

