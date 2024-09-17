<?php
class AbandonReportsController extends AppController
{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses = array('RegistrationMaster','CallRecord','vicidialCloserLog','vicidialUserLog',
        'CroneJob','AbandCallMaster','vicidialMaster','vicidialAgentLog','Roaster','AgentMaster');
    
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
    
    public  function sec_convert($sec,$precision)
	{
	$sec = round($sec,0);

	if ($sec < 1)
		{
		if ($precision == 'HF' || $precision == 'H')
			{return "0:00:00";}
		elseif ($precision == 'S')
			{return "0";}
		else
			{return "0:00";}
		}

	if ($precision == 'HF')
		{$precision='H';}
	else
		{
		# if ( ($sec < 3600) and ($precision != 'S') ) {$precision='M';}
		}

	if ($precision == 'H')
		{
		$hours = floor($sec / 3600);
		$hours_sec = $hours * 3600;
		$seconds = $sec - $hours_sec;
		$minutes = floor($seconds / 60);
		$seconds -= ($minutes * 60);
		$Ftime = sprintf('%s:%s:%s',
			$hours,
			str_pad($minutes,2,'0',STR_PAD_LEFT),
			str_pad($seconds,2,'0',STR_PAD_LEFT)
		);
		}
	elseif ($precision == 'M')
		{
		$minutes = floor($sec / 60);
		$seconds = $sec - ($minutes * 60);
		$Ftime = sprintf('%s:%s',
			$minutes,
			str_pad($seconds,2,'0',STR_PAD_LEFT)
		);
		}
	else 
		{
		$Ftime = $sec;
		}
	return "$Ftime";
	}
    
    public function user_stats()
    {
        $this->layout='user';
        $ag_master = $this->AgentMaster->find('list',array('fields'=>array('username','displayname'),'conditions'=>"status='A'",'order'=>"displayname"));
        $this->set('agent',array('All'=>'All')+$ag_master);
        
        if($this->request->is("POST")){
            #print_r($this->request->data);exit;
            $user = $this->request->data['AbandonReports']['agent'];
            $begin_date = $from_date =$this->request->data['AbandonReports']['startdate'];
            $end_date = $to_date =$this->request->data['AbandonReports']['enddate'];
            //$cat = $this->request->data['AbandonReports']['category'];
            
            //$begin_date = implode("-",array_reverse(explode("-",$from_date)));
            //$end_date = implode("-",array_reverse(explode("-",$to_date)));
            $user_qry = "";
            if(!empty($user) && $user!='All' )
            {
                $user_qry = " and username='$user'";
            }
        
            
            $sel_user = "SELECT username,displayname,category,processname FROM `agent_master` ag WHERE `status`='A' $user_qry ";
            $user_arr = $this->AgentMaster->query($sel_user);
            $login_arr = array();
            
            $max_count = 0;
            foreach($user_arr as $user_det)
            { 
                $user = $user_det['ag']['username'];
                $total_rows = 0;
                $event_start_seconds='';
                $event_stop_seconds='';
                $event_hours_minutes = '';
                $stmt="SELECT event,event_epoch,event_date,campaign_id,user_group,session_id,server_ip,extension,computer_ip,phone_login,phone_ip from vicidial_user_log vc_ulog where user='$user' and event_date >= '$begin_date 0:00:01'  and event_date <= '$end_date 23:59:59' order by event_date;";
                $this->vicidialCloserLog->useDbConfig = 'db2';
                $record_arr = $this->vicidialCloserLog->query($stmt);
                $total_login_time = 0;
                foreach($record_arr as $rec)
                { 
                    #print_r($rec);
                    if($rec['vc_ulog']['phone_ip']=='LOOKUP')
                    {
                        $rec['vc_ulog']['phone_ip'] = '';
                    }
                    
                    if ($event_start_seconds)
                    {
                        $event_stop_seconds = $rec['vc_ulog']['event_epoch'];
                        $event_seconds = ($event_stop_seconds - $event_start_seconds);
                        $total_login_time = ($total_login_time + $event_seconds);
                        $event_hours_minutes =		$this->sec_convert($event_seconds,'H'); 
                        $event_start_seconds='';
                        $event_stop_seconds='';
                    }
                    $new_rec = array();
                    $new_rec['event'] = $rec['vc_ulog']['event'];
                    $new_rec['event_date'] = $rec['vc_ulog']['event_date'];
                    $new_rec['campaign_id'] = $rec['vc_ulog']['campaign_id'];
                    //$new_rec['vc_ulog'] = $rec['vc_ulog']['vc_ulog'];
                    $new_rec['user_group'] = $rec['vc_ulog']['user_group'];
                    if (preg_match('/LOGIN/', $rec['vc_ulog']['event']))
                    {
                        $event_start_seconds = $rec['vc_ulog']['event_epoch'];
                        $new_rec['session_id'] = $rec['vc_ulog']['session_id'];
                    }
                    if (preg_match('/LOGOUT/', $rec['vc_ulog']['event']))
                    {
                        $new_rec['session_id'] = $event_hours_minutes;
                    }
                    $new_rec['server_ip'] = $rec['vc_ulog']['server_ip'];
                    $new_rec['extension'] = $rec['vc_ulog']['extension'];
                    $new_rec['computer_ip'] = $rec['vc_ulog']['computer_ip'];
                    $new_rec['phone_login'] = $rec['vc_ulog']['phone_login'];
                    $new_rec['phone_ip'] = $rec['vc_ulog']['phone_ip'];
                    $login_arr[$user][] = $new_rec;
                    $total_rows+=1;
                }
                if($total_rows>$max_count)
                {
                    $max_count = $total_rows;
                }
                $total[$user] = $this->sec_convert($total_login_time,'H'); 
            }    
            
            
            $html ='<table border="1">';
            $html .= '<tr>';
            $html .='<th>Agent</th>'; 
            $html .='<th>EVENT</th>'; 
            $html .='<th>DATE</th>'; 
            $html .='<th>CAMPAIGN</th>'; 
            $html .='<th>GROUP</th>'; 
            $html .='<th>SESSION HOURS:MM:SS</th>'; 

            $html .='<th>SERVER</th>'; 
            $html .='<th>PHONE</th>'; 
            $html .='<th>COMPUTER</th>'; 
            $html .='<th>PHONE LOGIN</th>'; 
            $html .='<th>PHONE IP</th>';
            
            $html .= '</tr>';
            /*foreach($user_arr as $user_det)
            {
                //$html .='<th colspan="2">'.$user_det['ag']['username']."</th>";
                //$html .='<th colspan="2">'.$user_det['ag']['displayname']."</th>";
                //$html .='<th colspan="2">'.$user_det['ag']['category']."</th>";
                //$html .='<th colspan="4">'.$user_det['ag']['processname']."</th>";
                $html .= '<tr>';
                $html .='<th>'. "{$user_det['ag']['displayname']} (".$user_det['ag']['username'].')</th>';
                $html .= '</tr>';
            }*/
            
           
            
            
            //print_r($login_arr);exit;
             
                foreach($user_arr as $user_det)
                {
                    $user = $user_det['ag']['username'];
                    $is_record = false;
                    foreach($login_arr[$user] as $rec)
                    {
                        $html .='<tr>';

                        //$rec = $login_arr[$user][$i];
                        $html .='<td>'. "{$user_det['ag']['displayname']} (".$user.')</td>';
                        $html .="<td>{$rec['event']}</td>"; 
                        //$html .="<th>{$rec['event_epoch']}</th>"; 
                        $html .="<td>{$rec['event_date']}</td>"; 
                        $html .="<td>{$rec['campaign_id']}</td>"; 
                        //$html .="<th>{$rec['vc_ulog']}</th>"; 
                        $html .="<td>{$rec['user_group']}</td>"; 
                        $html .="<td>{$rec['session_id']}</td>"; 
                        $html .="<td>{$rec['server_ip']}</td>"; 
                        $html .="<td>{$rec['extension']}</td>"; 
                        $html .="<td>{$rec['computer_ip']}</td>"; 
                        $html .="<td>{$rec['phone_login']}</td>"; 
                        $html .="<td>{$rec['phone_ip']}</td>"; 
                        $html .='</tr>';
                        $is_record = true;
                    }
                    if($is_record)
                    {
                        $html .='<tr>';
                        $html .='<td>'. "{$user_det['ag']['displayname']} (".$user.')</td>';
                        $html .="<th colspan=\"1\">Total</th>";
                        $html .="<th></th>";
                        $html .="<th></th>";
                        $html .="<th></th>";
                        $html .="<td>{$total[$user]}</td>";
                        $html .="<th></th>";
                        $html .="<th></th>";
                        $html .="<th></th>";
                        $html .="<th></th>";
                        $html .="<th></th>";
                        $html .='</tr>'; 
                    }
                }
               
            
            
             
             
            
           
            
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=user_status.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
           
            $html.='</table>';
            echo $html;exit;
        }
    }

    public function user_stats1()
    {
        $this->layout='user';
        $ag_master = $this->AgentMaster->find('list',array('fields'=>array('username','displayname'),'conditions'=>"status='A'",'order'=>"displayname"));
        $this->set('agent',array('All'=>'All')+$ag_master);
        
        if($this->request->is("POST")){
            #print_r($this->request->data);exit;
            $user = $this->request->data['AbandonReports']['agent'];
            $begin_date = $from_date =$this->request->data['AbandonReports']['startdate'];
            $end_date = $to_date =$this->request->data['AbandonReports']['enddate'];
            //$cat = $this->request->data['AbandonReports']['category'];
            
            //$begin_date = implode("-",array_reverse(explode("-",$from_date)));
            //$end_date = implode("-",array_reverse(explode("-",$to_date)));
            $user_qry = "";
            if(!empty($user) && $user!='All' )
            {
                $user_qry = " and username='$user'";
            }
        
            
            $sel_user = "SELECT username,displayname,category,processname FROM `agent_master` ag WHERE `status`='A' $user_qry ";
            $user_arr = $this->AgentMaster->query($sel_user);
            $login_arr = array();
            
            $max_count = 0;
            foreach($user_arr as $user_det)
            { 
                $user = $user_det['ag']['username'];
                $total_rows = 0;
                $event_start_seconds='';
                $event_stop_seconds='';
                $event_hours_minutes = '';
                $stmt="SELECT event,event_epoch,event_date,campaign_id,user_group,session_id,server_ip,extension,computer_ip,phone_login,phone_ip from vicidial_user_log vc_ulog where user='$user' and event_date >= '$begin_date 0:00:01'  and event_date <= '$end_date 23:59:59' order by event_date;";
                $this->vicidialCloserLog->useDbConfig = 'db2';
                $record_arr = $this->vicidialCloserLog->query($stmt);
                $total_login_time = 0;
                foreach($record_arr as $rec)
                { 
                    #print_r($rec);
                    if($rec['vc_ulog']['phone_ip']=='LOOKUP')
                    {
                        $rec['vc_ulog']['phone_ip'] = '';
                    }
                    
                    if($event_start_seconds)
                    {
                        $event_stop_seconds = $rec['vc_ulog']['event_epoch'];
                        $event_seconds = ($event_stop_seconds - $event_start_seconds);
                        $total_login_time = ($total_login_time + $event_seconds);
                        $event_hours_minutes =		$this->sec_convert($event_seconds,'H'); 
                        $event_start_seconds='';
                        $event_stop_seconds='';
                    }
                    $new_rec = array();
                    $new_rec['event'] = $rec['vc_ulog']['event'];
                    $new_rec['event_date'] = $rec['vc_ulog']['event_date'];
                    $new_rec['campaign_id'] = $rec['vc_ulog']['campaign_id'];
                    //$new_rec['vc_ulog'] = $rec['vc_ulog']['vc_ulog'];
                    $new_rec['user_group'] = $rec['vc_ulog']['user_group'];
                    if (preg_match('/LOGIN/', $rec['vc_ulog']['event']))
                    {
                        $event_start_seconds = $rec['vc_ulog']['event_epoch'];
                        $new_rec['session_id'] = $rec['vc_ulog']['session_id'];
                    }
                    if (preg_match('/LOGOUT/', $rec['vc_ulog']['event']))
                    {
                        $new_rec['session_id'] = $event_hours_minutes;
                    }
                    $new_rec['server_ip'] = $rec['vc_ulog']['server_ip'];
                    $new_rec['extension'] = $rec['vc_ulog']['extension'];
                    $new_rec['computer_ip'] = $rec['vc_ulog']['computer_ip'];
                    $new_rec['phone_login'] = $rec['vc_ulog']['phone_login'];
                    $new_rec['phone_ip'] = $rec['vc_ulog']['phone_ip'];
                    $login_arr[$user][] = $new_rec;
                    $total_rows+=1;
                }
                if($total_rows>$max_count)
                {
                    $max_count = $total_rows;
                }
                $total[$user] = $this->sec_convert($total_login_time,'H'); 
            }    
            
            
            $html ='<table border="2">';
            $html .='<tr>';
            $html .='<th>Agent</th>';
            $html .='</tr>';

            foreach($user_arr as $user_det)
            {

                $html .='<tr><td>'. "{$user_det['ag']['displayname']} (".$user_det['ag']['username'].')</td>';
        
                $html .='<td>EVENT</td>'; 
                $html .='<td>DATE</td>'; 
                $html .='<td>CAMPAIGN</td>'; 
                $html .='<td>GROUP</td>'; 
                $html .='<td>SESSION HOURS:MM:SS</td>'; 
                
                $html .='<td>SERVER</td>'; 
                $html .='<td>PHONE</td>'; 
                $html .='<td>COMPUTER</td>'; 
                $html .='<td>PHONE LOGIN</td>'; 
                $html .='<td>PHONE IP</td>';
                
                
                $html .='</tr>';
                
            }
       
            // $html .='<tr>';
            // foreach($user_arr as $user_det)
            // { 
            //    $html .='<th>EVENT</th>'; 
            //    $html .='<th>DATE</th>'; 
            //    $html .='<th>CAMPAIGN</th>'; 
            //    $html .='<th>GROUP</th>'; 
            //    $html .='<th>SESSION HOURS:MM:SS</th>'; 
                
            //    $html .='<th>SERVER</th>'; 
            //    $html .='<th>PHONE</th>'; 
            //    $html .='<th>COMPUTER</th>'; 
            //    $html .='<th>PHONE LOGIN</th>'; 
            //    $html .='<th>PHONE IP</th>'; 
             
            // }
            
            // $html .='</tr>';
            
            
            //print_r($login_arr);exit;
            // for($i=0;$i<=$max_count;$i++)
            // { 
            //     $html .='<tr>';
                
                
            //     foreach($user_arr as $user_det)
            //     {
            //         $user = $user_det['ag']['username'];
            //         $rec = $login_arr[$user][$i];
                    
            //         $html .="<td>{$rec['event']}</td>"; 
            //         //$html .="<th>{$rec['event_epoch']}</th>"; 
            //         $html .="<td>{$rec['event_date']}</td>"; 
            //         $html .="<td>{$rec['campaign_id']}</td>"; 
            //         //$html .="<th>{$rec['vc_ulog']}</th>"; 
            //         $html .="<td>{$rec['user_group']}</td>"; 
            //         $html .="<td>{$rec['session_id']}</td>"; 
            //         $html .="<td>{$rec['server_ip']}</td>"; 
            //         $html .="<td>{$rec['extension']}</td>"; 
            //         $html .="<td>{$rec['computer_ip']}</td>"; 
            //         $html .="<td>{$rec['phone_login']}</td>"; 
            //         $html .="<td>{$rec['phone_ip']}</td>"; 
                    
            //     }
            //    $html .='</tr>'; 
            // } 
            // $html .='<tr>'; 
             
            // foreach($user_arr as $user_det)
            //     {
            //         $user = $user_det['ag']['username'];
                    
            //         $html .="<th colspan=\"1\">Total</th>";
            //         $html .="<th></th>";
            //         $html .="<th></th>";
            //         $html .="<th>{$total[$user]}</th>";
            //         $html .="<th></th>";
            //         $html .="<th></th>";
            //         $html .="<th></th>";
            //         $html .="<th></th>";
            //         $html .="<th></th>";
                
            //     }
            // $html .='</tr>';
            
                // header("Content-Type: application/vnd.ms-excel; name='excel'");
                // header("Content-type: application/octet-stream");
                // header("Content-Disposition: attachment; filename=user_status_new.xls");
                // header("Pragma: no-cache");
                // header("Expires: 0");
           
            $html.='</table>';
            echo $html;exit;
        }
    }
    
