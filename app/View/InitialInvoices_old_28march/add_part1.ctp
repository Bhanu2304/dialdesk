
<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
	<?php $case=array('primary','success','info','warning','danger'); $i=0; ?>
		<?php $a=1; $To=0;?>
		<tbody>
			<tr class="active">
				<td>Sr. No.</td>
				<td>Particulars</td>
				<td>Qty</td>
				<td>Rate</td>
				<td>Amount</td>
			</tr>
			<?php  foreach ($inv_particulars as $post): ?>
			<tr class="<?php  echo $case[$i%4]; $i++;?>">
				<td><?php echo $a++; ?></td>
				<td><?php echo $post['AddInvParticular']['particulars']; ?></td>
				<td><?php echo $post['AddInvParticular']['qty']; ?></td>
				<td><?php echo $post['AddInvParticular']['rate']; ?></td>
				<td><?php echo $post['AddInvParticular']['amount']; ?></td>
				<?php  $To = $To+$post['AddInvParticular']['amount']; ?>
				<td></td>
			</tr>
			<?php endforeach; ?>
			<?php	echo $this->Form->input('par_total', array('label'=>false,'value'=>$To,'id'=>'par_total','type'=>'hidden')); ?>

			<?php unset($AddInvParticular); ?>
		</tbody>
</table>