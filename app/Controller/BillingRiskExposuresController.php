<?php
class BillingRiskExposuresController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','BillRiskExposure','BillRiskExposureMail');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
	$this->Auth->allow('index','index1','save_raw','save_raw1','get_data','get_data_mail','del_raw','get_raw','del_raw1');
        if(!$this->Session->check("admin_id"))
        {
                return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }
    }

    public function index()
    {
        $this->layout = "user";
        //$clientIds = array_values($this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('not'=>array('PlanId'=>null),'PlanType'=>'Prepaid'))));
        $this->set('data',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc'))));
    }
    public function index1()
    {
        $this->layout = "user";
        //$clientIds = array_values($this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('not'=>array('PlanId'=>null),'PlanType'=>'Prepaid'))));
        $this->set('data',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name','email'),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc'))));
        $record_arr = $this->BillRiskExposureMail->find('all',array('order'=>"BillRiskExposureMail.percent"));
        $this->set('record_arr',$record_arr);
        //print_r($record_arr);die;
      
    }
    public function save_raw()
    {
        $this->layout = "ajax";
        $data = $this->request->data;
        
        $save_data = array();
        $save_data['client_id'] = $data['client_id'];
        $save_data['percent'] = $data['percent'];
        $save_data['risk_action'] = $data['risk_action'];
        $save_data['email_id'] = addslashes($data['email']);
        $save_data['email_cc'] = addslashes($data['email_cc']);
        $save_data['remarks'] = addslashes($data['remarks']);
        $save_data['created_at'] = date('Y-m-d H:i:s');
        $save_data['created_by'] = 1;
        //$save_data['client_id'] = $data['client_id'];
        
        if($this->BillRiskExposure->save($save_data))
        {
            return $this->get_data($data['client_id']);
        }
        else
        {
            echo $msg = "1";
        }
        exit;
        //print_r($data); exit;
        
    }

    public function save_raw1()
    {
        $this->layout = "ajax";
        $data = $this->request->data;
        $save_data = array();
        //$save_data['client_id'] = $data['client_id'];
        $save_data['percent'] = $data['percent'];
        $save_data['risk_action'] = $data['risk_action'];
        $save_data['email_id'] = addslashes($data['email']);
        $save_data['email_cc'] = addslashes($data['email_cc']);
        $save_data['remarks'] = addslashes($data['remarks']);
        $save_data['created_at'] = date('Y-m-d H:i:s');
        $save_data['created_by'] = 1;
        //$save_data['client_id'] = $data['client_id'];
        $client_mail = $this->RegistrationMaster->find('all');

            //print_r($client_mail[0]['RegistrationMaster']['email']);die;
            // $client_id = array();
            // foreach ($client_mail as $mail){

            //     $client_id = implode(",",$mail['RegistrationMaster']['company_id']);
            // }
            // $save_data['client_id'] = $client_id;
            
            if($this->BillRiskExposureMail->save($save_data))
            {
                //return $this->get_data_mail();
                echo $msg = "2";
            }
            else
            {
                echo $msg = "1";
            }
       
      
        exit;
        //print_r($data); exit;
        
    }

    public function get_data_mail() 
    {
        $record_arr = $this->BillRiskExposure->find('all',array('order'=>"BillRiskExposure.percent"));
        foreach($record_arr as $record)
        {
            echo '<tr id="tr'.$record['BillRiskExposure']['risk_id'].'">';
                echo '<td>'.$record['BillRiskExposure']['percent'].'%</td>';
                echo '<td>'.$record['BillRiskExposure']['risk_action'].'</td>';
                echo '<td>'.$record['BillRiskExposure']['email_id'].'</td>';
                echo '<td>'.$record['BillRiskExposure']['email_cc'].'</td>';
                echo '<td>'.$record['BillRiskExposure']['remarks'].'</td>';
                //echo '<td>'.'<button type="button"  value="btn" onclick="del_raw('.$record['BillRiskExposure']['risk_id'].')" >Delete</button>'.'</td>';
            echo '</tr>';
        }
          
    }
    
    public function get_data($client_id) 
    {
        $record_arr = $this->BillRiskExposure->find('all',array('conditions'=>"client_id='$client_id'",'order'=>"BillRiskExposure.percent"));
        foreach($record_arr as $record)
        {
            echo '<tr id="tr'.$record['BillRiskExposure']['risk_id'].'">';
                echo '<td>'.$record['BillRiskExposure']['percent'].'%</td>';
                echo '<td>'.$record['BillRiskExposure']['risk_action'].'</td>';
                echo '<td>'.$record['BillRiskExposure']['email_id'].'</td>';
                echo '<td>'.$record['BillRiskExposure']['email_cc'].'</td>';
                echo '<td>'.$record['BillRiskExposure']['remarks'].'</td>';
                echo '<td>'.'<button type="button"  value="btn" onclick="del_raw('.$record['BillRiskExposure']['risk_id'].')" >Delete</button>'.'</td>';
            echo '</tr>';
        }
          
    }
    
    public function get_raw() 
    {
        $this->layout = "ajax";
        $data = $this->request->data;
        return $this->get_data($data['client_id']);exit;
    }
    
    public function del_raw() {
        $this->layout = "ajax";
        $data = $this->request->data;
        $risk_id = $data['risk_id'];
        if($this->BillRiskExposure->deleteAll(array("risk_id"=>$risk_id)))
        {
            echo "1";exit;
        }
        else
        {
            echo "0";exit;
        }
        exit;
    }

    public function del_raw1() {
        $this->layout = "ajax";
        $data = $this->request->data;
        //print_r($data);die;
        $risk_id = $data['risk_id'];
        if($this->BillRiskExposureMail->deleteAll(array("risk_id"=>$risk_id)))
        {
            echo "1";exit;
        }
        else
        {
            echo "0";exit;
        }
        exit;
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