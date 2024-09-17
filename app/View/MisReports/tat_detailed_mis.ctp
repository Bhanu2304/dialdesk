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
        <?php foreach($category as $row){?>
            <tr>
                <td>Total <?php echo $row['category'];?></td>
                <td><?php echo $row['total'];?></td>
                <td><?php if($row['persent'] !=""){echo $row['persent']."%";}?></td>
                <?php foreach($row['AllCount'] as $ro){?>
                <td><?php echo $ro;?></td>
                <?php }?>
            </tr>
        <?php }?>
        <?php foreach($intatArray as $row1){?>
            <tr>
                <td>Total <?php echo $row1['category'];?></td>
                <td><?php echo $row1['total'];?></td>
                <td><?php if($row1['persent'] !=""){echo $row1['persent']."%";}?></td>
                <?php foreach($row1['AllCount'] as $ro1){?>
                <td><?php echo $ro1;?></td>
                <?php }?>
            </tr>
        <?php }?> 
        <?php foreach($outtatArray as $row2){?>
            <tr>
                <td>Total <?php echo $row2['category'];?></td>
                <td><?php echo $row2['total'];?></td>
                <td><?php if($row2['persent'] !=""){echo $row2['persent']."%";}?></td>
                <?php foreach($row2['AllCount'] as $ro2){?>
                <td><?php echo $ro2;?></td>
                <?php }?>
            </tr>
        <?php }?> 
        <?php foreach($openIntatArray as $row3){?>
            <tr>
                <td>Total <?php echo $row3['category'];?></td>
                <td><?php echo $row3['total'];?></td>
                <td><?php if($row3['persent'] !=""){echo $row3['persent']."%";}?></td>
                <?php foreach($row3['AllCount'] as $ro3){?>
                <td><?php echo $ro3;?></td>
                <?php }?>
            </tr>
        <?php }?> 
        <?php foreach($openOuttatArray as $row4){?>
            <tr>
                <td>Total <?php echo $row4['category'];?></td>
                <td><?php echo $row4['total'];?></td>
                <td><?php if($row4['persent'] !=""){echo $row4['persent']."%";}?></td>
                <?php foreach($row4['AllCount'] as $ro4){?>
                <td><?php echo $ro4;?></td>
                <?php }?>
            </tr>
        <?php }?> 
       
    </tbody>
</table>






