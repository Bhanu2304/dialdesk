    <?php
class Dialdee2ViewsController extends AppController 
{
    public $uses=array('DialdeeSharedRegistrationMaster','DialdeeStandAloneRegistrationMaster','InitialInvoice','BillMaster','InitialInvoiceTmp','Addclient','Addbranch','Addcompany','CostCenterMaster',
        'AddInvParticular','Particular','AddInvDeductParticular','DeductParticular','Access','User','EditAmount',
        'Provision','PONumber','NotificationMaster','ProvisionPart','ProvisionPartDed');
    public $components = array('RequestHandler');
    public $helpers = array('Js');
		

public function beforeFilter()
{
    parent::beforeFilter();
    
    $this->Auth->allow('ajax_add','ajax_client_wallet','addon_credit','ajax_addon_credit','ajax_add2','bill_approval','view_bill','edit_proforma','update_proforma','edit_bill','update_bill');
    $this->InitialInvoice->useDbConfig = 'dbNorth';
    $this->BillMaster->useDbConfig = 'dbNorth';
    $this->InitialInvoiceTmp->useDbConfig = 'dbNorth';
    $this->Addclient->useDbConfig = 'dbNorth';
    $this->Addbranch->useDbConfig = 'dbNorth';
    $this->Addcompany->useDbConfig = 'dbNorth';
    $this->CostCenterMaster->useDbConfig = 'dbNorth';
    $this->AddInvParticular->useDbConfig = 'dbNorth';
    
    $this->Particular->useDbConfig = 'dbNorth';
    $this->AddInvDeductParticular->useDbConfig = 'dbNorth';
    $this->DeductParticular->useDbConfig = 'dbNorth';
    $this->Access->useDbConfig = 'dbNorth';
    $this->User->useDbConfig = 'dbNorth';
    
    
    $this->Provision->useDbConfig = 'dbNorth';
    $this->PONumber->useDbConfig = 'dbNorth';
    $this->ProvisionPart->useDbConfig = 'dbNorth';
    $this->ProvisionPartDed->useDbConfig = 'dbNorth';
    $this->DialdeeSharedRegistrationMaster->useDbConfig = 'dbDaildeeStandAlone';
    $this->DialdeeStandAloneRegistrationMaster->useDbConfig = 'dbDaildeeStandAlone';
    //$this->User->useDbConfig = 'dbNorth';
    //$this->EditAmount->useDbConfig = 'dbNorth';
    
}

public function get_credit_from_subs_value($rental,$balance,$subsvalue)
{
    //echo "round($balance/$rental,3)"; exit;
    $plan_pers = $balance/$rental; 
    //echo $plan_pers;exit;
     $creditvalue = round(($subsvalue*$plan_pers),2);
    //echo $creditvalue;exit
    return $creditvalue;
}

public function get_client_status($status)
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

public function get_plan_status($start_date)
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






public function get_cost_center($clientId)
{
    return $this->CostCenterMaster->query("select * from cost_master cm where dialdee_client_id='$clientId' and dialdee_type='sa' limit 1");
}

public function get_months_btw_dates($start_date,$end_date)
{
    //echo "{$start_date} btw {$end_date}";
    
    $start    = new DateTime($start_date);
$start->modify('first day of this month');
$end      = new DateTime($end_date);
$end->modify('first day of next month');
$interval = DateInterval::createFromDateString('1 month');
$period   = new DatePeriod($start, $interval, $end);
$month_list = array();
foreach ($period as $dt) {
    $month_list[] = $dt->format("Y-m");
}//exit;
return $month_list;
}

public function get_billed($cost_center,$inv_date,$rental_credit,$balance_credit,$op2_ledger,$op2_credit)
{
    $sel_billed_fromdateqry = "select ti.* from tbl_invoice ti where  ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (ti.invoiceDate = '$inv_date' )  "; #and bill_no!='' 
    $billed_fromdateqry_arr = $this->InitialInvoice->query($sel_billed_fromdateqry);

    foreach($billed_fromdateqry_arr as $inv_det)
    {
        $bill_no = $inv_det['ti']['bill_no'];
        $initial_id = $inv_det['ti']['id'];
        $bill_finyear = $inv_det['ti']['finance_year'];

        $op2_ledger +=$inv_det['ti']['grnd'];
        $cost_id = $cost['cm']['id'];
        $bill_branch = $cost['cm']['branch'];    

        if(strtolower($inv_det['ti']['category'])==strtolower('first_bill'))
        {
                //check whether first bill have subscritpion amount
            $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
            $rsc_subs_arr = $this->InitialInvoice->query($select_subs);
            foreach($rsc_subs_arr as $sb)
            {
                 $op2_credit +=$this->get_credit_from_subs_value($rental_credit,$balance_credit,$sb['ip']['amount']);
            }

        }
        else if(strtolower($inv_det['ti']['category'])==strtolower('subscription'))
        {
            $op2_credit +=$this->get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
        }
        else if(strtolower($inv_det['ti']['category'])==strtolower('Talk Time') || strtolower($inv_det['ti']['category'])==strtolower('Topup'))
        {
            $op2_credit +=$inv_det['ti']['total'];
        }
    }
    return array($op2_ledger,$op2_credit);
}



public function get_advance($cost_id,$from_date,$to_date,$ledger_value,$type)
{
    $cost_adv_arr = $this->CostCenterMaster->query("SELECT *,SUM(bill_passed) advance FROM `bill_pay_advance` bpa WHERE bill_no='$cost_id' AND  date(bpa.pay_dates) between '$from_date' and '$to_date' ");
    if(!empty($cost_adv_arr))
    {
        if($type=='old')
        $ledger_value -= $cost_adv_arr['0']['0']['advance'];
        else 
        $ledger_value += $cost_adv_arr['0']['0']['advance'];    
    }
    return $ledger_value;
}



public function get_collection($cost_center,$from_date,$to_date,$bill_company,$ledger_value,$type)
{
    //getting actual outstanding as on from date.
    $select_payment_fromdateqry = "SELECT sum(bpp.bill_passed) bill_passed FROM 
    bill_pay_particulars bpp inner join tbl_invoice ti on bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year and bpp.branch_name=ti.branch_name AND bpp.company_name='$bill_company' 
    where ti.cost_center='$cost_center' and date(bpp.pay_dates)  between '$from_date' and '$to_date' ";  

    $collection_fromdate_arr = $this->BillMaster->query($select_payment_fromdateqry);
    foreach($collection_fromdate_arr as $coll_det)
    {   if($type=='old') 
        $ledger_value -= $coll_det['0']['bill_passed'];
        else 
        $ledger_value += $coll_det['0']['bill_passed'];    
    }
    
    return $ledger_value;
}

public function get_consumption($clientId,$cap_date,$op2_value,$type)
{
    //getting consumption from table 
    $select_consumption = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId' and date(cm_date)='$cap_date'";
    $rsc_consuption_arr = $this->DialdeeSharedRegistrationMaster->query("$select_consumption");
    //print_r($rsc_consuption_arr);exit;
    foreach($rsc_consuption_arr as $consume)
    {
        if($type=='old')
        $op2_value -= $consume[0]['total'];
        else
        $op2_value += $consume[0]['total'];    
    }
    return $op2_value;
}

public function get_consumption2($clientId,$cap_date,$op2_value,$type)
{
    //getting consumption from table 
    $select_consumption = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId' and date(cm_date)='$cap_date'";
    $rsc_consuption_arr = $this->DialdeeSharedRegistrationMaster->query("$select_consumption");
    //print_r($rsc_consuption_arr);exit;
    $op2_value = 0;
    foreach($rsc_consuption_arr as $consume)
    {
        $op2_value += $consume[0]['total'];   
    }
    return $op2_value;
}

public function get_fresh_release($subs_validity,$cap_date,$consume)
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



public function get_freesession_from_subs_value($rental,$balance,$subsvalue)
{
    //echo "round($balance/$rental,3)"; exit;
    $plan_pers = $balance/$rental; 
    //echo $plan_pers;exit;
     $creditvalue = round(($subsvalue*$plan_pers));
    //echo $creditvalue;exit
    return $creditvalue;
}

public function get_freesession_from_topup($rental,$balance,$subsvalue)
{
    //echo "round($balance/$rental,3)"; exit;
    $plan_pers = $balance/$rental; 
    //echo $plan_pers;exit;
     $creditvalue = round(($subsvalue*$plan_pers));
    //echo $creditvalue;exit
    return $creditvalue;
}

public function get_collection_social($cost_center,$from_date,$to_date,$bill_company,$ledger_value,$type)
{
    //getting actual outstanding as on from date.
    $select_payment_fromdateqry = "SELECT sum(bpp.bill_passed) bill_passed FROM 
    bill_pay_particulars bpp inner join tbl_invoice ti on bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1) and ti.category in ('subscription-tool','Topup-Tool','Talk-Time-Tool')
    AND bpp.financial_year=ti.finance_year and bpp.branch_name=ti.branch_name AND bpp.company_name='$bill_company' 
    where ti.cost_center='$cost_center' and date(bpp.pay_dates)  between '$from_date' and '$to_date' ";  

    $collection_fromdate_arr = $this->BillMaster->query($select_payment_fromdateqry);
    foreach($collection_fromdate_arr as $coll_det)
    {   if($type=='old') 
        $ledger_value -= $coll_det['0']['bill_passed'];
        else 
        $ledger_value += $coll_det['0']['bill_passed'];    
    }
    
    return $ledger_value;
}

public function get_consumption_social($clientId,$start_date,$end_date)
{
    //getting consumption from table 
    $select_consumption = "SELECT user_intd_session total,user_intd_rate rate,date(cm_date) cm_date FROM billing_consume_daily  bcd WHERE client_id='$clientId' and date(cm_date) between '$start_date' and '$end_date'";
    $rsc_consuption_arr = $this->DialdeeSharedRegistrationMaster->query("$select_consumption");
    //print_r($rsc_consuption_arr);exit;
    $op2_value_arr = array();
    foreach($rsc_consuption_arr as $consume)
    {
        $op2_value_arr[$consume[0]['cm_date']]['total']= $consume['bcd']['total'];
        $op2_value_arr[$consume[0]['cm_date']]['rate']= $consume['bcd']['rate'];
    }
    return $op2_value_arr;
}




