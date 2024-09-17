<?php ?>
<script>
    function validateExport(url)
    {
        $(".w_msg").remove();
        
        var fdate=$("#fdate").val();
        var ldate=$("#ldate").val();
        
        if(fdate === ""){
            $("#error").html('<span class="w_msg err" style="color:red;">Please select start date.</span>');
            return false;
        }
        else if(ldate === ""){
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
                $('#validate-form').attr('action','<?php echo $this->webroot;?>AbandonReports/customer_wise');
            }
            $('#validate-form').submit();
            return true;
        }
    }
</script>
<ol class="breadcrumb">                                
    <li><a href="<?php echo $this->webroot;?>homes">Home</a></li>
    <li><a href="#">MIS & Reports</a></li>
    <li class="active"><a href="#">Customer/Date wise Density of calls</a></li>
</ol>
<div class="page-heading">            
    <h1>Customer/Date wise Density of calls</h1>
</div>
<div class="container-fluid">
    <div data-widget-group="group1">
        <div class="panel panel-default" data-widget='{"draggable": "false"}'>
            <div class="panel-heading">
                <h2>Customer/Date wise Density of calls</h2>
            </div>
            <div class="panel-body">
                <div id="error" style="color:red;font-size: 15px;"><?php echo $this->Session->flash();?></div>
                <?php echo $this->Form->create('AbandonReports',array('action'=>'index','id'=>'validate-form','class'=>'form-horizontal row-border','data-parsley-validate')); ?>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('clientID',array('label'=>false,'id'=>'client','required'=>'true','class'=>'form-control','options'=>$client,'empty'=>'Select Client','required'=>true)); ?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('category',array('label'=>false,'id'=>'category','required'=>'true','class'=>'form-control','options'=>array("All"=>"All","A"=>"A","B"=>"B"),'empty'=>'Client category','required'=>true)); ?>
                           
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('rType',array('label'=>false,'id'=>'rType','required'=>'true','class'=>'form-control','options'=>array("Offered"=>"Offered","Answered"=>"Answered","Abandon"=>"Abandon"),'empty'=>'Select Type','required'=>true)); ?>
                           
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('startdate',array('label'=>false,'placeholder'=>'Start Date','id'=>'fdate','required'=>'true','class'=>'form-control date-picker','autocomplete'=>'off'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('enddate',array('label'=>false,'placeholder'=>'End Date','id'=>'ldate','required'=>'true','class'=>'form-control date-picker','autocomplete'=>'off'));?>
                        </div>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('fetch_type',array('label'=>false,'id'=>'fetch_type','required'=>'true','class'=>'form-control','options'=>array("Client Wise"=>"Client Wise","Date Wise"=>"Date Wise"),'empty'=>'Select Type','required'=>true)); ?>
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View">
                        </div>
                        <div class="col-sm-2" style="margin-top:-8px;">
                            <input type="button" onclick="validateExport('download');" class="btn btn-web" value="Export">
                        </div>
                        <!--
                        <div class="col-sm-2" style="margin-top:-8px;margin-left: -30px;">
                            <input type="button" onclick="validateExport('view');" class="btn btn-web" value="View" >
                        </div> -->
                    </div>
                <?php $this->Form->end(); ?>
            </div>
        </div>
        
        <?php if(isset($data) && !empty($data) && $rType['rType']=="Answered"){ ?>
        <div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Customer/Slot wise Density of calls</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                  <thead>
    <tr style="background-color:#317EAC; color:#FFFFFF;">         
     <?php  $label_arr = array('Answered'); ?>
     <th>Slot</th>
    <?php 
    sort($timearray);
    foreach($timearray as $time) { echo "<td  style=\"text-align:center;\">$time</td>"; 
    } ?>
    <th>Answered</th>;
    <th>Offered</th>
    <th>Abandon</th>
    </tr>
    
    
</thead>
<tbody>
    <?php $dataZ[] = 'Grand Total';
    foreach($datearray as $date) {  echo '<tr>'; 
    echo "<td><a href=\"$this->webroot;?>AbandonReports/customer_wise\">$date</a></td>";
    $dataU = array();
    foreach($timearray as $time) {
        
       // echo "<td>$date</td>"; 
         //print_r($data[$date][$time]);exit;
        foreach($label_arr as $label=>$key)
        {
            if(!empty($data[$date][$time][$key]))
            {
        echo "<td>";
        echo $data[$date][$time][$key];
        echo "</td>";
            } else {

                echo "<td style=\"background-color:red\"></td>";
            }
        $dataZ[$time][$key] += $data[$date][$time][$key];
        }
        $key = 'Answered';
        if(!empty($data[$date][$time][$key]))
        {
            $dataU['count'] +=1;
            $dataU[$key] +=$data[$date][$time][$key];
        }
         $key = 'Offer';
        if(!empty($data[$date][$time][$key]))
        {
            $dataU['count'] +=1;
            $dataU[$key] +=$data[$date][$time][$key];
            $dataZ[$time][$key] += $data[$date][$time][$key];

        }
        $key = 'Abandon';
        if(!empty($data[$date][$time][$key]))
        {
            $dataU['count'] +=1;
            $dataU[$key] +=$data[$date][$time][$key];
            $dataZ[$time][$key] += $data[$date][$time][$key];

        }
     }
     echo '<td>';
        echo round($dataU['Answered'],2);

     echo '</td>';
     echo '<td>';
        echo round($dataU['Offer'],2);
        
     echo '</td>';
      echo '<td>';
        echo round($dataU['Abandon'],2);
        
     echo '</td>';

     echo '</tr>'; 
    
  }
    //print_r($dataZ);die;
    //echo array_sum( $dataZ);die;
    echo '<tr>';
    echo '<th>Grand Total</th>';
    $dataT = array();
    foreach($timearray as $time) {
    foreach($label_arr as $label=>$key)
        { 
            echo "<td>{$dataZ[$time][$key]}</td>";

        }
        $key = 'Answered';
            if(!empty($dataZ[$time][$key]))
            {
                $dataT['count'] +=1;
                $dataT[$key] +=$dataZ[$time][$key];
            }
        $key = 'Offer';
            if(!empty($dataZ[$time][$key]))
            {
                $dataT['count'] +=1;
                $dataT[$key] +=$dataZ[$time][$key];
            }
        $key = 'Abandon';
            if(!empty($dataZ[$time][$key]))
            {
                $dataT['count'] +=1;
                $dataT[$key] +=$dataZ[$time][$key];
            }        

    }
    echo '<td>';
        echo round($dataT['Answered'],2);
     echo '</td>';
     echo '<td>';
        echo round($dataT['Offer'],2);
     echo '</td>';
     echo '<td>';
        echo round($dataT['Abandon'],2);
     echo '</td>';
    echo '</tr>';
    

 ?>                     
</tbody>
                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php } else if(isset($data) && !empty($data) && $rType['rType']=="Offered") { ?>

<div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Customer/Slot wise Density of calls</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                  <thead>
    <tr style="background-color:#317EAC; color:#FFFFFF;">         
     <?php  $label_arr = array('Offer'); ?>
     <th>Slot</th>
    <?php
    sort($timearray);
     foreach($timearray as $time) { echo "<td  style=\"text-align:center;\">$time</td>"; 
    } ?>
    <th>Offered</th>;
    <th>Answered</th>
    <th>Abandon</th>
    </tr>
    
    
</thead>
<tbody>
    <?php $dataZ[] = 'Grand Total';
    foreach($datearray as $date) {  echo '<tr>'; 
    echo "<td><a href=\"$this->webroot"."AbandonReports/customer_density_excel?clientname="."$date"."&startdate="."{$rType['startdate']}"."&enddate="."{$rType['enddate']}\">$date</a></td>";
    $dataU = array();
    foreach($timearray as $time) {
        
       // echo "<td>$date</td>"; 
         //print_r($data[$date][$time]);exit;
        foreach($label_arr as $label=>$key)
        {
        if(!empty($data[$date][$time][$key]))
            {
        echo "<td>";
        echo $data[$date][$time][$key];
        echo "</td>";
            } else {

                echo "<td style=\"background-color:red\"></td>";
            }
        $dataZ[$time][$key] += $data[$date][$time][$key];
        }
        $key = 'Offer';
        if(!empty($data[$date][$time][$key]))
        {
            $dataU['count'] +=1;
            $dataU[$key] +=$data[$date][$time][$key];
        }
         $key = 'Answered';
        if(!empty($data[$date][$time][$key]))
        {
            $dataU['count'] +=1;
            $dataU[$key] +=$data[$date][$time][$key];
            $dataZ[$time][$key] += $data[$date][$time][$key];

        }
        $key = 'Abandon';
        if(!empty($data[$date][$time][$key]))
        {
            $dataU['count'] +=1;
            $dataU[$key] +=$data[$date][$time][$key];
            $dataZ[$time][$key] += $data[$date][$time][$key];

        }
     }
     echo '<td>';
        echo round($dataU['Offer'],2);

     echo '</td>';
     echo '<td>';
        echo round($dataU['Answered'],2);
        
     echo '</td>';
      echo '<td>';
        echo round($dataU['Abandon'],2);
        
     echo '</td>';

     echo '</tr>'; 
    
  }
    //print_r($dataZ);die;
    //echo array_sum( $dataZ);die;
    echo '<tr>';
    echo '<th>Grand Total</th>';
    $dataT = array();
    foreach($timearray as $time) {
    foreach($label_arr as $label=>$key)
        { 
            echo "<td>{$dataZ[$time][$key]}</td>";

        }
        $key = 'Offer';
            if(!empty($dataZ[$time][$key]))
            {
                $dataT['count'] +=1;
                $dataT[$key] +=$dataZ[$time][$key];
            }
        $key = 'Answered';
            if(!empty($dataZ[$time][$key]))
            {
                $dataT['count'] +=1;
                $dataT[$key] +=$dataZ[$time][$key];
            }
        $key = 'Abandon';
            if(!empty($dataZ[$time][$key]))
            {
                $dataT['count'] +=1;
                $dataT[$key] +=$dataZ[$time][$key];
            }        

    }
    echo '<td>';
        echo round($dataT['Offer'],2);
     echo '</td>';
    echo '<td>';
        echo round($dataT['Answered'],2);
     echo '</td>';
     
     echo '<td>';
        echo round($dataT['Abandon'],2);
     echo '</td>';
    echo '</tr>';
    

 ?>                     
</tbody>
 <?php } else if(isset($data) && !empty($data) && $rType['rType']=="Abandon") { ?>

<div class="panel panel-default" id="panel-inline">
            <div class="panel-heading">
                <h2>Customer/Slot wise Density of calls</h2>
                <div class="panel-ctrls"></div>
            </div>
            <div class="panel-body no-padding scrolling">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                  <thead>
    <tr style="background-color:#317EAC; color:#FFFFFF;">         
     <?php  $label_arr = array('Abandon'); ?>
     <th>Slot</th>
    <?php
    sort($timearray);
     foreach($timearray as $time) { echo "<td  style=\"text-align:center;\">$time</td>"; 
    } ?>
    <th>Offered</th>;
    <th>Answered</th>
    <th>Abandon</th>
    </tr>
    
    
</thead>
<tbody>
    <?php $dataZ[] = 'Grand Total';
    foreach($datearray as $date) {  echo '<tr>'; 
    echo "<td><a href=\"$this->webroot"."AbandonReports/customer_density_excel?clientname="."$date"."&startdate="."{$rType['startdate']}"."&enddate="."{$rType['enddate']}\">$date</a></td>";
    $dataU = array();
    foreach($timearray as $time) {
        
       // echo "<td>$date</td>"; 
         //print_r($data[$date][$time]);exit;
        foreach($label_arr as $label=>$key)
        {
        if(!empty($data[$date][$time][$key]))
            {
        echo "<td>";
        echo $data[$date][$time][$key];
        echo "</td>";
            } else {

                echo "<td style=\"background-color:red\"></td>";
            }
        $dataZ[$time][$key] += $data[$date][$time][$key];
        }
        $key = 'Offer';
        if(!empty($data[$date][$time][$key]))
        {
            $dataU['count'] +=1;
            $dataU[$key] +=$data[$date][$time][$key];
        }
         $key = 'Answered';
        if(!empty($data[$date][$time][$key]))
        {
            $dataU['count'] +=1;
            $dataU[$key] +=$data[$date][$time][$key];
            $dataZ[$time][$key] += $data[$date][$time][$key];

        }
        $key = 'Abandon';
        if(!empty($data[$date][$time][$key]))
        {
            $dataU['count'] +=1;
            $dataU[$key] +=$data[$date][$time][$key];
            $dataZ[$time][$key] += $data[$date][$time][$key];

        }
     }
     echo '<td>';
        echo round($dataU['Offer'],2);

     echo '</td>';
     echo '<td>';
        echo round($dataU['Answered'],2);
        
     echo '</td>';
      echo '<td>';
        echo round($dataU['Abandon'],2);
        
     echo '</td>';

     echo '</tr>'; 
    
  }
    //print_r($dataZ);die;
    //echo array_sum( $dataZ);die;
    echo '<tr>';
    echo '<th>Grand Total</th>';
    $dataT = array();
    foreach($timearray as $time) {
    foreach($label_arr as $label=>$key)
        { 
            echo "<td>{$dataZ[$time][$key]}</td>";

        }
        $key = 'Offer';
            if(!empty($dataZ[$time][$key]))
            {
                $dataT['count'] +=1;
                $dataT[$key] +=$dataZ[$time][$key];
            }
        $key = 'Answered';
            if(!empty($dataZ[$time][$key]))
            {
                $dataT['count'] +=1;
                $dataT[$key] +=$dataZ[$time][$key];
            }
        $key = 'Abandon';
            if(!empty($dataZ[$time][$key]))
            {
                $dataT['count'] +=1;
                $dataT[$key] +=$dataZ[$time][$key];
            }        

    }
    echo '<td>';
        echo round($dataT['Offer'],2);
     echo '</td>';
    echo '<td>';
        echo round($dataT['Answered'],2);
     echo '</td>';
     
     echo '<td>';
        echo round($dataT['Abandon'],2);
     echo '</td>';
    echo '</tr>';
    

 ?>                     
</tbody>
        

                </table>              
            </div>
            <div class="panel-footer"></div>
        </div>


        <?php } ?>
      

    </div>
</div>




