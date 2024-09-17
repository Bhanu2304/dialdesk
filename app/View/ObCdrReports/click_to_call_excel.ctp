<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=ob_cdr_report.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table cellspacing="0" border="1">
<thead>

	

	<tr>
	<td>Agent</td>
	<td>Phone Number</td>
	<td>Call Date</td>

	<td>Start Time</td>
	<td>End Time</td>
 
	<td>Call Duration</td>
	<td>Call Status</td>
	
	
    </tr>
	<?php //print_r($Data);die;
	foreach($Data as $dt)
        {
	?>
	<tr>
	<td><?php echo $dt[0]['Agent_No'];?></td>
	<td><?php echo $dt[0]['MSISDN'];?></td>
	<td><?php echo $dt[0]['Call_Date'];?></td>
  
	<td><?php echo $dt['b']['start_time'];?></td>
	<td><?php echo $dt['b']['end_time'];?></td>

	
        <td><?php echo $dt['b']['length_in_sec'];?></td>
        <td><?php echo $dt['a']['call_status'];?></td>
	
   	</tr>
        <?php } ?>
</table>			
        <?php 
exit;
?>