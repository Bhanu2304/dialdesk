<?php
class AdminsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('Admin');
	
    public function beforeFilter() {
        parent::beforeFilter();
	$this->Auth->allow('login','logout');
    }

    public function index() {
        $this->layout='adminlogin';
        if ($this->request->is('post')) {
            $Arr=array('UserName'=>$this->params['data']['UserName'],'Password'=>$this->params['data']['Password']);
            $arrData=$this->Admin->find('first',array('conditions'=>$Arr)); 

            if(!empty($arrData['Admin'])){
                $this->Session->write("admin_id",$arrData['Admin']['id']);
                $this->Session->write("admin_name",$arrData['Admin']['UserName']);
                $this->Session->write("admin_email",$arrData['Admin']['Email']);
                $this->Session->write("role","admin");
                //$this->redirect(array('controller' => 'AdminDetails', 'action' => 'index'));
                $this->redirect(array('controller' => 'homes', 'action' => 'index'));
            }
            else {   
                $this->set('err','Invalid username or password.');
            } 
        }
    }
	
    public function logout() {
        $this->Session->delete('admin_id');
        $this->Session->delete('admin_name');
        $this->Session->delete('admin_email');
        $this->Session->delete('role');
        $this->Session->delete('companyid');
        $this->Session->delete('clientstatus');
        $this->Session->delete('campaignid');
        $this->Session->destroy();
        $this->redirect(array('action'=>'index'));
    }
		
}
?>