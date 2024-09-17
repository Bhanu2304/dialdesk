<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=cdr_report.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table cellspacing="0" border="1">
<thead>
	<tr>
	<td>CallDate</td>
	<td>Time</td>
	<td>Agent Id</td>
	<td>Agent Name</td>
	<td>Calltype</td>
	<td>Campaign Name</td>
	<td>Phone Number</td>
	<td>Disposition</td>
        <td>Disconn.By</td>
	<td>Call Duration Second</td>
	<td>Call Duration Minute</td>
	<td>Queue Duration</td>
        <td>Hold Time</td>
        <td>Talkduration</td>
        <td>Acwduration (Wrapup or Dispo time)</td>
        <td>Hours Slot</td>
        <td>Total Handled Time</td>
        <td>Call 20 Sec (SL)</td>
        <td>End Time</td>
        <td>Call Transfer Id</td>
	
    </tr>
	<?php //print_r($Data);die;
	foreach($Data as $dt)
        {
	?>
	<tr>
	<td><?php echo $dt[0]['CallDate'];?></td>
	<td><?php echo $dt[0]['StartTime'];?></td>
	<td><?php echo $dt[t2]['Agent'];?></td>
	<td><?php echo $dt[vc]['full_name'];?></td>
	<td><?php echo "Inbound";?></td>
	<td><?php echo $dt[t2]['campaign_id'];?></td>
	<td><?php echo $dt[0]['PhoneNumber'];?></td>
	<td><?php echo $dt[t2]['status'];?></td>
	<td><?php echo $dt[t2]['term_reason'];?></td>
    <td><?php if($dt[0]['Agent']=='VDCL') { echo "0:00:00"; } else { echo $dt[0]['CallDuration1']; } ?></td>
	<td><?php if($dt[0]['Agent']=='VDCL') { echo "0:00:00"; } else { echo $dt[0]['CallDuration']; } ?></td>
	<td><?php echo $dt[0]['Queuetime'];?></td>
    <td><?php echo $dt[0]['ParkedTime'];?></td>
    <td><?php echo $dt[0]['CallDuration'];?></td>
        <?php
        $talk=explode(':',$dt[0]['CallDuration']);
                $tadl=($talk[0]*60*60)+($talk[1]*60)+$talk[2];
                $TotalLogin1= $dt[0]['WrapTime'];
		$totalHandle=$TotalLogin1+$tadl;
		//echo $tadl;die;
                 $seconds1 = $totalHandle % 60;
$time1 = ($totalHandle - $seconds1) / 60;
$minutes1 = $time1 % 60;
$hours1 = ($time1 - $minutes1) / 60;

$minutes1 = ($minutes1<10?"0".$minutes1:"".$minutes1);
$seconds1 = ($seconds1<10?"0".$seconds1:"".$seconds1);
$hours1 = ($hours1<10?"0".$hours1:"".$hours1);

$Totalh = ($hours1>0?$hours1.":":"00:").$minutes1.":".$seconds1;
                
                
                
                
		 $seconds = $TotalLogin1 % 60;
$time = ($TotalLogin1 - $seconds) / 60;
$minutes = $time % 60;
$hours = ($time - $minutes) / 60;

$minutes = ($minutes<10?"0".$minutes:"".$minutes);
$seconds = ($seconds<10?"0".$seconds:"".$seconds);
$hours = ($hours<10?"0".$hours:"".$hours);

$TotalLogin = ($hours>0?$hours.":":"00:").$minutes.":".$seconds;
        ?>
        <td><?php echo $TotalLogin;?></td>
        <td><?php echo date("H:00:00",strtotime($dt[0]['StartTime']));?></td>
        <td><?php echo $Totalh;?></td>
        <td><?php echo $dt[0]['Call20'];?></td>
        <td><?php echo $dt[0]['Endtime'];?></td>
        <td><?php echo $dt[t2]['xfercallid'];?></td>
	
   	</tr>
        <?php } ?>
</table>			
        <?php 
exit;
?>