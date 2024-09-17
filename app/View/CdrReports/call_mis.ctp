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
		<th rowspan="2"></th>			
		<?php foreach($datetimeArray as $dateLabel=>$timeArray) { echo "<th colspan=\"".count($timeArray)."\">$dateLabel</th>"; } ?>
	</tr>
	<tr style="background-color:#317EAC; color:#FFFFFF;">
		<?php foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { echo "<th>$timeLabel</th>"; } } ?>
	</tr>
</thead>
<tbody>
	<?php foreach($data as $dataLabel=>$dataSub) { ?>
	<tr>
		<td><?php echo $dataLabel; ?></td>
		<?php foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { echo "<td>{$dataSub[$dateLabel][$timeLabel]}</td>"; } } ?>
	</tr>		
	<?php } ?>						
</tbody>
</table>			
<?php 
exit;
?>