public function ajax_addon_credit() 
{
    //echo "site is under maintanance. will be live after 4 hours.";
    //exit;
    $this->layout='ajax';
    $data = array();
    $from_date = "";
    $to_date = "";
    $company_qry = "";
    //print_r($this->request->data['from_date']);exit;
    if(!empty($this->request->data['from_date']))
    {
        $from_date1 = $this->request->data['from_date'];
        $to_date1 = $this->request->data['to_date'];                 
    }
    else
    {
        $from_date1 = date('01-m-Y'); 
        $to_date1 = date('d-m-Y');          
    }
    
    $explode_date = explode("-",$from_date1);
    //print_r($explode_date);
    $explode_date = array_reverse($explode_date);
    //print_r($explode_date);exit;
    $from_date = $from_date1; 
        
    $explode_date1 = explode("-",$to_date1);
    $explode_date1 = array_reverse($explode_date1);
    $to_date = $to_date1; 
    
    
    $company_id = $this->request->data['client']; 
    $client_status = $this->request->data['client_status'];
    $this->set('company_id',$company_id);
    $this->set('client_status',$client_status);
    if(!empty($this->request->data['client']) && $this->request->data['client']!='All')
    {               
        $company_qry = " and company_id='$company_id'";        
    }
    if(!empty($this->request->data['client_status']) && $this->request->data['client_status']!='All')
    {               
        $client_status_qry = " and rm.status='$client_status'";        
    }
    
    
    $this->set('from_date',$from_date1);
    $this->set('to_date',$to_date1);
    
    $day_before = date( 'Y-m-d', strtotime( $from_date . ' -1 day' ) );
    $finYear = '2022-23';
    $year = date('Y');
    $year_2 = date('y');
    $arr = explode('-',$finYear);
    $selectedMonth = $this->request->data['month'];
    //$selectedMonth='Feb';
    $month_arr =array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,
        'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,
        'Oct'=>10,'Nov'=>11,'Dec'=>12) ;
    $month_arr_swap =array_flip($month_arr) ;
    $month_no = 0;
    
    if(empty($selectedMonth))
    {
        $Nmonth =$month = date( 'M', strtotime($from_date) );
        $month_no = $month_arr[$Nmonth];
    }
    else
    {
        $Nmonth =$month = $selectedMonth;
        $month_no = $month_arr[$selectedMonth]; 
    }
    
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
        
       $client_qry = "SELECT rm.* FROM registration_master rm 
         WHERE  rm.`status` != 'CL'  $company_qry $client_status_qry ORDER BY status";
       //echo $client_qry;exit;
        $client_det_arr = $this->DialdeeSharedRegistrationMaster->query($client_qry);
        
        
        
        foreach($client_det_arr as $clr)
        {
            $clientId = $clr['rm']['company_id']; 
            if(empty($clr['rm']['company_name']))
            {
                $clientName = $clr['rm']['auth_person'];
            }
            else
            {
                $clientName = $clr['rm']['company_name'];
            }
            
            $data[$clientName]['phone_no'] = $clr['rm']['phone_no'];
            $data[$clientName]['clientEmail'] = $clr['rm']['email'];
            #$createdate_for_client_to_first_bill = $clr['rm']['create_date'];
            $data[$clientName]['status'] = $this->get_client_status($clr['rm']['status']);
            
            
            $data[$clientName]['clientId'] = $clientId;
            $sel_balance_qry = "SELECT * FROM `plan_allocate_master` bm WHERE client_id='$clientId' limit 1";
            $bal_det=$this->DialdeeStandAloneRegistrationMaster->query($sel_balance_qry);
            $start_date = $bal_det['0']['bm']['plan_start_date']; 
            $data[$clientName]['plan_status'] = $this->get_plan_status($start_date); 
            
            
            $op2_ledger = 0;
            $bill2_ledger = 0;
            $coll2_ledger = 0;
            $free_chat = 0;
            $free_chat2 = 0;
            $used_chat = 0;
            $chargable_chat = 0;
            $op2_credit = 0;
            $fr_release_credit = 0;
            $consume_credit = 0;
            $credit_closing = 0;
            $op2_consume_credit_testing = 0;
            //echo "SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$clientId'";exit;
            $op_det_ledger = 0;
            
            
            $planId = $bal_det['0']['bm']['plan_id'];
            $plan_mode = $bal_det['0']['bm']['plan_mode'];
            $sel_months_qry = "SELECT * FROM `rental_master` rm WHERE rental2='$plan_mode' LIMIT 1";
            $months_det = $this->DialdeeSharedRegistrationMaster->query($sel_months_qry);
            $total_months = $months_det['0']['rm']['months']; 
           $plan_det_qry = "SELECT tool_charge,wa_busi_intd_charge,wa_user_intd_charge,user_intd_free_sessions FROM `plan_master` pm WHERE id='$planId'  limit 1";  
            $plan_det = $this->DialdeeStandAloneRegistrationMaster->query($plan_det_qry);
            #$data[$clientName]['InChatCharge'] = $plan_det['0']['pm']['wa_user_intd_charge'];
            $data[$clientName]['InChatCharge'] = 1;
            $data[$clientName]['OutChatCharge'] = 1;
            //$FreeValue = $plan_det['0']['pm']['CreditValue'];
            $tool_charge = $plan_det['0']['pm']['tool_charge'];
            $RentalAmount = $tool_charge*$total_months;
            $data[$clientName]['RentalAmount'] = $RentalAmount;            
            $end_date = $to_date;                                    
            $PeriodType = $months_det['0']['rm']['rental3'];                         
            $rental_credit = 0;
            $balance_credit = $plan_det['0']['pm']['user_intd_free_sessions']; 
            
            $no_of_days = $months_det['0']['rm']['days1']; 
            $pamnt = $RentalAmount;                
            $cost_cat_arr = $this->get_cost_center($clientId);
            $flagSubsMade = true;
            #print_r($cost_cat_arr);
            
                    
                 
                
            
            foreach($cost_cat_arr as $cost)
            {
                $cost_center = $cost['cm']['cost_center'];  
                $data[$clientName]['cost_center'] = $cost_center;
                $data[$clientName]['month'] = $month;
                $bill_company = $cost['cm']['company_name'];
                $subs_validity = array();
                $is_free_session_alloted = false;
                //echo $to_date;
                $months_list1 = $this->get_months_btw_dates($start_date,$from_date);
                //print_r($months_list1);exit;
                foreach($months_list1 as $monther)
                {
                    $date_start = "{$monther}-01"; 
                    $date_end = date("Y-m-t", strtotime($date_start)); 
                    if(strtotime($date_end)>=strtotime($to_date))
                    {
                        $date_end = $from_date; 
                        $is_free_session_alloted = true;
                    }
                    $free_chat = $balance_credit;
                    $sel_billed_todateqry1 = "select ti.* from tbl_invoice ti where ti.bill_no!='' and ti.category in ('subscription-tool','Topup-Tool','Talk-Time-Tool')  and ti.status='0' and cost_center='$cost_center' and ti.invoiceDate>='$start_date' and ti.invoiceDate<'$from_date' and (  ti.invoiceDate >= '$date_start' AND ti.invoiceDate < '$date_end')  "; #and bill_no!=''
                    //echo $sel_billed_todateqry1;exit;
                    $billed_todateqry_arr = $this->InitialInvoice->query($sel_billed_todateqry1);
                    $bill_no = $inv_det['ti']['bill_no'];
                    $bill_finyear = $inv_det['ti']['finance_year'];
                    $bill_company = $cost['cm']['company_name'];
                    $bill2_ledger +=$inv_det['ti']['grnd'];
                    $initial_id = $inv_det['ti']['id'];
                    $inv_date = $inv_det['ti']['invoiceDate'];
                    $carry_forward = 1;
                    //$bill_branch = $cost['cm']['branch'];     
                    $sub_days =$no_of_days*1;
                    $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 
                    
                    foreach($billed_todateqry_arr as $inv_det)
                    {
                        $bill_no = $inv_det['ti']['bill_no'];
                        $bill_finyear = $inv_det['ti']['finance_year'];
                        $bill_company = $cost['cm']['company_name'];
                        $bill2_ledger +=$inv_det['ti']['grnd'];
                        $initial_id = $inv_det['ti']['id'];
                        $inv_date = $inv_det['ti']['invoiceDate'];
                        $carry_forward = 1;
                        //$bill_branch = $cost['cm']['branch'];     
                        $sub_days =$no_of_days*1;
                        $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 

                        if(strtolower($inv_det['ti']['category'])==strtolower('subscription-tool') )
                        {
                            $select_inv_session = "SELECT * FROM inv_particulars ip WHERE initial_id='$initial_id' and sub_category in ('Topup-Tool','Talk-Time-Tool')"; 
                            $inv_parts_arr = $this->InitialInvoice->query($select_inv_session);
                            foreach($inv_parts_arr as $parts)
                            {
                                $op2_credit +=$parts['ip']['amount'];
                            }
                            $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                            $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$fr_release_credit;
                            $flagSubsMade = false; 
                        }
                        else if($carry_forward && strtolower($inv_det['ti']['category'])==strtolower('Topup-Tool') )
                        {
                            $op2_credit += $inv_det['ti']['total'];
                        }
                        else if($carry_forward && strtolower($inv_det['ti']['category'])==strtolower('Talk-Time-Tool') )
                        {
                            $op2_credit += $inv_det['ti']['total'];
                        }
                        else
                        {

                            #$op2_value =$this->get_freesession_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
                            $value_in_subs_validity = $subs_validity["$inv_date#$to"];
                            $subs_validity["$inv_date#$to"] = $value_in_subs_validity+$op2_value;
                        }
                       // echo $op2_credit;exit;
                        #$fr_release_credit +=$op2_value;

                    }
                    $coll2_ledger = $this->get_collection_social($cost_center,$date_start,$date_end,$bill_company,$coll2_ledger,'new');
                    $date_capture_start = strtotime($date_start);
                    $consume_arr = $this->get_consumption_social($clientId,$date_start,$date_end);
                    #print_r($consume_arr);
                    if(!empty($consume_arr))
                    {
                        while($date_capture_start<=strtotime($date_end))
                        {
                            $cap_date = date('Y-m-d',$date_capture_start);
                            #$used_chat += $consume_arr[$cap_date]['total'];
                            if($free_chat<=0)
                            {
                                $op2_credit -= $consume_arr[$cap_date]['total']*round($consume_arr[$cap_date]['rate'],3);
                                $chargable_chat += $consume_arr[$cap_date]['total'];
                            }
                            else
                            {
                                $free_chat -= $consume_arr[$cap_date]['total'];
                                if($free_chat<0)
                                {
                                    $op2_credit-= abs($free_chat)*round($consume_arr[$cap_date]['rate'],3);
                                    $chargable_chat += abs($free_chat);
                                    $free_chat = 0;
                                }
                            }
                            $date_capture_start =  strtotime($cap_date.' +1 days');
                        }
                    }
                    
                    //print_r("$cap_date /$free_chat");
                    //echo '<br/>';
                }
                #print_r($op2_credit);exit;
                //echo "$from_date/$to_date";exit;
                $months_list2 = $this->get_months_btw_dates($to_date,$from_date);
                //print_r($months_list2);exit;
                //print($free_chat);exit;
                $free_chat2 = $free_chat;
                foreach($months_list2 as $monther)
                {
                    $date_start = "{$monther}-01"; 
                    $date_end = date("Y-m-t", strtotime($date_start)); 
                    if($is_free_session_alloted)
                    {
                        $is_free_session_alloted = false;
                    }
                    else
                    {
                       $free_chat = $balance_credit;
                       $free_chat2 = $free_chat;
                    }
                    
                    $sel_billed_todateqry2 = "select ti.* from tbl_invoice ti where ti.bill_no!='' and ti.category in ('subscription-tool','Topup-Tool','Talk-Time-Tool')  and ti.status='0' and cost_center='$cost_center' and ti.invoiceDate>='$from_date' and ti.invoiceDate<='$to_date' and (  ti.invoiceDate >= '$date_start' AND ti.invoiceDate <= '$date_end')  "; #and bill_no!=''
                    //echo $sel_billed_todateqry2;exit;
                    $billed_todateqry_arr = $this->InitialInvoice->query($sel_billed_todateqry2);
                    $bill_no = $inv_det['ti']['bill_no'];
                    $bill_finyear = $inv_det['ti']['finance_year'];
                    $bill_company = $cost['cm']['company_name'];
                    $bill2_ledger +=$inv_det['ti']['grnd'];
                    $initial_id = $inv_det['ti']['id'];
                    $inv_date = $inv_det['ti']['invoiceDate'];
                    $carry_forward = 1;
                    //$bill_branch = $cost['cm']['branch'];     
                    $sub_days =$no_of_days*1;
                    $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 
                    
                    foreach($billed_todateqry_arr as $inv_det)
                    {
                        
                        $bill_no = $inv_det['ti']['bill_no'];
                        $bill_finyear = $inv_det['ti']['finance_year'];
                        $bill_company = $cost['cm']['company_name'];
                        $bill2_ledger +=$inv_det['ti']['grnd'];
                        $initial_id = $inv_det['ti']['id'];
                        $inv_date = $inv_det['ti']['invoiceDate'];
                        $carry_forward = 1;
                        //echo $inv_det['ti']['total'];exit;
                        //echo $inv_det['ti']['category'];exit;
                        //$bill_branch = $cost['cm']['branch'];     
                        $sub_days =$no_of_days*1;
                        $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 

                        if(strtolower($inv_det['ti']['category'])==strtolower('subscription-tool') )
                        {
                            $select_inv_session = "SELECT * FROM inv_particulars ip WHERE initial_id='$initial_id' and sub_category in ('Topup-Tool','Talk-Time-Tool')"; 
                            $inv_parts_arr = $this->InitialInvoice->query($select_inv_session);
                            foreach($inv_parts_arr as $parts)
                            {
                                $fr_release_credit +=$parts['ip']['amount'];
                            }
                            $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                            $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$fr_release_credit;
                            $flagSubsMade = false;
                        }
                        else if($carry_forward && strtolower($inv_det['ti']['category'])==strtolower('Topup-Tool') )
                        {
                            $fr_release_credit += $inv_det['ti']['total']; 
                        }
                        else if($carry_forward && strtolower($inv_det['ti']['category'])==strtolower('Talk-Time-Tool') )
                        {
                            $fr_release_credit += $inv_det['ti']['total'];
                        }
                        else
                        {

                            #$op2_value =$this->get_freesession_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
                            $value_in_subs_validity = $subs_validity["$inv_date#$to"];
                            $subs_validity["$inv_date#$to"] = $value_in_subs_validity+$op2_value;
                        }
                        #$fr_release_credit +=$op2_value;
                        // echo $fr_release_credit;exit;
                    }
                    $coll2_ledger = $this->get_collection_social($cost_center,$date_start,$date_end,$bill_company,$coll2_ledger,'new');
                    $date_capture_start = strtotime($date_start);
                    $consume_arr = $this->get_consumption_social($clientId,$date_start,$date_end);
                    //print_r($consume_arr);exit;
                    if(!empty($consume_arr))
                    {
                        while($date_capture_start<=strtotime($date_end))
                        {
                            $cap_date = date('Y-m-d',$date_capture_start);
                            $used_chat += $consume_arr[$cap_date]['total'];
                            if($free_chat<=0)
                            {
                                $consume_credit += $consume_arr[$cap_date]['total']*round($consume_arr[$cap_date]['rate'],3);
                                $chargable_chat += $consume_arr[$cap_date]['total'];
                            }
                            else
                            {
                                $free_chat -= $consume_arr[$cap_date]['total'];
                                if($free_chat<0)
                                {
                                    $consume_credit+= abs($free_chat)*round($consume_arr[$cap_date]['rate'],3);
                                    $chargable_chat += abs($free_chat);
                                    $free_chat = 0;
                                }
                            }
                            $date_capture_start =  strtotime($cap_date.' +1 days');
                        }
                    }
                    
                }
                
                
                //print_r($free_chat);exit;
                
                
                
                
                #print_r($credit_closing);exit;
                #echo $credit_closing;exit;
                $credit_closing = $op2_credit+$fr_release_credit-$consume_credit;
                $data[$clientName]['op2_ledger'] = $op2_ledger;
                $data[$clientName]['bill2_ledger'] = $bill2_ledger;
                $data[$clientName]['coll2_ledger'] = $coll2_ledger;
                $data[$clientName]['free_chat'] = $free_chat2; 
                $data[$clientName]['used_chat'] = $used_chat;
                $data[$clientName]['op2_credit'] = $op2_credit;
                $data[$clientName]['fr_release_credit'] = $fr_release_credit;
                $data[$clientName]['consume_credit'] = $consume_credit;
                $data[$clientName]['consume_credit_testing'] = $op2_consume_credit_testing;
                $data[$clientName]['closing_credit'] = $credit_closing;
                
                $sel_subs_qry = "select * from tbl_invoice ti where  cost_center='$cost_center' and category='subscription-tool' AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date' and bill_no!=''";
                $bill_subs_arr = $this->InitialInvoice->query($sel_subs_qry);
                $subs_coll = 0; $subs_coll2 = 0;
                foreach($bill_subs_arr as $inv)
                {
                    $data[$clientName]['subbilled'] += $inv['ti']['grnd'];
                }    
                
                #print_r($data);exit;
                //echo "$op2_credit-$consume_credit";exit; 

                
                


                $monthlyFreeValue = 0;

                $start_date1 = strtotime($start_date);
                
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
                else if(strtolower($PeriodType)==strtolower('Quarter'))
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

               // echo $subs_penP;exit;
                $sub_amt =round($RentalAmount*$subs_penP*1.18,2);
                //$subs_val = round($sub_amt-$subs_coll,2);
                $subs_val = round($sub_amt,2);
                //echo "{$data[$clientName]['status']}=='Active' && {$subs_val}>0 && strtotime({$today_date})>=strtotime({$start_date})";exit;
                if($data[$clientName]['status']=='Active' && $subs_val>0 && strtotime($today_date)>=strtotime($start_date))
                {
                     $last_month = $start_date;
                     $flag = true;
                     //echo "strtotime($last_month)<=strtotime($today_date) $subs_val"; exit;
                    while(strtotime($last_month)<=strtotime($today_date))
                    {
                        $last_month = date('Y-m-d',strtotime($last_month." +$no_of_days days"));  
                        //echo "{$last_month}>".date('Y-m-01')."{$last_month}<= {$today_date}"; exit;
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
                    else if($flagSubsMade)
                    {
                        $data[$clientName]['subs_val'] = $subs_val; 
                        $data[$clientName]['due_date'] = date('d-m-Y',strtotime($last_month." -1 days"));
                        $data[$clientName]['sub_start_date'] = $start_date; 
                        $data[$clientName]['sub_end_date'] =  date('Y-m-d',strtotime($start_date." +$no_of_days days"));
                    }

                }
                
                //print($data[$clientName]['subs_val']);exit;
            }
            
            
                
        }
        
    
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
            #$open_bal = round($record['op2_credit'],2);
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
    
    
    
    $this->set('data4',$data4);
    $this->set('month',$selectedMonth);
    $client_list =$this->DialdeeSharedRegistrationMaster->find('all',array('fields'=>array("company_id","company_name","auth_person"),'conditions'=>" `status` != 'CL'",'order'=>array('Company_name'=>'asc')));
    #$client = array('All'=>'All')+$client;
    #print_r($client);exit;
    $this->set('client_list',$client_list);
}

