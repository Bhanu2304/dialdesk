<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=import_format.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1"> 
	<tr>
    	<th>S. No.</th>
        <th>Date</th>		
        <th>File 1</th>
        <th>File 2</th>
        <th>File 3</th>
        <th>File 4</th>
        <th>File 5</th>
        <th>File 6</th>
        <th>File 7</th>
        <th>File 8</th>
        <th>File 9</th>
        <th>File 10</th>
    </tr>
<?php $i=1; foreach($data as $post): ?>
<tr>
	<td><?=$i++?></td>
    <td><?php $date =  date_create($post['Training']['createdate']);
		echo date_format($date,'d M Y');
	?></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field1']); ?>"><?php echo $post['Training']['Field1']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field2']); ?>"><?php echo $post['Training']['Field2']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field3']); ?>"><?php echo $post['Training']['Field3']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field4']); ?>"><?php echo $post['Training']['Field4']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field5']); ?>"><?php echo $post['Training']['Field5']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field6']); ?>"><?php echo $post['Training']['Field6']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field7']); ?>"><?php echo $post['Training']['Field7']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field8']); ?>"><?php echo $post['Training']['Field8']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field9']); ?>"><?php echo $post['Training']['Field9']; ?> </a></td>
    <td><a href="<?php echo $this->html->webroot('training'.DS.$post['Training']['Field10']); ?>"><?php echo $post['Training']['Field10']; ?> </a></td>

</tr>
<?php endforeach; ?>
</table>
