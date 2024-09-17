<?php
$db1=mysql_connect('182.71.80.196','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk',$db1) or die('unable to connect');

mysql_connect('192.168.1.5','root','vicidialnow',TRUE);
mysql_select_db('asterisk') or die('unable to connect');

include('report-send.php');

$export = mysql_query("SELECT * FROM reportmail_master",$db1);
while($row = mysql_fetch_assoc($export)){
    if($row['id'] !=""){
        mail_send2($row['filename'],$row['name'],$row['email'],$row['clientid'],$row['subject']);
        mysql_query("delete FROM reportmail_master where id='{$row['id']}' and clientid='{$row['clientid']}'",$db1);
    }
}
          
function mail_send2($filename,$name,$emailId,$clientId,$sub){
    $EmailText ='';
    $email = $emailId;
    $name = $name;
    
    $ReceiverEmail=array('Email'=>$email,'Name'=>$name); 
    $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Attachment=array( $filename); 
    $Subject="$sub report export"; 
    $EmailText .="<table><tr><td style=\"padding-left:12px;\">Hello $name</td></tr>"; 
    $EmailText .="<tr><td style=\"padding-left:12px;\">$smsText</td></tr>";
    $EmailText .="</table>"; 
    $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'Attachment'=>$Attachment);
    
    try
    {
        $done = send_email( $emaildata);
    }
    catch (Exception $e)
    {
        $error = $e.printStackTrace();
        $updQry = "insert into error_master(data_id,process,error_msg,createdate) values('{$row['data_id']}','{$row['bpo']}','{$error}',now())";
        mysql_query($updQry,$db1);
    }
    if($done=='1')
    {
        $msg =  "Mail Sent Successfully !";        
        
        $updQry2 = "insert into mail_log_report(clientId,mail_status,mail_date) "
                . "values('$clientId','$done',now()) ";
        mysql_query($updQry2,$db1);
    } 
}

