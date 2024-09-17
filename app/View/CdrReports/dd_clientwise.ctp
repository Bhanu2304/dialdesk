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
            $('#validate-form').attr('action','<?php echo $this->webroot;?>CdrReports/dd_clientwise');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>CdrReports/dd_clientwise');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">SLA Mis</a></li>
    <li class="active"><a href="#">SLA Client Wise</a></li>
</ol>
<div class="page-heading">            
    <h1>SLA Client Wise</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>SLA Client Wise Reports</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('CdrReports',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                    <?php if($this->Session->read('role') =="admin"){ ?>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'required'=>true,'multiple' => true)); ?>
                        </div>
                        <?php } ?>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker','autocomplete'=>'off'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker','autocomplete'=>'off'));?>
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
                        </div>
                        <!--
                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>-->
                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>
        
        <?php if(isset($data) && !empty($data)){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW SLA HOURLY REPORT</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <thead>
	<tr>
		<th rowspan="2"></th>			
		<?php 
                //print_r($datetimeArray); exit;
                foreach($datetimeArray as $dateLabel=>$timeArray) { echo "<th colspan=\"".count($timeArray)."\">$dateLabel</th>"; } ?>
	</tr>
	<tr>
		<?php foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { echo "<th>$timeLabel</th>"; } } ?>
	</tr>
</thead>
<tbody>
	
	
	
	
	
	<?php  $label_arr = array('Offered %','Total Calls Landed','Total Calls Answered','Total Calls Abandoned','AHT (In Sec)','Calls Ans (within 20 Sec)','Abnd Within Threshold','Abnd After Threshold','Ababdoned (%)','SL% (20 Sec)','AL%'); ?>
        
        
        <?php foreach($label_arr as $label) { ?>
	<tr>
            <?php echo '<td>'.$label.'</td>'; ?>
            <?php foreach($datetimeArray as $dateLabel=>$timeArray) { ?>
		<?php 
                    foreach($timeArray as $time)
                    { echo '<td>'.$data[$dateLabel][$time][$label].'</td>'; }
                    
                ?>
            <?php } ?>
	</tr>		
	<?php } ?>
        
</tbody>
                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>
      

    </div>
</div>




