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
             <table class="display table table-bordered table-condensed table-hovered sortableTable"> 
                <thead>
                <tr>
                    <th>Plan</th>
                    <th>PlanType</th>
                    <th>Setup Cost</th>
                    <th>Rental Cost</th>
                    <th>Balance</th>
                    <th>Period</th>
                    <th>Inbound Call</th>
                    <th>Outbound Call</th>
                    <th>Miss Call</th>
                    <th>VFO Call</th>
                    <th>SMS</th>
                    <th>Email</th>
                    <th>Payment GateWay Charge</th>
                    
<!--                    <th>Action</th>-->
                </tr>
                </thead>
                <tbody>
                    <?php foreach($plans as $plan) { ?>
                    <tr>
                <td><?php echo $plan['PlanMaster']['PlanName']; ?></td>
                <td><?php echo $plan['PlanMaster']['PlanType']; ?></td>
                <td><?php echo $plan['PlanMaster']['SetupCost']; ?></td>
                <td><?php echo $plan['PlanMaster']['RentalAmount']; ?></td>
                <td><?php echo $plan['PlanMaster']['Balance'].' Rs.'; ?></td>
                <td><?php echo $plan['PlanMaster']['RentalPeriod'].' '.$plan['PlanMaster']['PeriodType']; ?></td>
                <td><?php echo $plan['PlanMaster']['InboundCallCharge'].' Rs./'.$plan['PlanMaster']['InboundCallMinute'].' Min'; ?></td>
                <td><?php echo $plan['PlanMaster']['OutboundCallCharge'].' Rs./'.$plan['PlanMaster']['OutboundCallMinute'].' Min'; ?></td>
                <td><?php echo $plan['PlanMaster']['MissCallCharge'].' Rs.'; ?></td>
                <td><?php echo $plan['PlanMaster']['VFOCallCharge']." Rs."; ?></td>
                <td><?php echo $plan['PlanMaster']['SMSCharge']." Rs./".$plan['PlanMaster']['SMSLength']." Char"; ?></td>
                <td><?php echo $plan['PlanMaster']['EmailCharge']." Rs./email"; ?></td>
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

 <?php echo $this->Form->end(); ?>
