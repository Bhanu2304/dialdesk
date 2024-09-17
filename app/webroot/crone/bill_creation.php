<?php 

$con = mysql_connect('localhost','root','dial@mas123',false,128);
$db = mysql_select_db('db_dialdesk',$con) or die('unable to connect');

$DB_Connect = mysql_connect("192.168.10.5","root","vicidialnow");

$selectPostClient = "SELECT * FROM post_bill_master pbm1 WHERE pbm1.createdate = (SELECT MAX(createdate) FROM post_bill_master pbm2 WHERE pbm1.clientId = pbm2.clientId)";
$ExePostClient = mysql_query($selectPostClient,$con);

while($clientInfo =  mysql_fetch_assoc($ExePostClient))
{
   // echo "sadfsdf"; exit;
    $plan = $clientInfo['PlanId'];
    $clientId = $clientInfo['clientId'];
    
    $planInfo = mysql_fetch_assoc(mysql_query("Select * from plan_master where Id = '$plan'",$con));
    $smsChar = $planInfo['SMSLength'];
    $smsCharge = $planInfo['SMSCharge'];
    $EmailCharge = $planInfo['EmailCharge'];
    $InboundCallCharge = $planInfo['InboundCallCharge'];
    $InboundCallMinute = $planInfo['InboundCallMinute'];
    $OutboundCallCharge = $planInfo['OutoundCallCharge'];
    $OutboundCallMinute = $planInfo['OutboundCallMinute'];
    $MissCallCharge = $planInfo['MissCallCharge'];
    $VFOCallCharge = $planInfo['VFOCallCharge'];
    $flat =  $planInfo['Flat'];
    $flatAmount =  $planInfo['FlatAmount'];
    
    
    $CountSms = mysql_fetch_assoc(mysql_query("SELECT SUM(CEIL(CHAR_LENGTH(smsMsg)/$smsChar)) `smsCount` FROM sms_log where ClientId = '$clientId' AND MONTH(sms_time)=(MONTH(CURDATE())-1)",$con));
    $TotalSMS = $CountSms['smsCount']*$smsCharge;
    
    $CountEmail = mysql_fetch_assoc(mysql_query("SELECT COUNT(1) `EmailCount` FROM mail_log where ClientId = '$clientId' AND MONTH(mail_date)=(MONTH(CURDATE())-1)",$con));
    $TotalEmail = $CountEmail['EmailCount']*$EmailCharge;
    
    $CountVFO = mysql_fetch_assoc(mysql_query("SELECT COUNT(1) `VFO` FROM call_master  WHERE clientId='$clientId' CallType='VFO-Inbound' AND MONTH(CallDate)=(MONTH(CURDATE())-1)",$con));
    $TotalVFO = $CountVFO['VFO']*$VFOCallCharge;
    
    $InboundExe = mysql_query("Select distinct(LeadId) LeadId from call_master Where clientId = '$clientId' and CallType='Inbound' AND MONTH(CallDate)=(MONTH(CURDATE())-1)",$con);
    $TotalInbound = 0; 
    
    while($lead = mysql_fetch_assoc($InboundExe))
    {
        $leadId = $lead['LeadId'];
        $length = mysql_fetch_assoc(mysql_query("Select length_in_sec from vicidial_call_log where lead_id='$leadId' limit 1",$DB_Connect));
        $TotalInbound += ceil($length['length_in_sec']/($InboundCallMinute*60));
    }
    
    $OutboundExe = mysql_query("Select distinct(LeadId) LeadId from call_master_out Where clientId = '$clientId' AND MONTH(CallDate)=(MONTH(CURDATE())-1)",$con);
    $TotalOutbound = 0;
    
    while($lead = mysql_fetch_assoc($OutboundExe))
    {
        $leadId = $lead['LeadId'];
        $length = mysql_fetch_assoc(mysql_query("Select length_in_sec from vicidial_call_log where lead_id='$leadId' limit 1",$DB_Connect));
        $TotalOutbound += ceil($length['length_in_sec']/($OutboundCallMinute*60));
    }
    
    $lateCharge = 0;
    if($clientInfo['paymentStatus']==0)
        {
            $lateCharge = 100;
        }
    
    $Total = $TotalSMS+$TotalEmail+$TotalVFO+$TotalInbound+$$TotalOutbound;

    if($flatAmount>$Total)
    {
        $Total  = $flatAmount;
    }
    
    $serviceTax = $Total*0.14;
    $sbcTax = $Total*0.005;
    $krishiTax = $Total*0.005;
    $payment = $clientInfo['paymentPaid'];
    $OldDue = $clientInfo['paymentDue']+$lateCharge-$payment;
    $paymentDue = $OldDue+$Total+$serviceTax+$sbcTax+$krishiTax;
   //echo '<br>';
   //echo 'Total='.$Total.' lastcarried= '.$OldDue.' service = '.$serviceTax.' sbc= '.$sbcTax.' krishi = '.$krishiTax.'<br>';
    $AfterDueDate = $paymentDue+$lateCharge;
    
    $lastBill = $clientInfo['CurrentCharge']+$clientInfo['serviceTax']+$clientInfo['sbcTax']+$clientInfo['krishiTax'];
   
    
     $ins = "insert into post_bill_master set clientId='$clientId', PlanId='$plan', BillStartDate=concat(date_format(SUBDATE(CURDATE(),INTERVAL 1 MONTH),'%Y-%m'),'-1'), BillEndDate= LAST_DAY(SUBDATE(CURDATE(),INTERVAL 1 MONTH)), "
            . "LastBilled='$lastBill',paymentPaid='0',CurrentCharge='$Total',LastCarriedAmount='$OldDue',serviceTax='$serviceTax',sbcTax='$sbcTax',krishiTax='$krishiTax',paymentDue='$paymentDue',DueDate=concat(date_format(curdate(),'%Y-%m-'),'15'),AfterDueDate='$AfterDueDate',createdate=now()"; 
     
    $InsExe = mysql_query($ins,$con);
    
    
 //echo '<br>';   
}

