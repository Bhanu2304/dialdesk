<?php
class WatsappTextsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
   // public $uses=array('AgentMaster');
    public $uses=array('Watsapp','RegistrationMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('view_watsapp','delete_agents','updateagent');
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }		
    }
	
    public function index() {
        $this->layout='user';
        $company=$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));;
            $this->set('company',$company);
        if($this->request->is("POST")){
            $ClientId=$this->request->data['WatsappTexts']['ClientId'];		
            $AbandonText=$this->request->data['WatsappTexts']['AbandonText'];
            $OrderText=$this->request->data['WatsappTexts']['OrderText'];

                $this->Watsapp->save(array('ClientId'=>$ClientId,'AbandonText'=>$AbandonText,'OrderText'=>$OrderText));
                $this->redirect(array('action' => 'index'));
        }	
    }
    
    public function updateagent() {
        $this->layout='user';
        if($this->request->is("POST")){
            $id             =   $this->request->data['id'];		
            $AbandonText       =   $this->request->data['WatsappTexts']['AbandonText'];
            $OrderText    =   $this->request->data['WatsappTexts']['OrderText'];
            
            if($this->Session->read('role') =="client"){
                $update_user=$this->Session->read('email');
            }
            else if($this->Session->read('role') =="agent"){
                $update_user=$this->Session->read('agent_username');
            }
            if($this->Session->read('role') =="admin"){
                $update_user=$this->Session->read('admin_name');
            }

            $update_date    =   date('Y-m-d H:i:s');

            $data=array(
                'AbandonText'=>"'".$AbandonText."'",
                'OrderText'=>"'".$OrderText."'",
                'update_user'=>"'".$update_user."'",
                'update_date'=>"'".$update_date."'"
                );
            
            $this->Watsapp->updateAll($data,array('Id'=>$id));die;
        }	
    }

    public function view_watsapp(){
        $this->layout='user';
        $this->set('data',$this->Watsapp->find('all'));
    }
	
    public function delete_Watsapps(){
        
        $this->Watsapp->delete(array('Id'=>$this->request->query['Id']));
        $this->redirect(array('action' => 'view_watsapp'));
    }
		
}

?>