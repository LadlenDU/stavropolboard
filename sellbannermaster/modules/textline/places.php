<?php defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed."); ?>
<div id="information"><?php echo isset($information) ? $information : "";?></div>
<br />
<br />
<form action="" method="post" >
	<table width="100%" id="param">
		<tr class='row_2'>
			<th colspan="2"><?php echo __("Add Text line place", "textline"); ?></th>
		</tr>
		<?php if(isset($error_info) && !empty($error_info)): ?>
			<td colspan="2"><font color="ff3333"><?php echo implode("<br />", $error_info); ?></font></td>
		<?php endif;?>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Text line name", "textline"); ?><span><?php echo __("Text line place title", "textline"); ?></span></span></td>
			<td><input class="large" type="text" name="textline_title" value="<?php echo isset($_POST['textline_title']) ? safe($_POST['textline_title']) : __("Text line", "textline"); ?>" /></td>
		</tr>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Text line font size", "textline"); ?><span><?php echo __("Text line font size in px, em etc.", "textline"); ?></span></span></td>
			<td><input class="mini" type="text" name="font_size" value="<?php echo isset($_POST['font_size']) ? safe($_POST['font_size']) : "24px"; ?>" /></td>
		</tr>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Text line length", "textline"); ?><span><?php echo __("Text line max number of simbols", "textline"); ?></span></span></td>
			<td><input class="mini" type="text" name="textline_simbols" value="<?php echo isset($_POST['textline_simbols']) ? safe($_POST['textline_simbols']) : "70"; ?>" /></td>
		</tr>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Text line size width, height", "textline"); ?><span><?php echo __("Text line size (width, height) in px or %", "textline"); ?></span></span></td>
			<td>
				<input class="mini" type="text" name="textline_size_x" value="<?php echo isset($_POST['textline_size_x']) ? abs($_POST['textline_size_x']).($_POST['textline_size_x'] < 0 ? "%" : "") : "100%"; ?>" />
				<input class="mini" type="text" name="textline_size_y" value="<?php echo isset($_POST['textline_size_y']) ? abs($_POST['textline_size_y']).($_POST['textline_size_y'] < 0 ? "%" : "") : "40"; ?>" />
			</td>
		</tr>
		<tr class='row_0'>
			<td><span class="tooltip"><?php echo __("Text line price for 1000 views", "textline"); ?><span><?php echo __("Text line price for 1000 views", "textline"); ?></span></span></td>
			<td><input class="small" type="text" name="textline_price_1000" value="<?php echo isset($_POST['textline_price_1000']) ? floatval($_POST['textline_price_1000']) : "60"; ?>" /><?php echo __("RUB"); ?></td>
		</tr>
		<tr class='row_1'>
			<td><span class="tooltip"><?php echo __("Text line price for target=_self", "textline"); ?><span><?php echo __("If 0 - not sell and open in new window, if -1 not sell and open in self window", "textline"); ?></span></span></td>
			<td><input class="small" type="text" name="textline_no_blank" value="<?php echo isset($_POST['textline_no_blank']) ? floatval($_POST['textline_no_blank']) : "10"; ?>" /><?php echo __("RUB"); ?></td>
		</tr>
		<tr class='row_0'>
			<td></td>
			<td><input type="submit" name="add_textline_place" value="<?php echo __("Add Text line place", "textline"); ?>" /></td>
		</tr>
	</table>
</form>
<br />
<br />
<table width="100%" id="banners">
	<tr class='row_2'>
		<th colspan="9"><?php echo __("Text line places", "textline"); ?></th>
	</tr>
	<tr class='row_1'>
		<th><span class="tooltip"><?php echo __("Text line title", "textline"); ?><span><?php echo __("Text line place title", "textline"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Font size", "textline"); ?><span><?php echo __("Text line font size in px, em etc.", "textline"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Length", "textline"); ?><span><?php echo __("Text line max number of simbols", "textline"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Width", "textline"); ?><span><?php echo __("Text line width in px or %", "textline"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Height", "textline"); ?><span><?php echo __("Text line height in px or %", "textline"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Price 1000", "textline"); ?><span><?php echo __("Text line price for 1000 views", "textline"); ?></span></span></th>
		<th><span class="tooltip"><?php echo __("Price _self", "textline"); ?><span><?php echo __("If 0 - not sell and open in new window, if -1 not sell and open in self window", "textline"); ?></span></span></th>
		<th><?php echo __("Status", "textline"); ?></th>
		<th><?php echo __("Edit", "textline"); ?></th>
	</tr>
	<?php
		$tr = 1;
		$query = $db->query("SELECT * FROM ".TABLES_PREFIX."_textline ORDER BY id");
		while($row = $db->fetch($query)) {
		$tr = 1 - $tr;
	?>
		<form action="" method="post" >
			<tr class='row_<?php echo $tr;?>'>
				<td><input class="large blue" type="text" name="textline_title" value="<?php echo $row['title']; ?>" /></td>
				<td><input class="mini" type="text" name="font_size" value="<?php echo $row['font_size']; ?>" /></td>
				<td><input class="mini" type="text" name="textline_simbols" value="<?php echo $row['simbols']; ?>" /></td>
				<td><input class="mini" type="text" name="textline_size_x" value="<?php echo $row['size_x'] > 0 ? $row['size_x'] : abs($row['size_x'])."%"; ?>" /></td>
				<td><input class="mini" type="text" name="textline_size_y" value="<?php echo $row['size_y'] > 0 ? $row['size_y'] : abs($row['size_y'])."%"; ?>" /></td>
				<td><input class="mini" type="text" name="textline_price_1000" value="<?php echo $row['price_1000']; ?>" /></td>
				<td><input class="mini" type="text" name="textline_no_blank" value="<?php echo $row['price_no_blank']; ?>" /></td>
				<td>
					<select name="textline_status" class="mini">
						<option value="0" <?php echo $row['status'] == 0 ? "selected='selected'" : ""; ?>><?php echo __("Off", "textline");?></option>
						<option value="1" <?php echo $row['status'] == 1 ? "selected='selected'" : ""; ?>><?php echo __("On", "textline");?></option>
					</select>
				</td>
				<td width="70">
					<input type="hidden" name="textline_id" value="<?php echo $row['id']; ?>" />
					<input type="hidden" id="edit_textline_<?php echo $row['id']; ?>" name="edit_textline" value="" />
					<input type="image" onmousedown="if(confirm('<?php echo __("Are you sure?", "textline"); ?>')) { $('#edit_textline_<?php echo $row['id']; ?>').val('delete'); this.form.submit();}" src="<?php echo $images_url; ?>/delete.png" />
					<input type="image" onmousedown="$('#edit_textline_<?php echo $row['id']; ?>').val('save');" src="<?php echo $images_url; ?>/save.png" />
				</td>
			</tr>
			<tr class='row_<?php echo $tr;?>'>
				<td>
					<?php echo __("Place ID", "textline"); ?>: <?php echo $row["id"]; ?>
					<br />
					<?php echo __("Copy and past the code to your template:", "textline"); ?>
				</td>
				<td colspan="8"><textarea cols="96" readonly onclick="this.select();"><iframe width="<?php echo abs($row["size_x"]).($row["size_x"] < 0 ? "%" : ""); ?>" height="<?php echo abs($row["size_y"]).($row["size_y"] < 0 ? "%" : ""); ?>" src="<?php echo $script_url."modules/textline/textline.php?id=".$row["id"]; ?>" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></textarea></td>
			</tr>
			<tr class='row_3'>
				<td colspan="9"></td>
			</tr>
		</form>
	<?php
		}
	?>
</table>

