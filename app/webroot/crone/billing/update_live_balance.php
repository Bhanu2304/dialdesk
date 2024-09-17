<?php
$PhpDate = date('Y-m-d');
$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$qry=mysql_query("SELECT * FROM `balance_master` WHERE PlanType='Prepaid' AND CrmTagStatus='Yes' ");

while($result=  mysql_fetch_assoc($qry)){
    
    $PlanDetails =   mysql_fetch_assoc(mysql_query("select * from `plan_master` where Id='{$result['PlanId']}' limit 1"));
    $clientId    =   $result['clientId'];
    $FromDate    =   $result['start_date'];
    $ToDate      =   $result['end_date'];
    //$ToDate      =   $PhpDate;
    
    //$FromDate    =   "2017-04-04";
    //$ToDate      =   "2017-07-03";
    
    
    //echo "<pre>";
    //print_r($result);
    //echo "</pre>";
    
    $InDuration=mysql_query("SELECT Duration FROM `billing_master` WHERE clientId='$clientId' AND LeadId !='' AND DedType='Inbound' AND date(CallDate) between '$FromDate' AND '$ToDate' GROUP BY LeadId");
    $inTotalSumaryUnit=0;
    while($InDurArr=mysql_fetch_assoc($InDuration)){
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
    
    $OutDuration=mysql_query("SELECT Duration FROM `billing_master` WHERE clientId='$clientId' AND LeadId !='' AND DedType='Outbound' AND date(CallDate) between '$FromDate' AND '$ToDate' GROUP BY LeadId");
    $OutTotalSumaryUnit=0;
    while($OutDurArr=mysql_fetch_assoc($OutDuration)){
        $callLength = $OutDurArr['Duration'];
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


