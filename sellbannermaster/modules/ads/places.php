<?php defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed."); ?>
<div id="information"><?php echo isset($information) ? $information : "";?></div>
<br />
<br />
<form action="" method="post" >
	<table width="100%" id="param">
		<tr class='row_2'>
			<th colspan="2"><?php echo __("Add Ad place", "ads"); ?></th>
		</tr>
		<?php if(isset($error_info) && !empty($error_info)): ?>
			<td colspan="2"><font color="ff3333"><?php echo implode("<br />", $error_info); ?></font></td>
		<?php endif;?>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Ad name", "ads"); ?><span><?php echo __("Ad place title", "ads"); ?></span></span></td>
			<td><input class="large" type="text" name="ads_title" value="<?php echo isset($_POST['ads_title']) ? safe($_POST['ads_title']) : "Ad"; ?>" /></td>
		</tr>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Ad font size", "ads"); ?><span><?php echo __("Ad font size in px, em etc.", "ads"); ?></span></span></td>
			<td><input class="large" type="text" name="font_size" value="<?php echo isset($_POST['font_size']) ? safe($_POST['font_size']) : "24px"; ?>" /></td>
		</tr>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Ads number", "ads"); ?><span><?php echo __("Max number of ads in this block", "ads"); ?></span></span></td>
			<td><input class="mini" type="text" name="ads_number" value="<?php echo isset($_POST['ads_number']) ? safe($_POST['ads_number']) : "5"; ?>" /></td>
		</tr>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Ad length", "ads"); ?><span><?php echo __("Max number of simbols for ad", "ads"); ?></span></span></td>
			<td><input class="mini" type="text" name="ads_simbols" value="<?php echo isset($_POST['ads_simbols']) ? safe($_POST['ads_simbols']) : "70"; ?>" /></td>
		</tr>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Ad size width, height", "ads"); ?><span><?php echo __("Ad block size (width, height) in pixels", "ads"); ?></span></span></td>
			<td>
				<input class="mini" type="text" name="ads_size_x" value="<?php echo isset($_POST['ads_size_x']) ? abs($_POST['ads_size_x']).($_POST['ads_size_x'] < 0 ? "%" : "") : "300"; ?>" />
				<input class="mini" type="text" name="ads_size_y" value="<?php echo isset($_POST['ads_size_y']) ? abs($_POST['ads_size_y']).($_POST['ads_size_y'] < 0 ? "%" : "") : "300"; ?>" />
			</td>
		</tr>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Ad price", "ads"); ?><span><?php echo __("Price for ads in this block", "ads"); ?></span></span></td>
			<td><input class="small" type="text" name="ads_price_ad" value="<?php echo isset($_POST['ads_price_ad']) ? floatval($_POST['ads_price_ad']) : "60"; ?>" /><?php echo __("RUB"); ?></td>
		</tr>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Ad price for target=_self", "ads"); ?><span><?php echo __("If 0 - not sell and open in new window, if -1 not sell and open in self window", "ads"); ?></span></span></td>
			<td><input class="small" type="text" name="ads_no_blank" value="<?php echo isset($_POST['ads_no_blank']) ? floatval($_POST['ads_no_blank']) : "10"; ?>" /><?php echo __("RUB"); ?></td>
		</tr>
		<tr class='row_0'>
			<td></td>
			<td><input type="submit" name="add_ads_place" value="<?php echo __("Add Ad place", "ads"); ?>" /></td>
		</tr>
	</table>
</form>
<br />
<br />
<table width="100%" id="banners">
	<tr class='row_2'>
		<th colspan="10"><?php echo __("Ad places", "ads"); ?></th>
	</tr>
	<tr class='row_1'>
		<th><span class="tooltip"><?php echo __("Ad name", "ads"); ?><span><?php echo __("Ad place title", "ads"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Font size", "ads"); ?><span><?php echo __("Ad font size in px, em etc.", "ads"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Number", "ads"); ?><span><?php echo __("Max number of ads in this block", "ads"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Length", "ads"); ?><span><?php echo __("Max number of simbols for ad", "ads"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Width", "ads"); ?><span><?php echo __("Ad block width in pixels", "ads"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Height", "ads"); ?><span><?php echo __("Ad block height in pixels", "ads"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Price", "ads"); ?><span><?php echo __("Price for ads in this block", "ads"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Price _self", "ads"); ?><span><?php echo __("If 0 - not sell and open in new window, if -1 not sell and open in self window", "ads"); ?></span></span></th>
		<th><?php echo __("Status", "ads"); ?></th>
		<th><?php echo __("Edit", "ads"); ?></th>
	</tr>
	<?php
		$tr = 1;
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_ads ORDER BY id");
		while($row = $db->fetch($query)) {
		$tr = 1 - $tr;
	?>
		<form action="" method="post" >
			<tr class='row_<?php echo $tr;?>'>
				<td><input class="large blue" type="text" name="ads_title" value="<?php echo $row['title']; ?>" /></td>
				<td><input class="mini" type="text" name="font_size" value="<?php echo $row['font_size']; ?>" /></td>
				<td><input class="mini" type="text" name="ads_number" value="<?php echo $row['ads_number']; ?>" /></td>
				<td><input class="mini" type="text" name="ads_simbols" value="<?php echo $row['simbols']; ?>" /></td>
				<td><input class="mini" type="text" name="ads_size_x" value="<?php echo $row['size_x'] > 0 ? $row['size_x'] : abs($row['size_x'])."%"; ?>" /></td>
				<td><input class="mini" type="text" name="ads_size_y" value="<?php echo $row['size_y'] > 0 ? $row['size_y'] : abs($row['size_y'])."%"; ?>" /></td>
				<td><input class="mini" type="text" name="ads_price_ad" value="<?php echo $row['price_ad']; ?>" /></td>
				<td><input class="mini" type="text" name="ads_no_blank" value="<?php echo $row['price_no_blank']; ?>" /></td>
				<td>
					<select name="ads_status" class="mini">
						<option value="0" <?php echo $row['status'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("Off", "ads");?></option>
						<option value="1" <?php echo $row['status'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("On", "ads");?></option>
					</select>
				</td>
				<td width="70">
					<input type="hidden" name="ads_id" value="<?php echo $row['id']; ?>" />
					<input type="hidden" id="edit_ads_<?php echo $row['id']; ?>" name="edit_ads" value="" />
					<input type="image" onmousedown="if(confirm('<?php echo __("Are you sure?", "ads"); ?>')) { $('#edit_ads_<?php echo $row['id']; ?>').val('delete'); this.form.submit();}" src="<?php echo $images_url; ?>/delete.png" />
					<input type="image" onmousedown="$('#edit_ads_<?php echo $row['id']; ?>').val('save');" src="<?php echo $images_url; ?>/save.png" />
				</td>
			</tr>
			<tr class='row_<?php echo $tr;?>'>
				<td>
					<?php echo __("Place ID", "ads"); ?>: <?php echo $row["id"]; ?>
					<br />
					<?php echo __("Copy and past the code to your template:", "ads"); ?>
				</td>
				<td colspan="9"><textarea cols="96" readonly onclick="this.select();"><iframe width="<?php echo abs($row["size_x"]).($row["size_x"] < 0 ? "%" : ""); ?>" height="<?php echo abs($row["size_y"]).($row["size_y"] < 0 ? "%" : ""); ?>" src="<?php echo $script_url."modules/ads/ads.php?id=".$row["id"]; ?>" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></textarea></td>
			</tr>
			<tr class='row_3'>
				<td colspan="10"></td>
			</tr>
		</form>
	<?php
		}
	?>
</table>