    public function index() {
        $this->layout='user';
        if($this->request->is("POST")){
            
            //$campaignId ="campaign_id in(". $this->Session->read('campaignid').")";
            $clientId   =   $this->Session->read('companyid');
           $search=$this->request->data['AbandonReports'];
           //print_r($search);die;
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));
            
            // $qry = "SELECT *,DATE_FORMAT(start_time,'%d-%b-%y') dater,DATE_FORMAT(start_time,'%d-%b-%y %H:%i:%s') start_date,
            // DATE_FORMAT(end_time,'%d-%b-%y %H:%i:%s') end_date FROM `ivr_log` il  WHERE client_id='$clientId' and DATE(start_time) BETWEEN '$start_time' AND '$end_time'";

           $qry = "SELECT DATE(CallDate) DATE,getClientName(ClientId) CLIENT,COUNT(1) Abandondata,
            SUM(IF(TagStatus IS NULL,0,1)) Callback,IF(CallType IS NULL,'Abandon',CallType) CallTypes,
            SUM(IF(TIME(entrydate) BETWEEN '20:00:00' AND '23:59:59' AND TagStatus IS NULL,1,0)) Afterhr,
            SUM(IF(TIME(entrydate) BETWEEN '00:00:00' AND '20:00:00' AND TagStatus IS NULL,1,0)) SkillIssue FROM aband_call_master 
            WHERE DATE(Calldate) BETWEEN '$start_time' AND '$end_time' GROUP BY DATE(Calldate),ClientId,CallType";
            $data=$this->AbandCallMaster->query($qry);
            
            //print_r($data);exit;
         

	
        $this->set('data',$data);
         
        }
    }
    
    
    public function export_abandon_log()
    {
        if($this->request->is("POST")){
            
            
            
            
                
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=abandon_log_report.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
           
            
            $search=$this->request->data['AbandonReports'];
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));
            
            $clientId       =   $this->Session->read('companyid');
            $qry = "SELECT DATE(CallDate) DATE,getClientName(ClientId) CLIENT,COUNT(1) Abandondata,
            SUM(IF(TagStatus IS NULL,0,1)) Callback,IF(CallType IS NULL,'Abandon',CallType) CallTypes,
            SUM(IF(TIME(entrydate) BETWEEN '20:00:00' AND '23:59:59' AND TagStatus IS NULL,1,0)) Afterhr,
            SUM(IF(TIME(entrydate) BETWEEN '00:00:00' AND '20:00:00' AND TagStatus IS NULL,1,0)) SkillIssue FROM aband_call_master 
            WHERE DATE(Calldate) BETWEEN '$start_time' AND '$end_time' GROUP BY DATE(Calldate),ClientId,CallType";
            
            $data=$this->AbandCallMaster->query($qry);
            
            
            ?>
        
            <table cellspacing="0" border="1">
            <tr>
		<th>Date</th>			
		<th>Client</th>
                <th>Abandon Data</th>
                <th>Callback</th>
                <th>Calltypes</th>
                <th>Afterhr</th>
                <th>Skillissue</th>
                <!-- <th>Options chosen</th> -->
	</tr>
                <?php foreach($data as $record) { ?>
	<tr>
    <td><?php echo $record['0']['DATE']; ?></td>
		<td><?php echo $record['0']['CLIENT']; ?></td>
                <td><?php echo $record['0']['Abandondata']; ?></td>
                <td><?php echo $record['0']['Callback']; ?></td>
                <td><?php echo $record['0']['CallTypes']; ?></td>
                <td><?php echo $record['0']['Afterhr']; ?></td>
                <td><?php echo $record['0']['SkillIssue']; ?></td>
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

    public function skills(){
        $this->layout='user';

    }

    public function aband(){
        $this->layout='user';
    }

    public function view() {
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
        if($this->request->is("POST")){
            
            //$campaignId ="campaign_id in(". $this->Session->read('campaignid').")";
            $clientId   =   $this->Session->read('companyid');
           $search=$this->request->data['AbandonReports'];
           //print_r($search);die;
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));
            
            $qry = "select vl.user,full_name,date(event_time) calldate,count(1) calls,sec_to_time(sum(talk_sec-dead_sec)) talktime,campaign_id from 
            asterisk.vicidial_agent_log vl left join asterisk.vicidial_users v on vl.user=v.user
            where date(event_time) between '$start_time' and '$end_time' and lead_id!='' group by date(event_time),user";
            
           $this->vicidialAgentLog->useDbConfig = 'db2';
		   $data=$this->vicidialAgentLog->query($qry);
          print_r($data);die;

	
        $this->set('data',$data);
         
        }
    }




