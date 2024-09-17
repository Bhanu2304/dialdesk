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
            $('#validate-form').attr('action','<?php echo $this->webroot;?>HarvestChat/export_report');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>HarvestChat/report');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">In Call Operations</a></li>
    <li class="active"><a href="#">Bot Data Report</a></li>
</ol>
<div class="page-heading">            
    <h1>Bot Data Report</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Bot Data Report</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('HarvestChat',array('action'=>'report','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
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
                <h2>VIEW Bot Data Report</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Chat Rcvd Mobile No.</th>			
                            <th>Ticket No.</th>
                            <th>Scenario</th>
                            <th>Sub Scenario</th>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Address</th>
                            <th>Name of Store</th>
                            <th>Expect Callback</th>
                            <th>Actual Callback</th>
                        </tr>
                        
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($data as $record) { ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $record['dw']['wa_id']; ?></td>
                            <td><?php echo $record['dw']['id']; ?></td>
                            <td><?php echo $record['dw']['scenario']; ?></td>
                            <td><?php echo $record['dw']['sub_scenario']; ?></td>
                            <td><?php echo $record['dw']['name']; ?></td>
                            <td><?php echo $record['dw']['phone_no']; ?></td>
                            <td><?php echo $record['dw']['address']; ?></td>
                            <td><?php echo $record['dw']['company_name']; ?></td>
                            <td><?php echo $record['dw']['ex_close_date'];  ?></td>
                            <td><?php echo $record['dw']['ac_close_date'];  ?></td>
                            <?php //echo substr($record['whatsapp_template']['number'],0,10); ?>
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


