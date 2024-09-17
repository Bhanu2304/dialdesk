
<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=import_format.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table cellspacing="0" border="1">
   <thead>
        <tr style="background-color:#317EAC; color:#FFFFFF;">
            <th>SUMMARY</th>
            <th>MTD</th>
            <th>%</th>
			<?php foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { echo "<th>$timeLabel</th>"; } } ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Answered Calls</td>
            <td><?php echo isset($TotalAns)?$TotalAns:""; ?></td>
            <td><?php echo isset($TotalAns)?'100%':""; ?></td>
            <?php foreach($data as $dataLabel=>$dataSub) { ?>
					<?php foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { echo "<td>{$dataSub[$dateLabel][$timeLabel]}</td>"; } } ?>
				<?php } ?>
	</tr>
    </tbody>
</table>