public function ajax_client_wallet() 
{
    //echo "site is under maintanance. will be live after 4 hours.";
    //exit;
    $this->layout='ajax';
    $data = array();
    $from_date = "";
    $to_date = "";
    $company_qry = "";
    
    $from_date1 = date('d-m-Y'); 
    $to_date1 = date('d-m-Y');          
    
    
    $explode_date = explode("-",$from_date1);
    //print_r($explode_date);
    $explode_date = array_reverse($explode_date);
    //print_r($explode_date);exit;
    $from_date = $from_date1; 
        
    $explode_date1 = explode("-",$to_date1);
    $explode_date1 = array_reverse($explode_date1);
    $to_date = $to_date1; 
    
    
    $company_id = $this->request->data['client']; 
    $client_status = $this->request->data['client_status'];
    $this->set('company_id',$company_id);
    $this->set('client_status',$client_status);
    $company_qry = "  company_id='$company_id'";
    
    
    
    
    $this->set('from_date',$from_date1);
    $this->set('to_date',$to_date1);
    
    $day_before = date( 'Y-m-d', strtotime( $from_date . ' -1 day' ) );
    $finYear = '2022-23';
    $year = date('Y');
    $year_2 = date('y');
    $arr = explode('-',$finYear);
    $selectedMonth = $this->request->data['month'];
    //$selectedMonth='Feb';
    $month_arr =array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,
        'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,
        'Oct'=>10,'Nov'=>11,'Dec'=>12) ;
    $month_arr_swap =array_flip($month_arr) ;
    $month_no = 0;
    
    if(empty($selectedMonth))
    {
        $Nmonth =$month = date( 'M', strtotime($from_date) );
        $month_no = $month_arr[$Nmonth];
    }
    else
    {
        $Nmonth =$month = $selectedMonth;
        $month_no = $month_arr[$selectedMonth]; 
    }
    
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
       $client_qry = "SELECT rm.* FROM registration_master rm 
         WHERE    $company_qry  ORDER BY status";
       //echo $client_qry;exit;
        $client_det_arr = $this->DialdeeSharedRegistrationMaster->query($client_qry);
        
        
        
        foreach($client_det_arr as $clr)
        {
            $clientId = $clr['rm']['company_id']; 
            $clientName = $clr['rm']['company_name'];
            $data[$clientName]['phone_no'] = $clr['rm']['phone_no'];
            $data[$clientName]['clientEmail'] = $clr['rm']['email'];
            #$createdate_for_client_to_first_bill = $clr['rm']['create_date'];
            $data[$clientName]['status'] = $this->get_client_status($clr['rm']['status']);
            
            
            $data[$clientName]['clientId'] = $clientId;
            $sel_balance_qry = "SELECT * FROM `plan_allocate_master` bm WHERE client_id='$clientId' limit 1";
            $bal_det=$this->DialdeeStandAloneRegistrationMaster->query($sel_balance_qry);
            $start_date = $bal_det['0']['bm']['plan_start_date']; 
            $plan_end_date2 = $bal_det['0']['bm']['plan_end_date']; 
            $plan_end_date = date("d-M-y", strtotime($plan_end_date2)); 
            $data[$clientName]['plan_status'] = $this->get_plan_status($start_date); 
            
            
            $op2_ledger = 0;
            $bill2_ledger = 0;
            $coll2_ledger = 0;
            $free_chat = 0;
            $free_chat2 = 0;
            $used_chat = 0;
            $chargable_chat = 0;
            $op2_credit = 0;
            $fr_release_credit = 0;
            $consume_credit = 0;
            $credit_closing = 0;
            $op2_consume_credit_testing = 0;
            //echo "SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$clientId'";exit;
            $op_det_ledger = 0;
            
            
            $planId = $bal_det['0']['bm']['plan_id'];
            $plan_mode = $bal_det['0']['bm']['plan_mode'];
            $sel_months_qry = "SELECT * FROM `rental_master` rm WHERE rental2='$plan_mode' LIMIT 1";
            $months_det = $this->DialdeeSharedRegistrationMaster->query($sel_months_qry);
            $total_months = $months_det['0']['rm']['months']; 
            $plan_det_qry = "SELECT tool_charge,wa_busi_intd_charge,wa_user_intd_charge,user_intd_free_sessions FROM `plan_master` pm WHERE id='$planId'  limit 1"; 
            $plan_det = $this->DialdeeStandAloneRegistrationMaster->query($plan_det_qry);
            #$data[$clientName]['InChatCharge'] = $plan_det['0']['pm']['wa_user_intd_charge'];
            $data[$clientName]['InChatCharge'] = 1;
            $data[$clientName]['OutChatCharge'] = 1;
            //$FreeValue = $plan_det['0']['pm']['CreditValue'];
            $tool_charge = $plan_det['0']['pm']['tool_charge'];
            $RentalAmount = $tool_charge*$total_months;
            $data[$clientName]['RentalAmount'] = $RentalAmount;            
            $end_date = $to_date;                                    
            $PeriodType = $months_det['0']['rm']['rental3'];                         
            $rental_credit = 0;
            $balance_credit = $plan_det['0']['pm']['user_intd_free_sessions']; 
            
            $no_of_days = $months_det['0']['rm']['days1']; 
            $pamnt = $RentalAmount;                
            $cost_cat_arr = $this->get_cost_center($clientId);
            $flagSubsMade = true;
            #print_r($cost_cat_arr);
            
                    
                 
                
            
            foreach($cost_cat_arr as $cost)
            {
                $cost_center = $cost['cm']['cost_center'];  
                $data[$clientName]['cost_center'] = $cost_center;
                $data[$clientName]['month'] = $month;
                $bill_company = $cost['cm']['company_name'];
                $subs_validity = array();
                $is_free_session_alloted = false;
                //echo $to_date;
                $months_list1 = $this->get_months_btw_dates($start_date,$from_date);
                //print_r($months_list1);exit;
                foreach($months_list1 as $monther)
                {
                    $date_start = "{$monther}-01"; 
                    $date_end = date("Y-m-t", strtotime($date_start)); 
                    if(strtotime($date_end)>=strtotime($to_date))
                    {
                        $date_end = $from_date; 
                        $is_free_session_alloted = true;
                    }
                    $free_chat = $balance_credit;
                    $sel_billed_todateqry3 = "select ti.* from tbl_invoice ti where ti.bill_no!='' and ti.category in ('subscription-tool','Topup-Tool','Talk-Time-Tool')  and ti.status='0' and cost_center='$cost_center' and ti.invoiceDate>='$start_date' and ti.invoiceDate<'$from_date' and (  ti.invoiceDate >= '$date_start' AND ti.invoiceDate < '$date_end')  "; #and bill_no!=''
                    //echo $sel_billed_todateqry3;exit;
                    $billed_todateqry_arr = $this->InitialInvoice->query($sel_billed_todateqry3);
                    $bill_no = $inv_det['ti']['bill_no'];
                    $bill_finyear = $inv_det['ti']['finance_year'];
                    $bill_company = $cost['cm']['company_name'];
                    $bill2_ledger +=$inv_det['ti']['grnd'];
                    $initial_id = $inv_det['ti']['id'];
                    $inv_date = $inv_det['ti']['invoiceDate'];
                    $carry_forward = 1;
                    //$bill_branch = $cost['cm']['branch'];     
                    $sub_days =$no_of_days*1;
                    $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 
                    
                    foreach($billed_todateqry_arr as $inv_det)
                    {
                        $bill_no = $inv_det['ti']['bill_no'];
                        $bill_finyear = $inv_det['ti']['finance_year'];
                        $bill_company = $cost['cm']['company_name'];
                        $bill2_ledger +=$inv_det['ti']['grnd'];
                        $initial_id = $inv_det['ti']['id'];
                        $inv_date = $inv_det['ti']['invoiceDate'];
                        $carry_forward = 1;
                        //$bill_branch = $cost['cm']['branch'];     
                        $sub_days =$no_of_days*1;
                        $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 

                        if(strtolower($inv_det['ti']['category'])==strtolower('subscription-tool') )
                        {
                            $select_inv_session = "SELECT * FROM inv_particulars ip WHERE initial_id='$initial_id' and sub_category in ('Topup-Tool','Talk-Time-Tool')"; 
                            $inv_parts_arr = $this->InitialInvoice->query($select_inv_session);
                            foreach($inv_parts_arr as $parts)
                            {
                                $op2_credit +=$parts['ip']['amount'];
                            }
                            $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                            $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$fr_release_credit;
                            $flagSubsMade = false; 
                        }
                        else if($carry_forward && strtolower($inv_det['ti']['category'])==strtolower('Topup-Tool') )
                        {
                            $op2_credit += $inv_det['ti']['total'];
                        }
                        else if($carry_forward && strtolower($inv_det['ti']['category'])==strtolower('Talk-Time-Tool') )
                        {
                            $op2_credit += $inv_det['ti']['total'];
                        }
                        else
                        {

                            #$op2_value =$this->get_freesession_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
                            $value_in_subs_validity = $subs_validity["$inv_date#$to"];
                            $subs_validity["$inv_date#$to"] = $value_in_subs_validity+$op2_value;
                        }
                        #$fr_release_credit +=$op2_value;

                    }
                    $coll2_ledger = $this->get_collection_social($cost_center,$date_start,$date_end,$bill_company,$coll2_ledger,'new');
                    $date_capture_start = strtotime($date_start);
                    $consume_arr = $this->get_consumption_social($clientId,$date_start,$date_end);
                    #print_r($consume_arr);
                    if(!empty($consume_arr))
                    {
                        while($date_capture_start<=strtotime($date_end))
                        {
                            $cap_date = date('Y-m-d',$date_capture_start);
                            #$used_chat += $consume_arr[$cap_date]['total'];
                            if($free_chat<=0)
                            {
                                $op2_credit -= $consume_arr[$cap_date]['total']*round($consume_arr[$cap_date]['rate'],3);
                                $chargable_chat += $consume_arr[$cap_date]['total'];
                            }
                            else
                            {
                                $free_chat -= $consume_arr[$cap_date]['total'];
                                if($free_chat<0)
                                {
                                    $op2_credit-= abs($free_chat)*round($consume_arr[$cap_date]['rate'],3);
                                    $chargable_chat += abs($free_chat);
                                    $free_chat = 0;
                                }
                            }
                            $date_capture_start =  strtotime($cap_date.' +1 days');
                        }
                    }
                    
                    //print_r("$cap_date /$free_chat");
                    //echo '<br/>';
                }
                #print_r($op2_credit);exit;
                //echo "$from_date/$to_date";exit;
                $months_list2 = $this->get_months_btw_dates($to_date,$from_date);
                //print_r($months_list2);exit;
                //print($free_chat);exit;
                $free_chat2 = $free_chat;
                foreach($months_list2 as $monther)
                {
                    $date_start = "{$monther}-01"; 
                    $date_end = date("Y-m-t", strtotime($date_start)); 
                    if($is_free_session_alloted)
                    {
                        $is_free_session_alloted = false;
                    }
                    else
                    {
                       $free_chat = $balance_credit;
                       $free_chat2 = $free_chat;
                    }
                    
                    $sel_billed_todateqry4 = "select ti.* from tbl_invoice ti where ti.bill_no!='' and ti.category in ('subscription-tool','Topup-Tool','Talk-Time-Tool')  and ti.status='0' and cost_center='$cost_center' and ti.invoiceDate>='$from_date' and ti.invoiceDate<='$to_date' and (  ti.invoiceDate >= '$date_start' AND ti.invoiceDate <= '$date_end')  "; #and bill_no!=''
                    //echo $sel_billed_todateqry4;exit;
                    $billed_todateqry_arr = $this->InitialInvoice->query($sel_billed_todateqry4);
                    $bill_no = $inv_det['ti']['bill_no'];
                    $bill_finyear = $inv_det['ti']['finance_year'];
                    $bill_company = $cost['cm']['company_name'];
                    $bill2_ledger +=$inv_det['ti']['grnd'];
                    $initial_id = $inv_det['ti']['id'];
                    $inv_date = $inv_det['ti']['invoiceDate'];
                    $carry_forward = 1;
                    //$bill_branch = $cost['cm']['branch'];     
                    $sub_days =$no_of_days*1;
                    $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 
                    
                    foreach($billed_todateqry_arr as $inv_det)
                    {
                        
                        $bill_no = $inv_det['ti']['bill_no'];
                        $bill_finyear = $inv_det['ti']['finance_year'];
                        $bill_company = $cost['cm']['company_name'];
                        $bill2_ledger +=$inv_det['ti']['grnd'];
                        $initial_id = $inv_det['ti']['id'];
                        $inv_date = $inv_det['ti']['invoiceDate'];
                        $carry_forward = 1;
                        //$bill_branch = $cost['cm']['branch'];     
                        $sub_days =$no_of_days*1;
                        $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 

                        if(strtolower($inv_det['ti']['category'])==strtolower('subscription-tool') )
                        {
                            $select_inv_session = "SELECT * FROM inv_particulars ip WHERE initial_id='$initial_id' and sub_category in ('Topup-Tool','Talk-Time-Tool')"; 
                            $inv_parts_arr = $this->InitialInvoice->query($select_inv_session);
                            foreach($inv_parts_arr as $parts)
                            {
                                $fr_release_credit +=$parts['ip']['amount'];
                            }
                            $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                            $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$fr_release_credit;
                            $flagSubsMade = false;
                        }
                        else if($carry_forward && strtolower($inv_det['ti']['category'])==strtolower('Topup-Tool') )
                        {
                            $fr_release_credit += $inv_det['ti']['total'];
                        }
                        else if($carry_forward && strtolower($inv_det['ti']['category'])==strtolower('Talk-Time-Tool') )
                        {
                            $fr_release_credit += $inv_det['ti']['total'];
                        }
                        else
                        {

                            #$op2_value =$this->get_freesession_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
                            $value_in_subs_validity = $subs_validity["$inv_date#$to"];
                            $subs_validity["$inv_date#$to"] = $value_in_subs_validity+$op2_value;
                        }
                        #$fr_release_credit +=$op2_value;

                    }
                    $coll2_ledger = $this->get_collection_social($cost_center,$date_start,$date_end,$bill_company,$coll2_ledger,'new');
                    $date_capture_start = strtotime($date_start);
                    $consume_arr = $this->get_consumption_social($clientId,$date_start,$date_end);
                    //print_r($consume_arr);exit;
                    if(!empty($consume_arr))
                    {
                        while($date_capture_start<=strtotime($date_end))
                        {
                            $cap_date = date('Y-m-d',$date_capture_start);
                            $used_chat += $consume_arr[$cap_date]['total'];
                            if($free_chat<=0)
                            {
                                $consume_credit += $consume_arr[$cap_date]['total']*round($consume_arr[$cap_date]['rate'],3);
                                $chargable_chat += $consume_arr[$cap_date]['total'];
                            }
                            else
                            {
                                $free_chat -= $consume_arr[$cap_date]['total'];
                                if($free_chat<0)
                                {
                                    $consume_credit+= abs($free_chat)*round($consume_arr[$cap_date]['rate'],3);
                                    $chargable_chat += abs($free_chat);
                                    $free_chat = 0;
                                }
                            }
                            $date_capture_start =  strtotime($cap_date.' +1 days');
                        }
                    }
                    
                }
                
                
                //print_r($free_chat);exit;
                
                
                
                
                #print_r($credit_closing);exit;
                #echo $credit_closing;exit;
                $credit_closing = $op2_credit+$fr_release_credit-$consume_credit;
                $data[$clientName]['op2_ledger'] = $op2_ledger;
                $data[$clientName]['bill2_ledger'] = $bill2_ledger;
                $data[$clientName]['coll2_ledger'] = $coll2_ledger;
                $data[$clientName]['free_chat'] = $free_chat2; 
                echo json_encode(array('detail'=>$company_id,'wallet'=>$credit_closing,'free_chat'=>$free_chat,'wallet_expires_on'=>$plan_end_date)); exit;
                
            }
            
            
                
        }
        
        echo json_encode(array('wallet'=>'0.00','free_chat'=>'0','wallet_expires_on'=>"Plan or Cost Center Not Created. $clientId")); exit;
    
    
    
}


