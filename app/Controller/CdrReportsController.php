<?php
class CdrReportsController extends AppController
{
   //ini_set('max_execution_time', '0');
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses = array('EcrRecord','CallRecord','vicidialCloserLog','vicidialUserLog','CroneJob','AbandCallMaster','RegistrationMaster');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
        //echo $this->Session->read("companyid");exit;
        if(!$this->Session->check("companyid") && !$this->Session->check("admin_id"))
        {
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
        
    }
    
    public function index() 
    {
         $this->layout='user';
        if($this->request->is("POST")){
        $campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').",'DIALDESK')";
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

                if($clientId!='375')
                {
		                $this->vicidialCloserLog->useDbConfig = 'db2';
                }
                else
                {
                        $this->vicidialCloserLog->useDbConfig = 'db6';
                }
		                $dt=$this->vicidialCloserLog->query($qry);
                
                //echo "<pre>";
                //print_r($dt);die;
                
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
		//$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
                
                
                //$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']/($total-$dt[0][0]['AbndWithinThresold'])*100);
                
                $data['SL% (20 Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$data['Total Calls Landed'][$dateLabel][$timeLabel])."%";
                
				$data['AL%'][$dateLabel][$timeLabel]=round($dt[0][0]['Answered']/$data['Total Calls Landed'][$dateLabel][$timeLabel]*100)."%";
                
				
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
    
    
///////////////////////   Admin Reports////////////////////////////////

    public function sla_report_month() 
    {
         $this->layout='user';
         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
        if($this->request->is("POST")){
        
			$search     =$this->request->data['CdrReports'];

			$clientId = $search['clientID'];
			$FromDate = $search['startdate'];
			$ToDate = $search['enddate'];
            $aband_where_tag = "";
			if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1'"); 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
                $aband_where_tag = "";
            }else {

                $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

                $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
                $aband_where_tag .= " and ClientId='$clientId'";
            }
           
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
                WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end' AND $campaignId"; 

                
                $aband_data_arr = $this->AbandCallMaster->query("SELECT * FROM `aband_call_master` WHERE calldate>='$start_time_start' AND calldate<'$start_time_end' $aband_where_tag"); 
                #print_r($aband_data_arr);die;

                if($clientId!='375')
                {
		            $this->vicidialCloserLog->useDbConfig = 'db2';
                }
                else
                {
                    $this->vicidialCloserLog->useDbConfig = 'db6';
                }
		        $dt=$this->vicidialCloserLog->query($qry);
                
                //echo "<pre>";
                //print_r($dt);die;
                
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

                // $data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
                // $data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']/($total-$dt[0][0]['AbndWithinThresold'])*100);
                // $data['Agent Logged In'][$dateLabel][$timeLabel]=$usrDt['UserLoggedIn'];
                
                $data['SL% (20 Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$data['Total Calls Landed'][$dateLabel][$timeLabel])."%";
				$data['AL%'][$dateLabel][$timeLabel]=round($dt[0][0]['Answered']/$data['Total Calls Landed'][$dateLabel][$timeLabel]*100)."%";
                $TotalCall+=$total;
	        }
	
         

            foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
                $data['Offered %'][$dateLabel][$timeLabel]=round($data['Total Calls Landed'][$dateLabel][$timeLabel]*100/$TotalCall);  
            } }
            $this->set('data',$data);
            $this->set('datetimeArray',$datetimeArray);
        }
    }

    public function sla_report_month_excel() 
    {
         $this->layout='user';
         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
        if($this->request->is("POST")){
        
			$search     =$this->request->data['CdrReports'];

			$clientId = $search['clientID'];
			$FromDate = $search['startdate'];
			$ToDate = $search['enddate'];
			if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1'"); 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
           
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

                if($clientId!='375')
                {
		                $this->vicidialCloserLog->useDbConfig = 'db2';
                }
                else
                {
                        $this->vicidialCloserLog->useDbConfig = 'db6';
                }
		                $dt=$this->vicidialCloserLog->query($qry);
                
                //echo "<pre>";
                //print_r($dt);die;
                
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
		//$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
                
                
                //$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']/($total-$dt[0][0]['AbndWithinThresold'])*100);
                
                $data['SL% (20 Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$data['Total Calls Landed'][$dateLabel][$timeLabel])."%";
                
				$data['AL%'][$dateLabel][$timeLabel]=round($dt[0][0]['Answered']/$data['Total Calls Landed'][$dateLabel][$timeLabel]*100)."%";
                
				
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


    
    public function report()
    {
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
          }
       if($this->request->is("POST")){
       	$this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
        $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id  FROM `registration_master` WHERE is_dd_client='1' and `status`='A'"); 
            	//print_r($ClientInfo[0][0]); 
       $campaignId = $ClientInfo[0][0]['campaign_id']; 
       $campaignId =   "t2.campaign_id in(".$campaignId.")";
     //  $campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').")";
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
       
       $NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 hours"));
       $FromDate=$NextDate;
       
       $start_time_end=$NextDate;

               
$qry="
SELECT COUNT(*) `Total`,
SUM(IF(t2.user!='VDCL',1,0)) `Answered`,
COUNT(DISTINCT(IF(t2.user!='VDCL',1,0))) `Manpower`,										
SUM(t1.talk_sec) `Talk`,
SUM(t1.wait_sec) `wait`,
SUM(t1.dispo_sec) `dispo`,
SUM(t1.pause_sec) `pause`,
SUM(IFNULL(t3.p,0)) `hold`,
SUM(IF(t2.user!='VDCL',1,0))/ COUNT(*)*100 `Al`,
SUM(t1.talk_sec) + SUM(t1.wait_sec) + SUM(t1.dispo_sec) + SUM(t1.pause_sec) + SUM(IFNULL(t3.p,0)) `Total login`,
SUM(t1.talk_sec) + SUM(t1.wait_sec) + SUM(t1.dispo_sec) + SUM(IFNULL(t3.p,0)) `Net login`,
(SUM(t1.talk_sec) + SUM(t1.dispo_sec) + SUM(IFNULL(t3.p,0)))/ (SUM(t1.talk_sec) + SUM(t1.wait_sec) + SUM(t1.dispo_sec) + SUM(IFNULL(t3.p,0)))*100 `Utilization`
FROM asterisk.vicidial_closer_log t2 
LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid  AND t1.lead_id!='' AND t2.user=t1.user
LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end'
           ";

       $this->vicidialCloserLog->useDbConfig = 'db2';
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
		//$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
                
                
                //$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']/($total-$dt[0][0]['AbndWithinThresold'])*100);
                
                $data['SL% (20 Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$data['Total Calls Landed'][$dateLabel][$timeLabel])."%";
                
				$data['AL%'][$dateLabel][$timeLabel]=round($dt[0][0]['Answered']/$data['Total Calls Landed'][$dateLabel][$timeLabel]*100)."%";
                
				
		//$data['Agent Logged In'][$dateLabel][$timeLabel]=$usrDt['UserLoggedIn'];
		
		$TotalCall+=$total;
   }
    
   foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
       $data[$dateLabel][$timeLabel]['Offered %']=round($data[$dateLabel][$timeLabel]['Total Calls Landed']*100/$TotalCall[$dateLabel]);  
   } }
       
       
       $this->set('data',$data);
        $this->set('datetimeArray',$datetimeArray);
       }
   }

