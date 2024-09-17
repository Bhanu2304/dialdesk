<?php
/*
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=import_format.xls");
header("Pragma: no-cache");
header("Expires: 0");
*/
?>
<?php if(isset($Data) && !empty($Data)){?>
<table cellspacing="0" border="1">
    <thead>
        <tr>
            <th>S.No</th>
            <?php foreach($header as $hedrow){?>
                <th><?php echo $hedrow;?></th>
            <?php }?>
        </tr>
    </thead>
    <tbody>
    <?php $i=1; foreach($Data as $head){?>
        <tr>
            <td><?php echo $i++;?></td>
            <?php foreach($head['CallMasterOut'] as $key=>$row){?>
                <td><?php  echo $row; ?></td>
            <?php }?>
            <?php foreach($head['obcd'] as $row){?><td><?php echo $row;?></td><?php }?>
        </tr>
    <?php }?>
    </tbody>
</table>
<?php }?>
