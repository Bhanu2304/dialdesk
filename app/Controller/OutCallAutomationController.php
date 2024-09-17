<?php
class OutCallAutomationController extends AppController{

        public $helpers = array('Html', 'Form','Js');
        public $components = array('RequestHandler');
        public $uses=array('RegistrationMaster','OutCallAutomation','CampaignName');
	
        
        public function beforeFilter(){
            parent::beforeFilter();
            $this->Auth->allow();
            if(!$this->Session->check("companyid")){
                return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
            }

        }

    
	    public function index() {
            $this->layout='user';
            if($this->Session->read('role') =="admin"){
                $clientId = $this->Session->read('companyid');
                $Campaign=$this->CampaignName->find('list',array('fields'=>array('id','CampaignName'),'conditions'=>array('ClientId'=>$clientId,'CampaignStatus'=>'A')));
                
                $scen_data = $this->OutCallAutomation->find('all',array('conditions'=>array('client'=>$clientId)));      
                $this->set('scen_data',$scen_data);
            }
            $this->set('Campaign',$Campaign);
                
            
            if($this->request->is("POST")){
        
                $ClientId = $this->Session->read('companyid');
                $campaign_id =$this->request->data['OutCallAutomation']['campaignid'];		
                $to=$this->request->data['OutCallAutomation']['to'];
                $cc=$this->request->data['OutCallAutomation']['cc'];
                $remarks=$this->request->data['OutCallAutomation']['remarks'];
                $client_data =$this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$ClientId)));
                $client_name = $client_data['RegistrationMaster']['company_name'];

                $Campaign =$this->CampaignName->find('first',array('fields'=>array('CampaignName'),'conditions'=>array('ClientId'=>$ClientId,'id'=>$campaign_id)));
                $camp_name = $Campaign['CampaignName']['CampaignName'];
            
                $this->Session->setFlash("Added Sucessfully");
                $this->OutCallAutomation->save(array('client'=>$ClientId,'campaign_id'=>$campaign_id,'campaign_name'=>$camp_name,'to'=>$to,'cc'=>$cc,'remarks'=>$remarks,'created_at'=>date('Y-m-d H:i:s'),'created_by'=>$ClientId,'client_name'=>$client_name));

                $this->redirect(array('controller'=>'OutCallAutomation'));
               
            }

        }

        public function delete_alert(){
            $id=$this->request->query['id'];
            $this->OutCallAutomation->deleteAll(array('id'=>$id));
            $this->redirect(array('controller'=>'OutCallAutomation','action'=>'index'));
        }
            
        
}
?>