<?php
$clientId   = $_REQUEST['ClientId'];
$FromDate   = date_format(date_create($_REQUEST['FromDate']),'Y-m-d');
$ToDate     = date_format(date_create($_REQUEST['ToDate']),'Y-m-d');

$host       =   "localhost"; 
$username   =   "root";
$password   =   "dial@mas123";
$db_name    =   "db_dialdesk";

$con = mysql_connect("192.168.10.5","root","vicidialnow","false",128);
mysql_select_db("asterisk",$con)or die("cannot select DB");

mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");

//$BalanceMaster = mysql_fetch_assoc(mysql_query("select * from `balance_master` where clientId='$clientId' AND CrmTagStatus='No' limit 1"));
$BalanceMaster = mysql_fetch_assoc(mysql_query("select * from `balance_master` where Id='70' limit 1"));
//echo "<pre>";
//print_r($BalanceMaster);die;

if($BalanceMaster['PlanId'] !=""){
    
    $ClientInfo = mysql_fetch_assoc(mysql_query("select * from `registration_master` where company_id='$clientId' limit 1"));
    $Campagn=$ClientInfo['campaignid'];
    
    $PlanDetails = mysql_fetch_assoc(mysql_query("select * from `plan_master` where Id='{$BalanceMaster['PlanId']}' limit 1"));
    $start_date = "";
    $end_date = "";
    $balance = $BalanceMaster['MainBalance'];
    
    
    // Inbound Call duration details
    
    $InDuration=mysql_query("select length_in_sec,queue_seconds,call_date from `vicidial_closer_log` where campaign_id in ($Campagn) AND DATE(call_date) between '$FromDate' AND '$ToDate' ",$con);

    $inTotalSumaryUnit=0;$inTotalSumaryUnitNight = 0;
    while($DurArr=mysql_fetch_assoc($InDuration)){
        $InDurArr['Duration']=($DurArr['length_in_sec']-$DurArr['queue_seconds']);
        
        if($InDurArr['Duration'] >30){
            $callLength = $InDurArr['Duration'];
            $unit = ceil($callLength/30);
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
            $perMinute = 1*30;
            $unit = ceil($callLength/$perMinute);
            $amount = $unit*$PlanDetails['InboundCallCharge'];
        }

        //echo $InDurArr['call_date']; exit;
       if(strtotime(date('H:i:s',strtotime($DurArr['call_date'])))>=strtotime('20:00:00') 
                        || strtotime(date('H:i:s',strtotime($DurArr['call_date'])))<strtotime('08:00:00'))
       {
           $inTotalSumaryUnitNight = $inTotalSumaryUnitNight+$unit;
       }
       else
       {
            $inTotalSumaryUnit = $inTotalSumaryUnit+$unit; 
       }
        
        //$inTotalSumaryUnit = $inTotalSumaryUnit+$unit;
    }

    // Outbound Call duration details
   //echo $inTotalSumaryUnitNight; exit;
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

    $data = mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId' and date(CallDate) between '$FromDate' AND '$ToDate'"));
    
    $VFO = mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
    AND DedType='VFO' AND date(CallDate) between '$FromDate' AND '$ToDate'"));

    $SMS =  mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
    AND DedType='SMS' AND date(CallDate) between '$FromDate' AND '$ToDate'"));

    $Email = mysql_fetch_assoc(mysql_query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
    AND DedType='Email' AND date(CallDate) between '$FromDate' AND '$ToDate'"));
    
    $InboundDetails=mysql_query("select length_in_sec,queue_seconds,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_closer_log` where campaign_id in ($Campagn) AND DATE(call_date) between '$FromDate' AND '$ToDate' ",$con);
    //$InboundDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit,Duration FROM `billing_master` WHERE clientId='$clientId' AND LeadId !='' AND DedType='Inbound' AND date(CallDate) between '$FromDate' AND '$ToDate' GROUP BY LeadId order by Id");
    //$OutboundDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit,Duration FROM `billing_master` WHERE clientId='$clientId' AND LeadId !='' AND DedType='Outbound' AND date(CallDate) between '$FromDate' AND '$ToDate' GROUP BY LeadId order by Id");
  //  echo "select length_in_sec,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_log` where campaign_id in ($Campagn) AND DATE(call_date) between '$FromDate' AND '$ToDate'"; exit;
    $OutboundDetails=mysql_query("select length_in_sec,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_log` where campaign_id in ($Campagn) AND DATE(call_date) between '$FromDate' AND '$ToDate'",$con);
    $SMSDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='SMS' AND date(CallDate) between '$FromDate' AND '$ToDate' order by Id");
    $EmailDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='Email' AND date(CallDate) between '$FromDate' AND '$ToDate' order by Id");
    $VFODetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='VFO' AND date(CallDate) between '$FromDate' AND '$ToDate' order by Id");

    $TinAmount=0;
    $TouAmount=0;
    $TvfAmount=0;
    $TsmAmount=0;
    $TemAmount=0;
    
    if($inTotalSumaryUnit !="") {$TinAmount=round($inTotalSumaryUnit*$PlanDetails['InboundCallCharge'],2);}
    if($inTotalSumaryUnitNight !="") { $TinAmountNight=round($inTotalSumaryUnitNight*$PlanDetails['InboundCallChargeNight'],2);}
    if($OutTotalSumaryUnit !="") {$TouAmount=round($OutTotalSumaryUnit*$PlanDetails['OutboundCallCharge'],2);}
    if(!empty($VFO['Unit'])) {$TvfAmount=round($VFO['Unit']*$PlanDetails['VFOCallCharge'],2);}
    if(!empty($SMS['Unit'])) {$TsmAmount=round($SMS['Unit']*$PlanDetails['SMSCharge'],2);}
    if(!empty($Email['Unit'])) {$TemAmount=round($Email['Unit']*$PlanDetails['EmailCharge'],2);}
    
    $html .="
            <table border='0' width='600' cellpadding='2' cellspacing='2' style='font-size:11pt;' >
                <tr>
                    <td colspan='2' rowspan='4' ></td><td colspan='4' rowspan='4'>
                        <img src='http://dialdesk.co.in/dialdesk/app/webroot/billing_statement/logo.jpg'>
                    </td>
                </tr>
            </table>
    ";
    
    $html .="
            <table border='0' width='600' cellpadding='2' cellspacing='2' style='font-size:11pt;' >
                <tr>
                    <td colspan='2' rowspan='2' ></td><td colspan='4' rowspan='2' >
                        A UNIT OF ISPARK DATA CONNECT PVT LTD
                    </td>
                </tr>
            </table>
    ";
    
    
    $html .="
            <table border='1' width='600' cellpadding='2' cellspacing='2' style='font-size:11pt;' >
            <tr><td colspan='7' style='font-size:15pt;background-color:#607d8b;color:#fff;font-weight:bold;'>Client Details</td></tr>
            <tr>
                <th>Company</th>
                <th colspan='3' >Address</th>
                <th>registered Mobile No</th>
                <th>Registered Email Id</th>
                <th>authorised person</th>
            </tr>
            <tr>
                <td>{$ClientInfo['company_name']}</td>
                <td colspan='3' >{$ClientInfo['reg_office_address1']}</td>
                <td>{$ClientInfo['phone_no']}</td>
                <td>{$ClientInfo['email']}</td>
                <td>{$ClientInfo['auth_person']}</td>
            </tr>
            </table>
    ";

   $html .="<table><tr><td>&nbsp;</td></tr></table>";
    
    $TotUseBalance=(round($inTotalSumaryUnit*$PlanDetails['InboundCallCharge'],2)+round($inTotalSumaryUnitNight*$PlanDetails['InboundCallChargeNight'],2)+round($OutTotalSumaryUnit*$PlanDetails['OutboundCallCharge'],2)+round($VFO['Unit']*$PlanDetails['VFOCallCharge'],2)+round($SMS['Unit']*$PlanDetails['SMSCharge'],2)+round($Email['Unit']*$PlanDetails['EmailCharge'],2));

    
    $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
    $html .="<tr><td colspan='7' style='font-size:15pt;background-color:#607d8b;color:#fff;font-weight:bold;'>Plan Details</td></tr>";
    
    $html .="<tr>";
    $html .="<th>Plan Name</th>";
    $html .="<th>Start Date</th>";
    $html .="<th>End Date</th>";
    $html .="<th>Validity</th>";
    $html .="<th>Balance</th>";
    $html .="<th>Available</th>";
    $html .="<th>Used</th>";
    $html .="</tr>";
    $html .="<tr>";
    $html .="<td>{$PlanDetails['PlanName']}</td>";
    $html .="<td>{$BalanceMaster['start_date']}</td>";
    $html .="<td>{$BalanceMaster['end_date']}</td>";
    $html .="<td>".$PlanDetails['RentalPeriod'].' '.$PlanDetails['PeriodType']."</td>";
    $html .="<td>".$balance."</td>";
    $used = ($TinAmount+$TinAmountNight+$TouAmount+$TvfAmount+$TsmAmount+$TemAmount);
    //if(intval($BalanceMaster['Used']) >= intval($BalanceMaster['MainBalance'])){
        //$html .="<td>0</td>";
    //}
    //else{
        $html .="<td>".($balance-$used)."</td>";
    //}
    
    
    $html .="<td>".$used."</td>";
    $html .="</tr>";
    $html .="</table>";
    
    
    
    
    
    $html .="<table><tr><td>&nbsp;</td></tr></table>";
    
    if($inTotalSumaryUnit !="" || $inTotalSumaryUnitNight !="" || $OutTotalSumaryUnit !="" || $VFO['Unit'] !="" || $SMS['Unit'] !="" || $Email['Unit'] !="") {
        $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html .="<tr><td colspan='5' style='font-size:15pt;background-color:#607d8b;color:#fff;font-weight:bold;'>Summary</td></tr>";
        $html .="<tr>";
        $html .="<th>Description</th>";
        $html .="<th>Vol./Pulse</th>";
        $html .="<th>Rate</th>";
        $html .="<th colspan='2' >Amount</th>";
        $html .="</tr>";
    }
    
    if($inTotalSumaryUnit !="") {
        
        $html .="<tr>";
        $html .="<td>ICB</td>";
        $html .="<td>{$inTotalSumaryUnit}</td>";
        $html .="<td>{$PlanDetails['InboundCallCharge']}  Rs./ {$PlanDetails['InboundCallMinute']} Min</td>";
        $html .="<td colspan='2'>".round($inTotalSumaryUnit*$PlanDetails['InboundCallCharge'],2)."</td>";
        $html .="</tr>";
    }
    if($inTotalSumaryUnitNight !="") {
       
        $html .="<tr>";
        $html .="<td>ICB Night</td>";
        $html .="<td>{$inTotalSumaryUnitNight}</td>";
        $html .="<td>{$PlanDetails['InboundCallChargeNight']}  Rs./ {$PlanDetails['InboundCallMinute']} Min</td>";
        $html .="<td colspan='2'>".round($inTotalSumaryUnitNight*$PlanDetails['InboundCallChargeNight'],2)."</td>";
        $html .="</tr>";
    }

    if($OutTotalSumaryUnit !="") {
        
        $html .="<tr>";
        $html .="<td>OCB</td>";
        $html .="<td>{$OutTotalSumaryUnit}</td>";
        $html .="<td>{$PlanDetails['OutboundCallCharge']}  Rs./ {$PlanDetails['OutboundCallMinute']} Min</td>";
        $html .="<td colspan='2'>".round($OutTotalSumaryUnit*$PlanDetails['OutboundCallCharge'],2)."</td>";
        $html .="</tr>";
    }

    if(!empty($VFO['Unit'])) {
        
        $html .="<tr>";
        $html .="<td>VFO</td>";
        $html .="<td>{$VFO['Unit']}</td>";
        $html .="<td>{$PlanDetails['VFOCallCharge']}  Rs./Min </td>";
        $html .="<td colspan='2'>".round($VFO['Unit']*$PlanDetails['VFOCallCharge'],2)."</td>";
        $html .="</tr>";
    }

    if(!empty($SMS['Unit'])) {
        
        $html .="<tr>";
        $html .="<td>SMS</td>";
        $html .="<td>{$SMS['Unit']}</td>";
        $html .="<td>{$PlanDetails['SMSCharge']}  Rs./Min </td>";
        $html .="<td colspan='2'>".round($SMS['Unit']*$PlanDetails['SMSCharge'],2)."</td>";
        $html .="</tr>";
    }

    if(!empty($Email['Unit'])) {
        
        $html .="<tr>";
        $html .="<td>Email</td>";
        $html .="<td>{$Email['Unit']}</td>";
        $html .="<td>{$PlanDetails['EmailCharge']}  Rs./Min </td>";
        $html .="<td colspan='2'>".round($Email['Unit']*$PlanDetails['EmailCharge'],2)."</td>";
        $html .="</tr>";
    }
    
    $html .="<tr>";
    $html .="<td>TOTAL ({$FromDate} / {$ToDate})</td>";
    $html .="<td></td>";
    $html .="<td></td>";
    $html .="<td colspan='2'>".($TinAmount+$TinAmountNight+$TouAmount+$TvfAmount+$TsmAmount+$TemAmount)."</td>";
    $html .="</tr>";

    $html .="</table>";

    //$html .="<br/>";

    if(mysql_num_rows($InboundDetails) > 0){
        $html .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (INBOUND)</h5>";
        $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html .="<tr>";
        $html .="<th>Date</th>";
        $html .="<th>Time</th>";
        $html .="<th>Call From</th>";
        $html .="<th>Talk Time</th>";
        $html .="<th>Pulse</th>";
        $html .="<th>Rate</th>";
        $html .="</tr>";

        $InTotalPulse  =0;
        $InTotalAmount =0;
        $InTotalTalkTime =0;
        $InTotalTalkRate =0;
        while($inbDurArr = mysql_fetch_assoc($InboundDetails)){
            
            $inb['Duration']=($inbDurArr['length_in_sec']-$inbDurArr['queue_seconds']);
            
            if($inb['Duration'] >30){
                $callLength = $inb['Duration'];
                $unit = ceil($callLength/30);
            }
            else{
                $callLength =0;
                $unit =0;  
            }
            
            $amount = 0; 

            if($PlanDetails['InboundCallMinute']=='Flat'){
                $unit = 1;
                
                if(strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))>=strtotime('20:00:00') 
                        || strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))<=strtotime('08:00:00'))
                {
                    $amount = $PlanDetails['InboundCallChargeNight'];
                }
                else
                {
                    $amount = $PlanDetails['InboundCallCharge'];
                }
                
            }
            else{
                $perMinute = 1*30;
                $unit = ceil($callLength/$perMinute);
                /*echo strtotime(date('H:i:s',strtotime($inbDurArr['call_date']))); //exit;
                echo '<br>';
                echo strtotime('08:00:00'); exit;*/
                //$amount = $unit*$PlanDetails['InboundCallCharge'];
                if(strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))>=strtotime('20:00:00') 
                        || strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))<strtotime('08:00:00'))
                {
                    $amount = $unit*$PlanDetails['InboundCallChargeNight'];
                }
                else
                {
                    $amount = $unit*$PlanDetails['InboundCallCharge'];
                }
            }

            $html .="<tr>";
            $html .="<td>".date('Y-m-d',strtotime($inbDurArr['call_date']))."</td>";
            $html .="<td>".date('H:i:s',strtotime($inbDurArr['call_date']))."</td>";
            $html .="<td>".$inbDurArr['phone_number']."</td>";
            $html .="<td>".$inb['Duration']."</td>";
            $html .="<td>".$unit."</td>";
            $html .="<td>".round($amount,2)."</td>";
            $html .="</tr>";

            $InTotalTalkTime += $inb['Duration'];
            $InTotalPulse = $InTotalPulse+$unit;
            $InTotalAmount = $InTotalAmount+$amount;
            $InTotalTalkRate +=round($amount,2);
        }
        //$html .="<tr><td colspan='3' ><b>Total</b></td><td><b>{$InTotalPulse}</b></td><td><b>{$InTotalAmount}</b></td></tr>";
        $html .="<tr><td colspan='3' align=\"right\"><b>Total</b></td>";
        $html .="<td><b> {$InTotalTalkTime}</b></td>";
        $html .="<td><b> {$InTotalPulse}</b></td>";
        $html .="<td><b> {$InTotalTalkRate}</b></td>";
        $html .="</tr></table>";
    }

    //$html .="<br/><br/>";

  
    if(mysql_num_rows($OutboundDetails) > 0){
        $html .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (OUTBOUND)</h5>";
        $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html .="<tr>";
        $html .="<th>Date</th>";
        $html .="<th>Time</th>";
        $html .="<th>Call From</th>";
        $html .="<th>Talk Time</th>";
        $html .="<th>Pulse</th>";
        $html .="<th>Rate</th>";
        $html .="</tr>";

        $OutTotalPulse  =0;
        $OutTotalAmount =0;
        $OutTotalTalkTime =0;
        $OutTotalTalkRate =0;
        while($inb = mysql_fetch_assoc($OutboundDetails)){
           
            $callLength = $inb['length_in_sec'];
            $amount = 0; 
            $unit   = ceil($callLength/30);
            if($PlanDetails['OutboundCallMinute']=='Flat'){
                $unit = 1;
                $amount = $PlanDetails['OutboundCallCharge'];
            }
            else{
                $perMinute = 1*30;
                $unit = ceil($callLength/$perMinute);
                $amount = $unit*$PlanDetails['OutboundCallCharge'];
            }

            $html .="<tr>";
            $html .="<td>".date('Y-m-d',strtotime($inb['call_date']))."</td>";
            $html .="<td>".date('H:i:s',strtotime($inb['call_date']))."</td>";
            $html .="<td>".$inb['phone_number']."</td>";
            $html .="<td>".$callLength."</td>";
            $html .="<td>".$unit."</td>";
            $html .="<td>".round($unit*$PlanDetails['OutboundCallCharge'],2)."</td>";
            $html .="</tr>";

            $OutTotalTalkTime = $OutTotalTalkTime+$callLength;
            $OutTotalPulse = $OutTotalPulse+$unit;
            $OutTotalAmount = $OutTotalAmount+$amount;
            $OutTotalTalkRate += round($unit*$PlanDetails['OutboundCallCharge'],2);
        }
        //$html .="<tr><td colspan='3' ><b>Total</b></td><td><b>{$OutTotalPulse}</b></td><td><b>{$OutTotalAmount}</b></td></tr>";
        $html .="<tr><td colspan='3' ><b>Total</b></td>";
        $html .="<td><b>{$OutTotalTalkTime}</b></td>";
        $html .="<td><b>{$OutTotalPulse}</b></td>";
        $html .="<td><b>{$OutTotalTalkRate}</b></td>";
        $html .="</tr></table>";
    }

    //$html .="<br/><br/>";
    
   

    if(mysql_num_rows($SMSDetails) > 0){
        $html .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (SMS)</h5>";
        $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html .="<tr>";
        $html .="<th>Date</th>";
        $html .="<th>Time</th>";
        $html .="<th>Call From</th>";
        $html .="<th>Pulse</th>";
        $html .="<th>Rate</th>";
        $html .="</tr>";
        $SMSTotal = 0;
        while($inb = mysql_fetch_assoc($SMSDetails)){
            $html .="<tr>";
            $html .="<td>".$inb['CallDate']."</td>";
            $html .="<td>".$inb['CallTime']."</td>";
            $html .="<td>".$inb['CallFrom']."</td>";
            $html .="<td>".$inb['Unit']."</td>";
            $html .="<td>".round($inb['Unit']*$PlanDetails['SMSCharge'],2)."</td>";
            $html .="</tr>";
            $SMSTotal += $inb['Unit'];
        }
        $html .="<tr><td colspan='5' ><b>Total Vol {$SMSTotal}</b></td></tr>";
        $html .="</table>";
    }

    //$html .="<br/><br/>";

    if(mysql_num_rows($EmailDetails) > 0){
        $html .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (EMAIL)</h5>";
        $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html .="<tr>";
        $html .="<th>Date</th>";
        $html .="<th>Time</th>";
        $html .="<th>Call From</th>";
        $html .="<th>Pulse</th>";
        $html .="<th>Rate</th>";
        $html .="</tr>";
        $EmailTotal = 0;
        while($inb = mysql_fetch_assoc($EmailDetails)){
            $html .="<tr>";
            $html .="<td>".$inb['CallDate']."</td>";
            $html .="<td>".$inb['CallTime']."</td>";
            $html .="<td>".$inb['CallFrom']."</td>";
            $html .="<td>".$inb['Unit']."</td>";
            $html .="<td>".round($inb['Unit']*$PlanDetails['EmailCharge'],2)."</td>";
            $html .="</tr>";
            $EmailTotal += $inb['Unit'];
        }
        $html .="<tr><td colspan='5' ><b>Total Vol {$EmailTotal}</b></td></tr>";
        $html .="</table>";
    }

    //$html .="<br/><br/>";



    if(mysql_num_rows($VFODetails) > 0){
        $html .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (VFO)</h5>";
        $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html .="<tr>";
        $html .="<th>Date</th>";
        $html .="<th>Time</th>";
        $html .="<th>Call From</th>";
        $html .="<th>Pulse</th>";
        $html .="<th>Rate</th>";
        $html .="</tr>";
        $VFOTotal = 0;
        while($inb = mysql_fetch_assoc($VFODetails)){
            $html .="<tr>";
            $html .="<td>".$inb['CallDate']."</td>";
            $html .="<td>".$inb['CallTime']."</td>";
            $html .="<td>".$inb['CallFrom']."</td>";
            $html .="<td>".$inb['Unit']."</td>";
            $html .="<td>".round($inb['Unit']*$PlanDetails['VFOCallCharge'],2)."</td>";
            $html .="</tr>";
            $VFOTotal += $inb['Unit'];
        }
        $html .="<tr><td colspan='5' ><b>Total Vol {$VFOTotal}</b></td></tr>";
        $html .="</table>";
    }
}



$fileName = "billing".date('d_m_y_h_i_s');
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$fileName.".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo $html ;die;

