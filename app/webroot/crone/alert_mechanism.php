<?php
$db1=mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

$db2 = mysqli_connect("localhost","root","dial@mas123") or die(mysqli_error($db2));
mysqli_select_db($db2,"db_dialdesk")or die("cannot select DB dialdesk");

// $db6 = mysql_connect('192.168.10.5','root','vicidialnow',TRUE);
// mysql_select_db('asterisk') or die('unable to connect');

$db6 = mysqli_connect("192.168.10.5","root","vicidialnow") or die(mysqli_error($db6));
mysqli_select_db($db6,"asterisk")or die("cannot select DB dialdesk");


$db7 = mysql_connect("192.168.10.22","root","321*#LDtr!?*ktasb") or die(mysqli_error($db7));
mysql_select_db("db_bill",$db7)or die("cannot select DB db 7");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



include('report-send.php');
//include('function_alert_mechanism.php');

$select = "select campaignid,company_name,company_id from registration_master where status='A' and is_dd_client='1'";
$export = mysql_query($select,$db1);

$htmlalert1 = "<table cellspacing='0' border='1'>";

    $htmlalert1 .= "<tr>";
    $htmlalert1 .= "<th>Client Id</th>";
    $htmlalert1 .= "<th>MTD</th>";
    $htmlalert1 .= "<th>Calls Offered</th>";
    $htmlalert1 .= "<th>Calls Handled</th>";
    $htmlalert1 .= "<th>Calls Abandoned</th>";
    $htmlalert1 .= "<th>Calls Abandoned (WITHIN 20 SECONDS) </th>";
    $htmlalert1 .= "<th>Calls Abandoned (AFTER 20 SECONDS)</th>";
    $htmlalert1 .= "</tr>";

    $total_arr = '';
    $total_handle = '';
    $total_aband = '';
    $total_aband_with_two = '';
    $total_aband_after_two = '';
    $total_mtd = '';

