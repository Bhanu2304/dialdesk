<?php
$db1=mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

include('report-send.php');
include('customized_function.php');

$select = "SELECT * FROM reportmatrix_master
WHERE IF(report_type='daily' AND CONCAT(HOUR(NOW()),':',MINUTE(NOW()))=report_value,TRUE,FALSE)
";

//$select = "SELECT * FROM reportmatrix_master where report_type='daily'";

$export = mysql_query($select,$db1);
while($row = mysql_fetch_assoc($export)){ 
    $CallDate   = "Date(CallDate)=CURDATE() - 1";
    $ClientId   = $row['client_id'];
    
    if($row['report']=='Customized Report MIS'){
        customized_report($CallDate,$ClientId,$row['user_name'],$row['user_email'],'customized_mis_dealy','Customized Report MIS Daily Wise');
    }

}


