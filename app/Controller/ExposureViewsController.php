    <?php
class ExposureViewsController extends AppController 
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
        $this->Auth->allow('index_ledger','index_ledger2','index_ledger_bhanu');
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
    $today_date = date( 'Y-m-d', strtotime( $to_date . ' -1 day' ) );
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
        $client_det_arr = $this->RegistrationMaster->query($client_qry); 
        
        
        
        foreach($client_det_arr as $clr)
        {
            $clientId = $clr['rm']['company_id'];
            $clientName = $clr['rm']['company_name'];
            $data[$clientName]['phone_no'] = $clr['rm']['phone_no'];
            $data[$clientName]['clientEmail'] = $clr['rm']['email'];
            $createdate_for_client_to_first_bill = $clr['rm']['create_date'];
            
            if($clr['rm']['status']=='A')
            {
                $data[$clientName]['status'] = 'Active';
            }
            else if($clr['rm']['status']=='D')
            {
                $data[$clientName]['status'] = 'De-Active';
            }
            else if($clr['rm']['status']=='H')
            {
                $data[$clientName]['status'] = 'Hold';
            }
            else if($clr['rm']['status']=='CL')
            {
                $data[$clientName]['status'] = 'Close';
            }
            $first_bill = $data[$clientName]['first_bill'] =  $clr['rm']['first_bill'];
            $data[$clientName]['clientId'] = $clientId;
            $sel_start_date = "SELECT start_date FROM `balance_master` bm WHERE clientId='$clientId' limit 1";
            $rsc_start_date=$this->RegistrationMaster->query($sel_start_date);
            $record_start_date = $rsc_start_date['0']['bm']['start_date'];
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
            
            $op2_ledger = $clr['rm']['op'];
            $bill2_ledger = 0;
            $coll2_ledger = 0;
            
            $op2_credit = 0;
            $fr_release_credit = 0;
            $consume_credit = 0;
            $op2_consume_credit_testing = 0;
            //for waiver 
            $waiver_qry = "SELECT * FROM waiver_master wm WHERE clientId='$clientId' ";
            $waiver_list = $this->RegistrationMaster->query($waiver_qry);
            foreach($waiver_list as $wl)
            {
                $op2_credit += $wl['wm']['Balance'];
            }
            //echo "SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$clientId'";exit;
            $op_det_ledger = $this->RegistrationMaster->query("SELECT * FROM `billing_ledger` bl WHERE fin_year='2022' and fin_month='Apr' and clientId = '$clientId'");
            if(!empty($op_det_ledger))
            {
                $talktime = $op_det_ledger['0']['bl']['talk_time'];
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
                    $data[$clientName]['talk_time'] = round($op_det_ledger['0']['bl']['talk_time'],2);
                }

                $data[$clientName]['ledger_sub'] = round($op_det_ledger['0']['bl']['subs'],2);
                $data[$clientName]['ledger_topup'] = round($op_det_ledger['0']['bl']['topup'],2);
                $data[$clientName]['ledger_setup'] = round($op_det_ledger['0']['bl']['setup_cost'],2);
                $data[$clientName]['firstbilled'] += round($op_det_ledger['0']['bl']['firstbilled'],2);
                
                
            }

            $billing_opening_balance = "billing_opening_balance";
            if($current_month!=$Nmonth)
            {
                $billing_opening_balance = "billing_opening_balance_history";
            }
            $op_det = $this->RegistrationMaster->query("SELECT op_bal,cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$clientId' and fin_year='$year' and fin_month='$Nmonth'");
            //print_r($op_det);exit;
            if(!empty($op_det['0']['bob']['op_dd']))
            {
                $data[$clientName]['op_dd'] = $op_det['0']['bob']['op_dd'];
            }
            else
            {
                $op_det = $this->RegistrationMaster->query("SELECT op_bal,cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$clientId' and fin_year='$year' and fin_month='$Nmonth'");
                if(!empty($op_det['0']['bob']['op_dd']))
                {
                    $data[$clientName]['op_dd'] = $op_det['0']['bob']['op_dd'];
                }
            }
            $data[$clientName]['cs_bal'] = $op_det['0']['bob']['cs_bal'];
            //$data[$clientName]['fr_val'] = $op_det['0']['bob']['fr_val'];
            //$data[$clientName]['adv_val'] = $op_det['0']['bob']['adv_val'];

            $plan_get_qry = "SELECT PlanId,start_date FROM balance_master bm WHERE clientId='$clientId' limit 1"; 
            $bal_det = $this->RegistrationMaster->query($plan_get_qry);
            $planId = $bal_det['0']['bm']['PlanId'];
            $plan_det_qry = "SELECT RentalAmount,PeriodType,Balance,CreditValue,SetupCost,DevelopmentCost FROM `plan_master` pm WHERE id='$planId' limit 1"; 
            $plan_det = $this->RegistrationMaster->query($plan_det_qry);
            //$FreeValue = $plan_det['0']['pm']['CreditValue'];
            $FreeValue = round($plan_det['0']['pm']['Balance']/12);
            $data[$clientName]['RentalAmount'] = $plan_det['0']['pm']['RentalAmount'];
            $data[$clientName]['Balance'] = $plan_det['0']['pm']['Balance'];
            $SetupCost = $plan_det['0']['pm']['SetupCost'];
            $developmentCost = $plan_det['0']['pm']['DevelopmentCost'];
            $start_date = $bal_det['0']['bm']['start_date']; 
            
            $end_date = $to_date;
            $RentalAmount = $plan_det['0']['pm']['RentalAmount'];
            $PeriodType = $plan_det['0']['pm']['PeriodType'];
            $Balance = $plan_det['0']['pm']['Balance'];
            $data[$clientName]['free_value'] =round($FreeValue,2);
            $rental_credit = $plan_det['0']['pm']['RentalAmount'];
            $balance_credit = $plan_det['0']['pm']['Balance'];
            
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
                $pamnt_first = $pamnt;
                    
                    $cost_cat_arr = $this->CostCenterMaster->query("select * from cost_master cm where dialdesk_client_id='$clientId' limit 1");
                    $cost_adv_arr = $this->CostCenterMaster->query("SELECT *,SUM(bill_passed) advance FROM `bill_pay_advance` bpa WHERE bill_no='{$cost_cat_arr[0]['cm']['id']}'");
                    if(!empty($cost_adv_arr))
                    {
                        $data[$clientName]['advance'] = $cost_adv_arr['0']['bpa']['advance'];
                    }
                    
                        /*if($first_bill=='0' || $first_bill==0)
                        {
                            $data[$clientName]['new_plan_sub_cost'] = $pamnt;
                            $data[$clientName]['new_plan_setup_cost'] = $SetupCost;
                            $data[$clientName]['new_plan_dev_cost'] = $developmentCost;
                            $data[$clientName]['first_plan_value'] = ($pamnt+$SetupCost+$developmentCost);
                            $data[$clientName]['first_plan_value_with_gst'] = round(($pamnt+$SetupCost+$developmentCost)*1.18,2);
                            $data[$clientName]['sub_start_date'] = $start_date;
                            $data[$clientName]['sub_end_date'] = date('Y-m-d',strtotime($start_date." +$no_of_month days"));;  
                            $data[$clientName]['due_date'] = 'Immediate';  
                        }
                        else
                        {
                            $cost_center = $cost_cat_arr[0]['cm']['cost_center'];
                            $sel_firstplan_qry = "select id,grnd,subs_start_date from tbl_invoice ti where cost_center='$cost_center' and category='first_bill' and  finance_year='$finYear' and month='$month' limit 1";
                            $sel_firstplan_arr = $this->InitialInvoice->query($sel_firstplan_qry);
                            $intital_id = $sel_firstplan_arr['0']['ti']['id'];

                            $sel_first_sub_qry = "SELECT amount FROM `inv_particulars` ip WHERE initial_id='$intital_id' and sub_category='subscription' limit 1";
                            $bill_subs_arr = $this->InitialInvoice->query($sel_first_sub_qry);
                            $data[$clientName]['new_plan_sub_cost'] = $pamnt;
                            $data[$clientName]['first_plan_value'] = $sel_firstplan_arr['0']['ti']['total'];
                            $data[$clientName]['first_plan_value_with_gst'] = $sel_firstplan_arr['0']['ti']['grnd'];
                            $data[$clientName]['firstbilled'] += $sel_firstplan_arr['0']['ti']['grnd'];
                            if(empty($sel_firstplan_arr['0']['ti']['subs_start_date']))
                            {
                                $data[$clientName]['subbilled_as_sub_date'] += round($bill_subs_arr['0']['ip']['amount']*1.18,2);
                            }
                            //$data[$clientName]['subbilled'] += $sel_firstplan_arr['0']['ti']['grnd']; 
                            
                            $selec_all_payment_qry = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
     tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('first_bill')
    AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no"; 
                    $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);
                    $first_bill_coll = 0;
                            foreach($collection_det_arr as $coll_det)
                            {
                                $first_bill_coll += $coll_det['0']['bill_passed'];
                            }
                            $data[$clientName]['first_plan_coll'] = round($first_bill_coll,2);
                            $first_bill_coll_ntgst = round(($first_bill_coll/118)*100,2);
                            $first_subs = $first_bill_coll_ntgst- $SetupCost- $developmentCost;
                            /*if($first_subs>0)
                            {
                                $data[$clientName]['first_plan_subscoll'] = round($first_subs*1.18,2);
                            }*/
                        /*}*/
               $is_first_bill_made = false; 
            if(strtotime($createdate_for_client_to_first_bill)>=strtotime("2022-04-01 00:00:00"))
            {
                
                $sel_first_plan_billed = "SELECT ti.* FROM tbl_invoice ti 
                INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center 
                WHERE ti.category in ('first_bill','Setup Cost','DevelopmentCost') and ti.bill_no!='' and ti.status='0' and cm.dialdesk_client_id='$clientId'";
                //echo $sel_first_plan_billed;exit
                $billed_first_plan = $this->InitialInvoice->query($sel_first_plan_billed);
                foreach($billed_first_plan as $first_bill)
                {
                    $initial_id = $first_bill['ti']['id'];
                    $additional_cost = array('SetupCost','DevelopmentCost','Subscription');
                    $category = $first_bill['ti']['category'];
                    
                    //echo $pamnt;exit;
                    if($category=='first_bill')
                    {
                        $is_first_bill_made = true;
                        foreach($additional_cost as $scost)
                        {
                            $select_subs = "select ip.sub_category,ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='$scost'";
                            $rsc_subs_arr = $this->InitialInvoice->query($select_subs);
                            //print_r($rsc_subs_arr);
                            foreach($rsc_subs_arr as $sb)
                            {   
                                if(strtolower($scost)==strtolower('SetupCost'))
                                {$SetupCost -=$sb['ip']['amount'];}
                                else if(strtolower($scost)==strtolower('DevelopmentCost'))
                                {$developmentCost -=$sb['ip']['amount'];}
                                else if(strtolower($scost)==strtolower('Subscription'))
                                {$pamnt -=$sb['ip']['amount'];}
                            }
                        }
                        
                        //check whether first subscription made or not.
                        $select_subs = "select ip.sub_category,ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                        $rsc_subs_arr = $this->InitialInvoice->query($select_subs);
                        #print_r($rsc_subs_arr);;exit;
                        if(empty($rsc_subs_arr))
                        {
                            $sel_first_subs = "SELECT ti.* FROM tbl_invoice ti 
                INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center 
                WHERE ti.category = 'Subscription' and ti.bill_no!='' and ti.status='0' and cm.dialdesk_client_id='$clientId' limit 1";
                            $rsc_subs_arr = $this->InitialInvoice->query($sel_first_subs);
                            
                            if(empty($rsc_subs_arr) && !empty($start_date))
                            {
                                $data[$clientName]['subs_val'] = $pamnt_first*1.18;  
                                $data[$clientName]['due_date'] = 'Immediate';
                                $data[$clientName]['sub_start_date'] = $start_date; 
                                $data[$clientName]['sub_end_date'] =  date('Y-m-d',strtotime($start_date." +$no_of_month days"));
                            }
                            
                        }
                        
                    }
                    else
                    {
                        if(strtolower($category)==strtolower('Setup Cost'))
                        { $SetupCost -=$first_bill['ti']['total']; }
                        else if(strtolower($category)==strtolower('Development Cost'))
                        {$developmentCost -=$first_bill['ti']['total'];}
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
                $cost_center = $cost['cm']['cost_center'];  
                $data[$clientName]['cost_center'] = $cost_center;
                $data[$clientName]['month'] = $month;
                $bill_company = $cost['cm']['company_name'];
                
                //getting opening as on from date
                if(strtotime($from_date)>=strtotime('2022-04-01'))
                {
                    $sel_billed_fromdateqry = "select ti.* from tbl_invoice ti where  ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (ti.invoiceDate >= '2022-04-01' AND ti.invoiceDate <= '$day_before')  "; #and bill_no!='' 
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
                    //getting advance from this client
                        $cost_adv_arr = $this->CostCenterMaster->query("SELECT *,SUM(bill_passed) advance FROM `bill_pay_advance` bpa WHERE bill_no='$cost_id' AND  date(bpa.pay_dates)  between '2022-04-01' and '$day_before'");
                        if(!empty($cost_adv_arr))
                        {
                            $op2_ledger -= $cost_adv_arr['0']['0']['advance'];
                        }
                    //getting actual outstanding as on from date.
                    $select_payment_fromdateqry = "SELECT sum(bpp.bill_passed) bill_passed FROM 
    bill_pay_particulars bpp inner join tbl_invoice ti on bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year and bpp.branch_name=ti.branch_name AND bpp.company_name='$bill_company' 
    where ti.cost_center='$cost_center' and date(bpp.pay_dates)  between '2022-04-01' and '$day_before' ";  
                        
                        $collection_fromdate_arr = $this->BillMaster->query($select_payment_fromdateqry);
                        foreach($collection_fromdate_arr as $coll_det)
                        {    $op2_ledger -= $coll_det['0']['bill_passed']; }
                       //getting consumption from table 
                        $select_consumption = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId' and date(cm_date)>='$start_date' and (cm_date >= '2022-04-01' AND cm_date <= '$day_before')";
                        $rsc_consuption_arr = $this->RegistrationMaster->query("$select_consumption");
                        //print_r($rsc_consuption_arr);exit;
                        foreach($rsc_consuption_arr as $consume)
                        {
                            $op2_credit -= $consume[0]['total'];
                        }
                        
                        ///////////////////////// testing consumption ////////////////////////
                        $select_consumption_testing = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId'  and (cm_date >= '2022-04-01' AND cm_date <= '$day_before')";
                        $rsc_consuption_testing_arr = $this->RegistrationMaster->query("$select_consumption_testing");
                        //print_r($rsc_consuption_arr);exit;
                        foreach($rsc_consuption_testing_arr as $consume)
                        {
                            $op2_consume_credit_testing += $consume[0]['total'];
                        }
                        ///////////////////////// end testing consumption ////////////////////////
                }
                
                //getting opening as on FromDate to ToDate
                $sel_billed_todateqry = "select ti.* from tbl_invoice ti where ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (  ti.invoiceDate >= '$from_date' AND ti.invoiceDate <= '$to_date')  "; #and bill_no!=''
                $billed_todateqry_arr = $this->InitialInvoice->query($sel_billed_todateqry);

                foreach($billed_todateqry_arr as $inv_det)
                {
                    $bill_no = $inv_det['ti']['bill_no'];
                    $bill_finyear = $inv_det['ti']['finance_year'];
                    $bill_company = $cost['cm']['company_name'];
                    $bill2_ledger +=$inv_det['ti']['grnd'];
                    $initial_id = $inv_det['ti']['id'];
                    //$bill_branch = $cost['cm']['branch'];     
                    
                    if(strtolower($inv_det['ti']['category'])==strtolower('first_bill'))
                    {
                            //check whether first bill have subscritpion amount
                        $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                        $rsc_subs_arr = $this->InitialInvoice->query($select_subs);
                        foreach($rsc_subs_arr as $sb)
                        {
                             $fr_release_credit +=$this->get_credit_from_subs_value($rental_credit,$balance_credit,$sb['ip']['amount']);
                        }

                    }
                    else if(strtolower($inv_det['ti']['category'])==strtolower('subscription'))
                    {
                        $fr_release_credit +=$this->get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
                    }
                    else if(strtolower($inv_det['ti']['category'])==strtolower('Talk Time') || strtolower($inv_det['ti']['category'])==strtolower('Topup'))
                    {
                        $fr_release_credit +=$inv_det['ti']['total'];
                    }
                    
                    
                }
                //getting advance from this client
                $cost_adv_arr = $this->CostCenterMaster->query("SELECT *,SUM(bill_passed) advance FROM `bill_pay_advance` bpa WHERE bill_no='$cost_id' AND  date(bpa.pay_dates)  between '$to_date' and '$from_date'");
                if(!empty($cost_adv_arr))
                {
                    $coll2_ledger += $cost_adv_arr[0]['0']['advance'];
                }
                   //getting actual outstanding as on from date.  
               $select_payment_qry = "SELECT sum(bpp.bill_passed) bill_passed FROM 
    bill_pay_particulars bpp inner join tbl_invoice ti on bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year and bpp.branch_name=ti.branch_name AND bpp.company_name='$bill_company' 
    where ti.cost_center='$cost_center' and date(bpp.pay_dates)  between '$from_date' and '$to_date' ";    

                $collection_billingtodate_arr = $this->BillMaster->query($select_payment_qry);
                foreach($collection_billingtodate_arr as $coll_det)
                {    $coll2_ledger += $coll_det['0']['bill_passed']; }
                       
                //getting file detail 
                
                
                  
                //getting consumption from table

                 $select_consumption = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId' and date(cm_date)>='$start_date' and DATE(cm_date) between '$from_date' and '$to_date'";
                 $rsc_consuption_arr = $this->RegistrationMaster->query("$select_consumption");
                 //print_r($rsc_consuption_arr);exit;
                 foreach($rsc_consuption_arr as $consume)
                 {
                     $consume_credit += $consume[0]['total'];
                 }
                 
                 ///////////////////////// testing consumption ////////////////////////
                        $select_consumption_testing = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId'  and DATE(cm_date) between '$from_date' and '$to_date'";
                        $rsc_consuption_testing_arr = $this->RegistrationMaster->query("$select_consumption_testing");
                        //print_r($rsc_consuption_arr);exit;
                        foreach($rsc_consuption_testing_arr as $consume)
                        {
                            $op2_consume_credit_testing += $consume[0]['total'];
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
                $bill_set_arr = $this->InitialInvoice->query($sel_setup_qry);
                foreach($bill_set_arr as $inv)
                {    $data[$clientName]['setupbilled'] += $inv['ti']['grnd'];    }    

                $selec_setup_payment_qry = "SELECT cm.client,ti.id,ti.bill_no,ti.total,ti.grnd,sum(bpp.bill_passed) bill_passed FROM 
     tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('Setup Cost','Development Cost')
    AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no "; 
                    $collection_setup_arr = $this->BillMaster->query($selec_setup_payment_qry);
                    foreach($collection_setup_arr as $coll_det)
                    {    $data[$clientName]['setupcoll'] += $coll_det['0']['bill_passed']; }

                $sel_billing_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category in ('Talk Time','Topup') AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date'  and bill_no!=''";
                $bill_det_arr = $this->InitialInvoice->query($sel_billing_qry);


                foreach($bill_det_arr as $inv)
                {
                    $data[$clientName]['billed'] += $inv['ti']['grnd'];
                    $fin_year = $inv['ti']['finance_year'];
                    $company_name = $cost['cm']['company_name'];
                    $branch = $inv['ti']['branch_name'];
                    $bill_no = $inv['ti']['bill_no'];
                    //$net_amount = $inv['ti']['total'];
                }

                $selec_all_payment_qry = "SELECT cm.client,ti.id,ti.bill_no,ti.total,ti.grnd,sum(bpp.bill_passed) bill_passed FROM 
     tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('Talk Time','Topup')
    AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no "; 
                    $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);

                    foreach($collection_det_arr as $coll_det)
                    {
                        //$data[$clientName]['coll'] += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                        $data[$clientName]['coll'] += $coll_det['0']['bill_passed'];
                    }

                

                $selec_all_payment_qry = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
     tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('subscription')
    AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no"; 
                    $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);

                    foreach($collection_det_arr as $coll_det)
                    {
                        //echo $month;exit;
                        $subs_coll +=$coll_det['0']['bill_passed'];
                        /*if($coll_det['ti']['month']==$month)
                        {
                            //$subs_coll += $coll_det['bpp']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                            $subs_coll += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']); 
                        }
                        else
                        {
                           // $data[$clientName]['coll'] += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                             //$data[$clientName]['coll'] += $coll_det['0']['bill_passed'];
                            $subs_coll2 +=$coll_det['0']['bill_passed'];
                        }*/
                    }

                    $selec_all_payment_qry_for_release = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
     tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('subscription')
    AND ti.month='$month'   GROUP BY ti.bill_no"; 
                    $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry_for_release);
                    $subs_coll3 =0;
                    foreach($collection_det_arr as $coll_det)
                    {
                        $subs_coll3 += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']); 
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
                $no_of_month2 = 30;
                $start_date1 = strtotime($start_date);
                switch ($PeriodType) {
                    case "YEAR":
                        $pamnt= $RentalAmount; 
                        $no_of_month2 = 365;
                        break;
                    case "Half":
                        $pamnt= round($RentalAmount/2,2);
                        $no_of_month2 = 182.5;
                        break;
                    case "Quater":
                        $pamnt= round($RentalAmount/4,2);
                        $no_of_month2 = 91.25; 
                        break;
                    default:
                        $pamnt= round($RentalAmount/12,2);
                        $no_of_month2 = 30.41;
                    }

                if(1)
                {
                     //echo date('Y-m-d',$start_date1);exit;
                    //echo $no_of_month2;exit;
                    $day_diff = strtotime($end_date)-$start_date1;
                    $day_diff = round($day_diff / (60 * 60 * 24)); 
                    $year_diff = round($day_diff/$no_of_month2);
                    $year = 0;
                    if(strtolower($PeriodType)=='month')
                    {
                        $year = floor($day_diff/365);
                    }
                    else
                    {
                        $year = floor($year_diff/365);
                    }
                    
                    
                    if($year)
                    {
                        $start_date1 = strtotime($start_date." +$year years");
                        if(strtolower($PeriodType)=='month')
                        {
                            $start_date = date('Y-m-d',$start_date1);
                        }
                        
                    }
                    //echo date('Y-m-d',$start_date1);exit;
                    $day_diff2 = strtotime($end_date)-$start_date1;
                    $day_diff2 = round($day_diff2 / (60 * 60 * 24)); 
                    //echo date('Y-m-d',$start_date1);exit;
                    $period = round($day_diff2/$no_of_month2);
                    $add_old_period = round($period*$no_of_month2);
                    $add_old_period -=30; 
//                    if(strtolower($PeriodType)!='month')
//                    {
//                        $add_old_period -=30; 
//                    }
                    
                    //echo $start_date;exit;
                    $start_date1 = strtotime($start_date." +$add_old_period days");
                    $start_date  = date('Y-m-d',$start_date1);
                    if(strtolower($PeriodType)=='month')
                    {
                        $start_date1 = strtotime($start_date." +1 days");
                    }
                    $month_start_date2 =  date('Y-m-d',$start_date1);
                    //echo $start_date;exit;
                    //echo date('Y-m-d',$start_date1);exit;
                    $periods_days_add =$no_of_month -30 ;
                    
                    
                    #$start_date1 = strtotime($start_date." +$periods_days_add days"); 
                    //echo date('Y-m-d',$start_date1);exit;
                   #$start_date1 = strtotime(date('2023-01-d',$start_date1));
                }
                

                //exit;
                #$month_start_date2 = date('Y-m-d',$start_date1); 
                //echo $month_start_date2;exit;
                $month_end_date2 = date('Y-m-d',strtotime($month_start_date2." +$no_of_month days"));
                if(strtolower($PeriodType)=='month')
                {
                    $month_end_date2 = date('Y-m-d',strtotime($month_end_date2." -1 days"));
                }
                //echo $month_end_date2;exit;
                $sel_subs_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category='subscription' AND ti.invoiceDate BETWEEN '$month_start_date2' AND '$month_end_date2' and bill_no!=''";
                $bill_subs_arr = $this->InitialInvoice->query($sel_subs_qry);
                //echo $sel_subs_qry;exit;
                $subs_coll = 0; $subs_coll2 = 0;
                foreach($bill_subs_arr as $inv)
                {
                    $fin_year = $inv['ti']['finance_year'];
                    $company_name = $cost['cm']['company_name'];
                    $branch = $inv['ti']['branch_name'];
                    $bill_no = $inv['ti']['bill_no'];
                    $data[$clientName]['subbilled'] += $inv['ti']['grnd'];
                    $sub_start_date = $inv['ti']['subs_start_date'];
                    if(!empty($sub_start_date))
                    {
                        $data[$clientName]['subbilled_as_sub_date'] += $inv['ti']['grnd'];
                    }
                    else if(strtotime($sub_start_date)<=strtotime(date('Y-m-d')))
                    {
                        $data[$clientName]['subbilled_as_sub_date'] += $inv['ti']['grnd'];
                    }
                    //$net_amount = $inv['ti']['total'];

                   /*$selec_all_payment_qry = "select * from bill_pay_particulars bpp"
                            . " where financial_year='$fin_year' and company_name='$company_name' and branch_name='$branch'"
                            . " and pay_dates between '$month_start_date' and '$month_end_date' and bill_no = SUBSTRING_INDEX('$bill_no','/',1)"; 
                    $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);

                    foreach($collection_det_arr as $coll_det)
                    {
                        $subs_coll += $coll_det['bpp']['bill_passed']-($inv['ti']['grnd']-$inv['ti']['total']);
                    }*/
                }
                
                if(empty($data[$clientName]['subbilled_as_sub_date']))
                {
                    $sel_subs_qry2 = "select * from tbl_invoice ti where cost_center='$cost_center' and category='subscription' AND ti.subs_start_date BETWEEN '$month_start_date2' AND '$month_end_date2' and bill_no!=''";
                    $bill_subs_arr2 = $this->InitialInvoice->query($sel_subs_qry2);
                    foreach($bill_subs_arr2 as $inv)
                    {
                        $sub_start_date = $inv['ti']['subs_start_date'];
                        if(!empty($sub_start_date)){
                            $data[$clientName]['subbilled'] += $inv['ti']['grnd'];
                        }
                    }
                }

                $sel_subs_qry2 = "select * from tbl_invoice ti where cost_center='$cost_center' and category='first_bill' AND ti.invoiceDate BETWEEN '$month_start_date2' AND '$month_end_date2' and bill_no!='' limit 1";
                if(empty($bal_det['0']['bm']['start_date']))
                {
                   $sel_subs_qry2 = "select * from tbl_invoice ti where cost_center='$cost_center' and category='first_bill' and bill_no!='' limit 1"; 
                   //echo $sel_subs_qry2;exit;
                }
                
                
                
                $bill_subs_arr2 = $this->InitialInvoice->query($sel_subs_qry2);
                foreach($bill_subs_arr2 as $inv2)
                {
                    $initial_id = $inv2['ti']['id'];
                    $sel_subs_qry3 = "SELECT * FROM inv_particulars ti WHERE initial_id='$initial_id' and sub_category='subscription' limit 1";
                    $bill_subs_arr3 = $this->InitialInvoice->query($sel_subs_qry3);
                    $inv = $bill_subs_arr3[0];
                    $fin_year = $inv2['ti']['finance_year'];
                    $company_name = $cost['cm']['company_name'];
                    $branch = $inv2['ti']['branch_name'];
                    $bill_no = $inv2['ti']['bill_no'];
                    $data[$clientName]['subbilled'] += round($inv['ti']['amount']*1.18);
                    $sub_start_date = $inv2['ti']['subs_start_date'];
                    if(!empty($sub_start_date))
                    {
                        $data[$clientName]['subbilled_as_sub_date'] += round($inv['ti']['amount']*1.18);
                    }
                    else if(strtotime($sub_start_date)<=strtotime(date('Y-m-d')))
                    {
                        $data[$clientName]['subbilled_as_sub_date'] += round($inv['ti']['amount']*1.18);
                    }
                    
                }

                $datediff = strtotime($end_date) - $start_date1;
                //echo date('Y-m-d',$start_date1);exit;
                $noofday = round($datediff / (60 * 60 * 24));
                $subs_penP =0;
                $NewRentalAmount =0;
                $no_of_month = 0;
                $no_of_days = 0;
               # $noofday +=30 ;
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
                //echo $subs_val;exit;
                //echo "$today_date > $start_date";exit;
                if($data[$clientName]['status']=='Active' && $subs_val>0 && strtotime($today_date)>=strtotime($start_date))
                {
                    $last_month = $start_date;
                      //$today_date = '2022-05-17'; 
                    
                    $subs_start_date = date('Y-m-d',strtotime($start_date." +30 days"));  
                    $data[$clientName]['subs_val'] = $subs_val; 
                    $data[$clientName]['due_date'] = 'Immediate';
                    $data[$clientName]['sub_start_date'] = $subs_start_date;  
                    if($no_of_days==30)
                    {
                        //$subs_start_date = date('Y-m-d',strtotime($subs_start_date." +1 days")); 
                        $data[$clientName]['sub_end_date'] =  date('Y-m-d',strtotime($subs_start_date." +$no_of_days days"));
                    }
                    else
                    {
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
    
    
    
    $this->set('data4',$data4);
    $this->set('month',$selectedMonth);
    $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","company_name"),'conditions'=>" is_dd_client='1' and `status` != 'CL'",'order'=>array('Company_name'=>'asc')));
    $client = array('All'=>'All')+$client;
    $this->set('client_det',$client);
}


public function index_ledger_bhanu() 
{

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
        $today_date = date( 'Y-m-d', strtotime( $to_date . ' -1 day' ) );
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
        
       $client_qry = "SELECT * FROM registration_master rm WHERE `status` != 'CL' and is_dd_client='1'  $company_qry $client_status_qry ORDER BY status";
       //echo $client_qry;exit;
        $client_det_arr = $this->RegistrationMaster->query($client_qry);
        
        
        
        foreach($client_det_arr as $clr)
        {
            $clientId = $clr['rm']['company_id'];
            $clientName = $clr['rm']['company_name'];
            $data[$clientName]['phone_no'] = $clr['rm']['phone_no'];
            $data[$clientName]['clientEmail'] = $clr['rm']['email'];
            $createdate_for_client_to_first_bill = $clr['rm']['create_date'];
            
            if($clr['rm']['status']=='A')
            {
                $data[$clientName]['status'] = 'Active';
            }
            else if($clr['rm']['status']=='D')
            {
                $data[$clientName]['status'] = 'De-Active';
            }
            else if($clr['rm']['status']=='H')
            {
                $data[$clientName]['status'] = 'Hold';
            }
            else if($clr['rm']['status']=='CL')
            {
                $data[$clientName]['status'] = 'Close';
            }
            $first_bill = $data[$clientName]['first_bill'] =  $clr['rm']['first_bill'];
            $data[$clientName]['clientId'] = $clientId;
            $sel_start_date = "SELECT start_date FROM `balance_master` bm WHERE clientId='$clientId' limit 1";
            $rsc_start_date=$this->RegistrationMaster->query($sel_start_date);
            $record_start_date = $rsc_start_date['0']['bm']['start_date'];
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
            
            $op2_ledger = $clr['rm']['op'];
            #echo $op2_ledger;die;
            $bill2_ledger = 0;
            $coll2_ledger = 0;
            
            $op2_credit = 0;
            $fr_release_credit = 0;
            $consume_credit = 0;
            $op2_consume_credit_testing = 0;
            //for waiver 
            $waiver_qry = "SELECT * FROM waiver_master wm WHERE clientId='$clientId' ";
            $waiver_list = $this->RegistrationMaster->query($waiver_qry);
            foreach($waiver_list as $wl)
            {
                $op2_credit += $wl['wm']['Balance'];
            }
            //echo "SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$clientId'";exit;
            $op_det_ledger = $this->RegistrationMaster->query("SELECT * FROM `billing_ledger` bl WHERE fin_year='2022' and fin_month='Apr' and clientId = '$clientId'");
            if(!empty($op_det_ledger))
            {
                $talktime = $op_det_ledger['0']['bl']['talk_time'];
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
                    $data[$clientName]['talk_time'] = round($op_det_ledger['0']['bl']['talk_time'],2);
                }

                $data[$clientName]['ledger_sub'] = round($op_det_ledger['0']['bl']['subs'],2);
                $data[$clientName]['ledger_topup'] = round($op_det_ledger['0']['bl']['topup'],2);
                $data[$clientName]['ledger_setup'] = round($op_det_ledger['0']['bl']['setup_cost'],2);
                $data[$clientName]['firstbilled'] += round($op_det_ledger['0']['bl']['firstbilled'],2);
                
                
            }

            $billing_opening_balance = "billing_opening_balance";
            if($current_month!=$Nmonth)
            {
                $billing_opening_balance = "billing_opening_balance_history";
            }
            $op_det = $this->RegistrationMaster->query("SELECT op_bal,cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$clientId' and fin_year='$year' and fin_month='$Nmonth'");
            //print_r($op_det);exit;
            if(!empty($op_det['0']['bob']['op_dd']))
            {
                $data[$clientName]['op_dd'] = $op_det['0']['bob']['op_dd'];
            }
            else
            {
                $op_det = $this->RegistrationMaster->query("SELECT op_bal,cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$clientId' and fin_year='$year' and fin_month='$Nmonth'");
                if(!empty($op_det['0']['bob']['op_dd']))
                {
                    $data[$clientName]['op_dd'] = $op_det['0']['bob']['op_dd'];
                }
            }
            $data[$clientName]['cs_bal'] = $op_det['0']['bob']['cs_bal'];
            //$data[$clientName]['fr_val'] = $op_det['0']['bob']['fr_val'];
            //$data[$clientName]['adv_val'] = $op_det['0']['bob']['adv_val'];

            $plan_get_qry = "SELECT PlanId,start_date FROM balance_master bm WHERE clientId='$clientId' limit 1"; 
            $bal_det = $this->RegistrationMaster->query($plan_get_qry);
            $planId = $bal_det['0']['bm']['PlanId'];
            $plan_det_qry = "SELECT RentalAmount,PeriodType,Balance,CreditValue,SetupCost,DevelopmentCost FROM `plan_master` pm WHERE id='$planId' limit 1"; 
            $plan_det = $this->RegistrationMaster->query($plan_det_qry);
            //$FreeValue = $plan_det['0']['pm']['CreditValue'];
            $FreeValue = round($plan_det['0']['pm']['Balance']/12);
            $data[$clientName]['RentalAmount'] = $plan_det['0']['pm']['RentalAmount'];
            $data[$clientName]['Balance'] = $plan_det['0']['pm']['Balance'];
            $SetupCost = $plan_det['0']['pm']['SetupCost'];
            $developmentCost = $plan_det['0']['pm']['DevelopmentCost'];
            $start_date = $bal_det['0']['bm']['start_date']; 
            $end_date = $to_date;
            $RentalAmount = $plan_det['0']['pm']['RentalAmount'];
            $PeriodType = $plan_det['0']['pm']['PeriodType'];
            $Balance = $plan_det['0']['pm']['Balance'];
            $data[$clientName]['free_value'] =round($FreeValue,2);
            $rental_credit = $plan_det['0']['pm']['RentalAmount'];
            $balance_credit = $plan_det['0']['pm']['Balance'];
            
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
                $pamnt_first = $pamnt;
                    
                    $cost_cat_arr = $this->CostCenterMaster->query("select * from cost_master cm where dialdesk_client_id='$clientId' limit 1");
                    $cost_adv_arr = $this->CostCenterMaster->query("SELECT *,SUM(bill_passed) advance FROM `bill_pay_advance` bpa WHERE bill_no='{$cost_cat_arr[0]['cm']['id']}'");
                    if(!empty($cost_adv_arr))
                    {
                        $data[$clientName]['advance'] = $cost_adv_arr['0']['bpa']['advance'];
                    }
                    
                        
               $is_first_bill_made = false; 
            if(strtotime($createdate_for_client_to_first_bill)>=strtotime("2022-04-01 00:00:00"))
            {
                
                $sel_first_plan_billed = "SELECT ti.* FROM tbl_invoice ti 
                INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center 
                WHERE ti.category in ('first_bill','Setup Cost','DevelopmentCost') and ti.bill_no!='' and ti.status='0' and cm.dialdesk_client_id='$clientId'";
                //echo $sel_first_plan_billed;exit
                $billed_first_plan = $this->InitialInvoice->query($sel_first_plan_billed);
                foreach($billed_first_plan as $first_bill)
                {
                    $initial_id = $first_bill['ti']['id'];
                    $additional_cost = array('SetupCost','DevelopmentCost','Subscription');
                    $category = $first_bill['ti']['category'];
                    
                    //echo $pamnt;exit;
                    if($category=='first_bill')
                    {
                        $is_first_bill_made = true;
                        foreach($additional_cost as $scost)
                        {
                            $select_subs = "select ip.sub_category,ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='$scost'";
                            $rsc_subs_arr = $this->InitialInvoice->query($select_subs);
                            //print_r($rsc_subs_arr);
                            foreach($rsc_subs_arr as $sb)
                            {   
                                if(strtolower($scost)==strtolower('SetupCost'))
                                {$SetupCost -=$sb['ip']['amount'];}
                                else if(strtolower($scost)==strtolower('DevelopmentCost'))
                                {$developmentCost -=$sb['ip']['amount'];}
                                else if(strtolower($scost)==strtolower('Subscription'))
                                {$pamnt -=$sb['ip']['amount'];}
                            }
                        }
                        
                        //check whether first subscription made or not.
                        $select_subs = "select ip.sub_category,ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                        $rsc_subs_arr = $this->InitialInvoice->query($select_subs);
                        #print_r($rsc_subs_arr);;exit;
                        if(empty($rsc_subs_arr))
                        {
                            $sel_first_subs = "SELECT ti.* FROM tbl_invoice ti 
                INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center 
                WHERE ti.category = 'Subscription' and ti.bill_no!='' and ti.status='0' and cm.dialdesk_client_id='$clientId' limit 1";
                            $rsc_subs_arr = $this->InitialInvoice->query($sel_first_subs);
                            
                            if(empty($rsc_subs_arr) && !empty($start_date))
                            {
                                $data[$clientName]['subs_val'] = $pamnt_first*1.18;  
                                $data[$clientName]['due_date'] = 'Immediate';
                                $data[$clientName]['sub_start_date'] = $start_date; 
                                $data[$clientName]['sub_end_date'] =  date('Y-m-d',strtotime($start_date." +$no_of_month days"));
                            }
                            
                        }
                        
                    }
                    else
                    {
                        if(strtolower($category)==strtolower('Setup Cost'))
                        { $SetupCost -=$first_bill['ti']['total']; }
                        else if(strtolower($category)==strtolower('Development Cost'))
                        {$developmentCost -=$first_bill['ti']['total'];}
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
           #print_r($data);die;
           
            foreach($cost_cat_arr as $cost)
            {
                $cost_center = $cost['cm']['cost_center'];  
                $data[$clientName]['cost_center'] = $cost_center;
                $data[$clientName]['month'] = $month;
                $bill_company = $cost['cm']['company_name'];
                #echo $op2_ledger;die;
                //getting opening as on from date
                if(strtotime($from_date)>=strtotime('2022-04-01'))
                {
                    $sel_billed_fromdateqry = "select ti.* from tbl_invoice ti where  ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (ti.invoiceDate >= '2022-04-01' AND ti.invoiceDate <= '$day_before')  "; #and bill_no!='' 
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

                    #echo $op2_credit;die;
                    //getting advance from this client
                        $cost_adv_arr = $this->CostCenterMaster->query("SELECT *,SUM(bill_passed) advance FROM `bill_pay_advance` bpa WHERE bill_no='$cost_id' AND  date(bpa.pay_dates)  between '2022-04-01' and '$day_before'");
                        if(!empty($cost_adv_arr))
                        {
                            $op2_ledger -= $cost_adv_arr['0']['0']['advance'];
                        }
                        //getting actual outstanding as on from date.
                        $select_payment_fromdateqry = "SELECT sum(bpp.bill_passed) bill_passed FROM 
                        bill_pay_particulars bpp inner join tbl_invoice ti on bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
                        AND bpp.financial_year=ti.finance_year and bpp.branch_name=ti.branch_name AND bpp.company_name='$bill_company' 
                        where ti.cost_center='$cost_center' and date(bpp.pay_dates)  between '2022-04-01' and '$day_before' ";  
                        
                        $collection_fromdate_arr = $this->BillMaster->query($select_payment_fromdateqry);
                        foreach($collection_fromdate_arr as $coll_det)
                        {    $op2_ledger -= $coll_det['0']['bill_passed']; }
                       //getting consumption from table 
                        $select_consumption = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId' and date(cm_date)>='$start_date' and (cm_date >= '2022-04-01' AND cm_date <= '$day_before')";
                        $rsc_consuption_arr = $this->RegistrationMaster->query("$select_consumption");
                        //print_r($rsc_consuption_arr);exit;
                        foreach($rsc_consuption_arr as $consume)
                        {
                            $op2_credit -= $consume[0]['total'];
                        }
                        #echo $op2_credit;die;
                        ///////////////////////// testing consumption ////////////////////////
                        $select_consumption_testing = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId'  and (cm_date >= '2022-04-01' AND cm_date <= '$day_before')";
                        $rsc_consuption_testing_arr = $this->RegistrationMaster->query("$select_consumption_testing");
                        //print_r($rsc_consuption_arr);exit;
                        foreach($rsc_consuption_testing_arr as $consume)
                        {
                            $op2_consume_credit_testing += $consume[0]['total'];
                        }
                        ///////////////////////// end testing consumption ////////////////////////
                }

                #echo $op2_ledger;die;
                
                
                //getting opening as on FromDate to ToDate
                $sel_billed_todateqry = "select ti.* from tbl_invoice ti where ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (  ti.invoiceDate >= '$from_date' AND ti.invoiceDate <= '$to_date')  "; #and bill_no!=''
                $billed_todateqry_arr = $this->InitialInvoice->query($sel_billed_todateqry);
                #echo $op2_ledger;die;
                foreach($billed_todateqry_arr as $inv_det)
                {
                    $bill_no = $inv_det['ti']['bill_no'];
                    $bill_finyear = $inv_det['ti']['finance_year'];
                    $bill_company = $cost['cm']['company_name'];
                    $bill2_ledger +=$inv_det['ti']['grnd'];
                    $initial_id = $inv_det['ti']['id'];
                    //$bill_branch = $cost['cm']['branch'];     
                    
                    if(strtolower($inv_det['ti']['category'])==strtolower('first_bill'))
                    {
                            //check whether first bill have subscritpion amount
                        $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                        $rsc_subs_arr = $this->InitialInvoice->query($select_subs);
                        foreach($rsc_subs_arr as $sb)
                        {
                             $fr_release_credit +=$this->get_credit_from_subs_value($rental_credit,$balance_credit,$sb['ip']['amount']);
                        }

                    }
                    else if(strtolower($inv_det['ti']['category'])==strtolower('subscription'))
                    {
                        $fr_release_credit +=$this->get_credit_from_subs_value($rental_credit,$balance_credit,$inv_det['ti']['total']);
                    }
                    else if(strtolower($inv_det['ti']['category'])==strtolower('Talk Time') || strtolower($inv_det['ti']['category'])==strtolower('Topup'))
                    {
                        $fr_release_credit +=$inv_det['ti']['total'];
                    }
                    
                    
                }
                //getting advance from this client
                $cost_adv_arr = $this->CostCenterMaster->query("SELECT *,SUM(bill_passed) advance FROM `bill_pay_advance` bpa WHERE bill_no='$cost_id' AND  date(bpa.pay_dates)  between '$to_date' and '$from_date'");
                if(!empty($cost_adv_arr))
                {
                    $coll2_ledger += $cost_adv_arr[0]['0']['advance'];
                }
                   //getting actual outstanding as on from date.  
               $select_payment_qry = "SELECT sum(bpp.bill_passed) bill_passed FROM 
                bill_pay_particulars bpp inner join tbl_invoice ti on bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
                AND bpp.financial_year=ti.finance_year and bpp.branch_name=ti.branch_name AND bpp.company_name='$bill_company' 
                where ti.cost_center='$cost_center' and date(bpp.pay_dates)  between '$from_date' and '$to_date' ";    

                $collection_billingtodate_arr = $this->BillMaster->query($select_payment_qry);
                foreach($collection_billingtodate_arr as $coll_det)
                {    $coll2_ledger += $coll_det['0']['bill_passed']; }
                       
                //getting file detail 
                
                
                  
                //getting consumption from table

                 $select_consumption = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId' and date(cm_date)>='$start_date' and DATE(cm_date) between '$from_date' and '$to_date'";
                 $rsc_consuption_arr = $this->RegistrationMaster->query("$select_consumption");
                 //print_r($rsc_consuption_arr);exit;
                 foreach($rsc_consuption_arr as $consume)
                 {
                     $consume_credit += $consume[0]['total'];
                 }

                 echo  $consume_credit;
                 
                 ///////////////////////// testing consumption ////////////////////////
                        $select_consumption_testing = "SELECT sum(cm_total) total FROM billing_consume_daily  WHERE client_id='$clientId'  and DATE(cm_date) between '$from_date' and '$to_date'";
                        $rsc_consuption_testing_arr = $this->RegistrationMaster->query("$select_consumption_testing");
                        //print_r($rsc_consuption_arr);exit;
                        foreach($rsc_consuption_testing_arr as $consume)
                        {
                            $op2_consume_credit_testing += $consume[0]['total'];
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
                $bill_set_arr = $this->InitialInvoice->query($sel_setup_qry);
                foreach($bill_set_arr as $inv)
                {    $data[$clientName]['setupbilled'] += $inv['ti']['grnd'];    }    

                    $selec_setup_payment_qry = "SELECT cm.client,ti.id,ti.bill_no,ti.total,ti.grnd,sum(bpp.bill_passed) bill_passed FROM 
                    tbl_invoice ti 
                    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
                    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
                    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
                    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('Setup Cost','Development Cost')
                    AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no "; 
                    $collection_setup_arr = $this->BillMaster->query($selec_setup_payment_qry);
                    foreach($collection_setup_arr as $coll_det)
                    {    $data[$clientName]['setupcoll'] += $coll_det['0']['bill_passed']; }

                    $sel_billing_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category in ('Talk Time','Topup') AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date'  and bill_no!=''";
                    $bill_det_arr = $this->InitialInvoice->query($sel_billing_qry);


                    foreach($bill_det_arr as $inv)
                    {
                        $data[$clientName]['billed'] += $inv['ti']['grnd'];
                        $fin_year = $inv['ti']['finance_year'];
                        $company_name = $cost['cm']['company_name'];
                        $branch = $inv['ti']['branch_name'];
                        $bill_no = $inv['ti']['bill_no'];
                        //$net_amount = $inv['ti']['total'];
                    }

                    $selec_all_payment_qry = "SELECT cm.client,ti.id,ti.bill_no,ti.total,ti.grnd,sum(bpp.bill_passed) bill_passed FROM 
                    tbl_invoice ti 
                    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
                    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
                    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
                    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('Talk Time','Topup')
                    AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no "; 
                    $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);

                    foreach($collection_det_arr as $coll_det)
                    {
                        //$data[$clientName]['coll'] += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                        $data[$clientName]['coll'] += $coll_det['0']['bill_passed'];
                    }

                

                    $selec_all_payment_qry = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
                    tbl_invoice ti 
                    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
                    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
                    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
                    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('subscription')
                    AND date(bpp.createdate)  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no"; 
                    $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);

                    foreach($collection_det_arr as $coll_det)
                    {
                        //echo $month;exit;
                        $subs_coll +=$coll_det['0']['bill_passed'];
                        /*if($coll_det['ti']['month']==$month)
                        {
                            //$subs_coll += $coll_det['bpp']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                            $subs_coll += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']); 
                        }
                        else
                        {
                           // $data[$clientName]['coll'] += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                             //$data[$clientName]['coll'] += $coll_det['0']['bill_passed'];
                            $subs_coll2 +=$coll_det['0']['bill_passed'];
                        }*/
                    }

                    $selec_all_payment_qry_for_release = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
                    tbl_invoice ti 
                    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
                    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
                    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
                    WHERE  bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('subscription')
                    AND ti.month='$month'   GROUP BY ti.bill_no"; 
                    $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry_for_release);
                    $subs_coll3 =0;
                    foreach($collection_det_arr as $coll_det)
                    {
                        $subs_coll3 += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']); 
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
                $no_of_month2 = 30;
                $start_date1 = strtotime($start_date);
                switch ($PeriodType) {
                    case "YEAR":
                        $pamnt= $RentalAmount; 
                        $no_of_month2 = 365;
                        break;
                    case "Half":
                        $pamnt= round($RentalAmount/2,2);
                        $no_of_month2 = 182.5;
                        break;
                    case "Quater":
                        $pamnt= round($RentalAmount/4,2);
                        $no_of_month2 = 91.25; 
                        break;
                    default:
                        $pamnt= round($RentalAmount/12,2);
                        $no_of_month2 = 30.41;
                    }

                if(1)
                {
                     //echo date('Y-m-d',$start_date1);exit;
                    //echo $no_of_month2;exit;
                    $day_diff = strtotime($end_date)-$start_date1;
                    $day_diff = round($day_diff / (60 * 60 * 24)); 
                    $year_diff = round($day_diff/$no_of_month2);
                    $year = 0;
                    if(strtolower($PeriodType)=='month')
                    {
                        $year = floor($day_diff/365);
                    }
                    else
                    {
                        $year = floor($year_diff/365);
                    }
                    
                    
                    if($year)
                    {
                        $start_date1 = strtotime($start_date." +$year years");
                        if(strtolower($PeriodType)=='month')
                        {
                            $start_date = date('Y-m-d',$start_date1);
                        }
                        
                    }
                    //echo date('Y-m-d',$start_date1);exit;
                    $day_diff2 = strtotime($end_date)-$start_date1;
                    $day_diff2 = round($day_diff2 / (60 * 60 * 24)); 
                    //echo date('Y-m-d',$start_date1);exit;
                    $period = round($day_diff2/$no_of_month2);
                    $add_old_period = round($period*$no_of_month2);
                    $add_old_period -=30; 
                    // if(strtolower($PeriodType)!='month')
                    // {
                    // $add_old_period -=30; 
                    //}
                    
                    //echo $start_date;exit;
                    $start_date1 = strtotime($start_date." +$add_old_period days");
                    $start_date  = date('Y-m-d',$start_date1);
                    if(strtolower($PeriodType)=='month')
                    {
                        $start_date1 = strtotime($start_date." +1 days");
                    }
                    $month_start_date2 =  date('Y-m-d',$start_date1);
                    //echo $start_date;exit;
                    //echo date('Y-m-d',$start_date1);exit;
                    $periods_days_add =$no_of_month -30 ;
                    
                    
                    #$start_date1 = strtotime($start_date." +$periods_days_add days"); 
                    //echo date('Y-m-d',$start_date1);exit;
                   #$start_date1 = strtotime(date('2023-01-d',$start_date1));
                }
                

                //exit;
                #$month_start_date2 = date('Y-m-d',$start_date1); 
                //echo $month_start_date2;exit;
                $month_end_date2 = date('Y-m-d',strtotime($month_start_date2." +$no_of_month days"));
                if(strtolower($PeriodType)=='month')
                {
                    $month_end_date2 = date('Y-m-d',strtotime($month_end_date2." -1 days"));
                }
                //echo $month_end_date2;exit;
                $sel_subs_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category='subscription' AND ti.invoiceDate BETWEEN '$month_start_date2' AND '$month_end_date2' and bill_no!=''";
                $bill_subs_arr = $this->InitialInvoice->query($sel_subs_qry);
                //echo $sel_subs_qry;exit;
                $subs_coll = 0; $subs_coll2 = 0;
                foreach($bill_subs_arr as $inv)
                {
                    $fin_year = $inv['ti']['finance_year'];
                    $company_name = $cost['cm']['company_name'];
                    $branch = $inv['ti']['branch_name'];
                    $bill_no = $inv['ti']['bill_no'];
                    $data[$clientName]['subbilled'] += $inv['ti']['grnd'];
                    $sub_start_date = $inv['ti']['subs_start_date'];
                    if(!empty($sub_start_date))
                    {
                        $data[$clientName]['subbilled_as_sub_date'] += $inv['ti']['grnd'];
                    }
                    else if(strtotime($sub_start_date)<=strtotime(date('Y-m-d')))
                    {
                        $data[$clientName]['subbilled_as_sub_date'] += $inv['ti']['grnd'];
                    }
                    //$net_amount = $inv['ti']['total'];

                   /*$selec_all_payment_qry = "select * from bill_pay_particulars bpp"
                            . " where financial_year='$fin_year' and company_name='$company_name' and branch_name='$branch'"
                            . " and pay_dates between '$month_start_date' and '$month_end_date' and bill_no = SUBSTRING_INDEX('$bill_no','/',1)"; 
                    $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);

                    foreach($collection_det_arr as $coll_det)
                    {
                        $subs_coll += $coll_det['bpp']['bill_passed']-($inv['ti']['grnd']-$inv['ti']['total']);
                    }*/
                }

                $sel_subs_qry2 = "select * from tbl_invoice ti where cost_center='$cost_center' and category='first_bill' AND ti.invoiceDate BETWEEN '$month_start_date2' AND '$month_end_date2' and bill_no!='' limit 1";
                $bill_subs_arr2 = $this->InitialInvoice->query($sel_subs_qry2);
                foreach($bill_subs_arr2 as $inv2)
                {
                    $initial_id = $inv2['ti']['id'];
                    $sel_subs_qry3 = "SELECT * FROM inv_particulars ti WHERE initial_id='$initial_id' and sub_category='subscription' limit 1";
                    $bill_subs_arr3 = $this->InitialInvoice->query($sel_subs_qry3);
                    $inv = $bill_subs_arr3[0];
                    $fin_year = $inv2['ti']['finance_year'];
                    $company_name = $cost['cm']['company_name'];
                    $branch = $inv2['ti']['branch_name'];
                    $bill_no = $inv2['ti']['bill_no'];
                    $data[$clientName]['subbilled'] += round($inv['ti']['amount']*1.18);
                    $sub_start_date = $inv2['ti']['subs_start_date'];
                    if(!empty($sub_start_date))
                    {
                        $data[$clientName]['subbilled_as_sub_date'] += round($inv['ti']['amount']*1.18);
                    }
                    else if(strtotime($sub_start_date)<=strtotime(date('Y-m-d')))
                    {
                        $data[$clientName]['subbilled_as_sub_date'] += round($inv['ti']['amount']*1.18);
                    }
                    
                }

                $datediff = strtotime($end_date) - $start_date1;
                //echo date('Y-m-d',$start_date1);exit;
                $noofday = round($datediff / (60 * 60 * 24));
                $subs_penP =0;
                $NewRentalAmount =0;
                $no_of_month = 0;
                $no_of_days = 0;
               # $noofday +=30 ;
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
                //echo $subs_val;exit;
                //echo "$today_date > $start_date";exit;
                if($data[$clientName]['status']=='Active' && $subs_val>0 && strtotime($today_date)>=strtotime($start_date))
                {
                    $last_month = $start_date;
                      //$today_date = '2022-05-17'; 
                    $subs_start_date = date('Y-m-d',strtotime($start_date." +30 days"));  
                    $data[$clientName]['subs_val'] = $subs_val; 
                    $data[$clientName]['due_date'] = 'Immediate';
                    $data[$clientName]['sub_start_date'] = $subs_start_date;  
                    $data[$clientName]['sub_end_date'] =  date('Y-m-d',strtotime($last_month." +$no_of_days days"));
                }
            }

            
            
            
                
        }
        
        
        //sorting array on a bases of active,hold,de-active,closed
        $data2 = array();
        #print_r($data);die;
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
    #print_r($data4);exit;
    
    $this->set('data4',$data4);
    $this->set('month',$selectedMonth);
    $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","company_name"),'conditions'=>"`status` != 'CL'",'order'=>array('Company_name'=>'asc')));
    $client = array('All'=>'All')+$client;
    $this->set('client_det',$client);
}








}
?>