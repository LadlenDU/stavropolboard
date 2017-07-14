<?php defined("_BOARD_VALID_") or die("Direct Access to this location is not allowed."); ?>
<form action="" method="post" >
	<br />
	<br />
	<table width="100%" id="param">
		<tr>
			<th><?php echo __("Module name"); ?></th>
			<th><?php echo __("Sales"); ?></th>
			<th><?php echo __("Amount"); ?></th>
		</tr>
		<?php
			$mod_stat = array();
			$count_stat = 0;
			$amount_stat = 0;
			$tr = 1;
			foreach($Mod->modules as $name => $mod) {
				if(method_exists($mod, "onDashboard")) {
					$tr = 1 - $tr;
					$mod_stat[$name] = $mod->onDashboard();
					?>
						<tr class="row_<?php echo $tr;?>">
							<td>
								<?php echo __(ucfirst($name), $mod->name);?>
							</td>
							<td style="text-align: center;">
								<?php echo intval($mod_stat[$name]['c']);?>
							</td>
							<td style="text-align: center;">
								<?php echo floatval($mod_stat[$name]['a']);?> <?php echo __("RUB");?>
							</td>
						</tr>
					<?php
					$count_stat += $mod_stat[$name]['c'];
					$amount_stat += $mod_stat[$name]['a'];
				}
			}
		?>
		<tr class="row_2">
			<td>
				<b><?php echo __("Total earned");?></b>
			</td>
			<td style="text-align: center;">
				<b><?php echo $count_stat;?></b>
			</td>
			<td style="text-align: center;">
				<b><?php echo $amount_stat;?></b> <?php echo __("RUB");?>
			</td>
		</tr>
	</table>
</form>
