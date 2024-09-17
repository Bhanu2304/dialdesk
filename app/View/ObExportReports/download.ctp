<?php
//print_r($Data);
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=out_call_detail_report.xls");
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
    	<?php foreach($head['CallMasterOut'] as $row){?>
			<td><?php echo $row;?></td> 
                        
        <?php }?>
        
        <?php foreach($head['obcd'] as $row){?>
			<td><?php echo $row;?></td> 
        <?php }?>
                  
	</tr>
  	<?php }?>
</table>
