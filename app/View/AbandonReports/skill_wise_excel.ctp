<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=agent_skilled_mapped.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table border=1 cellpadding=0 cellspacing=0 width=3980 style='border-collapse:collapse;table-layout:fixed;width:2984pt'>
  <thead>
  <tr style="background-color:#317EAC; color:#FFFFFF;">
    <th>UserId</th>
    <th>Name</th>
    <th>DOJ</th>
    <th>Skilled Count</th>
    <th>Skilled Mapped</th>
  </tr>
</thead>

<tbody>
  <?php foreach($data as $v) { 
        $cs_camp =  str_replace("-","",trim($v['vu']['closer_campaigns']));
        $cs_camp_arr = explode(" ",$cs_camp);
        $skilled_cnt = count($cs_camp_arr);
          
    ?>
    <tr>
      <td><?php echo $v['vu']['user'];?></td>
      <td><?php echo $name[$v['vu']['user']];?></td>
      <td><?php echo $doj[$v['vu']['user']];?></td>
      <td><?php echo $skilled_cnt-1;?></td>
      <?php foreach($cs_camp_arr as $cs) { ?>
        <td><?php echo $cs;?></td>
      <?php } ?>
    </tr>

  <?php } ?>  
</tbody>
 
</table>


<?php exit; ?>
