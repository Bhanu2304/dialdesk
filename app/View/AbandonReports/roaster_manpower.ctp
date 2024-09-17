<?php ?>
<style>
  .form-control::placeholder
{
    /* font-weight: bold !important; */
    font-weight: normal !important;
    display: block;
    color:black !important;
    white-space: nowrap;
    min-height: 1.2em;
    padding: 0px 2px 1px;

}
</style>

<script>

    $(function () {
    $(".date-picker1").datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,changeYear: true});
    });

</script>

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
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AbandonReports/roaster_manpower_export');
        }
        if(url ==="view"){
            $('#validate-form').attr('action','<?php echo $this->webroot;?>AbandonReports/roaster_manpower');
        }
        $('#validate-form').submit();
        return true;
    }
}
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">Roaster Manpower Planning</a></li>
</ol>
<div class="page-heading">            
    <h1>Roaster Manpower Planning</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Roaster Manpower Planning</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('AbandonReports',array('action'=>'roaster_manpower','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-2">
                        <?php $options = ['All' => 'All','A' => 'A', 'B' => 'B'];?>
                            <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$options,'value'=>isset($companyid)?$companyid:"",'empty'=>'Select Category','required'=>true)); ?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker1','autocomplete'=>'off'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker1','autocomplete'=>'off'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('aht',array('label'=>false,'placeholder'=>'AHT (Second)','id'=>'ldate','required'=>'true','class'=>'form-control','autocomplete'=>'off'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('ut',array('label'=>false,'placeholder'=>'UTILIZATION %','id'=>'ldate','required'=>'true','class'=>'form-control','autocomplete'=>'off'));?>
                        </div>
                        <br><br><br>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div>
                        <!-- <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export" >
                        </div> -->
                        
                        <!-- <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div> -->
                        <div class="col-sm-6" style="margin-top:-8px;margin-left: -30px;">

                               
                        </div>
                        <?php if(isset($data) && !empty($data)){?>
                            <div class="col-sm-4" style="margin-top:-8px;float: right;">
                               <table cellpadding="0" cellspacing="0" border="1" >
                                <thead>
                                    <tr style="background-color:#317EAC; color:#FFFFFF;">
                                        <th>Aht (Second)</th>
                                        <th>Utilization</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $total_aht = 3600/$aht; ?></td>
                                        <td><?php echo $ut_data = 60*($ut/100)/60; ?></td>
                                    </tr>
                                </tbody>
                               </table>
                            </div>
                            <?php } ?>
                       
                    </div>

                <?php $this->Form->end(); ?>
            </div>
        </div>
        
        <?php if(isset($data) && !empty($data)){?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Roaster Manpower Planning View</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <thead>
                        <tr style="background-color:#317EAC; color:#FFFFFF;">
                                
                                
                            <th>Time Slot</th>			
                            <?php foreach($data as $dateLabel=>$timeArray) { echo "<th>".$dateLabel."</th>"; } ?>
                        </tr>
                        
                    </thead>
                    <tbody>
                        <?php $dataZ[] = 'Grand Total'; foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) {
                    $dataY = array();
                                    echo '<tr>';
                            echo "<td>$timeLabel</td>"; 

                        foreach($data as $dataLabel1=>$dataSub1) { 
                        
                                // $html.='<td>'.$data1[$dataLabel].'</td>';
                                
                            echo "<td>".$dataSub1[$dateLabel][$timeLabel]."</td>";
                                    $dataZ[$dataLabel1] += $dataSub1[$dateLabel][$timeLabel];
                                    }
                        
                            echo '</tr>';
                        }}

                    echo '<tr>';
                    foreach($dataZ as $k=>$gt)
                    { echo '<td>'.$gt.'</td>';

                    }echo '<tr>';

                    ?>						
                    </tbody>

                </table> 
                        </div>
                   
                        <div class="col-md-4">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <thead>
                        <tr style="background-color:#317EAC; color:#FFFFFF;">
                                
                                
                            <th>Slots</th>			
                            <?php foreach($dataActual as $dateLabel=>$timeArray) { echo "<th>".$dateLabel."</th>"; } ?>
                        </tr>
                        
                    </thead>
                    <tbody>
                        <?php  foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) {
                            echo '<tr>';
                            echo "<td>$timeLabel</td>"; 

                                foreach($dataActual as $dataLabel1=>$dataSub1) { 
                                
                                    echo "<td>".$dataSub1[$dateLabel][$timeLabel]."</td>";
                                    $dataZ[$dataLabel1] += $dataSub1[$dateLabel][$timeLabel];
                                    
                                    }
                        
                            echo '</tr>';
                        }}

                    ?>						
                    </tbody>

                </table> 
                        </div>
                        <div class="col-md-2">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                    <thead>
                        <tr style="background-color:#317EAC; color:#FFFFFF;">
                                
                                
                            <th>Req Shift</th>	
                            <th>Count</th>		
                        </tr>
                        
                          <?php $total = 0; foreach($count_array as $dateLabel=>$timeArray) 
                            { ?>
                            <tr>
                                <?php echo "<td>".$dateLabel."</td>"; ?>
                                <?php echo "<td>".$timeArray."</td>"; ?>
                                <?php $total += $timeArray; ?>
                                </tr>
                               <?php  } ?>

                               <tr>
                                <th>total</th> 
                                <td><?php echo $total; ?></td>
                            </tr>
                            <tr>
                            <th>Shrinkage @25%</th>
                            <?php $srink =  $total/($ut/100) ;
                            echo "<td>".round(number_format($srink),2)."</td>";
                            ?>
                            


                            </tr>
                               
                        
                        
                    </thead>
                    <tbody>
                       

                  						
                    </tbody>

                </table>
                        </div>
                    </div>
                </div>
                 
                
                
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php }?>
      

    </div>
</div>




