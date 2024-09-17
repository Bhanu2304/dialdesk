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
            <?php foreach($DateWiseCategory as $row1){?>
                <td><?php echo $row1;?></td>
            <?php }?>
        </tr>
        <tr>
            <th>Sub Type Details</th>
            <td></td>
            <td></td>
            <?php foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { echo "<td></td>"; } } ?>

        </tr>
        <?php foreach($DateWiseSubCategory as $row2){?>
            <tr>
                <td><?php echo $row2['category'];?></td>
                <td><?php echo $row2['total'];?></td>
                <td><?php if($row2['persent'] !=""){echo $row2['persent']."%";}?></td>
                <?php foreach($row2['AllCount'] as $row3){?>
                <td><?php echo $row3;?></td>
                <?php }?>
            </tr>
        <?php }?>   
    </tbody>
</table>
