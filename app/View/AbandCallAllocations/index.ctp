<script>
$(document).ready(function(){
    $("#select_all").change(function(){  //"select all" change
        $(".clientRights").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    //".checkbox" change
    $('.clientRights').change(function(){
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(false == $(this).prop("checked")){ //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.clientRights:checked').length == $('.checkbox').length ){
            $("#select_all").prop('checked', true);
        }
    });
});

function getAgentRights(id){
    if(id !=""){
        window.location.href = "<?php echo $this->webroot;?>AbandCallAllocations?id="+id;
    }
    else{
        window.location.href = "<?php echo $this->webroot;?>AbandCallAllocations"; 
    }
}
</script>
<style>
.client-dtails{
    height: 350px;
    margin-left: -40px;
    overflow: auto;
    width: 383px;
} 

.client-dtails ol{
    list-style: outside none none;
}
</style>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a >Agent Calling Allocation</a></li>
    <li class="active"><a href="<?php echo $this->webroot;?>AgentCreations">Client Rights Allocation</a></li>
</ol>
<div class="page-heading">            
    <h1>Client Rights Allocation</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Client Rights Allocation</h2>
            </div>
            <div class="panel-body">
                <font style="color:green;"><?php echo $this->Session->flash(); ?></font>
                 <?php echo $this->Form->create('AbandCallAllocations',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>

                <div class="col-md-6">
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                            <?php echo $this->Form->input('Agent',array('label'=>false,'id'=>'name','options'=>$agent,'value'=>$AgentId,'empty'=>'Select Agent','onchange'=>'getAgentRights(this.value)','class'=>'form-control','required'=>true));?>
                        </div>
                    </div>
                    
                    <div class="col-xs-12">
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user">Client Details</i>
                            </span> 
                            <label><input type='checkbox' name='select_all' id="select_all" value='' style="margin-top:30px;" > All </label>
                            <div class="client-dtails">
                                <ol>
                                    
                                <?php 
                                    foreach($client as $key=>$val){ 
                                    if(in_array($key,$rights)){$check='checked';}else{$check='';}
                                    ?>
                                    <li><div class='checkbox-primary'><label><input type='checkbox' class="clientRights" name='clientRights[]' <?php echo $check;?>  value='<?php echo $key;?>'> <?php echo $val;?> 
                                <?php }?>         
                                </ol>
                            </div>    
                        </div>
                    </div>

                </div>

                <div class="col-md-12" style="margin-top:10px;">
                    <div class="col-xs-12"  >
                        <div class="input-group">							
                            <span class="input-group-addon">
                                <i class="ti ti-user"></i>
                            </span>
                             <input type="submit" class="btn btn-web pull-left" value="Submit" >
                       </div>
                    </div>
                </div>
                
                 <?php echo $this->Form->end(); ?>   

            </div>
        </div>
    </div>
</div>