while($row = mysql_fetch_assoc($export)){ 

    $condition1 = "DATE(parked_time)=DATE_SUB(CURDATE(),INTERVAL 1 DAY)";
    $condition2 = "DATE(t2.call_date)=DATE_SUB(CURDATE(),INTERVAL 1 DAY)";

    $camaign_id = "t2.campaign_id in(".$row['campaignid'].")";
    

    $qry="SELECT count(*) Offer,t2.campaign_id ,SUM(IF(t2.user!='VDCL',1,0)) `Answered`,SUM(IF(t2.user='VDCL',1,0)) `Abandon`,
    SUM(IF(((t2.user='VDCL') AND (t2.queue_seconds>15 and t2.queue_seconds<=20)),1,0)) `AbndWithinTwosec`,
    SUM(IF(((t2.user='VDCL') AND (t2.queue_seconds IS NULL OR t2.queue_seconds>20)),1,0)) `AbndAftertwo`
    FROM asterisk.vicidial_closer_log t2
    LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid  AND t1.lead_id!='' AND t2.user=t1.user
    LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND $condition1 GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
    WHERE $condition2 and $camaign_id "; 

    $excute_campaign = mysqli_query($db6,$qry);

    

    while($camaign = mysqli_fetch_assoc($excute_campaign))
    {
       
        //$htmlalert1 .= $camaign;
        $total=$camaign['Answered']+$camaign['Abandon'];
        $AL  = round($camaign['Answered']/$total*100);
       // echo $AL;
        if($AL < 95 && $AL != 0)
        {
            $is_float_alert1 = true;

            $total_arr += $camaign['Offer'];
            $total_handle += $camaign['Answered'];
            $total_aband += $camaign['Abandon'];
            $total_aband_with_two += $camaign['AbndWithinTwosec'];
            $total_aband_after_two += $camaign['AbndAftertwo'];

            $mtd = $camaign['Offer'] + $camaign['Answered'] + $camaign['Abandon'] + $camaign['AbndWithinTwosec'] + $camaign['AbndAftertwo'];
            $total_mtd += $mtd;
            
               $htmlalert1 .= "<tr>
                    <td>{$row['company_name']}</td>
                    <td>{$mtd}</td>
                    <td>{$camaign['Offer']}</td>
                    <td>{$camaign['Answered']}</td>
                    <td>{$camaign['Abandon']}</td>
                    <td>{$camaign['AbndWithinTwosec']}</td>
                    <td>{$camaign['AbndAftertwo']}</td>
              </tr>";

        }
    }
    //$htmlalert1 .= "</html>";


// ----------------  Alert 2 -----------------
    $op2_credit = 0;
    $fr_release_credit = 0;

    $cost_qry2 = "select * from cost_master  where dialdesk_client_id='{$row['company_id']}'";
    $cost_data_alert2 = mysql_query($cost_qry2,$db7);


    $qry_bill = "SELECT * FROM `billing_ledger` WHERE fin_year='2022' and fin_month='Apr' and clientId = '{$row['company_id']}'";
    $data_bill = mysql_query($qry_bill,$db1);
    $data_bill_arr =    mysql_fetch_assoc($data_bill);

    if(!empty($data_bill_arr))
    {

        $talktime = $data_bill_arr['talk_time'];
        $op2_credit += $talktime;
    }


    $plan_get_qry = "SELECT PlanId,start_date FROM balance_master bm WHERE clientId='{$row['company_id']}' limit 1";
    $plan_get_data = mysql_query($plan_get_qry,$db1);
    $plan_data =    mysql_fetch_assoc($plan_get_data);
    $planId =    $plan_data['PlanId'];

    $plan_det_qry = "SELECT RentalAmount,PeriodType,Balance,CreditValue,SetupCost,DevelopmentCost,createdate FROM `plan_master` pm WHERE id='$planId' limit 1"; 
    $plan_det = mysql_query($plan_det_qry,$db1);
    $plan_det_data =    mysql_fetch_assoc($plan_det);

    $rental_credit = $plan_det_data['RentalAmount'];
    $balance_credit = $plan_det_data['Balance'];

    $amount_date = $plan_det_data['createdate'];


    while($cost_alert2 = mysql_fetch_assoc($cost_data_alert2))
    {
        $cost_center = $cost_alert2['cost_center'];  

        $sel_billed_todateqry = "select ti.* from tbl_invoice ti where ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' "; 
        $data_alert2 = mysql_query($sel_billed_todateqry,$db7);

        while($data_Arr = mysql_fetch_assoc($data_alert2))
        {
            $initial_id = $data_Arr['id'];

            if(strtolower($data_Arr['category'])==strtolower('first_bill'))
            {
                    //check whether first bill have subscritpion amount
                $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                $data_inv = mysql_query($select_subs,$db7);
                while($data_Arr_inv = mysql_fetch_assoc($data_inv))
                {
                        $fr_release_credit  += get_credit_from_subs_value($rental_credit,$balance_credit,$data_Arr_inv['amount']);

                        $op2_credit += get_credit_from_subs_value($rental_credit,$balance_credit,$data_Arr_inv['amount']);
                        
                }

            }
            else if(strtolower($data_Arr['category'])==strtolower('subscription'))
            {
                $fr_release_credit += get_credit_from_subs_value($rental_credit,$balance_credit,$data_Arr['total']);
                $op2_credit +=  get_credit_from_subs_value($rental_credit,$balance_credit,$data_Arr['total']);
            }
            else if(strtolower($data_Arr['category'])==strtolower('Talk Time') || strtolower($data_Arr['category'])==strtolower('Topup'))
            {
                $fr_release_credit += $data_Arr['total'];
                $op2_credit += $data_Arr['total'];
            }


           
        }

        $select_consumption = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='{$row['company_id']}'";
        $consumption = mysql_query($select_consumption,$db1);
    
        while($consumption_data = mysql_fetch_assoc($consumption))
        {
            $op2_credit -= $consumption_data['total'];
        }
        
    }

    $waiver_qry = "SELECT SUM(Balance) bal FROM `waiver_master` WHERE clientId='{$row['company_id']}' limit 1";
    $rsc_waiver = mysql_query($waiver_qry,$db1);
    $waiver = mysql_fetch_assoc($rsc_waiver);
    $waiver_bal = $waiver['bal'];

    $open_bal = round($op2_credit,2) + $waiver_bal;

    $fr_val = round($fr_release_credit,2);

    if($open_bal< 0 && $fr_val == 0)
    {
        
        alert_mechanism2($row['company_name'],$open_bal,$amount_date,$db1,$waiver);


    }



    

   




 

   
   



    // ------------------ Alert 3 -----------------

    $qry3 = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='{$row['company_id']}'";
    $data_alert3 = mysql_query($qry3,$db1);
    

    while($alert3 = mysql_fetch_assoc($data_alert3))
    {
        if($alert3['total'] == 0)
        {
            alert_mechanism3($row['company_id'],$row['company_name'],$db2,$db1);
        }
    }

}
$htmlalert1 .= "<tr>
                    <th>Total</th>
                    <th>$total_mtd</th>
                    <th>$total_arr</th>
                    <th>$total_handle</th>
                    <th>$total_aband</th>
                    <th>$total_aband_with_two</th>
                    <th>$total_aband_after_two</th>
              </tr>";
