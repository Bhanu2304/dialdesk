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
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AbandonReports/customer_wise_excel');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AbandonReports/client_live_agent');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">Realtime Agent map with clients</a></li>
</ol>
<div class="page-heading">            
    <h1>Realtime Agent map with clients</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Realtime Agent map with clients</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('AbandonReports',array('action'=>'client_live_agent','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <?php echo $this->Form->input('clientID',array('label'=>false,'multiple' => true,'height' => '200px','id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'empty'=>'Select Client','required'=>true)); ?>
                        </div>
                        
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
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
        
        <?php if(isset($data)){ ?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Realtime Agent map with clients</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                  <thead>
    <tr style="background-color:#317EAC; color:#FFFFFF;"> 
    <th>Sno.</th>             
    <th>Client Name</th> 
    <th>Skilled</th>;
    <th>Agent Count</th>
    </tr>
    
</thead>
<tbody>
    <?php
        $counter = 1;
     foreach($data as $k=>$v) { 
        $flag = true;

        ?>
     
         <?php foreach($v as $c=>$p) { ?>
            <tr>
                <?php if($flag) { ?>
                <td rowspan="<?php echo count($v); ?>"><?php echo $counter;?></td>    
                <td rowspan="<?php echo count($v); ?>"><?php echo $k;?></td> <?php  $flag = false; $counter++; } ?>
                <td><?php echo $c;?></td>
                <td><a href="<?php echo $this->webroot;?>AbandonReports/agent_name_excel?igpname=<?php echo $c;?>"><?php echo $p;?></a></td>
          </tr>   
          <?php  } ?>  

    
    <?php } ?>
</tbody>
                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>

        <?php } ?>
      

    </div>
</div>




