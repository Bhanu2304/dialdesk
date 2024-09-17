<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


// mysql_connect('localhost','root','dial@mas123',TRUE);
// mysql_select_db('db_dialdesk') or die('unable to connect');

$db2 = mysqli_connect("localhost","root","dial@mas123") or die(mysqli_error($db2));
mysqli_select_db($db2,"db_dialdesk")or die("cannot select DB dialdesk");

include('report-send.php');


$select = "SELECT * FROM `sim_management` WHERE date(dateofalert)=curdate()";
$execute = mysqli_query($db2,$select);
//print_r($execute);die;

$float = false;
$html = "<table cellspacing='0' border='1'>
            <tr>
                <th>Contact Number</th>
                <th>Process Name</th>
                <th>Purpose</th>
                <th>Service Provider</th>
                <th>Recharge Date</th>
                <th>Validity</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>";
while($row = mysqli_fetch_assoc($execute)){
    //print_r($row['id']);
    #send_link($row['id']);
    $float = true;
    $html .= "<tr>
                <th>{$row['contactnumber']}</th>
                <th>{$row['processname']}</th>
                <th>{$row['purpose']}</th>
                <th>{$row['serviceprovider']}</th>
                <th>{$row['rechargedate']}</th>
                <th>{$row['validity']}</th>
                <th>{$row['amountofrecharge']}</th>
                <th>{$row['statusofsim']}</th>
             </tr>";
}

  $html .= "</table>";

if($float)
{
    send_link($db2,$html);
}
 

function send_link($db2,$html)
{

    
        
    $EmailText      ='';
    $ReceiverEmail  = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject        = "Sim Alert For Recharge"; 
    $EmailText     .= $html;
                        
            $sim_data = "SELECT * FROM sim_alert_master limit 1";
            $sim_Arr = mysqli_query($db2,$sim_data);
            $row = mysqli_fetch_assoc($sim_Arr);

            $to = explode(',',$row['sim_to']);
            $cc = explode(',',$row['sim_cc']);
            $bcc = explode(',',$row['sim_bcc']);

            $EmailText      .= $remarks = $row['remarks'];


    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $to,'AddCc'=>$cc,'AddBcc'=> $bcc);

    //print_r($emaildata);
    $done = send_email($emaildata);
    if($done=='1'){
        mysqli_query($db2,"insert sim_alert_log SET sim_alert_id='{$row['id']}',MailStatus='Success',MailDateTime=NOW()");
        //$execute = mysqli_query($db2,$select);
    }else{
        mysqli_query($db2,"insert sim_alert_log SET sim_alert_id='{$row['id']}',MailStatus='Fail',MailDateTime=NOW()");
    }
    
}




