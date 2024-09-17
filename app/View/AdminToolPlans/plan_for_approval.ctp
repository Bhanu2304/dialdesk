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
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <b><h2 style="color:green;"><?php echo $this->Session->flash(); ?></h2></b>
            <div class="panel-ctrls"></div>
        </div>
        <?php if(isset($plans) && !empty($plans)){?>
        
        <div class="panel-body no-padding scrolling ">
             <table class="table table-striped table-bordered datatables"> 
                <thead>
                <tr>
                        <th style="text-align:left;">Sr. No.</th>
                        <th style="text-align:left;">Action</th>
                        <th style="text-align:left;">Plan</th>
                        <th style="text-align:left;">Reject Remarks</th>
                        <th style="text-align:left;">Quarterly Rental</th>
                        <th style="text-align:left;">Inbound Whatsapp Charge</th>
                        <th style="text-align:left;">Outbound Whatsapp Charge</th>
                        <th style="text-align:left;">Free Minute</th>
                        <th style="text-align:left;">Call Forwarding Charges</th>
                        <th style="text-align:left;">Pulse</th>
                        <th style="text-align:left;">Order Starting Date</th>
                        <th style="text-align:left;">Order End Date</th>
                        <th style="text-align:left;">Next Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; foreach($plans as $plan) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><a href="edit_plan_forapprove?tool_id=<?php echo $plan['PlanToolMaster']['Id']; ?>" >Approve</a></td>
                        <td><?php echo $plan['PlanToolMaster']['PlanName']; ?></td>
                        <td><?php echo $plan['PlanToolMaster']['reject_remarks']; ?></td>
                        <td style="text-align:right;"><?php echo $plan['PlanToolMaster']['RentalAmount']; ?></td>
                        <td style="text-align:right;"><?php echo $plan['PlanToolMaster']['InboundWhatsappCharge']; ?></td>
                        <td style="text-align:right;"><?php echo $plan['PlanToolMaster']['OutboundWhatsappCharge']; ?></td>
                        
                        <td style="text-align:right;"><?php echo number_format($plan['PlanToolMaster']['FreeMinutes'],2); ?></td>
                        <td style="text-align:right;"><?php echo number_format($plan['PlanToolMaster']['CallForwardingCharges'],2); ?></td>
                        <td style="text-align:right;"><?php echo number_format($plan['PlanToolMaster']['Pulse'],2); ?></td>
                        <td style="text-align:right;"><?php echo $plan['PlanToolMaster']['OrderStartingDate']; ?></td>
                        <td style="text-align:right;"><?php echo $plan['PlanToolMaster']['OrderEndDate']; ?></td>
                        <td style="text-align:right;"><?php echo $plan['PlanToolMaster']['NextDueDate']; ?></td>
                
<!--                <td><a href="edit_plan?id=<?php //echo $plan['PlanMaster']['Id']; ?>">Edit</a></td>-->
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            
        </div>
        <div class="panel-footer"></div>
    
    <?php }?>
        </div>
     <?php if(isset($plans) && empty($plans)){echo "No Record";}?>   
    </div>
</div>

 
