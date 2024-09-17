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
		<th rowspan="2"></th>			
		<?php 
                //print_r($datetimeArray); exit;
                foreach($datetimeArray as $dateLabel=>$timeArray) { echo "<th colspan=\"".count($timeArray)."\">$dateLabel</th>"; } ?>
	</tr>
	<tr>
		<?php foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { echo "<th>$timeLabel</th>"; } } ?>
	</tr>
</thead>
<tbody>
	
	
	
	
	
	<?php  $label_arr = array('Offered %','Total Calls Landed','Total Calls Answered','Total Calls Abandoned','AHT (In Sec)','Calls Ans (within 20 Sec)','Abnd Within Threshold','Abnd After Threshold','Ababdoned (%)','SL% (20 Sec)'); ?>
        
        
        <?php foreach($label_arr as $label) { ?>
	<tr>
            <?php echo '<td>'.$label.'</td>'; ?>
            <?php foreach($datetimeArray as $dateLabel=>$timeArray) { ?>
		<?php 
                    foreach($timeArray as $time)
                    { echo '<td>'.$data[$dateLabel][$time][$label].'</td>'; }
                    
                ?>
            <?php } ?>
	</tr>		
	<?php } ?>
        
</tbody>
</table>			
<?php 
exit;
?>