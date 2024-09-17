<?php
class AbandCallAllocationsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('AgentMaster','RegistrationMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('view_agent','delete_agents');
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }		
    }
	
    public function index() {
        $this->layout='user';
        $this->set('client',$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"))));
        $this->set('agent',$this->AgentMaster->find('list',array('fields'=>array("id","username"),'conditions'=>array('status'=>'A'))));
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=''){
            $rights=$this->AgentMaster->find('first',array('fields'=>array("ClientRights"),'conditions'=>array('id'=>$_REQUEST['id'],'status'=>'A')));
            $this->set('rights',explode(',', $rights['AgentMaster']['ClientRights'])); 
            $this->set('AgentId',$_REQUEST['id']);   
        }

        if($this->request->is("POST")){
            $AgentId  = $this->request->data['AbandCallAllocations']['Agent'];
            $ClientId = implode(',', $this->request->data['clientRights']);
            
            $this->AgentMaster->updateAll(array('ClientRights'=>"'".$ClientId."'",),array('id'=>$AgentId));
            $this->Session->setFlash("Agaent rights update successfully.");
            $this->redirect(array('action' => 'index','?'=>array('id'=>$AgentId)));            
        }	
    }
		
}

?>