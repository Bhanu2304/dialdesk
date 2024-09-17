<script> 		
function validateExport(url){
    $(".w_msg").remove();
    
    var client=$("#client").val();

    
    if(client ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select Client.</span>');
        return false;
    }
    else{
        if(url ==="download"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AkaiFlows/call_flow_report');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<script>
  $(function () {
        $(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});
  });
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Call Flow</a></li>
    <li class="active"><a href="#">Call Flow Report</a></li>
</ol>
<div class="page-heading">            
    <h1>Call Flow Report</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Call Flow Report</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('AkaiFlows',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'empty'=>'Select Client','required'=>true)); ?>
                        </div>
                       
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
                        </div>

                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>

    </div>
</div>




