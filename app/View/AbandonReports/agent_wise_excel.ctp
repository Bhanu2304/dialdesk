<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Agent_wise_Day_wise_Utilization.xls");
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
     <?php  $label_arr = array('Utilization %','Total Call','Talk Time','Productive','Login Time'); ?>
     <th colspan="3">Date</th>
    <?php foreach($datearray as $date) { echo "<td colspan=\"5\" style=\"text-align:center;\">".date('d', strtotime($date));"</td>"; 
    } ?>
    <!--<th rowspan="2">Utilization %</th>-->
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
   //  echo '<td>';
    //    echo round($dataU['Utilization %'],2);
    //    $util = round($dataU['Utilization %'],2);
    // echo '</td>';

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
            echo "<td>".round($dataZ[$date]['Productive']/$dataZ[$date]['Login Time']*100,2)."</td>";
            echo "<td>{$dataZ[$date]['Total Call']}</td>";
            echo "<td>{$dataZ[$date]['Talk Time']}</td>";
            echo "<td>{$dataZ[$date]['Productive']}</td>";
            echo "<td>{$dataZ[$date]['Login Time']}</td>";
        
       

    }
    //echo '<td>';
   //     echo round($dataT['Utilization %']/$dataT['count'],2);
    // echo '</td>';
    echo '</tr>';
    

 ?>                     
</tbody>
</table>


<?php exit; ?>
