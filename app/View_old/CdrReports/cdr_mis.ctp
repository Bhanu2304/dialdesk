<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=import_format.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table cellspacing="0" border="1">
<thead>
	<tr>
	<td>Agent</td>
	<td>Phone Number</td>
	<td>Call Date</td>
	<td>Queue Time</td>
	<td>Start Time - Queue</td>
	<td>Start Time</td>
	<td>End Time</td>
	<td>End time with Wrap Time</td>
        <td>Call Duration Sec</td>
	<td>Call Duration Time</td>
	<td>Wrap Time</td>
        <td>Hold Time</td>
	
    </tr>
	<?php //print_r($Data);die;
	foreach($Data as $dt)
        {
	?>
	<tr>
	<td><?php echo $dt[0]['Agent'];?></td>
	<td><?php echo $dt[0]['PhoneNumber'];?></td>
	<td><?php echo $dt[0]['CallDate'];?></td>
	<td><?php echo $dt[0]['Queuetime'];?></td>
	<td><?php echo $dt[0]['QueueStart'];?></td>
	<td><?php echo $dt[0]['StartTime'];?></td>
	<td><?php echo $dt[0]['Endtime'];?></td>
	<td><?php echo $dt[0]['WrapEndTime'];?></td>
        <td><?php if($dt[0]['Agent']=='VDCL') { echo "0"; } else { echo $dt['t3']['CallDuration1']; } ?></td>
	<td><?php if($dt[0]['Agent']=='VDCL') { echo "0:00:00"; } else { echo $dt[0]['CallDuration']; } ?></td>
	<td><?php echo $dt[0]['WrapTime'];?></td>
        <td><?php echo $dt[0]['ParkedTime'];?></td>
	
   	</tr>
        <?php } ?>
</table>			
        <?php 
exit;
?>