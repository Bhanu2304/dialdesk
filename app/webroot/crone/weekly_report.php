<?php
$db1=mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

mysql_connect('192.168.10.5','root','vicidialnow',TRUE);
mysql_select_db('asterisk') or die('unable to connect');

include('report-send.php');
include('function_report2.php');

$select = "SELECT * FROM reportmatrix_master WHERE IF(report_type='weekly' AND LOWER(DAYNAME(CURDATE()))=report_value,TRUE,FALSE)";

$export = mysql_query($select,$db1);
while($row = mysql_fetch_assoc($export)){
    $dayName = $row['report_value'];
    $user_name=$row['user_name'];
    $user_email=$row['user_email'];
    
    $select = "select campaignid from registration_master where company_id='{$row['client_id']}'";
    $excute_campaign = mysql_query($select,$db1);
    $camaign = mysql_fetch_assoc($excute_campaign);
    $camaign = "t2.campaign_id in(".$camaign['campaignid'].")";
   
    $condition1 = "DATE(t2.call_date) BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND DAYNAME(CURDATE()) = '$dayName'";
    $condition2 = "DATE(cm.CallDate) BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND DAYNAME(CURDATE()) = '$dayName'";
    $condition3 = "DATE(CallDate) BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND DAYNAME(CURDATE()) = '$dayName'";
   
    
    if($row['report']=='Time Wise MIS'){
        time_mis($condition1,$row['client_id'],$camaign,$condition2,$user_name,$user_email,$db1,'weekly');
    }
    else if($row['report']=='Agent Wise MIS'){
        agent_mis($condition1,$row['client_id'],$camaign,$condition2,$user_name,$user_email,$db1,'weekly');
    }
    else if($row['report']=='Abend Call MIS'){
        aband_mis($condition1,$row['client_id'],$camaign,$condition2,$user_name,$user_email,$db1,'weekly');
    }
    else if($row['report']=='Answer Call MIS'){
        answer_mis($condition1,$row['client_id'],$camaign,$condition2,$user_name,$user_email,$db1,'weekly');
    }
    else if($row['report']=='Category Wise MIS'){
        category_mis($condition1,$row['client_id'],$camaign,$condition2,$user_name,$user_email,$db1,'weekly');
    }
    else if($row['report']=='Escalation Level MIS'){
        esc_level_mis($condition1,$row['client_id'],$camaign,$condition2,$user_name,$user_email,$db1,'weekly');
    }
    else if($row['report']=='In Call Details MIS'){
        incall_details_mis($condition1,$row['client_id'],$camaign,$condition3,$user_name,$user_email,$db1,'weekly');
    }
    
}

