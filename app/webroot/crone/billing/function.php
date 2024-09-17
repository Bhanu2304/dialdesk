 <?php
 include_once('send_email.php');
 
 function expir_date_color_code($LD,$CD){
    if($LD !=""){
        $days = (strtotime($LD) - strtotime($CD)) / (60 * 60 * 24);
        if($days <= "7"){
            return "#ff0000";
        }
        else if($days <= "15"){
            return "#ff6666";
        }
        else if($days <= "30"){
            return "#ff9999";
        }
        else if($days <= "90"){
            return "#FFA500";
        }
        else{
            return "#008000";
        }
    }
}
    
function balance_color_code($Used,$Main,$LD){
    if($Main !="" && $LD !=""){
        $UsedBalance =   round($Used*100/$Main);
        if($UsedBalance >= 75 && $UsedBalance < 85 ){
            return "#008000";
        }
        else if($UsedBalance >= 85 && $UsedBalance < 95 ){
            return "#FFA500";
        }
        else if($UsedBalance >= 95 && $UsedBalance < 98 ){
            return "#ff9999";
        }
        else if($UsedBalance >= 98 && $UsedBalance < 100){
             return "#ff6666";
        }
        else if($UsedBalance >= 100){
            return "#ff0000";
        }
        else{
            return "#008000";   
        }
    }
}

function mail_send($text,$email,$name){
    $EmailText ='';
    $filename="/var/www/html/dialdesk/app/webroot/csv_bill_summary/client_wise_bill_summary_".date('d_m_Y_H_i_s')."_Export.xls";
    file_put_contents( $filename, $text); 
     
    $ReceiverEmail=array('Email'=>$email,'Name'=>$name); 
    $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Attachment=array( $filename);
    $Subject="Client wise bill summary report"; 
    $EmailText .="<table>";
    $EmailText .="<tr><td style=\"padding-left:12px;\">Hello $name</td></tr>";
    $EmailText .="<tr><td>&nbsp;</td></tr>";
    $EmailText .="<tr><td style=\"padding-left:12px;\">Please find attachment of client wise bill summary report.</td></tr>"; 
    $EmailText .="</table>"; 
    $EmailText .=$text;
    $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'Attachment'=>$Attachment);
    
    try{
        $done = send_email( $emaildata);
        mysql_query("insert into bill_summary_send_report_master(Email,MailStatus,CreateDate)values('$email','$done',now())");
    }
    catch (Exception $e){
        $error = $e.printStackTrace();
        mysql_query("insert into bill_summary_send_report_master(Email,MailStatus,CreateDate)values('$email','$error',now())");
    }
}
 ?>