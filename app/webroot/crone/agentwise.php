<?php

$db1=mysql_connect('192.168.10.5','root','vicidialnow',TRUE);
mysql_select_db('asterisk',$db1) or die('unable to connect');


include('report-send.php');
include('function_report2.php');
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
        
       
    $fileName = "overall_report_".date('Y_m_d_h_i').".xls"; 
$savePath = "/var/www/html/dialdesk/app/webroot/crone/comway/$fileName";
$campaignId ="campaign_id in('Comway')";
//$firstDayUTS = mktime (0, 0, 0, date("m"), 1, date("Y"));
//$lastDayUTS = mktime (0, 0, 0, date("m"), date('t'), date("Y"));

$firstDay = date("Y-m-d");
  $lastDay = date("Y-m-d");


         $FromDate=$firstDay.' 00:00:00';
	 $ToDate=$lastDay.' 23:59:59';
	// echo $ToDate;die;
	while(strtotime($FromDate)<strtotime($ToDate))
	{
		$start_time_start=$FromDate;
		$event_date_start=$ToDate;
		
		$start_time_end=date("Y-m-d 23:59:59",strtotime("$FromDate"));

		$NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 day"));
		$FromDate=$NextDate;
	
		/*$qry="SELECT COUNT(*) `Total`,
				SUM(If(t2.status='SALE',1,0)) `Answered`,
				SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`,
				SUM(IF(t2.status='SALE',t1.length_in_sec,0)) `TotalAcht`,
				SUM(IF(t2.status='SALE' AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
				SUM(IF(((t2.status IS NULL OR t2.status='DROP') AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
				SUM(IF(((t2.status IS NULL OR t2.status='DROP') AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
			FROM
				asterisk.call_log t1
				LEFT JOIN
				asterisk.vicidial_closer_log t2 ON t1.uniqueid=t2.uniqueid
			WHERE
				t1.start_time>='$start_time_start' AND t1.start_time<'$start_time_end'
				AND t1.extension IN ($Account)
				
				AND (t2.status IS NULL OR t2.status='DROP' OR t2.status='SALE')";*/
  /*$qry = "SELECT COUNT(*) `Total`,
SUM(If(t2.term_reason='AGENT' OR t2.term_reason='CALLER',1,0)) `Answered`,
SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`,
SUM(IF(t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER',t1.length_in_sec,0)) `TotalAcht`,
SUM(IF((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP')
AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP')
AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end'
and $campaignId and t2.term_reason!='AFTERHOURS'";*/

 
                  $usrQry="Select 
					sum(if((t1.event='LOGIN' || event_date>='$start_time_start'),1,0)) 'UserLoggedIn' 
				FROM 
					asterisk.vicidial_user_log t1 
					join 
					(Select max(user_log_id) `user_log_id` 
					From asterisk.vicidial_user_log 
					Where campaign_id='RI_Temp' and event_date>='$event_date_start' and event_date<'$start_time_end' group by user) as t2 
				Where t1.user_log_id=t2.user_log_id";
		$usrRsc=mysql_query($usrQry);
		$usrDt=mysql_fetch_assoc($usrRsc);
		//print_r($dt);die;
		$timeLabel=date("H:i",strtotime($start_time_start))." To ".date("H:i",strtotime($start_time_end));
		$dateLabel=date("F-Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		
                
		
		
		$TotalCall+=$total;
	}
	
         

	foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
		$data['Offered %'][$dateLabel][$timeLabel]=round($data['Total Calls Landed'][$dateLabel][$timeLabel]*100/$TotalCall); 
                $data1['Offered %'] =$data1['Offered %']+$data['Offered %'][$dateLabel][$timeLabel];
	} }
       
       

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($data) && !empty($data))
{
        
                $html.= '<table cellpadding="0" cellspacing="0" border="2" class="table table-striped table-bordered" >';
                    $html.='<thead>';
	$html.='<tr>';
		$html.='<th callspan="9">Last Day Details</th>';
                $html.='</tr>';
                $html.='<tr>';
                $html.='<th>EMP ID</th>';
                $html.='<th>EMP NAME</th>';
		  $html.='<th>PROCESS</th>';
                $html.='<th>Campaign</th>';
                 $html.='<th>Shift</th>';
                $html.='<th>Team Leader</th>';
                 $html.='<th>Date</th>';
                 $html.='<th>Attendance</th>';
                 $html.='<th>Call Handled</th>';
                $html.='</tr>';
        
$html.='</thead>';
$html.='<tbody>';
				
$html.='</tbody>';
               $html.='</table>';
              
            
        }
        
      
 echo $html;die;
  

         
         
   file_put_contents($savePath,$html);
	//file_put_contents($filename1,$htm1);
	//unset($htm);
	unset($htm1); 
	$ReceiverEmail=array('Email'=>"raj.solanki@teammas.in",'Name'=>'Raaz');
//	$ReceiverEmail=array('Email'=>"krishna.kumar@teammas.in",'Name'=>'Krishna');
	$SenderEmail=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet P2P');
	$ReplyEmail=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet P2P');
	$AddCc='';
	//$AddBcc=array("krishna.kumar@teammas.in");
	$Attachment=array($savePath);
	
	$Subject="Mas Callnet P2P - Mail Attachment Performance MIS";
	
	$EmailText.="<table><tr><td style=\"padding-left:12px;\">Hi ".$data['name'].",</td></tr>";
	$EmailText.="<tr><td style=\"padding-left:12px;\">Please find the attached Performance MIS</td></tr>";
	$EmailText.="</table>";
	$emaildata=array('ReceiverEmail'=>$ReceiverEmail,'SenderEmail'=>$SenderEmail,'ReplyEmail'=>$ReplyEmail,'AddCc'=>$AddCc,'AddBcc'=>$AddBcc,'Subject'=>$Subject,'EmailText'=>$EmailText,'Attachment'=>$Attachment);
	unset($EmailText);
	$done = send_email($emaildata);
	if($done=='1')
	{
	$msg =  "Mail Sent Successfully !";
	}
    unlink($filename);      
         
         
       






?>


