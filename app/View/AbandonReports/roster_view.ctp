<?php ?>
<script>        
function validateExport(url){ //alert(url);
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
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AbandonReports/roster_view_excel');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AbandonReports/roster_view');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">Roster Data</a></li>
</ol>
<div class="page-heading">            
    <h1>Roster Data</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
       <!-- <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Roster Data</h2>
            </div>
           
           
        </div>-->
        
        <?php if(isset($data2)){ ?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Roster Data</h2>
                <div class="panel-ctrls"> <?php echo $this->Form->create('AbandonReports',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
 <?php $this->Form->end(); ?>
            </div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                  <thead>
    <tr style="background-color:#317EAC; color:#FFFFFF;"> 
    <th>Sno.</th>             
    <th>Client Name</th> 
    <?php 
   // print_r($slot_arr2);
            foreach($slot_arr2 as $sl)
            {
                echo '<th>'.$sl.'</th>';
            }
    ?>
    </tr>
    
</thead>
<tbody>
    <?php
        $counter = 1;
     foreach($client_list as $cl) { 
        $flag = true;

        ?>
      <tr>
      <td ><?php echo $counter++;?></td>  
      <td ><?php echo $cl;?></td>  
         <?php foreach($slot_arr2 as $sl) { ?>
           
                <td><?php echo count($data2[$cl][$sl]);?></td>
                
                <?php } ?>
          
          </tr>               
    
    <?php } ?>
</tbody>
                </table>              
            </div>
            
            <div class="panel-footer"></div>
        </div>

        <?php } ?>
      

    </div>
</div>




