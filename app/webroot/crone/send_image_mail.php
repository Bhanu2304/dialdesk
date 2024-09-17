<?php
$db1=mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

$db2 = mysqli_connect("localhost","root","dial@mas123") or die(mysqli_error($db2));
mysqli_select_db($db2,"db_dialdesk")or die("cannot select DB dialdesk");



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



include('report-send.php');
//include('function_alert_mechanism.php');
$html = "<table cellspacing='0' border='1'>";

$html .= "<tr>";
$html .= "<th>Number</th>";
$html .= "<th>Image</th>";
$html .= "<th>Send date</th>";
$html .= "</tr>";

$qry = "select * from agent_whatsapp_log where type='image' and  image is not null and mail_status='0'";
$image_qry = mysql_query($qry,$db1);

$is_float_send = false;
$entry_list = array();
while($image_data = mysql_fetch_assoc($image_qry))
{
    $entryid = $image_data['id'];
    $entry_list[] = $entryid;

    $number = $image_data['phone_no'];
    $image_url = $image_data['image'];
    $send_date = $image_data['created_at'];

    $html .= "<tr>
                    <td>{$number}</td>
                    <td><a href='$image_url'>Click Here To See Image.</a></td>
                    <td>{$send_date}</td>
              </tr>";

    $is_float_send = true;
    
}
$html .= "</table>";


if($is_float_send)
{
    send_image_mail($html,$entry_list,$db1);
}

function send_image_mail($html,$entry_list,$db1)
{

    $qry = "select * from image_mail limit 1";
    $mail_qry = mysql_query($qry,$db1);
    $mail_data =  mysql_fetch_assoc($mail_qry);

    $to = explode(',',$mail_data['image_to']);
    $cc = explode(',',$mail_data['image_cc']);
    $bcc = explode(',',$mail_data['image_bcc']);

    
    $SenderEmail    = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail     = array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject        = "Images"; 
    $EmailText      ="<div style='border: 1px solid silver;width:550px;height:auto;pxposition: relative;top:18;left:22px;'>";
    $EmailText  .= "<p>DEAR ALL,</p>";
    $EmailText  .= "<p>Pls find the image</p>";
    $EmailText  .= $html;
    $EmailText  .= "<p>REGARDS,</p>";
    $EmailText  .= "<p>TEAM ISPARK</p>";

    $emaildata=array('SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'AddTo'=> $to,'AddCc'=>$cc,'AddBcc'=>$bcc);
    $done = send_email($emaildata);

    if($done=='1'){
        $entry_list = implode(",",$entry_list);
        $upd = "update agent_whatsapp_log set mail_status='1' where mail_status='0' and id in ($entry_list)";
        $rsc_update = mysql_query($upd,$db1);
        mysql_query("insert alert_mechanism_log SET type='image_data',alert_id='{$mail_data['id']}',mail_id='{$mail_data['image_to']}',MailStatus='Success',MailDateTime=NOW()",$db1);
        //$execute = mysqli_query($db2,$select);
    }
}




?>


