<?php
include_once('function.php');

$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$start_renewal_date=$_REQUEST['renewaldate'];

$qry=mysql_query("SELECT * FROM `renewal_plan_master` WHERE ApplyStatus='No' AND BillCycle IS NULL AND DATE(start_date)='$start_renewal_date'");

while($row=  mysql_fetch_assoc($qry)){
    
    $Id=$row['Id'];
    $clientid=$row['clientId'];
    $planId=$row['PlanId'];
    $planType=$row['PlanType'];
    $userid=$row['userid'];
    $start_date=$row['start_date'];
    $end_date=$row['end_date'];
    
    
    $BalanceQry=mysql_query("SELECT * FROM `balance_master` WHERE clientId='$clientid'");
    $balanceArr=  mysql_fetch_assoc($BalanceQry);
   
    $oldStartDate=$balanceArr['start_date'];
    $oldEndDate=$balanceArr['end_date'];
    $oldBalance=$balanceArr['Balance'];
    $oldMainBalance=$balanceArr['MainBalance'];
    $oldUsed=$balanceArr['Used'];
    
    $PlanQry=mysql_query("SELECT * FROM plan_master WHERE Id='$planId'");
    $plan=  mysql_fetch_assoc($PlanQry);
    
    if($plan['TransferAfterRental'] =='Yes'){
        if($oldUsed < $oldMainBalance){
            $newbalance=($plan['Balance']+$oldBalance);
        }
        else{
            $newbalance=$plan['Balance']; 
        }
    }
    else{
        $newbalance=$plan['Balance']; 
    }
    
    $insertHistory=mysql_query("INSERT INTO `balance_master_history` (`Id`,`clientId`,`PlanId`,`PlanType`,`Balance`,`Used`,`MainBalance`,
    `start_date`,`end_date`,`FreeRentalDays`,`userid`,`createdate`,`paymentTypes`,`paymentDate`,`paymentRemark`,`renewalDate`,
    `paymentReceive`,`ptpCount`,`insertdate`)SELECT `Id`,`clientId`,`PlanId`,`PlanType`,`Balance`,`Used`,`MainBalance`,
    `start_date`,`end_date`,`FreeRentalDays`,`userid`,`createdate`,`paymentTypes`,`paymentDate`,`paymentRemark`,`renewalDate`,
    `paymentReceive`,`ptpCount`,NOW() FROM   `balance_master`WHERE  clientId='$clientid'");
    
    if($insertHistory){
        $update=mysql_query("UPDATE `balance_master` SET PlanId='$planId',PlanType='$planType',Balance='{$newbalance}',Used=NULL,
        MainBalance='{$newbalance}',userid='$userid',start_date ='$start_date',end_date ='$end_date' WHERE clientId='$clientid'");
        if($update){
            $update=mysql_query("UPDATE `renewal_plan_master` SET ApplyStatus='Yes' WHERE Id='$Id'");
        }
    }
    
}
    
?>


