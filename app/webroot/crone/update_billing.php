<?php
set_time_limit(0);
ob_start();

$mysql_server = '192.168.10.5';
$mysql_database = 'asterisk';
$mysql_user = 'root';
$mysql_pass = 'vicidialnow';


$mysql = mysql_connect($mysql_server,$mysql_user,$mysql_pass) or die(mysql_error());
$con = mysql_select_db($mysql_database,$mysql);

$db = mysql_connect("localhost","root","dial@mas123") or die(mysql_error());
$con1 = mysql_select_db("db_dialdesk",$db);

$SelBill = "SELECT bm.clientId `clientId`,bm.Id `Id`,LeadId FROM `billing_master` bm INNER JOIN call_master cm ON bm.data_id = cm.id
 WHERE Duration IS NULL";
$exeBill = mysql_query($SelBill,$db);

while($row = mysql_fetch_assoc($exeBill))
{
    
    $clientId = $row['clientId'];
    $vici = "select length_in_sec from `vicidial_closer_log` where `lead_id` ='{$row['LeadId']}' limit 1";
    $exeRsc=mysql_query($vici,$mysql);
    $lengthArr = mysql_fetch_assoc($exeRsc);
    $length = $lengthArr['length_in_sec'];
    
    $InbCallCharge = "SELECT InboundCallCharge,InboundCallMinute FROM `balance_master` bm INNER JOIN `plan_master` pm ON bm.PlanId=pm.Id WHERE clientId='$clientId' LIMIT 1;";
    $exeInb = mysql_query($InbCallCharge,$db);
    $inb = mysql_fetch_assoc($exeInb);
    
    $divide = $inb['InboundCallMinute']*60;
    $unit = ceil($length/$divide);
    $amount = $unit*$inb['InboundCallCharge'];
    
    //$updateBilling = "UPDATE `billing_master` SET Duration='$length',Unit='$unit',Amount='$amount' WHERE Id='{$row['Id']}' limit 1";
    //$updateBalance = "UPDATE `balance_master` SET balance = balance-$amount WHERE clientId='$clientId';";
    //$exeBilling = mysql_query($updateBilling,$db);
    //$exeBalance = mysql_query($updateBalance,$db);
    
}