///////////////////   Krishna ////////////////////////////////////////////////////////////////////////

    public function slot_wise()
    {
        $this->layout='user';
         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
       if($this->request->is("POST")){
              
       $search     =$this->request->data['AbandonReports'];
      
           $FromDate=$search['startdate'];
           $ToDate=$search['enddate'];
           $clientId    =  $search['clientID'];
           $category = $search['category'];
           $category_qry = "";
            if(!empty($category) && $category!='All')
            {
                $category_qry = " and client_category='$category'";
            }
           
            if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1' $category_qry"); 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId' $category_qry"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
          
       if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
   if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
   while(strtotime($FromDate)<strtotime($ToDate))
   {
       
       $date_array[] =$date_fetch= date("Y-m-d",strtotime("$FromDate"));

        $start_time_start=$FromDate;
        $event_date_start=date("Y-m-d 00:00:00",strtotime("$FromDate +1 hours"));
        $timeArray[] =  $time_fetch=date("H",strtotime("$FromDate"));
        $NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 hours"));
        $FromDate=$NextDate;
        
        $start_time_end=$NextDate;

               
$qry="SELECT
SUM(IF(t2.user!='VDCL',1,0)) `Answered`,
IF(t2.user!='VDCL',count(DISTINCT(t2.user))-1,count(DISTINCT(t2.user))) `Manpower`,
(SUM(t1.talk_sec) + SUM(t1.dispo_sec) + SUM(IFNULL(t3.p,0)))/ (SUM(t1.talk_sec) + SUM(t1.wait_sec) + SUM(t1.dispo_sec) + SUM(IFNULL(t3.p,0)))*100 `Utilization`
FROM asterisk.vicidial_closer_log t2
LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid  AND t1.lead_id!='' AND t2.user=t1.user
LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end' and $campaignId"; 

       $this->vicidialCloserLog->useDbConfig = 'db2';
        $dt=$this->vicidialCloserLog->query($qry);
                //print_r($dt);die; 
        
        $timeLabel=$time_fetch;
        $dateLabel=$date_fetch;
        $datetimeArray[$dateLabel][]=$timeLabel;
        
        //$data[$dateLabel][$timeLabel]['Total'] = $dt[0][0]['Total'];
        $data[$dateLabel][$timeLabel]['Answered'] = $dt[0][0]['Answered'];
        $data[$dateLabel][$timeLabel]['Manpower'] = $dt[0][0]['Manpower'];
        $data[$dateLabel][$timeLabel]['Utilization %'] = round($dt[0][0]['Utilization'],2);
       
    }
     //print_r($data);exit;
        $date_array = array_unique($date_array);
        $timeArray=array_unique($timeArray);

        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
         $this->set('datearray', $date_array);
         $this->set('timearray', $timeArray);
       }
   }

    public function slot_wise_excel()
    {
        $this->layout='user';
         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
       if($this->request->is("POST")){
              
       $search     =$this->request->data['AbandonReports'];
      
           $FromDate=$search['startdate'];
           $ToDate=$search['enddate'];
           $clientId    =  $search['clientID'];
           $category = $search['category'];
           $category_qry = "";
            if(!empty($category) && $category!='All')
            {
                $category_qry = " and client_category='$category'";
            }
           
           
            if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1' $category_qry"); 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId' $category_qry"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
          
       if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
   if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
   while(strtotime($FromDate)<strtotime($ToDate))
   {
       
       $date_array[] =$date_fetch= date("Y-m-d",strtotime("$FromDate"));

        $start_time_start=$FromDate;
        $event_date_start=date("Y-m-d 00:00:00",strtotime("$FromDate +1 hours"));
        $timeArray[] =  $time_fetch=date("H",strtotime("$FromDate"));
        $NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 hours"));
        $FromDate=$NextDate;
        
        $start_time_end=$NextDate;

               
$qry="SELECT
SUM(IF(t2.user!='VDCL',1,0)) `Answered`,
IF(t2.user!='VDCL',count(DISTINCT(t2.user))-1,count(DISTINCT(t2.user))) `Manpower`,
(SUM(t1.talk_sec) + SUM(t1.dispo_sec) + SUM(IFNULL(t3.p,0)))/ (SUM(t1.talk_sec) + SUM(t1.wait_sec) + SUM(t1.dispo_sec) + SUM(IFNULL(t3.p,0)))*100 `Utilization`
FROM asterisk.vicidial_closer_log t2
LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid  AND t1.lead_id!='' AND t2.user=t1.user
LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end' and $campaignId";

       $this->vicidialCloserLog->useDbConfig = 'db2';
        $dt=$this->vicidialCloserLog->query($qry);
                //print_r($dt);die; 
        
        $timeLabel=$time_fetch;
        $dateLabel=$date_fetch;
        $datetimeArray[$dateLabel][]=$timeLabel;
        
        //$data[$dateLabel][$timeLabel]['Total'] = $dt[0][0]['Total'];
        $data[$dateLabel][$timeLabel]['Answered'] = $dt[0][0]['Answered'];
        $data[$dateLabel][$timeLabel]['Manpower'] = $dt[0][0]['Manpower'];
        $data[$dateLabel][$timeLabel]['Utilization %'] = round($dt[0][0]['Utilization'],2);
       
    }
     //print_r($data);exit;
        $date_array = array_unique($date_array);
        $timeArray=array_unique($timeArray);

        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
         $this->set('datearray', $date_array);
         $this->set('timearray', $timeArray);
       }
   }



    public function agent_wise()
    {
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
        if($this->request->is("POST")){
              
           $search     =$this->request->data['AbandonReports'];
      
           $FromDate=$search['startdate'];
           $ToDate=$search['enddate'];
           $clientId    =  $search['clientID'];
           $category = $search['category'];
           $time_format = $this->request->data['time_format'];
           $category_qry = "";
            if(!empty($category) && $category!='All')
            {
                $category_qry = " and client_category='$category'";
            }
           
            if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1' $category_qry"); 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId' $category_qry"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }

        
          
        if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
        if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
        while(strtotime($FromDate)<strtotime($ToDate))
        {
       
            $date_array[] =$date_fetch= date("Y-m-d",strtotime("$FromDate"));

            $start_time_start=$FromDate;
            $event_date_start=date("Y-m-d 00:00:00",strtotime("$FromDate +24 hours"));
            $timeArray[] =  $time_fetch=date("H",strtotime("$FromDate"));
            $NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +24 hours"));
            $FromDate=$NextDate;
            
            $start_time_end=$NextDate;

               
            $qry="select t1.user,sum(if(t1.lead_id!='',1,0)) Answered,SUM(t1.talk_sec) `talktime`,SUM(t1.talk_sec) talk_sec , SUM(t1.dispo_sec) dispo_sec, SUM(if(t1.wait_sec>10000,0,wait_sec)) wait_sec, SUM(if(t1.pause_sec>10000,0,pause_sec)) pause_sec
            from asterisk.vicidial_agent_log t1 left join (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND
            parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
            WHERE t1.user!='VDCL' and t1.event_time>='$start_time_start' AND t1.event_time<'$start_time_end' group by t1.user"; 

            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dtr=$this->vicidialCloserLog->query($qry);
                //var_dump($dtr); exit;
            foreach($dtr as $dt)
            {
                    //echo "SELECT * FROM agent_master where status='A' and username='".$dt['t2']['user']."'";
                $Userinfo = $this->RegistrationMaster->query("SELECT * FROM agent_master where status='A' and username='".$dt['t1']['user']."'"); 

                    //print_r($Userinfo);
                $Name[$dt['t1']['user']] =  $Userinfo['0']['agent_master']['displayname'];
                $Doj[$dt['t1']['user']] =  $Userinfo['0']['agent_master']['dateofjoining'];	

                $timeLabel=$dt['t1']['user'];
                $dateLabel=$date_fetch;
                $datetimeArray[$dateLabel][]=$timeLabel;
                $timeArray2[] = $timeLabel;
        
                $A=$dt[0]['talktime'];
                $B=$dt[0]['talk_sec']+$dt[0]['dispo_sec']+$dt[0]['wait_sec']+$dt[0]['pause_sec'];

                // sec convert to minutes
                if ($time_format == 'min') {
                    $A = round($A / 60, 2);
                    $B = round($B / 60, 2);
                }
            
                $data2[$dateLabel][$timeLabel]['Utilization %'] = round($A/$B*100,2);
                $data2[$dateLabel][$timeLabel]['Productive'] = $A;
                $data2[$dateLabel][$timeLabel]['Login Time'] = $B;  
            } //die;
        }
    //print_r($Name); exit;
     //print_r($data2);exit;
        $date_array = array_unique($date_array);
        $timeArray=array_unique($timeArray2);

         $this->set('data',$data2);
         $this->set('datetimeArray',$datetimeArray);
         $this->set('datearray', $date_array);
         $this->set('timearray', $timeArray);
         $this->set('name', $Name);
         $this->set('doj', $Doj);
         $this->set('companyid', $clientId);
         $this->set('time_format', $time_format);
         
       }
   }

    public function agent_wise_excel()
    {
        $this->layout='user';
         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
       if($this->request->is("POST")){
              
       $search     =$this->request->data['AbandonReports'];
      
           $FromDate=$search['startdate'];
           $ToDate=$search['enddate'];
           $clientId    =  $search['clientID'];
           $category = $search['category'];
           $time_format = $this->request->data['time_format'];
           $category_qry = "";
            if(!empty($category) && $category!='All')
            {
                $category_qry = " and client_category='$category'";
            }
            if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1' $category_qry"); 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId' $category_qry"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
          
       if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
   if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
     while(strtotime($FromDate)<strtotime($ToDate))
   {
       
       $date_array[] =$date_fetch= date("Y-m-d",strtotime("$FromDate"));

        $start_time_start=$FromDate;
        $event_date_start=date("Y-m-d 00:00:00",strtotime("$FromDate +24 hours"));
        $timeArray[] =  $time_fetch=date("H",strtotime("$FromDate"));
        $NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +24 hours"));
        $FromDate=$NextDate;
        
        $start_time_end=$NextDate;

               
        $qry="select t1.user,sum(if(t1.lead_id!='',1,0)) Answered,SUM(t1.talk_sec) `talktime`,SUM(t1.talk_sec) talk_sec , SUM(t1.dispo_sec) dispo_sec, SUM(if(t1.wait_sec>10000,0,wait_sec)) wait_sec, SUM(if(t1.pause_sec>10000,0,pause_sec)) pause_sec
        from asterisk.vicidial_agent_log t1 left join (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND
        parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
        WHERE t1.user!='VDCL' and t1.event_time>='$start_time_start' AND t1.event_time<'$start_time_end'  group by t1.user"; 
       
              $this->vicidialCloserLog->useDbConfig = 'db2';
               $dtr=$this->vicidialCloserLog->query($qry);
                       //var_dump($dtr); exit;
               foreach($dtr as $dt)
               {
                   //echo "SELECT * FROM agent_master where status='A' and username='".$dt['t2']['user']."'";
               $Userinfo = $this->RegistrationMaster->query("SELECT * FROM agent_master where status='A' and username='".$dt['t1']['user']."'"); 
       
               //print_r($Userinfo);
                  $Name[$dt['t1']['user']] =  $Userinfo['0']['agent_master']['displayname'];
                  $Doj[$dt['t1']['user']] =  $Userinfo['0']['agent_master']['dateofjoining'];	
       
               $timeLabel=$dt['t1']['user'];
               $dateLabel=$date_fetch;
               $datetimeArray[$dateLabel][]=$timeLabel;
               $timeArray2[] = $timeLabel;
               $park = "select user, count(*), sum(parked_sec) p From park_log where parked_time < '$start_time_end' and parked_time >= '$start_time_start' and channel_group in ('Dialdesk') and user='".$dt['t1']['user']."' group by user";
               $this->vicidialCloserLog->useDbConfig = 'db2';
               $park_time=$this->vicidialCloserLog->query($park);
               //print_r($park_time[0][0]['p']); 
               //$data[$dateLabel][$timeLabel]['Total'] = $dt[0][0]['Total'];
               
               $A=$dt[0]['talk_sec'];
               //echo "<br>";
               //echo $dt['t1']['user']." T "
                $B=$dt[0]['talk_sec']+$dt[0]['dispo_sec']+$dt[0]['wait_sec']+$dt[0]['pause_sec'];
                //echo "<br>";
                // sec convert to minutes
                if ($time_format == 'min') {
                    $A = round($A / 60, 2);
                    $B = round($B / 60, 2);
                }


                $data2[$dateLabel][$timeLabel]['Total Call'] = $dt[0]['Answered'];
                $data2[$dateLabel][$timeLabel]['Talk Time'] = $A;
                $data2[$dateLabel][$timeLabel]['Utilization %'] = round($A / $B * 100, 2);
                $data2[$dateLabel][$timeLabel]['Productive'] = $A;
                $data2[$dateLabel][$timeLabel]['Login Time'] = $B;
              } //die;
           }
           //print_r($Name); exit;
            //print_r($data2);exit;
               $date_array = array_unique($date_array);
               $timeArray=array_unique($timeArray2);
       
               $this->set('data',$data2);
                $this->set('datetimeArray',$datetimeArray);
                $this->set('datearray', $date_array);
                $this->set('timearray', $timeArray);
                $this->set('name', $Name);
                $this->set('doj', $Doj);
              }
   }




    public function customer_wise()
    {
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
        if($this->request->is("POST"))
        {
              
            $search     =$this->request->data['AbandonReports'];
      
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];
            $clientId    =  $search['clientID'];
            $rType    =  $search['rType'];
            $category = $search['category'];
            $fetch_type = $search['fetch_type'];
            
            $category_qry = "";
            if(!empty($category) && $category!='All')
            {
                $category_qry = " and client_category='$category'";
            }
            if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
                
                $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1' $category_qry"); 
                
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

                $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId' $category_qry"));

                $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
          
            if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
            if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
            while(strtotime($FromDate)<strtotime($ToDate))
            {
                
                $date_fetch= date("Y-m-d",strtotime("$FromDate"));

                $start_time_start=$FromDate;
                $event_date_start=date("Y-m-d 00:00:00",strtotime("$FromDate +1 hours"));
                $timeArray[] =  $time_fetch=date("H",strtotime("$FromDate"));
                $NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 hours"));
                $FromDate=$NextDate;
                
                $start_time_end=$NextDate;
                    
                $qry="SELECT count(*) Offer , t2.campaign_id,SUM(IF(t2.user!='VDCL',1,0)) `Answered`,SUM(IF(t2.user='VDCL',1,0)) `Abandon`
                FROM asterisk.vicidial_closer_log t2
                LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid  AND t1.lead_id!='' AND t2.user=t1.user
                LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
                WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end' and $campaignId group by t2.campaign_id"; 

                $this->vicidialCloserLog->useDbConfig = 'db2';
                $dtr=$this->vicidialCloserLog->query($qry);
                    //   var_dump($dt); 
                foreach($dtr as $dt)
                {
                    if ($fetch_type == 'Client Wise') {
                        // Client-wise (campaign-wise) data grouping
                        $timeLabel = $time_fetch;
                        $campaignLabel = $dt['t2']['campaign_id'];
                        $datetimeArray[$campaignLabel][] = $timeLabel;
        
                        $data2[$campaignLabel][$timeLabel]['Answered'] += $dt[0]['Answered'];
                        $data2[$campaignLabel][$timeLabel]['Abandon'] += $dt[0]['Abandon'];
                        $data2[$campaignLabel][$timeLabel]['Offer'] += $dt[0]['Offer'];
        
                        // Add unique labels to the arrays
                        $date_array[] = $campaignLabel;
                        $timeArray2[] = $timeLabel;

                      

                    } elseif ($fetch_type == 'Date Wise') {
                        // Date-wise data grouping
                        $dateLabel = $date_fetch;
                        $timeLabel = $time_fetch;
                        $datetimeArray[$dateLabel][] = $timeLabel;
        
                        $data2[$dateLabel][$timeLabel]['Answered'] += $dt[0]['Answered'];
                        $data2[$dateLabel][$timeLabel]['Abandon'] += $dt[0]['Abandon'];
                        $data2[$dateLabel][$timeLabel]['Offer'] += $dt[0]['Offer'];
        
                        // Add unique labels to the arrays
                        $date_array[] = $dateLabel;
                        $timeArray2[] = $timeLabel;
                    }
                    
                }
            }

            $date_array = array_unique($date_array);
            $timeArray=array_unique($timeArray2);

            $this->set('data',$data2);
            $this->set('datetimeArray',$datetimeArray);
            $this->set('datearray', $date_array);
            $this->set('timearray', $timeArray);
            $this->set('rType', $search);
        }
    }

    public function customer_wise_excel()
    {
        $this->layout='user';
         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
       if($this->request->is("POST")){
              
       $search     =$this->request->data['AbandonReports'];
      
           $FromDate=$search['startdate'];
           $ToDate=$search['enddate'];
           $clientId    =  $search['clientID'];
            $category = $search['category'];
            $fetch_type = $search['fetch_type'];
            
            $category_qry = "";
            if(!empty($category) && $category!='All')
            {
                $category_qry = " and client_category='$category'";
            }
            if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
                
                    $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1' $category_qry"); 
                
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId' $category_qry"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
          
       if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
   if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
     while(strtotime($FromDate)<strtotime($ToDate))
   {
       
       $date_fetch= date("Y-m-d",strtotime("$FromDate"));

        $start_time_start=$FromDate;
        $event_date_start=date("Y-m-d 00:00:00",strtotime("$FromDate +1 hours"));
        $timeArray[] =  $time_fetch=date("H",strtotime("$FromDate"));
        $NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 hours"));
        $FromDate=$NextDate;
        
        $start_time_end=$NextDate;

               
 $qry="SELECT count(*) Offer,t2.campaign_id,SUM(IF(t2.user!='VDCL',1,0)) `Answered`,SUM(IF(t2.user='VDCL',1,0)) `Abandon`
FROM asterisk.vicidial_closer_log t2
LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid  AND t1.lead_id!='' AND t2.user=t1.user
LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
WHERE  t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end' and $campaignId group by t2.campaign_id"; 

         $this->vicidialCloserLog->useDbConfig = 'db2';
        $dtr=$this->vicidialCloserLog->query($qry);
               // var_dump($dt); exit;
        foreach($dtr as $dt)
        {
            // $timeLabel=$time_fetch;
            // $dateLabel=$dt['t2']['campaign_id'];
            // $date_array[] =$dateLabel;
            // $datetimeArray[$dateLabel][]=$timeLabel;
            // $timeArray2[] = $timeLabel;
            
            // //$data[$dateLabel][$timeLabel]['Total'] = $dt[0][0]['Total'];
            // $data2[$dateLabel][$timeLabel]['Answered'] += $dt[0]['Answered'];
            // $data2[$dateLabel][$timeLabel]['Abandon'] += $dt[0]['Abandon'];
            // $data2[$dateLabel][$timeLabel]['Offer'] += $dt[0]['Offer'];
            if ($fetch_type == 'Client Wise') {
                // Client-wise (campaign-wise) data grouping
                $timeLabel = $time_fetch;
                $campaignLabel = $dt['t2']['campaign_id'];
                $datetimeArray[$campaignLabel][] = $timeLabel;

                $data2[$campaignLabel][$timeLabel]['Answered'] += $dt[0]['Answered'];
                $data2[$campaignLabel][$timeLabel]['Abandon'] += $dt[0]['Abandon'];
                $data2[$campaignLabel][$timeLabel]['Offer'] += $dt[0]['Offer'];

                // Add unique labels to the arrays
                $date_array[] = $campaignLabel;
                $timeArray2[] = $timeLabel;

              

            } elseif ($fetch_type == 'Date Wise') {
                // Date-wise data grouping
                $dateLabel = $date_fetch;
                $timeLabel = $time_fetch;
                $datetimeArray[$dateLabel][] = $timeLabel;

                $data2[$dateLabel][$timeLabel]['Answered'] += $dt[0]['Answered'];
                $data2[$dateLabel][$timeLabel]['Abandon'] += $dt[0]['Abandon'];
                $data2[$dateLabel][$timeLabel]['Offer'] += $dt[0]['Offer'];

                // Add unique labels to the arrays
                $date_array[] = $dateLabel;
                $timeArray2[] = $timeLabel;
            }
       }
    }
     //print_r($data2);exit;
        $date_array = array_unique($date_array);
        $timeArray=array_unique($timeArray2);

        $this->set('data',$data2);
         $this->set('datetimeArray',$datetimeArray);
         $this->set('datearray', $date_array);
         $this->set('timearray', $timeArray);
         $this->set('rType', $search);
       }
   }


public function customer_density_excel()
    {
        $this->layout='user';
       
       if($this->request->is("GET")){
              
       
      
           $FromDate=$_GET['startdate'];
           $ToDate=$_GET['enddate'];
           $clientId    =  $_GET['clientname'];
           
           $campaignId =   "t2.campaign_id in('".$clientId."')";
            
          
       if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
   if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
   while(strtotime($FromDate)<strtotime($ToDate))
   {
       
       $date_array[] =$date_fetch= date("Y-m-d",strtotime("$FromDate"));

        $start_time_start=$FromDate;
        $event_date_start=date("Y-m-d 00:00:00",strtotime("$FromDate +1 hours"));
        $timeArray[] =  $time_fetch=date("H",strtotime("$FromDate"));
        $NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 hours"));
        $FromDate=$NextDate;
        
        $start_time_end=$NextDate;

               
$qry="SELECT count(*) Offer,t2.campaign_id,SUM(IF(t2.user!='VDCL',1,0)) `Answered`,SUM(IF(t2.user='VDCL',1,0)) `Abandon`
FROM asterisk.vicidial_closer_log t2
LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid  AND t1.lead_id!='' AND t2.user=t1.user
LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end' and $campaignId";

       $this->vicidialCloserLog->useDbConfig = 'db2';
        $dt=$this->vicidialCloserLog->query($qry);
                //print_r($dt);die; 
        
        $timeLabel=$time_fetch;
        $dateLabel=$date_fetch;
        $datetimeArray[$dateLabel][]=$timeLabel;
        
        $data[$dateLabel][$timeLabel]['Total'] = $dt[0][0]['Offer'];
        $data[$dateLabel][$timeLabel]['Answered'] = $dt[0][0]['Answered'];
        $data[$dateLabel][$timeLabel]['Abandon'] = $dt[0][0]['Abandon'];
        //$data[$dateLabel][$timeLabel]['Utilization %'] = round($dt[0][0]['Utilization'],2);
       
    }
     ///print_r($data);exit;
        $date_array = array_unique($date_array);
        $timeArray=array_unique($timeArray);

        $this->set('data',$data);
         $this->set('datetimeArray',$datetimeArray);
         $this->set('datearray', $date_array);
         $this->set('timearray', $timeArray);
         $this->set('FromDate',$FromDate);
         $this->set('ToDate',$ToDate);
         $this->set('clientId',$clientId);
       }
   }

public function client_live_agent()
	{
		$this->layout='user';
		if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            
            $this->set('client',$client); 
        }

	    if($this->request->is("POST")){
	              
	       $search     =$this->request->data['AbandonReports'];
	       //print_r($search); die;
	       	$cntID =  implode(",",$search['clientID']); 
			$ClientInfo = $this->RegistrationMaster->query("SELECT Company_name as Company_name,campaign_name as campaign_name FROM `registration_master` r join ingroup_campaign_master i on r.company_id=i.client_id WHERE r.company_id in ($cntID) and `status`='A' and is_dd_client='1' and camp_type is null order by Company_name asc"); 
	       	

	       	foreach($ClientInfo as $v)
	       	{
	         $ClientInf = $v['r']['Company_name']; 
	         //$CampInf[$ClientInf][] = $v['i']['campaign_name']; 
				
				$qry="select count(*) cnt from vicidial_live_agents where campaign_id='Dialdesk' and closer_campaigns like '%".$v['i']['campaign_name']."%'";

		       	$this->vicidialCloserLog->useDbConfig = 'db2';
		        $dt=$this->vicidialCloserLog->query($qry);	
		        //echo $dt[0][0]['cnt']; die;
		        $CampInf[$ClientInf][$v['i']['campaign_name']] = $dt[0][0]['cnt'];     

	        }
	    } else {
	    	$ClientInfo = $this->RegistrationMaster->query("SELECT Company_name as Company_name,campaign_name as campaign_name FROM `registration_master` r join ingroup_campaign_master i on r.company_id=i.client_id WHERE `status`='A' and is_dd_client='1' and camp_type is null order by Company_name asc"); 
	       	

	       	foreach($ClientInfo as $v)
	       	{
	         $ClientInf = $v['r']['Company_name']; 
	         //$CampInf[$ClientInf][] = $v['i']['campaign_name']; 
				
				$qry="select count(*) cnt from vicidial_live_agents where campaign_id='Dialdesk' and closer_campaigns like '%".$v['i']['campaign_name']."%'";

		       	$this->vicidialCloserLog->useDbConfig = 'db2';
		        $dt=$this->vicidialCloserLog->query($qry);	
		        //echo $dt[0][0]['cnt']; die;
		        $CampInf[$ClientInf][$v['i']['campaign_name']] = $dt[0][0]['cnt'];     

	        }

	    }

       /* echo "<pre>";
        print_r($CampInf)   ;
        echo "<pre>";
         	die;
         	*/
        $this->set('data',$CampInf); 	
	}    

    public function roster_view()
	{
		$this->layout='user';
		if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            
            $this->set('client',$client); 
        }

	    if($this->request->is("POST")){
	              
	       $search     =$this->request->data['AbandonReports'];
	       //print_r($search); die;
	       	$cntID =  implode(",",$search['clientID']); 
			$ClientInfo = $this->RegistrationMaster->query("SELECT Company_name as Company_name,campaign_name as campaign_name FROM `registration_master` r join ingroup_campaign_master i on r.company_id=i.client_id WHERE r.company_id in ($cntID) and `status`='A' and is_dd_client='1' and camp_type is null order by Company_name asc"); 
	       	

	       	foreach($ClientInfo as $v)
	       	{
	         $ClientInf = $v['r']['Company_name']; 
	          $CampInf[$ClientInf][] = $v['i']['campaign_name'];  
				
				$qry="select count(*) cnt from vicidial_live_agents where campaign_id='Dialdesk' and closer_campaigns like '%".$v['i']['campaign_name']."%'";

		       	$this->vicidialCloserLog->useDbConfig = 'db2';
		        $dt=$this->vicidialCloserLog->query($qry);	
		        //echo $dt[0][0]['cnt']; die;
		        $CampInf[$ClientInf][$v['i']['campaign_name']] = $dt[0][0]['cnt'];     

	        }
	    } else {
	    	$ClientInfo = $this->RegistrationMaster->query("SELECT Company_name as Company_name,campaign_name as campaign_name FROM `registration_master` r join ingroup_campaign_master i on r.company_id=i.client_id WHERE `status`='A' and is_dd_client='1' and camp_type is null order by Company_name asc"); 
	       	$slot_arr = array(

                '00'=>array('00:00:00','00:59:59'),
                '01'=>array('01:00:00','01:59:59'),
                '02'=>array('02:00:00','02:59:59'),
                '03'=>array('03:00:00','03:59:59'),
                '04'=>array('04:00:00','04:59:59'),
                '05'=>array('05:00:00','05:59:59'),
                '06'=>array('06:00:00','06:59:59'),
                '07'=>array('07:00:01','07:59:59'),
                '08'=>array('08:00:00','08:59:59'),
                '09'=>array('09:00:00','09:59:59'),
                '10'=>array('10:00:00','10:59:59'),
                '11'=>array('11:00:00','11:59:59'),
                '12'=>array('12:00:00','12:59:59'),
                '13'=>array('13:00:00','13:59:59'),
                '14'=>array('14:00:00','14:59:59'),
                '15'=>array('15:00:00','15:59:59'),
                '16'=>array('16:00:00','16:59:59'),
                '17'=>array('17:00:00','17:59:59'),
                '18'=>array('18:00:00','18:59:59'),
                '19'=>array('19:00:00','19:59:59'),
                '20'=>array('20:00:00','20:59:59'),
                '21'=>array('21:00:00','21:59:59'),
                '22'=>array('22:00:00','22:59:59'),
                '23'=>array('23:00:00','23:59:59'),
            );
            
            $slot_arr2 = array_keys($slot_arr);
	       	foreach($ClientInfo as $v)
	       	{
	         $ClientInf = $v['r']['Company_name']; 
             $client_list[$ClientInf] =  $ClientInf;
	          $CampInf[strtolower($v['i']['campaign_name'])] = $ClientInf;  
            }
            	$rstdate="SELECT max(roasterdate) rstdate FROM roaster";
                $rstdate_dat = $this->RegistrationMaster->query($rstdate);
                //print_r($rstdate_dat);
            	$rsdate =  $rstdate_dat['0']['0']['rstdate'];
				$qry_agent="SELECT * FROM roaster rd where roasterdate='$rsdate'";
                $roster_det = $this->RegistrationMaster->query($qry_agent);
               // print_r($roster_det);
                $this->vicidialCloserLog->useDbConfig = 'db2';
                $data2 = array();
                foreach($roster_det as $roast)
                {
                    $idc_user =  $roast['rd']['Agent']; 
                    $login_time = $roast['rd']['shiftstarttime'];
                    $working_hour = $roast['rd']['working_hour'];

                    $start_time = date('Y-m-d H:i:s',strtotime("2022-12-08"." ".$login_time));
                    $end_time = date('Y-m-d H:i:s',strtotime($start_time."+$working_hour Hour"));
                    

                    $qry_vd="SELECT * FROM vicidial_users vu where user='$idc_user'";
                    $dt=$this->vicidialCloserLog->query($qry_vd);	
                    $closer_campaigns = $dt['0']['vu']['closer_campaigns'];
                    //print_r($dt);exit;
                    $campaigns_list = explode(" ",$closer_campaigns);
                    //print_r($campaigns_list);
                    foreach($campaigns_list as $campaign)
                    {
                        $start_time_new = $start_time;
                        while(strtotime($start_time_new)<=strtotime($end_time))
                        {
                            $slot = date('H',strtotime($start_time_new));
                            $campaign = strtolower($campaign);
                          //echo '<br>';
                            $client_id = $CampInf[$campaign];
                            //echo $campaign;
                            if(in_array($slot,$slot_arr2))
                            {
                                $data2[$client_id][$slot][$idc_user] = $idc_user;
                            }
                            $start_time_new = date('Y-m-d H:i:s',strtotime($start_time_new."+1 Hour"));
                          //echo $start_time."<br/>";
                        }
                        
                        
                    }

                }
		        //echo $dt[0][0]['cnt']; die;
		        //$CampInf[$ClientInf][$v['i']['campaign_name']] = $dt[0][0]['cnt'];     

	        

           // print_r($data2);exit;

	    }