public function ajax_add_bill_tool() 
{
    $this->layout='ajax';
    
    $result = $this->params->query;
    
    //print_r($result);exit;
    $cost_center = $result['cost_center'];
    $start_date = $result['sub_start_date'];
    $end_date = $result['sub_end_date'];
    $due_date = $result['due_date'];
    $rate = $result['rate'];
    $qty = $result['qty'];
    $amt = $rate*$qty;
    $username=$result['admin_id'];
    $this->AddInvParticular->deleteAll(array('username'=>$username));
    $month = date('M');
    
    $finYear = '2023-24';
    if(in_array($month,array('Jan','Feb','Mar')))
    {
        $finYear = (date('Y')-1).'-'.(date('y'));
        #$finYear = '2022-23';
        
    }
    else
    {
        $finYear = date('Y').'-'.(date('y')+1);
        #$finYear = '2022-23';
    }
    
    $cost_det = $this->CostCenterMaster->find('first',array('conditions'=>"cost_center='$cost_center'"));
    $cost_id = $cost_det['CostCenterMaster']['id'];
    $branch_name = $cost_det['CostCenterMaster']['branch'];
    
    
    if(empty($cost_center))
    {
        echo "<script>alert('Please Create Cost Master First');</script>";
        exit;
    }
    
    if($result['bill_type']=='talk-time-tool')
    {
        $particulars = 'Talk-Time-Tool';  
        
        $data = array();
        $due_date = 'Immediate';
        $data['AddInvParticular']['cost_center_id'] = $cost_id;
        $data['AddInvParticular']['username'] = $username;
        $data['AddInvParticular']['branch_name'] = $branch_name;
        $data['AddInvParticular']['cost_center'] = $cost_center;
        $data['AddInvParticular']['fin_year'] = $finYear;
        $data['AddInvParticular']['month_for'] = $month;
        $data['AddInvParticular']['particulars'] = $particulars;
        $data['AddInvParticular']['sub_category'] = 'Talk-Time-Tool';
        $data['AddInvParticular']['rate'] = round($rate,2);
        $data['AddInvParticular']['qty'] = $qty;
        $data['AddInvParticular']['amount'] = round($amt,2);
        $this->AddInvParticular->save($data);
    }
    
    
    else
    {
        $particulars = 'Subscription-Tool';
        $data = array();
        $data['AddInvParticular']['cost_center_id'] = $cost_id;
        $data['AddInvParticular']['username'] = $username;
        $data['AddInvParticular']['branch_name'] = $branch_name;
        $data['AddInvParticular']['cost_center'] = $cost_center;
        $data['AddInvParticular']['fin_year'] = $finYear;
        $data['AddInvParticular']['month_for'] = $month;
        $data['AddInvParticular']['particulars'] = "For Subscription of Tool From ". date_format(date_create($start_date),"d-M-Y")." to ". date_format(date_create($end_date),"d-M-Y")."";
        $data['AddInvParticular']['sub_category'] = 'subscription-tool';
        $data['AddInvParticular']['rate'] = round($rate,2);
        $data['AddInvParticular']['qty'] = 1;
        $data['AddInvParticular']['amount'] = round($rate,2);
    $this->AddInvParticular->save($data);
    }
    
    
    
    
    
    return $this->redirect(array('controller'=>'InitialInvoices','action' => "add2?finance_year=$finYear&month=$month&invoiceType=revenue&category=$particulars&servicetax=1&branch_name=$branch_name&cost_center=$cost_center&start_date=$start_date&end_date=$end_date&due_date=$due_date"));
}

