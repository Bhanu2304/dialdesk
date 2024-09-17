<?php
class AddListsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('RegistrationMaster','ListMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add_list_id','delete_list_id','addlist');
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'admins','action' => 'index'));
        }
    }

    public function addlist(){
        $this->layout='user';
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
        $this->set('client',$client);
        $this->set('clientid',array());

        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $this->set('listid',$this->ListMaster->find('all',array('conditions'=>array('client_id'=>$_REQUEST['id']))));
            $this->set('clientid',$_REQUEST['id']);
        }
        if($this->request->is('Post')){
            $data=$this->request->data['AddLists'];  
            $this->set('listid',$this->ListMaster->find('all',array('conditions'=>array('client_id'=>$data['clientID']))));
            $this->set('clientid',$data['clientID']);
        }
    }

    public function add_list_id(){
        if($this->request->is('Post')){
            $data=$this->request->data['AddLists'];
            $existDid=$this->ListMaster->find('first',array('conditions'=>array('list_id'=>$data['listid'])));
            
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
            
            if(!empty($existDid)){
                $this->Session->setFlash('<span style="color:red;">List id already used.</span>');
            }
            else{
                $this->ListMaster->save(array('list_id'=>$data['listid'],'client_id'=>$data['client_id'],'update_user'=>$update_user,'create_date'=>$update_date));  
                $this->Session->setFlash('List id add successfully.');
            }
            $this->redirect(array('controller' => 'AddLists', 'action' => 'addlist', '?' => array('id' =>$data['client_id'])));
        }
    }

    public function delete_list_id(){
        if(isset($_REQUEST['id'])){ 
            
            if($this->Session->read('role') =="client"){
                    $update_user=$this->Session->read('email');
                }
                else if($this->Session->read('role') =="agent"){
                    $update_user=$this->Session->read('agent_username');
                }
                if($this->Session->read('role') =="admin"){
                    $update_user=$this->Session->read('admin_name');
                }
                
                $this->ListMaster->query("INSERT INTO `list_master_history` (Id,list_id,client_id,create_date,update_user,update_date) 
                SELECT Id,list_id,client_id,create_date,'$update_user',NOW() FROM list_master WHERE Id='{$_REQUEST['id']}' AND client_id='{$_REQUEST['cid']}'");
            
                $this->ListMaster->delete(array('Id'=>$_REQUEST['id'],'client_id'=>$_REQUEST['cid']));
        }
        $this->redirect(array('controller' => 'AddLists', 'action' => 'addlist', '?' => array('id' =>$_REQUEST['cid'])));
    }
        
       
		
}
?>