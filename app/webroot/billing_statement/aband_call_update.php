<?php


/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
//echo $last_date = "2024-01-09";
//$last_date = date('Y-m-d',strtotime("-4 days"));
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

$dialdee = mysql_connect("192.168.10.23", "root", "Mas@1234")or die("cannot connect to dialdee"); 
mysql_select_db("$db_name",$dialdee)or die("cannot select DB");

$selec_all_clients_qry = "SELECT * FROM `registration_master` WHERE  `status`='CL' and company_id in (281,
333,
348,
439,
452,
457,
465,
473,
487,
488,
489,
490,
491,
493,
495,
497,
500,
501,
502,
503,
504,
479,
505,
506,
507,
508,
509,
511,
512,
514,
478,
517,
519,
521,
461,
466,
548
)"; #`status` in ('A','H','D') 


//exit;
$date_query = "SELECT DISTINCT(cm_date) cm_date FROM `billing_consume_daily` WHERE DATE(cm_date) BETWEEN '2023-04-01' AND '2024-01-17'"; 
$rsc_man = mysqli_query($dd,$date_query) ;

$aband_qry = "SELECT t2.uniqueid,t2.call_date AS CallDate,date(t2.call_date) AS CallDate2,LEFT(t2.phone_number,10) AS PhoneNumber,
            t2.`user` AS Agent
FROM asterisk.vicidial_log t2
WHERE t2.campaign_id='dialdesk' AND DATE(t2.call_date) BETWEEN '2023-04-01' AND '2024-01-17'  
AND t2.lead_id IS NOT NULL"; 

$aband_data = array();
    $OutboundDetails_aband=mysqli_query($vd,$aband_qry); 
    while($aboutDurArr = mysqli_fetch_assoc($OutboundDetails_aband))
    {
        $aband_data[$aboutDurArr['CallDate2']][] = $aboutDurArr;
    }

    //print_r($aband_data);exit;
$last_date = '';
while($cm_det = mysqli_fetch_assoc($rsc_man))
{
    echo $last_date = $cm_det['cm_date'];
    echo '<br/>';
    
    $RegistrationMaster = mysqli_query($dd,$selec_all_clients_qry) ;
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
    
    
    
   

    
    foreach($aband_data[$last_date] as $aboutDurArr)
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
    
    
    
    $cm_total = round($ib_total+$ibn_total+$ob_total+$ivr_total+$miss_total+$vfo_total+$sms_total+$email_total+$wasms_total+$bot_social_total ,3);
    
    $upd = "update billing_consume_daily set aband_flat='2',aband_pulse='$ob_pulse',aband_charge='$ob_pulse_rate',aband_total='$ob_total' where client_id='$clientId' and cm_date='$last_date' limit 1 ";

    //echo $insert_qry; exit ;
    mysqli_begin_transaction($dd, MYSQLI_TRANS_START_READ_ONLY);
    $insRsc = mysqli_query($dd,$upd);
    
    if($insRsc)
    {
        mysqli_commit($dd);
        
    }
    else
    {
         mysqli_rollback($dd);
    }
}

}
}
    





