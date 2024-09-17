

			<?php //print_r($tbl_invoice); exit; ?>
<div class="form-horizontal">
						<?php  foreach ($tbl_invoice as $post): ?>
						<?php $data=$post; ?>
						<?php endforeach; ?><?php unset($InitialInvoice); ?>
						
						<?php  foreach ($cost_master as $post): ?>
						<?php $data=$post; ?>
						<?php endforeach; ?><?php unset($CostCenterMaster); ?>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a >Invoice</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>Invoice">View Invoice</a></li>
</ol>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}' style="margin-top:5px;">
            <div class="panel-body" style="margin-top:-10px;padding:0px;">
					<!--	creating hide array for particulars table and hidden fields -->
            <table class="table" style="margin-bottom:0px;">
                <tr align="center">
                        <th>Branch</th>
                        <th>Cost Center</th>
                        <th>Financial Year</th>
                        <th>Month for</th>
                        <th>GST No.</th>
                        <th>Vendor GST No.</th>
                </tr>
                <tr align="center">
                        <td><?php echo $data['branch']; $hide['branch_name']=$data['branch']; ?></td>
                        <td><?php echo $tbl_invoice['InitialInvoice']['cost_center'];
                 $hide['cost_center']=$tbl_invoice['InitialInvoice']['cost_center']; ?></td>
                        <td><?php echo $tbl_invoice['InitialInvoice']['finance_year'];
                                $hide['finance_year']=$tbl_invoice['InitialInvoice']['finance_year'];?>
                        </td>
                        <td><?php echo $tbl_invoice['InitialInvoice']['month'];
                                $hide['month_for']=$tbl_invoice['InitialInvoice']['month'];?>
                        </td>
                        <td><?php echo $cost_master['CostCenterMaster']['ServiceTaxNo'];?>
                        </td>
                        <td><?php echo $cost_master['CostCenterMaster']['VendorGSTNo'];?>
                        </td>
                </tr>
                <?php
                         $hide['cost_center_id']=$data['id'];
                         $date = $tbl_invoice['InitialInvoice']['invoiceDate'];
                         $date=date_create($date);
                         $date=date_format($date,"Y-m-d");
                         $hide['invoiceDate']=$date;
                         $hide['app_tax_cal']=$tbl_invoice['InitialInvoice']['app_tax_cal'];
                         $hide['apply_service_tax']=$tbl_invoice['InitialInvoice']['servicetax'];
                         $hide['apply_krishi_tax']=$tbl_invoice['InitialInvoice']['apply_krishi_tax'];
                         $hide['apply_gst']=1;
                         $hide['invoiceDescription']=$tbl_invoice['InitialInvoice']['invoiceDescription'];
                 ?>
        </table>				
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-xs-12 col-sm-4">
    <div class="box">        
            <table class="table" style="margin-bottom:0px;">
                <tbody>
                    <tr><th><b>Bill To:-</b> <?php echo $data['client'];?></th></tr>
                <tr><td><?php if(!empty($data['bill_to'])) {echo $data['bill_to'].'<br>';}?>
                        <?php 
                    if(!empty($data['b_Address1'])) { echo $data['b_Address1'];}
                    if(!empty($data['b_Address2'])) { echo $data['b_Address2'].'<br>';} 
                    if(!empty($data['b_Address3'])) { echo $data['b_Address3'].'<br>';} 
                    if(!empty($data['b_Address4'])) { echo $data['b_Address4'].'<br>';} 
                    if(!empty($data['b_Address5'])) { echo $data['b_Address5'].'<br>';} 
                    ?>
                </td></tr>
                </tbody>
            </table>
    </div>
</div>
<div class="col-xs-12 col-sm-4">
    <div class="box">
        <div class="box-content no-padding">
            <table class="table" style="margin-bottom:0px;">
                <tbody>
                    <tr><th><b>Ship To:-</b><?php echo $data['client'];?></th></tr>
                    <tr><td><?php if(!empty($data['ship_to'])) {echo $data['ship_to'].'<br>';} ?>
                    <?php 
                    if(!empty($data['a_address1'])) { echo $data['a_address1'].'<br>';}
                    if(!empty($data['a_address2'])) { echo $data['a_address2'].'<br>';} 
                    if(!empty($data['a_address3'])) { echo $data['a_address3'].'<br>';} 
                    if(!empty($data['a_address4'])) { echo $data['a_address4'].'<br>';} 
                    if(!empty($data['a_address5'])) { echo $data['a_address5'].'<br>';} 
                    ?>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="col-xs-12 col-sm-4">
    <div class="box">
        <div class="box-content no-padding">
            <table class="table"  style="margin-bottom:0px;">
                <tbody>
                    <tr><th>Date</th><td><?php $date=date_create($hide['invoiceDate']); echo date_format($date,"d-M-Y"); ?></td></tr>
                    <tr><th>PO No.</th><td> <?php	echo $this->Form->input('InitialInvoice.po_no', array('label'=>false,'readonly'=>true,'required'=>false)); ?></td></tr>
