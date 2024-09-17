<?php
class IvrReportsController extends AppController
{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses = array('EcrRecord','CallRecord','vicidialCloserLog','vicidialUserLog','CroneJob','AbandCallMaster');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
        $flag = false;
        if($this->Session->check("companyid"))
        {
            $flag = true;
        }
        if($this->Session->check("admin_id"))
        {
            $flag = true;
        }
        if(!$flag)
        {
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
    
    public function index() {
        $this->layout='user';
        if($this->request->is("POST")){
            
            //$campaignId ="campaign_id in(". $this->Session->read('campaignid').")";
            $clientId   =   $this->Session->read('companyid');
           $search=$this->request->data['IvrReports'];
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));
            
            $qry = "SELECT *,DATE_FORMAT(start_time,'%d-%b-%y') dater,DATE_FORMAT(start_time,'%d-%b-%y %H:%i:%s') start_date,
            DATE_FORMAT(end_time,'%d-%b-%y %H:%i:%s') end_date FROM `ivr_log` il  WHERE client_id='$clientId' and DATE(start_time) BETWEEN '$start_time' AND '$end_time'";
            $data=$this->EcrRecord->query($qry);
            
            #print_r($data);exit;
            if($clientId == 525)
            {
                foreach($data as &$opt)
                {
                    $option = $opt['il']['opt'];
                    $values = explode(',', $option);
                    #print_r($values[1]);die;
                    $string = "";
                    if ($values[1] == 1) {
                        $string .= '- New Order';
                    } elseif ($values[1] == 2) {
                        $string .= '- Services';
                    } elseif ($values[1] == 3) {
                        $string .= '- Order Status';
                    } elseif ($values[1] == 4) {
                        $string .= '- Bulk Enquiry';
                    }elseif ($values[1] == 7) {
                        $string .= '- Complaints';
                    }elseif ($values[1] == 9) {
                        $string .= '- Any Other Enquiry';
                    }

                    if ($values[0] == "lang1") {
                        $opt['il']['languages'] = 'English'.$string;
                    } elseif ($values[0] == "lang2") {
                        $opt['il']['languages'] = 'Hindi'.$string;
                    }
                    
                    
                }
            }
            

            #print_r($data);die;
            $this->set('data',$data);
         
        }
    }
    
    
    public function export_ivr_log()
    {
        if($this->request->is("POST")){
            
            
            
            
                
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=ivr_log_report.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
           
            
            $search=$this->request->data['IvrReports'];
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));
            
            $clientId       =   $this->Session->read('companyid');
            $qry = "SELECT *,DATE_FORMAT(start_time,'%d-%b-%y') dater,DATE_FORMAT(start_time,'%d-%b-%y %H:%i:%s') start_date,
            DATE_FORMAT(end_time,'%d-%b-%y %H:%i:%s') end_date FROM `ivr_log` il  WHERE client_id='$clientId' and DATE(start_time) BETWEEN '$start_time' AND '$end_time'";
            $data=$this->EcrRecord->query($qry);

            if($clientId == 525)
            {
                foreach($data as &$opt)
                {
                    $option = $opt['il']['opt'];
                    $values = explode(',', $option);
                    #print_r($values[1]);die;
                    $string = "";
                    if ($values[1] == 1) {
                        $string .= '- New Order';
                    } elseif ($values[1] == 2) {
                        $string .= '- Services';
                    } elseif ($values[1] == 3) {
                        $string .= '- Order Status';
                    } elseif ($values[1] == 4) {
                        $string .= '- Bulk Enquiry';
                    }elseif ($values[1] == 7) {
                        $string .= '- Complaints';
                    }elseif ($values[1] == 9) {
                        $string .= '- Any Other Enquiry';
                    }

                    if ($values[0] == "lang1") {
                        $opt['il']['languages'] = 'English'.$string;
                    } elseif ($values[0] == "lang2") {
                        $opt['il']['languages'] = 'Hindi'.$string;
                    }
                    
                    
                }
            }
            
            
            ?>
        
            <table cellspacing="0" border="1">
                <tr>
		            <th>Date</th>			
		            <th>Call Type</th>
                    <th>From</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Duration (Sec.)</th>
                    <th>Outcome</th>
                    <th>Options chosen</th>
                    <th>Options</th>
                </tr>
                <?php foreach($data as $record) { ?>
	            <tr>
		        <td><?php echo $record['0']['dater']; ?></td>
		        <td><?php echo $record['il']['call_type']; ?></td>
                <td><?php echo substr($record['il']['from_source'],0,10); ?></td> 
                <td><?php echo $record['0']['start_date']; ?></td>
                <td><?php echo $record['0']['end_date']; ?></td>
                <td><?php echo $record['il']['duration']; ?></td>
                <td><?php echo $record['il']['outcome']; ?></td>
                <td><?php echo $record['il']['opt']; ?></td>
                <td><?php echo $record['il']['languages']; ?></td>
	            </tr>		
	        <?php } ?>
            </table>

            <?php
            }
            
           
                
             die;   
       
        
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
	
		 
 /*$qry = "SELECT count(*) `Total`,
sum(if(t2.term_reason='AGENT' OR t2.term_reason='CALLER',1,0)) `Answered`,
sum(if(t2.term_reason='QUEUETIMEOUT' OR t2.term_reason='NONE'OR t2.term_reason='ABANDON',1,0)) `Abandon`,
SUM(IF(t2.status!='DROP',t1.length_in_sec,0)) `TotalAcht`,
SUM(IF(t2.status!='DROP' AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP')
AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP')
AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end'
and $campaignId and t2.term_reason!='AFTERHOURS'";*/

