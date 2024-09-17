<?php


/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');

$clientId   = $_REQUEST['ClientId'];
$FromDate   = date_format(date_create($_REQUEST['FromDate']),'Y-m-d');
$ToDate     = date_format(date_create($_REQUEST['ToDate']),'Y-m-d');

$host       =   "localhost"; 
$username   =   "root";
$password   =   "dial@mas123";
$db_name    =   "db_dialdesk";

$con = mysql_connect("192.168.10.5","root","vicidialnow","false",128);
mysql_select_db("asterisk",$con)or die("cannot select DB");

$dd = mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name",$dd)or die("cannot select DB");

$BalanceMaster = mysql_fetch_assoc(mysql_query("select * from `balance_master` where clientId='$clientId'  limit 1"));
//$BalanceMaster = mysql_fetch_assoc(mysql_query("select * from `balance_master` where Id='70' limit 1"));
//echo "<pre>";
//print_r($BalanceMaster);die;

if($BalanceMaster['PlanId'] !=""){
    
    $ClientInfo = mysql_fetch_assoc(mysql_query("select * from `registration_master` where company_id='$clientId' limit 1"));
    $Campagn=$ClientInfo['campaignid'];
    
    $PlanDetails = mysql_fetch_assoc(mysql_query("select * from `plan_master` where Id='{$BalanceMaster['PlanId']}' limit 1"));
    $start_date = $BalanceMaster['start_date']; 
    $end_date = $BalanceMaster['end_date'];
    $balance = $BalanceMaster['MainBalance'];
    $PeriodType = strtolower($PlanDetails['PeriodType']);
    $bill_month = "";
    
    
       
    // Inbound Call duration details
    //echo "select length_in_sec,queue_seconds,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_closer_log` where campaign_id in ($Campagn) AND DATE(call_date) between '$start_date' AND '$ToDate' "; exit;
    $InboundDetails=mysql_query("select length_in_sec,queue_seconds,RIGHT(phone_number,10) phone_number,call_date from `vicidial_closer_log` where user !='VDCL' and campaign_id in ($Campagn) AND DATE(call_date) between '$start_date' AND '$ToDate' ",$con);
    
    //$InboundDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit,Duration FROM `billing_master` WHERE clientId='$clientId' AND LeadId !='' AND DedType='Inbound' AND date(CallDate) between '$start_date' AND '$ToDate' GROUP BY LeadId order by Id");
    //$OutboundDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit,Duration FROM `billing_master` WHERE clientId='$clientId' AND LeadId !='' AND DedType='Outbound' AND date(CallDate) between '$start_date' AND '$ToDate' GROUP BY LeadId order by Id");
    
    $OutboundDetails=mysql_query("select length_in_sec,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_log` where user !='VDAD' and campaign_id in ($Campagn) AND DATE(call_date) between '$start_date' AND '$ToDate' ",$con);
    
    $OutboundDetails_aband=mysql_query("SELECT t2.call_date AS CallDate,FROM_UNIXTIME(t2.start_epoch) AS StartTime,FROM_UNIXTIME(t2.end_epoch) AS Endtime,LEFT(t2.phone_number,10) AS PhoneNumber,
            t2.`user` AS Agent,vu.full_name as full_name,if(t2.`user`='VDAD','Not Connected','Connected') calltype,t2.status as status,if(t2.`list_id`='998','Mannual','Auto') dialmode,t2.campaign_id as campaign_id,t2.lead_id as lead_id,
            t2.length_in_sec AS LengthInSec,
                        SEC_TO_TIME(t2.length_in_sec) AS LengthInMin,
                        t2.length_in_sec AS CallDuration,
                        t2.`status` AS CallStatus,
                        t3.`pause_sec` AS PauseSec,
                        t3.`wait_sec` AS WaitSec,
                        t3.`talk_sec` AS TalkSec,t3.dispo_sec AS DispoSec
            FROM asterisk.vicidial_log t2
                        LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid left join vicidial_users vu on t2.user=vu.user
                        WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate' AND t2.campaign_id='dialdesk'
                        AND t2.lead_id IS NOT NULL",$con);
    
    
    $SMSDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='SMS' AND date(CallDate) between '$start_date' AND '$ToDate' ");
    
    $EmailDetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='Email' AND date(CallDate) between '$start_date' AND '$ToDate' ");
   // $VFODetails = mysql_query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit "
     //       . "FROM `billing_master` WHERE clientId='$clientId' AND DedType='VFO' AND date(CallDate) between '$start_date' AND '$ToDate' ");
    if($clientId=='383') {
    $VFODetails =mysql_query("select DATE_FORMAT(ivrtime,'%d %b %y') `CallDate1`,ivrtime CallDate,time(ivrtime) CallTime,left(t1.source_number,10) CallFrom,t2.length_in_sec,CEIL(t2.length_in_sec/60) Unit from sbarro_data t1 left join call_log t2 on t1.uniqueid=t2.uniqueid where destination_number!='' and date(ivrtime) between '$start_date' AND '$ToDate'",$con);
}

    $TinAmount=0;
    $TouAmount=0;
    $abTouAmount = 0;
    $TvfAmount=0;
    $TsmAmount=0;
    $TemAmount=0;
    $period_arr = array();
    $balance_arr = array();
    $package_bal = array();
    
    if($PeriodType=='month')
    {
      $package_bal =  round($balance/12);
       for($i=1;$i<=12;$i++)
       {
            $period_arr[$i] =    date('Y-m-d',strtotime($start_date ." + $i months")); 
            //add additional balance
            $last_date = date('Y-m-d',strtotime($start_date ." + $i months"));
            $last_date = date('Y-m-d',strtotime($start_date ." - 1 days"));
            $sel_add_bal = "SELECT * FROM `waiver_master` WHERE clientId='$clientId' AND  DATE(end_date) = '$last_date'";
            $sel_add_bal_rsc = mysql_query($sel_add_bal,$dd);
            while($add_bal_arr = mysql_fetch_assoc($sel_add_bal_rsc))
            {
                if(!empty($add_bal_arr['Balance']))
                {
                    $package_bal += $add_bal_arr['Balance'];
                }
            }
            $balance_arr[$i] = $package_bal;
       }
       
       
    }
    else if($PeriodType=='quater')
    {
       $package_bal =  round($balance/4); 
        for($i=1;$i<=4;$i++)
       {
            $period_arr[$i] =    date('Y-m-d',strtotime($start_date .' + '.($i*3).' months')); 
            $last_date = date('Y-m-d',strtotime($start_date .' + '.($i*3).' months'));
            $last_date = date('Y-m-d',strtotime($start_date ." - 1 days"));
            $sel_add_bal = "SELECT * FROM `waiver_master` WHERE clientId='$clientId' AND  DATE(end_date) = '$last_date'";
            $sel_add_bal_rsc = mysql_query($sel_add_bal,$dd);
            while($add_bal_arr = mysql_fetch_assoc($sel_add_bal_rsc))
            {
                if(!empty($add_bal_arr['Balance']))
                {
                    $package_bal += $add_bal_arr['Balance'];
                }
            }
            $balance_arr[$i] = $package_bal;
       }
       
    }
    else
    {
        $package_bal =  round($balance);
        $period_arr[1] =    $end_date; 
        
            $last_date = $end_date;
            $last_date = date('Y-m-d',strtotime($start_date ." - 1 days"));
            $sel_add_bal = "SELECT * FROM `waiver_master` WHERE clientId='$clientId' AND  DATE(end_date) = '$last_date'";
            $sel_add_bal_rsc = mysql_query($sel_add_bal,$dd);
            while($add_bal_arr = mysql_fetch_assoc($sel_add_bal_rsc))
            {
                if(!empty($add_bal_arr['Balance']))
                {
                    $package_bal += $add_bal_arr['Balance'];
                }
            }
        
        $balance_arr[1] = $package_bal;
    }
   
    //print_r($period_arr); exit;
    $html1 = "";
    if(mysql_num_rows($InboundDetails) > 0){
        $html1 .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (INBOUND)</h5>";
        $html1 .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html1 .="<tr>";
        $html1 .="<th>Date</th>";
        $html1 .="<th>Time</th>";
        $html1 .="<th>Call From</th>";
        $html1 .="<th>Talk Time</th>";
        $html1 .="<th>Pulse</th>";
        $html1 .="<th>Rate</th>";
        $html1 .="</tr>";

        $InTotalPulse  =0;
        $InTotalAmount =0;
        $InTotalTalkTime =0;
        $InTotalTalkRate =0;
        while($inbDurArr = mysql_fetch_assoc($InboundDetails))
        {
            
            $inb['Duration']=($inbDurArr['length_in_sec']-$inbDurArr['queue_seconds']);
            
            if($inb['Duration'] >=60){
                $callLength = $inb['Duration'];
                //$unit = ceil($callLength/30);
            }
            else{
                $callLength =$inb['Duration'];
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

                if(strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))>=strtotime('20:00:00') 
                        || strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))<strtotime('08:00:00'))
                {
                    if($callLength>=60) {
                    $amount = $callLength*($PlanDetails['InboundCallChargeNight']/60); }
                    else{ $amount = $PlanDetails['InboundCallChargeNight']; }
                }
                else
                {
                   // $amount = $unit*$PlanDetails['InboundCallCharge'];

                    if($callLength>=60) {
                    $amount = $callLength*($PlanDetails['InboundCallCharge']/60); }
                    else{ $amount = $PlanDetails['InboundCallCharge']; }
                }
            }
            
            $start_date1 = $start_date;
            $call_date = strtotime(date('Y-m-d',strtotime($inbDurArr['call_date'])));
            foreach($period_arr as $end_date)
            {
                
                if($call_date>=strtotime($start_date1) && $call_date<strtotime($end_date))
                {
                    //$data[$start_date1][$end_date]['InTotalTalkTime'] += $inb['Duration'];
                    //$data[$start_date1][$end_date]['InTotalPulse'] += $unit;
                    $data[$end_date]['InTotalAmount'] += $amount;
                    //$data[$start_date1][$end_date]['InTotalTalkRate'] +=round($amount,2);
                    break;
                }
                else
                {
                    $start_date1 =   $end_date; 
                }
                $Inbnew_cycle_start = $start_date1;
                $Inbnew_cycle_end = $end_date;
            }
            $inbDurArr['Duration'] = $inb['Duration'];
            $inbDurArr['amount'] = $amount;
            $inbDurArr['unit'] = $unit;
            $inbData[$inbDurArr['call_date']][] = $inbDurArr;
        }
        //print_r($inbData); exit;
        foreach($inbData as $call_date=>$inb_arr)
        {
            //print_r(strtotime($Inbnew_cycle_start)); exit;
            //echo strtotime($call_date);
            $call_date = substr($call_date,0,10);
            /*echo strtotime($call_date);
            echo '<br/>';
            echo strtotime($Inbnew_cycle_start);
            echo '<br/>';
            echo strtotime($FromDate);
            echo '<br/>';
            print_r($inbData); exit;*/
            foreach($inb_arr as $inb)
            {
                if(strtotime($call_date)>=strtotime($Inbnew_cycle_start) && strtotime($call_date)>=strtotime($FromDate))
                {
                $html1 .="<tr>"; //exit;
                $html1 .="<td>".date('Y-m-d',strtotime($inb['call_date']))."</td>"; 
                $html1 .="<td>".date('H:i:s',strtotime($inb['call_date']))."</td>";
                $html1 .="<td>".$inb['phone_number']."</td>";
                $html1 .="<td>".$inb['Duration']."</td>";
                $html1 .="<td>".$inb['unit']."</td>";
                $html1 .="<td>".round($inb['amount'],2)."</td>";
                $html1 .="</tr>";
                
                $InTotalTalkTime += $inb['Duration'];
                $InTotalPulse += $inb['unit'];
                $InTotalTalkRate += $inb['amount'];
                
                if(strtotime(date('H:i:s',strtotime($inb['call_date'])))>=strtotime('20:00:00') 
                        || strtotime(date('H:i:s',strtotime($inb['call_date'])))<strtotime('08:00:00'))
                {
                    //$TinAmountNight = round($inb['amount'],2);
                    $inTotalSumaryUnitNight += $inb['unit'];
                }
                else
                {
                    //$TinAmount = round($inb['amount'],2);
                    $inTotalSumaryUnit += $inb['unit'];
                }
                
            }
            }
            
            
        }
        //echo $html1; exit;
        //print_r($data); exit;
        //$html .="<tr><td colspan='3' ><b>Total</b></td><td><b>{$InTotalPulse}</b></td><td><b>{$InTotalAmount}</b></td></tr>";
        $html1 .="<tr><td colspan='3' align=\"right\"><b>Total</b></td>";
        $html1 .="<td><b> {$InTotalTalkTime}</b></td>";
        $html1 .="<td><b> {$InTotalPulse}</b></td>";
        $html1 .="<td><b> {$InTotalTalkRate}</b></td>";
        $html1 .="</tr></table>";
    }

    //echo "get"; exit;
    //echo $html1; exit;
    
    
    //$html .="<br/><br/>";

  
    if(mysql_num_rows($OutboundDetails) > 0){
        $html2 .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (OUTBOUND)</h5>";
        $html2 .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html2 .="<tr>";
        $html2 .="<th>Date</th>";
        $html2 .="<th>Time</th>";
        $html2 .="<th>Call From</th>";
        $html2 .="<th>Talk Time</th>";
        $html2 .="<th>Pulse</th>";
        $html2 .="<th>Rate</th>";
        $html2 .="</tr>";

        $OutTotalPulse  =0;
        $OutTotalAmount =0;
        $OutTotalTalkTime =0;
        $OutTotalTalkRate =0;
        while($inb = mysql_fetch_assoc($OutboundDetails)){
           
            $callLength = $inb['length_in_sec'];
            $amount = 0; 
            $unit   = ceil($callLength/60);
            if($PlanDetails['OutboundCallMinute']=='Flat'){
                $unit = 1;
                $amount = $PlanDetails['OutboundCallCharge'];
            }
            else{
                $perMinute = 1*60;
                $unit = ceil($callLength/$perMinute);
                $amount = $unit*$PlanDetails['OutboundCallCharge'];
            }

            $start_date1 = $start_date;
            $call_date = strtotime(date('Y-m-d',strtotime($inb['call_date'])));
            foreach($period_arr as $end_date)
            {
                
                if($call_date>=strtotime($start_date1) && $call_date<strtotime($end_date))
                {
                    //$data[$start_date1][$end_date]['OutTotalTalkTime'] += $callLength;
                    //$data[$start_date1][$end_date]['OutTotalPulse'] += $unit;
                    $data[$end_date]['OutTotalTalkRate'] +=round($amount,2);
                    break;
                }
                else
                {
                    $start_date1 =   $end_date; 
                }
                $Outnew_cycle_start = $start_date1;
                $Outnew_cycle_end = $end_date;
            }
            
            $inb['amount'] = $amount;
            $inb['unit'] = $unit;
            $OutData[$inb['call_date']][] = $inb;
            
        }
        
        foreach($OutData as $call_date=>$inb_arr)
        {
            $call_date = substr($call_date,0,10);
            
            foreach($inb_arr as $inb)
            {
                if(strtotime($call_date)>=strtotime($Outnew_cycle_start) && strtotime($call_date)>=strtotime($FromDate))
                {
                    $html2 .="<tr>";
                    $html2 .="<td>".date('Y-m-d',strtotime($inb['call_date']))."</td>";
                    $html2 .="<td>".date('H:i:s',strtotime($inb['call_date']))."</td>";
                    $html2 .="<td>".$inb['phone_number']."</td>";
                    $html2 .="<td>".$inb['length_in_sec']."</td>";
                    $html2 .="<td>".$inb['unit']."</td>";
                    $html2 .="<td>".round($inb['amount'],2)."</td>";
                    $html2 .="</tr>";

                    $OutTotalTalkTime += $inb['length_in_sec'];
                    $OutTotalPulse += $inb['unit'];
                    $OutTotalTalkRate += $inb['amount'];

                    $TouAmount = round($inb['amount'],2);
                    $OutTotalSumaryUnit += $inb['unit'];
                
                }
            }
            
            
        }
        
        
        //$html .="<tr><td colspan='3' ><b>Total</b></td><td><b>{$OutTotalPulse}</b></td><td><b>{$OutTotalAmount}</b></td></tr>";
        $html2 .="<tr><td colspan='3' ><b>Total</b></td>";
        $html2 .="<td><b>{$OutTotalTalkTime}</b></td>";
        $html2 .="<td><b>{$OutTotalPulse}</b></td>";
        $html2 .="<td><b>{$OutTotalTalkRate}</b></td>";
        $html2 .="</tr></table>";
    }

    //$html .="<br/><br/>";
    
   if(mysql_num_rows($OutboundDetails_aband) > 0){
        
        $html2_ab ="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (ABAND CALLBACK)</h5>";
        $html2_ab .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html2_ab .="<tr>";
        $html2_ab .="<th>Date</th>";
        $html2_ab .="<th>Time</th>";
        $html2_ab .="<th>Call From</th>";
        
        $html2_ab .="<th>Talk Time</th>";
        $html2_ab .="<th>Pulse</th>";
        $html2_ab .="<th>Rate</th>";
        $html2_ab .="</tr>";
        
        while($inb_aband = mysql_fetch_assoc($OutboundDetails_aband)){
           //print_r($inb_aband);exit;
        
        
        $qry = "SELECT * FROM aband_call_master acm where clientid='$clientId' and PhoneNo='".$inb_aband['PhoneNumber']."' and date(Callbackdate)=date('".$inb_aband['CallDate']."') limit 1"; 
        //echo $qry;
        $rsc_comp = mysql_query($qry,$dd);
        $det = mysql_fetch_assoc($rsc_comp);
        //echo '<br/>';
       // print_r($det);
        if(!empty($det))
        {
            //echo $inb_aband['TalkSec'];
            $callLength = round($inb_aband['TalkSec']);
            //echo '<br/>';
            $amount = 0; 
            $convrt_pulse = $callLength/$ob_pulse_sec;
           // echo '<br/> line 646=>';
            if($ob_first_min=='1'){
                if($convrt_pulse>$ofmp)
                {
                 $subsequent = ceil($convrt_pulse-$ofmp); 
                  $total_pulse = $ofmp+$subsequent;
              //echo '<br/> line 652=> ';
                }
                else if(empty($callLength) || $callLength=='0')
                {
                     $total_pulse = 0;
                   //echo '<br/> line 655=> ';
                }
                else
                {
                  $total_pulse = $ofmp;
                 //echo '<br/> line 661=> ';
                }
                
                $amount = $ob_pulse_rate*$total_pulse;
            }
            else{
                 $total_pulse = ceil($callLength/$ob_pulse_sec);
             //echo '<br/> line 669=> ';
                $amount = $total_pulse*($ob_pulse_rate);
              //echo '<br/> line 671=> ';
            }

            $start_date1 = $start_date;
            $call_date = strtotime(date('Y-m-d',strtotime($inb_aband['CallDate']))); 
            
                
                if($call_date>=strtotime($start_date1) && $call_date<strtotime($end_date))
                {
                    $data[$end_date]['OutTotalTalkRate'] +=round($amount,2);
                    break;
                }
                else
                {
                    $start_date1 =   $end_date; 
                }
                $Outnew_cycle_start = $start_date1;
                $Outnew_cycle_end = $end_date;
            
            
            $inb_aband['amount'] = $amount;
            $inb_aband['unit'] = $total_pulse;
            if(!empty($total_pulse))
            {
                $OutData_aband[$inb_aband['CallDate']][] = $inb_aband;
            }
        }       
            
    }
        
      
        
    foreach($OutData_aband as $call_date=>$inb_arr)
    {
        $call_date = substr($call_date,0,10);

        foreach($inb_arr as $inb)
        {

                $html2_ab .="<tr>";
                $html2_ab .="<td>".date('Y-m-d',strtotime($inb['CallDate']))."</td>";
                $html2_ab .="<td>".date('H:i:s',strtotime($inb['CallDate']))."</td>";
                $html2_ab .="<td>".$inb['PhoneNumber']."</td>";
                
                $html2_ab .="<td>".$inb['TalkSec']."</td>";
                $html2_ab .="<td>".$inb['unit']."</td>";
                $html2_ab .="<td>".round($inb['amount'],2)."</td>";
                $html2_ab .="</tr>";

                $abOutTotalTalkTime += $inb['TalkSec'];
                $abOutTotalPulse += $inb['unit'];
                $abOutTotalTalkRate += round($inb['amount'],2);

                $abTouAmount = round($inb['amount'],2);
                $abOutTotalSumaryUnit += $inb['unit'];


        }


    }
        
        //print_r($html2 );exit;
        //$html .="<tr><td colspan='3' ><b>Total</b></td><td><b>{$OutTotalPulse}</b></td><td><b>{$OutTotalAmount}</b></td></tr>";
        $html2_ab .="<tr><td colspan='3' ><b>Total</b></td>";
        $html2_ab .="<td><b>{$abOutTotalTalkTime}</b></td>";
        $html2_ab .="<td><b>{$abOutTotalPulse}</b></td>";
        $html2_ab .="<td><b>{$abOutTotalTalkRate}</b></td>";
        $html2_ab .="</tr></table>";
    }

    if(mysql_num_rows($SMSDetails) > 0){
        $html3 .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (SMS)</h5>";
        $html3 .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html3 .="<tr>";
        $html3 .="<th>Date</th>";
        $html3 .="<th>Time</th>";
        $html3 .="<th>Call From</th>";
        $html3 .="<th>Pulse</th>";
        $html3 .="<th>Rate</th>";
        $html3 .="</tr>";
        $SMSTotal = 0;
        while($inb = mysql_fetch_assoc($SMSDetails)){
              
            $start_date1 = $start_date;
            $call_date = strtotime(date('Y-m-d',strtotime($inb['CallDate'])));
            foreach($period_arr as $end_date)
            {    
                if($call_date>=strtotime($start_date1) && $call_date<strtotime($end_date))
                {
                    $data[$end_date]['SmsCharge'] += round($inb['Unit']*$PlanDetails['SMSCharge'],2);
                    break;
                }
                else
                {
                    $start_date1 =   $end_date; 
                }
                $SMSnew_cycle_start = $start_date1;
                $SMSnew_cycle_end = $end_date;
            }
            $inb['amount'] = round($inb['Unit']*$PlanDetails['SMSCharge'],2);;
            $SmsData[$inb['CallDate']][] = $inb;
        }
        
        foreach($SmsData as $call_date=>$inb_arr)
        {
            $call_date = substr($call_date,0,10);
            foreach($inb_arr as $inb)
            {
                if(strtotime($call_date)>=strtotime($SMSnew_cycle_start) && strtotime($call_date)>=strtotime($FromDate))
                {
                    $html3 .="<tr>";
                    $html3 .="<td>".$inb['CallDate1']."</td>";
                    $html3 .="<td>".$inb['CallTime']."</td>";
                    $html3 .="<td>".$inb['CallFrom']."</td>";
                    $html3 .="<td>".$inb['Unit']."</td>";
                    $html3 .="<td>".round($inb['Unit']*$PlanDetails['SMSCharge'],2)."</td>";
                    $html3 .="</tr>";

                    $SMSTotal += $inb['Unit']; 
                    $SMS['Unit'] += $inb['Unit']; 
                }
            }
            
        }
        
        
        $html3 .="<tr><td colspan='5' ><b>Total Vol {$SMSTotal}</b></td></tr>";
        $html3 .="</table>";
    }
    
    

    //$html .="<br/><br/>";

    if(mysql_num_rows($EmailDetails) > 0){
        $html4 .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (EMAIL)</h5>";
        $html4 .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html4 .="<tr>";
        $html4 .="<th>Date</th>";
        $html4 .="<th>Time</th>";
        $html4 .="<th>Call From</th>";
        $html4 .="<th>Pulse</th>";
        $html4 .="<th>Rate</th>";
        $html4 .="</tr>";
        $EmailTotal = 0;
        while($inb = mysql_fetch_assoc($EmailDetails)){
            
            $start_date1 = $start_date;
            $call_date = strtotime(date('Y-m-d',strtotime($inb['CallDate'])));
            foreach($period_arr as $end_date)
            {    
                if($call_date>=strtotime($start_date1) && $call_date<strtotime($end_date))
                {
                    $data[$end_date]['EmailCharge'] += round($inb['Unit']*$PlanDetails['EmailCharge'],2);
                    break;
                }
                else
                {
                    $start_date1 =   $end_date; 
                }
                $Emailnew_cycle_start = $start_date1;
                $Emailnew_cycle_end = $end_date;
            }
            $inb['amount'] = round($inb['Unit']*$PlanDetails['EmailCharge'],2);;
            $EmailData[$inb['CallDate']][] = $inb;
            
        }
        foreach($EmailData as $call_date=>$inb_arr)
        {
            $call_date = substr($call_date,0,10);
            foreach($inb_arr as $inb)
            {
                if(strtotime($call_date)>=strtotime($Emailnew_cycle_start) && strtotime($call_date)>=strtotime($FromDate))
                {
                    $html4 .="<tr>";
                $html4 .="<td>".$inb['CallDate1']."</td>";
                $html4 .="<td>".$inb['CallTime']."</td>";
                $html4 .="<td>".$inb['CallFrom']."</td>";
                $html4 .="<td>".$inb['Unit']."</td>";
                $html4 .="<td>".round($inb['Unit']*$PlanDetails['EmailCharge'],2)."</td>";
                $html4 .="</tr>";
                $EmailTotal += $inb['Unit'];
                $Email['Unit'] += $inb['Unit'];
                }
            }    
        }
        $html4 .="<tr><td colspan='5' ><b>Total Vol {$EmailTotal}</b></td></tr>";
        $html4 .="</table>";
    }

    //$html .="<br/><br/>";



    if(mysql_num_rows($VFODetails) > 0){
        $html5 .="<h5 style='font-size:11pt;' >{$ClientInfo['company_name']} (VFO)</h5>";
        $html5 .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
        $html5 .="<tr>";
        $html5 .="<th>Date</th>";
        $html5 .="<th>Time</th>";
        $html5 .="<th>Call From</th>";
        $html5 .="<th>Pulse</th>";
        $html5 .="<th>Rate</th>";
        $html5 .="</tr>";
        $VFOTotal = 0;
        while($inb = mysql_fetch_assoc($VFODetails)){
            
            
            $start_date1 = $start_date;
            $call_date = strtotime(date('Y-m-d',strtotime($inb['CallDate'])));
            foreach($period_arr as $end_date)
            {    
                if($call_date>=strtotime($start_date1) && $call_date<strtotime($end_date))
                {
                    $data[$end_date]['VFOCallCharge'] += round($inb['Unit']*$PlanDetails['VFOCallCharge'],2);
                    break;
                }
                else
                {
                    $start_date1 =   $end_date; 
                }
                $Vfonew_cycle_start = $start_date1;
                $Vfonew_cycle_end = $end_date;
            }
            $inb['amount'] = round($inb['Unit']*$PlanDetails['VFOCallCharge'],2);;
            $VfoData[$inb['CallDate']][] = $inb;
            
        }
        
        foreach($VfoData as $call_date=>$inb_arr)
        {
            $call_date = substr($call_date,0,10);
            foreach($inb_arr as $inb)
            {
                if(strtotime($call_date)>=strtotime($Vfonew_cycle_start) && strtotime($call_date)>=strtotime($FromDate))
                {
                    $html5 .="<tr>";
                    $html5 .="<td>".$inb['CallDate1']."</td>";
                    $html5 .="<td>".$inb['CallTime']."</td>";
                    $html5 .="<td>".$inb['CallFrom']."</td>";
                    $html5 .="<td>".$inb['Unit']."</td>";
                    $html5 .="<td>".round($inb['Unit']*$PlanDetails['VFOCallCharge'],2)."</td>";
                    $html5 .="</tr>";
                    $VFOTotal += $inb['Unit'];
                    $VFO['Unit'] += $inb['Unit'];
                }
            }    
        }
        
        
    
        $html5 .="<tr><td colspan='5' ><b>Total Vol {$VFOTotal}</b></td></tr>";
        $html5 .="</table>";
    }
    
    $bal_carray = 0; $pending_bal = 0;
    foreach($period_arr as $key=>$end_date)
    {
        $mont_aval = $balance_arr[$key];
       //echo ' - ';
        $month_used = round($data[$end_date]['InTotalAmount'],2) +
                round($data[$end_date]['OutTotalTalkRate'],2)+
                round($data[$end_date]['SmsCharge'],2)+
                round($data[$end_date]['EmailCharge'],2)+
                round($data[$end_date]['VFOCallCharge'],2);
        
        $mont_bal =  round($mont_aval - $month_used,2);
        
        if($mont_bal<0)
        {
            $pending_bal += $mont_bal;
        }
        
        if(strtolower($PlanDetails['TransferAfterRental'])=='yes')
        {
            if($mont_bal>=0)
            {
              $bal_carray +=$mont_bal;  
            }
        }
        echo '<br/>';
    }
    
    
    //print_r($bal_carray); exit;
    
    if($inTotalSumaryUnit !="") {$TinAmount=round($inTotalSumaryUnit*$PlanDetails['InboundCallCharge'],2);}
    if($inTotalSumaryUnitNight !="") { $TinAmountNight=round($inTotalSumaryUnitNight*$PlanDetails['InboundCallChargeNight'],2);}
    if($OutTotalSumaryUnit !="") {$TouAmount=round($OutTotalSumaryUnit*$PlanDetails['OutboundCallCharge'],2);}
    if($abOutTotalSumaryUnit !="") {$abTouAmount=round($abOutTotalTalkRate,2);}
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
    
    $TotUseBalance=(round($inTotalSumaryUnit*$PlanDetails['InboundCallCharge'],2)
            +round($inTotalSumaryUnitNight*$PlanDetails['InboundCallChargeNight'],2)
            +round($OutTotalSumaryUnit*$PlanDetails['OutboundCallCharge'],2)
            +round($VFO['Unit']*$PlanDetails['VFOCallCharge'],2)
            +round($SMS['Unit']*$PlanDetails['SMSCharge'],2)
            +round($Email['Unit']*$PlanDetails['EmailCharge'],2));

    
    $html .='<table border="1" width="600" cellpadding="2" cellspacing="2" style="font-size:11pt;" >';
    $html .="<tr><td colspan='8' style='font-size:15pt;background-color:#607d8b;color:#fff;font-weight:bold;'>Plan Details</td></tr>";
    
    $html .="<tr>";
    $html .="<th>Plan Name</th>";
    $html .="<th>Start Date</th>";
    $html .="<th>End Date</th>";
    $html .="<th>Balance</th>";
    $html .="<th>Validity</th>";
    $html .="<th>".$PlanDetails['PeriodType']. " Balance</th>";
    $html .="<th>Available</th>";
    $html .="<th>Used</th>";
    $html .="</tr>";
    $html .="<tr>";
    $html .="<td>{$PlanDetails['PlanName']}</td>";
    $html .="<td>{$BalanceMaster['start_date']}</td>";
    $html .="<td>{$BalanceMaster['end_date']}</td>";
    $html .="<td>".round($pending_bal+$bal_carray)."</td>";
    $html .="<td>".$PlanDetails['RentalPeriod'].' '.$PlanDetails['PeriodType']."</td>";
    $html .="<td>".$package_bal."</td>";
    $used = ($TinAmount+$TinAmountNight+$TouAmount+$TvfAmount+$TsmAmount+$TemAmount);
    //if(intval($BalanceMaster['Used']) >= intval($BalanceMaster['MainBalance'])){
        //$html .="<td>0</td>";
    //}
    //else{
        $html .="<td>".($package_bal-$used)."</td>";
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
        $html .="<td>{$PlanDetails['InboundCallCharge']}  Rs./ 30 Sec</td>";
        $html .="<td colspan='2'>".round($TinAmount,2)."</td>";
        $html .="</tr>";
    }
    if($inTotalSumaryUnitNight !="") {
       
        $html .="<tr>";
        $html .="<td>ICB Night</td>";
        $html .="<td>{$inTotalSumaryUnitNight}</td>";
        $html .="<td>{$PlanDetails['InboundCallChargeNight']}  Rs./ 30 Sec</td>";
        $html .="<td colspan='2'>".round($TinAmountNight,2)."</td>";
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
    
    if($abOutTotalSumaryUnit !="") {
        
        $html .="<tr>";
        $html .="<td>ABCB</td>";
        $html .="<td>{$abOutTotalSumaryUnit}</td>";
        $html .="<td>".round($ob_pulse_rate,6)."  Rs./ {$ob_pulse_sec} Min</td>";
        $html .="<td colspan='2'>".round($abOutTotalTalkRate,2)."</td>";
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
    $html .="<td colspan='2'>".($TinAmount+$TinAmountNight+$TouAmount+$abTouAmount+$TvfAmount+$TsmAmount+$TemAmount)."</td>";
    $html .="</tr>";

    $html .="</table>";

    //$html .="<br/>";

    
}



$fileName = "statement_".date('d_m_y_h_i_s');
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$fileName.".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo $html.$html1.$html2.$html2_ab.$html3.$html4.$html5 ;die;

