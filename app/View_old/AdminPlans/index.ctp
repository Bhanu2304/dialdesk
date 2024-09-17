
<script>    
function createClientPlan(){
	$('#clientplan_form').attr('action', '<?php echo $this->webroot;?>AdminPlans');
	$("#clientplan_form").submit();	
}
function checkCharacter(e,t) {
	try{
    	if (window.event) {
        	var charCode = window.event.keyCode;
     	}
        else if(e){
     		var charCode = e.which;
        }
        else{return true;}
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {         
        	return false;
        }
        return true;     
	}
    catch (err) {
    	alert(err.Description);
    }
}
</script>
<script>
    function getPeriod(value)
    {
        
        var str='<div class="input select">';
        str += '<select name="RentalPeriod" required="" class="form-control">';
        str +='<option>Select</option>';
        if(value=='')
        {    str += '<option value="">Rental Period</option>'; }
        
        else if(value=='DAY')
        {
            for(var i=1; i<=29; i++){    str +='<option value="'+i+'">'+i+'</option>'; }
        }
        else if(value=='MONTH')
        {
            for(var i=1; i<=11; i++){    str +='<option value="'+i+'">'+i+'</option>'; }
        }
        else if(value=='YEAR')
        {
            for(var i=1; i<=2; i++)    {    str +='<option value="'+i+'">'+i+'</option>';    }
        }
        str +='</select></div>';
            document.getElementById("rentalPeriod").innerHTML=str;
    }
   
    function getPaymentDetDisp(value)
    {
        var html = '';
        if(value=='Percent')
        {
            html = '<label class="col-sm-3 control-label">Percentage(%) / Transaction</label>';
        }
        else if(value=='INR')
        {
            html = '<label class="col-sm-3 control-label">Rs. / Transaction</label>';
        }
        else
        {
            var html = '<label class="col-sm-3 control-label">(Rs./Percent (%))/Transaction</label>';
        }
        document.getElementById('PaymentDetDisp').innerHTML=html;
    }
</script>



<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Plan Master</a></li>
    <li class="active"><a href="#">Plan Master</a></li>
</ol>