public function ajax_add2() 
{    
    //print_r($this->request->query);exit;
    $fin_year = $this->request->query['finance_year'];
    $month    =   $this->request->query['month'];
    $invoiceType = $this->request->query['invoiceType'];
    $category = $this->request->query['category'];
    $start_date    =   $this->request->query['start_date'];
    $end_date = $this->request->query['end_date'];
    $due_date = $this->request->query['due_date'];
    $arr =explode('-',$fin_year);
    

    if(in_array($month,array('Jan','Feb','Mar')))
    {
        if($arr[0]==date('Y') || $arr[1]==date('y'))
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

    $NewMonth = $month;
    

   krsort($invDate);
   $invDate=implode("-",$invDate);
    $this->layout='ajax';
    $serviceTax = $this->params->query['servicetax'];
    //$data = $this->params->query['InitialInvoice'];

    $username=$this->params->query['username'];
    $b_name=$this->params->query['branch_name'];
    $cost_center=$this->params->query['cost_center'];

    $dataX=$this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$cost_center)));

    

    $this->set('category',$category);
    $this->set('invoiceType',$invoiceType);
    $this->set('cost_master',$dataX);
    $this->set('tmp_particulars',$this->AddInvParticular->find('all',array('conditions'=>array('username' => $username))));
    $this->set('tmp_deduct_particulars',$this->AddInvDeductParticular->find('all',array('conditions'=>array('username' => $username))));
    $this->set('username', $username);
    $this->set('start_date', $start_date);
    $this->set('end_date', $end_date);
    $this->set('due_date', $due_date);
}

