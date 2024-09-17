<?php


/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

ini_set('max_execution_time', '400');
ini_set('memory_limit', '-1');

$clientId   = $_REQUEST['ClientId'];
#$FromDate   = date_format(date_create($_REQUEST['FromDate']),'Y-m-d');
#$ToDate     = date_format(date_create($_REQUEST['ToDate']),'Y-m-d');

$host       =   "localhost"; 
$username   =   "root";
$password   =   "dial@mas123";
$db_name    =   "db_dialdesk";

$ispark_host       =   "192.168.10.22"; 
$ispark_user   =   "root";
$ispark_pass   =   "321*#LDtr!?*ktasb";
$ispark_name    =   "db_bill";


$dd = mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name",$dd)or die("cannot select DB");

$ispark = mysql_connect("$ispark_host", "$ispark_user", "$ispark_pass")or die("cannot connect"); 
mysql_select_db("$ispark_name",$ispark)or die("cannot select DB");


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

function get_subs_days($PeriodType)
{
    switch ($PeriodType) 
    {
        case "YEAR":
            return  365;
            break;
        case "Half":
            return  180;
            break;
        case "Quater":
            return  90; 
            break;
        default:
            return  30;
    }
}

function get_plan_status($start_date)
{
    if(empty($start_date))
    {
        return 'Testing';
    }
    else
    {
        return date('d-M-y',strtotime($start_date));
    }
}

function get_subs_value($PeriodType,$RentalAmount)
{
    switch ($PeriodType) {
        case "YEAR":
            return $RentalAmount; 
            break;
        case "Half":
            return round($RentalAmount/2,2);
            break;
        case "Quater":
            return round($RentalAmount/4,2);
            break;
        default:
            return round($RentalAmount/12,2);
    }
}

function get_monthly_credit($Balance)
{
    return round($Balance/12,2);   
}

function get_credit_from_subs_value($rental,$balance,$subsvalue)
{
    //echo "round($balance/$rental,3)"; exit;
    $plan_pers = $balance/$rental; 
    //echo $plan_pers;exit;
     $creditvalue = round(($subsvalue*$plan_pers),2);
    //echo $creditvalue;exit
    return $creditvalue;
}

function get_op_det($clientId,$dd)
{
    $select = "SELECT * FROM `billing_ledger` bl WHERE fin_year='2022' and fin_month='Apr' and clientId = '$clientId'";
    $rsc = mysql_query($select,$dd);
    
    $record = mysql_fetch_assoc($rsc);
    return $record['subs'];
}

function get_cost_center($clientId,$ispark)
{
    
    $qry_cost = "select * from cost_master cm where dialdesk_client_id='$clientId' limit 1"; 
    $rsc = mysql_query($qry_cost,$ispark) or die(mysql_error());
    $record = mysql_fetch_assoc($rsc);
    #print_r($record);exit;
    return array($record);
}

function get_billed($ispark,$cost_center,$inv_date,$rental_credit,$balance_credit,$op2_ledger,$op2_credit)
{
    $sel_billed_fromdateqry = "select ti.* from tbl_invoice ti where  ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (ti.invoiceDate = '$inv_date' )  "; #and bill_no!='' 
    $billed_fromdateqry_arr = mysql_query($sel_billed_fromdateqry,$ispark);

    while($inv_det=mysql_fetch_assoc($billed_fromdateqry_arr))
    {
        $initial_id = $inv_det['id'];   
        $op2_ledger +=$inv_det['grnd'];
        if(strtolower($inv_det['category'])==strtolower('first_bill'))
        {
                //check whether first bill have subscritpion amount
            $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
            $rsc_subs_arr = mysql_query($select_subs,$ispark);
            while($sb=mysql_fetch_assoc($rsc_subs_arr))
            {
                 $op2_credit += get_credit_from_subs_value($rental_credit,$balance_credit,$sb['amount']);
            }

        }
        else if(strtolower($inv_det['category'])==strtolower('subscription'))
        {
            $op2_credit +=get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['total']);
        }
        else if(strtolower($inv_det['category'])==strtolower('Talk Time') || strtolower($inv_det['category'])==strtolower('Topup'))
        {
            $op2_credit +=$inv_det['total'];
        }
    }
    return array($op2_ledger,$op2_credit);
}

function get_advance($ispark,$cost_id,$from_date,$to_date,$ledger_value,$type)
{
    $cost_adv_arr = mysql_fetch_assoc(mysql_query("SELECT *,SUM(bill_passed) advance FROM `bill_pay_advance` bpa WHERE bill_no='$cost_id' AND  date(bpa.pay_dates) between '$from_date' and '$to_date' ",$ispark));
    if(!empty($cost_adv_arr))
    {
        if($type=='old')
        $ledger_value -= $cost_adv_arr['advance'];
        else 
        $ledger_value += $cost_adv_arr['advance'];    
    }
    return $ledger_value;
}

function get_collection($ispark,$cost_center,$from_date,$to_date,$bill_company,$ledger_value,$type)
{
    //getting actual outstanding as on from date.
    $select_payment_fromdateqry = "SELECT sum(bpp.bill_passed) bill_passed FROM 
    bill_pay_particulars bpp inner join tbl_invoice ti on bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year and bpp.branch_name=ti.branch_name AND bpp.company_name='$bill_company' 
    where ti.cost_center='$cost_center' and date(bpp.pay_dates)  between '$from_date' and '$to_date' ";  

    $collection_fromdate_arr = mysql_query($select_payment_fromdateqry,$ispark);
    while($coll_det = mysql_fetch_assoc($collection_fromdate_arr))
    {   if($type=='old') 
        $ledger_value -= $coll_det['bill_passed'];
        else 
        $ledger_value += $coll_det['bill_passed'];    
    }
    
    return $ledger_value;
}

function get_consumption($dd,$clientId,$cap_date,$op2_value,$type)
{
    //getting consumption from table 
    $select_consumption = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId' and date(cm_date)='$cap_date'";
    $rsc_consuption_arr = mysql_query("$select_consumption",$ispark);
    //print_r($rsc_consuption_arr);exit;
    while($consume=mysql_fetch_assoc($rsc_consuption_arr))
    {
        if($type=='old')
        $op2_value -= $consume['total'];
        else
        $op2_value += $consume['total'];    
    }
    return $op2_value;
}

