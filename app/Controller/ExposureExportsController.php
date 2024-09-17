    <?php
class ExposureExportsController extends AppController 
{
    public $uses=array('RegistrationMaster','InitialInvoice','BillMaster','InitialInvoiceTmp','Addclient','Addbranch','Addcompany','CostCenterMaster',
        'AddInvParticular','Particular','AddInvDeductParticular','DeductParticular','Access','User','EditAmount',
        'Provision','PONumber','NotificationMaster','ProvisionPart','ProvisionPartDed');
    public $components = array('RequestHandler');
    public $helpers = array('Js');
		

public function beforeFilter()
{
    parent::beforeFilter();
    $this->Auth->allow('index_ledger','export_exposure_views');
    
    
    
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

public function export_exposure_views() 
{
    

    $data = array();
    $from_date = "";
    $to_date = "";
    $company_qry = "";
    $client_status_qry = "";

    //print_r($this->request->params);exit;
    if(!empty($this->params->query['from_date']))
    {
        $from_date1 = $this->params->query['from_date'];
        $to_date1 = $this->params->query['to_date'];                 
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
    
    
    $company_id = $this->params->query['client']; 
    $client_status = $this->params->query['client_status'];
    $this->set('company_id',$company_id);
    $this->set('client_status',$client_status);
    if(!empty($this->params->query['client']) && $this->params->query['client']!='All')
    {               
        $company_qry = " and company_id='$company_id'";        
    }
    if(!empty($this->params->query['client_status']) && $this->params->query['client_status']!='All')
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
                    $data[$clientName]['SetupCost'] = $SetupCost;
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
                            $op2_ledger -= $cost_adv_arr['0']['0']['bill_passed'];
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
                    $coll2_ledger += $cost_adv_arr[0]['0']['bill_passed'];
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
    $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","company_name"),'conditions'=>"`status` != 'CL'",'order'=>array('Company_name'=>'asc')));
    $client = array('All'=>'All')+$client;
    $this->set('client_det',$client);
}


public function index_ledger() 
{
    //echo "site is under maintanance. will be live after 24 hours.";
    //exit;
    header("Content-Type: application/vnd.ms-excel; name='excel'");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=exposures_".date("Ymdhis").".xls");
	header("Pragma: no-cache");
	header("Expires: 0"); 
    
    
    $this->layout='ajax';
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
        
        //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE company_id in ('278','301','389') and  `status` = 'A' order by company_name");
        //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE company_id='278' and  `status` = 'A' "); 
        $client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE  is_dd_client='1' and `status` = 'A' order by company_name"); 
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
        
   ?> 
        
    <table border="1" style="width:100%;font-size: 10px;">
                    <tr>
                        <th rowspan="2">S.No.</th>
                        <th class="left_bord"></th>
                        
                        <th colspan="4" class="left_bord" style="text-align: center;background: #EEE9E9;">Ledger</th>
                        <th colspan="4" class="left_bord" style="text-align: center;background: #FAEBEB;">Credit Point Consumption</th>
                        <th colspan="3" class="left_bord" style="text-align: center;">Proposed Action</th>
                    </tr>
                    <tr>
                        <th class="left_bord">Client</th>
                        <th align="center" style="background: #EEE9E9;">Opening</th>
                        <th align="right" style="background: #EEE9E9;">Billed</th>
                        <th align="right" style="background: #EEE9E9;">Collected</th>
                        <th align="right" style="background: #EEE9E9;" class="left_bord">Closing</th>
                        <th align="right" style="background: #FAEBEB;">Opening</th>
                        <th align="right" style="background: #FAEBEB;">Fresh released</th>
                        <th align="right" style="background: #FAEBEB;">Consumed</th>
                        <th align="right" style="background: #FAEBEB;" class="left_bord">Balance</th>
                        <th align="right" style="background: #CDB7B5;">Exposure</th>
                        <th align="right" style="background: #CDB7B5;">To be billed</th>
                        
                        <th align="center" style="background: #CDB7B5;display: none;" class="left_bord">Export Date</th>
                    </tr>
                    <?php $i =1;
               
                    foreach($data as $client=>$record) 
                    { //print_r($record);exit;
                        //echo "";
                        ?>

                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td class="left_bord"><?php echo substr($client,0,30); ?></td>
                                <td align="right" style="background: #EEE9E9;"><?php  $op =($record['ledger_access_usage']+$record['ledger_topup']+$record['ledger_sub']+$record['ledger_setup']);  echo number_format($op,2); $Opening_ledger=$Opening_ledger+$op; ?></td>
                                <td align="right" style="background: #EEE9E9;"><?php  $led_op = $record['billed']+$record['subbilled']+$record['firstbilled']+$record['setupbilled'];  echo number_format($led_op,2); $Billed=$Billed+$led_op; ?></td>
                                <td align="right" style="background: #EEE9E9;"><?php  $total_collected = $record['coll']+$record['subs_coll']+$record['first_plan_coll']+$record['setupcoll']; echo number_format($total_collected,2);  $Collected=$Collected+$total_collected; ?></td>
                                <td align="right" style="background: #EEE9E9;" class="left_bord"><?php   $cl=round($op+$led_op-$record['coll']-$record['subs_coll']-$record['first_plan_coll']-$record['setupcoll'],2);  $clsum= $clsum+$cl;
                                echo number_format($cl,2); 
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
                                $fr_val_subs= round((($record['subbilled'])/118)*$plan_pers,2);
                                $fr_val_talk= round(($record['billed']*100/118),2);
                                $fr_val = $fr_val_subs+$fr_val_talk;
                                
                                $first_bill = $record['first_plan_value'];
                                $first_bill_with_gst = round($record['first_plan_value_with_gst'],2);
                                $plan_sub_cost = round($record['new_plan_sub_cost'],2);
                                $plan_setup_cost = round($record['new_plan_setup_cost'],2);
                                $plan_dev_cost = round($record['new_plan_dev_cost'],2);
                                
                                $url = "bill_type=first_bill&Subscription={$plan_sub_cost}&SetupCost={$plan_setup_cost}&DevelopmentCost={$plan_dev_cost}&cost_center={$record['cost_center']}&amt=".abs($first_bill_with_gst)."&sub_start_date={$record['sub_start_date']}&sub_end_date={$record['sub_end_date']}&due_date={$record['due_date']}";
                                ?></td>
                                <td align="right" style="background: #FAEBEB;"><?php  echo $open_bal; $AB=$AB+$open_bal; ?></td>
                                <td align="right" style="background: #FAEBEB;"><?php  echo  number_format($fr_val,2); $ABD=$ABD+$fr_val; ?></td>
                                <td align="right" style="background: #FAEBEB;"><?php $csbal =$record['cs_bal']; $XYZ=$XYZ+$csbal;  echo number_format($csbal,2); ?></td>
                                <td align="right" style="background: #FAEBEB;" class="left_bord"><?php $bal = round($open_bal+$fr_val-$record['cs_bal'],2); 
                                $CDX=$CDX+$bal; echo number_format($bal,2); ?></td>
                                <td align="right" style="background: #EEE9E9;"><?php //echo $record['subbilled'];exit;
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

                                $TOTAL_EXP =$TOTAL_EXP+$exposure;

                                echo number_format($exp+$cl+$tobebilled+$tobebilledfirst,2);
                                //printing exposure value ends here
                                echo '<br/>';
                                //echo $record['adv_val'];exit;
                                if($exp==0)
                                {
                                    $tobebilled2=0;
                                }
                                else
                                {
                                    $tobebilled2=round($exp-$record['billed'],2);
                                    $tobebilled2_without_tax = round(($tobebilled2/118)*100,2);
                                }
                                
                                $TO_BE_BILLED = $tobebilled2+$tobebilled+$tobebilledfirst;

                                $TOTAL_TO_BE_BILLED = $TOTAL_TO_BE_BILLED +$TO_BE_BILLED;
                                
                                ?>
                                </td> 
                                <td align="right" style="background: #EEE9E9;"><?php  echo number_format($tobebilled2+$tobebilled+$tobebilledfirst,2); ?></td>
                                
                                
                                <td><?php echo date('d-M-Y'); ?></td>
                                
                            </tr> 
                        
                    
                    <?php } 

                        

                ?>

                   
                    <tr>
                        <th colspan="2" style="text-align: center;">Total</th>
                        <th style="background: #EEE9E9;text-align: center;"><?= $Opening_ledger;?></th>
                        <th style="background: #EEE9E9;text-align: center;"><?= $Billed;?></th>
                        <th style="background: #EEE9E9;text-align: center;"><?= $Collected;?></th>
                        <th style="background: #EEE9E9;text-align: center;" class="left_bord"><?= $clsum;?></th>
                      
                      
                        <th style="background: #FAEBEB;text-align: center;"><?= $AB;?></th>
                        <th style="background: #FAEBEB;text-align: center;"><?= number_format($ABD,2);?></th>

                        <th style="background: #FAEBEB;text-align: center;"><?= number_format($XYZ,2);?></th>

                        <th style="background: #FAEBEB;text-align: center;" class="left_bord"><?php echo number_format($CDX,2);?></th>
                      
                        <th style="background: #CDB7B5;text-align: center;"><?= number_format($TOTAL_EXP,2) ;?></th>
                        <th style="background: #CDB7B5;text-align: center;"><?= number_format($TOTAL_TO_BE_BILLED,2);?></th>
                        <th style="background: #CDB7B5;text-align: center;" class="left_bord"></th>
                        <!-- <th align="center" style="background: #CDB7B5;" class="left_bord">Paymnet</th> -->
                    </tr>
                    

                </table>    
    
    
<?php   




	
exit;
}





}
?>