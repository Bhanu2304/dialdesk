<?php


/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

ini_set('max_execution_time', '400');
ini_set('memory_limit', '-1');

$clientId   = $_REQUEST['ClientId'];
$FromDate   = date_format(date_create($_REQUEST['FromDate']),'Y-m-d');
$ToDate     = date_format(date_create($_REQUEST['ToDate']),'Y-m-d');

$host       =   "localhost"; 
$username   =   "root";
$password   =   "dial@mas123";
$db_name    =   "db_dialdesk";



$dd = mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name",$dd)or die("cannot select DB");


function get_th($value)
{
    return '<th style="text-align:center">'.$value.'</th>';
}
function get_td($value)
{
    return '<td style="text-align:center">'.$value.'</td>';
}

function get_client_status($status)
{
    if($status=='A')
    {
        return 'Active';
    }
    else if($status=='D')
    {
        return 'De-Active';
    }
    else if($status=='H')
    {
        return 'Hold';
    }
    else if($status=='CL')
    {
        return 'Close';
    }
}



if($clientId!='All')
{
    $where = " and  company_id='$clientId'";
}

$client_rsc = mysql_query("select * from `registration_master` where is_dd_client='1' and status='A' $where order by company_name");

$html='<table border="2">';
   $html .="<tr>";
   $html .="<th>Client</th>";
   $html .="<th>Status</th>";
   $html .="<th>Revenue</th>";
   $html .="<th>IB Pulse</th>";
   $html .="<th>IB Charge</th>";
   $html .="<th>IB Total</th>";
   
   $html .="<th>IBN Pulse</th>";
   $html .="<th>IBN Charge</th>";
   $html .="<th>IBN Total</th>";
   
   $html .="<th>OB Pulse</th>";
   $html .="<th>OB Charge</th>";
   $html .="<th>OB Total</th>";
   
   $html .="<th>SMS Pulse</th>";
   $html .="<th>SMS Charge</th>";
   $html .="<th>SMS Total</th>";
   
   $html .="<th>Email Pulse</th>";
   $html .="<th>Email Charge</th>";
   $html .="<th>Email Total</th>";
   
   
   $html .="</tr>";
$data_total = array();
while( $clientDet = mysql_fetch_assoc($client_rsc)){
    
    $clientId = $clientDet['company_id'];
    
    $BalanceMaster = mysql_fetch_assoc(mysql_query("select * from `balance_master` where clientId='$clientId'  limit 1"));
    $start_date = $BalanceMaster['start_date']; 
    
    $select = "SELECT rm.company_name,rm.status,cm.client_id,SUM(cm_total) cm_total,
SUM(ib_pulse) ib_pulse,ib_charge,SUM(ib_total) ib_total,
SUM(ibn_pulse) ibn_pulse,ibn_charge,SUM(ibn_total) ibn_total, 
SUM(ob_pulse) ob_pulse,ob_charge,SUM(ob_total) ob_total,
SUM(sms_pulse) sms_pulse,sms_charge,SUM(sms_total) sms_total,
SUM(email_pulse) email_pulse,email_charge,SUM(email_total) email_total
FROM `billing_consume_daily` cm 
INNER JOIN registration_master rm ON 
cm.client_id= rm.company_id
WHERE cm.client_id='$clientId' and DATE(cm.cm_date)>='$start_date' and  DATE(cm.cm_date) BETWEEN '$FromDate' AND '$ToDate'
"; 
    
    
   $rsc_qry = mysql_query($select); 
    
   $html.="<tr>";
   $html.=get_th($clientDet['company_name']);
    $html.=get_td(get_client_status($clientDet['status']));
   
   while($record=mysql_fetch_assoc($rsc_qry))
   {
    
        
       
        
        $html.=get_td(round($record['cm_total'],2));     $data_total['cm_total'] += $record['cm_total'];
        $html.=get_td(round($record['ib_pulse'],2));     $data_total['ib_pulse'] += $record['ib_pulse'];
        $html.=get_td(round($record['ib_charge'],2));    
        $html.=get_td(round($record['ib_total'],2));     $data_total['ib_total'] += $record['ib_total'];
        
        $html.=get_td(round($record['ibn_pulse'],2));    $data_total['ibn_pulse'] += $record['ibn_pulse'];
        $html.=get_td(round($record['ibn_charge'],2));   
        $html.=get_td(round($record['ibn_total'],2));    $data_total['ibn_total'] += $record['ibn_total'];
        
        $html.=get_td(round($record['ob_pulse'],2));     $data_total['ob_pulse'] += $record['ob_pulse'];
        $html.=get_td(round($record['ob_charge'],2));    
        $html.=get_td(round($record['ob_total'],2));     $data_total['ob_total'] += $record['ob_total'];
        
        $html.=get_td(round($record['sms_pulse'],2));    $data_total['sms_pulse'] += $record['sms_pulse'];
        $html.=get_td(round($record['sms_charge'],2));   
        $html.=get_td(round($record['sms_total'],2));    $data_total['sms_total'] += $record['sms_total'];
        
        $html.=get_td(round($record['email_pulse'],2));  $data_total['email_pulse'] += $record['email_pulse'];
        $html.=get_td(round($record['email_charge'],2)); 
        $html.=get_td(round($record['email_total'],2));  $data_total['email_total'] += $record['email_total'];
        
   
   }
     $html.="</tr>";
}
$html.="<tr>";
        $html.=get_th('Total');
        $html.=get_td('');
        
        $html.=get_td(round($data_total['cm_total'],2));    
        $html.=get_td(round($data_total['ib_pulse'],2));     
        $html.=get_td('');
        $html.=get_td(round($data_total['ib_total'],2));     
        
        $html.=get_td(round($data_total['ibn_pulse'],2));    
        $html.=get_td('');
        $html.=get_td(round($data_total['ibn_total'],2));    
        $html.=get_td(round($data_total['ob_pulse'],2));     
        $html.=get_td('');
        $html.=get_td(round($data_total['ob_total'],2));     
        $html.=get_td(round($data_total['sms_pulse'],2));    
        $html.=get_td('');
        $html.=get_td(round($data_total['sms_total'],2));    
        $html.=get_td(round($data_total['email_pulse'],2));  
        $html.=get_td('');
        $html.=get_td(round($data_total['email_total'],2));  
        
    $html.="</tr>";
    
 $html.="</table>";   
    
    
    
    



$fileName = "revenue_".date('d_m_y_h_i_s');
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$fileName.".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo $html ;die;

