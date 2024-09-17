    <?php
class InitialInvoicesController extends AppController 
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

            $this->Auth->allow('index_ledger','add_bill','add2','view_approved_bill','get_view_approved','index','add_part','add_part1','delete_particular2','view','add','billApproval','get_view','view_bill','view_approve_bill','genrate_bill','genrate_bill','edit_bill','update_bill','update_bill',
            'branch_view','branch_viewbill','edit_bill','update_bill','view_pdf1','view_pdf','download','view_admin','dashboard','check_po','view_grn','check_grn',
            'approve_ahmd','view_ahmd','view_invoice','edit_invoice','download_grn','approve_grn','edit_forgrn','approve_po','view_forpo','view_pdf','billApproval',
            'reject_invoice','update_invoice','update_po','update_grn','billApproval','view_pdfgrn','view_pdfgrn1','view_pdfgrn2','get_costcenter','get_gst_type',
                    'get_service_no','view_status_change_request','delete_invoice','get_provision_months');

            
            
            if(1){$this->Auth->allow('index','get_costcenter','get_gst_type','get_service_no');$this->Auth->allow('add','check_po_number');$this->Auth->allow('billApproval');$this->Auth->allow('branch_viewbill');}
            if(1){$this->Auth->allow('view');$this->Auth->allow('view_bill');$this->Auth->allow('genrate_bill');$this->Auth->allow('edit_bill');$this->Auth->allow('update_bill');}
            if(1){$this->Auth->allow('branch_view');$this->Auth->allow('branch_viewbill');$this->Auth->allow('edit_bill');$this->Auth->allow('update_bill');}
            if(1){$this->Auth->allow('download');$this->Auth->allow('view_pdf');$this->Auth->allow('view_pdf1');}
            if(1){$this->Auth->allow('download_proforma');$this->Auth->allow('view_proforma_pdf');$this->Auth->allow('view_proforma_letter_pdf');}
            if(1){$this->Auth->allow('edit_proforma','view_proforma','approve_proforma','move_approve_proforma');$this->Auth->allow('update_proforma','reject_proforma'); }
            if(1){$this->Auth->allow('download_proforma_branch');}
            if(1){$this->Auth->allow('view_admin');$this->Auth->allow('view_forpo');$this->Auth->allow('update_po');}
            if(1){$this->Auth->allow('dashboard');}
            if(1){$this->Auth->allow('check_po');$this->Auth->allow('approve_po');}
            if(1){$this->Auth->allow('view_grn');$this->Auth->allow('edit_forgrn');$this->Auth->allow('update_grn');}
            if(1){$this->Auth->allow('check_grn');$this->Auth->allow('approve_grn');}
            if(1){$this->Auth->allow('download_grn','view_pdf','view_pdf1'); $this->Auth->allow('view_pdfgrn');$this->Auth->allow('view_pdfgrn1','view_pdfgrn2');}
            if(1){$this->Auth->allow('approve_ahmd');}
            if(1){$this->Auth->allow('view_ahmd');}
            if(1){$this->Auth->allow('view_status_change_request'); }
            if(1){$this->Auth->allow('view_invoice');$this->Auth->allow('edit_invoice');$this->Auth->allow('update_invoice');
            
            if(1){$this->Auth->allow('delete_invoice'); }
            
            $this->Auth->allow("apply_service_tax","get_provision_months"); $this->Auth->allow("apply_tax_cal"); $this->Auth->allow("apply_krishi_tax"); $this->Auth->allow('reject_invoice');}
            
    }
    $this->Auth->allow("apply_service_tax","get_provision_months");
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

public function get_service_no()
{
    $this->layout = 'ajax';
    $result = $this->request->data;
    $result['active'] = 1;
    $cost = $this->CostCenterMaster->find('first',array('fields'=>array('company_name','branch'),'conditions'=>$result));
    $company_name = $cost['CostCenterMaster']['company_name'];
    $branch = $cost['CostCenterMaster']['branch'];

    $serve = $this->CostCenterMaster->query("SELECT branch,ServiceTaxNo FROM tbl_service_tax ser WHERE (company_name !='$company_name' or branch !='$branch') and ServiceTaxNo IS NOT NULL");
    foreach($serve as $ser)
    {
        $data[$ser['ser']['ServiceTaxNo']]=$ser['ser']['branch']."-".$ser['ser']['ServiceTaxNo'];
    }
    echo json_encode($data);
    exit;
}

public function get_gst_type()
{
    $this->layout = 'ajax';
    $cost = $this->request->data['cost_center'];
    $data = $this->CostCenterMaster->query("select id from cost_master where cost_center='$cost' and  GSTType !='' and GSTTYPE is not null");
    if($data)
    {
        echo "1";
    }
    else
    {
        echo "0";
    }
    exit;
}

public function get_costcenter()
{
    $this->layout = 'ajax';
    $result = $this->request->data;
    $result['active'] = 1; 
    $cost_master = array();
    $data=$this->CostCenterMaster->find('list',array('fields'=>array('cost_center','client'),'conditions'=>array($result),'order'=>array('client'=>'asc')));
    foreach($data as $cost=>$client)
    {
        $cost1 = explode('/',$cost);
        $cost2 = $cost1[count($cost1)-1];
        $cost_master[$cost] = $client.'-'.$cost2; 
    }
    asort($cost_master);
    echo json_encode($cost_master); exit;
}

