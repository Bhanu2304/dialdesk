<?php
include_once('send_sms.php');
include_once('send_email.php');

$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$qry=mysql_query("SELECT * FROM `balance_master` WHERE PlanType='Prepaid'",$db);

while($result=  mysql_fetch_assoc($qry)){
    
    $renqry=  mysql_query("SELECT * FROM `renewal_plan_master` WHERE clientId='{$result['clientId']}' AND DATE(Plandate)='{$result['start_date']}'");
    $RenArr=mysql_fetch_array($renqry);
    
    if(empty($RenArr)){
    
    $ClientDetails  =   mysql_fetch_assoc(mysql_query("SELECT * FROM `registration_master` WHERE company_id='{$result['clientId']}' limit 1",$db));
                
    /*
    $name           =   $ClientDetails['auth_person'];
    $emailId        =   $ClientDetails['email'];
    $PhpneNo        =   $ClientDetails['phone_no'];
    $companyName    =   $ClientDetails['company_name'];
    */
    
    /*
    $name           =   "krishna kumar";
    $emailId        =   "krishna.kumar1@teammas.in";
    $PhpneNo        =   "9643323569";
    $companyName    =   "Mas Callnet";
    */
    $name           =   "Chandresh";
    $emailId        =   "chandresh.tripathi@teammas.in";
    $PhpneNo        =   "9312584606";
    $companyName    =   "Mas Callnet";
    
    $CD = date('Y-m-d');
    $FD = $result['start_date'];
    $LD = $result['end_date'];
    
    $ND1=date('Y-m-d', strtotime('-30 day', strtotime($LD)));
    $ND2=date('Y-m-d', strtotime('-23 day', strtotime($LD)));
    $ND3=date('Y-m-d', strtotime('-16 day', strtotime($LD)));
    $ND4=date('Y-m-d', strtotime('-9 day', strtotime($LD)));
    $ND5=date('Y-m-d', strtotime('-2 day', strtotime($LD)));
    
    $expiry_day =   "";

    if(strtotime($CD) == strtotime($ND1)){
        $num['SmsText'] = "Your OTP for logging in is 30.";
        $expiry_day = "30";
    }
    else if(strtotime($CD) == strtotime($ND2)){
        $num['SmsText'] = "Your OTP for logging in is 23.";
        $expiry_day = "23";
    }
    else if(strtotime($CD) == strtotime($ND3)){
        $num['SmsText'] = "Your OTP for logging in is 16.";
        $expiry_day = "16";
    }
    else if(strtotime($CD) == strtotime($ND4)){
        $num['SmsText'] = "Your OTP for logging in is 9.";
        $expiry_day = "9";
    }
    else if(strtotime($CD) == strtotime($ND5)){
        $num['SmsText'] = "Your OTP for logging in is 2.";
        $expiry_day = "2";
    }
    else if(strtotime($CD) == strtotime($LD)){
        $num['SmsText'] = "Your OTP for logging in is .";
        $expiry_day = "0";
    }
    
    $EmailText ='';
    $ReceiverEmail=array('Email'=>$emailId,'Name'=>$name); 
    $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Subject="Intimation of Validity Expiration"; 
    $EmailText .="<p>To,</p>";
    $EmailText .="<p>Customer Name : $name</p>"; 
    $EmailText .="<p>Organization  <span style='margin-left:25px;'>:</span> $companyName</p>";
    $EmailText .="<p><b>Subject :Intimation of Validity Expiration</b></p>";
    $EmailText .="<p><b>Dear Sir/Madam,</b></p>";
    $EmailText .="<p>Greetings from DIALDESK.</p>";
    $EmailText .="<p>We wish to bring to your notice that the validity of your bill plan is going to expire on ($LD). We request you kindly renew your plan before the due date to enjoying uninterrupted services.</p>";
    $EmailText .="<p>For further queries, please feel free to contact your Key Account Manager.</p>";
    $EmailText .="<p>Thanks & Regards<br/>Customer Care</p>";
    $EmailText .="<p><img src='http://dialdesk.in/dialdesk/css/assets/img/logo.png' style='width:100px;' ></p>";

    $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText);
    $num['ReceiverNumber'] = $PhpneNo;

    if($expiry_day !=""){
        $exist_sms=mysql_query("SELECT * FROM alert_used_balance WHERE ClientId='{$result['clientId']}' AND ExpiryStatus='$expiry_day' AND AlertType='Renewal Plan' AND SendType='sms'");
        if(empty(mysql_fetch_array($exist_sms))){
            $sms=send_sms($num);
            if($sms){
                mysql_query("INSERT INTO alert_used_balance SET ClientId='{$result['clientId']}',ExpiryStatus='$expiry_day',AlertType='Renewal Plan',SendType='sms',CreateDate=NOW()");
            }
        }

        $exist_email=mysql_query("SELECT * FROM alert_used_balance WHERE ClientId='{$result['clientId']}' AND ExpiryStatus='$expiry_day' AND AlertType='Renewal Plan' AND SendType='email'");
        if(empty(mysql_fetch_array($exist_email))){
            $email=send_email( $emaildata);
            if($email){
                mysql_query("INSERT INTO alert_used_balance SET ClientId='{$result['clientId']}',ExpiryStatus='$expiry_day',AlertType='Renewal Plan',SendType='email',CreateDate=NOW()");
            }
        } 
    }
    
    }
    
}
exit;
?>


