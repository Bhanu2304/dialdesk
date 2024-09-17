<?php

App::uses('AppController', 'Controller');
class CallbacksController extends AppController {
public $components = array('Session');
public $uses=array('CallBack','RegistrationMaster');
    public function beforeFilter() {
    parent::beforeFilter();
    // Allow users to register and logout.
    $this->Auth->allow('index','benchmark');
	
}

	
	
public function index(){
            $this->layout="user";
            //if($this->Session->read("adminid") == ''){$this->redirect(array('controller'=>'users','action'=>'logout'));}
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
                $this->set('client',$client);
                $client_qry = "DATE(callback_time)=CURDATE()";
                if($this->request->is('Post')){
                    //print_r($this->request->data);exit;
                    $client_id=$this->request->data['Home']['clientID'];
                    #$startdate=$this->request->data['Home']['startdate'];
                    #$enddate=$this->request->data['Home']['enddate'];
                    $startdate   =   date("Y-m-d",strtotime($this->request->data['Home']['startdate']));
                    $enddate     =   date("Y-m-d",strtotime($this->request->data['Home']['enddate']));
                    //print_r($client_id);exit;
                    $this->set('companyid',$client_id);
                    if(!empty($client_id))
                    {
                        $client_qry = " DATE(callback_time) BETWEEN '$startdate' AND '$enddate'and rm.company_id='$client_id'";
                        
                    }

                }
         
            $callbacklist = $this->CallBack->query("SELECT rm.company_name,cm.phone_no,cm.callback_time,cm.agentid,am.username,am.displayname FROM `callback_master` cm 
                INNER JOIN registration_master rm ON cm.client_id=rm.company_id
                INNER JOIN `agent_master` am ON cm.agentid = am.id 
                WHERE  $client_qry");
            $this->set('callbacklist',$callbacklist);
            
                
		
	}

    public function benchmark(){
		$this->layout='user';
        

		$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
        $this->set('client',$client);
        if($this->request->is('post'))
        {   
            $data = $this->request->data['Home'];
            $client_id = $data['clientID'];

            
            $benchmark_arr =$this->Benchmark->find('all');
            #print_r($benchmark_arr);die;
            #echo $client_id = $this->request->data('Home');die;
            $this->set('benchmark_arr',$benchmark_arr);
            $this->set('companyid',$client_id);
        }	
		
	}

    
}