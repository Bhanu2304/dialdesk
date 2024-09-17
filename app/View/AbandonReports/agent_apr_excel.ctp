<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Agent_wise_Day_wise_Utilization.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style type="text/css">
    td {
  text-align: center;
}

</style>
<table border=1 cellpadding=0 cellspacing=0 width=100 style='border-collapse:collapse;'>
  <thead>
  <tr style="background-color:#317EAC; color:#FFFFFF;">
    <th>Call Date</th>
    <th>ID</th>
    <th>Name</th>
    <th>Calls</th>
    <th>Login Time</th>
    <th>Wait</th>
    <th>Talk</th>
    <th>Dispo</th>
    <th>Pause</th>
    <th>Dead</th>
  </tr>
</thead>

<tbody>
  <?php foreach($data as $v) { 
       
          
    ?>
    <tr>
      <td><?php echo $v['0']['event_time'];?></td>
      <td><?php echo $v['vl']['user'];?></td>
      <td><?php echo $v['vu']['full_name'];?></td>
      <td><?php echo $v['0']['calls'];?></td>
      <td><?php echo $v['0']['login_time'];?></td>
      <td><?php echo $v['0']['wait_sec'];?></td>
      <td><?php echo $v['0']['talk_sec'];?></td>
      <td><?php echo $v['0']['dispo_sec'];?></td>
      <td><?php echo $v['0']['pause_sec'];?></td>
      <td><?php echo $v['0']['dead_sec'];?></td>
    </tr>

  <?php } ?>  
</tbody>
 
</table>


<?php exit; ?>
