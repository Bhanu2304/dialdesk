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
        <?php foreach($data as $dataLabel=>$dataSub) { ?>
        <tr>
            <td><?php echo $dataLabel; ?></td>
            <?php foreach($datetimeArray as $dateLabel=>$timeArray) {echo "<td></td><td></td>"; foreach($timeArray as $timeLabel) { echo "<td>{$dataSub[$dateLabel][$timeLabel]}</td>"; } } ?>
	</tr>		
	<?php } ?>
        
         <tr>
            <?php foreach($TotalTaging as $tagrow){?>
                <td> <?php echo$tagrow; ?></td>
            <?php }?> 
        </tr>
        
        <?php foreach($category as $row){?>
            <tr>
                <td><?php echo $row['category'];?></td>
                <td><?php echo $row['total'];?></td>
                <td><?php if($row['persent'] !=""){echo $row['persent']."%";}?></td>
                <?php foreach($row['AllCount'] as $row1){?>
                <td><?php echo $row1;?></td>
                <?php }?>
            </tr>
        <?php }?>   
    </tbody>
</table>






