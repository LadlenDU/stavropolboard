<?php defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed."); ?>
<div id="information"><?php echo isset($information) ? $information : "";?></div>
<br />
<br />
<form action="" method="post" >
	<table width="100%" id="param">
		<tr class='row_2'>
			<th colspan="2"><?php echo __("Add Teaser place", "teaser"); ?></th>
		</tr>
		<?php if(isset($error_info) && !empty($error_info)): ?>
			<td colspan="2"><font color="ff3333"><?php echo implode("<br />", $error_info); ?></font></td>
		<?php endif;?>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Teaser name", "teaser"); ?><span><?php echo __("Teaser place title", "teaser"); ?></span></span></td>
			<td><input class="large" type="text" name="teaser_title" value="<?php echo isset($_POST['teaser_title']) ? safe($_POST['teaser_title']) : __("Teaser", "teaser"); ?>" /></td>
		</tr>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Teaser font size", "teaser"); ?><span><?php echo __("Teaser font size in px, em etc.", "teaser"); ?></span></span></td>
			<td><input class="mini" type="text" name="font_size" value="<?php echo isset($_POST['font_size']) ? safe($_POST['font_size']) : "14px"; ?>" /></td>
		</tr>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Teaser text block place", "teaser"); ?><span><?php echo __("Place of teaser text block", "teaser"); ?></span></span></td>
			<td>
				<input type="radio" onclick="$('#text_block_1').show(); $('#text_block_0').hide();" name="text_place" value="1" <?php echo (!isset($_POST['text_place']) || $_POST['text_place'] == 1) ? "checked='checked'" : ""; ?> /> <?php echo __("Under image", "teaser"); ?>
				<input type="radio" onclick="$('#text_block_1').hide(); $('#text_block_0').show();" name="text_place" value="0" <?php echo isset($_POST['text_place']) && $_POST['text_place'] == 0 ? "checked='checked'" : ""; ?> /> <?php echo __("In right side of image", "teaser"); ?>
			</td>
		</tr>
		<tr class='row_0'>
			<td id="text_block_0" style="<?php echo (!isset($_POST['text_place']) || $_POST['text_place'] == 1) ? "display: none;" : ""; ?>"><span class="tooltip"><?php echo __("Teaser text block width", "teaser"); ?><span><?php echo __("Width of teaser text block in px", "teaser"); ?></span></span></td>
			<td id="text_block_1" style="<?php echo isset($_POST['text_place']) && $_POST['text_place'] == 0 ? "display: none;" : ""; ?>"><span class="tooltip"><?php echo __("Teaser text block height", "teaser"); ?><span><?php echo __("Height of teaser text block in px", "teaser"); ?></span></span></td>
			<td><input class="mini" type="text" name="text_block" value="<?php echo isset($_POST['text_block']) ? intval($_POST['text_block']) : "50"; ?>" /></td>
		</tr>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Teaser text length", "teaser"); ?><span><?php echo __("Teaser text max number of simbols", "teaser"); ?></span></span></td>
			<td><input class="mini" type="text" name="teaser_simbols" value="<?php echo isset($_POST['teaser_simbols']) ? safe($_POST['teaser_simbols']) : "70"; ?>" /></td>
		</tr>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Number of teasers to show", "teaser"); ?><span><?php echo __("Number of teasers shown in iframe block. Teasers will be in rotate.", "teaser"); ?></span></span></td>
			<td><input class="mini" type="text" name="teaser_number" value="<?php echo isset($_POST['teaser_number']) ? intval($_POST['teaser_number']) : "5"; ?>" /></td>
		</tr>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Teaser image size width, height", "teaser"); ?><span><?php echo __("Teaser image size (width, height) in px", "teaser"); ?></span></span></td>
			<td>
				<input class="mini" type="text" name="teaser_size_x" value="<?php echo isset($_POST['teaser_size_x']) ? abs($_POST['teaser_size_x']) : "100"; ?>" />
				<input class="mini" type="text" name="teaser_size_y" value="<?php echo isset($_POST['teaser_size_y']) ? abs($_POST['teaser_size_y']) : "100"; ?>" />
			</td>
		</tr>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Teaser max size in bytes", "teaser"); ?><span><?php echo __("Teaser max size in bytes", "teaser"); ?><br /><?php echo __("100.000 bytes = 97 Kb", "teaser"); ?></span></span></td>
			<td><input class="small" type="text" name="teaser_weight" value="<?php echo isset($_POST['teaser_weight']) ? intval($_POST['teaser_weight']) : "50000"; ?>" /></td>
		</tr>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Teaser price for 1000 views", "teaser"); ?><span><?php echo __("Teaser price for 1000 views", "teaser"); ?></span></span></td>
			<td><input class="small" type="text" name="teaser_price_1000" value="<?php echo isset($_POST['teaser_price_1000']) ? floatval($_POST['teaser_price_1000']) : "60"; ?>" /><?php echo __("RUB"); ?></td>
		</tr>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Teaser price for target=_self", "teaser"); ?><span><?php echo __("If 0 - not sell and open in new window, if -1 not sell and open in self window", "teaser"); ?></span></span></td>
			<td><input class="small" type="text" name="teaser_no_blank" value="<?php echo isset($_POST['teaser_no_blank']) ? floatval($_POST['teaser_no_blank']) : "10"; ?>" /><?php echo __("RUB"); ?></td>
		</tr>
		<tr class='row_1'>
			<td></td>
			<td><input type="submit" name="add_teaser_place" value="<?php echo __("Add Teaser place", "teaser"); ?>" /></td>
		</tr>
	</table>
