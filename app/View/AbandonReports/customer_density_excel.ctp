<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Slot_wise_Day_wise_Utilization.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style type="text/css">
    td {
  text-align: center;
}
<table cellspacing="0" border="1" >
<thead>
<tr><td colspan="25"><?php echo $clientId ; ?> - SLOT WISE DENSITY OF CALLS FROM <?php echo $FromDate;?> TO <?php echo $ToDate;?></td></tr>
    <tr style="background-color:#317EAC; color:#FFFFFF;">         
     <?php  $label_arr = array('Total','Answered','Abandon'); ?>
     <th>Date</th>
    <?php foreach($datearray as $date) { echo "<td colspan=\"3\" style=\"text-align:center;\">$date</td>"; 
    } ?>
   
    </tr>
    <tr>
     <th>Time Slot</th>
        <?php foreach($datearray as $date) { 
     foreach($label_arr as $label) { echo "<th>".$label."</th>"; 
    } } ?>
    </tr>
    
</thead>
<tbody>
    <?php //$dataZ[] = 'Grand Total';
    foreach($timearray as $time) { echo '<tr>'; 
    echo "<td>$time</td>";
    $dataU = array();
    foreach($datearray as $date) { 
        
       // echo "<td>$date</td>"; 
         
        foreach($label_arr as $label=>$key)
        {
        echo "<td>{$data[$date][$time][$key]}</td>";
        $dataZ[$date][$key] += $data[$date][$time][$key];
        }
       /* $key = 'Utilization %';
        if(!empty($data[$date][$time][$key]))
        {
            $dataU['count'] +=1;
            $dataU[$key] +=$data[$date][$time][$key];
        }*/
     }
    /* echo '<td>';
        echo round($dataU['Utilization %']/$dataU['count'],2);
        $util = round($dataU['Utilization %']/$dataU['count'],2);
     echo '</td>';*/

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
   // echo '<th>Grand Total</th>';
    
   /* foreach($datearray as $date) {
            echo "<td>{$dataZ[$date]['Answered']}</td>";
            echo "<td></td>";
            echo "<td>";
            //echo $dataZ[$date][]/$dataZ[$date][$key];
            echo "</td>";

        
        

    }
    echo '<td>';
        echo round($dataT['Utilization %']/$dataT['count'],2);
     echo '</td>';*/
    echo '</tr>';
    

 ?>                     
</tbody>
</table>


<?php exit; ?>
