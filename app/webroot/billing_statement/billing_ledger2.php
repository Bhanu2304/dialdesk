<?php


//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

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

$ispark = mysqli_connect("$ispark_host", "$ispark_user", "$ispark_pass")or die("Ispark Server Connection Failed"); 
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

function get_closing_bal($clientId,$dd,$ispark,$from_date,$to_date)
{
    
    
    
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
       $client_qry = "SELECT * FROM registration_master rm WHERE `status` != 'CL' and is_dd_client='1'  $company_qry $client_status_qry ORDER BY status ";
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
                
                $hlh = array();
                $hlh['date'] =$from_date;
                $hlh['remarks'] ='Opening Balance';
                $hlh['billing'] = $op2_ledger; //for billing
                $hlh['collection'] = ''; //for collection
                 
                
                $html_ledger_history[$from_date][] = $hlh;
                #print_r($html_ledger_history);exit;
                
                $hlh_credit = array();
                $hlh_credit['date'] =$from_date;
                $hlh_credit['remarks'] ='Opening Credit Points';
                $hlh_credit['fr_release'] = $op2_credit; //for billing
                $hlh_credit['consume'] = ''; //for collection
                $html_credit_history[$from_date][] = $hlh_credit;
                
                
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
                    $inv_date = $inv_det['invoiceDate'];
                    //$bill_branch = $cost['cm']['branch'];     
                    
                    if(strtolower($inv_det['category'])==strtolower('first_bill'))
                    {
                            //check whether first bill have subscritpion amount
                        $select_subs = "select * from inv_particulars ip where initial_id='$initial_id' ";
                        $rsc_subs_arr = mysqli_query($ispark,$select_subs);
                        #foreach($rsc_subs_arr as $sb)
                        while($sb = mysqli_fetch_assoc($rsc_subs_arr))
                        {
                            
                            $hlh = array();
                            $hlh['date'] =$inv_date;
                            $hlh['remarks'] =$sb['particulars'];
                            $hlh['category'] =$sb['sub_category'];
                            $hlh['bill_no'] =$bill_no;
                            $hlh['billing'] = round($sb['amount']*1.18,2); //for billing
                            $hlh['collection'] = ''; //for collection
                            $html_ledger_history[$inv_date][] = $hlh;
                            if(strtolower($sb['sub_category'])==strtolower('Subscription'))
                            {    
                             $fr_release_credit +=get_credit_from_subs_value($rental_credit,$balance_credit,$sb['amount']);
                             $hlh_credit = array();
                             $hlh_credit['date'] =$inv_date;
                             $hlh_credit['remarks'] =$sb['sub_category'];
                             $hlh_credit['fr_release'] = get_credit_from_subs_value($rental_credit,$balance_credit,$sb['amount']); //for billing
                             $hlh_credit['consume'] = ''; //for collection
                             $html_credit_history[$inv_date][] = $hlh_credit;
                            }
                        }

                    }
                    else if(strtolower($inv_det['category'])==strtolower('subscription'))
                    {
                        
                        $hlh = array();
                            $hlh['date'] =$inv_date;
                            $hlh['remarks'] =$inv_det['invoiceDescription'];
                            $hlh['category'] =$inv_det['category'];
                            $hlh['bill_no'] =$bill_no;
                            $hlh['billing'] = $inv_det['grnd']; //for billing
                            $hlh['collection'] = ''; //for collection
                            $html_ledger_history[$inv_date][] = $hlh;
                        $fr_release_credit +=get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['total']);
                        $hlh_credit = array();
                             $hlh_credit['date'] =$inv_date;
                             $hlh_credit['remarks'] =$inv_det['category'];
                             $hlh_credit['fr_release'] = get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['total']); //for billing
                             $hlh_credit['consume'] = ''; //for collection
                             $html_credit_history[$inv_date][] = $hlh_credit;
                    }
                    else if(strtolower($inv_det['category'])==strtolower('Talk Time') || strtolower($inv_det['category'])==strtolower('Topup'))
                    {
                        $fr_release_credit +=$inv_det['total'];
                        
                        $hlh = array();
                            $hlh['date'] =$inv_date;
                            $hlh['remarks'] =$inv_det['invoiceDescription'];
                            $hlh['category'] =$inv_det['category'];
                            $hlh['bill_no'] =$bill_no;
                            $hlh['billing'] = $inv_det['grnd']; //for billing
                            $hlh['collection'] = ''; //for collection
                            $html_ledger_history[$inv_date][] = $hlh;
                            
                            $hlh_credit = array();
                             $hlh_credit['date'] =$inv_date;
                             $hlh_credit['remarks'] =$inv_det['category'];
                             $hlh_credit['fr_release'] = $inv_det['total']; //for billing
                             $hlh_credit['consume'] = ''; //for collection
                             $html_credit_history[$inv_date][] = $hlh_credit;
                    }
                    else
                    {
                        $hlh = array();
                            $hlh['date'] =$inv_date;
                            $hlh['remarks'] =$inv_det['invoiceDescription'];
                            $hlh['category'] =$inv_det['category'];
                            $hlh['bill_no'] =$bill_no;
                            $hlh['billing'] = round($inv_det['grnd'],2); //for billing
                            $hlh['collection'] = ''; //for collection
                            $html_ledger_history[$inv_date][] = $hlh;
                    }
                    
                    
                }
                //getting advance from this client
                $cost_adv_rsc = mysqli_query($ispark,"SELECT * FROM `bill_pay_advance` bpa WHERE bill_no='$cost_id' AND  date(bpa.pay_dates)  between '$to_date' and '$from_date'");
                
                while($cost_adv_arr = mysqli_fetch_assoc($cost_adv_rsc) )    
                {
                    $coll2_ledger += $cost_adv_arr['bill_passed'];
                    $hlh = array();
                            $hlh['date'] =$cost_adv_arr['pay_dates'];
                            $hlh['remarks'] =$cost_adv_arr['remarks'];
                            $hlh['category'] ='Advance';
                            $hlh['bill_no'] =$cost_adv_arr['bill_no'];
                            $hlh['billing'] = ''; //for billing
                            $hlh['collection'] = $cost_adv_arr['bill_passed']; //for collection
                            $html_ledger_history[$cost_adv_arr['pay_dates']][] = $hlh;
                }
                   //getting actual outstanding as on from date.  
               $select_payment_qry = "SELECT * FROM 
    bill_pay_particulars bpp inner join tbl_invoice ti on bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year and bpp.branch_name=ti.branch_name AND bpp.company_name='$bill_company' 
    where ti.cost_center='$cost_center' and date(bpp.pay_dates)  between '$from_date' and '$to_date' ";    

                $collection_billingtodate_arr = mysqli_query($ispark,$select_payment_qry);
                while($coll_det = mysqli_fetch_assoc($collection_billingtodate_arr) )
                {    
                    $coll2_ledger += $coll_det['bill_passed'];
                    $hlh = array();
                            $hlh['date'] =$coll_det['pay_dates'];
                            $hlh['remarks'] =$coll_det['remarks'];
                            $hlh['category'] ='Collection';
                            $hlh['bill_no'] =$coll_det['bill_no'];
                            $hlh['collection'] = $coll_det['bill_passed']; //for collection
                            $html_ledger_history[$coll_det['pay_dates']][] = $hlh;
                }
                       
                //getting file detail 
                
                
                  
                //getting consumption from table
                 $select_consumption = "SELECT DATE_FORMAT(MAX(cm_date),'%Y-%m-%d') dater,SUM(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId' and date(cm_date)>='$start_date' and DATE(cm_date) between '$from_date' and '$to_date' GROUP BY YEAR(cm_date),MONTH(cm_date)";
                 $rsc_consuption_arr = mysqli_query($dd,"$select_consumption");
                 //print_r($rsc_consuption_arr);exit;
                 #foreach($rsc_consuption_arr as $consume)
                 while($consume = mysqli_fetch_assoc($rsc_consuption_arr))    
                 {
                     $consume_credit += $consume['total'];
                     $hlh_credit = array();
                             $hlh_credit['date'] =$consume['dater'];;
                             $hlh_credit['remarks'] ='Consume';
                             $hlh_credit['fr_release'] = ''; //for billing
                             $hlh_credit['consume'] = $consume['total'];; //for collection
                             $html_credit_history[$consume['dater']][] = $hlh_credit;
                 }
                 
                 /*
                  $select_consumption = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId' and date(cm_date)>='$start_date' and DATE(cm_date) between '$from_date' and '$to_date'";
                 $rsc_consuption_arr = mysqli_query($dd,"$select_consumption");
                 //print_r($rsc_consuption_arr);exit;
                 #foreach($rsc_consuption_arr as $consume)
                 while($consume = mysqli_fetch_assoc($rsc_consuption_arr))    
                 {
                     $consume_credit += $consume['total'];
                     $hlh_credit = array();
                             $hlh_credit['date'] =$to_date;
                             $hlh_credit['remarks'] ='Consume';
                             $hlh_credit['fr_release'] = ''; //for billing
                             $hlh_credit['consume'] = $consume['total'];; //for collection
                             $html_credit_history[$from_date][] = $hlh_credit;
                 }
                  */
                 
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
                $data[$clientName]['html_ledger_history'] = $html_ledger_history;
                $data[$clientName]['html_credit_history'] = $html_credit_history;

                
                #print_r($html_ledger_history);exit;
                
                
            }
            
            return $data[$clientName];
}

