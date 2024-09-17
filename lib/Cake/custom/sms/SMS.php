<?php  
class SMS
{
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
}
?>