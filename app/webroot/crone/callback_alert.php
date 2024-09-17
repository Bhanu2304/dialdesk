<?php
//exit;
include('report-send.php'); 
 include('function.php');
 
mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk') or die('unable to connect');

$select = "SELECT id FROM callback_master WHERE email_send='1' and DATE_FORMAT(SubDATE(callback_time, INTERVAL 15 MINUTE),'%Y-%m-%d %H:%i') = DATE_FORMAT(now(),'%Y-%m-%d %H:%i')";
$export = mysql_query($select);
$data = array();
$email_list = array();
while($row = mysql_fetch_assoc($export))
{
    $callback_id = $row['id'];
  $qry = "SELECT am.email,rm.company_name,cm.phone_no,cm.callback_time,cm.agentid,am.username,am.displayname FROM `callback_master` cm 
INNER JOIN registration_master rm ON cm.client_id=rm.company_id
INNER JOIN `agent_master` am ON cm.agentid = am.id 
WHERE cm.id='$callback_id' limit 1";
  $cb_det_rsc = mysql_query($qry);
  $cb_det = mysql_fetch_assoc($cb_det_rsc);
  
  $data[$cb_det['email']][] = $cb_det;
  $email_list[$cb_det['email']] = $cb_det['username'];
}


  
  foreach($email_list as $email=>$name)
  {
      
  
  
    $html="<style>
  .table {
      background-color: transparent;
      max-width: 100%;
      width: 100%;
      border-collapse: collapse;
      border-spacing: 0;
  }
  .table-striped>tbody>tr:nth-child(odd)>td, 
  .table-striped>tbody>tr:nth-child(odd)>th {
      background-color:  #fcfcfc; 
   }
  .table.dataTable thead th.sorting::after, table.dataTable thead th.sorting_asc::after, table.dataTable thead th.sorting_desc::after {
      font-size: 0.8em;
      font-weight: 400;
      padding: 0.12em 0;
  }
  *::before, *::after {
      box-sizing: border-box;
  }
  .table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > th, .table > caption + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > td, .table > thead:first-child > tr:first-child > td {
      border-top: 0 none;
  }
  .table-bordered > thead > tr > th, .table-bordered > thead > tr > td, .fc .fc-view > table > thead > tr > th, .fc .fc-view > table > thead > tr > td {
      border-bottom-width: 1px;
  }
  .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td, .fc .fc-view > table > thead > tr > th, .fc .fc-view > table > tbody > tr > th, .fc .fc-view > table > tfoot > tr > th, .fc .fc-view > table > thead > tr > td, .fc .fc-view > table > tbody > tr > td, .fc .fc-view > table > tfoot > tr > td {
      border: 1px solid #f1f1f1;
  }
  .table > thead > tr > th {
      border-bottom: 1px solid #f1f1f1;
      vertical-align: bottom;
  }
  .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
      border-top: 1px solid #f1f1f1;
      line-height: 1.45;
      padding: 10px;
      vertical-align: top;
  }
  .table tr th {
      text-transform: uppercase;
  }
  th {
      text-align: left;
  }
  td, th {
      padding: 0;
  }
  </style>";
  $image = "/var/www/html/dialdesk/app/webroot/img/logo.png";
  $html.="<div><img style='width:150px;' src=".$image."></div>";
  $html.="<div style='padding-top:20px;'><p>This is a system generated email to update you that we have received call from below caller.</p></div>";
  $html.= "
        <div style='padding-top:10px;'>
        <div style='background-color:#607d8b;color:#fff;width:100%;font-size:25px;text-indent:5px;'>Call Back Details</div>
        <table cellspacing='0' cellpadding='0' border='0' class='table table-striped table-bordered datatables dataTable'>
          ";
  $html.='<tr>
                            
                            <th>Client</th>
                            <th>Phone No.</th>
                            <th>CallBack Time</th>
                        </tr>';
  foreach($data[$email] as $record)
  {
      $callback_time = $record['callback_time'];
      $strtotime = strtotime($callback_time);
      $datefmt = "d M Y H:i";
      $new_date = date($datefmt,$strtotime);
      $html.= "<tr><td>{$record['company_name']}</td>";
      $html.= "<td>{$record['phone_no']}</td>";
      $html.= "<td>{$new_date}</td></tr>";
      
  }
  $html.= "</table><br/><br/>"; 
  $html.= "<hr>";
  $html.= "<div style='padding-top:20px;'>For further details kindly login https://dialdesk.co.in/dialdesk/client_activations/login</div>";
  $html.= "<div style='padding-top:10px;'>Thanks & regards</div>";
  $html.= "<div>Team DialDesk </div>";
  $html.= "</div>"; 
  
 //echo $html; die;
 
 
  
      $EmailText ='';
      //$filename="/var/www/html/dialdesk/app/webroot/crone/csv_data/dialdesk_Mas Callnet_".date('d_m_Y_H_i_s')."_Export.xls";
      //file_put_contents( $filename, $text); 

      
      //$name = $row['personName'];

      $To = $email;
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


      $ReceiverEmail=array('Email'=>$email,'Name'=>$name); 
      $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
      $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk');

      //$Attachment=array( $filename); 
      $Subject="Call Back Alert ".date('Y-m-d H:i:s');
      $EmailText .=$html;
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
          
      }  
      print_r($emaildata);
  }          
    


