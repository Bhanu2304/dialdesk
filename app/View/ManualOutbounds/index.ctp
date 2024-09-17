<script>
function getAllocation(campaignId){
    $.post("<?php echo $this->webroot;?>ManualOutbounds/allocation_name",{campaignId: campaignId},function(data){
        $("#allocationId").html(data);
    }); 
}
</script>

<div id="tabs">
  <ul>
    <li><a href="#tabs-2">Campaign Details</a></li>
  </ul>
    
<div id="tabs-2">
    <div style="font-size: 15px;margin-left:20px;color:green;padding-bottom: 10px;"><?php echo $this->Session->flash();?></div>
    <?php echo $this->Form->create("ManualOutbounds",array("url"=>"index",'class'=>'form-horizontal row-border'));?>
   
    <div class="container-fluid">
        <div data-widget-group="group1">
            <div class="panel panel-default" data-widget='{"draggable": "false"}'>
                <div class="panel-heading">
                    <h2></h2>
                    <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                </div>
                <div data-widget-controls="" class="panel-editbox"></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Campaign Name</label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('campaignId',array('label'=>false,'options'=>$campaignName,'empty'=>'Select Campaign','required'=>true,'onchange'=>'getAllocation(this.value)','class'=>'form-control')); ?>
                             <input type="submit" name="submit" value="submit" class="btn btn-web pull-left">
                        </div>
                        
                        <label class="col-sm-2 control-label">Allocation Name</label>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('allocationId',array('label'=>false,'options'=>'','empty'=>'Select Allocation','required'=>true,'id'=>'allocationId','class'=>'form-control')); ?>
                        </div>
       
                    </div>
                </div>
            </div>      
        </div>
    </div>      
    <?php echo $this->Form->end(); ?>
  </div>  
</div>

<link rel="stylesheet" href="<?php echo $this->webroot;?>datepicker/jquery-ui.css">
<script src="<?php echo $this->webroot;?>datepicker/jquery-ui.js"></script>
<script>
    $(function() {
        $( ".date-picker" ).datepicker();
    });
</script>