$htmlalert1 .= "</table>";
if($is_float_alert1)
{
    //echo $htmlalert1;die;
    alert_mechanism1($htmlalert1,$db1);
}



$firstDay = date("Y-m-d", strtotime("yesterday"))." 00:00:00";
$lastDay   = date("Y-m-d", strtotime("yesterday"))." 23:59:59";




// <-------  Alert 5   --------->
$qry5="select date(event_time) event_time, vl.user,vu.full_name,sum(if(lead_id>0 and status is not null,1,0)) calls,sec_to_time(sum(wait_sec+talk_sec+dispo_sec+pause_sec)) login_time,sec_to_time(sum(if(wait_sec>5000,0,wait_sec))) wait_sec,sec_to_time(sum(talk_sec)) talk_sec,sec_to_time(sum(dispo_sec)) dispo_sec,sec_to_time(sum(if(pause_sec>5000,0,pause_sec))) pause_sec,lead_id,status,sec_to_time(sum(dead_sec)) dead_sec from vicidial_agent_log vl join vicidial_users vu on vl.user=vu.user where event_time <= '$lastDay' and event_time >= '$firstDay' and campaign_id IN('Dialdesk') and vl.user_group IN('ADMIN','Agarwal_Pharma','AppleProcess','Boost_M','Dialdesk','DU_Digital','Exicom_Inbond','FB','Jaipur','Naresh','OBD','RupeeRedee','Sales') group by user,date(event_time)";
$alert5 = mysqli_query($db6,$qry5);


        $html = "<table cellspacing='0' border='1'>";

        $html .= "<tr>";
        $html .= "<th>Agent</th>";
        $html .= "<th>Emp code</th>";
        $html .= "<th>Number of calls</th>";
        $html .= "<th>Total login hours</th>";
        $html .= "</tr>";

    $html1 = "<table cellspacing='0' border='1'>";
    $html1 .= "<tr>";
    $html1 .= "<th>Agent</th>";
    $html1 .= "<th>Emp code</th>";
    $html1 .= "<th>Number of calls</th>";
    $html1 .= "<th>Aht</th>";
    $html1 .= "<th>Talktime</th>";
    $html1 .= "</tr>";

while($Data5 = mysqli_fetch_assoc($alert5))
{
    //print_r($Data5);die;
    if(strtotime($Data5['talk_sec']) < strtotime('04:00') && strtotime($Data5['login_time']) < strtotime('08:00'))
    {
        //alert_mechanism5($Data5['talk_sec'],$Data5['login_time'],$condition2,$db2,$db6,$row['company_name']);
        $float = true;
        $html .= "<tr>
                <th>{$Data5['full_name']}</th>
                <th>{$Data5['user']}</th>
                <th>{$Data5['calls']}</th>
                <th>{$Data5['login_time']}</th>
           </tr>";

           $html1 .= "<tr>
           <th>{$Data5['full_name']}</th>
           <th>{$Data5['user']}</th>
           <th>{$Data5['calls']}</th>
           <th></th>
           <th>{$Data5['talk_sec']}</th>
      </tr>";           

      
    }
}
$html1 .= "</table>";
$html .= "</table>";
if($float)
{
    alert_mechanism5($html,$html1,$db1);
}





