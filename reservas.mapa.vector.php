<div>
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<?php 
		for ($i = 1; $i <= 31; $i++) {
		?>
		<td style="width: 20px;">
			<table border=2 cellspacing=0 cellpadding=0>
				<tr>
					<td colspan="2">&nbsp;&nbsp;<?php echo $i; ?>&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr>

					<?php if (($i>=3+1&&$i<11+1) || ($i>=14+1&&$i<21+1)){?>
					<td bgcolor="blue">&nbsp;&nbsp;</td>
					<?php }else{?>
					<td>&nbsp;&nbsp;</td>
					<?php };?>

					<?php if (($i>=3&&$i<11) || ($i>=14&&$i<21)){?>
					<td bgcolor="blue">&nbsp;&nbsp;</td>
					<?php }else{?>
					<td>&nbsp;&nbsp;</td>
					<?php };?>

				</tr>
			</table>
		</td>
		<?php 
		}
		?>
	</tr>
</table>

</div>