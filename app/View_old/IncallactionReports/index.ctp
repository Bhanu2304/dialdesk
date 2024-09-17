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
            $('#validate-form').attr('action','<?php echo $this->webroot;?>IncallactionReports/export_callaction_mis');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>IncallactionReports');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>

<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">In Call Action MIS</a></li>
</ol>
<div class="page-heading">            
    <h1>In Call Action MIS</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>In Call Action MIS</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('IncallactionReports',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
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


        <?php if(isset($data) && !empty($data)){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>VIEW CALL MIS</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <tr>
                        <th>IN CALL ACTION</th>
                        <th>COUNT</th>
                        <th>%</th>
                    </tr>
                    <?php 
                    $TotalAction=0;
                    foreach($data as $v1){
                    $TotalAction=$TotalAction+$v1[0]['TotalCont'];
                    ?>
                        <tr>
                            <td><?php echo $v1['call_master']['ActionType'];?></td>
                            <td><?php echo $v1[0]['TotalCont'];?></td>
                            <td><?php echo round($v1[0]['TotalCont']*100/$TotalCount).'%';?></td>
                        </tr>
                    <?php } ?>
                    <?php $TotalNotAction= $TotalCount-$TotalAction; ?>
                        <tr>
                            <td>NOT CALL ACTION</td>
                            <td><?php echo $TotalNotAction; ?></td>
                            <td><?php echo round($TotalNotAction*100/$TotalCount).'%';?></td>
                        </tr>
                        <tr>
                            <td>TOTAL</td>
                            <td><?php echo $TotalCount; ?></td>
                            <td><?php echo round($TotalCount*100/$TotalCount).'%';?></td>
                        </tr>
                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>
    </div>
</div>