public function check_po_number()
{
    $this->layout="ajax";
    $month = $this->request->data['month'];
    $po_nos = $this->request->data['po_number'];
    $grnd = $this->request->data['grnd'];
    $finance = $this->request->data['finance'];

    $monthArr = array('Jan','Feb','Mar');       //Month array for deciding Year end or Year start
    $split = explode('-',$finance);    //explode finance_year
    //print_r($split); die; 
    if(in_array($month, $monthArr)) 
    {
        if($split[0]==date('Y') || $split[1]==date('y'))
        {
            $month .= '-'.date('y');    //Year from month
        }
        else
        {
            $month .= '-'.$split[1];    //Year from month
        }
        
    }
    else
    {
        $month .= '-'.($split[1]-1);    //Year from month
    }

    $poArray = explode(',',$po_nos);

    $amountFlag = true;
    $error = 3;
    $po_error = '';
    $msg = "";

    if(count($poArray)<=4)
    {
        foreach($poArray as $po_no)
        {
            if($amount = $this->PONumber->query("SELECT pn.balAmount FROM po_number_particulars pnp INNER JOIN po_number pn ON pnp.data_id = pn.id
WHERE pnp.poNumber = '$po_no' AND STR_TO_DATE(CONCAT('1-','$month'),'%d-%b-%y') BETWEEN pnp.periodTo AND pnp.periodFrom"))
            {
                $grnd -= $amount['0']['pn']['balAmount'];
            }
            else 
            {
                $amountFlag = false;
                $po_error = $po_no;
                $error = 1;
                $msg = "PO Number -$po_no Not Matched##0";
                break;
            }

        }
        if($grnd>=0 && $amountFlag)
        {
             $error = 2;
             $amountFlag =false;
             $msg = "PO Amount is less than Grand Total##0";
        }
    }
    else
    {
                $amountFlag = false;
                $po_error = $po_no;
                $error = 1;
                $msg = "Please Do Not Enter PO Number More Than 4##0";
    }

    if($amountFlag)
    {
        $msg = "OK##1";
    }
    echo $msg; exit;
}

public function get_provision_months()
{
    $this->layout="ajax";
    $year = $this->request->data['year'];
    $month = $this->request->data['month'];
    $branch_name = $this->request->data['branch'];
    $cost_center = $this->request->data['cost_center'];
    
    $monthArr=array(
        '1'=>'Apr','2'=>'May','3'=>'Jun',
        '4'=>'Jul','5'=>'Aug','6'=>'Sep',
        '7'=>'Oct','8'=>'Nov','9'=>'Dec',
        '10'=>'Jan','11'=>'Feb','12'=>'Mar');
    
       $arr_month =explode('-',$year);
    
        $array_print_month = array();
    
        foreach($monthArr as $k=>$v)
        {
            $amt = 0;
            if(in_array($month,array('Jan','Feb','Mar')))
            {
                if($arr_month[0]==date('Y') || $arr_month[1]==date('y'))
                {
                    $Nmonth=$v."-".date('y');
                }
                else 
                {
                    $Nmonth=$v."-".$arr_month[1];
                }
                
            }
            else
            {
                $Nmonth=$v."-".($arr_month[1]-1); 
            }
            
            //echo "finance_year='$year' and month='$Nmonth' and branch_name='$branch_name' and cost_center='$cost_center' and provision_balance!=0";  exit;
            $prov_ = $this->Provision->find('first',array('conditions'=>"finance_year='$year' and month='$Nmonth' and branch_name='$branch_name' and cost_center='$cost_center'"));
            if(!empty($prov_))
            {
                $amt = $prov_['Provision']['provision'];  
            }
            
            $out_source_master = $this->ProvisionPart->query("Select * from provision_particulars pp where FinanceYear='$year' and FinanceMonth='$Nmonth' and Branch_OutSource='$branch_name' and Cost_Center_OutSource='$cost_center' ");
            foreach($out_source_master as $osm)
            {
                $amt += round($osm['pp']['outsource_amt'],2); 
            }
            
            
            
            $prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where Provision_Finance_Year='$year' and Provision_Finance_Month='$Nmonth' and Provision_Branch_Name='$branch_name' and Provision_Cost_Center='$cost_center' and deduction_status='1'");
            foreach($prov_deduction as $pd)
            {
                $amt -= round($pd['pmmd']['ProvisionBalanceUsed'],2);
            }
            
            if($amt>0)
            {
                $array_print_month[$v] = round($amt,2);
            }
            
            if($v==$month)
            {
                break;
            }
        }
        //print_r($array_print_month); exit;
    echo '<table border="2">';
        echo '<tr><th colspan="3">Please Choose Revenue From Below Months</th></tr>';
        echo "<tr><td>Month</td><td>Provision Amount</td><td>Billing Amount</td></tr>";
        
    foreach($array_print_month as $month=>$arr_revenue)
    {
        echo "<tr>";
            echo '<td>';
            echo '<input type="checkbox" id="'.$month.'" name="data[InitialInvoice][MonthArr]['.$month.']" value="1" onclick="get_display('."'".$month."'".')">'.$month;
            echo "</td>";
            echo '<td>';
            echo $arr_revenue;
            echo '</td>';
            echo '<td><div id="'.$month.'Disp" style="display:none">';
            echo '<input type="text" id="input'.$month.'" name="data[InitialInvoice][Months]['.$month.']" placeholder="Revenue" value="'.$arr_revenue.'" onblur="get_revenue_total()" >';
            echo "</div></td>";
        echo "</tr>";
                
    }
    
    
    
    echo "<tr>";
            echo '<th>';
            echo 'Total';
            echo "</th>";
            echo '<th>';
            
            echo "</th>";
            echo '<td>';
            echo '<div id="Total">0';
            echo "</div></td>";
        echo "</tr>";
    
    echo "</table>";
    
    echo '<input type="hidden" id="month_check" value="'.implode(',',array_keys($array_print_month)).'" >';
    
    exit;
}

public function index_ledger() 
{
    $this->layout='user';
    $data = array();
    $finYear = '2021-22';
    $year = date('Y');
    $arr = explode('-',$finYear);
    $selectedMonth = $this->request->data['month'];
    //$selectedMonth='Feb';
    $month_arr =array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12) ;
    $month_arr_swap =array_flip($month_arr) ;
    $month_no = 0;
    if(empty($selectedMonth))
    {
        $Nmonth =$month = date('M');
        $month_no = $month_arr[date('M')];
    }
    else
    {
        $Nmonth =$month = $selectedMonth;
        $month_no = $month_arr[$selectedMonth]; 
    }
    
    $current_month = date('M');
    
    for($i=1;$i<$month_no;$i++)
    {
        $Nmonth =$month = $month_arr_swap[$i];
        $dateInfo = date_parse_from_format('Y-M-d',"$year-$Nmonth-01");
        $unixTimestamp = mktime(
        $dateInfo['hour'], $dateInfo['minute'], $dateInfo['second'],
        $dateInfo['month'], $dateInfo['day'], $dateInfo['year'],
        $dateInfo['is_dst']
        );
        $month_start_date = date('Y-m-d',$unixTimestamp);
        $month_end_date = date("Y-m-t",$unixTimestamp);
        
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
        //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE company_id in ('278','301','389') and  `status` = 'A' order by company_name");
        //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE company_id='377' and `status` = 'A' order by company_name"); 
        //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE `status` = 'A' order by company_name"); 
        foreach($client_det_arr as $clr)
        {
            $clientId = $clr['rm']['company_id'];
            $clientName = $clr['rm']['company_name'];
            
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
                
                $data[$clientName]['ledger_sub'] = round($op_det_ledger['0']['bl']['subs'],2);
                $data[$clientName]['talk_time'] = round($talktime,2);
                $data[$clientName]['ledger_topup'] = round($op_det_ledger['0']['bl']['topup'],2);
                $data[$clientName]['ledger_setup'] = round($op_det_ledger['0']['bl']['setup_cost'],2);
            }
            
            //print_r($data);
            //echo '<br/>';
            //echo '<br/>';
            $op_det = $this->RegistrationMaster->query("SELECT op_bal,cs_bal,fr_val,subs_val,adv_val,op_dd FROM billing_opening_balance_history bob WHERE clientId = '$clientId' and bill_start_date='$month_start_date'");
            if(!empty($op_det['0']['bob']['op_dd']))
            {
                $data[$clientName]['op_dd'] = $op_det['0']['bob']['op_dd'];
            }
            $data[$clientName]['cs_bal'] = $op_det['0']['bob']['cs_bal'];
            //$data[$clientName]['fr_val'] = round($FreeValue,2);  
            //$data[$clientName]['fr_val'] = $op_det['0']['bob']['fr_val'];
            //$data[$clientName]['adv_val'] = $op_det['0']['bob']['adv_val'];

                $plan_get_qry = "SELECT PlanId,start_date FROM balance_master bm WHERE clientId='$clientId' limit 1"; 
                $bal_det = $this->RegistrationMaster->query($plan_get_qry);
                $planId = $bal_det['0']['bm']['PlanId'];
                $plan_det_qry = "SELECT RentalAmount,PeriodType,Balance,CreditValue FROM `plan_master` pm WHERE id='$planId' limit 1"; 
                $plan_det = $this->RegistrationMaster->query($plan_det_qry);
                $FreeValue = round($plan_det['0']['pm']['Balance']/12);
                /*if($plan_det['0']['pm']['PeriodType']=='YEAR')
                {
                    $FreeValue = round($plan_det['0']['pm']['Balance']/12);
                }
                else if($plan_det['0']['pm']['PeriodType']=='Quater')
                {
                    $FreeValue = round($plan_det['0']['pm']['Balance']/4);
                }
                 else if($plan_det['0']['pm']['PeriodType']=='MONTH')
                {
                    $FreeValue = round($plan_det['0']['pm']['Balance']);
                }*/   
                $data[$clientName]['free_value'] =round($FreeValue,2);

            $cost_cat_arr = $this->CostCenterMaster->query("select * from cost_master cm where dialdesk_client_id='$clientId' limit 1");
            foreach($cost_cat_arr as $cost)
            {
                $cost_center = $cost['cm']['cost_center'];  
                $data[$clientName]['cost_center'] = $cost_center;
                $data[$clientName]['month'] = $month;
                $sel_billing_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category in ('Talk Time','Topup') and  finance_year='$finYear' and month='$month'  and bill_no!=''";
                $bill_det_arr = $this->InitialInvoice->query($sel_billing_qry);


                foreach($bill_det_arr as $inv)
                {
                    $data[$clientName]['billed'] += $inv['ti']['total'];
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
    WHERE bpp.financial_year='$finYear' AND bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('Talk Time','Topup')
    AND bpp.pay_dates  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no "; 
                    $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);

                    foreach($collection_det_arr as $coll_det)
                    {
                        //$data[$clientName]['coll'] += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                        $data[$clientName]['coll'] += $coll_det['0']['bill_passed'];
                    }

                $sel_subs_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category='subscription' and  finance_year='$finYear' and month='$month'";
                $bill_subs_arr = $this->InitialInvoice->query($sel_subs_qry);

                $subs_coll = 0; $subs_coll2 = 0;
                foreach($bill_subs_arr as $inv)
                {
                    $fin_year = $inv['ti']['finance_year'];
                    $company_name = $cost['cm']['company_name'];
                    $branch = $inv['ti']['branch_name'];
                    $bill_no = $inv['ti']['bill_no'];
                    $data[$clientName]['subbilled'] += $inv['ti']['grnd'];
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
    WHERE bpp.financial_year='$finYear' AND bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('subscription')
    AND bpp.pay_dates  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no"; 
                    $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);

                    foreach($collection_det_arr as $coll_det)
                    {
                        //echo $month;exit;
                        //echo "{$coll_det['ti']['month']}==$month";
                        //echo "<br/><br/>";
                        if($coll_det['ti']['month']==$month)
                        {
                            //$subs_coll += $coll_det['bpp']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                            $subs_coll += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']); 
                        }
                        else
                        {
                           // $data[$clientName]['coll'] += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                             //$data[$clientName]['coll'] += $coll_det['0']['bill_passed'];
                            $subs_coll2 +=$coll_det['0']['bill_passed'];
                        }
                    }

                    $selec_all_payment_qry_for_release = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
     tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
    WHERE bpp.financial_year='$finYear' AND bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('subscription')
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

                $plan_get_qry = "SELECT PlanId,start_date FROM balance_master bm WHERE clientId='$clientId' limit 1"; 
                $bal_det = $this->RegistrationMaster->query($plan_get_qry);
                $planId = $bal_det['0']['bm']['PlanId'];
                $start_date = $bal_det['0']['bm']['start_date']; 
                $end_date = date('Y-m-d');
                $plan_det_qry = "SELECT RentalAmount,PeriodType,Balance FROM `plan_master` pm WHERE id='$planId' limit 1"; 
                $plan_det = $this->RegistrationMaster->query($plan_det_qry);
                $RentalAmount = $plan_det['0']['pm']['RentalAmount'];
                $PeriodType = $plan_det['0']['pm']['PeriodType'];
                $Balance = $plan_det['0']['pm']['Balance'];

                //echo $subs_coll;exit;
                $data[$clientName]['subs_coll'] = round($subs_coll+$subs_coll2,2);
                /*if(!empty($subs_coll))
                {
                    $data[$clientName]['fr_val'] = round($FreeValue,2);  
                    /*if($subs_coll>=$RentalAmount)
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
                      echo  $nsub = $FreeValue-$sub; exit;
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
                if(strtolower($PeriodType)==strtolower('MONTH'))
                {
                    $subs_penP = ceil($noofday/30);
                    $NewRentalAmount = $RentalAmount/12;
                    $monthlyFreeValue = $Balance;
                }
                else if(strtolower($PeriodType)==strtolower('Quater'))
                {
                    $subs_penP = ceil($noofday/90);
                    $NewRentalAmount = $RentalAmount/4;
                    $monthlyFreeValue = round($Balance/3,2);
                }
                else if(strtolower($PeriodType)==strtolower('YEAR'))
                {
                    $subs_penP = round($noofday/365);
                    $NewRentalAmount = $RentalAmount/1;
                    $monthlyFreeValue = round($Balance/12,2);
                }
                if($subs_penP>=1)
                {
                   $subs_penP = 1; 
                }

                $sub_amt =round($NewRentalAmount*$subs_penP,2);
                $subs_val = round($sub_amt-$subs_coll,2);
                if($subs_val>0)
                {
                    $data[$clientName]['subs_val'] = $subs_val;
                }

            }
            //echo "{$data[$clientName]['talk_time']} + {$data[$clientName]['ledger_topup']}";exit;
            
            $led_Top = ($data[$clientName]['talk_time']+$data[$clientName]['ledger_topup']); 
            $led_Tcl = round($led_Top-$data[$clientName]['coll'],2); 
            //echo $data[$clientName]['coll'];
            //echo '<br/>';
            //echo '<br/>';
            
            $led_op = ($data[$clientName]['ledger_sub']); 
            $led_cl = round($led_op-$data[$clientName]['subs_coll'],2); 
            $credit_cl = round($data[$clientName]['op_dd']+$data[$clientName]['fr_val']-$data[$clientName]['cs_bal'],2); 
            
            $data[$clientName] = array();
            $data[$clientName]['talk_time'] = $led_Tcl;
            $data[$clientName]['ledger_sub'] = $led_cl;
            $data[$clientName]['op_dd'] = $credit_cl;
        }
    }
    
    //print_r($data);exit;
    if(empty($selectedMonth))
    {
        $Nmonth =$month = date('M');
        $month_no = $month_arr[date('M')];
    }
    else
    {
        $Nmonth =$month = $selectedMonth;
        $month_no = $month_arr[$selectedMonth];
    }



$dateInfo = date_parse_from_format('Y-M-d',"$year-$Nmonth-01");
        $unixTimestamp = mktime(
        $dateInfo['hour'], $dateInfo['minute'], $dateInfo['second'],
        $dateInfo['month'], $dateInfo['day'], $dateInfo['year'],
        $dateInfo['is_dst']
        );
        $month_start_date = date('Y-m-d',$unixTimestamp);
        $month_end_date = date("Y-m-t",$unixTimestamp);
        
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
        
        //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE company_id in ('278','301','389') and  `status` = 'A' order by company_name");
        //$client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE company_id='377' and  `status` = 'A' order by company_name"); 
        $client_det_arr = $this->RegistrationMaster->query("SELECT * FROM registration_master rm WHERE   `status` = 'A' order by company_name"); 
        foreach($client_det_arr as $clr)
        {
            $clientId = $clr['rm']['company_id'];
            $clientName = $clr['rm']['company_name'];
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
                if($talktime<0)
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
                
            }

            $billing_opening_balance = "billing_opening_balance";
            if($current_month!=$Nmonth)
            {
                $billing_opening_balance = "billing_opening_balance_history";
            }
            $op_det = $this->RegistrationMaster->query("SELECT op_bal,cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$clientId' and bill_start_date='$month_start_date'");
            //print_r($op_det);exit;
            if(!empty($op_det['0']['bob']['op_dd']))
            {
                //echo 'xyz';exit;
                $data[$clientName]['op_dd'] = $op_det['0']['bob']['op_dd'];
            }
            else
            {
                $op_det = $this->RegistrationMaster->query("SELECT op_bal,cs_bal,fr_val,subs_val,adv_val,op_dd FROM $billing_opening_balance bob WHERE clientId = '$clientId' and bill_start_date='$month_start_date'");
                if(!empty($op_det['0']['bob']['op_dd']))
                {
                    //echo 'zab';exit;
                    $data[$clientName]['op_dd'] = $op_det['0']['bob']['op_dd'];
                }
            }
            $data[$clientName]['cs_bal'] = $op_det['0']['bob']['cs_bal'];
            //$data[$clientName]['fr_val'] = $op_det['0']['bob']['fr_val'];
            //$data[$clientName]['adv_val'] = $op_det['0']['bob']['adv_val'];

                $plan_get_qry = "SELECT PlanId,start_date FROM balance_master bm WHERE clientId='$clientId' limit 1"; 
                $bal_det = $this->RegistrationMaster->query($plan_get_qry);
                $planId = $bal_det['0']['bm']['PlanId'];
                $plan_det_qry = "SELECT RentalAmount,PeriodType,Balance,CreditValue FROM `plan_master` pm WHERE id='$planId' limit 1"; 
                $plan_det = $this->RegistrationMaster->query($plan_det_qry);
                //$FreeValue = $plan_det['0']['pm']['CreditValue'];
                $FreeValue = round($plan_det['0']['pm']['Balance']/12);
                $data[$clientName]['RentalAmount'] = $plan_det['0']['pm']['RentalAmount'];
                $data[$clientName]['Balance'] = $plan_det['0']['pm']['Balance'];
                /*if($plan_det['0']['pm']['PeriodType']=='YEAR')
                {
                    $FreeValue = round($plan_det['0']['pm']['Balance']/12);
                }
                else if($plan_det['0']['pm']['PeriodType']=='Quater')
                {
                    $FreeValue = round($plan_det['0']['pm']['Balance']/4);
                }
                 else if($plan_det['0']['pm']['PeriodType']=='MONTH')
                {
                    $FreeValue = round($plan_det['0']['pm']['Balance']);
                } */
                $data[$clientName]['free_value'] =round($FreeValue,2);

            $cost_cat_arr = $this->CostCenterMaster->query("select * from cost_master cm where dialdesk_client_id='$clientId' limit 1");
            foreach($cost_cat_arr as $cost)
            {
                $cost_center = $cost['cm']['cost_center'];  
                $data[$clientName]['cost_center'] = $cost_center;
                $data[$clientName]['month'] = $month;
                $sel_billing_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category in ('Talk Time','Topup') and  finance_year='$finYear' and month='$month'  and bill_no!=''";
                $bill_det_arr = $this->InitialInvoice->query($sel_billing_qry);


                foreach($bill_det_arr as $inv)
                {
                    $data[$clientName]['billed'] += $inv['ti']['total'];
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
    WHERE bpp.financial_year='$finYear' AND bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('Talk Time','Topup')
    AND bpp.pay_dates  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no "; 
                    $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);

                    foreach($collection_det_arr as $coll_det)
                    {
                        //$data[$clientName]['coll'] += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                        $data[$clientName]['coll'] += $coll_det['0']['bill_passed'];
                    }

                $sel_subs_qry = "select * from tbl_invoice ti where cost_center='$cost_center' and category='subscription' and  finance_year='$finYear' and month='$month'";
                $bill_subs_arr = $this->InitialInvoice->query($sel_subs_qry);

                $subs_coll = 0; $subs_coll2 = 0;
                foreach($bill_subs_arr as $inv)
                {
                    $fin_year = $inv['ti']['finance_year'];
                    $company_name = $cost['cm']['company_name'];
                    $branch = $inv['ti']['branch_name'];
                    $bill_no = $inv['ti']['bill_no'];
                    $data[$clientName]['subbilled'] += $inv['ti']['grnd'];
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
    WHERE bpp.financial_year='$finYear' AND bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('subscription')
    AND bpp.pay_dates  between '$month_start_date' and '$month_end_date' GROUP BY ti.bill_no"; 
                    $collection_det_arr = $this->BillMaster->query($selec_all_payment_qry);

                    foreach($collection_det_arr as $coll_det)
                    {
                        //echo $month;exit;
                        
                        if($coll_det['ti']['month']==$month)
                        {
                            //$subs_coll += $coll_det['bpp']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                            $subs_coll += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']); 
                        }
                        else
                        {
                           // $data[$clientName]['coll'] += $coll_det['0']['bill_passed']-($coll_det['ti']['grnd']-$coll_det['ti']['total']);
                             //$data[$clientName]['coll'] += $coll_det['0']['bill_passed'];
                            $subs_coll2 +=$coll_det['0']['bill_passed'];
                        }
                    }

                    $selec_all_payment_qry_for_release = "SELECT cm.client,ti.id,ti.month,ti.bill_no,ti.grnd,ti.total,sum(bpp.bill_passed) bill_passed FROM 
     tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center AND cm.dialdesk_client_id ='$clientId'
    INNER JOIN bill_pay_particulars bpp ON bpp.bill_no = SUBSTRING_INDEX(ti.bill_no,'/',1)
    AND bpp.financial_year=ti.finance_year AND cm.company_name=bpp.company_name AND cm.branch=bpp.branch_name
    WHERE bpp.financial_year='$finYear' AND bpp.company_name='idc' AND bpp.branch_name='noida-dialdesk' AND ti.category IN ('subscription')
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
                $plan_get_qry = "SELECT PlanId,start_date FROM balance_master bm WHERE clientId='$clientId' limit 1"; 
                $bal_det = $this->RegistrationMaster->query($plan_get_qry);
                $planId = $bal_det['0']['bm']['PlanId'];
                $start_date = $bal_det['0']['bm']['start_date']; 
                $end_date = date('Y-m-d');
                $plan_det_qry = "SELECT RentalAmount,PeriodType,Balance FROM `plan_master` pm WHERE id='$planId' limit 1"; 
                $plan_det = $this->RegistrationMaster->query($plan_det_qry);
                $RentalAmount = $plan_det['0']['pm']['RentalAmount'];
                $PeriodType = $plan_det['0']['pm']['PeriodType'];
                $Balance = $plan_det['0']['pm']['Balance'];

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
                if(strtolower($PeriodType)==strtolower('MONTH'))
                {
                    $subs_penP = ceil($noofday/30);
                    $NewRentalAmount = $RentalAmount/12;
                    $monthlyFreeValue = $Balance;
                    $no_of_month = 1;
                }
                else if(strtolower($PeriodType)==strtolower('Quater'))
                {
                    $subs_penP = ceil($noofday/90);
                    $NewRentalAmount = $RentalAmount/4;
                    $monthlyFreeValue = round($Balance/3,2);
                    $no_of_month = 3;
                }
                else if(strtolower($PeriodType)==strtolower('YEAR'))
                {
                    $subs_penP = round($noofday/365);
                    $NewRentalAmount = $RentalAmount/1;
                    $monthlyFreeValue = round($Balance/12,2);
                    $no_of_month = 12;
                }
                if($subs_penP>=1)
                {
                   $subs_penP = 1; 
                }

                $sub_amt =round($NewRentalAmount*$subs_penP*1.18,2);
                $subs_val = round($sub_amt-$subs_coll,2);
                if($subs_val>0)
                {
                    $last_month = $start_date;
                    $today_date = date('Y-m-d');
                    $new_start_Date = date('Y-m-01');
                    //$new_end_date = date('Y-m-t');
                    while(strtotime($last_month)<=strtotime($today_date))
                    {
                        $last_month = date('Y-m-d',strtotime($last_month." +$no_of_month months")); 
                    }
                    $last_month = date('Y-m-d',strtotime($last_month." -$no_of_month months")); 
                    if(strtotime($last_month)>= strtotime($new_start_Date) && strtotime($last_month)<= strtotime($today_date))
                    {
                        $data[$clientName]['subs_val'] = $subs_val; 
                    }
                    
                }

            }
        }
        
    
        
        
    
    
    //print_r($data);exit;
    
    
    
    $this->set('data',$data);
    $this->set('month',$selectedMonth);
}

public function add_bill() 
{
    $this->layout='ajax';
    $username=$this->Session->read("admin_id");
    $result = $this->params->query;
    $this->AddInvParticular->deleteAll(array('username'=>$username));
    //print_r($result);exit;
    $cost_center = $result['cost_center'];
    if($result['bill_type']=='talk')
    {
      $particulars = 'Talk Time';  
    }
    else
    {
        $particulars = 'Subscription';
    }
    $rate = $result['amt'];
    
    $month = date('M');
    $finYear = '2021-22';
    
    $cost_det = $this->CostCenterMaster->find('first',array('conditions'=>"cost_center='$cost_center'"));
    $cost_id = $cost_det['CostCenterMaster']['id'];
    $branch_name = $cost_det['CostCenterMaster']['branch'];
    
    $data = array();
    $data['AddInvParticular']['cost_center_id'] = $cost_id;
    $data['AddInvParticular']['username'] = $username;
    $data['AddInvParticular']['branch_name'] = $branch_name;
    $data['AddInvParticular']['cost_center'] = $cost_center;
    $data['AddInvParticular']['fin_year'] = $finYear;
    $data['AddInvParticular']['month_for'] = $month;
    $data['AddInvParticular']['particulars'] = $particulars;
    $data['AddInvParticular']['rate'] = round($rate,2);
    $data['AddInvParticular']['qty'] = 1;
    $data['AddInvParticular']['amount'] = round($rate,2);
    $this->AddInvParticular->save($data);
    
    return $this->redirect(array('controller'=>'InitialInvoices','action' => "add2?finance_year=$finYear&month=$month&invoiceType=revenue&category=$particulars&servicetax=1&branch_name=$branch_name&cost_center=$cost_center"));
}
 
public function add2() 
{    
    
    $fin_year = $this->request->query['finance_year'];
    $month    =   $this->request->query['month'];
    $invoiceType = $this->request->query['invoiceType'];
    $category = $this->request->query['category'];
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
    $this->layout='user';
    $serviceTax = $this->params->query['servicetax'];
    //$data = $this->params->query['InitialInvoice'];

    $username=$this->Session->read("admin_id");
    $b_name=$this->params->query['branch_name'];
    $cost_center=$this->params->query['cost_center'];

    $dataX=$this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$cost_center)));

    

    $this->set('category',$category);
    $this->set('invoiceType',$invoiceType);
    $this->set('cost_master',$dataX);
    $this->set('tmp_particulars',$this->AddInvParticular->find('all',array('conditions'=>array('username' => $username))));
    $this->set('tmp_deduct_particulars',$this->AddInvDeductParticular->find('all',array('conditions'=>array('username' => $username))));
    $this->set('username', $username);
}


