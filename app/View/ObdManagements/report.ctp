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
            $('#validate-form').attr('action','<?php echo $this->webroot;?>ObdManagements/export_report');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>ObdManagements/report');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Obd Management</a></li>
    <li class="active"><a href="#">Report</a></li>
</ol>
<div class="page-heading">            
    <h1>Report</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Report</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('ObdManagement',array('action'=>'report','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker'));?>
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export">
                        </div>
                        <!--
                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                        -->
                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>
        
        <?php if(isset($data) && !empty($data)){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Date</th>			
                            <th>Lead Id</th>
                            <th>User</th>
                            <th>Source Id</th>
                            <th>List Id</th>
                            <th>Phone Number</th>
                            <th>GMT Offset Now</th>
                            <th>Status</th>
                        </tr>
                        
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($data as $record) { ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo date_format(date_create($record['vicidial_list']['entry_date']),'d M Y H:i:s'); ?></td>
                            <td><?php echo $record['vicidial_list']['lead_id']; ?></td>
                            <td><?php echo $record['vicidial_list']['user']; ?></td>
                            <td><?php echo $record['vicidial_list']['source_id']; ?></td>
                            <td><?php echo $record['vicidial_list']['list_id']; ?></td>
                            <td><?php echo $record['vicidial_list']['phone_number']; ?></td>
                            <td><?php echo $record['vicidial_list']['gmt_offset_now']; ?></td>
                            <td><?php echo $record['vicidial_list']['status']; ?></td>
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


