<?php //print_r($inv_particulars);exit; ?>
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
	var rate=document.getElementById('ParticularQty').value;
	//var amount=document.getElementById('AddInvDeductParticularAmount').value;
	//document.getElementById("AddInvDeductParticularAmount").readOnly= true;
	document.getElementById('ParticularAmount').value = (val*rate).toFixed(2);
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
	var Part=document.getElementById('ParticularAmount').value;
	var Ded=0;
	var idqty=document.getElementById('idx').value;
	//var iddqty=document.getElementById('idxd').value;	
	
	var str=idqty.split(",");
	//var strd=iddqty.split(",");

	var i,total=0;
	var amount = 0;
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
            
            if(serviceTax==='0')
            Total = document.getElementById('InitialInvoiceTotal').value = ((parseFloat(total)+parseFloat(Part))-parseFloat(Ded)).toFixed(2);
            else
            {Total = ((parseFloat(total)+parseFloat(Part))-parseFloat(Ded)).toFixed(2);}
        }
        catch(err){Total = ((parseFloat(total)+parseFloat(Part))-parseFloat(Ded)).toFixed(2);}
        document.getElementById('InitialInvoiceTotal').value=Total;
	GST = document.getElementById('InitialInvoiceApplyGst').value;
        
        var IGST=0,CGST=0,SGST=0;
        
        if(GST===1)
        {
           var GSTType = document.getElementById('InitialInvoiceGSTType').value;
           
           if(GSTType==='Integrated')
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
           if(serviceTax==='1')
        {Total = 0;}
           document.getElementById('InitialInvoiceGrnd').value = (parseFloat(Total)+parseFloat(IGST)+parseFloat(CGST)+parseFloat(SGST)).toFixed(2);
        }
        
	
	
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
				xmlHttpReq.open('POST','delete_particular2/?id='+id,true);
                                
				xmlHttpReq.send(null);
		
}


function getAmount1(val)
{
	var rate=document.getElementById(val).value;
	
	var numberPattern=/\d+/g;
	val=val.match(numberPattern);
	
	var qty="Particular"+val+"Qty";
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
        if(GST===1)
        {
           var GSTType = document.getElementById('InitialInvoiceGSTType').value;
           
           if(GSTType==='Integrated')
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

function validate()
{
	//var check=document.getElementById('par_total').value;
	var idqty=document.getElementById('idx').value;
	var check1=document.getElementById('InitialInvoiceGrnd').value;
	var check2=document.getElementById('AddInvParticularRate').value;	
	
	
	check2=""+check2;
	check3=""+check3;
	
	if(check2!="")
	{
		alert("You did not added Particular amount, Please click Add Button");
		return false;
	}

	if(check1<0)
	{
		alert("Deduction Amount is not Greater Then Particulars Amount");
		return false;
	}	

	

	var i,total=0,amount = '';
	var str=idqty.split(",");
	for(i=0; i<str.length-1; i++)
	{
		amount="Particular"+str[i]+"Amount";
		try{total+=parseInt(document.getElementById(amount).value);}
		catch(err)		
		{
			total += 0;
		}
	}

	if(total<1)
	{
		alert("Please Add Particular");
		return false;
	}
	//return check_provision();
}

function add_part(val)
{
	var initial_id = document.getElementById('InitialInvoiceId').value;
    var service = document.getElementById('service').value;
	var particular = document.getElementById('particulars').value;
	var rate = document.getElementById('ParticularRate').value;
	var qty = document.getElementById('ParticularQty').value;
	var amount = document.getElementById('ParticularAmount').value;
	
	if(particular === '')
	{alert("Please Fill Particular");
		return false;
	}

	if(rate === '')
	{alert("Please Fill Rate");
		return false;
	}
	if(qty === '')
	{alert("Please Fill Quantity");
	return false;
	}

	if(amount === '')
	{alert("Please Click to Rate to Calculate");
	return false;
	}

	get_part(particular,rate,qty,amount,initial_id);
	return false;
}

function get_part(particular,rate,qty,amount,initial_id)
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
        if (xmlHttpReq.readyState === 4)
        {     //alert(xmlHttpReq.responseText);
                 location.reload();
        }
    }; 
    xmlHttpReq.open('POST','add_part?particular='+particular+'&rate='+rate+'&qty='+qty+'&amount='+amount+'&initial_id='+initial_id+'&service_id='+service,true);
    xmlHttpReq.send(null);
		
}