public function index() 
{
    $this->layout='user';
    $this->InitialInvoice->recursive = 0;
    
    $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1,'branch_name'=>'NOIDA-DIALDESK'),'order'=>array('branch_name'=>'asc'))));
    $this->set('client_master', $this->Addclient->find('all',array('conditions'=>array('client_status'=>1),'order'=>array('client_name'=>'asc'))));
    $this->set('cost_master', $this->CostCenterMaster->find('all',array('conditions'=>array('branch'=>'NOIDA-DIALDESK','not'=>array('cost_center'=>''),'active'=>1))));
    $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('active'=>'1','finance_year'=>'2021-22'))));
}




public function add_part1() 
{
    $this->layout = 'ajax';
    $username=$this->Session->read("admin_id");
    $result = $this->request->data['AddInvParticular'];

    $result['particulars'] = addslashes($result['particulars']);
    $result['username'] = $username;

    if($this->AddInvParticular->save($result))
    {
        $this->set('inv_particulars', $this->AddInvParticular->find('all',array('conditions'=>array('username'=>$username))));
    }
}

public function add_part() 
{
    $this->layout = 'ajax';
    
    $initial_id = $this->params->query['initial_id'];
    $particular = addslashes($this->params->query['particular']);
			$rate = $this->params->query['rate'];
			$qty = $this->params->query['qty'];
			//$amount = $this->params->query['amount'];
                        $amount = round($rate*$qty,2);
    $data = $this->InitialInvoice->find('first',
            array('fields' => array('branch_name','invoiceDate','cost_center','finance_year','month','total','app_tax_cal','apply_gst','GSTType'),
            'conditions' => array('id' => $initial_id)
    ));
    $dataX = array('username' => $this->Session->read('admin_id'), 'branch_name' => $data['InitialInvoice']['branch_name'], 
        'cost_center' => $data['InitialInvoice']['cost_center'],
        'fin_year' => $data['InitialInvoice']['finance_year'],
        'month_for' => $data['InitialInvoice']['month'],
        'particulars' => $particular, 'rate' => $rate, 'qty' => $qty, 'amount' => $amount, 'initial_id' => $initial_id);
    //print_r($dataX);exit;
    if($this->Particular -> save($dataX))
    {
        $Total = $this->Particular->query("select sum(amount) Total from inv_particulars tbl where initial_id='$initial_id'");

        $total = $Total['0']['0']['Total'];
        
        $grnd = 
        
        $flag = false;
        if($data['InitialInvoice']['app_tax_cal'] == 1)
        {
            if($data['InitialInvoice']['apply_gst']=='1')
            {
              if($data['InitialInvoice']['GSTType']=='IntraState')
              {
                  $sgst = $cgst = round($total*0.09,0);
              }
              else if($data['InitialInvoice']['GSTType']=='Integrated')
              {
                  $igst = round($total*0.18,0);
              }
              $grnd = $total+$sgst+$cgst+$igst;
              $dataY = array('total'=>$total,'sgst' => $sgst,'cgst'=>$cgst,'igst'=>$igst, 'grnd' => $grnd);
                $this->InitialInvoice->updateAll($dataY,array('id' =>$initial_id));
            }
            
        }
        else
        {
                $dataY = array('total'=>$total, 'grnd' => $total);
                $this->InitialInvoice->updateAll($dataY, array('id' => $initial_id));
        }
    }
}


