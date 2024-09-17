<?php  


        
        
        function call_mis($condition,$clientId,$campaignId,$condition2)
        {
            $qry =  "SELECT COUNT(*) `Total`,
            SUM(If(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',1,0)) `Answered`,
            SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`,
            SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',t1.length_in_sec,0)) `TotalAcht`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
            WHERE $condition and
             $campaignId and t2.term_reason!='AFTERHOURS' limit 1";
            
            $call_excute = mysql_query($qry);
            $tot1 = mysql_fetch_assoc($call_excute);
            
            
            $answer =$tot1['Answered']; 
            $aband = $tot1['Abandon'];
            $acht = $tot1['TotalAcht'];
            $total = $answer + $aband;
            
            $select = "select Category1,count(Category1)`count` from call_master where clientId='$clientId' group by Category1";
            $categoryExecute = mysql_query($select);
            
            $category = array(); $tot2 = 0;
            while($row = mysql_fetch_assoc($categoryExecute))
            {
                $category[] = array($row['Category1'],$row['count'],'0%');
                $tot2 += $row['count'];
            }
            
            $SumeryDetails=array(
                 array( 'SUMMARY',       'MTD',    '%'),
                array('Received Calls',$total,$total !=0?"100%":""),
                array('Answered Calls',$answer,$answer?round($answer*100/$total)."%":""),
                array('Abandoned Calls',$aband!=0?$aband:"",$aband != 0?round($aband*100/$total).'%':""),
                array('AHT',$acht != 0?round($acht/$answer):"",$acht != 0?round($acht/$answer)."%":""),
                array('Tagging Details',$tot2,$tot2 !=0?"100%":""),
                );
            //print_r($category); exit;
            $exportArray1=array_merge($SumeryDetails,$category);
            
            $qry1 = "SELECT date_format(t2.call_date,'%d-%b-%Y') `date`,COUNT(*) `Total`,
            SUM(If(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',1,0)) `Answered`,
            SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`,
            SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',t1.length_in_sec,0)) `TotalAcht`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND (t2.queue_seconds is null OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
            WHERE $condition and
             $campaignId and t2.term_reason!='AFTERHOURS' group by date(t2.call_date)";
            
            
            $call_details_detail_excute = mysql_query($qry1);
            //$tot1 = mysql_fetch_assoc($call_details_detail_excute);
            
            while($row=mysql_fetch_assoc($call_details_detail_excute))
            {
                $DateArr[] = $row['date'];
                $Arr['Receive'] = $row['Answered']+$row['Abandon'];
                $Arr['Answer']  = $row['Answered'];
                $Arr['Aband']   = $row['Abandon'];
                $Arr['Aht']     = $row['TotalAcht']/$row['Answered'];
                $TotalArr[$row['date']] = $Arr;
                $totalcount +=$Arr['Receive']; 
            }
            $html = "<table border='1'>";
            $html .= "<tr color='red'>";
            $html .="<th><b>Summary</b></th>";
            $html .="<th><b>MTD<b></th>";
            $html .="<th><b>%</b></th>";
            foreach($DateArr as $d)
            {
                $html .= "<th><b>".$d."</b></th>";
            }
            $html .= "</tr>";
            
            $flag = true;
            foreach($Arr as $k=>$v)
            {
                $html .= "<tr>";
                $html .= "<td>".$k."</td>";
                $html .= "<td>".$v."</td>";
                if($flag)
                {$totalcount = $v; $flag = false;}
                $html .= "<td>".round($v*100/$v,2)."</td>";
                
                foreach($DateArr as $m)
                {
                    //echo $m;
                    $html .= "<td>".$TotalArr[$m][$k]."</th>";
                    //print_r($DateArr); exit;
                }
                $html .= "</tr>";
            }
            
            $select = "SELECT DATE_FORMAT(cm.CallDate,'%d-%b-%Y') `date`,cm.Category1,COUNT(1) `count` FROM call_master cm 
                where clientId='$clientId'
             GROUP BY cm.Category1,DATE(cm.CallDate)";
            
            $call_detail_excute = mysql_query($select);
            
            while($row=mysql_fetch_assoc($call_detail_excute))
            {
                $TotalArr2[$row['date']][$row['Category1']] = $row;
                $Category[$row['Category1']] += $row['count'];
                $dateArr[$row['date']] +=  $row['count'];
                $total += $row['count'];
            }
            
            $Category = array_unique($Category);
            
            $html .="<tr><td><b>Tagging</b></td>";
            $html .="<td><b>$total</b></td>";
            $html .="<td><b>100%</b></td>";
            foreach($DateArr as $k)
            {
                $html .="<td><b>".$dateArr[$k]."</b></td>";
            }
            
            
            $html .="</tr>";
            
            foreach($Category as $k=>$v)
            {
                $a = $k;
                $html .= "<tr>";
                $html .= "<td>".$a."</td>";
                $html .= "<td>".$v."</td>";
                $html .= "<td>".round($v*100/$total,2)."</td>";
                foreach($DateArr as $m)
                {   
                    $html .= "<td>".$TotalArr2[$m][$a]['count']."</th>";
                    //print_r($TotalArr2[$m][$a]);
                }
                $html .= "</tr>";
            }
           //echo $html; exit;
            $select = "SELECT DATE_FORMAT(cm.CallDate,'%d-%b-%Y') `date`,cm.Category1,cm.Category2,COUNT(1) `count` FROM call_master cm 
WHERE clientId='$clientId' GROUP BY cm.Category1,cm.Category2,DATE(cm.CallDate)";
            
            $call_detail_excute = mysql_query($select);
            
            while($row=mysql_fetch_assoc($call_detail_excute))
            {
                $TotalArr3[$row['date']][$row['Category1']][$row['Category2']] = $row;
                $Category2[$row['Category1']][$row['Category2']] += $row['count'];
                $dateArr2[$row['date']] +=  $row['count'];
                $total += $row['count'];
            }
            
            
            //print_r($Category2); exit;
            //$Category = array_unique($Category);
            foreach($Category as $k=>$v)
            {

                
                $html .="<tr><td><b>$k</b></td>";
                $html .="<td><b>$v</b></td>";
                $html .="<td><b>100%</b></td>";
                $total2 = $v;
                foreach($DateArr as $m)
                {
                     $html .= "<td><b>".$TotalArr2[$m][$k]['count']."</b></td>";
                     
                     //print_r($TotalArr2[$m][$k]['count']); 
                }
            
                $html .="</tr>";
                
                
                //print_r($Category2); 
                $Category3 = $Category2[$k];
                //print_r($Category3); 
                foreach($Category3 as $k1=>$v1)
                {
                    $html .= "<tr>";
                    $html .= "<td>".$k1."</td>";
                    $html .= "<td>".$v1."</td>";
                    $html .= "<td>".round($v1*100/$total2,2)."</td>";
                    foreach($DateArr as $m1)
                    {
                        $html .= "<td>".$TotalArr3[$m1][$k][$k1]['count']."</th>";
                        //print_r($TotalArr3[$m1][$k][$k1]); 
                    }
                    $html .= "</tr>";
                }
                
            }
            
            
            
            echo $html; exit;
            //print_r($exportArray2=array_merge($TotalCalls,$TotalPersent)); exit;
            export_excel($exportArray2,$exportArray1);
            
        }
        
        function tat_mis($condition,$clientId,$campaignId,$condition2)
        {
            $qry="SELECT cm.Category1,
                IF(DATE(cm.CloseLoopingDate)>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NOT NULL,1,
                IF((HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NOT NULL,1,0)) `outtat`,
                IF(DATE(cm.CloseLoopingDate)=DATE(cm.CallDate) AND (HOUR(cm.CloseLoopingDate)-HOUR(cm.CallDate))<=tt.time_Hours 
                AND cm.CloseLoopingDate IS NOT NULL,1,0) `intat`,

                IF(CURDATE()>DATE(cm.CallDate) AND cm.CloseLoopingDate IS NULL,1,
                IF((HOUR(NOW())-HOUR(cm.CallDate))>tt.time_Hours AND cm.CloseLoopingDate IS NULL,1,0)) `openouttat`,

                IF(CURDATE()=DATE(cm.CallDate) AND (HOUR(NOW())-HOUR(cm.CallDate))<=tt.time_Hours 
                AND cm.CloseLoopingDate IS NOT NULL,1,0) `openintat`,

                DATE_FORMAT(cm.CloseLoopingDate,'%d-%b-%Y')`CallDate`,DATE_FORMAT(cm.CallDate,'%d-%b-%Y')`CloseLoopDate`,tt.time_hours FROM call_master cm
                INNER JOIN tbl_time tt ON cm.ClientId = tt.ClientId 
                 WHERE cm.ClientId='$clientId'  AND $condition2";
            
                 $execute = mysql_query($qry);
                 
                 while($row = mysql_fetch_assoc($execute))
                 {
                     $key = $row['Category1'];
                    if(key_exists($key, $category))
                    {
                        $category[$key]['MTD'] +=1;
                        $category[$key]['intat'] +=$row['intat'];
                        $category[$key]['outtat'] +=$row['outtat'];
                        $category[$key]['openintat'] +=$row['openintat'];
                        $category[$key]['openouttat'] +=$row['openouttat'];
                        $category[$key][$row['CloseLoopDate']]['intat'] =$row['intat'];
                        $category[$key][$row['CloseLoopDate']]['outtat'] =$row['outtat'];
                        $category[$key][$row['CloseLoopDate']]['openintat'] =$row['openintat'];
                        $category[$key][$row['CloseLoopDate']]['openouttat'] =$row['openouttat'];
                        
                    }
                    else
                    {
                        $category[$key]['MTD'] =1;
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
                 
                 
                 
                 $html .= "<table border='1'>";
                 $html .= "<tr><th><b>Summary</b></th>";
                 $html .= "<th><b>MTD</b></th>";
                 $html .= "<th><b>%</b></th>";
                 
                 foreach($DataArr as $k)
                 {
                     $html .= "<th><b>".$k."</b></th>";
                 }
                 $html .= "</tr>";
                 
                 $keys = array_keys($category);
                 //print_r($category); 
                 $header = array('MTD'=>'','intat'=>'CLOSURE WITHIN TAT','outtat'=>'CLOSURE OUT OF TAT','openintat'=>'OPEN WITHIN TAT','openouttat'=>'OPEN OUT OF TAT');
                 
                 
                foreach($header as $k1=>$v1)
                {
                 foreach($keys as $k)
                 {
                    $html .= "<tr><th><b>Total ".$k.' '.$v1."</b></th>";
                    $html .= "<th><b>".$category[$k][$k1]."</b></th>"; 
                    $html .= "<th><b>".round($category[$k][$k1]*100/$total,2)."%</b></th>";
                    foreach($DataArr as $k2)
                    {
                     $html .= "<td>".$category[$k][$k2][$k1]."</td>";
                    }
                   $html .= "</tr>";     
                 }
                }
                
                echo $html; exit;
        }
        
        function tagging_mis($condition,$clientId,$campaignId,$condition2)
        {
            $qry="SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,COUNT(*) `Total`,
            SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',1,0)) `Answered`,
            SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`,
            SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',t1.length_in_sec,0)) `TotalAcht`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND (t2.queue_seconds IS NULL OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`,Category1,Category2,COUNT(Category1)`count`
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
            LEFT JOIN db_dialdesk.call_master cm ON DATE(cm.CallDate) = DATE(t2.call_date)
            WHERE $condition AND $campaignId AND
              t2.term_reason!='AFTERHOURS' $condition2";
            
                 $execute = mysql_query($qry);
                 
                 while($row = mysql_fetch_assoc($execute))
                 {
                     $key = $row['Category1'];
                    if(key_exists($key, $category))
                    {
                        $category[$key]['Answer'] +=$row['Answered'];
                        $category[$key]['count'] +=$row['count'];
                        $category[$key][$row['date']]['count'] =$row['count'];
                        $category[$key]['Category2'][$row['Category2']]['count'] +=$row['count'];
                        $category[$key]['Category2'][$row['Category2']][$row['date']] =$row['count'];
                    }
                    else
                    {
                        $category[$key]['Answer'] =$row['Answered'];
                        $category[$key]['count'] =$row['count'];
                        $category[$key][$row['date']]['count'] =$row['count'];
                        $category[$key]['Category2'][$row['Category2']]['count'] +=$row['count'];
                        $category[$key]['Category2'][$row['Category2']][$row['date']] =$row['count'];
                    }
                    
                    $anstotal +=$row['Answered'];
                    $tagtotal +=$row['count'];
                    $DataArr[] = $row['date'];
                 }
                 
                 
                 
                 $html .= "<table border='1'>";
                 $html .= "<tr><th><b>Summary</b></th>";
                 $html .= "<th><b>MTD</b></th>";
                 $html .= "<th><b>%</b></th>";
                 
                 $DataArr = array_unique($DataArr);
                 foreach($DataArr as $k)
                 {
                     $html .= "<th><b>".$k."</b></th>";
                 }
                 $html .= "</tr>";
                 //print_r($category);
                 //print_r($html); exit;
                 $keys = array_keys($category);
                 //print_r($category); 
                 //$header = array('MTD'=>'','intat'=>'CLOSURE WITHIN TAT','outtat'=>'CLOSURE OUT OF TAT','openintat'=>'OPEN WITHIN TAT','openouttat'=>'OPEN OUT OF TAT');
                 
                 foreach($keys as $k)
                 {
                    foreach($DataArr as $k1)
                    {
                     $DataArr2[$k1]['answer'] += $category[$k][$k1]['Answer'];
                     $DataArr2[$k1]['count'] += $category[$k][$k1]['count'];
                    }
                 }
                //foreach($header as $k1=>$v1)
                //{
                $html .= "<tr><th>Total Answered Calls</th>"; 
                $html .= "<th>$anstotal</th>";
                $html .= "<th></th>";
                 
                foreach($DataArr as $k1)
                {
                    $html .= "<th><b>".$DataArr2[$k1]['answer']."</b></th>";
                }
                $html .= "</tr>";
                
                $html .= "<tr><th>Tagging Calls</th>"; 
                $html .= "<th>$tagtotal</th>";
                $html .= "<th>".round($tagtotal*100/$anstotal,2)."%</th>";
                
                foreach($DataArr as $k1)
                {
                    $html .= "<th><b>".$DataArr2[$k1]['count']."</b></th>";
                }
                $html .= "</tr>";
                
                
                foreach($keys as $k)
                {
                    $html .= "<tr><th>$k</th>"; 
                    $html .= "<th>".$category[$k]['count']."</th>";
                    $html .= "<th>".round($category[$k]['count']*100/$tagtotal,2)."</th>";
                    
                    foreach($DataArr as $k1)
                    {
                        $html .= "<td>".$category[$k][$k1]['count']."</td>";
                    }
                    $html .= "</tr>";
                    
                    $category2 = array_keys($category[$k]['Category2']);
                    
                    foreach($category2 as $c)
                    {
                        $html .= "<tr><td>".$c."</td>";
                        //echo $category[$k]['Category2'][$c]['count']; exit;
                        $html .= "<td>".$category[$k]['Category2'][$c]['count']."</td>";
                        $html .= "<td>".round($category[$k]['Category2'][$c]['count']*100/$category[$k]['count'],2)."%</td>";
                        foreach($DataArr as $k1)
                        {
                            $html .= "<td>".$category[$k]['Category2'][$c][$k1]['count']."</td>";
                        }
                        $html .= "</tr>";
                    }
                }
                    //$html .= "<th><b>".$DataArr2[$k1]['answer']."</b></th>";
                    
                    //$html .= "<tr><th><b>Total ".$k.' '.$v1."</b></th>";
                    //$html .= "<th><b>".$category[$k][$k1]."</b></th>"; 
                    //$html .= "<th><b>".round($category[$k][$k1]*100/$total,2)."%</b></th>";
                    
                   
                 
                //}
                
                echo $html; exit;
        }
        
        function time_mis($condition,$clientId,$campaignId,$condition2)
        {
            $qry="SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,HOUR(t2.call_date)`hour`,MINUTE(t2.call_date)`minute`,COUNT(1) `Total`,
            SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',1,0)) `Answered`,
            SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`,
            SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',t1.length_in_sec,0)) `TotalAcht`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND (t2.queue_seconds IS NULL OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`,Category1,Category2,COUNT(Category1)`count`
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
            LEFT JOIN db_dialdesk.call_master cm ON DATE(cm.CallDate) = DATE(t2.call_date)
            WHERE $condition and $campaignId and
              t2.term_reason!='AFTERHOURS' $condition2 ";
            
                 $execute = mysql_query($qry);
                 $days = array('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
                 while($row = mysql_fetch_assoc($execute))
                 {
                  
                    if($row['minute']>30){$key = 0;}
                    else{$key = 1;}

                   if(key_exists($row['date'], $dateArr))
                    {
                       if(key_exists($row['hour'], $dateArr[$row['date']]))
                       {   
                            if(key_exists($key,$dateArr[$row['date']][$row['hour']]))
                            {
                                $dateArr[$row['date']][$row['hour']][$key]['Answered'] +=$row['Answered'];
                                    $dateArr[$row['date']][$row['hour']]['Abandon'] +=$row['Abandon'];
                                    $dateArr[$row['date']][$row['hour']]['Total'] +=$row['Answered'] + $row['Abandon'];
                            }
                            
                            else 
                                {
                                    $dateArr[$row['date']][$row['hour']][$key]['Answered']=$row['Answered'];
                                    $dateArr[$row['date']][$row['hour']][$key]['Abandon']=$row['Abandon'];
                                    $dateArr[$row['date']][$row['hour']][$key]['Total']=$row['Answered'] + $row['Abandon'];
                                }
                       }
                       else
                       {
                            $dateArr[$row['date']][$row['hour']][$key]['Answered']=$row['Answered'];
                            $dateArr[$row['date']][$row['hour']][$key]['Abandon']=$row['Abandon'];
                            $dateArr[$row['date']][$row['hour']][$key]['Total']=$row['Answered'] + $row['Abandon'];
                            
                       }
                       
                    }
                    else
                    {
                        $dateArr[$row['date']][$row['hour']][$key]['Answered']=$row['Answered'];
                        $dateArr[$row['date']][$row['hour']][$key]['Abandon']=$row['Abandon'];
                        $dateArr[$row['date']][$row['hour']][$key]['Total']=$row['Answered'] + $row['Abandon'];
                        $Hour []= $row['hour'];
                    }
                    $Date[] = $row['date'];
                 }
                 
                 $Date = array_unique($Date);
                 $Hour = array_unique($Hour);
                 echo "<table border='1'><tr><th>Date</th>";
                 foreach($Date as $d)
                 {
                     echo "<th colspan='3'>".$d."</th>";
                 }
                 echo "</tr>";
                 
                 echo "<tr><th>Timing</th>";
                 foreach($Date as $d)
                 {
                     echo "<th>Total</th>";
                     echo "<th>Answered</th>";
                     echo "<th>Abandon</th>";
                 }
                 echo "</tr>";
                 foreach($Hour as $h)
                 {
                     
                     echo "<tr><th>".$h.":00AM to $h:30 AM</th>";
                    foreach($Date as $d)
                    {
                        echo "<th>".$dateArr[$d][$h][0]['Answered']."</th>"; 
                        echo "<th>".$dateArr[$d][$h][0]['Abandon']."</th>"; 
                        echo "<th>".$dateArr[$d][$h][0]['Total']."</th>"; 
                    }
                    echo "<tr><th>".$h.":30AM to ".($h+1).":00 AM</th>";
                    foreach($Date as $d)
                    {
                        echo "<th>".$dateArr[$d][$h][1]['Answered']."</th>"; 
                        echo "<th>".$dateArr[$d][$h][1]['Abandon']."</th>"; 
                        echo "<th>".$dateArr[$d][$h][1]['Total']."</th>"; 
                    }
                 }
                 
                 
                
                echo $html; exit;
        }
        
        function agent_mis($condition,$clientId,$campaignId,$condition2)
        { 
            $qry="SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,cm.AgentId,COUNT(1) `Total`,am.username,
            SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',1,0)) `Answered`,
            SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`,
            SUM(IF(t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR',t1.length_in_sec,0)) `TotalAcht`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=20,1,0)) `WIthinSLA`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=60,1,0)) `WIthinSLASix`,
            SUM(IF((t2.status='SALE' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='QUEUE' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR') AND t2.queue_seconds<=90,1,0)) `WIthinSLANin`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND (t2.queue_seconds IS NULL OR t2.queue_seconds<=20)),1,0)) `AbndWithinThresold`,
            SUM(IF(((t2.status IS NULL OR t2.status='DROP')
            AND t2.queue_seconds>20),1,0)) `AbndAfterThresold`,Category1,Category2,COUNT(Category1)`count`
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
            LEFT JOIN db_dialdesk.call_master cm ON cm.LeadId = t2.lead_id
            LEFT JOIN db_dialdesk.agent_master am ON am.id = cm.AgentId
            WHERE $condition AND cm.clientId='$clientId' AND
              t2.term_reason!='AFTERHOURS' $condition2";
            
                 $execute = mysql_query($qry);
                  //echo $qry;
                 while($row = mysql_fetch_assoc($execute))
                 {
                     $Date[] = $row['date'];
                     $Agent[] = $row['username'];
                     $Data[$row['date']][$row['username']] = $row;
                 }
                 
                 $Date = array_unique($Date);
                 $Agent = array_unique($Agent);
                 echo "<table border='1'><tr><th>Date</th>";
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
                        echo "<th>".($Data[$d][$v]['Answered']+$Data[$d][$v]['Abandon'])."</th>"; 
                        echo "<th>".$Data[$d][$v]['Answered']."</th>"; 
                        echo "<th>".$Data[$d][$v]['Abandon']."</th>"; 
                    }
                    echo "</tr>";
                 }
                 
                 
                
                echo $html; exit;
        }
       
        function aband_mis($condition,$clientId,$campaignId,$condition2)
        { 
           $qry="SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,t2.length_in_sec
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE $condition AND
t2.term_reason!='AFTERHOURS' $condition2";
            
                 $execute = mysql_query($qry);
                  
                 while($row = mysql_fetch_assoc($execute))
                 {
                     
                     $sec = round($row['length_in_sec']/5,0);
                     $Date[] = $row['date'];
                     
                     if(key_exists($sec, $secArr))
                     {$secArr[$sec] = +1;}
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
                 
                 //print_r($Data); //exit;
                 $Date = array_unique($Date);
                 $html = "<table border='1'><tr><th>SUMMARY</th><th>MTD</th><th>%</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".$d."</th>";
                 }
                 $html .= "</tr>";
                 
                 $html .= "<tr><th>TOTAL ABANDONED CALLS</th><th>$total</th><th>100%</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".$d."</th>";
                 }
                 $html .= "</tr>";
                 
                 $html .= "<tr></tr>";
                 $html .= "<tr><th>ABANDONED CALLS DETAILS<th></tr>";
                 
                 
                 
                 //print_r($secArr); 
                 foreach($secArr as $k=>$v)
                 {
                     $html .= "<tr><th>"."<".(($k+1)*5)."</th>";
                     $html .= "<th>$v</th>";
                     $html .= "<th>".round($v*100/$total,2)."%</th>";
                    foreach($Date as $d)
                    {
                        $html .= "<th>".$Data[$d][$k]."</th>"; 
                    }
                    $html .= "</tr>";
                 }
                 
                 
                
                echo $html; exit;
        }
        
        function answer_mis($condition,$clientId,$campaignId,$condition2)
        { 
            $qry="SELECT DATE_FORMAT(t2.call_date,'%d-%b-%Y') `date`,t2.length_in_sec, IF(t2.status IS NULL OR t2.status='DROP',1,0) `Abandon`
FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid
WHERE $condition AND
t2.term_reason!='AFTERHOURS' $condition2";
          
            
                 $execute = mysql_query($qry);
                  
                 while($row = mysql_fetch_assoc($execute))
                 {
                     
                     $sec = round($row['length_in_sec']/5,0);
                     $Date[] = $row['date'];
                     
                     if(key_exists($sec, $secArr))
                     {$secArr[$sec] = +1;}
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
                 
                 //print_r($Data); //exit;
                 $Date = array_unique($Date);
                 $html = "<table border='1'><tr><th>SUMMARY</th><th>MTD</th><th>%</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".$d."</th>";
                 }
                 $html .= "</tr>";
                 
                 $html .= "<tr><th>TOTAL ANSWERED CALLS</th><th>$total</th><th>100%</th>";
                 foreach($Date as $d)
                 {
                     $html .= "<th>".$d."</th>";
                 }
                 $html .= "</tr>";
                 
                 $html .= "<tr></tr>";
                 $html .= "<tr><th>ANSWERED CALLS DETAILS<th></tr>";
                 
                 //print_r($secArr); 
                 foreach($secArr as $k=>$v)
                 {
                     $html .= "<tr><th>"."<".(($k+1)*5)."</th>";
                     $html .= "<th>$v</th>";
                     $html .= "<th>".round($v*100/$total,2)."%</th>";
                    foreach($Date as $d)
                    {
                        $html .= "<th>".$Data[$d][$k]."</th>"; 
                    }
                    $html .= "</tr>";
                 }
                 
                 
                
                echo $html; exit;
        }

        function Corrective_report($condition,$clientId,$name,$emailId,$db1,$reptype)
{ 
    
    $tArr   =   mysqli_query($db1,"SELECT * FROM call_master WHERE ClientId='$clientId' AND $condition order by Category3 ASC");
    //print_r($tArr)die;
    $dataArr = array();
    while($calldata = mysqli_fetch_assoc($tArr))
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
                foreach($col2_keys as $key2){  
                    $html .= "<tr>";
                    if($a==1) { 
                    
                    $html .= "<th rowspan='".count($value)."'>".$key."</th>";
                     $a=0; } 
                    
                    $html .= "<th>".$key2."</th>";
                    
                    $html .= "<td>".$complaint = $value[$key2]['open']+$value[$key2]['close'];$complaint."</td>";
                    $html .= "<td>".$value[$key2]['open']."</td>";
                    $html .= "<td>".$value[$key2]['close']."</td>";
                    
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
                $html .= "<th style='background-color:yellow;'>".$totalarr = $grand_total_close/$grand_total_corr;round($totalarr)."</th>";
                $html .= "</tr>";
                $html .= "</table>";
                // echo $html;
                // return $html; 
                
    mail_send($html,'corrective_report_'.$reptype,$name,$emailId,$clientId,'Corrective Report '.$reptype);
}
        
        
        
//        function export_excel($SumeryDetails,$exportArray2){
//        include('Classes/PHPExcel.php');
//        $objPHPExcel = new PHPExcel();
//        
//        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
//         $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(
//        array(
//            'fill' => array(
//                'type' => PHPExcel_Style_Fill::FILL_SOLID,
//                'color' => array('rgb' => 'F90417'),
//                
//            )
//        ))->getFont()->setBold(true)
//                                ->setName('Verdana')
//                                ->setSize(10)
//                                ->getColor()->setRGB('fffff');
//         $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45);
//         
//         
//       // print_r($objPHPExcel);
//       
//       
//        
//        
//        $objWorksheet->fromArray($SumeryDetails);
//
//            $dataseriesLabels1 = array(
//                new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$B$1', NULL, 1),   //  Temperature
//            );
//            $dataseriesLabels2 = array(
//                new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$C$1', NULL, 1),   //  Rainfall
//            );
//            $dataseriesLabels3 = array(
//                new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$D$1', NULL, 1),   //  Humidity
//            );
//
//
//            $xAxisTickValues = array(
//                new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$2:$A$13', NULL, 12),    //  Jan to Dec
//            );
//
//            $dataSeriesValues1 = array(
//                new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$B$2:$B$13', NULL, 12),
//            );
//
//            //  Build the dataseries
//            $series1 = new PHPExcel_Chart_DataSeries(
//                PHPExcel_Chart_DataSeries::TYPE_BARCHART,       // plotType
//                PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,  // plotGrouping
//                range(0, count($dataSeriesValues1)-1),          // plotOrder
//                $dataseriesLabels1,                             // plotLabel
//                $xAxisTickValues,                               // plotCategory
//                $dataSeriesValues1                              // plotValues
//            );
//
//            $series1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
//
//            $dataSeriesValues2 = array(
//                new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$C$2:$C$13', NULL, 12),
//            );
//
//            //  Build the dataseries
//            $series2 = new PHPExcel_Chart_DataSeries(
//                PHPExcel_Chart_DataSeries::TYPE_LINECHART,      // plotType
//                PHPExcel_Chart_DataSeries::GROUPING_STANDARD,   // plotGrouping
//                range(0, count($dataSeriesValues2)-1),          // plotOrder
//                $dataseriesLabels2,                             // plotLabel
//                NULL,                                           // plotCategory
//                $dataSeriesValues2                              // plotValues
//            );
//
//            $dataSeriesValues3 = array(
//                new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$D$2:$D$13', NULL, 12),
//            );
//
//            //  Build the dataseries
//            $series3 = new PHPExcel_Chart_DataSeries(
//                PHPExcel_Chart_DataSeries::TYPE_AREACHART,      // plotType
//                PHPExcel_Chart_DataSeries::GROUPING_STANDARD,   // plotGrouping
//                range(0, count($dataSeriesValues2)-1),          // plotOrder
//                $dataseriesLabels3,                             // plotLabel
//                NULL,                                           // plotCategory
//                $dataSeriesValues3                              // plotValues
//            );
//
//
//            //  Set the series in the plot area
//            $plotarea = new PHPExcel_Chart_PlotArea(NULL, array($series1, $series2, $series3));
//            //  Set the chart legend
//            $legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
//
//            $title = new PHPExcel_Chart_Title('DASHBOARD');
//
//
//            //  Create the chart
//            $chart = new PHPExcel_Chart(
//                'chart1',       // name
//                $title,         // title
//                $legend,        // legend
//                $plotarea,      // plotArea
//                true,           // plotVisibleOnly
//                0,              // displayBlanksAs
//                NULL,           // xAxisLabel
//                NULL            // yAxisLabel
//            );
//
//            $chart->setTopLeftPosition('D1');
//            $chart->setBottomRightPosition('M15');
//            $objWorksheet->addChart($chart);
//
//            
//        //=====================================
//           if($exportArray2!=NULL){
//            $objPHPExcel->createSheet();
//
//// Add some data to the second sheet, resembling some different data types
//       $objWorksheet= $objPHPExcel->setActiveSheetIndex(1);
//
//
//// Rename 2nd sheet
////$objPHPExcel->getActiveSheet()->setTitle('Second sheet'); 
//        
//        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(
//        array(
//            'fill' => array(
//                'type' => PHPExcel_Style_Fill::FILL_SOLID,
//                'color' => array('rgb' => 'F90417'),
//                
//            )
//        ))->getFont()->setBold(true)
//                                ->setName('Verdana')
//                                ->setSize(10)
//                                ->getColor()->setRGB('fffff');
//         $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45);      
//            
//             $objWorksheet->fromArray($exportArray2);
//             
//           }
//            
//        //====================================
//            
//            
//            
//
//   $fileName = date("m-d-Y") . 'xlsx';
//    if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');
//    header('Content-Type: application/force-download');
//    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//    header('Content-Disposition: attachment; filename="'. $fileName . '"');
//    header('Content-Transfer-Encoding: binary');
//    header('Accept-Ranges: bytes');
////header("Content-Type: application/vnd.ms-excel; name='excel'");
////header("Content-type: application/octet-stream");
////header("Content-Disposition: attachment; filename=$fileName."."xls");
////header("Pragma: no-cache");
////header("Expires: 0");
//    header('Cache-control: no-cache, pre-check=0, post-check=0');
//    header('Cache-control: private');
//    header('Pragma: private');
//    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // any date in the past
//    echo $objPHPExcel;
//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//    $objWriter->setIncludeCharts(TRUE);
//    $objWriter->save('php://output');
//   exit;
//    }
?>