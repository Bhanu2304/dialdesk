<?php
class AgentCreationsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('AgentMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('view_agent','delete_agents','updateagent');
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }		
    }
	
    public function index() {
        $this->layout='user';
        if($this->request->is("POST")){
            $name=$this->request->data['AgentCreations']['username'];		
            $password=$this->request->data['AgentCreations']['password'];
            $displayname=$this->request->data['AgentCreations']['displayname'];

            if($this->AgentMaster->find('first',array('fields'=>array('id'),'conditions'=>array('username'=>$name,'status'=>'A')))){
                $this->Session->setFlash("Login Id already exists.");
                $this->redirect(array('action' => 'index'));
            }
            else{
                $this->AgentMaster->save(array('displayname'=>$displayname,'username'=>$name,'password'=>$password,'status'=>'A'));
                $this->redirect(array('action' => 'view_agent'));
            }
        }	
    }
    
    public function updateagent() {
        $this->layout='user';
        if($this->request->is("POST")){
            $id             =   $this->request->data['id'];		
            $password       =   $this->request->data['AgentCreations']['password'];
            $displayname    =   $this->request->data['AgentCreations']['displayname'];
            
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
                'displayname'=>"'".$displayname."'",
                //'password'=>"'".$password."'",
                'update_user'=>"'".$update_user."'",
                'update_date'=>"'".$update_date."'"
                );
            
            $this->AgentMaster->updateAll($data,array('id'=>$id));die;
        }	
    }

    public function view_agent(){
        $this->layout='user';
        $this->set('data',$this->AgentMaster->find('all',array('conditions'=>array('status'=>'A'))));
    }
	
    public function delete_agents(){
        
        if($this->Session->read('role') =="client"){
            $update_user=$this->Session->read('email');
        }
        else if($this->Session->read('role') =="agent"){
            $update_user=$this->Session->read('agent_username');
        }
        if($this->Session->read('role') =="admin"){
            $update_user=$this->Session->read('admin_name');
        }

        $update_date=date('Y-m-d H:i:s');
        
        $status="I";
        $data=array('status'=>"'".$status."'",'update_user'=>"'".$update_user."'",'update_date'=>"'".$update_date."'");
        $this->AgentMaster->updateAll($data,array('id'=>$this->request->query['id']));
        $this->redirect(array('action' => 'view_agent'));
    }
		
}

?>