<?php
class BillingReportsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','PlanMaster','Waiver','BalanceMaster','BillingMaster','BillMasterPost','BalanceMasterHistory');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
	$this->Auth->allow('index','view_bill','view_invoice','view_statement','update_client_plan','get_client_name','allocate_plan','getbillmonth','view_prepaid_invoice','get_oldbill_date','get_tagging_status');
	if(!$this->Session->check("admin_id"))
        {
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
	}
    }

    public function index()
    {
        $this->layout = "user";;
        $clientIds = array_values($this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('not'=>array('PlanId'=>null),'PlanType'=>'Prepaid'))));
        $this->set('data',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('status'=>'A','company_id'=>$clientIds))));
    }
    public function view_bill()
    {
        $this->layout = "user";;
        $clientIds = array_values($this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('not'=>array('PlanId'=>null),'PlanType'=>'Postpaid'))));
        $this->set('data',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('company_id'=>$clientIds))));
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
    
    
		
}
?>