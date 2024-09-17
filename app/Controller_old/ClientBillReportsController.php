<?php
class ClientBillReportsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('RegistrationMaster','PlanMaster','Waiver','BalanceMaster','BillingMaster','BillMasterPost','BalanceMasterHistory');
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index','view_bill','view_invoice','view_padunpad_payment','view_prepaid_invoice');
    }
	
    public function index() {
        $this->layout='user';
        $ClientId = $this->Session->read('companyid');
        $result = $this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>$ClientId))); 
        $this->set('result',$result);
        $this->set('oldbill',$this->BalanceMasterHistory->find('all',array('fields'=>array('start_date','end_date'),'conditions'=>array('clientId'=>$ClientId),'Order'=>array('start_date'=>'Asc'))));
    }
    
    public function view_bill(){
        $this->layout = "user";;
        $ClientId = $this->Session->read('companyid');
        $result = $this->BillMasterPost->find('all',array('conditions'=>array('clientId'=>$ClientId))); 
        $this->set('result',$result);
        
    }
    
    public function view_invoice() {
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
    
    public function view_padunpad_payment(){
        $this->layout = "user";
        $ClientId = $this->Session->read('companyid');
        $data = $this->BillMasterPost->find('all',array('conditions'=>array('ClientId'=>$ClientId)));
        if(!empty($data)){
            $this->set('padunpad_payment',$data);
        }
        else{
            $this->set('padunpad_payment',array());
        }
    }
    
}

?>