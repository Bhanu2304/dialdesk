<?php

mysql_connect('192.168.10.5','root','vicidialnow',TRUE);
mysql_select_db('asterisk') or die('unable to connect');



$campaignId = "t2.campaign_id in('Blueheaven_Hindi','Blueheaven_English')";
            $FromDate=date('Y-m-d',strtotime('-1 days'));
            $ToDate=$FromDate;
           
        if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
	if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
	//echo $ToDate;die;
	while(strtotime($FromDate)<strtotime($ToDate))
	{
		
		$start_time_start=$FromDate;
		
                
		
		$NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 hours"));
		$FromDate=$NextDate;
		
		$start_time_end=$NextDate;

                
$qry="
SELECT COUNT(*) `Total`,
SUM(IF(t2.user!='VDCL',1,0)) `Answered`,
SUM(IF(t2.user='VDCL',1,0)) `Abandon`,
SUM(IF(t2.user!='VDCL',(t1.talk_sec+t1.dispo_sec+IFNULL(t3.p,0)),0)) `TotalAcht`,
SUM(IF(t2.user!='VDCL' AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
SUM(IF(((t2.user='VDCL')
AND (t2.queue_seconds IS NULL OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
SUM(IF(((t2.user='VDCL') AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
FROM asterisk.vicidial_closer_log t2 
LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid  AND t1.lead_id!='' AND t2.user=t1.user
LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end' AND $campaignId
            ";

		$rsc = mysql_query($qry);
		$dt=mysql_fetch_assoc($rsc);
               // print_r($dt);die;
		// Usewr Loggedd In
		
		
		$timeLabel=date("H:i",strtotime($start_time_start))." To ".date("H:i",strtotime($start_time_end));
		$dateLabel=date("d-F-Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		
		$data[$dateLabel][$timeLabel]['Offered %']='';
		$total=$dt['Answered']+$dt['Abandon'];
		$data[$dateLabel][$timeLabel]['Total Calls Landed']=$total;
		$data[$dateLabel][$timeLabel]['Total Calls Answered']=$dt['Answered'];
		$data[$dateLabel][$timeLabel]['Total Calls Abandoned']=$dt['Abandon'];
		$data[$dateLabel][$timeLabel]['AHT (In Sec)']=round($dt['TotalAcht']/$dt['Answered']);
		$data[$dateLabel][$timeLabel]['Calls Ans (within 20 Sec)']=$dt['WIthinSLA'];
		$data[$dateLabel][$timeLabel]['Abnd Within Threshold']=$dt['AbndWithinThresold'];
		$data[$dateLabel][$timeLabel]['Abnd After Threshold']=$dt['AbndAfterThresold'];
		$data[$dateLabel][$timeLabel]['Ababdoned (%)']=round($dt['Abandon']*100/$total);
		//$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
                
                //$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']/($total-$dt[0][0]['AbndWithinThresold'])*100);
                
                $data[$dateLabel][$timeLabel]['SL% (20 Sec)']=round($dt['WIthinSLA']*100/$data[$dateLabel][$timeLabel]['Total Calls Landed'])."%";
                
                $data[$dateLabel][$timeLabel]['AL%']=round($dt['Answered']/$data[$dateLabel][$timeLabel]['Total Calls Landed']*100)."%";
        
		//$data['Agent Logged In'][$dateLabel][$timeLabel]=$usrDt['UserLoggedIn'];
		
		
		$TotalCall[$dateLabel]+=$total;
	}
	
         

	foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
		$data[$dateLabel][$timeLabel]['Offered %']=round($data[$dateLabel][$timeLabel]['Total Calls Landed']*100/$TotalCall[$dateLabel]);  
	} }
        
        $htmlsla = '<table  border="0" >
                    <thead>
	<tr>
		<th rowspan="2"></th>';			 
                //print_r($datetimeArray); exit;
                foreach($datetimeArray as $dateLabel=>$timeArray) { $htmlsla .="<th colspan=\"".count($timeArray)."\">$dateLabel</th>"; } 
	$htmlsla .= '</tr>';
	$htmlsla .= '<tr>';
	 foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { $htmlsla .= "<th>$timeLabel</th>"; } } 
	$htmlsla .= '</tr>';
$htmlsla .='</thead>';
$htmlsla .='<tbody>';
	
$label_arr = array('Offered %','Total Calls Landed','Total Calls Answered','Total Calls Abandoned','AHT (In Sec)','Calls Ans (within 20 Sec)','Abnd Within Threshold','Abnd After Threshold','Ababdoned (%)','SL% (20 Sec)','AL%'); 
                
        foreach($label_arr as $label) { 
	$htmlsla .='<tr>';
             $htmlsla .= '<td>'.$label.'</td>'; 
             foreach($datetimeArray as $dateLabel=>$timeArray) { 
		 
                    foreach($timeArray as $time)
                    { $htmlsla .= '<td>'.$data[$dateLabel][$time][$label].'</td>'; }   
             } 
	$htmlsla .= '</tr>';
        } 
        
$htmlsla .='</tbody>';
$htmlsla .='</table>';

echo $htmlsla;exit;