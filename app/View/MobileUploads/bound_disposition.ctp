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
            $('#validate-form').attr('action','<?php echo $this->webroot;?>MobileUploads/Bound_disposition_report');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>MobileUploads/Bound_disposition');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">Mobile Disposition</a></li>
    <li class="active"><a href="#">Mobile Disposition Report</a></li>
</ol>
<div class="page-heading">            
    <h1>Mobile Disposition REPORT</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Mobile DispositionReport</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('MobileUploads',array('action'=>'reports','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
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
                <h2>VIEW Mobile Upload  REPORT</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" >
                <!-- <table cellspacing="0" border="1"> -->
            <thead>
                <tr style="background-color:#317EAC; color:#FFFFFF;">                        
                        
                    <th>Sno.</th>			
                    <th>Category</th>
                    <th>Mobile No.</th>
                    <th>Dispositions</th>
                    <th>Sub Dispositions</th>
                    <th>Agent</th>
                    <th>Dial Id</th>
                    <th>Remarks</th>

                </tr>
                
            </thead>
            <tbody>

            <?php  $ik='1'; foreach ($Data as $result) { ?>
              
                    <tr>
                            <td><?php echo $ik++;?></td>
                            <td><?php echo $result['mudd']['category'];?></td>
                            <td><?php echo $result['mudd']['mobile'];?></td>
                            <td><?php echo $result['mudd']['dispositions'];?></td>
                            <td><?php echo $result['mudd']['sub_dispositions'];?></td>
                            <td><?php echo $result['am']['displayname'];?></td>
                            <td><?php echo $result['mudd']['dialid'];?></td>
                            <td><?php echo $result['mudd']['remarks'];?></td>
                    </tr>



                <?php 
                    } 
            						
            echo '</tbody>
            </table>';?>             
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>
      

    </div>
</div>




