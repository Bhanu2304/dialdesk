<?php
//echo "<pre>";
//print_r($Data);
//echo "</pre>";
//die;     
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=incall_closeloop_det.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>



<table border="1">
	<tr>
    	<?php foreach($header as $hedrow){ ?>
        	<th><?php echo $hedrow;?></th>
        <?php }?>
        <th>Status</th>
        <th>Remarks</th>
    </tr>
	<?php foreach($Data as $head){ ?>
    <tr>
    	<?php foreach($head['CallMaster'] as $row){ ?>
			<td><?php echo $row;?></td> 
        <?php }?>
                 <td></td>
                 <td></td>    
	</tr>
  	<?php } ?>
</table>

