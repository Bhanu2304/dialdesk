<?php
//print_r($Data);
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=import_format.xls");
header("Pragma: no-cache");
header("Expires: 0");

$NewField = explode(',',$Data[0][0]['CatField']);
$ArrCnt   = count($NewField);
?>
<table border="1">
<tr>
	<td>MSISDN</td>
        <td>CallDate</td>
        <td>CloseLoopCate1</td>
        <td>CloseLoopCate2</td>
        <td>CloseLoopingDate</td>
	<?php
	for($i=0;$i<$ArrCnt;$i++)
	{
	?>
	<td><?php echo is_numeric($NewField[$i])?'Category'.$NewField[$i]:$NewField[$i]; ?></td>
	<?php } ?>  
</tr>

</table>