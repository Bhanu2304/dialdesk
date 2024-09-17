<?php
//exit;
include('report-send.php'); 
include('function.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);
 
mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk') or die('unable to connect');


$ClientDetails  =   mysql_fetch_assoc(mysql_query("SELECT * FROM `tbl_user` WHERE Username='superadmin' limit 1",$db));
#$name           =   $ClientDetails['UserName'];
#$emailId        =   $ClientDetails['Email'];

$name           =   "Bhanu";
$email        =   "bhanu.singh@teammas.in";


      $To = "bhanu.singh@teammas.in";
      $Tos = explode(",",$To);
      $AddTo = array();
      $TosFlag = true;
      if(is_array($Tos) && !empty($Tos))
      {
          foreach($Tos as $to)
          {
              if(!empty($to))
              {
                  if($TosFlag)
                  {
                      $To = $to;$TosFlag=false;
                  }
                  else
                  {
                      $AddTo[] = $to;
                  }
              }

          }
      }


      $ReceiverEmail=array('Email'=>$To,'Name'=>$name); 
      $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
      $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk');

      //$Attachment=array( $filename); 
      $Subject="Alert " ;
      $EmailText ="testing";
      //$EmailText .="<table><tr><td style=\"padding-left:12px;\">Hello $name</td></tr>"; 
      //$EmailText .="<tr><td style=\"padding-left:12px;\">$smsText</td></tr>";
      //$EmailText .="</table>"; 
      $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText);

      try
      {
        if(!empty($AddTo))
        {
            $emaildata['AddTo'] =  $AddTo;
        }

        $done = send_email( $emaildata);
         
      }
      catch (Exception $e)
      {
        echo $error = $e.printStackTrace();  

      }



  




