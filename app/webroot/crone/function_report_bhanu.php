<?php 
session_start();
function sample_report($condition,$clientId,$name,$emailId,$db1,$reptype)
{ 
    $tArr   =   mysqli_query($db1,"SELECT * FROM agent_master where category is not null");

    $dataArr  = array();
    $category = array();
    $process  = array();
    //print_r($tArr);
    while($data = mysqli_fetch_assoc($tArr))
    {

        $category[] = $data['category'];
        $process[] = $data['processname'];

        $dataArr[$data['category']][$data['processname']] += 1;


    }

    $html = "<table cellspacing='0' border='1'>";
    
    $category = array_unique($category);
    $process = array_unique($process);

        $html .= "<tr>";
        $html .= "<th>Category</th>";
        foreach($process as $pro)
        {
            $html .= "<th>".$pro."</th>";
        }
        $html .= "<th>Total</th>";
        $html .= "</tr>";
      
        $total = 0; 
    foreach($category as $cat)
    {
        
        $html .= "<tr>";
        $html .= "<th>".$cat."</th>";
        $grand_total = 0;  
        foreach($process as $pro)
        {
          
            $html .= "<td>".$dataArr[$cat][$pro]."</td>";
            $grand_total += $dataArr[$cat][$pro];
            $total += $dataArr[$cat][$pro];
        }
        $html .= "<td>".$grand_total."</td>";
        $html .= "</tr>";

    }
    $html .= "<tr>";
    $html .= "<th>Total</th>";
    $html .= "<td>".$total."</td>";
    $html .= "<td>".$total."</td>";
    $html .= "<td>".$total."</td>";
    $html .= "</tr>";
    
    $html .= "</table>";
 
    echo $html;
    return $html; 
                
    //mail_send($html,'corrective_report_'.$reptype,$name,$emailId,$clientId,'Corrective Report '.$reptype);
}
function sheet2($condition,$clientId,$name,$emailId,$db1,$reptype)
{
    //$tArr   =   mysqli_query($db1,"SELECT * FROM call_master WHERE ClientId='$clientId' AND $condition order by Category3 ASC");
     $enddate = date('Y-m-d', strtotime('-1 day'));
     $startdate   = date('Y-m-d', strtotime('-2 day'));

    $tArr   =   mysqli_query($db1,"SELECT * FROM call_master WHERE ClientId='$clientId' AND date(CallDate) >= '$startdate' and date(CallDate) <='$enddate'");
    $dataArr = array();
    $datetimeArray = array();
    $format = 'd-F-Y';
    //$dates = array();


    $startdate=$startdate.' 00:00:00';
    $enddate=$enddate.' 23:59:59';
    $current = strtotime($startdate);
    $date2 = strtotime($enddate);
    $stepVal = '+1 day';

    while($current <= $date2 ) {
       $dates = date($format, $current);
       $datetimeArray[] = $dates;
       $current = strtotime($stepVal, $current);

      
        while($calldata = mysqli_fetch_assoc($tArr))
        {
           // print_r($calldata);
            $cur = strtotime($calldata['CallDate']);
            $dates1 = date($format, $cur);
            if(empty($calldata['CloseLoopCate1']))
            {
              $dataArr[$calldata['Category2']]['open'][$dates1] +=1;
            }
            else
            {
               $dataArr[$calldata['Category2']]['close'][$dates1] +=1; 
            }

            $dataArr[$calldata['Category2']]['data']  +=1;
            //
        }
     
    }
    //print_r($datetimeArray);die;

            $html = "<table cellpadding='2' cellspacing='2' border='2' class='table table-striped table-bordered'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<th></th>";
            foreach($dataArr as $dataLabe=>$dataSub)
            {
                $html .= "<th>".$dataLabe."</th>";
            }
            $html .= "</tr>";
            $html .= "</thead>";


            $html .= "<tbody>";
            foreach($datetimeArray as $dateLabel)
            {
                $html .= "<tr>";
                $html .= "<th>".$dateLabel."</th>";
                    
                $html .= "</tr>";

                $html .= "<tr>";
                $html .= "<td>Open</td>";
                $total_open=0; 
                foreach($dataArr as $dataLabel=>$dataSub) 
                {
                    $html .= "<td>".$dataSub['open'][$dateLabel]."</td>";
                    $total_open+=$dataSub['open'][$dateLabel];
                }
                    
                $html .= "</tr>";

                $html .= "<tr>";
                $html .= "<td>Close</td>";
                $total_open=0; 
                foreach($dataArr as $dataLabel=>$dataSub) 
                {
                    $html .= "<td>".$dataSub['close'][$dateLabel]."</td>";
                    $total_open+=$dataSub['close'][$dateLabel];
                }
                    
                $html .= "</tr>";

            }
            $html .= "<tr>";
            $html .= "<th>Grand Total</th>";
            foreach($dataArr as $dataLabel=>$dataSub)
            {
                $html .= "<th>".$dataSub['data']."</th>";
            }
            $html .= "</tr>";




                $html .= "</tbody>";

           

          
                $html .= "</table>";
                  echo $html;
                  return $html; 
                
   // mail_send($html,'in_call_detail'.$reptype,$name,$emailId,$clientId,'In Call Detail '.$reptype);
}

