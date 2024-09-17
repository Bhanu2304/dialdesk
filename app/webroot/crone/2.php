<?php include('connection.php'); 

 $filename="/var/www/html/dialdesk/app/webroot/crone/csv_data/dialdesk_Mas Callnet_".date('d_m_Y_H_i_s')."_Export.xls";
 $sel = mysql_query("Select  from call_master where ClientId = '5'and emailSend is null "); 
$text ='sdfsdf[tag1]Serial No[/tag1][tag1]Serial No[/tag1]';
$i =0; 

					while(strpos($text,'[tag]') && strpos($text,'[/tag]'))
					{
						$str = substr($text,strpos($text,'[tag]'),( strpos($text,'[/tag]')-strpos($text,'[tag]')+6));
						$text = str_replace($str,'',$text);
                                                $str = str_replace('[tag]','',$str);
                                                $str = str_replace('[/tag]','',$str);    
						$header[] = $str;
					}
					while(strpos($text,'[tag1]') && strpos($text,'[/tag1]'))
					{
						$str = substr($text,strpos($text,'[tag1]'),( strpos($text,'[/tag1]')-strpos($text,'[tag1]')+7));
						$text = str_replace($str,'',$text);
                                                $str = str_replace('[tag1]','',$str);
                                                $str = str_replace('[/tag1]','',$str);    
						$header[] = $str;
					}

					
					
 $text .= "<table border='2'><tr>";
 for($i=0; $i<count($header); $i++){
 $text .= "<th>".$header[$i]."</th>";}
 $text .= "</tr>";while( $Data = mysql_fetch_array($sel)){ 


 $text .= "<tr>";
 for($i=0; $i<count($header); $i++){
 $text .= "<td>".$Data[$i]."</td>";}
 $text .= "</tr>";}
 $text .= "</table>"; include('report-send.php'); 

file_put_contents( $filename, $text); 
 $ReceiverEmail=array('Email'=>'chandresh.tripahti@teammas.in','Name'=>'chandresh.tripahti@teammas.in'); 
 $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
 $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
 $Attachment=array( $filename); 
 $Subject="DialDesk - Report Export"; 
 $EmailText .="<table><tr><td style=\"padding-left:12px;\">Hello chandresh.tripahti@teammas.in</td></tr>"; 
 $EmailText .="<tr><td style=\"padding-left:12px;\">Please find the attached Export</td></tr>";
 $EmailText .="</table>"; 
 $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'AddCc'=> $AddCc,'AddBcc'=> $AddBcc,'Subject'=> $Subject,'EmailText'=> $EmailText,'Attachment'=> $Attachment);
 $done = send_email( $emaildata);
 if($done=='1'){ $msg =  "Mail Sent Successfully !";}
 unlink( $filename);
 mysql_query("update call_master set emailSend='$done',smsSend='$sms' where ClientId = '5'"); ?>