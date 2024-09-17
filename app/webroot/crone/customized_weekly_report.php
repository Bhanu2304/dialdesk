<?php
$db1=mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

include('report-send.php');
include('customized_function.php');

$select = "SELECT * FROM reportmatrix_master WHERE IF(report_type='weekly' AND LOWER(DAYNAME(CURDATE()))=report_value,TRUE,FALSE)";

$export = mysql_query($select,$db1);
while($row = mysql_fetch_assoc($export)){
    $dayName    = $row['report_value'];
    $CallDate   = "DATE(CallDate) BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND DAYNAME(CURDATE()) = '$dayName'";
    $ClientId   = $row['client_id'];
    
    if($row['report']=='Customized Report MIS'){
        customized_report($CallDate,$ClientId,$row['user_name'],$row['user_email'],'customized_mis_weekly','Customized Report MIS Weekly Wise');
    }
}