function process_wise($condition,$clientId,$campaignId,$name,$emailId,$db1,$reptype,$db2)
{ 
    //$html = $campaignId;
    $FromDate = date('Y-m-01',strtotime('-1 Month'));
    $ToDate   = date('Y-m-d', strtotime('last day of last month'));
   
    if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
    if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
    $TotalCall = '';
    $totalcount = '';
    $Arr = array();
    $Arr2 = array();
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
            WHERE $view_date1 and $campaignId and t2.phone_number in('09680193319','07884214478','08369692722','06239179641')  and t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL GROUP BY date(t2.call_date) ";

          $qry1 = "SELECT COUNT(*) `Total`,
            SUM(if(t2.`user` !='VDCL',1,0)) `Answered`,
            SUM(if(t2.`user` ='VDCL',1,0)) `Abandon`,
            date(t2.call_date) as gdate                            
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid 
            WHERE $view_date1 and $campaignId and t2.phone_number in('08045994699','08042994299')  and t2.term_reason!='AFTERHOURS' AND  t2.lead_id IS NOT NULL GROUP BY date(t2.call_date) ";

            $data_q   =   mysqli_query($db2,$qry); 
            $dt = mysqli_fetch_assoc($data_q);
            $data_q1   =   mysqli_query($db2,$qry1);  
            $dt1 = mysqli_fetch_assoc($data_q1);
            
          $qry2 = "SELECT SUM(IF(TagStatus IS NULL,0,1)) Callback FROM aband_call_master 
            WHERE DATE(Calldate) BETWEEN '$start_time_start' AND '$start_time_end' and ClientId ='$clientId' GROUP BY DATE(Calldate),ClientId,CallType";

            //$Abanddata=$this->AbandCallMaster->query($qry2);
            $data_q2   =   mysqli_query($db1,$qry2);
            $Abanddata = mysqli_fetch_assoc($data_q2);
            
             $timeLabel=date("d-M-Y",strtotime($start_time_start));
             $dateLabel=date("F-Y",strtotime($start_time_start));
             $datetimeArray[$dateLabel][]=$timeLabel;
            
            // //$data['Offered %'][$dateLabel][$timeLabel]='';
             $total=$dt['Answered']+$dt1['Answered'];

             $data['Everest Indiamart (Recieved Calls)'][$dateLabel][$timeLabel]=$dt1['Answered'];
             $data['Everest Industries Toll Free (Recieved Calls)'][$dateLabel][$timeLabel] = $dt['Answered'];

             $data['Total Recieved Calls'][$dateLabel][$timeLabel]=$total;

             $total1=$dt['Abandon']+$dt1['Abandon'];

             $data['Everest Indiamart (Abandoned Calls)'][$dateLabel][$timeLabel]=$dt1['Abandon'];
             $data['Everest Industries Toll Free (Abandoned Calls)'][$dateLabel][$timeLabel] = $dt['Abandon'];

             $data['Total Abandoned'][$dateLabel][$timeLabel]=$total1;

             $data['Call Back On Abandoned Calls'][$dateLabel][$timeLabel]=$Abanddata['Callback'];

             $data['Grand Total'][$dateLabel][$timeLabel] = $dt['Total']+$dt1['Total']+$Abanddata['Callback'];

            
	}
    //print_r($DateArr);die;
            $html = "<table cellspacing='0' border='1'>";
           
            $html .= "<tr style='background-color:#317EAC; color:#FFFFFF;'>";
            $html .= "<th rowspan='2'>Scenario</th>";
            foreach($datetimeArray as $dateLabel=>$timeArray) { $html .= "<th colspan=\"".count($timeArray)."\">$dateLabel</th>"; }
            $html .= "</tr>";

            $html .= "<tr style='background-color:#317EAC; color:#FFFFFF;'>";
            foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { $html .= "<th>$timeLabel</th>"; } }
            $html .= "</tr>";

            $html .= "</thead>";
            $html .= "<tbody>";
            foreach($data as $dataLabel=>$dataSub) { 
                $html .= "<tr>";
                $html .= "<td>".$dataLabel."</td>";
                foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { $html .= "<td>{$dataSub[$dateLabel][$timeLabel]}</td>"; } }
                $html .= "</tr>";
            }
            $html .= "</tbody>";
            $html .= "</table>";

    //   /print_r($value);
      echo $html;
       return $html; 

                
      // mail_send($html,'sla_report_'.$reptype,$name,$emailId,$clientId,'SLA Report '.$reptype);
             
   // export_excel($exportArr1,$exportArr2,'tagging_mis_'.$reptype,$name,$emailId,$clientId,'Tagging mis '.$reptype,$db1);   
        
}

