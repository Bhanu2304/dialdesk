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
$client_info_arr = array();
while($row = mysql_fetch_assoc($export)){ 

    $condition1 = "DATE(parked_time)=DATE_SUB(CURDATE(),INTERVAL 1 DAY)";
    $condition2 = "DATE(t2.call_date)=DATE_SUB(CURDATE(),INTERVAL 1 DAY)";
    $condition3 = "DATE(t2.call_date)<DATE_SUB(CURDATE(),INTERVAL 1 DAY)";

    if(!empty($row['campaignid']))
    {
        $camaign_id = "t2.campaign_id in(".$row['campaignid'].")";
    
 
        $inbound_qry = "SELECT t2.`user` FROM asterisk.vicidial_closer_log t2  
                WHERE $condition2 and $camaign_id  limit 1";

        $outbound_qry = "SELECT t2.`user` FROM asterisk.vicidial_log t2  
            WHERE $condition2 and $camaign_id  limit 1";

        $excute_campaign = mysqli_query($db6,$inbound_qry);
        $dt =   mysqli_fetch_assoc($excute_campaign);

        $excute_out_campaign = mysqli_query($db6,$outbound_qry);
        $out_dt =   mysqli_fetch_assoc($excute_out_campaign);

        $is_call_receive = false;
        if(empty($dt))
        {
            //$client_info_arr[] = $row['company_name'];
            
            $is_call_receive = true;
            $in_qry1 = "SELECT max(t2.call_date) as last_call_date FROM asterisk.vicidial_closer_log t2  
                WHERE $camaign_id and $condition3  limit 1";

            $excute_query = mysqli_query($db6,$in_qry1);
            $last_date_arr =   mysqli_fetch_assoc($excute_query);


            //print_r($last_date_arr);
            if(!empty($last_date_arr['last_call_date']))
            {
                $client_info_arr[$row['company_name']]['last_call_date'] = date_format(date_create($last_date_arr['last_call_date']),'d-m-Y H:i:s');
                $in_float_alert5 = true;
            }
          
        }
        if(empty($out_dt))
        {
        
            $is_call_receive = true;
            $out_qry1 = "SELECT max(t2.call_date) as last_call_date FROM asterisk.vicidial_log t2  
               WHERE $camaign_id and $condition3  limit 1";

             $excute_out_query = mysqli_query($db6,$out_qry1);
             $out_last_date_arr =   mysqli_fetch_assoc($excute_out_query);
           

            //print_r($last_date_arr);
            if(!$is_call_receive && !empty($out_last_date_arr['last_call_date']))
            {
                $client_info_arr[$row['company_name']]['last_call_date'] = date_format(date_create($out_last_date_arr['last_call_date']),'d-m-Y H:i:s');
                $out_float_alert5 = true;
            }
          
        }
    }
    

    
}

if($in_float_alert5 || $out_float_alert5)
{
    //echo $htmlalert1;die;
    // echo "hello";
    // print_r($client_info_arr);
    alert_mechanism5($client_info_arr,$db1);
}
//print_r($client_info_arr);

function alert_mechanism5($data_Arr,$db1)
{

    $qry = "select * from alert_mechanism where type='alert5' limit 1";
    $alertdata = mysql_query($qry,$db1);
    $alert_mail =  mysql_fetch_assoc($alertdata);

    $to = explode(',',$alert_mail['to']);
    $cc = explode(',',$alert_mail['cc']);
    $bcc = explode(',',$alert_mail['bcc']);

    // $to = array("anil.goar@teammas.in");
    // $cc = array("bhanu.singh@teammas.in");
    // $bcc = array("krishna.kumar@teammas.in");

    $last_day_date = date('d-m-Y',strtotime("-1 days")); 


    $htmlalert5 = "<table cellspacing='0' border='1'>";

    $htmlalert5 .= "<tr>";
    $htmlalert5 .= "<th>Client Name</th>";
    $htmlalert5 .= "<th>Last Call Offered Date</th>";
    $htmlalert5 .= "</tr>";

    foreach($data_Arr as $data=>$key)
    {
        //print_r($key);die;
        $htmlalert5 .= "<tr>
                         <td>{$data}</td>
                         <td>{$key['last_call_date']}</td>
                        </tr>";
    }

    $htmlalert5 .= "</html>";
    
    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject        = "Clients who offered ZERO Calls - ($last_day_date) "; 
    $EmailText      .="<div style='border: 1px solid silver;width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";
    $EmailText  .= "<p>DEAR ALL,</p>";
    $EmailText  .= "<p>Pls find the details of clients who offered ZERO calls yesterday</p>";
    $EmailText  .= $htmlalert5;
    $EmailText  .= "<p>REGARDS,</p>";
    $EmailText  .= "<p>TEAM ISPARK</p>";

    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $to,'AddCc'=>$cc,'AddBcc'=>$bcc);
    $done = send_email($emaildata);

    if($done=='1'){
        mysql_query("insert alert_mechanism_log SET type='{$alert_mail['type']}',alert_id='{$alert_mail['id']}',mail_id='{$alert_mail['to']}',MailStatus='Success',MailDateTime=NOW()",$db1);
        //$execute = mysqli_query($db2,$select);
    }
}




?>


