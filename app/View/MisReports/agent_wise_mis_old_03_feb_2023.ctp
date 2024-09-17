<?php
/*
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=import_format.xls");
header("Pragma: no-cache");
header("Expires: 0");
*/
?>
<table cellspacing="0" border="1">
    <thead>
        <tr style="background-color:#317EAC; color:#FFFFFF;">
            <th>Agent Name</th>
			<?php foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { echo "<th>$timeLabel</th>"; } } ?>
        </tr>
    </thead>
    <tbody>
        <tr>
			<?php foreach($agentDetails as $agent){?>
            <td><?php echo $agent[t2]['user'];?></td>
			<?php }?>
			<?php foreach($data as $dataLabel=>$dataSub) { ?>
					<?php foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { echo "<td>{$dataSub[$dateLabel][$timeLabel]}</td>"; } } ?>
			<?php } ?>
	</tr>
    </tbody>
</table>






