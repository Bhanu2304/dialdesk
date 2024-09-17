<<script>
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
	else if(value=='Half')
        {
            document.getElementById('CreditValue').value=Math.round(document.getElementById('Balance').value/2);
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

	// Basant code start from here

// Day Shift

function get_rate_per_pulse_day_shift(value)
   {
	 
        var InboundCallCharge = document.getElementById('InboundCallCharge').value;

        if(value=='1')
        {
            document.getElementById('rate_per_pulse_day_shift').value= ( InboundCallCharge / 60 ) * 1;
        }
         else if(value=='15')
        {
            document.getElementById('rate_per_pulse_day_shift').value= ( InboundCallCharge / 60 ) * 15;      
         }
        else if(value=='30')
        {
            document.getElementById('rate_per_pulse_day_shift').value= ( InboundCallCharge / 60 ) * 30;        
        }
        else if(value=='45')
        {
            document.getElementById('rate_per_pulse_day_shift').value= ( InboundCallCharge / 60 ) * 45;        
        }
	
	 else if(value=='60')
        {
            document.getElementById('rate_per_pulse_day_shift').value= ( InboundCallCharge / 60 ) * 60;       
        }



    }


    // Night Shift

function get_rate_per_pulse_night_shift(value)
   {
	 
        var InboundCallChargeNight = document.getElementById('InboundCallChargeNight').value;

        if(value=='1')
        {
            document.getElementById('rate_per_pulse_night_shift').value= ( InboundCallChargeNight / 60 ) * 1;
        }
         else if(value=='15')
        {
            document.getElementById('rate_per_pulse_night_shift').value= ( InboundCallChargeNight / 60 ) * 15;      
         }
        else if(value=='30')
        {
            document.getElementById('rate_per_pulse_night_shift').value= ( InboundCallChargeNight / 60 ) * 30;        
        }
        else if(value=='45')
        {
            document.getElementById('rate_per_pulse_night_shift').value= ( InboundCallChargeNight / 60 ) * 45;        
        }
	
	 else if(value=='60')
        {
            document.getElementById('rate_per_pulse_night_shift').value= ( InboundCallChargeNight / 60 ) * 60;       
        }



    }


       // Outbound Call Charge

function get_rate_per_pulse_outbound_call_shift(value)
   {
	 
        var OutboundCallCharge = document.getElementById('OutboundCallCharge').value;

        if(value=='1')
        {
            document.getElementById('rate_per_pulse_outbound_call_shift').value= ( OutboundCallCharge / 60 ) * 1;
        }
         else if(value=='15')
        {
            document.getElementById('rate_per_pulse_outbound_call_shift').value= ( OutboundCallCharge / 60 ) * 15;      
         }
        else if(value=='30')
        {
            document.getElementById('rate_per_pulse_outbound_call_shift').value= ( OutboundCallCharge / 60 ) * 30;        
        }
        else if(value=='45')
        {
            document.getElementById('rate_per_pulse_outbound_call_shift').value= ( OutboundCallCharge / 60 ) * 45;        
        }
	
	 else if(value=='60')
        {
            document.getElementById('rate_per_pulse_outbound_call_shift').value= ( OutboundCallCharge / 60 ) * 60;       
        }



    }

    function show_flat(value)
    {
        if(value=='Yes')
        {
            document.getElementById('flatDisp').style.display = "block"; 
        }
        else
        {
            document.getElementById('flatDisp').style.display = "none"; 
        }
    }

// Basant code end here



</script>



<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Plan Master</a></li>
    <li class="active"><a href="#">Plan Master</a></li>
</ol>

<div class="container-fluid">
    <div class="panel panel-default" data-widget='{"draggable": "false"}' style="margin-top:5px;">
            <div class="panel-heading">
                 <h2>Plan Approve</h2>
            </div>
            <div class="panel-body" style="margin-top:-10px;" >
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                
                <?php echo $this->Form->create('AdminPlans',array('action'=>'plan_approved')); ?>        
                    <div class="col-md-12">
                        
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Plan Name</span>
                                <input type="text" name="PlanName" value="<?php echo $plan['PlanMaster']['PlanName']; ?>" placeholder="Plan Name" required="" class="form-control extclass">
                           </div>
                        </div>
                        
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Setup Fee</span>
                               <input type="text" name="SetupCost" value="<?php echo $plan['PlanMaster']['SetupCost']; ?>" placeholder="Setup Cost" required="" onkeypress="return checkCharacter(event,this)" class="form-control extclass decimal">
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Subscription Amount </span>
                               <input type="text" id="RentalAmount" name="RentalAmount" value="<?php echo $plan['PlanMaster']['RentalAmount']; ?>" placeholder="Subscription Amount" onkeypress="return checkCharacter(event,this)" required="" class="form-control extclass decimal">
                           </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Credit value</span>
                                <input type="text" id="Balance" name="Balance" value="<?php echo $plan['PlanMaster']['Balance']; ?>" placeholder="Credit value" onkeypress="return checkCharacter(event,this)" required="" class="form-control extclass decimal">
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
                        <option value="YEAR" <?php if($plan['PlanMaster']['PeriodType']=='YEAR') echo 'selected'; ?>>YEAR</option>
                        <option value="MONTH" <?php if($plan['PlanMaster']['PeriodType']=='MONTH') echo 'selected'; ?>>MONTH</option>
                        <option value="Quater" <?php if($plan['PlanMaster']['PeriodType']=='Quater') echo 'selected'; ?>>Quarter</option>
                        <option value="Half" <?php if($plan['PlanMaster']['PeriodType']=='Half') echo 'selected'; ?>>Half</option>
                    </select>
                           </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Credit Value as per Plan Mode</span>
                                        
                                        <input type="text" id="CreditValue" name="CreditValue" value="<?php echo $plan['PlanMaster']['CreditValue']; ?>" placeholder="Credit Value as per Plan Mode" onkeypress="return checkCharacter(event,this)" required="" class="form-control extclass decimal">
                           </div>
                        </div>


                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span >Inbound Call Charge(Day Shift)</span>
                                <input type="text" id="InboundCallCharge" name="InboundCallCharge"   value="<?php echo $plan['PlanMaster']['InboundCallCharge']; ?>"  onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>

                        <!-- Basant Code Start From here ---->



<div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span>Pulse(Day Shift)</span>
                                <select id="pulse_day_shift" name="pulse_day_shift"  onchange="get_rate_per_pulse_day_shift(this.value)" required="" class="form-control">
                                     
                                     <option value="1" <?php if($plan['PlanMaster']['pulse_day_shift']=='1') echo 'selected'; ?>>1</option>
                                     <option value="15" <?php if($plan['PlanMaster']['pulse_day_shift']=='15') echo 'selected'; ?>>15</option>
				     <option value="30" <?php if($plan['PlanMaster']['pulse_day_shift']=='30') echo 'selected'; ?>>30</option>
                                     <option value="45" <?php if($plan['PlanMaster']['pulse_day_shift']=='45') echo 'selected'; ?>>45</option>
                                     <option value="60" <?php if($plan['PlanMaster']['pulse_day_shift']=='60') echo 'selected'; ?>>60</option>
                               </select>
                           </div>
                        </div>

			<div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span>Rate Per Pulse(Day Shift)</span>
                                <input type="text" id="rate_per_pulse_day_shift" readonly name="rate_per_pulse_day_shift"   value="<?php echo $plan['PlanMaster']['rate_per_pulse_day_shift']; ?>" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>



                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span >Inbound Call Charge(Night Shift)</span>
                                <input type="text" id="InboundCallChargeNight" name="InboundCallChargeNight"   value="<?php echo $plan['PlanMaster']['InboundCallChargeNight']; ?>"  onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>

			
			<div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span>Pulse(Night Shift)</span>
                                <select id="pulse_night_shift" name="pulse_night_shift"  onchange="get_rate_per_pulse_night_shift(this.value)" required="" class="form-control">
                                     
                                     <option value="1" <?php if($plan['PlanMaster']['pulse_night_shift']=='1') echo 'selected'; ?>>1</option>
                                     <option value="15" <?php if($plan['PlanMaster']['pulse_night_shift']=='15') echo 'selected'; ?>>15</option>
				     <option value="30" <?php if($plan['PlanMaster']['pulse_night_shift']=='30') echo 'selected'; ?>>30</option>
                                     <option value="45" <?php if($plan['PlanMaster']['pulse_night_shift']=='45') echo 'selected'; ?>>45</option>
                                     <option value="60"  <?php if($plan['PlanMaster']['pulse_night_shift']=='60') echo 'selected'; ?>>60</option>
                               </select>
                           </div>
                        </div>

			<div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span>Rate Per Pulse(Night Shift)</span>
                                <input type="text" id="rate_per_pulse_night_shift"  readonly name="rate_per_pulse_night_shift"  value="<?php echo $plan['PlanMaster']['rate_per_pulse_night_shift']; ?>"   onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>







                    </div>
                
                        
                    <div class="col-md-12">
                       
                    
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span >Outbound Call Charge</span>
                                <input type="text" id="OutboundCallCharge" name="OutboundCallCharge"  value="<?php echo $plan['PlanMaster']['OutboundCallCharge']; ?>"  onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>

			
			
			<div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span>Pulse</span>
                                <select id="pulse_outbound_call_shift" name="pulse_outbound_call_shift"  onchange="get_rate_per_pulse_outbound_call_shift(this.value)" required="" class="form-control">
                                     
                                     <option value="1" <?php if($plan['PlanMaster']['pulse_outbound_call_shift']=='1') echo 'selected'; ?>>1</option>
                                     <option value="15"  <?php if($plan['PlanMaster']['pulse_outbound_call_shift']=='15') echo 'selected'; ?>>15</option>
				     <option value="30"  <?php if($plan['PlanMaster']['pulse_outbound_call_shift']=='30') echo 'selected'; ?>>30</option>
                                     <option value="45"  <?php if($plan['PlanMaster']['pulse_outbound_call_shift']=='45') echo 'selected'; ?>>45</option>
                                     <option value="60" <?php if($plan['PlanMaster']['pulse_outbound_call_shift']=='60') echo 'selected'; ?>>60</option>
                               </select>
                           </div>
                        </div>

			<div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span>Rate Per Pulse</span>
                                <input type="text" id="rate_per_pulse_outbound_call_shift"  readonly name="rate_per_pulse_outbound_call_shift" value="<?php echo $plan['PlanMaster']['rate_per_pulse_outbound_call_shift']; ?>"  onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>


<!-- Basant code end here--->
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">SMS Char 160</span>
                                <input type="text" id="SMSCharge" name="SMSCharge" value="<?php echo $plan['PlanMaster']['SMSCharge']; ?>" placeholder="SMS Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>  
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Email Charge</span>
                                <input type="text" id="EmailCharge" name="EmailCharge" value="<?php echo $plan['PlanMaster']['EmailCharge']; ?>" placeholder="Per Email Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>

                         <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">VFO Call Charge</span>
                                <input type="text" id="VFOCallCharge" name="VFOCallCharge" value="<?php echo $plan['PlanMaster']['VFOCallCharge']; ?>" placeholder="VFO Rs./Min" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>
			

                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Miss Call Charge</span>
                                 <input type="text" id="MissCallCharge" name="MissCallCharge" value="<?php echo $plan['PlanMaster']['MissCallCharge']; ?>" placeholder="Miss Call Rs./Min" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div> 

			
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">IVR Call Charge</span>
                                 <input type="text" id="IvrCharge" name="IVR_Charge" value="<?php echo $plan['PlanMaster']['IVR_Charge']; ?>" placeholder="IVR Call Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div> 


                    </div>
                
                
                
                    <div class="col-md-12">
                       
                        
			 <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">First Minute</span>
                                 <div class="form-group is-empty">
                                    
                                     <span>Enable</span> <input type="radio" id="first_minute" value="Enable" <?php if($plan['PlanMaster']['first_minute']=='Enable') echo 'checked'; ?> name="first_minute"><span class="material-input"></span> 

				     <span>Disable</span> <input type="radio" id="first_minute" value="Disable" <?php if($plan['PlanMaster']['first_minute']=='Disable') echo 'checked'; ?>  name="first_minute"><span class="material-input"></span>
                                 
 
				</div>
                           </div>
                        </div>

                        
                       
                         
                         <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">No of Users</span>
                                <input type="text" id="NoOfFreeUser" name="NoOfFreeUser" value="<?php echo $plan['PlanMaster']['NoOfFreeUser']; ?>" placeholder="No. Of Free User" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Charge Per Extra User</span>
                                <input type="text" id="ChargePerExtraUser" name="ChargePerExtraUser" value="<?php echo $plan['PlanMaster']['ChargePerExtraUser']; ?>" placeholder="Charge For Extra User Rs./User" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Balance Carry Forward</span>
                                <select id="TransferAfterRental" onChange="show_flat(this.value);" name="TransferAfterRental" required="" class="form-control">
                         <option>Select</option>
                         <option value="Yes" <?php if($plan['PlanMaster']['TransferAfterRental']=='Yes') echo 'selected'; ?>>Yes</option>
                         <option value="No" <?php if($plan['PlanMaster']['TransferAfterRental']=='No') echo 'selected'; ?>>No</option>
                     </select>
                           </div>
                        </div> 
                        <div class="col-md-3" id="flatDisp" style="display:<?php if($plan['PlanMaster']['TransferAfterRental']=='No') {echo 'none';} ?>">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span>Flat %</span>

                                <input type="text" id="Flat" name="Flat" value="<?php echo $plan['PlanMaster']['Flat']; ?>" placeholder="% Used to Balance Carry Forward" onkeypress="return checkCharacter(event,this)"  class="form-control decimal">
                           </div>
                        </div>
                    </div>
                
                <div class="col-md-12">
                    <div class="col-md-6">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Reject Remarks</span>
                                <textarea type="text" id="reject_remarks" name="reject_remarks"  placeholder="Reject Remarks"   class="form-control"><?php echo $plan['PlanMaster']['reject_remarks']; ?></textarea>
                           </div>
                        </div>
                </div>
                
                
                <div class="col-md-4">
                    <div class="col-xs-12">
                        <div class="input-group">                           
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span> 
                            <input type="hidden" name="id" value="<?php echo $plan['PlanMaster']['Id']; ?>" />
                            <input name="submit" type="submit" value="Approve" class="btn-web btn">   
                            <input name="submit" type="submit" value="Reject" class="btn-web btn">  
                        </div>
                    </div>
                </div> 
                <?php  echo $this->Form->end(); ?>
               
            </div>
        </div>
   
        
    </div>
    
</div>
 