<div class="container-fluid">
    <div data-widget-group="group1">
        
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>Plan Master</h2>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body">
            <?php echo $this->Form->create('AdminPlans',array('class'=>'form-horizontal','action'=>'client_wise_plan_creation')); ?>
            <div class="form-group">
                <label class="col-sm-3 control-label">Plan Name</label>
                <div class="col-sm-3">
                    <input type="text" name="PlanName" placeholder="Plan Name" required="" class="form-control">
                </div>
                
                <label class="col-sm-3 control-label">Plan Type</label>
                <div class="col-sm-3">
                    <select name="PlanType" required="" class="form-control">
                        <option value="">Plan Type</option>
                        <option value="Prepaid">Prepaid</option>
                        <option value="Postpaid">Postpaid</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">COMPONENTS</label>
                <label class="col-sm-3 control-label">UNIT</label>
                <label class="col-sm-3 control-label">FREEB</label>
                <label class="col-sm-3 control-label">CHARGE</label>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Setup Fee</label>
                <label class="col-sm-3 control-label">Rs.</label>
                
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-3">
                    <input type="text" name="SetupCost" placeholder="Setup Cost" required="" onkeypress="return checkCharacter(event,this)" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Rental</label>
                <label class="col-sm-3 control-label">Rs.</label>
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-3">
                    <input type="text" id="RentalAmount" name="RentalAmount" placeholder="Rental Amount" onkeypress="return checkCharacter(event,this)" required="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Balance</label>
                <label class="col-sm-3 control-label">Rs.</label>
                <div class="col-sm-3">
                    <input type="text" id="Balance" name="Balance" placeholder="Balance" onkeypress="return checkCharacter(event,this)" required="" class="form-control">
                </div>
                <label class="col-sm-3 control-label"></label>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Period</label>
                <label class="col-sm-1 control-label">Type</label>
                <div class="col-sm-2">
                    <select id="PeriodType" name="PeriodType"  onchange="getPeriod(this.value)" required="" class="form-control">
                        <option value="">Period Type</option>
                        <option value="YEAR">YEAR</option>
                        <option value="MONTH">MONTH</option>
                        <option value="DAY">DAY</option>
                    </select>
                </div>
                
                <div class="col-sm-3">
                    <div id="rentalPeriod">
                    <select name="RentalPeriod" required="" class="form-control">
                        <option>Select Period</option>
                    </select>
                    </div>    
                </div>
                <label class="col-sm-3 control-label"></label>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label">NUMBER TYPE(MOBILE/TF)</label>
                <label class="col-sm-3 control-label">Type</label>
                <div class="col-sm-3">
                    <select id="NumberType" name="NumberType" required="" class="form-control">
                        <option value="MOBILE">MOBILE</option>
                        <option value="TF">TF</option>
                    </select>
                </div>
                <label class="col-sm-3 control-label"></label>
            </div>
                
            <div class="form-group">
                <label class="col-sm-3 control-label">INCOMING CALL</label>
                <label class="col-sm-1 control-label">Rs./Min</label>
                <div class="col-sm-2">
                    <select name="InboundCallMinute" id="InboundCallMinute" required="" class="form-control">
                        <option value="1">1 Min</option>
                        <option value="2">2 Min</option>
                        <option value="3">3 Min</option>
                        <option value="4">4 Min</option>
                        <option value="5">5 Min</option>
                        <option value="Flat">Flat</option>
                    </select>
                </div>
                 <label class="col-sm-3 control-label"></label>
                 <div class="col-sm-3">
                     <input type="text" id="InboundCallCharge" name="InboundCallCharge" placeholder="Inbound Call Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control">
                 </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label">Outbound CALL</label>
                <label class="col-sm-1 control-label">Rs./Min</label>
                <div class="col-sm-2">
                    <select id="OutboundCallCharge" name="OutboundCallMinute" required="" class="form-control">
                        <option value="1">1 Min</option>
                        <option value="2">2 Min</option>
                        <option value="3">3 Min</option>
                        <option value="4">4 Min</option>
                        <option value="5">5 Min</option>
                        <option value="Flat">Flat</option>
                    </select>
                </div>
                 <label class="col-sm-3 control-label"></label>
                 <div class="col-sm-3">
                     <input type="text" id="OutboundCallCharge" name="OutboundCallCharge" placeholder="Outbound Call Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control">
                 </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label">SMS</label>
                <label class="col-sm-1 control-label">CHRTR</label>
                <div class="col-sm-2">
                    <input type="text" id="SMSLength" name="SMSLength" value="160" required="" class="form-control">
                </div>
                 <label class="col-sm-3 control-label"></label>
                 <div class="col-sm-3">
                     <input type="text" id="SMSCharge" name="SMSCharge" placeholder="SMS Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control">
                 </div>
            </div>
                
            <div class="form-group">
                <label class="col-sm-3 control-label">EMAIL</label>
                <label class="col-sm-3 control-label">Rs./MAIL</label>
                 <label class="col-sm-3 control-label"></label>
                 <div class="col-sm-3">
                     <input type="text" id="EmailCharge" name="EmailCharge" placeholder="Email Rs./Min" onkeypress="return checkCharacter(event,this)" required="" class="form-control">
                 </div>
            </div>
                
            <div class="form-group">
                <label class="col-sm-3 control-label">VFO</label>
                <label class="col-sm-3 control-label">Rs./Min</label>
                 <label class="col-sm-3 control-label"></label>
                 <div class="col-sm-3">
                     <input type="text" id="VFOCallCharge" name="VFOCallCharge" placeholder="VFO Rs./Min" onkeypress="return checkCharacter(event,this)" required="" class="form-control">
                 </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label">Missed Call</label>
                <label class="col-sm-3 control-label">Rs./Min</label>
                 <label class="col-sm-3 control-label"></label>
                 <div class="col-sm-3">
                     <input type="text" id="MissCallCharge" name="MissCallCharge" placeholder="Miss Call Rs./Min" onkeypress="return checkCharacter(event,this)" required="" class="form-control">
                 </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Payment Gateway</label>
                <label class="col-sm-2 control-label">Percent (%) / Rs. (<i class="fa fa-inr"></i>)</label>
                
                <div class="col-sm-1">
                    <select name="PaymentGateWayType" id="PaymentGateWayType" class="form-control" onchange="getPaymentDetDisp(this.value)">
                    <option value="">Select</option>
                    <option value="Percent">Percent (%)</option>
                    <option value="INR">INR.</option>
                </select>
                </div>
                <div id="PaymentDetDisp" >
                    <label class="col-sm-3 control-label">(Rs./Percent (%))/Transaction</label>
                </div>    
                 <label class="col-sm-3 control-label"></label>
                 <div class="col-sm-3">
                     <input type="text" id="PaymentGateWayCharge" name="PaymentGateWayCharge" placeholder="Payment Charge / Transaction"  onkeypress="return checkCharacter(event,this)" class="form-control" >
                 </div>
            </div>
                
            <div class="form-group">
                <label class="col-sm-3 control-label">USER ID</label>
                <label class="col-sm-3 control-label">No of Users</label>
                 <div class="col-sm-3">
                     <input type="text" id="NoOfFreeUser" name="NoOfFreeUser" placeholder="No. Of Free User" onkeypress="return checkCharacter(event,this)" required="" class="form-control">
                 </div>
                <div class="col-sm-3">
                    <input type="text" id="ChargePerExtraUser" name="ChargePerExtraUser" placeholder="Charge For Extra User Rs./User" onkeypress="return checkCharacter(event,this)" required="" class="form-control">
                 </div>
            </div>    
            <div class="form-group">
                <label class="col-sm-3 control-label">Balance Carry Forward </label>
                <label class="col-sm-3 control-label">Rs.</label>
                 <div class="col-sm-3">
                     <select id="TransferAfterRental" name="TransferAfterRental" required="" class="form-control">
                         <option>Select</option>
                         <option value="Yes">Yes</option>
                         <option value="No">No</option>
                     </select>
                 </div>
                <label class="col-sm-3 control-label"></label>
            </div>    
            <div class="form-group">
                <div class="col-sm-4"></div>
                <div class="col-sm-2">
                    <input type="submit" value="Submit" class="btn-web btn">
                </div>
            </div>    
           <?php echo $this->Form->end();    ?>    
        </div>
        <div class="panel-footer"></div>
    </div>
   
        
    </div>
    
</div>
 
