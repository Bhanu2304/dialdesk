<?php
$PhpDate = date('Y-m-d');

$con = mysql_connect("192.168.10.5","root","vicidialnow","false",128);
mysql_select_db("asterisk",$con)or die("cannot select DB");

$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$qry=mysql_query("SELECT * FROM `balance_master` WHERE PlanType='Prepaid' AND CrmTagStatus='No' ");

while($result=  mysql_fetch_assoc($qry)){
    
    $PlanDetails =   mysql_fetch_assoc(mysql_query("select * from `plan_master` where Id='{$result['PlanId']}' limit 1"));
    $clientId    =   $result['clientId'];
    $FromDate    =   $result['start_date'];
    //$ToDate      =   $result['end_date'];
    $ToDate      =   date('Y-m-d');
    
    $ClientInfo = mysql_fetch_assoc(mysql_query("select * from `registration_master` where company_id='$clientId' limit 1"));
    $Campagn=$ClientInfo['campaignid'];
    
   
    
    
    $InDuration=mysql_query("select length_in_sec,queue_seconds from `vicidial_closer_log` where campaign_id in ($Campagn) AND DATE(call_date) between '$FromDate' AND '$ToDate' ",$con);
    $inTotalSumaryUnit=0;
    while($DurArr=mysql_fetch_assoc($InDuration)){
         $InDurArr['Duration']=($DurArr['length_in_sec']-$DurArr['queue_seconds']);
        if($InDurArr['Duration'] >30){
            $callLength = $InDurArr['Duration'];
            $unit = ceil($callLength/60);
        }
        else{
            $callLength =0;
            $unit =0; 
        }
        
        $amount = 0; 
        if($PlanDetails['InboundCallMinute']=='Flat'){
            $unit = 1;
            $amount = $PlanDetails['InboundCallCharge'];
        }
        else{
            $perMinute = $PlanDetails['InboundCallMinute']*60;
            $unit = ceil($callLength/$perMinute);
            $amount = $unit*$PlanDetails['InboundCallCharge'];
        }

        $inTotalSumaryUnit = $inTotalSumaryUnit+$unit;
    }
    
    
    
    $OutDuration=mysql_query("select length_in_sec from `vicidial_log` where campaign_id in ($Campagn) AND DATE(call_date) between '$FromDate' AND '$ToDate'",$con);
    
    $OutTotalSumaryUnit=0;
    while($OutDurArr=mysql_fetch_assoc($OutDuration)){
        $callLength = $OutDurArr['length_in_sec'];
        $amount = 0; 
        $unit = ceil($callLength/60);
        if($PlanDetails['OutboundCallMinute']=='Flat'){
            $unit = 1;
            $amount = $PlanDetails['OutboundCallCharge'];
        }
        else{
            $perMinute = $PlanDetails['OutboundCallMinute']*60;
            $unit = ceil($callLength/$perMinute);
            $amount = $unit*$PlanDetails['OutboundCallCharge'];
        }

        $OutTotalSumaryUnit = $OutTotalSumaryUnit+$unit;
    }
    
    
    
    $VFO   = mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId' AND DedType='VFO' AND date(CallDate) between '$FromDate' AND '$ToDate'"));
    $SMS   = mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId' AND DedType='SMS' AND date(CallDate) between '$FromDate' AND '$ToDate'"));
    $Email = mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId' AND DedType='Email' AND date(CallDate) between '$FromDate' AND '$ToDate'"));

    $UseBalance =(round($inTotalSumaryUnit*$PlanDetails['InboundCallCharge'],2)+round($OutTotalSumaryUnit*$PlanDetails['OutboundCallCharge'],2)+round($VFO['Unit']*$PlanDetails['VFOCallCharge'],2)+round($SMS['Unit']*$PlanDetails['SMSCharge'],2)+round($Email['Unit']*$PlanDetails['EmailCharge'],2));
    
    //$result['Balance']
    //$result['MainBalance']
    //$PlanDetails['Balance']
    
    //$TotalBalance =(intval($result['MainBalance'])-intval($UseBalance));
    
    $TotalBalance =(round($result['MainBalance'],2)-round($UseBalance,2));
    
    /*
    if(intval($UseBalance) >= intval($result['MainBalance'])){
        mysql_query("UPDATE balance_master SET Balance=0,Used='$UseBalance' WHERE Id='{$result['Id']}' AND PlanType='Prepaid'");
    }
    else{
        */
        
    mysql_query("UPDATE balance_master SET Balance='$TotalBalance',Used='$UseBalance' WHERE Id='{$result['Id']}' AND PlanType='Prepaid'");   
    
      /*
      }*/   
}
exit;
?>


