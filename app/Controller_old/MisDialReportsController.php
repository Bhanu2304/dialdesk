<?php
class MisDialReportsController extends AppController
{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses = array('EcrRecord','CallRecord','vicidialCloserLog','vicidialUserLog','CroneJob','AbandCallMaster');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid"))
        {
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
    
    public function index() 
    {
        $this->layout='user';
	
    }
       
    
    public function tagging_mis() {
        $this->layout='ajax';
        if($this->request->is("POST")){
            $catgoryArray=array();
            $TotalAns=array();
            
            $search     =$this->request->data['MisDialReports'];
            $clientId   = $this->Session->read('companyid');
            $Campagn  = $this->Session->read('campaignid');
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            
            $condition['ClientId']=$clientId;
            $start_date=date("Y-m-d",$startdate);
            $end_date=date("Y-m-d",$enddate);
            
            // START TOTAL ANSWERED CALL QUERY 
            
            $firstDay = date("Y-m-d",$startdate);
            $lastDay   = date("Y-m-d",$enddate);
            
          


	$FromDate='00:00:00';
	$ToDate='23:59:59';
	 $i=0;
	while($i <= 23)
	{
		$start_time_start=$FromDate;
		$event_date_start=$ToDate;
		
		$start_time_end=date("H:59:59",strtotime("$FromDate"));

		$NextDate=date("H:i:s",strtotime("$FromDate +1 hour"));
		$FromDate=$NextDate;
		
  $qry = "SELECT COUNT(*) `Total`,SEC_TO_TIME(p.parked_sec) `HoldTime`,SEC_TO_TIME(SUM(UNIX_TIMESTAMP(LEAST(IFNULL(t4.end_time,FROM_UNIXTIME(t2.end_epoch)),FROM_UNIXTIME(t2.end_epoch)))-UNIX_TIMESTAMP(GREATEST(IFNULL(t4.start_time,IF(queue_seconds='0',call_date,FROM_UNIXTIME((UNIX_TIMESTAMP(call_date)+queue_seconds)))),IF(queue_seconds='0',call_date,FROM_UNIXTIME((UNIX_TIMESTAMP(call_date)+queue_seconds))))))) AS TalkTime,SEC_TO_TIME(LEFT(SUM(dispo_sec),6))AS `dispotime`,SEC_TO_TIME(LEFT(SUM(dispo_sec),5)) `WrapTime`,
SUM(If((t2.status='SALE'  OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A'  OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI') and t2.`user` !='vdcl',1,0)) `Answered`,
SUM(IF(t2.status IS NULL OR t2.status='DROP',1,if(t2.USER='VDCL',1,0))) `Abandon`,
SUM(IF(t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI',UNIX_TIMESTAMP(LEAST(IFNULL(t4.end_time,FROM_UNIXTIME(t2.end_epoch)),FROM_UNIXTIME(t2.end_epoch)))-UNIX_TIMESTAMP(GREATEST(IFNULL(t4.start_time,IF(queue_seconds='0',call_date,FROM_UNIXTIME((UNIX_TIMESTAMP(call_date)+queue_seconds)))),IF(queue_seconds='0',call_date,FROM_UNIXTIME((UNIX_TIMESTAMP(call_date)+queue_seconds))))),0)) `TotalAcht`,
SUM(IF((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
SUM(IF((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
SUM(IF((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL') AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL')  and t2.queue_seconds<=60),1,0)) `Abndwithinsix`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL')  and t2.queue_seconds<=90),1,0)) `Abndwithinnine`
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid LEFT JOIN asterisk.park_log p ON t1.uniqueid=p.uniqueid  LEFT JOIN recording_log t4 ON t2.lead_id=t4.lead_id 
WHERE  time(t2.call_date)>='$start_time_start' AND time(t2.call_date)<'$start_time_end' and date(t2.call_date)>='$firstDay' AND date(t2.call_date)<='$lastDay'
and t2.term_reason!='AFTERHOURS'"; 
		
		 $this->vicidialCloserLog->useDbConfig = 'db2';
            $dtn= $this->vicidialCloserLog->query($qry);
            $dt=$dtn[0][0];
		//print_r($dt);die;
               
         

 $firstDayag = $firstDay;
  $lastDayag = $lastDay;


         $FromDateag=$firstDay. $start_time_end;
	 $ToDateag=$lastDay. $start_time_end;
         $userCnt=0;
	while(strtotime($FromDateag)<=strtotime($ToDateag))
	{
		$start_time_startag=$FromDateag;
		$event_date_startag=$ToDateag;
		
		$start_time_endag=date("Y-m-d H:i:s",strtotime("$FromDateag"));
                $FromDate3=date("Y-m-d 00:00:00",strtotime("$start_time_startag"));
		$NextDateag=date("Y-m-d H:i:s",strtotime("$FromDateag +1 day"));
                
                 
		 $FromDateag=$NextDateag;
                   $usrQry="SELECT SUM(IF((t1.event='LOGOUT' && event_date>='$FromDate3'),0,1)) 'UserLoggedIn' 
FROM asterisk.vicidial_user_log t1 JOIN (SELECT MAX(user_log_id) `user_log_id` FROM 
asterisk.vicidial_user_log WHERE  event_date>='$FromDate3' 
AND event_date<'$start_time_endag' GROUP BY USER) AS t2 WHERE t1.user_log_id=t2.user_log_id";
              $usrRsc=$this->vicidialCloserLog->query($usrQry);
		$usrDt=$usrRsc[0][0];     
            
                
		$dd=date("j");
                    if($usrDt['UserLoggedIn']=='')
                {
                        
			$userCnt =$dd;
                }
                else
                {
                   $userCnt=$userCnt+$usrDt['UserLoggedIn']; 
                }
		
                
                
        } 
            $userCnt=round($userCnt/$dd);       
		$cur=date("H:i:s");
		
		$timeLabel=date("H:i:s",strtotime($start_time_start));
		
                   
                   
                   
//		$usrRsc=mysql_query($usrQry);
//		$usrDt=  mysql_num_rows($usrRsc);
//		 if($start_time_start=='00:00:00' || $start_time_start=='01:00:00' || $start_time_start=='02:00:00'|| $start_time_start=='03:00:00'|| $start_time_start=='04:00:00'|| $start_time_start=='05:00:00'|| $start_time_start=='06:00:00'|| $start_time_start=='07:00:00'|| $start_time_start=='08:00:00'|| $start_time_start=='09:00:00' || $start_time_start=='10:00:00'|| $start_time_start=='22:00:00'|| $start_time_start=='23:00:00')
//                {
//                       $lastcnt = date("d");
//                   $usrDt= $lastcnt;
//                  
//                   
//                }
//                else
//                {
//                 $lastcnt = date("d");
//                   $usrDt=$usrDt+ $lastcnt;
//                }
                
                 $talk=explode(':',$dt['TalkTime']);
                $tadl=($talk[0]*60*60)+($talk[1]*60)+$talk[2];
                
		$totalHandle=$totalHandle+$tadl;
                
		$timeLabel=date("H:i:s",strtotime($start_time_start));
		$dateLabel=date("Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		$data['Offered'][$dateLabel][$timeLabel]=$dt['Answered']+$dt['Abandon'];
                $data['Handled'][$dateLabel][$timeLabel]=$dt['Answered'];
                $data['Calls Ans (within 20 Sec)'][$dateLabel][$timeLabel]=$dt['WIthinSLA'];
                $data['Total Calls Abandoned'][$dateLabel][$timeLabel]=$dt['Abandon'];
                $data['Abnd Within (20)'][$dateLabel][$timeLabel]=$dt['AbndWithinThresold'];
                $data['Average Aband Time'][$dateLabel][$timeLabel]='';
                 $data['Total Talk time'][$dateLabel][$timeLabel]=$dt['TalkTime'];
                
                $data['SL% (20 Sec)'][$dateLabel][$timeLabel]=round($dt['WIthinSLA']*100/$dt['Answered'])."%";
//		$data['SL% (60 Sec)'][$dateLabel][$timeLabel]=round($dt['WIthinSLASix']*100/$dt['Answered'])."%";
//                $data['SL% (90 Sec)'][$dateLabel][$timeLabel]=round($dt['WIthinSLANin']*100/$dt['Answered'])."%";
                // $data['AL %'][$dateLabel][$timeLabel]=round($dt['Answered']*100/($dt['Answered']+$dt['Abandon']))."%";
//                 $data['AHT'][$dateLabel][$timeLabel]=floor(round($dt['TotalAcht']/$dt['Answered'])/60).':'.round(round($dt['TotalAcht']/$dt['Answered'])%60,2);
                $data['ASA'][$dateLabel][$timeLabel]='';
		$data['AHT (In Sec)'][$dateLabel][$timeLabel]=round($dt['TotalAcht']/$dt['Answered']);
		$total=$dt['Answered']+ $dt['Abandon'];
		$tatlahct = $tatlahct+ $dt['TotalAcht'];
                $Answered=$Answered + $dt['Answered'];
//                $data['Calls Ans (within 60 Sec)'][$dateLabel][$timeLabel]=$dt['WIthinSLASix'];
//                $data['Abnd Within (60)'][$dateLabel][$timeLabel]=$dt['Abndwithinsix'];
//                $data['Calls Ans (within 90 Sec)'][$dateLabel][$timeLabel]=$dt['WIthinSLANin'];
//                $data['Abnd Within (90)'][$dateLabel][$timeLabel]=$dt['Abndwithinnine'];
//		//$data['Ababdoned (%)'][$dateLabel][$timeLabel]=round($dt['Abandon']*100/$total)."%";
                $data['Agent Logged In'][$dateLabel][$timeLabel]=$userCnt;
                
                
               // echo $dt['TotalAcht'].'<br>';
		//$data1['Ababdoned (%)']=+$data1['Ababdoned (%)']+round($dt['Abandon']*100/$total);
		
		
		$TotalCall+=$total;
	$i++;
        
        }//print_r($data);
        //die;
	
          $seconds1 = $totalHandle % 60;
$time1 = ($totalHandle - $seconds1) / 60;
$minutes1 = $time1 % 60;
$hours1 = ($time1 - $minutes1) / 60;

$minutes1 = ($minutes1<10?"0".$minutes1:"".$minutes1);
$seconds1 = ($seconds1<10?"0".$seconds1:"".$seconds1);
$hours1 = ($hours1<10?"0".$hours1:"".$hours1);

 $Totalh = ($hours1>0?$hours1.":":"00:").$minutes1.":".$seconds1;

//	foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
//		$data['Offered %'][$dateLabel][$timeLabel]=round($data['Total Calls Landed'][$dateLabel][$timeLabel]*100/$TotalCall); 
//                $data1['Offered %'] =$data1['Offered %']+$data['Offered %'][$dateLabel][$timeLabel];
//	} }
       
       

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $nn=0;
	 
               
                $head[]='Time Slot';
		 foreach($data as $dateLabel=>$timeArray) {  $head[]=$dateLabel; } 
	 $qfArr[$nn++] = $head;
     
$dataZ[] = 'Grand Total';
foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) {
  $dataY = array();
                
		$dataY[]=$timeLabel;

	 foreach($data as $dataLabel1=>$dataSub1) { 
	
               // $html.='<td>'.$data1[$dataLabel].'</td>';
             
		  $dataY[]=$dataSub1[$dateLabel][$timeLabel];
                  $dataZ[$dataLabel1] += $dataSub1[$dateLabel][$timeLabel];
                  }
	 $qfArr[$nn++] = $dataY;
         
	}}
		//print_r($dataZ); exit;
        
		
        $dataZ['SL% (20 Sec)'] = round($dataZ['Calls Ans (within 20 Sec)']*100/$dataZ['Handled'])."%";
//        $dataZ['SL% (60 Sec)'] =round($dataZ['Calls Ans (within 60 Sec)']*100/$dataZ['Handled'])."%";
//        $dataZ['SL% (90 Sec)'] =round($dataZ['Calls Ans (within 90 Sec)']*100/$dataZ['Handled'])."%";
       // $dataZ['AL %']= round($dataZ['Handled']*100/($dataZ['Handled']+$dataZ['Total Calls Abandoned']))."%";
        //$dataZ['AHT (In Sec)']=round($dataZ['AHT (In Sec)']/$dataZ['Handled']);
        //$dataZ['Ababdoned (%)']=round($dataZ['Total Calls Abandoned']*100/$TotalCall)."%";
             $dataZ['AHT (In Sec)']=round($tatlahct/$Answered);   
       
             
              $dataZ['Total Talk time']=$Totalh;
               
			   //$dataZ['AHT'] = round($dataZ['AHT (In Sec)']/60,0).':'.round($dataZ['AHT (In Sec)']%60,2);
		
			   //print_r($dataZ); exit;
        $qfArr[$nn++] = $dataZ;
        //return  $qfArr; 
                
            
         
            $this->export_excel($qfArr);     
        }
    }

    public function export_tagging_mis() {
        $this->layout='user';
        if($this->request->is("POST")){
            $catgoryArray=array();
            $TotalAns=array();
            
            $search     =$this->request->data['MisReports'];
            $clientId   = $this->Session->read('companyid');
            $Campagn  = $this->Session->read('campaignid');
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            
            $condition['ClientId']=$clientId;
            $start_date=date("Y-m-d",$startdate);
            $end_date=date("Y-m-d",$enddate);
            
            // START TOTAL ANSWERED CALL QUERY 
            
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            
            $campaignId ="campaign_id in ($Campagn)"; 
            
            $qry = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,COUNT(*) `Total`,
            SUM(IF(t2.status='SALE' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',1,0)) `Answered`,
            SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`,
            SUM(IF(t2.status='SALE' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',t1.length_in_sec,0)) `TotalAcht`,
            SUM(IF((t2.status='SALE' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
            SUM(IF((t2.status='SALE' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
            SUM(IF((t2.status='SALE' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND (t2.queue_seconds IS NULL OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
            WHERE date(t2.call_date) between '$start_date' and '$end_date' AND $campaignId AND t2.term_reason!='AFTERHOURS' GROUP BY DATE(t2.call_date)";

            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt1= $this->vicidialCloserLog->query($qry);
            
            
            //print_r($dt1);die;
            
            
            $qry1="SELECT DATE_FORMAT(cm.CallDate,'%d-%b-%Y') `date`,Category1,Category2,COUNT(Category1)`count`
                   FROM db_dialdesk.call_master cm 
                   WHERE cm.ClientId = '$clientId' AND date(cm.CallDate) between '$start_date' and '$end_date' GROUP BY cm.Category1,cm.Category2,date(cm.CallDate)";
            
            $dt=$this->CallRecord->query($qry1);
            
            //print_r($dt);
            foreach($dt1 as $row):
            $anstotal +=$row[0]['Answered'];
            $DataArr[] = $row[0]['date'];
            $DateArr3[$row[0]['date']] =  $row[0]['Answered'];
            //die;
            endforeach;
            foreach($dt as $row):
                 
                $key = $row['cm']['Category1'];
                if(key_exists($key, $category))
                {
                    $category[$key]['Answer'] +=$row[0]['count'];
                    $category[$key]['count'] +=$row[0]['count'];
                    $category[$key][$row[0]['date']]['count'] +=$row[0]['count'];
                    $category[$key]['Category2'][$row['cm']['Category2']]['count'] +=$row[0]['count'];
                    $category[$key]['Category2'][$row['cm']['Category2']][$row[0]['date']] +=$row[0]['count'];
                }
                else
                {
                    $category[$key]['Answer'] =$row[0]['count'];
                    $category[$key]['count'] =$row[0]['count'];
                    $category[$key][$row[0]['date']]['count'] =$row[0]['count'];
                    $category[$key]['Category2'][$row['cm']['Category2']]['count'] +=$row[0]['count'];
                    $category[$key]['Category2'][$row['cm']['Category2']][$row[0]['date']] =$row[0]['count'];
                }
                    
                
                $tagtotal +=$row[0]['count'];
                
                    
            endforeach;
                 
                 $exportArr1=array();$exportArr2=array(); $ii=0; $jj=0;
                 
                 
                 //$html .= "<tr><th><b>Summary</b></th>";
                 $exportArr1[$ii][] = "SUMMARY";
                 $exportArr2[$jj][] = "SUMMARY";
                 //$html .= "<th><b>MTD</b></th>";
                 $exportArr1[$ii][] = "MTD";
                 $exportArr2[$jj][] = "MTD";
                 //$html .= "%";
                 $exportArr1[$ii][] = "%";
                 $exportArr2[$jj][] = "%";
                 
                 $DataArr = array_unique($DataArr);
                 foreach($DataArr as $k)
                 {
                     $exportArr2[$jj][] = $k;
                 }
                 $jj++;$ii++;
                 //$html .= "</tr>";
                 //print_r($category); exit;
                 //print_r($html); exit;
                 $keys = array_keys($category);
                 //print_r($category); 
                 //$header = array('MTD'=>'','intat'=>'CLOSURE WITHIN TAT','outtat'=>'CLOSURE OUT OF TAT','openintat'=>'OPEN WITHIN TAT','openouttat'=>'OPEN OUT OF TAT');
                 
                 foreach($keys as $k)
                 {
                    foreach($DataArr as $k1)
                    {
                        $DataArr2[$k1]['answer'] += $DateArr3[$k1];
                        $DataArr2[$k1]['count'] += $category[$k][$k1]['count'];
                    }
                 }
                 //exit;
                //print_r($DataArr2); exit;
                //$html .= "<tr><th>Total Answered Calls</th>"; 
                //$html .= "<th>$anstotal</th>";
                //$html .= "<th></th>";
                 
                $exportArr2[$jj][] = "Total Answered Calls";
                $exportArr2[$jj][] = $anstotal;
                $exportArr2[$jj][] = 0;
                
                $exportArr1[$ii][] = "Total Answered Calls";
                $exportArr1[$ii][] = $anstotal;
                $exportArr1[$ii][] = 0;
                
                foreach($DataArr as $k1)
                {
                    
                   
                    //$html .= "<th><b>".$DataArr2[$k1]['answer']."</b></th>";
                    $exportArr2[$jj][]=$DateArr3[$k1];
                }
                
            
                //$html .= "</tr>";
                
                //$html .= "<tr><th>Tagging Calls</th>"; 
                //$html .= "<th>$tagtotal</th>";
                //$html .= "<th>".round($tagtotal*100/$anstotal,2)."%</th>";
                $ii++; $jj++;
                
                $exportArr2[$jj][] = "Tagging Calls";
                $exportArr2[$jj][] = $tagtotal;
                $exportArr2[$jj][] = round($tagtotal*100/$anstotal,2)."%";
                
                $exportArr1[$ii][] = "Tagging Calls";
                $exportArr1[$ii][] = $tagtotal;
                $exportArr1[$ii][] = round($tagtotal*100/$anstotal,2);
                
                
                
                foreach($DataArr as $k1)
                {
                    //$html .= "<th><b>".$DataArr2[$k1]['count']."</b></th>";
                    $exportArr2[$jj][] = $DataArr2[$k1]['count'];
                }
                //$html .= "</tr>";
                
                $jj++; $ii++;
                $exportArr2[$jj++][]='';
                
                
                foreach($keys as $k)
                {
                    //$html .= "<tr><th>$k</th>"; 
                    //$html .= "<th>".$category[$k]['count']."</th>";
                    //$html .= "<th>".round($category[$k]['count']*100/$tagtotal,2)."</th>";
                    
                    $exportArr2[$jj][] = $k;
                    $exportArr2[$jj][] = $category[$k]['count'];
                    $exportArr2[$jj][] = round($category[$k]['count']*100/$tagtotal,0)."%";
                    
                    $exportArr1[$ii][] = $k;
                    $exportArr1[$ii][] = $category[$k]['count'];
                    $exportArr1[$ii][] = round($category[$k]['count']*100/$tagtotal,0);
                    
                    foreach($DataArr as $k1)
                    {
                        //$html .= "<td>".$category[$k][$k1]['count']."</td>";
                        $exportArr2[$jj][] =$category[$k][$k1]['count'];
                    }
                    //$html .= "</tr>";
                    $ii++; $jj++;
                    
                    
                    $category2 = array_keys($category[$k]['Category2']);
                    
                    foreach($category2 as $c)
                    {
                        //$html .= "<tr><td>".$c."</td>";
                        //$html .= "<td>".$category[$k]['Category2'][$c]['count']."</td>";
                        //$html .= "<td>".round($category[$k]['Category2'][$c]['count']*100/$category[$k]['count'],2)."%</td>";
                        
                        $exportArr2[$jj][] = $c;
                        $exportArr2[$jj][] = $category[$k]['Category2'][$c]['count'];
                        $exportArr2[$jj][] = round($category[$k]['Category2'][$c]['count']*100/$category[$k]['count'],0);
                        
                        foreach($DataArr as $k1)
                        {
                            //$html .= "<td>".$category[$k]['Category2'][$c][$k1]['count']."</td>";
                            $exportArr2[$jj][] = $category[$k]['Category2'][$c][$k1];
                        }
                        //$html .= "</tr>";
                        $ii++; $jj++;
                    }
                    $ii++; $jj++;
                    $exportArr2[$jj++][]='';
                }
               
            //$exportArray1=array_merge(array(),$exportArr2);
            //$this->export_excel($exportArr1,$exportArr2); 
                
                //print_r($exportArr2);die;
                
                
  
            $this->set('data',$exportArr2);
        }
    }
    
    
    
    
    
    public function export_excel($exportArray2){
        require_once(APP . 'Lib' . DS . 'Classes' . DS . 'PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        
        
        //=====================================
           if($exportArray2!=NULL){
            $objPHPExcel->createSheet();

// Add some data to the second sheet, resembling some different data types
       $objWorksheet= $objPHPExcel->setActiveSheetIndex(0);
       //print_r($exportArray2[0]); EXIT;
  
     // print_r($exportArray2); die; 
       $array=array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M',14=>'N',15=>'O',16=>'P',17=>'Q',18=>'R',19=>'S',20=>'T',21=>'U',22=>'V',23=>'W',24=>'X',25=>'Y',26=>'Z');
// Rename 2nd sheet
//$objPHPExcel->getActiveSheet()->setTitle('Second sheet'); 
     
       //echo $ch; exit;
       
        $objPHPExcel->getActiveSheet()->getStyle("A1:L1")->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'F90417'),
                
            )
        ));      
          $objPHPExcel->getActiveSheet()->getStyle("A1:A26")->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'F90417'),
                
            )
        ));  
          
           $objPHPExcel->getActiveSheet()->getStyle("I2:L25")->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '3CFF54'),
                
            )
        ));  
          $objPHPExcel->getActiveSheet()->getStyle("A26:L26")->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'F90417'),
                
            )
        ));
          
      $ts=  array(
            'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => 'FFFFFF'),
        'size'  => 9,
        'name'  => 'Calibri'
    )
        );
      
      $objPHPExcel->getActiveSheet()->getStyle("A26:L26")->applyFromArray($ts);
          $objPHPExcel->getActiveSheet()->getStyle("A1:L1")->applyFromArray($ts); 
           $objPHPExcel->getActiveSheet()->getStyle("A1:A26")->applyFromArray($ts);
             $objWorksheet->fromArray($exportArray2);
            $styleArray = array(
      'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      )
  );
            $objPHPExcel->getActiveSheet()->getStyle("A1:L26")->applyFromArray($styleArray);
           }
            
        //====================================
            
            
            

    $fileName = date("m-d-Y") . '.xlsx';
    if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');
    header('Content-Type: application/force-download');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'. $fileName . '"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');

    header('Cache-control: no-cache, pre-check=0, post-check=0');
    header('Cache-control: private');
    header('Pragma: private');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // any date in the past
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->setIncludeCharts(TRUE);
    $objWriter->save('php://output');
   
    }

}
?>