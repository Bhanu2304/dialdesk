
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

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>AdminDetails">Home</a></li>
    <li><a >Plan Master</a></li>
    <li class="active"><a href="#">Plan Master</a></li>
</ol>

<div class="container-fluid">
    <div data-widget-group="group1">
        
        <div class="panel panel-default" id="panel-inline">
        <div class="panel-heading">
            <h2>Plan Master</h2>
            <div class="panel-ctrls"></div>
        </div>
        <div class="panel-body">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->Form->create('ClientAccounts',array('class'=>'form-horizontal')); ?>    
            <div class="form-group">
                <label class="col-sm-1 control-label">Client</label>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('clientId',array('label'=>false,'options'=>$ClientName,'empty'=>'Select Client','class'=>'form-control')); ?> 
                </div>
                <label class="col-sm-1 control-label">Start Date</label>
                <div class="col-sm-3">
                    <?php echo $this->Form->input('start_date',array('label'=>false,'placeholder'=>'Start Date','class'=>'form-control date-picker')); ?>
                </div>
                <div class="col-sm-2">
                    <input type="submit" name="submit" value="submit" class="btn-web btn">
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
        <div class="panel-footer"></div>
        </div>
    </div>
    
</div>
 
