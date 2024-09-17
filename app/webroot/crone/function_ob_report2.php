<?php 
//$db2 = mysql_connect('192.168.10.5','root','vicidialnow',TRUE);
session_start();

function category_wise($clientId,$name,$emailId,$db1,$reptype)
{
    //$tArr   =   mysqli_query($db1,"SELECT * FROM call_master WHERE ClientId='$clientId' AND $condition order by Category3 ASC");
    $startdate   = date('Y-m-d', strtotime('-2 day'));
    $enddate = date('Y-m-d', strtotime('-1 day'));

    $tArr   =   mysql_query("SELECT * FROM call_master_out WHERE ClientId='$clientId' AND date(CallDate) >= '$startdate' and date(CallDate) <='$enddate'",$db1);
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

      
        while($calldata = mysql_fetch_assoc($tArr))
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

            $html = "<table cellpadding='0' cellspacing='0' border='1' class='table table-striped table-bordered'>";
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
              
                
   mail_send($html,'category_wise_'.$reptype,$name,$emailId,$clientId,'Category Wise '.$reptype);
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

        $rsc = mysql_query($qry,$db1);
        
       

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
        while($dt  = mysql_fetch_assoc($rsc))
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

                
     mail_send($html,'abandoned_data_'.$reptype,$name,$emailId,$clientId,'Abandoned Report '.$reptype);
             
   // export_excel($exportArr1,$exportArr2,'tagging_mis_'.$reptype,$name,$emailId,$clientId,'Tagging mis '.$reptype,$db1);   
        
}
function tollfree($condition,$clientId,$name,$emailId,$db1,$reptype)
{

    $tArr   =   mysql_query("SELECT * FROM call_master_out WHERE ClientId='$clientId' AND $condition and MSISDN IN('9680193319','7884214478','8369692722','6239179641')",$db1);

    //$tArr   =   mysqli_query($db1,"SELECT * FROM call_master WHERE ClientId='$clientId' AND $condition order by Category3 ASC");

    $ecr = mysql_query("select label,ecrName from ecr_master where Client='$clientId' GROUP BY Label ORDER BY Label ASC",$db1);
    $fieldName = mysql_query("select * from field_master where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC",$db1);
    $fieldName1 = mysql_query("select * from close_field where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC",$db1);
	
    $fieldSearch = mysql_query("select fieldNumber from field_master where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC",$db1);
    $headervalue = array();
    while($f = mysql_fetch_assoc($fieldSearch))
    {
        $headervalue[] = 'Field'.$f['fieldNumber']; 
    }

    $fieldSearch1 =  mysql_query("select fieldNumber from close_field where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC",$db1);
    $headervalue1=array();
    while($f1 = mysql_fetch_assoc($fieldSearch1)){
        $headervalue1[] = 'CField'.$f1['fieldNumber']; 
    }
    //print_r($headervalue1);


            $html = "<table cellspacing='0' border='1'>";
            $html .= "<tr>";
            $html .= "<th>IN CALL ID</th>";
            $html .= "<th>CALL FROM</th>";
            $keyss = array();
            while($k = mysql_fetch_assoc($ecr))
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
            while($post = mysql_fetch_assoc($fieldName)){
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
            while($post = mysql_fetch_assoc($fieldName1)){
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
            while($his = mysql_fetch_assoc($tArr)){
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
                
    mail_send($html,'toll_free'.$reptype,$name,$emailId,$clientId,'Toll Free '.$reptype);
}
function indiamart($condition,$clientId,$name,$emailId,$db1,$reptype)
{

    $tArr   =   mysql_query("SELECT * FROM call_master_out WHERE ClientId='$clientId' AND $condition and MSISDN IN('8045994699','8042994299')",$db1);

    //$tArr   =   mysqli_query($db1,"SELECT * FROM call_master WHERE ClientId='$clientId' AND $condition order by Category3 ASC");

    $ecr = mysql_query("select label,ecrName from ecr_master where Client='$clientId' GROUP BY Label ORDER BY Label ASC",$db1);
    $fieldName = mysql_query("select * from field_master where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC",$db1);
    $fieldName1 = mysql_query("select * from close_field where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC",$db1);
	
    $fieldSearch = mysql_query("select fieldNumber from field_master where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC",$db1);
    $headervalue = array();
    while($f = mysql_fetch_assoc($fieldSearch))
    {
        $headervalue[] = 'Field'.$f['fieldNumber']; 
    }

    $fieldSearch1 =  mysql_query("select fieldNumber from close_field where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC",$db1);
    $headervalue1=array();
    while($f1 = mysql_fetch_assoc($fieldSearch1)){
        $headervalue1[] = 'CField'.$f1['fieldNumber']; 
    }
    //print_r($headervalue1);


            $html = "<table cellspacing='0' border='1'>";
            $html .= "<tr>";
            $html .= "<th>IN CALL ID</th>";
            $html .= "<th>CALL FROM</th>";
            $keyss = array();
            while($k = mysql_fetch_assoc($ecr))
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
            while($post = mysql_fetch_assoc($fieldName)){
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
            while($post = mysql_fetch_assoc($fieldName1)){
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
            while($his = mysql_fetch_assoc($tArr)){
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
                
                
    mail_send($html,'indiamart'.$reptype,$name,$emailId,$clientId,'Indiamart '.$reptype);
}
function process_wise($dayName,$clientId,$campaignId,$name,$emailId,$db1,$reptype,$db2)
{ 
    //$html = $campaignId;
    // $FromDate = date('Y-m-01',strtotime('-1 Month'));
    // $ToDate   = date('Y-m-d', strtotime('last day of last month'));

    //$NextDate=date("Y-m-d H:i:s",strtotime("$FromDate -1 Month"));die;
    $FromDate=date("Y-m-01",strtotime("$dayName -1 Month"));
    $ToDate   = date('Y-m-d', strtotime("$dayName last day of last month"));
   
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
            //$tArr   =   mysql_query("SELECT * FROM call_master WHERE ClientId='$clientId' AND $condition4 order by Category3 ASC",$db1);
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

            $data_q   =   mysql_query($qry,$db2); 
            $dt = mysql_fetch_assoc($data_q);
            $data_q1   =   mysql_query($qry1,$db2);  
            $dt1 = mysql_fetch_assoc($data_q1);
            
          $qry2 = "SELECT SUM(IF(TagStatus IS NULL,0,1)) Callback FROM aband_call_master 
            WHERE DATE(Calldate) BETWEEN '$start_time_start' AND '$start_time_end' and ClientId ='$clientId' GROUP BY DATE(Calldate),ClientId,CallType";

            //$Abanddata=$this->AbandCallMaster->query($qry2);
            $data_q2   =   mysql_query($qry2,$db1);
            $Abanddata = mysql_fetch_assoc($data_q2);
            
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
    //   echo $html;
    //    return $html; 

                
      mail_send($html,'process_report_'.$reptype,$name,$emailId,$clientId,'Process Report '.$reptype);
             
   // export_excel($exportArr1,$exportArr2,'tagging_mis_'.$reptype,$name,$emailId,$clientId,'Tagging mis '.$reptype,$db1);   
        
}
function sla($dayName,$clientId,$campaignId,$name,$emailId,$db1,$reptype)
{ 
    //$html = $campaignId;
    // $FromDate = date('Y-m-01',strtotime('-1 Month'));
    // $ToDate   = date('Y-m-d', strtotime('last day of last month'));

    $FromDate=date("Y-m-01",strtotime("$dayName -1 Month"));
    $ToDate   = date('Y-m-d', strtotime("$dayName last day of last month"));
   
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
        $rsc = mysql_query($qry,$db1);
        $dt  = mysql_fetch_assoc($rsc);
		
		
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

    //    echo $html;
    //    return $html; 

                
       mail_send($html,'sla_report_'.$reptype,$name,$emailId,$clientId,'SLA Report '.$reptype);
             
   // export_excel($exportArr1,$exportArr2,'tagging_mis_'.$reptype,$name,$emailId,$clientId,'Tagging mis '.$reptype,$db1);   
        
}
function Corrective_report($condition4,$clientId,$name,$emailId,$db1,$reptype)
{ 
    
    $tArr   =   mysql_query("SELECT * FROM call_master WHERE ClientId='$clientId' AND Category3 IN('Phase 1','Phase 2','Phase 3','Phase 4') AND $condition4 order by Category3 ASC",$db1);
    // $TACN=mysql_fetch_assoc(mysql_query("SELECT COUNT(Id) AS Abandon FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate)='$stdt1' AND TagStatus IS NULL",$db1));
    //print_r($tArr)die;
    $category2 = array('General Maintenance'=>'72 Hrs','Horticulture'=>'48 Hrs','Electrical'=>'48 Hrs','Water'=>'24 Hrs','Security'=>'24 Hrs','Construction Related'=>'72 Hrs');
    $dataArr = array();
    while($calldata = mysql_fetch_assoc($tArr))
    {
        //print_r($tArr);
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
                    $html .= "<th>".number_format($tot_close_corr,2)."</th>";  
                    $html .= "</tr>";

                    $grand_total_corr += $total_corr;
                    $grand_total_open += $total_open;
                    $grand_total_close += $total_close;
            }
                $totalarr = $grand_total_close/$grand_total_corr;
                $html .= "<tr>";
                $html .= "<th style='background-color:yellow;' colspan='2'>Grand Total</th>";
                $html .= "<th style='background-color:yellow;'>".$grand_total_corr."</th>"; 
                $html .= "<th style='background-color:yellow;'>".$grand_total_open."</th>";
                $html .= "<th style='background-color:yellow;'>".$grand_total_close."</th>";
                $html .= "<th style='background-color:yellow;'></th>";
                $html .= "<th style='background-color:yellow;'>".number_format($totalarr,2)."</th>";
                $html .= "</tr>";
                $html .= "</table>";
                // echo $html;
                // return $html; 
                
    mail_send($html,'corrective_report_'.$reptype,$name,$emailId,$clientId,'Corrective Report '.$reptype);
}
function Incall_details($condition,$clientId,$name,$emailId,$db1,$reptype)
{
 
    $tArr   =   mysql_query("SELECT * FROM call_master WHERE ClientId='$clientId' AND $condition",$db1);

    $ecr = mysql_query("select label,ecrName from ecr_master where Client='$clientId' GROUP BY Label ORDER BY Label ASC",$db1);
    $fieldName = mysql_query("select * from field_master where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC",$db1);
    $fieldName1 = mysql_query("select * from close_field where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC",$db1);
	
    $fieldSearch = mysql_query("select fieldNumber from field_master where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC",$db1);
    $headervalue = array();
    while($f = mysql_fetch_assoc($fieldSearch))
    {
        $headervalue[] = 'Field'.$f['fieldNumber']; 
    }

    $fieldSearch1 =  mysql_query("select fieldNumber from close_field where ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority ASC",$db1);
    $headervalue1=array();
    while($f1 = mysql_fetch_assoc($fieldSearch1)){
        $headervalue1[] = 'CField'.$f1['fieldNumber']; 
    }
    //print_r($headervalue1);


            $html = "<table cellspacing='0' border='1'>";
            $html .= "<tr>";
            $html .= "<th>IN CALL ID</th>";
            $html .= "<th>CALL FROM</th>";
            $keyss = array();
            while($k = mysql_fetch_assoc($ecr))
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
            while($post = mysql_fetch_assoc($fieldName)){
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
            while($post = mysql_fetch_assoc($fieldName1)){
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
            while($his = mysql_fetch_assoc($tArr)){
                $html .= "<tr>";
                $html .= "<td>".$his['SrNo']."</td>";
                $html .= "<td>".$his['MSISDN']."</td>";
                foreach($keyss as $ke)
                {
                    $html .= "<td>".$his["Category".$ke['Label']]."</td>";
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
                //  echo $html;
                //  return $html; 
                
    mail_send($html,'in_call_detail'.$reptype,$name,$emailId,$clientId,'In Call Detail '.$reptype);
}
function call_mis($condition,$clientId,$campaignId,$condition2,$name,$emailId,$db1,$reptype)
        {
    
    $qry =  "SELECT COUNT(*) `Total`,
    SUM(If(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',1,0)) `Answered`,
    SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`,
    SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',t1.length_in_sec,0)) `TotalAcht`,
    SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
    SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
    SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
    SUM(IF(((t2.status IS NULL OR t2.status='DROP')
    AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
    SUM(IF(((t2.status IS NULL OR t2.status='DROP')
    AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
    FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
    WHERE $condition and $campaignId and t2.term_reason!='AFTERHOURS' limit 1";

    $call_excute = mysql_query($qry);
    $tot1 = mysql_fetch_assoc($call_excute);
    
    $TAC=mysql_fetch_assoc(mysql_query("SELECT COUNT(Id) AS Abandon FROM `aband_call_master` WHERE ClientId='$clientId' AND $condition2 AND TagStatus IS NULL",$db1));
    
    $answer =$tot1['Answered'];
    $aband = $TAC['Abandon'];
    $acht = $tot1['TotalAcht'];
    $total = $answer + $aband;

    $select = "select Category1,count(Category1)`count` from call_master where clientId='$clientId' AND $condition2 group by Category1";
    $categoryExecute = mysql_query($select,$db1);


    $category = array(); $tot2 = 0;
    while($row = mysql_fetch_assoc($categoryExecute)){
        $category[] = array($row['Category1'],$row['count'],'0%');
        $tot2 += $row['count'];
    }
    
    $qry1 = "SELECT date_format(t2.call_date,'%d-%b-%Y') `date`,COUNT(*) `Total`,
    SUM(If(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',1,0)) `Answered`,
    SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`,
    SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',t1.length_in_sec,0)) `TotalAcht`,
    SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
    SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
    SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
    SUM(IF(((t2.status IS NULL OR t2.status='DROP')
    AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
    SUM(IF(((t2.status IS NULL OR t2.status='DROP')
    AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
    FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
    WHERE $condition and $campaignId and t2.term_reason!='AFTERHOURS' group by date(t2.call_date)";

    $call_details_detail_excute = mysql_query($qry1);

    while($row=mysql_fetch_assoc($call_details_detail_excute)){
        $stdt=strtotime($row['date']);
        $stdt1   = date("Y-m-d",$stdt);
        $TACN=mysql_fetch_assoc(mysql_query("SELECT COUNT(Id) AS Abandon FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate)='$stdt1' AND TagStatus IS NULL",$db1));
        
        $DateArr[] = $row['date'];
        $Arr['Receive'] = $row['Answered']+$TACN['Abandon'];
        $Arr['Answer']  = $row['Answered'];
        $Arr['Aband']   = $TACN['Abandon'];
        $Arr['Aht']     = $row['TotalAcht']/$row['Answered'];
        $TotalArr[$row['date']] = $Arr;
        $totalcount +=$Arr['Receive']; 

        $Arr2['Receive'] += $row['Answered']+$TACN['Abandon'];
        $Arr2['Answer']  += $row['Answered'];
        $Arr2['Aband']   += $TACN['Abandon'];
        $Arr2['Aht']     += $row['TotalAcht']/$row['Answered'];
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

    $select = "SELECT DATE_FORMAT(CallDate,'%d-%b-%Y') `date`,Category1,COUNT(1) `count` FROM call_master
               where clientId='$clientId' AND $condition2 GROUP BY Category1,DATE(CallDate)";

    $call_detail_excute = mysql_query($select,$db1);

    $total = 0;
    while($row=mysql_fetch_assoc($call_detail_excute)){
        $TotalArr2[$row['date']][$row['Category1']]['count'] = $row['count'];
        $Category[$row['Category1']] += $row['count'];
        $dateArr[$row['date']] +=  $row['count'];
        $total += $row['count'];   
    }

    $j++; 

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
    
    export_excel($value1,$value,'call_mis_'.$reptype,$name,$emailId,$clientId,'Call mis '.$reptype,$db1);
}
        
function tat_mis($condition,$clientId,$campaignId,$condition2,$name,$emailId,$db1,$reptype){ 
    
    $qry="SELECT cm.Category1, IF(DATE(cm.CloseLoopingDate)>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NOT NULL,1,
    IF((HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)) `outtat`,
    IF(DATE(cm.CloseLoopingDate)=DATE(cm.CallDate) AND (HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))<=tt.time_Hours 
    AND cm.CloseLoopingDate IS NOT NULL,1,0) `intat`, IF(CURDATE()>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NULL,
    1, IF((HOUR(NOW())-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NULL,1,0)) `openouttat`, 
    IF(CURDATE()=DATE(cm.CallDate) AND (HOUR(NOW())-HOUR(cm.CallDate))<=tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)
    `openintat`, DATE_FORMAT(cm.CloseLoopingDate,'%d-%b-%Y')`CallDate`,DATE_FORMAT(cm.CallDate,'%d-%b-%Y')`CloseLoopDate`,
    tt.time_hours FROM call_master cm INNER JOIN tbl_time tt ON cm.ClientId = tt.clientId 
    AND CONCAT(IFNULL(cm.Category1,''),IFNULL(cm.Category2,''),IFNULL(cm.Category3,''),IFNULL(cm.Category4,''),IFNULL(cm.Category5,'')) = 
    CONCAT(IFNULL(tt.Category1,''),IFNULL(tt.Category2,''),IFNULL(tt.Category3,''),IFNULL(tt.Category4,''),IFNULL(tt.Category5,''))
    WHERE cm.ClientId='$clientId' AND $condition2";
                  
               
    $execute = mysql_query($qry,$db1);

    while($row = mysql_fetch_assoc($execute)){
        
        $key = $row['Category1'];
        if(key_exists($key, $category)){
            $category[$key]['MTD'] +=1;
            $category[$key][$row['CloseLoopDate']]['MTD'] +=1;
            $category[$key]['intat'] +=$row['intat'];
            $category[$key]['outtat'] +=$row['outtat'];
            $category[$key]['openintat'] +=$row['openintat'];
            $category[$key]['openouttat'] +=$row['openouttat'];
            $category[$key][$row['CloseLoopDate']]['intat'] +=$row['intat'];
            $category[$key][$row['CloseLoopDate']]['outtat'] +=$row['outtat'];
            $category[$key][$row['CloseLoopDate']]['openintat'] +=$row['openintat'];
            $category[$key][$row['CloseLoopDate']]['openouttat'] +=$row['openouttat'];

        }
        else{
            
            $category[$key]['MTD'] =1;
            $category[$key][$row['CloseLoopDate']]['MTD'] =1;
            $category[$key]['intat'] =$row['intat'];
            $category[$key]['outtat'] =$row['outtat'];
            $category[$key]['openintat'] =$row['openintat'];
            $category[$key]['openouttat'] =$row['openouttat'];
            $category[$key][$row['CloseLoopDate']]['intat'] =$row['intat'];
            $category[$key][$row['CloseLoopDate']]['outtat'] =$row['outtat'];
            $category[$key][$row['CloseLoopDate']]['openintat'] =$row['openintat'];
            $category[$key][$row['CloseLoopDate']]['openouttat'] =$row['openouttat'];
        }

        $total +=1;
        $DataArr[] = $row['CloseLoopDate'];
    }


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

    export_excel($exportArray1,$value,'tat_mis_'.$reptype,$name,$emailId,$clientId,'Tat mis '.$reptype,$db1);  
  
}
        
function tagging_mis($condition,$clientId,$campaignId,$condition2,$name,$emailId,$db1,$reptype){ 
    $qry = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,COUNT(*) `Total`,
    SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',1,0)) `Answered`,
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
    WHERE $condition AND $campaignId AND t2.term_reason!='AFTERHOURS' GROUP BY DATE(t2.call_date)";

    $execute=  mysql_query($qry);
    while($row = mysql_fetch_assoc($execute)){
        $anstotal +=$row['Answered'];
        $DataArr[] = $row['date'];
        $DateArr3[$row['date']] =  $row['Answered'];
    }   
               
    $qry1="SELECT DATE_FORMAT(cm.CallDate,'%d-%b-%Y') `date`,Category1,Category2,COUNT(Category1)`count`
           FROM db_dialdesk.call_master cm 
           WHERE cm.ClientId = '$clientId' AND $condition2 GROUP BY cm.Category1,cm.Category2,date(cm.CallDate)";

    $executeCat=  mysql_query($qry1,$db1);
                   
    while($row = mysql_fetch_assoc($executeCat)){
        $key = $row['Category1'];
        if(key_exists($key, $category))
        {
            $category[$key]['Answer'] +=$row['count'];
            $category[$key]['count'] +=$row['count'];
            $category[$key][$row['date']]['count'] +=$row['count'];
            $category[$key]['Category2'][$row['Category2']]['count'] +=$row['count'];
            $category[$key]['Category2'][$row['Category2']][$row['date']] +=$row['count'];
        }
        else
        {
            $category[$key]['Answer'] =$row['count'];
            $category[$key]['count'] =$row['count'];
            $category[$key][$row['date']]['count'] =$row['count'];
            $category[$key]['Category2'][$row['Category2']]['count'] +=$row['count'];
            $category[$key]['Category2'][$row['Category2']][$row['date']] =$row['count'];
        }


        $tagtotal +=$row['count'];
    }
                 
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
                
     
             
    export_excel($exportArr1,$exportArr2,'tagging_mis_'.$reptype,$name,$emailId,$clientId,'Tagging mis '.$reptype,$db1);   
        
}
        
function time_mis($condition,$clientId,$campaignId,$condition2,$name,$emailId,$db1,$reptype){ 
    $qry = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,HOUR(t2.call_date)`hour`,MINUTE(t2.call_date)`minute`,COUNT(1) `Total`,
    SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',1,0)) `Answered`,
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
    WHERE $condition AND $campaignId AND t2.term_reason!='AFTERHOURS' GROUP BY DATE(t2.call_date)";

    $execute = mysql_query($qry); 
    while($row = mysql_fetch_assoc($execute)){
        
        $stdt=strtotime($row['date']);
        $stdt1   = date("Y-m-d",$stdt);
        $TACN=mysql_fetch_assoc(mysql_query("SELECT COUNT(Id) AS Abandon FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate)='$stdt1' AND TagStatus IS NULL",$db1));
        
        
        
        if($row['minute']>30){$key = 0;}
        else{$key = 1;}

       if(key_exists($row['date'], $dateArr))
        {
           if(key_exists($row['hour'], $dateArr[$row['date']]))
           {   
                if(key_exists($key,$dateArr[$row['date']][$row['hour']]))
                {
                    $dateArr[$row['date']][$row['hour']][$key]['Answered'] +=$row['Answered'];
                        $dateArr[$row['date']][$row['hour']]['Abandon'] +=$TACN['Abandon'];
                        $dateArr[$row['date']][$row['hour']]['Total'] +=$row['Answered'] + $TACN['Abandon'];
                }

                else 
                    {
                        $dateArr[$row['date']][$row['hour']][$key]['Answered']=$row['Answered'];
                        $dateArr[$row['date']][$row['hour']][$key]['Abandon']=$TACN['Abandon'];
                        $dateArr[$row['date']][$row['hour']][$key]['Total']=$row['Answered'] + $TACN['Abandon'];
                    }
           }
           else
           {
                $dateArr[$row['date']][$row['hour']][$key]['Answered']=$row['Answered'];
                $dateArr[$row['date']][$row['hour']][$key]['Abandon']=$TACN['Abandon'];
                $dateArr[$row['date']][$row['hour']][$key]['Total']=$row['Answered'] + $TACN['Abandon'];

           }

        }
        else
        {
            $dateArr[$row['date']][$row['hour']][$key]['Answered']=$row['Answered'];
            $dateArr[$row['date']][$row['hour']][$key]['Abandon']=$TACN['Abandon'];
            $dateArr[$row['date']][$row['hour']][$key]['Total']=$row['Answered'] + $TACN['Abandon'];
            $Hour []= $row['hour'];
        }
        $Date[] = $row['date'];
    }
                
                
                
                
                
                 
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
                mail_send($html,'time_mis_'.$reptype,$name,$emailId,$clientId,'Time wise mis '.$reptype);
                
                
               
                /* 
                echo $html;
                $fileName = "time_wise_mis".date('d_m_y_h_i_s');
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=".$fileName.".xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                */ 
                
}

        
function agent_mis($condition,$clientId,$campaignId,$condition2,$name,$emailId,$db1,$reptype){ 
    $qry1 = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,t2.user `username`,
    SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',1,0)) `Answered`,
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
    WHERE $condition AND $campaignId AND t2.term_reason!='AFTERHOURS' GROUP BY DATE(t2.call_date)";
            
    $execute = mysql_query($qry1); 
    while($row = mysql_fetch_assoc($execute)){

        $Date[] = $row['date'];
        $Agent[] = $row['username'];
        $Data[$row['date']][$row['username']] = $row;
    }

    $Date = array_unique($Date);
         $Agent = array_unique($Agent);
         $html .= "<table border='1'><tr style='background-color:#F90417; color:#FFFFFF;' ><th>Date</th>";
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
             $html .= "<tr><th>$v</th>";

            foreach($Date as $d)
            {
                
                $stdt=strtotime($d);
                $stdt1   = date("Y-m-d",$stdt);
                $TACN=mysql_fetch_assoc(mysql_query("SELECT COUNT(Id) AS Abandon FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate)='$stdt1' AND TagStatus IS NULL",$db1));
                
                $TABANDCOUNT1="";
                if($Data[$d][$v]['Answered'] !=""){
                    $TABANDCOUNT1=$TACN['Abandon'];
                }
                
                $html .= "<th>".($Data[$d][$v]['Answered']+$TABANDCOUNT1)."</th>"; 
                $html .= "<th>".$Data[$d][$v]['Answered']."</th>"; 
                $html .= "<th>".$TABANDCOUNT1."</th>"; 
            }
            $html .= "</tr>";
         }
         $html .= "</table>";
         
        mail_send($html,'agent_mis_'.$reptype,$name,$emailId,$clientId,'Agent wise mis '.$reptype);
         
        /*
        $fileName = "agent_mis_report".date('Y_m_d_h_i_s');
        header("Content-Type: application/vnd.ms-excel; name='excel'");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$fileName.".xls");
        header("Pragma: no-cache");
        header("Expires: 0"); 
        exit;
        */
}
        
        
  
function aband_mis($condition,$clientId,$campaignId,$condition2,$name,$emailId,$db1,$reptype){ 
    $qry = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,t2.length_in_sec,t2.lead_id
    FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
    WHERE $condition AND $campaignId AND t2.term_reason!='AFTERHOURS' and IF(t2.status IS NULL OR t2.status='DROP',true,false) group by t2.phone_number ";
  
    $execute = mysql_query($qry);
    while($row = mysql_fetch_assoc($execute)){
            
            $stdt=strtotime($row['date']);
            $stdt1   = date("Y-m-d",$stdt);
            $TACN=mysql_fetch_assoc(mysql_query("SELECT LeadId FROM `aband_call_master` WHERE ClientId='$clientId' AND DATE(CallDate)='$stdt1' AND LeadId='{$row['lead_id']}' AND TagStatus IS NULL",$db1));
            if(!empty($TACN)){

             $sec = round($row['length_in_sec']/5,0);
             $Date[] = $row['date'];

             if(key_exists("".$sec, $secArr))
             {$secArr[$sec] += 1;}
             else{$secArr[$sec] = 1;}

             if(key_exists($row['date'], $Data))
             {
                 if(key_exists($sec, $Data[$row['date']]))
                 {
                     $Data[$row['date']][$sec] += $row['length_in_sec'];

                 }
                 else
                 {
                     $Data[$row['date']][$sec] = $row['length_in_sec'];
                 }

             }
             else
             {
                 $Data[$row['date']][$sec] = $row['length_in_sec'];
             }
             $total += 1;
             
            }
    }

        //print_r($Data); exit;
         $Date = array_unique($Date);
         $html .= "<table border=\"1\"><tr><th>SUMMARY</th><th>MTD</th><th>%</th>";
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
        $html .= "</table>";
        
        mail_send($html,'aband_mis_'.$reptype,$name,$emailId,$clientId,'Abend call mis '.$reptype);
        
        /*
        $fileName = "abend_call".date('d_m_y_h_i_s');
        header("Content-Type: application/vnd.ms-excel; name='excel'");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$fileName.".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        exit;
        */
}

        
        
function answer_mis($condition,$clientId,$campaignId,$condition2,$name,$emailId,$db1,$reptype){ 
    
    
    $qry1 = "SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,t2.length_in_sec, IF(t2.status IS NULL OR t2.status='DROP',1,0) `Abandon`
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE $condition AND $campaignId AND t2.term_reason!='AFTERHOURS' and IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='A' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',true,false)";
  
            $execute = mysql_query($qry1);
                  
                 while($row = mysql_fetch_assoc($execute)){
            
                     
                     $sec = round($row['length_in_sec']/5,0);
                     $Date[] = $row['date'];
                     
                     if(key_exists("".$sec, $secArr))
                     {$secArr[$sec] += 1;}
                     else{$secArr[$sec] = 1;}
                     
                     if(key_exists($row['date'], $Data))
                     {
                         if(key_exists($sec, $Data[$row['date']]))
                         {
                             $Data[$row['date']][$sec] += $row['length_in_sec'];
                             
                         }
                         else
                         {
                             $Data[$row['date']][$sec] = $row['length_in_sec'];
                         }
                         
                     }
                     else
                     {
                         $Data[$row['date']][$sec] = $row['length_in_sec'];
                     }
                     $total += 1;
                 }
                 
                //print_r($Data); exit;
                 $Date = array_unique($Date);
                 $html .= "<table border='1'><tr style='background-color:#F90417; color:#FFFFFF;'><th>SUMMARY</th><th>MTD</th><th>%</th>";
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
                     $html .= "<tr><th>"."<".(($k+1)*5)."</th>";
                     $html .= "<th>$v</th>";
                     $html .= "<th>".round($v*100/$total,2)."%</th>";
                    foreach($Date as $d)
                    {
                       $html .= "<td>".$Data[$d][$k]."</td>"; 
                    }
                    $html .= "</tr>";
                 }
                 $html .= "</table>";
                 
                 mail_send($html,'answer_mis_'.$reptype,$name,$emailId,$clientId,'Answer call mis '.$reptype);
                 
                 
                /*
                $fileName = "answer_call_mis".date('d_m_y_h_i_s');
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=".$fileName.".xls");
                header("Pragma: no-cache");
                header("Expires: 0"); 
                exit;
                */
   
    }
        
        
        
function esc_level_mis($condition,$clientId,$campaignId,$condition2,$name,$emailId,$db1,$reptype){ 
    
    
    $qry="
            SELECT DATE_FORMAT(cm.CallDate,'%d-%b-%Y')`date`,COUNT(1)`count2`,Category1,IFNULL(alertType1,alertType2)`AlertType`,
SUM(IF(cj.alertOn='both',IFNULL(count1,0)+IFNULL(count2,0),IF(cj.alertOn='email',IFNULL(count1,0),IFNULL(count2,0)))) `count`
FROM call_master cm 
INNER JOIN crone_job cj ON cm.id=cj.data_id
LEFT JOIN(SELECT data_id `data_id1`,alertType `alertType1`,SUM(mail_status)`count1` FROM mail_log WHERE alertType !='Alert' AND alertType !='Report' GROUP BY data_id,alertType)AS ml 
ON cm.id=ml.data_id1 AND cj.alertType = ml.alertType1
LEFT JOIN(SELECT data_id `data_id2`,alertType `alertType2`,SUM(sms_status)`count2` FROM sms_log WHERE alertType !='Alert' AND alertType !='Report' GROUP BY data_id,alertType)AS sl 
ON cm.id=sl.data_id2 AND cj.alertType = sl.alertType2
WHERE cj.alertType !='Alert' AND cj.alertType !='Report' and cm.ClientId='$clientId' and $condition2
GROUP BY DATE(cm.CallDate),Category1,cj.alertType
            ";
            
             $execute = mysql_query($qry,$db1);
                  
                 while($row = mysql_fetch_assoc($execute))
               
          
                 {
                     $Date[] = $row['date'];
                     
                     if($row['AlertType']!='')
                     {
                     if(key_exists($row['AlertType'],$Escalation))
                     $Escalation[$row['AlertType']] += $row['count'];
                     else
                     $Escalation[$row['AlertType']] = $row['count'];
                     }
                     
                     if(key_exists($row['Category1'],$CategoryArr))
                     {
                         if(key_exists($row['AlertType'],$CategoryArr[$row['Category1']]))
                         $CategoryArr[$row['Category1']][$row['AlertType']] += $row['count'];
                         else
                         $CategoryArr[$row['Category1']][$row['AlertType']] = $row['count'];
                     }
                     else
                     {$CategoryArr[$row['Category1']][$row['AlertType']] = $row['count'];}
                     
                     $DataArr2[$row['AlertType']][$row['date']] = $row['count'];
                     
                     $DateArr[$row['date']]['esc'] += $row['count'];
                     $DateArr[$row['date']]['tag'] += $row['count2'];
                     
                     if($row['AlertType']!='')
                     {$Data[$row['date']][$row['Category1']][$row['AlertType']]=$row['count'];}
                     $totalEsc += $row['count'];
                     $total += $row['count2'];
                     $category[] = $row['Category1'];
                     
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
                
                mail_send($html,'esc_level_mis_'.$reptype,$name,$emailId,$clientId,'Escalation level mis '.$reptype); 
                /*
                echo $html;  
                $fileName = "esc_level_mis".date('d_m_y_h_i_s');
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=".$fileName.".xls");
                header("Pragma: no-cache");
                header("Expires: 0"); 
                exit;
                 */
        }
        
function incall_details_mis($condition,$clientId,$campaignId,$condition3,$name,$emailId,$db1,$reptype){ 
    $ecr        =   mysql_query("SELECT Label FROM ecr_master WHERE CLIENT='$clientId' GROUP BY Label ORDER BY Label ASC",$db1);
    $field      =   mysql_query("SELECT * FROM field_master WHERE ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority",$db1);
    $fieldhead  =   mysql_query("SELECT * FROM field_master WHERE ClientId='$clientId' AND FieldStatus IS NULL ORDER BY Priority",$db1);
    $calldata   =   mysql_query("SELECT * FROM call_master WHERE ClientId='$clientId' AND $condition3",$db1);
    
    $headervalue=array();
    while($fieldno = mysql_fetch_assoc($fieldhead)){            
        $headervalue[] = 'Field'.$fieldno['fieldNumber'];
    }
    
    $keys=array();
    while($key = mysql_fetch_assoc($ecr)){            
        $keys[]= $key['Label'];
    }
    
    $html = "<table border='2'>";
        $html .= "<tr style='background-color:#F90417; color:#FFFFFF;'>";
            $html .= "<th>IN CALL ID</th>";
            $html .= "<th>CALL FROM</th>";
            foreach($keys as $k){$no=$k-1;if($k =='1'){$html .= "<th>SCENARIO</th>";}else {$html .= "<th>"."SUB SCENARIO".$no."</th>";}}
            while($row1 = mysql_fetch_assoc($field)){$html .= "<th>".$row1['FieldName']."</th>";}
            $html .= "<th>Call Action</th>";
            $html .= "<th>Call Sub Action</th>";
            $html .= "<th>Call Action Remarks</th>";
            $html .= "<th>Closer Date</th>";
            $html .= "<th>Follow Up Date</th>";
            $html .= "<th>Call Date</th>";
            $html .= "<th>Tat</th>";
            $html .= "<th>Due Date</th>";
            $html .= "<th>Call Created</th>";
        $html .= "</tr>";
       
        while($row2 = mysql_fetch_assoc($calldata)){
            if($row2['CloseLoopingDate'] !=""){$cld=$row2['CloseLoopingDate'];}else{$cld="";}
            
            $html .= "<tr>";
                $html .= "<td>1".$row2['SrNo']."</td>";
                $html .= "<td>2".$row2['MSISDN']."</td>";
                foreach($keys as $k){$html .= "<td>".$row2["Category".$k]."</td>";}
                foreach($headervalue as $header){$html .= "<td>".$row2[$header]."</td>";}
                $html .= "<td>".$row2['CloseLoopCate1']."</td>";
                $html .= "<td>".$row2['CloseLoopCate2']."</td>";
                $html .= "<td>".$row2['closelooping_remarks']."</td>";
                $html .= "<td>".$cld."</td>";
                $html .= "<td>".$row2['FollowupDate']."</td>";
                $html .= "<td>".$row2['CallDate']."</td>";
                $html .= "<td>".$row2['tat']."</td>";
                $html .= "<td>".$row2['duedate']."</td>";
                $html .= "<td>".$row2['callcreated']."</td>";
            $html .= "</tr>";
        }
        
    $html .= "</table>";   
    mail_send($html,'incall_details_mis_'.$reptype,$name,$emailId,$clientId,'In call details mis '.$reptype); 
}
        

function category_mis($condition,$clientId,$campaignId,$condition2,$name,$emailId,$db1,$reptype){ 
    $qry1 = "SELECT ecrName FROM obecr_master WHERE CLIENT='$clientId' and CampaignId='$campaignId' AND Label='1'";
    $getEcr = mysql_query($qry1,$db1);
    while($ecrRow = mysql_fetch_assoc($getEcr)){
        $categoryName =$ecrRow['ecrName'];  
        $html= getCategory($clientId,$categoryName,$condition2,$db1);
        #echo $html;
    }
    mail_send($html,$categoryName.'_'.$reptype,$name,$emailId,$clientId,$categoryName.' wise mis '.$reptype);    
}

function getCategory($clientId,$categoryName,$condition2,$db1){
    $qry="SELECT DATE_FORMAT(CallDate,'%d-%b-%Y') `date`,Category1,Category2,COUNT(1)`count` FROM call_master_out cm WHERE ClientId=$clientId and Category1='$categoryName' and $condition2 GROUP BY DATE(CallDate),Category2";
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