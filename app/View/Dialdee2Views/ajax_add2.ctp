<script>
    function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode;
          if ((charCode != 46 && charCode != 45 ) && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
       function check_po_number()
    {
    var po_number = document.getElementById('InitialInvoicePoNo').value;
    if(po_number !='')
    {
        var month = document.getElementById('InitialInvoiceMonth').value;
        var grnd = document.getElementById('InitialInvoiceTotal').value;
        var finance = document.getElementById('InitialInvoiceFinanceYear').value;
        var flag = true;

        var aj = $.ajax({type:"Post",async: false,cache:false,url: "check_po_number",
            data:{po_number:po_number,month:month,grnd:grnd,finance:finance}, success: function(data)
            {
                return data;
            }
        });
        var data = aj.responseText;
        var resArr=data.split("##");
        var msg = resArr[0]; 
        var status=resArr[1]; 

        if(msg=='OK' && status==1)
        {
            
            return true;
        }
        else
        {
            alert("Po Number Not Found");
            return false;
        }
    }
    else
    {
        return true;
    }
}
function getAmount(val)
{
	var rate=document.getElementById('AddInvParticularQty').value;
	//var amount=document.getElementById('AddInvDeductParticularAmount').value;
	//document.getElementById("AddInvDeductParticularAmount").readOnly= true;
	document.getElementById('AddInvParticularAmount').value = (val*rate).toFixed(2);
	grandTotal();
}

function getBlank()
{
	document.getElementById('particulars').value = '';
	document.getElementById('AddInvParticularRate').value = '';
	document.getElementById('AddInvParticularQty').value = '';
	document.getElementById('AddInvParticularAmount').value = 0;
	location.reload();
}
function grandTotal()
{
	var Part=document.getElementById('AddInvParticularAmount').value;
	var Ded=document.getElementById('AddInvDeductParticularAmount').value; 
	var idqty=document.getElementById('idx').value;
	//var iddqty=document.getElementById('idxd').value;	
	
	var str=idqty.split(",");
	//var strd=iddqty.split(",");

	var i,total=0;
	
	for(i=0; i<str.length-1; i++)
	{
		amount="Particular"+str[i]+"Amount";
		total+=parseFloat(document.getElementById(amount).value);
	}

	/*for(i=0; i<strd.length-1; i++)
	{
		amount="DeductParticular"+strd[i]+"Amount";
		total-=parseInt(document.getElementById(amount).value);
	}*/
        var Total = 0,serviceTax=0,GST=0;
	try{
            serviceTax = '0';
            
            if(serviceTax=='0')
            Total = document.getElementById('InitialInvoiceTotal').value = ((parseFloat(total)+parseFloat(Part))-parseFloat(Ded)).toFixed(2);
            else
            {Total = ((parseFloat(total)+parseFloat(Part))-parseFloat(Ded)).toFixed(2);}
        }
        catch(err){Total = ((parseFloat(total)+parseFloat(Part))-parseFloat(Ded)).toFixed(2);}
        document.getElementById('InitialInvoiceTotal').value=Total;
	GST = document.getElementById('InitialInvoiceApplyGst').value;
        
        var IGST=0,CGST=0,SGST=0;
        
        if(GST==1)
        {
           var GSTType = document.getElementById('InitialInvoiceGSTType').value;
           
           if(GSTType=='Integrated')
           {
               try{ IGST = document.getElementById('InitialInvoiceIgst').value = ((Total*18)/100).toFixed(2); }	
                catch(err)
                { var IGST = 0; }
           }
           else
           {
               try{ CGST = document.getElementById('InitialInvoiceCgst').value = ((Total*9)/100).toFixed(2); }	
                catch(err)
                { var CGST = 0; }
                
                try{ SGST = document.getElementById('InitialInvoiceSgst').value = ((Total*9)/100).toFixed(2); }	
                catch(err)
                { var SGST = 0; }
           }
           if(serviceTax=='1')
        {Total = 0;}
           document.getElementById('InitialInvoiceGrnd').value = (parseFloat(Total)+parseFloat(IGST)+parseFloat(CGST)+parseFloat(SGST)).toFixed(2);
        }
        
	
	
}

function add_part1()
{
    var particulars=document.getElementById('particulars').value;
    var cost_center_id=document.getElementById('AddInvParticularCostCenterId').value;
    var branch_name=document.getElementById('AddInvParticularBranchName').value;
    var cost_center=document.getElementById('AddInvParticularCostCenter').value;
    var fin_year=document.getElementById('AddInvParticularFinYear').value;
    var month_for=document.getElementById('AddInvParticularMonthFor').value;
    var rate=document.getElementById('AddInvParticularRate').value;
    var qty=document.getElementById('AddInvParticularQty').value;
    var amount=document.getElementById('AddInvParticularAmount').value;
    
    var params = "particulars="+particulars+'&cost_center_id='+cost_center_id+'&branch_name='+branch_name+'&cost_center='+cost_center;
    params += '&fin_year='+fin_year+'&month_for='+month_for+'&rate='+rate+'&qty='+qty+'&amount='+amount

    var xmlHttpReq = false;	
				if (window.XMLHttpRequest)
				{
				xmlHttpReq = new XMLHttpRequest();
				}
				else if (window.ActiveXObject)
				{
				xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlHttpReq.onreadystatechange = function()
				{
				if (xmlHttpReq.readyState == 4)
				{     //alert(xmlHttpReq.responseText);
					getBlank();
                    //location.reload();
                    return false;
				}
				} 
				xmlHttpReq.open('POST','/invoices/add_part1?'+params,true);
                                
				xmlHttpReq.send(null);
    
}

function deletes2(val)
{
	var flag = delete_part2(val);
	return false;	
}

function delete_part2(id)
{
	var xmlHttpReq = false;	
				if (window.XMLHttpRequest)
				{
				xmlHttpReq = new XMLHttpRequest();
				}
				else if (window.ActiveXObject)
				{
				xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlHttpReq.onreadystatechange = function()
				{
				if (xmlHttpReq.readyState == 4)
				{     //alert(xmlHttpReq.responseText);
					location.reload();
				}
				} 
				xmlHttpReq.open('POST','/invoices/delete_particular2?id='+id,true);
                                
				xmlHttpReq.send(null);
		
}


function getAmount1(val)
{
	rate=document.getElementById(val).value;
	
	var numberPattern=/\d+/g;
	val=val.match(numberPattern);
	
	qty="Particular"+val+"Qty";
	qty=document.getElementById(qty).value;
	
	amount="Particular"+val+"Amount";
	document.getElementById(amount).value=(parseFloat(qty)*parseFloat(rate)).toFixed(2);
	getTotal1();
}
function getTotal1()
{
	
	var idqty=document.getElementById('idx').value;
	

	var iddqty;
	try
	{
	iddqty = 0;	//iddqty=document.getElementById('idxd').value;
	}
	catch(err)
	{
		iddqty = 0;
	}
	var amt;
	try
	{
	 amt=parseFloat(document.getElementById('AddInvParticularAmount').value);
	}
	catch(err)
	{
		amt = 0;
	}
	var ded;
	try
	{
	 ded=parseFloat(document.getElementById('AddInvDeductParticularAmount').value);
	}
	catch(err)
	{
		ded = 0;
	}
	
	var str=idqty.split(",");
	//var strd=iddqty.split(",");
	
	var i,total=0;
	
	for(i=0; i<str.length-1; i++)
	{
		amount="Particular"+str[i]+"Amount";
		total+=parseFloat(document.getElementById(amount).value);
	}

	/*for(i=0; i<strd.length-1; i++)
	{
		amount="DeductParticular"+strd[i]+"Amount";
		total-=parseInt(document.getElementById(amount).value);
	}*/
        
	total = total +amt - ded;
        
        var IGST=0,CGST=0,SGST=0;
        var GST = document.getElementById('InitialInvoiceApplyGst').value;
        if(GST==1)
        {
           var GSTType = document.getElementById('InitialInvoiceGSTType').value;
           
           if(GSTType=='Integrated')
           {
               try{ IGST = document.getElementById('InitialInvoiceIgst').value = ((total*18)/100).toFixed(2); }	
                catch(err)
                { var IGST = 0; }
           }
           else
           {
               try{ CGST = document.getElementById('InitialInvoiceCgst').value = ((total*9)/100).toFixed(2); }	
                catch(err)
                { var CGST = 0; }
                
                try{ SGST = document.getElementById('InitialInvoiceSgst').value = ((total*9)/100).toFixed(2); }	
                catch(err)
                { var SGST = 0; }
           }
           document.getElementById('InitialInvoiceTotal').value = parseFloat(total).toFixed(2);
           document.getElementById('InitialInvoiceGrnd').value = (parseFloat(total)+parseFloat(IGST)+parseFloat(CGST)+parseFloat(SGST)).toFixed(2);
        }
        
}
</script>

<?php 
//print_r($cost_master); exit;
echo $this->Form->create('InitialInvoice',array('class'=>'form-horizontal','action'=>'bill_approval')); ?>
						<?php $data=$cost_master['CostCenterMaster']; ?>
						



<div class="row">
    <div class="col-md-12">
        
            <div class="panel-body" style="margin-top:-10px;padding:0px;">
					<!--	creating hide array for particulars table and hidden fields -->
                                        <table class="table" style="margin-bottom:0px;">
                <tr align="center">
                        <th>Branch</th>
                        <th>Cost Center</th>
                        <th>Financial Year</th>
                        <th>For the Month</th>
                        <th>GST No.</th>
                        <th>Vendor GST No.</th>
                </tr>
                <tr align="left">
                        <td><?php echo $data['branch']; $hide['branch_name']=$data['branch']; ?></td>
                        <td><?php echo $this->params->query['cost_center'];
                 $hide['cost_center']=$this->params->query['cost_center']; ?></td>
                        <td><?php echo $this->params->query['finance_year'];
                                $hide['finance_year']=$this->params->query['finance_year'];?>
                        </td>
                        <td><?php echo $this->params->query['month'];
                                $hide['month_for']=$this->params->query['month'];?>
                        </td>
                        <td><?php echo $cost_master['CostCenterMaster']['ServiceTaxNo'];?>
                        </td>
                        <td><?php echo $cost_master['CostCenterMaster']['VendorGSTNo'];?>
                        </td>
                </tr>
                <?php
                         $hide['cost_center_id']=$data['id'];
                         $date = date('Y-m-d');
                        // $date = '2023-03-31';
                         $date=date_create($date);
                         $date=date_format($date,"Y-m-d");
                         $hide['invoiceDate']=$date;
                         $hide['app_tax_cal']=1;
                         $hide['apply_service_tax']=0;
                         $hide['apply_krishi_tax']=0;
                         $hide['apply_gst']=1;
                         $hide['invoiceDescription']=$this->params->query['invoiceDescription'];
                 ?>
        </table>				
            </div>
        
    </div>
</div>
<div class="row">
<div class="col-md-4">
          
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
<div class="col-md-4">
    
        
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
<div class="col-md-4">
    
        
            <table class="table"  style="margin-bottom:0px;">
                <tbody>
                    <tr><th>Date</th><td><?php $date=date_create($hide['invoiceDate']); echo date_format($date,"d-M-Y"); ?></td></tr>
                    <tr><th>Due Date</th><td> <?php	echo $this->Form->input('InitialInvoice.due_date', array('label'=>false,'readonly'=>true,'required'=>true,'value'=>$due_date)); ?></td></tr>
                    <tr><th>Description</th><td><?php	echo $this->Form->input('InitialInvoice.invoiceDescription', array('label'=>false,'value'=>$this->params->query['category'],'required'=>false)); ?></td></tr>
                    <?php if(!empty($start_date)) { ?>
                    <tr><th colspan="2"><?php echo "For Subscription From ". date_format(date_create($start_date),"d-M-Y")." to ". date_format(date_create($end_date),"d-M-Y")."";?></th></tr>
                    <?php } ?>
                </tbody>
            </table>
    
    
</div>    
</div>

<div class="row">
	<div class="col-md-12">
		
					
			<?php // echo $this->Form->create('Add',array('url'=>array('controller'=>'AddInvParticulars','action'=>'index'))); ?>
				<table class="table" border="1" style="margin-bottom:0px;">
                                            <thead>
                                                <tr>
                                                    <th>SNo</th>
                                                    <th>Particulars</th>
                                                    <th>Qty</th>
                                                    <th>Rate</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <?php $edit_tail = false; if(strtolower($category)!=strtolower('subscription')) { $edit_tail = true; ?>
                                            <tbody>
                                                <tr>
                                                        <td></td>
                                                        <td><?php   echo $this->Form->input('AddInvParticular.particulars',
                                                         array('label'=>false,'placeholder'=>'Particulars','id'=>'particulars')); ?></td>
                                                        <td><?php   echo $this->Form->input('AddInvParticular.qty', array('label'=>false,"style"=>"text-align:right;",'placeholder'=>'Qty','onKeypress'=>'return isNumberKey(event)')); ?></td>
                                                        <td><?php   echo $this->Form->input('AddInvParticular.rate', array('label'=>false,"style"=>"text-align:right;",'placeholder'=>'Rate','onBlur'=>'getAmount(this.value)','onKeypress'=>'return isNumberKey(event)')); ?></td>
                                                        <td><?php   echo $this->Form->input('AddInvParticular.amount', array('label'=>false,"style"=>"text-align:right;",'value'=>0,'placeholder'=>'Amount','readonly'=>true)); ?></td>
                                                        <td><button type="button" onClick ="return add_part1()" class="btn btn-primary">ADD</button></td>
                                                </tr>
                                            </tbody>
                                            <?php } ?>
				
				<?php //$this->Js->get('#performAjaxLink');
				//$this->Js->event('click',$this->Js->request(array('controller'=>'AddInvParticulars','action' => 'index'),array('async' => true, 'update' => '#mm')));
				?>
				<div id="xx"><input type='hidden' value='0' id='par_total' name='par_total'></div>
				
						<?php $idx=''; $i = 0; $total = 0;?>
						<?php  foreach ($tmp_particulars as $post): 
                             $idx.=$post['AddInvParticular']['id'].',';$i++;
                             $action = true;
                             //print_r($post);
                             //continue;
                             $edit_tail = false;
                             if(strtolower($post['AddInvParticular']['sub_category'])==strtolower('subscription'))
                             {
                                $edit_tail = true;
                             }
                             if(strtolower($post['AddInvParticular']['sub_category'])==strtolower('subscription') || strtolower($post['AddInvParticular']['sub_category'])==strtolower('credit'))
                             {
                                $action = false;
                             }
                              ?>
							<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['AddInvParticular']['id'].'.particulars',array('label'=>false,'value'=>$post['AddInvParticular']['particulars'])); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['AddInvParticular']['id'].'.qty',array('label'=>false,'readonly'=>$edit_tail,"style"=>"text-align:right;",'value'=>$post['AddInvParticular']['qty'],'onkeypress'=>'return isNumberKey(event)')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['AddInvParticular']['id'].'.rate',array('label'=>false,'readonly'=>$edit_tail,"style"=>"text-align:right;",'value'=>$post['AddInvParticular']['rate'],'onBlur'=>'getAmount1(this.id)','onkeypress'=>'return isNumberKey(event)')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['AddInvParticular']['id'].'.amount',array('label'=>false,"style"=>"text-align:right;",'value'=>$post['AddInvParticular']['amount'],'readonly'=>true)); ?></td>
							<td> <?php //if($action) { ?> <button name = "Delete" class="btn btn-danger" value="<?php echo $post['AddInvParticular']['id']; ?>" onClick ="return deletes2(this.value)">Delete</button> <?php //} ?> </td>
							</tr>
							<?php $total += $post['AddInvParticular']['amount']; ?>
						<?php endforeach; ?><?php unset($AddInvParticular); ?>
						<?php echo $this->Form->input('a.idx',array('label'=>false,'value'=>$idx,'type'=>'hidden','id'=>'idx')); ?>
				</table>
				<?php	echo $this->Form->input('AddInvParticular.username', 		array('label'=>false,'value'=>$username,'type'=>'hidden')); ?>
				<?php	echo $this->Form->input('AddInvParticular.cost_center_id', 	array('label'=>false,'value'=>$hide['cost_center_id'],'type'=>'hidden')); ?>
				
				<?php	echo $this->Form->input('AddInvParticular.branch_name', 	array('label'=>false,'value'=>$hide['branch_name'],'type'=>'hidden')); ?>
				<?php	echo $this->Form->input('AddInvParticular.cost_center', 	array('label'=>false,'value'=>$hide['cost_center'],'type'=>'hidden')); ?>
				<?php	echo $this->Form->input('AddInvParticular.fin_year',		array('label'=>false,'value'=>$hide['finance_year'],'type'=>'hidden')); ?>
				<?php	echo $this->Form->input('AddInvParticular.month_for', 		array('label'=>false,'value'=>$hide['month_for'],'type'=>'hidden')); ?>
				<?php // 	echo $this->Form->end(); ?>		

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
                        array('label'=>false,'value'=>number_format($total,2,",","."),"style"=>"text-align:right;",'readonly'=>true)); ?>
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
                        array('label'=>false,'value'=>number_format(round($total*0.18,2),2,",","."),"style"=>"text-align:right;",'readonly'=>true)); ?>
                    </td>
                </tr>
                <?php      }
                else if($hide['apply_gst']=='1') {  ?>
                    <tr>
                    <th>CGST @ 9%</th>
                    <td><?php echo $this->Form->input('InitialInvoice.cgst', 
                        array('label'=>false,'value'=>number_format(round($total*0.09,2),2,",","."),"style"=>"text-align:right;",'readonly'=>true)); ?>
                    </td>
                </tr>
                <tr>
                    <th>SGST @ 9%</th>
                    <td><?php echo $this->Form->input('InitialInvoice.sgst', 
                        array('label'=>false,'value'=>number_format(round($total*0.09,2),2,",","."),"style"=>"text-align:right;",'readonly'=>true)); ?>
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

                <tr><th>Grand Total:</th><td><?php	echo $this->Form->input('InitialInvoice.grnd', 	array('label'=>false,"style"=>"text-align:right;",'value'=>number_format($grnd,2), 'readonly'=>true,'required'=>true)); ?></td></tr>
                <tr><td></td><td><button type="submit" style="background-color: #607d8b;"  class="btn btn-success btn-label-left" onClick="return validate()"><b>Submit</b></button></td></tr>
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
<?php	echo $this->Form->input('InitialInvoice.category', 	array('label'=>false,'id'=>"category",'value'=>$category,'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.revenue', 	array('label'=>false,'id'=>"revenue",'value'=>$revenue,'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.invoiceType', 	array('label'=>false,'value'=>$invoiceType,'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.branch_name', 	array('label'=>false,'value'=>$hide['branch_name'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.cost_center', 	array('label'=>false,'value'=>$hide['cost_center'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.finance_year',		array('label'=>false,'value'=>$hide['finance_year'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.month', 		array('label'=>false,'value'=>$hide['month_for'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.invoiceDate', 		array('label'=>false,'value'=>$hide['invoiceDate'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.app_tax_cal', 		array('label'=>false,'value'=>$hide['app_tax_cal'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.apply_service_tax', 		array('label'=>false,'value'=>$hide['apply_service_tax'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.apply_krishi_tax', 		array('label'=>false,'value'=>$hide['apply_krishi_tax'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('InitialInvoice.apply_gst', 		array('label'=>false,'value'=>$hide['apply_gst'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('GSTType', 		array('label'=>false,'value'=>$data['GSTType'],'type'=>'hidden')); ?>
<?php	echo $this->Form->input('subs_start_date', 		array('label'=>false,'value'=>$start_date,'type'=>'hidden')); ?>
<?php	echo $this->Form->input('subs_end_date', 		array('label'=>false,'value'=>$end_date,'type'=>'hidden')); ?>
<?php echo $this->Form->end(); ?>

