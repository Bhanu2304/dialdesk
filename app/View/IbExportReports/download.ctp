<?php
//echo "<pre>";
//print_r($Data);
//echo "</pre>";
//die;     
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=in_call_details.xls");
header("Pragma: no-cache");
header("Expires: 0");

if($ClientId=='364')
{    
?>
<table border="1">
	<tr>
    	<?php foreach($header_field_new as $hedrow=>$headRow){ ?>
        	<th><?php echo $hedrow;?></th>
        <?php }?>
        
    </tr>
	<?php foreach($Data as $head){ 
        if($showVal['CloseLoopCate1'] == '0'){ 
            unset($head['CallMaster']['CloseLoopCate1']);
            $head['CallMaster']['CloseLoopCate2'] = 'Open'; } 

            $head['CallMaster']['CallCreated'] = $head['CallMaster']['callcreated'];
            $head['CallMaster']['CallDate1'] = $head['CallMaster']['CallDate'];  ?>
        <tr>
    	<?php foreach($head['CallMaster'] as $head=>$row){?>
			<td><?php echo $row;?></td>
                        <?php if($head=='Field7') { ?>
                        <td><?php echo $row;?></td>
                        <td><?php echo $row;?></td>
                        <?php } ?>
        <?php }?>
                      
	</tr>
  	<?php }?>
</table>

<?php } else { ?>


<table border="1">
	<tr>
    	<?php foreach($header as $hedrow){ ?>
        	<th><?php echo $hedrow;?></th>
        <?php }?>
        <th>Closer Time</th>
    </tr>
	<?php foreach($Data as $head){
        if($showVal['CloseLoopCate1'] == '0' || empty($head['CallMaster']['CloseLoopCate1'])){ 
            unset($head['CallMaster']['CloseLoopCate1']);
            $head['CallMaster']['CloseLoopCate2'] = 'Open'; } 
            ?>
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

<?php } ?>