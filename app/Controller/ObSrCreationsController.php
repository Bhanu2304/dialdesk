<?php
App::uses('AppController', 'Controller');
class ObSrCreationsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('FieldMaster','FieldValue','CallMaster','ObecrMaster','CampaignName');
	
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('save_srcreation','getChild','getobecr_bycampaign');
        if(!$this->Session->check("companyid")){
                return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
	
	public function index() {       
            $this->layout='user';
            $clientId = $this->Session->read('companyid');
            $this->set('Campaign',$this->CampaignName->find('list',array('fields'=>array('CampaignName'),'conditions'=>array('ClientId'=>$clientId))));
            $fieldName = $this->FieldMaster->find('all',array('conditions'=>array('ClientId'=>$clientId),'order'=>array('Priority')));
            $fieldValue = $this->FieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId),'group'=>'FieldId'));
            
            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);
   
            $ecr = array();
            if($this->request->is('Post')){
                $campaignid=$this->request->data['ObSrCreations']['campaign'];
                $ecr = $this->ObecrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId,'CampaignId'=>$campaignid),'group'=>'Label','order'=>array('Label'=>'asc')));		             
               $this->set('ecr',$ecr);
            }
            else{
                 $this->set('ecr',$ecr);
            }
            
            unset($fieldName);unset($fieldValue);unset($ecr);       
	}
        

         public function save_srcreation(){
            if($this->request->is("POST")){
                $data['CallMaster'] = $this->request->data['ObSrCreations'];
                $data['CallMaster']['CallDate'] = date('Y-m-d H:i:s');
                $data['CallMaster']['ClientId'] = $this->Session->read("companyid");	
                $dataSource = $this->CallMaster->getDataSource();
                $dataSource ->begin();
                $flag = $this->CallMaster->save($data);
                $this->set('data',$data);
                if($flag){$dataSource->commit();}
                else{$dataSource->rollback();}			
                $this->redirect(array('action'=>'index'));
            }
        }
        
       public function getChild(){
        $this->layout ='ajax';
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $label = $data['Label'] = addslashes($this->request->data['Label']);
                $label ++;	
                $data['Client'] = $this->Session->read('companyid');
                $data['ecrName'] = addslashes($this->request->data['Parent']);
                $category1 = $this->ObecrMaster->find('first',array('fields'=>array('id'),'conditions'=>$data));
                $category = array();		
                if(!empty($category1)){
                    $data = array();
                    $data['Label'] = $label;
                    $data['Client'] = $this->Session->read('companyid');
                    $data['parent_id'] = $category1['ObecrMaster']['id'];
                    $data = $this->ObecrMaster->find('all',array('fields'=>array('ecrName'),'conditions'=>$data));
                    foreach($data as $post):
                    $category[$post['ObecrMaster']['ecrName']] = $post['ObecrMaster']['ecrName'];
                    endforeach;
                }
            }
            $this->set('label',$label);
            $this->set('options',$category);			
        }		
    }
	
}

?>