function everest_indiamart($condition,$clientId,$name,$emailId,$db1,$reptype)
{

    $tArr   =   mysqli_query($db1,"SELECT * FROM call_master WHERE ClientId='$clientId' AND $condition and MSISDN='8042994299'");

    //$tArr   =   mysqli_query($db1,"SELECT * FROM call_master WHERE ClientId='$clientId' AND $condition order by Category3 ASC");

    $ecr = mysqli_query($db1,"select label,ecrName from ecr_master where Client='$clientId' GROUP BY Label ORDER BY Label ASC");
    $fieldName = mysqli_query($db1,"select * from field_master where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC");
    $fieldName1 = mysqli_query($db1,"select * from close_field where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC");
	
    $fieldSearch = mysqli_query($db1,"select fieldNumber from field_master where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC");
    $headervalue = array();
    while($f = mysqli_fetch_assoc($fieldSearch))
    {
        $headervalue[] = 'Field'.$f['fieldNumber']; 
    }

    $fieldSearch1 =  mysqli_query($db1,"select fieldNumber from close_field where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC");
    $headervalue1=array();
    while($f1 = mysqli_fetch_assoc($fieldSearch1)){
        $headervalue1[] = 'CField'.$f1['fieldNumber']; 
    }
    //print_r($headervalue1);


            $html = "<table cellspacing='0' border='1'>";
            $html .= "<tr>";
            $html .= "<th>IN CALL ID</th>";
            $html .= "<th>CALL FROM</th>";
            $keyss = array();
            while($k = mysqli_fetch_assoc($ecr))
            {
                $keyss[] = $k['label'];
                $no=$k['label']-1;
                                    
                if($k['label'] =='1'){
                    $html .= "<th>SCENARIO</th>";
                }
                else {
                    $html .= "<th>"."SUB SCENARIO".$no."</th>";
                }
            }
            while($post = mysqli_fetch_assoc($fieldName)){
                $html .= "<th>".$post['FieldName']."</th>";
            }
            //print_r($keyss);
            $html .= "<th>Call Action</th>";
            $html .= "<th>Call Sub Action</th>";
            $html .= "<th>Call Action Remarks</th>";
            $html .= "<th>Closer Date</th>";
            $html .= "<th>Follow Up Date</th>";
            $html .= "<th>Case Close By</th>";
            $html .= "<th>Call Date</th>";
            $html .= "<th>Tat</th>";
            $html .= "<th>Due Date</th>";
            $html .= "<th>Call Created</th>";
            $html .= "<th>Closer Time</th>";
            while($post = mysqli_fetch_assoc($fieldName1)){
                $html .= "<th>".$post['FieldName']."</th>";
            } 

            $html .= "<th>Call Status</th>";
            $html .= "<th>Return AWB</th>";
            $html .= "<th>Return Token</th>";
            $html .= "<th>Pickup Date</th>";
            $html .= "<th>Forword AWB</th>";
            $html .= "<th>Forword Token</th>";
            $html .= "<th>Pickup Date</th>";	 
            $html .= "</tr>";
            while($his = mysqli_fetch_assoc($tArr)){
                $html .= "<tr>";
                $html .= "<td>".$his['SrNo']."</td>";
                $html .= "<td>".$his['MSISDN']."</td>";
                foreach($keyss as $ke)
                {   
                    $html .= "<td>".$his["Category".$ke]."</td>";
                }
                foreach($headervalue as $header){
                    $html .= "<td>".$his[$header]."</td>";
                }

                if($his['CloseLoopingDate'] !=""){
                    $cld=$his['CloseLoopingDate'];
                }
                else{
                    $cld="";
                }
                
                if($cld !=""){
                $t1 = StrToTime ($cld);
                $t2 = StrToTime ($his['CallDate']);
                $diff = $t1 - $t2;
                $hours = $diff / ( 60 * 60 );
                }
                else{
                  $hours="";  
                }

                if(empty($his['CloseLoopCate1'])){
                     $html .=  "<td>Open</td>";
                }else{
                     $html .=  "<td>".$his['CloseLoopCate1']."</td>";
                }
                $html .= "<td>".$his['CloseLoopCate2']."</td>";
                $html .= "<td>".$his['closelooping_remarks']."</td>";
                $html .= "<td>".$cld."</td>";
                $html .= "<td>".$his['FollowupDate']."</td>";
                $html .= "<td>".$his['CaseCloseBy']."</td>";
                $html .= "<td>".$his['CallDate']."</td>";
                $html .= "<td>".$his['tat']."</td>";
                $html .= "<td>".$his['duedate']."</td>";
                $html .= "<td>".$his['callcreated']."</td>";
                $html .= "<td>".round($hours)."</td>";  
                
                foreach($headervalue1 as $header1){
                    $html .= "<td>".$his[$header1]."</td>";
                }
                $html .= "<td>".$his['AbandStatus']."</td>";

                if($his['AWBNo'] !=""){
                    $html .= "<td>".$his['AWBNo']."</td>";
                }
                else{
                    if($his['Category1']=="Return Request" && $his['AreaPincode'] !=""){
                    
                    $html .= "<td> Create AWB</td>";
                 
                    }
                    else{
                        $html .= "<td></td>";
                    }
                    
                }

                $html .= "<td>".$his['TokenNumber']."</td>";
                    if($his['AWBNo'] !=""){
                        $html .= "<td>".$his['CallDate']."</td>";
                    }
                    else{
                        $html .= "<td></td>"; 
                    }
                $html .= "<td>".$his['Ret_AWBNo']."</td>";
                $html .= "<td>".$his['Ret_TokenNumber']."</td>";
                $html .= "<td>".$his['Ret_PikupDate']."</td>";

                $html .= "</tr>";
    
            }
           

          
                $html .= "</table>";
                  echo $html;
                  return $html; 
                
   // mail_send($html,'in_call_detail'.$reptype,$name,$emailId,$clientId,'In Call Detail '.$reptype);
}

