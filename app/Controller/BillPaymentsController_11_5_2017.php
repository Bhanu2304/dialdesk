<?php
class BillPaymentsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','PlanMaster','PaymentMaster','Admin','Waiver','BalanceMaster','BillingMaster','BillMasterPost');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
	$this->Auth->allow('index','getplan','getplantype','getbillno','getbillplan','checkpayment','get_plan_name','get_company_name','payment_submition','payment_details','payment_approval','payment_approved','updatepayment','view_padunpad_payment');
	if(!$this->Session->check("admin_id"))
        {
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
	}
    }

    public function index(){
        $this->layout = "user";
        $this->set('PlanName',$this->PlanMaster->find('list',array('fields'=>array('Id','PlanName'),'Order'=>array('PlanName'=>'Asc'))));
        $this->set('ClientName',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'Order'=>array('company_name'=>'Asc'))));
        $bank=array(
            "Allahabad Bank"=>"Allahabad Bank",
            "Andhra Bank"=>"Andhra Bank",
            "Axis Bank"=>"Axis Bank",
            "Bank of Baroda"=>"Bank of Baroda",
            "Bank of Maharashtra"=>"Bank of Maharashtra",
            "Canara Bank"=>"Canara Bank",
            "Central Bank of India"=>"Central Bank of India",
            "Corporation Bank"=>"Corporation Bank",
            "City Union Bank"=>"City Union Bank",
            "Dena Bank"=>"Dena Bank",
            "Federal Bank"=>"Federal Bank",
            "HDFC Bank"=>"HDFC Bank",
            "Indian Bank"=>"Indian Bank",
            "IndusInd Bank"=>"IndusInd Bank",
            "ICICI Bank"=>"ICICI Bank",
            "IDFC Bank"=>"IDFC Bank",
            "Jammu and Kashmir Bank"=>"Jammu and Kashmir Bank",
            "Karnataka Bank"=>"Karnataka Bank",
            "Karur Vysya Bank"=>"Karur Vysya Bank",
            "Kotak Mahindra Bank"=>"Kotak Mahindra Bank",
            "Lakshmi Vilas Bank"=>"Lakshmi Vilas Bank",
            "Nainital Bank"=>"Nainital Bank",
            "Oriental Bank of Commerce"=>"Oriental Bank of Commerce",
            "Punjab National Bank"=>"Punjab National Bank",
            "RBL Bank"=>"RBL Bank",
            "State Bank of India"=>"State Bank of India",
            "South Indian Bank"=>"South Indian Bank",
            "Union Bank of India"=>"Union Bank of India",
            "Vijaya Bank"=>"Vijaya Bank",
            "Yes Bank"=>"Yes Bank"
        );
        
        $this->set('bank',$bank);
        
        
        
        if(isset($_REQUEST['cid']) && isset($_REQUEST['BillNo']) && $_REQUEST['cid'] !="" && $_REQUEST['BillNo'] !=""){
            $exp=  explode("_", $_REQUEST['BillNo']);
            $result = $this->PaymentMaster->find('first',array('conditions'=>array('PaymentStatus'=>0,'ClientId'=>$_REQUEST['cid'],'BillNo'=>$exp[0]))); 
            if(!empty($result)){
                $this->set('data',$result['PaymentMaster']);
            }
            else{
                
                $this->set('data',array());
            }
            
            $BillArr = $this->BillMasterPost->find('all',array('conditions'=>array('clientId'=>$_REQUEST['cid'],'paymentStatus'=>0)));
            $ArrList=array();
            foreach($BillArr as $val){
                $BillMonth = $val['BillMasterPost']['BillStartDate']." To ".$val['BillMasterPost']['BillEndDate'];
                $BillId    = $val['BillMasterPost']['Id']; 
                $ArrList[$BillId."_".$BillMonth]=$BillMonth;
            }
      
            $this->set('BillList',$ArrList);
            $this->set('BillNo',$_REQUEST['BillNo']);
            $this->set('BillClient',$_REQUEST['cid']);
        }
    }
    
    public function getplan(){
        if(isset($_REQUEST['ClientId']) && isset($_REQUEST['PlanId'])){
            $result = $this->BalanceMaster->find('first',array('conditions'=>array('ClientId'=>$_REQUEST['ClientId'],'PlanId'=>$_REQUEST['PlanId'])));    
            if(!empty($result)){
                echo "1";
            }
            else{
                echo "";
            }  
        }
        die;
    }
    
    public function getplantype(){
        if(isset($_REQUEST['ClientId']) && isset($_REQUEST['PlanId']) && isset($_REQUEST['PlanType']) ){
            $result = $this->BalanceMaster->find('first',array('conditions'=>array('ClientId'=>$_REQUEST['ClientId'],'PlanId'=>$_REQUEST['PlanId'],'PlanType'=>$_REQUEST['PlanType'])));    
            if(!empty($result)){
                echo "1";
            }
            else{
                echo "";
            }  
        }
        die;
    }
    
    public function checkpayment(){
        if(isset($_REQUEST['billMonth']) && isset($_REQUEST['billYear']) && isset($_REQUEST['clientid']) ){
            $exp   = explode("_", $_REQUEST['billMonth']);
            $month = $exp[0];
            $result = $this->BillMasterPost->find('first',array('conditions'=>array('clientId'=>$_REQUEST['clientid'],'paymentStatus'=>1,'MONTH(BillStartDate)'=>$month,'YEAR(BillStartDate)'=>$_REQUEST['billYear']))); 
            $resul2 = $this->BillMasterPost->find('first',array('conditions'=>array('clientId'=>$_REQUEST['clientid'],'paymentStatus'=>0,'MONTH(BillStartDate)'=>$month,'YEAR(BillStartDate)'=>$_REQUEST['billYear'])));
            if(!empty($result)){
                echo "1";die;
            }
            else if(empty($resul2)){
                echo "2";die;
            }
            else{
                echo "3";die;
            }  
        }
    }
    
    public function getbillno(){
        if(isset($_REQUEST['clientid'])){
             
            $result = $this->BillMasterPost->find('all',array('conditions'=>array('clientId'=>$_REQUEST['clientid'],'paymentStatus'=>0))); 
            //$result = $this->BillMasterPost->find('all',array('conditions'=>array('clientId'=>$_REQUEST['clientid'],'paymentStatus'=>0),'order' => array('Id DESC'),'limit' =>'1'));
            echo "<option value=''>Select Bill Month</option>";
            foreach($result as $val){
                $BillMonth = $val['BillMasterPost']['BillStartDate']." To ".$val['BillMasterPost']['BillEndDate'];
                $BillId    = $val['BillMasterPost']['Id'];
                echo "<option value='{$BillId}_{$BillMonth}'>$BillMonth</option>";
            }
            die;
        }
    }

    public function payment_submition(){  
        if($this->request->is('Post')){
            $data        = $this->request->data['BillPayments'];
            $name        = $this->Session->read('admin_name');
            $email       = $this->Session->read('admin_email');
            $CompanyName = $this->get_company_name($data['ClientId']);
            $PlanName    = $this->get_plan_name($data['PlanId']);
            $Amount      = $data['PayAmount'];
            
            $exp=  explode("_",$data['BillNo']);
            
            $data['CreatedId']=$this->Session->read('admin_id');
            $data['CreatedEmail']=$email;
            $data['CompanyName']=$CompanyName;
            $data['PlanName']=$PlanName;
            $data['BillNo']=$exp[0];
            $data['BillMonth']=$exp[1];

            $result = $this->PaymentMaster->find('first',array('conditions'=>array('PaymentStatus'=>0,'Id'=>$data['PaymentId'])));
            
            if(!empty($result)){
                if($data['PaymentType'] =="Cheque"){
                    $dataArr=array(
                        'BankName'=>"'".$data['BankName']."'",
                        'AccountNo'=>"'".$data['AccountNo']."'",
                        'ChequeNo'=>"'".$data['ChequeNo']."'",
                        'PayName'=>"'".$data['PayName']."'",
                        'PayAmount'=>"'".$data['PayAmount']."'",
                        'PayDate'=>"'".$data['PayDate']."'"
                    );
                }
                else{
                    $dataArr=array(
                        'TransactionNo'=>"'".$data['TransactionNo']."'",
                        'PayAmount'=>"'".$data['PayAmount']."'",
                        'PayDate'=>"'".$data['PayDate']."'"
                    );
                }
            
            $this->PaymentMaster->updateAll($dataArr,array('Id'=>$data['PaymentId']));
            }
            else{
                $this->PaymentMaster->save($data);
            }
            
            require_once(APP . 'Lib' . DS . 'send_email' . DS . 'function.php');
            $superAdmint = $this->Admin->find('first',array('conditions'=>array('id'=>3)));
            $EmailText='';
            $to=array('Email'=>$superAdmint['Admin']['Email'],'Name'=>$superAdmint['Admin']['UserName']);	
            $from=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
            $reply=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
            $Sub="Dialdesk: Payment Approval";
            $EmailText.="Dear sir,<br/><br/>";
            $EmailText.="Please check and approve this month payment.<br/>Client : $CompanyName , Plan : $PlanName , Amount : $Amount.<br/><br/>";
           
            $emaildata=array('ReceiverEmail'=>$to,'SenderEmail'=>$from,'ReplyEmail'=>$reply,'Subject'=>$Sub,'EmailText'=>$EmailText);
            send_email($emaildata);
               
            $this->Session->setFlash('Your payment submit successfully and this will be update after approval.');
            $this->redirect(array('controller' => 'BillPayments', 'action' => 'index'));	
        } 
    }
    
    public function payment_approval(){
        $this->layout = "user";
        $result = $this->PaymentMaster->find('all',array('conditions'=>array('PaymentStatus'=>0))); 
        $this->set('result',$result);
    }
    
    public function payment_details(){
        $this->layout = "user";
        if(isset($_REQUEST['id'])){
            $result = $this->PaymentMaster->find('first',array('conditions'=>array('PaymentStatus'=>0,'Id'=>$_REQUEST['id']))); 
            $this->set('data',$result['PaymentMaster']);
        }
    }
    
    public function payment_approved(){  
        if($this->request->is('Post')){
            $data=$this->request->data;
            
            $Month       = $data['Month'];
            $CompanyName = $data['CompanyName'];
            $Remarks     = $data['remarks'];
            
            require_once(APP . 'Lib' . DS . 'send_email' . DS . 'function.php');
            $EmailText='';
            $to=array('Email'=>$data['Email'],'Name'=>'Admin');	
            $from=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
            $reply=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
            $Sub="Dialdesk: Client Payment Approval";
            $EmailText.="Dear,<br/><br/>";
               
            if($data['PaymentStatus'] ==1){
                $dataArr=array(
                    'PaymentStatus'=>"'".$data['PaymentStatus']."'",
                    'remarks'=>"'".$Remarks."'",
                    'ApproveDate'=>"'".date("Y-m-d H:i:s")."'"
                );
                $EmailText.="Your client $CompanyName of $Month Bill Payment is Approved.<br/><br/>"; 
                $EmailText.="Remarks : $Remarks.<br/><br/>"; 
                $this->PaymentMaster->updateAll($dataArr,array('Id'=>$data['Id']));
                $this->updatepayment($data['Id']);
            }
            else{
                $dataArr=array('remarks'=>"'".$Remarks."'");
                $EmailText.="Your client $CompanyName of $Month Bill Payment Not Approve.<br/><br/>"; 
                $EmailText.="Remarks : $Remarks.<br/><br/>"; 
                $EmailText.="Please check and update payment again.<br/><br/>";
                $this->PaymentMaster->updateAll($dataArr,array('Id'=>$data['Id']));
            }

            $emaildata=array('ReceiverEmail'=>$to,'SenderEmail'=>$from,'ReplyEmail'=>$reply,'Subject'=>$Sub,'EmailText'=>$EmailText);
            send_email($emaildata);
            
            $this->Session->setFlash('Thank you update successfully.');
            $this->redirect(array('controller' => 'BillPayments', 'action' => 'payment_approval')); 
        } 
    }
    
    public function get_plan_name($id){
        $result = $this->PlanMaster->find('first',array('fields'=>array('PlanName'),'conditions'=>array('Id'=>$id)));
        return $result['PlanMaster']['PlanName'];
    }
    
    public function get_company_name($id){
        $result = $this->RegistrationMaster->find('first',array('fields'=>array('company_name'),'conditions'=>array('company_id'=>$id)));
        return $result['RegistrationMaster']['company_name'];
    }
    
    public function updatepayment($id){
        $arr = $this->PaymentMaster->find('first',array('conditions'=>array('PaymentStatus'=>1,'Id'=>$id)));
        $data=$arr['PaymentMaster'];
        $clientid = $data['ClientId'];
        $planid   = $data['PlanId'];
        $payamount= $data['PayAmount'];
        $paystatus=1;
        
        $condition1=array(
            'Id'=>$data['BillNo'],
            'clientId'=>$data['ClientId'],
            'PlanId'=>$data['PlanId'],
            'paymentStatus'=>0
        );
       
        $dataArr=array(
            'paymentStatus'=>"'".$paystatus."'",
            'paymentPaid'=>"'".$data['PayAmount']."'",
            'paymentDate'=>"'".$data['PayDate']."'"
        );
             
        $this->BillMasterPost->updateAll($dataArr,$condition1);
        
        // update due payment
        $dueArr=$this->BillMasterPost->find('all',array('conditions'=>array('PaymentStatus'=>0,'clientId'=>$clientid,'PlanId'=>$planid)));
        
        foreach($dueArr as $val){
            $paymentDue = $val['BillMasterPost']['paymentDue'];
            $afterDue   = $val['BillMasterPost']['AfterDueDate'];
            $lastCarred = $val['BillMasterPost']['LastCarriedAmount'];
            
            $updatePaymentDue = $paymentDue-$payamount;
            $updateafterDue   = $afterDue-$payamount;
            $updatelastCarred = $lastCarred-$payamount;
             
            $dataArr2=array(
                'paymentDue'=>"'".$updatePaymentDue."'",
                'AfterDueDate'=>"'".$updateafterDue."'",
                'LastCarriedAmount'=>"'".$updatelastCarred."'"
            );
            
            $this->BillMasterPost->updateAll($dataArr2,array('Id'=>$val['BillMasterPost']['Id']));
  
        }
    }
    
    public function view_padunpad_payment(){
        $this->layout = "user";
        $this->set('ClientName',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'Order'=>array('company_name'=>'Asc'))));

        if(isset($_REQUEST['cid']) && $_REQUEST['cid'] !=""){
            
            $data = $this->BillMasterPost->find('all',array('conditions'=>array('ClientId'=>$_REQUEST['cid'])));
            if(!empty($data)){
                $this->set('padunpad_payment',$data);
            }
            else{
                $this->set('padunpad_payment',array());
            }
            $this->set('BillClient',$_REQUEST['cid']);
        }  
    }
    
}
?>