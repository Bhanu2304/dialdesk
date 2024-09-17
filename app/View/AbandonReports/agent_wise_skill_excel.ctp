<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=agent_skilled_mapped.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table cellpadding="0" cellspacing="0" border="1" class="table table-striped table-bordered" >
                  <thead>
    <tr style="background-color:#317EAC; color:#FFFFFF;"> 
    <th>Sno.</th>             
    <th>Client Name</th> 
    <th>Skilled</th>
    <th>Agent Count</th>
    <th>Agent Name</th>
    </tr>
    
</thead>
<tbody>
    <?php
        $counter = 1;
     foreach($data as $k=>$v) { 
        $flag = true;

        ?>
     
         <?php foreach($v as $c=>$p) { 
          //$cs_camp =  str_replace("-","",trim($v['vu']['closer_campaigns']));
          $cs_camp_arr = explode(",",$p);
          $skilled_cnt = count($cs_camp_arr);
          
          ?>
            <tr>
                <?php if($flag) { ?>
                <td rowspan="<?php echo count($v); ?>"><?php echo $counter;?></td>    
                <td rowspan="<?php echo count($v); ?>"><?php echo $k;?></td> <?php  $flag = false; $counter++; } ?>
                <td><?php echo $c;?></td>
                <td><?php echo $skilled_cnt;?></td>
                <?php foreach($cs_camp_arr as $cs) { ?>
                <td><?php echo $cs;?></td>
                <?php } ?>
          </tr>   
          <?php  } ?>  

    
    <?php } ?>
</tbody>
</table> 


<?php exit; ?>