//exit;
       /* echo "<pre>";
        print_r($CampInf)   ;
        echo "<pre>";
         	die;
         	*/
         	//print_r($data2); exit;
        $this->set('data2',$data2); 	
        $this->set('slot_arr2',$slot_arr2);
        $this->set('client_list',$client_list);
        
	}
    
    public function roster_view_excel()
	{
		$this->layout='user';
		if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            
            $this->set('client',$client); 
        }

	    if($this->request->is("POST")){
	              
            $ClientInfo = $this->RegistrationMaster->query("SELECT Company_name as Company_name,campaign_name as campaign_name FROM `registration_master` r join ingroup_campaign_master i on r.company_id=i.client_id WHERE `status`='A' and is_dd_client='1' and camp_type is null order by Company_name asc"); 
            $slot_arr = array(

             '00'=>array('00:00:00','00:59:59'),
             '01'=>array('01:00:00','01:59:59'),
             '02'=>array('02:00:00','02:59:59'),
             '03'=>array('03:00:00','03:59:59'),
             '04'=>array('04:00:00','04:59:59'),
             '05'=>array('05:00:00','05:59:59'),
             '06'=>array('06:00:00','06:59:59'),
             '07'=>array('07:00:01','07:59:59'),
             '08'=>array('08:00:00','08:59:59'),
             '09'=>array('09:00:00','09:59:59'),
             '10'=>array('10:00:00','10:59:59'),
             '11'=>array('11:00:00','11:59:59'),
             '12'=>array('12:00:00','12:59:59'),
             '13'=>array('13:00:00','13:59:59'),
             '14'=>array('14:00:00','14:59:59'),
             '15'=>array('15:00:00','15:59:59'),
             '16'=>array('16:00:00','16:59:59'),
             '17'=>array('17:00:00','17:59:59'),
             '18'=>array('18:00:00','18:59:59'),
             '19'=>array('19:00:00','19:59:59'),
             '20'=>array('20:00:00','20:59:59'),
             '21'=>array('21:00:00','21:59:59'),
             '22'=>array('22:00:00','22:59:59'),
             '23'=>array('23:00:00','23:59:59'),
         );
         
         $slot_arr2 = array_keys($slot_arr);
            foreach($ClientInfo as $v)
            {
          $ClientInf = $v['r']['Company_name']; 
          $client_list[$ClientInf] =  $ClientInf;
           $CampInf[strtolower($v['i']['campaign_name'])] = $ClientInf;  
         }
             $rstdate="SELECT max(roasterdate) rstdate FROM roaster";
             $rstdate_dat = $this->RegistrationMaster->query($rstdate);
             //print_r($rstdate_dat);
             $rsdate =  $rstdate_dat['0']['0']['rstdate'];
             $qry_agent="SELECT * FROM roaster rd where roasterdate='$rsdate'";
             $roster_det = $this->RegistrationMaster->query($qry_agent);
            // print_r($roster_det);
             $this->vicidialCloserLog->useDbConfig = 'db2';
             $data2 = array();
             foreach($roster_det as $roast)
             {
                 $idc_user =  $roast['rd']['Agent']; 
                 $login_time = $roast['rd']['shiftstarttime'];
                 $working_hour = $roast['rd']['working_hour'];

                 $start_time = date('Y-m-d H:i:s',strtotime("2022-12-08"." ".$login_time));
                 $end_time = date('Y-m-d H:i:s',strtotime($start_time."+$working_hour Hour"));
                 

                 $qry_vd="SELECT * FROM vicidial_users vu where user='$idc_user'";
                 $dt=$this->vicidialCloserLog->query($qry_vd);	
                 $closer_campaigns = $dt['0']['vu']['closer_campaigns'];
                 //print_r($dt);exit;
                 $campaigns_list = explode(" ",$closer_campaigns);
                 //print_r($campaigns_list);
                 foreach($campaigns_list as $campaign)
                 {
                     $start_time_new = $start_time;
                     while(strtotime($start_time_new)<=strtotime($end_time))
                     {
                         $slot = date('H',strtotime($start_time_new));
                         $campaign = strtolower($campaign);
                       //echo '<br>';
                         $client_id = $CampInf[$campaign];
                         //echo $campaign;
                         if(in_array($slot,$slot_arr2))
                         {
                             $data2[$client_id][$slot][$idc_user] = $idc_user;
                         }
                         $start_time_new = date('Y-m-d H:i:s',strtotime($start_time_new."+1 Hour"));
                       //echo $start_time."<br/>";
                     }
                     
                     
                 }

             }

            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=roster_planning.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ?><style type="text/css">
            td {
          text-align: center;
        }
        </style>
        <table cellspacing="0" border="1" >
        
        
         <thead>
         <tr><td colspan="25">Rostaer Planning as on Date</td></tr>
            <tr style="background-color:#317EAC; color:#FFFFFF;"> 
            <th>Sno.</th>             
            <th>Client Name</th> 
            <?php 
                    foreach($slot_arr2 as $sl)
                    {
                        echo '<th>'.$sl.'</th>';
                    }
            ?>
            </tr>
            
        </thead>
        <tbody>
            <?php
                $counter = 1;
             foreach($client_list as $cl) { 
                $flag = true;
        
                ?>
              <tr>
              <td ><?php echo $counter++;?></td>  
              <td ><?php echo $cl;?></td>  
                 <?php foreach($slot_arr2 as $sl) { ?>
                   
                        
                          
                        
                        <td><?php echo count($data2[$cl][$sl]);?></td>
                        
                        <?php } ?>
                  
                  </tr>               
            
            <?php } ?>
        </tbody>
        </table>
        
        
        <?php exit; 

	    }

        
	}


