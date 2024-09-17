<?php
class ObCdrReportsController extends AppController
{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses = array('EcrRecord','CallRecord','vicidialCloserLog','vicidialLog','vicidialUserLog','CroneJob','AbandCallMaster','RegistrationMaster');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid") && !$this->Session->check("admin_id"))
        {
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
    
    public function index() 
    {
         $this->layout='user';
        if($this->request->is("POST")){
        $campaignId ="campaign_id in(". $this->Session->read('campaignid').")";
        $clientId   = $this->Session->read('companyid');
        
        $search     =$this->request->data['CdrReports'];
       
            $Campagn  = $this->Session->read('campaignid');
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];
           
        if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
	if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
	//echo $ToDate;die;
	while(strtotime($FromDate)<strtotime($ToDate))
	{
		$start_time_start=$FromDate;
		$event_date_start=$ToDate;
		
		$start_time_end=date("Y-m-d 23:59:59",strtotime("$FromDate"));

		$NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 day"));
		$FromDate=$NextDate;
	
		

$qry = "SELECT COUNT(*) `Total`,
SUM(If((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A') and `user` !='vdcl',1,0)) `Answered`,
SUM(IF(t2.status IS NULL OR t2.status='DROP',1,if(t2.USER='VDCL',1,0))) `Abandon`,
SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A',t1.length_in_sec,0)) `TotalAcht`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL') AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL') AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end'
and $campaignId and t2.term_reason!='AFTERHOURS'";
		if($clientId!='375')
                {
		$this->vicidialCloserLog->useDbConfig = 'db2';
                }
                else
                {
                    $this->vicidialCloserLog->useDbConfig = 'db6';
                }
		$dt=$this->vicidialCloserLog->query($qry);
               // print_r($dt);die;
		// Usewr Loggedd In
		
		
		$timeLabel=date("d-M-Y",strtotime($start_time_start));
		$dateLabel=date("F-Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		
		$data['Offered %'][$dateLabel][$timeLabel]='';
		$total=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
		$data['Total Calls Landed'][$dateLabel][$timeLabel]=$total;
		$data['Total Calls Answered'][$dateLabel][$timeLabel]=$dt[0][0]['Answered'];
		$data['Total Calls Abandoned'][$dateLabel][$timeLabel]=$dt[0][0]['Abandon'];
		$data['AHT (In Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
		$data['Calls Ans (within 20 Sec)'][$dateLabel][$timeLabel]=$dt[0][0]['WIthinSLA'];
		$data['Abnd Within Threshold'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinThresold'];
		$data['Abnd After Threshold'][$dateLabel][$timeLabel]=$dt[0][0]['AbndAfterThresold'];
		$data['Ababdoned (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['Abandon']*100/$total);
		$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
		//$data['Agent Logged In'][$dateLabel][$timeLabel]=$usrDt['UserLoggedIn'];
		
		$TotalCall+=$total;
	}
	
         

	foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
		$data['Offered %'][$dateLabel][$timeLabel]=round($data['Total Calls Landed'][$dateLabel][$timeLabel]*100/$TotalCall);  
	} }
        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
        }
    }
    
    
    
  
      
    public function cdr() 
    {
         $this->layout='user';
        if($this->request->is("POST")){
        $campaignId ="campaign_id in(". $this->Session->read('campaignid').")";
        $clientId   = $this->Session->read('companyid');
        
        $search     =$this->request->data['CdrReports'];
       
            $Campagn  = $this->Session->read('campaignid');
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];
           
        if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
	if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
	//echo $ToDate;die;
	while(strtotime($FromDate)<strtotime($ToDate))
	{
		
		$start_time_start=$FromDate;
		$event_date_start=date("Y-m-d 08:00:00",strtotime("$FromDate +1 hours"));
		
		$NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 hours"));
		$FromDate=$NextDate;
		
		$start_time_end=$NextDate;
		if(date('H',strtotime($NextDate))=="00") { $FromDate=date("Y-m-d H:i:s",strtotime("$NextDate +1 hours")); }
	
		 
 

$qry = "SELECT COUNT(*) `Total`,
SUM(If((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') and user !='vdcl',1,0)) `Answered`,
SUM(IF(t2.status IS NULL OR t2.status='DROP',1,if(t2.USER='VDCL',1,0))) `Abandon`,
SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA',t1.length_in_sec,0)) `TotalAcht`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL') AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL') AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end'
and $campaignId and t2.term_reason!='AFTERHOURS'";
		if($clientId!='375')
                {
		$this->vicidialCloserLog->useDbConfig = 'db2';
                }
                else
                {
                    $this->vicidialCloserLog->useDbConfig = 'db6';
                }
		$dt=$this->vicidialCloserLog->query($qry);
               // print_r($dt);die;
		// Usewr Loggedd In
		
		
		$timeLabel=date("H:i",strtotime($start_time_start))." To ".date("H:i",strtotime($start_time_end));
		$dateLabel=date("F-Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		
		$data['Offered %'][$dateLabel][$timeLabel]='';
		$total=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
		$data['Total Calls Landed'][$dateLabel][$timeLabel]=$total;
		$data['Total Calls Answered'][$dateLabel][$timeLabel]=$dt[0][0]['Answered'];
		$data['Total Calls Abandoned'][$dateLabel][$timeLabel]=$dt[0][0]['Abandon'];
		$data['AHT (In Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
		$data['Calls Ans (within 20 Sec)'][$dateLabel][$timeLabel]=$dt[0][0]['WIthinSLA'];
		$data['Abnd Within Threshold'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinThresold'];
		$data['Abnd After Threshold'][$dateLabel][$timeLabel]=$dt[0][0]['AbndAfterThresold'];
		$data['Ababdoned (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['Abandon']*100/$total);
		$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
		//$data['Agent Logged In'][$dateLabel][$timeLabel]=$usrDt['UserLoggedIn'];
		
		$TotalCall+=$total;
	}
	
         

	foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
		$data['Offered %'][$dateLabel][$timeLabel]=round($data['Total Calls Landed'][$dateLabel][$timeLabel]*100/$TotalCall);  
	} }
        
        
        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
        }
    }
    
    
  public  function call_mis()
        {
        if($this->request->is("POST")){
        $campaignId ="campaign_id in(". $this->Session->read('campaignid').")";
        $clientId   = $this->Session->read('companyid');
        
        $search     =$this->request->data['CdrReports'];
       
            $Campagn  = $this->Session->read('campaignid');
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];
           
        if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
	if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
	//echo $ToDate;die;
	while(strtotime($FromDate)<strtotime($ToDate))
	{
		$start_time_start=$FromDate;
		$event_date_start=$ToDate;
		
		$start_time_end=date("Y-m-d 23:59:59",strtotime("$FromDate"));

		$NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 day"));
		$FromDate=$NextDate;
	
		
  $qry = "SELECT COUNT(*) `Total`,
SUM(If((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A') and `user` !='vdcl',1,0)) `Answered`,
SUM(IF(t2.status IS NULL OR t2.status='DROP',1,if(t2.USER='VDCL',1,0))) `Abandon`,
SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A',t1.length_in_sec,0)) `TotalAcht`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL') AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL') AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end'
and $campaignId and t2.term_reason!='AFTERHOURS'";
		if($clientId!='375')
                {
		$this->vicidialCloserLog->useDbConfig = 'db2';
                }
                else
                {
                    $this->vicidialCloserLog->useDbConfig = 'db6';
                }
		$dt=$this->vicidialCloserLog->query($qry);
               // print_r($dt);die;
		// Usewr Loggedd In
		$usrQry="Select 
					sum(if((t1.event='LOGIN' || event_date>='$start_time_start'),1,0)) 'UserLoggedIn' 
				FROM 
					asterisk.vicidial_user_log t1 
					join 
					(Select max(user_log_id) `user_log_id` 
					From asterisk.vicidial_user_log 
					Where campaign_id='dialdesk' and event_date>='$event_date_start' and event_date<'$start_time_end' group by user) as t2 
				Where t1.user_log_id=t2.user_log_id";
		$usrRsc=mysql_query($usrQry);
		$usrDt=mysql_fetch_assoc($usrRsc);
		
		$timeLabel=date("d-M-Y",strtotime($start_time_start));
		$dateLabel=date("F-Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		
		$data['Offered %'][$dateLabel][$timeLabel]='';
		$total=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
		$data['Total Calls Landed'][$dateLabel][$timeLabel]=$total;
		$data['Total Calls Answered'][$dateLabel][$timeLabel]=$dt[0][0]['Answered'];
		$data['Total Calls Abandoned'][$dateLabel][$timeLabel]=$dt[0][0]['Abandon'];
		$data['AHT (In Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
		$data['Calls Ans (within 20 Sec)'][$dateLabel][$timeLabel]=$dt[0][0]['WIthinSLA'];
		$data['Abnd Within Threshold'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinThresold'];
		$data['Abnd After Threshold'][$dateLabel][$timeLabel]=$dt[0][0]['AbndAfterThresold'];
		$data['Ababdoned (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['Abandon']*100/$total);
		$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
		//$data['Agent Logged In'][$dateLabel][$timeLabel]=$usrDt['UserLoggedIn'];
		
		$TotalCall+=$total;
	}
	
         

	foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
		$data['Offered %'][$dateLabel][$timeLabel]=round($data['Total Calls Landed'][$dateLabel][$timeLabel]*100/$TotalCall);  
	} }
        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
        }
        
       // $this->export_excel($data,$datetimeArray); 
        }

public  function hour_reports()
        {
        if($this->request->is("POST")){
        $campaignId ="campaign_id in(". $this->Session->read('campaignid').")";
        $clientId   = $this->Session->read('companyid');
        
        $search     =$this->request->data['CdrReports'];
       
            $Campagn  = $this->Session->read('campaignid');
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];
           
        if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
	if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
	//echo $ToDate;die;
	while(strtotime($FromDate)<strtotime($ToDate))
	{
		$start_time_start=$FromDate;
		$event_date_start=date("Y-m-d 08:00:00",strtotime("$FromDate +1 hours"));
		
		$NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 hours"));
		$FromDate=$NextDate;
		
		$start_time_end=$NextDate;
		if(date('H',strtotime($NextDate))=="00") { $FromDate=date("Y-m-d H:i:s",strtotime("$NextDate +1 hours")); }
	
		 
$qry = "SELECT COUNT(*) `Total`,
SUM(If((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') and user !='vdcl',1,0)) `Answered`,
SUM(IF(t2.status IS NULL OR t2.status='DROP',1,if(t2.USER='VDCL',1,0))) `Abandon`,
SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA',t1.length_in_sec,0)) `TotalAcht`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL') AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL') AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end'
and $campaignId and t2.term_reason!='AFTERHOURS'";
		if($clientId!='375')
                {
		$this->vicidialCloserLog->useDbConfig = 'db2';
                }
                else
                {
                    $this->vicidialCloserLog->useDbConfig = 'db6';
                }
		$dt=$this->vicidialCloserLog->query($qry);
               // print_r($dt);die;
		// Usewr Loggedd In
		
		
		$timeLabel=date("H:i",strtotime($start_time_start))." To ".date("H:i",strtotime($start_time_end));
		$dateLabel=date("F-Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		
		$data['Offered %'][$dateLabel][$timeLabel]='';
		$total=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
		$data['Total Calls Landed'][$dateLabel][$timeLabel]=$total;
		$data['Total Calls Answered'][$dateLabel][$timeLabel]=$dt[0][0]['Answered'];
		$data['Total Calls Abandoned'][$dateLabel][$timeLabel]=$dt[0][0]['Abandon'];
		$data['AHT (In Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
		$data['Calls Ans (within 20 Sec)'][$dateLabel][$timeLabel]=$dt[0][0]['WIthinSLA'];
		$data['Abnd Within Threshold'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinThresold'];
		$data['Abnd After Threshold'][$dateLabel][$timeLabel]=$dt[0][0]['AbndAfterThresold'];
		$data['Ababdoned (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['Abandon']*100/$total);
		$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
		//$data['Agent Logged In'][$dateLabel][$timeLabel]=$usrDt['UserLoggedIn'];
		
		$TotalCall+=$total;
	}
	
         

	foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
		$data['Offered %'][$dateLabel][$timeLabel]=round($data['Total Calls Landed'][$dateLabel][$timeLabel]*100/$TotalCall);  
	} }
        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
        }
        
       // $this->export_excel($data,$datetimeArray); 
        }
        
    public function detailscdr(){
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
                $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
                $client = array('All'=>'All')+$client;
                $this->set('client',$client); 
            }
            else
            {
                $clientId   = $this->Session->read('companyid');
                $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','company_id'=>$clientId),'order'=>array('Company_name'=>'asc')));
                $this->set('client',$client); 
            }
        if($this->request->is("POST")){
            //$campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').")";
            $clientId   = $this->Session->read('companyid');
            $search     =$this->request->data['ObCdrReports'];
            $Campagn  = $this->Session->read('campaignid');
			$Campagn  = $this->Session->read('GroupId');
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];
            $clientId	=  $search['clientID'];
           
          if($clientId=='All'){
            	$this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A'"); 
            	//print_r($ClientInfo[0][0]); 
            	$campaignId = $ClientInfo[0][0]['campaign_id']; 
    			$campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

    		$campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
    		$campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
            
          $qry="SELECT DATE(t2.call_date) AS CallDate,FROM_UNIXTIME(t2.start_epoch) AS StartTime,FROM_UNIXTIME(t2.end_epoch) AS Endtime,LEFT(t2.phone_number,10) AS PhoneNumber,
t2.`user` AS Agent,vu.full_name as full_name,if(t2.`user`='VDAD','Not Connected','Connected') calltype,t2.status as status,if(t2.`list_id`='998','Mannual','Auto') dialmode,t2.campaign_id as campaign_id,t2.lead_id as lead_id,
 t2.length_in_sec AS LengthInSec,
            SEC_TO_TIME(t2.length_in_sec) AS LengthInMin,
            t2.length_in_sec AS CallDuration,
            t2.`status` AS CallStatus,
            t3.`pause_sec` AS PauseSec,
            t3.`wait_sec` AS WaitSec,
            t3.`talk_sec` AS TalkSec,t3.dispo_sec AS DispoSec
  FROM asterisk.vicidial_log t2
            LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid left join vicidial_users vu on t2.user=vu.user
            WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate' and DATE(t2.call_date) BETWEEN DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND CURDATE() AND $campaignId
            AND t2.lead_id IS NOT NULL"; 
	   
          
            
            if($clientId!='375')
                {
		$this->vicidialLog->useDbConfig = 'db2';
                }
                else
                {
                    $this->vicidialLog->useDbConfig = 'db6';
                }
                $dt=$this->vicidialLog->query($qry);
            //print_r($dt); exit;
            $this->set('Data',$dt);
            
            }
            
       
        }
        public function click_to_call(){
            $this->layout='user';
            if($this->Session->read('role') =="admin"){
                    $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
                    $client = array('All'=>'All')+$client;
                    $this->set('client',$client); 
                }
                else
                {
                    $clientId   = $this->Session->read('companyid');
                    $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','company_id'=>$clientId),'order'=>array('Company_name'=>'asc')));
                    $this->set('client',$client); 
                }
            if($this->request->is("POST")){
                //$campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').")";
                $clientId   = $this->Session->read('companyid');
                $search     =$this->request->data['ObCdrReports'];
                $Campagn  = $this->Session->read('campaignid');
                $Campagn  = $this->Session->read('GroupId');
                $FromDate=$search['startdate'];
                $ToDate=$search['enddate'];
                $clientId	=  $search['clientID'];
               
              if($clientId=='All'){
                    $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
                   $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A'"); 
                    //print_r($ClientInfo[0][0]); 
                    $campaignId = $ClientInfo[0][0]['campaign_id']; 
                    $campaignId =   "t2.campaign_id in(".$campaignId.")";
                }else {
    
                $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));
    
                $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
                }
                
              $qry="select right(a.Driver_num,10) as Agent_No,right(a.customer_num,10) as MSISDN,date(a.call_time) Call_Date,b.start_time,b.end_time,b.length_in_sec,a.call_status from cdr_table a left join call_log b on a.uniqueid1=b.uniqueid where date(a.call_time) >= '$FromDate' and date(a.call_time) <= '$ToDate'"; 
           
              
                
                if($clientId!='375')
                    {
                        $this->vicidialLog->useDbConfig = 'db8';
                    }
                    else
                    {
                        $this->vicidialLog->useDbConfig = 'db6';
                    }
                    $dt=$this->vicidialLog->query($qry);
                //print_r($dt); exit;
                $this->set('Data',$dt);
                
                }
                
           
            } 
            
            public function click_to_call_excel(){
                $this->layout='user';
                if($this->Session->read('role') =="admin"){
                        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
                        $client = array('All'=>'All')+$client;
                        $this->set('client',$client); 
                    }
                    else
                    {
                        $clientId   = $this->Session->read('companyid');
                        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','company_id'=>$clientId),'order'=>array('Company_name'=>'asc')));
                        $this->set('client',$client); 
                    }
                if($this->request->is("POST")){
                    //$campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').")";
                    $clientId   = $this->Session->read('companyid');
                    $search     =$this->request->data['ObCdrReports'];
                    $Campagn  = $this->Session->read('campaignid');
                    $Campagn  = $this->Session->read('GroupId');
                    $FromDate=$search['startdate'];
                    $ToDate=$search['enddate'];
                    $clientId	=  $search['clientID'];
                   
                  if($clientId=='All'){
                        $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
                       $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A'"); 
                        //print_r($ClientInfo[0][0]); 
                        $campaignId = $ClientInfo[0][0]['campaign_id']; 
                        $campaignId =   "t2.campaign_id in(".$campaignId.")";
                    }else {
        
                    $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));
        
                    $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
                    $campaignId =   "t2.campaign_id in(".$campaignId.")";
                    }
                    
                  $qry="select right(a.Driver_num,10) as Agent_No,right(a.customer_num,10) as MSISDN,date(a.call_time) Call_Date,b.start_time,b.end_time,b.length_in_sec,a.call_status from cdr_table a left join call_log b on a.uniqueid1=b.uniqueid where date(a.call_time) >= '$FromDate' and date(a.call_time) <= '$ToDate'"; 
               
                  
                    
                    if($clientId!='375')
                        {
                            $this->vicidialLog->useDbConfig = 'db8';
                        }
                        else
                        {
                            $this->vicidialLog->useDbConfig = 'db6';
                        }
                        $dt=$this->vicidialLog->query($qry);
                    //print_r($dt); exit;
                    $this->set('Data',$dt);
                    
                    }
                    
               
                } 
     
 public function sharedscdr(){
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
          }else{
              $clientId   = $this->Session->read('companyid');
              $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','company_id'=>$clientId),'order'=>array('Company_name'=>'asc')));
              $this->set('client',$client); 
          }
        if($this->request->is("POST")){
            //$campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').")";
            //$clientId   = $this->Session->read('companyid');
            $search     =$this->request->data['ObCdrReports'];
            $Campagn  = $this->Session->read('campaignid');
			$Campagn  = $this->Session->read('GroupId');
            
            // $FromDate=$search['startdate'];
            // $ToDate=$search['enddate'];

            $FromDate = date("Y-m-d", strtotime($search['startdate']));  
            $ToDate = date("Y-m-d", strtotime($search['enddate']));
            $clientId	=  $search['clientID'];

            if($clientId=='All'){
            	$this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A'"); 
            	//print_r($ClientInfo[0][0]); 
            	$campaignId = $ClientInfo[0][0]['campaign_id']; 
    			$campaignId =   "t2.campaign_id in(".$campaignId.")";

                $DialerConnectionPage = $ClientInfo[0][0]['DialerConnectionPage'];
			    $GroupId = $ClientInfo[0][0]['GroupId']; 
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

    		$campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
    		$campaignId =   "t2.campaign_id in(".$campaignId.")";

            $DialerConnectionPage = $ClientInfo['RegistrationMaster']['DialerConnectionPage'];
			$GroupId = $ClientInfo['RegistrationMaster']['GroupId']; 
            }
           
            //$dataArr = $this->CallRecord->query("SELECT * FROM registration_master WHERE company_id='$clientId' limit 1");
          //print_r($GroupId);die;
            
                
                      
                        
            $campaignId ="t2.campaign_id in(".$GroupId.")";
            if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
            if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
            
//           $qry="SELECT t2.lead_id lead_id,
//             t2.`user` AS Agent,
//             LEFT(t2.phone_number,10) AS PhoneNumber,
//             DATE(t2.call_date) AS CallDate,
//             FROM_UNIXTIME(t2.start_epoch) AS StartTime,
//             FROM_UNIXTIME(t2.end_epoch) AS Endtime,
//             t2.length_in_sec AS LengthInSec,
//             SEC_TO_TIME(t2.length_in_sec) AS LengthInMin,
//             t2.length_in_sec AS CallDuration,
//             t2.`status` AS CallStatus,
//             t3.`pause_sec` AS PauseSec,
//             t3.`wait_sec` AS WaitSec,
//             t3.`talk_sec` AS TalkSec,
//             t3.dispo_sec AS DispoSec,t2.campaign_id,
//             if(t3.dispo_sec Is Null,0,if(t3.sub_status='LOGIN'  or t3.sub_status='Feed' or t3.talk_sec=t3.dispo_sec or t3.talk_sec=0,1,if(t3.dispo_sec>100,(t3.dispo_sec-(t3.dispo_sec/100)*100),t3.dispo_sec))) WrapTime

//   FROM asterisk.vicidial_log t2
//             LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid 
//             WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate' AND t2.campaign_id='dialdesk'
//             AND t2.lead_id IS NOT NULL"; 

            $qry="SELECT DATE(t2.call_date) AS CallDate,FROM_UNIXTIME(t2.start_epoch) AS StartTime,FROM_UNIXTIME(t2.end_epoch) AS Endtime,LEFT(t2.phone_number,10) AS PhoneNumber,
            t2.`user` AS Agent,vu.full_name as full_name,if(t2.`user`='VDAD','Not Connected','Connected') calltype,t2.status as status,if(t2.`list_id`='998','Mannual','Auto') dialmode,t2.campaign_id as campaign_id,t2.lead_id as lead_id,
            t2.length_in_sec AS LengthInSec,
                        SEC_TO_TIME(t2.length_in_sec) AS LengthInMin,
                        t2.length_in_sec AS CallDuration,
                        t2.`status` AS CallStatus,
                        t3.`pause_sec` AS PauseSec,
                        t3.`wait_sec` AS WaitSec,
                        t3.`talk_sec` AS TalkSec,t3.dispo_sec AS DispoSec
            FROM asterisk.vicidial_log t2
                        LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid left join vicidial_users vu on t2.user=vu.user
                        WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate' AND t2.campaign_id='dialdesk'
                        AND t2.lead_id IS NOT NULL"; 
	   

            if($clientId!='375')
            {
                    $this->vicidialLog->useDbConfig = 'db2';
            }
            else
            {
                $this->vicidialLog->useDbConfig = 'db6';
            }
            $dt=$this->vicidialLog->query($qry);
            $adt=array();
            foreach ($dt as $key => $value) {
            	$qry = "SELECT * FROM aband_call_master acm where PhoneNo='".$value[0]['PhoneNumber']."' and date(Callbackdate)=date('".$value[0]['CallDate']."') limit 1";
            	
            	$record = $this->EcrRecord->query($qry);
            	$value[0]['client_name'] = $record[0]['acm']['CompanyName'];
 
            	$adt[] = $value;
            }

            $this->set('Data',$adt);
          // print_r($adt); die;
       
        }
    }

    public function sharedscdr_external(){
        $this->layout='user';
	    $ClientId = $this->Session->read('companyid');
    
             $search=$this->request->data['AbandonCallReports'];
           
             if($this->Session->read('role') =="admin"){
            
            
            $this->set('client',$client); 
        }
        if($this->request->is("POST")){
            //$campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').")";
            //$clientId   = $this->Session->read('companyid');
            $search     =$this->request->data['ObCdrReports'];
            $Campagn  = $this->Session->read('campaignid');
			$Campagn  = $this->Session->read('GroupId');
            
            // $FromDate=$search['startdate'];
            // $ToDate=$search['enddate'];

            $FromDate = date("Y-m-d", strtotime($search['startdate']));  
            $ToDate = date("Y-m-d", strtotime($search['enddate']));
            //$clientId	=  $search['clientID'];
            $clientId = $this->Session->read('companyid');

            

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

    		$campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
    		$campaignId =   "t2.campaign_id in(".$campaignId.")";

            $DialerConnectionPage = $ClientInfo['RegistrationMaster']['DialerConnectionPage'];
			$GroupId = $ClientInfo['RegistrationMaster']['GroupId']; 

            //echo $campaignId;die;

           
            //$dataArr = $this->CallRecord->query("SELECT * FROM registration_master WHERE company_id='$clientId' limit 1");
          //print_r($GroupId);die;
            
                
                        if($GroupId !=""){
                        
            $campaignId ="t2.campaign_id in(".$GroupId.")";
            if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
            if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
            
//           $qry="SELECT t2.lead_id lead_id,
//             t2.`user` AS Agent,
//             LEFT(t2.phone_number,10) AS PhoneNumber,
//             DATE(t2.call_date) AS CallDate,
//             FROM_UNIXTIME(t2.start_epoch) AS StartTime,
//             FROM_UNIXTIME(t2.end_epoch) AS Endtime,
//             t2.length_in_sec AS LengthInSec,
//             SEC_TO_TIME(t2.length_in_sec) AS LengthInMin,
//             t2.length_in_sec AS CallDuration,
//             t2.`status` AS CallStatus,
//             t3.`pause_sec` AS PauseSec,
//             t3.`wait_sec` AS WaitSec,
//             t3.`talk_sec` AS TalkSec,
//             t3.dispo_sec AS DispoSec,t2.campaign_id,
//             if(t3.dispo_sec Is Null,0,if(t3.sub_status='LOGIN'  or t3.sub_status='Feed' or t3.talk_sec=t3.dispo_sec or t3.talk_sec=0,1,if(t3.dispo_sec>100,(t3.dispo_sec-(t3.dispo_sec/100)*100),t3.dispo_sec))) WrapTime

//   FROM asterisk.vicidial_log t2
//             LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid 
//             WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate' AND t2.campaign_id='dialdesk'
//             AND t2.lead_id IS NOT NULL"; 

            $qry="SELECT DATE(t2.call_date) AS CallDate,FROM_UNIXTIME(t2.start_epoch) AS StartTime,FROM_UNIXTIME(t2.end_epoch) AS Endtime,LEFT(t2.phone_number,10) AS PhoneNumber,
            t2.`user` AS Agent,vu.full_name as full_name,if(t2.`user`='VDAD','Not Connected','Connected') calltype,t2.status as status,if(t2.`list_id`='998','Mannual','Auto') dialmode,t2.campaign_id as campaign_id,t2.lead_id as lead_id,
            t2.length_in_sec AS LengthInSec,
                        SEC_TO_TIME(t2.length_in_sec) AS LengthInMin,
                        t2.length_in_sec AS CallDuration,
                        t2.`status` AS CallStatus,
                        t3.`pause_sec` AS PauseSec,
                        t3.`wait_sec` AS WaitSec,
                        t3.`talk_sec` AS TalkSec,t3.dispo_sec AS DispoSec
            FROM asterisk.vicidial_log t2
                        LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid left join vicidial_users vu on t2.user=vu.user
                        WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate' AND t2.campaign_id='dialdesk'
                        AND t2.lead_id IS NOT NULL"; 

                        
	   

            if($clientId!='375')
            {
                    $this->vicidialLog->useDbConfig = 'db2';
            }
            else
            {
                $this->vicidialLog->useDbConfig = 'db6';
            }

            $dt=$this->vicidialLog->query($qry);
            $adt=array();
            foreach ($dt as $key => $value) {
            	$qry = "SELECT * FROM aband_call_master acm where PhoneNo='".$value[0]['PhoneNumber']."' and date(Callbackdate)=date('".$value[0]['CallDate']."') and ClientId='$clientId' limit 1";
            	
            	$record = $this->EcrRecord->query($qry);
                if(!empty($record))
                {
                    $value[0]['client_name'] = $record[0]['acm']['CompanyName'];

                    $adt[] = $value;
                }
            	
 
            	
            }

            $this->set('Data',$adt);

            
            }
            else{
              $this->Session->setFlash('<span style="color:red;" >Please contact admin for adding group id.</span>');  
            }
       
        }
    }



    public function cdr_mis(){
        if($this->request->is("POST")){
            //$campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').")";
            $clientId   = $this->Session->read('companyid');
            $search     =$this->request->data['ObCdrReports'];
            $Campagn  = $this->Session->read('campaignid');
			$Campagn  = $this->Session->read('GroupId');
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];
            $clientId	=  $search['clientID'];
           
          if($clientId=='All'){
            	$this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A'"); 
            	//print_r($ClientInfo[0][0]); 
            	$campaignId = $ClientInfo[0][0]['campaign_id']; 
    			$campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

    		$campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
    		$campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
            
          $qry="SELECT DATE(t2.call_date) AS CallDate,FROM_UNIXTIME(t2.start_epoch) AS StartTime,FROM_UNIXTIME(t2.end_epoch) AS Endtime,LEFT(t2.phone_number,10) AS PhoneNumber,
t2.`user` AS Agent,vu.full_name as full_name,if(t2.`user`='VDAD','Not Connected','Connected') calltype,t2.status as status,if(t2.`list_id`='998','Mannual','Auto') dialmode,t2.campaign_id as campaign_id,t2.lead_id as lead_id,
 t2.length_in_sec AS LengthInSec,
            SEC_TO_TIME(t2.length_in_sec) AS LengthInMin,
            t2.length_in_sec AS CallDuration,
            t2.`status` AS CallStatus,
            t3.`pause_sec` AS PauseSec,
            t3.`wait_sec` AS WaitSec,
            t3.`talk_sec` AS TalkSec,
            t3.dispo_sec AS DispoSec,
            t3.dead_sec as dead_sec
  FROM asterisk.vicidial_log t2
            LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid left join vicidial_users vu on t2.user=vu.user
            WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate' AND $campaignId
            AND t2.lead_id IS NOT NULL"; 
	   
          
            
            if($clientId!='375')
                {
		$this->vicidialLog->useDbConfig = 'db2';
                }
                else
                {
                    $this->vicidialLog->useDbConfig = 'db6';
                }
                $dt=$this->vicidialLog->query($qry);
            //print_r($dt); exit;
            $this->set('Data',$dt);
            
            }
    }
 public function cdr_mis_share(){
        if($this->request->is("POST")){
            $campaignId =   "t2.campaign_id in(". $this->Session->read('campaignid').")";
            $clientId   =   $this->Session->read('companyid');
            $search     =   $this->request->data['ObCdrReports'];
            $Campagn    =   $this->Session->read('campaignid');
            // $FromDate   =   $search['startdate'];
            // $ToDate     =   $search['enddate'];

            $FromDate = date("Y-m-d", strtotime($search['startdate']));  
            $ToDate = date("Y-m-d", strtotime($search['enddate']));
            $clientId	=  $search['clientID'];

            if($clientId=='All'){
            	$this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A'"); 
            	//print_r($ClientInfo[0][0]); 
            	$campaignId = $ClientInfo[0][0]['campaign_id']; 
    			$campaignId =   "t2.campaign_id in(".$campaignId.")";

                $DialerConnectionPage = $ClientInfo[0][0]['DialerConnectionPage'];
			    $GroupId = $ClientInfo[0][0]['GroupId']; 
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

    		$campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
    		$campaignId =   "t2.campaign_id in(".$campaignId.")";

            $DialerConnectionPage = $ClientInfo['RegistrationMaster']['DialerConnectionPage'];
			$GroupId = $ClientInfo['RegistrationMaster']['GroupId']; 
            }

			
            // $dataArr    =   $this->CallRecord->query("SELECT * FROM registration_master WHERE company_id='$clientId' limit 1");
            // //print_r($dataArr);die;
            // $DialerConnectionPage   =   $dataArr['0']['registration_master']['DialerConnectionPage'];
            // $GroupId                =   $dataArr['0']['registration_master']['GroupId']; 
            
            
                $campaignId ="t2.campaign_id in(".$GroupId.")";

                if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
                if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }

                $qry="SELECT DATE(t2.call_date) AS CallDate,FROM_UNIXTIME(t2.start_epoch) AS StartTime,FROM_UNIXTIME(t2.end_epoch) AS Endtime,LEFT(t2.phone_number,10) AS PhoneNumber,
t2.`user` AS Agent,vu.full_name as full_name,if(t2.`user`='VDAD','Not Connected','Connected') calltype,t2.status as status,if(t2.`list_id`='998','Mannual','Auto') dialmode,t2.campaign_id as campaign_id,t2.lead_id as lead_id,
 t2.length_in_sec AS LengthInSec,
            SEC_TO_TIME(t2.length_in_sec) AS LengthInMin,
            t2.length_in_sec AS CallDuration,
            t2.`status` AS CallStatus,
            t3.`pause_sec` AS PauseSec,
            t3.`wait_sec` AS WaitSec,
            t3.`talk_sec` AS TalkSec,t3.dispo_sec AS DispoSec
  FROM asterisk.vicidial_log t2
            LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid left join vicidial_users vu on t2.user=vu.user
            WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate' AND t2.campaign_id='dialdesk'
            AND t2.lead_id IS NOT NULL"; 

                if($clientId!='375')
                {
		$this->vicidialLog->useDbConfig = 'db2';
                }
                else
                {
                    $this->vicidialLog->useDbConfig = 'db6';
                }
                $dt=$this->vicidialLog->query($qry);
                 $adt=array();
            foreach ($dt as $key => $value) {
            	$qry = "SELECT * FROM aband_call_master acm where PhoneNo='".$value[0]['PhoneNumber']."' and date(Callbackdate)=date('".$value[0]['CallDate']."') limit 1";
            	
            	$record = $this->EcrRecord->query($qry);
            	$value[0]['client_name'] = $record[0]['acm']['CompanyName'];
 
            	$adt[] = $value;
            }

            $this->set('Data',$adt); 
            
            
        }
    }

    public function cdr_mis_share_external(){
        if($this->request->is("POST")){
            $campaignId =   "t2.campaign_id in(". $this->Session->read('campaignid').")";
            $clientId   =   $this->Session->read('companyid');
            $search     =   $this->request->data['ObCdrReports'];
            $Campagn    =   $this->Session->read('campaignid');
            // $FromDate   =   $search['startdate'];
            // $ToDate     =   $search['enddate'];

            $FromDate = date("Y-m-d", strtotime($search['startdate']));  
            $ToDate = date("Y-m-d", strtotime($search['enddate']));
            //$clientId	=  $search['clientID'];
            $clientId = $this->Session->read('companyid');

            if($clientId=='All'){
            	$this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A'"); 
            	//print_r($ClientInfo[0][0]); 
            	$campaignId = $ClientInfo[0][0]['campaign_id']; 
    			$campaignId =   "t2.campaign_id in(".$campaignId.")";

                $DialerConnectionPage = $ClientInfo[0][0]['DialerConnectionPage'];
			    $GroupId = $ClientInfo[0][0]['GroupId']; 
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

    		$campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
    		$campaignId =   "t2.campaign_id in(".$campaignId.")";

            $DialerConnectionPage = $ClientInfo['RegistrationMaster']['DialerConnectionPage'];
			$GroupId = $ClientInfo['RegistrationMaster']['GroupId']; 
            }

			
            // $dataArr    =   $this->CallRecord->query("SELECT * FROM registration_master WHERE company_id='$clientId' limit 1");
            // //print_r($dataArr);die;
            // $DialerConnectionPage   =   $dataArr['0']['registration_master']['DialerConnectionPage'];
            // $GroupId                =   $dataArr['0']['registration_master']['GroupId']; 
            
            if($GroupId !=""){
                $campaignId ="t2.campaign_id in(".$GroupId.")";

                if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
                if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }

                $qry="SELECT DATE(t2.call_date) AS CallDate,FROM_UNIXTIME(t2.start_epoch) AS StartTime,FROM_UNIXTIME(t2.end_epoch) AS Endtime,LEFT(t2.phone_number,10) AS PhoneNumber,
t2.`user` AS Agent,vu.full_name as full_name,if(t2.`user`='VDAD','Not Connected','Connected') calltype,t2.status as status,if(t2.`list_id`='998','Mannual','Auto') dialmode,t2.campaign_id as campaign_id,t2.lead_id as lead_id,
 t2.length_in_sec AS LengthInSec,
            SEC_TO_TIME(t2.length_in_sec) AS LengthInMin,
            t2.length_in_sec AS CallDuration,
            t2.`status` AS CallStatus,
            t3.`pause_sec` AS PauseSec,
            t3.`wait_sec` AS WaitSec,
            t3.`talk_sec` AS TalkSec,t3.dispo_sec AS DispoSec
  FROM asterisk.vicidial_log t2
            LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid left join vicidial_users vu on t2.user=vu.user
            WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate' AND t2.campaign_id='dialdesk'
            AND t2.lead_id IS NOT NULL"; 

                if($clientId!='375')
                {
		$this->vicidialLog->useDbConfig = 'db2';
                }
                else
                {
                    $this->vicidialLog->useDbConfig = 'db6';
                }
                $dt=$this->vicidialLog->query($qry);
                 $adt=array();
            foreach ($dt as $key => $value) {
            	$qry = "SELECT * FROM aband_call_master acm where PhoneNo='".$value[0]['PhoneNumber']."' and date(Callbackdate)=date('".$value[0]['CallDate']."') and ClientId='$clientId' limit 1";
            	
            	$record = $this->EcrRecord->query($qry);
                if(!empty($record))
                {
                    $value[0]['client_name'] = $record[0]['acm']['CompanyName'];
 
            	   $adt[] = $value;
                }
            	
            }

            $this->set('Data',$adt); 
            
            }
            else{
                $this->Session->setFlash('<span style="color:red;" >Please contact admin for adding group id.</span>');  
            }
        }
    }

}
?>