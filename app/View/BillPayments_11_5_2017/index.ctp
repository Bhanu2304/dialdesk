<?php ?>
<script>
function getPlan(PlanId){
    var ClientId=$("#ClientId").val();    
    $.post("<?php echo $this->webroot;?>BillPayments/getplan",{ClientId:ClientId,PlanId:PlanId},function(data){
        if(data ==""){
            alert('Please select correct plan.');
            document.getElementById('PlanId').selectedIndex = 0;
            $("#PlanId").focus();
            return false;
        }
    });
}

function getPlanType(PlanType){
    var ClientId=$("#ClientId").val();
    var PlanId=$("#PlanId").val();
    
    if(PlanId ==""){
        alert('Please select plan.');
        document.getElementById('PlanType').selectedIndex = 0;
        $("#PlanId").focus();
        return false;
    }
    
    $.post("<?php echo $this->webroot;?>BillPayments/getplantype",{ClientId:ClientId,PlanId:PlanId,PlanType:PlanType},function(data){
        if(data ==""){
            alert('Please select correct plan type.');
            document.getElementById('PlanType').selectedIndex = 0;
            $("#PlanType").focus();
            return false;
        }
    });
}

$(document).ready(function(){
    <?php if(!empty($data)){?>
    var tp="<?php echo $data['PaymentType'];?>";
    paymentType(tp);
    <?php }?>
});


function paymentType(type){
    $(".check-details,#BankName,#AccountNo,#ChequeNo,#Pay").hide();
    $(".trans-details,#TransactionNo").hide();
    <?php if(empty($data)){?>
    $("#BankName,#AccountNo,#ChequeNo,#Pay,#TransactionNo").val('');
    <?php }?>
    
    if(type =="Cheque"){
        $(".check-details,#BankName,#AccountNo,#ChequeNo,#Pay").show();
        $("#TransactionNo").removeAttr('required');
        document.getElementById("BankName").required = true;
        document.getElementById("AccountNo").required = true;
        document.getElementById("ChequeNo").required = true;
        document.getElementById("Pay").required = true;
        document.getElementById("TransactionNo").required = false;
    }
    else if(type =="Online"){
        $(".trans-details,#TransactionNo").show();
        document.getElementById("BankName").required = false;
        document.getElementById("AccountNo").required = false;
        document.getElementById("ChequeNo").required = false;
        document.getElementById("Pay").required = false;
        document.getElementById("TransactionNo").required = true;
    }      
}

function getPaymentDetals(BillNo){
    var ClientId=$("#ClientId").val();
    window.location.href = "<?php echo $this->webroot;?>BillPayments/?cid="+ClientId+"&BillNo="+BillNo;
}


function getBillNo(clientid){    
    $.post("<?php echo $this->webroot;?>BillPayments/getbillno",{clientid:clientid},function(data){
        $("#BillNo").html(data);
    });
}



function checkCharacter(e,t) {
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {         
        return false;
        }
         return true;

    }
    catch (err) {
        alert(err.Description);
    }
}
</script>

<style>
.check-details{display: none;}
.trans-details{display: none;}

</style>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Bill Payment</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>BillPayments">Payment Submition</a></li>
</ol>
<!--
<div class="page-heading">            
    <h1>Payment Submition</h1>
