<?php ?>
<script>
function showListId(type){ 
    $("#clientListId").hide();
    $("#ObReallocationsListid").hide();
    document.getElementById("ObReallocationsListid").required = false;
    if(type ==="pd"){
        $("#clientListId").show();
        $("#ObReallocationsListid").show();
        document.getElementById("ObReallocationsListid").required = true;
    }
}

function getAllocation(campid){
    if(campid !=""){
        $.post('<?php echo $this->webroot;?>ObReallocations/getAllocation',{campid:campid},function(data){
            $("#AllocationId").html(data);
        });

        $.post('<?php echo $this->webroot;?>ObReallocations/getScenario',{campid:campid},function(data){
            $("#ScenarioName").html(data);
        });
    }
    else{
        $("#AllocationId").html('');
        $("#ScenarioName").html('');
    }
}

function getAgent(type){
    if(type ==="Same"){
        $("#AgentId").html("<option value='Same' >Same</option>");
    }
    else{
        $.post('<?php echo $this->webroot;?>ObReallocations/getAgent',{type:type},function(data){
            $("#AgentId").html(data);
        });
    } 
}

function getAllocationName(AttemptId){
    var AllocationId =  $("#AllocationId").val();
    if(AttemptId !=""){
        $.post('<?php echo $this->webroot;?>ObReallocations/getAllocationName',{AllocationId:AllocationId,AttemptId:AttemptId},function(data){
            $("#AllocationName").val(data);
        });
    }
    else{
        $("#AllocationName").val('');
    }
}
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Out Call Management</a></li>
    <li class="active"><a href="#">Manage Re Allocations</a></li>
</ol>

<div class="page-heading">            
    <h1>Manage Re Allocations</h1>
</div>

<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Manage Re Allocations</h2>
                <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
            </div>
            <div data-widget-controls="" class="panel-editbox"></div>
            <div class="panel-body">	
            <?php   echo $this->Session->flash();?>  
            <?php   echo $this->Form->create('ObReallocations',array('action'=>'index','enctype'=>'multipart/form-data','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>      
                <div class="form-group">	
                    <div class="col-sm-6"> 
                        <?php echo $this->Form->input('CampaignName',array('label'=>false,'options'=>$Campaign,'empty'=>'Select Campaign','onchange'=>'getAllocation(this.value)','id'=>'CampaignName','class'=>'form-control','required'=>true ));?>     
                    </div>
                    
                    <div class="col-sm-6"> 
                        <?php echo $this->Form->input('AllocationId',array('label'=>false,'options'=>'','empty'=>'Select Allocation','id'=>'AllocationId','class'=>'form-control','required'=>true ));?>     
                    </div>
                    
                    <div class="col-sm-6"> 
                        <?php echo $this->Form->input('ScenarioName',array('label'=>false,'options'=>'','empty'=>'Select Scenario','multiple'=>'multiple','id'=>'ScenarioName','class'=>'form-control','required'=>true ));?>     
                    </div>
                    
                    <div class="col-sm-6"> 
                        <?php echo $this->Form->input('AgentType',array('label'=>false,'options'=>array('Same'=>'Same','Other'=>'Other'),'empty'=>'Agent Type','onchange'=>'getAgent(this.value)','id'=>'AgentType','class'=>'form-control','required'=>true ));?>     
                    </div> 
                    
                    <div class="col-sm-6"> 
                        <?php echo $this->Form->input('AgentId',array('label'=>false,'options'=>'','empty'=>'Select Agent','id'=>'AgentId','class'=>'form-control','required'=>true ));?>     
                    </div>
                   	
                    <div class="col-sm-6"> 
                        <!-- ,'pd'=>'PD' -->
                        <?php echo $this->Form->input('uploadType',array('label'=>false,'options'=>array('manual'=>'Manual'),'empty'=>'Allocation Type','onchange'=>'showListId(this.value)','class'=>'form-control','required'=>true ));?>     
                    </div>
                    
                    <div class="col-sm-6" id="clientListId" style="display: none;"  > 
                        <?php echo $this->Form->input('listid',array('label'=>false,'options'=>$viewListId,'empty'=>'Select List Id','class'=>'form-control'));?>      
                    </div>
                </div>
                
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar">
                                <input type="submit" class="btn btn-web pull-right" value="Submit" >
                            </div>
                        </div>
                    </div>
                </div>
                
            <?php echo $this->Form->end(); ?>
            </div> 
        </div>      
    </div>
</div>



