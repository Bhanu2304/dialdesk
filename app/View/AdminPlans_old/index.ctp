<script>
    $(function () {
   

    
    
    $('.decimal').keypress(function (e) {
        var character = String.fromCharCode(e.keyCode)
        var newValue = this.value + character;
        if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
            e.preventDefault();
            return false;
        }
    });
    
    function hasDecimalPlace(value, x) {
        var pointIndex = value.indexOf('.');
        return  pointIndex >= 0 && pointIndex < value.length - x;
    }
});
</script>

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

function decimal_check(t,val)
{
    val = val.toFixed(2);
    t.value = val;
}


</script>
<script>
   function getPeriod(value)
    {
        var strx = ''
        var cvalue = document.getElementById('Balance').value;
        if(value=='YEAR')
        {
            document.getElementById('CreditValue').value=document.getElementById('Balance').value;
        }
         else if(value=='MONTH')
        {
            document.getElementById('CreditValue').value=Math.round(document.getElementById('Balance').value/12);
        }
        else if(value=='Quater')
        {
            document.getElementById('CreditValue').value=Math.round(document.getElementById('Balance').value/4);
        }

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
    <div class="panel panel-default" data-widget='{"draggable": "false"}' style="margin-top:5px;">
            <div class="panel-heading">
                 <h2>Plan Creation</h2>
            </div>
            <div class="panel-body" style="margin-top:-10px;" >
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                
                <?php echo $this->Form->create('AdminPlans',array('action'=>'client_wise_plan_creation')); ?>        
                    <div class="col-md-12">
                        
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Plan Name</span>
                                <input type="text" name="PlanName" placeholder="Plan Name" required="" class="form-control extclass">
                           </div>
                        </div>
                        
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Setup Fee</span>
                               <input type="text" name="SetupCost" placeholder="Setup Cost" required="" onkeypress="return checkCharacter(event,this)" onkeyup="return decimal_check(this,this.value)" class="form-control extclass decimal">
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Subscription Amount </span>
                               <input type="text" id="RentalAmount" name="RentalAmount" placeholder="Subscription Amount" onkeypress="return checkCharacter(event,this)" required="" class="form-control extclass decimal">
                           </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Credit value</span>
                                <input type="text" id="Balance" name="Balance" placeholder="Credit value" onkeypress="return checkCharacter(event,this)" required="" class="form-control extclass decimal">
                           </div>
                        </div>
                    </div>
                
                    
                    <div class="col-md-12">                    
                        
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Plan Mode</span>
                               <select id="PeriodType" name="PeriodType"  onchange="getPeriod(this.value)" required="" class="form-control">
                        <option value="">Period Type</option>
                        <option value="YEAR">YEAR</option>
                        <option value="MONTH">MONTH</option>
                        <option value="Quater">Quater</option>
                    </select>
                           </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Credit Value as per Plan Mode</span>
                                        
                                        <input type="text" id="CreditValue" name="CreditValue" placeholder="Credit Value as per Plan Mode" onkeypress="return checkCharacter(event,this)" required="" class="form-control extclass decimal">
                           </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Inbound Call Charge(Day Shift)</span>
                                <input type="text" id="InboundCallCharge" name="InboundCallCharge" placeholder="Inbound Call Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>
						<div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Inbound Call Charge Type</span>
                                <select id="IB_Call_Charge" name="IB_Call_Charge" placeholder="Inbound Call Charge" required="" class="form-control">
								<option value="Minute">Minute</option>
								<option value="Second">Second</option>
								<option value="Minute/Second">Minute/Second</option>
								</select>
                           </div>
                        </div>
						
                        


                    </div>
                
                        
                    <div class="col-md-12">
                       <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Inbound Call Charge(Night Shift)</span>
                                <input type="text" id="InboundCallChargeNight" name="InboundCallChargeNight" placeholder="Inbound Call Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>
                    
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Outbound Call Charge</span>
                                <input type="text" id="OutboundCallCharge" name="OutboundCallCharge" placeholder="Outbound Call Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Outbound Call Charge Type</span>
                                <select id="OB_Call_Charge" name="OB_Call_Charge" required="" class="form-control">
								<option value="Minute">Minute</option>
								<option value="Second">Second</option>
								<option value="Minute/Second">Minute/Second</option>
								</select>
                           </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">SMS Char 160</span>
                                <input type="text" id="SMSCharge" name="SMSCharge" placeholder="SMS Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>  
                        
                    </div>
                
                
                
                    <div class="col-md-12">
                       <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Email Charge</span>
                                <input type="text" id="EmailCharge" name="EmailCharge" placeholder="Per Email Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>

                         <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">VFO Call Charge</span>
                                <input type="text" id="VFOCallCharge" name="VFOCallCharge" placeholder="VFO Rs./Min" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>
                        
                        
                       
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Miss Call Charge</span>
                                 <input type="text" id="MissCallCharge" name="MissCallCharge" placeholder="Miss Call Rs./Min" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>

						<div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">IVR Call Charge</span>
                                 <input type="text" id="IVR_Charge" name="IVR_Charge" placeholder="IVR Call Rs./CALL" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>
						
                         
                        
                         
                        
                    </div>
                <div class="col-md-12">
				<div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">No of Users</span>
                                <input type="text" id="NoOfFreeUser" name="NoOfFreeUser" placeholder="No. Of Free User" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>
				
                <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Charge Per Extra User</span>
                                <input type="text" id="ChargePerExtraUser" name="ChargePerExtraUser" placeholder="Charge For Extra User Rs./User" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Balance Carry Forward</span>
                                <select id="TransferAfterRental" name="TransferAfterRental" required="" class="form-control">
                         <option>Select</option>
                         <option value="Yes">Yes</option>
                         <option value="No">No</option>
                     </select>
                           </div>
                        </div>
                
            </div>    
                <div class="col-md-4">
                    <div class="col-xs-12">
                        <div class="input-group">                           
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                           <input type="submit" value="Submit" class="btn-web btn">   
                        </div>
                    </div>
                </div> 
                <?php  echo $this->Form->end(); ?>
               
            </div>
        </div>
   
        
    </div>
    
</div>
 
