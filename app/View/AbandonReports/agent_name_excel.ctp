<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=roster_planning.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style type="text/css">
    td {
  text-align: center;
}
<table cellspacing="0" border="1" >


 <thead>
 
    <tr style="background-color:#317EAC; color:#FFFFFF;"> 
    <th>Agent Id</th>             
    <th>Agent Name</th> 
    
    </tr>
    
</thead>
<tbody>
    <?php
        $counter = 1;
     foreach($data as $cl) { 

        ?>
      <tr>
      <td ><?php echo $cl[vl][user];?></td>  
      <td ><?php echo $cl[vu][full_name];?></td>  
          </tr>               
    
    <?php } ?>
</tbody>
</table>


<?php exit; ?>
