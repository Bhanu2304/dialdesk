<?php

require_once('class.phpmailer.php');

function send_email($emaildata)
{
	$mailBody = $emaildata['EmailText'];
	$mailBody = str_replace("\\",'',$mailBody);

	// Send Email
	$mail = new PHPMailer();

	$mail->IsHTML(true);
	$mail->IsSMTP();

	$mail->Host 	 = "smtp.teammas.in";
	$mail->SMTPDebug = 1; 
	$mail->SMTPAuth  = true;
	$mail->Host      = "smtp.teammas.in";
	$mail->Port      = 587;
	$mail->Username  = "ispark@teammas.in";
	$mail->Password  = "abc@123#1";
	

	$mail->SetFrom($emaildata['SenderEmail']['Email'],$emaildata['SenderEmail']['Name']);
	$mail->AddReplyTo($emaildata['ReplyEmail']['Email'],$emaildata['ReplyEmail']['Name']);
	$mail->Subject = $emaildata['Subject'];

	$mail->AltBody = "To view the message, please use an HTML compatible email viewer!";
	$mail->MsgHTML($mailBody);
	
	$mail->AddAddress($emaildata['ReceiverEmail']['Email']);
	if(array_key_exists('AddTo',$emaildata))
	{
		$addto=$emaildata['AddTo'];
		foreach($addto as $v) { $mail->AddAddress($v); }
	}	
	if(array_key_exists('AddCc',$emaildata))
	{
		$addcc=$emaildata['AddCc'];
		foreach($addcc as $v) { if($v!='') { $mail->AddCC($v); } }
	}
	if(array_key_exists('AddBcc',$emaildata))
	{
		$addbcc=$emaildata['AddBcc'];
		foreach($addbcc as $v) { if($v!='') { $mail->AddBCC($v); } }
	}
	//if($emaildata['Attachment']!="") { $mail->AddAttachment($emaildata['Attachment']); }
	if(array_key_exists('Attachment',$emaildata))
	{
		$addattachment=$emaildata['Attachment'];
		if(is_array($emaildata['Attachment']))
		{
		foreach($addattachment as $v) { $mail->AddAttachment($v); }
		}
		else
		{
		 $mail->AddAttachment($emaildata['Attachment']);
		}
	}
	
	if(!$mail->Send()) { $msg="Mailer Error: " . $mail->ErrorInfo; } 
	else { $msg= "1";}

	return $msg; 
}

function send_sms($smsdata)
{
	$ReceiverNumber=$smsdata['ReceiverNumber'];
	$len=strlen($ReceiverNumber);
	$ReceiverNumber=substr($ReceiverNumber,$len-10,10);

	if(strlen($ReceiverNumber)<11) { $ReceiverNumber='91'.$ReceiverNumber; }

	$SmsText=$smsdata['SmsText'];

	$postdata = http_build_query(
	array(
		'uname'=>'MasCall',
		'pass'=>'M@sCaLl@234',
		'send'=>'Ispark',
		'dest'=>$ReceiverNumber,
		'msg'=>$SmsText
	)
	);
	
	$opts = array('http' =>
	array(
		'method'  => 'POST',
		'header'  => 'Content-type: application/x-www-form-urlencoded',
		'content' => $postdata
	)
	);
	
	$context  = stream_context_create($opts);
	
	return $result = file_get_contents('http://www.unicel.in/SendSMS/sendmsg.php', false, $context);
	
	/*if($result) { $msg="SMS Sent Successfully"; } else { $msg="SMS Sending Fail"; }
	return $msg;*/
}

function sec_to_time($seconds)
{
	$dataHour=floor($seconds/3600);
	$dataMinute=floor(($seconds-$dataHour*3600)/60);
	$dataSecond=$seconds-($dataHour*3600+$dataMinute*60);
	
	$dataHour=$dataHour<10?'0'.$dataHour:$dataHour;
	$dataMinute=$dataMinute<10?'0'.$dataMinute:$dataMinute;
	
	return "$dataHour:$dataMinute";
}

// Input Paramater : HH:MM:SS
function time_to_sec($seconds)
{
	$tmp=explode(":",$seconds);
	
	$dataHour=$tmp[0]*60*60;
	$dataMinute=$tmp[1]*60;
	$dataSecond=$tmp[2];

	$tmp=$dataHour+$dataMinute+$dataSecond;
	return $tmp;
}

/*
function mail_info($EmailName)
{
	$UserId=$_REQUEST['User'];
	if($UserId=='')
	{
		$UserId="System Generated";
	}
	$CurrTime=date('Y-m-d H:i:s');
	$Qr="inser into ApolloEmailSend (SendDate,UserId,EmailName) values('$CurrTime','$UserId','$EmailName')";
	$Rsc=mssql_query($Qr);
	return "$Rsc";
}

*/

function get_numerics($str) {
	preg_match_all('/\d+/', $str, $matches);
	return $matches[0];
}

function get_user($ClientId)
{
	if($ClientId!='') { $subqry=" && ClientId=".$ClientId; } else { $subqry=""; } 

	$user[0]="All";
	$qry="Select * from dms.tbl_userlogin Where UserType=2 && active=1 $subqry";
	$rsc=mysql_query($qry);
	while($dt=mysql_fetch_assoc($rsc))
	{
		$idx=$dt['UserName'];
		$user[$idx]=$dt['UserDisplayName'];
	}
	
	return $user;
}

?>