public function bill_approval() 
{
    $this->layout='ajax';
    
    
    if ($this->request->is('post')) 
    {
        $username=$this->request->data("username");
        $userid = $this->request->data("username");
    
        
        $checkTotal = 0;	
        $result=$this->request->data['InitialInvoice'];
        $Revenue = $result['revenue'];
        $RevenueMonthArr = $result['revenue_arr'];
        $category = $result['category'];
        //print_r($result);  exit;
        $Transaction = $this->User->getDataSource();
        $Transaction->begin();
        
        //////////////////////    Fetching Variables ///////////////////////////
        $date = date('Y-m-d H:i:s');
        $result['createdate'] = $date;
        $branch_name = $b_name=$result['branch_name'];
        $cost_center = $result['cost_center'];
        $desc = $result['invoiceDescription'];
        $invoiceDate = $result['invoiceDate'];
        $tax_call = $result['app_tax_cal'];
        $serviceTax=$result['apply_service_tax'];
        $apply_krishi_tax = $result['apply_krishi_tax'];
        $apply_gst = $result['apply_gst'];
        $grnd = $result['grnd'];				
        //$amount=$result['total'];
        $month = $result['month'];
        $finYear = $result['finance_year'];
        $result['finance_year']=$finYear;
        $arr_month = explode("-",$finYear);
        ///////////////////    Fetching Variables Ends Here ////////////////////
        
        
        //////////////////     Making Month Variables      /////////////////////
        $arr =explode('-',$result['finance_year']);
        if(in_array($month,array('Jan','Feb','Mar')))
        {
            if($arr[0]==date('Y') || $arr[1]==date('y'))
            {
                $result['month']=$result['month']."-".date('y');
            }
            else
            {
                $result['month']=$result['month']."-".$arr[1];
            }
        }
        else
        {
            $result['month']=$result['month']."-".($arr[1]-1);
        }
        $result['username']=$username;
        $NewMonth = $result['month'];
        $RevenueMonthArr[$month] = $NewMonth;
        foreach($RevenueMonthArr as $mnt=>$mntValue)
        {
            $amt = 0;
            if(in_array($mnt,array('Jan','Feb','Mar')))
            {
                if($arr_month[0]==date('Y') || $arr_month[1]==date('y'))
                {
                    $Nmonth=$mnt."-".date('y');
                }
                else
                {
                    $Nmonth=$mnt."-".$arr_month[1];
                }
            }
            else
            {
                $Nmonth=$mnt."-".($arr_month[1]-1); 
            }
            
            //echo "finance_year='$year' and month='$Nmonth' and branch_name='$branch_name' and cost_center='$cost_center' and provision_balance!=0";  exit;
            $prov_ = $this->Provision->find('first',array('conditions'=>"finance_year='$finYear' and month='$Nmonth' and branch_name='$b_name' and cost_center='$cost_center'"));
            if(!empty($prov_))
            {
                $amt = $prov_['Provision']['provision'];
                $ProvisionId = $prov_['Provision']['id'];
            }
            
            /*$out_source_master = $this->ProvisionPart->query("Select * from provision_particulars pp where FinanceYear='$finYear' and FinanceMonth='$Nmonth' and Branch_OutSource='$b_name' and Cost_Center_OutSource='$cost_center' ");
            foreach($out_source_master as $osm)
            {
                $amt += round($osm['pp']['outsource_amt'],2); 
            }*/
            
            /*
            $prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where Provision_Finance_Year='$finYear' and Provision_Finance_Month='$Nmonth' and Provision_Branch_Name='$b_name' and Provision_Cost_Center='$cost_center' and deduction_status='1'");
            foreach($prov_deduction as $pd)
            {
                $amt -= round($pd['pmmd']['ProvisionBalanceUsed'],2);
            }*/
            
            
            
            if($amt<$mntValue)
            {
               // $this->Session->setFlash("Revenue is Smaller Then Invoice");
               // return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
               // break;
            }
            else
            {
                $ProvisionArray[$mnt] = $ProvisionId;
                $ProvisionBalArray[$mnt] = $amt-$mntValue;
            }
        }
        
        $amount = 0;
        
        ////////////////  Getting All Amount Of Created Bill Ends Here /////////
        
        ////////////////  Getting Amount Of Bill Is Going To Create ////////////
        $particular = $this->params['data']['Particular'];
        $k=array_keys($particular);$i=0;

       // print_r($particular); exit;
        
        foreach($particular as $post)
        {    
            $dataX['particulars']="'".addslashes($post['particulars'])."'";
            $dataX['qty']="'".$post['qty']."'";
            $dataX['rate']="'".$post['rate']."'";
            $dataX['amount']="'".$post['amount']."'";
            $amount += $post['amount'];
            $this->AddInvParticular->updateAll($dataX,array('id'=>$k[$i++]));
        } 
        unset($dataX);
        
        $flag=false;
        if(isset($this->params['data']['DeductParticular']))
        {
            $deductparticular = $this->params['data']['DeductParticular'];
            $k=array_keys($deductparticular);$i=0;
            foreach($deductparticular as $post)
            {
                $dataX['particulars'] = "'".addslashes($post['particulars'])."'";
                $dataX['qty']="'".$post['qty']."'";
                $dataX['rate']="'".$post['rate']."'";
                $dataX['amount']="'".$post['amount']."'";
                $amount -= $post['amount'];
                $this->AddInvDeductParticular->updateAll($dataX,array('id'=>$k[$i++]));
            }
            $flag=true;
        }
        
    ////////////////  Getting Amount Of Bill Is Going To Create Ends Here///////
                    
    
        ///////////// CalCulating Tax //////////////////////////////////////////
        $total =$amount;
        $sbctax = 0;
        $tax = 0;
        $krishiTax = 0;
        $igst = 0;
        $cgst = 0;
        $sgst = 0;

        if($tax_call=='1')
        {
            if($result['GSTType']=='Integrated')
            {
                $igst = round($amount*0.18,2);
            }
            else
            {
                $cgst = round($amount*0.09,2);
                $sgst = round($amount*0.09,2);
            }   
        }

        if($serviceTax=='1')
        {$total = 0;$TotTotalY += 0;}
        else
        {
            $TotTotalY += $amount;
        }
        $grnd = round($total +$igst+$cgst+$sgst,2);
        $result['total'] = $total;
        
        
        $result['igst'] = $igst;
        $result['sgst'] = $sgst;
        $result['cgst'] = $cgst;
        
        
        $result['grnd'] = $grnd;
        
        
        

        ////////////////////  Checking Bill Amount is Less Than Provision Amount ///
        
        /*if(intval($Revenue)<intval($TotTotalY))
        {
            $Transaction->rollback();
            $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style='color:#FF0000'>".'The Bill Amount is Not More Than Provision Amount'."</b></h4>"));
            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
        }*/
        
    ////////////////////  Checking Provision Amount is Less Than Bill Amount Ends Here ///
    
     
        
    ///////////////////   Creating Proforma No From Here ///////////////////////
    $bill = $this->BillMaster->query("SELECT MAX(proforma_bill_no) proforma_bill_no from bill_no_master where id=1");
    $data=$this->Addbranch->find('first',array('conditions'=>array('branch_name'=>$b_name)));
    $b_name=$data['Addbranch']['branch_code'];
    $state_code = $data['Addbranch']['state_code'];

    $Transaction->query("Lock TABLES tbl_invoice READ");  //bill no master table not be read by other tables
    $idx = $bill['0']['0']['proforma_bill_no']+1;
    $proforma_no = 'PI/'.$state_code.'/'.$idx; 
    $Transaction->query("UNLOCK TABLES"); //unlock to update table
    
    $result['proforma_bill_no']=$proforma_no; 
    //print_r($result);exit;
    ///////////////////   Creating Proforma No Ends Here ///////////////////////
    $cost_det_plan = $this->CostCenterMaster->find('first',array("conditions"=>"cost_center='$cost_center'"));
    $dialdesk_client_id = $cost_det_plan['CostCenterMaster']['dialdesk_client_id'];
    #$plan_det = $this->PlanMaster->query("select id from billing_master bm where clientId='$dialdesk_client_id' limit 1");
    #$plan_id = $plan_det['0']['bm']['id'];
    #$result['plan_id']=$plan_id; 
    
        if ($this->InitialInvoice->save($result))
        {

            $id=$this->InitialInvoice->getLastInsertID(); //Getting Last Insert Id From Table
            $RevenueMonthArr[$month] = $NewMonth;
            foreach($RevenueMonthArr as $mnt=>$mntValue)
            {
                if(in_array($mnt,array('Jan','Feb','Mar')))
                {
                    if($arr_month[0]==date('Y') || $arr_month[1]==date('y'))
                    {
                        $Nmonth=$mnt."-".date('y');
                    }
                    else
                    {
                        $Nmonth=$mnt."-".$arr_month[1];
                    }

                }
                else
                {
                    $Nmonth=$mnt."-".($arr_month[1]-1); 
                }

                if(!$this->ProvisionPartDed->saveAll(array('ProvisionPartDed'=>array('ProvisionId'=>$ProvisionArray[$mnt],'Provision_Finance_Year'=>$finYear,'Provision_Finance_Month'=>$Nmonth,'Provision_Branch_Name'=>$branch_name,'Provision_Cost_Center'=>$cost_center,'Provision_UsedBy_Month'=>$NewMonth,'ProvisionBalanceUsed'=>$mntValue,'InvoiceId'=>$id,'deduction_status'=>1,'created_at'=>$date,'created_by'=>$userid))))
                {
                    $Transaction->rollback();
                }

                /////  Updating Provision Balance Starts From Here /////////////////
                if(!$this->Provision->updateAll(array('provision_balance'=>"'{$ProvisionBalArray[$mnt]}'"),array('cost_center'=>$cost_center,'month'=>$Nmonth,'finance_year'=>$finYear)))
                { 
                    $Transaction->rollback();
                    echo json_encode(array('status'=>false,'msg'=>'','error'=>'Provision Not Updated. Please Try Again'));exit;
                }
                //// Updating Provision Balance Ends Here  /////////////////////////////
            }

            ////// Updating Proforma No in BillMaster //////////////////////////
            if(!$this->BillMaster->updateAll(array('proforma_bill_no'=>$idx),array('Id'=>"1"))) 
            { 
                $Transaction->rollback();
                echo json_encode(array('status'=>false,'msg'=>'','error'=>'Proforma No. Not Updated. Please Try Again'));exit;
            }
            ////// Updating Proforma No in BillMaster Ends Here/////////////////





            //// Moving Particular Table From Temp To Here//////////////////////////
            $res=$this->AddInvParticular->find('all',array('conditions'=>array('username'=>$username)));
            foreach ($res as $post)
            {
                $post['AddInvParticular']['initial_id']=$id;
                $post['AddInvParticular']=Hash::remove($post['AddInvParticular'],'id');
                $this->Particular->saveAll($post['AddInvParticular']);
            }

            $this->AddInvParticular->deleteAll(array('username'=>$username));

            $res=$this->AddInvDeductParticular->find('all',array('conditions'=>array('username'=>$username)));
                    foreach ($res as $post):
                    $post['AddInvDeductParticular']['initial_id']=$id;
                    $post['AddInvDeductParticular']=Hash::remove($post['AddInvDeductParticular'],'id');
                    $this->DeductParticular->saveAll($post['AddInvDeductParticular']);
                    endforeach;					
            $this->AddInvDeductParticular->deleteAll(array('username'=>$username));
            //// Moving Particular Table From Temp To Here Ends ////////////////////
            if($category=='first_bill')
            {
                $cost_det = $this->CostCenterMaster->find('first',array('conditions'=>array('cost_center'=>$cost_center)));
               $client_id = $cost_det['CostCenterMaster']['dialdesk_client_id'];
                if(!$RegistrationMaster = $this->RegistrationMaster->updateAll(array('first_bill'=>'1'),array("company_id"=>"$client_id")))
                {
                    $Transaction->rollback();
                    echo json_encode(array('status'=>false,'msg'=>'','error'=>'Bill No. not made. Please Try Again'));exit;
                }
            }
            $Transaction->commit();

            $msg = ' The Proforma Invoice '.$proforma_no.' for amount '.$amount.' to '.$b_name.' has been saved';

            echo json_encode(array('status'=>true,'msg'=>$msg,'error'=>''));exit;
        }
        else
        {
            $Transaction->rollback();
            echo json_encode(array('status'=>false,'msg'=>$msg,'error'=>'The Initial Invoice could not be saved. Please Try Again.'));exit;
        } 
    }
}

public function view_bill()
{	
    $username=$this->request->query("username");
    $id  = $this->request->query['id'];
    $back_url  = $this->request->query['back_url'];
    $this->set('back_url', $back_url);
    $this->layout='ajax';
    $data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'bill_no'=>'','status'=>0)));

    $b_name=$data['InitialInvoice']['branch_name'];
    $c_center=$data['InitialInvoice']['cost_center'];

    $this->set('tbl_invoice', $data);
    $this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,
        'cost_center'=>$c_center))));
    $this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
    $this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
}