function alert_mechanism1($html,$db1)
{ 
    $qry = "select * from alert_mechanism where type='alert1' limit 1";
    $alertdata = mysql_query($qry,$db1);
    $alert_mail =  mysql_fetch_assoc($alertdata);



    $last_day_date = date('d/m/Y',strtotime("-1 days"));    
    $EmailText      ='';
 
    $to = explode(',',$alert_mail['to']);
    $cc = explode(',',$alert_mail['cc']);
    $bcc = explode(',',$alert_mail['bcc']);

    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject        = "CLIENTS WITH AL LESS THAN 95% - ($last_day_date) "; 
    $EmailText      .="<div style='border: 1px solid silver;width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";
    $EmailText  .= "<p>DEAR ALL,</p>";
    $EmailText  .= "<p>PLS FIND LIST OF CLIENTS WHOSE AL IS LESS THAN 95% FOR DD/MM/YY - $last_day_date</p>";
    $EmailText  .= $html;
    $EmailText  .= "<p>REGARDS,</p>";
    $EmailText  .= "<p>TEAM ISPARK</p>";

    //$emaildata = array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText);
    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $to,'AddCc'=>$cc,'AddBcc'=>$bcc);
    $done = send_email($emaildata);

    if($done=='1'){
        mysql_query("insert alert_mechanism_log SET type='{$alert_mail['type']}',alert_id='{$alert_mail['id']}',mail_id='{$alert_mail['to']}',MailStatus='Success',MailDateTime=NOW()",$db1);
        //$execute = mysqli_query($db2,$select);
    }


     //echo $html;die;


}

function alert_mechanism2($client_name,$open_bal,$amount_date,$db1,$waiver)
{ 

    $waiver_bal = $waiver['bal'];

    $total_waiver = $amount_date+$waiver_bal;

    $qry = "select * from alert_mechanism where type='alert2' limit 1";
    $alertdata = mysql_query($qry,$db1);
    $alert_mail =  mysql_fetch_assoc($alertdata);
    
    
    $EmailText      ='';
    //$ReceiverEmail  = array('Email'=>$email,'Name'=>$name); 
    //$ReceiverEmail  = array('Email'=>'bhanu.singh@teammas.in','Name'=>'bhanu','Email'=>'ispark@teammas.in','Name'=>'teammas'); 
    //$to = array('bhanu.singh@teammas.in');
    // $to = array('saurabh.bindal@teammas.in','himanshu.sharma1@teammas.in');
    // $cc = array('varuna.raghav@teammas.in','urvi.wadhwa@teammas.in');

    $to = explode(',',$alert_mail['to']);
    $cc = explode(',',$alert_mail['cc']);
    $bcc = explode(',',$alert_mail['bcc']);

    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject        = "PENDING INVOICES AGAINST RECEIVED PAYMENTS"; 
    $EmailText      .="<div style='border: 1px solid silver;width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";
    $EmailText  .= "<p>DEAR ALL,</p>";
    $EmailText  .= "<p>Pls find the invoices that are pending to be created:</p>";
    $EmailText  .= "<table>";
    $EmailText .= "<tr>";
    $EmailText .= "<th>Client Name</th>";
    $EmailText .= "<th>Amount</th>";
    $EmailText .= "<th>Payment Received date</th>";
    $EmailText .= "<th>Waiver</th>";
    $EmailText .= "</tr>";
    $EmailText .= "<tr>";
    $EmailText .= "<td>{$client_name}</td>";
    $EmailText .= "<td>{$open_bal}</td>";
    $EmailText .= "<td>{$amount_date}</td>";
    $EmailText .= "<td>{$total_waiver}</td>";
    $EmailText .= "</tr>";

    $EmailText  .= "</table>";
    $EmailText  .= "<p>REGARDS,</p>";
    $EmailText  .= "<p>TEAM ISPARK</p>";

    //$emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText);
    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $to,'AddCc'=>$cc,'AddBcc'=>$bcc);
    $done = send_email($emaildata);

    if($done=='1'){
        mysql_query("insert alert_mechanism_log SET type='{$alert_mail['type']}',alert_id='{$alert_mail['id']}',mail_id='{$alert_mail['to']}',MailStatus='Success',MailDateTime=NOW()",$db1);
        //$execute = mysqli_query($db2,$select);
    }

 
    // echo $html;die;
    // return $html; 
            
        //mail_send($html,'aband_mis_'.$reptype,$name,$emailId,$clientId,'Abend call mis '.$reptype);

}

