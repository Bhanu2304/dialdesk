<?php
class BillingReportsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','PlanMaster','Waiver','BalanceMaster','BillingMaster',
            'BillMasterPost','BalanceMasterHistory','vicidialCloserLog');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
	$this->Auth->allow('get_stmt','index','get_today_consume_data','get_collection','billing_reports','get_billing_reports','view_bill','view_invoice','view_statement','update_client_plan','get_client_name','allocate_plan','getbillmonth','view_prepaid_invoice','get_oldbill_date','get_tagging_status');
	// if(!$this->Session->check("admin_id"))
    //     {
    //         return $this->redirect(array('controller'=>'Admins','action' => 'index'));
	// }
    }

    public function index()
    {
        $this->layout = "user";;
        $clientIds = array_values($this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('not'=>array('PlanId'=>null),'PlanType'=>'Prepaid'))));
        $this->set('data',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('status'=>'A','company_id'=>$clientIds),'order'=>array('Company_name'=>'asc'))));
    }
    public function view_bill()
    {
        $this->layout = "user";;
        $clientIds = array_values($this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('not'=>array('PlanId'=>null),'PlanType'=>'Postpaid'))));
        $this->set('data',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('company_id'=>$clientIds),'order'=>array('Company_name'=>'asc'))));
    }
    public function view_invoice() 
    {
        $clientId = $this->params->query['ClientId'];
        
        /*
        $BillDate = $this->params->query['BillMonth'];
        $exp      =  explode("_", $BillDate);
        $StartDate=$exp[0];
        $EndDate  =$exp[1];
        */
        
        $clientInfo = $this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>"$clientId")));
        $BalanceMaster = $this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>"$clientId")));
        //print_r($BalanceMaster); exit;
        
        
        
        $postBill = $this->BillMasterPost->query("SELECT Id,clientId,DATE_FORMAT(BillStartDate,'%d %b %Y') `BillStartDate`,DATE_FORMAT(BillEndDate,'%d %b %Y') `BillEndDate`, LastBilled, paymentDue,CurrentCharge,
AfterDueDate,DATE_FORMAT(DATE_ADD(BillEndDate,INTERVAL 15 DAY),'%d %b %Y') `DueDate`,paymentPaid,Adjustments,LastCarriedAmount,serviceTax,sbcTax,krishiTax
 FROM `post_bill_master` WHERE clientId='$clientId' ORDER BY id DESC LIMIT 1");
        
        
        /*
        $postBill = $this->BillMasterPost->query("SELECT Id,clientId,DATE_FORMAT(BillStartDate,'%d %b %Y') `BillStartDate`,DATE_FORMAT(BillEndDate,'%d %b %Y') `BillEndDate`, LastBilled, paymentDue,CurrentCharge,
AfterDueDate,DATE_FORMAT(DATE_ADD(BillEndDate,INTERVAL 15 DAY),'%d %b %Y') `DueDate`,paymentPaid,Adjustments,LastCarriedAmount,serviceTax,sbcTax,krishiTax
 FROM `post_bill_master` WHERE clientId='$clientId' AND DATE(BillStartDate)='$StartDate' AND DATE(BillEndDate)='$EndDate' ORDER BY id DESC LIMIT 1");
        */
        
        
        //print_r($postBill); exit;
       $this->set('ClientInfo',$clientInfo);
       $this->set('BalanceMaster',$BalanceMaster);
       $this->set('BillMaster',$postBill);
       $this->set('PlanDetails',$this->PlanMaster->find('first',array('conditions'=> array('Id'=>$BalanceMaster['BalanceMaster']['PlanId']))));
       $this->set('data',$this->BillingMaster->query("SELECT SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'"));
          
    }
    
    
    public function view_prepaid_invoice() {
        $clientId = $this->params->query['ClientId'];
        $clientInfo = $this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>"$clientId")));
        $BalanceMaster = $this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>"$clientId")));
  
        $this->set('ClientInfo',$clientInfo);
        $this->set('BalanceMaster',$BalanceMaster);
        $this->set('BillMaster',$postBill);
        $this->set('PlanDetails',$this->PlanMaster->find('first',array('conditions'=> array('Id'=>$BalanceMaster['BalanceMaster']['PlanId']))));
        $this->set('data',$this->BillingMaster->query("SELECT SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'"));     
    }
    
    
    public function view_statement() 
    {
        //print_r($this->request->data); exit;
        //$this->layout="user";
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M');
        $clientId = $this->params->query['ClientId'];
        $FromDate = date_format(date_create($this->params->query['FromDate']),'Y-m-d');
        $ToDate = date_format(date_create($this->params->query['ToDate']),'Y-m-d');
        
        $clientInfo = $this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>"$clientId")));
        $BalanceMaster = $this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>"$clientId")));
       $this->set('ClientInfo',$clientInfo);
       $this->set('BalanceMaster',$BalanceMaster);
       $this->set('PlanDetails',$this->PlanMaster->find('first',array('Id'=>$BalanceMaster['BalanceMaster']['PlanId'])));
       
       $this->set('data',$this->BillingMaster->query("SELECT SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId' and date(CallDate) between '$FromDate' AND '$ToDate'"));
       $this->set('Inbound',$this->BillingMaster->query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount)) FROM `billing_master` WHERE clientId='$clientId'
