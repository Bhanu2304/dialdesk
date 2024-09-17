
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
            $('#validate-form').attr('action','<?php echo $this->webroot;?>LoginLog/export_log');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>LoginLog/index');
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
    <li><a href="#">Login Log</a></li>
    <li class="active"><a href="#">Login Log</a></li>
</ol>
<div class="page-heading">            
    <h1>Login Log</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Login Log</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('LoginLog',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker1'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker1'));?>
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
        
        <?php if(isset($data) && !empty($data)){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW Login Log</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>IP Address</th>
                        <th>Page Name</th>
                        <th>Page Url</th>
                        <th>Hit Time</th>
                    </tr>

                    <?php $i =1;
                    foreach($data as $d)
                    {
                        echo "<tr>";
                        echo "<td>".$i++."</td>";
                        echo "<td>".$d['login_log']['user_name']."</td>";
                        echo "<td>".$d['login_log']['type']."</td>";
                        echo "<td>".$d['login_log']['ip_address']."</td>";
                        echo "<td>".$d['login_log']['page_name']."</td>";
                        echo "<td>".$d['login_log']['page_url']."</td>";
                        echo "<td>".date_format(date_create($d['login_log']['hit_time']),'d M Y H:i:s')."</td>";
                        echo "</tr>";
                    }
                    ?>    
                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>
      

    </div>
</div>