public function delete_particular2()
{
        $result = $this->params->query;
        $this->AddInvParticular->deleteAll($result);exit;
}

public function add() 
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
    $this->layout='user';
    $serviceTax = $this->params['data']['InitialInvoice']['servicetax'];
    $data = $this->params['data']['InitialInvoice'];

    $username=$this->Session->read("admin_id");
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
                
public function billApproval() 
{
    $this->layout='user';
    $username=$this->Session->read("admin_id");
    $userid = $this->Session->read("admin_id");
    
    $role=$this->Session->read("role");
    
    if ($this->request->is('post')) 
    {		
        $checkTotal = 0;	
        $result=$this->request->data['InitialInvoice'];
        $Revenue = $result['revenue'];
        $RevenueMonthArr = $result['revenue_arr'];
        
        //print_r($RevenueMonthArr);  exit;
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
                $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"));
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
            }
            //// Updating Provision Balance Ends Here  /////////////////////////////
        }
        
        ////// Updating Proforma No in BillMaster //////////////////////////
        if(!$this->BillMaster->updateAll(array('proforma_bill_no'=>$idx),array('Id'=>"1"))) 
        { 
            $Transaction->rollback();
            $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style='color:#FF0000'>".'Proforma No. Not Updated. Please Try Again'."</b></h4>"));
            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
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
        
        $Transaction->commit();
        
        $msg = "Hi <br>".$b_name." has Initiatead Invoice for ".$desc." with Value of ".$grnd." on ".date("F j, Y, g:i a");
        $msg .= "<br><strong><b style=color:#FF0000>Kindly Approve </b></strong>";

        //$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('bill_no'=>'','username'=>$username))));
        $this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style='color:#FF0000'>".' The Proforma Invoice '.$proforma_no.' for amount '.$amount.' to '.$b_name.' has been saved'."</b></h4>"));


            if($role=='admin')
            {
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
            }
            else
            {
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'branch_view'));
            }

                           
    }
    else
    {
        $Transaction->rollback();
        $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'The Initial Invoice could not be saved. Please Try Again.'."</b></h4>"));
    } 
    }
}

public function get_view()
{
        $this->layout = 'ajax';
        $result = $this->params->query;
        $branch_name = $result['branch_name'];
        $qry = "select InitialInvoice.*,cm.client from tbl_invoice InitialInvoice inner join cost_master cm on InitialInvoice.cost_center=cm.cost_center where bill_no='' and status='0' and branch_name='$branch_name'";
        $this->set('tbl_invoice', $this->InitialInvoice->query($qry));
}

public function view()
{	
        $username=$this->Session->read("admin_id");
        $this->layout='user';
        $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1,'branch_name'=>'NOIDA-DIALDESK'),'fields'=>array('branch_name'))));
        //$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('bill_no'=>''))));
}

public function edit_proforma()
{
        //$username=$this->Session->read("admin_id");
        //$branch_name=$this->Session->read("branch_name");
        $roles=explode(',',$this->Session->read("page_access"));
        $id  = $this->request->query['id'];
        $this->layout='user';
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


        $this->set('roles',$roles);
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
$roles=explode(',',$this->Session->read("page_access"));
$role=$this->Session->read("role");

if ($this->request->is('post'))
{
    $id = $this->request->data['InitialInvoice']['id']; 
    if(!empty($this->request->data['Reject']))
    {
        $Transaction = $this->InitialInvoice->getDataSource(); $flag = true;
        $Transaction->begin();

        if($this->InitialInvoice->updateAll(array('status'=>"'1'",'InvoiceRejectBy'=>$this->Session->read('userid'),'InvoiceRejectDate'=>"'".date('Y-m-d H:i:s')."'"),array('id'=>$id)))
        {
            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice Rejected Successfully.'."</b></h4>"));
            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
        }
        else
        {
            $Transaction->rollback();
            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice  Reject Request Failed. Please Contact To Admin'."</b></h4>"));
            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_bill','?'=>array('id'=>$id)));
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

        $b_name=$data['branch_name'];
        $amount=$data['total'];
        $data=Hash::remove($data,'branch_name');


        $date = $data['invoiceDate'];
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

            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice to branch '.$b_name.' for Amount '.$amount.' Updated Successfully.'."</b></h4>"));

            App::uses('sendEmail', 'custom/Email');

            $dataX = $this ->InitialInvoice-> find('first',array('fields' => array('bill_no','grnd','invoiceDescription'),'conditions'=>array('id' => $id)));

            $msg = "Hi<br>".$b_name." branch Proforma No.".$dataX['InitialInvoice']['proforma_bill_no'].' has been Edited. '.date("F j, Y, g:i a");
        }
        $username=$this->Session->read("admin_id");
        return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view_approve_bill'));
    }
}

}

public function download_proforma()
{	
            $this->layout='user';
            $this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('finance_year'=>array('2017-18','2018-19','2019-20','2020-21','2021-22')))));

            $role = $this->Session->read("role");
            if($role=='admin')
            {
            $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1),'order'=>array('branch_name'=>'asc'))));
            }
            else
            {
                $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1,'branch_name'=>$branch_name),'order'=>array('branch_name'=>'asc'))));
            }

            $this->set('company_master', $this->Addcompany->find('list',array('fields'=>array('company_name','company_name'))));

           if($this->request->is('Post'))
            {
                $branch_name=$this->Session->read("branch_name");
                $roles=explode(',',$this->Session->read("page_access"));
                $data = $this->request->data['InitialInvoice'];

                $condition = array("status"=>"0","or"=>array('bill_no'=>''),"not"=>array('proforma_bill_no'=>''),'proforma_approve'=>'0');
                if($data['company_name'] !='')
                    $condition['CostCenterMaster.company_name'] =  $data['company_name'];
                if($data['finance_year'] !='')
                    $condition['InitialInvoice.finance_year'] =  $data['finance_year'];
                if($data['branch_name'] !='')
                    $condition['InitialInvoice.branch_name'] =  $data['branch_name'];
                if($data['bill_no'] !='')
                    $condition["SUBSTRING_INDEX(InitialInvoice.proforma_bill_no,'/','1')"] =  $data['proforma_bill_no'];
                if($role!='admin')
                        $condition['InitialInvoice.branch_name'] =  $branch_name;
                 //print_r($condition); exit;
                $data = $this->InitialInvoice->find('all',array('fields'=>array('id','branch_name','proforma_bill_no','total','po_no','grn','invoiceDescription'),
                    'joins'=>array(array('table'=>'cost_master',
                    'type'=>'inner','alias'=>'CostCenterMaster',
                    'conditions'=>array('InitialInvoice.cost_center = CostCenterMaster.cost_center'))),'conditions'=>$condition));
                $this->set('tbl_invoice',$data);                           
                //print_r($data); die;
            }     
}

