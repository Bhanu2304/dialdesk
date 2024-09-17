<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=customer_wise_density_call.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style type="text/css">
    td {
  text-align: center;
}

</style>
<?php if($rType['rType']=="Answered") { ?>
<table cellspacing="0" border="1">
 <thead>
    <tr><td colspan="25">CUSTOMER/SLOT WISE DENSITY OF CALLS FROM <?php echo $rType['startdate'];?> TO <?php echo $rType['enddate'];?></td></tr>
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
    echo "<td>$date</td>";
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
<?php } else if($rType['rType']=="Offered") { ?>

<table cellspacing="0" border="1">
  <thead>
    <tr><td colspan="25">CUSTOMER/SLOT WISE DENSITY OF CALLS FROM <?php echo $rType['startdate'];?> TO <?php echo $rType['enddate'];?></td></tr>
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
    echo "<td>$date</td>";
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
<?php } else if($rType['rType']=="Abandon") { ?>

<table cellspacing="0" border="1">
  <thead>
    <tr><td colspan="25">CUSTOMER/SLOT WISE DENSITY OF CALLS FROM <?php echo $rType['startdate'];?> TO <?php echo $rType['enddate'];?></td></tr>
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
    echo "<td>$date</td>";
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

 <?php } ?> 

<?php exit; ?>