public function roster_view_excel_old()
	{
		$this->layout='user';
		if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            
            $this->set('client',$client); 
        }

	    if($this->request->is("POST")){
	              
	       $ClientInfo = $this->RegistrationMaster->query("SELECT Company_name as Company_name,campaign_name as campaign_name FROM `registration_master` r join ingroup_campaign_master i on r.company_id=i.client_id WHERE `status`='A' and is_dd_client='1' and camp_type is null order by Company_name asc"); 
	       	$slot_arr = array(
                '01'=>array('00:00:00','00:59:59'),
                '02'=>array('01:00:00','01:59:59'),
                '03'=>array('02:00:00','02:59:59'),
                '04'=>array('03:00:00','03:59:59'),
                '05'=>array('04:00:00','04:59:59'),
                '06'=>array('05:00:00','05:59:59'),
                '07'=>array('06:00:00','06:59:59'),
                '08'=>array('07:00:00','07:59:59'),
                '09'=>array('08:00:00','08:59:59'),
                '10'=>array('09:00:00','09:59:59'),
                '11'=>array('10:00:00','10:59:59'),
                '12'=>array('11:00:00','11:59:59'),
                '13'=>array('12:00:00','12:59:59'),
                '14'=>array('13:00:00','13:59:59'),
                '15'=>array('14:00:00','14:59:59'),
                '16'=>array('15:00:00','15:59:59'),
                '17'=>array('16:00:00','16:59:59'),
                '18'=>array('17:00:00','17:59:59'),
                '19'=>array('18:00:00','18:59:59'),
                '20'=>array('19:00:00','19:59:59'),
                '21'=>array('20:00:00','20:59:59'),
                '22'=>array('21:00:00','21:59:59'),
                '23'=>array('22:00:00','22:59:59'),
                '24'=>array('23:00:00','23:59:59'),
            );
            
            $slot_arr2 = array_keys($slot_arr);
	       	foreach($ClientInfo as $v)
	       	{
	         $ClientInf = $v['r']['Company_name']; 
             $client_list[$ClientInf] =  $ClientInf;
	          $CampInf[strtolower($v['i']['campaign_name'])] = $ClientInf;  
            }
            
            //print_r($CampInf);exit;
				$qry_agent="SELECT * FROM roaster rd";
                $roster_det = $this->RegistrationMaster->query($qry_agent);
               // print_r($roster_det);
                $this->vicidialCloserLog->useDbConfig = 'db2';
                $data2 = array();
                foreach($roster_det as $roast)
                {
                    $idc_user =  $roast['rd']['Agent']; 
                    $login_time = $roast['rd']['shiftstarttime'];
                    $working_hour = $roast['rd']['working_hour'];

                    $start_time = date('Y-m-d H:i:s',strtotime("2022-12-08"." ".$login_time));
                    $end_time = date('Y-m-d H:i:s',strtotime($start_time."+$working_hour Hour"));
                    

                    $qry_vd="SELECT * FROM vicidial_users vu where user='$idc_user'";
                    $dt=$this->vicidialCloserLog->query($qry_vd);	
                    $closer_campaigns = $dt['0']['vu']['closer_campaigns'];
                    //print_r($dt);exit;
                    $campaigns_list = explode(" ",$closer_campaigns);
                    //print_r($campaigns_list);
                    foreach($campaigns_list as $campaign)
                    {
                        $start_time_new = $start_time;
                        while(strtotime($start_time_new)<=strtotime($end_time))
                        {
                            $slot = date('H',strtotime($start_time_new));
                            $campaign = strtolower($campaign);
                          //echo '<br>';
                            $client_id = $CampInf[$campaign];
                            //echo $campaign;
                            if(in_array($slot,$slot_arr2))
                            {
                                $data2[$client_id][$slot][$idc_user] = $idc_user;
                            }
                            $start_time_new = date('Y-m-d H:i:s',strtotime($start_time_new."+1 Hour"));
                          //echo $start_time."<br/>";
                        }
                        
                        
                    }

                }
	    } else {
	    	$ClientInfo = $this->RegistrationMaster->query("SELECT Company_name as Company_name,campaign_name as campaign_name FROM `registration_master` r join ingroup_campaign_master i on r.company_id=i.client_id WHERE `status`='A' and is_dd_client='1' and camp_type is null order by Company_name asc"); 
	       	$slot_arr = array(
                '01'=>array('00:00:00','00:59:59'),
                '02'=>array('01:00:00','01:59:59'),
                '03'=>array('02:00:00','02:59:59'),
                '04'=>array('03:00:00','03:59:59'),
                '05'=>array('04:00:00','04:59:59'),
                '06'=>array('05:00:00','05:59:59'),
                '07'=>array('06:00:00','06:59:59'),
                '08'=>array('07:00:00','07:59:59'),
                '09'=>array('08:00:00','08:59:59'),
                '10'=>array('09:00:00','09:59:59'),
                '11'=>array('10:00:00','10:59:59'),
                '12'=>array('11:00:00','11:59:59'),
                '13'=>array('12:00:00','12:59:59'),
                '14'=>array('13:00:00','13:59:59'),
                '15'=>array('14:00:00','14:59:59'),
                '16'=>array('15:00:00','15:59:59'),
                '17'=>array('16:00:00','16:59:59'),
                '18'=>array('17:00:00','17:59:59'),
                '19'=>array('18:00:00','18:59:59'),
                '20'=>array('19:00:00','19:59:59'),
                '21'=>array('20:00:00','20:59:59'),
                '22'=>array('21:00:00','21:59:59'),
                '23'=>array('22:00:00','22:59:59'),
                '24'=>array('23:00:00','23:59:59'),
            );
            
            $slot_arr2 = array_keys($slot_arr);
	       	foreach($ClientInfo as $v)
	       	{
	         $ClientInf = $v['r']['Company_name']; 
             $client_list[$ClientInf] =  $ClientInf;
	          $CampInf[strtolower($v['i']['campaign_name'])] = $ClientInf;  
            }
            
            //print_r($CampInf);exit;
				$qry_agent="SELECT * FROM roaster rd";
                $roster_det = $this->RegistrationMaster->query($qry_agent);
               // print_r($roster_det);
                $this->vicidialCloserLog->useDbConfig = 'db2';
                $data2 = array();
                foreach($roster_det as $roast)
                {
                    $idc_user =  $roast['rd']['Agent']; 
                    $login_time = $roast['rd']['shiftstarttime'];
                    $working_hour = $roast['rd']['working_hour'];

                    $start_time = date('Y-m-d H:i:s',strtotime("2022-12-09"." ".$login_time));
                    $end_time = date('Y-m-d H:i:s',strtotime($start_time."+$working_hour Hour"));
                    

                    $qry_vd="SELECT * FROM vicidial_users vu where user='$idc_user'";
                    $dt=$this->vicidialCloserLog->query($qry_vd);	
                    $closer_campaigns = $dt['0']['vu']['closer_campaigns'];
                    //print_r($dt);exit;
                    $campaigns_list = explode(" ",$closer_campaigns);
                    //print_r($campaigns_list);
                    foreach($campaigns_list as $campaign)
                    {
                        $start_time_new = $start_time;
                        while(strtotime($start_time_new)<=strtotime($end_time))
                        {
                            $slot = date('H',strtotime($start_time_new));
                            $campaign = strtolower($campaign);
                          //echo '<br>';
                            $client_id = $CampInf[$campaign];
                            //echo $campaign;
                            if(in_array($slot,$slot_arr2))
                            {
                                $data2[$client_id][$slot][$idc_user] = $idc_user;
                            }
                            $start_time_new = date('Y-m-d H:i:s',strtotime($start_time_new."+1 Hour"));
                          //echo $start_time."<br/>";
                        }
                        
                        
                    }

                }

	    }
        $this->set('data2',$data2); 	
        $this->set('slot_arr2',$slot_arr2);
        $this->set('client_list',$client_list);
        
	}



	public function skill_wise_excel()
	{
		$this->layout='user';
		
        $Userinfo = $this->RegistrationMaster->query("SELECT * FROM agent_master where status='A' and processname='Dialdesk'"); 
        foreach ($Userinfo as $key => $value) {
           $userid[] =  $value['agent_master']['username'];
           $Name[$value['agent_master']['username']] =  $value['agent_master']['displayname'];
           $Doj[$value['agent_master']['username']] =  $value['agent_master']['dateofjoining'];
        }
         //print_r($Name);
         //print_r($Doj);
         //exit;        	
        $usersearch =  "'".implode("','",$userid)."'"; 
        $qry="select user,closer_campaigns from vicidial_users vu where user in (".$usersearch.")"; 

        $this->vicidialCloserLog->useDbConfig = 'db2';
        $dt=$this->vicidialCloserLog->query($qry);
        //print_r($dt); exit;
        $this->set('data',$dt);
        $this->set('name',$Name);
        $this->set('doj',$Doj);
	}  


	public function agent_name_excel()
    {
        $this->layout='user';
       
       if($this->request->is("GET")){
      
           	echo $igpname=$_GET['igpname'];
           	$qry="select vl.user,vu.full_name from vicidial_live_agents vl join vicidial_users vu on vl.user=vu.user where campaign_id='Dialdesk' and vl.closer_campaigns like '%".$igpname."%'";

       	$this->vicidialCloserLog->useDbConfig = 'db2';
        $dt=$this->vicidialCloserLog->query($qry);
         //print_r($dt); die;
	    $this->set('data',$dt);
	         
       }
   } 



   public function abandon_trend()
    {
        $this->layout='user';
         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
       if($this->request->is("POST")){
              
       $search     =$this->request->data['AbandonReports'];
      
           $FromDate=$search['startdate'];
           $ToDate=$search['enddate'];
           $clientId    =  $search['clientID'];
           $Noofcount = $search['Noofcount'];
           $category = $search['category'];
            if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
                if($category=='All'){
                    $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1'"); 
                } else{
                    $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1' and client_category='$category'"); 
                }
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
          
       if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
   if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
   while(strtotime($FromDate)<strtotime($ToDate))
   {
       
       $date_array[] =$date_fetch= date("Y-m-d",strtotime("$FromDate"));

        $start_time_start=$FromDate;
        $event_date_start=date("Y-m-d 00:00:00",strtotime("$FromDate +24 hours"));
        $timeArray[] =  $time_fetch=date("H",strtotime("$FromDate"));
        $NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +24 hours"));
        $FromDate=$NextDate;
        
        $start_time_end=$NextDate;

               
$qry="SELECT t2.campaign_id as campaign_id,count(*) total ,SUM(IF(t2.user!='VDCL',1,0)) `Answered`,SUM(IF(t2.user='VDCL',1,0)) `Abandon`  FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid AND t1.lead_id!='' AND t2.user=t1.user LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end' and $campaignId group by t2.campaign_id"; 

       $this->vicidialCloserLog->useDbConfig = 'db2';
        $dtr=$this->vicidialCloserLog->query($qry);
               // print_r($dtr); 
        foreach($dtr as $dt)
        {
        $timeLabel=$dt['t2']['campaign_id'];
        $dateLabel=$date_fetch;
        $datetimeArray[$dateLabel][]=$timeLabel;
        $timeArray2[] = $timeLabel;
        

        $total=$dt[0]['Answered']+$dt[0]['Abandon'];
        if($total<=$Noofcount)
        {
        $data2[$dateLabel][$timeLabel]['Abandon %'] = round($dt[0]['Abandon']*100/$total)."%"; 
        //$data2[$dateLabel][$timeLabel]['Talk Time'] = $dt[0]['talktime'];
        //$data2[$dateLabel][$timeLabel]['Utilization %'] = round($dt[0]['Utilization'],2);
    	}
       }
       
    }
     //print_r($data2);exit;
        $date_array = array_unique($date_array);
        $timeArray=array_unique($timeArray2);

        $this->set('companyid',$client);
        $this->set('data',$data2);
         $this->set('datetimeArray',$datetimeArray);
         $this->set('datearray', $date_array);
         $this->set('timearray', $timeArray);
       }
   }


