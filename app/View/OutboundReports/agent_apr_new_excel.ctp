<?php
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Agent_wise_Day_wise_Utilization_New.xls");
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
          <th>Wait %</th>
          <th>Talk</th>
          <th>Talk %</th>
          <th>Dispo</th>
          <th>Dispo %</th>
          <th>Pause</th>
          <th>Pause %</th>
          <th>Dead</th>
          <th>Dead %</th>
          <th>Customer</th>
          <th>Customer %</th>
        </tr>
      </thead>

      <tbody>
          <?php foreach($data as $v) { 

            $scoustomersec =	($v['0']['talk_sec'] - $v['0']['dead_sec']);

            $Slogintime =	strtotime($v['0']['login_time']);

            $minute = date("i", $Slogintime);
            $second = date("s", $Slogintime);
            $hour = date("H", $Slogintime);
            $Total_time = (($hour*3600) + ($minute*60) + ($second*1));

            $Stalk_sec =	strtotime($v['0']['talk_sec']);
            $tminute = date("i", $Stalk_sec);
            $tsecond = date("s", $Stalk_sec);
            $thour = date("H", $Stalk_sec);
            $talk_per = (($thour*3600) + ($tminute*60) + ($tsecond*1));

            $Swait_sec =	strtotime($v['0']['wait_sec']);
            $wminute = date("i", $Swait_sec);
            $wsecond = date("s", $Swait_sec);
            $whour = date("H", $Swait_sec);
            $wait_per = (($whour*3600) + ($wminute*60) + ($wsecond*1));

            $Sdespo_sec =	strtotime($v['0']['dispo_sec']);
            $dminute = date("i", $Sdespo_sec);
            $dsecond = date("s", $Sdespo_sec);
            $dhour = date("H", $Sdespo_sec);
            $despo_per = (($dhour*3600) + ($dminute*60) + ($dsecond*1));

            $Spause_sec =	strtotime($v['0']['pause_sec']);
            $pminute = date("i", $Spause_sec);
            $psecond = date("s", $Spause_sec);
            $phour = date("H", $Spause_sec);
            $pause_per = (($phour*3600) + ($pminute*60) + ($psecond*1));

            $Sdead_sec =	strtotime($v['0']['dead_sec']);
            $deminute = date("i", $Sdead_sec);
            $desecond = date("s", $Sdead_sec);
            $dehour = date("H", $Sdead_sec);
            $dead_per = (($dehour*3600) + ($deminute*60) + ($desecond*1));
                 
          ?>
          <tr>
            <td><?php echo $v['0']['event_time'];?></td>
            <td><?php echo $v['vl']['user'];?></td>
            <td><?php echo $v['vu']['full_name'];?></td>
            <td><?php echo $v['0']['calls'];?></td>
            <td><?php echo $v['0']['login_time'];?></td>
            <td><?php echo $v['0']['wait_sec'];?></td>
            <td><?php echo round(($wait_per/$Total_time*100),2); ?></td>
            <td><?php echo $v['0']['talk_sec'];?></td>
            <td><?php echo round(($talk_per/$Total_time*100),2); ?></td>
            <td><?php echo $v['0']['dispo_sec'];?></td>
            <td><?php echo round(($despo_per/$Total_time*100),2); ?></td>
            <td><?php echo $v['0']['pause_sec'];?></td>
            <td><?php echo round(($pause_per/$Total_time*100),2); ?></td>
            <td><?php echo $v['0']['dead_sec'];?></td>
            <td><?php echo round(($dead_per/$Total_time*100),2); ?></td>
            <td><?php echo $scoustomersec;?></td>
            <td></td>
            <td></td>
          </tr>

        <?php } ?>  
      </tbody>
 
</table>


<?php exit; ?>