</script>

			<?php //print_r($tbl_invoice); exit; ?>
<?php echo $this->Form->create('InitialInvoice',array('class'=>'form-horizontal','action'=>'update_bill')); 
  foreach ($tbl_invoice as $post){
 $data=$post; 
}  unset($InitialInvoice); ?>

<?php  foreach ($cost_master as $post): ?>
<?php $data=$post; ?>
<?php endforeach; ?><?php unset($CostCenterMaster); ?>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a >Invoice</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>Invoice">Approve Invoice</a></li>
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
                    <tr><th>Proforma Bill No.</th><td><?php echo $tbl_invoice['InitialInvoice']['proforma_bill_no']; ?></td></tr>
                    <tr><th>Date</th><td><?php //$date=date_create($hide['invoiceDate']); echo date_format($date,"d-M-Y"); ?>
                    <input type="date" id="FromDate" name="data[InitialInvoice][invoiceDate]" value="<?php echo $hide['invoiceDate'];?>" placeholder="First Date" required="" class="date-picker hasDatepicker"></td></tr>
                    <tr><th>Due Date</th><td> <?php	echo $this->Form->input('InitialInvoice.due', array('label'=>false,'value'=>$tbl_invoice['InitialInvoice']['due_date'],'required'=>false)); ?></td></tr>
                    <?php if(!empty($tbl_invoice['InitialInvoice']['subs_start_date'])) { ?>
                    <tr><th colspan="2"><?php echo "For Subscription From ". date_format(date_create($tbl_invoice['InitialInvoice']['subs_start_date']),"d-M-Y")." to ". date_format(date_create($tbl_invoice['InitialInvoice']['subs_end_date']),"d-M-Y")."";?></th></tr>
                    <?php } ?>
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
					<i class="fa fa-table"></i>
					
				</div>
			</div>			
			<?php // echo $this->Form->create('Add',array('url'=>array('controller'=>'AddInvParticulars','action'=>'index'))); ?>
				<table class="table" border="1" style="margin-bottom:0px;">
                                            <thead>
                                                <tr>
                                                    <th>SNo</th>
                                                    <th>Service</th>
                                                    <th>Particulars</th>
                                                    <th>Qty</th>
                                                    <th>Rate</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                        <td></td>
                                                        <td>
                                                        <?php   echo $this->Form->input('AddInvParticular.service',
                                                         array('label'=>false,'empty'=>'Service','options'=>$service_list,'id'=>'service')); ?>
                                                        </td>
                                                        <td><?php   echo $this->Form->input('Particular.particulars',
                                                         array('label'=>false,'placeholder'=>'Particulars','id'=>'particulars')); ?></td>
                                                        <td><?php   echo $this->Form->input('Particular.qty', array('label'=>false,'placeholder'=>'Qty','onKeypress'=>'return isNumberKey(event)')); ?></td>
                                                        <td><?php   echo $this->Form->input('Particular.rate', array('label'=>false,'placeholder'=>'Rate','onBlur'=>'getAmount(this.value)','onKeypress'=>'return isNumberKey(event)')); ?></td>
                                                        <td><?php   echo $this->Form->input('Particular.amount', array('label'=>false,'value'=>0,'placeholder'=>'Amount','readonly'=>true)); ?></td>
                                                        <td><input  id="p1" type="submit" value="ADD" onClick="return add_part(this.value)"></td>
                                                </tr>
                                            </tbody>
				
				
				<?php //$this->Js->get('#performAjaxLink');
				//$this->Js->event('click',$this->Js->request(array('controller'=>'AddInvParticulars','action' => 'index'),array('async' => true, 'update' => '#mm')));
				?>
				<div id="xx"><input type='hidden' value='0' id='par_total' name='par_total'></div>
				
						<?php $idx=''; $i = 0; $total = 0;?>
						<?php  foreach ($inv_particulars as $post): 
                                $idx.=$post['Particular']['id'].',';$i++;
                                $action = true;
                                //print_r($post);
                                //continue;
                                $edit_tail = false;
                                if(strtolower($post['Particular']['sub_category'])==strtolower('subscription'))
                                {
                                   $edit_tail = true;
                                }
                                if(strtolower($post['Particular']['sub_category'])==strtolower('subscription') || strtolower($post['Particular']['sub_category'])==strtolower('credit'))
                                {
                                   $action = false;
                                }
                              ?>
							<tr>
							<td><?php echo $i;?></td>
                            <td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.service',array('label'=>false,'options'=>$service_list,'value'=>$post['Particular']['service_id'])); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.particulars',array('label'=>false,'value'=>$post['Particular']['particulars'])); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.qty',array('label'=>false,"style"=>"text-align:right;",'readonly'=>$edit_tail,'value'=>$post['Particular']['qty'],'onkeypress'=>'return isNumberKey(event)')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.rate',array('label'=>false,"style"=>"text-align:right;",'readonly'=>$edit_tail,'value'=>$post['Particular']['rate'],'onBlur'=>'getAmount1(this.id)','onkeypress'=>'return isNumberKey(event)')); ?></td>
							<td><?php echo $this->Form->input('Particular.'.$post['Particular']['id'].'.amount',array('label'=>false,"style"=>"text-align:right;",'value'=>$post['Particular']['amount'],'readonly'=>true)); ?></td>
							<td> <?php if($action) { ?> <button name = "Delete" class="btn btn-danger" value="<?php echo $post['AddInvParticular']['id']; ?>" onClick ="return deletes2(this.value)">Delete</button> <?php } ?> </td>
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
                    <td colspan="2">
                        <?php echo $this->Form->input('InitialInvoice.total',
                        array('label'=>false,"style"=>"text-align:right;",'value'=>$hide['apply_service_tax']?'0':$total,'readonly'=>true)); ?>
                    </td>
                </tr>
                <?php $taxex = 1; if($hide['app_tax_cal']=='1'){

                     $taxex = 1.18;
                      if($data['GSTType']=='Integrated' && ($hide['apply_gst']=='1' || $hide['apply_gst']==1))
                      { 
                    ?>
                <tr>
                    <th>IGST @ 18%</th>
                    <td  colspan="2"><?php echo $this->Form->input('InitialInvoice.igst', 
                        array('label'=>false,"style"=>"text-align:right;",'value'=>round($total*0.18,2),'readonly'=>true)); ?>
                    </td>
                </tr>
                <?php      }
                else if($hide['apply_gst']=='1') {  ?>
                    <tr>
                    <th>CGST @ 9%</th>
                    <td colspan="2"><?php echo $this->Form->input('InitialInvoice.cgst', 
                        array('label'=>false,"style"=>"text-align:right;",'value'=>round($total*0.09,2),'readonly'=>true)); ?>
                    </td>
                </tr>
                <tr>
                    <th>SGST @ 9%</th>
                    <td colspan="2"><?php echo $this->Form->input('InitialInvoice.sgst', 
                        array('label'=>false,"style"=>"text-align:right;",'value'=>round($total*0.09,2),'readonly'=>true)); ?>
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

                <tr><th>Grand Total:</th>
                <td colspan="2"><?php   echo $this->Form->input('InitialInvoice.grnd', array('label'=>false,"style"=>"text-align:right;",'value'=>$grnd, 'readonly'=>true,'required'=>true)); ?></td></tr>
                <tr>
                    <td><button type="submit" name="Approve" value="Reject" class="btn btn-danger btn-label-left"><b>Reject</b></button></td>
                    <td><button type="submit" name="Approve" value="Approve" class="btn btn-primary" onClick="return validate();"><b>Approve</b></button></td>
                    <td><button type="submit" name="Approve" value="Update" class="btn btn-success btn-label-right" onClick="return validate();"><b>Update</b></button></td>
                </tr>
            </table>
                            	
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
<?php	echo $this->Form->input('InitialInvoice.revenue',array('label'=>false,'id'=>"revenue",'value'=>$revenue,'type'=>'hidden')); ?>
<?php 
echo $this->Form->input('InitialInvoice.id',array('label'=>false,'value'=>$tbl_invoice['InitialInvoice']['id'],'type'=>'hidden'));
echo $this->Form->input('InitialInvoice.GSTType',array('label'=>false,'value'=>$dataX['GSTType'],'type'=>'hidden'));
echo $this->Form->input('InitialInvoice.apply_gst',array('label'=>false,'value'=>$tbl_invoice['InitialInvoice']['apply_gst'],'type'=>'hidden'));
?>

<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>