AND DedType='Inbound' AND date(CallDate) between '$FromDate' AND '$ToDate'"));
          $this->set('VFO',$this->BillingMaster->query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
AND DedType='VFO' AND date(CallDate) between '$FromDate' AND '$ToDate'"));
          $this->set('SMS',$this->BillingMaster->query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
AND DedType='SMS' AND date(CallDate) between '$FromDate' AND '$ToDate'"));
          $this->set('Email',$this->BillingMaster->query("SELECT SUM(IF(Unit IS NULL OR Unit='',0,Unit)) `Unit`,SUM(IF(Amount IS NULL OR Amount='',0,Amount))`Total` FROM `billing_master` WHERE clientId='$clientId'
AND DedType='Email' AND date(CallDate) between '$FromDate' AND '$ToDate'"));
          
       $this->set('InboundDetails',$this->BillingMaster->query("SELECT DATE_FORMAT(CallDate,'%d %b %Y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='Inbound' AND date(CallDate) between '$FromDate' AND '$ToDate';"));
       $this->set('SMSDetails',$this->BillingMaster->query("SELECT DATE_FORMAT(CallDate,'%d %b %Y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='SMS' AND date(CallDate) between '$FromDate' AND '$ToDate';"));
       $this->set('EmailDetails',$this->BillingMaster->query("SELECT DATE_FORMAT(CallDate,'%d %b %Y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='Email' AND date(CallDate) between '$FromDate' AND '$ToDate';"));
       $this->set('VFODetails',$this->BillingMaster->query("SELECT DATE_FORMAT(CallDate,'%d %b %Y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='VFO' AND date(CallDate) between '$FromDate' AND '$ToDate';"));
    }
	

    public function getbillmonth(){
        if(isset($_REQUEST['clientid'])){
            $result = $this->BillMasterPost->find('all',array('conditions'=>array('clientId'=>$_REQUEST['clientid']))); 
            //$result = $this->BillMasterPost->find('first',array('conditions'=>array('clientId'=>$_REQUEST['clientid']),'order'=>array('id'=>'DESC'))); 
            echo "<option value=''>Select Bill Month</option>";
            foreach($result as $val){
                $BillMonth = $val['BillMasterPost']['BillStartDate']." To ".$val['BillMasterPost']['BillEndDate'];
                $fd    = $val['BillMasterPost']['BillStartDate'];
                $ld    = $val['BillMasterPost']['BillEndDate'];
                echo "<option value='{$fd}_{$ld}'>$BillMonth</option>";
            }
            die;
        }
    }

    
    public function get_oldbill_date(){
        if(isset($_REQUEST['ClientId']) && $_REQUEST['ClientId'] !=""){
            $ClientId=$_REQUEST['ClientId'];
            $oldbill=$this->BalanceMasterHistory->find('all',array('fields'=>array('start_date','end_date'),'conditions'=>array('clientId'=>$ClientId),'Order'=>array('start_date'=>'Asc')));
            ?>
            <option value="">Select Date</option>
            <?php foreach ($oldbill as $value){?>
            <option value="<?php echo $value['BalanceMasterHistory']['start_date'].'__'.$value['BalanceMasterHistory']['end_date'];?>"><?php echo $value['BalanceMasterHistory']['start_date'].' To '.$value['BalanceMasterHistory']['end_date'];?></option>
            <?php }?>
            <?php
        }
        else{
           echo '<option value="">Select Date</option>';
        }
        die;
    }
    
    public function get_tagging_status(){
        $this->layout = "ajax";
        $result = $this->BalanceMaster->find('first',array('fields'=>array('CrmTagStatus'),'conditions'=>array('clientId'=>$_REQUEST['ClientId']))); 
        echo $result['BalanceMaster']['CrmTagStatus'];die;
    }
    
    public function billing_reports()
    {
        $this->layout = "user";;
        $clientIds = array_values($this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('not'=>array('PlanId'=>null),'PlanType'=>'Prepaid'))));
        $this->set('data',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('status'=>'A','company_id'=>$clientIds),'order'=>array('Company_name'=>'asc'))));
    }
    
    public function get_billing_reports()
    {
        $clientIdReq = $this->params->query['ClientId'];
        //$company_id_qr = " and company_id='278'";
        $company_id_qr = "";
        /*if($clientId!='All')
        {
            $company_id_qr = " and company_id='$clientId'";
        }*/
        
        $client_mas = $this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>"status='A' $company_id_qr ",'order'=>array('company_name'=>'asc')));
        
        $html = '<table border="2">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Sr.No.</th>';
        $html .= '<th>Client Name</th>';
        $html .= '<th>Opening Balance as on 1st of Current Month</th>';
        $html .= '<th>Collection MTD</th>';
        $html .= '<th>Consume Value MTD</th>';
        $html .= '<th>Closing Balance as on Date.</th>';
        //$html .= '<th>MIN. IB</th>';
        //$html .= '<th>MIN. OB</th>';
        //$html .= '<th>SMS Qty.</th>';
        //$html .= '<th>Email Qty.</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        
        $html .= '</tbody>';
        $i = 1;
        
        $total_op_bal = 0;
        $total_cs_bal = 0;
        $total_collection = 0;
        $total_ib_minute = 0;
        $total_ob_minute = 0;
        $total_sms_minute = 0;
        $total_email_minute = 0;
        
        foreach($client_mas as $clientId=>$clientName)
        {
            
            
            $BalanceMaster_det = $this->RegistrationMaster->query("select * from billing_opening_balance where clientId='$clientId'  limit 1");
            $op_bal = round($BalanceMaster_det['0']['billing_opening_balance']['op_bal']);
            $cs_bal = round($BalanceMaster_det['0']['billing_opening_balance']['cs_bal']);
            $today_cs = $this->get_today_consume_data($clientId);
            $coll_bal = $this->get_collection($clientId);
            //print_r($today_cs);exit;
            
            if(!empty($today_cs))
            {
                if(!empty($today_cs['total']))
                {
                    $cs_bal = $cs_bal+(int)$today_cs['total'];
                }
            }
            
			$closing_bal = round($op_bal+$coll_bal - $cs_bal);
			
			if($clientIdReq=='All' || $closing_bal<0)
			{
            $html .= '<tr>';
            $html .= '<td>'.$i++.'</td>';
            $html .= '<td>'.$clientName.'</td>';
            $html .= '<td>'.$op_bal.'</td>';
            $html .= '<td>'.$coll_bal.'</td>';
            $html .= '<td>'.$cs_bal.'</td>';
            $html .= '<td>'.$closing_bal.'</td>';
            
            /*$pulse_det_arr = $this->RegistrationMaster->query("SELECT * FROM `billing_consume_daily` bcd WHERE client_Id='$clientId' and DATE_FORMAT(CURDATE(),'%m-%Y') = DATE_FORMAT(cm_date,'%m-%Y')");
            $ib_minute = 0;
            $ob_minute = 0;
            $sms_minute = 0;
            $email_minute = 0;
            foreach($pulse_det_arr as $pulse_det)    
            {
                $ib_pulse = (int) $pulse_det['bcd']['ib_pulse'];
                $ib_sec = (int) $pulse_det['bcd']['ib_secs'];
                $ibn_pulse = (int) $pulse_det['bcd']['ibn_pulse'];
                $ibn_secs = (int) $pulse_det['bcd']['ibn_secs'];
                $ob_pulse = (int) $pulse_det['bcd']['ob_pulse'];
                $ob_secs = (int) $pulse_det['bcd']['ob_secs'];
                
                $ib_minute +=$ib_pulse;
                $ib_minute += round($ib_sec/60);
                $ib_minute +=$ibn_pulse;
                $ib_minute +=round($ibn_secs/60);
                
                $ob_minute += $ob_pulse;
                $ob_minute += round($ob_secs/60);
                
                $sms_minute += $pulse_det['bcd']['sms_pulse'];
                $email_minute += $pulse_det['bcd']['email_pulse'];
            }*/ 
            
            //$ib_minute =$ib_minute+round((int)$today_cs['ib']);
            //$ob_minute =$ob_minute+round((int)$today_cs['ob']);
            //$sms_minute =$sms_minute+round((int)$today_cs['sms']);
            //$email_minute =$email_minute+round((int)$today_cs['email']);
            
         //   $html .= '<td>'.$ib_minute.'</td>';
         //   $html .= '<td>'.$ob_minute.'</td>';
         //   $html .= '<td>'.$sms_minute.'</td>';
         //   $html .= '<td>'.$email_minute.'</td>';
            $html .= '</tr>';   
            
            
            $total_op_bal += $op_bal;
            $total_cs_bal += $cs_bal;
            $total_collection += $coll_bal;
            $total_ib_minute += $ib_minute;
            $total_ob_minute += $ob_minute;
            $total_sms_minute += $sms_minute;
            $total_email_minute += $email_minute;
            }
            
        }
        
        
        
         $html .= '<tr>';
            $html .= '<th colspan="2" align="right">Total</th>';
            $html .= '<th>'.$total_op_bal.'</th>';
            $html .= '<th>'.$total_collection.'</th>';
            $html .= '<th>'.$total_cs_bal.'</th>';
            $html .= '<th>'.round($total_op_bal - $total_cs_bal).'</th>';  
           // $html .= '<th>'.$total_ib_minute.'</th>';
           // $html .= '<th>'.$total_ob_minute.'</th>';
           // $html .= '<th>'.$total_sms_minute.'</th>';
           // $html .= '<th>'.$total_email_minute.'</th>';
        $html .= '</tr>'; 
        $html .= '</tbody>';
        $html .= '</table>';
        
        $fileName = "closing_balance_reports".date('d_m_y_h_i_s');
