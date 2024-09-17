<?php
class OpdashController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('ClientCategory','UploadExistingBase','RegistrationMaster','vicidialCloserLog',
            'vicidialUserLog','CallMaster','CallRecord','CampaignName','CallMasterOut','ClientReportMaster',
            'AbandCallMaster','vicidialLog','PlanMaster','InitialInvoice','CostCenterMaster','BillMaster','BillingLedger','BalanceMaster','BillingMaster','AgentMaster');
	
	
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
    
    public function get_color($result,$figure,$variance,$view)
    {
        
        if(!empty($view))
        {
            //print_r($view);exit;
            echo $figure;exit;
        }
        
        $color="green";
        $variance2 = $variance*2;
        $v_value  =round($figure*$variance/100);
        $v_value2  =$figure*$variance*2/100;
        $diff_value = 0;
        if($result>$figure)
        {
            $diff_value = round($result-$figure);
        }
        else
        {
            $diff_value = round($figure-$result);
        }
        
        $diff_perc = round($diff_value*100/$figure);
        //echo $diff_perc;exit;
        //echo $diff_value; exit;
        //exit;
        if($diff_perc<=$variance)
        {
            $color="green";
        }
        else if($diff_perc<=$variance2)
        {
            $color="orange";
        }        
        else
        {
            $color="red";
        }
        return $color;            
    }
    //$this->Session->check("companyid")
    //$this->Session->write("role","admin");

    public function show_data1(){
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
           $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),
               'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
           $client = array('All'=>'All')+$client;
           $this->set('client',$client); 
        }

    
        $time = date('H:00:00');
        //$time = date('12:00:00');
        
        $qry_ben = "SELECT DISTINCT(`benchmark`) ben FROM tbl_benchmark1 tbm where atype='client'";
        $ben_arr = $this->RegistrationMaster->query($qry_ben);        
        $ben_list = array();
        
        foreach($ben_arr as $bend)
        {
            //print_r($ben); exit;
            $ben=$bend['tbm']['ben']; 
            $ben_list[$ben] = $ben;
        }
        
        $sel_ben_parts = "SELECT * FROM `tbl_benchmark_client` bmn WHERE `time`='$time'";
        $ben_parts_arr = $this->RegistrationMaster->query($sel_ben_parts);        
       
        
        $ben_avg_list = array();
        $parts_list = array('Login Time','Ready to Take Call time','No of calls','ACHT','Talk Time','Utilization','Primary LOV');
        $client_list = array();
        
        $dash_data = array();
        $client_executed = array();
        
        
        foreach($ben_parts_arr as $parts)
        {
            $client_id = $parts['bmn']['client_id'];
            $ben = $parts['bmn']['benchmark'];
            $ben_time_list[$client_id][$time][$ben] = $parts;
        }
        
        //print_r($ben_time_list);exit;
        
        foreach($ben_time_list as $client_id=>$timer)
        {
            
            $select_campaigns = "select campaignid campaign_name,company_name from registration_master where company_id ='$client_id' limit 1";
            $ClientInfo = $this->RegistrationMaster->query($select_campaigns);  
            $campaigns = $ClientInfo['0']['registration_master']['campaign_name'];
            $company_name = $ClientInfo['0']['registration_master']['company_name'];
            if(empty($campaigns))
            {
                continue;
            }
            
            foreach($timer as $time=>$parts)
            {
                $client_executed[] = $client_id;
                $qry = "SELECT t2.campaign_id, COUNT(*) `Total`,
                SEC_TO_TIME(LEFT(SUM(talk_sec)+SUM(pause_sec)+SUM(wait_sec)+SUM(dispo_sec),6)) AS TalkTime,
                SEC_TO_TIME(LEFT(SUM(dispo_sec),6))AS `dispotime`,SEC_TO_TIME(LEFT(SUM(dispo_sec),5)) `WrapTime`, 
                SUM(IF(t2.`user` !='VDCL',1,0)) `Answered`, SUM(IF(t2.`user` ='VDCL',1,0)) `Abandon`, 
                SUM(IF(t2.`user` !='VDCL',t1.length_in_sec,0)) `TotalAcht`,
                SUM(IF(t2.`user` !='VDCL' AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
                SUM(IF(t2.`user` !='VDCL' AND t2.queue_seconds<=10,1,0)) `WIthinSLATen`,
                SUM(IF(t2.`user` ='VDCL' AND t2.queue_seconds<=20,1,0)) `AbndWithinThresold`,
                SUM(IF(t2.`user` ='VDCL' AND t2.queue_seconds>20,1,0)) `AbndAfterThresold` FROM asterisk.vicidial_closer_log t2 
                LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid 
                LEFT JOIN asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid 
                WHERE  DATE(t2.call_date)=CURDATE() and Hour(t2.call_date) = Hour('$time')
                and t2.campaign_id in (".$campaigns.") and t2.term_reason!='AFTERHOURS' and t2.lead_id is not null";
                $this->vicidialCloserLog->useDbConfig = 'db2';
                $dt=$this->vicidialCloserLog->query($qry);


                $data['Inbound Calls']=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
                //echo "{$dt[0][0]['Answered']}+{$dt[0][0]['Abandon']} <br/>";
                $calls_count = $data['Handled']=$dt[0][0]['Answered'];
                $data['Calls Ans (20 Sec)']=$dt[0][0]['WIthinSLA'];
                $data['Calls Ans (10 Sec)']=$dt[0][0]['WIthinSLATen'];
                $data['Total Calls Abandoned']=$dt[0][0]['Abandon'];
                $data['Abnd Within (20)']=$dt[0][0]['AbndWithinThresold'];
                $data['Average Aband Time']='';
                $talk_time = $data['Total Talk time']=$dt[0][0]['TalkTime'];
                $SL20 = $data['SL - 20 sec - 80%']=round($dt[0][0]['WIthinSLA']*100/$data['Handled']);
                $SL10 = $data['SL - 10 sec - 90%']=round($dt[0][0]['WIthinSLATen']*100/$data['Handled']);
                //echo "{$dt[0][0]['WIthinSLATen']}*100/{$data['Handled']}";exit;
                $AL = $data['AL - 95%']=round($dt[0][0]['Answered']*100/$data['Inbound Calls']);
                //echo $AL;exit;
                //echo "{$dt[0][0]['Answered']}*100/{$data['Inbound Calls']}";exit;
                $data['AHT']=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
                $ACHT = $dt[0][0]['TotalAcht']/$dt[0][0]['Answered']; 
                $data['Call Rate'] = $rate;
                $data['Amount'] = round($dt[0][0]['Abandon']*$rate_per_sec*round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']),2);

                $live_agent_det = "select user from vicidial_live_agents vla where campaign_id in ({$campaigns})";
                $this->vicidialCloserLog->useDbConfig = 'db2';
                $live_agent_data=$this->vicidialCloserLog->query($live_agent_det);
                $agent_count = 0;
                $A=0;
                $B = 0;
                    
                foreach($live_agent_data as $la)
                {
                    $agent_count += 1; 
                    $park = "select user, count(*), sum(parked_sec) p From park_log where date(parked_time) =curdate() and Hour(parked_time) = Hour('$time') and channel_group in ('Dialdesk') and user='".$dt['t1']['user']."' group by user";
                    $this->vicidialCloserLog->useDbConfig = 'db2';
                    $park_time=$this->vicidialCloserLog->query($park);
                    $A+=$dt[0]['talk_sec']+$dt[0]['dispo_sec']+$park_time[0][0]['p'];
                    $B+=$dt[0]['talk_sec']+$dt[0]['dispo_sec']+$park_time[0][0]['p']+$dt[0]['wait_sec']+$dt[0]['pause_sec'];

                }
                $login_time = $B;
                $util += round($A/$B*100,2);
                    
                foreach($ben_arr as $bend)
                {
                    //print_r($ben); exit;
                    $ben=$bend['tbm']['ben'];
                    if($ben=='SKILL Against Forecast')
                    {                        
                        $color="green";
                        $variance = $parts['ACHT']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['ACHT']['bmn']['figure']; 
                        if($figure!=$ACHT)
                        {   
                            $newval = $ACHT-$figure;
                            $color = $this->get_color($ACHT,$figure,$variance,0);
                        }
                        $variance = $parts['Agent Count']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['Agent Count']['bmn']['figure'];
                        if($color!='red' && $figure!=$agent_count)
                        {
                            $newval = $agent_count-$figure;
                            $color = $this->get_color($agent_count,$figure,$variance,0);
                            //$dash_data[$ben]['red'][$client_id] =$client_id ;
                            //$dash_data[$ben]['color'] ="red" ;   
                        }
                        
                        $variance = $parts['Utilization']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['Utilization']['bmn']['figure'];
                        if($color!='red' && $figure!=$util)
                        {
                            $newval = $util-$figure;
                            $color = $this->get_color($util,$figure,$variance,0); 
                        }
                        $dash_data[$color][$ben][$client_id] =$company_name ;

                    }

                    else if($ben=='Inbound Calls')
                    {
                        $variance = $parts['Call Count']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['Call Count']['bmn']['figure'];
                        $newval = $calls_count-$figure;                        
                        $color = $this->get_color($calls_count,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name ;
                    }
                    else if($ben=='AL - 95%')
                    {
                        $variance = $parts['AL']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['AL']['bmn']['figure'];
                        $newval = $AL-$figure;
                        $color = $this->get_color($AL,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name ;
                    }
                    else if($ben=='AHT')
                    {
                        $variance = $parts['ACHT']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['ACHT']['bmn']['figure'];
                        $newval = $ACHT-$figure;
                        $color = $this->get_color($ACHT,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name ;
                    }
                    else if($ben=='SL - 10 sec - 90%')
                    {
                        $variance = $parts['SLA 10']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['SLA 10']['bmn']['figure'];
                        $newval = $SL10-$figure;
                        $color = $this->get_color($SL10,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name ;
                    }
                    else if($ben=='SL - 20 sec - 80%')
                    {
                        $variance = $parts['SLA 20']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['SLA 20']['bmn']['figure'];
                        $newval = $SL20-$figure;
                        $color = $this->get_color($SL20,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name ;
                    }
                    else if($ben=='Talk Time')
                    {
                        $variance = $parts['Talk Time']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['Talk Time']['bmn']['figure'];
                        $newval = $talk_time-$figure;
                        $color = $this->get_color($talk_time,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name ;
                    }
                    else if($ben=='RL - 98%')
                    {
                        $variance = $parts['RL']['bmn']['variance'];
                        $figure = $parts['Talk Time']['bmn']['figure'];
                        $variance2 = $variance*2;                        
                        $newval = $rl-$figure;
                        $color = $this->get_color($rl,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name ;
                    }
                    
                    else if($ben=='OB Call')
                    {
                        $variance2 = $variance*2; 
                        $figure = $parts['OB Call']['bmn']['figure'];
                        $newval = $ob_call-$figure;
                        $color = $this->get_color($ob_call,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name ;
                    }
                    
                    $ben_list[$ben] = $ben;
                }
            }
            
            
        }
        
        //print_r($dash_data);exit;
        
        $this->set('ben_list',$ben_list);
        $this->set('bnc_client_list',$dash_data);
        
        //starts works for agent panel
        
        $sel_ben_parts_ag = "SELECT * FROM `tbl_benchmark_agent` bmn WHERE `time`='$time'";
        $ben_parts_arr_ag = $this->RegistrationMaster->query($sel_ben_parts_ag);    
        
        
        $qry_ben_agent = "SELECT DISTINCT(`benchmark`) ben FROM tbl_benchmark1 tbm where atype='agent'";
        $ben_arr_agent = $this->RegistrationMaster->query($qry_ben_agent);        
        
        $ben_time_list_agent = array();
        foreach($ben_parts_arr_ag as $parts)
        {
            $user = $parts['bmn']['user'];
            $ben = $parts['bmn']['benchmark'];
            $ben_time_list_agent[$user][$time][$ben] = $parts;
        }
        
        //print_r($ben_time_list);exit;
        foreach($ben_arr_agent as $bend)
        { 
            $ben=$bend['tbm']['ben']; 
            $ben_ag_list[$ben] = $ben;
        }
        
        
        $dash_data2 = array();
        foreach($ben_time_list_agent as $ag=>$timer)
        {
            $qry="select t1.user,
                SUM(IF(t2.`user` !='VDCL',t2.length_in_sec,0)) `TotalAcht`,
                sum(if(t1.lead_id!='',1,0)) Answered,SUM(t1.talk_sec) `talktime`,SUM(t1.talk_sec) talk_sec , SUM(t1.dispo_sec) dispo_sec, SUM(if(t1.wait_sec>10000,0,wait_sec)) wait_sec, SUM(if(t1.pause_sec>10000,0,pause_sec)) pause_sec
                from asterisk.vicidial_agent_log t1 left join (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND
                date(parked_time)=curdate() AND hour(parked_time) = Hour('$time') GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
                LEFT JOIN asterisk.vicidial_closer_log t2 ON t1.uniqueid=t2.uniqueid     
                WHERE t1.user='$ag' and date(t1.event_time)=curdate() AND hour(t1.event_time)=hour('$start_time_end') and t1.campaign_id='DIALDESK' and t1.user_group='Dialdesk' "; 
            
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt=$this->vicidialCloserLog->query($qry);
            
            $park = "select user, count(*), sum(parked_sec) p From park_log where date(parked_time)=curdate() AND hour(parked_time) = Hour('$time') and channel_group in ('Dialdesk') and user='$user' group by user";
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $park_time=$this->vicidialCloserLog->query($park);
            
            $calls_count = $dt[0]['Answered'];
            $talk_time = $dt[0]['talktime'];
            $A=$dt[0]['talk_sec']+$dt[0]['dispo_sec']+$park_time[0][0]['p'];
            $B=$dt[0]['talk_sec']+$dt[0]['dispo_sec']+$park_time[0][0]['p']+$dt[0]['wait_sec']+$dt[0]['pause_sec'];
            $ACHT = $dt[0][0]['TotalAcht']/$dt[0][0]['Answered']; 
            $util += round($A/$B*100,2);
            
            
            $agent_name = $ag;
            
            
            foreach($timer as $time=>$parts)
            {
                foreach($ben_arr_agent as $bend)
                {
                    $ben=$bend['tbm']['ben']; 
                
                    if($ben=='Login Time')
                    {
                        $figure = $parts['Login Time']['bmn']['figure'];
                        $newval = $login_time-$figure;                        
                        $color = $this->get_color($login_time,$figure,$variance,0);
                        $dash_data2[$color][$ben][$agent_name] =$agent_name ;
                    }
                    else if($ben=='Ready to Take Call time')
                    {

                    }
                    else if($ben=='No of calls')
                    {

                    }
                    else if($ben=='ACHT')
                    {
                        $figure = 300;
                        $variance = $parts['ACHT']['bmn']['variance'];
                        $color = $this->get_color($ACHT,$figure,$variance,0);
                        $dash_data2[$color][$ben][$agent_name] =$agent_name ;
                    }
                    else if($ben=='Talk Time')
                    {
                        $figure = 300;
                        $variance = $parts['Talk Time']['bmn']['variance'];
                        $color = $this->get_color($talk_time,$figure,$variance,0);
                        $dash_data2[$color][$ben][$agent_name] =$agent_name ;
                    }
                    else if($ben=='Utilization')
                    {
                        $figure = 300;
                        $variance = $parts['Utilization']['bmn']['Utilization'];
                        $color = $this->get_color($util,$figure,$variance,0);
                        $dash_data2[$color][$ben][$agent_name] =$agent_name ;
                    }
                    else if($ben=='Primary LOV')
                    {

                    }
                    
                }
                
            }
        }
        
        $this->set('ben_ag_list',$ben_ag_list);
        $this->set('bnc_agent_list',$dash_data2);
        
    }
    
    public function show_data2(){
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
           $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),
               'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
           $client = array('All'=>'All')+$client;
           $this->set('client',$client); 
        }

    
        $time = date('H:00:00');
        //$time = date('12:00:00');
        
        $qry_ben = "SELECT DISTINCT(`benchmark`) ben FROM tbl_benchmark1 tbm where atype='client'";
        $ben_arr = $this->RegistrationMaster->query($qry_ben);
        $ben_list = array();
        
        foreach($ben_arr as $bend)
        {
            //print_r($ben); exit;
            $ben=$bend['tbm']['ben']; 
            $ben_list[$ben] = $ben;
        }
        
        $sel_ben_parts = "SELECT * FROM `tbl_benchmark_client` bmn ";
        $ben_parts_arr = $this->RegistrationMaster->query($sel_ben_parts);
       
        
        $ben_avg_list = array();
        #$parts_list = array('Login Time','Ready to Take Call time','No of calls','ACHT','Talk Time','Utilization','Primary LOV');
        $client_list = array();
        
        $dash_data = array();
        $client_executed = array();
        
        
        foreach($ben_parts_arr as $parts)
        {
            $client_id = $parts['bmn']['client_id'];
            $ben = $parts['bmn']['benchmark'];
            $ben_time_list[$client_id][$time][$ben] = $parts;
        }
        
        //print_r($ben_time_list);exit;
        
        foreach($ben_time_list as $client_id=>$timer)
        {
            
            $select_campaigns = "select campaignid campaign_name,company_name from registration_master where company_id ='$client_id' limit 1";
            $ClientInfo = $this->RegistrationMaster->query($select_campaigns);
            $campaigns = $ClientInfo['0']['registration_master']['campaign_name'];
            $company_name = $ClientInfo['0']['registration_master']['company_name'];
            if(empty($campaigns))
            {
                continue;
            }
            
            #print_r($timer);die;
            foreach($timer as $time=>$parts)
            {
                #echo $time;die;
                $client_executed[] = $client_id;
                $qry = "SELECT t2.campaign_id, COUNT(*) `Total`,
                SEC_TO_TIME(LEFT(SUM(talk_sec)+SUM(pause_sec)+SUM(wait_sec)+SUM(dispo_sec),6)) AS TalkTime,
                SEC_TO_TIME(LEFT(SUM(dispo_sec),6))AS `dispotime`,SEC_TO_TIME(LEFT(SUM(dispo_sec),5)) `WrapTime`, 
                SUM(IF(t2.`user` !='VDCL',1,0)) `Answered`, SUM(IF(t2.`user` ='VDCL',1,0)) `Abandon`, 
                SUM(IF(t2.`user` !='VDCL',t1.length_in_sec,0)) `TotalAcht`,
                SUM(IF(t2.`user` !='VDCL' AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
                SUM(IF(t2.`user` !='VDCL' AND t2.queue_seconds<=10,1,0)) `WIthinSLATen`,
                SUM(IF(t2.`user` ='VDCL' AND t2.queue_seconds<=20,1,0)) `AbndWithinThresold`,
                SUM(IF(t2.`user` ='VDCL' AND t2.queue_seconds>20,1,0)) `AbndAfterThresold` FROM asterisk.vicidial_closer_log t2 
                LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid 
                LEFT JOIN asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid 
                WHERE  DATE(t2.call_date)=CURDATE() and Hour(t2.call_date) = Hour('$time')
                and t2.campaign_id in (".$campaigns.") and t2.term_reason!='AFTERHOURS' and t2.lead_id is not null";
                $this->vicidialCloserLog->useDbConfig = 'db2';
                $dt=$this->vicidialCloserLog->query($qry);

                $data['Inbound Calls']=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
                //echo "{$dt[0][0]['Answered']}+{$dt[0][0]['Abandon']} <br/>";
                $calls_count = $data['Handled']=$dt[0][0]['Answered'];
                $data['Calls Ans (20 Sec)']=$dt[0][0]['WIthinSLA'];
                $data['Calls Ans (10 Sec)']=$dt[0][0]['WIthinSLATen'];
                $data['Total Calls Abandoned']=$dt[0][0]['Abandon'];
                $data['Abnd Within (20)']=$dt[0][0]['AbndWithinThresold'];
                $data['Average Aband Time']='';
                $talk_time = $data['Total Talk time']=$dt[0][0]['TalkTime'];
                $SL20 = $data['SL - 20 sec - 80%']=round($dt[0][0]['WIthinSLA']*100/$data['Handled']);
                $SL10 = $data['SL - 10 sec - 90%']=round($dt[0][0]['WIthinSLATen']*100/$data['Handled']);
                //echo "{$dt[0][0]['WIthinSLATen']}*100/{$data['Handled']}";exit;
                $AL = $data['AL - 95%']=round($dt[0][0]['Answered']*100/$data['Inbound Calls']);
                //echo $AL;exit;
                //echo "{$dt[0][0]['Answered']}*100/{$data['Inbound Calls']}";exit;
                $data['AHT']=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
                $ACHT = $dt[0][0]['TotalAcht']/$dt[0][0]['Answered']; 
                $data['Call Rate'] = $rate;
                $data['Amount'] = round($dt[0][0]['Abandon']*$rate_per_sec*round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']),2);

                $live_agent_det = "select user from vicidial_live_agents vla where campaign_id in ({$campaigns})";
                $this->vicidialCloserLog->useDbConfig = 'db2';
                $live_agent_data=$this->vicidialCloserLog->query($live_agent_det);
                $agent_count = 0;
                $A=0;
                $B = 0;
                    
                foreach($live_agent_data as $la)
                {
                    $agent_count += 1; 
                    $park = "select user, count(*), sum(parked_sec) p From park_log where date(parked_time) =curdate() and Hour(parked_time) = Hour('$time') and channel_group in ('Dialdesk') and user='".$dt['t1']['user']."' group by user";
                    $this->vicidialCloserLog->useDbConfig = 'db2';
                    $park_time=$this->vicidialCloserLog->query($park);
                    $A+=$dt[0]['talk_sec']+$dt[0]['dispo_sec']+$park_time[0][0]['p'];
                    $B+=$dt[0]['talk_sec']+$dt[0]['dispo_sec']+$park_time[0][0]['p']+$dt[0]['wait_sec']+$dt[0]['pause_sec'];

                }
                $login_time = $B;
                $util += round($A/$B*100,2);
                    
                foreach($ben_arr as $bend)
                {
                    #print_r($ben); exit;
                    $ben=$bend['tbm']['ben'];
                    if($ben=='SKILL Against Forecast')
                    {    
                                      
                        $color="green";
                        #print_r($parts['SKILL Against Forecast']);
                        #echo "<br>";
                        $variance = $parts['SKILL Against Forecast']['bmn']['variance'];
                        #echo "<br>";
                        $variance2 = $variance*2;
                        $figure = $parts['ACHT']['bmn']['figure'];
                        if($figure!=$ACHT)
                        {   
                            $newval = $ACHT-$figure;
                            $color = $this->get_color($ACHT,$figure,$variance,0);
                        }
                        $variance = $parts['Agent Count']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['Agent Count']['bmn']['figure'];
                        if($color!='red' && $figure!=$agent_count)
                        {
                            $newval = $agent_count-$figure;
                            $color = $this->get_color($agent_count,$figure,$variance,0);
                            //$dash_data[$ben]['red'][$client_id] =$client_id ;
                            //$dash_data[$ben]['color'] ="red" ;   
                        }
                        
                        $variance = $parts['Utilization']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['Utilization']['bmn']['figure'];
                        if($color!='red' && $figure!=$util)
                        {
                            $newval = $util-$figure;
                            $color = $this->get_color($util,$figure,$variance,0); 
                        }
                        $dash_data[$color][$ben][$client_id] = $company_name ;

                    }
                    else if($ben=='Inbound Calls')
                    {
                        $variance = $parts['No of calls']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['No of calls']['bmn']['figure']; 
                        $newval = $calls_count-$figure;
                        $color = $this->get_color($calls_count,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] = $company_name." variance=>".$variance." figure=>".$figure ;
                    }
                    else if($ben=='AL - 95%')
                    {
                        $variance = $parts['AL - 95%']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['AL - 95%']['bmn']['figure'];
                        $newval = $AL-$figure;
                        $color = $this->get_color($AL,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name;
                    }
                    else if($ben=='AHT')
                    {
                        $variance = $parts['AHT']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['AHT']['bmn']['figure'];
                        $newval = $ACHT-$figure;
                        $color = $this->get_color($ACHT,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name ;
                    }
                    else if($ben=='SL - 10 sec - 90%')
                    {
                        #print_r($parts);die;
                        $variance = $parts['SL - 10 sec - 90%']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['SL - 10 sec - 90%']['bmn']['figure'];
                        $newval = $SL10-$figure;
                        $color = $this->get_color($SL10,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name ;
                    }
                    else if($ben=='SL - 20 sec - 80%')
                    {
                        $variance = $parts['SL - 20 sec - 80%']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['SL - 20 sec - 80%']['bmn']['figure'];
                        $newval = $SL20-$figure;
                        $color = $this->get_color($SL20,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name ;
                    }
                    else if($ben=='Talk Time')
                    {
                        $variance = $parts['Talk Time']['bmn']['variance'];
                        $variance2 = $variance*2;
                        $figure = $parts['Talk Time']['bmn']['figure'];
                        $newval = $talk_time-$figure;
                        $color = $this->get_color($talk_time,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name ;
                    }
                    else if($ben=='RL - 98%')
                    {
                        $variance = $parts['RL - 98%']['bmn']['variance'];
                        $figure = $parts['RL - 98%']['bmn']['figure'];
                        $variance2 = $variance*2;                        
                        $newval = $rl-$figure;
                        $color = $this->get_color($rl,$figure,$variance,0);
                        $dash_data[$color][$ben][$client_id] =$company_name ;
                    }
                    
                    // else if($ben=='OB Call')
                    // {
                    //     $variance2 = $variance*2; 
                    //     $figure = $parts['OB Call']['bmn']['figure'];
                    //     $newval = $ob_call-$figure;
                    //     $color = $this->get_color($ob_call,$figure,$variance,0);
                    //     $dash_data[$color][$ben][$client_id] =$company_name ;
                    // }
                    
                    $ben_list[$ben] = $ben;
                }
            }
            
            
        }
        
        //print_r($dash_data);exit;
        
        $this->set('ben_list',$ben_list);
        $this->set('bnc_client_list',$dash_data);
        
        //starts works for agent panel
        
        // $sel_ben_parts_ag = "SELECT * FROM `tbl_benchmark_agent` bmn ";
        // $ben_parts_arr_ag = $this->RegistrationMaster->query($sel_ben_parts_ag);
        $sel_ben_parts_ag = "SELECT * FROM `vicidial_live_agents` bmn WHERE STATUS='Ready'";
        $this->vicidialCloserLog->useDbConfig = 'db2';
        $ben_parts_arr_ag=$this->vicidialCloserLog->query($sel_ben_parts_ag);
        
        #print_r($ben_parts_arr_ag);die;
        $qry_ben_agent = "SELECT DISTINCT(`benchmark`) ben FROM tbl_benchmark1 tbm where atype='agent'";
        $ben_arr_agent = $this->RegistrationMaster->query($qry_ben_agent);

        $ben_time_list_agent = array();
        foreach($ben_parts_arr_ag as $parts)
        {
            $user = $parts['bmn']['user'];
            $ben = $parts['bmn']['benchmark'];
            $ben_time_list_agent[$user][$time][$ben] = $parts;
        }
        
        //print_r($ben_time_list);exit;
        foreach($ben_arr_agent as $bend)
        { 
            $ben=$bend['tbm']['ben']; 
            $ben_ag_list[$ben] = $ben;
        }
        
        
        $dash_data2 = array();
        
        foreach($ben_time_list_agent as $ag=>$timer)
        {
            $qry="select t1.user,
                SUM(IF(t2.`user` !='VDCL',t2.length_in_sec,0)) `TotalAcht`,
                sum(if(t1.lead_id!='',1,0)) Answered,SUM(t1.talk_sec) `talktime`,SUM(t1.talk_sec) talk_sec , SUM(t1.dispo_sec) dispo_sec, SUM(if(t1.wait_sec>10000,0,wait_sec)) wait_sec, SUM(if(t1.pause_sec>10000,0,pause_sec)) pause_sec
                from asterisk.vicidial_agent_log t1 left join (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND
                date(parked_time)=curdate() AND hour(parked_time) = Hour('$time') GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid
                LEFT JOIN asterisk.vicidial_closer_log t2 ON t1.uniqueid=t2.uniqueid     
                WHERE t1.user='$user' and date(t1.event_time)=curdate() AND hour(t1.event_time)=hour('$time') and t1.campaign_id='DIALDESK' and t1.user_group='Dialdesk' ";
            
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt=$this->vicidialCloserLog->query($qry);

            $park = "select user, count(*), sum(parked_sec) p From park_log where date(parked_time)=curdate() AND hour(parked_time) = Hour('$time') and channel_group in ('Dialdesk') and user='$user' group by user";
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $park_time=$this->vicidialCloserLog->query($park);
            
            $calls_count = $dt[0]['Answered'];
            $talk_time = $dt[0]['talktime'];
            $A=$dt[0]['talk_sec']+$dt[0]['dispo_sec']+$park_time[0][0]['p'];
            $B=$dt[0]['talk_sec']+$dt[0]['dispo_sec']+$park_time[0][0]['p']+$dt[0]['wait_sec']+$dt[0]['pause_sec'];
            $ACHT = $dt[0][0]['TotalAcht']/$dt[0][0]['Answered']; 
            $util += round($A/$B*100,2);
            
            
            $agent_name = $ag;
            
            
            foreach($timer as $time=>$parts)
            {
                foreach($ben_arr_agent as $bend)
                {
                    $ben=$bend['tbm']['ben'];
                
                    if($ben=='Login Time')
                    {
                        $figure = $parts['Login Time']['bmn']['figure'];
                        $newval = $login_time-$figure;                        
                        $color = $this->get_color($login_time,$figure,$variance,0);
                        $dash_data2[$color][$ben][$agent_name] =$agent_name;
                    }
                    else if($ben=='Ready to Take Call time')
                    {
                        
                    }
                    else if($ben=='No of calls')
                    {

                    }
                    else if($ben=='ACHT')
                    {
                        #$figure = 300;
                        #print_r($parts);
                        #echo "<br>";
                        $figure = $parts['ACHT']['bmn']['figure'];
                        #echo "<br>";
                        $variance = $parts['ACHT']['bmn']['variance'];
                        #echo "$ACHT,$figure,$variance,0";die;
                        $color = $this->get_color($ACHT,$figure,$variance,0);
                        $dash_data2[$color][$ben][$agent_name] =$agent_name ;
                    }
                    else if($ben=='Talk Time')
                    {
                        #$figure = 300;
                        $variance = $parts['Talk Time']['bmn']['variance'];
                        $color = $this->get_color($talk_time,$figure,$variance,0);
                        $dash_data2[$color][$ben][$agent_name] =$agent_name ;
                    }
                    else if($ben=='Utilization')
                    {
                        #$figure = 300;
                        $variance = $parts['Utilization']['bmn']['Utilization'];
                        $color = $this->get_color($util,$figure,$variance,0);
                        $dash_data2[$color][$ben][$agent_name] =$agent_name;
                    }
                    else if($ben=='Primary LOV')
                    {

                    }
                    
                }
                
            }
        }
        
        $this->set('ben_ag_list',$ben_ag_list);
        $this->set('bnc_agent_list',$dash_data2);
        
    }
    
    

    
    public function show_data(){
        $this->layout='user';
        if($this->Session->read('role') =="admin"){
           $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
           $client = array('All'=>'All')+$client;
           $this->set('client',$client); 
       }

       //$ClientInfo = $this->RegistrationMaster->query("SELECT Company_name as Company_name,campaignid as campaign_name,company_id FROM `registration_master` WHERE company_id='339' and `status`='A' and is_dd_client='1' order by Company_name asc "); 
       $ClientInfo = $this->RegistrationMaster->query("SELECT Company_name as Company_name,campaignid as campaign_name,company_id FROM `registration_master` WHERE  `status`='A' and is_dd_client='1' order by Company_name asc "); 

       
       $bnc_client_list = array();
       $benchmark_color_green = array();
       $benchmark_color_orange = array();
       $benchmark_color_red = array();
       $today_total = array();
       $total_today_new = array();
       foreach($ClientInfo as $v)
       {
        //print_r($v);die;
        if(empty($v['registration_master']['campaign_name']))
        {
            continue;
        }
             $ClientInf = $v['registration_master']['Company_name']; 
            $client_id  = $v['registration_master']['company_id'];
            //$CampInf[$ClientInf][] = $v['i']['campaign_name']; 

             $qry = "SELECT t2.campaign_id, COUNT(*) `Total`,
            SEC_TO_TIME(LEFT(SUM(talk_sec)+SUM(pause_sec)+SUM(wait_sec)+SUM(dispo_sec),6)) AS TalkTime,
            SEC_TO_TIME(LEFT(SUM(dispo_sec),6))AS `dispotime`,SEC_TO_TIME(LEFT(SUM(dispo_sec),5)) `WrapTime`, 
            SUM(if(t2.`user` !='VDCL',1,0)) `Answered`, SUM(if(t2.`user` ='VDCL',1,0)) `Abandon`, 
            SUM(IF(t2.`user` !='VDCL',t1.length_in_sec,0)) `TotalAcht`,
            SUM(IF(t2.`user` !='VDCL' AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
            SUM(IF(t2.`user` !='VDCL' AND t2.queue_seconds<=10,1,0)) `WIthinSLATen`,
            SUM(IF(t2.`user` ='VDCL' AND t2.queue_seconds<=20,1,0)) `AbndWithinThresold`,
            SUM(IF(t2.`user` ='VDCL' AND t2.queue_seconds>20,1,0)) `AbndAfterThresold` FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid WHERE  date(t2.call_date)=curdate()
            and t2.campaign_id in (".$v[0]['registration_master']['campaign_name'].") and t2.term_reason!='AFTERHOURS' and t2.lead_id is not null"; 
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
        $data['Inbound Calls']=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
        //echo "{$dt[0][0]['Answered']}+{$dt[0][0]['Abandon']} <br/>";
        $data['Handled']=$dt[0][0]['Answered'];
        $data['Calls Ans (20 Sec)']=$dt[0][0]['WIthinSLA'];
        $data['Calls Ans (10 Sec)']=$dt[0][0]['WIthinSLATen'];
        $data['Total Calls Abandoned']=$dt[0][0]['Abandon'];
        $data['Abnd Within (20)']=$dt[0][0]['AbndWithinThresold'];
        $data['Average Aband Time']='';
        $data['Total Talk time']=$dt[0][0]['TalkTime'];
        $data['SL - 20 sec - 80%']=round($dt[0][0]['WIthinSLA']*100/$data['Handled']);
        $data['SL - 10 sec - 90%']=round($dt[0][0]['WIthinSLATen']*100/$data['Handled']);
        //echo "{$dt[0][0]['WIthinSLATen']}*100/{$data['Handled']}";exit;
        $data['AL - 95%']=round($dt[0][0]['Answered']*100/$data['Inbound Calls']);
        //echo "{$dt[0][0]['Answered']}*100/{$data['Inbound Calls']}";exit;
        $data['AHT']=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
        $data['ACHT'] = $dt[0][0]['TotalAcht'];
        $data['Call Rate'] = $rate;
        $data['Amount'] = round($dt[0][0]['Abandon']*$rate_per_sec*round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']),2);


        $total=$dt[0][0]['Answered']+ $dt[0][0]['Abandon'];
        $tatlahct = $tatlahct+ $dt[0][0]['TotalAcht'];
        $Answered=$Answered + $dt[0][0]['Answered'];
        $Offered=$Offered + $dt[0][0]['Inbound Calls'];
        $TotalCall+=$total;
	    $i++;   

        $total_today_new['Inbound Calls']['Total_today'] += $data['Inbound Calls'];
        $total_today_new['AL - 95%']['Total_today'] += $data['AL - 95%'];
        $total_today_new['AHT']['Total_today'] += $data['AHT'];
        $total_today_new['ACHT']['Total_today'] += $data['ACHT']; 

        $total_lbl_list['Answered'] += $dt[0][0]['Answered'];
        $total_lbl_list['Abandon'] += $dt[0][0]['Abandon'];
        $total_lbl_list['WIthinSLA'] += $dt[0][0]['WIthinSLA'];
        $total_lbl_list['WIthinSLATen'] += $dt[0][0]['WIthinSLATen'];
        $total_lbl_list['AbndWithinThresold'] += $dt[0][0]['AbndWithinThresold'];
        $total_lbl_list['TalkTime'] += $dt[0][0]['TalkTime'];
        $total_lbl_list['TotalAcht'] += $dt[0][0]['TotalAcht'];
        


        $benchmark = $this->RegistrationMaster->query("SELECT * from  tbl_benchmark where ClientId='$client_id' ");
        //$benchmark = $this->RegistrationMaster->query("SELECT * from  tbl_benchmark where ClientId='$client_id' and benchmark='SL - 10 sec - 90%'");
        foreach($benchmark as $bench)
        {
            $label = $bench['tbl_benchmark']['benchmark'];
            $calc = $bench['tbl_benchmark']['calc'];
            $green = $bench['tbl_benchmark']['Green'];
            $orange = $bench['tbl_benchmark']['Orange'];
            $red = $bench['tbl_benchmark']['Red'];

            if($calc == "bt")
            {
                #$data[$label]['Today'] += 1;
                //echo "$orange >= {$data[$label]}";exit;
                if (empty($data[$label]) || $red >= $data[$label]) {
                    $benchmark_color_red[$label] = "red";
                    $bnc_client_list['red'][$label][$ClientInf] = $ClientInf;
                    $today_total[$label]['Today'] += 1;
                }
                elseif ($orange >= $data[$label]) {
                    $benchmark_color_orange[$label] = "orange";
                    $bnc_client_list['orange'][$label][$ClientInf] = $ClientInf;
                    $today_total[$label]['Today'] += 1;
                } 
                else {
                    $benchmark_color_green[$label] = "green";
                    $bnc_client_list['green'][$label][$ClientInf] = $ClientInf;
                    $today_total[$label]['Today'] += 1;
                } 

                
            }else if($calc == "tb")
            {
                if ($green <= $data[$label]) {
                    $benchmark_color_green[$label] = "green";
                    $bnc_client_list['green'][$label][$ClientInf] = $ClientInf;
                    $today_total[$label]['Today'] += 1;
                } elseif ($orange <= $data[$label]) {
                    $benchmark_color_orange[$label] = "orange";
                    $bnc_client_list['orange'][$label][$ClientInf] = $ClientInf;
                    $today_total[$label]['Today'] += 1;
                } else {
                    $benchmark_color_red[$label] = "red";
                    $bnc_client_list['red'][$label][$ClientInf] = $ClientInf;
                    $today_total[$label]['Today'] += 1;
                }
                
            }
            $benchmark_key[$label] = $label;
            //print_r($bnc_client_list);
        }

        //print_r($bnc_client_list);
        }

        //exit;
        #print_r($benchmark_color);
        #echo "h---";die;
        // print_r($data);
        // echo "<Br>";
        // print_r($total_today_new);die;




        $starttime = date('Y-m-d');
        $start_time_start=strtotime($starttime);
        $start_time_end=date("Y-m-d H:i:s",strtotime("$starttime +24 hours"));
        $qry="select t1.user,sum(if(t1.lead_id!='',1,0)) Answered,SUM(t1.talk_sec) `talktime`,SUM(t1.talk_sec) talk_sec , SUM(t1.dispo_sec) dispo_sec, SUM(if(t1.wait_sec>10000,0,wait_sec)) wait_sec, SUM(if(t1.pause_sec>10000,0,pause_sec)) pause_sec from asterisk.vicidial_agent_log t1 left join (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND date(parked_time)=curdate() GROUP BY uniqueid) t3 ON t1.uniqueid=t3.uniqueid WHERE t1.user!='VDCL' and date(event_time)=curdate() and t1.campaign_id='DIALDESK' and t1.user_group='Dialdesk' group by t1.user"; 
       
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
                  $agent_name = $Userinfo['0']['agent_master']['displayname'];
       
               $timeLabel=$dt['t1']['user'];
               $dateLabel=$date_fetch;
               $datetimeArray[$dateLabel][]=$timeLabel;
               $timeArray2[] = $timeLabel;
               $park = "select user, count(*), sum(parked_sec) p From park_log where date(parked_time) =curdate() and channel_group in ('Dialdesk') and user='".$dt['t1']['user']."' group by user";
               $this->vicidialCloserLog->useDbConfig = 'db2';
               $park_time=$this->vicidialCloserLog->query($park);
              // print_r($park_time[0][0]['p']); 
               //$data[$dateLabel][$timeLabel]['Total'] = $dt[0][0]['Total'];
               $data2['No of  calls'] = $dt[0]['Answered'];
               $data2['Talk Time'] = $dt[0]['talktime'];
               $A=$dt[0]['talk_sec']+$dt[0]['dispo_sec']+$park_time[0][0]['p'];
              //echo "<br>";
              //echo $dt['t1']['user']." T "
                $B=$dt[0]['talk_sec']+$dt[0]['dispo_sec']+$park_time[0][0]['p']+$dt[0]['wait_sec']+$dt[0]['pause_sec'];
                //"<br>";
               $data2['Utilization'] = round($A/$B*100,2);
               $data2['Productive'] = $A;
               $data2['Login Time'] = $B;
               
               $data2['Ready to Take Call time'] = $dt[0]['wait_sec'];

               $total_lbl_list2['No of  calls'] += $dt[0]['Answered'];
               $total_lbl_list2['Talk Time'] += $dt[0]['talktime'];
               $total_lbl_list2['Utilization'] += round($A/$B*100,2);
               $total_lbl_list2['Productive'] += $A;
               $total_lbl_list2['Login Time'] += $B;
               $total_lbl_list2['Ready to Take Call time'] += $dt[0]['wait_sec'];
                

               $benchmark2 = $this->RegistrationMaster->query("SELECT * from  tbl_benchmark where ClientId='0'  ");
              // print_r($benchmark2);
               //$benchmark = $this->RegistrationMaster->query("SELECT * from  tbl_benchmark where ClientId='$client_id' and benchmark='SL - 10 sec - 90%'");
               foreach($benchmark2 as $bench)
               {
                   $label = $bench['tbl_benchmark']['benchmark'];
                   $calc = $bench['tbl_benchmark']['calc'];
                   $green = $bench['tbl_benchmark']['Green'];
                   $orange = $bench['tbl_benchmark']['Orange'];
                   $red = $bench['tbl_benchmark']['Red'];
       
                   if($calc == "bt")
                   {
                       #$data[$label]['Today'] += 1;
                       //echo "$red >= {$data2[$label]}";exit;
                       if (empty($data2[$label]) || $red >= $data2[$label]) {
                           $benchmark_color_red[$label] = "red";
                           $bnc_client_list['red'][$label][$agent_name] = "$agent_name({$data2['Utilization']})";
                           $today_total[$label]['Today'] += 1;
                       }
                       elseif ($orange >= $data2[$label]) {
                           $benchmark_color_orange[$label] = "orange";
                           $bnc_client_list['orange'][$label][$agent_name] = "$agent_name({$data2['Utilization']})";
                           $today_total[$label]['Today'] += 1;
                       } 
                       else {
                           $benchmark_color_green[$label] = "green";
                           $bnc_client_list['green'][$label][$agent_name] = "$agent_name({$data2['Utilization']})";
                           $today_total[$label]['Today'] += 1;
                       } 
       
                       
                   }else if($calc == "tb")
                   {
                    //echo "$green >= {$data2[$label]}";
                    //echo $agent_name;
                    //echo '<br/>';
                       if ($green >= $data2[$label]) {
                           $benchmark_color_green[$label] = "green";
                           $bnc_client_list['green'][$label][$agent_name] = "$agent_name({$data2['Utilization']})";
                           $today_total[$label]['Today'] += 1;
                       } elseif ($orange >= $data2[$label]) {
                           $benchmark_color_orange[$label] = "orange";
                           $bnc_client_list['orange'][$label][$agent_name] = "$agent_name({$data2['Utilization']})";
                           $today_total[$label]['Today'] += 1;
                       } else {
                           $benchmark_color_red[$label] = "red";
                           $bnc_client_list['red'][$label][$agent_name] = "$agent_name({$data2['Utilization']})";
                           $today_total[$label]['Today'] += 1;
                       }
                       
                   }
                   $benchmark_key[$label] = $label;
                   //print_r($bnc_client_list);
               }



              }
              //print_r($total_lbl_list);exit;
              //exit;
        $this->set('total_today_new',$total_today_new);
        $this->set('today_total',$today_total);
        $this->set('bnc_client_list',$bnc_client_list);
        $this->set('benchmark_key',$benchmark_key);
        $this->set('benchmark_color_green',$benchmark_color_green);
        $this->set('benchmark_color_orange',$benchmark_color_orange);
        $this->set('benchmark_color_red',$benchmark_color_red);
        $this->set('benchmark',$benchmark);
        $this->set('data',$data); 
        $this->set('total_lbl_list',$total_lbl_list); 
        $this->set('data2',$total_lbl_list2); 
        
        
        
    }

    public function al_sl_chart()
	{
		$this->layout='user';

        $this->setClientsAndAgents();
        $campaignIds = $this->getAllCampaignIds();

        #print_r($campaignIds);die;
        #$campaignIdString = implode(',', $campaignIds);
        $campaignIdCondition = "t2.campaign_id IN ($campaignIds)";
        #echo $campaignIdCondition;die;
        $dt = $this->getCallData($campaignIdCondition, 'Today');
        #print_r($dt);die;
        
        $agentIds = $this->getAllAgentIds();
        $agent_data = $this->getAgentUtilizationData($agentIds,'Today');

        if($this->request->is("POST"))
        {
            #print_r($this->request->data);die;

            $clientIds = $this->request->data['clientID'];
            $viewType = $this->request->data['view_type'];
            $agentIds = $this->request->data['agent_id'];
            $viewType2 = $this->request->data['view_type2'];

            if(!empty($clientIds))
            {
                $campaignIds = $this->getCampaignIds($clientIds);
                $campaignIdString = implode(',', $campaignIds);
                $campaignIdCondition = "t2.campaign_id IN ($campaignIdString)";
                $dt = $this->getCallData($campaignIdCondition, $viewType);
            }

            if(!empty($agentIds))
            {
                $agentIds = $this->getAgentIds($agentIds);
                $agentIdstring = implode(',', $agentIds);
                $agent_data = $this->getAgentUtilizationData($agentIdstring,$viewType2);
            }
            
            
            
        }

        $this->set('view_type',$viewType);
        $this->set('view_type2',$viewType2);

        $this->set('data', $dt);
        $this->set('agent_data', $agent_data);
        
        
	}


    function setClientsAndAgents()
    {
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
        $client = array('All'=>'All')+$client;
        $this->set('client',$client);

        $agent_arr = $this->AgentMaster->find('list',array('fields'=>array("username","displayname"),'conditions'=>array('status'=>'A'))); 
        $agent_arr = array('All'=>'All')+$agent_arr;
        $this->set('agent_arr',$agent_arr);

    }


    function getAllCampaignIds()
    {
        $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
        $clientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id  FROM `registration_master` WHERE `status`='A' AND is_dd_client='1'");
        return $clientInfo[0][0]['campaign_id'];
    }

    function getAllAgentIds()
    {
       // $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
        $agentInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(CONCAT('\'', username, '\'')) AS username FROM `agent_master` WHERE `status`='A'"); 
        return $agentInfo[0][0]['username'];
    }

    function getCampaignIds($clientArr)
    {
        $campaignIds = [];
        foreach ($clientArr as $client) {
            $query = ($client == "All") ? "
                SELECT GROUP_CONCAT(campaignid) AS campaign_id 
                FROM `registration_master` 
                WHERE `status`='A' AND is_dd_client='1'
            " : "
                SELECT GROUP_CONCAT(campaignid) AS campaign_id 
                FROM `registration_master` 
                WHERE company_id='$client' AND `status`='A' AND is_dd_client='1'
            ";
            $clientInfo = $this->RegistrationMaster->query($query);
            if (!empty($clientInfo[0][0]['campaign_id'])) {
                $campaignIds[] = $clientInfo[0][0]['campaign_id'];
            }
        }
        return $campaignIds;
    }

    function getAgentIds($agentArr)
    {
        $agentIds = [];
        foreach ($agentArr as $agent) {
            $query = ($agent == "All") ? "
            SELECT GROUP_CONCAT(CONCAT('\'', username, '\'')) AS username FROM `agent_master` WHERE `status`='A'
            " : "
            SELECT GROUP_CONCAT(CONCAT('\'', username, '\'')) AS username FROM `agent_master` WHERE `status`='A' and username='$agent'
            ";
            #echo $query;die;
            $clientInfo = $this->RegistrationMaster->query($query);
            if (!empty($clientInfo[0][0]['username'])) {
                $agentIds[] = $clientInfo[0][0]['username'];
            }
        }
        return $agentIds;
    }

    function getCallData($campaignIds, $viewType)
    {
        $dateCondition = ($viewType == "MTD") ? 
            "DATE(t2.call_date) BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()" : 
            "DATE(t2.call_date) = CURDATE()";

       // $campaignIdCondition =   "t2.campaign_id in(".$campaignIds.")";


         $query = "SELECT  SUM(IF(t2.`user` != 'VDCL', 1, 0)) AS `Answered`,  SUM(IF(t2.`user` = 'VDCL', 1, 0)) AS `Abandon`,
            SUM(IF(t2.`user` != 'VDCL' AND t2.queue_seconds <= 20, 1, 0)) AS `WithinSLA`, SUM(IF(t2.`user` != 'VDCL' AND t2.queue_seconds <= 10, 1, 0)) AS `WithinSLATen`
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.vicidial_agent_log t3 ON t2.uniqueid = t3.uniqueid 
            WHERE $dateCondition AND $campaignIds   AND t2.term_reason != 'AFTERHOURS'  AND t2.lead_id IS NOT NULL";
        $this->vicidialCloserLog->useDbConfig = 'db2';
        return $this->vicidialCloserLog->query($query);
    }


    function getAgentUtilizationData($agentIds,$viewType)
    {
        $wheretag = "";
        if($viewType =="MTD")
        {
            $wheretag .= "and DATE(event_time) BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()";
        }else{
            $wheretag .= "AND DATE(event_time) = CURDATE()";
        }
        #print_r($agentIds);die;
        $agentIdCondition = !empty($agentIds) ? "t1.user IN (" . $agentIds . ")" : "1=1";
        
        $query = "SELECT t1.user, SUM(t1.talk_sec) AS talk_sec,  SUM(t1.dispo_sec) AS dispo_sec,SUM(IF(t1.wait_sec > 10000, 0, wait_sec)) AS wait_sec,
        SUM(IF(t1.pause_sec > 10000, 0, pause_sec)) AS pause_sec FROM asterisk.vicidial_agent_log t1 WHERE $agentIdCondition  $wheretag ";
        #t1.user in ('IDC53213','IDC56050')
        $this->vicidialCloserLog->useDbConfig = 'db2';
        $agentData = $this->vicidialCloserLog->query($query);
        #print_r($agentData[0]);die;

        $A = $agentData[0][0]['talk_sec'] ;
        $B = $agentData[0][0]['talk_sec'] + $agentData[0][0]['dispo_sec'] + $agentData[0][0]['wait_sec'] + $agentData[0][0]['pause_sec'];
        $utilization['Utilization'] = round($A / $B * 100, 2);

        return $utilization;
    }
    

}
?>