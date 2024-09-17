<?php


//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');


include_once('send_email.php');
$dd_host       =   "localhost"; 
$dd_user   =   "root";
$dd_pass   =   "dial@mas123";
$dd_name    =   "db_dialdesk";

//ispark connection
$ispark_host       =   "192.168.10.22"; 
$ispark_user   =   "root";
$ispark_pass   =   "321*#LDtr!?*ktasb";
$ispark_name    =   "db_bill";

$con = mysqli_connect("192.168.10.5","root","vicidialnow");
mysqli_select_db($con,"asterisk")or die("cannot select DB");

$dd = mysqli_connect("$dd_host", "$dd_user", "$dd_pass")or die("cannot connect"); 
mysqli_select_db($dd,"$dd_name")or die("cannot select DB");

$ispark = mysqli_connect("$ispark_host", "$ispark_user", "$ispark_pass")or die("cannot connect"); 
mysqli_select_db($ispark,"$ispark_name")or die("cannot select DB");

function get_today_consume_data($client_id,$dd,$vd)
{
            
            $last_date = date('Y-m-d');
            $bal_qry = "select * from `balance_master` where clientId='$client_id'  limit 1";
            //$BalanceMasterRsc = mysqli_query($dd,$bal_qry);
            $registration_rsc = mysqli_query($dd,$bal_qry);
            $BalanceMasterRsc = mysqli_fetch_assoc($registration_rsc);
            
            $BalanceMaster = $BalanceMasterRsc;
            //print_r($BalanceMaster);exit;

          
            $cm_total = 0;
            $ib_pulse = 0;      $ib_secs=0;         $ib_charge = 0;     $ib_flat = 0;       $ib_total = 0;      $ib_pulse_per_call = 60;
            $ibn_pulse = 0;     $ibn_secs=0;         $ibn_charge = 0;    $ibn_flat = 0;      $ibn_total = 0;     $ibn_pulse_per_call = 60;
            $ob_pulse = 0;      $ob_secs=0;         $ob_charge = 0;     $ob_flat = 0;       $ob_total = 0;      $ob_pulse_per_call = 60;
            $ivr_pulse = 0;     $ivr_secs=0;         $ivr_charge = 0;    $ivr_flat = 0;      $ivr_total = 0;     //$ivr_pulse_per_call = 60;
            $miss_pulse = 0;    $miss_secs=0;         $miss_charge = 0;   $miss_flat = 0;     $miss_total = 0;    //$miss_pulse_per_call = 60;
            $vfo_pulse = 0;     $vfo_secs=0;         $vfo_charge = 0;    $vfo_flat = 0;      $vfo_total = 0;     //$vfo_pulse_per_call = 60;
            $sms_pulse = 0;     $sms_secs=0;         $sms_charge = 0;    $sms_flat = 0;      $sms_total = 0;     $sms_pulse_per_call = 60;
            $email_pulse = 0;   $email_secs=0;         $email_charge = 0;  $email_flat = 0;    $email_total = 0;   //$email_pulse_per_call = 60;
    
    
            if($BalanceMaster['PlanId'] !="")
            {
                //$ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$ClientId'"));
                $BalanceRsc = mysqli_query($dd,"select * from registration_master where company_id='$client_id' limit 1");
                $BalanceMasterRsc = mysqli_fetch_assoc($BalanceRsc);
                $Campagn=$BalanceMasterRsc['campaignid']; 

                $PlanId = $BalanceMaster['PlanId'];
                $plan_det_qry = "select * from `plan_master` where Id='$PlanId' limit 1";
                //$PlanDetailsRsc = $this->RegistrationMaster->query($plan_det_qry);
                $plan_rsc = mysqli_query($dd,$plan_det_qry);
                $PlanDetails = mysqli_fetch_assoc($plan_rsc);


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

                //print_r($return);exit;
            return $cm_total;
            }


            
            return 0; exit;
}