public function edit_proforma()
{
        //$username=$this->Session->read("admin_id");
        //$branch_name=$this->Session->read("branch_name");
        
        $id  = $this->request->query['id'];
        $this->layout='ajax';
        $data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'bill_no'=>'')));
        $b_name=$data['InitialInvoice']['branch_name'];
        $c_center=$data['InitialInvoice']['cost_center'];

       /* $prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where InvoiceId= '$id'");
    $ActualRevenue = array(); 
    foreach($prov_deduction as $pd)
    {
        $ProvisionId = $pd['pmmd']['ProvisionId'];
        $revenue += round($pd['pmmd']['ProvisionBalanceUsed'],2);
        $monthMaster[$pd['pmmd']['Provision_Finance_Month']] = round($pd['pmmd']['ProvisionBalanceUsed'],2);
        $ActualProvArr = $this->Provision->find('first',array('conditions'=>"Id='$ProvisionId'"));
        $ActualRevenue[$pd['pmmd']['Provision_Finance_Month']]=  round($ActualProvArr['Provision']['provision_balance'],2) + round($pd['pmmd']['ProvisionBalanceUsed'],2);
    }*/


        
        $this->set('revenue',$revenue);
        $this->set('monthMaster',$monthMaster);
        $this->set('ActualRevenue',$ActualRevenue);
        $this->set('tbl_invoice', $this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id))));
        $this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center))));
        $this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
        $this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));

}

public function update_proforma()
{
    $this->layout='ajax';
    #$roles=explode(',',$this->Session->read("page_access"));
    #$role=$this->Session->read("role");

    if ($this->request->is('post'))
    {
        //print_r($this->request->data['InitialInvoice']); die;

        $invoiceDate = $this->request->data['InitialInvoice']['invoiceDate']; 
        $id = $this->request->data['InitialInvoice']['id']; 
        if(!empty($this->request->data['Reject']))
        {
            $Transaction = $this->InitialInvoice->getDataSource(); $flag = true;
            $Transaction->begin();

            if($this->InitialInvoice->updateAll(array('status'=>"'1'",'InvoiceRejectBy'=>$this->Session->read('userid'),'InvoiceRejectDate'=>"'".date('Y-m-d H:i:s')."'"),array('id'=>$id)))
            {
                $Transaction->commit();
                echo json_encode(array('status'=>true,'msg'=>'Invoice Rejected Successfully','error'=>''));exit;
            }
            else
            {                
                $Transaction->rollback();
                echo json_encode(array('status'=>false,'msg'=>'','error'=>'Invoice  Reject Request Failed. Please Contact To Admin'));exit;
                
            }
        }
        else
        {
            $checkTotal = 0;
            $findData = $this->InitialInvoice->find('first',array('conditions'=>array('Id'=>$id)));

            $findCostCenter     = $findData['InitialInvoice']['cost_center']; 
            $findFinanceYear    = $findData['InitialInvoice']['finance_year'];
            $findMonth          = $findData['InitialInvoice']['month']; 
            ////////////////  Getting All Amount Of Created Bill Ends Here /////////

            $Transaction = $this->User->getDataSource();
            $Transaction->begin();

            $particular = $this->params['data']['Particular'];
            $k=array_keys($particular);$i=0;

            foreach($particular as $post){
                $dataX['particulars']="'".addslashes($post['particulars'])."'";
                $dataX['qty']="'".$post['qty']."'";
                $dataX['rate']="'".$post['rate']."'";
                $dataX['amount']="'".$post['amount']."'";
                $checkTotal += $post['amount'];
                if(!$this->Particular->updateAll($dataX,array('id'=>$k[$i++])))
                {
                    $Transaction->rollback(); 
                    echo json_encode(array('status'=>false,'msg'=>'','error'=>'Particulars Not Added Please Try Again'));exit;
                    
                    
                }

            }
            unset($dataX);

            $flag=false;
            
            $findInvAmt = $this->InitialInvoice->query("select sum(amount) total from inv_particulars where initial_id='$id'");
            $findSumPrv = $findInvAmt['0']['0']['total'];

            $findDInvAmt = $this->InitialInvoice->query("select sum(amount) total from inv_deduct_particulars where initial_id='$id'");
            $findSumDPrv = round($findDInvAmt['0']['0']['total']);

            $findTotalProvAmt = $this->InitialInvoice->query("select sum(total)total from tbl_invoice where id!='$id' and cost_center='$findCostCenter' and finance_year='$findFinanceYear' and `month`='$findMonth' and `status`='0'");
            $findTotalBillMade = round($findSumPrv,2)-round($findSumDPrv,2);

            $data=Hash::remove($findData['InitialInvoice'],'id');
            $data=Hash::remove($data,'revenue_arr');
            $data=Hash::remove($data,'revenue_str');
            $data=Hash::remove($data,'revenue');

            $b_name=$data['branch_name'];
            $amount=$data['total'];
            $data=Hash::remove($data,'branch_name');


            $date = $data['invoiceDate']; 

            $date = $invoiceDate; //( user date input value)

            $date = date_create($date);
            $date = date_format($date,"Y-m-d");

            $dataN['po_no']="'".addslashes($data['po_no'])."'";
            $dataN['invoiceDescription']="'".addslashes($data['invoiceDescription'])."'";
            $dataN['month']="'".addslashes($data['month'])."'";
            $dataN['cost_center']="'".addslashes($data['cost_center'])."'";
            $dataN['finance_year']="'".addslashes($data['finance_year'])."'";
            $dataN['invoiceDate'] = "'".addslashes($date)."'";
            $dataN['GSTType'] = "'".addslashes($data['GSTType'])."'";
            $apply_gst = $data['apply_gst'];
            $dataN['apply_gst'] = "'".$data['apply_gst']."'";
            $dataN['apply_service_tax'] = "'".$data['apply_service_tax']."'";

            $dataA = $this->InitialInvoice->find('first',array('fields'=>array('total','cost_center','month','finance_year'),'conditions'=>array('id'=>$id)));
            $dataY = $this->InitialInvoice->find('first',array('fields'=>array('app_tax_cal','total','bill_no'),'conditions'=>array('id' => $id)));
            $tax_call = $dataY['InitialInvoice']['app_tax_cal'];

            if($dataY['InitialInvoice']['total'] != $data['total'])
            {
                $dataZ['initial_id'] = $id;
                $dataZ['bill_no'] = $dataY['InitialInvoice']['bill_no'];
                $dataZ['old_amount'] = $dataY['InitialInvoice']['total'];
                $dataZ['new_amount'] = $data['total'];
                $dataZ['createdate'] = date('Y-m-d H:i:s');
                $this->EditAmount->save($dataZ);
            }

            if ($this->InitialInvoice->updateAll($dataN,array('id'=>$id)))
            {

                $total = $checkTotal;
                $total = round($checkTotal,2);
                $tax = 0;
                $sbctax = 0;
                $krishiTax = 0;
                $igst = 0;
                $cgst = 0;
                $sgst = 0;

                if($tax_call == '1')
                {
                    $tax=0;$krishiTax=0;$sbctax=0;
                    if($this->params['data']['InitialInvoice']['GSTType']=='Integrated')
                    {
                        $igst = round($checkTotal*0.18,2);
                    }
                    else
                    {
                        $cgst = round($checkTotal*0.09,2);
                        $sgst = round($checkTotal*0.09,2);
                    }
                }
                $grnd = round($total + $tax + $krishiTax + $sbctax+$igst+$sgst+$cgst,2);
                $dataY = array('total'=>$total,'tax'=>$tax,'krishi_tax'=>$krishiTax,'sbctax'=>$sbctax,'grnd'=>$grnd);
                
                $total2 = 0;
                $total2 = $dataA['InitialInvoice']['total'] -$total;     
                if($this->InitialInvoice->updateAll($dataY,array('id'=>$id)))
                {
                    $Transaction->commit();
                    
                }

                echo json_encode(array('status'=>true,'msg'=>'Invoice to branch '.$b_name.' for Amount '.$amount.' Updated Successfully.','error'=>''));exit;

                
            }
            echo json_encode(array('status'=>true,'msg'=>'Invoice to branch '.$b_name.' for Amount '.$amount.' Updated Successfully.','error'=>''));exit;
        }
    }

}

public function edit_bill()
{

$id  = $this->request->query['id'];
$this->layout='ajax';
$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'bill_no'=>'')));
$b_name=$data['InitialInvoice']['branch_name'];
$c_center=$data['InitialInvoice']['cost_center'];

$this->set('revenue',$revenue);
$this->set('monthMaster',$monthMaster);
$this->set('ActualRevenue',$ActualRevenue);
$this->set('tbl_invoice', $this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id))));
$this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center))));
$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));

}