$selectPostClient = "SELECT * FROM `balance_master` WHERE clientId NOT IN (SELECT DISTINCT(clientId) FROM `post_bill_master`)";
$ExePostClient = mysql_query($selectPostClient,$con);

while($clientInfo =  mysql_fetch_assoc($ExePostClient))
{
   // echo "sadfsdf"; exit;
    $plan = $clientInfo['PlanId'];
    $clientId = $clientInfo['clientId'];
    
    $planInfo = mysql_fetch_assoc(mysql_query("Select * from plan_master where Id = '$plan'",$con));
    $smsChar = $planInfo['SMSLength'];
    $smsCharge = $planInfo['SMSCharge'];
    $EmailCharge = $planInfo['EmailCharge'];
    $InboundCallCharge = $planInfo['InboundCallCharge'];
    $InboundCallMinute = $planInfo['InboundCallMinute'];
    $OutboundCallCharge = $planInfo['OutoundCallCharge'];
    $OutboundCallMinute = $planInfo['OutboundCallMinute'];
    $MissCallCharge = $planInfo['MissCallCharge'];
    $VFOCallCharge = $planInfo['VFOCallCharge'];
    $flat =  $planInfo['Flat'];
    $flatAmount =  $planInfo['FlatAmount'];
    
    
    $CountSms = mysql_fetch_assoc(mysql_query("SELECT SUM(CEIL(CHAR_LENGTH(smsMsg)/$smsChar)) `smsCount` FROM sms_log where ClientId = '$clientId' AND MONTH(sms_time)=(MONTH(CURDATE())-1)",$con));
    $TotalSMS = $CountSms['smsCount']*$smsCharge;
    
    $CountEmail = mysql_fetch_assoc(mysql_query("SELECT COUNT(1) `EmailCount` FROM mail_log where ClientId = '$clientId' AND MONTH(mail_date)=(MONTH(CURDATE())-1)",$con));
   $TotalEmail = $CountEmail['EmailCount']*$EmailCharge;
    
    $CountVFO = mysql_fetch_assoc(mysql_query("SELECT COUNT(1) `VFO` FROM call_master  WHERE clientId='$clientId' CallType='VFO-Inbound' AND MONTH(CallDate)=(MONTH(CURDATE())-1)",$con));
    $TotalVFO = $CountVFO['VFO']*$VFOCallCharge;
    
    $InboundExe = mysql_query("Select distinct(LeadId) LeadId from call_master Where clientId = '$clientId' and CallType='Inbound' AND MONTH(CallDate)=(MONTH(CURDATE())-1)",$con);
    $TotalInbound = 0; 
    
    while($lead = mysql_fetch_assoc($InboundExe))
    {
        $leadId = $lead['LeadId'];
        $length = mysql_fetch_assoc(mysql_query("Select length_in_sec from asterisk.vicidial_closer_log where lead_id='$leadId' limit 1",$DB_Connect));
        //print_r($length);
        $TotalInbound += ceil($length['length_in_sec']/($InboundCallMinute*60));
    }
    
    
    
    
    $TotalInbound = $TotalInbound*$InboundCallCharge;
    $OutboundExe = mysql_query("Select LeadId,DialType from call_master_out Where clientId = '$clientId' AND MONTH(CallDate)=(MONTH(CURDATE())-1)",$con);
    $TotalOutbound = 0;
    
    while($lead = mysql_fetch_assoc($OutboundExe))
    {
        $leadId = $lead['LeadId'];
        if($lead['DialType']=='PD')
        {$length = mysql_fetch_assoc(mysql_query("Select length_in_sec from asterisk.call_log where uniqueid = '$leadId' limit 1",$DB_Connect));}
        else
        {$length = mysql_fetch_assoc(mysql_query("Select length_in_sec from asterisk.vicidial_log where lead_id = '$leadId' limit 1",$DB_Connect));}
        
        $TotalOutbound += ceil($length['length_in_sec']/($OutboundCallMinute*60));
    }
    
    $lateCharge = 0;
    if($clientInfo['paymentStatus']==0)
        {
            $lateCharge = 100;
        }
    
    $TotalOutbound = $TotalOutbound*$OutboundCallCharge;
    
    $Total = $TotalSMS+$TotalEmail+$TotalVFO+$TotalInbound+$$TotalOutbound;
    $OldDue = $clientInfo['paymentDue']-$payment;
    
    
    if($flatAmount>$Total)
    {
        $Total  = $flatAmount;
    }
    
    $serviceTax = $Total*0.14;
    $sbcTax = $Total*0.005;
    $krishiTax = $Total*0.005;
    $payment = $clientInfo['paymentPaid'];
    $paymentDue = $Total+$OldDue+$serviceTax+$sbcTax+$krishiTax-$payment;
    $AfterDueDate = $paymentDue+$lateCharge;
    $lastBill = $clientInfo['CurrentCharge']+$clientInfo['serviceTax']+$clientInfo['sbcTax']+$clientInfo['krishiTax']+$lateCharge;
    
    $ins = "insert into post_bill_master set clientId='$clientId', PlanId='$plan', BillStartDate=concat(date_format(SUBDATE(CURDATE(),INTERVAL 1 MONTH),'%Y-%m'),'-1'), BillEndDate= LAST_DAY(SUBDATE(CURDATE(),INTERVAL 1 MONTH)), "
            . "LastBilled='$lastBill',paymentPaid='0',CurrentCharge='$Total',LastCarriedAmount='$OldDue',serviceTax='$serviceTax',sbcTax='$sbcTax',krishiTax='$krishiTax',paymentDue='$paymentDue',DueDate=concat(date_format(curdate(),'%Y-%m-'),'15'),AfterDueDate='$AfterDueDate',createdate=now()"; 
     
    $InsExe = mysql_query($ins,$con);
 //echo '<br>';     
}