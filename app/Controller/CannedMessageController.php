<?php
class CannedMessageController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','BillRiskExposure','BillRiskExposureMail','CannedMessage');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
	    $this->Auth->allow('index','save_raw','get_data','get_data_mail','del_raw','get_raw');
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
    
    public function save_raw()
    {
        $this->layout = "ajax";
        $data = $this->request->data;
        $client_id = $data['client_id'];
        $record_arr = $this->RegistrationMaster->find('first',array('conditions' => array('company_id'=>$client_id)));

        $email = $record_arr['RegistrationMaster']['email'];
        $save_data = array();
        $save_data['client_id'] = $data['client_id'];
        $save_data['percent'] = $data['percent'];
        $save_data['email_id'] = addslashes($email);
        $save_data['remarks'] = addslashes($data['remarks']);
        $save_data['created_at'] = date('Y-m-d H:i:s');
        $save_data['created_by'] = 1;
        //$save_data['client_id'] = $data['client_id'];
        
        if($this->CannedMessage->save($save_data))
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
    
    public function get_data($client_id) 
    {
        $record_arr = $this->CannedMessage->find('all',array('conditions'=>"client_id='$client_id'",'order'=>"CannedMessage.percent"));
        foreach($record_arr as $record)
        {
            echo '<tr id="tr'.$record['CannedMessage']['risk_id'].'">';
                echo '<td>'.$record['CannedMessage']['percent'].'%</td>';
                echo '<td>'.$record['CannedMessage']['email_id'].'</td>';
                echo '<td>'.$record['CannedMessage']['remarks'].'</td>';
                echo '<td>'.'<button type="button"  value="btn" onclick="del_raw('.$record['CannedMessage']['risk_id'].')" >Delete</button>'.'</td>';
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
        if($this->CannedMessage->deleteAll(array("risk_id"=>$risk_id)))
        {
            echo "1";exit;
        }
        else
        {
            echo "0";exit;
        }
        exit;
    }
    
    public function get_tagging_status(){
        $this->layout = "ajax";
        $result = $this->BalanceMaster->find('first',array('fields'=>array('CrmTagStatus'),'conditions'=>array('clientId'=>$_REQUEST['ClientId']))); 
        echo $result['BalanceMaster']['CrmTagStatus'];die;
    }
    
    
		
}
?>