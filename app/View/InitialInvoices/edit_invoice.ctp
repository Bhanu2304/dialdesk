<?php 
//print_r($inv_particulars); 
 echo $this->Form->create('InitialInvoice',array('class'=>'form-horizontal','action'=>'update_invoice')); 
 foreach ($tbl_invoice as $post): 
  $data=$post;
 endforeach;  unset($InitialInvoice); 
						
 foreach ($cost_master as $post):
    $dataX=$post;
 endforeach;  
 unset($CostCenterMaster);

 //print_r($dataX); exit;
 
 $dataY=array(); 
 foreach ($branch_master as $post): 
    $dataY[$post['Addbranch']['branch_name']]= $post['Addbranch']['branch_name'];
 endforeach;
 unset($Addbranch);
						
 $dataZ=array(); foreach ($cost_master2 as $post): 
    $dataZ[$post['CostCenterMaster']['cost_center']]= $post['CostCenterMaster']['cost_center']; 
 endforeach; 
 unset($CostCenterMaster);

 ?>						
						
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
    <a href="#" class="show-sidebar">
    <i class="fa fa-bars"></i></a>
    <ol class="breadcrumb pull-left"></ol>
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
		<span>Edit Invoice</span>
                
		</div>
		<div class="box-icons">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="expand-link"><i class="fa fa-expand"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
		</div>
		<div class="no-move"></div>
            </div>
            <div class="box-content">
                <h4><?php echo $this->Session->flash();?></h4>
            <!---	creating hide array for particulars table and hidden fields -->
            <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
            <tr align="center">
                <td>Branch</td>
		<td>Cost Center</td>
		<td>Financial Year</td>
                
		<td>Month for</td>
                <?php if(strtotime($data['InitialInvoice']['invoiceDate'])>strtotime("2017-06-30")) { ?>
                <td>GST No</td>
                <td>Client GST No</td>
                <?php } ?>
            </tr>
            <tr align="center">
            <td class="info"><?php echo $data['InitialInvoice']['branch_name'];  ?></td>
            <td class="danger"><?php echo $data['InitialInvoice']['cost_center']; ?></td>
            <td class="info"><?php echo $data['InitialInvoice']['finance_year'];?></td>
            <td class="danger"><?php echo $data['InitialInvoice']['month'];?></td>
            <?php if(strtotime($data['InitialInvoice']['invoiceDate'])>strtotime("2017-06-30")) { ?>
            <td class="info"><?php echo $cost_master['CostCenterMaster']['ServiceTaxNo'];?></td>
            <td class="info"><?php echo $cost_master['CostCenterMaster']['VendorGSTNo'];?></td>
            <?php } ?>
            </tr>