header("Content-Type: application/vnd.ms-excel; name='excel'");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$fileName.".xls");
header("Pragma: no-cache");
header("Expires: 0");

        
        echo $html;exit;
    }
    
    public function get_today_consume_data($clientId)
        {
            //$clientId = $this->Session->read('companyid');   
            $last_date = date('Y-m-d');
            $bal_qry = "select * from `balance_master` where clientId='$clientId'  limit 1";
            //$BalanceMasterRsc = mysqli_query($dd,$bal_qry);
            $BalanceMasterRsc =$this->RegistrationMaster->query($bal_qry);
            $BalanceMaster = $BalanceMasterRsc['0']['balance_master'];
            //print_r($BalanceMaster);exit;


            $cm_total = 0;
            $ib_pulse = 0;      $ib_secs=0;         $ib_charge = 0;     $ib_flat = 0;       $ib_total = 0;      $ib_pulse_per_call = 60;
            $ibn_pulse = 0;     $ibn_secs=0;         $ibn_charge = 0;    $ibn_flat = 0;      $ibn_total = 0;     $ibn_pulse_per_call = 60;
            $ob_pulse = 0;      $ob_secs=0;         $ob_charge = 0;     $ob_flat = 0;       $ob_total = 0;      $ob_pulse_per_call = 60;
            $ivr_pulse = 0;     $ivr_secs=0;         $ivr_charge = 0;    $ivr_flat = 0;      $ivr_total = 0;     //$ivr_pulse_per_call = 60;
            $miss_pulse = 0;    $miss_secs=0;         $miss_charge = 0;   $miss_flat = 0;     $miss_total = 0;    //$miss_pulse_per_call = 60;
            $vfo_pulse = 0;     $vfo_secs=0;         $vfo_charge = 0;    $vfo_flat = 0;      $vfo_total = 0;     //$vfo_pulse_per_call = 60;
            $sms_pulse = 0;     $sms_secs=0;         $sms_charge = 0;    $sms_flat = 0;      $sms_total = 0;     $sms_pulse_per_call = 60;
            $email_pulse = 0;   $email_secs=0;         $email_charge = 0;  $email_flat = 0;    $email_total = 0;   //$email_pulse_per_call = 60;




    if($BalanceMaster['PlanId'] !=""){
    $ClientInfo = $this->RegistrationMaster->find('first',array("conditions"=>"company_id='$clientId'"));

    $Campagn=$ClientInfo['RegistrationMaster']['campaignid']; 
    $PlanId = $BalanceMaster['PlanId'];
    $plan_det_qry = "select * from `plan_master` where Id='$PlanId' limit 1";
    $PlanDetailsRsc = $this->RegistrationMaster->query($plan_det_qry);
    $PlanDetails = $PlanDetailsRsc['0']['plan_master'];




    $ib_charge = $PlanDetails['InboundCallCharge'];
    if($PlanDetails['IB_Call_Charge']=='Minute')
    {
        $ib_flat = 0;
    }
    else if($PlanDetails['IB_Call_Charge']=='Second')
    {
        $ib_flat = 1;
    }
    else if($PlanDetails['IB_Call_Charge']=='Minute/Second')
    {
        $ib_flat = 2;
    }

    $ibn_charge = $PlanDetails['InboundCallChargeNight'];    
    if($PlanDetails['IB_Call_Charge']=='Minute')
    {
        $ibn_flat = 0;
    }
    else if($PlanDetails['IB_Call_Charge']=='Second')
    {
        $ibn_flat = 1;
    }
    else if($PlanDetails['IB_Call_Charge']=='Minute/Second')
    {
        $ibn_flat = 2;
    }

    $ob_charge = $PlanDetails['OutboundCallCharge'];
    if($PlanDetails['OB_Call_Charge']=='Minute')
    {
        $ob_flat = 0;
    }
    else if($PlanDetails['OB_Call_Charge']=='Second')
    {
        $ob_flat = 1;
    }
    else if($PlanDetails['OB_Call_Charge']=='Minute/Second')
    {
        $ob_flat = 2;
    }

    $ivr_charge = $PlanDetails['IVR_Charge'];    
    $ivr_flat = 0;
    $miss_charge = $PlanDetails['MissCallCharge'];   
    $miss_flat = 0;
    $vfo_charge = $PlanDetails['VFOCallCharge'];    
    $vfo_flat = 0;
    $sms_charge = $PlanDetails['SMSCharge'];    
    $sms_flat = 0;
    $email_charge = $PlanDetails['EmailCharge'];  
    $email_flat = 0;

    $IvrQry = "SELECT DATE_FORMAT(call_time,'%d %b %y') `CallDate1`,call_time CallDate,source_number CallFrom,uniqueid FROM `rx_log` WHERE clientId='$clientId'  AND date(call_time) = '$last_date'";
    $IvrDetails = $this->RegistrationMaster->query($IvrQry);


    $SMSQuery = "SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='SMS' AND date(CallDate) = '$last_date'  ";
    $SMSDetails = $this->RegistrationMaster->query($SMSQuery);

    $EmailQry = "SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate1`,CallDate,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='Email' AND date(CallDate) = '$last_date'";
    $EmailDetails = $this->RegistrationMaster->query($EmailQry);

    //$MissQry = "SELECT DATE_FORMAT(call_time,'%d %b %y') `CallDate1`,call_time CallDate,source_number CallFrom FROM `billing_master` WHERE clientId='$clientId'  AND date(CallDate) = '$last_date'";
    //$MissDetails = $this->RegistrationMaster->query($MissQry);

    // Inbound Call duration details
    $this->vicidialCloserLog->useDbConfig = 'db2';
    $InboundQry = "select length_in_sec,queue_seconds,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_closer_log` where user !='VDCL' and campaign_id in ($Campagn) AND DATE(call_date) = '$last_date'  ";
    $InboundDetails=$this->vicidialCloserLog->query($InboundQry);

    $OutboundQry = "select length_in_sec,if(LENGTH(phone_number)>'12',LEFT(phone_number,10),RIGHT(phone_number,10)) phone_number,call_date from `vicidial_log` where user !='VDAD' and campaign_id in ($Campagn) AND DATE(call_date) = '$last_date'  ";
    $OutboundDetails=$this->vicidialCloserLog->query($OutboundQry);

    //$VFOQry = "SELECT DATE_FORMAT(calltime,'%d %b %y') `CallDate1`,calltime CallDate,source_number, uniqueid FROM sbarro_data WHERE clientId='$clientId'  AND DATE(calltime) = '$last_date'";
    //$VfoDetails = $this->vicidialCloserLog->query($VFOQry);






    foreach($InboundDetails as $key=>$inbDurArrRsc)
    {
        //print_r($inbDurArrRsc);exit; 
        $inbDurArr = $inbDurArrRsc['vicidial_closer_log'];
        $call_duration = $inbDurArr['length_in_sec']-$inbDurArr['queue_seconds'];
        $call_pulse = 0;
        $call_rate = 0;
        $call_pulsesec = 0;

        if(strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))>=strtotime('20:00:00') 
                        || strtotime(date('H:i:s',strtotime($inbDurArr['call_date'])))<=strtotime('08:00:00'))
        {
            if($ibn_flat==0)
            {
                $call_pulse = ceil($call_duration/$ibn_pulse_per_call);
                $call_rate = $call_pulse*$ibn_charge;
            }
            else if($ibn_flat==1)
            {
                $call_pulsesec = $call_duration;
                $call_rate = $call_pulsesec*$ibn_charge;
            }
            else
            {
                $call_pulse = 1;
                $call_rate = $ibn_charge;
                if($call_duration>$ibn_pulse_per_call)
                {
                    $call_duration=($call_duration-$ibn_pulse_per_call);
                    $call_rate += ($call_duration*($ibn_charge/$ibn_pulse_per_call));
                    $call_pulsesec = $call_duration;
                }
            }

            $ibn_pulse += $call_pulse;
            $ibn_secs += $call_pulsesec;
            $ibn_total += $call_rate;
        }
        else
        {
            if($ib_flat==0)
            {
                $call_pulse = ceil($call_duration/$ib_pulse_per_call);
                $call_rate = $call_pulse*$ib_charge;
            }
            else if($ib_flat==1)
            {
                $call_pulsesec = $call_duration;
                $call_rate = $call_pulsesec*$ib_charge;
            }
            else
            {
                $call_pulse = 1;
                $call_rate = $ib_charge;
                if($call_duration>$ib_pulse_per_call)
                {
                    $call_duration=($call_duration-$ib_pulse_per_call);
                    $call_rate += ($call_duration*($ib_charge/$ib_pulse_per_call));
                    $call_pulsesec = $call_duration;
                }
            }

            $ib_pulse += $call_pulse;
            $ib_secs += $call_pulsesec;
            $ib_total += $call_rate;
        }

    }


    foreach($OutboundDetails as $key=>$outDurArrRsc)        
    {
        $outDurArr = $outDurArrRsc['vicidial_log'];
        $call_duration = $outDurArr['length_in_sec'];
        $call_pulse = 0;
        $call_rate = 0;
        $call_pulsesec = 0;

        if($ob_flat==0)
        {
            $call_pulse = ceil($call_duration/$ob_pulse_per_call);
            $call_rate = $call_pulse*$ob_charge;
        }
        else if($ob_flat==1)
        {
            $call_pulsesec = $call_duration;
            $call_rate = $call_pulsesec*$ob_charge;
        }
        else
        {
            $call_pulse = 1;
            $call_rate = $ob_charge;
            if($call_duration>$ob_pulse_per_call)
            {
                $call_duration=($call_duration-$ob_pulse_per_call);
                $call_rate += ($call_duration*($ob_charge/$ob_pulse_per_call));
                $call_pulsesec = $call_duration;
            }
        }

        $ob_pulse += $call_pulse;
        $ob_secs += $call_pulsesec;
        $ob_total += $call_rate;
    }

    $ivr_uniqueid_arr = array();


    foreach($IvrDetails as $key=>$ivrArrRsc)        
    {
        $ivrArr =$ivrArrRsc['rx_log'];
        $uniqueid = $ivrArr['uniqueid'];
        $ivr_uniqueid_arr[$uniqueid] = $uniqueid;
    }
    $ivr_unit = count($ivr_uniqueid_arr); 
    $ivr_rate = $ivr_unit*$ivr_charge;
    $ivr_total = $ivr_rate;
    $ivr_pulse = $ivr_unit;


    foreach($SMSDetails as $key=>$smsArrRsc)        
    {
        $smsArr = $smsArrRsc['billing_master'];
        $smsChar = $smsArr['Duration'];

        $sms_unit =$smsArr['Unit'];;
        $sms_rate = 0;
        $sms_pulsesec = 0;

    //        if($sms_flat==0)
    //        {
    //            $sms_unit = ceil($smsChar/60);
    //            $sms_rate = ceil($sms_unit*$sms_charge);
    //        }
    //        else if($sms_flat==1)
    //        {
    //            $sms_pulsesec = $smsChar;
    //            $sms_rate = $sms_pulsesec*$sms_charge;
    //        }
    //        else
    //        {
    //            $sms_unit = 1;
    //            $sms_rate = $sms_charge;
    //            if($smsChar>60)
    //            {
    //                $smsChar=($smsChar-60);
    //                $sms_rate += ($smsChar*($sms_charge/60));
    //                $sms_pulsesec = $smsChar;
    //            }
    //        }

        $sms_pulse += $sms_unit;
        $sms_secs += $smsChar;
        $sms_total += $sms_charge*$sms_unit;
    }

    $EmailUnit = 0;

    foreach($EmailDetails as $key=>$emailRsc)        
    {
        $emailArr = $emailRsc['billing_master'];
        $EmailUnit = $emailArr['Unit'];
        $email_rate = $EmailUnit*$email_charge;
        $email_pulse    += $EmailUnit;
        $email_total      += $email_rate;
    }


    foreach($MissDetails as $key=>$misArr)        
    {
        $MissUnit = $misArr['Unit'];
        $email_rate = ceil($MissUnit*$miss_charge);
        $miss_pulse    += $MissUnit;
        $miss_total      += $email_rate;
    }
    $ib = round($ib_pulse+$ibn_pulse+($ib_secs/60)+($ibn_secs/60));
    $ob = round($ob_pulse+($ob_secs/60));
    $cm_total = round($ib_total+$ibn_total+$ob_total+$ivr_total+$miss_total+$vfo_total+$sms_total+$email_total,3);
    $return =  array('total'=>$cm_total,'ib'=>$ib,'ob'=>$ob,'sms'=>$sms_pulse,'email'=>$email_pulse,'ivr'=>$ivr_pulse,'miss'=>$miss_pulse,'vfo'=>$vfo_pulse);
    //print_r($return);exit;
    return $return;
    }



            return array('total'=>0,'ib'=>0,'ob'=>0,'sms'=>0,'email'=>0,'ivr'=>0,'miss'=>0,'vfo'=>0); exit;
    }
    
    public function get_collection($clientId)
    {
            $CollectionMaster_det = $this->RegistrationMaster->query("select * from billing_collection where client_id='$clientId' and DATE_FORMAT(created_at,'%m-%Y') = DATE_FORMAT(CURDATE(),'%m-%Y') limit 1");
            $coll_bal = 0;
            foreach($CollectionMaster_det as $bal)
            {
                $coll_bal +=round($bal['billing_collection']['coll_bal']);
            }
            return $coll_bal;

    }
    
    public function get_stmt()
    {
        $this->layout = "user";
        if($this->Session->read('role') =="admin"){
            $clientIds = array_values($this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('not'=>array('PlanId'=>null),'PlanType'=>'Prepaid'))));
            $data_arr = $this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('status'=>'A','company_id'=>$clientIds),'order'=>array('Company_name'=>'asc')));
            $this->set('data',$data_arr);
        }else{
            $clientIds   = $this->Session->read('companyid');
            #$clientIds = array_values($this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('not'=>array('PlanId'=>null),'PlanType'=>'Prepaid'))));
            $data_arr = $this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('status'=>'A','company_id'=>$clientIds),'order'=>array('Company_name'=>'asc')));
            $this->set('data',$data_arr);
        }
        
        
    }
}
?>