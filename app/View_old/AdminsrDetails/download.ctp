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
    	<?php foreach($header as $hedrow){ ?>
        	<th><?php echo $hedrow;?></th>
        <?php }?>
    </tr>
	<?php foreach($Data as $head){?>
    <tr>
    	<?php foreach($head['CallMaster'] as $row){?>
			<td><?php echo $row;?></td> 
        <?php }?>
	</tr>
  	<?php }?>
</table>
