<?php
$db1=mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

$db6 = mysql_connect('192.168.10.5','root','vicidialnow',TRUE);
mysql_select_db('asterisk') or die('unable to connect');

include('report-send.php');
include('function_report2.php');



$select = "SELECT * FROM reportmatrix_master WHERE IF(report_type='monthly' AND CURDATE() = DATE(report_value),TRUE,FALSE)";


$export = mysql_query($select,$db1);
while($row = mysql_fetch_assoc($export)){
    $dayName = $row['report_value'];
    $condition1 = "DATE(t2.call_date) BETWEEN DATE_SUB(DATE_FORMAT(CURDATE() ,'%Y-%m-01'),INTERVAL 1 MONTH) AND LAST_DAY(DATE_SUB(CURDATE(),INTERVAL 1 MONTH)) AND CURDATE()='$dayName'";
    $condition2 = "DATE(cm.CallDate) BETWEEN DATE_SUB(DATE_FORMAT(CURDATE() ,'%Y-%m-01'),INTERVAL 1 MONTH) AND LAST_DAY(DATE_SUB(CURDATE(),INTERVAL 1 MONTH)) AND CURDATE()='$dayName'";
    $condition3 = "DATE(CallDate) BETWEEN DATE_SUB(DATE_FORMAT(CURDATE() ,'%Y-%m-01'),INTERVAL 1 MONTH) AND LAST_DAY(DATE_SUB(CURDATE(),INTERVAL 1 MONTH)) AND CURDATE()='$dayName'";
     

    $select = "select campaignid from registration_master where company_id='{$row['client_id']}'";
    $excute_campaign = mysql_query($select,$db1);
    $camaign = mysql_fetch_assoc($excute_campaign);
    $camaign = "t2.campaign_id in(".$camaign['campaignid'].")";
    
    if($row['report']=='Tagging MIS'){
        tagging_mis($condition1,$row['client_id'],$camaign,$condition2,$row['user_name'],$row['user_email'],$db1,'monthly'); 
    }
    if($row['report']=='Process Wise'){
        process_wise($dayName,$row['client_id'],$camaign,$row['user_name'],$row['user_email'],$db1,'monthly',$db6); 
    }
    if($row['report']=='SLA'){
        sla($dayName,$row['client_id'],$camaign,$row['user_name'],$row['user_email'],$db6,'monthly'); 
    }

}

