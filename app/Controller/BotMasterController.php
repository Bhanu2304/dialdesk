<?php
class BotMasterController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('RegistrationMaster','FieldValue','TrainingMaster','TrainingDetails','WhatsappTicket','WhatsappIssuelist','BotMaster');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
    
        $this->Auth->allow('index','view','delete_data');
        
    }
	
    public function index() 
    {
        $this->layout='user';

        $ClientId = $this->Session->read('companyid');
        
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));  
        $this->set('client',$client);
        if($this->request->is('Post'))
        {
            $data   =   $this->request->data['BotMaster'];
            //print_r($data);die;
            $botArr = array();
            $botArr['ClientId'] = $data['clientID'];
            $botArr['phone_no'] = '91'.$data['number'];
            $botArr['type']     = $data['type'];
            $botArr['request']  = $data['request'];
            $botArr['response'] = $data['response'];
            $botArr['Bot_options'] = $data['option'];

            //print_r($botArr);die;

            $this->BotMaster->save($botArr);die;

        }
       
    }

    public function view() 
    {
        $this->layout='user';

        $ClientId = $this->Session->read('companyid');
        
        $data = $this->BotMaster->find('all',array(
            'order' => array('BotMaster.id' => 'desc')
        ));
        //print_r($data);die;
        $this->set('data',$data);
        
       
    }

    public function delete_data()
    {
        $id  = $this->request->query['id'];
            
		$this->BotMaster->delete(array('id'=>$id));
		$this->redirect(array('action' => 'index'));
    }
  
	



       
 


}
?>