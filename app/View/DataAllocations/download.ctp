<?php
//print_r($Data);
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=import_format.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<table border="1">
    <tr>
        <?php foreach($Data as $head){?>
            <td><?php echo $head;?></td>
        <?php }?>
    </tr>
</table>