function nagitive_check($value){
    if (isset($value)){
        if (substr(strval($value), 0, 1) == "-"){
        return 'negative';
    } else {
        return 'It is not negative!';
    }
        }
}
$from_date1 = $_REQUEST['FromDate'];
$to_date1 = $_REQUEST['ToDate'];

    $explode_date = explode("-",$from_date1);
    $explode_date = array_reverse($explode_date);
    $from_date = implode("-",$explode_date); 
    $explode_date1 = explode("-",$to_date1);
    $explode_date1 = array_reverse($explode_date1);
    $to_date = implode("-",$explode_date1);

  $company_id = $_REQUEST['ClientId'];  
  $ledger_report =   $_REQUEST['ledger'];  
  $credit_report =   $_REQUEST['credit'];    
    
    if(!empty($company_id) && $company_id!='All')
    {               
        $company_qry = " and company_id='$company_id'";        
    }              
                    
                    
                    $ClientRsc = mysqli_query($dd,"SELECT * FROM registration_master rm where 1=1 $company_qry order by company_name ");
                    
                    
                        $html = '<table border="2">';
                        $html .= "<tr><th colspan=\"7\"> From $from_date1 To $to_date1</td></tr>";
                        $html .= '<tr>';
                        $html .= '<th colspan="7">Client Payment History</th>';
                        $html .= '</tr>';
                        $html .= '<th>Client</th>';
                        $html .= '<th>Date</th>';
                        $html .= '<th>Particulars</th>';
                        $html .= '<th>Type</th>';
                        $html .= '<th>Bill No.</th>';
                        $html .= '<th>Billing</th>';
                        $html .= '<th>Collection</th>';
                        $html .= '</tr>';
                        
                        
                        $html_credit = '<table border="2">';
                        $html_credit .= "<tr><th colspan=\"5\"> From $from_date1 To $to_date1</td></tr>";
                        $html_credit .= '<tr>';
                        $html_credit .= '<th colspan="5">Credit Point Consumption</th>';
                        $html_credit .= '</tr>';
                        $html_credit .= '<th>Client</th>';
                        $html_credit .= '<th>Date</th>';
                        $html_credit .= '<th>Particular</th>';
                        $html_credit .= '<th>Fresh Release</th>';
                        $html_credit .= '<th>Consumed</th>';
                        $html_credit .= '</tr>';
                        
                        $html3 = "<table>";
                        
                        
                            while($ClientMaster = mysqli_fetch_assoc($ClientRsc))
                            {
                                $clientId = $client_id = $ClientMaster['company_id'];

                              //echo  $today_consume = get_today_consume_data($client_id,$dd,$con); exit;
                                $closing = 0;
                                $billing =0;
                                $collection =0;
                                

                                

                                $record_client = get_closing_bal($clientId,$dd,$ispark,$from_date,$to_date);
                                #print_r($record);exit;
                                $record_list = $record_client['html_ledger_history'];
                                $date_key_list = array_keys($record_list);
                                sort($date_key_list);
                                #print_r($record_list);exit;
                               $html2 ='';
                                foreach($date_key_list as $datekey)
                                {
                                    foreach($record_list[$datekey] as $record)
                                    {
                                        #print_r($record);exit;
                                        $html2 .= '<tr>';
                                        $html2 .= '<td>'.$ClientMaster['company_name'].'</td>';
                                        $html2 .= '<td>'.$record['date'].'</td>';
                                        $html2 .= '<td>'.$record['remarks'].'</td>';
                                        $html2 .= '<td>'.$record['category'].'</td>';
                                        $html2 .= '<td>'.$record['bill_no'].'</td>';
                                        $html2 .= '<td>'.$record['billing'].'</td>';
                                        $html2 .= '<td>'.$record['collection'].'</td>';
                                        $html2 .= '</tr>';
                                        $billing +=$record['billing'];

                                        $collection +=$record['collection'];
                                        $closing +=$record['billing'];
                                        $closing -=$record['collection'];
                                    }
                                }
                                
                                if(!empty($html2) && !empty($ledger_report))
                                {
                                    $html2 = $html.$html2; 
                                    $html2 .= '<tr>';
                                       $html2 .= '<th colspan="4"></th>';
                                       $html2 .= '<th> Total</th>';
                                       $html2 .= '<th>'.$billing.'</th>';
                                       $html2 .= '<th>'.$collection.'</th>';
                                   $html2 .= '</tr>';
                                   $html2 .= '<tr>';
                                       $html2 .= '<th colspan="2"> </th>';
                                       $html2 .= '<th> Closing Balance</th>';
                                       $html2 .= '<th colspan="2"> </th>';
                                       $html2 .= '<th>'.$closing.'</th>';
                                       $html2 .= '<th></th>';
                                   $html2 .= '</tr>';
                                   $html2 .= '</table>'; 
                                }
                                
//                                $html .= '<tr>';
//                                $html .= '<th colspan="2"> </th>';
//                                    $html .= '<th> Closing Balance</th>';
//                                    $html .= '<th colspan="2"> </th>';
//                                    $html .= '<th>'.$closing.'</th>';
//                                    $html .= '<th></th>';
//                                    $html .= '</tr>';

                                
                                $record_list_credit = $record_client['html_credit_history'];
                                $date_key_clist = array_keys($record_list_credit);
                                sort($date_key_clist);
                                #print_r($record_list_credit);exit;
                                $closing_credit = 0;
                                $fr_release =0;
                                $consume =0;
                                $html_credit2 = '';
                                #$html_credit2 =$html_credit;
                                foreach($date_key_clist as $cdatekey)
                                {
                                    foreach($record_list_credit[$cdatekey] as $record)
                                    {
                                    $html_credit2 .= '<tr>';
                                    $html_credit2 .= '<td>'.$ClientMaster['company_name'].'</td>';
                                    $html_credit2 .= '<td>'.$record['date'].'</td>';
                                    $html_credit2 .= '<td>'.$record['remarks'].'</td>';
                                    $html_credit2 .= '<td>'.round($record['fr_release'],2).'</td>';
                                    $html_credit2 .= '<td>'.round($record['consume'],2).'</td>';
                                    $html_credit2 .= '</tr>';
                                    $closing_credit +=round($record['fr_release'],2);
                                    $closing_credit -=round($record['consume'],2);
                                    
                                    $fr_release +=round($record['fr_release'],2);
                                    $consume +=round($record['consume'],2);
                                    }
                                }
                                
                                if(!empty($html_credit2) && !empty($credit_report))
                                {
                                    $html_credit2 = $html_credit.$html_credit2;
                                    $html_credit2 .= '<tr>';
                                       $html_credit2 .= '<th colspan="2"> </th>';
                                       $html_credit2 .= '<th>Total</th>';
                                       $html_credit2 .= '<th>'.$fr_release.'</th>';
                                       $html_credit2 .= '<th>'.$consume.'</th>';
                                   $html_credit2 .= '</tr>';

                                   $html_credit2 .= '<tr>';
                                   $html_credit2 .= '<th colspan="2"> </th>';
                                       $html_credit2 .= '<th> Closing Credit Point</th>';
                                       $html_credit2 .= '<th>'.$closing_credit.'</th>';
                                       $html_credit2 .= '<th></th>';
                                       $html_credit2 .= '</tr>';
                                    $html_credit2 .= '</table>';  
                                }
                                
                                if(!empty($html2) || !empty($html_credit2))
                                 #$html3 .='<tr><td colspan="4"></td></tr>';
                                {
                                    $html3 .='<tr>';
                                    if(!empty($ledger_report))
                                    {
                                        $html3 .='<td>'.$html2.'</td>';
                                        $html3 .='<td></td>';
                                        $html3 .='<td></td>';
                                    }
                                 
                                 if(!empty($credit_report))
                                    {
                                        $html3 .='<td>'.$html_credit2.'</td>';
                                    }
                                 
                                 $html3 .='</tr>';
                                 $html3 .='<tr><td>&nbsp;</td> <td> </td> <td> </td> <td> </td></tr>';
                                 $html3 .='<tr><td>&nbsp;</td> <td> </td> <td> </td> <td> </td></tr>';
                                }
                                 
                                 
                            }     
                                 
                               $html3 .='</table>';  
                                
                            
                    //print_r($emaildata);die;

                        

 //   }

// print_r($clientArr);die;


$fileName = "client_ledger_report".date('d_m_y_h_i_s');
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$fileName.".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo $html3 ; exit;
exit;