function get_fr_value($client_id,$ispark,$SetupCost,$developmentCost,$plan_pers)
{
    $month_start_date  = date('Y-m-01');
    $month_end_date = date('Y-m-d');
    $selec_all_payment_qry = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
     tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$client_id'
    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('first_bill')
    AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no"; 
    $collection_qry = mysqli_query($ispark,$selec_all_payment_qry);
    $first_bill_coll = 0;
    $first_plan_subscoll = 0;
    $subbilled = 0;
    $billed = 0;
    while($coll_det = mysqli_fetch_assoc($collection_qry))
    {
        $first_bill_coll += $coll_det['bill_passed'];
    }
    
    $first_bill_coll_ntgst = round(($first_bill_coll/118)*100,2);
    $first_subs = $first_bill_coll_ntgst- $SetupCost- $developmentCost;
    if($first_subs>0)
    {
        $first_plan_subscoll = round($first_subs*1.18,2);
    }
    
    //get cost_center
    $cost_qry = "select * from cost_master cm where dialdesk_client_id='$client_id' limit 1";
    $cost_cat_rsc = mysqli_query($ispark,$cost_qry);
    $cost_cat_arr = mysqli_fetch_assoc($cost_cat_rsc);
    $cost_center = $cost_cat_arr['cost_center'];
    $sel_subs_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category='subscription' AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date' and bill_no!=''";
    //$bill_subs_arr = $this->InitialInvoice->query($sel_subs_qry);
    $subbilled_rsc = mysqli_query($ispark,$sel_subs_qry);
    while($subbilled_arr = mysqli_fetch_assoc($subbilled_rsc))
    {
        $subbilled += $subbilled_arr['grnd'];
    }
    
    $sel_billing_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category in ('Talk Time','Topup') AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date'  and bill_no!=''";
    $billing_rsc = mysqli_query($ispark,$sel_billing_qry);
    while($inv = mysqli_fetch_assoc($billing_rsc))
    {
        $billed += $inv['grnd'];
    }
    
    $op_val_subs = round((($first_plan_subscoll)/118)*$plan_pers,2);
    
    $fr_val_subs= round((($subbilled)/118)*$plan_pers,2);
    $fr_val_talk= round(($billed*100/118),2);

    $fr_val = $fr_val_subs+$fr_val_talk+$op_val_subs;
    
    return $fr_val;
}

function nagitive_check($value){
    if (isset($value)){
        if (substr(strval($value), 0, 1) == "-"){
        return 'negative';
    } else {
        return 'It is not negative!';
    }
        }
}


$select_risk = "SELECT * FROM `billing_risk_exposure_mail` where risk_action='email' ORDER BY risk_id DESC LIMIT 1 ";
$rm = "SELECT * FROM registration_master rm 
INNER JOIN  balance_master bm ON rm.company_id=bm.clientId
INNER JOIN  plan_master pm ON bm.PlanId = pm.Id
WHERE rm.status='A' AND (bm.start_date IS NOT NULL AND bm.start_date<CURDATE())";

