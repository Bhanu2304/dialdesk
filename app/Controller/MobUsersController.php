<?php
class MobUsersController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('Mob');
	
  /*  public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('view_agent','delete_agents','updateagent');
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }		
    } */

    public function beforeFilter() {
        parent::beforeFilter();
        if($this->Session->check('companyid'))
        {
        $this->Auth->allow(
            'index',
            'updateagent',
            'view_agent',
            'view_edit_field',
            'delete_agents'
            );
        }
        else
        {$this->Auth->deny('index',
            'add',
            'view'
            );
        }
    }
	
    public function index() {
        $this->layout='user';
        //print_r($_POST);
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
        if($this->request->is("POST")){
            $name=$this->request->data['MobUsers']['Name'];		
            $password=$this->request->data['MobUsers']['password'];
            $Code=$this->request->data['MobUsers']['Code'];

            if($this->Mob->find('first',array('fields'=>array('Id'),'conditions'=>array('Code'=>$Code,'status'=>'1')))){
                $this->Session->setFlash("Code already exists.");
                $this->redirect(array('action' => 'index'));
            }
            else{
                $this->Mob->save(array('Name'=>$name,'Password'=>$password,'Code'=>$Code,'CreateDate'=>$update_date,'CreateBy'=>$update_user,'Status'=>'1'));
                $this->Session->setFlash("Code Created Sucessfully.");
                $this->redirect(array('action' => 'index'));
            }
        }
        $this->set('data',$this->Mob->find('all',array('conditions'=>array('1'=>'1'),"order"=>array('status'=>"desc"))));	
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
        $this->set('data',$this->Mob->find('all',array('conditions'=>array('Status'=>'1'))));
    }

    public function view_edit_field(){
       // print_r($_POST); exit;
            if ($this->request->is('post')) {
                $this->layout='ajax';
                echo $data=$this->request->data; exit;
                //$data['user_active']='1';
                $user=$this->Mob->find('first',array('conditions' =>$data));
                //$userAss=explode(',',$user['Admin']['user_right']);
                
                $this->set('get_record',$user);
                $this->set('save_record',$userAss);
                
                
                
                
                
                }   
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
        
        $status="0";
        $data=array('Status'=>"'".$status."'",'UpdateBy'=>"'".$update_user."'",'UpdateDate'=>"'".$update_date."'");
        $this->Mob->updateAll($data,array('Id'=>$this->request->query['Id']));
        $this->redirect(array('action' => 'index'));
    }
		
}

?>