<?php
require_once('mailer/class.phpmailer.php');

function send_email($emaildata)
{
	
	mysql_connect('localhost','root','dial@mas123',false,128);
	mysql_select_db('db_dialdesk') or die('unable to connect');
	
	// $select	=	"SELECT * FROM crone_job WHERE DATE(createdate)=CURDATE() AND (alertOn='email' OR alertOn='both');";
	// $query	= 	mysql_query($select);
	// $numRow	=	mysql_num_rows($query);
	
	// if($numRow > 480){
	// 	$username	=	"dialdesknotification@teammas.in";
	// }
	// else{
	// 	$username	=	"dialdeskalert@teammas.in";
	// }

	$today_date = date("Y-m-d");

	$select_mail = "SELECT * FROM email_master";
	$mail_arr	= 	mysql_query($select_mail);

	while($mail =  mysql_fetch_assoc($mail_arr))
	{
		$id = $mail['id'];
		$email = $mail['email'];
		#$count = $mail['count'];

		$select_email = "SELECT * FROM email_tracker where email_id = '$email' and date(date)= curdate()";
		$email_arr	= 	mysql_query($select_email);
		$email_data =  mysql_fetch_assoc($email_arr);
		#$username	= $email_data['email'];
		$count = $email_data['count'];

		if($count < 249 && $count > 0)
		{
			$username = $mail['email'];
			break;
		}
		else if($count > 249)
		{
			$username = $mail['email'];
			continue;
		}
		else{
			$username = $mail['email'];
			break;
		}
		
		
	}
	
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
	$mail->Username  = $username;		
	$mail->Password  = "Noida@123#1";
	
	
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
	else { $msg= "1";

		$qry = "select * from email_tracker where email_id= '$username' and date = '$today_date'";
		$check_mail	= 	mysql_query($qry);
		$mail_data =  mysql_fetch_assoc($check_mail);
		if(empty($mail_data))
		{
			$insert_count = "INSERT INTO email_tracker (email_id,date,count) VALUES ('$username','$today_date','1')";
        	mysql_query($insert_count);
		}else{
			$count = $mail_data['count'] + 1;
			$insert_count = "update email_tracker set count = '$count' where email_id= '$username' and date = '$today_date' ";
        	mysql_query($insert_count);
		}
	}

	return $msg; 
}


//	ob_start();
//	include("a3c-mis-sheet.php");
//	$excel_sheet=ob_get_clean();
//	file_put_contents($filename,$excel_sheet);

	// CSM List
?> 