<!--                    <tr><th>JCC No.</th><td><?php	echo $this->Form->input('InitialInvoice.jcc_no', array('label'=>false,'required'=>false)); ?></td></tr>
                    <tr><th>GRN</th><td><?php	//echo $this->Form->input('InitialInvoice.grn', array('label'=>false,'readonly'=>true)); ?></td></tr>-->
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
					
					<span style="color:#FF0000">Particular</span>
				</div>
			</div>			
			<?php // echo $this->Form->create('Add',array('url'=>array('controller'=>'AddInvParticulars','action'=>'index'))); ?>
				<table class="table" border="1" style="margin-bottom:0px;">
                                            <thead>
                                                <tr>
                                                    <th>SNo</th>
                                                    <th>Particulars</th>
                                                    <th>Qty</th>
                                                    <th>Rate</th>
                                                    <th>Amount</th>
                                                    
                                                </tr>
                                            </thead>
                                            
				
				
				<?php //$this->Js->get('#performAjaxLink');
				//$this->Js->event('click',$this->Js->request(array('controller'=>'AddInvParticulars','action' => 'index'),array('async' => true, 'update' => '#mm')));
				?>
				<div id="xx"><input type='hidden' value='0' id='par_total' name='par_total'></div>
				
						<?php $idx=''; $i = 0; $total = 0;?>
						<?php  foreach ($inv_particulars as $post): ?>
							<?php $idx.=$post['Particular']['id'].',';$i++; ?>
							<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.particulars',array('label'=>false,'readonly'=>true,'value'=>$post['Particular']['particulars'])); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.qty',array('label'=>false,'readonly'=>true,'value'=>$post['Particular']['qty'],'onkeypress'=>'return isNumberKey(event)')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.rate',array('label'=>false,'readonly'=>true,'value'=>$post['Particular']['rate'])); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.amount',array('label'=>false,'value'=>$post['Particular']['amount'],'readonly'=>true)); ?></td>
							</tr>
							<?php $total += $post['Particular']['amount']; ?>
						<?php endforeach; ?><?php unset($AddInvParticular); ?>
						<?php echo $this->Form->input('a.idx',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx')); ?>
				</table>
						
</div>
	</div>
</div>
		
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-content no-padding">
                <table class="table table-striped">
                <tr><th>Total:</th>
                    <td>
                        <?php echo $this->Form->input('InitialInvoice.total',
                        array('label'=>false,'value'=>$hide['apply_service_tax']?'0':$total,'readonly'=>true)); ?>
                    </td>
                </tr>
                <?php $taxex = 1; if($hide['app_tax_cal']=='1'){

                     $taxex = 1.18;
                      if($data['GSTType']=='Integrated' && ($hide['apply_gst']=='1' || $hide['apply_gst']==1))
                      { 
                    ?>
                <tr>
                    <th>IGST @ 18%</th>
                    <td><?php echo $this->Form->input('InitialInvoice.igst', 
                        array('label'=>false,'value'=>round($total*0.18,2),'readonly'=>true)); ?>
                    </td>
                </tr>
                <?php      }
                else if($hide['apply_gst']=='1') {  ?>
                    <tr>
                    <th>CGST @ 9%</th>
                    <td><?php echo $this->Form->input('InitialInvoice.cgst', 
                        array('label'=>false,'value'=>round($total*0.09,2),'readonly'=>true)); ?>
                    </td>
                </tr>
                <tr>
                    <th>SGST @ 9%</th>
                    <td><?php echo $this->Form->input('InitialInvoice.sgst', 
                        array('label'=>false,'value'=>round($total*0.09,2),'readonly'=>true)); ?>
                    </td>
                </tr>
                <?php }
                }
                $grnd = 0;
                if($hide['apply_service_tax'])
                {
                    $grnd = round($total*($taxex-1),2);
                }
                else
                {$grnd =round($total*$taxex,2); }
                ?>

                <tr><th>Grand Total:</th><td><?php	echo $this->Form->input('InitialInvoice.grnd', 	array('label'=>false,'value'=>$grnd, 'readonly'=>true,'required'=>true)); ?></td></tr>
                <tr><td></td><td><a href="<?php echo $back_url; ?>" class="btn btn-success btn-label-left" ><b>Back</b></button></td></tr>
</table>
                            <div class="col-sm-2"></div>	
			</div>
		</div>
	</div>
</div>

<div style="display: none">
<?php

/*foreach($monthMaster as $mnt=>$mntRevenue)
{
    echo $this->Form->input('InitialInvoice.revenue_arr.'.$mnt, 	array('label'=>false,'value'=>$mntRevenue,'type'=>'hidden'));
}*/
?>
</div>


</div>
