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
        <tr>
            <td>Total Answered Calls</td>
            <td><?php echo isset($TotalAns)?$TotalAns:""; ?></td>
            <td><?php if($TotalAns !=""){echo "100%";} ?></td>
        </tr>
        
        <tr>
            <td>Tagging Details</td>
            <td><?php echo isset($totalTag)?$totalTag:""; ?></td>
            <td><?php if($totalTag !=""){echo "100%";} ?></td>
        </tr>
        
        <?php foreach($category as $row){?>
            <tr>
                <td>Total Tagged <?php echo $row['category'];?> Details</td>
                <td><?php echo $row['total'];?></td>
                <td><?php if($row['persent'] !=""){echo $row['persent']."%";}?></td>
            </tr>
        <?php }?>   
    </tbody>
</table>






