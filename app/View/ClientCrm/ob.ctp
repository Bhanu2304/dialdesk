<?php  
echo $this->Html->script('ecr');
echo $this->Html->script('assets/main/dialdesk');
echo $this->Html->script('assets/main/formcomponents');

?>
<script src="<?php echo $this->webroot;?>js/closelooping.js"></script>

<style>
 body {font-family: Arial, Helvetica, sans-serif;} 

/* The modal2 (background) */
.modal2 {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* modal2 Content */
.modal2-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%; 
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Client Crm Master</a></li>
    <li class="active"><a href="#">Crm</a></li>
</ol>
<div class="container-fluid">
    <div data-widget-group="group1"> 
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>Plan Allocation</h2>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body">
            <table class="table">
                <tr>
                    <th>Client</th>
                    <th>Plan</th>
                    <th>Activation Date</th>
                    <th>Did No.</th>
                    <th>Campaign</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <th><?php echo $client_det['RegistrationMaster']['company_name']; ?></th>
                    <th><?php if($plan_action=='allocate'){ echo '<span style="color:red">No Plan Allocated</span>';} else {echo $plan_det['PlanMaster']['PlanName'];} ?></th>
                    <th><?php if($activation_date) { echo $activation_date;} else { echo '<span style="color:#FFBF00;">Testing</span>';} ?></th>
                    <th><?php if(!empty($did)) { echo $did;} else { echo '<span style="color:red">Did No. Not Mapped</a></span>';} ?></th>
                    <th><?php if($campaignid) { echo $campaignid;} else { echo '<span style="color:red">Campamign not Mapped</a></span>'; } ?></th>
                    <th> <select id="action1" onchange="manage_change(this.value)">
                    <option value="">Select</option>
                    <?php 
                        if($plan_action=='allocate') 
                        { 
                            echo '<option value="AdminPlans/allocate_plan">Allocate Plan</option>'; 
                        } 
                        else { 
                            if(!$activation_date) 
                            { echo '<option value="ClientAccounts/add_start_date"><span style="color:red">Plan Activation</span></a>';} 
                            echo '<option value="AdminPlans/reallocate_plan">Re-Allocate Plan</a>';
                        }
                        echo '<option value="'.$did_action.'">Manage DID</option>';
                        echo '<option value="'.$campaign_action.'">Manage Campaign</option>';
                      ?>
                      </select>
                      </th>
                </tr>
            </table>
            
        </div>
        
    </div>
    </div>
</div>

<div class="container-fluid">
    <div data-widget-group="group1"> 
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>Outbound Crm Creation</h2>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body">
        <table class="table">
                <tr>
                    
                    <th>Scenario</th>
                    <th>Required Fields</th>
                    <th>Close Looping</th>
                    
                    
                    <th>Action</th>
                </tr>
                <tr>
                    
                    <th><?php if($scenario) { echo $scenario;} else { echo '<span style="color:#FFBF00">Scenario Not Created</a></span>'; } ?></th>
                    <th><?php if($rfields) { echo $rfields;} else { echo '<span style="color:red">Fields not Created</a></span>'; } ?></th>
                    <th><?php if($closeloop_scenario) { echo $closeloop_scenario;} else { echo '<span style="color:#FFBF00">Close Loop not Created</a></span>'; } ?></th>
                    
                    
                    <th><select id="action2" onchange="manage_change(this.value)">
                        <option value="">Select</option>
                        <?php
                        
                        echo '<option value="'.$this->webroot.$scenario_action.'">Manage Scenario</option>';
                        echo '<option value="'.$this->webroot.$rfield_action.'">Manage Required Fields</option>';
                        echo '<option value="'.$this->webroot.$closeloop_action.'">Manage Close Loop</option>';
                        echo '<option value="'.$this->webroot.'ObEscalations/view_fields">Manage Alert & Escalations</option>';
                        ?>
                        </select>
                    </th>
                </tr>
            </table>
            
        </div>
        <div class="panel-footer"></div>
    </div>
    </div>
</div>

<div id="plan_model" class="modal2">

  <!-- modal2 content -->
  <div class="modal2-content">
    <span class="close">&times;</span>
    <div class="form-horizontal">   
    <?php echo $this->Form->create('AdminPlan',array('action'=>'save_plan_allocation','style'=>'display:none','id'=>'plan_allocation_disp')); ?> 
    <div class="panel-heading">
            <h2>Plan Allocation</h2>
            <div class="panel-ctrls"></div>
    </div>       
        <div class="form-group ">       
            <label class="col-sm-1 control-label">Client</label>
            <div class="col-sm-3">
                <?php echo $this->Form->input('clientId',array('label'=>false,'options'=>$client_list,'class'=>'form-control','required'=>true)); ?> 
            </div>
            <label class="col-sm-1 control-label">Plan</label>
            <div class="col-sm-3">
                <?php echo $this->Form->input('PlanId',array('label'=>false,'options'=>$PlanList,'empty'=>'Plan Name','class'=>'form-control','required'=>true)); ?>
                <?php echo $this->Form->input('PlanType',array('label'=>false,'type'=>'hidden','id'=>'PlanType','value'=>'Prepaid','class'=>'form-control')); ?> 
            </div>
            <label class="col-sm-1 control-label">Start Date</label>
            <div class="col-sm-2">
                <?php echo $this->Form->input('start_date',array('label'=>false,'id'=>'start_date1','class'=>'form-control date-picker','placeholder'=>'Plan Start Date','required'=>true,'autocomplete'=>'off')); ?> 
            </div>                
        </div>
        <div class="form-group">
            <div class="col-sm-2">
                <input type="submit" name="submit" value="Allocate" class="btn-web btn">
            </div>
        </div>    
    <?php echo $this->Form->end(); ?>

    <?php echo $this->Form->create('AdminPlan',array('action'=>'save_plan_re_allocation','style'=>'display:none','id'=>'plan_re_allocation_disp')); ?>
    <div class="panel-heading">
            <h2>Plan Re-Allocation</h2>
            <div class="panel-ctrls"></div>
    </div>           
    <div class="form-group">
        <label class="col-sm-1 control-label">Client</label>
            <div class="col-sm-3">
                <?php echo $this->Form->input('clientId',array('label'=>false,'options'=>$client_list,'class'=>'form-control','required'=>true)); ?> 
            </div>
            <label class="col-sm-1 control-label">Plan</label>
            <div class="col-sm-3">
                <?php echo $this->Form->input('PlanId',array('label'=>false,'options'=>$PlanList,'empty'=>'Select PlanName','class'=>'form-control','required'=>true)); ?>
            </div>
            <label class="col-sm-1 control-label">Start Date</label>
            <div class="col-sm-2">
                <?php echo $this->Form->input('start_date',array('label'=>false,'id'=>'start_date2','class'=>'form-control date-picker','placeholder'=>'Plan Start Date','required'=>true,'autocomplete'=>'off')); ?> 
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-2">
                <input type="submit" name="submit" value="submit" class="btn-web btn">
            </div>
        </div>
    <?php echo $this->Form->end(); ?>
    
    <?php echo $this->Form->create('ClientAccounts',array('action'=>'save_start_date','style'=>'display:none','id'=>'add_start_date_disp')); ?>
    <div class="panel-heading">
            <h2>Client Activation Date</h2>
            <div class="panel-ctrls"></div>
    </div> 
    <div class="form-group">
        <label class="col-sm-1 control-label">Client</label>
        <div class="col-sm-3">
            <?php echo $this->Form->input('clientId',array('label'=>false,'options'=>$client_list,'class'=>'form-control')); ?> 
        </div>
        <label class="col-sm-2 control-label">Activation Date</label>
        <div class="col-sm-2">
            <?php echo $this->Form->input('start_date',array('label'=>false,'id'=>'start_date3','placeholder'=>'Start Date','class'=>'form-control date-picker')); ?>
        </div>
        <div class="col-sm-2">
            <input type="submit" name="submit" value="Activate" class="btn-web btn">
        </div>
    </div>
    <?php echo $this->Form->end(); ?>


    <?php echo $this->Form->create('AdminDetails',array('action'=>'update_did2','style'=>'display:none','id'=>'manage_did_disp')); ?>
    <div class="panel-heading">
            <h2>Manage DID</h2>
            <div class="panel-ctrls"></div>
    </div> 
    <div class="form-group">
        <label class="col-sm-1 control-label">Client</label>
        <div class="col-sm-3">
            <?php echo $this->Form->input('clientId',array('label'=>false,'options'=>$client_list,'class'=>'form-control')); ?> 
        </div>
        <label class="col-sm-1 control-label">DID No.</label>
        <div class="col-sm-2">
        <?php echo $this->Form->input('did_number',array('label'=>false,'class'=>'form-control', 'placeholder'=>'DID Number','maxlength'=>'8','value'=>isset ($didnumber['DidMaster']['did_number']) ? $didnumber['DidMaster']['did_number'] : "","onkeyup"=>"this.value=this.value.replace(/[^0-9]/g,'')",'required'=>true)); ?>
        </div>   
        <label class="col-sm-1 control-label">Customer Care No.</label>
        <div class="col-sm-2">
        <?php echo $this->Form->input('customer_care_number',array('label'=>false,'class'=>'form-control', 'placeholder'=>'Customer Care Number','maxlength'=>'8','value'=>isset ($didnumber['DidMaster']['customer_care_number']) ? $didnumber['DidMaster']['customer_care_number'] : "","onkeyup"=>"this.value=this.value.replace(/[^0-9]/g,'')",'required'=>true)); ?>
        </div>  
        <div class="col-sm-2">
            <input type="submit" name="submit" value="Update" class="btn-web btn">
        </div>          
    </div>
    
    <?php echo $this->Form->end(); ?>

    <?php echo $this->Form->create('AdminDetails',array('action'=>'update_did2','style'=>'display:none','id'=>'manage_campaign_disp')); ?>
    <div class="panel-heading">
            <h2>Manage OB Campaign</h2>
            <div class="panel-ctrls"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-1 control-label">Client</label>
        <div class="col-sm-3">
            <?php echo $this->Form->input('clientId',array('label'=>false,'options'=>$client_list,'class'=>'form-control')); ?> 
        </div>
        <label class="col-sm-1 control-label">Campaign ID</label>
        <div class="col-sm-3">
        <?php echo $this->Form->input('campaignid',array('label'=>false,'class'=>'form-control', 'placeholder'=>'Campaign ID','value'=>isset ($campName) ? $campName : "",'required'=>true)); ?> 
        </div>
        <label class="col-sm-1 control-label">Group Id</label>
        <div class="col-sm-3">
        <?php echo $this->Form->input('GroupId',array('label'=>false,'class'=>'form-control', 'placeholder'=>'Group Id','value'=>isset ($grpName) ? $grpName : "",'required'=>true)); ?>
        </div>
        <label class="col-sm-1 control-label">Multilanguage Group ID</label>
        <div class="col-sm-3">
        <?php echo $this->Form->input('multilang_ivrs',array('label'=>false,'class'=>'form-control', 'placeholder'=>'Multilanguage Group ID','value'=>isset ($multiName) ? $multiName : "")); ?>
        </div>
        <div class="col-sm-2">
            <input type="submit" name="submit" value="Update" class="btn-web btn">
        </div>
    </div>    
    <?php echo $this->Form->end(); ?>

    
    
    </div>
    <div id="ecrs_disp" ></div>
    <div id="require_fields" ></div>
  </div>

</div>
<script>
var modal2 = document.getElementById("plan_model");
var span = document.getElementsByClassName("close")[0]; 

// When the user select the option, open the modal2 
function manage_change(url)
{
    //alert(url);
    document.getElementById("plan_allocation_disp").style.display="none";
    document.getElementById("plan_re_allocation_disp").style.display="none";
    document.getElementById("add_start_date_disp").style.display="none";
    document.getElementById("manage_did_disp").style.display="none";
    document.getElementById("manage_campaign_disp").style.display="none";
    document.getElementById("ecrs_disp").style.display="none";
    document.getElementById("require_fields").style.display="none";
    
    if(url=='AdminPlans/allocate_plan')
    {
        // popupWindow = window.open(url,'popUpWindow','height=500,width=800,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
         document.getElementById("plan_allocation_disp").style.display="block";
    }
    else if(url=='AdminPlans/reallocate_plan')
    {
        document.getElementById("plan_re_allocation_disp").style.display="block";
    }
    else if(url=='ClientAccounts/add_start_date')
    {
        document.getElementById("add_start_date_disp").style.display="block";
    }
    else if(url=='admin_details/clientdid')
    {
        document.getElementById("manage_did_disp").style.display="block";
    }
    else if(url=='admin_details/addcampaign')
    {
        document.getElementById("manage_campaign_disp").style.display="block";
    }
    else if(url=='/dialdesk/Obecrs')
    {
        // $.get(url+'/index2?client_id=<?php echo $client_id;?>', function(data, status){
        //     document.getElementById("ecrs_disp").innerHTML=data;
        // });
        // document.getElementById("ecrs_disp").style.display="block";
        centeredPopup(url+'/index2','myWindow','1000','600','yes');
    }
    else if(url=='/dialdesk/ObclientFields')
    {
        centeredPopup(url+'/index2','myWindow','1000','600','yes');
        // $.get(url+'/index2?client_id=<?php echo $client_id;?>', function(data, status){
        //     document.getElementById("require_fields").innerHTML=data;
        // });
        // document.getElementById("require_fields").style.display="block";
    }
    else if(url=='/dialdesk/ObcloseLoopings')
    {
        centeredPopup(url+'/index2','myWindow','1000','600','yes');
        // $.get(url+'/index2?client_id=<?php echo $client_id;?>', function(data, status){
        //     document.getElementById("require_fields").innerHTML=data;
        // });
        // document.getElementById("require_fields").style.display="block";
    }
    else if(url=='/dialdesk/ObEscalations/view_fields')
    {
         //popupWindow = window.open(url,'popUpWindow','height=300,width=700,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
        // $.get(url+'2?client_id=<?php echo $client_id;?>', function(data, status){
        //     document.getElementById("require_fields").innerHTML=data;
        // });
        // document.getElementById("require_fields").style.display="block";
        centeredPopup(url+'2','myWindow','1000','600','yes');
        
    }
    modal2.style.display = "block";
}

var popupWindow = null;
function centeredPopup(url,winName,w,h,scroll){
    LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
    TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
    settings =
    'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'
    popupWindow = window.open(url,winName,settings)
}

</script>
<script>


// When the user clicks on <span> (x), close the modal2
span.onclick = function() {
  modal2.style.display = "none";
}

// When the user clicks anywhere outside of the modal2, close it
window.onclick = function(event) {
  if (event.target == modal2) {
    modal2.style.display = "none";
  }
}
</script>