public function view_bill()
{	
    $username=$this->Session->read("admin_id");
    $id  = $this->request->query['id'];
    $back_url  = $this->request->query['back_url'];
    $this->set('back_url', $back_url);
    $this->layout='user';
    $data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'bill_no'=>'','status'=>0)));

    $b_name=$data['InitialInvoice']['branch_name'];
    $c_center=$data['InitialInvoice']['cost_center'];

    $this->set('tbl_invoice', $data);
    $this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,
        'cost_center'=>$c_center))));
    $this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
    $this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
}

public function view_approve_bill()
{	
    $username=$this->Session->read("admin_id");
    $this->layout='user';
    $qry = "select InitialInvoice.*,cm.client from tbl_invoice InitialInvoice inner join cost_master cm on InitialInvoice.cost_center=cm.cost_center where bill_no='' and status='0' and branch_name='NOIDA-DIALDESK'";
    $this->set('tbl_invoice', $this->InitialInvoice->query($qry));
    //$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('bill_no'=>''))));
}

public function get_view_approved()
{
        $this->layout = 'ajax';
        $result = $this->params->query;
        $branch_name = $result['branch_name'];
        $qry = "select InitialInvoice.*,cm.client from tbl_invoice InitialInvoice inner join cost_master cm on InitialInvoice.cost_center=cm.cost_center where finance_year='2021-22' and bill_no!='' and status='0' and InitialInvoice.category in ('Subscription','Talk Time','Setup Cost') and branch_name='$branch_name'";
        $this->set('tbl_invoice', $this->InitialInvoice->query($qry));
}

public function edit_bill()
{
$roles=explode(',',$this->Session->read("page_access"));
$id  = $this->request->query['id'];
$this->layout='user';
$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'bill_no'=>'')));
$b_name=$data['InitialInvoice']['branch_name'];
$c_center=$data['InitialInvoice']['cost_center'];
$this->set('roles',$roles);
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
$roles=explode(',',$this->Session->read("page_access"));
$role=$this->Session->read("role");

if ($this->request->is('post'))
{
    $submit = $this->request->data['Approve'];
    $id = $this->request->data['InitialInvoice']['id']; 
    if($submit=='Reject')
    {
        /*$Transaction = $this->InitialInvoice->getDataSource(); $flag = true;
        $Transaction->begin();

        if($this->InitialInvoice->updateAll(array('status'=>"'1'",'InvoiceRejectBy'=>$this->Session->read('userid'),'InvoiceRejectDate'=>"'".date('Y-m-d H:i:s')."'"),array('id'=>$id)))
        {
            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice Rejected Successfully.'."</b></h4>"));
            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
        }
        else
        {
            $Transaction->rollback();
            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice  Reject Request Failed. Please Contact To Admin'."</b></h4>"));
            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_bill','?'=>array('id'=>$id)));
        }*/
        if($this->InitialInvoice->deleteAll(array('id'=>$id)))
        {
        $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice Rejected Successfully.'."</b></h4>"));
        }
        else
        {
            
        }
        
        return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view_approve_bill','?'=>array('id'=>$id)));
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

        $b_name=$data['branch_name'];
        $amount=$data['total'];
        $data=Hash::remove($data,'branch_name');


        $date = $data['invoiceDate'];
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
                $msg = "Hi<br>".$b_name." branch Proforma No.".$dataX['InitialInvoice']['proforma_bill_no'].' has been Edited. '.date("F j, Y, g:i a");
                $this->Session->setFlash(__($msg));
                $Transaction->commit();
            }
            if($submit=='Approve')
            {
            $Transaction->begin();
            
            $cost_center = $this->InitialInvoice->find('first',array('fields'=>array('cost_center','finance_year'),'conditions'=>array('id'=>$id)));
            $company = $this->CostCenterMaster->find('first',array('fields'=>array('company_name','branch'),
                'conditions'=>array('cost_center'=>$cost_center['InitialInvoice']['cost_center'])));
            $companyName = $company['CostCenterMaster']['company_name'];
            $b_name = $company['CostCenterMaster']['branch'];
            $f_year1 = $cost_center['InitialInvoice']['finance_year'];
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
            $Transaction->query("UNLOCK TABLES"); //unlock to update table
            $data=array('bill_no'=>"'".$bill_no."'",'BillNoChange'=>"$idx",'state_code'=>"'".$state_code."'");

                if ($this->InitialInvoice->updateAll($data,array('id'=>$id)))
                {
                    $Transaction->commit();
                    $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Bill No. '.$bill_no.' to Branch '.$branch_name.' for amount '.$amount.' for '.$month.' Generated Successfully.'."</b></h4>"));
                }
                else
                {
                    $Transaction->rollback();
                    $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>Data Not Updated</b></h4>"));
                }
            }
        }
        
        return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view_approve_bill'));
    }
}

}

public function view_proforma_pdf()
{	

        ini_set('memory_limit', '512M');

        $id  = base64_decode($this->request->query['id']);
        $username=$this->Session->read("username");
        $branch_name=$this->Session->read("branch_name");

        $roles=explode(',',$this->Session->read("page_access"));
        $role=$this->Session->read("role");
        $data='';

            $data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));



        $b_name=$data['InitialInvoice']['branch_name'];
        $c_center=$data['InitialInvoice']['cost_center'];
        $this->set("branch_detail",$this->Addbranch->find("first",array("conditions"=>array("branch_name"=>$b_name))));
        $this->set('tbl_invoice', $data);
        $cost_master = $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center)));
        $this->set('cost_master', $cost_master);
        $this->set('company',$this->Addcompany->find("first",array('conditions'=>array('company_name'=>$cost_master['CostCenterMaster']['company_name']))));
        $this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
        $this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
}

public function view_proforma_letter_pdf()
{	

        ini_set('memory_limit', '512M');

        $id  = base64_decode($this->request->query['id']);
        $username=$this->Session->read("username");
        $branch_name=$this->Session->read("branch_name");
        $roles=explode(',',$this->Session->read("page_access"));
        $role=$this->Session->read("role");

        if($role=='admin')
        {
                $data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));
        }
        else
        {			
                if(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'branch_name'=>$branch_name))))
                {return $this->redirect(array('controller'=>'Users','action' => 'login')); }
        }

        $b_name=$data['InitialInvoice']['branch_name'];
        $c_center=$data['InitialInvoice']['cost_center'];
        $this->set("branch_detail",$this->Addbranch->find("first",array("conditions"=>array("branch_name"=>$b_name))));
        $this->set('tbl_invoice', $data);
         $cost_master = $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center)));
        $this->set('cost_master', $cost_master);
        $this->set('company',$this->Addcompany->find("first",array('conditions'=>array('company_name'=>$cost_master['CostCenterMaster']['company_name']))));
        $this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
        $this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
}

public function view_approved_bill()
{	
        $username=$this->Session->read("admin_id");
        $this->layout='user';
        $this->set('branch_master', $this->Addbranch->find('all',array('conditions'=>array('active'=>1,'branch_name'=>'NOIDA-DIALDESK'),'fields'=>array('branch_name'))));
        //$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('bill_no'=>''))));
}