function everest_tollfree($condition,$clientId,$name,$emailId,$db1,$reptype)
{

    $tArr   =   mysqli_query($db1,"SELECT * FROM call_master WHERE ClientId='$clientId' AND $condition and MSISDN='9680193319'");

    //$tArr   =   mysqli_query($db1,"SELECT * FROM call_master WHERE ClientId='$clientId' AND $condition order by Category3 ASC");

    $ecr = mysqli_query($db1,"select label,ecrName from ecr_master where Client='$clientId' GROUP BY Label ORDER BY Label ASC");
    $fieldName = mysqli_query($db1,"select * from field_master where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC");
    $fieldName1 = mysqli_query($db1,"select * from close_field where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC");
	
    $fieldSearch = mysqli_query($db1,"select fieldNumber from field_master where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC");
    $headervalue = array();
    while($f = mysqli_fetch_assoc($fieldSearch))
    {
        $headervalue[] = 'Field'.$f['fieldNumber']; 
    }

    $fieldSearch1 =  mysqli_query($db1,"select fieldNumber from close_field where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC");
    $headervalue1=array();
    while($f1 = mysqli_fetch_assoc($fieldSearch1)){
        $headervalue1[] = 'CField'.$f1['fieldNumber']; 
    }
    //print_r($headervalue1);


            $html = "<table cellspacing='0' border='1'>";
            $html .= "<tr>";
            $html .= "<th>IN CALL ID</th>";
            $html .= "<th>CALL FROM</th>";
            $keyss = array();
            while($k = mysqli_fetch_assoc($ecr))
            {
                $keyss[] = $k['label'];
                $no=$k['label']-1;
                                    
                if($k['label'] =='1'){
                    $html .= "<th>SCENARIO</th>";
                }
                else {
                    $html .= "<th>"."SUB SCENARIO".$no."</th>";
                }
            }
            while($post = mysqli_fetch_assoc($fieldName)){
                $html .= "<th>".$post['FieldName']."</th>";
            }
            //print_r($keyss);
            $html .= "<th>Call Action</th>";
            $html .= "<th>Call Sub Action</th>";
            $html .= "<th>Call Action Remarks</th>";
            $html .= "<th>Closer Date</th>";
            $html .= "<th>Follow Up Date</th>";
            $html .= "<th>Case Close By</th>";
            $html .= "<th>Call Date</th>";
            $html .= "<th>Tat</th>";
            $html .= "<th>Due Date</th>";
            $html .= "<th>Call Created</th>";
            $html .= "<th>Closer Time</th>";
            while($post = mysqli_fetch_assoc($fieldName1)){
                $html .= "<th>".$post['FieldName']."</th>";
            } 

            $html .= "<th>Call Status</th>";
            $html .= "<th>Return AWB</th>";
            $html .= "<th>Return Token</th>";
            $html .= "<th>Pickup Date</th>";
            $html .= "<th>Forword AWB</th>";
            $html .= "<th>Forword Token</th>";
            $html .= "<th>Pickup Date</th>";	 
            $html .= "</tr>";
            while($his = mysqli_fetch_assoc($tArr)){
                $html .= "<tr>";
                $html .= "<td>".$his['SrNo']."</td>";
                $html .= "<td>".$his['MSISDN']."</td>";
                foreach($keyss as $ke)
                {   
                    $html .= "<td>".$his["Category".$ke]."</td>";
                }
                foreach($headervalue as $header){
                    $html .= "<td>".$his[$header]."</td>";
                }

                if($his['CloseLoopingDate'] !=""){
                    $cld=$his['CloseLoopingDate'];
                }
                else{
                    $cld="";
                }
                
                if($cld !=""){
                $t1 = StrToTime ($cld);
                $t2 = StrToTime ($his['CallDate']);
                $diff = $t1 - $t2;
                $hours = $diff / ( 60 * 60 );
                }
                else{
                  $hours="";  
                }

                if(empty($his['CloseLoopCate1'])){
                     $html .=  "<td>Open</td>";
                }else{
                     $html .=  "<td>".$his['CloseLoopCate1']."</td>";
                }
                $html .= "<td>".$his['CloseLoopCate2']."</td>";
                $html .= "<td>".$his['closelooping_remarks']."</td>";
                $html .= "<td>".$cld."</td>";
                $html .= "<td>".$his['FollowupDate']."</td>";
                $html .= "<td>".$his['CaseCloseBy']."</td>";
                $html .= "<td>".$his['CallDate']."</td>";
                $html .= "<td>".$his['tat']."</td>";
                $html .= "<td>".$his['duedate']."</td>";
                $html .= "<td>".$his['callcreated']."</td>";
                $html .= "<td>".round($hours)."</td>";  
                
                foreach($headervalue1 as $header1){
                    $html .= "<td>".$his[$header1]."</td>";
                }
                $html .= "<td>".$his['AbandStatus']."</td>";

                if($his['AWBNo'] !=""){
                    $html .= "<td>".$his['AWBNo']."</td>";
                }
                else{
                    if($his['Category1']=="Return Request" && $his['AreaPincode'] !=""){
                    
                    $html .= "<td> Create AWB</td>";
                 
                    }
                    else{
                        $html .= "<td></td>";
                    }
                    
                }

                $html .= "<td>".$his['TokenNumber']."</td>";
                    if($his['AWBNo'] !=""){
                        $html .= "<td>".$his['CallDate']."</td>";
                    }
                    else{
                        $html .= "<td></td>"; 
                    }
                $html .= "<td>".$his['Ret_AWBNo']."</td>";
                $html .= "<td>".$his['Ret_TokenNumber']."</td>";
                $html .= "<td>".$his['Ret_PikupDate']."</td>";

                $html .= "</tr>";
    
            }
           

          
                $html .= "</table>";
                  echo $html;
                  return $html; 
                
   // mail_send($html,'in_call_detail'.$reptype,$name,$emailId,$clientId,'In Call Detail '.$reptype);
}

