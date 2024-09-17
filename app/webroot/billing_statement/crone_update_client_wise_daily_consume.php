<?php


/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
//echo $last_date = "2023-08-18";
$last_date = date('Y-m-d',strtotime("-1 days"));
$todays_day_is_one = date('d',strtotime("-1 days"));
$end_date_of_month = date('Y-m-t',strtotime("-1 days"));
$fin_year = date('Y',strtotime("-1 days"));
$fin_month = date('M',strtotime("-1 days"));


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

$dialdee = mysql_connect("172.12.10.30", "root", "Mas@1234")or die("cannot connect to dialdee"); 
mysql_select_db("$db_name",$dialdee)or die("cannot select DB");


$aband_qry = "SELECT t2.uniqueid,t2.call_date AS CallDate,date(t2.call_date) AS CallDate2,LEFT(t2.phone_number,10) AS PhoneNumber,
            t2.`user` AS Agent
FROM asterisk.vicidial_log t2
WHERE t2.campaign_id='dialdesk' AND DATE(t2.call_date) = '$last_date'
AND t2.lead_id IS NOT NULL";
$OutboundDetails_aband=mysqli_query($vd,$aband_qry); 

$aband_data = array();
while($aboutDurArr2 = mysqli_fetch_assoc($OutboundDetails_aband))
{
    $aband_data[] = $aboutDurArr2;
}    
    



$selec_all_clients_qry = "SELECT * FROM `registration_master` WHERE  `status`='A' "; #`status` in ('A','H','D') 
$RegistrationMaster = mysqli_query($dd,$selec_all_clients_qry) ;

//exit;
    
