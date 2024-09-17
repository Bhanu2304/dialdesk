<?php  
echo $this->Html->script('ecr');
echo $this->Html->script('assets/main/dialdesk');
echo $this->Html->script('assets/main/formcomponents');

?>
<script src="<?php echo $this->webroot;?>js/closelooping.js"></script>
<script>
    var catid = ["Category1","Category2","Category3","Category4","Category5",];
    var maxid = ["close_loop","parent-child","parent_id",];
</script>
<script>


function selectCategory(pid,id){
    
    if(pid.value ===""){
        var pcid='0'
    }
    else{
        var pcid=pid.value;
    }

    var k = Number(id) + Number(1);
  
    $.post("<?php echo $this->webroot?>CloseLoopings/selectCategory",{parent_id:pcid,divid:k},function(data){
       if(pid.value ==="" && id ==="1"){
           $("#Category3").html('');
           $("#Category4").html('');
           $("#Category5").html('');
       }
       if(pid.value ==="" && id ==="2"){
           $("#Category4").html('');
           $("#Category5").html('');
       }
       if(pid.value ==="" && id ==="3"){
           $("#Category5").html('');
       }
       $("#Category"+k).html(data);
    });
    
}


function showParentBycloseLoop(){
    $.post("<?php echo $this->webroot?>CloseLoopings/get_parent_action",{close_loop:$("#close_loop").val()},function(data){
        $("#parent_category").html(data);
    });
}


function selectLabel(label){
    $("#create_category").hide();
    $("#parent_category").hide();
    $("#CloseStatusDiv").hide();
    
    if(label =="1"){
        $("#create_category").show();
        $("#CloseStatusDiv").show();
    }
    if(label =="2"){
        $.post("<?php echo $this->webroot?>CloseLoopings/get_parent_action",{close_loop:$("#close_loop").val()},function(data){
            $("#parent_category").show();
            $("#parent_category").html(data);
        });
        $("#create_category").show();
        
    }
}

