<script>
function viewClientPlan(){
	$('#clientplan_form').attr('action', '<?php echo $this->webroot;?>AdminPlans/view_client_plan');
	$("#clientplan_form").submit();	
}
function removeReadonly(){
	$('input').prop('readonly', false);
	$("#update_plan").show();
	$("#cancel").show();
}
function addReadonly(){
	$('input').prop('readonly', true);
	$("#update_plan").hide();
	$("#cancel").hide();
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
<style>
.imgclass{
	width:30px;
	cursor:pointer;
}
#addnew{
	position:absolute;
	left:87%;
	top:40px;	
}
th {
    padding: 10px 5px;
}
</style>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Plan Master</a></li>
    <li class="active"><a href="index">Plan Master</a></li>
</ol>
<?php echo $this->Form->create('AdminPlans',array('action'=>'client_wise_plan_creation','id'=>'clientplan_form')); ?>

<div class="container-fluid">
    <div data-widget-group="group1">
        <?php if(isset($plans) && !empty($plans)){?>
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <b><h2 style="color:green;"><?php echo $this->Session->Flash(); ?></h2></b>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body no-padding scrolling ">
<!--             <table class="display table table-bordered table-condensed table-hovered sortableTable"> -->
            <table class="table table-striped table-bordered datatables"> 
                <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Plan</th>
                    
                    <th style="text-align:left;">SetupCost</th>
                    <th style="text-align:left;">RentalCost</th>
                    <th style="text-align:left;">Balance</th>
                    <th style="text-align:left;">Credit Value</th>
                    <th style="text-align:left;">Payment Terms</th>
                    
                    <th style="text-align:left;">SMS</th>
                    <th style="text-align:left;">Email</th>
                    <th style="text-align:left;">MissCall</th>
                    <th style="text-align:left;">VFO</th>
                    <th align="right">PaymentGateWay</th>
                    <th style="text-align:left;">Inbound Call Charge(Day Shift)</th>
		    <th style="text-align:left;">Pulse(Day Shift)	</th>
		    <th style="text-align:left;">Rate Per Pulse(Day Shift)</th>
		    <th style="text-align:left;">Inbound Call Charge(Night Shift)</th>
		    <th style="text-align:left;">Pulse(Night Shift)</th>
		    <th style="text-align:left;">Rate Per Pulse(Night Shift)</th>
		    <th style="text-align:left;">Outbound Call Charge</th>
		    <th style="text-align:left;">Pulse</th>
                    <th style="text-align:left;">Rate Per Pulse</th>
		    <th style="text-align:left;">IVR Charge</th>
		    <th style="text-align:left;">First Minute</th>
                    
<!--                    <th>Action</th>-->
                </tr>
                </thead>
                <tbody>
                    <?php $i=1; foreach($plans as $plan) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                <td><?php echo $plan['PlanMaster']['PlanName']; ?></td>
                
                <td style="text-align:right;"><?php echo $plan['PlanMaster']['SetupCost']; ?></td>
                <td style="text-align:right;"><?php echo $plan['PlanMaster']['RentalAmount']; ?></td>
                <td style="text-align:right;"><?php echo $plan['PlanMaster']['Balance']; ?></td>
                <td style="text-align:right;"><?php echo $plan['PlanMaster']['CreditValue']; ?></td>
                <td style="text-align:left;"><?php echo $plan['PlanMaster']['PeriodType']; ?></td> 
                
                <td style="text-align:right;"><?php echo number_format($plan['PlanMaster']['SMSCharge'],2); ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['PlanMaster']['EmailCharge'],2); ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['PlanMaster']['MissCallCharge'],2); ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['PlanMaster']['VFOCallCharge'],2); ?></td>
                <td><?php if(!empty($plan['PlanMaster']['PaymentGateWayCharge'])) { echo $plan['PlanMaster']['PaymentGateWayCharge']." ".($plan['PlanMaster']['PaymentGateWayType']=='Percent'?'%':$plan['PlanMaster']['PaymentGateWayType'])."/Transaction"; } else { echo 'Not Specified'; } ?></td>
                
                <td style="text-align:right;"><?php echo $plan['PlanMaster']['InboundCallCharge']; ?></td>
                <td style="text-align:right;"><?php echo $plan['PlanMaster']['pulse_day_shift']; ?></td>
		<td style="text-align:right;"><?php echo $plan['PlanMaster']['rate_per_pulse_day_shift']; ?></td>
		<td style="text-align:right;"><?php echo $plan['PlanMaster']['InboundCallChargeNight']; ?></td>
		<td style="text-align:right;"><?php echo $plan['PlanMaster']['pulse_night_shift']; ?></td>
		<td style="text-align:right;"><?php echo $plan['PlanMaster']['rate_per_pulse_night_shift']; ?></td>
		<td style="text-align:right;"><?php echo $plan['PlanMaster']['OutboundCallCharge']; ?></td>
		<td style="text-align:right;"><?php echo $plan['PlanMaster']['pulse_outbound_call_shift']; ?></td>
		<td style="text-align:right;"><?php echo $plan['PlanMaster']['rate_per_pulse_outbound_call_shift']; ?></td>

		<td style="text-align:right;"><?php echo $plan['PlanMaster']['IVR_Charge']; ?></td>
		<td style="text-align:right;"><?php echo $plan['PlanMaster']['first_minute']; ?></td>
<!--                <td><a href="edit_plan?id=<?php //echo $plan['PlanMaster']['Id']; ?>">Edit</a></td>-->
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <input type="submit" value="Update Plan >>" style="display:none;" class="btn btn-web" onclick="updateClientPlan();" id="update_plan" />
            <input type="button" value="Cancel" class="btn btn-web" style="display:none;" onclick="addReadonly();" id="cancel" />
        </div>
        <div class="panel-footer"></div>
    </div>
    <?php }?>
     <?php if(isset($plans) && empty($plans)){echo "No Record";}?>   
    </div>
</div>

 <?php echo $this->Form->end(); ?>