function alert_mechanism3($client_id,$company_name,$db2,$db1)
{ 

    $qry = "select * from alert_mechanism where type='alert3' limit 1";
    $alertdata = mysql_query($qry,$db1);
    $alert_mail =  mysql_fetch_assoc($alertdata);

    $qry="select count(1) as calls,CallDate from call_master where ClientId = '$client_id'"; 
    $data  = mysqli_query($db2,$qry);

    $row = mysqli_fetch_assoc($data);
    $call_count = $row['calls'];
    $date = $row['CallDate'];
     
    if($call_count != '0')
    {

    
        $EmailText      ='';
        //$ReceiverEmail  = array('Email'=>$email,'Name'=>$name); 
        //$ReceiverEmail  = array('Email'=>'bhanu.singh@teammas.in','Name'=>'bhanu'); 
        //$to = array('bhanu.singh@teammas.in');
        // $to = array('saurabh.bindal@teammas.in','himanshu.sharma1@teammas.in');
        // $cc = array('varuna.raghav@teammas.in','urvi.wadhwa@teammas.in');

        $to = explode(',',$alert_mail['to']);
        $cc = explode(',',$alert_mail['cc']);
        $bcc = explode(',',$alert_mail['bcc']);

        $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
        $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
        $Subject        = "CALLS STARTED BUT BILLING NOT STARTED"; 
        $EmailText      .="<div style='border: 1px solid silver;width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";
        $EmailText  .= "<p>DEAR ALL,</p>";
        $EmailText  .= "<p>Pls check the issue as CDR report of $company_name shows $call_count calls on $date but the billing statement is coming zero </p>";

        $EmailText  .= "<p>REGARDS,</p>";
        $EmailText  .= "<p>TEAM ISPARK</p>";

        $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $to,'AddCc'=>$cc,'AddBcc'=>$bcc);
        $done = send_email($emaildata);

        if($done=='1'){
            mysql_query("insert alert_mechanism_log SET type='{$alert_mail['type']}',alert_id='{$alert_mail['id']}',mail_id='{$alert_mail['to']}',MailStatus='Success',MailDateTime=NOW()",$db1);
            //$execute = mysqli_query($db2,$select);
        }
    }
 

}

