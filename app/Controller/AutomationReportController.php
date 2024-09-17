<?php
	class AutomationReportController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler','Session');
	public $uses=array('RegistrationMaster','ClientCategory','CallMaster','FieldMaster','ObAllocationMaster','ObCampaignDataMaster','AbandCallMaster','VicidialListMaster','vicidialCloserLog');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
			'index',
			'export_corrective_report',
			'sla',
                        'process_wise',
                        'displayDates',
			'download');
    }
	
	public function index() 
    {

		$this->layout='user';
        $companyid   =   $this->Session->read('companyid');
		if($this->Session->read('role') == "admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
            $this->set('client',$client); 
        }

        if($this->request->is("POST"))
        {
            $data = $this->request->data['AutomationReport'];
            $startdate = $data['startdate'];
            $enddate = $data['enddate'];
            //print_r($data);die;
            $ClientId = $data['clientID'];
            $data1['ClientId'] = $ClientId;

            $data1['date(CallDate) >='] = $startdate;
            $data1['date(CallDate) <='] = $enddate;

            // $startdate=$startdate.' 00:00:00';
            // $enddate=$enddate.' 23:59:59';
             $datetimeArray = array();
             $dataArr = array();
             $tArr = $this->CallMaster->find('all',array('conditions' =>$data1));
          
            $format = 'd-F-Y';
                //$dates = array();
                $startdate=$startdate.' 00:00:00';
                $enddate=$enddate.' 23:59:59';
                $current = strtotime($startdate);
                $date2 = strtotime($enddate);
                $stepVal = '+1 day';
                while($current <= $date2) {
                   $dates = date($format, $current);
                   $datetimeArray[] = $dates;
                   $current = strtotime($stepVal, $current);
                   //    $dataArr[][][$dates];
                    foreach($tArr as $calldata)
                    {
                        //print_r($calldata);
                        $cur = strtotime($calldata['CallMaster']['CallDate']);
                        $dates1 = date($format, $cur);
                        if(empty($calldata['CallMaster']['CloseLoopCate1']))
                        {
                        $dataArr[$calldata['CallMaster']['Category2']]['open'][$dates1] +=1;
                        }
                        else
                        {
                        $dataArr[$calldata['CallMaster']['Category2']]['close'][$dates1] +=1; 
                        }
        
                        $dataArr[$calldata['CallMaster']['Category2']]['data']  +=1;
                        //print_r($dataArr);die;
                    }
                }

            $this->set('datetimeArray',$datetimeArray);
            $this->set('data',$dataArr);
            $this->set('companyid',$ClientId);
        }
	
	}

    public function sla() 
    {

		$this->layout='user';
        $companyid   =   $this->Session->read('companyid');
		if($this->Session->read('role') == "admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
            $this->set('client',$client); 
        }

        if($this->request->is("POST")){
            $campaignId ="t2.campaign_id in(". $this->Session->read('campaignid').",'DIALDESK')";
            $clientId   = $this->Session->read('companyid');
            
            $search     =$this->request->data['AutomationReport'];
           
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
            
            //$data['Offered %'][$dateLabel][$timeLabel]='';
            $total=$dt[0][0]['Answered']+$dt[0][0]['Abandon'];
            $data['Total Calls Recieved'][$dateLabel][$timeLabel]=$total;
            $data['Total Calls Answered'][$dateLabel][$timeLabel]=$dt[0][0]['Answered'];
            $data['Total Calls Abandoned'][$dateLabel][$timeLabel]=$dt[0][0]['Abandon'];
            
            $data['AHT (In Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['TotalAcht']/$dt[0][0]['Answered']);
                    
            $data['Calls Ans (within 20 Sec)'][$dateLabel][$timeLabel]=$dt[0][0]['WIthinSLA'];
            $data['Abnd Within Threshold'][$dateLabel][$timeLabel]=$dt[0][0]['AbndWithinThresold'];
            $data['Abnd After Threshold'][$dateLabel][$timeLabel]=$dt[0][0]['AbndAfterThresold'];
            $data['Ababdoned (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['Abandon']*100/$total);
            //$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$dt[0][0]['Answered']);
                    
                    
                    //$data['SL (%)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']/($total-$dt[0][0]['AbndWithinThresold'])*100);
                    
                    $data['SL% (20 Sec)'][$dateLabel][$timeLabel]=round($dt[0][0]['WIthinSLA']*100/$data['Total Calls Recieved'][$dateLabel][$timeLabel])."%";
                    
                    $data['AL%'][$dateLabel][$timeLabel]=round($dt[0][0]['Answered']/$data['Total Calls Recieved'][$dateLabel][$timeLabel]*100)."%";
                    
                    
            //$data['Agent Logged In'][$dateLabel][$timeLabel]=$usrDt['UserLoggedIn'];
            
            $TotalCall+=$total;
        }
        
             
    
        // foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { 
        //     $data['Offered %'][$dateLabel][$timeLabel]=round($data['Total Calls Recieved'][$dateLabel][$timeLabel]*100/$TotalCall);  
        // } }
            $this->set('data',$data);
             $this->set('datetimeArray',$datetimeArray);
            }
	
	}

    public function process_wise() 
    {

		$this->layout='user';
        $companyid   =   $this->Session->read('companyid');
		if($this->Session->read('role') == "admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
            $this->set('client',$client); 

        }

        if($this->request->is("POST")){
            $campaignId ="and t2.campaign_id in(". $this->Session->read('campaignid').")";
            $clientId   = $this->Session->read('companyid');
            
            $search     =$this->request->data['AutomationReport'];
           
                $Campagn  = $this->Session->read('campaignid');
                // $FromDate=$search['startdate'];
                // $ToDate=$search['enddate'];

                $FromDate = date('Y-m-01',strtotime('-1 Month'));
                //$ToDate   = date('Y-m-d');
                $ToDate   = date('Y-m-d', strtotime('last day of last month'));
               
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
    

            $view_date1 =" date(t2.call_date) between '$start_time_start' and '$start_time_end' ";
    
          $qry = "SELECT COUNT(*) `Total`,
            SUM(if(t2.`user` !='VDCL',1,0)) `Answered`,
            SUM(if(t2.`user` ='VDCL',1,0)) `Abandon`,
            date(t2.call_date) as gdate                            
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid 
            WHERE $view_date1 $campaignId and t2.phone_number in('09680193319','07884214478','08369692722','06239179641')  and t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL GROUP BY date(t2.call_date) ";

          $qry1 = "SELECT COUNT(*) `Total`,
            SUM(if(t2.`user` !='VDCL',1,0)) `Answered`,
            SUM(if(t2.`user` ='VDCL',1,0)) `Abandon`,
            date(t2.call_date) as gdate                            
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid 
            WHERE $view_date1 $campaignId and t2.phone_number in('08045994699','08042994299')  and t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL GROUP BY date(t2.call_date) ";

            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt=$this->vicidialCloserLog->query($qry);
            $dt1=$this->vicidialCloserLog->query($qry1);  
            
          $qry2 = "SELECT SUM(IF(TagStatus IS NULL,0,1)) Callback FROM aband_call_master 
            WHERE DATE(Calldate) BETWEEN '$start_time_start' AND '$start_time_end' and ClientId ='$clientId' GROUP BY DATE(Calldate),ClientId,CallType";

            $Abanddata=$this->AbandCallMaster->query($qry2);
            
             $timeLabel=date("d-M-Y",strtotime($start_time_start));
             $dateLabel=date("F-Y",strtotime($start_time_start));
             $datetimeArray[$dateLabel][]=$timeLabel;
            
            // //$data['Offered %'][$dateLabel][$timeLabel]='';
             $total=$dt[0][0]['Answered']+$dt1[0][0]['Answered'];

             $data['Everest Indiamart (Recieved Calls)'][$dateLabel][$timeLabel]=$dt1[0][0]['Answered'];
             $data['Everest Industries Toll Free (Recieved Calls)'][$dateLabel][$timeLabel] = $dt[0][0]['Answered'];

             $data['Total Recieved Calls'][$dateLabel][$timeLabel]=$total;

             $total1=$dt[0][0]['Abandon']+$dt1[0][0]['Abandon'];

             $data['Everest Indiamart (Abandoned Calls)'][$dateLabel][$timeLabel]=$dt1[0][0]['Abandon'];
             $data['Everest Industries Toll Free (Abandoned Calls)'][$dateLabel][$timeLabel] = $dt[0][0]['Abandon'];

             $data['Total Abandoned'][$dateLabel][$timeLabel]=$total1;

             $data['Call Back On Abandoned Calls'][$dateLabel][$timeLabel]=$Abanddata[0][0]['Callback'];

             $data['Grand Total'][$dateLabel][$timeLabel] = $dt[0][0]['Total']+$dt1[0][0]['Total']+$Abanddata[0][0]['Callback'];
          
        }

 //print_r($Abanddata);die;
            $this->set('data',$data);
             $this->set('datetimeArray',$datetimeArray);
            }
	
	}

    public function export_corrective_report()
    {
        if($this->request->is("POST"))
        {
            
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=corrective_report.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
       
        
            $data = $this->request->data['CorrectiveReport'];
            $startdate = $data['startdate'];
            $enddate = $data['enddate'];
            //print_r($data);die;
            $ClientId = $data['clientID'];
            $data1['ClientId'] = $ClientId;

            $data1['date(CallDate) >='] = $startdate;
            $data1['date(CallDate) <='] = $enddate;

            $tArr = $this->CallMaster->find('all',array('conditions' =>$data1,'order'=>array('CallMaster.Category3 ASC')));

            $dataArr = array();

            foreach($tArr as $calldata)
            {

                if(empty($calldata['CallMaster']['CloseLoopCate1']))
                {
                   $dataArr[$calldata['CallMaster']['Category3']][$calldata['CallMaster']['Category2']]['open'] +=1;
                }
                else
                {
                   $dataArr[$calldata['CallMaster']['Category3']][$calldata['CallMaster']['Category2']]['close'] +=1; 
                }

                $dataArr[$calldata['CallMaster']['Category3']][$calldata['CallMaster']['Category2']]['data'] = $calldata['CallMaster'];
                //print_r($dataArr);die;
            }
                
                
                ?>
            
                <table cellspacing="0" border="1">
                    <tr style="background-color:DarkGray;">
                        <th rowspan="2">Site</th>			
                        <th rowspan="2">Category</th>
                        <th rowspan="2">Total Corrections</th>
                        <th colspan="2" style="text-align: center;">Status</th>
                        <th rowspan="2">Remarks</th>   
                    </tr>
                    <tr style="background-color:DarkGray;">
                            
                            <th>Open</th>
                            <th>close</th>
                              
                    </tr>
                    <?php 
                           $grand_total_corr = 0;
                           foreach($dataArr as $key=>$value){  ?>
                            
                                
                                <?php $a=1;$total_corr=0;$total_open= 0;$total_close= 0; $col2_keys = array_keys($value);?>
                                <?php foreach($col2_keys as $key2){  ?>
                                    <tr>
                                    <?php if($a==1) { ?>
                                    <th rowspan="<?php echo count($value); ?>"><?php echo $key; ?></th>
                                    <?php $a=0; } ?>
                                    <th><?php echo $key2; ?></th>
                                    <td><?php $complaint = $value[$key2]['open']+$value[$key2]['close']; echo $complaint;?></td>
                                    <td><?php echo $value[$key2]['open']; ?></td>
                                    <td><?php echo $value[$key2]['close']; ?></td>
                                    <td><?php //echo wordwrap($value[$key2]['data']['Field21'],25,"<br>\n"); ?></td>
                                    
                                    </tr>
                                    <?php $total_open+=$value[$key2]['open'];
                                          $total_close+=$value[$key2]['close'];
                                          $total_corr += $complaint;
                                      }?>  
                                    
                                    <tr>
                                        <?php $phase_total = $total_close/$total_corr; ?>
                                            <th colspan="2">Total</th>
                                            <th><?php echo $total_corr; ?></th>
                                            <th><?php echo $total_open;?></th>
                                            <th><?php echo $total_close; ?></th>
                                            <th><?php echo number_format($phase_total);?></th>
                                            
                                    </tr>
                            
                                <?php $grand_total_corr += $total_corr;
                                      $grand_total_open += $total_open;
                                      $grand_total_close += $total_close;
                                      }?>    
                                    <tr>
                                            <th style="background-color:yellow;" colspan="2">Grand Total</th>
                                            <th style="background-color:yellow;"><?php echo $grand_total_corr; ?></th>
                                            <th style="background-color:yellow;"><?php echo $grand_total_open; ?></th>
                                            <th style="background-color:yellow;"><?php echo $grand_total_close; ?></th>
                                            <th style="background-color:yellow;"><?php $totalarr = $grand_total_close/$grand_total_corr; echo number_format($totalarr,2) ; ?></th>
                                    </tr>
                        								
                 
                </table> 

                <?php
        }
                
         die;   
    
    }

    // public function displayDates($date1, $date2, $format = 'd-m-Y') {
    //     $dates = array();
    //     $current = strtotime($date1);
    //     $date2 = strtotime($date2);
    //     $stepVal = '+1 day';
    //     while( $current <= $date2 ) {
    //        $dates[] = date($format, $current);
    //        $current = strtotime($stepVal, $current);
    //     }
    //     return $dates;
    //  }


        

	
}

?>