$qry = "SELECT COUNT(*) `Total`,
SUM(If((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') and user !='vdcl',1,0)) `Answered`,
SUM(IF(t2.status IS NULL OR t2.status='DROP',1,IF(t2.USER='VDCL',1,0))) `Abandon`,
SUM(IF(t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' 
OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' 
OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' 
OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI',UNIX_TIMESTAMP(IF(t2.end_epoch IS NULL,
FROM_UNIXTIME(t2.start_epoch),FROM_UNIXTIME(t2.end_epoch)))-UNIX_TIMESTAMP(IF(queue_seconds='0',call_date,
FROM_UNIXTIME((UNIX_TIMESTAMP(call_date)+queue_seconds)))),0)) `TotalAcht`,
SUM(IF((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' 
OR t2.status='REQ' OR t2.status='B' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' 
OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' 
OR t2.status='AA' OR t2.status='ERI') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL') AND (t2.queue_seconds is null OR t2.queue_seconds<=300)),1,0)) `AbndWithinThresold`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL') AND t2.queue_seconds>300),1,0)) `AbndAfterThresold`
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end'
and $campaignId and t2.term_reason!='AFTERHOURS'";
		$this->vicidialCloserLog->useDbConfig = 'db2';
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
		//$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
                
                //$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']/($total-$dt[0][0]['AbndWithinThresold'])*100);
                
                $data['SL% (20 Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$data['Total Calls Landed'][$dateLabel][$timeLabel])."%";
                
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
    
    
    public  function call_mis(){
        if($this->request->is("POST")){
            
            $campaignId     =   "campaign_id in(". $this->Session->read('campaignid').")";
            $clientId       =   $this->Session->read('companyid');
            
            if($clientId =="326"){
                
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=ivr_report.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
           
            
            $search         =   $this->request->data['IvrReports'];
       
            $Campagn        =   $this->Session->read('campaignid');
            $FromDate       =   $search['startdate'];
            $ToDate         =   $search['enddate'];
        
            $qry="SELECT msisdn,Caller_Id UniqueId,options optSelect,createdate FROM ivr_entry where levels = '2' and date(createdate) between '$FromDate' and '$ToDate'";
        
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt=$this->vicidialCloserLog->query($qry);
            ?>
        
            <table cellspacing="0" border="1">
                <tr>
                    <th>msisdn</th>
                    <th>UniqueId</th>
                    <th>optSelect</th>
                    <th>createdate</th>
                </tr>
                <?php foreach($dt as $data) { ?>
                <tr>
                    <td><?php echo $data['ivr_entry']['msisdn']; ?></td>
                    <td><?php echo $data['ivr_entry']['UniqueId']; ?></td>
                    <td><?php echo $data['ivr_entry']['optSelect']; ?></td>
                    <td><?php echo $data['ivr_entry']['createdate']; ?></td>
                </tr>		
                <?php } ?>
            </table>

            <?php
            }
            else{
                return $this->redirect(array('controller'=>'IvrReports','action' => 'index')); 
            }
           
                
             die;   
       
        }
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
	
		 /*$qry="SELECT
				COUNT(*) `Total`,
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
				
				AND (t2.status IS NULL OR t2.status='DROP' OR t2.status='SALE' OR t2.status='INCALL')"; */
$qry = "SELECT COUNT(*) `Total`,
SUM(If((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') and user !='vdcl',1,0)) `Answered`,
SUM(IF(t2.status IS NULL OR t2.status='DROP',1,IF(t2.USER='VDCL',1,0))) `Abandon`,
SUM(IF(t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' 
OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' 
OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' 
OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI',UNIX_TIMESTAMP(IF(t2.end_epoch IS NULL,
FROM_UNIXTIME(t2.start_epoch),FROM_UNIXTIME(t2.end_epoch)))-UNIX_TIMESTAMP(IF(queue_seconds='0',call_date,
FROM_UNIXTIME((UNIX_TIMESTAMP(call_date)+queue_seconds)))),0)) `TotalAcht`,
SUM(IF((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' 
OR t2.status='REQ' OR t2.status='B' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' 
OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' 
OR t2.status='AA' OR t2.status='ERI') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='AA') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL') AND (t2.queue_seconds is null OR t2.queue_seconds<=30)),1,0)) `AbndWithinThresold`,
SUM(IF(((t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL') AND t2.queue_seconds>30),1,0)) `AbndAfterThresold`
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end'
and $campaignId and t2.term_reason!='AFTERHOURS'";
		$this->vicidialCloserLog->useDbConfig = 'db2';
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
		//$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
                
                //$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']/($total-$dt[0][0]['AbndWithinThresold'])*100);
                
                $data['SL% (20 Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$data['Total Calls Landed'][$dateLabel][$timeLabel])."%";
                
                
                
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
        if($this->request->is("POST")){
            $campaignId =   "t2.campaign_id in(". $this->Session->read('campaignid').")";
            $clientId   =   $this->Session->read('companyid');
            $search     =   $this->request->data['CdrReports'];
            $FromDate   =   date("Y-m-d",strtotime($search['startdate']));
            $ToDate     =   date("Y-m-d",strtotime($search['enddate']));
            
        
            $qry="SELECT 
            IF(t2.status IS NULL OR t2.status='DROP','VDCL',t2.USER) Agent,t2.lead_id as LeadId,
            LEFT(phone_number,10) PhoneNumber,
            DATE(call_date) CallDate ,
            SEC_TO_TIME(queue_seconds) Queuetime,
            IF(queue_seconds='0',FROM_UNIXTIME(t2.start_epoch),FROM_UNIXTIME(t2.start_epoch-queue_seconds)) QueueStart,
            FROM_UNIXTIME(t2.start_epoch) StartTime,
            FROM_UNIXTIME(t2.end_epoch) Endtime,
            SEC_TO_TIME(t2.`length_in_sec`) CallDuration,
            FROM_UNIXTIME(t2.end_epoch+TIME_TO_SEC(IF(t3.dispo_sec IS NULL,SEC_TO_TIME(0),IF(t3.sub_status='LOGIN'  OR t3.sub_status='Feed' OR t3.talk_sec=t3.dispo_sec OR t3.talk_sec=0,SEC_TO_TIME(1),IF(t3.dispo_sec>100,SEC_TO_TIME(t3.dispo_sec-(t3.dispo_sec/100)*100),SEC_TO_TIME(t3.dispo_sec)))))) WrapEndTime,
            IF(t3.dispo_sec IS NULL,SEC_TO_TIME(0),IF(t3.sub_status='LOGIN'  OR t3.sub_status='Feed' OR t3.talk_sec=t3.dispo_sec OR t3.talk_sec=0,SEC_TO_TIME(1),IF(t3.dispo_sec>100,SEC_TO_TIME(t3.dispo_sec-(t3.dispo_sec/100)*100),SEC_TO_TIME(t3.dispo_sec)))) WrapTime
            FROM asterisk.vicidial_closer_log t2 
            LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
            LEFT JOIN  asterisk.vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid 
            LEFT JOIN asterisk.recording_log t4 ON t2.lead_id=t4.lead_id AND DATE(t4.start_time)>='2017-01-01'
            WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate'
            AND $campaignId AND t2.lead_id IS NOT NULL GROUP BY t1.uniqueid";
        
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt=$this->vicidialCloserLog->query($qry);
            $this->set('Data',$dt);
        }
    }
        
    public function cdr_mis(){
        if($this->request->is("POST")){
            $campaignId =   "t2.campaign_id in(". $this->Session->read('campaignid').")";
            $clientId   =   $this->Session->read('companyid');
            $search     =   $this->request->data['CdrReports'];
            $FromDate   =   date("Y-m-d",strtotime($search['startdate']));
            $ToDate     =   date("Y-m-d",strtotime($search['enddate']));
            
        
            $qry="SELECT 
            IF(t2.status IS NULL OR t2.status='DROP','VDCL',t2.USER) Agent,
            LEFT(phone_number,10) PhoneNumber,
            DATE(call_date) CallDate ,
            SEC_TO_TIME(queue_seconds) Queuetime,
            IF(queue_seconds='0',FROM_UNIXTIME(t2.start_epoch),FROM_UNIXTIME(t2.start_epoch-queue_seconds)) QueueStart,
            FROM_UNIXTIME(t2.start_epoch) StartTime,
            FROM_UNIXTIME(t2.end_epoch) Endtime,
            SEC_TO_TIME(t2.`length_in_sec`) CallDuration,
            FROM_UNIXTIME(t2.end_epoch+TIME_TO_SEC(IF(t3.dispo_sec IS NULL,SEC_TO_TIME(0),IF(t3.sub_status='LOGIN'  OR t3.sub_status='Feed' OR t3.talk_sec=t3.dispo_sec OR t3.talk_sec=0,SEC_TO_TIME(1),IF(t3.dispo_sec>100,SEC_TO_TIME(t3.dispo_sec-(t3.dispo_sec/100)*100),SEC_TO_TIME(t3.dispo_sec)))))) WrapEndTime,
            IF(t3.dispo_sec IS NULL,SEC_TO_TIME(0),IF(t3.sub_status='LOGIN'  OR t3.sub_status='Feed' OR t3.talk_sec=t3.dispo_sec OR t3.talk_sec=0,SEC_TO_TIME(1),IF(t3.dispo_sec>100,SEC_TO_TIME(t3.dispo_sec-(t3.dispo_sec/100)*100),SEC_TO_TIME(t3.dispo_sec)))) WrapTime
            FROM asterisk.vicidial_closer_log t2 
            LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
            LEFT JOIN  asterisk.vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid 
            LEFT JOIN asterisk.recording_log t4 ON t2.lead_id=t4.lead_id AND DATE(t4.start_time)>='2017-01-01'
            WHERE DATE(t2.call_date) BETWEEN '$FromDate' AND '$ToDate'
            AND $campaignId AND t2.lead_id IS NOT NULL GROUP BY t1.uniqueid";
        
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt=$this->vicidialCloserLog->query($qry);
            
            
            
            
            
            $this->set('Data',$dt);
        }
    }

}
?>