function abandoned_data($condition3,$clientId,$campaignId,$name,$emailId,$db1,$reptype,$condition4)
{ 
    //$html = $campaignId;
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
    t2.term_reason term_reason 
    FROM asterisk.vicidial_closer_log t2 
    LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid 
    LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid 
    LEFT JOIN 
    (SELECT uniqueid,SUM(parked_sec) p FROM park_log WHERE STATUS='GRABBED' AND $condition4 GROUP BY uniqueid) t6 ON t2.uniqueid=t6.uniqueid left join vicidial_users vc on t2.user=vc.user 
    WHERE $condition3
    AND $campaignId 
    AND  t2.lead_id IS NOT NULL
                ";

        $rsc = mysqli_query($db1,$qry);
        
       

            $html = "<table cellspacing='0' border='1'>";
            $html .= "<thead>";
            $html .= "<tr>";
            $html .= "<td>CallDate</td>";
            $html .= "<td>Time</td>";
            $html .= "<td>Agent Id</td>";
            $html .= "<td>Agent Name</td>";
            $html .= "<td>Calltype</td>";
            $html .= "<td>Campaign Name</td>";
            $html .= "<td>Phone Number</td>";
            $html .= "<td>Disposition</td>";
                $html .= "<td>Disconn.By</td>";
            $html .= "<td>Call Duration Second</td>";
            $html .= "<td>Call Duration Minute</td>";
            $html .= "<td>Queue Duration</td>";
                $html .= "<td>Hold Time</td>";
                $html .= "<td>Talkduration</td>";
                $html .= "<td>Acwduration (Wrapup or Dispo time)</td>";
                $html .= "<td>Hours Slot</td>";
                $html .= "<td>Total Handled Time</td>";
                $html .= "<td>Call 20 Sec (SL)</td>";
                $html .= "<td>End Time</td>";
            
                $html .= "</tr>";

            $html .= "</thead>";
            $html .= "<tbody>";
        while($dt  = mysqli_fetch_assoc($rsc))
        {
            
                $html .= "<tr>";
                $html .= "<td>".$dt['CallDate']."</td>";
                $html .= "<td>".$dt['StartTime']."</td>";
                $html .= "<td>".$dt['Agent']."</td>";
                $html .= "<td>".$dt['full_name']."</td>";
                $html .= "<td>Inbound</td>";
                $html .= "<td>".$dt['campaign_id']."</td>";
                $html .= "<td>".$dt['PhoneNumber']."</td>";
                $html .= "<td>".$dt['status']."</td>";
                $html .= "<td>".$dt['term_reason']."</td>";
                if($dt['Agent']=='VDCL') { $html .= "<td>0:00:00</td>"; } else { $html .= "<td>".$dt['CallDuration1']."</td>"; }
                if($dt['Agent']=='VDCL') { $html .= "<td>0:00:00</td>"; } else { $html .= "<td>".$dt['CallDuration']."</td>"; }
                $html .= "<td>".$dt['Queuetime']."</td>";
                $html .= "<td>".$dt['ParkedTime']."</td>";
                $html .= "<td>".$dt['CallDuration']."</td>";

                $talk=explode(':',$dt['CallDuration']);
                //print_r($talk);
                $tadl=($talk[0]*60*60)+($talk[1]*60)+$talk[2];
                $TotalLogin1= $dt['WrapTime'];
		        $totalHandle=$TotalLogin1+$tadl;

                $seconds1 = $totalHandle % 60;
                $time1 = ($totalHandle - $seconds1) / 60;
                $minutes1 = $time1 % 60;
                $hours1 = ($time1 - $minutes1) / 60;

                $minutes1 = ($minutes1<10?"0".$minutes1:"".$minutes1);
                $seconds1 = ($seconds1<10?"0".$seconds1:"".$seconds1);
                $hours1 = ($hours1<10?"0".$hours1:"".$hours1);

                $Totalh = ($hours1>0?$hours1.":":"00:").$minutes1.":".$seconds1;
                $seconds = $TotalLogin1 % 60;
                $time = ($TotalLogin1 - $seconds) / 60;
                $minutes = $time % 60;
                $hours = ($time - $minutes) / 60;
                
                $minutes = ($minutes<10?"0".$minutes:"".$minutes);
                $seconds = ($seconds<10?"0".$seconds:"".$seconds);
                $hours = ($hours<10?"0".$hours:"".$hours);
                
                $TotalLogin = ($hours>0?$hours.":":"00:").$minutes.":".$seconds;

                $html .= "<td>".$TotalLogin."</td>";
                $html .= "<td>".date("H:00:00",strtotime($dt['StartTime']))."</td>";
                $html .= "<td>".$Totalh."</td>";
                $html .= "<td>".$dt['Call20']."</td>";
                $html .= "<td>".$dt['Endtime']."</td>";
                            
                $html .= "</tr>";
        }
            
            $html .= "</tbody>";
            $html .= "</table>";

       echo $html;
       return $html; 

                
     mail_send($html,'sla_report_'.$reptype,$name,$emailId,$clientId,'SLA Report '.$reptype);
             
   // export_excel($exportArr1,$exportArr2,'tagging_mis_'.$reptype,$name,$emailId,$clientId,'Tagging mis '.$reptype,$db1);   
        
}

