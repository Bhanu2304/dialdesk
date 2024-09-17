<?php //print_r($tbl_invoice); ?>
<?php echo $this->Form->create('InitialInvoice',array('class'=>'form-horizontal','action'=>'move_approve_proforma')); ?>
<?php  foreach ($tbl_invoice as $post): ?>
<?php $data=$post; ?>
<?php endforeach; ?><?php unset($InitialInvoice); ?>

<?php  foreach ($cost_master as $post): ?>
<?php $dataX=$post; ?>
<?php endforeach; ?><?php unset($CostCenterMaster); ?>
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
		</ol>
		
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					
					<span>Approve Proforma Invoice</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content">
					<!---	creating hide array for particulars table and hidden fields -->
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                    <tr align="center">
                                        <td>Branch</td>
                                        <td>Cost Center</td>
                                        <td>Financial Year</td>
                                        <td>Month for</td>
                                        <?php if(strtotime($data['InitialInvoice']['invoiceDate'])>strtotime("2017-06-30")) { ?>
                                        <td>GST No.</td>
                                        <td>Client GST No.</td>
                                        <?php } ?>
                                    </tr>
                                    <tr align="center">
                                        <td class="info"><?php echo $data['branch_name'];  ?></td>
                                        <td class="danger"><?php echo $data['cost_center']; ?></td>
                                        <td class="info"><?php echo $data['finance_year'];?></td>
                                        <td class="danger"><?php echo $data['month'];?></td>
                                        <?php if(strtotime($data['InitialInvoice']['invoiceDate'])>strtotime("2017-06-30")) { ?>
                                        <td class="info"><?php echo $cost_master['CostCenterMaster']['ServiceTaxNo'];?>
                                        </td>
                                        <td class="danger"><?php echo $cost_master['CostCenterMaster']['VendorGSTNo'];?>
                                        </td>
                                        <?php } ?>
                                    </tr>
				</table>

					</div>
					</div>
				</div>
			</div>
<div class="row">
	<div class="col-xs-8 col-sm-4">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<span>Bill To</span>
				</div>
				
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
                                    <tbody>
                                        <tr><th><?php echo $dataX['client'];?>&nbsp;</th></tr>
                                    <tr><th><?php echo $dataX['bill_to'];?>&nbsp;</th></tr>
                                    <tr><th><?php echo $dataX['b_Address1'];?>&nbsp;</th></tr>
                                    <tr><th><?php echo $dataX['b_Address2'];?>&nbsp;</th></tr>
                                    <tr><th><?php echo $dataX['b_Address3'];?>&nbsp;</th></tr>
                                    <tr><th><?php echo $dataX['b_Address4'];?>&nbsp;</th></tr>
                                    <tr><th><?php echo $dataX['b_Address5'];?>&nbsp;</th></tr>
                                    </tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-xs-8 col-sm-4">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<span>Ship To</span>
				</div>
				
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">
				
				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
					<tbody>
						<tr><th><?php echo $dataX['client'];?>&nbsp;</th></tr>
						<tr><th><?php echo $dataX['ship_to'];?>&nbsp;</th></tr>
						<tr><th><?php echo $dataX['a_address1'];?>&nbsp;</th></tr>
						<tr><th><?php echo $dataX['a_address2'];?>&nbsp;</th></tr>
						<tr><th><?php echo $dataX['a_address3'];?>&nbsp;</th></tr>
						<tr><th><?php echo $dataX['a_address4'];?>&nbsp;</th></tr>
						<tr><th><?php echo $dataX['a_address5'];?>&nbsp;</th></tr>						
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-xs-8 col-sm-4">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
                                    <span>Details</span>
				</div>
				
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">

				<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
					<tbody>
						<tr><th>Bill Date</th><td><?php $date = date_create($data['invoiceDate']);
						echo date_format($date,"d-M-Y");?></td></tr>
						<tr><th>Proforma No.</th><td><?php echo $data['proforma_bill_no'];?></td></tr>
						<tr><th>JCC No.</th><td><?php echo $data['jcc_no'];?></td></tr>
						<tr><th>PO No.</th><td><?php echo $data['po_no'];?></td></tr>
						<tr><th>GRN</th><td><?php echo $data['grn'];?></td></tr>
                                                <tr><th>&nbsp;</th><td>&nbsp;</td></tr>
                                                <tr><th>&nbsp;</th><td>&nbsp;</td></tr>
					</tbody>
				</table>

				</div>
		   </div>
	</div>
