<?php
$startDate=$_REQUEST['CallDate'];
$ClientId=$_REQUEST['ClientId'];

$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$con = mysql_connect("192.168.10.5","root","vicidialnow","false",128);
mysql_select_db("asterisk",$con)or die("cannot select DB");

$qry=mysql_query("SELECT Id,data_id,CallFrom FROM billing_master WHERE clientId ='$ClientId' AND DedType='Outbound' AND DATE(CallDate)='$startDate'",$db);

while($result=  mysql_fetch_assoc($qry)){
    /*
    echo "<pre>";
    print_r($result);
    echo "</pre>";
   */
    
    $CallDu=mysql_query("SELECT phone_number,lead_id,length_in_sec FROM `vicidial_log` WHERE phone_number='{$result['CallFrom']}' AND DATE(call_date)='$startDate' limit 1 ",$con);
    $Duration=mysql_fetch_assoc($CallDu);
     
    if($Duration['lead_id'] !=""){
        mysql_query("UPDATE call_master_out SET LeadId='{$Duration['lead_id']}' WHERE Id='{$result['data_id']}'",$db);  
    }
    
    if($Duration['length_in_sec'] !=""){
        mysql_query("UPDATE billing_master SET Duration='{$Duration['length_in_sec']}',LeadId='{$Duration['lead_id']}' WHERE Id='{$result['Id']}' AND DedType='Outbound'",$db);  
    }
    
   
    /*
    echo "<pre>";
    print_r($Duration);
    echo "</pre>";
    */

}
exit;
?>


