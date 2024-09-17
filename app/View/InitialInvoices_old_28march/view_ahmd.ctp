<?php //print_r($branch_master); ?>

<?php foreach($branch_master as $post) :
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
					<span>Download Initial Invoice</span>
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
					
					<?php echo $this->Session->flash(); ?>
                                            <?php echo $this->Form->create(array('controller'=>'InitialInvoice','url'=>'view_ahmd')); ?>
                                            	<div class="form-group has-info has-feedback">
							<label class="col-sm-3 control-label"><b style="font-size:14px"> Select Company </b></label>
                                                        <div class="col-sm-3">
                                                        <?php	echo $this->Form->input('company_name', array('label'=>false,'options'=>array('Mas Callnet India Pvt Ltd'=>'Mas Callnet India Pvt Ltd','IDC'=>'ISPARK Dataconnect Pvt. Ltd.'),'empty'=>'Select Company','required'=>false,'class'=>'form-control')); ?>
                                                        </div>    
						<label class="col-sm-3 control-label"><b style="font-size:14px">
							 Select Branch </b></label>
                                                <div class="col-sm-3">
                                                    <?php	echo $this->Form->input('branch_name', array('label'=>false,'options'=>$data,'empty'=>'Select Branch','required'=>false,'class'=>'form-control','onChange'=>'view_ahmd(this)')); ?>
						</div>
						</div>
                                                <div class="form-group has-info has-feedback">
						<label class="col-sm-3 control-label"><b style="font-size:14px"> Select Finance year </b></label>
                                                <div class="col-sm-3">
                                        	<?php	echo $this->Form->input('finance_year', array('label'=>false,'options'=>$finance_yearNew,'empty'=>'Select Finance Year','class'=>'form-control')); ?>
						</div>
                                                
						<label class="col-sm-3 control-label"><b style="font-size:14px"> Bill No. </b></label>
                                                <div class="col-sm-3">
                                        	<?php	echo $this->Form->input('bill_no', array('label'=>false,'class'=>'form-control','onBlur'=>'view_ahmd_billno(this)')); ?>
						</div>
                                                </div>
                                                    <div class="form-group has-info has-feedback">
                                                        <label class="col-sm-3 control-label"><b style="font-size:14px">  </b></label><div class="col-sm-3">	<input type="submit" class="btn btn-info" value="search"></div>
						</div>
                                            <?php echo $this->Form->end();?>
                        </div>
					<div id="mm">
                                <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
				<?php $case=array('active','','active','','info'); $i=0; ?>
					<tbody>
						<tr class="info" align="center">
							<th>Sr. No.</th>
							<th>Branch Name</th>
							<th>Invoice No.</th>
							<th>Amount</th>
							<th>PO No.</th>
							<th>Description</th>
							<th colspan="2">Action</th>
						</tr>
						<?php if(isset($tbl_invoice)){ foreach ($tbl_invoice as $post): ?>
						<tr class="<?php  echo $case[$i%4]; $i++;?>" align="center">
							<?php $id= $post['InitialInvoice']['id']; ?>
							<td><?php echo $i; ?></td>
							<td><?php echo $post['InitialInvoice']['branch_name']; ?></td>
							<td><?php echo $post['InitialInvoice']['bill_no']; ?></td>
							<td><?php echo $post['InitialInvoice']['total']; ?></td>
							<td><?php echo $post['InitialInvoice']['po_no']; ?></td>
							<td><?php echo $post['InitialInvoice']['invoiceDescription']; ?></td>
							<td><?php echo $this->Html->link(__('PDF'), array('controller'=>'InitialInvoices','action' => 'view_pdf','?'=>array('id'=>$id), 'ext' => 'pdf', 'DownloadPdf')); ?>
							<td><?php echo $this->Html->link(__('Letter Head'), array('controller'=>'InitialInvoices','action' => 'view_pdf1','?'=>array('id'=>$id), 'ext' => 'pdf', 'DownloadPdf')); ?>
							</td>
						</tr>
                                                <?php endforeach; } ?>
						<?php unset($InitialInvoice); ?>
					</tbody>
				</table>
                          </div>
                    </div>
               </div>
	</div>
			