function sla($condition,$clientId,$campaignId,$name,$emailId,$db1,$reptype)
{ 
    //$html = $campaignId;
    $FromDate = date('Y-m-01',strtotime('-1 Month'));
    $ToDate   = date('Y-m-d', strtotime('last day of last month'));
   
    if($FromDate!='') { $FromDate=$FromDate.' 00:00:00'; }
    if($ToDate!='') { $ToDate=$ToDate.' 23:59:59'; }
    $TotalCall = '';
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
        $rsc = mysqli_query($db1,$qry);
        $dt  = mysqli_fetch_assoc($rsc);
		
		
		$timeLabel=date("d-M-Y",strtotime($start_time_start));
		$dateLabel=date("F-Y",strtotime($start_time_start));
		$datetimeArray[$dateLabel][]=$timeLabel;
		
		//$data['Offered %'][$dateLabel][$timeLabel]='';
		$total=$dt['Answered']+$dt['Abandon'];
		$data['Total Calls Recieved'][$dateLabel][$timeLabel]=$total;
		$data['Total Calls Answered'][$dateLabel][$timeLabel]=$dt['Answered'];
		$data['Total Calls Abandoned'][$dateLabel][$timeLabel]=$dt['Abandon'];
		
		$data['AHT (In Sec)'][$dateLabel][$timeLabel]=round($dt['TotalAcht']/$dt['Answered']);
                
		$data['Calls Ans (within 20 Sec)'][$dateLabel][$timeLabel]=$dt['WIthinSLA'];
		$data['Abnd Within Threshold'][$dateLabel][$timeLabel]=$dt['AbndWithinThresold'];
		$data['Abnd After Threshold'][$dateLabel][$timeLabel]=$dt['AbndAfterThresold'];
		$data['Ababdoned (%)'][$dateLabel][$timeLabel]=round($dt['Abandon']*100/$total);
                
        $data['SL% (20 Sec)'][$dateLabel][$timeLabel]=round($dt['WIthinSLA']*100/$data['Total Calls Recieved'][$dateLabel][$timeLabel])."%";
        
        $data['AL%'][$dateLabel][$timeLabel]=round($dt['Answered']/$data['Total Calls Recieved'][$dateLabel][$timeLabel]*100)."%";
		
		$TotalCall+=$total;
	}
    //print_r($data);die;
            $html = "<table cellspacing='0' border='1'>";
            $html .= "<thead>";
            $html .= "<tr style='background-color:#317EAC; color:#FFFFFF;'>";
            $html .= "<th rowspan='2'></th>";
            foreach($datetimeArray as $dateLabel=>$timeArray) { $html .= "<th colspan=\"".count($timeArray)."\">$dateLabel</th>"; }
            $html .= "</tr>";

            $html .= "<tr style='background-color:#317EAC; color:#FFFFFF;'>";
            foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { $html .= "<th>$timeLabel</th>"; } }
            $html .= "</tr>";

            $html .= "</thead>";
            $html .= "<tbody>";
            foreach($data as $dataLabel=>$dataSub) { 
                $html .= "<tr>";
                $html .= "<td>".$dataLabel."</td>";
                foreach($datetimeArray as $dateLabel=>$timeArray) { foreach($timeArray as $timeLabel) { $html .= "<td>{$dataSub[$dateLabel][$timeLabel]}</td>"; } }
                $html .= "</tr>";
            }
            $html .= "</tbody>";
            $html .= "</table>";

       echo $html;
       return $html; 

                
      // mail_send($html,'sla_report_'.$reptype,$name,$emailId,$clientId,'SLA Report '.$reptype);
             
   // export_excel($exportArr1,$exportArr2,'tagging_mis_'.$reptype,$name,$emailId,$clientId,'Tagging mis '.$reptype,$db1);   
        
}

