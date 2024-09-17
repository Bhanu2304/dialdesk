<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');


include_once('send_email.php');
$dd_host       =   "localhost"; 
$dd_user   =   "root";
$dd_pass   =   "dial@mas123";
$dd_name    =   "db_dialdesk";

//ispark connection
$ispark_host       =   "192.168.10.22"; 
$ispark_user   =   "root";
$ispark_pass   =   "321*#LDtr!?*ktasb";
$ispark_name    =   "db_bill";

$con = mysqli_connect("192.168.10.5","root","vicidialnow");
mysqli_select_db($con,"asterisk")or die("cannot select DB");

$dd = mysqli_connect("$dd_host", "$dd_user", "$dd_pass")or die("cannot connect"); 
mysqli_select_db($dd,"$dd_name")or die("cannot select DB");

$ispark = mysqli_connect("$ispark_host", "$ispark_user", "$ispark_pass")or die("cannot connect"); 
mysqli_select_db($ispark,"$ispark_name")or die("cannot select DB");

function get_credit_from_subs_value($rental,$balance,$subsvalue)
{
    //echo "round($balance/$rental,3)"; exit;
    $plan_pers = $balance/$rental; 
    //echo $plan_pers;exit;
     $creditvalue = round(($subsvalue*$plan_pers),2);
    //echo $creditvalue;exit
    return $creditvalue;
}