<!--            <tr align="center">
            <td class="info">
                <?php echo $this->Form->input('InitialInvoice.branch_name', array('options' => $dataY,
                    'selected' => $data['InitialInvoice']['branch_name'],'label' => false, 'div' => false,'class'=>'form-control',
                    'onChange'=>'get_costcenter2(this)')); ?>
            </td>
            <td class="danger"><div id = "oo">
                <?php echo $this->Form->input('InitialInvoice.cost_center', array('options' => $dataZ,
                    'selected' => $data['InitialInvoice']['cost_center'],'label' => false, 'div' => false,'class'=>'form-control')); ?>
            </div></td>
            <td class="info">
                <?php echo $this->Form->input('InitialInvoice.finance_year', array('options' => $finance_yearNew,'selected' => $data['InitialInvoice']['finance_year'],'label' => false,
                    'div' => false,'class'=>'form-control')); ?></td>
            <td class="danger">
                <?php $month =  explode('-',$data['InitialInvoice']['month']);
                $month = $month[0];
                echo $this->Form->input('InitialInvoice.month', array('options' => array(
'Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug',
                    'Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'),'value' => $month,
                    'label' => false, 'div' => false,'class'=>'form-control')); ?>
            </td>
            <?php if(strtotime($data['InitialInvoice']['invoiceDate'])>strtotime("2017-06-30")) { ?>
            <td class="info"></td>
            <td class="danger"></td>
            <?php } ?>
            </tr>-->

            <tr align="center">
            <td class="">
                <?php echo $this->Form->input('InitialInvoice.branch_name', array(/*'options' => $dataY,*/'readonly'=>true,
                    'value' => $data['InitialInvoice']['branch_name'],'label' => false, 'div' => false,'class'=>'form-control',
                    'onChange'=>'get_costcenter2(this)')); ?>
            </td>
            <td class="danger"><div id = "oo">
                <?php echo $this->Form->input('InitialInvoice.cost_center', array(/*'options' => $dataZ,*/'readonly'=>true,
                    'value' => $data['InitialInvoice']['cost_center'],'label' => false, 'div' => false,'class'=>'form-control')); ?>
            </div></td>
            <td class="">
                <?php echo $this->Form->input('InitialInvoice.finance_year', array('options' => $finance_yearNew,'readonly'=>true,'value' => $data['InitialInvoice']['finance_year'],'label' => false,
                    'div' => false,'class'=>'form-control'));
                
                
                
                ?></td>
            
            <td class="danger">
                <?php $month =  explode('-',$data['InitialInvoice']['month']);
                $month = $month[0];
                echo $this->Form->input('InitialInvoice.month', array('options' => array(
'Jan'=>'Jan','Feb'=>'Feb','Mar'=>'Mar','Apr'=>'Apr','May'=>'May','Jun'=>'Jun','Jul'=>'Jul','Aug'=>'Aug',
                    'Sep'=>'Sep','Oct'=>'Oct','Nov'=>'Nov','Dec'=>'Dec'), 'readonly'=>true,'value' => $month,
                    'label' => false, 'div' => false,'class'=>'form-control')); ?>
            </td>
            <?php if(strtotime($data['InitialInvoice']['invoiceDate'])>strtotime("2017-06-30")) { ?>
            <td class=""></td>
            <td class="danger"></td>
            <?php } ?>
            </tr>        
                    
                    
            </table>
            
            <div class="form-group">
                <table class="table table-striped">
                    <tr>
                        <th>Revenue</th> 
                        <?php
                                foreach($monthMaster as $mnt=>$mntRevenue)
                                {
                                    echo '<td>'.$mnt.'</td>';
                                }
                        ?>
                        <th>Total Revenue</th>
                    </tr>
                    
                    <tr>
                        <th>Revenue Remaining</th>
                        <?php
                                foreach($monthMaster as $mnt=>$mntRevenue)
                                {
                                    echo '<td>'.$ActualRevenue[$mnt].'</td>';
                                }
                        ?>
                        <th><?php echo array_sum($ActualRevenue); ?></th>
                    </tr>
                    
                    <tr>
                        <th>Revenue Choosen</th>
                        <?php
                                foreach($monthMaster as $mnt=>$mntRevenue)
                                {
                                    echo '<td>';
                                    echo $this->Form->input('InitialInvoice.revenue_arr.'.$mnt, array('label'=>false,'id'=>substr($mnt,0,3),'value'=>$mntRevenue,'onKeypress'=>'return isNumberKey(event)','type'=>'text','align'=>'right','onblur'=>"get_revenue_change()"));
                                    echo '</td>';
                                    $revenue_str[] = substr($mnt,0,3);
                                }
                        ?>
                        <th><?php echo $revenue; ?></th>
                    </tr>
                </table>
                <h6><b><font color="red">Note:-</font></b>Revenue Chosen will be smaller or equal to Total Revenue Remaining</h6>
            </div>
            
            
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-8 col-sm-4">
    <div class="box">
    <div class="box-header">
    <div class="box-name"><i class="fa fa-table"></i><span>Bill To</span></div>
    <div class="box-icons">
        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
        <a class="expand-link"><i class="fa fa-expand"></i></a>
        <a class="close-link"><i class="fa fa-times"></i></a>
    </div>
    <div class="no-move"></div>
    </div>
    <div class="box-content no-padding">
    <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
    <?php $case=array('active','','active',''); $i=0; ?>
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
            <div class="box-name"><i class="fa fa-table"></i><span>Ship To</span></div>
        <div class="box-icons">
	<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
        <a class="expand-link"><i class="fa fa-expand"></i></a>
        <a class="close-link"><i class="fa fa-times"></i></a>
	</div>
	<div class="no-move"></div>
	</div>
	<div class="box-content no-padding">
				
	<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
	<?php $case=array('active','','active',''); $i=0; ?>
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
            <div class="box-icons">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                <a class="expand-link"><i class="fa fa-expand"></i></a>
                <a class="close-link"><i class="fa fa-times"></i></a>
            </div>
	<div class="no-move"></div>
	</div>
	<div class="box-content no-padding">

            <table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
            <?php $case=array('active','','active',''); $i=0; ?>
            <tbody>
					
            <tr class="<?php  echo $case[$i%3]; $i++;?>">
                <th>Bill Date</th>
		<td><?php $date=date_create($data['InitialInvoice']['invoiceDate']); 
		echo $date= "".date_format($date,"d-M-Y").""; 
		//echo $this->Form->input('InitialInvoice.invoiceDate', 
		//array('label'=>false,'value'=>$date,'class'=>'form-control'));
		?>
		</td>
            </tr>
            <tr class="<?php  echo $case[$i%3]; $i++;?>">
            <th>Date Change</th>
            <td>
                <?php	
                $dat =$data['InitialInvoice']['invoiceDate'];
		$dat=$dat." 00:00:00";
		$dat=date_create($dat);
		$dat = date_format($dat,"d-m-Y");
                echo $this->Form->input('InitialInvoice.invoiceDate', array('label'=>false,'class'=>'form-control','value'=>$dat,
		'onClick'=>"displayDatePicker('data[InitialInvoice][invoiceDate]');")); 
                ?>
            </td>
            </tr>

            <tr class="<?php  echo $case[$i%3]; $i++;?>">
            <th>Bill No.</th>
            <td><?php	echo $this->Form->input('InitialInvoice.bill_no', 
            array('label'=>false,'value'=>$data['InitialInvoice']['bill_no'],'class'=>'form-control','readOnly'=>true)); ?></td>
            </tr>

            <tr class="<?php  echo $case[$i%3]; $i++;?>">
            <th>JCC No.</th><td><?php	
            echo $this->Form->input('InitialInvoice.jcc_no', 
            array('label'=>false,'value'=>$data['InitialInvoice']['jcc_no'],'class'=>'form-control')); ?>
            </td>
            </tr>

            <tr class="<?php  echo $case[$i%3]; $i++;?>">
            <th>PO No.</th><td><?php	echo $this->Form->input('InitialInvoice.po_no', 
                array('label'=>false,'value'=>$data['InitialInvoice']['po_no'],'class'=>'form-control')); ?></td>
            </tr>
						
            <tr class="<?php  echo $case[$i%3]; $i++;?>">
            <th>GRN</th><td><?php	echo $this->Form->input('InitialInvoice.grn', 
                array('label'=>false,'value'=>$data['InitialInvoice']['grn'],'class'=>'form-control')); ?></td>
            </tr>
            <tr class="<?php  echo $case[$i%3]; $i++;?>">
            <th>Description</th>
            <td><?php	echo $this->Form->input('InitialInvoice.invoiceDescription', 
            array('label'=>false,'value'=>$data['InitialInvoice']['invoiceDescription'],'class'=>'form-control')); ?></td>
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
							<th> Qty. </th>
							<th> Rate </th>
							<th> Amount </th>
							<th> Delete </th>
						</tr>
						
						<tbody>
							<tr>
								<td></td>
								<td><?php	echo $this->Form->input('AddInvParticular.particulars',
								 array('label'=>false,'class'=>'form-control','placeholder'=>'Particulars','id'=>'particulars')); ?></td>
								<td><?php	echo $this->Form->input('AddInvParticular.qty', array('label'=>false,'class'=>'form-control','placeholder'=>'Qty','onKeypress'=>'return isNumberKey(event)')); ?></td>
								<td><?php	echo $this->Form->input('AddInvParticular.rate', array('label'=>false,'class'=>'form-control','placeholder'=>'Rate','onBlur'=>'getAmount3(this.value)','onKeypress'=>'return isNumberKey(event)')); ?></td>
								<td><?php	echo $this->Form->input('AddInvParticular.amount', array('label'=>false,'class'=>'form-control','value'=>0,'placeholder'=>'Amount','readonly'=>true)); ?></td>
								<td><div class="submit"><input class="btn btn-success btn-label-left" id="p1" type="submit" value="ADD" onClick="return add_part(this.value)"></div></td>
							</tr>
						</tbody>
						
						<div id = "mm"></div>
						<?php $idx=''; ?>
						<?php  foreach ($inv_particulars as $post): ?>
							<?php $idx.=$post['Particular']['id'].','; ?>
							<tr class="<?php  echo $case[$i%3]; $i++;?>">
							<td><?php echo $i;?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.particulars',array('label'=>false,'value'=>$post['Particular']['particulars'],'class'=>'form-control')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.qty',array('label'=>false,'value'=>$post['Particular']['qty'],'class'=>'form-control','onkeypress'=>'return isNumberKey(event)')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.rate',array('label'=>false,'value'=>$post['Particular']['rate'],'class'=>'form-control','onBlur'=>'getAmount1(this.id)','onkeypress'=>'return isNumberKey(event)')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.amount',array('label'=>false,'value'=>$post['Particular']['amount'],'class'=>'form-control','readonly'=>true)); ?></td>
							<td> <button name = Delete class="btn btn-primary" value="<?php echo $post['Particular']['id']; ?>" onClick ="return deletes(this.value)">Delete</button> </td>
							</tr>
						<?php endforeach; ?><?php unset($Particular); ?>
						<?php echo $this->Form->input('a.idx',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx')); ?>
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
					<span>Any Deduction</span>
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
							<th> Qty. </th>
							<th> Rate </th>
							<th> Amount </th>
						</tr>
                        
                        <tbody>
							<tr>
								<td></td>
								<td><?php	echo $this->Form->input('AddInvDeductParticular.particulars', 
								 array('label'=>false,'class'=>'form-control','placeholder'=>'Particulars','id'=>'deductparticulars')); ?></td>
								<td><?php	echo $this->Form->input('AddInvDeductParticular.qty', array('label'=>false,'class'=>'form-control','placeholder'=>'Qty','onKeypress'=>'return isNumberKey(event)')); ?></td>
								<td><?php	echo $this->Form->input('AddInvDeductParticular.rate', array('label'=>false,'class'=>'form-control','placeholder'=>'Rate','onBlur'=>'getAmount4(this.value)','onKeypress'=>'return isNumberKey(event)')); ?></td>
								<td><?php	echo $this->Form->input('AddInvDeductParticular.amount', array('label'=>false,'class'=>'form-control','value'=>0,'placeholder'=>'Amount','readonly'=>true)); ?></td>
								<td><div class="submit"><input class="btn btn-success btn-label-left" id="p1" type="submit" value="ADD" onClick="return add_deduct_part(this.value)"></div></td>
							</tr>
						</tbody>
                        
						<?php $idxd=''; ?>
						<?php  foreach ($inv_deduct_particulars as $post): ?>
						<?php $idxd.=$post['DeductParticular']['id'].','; ?>
							<tr class="<?php  echo $case[$i%3]; $i++;?>">
							<td><?php echo $i;?></td>
							<td><?php echo $this->Form->input('DeductParticular.'.$post['DeductParticular']['id'].'.particulars',array('label'=>false,'value'=>$post['DeductParticular']['particulars'],'class'=>'form-control'));?></td>
							<td><?php echo $this->Form->input('DeductParticular.'.$post['DeductParticular']['id'].'.qty',array('label'=>false,'value'=>$post['DeductParticular']['qty'],'class'=>'form-control','onkeypress'=>'return isNumberKey(event)'));?></td>
							<td><?php echo $this->Form->input('DeductParticular.'.$post['DeductParticular']['id'].'.rate',array('label'=>false,'value'=>$post['DeductParticular']['rate'],'class'=>'form-control','onBlur'=>'getAmount2(this.id)','onkeypress'=>'return isNumberKey(event)'));?></td>							
							<td><?php echo $this->Form->input('DeductParticular.'.$post['DeductParticular']['id'].'.amount',array('label'=>false,'value'=>$post['DeductParticular']['amount'],'class'=>'form-control','readOnly'=>true));?></td>
							<td> <button name = Delete class="btn btn-primary" value="<?php echo $post['DeductParticular']['id']; ?>" onClick ="return deletes1(this.value)">Delete</button> </td>
							</tr>
						<?php endforeach; ?><?php unset($DeductParticular); ?>
						<?php echo $this->Form->input('a.idxd',array('label'=>false,'value'=>$idxd,'type'=>'hidden','id'=>'idxd')); ?>
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
                                <?php $apply_krishi_tax=false;$app_tax_cal = false; $apply_service_tax = false; ?>
                                
                                <tr> 
                                    <th>Total:</th>
                                    <td><?php echo $this->Form->input('InitialInvoice.total',
                                array('label'=>false,'value'=>$data['InitialInvoice']['total'],'readonly'=>true));?>
                                    </td>
                                </tr>
                                                
                                <?php if($data['InitialInvoice']['app_tax_cal']=='1') { 
                                    
                                    if(strtotime($data['InitialInvoice']['invoiceDate'])>strtotime("2017-06-30"))
                                                    {
                                                      if(strtolower($dataX['GSTType'])==strtolower('Integrated') && $data['InitialInvoice']['apply_gst']=='1')
                                                      {
                                                    ?>
                                                <tr>
                                                    <th>IGST @ 18%</th>
                                                    <td><?php echo $this->Form->input('InitialInvoice.igst', 
                                                        array('label'=>false,'value'=>round($data['InitialInvoice']['igst']),'readonly'=>true)); ?>
                                                    </td>
                                                </tr>
                                                <?php      }
                                                else if($data['InitialInvoice']['apply_gst']=='1') {?>
                                                    <tr>
                                                    <th>CGST @ 9%</th>
                                                    <td><?php echo $this->Form->input('InitialInvoice.cgst', 
                                                        array('label'=>false,'value'=>round($data['InitialInvoice']['cgst']),'readonly'=>true)); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>SGST @ 9%</th>
                                                    <td><?php echo $this->Form->input('InitialInvoice.sgst', 
                                                        array('label'=>false,'value'=>round($data['InitialInvoice']['sgst']),'readonly'=>true)); ?>
                                                    </td>
                                                </tr>
                                                <?php }
                                                    }
                                                    else {
                                    
                                    $app_tax_cal =true; ?>
                                
				<tr> 
                                    <th>Service Tax @ 14.00%</th>
                                    <td><?php echo $this->Form->input('InitialInvoice.tax',
				array('label'=>false,'value'=>$data['InitialInvoice']['tax'],'readonly'=>true));?>
                                    </td>
                                </tr>
                                
				<?php if(strtotime($data['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14")){  ?>
                                
				<tr>
                                    <th>SBC @ 0.5%</th><td><?php echo $this->Form->input('InitialInvoice.sbctax',
                                array('label'=>false,'value'=>$data['InitialInvoice']['sbctax'],'readonly'=>true)); ?></td>
                                </tr>
                                <?php }}} ?>
                                                                
                                <?php if($data['InitialInvoice']['apply_krishi_tax']=="1") { $apply_krishi_tax=true; ?>
                                <tr><th>KKC @ 0.5%</th>
                                <td><?php echo $this->Form->input('InitialInvoice.krishi_tax',
                                array('label'=>false,'value'=>$data['InitialInvoice']['krishi_tax'],'readonly'=>true)); ?></td></tr>
                                <?php } ?>
                                
                                <tr> 
                                    <th>Grand Total:</th>
                                    <td><?php echo $this->Form->input('InitialInvoice.grnd',
				array('label'=>false,'value'=>$data['InitialInvoice']['grnd'],'readonly'=>true));?>
                                    </td>
                                </tr>
                                <?php if(strtotime($data['InitialInvoice']['invoiceDate'])>strtotime("2017-06-30")) { ?>
                                <tr>
                                    <th>GST Type</th>
                                    <td>
                                        <div class="col-sm-3">
                                                    <input type="radio" name="GSTType"  value="IntraState" <?php if($data['InitialInvoice']['GSTType']=='IntraState') echo "checked"; ?>>IntraState
                                                    <input type="radio" name="GSTType"  value="Integrated" <?php if($data['InitialInvoice']['GSTType']=='Integrated') echo "checked"; ?>>Integrated
                                        </div>
                                    </td>
                                </tr>
                                <?php }  else { ?>
                                
                                <tr>
                                    <th>Apply Krishi Tax</th>
                                    <td><div class="checkbox-inline" ><label>
                    <?php	echo $this->Form->checkbox('apply_krishi_tax', array('label'=>false,'checked'=>$apply_krishi_tax,'onClick'=>"apply_krishi_tax()")); ?>
                                    <i class="fa fa-square-o"></i></label></div>(Check for Krishi Tax)</td>
                                </tr>
                               <?php } ?>
                                <tr>
                                    <th>Apply Tax Calculation</th>
                                    <td>
                                        <div class="checkbox-inline" ><label>
                    <?php	echo $this->Form->checkbox('app_tax_cal', array('label'=>false,'checked'=>$data['InitialInvoice']['app_tax_cal'],'onclick'=>'apply_tax_cal()')); ?>
                                                                    <i class="fa fa-square-o"></i></label>
                                        </div>(check for Yes)
                                    </td>
                                    <tr>
                                    <th>Apply Service Tax</th>
                                    <td>
                                        <div class="col-sm-3"><div class="checkbox-inline" ><label>
                    <?php	echo $this->Form->checkbox('apply_service_tax', array('label'=>false,'checked'=>$data['InitialInvoice']['apply_service_tax']=="1"?true:false,'value'=>$data['InitialInvoice']['apply_service_tax'],'onClick'=>'apply_service_tax()')); ?>
                                                <i class="fa fa-square-o"></i></label></div>(Check for Service Tax)
                                        </div>
                                    </td>
                                </tr>
                                <tr> <th>Current Invoice Status</th>
                                    <td><?php echo $data['InitialInvoice']['CurrentInvoiceType'];?> </td>
                                </tr>
                                <tr> <th>Request To Change Invoice Status</th>
                                    <td><?php echo $this->Form->input('InitialInvoice.RequestInvoiceType',array('label'=>false,'options'=>array('OutStanding'=>'OutStanding','Dispute'=>'Dispute','Write-Off'=>'WriteOff'),'empty'=>"Select",'value'=>$data['InitialInvoice']['RequestInvoiceType'],'onchange'=>'get_status_change_remarks(this.value)'));?>  &nbsp;&nbsp;&nbsp;&nbsp; </td>
                                </tr>
                                <tr id="InvoiceTyperemarksDisp" style="display:none"><th>Remarks</th> <td><?php echo $this->Form->input('InitialInvoice.InvoiceTypeRemarks',array('label'=>false,'value'=>"",'placeholder'=>"Remarks For Change Invoice Status"));?></td></tr>
                                <tr> <th>Request To Delete Invoice</th>
                                    <td><?php echo $this->Form->input('InitialInvoice.InvoiceRejectRequest',array('label'=>false,'options'=>array('Reject'=>'Delete'),'empty'=>"Select",'onchange'=>"get_delete_remarks(this.value)"));?>  &nbsp;&nbsp;&nbsp;&nbsp; </td>
                                </tr>
                                <tr id="InvoiceDeleteremarksDisp" style="display:none"><th>Remarks</th> <td><?php echo $this->Form->input('InitialInvoice.InvoiceDeleteRemarks',array('label'=>false,'value'=>"",'placeholder'=>"Remarks For Delete Invoice"));?></td></tr>
                                <tr> <th>Invoice Type</th>
                                    <td><?php echo $this->Form->input('InitialInvoice.invoiceType',array('label'=>false,'options'=>array("Revenue"=>"Revenue","Non Revenue"=>"Non Revenue"),'empty'=>"Select",'value'=>$data['InitialInvoice']['invoiceType']));?>  &nbsp;&nbsp;&nbsp;&nbsp; </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-sm-2"><button type="submit" class="btn btn-success btn-label-left" onClick="return validate_edit()"><b>Update</b></div>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php if($_GET['status']=='ch') {echo $this->Html->link('  Back',array('action'=>'view_status_change_request'),array('class'=>'btn btn-primary'));} 
                                        else if(in_array('5',$roles)){echo $this->Html->link('  Back',array('action'=>'view_invoice'),array('class'=>'btn btn-primary'));}
                                        else{echo $this->Html->link('Back',array('action'=>'branch_view'),array('class'=>'btn btn-primary'));}	 ?>
                                    </td>
                                    <td>
                                        
                                    </td>
                                </tr>

				</table>
			</div>
		</div>
	</div>
</div>
<?php	
echo $this->Form->input('InitialInvoice.revenue_str', 	array('label'=>false,'id'=>"revenue_str",'value'=>implode(",",$revenue_str),'type'=>'hidden'));
echo $this->Form->input('InitialInvoice.revenue', 	array('label'=>false,'id'=>"revenue",'value'=>$revenue,'type'=>'hidden')); ?>
<?php 
echo $this->Form->input('InitialInvoice.id',array('label'=>false,'value'=>$data['InitialInvoice']['id'],'type'=>'hidden'));
echo $this->Form->input('InitialInvoice.GSTType',array('label'=>false,'value'=>$dataX['GSTType'],'type'=>'hidden'));
echo $this->Form->input('InitialInvoice.apply_gst',array('label'=>false,'value'=>$data['InitialInvoice']['apply_gst'],'type'=>'hidden'));
?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>
<script>
    function get_status_change_remarks(value)
    {
        if(value=='')
        {
            $('#InvoiceDeleteremarksDisp').hide();
        }
        else
        {
            $('#InvoiceDeleteremarksDisp').show();
        }
    }
</script>
<script>
    function get_delete_remarks(value)
    {
        if(value=='')
        {
            $('#InvoiceTyperemarksDisp').hide();
        }
        else
        {
            $('#InvoiceTyperemarksDisp').show();
        }
    }
    function get_revenue_change()
    {
        var rev_str = document.getElementById("revenue_str").value ;
        var rev_arr = rev_str.split(",");
        //alert(rev_arr);
        var revenue = 0;
        for(var i=0; i<rev_arr.length; i++)
        {
            revenue+= parseInt(document.getElementById(rev_arr[i]).value);
            
        }
        document.getElementById("revenue").value = revenue ;
    }
</script>