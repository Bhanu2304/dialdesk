<?php
class MisReportsController extends AppController
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
	$clientId = $this->Session->read('companyid');
        $report=array(
            'call_mis'=>'Call MIS',
            'call_detailed_mis'=>'Call Detailed MIS',
            'sales_mis'=>'Sales MIS',
            'sales_detailed_mis'=>'Sales Detailed MIS',
            'tat_mis'=>'TAT MIS',
            'tat_detailed_mis'=>'Tat Detailed MIS',
            'tagging_mis'=>'Tagging MIS',
            'tagging_detailed_mis'=>'Tagging Detailed MIS',
            'time_wise_mis'=>'Time Wise MIS',
            'agent_wise_mis'=>'Agent Wise MIS',
            'abend_call'=>'Abend Call',
            'answer_call'=>'Answer Call',
            'esclation_level_mis'=>'Esclation Level MIS',
            'esclation_level_detailed_mis'=>'Esclation Level Detailed MIS'
        );

        $this->set('report',$report);
    }
    
    
    
   public function export_call_mis(){
        $this->layout='user';
        if($this->request->is("POST")){
        $campaignId ="campaign_id in(". $this->Session->read('campaignid').")";
        $clientId   = $this->Session->read('companyid');
        
        $search     =$this->request->data['MisReports'];
            $Campagn  = $this->Session->read('campaignid');
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            
            //print_r($start_time_start); exit;
      $qry =  "SELECT COUNT(*) `Total`,
        SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
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
        SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
        SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
        SUM(IF(((t2.status IS NULL OR t2.status='DROP')
        AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
        SUM(IF(((t2.status IS NULL OR t2.status='DROP')
        AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
        FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
        WHERE date(t2.call_date) BETWEEN '$start_time_start' AND '$start_time_end' AND $campaignId and t2.term_reason!='AFTERHOURS' limit 1";
        
        $this->vicidialCloserLog->useDbConfig = 'db2';
        $call_excute= $this->vicidialCloserLog->query($qry);
        
       
        $TACC=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate) BETWEEN '$start_time_start' AND '$start_time_end' AND TagStatus IS NULL");
        
        
        
        $tot1=$call_excute[0][0];
  
        $answer =$tot1['Answered'];
        //$aband = $tot1['Abandon'];
        $aband = $TACC[0][0]['AbandCount'];
        $acht = $tot1['TotalAcht'];
        $total = $answer + $aband;
        
       
          
        $select = "select Category1,count(Category1)`count` from call_master where clientId='$clientId' AND date(CallDate) BETWEEN '$start_time_start' AND '$start_time_end' group by Category1";
        $categoryExecute=$this->CallRecord->query($select);
        
       
        
        $category = array(); $tot2 = 0;
        foreach($categoryExecute as $exerow){
            $row=$exerow['call_master'];
            $category[] = array($row['Category1'],$row['count'],'0%');
            $tot2 += $row['count'];
        }
        
        
        
   
        $qry1 = "SELECT date_format(t2.call_date,'%d-%b-%Y') `date`,date_format(t2.call_date,'%Y-%m-%d') `date1`,COUNT(*) `Total`,
        SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
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
        SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
        SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
        SUM(IF(((t2.status IS NULL OR t2.status='DROP')
        AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
        SUM(IF(((t2.status IS NULL OR t2.status='DROP')
        AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
        FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
        WHERE date(t2.call_date) BETWEEN '$start_time_start' AND '$start_time_end' AND $campaignId and t2.term_reason!='AFTERHOURS' group by date(t2.call_date)";
           
        
        
        $this->vicidialCloserLog->useDbConfig = 'db2';
        $call_details_detail_excute= $this->vicidialCloserLog->query($qry1);
        //print_r($call_details_detail_excute); exit;
        
        foreach($call_details_detail_excute as $row)
        {
            
            $TACC1=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate)='{$row[0]['date1']}' AND TagStatus IS NULL");
            $TABAND=$TACC1[0][0]['AbandCount'];
            
            $DateArr[] = $row[0]['date'];
          //$Arr['Receive'] = $row[0]['Answered']+$TABAND;
            $Arr['Receive'] = $row[0]['Answered']+$row[0]['Abandon'];
            $Arr['Answer']  = $row[0]['Answered'];
          //$Arr['Aband']   = $TABAND;
            $Arr['Aband']   = $row[0]['Abandon'];
            $Arr['Aht']     = round($row[0]['TotalAcht']/$row[0]['Answered']);
            $TotalArr[$row[0]['date']] = $Arr;
            $totalcount +=$Arr['Receive']; 
          //$Arr2['Receive'] += $row[0]['Answered']+$TABAND;
            $Arr2['Receive'] += $row[0]['Answered']+$row[0]['Abandon'];
            $Arr2['Answer']  += $row[0]['Answered'];
          //$Arr2['Aband']   += $TABAND;
            $Arr2['Aband']   += $row[0]['Abandon'];
            $Arr2['Aht']     += round($row[0]['TotalAcht']/$row[0]['Answered']);
        }

        
        
        $j=0;$jj=0;
        $value[$j][]  ="Summary";
        $value1[$jj][]  ="Summary";
        
        $value[$j][]="MTD";
        $value1[$jj][]="MTD";
        
        $value[$j][]="%";
        $value1[$jj][]="%";
        
        foreach($DateArr as $d)
        {
            $value[$j][] = $d;
        }
        
        
        $j=1;$jj = 1;
        $flag = true;
        foreach($Arr2 as $k=>$v)
        {
            
            $value[$j][]="TOTAL ".$k." CALLS";
            $value1[$jj][]="TOTAL ".$k." CALLS";
            
            $value[$j][] = $v;
            $value1[$jj][] = $v;
            
            if($flag)
            {$totalcount = $v; $flag = false;}
            $value[$j][]= round($v*100/$v,0)."%";
            $value1[$jj][] = round($v*100/$v,0);

            foreach($DateArr as $m)
            {
                $value[$j][]= $TotalArr[$m][$k];
            }
            $j++;$jj++;
        }
        //print_r($value); exit;
        // print_r($call_detail_excute); exit;
        $select = "SELECT DATE_FORMAT(cm.CallDate,'%d-%b-%Y') `date`,cm.Category1,COUNT(1) `count` FROM call_master cm 
                   where clientId='$clientId' AND date(CallDate) BETWEEN '$start_time_start' AND '$start_time_end' GROUP BY cm.Category1,DATE(cm.CallDate)";
        
        $call_detail_excute=$this->CallRecord->query($select);
        
        //print_r($call_detail_excute);die;
        //echo "<pre>";
        //print_r($call_detail_excute);
        //echo "<pre>";
       // die;
       $total =0;
        //print_r($call_detail_excute); exit;    
        foreach($call_detail_excute as $row)
        {
            $TotalArr2[$row[0]['date']][$row['cm']['Category1']]['count'] = $row[0]['count'];
            $Category[$row['cm']['Category1']] += $row[0]['count'];
            $dateArr[$row[0]['date']] +=  $row[0]['count'];
            $total =$total+$row[0]['count'];
        }
        
       
          $j++; 
          //print_r($TotalArr2); exit;
        $Category = array_unique($Category);
        
        $value[$j++][] ="";
        $value[$j][] ="TAGGING DETAILS";
        $value1[$jj++][] ="";
        $value1[$jj][] ="TAGGING DETAILS";
        $value[$j][] =$total;
        $value[$j][] ="100%";
        
        //echo $total;die;
        
        
        //$keys = array_keys($DateArr);
        //print_r($TotalArr2); exit;
        foreach($DateArr as $k)
        {
            $value[$j][]=$dateArr[$k];
            $total2 += $dateArr[$k];
            //$dateArr[$k];
        }
        $value1[$jj][] =$total2;
        $value1[$jj][] =100;
        $j++;$jj++;
        $value[$j++][] ="";
        $value1[$jj++][] ="";
        //$html .="</tr>";
        foreach($Category as $k=>$v)
        {
            $value[$j][]= $k;
            $value1[$jj][]= $k;
            
            $value[$j][]= $v;
            $value1[$jj][]= $v;
            
            $value[$j][]= round($v*100/$total,0)."%";
            $value1[$jj][]= round($v*100/$total,0);
            foreach($DateArr as $m)
            {
                $value[$j][]= $TotalArr2[$m][$k]['count'];
            }
            $j++;$jj++;
        }

       
        
        
        $this->set('data',$value); 
        }
        
    }
    
    function call_mis_reports()
        {
        $campaignId ="campaign_id in(". $this->Session->read('campaignid').")";
        $clientId   = $this->Session->read('companyid');
        
        $search     =$this->request->data['MisReports'];
            $Campagn  = $this->Session->read('campaignid');
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            //print_r($start_time_start); exit;
      $qry =  "SELECT COUNT(*) `Total`,
        SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
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

        SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
        SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
        SUM(IF(((t2.status IS NULL OR t2.status='DROP')
        AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
        SUM(IF(((t2.status IS NULL OR t2.status='DROP')
        AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
        FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
        WHERE date(t2.call_date) BETWEEN '$start_time_start' AND '$start_time_end' AND $campaignId and t2.term_reason!='AFTERHOURS' limit 1";
        
        $this->vicidialCloserLog->useDbConfig = 'db2';
        $call_excute= $this->vicidialCloserLog->query($qry);
        
        
        $TACC=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate) BETWEEN '$start_time_start' AND '$start_time_end' AND TagStatus IS NULL");
        
        
        $tot1=$call_excute[0][0];
  
        $answer =$tot1['Answered'];
        //$aband = $tot1['Abandon'];
        $aband = $TACC[0][0]['AbandCount'];
        $acht = $tot1['TotalAcht'];
        $total = $answer + $aband;
        
        
        
            
        $select = "select Category1,count(Category1)`count` from call_master where clientId='$clientId' AND date(CallDate) BETWEEN '$start_time_start' AND '$start_time_end' group by Category1";
        $categoryExecute=$this->CallRecord->query($select);

        
        $category = array(); $tot2 = 0;
        foreach($categoryExecute as $exerow){
            $row=$exerow['call_master'];
            $category[] = array($row['Category1'],$row['count'],'0%');
            $tot2 += $row['count'];
        }
   
        $qry1 = "SELECT date_format(t2.call_date,'%d-%b-%Y') `date`,date_format(t2.call_date,'%Y-%m-%d') `date1`,COUNT(*) `Total`,
        SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
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

        SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
        SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
        SUM(IF(((t2.status IS NULL OR t2.status='DROP')
        AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
        SUM(IF(((t2.status IS NULL OR t2.status='DROP')
        AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
        FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
        WHERE date(t2.call_date) BETWEEN '$start_time_start' AND '$start_time_end' AND $campaignId and t2.term_reason!='AFTERHOURS' group by date(t2.call_date)";
           
        
        
        $this->vicidialCloserLog->useDbConfig = 'db2';
        $call_details_detail_excute= $this->vicidialCloserLog->query($qry1);
        //print_r($call_details_detail_excute); exit;
        
        foreach($call_details_detail_excute as $row)
        {
            
            //$TACC1=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate)='{$row[0]['date1']}' AND TagStatus IS NULL");
            //$TABAND=$TACC1[0][0]['AbandCount'];
            
            $DateArr[] = $row[0]['date'];
            $Arr['Receive'] = $row[0]['Answered']+$row[0]['Abandon'];
            $Arr['Answer']  = $row[0]['Answered'];
            $Arr['Aband']   = $row[0]['Abandon'];
            $Arr['Aht']     = round($row[0]['TotalAcht']/$row[0]['Answered']);
            $TotalArr[$row[0]['date']] = $Arr;
            $totalcount +=$Arr['Receive']; 
            $Arr2['Receive'] += $row[0]['Answered']+$row[0]['Abandon'];
            $Arr2['Answer']  += $row[0]['Answered'];
            $Arr2['Aband']   += $row[0]['Abandon'];
            $Arr2['Aht']     += round($row[0]['TotalAcht']/$row[0]['Answered']);
        }

        
        
        $j=0;$jj=0;
        $value[$j][]  ="Summary";
        $value1[$jj][]  ="Summary";
        
        $value[$j][]="MTD";
        $value1[$jj][]="MTD";
        
        $value[$j][]="%";
        $value1[$jj][]="%";
        
        foreach($DateArr as $d)
        {
            $value[$j][] = $d;
        }
        
        
        $j=1;$jj = 1;
        $flag = true;
        foreach($Arr2 as $k=>$v)
        {
            
            $value[$j][]="TOTAL ".$k." CALLS";
            $value1[$jj][]="TOTAL ".$k." CALLS";
            
            $value[$j][] = $v;
            $value1[$jj][] = $v;
            
            if($flag)
            {$totalcount = $v; $flag = false;}
            $value[$j][]= round($v*100/$v,0)."%";
            $value1[$jj][] = round($v*100/$v,0);

            foreach($DateArr as $m)
            {
                $value[$j][]= $TotalArr[$m][$k];
            }
            $j++;$jj++;
        }
        //print_r($value); exit;
        // print_r($call_detail_excute); exit;
        $select = "SELECT DATE_FORMAT(cm.CallDate,'%d-%b-%Y') `date`,cm.Category1,COUNT(1) `count` FROM call_master cm 
                   where clientId='$clientId' AND date(CallDate) BETWEEN '$start_time_start' AND '$start_time_end' GROUP BY cm.Category1,DATE(cm.CallDate)";
        
        $call_detail_excute=$this->CallRecord->query($select);
        
        //print_r($call_detail_excute); exit; 
        $total = 0;
        
        foreach($call_detail_excute as $row)
        {
            $TotalArr2[$row[0]['date']][$row['cm']['Category1']]['count'] = $row[0]['count'];
            $Category[$row['cm']['Category1']] += $row[0]['count'];
            $dateArr[$row[0]['date']] +=  $row[0]['count'];
            $total += $row[0]['count'];
        }
          $j++; 
          //print_r($TotalArr2); exit;
        $Category = array_unique($Category);
        
        $value[$j++][] ="";
        $value[$j][] ="TAGGING DETAILS";
        $value1[$jj++][] ="";
        $value1[$jj][] ="TAGGING DETAILS";
        $value[$j][] =$total;
        $value[$j][] ="100%";
        //$keys = array_keys($DateArr);
        //print_r($dateArr); exit;
        foreach($DateArr as $k)
        {
            $value[$j][]=$dateArr[$k];
            $total2 += $dateArr[$k];
            //$dateArr[$k];
        }
        $value1[$jj][] =$total2;
        $value1[$jj][] =100;
        $j++;$jj++;
        $value[$j++][] ="";
        $value1[$jj++][] ="";
        //$html .="</tr>";
        foreach($Category as $k=>$v)
        {
            $value[$j][]= $k;
            $value1[$jj][]= $k;
            
            $value[$j][]= $v;
            $value1[$jj][]= $v;
            
            $value[$j][]= round($v*100/$total,0)."%";
            $value1[$jj][]= round($v*100/$total,0);
            foreach($DateArr as $m)
            {
                $value[$j][]= $TotalArr2[$m][$k]['count'];
            }
            $j++;$jj++;
        }
        
        $this->export_excel($value1,$value); 
        }


    public function tat_mis(){ 
        $this->layout='ajax';
        if($this->request->is("POST")){
            $search=$this->request->data['MisReports'];
            $clientId = $this->Session->read('companyid');
            
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            
            $start_date = date("Y-m-d",strtotime($search['startdate']));
            $end_date = date("Y-m-d",strtotime($search['enddate']));
            
            
            $condition['ClientId']=$clientId;
            $condition['date(CallDate) >=']=$start_date;
            $condition['date(CallDate) <=']=$end_date;
            
              $qry="
                SELECT cm.Category1, IF(DATE(cm.CloseLoopingDate)>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NOT NULL,1,
 IF((HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)) `outtat`,
  IF(DATE(cm.CloseLoopingDate)=DATE(cm.CallDate) AND (HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))<=tt.time_Hours 
  AND cm.CloseLoopingDate IS NOT NULL,1,0) `intat`, IF(CURDATE()>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NULL,
  1, IF((HOUR(NOW())-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NULL,1,0)) `openouttat`, 
  IF(CURDATE()=DATE(cm.CallDate) AND (HOUR(NOW())-HOUR(cm.CallDate))<=tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)
   `openintat`, DATE_FORMAT(cm.CloseLoopingDate,'%d-%b-%Y')`CallDate`,DATE_FORMAT(cm.CallDate,'%d-%b-%Y')`CloseLoopDate`,
   tt.time_hours FROM call_master cm INNER JOIN tbl_time tt ON cm.ClientId = tt.clientId 
   AND CONCAT(IFNULL(cm.Category1,''),IFNULL(cm.Category2,''),IFNULL(cm.Category3,''),IFNULL(cm.Category4,''),IFNULL(cm.Category5,'')) = 
   CONCAT(IFNULL(tt.Category1,''),IFNULL(tt.Category2,''),IFNULL(tt.Category3,''),IFNULL(tt.Category4,''),IFNULL(tt.Category5,''))
                 WHERE cm.ClientId='$clientId' AND DATE(cm.CallDate) BETWEEN '$start_date' AND '$end_date'";
                  
                $RecArr=$this->CallRecord->query($qry);
                
                
                
                
                //print_r($RecArr); exit;
                
                foreach($RecArr as $row):
                 
                     $key = $row['cm']['Category1'];
                    if(key_exists($key, $category))
                    {
                        $category[$key]['MTD'] +=1;
                        $category[$key][$row[0]['CloseLoopDate']]['MTD'] +=1;
                        $category[$key]['intat'] +=$row[0]['intat'];
                        $category[$key]['outtat'] +=$row[0]['outtat'];
                        $category[$key]['openintat'] +=$row[0]['openintat'];
                        $category[$key]['openouttat'] +=$row[0]['openouttat'];
                        $category[$key][$row[0]['CloseLoopDate']]['intat'] +=$row[0]['intat'];
                        $category[$key][$row[0]['CloseLoopDate']]['outtat'] +=$row[0]['outtat'];
                        $category[$key][$row[0]['CloseLoopDate']]['openintat'] +=$row[0]['openintat'];
                        $category[$key][$row[0]['CloseLoopDate']]['openouttat'] +=$row[0]['openouttat'];
                        
                    }
                    else
                    {
                        $category[$key]['MTD'] =1;
                        $category[$key][$row[0]['CloseLoopDate']]['MTD'] =1;
                        $category[$key]['intat'] =$row[0]['intat'];
                        $category[$key]['outtat'] =$row[0]['outtat'];
                        $category[$key]['openintat'] =$row[0]['openintat'];
                        $category[$key]['openouttat'] =$row[0]['openouttat'];
                        $category[$key][$row[0]['CloseLoopDate']]['intat'] =$row[0]['intat'];
                        $category[$key][$row[0]['CloseLoopDate']]['outtat'] =$row[0]['outtat'];
                        $category[$key][$row[0]['CloseLoopDate']]['openintat'] =$row[0]['openintat'];
                        $category[$key][$row[0]['CloseLoopDate']]['openouttat'] =$row[0]['openouttat'];
                    }
                    
                    $total +=1;
                    $DataArr[] = $row[0]['CloseLoopDate'];
                    endforeach;
                 
                   $DataArr = array_unique($DataArr);
                 
                 $j=0;
                 $value[$j][]= "Summary";
                 $value[$j][]= "MTD";
                 $value[$j][]= "%";
                 
                 foreach($DataArr as $k)
                 {
                     $value[$j][]= $k;
                 }
                 
                 
                 $keys = array_keys($category);
                 //print_r($category); exit;
                 $header = array('MTD'=>'','intat'=>'CLOSURE WITHIN TAT','outtat'=>'CLOSURE OUT OF TAT','openintat'=>'OPEN WITHIN TAT','openouttat'=>'OPEN OUT OF TAT');
                 
                 $i=0;$j=1;
                foreach($header as $k1=>$v1)
                {
                 foreach($keys as $k)
                 {
                    $value[$j][]= "Total ".$k.' '.$v1."";
                    $value[$j][]= $category[$k][$k1]; 
                    $value[$j][]= round($category[$k][$k1]*100/$total,2);
                    foreach($DataArr as $k2)
                    {
                     $value[$j][]= $category[$k][$k2][$k1];
                    }
                   //$html .= "</tr>";
                   $j++;
                   $data[$k1][$i][0] = "Total ".$k.' '.$v1;
                   $data[$k1][$i][1] = $category[$k][$k1];
                   $data[$k1][$i++][2] = round($category[$k][$k1]*100/$total,0);
                 }
                }
                
             
                
                $TotalInTat=array();
                $TotalOutTat=array();
                $TotalOpenInTat=array();
                $TotalOpenOutTat=array();
                
                $intatArray[]=array($val['EcrRecord']['ecrName']." Closure Within TAT",round(count($TotalInTat)),'');
                $outtatArray[]=array($val['EcrRecord']['ecrName']." Closure Out Of TAT",count($TotalOutTat),'');
                $openIntatArray[]=array($val['EcrRecord']['ecrName']." Closure Open Within TAT",count($TotalOpenInTat),'');
                $openOuttatArray[]=array($val['EcrRecord']['ecrName']." Closure Open Out Of TAT",count($TotalOpenOutTat),'');
            
            
            $SumeryDetails=array(array('SUMMARY','MTD','%'));
 
            $exportArray1=array_merge($SumeryDetails,$data['MTD'],$data['intat'],$data['outtat'],$data['openintat'],$data['openouttat']);
            
            
            
            //echo "<pre>";
                //print_r($data);
            // echo "</pre>";
            //die;
            

            $this->export_excel($exportArray1,$value); 
        }
    }

    public function export_tat_mis(){ 
        $this->layout='user';
        if($this->request->is("POST")){
            $search=$this->request->data['MisReports'];
            $clientId = $this->Session->read('companyid');
            
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            
            $start_date = date("Y-m-d",strtotime($search['startdate']));
            $end_date = date("Y-m-d",strtotime($search['enddate']));
            
            
            $condition['ClientId']=$clientId;
            $condition['date(CallDate) >=']=$start_date;
            $condition['date(CallDate) <=']=$end_date;
            
              $qry="
                SELECT cm.Category1, IF(DATE(cm.CloseLoopingDate)>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NOT NULL,1,
 IF((HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)) `outtat`,
  IF(DATE(cm.CloseLoopingDate)=DATE(cm.CallDate) AND (HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))<=tt.time_Hours 
  AND cm.CloseLoopingDate IS NOT NULL,1,0) `intat`, IF(CURDATE()>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NULL,
  1, IF((HOUR(NOW())-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NULL,1,0)) `openouttat`, 
  IF(CURDATE()=DATE(cm.CallDate) AND (HOUR(NOW())-HOUR(cm.CallDate))<=tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)
   `openintat`, DATE_FORMAT(cm.CloseLoopingDate,'%d-%b-%Y')`CallDate`,DATE_FORMAT(cm.CallDate,'%d-%b-%Y')`CloseLoopDate`,
   tt.time_hours FROM call_master cm INNER JOIN tbl_time tt ON cm.ClientId = tt.clientId 
   AND CONCAT(IFNULL(cm.Category1,''),IFNULL(cm.Category2,''),IFNULL(cm.Category3,''),IFNULL(cm.Category4,''),IFNULL(cm.Category5,'')) = 
   CONCAT(IFNULL(tt.Category1,''),IFNULL(tt.Category2,''),IFNULL(tt.Category3,''),IFNULL(tt.Category4,''),IFNULL(tt.Category5,''))
                 WHERE cm.ClientId='$clientId' AND DATE(cm.CallDate) BETWEEN '$start_date' AND '$end_date'";
                  
                $RecArr=$this->CallRecord->query($qry);
                
                
                
                
                //print_r($RecArr); exit;
                
                foreach($RecArr as $row):
                 
                     $key = $row['cm']['Category1'];
                    if(key_exists($key, $category))
                    {
                        $category[$key]['MTD'] +=1;
                        $category[$key][$row[0]['CloseLoopDate']]['MTD'] +=1;
                        $category[$key]['intat'] +=$row[0]['intat'];
                        $category[$key]['outtat'] +=$row[0]['outtat'];
                        $category[$key]['openintat'] +=$row[0]['openintat'];
                        $category[$key]['openouttat'] +=$row[0]['openouttat'];
                        $category[$key][$row[0]['CloseLoopDate']]['intat'] +=$row[0]['intat'];
                        $category[$key][$row[0]['CloseLoopDate']]['outtat'] +=$row[0]['outtat'];
                        $category[$key][$row[0]['CloseLoopDate']]['openintat'] +=$row[0]['openintat'];
                        $category[$key][$row[0]['CloseLoopDate']]['openouttat'] +=$row[0]['openouttat'];
                        
                    }
                    else
                    {
                        $category[$key]['MTD'] =1;
                        $category[$key][$row[0]['CloseLoopDate']]['MTD'] =1;
                        $category[$key]['intat'] =$row[0]['intat'];
                        $category[$key]['outtat'] =$row[0]['outtat'];
                        $category[$key]['openintat'] =$row[0]['openintat'];
                        $category[$key]['openouttat'] =$row[0]['openouttat'];
                        $category[$key][$row[0]['CloseLoopDate']]['intat'] =$row[0]['intat'];
                        $category[$key][$row[0]['CloseLoopDate']]['outtat'] =$row[0]['outtat'];
                        $category[$key][$row[0]['CloseLoopDate']]['openintat'] =$row[0]['openintat'];
                        $category[$key][$row[0]['CloseLoopDate']]['openouttat'] =$row[0]['openouttat'];
                    }
                    
                    $total +=1;
                    $DataArr[] = $row[0]['CloseLoopDate'];
                    endforeach;
                 
                   $DataArr = array_unique($DataArr);
                 
                 $j=0;
                 $value[$j][]= "Summary";
                 $value[$j][]= "MTD";
                 $value[$j][]= "%";
                 
                 foreach($DataArr as $k)
                 {
                     $value[$j][]= $k;
                 }
                 
                 
                 $keys = array_keys($category);
                 //print_r($category); exit;
                 $header = array('MTD'=>'','intat'=>'CLOSURE WITHIN TAT','outtat'=>'CLOSURE OUT OF TAT','openintat'=>'OPEN WITHIN TAT','openouttat'=>'OPEN OUT OF TAT');
                 
                 $i=0;$j=1;
                foreach($header as $k1=>$v1)
                {
                 foreach($keys as $k)
                 {
                    $value[$j][]= "Total ".$k.' '.$v1."";
                    $value[$j][]= $category[$k][$k1]; 
                    $value[$j][]= round($category[$k][$k1]*100/$total,2);
                    foreach($DataArr as $k2)
                    {
                     $value[$j][]= $category[$k][$k2][$k1];
                    }
                   //$html .= "</tr>";
                   $j++;
                   $data[$k1][$i][0] = "Total ".$k.' '.$v1;
                   $data[$k1][$i][1] = $category[$k][$k1];
                   $data[$k1][$i++][2] = round($category[$k][$k1]*100/$total,0);
                 }
                }
                
                $TotalInTat=array();
                $TotalOutTat=array();
                $TotalOpenInTat=array();
                $TotalOpenOutTat=array();
                
                $intatArray[]=array($val['EcrRecord']['ecrName']." Closure Within TAT",round(count($TotalInTat)),'');
                $outtatArray[]=array($val['EcrRecord']['ecrName']." Closure Out Of TAT",count($TotalOutTat),'');
                $openIntatArray[]=array($val['EcrRecord']['ecrName']." Closure Open Within TAT",count($TotalOpenInTat),'');
                $openOuttatArray[]=array($val['EcrRecord']['ecrName']." Closure Open Out Of TAT",count($TotalOpenOutTat),'');
            
            
            $SumeryDetails=array(array('SUMMARY','MTD','%'));
            $exportArray1=array_merge($SumeryDetails,$keys,$data['intat'],$data['outtat'],$data['openintat'],$data['openouttat']);
            
            $this->set('data',$value);

            //$this->export_excel($exportArray1,$value); 
        }
    }
    
    
    public function tagging_mis() {
        $this->layout='ajax';
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
            SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
            SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`,
            SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',t1.length_in_sec,0)) `TotalAcht`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
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
                        
                        $exportArr1[$jj][] = $c;
                        $exportArr1[$jj][] = $category[$k]['Category2'][$c]['count'];
                        $exportArr1[$jj][] = round($category[$k]['Category2'][$c]['count']*100/$category[$k]['count'],0);
                        
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
                
                //echo "<pre>";
               //print_r($exportArr1);
               
               //echo "</pre>";
               //die; 
                
            
         
            $this->export_excel($exportArr1,$exportArr2);     
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
            SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
            SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`,
            SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',t1.length_in_sec,0)) `TotalAcht`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
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
    
    
    public function time_wise_mis() {
        $this->layout='ajax';
      
                 
                 
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
            
            $qry = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,HOUR(t2.call_date)`hour`,MINUTE(t2.call_date)`minute`,COUNT(1) `Total`,
            SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
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

            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND (t2.queue_seconds IS NULL OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
            WHERE date(t2.call_date) between '$start_date' and '$end_date' AND $campaignId AND t2.term_reason!='AFTERHOURS' GROUP BY DATE(t2.call_date)";
            
           
            $this->vicidialCloserLog->useDbConfig = 'db2';
            
            $dt= $this->vicidialCloserLog->query($qry);
            //print_r($dt); exit;
            foreach($dt as $row):
                
                $stdt=strtotime($row[0]['date']);
                $stdt1   = date("Y-m-d",$stdt);
                $TACC1=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate)='$stdt1' AND TagStatus IS NULL");
                //$TABANDCOUNT1=$TACC1[0][0]['AbandCount'];
                $TABANDCOUNT1=$row[0]['Abandon'];
                  
                    if($row[0]['minute']>30){$key = 0;}
                    else{$key = 1;}

                   if(key_exists($row[0]['date'], $dateArr))
                    {
                       if(key_exists($row[0]['hour'], $dateArr[$row[0]['date']]))
                       {   
                            if(key_exists($key,$dateArr[$row[0]['date']][$row[0]['hour']]))
                            {
                                $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Answered'] +=$row[0]['Answered'];
                                    $dateArr[$row[0]['date']][$row[0]['hour']]['Abandon'] +=$TABANDCOUNT1;
                                    $dateArr[$row[0]['date']][$row[0]['hour']]['Total'] +=$row[0]['Answered'] + $TABANDCOUNT1;
                            }
                            
                            else 
                                {
                                    $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Answered']=$row[0]['Answered'];
                                    $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Abandon']=$TABANDCOUNT1;
                                    $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Total']=$row[0]['Answered'] + $TABANDCOUNT1;
                                }
                       }
                       else
                       {
                            $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Answered']=$row[0]['Answered'];
                            $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Abandon']=$TABANDCOUNT1;
                            $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Total']=$row[0]['Answered'] + $TABANDCOUNT1;
                            
                       }
                       
                    }
                    else
                    {
                        $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Answered']=$row[0]['Answered'];
                        $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Abandon']=$TABANDCOUNT1;
                        $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Total']=$row[0]['Answered'] + $TABANDCOUNT1;
                        $Hour []= $row[0]['hour'];
                    }
                    $Date[] = $row[0]['date'];
                endforeach;
                 
                 $Date = array_unique($Date);
                 $Hour = array_unique($Hour);
                 
                 $jj=0; $ii=0;
                 //$exportArr = array();
                 $jj=0; $ii=0;
                $html .= "<table border='1'><tr style='background-color:#F90417; color:#FFFFFF;'><th>Date</th>";
                 //$exportArr[$ii][0] = "Date";
                 
                 
                 foreach($Date as $d)
                 {
                     $html .= "<th colspan='3'>".$d."</th>";
                 }
                 $html .= "</tr>";
                 
                 $html .= "<tr><th>Timing</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>Total</th>";
                     $html .= "<th>Abandon</th>";
                     $html .= "<th>Answered</th>";
                     
                 }
                 $html .= "</tr>";
                 foreach($Hour as $h)
                 {
                     
                     $html .= "<tr><th>".$h.":00AM to $h:30 AM</th>";
                    foreach($Date as $d)
                    {
                        $html .= "<td>".$dateArr[$d][$h][0]['Total']."</td>"; 
                        $html .= "<td>".$dateArr[$d][$h][0]['Abandon']."</td>";
                        $html .= "<td>".$dateArr[$d][$h][0]['Answered']."</td>"; 
                    }
                    $html .= "<tr><th>".$h.":30AM to ".($h+1).":00 AM</th>";
                    foreach($Date as $d)
                    {
                        $html .= "<td>".$dateArr[$d][$h][1]['Total']."</td>"; 
                        $html .= "<td>".$dateArr[$d][$h][1]['Abandon']."</td>";
                        $html .= "<td>".$dateArr[$d][$h][1]['Answered']."</td>"; 
                    }
                     $html .= "</tr>";
                 }
                 
                 
                 
                 
                 
                 
                 $html .="</table";
                 
                //mail_send($html,'time_mis',$name,$emailId,$clientId);
                echo $html;
                
                $fileName = "time_wise_mis".date('d_m_y_h_i_s');
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0"); 
                
                exit;
                //print_r();
            //echo $html; exit;
            
            
            //$exportArray1=array_merge(array(),$exportArr2);
            $this->export_excel($exportArr1,$exportArr2);     
        }
    }
    
    public function export_time_wise_mis() {
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
            
            $qry = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,HOUR(t2.call_date)`hour`,MINUTE(t2.call_date)`minute`,COUNT(1) `Total`,
            SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
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

            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND (t2.queue_seconds IS NULL OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
            WHERE date(t2.call_date) between '$start_date' and '$end_date' AND $campaignId AND t2.term_reason!='AFTERHOURS' GROUP BY DATE(t2.call_date)";
            
           
            $this->vicidialCloserLog->useDbConfig = 'db2';
            
            $dt= $this->vicidialCloserLog->query($qry);
            //print_r($dt); exit;
            foreach($dt as $row):
                
                $stdt=strtotime($row[0]['date']);
                $stdt1   = date("Y-m-d",$stdt);
                $TACC1=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate)='$stdt1' AND TagStatus IS NULL");
                //$TABANDCOUNT1=$TACC1[0][0]['AbandCount'];
                $TABANDCOUNT1=$row[0]['Abandon'];
                
                
                  
                    if($row[0]['minute']>30){$key = 0;}
                    else{$key = 1;}

                   if(key_exists($row[0]['date'], $dateArr))
                    {
                       if(key_exists($row[0]['hour'], $dateArr[$row[0]['date']]))
                       {   
                            if(key_exists($key,$dateArr[$row[0]['date']][$row[0]['hour']]))
                            {
                                $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Answered'] +=$row[0]['Answered'];
                                $dateArr[$row[0]['date']][$row[0]['hour']]['Abandon'] +=$TABANDCOUNT1;
                                $dateArr[$row[0]['date']][$row[0]['hour']]['Total'] +=$row[0]['Answered'] + $TABANDCOUNT1;
                            }
                            
                            else 
                                {
                                
                                    $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Answered']=$row[0]['Answered'];
                                    $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Abandon']=$TABANDCOUNT1;
                                    $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Total']=$row[0]['Answered'] + $TABANDCOUNT1;
                                }
                       }
                       else
                       {
                            $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Answered']=$row[0]['Answered'];
                            $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Abandon']=$TABANDCOUNT1;
                            $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Total']=$row[0]['Answered'] + $TABANDCOUNT1;
                            
                       }
                       
                    }
                    else
                    {
                        
                        $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Answered']=$row[0]['Answered'];
                        $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Abandon']=$TABANDCOUNT1;
                        $dateArr[$row[0]['date']][$row[0]['hour']][$key]['Total']=$row[0]['Answered'] + $TABANDCOUNT1;
                        $Hour []= $row[0]['hour'];
                    }
                    $Date[] = $row[0]['date'];
                endforeach;
                 
                 $Date = array_unique($Date);
                 $Hour = array_unique($Hour);
                 
                 $jj=0; $ii=0;
                 //$exportArr = array();
                 $jj=0; $ii=0;
                 $html .= "<tr><th>Date</th>";
                 //$exportArr[$ii][0] = "Date";
                 
                 
                 foreach($Date as $d)
                 {
                     $html .= "<th colspan='3'>".$d."</th>";
                 }
                 $html .= "</tr>";
                 
                 $html .= "<tr><th>Timing</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>Total</th>";
                     $html .= "<th>Abandon</th>";
                     $html .= "<th>Answered</th>";
                     
                 }
                 $html .= "</tr>";
                 foreach($Hour as $h)
                 {
                     
                     $html .= "<tr><th>".$h.":00AM to $h:30 AM</th>";
                    foreach($Date as $d)
                    {
                        $html .= "<td>".$dateArr[$d][$h][0]['Total']."</td>"; 
                        $html .= "<td>".$dateArr[$d][$h][0]['Abandon']."</td>";
                        $html .= "<td>".$dateArr[$d][$h][0]['Answered']."</td>"; 
                    }
                    $html .= "<tr><th>".$h.":30AM to ".($h+1).":00 AM</th>";
                    foreach($Date as $d)
                    {
                        $html .= "<td>".$dateArr[$d][$h][1]['Total']."</td>"; 
                        $html .= "<td>".$dateArr[$d][$h][1]['Abandon']."</td>";
                        $html .= "<td>".$dateArr[$d][$h][1]['Answered']."</td>"; 
                    }
                     $html .= "</tr>";
                 }

                $this->set('html',$html);
        }
    }


   
    public function agent_wise_mis() {
        $this->layout='ajax';
        if($this->request->is("POST")){
            $catgoryArray=array();
            
            $search     =$this->request->data['MisReports'];
            $clientId   = $this->Session->read('companyid');
            $Campagn  = $this->Session->read('campaignid');
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            
            $condition['ClientId']=$clientId;
            $startdate=date("Y-m-d",$startdate);
            $enddate=date("Y-m-d",$enddate);
            
             // START TOTAL ANSWERED CALL QUERY 
            
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            
            $CampagnId ="campaign_id in ($Campagn)"; 
            
           
           $qry1 = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,t2.user `username`,
            SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
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
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND (t2.queue_seconds IS NULL OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
            WHERE date(t2.call_date) between '$startdate' AND '$enddate' AND $CampagnId AND t2.term_reason!='AFTERHOURS' GROUP BY DATE(t2.call_date)";
  
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt1= $this->vicidialCloserLog->query($qry1);
          
           // print_r($dt1); exit;
            
            foreach($dt1 as $row):
                $Date[] = $row[0]['date'];
                $Agent[] = $row['t2']['username'];
                $Data[$row[0]['date']][$row['t2']['username']] = $row[0];
            endforeach;
            
            $Date = array_unique($Date);
                 $Agent = array_unique($Agent);
                 echo "<table border='1'><tr style='background-color:#F90417; color:#FFFFFF;' ><th>Date</th>";
                 foreach($Date as $d)
                 {
                     echo "<th colspan='3'>".$d."</th>";
                 }
                 echo "</tr>";
                 
                 echo "<tr><th>Agent</th>";
                 foreach($Date as $d)
                 {
                     echo "<th>Total</th>";
                     echo "<th>Answered</th>";
                     echo "<th>Abandon</th>";
                 }
                 echo "</tr>";
                 
                 foreach($Agent as $k=>$v)
                 {
                     echo "<tr><th>$v</th>";
                     
                    foreach($Date as $d)
                    {
                        $stdt=strtotime($d);
                        $stdt1   = date("Y-m-d",$stdt);
                        $TACC1=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate)='$stdt1' AND TagStatus IS NULL");
                        
                        $TABANDCOUNT1="";
                        if($Data[$d][$v]['Answered'] !=""){
                            //$TABANDCOUNT1=$TACC1[0][0]['AbandCount'];
                            $TABANDCOUNT1=$Data[$d][$v]['Abandon'];
                        }
                        
                        echo "<th>".($Data[$d][$v]['Answered']+$TABANDCOUNT1)."</th>"; 
                        echo "<th>".$Data[$d][$v]['Answered']."</th>"; 
                        echo "<th>".$TABANDCOUNT1."</th>"; 
                    }
                    echo "</tr>";
                 }
                 echo "</table>";
                 
                 $fileName = "agent_mis_report".date('Y_m_d_h_i_s');
	header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$fileName.".xls");
	header("Pragma: no-cache");
	header("Expires: 0"); exit;
        }
    }
    
    
    
    public function export_agent_wise_mis() {
        $this->layout='user';
        if($this->request->is("POST")){
            $catgoryArray=array();
            
            $search     =$this->request->data['MisReports'];
            $clientId   = $this->Session->read('companyid');
            $Campagn  = $this->Session->read('campaignid');
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            
            $condition['ClientId']=$clientId;
            $startdate=date("Y-m-d",$startdate);
            $enddate=date("Y-m-d",$enddate);
            
             // START TOTAL ANSWERED CALL QUERY 
            
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            
            $CampagnId ="campaign_id in ($Campagn)"; 
            
            
            
            
           $qry1 = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,t2.user `username`,
            SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
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
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND (t2.queue_seconds IS NULL OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
            WHERE date(t2.call_date) between '$startdate' AND '$enddate' AND $CampagnId AND t2.term_reason!='AFTERHOURS' GROUP BY DATE(t2.call_date)";
  
           
           
        
           
           
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt1= $this->vicidialCloserLog->query($qry1);
          
           // print_r($dt1); exit;
            
            foreach($dt1 as $row):
                $Date[] = $row[0]['date'];
                $Agent[] = $row['t2']['username'];
                $Data[$row[0]['date']][$row['t2']['username']] = $row[0];
            endforeach;
            
            $Date = array_unique($Date);
                 $Agent = array_unique($Agent);
                 $html .= "<tr  ><th>Date</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th colspan='3'>".$d."</th>";
                 }
                 $html .= "</tr>";
                 
                 $html .= "<tr><th>Agent</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>Total</th>";
                     $html .= "<th>Answered</th>";
                     $html .= "<th>Abandon</th>";
                 }
                 $html .= "</tr>";
                 
                 foreach($Agent as $k=>$v)
                 {
                    
                     //echo $k;die;
                     
                     $html .= "<tr><th>$v</th>";
                     
                    foreach($Date as $d)
                    {
                        
                        $stdt=strtotime($d);
                        $stdt1   = date("Y-m-d",$stdt);
                        $TACC1=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate)='$stdt1' AND TagStatus IS NULL");
                        
                        $TABANDCOUNT1="";
                        if($Data[$d][$v]['Answered'] !=""){
                            //$TABANDCOUNT1=$TACC1[0][0]['AbandCount'];
                            $TABANDCOUNT1=$Data[$d][$v]['Abandon'];
                        }
                        
                        $html .= "<th>".($Data[$d][$v]['Answered']+$TABANDCOUNT1)."</th>"; 
                        $html .= "<th>".$Data[$d][$v]['Answered']."</th>"; 
                        $html .= "<th>".$TABANDCOUNT1."</th>"; 
                    }
                    $html .= "</tr>";
                 }
                 
               
                 
                 $this->set('html',$html);
                 
                 
        
        }
    }
    
    
    public function abend_call() {
       $this->layout='ajax';
        if($this->request->is("POST")){
            $catgoryArray=array();
            
            $search     =$this->request->data['MisReports'];
            $clientId   = $this->Session->read('companyid');
            $Campagn  = $this->Session->read('campaignid');
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            
            $condition['ClientId']=$clientId;
            $condition['date(CallDate) >=']=date("Y-m-d",$startdate);
            $condition['date(CallDate) <=']=date("Y-m-d",$enddate);
            
             // START TOTAL ANSWERED CALL QUERY 
            
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            
            $CampagnId ="campaign_id in ($Campagn)"; 
            
            $qry1 = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,t2.length_in_sec,t2.lead_id
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE date(t2.call_date) between '$start_time_start' AND '$start_time_end' AND $CampagnId AND
t2.term_reason!='AFTERHOURS' and IF(t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL',true,false) group by t2.phone_number ";
  
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt1= $this->vicidialCloserLog->query($qry1);
            
            //print_r($dt1); exit;
            foreach($dt1 as $row):
                    $stdt=strtotime($row[0]['date']);
                    $stdt1   = date("Y-m-d",$stdt);
                    $TACC1=$this->AbandCallMaster->query("SELECT LeadId  FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate)='$stdt1' AND LeadId='{$row['t2']['lead_id']}' AND TagStatus IS NULL");
                    //if(!empty($TACC1)){
                     
                     $sec = round($row['t2']['length_in_sec']/5,0);
                     $Date[] = $row[0]['date'];
                     
                     if(key_exists("".$sec, $secArr))
                     {$secArr[$sec] += 1;}
                     else{$secArr[$sec] = 1;}
                     
                     if(key_exists($row[0]['date'], $Data))
                     {
                         if(key_exists($sec, $Data[$row[0]['date']]))
                         {
                             $Data[$row[0]['date']][$sec] += $row['t2']['length_in_sec'];
                             
                         }
                         else
                         {
                             $Data[$row[0]['date']][$sec] = $row['t2']['length_in_sec'];
                         }
                         
                     }
                     else
                     {
                         $Data[$row[0]['date']][$sec] = $row['t2']['length_in_sec'];
                     }
                     $total += 1;
                     
                    //}
            endforeach;
                 
                //print_r($Data); exit;
                 $Date = array_unique($Date);
                 echo "<table border=\"1\"><tr><th>SUMMARY</th><th>MTD</th><th>%</th>";
                 foreach($Date as $d)
                 {
                     echo "<th>".$d."</th>";
                 }
                 echo "</tr>";
                 
                 echo "<tr><th>TOTAL ABANDONED CALLS</th><th>$total</th><th>100%</th>";
                 foreach($Date as $d)
                 {
                     echo "<th>".''."</th>";
                 }
                 echo "</tr>";
                 
                 echo "<tr><th></th><th></th><th></th>";
                 foreach($Date as $d)
                 {
                     echo "<th>".''."</th>";
                 }
                 echo "</tr>";
                 
                 
                 
                 echo "<tr><th>ABANDONED CALLS DETAILS<th><th></th>";
                 foreach($Date as $d)
                 {
                     echo "<th>".''."</th>";
                 }
                 echo "</tr>";
                 
                 //print_r($secArr); 
                 ksort($secArr); 
                 foreach($secArr as $k=>$v)
                 {
                     echo "<tr><th>"."<".(($k+1)*5)." Sec</th>";
                     echo "<th>$v</th>";
                     echo "<th>".round($v*100/$total,2)."%</th>";
                    foreach($Date as $d)
                    {
                       echo "<td>".$Data[$d][$k]."</td>"; 
                    }
                    echo "</tr>";
                 }
                echo "</table>";
                $fileName = "abend_call".date('d_m_y_h_i_s');
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=".$fileName.".xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                exit;
        }
    }
    
    
    public function export_abend_call() {
       $this->layout='user';
        if($this->request->is("POST")){
            $catgoryArray=array();
            
            $search     =$this->request->data['MisReports'];
            $clientId   = $this->Session->read('companyid');
            $Campagn  = $this->Session->read('campaignid');
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            
            $condition['ClientId']=$clientId;
            $condition['date(CallDate) >=']=date("Y-m-d",$startdate);
            $condition['date(CallDate) <=']=date("Y-m-d",$enddate);
            
             // START TOTAL ANSWERED CALL QUERY 
            
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            
            /*
            $TACC=$this->AbandCallMaster->query("SELECT LeadId  FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate) BETWEEN '$start_time_start' AND '$start_time_end' AND TagStatus='yes'");
            
            foreach($TACC as $LeadArr){
                print_r($LeadArr['aband_call_master']['LeadId']);
            }*/
            
            
            //$TABANDCOUNT=$TACC[0][0]['LeadId'];
            
            
            $CampagnId ="campaign_id in ($Campagn)"; 
            
            $qry1 = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,t2.length_in_sec,t2.lead_id
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE date(t2.call_date) between '$start_time_start' AND '$start_time_end' AND $CampagnId AND
t2.term_reason!='AFTERHOURS' and IF(t2.status IS NULL OR t2.status='DROP' OR t2.USER='VDCL',true,false) group by t2.phone_number ";
  
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt1= $this->vicidialCloserLog->query($qry1);
            
            //print_r($dt1); exit;
            foreach($dt1 as $row):
            
                    $stdt=strtotime($row[0]['date']);
                    $stdt1   = date("Y-m-d",$stdt);
                    $TACC1=$this->AbandCallMaster->query("SELECT LeadId  FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate)='$stdt1' AND LeadId='{$row['t2']['lead_id']}' AND TagStatus IS NULL");
                    //if(!empty($TACC1)){
                   
                     $sec = round($row['t2']['length_in_sec']/5,0);
                     $Date[] = $row[0]['date'];
                     
                     if(key_exists("".$sec, $secArr))
                     {$secArr[$sec] += 1;}
                     else{$secArr[$sec] = 1;}
                     
                     if(key_exists($row[0]['date'], $Data))
                     {
                         if(key_exists($sec, $Data[$row[0]['date']]))
                         {
                             $Data[$row[0]['date']][$sec] += $row['t2']['length_in_sec'];
                             
                         }
                         else
                         {
                             $Data[$row[0]['date']][$sec] = $row['t2']['length_in_sec'];
                         }
                         
                     }
                     else
                     {
                         $Data[$row[0]['date']][$sec] = $row['t2']['length_in_sec'];
                     }
                     $total += 1;
                     
                     //}
            endforeach;
                 
                //print_r($Data); exit;
                 $Date = array_unique($Date);
                 
                 $html = "<tr><th>SUMMARY</th><th>MTD</th><th>%</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".$d."</th>";
                 }
                 $html .= "</tr>";
                 
                 $html .= "<tr><th>TOTAL ABANDONED CALLS</th><th>$total</th><th>100%</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".''."</th>";
                 }
                 $html .= "</tr>";
                 
                 $html .= "<tr><th></th><th></th><th></th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".''."</th>";
                 }
                 $html .= "</tr>";
                 
                 
                 
                 $html .= "<tr><th>ABANDONED CALLS DETAILS<th><th></th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".''."</th>";
                 }
                 $html .= "</tr>";
                 
                 //print_r($secArr); 
                 ksort($secArr);
                 //print_r($secArr); exit;
                 foreach($secArr as $k=>$v)
                 {
                     $html .= "<tr><th>"."<".(($k+1)*5)." Sec</th>";
                     $html .= "<th>$v</th>";
                     $html .= "<th>".round($v*100/$total,2)."%</th>";
                    foreach($Date as $d)
                    {
                       $html .= "<td>".$Data[$d][$k]."</td>"; 
                    }
                    $html .= "</tr>";
                 }
             $this->set('html',$html);
            
           
        }
    }
    
    
    public function answer_call() {
       $this->layout='ajax';
        if($this->request->is("POST")){
            
            $fileName = "time_wise_mis".date('d_m_y_h_i_s');
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=".$fileName.".xls");
            header("Pragma: no-cache");
            header("Expires: 0"); 
            
            $catgoryArray=array();
            
            $search     =$this->request->data['MisReports'];
            $clientId   = $this->Session->read('companyid');
            $Campagn  = $this->Session->read('campaignid');
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            
            $condition['ClientId']=$clientId;
            $condition['date(CallDate) >=']=date("Y-m-d",$startdate);
            $condition['date(CallDate) <=']=date("Y-m-d",$enddate);
            
             // START TOTAL ANSWERED CALL QUERY 
            
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            
            $CampagnId ="campaign_id in ($Campagn)"; 
            
            $qry1 = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,t2.length_in_sec, IF(t2.status IS NULL OR t2.status='DROP',1,0) `Abandon`
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE date(t2.call_date) between '$start_time_start' AND '$start_time_end' AND $CampagnId AND
t2.term_reason!='AFTERHOURS' and IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B',TRUE,FALSE) AND t2.user !='vdcl'";
  
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt1= $this->vicidialCloserLog->query($qry1);
            
            //print_r($dt1); exit;
            foreach($dt1 as $row):
            
                     
                     $sec = round($row['t2']['length_in_sec']/5,0);
                     $Date[] = $row[0]['date'];
                     
                     if(key_exists("".$sec, $secArr))
                     {$secArr[$sec] += 1;}
                     else{$secArr[$sec] = 1;}
                     
                     if(key_exists($row[0]['date'], $Data))
                     {
                         if(key_exists($sec, $Data[$row[0]['date']]))
                         {
                             $Data[$row[0]['date']][$sec] += $row['t2']['length_in_sec'];
                             
                         }
                         else
                         {
                             $Data[$row[0]['date']][$sec] = $row['t2']['length_in_sec'];
                         }
                         
                     }
                     else
                     {
                         $Data[$row[0]['date']][$sec] = $row['t2']['length_in_sec'];
                     }
                     $total += 1;
            endforeach;
                 
                //print_r($Data); exit;
                 $Date = array_unique($Date);
                 echo "<table border='1'><tr style='background-color:#F90417; color:#FFFFFF;'><th>SUMMARY</th><th>MTD</th><th>%</th>";
                 foreach($Date as $d)
                 {
                     echo "<th>".$d."</th>";
                 }
                 echo "</tr>";
                 
                 echo "<tr><th>TOTAL ANSWERED CALLS</th><th>$total</th><th>100%</th>";
                 foreach($Date as $d)
                 {
                     echo "<th>".''."</th>";
                 }
                 echo "</tr>";
                 
                 echo "<tr><th></th><th></th><th></th>";
                 foreach($Date as $d)
                 {
                     echo "<th>".''."</th>";
                 }
                 echo "</tr>";
                 
                 
                 
                 echo "<tr><th>ANSWERED CALLS DETAILS<th><th></th>";
                 foreach($Date as $d)
                 {
                     echo "<th>".''."</th>";
                 }
                 echo "</tr>";
                 
                 //print_r($secArr); 
                 ksort($secArr);
                 foreach($secArr as $k=>$v)
                 {
                     echo "<tr><th>"."<".(($k+1)*5)."</th>";
                     echo "<th>$v</th>";
                     echo "<th>".round($v*100/$total,2)."%</th>";
                    foreach($Date as $d)
                    {
                       echo "<td>".$Data[$d][$k]."</td>"; 
                    }
                    echo "</tr>";
                 }
                 echo "</table>";
                
                exit;
        }
    }
    
    public function export_answer_call() {
       $this->layout='user';
        if($this->request->is("POST")){
            $catgoryArray=array();
            
            $search     =$this->request->data['MisReports'];
            $clientId   = $this->Session->read('companyid');
            $Campagn  = $this->Session->read('campaignid');
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            
            $condition['ClientId']=$clientId;
            $condition['date(CallDate) >=']=date("Y-m-d",$startdate);
            $condition['date(CallDate) <=']=date("Y-m-d",$enddate);
            
             // START TOTAL ANSWERED CALL QUERY 
            
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            
            $CampagnId ="campaign_id in ($Campagn)"; 
            
            $qry1 = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,t2.length_in_sec, IF(t2.status IS NULL OR t2.status='DROP',1,0) `Abandon`
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE date(t2.call_date) between '$start_time_start' AND '$start_time_end' AND $CampagnId AND
t2.term_reason!='AFTERHOURS' and IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A'  OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B',true,false) and t2.user !='vdcl'";
  
            $this->vicidialCloserLog->useDbConfig = 'db2';
            $dt1= $this->vicidialCloserLog->query($qry1);
            
            //print_r($dt1); exit; 
            $secArr=array();
            foreach($dt1 as $row):
            
                     
                 $sec = round($row['t2']['length_in_sec']/5,0);
                     $Date[] = $row[0]['date'];
                     
                     
                     if(array_key_exists("".$sec, $secArr))
                     {$secArr[$sec] +=1;
                     }
                     else{$secArr[$sec] = 1;}
                     
                     
                     if(array_key_exists($row[0]['date'], $Data))
                     {
                         if(array_key_exists($sec, $Data[$row[0]['date']]))
                         {
                             $Data[$row[0]['date']][$sec] += $row['t2']['length_in_sec'];
                             
                         }
                         else
                         {
                             $Data[$row[0]['date']][$sec] = $row['t2']['length_in_sec'];
                         }
                         
                     }
                     else
                     {
                         $Data[$row[0]['date']][$sec] = $row['t2']['length_in_sec'];
                     }
                     $total += 1;
            endforeach;
            
            
                //print_r($secArr); 
            
            
                 $Date = array_unique($Date);
                 $html = "<tr><th>SUMMARY</th><th>MTD</th><th>%</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".$d."</th>";
                 }
                 $html .= "</tr>";
                 
                 $html .= "<tr><th>TOTAL ANSWERED CALLS</th><th>$total</th><th>100%</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".''."</th>";
                 }
                 $html .= "</tr>";
                 
                 $html .= "<tr><th></th><th></th><th></th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".''."</th>";
                 }
                 $html .= "</tr>";
                 
                 
                 
                 $html .= "<tr><th>ANSWERED CALLS DETAILS<th><th></th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".''."</th>";
                 }
                 $html .= "</tr>";
                 
                 //print_r($secArr); 
                 ksort($secArr);
                 foreach($secArr as $k=>$v)
                 {
                     $html .= "<tr><th>"."<".(($k+1)*5)." Sec</th>";
                     $html .= "<th>$v</th>";
                     $html .= "<th>".round($v*100/$total,2)."%</th>";
                    foreach($Date as $d)
                    {
                       $html .= "<td>".$Data[$d][$k]."</td>"; 
                    }
                    $html .= "</tr>";
                 }
                $this->set('html',$html);
        }
    }
    

    
     public function category_reports(){
        $this->layout='user';
        $clientId   = $this->Session->read('companyid');
        $this->set('category',$this->EcrRecord->find('list',array('fields'=>array('ecrName','ecrName'),'conditions'=>array('Client'=>$clientId,'Label'=>1,'parent_id'=>NULL))));
  
        if($this->request->is("POST")){
            $DateWiseCategory=array();
            $DateWiseSubCategory=array();
            $search     = $this->request->data['MisReports'];
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            $categoryName = $search['category'];
            
            $data = $this->CallRecord->query("SELECT DATE_FORMAT(CallDate,'%d-%b-%Y') `date`,Category1,Category2,COUNT(1)`count` FROM call_master cm
WHERE ClientId=$clientId and Category1='$categoryName' and date(cm.CallDate) between '$start_time_start' and '$start_time_end' GROUP BY DATE(CallDate),Category2");
            
           
            
            
            foreach($data as $row):
                $date[$row[0]['date']] += $row[0]['count'];
                $type[] = $row['cm']['Category2'];
                $total +=$row[0]['count']; 
                $Data[$row['cm']['Category2']][$row[0]['date']]=$row;
                $category = $row['cm']['Category1'];
                $category2[$row['cm']['Category2']] += $row[0]['count']; 
            endforeach;
            
           
            $html .= "<tr><th>Summary</th><th>MTD</th><th>%</th>";
            
            $keys = array_keys($date);
            
            foreach($keys as $k)
            {
                $html .= "<th>".$k."</th>";
            }
            $html .= "</tr>";
            
            $html .= "<tr><th>$category</th><th>$total</th><th></th>";
            
            foreach($keys as $k)
            {
                $html .= "<th>".$date[$k]."</th>";
            }
            $html .= "</tr>";
            
            $keys2 = array_keys($category2);
            //$keys2 = array_unique($keys2);
            
            foreach($keys2 as $k)
            {
                $html .= "<tr>";
                    $html .= "<th>$k</th>";
                    $html .= "<th>$category2[$k]</th>";
                    $html .= "<th>".round($category2[$k]*100/$total,0)."%</th>";
                    
                    foreach($keys as $k1)
                    {
                        $html .= "<td>";
                            $html .= $Data[$k][$k1][0]['count'];
                        $html .= "</td>";
                    }
                    
                $html .= "</tr>";
            }
          
            
            $this->set('html',$html);
           
        }
    }
    
    public function category_wise(){
        $this->layout='ajax';
        if($this->request->is("POST")){
            $DateWiseCategory=array();
            $DateWiseSubCategory=array();
                 
            $clientId   = $this->Session->read('companyid');
            $search     = $this->request->data['MisReports'];
            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);
            $start_time_start = date("Y-m-d",$startdate);
            $start_time_end   = date("Y-m-d",$enddate);
            $categoryName = $search['category'];
            
            $data = $this->CallRecord->query("SELECT DATE_FORMAT(CallDate,'%d-%b-%Y') `date`,Category1,Category2,COUNT(1)`count` FROM call_master cm
WHERE ClientId=$clientId and Category1='$categoryName' and date(cm.CallDate) between '$start_time_start' and '$start_time_end' GROUP BY DATE(CallDate),Category2");
            
           
            
            
            foreach($data as $row):
                $date[$row[0]['date']] += $row[0]['count'];
                $type[] = $row['cm']['Category2'];
                $total +=$row[0]['count']; 
                $Data[$row['cm']['Category2']][$row[0]['date']]=$row;
                $category = $row['cm']['Category1'];
                $category2[$row['cm']['Category2']] += $row[0]['count']; 
            endforeach;
            
            echo "<table border='2'>";
            echo "<tr style='background-color:#F90417; color:#FFFFFF;'><th>Summary</th><th>MTD</th><th>%</th>";
            
            $keys = array_keys($date);
            
            foreach($keys as $k)
            {
                echo "<th>".$k."</th>";
            }
            echo "</tr>";
            
            echo "<tr><th>$category</th><th>$total</th><th></th>";
            
            foreach($keys as $k)
            {
                echo "<th>".$date[$k]."</th>";
            }
            echo "</tr>";
            
            $keys2 = array_keys($category2);
            //$keys2 = array_unique($keys2);
            
            foreach($keys2 as $k)
            {
                echo "<tr>";
                    echo "<th>$k</th>";
                    echo "<th>$category2[$k]</th>";
                    echo "<th>".round($category2[$k]*100/$total,0)."%</th>";
                    
                    foreach($keys as $k1)
                    {
                        echo "<td>";
                            echo $Data[$k][$k1][0]['count'];
                        echo "</td>";
                    }
                    
                echo "</tr>";
            }
            echo "</table>"; 
            
            $fileName = "abend_call_mis".date('d_m_y_h_i_s');
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=".$fileName.".xls");
            header("Pragma: no-cache");
            header("Expires: 0"); 
            die;

           
        }
    }

     
    public function export_esclation_level_mis() {
        $this->layout='user';
        if($this->request->is("POST")){
            $catgoryArray=array();
            $TotalAns=array();
            
            $search     =$this->request->data['MisReports'];
            $clientId   = $this->Session->read('companyid');
            $Campagn  = $this->Session->read('campaignid');
            
            $start_date = date("Y-m-d",strtotime($search['startdate']));
            $end_date = date("Y-m-d",strtotime($search['enddate']));
            
            
         
            // START TOTAL TAGGED CASE 
    
            $totalTag=count($this->CallRecord->find('all',array('fields'=>array('Id'),'conditions'=>$condition)));

            $qry="
            SELECT DATE_FORMAT(cm.CallDate,'%d-%b-%Y')`date`,COUNT(1)`count2`,Category1,IFNULL(alertType1,alertType2)`AlertType`,
SUM(IF(cj.alertOn='both',IFNULL(count1,0)+IFNULL(count2,0),IF(cj.alertOn='email',IFNULL(count1,0),IFNULL(count2,0)))) `count`
FROM call_master cm 
INNER JOIN crone_job cj ON cm.id=cj.data_id
LEFT JOIN(SELECT data_id `data_id1`,alertType `alertType1`,SUM(mail_status)`count1` FROM mail_log WHERE alertType !='Alert' AND alertType !='Report' GROUP BY data_id,alertType)AS ml 
ON cm.id=ml.data_id1 AND cj.alertType = ml.alertType1
LEFT JOIN(SELECT data_id `data_id2`,alertType `alertType2`,SUM(sms_status)`count2` FROM sms_log WHERE alertType !='Alert' AND alertType !='Report' GROUP BY data_id,alertType)AS sl 
ON cm.id=sl.data_id2 AND cj.alertType = sl.alertType2
WHERE cj.alertType !='Alert' AND cj.alertType !='Report' and cm.ClientId='$clientId' and date(cm.CallDate) between '$start_date' and '$end_date' 
GROUP BY DATE(cm.CallDate),Category1,cj.alertType
            ";
            
            $EscData=$this->CroneJob->query($qry);
           
            // GET TOTAL ESCLATION COUNT
           foreach($EscData as $row)
            //while($row = mysql_fetch_assoc($execute))
                 {
                     $Date[] = $row[0]['date'];
                     
                     if($row[0]['AlertType']!='')
                     {
                     if(key_exists($row[0]['AlertType'],$Escalation))
                     $Escalation[$row[0]['AlertType']] += $row[0]['count'];
                     else
                     $Escalation[$row[0]['AlertType']] = $row[0]['count'];
                     }
                     
                     if(key_exists($row['cm']['Category1'],$CategoryArr))
                     {
                         if(key_exists($row[0]['AlertType'],$CategoryArr[$row['cm']['Category1']]))
                         $CategoryArr[$row['cm']['Category1']][$row[0]['AlertType']] += $row[0]['count'];
                         else
                         $CategoryArr[$row['cm']['Category1']][$row[0]['AlertType']] = $row[0]['count'];
                     }
                     else
                     {$CategoryArr[$row['cm']['Category1']][$row[0]['AlertType']] = $row[0]['count'];}
                     
                     $DataArr2[$row[0]['AlertType']][$row[0]['date']] = $row[0]['count'];
                     
                     $DateArr[$row[0]['date']]['esc'] += $row[0]['count'];
                     $DateArr[$row[0]['date']]['tag'] += $row[0]['count2'];
                     
                     if($row[0]['AlertType']!='')
                     {$Data[$row[0]['date']][$row['cm']['Category1']][$row[0]['AlertType']]=$row[0]['count'];}
                     $totalEsc += $row[0]['count'];
                     $total += $row[0]['count2'];
                     $category[] = $row['cm']['Category1'];
                     
                 }
                 
                 //print_r($Data); //exit;
                 $Date = array_unique($Date);
                 $html = "<tr><th>SUMMARY</th><th>MTD</th><th>%</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".$d."</th>";
                 }
                 $html .= "</tr>";
                 
                 $html .= "<tr><th>TOTAL TAGGED CASES</th><th>$total</th><th></th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".$DateArr[$d]['tag']."</th>";
                 }
                 $html .= "</tr>";
                 
                 
                 $html .= "<tr><th>TOTAL ESCALATED CASES</th><th>$totalEsc</th>";
                 $html .="<th>".round($totalEsc*100/$total,0)."%</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".$DateArr[$d]['esc']."</th>";
                 }
                 $html .= "</tr>";
                 $html .= "<tr></tr>";
                 //print_r($Escalation); //exit;
                 $keys = array_keys($Escalation);
                 //$keys = array_unique($Escalation);
                 $category = array_unique($category);
                 //print_r($keys); //exit;
                 foreach($keys as $k)
                 {
                     $html .= "<tr>";
                     $html .= "<th>".$k."</th>";
                     $html .= "<th>".$Escalation[$k]."</th>";
                     $total1 =$Escalation[$k];
                     $html .= "<th>".round($Escalation[$k]*100/$totalEsc,0)."%</th>";
                     
                     foreach($Date as $d)
                     {
                         $html .= "<th>".$DataArr2[$k][$d]."</th>"; 
                     }
                     $html .= "</tr>";
                     
                     foreach($category as $c)
                     {
                        $html .= "<tr>";
                        $html .= "<td>$c</td>";
                        $html .= "<td>".$CategoryArr[$c][$k]."</td>";
                        $html .= "<td>".round($CategoryArr[$c][$k]*100/$total1,0)."</td>";
                        
                        foreach($Date as $d)
                        {
                            $html .= "<td>".$Data[$d][$c][$k]."</td>"; 
                        }
                        $html .= "</tr>";
                        
                     }
                 }
                
               $this->set('html',$html);
             
        }
    }


    public function esclation_level_mis() {
        $this->layout='ajax';
        if($this->request->is("POST")){
            $catgoryArray=array();
            $TotalAns=array();
            
            $search     =$this->request->data['MisReports'];
            $clientId   = $this->Session->read('companyid');
            $Campagn  = $this->Session->read('campaignid');
            
            $start_date = date("Y-m-d",strtotime($search['startdate']));
            $end_date = date("Y-m-d",strtotime($search['enddate']));
            
            
         
            // START TOTAL TAGGED CASE 
    
            $totalTag=count($this->CallRecord->find('all',array('fields'=>array('Id'),'conditions'=>$condition)));

            $qry="
            SELECT DATE_FORMAT(cm.CallDate,'%d-%b-%Y')`date`,COUNT(1)`count2`,Category1,IFNULL(alertType1,alertType2)`AlertType`,
SUM(IF(cj.alertOn='both',IFNULL(count1,0)+IFNULL(count2,0),IF(cj.alertOn='email',IFNULL(count1,0),IFNULL(count2,0)))) `count`
FROM call_master cm 
INNER JOIN crone_job cj ON cm.id=cj.data_id
LEFT JOIN(SELECT data_id `data_id1`,alertType `alertType1`,SUM(mail_status)`count1` FROM mail_log WHERE alertType !='Alert' AND alertType !='Report' GROUP BY data_id,alertType)AS ml 
ON cm.id=ml.data_id1 AND cj.alertType = ml.alertType1
LEFT JOIN(SELECT data_id `data_id2`,alertType `alertType2`,SUM(sms_status)`count2` FROM sms_log WHERE alertType !='Alert' AND alertType !='Report' GROUP BY data_id,alertType)AS sl 
ON cm.id=sl.data_id2 AND cj.alertType = sl.alertType2
WHERE cj.alertType !='Alert' AND cj.alertType !='Report' and cm.ClientId='$clientId' and date(cm.CallDate) between '$start_date' and '$end_date'
GROUP BY DATE(cm.CallDate),Category1,cj.alertType
            ";
            
            $EscData=$this->CroneJob->query($qry);
           
            // GET TOTAL ESCLATION COUNT
           foreach($EscData as $row)
            //while($row = mysql_fetch_assoc($execute))
                 {
                     $Date[] = $row[0]['date'];
                     
                     if($row[0]['AlertType']!='')
                     {
                     if(key_exists($row[0]['AlertType'],$Escalation))
                     $Escalation[$row[0]['AlertType']] += $row[0]['count'];
                     else
                     $Escalation[$row[0]['AlertType']] = $row[0]['count'];
                     }
                     
                     if(key_exists($row['cm']['Category1'],$CategoryArr))
                     {
                         if(key_exists($row[0]['AlertType'],$CategoryArr[$row['cm']['Category1']]))
                         $CategoryArr[$row['cm']['Category1']][$row[0]['AlertType']] += $row[0]['count'];
                         else
                         $CategoryArr[$row['cm']['Category1']][$row[0]['AlertType']] = $row[0]['count'];
                     }
                     else
                     {$CategoryArr[$row['cm']['Category1']][$row[0]['AlertType']] = $row[0]['count'];}
                     
                     $DataArr2[$row[0]['AlertType']][$row[0]['date']] = $row[0]['count'];
                     
                     $DateArr[$row[0]['date']]['esc'] += $row[0]['count'];
                     $DateArr[$row[0]['date']]['tag'] += $row[0]['count2'];
                     
                     if($row[0]['AlertType']!='')
                     {$Data[$row[0]['date']][$row['cm']['Category1']][$row[0]['AlertType']]=$row[0]['count'];}
                     $totalEsc += $row[0]['count'];
                     $total += $row[0]['count2'];
                     $category[] = $row['cm']['Category1'];
                     
                 }
                 
                 //print_r($Data); //exit;
                 $Date = array_unique($Date);
                 $html = "<table border='2'><tr style='background-color:#F90417; color:#FFFFFF;' ><th>SUMMARY</th><th>MTD</th><th>%</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".$d."</th>";
                 }
                 $html .= "</tr>";
                 
                 $html .= "<tr><th>TOTAL TAGGED CASES</th><th>$total</th><th></th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".$DateArr[$d]['tag']."</th>";
                 }
                 $html .= "</tr>";
                 
                 
                 $html .= "<tr><th>TOTAL ESCALATED CASES</th><th>$totalEsc</th>";
                 $html .="<th>".round($totalEsc*100/$total,0)."%</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".$DateArr[$d]['esc']."</th>";
                 }
                 $html .= "</tr>";
                 $html .= "<tr></tr>";
                 //print_r($Escalation); //exit;
                 $keys = array_keys($Escalation);
                 //$keys = array_unique($Escalation);
                 $category = array_unique($category);
                 //print_r($keys); //exit;
                 foreach($keys as $k)
                 {
                     $html .= "<tr>";
                     $html .= "<th>".$k."</th>";
                     $html .= "<th>".$Escalation[$k]."</th>";
                     $total1 =$Escalation[$k];
                     $html .= "<th>".round($Escalation[$k]*100/$totalEsc,0)."%</th>";
                     
                     foreach($Date as $d)
                     {
                         $html .= "<th>".$DataArr2[$k][$d]."</th>"; 
                     }
                     $html .= "</tr>";
                     
                     foreach($category as $c)
                     {
                        $html .= "<tr>";
                        $html .= "<td>$c</td>";
                        $html .= "<td>".$CategoryArr[$c][$k]."</td>";
                        $html .= "<td>".round($CategoryArr[$c][$k]*100/$total1,0)."</td>";
                        
                        foreach($Date as $d)
                        {
                            $html .= "<td>".$Data[$d][$c][$k]."</td>"; 
                        }
                        $html .= "</tr>";
                        
                     }
                 }
                $html .= "</table>";
                echo $html;  
                
                $fileName = "abend_call_mis".date('d_m_y_h_i_s');
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=".$fileName.".xls");
                header("Pragma: no-cache");
                header("Expires: 0"); 
                exit;
             
        }
    }

    public function getTag($totalTag,$condition){
        $callArr=$this->CallRecord->find('all',array('fields'=>array('Id'),'conditions'=>$condition));
        $total=count($callArr);
        $persent=$total/$totalTag*100;
        return array('total'=>$total,'persent'=>$persent);   
    }
    
    public function getDateArray($FromDate,$ToDate){
        if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
        if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
        while(strtotime($FromDate)<strtotime($ToDate)){
            $start_time_start=$FromDate;
            $event_date_start=$ToDate;
            $start_time_end=date("Y-m-d 23:59:59",strtotime("$FromDate"));
            $NextDate=date("Y-m-d H:i:s",strtotime("$FromDate +1 day"));
            $FromDate=$NextDate;

            $timeLabel=date("d-M-Y",strtotime($start_time_start));
            $dateLabel=date("F-Y",strtotime($start_time_start));
            $datetimeArray[$dateLabel][]=$timeLabel;
        }
        return $datetimeArray;
    }
    
    
    public function export_excel($SumeryDetails,$exportArray2){
        require_once(APP . 'Lib' . DS . 'Classes' . DS . 'PHPExcel.php');
        $objPHPExcel = new PHPExcel();
        
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
         $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'F90417'),
                
            )
        ))->getFont()->setBold(true)
                                ->setName('Verdana')
                                ->setSize(10)
                                ->getColor()->setRGB('fffff');
         $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45);
         
         
       // print_r($objPHPExcel);
       
       
        
        
        $objWorksheet->fromArray($SumeryDetails);

            $dataseriesLabels1 = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$B$1', NULL, 1),   //  Temperature
            );
            $dataseriesLabels2 = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$C$1', NULL, 1),   //  Rainfall
            );
            $dataseriesLabels3 = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$D$1', NULL, 1),   //  Humidity
            );


            $xAxisTickValues = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$2:$A$13', NULL, 12),    //  Jan to Dec
            );

            $dataSeriesValues1 = array(
                new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$B$2:$B$13', NULL, 12),
            );

            //  Build the dataseries
            $series1 = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_BARCHART,       // plotType
                PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,  // plotGrouping
                range(0, count($dataSeriesValues1)-1),          // plotOrder
                $dataseriesLabels1,                             // plotLabel
                $xAxisTickValues,                               // plotCategory
                $dataSeriesValues1                              // plotValues
            );

            $series1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

            $dataSeriesValues2 = array(
                new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$C$2:$C$13', NULL, 12),
            );

            //  Build the dataseries
            $series2 = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_LINECHART,      // plotType
                PHPExcel_Chart_DataSeries::GROUPING_STANDARD,   // plotGrouping
                range(0, count($dataSeriesValues2)-1),          // plotOrder
                $dataseriesLabels2,                             // plotLabel
                NULL,                                           // plotCategory
                $dataSeriesValues2                              // plotValues
            );

            $dataSeriesValues3 = array(
                new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$D$2:$D$13', NULL, 12),
            );

            //  Build the dataseries
            $series3 = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_AREACHART,      // plotType
                PHPExcel_Chart_DataSeries::GROUPING_STANDARD,   // plotGrouping
                range(0, count($dataSeriesValues2)-1),          // plotOrder
                $dataseriesLabels3,                             // plotLabel
                NULL,                                           // plotCategory
                $dataSeriesValues3                              // plotValues
            );


            //  Set the series in the plot area
            $plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series1, $series2, $series3));
            //  Set the chart legend
            $legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

            $title = new PHPExcel_Chart_Title('DASHBOARD');


            //  Create the chart
            $chart = new PHPExcel_Chart(
                'chart1',       // name
                $title,         // title
                $legend,        // legend
                $plotarea,      // plotArea
                true,           // plotVisibleOnly
                0,              // displayBlanksAs
                NULL,           // xAxisLabel
                NULL            // yAxisLabel
            );

            $chart->setTopLeftPosition('D1');
            $chart->setBottomRightPosition('M15');
            $objWorksheet->addChart($chart);

            
        //=====================================
           if($exportArray2!=NULL){
            $objPHPExcel->createSheet();

// Add some data to the second sheet, resembling some different data types
       $objWorksheet= $objPHPExcel->setActiveSheetIndex(1);
       //print_r($exportArray2[0]); EXIT;
    $col = count($exportArray2[0]);
      //print_r($exportArray2);  exit;
       $array=array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M',14=>'N',15=>'O',16=>'P',17=>'Q',18=>'R',19=>'S',20=>'T',21=>'U',22=>'V',23=>'W',24=>'X',25=>'Y',26=>'Z');
// Rename 2nd sheet
//$objPHPExcel->getActiveSheet()->setTitle('Second sheet'); 
       $a = round($col/26,0);
       $b = $col%26;
       $ch = $array[$a].$array[$b].'1';
       //echo $ch; exit;
       
        $objPHPExcel->getActiveSheet()->getStyle("A1:$ch")->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'F90417'),
                
            )
        ))->getFont()->setBold(true)
                                ->setName('Verdana')
                                ->setSize(10)
                                ->getColor()->setRGB('fffff');
         $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45);      
            
             $objWorksheet->fromArray($exportArray2);
             
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