<?php

header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=import_format.xls");
header("Pragma: no-cache");
header("Expires: 0");

?>
<table cellspacing="0" border="1" width="500">
    <tr style="background-color:#317EAC; color:#FFFFFF;">
        <th>DASHBOARD</th>
    </tr>
</table>
<table cellspacing="0" border="1" width="500">
    <thead>
        <tr style="background-color:#317EAC; color:#FFFFFF;">
            <th rowspan="2">SUMMARY</th>
            <th rowspan="2">MTD</th>
             <th rowspan="2">%</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($category as $row){?>
            <tr>
                <td>Total <?php echo $row['category'];?></td>
                <td><?php echo $row['total'];?></td>
                <td><?php if($row['persent'] !=""){echo $row['persent']."%";}?></td>
            </tr>
        <?php }?> 
          
        <?php foreach($intatArray as $row1){?>
            <tr>
                <td>Total <?php echo $row1['category'];?></td>
                <td><?php echo $row1['total'];?></td>
                <td><?php if($row1['persent'] !=""){echo $row1['persent']."%";}?></td>
            </tr>
        <?php }?>
        <?php foreach($outtatArray as $ro2){?>
            <tr>
                <td>Total <?php echo $ro2['category'];?></td>
                <td><?php echo $ro2['total'];?></td>
                <td><?php if($ro2['persent'] !=""){echo $ro2['persent']."%";}?></td>
            </tr>
        <?php }?>
        <?php foreach($openIntatArray as $row3){?>
            <tr>
                <td>Total <?php echo $row3['category'];?></td>
                <td><?php echo $row3['total'];?></td>
                <td><?php if($row3['persent'] !=""){echo $row3['persent']."%";}?></td>
            </tr>
        <?php }?>
        <?php foreach($openOuttatArray as $row4){?>
            <tr>
                <td>Total <?php echo $row4['category'];?></td>
                <td><?php echo $row4['total'];?></td>
                <td><?php if($row4['persent'] !=""){echo $row4['persent']."%";}?></td>
            </tr>
        <?php }?>
           
    </tbody>
</table>






