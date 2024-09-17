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
            <td>Total Tagged Cases</td>
            <td><?php echo isset($totalTag)?$totalTag:""; ?></td>
            <td><?php if($totalTag !=""){echo "100%";} ?></td>
            <?php foreach($dateWiseTagged as $dwtTag){?>
                <td><?php echo $dwtTag; ?></td>
            <?php }?>
	</tr>	
        
        <tr>
            <td>Total Escalated Cases</td>
            <td><?php echo isset($totalEsclation)?$totalEsclation:""; ?></td>
            <td><?php if($totalEsclation !=""){echo "100%";} ?></td>
            <?php foreach($dateWiseEsc as $dwtEsc){?>
                <td><?php echo $dwtEsc; ?></td>
            <?php }?>
        </tr>
        
        <?php foreach($escArr as $row){?>
            <tr>
                <td><?php echo $row['type'];?></td>
                <td><?php echo $row['count'];?></td>
                <td><?php if($row['persent'] !=""){echo $row['persent']."%";}?></td>
                <?php foreach($row['AllCount'] as $row1){?>
                <td><?php echo $row1;?></td>
                <?php }?>
            </tr>
        <?php }?> 
    </tbody>
</table>
