<?php
$PhpDate = date('Y-m-d');


$db = mysql_connect("192.168.0.4","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$con = mysql_connect("192.168.0.5","root","vicidialnow","false",128);
mysql_select_db("asterisk",$con)or die("cannot select DB");

$qry=mysql_query("SELECT Id,data_id FROM billing_master WHERE DedType='Outbound' AND Duration IS NULL AND DATE(CallDate)='$PhpDate'",$db);

//$qry=mysql_query("SELECT Id,data_id FROM billing_master WHERE DedType='Outbound'",$db);

while($result=  mysql_fetch_assoc($qry)){
  
    $dataArr=  mysql_fetch_assoc(mysql_query("SELECT LeadId,TagType FROM call_master_out WHERE Id='{$result['data_id']}'",$db));
    
    if($dataArr['LeadId'] !=""){
        $CallDu=mysql_query("select length_in_sec from `vicidial_log` where `lead_id` ='{$dataArr['LeadId']}' limit 1",$con);
        $Duration=mysql_fetch_assoc($CallDu);
        if($Duration['length_in_sec'] !=""){
            mysql_query("UPDATE billing_master SET Duration='{$Duration['length_in_sec']}' WHERE Id='{$result['Id']}' AND DedType='Outbound'",$db);  
        }
    }
}
exit;
?>


