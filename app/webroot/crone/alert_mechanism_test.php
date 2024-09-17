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



function alert_mechanism1($html,$db1)
{ 
    $qry = "select * from alert_mechanism where type='alert1' limit 1";
    $alertdata = mysql_query($qry,$db1);
    $alert_mail =  mysql_fetch_assoc($alertdata);



    $last_day_date = date('d/m/Y',strtotime("-1 days"));    
    $EmailText      ='';
    //$ReceiverEmail  = array('Email'=>$email,'Name'=>$name); 
    //$ReceiverEmail  = array('Email'=>'bhanu.singh@teammas.in','Name'=>'bhanu'); 
    //$ReceiverEmail  = array('Email'=>'bhanu.singh@teammas.in','Name'=>'bhanu','Email'=>'ispark@teammas.in','Name'=>'teammas'); 
    // $to = array('tl@teammas.in','himanshu.bhatt@teammas.in','saurabh.bindal@teammas.in','dipak.ojha@teammas.in','himanshu.sharma1@teammas.in');
    // $cc = array('varuna.raghav@teammas.in','urvi.wadhwa@teammas.in');
    //$to = array('bhanu.singh@teammas.in');
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
    // $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $to,'AddCc'=>$cc,'AddBcc'=>$bcc);
    // $done = send_email($emaildata);

    // if($done=='1'){
    //     mysql_query("insert alert_mechanism_log SET type='{$alert_mail['type']}',alert_id='{$alert_mail['id']}',mail_id='{$alert_mail['to']}',MailStatus='Success',MailDateTime=NOW()",$db1);
    //     //$execute = mysqli_query($db2,$select);
    // }


     echo $html;die;


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