function get_consumption2($dd,$clientId,$cap_date,$op2_value,$type)
{
    //getting consumption from table 
    $select_consumption = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId' and date(cm_date)='$cap_date'";
    $rsc_consuption_arr = mysql_query("$select_consumption",$dd);
    //print_r($rsc_consuption_arr);exit;
    $op2_value = 0;
    while($consume = mysql_fetch_assoc($rsc_consuption_arr))
    {
        $op2_value += $consume['total'];   
    }
    return $op2_value;
}

function get_fresh_release($subs_validity,$cap_date,$consume)
{
    foreach($subs_validity as $from_to=>$value)
    {
        
        $exp = explode('#',$from_to);
        $from = $exp[0];
        $to = $exp[1];
        $flag_break = true;
        
        if(strtotime($cap_date)>=strtotime($from))
        {
            if($to=='unlimited' || strtotime($cap_date)<=strtotime($to))
            {
                 $value -=$consume;
                 if($value>=0)
                 {
                     $subs_validity[$from_to] = $value;
                 }
                 else
                 {
                     $consume = $value*(-1);
                     $flag_break = false;
                 }
            }
        }
        
        if($flag_break)
        {
            break;
        }
        
    }
}

function index_ledger2() 
{
   
    
        
    
        //sorting array on a bases of active,hold,de-active,closed
        $data2 = array();
        foreach($data as $client=>$record){
            $status =$record['status'];
            $op =($record['op2_ledger']);
            $led_op = $record['bill2_ledger'];
            $total_collected = $record['coll2_ledger'];
            $cl=round($op+$led_op-$total_collected,2);
            $led_access = round(($record['ledger_access_usage']/118)*100,2);
            $led_topup = round(($record['ledger_topup']/118)*100,2);
            //$led_sub = round(($record['ledger_sub']/118)*100,2);

            $cln = round(($record['ledger_sub']/118)*100,2);
            $onePer = round($record['RentalAmount']/100,2); 
            $plan_pers = round($record['Balance']/$onePer,3);
            $led_bal_sub = round(($cln*$plan_pers)/100,2); 
            //$open_bal = $record['talk_time']-($led_bal_sub+$led_access+$led_topup);
            $open_bal = $record['talk_time'];
            $coll = $record['coll']+$record['subs_coll']+$record['first_plan_subscoll'];
            //echo '<br/>'.($record['subs_coll']/118;exit;


            $op_val_subs = round((($record['first_plan_subscoll'])/118)*$plan_pers,2);
            $open_bal +=$op_val_subs;
            //$fr_val_subs= round((($record['subbilled'])/118)*$plan_pers,2);
            $fr_val_subs = round((($record['subbilled_as_sub_date'])/118)*$plan_pers,2);
            $fr_val_talk= round(($record['billed']*100/118),2);
            $fr_val = $fr_val_subs+$fr_val_talk;

            $first_bill = $record['first_plan_value']+$record['new_plan_sub_cost'];
            $first_bill_with_gst = round($first_bill*1.18,2);
            $plan_sub_cost = round($record['new_plan_sub_cost'],2);
            $plan_setup_cost = round($record['new_plan_setup_cost'],2);
            $plan_dev_cost = round($record['new_plan_dev_cost'],2);
            $open_bal = round($record['op2_credit'],2);
            $fr_val = round($record['fr_release_credit'],2);
            $csbal = round($record['consume_credit'],2);
            $bal = round($open_bal+$fr_val-$csbal,2);
            $tobebilledfirst = round($first_bill_with_gst-$record['firstbilled'],2);
            $tobebilled = round($record['subs_val']-$record['subbilled'],2);
            $total_without_tax = 0;
            if($bal<0)
            {    $total_without_tax=round($bal,2);     }
            $total = round($total_without_tax*1.18,2);
            //$exp = $total+$cl;
            $exp = $total;
            //$exp = $cl-$led_exp; 
            if($exp<0)
            {
                $exp = -1*$exp;
            }


            if($tobebilled<0)
            {
                $tobebilled = 0;
            }
            $tobebilled_without_tax = round(($tobebilled/118)*100,2);
            $exposure = $exp+$cl+$tobebilled+$tobebilledfirst;

            $data2[$status][$exposure][$client] =  $record;
        }
    
        
        $data4 = array();
        
        //sort data on bases of exposure and client
        foreach($data2 as $status=>$exposure)
        {
            
            $data3 = array();
            foreach($exposure as $exp=>$records)
            {
                ksort($records);
                $data3[$exp] = $records; 
            }
            ksort($data3);
            $data4[$status] = $data3;
            
        }
    //print_r($data4);exit;
    
    
    
    
}

if($clientId!='All')
{
    $where = " and  company_id='$clientId'";
}
?>


<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<div class="loader">
<?php
$html='<table border="2">';
$html .="<tr>";
$html .="<th colspan=\"2\">{$_REQUEST['ToDate']} - {$_REQUEST['FromDate']}</th>";
$html .="<th colspan=\"30\"></th>";
$html .="</tr>";   
$html .="<tr>";
   $html .="<th colspan=\"2\"></th>";
   $html .="<th colspan=\"5\">Plan Details</th>";
   $html .="<th colspan=\"9\">Unit Consumption Details</th>";
   $html .="<th colspan=\"9\">Rate Details</th>";
   $html .="<th colspan=\"7\"></th>";
