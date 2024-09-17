<?php
include_once('send_sms.php');
include_once('send_email.php');

$db = mysql_connect("localhost","root","dial@mas123","false",128);
mysql_select_db("db_dialdesk",$db)or die("cannot select DB");

$qry=mysql_query("SELECT * FROM `balance_master` WHERE PlanType='Prepaid'",$db);

while($result=  mysql_fetch_assoc($qry)){
        
    $UsedBalance    =   round($result['Used']*100/$result['MainBalance']);
    if($UsedBalance >= 75){
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
        
        $balance_status =   "";
        
        if($UsedBalance >= 75 && $UsedBalance < 85 ){
            $num['SmsText'] = "Your OTP for logging in is 75.";
            $balance_status = "75";
        }
        else if($UsedBalance >= 85 && $UsedBalance < 95 ){
            $num['SmsText'] = "Your OTP for logging in is 85.";
            $balance_status = "85";
        }
        else if($UsedBalance >= 95 && $UsedBalance < 98 ){
            $num['SmsText'] = "Your OTP for logging in is 95.";
            $balance_status = "95";
        }
        else if($UsedBalance >= 98 && $UsedBalance < 100){
            $num['SmsText'] = "Your OTP for logging in is 98.";
            $balance_status = "98";
        }
        else if($UsedBalance >= 100){
            $num['SmsText'] = "Your OTP for logging in is 100.";
            $balance_status = "100";
        }
       
        
        $CurDate = date('d-M-Y');
        $EmailText ='';
        $ReceiverEmail=array('Email'=>$emailId,'Name'=>$name); 
        $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
        $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
        $Subject="Intimation of Account Usage"; 
        $EmailText .="<p>Date: $CurDate</p>"; 
        $EmailText .="<p>To,</p>";
        $EmailText .="<p>Customer Name : $name</p>"; 
        $EmailText .="<p>Organization  <span style='margin-left:25px;'>:</span> $companyName</p>";
        $EmailText .="<p><b>Subject :Intimation of Account Usage</b></p>";
        $EmailText .="<p><b>Dear Sir/Madam,</b></p>";
        $EmailText .="<p>Greetings from DIALDESK.</p>";
        $EmailText .="<p>We wish to bring to your notice that the usage of Rs {$result['Used']}/- on your account is nearing $balance_status% of the available value for this account. We request you kindly make an interim payment to enjoying our services.</p>";
        $EmailText .="<p>For further queries, please feel free to contact your Key Account Manager.</p>";
        $EmailText .="<p>Thanks & Regards<br/>Customer Care</p>";
        $EmailText .="<p><img src='http://dialdesk.in/dialdesk/css/assets/img/logo.png' style='width:100px;' ></p>";

        $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText);
        $num['ReceiverNumber'] = $PhpneNo;
       
        if($balance_status !=""){
            $exist_sms=mysql_query("SELECT * FROM alert_used_balance WHERE ClientId='{$result['clientId']}' AND BalanceStatus='$balance_status' AND AlertType='Charge Balance' AND SendType='sms'");
            if(empty(mysql_fetch_array($exist_sms))){
                $sms=send_sms($num);
                if($sms){
                    mysql_query("INSERT INTO alert_used_balance SET ClientId='{$result['clientId']}',BalanceStatus='$balance_status',AlertType='Charge Balance',SendType='sms',CreateDate=NOW()");
                }
            }
            
            $exist_email=mysql_query("SELECT * FROM alert_used_balance WHERE ClientId='{$result['clientId']}' AND BalanceStatus='$balance_status' AND AlertType='Charge Balance' AND SendType='email'");
            if(empty(mysql_fetch_array($exist_email))){
                $email=send_email( $emaildata);
                if($email){
                    mysql_query("INSERT INTO alert_used_balance SET ClientId='{$result['clientId']}',BalanceStatus='$balance_status',AlertType='Charge Balance',SendType='email',CreateDate=NOW()");
                }
            } 
        }
        
        }

}
exit;
?>


