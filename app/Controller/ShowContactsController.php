<?php
class ShowContactsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('ContactUs');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
	$this->Auth->allow('index');
	if(!$this->Session->check("admin_id"))
        {
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
	}
    }
                
    	
	public function index() 
        {
		$this->layout='user';
                
               //print_r($this->ContactUs->find("all"));die;
                
		$this->set('data', $this->ContactUs->find("all"));

	}
	
    
        
	
}
?>