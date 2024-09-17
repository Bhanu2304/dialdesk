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
            $('#validate-form').attr('action','<?php echo $this->webroot;?>ObCdrReports/cdr_mis');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>ObCdrReports/detailscdr');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Report</a></li>
    <li class="active"><a href="#">OB CDR  Report</a></li>
</ol>
<div class="page-heading">            
    <h1>OB CDR  REPORT</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>OB CDR Report</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('ObCdrReports',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
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
                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>
        
        <?php  if(isset($Data) && !empty($Data)){  ?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW CDR  REPORT</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <tr>
	<td>Agent</td>
	<td>Phone Number</td>
	<td>Call Date</td>
        <!--
	<td>Queue Time</td>
	<td>Start Time - Queue</td>
        -->
	<td>Start Time</td>
	<td>End Time</td>
        <!--
	<td>End time with Wrap Time</td>
        -->
	<td>Call Duration</td>
	<td>Wrap Time</td>
	<td>Recording</td>
    </tr>
	<?php
	foreach($Data as $dt)
        {
	?>
	<tr>
	<td><?php echo $dt['t2']['Agent'];?></td>
	<td><?php echo $dt[0]['PhoneNumber'];?></td>
	<td><?php echo $dt[0]['CallDate'];?></td>
        <!--
	<td><?php echo $dt[0]['Queuetime'];?></td>
	<td><?php echo $dt[0]['QueueStart'];?></td>
        -->
	<td><?php echo $dt[0]['StartTime'];?></td>
	<td><?php echo $dt[0]['Endtime'];?></td>
        <!--
	<td><?php echo $dt[0]['WrapEndTime'];?></td>
        -->
	
	<td><?php if($dt['t2']['Agent']=='VDCL') { echo "0:00:00"; } else { echo $dt['t2']['CallDuration']; } ?></td>
	<td><?php echo $dt[0]['WrapTime'];?></td>
        <td style="text-align: center;" ><a style="text-decoration: none;" href="http://182.71.80.196/download-recording/download.php?mode=DD&filename=<?php echo  $dt['t2']['lead_id'];?>"><i title="download" class="material-icons">file_download</i></a></td>
   	<!--
        <td style="text-align: center;" ><a style="text-decoration: none;" href="../app/webroot/rec_file/download.php?mode=dial&filename=<?php echo  $dt['t2']['lead_id'];?>&agt=<?php echo  $dt['t2']['Agent'];?>"><i title="download" class="material-icons">file_download</i></a></td>
   	-->
        </tr>
	 <?php } ?>
                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>
      

    </div>
</div>




