<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=sla_monthly_report.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table cellspacing="0" border="1">
<thead>
    <tr style="background-color:#317EAC; color:#FFFFFF;">         
     <?php  $label_arr = array('Total','Answered','Manpower','Talk','wait','dispo','pause','hold','Al %','Total login','Net login','Utilization %'); ?>
	 <th>Date</th>	
	 <th>Time Slot</th>
					
		<?php foreach($label_arr as $label) { echo "<th>".$label."</th>"; } ?>
	</tr>
	
</thead>
<tbody>
	<?php $dataZ[] = 'Grand Total'; foreach($datearray as $date) { foreach($timearray as $time) {
        echo '<tr>';
		echo "<td>$date</td>"; 
		echo "<td>$time</td>"; 
		foreach($label_arr as $label=>$key)
		{
		echo "<td>{$data[$date][$time][$key]}</td>";
		$dataZ[$key] += $data[$date][$time][$key];

		}
         echo '</tr>'; 
	 }
	
  }
	//print_r($dataZ);die;
	//echo array_sum( $dataZ);die;
	echo '<tr>';
	echo '<td></td>';
	foreach($dataZ as $k=>$gt)
	{ 
	  echo '<td>'.$gt.'</td>';
	}
	echo '</tr>';
	

 ?>						
</tbody>
</table>


<?php exit; ?>
