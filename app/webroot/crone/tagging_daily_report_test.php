<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// $db1=mysql_connect('localhost','root','dial@mas123',false,128);
// mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

// mysql_connect('192.168.10.5','root','vicidialnow',TRUE);
// mysql_select_db('asterisk') or die('unable to connect');
//echo "gesgh;";die;
$db1 = mysqli_connect("192.168.10.12","root","dial@mas123") or die(mysqli_error($db1));
mysqli_select_db($db1,"db_dialdesk")or die("cannot select DB dialdesk");

$db6 = mysqli_connect("192.168.10.5","root","vicidialnow") or die(mysqli_error($db6));
mysqli_select_db($db6,"asterisk")or die("cannot select DB dialdesk");



include('report-send.php');
include('function_report_bhanu.php');

// $select = "SELECT * FROM reportmatrix_master
// WHERE IF(report_type='daily' AND CONCAT(HOUR(NOW()),':',MINUTE(NOW()))=report_value,TRUE,FALSE)
// ";
$select = "SELECT * FROM reportmatrix_master
WHERE report = 'Corrective Report' limit 1";
//$select = "SELECT * FROM reportmatrix_master where report_type='daily'";

//$export = mysqli_query($select,$db1);
$export = mysqli_query($db1,$select);

while($row = mysqli_fetch_assoc($export))
{ 
    //print_r($row);die;
    $dayName = $row['report_value'];
    $condition = "DATE_SUB(CURDATE(),INTERVAL 1 DAY)";

    $condition1 = "DATE(t2.call_date) BETWEEN DATE_SUB(DATE_FORMAT(CURDATE() ,'%Y-%m-01'),INTERVAL 1 MONTH) AND LAST_DAY(DATE_SUB(CURDATE(),INTERVAL 1 MONTH)) AND CURDATE()='$dayName'";

    $condition3 = "DATE(t2.call_date)=DATE_SUB(CURDATE(),INTERVAL 1 DAY)";
    $condition4 = "DATE(parked_time)=DATE_SUB(CURDATE(),INTERVAL 1 DAY)";
    $condition5 = "DATE(CallDate)=DATE_SUB(CURDATE(),INTERVAL 1 DAY)";

    $conditiontest = "DATE(CallDate)=DATE_SUB(CURDATE(),INTERVAL 2 DAY)";
   
    $select = "select campaignid from registration_master where company_id='201'";
    $excute_campaign = mysqli_query($db1,$select);
    $camaign = mysqli_fetch_assoc($excute_campaign);
    $camaign = "t2.campaign_id in(".$camaign['campaignid'].")";
  
    //echo $row['report'];die;
    // if($row['report']=='Corrective Report'){
    //     //print_r($row);die;
    //     Corrective_report($condition,$row['client_id'],$row['user_name'],$row['user_email'],$db1,'daily');
    // }

    if($row['report']=='Corrective Report'){
        //print_r($row);die;
        sample_report($condition,$row['client_id'],$row['user_name'],$row['user_email'],$db1,'daily');
    }
    //for sla everest
    // if($row['report']=='In Call Details MIS'){
    //     sla($condition1,311,$camaign,$row['user_name'],$row['user_email'],$db6,'monthly'); 
    // }
    
    //for abandoned everest
    // if($row['report']=='In Call Details MIS'){
    //     abandoned_data($condition3,311,$camaign,$row['user_name'],$row['user_email'],$db6,'daily',$condition4); 
    // }

    //for abandoned everest toll free
    // if($row['report']=='In Call Details MIS'){
    //     everest_tollfree($condition5,311,$row['user_name'],$row['user_email'],$db1,'daily'); 
    // }
   // for abandoned everest indiamart
    // if($row['report']=='In Call Details MIS'){
    //     everest_indiamart($condition5,311,$row['user_name'],$row['user_email'],$db1,'daily'); 
    // }

    //for everest process wise
    // if($row['report']=='In Call Details MIS'){
    //     process_wise($condition1,311,$camaign,$row['user_name'],$row['user_email'],$db1,'monthly',$db6); 
    // }
    //for sheet 2
    // if($row['report']=='In Call Details MIS'){
    //     sheet2($conditiontest,201,$row['user_name'],$row['user_email'],$db1,'daily'); 
    // }

    
    //echo $html;

}





