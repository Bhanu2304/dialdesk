<?php
 include('report-send.php');
 include('function.php');
 
mysql_connect('localhost','root','dial@mas123',false,128);
mysql_select_db('db_dialdesk') or die('unable to connect');

/*
$select = "SELECT crone_job.*,call_master.* FROM crone_job 
INNER JOIN call_master ON crone_job.data_id = call_master.id    
WHERE
IF(alertOn='email' && mail_status IS NULL,TRUE,
 IF(alertOn='sms' && sms_status IS NULL,TRUE,
  IF(alertOn='both' && mail_status IS NULL && sms_status IS NULL,TRUE,FALSE))) AND alertType !='Alert'
AND IF((HOUR(NOW())-HOUR(createdate))>tat,TRUE,IF(DATE(createdate)<CURDATE(),TRUE,FALSE))
AND (IF(alertType='Escalation' && Escalation IS NULL,true,false) || IF(alertType='Escalation1' && Escalation1 IS NULL,true,false) ||
IF(alertType='Escalation2' && Escalation2 IS NULL,true,false) || IF(alertType='Escalation3' && Escalation3 IS NULL,true,false))
AND CloseLoopCate1 IS NULL"; */
 $select = "SELECT crone_job.*,call_master_out.* FROM crone_job
INNER JOIN call_master_out ON crone_job.data_id = call_master_out.id
WHERE
IF(alertOn='email' && mail_status IS NULL,TRUE,
 IF(alertOn='sms' && sms_status IS NULL,TRUE,
  IF(alertOn='both' && mail_status IS NULL && sms_status IS NULL,TRUE,FALSE))) AND alertType !='Alert'
AND IF((HOUR(NOW())-HOUR(createdate))>crone_job.tat,TRUE,IF(DATE(createdate)<CURDATE(),TRUE,FALSE))
AND (IF(alertType='Escalation' && Escalation IS NULL,true,false) || IF(alertType='Escalation1' && Escalation1 IS NULL,true,false) ||
IF(alertType='Escalation2' && Escalation2 IS NULL,true,false) || IF(alertType='Escalation3' && Escalation3 IS NULL,true,false))
AND CloseLoopCate1 IS NULL and crone_job.clientId='409'";
$export = mysql_query($select);

//$data = mysql_fetch_assoc($export);
//print_r($data);

