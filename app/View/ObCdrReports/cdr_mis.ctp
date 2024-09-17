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
	<td>Call Date</td>
	<td>Call Start Time</td>
	<td>Call End Time</td>
      
	<td>Customer  Number</td>
	<td>Agent ID</td>
      
	<td>Agent Name</td>
	<td>Call Type</td>
       
	<td>System Disposition</td>
        
	<td>Dialing Mode</td>
	<td>Client Name</td>
	<td>Lead ID</td>
	<td>ACHT</td>
	<td>Talk Time</td>
	<td>Wait Time</td>
	<td>Dispose Time</td>
	<td>Dead Time</td>
	
	
    </tr>
	<?php //print_r($Data);die;
	foreach($Data as $dt)
        {
	?>
	<tr>
	<td><?php echo $dt[0]['CallDate'];?></td>
	<td><?php echo $dt[0]['StartTime'];?></td>
	<td><?php echo $dt[0]['Endtime'];?></td>
	<td><?php echo $dt[0]['PhoneNumber'];?></td>
	<td><?php echo $dt['t2']['Agent'];?></td>
	<td><?php echo $dt['vu']['full_name'];?></td>
	<td><?php echo $dt[0]['calltype'];?></td>
	<td><?php echo $dt['t2']['status'];?></td>
	<td><?php echo $dt[0]['dialmode'];?></td>
	<td><?php echo $dt['t2']['campaign_id'];?></td>
	<td><?php echo $dt['t2']['lead_id'];?></td>
	<td><?php echo $dt['t3']['TalkSec']+$dt['t3']['DispoSec'];?></td>
	<td><?php echo $dt['t3']['TalkSec'];?></td>
	<td><?php echo $dt['t3']['WaitSec'];?></td>
	<td><?php echo $dt['t3']['DispoSec'];?></td>
	<td><?php echo $dt['t3']['dead_sec'];?></td>
	
   	</tr>
        <?php } ?>
</table>			
        <?php 
exit;
?>