public function update_bill()
{
        $this->layout='ajax';
        
        

        if ($this->request->is('post'))
        {
            
            $submit = $this->request->data['Approve'];
            $id = $this->request->data['InitialInvoice']['id']; 
            if($submit=='Reject')
            {
                
                if($this->InitialInvoice->deleteAll(array('id'=>$id)))
                {
                    echo json_encode(array('status'=>true,'msg'=>'Invoice Rejected Successfully.','error'=>''));exit;
                
                }
                else
                {
                    
                }
                
                
            }
            else
            {
                $checkTotal = 0;
                $findData = $this->InitialInvoice->find('first',array('conditions'=>array('Id'=>$id)));

                $findCostCenter     = $findData['InitialInvoice']['cost_center']; 
                $findFinanceYear    = $findData['InitialInvoice']['finance_year'];
                $findMonth          = $findData['InitialInvoice']['month'];
                ////////////////  Getting All Amount Of Created Bill Ends Here /////////

                $Transaction = $this->User->getDataSource();
                $Transaction->begin();

                $particular = $this->params['data']['Particular'];
                $k=array_keys($particular);$i=0;

                foreach($particular as $post){
                    $dataX['particulars']="'".addslashes($post['particulars'])."'";
                    $dataX['qty']="'".$post['qty']."'";
                    $dataX['rate']="'".$post['rate']."'";
                    $dataX['amount']="'".$post['amount']."'";
                    $checkTotal += $post['amount'];
                    if(!$this->Particular->updateAll($dataX,array('id'=>$k[$i++])))
                    {
                        $Transaction->rollback(); 
                        $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Particulars Not Added Please Try Again'."</b></h4>"));
                        return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_bill','?'=>array('id'=>$id)));
                    }

                }
                unset($dataX);

                $flag=false;
                
                $findInvAmt = $this->InitialInvoice->query("select sum(amount) total from inv_particulars where initial_id='$id'");
                $findSumPrv = $findInvAmt['0']['0']['total'];

                $findDInvAmt = $this->InitialInvoice->query("select sum(amount) total from inv_deduct_particulars where initial_id='$id'");
                $findSumDPrv = round($findDInvAmt['0']['0']['total']);

                $findTotalProvAmt = $this->InitialInvoice->query("select sum(total)total from tbl_invoice where id!='$id' and cost_center='$findCostCenter' and finance_year='$findFinanceYear' and `month`='$findMonth' and `status`='0'");
                $findTotalBillMade = round($findSumPrv,2)-round($findSumDPrv,2);

                $data=Hash::remove($findData['InitialInvoice'],'id');
                $data=Hash::remove($data,'revenue_arr');
                $data=Hash::remove($data,'revenue_str');
                $data=Hash::remove($data,'revenue');

                $branch_name = $b_name=$data['branch_name'];
                $amount=$data['total'];
                $data=Hash::remove($data,'branch_name');


                // $date = $data['invoiceDate'];
                $date = $this->request->data['InitialInvoice']['invoiceDate'];
                $date = date_create($date);
                $date = date_format($date,"Y-m-d");

                $dataN['po_no']="'".addslashes($data['po_no'])."'";
                $dataN['invoiceDescription']="'".addslashes($data['invoiceDescription'])."'";
                $dataN['month']="'".addslashes($data['month'])."'";
                $dataN['cost_center']="'".addslashes($data['cost_center'])."'";
                $dataN['finance_year']="'".addslashes($data['finance_year'])."'";
                $dataN['invoiceDate'] = "'".addslashes($date)."'";
                $dataN['GSTType'] = "'".addslashes($data['GSTType'])."'";
                $apply_gst = $data['apply_gst'];
                $dataN['apply_gst'] = "'".$data['apply_gst']."'";
                $dataN['apply_service_tax'] = "'".$data['apply_service_tax']."'";

                $dataA = $this->InitialInvoice->find('first',array('fields'=>array('total','cost_center','month','finance_year'),'conditions'=>array('id'=>$id)));
                $dataY = $this->InitialInvoice->find('first',array('fields'=>array('app_tax_cal','total','bill_no'),'conditions'=>array('id' => $id)));
                $tax_call = $dataY['InitialInvoice']['app_tax_cal'];

                if($dataY['InitialInvoice']['total'] != $data['total'])
                {
                    $dataZ['initial_id'] = $id;
                    $dataZ['bill_no'] = $dataY['InitialInvoice']['bill_no'];
                    $dataZ['old_amount'] = $dataY['InitialInvoice']['total'];
                    $dataZ['new_amount'] = $data['total'];
                    $dataZ['createdate'] = date('Y-m-d H:i:s');
                    $this->EditAmount->save($dataZ);
                }

                if ($this->InitialInvoice->updateAll($dataN,array('id'=>$id)))
                {

                    $total = $checkTotal;
                    $total = round($checkTotal,2);
                    $tax = 0;
                    $sbctax = 0;
                    $krishiTax = 0;
                    $igst = 0;
                    $cgst = 0;
                    $sgst = 0;

                    if($tax_call == '1')
                    {
                        $tax=0;$krishiTax=0;$sbctax=0;
                        if($this->params['data']['InitialInvoice']['GSTType']=='Integrated')
                        {
                            $igst = round($checkTotal*0.18,2);
                        }
                        else
                        {
                            $cgst = round($checkTotal*0.09,2);
                            $sgst = round($checkTotal*0.09,2);
                        }
                    }
                    $grnd = round($total + $tax + $krishiTax + $sbctax+$igst+$sgst+$cgst,2);
                    $dataY = array('total'=>$total,'tax'=>$tax,'krishi_tax'=>$krishiTax,'sbctax'=>$sbctax,'grnd'=>$grnd);
                    
                    $total2 = 0;
                    $total2 = $dataA['InitialInvoice']['total'] -$total;     
                    if($this->InitialInvoice->updateAll($dataY,array('id'=>$id)))
                    {
                        $msg = "Hi".$b_name." branch Proforma No.".$dataX['InitialInvoice']['proforma_bill_no'].' has been Edited. '.date("F j, Y, g:i a");
                        //$this->Session->setFlash(__($msg));
                        $Transaction->commit();
                        //echo json_encode(array('status'=>true,'msg'=>$msg,'error'=>''));exit;
                    }
                    if($submit=='Approve')
                    {
                        $Transaction->begin();
                        
                        $cost_center = $this->InitialInvoice->find('first',array('fields'=>array('cost_center','finance_year','BillNoChange'),'conditions'=>array('id'=>$id)));
                        $company = $this->CostCenterMaster->find('first',array(
                            'conditions'=>array('cost_center'=>$cost_center['InitialInvoice']['cost_center'])));

                            //print_r($cost_center);

                        $companyName = $company['CostCenterMaster']['company_name']; 
                        $b_name = $company['CostCenterMaster']['branch'];
                        $f_year1 = $cost_center['InitialInvoice']['finance_year']; 
                        $BillNoChange = $cost_center['InitialInvoice']['BillNoChange']; 
                        $data_br=$this->Addbranch->find('first',array('conditions'=>array('branch_name'=>$b_name)));
                        $state_code = $data_br['Addbranch']['state_code'];
                        //print_r($state_code);exit;
                        $selT = "SELECT MAX(BillNoChange) BillNoChange FROM tbl_invoice ti INNER JOIN cost_master cm ON ti.cost_center =cm.cost_center
                            WHERE finance_year='$f_year1' AND ti.state_code = '$state_code' AND company_name ='$companyName' and ti.id!='$id'"; 
                                    
                        $bill = $this->BillMaster->query($selT);
                        $Transaction->query("Lock TABLES tbl_invoice READ");  //bill no master table not be read by other tables

                        if(strlen(intval($bill['0']['0']['BillNoChange']))==1 || empty($bill['0']['0']['BillNoChange']))
                        {
                            $idx = "0".(intval($bill['0']['0']['BillNoChange'])+1);
                        }
                        else
                        {
                            $idx = (intval($bill['0']['0']['BillNoChange'])+1);
                            if(strlen($idx)==1)
                            {
                                $idx = '0'.$idx;
                            }
                        }
                        $bill_no = $state_code.'-'.$idx.'/'.substr($f_year1,2,6);
                        $data=array('bill_no'=>"'".$bill_no."'",'BillNoChange'=>"$idx",'state_code'=>"'".$state_code."'");
                        
                        $Transaction->query("UNLOCK TABLES"); //unlock to update table

                        if($BillNoChange!=0)
                        {
                            echo json_encode(array('status'=>false,'msg'=>'','error'=>'Data Not Updated because Allready Approved'));exit;
                        }
                        else
                        {

                            $column_from_cost = array(
                            'cost_company_name'=>'company_name','cost_branch'=>'branch','cost_OPBranch'=>'OPBranch',
                            'cost_stream'=>'stream','cost_process'=>'process','cost_process_name'=>'process_name',
                            'cost_TallyHead'=>'TallyHead','cost_client'=>'client','cost_bill_to'=>'bill_to',
                            'cost_as_client'=>'as_client','cost_b_Address1'=>'b_Address1','cost_b_Address2'=>'b_Address2',
                            'cost_b_Address3'=>'b_Address3','cost_b_Address4'=>'b_Address4','cost_b_Address5'=>'b_Address5',
                            'cost_ship_to'=>'ship_to','cost_as_bill_to'=>'as_bill_to','cost_a_address1'=>'a_address1',
                            'cost_a_address2'=>'a_address2','cost_a_address3'=>'a_address3','cost_a_address4'=>'a_address4',
                            'cost_a_address5'=>'a_address5','cost_GSTType'=>'GSTType','cost_ServiceTaxNo'=>'ServiceTaxNo',
                            'cost_VendorGSTNo'=>'VendorGSTNo','cost_HSNCode'=>'HSNCode','cost_SACCode'=>'SACCode',
                            'cost_VendorHSNCode'=>'VendorHSNCode','cost_VendorSACCode'=>'VendorSACCode',
                            'cost_VendorGSTState'=>'VendorGSTState','cost_VendorStateCode'=>'VendorStateCode',
                            'cost_OwnerName'=>'OwnerName','cost_statecodecost'=>'statecodecost',
                            'cost_statenamecost'=>'statenamecost','cost_client_tally_name'=>'client_tally_name',
                            'cost_group_cost_center'=>'group_cost_center','cost_cost_center_type'=>'cost_center_type');
                        
                            foreach($column_from_cost as $ti_cost=>$cost)
                            {
                                $data[$ti_cost] ="'".addslashes($company['CostCenterMaster'][$cost])."'";
                            }
                            
                            
                            //print_r($data);exit;
                            if ($this->InitialInvoice->updateAll($data,array('id'=>$id)))
                            {
                                $Transaction->commit();
                                echo json_encode(array('status'=>true,'error'=>'','msg'=>'Bill No. '.$bill_no.' to Branch '.$branch_name.' for amount '.$amount.' for '.$month.' Generated Successfully.'));exit;
                            }
                            else
                            {
                                $Transaction->rollback();
                                echo json_encode(array('status'=>false,'msg'=>$msg,'error'=>'Data Not Updated'));exit;
                            }
                        }
                    }
                }
                echo json_encode(array('status'=>false,'msg'=>$msg,'error'=>'Data Not Updated'));exit;
                
            }
        }

}

public function ajax_add() 
{
   
    $monthsArrCheck = $this->request->data['InitialInvoice']['MonthArr'];
    $monthsArr = $this->request->data['InitialInvoice']['Months'];
    $monthMaster = array();
    //print_r($monthsArr); exit;    
    
    $invDate = explode('-',$this->request->data['InitialInvoice']['invoiceDate']);
    $gstType = $this->request->data['GSTType'];
    $serv_no = $this->request->data['InitialInvoice']['serv_no'];
    $cost_no = $this->request->data['InitialInvoice']['cost_center'];
    $fin_year = $this->request->data['InitialInvoice']['finance_year'];
    $month    =   $this->request->data['InitialInvoice']['month'];
    $invoiceType = $this->request->data['InitialInvoice']['invoiceType'];
    $category = $this->request->data['InitialInvoice']['category'];
    $arr =explode('-',$fin_year);
    

    if(in_array($month,array('Jan','Feb','Mar')))
    {
        if($arr[0]==date('Y') || $arr[1]==date('y'))
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

    $NewMonth = $month;
    

   krsort($invDate);
   $invDate=implode("-",$invDate);
    $this->layout='ajax';
    $serviceTax = $this->params['data']['InitialInvoice']['servicetax'];
    $data = $this->params['data']['InitialInvoice'];
    //print_r($this->params);exit;
    $username=$this->params['data']['username'];
    //echo $username;exit;
    $b_name=$this->params['data']['InitialInvoice']['branch_name'];
    $cost_center=$this->params['data']['InitialInvoice']['cost_center'];

    $dataX=$this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$cost_center)));

    

    $this->set('category',$category);
    $this->set('invoiceType',$invoiceType);
    $this->set('cost_master',$dataX);
    $this->set('tmp_particulars',$this->AddInvParticular->find('all',array('conditions'=>array('username' => $username))));
    $this->set('tmp_deduct_particulars',$this->AddInvDeductParticular->find('all',array('conditions'=>array('username' => $username))));
    $this->set('username', $username);
}

}
?>