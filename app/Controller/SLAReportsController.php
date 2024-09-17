<?php
class SLAReportsController extends AppController{
    
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('RegistrationMaster','CallMaster','vicidialCloserLog','vicidialUserLog','ClientReportMaster','AbandCallMaster','Agent');
	
    public function beforeFilter(){
        parent::beforeFilter();
	$this->Auth->allow('index','view_report','cdr','hour_reports');
	if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
	}
    }

    public function index(){
        $this->layout = "user";
        if($this->request->is('Post')){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=CallTaggingSummary.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $fdate=$this->request->data['FromDate'];
            $ldate=$this->request->data['ToDate'];
            $client_id = $_REQUEST['client_id'];
            
            $condition_cl = array();
            if($client_id=='All')
            {
                $condition_cl = "Status='A'";
            }
            else
            {
                 $condition_cl = "company_id='$client_id' and Status='A'"; 
            }
            
            //$conditions = "and date(CallDate) between '$fdate' and '$ldate'";
            //$view_date = "date(t2.call_date) between '$fdate' and '$ldate'";
            $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>$condition_cl,'order'=>array('company_name')));
            $clientList = $this->ClientReportMaster->find('list',array('fields'=>array('ClientId'),'conditions'=>array('Status'=>'A')));
            $this->vicidialCloserLog->useDbConfig = 'db2';
            
            
           
                    
                    $slot_arr = array(
                        'Slot 8:AM To 10 AM'=>array(0=>"$fdate",1=>"$ldate",2=>"08:00:00",3=>"09:59:59"),
                        'Slot 10:AM To 8 PM'=>array(0=>"$fdate",1=>"$ldate",2=>"10:00:00",3=>"19:59:59"),
                        'Slot 8:PM To 10 PM'=>array(0=>"$fdate",1=>"$ldate",2=>"20:00:00",3=>"21:59:59"),
                        'Slot 10:PM To 5 AM'=>array(0=>"$fdate",1=>date('Y-m-d',strtotime('+1 day',strtotime($ldate))),2=>"22:00:00",3=>"05:00:00")
                        );
            ?>        
            <table border="2">
                <thead>
                    <tr>
                        <th rowspan="2">CLIENT NAME</th>
                        <th colspan="5">Slot 8:AM To 10 AM</th>
                        <th colspan="5">Slot 10:AM To 8 PM</th>
                        <th colspan="5">Slot 8:PM To 10 PM</th>
                        <th colspan="5">Slot 10:PM To 5 AM</th>
                    </tr>
                    <tr>
                        <th>TOTAL ANSWER</th>
                        <th>TOTAL ABAND</th>
                        <th>TOTAL TAGGED</th>
                        <th>UNIQUE TAGGED</th>
                        <th>ABAND CALL BACK</th> 
                        
                        <th>TOTAL ANSWER</th>
                        <th>TOTAL ABAND</th>
                        <th>TOTAL TAGGED</th>
                        <th>UNIQUE TAGGED</th>
                        <th>ABAND CALL BACK</th>
                        
                        <th>TOTAL ANSWER</th>
                        <th>TOTAL ABAND</th>
                        <th>TOTAL TAGGED</th>
                        <th>UNIQUE TAGGED</th>
                        <th>ABAND CALL BACK</th> 
                        
                        <th>TOTAL ANSWER</th>
                        <th>TOTAL ABAND</th>
                        <th>TOTAL TAGGED</th>
                        <th>UNIQUE TAGGED</th>
                        <th>ABAND CALL BACK</th> 
                        
                        
                    </tr>
                        
                    </tr>
                </thead>
                <tbody>
                <?php 
                $ans=0;
                $abn=0;
                $tag=0;
                $tagu=0;
                $bak=0;
                
                foreach($clientArr as $row){
                    $Campagn=$row['RegistrationMaster']['campaignid'];
                    $CompanyId=$row['RegistrationMaster']['company_id'];
                    
                    if($Campagn !=""){
                        $CampagnId ="and t2.campaign_id in ($Campagn)";
                    }
                    else{
                        $CampagnId ="and t2.campaign_id in ('')";
                    }

                    ?>
                    <tr>
                    <td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                    
                    <?php //print_r($slot_arr); exit;
                    
                    foreach($slot_arr as $slot=>$time_slot)
                    {
                        
                        $view_date = "date(t2.call_date) between '{$time_slot[0]}' and '{$time_slot[1]}'";
                        $conditions_date = "and date(CallDate) between '{$time_slot[0]}' and '{$time_slot[1]}'";
                        //echo '<br/>'; 
                        $view_time = " and time(t2.call_date) between '{$time_slot[2]}' and '{$time_slot[3]}'";
                        $conditions_time = "and time(CallDate) between '{$time_slot[2]}' and '{$time_slot[3]}'";
                       //echo '<br/>'; 
                        
                        $qr1 = "SELECT COUNT(*) `Total`,
                    SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ'
                    OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK'
                    OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R'
                    OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
                    SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`
                    FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid 
                    left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid LEFT JOIN recording_log t4 ON t2.lead_id=t4.lead_id 
                    WHERE $view_date $view_time $CampagnId and t2.term_reason!='AFTERHOURS'";
                        
                        $qr2 = "SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='{$row['RegistrationMaster']['company_id']}' $conditions_date $conditions_time AND TagStatus IS NULL";
                        $qr3 ="Select count(Id) as totaltag , COUNT(DISTINCT LeadId) as totaltagu FROM call_master where ClientId='{$row['RegistrationMaster']['company_id']}' $conditions_date $conditions_time AND CallType !='Upload' AND CallType !='VFO-Inbound'";
                        $qr4 = "SELECT COUNT(Id) AS AbandCallBack FROM `aband_call_master` WHERE ClientId='{$row['RegistrationMaster']['company_id']}' $conditions_date $conditions_time AND TagStatus='yes'";
                        
                        $dt_answer= $this->vicidialCloserLog->query($qr1);
                    
                    $TACC=$this->AbandCallMaster->query($qr2);
                    $tc=$this->CallMaster->query($qr3);
                    $TACB=$this->AbandCallMaster->query($qr4);
                        
                   
                    
                    
                    
         
                    $AnsData=$dt_answer[0][0]['Answered'];
                    $AbnData=$TACC[0][0]['AbandCount'];
                    $totalTag=$tc[0][0]['totaltag'];
                    $totalTagU=$tc[0][0]['totaltagu'];
                    $BakData=$TACB[0][0]['AbandCallBack'];
                    
                    $ans[$slot]=$ans+$AnsData;
                    $abn[$slot]=$abn+$AbnData;
                    $tag[$slot]=$tag+$totalTag;
                    $tagu[$slot]=$tagu+$totalTagU;
                    $bak[$slot]=$bak+$BakData;
                ?>
                
                    <td><?php echo $AnsData;?></td>
                    <td><?php echo $AbnData;?></td>
                    <td><?php echo $totalTag; ?></td>
                    <td><?php echo $totalTagU; ?></td>
                    <td><?php echo $BakData; ?></td>
                
                <?php } ?>
                    </tr>
                <?php exit; } ?>
                <tr>
                    <td>TOTAL</td>
                    <?php foreach($slot_arr as $slot=>$time_slot)
                    { ?>
                    <td><?php echo $ans[$slot];?></td>
                    <td><?php echo $abn[$slot];?></td>
                    <td><?php echo $tag[$slot];?></td>
                    <td><?php echo $tagu[$slot];?></td>
                    <td><?php echo $bak[$slot];?></td>
                    <?php  } ?>
                </tr>
                </tbody>                    
            </table>
            <?php
            exit;
        }
        $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('company_name')));
        $this->set('client_arr',$clientArr);
    }
    
    public function view_report(){
        $this->layout = "ajax";
        if($_REQUEST['fdate']){
            
            $fdate=$_REQUEST['fdate'];
            $ldate=$_REQUEST['ldate'];
            $client_id = $_REQUEST['client_id'];
            
            $condition_cl = array();
            if($client_id=='All')
            {
                $condition_cl = "Status='A'";
            }
            else
            {
                 $condition_cl = "company_id='$client_id' and Status='A'"; 
            }
            
            //$conditions = "and date(CallDate) between '$fdate' and '$ldate'";
            //$view_date = "date(t2.call_date) between '$fdate' and '$ldate'";
            $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>$condition_cl,'order'=>array('company_name')));
            $clientList = $this->ClientReportMaster->find('list',array('fields'=>array('ClientId'),'conditions'=>array('Status'=>'A')));
            $this->vicidialCloserLog->useDbConfig = 'db2';
            
            
           
                    
                    $slot_arr = array(
                        'Slot 8:AM To 10 AM'=>array(0=>"$fdate",1=>"$ldate",2=>"08:00:00",3=>"09:59:59"),
                        'Slot 10:AM To 8 PM'=>array(0=>"$fdate",1=>"$ldate",2=>"10:00:00",3=>"19:59:59"),
                        'Slot 8:PM To 10 PM'=>array(0=>"$fdate",1=>"$ldate",2=>"20:00:00",3=>"21:59:59"),
                        'Slot 10:PM To 5 AM'=>array(0=>"$fdate",1=>date('Y-m-d',strtotime('+1 day',strtotime($ldate))),2=>"22:00:00",3=>"05:00:00")
                        );
            
            
            ?>        
             <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th rowspan="2">CLIENT NAME</th>
                        <th colspan="5">Slot 8:AM To 10 AM</th>
                        <th colspan="5">Slot 10:AM To 8 PM</th>
                        <th colspan="5">Slot 8:PM To 10 PM</th>
                        <th colspan="5">Slot 10:PM To 5 AM</th>
                    </tr>
                    <tr>
                        <th>TOTAL ANSWER</th>
                        <th>TOTAL ABAND</th>
                        <th>TOTAL TAGGED</th>
                        <th>UNIQUE TAGGED</th>
                        <th>ABAND CALL BACK</th> 
                        
                        <th>TOTAL ANSWER</th>
                        <th>TOTAL ABAND</th>
                        <th>TOTAL TAGGED</th>
                        <th>UNIQUE TAGGED</th>
                        <th>ABAND CALL BACK</th>
                        
                        <th>TOTAL ANSWER</th>
                        <th>TOTAL ABAND</th>
                        <th>TOTAL TAGGED</th>
                        <th>UNIQUE TAGGED</th>
                        <th>ABAND CALL BACK</th> 
                        
                        <th>TOTAL ANSWER</th>
                        <th>TOTAL ABAND</th>
                        <th>TOTAL TAGGED</th>
                        <th>UNIQUE TAGGED</th>
                        <th>ABAND CALL BACK</th> 
                        
                        
                    </tr>
                        
                    </tr>
                </thead>
                <tbody>
                <?php 
                $ans=0;
                $abn=0;
                $tag=0;
                $tagu=0;
                $bak=0;
                
                foreach($clientArr as $row){
                    $Campagn=$row['RegistrationMaster']['campaignid'];
                    $CompanyId=$row['RegistrationMaster']['company_id'];
                    
                    if($Campagn !=""){
                        $CampagnId ="and t2.campaign_id in ($Campagn)";
                    }
                    else{
                        $CampagnId ="and t2.campaign_id in ('')";
                    }

                    ?>
                    <tr>
                    <td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                    
                    <?php //print_r($slot_arr); exit;
                    
                    foreach($slot_arr as $slot=>$time_slot)
                    {
                        
                        $view_date = "date(t2.call_date) between '{$time_slot[0]}' and '{$time_slot[1]}'";
                        $conditions_date = "and date(CallDate) between '{$time_slot[0]}' and '{$time_slot[1]}'";
                        //echo '<br/>'; 
                        $view_time = " and time(t2.call_date) between '{$time_slot[2]}' and '{$time_slot[3]}'";
                        $conditions_time = "and time(CallDate) between '{$time_slot[2]}' and '{$time_slot[3]}'";
                       //echo '<br/>'; 
                        
                        $qr1 = "SELECT COUNT(*) `Total`,
                    SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ'
                    OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK'
                    OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R'
                    OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
                    SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`
                    FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid 
                    left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid LEFT JOIN recording_log t4 ON t2.lead_id=t4.lead_id 
                    WHERE $view_date $view_time $CampagnId and t2.term_reason!='AFTERHOURS'";
                        
                        $qr2 = "SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='{$row['RegistrationMaster']['company_id']}' $conditions_date $conditions_time AND TagStatus IS NULL";
                        $qr3 ="Select count(Id) as totaltag , COUNT(DISTINCT LeadId) as totaltagu FROM call_master where ClientId='{$row['RegistrationMaster']['company_id']}' $conditions_date $conditions_time AND CallType !='Upload' AND CallType !='VFO-Inbound'";
                        $qr4 = "SELECT COUNT(Id) AS AbandCallBack FROM `aband_call_master` WHERE ClientId='{$row['RegistrationMaster']['company_id']}' $conditions_date $conditions_time AND TagStatus='yes'";
                        
                        $dt_answer= $this->vicidialCloserLog->query($qr1);
                    
                    $TACC=$this->AbandCallMaster->query($qr2);
                    $tc=$this->CallMaster->query($qr3);
                    $TACB=$this->AbandCallMaster->query($qr4);
                        
                   
                    
                    
                    
         
                    $AnsData=$dt_answer[0][0]['Answered'];
                    $AbnData=$TACC[0][0]['AbandCount'];
                    $totalTag=$tc[0][0]['totaltag'];
                    $totalTagU=$tc[0][0]['totaltagu'];
                    $BakData=$TACB[0][0]['AbandCallBack'];
                    
                    $ans[$slot]=$ans+$AnsData;
                    $abn[$slot]=$abn+$AbnData;
                    $tag[$slot]=$tag+$totalTag;
                    $tagu[$slot]=$tagu+$totalTagU;
                    $bak[$slot]=$bak+$BakData;
                ?>
                
                    <td><?php echo $AnsData;?></td>
                    <td><?php echo $AbnData;?></td>
                    <td><?php echo $totalTag; ?></td>
                    <td><?php echo $totalTagU; ?></td>
                    <td><?php echo $BakData; ?></td>
                
                <?php } ?>
                    </tr>
                <?php exit; } ?>
                <tr>
                    <td>TOTAL</td>
                    <?php foreach($slot_arr as $slot=>$time_slot)
                    { ?>
                    <td><?php echo $ans[$slot];?></td>
                    <td><?php echo $abn[$slot];?></td>
                    <td><?php echo $tag[$slot];?></td>
                    <td><?php echo $tagu[$slot];?></td>
                    <td><?php echo $bak[$slot];?></td>
                    <?php  } ?>
                </tr>
                </tbody>                    
            </table>
            <?php
            exit;
        }
    }
    
    
    public function cdr() 
    {
         $this->layout='user';
        if($this->request->is("POST")){
       
        
        
        $search     =$this->request->data['SLAReports'];
       
            
            $FromDate=$search['startdate']; 
            $ToDate=$search['enddate'];
           $client_id = $_REQUEST['client_id']; 
           $this->set('client_id',$client_id);
           
        if($FromDate!='') { $FromDate=$FromDate.' 08:00:00'; }
	if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
            
            
            $condition_cl = array();
            if($client_id=='All')
            {
                $condition_cl = "Status='A'";
            }
            else
            {
                 $condition_cl = "company_id='$client_id' and Status='A'"; 
            }
           
        $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>$condition_cl,'order'=>array('company_name')));
	
        //print_r($clientArr); exit;
        $company_name_arr = array();
        foreach($clientArr as $row){
        $Campagn=$row['RegistrationMaster']['campaignid'];
        $CompanyId=$row['RegistrationMaster']['company_id'];
        $company_name = $row['RegistrationMaster']['company_name'];
                    
                    if($Campagn !=""){
                        $CampagnId =" t2.campaign_id in ($Campagn)";
                    }
                    else{
                        $CampagnId =" t2.campaign_id in ('')";
                    }
        $FromDate1 = $FromDate;
        $ToDate1 = $ToDate;
	while(strtotime($FromDate1)<=strtotime($ToDate1))
	{
		
		$start_time_start=$FromDate1;
		$start_time_end=date("Y-m-d",strtotime("$FromDate1")).' 23:59:59';
                
		
                 $NextDate=date("Y-m-d H:i:s",strtotime("$FromDate1 +1 day"));  
   
$slot_arr = array(
                        'Slot 12:AM To 8 AM'=>array('first'=>date("Y-m-d",strtotime("$FromDate")).' 00:00:00','last'=>date("Y-m-d",strtotime("$FromDate")).' 08:00:00'),
                        'Slot 8:AM To 10 AM'=>array('first'=>date("Y-m-d",strtotime("$FromDate")).' 08:00:00','last'=>date("Y-m-d",strtotime("$FromDate")).' 10:00:00'),
                        'Slot 10:AM To 8 PM'=>array('first'=>date("Y-m-d",strtotime("$FromDate")).' 10:00:00','last'=>date("Y-m-d",strtotime("$FromDate")).' 20:00:00'),
                        'Slot 8:PM To 12 AM'=>array('first'=>date("Y-m-d",strtotime("$FromDate")).' 20:00:00','last'=>date("Y-m-d",strtotime("$FromDate")).' 23:59:59'),
                        
                        
                        );                
  
foreach($slot_arr as $slot=>$slot_cd)
{
                
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
WHERE t2.call_date>='{$slot_cd['first']}' AND t2.call_date<'{$slot_cd['last']}' AND $CampagnId
            "; //exit;

		$this->vicidialCloserLog->useDbConfig = 'db2';
		$dt=$this->vicidialCloserLog->query($qry);
               // print_r($dt);die;
		// Usewr Loggedd In
		
		
		
                        
                        
                            /*print_r($slot_cd);
                            print_r($start_time_start);
                            print_r($start_time_end);
                            exit;*/
                            
                               
                                $timeLabel=$slot;
		$dateLabel=date("d-F-Y",strtotime($start_time_start));
		if(!in_array($timeLabel, $datetimeArray[$dateLabel]))
                {
                    $datetimeArray[$dateLabel][]=$timeLabel; 
                }
                $company_name_arr[] = $company_name;
                
		
		$data[$company_name][$dateLabel][$timeLabel]['Offered %']='';
		$total=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
		$data[$company_name][$dateLabel][$timeLabel]['Total Calls Landed'] +=$total;
		$data[$company_name][$dateLabel][$timeLabel]['Total Calls Answered']+=$dt[0][0]['Answered'];
		$data[$company_name][$dateLabel][$timeLabel]['Total Calls Abandoned']+=$dt[0][0]['Abandon'];
		$data[$company_name][$dateLabel][$timeLabel]['AHT (In Sec)']+=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
		$data[$company_name][$dateLabel][$timeLabel]['Calls Ans (within 20 Sec)']+=$dt[0][0]['WIthinSLA'];
		$data[$company_name][$dateLabel][$timeLabel]['Abnd Within Threshold']+=$dt[0][0]['AbndWithinThresold'];
		$data[$company_name][$dateLabel][$timeLabel]['Abnd After Threshold']+=$dt[0][0]['AbndAfterThresold'];
		$data[$company_name][$dateLabel][$timeLabel]['Ababdoned (%)']+=round($dt[0][0]['Abandon']*100/$total);
		//$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
                
                //$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']/($total-$dt[0][0]['AbndWithinThresold'])*100);
                
                $data[$company_name][$dateLabel][$timeLabel]['SL% (20 Sec)']=round($dt[0][0]['WIthinSLA']*100/$data[$company_name][$dateLabel][$timeLabel]['Total Calls Landed'])."%";
                
		$data[$company_name][$dateLabel][$timeLabel]['AL%']=round($dt[0][0]['Answered']/$data[$company_name][$dateLabel][$timeLabel]['Total Calls Landed']*100)."%";
        
		//$data['Agent Logged In'][$dateLabel][$timeLabel]=$usrDt['UserLoggedIn'];
		
		
		$TotalCall[$company_name][$dateLabel]+=$total;
                           
                        }
                
               //print_r($data); exit;  
                    
                    
                   // $timeLabel=date("H:i",strtotime($start_time_start))." To ".date("H:i",strtotime($start_time_end));
                
		$FromDate1=$NextDate;
	}
	
           
        
        }
         $company_name_arr = array_unique($company_name_arr);
         
         //print_r($company_name_arr); exit;
         
        foreach($company_name_arr as $comp)
        {
            foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
                    $data[$comp][$dateLabel][$timeLabel]['Offered %']=round($data[$comp][$dateLabel][$timeLabel]['Total Calls Landed']*100/$TotalCall[$company_name][$dateLabel]);  
            } }
        }
         
        
        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
          $this->set('company_name_arr',$company_name_arr); 
        }
        
        $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('company_name')));
        $this->set('client_arr',$clientArr);
        
    }
    
    public  function hour_reports()
        {
        if($this->request->is("POST")){
       
        
        
        $search     =$this->request->data['SLAReports'];
       
            
            $FromDate=$search['startdate']; 
            $ToDate=$search['enddate'];
            $client_id = $_REQUEST['client_id']; 
           
        if($FromDate!='') { $FromDate=$FromDate.' 08:00:00'; }
	if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
            
            
            $condition_cl = array();
            if($client_id=='All')
            {
                $condition_cl = "Status='A'";
            }
            else
            {
                 $condition_cl = "company_id='$client_id' and Status='A'"; 
            }
           
        $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>$condition_cl,'order'=>array('company_name')));
	
        //print_r($clientArr); exit;
        $company_name_arr = array();
        foreach($clientArr as $row){
        $Campagn=$row['RegistrationMaster']['campaignid'];
        $CompanyId=$row['RegistrationMaster']['company_id'];
        $company_name = $row['RegistrationMaster']['company_name'];
                    
                    if($Campagn !=""){
                        $CampagnId =" t2.campaign_id in ($Campagn)";
                    }
                    else{
                        $CampagnId =" t2.campaign_id in ('')";
                    }
        $FromDate1 = $FromDate;
        $ToDate1 = $ToDate;
	while(strtotime($FromDate1)<=strtotime($ToDate1))
	{
		
		$start_time_start=$FromDate1;
		$start_time_end=date("Y-m-d",strtotime("$FromDate1")).' 23:59:59';
                
		
                 $NextDate=date("Y-m-d H:i:s",strtotime("$FromDate1 +1 day"));  
   
$slot_arr = array(
                        'Slot 12:AM To 8 AM'=>array('first'=>date("Y-m-d",strtotime("$FromDate1")).' 00:00:00','last'=>date("Y-m-d",strtotime("$FromDate1")).' 08:00:00'),
                        'Slot 8:AM To 10 AM'=>array('first'=>date("Y-m-d",strtotime("$FromDate1")).' 08:00:00','last'=>date("Y-m-d",strtotime("$FromDate1")).' 10:00:00'),
                        'Slot 10:AM To 8 PM'=>array('first'=>date("Y-m-d",strtotime("$FromDate1")).' 10:00:00','last'=>date("Y-m-d",strtotime("$FromDate1")).' 20:00:00'),
                        'Slot 8:PM To 12 AM'=>array('first'=>date("Y-m-d",strtotime("$FromDate1")).' 20:00:00','last'=>date("Y-m-d",strtotime("$FromDate1")).' 23:59:59')
                        
                        
                        );   
  
foreach($slot_arr as $slot=>$slot_cd)
{
                
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
WHERE t2.call_date>='{$slot_cd['first']}' AND t2.call_date<'{$slot_cd['last']}' AND $CampagnId
            "; //exit;

		$this->vicidialCloserLog->useDbConfig = 'db2';
		$dt=$this->vicidialCloserLog->query($qry);
               // print_r($dt);die;
		// Usewr Loggedd In
		
		
		
                        
                        
                            /*print_r($slot_cd);
                            print_r($start_time_start);
                            print_r($start_time_end);
                            exit;*/
                            
                               
                                $timeLabel=$slot;
		$dateLabel=date("d-F-Y",strtotime($start_time_start));
                if(!in_array($timeLabel, $datetimeArray[$dateLabel]))
                {
                    $datetimeArray[$dateLabel][]=$timeLabel; 
                }
                $company_name_arr[] = $company_name;
                
		
		$data[$company_name][$dateLabel][$timeLabel]['Offered %']='';
		$total=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
		$data[$company_name][$dateLabel][$timeLabel]['Total Calls Landed'] +=$total;
		$data[$company_name][$dateLabel][$timeLabel]['Total Calls Answered']+=$dt[0][0]['Answered'];
		$data[$company_name][$dateLabel][$timeLabel]['Total Calls Abandoned']+=$dt[0][0]['Abandon'];
		$data[$company_name][$dateLabel][$timeLabel]['AHT (In Sec)']+=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
		$data[$company_name][$dateLabel][$timeLabel]['Calls Ans (within 20 Sec)']+=$dt[0][0]['WIthinSLA'];
		$data[$company_name][$dateLabel][$timeLabel]['Abnd Within Threshold']+=$dt[0][0]['AbndWithinThresold'];
		$data[$company_name][$dateLabel][$timeLabel]['Abnd After Threshold']+=$dt[0][0]['AbndAfterThresold'];
		$data[$company_name][$dateLabel][$timeLabel]['Ababdoned (%)']+=round($dt[0][0]['Abandon']*100/$total);
		//$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
                
                //$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']/($total-$dt[0][0]['AbndWithinThresold'])*100);
                
                $data[$company_name][$dateLabel][$timeLabel]['SL% (20 Sec)']=round($dt[0][0]['WIthinSLA']*100/$data[$company_name][$dateLabel][$timeLabel]['Total Calls Landed'])."%";
                
		$data[$company_name][$dateLabel][$timeLabel]['AL%']=round($dt[0][0]['Answered']/$data[$company_name][$dateLabel][$timeLabel]['Total Calls Landed']*100)."%";
        
		//$data['Agent Logged In'][$dateLabel][$timeLabel]=$usrDt['UserLoggedIn'];
		
		
		$TotalCall[$company_name][$dateLabel]+=$total;
                           
                        }
                
               //print_r($data); exit;  
                    
                    
                   // $timeLabel=date("H:i",strtotime($start_time_start))." To ".date("H:i",strtotime($start_time_end));
                
		$FromDate1=$NextDate;
	}
	
           
        
        }
         $company_name_arr = array_unique($company_name_arr);
         
         
         //print_r($company_name_arr); exit;
         
        foreach($company_name_arr as $comp)
        {
            foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
                    $data[$comp][$dateLabel][$timeLabel]['Offered %']=round($data[$comp][$dateLabel][$timeLabel]['Total Calls Landed']*100/$TotalCall[$company_name][$dateLabel]);  
            } }
        }
         
        
        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
          $this->set('company_name_arr',$company_name_arr); 
        }
        
       // $this->export_excel($data,$datetimeArray); 
        }
    
}
?>