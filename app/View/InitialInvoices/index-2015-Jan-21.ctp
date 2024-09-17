<?php echo $this->Form->create('InitialInvoice',array('class'=>'form-horizontal','action'=>'add')); ?>
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
					<span>Invoice Entry</span>
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
				<h4 class="page-header">Initial Invoice</h4>
										
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Branch</label>
						<div class="col-sm-2">
						<?php $data=array(); foreach ($branch_master as $post): ?>
						<?php $data[$post['Addbranch']['branch_name']]= $post['Addbranch']['branch_name']; ?>
						<?php endforeach; ?><?php unset($Addbranch); ?>

							<?php echo $this->Form->input('branch_name', array('options' => $data,'empty' => 'Select Branch','label' => false, 'div' => false,'class'=>'form-control','onChange'=>'get_costcenter(this)')); ?>
						</div>

						<label class="col-sm-2 control-label">Cost Center</label>
						<div class="col-sm-2"><div id="mm">
							<?php	echo $this->Form->input('cost_center', array('label'=>false,'class'=>'form-control','options' => '','empty' => 'Cost Center','required'=>true)); ?></div>
						</div>
					</div>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">Finance Year</label>
						<div class="col-sm-2">
						<?php $data=array('2015-16'=>'2015-16'); ?>
							<?php	echo $this->Form->input('finance_year', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Select Year','required'=>true)); ?>
						</div>

						<label class="col-sm-2 control-label">Month</label>
						<div class="col-sm-2">
						<?php 
								$data=array(
								'Jan-15'=>'Jan','Feb-15'=>'Feb','Mar-15'=>'Mar','Apr-15'=>'Apr','May-15'=>'May','Jun-15'=>'Jun','Jul-15'=>'Jul','Aug-15'=>'Aug','Sep-15'=>'Sep','Oct-15'=>'Oct','Nov-15'=>'Nov','Dec-15'=>'Dec');
						 ?>
							<?php	echo $this->Form->input('month', array('label'=>false,'class'=>'form-control','options' => $data,'empty' => 'Month','required'=>true,'onChange'=>'getDescription(this)')); ?>
						</div>
					</div>
						<div class="form-group has-success has-feedback">
						<label class="col-sm-2 control-label">invoice Date</label>
						<div class="col-sm-2">
							<?php	echo $this->Form->input('invoiceDate', array('label'=>false,'class'=>'form-control','placeholder'=>'Date',
							'onClick'=>"displayDatePicker('data[InitialInvoice][invoiceDate]');",'required'=>true)); ?>
						</div>

						<label class="col-sm-2 control-label">Apply Tax Calculation</label>
						<div class="col-sm-2">
							<div class="checkbox-inline" ><label>
							<?php	echo $this->Form->checkbox('app_tax_cal', array('label'=>false,'checked'=>true)); ?><i class="fa fa-square-o"></i></label></div>(check for Yes)
						</div>
					</div>

						<div class="form-group has-success has-feedback">
							<label class="col-sm-2 control-label">Invoice Description</label>
							<div class="col-sm-2">
							<?php	echo $this->Form->input('invoiceDescription', array('label'=>false,'class'=>'form-control','placeholder' => 'Invoice Description','required'=>true)); ?>
							</div>
					
						<div class="col-sm-2">
							<button type="submit" class="btn btn-success btn-label-left"><b>Go</b>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
					
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end(); ?>
<div id="mm"></div><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

</body>
</html>