   public function reportprint()
   {
        if($this->request->is("POST")){
        
        $search     =$this->request->data['CdrReports'];
       $date_array = array();
       $timeArray = array();
           
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];
            $clientId = $search['clientID'];

            if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1'"); 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
            if($this->Session->read('role') !="admin")
            {
                $campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').")";
                $clientId   = $this->Session->read('companyid');
            }
           
        if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
	if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
	//echo $ToDate;die;
	while(strtotime($FromDate)<strtotime($ToDate))
	{
        $date_array[] =$date_fetch= date("Y-m-d",strtotime("$FromDate"));

		$start_time_start=$FromDate;
		$event_date_start=date("Y-m-d 00:00:00",strtotime("$FromDate +1 hours"));
		$timeArray[] =  $time_fetch=date("H",strtotime("$FromDate"));
		$NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 hours"));
		$FromDate=$NextDate;
		
		$start_time_end=$NextDate;
	
        $qry="
        SELECT COUNT(*) `Total`,
        SUM(IF(t2.user!='VDCL',1,0)) `Answered`,
        IF(t2.user!='VDCL',count(DISTINCT(t2.user))-1,count(DISTINCT(t2.user))) `Manpower`,										
        SUM(t1.talk_sec) `Talk`,
        SUM(t1.wait_sec) `wait`,
        SUM(t1.dispo_sec) `dispo`,
        SUM(t1.pause_sec) `pause`,
        SUM(IFNULL(t3.p,0)) `hold`,
        SUM(IF(t2.user!='VDCL',1,0))/ COUNT(*)*100 `Al`,
        SUM(t1.talk_sec) + SUM(t1.wait_sec) + SUM(t1.dispo_sec) + SUM(t1.pause_sec) + SUM(IFNULL(t3.p,0)) `Total login`,
        SUM(t1.talk_sec) + SUM(t1.wait_sec) + SUM(t1.dispo_sec) + SUM(IFNULL(t3.p,0)) `Net login`,
        (SUM(t1.talk_sec) + SUM(t1.dispo_sec) + SUM(IFNULL(t3.p,0)))/ (SUM(t1.talk_sec) + SUM(t1.wait_sec) + SUM(t1.dispo_sec) + SUM(IFNULL(t3.p,0)))*100 `Utilization`
        FROM asterisk.vicidial_closer_log t2 
        LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid  AND t1.lead_id!='' AND t2.user=t1.user
        LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
        WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end' AND $campaignId
        ";
  
  
		$this->vicidialCloserLog->useDbConfig = 'db2';
		$dt=$this->vicidialCloserLog->query($qry);
                //print_r($dt);die; 
		
        $timeLabel=$time_fetch;
        $dateLabel=$date_fetch;
        $datetimeArray[$dateLabel][]=$timeLabel;
		
		$data[$dateLabel][$timeLabel]['Total'] = $dt[0][0]['Total'];
		$data[$dateLabel][$timeLabel]['Answered'] = $dt[0][0]['Answered'];
        $data[$dateLabel][$timeLabel]['Manpower'] = $dt[0][0]['Manpower'];
		$data[$dateLabel][$timeLabel]['Talk'] = $dt[0][0]['Talk'];
        $data[$dateLabel][$timeLabel]['wait'] = $dt[0][0]['wait'];
		$data[$dateLabel][$timeLabel]['dispo'] = $dt[0][0]['dispo'];
        $data[$dateLabel][$timeLabel]['pause'] = $dt[0][0]['pause'];
        $data[$dateLabel][$timeLabel]['hold'] = $dt[0][0]['hold'];
        $data[$dateLabel][$timeLabel]['Al %'] = $dt[0][0]['Al'];
        $data[$dateLabel][$timeLabel]['Total login'] = $dt[0][0]['Total login'];
        $data[$dateLabel][$timeLabel]['Net login'] = $dt[0][0]['Net login'];
        $data[$dateLabel][$timeLabel]['Utilization %'] = $dt[0][0]['Utilization'];
       
	}
	// print_r($data);exit;
        $date_array = array_unique($date_array);
        $timeArray=array_unique($timeArray);

        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
         $this->set('datearray', $date_array);
         $this->set('timearray', $timeArray);
        }
   
  }
  
      
    public function cdr() 
    {
         $this->layout='user';
         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
          }
        if($this->request->is("POST")){
           ini_set('max_execution_time', '0');
        
        
        $search     =$this->request->data['CdrReports'];
        $clientId = $search['clientID'];
       
            $Campagn  = $this->Session->read('campaignid');
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];

            if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1'"); 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
            if($this->Session->read('role') !="admin")
            {
                $campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').")";
                $clientId   = $this->Session->read('companyid');
            }
            

           
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

		$this->vicidialCloserLog->useDbConfig = 'db2';
		$dt=$this->vicidialCloserLog->query($qry);
               // print_r($dt);die;
		// Usewr Loggedd In
		
		
		$timeLabel=date("H:i",strtotime($start_time_start))." To ".date("H:i",strtotime($start_time_end));
		$dateLabel=date("d-F-Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		
		$data[$dateLabel][$timeLabel]['Offered %']='';
		$total=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
		$data[$dateLabel][$timeLabel]['Total Calls Landed']=$total;
		$data[$dateLabel][$timeLabel]['Total Calls Answered']=$dt[0][0]['Answered'];
		$data[$dateLabel][$timeLabel]['Total Calls Abandoned']=$dt[0][0]['Abandon'];
		$data[$dateLabel][$timeLabel]['AHT (In Sec)']=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
		$data[$dateLabel][$timeLabel]['Calls Ans (within 20 Sec)']=$dt[0][0]['WIthinSLA'];
		$data[$dateLabel][$timeLabel]['Abnd Within Threshold']=$dt[0][0]['AbndWithinThresold'];
		$data[$dateLabel][$timeLabel]['Abnd After Threshold']=$dt[0][0]['AbndAfterThresold'];
		$data[$dateLabel][$timeLabel]['Abandoned (%)']=round($dt[0][0]['Abandon']*100/$total);
		//$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
                
                //$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']/($total-$dt[0][0]['AbndWithinThresold'])*100);
                
                $data[$dateLabel][$timeLabel]['SL% (20 Sec)']=round($dt[0][0]['WIthinSLA']*100/$data[$dateLabel][$timeLabel]['Total Calls Landed'])."%";
                
				$data[$dateLabel][$timeLabel]['AL%']=round($dt[0][0]['Answered']/$data[$dateLabel][$timeLabel]['Total Calls Landed']*100)."%";
        
		//$data['Agent Logged In'][$dateLabel][$timeLabel]=$usrDt['UserLoggedIn'];
		
		
		$TotalCall[$dateLabel]+=$total;
	}
	
         

	foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
		$data[$dateLabel][$timeLabel]['Offered %']=round($data[$dateLabel][$timeLabel]['Total Calls Landed']*100/$TotalCall[$dateLabel]);  
	} }
        
        
        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
        }
    }
    
    
  public  function call_mis()
        {
        if($this->request->is("POST")){
           ini_set('max_execution_time', '0');
        $campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').")";
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
  
  
		$this->vicidialCloserLog->useDbConfig = 'db2';
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
		//$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
                
                //$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']/($total-$dt[0][0]['AbndWithinThresold'])*100);
                
                $data['SL% (20 Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$data['Total Calls Landed'][$dateLabel][$timeLabel])."%";
                $data['AL%'][$dateLabel][$timeLabel]=round($dt[0][0]['Answered']/$data['Total Calls Landed'][$dateLabel][$timeLabel]*100)."%";
                
                
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
     
        
        $search     =$this->request->data['CdrReports'];
       
           
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];
            $clientId = $search['clientID'];

            if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1'"); 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }

            if($this->Session->read('role') !="admin")
            {
                $campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').")";
                $clientId   = $this->Session->read('companyid');
            }
           
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


		$this->vicidialCloserLog->useDbConfig = 'db2';
		$dt=$this->vicidialCloserLog->query($qry);
               // print_r($dt);die;
		// Usewr Loggedd In
		
		
		$timeLabel=date("H:i",strtotime($start_time_start))." To ".date("H:i",strtotime($start_time_end));
		$dateLabel=date("d-F-Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		
		$data[$dateLabel][$timeLabel]['Offered %']='';
		$total=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
		$data[$dateLabel][$timeLabel]['Total Calls Landed']=$total;
		$data[$dateLabel][$timeLabel]['Total Calls Answered']=$dt[0][0]['Answered'];
		$data[$dateLabel][$timeLabel]['Total Calls Abandoned']=$dt[0][0]['Abandon'];
		$data[$dateLabel][$timeLabel]['AHT (In Sec)']=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
		$data[$dateLabel][$timeLabel]['Calls Ans (within 20 Sec)']=$dt[0][0]['WIthinSLA'];
		$data[$dateLabel][$timeLabel]['Abnd Within Threshold']=$dt[0][0]['AbndWithinThresold'];
		$data[$dateLabel][$timeLabel]['Abnd After Threshold']=$dt[0][0]['AbndAfterThresold'];
		$data[$dateLabel][$timeLabel]['Abandoned (%)']=round($dt[0][0]['Abandon']*100/$total);
		//$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
                
                //$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']/($total-$dt[0][0]['AbndWithinThresold'])*100);
                
                $data[$dateLabel][$timeLabel]['SL% (20 Sec)']=round($dt[0][0]['WIthinSLA']*100/$data[$dateLabel][$timeLabel]['Total Calls Landed'])."%";
                $data[$dateLabel][$timeLabel]['AL%']=round($dt[0][0]['Answered']/$data[$dateLabel][$timeLabel]['Total Calls Landed']*100)."%";
        
		//$data['Agent Logged In'][$dateLabel][$timeLabel]=$usrDt['UserLoggedIn'];
		
		$TotalCall[$dateLabel]+=$total;
	}
	
         

	foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
		$data[$dateLabel][$timeLabel]['Offered %']=round($data[$dateLabel][$timeLabel]['Total Calls Landed']*100/$TotalCall[$dateLabel]);  
	} }
        
        
        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
        }
        
       // $this->export_excel($data,$datetimeArray); 
        }
        
        
    public function detailscdr(){
        $this->layout='user';
        
        if($this->request->is("POST")){
            
            ini_set('max_execution_time', '0');
            $campaignId =   "t2.campaign_id in(". $this->Session->read('campaignid').")"; 
            $clientId   =   $this->Session->read('companyid');
            $search     =   $this->request->data['CdrReports'];

            $FromDate   =   date("Y-m-d",strtotime($search['startdate']));
            $ToDate     =   date("Y-m-d",strtotime($search['enddate']));
           
           
        $qry="SELECT 
            SEC_TO_TIME(t6.`p`) ParkedTime,
            t2.user Agent,t2.lead_id as LeadId,
            RIGHT(phone_number,10) PhoneNumber,
            DATE(call_date) CallDate ,
            SEC_TO_TIME(queue_seconds) Queuetime,
            IF(queue_seconds='0',FROM_UNIXTIME(t2.start_epoch),FROM_UNIXTIME(t2.start_epoch-queue_seconds)) QueueStart,
            FROM_UNIXTIME(t2.start_epoch) StartTime,
            FROM_UNIXTIME(t2.end_epoch) Endtime,
            SEC_TO_TIME(if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`)) CallDuration,
            if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`) AS CallDuration1,
            FROM_UNIXTIME(t2.end_epoch+TIME_TO_SEC(IF(t3.dispo_sec IS NULL,SEC_TO_TIME(0),IF(t3.sub_status='LOGIN'  OR t3.sub_status='Feed' OR t3.talk_sec=t3.dispo_sec OR t3.talk_sec=0,SEC_TO_TIME(1),IF(t3.dispo_sec>100,SEC_TO_TIME(t3.dispo_sec-(t3.dispo_sec/100)*100),SEC_TO_TIME(t3.dispo_sec)))))) WrapEndTime,
            IF(t3.dispo_sec IS NULL,SEC_TO_TIME(0),IF(t3.sub_status='LOGIN'  OR t3.sub_status='Feed' OR t3.talk_sec=t3.dispo_sec OR t3.talk_sec=0,SEC_TO_TIME(1),IF(t3.dispo_sec>100,SEC_TO_TIME(t3.dispo_sec-(t3.dispo_sec/100)*100),SEC_TO_TIME(t3.dispo_sec)))) WrapTime
            
            FROM asterisk.vicidial_closer_log t2 
LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid and t2.user=t3.user
LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND DATE(parked_time) BETWEEN '$FromDate' AND '$ToDate' GROUP BY uniqueid) t6 ON t2.uniqueid=t6.uniqueid  
WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate' 
AND DATE(t2.call_date) BETWEEN DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND CURDATE() and $campaignId 
AND  t2.lead_id IS NOT NULL
            "; 
           
           
        
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt=$this->vicidialCloserLog->query($qry);
            
            //echo "<pre>";
            //print_r($dt);die;
            
            
            $this->set('Data',$dt);
        }
    }
        
    public function cdr_mis(){
        if($this->request->is("POST")){
           ini_set('max_execution_time', '0');
            $campaignId =   "t2.campaign_id in(". $this->Session->read('campaignid').")";
            $clientId   =   $this->Session->read('companyid');
            $search     =   $this->request->data['CdrReports'];
            $FromDate   =   date("Y-m-d",strtotime($search['startdate']));
            $ToDate     =   date("Y-m-d",strtotime($search['enddate']));
            
           
              
         $qry="SELECT 
            SEC_TO_TIME(t6.`p`) ParkedTime,
            t2.user Agent,t2.lead_id as LeadId,
            RIGHT(phone_number,10) PhoneNumber,
            DATE(call_date) CallDate ,
            SEC_TO_TIME(queue_seconds) Queuetime,
            IF(queue_seconds='0',FROM_UNIXTIME(t2.start_epoch),FROM_UNIXTIME(t2.start_epoch-queue_seconds)) QueueStart,
            FROM_UNIXTIME(t2.start_epoch) StartTime,
            FROM_UNIXTIME(t2.end_epoch) Endtime,
            SEC_TO_TIME(if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`)) CallDuration,
            if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`) AS CallDuration1,
            FROM_UNIXTIME(t2.end_epoch+TIME_TO_SEC(IF(t3.dispo_sec IS NULL,SEC_TO_TIME(0),IF(t3.sub_status='LOGIN'  OR t3.sub_status='Feed' OR t3.talk_sec=t3.dispo_sec OR t3.talk_sec=0,SEC_TO_TIME(1),IF(t3.dispo_sec>100,SEC_TO_TIME(t3.dispo_sec-(t3.dispo_sec/100)*100),SEC_TO_TIME(t3.dispo_sec)))))) WrapEndTime,
            IF(t3.dispo_sec IS NULL,SEC_TO_TIME(0),IF(t3.sub_status='LOGIN'  OR t3.sub_status='Feed' OR t3.talk_sec=t3.dispo_sec OR t3.talk_sec=0,SEC_TO_TIME(1),IF(t3.dispo_sec>100,SEC_TO_TIME(t3.dispo_sec-(t3.dispo_sec/100)*100),SEC_TO_TIME(t3.dispo_sec)))) WrapTime
            FROM asterisk.vicidial_closer_log t2 
LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid  and t2.user=t3.user
LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND DATE(parked_time) BETWEEN '$FromDate' AND '$ToDate' GROUP BY uniqueid) t6 ON t2.uniqueid=t6.uniqueid  
WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate' 
AND $campaignId 
AND  t2.lead_id IS NOT NULL
            ";
         
         
           
        
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt=$this->vicidialCloserLog->query($qry);
            
            
            
            
            
            $this->set('Data',$dt);
        }
    }