while($row = mysql_fetch_assoc($export))
{
  $smsText = '';$smsText2=''; $i='';$count='';$pos='';$text='';$str='';$fields=''; $captureFields='';
    $selectSrNo = "select SrNo,MSISDN from call_master_out where id='{$row['data_id']}'";
    $exeSrNo = mysql_query($selectSrNo);
    $srno = mysql_fetch_assoc($exeSrNo);
    $smsText = $row['msg'];
  
    
  if($row['bpo']==0) {$table = "call_master";}
  else{$table = "call_master_out";}
  
    $smsText = str_replace(":SrNo:",' '.$srno['SrNo'], $smsText);
    $row['msg'] = $smsText = str_replace(":MSISDN:",' '.$srno['MSISDN'], $smsText);
    $smsText2 = $smsText;
  //$fields = explode(':',$smsText);
  
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
  $clientId = $row['clientId'];
  $selectFields = "select FieldName,fieldNumber from obfield_master where ClientId='$clientId'";
  $exportFields = mysql_query($selectFields);
  while($row2 = mysql_fetch_assoc($exportFields))
  {
    $captureFields[$row2['fieldNumber']] = $row2['FieldName'];
  }
  //print_r($captureFields);
  $keys = array_keys($captureFields);
  //print_r($smsText); exit;
  for($i=0; $i<count($captureFields); $i++)
  {
      if(in_array($captureFields[$keys[$i]],$fields))
      {
          if($flag) $capture .=",";
          
          $capture .= 'field'.$keys[$i];
          $capture1[] =  "##{$keys[$i]}#";
          $smsText = str_replace(':'.$captureFields[$keys[$i]].':', "##{$keys[$i]}#", $smsText);
          $flag = true;
          //$table[$captureFields[$keys[$i]]]
         // echo $captureFields[$keys[$i]];
      }
  }
  //exit;
  //echo $ecr;
  //echo $capture; die;
  //print_r($smsText); die;
  //$fields3 = "MSISDN,SrNo,";
  $fields3 = $ecr;
  if(!empty($ecr))
  {
      $fields3 .=',';
  }
  if(!empty($capture))
  {
    $fields3 .=$capture;
  }
  //echo $fields3; exit;
  $selectCustomerData = "select $fields3 from $table where id='{$row['data_id']}' limit 1";  
  $executeData = mysql_query($selectCustomerData);
  $exportData = mysql_fetch_assoc($executeData);
  
  //print_r($exportData); die;
  //$smsText = str_replace(':MSISDN:', $exportData['MSISDN'], $smsText);
  //$smsText = str_replace(':SrNo:', $exportData['SrNo'], $smsText);
  //echo $smsText; exit;
  
  $smsText2 = $smsText;
  
  for($i=1; $i<=5; $i++)
  {
    $ecr .= "Category$i";
    $smsText = str_replace(":@@$i@:",$exportData["Category$i"], $smsText);
    $smsText2 = str_replace(":@@$i@:",'', $smsText2);
    unset($exportData["Category$i"]);
  }
  //print_r($smsText2); exit;
 $captureFields = explode(',', $smsText2);
  
  $captureFields = array_filter($captureFields);
  //print_r($capture1); die;
  //echo $smsText; echo "<br>";  die;
  foreach($capture1 as $v)
  {
      $v = str_replace('#', '', $v);
      $v = str_replace(':', '', $v);
     // $smsText = str_replace("##$v#",$exportData["field$v"], $smsText);
      $smsText = str_replace("##$v#",$exportData["field$v"]."\n", $smsText);
  }
  //echo $smsText; exit;
  $header = array();
 $smsText2=  str_replace(':MSISDN:',':Call From:', $row['msg']);
 $smsText2=  str_replace(':SrNo:',':In Call ID:', $smsText2);
  $header = explode(",",$smsText2);
  //print_r($header);exit;
  $value = explode(",",$smsText);
  
  
if($row['Category1'] !=""){
    $tmcond="and Category1='{$row['Category1']}'";
}
if($row['Category2'] !=""){
    $tmcond="and Category2='{$row['Category2']}'";
}
if($row['Category3'] !=""){
    $tmcond="and Category3='{$row['Category3']}'";
}
if($row['Category4'] !=""){
    $tmcond="and Category4='{$row['Category4']}'";
}
if($row['Category5'] !=""){
    $tmcond="and Category5='{$row['Category5']}'";
}

$totalTat=mysql_fetch_array(mysql_query("select time_Hours from tbl_time where clientId='$clientId' $tmcond limit 1"));
$totat=$totalTat[0]['time_Hours'];
  
$date1 = $row['createdate'];
$date2 = date("Y-m-d H:i:s");
$timestamp1 = strtotime($date1);
$timestamp2 = strtotime($date2);
$hour = abs($timestamp2 - $timestamp1)/(60*60);
$final_hours = round($hour,0);
$final_hours = $final_hours -$totat;


$html ="<style>
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
  $image = "https://dialdesk.co.in/dialdesk/app/webroot/img/logo.png";
  $html.="<div><img style='width:150px;' src=".$image."></div>";
  $html.="<div style='padding-top:20px;'><p>This is a system generated email to update you that below case has not attempted within tat and dverdue by ( $final_hours Hours).</p></div>";
  $html.= "
        <div style='padding-top:10px;'>
        <div style='background-color:#607d8b;color:#fff;width:100%;font-size:25px;text-indent:5px;'>In Call Details</div>
        <table cellspacing='0' cellpadding='0' border='0' class='table table-striped table-bordered datatables dataTable'>
          ";
         //print_r($header);
  for($i=0; $i<count($header); $i++)
  {

      $html.= "<tr><td>".str_replace(':','',$header[$i])."</td>";
      $html.= "<td>".$value[$i]."</td>";
      $html.= "</tr>";

  }
  $html.= "</table><br/><br/>"; 
  $html.= "<hr>";
  $html.= "<div style='padding-top:20px;'>For further details kindly login http://dialdesk.co.in/dialdesk/client_activations/login</div>";
  $html.= "<div style='padding-top:10px;'>Thanks & regards</div>";
  $html.= "<div>Team DialDesk </div>";
  $html.= "</div>"; 

  
  //echo $html;die;
  

  //print_r($smsText); exit;
  
  if($row['alertOn']=='email' || $row['alertOn']=='both')
  {  
    $EmailText ='';
    //$filename="/var/www/html/dialdesk/app/webroot/crone/csv_data/dialdesk_Mas Callnet_".date('d_m_Y_H_i_s')."_Export.xls";
    //file_put_contents( $filename, $text); 
    $email = ""; $name = "";
    
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
		

    $name = $row['personName'];
    
    $ReceiverEmail=array('Email'=>$To,'Name'=>$name); 
    $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    //$Attachment=array( $filename); 
    $Subject=$row['alertType']; 
    $EmailText .=$html;
    //$EmailText .="<table><tr><td style=\"padding-left:12px;\">Hello $name</td></tr>"; 
    //$EmailText .="<tr><td style=\"padding-left:12px;\">$smsText</td></tr>";
    //$EmailText .="</table>"; 
    $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText);


	if(!empty($AddTo))
    {
	//$AddTo[] = "anil.goar@teammas.in";
        $emaildata['AddCc'] =  $AddTo;
    }

    
    try
    {
        $done = send_email( $emaildata);
        
        //*************************
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
        
        $ins1 = "INSERT INTO billing_master SET clientId='{$row['clientId']}', data_id='{$row['data_id']}',CallDate=CURDATE(),CallTime=TIME(NOW()),SrNo='{$srno['SrNo']}',
CallFrom='{$srno['MSISDN']}',Duration='1',Unit='1',Amount='0',DedType='Email',DedSubType='{$row['alertType']}'";  
        mysql_query($ins1);
    
        //*************************
          
        
    }
    catch (Exception $e)
    {
        $error = $e->getMessage();
        $updQry = "insert into error_master(data_id,process,error_msg,createdate) values('{$row['data_id']}','{$row['bpo']}','{$error}',now())";
        mysql_query($updQry);
    }
    if($done=='1')
    {
        $msg =  "Mail Sent Successfully !";
        
        $escalation = $row['alertType'];
        $escalationDate = $row['alertType'].'_date';
        $updQry2 = "insert into mail_log(clientId,data_id,alertType,mail_status,mail_date,processType) "
                . "values('{$row['clientId']}','{$row['data_id']}','{$row['alertType']}','$done',now(),'{$row['bpo']}') ";
        mysql_query($updQry2);
        
        if($escalation!='Escalation3')
        {
            $updQry1 = "update crone_job set mail_status='$done',mail_date=now() where data_id = '{$row['data_id']}' and alertType='{$row['alertType']}'";
            mysql_query($updQry1);
            $updQry3 = "update $table set $escalation='$done',$escalationDate=now() where id = '{$row['data_id']}'";
            mysql_query($updQry3);
        }
    }    
}
  
 if($row['alertOn']=='sms' || $row['alertOn']=='both')
 {
        if($row['alertType'] =='Escalation')
        {
            $i =' ';
        }
        else if($row['alertType'] =='Escalation1')
        {
            $i ='1';
        }
        else if($row['alertType'] =='Escalation2')
        {
            $i ='2';
        }
        else if($row['alertType'] =='Escalation3')
        {
            $i ='3';
        }
        
        $num['ReceiverNumber'] = $row['mobileNo'];
        $num['SmsText'] = "Escalation $i

This is to inform you that In call Id – {$srno['SrNo']} & its No - {$srno['MSISDN']} has not closed within TAT.
";
    $done = send_sms($num);  
        
        $updQry2 = "insert into sms_log(clientId,data_id,alertType,sms_status,sms_date,processType) "
                . "values('{$row['clientId']}','{$row['data_id']}','{$row['alertType']}','$done',now(),'{$row['bpo']}') ";
        mysql_query($updQry2);
        
        $escalation = $row['alertType'];
        $escalationDate = $row['alertType'].'_date';
        
        //*************************
        $smsText=$num['SmsText'];
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
        
        $ins2 = "INSERT INTO billing_master SET clientId='{$row['clientId']}', data_id='{$row['data_id']}',CallDate=CURDATE(),CallTime=TIME(NOW()),SrNo='{$srno['SrNo']}',
    CallFrom='{$srno['MSISDN']}',Duration='$strlen',Unit='$unit',Amount='0',DedType='SMS',DedSubType='{$escalation}'";  
        mysql_query($ins2);
        //*************************
        
    
        if($escalation!='Escalation3')
        {
            $updQry1 = "update crone_job set sms_status='$done',sms_date=now() where data_id = '{$row['data_id']}' and alertType='{$row['alertType']}'";
            mysql_query($updQry2);
            $updQry3 = "update $table set $escalation='$done',$escalationDate=now() where id = '{$row['data_id']}'";
            mysql_query($updQry3);  
        }   
    
}
 
 //echo $smsText; 
}