public function genrate_bill()
{
    $this->layout='user';

    if ($this->request->is('post'))
    {
        $id=$this->params['data']['InitialInvoice']['id'];
        $b_name=$this->params['data']['InitialInvoice']['branch_name'];
        $f_year=$this->params['data']['InitialInvoice']['finance_year'];
        $f_year1=$this->params['data']['InitialInvoice']['finance_year'];
        $amount=$this->params['data']['InitialInvoice']['total'];
        $month=$this->params['data']['InitialInvoice']['month'];
        $po=$this->params['data']['InitialInvoice']['po_no'];
        $Total = $amount;
        $amountFlag = true;

        $Transaction = $this->User->getDataSource();
        $Transaction->begin();

        if($po != '')
        {
            $data=array('bill_no'=>"'".$bill_no."'",'approve_po'=>"'Yes'");
            $poArray = explode(',',$po);

            $error = 3;
            $po_error = '';
            $msg = "";
            $data_id[] = 0;
            if(count($poArray)<=4)
            {
                $data_ids = implode(',', $data_id);
                foreach($poArray as $po_no)
                {
                    if($POAmount = $this->PONumber->query("SELECT pn.balAmount,pnp.data_id FROM po_number_particulars pnp INNER JOIN po_number pn ON pnp.data_id = pn.id
                        WHERE pnp.poNumber = '$po_no' AND pnp.data_id not in ($data_ids) AND STR_TO_DATE(CONCAT('1-','$month'),'%d-%b-%y') BETWEEN pnp.periodTo AND pnp.periodFrom"))
                    {
                        $Total -= $POAmount['0']['pn']['balAmount'];
                        $data_id[] = $POAmount['0']['pnp']['data_id'];
                    }
                    else 
                    {
                        $amountFlag = false;
                        $po_error = $po_no;
                        $error = 1;
                        $msg = "PO Number -$po_no Not Matched";
                        break;
                    }


                }
                if($Total>=0 && $amountFlag)
                {
                     $error = 2;
                     $amountFlag =false;
                     $msg = "PO Amount is less than Grand Total";
                }
            }
            else
            {
                        $amountFlag = false;
                        $po_error = $po_no;
                        $error = 1;
                        $msg = "Please Do Not Enter PO Number More Than 4";
            }

            if($amountFlag)
            {
                $msg = "OK";
            }
        }

        if($amountFlag)
        {
        $branch_name=$b_name;

        $data=$this->Addbranch->find('first',array('conditions'=>array('branch_name'=>$b_name)));
        $b_name=$data['Addbranch']['branch_code'];
        $state_code = $data['Addbranch']['state_code'];

        $cost_center = $this->InitialInvoice->find('first',array('fields'=>array('cost_center'),'conditions'=>array('id'=>$id)));


        $company = $this->CostCenterMaster->find('first',array('fields'=>array('company_name'),
            'conditions'=>array('cost_center'=>$cost_center['InitialInvoice']['cost_center'])));
        $companyName = $company['CostCenterMaster']['company_name'];

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


        $Transaction->query("UNLOCK TABLES"); //unlock to update table

        //$this->tbl_invoice->updateAll(array('bill_no'=>$idx),array('company_name'=>$companyName,'finance_year'=>$f_year));
        $data=array('bill_no'=>"'".$bill_no."'",'BillNoChange'=>"$idx",'state_code'=>"'".$state_code."'");

        //if($po != ''){$data=array('bill_no'=>"'".$bill_no."'",'approve_po'=>"'Yes'");}

        if ($this->InitialInvoice->updateAll($data,array('id'=>$id))) 
        {   
                $data_id = array(0); $Total = $amount;
                foreach($poArray as $po_no)
                {

                    $data_ids = implode(',', $data_id);
                    $this->PONumber->query("UPDATE po_number_particulars pnp INNER JOIN po_number pn ON pnp.data_id = pn.id SET pn.balAmount = IF(pn.balAmount>$Total,pn.balAmount-$Total,0)
                    WHERE pnp.poNumber = '$po_no' AND STR_TO_DATE(CONCAT('1-','$month'),'%d-%b-%y') BETWEEN pnp.periodTo AND pnp.periodFrom");

                    if($POAmount = $this->PONumber->query("SELECT pn.balAmount,pnp.data_id FROM po_number_particulars pnp INNER JOIN po_number pn ON pnp.data_id = pn.id
                        WHERE pnp.poNumber = '$po_no' AND pnp.data_id not in ($data_ids) AND STR_TO_DATE(CONCAT('1-','$month'),'%d-%b-%y') BETWEEN pnp.periodTo AND pnp.periodFrom"))
                    {
                        $Total -= $POAmount['0']['pn']['balAmount'];
                        $data_id[] = $POAmount['0']['pnp']['data_id'];
                    }
                    $data_ids = implode(',', $data_id);
                }
                $Transaction->commit();

                App::uses('sendEmail', 'custom/Email');

                $dataX = $this ->InitialInvoice-> find('first',array('fields' => array('grnd','invoiceDescription'),'conditions'=>array('id' => $id)));

                $msg = "Hi<br> ADMIN Has Approved ".$branch_name." branch Initial Invoice ".$bill_no." for ".$dataX['InitialInvoice']['invoiceDescription']." with value of ".$dataX['InitialInvoice']['grnd']." on ".date("F j, Y, g:i a");
                $msg .= "<br><strong><b style=color:#FF0000> Approved </b></strong>";
                $emailid = $this ->User->find("all",
                array('fields' => array('email','id','branch_name'),
                'conditions' =>"(user_type IS NULL OR user_type!='Super-Admin') and work_type='account' and UserActive='1' and email!='' and (role='admin' or branch_name='$b_name')" ));

                $nofifyArr = array(); $notifyLoop=0;

                foreach($emailid as $email1):
                        $email2[] = trim($email1['User']['email']);
                        $nofifyArr[$notifyLoop]['userid'] = $email1['User']['id'];
                        $nofifyArr[$notifyLoop]['branch'] = $email1['User']['branch_name'];
                        $nofifyArr[$notifyLoop]['msg'] = addslashes($msg);
                        $nofifyArr[$notifyLoop++]['createdate'] = date('Y-m-d H:i:s');
                endforeach;

                $this->NotificationMaster->saveAll($nofifyArr);

                $sub = "Initial Invoice Approve Bill to ".$branch_name;
                $mail = new sendEmail();

                    if(!empty($email2))
                                        {
                                            try
                                            {
                                                $mail-> to($email2,$msg,$sub);	
                                            }
                                            catch(Exception $e)
                                            {

                                            }
                                        }
                $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Bill No. '.$bill_no.' to Branch '.$branch_name.' for amount '.$amount.' for '.$month.' Generated Successfully. And moved to Branch'."</b></h4>"));
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
            }
            else
            {
                $Transaction->rollback();
                $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>Data Not Updated</b></h4>"));
                return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
            }

        }	
        else
        {
            $Transaction->query("unlock tables");
            $Transaction->rollback();
            $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>$msg</b></h4>"));
            return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view'));
        }
    }

}		
public function download()
{	
$this->layout='user';
$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name')))); 
$this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
 $this->set('company_master', $this->Addcompany->find('list',array('fields'=>array('company_name','company_name'))));
if($this->request->is('Post'))
{
    $branch_name=$this->Session->read("branch_name");
    $roles=explode(',',$this->Session->read("page_access"));
    $role = $this->Session->read("role");
    $data = $this->request->data['InitialInvoice'];

    $condition = array();
    if($data['company_name'] !='')
        $condition['CostCenterMaster.company_name'] =  $data['company_name'];
    if($data['finance_year'] !='')
        $condition['InitialInvoice.finance_year'] =  $data['finance_year'];
    if($data['branch_name'] !='')
        $condition['InitialInvoice.branch_name'] =  $data['branch_name'];
    if($data['bill_no'] !='')
        $condition["SUBSTRING_INDEX(InitialInvoice.bill_no,'/','1')"] =  $data['bill_no'];
    if($role!='admin')
            $condition['InitialInvoice.branch_name'] =  $branch_name;
    $data = $this->InitialInvoice->find('all',array('fields'=>array('id','branch_name','bill_no','total','po_no','grn','invoiceDescription'),
        'joins'=>array(array('table'=>'cost_master',
        'type'=>'inner','alias'=>'CostCenterMaster',
        'conditions'=>array('InitialInvoice.cost_center = CostCenterMaster.cost_center'))),'conditions'=>$condition));
    $this->set('tbl_invoice',$data);                           
    //print_r($data); die;
}     
}

public function view_pdf()
{	

ini_set('memory_limit', '512M');

$id  = base64_decode($this->request->query['id']);
$username=$this->Session->read("username");
$branch_name=$this->Session->read("branch_name");

$roles=explode(',',$this->Session->read("page_access"));
$role=$this->Session->read("role");
$data='';
if($role=='admin')
{
    $data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));
}
else
{
        if(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'branch_name'=>$branch_name))))
        {
            return $this->redirect(array('controller'=>'Users','action' => 'login')); 
        }
}


$b_name=$data['InitialInvoice']['branch_name'];
$c_center=$data['InitialInvoice']['cost_center'];
$this->set("branch_detail",$this->Addbranch->find("first",array("conditions"=>array("branch_name"=>$b_name))));
$this->set('tbl_invoice', $data);
$cost_master = $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center)));
$this->set('cost_master', $cost_master);
$this->set('company',$this->Addcompany->find("first",array('conditions'=>array('company_name'=>$cost_master['CostCenterMaster']['company_name']))));
$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
}
public function view_pdf1()
{	

ini_set('memory_limit', '512M');

$id  = $this->request->query['id'];
$username=$this->Session->read("username");
$branch_name=$this->Session->read("branch_name");
$roles=explode(',',$this->Session->read("page_access"));
$role=$this->Session->read("role");

if($role=='admin')
{
        $data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));
}
else
{			
        if(!$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'branch_name'=>$branch_name))))
        {return $this->redirect(array('controller'=>'Users','action' => 'login')); }
}

$b_name=$data['InitialInvoice']['branch_name'];
$c_center=$data['InitialInvoice']['cost_center'];
$this->set("branch_detail",$this->Addbranch->find("first",array("conditions"=>array("branch_name"=>$b_name))));
$this->set('tbl_invoice', $data);
 $cost_master = $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center)));
$this->set('cost_master', $cost_master);
$this->set('company',$this->Addcompany->find("first",array('conditions'=>array('company_name'=>$cost_master['CostCenterMaster']['company_name']))));
$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id,'testactive'=>'1'))));
$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
}

public function view_invoice()
{	
$username=$this->Session->read("username");
$this->layout='user';
$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
$this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('not'=>array('finance_year'=>'14-15')))));
if($this->request->is('Post'))
{

    $data = $this->request->data['InitialInvoice'];
    $condition = '';
    if($data['company_name'] !='')
        $condition .=" and cm.company_name =  '{$data['company_name']}'";
    if($data['finance_year'] !='')
    $condition .=" and ti.finance_year =  '{$data['finance_year']}'";
    if($data['branch_name'] !='')
    $condition .=" and ti.branch_name =  '{$data['branch_name']}'";
    if($data['bill_no'] !='')
        $condition .=" and ti.bill_no =  '{$data['bill_no']}'";

    $data = $this->InitialInvoice->query("SELECT ti.id,ti.branch_name,ti.bill_no,ti.total,ti.po_no,ti.grn,ti.invoiceDescription FROM tbl_invoice ti 
    INNER JOIN cost_master cm ON ti.cost_center = cm.cost_center
    LEFT JOIN bill_pay_particulars bpp ON 
    SUBSTRING_INDEX(ti.bill_no,'/','1') = bpp.bill_no
    AND ti.branch_name = bpp.branch_name
    AND ti.finance_year = bpp.financial_year
    AND cm.company_name = bpp.company_name

    WHERE bpp.bill_no IS  NULL and ti.bill_no!='' $condition ");
    $this->set('tbl_invoice',$data);
    //print_r($data); die;
}    
//$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('bill_no'=>''))));
}

public function edit_invoice()
{	
$roles=explode(',',$this->Session->read("page_access"));

$id  = $this->request->query['id'];
$this->layout='user';
$data=$this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id,'status'=>'0')));

//print_r($data); exit;

$b_name = $data['InitialInvoice']['branch_name'];
$c_center = $data['InitialInvoice']['cost_center'];

$prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where InvoiceId= '$id'");

$ActualRevenue = array(); 
foreach($prov_deduction as $pd)
{
    $ProvisionId = $pd['pmmd']['ProvisionId'];
    $revenue += round($pd['pmmd']['ProvisionBalanceUsed'],2);
    $monthMaster[$pd['pmmd']['Provision_Finance_Month']] = round($pd['pmmd']['ProvisionBalanceUsed'],2);
    $ActualProvArr = $this->Provision->find('first',array('conditions'=>"Id='$ProvisionId'"));
    $ActualRevenue[$pd['pmmd']['Provision_Finance_Month']]=  round($ActualProvArr['Provision']['provision_balance'],2) + round($pd['pmmd']['ProvisionBalanceUsed'],2);
}


