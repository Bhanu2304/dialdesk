<?php
class BillingpaymentsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','Training');
	
    public function beforeFilter() {
        parent::beforeFilter();
			$this->Auth->allow();
			if(!$this->Session->check("admin_id")){
				return $this->redirect(array('controller'=>'Admins','action' => 'index'));
			}
			
    }

	public function index() {
		$this->layout='adminlayout';
		$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
		$this->set('client',$client);
		$data = array();
		if($this->request->is('Post')){
			$ClientId = $this->request->data['Billingpayments']['client'];
			$data = $this->Training->find('all',array('conditions'=>array('ClientId'=>$ClientId)));
			$this->set('data',$data);
		}
		else{
			$this->set('data',$data);
		}
	}
	
	public function download() {
		$this->layout='ajax';
		$ClientId = $this->request->data['Billingpayments']['client'];
		$data = $this->Training->find('all',array('conditions'=>array('ClientId'=>$ClientId)));
		$this->set('data',$data);
	}
		
}
?>