////////////////////////  Krishna //////////

        public function cdrdataview(){
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
                $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
                $client = array('All'=>'All')+$client;
                $this->set('client',$client); 
            }
        if($this->request->is("POST")){
            ini_set('max_execution_time', '0');
           
            //$campaignId =   "t2.campaign_id in(". $this->Session->read('campaignid').")"; 
            //$clientId   =   $this->Session->read('companyid');
            $search     =   $this->request->data['CdrReports'];

            $FromDate   =   date("Y-m-d",strtotime($search['startdate']));
            $ToDate     =   date("Y-m-d",strtotime($search['enddate']));
            $clientId	=  $search['clientID'];
            $category   =   $search['category'];
            if($this->Session->read('role') !="admin"){
                $clientId   =   $this->Session->read('companyid');
            }
            
            $category_qry = "";
            if(!empty($category) && $category!='All')
            {
                $category_qry = "and client_category='$category'";
            }
            
            if($clientId=='All'){
            	$this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
                
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1' $category_qry"); 
            	//print_r($ClientInfo[0][0]); 
            	$campaignId = $ClientInfo[0][0]['campaign_id']; 
    			$campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId' $category_qry"));
            
            #print_r($ClientInfo);die;
                if(!empty($ClientInfo))
                {
                    $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
                    $campaignId =   "t2.campaign_id in(".$campaignId.")";

                }else{

                    return $this->redirect(['controller'=>'CdrReports','action' => 'cdrdataview']);

                }

    		
            }

           
        $qry="SELECT SEC_TO_TIME(t6.`p`) ParkedTime,t2.campaign_id, 
            IF(queue_seconds<='20',1,0)Call20,IF(queue_seconds<='60',1,0)Call60,IF(queue_seconds<='90',1,0)Call90,
            t2.user Agent,vc.full_name,t2.lead_id as LeadId,RIGHT(phone_number,10) PhoneNumber, 
            DATE(call_date) CallDate,SEC_TO_TIME(queue_seconds) Queuetime, 
            IF(queue_seconds='0',FROM_UNIXTIME(t2.start_epoch),FROM_UNIXTIME(t2.start_epoch-queue_seconds)) AS QueueStart, 
            FROM_UNIXTIME(t2.start_epoch) StartTime,FROM_UNIXTIME(t2.end_epoch) Endtime,
            SEC_TO_TIME(if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`)) CallDuration,
            if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`) AS CallDuration1,
            FROM_UNIXTIME(t2.end_epoch+TIME_TO_SEC(IF(t3.dispo_sec IS NULL,SEC_TO_TIME(0),IF(t3.sub_status='LOGIN'  OR t3.sub_status='Feed' OR t3.talk_sec=t3.dispo_sec OR t3.talk_sec=0,SEC_TO_TIME(1),IF(t3.dispo_sec>100,SEC_TO_TIME(t3.dispo_sec-(t3.dispo_sec/100)*100),SEC_TO_TIME(t3.dispo_sec)))))) WrapEndTime,
            IF(t3.dispo_sec IS NULL,SEC_TO_TIME(0),IF(t3.sub_status='LOGIN'  OR t3.sub_status='Feed' OR t3.talk_sec=t3.dispo_sec OR t3.talk_sec=0,SEC_TO_TIME(1),IF(t3.dispo_sec>100,SEC_TO_TIME(t3.dispo_sec-(t3.dispo_sec/100)*100),SEC_TO_TIME(t3.dispo_sec)))) WrapTime,
            sub_status,t2.status,t2.term_reason,t2.xfercallid FROM asterisk.vicidial_closer_log t2 
            LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid  and t2.user=t3.user  
            LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND DATE(parked_time) BETWEEN '$FromDate' AND '$ToDate' GROUP BY uniqueid) t6 ON t2.uniqueid=t6.uniqueid left join vicidial_users vc on t2.user=vc.user 
            WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate' AND $campaignId AND  t2.lead_id IS NOT NULL GROUP BY t2.uniqueid";
           
            #echo $qry;exit;         
        
            if($clientId!='375')
            {
                $this->vicidialCloserLog->useDbConfig = 'db2';
            }
            else
            {
                $this->vicidialCloserLog->useDbConfig = 'db6';
            }
            $dt=$this->vicidialCloserLog->query($qry);
            
            //echo "<pre>";
            //print_r($dt);die;
            
            
            $this->set('Data',$dt);
            $this->set('companyid',$clientId);

        }
    }
        
    public function cdrdataexcel(){
        if($this->request->is("POST")){
          ini_set('max_execution_time', '0');
            //$campaignId =   "t2.campaign_id in(". $this->Session->read('campaignid').")"; 
            //$clientId   =   $this->Session->read('companyid');
            $search     =   $this->request->data['CdrReports'];

            $FromDate   =   date("Y-m-d",strtotime($search['startdate']));
            $ToDate     =   date("Y-m-d",strtotime($search['enddate']));
            $clientId	=  $search['clientID'];
            $category   =   $search['category'];

            if($this->Session->read('role') != "admin")
            {
                $clientId = $this->Session->read("companyid");
            }
            
            
            // echo $category; die; 
            $category_qry = "";
            if(!empty($category) && $category!='All')
            {
                $category_qry = "and client_category='$category'";
            }
            #echo $category_qry;die;
            
            if($clientId=='All'){
            	$this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1' $category_qry");
               //$ClientInfo = $this->RegistrationMaster->query("SELECT campaignid as campaign_id FROM `registration_master` WHERE `status`='A'"); 
            	//print_r($ClientInfo);
            	//foreach($ClientInfo as $record) { 
            	//	echo $record['0']['campaign_id'];
            	//}
            	//print_r($df); exit;
            	$campaignId = $ClientInfo[0][0]['campaign_id']; 
    			$campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {
                
             $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId' $category_qry"));


                if(!empty($ClientInfo))
                {
                    $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
                    $campaignId =   "t2.campaign_id in(".$campaignId.")";

                }else{

                    $this->Session->setFlash('<span style="color:green;">Category Not Match</span>');
                    return $this->redirect(['controller'=>'CdrReports','action' => 'cdrdataview']);

                }

    	
            }
            
           
              
        $qry="SELECT 
SEC_TO_TIME(t6.`p`) ParkedTime,
t2.campaign_id campaign_id, 
IF(queue_seconds<='20',1,0)Call20,
IF(queue_seconds<='60',1,0)Call60,
IF(queue_seconds<='90',1,0)Call90,
t2.user Agent,vc.full_name full_name,t2.lead_id as LeadId,
RIGHT(phone_number,10) PhoneNumber, 
DATE(call_date) CallDate,
SEC_TO_TIME(queue_seconds) Queuetime,
IF(queue_seconds='0',FROM_UNIXTIME(t2.start_epoch),FROM_UNIXTIME(t2.start_epoch-queue_seconds)) AS QueueStart, 
FROM_UNIXTIME(t2.start_epoch) StartTime,
FROM_UNIXTIME(t2.end_epoch) Endtime,
SEC_TO_TIME(if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`)) CallDuration,
if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`) AS CallDuration1,
FROM_UNIXTIME(t2.end_epoch+TIME_TO_SEC(IF(t3.dispo_sec IS NULL,SEC_TO_TIME(0),IF(t3.sub_status='LOGIN'  OR t3.sub_status='Feed' OR t3.talk_sec=t3.dispo_sec OR t3.talk_sec=0,SEC_TO_TIME(1),IF(t3.dispo_sec>100,SEC_TO_TIME(t3.dispo_sec-(t3.dispo_sec/100)*100),SEC_TO_TIME(t3.dispo_sec)))))) WrapEndTime,
IF(t3.dispo_sec IS NULL,SEC_TO_TIME(0),IF(t3.sub_status='LOGIN'  OR t3.sub_status='Feed' OR t3.talk_sec=t3.dispo_sec OR t3.talk_sec=0,SEC_TO_TIME(1),IF(t3.dispo_sec>100,SEC_TO_TIME(t3.dispo_sec-(t3.dispo_sec/100)*100),SEC_TO_TIME(t3.dispo_sec)))) WrapTime,
sub_status,
t2.status status,
t2.term_reason term_reason,t2.xfercallid 
FROM asterisk.vicidial_closer_log t2 
LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid and t2.user=t3.user
LEFT JOIN 
(SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND DATE(parked_time) BETWEEN '$FromDate' AND '$ToDate' GROUP BY uniqueid) t6 ON t2.uniqueid=t6.uniqueid left join vicidial_users vc on t2.user=vc.user 
WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate' 
AND $campaignId 
AND  t2.lead_id IS NOT NULL GROUP BY t2.uniqueid
            ";
         
           
        
            if($clientId!='375')
                {
		$this->vicidialCloserLog->useDbConfig = 'db2';
                }
                else
                {
                    $this->vicidialCloserLog->useDbConfig = 'db6';
                }
            $dt=$this->vicidialCloserLog->query($qry);
            
           // print_r($dt); 
            
            
            
            $this->set('Data',$dt);
        }
    }


public function dd_overall() 
    {
         $this->layout='user';

         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
          }

        if($this->request->is("POST")){
        	
        
         $search     =$this->request->data['CdrReports'];
       
            $firstDay=$search['startdate'];
            $lastDay=$search['enddate'];
             $clientId = $search['clientID'];

            if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1'"); 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId1 = $ClientInfo[0][0]['campaign_id'];
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId1=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
            if($this->Session->read('role') !="admin")
            {
                $clientId   = $this->Session->read('companyid');
                $campaignId1=$this->Session->read('campaignid');
                $campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').")";
                
            }

        
        
        
    $FromDate='00:00:00';
	$ToDate='23:59:59';
	 $i=0;
 if(!empty($firstDay)){
while($i <= 23)
	{
		$start_time_start=$FromDate;
		$event_date_start=$ToDate;
		
		$start_time_end=date("H:59:59",strtotime("$FromDate"));

		$NextDate=date("H:i:s",strtotime("$FromDate +1 hour"));
		$FromDate=$NextDate;
		
 $qry = "SELECT COUNT(*) `Total`,
SEC_TO_TIME(LEFT(SUM(talk_sec)+SUM(pause_sec)+SUM(wait_sec)+SUM(dispo_sec),6)) AS TalkTime,
SEC_TO_TIME(LEFT(SUM(dispo_sec),6))AS `dispotime`,SEC_TO_TIME(LEFT(SUM(dispo_sec),5)) `WrapTime`, 
SUM(if(t2.`user` !='VDCL',1,0)) `Answered`, SUM(if(t2.`user` ='VDCL',1,0)) `Abandon`, 
SUM(IF(t2.`user` !='VDCL',t1.length_in_sec,0)) `TotalAcht`,
SUM(IF(t2.`user` !='VDCL' AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
SUM(IF(t2.`user` ='VDCL' AND t2.queue_seconds<=20,1,0)) `AbndWithinThresold`,
SUM(IF(t2.`user` ='VDCL' AND t2.queue_seconds>20,1,0)) `AbndAfterThresold`,
COUNT(DISTINCT(IF(t2.user!='VDCL',1,0))) `Agentlogin`,
SUM(IF(t2.`user` ='VDCL' AND t2.queue_seconds,1,0)) `avgabandtime` FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid  WHERE  time(t2.call_date)>='$start_time_start' AND time(t2.call_date)<='$start_time_end' and date(t2.call_date)>='$firstDay' AND date(t2.call_date)<='$lastDay'
and $campaignId and t2.term_reason!='AFTERHOURS' and t2.lead_id is not null";
		
$this->vicidialCloserLog->useDbConfig = 'db2';
$dt=$this->vicidialCloserLog->query($qry);
//print_r($dt);  die;
  $firstDayag = $firstDay;
  $lastDayag = $lastDay;

     $FromDateag=$firstDay.' '.$start_time_end;
	 $ToDateag=$lastDay.' '. $start_time_end;
     $userCnt=0;
	while(strtotime($FromDateag)<=strtotime($ToDateag))
	{
		$start_time_startag=$FromDateag;
		$event_date_startag=$ToDateag;
		
		$start_time_endag=date("Y-m-d H:i:s",strtotime("$FromDateag"));
        $FromDate3=date("Y-m-d 00:00:00",strtotime("$start_time_startag"));
		$NextDateag=date("Y-m-d H:i:s",strtotime("$FromDateag +1 day"));
                        
		$FromDateag=$NextDateag;
        $usrQry="SELECT (sum(if((t1.event='LOGIN' || event_date>='$FromDateag'),1,0))- SUM(IF((t1.event='LOGOUT' &&
        event_date>='$FromDateag'),1,0))) 'UserLoggedIn'
        FROM asterisk.vicidial_user_log t1 JOIN (SELECT MAX(user_log_id) `user_log_id` FROM 
        asterisk.vicidial_user_log WHERE campaign_id in ({$campaignId1}) and  event_date>='$FromDate3' 
        AND event_date<'$start_time_endag' GROUP BY USER) AS t2 WHERE t1.user_log_id=t2.user_log_id";
                 
		$this->vicidialCloserLog->useDbConfig = 'db2';
        $usrdt=$this->vicidialCloserLog->query($usrQry);
                    
		$cur=date("H:i:s");
		$dat=date("Y-m-d");
		$timeLabel=date("H:i:s",strtotime($start_time_start));
       
		if($firstDay == $dat){
		if($timeLabel<=$cur )
		{
                if($usrDt[0][0]['UserLoggedIn']=='')
                {
			       $userCnt =1;
                }
                else
                {
                   $userCnt=$userCnt+$usrDt[0][0]['UserLoggedIn'];
                }
		}
		else{
			$userCnt='';
		}}
else{
$userCnt=$userCnt+$usrDt[0][0]['UserLoggedIn'];

}		        
        } 

        $cur=date("H:i:s");		
		$timeLabel=date("H:i:s",strtotime($start_time_start));
		
                $talk=explode(':',$dt[0][0]['TalkTime']);
                $tadl=($talk[0]*60*60)+($talk[1]*60)+$talk[2];
                
		$totalHandle=$totalHandle+$tadl;
        $total_avg_abend_time = $dt[0][0]['avgabandtime'] / $dt[0][0]['Abandon'];         
		$timeLabel=date("H:i:s",strtotime($start_time_start));
		$dateLabel=date("Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		$data['Offered'][$dateLabel][$timeLabel]=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
		$data['Handled'][$dateLabel][$timeLabel]=$dt[0][0]['Answered'];
		$data['Calls Ans (within 20 Sec)'][$dateLabel][$timeLabel]=$dt[0][0]['WIthinSLA'];
		$data['Total Calls Abandoned'][$dateLabel][$timeLabel]=$dt[0][0]['Abandon'];
		$data['Abnd Within (20)'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinThresold'];
        //$data['Average Aband Time'][$dateLabel][$timeLabel]='';
		$data['Average Aband Time'][$dateLabel][$timeLabel]=$total_avg_abend_time;
		$data['Total Talk time'][$dateLabel][$timeLabel]=$dt[0][0]['TalkTime'];

		$data['SL% (20 Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$data['Offered'][$dateLabel][$timeLabel])."%";
		$data['ASA'][$dateLabel][$timeLabel]=round($dt[0][0]['Answered']*100/$data['Offered'][$dateLabel][$timeLabel])."%";
		$data['AHT (In Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
		$total=$dt[0][0]['Answered']+ $dt[0][0]['Abandon'];
		$tatlahct = $tatlahct+ $dt[0][0]['TotalAcht'];
		$Answered=$Answered + $dt[0][0]['Answered'];
             
        if($userCnt<='0') { $userCnt='2';  } else { $userCnt=$userCnt;  }  
		//$data['Agent Logged In'][$dateLabel][$timeLabel]=$userCnt;
        $data['Agent Logged In'][$dateLabel][$timeLabel]=$dt[0][0]['Agentlogin'];
                
 		$TotalCall+=$total;
	$i++;
        
        }}

$seconds1 = $totalHandle % 60;
$time1 = ($totalHandle - $seconds1) / 60;
$minutes1 = $time1 % 60;
$hours1 = ($time1 - $minutes1) / 60;

$minutes1 = ($minutes1<10?"0".$minutes1:"".$minutes1);
$seconds1 = ($seconds1<10?"0".$seconds1:"".$seconds1);
$hours1 = ($hours1<10?"0".$hours1:"".$hours1);

 $Totalh = ($hours1>0?$hours1.":":"00:").$minutes1.":".$seconds1;
  // $this->layout="ajax";     
 // print_r($data);   
        //$this->set('data',$data);
        // $this->set('datetimeArray',$datetimeArray);
$filename = "sla_over_all_slot_wise".date("Y-m-d_h-i_s",time());
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$filename."."xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table cellspacing="0" border="1">
<thead>
	<tr style="background-color:#317EAC; color:#FFFFFF;">
            
            
		<th>Time Slot</th>			
		<?php foreach($data as $dateLabel=>$timeArray) { echo "<th>".$dateLabel."</th>"; } ?>
	</tr>
	
</thead>
<tbody>
	<?php $dataZ[] = 'Grand Total'; foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) {
  $dataY = array();
                echo '<tr>';
		echo "<td>$timeLabel</td>"; 

	 foreach($data as $dataLabel1=>$dataSub1) { 
	
               // $html.='<td>'.$data1[$dataLabel].'</td>';
             
		  echo "<td>".$dataSub1[$dateLabel][$timeLabel]."</td>";
                  $dataZ[$dataLabel1] += $dataSub1[$dateLabel][$timeLabel];
                  }
	
         echo '</tr>';
	}}

	$dataZ['SL% (20 Sec)'] = round($dataZ['Calls Ans (within 20 Sec)']*100/$dataZ['Offered'])."%";
	$dataZ['AHT (In Sec)']=round($tatlahct/$Answered);   
    $dataZ['ASA']=round($Answered*100/$dataZ['Offered'])."%";         
    $dataZ['Total Talk time']=$Totalh;
//print_r($dataZ);die;
echo '<tr>';
foreach($dataZ as $k=>$gt)
{ echo '<td>'.$gt.'</td>';

}echo '<tr>';

 ?>						
</tbody>
</table>			


   <?php
exit;
        }
    }




