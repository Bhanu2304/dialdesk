<?php


/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

//$last_date = "2022-10-28";
 $last_date = $_GET['last_date'];
$todays_day_is_one = date('d',strtotime($last_date." -1 days"));
$end_date_of_month = date('Y-m-t',strtotime($last_date." -1 days"));
$fin_year = date('Y',strtotime($last_date." -1 days"));
$fin_month = date('M',strtotime($last_date." -1 days"));


//$last_date = "2022-03-30";  
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');

$host       =   "localhost"; 
$username   =   "root";
$password   =   "dial@mas123";
$db_name    =   "db_dialdesk";



//exit;

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


 $selec_all_clients_qry = "SELECT * FROM `registration_master` WHERE company_id='470' and `status`='A' "; 
$RegistrationMaster = mysqli_query($dd,$selec_all_clients_qry) ;


    
while($ClientInfo = mysqli_fetch_assoc($RegistrationMaster))
{
    $clientId = $ClientInfo['company_id']; 
    $bal_qry = "select * from `balance_master` where clientId='$clientId' and DATE(start_date) <=CURDATE()  limit 1";
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
    
    $ib_pulse_sec = $PlanDetails['pulse_day_shift'];
    $ib_pulse_rate = $PlanDetails['rate_per_pulse_day_shift'];
    
    $ibn_pulse_sec = $PlanDetails['pulse_night_shift'];
    $ibn_pulse_rate = $PlanDetails['rate_per_pulse_night_shift'];
    
    
    $ob_pulse_sec = $PlanDetails['pulse_outbound_call_shift'];
    $ob_pulse_rate = $PlanDetails['rate_per_pulse_outbound_call_shift'];
    $ifmp = ceil(60/$ib_pulse_sec);
    $ofmp = ceil(60/$ob_pulse_sec); 
    
    $bill_month = "";
    
    $ib_charge = $PlanDetails['InboundCallCharge'];
    $ibn_charge = $PlanDetails['InboundCallChargeNight'];    
    $ob_charge = $PlanDetails['OutboundCallCharge'];
    
    
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

    if($PlanDetails['first_minute']=='Enable')
    {
        //$ib_first_min = $PlanDetails['ib_first_min'];
        $ib_first_min='1';
        $ob_first_min='1';
    }
    else
    {
        $ib_first_min='0';
        $ob_first_min='0';
    }


      
    
    // Inbound Call duration details
    //$InboundQry = "select length_in_sec,queue_seconds,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_closer_log` where user !='VDCL' and campaign_id in ($Campagn) AND DATE(call_date) = '$last_date'  ";
    
    $InboundQry = "select if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`) length_in_sec, phone_number,call_date from `vicidial_closer_log` t2 left join vicidial_agent_log t3 on t2.uniqueid=t3.uniqueid where t2.user !='VDCL' and t2.campaign_id in ($Campagn) AND DATE(call_date) = '$last_date'";
    $InboundDetails=mysqli_query($vd,$InboundQry);
    
    $OutboundQry = "select length_in_sec,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_log` where length_in_sec!='0' and user !='VDAD' and campaign_id in ($Campagn) AND DATE(call_date) = '$last_date'  ";
    $OutboundDetails=mysqli_query($vd,$OutboundQry);

    $IvrQry = "SELECT DATE_FORMAT(call_time,'%d %b %y') `CallDate1`,call_time CallDate,source_number CallFrom,uniqueid FROM `rx_log` WHERE clientId='$clientId'  AND date(call_time) = '$last_date'";
    $IvrDetails = mysqli_query($dd,$IvrQry);

    
    $SMSQuery = "SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='SMS' AND date(CallDate) = '$last_date'  ";
    $SMSDetails = mysqli_query($dd,$SMSQuery);
    
    $EmailQry = "SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='Email' AND date(CallDate) = '$last_date'";
    $EmailDetails = mysqli_query($dd,$EmailQry);
    
    $MissQry = "SELECT DATE_FORMAT(call_time,'%d %b %y') `CallDate1`,call_time CallDate,source_number CallFrom FROM `billing_master` WHERE clientId='$clientId'  AND date(call_time) = '$last_date'";
    $MissDetails = mysqli_query($dd,$MissQry);
    
    $VFOQry = "SELECT DATE_FORMAT(calltime,'%d %b %y') `CallDate1`,calltime CallDate,source_number, uniqueid FROM sbarro_data WHERE clientId='$clientId'  AND DATE(calltime) = '$last_date'";
    $VfoDetails = mysql_query($vd,$VFOQry);
    
   
    while($inbDurArr = mysqli_fetch_assoc($InboundDetails))
    {
        $call_duration = $inbDurArr['length_in_sec'];
        $call_pulse = 0;
        $call_rate = 0;
        $call_pulsesec = 0;
        
        
        if(strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))>=strtotime('20:00:00') 
                        || strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))<=strtotime('08:00:00'))
        {
            $convrt_pulse = $call_duration/$ibn_pulse_sec;
            if($ib_first_min=='1'){
                
                if($convrt_pulse>$ifmp)
                {
                    $subsequent = ($convrt_pulse-$ifmp);  
                    $call_pulse = $ifmp+ceil($subsequent);
                }
                else
                {
                    $call_pulse = $ifmp;
                }
                $call_rate = $ibn_pulse_rate*$call_pulse; 
            }
            else
            {
                $call_pulse = ceil($call_duration/$ib_pulse_sec);
                $call_rate = $call_pulse*($ibn_pulse_rate); 
            }
            
            

            $ibn_pulse += $call_pulse;
            $ibn_secs += $call_pulsesec;
            $ibn_total += $call_rate;
        }
        else
        {
            $convrt_pulse = $call_duration/$ib_pulse_sec;
            //echo 'adf';exit;
            //echo $ib_first_min;exit;
            if($ib_first_min=='1'){
                if($convrt_pulse>$ifmp)
                {
                    $subsequent = ($convrt_pulse-$ifmp);  
                    $call_pulse = $ifmp+ceil($subsequent);
                }
                else
                {
                    $call_pulse = $ifmp; 
                }
                $call_rate = $ib_pulse_rate*$call_pulse;
            }
            else
            {
                $call_pulse = ceil($call_duration/$ib_pulse_sec); 
                $call_rate = $call_pulse*($ib_pulse_rate); 
            }

            $ib_pulse += $call_pulse; 
            $ib_secs += $call_pulsesec;
            $ib_total += $call_rate;
        }
        
    }
    
    while($outDurArr = mysqli_fetch_assoc($OutboundDetails))
    {
        $callLength = round($outDurArr['length_in_sec']);   
        $call_rate = 0;
        $convrt_pulse = $callLength/$ob_pulse_sec; 
        if($ob_first_min=='1'){
                if($convrt_pulse>$ofmp)
                {
                 $subsequent = ceil($convrt_pulse-$ofmp); 
                 $total_pulse = $ofmp+$subsequent;
                }
                else
                {
                 $total_pulse = $ofmp; 
                }
                $call_rate = $ob_pulse_rate*$total_pulse;
            }
        else{
                $total_pulse = ceil($callLength/$ob_pulse_sec);
                $call_rate = $total_pulse*($ob_pulse_rate);
            }

        $ob_pulse += $total_pulse;
        $ob_secs += $call_pulsesec;
        $ob_total += $call_rate;
    }
    
    $ivr_uniqueid_arr = array();
    
    while($ivrArr = mysqli_fetch_assoc($IvrDetails))
    {
        $uniqueid = $ivrArr['uniqueid'];
        $ivr_uniqueid_arr[$uniqueid] = $uniqueid;
    }
    $ivr_unit = count($ivr_uniqueid_arr); 
    $ivr_rate = $ivr_unit*$ivr_charge;
    $ivr_total = $ivr_rate;
    $ivr_pulse = $ivr_unit;
    
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
    
    $EmailUnit = 0;
    while($emailArr = mysqli_fetch_assoc($EmailDetails))
    {
        $EmailUnit = $emailArr['Unit'];
        $email_rate = $EmailUnit*$email_charge;
        $email_pulse    += $EmailUnit;
        $email_total      += $email_rate;
    }
    
    while($misArr = mysqli_fetch_assoc($MissDetails))
    {
        $MissUnit = $misArr['Unit'];
        $email_rate = ceil($MissUnit*$miss_charge);
        $miss_pulse    += $MissUnit;
        $miss_total      += $email_rate;
    }
    
    $cm_total = round($ib_total+$ibn_total+$ob_total+$ivr_total+$miss_total+$vfo_total+$sms_total+$email_total,3);

    $insert_qry = "INSERT INTO `billing_consume_daily` SET client_id='$clientId',cm_date='$last_date',cm_total='$cm_total',";
    $insert_qry .= "ib_pulse='$ib_pulse',";         $insert_qry .= "ib_secs='$ib_secs',";       $insert_qry .= "ib_charge='$ib_charge',";       $insert_qry .= "ib_flat='$ib_flat',";       $insert_qry .= "ib_total='$ib_total',";
    $insert_qry .= "ibn_pulse='$ibn_pulse',";       $insert_qry .= "ibn_secs='$ibn_secs',";     $insert_qry .= "ibn_charge='$ibn_charge',";     $insert_qry .= "ibn_flat='$ibn_flat',";     $insert_qry .= "ibn_total='$ibn_total',";
    $insert_qry .= "ob_pulse='$ob_pulse',";         $insert_qry .= "ob_secs='$ob_secs',";       $insert_qry .= "ob_charge='$ob_charge',";       $insert_qry .= "ob_flat='$ob_flat',";       $insert_qry .= "ob_total='$ob_total',";
    $insert_qry .= "ivr_pulse='$ivr_pulse',";       $insert_qry .= "ivr_charge='$ivr_total',"; $insert_qry .= "ivr_flat='$ivr_flat',";         $insert_qry .= "ivr_total='$ivr_total',";
    $insert_qry .= "miss_pulse='$miss_pulse',";     $insert_qry .= "miss_charge='$miss_total',";$insert_qry .= "miss_flat='$miss_flat',";      $insert_qry .= "miss_total='$miss_total',";
    $insert_qry .= "vfo_pulse='$vfo_pulse',";       $insert_qry .= "vfo_charge='$vfo_total',"; $insert_qry .= "vfo_flat='$vfo_flat',";         $insert_qry .= "vfo_total='$vfo_total',";   
    $insert_qry .= "sms_pulse='$sms_pulse',";       $insert_qry .= "sms_charge='$sms_charge',";$insert_qry .= "sms_flat='$sms_flat',";          $insert_qry .= "sms_total='$sms_total',";       
    $insert_qry .= "email_pulse='$email_pulse',";   $insert_qry .= "email_charge='$email_charge',"; $insert_qry .= "email_flat='$email_flat',"; $insert_qry .= "email_total='$email_total',created_at=now(),plan_id='$PlanId'"; 
    
   


    //echo $insert_qry; exit ;
    mysqli_begin_transaction($dd, MYSQLI_TRANS_START_READ_ONLY);
    $insRsc = mysqli_query($dd,$insert_qry);
    
    if($insRsc)
    {
        if($todays_day_is_one=='01' || $todays_day_is_one=='1' || $todays_day_is_one==1)
        {            
            $insert_new_month_entry = "insert into billing_opening_balance set clientId='$clientId',fin_year='$fin_year',fin_month='$fin_month',bill_start_date=curdate(),bill_end_date='$end_date_of_month'";
            $NewBillingRsc = mysqli_query($dd,$insert_new_month_entry);
        }
        $sel_consume = "SELECT * FROM `billing_opening_balance` WHERE clientId='$clientId' and fin_year='$fin_year' and fin_month='$fin_month' LIMIT 1";
        $consumeRsc = mysqli_query($dd,$sel_consume);
        $consumeDet = mysqli_fetch_assoc($consumeRsc);
        $old_consume = $consumeDet['cs_bal'];
        $consume_till_date = round($old_consume + $cm_total,2);
        $op_id = $consumeDet['op_id'];
        
        
        
        
        
        $upd_consume = "update billing_opening_balance set cs_bal='$consume_till_date',as_on_date=now() where clientId='$clientId' and fin_year='$fin_year' and fin_month='$fin_month' limit 1";
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



