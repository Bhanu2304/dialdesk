<?php
$db1=mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

$db2 = mysql_connect('192.168.10.5','root','vicidialnow',TRUE);
mysql_select_db('asterisk') or die('unable to connect');

include('report-send.php');
include('function_ob_report2.php');
error_reporting(E_ALL);


$select = "SELECT * FROM obreportmatrix_master
WHERE  user_email='bhanu.singh@teammas.in'";

//$select ="SELECT * FROM reportmatrix_master where report_value='15:58'";
$export = mysql_query($select,$db1);

while($row = mysql_fetch_assoc($export)){
    $user_name=$row['user_name'];
    $user_email=$row['user_email'];
    $campaign_id=$row['campaign_id'];

    $condition1 = "Date(t2.call_date)=CURDATE() - 1";
    $condition2 = "Date(cm.CallDate)=CURDATE() - 1";
    $condition3 = "Date(CallDate)=CURDATE() - 1";
  
    $select = "select campaignid from registration_master where company_id='{$row['client_id']}'";
    $excute_campaign = mysql_query($select,$db1);
    $camaign = mysql_fetch_assoc($excute_campaign);
    $camaign = "t2.campaign_id in(".$camaign['campaignid'].")";

    if($row['report']=='Time Wise MIS')
    {
        time_mis($condition1,$row['client_id'],$camaign,$condition2,$user_name,$user_email,$db1,'daily');
    }
    else if($row['report']=='Agent Wise MIS')
    {
        agent_mis($condition1,$row['client_id'],$camaign,$condition2,$user_name,$user_email,$db1,'daily');
    }
    else if($row['report']=='Abend Call MIS')
    {
        aband_mis($condition1,$row['client_id'],$camaign,$condition2,$user_name,$user_email,$db1,'daily');
    }
    else if($row['report']=='Answer Call MIS'){
        answer_mis($condition1,$row['client_id'],$camaign,$condition2,$user_name,$user_email,$db1,'daily');
    }
    else if($row['report']=='Category Wise MIS')
    {
        
        category_mis($condition1,$row['client_id'],$campaign_id,$condition2,$user_name,$user_email,$db1,'daily');
    }
    else if($row['report']=='Escalation Level MIS')
    {
        esc_level_mis($condition1,$row['client_id'],$camaign,$condition2,$user_name,$user_email,$db1,'daily');
    }
    else if($row['report']=='In Call Details MIS'){
        incall_details_mis($condition1,$row['client_id'],$camaign,$condition3,$user_name,$user_email,$db1,'daily');
    }
    else if($row['report']=='SLA Report MIS'){
        sla_report_mis($condition1,$row['client_id'],$camaign,$condition3,$user_name,$user_email,$db2,'daily');
    }
}