$html .="</tr>";   

   $html .="<tr>";
   $html .="<th>Client</th>";
   $html .="<th>Opening Ledger Balance</th>";
   $html .="<th>Plan Term:</th>";
   $html .="<th>Setup Fees</th>";
   $html .="<th>Subscription Value</th>";
   $html .="<th>Development Fee</th>";
   
   $html .="<th>Adhoc Plan</th>";
   $html .="<th>Inbound</th>";
   $html .="<th>Outbound</th>";
   
   $html .="<th>Night Shift</th>";
   $html .="<th>SMS</th>";
   $html .="<th>Email</th>";
   
   $html .="<th>Call Forwarding</th>";
   $html .="<th>IVR Automation</th>";
   $html .="<th>Missed Call</th>";
   $html .="<th>Additional / Other</th>";
   
   $html .="<th>Inbound</th>";
   $html .="<th>Outbound</th>";
   $html .="<th>Night Shift</th>";
   $html .="<th>SMS</th>";
   $html .="<th>Email</th>";
   $html .="<th>Call Forwarding</th>";
   $html .="<th>IVR Automation</th>";
   $html .="<th>Missed Call</th>";
   $html .="<th>Additional / Other</th>";
   
   $html .="<th>Total Revenue</th>";
   $html .="<th>Total Revenue Accrued</th>";
   $html .="<th>Dialdee Revenue</th>";
   $html .="<th>Total Revenue</th>";
   $html .="<th>CAAS Revenue</th>";
   $html .="<th>SAAS Revenue</th>";
   $html .="<th>Category</th>";
   
   $html .="</tr>";
   $data_total = array();


    $data = array();
    $from_date = "";
    $to_date = "";
    $company_qry = "";
    if(!empty($_REQUEST['FromDate']))
    {
        $req_from = $_REQUEST['FromDate'];
        $explode_date = explode('-',$_REQUEST['FromDate']);
        $req_to = $_REQUEST['ToDate'];
        if(in_array($req_to,array('Jan','Feb','Mar')))
        {
            $finYear = $explode_date[0]+1;
        }
        else
        {
            $finYear = $explode_date[0];
        }
        $req_from = "$finYear-$req_to-01";
        
        $from_date1 = date('d-m-Y',strtotime($req_from));
        $to_date1 = date('t-m-Y',strtotime($req_from));
    }
    else
    {
        $from_date1 = date('01-m-Y'); 
        $to_date1 = date('d-m-Y');          
        exit;
    }
    
    $explode_date = explode("-",$from_date1);
    //print_r($explode_date);
    $explode_date = array_reverse($explode_date);
    //print_r($explode_date);exit;
    $from_date = implode("-",$explode_date); 
        
    $explode_date1 = explode("-",$to_date1);
    $explode_date1 = array_reverse($explode_date1);
    $to_date = implode("-",$explode_date1);
    
    
    $company_id = $_REQUEST['ClientId'];  
    
    
    
    if(!empty($company_id) && $company_id!='All')
    {               
        $company_qry = " and company_id='$company_id'";        
    }
    
    
    $day_before = date( 'Y-m-d', strtotime( $from_date . ' -1 day' ) );
    
    $year = date('Y');
    $year_2 = date('y');
    
    
    //$selectedMonth='Feb';
    $month_arr =array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,
        'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,
        'Oct'=>10,'Nov'=>11,'Dec'=>12) ;
    $month_arr_swap =array_flip($month_arr) ;
    $month_no = 0;
    
    
    $Nmonth =$month = date( 'M', strtotime($from_date) );
    $month_no = $month_arr[$Nmonth];
     $today_date = $day_before;
    //$today_date = date('Y-03-01');

    $dateInfo = date_parse_from_format('Y-M-d',"$year-$Nmonth-01");
        $unixTimestamp = mktime(
        $dateInfo['hour'], $dateInfo['minute'], $dateInfo['second'],
        $dateInfo['month'], $dateInfo['day'], $dateInfo['year'],
        $dateInfo['is_dst']
        );
        
    $month_start_date = date('Y-m-d',$unixTimestamp); 
    $month_end_date = date("Y-m-t",$unixTimestamp); 

    $client_qry = "SELECT * FROM registration_master rm WHERE  `status` in ('A','H') and is_dd_client='1'  $company_qry  ORDER BY company_name";
       //echo $client_qry;exit;
    $client_det_arr = mysql_query($client_qry,$dd);
        
        
        
        while($clr = mysql_fetch_assoc($client_det_arr))
        {
            $clientId = $clr['company_id'];
            
            $clientName = $clr['company_name'];
            $data[$clientName]['clientId'] = $clientId;
            $data[$clientName]['status'] = get_client_status($clr['status']);
            
            $first_bill = $data[$clientName]['first_bill'] =  $clr['first_bill'];
            $data[$clientName]['clientId'] = $clientId;
            $data[$clientName]['client_category'] = $clr['client_category'];
            $sel_balance_qry = "SELECT * FROM `balance_master` bm WHERE clientId='$clientId' limit 1";
            $bal_det= mysql_fetch_assoc(mysql_query($sel_balance_qry,$dd) );
            $start_date = $bal_det['start_date'];
            $data[$clientName]['plan_status'] = get_plan_status($start_date);
            
            
            $op2_ledger = $clr['op'];
            $bill2_ledger = 0;
            $coll2_ledger = 0;
            
            $op2_credit = 0;
            $fr_release_credit = 0;
            $consume_credit = 0;
            $credit_closing = 0;
            $op2_consume_credit_testing = 0;
            //echo "SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$clientId'";exit;
            $op_det_ledger = get_op_det($clientId,$dd);
            if(!empty($op_det_ledger))
            {
                $talktime = $op_det_ledger['talk_time'];
                $op2_credit += $talktime;
                $credit_closing +=$talktime;
                $data[$clientName]['ledger_access_usage'] = 0;
                $data[$clientName]['talk_time'] = round($op_det_ledger['talk_time'],2);

                $data[$clientName]['ledger_sub'] = round($op_det_ledger['subs'],2);
                $data[$clientName]['ledger_topup'] = round($op_det_ledger['topup'],2);
                $data[$clientName]['ledger_setup'] = round($op_det_ledger['setup_cost'],2);
                $data[$clientName]['firstbilled'] += round($op_det_ledger['firstbilled'],2);
                
            }
            
            $planId = $bal_det['PlanId'];
            $plan_det_qry = "SELECT * FROM `plan_master` pm WHERE id='$planId' limit 1"; 
            $plan_det = mysql_fetch_assoc(mysql_query($plan_det_qry,$dd));
            
            //$FreeValue = $plan_det['0']['pm']['CreditValue'];
            $PeriodType = $plan_det['PeriodType'];
            $RentalAmount = $plan_det['RentalAmount'];
            $FreeValue = round($plan_det['Balance']/12);
            $data[$clientName]['RentalAmount'] = $plan_det['RentalAmount'];
            $data[$clientName]['Balance'] = $plan_det['Balance'];
            $data[$clientName]['PeriodType'] = $plan_det['PeriodType']=='Quater'?'Quarter':$plan_det['PeriodType'];
            
            $data[$clientName]['SetupFees'] = $plan_det['SetupCost'];
            $data[$clientName]['DevelopmentCost'] = $plan_det['DevelopmentCost'];
            $data[$clientName]['SubscriptionValue'] = get_subs_value($PeriodType,$RentalAmount); 
            
            $data[$clientName]['start_date'] = $start_date;
            //echo $clr['deactive_date'];exit;
            if(!empty($start_date) && (strtotime($start_date)<strtotime($to_date)) && (empty($clr['deactive_date']) || $clr['deactive_date']=='0000-00-00' || (strtotime($clr['deactive_date'])>strtotime($from_date)) ) )
            {    
                $data[$clientName]['monthly_fr_value'] = get_monthly_credit($plan_det['RentalAmount']); 
            }
            $data[$clientName]['InboundCallCharge'] = $plan_det['rate_per_pulse_day_shift'];
            $data[$clientName]['OutboundCallCharge'] = $plan_det['rate_per_pulse_outbound_call_shift'];
            $data[$clientName]['InboundCallChargeNight'] = $plan_det['rate_per_pulse_night_shift'];
            $data[$clientName]['MissCallCharge'] = $plan_det['MissCallCharge'];
            $data[$clientName]['VFOCallCharge'] = $plan_det['VFOCallCharge'];
            $data[$clientName]['SMSCharge'] = $plan_det['SMSCharge'];
            $data[$clientName]['EmailCharge'] = $plan_det['EmailCharge'];
            $data[$clientName]['IVR_Charge'] = $plan_det['IVR_Charge'];
            
            
            
            $SetupCost = $plan_det['SetupCost'];
            $developmentCost = $plan_det['DevelopmentCost'];
            
            $end_date = $to_date;
            $RentalAmount = $plan_det['RentalAmount'];
            
            $Balance = $plan_det['Balance'];
            $data[$clientName]['free_value'] =round($FreeValue,2);
            $rental_credit = $plan_det['RentalAmount'];
            $balance_credit = $plan_det['Balance'];
            
            $no_of_days = get_subs_days($PeriodType);
            $pamnt = get_subs_value($PeriodType,$RentalAmount);
                
            $cost_cat_arr = get_cost_center($clientId,$ispark);
            $total_subs = 0;
            #print_r($cost_cat_arr);exit;
            #$data[$clientName]['advance'] = $this->get_advance($cost_cat_arr);
                    
            $is_first_bill_made =   $clr['first_bill'];          
            if(empty($is_first_bill_made))
            {
                $data[$clientName]['new_plan_sub_cost'] = $pamnt;
                $data[$clientName]['new_plan_setup_cost'] = $SetupCost;
                $data[$clientName]['new_plan_dev_cost'] = $developmentCost;
                $data[$clientName]['first_plan_value'] = ($SetupCost+$developmentCost);
                $data[$clientName]['first_plan_value_with_gst'] = round(($SetupCost+$developmentCost)*1.18,2);
                $data[$clientName]['sub_start_date'] = $start_date;
                $data[$clientName]['sub_end_date'] = date('Y-m-d',strtotime($start_date." +$no_of_days days"));;  
                $data[$clientName]['due_date'] = 'Immediate';  
            }     
                
            
            foreach($cost_cat_arr as $cost)
            {
                $cost_center = $cost['cost_center'];  
                $data[$clientName]['cost_center'] = $cost_center;
                $data[$clientName]['month'] = $month;
                $bill_company = $cost['company_name'];
                $cost_id = $cost['id'];  
                
                //getting opening as on from date
                if(strtotime($from_date)>strtotime('2022-04-01'))
                {
                    $sel_billed_fromdateqry = "select ti.* from tbl_invoice ti where  ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (ti.invoiceDate >= '2022-04-01' AND ti.invoiceDate <= '$day_before')  ";  #and bill_no!=''  
                    $billed_fromdateqry_arr = mysql_query($sel_billed_fromdateqry,$ispark);
                    #print_r($billed_fromdateqry_arr);exit;
                    $subs_validity = array();
                    while($inv_det=mysql_fetch_assoc($billed_fromdateqry_arr) )
                    {
                        $bill_no = $inv_det['bill_no'];
                        $bill_finyear = $inv_det['finance_year'];
                        $bill_company = $cost['company_name'];
                        $op2_ledger +=$inv_det['grnd'];
                        $initial_id = $inv_det['id'];
                        $inv_date = $inv_det['invoiceDate'];
                        $carry_forward = $inv_det['carry_forward'];
                        //$bill_branch = $cost['cm']['branch'];     
                        $sub_days =$no_of_days*2;
                        $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 
                        //echo $op2_credit;exit;
                        if(strtolower($inv_det['category'])==strtolower('first_bill'))
                        {
                                //check whether first bill have subscritpion amount
                            $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                            $rsc_subs_arr = mysql_query($select_subs,$ispark);

                            while($sb = mysql_fetch_assoc($rsc_subs_arr))
                            {
                                $op2_value =get_credit_from_subs_value($rental_credit,$balance_credit,$sb['amount']);
                                $op2_credit +=$op2_value;
                                
                                if($carry_forward)
                                {
                                    $value_in_subs_validity = $subs_validity["$inv_date#$to"];
                                    $subs_validity["$inv_date#$to"] = $value_in_subs_validity+$op2_value;    
                                }
                                else
                                {
                                    $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                                    $subs_validity["$inv_date#unlimited"] = $value_in_subs_validity+$op2_value;
                                }
                                $total_subs +=1;
                            }
                        }
                        else if(strtolower($inv_det['category'])==strtolower('subscription'))
                        {
                                $total_subs +=1;
                                $op2_value =get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['total']);
                                $op2_credit +=$op2_value;
                                if($carry_forward)
                                {
                                    $value_in_subs_validity = $subs_validity["$inv_date#$to"];
                                    $subs_validity["$inv_date#$to"] = $value_in_subs_validity+$op2_value;

                                }
                                else
                                {
                                    $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                                    $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$op2_value;
                                }
                        }
                        else if(strtolower($inv_det['category'])==strtolower('Talk Time') || strtolower($inv_det['category'])==strtolower('Topup'))
                        {
                            
                            $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                            $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$inv_det['total'];
                            $op2_credit +=$inv_det['total'];
                        }


                    }
                    $op2_ledger = get_advance($ispark,$cost_id,'2022-04-01',$day_before,$op2_ledger,'old');
                    $op2_ledger = get_collection($ispark,$cost_center,'2022-04-01',$day_before,$bill_company,$op2_ledger,'old');
                    
                    #print_r($subs_validity);exit;
                    
                    
                    //starts here for ledger
                    $date_capture_start = strtotime('2022-04-01');
                    while($date_capture_start<=strtotime($day_before))
                    {
                        $cap_date = date('Y-m-d',$date_capture_start);
                        $consume_credit2 = get_consumption2($dd,$clientId,$cap_date,$consume_credit,'new');
                        //echo $consume_credit2;exit;
                        $op2_credit -=$consume_credit2;
                        $op2_consume_credit_testing += $consume_credit2; 
                        $credit_closing -=$consume_credit2;  
                        
                        foreach($subs_validity as $from_to=>$value)
                        {

                            $exp = explode('#',$from_to);
                            $from = $exp[0];
                            $to = $exp[1]; 
                            $flag_break = true;

                            if(strtotime($cap_date)>=strtotime($from))
                            {
                                #echo "fromto#$from_to#fr_rel#$value#capdate#$cap_date#consume#$credit_closing"; exit;
                                if($to=='unlimited' || strtotime($cap_date)<=strtotime($to))
                                {
                                      $value +=$credit_closing;  
                                     if($value>=0)
                                     {
                                         $subs_validity[$from_to] = $value;
                                         $credit_closing = 0;
                                     }
                                     else
                                     {
                                         unset($subs_validity[$from_to]);
                                         $credit_closing = $value; 
                                         $flag_break = false;
                                     }
                                }
                            }

                            if($flag_break)
                            {
                                break;
                            }

                        }  
                        
                        $date_capture_start =  strtotime($cap_date.' +1 days');
                    }
                    #print_r($subs_validity);exit;
                    foreach($subs_validity as $from_to=>$value)
                    {

                        $exp = explode('#',$from_to);
                        $from = $exp[0];
                        $to = $exp[1]; 

                        if($to!='unlimited' && strtotime($day_before)>=strtotime($to))
                        {
                            continue;
                        }
                        else
                        {
                            $credit_closing += $value;
                        }
                    }
                    
                    #$op2_credit = $credit_closing;
                }
                
                
                
                //getting opening as on FromDate to ToDate
                //starts here for ledger
                $sel_billed_todateqry = "select ti.* from tbl_invoice ti where ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (  ti.invoiceDate >= '$from_date' AND ti.invoiceDate <= '$to_date')  "; #and bill_no!=''
                $billed_todateqry_arr = mysql_query($sel_billed_todateqry,$ispark);
                #print_r($billed_todateqry_arr);exit;
                $subs_validity = array();
                while($inv_det = mysql_fetch_assoc($billed_todateqry_arr) )
                {
                    $bill_no = $inv_det['bill_no'];
                    $bill_finyear = $inv_det['finance_year'];
                    $bill_company = $cost['company_name'];
                    $bill2_ledger +=$inv_det['grnd'];
                    $initial_id = $inv_det['id'];
                    $inv_date = $inv_det['invoiceDate'];
                    $carry_forward = $inv_det['carry_forward'];
                    //$bill_branch = $cost['cm']['branch'];     
                    $sub_days =$no_of_days*2;
                    $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 
                    
                    if(strtolower($inv_det['category'])==strtolower('first_bill'))
                    {
                            //check whether first bill have subscritpion amount
                        $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                        $rsc_subs_arr = mysql_query($select_subs,$ispark);
                        
                        while($sb = mysql_fetch_assoc($rsc_subs_arr))
                        {
                            $total_subs +=1;
                            $fr_release_credit +=get_credit_from_subs_value($rental_credit,$balance_credit,$sb['amount']);
                            if($carry_forward)
                            {
                                $value_in_subs_validity = $subs_validity["$inv_date#$to"];
                                $subs_validity["$inv_date#$to"] = $value_in_subs_validity+get_credit_from_subs_value($rental_credit,$balance_credit,$sb['amount']);    
                            }
                            else
                            {
                                $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                                $subs_validity["$inv_date#unlimited"] = $value_in_subs_validity+get_credit_from_subs_value($rental_credit,$balance_credit,$sb['amount']);
                            }
                        }
                    }
                    else if(strtolower($inv_det['category'])==strtolower('subscription'))
                    {
                            $total_subs +=1;
                            $fr_release_credit += get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['total']);
                            if($carry_forward)
                            {
                                $value_in_subs_validity = $subs_validity["$inv_date#$to"];
                                $subs_validity["$inv_date#$to"] = $value_in_subs_validity+get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['total']);
                                
                            }
                            else
                            {
                                $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                                $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['total']);
                            }
                    }
                    else if(strtolower($inv_det['category'])==strtolower('Talk Time') || strtolower($inv_det['category'])==strtolower('Topup'))
                    {
                        $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                        $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$inv_det['total'];
                        $fr_release_credit +=$inv_det['total'];
                    }
                    
                    
                }
                
                $coll2_ledger = get_advance($ispark,$cost_id,$from_date,$to_date,$coll2_ledger,'new');
                $coll2_ledger = get_collection($ispark,$cost_center,$from_date,$to_date,$bill_company,$coll2_ledger,'new');
                #print_r($subs_validity);exit;
                #$credit_closing = $op2_credit+$fr_release_credit-$consume_credit;
                
                $date_capture_start = strtotime($from_date);
                
                while($date_capture_start<=strtotime($to_date))
                {
                    $cap_date = date('Y-m-d',$date_capture_start);
                    $consume_credit2 = get_consumption2($dd,$clientId,$cap_date,$consume_credit,'new');
                    $consume_credit += $consume_credit2;
                    $op2_consume_credit_testing += $consume_credit2;
                    $credit_closing -=$consume_credit2; 
                    #echo "$cap_date#$credit_closing#$consume_credit2".'<br/>';
                    //for getting actual closing.
                    #print_r($subs_validity);
                    foreach($subs_validity as $from_to=>$value)
                    {
                        
                        $exp = explode('#',$from_to);
                        $from = $exp[0];
                        $to = $exp[1]; 
                        $flag_break = true;
        
                        if(strtotime($cap_date)>=strtotime($from))
                        {
                            //echo "fromto#$from_to#fr_rel#$value#capdate#$cap_date#consume#$credit_closing"; exit;
                            if($to=='unlimited' || strtotime($cap_date)<=strtotime($to))
                            {
                                  $value +=$credit_closing;  
                                 if($value>=0)
                                 {
                                     $subs_validity[$from_to] = $value;
                                     $credit_closing = 0;
                                 }
                                 else
                                 {
                                     unset($subs_validity[$from_to]);
                                     $credit_closing = $value; 
                                     $flag_break = false;
                                 }
                            }
                        }
        
                        if($flag_break)
                        {
                            break;
                        }
        
                    }   
                    
                    $date_capture_start =  strtotime($cap_date.' +1 days');
                }
                
                #print_r($subs_validity);exit;
                foreach($subs_validity as $from_to=>$value)
                {
                    
                    $exp = explode('#',$from_to);
                    $from = $exp[0];
                    $to = $exp[1]; 
                    
                    if($to!='unlimited' && strtotime($from_date)>=strtotime($to))
                    {
                        continue;
                    }
                    else
                    {
                        $credit_closing += $value;
                    }
                }
                #print_r($credit_closing);exit;
                #echo $credit_closing;exit;
                $data[$clientName]['op2_ledger'] = $op2_ledger;
                $data[$clientName]['bill2_ledger'] = $bill2_ledger;
                $data[$clientName]['coll2_ledger'] = $coll2_ledger;
                $data[$clientName]['op2_credit'] = $op2_credit;
                $data[$clientName]['fr_release_credit'] = $fr_release_credit;
                $data[$clientName]['consume_credit'] = $consume_credit;
                $data[$clientName]['consume_credit_testing'] = $op2_consume_credit_testing;
                $data[$clientName]['closing_credit'] = $credit_closing;
                
                
                //for this month charges only #and category='subscription'
                $sel_subs_qry = "select * from tbl_invoice ti where cost_center='$cost_center'  AND ti.invoiceDate BETWEEN '$from_date' AND '$to_date' and bill_no!=''";
                $bill_subs_arr = mysql_query($sel_subs_qry,$ispark);
                $subs_coll = 0; $subs_coll2 = 0;
                while($inv = mysql_fetch_assoc($bill_subs_arr) )
                {
                    $initial_id = $inv['id'];
                    if(strtolower($inv['category'])==strtolower('first_bill'))
                    {
                        $select_subs = "select * from inv_particulars ip where initial_id='$initial_id' ";
                        $rsc_subs_arr = mysql_query($select_subs,$ispark);

                        while($sb = mysql_fetch_assoc($rsc_subs_arr))
                        {
                            if(strtolower($sb['sub_category'])==strtolower('Subscription'))
                            {$data[$clientName]['subbilled'] += round($sb['amount']);}
                            else if(strtolower($sb['sub_category'])==strtolower('SetupCost') || strtolower($sb['sub_category'])==strtolower('Setup Cost'))
                            {$data[$clientName]['subsetup'] += round($sb['amount']);}
                            else if(strtolower($sb['sub_category'])==strtolower('Development Cost') || strtolower($sb['sub_category'])==strtolower('DevelopmentCost'))
                            {$data[$clientName]['subdevel'] += round($sb['amount']);}
                        }
                    }
                    else if(strtolower($inv['category'])==strtolower('Setup Cost') || strtolower($inv['category'])==strtolower('SetupCost'))
                    {
                        $data[$clientName]['subsetup'] += round($inv['total']);
                    }
                    else if(strtolower($inv['category'])==strtolower('Subscription'))
                    {
                        $data[$clientName]['subbilled'] += round($inv['total']);
                    }
                    else if(strtolower($inv['category'])==strtolower('Development Cost') || strtolower($inv['category'])==strtolower('DevelopmentCost'))
                    {
                        $data[$clientName]['subdevel'] += round($inv['total']);
                    }
                    else if(strtolower($inv['category'])==strtolower('Talk Time') || strtolower($inv['category'])==strtolower('TalkTime'))
                    {
                        $data[$clientName]['subtalk'] += round($inv['total']);
                    }
                    else if(strtolower($inv['category'])==strtolower('Top up') || strtolower($inv['category'])==strtolower('Topup'))
                    {
                        $data[$clientName]['subtalk'] += round($inv['total']);
                    }
                }
                
                
                
                $monthlyFreeValue = 0;

                $start_date1 = strtotime($start_date);
                if($start_date1<strtotime('2022-01-01'))
                {
                   $start_date1 = strtotime(date('2022-01-d',$start_date1));
                }
                //exit;
                $datediff = strtotime($end_date) - $start_date1;
                //echo date('Y-m-d',$start_date1);exit;
                $noofday = round($datediff / (60 * 60 * 24));
                $subs_penP =0;
                $NewRentalAmount =0;
                $no_of_month = 0;
                $no_of_days = 0;
                $cur_date = $to_date;
                $start_date_activation = $start_date; 
                if(strtotime($start_date_activation)<strtotime('2022-04-01'))
                {
                    $start_date_activation = '2022-04-01';
                }
                //echo "$cur_date-$start_date_activation";exit;
                $datediff2 = strtotime($cur_date) - strtotime($start_date_activation);
                $str_to_days = 60 * 60 * 24;
                $datediff2 = $datediff2/$str_to_days; 
                $total_sub_req = 0;
                if(strtolower($PeriodType)==strtolower('MONTH'))
                {
                    $subs_penP = ceil($noofday/30);
                    $NewRentalAmount = $RentalAmount/12;
                    $monthlyFreeValue = $Balance;
                    $no_of_month = 1;
                    $no_of_days = 30;
                    $total_sub_req = ceil($datediff2/30.42);
                }
                else if(strtolower($PeriodType)==strtolower('Quater'))
                {
                    $subs_penP = ceil($noofday/90);
                    $NewRentalAmount = $RentalAmount/4;
                    $monthlyFreeValue = round($Balance/3,2);
                    $no_of_month = 3;
                    $no_of_days =90;
                    $total_sub_req = ceil($datediff2/91.25);
                }
                else if(strtolower($PeriodType)==strtolower('Half'))
                {
                    $subs_penP = ceil($noofday/180);
                    $NewRentalAmount = $RentalAmount/2;
                    $monthlyFreeValue = round($Balance/6,2);
                    $no_of_month = 6;
                    $no_of_days =180;
                    $total_sub_req = ceil($datediff2/182.5);
                }
                else if(strtolower($PeriodType)==strtolower('YEAR'))
                {
                    $subs_penP = round($noofday/365);
                    $NewRentalAmount = $RentalAmount/1;
                    $monthlyFreeValue = round($Balance/12,2);
                    $no_of_month = 12;
                    $no_of_days = 365;
                    $total_sub_req = ceil($datediff2/365);
                }
                if($subs_penP>=1)
                {
                   $subs_penP = 1; 
                }

                $sub_amt =round($NewRentalAmount*$subs_penP*1.18,2);
                //$subs_val = round($sub_amt-$subs_coll,2);
                $subs_val = round($sub_amt,2);
                //echo "$total_subs-$total_sub_req";exit;
                $total_subs =$total_subs-$total_sub_req;
                $data[$clientName]['subs_done'] = $total_subs;

                if($data[$clientName]['status']=='Active' && $subs_val>0 && strtotime($today_date)>=strtotime($start_date))
                {
                     $last_month = $start_date;
                     $flag = true;
                    while(strtotime($last_month)<=strtotime($today_date))
                    {
                        $last_month = date('Y-m-d',strtotime($last_month." +$no_of_days days"));  
                        if(strtotime($last_month)>strtotime(date('Y-m-01')) && strtotime($last_month)<=strtotime($today_date))
                        {
                            $data[$clientName]['subs_val'] = $subs_val; 
                            $data[$clientName]['due_date'] = 'Immediate';
                            $data[$clientName]['sub_start_date'] = $last_month; 
                            $data[$clientName]['sub_end_date'] =  date('Y-m-d',strtotime($last_month." +$no_of_days days"));
                            $flag = false;
                            break;
                        }
                        //echo '<br/>';
                    }
                    //exit;
                    $due_date = date('Y-m-d',strtotime($last_month.' -30 days')); 
                    //echo "$due_date $last_month" ; exit;

                    if($flag && strtotime($today_date)>= strtotime($due_date) )
                    {
                        $data[$clientName]['subs_val'] = $subs_val; 
                        $data[$clientName]['due_date'] = date('d-m-Y',strtotime($last_month." -1 days"));
                        $data[$clientName]['sub_start_date'] = $last_month; 
                        $data[$clientName]['sub_end_date'] =  date('Y-m-d',strtotime($last_month." +$no_of_days days"));
                    }

                }
            }
            
            
                
        }


        #print_r($data);exit;
        
        foreach($data as $clientName=>$record){
            $clientId = $record['clientId'];
            $op =($record['op2_ledger']);
            $start_date = $record['start_date'];
            
            
            $html.="<tr>";
            $html.=get_td($clientName);
            $html.=get_td(number_format($op,2)); $data_total['op2_ledger'] += round($record['op2_ledger'],2);
            $html.=get_td($record['PeriodType']); 
            $html.=get_td(number_format($record['subsetup'],2)); $data_total['subsetup'] += round($record['subsetup'],2);
            $monthly_fr_value = 0;
            if($record['subs_done']>=0)
            {
                $html.=get_td(number_format($record['monthly_fr_value'],2)); $data_total['subbilled'] += round($record['monthly_fr_value'],2);
                $monthly_fr_value = round($record['monthly_fr_value'],2);
            }
            else
            {
              $html.=get_td('');  
            }
            $html.=get_td(number_format($record['subdevel'],2)); $data_total['subdevel'] += round($record['subdevel'],2);
            $html.=get_td(number_format($record['Adhoc'],2)); $data_total['Adhoc'] += round($record['Adhoc'],2);
            
            
            $sum_kn = round($record['subsetup'],2)+$monthly_fr_value+round($record['subdevel'],2)+round($record['Adhoc'],2);
            
            
            
            $select = "SELECT rm.company_name,rm.status,cm.client_id,SUM(cm_total) cm_total,
SUM(ib_pulse) ib_pulse,ib_charge,SUM(ib_total) ib_total,
SUM(ibn_pulse) ibn_pulse,ibn_charge,SUM(ibn_total) ibn_total, 
SUM(ob_pulse) ob_pulse,ob_charge,SUM(ob_total) ob_total,
SUM(sms_pulse) sms_pulse,sms_charge,SUM(sms_total) sms_total,
SUM(email_pulse) email_pulse,email_charge,SUM(email_total) email_total,
SUM(ivr_pulse) ivr_pulse,ivr_charge,SUM(ivr_total) ivr_total,
SUM(miss_pulse) miss_pulse,miss_charge,SUM(miss_total) miss_total
FROM `billing_consume_daily` cm 
INNER JOIN registration_master rm ON 
cm.client_id= rm.company_id
WHERE cm.client_id='$clientId' and DATE(cm.cm_date)>='$start_date' and  DATE(cm.cm_date) BETWEEN '$from_date' AND '$to_date'
"; 
    
    
            $rsc_qry = mysql_query($select,$dd); 
            
            $revenue_cm = 0;
            $total_caas = 0;
            
            while($record2=mysql_fetch_assoc($rsc_qry))
            {
                $html.=get_td(number_format($record2['ib_pulse'],2));     $data_total['ib_pulse'] += round($record2['ib_pulse'],2);
                $html.=get_td(number_format($record2['ob_pulse'],2));     $data_total['ob_pulse'] += round($record2['ob_pulse'],2);
                $html.=get_td(number_format($record2['ibn_pulse'],2));    $data_total['ibn_pulse'] += round($record2['ibn_pulse'],2);
                $html.=get_td(number_format($record2['sms_pulse'],2));    $data_total['sms_pulse'] += round($record2['sms_pulse'],2);
                $html.=get_td(number_format($record2['email_pulse'],2));  $data_total['email_pulse'] += round($record2['email_pulse'],2);
                $html.=get_td(number_format($record2['callforw_pulse'],2));  $data_total['callforw_pulse'] += round($record2['callforw_pulse'],2);
                $html.=get_td(number_format($record2['ivr_pulse'],2));  $data_total['ivr_pulse'] += round($record2['ivr_pulse'],2);
                $html.=get_td(number_format($record2['miss_pulse'],2));  $data_total['miss_pulse'] += round($record2['miss_pulse'],2);
                $html.=get_td(number_format($record2['other_pulse'],2));  $data_total['other_pulse'] += round($record2['other_pulse'],2);
                
                
                
                
                
                
                
               $total_caas =  (round($record2['ib_pulse'],2)*$record['InboundCallCharge'])+(round($record2['ibn_pulse'],2)*$record['InboundCallChargeNight'])+(round($record2['ob_pulse'],2)*$record['OutboundCallCharge']);
               $revenue_cm =  round($record2['cm_total'],2);
                
                
                
            }
            
            $html.=get_td(number_format($record['InboundCallCharge'],6));     
                $html.=get_td(number_format($record['OutboundCallCharge'],6));     
                $html.=get_td(number_format($record['InboundCallChargeNight'],6));    
                $html.=get_td(number_format($record['SMSCharge'],6));    
                $html.=get_td(number_format($record['EmailCharge'],6));  
                $html.=get_td(number_format($record['callforw_Charge'],6));  
                $html.=get_td(number_format($record['IVR_Charge'],2));  
                $html.=get_td(number_format($record['MissCallCharge'],2)); 
                $html.=get_td(number_format($record['other_Charge'],2));
            
            $revenue = 0;
            $html.=get_td(number_format($revenue_cm,2));     $data_total['revenue_cm'] += round($revenue_cm,2);
            
            
            
            $excess_usage = $record['subtalk'];
            $revenue_acrued = $monthly_fr_value-$revenue_cm+$excess_usage;
            
            if($revenue_cm>$sum_kn)
            {
                $revenue = $revenue_cm;
            }
            else
            {
                $revenue = $sum_kn;
            }
            
            
            
            $html.=get_td(number_format($revenue,2));     $data_total['accrued_revenue'] += round($revenue,2);
            
            $html.=get_td('');     
            $html.=get_td(number_format($revenue,2));     $data_total['total_revenue'] += round($revenue,2);
            
            $html.=get_td(number_format($total_caas,2));     $data_total['total_caas'] += round($total_caas,2);
            
            $revenue_saas = 0;
            if(($revenue-$total_caas)>0)
            {
                $revenue_saas = $revenue-$total_caas;
            }
            else
            {
                $revenue_saas = 0;
            }
            $html.=get_td(number_format($revenue_saas,2));     $data_total['total_saas'] += round($revenue_saas,2);
            
            $html.=get_td($record['client_category']);
        }















