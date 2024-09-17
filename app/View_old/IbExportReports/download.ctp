<?php
//echo "<pre>";
//print_r($Data);
//echo "</pre>";
//die;     
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
        <th>Closer Time</th>
    </tr>
	<?php foreach($Data as $head){?>
    <tr>
    	<?php foreach($head['CallMaster'] as $row){?>
			<td><?php echo $row;?></td> 
        <?php }?>
        <?php
        if($head['CallMaster']['CloseLoopingDate'] !=""){$cld=$head['CallMaster']['CloseLoopingDate'];}else{$cld="";}                      
        if($cld !=""){
            $t1 = StrToTime ($cld);
            $t2 = StrToTime ($head['CallMaster']['CallDate']);
            $diff = $t1 - $t2;
            $hours = $diff / ( 60 * 60 );
        }
        else{
            $hours="";  
        }
        echo "<td>".round($hours)."</td>";     
        ?>              
	</tr>
  	<?php }?>
</table>