function Corrective_report($condition,$clientId,$name,$emailId,$db1,$reptype)
{ 
    $tArr   =   mysqli_query($db1,"SELECT * FROM call_master WHERE ClientId='$clientId' AND Category3 IN('Phase 1','Phase 2','Phase 3','Phase 4') AND $condition order by Category3 ASC ");
    //print_r($tArr)die;
    //echo "SELECT * FROM call_master WHERE ClientId='$clientId' AND $condition order by Category3 ASC";die;
    $dataArr = array();
    $category2 = array('General Maintenance'=>'72 Hrs','Horticulture'=>'48 Hrs','Electrical'=>'48 Hrs','Water'=>'24 Hrs','Security'=>'24 Hrs','Construction Related'=>'72 Hrs');
   

             $i = 0;
            while($calldata = mysqli_fetch_assoc($tArr))
            {
                
                if(empty($calldata['CloseLoopCate1']))
                {

                    $dataArr[$calldata['Category3']][$calldata['Category2']]['open'] +=1;
                }
                else
                {
                    $dataArr[$calldata['Category3']][$calldata['Category2']]['close'] +=1; 
                }

                $dataArr[$calldata['Category3']][$calldata['Category2']]['data'] = $calldata;

                //print_r($dataArr);die;
            }
        

            $html = "<table cellspacing='0' border='1'>";
            $html .= "<tr style='background-color:DarkGray;'>";
            $html .= "<th rowspan='2'>Site</th>";
            $html .= "<th rowspan='2'>Category</th>";
            $html .= "<th rowspan='2'>Total Corrections</th>";
            $html .= "<th colspan='2' style='text-align: center;'>Status</th>";
            $html .= "<th rowspan='2'>TAT</th>";
            $html .= "<th rowspan='2'>Remarks</th>";  
            $html .= "</tr>";

            $html .= "<tr style='background-color:DarkGray;'>";
            $html .= "<th>Open</th>";
            $html .= "<th>close</th>"; 
            $html .= "</tr>";

            $grand_total_corr = 0;
            $i=0;
            foreach($dataArr as $key=>$value)
            {
                $a=1;$total_corr=0;$total_open= 0;$total_close= 0; $col2_keys = array_keys($value); 
                foreach($category2 as $key2=>$val){  
                    $html .= "<tr>";
                    if($a==1) { 
                    
                    $html .= "<th rowspan='6'>".$key."</th>";
                     $a=0; } 
                    
                    $html .= "<th>".$key2."</th>";
                    
                    $html .= "<td>".$complaint = $value[$key2]['open']+$value[$key2]['close'];$complaint."</td>";
                    $html .= "<td>".$value[$key2]['open']."</td>";
                    $html .= "<td>".$value[$key2]['close']."</td>";
                    $html .= "<td>".$val."</td>";
                    $html .= "<td></td>";
             
 
                    $html .= "</tr>";
                     $total_open+=$value[$key2]['open'];
                          $total_close+=$value[$key2]['close'];
                          $total_corr += $complaint;
                }
                $html .= "<tr>";
                $html .= " <th colspan='2'>Total</th>";
                $html .= "<th>".$total_corr."</th>"; 
                $html .= "<th>".$total_open."</th>"; 
                $html .= "<th>".$total_close."</th>";
                $html .= "<th></th>";
               
                
                $tot_close_corr =  $total_close/$total_corr;
                $html .= "<th>".$tot_close_corr."</th>";  
                $html .= "</tr>";

                $grand_total_corr += $total_corr;
                $grand_total_open += $total_open;
                $grand_total_close += $total_close;
            }

            $html .= "<tr>";
            $html .= "<th style='background-color:yellow;' colspan='2'>Grand Total</th>";
            $html .= "<th style='background-color:yellow;'>".$grand_total_corr."</th>"; 
            $html .= "<th style='background-color:yellow;'>".$grand_total_open."</th>";
            $html .= "<th style='background-color:yellow;'>".$grand_total_close."</th>";
            $html .= "<th style='background-color:yellow;'></th>";
            $html .= "<th style='background-color:yellow;'>".$totalarr = $grand_total_close/$grand_total_corr;round($totalarr)."</th>";
            $html .= "</tr>";
            $html .= "</table>";
                echo $html;
                return $html; 
                
    //mail_send($html,'corrective_report_'.$reptype,$name,$emailId,$clientId,'Corrective Report '.$reptype);
}
        

function category_mis($condition,$clientId,$campaignId,$condition2,$name,$emailId,$db1,$reptype){ 
    $qry1 = "SELECT ecrName FROM ecr_master WHERE CLIENT='$clientId' AND Label='1'";
    $getEcr = mysql_query($qry1,$db1);
    while($ecrRow = mysql_fetch_assoc($getEcr)){
        $categoryName =$ecrRow['ecrName'];  
        $html= getCategory($clientId,$categoryName,$condition2,$db1);
        mail_send($html,$categoryName.'_'.$reptype,$name,$emailId,$clientId,$categoryName.' wise mis '.$reptype); 
    }   
}

function getCategory($clientId,$categoryName,$condition2,$db1){
    $qry="SELECT DATE_FORMAT(CallDate,'%d-%b-%Y') `date`,Category1,Category2,COUNT(1)`count` FROM call_master cm WHERE ClientId=$clientId and Category1='$categoryName' and $condition2 GROUP BY DATE(CallDate),Category2";     
    $execute = mysql_query($qry,$db1);      
    while($row = mysql_fetch_assoc($execute)){
        $date[$row['date']] += $row['count'];
        $type[] = $row['Category2'];
        $total +=$row['count']; 
        $Data[$row['Category2']][$row['date']]=$row;
        $category = $row['Category1'];
        $category2[$row['Category2']] += $row['count']; 
    }

    $html .= "<table border='2'>";
    $html .= "<tr style='background-color:#F90417; color:#FFFFFF;'><th>Summary</th><th>MTD</th><th>%</th>";

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
                    $html .= $Data[$k][$k1]['count'];
                $html .= "</td>";
            }

        $html .= "</tr>";
    }
    $html .= "</table>";
    return $html;  
}