function checkorder(){
    $("#orderno").hide();
    if(document.getElementById('orderby').checked == true){
        $("#orderno").show();
    }
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
   function plan_allocate()
   {
        var client = $("#clientid").val();
        var PlanType = $("#PlanType").val();
        var plan_list = $("#plan_list").val();
        var start_date = $("#start_date1").val();
        if($.trim(client)===""){
            $('#clientid').focus();
            $("#elMsg").html('Select Client.').show();  
            return false;
        }
        else if($.trim(plan_list)===""){
            $('#plan_list').focus();
            $("#elMsg").html('Select Plan.').show();  
            return false;
        }
        else if($.trim(start_date)===""){
            $('#start_date1').focus();
            $("#elMsg").html('Select Date.').show();  
            return false;

        }else{

            $.post("AdminPlans/save_plan_allocation",{client : client,PlanType : PlanType,plan_list:plan_list,start_date:start_date},function(data){
                if(data !='')
                {
                    $("#close-login-popup").trigger('click');
                    $("#show-login-message").trigger('click');
                    $("#login-text-message").text(data);
                }
                //$('#type1').replaceWith(data);
            });
        }

   }
   function plan_activation()
   {
        var client = $("#client_id_activation").val();
        var start_date = $("#start_date3").val();
        if($.trim(client)===""){
            $('#client_id_activation').focus();
            $("#elMsg1").html('Select Client.').show();  
            return false;
        }
        else if($.trim(start_date)===""){
            $('#start_date3').focus();
            $("#elMsg1").html('Select Date.').show();  
            return false;

        }else{

            $.post("ClientAccounts/save_start_date",{client : client,start_date:start_date},function(data){
                if(data !='')
                {
                    $("#close-login-popup").trigger('click');
                    $("#show-login-message").trigger('click');
                    $("#login-text-message").text(data);
                }
                //$('#type1').replaceWith(data);
            });
        }
   }

   function plan_reallocate()
   {
        var client = $("#re_plan_client").val();
        var plan_list = $("#re_plan_id").val();
        var start_date = $("#start_date4").val();
        if($.trim(client)===""){
            $('#re_plan_client').focus();
            $("#elMsg2").html('Select Client.').show();  
            return false;
        }
        else if($.trim(plan_list)===""){
            $('#re_plan_id').focus();
            $("#elMsg2").html('Select Plan.').show();  
            return false;
        }
        else if($.trim(start_date)===""){
            $('#start_date4').focus();
            $("#elMsg2").html('Select Date.').show();  
            return false;

        }else{

            $.post("AdminPlans/save_plan_re_allocation",{client : client,plan_list:plan_list,start_date:start_date},function(data){
                if(data !='')
                {
                    $("#close-login-popup").trigger('click');
                    $("#show-login-message").trigger('click');
                    $("#login-text-message").text(data);
                    
                }
                //$('#type1').replaceWith(data);
            });
        }
   }

   function did_master()
   {
        var client = $("#did_client").val();
        var did_number = $("#did_number").val();
        var customer_care_number = $("#customer_care_number").val();
        if($.trim(client)===""){
            $('#did_client').focus();
            $("#elMsg4").html('Select Client.').show();  
            return false;
        }
        else if($.trim(did_number)===""){
            $('#did_number').focus();
            $("#elMsg4").html('Enter Did Number.').show();  
            return false;
        }
        else if($.trim(customer_care_number)===""){
            $('#customer_care_number').focus();
            $("#elMsg4").html('Enter Customer NUmber.').show();  
            return false;

        }else{

            $.post("AdminDetails/update_did2",{client : client,did_number:did_number,customer_care_number:customer_care_number},function(data){
                if(data == '1')
                {
                    $("#close-login-popup").trigger('click');
                    $("#show-login-message").trigger('click');
                    $("#login-text-message").text("DID No. Updated Successfully.");
                    
                }else{
                    alert(data);
                }
                //$('#type1').replaceWith(data);
            });
        }
   }

   function add_campaign()
   {
        var client = $("#camp_client_id").val();
        var campaignid = $("#campaignid").val();
        var GroupId = $("#GroupId").val();
        var multilang_ivrs = $("#multilang_ivrs").val();
        if($.trim(client)===""){
            $('#camp_client_id').focus();
            $("#elMsg5").html('Select Client.').show();  
            return false;
        }
        else if($.trim(campaignid)===""){
            $('#campaignid').focus();
            $("#elMsg5").html('Enter Campaign id.').show();  
            return false;
        }
        else if($.trim(GroupId)===""){
            $('#GroupId').focus();
            $("#elMsg5").html('Enter Group id.').show();  
            return false;

        }else if($.trim(multilang_ivrs)===""){
            $('#multilang_ivrs').focus();
            $("#elMsg5").html('Enter Multillanguage Group id.').show();  
            return false;

        }
        else{

            $.post("AdminDetails/update_campaign2",{client : client,campaignid:campaignid,GroupId:GroupId,multilang_ivrs:multilang_ivrs},function(data){

                if(data !='')
                {
                    $("#close-login-popup").trigger('click');
                    $("#show-login-message").trigger('click');
                    $("#login-text-message").text(data);
                    
                }
                
                //$('#type1').replaceWith(data);
            });
        }
   }

   

</script>
<style>
 body {font-family: Arial, Helvetica, sans-serif;} 
 .panel { margin : 0px 0 5px 0 !important;}

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
            <h2>Plan Details</h2>
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
                    <th><?php if($activation_date) { echo $activation_date;} else { echo '<span style="color:#FFD700;">Testing</span>';} ?></th>
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
            <h2>Inbound Crm Details</h2>
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
                    
                    <th><?php if($scenario) { echo $scenario;} else { echo '<span style="color:#FFD700">Scenario Not Created</a></span>'; } ?></th>
                    <th><?php if($rfields) { echo $rfields;} else { echo '<span style="color:red">Fields not Created</a></span>'; } ?></th>
                    <th><?php if($closeloop_scenario) { echo $closeloop_scenario;} else { echo '<span style="color:#FFD700">Close Loop not Created</a></span>'; } ?></th>
                    
                    
                    <th><select id="action2" onchange="manage_change(this.value)">
                        <option value="">Select</option>
                        <?php
                        
                        echo '<option value="'.$scenario_action.'">Manage Scenario</option>';
                        echo '<option value="'.$rfield_action.'">Manage Required Fields</option>';
                        echo '<option value="'.$closeloop_action.'">Manage Close Loop</option>';
                        echo '<option value="Escalations/view_fields">Manage Alert & Escalations</option>';
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

<div class="container-fluid">
    <div data-widget-group="group1"> 
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>Outbound Crm Details</h2>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body">
        <table class="table">
                <tr>
                    
                    <th>Scenario</th>
                    <th>Required Fields</th>
                    <th>Close Looping</th>
                    <th>List Id</th>
                    <th>Campaign Subtype</th>
                    
                    <th>Action</th>
                </tr>
                <tr>
                    
                    <th><?php if($obscenario) { echo $obscenario;} else { echo '<span style="color:#FFD700">Scenario Not Created</a></span>'; } ?></th>
                    <th><?php if($obrfields) { echo $obrfields;} else { echo '<span style="color:red">Fields not Created</a></span>'; } ?></th>
                    <th><?php if($obcloseloop_scenario) { echo $obcloseloop_scenario;} else { echo '<span style="color:#FFD700">Close Loop not Created</a></span>'; } ?></th>
                    <th><?php if($list_id) { echo $list_id;} else { echo '<span style="color:#FFD700">List Id not Created</a></span>'; } ?></th>
                    <th><?php if($camp_subtype) { echo $camp_subtype;} else { echo '<span style="color:#FFD700">Campaign subtype not Created</a></span>'; } ?></th>
                    
                    
                    <th><select id="action2" onchange="manage_change(this.value)">
                        <option value="">Select</option>
                        <?php
                        
                        echo '<option value="'.$obscenario_action.'">Manage Scenario</option>';
                        echo '<option value="'.$obrfield_action.'">Manage Required Fields</option>';
                        echo '<option value="'.$obcloseloop_action.'">Manage Close Loop</option>';
                        echo '<option value="ObEscalations/view_fields">Manage Alert & Escalations</option>';
                        echo '<option value="'.$list_action.'">Manage List Id</option>';
                        echo '<option value="'.$camp_action.'">Manage Campaign Subtype</option>';
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
            <div id="elMsg" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div> 
            <div class="panel-ctrls"></div>
    </div>       
        <div class="form-group ">       
            <label class="col-sm-1 control-label">Client</label>
            <div class="col-sm-3">
                <?php echo $this->Form->input('clientId',array('label'=>false,'options'=>$client_list,'class'=>'form-control','id'=>'clientid','required'=>true)); ?> 
            </div>
            <label class="col-sm-1 control-label">Plan</label>
            <div class="col-sm-3">
                <?php echo $this->Form->input('PlanId',array('label'=>false,'options'=>$PlanList,'empty'=>'Plan Name','class'=>'form-control','id'=>'plan_list','required'=>true)); ?>
                <?php echo $this->Form->input('PlanType',array('label'=>false,'type'=>'hidden','id'=>'PlanType','value'=>'Prepaid','class'=>'form-control')); ?> 
            </div>
            <label class="col-sm-1 control-label">Start Date</label>
            <div class="col-sm-2">
                <?php echo $this->Form->input('start_date',array('label'=>false,'id'=>'start_date1','class'=>'form-control date-picker','placeholder'=>'Plan Start Date','required'=>true,'autocomplete'=>'off')); ?> 
            </div>                
        </div>
        <div class="form-group">
            <div class="col-sm-2">
                <input type="button" name="submit" value="Allocate" onclick="plan_allocate();" class="btn-web btn">
            </div>
        </div>    
    <?php echo $this->Form->end(); ?>

    <?php echo $this->Form->create('AdminPlan',array('action'=>'save_plan_re_allocation','style'=>'display:none','id'=>'plan_re_allocation_disp')); ?>
     <div id="elMsg2" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div> 
    <div class="panel-heading">
            <h2>Plan Re-Allocation</h2>
            <div class="panel-ctrls"></div>
    </div>           
    <div class="form-group">
        <label class="col-sm-1 control-label">Client</label>
            <div class="col-sm-3">
                <?php echo $this->Form->input('clientId',array('label'=>false,'options'=>$client_list,'id'=>'re_plan_client','class'=>'form-control','required'=>true)); ?> 
            </div>
            <label class="col-sm-1 control-label">Plan</label>
            <div class="col-sm-3">
                <?php echo $this->Form->input('PlanId',array('label'=>false,'options'=>$PlanList,'id'=>'re_plan_id','empty'=>'Select PlanName','class'=>'form-control','required'=>true)); ?>
            </div>
            <label class="col-sm-1 control-label">Start Date</label>
            <div class="col-sm-2">
                <?php echo $this->Form->input('start_date',array('label'=>false,'id'=>'start_date4','class'=>'form-control date-picker','placeholder'=>'Plan Start Date','required'=>true,'autocomplete'=>'off')); ?> 
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-2">
                <input type="button" name="submit" value="submit" onclick="plan_reallocate()" class="btn-web btn">
            </div>
        </div>
    <?php echo $this->Form->end(); ?>
    
    <?php echo $this->Form->create('ClientAccounts',array('action'=>'save_start_date','style'=>'display:none','id'=>'add_start_date_disp')); ?>
    <div id="elMsg1" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div> 
    <div class="panel-heading">
            <h2>Client Activation Date</h2>
            <div class="panel-ctrls"></div>
    </div> 
    <div class="form-group">
        <label class="col-sm-1 control-label">Client</label>
        <div class="col-sm-3">
            <?php echo $this->Form->input('clientId',array('label'=>false,'options'=>$client_list,'class'=>'form-control','id'=>'client_id_activation')); ?> 
        </div>
        <label class="col-sm-2 control-label">Activation Date</label>
        <div class="col-sm-2">
            <?php echo $this->Form->input('start_date',array('label'=>false,'id'=>'start_date3','placeholder'=>'Start Date','class'=>'form-control date-picker')); ?>
        </div>
        <div class="col-sm-2">
            <input type="button" name="submit" value="Activate" onclick="plan_activation()" class="btn-web btn">
        </div>
    </div>
    <?php echo $this->Form->end(); ?>


    <?php echo $this->Form->create('AdminDetails',array('action'=>'update_did2','style'=>'display:none','id'=>'manage_did_disp')); ?>
    <div id="elMsg4" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div> 
    <div class="panel-heading">
            <h2>Manage DID</h2>
            <div class="panel-ctrls"></div>
    </div> 
    <div class="form-group">
        <label class="col-sm-1 control-label">Client</label>
        <div class="col-sm-3">
            <?php echo $this->Form->input('clientId',array('label'=>false,'options'=>$client_list,'id'=>'did_client','class'=>'form-control')); ?> 
        </div>
        <label class="col-sm-1 control-label">DID No.</label>
        <div class="col-sm-2">
        <?php echo $this->Form->input('did_number',array('label'=>false,'class'=>'form-control','id'=>'did_number','placeholder'=>'DID Number','maxlength'=>'8','value'=>isset ($didnumber['DidMaster']['did_number']) ? $didnumber['DidMaster']['did_number'] : "","onkeyup"=>"this.value=this.value.replace(/[^0-9]/g,'')",'required'=>true)); ?>
        </div>   
        <label class="col-sm-1 control-label">Customer Care No.</label>
        <div class="col-sm-2">
        <?php echo $this->Form->input('customer_care_number',array('label'=>false,'class'=>'form-control','id'=>'customer_care_number','placeholder'=>'Customer Care Number','maxlength'=>'8','value'=>isset ($didnumber['DidMaster']['customer_care_number']) ? $didnumber['DidMaster']['customer_care_number'] : "","onkeyup"=>"this.value=this.value.replace(/[^0-9]/g,'')",'required'=>true)); ?>
        </div>  
        <div class="col-sm-2">
            <input type="button" name="submit" value="Update" onclick="did_master()" class="btn-web btn">
        </div>          
    </div>
    
    <?php echo $this->Form->end(); ?>

    <?php echo $this->Form->create('AdminDetails',array('action'=>'update_campaign2','style'=>'display:none','id'=>'manage_campaign_disp')); ?>
    <div id="elMsg5" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div> 
    <div class="panel-heading">
            <h2>Manage IB Campaign</h2>
            <div class="panel-ctrls"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-1 control-label">Client</label>
        <div class="col-sm-3">
            <?php echo $this->Form->input('clientId',array('label'=>false,'options'=>$client_list,'class'=>'form-control','id'=>'camp_client_id')); ?> 
        </div>
        <label class="col-sm-1 control-label">Campaign ID</label>
        <div class="col-sm-3">
        <?php echo $this->Form->input('campaignid',array('label'=>false,'class'=>'form-control','id'=>'campaignid','placeholder'=>'Campaign ID','value'=>isset ($campName) ? $campName : "",'required'=>true)); ?> 
        </div>
        <label class="col-sm-1 control-label">Group Id</label>
        <div class="col-sm-3">
        <?php echo $this->Form->input('GroupId',array('label'=>false,'class'=>'form-control','id'=>'GroupId','placeholder'=>'Group Id','value'=>isset ($grpName) ? $grpName : "",'required'=>true)); ?>
        </div>
        <label class="col-sm-1 control-label">Multilanguage Group ID</label>
        <div class="col-sm-3">
        <?php echo $this->Form->input('multilang_ivrs',array('label'=>false,'class'=>'form-control','id'=>'multilang_ivrs','placeholder'=>'Multilanguage Group ID','value'=>isset ($multiName) ? $multiName : "")); ?>
        </div>
        <div class="col-sm-2">
            <input type="button" name="submit" value="Update" onclick="add_campaign()" class="btn-web btn">
        </div>
    </div>    
    <?php echo $this->Form->end(); ?>

    
    
    </div>
    <div id="ecrs_disp" ></div>
    <div id="require_fields" ></div>
  </div>

</div>

<!-- Edit Login Message Popup -->
<a class="btn btn-primary btn-lg" id="show-login-message" data-toggle="modal" data-target=".bs-example-modal-sm"></a>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm">
            <div class="modal-content">
                    <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h2 class="modal-title">Message</h2>
                    </div>
                    <div class="modal-body">
                        <p id="login-text-message" ></p>
                    </div>
                    <div class="modal-footer">
                            <button type="button" onclick="hidepopup()" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
            </div>
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
         modal2.style.display = "block";
    }
    else if(url=='AdminPlans/reallocate_plan')
    {
        document.getElementById("plan_re_allocation_disp").style.display="block";

        modal2.style.display = "block";
    }
    else if(url=='ClientAccounts/add_start_date')
    {
        document.getElementById("add_start_date_disp").style.display="block";
        modal2.style.display = "block";
    }
    else if(url=='admin_details/clientdid')
    {
        document.getElementById("manage_did_disp").style.display="block";
        modal2.style.display = "block";
    }
    else if(url=='admin_details/addcampaign')
    {
        document.getElementById("manage_campaign_disp").style.display="block";
        modal2.style.display = "block";
    }
    else if(url=='Ecrs')
    {
        // $.get(url+'/index2?client_id=<?php echo $client_id;?>', function(data, status){
        //     document.getElementById("ecrs_disp").innerHTML=data;
        // });
        // document.getElementById("ecrs_disp").style.display="block";
        centeredPopup(url+'/index2','myWindow','1000','600','yes');
    }
    else if(url=='ClientFields')
    {
        centeredPopup(url+'/index2','myWindow','1000','600','yes');
        // $.get(url+'/index2?client_id=<?php echo $client_id;?>', function(data, status){
        //     document.getElementById("require_fields").innerHTML=data;
        // });
        // document.getElementById("require_fields").style.display="block";
    }
    else if(url=='CloseLoopings')
    {
        centeredPopup(url+'/index2','myWindow','1000','600','yes');
        // $.get(url+'/index2?client_id=<?php echo $client_id;?>', function(data, status){
        //     document.getElementById("require_fields").innerHTML=data;
        // });
        // document.getElementById("require_fields").style.display="block";
    }
    else if(url=='Escalations/view_fields')
    {
         //popupWindow = window.open(url,'popUpWindow','height=300,width=700,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
        // $.get(url+'2?client_id=<?php echo $client_id;?>', function(data, status){
        //     document.getElementById("require_fields").innerHTML=data;
        // });
        // document.getElementById("require_fields").style.display="block";
        centeredPopup(url+'2','myWindow','1000','600','yes');
        
    }
    else if(url=='Obecrs')
    {
        centeredPopup(url+'/index2','myWindow','1000','600','yes');
    }
    else if(url=='ObclientFields')
    {
        centeredPopup(url+'/index2','myWindow','1000','600','yes');
    }
    else if(url=='ObcloseLoopings')
    {
        centeredPopup(url+'/index2','myWindow','1000','600','yes');
    }
    else if(url=='ObEscalations/view_fields')
    {
        centeredPopup(url+'2','myWindow','1000','600','yes');
        
    }
    else if(url=='AdminDetails/addcampaignlistid')
    {
        centeredPopup(url+'1','myWindow','1000','600','yes');
        
    }
    else if(url=='AdminDetails/addcampaignsubtype')
    {
        centeredPopup(url+'1','myWindow','1000','600','yes');
        
    }
    
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

