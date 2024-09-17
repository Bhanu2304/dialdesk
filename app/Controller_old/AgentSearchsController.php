<?php
class AgentSearchsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('FieldMaster','FieldValue','EcrMaster','CallMaster','ClientMaster','ObField','ObFieldValue','ObEcr','CampaignName','ObCampaignDataMaster','ObAllocationMaster','UploadExistingBase','CloseLoopMaster');
	
        //public $uses=array('FieldMaster','FieldValue','EcrMaster','CallMaster','OutboundMaster','ClientMaster','ObField','ObFieldValue','ObEcr','CampaignName','ObCampaignDataMaster','ObAllocationMaster');
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('search_result');
               
		if(!$this->Session->check("companyid")){
			return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
		}
    }
	

        
       
            public function search_result(){ 
            $this->layout = 'ajax';
            $search = array();
            $fields = array();
           
            if($this->request->is('POST')){
                $clientId = $this->Session->read("companyid");
                $fields = array_keys($this->request->data['AgentSearchs']);
                $data = array_filter($this->request->data['AgentSearchs']);  
     
                isset ($data['CallDate']) ? $data['CallDate'] =date("Y-m-d",strtotime($data['CallDate'])) : "";

                 if(!empty($data)){
                    foreach($data as $s=>$v){
                    if(!empty($v)){
                        $search['CallMaster.'.$s.' LIKE'] = '%'.$v.'%';
                    }
                }
                
             
                
                $search = $this->CallMaster->find('all',array('fields'=>$fields,'conditions'=>array('or'=>$search,'ClientId'=>$this->Session->read('companyid'))));       
               
               
                
                if(!empty($search)){
                $this->set('search',$search); 
                }
                else{
                echo "";die;
            }
            }
            else{
                echo "";die;
            }
            $fieldName = $this->FieldMaster->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
            $ecr = $this->EcrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId),'group'=>'Label','order'=>array('Label'=>'asc')));		
            $this->set('fieldName',$fieldName);
            $this->set('ecr',$ecr);
                
            }  
        }
        
        
        
}