public function abandon_trend_excel()
    {
        $this->layout='user';
         if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
       if($this->request->is("POST")){
              
       $search     =$this->request->data['AbandonReports'];
      
           $FromDate=$search['startdate'];
           $ToDate=$search['enddate'];
           $clientId    =  $search['clientID'];
           $Noofcount = $search['Noofcount'];
           $category = $search['category'];
            if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
                if($category=='All'){
                    $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1'"); 
                } else{
                    $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1' and client_category='$category'"); 
                } 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }else {

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId.")";
            }
          
       if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
   if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
   while(strtotime($FromDate)<strtotime($ToDate))
   {
       
       $date_array[] =$date_fetch= date("Y-m-d",strtotime("$FromDate"));

        $start_time_start=$FromDate;
        $event_date_start=date("Y-m-d 00:00:00",strtotime("$FromDate +24 hours"));
        $timeArray[] =  $time_fetch=date("H",strtotime("$FromDate"));
        $NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +24 hours"));
        $FromDate=$NextDate;
        
        $start_time_end=$NextDate;

               
$qry="SELECT t2.campaign_id as campaign_id,count(*) total ,SUM(IF(t2.user!='VDCL',1,0)) `Answered`,SUM(IF(t2.user='VDCL',1,0)) `Abandon`  FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.vicidial_agent_log t1 ON t1.uniqueid=t2.uniqueid AND t1.lead_id!='' AND t2.user=t1.user LEFT JOIN (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND parked_time>='$start_time_start' AND parked_time<'$start_time_end' GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid WHERE t2.call_date>='$start_time_start' AND t2.call_date<'$start_time_end' and $campaignId group by t2.campaign_id"; 

       $this->vicidialCloserLog->useDbConfig = 'db2';
        $dtr=$this->vicidialCloserLog->query($qry);
               // print_r($dtr); 
        foreach($dtr as $dt)
        {
        $timeLabel=$dt['t2']['campaign_id'];
        $dateLabel=$date_fetch;
        $datetimeArray[$dateLabel][]=$timeLabel;
        $timeArray2[] = $timeLabel;
        
        $total=$dt[0]['Answered']+$dt[0]['Abandon'];
        if($total<=$Noofcount)
        {
        $data2[$dateLabel][$timeLabel]['Abandon %'] = round($dt[0]['Abandon']*100/$total)."%"; 
        //$data2[$dateLabel][$timeLabel]['Talk Time'] = $dt[0]['talktime'];
        //$data2[$dateLabel][$timeLabel]['Utilization %'] = round($dt[0]['Utilization'],2);
       	}
       }
       
    }
     //print_r($data2);exit;
        $date_array = array_unique($date_array);
        $timeArray=array_unique($timeArray2);

        $this->set('data',$data2);
         $this->set('datetimeArray',$datetimeArray);
         $this->set('datearray', $date_array);
         $this->set('timearray', $timeArray);
       }
   }  
   
   public function roaster_manpower()
   {
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
        if($this->request->is("POST")){

            $search   =  $this->request->data['AbandonReports'];
            $firstDay = $search['startdate'];
            $lastDay   = $search['enddate'];

            $start_date = $search['startdate'];
            $end_date   = $search['enddate'];

            $firstDay = date("Y-m-d", strtotime($start_date));  
            $lastDay = date("Y-m-d", strtotime($end_date));


            $clientId    =  $search['clientID'];
            $aht    =  $search['aht'];
            $ut    =  $search['ut'].' %';

            if($clientId=='All'){
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
               $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1'"); 
                $campaignId = $ClientInfo[0][0]['campaign_id']; 
                $campaignId =   "t2.campaign_id in(".$campaignId.")";

           
            }
            else{

                //$ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));
                $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
                $ClientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id FROM `registration_master` WHERE `status`='A' and is_dd_client='1' and client_category='$clientId'"); 

                // $campaignId1 =  $ClientInfo['RegistrationMaster']['campaignid'];
                // $campaignId =   "t2.campaign_id in(".$campaignId1.")";
                if(!empty($ClientInfo[0][0]['campaign_id'])){

                    $campaignId = $ClientInfo[0][0]['campaign_id']; 
                    $campaignId =   "t2.campaign_id in(".$campaignId.")";
    
                   

                }else{
                    //$campaignId =   "";
                    return $this->redirect(['controller'=>'AbandonReports','action' => 'roaster_manpower']);

                }

            }

            // $DayDiff = strtotime($firstDay)-strtotime($lastDay);
            // echo  date('z', $DayDiff)." Days";die;
            $daycount=floor((strtotime($lastDay)-strtotime($firstDay))/ (24 * 60 * 60))+1;
            $month_name = date_format(date_create($lastDay),'M');
            //echo $month_name;die;
            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

            

            
        $require_shift = array('22:00-7:00','10:00-19:00','13:00-22:00','07:00-16:00','09:00-13:00','18:00-22:00');
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
                SUM(if(t2.`user` !='VDCL',1,0)) `Answered`, SUM(if(t2.`user` ='VDCL',1,0)) `Abandon` FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid  WHERE  time(t2.call_date)>='$start_time_start' AND time(t2.call_date)<='$start_time_end' and date(t2.call_date)>='$firstDay' AND date(t2.call_date)<='$lastDay'
                and $campaignId and t2.term_reason!='AFTERHOURS' and t2.lead_id is not null ";   
                
                $this->vicidialCloserLog->useDbConfig = 'db2';
                $dt=$this->vicidialCloserLog->query($qry);
                //print_r($dt);  die;
                $firstDayag = $firstDay;
                $lastDayag = $lastDay;

                $FromDateag=$firstDay.' '.$start_time_end;
                $ToDateag=$lastDay.' '. $start_time_end;
                $userCnt=0;
                $actual = '';

            
                        
                $timeLabel=date("H:i:s",strtotime($start_time_start));
                $dateLabel=date("Y",strtotime($start_time_start));
                $datetimeArray[$dateLabel][]=$timeLabel;
                $data['Total Calls'][$dateLabel][$timeLabel]=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];

                $total_ans = $dt[0][0]['Answered']+$dt[0][0]['Abandon'];

                $avg_data = $total_ans/$daycount;
                $data['Avg Calls'][$dateLabel][$timeLabel]=round($avg_data,2);

                $aht_data = 3600/$aht;
                $ut_data = 60*($ut/100)/60;
                $datas['AHT '.$aht.''][$dateLabel][$timeLabel]=$aht_data;
                $datas['UT '.$ut.''][$dateLabel][$timeLabel]=$ut_data;
                $capacity_data = $aht_data*$ut_data;
                $datas['Capacity'][$dateLabel][$timeLabel]=$capacity_data;

                $manpower_data= round($avg_data/$capacity_data,2);
                $data['Manpower(Avg/AHT*UT)'][$dateLabel][$timeLabel] = $manpower_data;


                $total=$dt[0][0]['Answered']+ $dt[0][0]['Abandon'];
                $tatlahct = $tatlahct+ $dt[0][0]['TotalAcht'];
                $Answered=$Answered + $dt[0][0]['Answered'];
                    
                // if($userCnt<='0') { $userCnt='2';  } else { $userCnt=$userCnt;  }  
                // $data['Agent Logged In'][$dateLabel][$timeLabel]=$userCnt;
                        
                $TotalCall+=$total;
                $i++;

                $dataActual['Actual Required'][$dateLabel][$timeLabel]=round($manpower_data+1);
                $dataActual['Shortage'][$dateLabel][$timeLabel]=round($manpower_data+1) - $manpower_data;


                
                $slot_data[$timeLabel] = number_format($manpower_data);
                // $datashift['Required Shift'][$dateLabel][$timeLabel]=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
                $datashift['Count'][$dateLabel][$timeLabel]=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
                
            }
        }  
        //print_r($require_shift);die;
        $count_array = array();
        foreach($require_shift as $slot)
        {
            $slot_arr = explode('-',$slot);
            //print_r($slot_arr);exit;
            $from = $slot_arr[0];
            $to = $slot_arr[1];

            $from_date = "$firstDay $from:00";
            $to_date  = '';
            $number = intval($from);
            if($number > 12)
            {
                //$number .= $to_date;
                $to_date = "$lastDay $to:00";
            }else{
                $to_date = "$firstDay $to:00";
            }
           
            //$to_date = "2022-12-29 $to:00"; 
            
            while(strtotime($from_date) < strtotime($to_date))
            {
                $timeslot = date('H:i:s',strtotime($from_date));
                #echo  '<br/>';
                #print_r($slot_data);
                #echo  '<br/>';
                if(empty($count_array[$slot]) || $slot_data[$timeslot] < $count_array[$slot])
                {
                    $count_array[$slot] = $slot_data[$timeslot];
                    //$datashift['Count'][$dateLabel][$timeLabel]=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
                }
               
                $from_date = date('Y-m-d H:i:s',strtotime($from_date." +1 Hours"));
            }
            
        }
        #print_r($slot_data);exit;
        #echo '<br/>'; 
            #print_r($count_array);exit;
      }

      $this->set('aht',$aht);
      $this->set('ut',$ut);
      $this->set('count_array',$count_array);
      $this->set('data',$data);
      $this->set('dataActual',$dataActual);
      $this->set('datashift',$datashift);
      $this->set('datetimeArray',$datetimeArray);
      $this->set('companyid',$clientId);
      
   }

   public function roaster_shift_match()
   {
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
        if($this->request->is("POST")){

            $search   =  $this->request->data['AbandonReports'];
            $firstDay = $search['startdate'];
            $lastDay   = $search['enddate'];
            $clientId    =  $search['clientID'];
            $aht    =  $search['aht'];
            $ut    =  $search['ut'].' %';

            $ClientInfo = $this->RegistrationMaster->query("SELECT Company_name as Company_name,campaign_name as campaign_name FROM `registration_master` r join ingroup_campaign_master i on r.company_id=i.client_id WHERE r.company_id ='$clientId' and `status`='A' and is_dd_client='1' and camp_type is null order by Company_name asc"); 
            $agent = array();
            foreach($ClientInfo as $v)
            {
                $ClientInf = $v['r']['Company_name']; 
                //$CampInf[$ClientInf][] = $v['i']['campaign_name']; 
                
                $qry="select user,closer_campaigns from vicidial_users where closer_campaigns like '%".$v['i']['campaign_name']."%'";

                $this->vicidialCloserLog->useDbConfig = 'db2';
                $dt=$this->vicidialCloserLog->query($qry);

                $ClientInfo = $this->Roaster->query("SELECT Company_name as Company_name,campaign_name as campaign_name FROM `registration_master` r join ingroup_campaign_master i on r.company_id=i.client_id WHERE r.company_id ='$clientId' and `status`='A' and is_dd_client='1' and camp_type is null order by Company_name asc"); 	
                //echo $dt[0][0]['cnt']; die;
                //print_r($dt);die;
                $agent[$dt[0]['vicidial_users']['user']] = $dt[0]['vicidial_users']['closer_campaigns'];
                    
            }
            print_r($agent);die;

            // $DayDiff = strtotime($firstDay)-strtotime($lastDay);
            // echo  date('z', $DayDiff)." Days";die;
            $daycount=floor((strtotime($lastDay)-strtotime($firstDay))/ (24 * 60 * 60))+1;
            $month_name = date_format(date_create($lastDay),'M');
            //echo $month_name;die;
            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

            $campaignId1 =  $ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "t2.campaign_id in(".$campaignId1.")";
        $require_shift = array('22:00-7:00','10:00-19:00','13:00-22:00','07:00-16:00','09:00-13:00','18:00-22:00');
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
                SUM(IF(t2.`user` ='VDCL' AND t2.queue_seconds>20,1,0)) `AbndAfterThresold` FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid  WHERE  time(t2.call_date)>='$start_time_start' AND time(t2.call_date)<='$start_time_end' and date(t2.call_date)>='$firstDay' AND date(t2.call_date)<='$lastDay'
                and $campaignId and t2.term_reason!='AFTERHOURS' and t2.lead_id is not null "; 
                
                $this->vicidialCloserLog->useDbConfig = 'db2';
                $dt=$this->vicidialCloserLog->query($qry);
                //print_r($dt);  die;
                $firstDayag = $firstDay;
                $lastDayag = $lastDay;

                $FromDateag=$firstDay.' '.$start_time_end;
                $ToDateag=$lastDay.' '. $start_time_end;
                $userCnt=0;
                $actual = '';
    
                $timeLabel=date("H:i:s",strtotime($start_time_start));
                $dateLabel=date("Y",strtotime($start_time_start));
                $datetimeArray[$dateLabel][]=$timeLabel;
                $data['Call Till '.$daycount.'th '.$month_name.''][$dateLabel][$timeLabel]=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];

                $total_ans = $dt[0][0]['Answered']+$dt[0][0]['Abandon'];

                $avg_data = $total_ans/$daycount;
                $data['Avg Calls'][$dateLabel][$timeLabel]=round($avg_data,2);

                $aht_data = 3600/$aht;
                $ut_data = 60*($ut/100)/60;
                $data['AHT '.$aht.''][$dateLabel][$timeLabel]=$aht_data;
                $data['UT '.$ut.''][$dateLabel][$timeLabel]=$ut_data;
                $capacity_data = $aht_data*$ut_data;
                $data['Capacity'][$dateLabel][$timeLabel]=$capacity_data;

                $manpower_data= round($avg_data/$capacity_data,2);
                $data['Manpower'][$dateLabel][$timeLabel] = $manpower_data;


                $total=$dt[0][0]['Answered']+ $dt[0][0]['Abandon'];
                $tatlahct = $tatlahct+ $dt[0][0]['TotalAcht'];
                $Answered=$Answered + $dt[0][0]['Answered'];
                    
                // if($userCnt<='0') { $userCnt='2';  } else { $userCnt=$userCnt;  }  
                // $data['Agent Logged In'][$dateLabel][$timeLabel]=$userCnt;
                        
                $TotalCall+=$total;
                $i++;

                $dataActual['Actual Required'][$dateLabel][$timeLabel]=number_format($manpower_data);
                $dataActual['Shortage'][$dateLabel][$timeLabel]=round(number_format($manpower_data) - $manpower_data,2);


                
                $slot_data[$timeLabel] = number_format($manpower_data);
                // $datashift['Required Shift'][$dateLabel][$timeLabel]=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
                $datashift['Count'][$dateLabel][$timeLabel]=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];

                
            }
        }  

        $count_array = array();
        foreach($require_shift as $slot)
        {
            $slot_arr = explode('-',$slot);
            //print_r($slot_arr);exit;
            $from = $slot_arr[0];
            $to = $slot_arr[1];

            $from_date = "2022-12-29 $from:00";
            $to_date  = '';
            $number = intval($from);
            if($number > 12)
            {
                //$number .= $to_date;
                $to_date = "2022-12-30 $to:00";
            }else{
                $to_date = "2022-12-29 $to:00";
            }
           
            //$to_date = "2022-12-29 $to:00"; 
            
            while(strtotime($from_date)< strtotime($to_date))
            {
                 $timeslot = date('H:i:s',strtotime($from_date));
                #echo  '<br/>';
                #print_r($slot_data);
                #echo  '<br/>';
                if(empty($count_array[$slot]) || $slot_data[$timeslot] < $count_array[$slot])
                {
                    $count_array[$slot] = $slot_data[$timeslot];
                    //$datashift['Count'][$dateLabel][$timeLabel]=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
                }
               
                
                $from_date = date('Y-m-d H:i:s',strtotime($from_date." +1 Hours"));
            }
            
        }
        #print_r($slot_data);exit;
        #echo '<br/>'; 
            #print_r($count_array);exit;
        

      }
      $this->set('count_array',$count_array);
      $this->set('data',$data);
      $this->set('dataActual',$dataActual);
      $this->set('datashift',$datashift);
      $this->set('datetimeArray',$datetimeArray);
      $this->set('companyid',$clientId);
      
   }


   public function agent_apr()
    {
        $this->layout='user';
      
    }

    public function agent_apr_excel()
    {
        $this->layout='user';
       
        if($this->request->is("POST")){
      
            $search   =  $this->request->data['AbandonReports'];
            $firstDay = $search['startdate']." 00:00:00";
            $lastDay   = $search['enddate']." 23:59:59";
            $qry="select date(event_time) event_time, vl.user,vu.full_name,sum(if(lead_id>0 and status is not null,1,0)) calls,sec_to_time(sum(wait_sec+talk_sec+dispo_sec+pause_sec)) login_time,sec_to_time(sum(if(wait_sec>5000,0,wait_sec))) wait_sec,sec_to_time(sum(talk_sec)) talk_sec,sec_to_time(sum(dispo_sec)) dispo_sec,sec_to_time(sum(if(pause_sec>5000,0,pause_sec))) pause_sec,lead_id,status,sec_to_time(sum(dead_sec)) dead_sec from vicidial_agent_log vl join vicidial_users vu on vl.user=vu.user where event_time <= '$lastDay' and event_time >= '$firstDay' and campaign_id IN('Dialdesk') and vl.user_group IN('ADMIN','Agarwal_Pharma','AppleProcess','Boost_M','Dialdesk','DU_Digital','Exicom_Inbond','FB','Jaipur','Naresh','OBD','RupeeRedee','Sales') group by user,date(event_time)";

            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt=$this->vicidialCloserLog->query($qry);
            //print_r($dt); die;
            $this->set('data',$dt);   
        }
    }

    public function client_wise_agent_apr()
    {
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("campaignid","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
            #print_r($client);die;
            $newClient = array();

            foreach ($client as $campaignid => $companyName) {
                #echo $campaignid;
                $campaignid_new  = str_replace("'", "", $campaignid);
                $newClient[$campaignid_new] = $companyName;
            }
            $this->set('client',$newClient); 
        }else{

            $company_id   =   $this->Session->read('companyid');
            #echo $company_id;die;
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("campaignid","Company_name"),'conditions'=>array('status'=>'A','company_id'=>$company_id)));
            
            $newClient = array();

            foreach ($client as $campaignid => $companyName) {
                #echo $campaignid;
                $campaignid_new  = str_replace("'", "", $campaignid);
                $newClient[$campaignid_new] = $companyName;
            }
            #echo $company_id;die;
            $this->set('companyid',$campaignid_new); 
            $this->set('client',$newClient); 
        }

    }


   public function ob_internal()
    {
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            //$client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
       
       if($this->request->is("POST")){
      
            $search   =  $this->request->data['AbandonReports'];
            $firstDay = $search['startdate'];
            $lastDay   = $search['enddate'];
            $clientId    =  $search['clientID'];

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "campaign_id in(".$campaignId.")";

            $qry="select date(entry_date) eDate,count(1) datareceive,sum(if(user is not null,1,0)) dialcnt,sum(if(user is null,1,0)) ndialcnt,sum(if(user!='VDAD' and user is not null,1,0)) concnt,sum(if(user='VDAD',1,0)) nconcnt from vicidial_list where date(entry_date) between '$firstDay' and '$lastDay' and list_id in (select list_id from vicidial_lists where $campaignId) group by date(entry_date)";

        $this->vicidialCloserLog->useDbConfig = 'db2';
        $dt=$this->vicidialCloserLog->query($qry);
        // print_r($dt); die;
        $this->set('data',$dt);
             
       }
   }

    

   public function agent_forecast()
    {
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            //$client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
       
       if($this->request->is("POST")){
      
            $search   =  $this->request->data['AbandonReports'];
            $firstDay = $search['startdate'];
            $lastDay   = $search['enddate'];
            $clientId    =  $search['clientID'];

            $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

            $campaignId=$ClientInfo['RegistrationMaster']['campaignid'];
            $campaignId =   "campaign_id in(".$campaignId.")";

            $hour_stmt="select closecallid, substr(call_date, 1, 13) as start_hour, length_in_sec, substr(call_date+INTERVAL length_in_sec second, 1, 13) as end_hour, if(DATE_FORMAT(call_date, '%Y-%m-%d %H:00:00')!=DATE_FORMAT(call_date+INTERVAL length_in_sec second, '%Y-%m-%d %H:00:00'), UNIX_TIMESTAMP(DATE_FORMAT(call_date+INTERVAL length_in_sec second, '%Y-%m-%d %H:00:00'))-UNIX_TIMESTAMP(call_date), length_in_sec) as length_up_to_next_hour, if(DATE_FORMAT(call_date, '%Y-%m-%d %H:00:00')!=DATE_FORMAT(call_date+INTERVAL length_in_sec second, '%Y-%m-%d %H:00:00'), UNIX_TIMESTAMP(call_date+INTERVAL length_in_sec second)-UNIX_TIMESTAMP(DATE_FORMAT(call_date+INTERVAL length_in_sec second, '%Y-%m-%d %H:00:00')), 0) as length_running_into_next_hour, status, uniqueid from vicidial_closer_log v where length_in_sec is not null and campaign_id in ('AKAI','Aaavio_Ecommerce','Aaavio_Franchisee','Aaavio_Ministore','Aaavio_Otherqueries','AIWA_English','AIWA_Hindi','AKAI','AKAI_Hindi','Altum_Technologies','Anand_Novelities','Attidust','B_Monk_Villa_English','B_Monk_Villa_Hindi','Baffle_Soul','Baffle_Technologies','Bella_Vita','Blueheaven_English','Blueheaven_Hindi','Breathing_Village','Charakashtanga_Ayur','Cotton_Villa_English','Cotton_Villa_Hindi','Dialdesk_OBD','Dialdesk_Sales','Dialdesk_Transfer','dialdesk1','DLF','durian','E_Motherson','E_The_Taste_of_Malwa','Energy_Fire','Eng_Anand_Novelities','Eng_Huge_Segway','Exalata','Exalata_Inbound','Exalata_Share','Firefly','Firefly_Hindi','Fortum','girish','Globira_Medical','H_Motherson','H_The_Taste_of_Malwa','Harvest_Food','Hin_Anand_Novelities','Hin_TAC_Ayurveda','I2E1','IA_Sound_Healing','ICA_Job_Guarentee','Iroomz_India','Jazz_Inn','Jazz_Inn_Eng','JS_Power_Solution','Kansystore','Karam_Kundali','KW_Group','lapcare','lapcare_sale','lapcare_support','Lapinoz','Leadsark_English','Leadsark_Hindi','Loanjunction_Eng','Loanjunction_Hin','Meeratask','Mobile_360','Motherson','Multicraft','Nature_essence','OMEGA_English','OMEGA_Hindi','Paradise_Security','Repair_My_Pixel','Revosol','Salochana','Sandook_Sutras','ShareNply','Shri_Sai_Ent','Skill_Camper','sparemaster','SRF_Limited','Stay_Bindaas_English','Stay_Bindaas_Hindi','The_White_Tree','Travel_Port','Tuchware_Eng','tvteleshop','Twenty_Four_Jew_Hin','Twenty_Four_Jewelery','Uniform_Solutions','usha_shriram','usha_shriram_hindi','Vaalve_Bathware_Hin','Vaalve_Bathwares','Vet_On_Wheels','Viega','Vyom','Vyom_New_Connection','Vyom_Support','Wire_Switch','Zap_in','DU_Digital_Bangla','DU_Digital_English','DU_Digital_Hindi','Boost_Media','Apple_Inbound','EV_Charger833','Exicom_EV_Battery','Exicom_EV_Charger','Exicom_TC_Battery','Ap_IN_MAS','AP_OB','Apple_Inbound','Dialdesk_Sales','Dialdesk_Sales','Dialdesk_Sales','Dialdesk_Sales','Group_Four','Group_One','Group_Three','Group_Two') and call_date>='2023-01-19 00:00:00' and call_date<='2023-01-19 23:59:59' and status!='AFTHRS'";

            $avg_stmt="select avg(length_in_sec) as avg_length from vicidial_closer_log v where length_in_sec is not null and campaign_id in ('AKAI','Aaavio_Ecommerce','Aaavio_Franchisee','Aaavio_Ministore','Aaavio_Otherqueries','AIWA_English','AIWA_Hindi','AKAI','AKAI_Hindi','Altum_Technologies','Anand_Novelities','Attidust','B_Monk_Villa_English','B_Monk_Villa_Hindi','Baffle_Soul','Baffle_Technologies','Bella_Vita','Blueheaven_English','Blueheaven_Hindi','Breathing_Village','Charakashtanga_Ayur','Cotton_Villa_English','Cotton_Villa_Hindi','Dialdesk_OBD','Dialdesk_Sales','Dialdesk_Transfer','dialdesk1','DLF','durian','E_Motherson','E_The_Taste_of_Malwa','Energy_Fire','Eng_Anand_Novelities','Eng_Huge_Segway','Exalata','Exalata_Inbound','Exalata_Share','Firefly','Firefly_Hindi','Fortum','girish','Globira_Medical','H_Motherson','H_The_Taste_of_Malwa','Harvest_Food','Hin_Anand_Novelities','Hin_TAC_Ayurveda','I2E1','IA_Sound_Healing','ICA_Job_Guarentee','Iroomz_India','Jazz_Inn','Jazz_Inn_Eng','JS_Power_Solution','Kansystore','Karam_Kundali','KW_Group','lapcare','lapcare_sale','lapcare_support','Lapinoz','Leadsark_English','Leadsark_Hindi','Loanjunction_Eng','Loanjunction_Hin','Meeratask','Mobile_360','Motherson','Multicraft','Nature_essence','OMEGA_English','OMEGA_Hindi','Paradise_Security','Repair_My_Pixel','Revosol','Salochana','Sandook_Sutras','ShareNply','Shri_Sai_Ent','Skill_Camper','sparemaster','SRF_Limited','Stay_Bindaas_English','Stay_Bindaas_Hindi','The_White_Tree','Travel_Port','Tuchware_Eng','tvteleshop','Twenty_Four_Jew_Hin','Twenty_Four_Jewelery','Uniform_Solutions','usha_shriram','usha_shriram_hindi','Vaalve_Bathware_Hin','Vaalve_Bathwares','Vet_On_Wheels','Viega','Vyom','Vyom_New_Connection','Vyom_Support','Wire_Switch','Zap_in','DU_Digital_Bangla','DU_Digital_English','DU_Digital_Hindi','Boost_Media','Apple_Inbound','EV_Charger833','Exicom_EV_Battery','Exicom_EV_Charger','Exicom_TC_Battery','Ap_IN_MAS','AP_OB','Apple_Inbound','Dialdesk_Sales','Dialdesk_Sales','Dialdesk_Sales','Dialdesk_Sales','Group_Four','Group_One','Group_Three','Group_Two') and call_date>='2023-01-19 00:00:00' and call_date<='2023-01-19 23:59:59'";

            $wrapup_stmt="select v.uniqueid from vicidial_closer_log v where length_in_sec is not null and user!='VDCL' and campaign_id in ('AKAI','Aaavio_Ecommerce','Aaavio_Franchisee','Aaavio_Ministore','Aaavio_Otherqueries','AIWA_English','AIWA_Hindi','AKAI','AKAI_Hindi','Altum_Technologies','Anand_Novelities','Attidust','B_Monk_Villa_English','B_Monk_Villa_Hindi','Baffle_Soul','Baffle_Technologies','Bella_Vita','Blueheaven_English','Blueheaven_Hindi','Breathing_Village','Charakashtanga_Ayur','Cotton_Villa_English','Cotton_Villa_Hindi','Dialdesk_OBD','Dialdesk_Sales','Dialdesk_Transfer','dialdesk1','DLF','durian','E_Motherson','E_The_Taste_of_Malwa','Energy_Fire','Eng_Anand_Novelities','Eng_Huge_Segway','Exalata','Exalata_Inbound','Exalata_Share','Firefly','Firefly_Hindi','Fortum','girish','Globira_Medical','H_Motherson','H_The_Taste_of_Malwa','Harvest_Food','Hin_Anand_Novelities','Hin_TAC_Ayurveda','I2E1','IA_Sound_Healing','ICA_Job_Guarentee','Iroomz_India','Jazz_Inn','Jazz_Inn_Eng','JS_Power_Solution','Kansystore','Karam_Kundali','KW_Group','lapcare','lapcare_sale','lapcare_support','Lapinoz','Leadsark_English','Leadsark_Hindi','Loanjunction_Eng','Loanjunction_Hin','Meeratask','Mobile_360','Motherson','Multicraft','Nature_essence','OMEGA_English','OMEGA_Hindi','Paradise_Security','Repair_My_Pixel','Revosol','Salochana','Sandook_Sutras','ShareNply','Shri_Sai_Ent','Skill_Camper','sparemaster','SRF_Limited','Stay_Bindaas_English','Stay_Bindaas_Hindi','The_White_Tree','Travel_Port','Tuchware_Eng','tvteleshop','Twenty_Four_Jew_Hin','Twenty_Four_Jewelery','Uniform_Solutions','usha_shriram','usha_shriram_hindi','Vaalve_Bathware_Hin','Vaalve_Bathwares','Vet_On_Wheels','Viega','Vyom','Vyom_New_Connection','Vyom_Support','Wire_Switch','Zap_in','DU_Digital_Bangla','DU_Digital_English','DU_Digital_Hindi','Boost_Media','Apple_Inbound','EV_Charger833','Exicom_EV_Battery','Exicom_EV_Charger','Exicom_TC_Battery','Ap_IN_MAS','AP_OB','Apple_Inbound','Dialdesk_Sales','Dialdesk_Sales','Dialdesk_Sales','Dialdesk_Sales','Group_Four','Group_One','Group_Three','Group_Two') and call_date>='2023-01-19 00:00:00' and call_date<='2023-01-19 23:59:59'";

        $this->vicidialCloserLog->useDbConfig = 'db2';
        $hour_rslt=$this->vicidialCloserLog->query($hour_stmt);
        $avg_rslt=$this->vicidialCloserLog->query($avg_stmt);
        $wrapup_rslt=$this->vicidialCloserLog->query($wrapup_stmt);

    $uid_ct=0; 
    $uid_clause="";
    $dispo_secs=0;
    $talk_secs=0;
    foreach($wrapup_rslt as $dt) {
        $uid_ct++;
        $uid_clause.="'".$dt['v']['uniqueid']."',";
        if ($uid_ct%100==0) {
            $uid_clause=preg_replace('/,$/', '', $uid_clause);
            $uid_stmt="select va.dispo_sec, va.talk_sec from vicidial_agent_log va where uniqueid in ($uid_clause)";
            $uid_rslt=$this->vicidialCloserLog->query($uid_stmt);
            foreach($uid_rslt as $ur) {
                $dispo_secs+=$ur['va']['dispo_sec'];
                $talk_secs+=$ur['va']['talk_sec'];
            }
            $uid_clause="";
        }
    }
    echo $uid_ct;
    if (strlen($uid_clause)>0) {
        $uid_clause=preg_replace('/,$/', '', $uid_clause);
        $uid_stmt="select va.dispo_sec, va.talk_sec from vicidial_agent_log va where uniqueid in ($uid_clause)";
            $uid_rslt=$this->vicidialCloserLog->query($uid_stmt);
            foreach($uid_rslt as $ur) {
                $dispo_secs+=$ur['va']['dispo_sec'];
                $talk_secs+=$ur['va']['talk_sec'];
            }
        $uid_clause="";
    }
    $avg_dispo_sec=$dispo_secs/$wrapup_calls;
    $avg_talk_sec=$talk_secs/$wrapup_calls;



         //print_r($wrapup_rslt); 
    die;
        $this->set('data',$dt);
             
       }
   }

   public function agent_wise_skill_excel()
   {
       $this->layout='user';
       $ClientInfo = $this->RegistrationMaster->query("SELECT Company_name as Company_name,campaign_name as campaign_name FROM `registration_master` r join ingroup_campaign_master i on r.company_id=i.client_id WHERE `status`='A' and is_dd_client='1' and camp_type is null order by Company_name asc"); 
      foreach($ClientInfo as $v)
       {
        $ClientInf = $v['r']['Company_name']; 
        $qry="select group_concat(\"'\",user,\"'\") users from vicidial_users where closer_campaigns like '%".$v['i']['campaign_name']."%'";

        $this->vicidialCloserLog->useDbConfig = 'db2';
        $dt=$this->vicidialCloserLog->query($qry);	
        //echo $dt[0][0]['cnt']; die;
        $Usr = $dt[0][0]['users'];
        if($Usr) {
        $Userinfo = $this->RegistrationMaster->query("SELECT group_concat(displayname) lusers FROM agent_master where username in ($Usr) and status='A' and processname='Dialdesk'");
        }
        //print_r($Userinfo); exit;
        //echo $Userinfo[0][0]['lusers']; exit;
        $CampInf[$ClientInf][$v['i']['campaign_name']] = $Userinfo[0][0]['lusers'];     
        }
//print_r($CampInf); exit;
        $this->set('data',$CampInf); 
   }  


   public function drop_call_reason() {
    $this->layout='user';
    if($this->Session->read('role') =="admin"){
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
        $this->set('client',$client); 
    }
    if($this->request->is("POST")){
    
        $search=$this->request->data['AbandonReports'];
        //print_r($search);die;
        $FromDate   =   $search['startdate'];
        $ToDate     =   $search['enddate'];
        $start_time=date("Y-m-d",strtotime("$FromDate"));
        $end_time=date("Y-m-d",strtotime("$ToDate"));
        
       
       
        $loginqry = "select group_concat(distinct(user)) user,count(distinct(user)) usecnt from vicidial_closer_log t2 where DATE(t2.call_date) BETWEEN '2023-02-07' AND '2023-02-07' AND
        t2.campaign_id in('Fortum','Fortum_O') AND t2.lead_id IS NOT NULL and t2.user!='VDCL'"  ;  

        $this->vicidialAgentLog->useDbConfig = 'db2';
        $data1=$this->vicidialAgentLog->query($loginqry);
        //print_r($data1);
        $cnt = $data1[0][0]['usecnt'];
        echo $Userx = "'".str_replace(",","','",$data1[0][0]['user'])."'";
       
        $qry = "select t2.user Agent,t2.lead_id as LeadId,
        RIGHT(phone_number,10) PhoneNumber, 
        DATE(call_date) CallDate,
        SEC_TO_TIME(queue_seconds) Queuetime, 
        IF(queue_seconds='0',FROM_UNIXTIME(t2.start_epoch),FROM_UNIXTIME(t2.start_epoch-queue_seconds)) AS QueueStart, 
        FROM_UNIXTIME(t2.start_epoch) StartTime,
        FROM_UNIXTIME(t2.end_epoch) Endtime,t2.start_epoch,t2.end_epoch  from vicidial_closer_log t2 where DATE(t2.call_date) BETWEEN '2023-02-07' AND '2023-02-07' AND 
        t2.campaign_id in('Fortum','Fortum_O') AND t2.lead_id IS NOT NULL and t2.user='VDCL'";


//select * from vicidial_agent_log where user='46313C' and date(event_time)='2023-02-07' and talk_epoch<'1675754977' and dispo_epoch>'1675754998';

//select * from vicidial_closer_log where lead_id='9389664';

//select min(event_date) lgtime from vicidial_user_log where event='LOGIN' and date(event_date)=curdate() and user='MAS51305';

//select max(event_date) lotime from vicidial_user_log where event='LOGOUT' and date(event_date)=curdate() and user='MAS51305';
        
        $this->vicidialAgentLog->useDbConfig = 'db2';
        $data=$this->vicidialAgentLog->query($qry);
        print_r($data);
        echo "<br>";
        foreach($data as $dt){
            $start = $dt[t2]['start_epoch'];
            $end = $dt[t2]['end_epoch'];
            $Callqry = "select count(user) cnt from vicidial_agent_log where user in ($Userx) and date(event_time)='2023-02-07' and 
            talk_epoch<'$start' and dispo_epoch>'$end'";
            $this->vicidialAgentLog->useDbConfig = 'db2';
            $data2=$this->vicidialAgentLog->query($Callqry);
            print_r($data2);
           echo  $user_cnt[$dt[t2]['LeadId']][] = $data2[0][0]['cnt']; 
        }
        print_r($user_cnt);
        die;
        
        




    $this->set('data',$data);
     
    }
}


