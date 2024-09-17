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
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AbandonReports/agent_wise_excel');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AbandonReports/drop_call_reason');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">Abandon Call Reason</a></li>
</ol>
<div class="page-heading">            
    <h1>Abandon Call Reason</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Abandon Call Reason</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('AbandonReports',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Client','required'=>true)); ?>
                        </div>
                        
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker','autocomplete'=>'off'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker','autocomplete'=>'off'));?>
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
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
                <h2>Agents wise Utilization</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                 <thead>
    <tr style="background-color:#317EAC; color:#FFFFFF;">         
     <?php  $label_arr = array('Utilization %','Total Call','Talk Time'); ?>
     <th colspan="3">Date</th>
    <?php foreach($datearray as $date) { echo "<td colspan=\"3\" style=\"text-align:center;\">".date('d', strtotime($date));"</td>"; 
    } ?>
    <th rowspan="2">Utilization %</th>;
    </tr>
    <tr>
     <th>Agent</th>
     <th>Name</th>
     <th>DOJ</th>
        <?php foreach($datearray as $date) { 
     foreach($label_arr as $label) { echo "<th>".$label."</th>"; 
    } } ?>
    </tr>
    
</thead>
<tbody>
    <?php  $dataZ[] = 'Grand Total';
    foreach($timearray as $time) { echo '<tr>'; 
    echo "<td>$time</td>";
    echo "<td>".$name[$time]."</td>";
    echo "<td>".$doj[$time]."</td>";
    $dataU = array();
    foreach($datearray as $date) { 
        
       // echo "<td>$date</td>"; 
         //print_r($data[$date][$time]);exit;
        foreach($label_arr as $label=>$key)
        {
        echo "<td>{$data[$date][$time][$key]}</td>";
        $dataZ[$date][$key] += $data[$date][$time][$key];
        }
        $key = 'Utilization %';
        if(!empty($data[$date][$time][$key]))
        {
            $dataU['count'] +=1;
            $dataU[$key] +=$data[$date][$time][$key];
        }
     }
     echo '<td>';
        echo round($dataU['Utilization %']/$dataU['count'],2);
        $util = round($dataU['Utilization %']/$dataU['count'],2);
     echo '</td>';

     echo '</tr>'; 
     if(!empty($util))
            {
                $dataT['count'] +=1;
                $dataT['Utilization %'] +=$util;
            }
    
  }
    //print_r($dataZ);die;
    //echo array_sum( $dataZ);die;
    echo '<tr>';
    echo '<th colspan="3">Grand Total</th>';
    
    foreach($datearray as $date) {
            echo '<td></td>';
            echo "<td>{$dataZ[$date]['Total Call']}</td>";
            echo "<td>{$dataZ[$date]['Talk Time']}</td>";
        
       

    }
    echo '<td>';
        echo round($dataT['Utilization %']/$dataT['count'],2);
     echo '</td>';
    echo '</tr>';
    

 ?>                     
</tbody>
                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>
      

    </div>
</div>