</form>
<br />
<br />
<table width="100%" id="banners">
	<tr class='row_2'>
		<th colspan="11"><?php echo __("Teaser places", "teaser"); ?></th>
	</tr>
	<?php
		$tr = 1;
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_teaser ORDER BY id");
		while($row = $db->fetch($query)) {
		$tr = 1 - $tr;
	?>
		<tr class='row_1'>
			<th><span class="tooltip"><?php echo __("Teaser name", "teaser"); ?><span><?php echo __("Teaser place title", "teaser"); ?></span></span></th>
			<th><span class="tooltip"><?php echo __("Font size", "teaser"); ?><span><?php echo __("Teaser font size in px, em etc.", "teaser"); ?></span></span></th>
			<th><span class="tooltip"><?php if($row['text_place'] == 1):?><?php echo __("Text height", "teaser") ?><span><?php echo __("Height of teaser text block in px", "teaser"); ?></span></span><?php else: ?><?php echo __("Text width", "teaser"); ?><span><?php echo __("Width of teaser text block in px", "teaser"); ?></span></span><?php endif;?></th>
			<th><span class="tooltip"><?php echo __("Length", "teaser"); ?><span><?php echo __("Teaser max number of simbols", "teaser"); ?></span></span></th>
			<th><span class="tooltip"><?php echo __("Teasers number", "teaser"); ?><span><?php echo __("Number of teasers shown in iframe block. Teasers will be in rotate.", "teaser"); ?></span></span></th>
			<th><span class="tooltip"><?php echo __("Width", "teaser"); ?><span><?php echo __("Teaser image width in px", "teaser"); ?></span></span>
			<span class="tooltip"><?php echo __("Height", "teaser"); ?><span><?php echo __("Teaser image height in px", "teaser"); ?></span></span></th>
			<th><span class="tooltip"><?php echo __("Weight bytes", "teaser"); ?><span><?php echo __("Teaser max size in bytes", "teaser"); ?><br /><?php echo __("100.000 bytes = 97 Kb", "teaser"); ?></span></span></th>
			<th><span class="tooltip"><?php echo __("Price 1000", "teaser"); ?><span><?php echo __("Teaser price for 1000 views", "teaser"); ?></span></span></th>
			<th><span class="tooltip"><?php echo __("Price _self", "teaser"); ?><span><?php echo __("If 0 - not sell and open in new window, if -1 not sell and open in self window", "teaser"); ?></span></span></th>
			<th><?php echo __("Status", "teaser"); ?></th>
			<th><?php echo __("Edit", "teaser"); ?></th>
		</tr>
		<form action="" method="post" >
			<tr class='row_<?php echo $tr;?>'>
				<td><input class="large blue" type="text" name="teaser_title" value="<?php echo $row['title']; ?>" /></td>
				<td><input class="mini" type="text" name="font_size" value="<?php echo $row['font_size']; ?>" /></td>
				<td><input class="mini" type="text" name="text_block" value="<?php echo $row['text_block']; ?>" /></td>
				<td><input class="mini" type="text" name="teaser_simbols" value="<?php echo $row['simbols']; ?>" /></td>
				<td><input class="mini" type="text" name="teaser_number" value="<?php echo $row['teaser_number']; ?>" /></td>
				<td>
					<input class="mini" type="text" name="teaser_size_x" value="<?php echo $row['size_x']; ?>" />
					<input class="mini" type="text" name="teaser_size_y" value="<?php echo $row['size_y']; ?>" />
				</td>
				<td><input class="mini" type="text" name="teaser_weight" value="<?php echo $row['weight']; ?>" /></td>
				<td><input class="mini" type="text" name="teaser_price_1000" value="<?php echo $row['price_1000']; ?>" /></td>
				<td><input class="mini" type="text" name="teaser_no_blank" value="<?php echo $row['price_no_blank']; ?>" /></td>
				<td>
					<select name="teaser_status" class="mini">
						<option value="0" <?php echo $row['status'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("Off", "teaser");?></option>
						<option value="1" <?php echo $row['status'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("On", "teaser");?></option>
					</select>
				</td>
				<td width="70">
					<input type="hidden" name="teaser_id" value="<?php echo $row['id']; ?>" />
					<input type="hidden" id="edit_teaser_<?php echo $row['id']; ?>" name="edit_teaser" value="" />
					<input type="image" onmousedown="if(confirm('<?php echo __("Are you sure?", "teaser"); ?>')) { $('#edit_teaser_<?php echo $row['id']; ?>').val('delete'); this.form.submit();}" src="<?php echo $images_url; ?>/delete.png" />
					<input type="image" onmousedown="$('#edit_teaser_<?php echo $row['id']; ?>').val('save');" src="<?php echo $images_url; ?>/save.png" />
				</td>
			</tr>
			<tr class='row_<?php echo $tr;?>'>
				<td>
					<?php echo __("Place ID", "teaser"); ?>: <?php echo $row["id"]; ?>
					<br />
					<?php echo __("Copy and past the code to your template:", "teaser"); ?>
				</td>
				<td colspan="10">
					<?php if($row['text_place'] == 1):?>
						<?php echo __("Vertical", "teaser"); ?>
						<textarea cols="96" readonly onclick="this.select();"><iframe width="<?php echo ($row["size_x"] + 30); ?>" height="<?php echo (($row["size_y"] + $row['text_block'] + 20) * $row['teaser_number'] + 80); ?>" src="<?php echo $script_url."modules/teaser/teaser.php?id=".$row["id"]; ?>" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></textarea>
						<?php echo __("Horisontal", "teaser"); ?>
						<textarea cols="96" readonly onclick="this.select();"><iframe width="<?php echo (($row["size_y"] + 30) * $row['teaser_number']); ?>" height="<?php echo ($row["size_x"] + $row['text_block'] + 20 + 40); ?>" src="<?php echo $script_url."modules/teaser/teaser.php?id=".$row["id"]; ?>" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></textarea></td>
					<?php else: ?>
						<?php echo __("Vertical", "teaser"); ?>
						<textarea cols="96" readonly onclick="this.select();"><iframe width="<?php echo ($row["size_y"] + $row['text_block'] + 34); ?>" height="<?php echo (($row["size_x"] + 20) * $row['teaser_number'] + 30); ?>" src="<?php echo $script_url."modules/teaser/teaser.php?id=".$row["id"]; ?>" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></textarea>
						<?php echo __("Horisontal", "teaser"); ?>
						<textarea cols="96" readonly onclick="this.select();"><iframe width="<?php echo (($row["size_x"] + $row['text_block'] + 34) * $row['teaser_number']); ?>" height="<?php echo ($row["size_y"] + 45); ?>" src="<?php echo $script_url."modules/teaser/teaser.php?id=".$row["id"]; ?>" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></textarea></td>
					<?php endif; ?>
			</tr>
			<tr class='row_3'>
				<td colspan="11"></td>
			</tr>
		</form>
	<?php
		}
	?>
</table>

