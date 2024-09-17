<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


 include('report-send.php');
 include('function.php');
 
// mysql_connect('localhost','root','dial@mas123',false,128);
// mysql_select_db('db_dialdesk') or die('unable to connect');

$con1   =   mysqli_connect("172.12.10.22","root","dial@mas123",'db_bill'); 

  $image = "/var/www/html/dialdesk/app/webroot/img/logo.png";
  $html ="<div><img style='width:150px;' src=".$image."></div>";
  $html.="<div style='padding-top:20px;'><p>This is a system generated email to update you that we have received call from below caller.</p></div>";
  $html.= "
        <div style='padding-top:10px;'>
        <div style='background-color:#607d8b;color:#fff;width:100%;font-size:25px;text-indent:5px;'>In Call Details</div>
        <table cellspacing='0' cellpadding='0' border='0' class='table table-striped table-bordered datatables dataTable'>
          ";

  $html.= "</table><br/><br/>"; 
  $html.= "<hr>";
  $html.= "<div style='padding-top:20px;'>For further details kindly login http://dialdesk.co.in/dialdesk/client_activations/login</div>";
  $html.= "<div style='padding-top:10px;'>Thanks & regards</div>";
  $html.= "<div>Team DialDesk </div>";
  $html.= "</div>"; 

    $EmailText ='';
    //$filename="/var/www/html/dialdesk/app/webroot/crone/csv_data/dialdesk_Mas Callnet_".date('d_m_Y_H_i_s')."_Export.xls";
    //file_put_contents( $filename, $text); 

    $email = 'bhanu.singh@teammas.in';
    $name = "bhanu singh";

    $To = 'bhanu.singh@teammas.in';


    
    
    $ReceiverEmail=array('Email'=>$To,'Name'=>$name); 
    $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk');
    
    //$Attachment=array( $filename); 
    $Subject="Alert";
    $EmailText .=$html;

    $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText);
    
    #$emaildata['AddTo'] =  $AddTo;

        
        
    $done = send_email( $emaildata);
    $msg =  "Mail Sent Successfully !";
        



