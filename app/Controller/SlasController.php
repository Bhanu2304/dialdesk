<?php
class SlasController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow();
                /*
		if(!$this->Session->check("companyid")){
			return $this->redirect(array('controller'=>'ClientActivations','action' => 'index'));
		}*/
    }
    //$this->Session->check("companyid")
    //$this->Session->write("role","admin");
    
	public function index() {
          $this->layout='user';
               
		
	}
	public function cdr() {
          $this->layout='user';
               
		
	}
	public function call() {
          $this->layout='user';
               
		
	}
	public function tagging() {
          $this->layout='user';
               
		
	}
	
		
}
?>