    <?php
class ExposureViews2Controller extends AppController 
{
    public $uses=array('RegistrationMaster','InitialInvoice','BillMaster','InitialInvoiceTmp','Addclient','Addbranch','Addcompany','CostCenterMaster',
        'AddInvParticular','Particular','AddInvDeductParticular','DeductParticular','Access','User','EditAmount',
        'Provision','PONumber','NotificationMaster','ProvisionPart','ProvisionPartDed');
    public $components = array('RequestHandler');
    public $helpers = array('Js');
		

public function beforeFilter()
{
    parent::beforeFilter();
    if(!$this->Session->read("role") ==="admin"){
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
        else if(empty($this->Session->read("admin_id"))){
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    else
    {
        $role=$this->Session->read("role");
        $roles=explode(',',$this->Session->read("page_access"));
        $this->Auth->allow('index_ledger','index_ledger2','addon_credit');
    }
    
    if ($this->request->is('ajax'))
    {
        $this->render('contact-ajax-response', 'ajax');
    }
    
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

public function get_op_det($clientId)
{
    return $this->RegistrationMaster->query("SELECT * FROM `billing_ledger` bl WHERE fin_year='2022' and fin_month='Apr' and clientId = '$clientId'");
}

public function get_subs_days($PeriodType)
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

public function get_subs_value($PeriodType,$RentalAmount)
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

public function get_cost_center($clientId)
{
    return $this->CostCenterMaster->query("select * from cost_master cm where dialdesk_client_id='$clientId' limit 1");
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
    $rsc_consuption_arr = $this->RegistrationMaster->query("$select_consumption");
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
    $rsc_consuption_arr = $this->RegistrationMaster->query("$select_consumption");
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

public function index_ledger2() 
{
    //echo "site is under maintanance. will be live after 4 hours.";
    //exit;
    $this->layout='user';
    $data = array();
    $from_date = "";
    $to_date = "";
    $company_qry = "";
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
    $from_date = implode("-",$explode_date); 
        
    $explode_date1 = explode("-",$to_date1);
    $explode_date1 = array_reverse($explode_date1);
    $to_date = implode("-",$explode_date1);
    
    
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
        $client_status_qry = " and status='$client_status'";        
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
       $client_qry = "SELECT * FROM registration_master rm WHERE  `status` != 'CL' and is_dd_client='1'  $company_qry $client_status_qry ORDER BY status";
       //echo $client_qry;exit;
        $client_det_arr = $this->RegistrationMaster->query($client_qry);
        
        
        
        foreach($client_det_arr as $clr)
        {
            $clientId = $clr['rm']['company_id'];
            $clientName = $clr['rm']['company_name'];
            $data[$clientName]['phone_no'] = $clr['rm']['phone_no'];
            $data[$clientName]['clientEmail'] = $clr['rm']['email'];
            $createdate_for_client_to_first_bill = $clr['rm']['create_date'];
            $data[$clientName]['status'] = $this->get_client_status($clr['rm']['status']);
            
            $first_bill = $data[$clientName]['first_bill'] =  $clr['rm']['first_bill'];
            $data[$clientName]['clientId'] = $clientId;
            $sel_balance_qry = "SELECT * FROM `balance_master` bm WHERE clientId='$clientId' limit 1";
            $bal_det=$this->RegistrationMaster->query($sel_balance_qry);
            $start_date = $bal_det['0']['bm']['start_date'];
            $data[$clientName]['plan_status'] = $this->get_plan_status($start_date);
            
            
            $op2_ledger = $clr['rm']['op'];
            $bill2_ledger = 0;
            $coll2_ledger = 0;
            
            $op2_credit = 0;
            $fr_release_credit = 0;
            $consume_credit = 0;
            $credit_closing = 0;
            $op2_consume_credit_testing = 0;
            //echo "SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$clientId'";exit;
            $op_det_ledger = $this->get_op_det($clientId);
            if(!empty($op_det_ledger))
            {
                $talktime = $op_det_ledger['0']['bl']['talk_time'];
                $op2_credit += $talktime;
                $credit_closing +=$talktime;
                $data[$clientName]['ledger_access_usage'] = 0;
                $data[$clientName]['talk_time'] = round($op_det_ledger['0']['bl']['talk_time'],2);

                $data[$clientName]['ledger_sub'] = round($op_det_ledger['0']['bl']['subs'],2);
                $data[$clientName]['ledger_topup'] = round($op_det_ledger['0']['bl']['topup'],2);
                $data[$clientName]['ledger_setup'] = round($op_det_ledger['0']['bl']['setup_cost'],2);
                $data[$clientName]['firstbilled'] += round($op_det_ledger['0']['bl']['firstbilled'],2);
                
            }
            
            $planId = $bal_det['0']['bm']['PlanId'];
            $plan_det_qry = "SELECT RentalAmount,PeriodType,Balance,CreditValue,SetupCost,DevelopmentCost FROM `plan_master` pm WHERE id='$planId' limit 1"; 
            $plan_det = $this->RegistrationMaster->query($plan_det_qry);
            //$FreeValue = $plan_det['0']['pm']['CreditValue'];
            $FreeValue = round($plan_det['0']['pm']['Balance']/12);
            $data[$clientName]['RentalAmount'] = $plan_det['0']['pm']['RentalAmount'];
            $data[$clientName]['Balance'] = $plan_det['0']['pm']['Balance'];
            $SetupCost = $plan_det['0']['pm']['SetupCost'];
            $developmentCost = $plan_det['0']['pm']['DevelopmentCost'];
            
            $end_date = $to_date;
            $RentalAmount = $plan_det['0']['pm']['RentalAmount'];
            $PeriodType = $plan_det['0']['pm']['PeriodType'];
            $Balance = $plan_det['0']['pm']['Balance'];
            $data[$clientName]['free_value'] =round($FreeValue,2);
            $rental_credit = $plan_det['0']['pm']['RentalAmount'];
            $balance_credit = $plan_det['0']['pm']['Balance'];
            
            $no_of_days = $this->get_subs_days($PeriodType);
            $pamnt = $this->get_subs_value($PeriodType,$RentalAmount);
                
            $cost_cat_arr = $this->get_cost_center($clientId);
            #$data[$clientName]['advance'] = $this->get_advance($cost_cat_arr);
                    
            $is_first_bill_made =   $clr['rm']['first_bill'];          
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
                $cost_center = $cost['cm']['cost_center'];  
                $data[$clientName]['cost_center'] = $cost_center;
                $data[$clientName]['month'] = $month;
                $bill_company = $cost['cm']['company_name'];
                
                //getting opening as on from date
                if(strtotime($from_date)>strtotime('2022-04-01'))
                {
                    $sel_billed_fromdateqry = "select ti.* from tbl_invoice ti where  ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (ti.invoiceDate >= '2022-04-01' AND ti.invoiceDate <= '$day_before')  "; #and bill_no!='' 
                    $billed_fromdateqry_arr = $this->InitialInvoice->query($sel_billed_fromdateqry);
                    #print_r($billed_fromdateqry_arr);exit;
                    $subs_validity = array();
                    foreach($billed_fromdateqry_arr as $inv_det)
                    {
                        $bill_no = $inv_det['ti']['bill_no'];
                        $bill_finyear = $inv_det['ti']['finance_year'];
                        $bill_company = $cost['cm']['company_name'];
                        $op2_ledger +=$inv_det['ti']['grnd'];
                        $initial_id = $inv_det['ti']['id'];
                        $inv_date = $inv_det['ti']['invoiceDate'];
                        $carry_forward = $inv_det['ti']['carry_forward'];
                        //$bill_branch = $cost['cm']['branch'];     
                        $sub_days =$no_of_days*2;
                        $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 
                        //echo $op2_credit;exit;
                        if(strtolower($inv_det['ti']['category'])==strtolower('first_bill'))
                        {
                                //check whether first bill have subscritpion amount
                            $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                            $rsc_subs_arr = $this->InitialInvoice->query($select_subs);

                            foreach($rsc_subs_arr as $sb)
                            {
                                $op2_value =$this->get_credit_from_subs_value($rental_credit,$balance_credit,$sb['ip']['amount']);
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
                            }
                        }
                        else if(strtolower($inv_det['ti']['category'])==strtolower('subscription'))
                        {
                                $op2_value =$this->get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
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
                        else if(strtolower($inv_det['ti']['category'])==strtolower('Talk Time') || strtolower($inv_det['ti']['category'])==strtolower('Topup'))
                        {
                            $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                            $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$inv_det['ti']['total'];
                            $op2_credit +=$inv_det['ti']['total'];
                        }


                    }
                    $op2_ledger = $this->get_advance($cost_id,'2022-04-01',$day_before,$op2_ledger,'old');
                    $op2_ledger = $this->get_collection($cost_center,'2022-04-01',$day_before,$bill_company,$op2_ledger,'old');
                    
                    #print_r($subs_validity);exit;
                    
                    
                    //starts here for ledger
                    $date_capture_start = strtotime('2022-04-01');
                    while($date_capture_start<=strtotime($day_before))
                    {
                        $cap_date = date('Y-m-d',$date_capture_start);
                        
                        #$obj = $this->get_billed($cost_center,$cap_date,$rental_credit,$balance_credit,$op2_ledger,$op2_credit);
                        #$op2_ledger=$obj[0];
                        #$op2_credit = $obj[1];
                        #$coll2_ledger = $this->get_advance($cost_id,$cap_date,$coll2_ledger,'new');
                        #$op2_ledger = $this->get_collection($cost_center,'2022-04-01',$day_before,$bill_company,$op2_ledger,'old');
                        #$op2_credit = $this->get_consumption($clientId,$cap_date,$op2_credit,'old');
                        ///////////////////////// testing consumption ////////////////////////
                       # $op2_consume_credit_testing = $this->get_consumption($clientId,$cap_date,$op2_consume_credit_testing,'new');   
                        
                        
                        $consume_credit2 = $this->get_consumption2($clientId,$cap_date,$consume_credit,'new');
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
                
                $subs_validity = array();
                //for adding/substracting waiver from waiver master
                $waiver_qry = "SELECT * FROM waiver_master wm WHERE clientId='$clientId' and status='1'";
                $waiver_list = $this->RegistrationMaster->query($waiver_qry);
                foreach($waiver_list as $wl)
                {
                    $inv_date = $wl['wm']['start_date'];
                    $to = $wl['wm']['end_date'];
                    $subs_validity["$inv_date#$to"] = $wl['wm']['Balance'];
                }
                //print_r($subs_validity);exit;
                //getting opening as on FromDate to ToDate
                //starts here for ledger
                $sel_billed_todateqry = "select ti.* from tbl_invoice ti where ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (  ti.invoiceDate >= '$from_date' AND ti.invoiceDate <= '$to_date')  "; #and bill_no!=''
                $billed_todateqry_arr = $this->InitialInvoice->query($sel_billed_todateqry);
                #print_r($billed_todateqry_arr);exit;
                
                
                foreach($billed_todateqry_arr as $inv_det)
                {
                    $bill_no = $inv_det['ti']['bill_no'];
                    $bill_finyear = $inv_det['ti']['finance_year'];
                    $bill_company = $cost['cm']['company_name'];
                    $bill2_ledger +=$inv_det['ti']['grnd'];
                    $initial_id = $inv_det['ti']['id'];
                    $inv_date = $inv_det['ti']['invoiceDate'];
                    $carry_forward = $inv_det['ti']['carry_forward'];
                    //$bill_branch = $cost['cm']['branch'];     
                    $sub_days =$no_of_days*2;
                    $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 
                    
                    if(strtolower($inv_det['ti']['category'])==strtolower('first_bill'))
                    {
                            //check whether first bill have subscritpion amount
                        $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                        $rsc_subs_arr = $this->InitialInvoice->query($select_subs);
                        
                        foreach($rsc_subs_arr as $sb)
                        {
                            $fr_release_credit +=$this->get_credit_from_subs_value($rental_credit,$balance_credit,$sb['ip']['amount']);
                            if($carry_forward)
                            {
                                $value_in_subs_validity = $subs_validity["$inv_date#$to"];
                                $subs_validity["$inv_date#$to"] = $value_in_subs_validity+$this->get_credit_from_subs_value($rental_credit,$balance_credit,$sb['ip']['amount']);    
                            }
                            else
                            {
                                $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                                $subs_validity["$inv_date#unlimited"] = $value_in_subs_validity+$this->get_credit_from_subs_value($rental_credit,$balance_credit,$sb['ip']['amount']);
                            }
                        }
                    }
                    else if(strtolower($inv_det['ti']['category'])==strtolower('subscription'))
                    {
                            $fr_release_credit += $this->get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
                            if($carry_forward)
                            {
                                $value_in_subs_validity = $subs_validity["$inv_date#$to"];
                                $subs_validity["$inv_date#$to"] = $value_in_subs_validity+$this->get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
                                
                            }
                            else
                            {
                                $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                                $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$this->get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
                            }
                    }
                    else if(strtolower($inv_det['ti']['category'])==strtolower('Talk Time') || strtolower($inv_det['ti']['category'])==strtolower('Topup'))
                    {
                        $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                        $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$inv_det['ti']['total'];
                        $fr_release_credit +=$inv_det['ti']['total'];
                    }
                    
                    
                }
                
                $coll2_ledger = $this->get_advance($cost_id,$from_date,$to_date,$coll2_ledger,'new');
                $coll2_ledger = $this->get_collection($cost_center,$from_date,$to_date,$bill_company,$coll2_ledger,'new');
                #print_r($subs_validity);exit;
                #$credit_closing = $op2_credit+$fr_release_credit-$consume_credit;
                
                $date_capture_start = strtotime($from_date);
                
                while($date_capture_start<=strtotime($to_date))
                {
                    $cap_date = date('Y-m-d',$date_capture_start);
                    //echo '<br/>';

                    #$obj = $this->get_billed($cost_center,$cap_date,$rental_credit,$balance_credit,$bill2_ledger,$fr_release_credit);
                    #$bill2_ledger=$obj[0];
                    //echo '<br/>';
                    #$fr_release_credit = $obj[1];
                    
                    #$coll2_ledger = $this->get_advance($cost_id,$cap_date,$coll2_ledger,'new');
                    #$coll2_ledger = $this->get_collection($cost_center,$cap_date,$bill_company,$coll2_ledger,'new');
                    
                    #$consume_credit = $this->get_consumption($clientId,$cap_date,$consume_credit,'new');
                    ///////////////////////// testing consumption ////////////////////////
                    
                    #$op2_consume_credit_testing = $this->get_consumption($clientId,$cap_date,$op2_consume_credit_testing,'new');   
                    
                    $consume_credit2 = $this->get_consumption2($clientId,$cap_date,$consume_credit,'new');
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
                
                $sel_subs_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category='subscription' AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date' and bill_no!=''";
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
    $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","company_name"),'conditions'=>"`status` != 'CL'",'order'=>array('Company_name'=>'asc')));
    $client = array('All'=>'All')+$client;
    $this->set('client_det',$client);
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
    $select_consumption = "SELECT botapp_pulse total,date(cm_date) cm_date FROM billing_consume_daily  bcd WHERE client_id='$clientId' and date(cm_date) between '$start_date' and '$end_date'";
    $rsc_consuption_arr = $this->RegistrationMaster->query("$select_consumption");
    #print_r($rsc_consuption_arr);exit;
    $op2_value_arr = array();
    foreach($rsc_consuption_arr as $consume)
    {
        $op2_value_arr[$consume[0]['cm_date']]= $consume['bcd']['total'];   
    }
    return $op2_value_arr;
}


public function addon_credit() 
{
    //echo "site is under maintanance. will be live after 4 hours.";
    //exit;
    $this->layout='user';
    $data = array();
    $from_date = "";
    $to_date = "";
    $company_qry = "";
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
    $from_date = implode("-",$explode_date); 
        
    $explode_date1 = explode("-",$to_date1);
    $explode_date1 = array_reverse($explode_date1);
    $to_date = implode("-",$explode_date1);
    
    
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
INNER JOIN `balance_tool_master` btm ON rm.company_id=btm.clientId WHERE  rm.`status` != 'CL' and rm.is_dd_client='1'  $company_qry $client_status_qry ORDER BY status";
       //echo $client_qry;exit;
        $client_det_arr = $this->RegistrationMaster->query($client_qry);
        
        
        
        foreach($client_det_arr as $clr)
        {
            $clientId = $clr['rm']['company_id'];
            $clientName = $clr['rm']['company_name'];
            $data[$clientName]['phone_no'] = $clr['rm']['phone_no'];
            $data[$clientName]['clientEmail'] = $clr['rm']['email'];
            #$createdate_for_client_to_first_bill = $clr['rm']['create_date'];
            $data[$clientName]['status'] = $this->get_client_status($clr['rm']['status']);
            
            
            $data[$clientName]['clientId'] = $clientId;
            $sel_balance_qry = "SELECT * FROM `balance_tool_master` bm WHERE clientId='$clientId' limit 1";
            $bal_det=$this->RegistrationMaster->query($sel_balance_qry);
            $start_date = $bal_det['0']['bm']['start_date'];
            $data[$clientName]['plan_status'] = $this->get_plan_status($start_date);
            
            
            $op2_ledger = 0;
            $bill2_ledger = 0;
            $coll2_ledger = 0;
            
            $op2_credit = 0;
            $fr_release_credit = 0;
            $consume_credit = 0;
            $credit_closing = 0;
            $op2_consume_credit_testing = 0;
            //echo "SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$clientId'";exit;
            $op_det_ledger = 0;
            
            
            $planId = $bal_det['0']['bm']['ToolId'];
            $plan_det_qry = "SELECT PlanCreate,RentalAmount,PeriodType,FreeSessions,InboundWhatsappCharge,OutboundWhatsappCharge FROM `plan_tool_master` pm WHERE id='$planId' and PlanCreate='Dialdee' limit 1"; 
            $plan_det = $this->RegistrationMaster->query($plan_det_qry);
            $data[$clientName]['InChatCharge'] = $plan_det['0']['pm']['InboundWhatsappCharge'];
            $data[$clientName]['OutChatCharge'] = $plan_det['0']['pm']['OutboundWhatsappCharge'];
            //$FreeValue = $plan_det['0']['pm']['CreditValue'];
            
            $data[$clientName]['RentalAmount'] = $plan_det['0']['pm']['RentalAmount'];
            
            $end_date = $to_date;
            $RentalAmount = $plan_det['0']['pm']['RentalAmount'];
            $PeriodType = $plan_det['0']['pm']['PeriodType'];
            
            
            $rental_credit = $plan_det['0']['pm']['RentalAmount'];
            $balance_credit = $plan_det['0']['pm']['FreeSessions'];
            
            $no_of_days = $this->get_subs_days($PeriodType);
            $pamnt = $this->get_subs_value($PeriodType,$RentalAmount);
                
            $cost_cat_arr = $this->get_cost_center($clientId);
            #$data[$clientName]['advance'] = $this->get_advance($cost_cat_arr);
                    
                 
                
            
            foreach($cost_cat_arr as $cost)
            {
                $cost_center = $cost['cm']['cost_center'];  
                $data[$clientName]['cost_center'] = $cost_center;
                $data[$clientName]['month'] = $month;
                $bill_company = $cost['cm']['company_name'];
                
                //getting opening as on from date
                if(strtotime($day_before)>strtotime('2022-03-31'))
                {
                    $sel_billed_fromdateqry = "select ti.* from tbl_invoice ti where ti.bill_no!='' and ti.category in ('Subscription-Tool','Topup-Tool','Talk-Time-Tool')  and ti.status='0' and cost_center='$cost_center' and  ti.invoiceDate <= '$day_before'  "; #and bill_no!='' 
                    $billed_fromdateqry_arr = $this->InitialInvoice->query($sel_billed_fromdateqry);
                    #print_r($billed_fromdateqry_arr);exit;
                    $subs_validity = array();
                    foreach($billed_fromdateqry_arr as $inv_det)
                    {
                        $bill_no = $inv_det['ti']['bill_no'];
                        $bill_finyear = $inv_det['ti']['finance_year'];
                        $bill_company = $cost['cm']['company_name'];
                        $op2_ledger +=$inv_det['ti']['grnd'];
                        $initial_id = $inv_det['ti']['id'];
                        $inv_date = $inv_det['ti']['invoiceDate'];
                        $carry_forward = 0;
                        //$bill_branch = $cost['cm']['branch'];     
                        $sub_days =$no_of_days;
                        $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 
                        //echo $op2_credit;exit;
                        $op2_value = 0;
                        if(strtolower($inv_det['ti']['category'])!=strtolower('subscription-tool'))
                        {
                            $select_inv_session = "SELECT * FROM inv_particulars ip WHERE initial_id='$initial_id' ";
                            $inv_parts_arr = $this->InitialInvoice->query($select_inv_session);
                            foreach($inv_parts_arr as $parts)
                            {
                                $op2_value +=$parts['ip']['qty'];
                            }
                            
                            $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                            $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$op2_value;
                        }
                        else if(strtolower($inv_det['ti']['category'])==strtolower('subscription-tool') && $carry_forward)
                        {
                            $op2_value =$this->get_freesession_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
                            $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                            $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$op2_value;
                        }
                        else
                        {
                            $op2_value =$this->get_freesession_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
                            
                            $value_in_subs_validity = $subs_validity["$inv_date#$to"];
                            $subs_validity["$inv_date#$to"] = $value_in_subs_validity+$op2_value;
                        }
                        $op2_credit +=$op2_value;
                    }
                    #$op2_ledger = $this->get_advance($cost_id,'2022-04-01',$day_before,$op2_ledger,'old');
                    $op2_ledger = $this->get_collection_social($cost_center,'2022-04-01',$day_before,$bill_company,$op2_ledger,'old');
                    
                    #print_r($subs_validity);exit;
                    
                    //echo $op2_credit;exit;
                    //starts here for ledger
                    $date_capture_start = strtotime('2022-09-01');
                    $consume_arr = $this->get_consumption_social($clientId,'2022-04-01',$day_before);
                    #print($consume_arr);exit;
                    while($date_capture_start<=strtotime($day_before))
                    {
                        $cap_date = date('Y-m-d',$date_capture_start); 
                        $consume_credit2 = $consume_arr[$cap_date];
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
                    //echo $op2_credit;exit;
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
                    
                    $op2_credit = $credit_closing;
                }
                #print_r($subs_validity);exit;
                
                
                //getting opening as on FromDate to ToDate
                //starts here for ledger
                $sel_billed_todateqry = "select ti.* from tbl_invoice ti where ti.bill_no!='' and ti.category in ('subscription-tool','Topup-Tool','Talk-Time-Tool')  and ti.status='0' and cost_center='$cost_center' and (  ti.invoiceDate >= '$from_date' AND ti.invoiceDate <= '$to_date')  "; #and bill_no!=''
                $billed_todateqry_arr = $this->InitialInvoice->query($sel_billed_todateqry);
                #print_r($sel_billed_todateqry);exit;
                $subs_validity = array();
                foreach($billed_todateqry_arr as $inv_det)
                {
                    $bill_no = $inv_det['ti']['bill_no'];
                    $bill_finyear = $inv_det['ti']['finance_year'];
                    $bill_company = $cost['cm']['company_name'];
                    $bill2_ledger +=$inv_det['ti']['grnd'];
                    $initial_id = $inv_det['ti']['id'];
                    $inv_date = $inv_det['ti']['invoiceDate'];
                    $carry_forward = 0;
                    //$bill_branch = $cost['cm']['branch'];     
                    $sub_days =$no_of_days*1;
                    $to = date('Y-m-d',strtotime($inv_date." +$sub_days days")); 
                    
                    $op2_value = 0;
                    if(strtolower($inv_det['ti']['category'])!=strtolower('subscription-tool') )
                    {
                        $select_inv_session = "SELECT * FROM inv_particulars ip WHERE initial_id='$initial_id' "; 
                        $inv_parts_arr = $this->InitialInvoice->query($select_inv_session);
                        foreach($inv_parts_arr as $parts)
                        {
                            $op2_value +=$parts['ip']['qty'];
                        }
                        $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                        $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$op2_value;
                    }
                    else if($carry_forward && strtolower($inv_det['ti']['category'])==strtolower('subscription-tool') )
                    {
                        $op2_value =$this->get_freesession_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
                        $value_in_subs_validity = $subs_validity["$inv_date#unlimited"];
                        $subs_validity["$inv_date#unlimited"] =$value_in_subs_validity+$op2_value;
                    }
                    else
                    {
                        
                        $op2_value =$this->get_freesession_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
                        $value_in_subs_validity = $subs_validity["$inv_date#$to"];
                        $subs_validity["$inv_date#$to"] = $value_in_subs_validity+$op2_value;
                    }
                    $fr_release_credit +=$op2_value;
                    
                }
                
                
                $coll2_ledger = $this->get_collection_social($cost_center,$from_date,$to_date,$bill_company,$coll2_ledger,'new');
                #print_r($subs_validity);exit;
                #$credit_closing = $op2_credit+$fr_release_credit-$consume_credit;
                
                $date_capture_start = strtotime($from_date);
                $consume_arr = $this->get_consumption_social($clientId,$from_date,$to_date);
                #print_r($consume_arr);exit;
                while($date_capture_start<=strtotime($to_date))
                {
                    $cap_date = date('Y-m-d',$date_capture_start);
                    $consume_credit2 = $consume_arr[$cap_date];
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
    $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","company_name"),'conditions'=>"`status` != 'CL'",'order'=>array('Company_name'=>'asc')));
    $client = array('All'=>'All')+$client;
    $this->set('client_det',$client);
}




}
?>