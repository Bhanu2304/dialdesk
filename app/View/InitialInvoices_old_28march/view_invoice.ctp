<?php //print_r($branch_master);
   
    foreach($branch_master as $post) :
	$data[$post['Addbranch']['branch_name']]=$post['Addbranch']['branch_name'];
	endforeach;
?>
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
        
            
	<div class="box-content">
        <h4 class="page-header">
        <?php echo $this->Session->flash(); ?></h4>
           <?php echo $this->Form->create(array('controller'=>'AddInvParticular','url'=>'view_invoice')); ?>
        <div class="form-group has-info has-feedback">
        <label class="col-sm-3 control-label"><b style="font-size:14px"> Select Company </b></label>	
        <div class="col-sm-3">
        <?php	echo $this->Form->input('company_name', array('label'=>false,'required'=>false,'options'=>
            array('Mas Callnet India Pvt Ltd'=>'Mas Callnet India Pvt Ltd','IDC'=>'ISPARK Dataconnect Pvt. Ltd.'),'empty'=>'Select Company','class'=>'form-control','onChange'=>'getInvoices1(this)'));
            $this->Js->event('change',    $this->Js->request(array('controller' => 'AddInvParticular','action'=>'view'),
            array('async' => true, 'update' => '#mm')));
	?>
	</div>
        <label class="col-sm-3 control-label"><b style="font-size:14px"> Select Finance Year </b></label>	
        <div class="col-sm-3">
        <?php	echo $this->Form->input('finance_year', array('label'=>false,'options'=>$finance_yearNew,'empty'=>'Select Finance Year','class'=>'form-control','onChange'=>'getInvoices1(this)'));
            $this->Js->event('change',    $this->Js->request(array('controller' => 'AddInvParticular','action'=>'view'),
            array('async' => true, 'update' => '#mm')));
	?>
	</div>        
        </div>
        <div class="form-group has-info has-feedback">
       <label class="col-sm-3 control-label"><b style="font-size:14px"> Select Branch </b></label>	
        <div class="col-sm-3">
        <?php	echo $this->Form->input('branch_name', array('label'=>false,'required'=>false,'options'=>$data,'empty'=>'Select Branch','class'=>'form-control','onChange'=>'getInvoices1(this)'));
            $this->Js->event('change',$this->Js->request(array('controller' => 'AddInvParticular','action'=>'view'),
            array('async' => true, 'update' => '#mm')));
	?>
	</div>
	<label class="col-sm-3 control-label"><b style="font-size:14px"> Bill No. </b></label>	
	<div class="col-sm-3">
            <?php	echo $this->Form->input('bill_no', array('label'=>false,'class'=>'form-control','onBlur'=>'get_invoices_bybillno(this)')); ?>
	</div>
	</div>
            <div class="form-group has-info has-feedback">
            <label class="col-sm-3 control-label"><b style="font-size:14px">  </b></label>
            <div class="col-sm-3">
                <input type="submit" class="btn btn-info" value="search">
	</div>
            </div>
        <?php echo $this->Form->end();?>
	</div>
        <div id="mm">
        			<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('active','','active',''); $i=0; ?>
					<tbody>
						<tr class="info" align="center">
							<th>Sr. No.</th>
							<th>Branch Name</th>
							<th>Bill No.</th>
							<th>Amount</th>
							<th>PO No.</th>
							<th>GRN</th>
							<th>Description</th>
							<th>Action</th>
						</tr>
						<?php if(isset($tbl_invoice)){ foreach ($tbl_invoice as $post): ?>
						<tr class="<?php  echo $case[$i%3]; $i++;?>" align="center">
							<?php $id= $post['ti']['id']; ?>
							<td><?php echo $i; ?></td>
							<td><?php echo $post['ti']['branch_name']; ?></td>
							<td><?php echo $post['ti']['bill_no']; ?></td>
							<td><?php echo $post['ti']['total']; ?></td>
							<td><?php echo $post['ti']['po_no']; ?></td>
							<td><?php echo $post['ti']['grn']; ?></td>
							<td><?php echo $post['ti']['invoiceDescription']; ?></td>
							<td>
								<?php echo $this->Html->link('Edit',
							array('controller'=>'InitialInvoices','action'=>'edit_invoice','?'=>array('id'=>$id),'full_base' => true)); ?>
															<?php echo $this->Html->link('Reject',
							array('controller'=>'InitialInvoices','action'=>'reject_invoice','?'=>array('id'=>$id),'full_base' => true)); ?>
							</td>
						</tr>
                                                <?php endforeach; } ?>
						<?php unset($InitialInvoice); ?>
					</tbody>
				</table>						
        </div>
	
	</div>
	</div>	
