<?php 


session_start();


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