function alert_mechanism4($condition,$clientId,$campaignId,$condition2,$name,$emailId,$db1,$db6,$client_name,$db1)
{ 

    $qry = "select * from alert_mechanism where type='alert1' limit 1";
    $alertdata = mysql_query($qry,$db1);
    $alert_mail =  mysql_fetch_assoc($alertdata);

  $qry="SELECT count(*) Offer,t2.campaign_id ,SUM(IF(t2.user!='VDCL',1,0)) `Answered`,SUM(IF(t2.user='VDCL',1,0)) `Abandon`
    FROM asterisk.vicidial_closer_log t2
    LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid  AND t1.lead_id!='' AND t2.user=t1.user
    LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND $condition GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
    WHERE $condition2 and $campaignId group by t2.campaign_id"; 

    // $this->vicidialCloserLog->useDbConfig = 'db2';
    // $dtr=$this->vicidialCloserLog->query($qry);

    $execute = mysqli_query($db6,$qry);

    //$execute = mysql_query($qry,$db1);
    //print_r($execute); exit;
    


        $html = "<table cellspacing='0' border='1'>";

        $html .= "<tr>";
        $html .= "<th>Campaign Id</th>";
        $html .= "<th>Calls Offered</th>";
        $html .= "<th>Calls Handled</th>";
        $html .= "<th>Calls Abandoned</th>";
        $html .= "</tr>";
      
   
        while($row = mysqli_fetch_assoc($execute)){
            $html .= "<tr>";
                $html .= "<td>".$client_name."</td>";
                $html .= "<td>".$row['Offer']."</td>";
                $html .= "<td>".$row['Answered']."</td>";
                $html .= "<td>".$row['Abandon']."</td>";
            $html .= "</tr>";
        }
        
    $html .= "</table>";
    $last_day_date = date('d/m/Y',strtotime("-1 days"));    
    $EmailText      ='';
    //$ReceiverEmail  = array('Email'=>$email,'Name'=>$name); 
    $ReceiverEmail  = array('Email'=>'bhanu.singh@teammas.in','Name'=>'bhanu'); 
    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject        = "CLIENTS WITH AL LESS THAN 95% - ($last_day_date) "; 
    $EmailText      .="<div style='border: 1px solid silver;width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";
    $EmailText  .= "<p>DEAR ALL,</p>";
    $EmailText  .= "<p>PLS FIND LIST OF CLIENTS WHOSE AL IS LESS THAN 95% FOR DD/MM/YY - $last_day_date</p>";
    $EmailText  .= $html;
    $EmailText  .= "<p>REGARDS,</p>";
    $EmailText  .= "<p>TEAM ISPARK</p>";

    $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText);
    //$done = send_email($emaildata);

 
    // echo $html;die;
    // return $html; 
            
        //mail_send($html,'aband_mis_'.$reptype,$name,$emailId,$clientId,'Abend call mis '.$reptype);

}

function alert_mechanism5($html,$html1,$db1)
{ 

    $qry = "select * from alert_mechanism where type='alert4' limit 1";
    $alertdata = mysql_query($qry,$db1);
    $alert_mail =  mysql_fetch_assoc($alertdata);

    $EmailText      ='';
    //$ReceiverEmail  = array('Email'=>$email,'Name'=>$name); 
    //$ReceiverEmail  = array('Email'=>'bhanu.singh@teammas.in','Name'=>'bhanu'); 
    //$to = array('bhanu.singh@teammas.in');
    // $to = array('tl@teammas.in','himanshu.bhatt@teammas.in','saurabh.bindal@teammas.in','dipak.ojha@teammas.in','himanshu.sharma1@teammas.in');
    // $cc = array('varuna.raghav@teammas.in','urvi.wadhwa@teammas.in');

    $to = explode(',',$alert_mail['to']);
    $cc = explode(',',$alert_mail['cc']);
    $bcc = explode(',',$alert_mail['bcc']);

    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject        = "AGENT PERFORMANCE ALERT - LOW TALKTIME AND LESS LOGIN HOURS"; 
    $EmailText  .="<div style='border: 1px solid silver;width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";
    $EmailText  .= "<p>DEAR ALL,</p>";
    $EmailText  .= "<p>Pls find the agents who have login hours less than 8 hours: </p>";
    $EmailText  .= $html;
    $EmailText  .= '<br>';
    $EmailText  .= "Agents with less talktime are:";
    $EmailText  .= $html1;

    $EmailText  .= "<p>REGARDS,</p>";
    $EmailText  .= "<p>TEAM ISPARK</p>";
    $EmailText  .= "</div>";


    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $to,'AddCc'=>$cc,'AddBcc'=>$bcc);
    //$done = send_email($emaildata);

    if($done=='1'){
        mysql_query("insert alert_mechanism_log SET type='{$alert_mail['type']}',alert_id='{$alert_mail['id']}',mail_id='{$alert_mail['to']}',MailStatus='Success',MailDateTime=NOW()",$db1);
        //$execute = mysqli_query($db2,$select);
    }

 
     //echo $EmailText;die;
    // return $html; 
            
        //mail_send($html,'aband_mis_'.$reptype,$name,$emailId,$clientId,'Abend call mis '.$reptype);

}



function get_credit_from_subs_value($rental,$balance,$subsvalue)
{
    
    $plan_pers = $balance/$rental; 
    //echo $plan_pers;exit;
     $creditvalue = round(($subsvalue*$plan_pers),2);
    //echo $creditvalue;exit
    return $creditvalue;
}
?>


