<?php


/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

//$last_date = date('Y-m-d',strtotime("-1 days"));
exit;
$last_date = "2022-01-06";  
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');

$host       =   "localhost"; 
$username   =   "root";
$password   =   "dial@mas123";
$db_name    =   "db_dialdesk";





$vd = mysqli_connect("192.168.10.5","root","vicidialnow",'asterisk') ;
if (mysqli_connect_errno($vd)) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

$dd = mysqli_connect("$host", "$username", "$password","$db_name"); 
if (mysqli_connect_errno($dd)) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}


$selec_all_clients_qry = "SELECT * FROM `registration_master` WHERE  `status`='A' "; 
$RegistrationMaster = mysqli_query($dd,$selec_all_clients_qry) ;

while($ClientInfo = mysqli_fetch_assoc($RegistrationMaster))
{
    $clientId = $ClientInfo['company_id']; 
    $bal_qry = "select * from `balance_master` where clientId='$clientId'  limit 1";
    $BalanceMasterRsc = mysqli_query($dd,$bal_qry);
    $BalanceMaster = mysqli_fetch_assoc($BalanceMasterRsc);
    
    //print_r($BalanceMaster);exit;
    
    $cm_date = $last_date;
    $cm_total = 0;
    $ib_pulse = 0;      $ib_secs=0;         $ib_charge = 0;     $ib_flat = 0;       $ib_total = 0;      $ib_pulse_per_call = 60;
    $ibn_pulse = 0;     $ibn_secs=0;         $ibn_charge = 0;    $ibn_flat = 0;      $ibn_total = 0;     $ibn_pulse_per_call = 60;
    $ob_pulse = 0;      $ob_secs=0;         $ob_charge = 0;     $ob_flat = 0;       $ob_total = 0;      $ob_pulse_per_call = 60;
    $ivr_pulse = 0;     $ivr_secs=0;         $ivr_charge = 0;    $ivr_flat = 0;      $ivr_total = 0;     //$ivr_pulse_per_call = 60;
    $miss_pulse = 0;    $miss_secs=0;         $miss_charge = 0;   $miss_flat = 0;     $miss_total = 0;    //$miss_pulse_per_call = 60;
    $vfo_pulse = 0;     $vfo_secs=0;         $vfo_charge = 0;    $vfo_flat = 0;      $vfo_total = 0;     //$vfo_pulse_per_call = 60;
    $sms_pulse = 0;     $sms_secs=0;         $sms_charge = 0;    $sms_flat = 0;      $sms_total = 0;     $sms_pulse_per_call = 60;
    $email_pulse = 0;   $email_secs=0;         $email_charge = 0;  $email_flat = 0;    $email_total = 0;   //$email_pulse_per_call = 60;
    
    
    $TinAmount=0;
    $TouAmount=0;
    $TvfAmount=0;
    $TsmAmount=0;
    $TemAmount=0;
    $period_arr = array();
    $balance_arr = array();
    $package_bal = array();

if($BalanceMaster['PlanId'] !=""){
    
    $Campagn=$ClientInfo['campaignid']; 
    $PlanId = $BalanceMaster['PlanId'];
    $plan_det_qry = "select * from `plan_master` where Id='$PlanId' limit 1";
    $PlanDetailsRsc = mysqli_query($dd,$plan_det_qry);
    $PlanDetails = mysqli_fetch_assoc($PlanDetailsRsc);
    $start_date = $BalanceMaster['start_date']; 
    $end_date = $BalanceMaster['end_date'];
    $balance = $BalanceMaster['MainBalance'];
    $PeriodType = strtolower($PlanDetails['PeriodType']);
    $bill_month = "";
    
    $ib_charge = $PlanDetails['InboundCallCharge'];
    if($PlanDetails['IB_Call_Charge']=='Minute')
    {
        $ib_flat = 0;
    }
    else if($PlanDetails['IB_Call_Charge']=='Second')
    {
        $ib_flat = 1;
    }
    else if($PlanDetails['IB_Call_Charge']=='Minute/Second')
    {
        $ib_flat = 2;
    }
    
    $ibn_charge = $PlanDetails['InboundCallChargeNight'];    
    if($PlanDetails['IB_Call_Charge']=='Minute')
    {
        $ibn_flat = 0;
    }
    else if($PlanDetails['IB_Call_Charge']=='Second')
    {
        $ibn_flat = 1;
    }
    else if($PlanDetails['IB_Call_Charge']=='Minute/Second')
    {
        $ibn_flat = 2;
    }
    
    $ob_charge = $PlanDetails['OutboundCallCharge'];
    if($PlanDetails['OutboundCallChargeType']=='Minute')
    {
        $ob_flat = 0;
    }
    else if($PlanDetails['OutboundCallChargeType']=='Second')
    {
        $ob_flat = 1;
    }
    else if($PlanDetails['OutboundCallChargeType']=='Minute/Second')
    {
        $ob_flat = 2;
    }
    
    $ivr_charge = $PlanDetails['IVR_Charge'];    
    $ivr_flat = 0;
    $miss_charge = $PlanDetails['MissCallCharge'];   
    $miss_flat = 0;
    $vfo_charge = $PlanDetails['VFOCallCharge'];    
    $vfo_flat = 0;
    $sms_charge = $PlanDetails['SMSCharge'];    
    $sms_flat = 0;
    $email_charge = $PlanDetails['EmailCharge'];  
    $email_flat = 0;
       
//    // Inbound Call duration details
//    $InboundQry = "select length_in_sec,queue_seconds,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_closer_log` where user !='VDCL' and campaign_id in ($Campagn) AND DATE(call_date) = '$last_date'  ";
//    $InboundDetails=mysqli_query($vd,$InboundQry);
//    
//    $OutboundQry = "select length_in_sec,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_log` where user !='VDAD' and campaign_id in ($Campagn) AND DATE(call_date) = '$last_date'  ";
//    $OutboundDetails=mysqli_query($vd,$OutboundQry);
//
//    $IvrQry = "SELECT DATE_FORMAT(call_time,'%d %b %y') `CallDate1`,call_time CallDate,source_number CallFrom,uniqueid FROM `rx_log` WHERE clientId='$clientId'  AND date(call_time) = '$last_date'";
//    $IvrDetails = mysqli_query($dd,$IvrQry);

    
    $SMSQuery = "SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='SMS' AND date(CallDate) = '$last_date'  ";
    $SMSDetails = mysqli_query($dd,$SMSQuery);
    
//    $EmailQry = "SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='Email' AND date(CallDate) = '$last_date'";
//    $EmailDetails = mysqli_query($dd,$EmailQry);
//    
//    $MissQry = "SELECT DATE_FORMAT(call_time,'%d %b %y') `CallDate1`,call_time CallDate,source_number CallFrom FROM `billing_master` WHERE clientId='$clientId'  AND date(call_time) = '$last_date'";
//    $MissDetails = mysqli_query($dd,$MissQry);
//    
//    $VFOQry = "SELECT DATE_FORMAT(calltime,'%d %b %y') `CallDate1`,calltime CallDate,source_number, uniqueid FROM sbarro_data WHERE clientId='$clientId'  AND DATE(calltime) = '$last_date'";
//    $VfoDetails = mysql_query($vd,$VFOQry);
    
   
//    while($inbDurArr = mysqli_fetch_assoc($InboundDetails))
//    {
//        $call_duration = $inbDurArr['length_in_sec']-$inbDurArr['queue_seconds'];
//        $call_pulse = 0;
//        $call_rate = 0;
//        $call_pulsesec = 0;
//        
//        if(strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))>=strtotime('20:00:00') 
//                        || strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))<=strtotime('08:00:00'))
//        {
//            if($ibn_flat==0)
//            {
//                $call_pulse = ceil($call_duration/$ibn_pulse_per_call);
//                $call_rate = $call_pulse*$ibn_charge;
//            }
//            else if($ibn_flat==1)
//            {
//                $call_pulsesec = $call_duration;
//                $call_rate = $call_pulsesec*$ibn_charge;
//            }
//            else
//            {
//                $call_pulse = 1;
//                $call_rate = $ibn_charge;
//                if($call_duration>$ibn_pulse_per_call)
//                {
//                    $call_duration=($call_duration-$ibn_pulse_per_call);
//                    $call_rate += ($call_duration*($ibn_charge/$ibn_pulse_per_call));
//                    $call_pulsesec = $call_duration;
//                }
//            }
//
//            $ibn_pulse += $call_pulse;
//            $ibn_secs += $call_pulsesec;
//            $ibn_total += $call_rate;
//        }
//        else
//        {
//            if($ib_flat==0)
//            {
//                $call_pulse = ceil($call_duration/$ib_pulse_per_call);
//                $call_rate = $call_pulse*$ib_charge;
//            }
//            else if($ib_flat==1)
//            {
//                $call_pulsesec = $call_duration;
//                $call_rate = $call_pulsesec*$ib_charge;
//            }
//            else
//            {
//                $call_pulse = 1;
//                $call_rate = $ib_charge;
//                if($call_duration>$ib_pulse_per_call)
//                {
//                    $call_duration=($call_duration-$ib_pulse_per_call);
//                    $call_rate += ($call_duration*($ib_charge/$ib_pulse_per_call));
//                    $call_pulsesec = $call_duration;
//                }
//            }
//
//            $ib_pulse += $call_pulse;
//            $ib_secs += $call_pulsesec;
//            $ib_total += $call_rate;
//        }
//        
//    }
    
//    while($outDurArr = mysqli_fetch_assoc($OutboundDetails))
//    {
//        $call_duration = $outDurArr['length_in_sec'];
//        $call_pulse = 0;
//        $call_rate = 0;
//        $call_pulsesec = 0;
//        
//        if($ob_flat==0)
//        {
//            $call_pulse = ceil($call_duration/$ob_pulse_per_call);
//            $call_rate = $call_pulse*$ob_charge;
//        }
//        else if($ob_flat==1)
//        {
//            $call_pulsesec = $call_duration;
//            $call_rate = $call_pulsesec*$ob_charge;
//        }
//        else
//        {
//            $call_pulse = 1;
//            $call_rate = $ob_charge;
//            if($call_duration>$ob_pulse_per_call)
//            {
//                $call_duration=($call_duration-$ob_pulse_per_call);
//                $call_rate += ($call_duration*($ob_charge/$ob_pulse_per_call));
//                $call_pulsesec = $call_duration;
//            }
//        }
//
//        $ob_pulse += $call_pulse;
//        $ob_secs += $call_pulsesec;
//        $ob_total += $call_rate;
//    }
    
//    $ivr_uniqueid_arr = array();
//    
//    while($ivrArr = mysqli_fetch_assoc($IvrDetails))
//    {
//        $uniqueid = $ivrArr['uniqueid'];
//        $ivr_uniqueid_arr[$uniqueid] = $uniqueid;
//    }
//    $ivr_unit = count($ivr_uniqueid_arr); 
//    $ivr_rate = $ivr_unit*$ivr_charge;
//    $ivr_total = $ivr_rate;
//    $ivr_pulse = $ivr_unit;
    
    while($smsArr = mysqli_fetch_assoc($SMSDetails))
    {
        $smsChar = $smsArr['Duration'];
        
        $sms_unit =$smsArr['Unit'];;
        $sms_rate = 0;
        $sms_pulsesec = 0;
        
//        if($sms_flat==0)
//        {
//            $sms_unit = ceil($smsChar/60);
//            $sms_rate = ceil($sms_unit*$sms_charge);
//        }
//        else if($sms_flat==1)
//        {
//            $sms_pulsesec = $smsChar;
//            $sms_rate = $sms_pulsesec*$sms_charge;
//        }
//        else
//        {
//            $sms_unit = 1;
//            $sms_rate = $sms_charge;
//            if($smsChar>60)
//            {
//                $smsChar=($smsChar-60);
//                $sms_rate += ($smsChar*($sms_charge/60));
//                $sms_pulsesec = $smsChar;
//            }
//        }

        $sms_pulse += $sms_unit;
        $sms_secs += $smsChar;
        $sms_total += $sms_charge*$sms_unit;
    }
    
//    $EmailUnit = 0;
//    while($emailArr = mysqli_fetch_assoc($EmailDetails))
//    {
//        $EmailUnit = $emailArr['Unit'];
//        $email_rate = $EmailUnit*$email_charge;
//        $email_pulse    += $EmailUnit;
//        $email_total      += $email_rate;
//    }
//    
//    while($misArr = mysqli_fetch_assoc($MissDetails))
//    {
//        $MissUnit = $misArr['Unit'];
//        $email_rate = ceil($MissUnit*$miss_charge);
//        $miss_pulse    += $MissUnit;
//        $miss_total      += $email_rate;
//    }
    
        $sel_cm_total = "SELECT * FROM billing_consume_daily WHERE client_id='$clientId' and cm_date='$last_date' LIMIT 1";
        $cmTotalRsc = mysqli_query($dd,$sel_cm_total);
        $cmTotalDet = mysqli_fetch_assoc($cmTotalRsc);
        $old_cm_total = $cmTotalDet['cm_total'];

    $cm_total =(float)$old_cm_total+ round($sms_total,3);

    $insert_qry = "update `billing_consume_daily` SET cm_total='$cm_total',";
    //$insert_qry .= "ib_pulse='$ib_pulse',";         $insert_qry .= "ib_secs='$ib_secs',";       $insert_qry .= "ib_charge='$ib_charge',";       $insert_qry .= "ib_flat='$ib_flat',";       $insert_qry .= "ib_total='$ib_total',";
    //$insert_qry .= "ibn_pulse='$ibn_pulse',";       $insert_qry .= "ibn_secs='$ibn_secs',";     $insert_qry .= "ibn_charge='$ibn_charge',";     $insert_qry .= "ibn_flat='$ibn_flat',";     $insert_qry .= "ibn_total='$ibn_total',";
    //$insert_qry .= "ob_pulse='$ob_pulse',";         $insert_qry .= "ob_secs='$ob_secs',";       $insert_qry .= "ob_charge='$ob_charge',";       $insert_qry .= "ob_flat='$ob_flat',";       $insert_qry .= "ob_total='$ob_total',";
    //$insert_qry .= "ivr_pulse='$ivr_pulse',";       $insert_qry .= "ivr_charge='$ivr_total',"; $insert_qry .= "ivr_flat='$ivr_flat',";         $insert_qry .= "ivr_total='$ivr_total',";
    //$insert_qry .= "miss_pulse='$miss_pulse',";     $insert_qry .= "miss_charge='$miss_total',";$insert_qry .= "miss_flat='$miss_flat',";      $insert_qry .= "miss_total='$miss_total',";
    //$insert_qry .= "vfo_pulse='$vfo_pulse',";       $insert_qry .= "vfo_charge='$vfo_total',"; $insert_qry .= "vfo_flat='$vfo_flat',";         $insert_qry .= "vfo_total='$vfo_total',";   
    $insert_qry .= "sms_pulse='$sms_pulse',";       $insert_qry .= "sms_charge='$sms_charge',";$insert_qry .= "sms_flat='$sms_flat',";          $insert_qry .= "sms_total='$sms_total' where client_id='$clientId' and cm_date='$last_date' limit 1";       
    //$insert_qry .= "email_pulse='$email_pulse',";   $insert_qry .= "email_charge='$email_charge',"; $insert_qry .= "email_flat='$email_flat',"; $insert_qry .= "email_total='$email_total',created_at=now()"; 
    
    //echo $insert_qry; exit; 
    mysqli_begin_transaction($dd, MYSQLI_TRANS_START_READ_ONLY);
    $insRsc = mysqli_query($dd,$insert_qry);
    
    if($insRsc)
    {
        $sel_consume = "SELECT * FROM `billing_opening_balance` WHERE clientId='$clientId' AND DATE('$last_date') BETWEEN bill_start_date AND bill_end_date LIMIT 1";
        $consumeRsc = mysqli_query($dd,$sel_consume);
        $consumeDet = mysqli_fetch_assoc($consumeRsc);
        $old_consume = $consumeDet['cs_bal'];
        $consume_till_date = round($old_consume + round($sms_total,3),2);
        $op_id = $consumeDet['op_id'];
        $upd_consume = "update billing_opening_balance set cs_bal='$consume_till_date' where clientId='$clientId' and op_id='$op_id' limit 1";
        $NewconsumeRsc = mysqli_query($dd,$upd_consume);
        if($NewconsumeRsc)
        {
            mysqli_commit($dd);
        }
        else
        {
            mysqli_rollback($dd);
        }
    }
    else
    {
         mysqli_rollback($dd);
    }
}

}