while($ClientInfo = mysqli_fetch_assoc($RegistrationMaster))
{
   $clientId = $ClientInfo['company_id']; 
   $bal_qry = "select * from `balance_master` where clientId='$clientId'   limit 1";
    $BalanceMasterRsc = mysqli_query($dd,$bal_qry);
    $BalanceMaster = mysqli_fetch_assoc($BalanceMasterRsc);
    
    #print_r($ClientInfo);exit;
    
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
    $wasms_pulse = 0;     $wasms_secs=0;         $wasms_charge = 0;    $wasms_flat = 0;      $wasms_total = 0;     //$wasms_pulse_per_call = 60;
    
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
    $wasms_charge = $PlanDetails['whatsapp_message_charge'];  

    $chat_charge = $PlanDetails['chat_session_rate'];
    $chat__byte_rate = $PlanDetails['chat_byte_rate'];
    
    $select_multitool_qry = "select * from balance_tool_master where clientId='$clientId'"; 
    $rsc_multitools_qry = mysqli_query($dd,$select_multitool_qry);
    
    $inbot_charge = 0;
    $outbot_charge = 0;
    while($multitool_det = mysqli_fetch_assoc($rsc_multitools_qry))
    {
      $tool_id = $multitool_det['ToolId'];
      $tool_type = $multitool_det['ToolType']; 
      
      $select_tool_qry = "select * from plan_tool_master where id='$tool_id' limit 1"; 
      $rsc_tool = mysqli_query($dd,$select_tool_qry);
      $tool_det = mysqli_fetch_assoc($rsc_tool);
      
      if($tool_type=='Dialdee')
      {
            $inbot_charge = $tool_det['InboundWhatsappCharge'];
            $outbot_charge = $tool_det['OutboundWhatsappCharge'];
      }
    }



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
    
    $InboundQry = "select if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`) length_in_sec, phone_number,call_date from `vicidial_closer_log` t2 left join vicidial_agent_log t3 on t2.uniqueid=t3.uniqueid and t2.user=t3.user where t2.user !='VDCL' and t2.campaign_id in ($Campagn) AND DATE(call_date) = '$last_date'";
    $InboundDetails=mysqli_query($vd,$InboundQry);
    
    //$OutboundQry = "select length_in_sec,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_log` where length_in_sec!='0' and user !='VDAD' and campaign_id in ($Campagn) AND DATE(call_date) = '$last_date'  ";
    $OutboundQry = "select va.talk_sec,va.dead_sec,call_date from `vicidial_log` v join vicidial_agent_log va on v.uniqueid=va.uniqueid where length_in_sec!='0' and v.user !='VDAD' and v.campaign_id in ($Campagn) AND DATE(call_date) ='$last_date' ";
    $OutboundDetails=mysqli_query($vd,$OutboundQry);
    
    

    $IvrQry = "SELECT DATE_FORMAT(call_time,'%d %b %y') `CallDate1`,call_time CallDate,source_number CallFrom,uniqueid FROM `rx_log` WHERE clientId='$clientId'  AND date(call_time) = '$last_date'";
    $IvrDetails = mysqli_query($dd,$IvrQry);

    
    $SMSQuery = "SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='SMS' AND date(CallDate) = '$last_date'  ";
    $SMSDetails = mysqli_query($dd,$SMSQuery);
    
    $EmailQry = "SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='Email' AND date(CallDate) = '$last_date'";
    $EmailDetails = mysqli_query($dd,$EmailQry);
    
    $MissQry = "SELECT DATE_FORMAT(call_time,'%d %b %y') `CallDate1`,call_time CallDate,source_number CallFrom FROM `billing_master` WHERE clientId='$clientId' AND DedType='MISSCALL' AND date(CallDate) = '$last_date'";
    $MissDetails = mysqli_query($dd,$MissQry);
    
    $VFOQry = "SELECT DATE_FORMAT(calltime,'%d %b %y') `CallDate1`,calltime CallDate,source_number, uniqueid FROM sbarro_data WHERE clientId='$clientId'  AND DATE(calltime) = '$last_date'";
    $VfoDetails = mysql_query($vd,$VFOQry);
    
    $waSMSQuery = "SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='WhatsappAlert' AND date(CallDate) = '$last_date'  ";
    $waSMSDetails = mysqli_query($dd,$waSMSQuery);

    
    
    
    $Social_bussiness_Qry = "select count(1) cnt from dialdee_broadcastdatamaster where client_id='$clientId' and date(created_at) ='$last_date'";  
    $Social_bussinessDetails = mysql_query($Social_bussiness_Qry,$dialdee);

    $Social_bot_Qry2 = "SELECT DISTINCT(customer_no) customer_no FROM chat_customer WHERE client_id='$clientId' AND DATE(created_at)='$last_date'";  
    $Social_botDetails2 = mysql_query($Social_bot_Qry2,$dialdee);
    
    
   
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
                $call_pulse = ceil($call_duration/$ibn_pulse_sec);
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
        #va.talk_sec-va.dead_sec
        $callLength = round($outDurArr['talk_sec']-$outDurArr['dead_sec']);  
        $total_pulse = 0;
        $call_pulsesec = 0;
        $call_rate = 0;
        
        if(!empty($callLength))
        {
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
        }
        

        $ob_pulse += $total_pulse;
        $ob_secs += $call_pulsesec;
        $ob_total += $call_rate;
    }
    
    foreach($aband_data as $aboutDurArr)
    {
        #va.talk_sec-va.dead_sec
        $qry = "SELECT * FROM aband_call_master acm where clientid='$clientId' and PhoneNo='".$aboutDurArr['PhoneNumber']."' and date(Callbackdate)=date('".$aboutDurArr['CallDate']."') limit 1"; 
        $rsc_comp = mysqli_query($dd,$qry);
        $det = mysqli_fetch_assoc($rsc_comp);
        
        if(!empty($det))
        {
            $sel_talk = "SELECT talk_sec AS TalkSec FROM vicidial_agent_log WHERE uniqueid='".$aboutDurArr['uniqueid']."' limit 1;";
            $talk_rsc=mysqli_query($vd,$sel_talk); 
            $talk_det = mysqli_fetch_assoc($talk_rsc);
            $callLength = round($talk_det['TalkSec']);  
            $total_pulse = 0;
            $call_pulsesec = 0;
            $call_rate = 0;

            if(!empty($callLength))
            {
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
            }


            $ob_pulse += $total_pulse;
            $ob_secs += $call_pulsesec;
            $ob_total += $call_rate;
        }
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
    
    while($wasmsArr = mysqli_fetch_assoc($waSMSDetails))
    {
        $wasms_unit =$wasmsArr['Unit'];;
        $wasms_rate = 0;
        $wasms_pulsesec = 0;
        $wasms_pulse += $wasms_unit;   
        $wasms_total += $wasms_charge*$wasms_unit;
    }

    
    $BotUnit = 0;
        
    while($broadcastArr = mysql_fetch_assoc($Social_bussinessDetails))
    {
        //$creation_date = $botArr['created_at'];
        //$customer_no = $botArr['customer_no'];
        #$secondlast_date = date('Y-m-d',strtotime("-2 days"));
        #$check_session_exist_time = date('Y-m-d H:i:s',strtotime($creation_date." -1 days"));
        $BotUnit = $broadcastArr['cnt'];
        
        
    }
    
    while($botArr = mysql_fetch_assoc($Social_botDetails2))
    {
        //$creation_date = $botArr['created_at'];
        //$customer_no = $botArr['customer_no'];
        #$secondlast_date = date('Y-m-d',strtotime("-2 days"));
        #$check_session_exist_time = date('Y-m-d H:i:s',strtotime($creation_date." -1 days"));
        $BotUnit += 1;
        
        
    }
    
        
        $bot_social_total = round($BotUnit*$inbot_charge,2);
        $bot_social_pulse    = $BotUnit;
        $bot_social_rate      = $inbot_charge; 
        
        
    //dialdee bot starts from here
    /*$session_check_bot = "select * from chat_customer where client_id='$clientId' date(created_at)='$secondlast_date";
    $rsc_session_bot = mysqli_query($dialdesk,$session_check_bot);
    $session_data_bot = array();
    while($session_check = mysqli_fetch_assoc($rsc_session_bot))
    {
        $creation_date = $session_check['created_at'];
        $customer_no = $session_check['contactNo'];
        $session_data_bot[$customer_no][] = $creation_date;
    }    
        
    $unique_session_bot = array();    
    while($botArr = mysqli_fetch_assoc($Social_botDetails))
    {
        $creation_date = $botArr['created_at'];
        $customer_no = $botArr['customer_no'];
        #$secondlast_date = date('Y-m-d',strtotime("-2 days"));
        #$check_session_exist_time = date('Y-m-d H:i:s',strtotime($creation_date." -1 days"));
        $SocialUnit = 1;
        if( in_array($customer_no,$session_data_bot))
        {
            $time_array = $session_data_bot[$customer_no];
            foreach($time_array as $time)
            {
                $diff_time = strtotime($creation_date)-strtotime($time);
                if($diff_time<3600*24)
                {
                    $SocialUnit = 0;
                }
            }
        }
        else
        {
             $SocialUnit =1;
        }
        if($SocialUnit==1)
        {$unique_session_bot[$customer_no] = $SocialUnit;}
    }
    
        $BotUnit = count($unique_session_bot);
        $bot_social_rate = ceil($BotUnit*$inbot_charge);
        $bot_social_pulse    += $BotUnit;
        $bot_social_total      += $bot_social_rate;    */
    
    $cm_total = round($ib_total+$ibn_total+$ob_total+$ivr_total+$miss_total+$vfo_total+$sms_total+$email_total+$wasms_total+$bot_social_total ,3);

    $insert_qry = "INSERT INTO `billing_consume_daily` SET client_id='$clientId',cm_date='$last_date',cm_total='$cm_total',";
    $insert_qry .= "ib_pulse='$ib_pulse',";         $insert_qry .= "ib_secs='$ib_secs',";       $insert_qry .= "ib_charge='$ib_charge',";       $insert_qry .= "ib_flat='$ib_flat',";       $insert_qry .= "ib_total='$ib_total',";
    $insert_qry .= "ibn_pulse='$ibn_pulse',";       $insert_qry .= "ibn_secs='$ibn_secs',";     $insert_qry .= "ibn_charge='$ibn_charge',";     $insert_qry .= "ibn_flat='$ibn_flat',";     $insert_qry .= "ibn_total='$ibn_total',";
    $insert_qry .= "ob_pulse='$ob_pulse',";         $insert_qry .= "ob_secs='$ob_secs',";       $insert_qry .= "ob_charge='$ob_charge',";       $insert_qry .= "ob_flat='$ob_flat',";       $insert_qry .= "ob_total='$ob_total',";
    $insert_qry .= "ivr_pulse='$ivr_pulse',";       $insert_qry .= "ivr_charge='$ivr_total',"; $insert_qry .= "ivr_flat='$ivr_flat',";         $insert_qry .= "ivr_total='$ivr_total',";
    $insert_qry .= "miss_pulse='$miss_pulse',";     $insert_qry .= "miss_charge='$miss_total',";$insert_qry .= "miss_flat='$miss_flat',";      $insert_qry .= "miss_total='$miss_total',";
    $insert_qry .= "vfo_pulse='$vfo_pulse',";       $insert_qry .= "vfo_charge='$vfo_total',"; $insert_qry .= "vfo_flat='$vfo_flat',";         $insert_qry .= "vfo_total='$vfo_total',";   
    $insert_qry .= "sms_pulse='$sms_pulse',";       $insert_qry .= "sms_charge='$sms_charge',";$insert_qry .= "sms_flat='$sms_flat',";          $insert_qry .= "sms_total='$sms_total',";       
    $insert_qry .= "email_pulse='$email_pulse',";   $insert_qry .= "email_charge='$email_charge',"; $insert_qry .= "email_flat='$email_flat',"; $insert_qry .= "email_total='$email_total',"; 
    //$insert_qry .= "dialdee_pulse='$social_pulse',";   $insert_qry .= "dialdee_charge='$chat_charge',"; $insert_qry .= "dialdee_flat='0',"; $insert_qry .= "dialdee_total='$social_total',";
    $insert_qry .= "botapp_pulse='$bot_social_pulse',";   $insert_qry .= "botapp_charge='$inbot_charge',"; $insert_qry .= "botapp_flat='0',"; $insert_qry .= "botapp_total='$bot_social_total',";
    $insert_qry .= "whatsapp_sms_pulse='$wasms_pulse',";   $insert_qry .= "whatsapp_sms_charge='$wasms_charge',"; $insert_qry .= "whatsapp_sms_flat='$wasms_flat',"; $insert_qry .= "whatsapp_sms_total='$wasms_total',"; 
    $insert_qry .= "created_at=now(),plan_id='$PlanId'";
   


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



