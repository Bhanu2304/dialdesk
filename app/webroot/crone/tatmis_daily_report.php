<?php
$db1=mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

mysql_connect('192.168.10.5','root','vicidialnow',TRUE);
mysql_select_db('asterisk') or die('unable to connect');

include('report-send.php');
include('function_report2.php');

$select = "SELECT * FROM reportmatrix_master
WHERE IF(report_type='daily' AND CONCAT(HOUR(NOW()),':',MINUTE(NOW()))=report_value,TRUE,FALSE)
";
$export = mysql_query($select,$db1);
while($row = mysql_fetch_assoc($export)){ 
    $condition1 = "Date(t2.call_date)=CURDATE() - 1";
    $condition2 = "Date(cm.CallDate)=CURDATE() - 1";
    $condition3 = "Date(CallDate)=CURDATE() - 1";
  
    $select = "select campaignid from registration_master where company_id='{$row['client_id']}'";
    $excute_campaign = mysql_query($select,$db1);
    $camaign = mysql_fetch_assoc($excute_campaign);
    $camaign = "t2.campaign_id in(".$camaign['campaignid'].")";
    
    if($row['report']=='TAT MIS'){
        tat_mis($condition1,$row['client_id'],$camaign,$condition2,$row['user_name'],$row['user_email'],$db1,'daily');
    }
}