///////////// Revenue Data ////////////////////////////////////

public function revenue_data()
{
    $this->layout = 'user';

    // Handle the POST request
    

    if ($this->request->is("POST")) {
        $search = $this->request->data['AbandonReports'];

        $FromDate = $search['startdate'];
        $ToDate = $search['enddate'];

        if ($FromDate != '') {
            $FromDate = $FromDate . ' 00:00:00';
        }
        if ($ToDate != '') {
            $ToDate = $ToDate . ' 23:59:59';
        }

        while (strtotime($FromDate) < strtotime($ToDate)) { 
            $date_array[] = $date_fetch = date("Y-m-d", strtotime("$FromDate"));

            $start_time_start = $FromDate;
            $event_date_start = date("Y-m-d 00:00:00", strtotime("$FromDate +24 hours"));
            $timeArray[] = $time_fetch = date("H", strtotime("$FromDate"));
            $NextDate = date("Y-m-d H:i:s", strtotime("$FromDate +24 hours"));
            $FromDate = $NextDate;

            $start_time_end = $NextDate;
           // echo "select t1.company_name,t2.cm_total from registration_master t1 join billing_consume_daily t2 on t1.company_id=t2.client_id where t2.cm_date>='$start_time_start' and cm_date<='$start_time_end' group by t2.client_id  order by t1.company_name";
            $ClientInfo = $this->RegistrationMaster->query("select t1.company_name,round(t2.cm_total,2) cm_total from registration_master t1 join billing_consume_daily t2 on t1.company_id=t2.client_id where t2.cm_date>='$start_time_start' and cm_date<='$start_time_end' group by t2.client_id  order by t1.company_name");
            //print_r($ClientInfo);
            foreach ($ClientInfo as $dt) {
                $timeLabel = $dt['t1']['company_name'];
                $dateLabel = $date_fetch;
                $datetimeArray[$dateLabel][] = $timeLabel;
                $timeArray2[] = $timeLabel;
                $data2[$dateLabel][$timeLabel]['Amount'] = $dt[0]['cm_total'];
            }
        }

        $date_array = array_unique($date_array);
        $timeArray = array_unique($timeArray2);

        $this->set('companyid', $client);
        $this->set('data', $data2);
        $this->set('datetimeArray', $datetimeArray);
        $this->set('datearray', $date_array);
        $this->set('timearray', $timeArray);
    }
}