public function dd_clientwise() 
    {
         $this->layout='user';
         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            //$client = array('All'=>'All')+$client;
            $this->set('client',$client); 
          }

        if($this->request->is("POST")){

            $clientId   = $this->Session->read('companyid');

            $search     =$this->request->data['CdrReports'];
            
            $Campagn  = $this->Session->read('campaignid');
            $firstDay=$search['startdate'];
            $lastDay=$search['enddate'];
            $clientId = $search['clientID'];
            //echo $clientId;
            //print_r($clientId);
            $stringRepresentation = implode(',', $clientId);

            //echo $stringRepresentation;
            //die;
            
            $start_time_start='00:00:00';
            $start_time_end='23:59:59';
                
         $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
         //echo "SELECT company_id,campaignid AS campaign_id FROM `registration_master` WHERE company_id in ($stringRepresentation) and `status`='A'";die;
         $ClientList = $this->RegistrationMaster->query("SELECT company_id,campaignid AS campaign_id FROM `registration_master` WHERE company_id in ($stringRepresentation) and `status`='A'"); 
         
    	 //$campaignId =   "t2.campaign_id in(".$campaignId.")";


        
        
	 //$i=0;
 if(!empty($firstDay)){
     foreach($ClientList as $ClientInfo)
     {
         //print_r($ClientInfo);exit;
         $campaignId1 = $ClientInfo['registration_master']['campaign_id'];
         $campaignId = $campaignId1; 
         $ClientArray  	=   explode(',',str_replace("'"," ",$campaignId));
         $clientId = $ClientInfo['registration_master']['company_id'];
         $sel_balance_qry = "SELECT * FROM `balance_master` bm WHERE clientId='$clientId' limit 1";
         $bal_det=$this->RegistrationMaster->query($sel_balance_qry);
         $planId = $bal_det['0']['bm']['PlanId'];
         $plan_det_qry = "SELECT InboundCallCharge,rate_per_pulse_day_shift,pulse_day_shift FROM `plan_master` pm WHERE id='$planId' limit 1"; 
         $plan_det = $this->RegistrationMaster->query($plan_det_qry);
         $ib_pulse_rate = $plan_det['0']['pm']['rate_per_pulse_day_shift'];
         $ib_pulse_sec = $plan_det['0']['pm']['pulse_day_shift'];
         $rate = $plan_det['0']['pm']['InboundCallCharge'];
         //echo $rate;exit;
         //$rate_per_sec = $ib_pulse_rate;
         //echo $campaignId1 ;
         //echo '<br/>';
         //echo $rate_per_sec;exit;
         $rate_per_sec = $ib_pulse_rate/$ib_pulse_sec; 
         
         //print_r($ClientArray);exit;
         foreach($ClientArray as $campaign_row){
		
	$campaign_row=trim($campaign_row);
        if(empty($campaign_row))
        {continue;}

     $qry = "
        SELECT t2.campaign_id, COUNT(*) `Total`,
        SEC_TO_TIME(LEFT(SUM(talk_sec)+SUM(pause_sec)+SUM(wait_sec)+SUM(dispo_sec),6)) AS TalkTime,
        SEC_TO_TIME(LEFT(SUM(dispo_sec),6))AS `dispotime`,SEC_TO_TIME(LEFT(SUM(dispo_sec),5)) `WrapTime`, 
        SUM(if(t2.`user` !='VDCL',1,0)) `Answered`, SUM(if(t2.`user` ='VDCL',1,0)) `Abandon`, 
SUM(IF(t2.`user` !='VDCL',t1.length_in_sec,0)) `TotalAcht`,
SUM(IF(t2.`user` !='VDCL' AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
SUM(IF(t2.`user` !='VDCL' AND t2.queue_seconds<=10,1,0)) `WIthinSLATen`,
SUM(IF(t2.`user` ='VDCL' AND t2.queue_seconds<=20,1,0)) `AbndWithinThresold`,
SUM(IF(t2.`user` ='VDCL' AND t2.queue_seconds>20,1,0)) `AbndAfterThresold` FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid WHERE  time(t2.call_date)>='$start_time_start' AND time(t2.call_date)<='$start_time_end' and date(t2.call_date)>='$firstDay' AND date(t2.call_date)<='$lastDay'
        and t2.campaign_id='$campaign_row' and t2.term_reason!='AFTERHOURS' and t2.lead_id is not null group by t2.campaign_id"; 
        $this->vicidialCloserLog->useDbConfig = 'db2';
         $dt=$this->vicidialCloserLog->query($qry);

		$cur=date("H:i:s");
		$timeLabel=date("H:i:s",strtotime($start_time_start));

		$talk=explode(':',$dt[0][0]['TalkTime']);
		$tadl=($talk[0]*60*60)+($talk[1]*60)+$talk[2];
                
		$totalHandle=$totalHandle+$tadl;
                
		$timeLabel=$campaign_row;
		$dateLabel=date("Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		        $data['Offered'][$dateLabel][$timeLabel]=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
                $data['Handled'][$dateLabel][$timeLabel]=$dt[0][0]['Answered'];
                $data['Calls Ans (20 Sec)'][$dateLabel][$timeLabel]=$dt[0][0]['WIthinSLA'];
                $data['Calls Ans (10 Sec)'][$dateLabel][$timeLabel]=$dt[0][0]['WIthinSLATen'];
                $data['Total Calls Abandoned'][$dateLabel][$timeLabel]=$dt[0][0]['Abandon'];
                $data['Abnd Within (20)'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinThresold'];
                $data['Average Aband Time'][$dateLabel][$timeLabel]='';
                $data['Total Talk time'][$dateLabel][$timeLabel]=$dt[0][0]['TalkTime'];
                $data['SL% (20 Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$data['Handled'][$dateLabel][$timeLabel])."%";
                $data['SL% (10 Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLATen']*100/$data['Handled'][$dateLabel][$timeLabel])."%";
                $data['AL'][$dateLabel][$timeLabel]=round($dt[0][0]['Answered']*100/$data['Offered'][$dateLabel][$timeLabel])."%";
                $data['AHT (In Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
                $data['Call Rate'][$dateLabel][$timeLabel] = $rate;
                $data['Amount'][$dateLabel][$timeLabel] = round($dt[0][0]['Abandon']*$rate_per_sec*round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']),2);
                
                
				$total=$dt[0][0]['Answered']+ $dt[0][0]['Abandon'];
				$tatlahct = $tatlahct+ $dt[0][0]['TotalAcht'];
                $Answered=$Answered + $dt[0][0]['Answered'];
                $Offered=$Offered + $dt[0][0]['Offered'];
		$TotalCall+=$total;
	$i++;
        
        }}
     }
        
 
        
	$seconds1 = $totalHandle % 60;
	$time1 = ($totalHandle - $seconds1) / 60;
	$minutes1 = $time1 % 60;
	$hours1 = ($time1 - $minutes1) / 60;

	$minutes1 = ($minutes1<10?"0".$minutes1:"".$minutes1);
	$seconds1 = ($seconds1<10?"0".$seconds1:"".$seconds1);
	$hours1 = ($hours1<10?"0".$hours1:"".$hours1);

	 $Totalh = ($hours1>0?$hours1.":":"00:").$minutes1.":".$seconds1; 
$filename = "sla_clientwise_report".date("Y-m-d_h-i_s",time());
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$filename."."xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table cellspacing="0" border="1">
<thead>
	<tr style="background-color:#317EAC; color:#FFFFFF;">
            
            
		<th>Client Name</th>			
		<?php foreach($data as $dateLabel=>$timeArray) { echo "<th>".$dateLabel."</th>"; } ?>
	</tr>
	
</thead>
<tbody>
	<?php $dataZ[] = 'Grand Total'; foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) {
  $dataY = array();
                echo '<tr>';
		echo "<td>$timeLabel</td>"; 

	 foreach($data as $dataLabel1=>$dataSub1) { 
	
               // $html.='<td>'.$data1[$dataLabel].'</td>';
             
		  echo "<td>".$dataSub1[$dateLabel][$timeLabel]."</td>";
                  $dataZ[$dataLabel1] += $dataSub1[$dateLabel][$timeLabel];
                  }
	
         echo '</tr>';
	}}
			 $dataZ['SL% (20 Sec)'] = round($dataZ['Calls Ans (20 Sec)']*100/$dataZ['Handled'])."%";
             $dataZ['SL% (10 Sec)'] = round($dataZ['Calls Ans (10 Sec)']*100/$dataZ['Handled'])."%";
             $dataZ['AL'] = round($dataZ['Handled']*100/$dataZ['Offered'])."%";
             $dataZ['AHT (In Sec)']=round($tatlahct/$Answered);   
             $dataZ['Total Talk time']=$Totalh;
//print_r($dataZ);
echo '<tr>';
foreach($dataZ as $k=>$gt)
{ 
    //if($k=='Call Rate'){} else {}
    echo '<td>'.$gt.'</td>';
    
}echo '<tr>';

 ?>						
</tbody>
</table>			

   <?php
exit;
        }
    }


    public function abandon_call() 
    {
         $this->layout='user';
         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
        if($this->request->is("POST")){
        
			$search     =$this->request->data['CdrReports'];

			$clientId = $search['clientID'];
			$FromDate = $search['startdate'];
			$ToDate = $search['enddate'];
                        $FromDate=date("Y-m-d",strtotime("$FromDate"));
                        $ToDate=date("Y-m-d",strtotime("$ToDate"));
			if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1'"); 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
           
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

         $qry="
        SELECT 
        SUM(IF(t2.user='VDCL',1,0)) `Abandon`,
        SUM(IF(((t2.user='VDCL') AND (t2.queue_seconds IS NULL OR t2.queue_seconds<=10)),1,0)) `AbndWithinTen`,
        SUM(IF(((t2.user='VDCL') AND (t2.queue_seconds>10 and t2.queue_seconds<=15)),1,0)) `AbndWithinFif`,
        SUM(IF(((t2.user='VDCL') AND (t2.queue_seconds>15 and t2.queue_seconds<=20)),1,0)) `AbndWithinTwe`,
        SUM(IF(((t2.user='VDCL') AND (t2.queue_seconds>20 and t2.queue_seconds<=30)),1,0)) `AbndWithinThr`,
        SUM(IF(((t2.user='VDCL') AND (t2.queue_seconds IS NULL OR t2.queue_seconds>30)),1,0)) `AbndAftertThr`
        FROM asterisk.vicidial_closer_log t2 
        LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid  AND t1.lead_id!='' AND t2.user=t1.user
        LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
        WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end' AND $campaignId
            "; 

                if($clientId!='375')
                {
		                $this->vicidialCloserLog->useDbConfig = 'db2';
                }
                else
                {
                        $this->vicidialCloserLog->useDbConfig = 'db6';
                }
		                $dt=$this->vicidialCloserLog->query($qry);
                
 		$timeLabel=date("d-M-Y",strtotime($start_time_start));
		$dateLabel=date("F-Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		
		
		$data['Abandon Call'][$dateLabel][$timeLabel]=$dt[0][0]['Abandon'];
		$data['Abandon in 10 Sec'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinTen'];
        $data['Abandon in 15 Sec'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinFif'];
        $data['Abandon in 20 Sec'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinTwe'];
        $data['Abandon in 30 Sec'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinThr'];
        $data['Abandon After 30 Sec'][$dateLabel][$timeLabel]=$dt[0][0]['AbndAftertThr'];
	
	}

        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
        }
    }

    public function abandon_call_excel() 
    {
         $this->layout='user';
         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
        if($this->request->is("POST")){
        
			$search     =$this->request->data['CdrReports'];

			$clientId = $search['clientID'];
			$FromDate = $search['startdate'];
			$ToDate = $search['enddate'];
                        $FromDate=date("Y-m-d",strtotime("$FromDate"));
                        $ToDate=date("Y-m-d",strtotime("$ToDate"));
			if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1'"); 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
           
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

         $qry="
        SELECT 
        SUM(IF(t2.user='VDCL',1,0)) `Abandon`,
        SUM(IF(((t2.user='VDCL') AND (t2.queue_seconds IS NULL OR t2.queue_seconds<=10)),1,0)) `AbndWithinTen`,
        SUM(IF(((t2.user='VDCL') AND (t2.queue_seconds>10 and t2.queue_seconds<=15)),1,0)) `AbndWithinFif`,
        SUM(IF(((t2.user='VDCL') AND (t2.queue_seconds>15 and t2.queue_seconds<=20)),1,0)) `AbndWithinTwe`,
        SUM(IF(((t2.user='VDCL') AND (t2.queue_seconds>20 and t2.queue_seconds<=30)),1,0)) `AbndWithinThr`,
        SUM(IF(((t2.user='VDCL') AND (t2.queue_seconds IS NULL OR t2.queue_seconds>30)),1,0)) `AbndAftertThr`
        FROM asterisk.vicidial_closer_log t2 
        LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid  AND t1.lead_id!='' AND t2.user=t1.user
        LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
        WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end' AND $campaignId
            "; 

                if($clientId!='375')
                {
		                $this->vicidialCloserLog->useDbConfig = 'db2';
                }
                else
                {
                        $this->vicidialCloserLog->useDbConfig = 'db6';
                }
		                $dt=$this->vicidialCloserLog->query($qry);
                
 		$timeLabel=date("d-M-Y",strtotime($start_time_start));
		$dateLabel=date("F-Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		
		
		$data['Abandon Call'][$dateLabel][$timeLabel]=$dt[0][0]['Abandon'];
		$data['Abandon in 10 Sec'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinTen'];
        $data['Abandon in 15 Sec'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinFif'];
        $data['Abandon in 20 Sec'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinTwe'];
        $data['Abandon in 30 Sec'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinThr'];
        $data['Abandon After 30 Sec'][$dateLabel][$timeLabel]=$dt[0][0]['AbndAftertThr'];
	
	}

        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
        }
    }




}
?>