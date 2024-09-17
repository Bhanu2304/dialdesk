    <?php
class ExposuresController extends AppController 
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
        $this->Auth->allow('index_ledger','index_ledger2');
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

public function index_ledger2() 
{
    //echo "site is under maintanance. will be live after 4 hours.";
    //exit;
    $this->layout='user';
    $data = array();
    $exposure_date = date('Y-m-d'); 
    $day_before = date( 'Y-m-d' );
    $finYear = '2022-23';
    $year = date('Y');
    $year_2 = date('y');
    $arr = explode('-',$finYear);
    $selectedMonth = $this->request->data['month'];
    //$selectedMonth='Feb';
    $month_arr =array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12) ;
    $month_arr_swap =array_flip($month_arr) ;
    $month_no = 0;
    
    if(empty($selectedMonth))
    {
        $Nmonth =$month = date( 'M', strtotime($day_before) );
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
        //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE company_id='278' and  `status` = 'A' and is_dd_client='1'"); 
        
        // $client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE is_dd_client='1' and  `status` = 'A' order by company_name"); 
       
        $client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE is_dd_client='1' and  `status` = 'A' ORDER BY create_date DESC"); 
       
        foreach($client_det_arr as $clr)
        {
            $clientId = $clr['rm']['company_id'];
            $clientName = $clr['rm']['company_name'];
            $data[$clientName]['phone_no'] = $clr['rm']['phone_no'];
            $data[$clientName]['clientEmail'] = $clr['rm']['email'];
            $first_bill = $data[$clientName]['first_bill'] =  $clr['rm']['first_bill'];
            $data[$clientName]['clientId'] = $clientId;
            $sel_start_date = "SELECT start_date FROM `balance_master` bm WHERE clientId='$clientId'";
            $rsc_start_date=$this->RegistrationMaster->query($sel_start_date);
            $record_start_date = $rsc_start_date['0']['bm']['start_date'];
            if(strtotime($record_start_date)>strtotime($month_end_date))
            {
                continue;
            }
            
            
            //echo "SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$clientId'";exit;
            $op_det_ledger = $this->RegistrationMaster->query("SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$clientId'");
            if(!empty($op_det_ledger))
            {
                $talktime = $op_det_ledger['0']['bl']['talk_time'];
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
                $end_date = date('Y-m-d');
                $RentalAmount = $plan_det['0']['pm']['RentalAmount'];
                $PeriodType = $plan_det['0']['pm']['PeriodType'];
                $Balance = $plan_det['0']['pm']['Balance'];
                $data[$clientName]['free_value'] =round($FreeValue,2);
                
                $no_of_month = 0;
                
                switch ($PeriodType) {
                    case "YEAR":
                        $pamnt= $RentalAmount; 
                        $no_of_month = 365;
                        break;
                    case "Half YEAR":
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
                    
                    $cost_cat_arr = $this->CostCenterMaster->query("select * from cost_master cm where dialdesk_client_id='$clientId' limit 1");
                    $cost_adv_arr = $this->CostCenterMaster->query("SELECT *,SUM(bill_passed) advance FROM `bill_pay_advance` bpa WHERE bill_no='{$cost_cat_arr[0]['cm']['id']}'");
                    if(!empty($cost_adv_arr))
                    {
                        $data[$clientName]['advance'] = $cost_adv_arr['0']['bpa']['advance'];
                    }
                    
                        if($first_bill=='0' || $first_bill==0)
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
                            $sel_firstplan_qry = "select id,grnd from tbl_invoice ti where cost_center='$cost_center' and category='first_bill' and  finance_year='$finYear' and month='$month' limit 1";
                            $sel_firstplan_arr = $this->InitialInvoice->query($sel_firstplan_qry);
                            $intital_id = $sel_firstplan_arr['0']['ti']['id'];

                            $sel_first_sub_qry = "SELECT amount FROM `inv_particulars` ip WHERE initial_id='$intital_id' and sub_category='subscription' limit 1";
                            $bill_subs_arr = $this->InitialInvoice->query($sel_first_sub_qry);
                            $data[$clientName]['new_plan_sub_cost'] = $pamnt;
                            $data[$clientName]['first_plan_value'] = $sel_firstplan_arr['0']['ti']['total'];
                            $data[$clientName]['first_plan_value_with_gst'] = $sel_firstplan_arr['0']['ti']['grnd'];
                            $data[$clientName]['firstbilled'] += $sel_firstplan_arr['0']['ti']['grnd'];
                            
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
                            if($first_subs>0)
                            {
                                $data[$clientName]['first_plan_subscoll'] = round($first_subs*1.18,2);
                            }
                        }
                
                        
            
            
            
            
            foreach($cost_cat_arr as $cost)
            {
                $cost_center = $cost['cm']['cost_center'];  
                $data[$clientName]['cost_center'] = $cost_center;
                $data[$clientName]['month'] = $month;

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

                $sel_subs_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category='subscription' AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date' and bill_no!=''";
                $bill_subs_arr = $this->InitialInvoice->query($sel_subs_qry);

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

                $start_date1 = strtotime($start_date);
                if($start_date1<strtotime('2022-01-01'))
                {
                   $start_date1 = strtotime(date('2022-01-d',$start_date1));
                }
                //exit;
                $datediff = strtotime($end_date) - $start_date1;
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
                $subs_val = round($sub_amt-$subs_coll,2);
                
                if($subs_val>0 && strtotime($today_date)>=strtotime($start_date))
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
        
    
        
        
    
    
    //print_r($data);exit;
    
    
    
    $this->set('data',$data);
    $this->set('month',$selectedMonth);
}


public function index_ledger() 
{
    
    $data = array();
    $exposure_date = date('Y-m-d'); 
    $day_before = date( 'Y-m-d', strtotime( $exposure_date . ' -1 day' ) );
    $finYear = '2022-23';
    $year = date('Y');
    $year_2 = date('y');
    $arr = explode('-',$finYear);
    $selectedMonth = $this->request->data['month'];
    //$selectedMonth='Feb';
    $month_arr =array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12) ;
    $month_arr_swap =array_flip($month_arr) ;
    $month_no = 0;
    
    if(empty($selectedMonth))
    {
        $Nmonth =$month = date( 'M', strtotime($day_before) );
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
        
        //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE company_id in ('464','465','467') and  `status` = 'A' and is_dd_client='1' order by company_name");
        //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE company_id='470' and  `status` = 'A' and is_dd_client='1'"); 
        $client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE  is_dd_client='1' and  `status` = 'A' order by company_name "); 
        foreach($client_det_arr as $clr)
        {
            $clientId = $clr['rm']['company_id'];
            $clientName = $clr['rm']['company_name'];
            $data[$clientName]['phone_no'] = $clr['rm']['phone_no'];
            $data[$clientName]['clientEmail'] = $clr['rm']['email'];
            $first_bill = $data[$clientName]['first_bill'] =  $clr['rm']['first_bill'];
            $data[$clientName]['clientId'] = $clientId;
            $sel_start_date = "SELECT start_date FROM `balance_master` bm WHERE clientId='$clientId'";
            $rsc_start_date=$this->RegistrationMaster->query($sel_start_date);
            $record_start_date = $rsc_start_date['0']['bm']['start_date'];
            if(strtotime($record_start_date)>strtotime($month_end_date))
            {
                continue;
            }
            
            
            //echo "SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$clientId'";exit;
            $op_det_ledger = $this->RegistrationMaster->query("SELECT * FROM `billing_ledger` bl WHERE fin_year='$year' and fin_month='$Nmonth' and clientId = '$clientId'");
            if(!empty($op_det_ledger))
            {
                $talktime = $op_det_ledger['0']['bl']['talk_time'];
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
                $data[$clientName]['ledger_sub_e'] = round($op_det_ledger['0']['bl']['subs'],2);
                
                $data[$clientName]['ledger_topup'] = round($op_det_ledger['0']['bl']['topup'],2);
                $data[$clientName]['ledger_setup'] = round($op_det_ledger['0']['bl']['setup_cost'],2);
                
            }

            $billing_opening_balance = "billing_opening_balance";
            /*if($current_month!=$Nmonth)
            {
                $billing_opening_balance = "billing_opening_balance_history";
            }*/
            $op_det = $this->RegistrationMaster->query("SELECT cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$clientId' and fin_year='$year' and fin_month='$Nmonth'");
            //print_r($op_det);exit;
            if(!empty($op_det['0']['bob']['op_dd']))
            {
                $data[$clientName]['op_dd'] = $op_det['0']['bob']['op_dd'];
            }
            else
            {
                $op_det = $this->RegistrationMaster->query("SELECT cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$clientId' and fin_year='$year' and fin_month='$Nmonth'");
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
                $end_date = '2022-10-31';
                $RentalAmount = $plan_det['0']['pm']['RentalAmount'];
                $PeriodType = $plan_det['0']['pm']['PeriodType'];
                $Balance = $plan_det['0']['pm']['Balance'];
                $data[$clientName]['free_value'] =round($FreeValue,2);
                
                $no_of_month = 0;
                
                switch ($PeriodType) {
                    case "YEAR":
                        $pamnt= $RentalAmount; 
                        $no_of_month = 365;
                        break;
                    case "Half YEAR":
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
                    
                    $cost_cat_arr = $this->CostCenterMaster->query("select * from cost_master cm where dialdesk_client_id='$clientId' limit 1");
                    $cost_adv_arr = $this->CostCenterMaster->query("SELECT *,SUM(bill_passed) advance FROM `bill_pay_advance` bpa WHERE bill_no='{$cost_cat_arr[0]['cm']['id']}'");
                    if(!empty($cost_adv_arr))
                    {
                        $data[$clientName]['advance'] = $cost_adv_arr['0']['bpa']['advance'];
                    }
                        if($first_bill=='0' || $first_bill==0)
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

                            $sel_first_sub_qry = "SELECT amount FROM `inv_particulars` ip WHERE initial_id='$intital_id' and sub_category='subscription'  limit 1";
                            $bill_subs_arr = $this->InitialInvoice->query($sel_first_sub_qry);
                            
                            $data[$clientName]['new_plan_sub_cost'] = $pamnt;
                            $data[$clientName]['first_plan_value'] = $sel_firstplan_arr['0']['ti']['total'];
                            $data[$clientName]['first_plan_value_with_gst'] = $sel_firstplan_arr['0']['ti']['grnd'];
                            //$data[$clientName]['firstbilled'] += $sel_firstplan_arr['0']['ti']['grnd']; 
                            if(isset($bill_subs_arr['0']['ip']['amount']))
                            {
                                $sel_firstplan_arr['0']['ti']['grnd'] -= round($bill_subs_arr['0']['ip']['amount']*1.18,2);
                                $data[$clientName]['ledger_sub'] += $bill_subs_arr['0']['ip']['amount'];
                                $data[$clientName]['ledger_sub_e'] += round($bill_subs_arr['0']['ip']['amount']*1.18,2); 
                                $data[$clientName]['ledger_setup'] +=  $sel_firstplan_arr['0']['ti']['grnd']; 
                            }
                            else
                            {
                                $data[$clientName]['ledger_setup'] +=  $sel_firstplan_arr['0']['ti']['grnd']; 
                            }
                            if(empty($sel_firstplan_arr['0']['ti']['subs_start_date']))
                            {
                                $data[$clientName]['subbilled_as_sub_date'] += round($bill_subs_arr['0']['ip']['amount']*1.18,2);
                            }
                            /*$sel_first_setup_qry = "SELECT amount FROM `inv_particulars` ip WHERE initial_id='$intital_id' and sub_category='setupcost' limit 1";
                            $bill_setup_arr = $this->InitialInvoice->query($sel_first_setup_qry);
                            if(isset($bill_subs_arr['0']['ip']['amount']))
                            {
                                $data[$clientName]['ledger_setup'] +=  $sel_firstplan_arr['0']['ti']['grnd'];
                            }*/
                            
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
                            
                            //echo "$first_bill_coll;";exit;

                            $first_bill_coll_ntgst = round(($first_bill_coll/118)*100,2);
                            $first_subs = $first_bill_coll_ntgst- $SetupCost- $developmentCost;

                            
                            

                            if($first_subs>0)
                            {
                                $data[$clientName]['ledger_subcoll'] = round($first_subs,2); 
                                $data[$clientName]['ledger_setupcoll'] = round($SetupCost*1.18,2); 
                                $data[$clientName]['first_plan_subscoll'] = round($first_subs*1.18,2);
                            }
                            else
                            {
                                 $data[$clientName]['ledger_setupcoll'] = round($first_bill_coll,2); 
                            }
                        }
                

                        //print_r($data[$clientName]); exit;
            
            
            
            foreach($cost_cat_arr as $cost)
            {
                $cost_center = $cost['cm']['cost_center'];  
                $data[$clientName]['cost_center'] = $cost_center;
                $data[$clientName]['month'] = $month;

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

                $sel_subs_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category='subscription' AND ti.invoiceDate BETWEEN '$month_start_date' AND '$month_end_date' and bill_no!=''";
                $bill_subs_arr = $this->InitialInvoice->query($sel_subs_qry);

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

                $start_date1 = strtotime($start_date);
                if($start_date1<strtotime('2022-01-01'))
                {
                   $start_date1 = strtotime(date('2022-01-d',$start_date1));
                }
                //exit;
                $datediff = strtotime($end_date) - $start_date1;
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
                $subs_val = round($sub_amt-$subs_coll,2);
                
                if($subs_val>0 && strtotime($today_date)>=strtotime($start_date))
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
        
        
        foreach($data as $client=>$record) 
        {
            //ledger updation  code starts from here
            $op =($record['ledger_access_usage']+$record['ledger_topup']+$record['ledger_sub']+$record['ledger_sub']);            
            $led_op = $record['billed']+$record['subbilled']+$record['firstbilled']+$record['setupbilled'];
            #$total_collected = $record['coll']+$record['subs_coll']+$record['first_plan_coll']+$record['setupcoll']; 
            $cl=round($op+$led_op-$record['coll']-$record['subs_coll']-$record['first_plan_coll']-$record['setupcoll'],2);
            //echo "{$record['ledger_sub']}+{$record['subbilled']}-{$record['subs_coll']}-{$record['ledger_subcoll']}";exit;
            $ledger_sub = $record['ledger_sub_e']+$record['subbilled']-$record['subs_coll']-round($record['ledger_subcoll']*1.18);
            $ledger_topup = $record['ledger_topup']+$record['billed']-$record['coll'];
            $ledger_setup = $record['ledger_setup']+$record['setupbilled']-$record['setupcoll']-$record['ledger_setupcoll'];
            //echo "{$record['ledger_setup']}+{$record['setupbilled']}-{$record['setupcoll']}-{$record['ledger_setupcoll']}"; exit;
            
            //$ledger_first_billed = $record['ledger_firstbilled']+$record['firstbilled']-$record['first_plan_coll'];
            
            
            //talktime codes from here
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
            if(!empty($record['subbilled']))
            {
                $fr_val_subs= round((($record['subbilled'])/118)*$plan_pers,2);
            }
            else
            {
                $fr_val_subs= round((($record['subbilled_as_sub_date'])/118)*$plan_pers,2);
            }
            
            
            //echo "round((({$record['subbilled_as_sub_date']})/118)*$plan_pers,2);"; exit;
            $fr_val_talk= round(($record['billed']*100/118),2);
            $fr_val = $fr_val_subs+$fr_val_talk;

            $first_bill = $record['first_plan_value'];
            $first_bill_with_gst = round($record['first_plan_value_with_gst'],2);
            $plan_sub_cost = round($record['new_plan_sub_cost'],2);
            $plan_setup_cost = round($record['new_plan_setup_cost'],2);
            $plan_dev_cost = round($record['new_plan_dev_cost'],2);
            
            $csbal =$record['cs_bal'];
            $bal = round($open_bal+$fr_val-$record['cs_bal'],2);
            //echo "round($open_bal+$fr_val-{$record['cs_bal']},2)";exit;
            $clientId = $record['clientId'];
            $year1 = date('Y');
            $month1 = date('M');
            echo $qry = "insert into billing_ledger set subs='$ledger_sub',topup='$ledger_topup',setup_cost='$ledger_setup',firstbilled='$ledger_first_billed',talk_time='$bal', fin_year='$year1' , fin_month='$month1' , clientId='$clientId'";
            echo '&nbsp;&nbsp;&nbsp;';
            echo $client;
            echo '<br/>'; 
            //exit;
            $rsc_insert=$this->RegistrationMaster->query($qry);
        }
        
        exit;
    
    
    //print_r($data);exit;
    
    
    
    $this->set('data',$data);
    $this->set('month',$selectedMonth);
}



}
?>