public function revenue_data_excel()
{
    $this->layout = 'user';

    // Handle the POST request
    

    if ($this->request->is("POST")) {
        $search = $this->request->data['AbandonReports'];

        $FromDate = $search['startdate'];
        $ToDate = $search['enddate'];

        if ($FromDate != '') {
            $FromDate = $FromDate . ' 00:00:00';
        }
        if ($ToDate != '') {
            $ToDate = $ToDate . ' 23:59:59';
        }

        while (strtotime($FromDate) < strtotime($ToDate)) {
            $date_array[] = $date_fetch = date("Y-m-d", strtotime("$FromDate"));

            $start_time_start = $FromDate;
            $event_date_start = date("Y-m-d 00:00:00", strtotime("$FromDate +24 hours"));
            $timeArray[] = $time_fetch = date("H", strtotime("$FromDate"));
            $NextDate = date("Y-m-d H:i:s", strtotime("$FromDate +24 hours"));
            $FromDate = $NextDate;

            $start_time_end = $NextDate;

            $ClientInfo = $this->RegistrationMaster->query("select t1.company_name,round(t2.cm_total,2) cm_total from registration_master t1 join billing_consume_daily t2 on t1.company_id=t2.client_id where t2.cm_date>='$start_time_start' and cm_date<='$start_time_end' group by t2.client_id order by t1.company_name");
            //print_r($ClientInfo); exit;
            foreach ($ClientInfo as $dt) {
                $timeLabel = $dt['t1']['company_name'];
                $dateLabel = $date_fetch;
                $datetimeArray[$dateLabel][] = $timeLabel;
                $timeArray2[] = $timeLabel;
                $data2[$dateLabel][$timeLabel]['Amount'] = $dt[0]['cm_total'];
            }
        }

        $date_array = array_unique($date_array);
        $timeArray = array_unique($timeArray2);

        $this->set('companyid', $client);
        $this->set('data', $data2);
        $this->set('datetimeArray', $datetimeArray);
        $this->set('datearray', $date_array);
        $this->set('timearray', $timeArray);
    }
}





}




?>