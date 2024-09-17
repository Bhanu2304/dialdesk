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
    <li class="active"><a href="index">View Pending Plan</a></li>
</ol>


<div class="container-fluid">
    <div data-widget-group="group1">
        <?php if(isset($plans) && !empty($plans)){?>
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <b><h2 style="color:green;"><?php echo $this->Session->Flash(); ?></h2></b>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body no-padding scrolling ">
             <table class="table table-striped table-bordered datatables"> 
                <thead>
                <tr>
                    <th style="text-align:left;">Sr. No.</th>
                    <th style="text-align:left;">Action</th>
                    <th style="text-align:left;">Reject Remarks</th>
                    <th style="text-align:left;">Plan</th>
                    
                    <th style="text-align:left;">SetupCost</th>
                    <th style="text-align:left;">RentalCost</th>
                    <th style="text-align:left;">Balance</th>
                    <th style="text-align:left;">Payment Terms</th>
                    <th style="text-align:left;">IBCall</th>
					<th style="text-align:left;">IBCallType</th>
                    <th style="text-align:left;">IBCallNight</th>
                    <th style="text-align:left;">OBCall</th>
					<th style="text-align:left;">OBCallType</th>
                    <th style="text-align:left;">SMS</th>
                    <th style="text-align:left;">Email</th>
                    <th style="text-align:left;">MissCall</th>
					<th style="text-align:left;">IVR</th>
                    <th style="text-align:left;">VFO</th>
                    <th style="text-align:left;">PaymentGateWay</th>
                    
                    
<!--                    <th>Action</th>-->
                </tr>
                </thead>
                <tbody>
                    <?php  $i=1;  foreach($plans as $plan) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><a href="edit_plan?plan_id=<?php echo $plan['PlanMaster']['Id']; ?>" >Edit</a></td>
                        <td><?php echo $plan['PlanMaster']['reject_remarks']; ?></td>
                <td><?php echo $plan['PlanMaster']['PlanName']; ?></td>
                
                <td style="text-align:right;"><?php echo $plan['PlanMaster']['SetupCost']; ?></td>
                <td style="text-align:right;"><?php echo $plan['PlanMaster']['RentalAmount']; ?></td>
                <td style="text-align:right;"><?php echo $plan['PlanMaster']['Balance']; ?></td>
                <td style="text-align:right;"><?php echo $plan['PlanMaster']['PeriodType']; ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['PlanMaster']['InboundCallCharge'],2); ?></td>
				<td style="text-align:right;"><?php echo $plan['PlanMaster']['IB_Call_Charge']; ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['PlanMaster']['InboundCallChargeNight'],2); ?></td>
				
                <td style="text-align:right;"><?php echo number_format($plan['PlanMaster']['OutboundCallCharge'],2); ?></td>
				<td style="text-align:right;"><?php echo $plan['PlanMaster']['OB_Call_Charge']; ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['PlanMaster']['SMSCharge'],2); ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['PlanMaster']['EmailCharge'],2); ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['PlanMaster']['MissCallCharge'],2); ?></td>
				<td style="text-align:right;"><?php echo number_format($plan['PlanMaster']['IVR_Charge'],2); ?></td>
                <td style="text-align:right;"><?php echo number_format($plan['PlanMaster']['VFOCallCharge'],2); ?></td>
                <td><?php if(!empty($plan['PlanMaster']['PaymentGateWayCharge'])) { echo $plan['PlanMaster']['PaymentGateWayCharge']." ".($plan['PlanMaster']['PaymentGateWayType']=='Percent'?'%':$plan['PlanMaster']['PaymentGateWayType'])."/Transaction"; } else { echo 'Not Specified'; } ?></td>
                
                
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

 