$html.="<tr>";
        $html.=get_th('Total');
        $html.=get_td(number_format($data_total['op2_ledger'],2));
        $html.=get_td('');
        $html.=get_td(number_format($data_total['subsetup'],2));  
        $html.=get_td(number_format($data_total['subbilled'],2));  
        $html.=get_td(number_format($data_total['subdevel'],2));
         $html.=get_td(number_format('0',2));
        
        $html.=get_td(number_format($data_total['ib_pulse'],2));    
        $html.=get_td(number_format($data_total['ob_pulse'],2));     
        $html.=get_td(number_format($data_total['ibn_pulse'],2));     
        $html.=get_td(number_format($data_total['sms_pulse'],2));            
        $html.=get_td(number_format($data_total['email_pulse'],2));    
        $html.=get_td(number_format($data_total['callforw_pulse'],2));     
        $html.=get_td(number_format($data_total['ivr_pulse'],2));     
        $html.=get_td(number_format($data_total['miss_pulse'],2));    
        $html.=get_td(number_format($data_total['other_pulse'],2));    
        $html.=get_td(number_format($data_total['other_pulse'],2));
        $html.=get_td(''); $html.=get_td(''); $html.=get_td('');
        $html.=get_td(''); $html.=get_td(''); $html.=get_td('');
        $html.=get_td(''); $html.=get_td(''); 
        $html.=get_td(number_format($data_total['revenue_cm'],2));  
        $html.=get_td(number_format($data_total['accrued_revenue'],2));
        $html.=get_td('');
        $html.=get_td(number_format($data_total['total_revenue'],2));
        $html.=get_td(number_format($data_total['total_caas'],2));
        $html.=get_td(number_format($data_total['total_saas'],2));
        $html.=get_td('');
    $html.="</tr>";
    
 $html.="</table>";   
    
    
    
    



$fileName = "revenue_".date('d_m_y_h_i_s');
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$fileName.".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo $html ;
?>
</div>
<?php
die;