function sla_report_mis($condition,$clientId,$campaignId,$condition3,$name,$emailId,$db1,$reptype)
{
    //echo $db1; 

    //$Campagn  = $campaignId;
    //$campaignId ="t2.campaign_id in(". $campaignId.")";
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
               // print_r($dt);
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
        
        $htmlsla = '<table  border="1" >
                    <thead>
    <tr>
        <th>Date</th>';          
                //print_r($datetimeArray); exit;
                foreach($datetimeArray as $dateLabel=>$timeArray) { $htmlsla .="<th colspan=\"".count($timeArray)."\">$dateLabel</th>"; } 
    $htmlsla .= '</tr>';
    $htmlsla .= '<tr><th>Time</th>';
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
//echo $htmlsla ."<br><br><br><br><br>"; 
echo mail_send($htmlsla,'sla_mis_'.$reptype,$name,$emailId,$clientId,'SLA MIS '.$reptype);
        
}
   
    /*
    function export_excel($SumeryDetails,$exportArray2,$filename){
        
        
        $filename = "/var/www/html/dialdesk/app/webroot/crone/csv_data/dialdesk_Mas Callnet_".$filename.date('d_m_Y_H_i_s')."_Export.xls";
        
        $_SESSION['SumeryDetails'] = $SumeryDetails;
        $_SESSION['exportArray2'] = $exportArray2;
        $_SESSION['filename'] = $filename;
        ?>
        
        <script>window.open("graph.php", "_blank");</script>
        <?php
        return $filename;
        
    }*/
        
       


function export_excel($SumeryDetails,$exportArray2,$filename,$name,$emailId,$clientId,$sub,$db1){
        $filename = "/var/www/html/dialdesk/app/webroot/crone/csv_data/dialdesk_Mas Callnet_".$filename.date('d_m_Y_H_i_s')."_Export.xls";
        
        $sql = "INSERT INTO reportmail_master(name,email,clientid,subject,filename) VALUES ('$name','$emailId','$clientId','$sub','$filename')";
        mysql_query($sql,$db1);
        
        
        include('Classes/PHPExcel.php');
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

        ob_end_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->setIncludeCharts(TRUE);
        $objWriter->save(str_replace(__FILE__,$filename,__FILE__));

        //return $filename;
        
    }

    
    
    
    
    
    
        
          
function mail_send($text,$filename,$name,$emailId,$clientId,$sub){
            $EmailText ='';
//            echo $text; 
    $filename="/var/www/html/dialdesk/app/webroot/crone/csv_data/dialdesk_Mas_Callnet_".$filename.date('d_m_Y_H_i_s')."_Export.xls";
    file_put_contents($filename, $text); 

    $email = $emailId;
    $name = $name;
    
    $ReceiverEmail=array('Email'=>$email,'Name'=>$name); 
    $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Attachment=array( $filename); 
     $Subject="$sub report export"; 
     $dated = date('d-M',strtotime('-1 days'));
    $EmailText .="<table><tr><td style=\"padding-left:12px;\">Dear $name</td></tr>"; 
    $EmailText .="<tr><td style=\"padding-left:12px;\">Please find the enclosed $sub  updated till $dated.</td></tr>";
    $EmailText .="</table>"; 
    $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'Attachment'=>$Attachment);
    
    try
    {
        $done = send_email( $emaildata);
    }
    catch (Exception $e)
    {
        $error = $e.printStackTrace();
        $updQry = "insert into db_dialdesk.error_master(data_id,process,error_msg,createdate) values('{$row['data_id']}','{$row['bpo']}','{$error}',now())";
        mysql_query($updQry);
    }
    if($done=='1')
    {
        $msg =  "Mail Sent Successfully !";        
        
        $updQry2 = "insert into db_dialdesk.mail_log_report(clientId,mail_status,mail_date) "
                . "values('$clientId','$done',now()) ";
        mysql_query($updQry2);
    } 
        }
        
function mail_send2($filename,$name,$emailId,$clientId,$sub){
            $EmailText ='';
    
    $email = $emailId;
    $name = $name;
    
    $ReceiverEmail=array('Email'=>$email,'Name'=>$name); 
    $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'DialDesk'); 
    $Attachment=array( $filename); 
    $Subject="$sub Report Export"; 
    $EmailText .="<table><tr><td style=\"padding-left:12px;\">Hello $name</td></tr>"; 
    $EmailText .="<tr><td style=\"padding-left:12px;\">$smsText</td></tr>";
    $EmailText .="</table>"; 
    $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'Subject'=> $Subject,'EmailText'=> $EmailText,'Attachment'=>$Attachment);
    
    try
    {
        //$done = send_email( $emaildata);
    }
    catch (Exception $e)
    {
        $error = $e.printStackTrace();
        $updQry = "insert into db_dialdesk.error_master(data_id,process,error_msg,createdate) values('{$row['data_id']}','{$row['bpo']}','{$error}',now())";
        mysql_query($updQry);
    }
    if($done=='1')
    {
        $msg =  "Mail Sent Successfully !";        
        
        $updQry2 = "insert into db_dialdesk.mail_log_report(clientId,mail_status,mail_date) "
                . "values('$clientId','$done',now()) ";
        mysql_query($updQry2);
    } 
        }        
        
?>