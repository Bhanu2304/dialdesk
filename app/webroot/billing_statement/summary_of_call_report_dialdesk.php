<?php


/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$FromDate   = date_format(date_create($_REQUEST['FromDate']),'Y-m-d');
$ToDate     = date_format(date_create($_REQUEST['ToDate']),'Y-m-d');
$client_id     = $_REQUEST['client'];
$category = $_REQUEST['category'];
//echo $client_id;die;
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

$category_qry = "";
if(!empty($category) && $category!='All')
{
    $category_qry = " and client_category='$category'";
}
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

if($client_id == 'All')
{
 $selec_all_clients_qry = "SELECT * FROM `registration_master` WHERE  `status`='A' and is_dd_client='1' $category_qry order by company_name"; 
$RegistrationMaster = mysqli_query($dd,$selec_all_clients_qry) ;

}else{
    $selec_all_clients_qry = "SELECT * FROM `registration_master` WHERE  `status`='A' and is_dd_client='1' and company_id='$client_id' $category_qry order by company_name"; 
    $RegistrationMaster = mysqli_query($dd,$selec_all_clients_qry) ;  
}

$total_calls = array();
    
while($ClientInfo = mysqli_fetch_assoc($RegistrationMaster))
{
    $clientId = $ClientInfo['company_id']; 
    $bal_qry = "select * from `balance_master` where clientId='$clientId' and DATE(start_date) <=CURDATE()  limit 1";
    $BalanceMasterRsc = mysqli_query($dd,$bal_qry);
    $BalanceMaster = mysqli_fetch_assoc($BalanceMasterRsc);
    $total_calls[$clientId]['client'] = $ClientInfo['company_name']; 
    
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
    
    $InboundQry = "select if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`) length_in_sec, phone_number,call_date from `vicidial_closer_log` t2 left join vicidial_agent_log t3 on t2.uniqueid=t3.uniqueid where t2.user !='VDCL' and t2.campaign_id in ($Campagn) AND DATE(call_date) between '$FromDate' and  '$ToDate'";
    $InboundDetails=mysqli_query($vd,$InboundQry);
    
    $OutboundQry = "select length_in_sec,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_log` where length_in_sec!='0' and user !='VDAD' and campaign_id in ($Campagn) AND DATE(call_date) between '$FromDate' and  '$ToDate'";
    $OutboundDetails=mysqli_query($vd,$OutboundQry);

    
   
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
            $ibn_secs += $call_duration;
            $ibn_total += 1;
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
            $ib_secs += $call_duration;
            $ib_total += 1;
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
        $ob_secs += $callLength;
        $ob_total += 1;
    }
    
    $total_calls[$clientId]['ib_count'] += $ib_total+$ibn_total;
    $total_calls[$clientId]['out_count'] += $ob_total; 

    $total_calls[$clientId]['ib_talk'] += ($ib_secs+$ibn_secs);
    $total_calls[$clientId]['out_talk'] += $ob_secs; 
    
   
    
    
}

}



$fileName = "summary_of_call_report_".date('d_m_y_h_i_s');
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$fileName.".xls");
header("Pragma: no-cache");
header("Expires: 0");

#print_r($total_calls);

?>


<table border="2">
<thead>
    <tr> 
        <th>Client</th> 
        <th>Count(IB Calls)</th>
        <th>Count(OB Calls)</th> 
        <th>Total Talk IN Hrs(IB Calls)</th>
        <th>Total Talk IN Hrs(OB Calls)</th>
        <th>Count</th> 
        <th>Total Talk IN Hrs</th>
    </tr>
</thead>
<tbody>
    <?php 

        foreach($total_calls as $key=>$record)
        {
            echo '<tr>';
                echo '<th>'.$record['client'].'</th>';
                echo '<td>'.$record['ib_count'].'</td>';
                echo '<td>'.$record['out_count'].'</td>';
                echo '<td>'.round($record['ib_talk']/3600,2).'</td>';
                echo '<td>'.round($record['out_talk']/3600,2).'</td>';
                echo '<td>'.round($record['ib_count']+$record['out_count']).'</td>';
                echo '<td>'.round(($record['ib_talk']+$record['out_talk'])/3600,2).'</td>';
            echo '</tr>';

            $talk_ib += round($record['ib_talk']/3600,2);
            $talk_ob += round($record['out_talk']/3600,2);
            $total_ib += round($record['ib_count']);
            $total_ob += round($record['out_count']);
            $grand_talk += round(($record['ib_talk']+$record['out_talk'])/3600,2);
            $grand_total += round($record['ib_count']+$record['out_count']);
        }

        echo '<tr>';
                echo '<th>Total</th>';
                echo '<td>'.$total_ib.'</td>';
                echo '<td>'.$total_ob.'</td>';
                echo '<td>'.round($talk_ib,2).'</td>';
                echo '<td>'.round($talk_ob,2).'</td>';
                echo '<td>'.round($grand_total).'</td>';
                echo '<td>'.round($grand_talk,2).'</td>';
            echo '</tr>';

    ?>
</tbody>
</table>

<?php

exit;
?>


