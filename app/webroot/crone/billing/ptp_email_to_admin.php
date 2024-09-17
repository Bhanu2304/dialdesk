<?php
include_once('send_email.php');

$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$qry=mysql_query("SELECT * FROM `renewal_plan_master` WHERE `paymentTypes`='PTP' AND `paymentReceive`='No'",$db);


while($result=  mysql_fetch_assoc($qry)){
    
    $ClientDetails  =   mysql_fetch_assoc(mysql_query("SELECT * FROM `registration_master` WHERE company_id='{$result['clientId']}' limit 1",$db));
                
    $name           =   "Chandresh";
    $emailId        =   "chandresh.tripathi@teammas.in";
    $companyName    =   $ClientDetails['company_name'];

    $CD = date('Y-m-d');
    $ND3=date('Y-m-d', strtotime('-2 day', strtotime($result['paymentDate'])));
    
    if(strtotime($CD) >= strtotime($ND3)){
        $EmailText ='';
        $ReceiverEmail=array('Email'=>$emailId,'Name'=>$name); 
        $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
        $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
        $Subject="PTP Expire"; 
        $EmailText .="<p>To,</p>";
        $EmailText .="<p>Admin : $name</p>"; 
        $EmailText .="<p>Client : $companyName</p>"; 
        $EmailText .="<p>Expire you ptp days on ".date('Y-m-d', strtotime($result['paymentDate']))."</p>"; 
         
        $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText);
        $email=send_email( $emaildata);   
    }
       
}
exit;
?>


