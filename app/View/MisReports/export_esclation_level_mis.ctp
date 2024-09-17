<script> 		
function validateExport(url){
    $(".w_msg").remove();
    var report_type=$("#report_type").val();
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
             $('#validate-form').attr('action','<?php echo $this->webroot;?>MisReports/esclation_level_mis');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>MisReports/export_esclation_level_mis');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">Esclation Level MIS</a></li>
</ol>
<div class="page-heading">            
    <h1>Esclation Level MIS</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Esclation Level MIS</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('MisReports',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('clientID',array('label'=>false,'class'=>'form-control','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
                        </div>
                
                        <div class="col-sm-2">
                            <?php $category = ['All' => 'All','Complaint' => 'Complaint', 'Request' => 'Request', 'Enquiry' => 'Enquiry']?>
                            <?php  echo $this->Form->input('category',array('label'=>false,'options'=>$category,'class'=>'form-control','id'=>'category_type','required'=>'true','empty'=>'Select Category'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>


        <?php if(isset($html) && $html !=""){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW ABEND CALL MIS</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <?php echo $html;?>
                </table>
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>


    </div>
</div>