$this->set('roles',$roles);
$this->set('revenue',$revenue);
$this->set('monthMaster',$monthMaster);
$this->set('branch_master', $this->Addbranch->find('all',array('fields'=>array('branch_name'))));
$this->set('tbl_invoice', $this->InitialInvoice->find('all',array('conditions'=>array('id'=>$id))));
$this->set('cost_master', $this->CostCenterMaster->find('first',array('conditions'=>array('branch'=>$b_name,'cost_center'=>$c_center))));
$this->set('cost_master2', $this->CostCenterMaster->find('all',array('fields'=>array('cost_center'),'conditions'=>array('branch'=>$b_name))));
$this->set('inv_particulars', $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id))));
$this->set('inv_deduct_particulars', $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id))));
$this->set('finance_yearNew', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),'conditions'=>array('active'=>'1'))));
}
public function update_invoice()
{
$this->layout='user';
$roles=explode(',',$this->Session->read("page_access"));
$role=$this->Session->read("role");

if ($this->request->is('post'))
{
$checkTotal = 0; 
//print_r($this->request->data); exit;
$id=$this->params['data']['InitialInvoice']['id'];


$findData = $this->InitialInvoice->find('first',array('conditions'=>array('Id'=>$id)));

$findCostCenter     = $findData['InitialInvoice']['cost_center'];
$findFinanceYear    = $findData['InitialInvoice']['finance_year'];
$findMonth          = $findData['InitialInvoice']['month'];
$branch_name = $findData['InitialInvoice']['branch_name']; 
$GSTType = $this->params['data']['InitialInvoice']['GSTType'];
//$Revenue = $this->request->data['InitialInvoice']['revenue'];
$RevenueMonthArr = $this->request->data['InitialInvoice']['revenue_arr'];
//print_r($RevenueMonthArr); exit;
$arr =explode('-',$findFinanceYear);

foreach($RevenueMonthArr as $Nmonth=>$mntValue)
{
$amt = 0;

$prov_ = $this->Provision->find('first',array('conditions'=>"finance_year='$findFinanceYear' and month='$Nmonth' and branch_name='$branch_name' and cost_center='$findCostCenter'"));
if(!empty($prov_))
{
$amt = $prov_['Provision']['provision'];
$ProvisionId = $prov_['Provision']['id'];
}

//            $out_source_master = $this->ProvisionPart->query("Select * from provision_particulars pp where FinanceYear='$findFinanceYear' and FinanceMonth='$Nmonth' and Branch_OutSource='$branch_name' and Cost_Center_OutSource='$findCostCenter' ");
//            foreach($out_source_master as $osm)
//            {
//                $amt += round($osm['pp']['outsource_amt'],2); 
//            }


$prov_deduction = $this->ProvisionPartDed->query("Select * from provision_master_month_deductions pmmd where Provision_Finance_Year='$findFinanceYear' and Provision_Finance_Month='$Nmonth' and Provision_Branch_Name='$branch_name' and Provision_Cost_Center='$findCostCenter' and deduction_status='1' and InvoiceId!='$id'");
foreach($prov_deduction as $pd)
{
$amt -= round($pd['pmmd']['ProvisionBalanceUsed'],2);
}



if($amt<$mntValue)
{
//echo "Revenue For $Nmonth($mntValue) More Then Provision ($amt) Can't Not Edited"; exit;
$this->Session->setFlash("Revenue For $Nmonth($mntValue) More Then Provision ($amt) Can't Not Edited");
return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
break;
}
else
{
$ProvisionArray[$Nmonth] = $ProvisionId;
$ProvisionBalArray[$Nmonth] = $amt-$mntValue;
}
$Revenue +=$mntValue;
}


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
return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
}

}
unset($dataX);

//print_r($particular); exit;
$flag=false;
if(!isset($this->params['data']['DeductParticular']))
{

}
else
{
$deductparticular = $this->params['data']['DeductParticular'];
$flag=true;
}
if($flag)
{
$k=array_keys($deductparticular);$i=0;

foreach($deductparticular as $post):
$dataX['particulars']="'".addslashes($post['particulars'])."'";
$dataX['qty']="'".$post['qty']."'";
$dataX['rate']="'".$post['rate']."'";
$dataX['amount']="'".$post['amount']."'";
$checkTotal -= $post['amount'];
if(!$this->DeductParticular->updateAll($dataX,array('id'=>$k[$i++])))
{
$Transaction->rollback(); 
$this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Particulars Not Added Please Try Again'."</b></h4>"));
return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
}
endforeach;
}


$findInvAmt = $this->InitialInvoice->query("select sum(amount) total from inv_particulars where initial_id='$id'");
$findSumPrv = $findInvAmt['0']['0']['total'];

$findDInvAmt = $this->InitialInvoice->query("select sum(amount) total from inv_deduct_particulars where initial_id='$id'");
$findSumDPrv = round($findDInvAmt['0']['0']['total']);

$findTotalProvAmt = $this->InitialInvoice->query("select sum(total)total from tbl_invoice where id!='$id' and cost_center='$findCostCenter' and finance_year='$findFinanceYear' and `month`='$findMonth' and `status`='0'");
$findTotalBillMade = round($findSumPrv,2)-round($findSumDPrv,2);

$finn = explode('-',$this->request->data['InitialInvoice']['finance_year']);

$monthArr = array('Jan','Feb','Mar');
$monthM = substr($findMonth,0,3);
if(in_array($monthM, $monthArr))
{
if($finn[0]==date('Y') || $finn[1]==date('y'))
{
$ff_mnt = $monthM.'-'.date('y');
$data['month'] = "'".$ff_mnt."'";
}
else
{
$ff_mnt = $monthM.'-'.$finn[1];
$data['month'] = "'".$ff_mnt."'";
}
}
else
{
$ff_mnt = $monthM.'-'.($finn[1]-1);
$data['month'] = "'".$ff_mnt."'";
}

// echo $findFinanceYear; exit;

if($Revenue==$findTotalBillMade)
// if(1)
{

foreach($RevenueMonthArr as $Nmonth=>$mntValue)
{

if(!$this->ProvisionPartDed->updateAll(array('ProvisionBalanceUsed'=>$mntValue,'Provision_Finance_Year'=>"'".$findFinanceYear."'",'Provision_Finance_Month'=>$data['month'],'Provision_UsedBy_Month'=>$data['month']),array('Provision_Finance_Month'=>$Nmonth,'Provision_UsedBy_Month'=>$findMonth,'InvoiceId'=>$id)))
{
$Transaction->rollback();
$this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"));
return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
}

/////  Updating Provision Balance Starts From Here /////////////////
if(!$this->Provision->updateAll(array('provision_balance'=>"'{$ProvisionBalArray[$mnt]}'"),array('cost_center'=>$findCostCenter,'month'=>$Nmonth,'finance_year'=>$findFinanceYear)))
{ 
$Transaction->rollback();
//echo "<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"; exit;
$this->Session->setFlash(__("<h4 class=bg-active align=center style='font-size:14px'><b style=color:'#FF0000'>".'Provision Not Updated. Please Try Again'."</b></h4>"));
return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
}
//// Updating Provision Balance Ends Here  /////////////////////////////
} 

$invoiceDate = $this->params['data']['InitialInvoice']['invoiceDate'];
$data=Hash::remove($this->params['data']['InitialInvoice'],'id');
$data=Hash::remove($data,'revenue_arr');
$data=Hash::remove($data,'revenue_str');
$data=Hash::remove($data,'revenue');
if(empty($data['RequestInvoiceType']))
{
$data=Hash::remove($data,'RequestInvoiceType');
}
else
{
$data['RequestInvoiceType'] = "'".$data['RequestInvoiceType']."'";
}
if(empty($data['InvoiceTypeRemarks']))
{
$data=Hash::remove($data,'InvoiceTypeRemarks');
}
else
{
$data['InvoiceTypeRemarks'] = "'".$data['InvoiceTypeRemarks']."'";
}
if(empty($data['InvoiceRejectRequest']))
{
$data=Hash::remove($data,'InvoiceRejectRequest');
}
else
{
$data['InvoiceRejectRequest'] = "'".$data['InvoiceRejectRequest']."'";
}
if(empty($data['InvoiceDeleteRemarks']))
{
$data=Hash::remove($data,'InvoiceDeleteRemarks');
}
else
{
$data['InvoiceDeleteRemarks'] = "'".$data['InvoiceDeleteRemarks']."'";
}


$b_name=$data['branch_name'];
$amount=$data['total'];
//$data=Hash::remove($data,'branch_name');

$particular = $this->params['data']['Particular'];

$flag=false;
if(!isset($this->params['data']['DeductParticular']))
{}
else
{
$deductparticular = $this->params['data']['DeductParticular'];
$flag=true;
}

$date = $data['invoiceDate'];
$date = date_create($date);
$date = date_format($date,"Y-m-d");
$data['invoiceType']="'".$this->params['data']['InitialInvoice']['invoiceType']."'";
$data['branch_name']="'".$data['branch_name']."'";
$data['krishi_tax']="'".$data['krishi_tax']."'";
$data['cost_center']="'".$data['cost_center']."'";
$data['finance_year']="'".$data['finance_year']."'";
$data['jcc_no']="'".$data['jcc_no']."'";
$data['grn']="'".addslashes($data['grn'])."'";
$data['bill_no']="'".$data['bill_no']."'";
$po_no = $data['po_no'];
$data['po_no']="'".addslashes($data['po_no'])."'";
$data['invoiceDescription']="'".addslashes($data['invoiceDescription'])."'";
$data['month']="'".addslashes($data['month'])."'";
$data['invoiceDate'] = "'".addslashes($date)."'";		
$data['GSTType'] = "'".addslashes($GSTType)."'";
$month = explode('-',str_replace("'", "", $data['month']));
$month = $month[0];

$finn = explode('-',str_replace("'", "", $data['finance_year']));
$monthArr = array('Jan','Feb','Mar');

if(in_array($month, $monthArr))
{
if($finn[0]==date('Y') || $finn[1]==date('y'))
{
$data['month'] = "'".$month.'-'.date('y')."'";
}
else
{
$data['month'] = "'".$month.'-'.$finn[1]."'";
}
}
else
{$data['month'] = "'".$month.'-'.($finn[1]-1)."'";}

$dataA = $this->InitialInvoice->find('first',array('fields'=>array('total','cost_center','finance_year','month'),'conditions'=>array('id'=>$id)));

$dataY = $this->InitialInvoice->find('first',array('fields'=>array('app_tax_cal','total','bill_no'),'conditions'=>array('id' => $id)));

$tax_call = $dataY['InitialInvoice']['app_tax_cal'];
$krishi_tax = $data['apply_krishi_tax'];
$service_tax = $data['apply_service_tax'];
$data['apply_gst'] = "0";

if($dataY['InitialInvoice']['total'] != $data['total'])
{
$dataZ['initial_id'] = $id;
$dataZ['bill_no'] = $dataY['InitialInvoice']['bill_no'];
$dataZ['old_amount'] = $dataY['InitialInvoice']['total'];
$dataZ['new_amount'] = $data['total'];
$dataZ['createdate'] = date('Y-m-d H:i:s');
$this->EditAmount->save($dataZ);
}

if ($this->InitialInvoice->updateAll($data,array('id'=>$id)))
{
//print_r($data); exit;

$tax = 0;
$sbctax = 0;
$krishiTax = 0;
$igst = 0;
$sgst =0;
$cgst = 0;
$total =round($checkTotal,0); 
$apply_gst = "0";				
if($tax_call == '1')
{
if(strtotime($date) > strtotime("2017-06-30"))
{
$apply_gst = "1"; 

$apply_krishi_tax = "0";
if($GSTType=='Integrated')
{
$igst = round($checkTotal*0.18,0);
}
else 
{
echo  $sgst = $cgst = round($checkTotal*0.09,0);
}
}
else
{
$apply_gst = "0";

$krishi_tax = 1;
$tax = round($checkTotal*0.14,0);
$sbctax = 0;
if(strtotime($date) > strtotime("2015-11-14"))
{$sbctax = round($checkTotal*0.005,0); }

if($krishi_tax == "1")
{
$krishiTax = round($checkTotal*0.005,0);
$apply_krishi_tax  = "1";
}
}
}

if($service_tax=="1")
{
$total = "0"; 
}
$grnd = round($total + $tax + $sbctax+$krishiTax+$sgst+$cgst+$igst,0);

$total2 = 0;
if($dataA['InitialInvoice']['cost_center'] == str_replace("'", "", $data['cost_center']) && $dataA['InitialInvoice']['month'] == str_replace("'", "", $data['month']) && $dataA['InitialInvoice']['finance_year'] == str_replace("'", "", $data['finance_year']) )
{
$total2 = $dataA['InitialInvoice']['total'] -$total;
}
else
{
$total2 = $total - 2 *$total; 
$total3 = $dataA['InitialInvoice']['total'];
//$this->Provision->updateAll(array('provision_balance'=>"provision_balance+$total3"),array('cost_center'=>  $dataA['InitialInvoice']['cost_center'],'finance_year'=>$dataA['InitialInvoice']['finance_year'],'month'=>$dataA['InitialInvoice']['month']));
}

//$this->Provision->updateAll(array('provision_balance'=>"provision_balance+$total2"),array('cost_center'=>  str_replace("'", "", $data['cost_center']),'finance_year'=>str_replace("'", "", $data['finance_year']),'month'=>str_replace("'", "", $data['month'])));

$dataY = array('po_no'=>"'".$po_no."'",'total'=>$total,'tax'=>$tax,'sbctax'=>$sbctax,'grnd'=>$grnd,'igst'=>$igst,'sgst'=>$sgst,'cgst'=>$cgst,'krishi_tax'=>$krishiTax,'apply_krishi_tax'=>$apply_krishi_tax,'apply_gst'=>$apply_gst);

if($this->InitialInvoice->updateAll($dataY,array('id'=>$id)))
{
$Transaction->commit(); 
}

$this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice to branch '.$b_name.' for Amount '.$amount.' Updated Successfully.'."</b></h4>"));
$username=$this->Session->read("username");

App::uses('sendEmail', 'custom/Email');

$dataX = $this ->InitialInvoice-> find('first',array('fields' => array('bill_no','grnd','invoiceDescription'),'conditions'=>array('id' => $id)));

$msg = "Hi<br>".$b_name." branch Invoice No.".$dataX['InitialInvoice']['bill_no'].' has been Edited. '.date("F j, Y, g:i a");

$msg .= "<br><strong><b style=color:#FF0000>Kindly update your Records </b></strong>";

$emailid = $this ->User->find("all",array('fields' => array('email','id','branch_name'),'conditions' =>"(user_type IS NULL OR user_type!='Super-Admin') and work_type='account' and UserActive='1' and email!='' and (role='admin' or branch_name='$b_name')" ));

$nofifyArr = array(); $notifyLoop=0;

foreach($emailid as $email1):
$email2[] = trim($email1['User']['email']);
$nofifyArr[$notifyLoop]['userid'] = $email1['User']['id'];
$nofifyArr[$notifyLoop]['branch'] = $email1['User']['branch_name'];
$nofifyArr[$notifyLoop]['msg'] = addslashes($msg);
$nofifyArr[$notifyLoop++]['createdate'] = date('Y-m-d H:i:s');
endforeach;

//$this->NotificationMaster->saveAll($nofifyArr);

$sub = "Invoice Edited";
$mail = new sendEmail();
if(!empty($email2))
                {
                    try
                    {
                        $mail-> to($email2,$msg,$sub);		
                    }
                    catch(Exception $e)
                    {

                    }
                }				

if($role=='admin')
{return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view_invoice'));}
else
{return $this->redirect(array('controller'=>'InitialInvoices','action' => 'branch_view'));}

}
else
{
$Transaction->rollback(); 
$this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Updation Failed'."</b></h4>"));
return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
}
}

else
{
$Transaction->rollback(); 
$this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Provision  for Amount '.$amount.' is Less'."</b></h4>"));
return $this->redirect(array('controller'=>'InitialInvoices','action' => 'edit_invoice','?'=>array('id'=>$id)));
}

}
}

