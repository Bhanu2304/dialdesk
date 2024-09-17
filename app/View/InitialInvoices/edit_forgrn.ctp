<?php //print_r($inv_particulars); ?>
<?php echo $this->Form->create('InitialInvoice',array('class'=>'form-horizontal','action'=>'update_grn','enctype'=>'multipart/form-data')); ?>
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
		<div id="social" class="pull-right">
			<a href="#"><i class="fa fa-google-plus"></i></a>
			<a href="#"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-linkedin"></i></a>
			<a href="#"><i class="fa fa-youtube"></i></a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search"></i>
					<span>Add GRN To Invoice</span>
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
						</tr>
						<tr align="center">
							<td class="info"><?php echo $data['branch_name'];  ?></td>
							<td class="danger"><?php echo $data['cost_center']; ?></td>
							<td class="info"><?php echo $data['finance_year'];	?></td>
							<td class="danger"><?php echo $data['month'];	?></td>
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
					<i class="fa fa-table"></i>
					<span>Bill To</span>
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
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['client'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['bill_to'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['b_Address1'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['b_Address2'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['b_Address3'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['b_Address4'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['b_Address5'];?></th></tr>
						</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-xs-8 col-sm-4">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Ship To</span>
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
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['client'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['ship_to'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['a_address1'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['a_address2'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['a_address3'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['a_address4'];?></th></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th><?php echo $dataX['a_address5'];?></th></tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-xs-8 col-sm-4">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
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
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th>Bill Date</th><td><?php $date=date_create($data['invoiceDate']); echo date_format($date,"d-M-Y"); ?></td></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th>Bill No.</th><td><?php echo $data['bill_no'];?></td></tr>
						
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th>JCC No.</th><td><?php echo $data['jcc_no'];?></td></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th>PO No.</th><td><?php echo $data['po_no']; ?></td></tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th>GRN</th><td><?php echo $this->Form->input('InitialInvoice.grn', 
						array('label'=>false,'class'=>'form-control','value'=>$data['grn'],'required'=>true)); ?></td>
						</tr>
						<tr class="<?php  echo $case[$i%3]; $i++;?>"><th>File Attach</th><td><?php echo $this->Form->file('InitialInvoice.filepath.', array('label'=>false,'type' => 'file','class' => 'form-control','multiple','required'=>false)); ?></td>
						</tr>
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
					<i class="fa fa-table"></i>
					<span>Bill Details</span>
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
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
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
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					
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
				<table class="table table-striped">
						<tr><thead> <th>Total:</th>					<td><?php echo $data['total'];?></td></thead></tr>
                                                <?php if(strtotime($data['invoiceDate']) > strtotime("2017-06-30")) { if($data['GSTType']=='Integrated' && $data['apply_gst']=='1') { ?>
                                                   <tr><thead> <th>IGST @ 18.00%</th>	<td><?php echo $data['igst'];?></td></thead></tr>
                                                  <?php } else if($data['apply_gst']=='1') {?>
                                                    <tr><thead> <th>CGST @ 9.00%</th>	<td><?php echo $data['cgst'];?></td></thead></tr>
                                                    <tr><thead> <th>SGST @ 9.00%</th>	<td><?php echo $data['sgst'];?></td></thead></tr>
                                                  <?php }} else { ?>
						<tr><thead> <th>Service Tax @ 14.00%</th>	<td><?php echo $data['tax'];?></td></thead></tr>
						<?php if(strtotime($data['invoiceDate']) > strtotime("2015-11-14")) { ?>
						<tr><thead> <th>SBC @ 0.5%</th>	<td><?php echo $data['sbctax'];?></td></thead></tr>
						<?php } ?>
                                                <?php if(strtotime($data['invoiceDate']) > strtotime("2016-05-31")) { ?>
						<tr><thead> <th>KKC @ 0.5%</th>	<td><?php echo $data['krishi_tax'];?></td></thead></tr>
                                                  <?php }} ?>
						<tr><thead> <th>Grand Total:</th>			<td><?php echo "<b>".$data['grnd']."</b>";?></td></thead></tr>
				</table>
							<div class="box-content">
					<div class="col-sm-2"><button type="submit" class="btn btn-success btn-label-left"><b>ADD GRN</b></div>
			</div>

			</div>
		</div>
	</div>
</div>
<?php	echo $this->Form->input('InitialInvoice.id', 	array('label'=>false,'value'=>$data['id'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.branch_name', 	array('label'=>false,'value'=>$data['branch_name'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.bill_no', 	array('label'=>false,'value'=>$data['bill_no'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.total', 	array('label'=>false,'value'=>$data['total'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.month', 	array('label'=>false,'value'=>$data['month'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.finance_year',		array('label'=>false,'value'=>$data['finance_year'],'type'=>'hidden')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
