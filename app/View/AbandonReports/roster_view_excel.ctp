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
</style>
<table cellspacing="0" border="1" >


 <thead>
 <tr><td colspan="25">Rostaer Planning as on Date</td></tr>
    <tr style="background-color:#317EAC; color:#FFFFFF;"> 
    <th>Sno.</th>             
    <th>Client Name</th> 
    <?php 
            foreach($slot_arr2 as $sl)
            {
                echo '<th>'.$sl.'</th>';
            }
    ?>
    </tr>
    
</thead>
<tbody>
    <?php
        $counter = 1;
     foreach($client_list as $cl) { 
        $flag = true;

        ?>
      <tr>
      <td ><?php echo $counter++;?></td>  
      <td ><?php echo $cl;?></td>  
         <?php foreach($slot_arr2 as $sl) { ?>
           
                
                  
                
                <td><?php echo count($data2[$cl][$sl]);?></td>
                
                <?php } ?>
          
          </tr>               
    
    <?php } ?>
</tbody>
</table>


<?php exit; ?>