public function reject_invoice()
{
$id  = $this->request->query['id'];
$data = array('status' => '1');
if ($this->InitialInvoice->updateAll($data,array('id'=>$id)))
{
    $Initial = $this->InitialInvoice->find('first',array('conditions'=>array('id'=>$id)));
    $Provision = $this->Provision->updateAll(array('provision_balance'=>'provision_balance'+$Initial['InitialInvoice']['total']),
            array('branch_name'=>$Initial['InitialInvoice']['branch_name'],'finance_year'=>$Initial['InitialInvoice']['finance_year'],'month'=>$Initial['InitialInvoice']['month'],
                'cost_center'=>$Initial['InitialInvoice']['cost_center']));
        $this->Session->setFlash(__("<h4 class=bg-active align=center style=font-size:14px><b style=color:#FF0000>".'Invoice to Bill No. '.$id.' Rejected Successfully.'."</b></h4>"));
        return $this->redirect(array('controller'=>'InitialInvoices','action' => 'view_invoice'));
}
}

public function apply_tax_cal()
{ 
$username=$this->Session->read("username");
$this->layout="ajax";
$id = $this->params->query['id'];    
$apply = $this->params->query['apply'];
if($apply=="No")
{
//$this->InitialInvoice->find('first',array('fields'=>array('total'),'conditions'=>array('id'=>$id)));
$this->InitialInvoice->updateAll(array("sbctax"=>'0','apply_krishi_tax'=>'0','krishi_tax'=>'0',"app_tax_cal"=>"0",'grnd'=>'total'),array('id'=>$id));
}
else 
{
$Particular = $this->Particular->find('all',array('conditions'=>array('initial_id'=>$id),'fields'=>array('amount')));

$DeductParticular = $this->DeductParticular->find('all',array('conditions'=>array('initial_id'=>$id),'fields'=>array('amount')));
$total = 0;
foreach($Particular as $post):
    $total += $post['Particular']['amount'];
endforeach;
foreach($DeductParticular as $post):
    $total -= $post['DeductParticular']['amount'];
endforeach;  
$data = $this->InitialInvoice->find('first',array('fields'=>array('invoiceDate','finance_year','cost_center'),'conditions'=>array('id'=>$id)));
$sbctax = "0"; $krishiTax = "0";$apply_krishi_tax=0;$apply_gst =0;
if(strtotime($data['InitialInvoice']['invoiceDate']) > strtotime("2017-06-30"))
{
    $cost_center = $this->CostCenterMaster->find('first',array('conditions'=>array('cost_center'=>$data['InitialInvoice']['cost_center'])));
    $apply_gst = "1";

       $tax = 0;
        if($cost_center['CostCenterMaster']['GSTType']=='Integrated')
        {
            $igst = round($total*0.18,0);
        }
        else 
        {
            $sgst = $cgst = round($total*0.09,0);
        }
}
else
{
     $tax = $total*0.14; 
if(strtotime($data['InitialInvoice']['invoiceDate']) > strtotime("2015-11-14"))
{
   $sbctax = $total * 0.05;
}
if($data['InitialInvoice']['finance_year']=='2016-17')
{
   $krishiTax = $total * 0.05;
   $apply_krishi_tax=1;

}
}
$grnd = $total+$tax+$krishiTax+$sbctax+$igst+$sgst+$cgst;
 $this->InitialInvoice->updateAll(
 array("total"=>$total,'apply_gst'=>$apply_gst,'igst'=>$igst,'sgst'=>$sgst,'cgst'=>$cgst,"apply_krishi_tax"=>$apply_krishi_tax,"krishi_tax"=>$krishiTax,'sbctax'=>$sbctax,'app_tax_cal'=>'1','grnd'=>$grnd),array('id'=>$id));
}    
}


public function view_status_change_request()
{
$this->layout="home";
$this->set('branch_master', $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name'=>'asc'))));

$this->set('finance_year', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),
'conditions'=>array('active'=>'1','not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17','2017-18'))))));

$data = $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('RequestInvoiceType'=>null))));

$this->set('data',$data);

if($this->request->is('Post'))
{
if(!empty($this->request->data['View']))
{
    $branch_name = $this->request->data['InitialInvoice']['branch_name'];
    $year = $this->request->data['InitialInvoice']['year'];
    $data = $this->InitialInvoice->find('all',array('conditions'=>array('branch_name'=>$branch_name,'finance_year'=>$year,'not'=>array('RequestInvoiceType'=>null))));
    $this->set('data',$data);
}
else
{
   $id= $this->request->data['InitialInvoice']['id']; 
   $this->InitialInvoice->updateAll(array('CurrentInvoiceType'=>'RequestInvoiceType','RequestInvoiceType'=>null,'InvoiceTypeApproveBy'=>"'".$this->Session->read('userid')."'",
       'InvoiceTypeApproveDate'=>"'".date('Y-m-d')."'"),array('id'=>$id));
   $this->redirect(array('action'=>'view_status_change_request'));
    $this->Session->setFlash('Records Updated Successfully');
}
}   
}
public function delete_invoice()
{
//                    $this->layout="home";
//                    $this->set('branch_master', $this->Addbranch->find('list',array('fields'=>array('branch_name','branch_name'),'order'=>array('branch_name'=>'asc'))));
//
//                    $this->set('finance_year', $this->BillMaster->find('list',array('fields'=>array('finance_year','finance_year'),
//                        'conditions'=>array('active'=>'1','not'=>array('finance_year'=>array('14-15','2014-15','2015-16','2016-17','2017-18'))))));
//
//                    $data = $this->InitialInvoice->find('all',array('conditions'=>array('not'=>array('InvoiceRejectRequest'=>null))));
//                    if($this->request->is('Post'))
//                    {
//                        if(!empty($this->request->data['View']))
//                        {
//                            $branch_name = $this->request->data['InitialInvoice']['branch_name'];
//                            $year = $this->request->data['InitialInvoice']['year'];
//                            $data = $this->InitialInvoice->find('all',array('conditions'=>array('branch_name'=>$branch_name,'finance_year'=>$year,'not'=>array('InvoiceRejectRequest'=>null))));
//                            $this->set('data',$data);
//                        }
//                        else
//                        {
//                           $id= $this->request->data['InitialInvoice']['id']; 
//                           $this->InitialInvoice->updateAll(array('status'=>'1','InvoiceRejectBy'=>"'".$this->Session->read('userid')."'",
//                               'InvoiceRejectDate'=>"'".date('Y-m-d')."'"),array('id'=>$id));
//                           $this->redirect(array('action'=>'delete_invoice'));
//                            $this->Session->setFlash('Invoice Deleted Successfully');
//                        }
//                    }

}

}
?>