</div>
-->

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Payment Submition</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">
                 <?php echo $this->Form->create('BillPayments',array('action'=>'payment_submition')); ?> 
                    
                    <div id="error" style="color:green;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                    
                    <div class="form-group">
                        <!--
                        <label class="col-sm-1 control-label">Bill Month</label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('BillMonth',array('label'=>false,'options'=>$month,'empty'=>'Bill Month','value'=>isset($BillMonth)?$BillMonth:"",'id'=>'month','class'=>'form-control','required'=>true));?>
                        </div>
                        <label class="col-sm-1 control-label">Bill Year</label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('BillYear',array('label'=>false,'options'=>$year,'empty'=>'Bill Year','value'=>isset($BillYear)?$BillYear:"",'id'=>'year','class'=>'form-control','required'=>true));?>
                        </div>
                        -->
                        
                        <label class="col-sm-1 control-label">Client</label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('ClientId',array('label'=>false,'options'=>$ClientName,'empty'=>'Select Client','value'=>isset($BillClient)?$BillClient:"",'onchange'=>'getBillNo(this.value)','id'=>'ClientId','class'=>'form-control','required'=>true));?>
                        </div>
                        
                        <label class="col-sm-1 control-label">Bill Month</label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('BillNo',array('label'=>false,'options'=>isset($BillList)?$BillList:'','empty'=>'Select Bill Month','value'=>isset($BillNo)?$BillNo:"",'id'=>'BillNo','onchange'=>'getPaymentDetals(this.value)','class'=>'form-control','required'=>true));?>
                        </div>
                        
                        <label class="col-sm-1 control-label">Plan</label>
                        <input type="hidden" name="data[BillPayments][PaymentId]" value="<?php echo isset($data['Id'])?$data['Id']:"";?>" >
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('PlanId',array('label'=>false,'options'=>$PlanName,'empty'=>'Select PlanName','onchange'=>'getPlan(this.value)','value'=>isset($data['PlanId'])?$data['PlanId']:"",'id'=>'PlanId','class'=>'form-control','required'=>true)); ?>
                        </div>
                    </div>
                    
                    
                   
                    <div class="form-group">
                        <div class="col-sm-12">
                            <hr/>
                        </div>
                    </div>
       
                    <div class="form-group">
                        
                        <label class="col-sm-1 control-label">Plan Type</label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('PlanType',array('label'=>false,'options'=>array('Prepaid'=>'Prepaid','Postpaid'=>'Postpaid'),'onchange'=>'getPlanType(this.value)','value'=>isset($data['PlanType'])?$data['PlanType']:"",'empty'=>'Select Plan Type','id'=>'PlanType','class'=>'form-control','required'=>true)); ?> 
                        </div>
                        <label class="col-sm-1 control-label">Payment Type</label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('PaymentType',array('label'=>false,'options'=>array('Cheque'=>'Cheque','Online'=>'Online'),'empty'=>'Payment Type','value'=>isset($data['PaymentType'])?$data['PaymentType']:"",'onchange'=>'paymentType(this.value)','class'=>'form-control','required'=>true)); ?> 
                        </div>
                        
                        <div class="check-details">
                            <label class="col-sm-1 control-label">Bank</label>
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('BankName',array('label'=>false,'options'=>$bank,'empty'=>'Select Bank','value'=>isset($data['BankName'])?$data['BankName']:"",'id'=>'BankName','style'=>'display:none;','class'=>'form-control','required'=>true)); ?>
                            </div>
                            <label class="col-sm-1 control-label">Account No</label>
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('AccountNo',array('label'=>false,'placeholder'=>'Account No','onkeypress'=>'return checkCharacter(event,this)','value'=>isset($data['AccountNo'])?$data['AccountNo']:"",'id'=>'AccountNo','style'=>'display:none;','class'=>'form-control','required'=>true)); ?> 
                            </div>
                            <label class="col-sm-1 control-label">Check No</label>
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('ChequeNo',array('label'=>false,'placeholder'=>'Cheque No','onkeypress'=>'return checkCharacter(event,this)','value'=>isset($data['ChequeNo'])?$data['ChequeNo']:"",'id'=>'ChequeNo','style'=>'display:none;','class'=>'form-control','required'=>true)); ?> 
                            </div>
                            <label class="col-sm-1 control-label">Pay</label>
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('PayName',array('label'=>false,'placeholder'=>'Pay Name','value'=>isset($data['PayName'])?$data['PayName']:"",'id'=>'Pay','style'=>'display:none;','class'=>'form-control','required'=>true)); ?> 
                            </div>
                        </div>
                    
                        <div class="trans-details">
                            <label class="col-sm-1 control-label">Transaction No</label>
                            <div class="col-sm-3">
                                <?php echo $this->Form->input('TransactionNo',array('label'=>false,'placeholder'=>'Transaction No','onkeypress'=>'return checkCharacter(event,this)','value'=>isset($data['TransactionNo'])?$data['TransactionNo']:"",'id'=>'TransactionNo','style'=>'display:none;','class'=>'form-control','required'=>true)); ?> 
                            </div>
                        </div>
                    
                        <label class="col-sm-1 control-label">Pay Amount</label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('PayAmount',array('label'=>false,'placeholder'=>'Pay Amount','id'=>'PayAmount','value'=>isset($data['PayAmount'])?$data['PayAmount']:"",'class'=>'form-control','required'=>true)); ?> 
                        </div>
                        
                        <label class="col-sm-1 control-label">Pay Date</label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('PayDate',array('label'=>false,'placeholder'=>'Select Pay Date','id'=>'PayDate','value'=>isset($data['PayDate'])?$data['PayDate']:"",'class'=>'form-control date-picker','required'=>true)); ?> 
                        </div> 
                    </div>
       
                    <div class="form-group">
                        <label class="col-sm-1 control-label">&nbsp;</label>
                        <div class="col-sm-12">
                            <input type="submit" value="submit" class="btn-web btn pull-right"> 
                        </div>
                    </div>    
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

