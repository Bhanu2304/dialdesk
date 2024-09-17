<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Abandon_call_trend.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style type="text/css">
    td {
  text-align: center;
}

</style>
<table cellspacing="0" border="1">
 <thead>
   <tr style="background-color:#317EAC; color:#FFFFFF;">         
     <?php  $label_arr = array('Abandon %'); ?>
     <th>Date</th>
    <?php foreach($datearray as $date) { echo "<td style=\"text-align:center;\">$date</td>"; 
    } ?>
   
    </tr>
    <tr>
     <th>Campaign</th>
        <?php foreach($datearray as $date) { 
     foreach($label_arr as $label) { echo "<th>".$label."</th>"; 
    } } ?>
    </tr>
    
</thead>
<tbody>
    <?php 
    foreach($timearray as $time) { echo '<tr>'; 
    echo "<td>$time</td>";
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


     echo '</tr>'; 
     if(!empty($util))
            {
                $dataT['count'] +=1;
                $dataT['Utilization %'] +=$util;
            }
    
  }

 ?>                     
</tbody>
</table>


<?php exit; ?>
