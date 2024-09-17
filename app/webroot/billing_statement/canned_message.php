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


$select_risk = "SELECT * FROM canned_message WHERE (client_id,percent) IN ( SELECT client_id, MAX(percent) FROM canned_message
  GROUP BY client_id)";
$rsc_risk = mysqli_query($dd,$select_risk);
//print_r($rsc_risk);die;
while($risk = mysqli_fetch_assoc($rsc_risk))
{
    $clientId   = $risk['client_id'];
    $percent = $risk['percent'];
    // $sel_sent_status = "SELECT * FROM `billing_risk_exposure_mail_status` WHERE clientId='$clientId' AND date(mail_date)=CURDATE()";
    // $rsc_sent = mysqli_query($dd,$sel_sent_status);
    // $sent_det = mysqli_fetch_assoc($rsc_sent);
    // if(!empty($sent_det))
    // {
    //     continue;
    // }


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

    $send_email = true;
    $ClientRsc = mysqli_query($dd,"select * from registration_master where company_id='$clientId' limit 1");
    $ClientMaster = mysqli_fetch_assoc($ClientRsc);
    $table_trigger_detail = "<table border=\"2\"><tr> <th>Client Name</th><td>".$ClientMaster['company_name']."</td></tr>";
    $table_trigger_detail .= "<tr> <th>Risk %</th><td>".$percent."</td></tr>";
    $table_trigger_detail .= "</table>"; 
    $Subject="Risk Management ".date('d/M/Y');
    $AddCc = explode(',',$risk['email_cc']);
    $EmailText = $Subject;
    $EmailText .="<br/>";
    $EmailText .="<br/>";
    $EmailText .= $risk['remarks'];
    $EmailText .="<br/>";
    $EmailText .="<br/>";
    $EmailText .= $table_trigger_detail;
    $EmailText .="<br/>";
    $EmailText .="<br/>";
    $EmailText .="This is a auto-generated mail.";
    $EmailText .="<br/>";
    $risk_remarks = addslashes($EmailText);
    $ReceiverEmail=array('Email'=>$To,'Name'=>''); 
    $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
    
    $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'EmailText'=> $EmailText);
    
    //print_r($send_email);die;
    if($send_email)
    {
        
        $emaildata['Subject'] =  $Subject;
        
            if(!empty($AddCc))
            {
                $emaildata['AddCc'] =  $AddCc;
            }
            if(!empty($AddTo))
            {
                $emaildata['AddTo'] =  $AddTo;
            }
            
           $done = send_email($emaildata); 
        //    if($done)
        //    {
        //        $insert = "INSERT INTO `billing_risk_exposure_mail_status` SET clientId='$clientId',percent='$percent',calc_percent='$calc_perc',mail_date=now(),sent_status='$done',sent_text='$risk_remarks',today_closing_aot='$total_consume'";
        //        $save = mysqli_query($dd,$insert);
        //    }
        //    else
        //    {
        //        $insert = "INSERT INTO `billing_risk_exposure_mail_status` SET clientId='$clientId',percent='$percent',calc_percent='$calc_perc',mail_date=now(),sent_status='failed',sent_text='$risk_remarks',today_closing_aot='$total_consume'";
        //        $save = mysqli_query($dd,$insert);
        //    }
       
        // catch (Exception $e){
        //     $insert = "INSERT INTO `billing_risk_exposure_mail_status` SET clientId='$clientId',percent='$percent',calc_percent='$calc_perc',mail_date=now(),sent_status='failed',sent_text='$risk_remarks',today_closing_aot='$total_consume'";
        //        $save = mysqli_query($dd,$insert);
        // }
    }

}









