<script> 		
function validateExport(){
    $(".w_msg").remove();
    var report_type=$("#report_type").val();
    var fdate=$("#fdate").val();
    var ldate=$("#ldate").val();
    
    if(report_type ===""){
        $("#error").html('<span class="w_msg err" style="color:red;">Please select report type.</span>');
        return false;
    }
    else if(fdate ===""){
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
        $('#validate-form').attr('action','<?php echo $this->webroot;?>MisReports/'+report_type);
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">Sales MIS</a></li>
</ol>
<div class="page-heading">            
    <h1>Sales MIS</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Sales MIS Reports</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('MisReports',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <?php  echo $this->Form->input('Report',array('label'=>false,'options'=>$report,'class'=>'form-control','id'=>'report_type','required'=>'true','empty'=>'Select Reports'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport();" class="btn btn-web" value="Export" >
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport();" class="btn btn-web" value="View" >
                        </div>
                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>