function get_closing_bal($clientId,$dd,$ispark)
{
    $from_date = date("Y-m-01");            
    $to_date = date("Y-m-d");
    
    
    $company_id = $clientId;
    $company_qry = " and company_id='$company_id'";        
    
   
    
    $day_before = date( 'Y-m-d', strtotime( $from_date . ' -1 day' ) );
    $finYear = '2022-23';
    $year = date('Y');
    $year_2 = date('y');
    $arr = explode('-',$finYear);
    
    
    $month_arr =array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,
        'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,
        'Oct'=>10,'Nov'=>11,'Dec'=>12) ;
    $month_arr_swap =array_flip($month_arr) ;
    $month_no = 0;
    
    
        $Nmonth =$month = date( 'M', strtotime($from_date) );
        $month_no = $month_arr[$Nmonth];
    
    
    $current_month = $Nmonth;
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

       //$month_start_date = '2022-03-11';
        //$month_end_date = date("Y-m-d");
        
        if(in_array($month,array('Jan','Feb','Mar')))
        {
            if($arr[0]==$year || $arr[1]==$year_2)
            {
                //$month=$month."-".date('y');
                $month=$month."-".$arr[1];
            }
            else
            {
                $month=$month."-".$arr[1]; 
            }
        }
        else
        {
            $month=$month."-".($arr[1]-1);
        }
        
        //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE company_id in ('278','301','389') and  `status` = 'A' and is_dd_client='1' order by company_name");
        //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE company_id='465' and  `status` = 'A' and is_dd_client='1'"); 
        
        // $client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE is_dd_client='1' and  `status` = 'A' order by company_name"); 
       $client_qry = "SELECT * FROM registration_master rm WHERE `status` != 'CL' and is_dd_client='1'  $company_qry $client_status_qry ORDER BY status";
       //echo $client_qry;exit;
        $client_det_arr = mysqli_query($dd,$client_qry);
        $clr = mysqli_fetch_assoc($client_det_arr);
        
        
        
            $clientId = $clr['company_id'];
            $clientName = $clr['company_name'];
            $data[$clientName]['phone_no'] = $clr['phone_no'];
            $data[$clientName]['clientEmail'] = $clr['email'];
            $createdate_for_client_to_first_bill = $clr['create_date'];
            
            if($clr['status']=='A')
            {
                $data[$clientName]['status'] = 'Active';
            }
            else if($clr['status']=='D')
            {
                $data[$clientName]['status'] = 'De-Active';
            }
            else if($clr['status']=='H')
            {
                $data[$clientName]['status'] = 'Hold';
            }
            else if($clr['status']=='CL')
            {
                $data[$clientName]['status'] = 'Close';
            }
            $first_bill = $data[$clientName]['first_bill'] =  $clr['first_bill'];
            $data[$clientName]['clientId'] = $clientId;
            $sel_start_date = "SELECT start_date FROM `balance_master` bm WHERE clientId='$clientId' limit 1";
            $rsc_start_date=mysqli_fetch_assoc(mysqli_query($dd,$sel_start_date));
            
            $record_start_date = $rsc_start_date['start_date'];
            /*if(strtotime($record_start_date)>strtotime($month_end_date))
            {
                continue;
            }*/
            
            if(empty($record_start_date))
            {
                $data[$clientName]['plan_status'] = 'Testing';
            }
            else
            {
                
                $data[$clientName]['plan_status'] = date('d-M-y',strtotime($record_start_date));
            }
            
            $op2_ledger = $clr['op'];
            $bill2_ledger = 0;
            $coll2_ledger = 0;
            
            $op2_credit = 0;
            $fr_release_credit = 0;
            $consume_credit = 0;
            $op2_consume_credit_testing = 0;
            //echo "SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$clientId'";exit;
            $op_det_ledger = mysqli_fetch_assoc(mysqli_query($dd,"SELECT * FROM `billing_ledger` bl WHERE fin_year='2022' and fin_month='Apr' and clientId = '$clientId'"));
            
            if(!empty($op_det_ledger))
            {
                $talktime = $op_det_ledger['talk_time'];
                $op2_credit += $talktime;
                
                if(1!=1)
                {
                    $talktime = abs($talktime*1.18);
                    $data[$clientName]['ledger_access_usage'] = round($talktime,2);
                    $data[$clientName]['talk_time'] = 0;
                }
                else
                {
                    $data[$clientName]['ledger_access_usage'] = 0;
                    $data[$clientName]['talk_time'] = round($op_det_ledger['talk_time'],2);
                }

                $data[$clientName]['ledger_sub'] = round($op_det_ledger['subs'],2);
                $data[$clientName]['ledger_topup'] = round($op_det_ledger['topup'],2);
                $data[$clientName]['ledger_setup'] = round($op_det_ledger['setup_cost'],2);
                $data[$clientName]['firstbilled'] += round($op_det_ledger['firstbilled'],2);
                
                
            }

            $billing_opening_balance = "billing_opening_balance";
            if($current_month!=$Nmonth)
            {
                $billing_opening_balance = "billing_opening_balance_history";
            }
            $op_det = mysqli_fetch_assoc(mysqli_query($dd,"SELECT op_bal,cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$clientId' and fin_year='$year' and fin_month='$Nmonth'"));
            //print_r($op_det);exit;
            if(!empty($op_det['op_dd']))
            {
                $data[$clientName]['op_dd'] = $op_det['op_dd'];
            }
            else
            {
                $op_det = mysqli_fetch_assoc(mysqli_query($dd,"SELECT op_bal,cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$clientId' and fin_year='$year' and fin_month='$Nmonth'"));
                if(!empty($op_det['op_dd']))
                {
                    $data[$clientName]['op_dd'] = $op_det['op_dd'];
                }
            }
            $data[$clientName]['cs_bal'] = $op_det['cs_bal'];
            //$data[$clientName]['fr_val'] = $op_det['0']['bob']['fr_val'];
            //$data[$clientName]['adv_val'] = $op_det['0']['bob']['adv_val'];

            $plan_get_qry = "SELECT PlanId,start_date FROM balance_master bm WHERE clientId='$clientId' limit 1"; 
            $bal_det = mysqli_fetch_assoc(mysqli_query($dd,$plan_get_qry));
            $planId = $bal_det['PlanId'];
            $plan_det_qry = "SELECT RentalAmount,PeriodType,Balance,CreditValue,SetupCost,DevelopmentCost FROM `plan_master` pm WHERE id='$planId' limit 1"; 
            $plan_det = mysqli_fetch_assoc(mysqli_query($dd,$plan_det_qry));
            //$FreeValue = $plan_det['0']['pm']['CreditValue'];
            $FreeValue = round($plan_det['Balance']/12);
            $data[$clientName]['RentalAmount'] = $plan_det['RentalAmount'];
            $data[$clientName]['Balance'] = $plan_det['Balance'];
            $SetupCost = $plan_det['SetupCost'];
            $developmentCost = $plan_det['DevelopmentCost'];
            $start_date = $bal_det['start_date']; 
            $end_date = $to_date;
            $RentalAmount = $plan_det['RentalAmount'];
            $PeriodType = $plan_det['PeriodType'];
            $Balance = $plan_det['Balance'];
            $data[$clientName]['free_value'] =round($FreeValue,2);
            $rental_credit = $plan_det['RentalAmount'];
            $balance_credit = $plan_det['Balance'];
            
            $no_of_month = 0;
                
            switch ($PeriodType) {
                case "YEAR":
                    $pamnt= $RentalAmount; 
                    $no_of_month = 365;
                    break;
                case "Half":
                    $pamnt= round($RentalAmount/2,2);
                    $no_of_month = 180;
                    break;
                case "Quater":
                    $pamnt= round($RentalAmount/4,2);
                    $no_of_month = 90; 
                    break;
                default:
                    $pamnt= round($RentalAmount/12,2);
                    $no_of_month = 30;
                }
                    
                    $cost_cat_rsc = mysqli_query($ispark,"select * from cost_master cm where dialdesk_client_id='$clientId' limit 1");
                    $cost_cat_arr = array();
                    while($cost_cat = mysqli_fetch_assoc($cost_cat_rsc))
                    {
                        $cost_cat_arr[] = $cost_cat;
                    }
                    $cost_adv_arr = mysqli_fetch_assoc(mysqli_query($ispark,"SELECT *,SUM(bill_passed) advance FROM `bill_pay_advance` bpa WHERE bill_no='{$cost_cat['id']}'"));
                    if(!empty($cost_adv_arr))
                    {
                        $data[$clientName]['advance'] = $cost_adv_arr['advance'];
                    }
                    
                        
               $is_first_bill_made = false; 
            if(strtotime($createdate_for_client_to_first_bill)>=strtotime("2022-04-01 00:00:00"))
            {
                
                $sel_first_plan_billed = "SELECT ti.* FROM tbl_invoice ti 
                INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center 
                WHERE ti.category in ('first_bill','Setup Cost','DevelopmentCost') and ti.bill_no!='' and ti.status='0' and cm.dialdesk_client_id='$clientId'";
                //echo $sel_first_plan_billed;exit
                $billed_first_plan = mysqli_query($ispark,$sel_first_plan_billed);
                while($first_bill = mysqli_fetch_assoc($billed_first_plan))
                {
                    $initial_id = $first_bill['id'];
                    $additional_cost = array('SetupCost','DevelopmentCost','Subscription');
                    $category = $first_bill['category'];
                    
                    //echo $pamnt;exit;
                    if($category=='first_bill')
                    {
                        $is_first_bill_made = true;
                        foreach($additional_cost as $scost)
                        {
                            $select_subs = "select ip.sub_category,ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='$scost'";
                            $rsc_subs_arr = mysqli_query($ispark,$select_subs);
                            //print_r($rsc_subs_arr);
                            while($sb=mysqli_fetch_assoc($rsc_subs_arr))
                            {   
                                if(strtolower($scost)==strtolower('SetupCost'))
                                {$SetupCost -=$sb['amount'];}
                                else if(strtolower($scost)==strtolower('DevelopmentCost'))
                                {$developmentCost -=$sb['amount'];}
                                else if(strtolower($scost)==strtolower('Subscription'))
                                {$pamnt -=$sb['amount'];}
                            }
                        }
                    }
                    else
                    {
                        if(strtolower($category)==strtolower('Setup Cost'))
                        { $SetupCost -=$first_bill['total']; }
                        else if(strtolower($category)==strtolower('Development Cost'))
                        {$developmentCost -=$first_bill['total'];}
                    }
                    
                    
                    
                }
                
                if(!$is_first_bill_made)
                {
                    $data[$clientName]['new_plan_sub_cost'] = $pamnt;
                    $data[$clientName]['new_plan_setup_cost'] = $SetupCost;
                    $data[$clientName]['new_plan_dev_cost'] = $developmentCost;
                    $data[$clientName]['first_plan_value'] = ($SetupCost+$developmentCost);
                    $data[$clientName]['first_plan_value_with_gst'] = round(($SetupCost+$developmentCost)*1.18,2);
                    $data[$clientName]['sub_start_date'] = $start_date;
                    $data[$clientName]['sub_end_date'] = date('Y-m-d',strtotime($start_date." +$no_of_month days"));;  
                    $data[$clientName]['due_date'] = 'Immediate';  
                }
                
                 
            }
            
                       
            
           //print_r($cost_cat_arr);exit;
            
            foreach($cost_cat_arr as $cost)
            {
                $cost_center = $cost['cost_center'];  
                $data[$clientName]['cost_center'] = $cost_center;
                $data[$clientName]['month'] = $month;
                $bill_company = $cost['company_name'];
                
                //getting opening as on from date
                if(strtotime($from_date)>=strtotime('2022-04-01'))
                {
                    $sel_billed_fromdateqry = "select ti.* from tbl_invoice ti where  ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (ti.invoiceDate >= '2022-04-01' AND ti.invoiceDate <= '$day_before')  "; #and bill_no!='' 
                    $billed_fromdateqry_arr = mysqli_query($ispark,$sel_billed_fromdateqry);
                    
                    while($inv_det = mysqli_fetch_assoc($billed_fromdateqry_arr))
                    {
                        $bill_no = $inv_det['bill_no'];
                        $initial_id = $inv_det['id'];
                        $bill_finyear = $inv_det['finance_year'];
                        
                        $op2_ledger +=$inv_det['grnd'];
                        $cost_id = $cost['id'];
                        $bill_branch = $cost['branch'];    
                        
                        if(strtolower($inv_det['category'])==strtolower('first_bill'))
                        {
                                //check whether first bill have subscritpion amount
                            $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                            $rsc_subs_arr = mysqli_query($ispark,$select_subs);
                            while($sb=mysqli_fetch_assoc($rsc_subs_arr) )
                            {
                                 $op2_credit +=get_credit_from_subs_value($rental_credit,$balance_credit,$sb['amount']);
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
                    //getting advance from this client
                        $cost_adv_arr = mysqli_fetch_assoc(mysqli_query($ispark,"SELECT *,SUM(bill_passed) advance FROM `bill_pay_advance` bpa WHERE bill_no='$cost_id' AND  date(bpa.pay_dates)  between '2022-04-01' and '$day_before'"));
                        if(!empty($cost_adv_arr))
                        {
                            $op2_ledger -= $cost_adv_arr['bill_passed'];
                        }
                    //getting actual outstanding as on from date.
                    $select_payment_fromdateqry = "SELECT sum(bpp.bill_passed) bill_passed FROM 
    bill_pay_particulars bpp inner join tbl_invoice ti on bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year and bpp.branch_name=ti.branch_name AND bpp.company_name='$bill_company' 
    where ti.cost_center='$cost_center' and date(bpp.pay_dates)  between '2022-04-01' and '$day_before' ";  
                        
                        $collection_fromdate_arr = mysqli_query($ispark,$select_payment_fromdateqry);
                        while($coll_det = mysqli_fetch_assoc($collection_fromdate_arr))
                        {    $op2_ledger -= $coll_det['bill_passed']; }
                       //getting consumption from table 
                        $select_consumption = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId' and date(cm_date)>='$start_date' and (cm_date >= '2022-04-01' AND cm_date <= '$day_before')";
                        $rsc_consuption_arr =  mysqli_query($dd,"$select_consumption");
                        //print_r($rsc_consuption_arr);exit;
                        //foreach($rsc_consuption_arr as $consume)
                        while($consume = mysqli_fetch_assoc($rsc_consuption_arr))
                        {
                            $op2_credit -= $consume['total'];
                        }
                        
                        ///////////////////////// testing consumption ////////////////////////
                        $select_consumption_testing = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId'  and (cm_date >= '2022-04-01' AND cm_date <= '$day_before')";
                        $rsc_consuption_testing_arr = mysqli_query($dd,"$select_consumption_testing");
                        //print_r($rsc_consuption_arr);exit;
                        //foreach($rsc_consuption_testing_arr as $consume)
                        while($consume = mysqli_fetch_assoc($rsc_consuption_testing_arr))
                        {
                            $op2_consume_credit_testing += $consume['total'];
                        }
                        ///////////////////////// end testing consumption ////////////////////////
                }
                
                //getting opening as on FromDate to ToDate
                $sel_billed_todateqry = "select ti.* from tbl_invoice ti where ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (  ti.invoiceDate >= '$from_date' AND ti.invoiceDate <= '$to_date')  "; #and bill_no!=''
                $billed_todateqry_arr = mysqli_query($ispark,$sel_billed_todateqry);

                #foreach($billed_todateqry_arr as $inv_det)
                while($inv_det = mysqli_fetch_assoc($billed_todateqry_arr))
                {
                    $bill_no = $inv_det['bill_no'];
                    $bill_finyear = $inv_det['finance_year'];
                    $bill_company = $cost['company_name'];
                    $bill2_ledger +=$inv_det['grnd'];
                    $initial_id = $inv_det['id'];
                    //$bill_branch = $cost['cm']['branch'];     
                    
                    if(strtolower($inv_det['category'])==strtolower('first_bill'))
                    {
                            //check whether first bill have subscritpion amount
                        $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                        $rsc_subs_arr = mysqli_query($ispark,$select_subs);
                        #foreach($rsc_subs_arr as $sb)
                        while($sb = mysqli_fetch_assoc($rsc_subs_arr))
                        {
                             $fr_release_credit +=get_credit_from_subs_value($rental_credit,$balance_credit,$sb['amount']);
                        }

                    }
                    else if(strtolower($inv_det['category'])==strtolower('subscription'))
                    {
                        $fr_release_credit +=get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['total']);
                    }
                    else if(strtolower($inv_det['category'])==strtolower('Talk Time') || strtolower($inv_det['category'])==strtolower('Topup'))
                    {
                        $fr_release_credit +=$inv_det['total'];
                    }
                    
                    
                }
                //getting advance from this client
                $cost_adv_arr = mysqli_fetch_assoc(mysqli_query($ispark,"SELECT *,SUM(bill_passed) advance FROM `bill_pay_advance` bpa WHERE bill_no='$cost_id' AND  date(bpa.pay_dates)  between '$to_date' and '$from_date'"));
                if(!empty($cost_adv_arr))
                {
                    $coll2_ledger += $cost_adv_arr['bill_passed'];
                }
                   //getting actual outstanding as on from date.  
               $select_payment_qry = "SELECT sum(bpp.bill_passed) bill_passed FROM 
    bill_pay_particulars bpp inner join tbl_invoice ti on bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year and bpp.branch_name=ti.branch_name AND bpp.company_name='$bill_company' 
    where ti.cost_center='$cost_center' and date(bpp.pay_dates)  between '$from_date' and '$to_date' ";    

                $collection_billingtodate_arr = mysqli_query($ispark,$select_payment_qry);
                while($coll_det = mysqli_fetch_assoc($collection_billingtodate_arr) )
                {    $coll2_ledger += $coll_det['bill_passed']; }
                       
                //getting file detail 
                
                
                  
                //getting consumption from table

                 $select_consumption = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId' and date(cm_date)>='$start_date' and DATE(cm_date) between '$from_date' and '$to_date'";
                 $rsc_consuption_arr = mysqli_query($dd,"$select_consumption");
                 //print_r($rsc_consuption_arr);exit;
                 #foreach($rsc_consuption_arr as $consume)
                 while($consume = mysqli_fetch_assoc($rsc_consuption_arr))    
                 {
                     $consume_credit += $consume['total'];
                 }
                 
                 ///////////////////////// testing consumption ////////////////////////
                        $select_consumption_testing = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId'  and DATE(cm_date) between '$from_date' and '$to_date'";
                        $rsc_consuption_testing_arr = mysqli_query($dd,"$select_consumption_testing");
                        //print_r($rsc_consuption_arr);exit;
                        #foreach($rsc_consuption_testing_arr as $consume)
                        while($consume = mysqli_fetch_assoc($rsc_consuption_testing_arr))    
                        {
                            $op2_consume_credit_testing += $consume['total'];
                        }
                        ///////////////////////// end testing consumption ////////////////////////
                
                $data[$clientName]['op2_ledger'] = $op2_ledger;
                $data[$clientName]['bill2_ledger'] = $bill2_ledger;
                $data[$clientName]['coll2_ledger'] = $coll2_ledger;
                $data[$clientName]['op2_credit'] = ($op2_credit);
                $data[$clientName]['fr_release_credit'] = ($fr_release_credit);
                $data[$clientName]['consume_credit'] = $consume_credit;
                $data[$clientName]['consume_credit_testing'] = $op2_consume_credit_testing;
                
                
                //echo "$op2_credit-$consume_credit";exit; 
                
                
                $sel_setup_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category in ('Setup Cost','Development Cost') AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date'  and bill_no!=''";
                $bill_set_arr = mysqli_query($ispark,$sel_setup_qry);
                #foreach($bill_set_arr as $inv)
                while($inv = mysqli_fetch_assoc($bill_set_arr))    
                {    $data[$clientName]['setupbilled'] += $inv['grnd'];    }    

                $selec_setup_payment_qry = "SELECT cm.client,ti.id,ti.bill_no,ti.total,ti.grnd,sum(bpp.bill_passed) bill_passed FROM 
     tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('Setup Cost','Development Cost')
    AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no "; 
                    $collection_setup_arr = mysqli_query($ispark,$selec_setup_payment_qry);
                    
                    
                    while($coll_det = mysqli_fetch_assoc($collection_setup_arr))    
                    {    $data[$clientName]['setupcoll'] += $coll_det['bill_passed']; }

                $sel_billing_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category in ('Talk Time','Topup') AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date'  and bill_no!=''";
                $bill_det_arr = mysqli_query($ispark,$sel_billing_qry);


                #foreach($bill_det_arr as $inv)
                 while($inv=mysqli_fetch_assoc($bill_det_arr))   
                {
                    $data[$clientName]['billed'] += $inv['grnd'];
                    $fin_year = $inv['finance_year'];
                    $company_name = $cost['company_name'];
                    $branch = $inv['branch_name'];
                    $bill_no = $inv['bill_no'];
                    //$net_amount = $inv['ti']['total'];
                }

                $selec_all_payment_qry = "SELECT cm.client,ti.id,ti.bill_no,ti.total,ti.grnd,sum(bpp.bill_passed) bill_passed FROM 
     tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('Talk Time','Topup')
    AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no "; 
                    $collection_det_arr = mysqli_query($ispark,$selec_all_payment_qry);

                    
                    while($coll_det=mysqli_fetch_assoc($collection_det_arr))    
                    {
                        //$data[$clientName]['coll'] += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                        $data[$clientName]['coll'] += $coll_det['bill_passed'];
                    }

                $sel_subs_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category='subscription' AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date' and bill_no!=''";
                $bill_subs_arr = mysqli_query($ispark,$sel_subs_qry);

                $subs_coll = 0; $subs_coll2 = 0;
                #foreach($bill_subs_arr as $inv)
                while($inv=mysqli_fetch_assoc($bill_subs_arr))     
                {
                    $fin_year = $inv['finance_year'];
                    $company_name = $cost['company_name'];
                    $branch = $inv['branch_name'];
                    $bill_no = $inv['bill_no'];
                    $data[$clientName]['subbilled'] += $inv['grnd'];
                    $sub_start_date = $inv['subs_start_date'];
                    if(!empty($sub_start_date))
                    {
                        $data[$clientName]['subbilled_as_sub_date'] += $inv['grnd'];
                    }
                    else if(strtotime($sub_start_date)<=strtotime(date('Y-m-d')))
                    {
                        $data[$clientName]['subbilled_as_sub_date'] += $inv['grnd'];
                    }
                   
                }

                $selec_all_payment_qry = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
     tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('subscription')
    AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no"; 
                    $collection_det_arr = mysqli_query($ispark,$selec_all_payment_qry);

                    
                    while($coll_det = mysqli_fetch_assoc($collection_det_arr))
                    {
                        //echo $month;exit;
                        $subs_coll +=$coll_det['bill_passed'];
                        
                    }

                    $selec_all_payment_qry_for_release = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
     tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('subscription')
    AND ti.month='$month'   GROUP BY ti.bill_no"; 
                    $collection_det_arr = mysqli_query($ispark,$selec_all_payment_qry_for_release);
                    $subs_coll3 =0;
                    while($coll_det = mysqli_fetch_assoc($collection_det_arr) )
                    {
                        $subs_coll3 += $coll_det['bill_passed']-($coll_det['grnd']-$coll_det['total']); 
                    }
                    if($subs_coll3<$FreeValue)
                        {
                            $data[$clientName]['fr_val'] = round($subs_coll3,2);
                        }
                        else
                        {
                            $data[$clientName]['fr_val'] = round($FreeValue,2);
                        }
                

                //echo $subs_coll;exit;
                $data[$clientName]['subs_coll'] = round($subs_coll+$subs_coll2,2);
                /*if(!empty($subs_coll))
                {
                    $data[$clientName]['fr_val'] = round($FreeValue,2);
                    if($subs_coll>=$RentalAmount)
                    {
                        $data[$clientName]['fr_val'] = round($FreeValue,2);
                    }
                    else
                    {
                        $NRentalAmount = 0;
                        if(strtolower($PeriodType)==strtolower('MONTH'))
                        {
                            $NRentalAmount = $RentalAmount/12;
                        }
                        else if(strtolower($PeriodType)==strtolower('Quater'))
                        {
                            $NRentalAmount = $RentalAmount/4;
                        }
                        else if(strtolower($PeriodType)==strtolower('YEAR'))
                        {
                            $NRentalAmount = $RentalAmount;
                        }
                        $sub = $NRentalAmount-$subs_coll;
                        $nsub = $FreeValue-$sub;
                        if($nsub>0)
                        {
                            $data[$clientName]['fr_val'] = round($FreeValue,2);
                        }
                    }
                }*/


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
                if(strtolower($PeriodType)==strtolower('MONTH'))
                {
                    $subs_penP = ceil($noofday/30);
                    $NewRentalAmount = $RentalAmount/12;
                    $monthlyFreeValue = $Balance;
                    $no_of_month = 1;
                    $no_of_days = 30;
                }
                else if(strtolower($PeriodType)==strtolower('Quater'))
                {
                    $subs_penP = ceil($noofday/90);
                    $NewRentalAmount = $RentalAmount/4;
                    $monthlyFreeValue = round($Balance/3,2);
                    $no_of_month = 3;
                    $no_of_days =90;
                }
                else if(strtolower($PeriodType)==strtolower('Half'))
                {
                    $subs_penP = ceil($noofday/180);
                    $NewRentalAmount = $RentalAmount/2;
                    $monthlyFreeValue = round($Balance/6,2);
                    $no_of_month = 6;
                    $no_of_days =180;
                }
                else if(strtolower($PeriodType)==strtolower('YEAR'))
                {
                    $subs_penP = round($noofday/365);
                    $NewRentalAmount = $RentalAmount/1;
                    $monthlyFreeValue = round($Balance/12,2);
                    $no_of_month = 12;
                    $no_of_days = 365;
                }
                if($subs_penP>=1)
                {
                   $subs_penP = 1; 
                }

                $sub_amt =round($NewRentalAmount*$subs_penP*1.18,2);
                //$subs_val = round($sub_amt-$subs_coll,2);
                $subs_val = round($sub_amt,2);
                
                if($data[$clientName]['status']=='Active' && $subs_val>0 && strtotime($today_date)>=strtotime($start_date))
                {
                     $last_month = $start_date;
                      //$today_date = '2022-05-17'; 
                       

                       
                    // $new_start_Date = date('Y-m-01');
                    //$new_end_date = date('Y-m-t');
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
            
            return $data[$clientName];
}

$select_risk = "SELECT * FROM `billing_risk_exposure` where client_id='395' and risk_action='email'"; 
$rsc_risk = mysqli_query($dd,$select_risk);

while($risk = mysqli_fetch_assoc($rsc_risk))
{
    $clientId   = $risk['client_id'];
    $percent = $risk['percent'];
    $sel_sent_status = "SELECT * FROM `billing_risk_exposure_mail_status` WHERE clientId='$clientId'  AND date(mail_date)=CURDATE()";
    $rsc_sent = mysqli_query($dd,$sel_sent_status);
    $sent_det = mysqli_fetch_assoc($rsc_sent);
    
    
    $record = get_closing_bal($clientId,$dd,$ispark);
    $onePer = round($record['RentalAmount']/100,2); 
    $plan_pers = round($record['Balance']/$onePer,3);
            
    //$fr_val_subs= round((($record['subbilled'])/118)*$plan_pers,2);
    $fr_val_subs = round((($record['subbilled_as_sub_date'])/118)*$plan_pers,2);
    $fr_val_talk= round(($record['billed']*100/118),2);
    $fr_val = $fr_val_subs+$fr_val_talk;    
            
    $open_bal = round($record['op2_credit'],2);
    $fr_val = round($record['fr_release_credit'],2);
    $csbal = round($record['consume_credit'],2);
    $total_consume = $bal = round($open_bal+$fr_val-$csbal,2);        
            
    $rsc_get_plan = mysqli_query($dd,"select * from `balance_master` where clientId='$clientId'  limit 1");
    $BalanceMaster = mysqli_fetch_assoc($rsc_get_plan);
    if($BalanceMaster['PlanId'] !=""){
        $PlanId = $BalanceMaster['PlanId'];
        $plan_det_qry = "select * from `plan_master` where Id='$PlanId' limit 1";
        $plan_rsc = mysqli_query($dd,$plan_det_qry);
        $PlanDetails = mysqli_fetch_assoc($plan_rsc);
        $RentalValue = $PlanDetails['CreditValue'];
        //echo $bal;exit;
        $calc_perc = round(($bal*100)/$RentalValue,2);  
        $send_email = false;
        $percent_remain = 100-$calc_perc; 
        if(!empty($bal) && $bal<0)
        {
            $send_email = true;
        }
        else if($calc_perc<=$percent_remain)
        {
            $send_email = true;
        }
        //echo $send_email;exit;
    //exit;
        $To = $risk['email_id'];
        $Tos = explode(",",$To);
        $AddTo = array();
        $TosFlag = true;
        if(!empty($Tos) && is_array($Tos))
        {
            foreach($Tos as $to)
            {
                if(!empty($to))
                {
                    if($TosFlag)
                    {
                        $To = $to;
                        $TosFlag=false;
                    }
                    else
                    {
                        $AddTo[] = $to;
                    }
                }

            }
        }
                
        $ClientRsc = mysqli_query($dd,"select * from registration_master where company_id='$clientId' limit 1");
        $ClientMaster = mysqli_fetch_assoc($ClientRsc);
        $table_trigger_detail = "<table border=\"2\"><tr> <th>Client Name</th><td>".$ClientMaster['company_name']."</td></tr>";
        $table_trigger_detail .= "<tr> <th>Plan Credit</th><td>".$PlanDetails['RentalAmount']."</td></tr>";
        $table_trigger_detail .= "<tr> <th>Frequency</th><td>".$PlanDetails['PeriodType']."</td></tr>";
        $table_trigger_detail .= "<tr> <th>Credit Value</th><td>".$RentalValue."</td></tr>";
        $table_trigger_detail .= "<tr> <th>Risk %</th><td>".$percent."</td></tr>";
        $table_trigger_detail .= "<tr> <th>Trigger Usage %</th><td>".$calc_perc."</td></tr>";
        $table_trigger_detail .= "</table>"; 
        $Subject="Risk Management ".date('d/M/Y');
        $AddCc = explode(',',$risk['email_cc']);
        $EmailText = $Subject;
        $EmailText .="<br/>";
        $EmailText .="<br/>";
        $EmailText .= $risk['remarks'];
        $EmailText .="<br/>";
        $EmailText .="<br/>";
        $EmailText .= $table_trigger_detail;
        $EmailText .="<br/>";
        $EmailText .="<br/>";
        $EmailText .="This is a auto-generated mail.";
        $EmailText .="<br/>";
        $risk_remarks = addslashes($EmailText);
        $ReceiverEmail=array('Email'=>$To,'Name'=>''); 
        $SenderEmail=array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 
        $ReplyEmail=array('Email'=>'ispark@teammas.in','Name'=>'Ispark'); 

        $emaildata=array('ReceiverEmail'=> $ReceiverEmail,'SenderEmail'=> $SenderEmail,'ReplyEmail'=> $ReplyEmail,'EmailText'=> $EmailText);


                if($send_email)
                {

                    $emaildata['Subject'] =  $Subject;
                    try
                    {
                        if(!empty($AddCc))
                        {
                            $emaildata['AddCc'] =  $AddCc;
                        }
                        if(!empty($AddTo))
                        {
                            $emaildata['AddTo'] =  $AddTo;
                        }
                        //print_r($emaildata); 
                        //echo '<br/>client id=>'.$clientId.'=>'.$total_consume.'<br/>';

                       
                       if($done)
                       {
                           $insert = "INSERT INTO `billing_risk_exposure_mail_status` SET clientId='$clientId',percent='$percent',calc_percent='$calc_perc',mail_date=now(),sent_status='$done',sent_text='$risk_remarks',today_closing_aot='$total_consume'";
                           $save = mysqli_query($dd,$insert);
                       }
                       else
                       {
                           $insert = "INSERT INTO `billing_risk_exposure_mail_status` SET clientId='$clientId',percent='$percent',calc_percent='$calc_perc',mail_date=now(),sent_status='failed',sent_text='$risk_remarks',today_closing_aot='$total_consume'";
                           $save = mysqli_query($dd,$insert);
                       }
                    }
                    catch (Exception $e){
                        $insert = "INSERT INTO `billing_risk_exposure_mail_status` SET clientId='$clientId',percent='$percent',calc_percent='$calc_perc',mail_date=now(),sent_status='failed',sent_text='$risk_remarks',today_closing_aot='$total_consume'";
                           $save = mysqli_query($dd,$insert);
                    }
                }
                
                
        }
    
        
    //print_r($data4);exit;
    
    
    
    
    
    
        

    
    
}