</div>
			
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<span>Bill Details</span>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">
			<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('primary','success','info','danger'); $i=0; ?>
					<tbody>
						<tr>
							<th> S.No. </th>
							<th> Particulars </th>
							<th> Rate </th>
							<th> Qty. </th>
							<th> Amount </th>
						</tr>
						<?php  foreach ($inv_particulars as $post): ?>
							<tr class="<?php  echo $case[$i%3]; $i++;?>">
							<td><?php echo $i;?></td>
							<td><?php echo $post['Particular']['particulars'];?></td>
							<td><?php echo $post['Particular']['rate'];?></td>
							<td><?php echo $post['Particular']['qty'];?></td>
							<td><?php echo $post['Particular']['amount'];?></td>
							</tr>
						<?php endforeach; ?><?php unset($Particular); ?>
						</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php if(!empty($inv_deduct_particulars)) { ?>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<span>Deduction</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">
			<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('primary','success','info','danger'); $i=0; ?>
					<tbody>
						<tr>
							<th> S.No. </th>
							<th> Particulars </th>
							<th> Rate </th>
							<th> Qty. </th>
							<th> Amount </th>
						</tr>
						<?php  foreach ($inv_deduct_particulars as $post): ?>
							<tr class="<?php  echo $case[$i%3]; $i++;?>">
							<td><?php echo $i;?></td>
							<td><?php echo $post['DeductParticular']['particulars'];?></td>
							<td><?php echo $post['DeductParticular']['rate'];?></td>
							<td><?php echo $post['DeductParticular']['qty'];?></td>
							<td><?php echo $post['DeductParticular']['amount'];?></td>
							</tr>
						<?php endforeach; ?><?php unset($DeductParticular); ?>
						</tbody>
				</table>

				<div id="nn"></div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			
			<div class="box-content no-padding">
				<table class="table table-striped">
						<thead><tr> <th>Total:</th><td><?php echo $data['total'];?></td></tr></thead>
                                                  <?php  if($data['GSTType']=='Integrated' && $data['apply_gst']=='1') { ?>
                                                   <thead><tr> <th>IGST @ 18.00%</th>	<td><?php echo $data['igst'];?></td></tr></thead>
                                                  <?php } else if($data['apply_gst']=='1') {?>
                                                    <thead><tr> <th>CGST @ 9.00%</th>	<td><?php echo $data['cgst'];?></td></tr></thead>
                                                    <thead><tr> <th>SGST @ 9.00%</th>	<td><?php echo $data['sgst'];?></td></tr></thead>
                                                  <?php }  ?>
						

						<thead><tr> <th>Grand Total:</th><td><?php echo "<b>".$data['grnd']."</b>";?></td></tr></thead>
				</table> 
							
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label"></label>
                                        <div class="col-sm-1">
                                            <button type="submit" onclick="this.disabled=true;this.form.submit();return true;" class="btn btn-primary btn-label-left"><b>Approved</b></button>
                                        </div>
                                        <div class="col-sm-1">
                                            <?php echo $this->Html->link('  Back',array('action'=>'download_proforma'),array('class'=>'btn btn-primary')); ?>
                                        </div>
                                    </div>
                                </div>
			</div>
		</div>
	</div>
</div>
<?php	echo $this->Form->input('InitialInvoice.id', 	array('label'=>false,'value'=>$data['id'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.branch_name', 	array('label'=>false,'value'=>$data['branch_name'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.total', 	array('label'=>false,'value'=>$data['total'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.finance_year',		array('label'=>false,'value'=>$data['finance_year'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.month',		array('label'=>false,'value'=>$data['month'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.po_no',		array('label'=>false,'value'=>$data['po_no'],'type'=>'hidden')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
<script type="text/javascript">
$(document).ready(function() {
	// Drag-n-Drop feature
	WinMove();
});
</script>