$rsc_risk = mysqli_query($dd,$select_risk);
//print_r($rsc_risk);die;
$send_mail = mysqli_query($dd,$rm);
//print_r($send_mail);die;
$records = mysqli_num_rows($send_mail);
//print_r($records);die;


    /*while($data = mysqli_fetch_assoc($send_mail))
    {
        //print_r($data['company_id']);die;
        $client_id = $data['company_id'];
        $PlanId = $data['PlanId'];
        $SetupCost = $data['SetupCost']; 
        $developmentCost = $data['DevelopmentCost'];
        $onePer = round($data['RentalAmount']/100,2); 
        $plan_pers = round($data['Balance']/$onePer,3);
        $RentalValue = $data['CreditValue'];*/


        while($risk = mysqli_fetch_assoc($rsc_risk))
        {
                //$clientId   = $risk['client_id'];
                $percent = $risk['percent'];

                // $sel_sent_status = "SELECT * FROM `billing_risk_exposure_mail_status` WHERE clientId='$client_id'  AND date(mail_date)=CURDATE()";
                // $rsc_sent = mysqli_query($dd,$sel_sent_status);
                // //print_r($rsc_sent);die;
                // $sent_det = mysqli_fetch_assoc($rsc_sent);
                // if(!empty($sent_det))
                // {
                //     continue;
                // }

                
                //print_r($BalanceMaster);die;
                

                    //$today_consume = get_today_consume_data($client_id,$dd,$con);
                
                    $To = $risk['email_id'];
                    $Tos = explode(",",$To);
                    $AddTo = array();
                    $TosFlag = true;
                    if(!empty($Tos) && is_array($Tos))
                    {
                        foreach($Tos as $to)
                        {
                            if(!empty($to))
                            {
                                if($TosFlag)
                                {
                                    $To = $to;
                                    $TosFlag=false;
                                }
                                else
                                {
                                    $AddTo[] = $to;
                                }
                            }
                            
                        }
                    }
                
                    $send_email = false;
                
                    
                    
                    $ClientRsc = mysqli_query($dd,"SELECT * FROM registration_master rm 
                    INNER JOIN  balance_master bm ON rm.company_id=bm.clientId
                    INNER JOIN  plan_master pm ON bm.PlanId = pm.Id
                    WHERE  rm.status='A' AND (bm.start_date IS NOT NULL AND bm.start_date<CURDATE())");
                    $table_trigger_detail = array();
                    
                    $table_trigger_detail  = "<table border=\"2\"><tr><th>Client Name</th>";
                            $table_trigger_detail .= "<th>Plan Mode Selected</th>";
                            $table_trigger_detail .= "<th>Subscription value</th>";
                            $table_trigger_detail .= "<th>Credit Value</th>";
                            $table_trigger_detail .= "<th>Risk %</th>";
                            $table_trigger_detail .= "<th>current usage till yesterday</th>";
                            $table_trigger_detail .= "<th>current usage till yesterday</th></tr>";
                            $client_per_list = array();
                            $client_data = array();
                    
                    while($ClientMaster = mysqli_fetch_assoc($ClientRsc))
                    {
                        $client_id = $ClientMaster['company_id'];
                        
                      //echo  $today_consume = get_today_consume_data($client_id,$dd,$con); exit;
                        
                        $bal_carray = 0; $pending_bal = 0;
                        $open_qry = "select * from billing_ledger where clientId='$client_id' and fin_year=year(curdate()) and fin_month=date_format(curdate(),'%b') ";
                        $rsc_get_opening = mysqli_query($dd,$open_qry);
                        $OpeningDet = mysqli_fetch_assoc($rsc_get_opening);
                        $month_opening = $OpeningDet['talk_time'];

                        $rsc_get_consumed = mysqli_query($dd,"SELECT * FROM `billing_opening_balance` where clientId='$client_id' and date(curdate()) between bill_start_date and bill_end_date ");
                        $ConsumeDet = mysqli_fetch_assoc($rsc_get_consumed);
                        $month_closing = $ConsumeDet['cs_bal']; 

                        $SetupCost = $ClientMaster['SetupCost']; 
                        $developmentCost = $ClientMaster['DevelopmentCost'];
                        $onePer = round($ClientMaster['RentalAmount']/100,2); 
                        $plan_pers = round($ClientMaster['Balance']/$onePer,3);
                       

                        

                        $fr_value  = round(get_fr_value($client_id,$ispark,$SetupCost,$developmentCost,$plan_pers),2);
                        $total_consume = $month_opening+$fr_value-$month_closing-$today_consume;
                        // echo $month_opening.'<br>';
                        // echo $fr_value.'<br>';
                        // echo $month_closing.'<br>';
                        // echo $ClientMaster['CreditValue'].'<br>';
                        // echo $today_consume;
                        //echo $total_consume2 = $ClientMaster['CreditValue']-$total_consume; 

                        $calc_perc = round(($total_consume*100)/$ClientMaster['CreditValue'],2);
                        $consume_percent = 100-$calc_perc;
                        //echo $send_email;die;
                        //echo $calc_perc;
                        $operation = nagitive_check($calc_perc);
                        if(($consume_percent>=$percent && $ClientMaster['CreditValue']>$total_consume) || ($operation == 'negative'))
                        {
                            $send_email = true;
                            
                            $client_data[$client_id] .= "<tr><td>".$ClientMaster['company_name']."</td>";                            
                            $client_data[$client_id] .= "<td>".$ClientMaster['PeriodType']."</td>";
                            $client_data[$client_id] .= "<td>".$ClientMaster['RentalAmount']."</td>";
                            $client_data[$client_id] .= "<td>".$ClientMaster['CreditValue']."</td>";
                            $client_data[$client_id] .= "<td>".$percent."</td>";
                            $client_data[$client_id] .= "<td>".$consume_percent."</td>";
                            $client_data[$client_id] .= "<td>".$total_consume."</td>";
                            $client_data[$client_id] .= "</tr>"; 
                            $client_per_list[$client_id] = $consume_percent;
                            
                            //$done = 1;
                            if($send_email)
                            {
                                $insert = "INSERT INTO `billing_risk_exposure_mail_status_outer` SET clientId='$client_id',percent='$percent',calc_percent='$calc_perc',mail_date=now(),sent_status='1',sent_text='$risk_remarks',today_closing_aot='$total_consume'";
                                $save = mysqli_query($dd,$insert);
                            }
                            else
                            {
                                $insert = "INSERT INTO `billing_risk_exposure_mail_status_outer` SET clientId='$client_id',percent='$percent',calc_percent='$calc_perc',mail_date=now(),sent_status='failed',sent_text='$risk_remarks',today_closing_aot='$total_consume'";
                                $save = mysqli_query($dd,$insert);
                            }
                        }
                        
                    }
                    
                    //print_r($emaildata);die;

                        if($send_email)
                        {
                        $Subject="Risk Management ".date('d/M/Y');
                            $AddCc = explode(',',$risk['email_cc']);
                            $EmailText = $Subject;
                            $EmailText .="<br/>";
                            $EmailText .="<br/>";
                            $EmailText .= $risk['remarks'];
                            $EmailText .="<br/>";
                            $EmailText .="<br/>";
                            //$table = implode(" ",$table_trigger_detail);
                            arsort($client_per_list);
                            foreach($client_per_list as $client_id=>$clientPercent)
                            {
                                $table_trigger_detail .= $client_data[$client_id];
                            }
                            $table_trigger_detail .= "</table>"; 
                            $EmailText .= $table_trigger_detail;  
                            $EmailText .="<br/>";
                            $EmailText .="<br/>";
                            $EmailText .="This is a auto-generated mail.";
                            $EmailText .="<br/>";
                            $risk_remarks = addslashes($EmailText);
                            $ReceiverEmail=array('Email'=>$To,'Name'=>''); 
                            $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
                            $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
                            
                            $emaildata = array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'EmailText'=> $EmailText);

                            $emaildata['Subject'] =  $Subject;
                            if(!empty($AddCc))
                            {
                                $emaildata['AddCc'] =  $AddCc;
                            }
                            $AddTo[] = "krishna.kumar@teammas.in";
                            $AddTo[] = "anil.goar@teammas.in";
                            if(!empty($AddTo))
                            {
                                $emaildata['AddTo'] =  $AddTo;
                                
                            }
                            try
                            {
                               // print_r($emaildata);exit;
                                $done = send_email($emaildata);  
                            }
                            catch(Exception $e){
                                $insert = "INSERT INTO `billing_risk_exposure_mail_status_outer` SET clientId='$client_id',percent='$percent',calc_percent='$calc_perc',mail_date=now(),sent_status='failed',sent_text='$risk_remarks',today_closing_aot='$total_consume'";
                                $save = mysqli_query($dd,$insert);
                            }
                        }
            
                

             
       
        }

 //   }

// print_r($clientArr);die;










