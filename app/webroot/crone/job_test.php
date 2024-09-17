<?php
 include('report-send.php');
 include('function.php');
 
mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk') or die('unable to connect');

echo $select = "SELECT * FROM crone_job WHERE bpo='0' and
IF(alertOn='email' && mail_status IS NULL,TRUE,
 IF(alertOn='sms' && sms_status IS NULL,TRUE,
  IF(alertOn='both' ,TRUE,FALSE))) && alertType='Alert' and date(createdate)=curdate() and clientid='199' and id='1011413'";
$export = mysql_query($select);

while($row = mysql_fetch_assoc($export))
{
  $Clientname = mysql_fetch_array(mysql_query("select company_name from registration_master where company_id={$row['clientId']}"));

  $smsText = '';$smsText2=''; $i='';$count='';$pos='';$text='';$str='';$fields=''; $captureFields='';
  
  $callEmpty = "select SrNo `ID`,MSISDN `No`,Category1,Category2,Category3,Category4,Category5 from call_master where id={$row['data_id']} limit 1";
    $execallEmpty = mysql_query($callEmpty);
    $dataEmpty = mysql_fetch_assoc($execallEmpty);
    $smsText .="In Call Alert  \n";
    $smsText .=" ID   ".$dataEmpty['ID']."\n";
    $smsText .=' No   '.$dataEmpty['No']."\n";
    
    if(empty($row['msg']))
    {
        $smsText .=' Scenario  - '.$dataEmpty['Category1']."\n";
        $smsText .=' Sub Scenario1 - '.$dataEmpty['Category2']."\n";
        $smsText .=' Sub Scenario2 - '.$dataEmpty['Category3']."\n";
    }
    else
    {
        if($row['clientId']=='')
        {
            if($row['bpo']==0) {$table = "call_master";}
            else{$table = "call_master_out";}
  
            $smsText .= $row['msg']; 
            $selectSrNo = "select SrNo,MSISDN from call_master where id='{$row['data_id']}'";
            $exeSrNo = mysql_query($selectSrNo);
            $srno = mysql_fetch_assoc($exeSrNo);
  
            $row['msg'] = $smsText = str_replace("#SrNo#",$srno['SrNo'], $smsText);
            $row['msg'] = $smsText = str_replace("#MSISDN#",$srno['MSISDN'], $smsText);
 
            $smsText2 = $row['msg'];
  //$fields = explode('#',$smsText);
  
            $count = substr_count($smsText,'#'); 
            for($i=1; $i<=$count/2; $i++)
            {
      $pos = strpos($smsText, '#');
      $smsText = substr_replace($smsText, "[tag]", $pos, strlen('#'));
      $pos = strpos($smsText, '#');
      $smsText = substr_replace($smsText, '[/tag]', $pos, strlen('#'));
  }
  $text = $smsText;
  $smsText = $smsText2;
  
  while(strpos($text,'[tag]')>=0 && strpos($text,'[/tag]'))
    {
        $str = substr($text,strpos($text,'[tag]'),( strpos($text,'[/tag]')-strpos($text,'[tag]')+6));
        $text = str_replace($str,'',$text);
        $str = str_replace('[tag]','',$str);
        $str = str_replace('[/tag]','',$str);    
        $fields[] = $str;
    }
    
  $ecr =''; $flag = false; $header = $fields;
//print_r($fields); exit;
  for($i=1; $i<=5; $i++)
  {
    if(in_array("Category$i",$fields))
    {
        if($flag) $ecr .= ",";
      $ecr .= "Category$i";
      $smsText = str_replace("Category$i", "@@$i@", $smsText);
     $fields2 = array("Category$i");
     $fields = array_diff($fields, $fields2);
     $flag = true;
    }
  }
  //print_r($fields); die;
  $captureFields = array();
  $capture = ''; $flag = false;
  $clientId = $row['clientId'];
  $selectFields = "select FieldName,fieldNumber from field_master where ClientId='$clientId'";
  $exportFields = mysql_query($selectFields);
  while($row2 = mysql_fetch_assoc($exportFields))
  {
    $captureFields[$row2['fieldNumber']] = $row2['FieldName'];
  }
  //print_r($captureFields); exit;
  $keys = array_keys($captureFields);
  
  for($i=0; $i<count($captureFields); $i++)
  {
      if(in_array($captureFields[$keys[$i]],$fields))
      {
          if($flag) $capture .=",";
          
          $capture .= 'field'.$keys[$i];
          $capture1[] = "##{$keys[$i]}#";
          $smsText = str_replace("#".$captureFields[$keys[$i]]."#", "##{$keys[$i]}#", $smsText);
          $flag = true;
      }
  }
  //echo $ecr;
  //echo $capture;
  $fields3 = $ecr;
  if(!empty($ecr))
  {
      $fields3 .=',';
  }
  if(!empty($capture))
  {
    $fields3 .=$capture;
  }
 $selectCustomerData = "select $fields3 from $table where id='{$row['data_id']}' limit 1";
  $executeData = mysql_query($selectCustomerData);
  $exportData = mysql_fetch_assoc($executeData);
  
  //print_r($exportData);
  //echo $smsText;
  
  $smsText2 = $smsText;
  
  for($i=1; $i<=5; $i++)
  {
    $ecr .= "Category$i";
    $smsText = str_replace("#@@$i@#",$exportData["Category$i"], $smsText);
    $smsText2 = str_replace("#@@$i@#",'', $smsText2);
    unset($exportData["Category$i"]);
  }
  
  $captureFields = explode(',', $smsText2);
  $captureFields = array_filter($captureFields);
  //echo $smsText; echo "<br>";
  foreach($capture1 as $v)
  {
      $v = str_replace('#', '', $v);
      $v = str_replace('#', '', $v);
      $smsText = str_replace("##$v#",$exportData["field$v"], $smsText);
  }
  
  
  
  
  
  //echo $smsText; exit;  
  }
        else
        {
            if($row['bpo']==0) {$table = "call_master";}
            else{$table = "call_master_out";}
            
            $smsText .= $row['msg'];
            
  //$fields = explode(':',$smsText);
            
            $selectSrNo = "select SrNo,MSISDN from call_master where id='{$row['data_id']}' limit 1";
            $exeSrNo = mysql_query($selectSrNo);
            $srno = mysql_fetch_assoc($exeSrNo);
            
            $smsText = str_replace(":SrNo:",'', $smsText);
            $smsText = str_replace("SrNo",'', $smsText);
            $smsText = str_replace(":MSISDN:",'', $smsText);
            $row['msg'] = $smsText = str_replace("MSISDN",'', $smsText);
            
            $smsText2 = $smsText;
           
            $count = substr_count($smsText,':'); 
            for($i=1; $i<=$count/2; $i++)
            {
                $pos = strpos($smsText, ':');
                $smsText = substr_replace($smsText, "[tag]", $pos, strlen(':'));
                $pos = strpos($smsText, ':');
                $smsText = substr_replace($smsText, '[/tag]', $pos, strlen(':'));
            }
  $text = $smsText;
  $smsText = $smsText2;
  
  while(strpos($text,'[tag]')>=0 && strpos($text,'[/tag]'))
    {
        $str = substr($text,strpos($text,'[tag]'),( strpos($text,'[/tag]')-strpos($text,'[tag]')+6));
        $text = str_replace($str,'',$text);
        $str = str_replace('[tag]','',$str);
        $str = str_replace('[/tag]','',$str);    
        $fields[] = $str;
    }
    
  $ecr =''; $flag = false; $header = $fields;
  
  for($i=1; $i<=5; $i++)
  {
    if(in_array("Category$i",$fields))
    {
        if($flag) $ecr .= ",";
      $ecr .= "Category$i";
      $smsText = str_replace("Category$i", "@@$i@", $smsText);
     $fields2 = array("Category$i");
     $fields = array_diff($fields, $fields2);
     $flag = true;
    }
  }
  //print_r($fields); die;
  $capture = ''; $flag = false;
  $capture1 = array();
  $clientId = $row['clientId'];
  $selectFields = "select FieldName,fieldNumber from field_master where ClientId='$clientId'";
  $exportFields = mysql_query($selectFields);
  while($row2 = mysql_fetch_assoc($exportFields))
  {
    $captureFields[$row2['fieldNumber']] = $row2['FieldName'];
  }
  
  $keys = array_keys($captureFields);
  
  for($i=0; $i<count($captureFields); $i++)
  {
      if(in_array($captureFields[$keys[$i]],$fields))
      {
          if($flag) $capture .=",";
          
          $capture .= 'field'.$keys[$i];
          $capture1[] = "##{$keys[$i]}#";
          $smsText = str_replace(":".$captureFields[$keys[$i]].":", "##{$keys[$i]}#", $smsText);
          $flag = true;
      }
  }
  //echo $ecr;
  //echo $capture;
  $fields3 = $ecr;
  if(!empty($ecr))
  {
      $fields3 .=',';
  }
  if(!empty($capture))
  {
    $fields3 .=$capture;
  }
 $selectCustomerData = "select $fields3 from $table where id='{$row['data_id']}' limit 1";
  $executeData = mysql_query($selectCustomerData);
  $exportData = mysql_fetch_assoc($executeData);
  
  //print_r($exportData);
  //echo $smsText; exit;
  
  $smsText2 = $smsText;
  
  for($i=1; $i<=5; $i++)
  {
    $ecr .= "Category$i";
    $smsText = str_replace(":@@$i@:",$exportData["Category$i"]."\n", $smsText);
    $smsText2 = str_replace(":@@$i@:",'', $smsText2);
    unset($exportData["Category$i"]);
  }
  
  $captureFields = explode(',', $smsText2);
  $captureFields = array_filter($captureFields);
  //echo $smsText; echo "<br>";
  foreach($capture1 as $v)
  {
      $v = str_replace('#', '', $v);
      $v = str_replace(':', '', $v);
      $smsText = str_replace("##$v#",$exportData["field$v"]."\n", $smsText);
  }
  
  
  //echo $smsText; exit;
  
  
  //echo $smsText; exit;
  $header = array();
  $smsText2=  str_replace(':MSISDN:',':Call From:', $row['msg']);
  $smsText2=  str_replace(':SrNo:',':In Call ID:', $smsText2);
  $header = explode(",",$smsText2);
  $value = explode(",",$smsText);
  }
  }
  $capture = "SELECT GROUP_CONCAT(CONCAT('field',fieldNumber,'`',FieldName,'`'))`fields` FROM `field_master` WHERE `ClientId` = '{$row{'clientId'}}' and FieldStatus is null ORDER BY Priority ASC limit 1";
    $execapture = mysql_query($capture);
    $fields = mysql_fetch_assoc($execapture);
    
   echo $call = "select SrNo `In Call ID`,MSISDN `Call From`,Category1 `Scenario`,Category2 `Sub-Scenario1`,Category3 `Sub-Scenario2`,Category4 `Sub-Scenario3`,Category5 `Sub-Scenario4`,".$fields['fields']." from call_master where id={$row['data_id']} limit 1";
    $execall = mysql_query($call);
    
    $data = mysql_fetch_assoc($execall);

  
  
  
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
        <div style='background-color:#607d8b;color:#fff;width:100%;font-size:25px;text-indent:5px;'>In Call Details</div>
        <table cellspacing='0' cellpadding='0' border='0' class='table table-striped table-bordered datatables dataTable'>
          ";
  for($i=0; $i<count($data); $i++)
  {
       $header = mysql_field_name($execall, $i);
      if(empty($data[$header]) && $i>1 && $i<7)
      {
          
      }
      else
      {
        $html.= "<tr><td>".str_replace(':','',$header)."</td>";
        $html.= "<td>".$data[$header]."</td>";
        $html.= "</tr>";
      }
  }
  $html.= "</table><br/><br/>"; 
  $html.= "<hr>";
  $html.= "<div style='padding-top:20px;'>For further details kindly login http://dialdesk.co.in/dialdesk/client_activations/login</div>";
  $html.= "<div style='padding-top:10px;'>Thanks & regards</div>";
  $html.= "<div>Team DialDesk </div>";
  $html.= "</div>"; 
  
 //echo $html; die;
 
 
  
  
  
  
  //print_r($smsText);
  

  
  
  if($row['alertOn']=='email' || $row['alertOn']=='both')
  {  
    $EmailText ='';
    //$filename="/var/www/html/dialdesk/app/webroot/crone/csv_data/dialdesk_Mas Callnet_".date('d_m_Y_H_i_s')."_Export.xls";
    //file_put_contents( $filename, $text); 
    
    $email = $row['email'];
    $name = $row['personName'];
    
    $To = $row['email'];
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
    $Subject="Alert ".$Clientname['company_name']." ".$data['In Call ID'];
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
        $msg =  "Mail Sent Successfully !";
        $updQry = "update crone_job set mail_status='$done',mail_date=now() where data_id = '{$row['data_id']}' and id='{$row['id']}'";
        mysql_query($updQry);
        
        $updQry2 = "insert into mail_log(clientId,data_id,alertType,mail_status,mail_date,processType) "
                . "values('{$row['clientId']}','{$row['data_id']}','{$row['alertType']}','$done',now(),'{$row['bpo']}') ";
        mysql_query($updQry2);
        
        // start update balance
        $amount = 0;
        $unit1=1;
        $BalanceMaster = mysql_fetch_assoc(mysql_query("select * from `balance_master` where clientId='{$row['clientId']}' limit 1"));
        $PlanDetails = mysql_fetch_assoc(mysql_query("select * from `plan_master` where Id='{$BalanceMaster['PlanId']}' limit 1"));
        $amount = $unit1*$PlanDetails['EmailCharge'];

        if($BalanceMaster['PlanType']== 'Prepaid'){ 
            $AvBalance=($BalanceMaster['Balance']-$amount);
            mysql_query("update balance_master set Balance='$AvBalance' where clientId = '{$row['clientId']}'");  
        }
        else{
            $AvBalance=($BalanceMaster['Balance']+$amount);
           mysql_query("update balance_master set Balance='$AvBalance' where clientId = '{$row['clientId']}'"); 
        }
        // end update balance
        
        $ins = "INSERT INTO billing_master SET clientId='{$row['clientId']}', data_id='{$row['data_id']}',CallDate=CURDATE(),CallTime=TIME(NOW()),SrNo='{$dataEmpty['ID']}',
CallFrom='{$dataEmpty['No']}',Duration='1',Unit='1',Amount='0',DedType='Email',DedSubType='Alert'";  
    mysql_query($ins);
    }
    catch (Exception $e)
    {
        $error = $e.printStackTrace();
        $updQry = "insert into error_master(data_id,process,error_msg,createdate) values('{$row['data_id']}','{$row['bpo']}','{$error}',now())";
        mysql_query($updQry);
        
    }
    if(!empty($done))
    {
        $msg =  "Mail Sent Successfully !";
        $updQry = "update crone_job set mail_status='$done',mail_date=now() where data_id = '{$row['data_id']}' and id='{$row['id']}'";
        mysql_query($updQry);
        
        $updQry2 = "insert into mail_log(clientId,data_id,alertType,mail_status,mail_date,processType) "
                . "values('{$row['clientId']}','{$row['data_id']}','{$row['alertType']}','$done',now(),'{$row['bpo']}') ";
        mysql_query($updQry2);
        

    }    
}
  
 if($row['alertOn']=='sms' || $row['alertOn']=='both')
 {
   $num['ReceiverNumber'] = $row['mobileNo'];
   $smsText = str_replace(":",' ',$smsText);
   $smsText = str_replace(",",' ',$smsText);
   $num['SmsText'] = $smsText;
   $done = '';
   
   
   //echo $smsText; exit;
   if(!empty($smsText))
   {
    $done = send_sms($num);  
    $updQry = "update crone_job set sms_status='$done',sms_date=now() where data_id = '{$row['data_id']}' and id='{$row['id']}' ";
    mysql_query($updQry);
   }
   $updQry2 = "insert into sms_log(clientId,data_id,alertType,sms_status,sms_date,processType) "
                . "values('{$row['clientId']}','{$row['data_id']}','{$row['alertType']}','$done',now(),'{$row['bpo']}') ";
        mysql_query($updQry2);
     
        $strlen = strlen($smsText);
        $unit = ceil($strlen/160);
        
        $amount = 0;
        $BalanceMaster = mysql_fetch_assoc(mysql_query("select * from `balance_master` where clientId='{$row['clientId']}' limit 1"));
        $PlanDetails = mysql_fetch_assoc(mysql_query("select SMSLength,SMSCharge from `plan_master` where Id='{$BalanceMaster['PlanId']}' limit 1"));
        $amount = $unit*$PlanDetails['SMSCharge'];

        if($BalanceMaster['PlanType']== 'Prepaid'){ 
            $AvBalance=($BalanceMaster['Balance']-$amount);
            mysql_query("update balance_master set Balance='$AvBalance' where clientId = '{$row['clientId']}'");  
        }
        else{
            $AvBalance=($BalanceMaster['Balance']+$amount);
            mysql_query("update balance_master set Balance='$AvBalance' where clientId = '{$row['clientId']}'"); 
        }
        
    $ins = "INSERT INTO billing_master SET clientId='{$row['clientId']}', data_id='{$row['data_id']}',CallDate=CURDATE(),CallTime=TIME(NOW()),SrNo='{$dataEmpty['ID']}',
CallFrom='{$dataEmpty['No']}',Duration='$strlen',Unit='$unit',Amount='0',DedType='SMS',DedSubType='Alert'";    

    mysql_query($ins);
        
 }
}


