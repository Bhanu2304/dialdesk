<?php
class MyWalletsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('BalanceMaster','PlanMaster');
	
	
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid")){
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
	
    public function index() {
        $this->layout='user';
        $ClientId = $this->Session->read('companyid');
        //$result = $this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$ClientId)));
        //$this->set('data',$result['RegistrationMaster']);
        
        
        $BalanceArr = $this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>$ClientId)));
        $PlanArr = $this->PlanMaster->find('first',array('conditions'=>array('Id'=>$BalanceArr['BalanceMaster']['PlanId'])));
        $this->set('plan',$PlanArr['PlanMaster']);
        $this->set('balance',$BalanceArr['BalanceMaster']);
        
    }
		
}
?>