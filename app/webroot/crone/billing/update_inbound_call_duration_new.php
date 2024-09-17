<?php
$PhpDate = date('Y-m-d');

$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$con = mysql_connect("192.168.10.5","root","vicidialnow","false",128);
mysql_select_db("asterisk",$con)or die("cannot select DB");

$qry=mysql_query("SELECT Id,data_id FROM billing_master WHERE DedType='Inbound' AND DATE(CallDate)='$PhpDate'",$db);

//$qry=mysql_query("SELECT Id,data_id FROM billing_master WHERE DedType='Inbound'",$db);

while($result=  mysql_fetch_assoc($qry)){
  
    $dataArr=  mysql_fetch_assoc(mysql_query("SELECT LeadId,TagType FROM call_master WHERE Id='{$result['data_id']}'",$db));
    
    if($dataArr['TagType'] =='Offline Tagging'){
        if($dataArr['LeadId'] !=""){
            $CallDu=mysql_query("select length_in_sec from `vicidial_log` where `lead_id` ='{$dataArr['LeadId']}' AND DATE(call_date)='$PhpDate' limit 1",$con);
            $Duration=mysql_fetch_assoc($CallDu);
            if($Duration['length_in_sec'] !=""){
                mysql_query("UPDATE billing_master SET Duration='{$Duration['length_in_sec']}' WHERE Id='{$result['Id']}' AND DedType='Inbound'",$db);  
            }
        }
    }
    else{
        if($dataArr['LeadId'] !=""){
            $CallDu=mysql_query("select length_in_sec,queue_seconds from `vicidial_closer_log` where `lead_id` ='{$dataArr['LeadId']}' AND DATE(call_date)='$PhpDate' limit 1",$con);
            //$CallDu=mysql_query("select length_in_sec,queue_seconds from `vicidial_closer_log` where `lead_id` ='{$dataArr['LeadId']}' limit 1",$con);
            $Duration=mysql_fetch_assoc($CallDu);
            if($Duration['length_in_sec'] !=""){
                $TotalDuration=($Duration['length_in_sec']-$Duration['queue_seconds']);
                
                //echo "<pre>";
                //echo $Duration['length_in_sec']."---------->".$Duration['queue_seconds']."----------->".$TotalDuration;
                //echo "</pre>";
                
                mysql_query("UPDATE billing_master SET Duration='$TotalDuration' WHERE Id='{$result['Id']}' AND DedType='Inbound'",$db); 
            }
        }
    }
}
exit;
?>


