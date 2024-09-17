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
                        <option value="Quater" <?php if($plan['PlanMaster']['PeriodType']=='Quater') echo 'selected'; ?>>Quater</option>
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
                                <span style="font-style:italic;">Inbound Call Charge(Day Shift)</span>
                                <input type="text" id="InboundCallCharge" name="InboundCallCharge" value="<?php echo $plan['PlanMaster']['InboundCallCharge']; ?>" placeholder="Inbound Call Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>
						
						<div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Inbound Call Charge Type</span>
                                <select id="IB_Call_Charge" name="IB_Call_Charge" placeholder="Inbound Call Charge" required="" class="form-control">
								<option value="Minute" <?php if($plan['PlanMaster']['IB_Call_Charge']=='Minute') echo 'selected'; ?>>Minute</option>
								<option value="Second" <?php if($plan['PlanMaster']['IB_Call_Charge']=='Second') echo 'selected'; ?>>Second</option>
								<option value="Minute/Second" <?php if($plan['PlanMaster']['IB_Call_Charge']=='Minute/Second') echo 'selected'; ?>>Minute/Second</option>
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
                                <input type="text" id="InboundCallChargeNight" name="InboundCallChargeNight" value="<?php echo $plan['PlanMaster']['InboundCallChargeNight']; ?>" placeholder="Inbound Call Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Outbound Call Charge</span>
                                <input type="text" id="OutboundCallCharge" value="<?php echo $plan['PlanMaster']['OutboundCallCharge']; ?>" name="OutboundCallCharge" placeholder="Outbound Call Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
                           </div>
                        </div>
                        
						<div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">Outbound Call Charge Type</span>
                                <select id="OB_Call_Charge" name="OB_Call_Charge" required="" class="form-control">
								<option value="Minute" <?php if($plan['PlanMaster']['OB_Call_Charge']=='Minute') echo 'selected'; ?>>Minute</option>
								<option value="Second" <?php if($plan['PlanMaster']['OB_Call_Charge']=='Second') echo 'selected'; ?>>Second</option>
								<option value="Minute/Second" <?php if($plan['PlanMaster']['OB_Call_Charge']=='Minute/Second') echo 'selected'; ?>>Minute/Second</option>
								</select>
                           </div>
                        </div>
						
                        <div class="col-md-3">
                            <div class="input-group">                           
                                <span class="input-group-addon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <span style="font-style:italic;">SMS Char 160</span>
                                <input type="text" id="SMSCharge" name="SMSCharge" value="<?php echo $plan['PlanMaster']['SMSCharge']; ?>" placeholder="SMS Charge" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
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
                                 <input type="text" value="<?php echo $plan['PlanMaster']['IVR_Charge']; ?>" id="IVR_Charge" name="IVR_Charge" placeholder="IVR Call Rs./CALL" onkeypress="return checkCharacter(event,this)" required="" class="form-control decimal">
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
                                <select id="TransferAfterRental" name="TransferAfterRental" required="" class="form-control">
                         <option>Select</option>
                         <option value="Yes" <?php if($plan['PlanMaster']['TransferAfterRental']=='Yes') echo 'selected'; ?>>Yes</option>
                         <option value="No" <?php if($plan['PlanMaster']['TransferAfterRental']=='No') echo 'selected'; ?>>No</option>
                     </select>
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
 
