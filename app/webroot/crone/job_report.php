<?php
 include('report-send.php');
 
 
mysql_connect('localhost','root','vicidialnow',false,128);
mysql_select_db('db_dialdesk') or die('unable to connect');

include('function_report.php');

$select = "SELECT *
FROM reportmatrix_master
WHERE IF(report_type='monthly' AND LAST_DAY(CURDATE())=CURDATE(),TRUE,FALSE)
";
$export = mysql_query($select);



while($row = mysql_fetch_assoc($export))
{
    $call_mis_monthly = "DATE(t2.call_date) BETWEEN DATE_FORMAT(CURDATE() ,'%Y-%m-01') AND LAST_DAY(CURDATE())";
    $tat_mis_monthly = "DATE(cm.CallDate) BETWEEN DATE_FORMAT(CURDATE() ,'%Y-%m-01') AND LAST_DAY(CURDATE())";
    $esc_mis_monthly = "DATE(c.createdate) BETWEEN DATE_FORMAT(CURDATE() ,'%Y-%m-01') AND LAST_DAY(CURDATE())";
    
    
    
   
    
    
    $select = "select campaignid from registration_master where company_id='{$row['client_id']}'";
    $excute_campaign = mysql_query($select);
    $camaign = mysql_fetch_assoc($excute_campaign);
    $camaign = $camaign['campaignid'];
    
    call_mis($call_mis_monthly,$row['cleint_id'],$camaign);
    
    if($row['report']=='Call MIS')
    {
        call_mis($call_mis_monthly,$row['client_id'],$camaign,$call_mis_monthly);
    }
    else if($row['report']=='Tagging MIS')
    {$condition1 = "DATE(cm.CallDate) DATE_FORMAT(CURDATE() ,'%Y-%m-01') BETWEEN CURDATE()";
        $condition2 = "GROUP BY DATE(t2.call_date),cm.Category1";
        tagging_mis($condition1,$row['client_id'],$camaign,$condition2);
       // tagging_mis($tat_mis_daily,$row['client_id'],$camaign,$tat_mis_daily2);
    }
    else if($row['report']=='Time Wise MIS')
    {
        $condition1 = "DATE(cm.CallDate) DATE_FORMAT(CURDATE() ,'%Y-%m-01') BETWEEN CURDATE()";
        $condition2 = "GROUP BY DATE(t2.call_date),cm.Category1";
        time_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Agent Wise MIS')
    {$condition1 = "DATE(cm.CallDate) DATE_FORMAT(CURDATE() ,'%Y-%m-01') BETWEEN CURDATE()and $camaign";
        $condition2 = "GROUP BY DATE(t2.call_date),cm.Category1";
        agent_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Abend Call MIS')
    {$condition1 = "DATE(t2.call_date) DATE_FORMAT(CURDATE() ,'%Y-%m-01') BETWEEN CURDATE() and $camaign";
        $condition2="and IF(t2.status IS NULL OR t2.status='DROP',true,false)";
        aband_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Answer Call MIS')
    {$condition1 = "DATE(cm.CallDate) DATE_FORMAT(CURDATE() ,'%Y-%m-01') BETWEEN CURDATE() and $camaign";
        $condition2 = "GROUP BY DATE(t2.call_date),cm.Category1";
        answer_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Category Wise MIS')
    {
        $condition1 = "";
        $condition2 = "";
        answer_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Escalation Level MIS')
    {
        $condition1 = "";
        $condition2 = "";
        esc_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
}

$select = "SELECT * FROM reportmatrix_master
WHERE IF(report_type='weekly' AND LOWER(DAYNAME(CURDATE()))=report_value,TRUE,FALSE)
";
$export = mysql_query($select);
while($row = mysql_fetch_assoc($export))
{
    $dayName = $row['report_value'];
    $call_mis_weekly = "DATE(t2.call_date) BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND CURDATE()
            AND DAYNAME(CURDATE()) = '$dayName'";
    
    $tat_mis_weekly = "DATE(cm.CallDate) BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND CURDATE()
            AND DAYNAME(CURDATE()) = '$dayName'";
    
    $tat_mis_weekly2 = "group by DATE(cm.CallDate)";
    
    $esc_mis_weekly = "DATE(c.createdate) BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND CURDATE()
            AND DAYNAME(CURDATE()) = '$dayName'";
    
    
    if($row['report']=='Call MIS')
    {
        call_mis($call_mis_daily,$row['client_id'],$camaign,$call_mis_daily2);
    }
    
    else if($row['report']=='TAT MIS')
    {
        tagging_mis($tat_mis_daily,$row['client_id'],$camaign,$tat_mis_daily2);
    }
    
    else if($row['report']=='Tagging MIS')
    {
        $condition1 = "DATE(cm.CallDate) BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND CURDATE()
            AND DAYNAME(CURDATE()) = '$dayName'";
        $condition2 = "GROUP BY DATE(t2.call_date),cm.Category1";
        tagging_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Time Wise MIS')
    {
        $condition1 = "DATE(t2.call_date) BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND CURDATE()
            AND DAYNAME(CURDATE()) = '$dayName'"; $condition2="GROUP BY DATE(t2.call_date),cm.Category1";
        time_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Agent Wise MIS')
    {
        $condition1="DATE(cm.CallDate) BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND CURDATE()
            AND DAYNAME(CURDATE()) = '$dayName' AND $camaign"; 
            $condition2="GROUP BY DATE(t2.call_date),cm.AgentId";
        agent_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Abend Call MIS')
    {
        $condition1="DATE(t2.call_date) BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND CURDATE()
            AND DAYNAME(CURDATE()) = '$dayName' and $camaign";
        $condition2="and IF(t2.status IS NULL OR t2.status='DROP',true,false)";
        
        aband_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Answer Call MIS')
    {
        $condition1="DATE(t2.call_date) BETWEEN DATE_SUB(CURDATE(),INTERVAL 7 DAY) AND CURDATE()
            AND DAYNAME(CURDATE()) = '$dayName' and $camaign";
        $condition2="and IF(t2.status IS NULL OR t2.status='DROP',true,false)";
        answer_mis($tat_mis_daily,$row['client_id'],$camaign,$tat_mis_daily2);
    }
    else if($row['report']=='Category Wise MIS')
    {
        $condition1="";$condition2="";
        answer_mis($tat_mis_daily,$row['client_id'],$camaign,$tat_mis_daily2);
    }
    else if($row['report']=='Escalation Level MIS')
    {
        $condition1="";$condition2="";
        esc_mis($tat_mis_daily,$row['client_id'],$camaign,$tat_mis_daily2);
    }
    
}

$select = "SELECT * FROM reportmatrix_master
WHERE IF(report_type='daily' AND HOUR(NOW())>=report_value,TRUE,FALSE)
";
$export = mysql_query($select);
while($row = mysql_fetch_assoc($export))
{
    $dayName = $row['report_value'];
    $call_mis_daily = "Date(t2.call_date)=CURDATE()";
    $call_mis_daily2 = "Date(t2.call_date)=CURDATE()";
    $tat_mis_daily = "Date(cm.CallDate)=CURDATE()";
    $tat_mis_daily2 = "Date(cm.CallDate)=CURDATE()";
    $esc_mis_daily = "Date(c.createdate)=CURDATE()"; 
    
    
    $select = "select campaignid from registration_master where company_id='{$row['client_id']}'";
    $excute_campaign = mysql_query($select);
    $camaign = mysql_fetch_assoc($excute_campaign);
    $camaign = "t2.campaign_id in(".$camaign['campaignid'].")";
    //print_r($row); exit;
    if($row['report']=='Call MIS')
    {
        call_mis($call_mis_daily,$row['client_id'],$camaign,$call_mis_daily2);
    }
    else if($row['report']=='Tagging MIS')
    {
        $condition1="Date(cm.CallDate)=CURDATE()and $camaign";
        $condition2 = "Date(cm.CallDate)=CURDATE()and $camaign";
        tagging_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Time Wise MIS')
    {
        $condition1="Date(cm.CallDate)=CURDATE()and $camaign";
        $condition2 = "Date(cm.CallDate)=CURDATE()and $camaign";
        time_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Agent Wise MIS')
    {
        $condition1="Date(cm.CallDate)=CURDATE()and $camaign";
        $condition2 = "Date(cm.CallDate)=CURDATE()and $camaign";
        agent_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Abend Call MIS')
    {
        $condition1="Date(t2.Call_Date)=CURDATE()and $camaign";
        $condition2 = "and Date(t2.Call_Date)=CURDATE()and $camaign";
        aband_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Answer Call MIS')
    {
        $condition1="Date(t2.call_date)=CURDATE()and $camaign";
        $condition2 = "and Date(t2.call_date)=CURDATE()and $camaign";
        answer_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Category Wise MIS')
    {
        $condition1="Date(t2.call_date)=CURDATE()and $camaign";
        $condition2 = "and Date(t2.call_date)=CURDATE() and $camaign";
        answer_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
    else if($row['report']=='Escalation Level MIS')
    {
        $condition1 = "";
        $condition2 = "";
        esc_mis($condition1,$row['client_id'],$camaign,$condition2);
    }
}



