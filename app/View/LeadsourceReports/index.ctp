<?php ?>
<script> 		
function validateExport(url){
    $(".w_msg").remove();
    
    var fdate=$("#fdate").val();
    var ldate=$("#ldate").val();
    
    if(fdate ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select start date.</span>');
        return false;
    }
    else if(ldate ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select end date.</span>');
        return false;
    }
    else if((new Date(fdate).getTime()) > (new Date(ldate).getTime())) {
        $("#error").html('<span class="w_msg err" style="color:red;">Please select valid date.</span>');
        return false;
    }
    else{
        if(url ==="download"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>LeadsourceReports');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">LEAD REPORT</a></li>
    <li class="active"><a href="#">LEAD REPORT</a></li>
</ol>
<div class="page-heading">            
    <h1>LEAD REPORT</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>LEAD REPORT</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('LeadsourceReports',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                <div class="form-group">
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker'));?>
                    </div>
                    <div class="col-sm-2">
                        <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker'));?>
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




