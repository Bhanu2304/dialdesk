<?php
class ClientRevenueReportsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','BillMaster','PlanMaster','BalanceMaster',
            'BillMasterPost','BalanceMasterHistory','vicidialCloserLog','InitialInvoice');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
	$this->Auth->allow('ledger_statement','index','ledger_statement2','client_revewal_report','client_revewal_report_bhanu','export_client_revewal_report');
	if(!$this->Session->check("admin_id"))
        {
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
	}
        $this->BillMaster->useDbConfig = 'dbNorth';
        $this->InitialInvoice->useDbConfig = 'dbNorth';
        
        
    }

    public function index()
    {
        $this->layout = "user";
        $clientIds = array_values($this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('not'=>array('PlanId'=>null),'PlanType'=>'Prepaid'))));
        $this->set('data',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('is_dd_client'=>'1','status'=>array('A','H',''),'company_id'=>$clientIds),'order'=>array('Company_name'=>'asc'))));
        $finance_year = $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>"id>18"));
        $this->set('finance_year', $finance_year);
        $month_arr = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        $this->set('month_arr', $month_arr);
        $current_month = date('M');
        $this->set('current_month', $current_month);
        $finYear = '';
        if(in_array($current_month,array('Jan','Feb','Mar')))
        {
            $finYear = (date('Y')-1).'-'.(date('y'));
            #$finYear = '2022-23';

        }
        else
        {
            $finYear = date('Y').'-'.(date('y')+1);
            #$finYear = '2022-23';
        }
        $this->set('current_year', $finYear);
        //echo $finYear;exit;
        #print_r($finance_year);exit;
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
    
    
    public function client_revewal_report_bhanu()
    {
        $this->layout = "user";
        $today_date = date('Y-m-d');
        $sub_month = date('Y-m-d',strtotime(" +1"." month")); 
        $sub_month_str = date('M-Y',strtotime($sub_month));
        $to_date = date("Y-m-t", strtotime($sub_month)); 
        $this->set('sub_month_str',$sub_month_str);
        $client_qry = "SELECT * FROM registration_master rm WHERE  `status` = 'A' and is_dd_client='1'   ORDER BY company_name";
        $client_det_arr = $this->RegistrationMaster->query($client_qry);
        foreach($client_det_arr as $clr)
        {
            $clientId = $clr['rm']['company_id'];
            $clientName = $clr['rm']['company_name'];
            $sel_balance_qry = "SELECT * FROM `balance_master` bm WHERE clientId='$clientId' limit 1";
            $bal_det=$this->RegistrationMaster->query($sel_balance_qry);
            $start_date = $bal_det['0']['bm']['start_date'];
            if(!empty($start_date))
            {
                $planId = $bal_det['0']['bm']['PlanId'];
            $plan_det_qry = "SELECT RentalAmount,PeriodType,Balance,CreditValue,SetupCost,DevelopmentCost FROM `plan_master` pm WHERE id='$planId' limit 1"; 
            $plan_det = $this->RegistrationMaster->query($plan_det_qry);
            $RentalAmount = $plan_det['0']['pm']['RentalAmount'];
            $PeriodType = $plan_det['0']['pm']['PeriodType'];
            $balance_credit = $plan_det['0']['pm']['Balance'];
            $total_subs = 0;
            
            $cost_cat_arr = $this->InitialInvoice->query("select * from cost_master cm where dialdesk_client_id='$clientId' limit 1");
            foreach($cost_cat_arr as $cost)
            {
                $cost_center = $cost['cm']['cost_center'];
                $sel_billed_fromdateqry = "select ti.* from tbl_invoice ti where  ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (ti.invoiceDate >= '2022-04-01' AND ti.invoiceDate <= '$to_date')  ";
                $billed_fromdateqry_arr = $this->InitialInvoice->query($sel_billed_fromdateqry);
                foreach($billed_fromdateqry_arr as $inv_det)
                {
                    $initial_id = $inv_det['ti']['id'];
                    if(strtolower($inv_det['ti']['category'])==strtolower('first_bill'))
                    {
                            //check whether first bill have subscritpion amount
                        $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                        $rsc_subs_arr = $this->InitialInvoice->query($select_subs);

                        foreach($rsc_subs_arr as $sb)
                        {
                            $total_subs +=1;
                        }
                    }
                    else if(strtolower($inv_det['ti']['category'])==strtolower('subscription'))
                    {
                           $total_subs +=1; 
                    }
                }
            }
                $cur_date = $to_date;
                $start_date_activation = $start_date; 
                if(strtotime($start_date_activation)<strtotime('2022-04-01'))
                {
                   // $start_date_activation = '2022-04-01';
                }
                //echo "$cur_date-$start_date_activation";exit;
                //echo $PeriodType;exit;
                $datediff2 = strtotime($cur_date) - strtotime($start_date_activation);
                $str_to_days = 60 * 60 * 24;
                $datediff2 = $datediff2/$str_to_days; 
                $total_sub_req = 0;
                $NewRentalAmount = 0;
                $no_of_days = 1;
                if(strtolower($PeriodType)==strtolower('MONTH'))
                {
                    $subs_penP = ceil($noofday/30);
                    $NewRentalAmount = $RentalAmount/12;
                    $no_of_month = 1;
                    $no_of_days = 1;
                    $total_sub_req = ($datediff2/30.42);
                }
                else if(strtolower($PeriodType)==strtolower('Quater'))
                {
                    $subs_penP = ceil($noofday/90);
                    $NewRentalAmount = $RentalAmount/4;
                    $no_of_month = 3;
                    $no_of_days =3;
                    $total_sub_req = ($datediff2/91.25);
                }
                else if(strtolower($PeriodType)==strtolower('Half'))
                {
                    $subs_penP = ceil($noofday/180);
                    $NewRentalAmount = $RentalAmount/2;
                    $no_of_month = 6;
                    $no_of_days =6;
                    $total_sub_req = ($datediff2/182.5);
                }
                else if(strtolower($PeriodType)==strtolower('YEAR'))
                {
                    $subs_penP = round($noofday/365);
                    $NewRentalAmount = $RentalAmount/1;
                    $no_of_month = 12;
                    $no_of_days = 12;
                    $total_sub_req = ($datediff2/365);
                }
                //echo "$total_subs-$total_sub_req";exit;
                //echo $total_sub_req;exit;
                $total_subs_pending =$total_subs-$total_sub_req;
                $days_added = round(floor($total_sub_req)*($no_of_days));
                $renwal_date = date('Y-m-d',strtotime($start_date_activation." +"."$days_added month")); 
                //echo $days_added;exit;
                //echo $start_date_activation;exit;
                
                $from_date = date('Y-m-01',strtotime($to_date));
                //echo $total_subs_pending;exit;
                $total_subs_pending = 0;
                while(strtotime($start_date_activation)<strtotime($to_date))
                {
                     if(strtotime($start_date_activation)>=strtotime($from_date) && strtotime($start_date_activation)<=strtotime($to_date))
                     {
                         $total_subs_pending = -1;
                         break;
                     }
                    $renwal_date = $start_date_activation = date('Y-m-d',strtotime($start_date_activation." +"."$no_of_days month"));
                   // echo $renwal_date.'<br/>';
                }
                
                //exit;
                
                
                if($total_subs_pending<0)
                {
                    $renwal_date_str = date('d-M-Y',strtotime($renwal_date));
                    $data[$clientName]['subs_done'] = $total_subs;
                    $data[$clientName]['subs_amount'] = $NewRentalAmount;
                    $data[$clientName]['PeriodType'] = $PeriodType;
                    $data[$clientName]['credit_value'] = $this->get_credit_from_subs_value($RentalAmount,$balance_credit,$NewRentalAmount);
                    $data[$clientName]['renew_date'] = $renwal_date_str;
                }
            }
            
        }
        
        $this->set('client_arr',$data);
    }

    public function client_revewal_report()
    {
        $this->layout = "user";
        if($this->request->is("POST"))
        {
            $search     =   $this->request->data['ClientRevenueReports'];
            $from = date('Y-m-d',strtotime($search['startdate']));
            $enddate = $search['enddate'];
            // $today_date = date('Y-m-d');
            $sub_month = date('Y-m-d',strtotime(" +0"." month")); 
            $sub_month_str = date('M-Y',strtotime($sub_month));
            $to_date = date("Y-m-d", strtotime($enddate)); 
            $this->set('sub_month_str',$sub_month_str);
            $client_qry = "SELECT * FROM registration_master rm WHERE  `status` NOT IN('CL','D') and is_dd_client='1'   ORDER BY company_name";
            $client_det_arr = $this->RegistrationMaster->query($client_qry);
            foreach($client_det_arr as $clr)
            {
                $clientId = $clr['rm']['company_id'];
                $clientName = $clr['rm']['company_name'];
                $clientstatus = $clr['rm']['status'];
                $sel_balance_qry = "SELECT * FROM `balance_master` bm WHERE clientId='$clientId' limit 1";
                $bal_det=$this->RegistrationMaster->query($sel_balance_qry);
                $start_date = $bal_det['0']['bm']['start_date'];
                if(!empty($start_date))
                {
                    $planId = $bal_det['0']['bm']['PlanId'];
                $plan_det_qry = "SELECT RentalAmount,PeriodType,Balance,CreditValue,SetupCost,DevelopmentCost FROM `plan_master` pm WHERE id='$planId' limit 1"; 
                $plan_det = $this->RegistrationMaster->query($plan_det_qry);
                $RentalAmount = $plan_det['0']['pm']['RentalAmount'];
                $PeriodType = $plan_det['0']['pm']['PeriodType'];
                $balance_credit = $plan_det['0']['pm']['Balance'];
                $total_subs = 0;
                
                $cost_cat_arr = $this->InitialInvoice->query("select * from cost_master cm where dialdesk_client_id='$clientId' limit 1");
                foreach($cost_cat_arr as $cost)
                {
                    $cost_center = $cost['cm']['cost_center'];
                    $bill_company = $cost['cm']['company_name'];
                    
                    $sel_billed_fromdateqry = "select ti.* from tbl_invoice ti where  ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (ti.invoiceDate >= '$from' AND ti.invoiceDate <= '$to_date')  ";
                    $billed_fromdateqry_arr = $this->InitialInvoice->query($sel_billed_fromdateqry);

                    foreach($billed_fromdateqry_arr as $inv_det)
                    {
                        $initial_id = $inv_det['ti']['id'];
                        $invoice_due_date = $inv_det['ti']['due_date'];
                        $bill_no = $inv_det['ti']['bill_no'];

                        $select_payment_qry = "SELECT sum(bpp.bill_passed) bill_passed FROM 
                            bill_pay_particulars bpp inner join tbl_invoice ti on bpp.bill_no = SUBSTRING_INDEX('$bill_no','/',1)
                            AND bpp.financial_year=ti.finance_year and bpp.branch_name=ti.branch_name AND bpp.company_name='$bill_company' 
                            where ti.cost_center='$cost_center' and date(bpp.pay_dates)  between '$from' and '$to_date'";

                        $collection_billingtodate_arr = $this->BillMaster->query($select_payment_qry); 

                        $collected = $collection_billingtodate_arr[0][0]['bill_passed'];


                        if(strtolower($inv_det['ti']['category'])==strtolower('first_bill'))
                        {
                                //check whether first bill have subscritpion amount
                            $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                            $rsc_subs_arr = $this->InitialInvoice->query($select_subs);

                            foreach($rsc_subs_arr as $sb)
                            {
                                $total_subs +=1;
                            }
                        }
                        else if(strtolower($inv_det['ti']['category'])==strtolower('subscription'))
                        {
                            $total_subs +=1; 
                        }
                    }
                }
                    $cur_date = $to_date;
                    //$start_date_activation = date("Y-m-d", strtotime($startdate));;
                    $start_date_activation = $start_date; 
                    if(strtotime($start_date_activation)<strtotime($from))
                    {
                    // $start_date_activation = '2022-04-01';
                    }
                    //echo "$cur_date-$start_date_activation";exit;
                    //echo $PeriodType;exit;
                    $datediff2 = strtotime($cur_date) - strtotime($start_date_activation);
                    $str_to_days = 60 * 60 * 24;
                    $datediff2 = $datediff2/$str_to_days; 
                    $total_sub_req = 0;
                    $NewRentalAmount = 0;
                    $no_of_days = 1;
                    if(strtolower($PeriodType)==strtolower('MONTH'))
                    {
                        $subs_penP = ceil($noofday/30);
                        $NewRentalAmount = $RentalAmount/12;
                        $no_of_month = 1;
                        $no_of_days = 1;
                        $total_sub_req = ($datediff2/30.42);
                    }
                    else if(strtolower($PeriodType)==strtolower('Quater'))
                    {
                        $subs_penP = ceil($noofday/90);
                        $NewRentalAmount = $RentalAmount/4;
                        $no_of_month = 3;
                        $no_of_days =3;
                        $total_sub_req = ($datediff2/91.25);
                    }
                    else if(strtolower($PeriodType)==strtolower('Half'))
                    {
                        $subs_penP = ceil($noofday/180);
                        $NewRentalAmount = $RentalAmount/2;
                        $no_of_month = 6;
                        $no_of_days =6;
                        $total_sub_req = ($datediff2/182.5);
                    }
                    else if(strtolower($PeriodType)==strtolower('YEAR'))
                    {
                        $subs_penP = round($noofday/365);
                        $NewRentalAmount = $RentalAmount/1;
                        $no_of_month = 12;
                        $no_of_days = 12;
                        $total_sub_req = ($datediff2/365);
                    }
                    //echo "$total_subs-$total_sub_req";exit;
                    //echo $total_sub_req;exit;
                    $total_subs_pending =$total_subs-$total_sub_req;
                    $days_added = round(floor($total_sub_req)*($no_of_days));
                    $renwal_date = date('Y-m-d',strtotime($start_date_activation." +"."$days_added month")); 
                    //echo $days_added;exit;
                    //echo $start_date_activation;exit;
                    
                    $from_date = date('Y-m-01',strtotime($to_date));
                    //echo $total_subs_pending;exit;
                    $total_subs_pending = 0;
                    while(strtotime($start_date_activation)<strtotime($to_date))
                    {
                        if(strtotime($start_date_activation)>=strtotime($from) && strtotime($start_date_activation)<=strtotime($to_date))
                        {
                            $total_subs_pending = -1;
                            break;
                        }
                        $renwal_date = $start_date_activation = date('Y-m-d',strtotime($start_date_activation." +"."$no_of_days month"));
                    // echo $renwal_date.'<br/>';
                    }
                    
                    //exit;
                    
                    
                    if($total_subs_pending<0)
                    {
                        $renwal_date_str = date('d-m-Y',strtotime($renwal_date));
                        $new_invoice_date = date('d-m-Y',strtotime($renwal_date." +31 Days")); 
                        // if(!empty($invoice_due_date))
                        // {
                        //     $invoice_due_date = date('d-M-Y',strtotime($invoice_due_date));
                        // }
                            
                        $data[$clientName]['subs_done'] = $total_subs;
                        $data[$clientName]['subs_amount'] = $NewRentalAmount;
                        $data[$clientName]['PeriodType'] = $PeriodType;
                        $data[$clientName]['credit_value'] = $this->get_credit_from_subs_value($RentalAmount,$balance_credit,$NewRentalAmount);
                        $data[$clientName]['renew_date'] = $renwal_date_str;
                        //$data[$clientName]['invoice_due_date'] = $invoice_due_date;
                        $data[$clientName]['invoice_due_date'] = $new_invoice_date;
                        $data[$clientName]['client_status'] = $clientstatus;
                        $data[$clientName]['collection_status'] = $collected;
                    }
                }
                
            }
        
        $this->set('client_arr',$data);
        }
    }

    public function export_client_revewal_report()
    {
        $this->layout = "user";
        if($this->request->is("POST"))
        {
            $search     =   $this->request->data['ClientRevenueReports'];
            $from = date('Y-m-d',strtotime($search['startdate']));
            $enddate = $search['enddate'];
            
            $today_date = date('Y-m-d');
            $sub_month = date('Y-m-d',strtotime(" +0"." month")); 
            $sub_month_str = date('M-Y',strtotime($sub_month));
            //$to_date = date("Y-m-t", strtotime($sub_month)); 
            $to_date = date("Y-m-d", strtotime($enddate)); 
            $this->set('sub_month_str',$sub_month_str);
            //$client_qry = "SELECT * FROM registration_master rm WHERE  `status` != 'CL' and is_dd_client='1'   ORDER BY company_name";
            $client_qry = "SELECT * FROM registration_master rm WHERE  `status` NOT IN('CL','D') and is_dd_client='1'   ORDER BY company_name";
            $client_det_arr = $this->RegistrationMaster->query($client_qry);
            foreach($client_det_arr as $clr)
            {
                $clientId = $clr['rm']['company_id'];
                $clientName = $clr['rm']['company_name'];
                $clientstatus = $clr['rm']['status'];
                $sel_balance_qry = "SELECT * FROM `balance_master` bm WHERE clientId='$clientId' limit 1";
                $bal_det=$this->RegistrationMaster->query($sel_balance_qry);
                $start_date = $bal_det['0']['bm']['start_date'];
                if(!empty($start_date))
                {
                    $planId = $bal_det['0']['bm']['PlanId'];
                $plan_det_qry = "SELECT RentalAmount,PeriodType,Balance,CreditValue,SetupCost,DevelopmentCost FROM `plan_master` pm WHERE id='$planId' limit 1"; 
                $plan_det = $this->RegistrationMaster->query($plan_det_qry);
                $RentalAmount = $plan_det['0']['pm']['RentalAmount'];
                $PeriodType = $plan_det['0']['pm']['PeriodType'];
                $balance_credit = $plan_det['0']['pm']['Balance'];
                $total_subs = 0;
                
                $cost_cat_arr = $this->InitialInvoice->query("select * from cost_master cm where dialdesk_client_id='$clientId' limit 1");
                foreach($cost_cat_arr as $cost)
                {
                    $cost_center = $cost['cm']['cost_center'];
                    $bill_company = $cost['cm']['company_name'];
                    $sel_billed_fromdateqry = "select ti.* from tbl_invoice ti where  ti.bill_no!='' and ti.status='0' and cost_center='$cost_center' and (ti.invoiceDate >= '$from' AND ti.invoiceDate <= '$to_date')  ";
                    $billed_fromdateqry_arr = $this->InitialInvoice->query($sel_billed_fromdateqry);


                    foreach($billed_fromdateqry_arr as $inv_det)
                    {
                        $initial_id = $inv_det['ti']['id'];
                        $invoice_due_date = $inv_det['ti']['due_date'];
                        $bill_no = $inv_det['ti']['bill_no'];

                        $select_payment_qry = "SELECT sum(bpp.bill_passed) bill_passed FROM 
                            bill_pay_particulars bpp inner join tbl_invoice ti on bpp.bill_no = SUBSTRING_INDEX('$bill_no','/',1)
                            AND bpp.financial_year=ti.finance_year and bpp.branch_name=ti.branch_name AND bpp.company_name='$bill_company' 
                            where ti.cost_center='$cost_center' and date(bpp.pay_dates)  between '$from' and '$to_date'";

                        $collection_billingtodate_arr = $this->BillMaster->query($select_payment_qry); 

                        $collected = $collection_billingtodate_arr[0][0]['bill_passed'];
                        
                        if(strtolower($inv_det['ti']['category'])==strtolower('first_bill'))
                        {
                                //check whether first bill have subscritpion amount
                            $select_subs = "select ip.amount from inv_particulars ip where initial_id='$initial_id' and sub_category='Subscription'";
                            $rsc_subs_arr = $this->InitialInvoice->query($select_subs);

                            foreach($rsc_subs_arr as $sb)
                            {
                                $total_subs +=1;
                            }
                        }
                        else if(strtolower($inv_det['ti']['category'])==strtolower('subscription'))
                        {
                            $total_subs +=1; 
                        }
                    }
                }
                    $cur_date = $to_date;
                    $start_date_activation = $start_date; 
                    if(strtotime($start_date_activation)<strtotime($from))
                    {
                    // $start_date_activation = '2022-04-01';
                    }
                    //echo "$cur_date-$start_date_activation";exit;
                    //echo $PeriodType;exit;
                    $datediff2 = strtotime($cur_date) - strtotime($start_date_activation);
                    $str_to_days = 60 * 60 * 24;
                    $datediff2 = $datediff2/$str_to_days; 
                    $total_sub_req = 0;
                    $NewRentalAmount = 0;
                    $no_of_days = 1;
                    if(strtolower($PeriodType)==strtolower('MONTH'))
                    {
                        $subs_penP = ceil($noofday/30);
                        $NewRentalAmount = $RentalAmount/12;
                        $no_of_month = 1;
                        $no_of_days = 1;
                        $total_sub_req = ($datediff2/30.42);
                    }
                    else if(strtolower($PeriodType)==strtolower('Quater'))
                    {
                        $subs_penP = ceil($noofday/90);
                        $NewRentalAmount = $RentalAmount/4;
                        $no_of_month = 3;
                        $no_of_days =3;
                        $total_sub_req = ($datediff2/91.25);
                    }
                    else if(strtolower($PeriodType)==strtolower('Half'))
                    {
                        $subs_penP = ceil($noofday/180);
                        $NewRentalAmount = $RentalAmount/2;
                        $no_of_month = 6;
                        $no_of_days =6;
                        $total_sub_req = ($datediff2/182.5);
                    }
                    else if(strtolower($PeriodType)==strtolower('YEAR'))
                    {
                        $subs_penP = round($noofday/365);
                        $NewRentalAmount = $RentalAmount/1;
                        $no_of_month = 12;
                        $no_of_days = 12;
                        $total_sub_req = ($datediff2/365);
                    }
                    //echo "$total_subs-$total_sub_req";exit;
                    //echo $total_sub_req;exit;
                    $total_subs_pending =$total_subs-$total_sub_req;
                    $days_added = round(floor($total_sub_req)*($no_of_days));
                    $renwal_date = date('Y-m-d',strtotime($start_date_activation." +"."$days_added month")); 
                    //echo $days_added;exit;
                    //echo $start_date_activation;exit;
                    
                    $from_date = date('Y-m-01',strtotime($to_date));
                    //echo $total_subs_pending;exit;
                    $total_subs_pending = 0;
                    while(strtotime($start_date_activation)<strtotime($to_date))
                    {
                        if(strtotime($start_date_activation)>=strtotime($from) && strtotime($start_date_activation)<=strtotime($to_date))
                        {
                            $total_subs_pending = -1;
                            break;
                        }
                        $renwal_date = $start_date_activation = date('Y-m-d',strtotime($start_date_activation." +"."$no_of_days month"));
                    // echo $renwal_date.'<br/>';
                    }
                    
                    //exit;
                    
                    
                    if($total_subs_pending<0)
                    {
                        $renwal_date_str = date('d-m-Y',strtotime($renwal_date));
                        $new_invoice_date = date('d-m-Y',strtotime($renwal_date." +31 Days"));
                        // if(!empty($invoice_due_date))
                        // {
                        //     $invoice_due_date = date('d-M-Y',strtotime($invoice_due_date));
                        // }
                            
                        $data[$clientName]['subs_done'] = $total_subs;
                        $data[$clientName]['subs_amount'] = $NewRentalAmount;
                        $data[$clientName]['PeriodType'] = $PeriodType;
                        $data[$clientName]['credit_value'] = $this->get_credit_from_subs_value($RentalAmount,$balance_credit,$NewRentalAmount);
                        $data[$clientName]['renew_date'] = $renwal_date_str;
                        //$data[$clientName]['invoice_due_date'] = $invoice_due_date;
                        $data[$clientName]['invoice_due_date'] = $new_invoice_date;
                        $data[$clientName]['client_status'] = $clientstatus;
                        $data[$clientName]['collection_status'] = $collected;
                    }
                }
                
            }
        
        //$this->set('client_arr',$data);
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=client_renewal_report.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ?>
            <table cellpadding="0" cellspacing="0" border="2"  class="table">
                        <tr>
                            <th>Sr. No.</th>
                            <th>Client Name</th>
                            <th>Subs Amount</th>
                            <th>Credit Value</th>
                            <th>Payment Mode</th>
                            <th>Renewal Date</th> 
                            <th>Invoice Due Date</th>  
                            <th>Client Status</th> 
                            <th>Collection Status</th> 
                        </tr>
                        <?php $i=1;  $total_subscription = 0;
                        $total_credit = 0;
                        $total_collection = 0;
                        foreach($data as $client=>$det)
                        {
                         ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $client; ?></td>
                                <td><?php echo $det['subs_amount']; ?></td>
                                <td><?php echo $det['credit_value']; ?></td>
                                <td><?php echo $det['PeriodType']=='Quater'?'Quarter':$det['PeriodType']; ?></td>
                                <td><?php echo $det['renew_date']; ?></td>
                                <td><?php echo $det['invoice_due_date']; ?></td>
                                <td><?php if($det['client_status'] == 'A'){echo 'Active';}else if($det['client_status'] == 'H'){echo 'Hold';}else{echo 'Deactivate';} ?></td>
                                <td><?php echo number_format($det['collection_status'],2); ?></td>
                            </tr>
                        <?php $total_subscription+=$det['subs_amount'];
                            $total_credit+=$det['credit_value'];
                            $total_collection+=$det['collection_status'];
                        } ?>
                        <tr>
                            <th></th>
                            <th>Total</th>
                            <th><?php echo number_format($total_subscription,2); ?></th>
                            <th><?php echo number_format($total_credit,2); ?></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><?php echo number_format($total_collection,2); ?></th>
                        </tr>
                    </table> 

            <?php die;
        }
    }
    
    
    public function revenue()
    {
        $this->layout = "user";
        $clientIds = array_values($this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('not'=>array('PlanId'=>null),'PlanType'=>'Prepaid'))));
        $this->set('data',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('is_dd_client'=>'1','status'=>'A','company_id'=>$clientIds),'order'=>array('Company_name'=>'asc'))));
    }
    
    public function ledger_statement()
    {
        $this->layout = "user";
        $clientIds = array_values($this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('not'=>array('PlanId'=>null),'PlanType'=>'Prepaid'))));
        $this->set('data',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),
            'conditions'=>array('is_dd_client'=>'1',
                'status'=>array('A','H',''),
                'company_id'=>$clientIds),
            'order'=>array('Company_name'=>'asc'))));
    }
    
    public function ledger_statement2()
    {
        $this->layout = "user";
        $clientIds = array_values($this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('not'=>array('PlanId'=>null),'PlanType'=>'Prepaid'))));
        $this->set('data',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),
            'conditions'=>array('is_dd_client'=>'1',
                'status'=>array('A','H',''),
                'company_id'=>$clientIds),
            'order'=>array('Company_name'=>'asc'))));
    }
    
}
?>