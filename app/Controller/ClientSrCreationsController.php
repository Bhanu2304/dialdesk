<?php
App::uses('AppController', 'Controller');
class ClientSrCreationsController extends AppController{
    
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('UploadExistingBase','FieldMaster','FieldValue','EcrMaster','CallMaster');
	
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('save_srcreation','getChild');
        if(!$this->Session->check("companyid")){
                return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
	
    public function index() {
        $this->layout='user';
        $clientId = $this->Session->read('companyid');
        $fieldName = $this->FieldMaster->find('all',array('conditions'=>array('ClientId'=>$clientId),'order'=>array('Priority')));
        $fieldValue = $this->FieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId),'group'=>'FieldId'));
        $ecr = $this->EcrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId),'group'=>'Label','order'=>array('Label'=>'asc')));		
        $this->set('fieldName',$fieldName);
        $this->set('fieldValue',$fieldValue);
        $this->set('ecr',$ecr);
        unset($fieldName);unset($fieldValue);unset($ecr);
    }
    
    public function save_srcreation(){
        if($this->request->is("POST")){
            $data['CallMaster'] = $this->request->data['ClientSrCreations'];
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
                $category1 = $this->EcrMaster->find('first',array('fields'=>array('id'),'conditions'=>$data));
                $category = array();		
                if(!empty($category1)){
                    $data = array();
                    $data['Label'] = $label;
                    $data['Client'] = $this->Session->read('companyid');
                    $data['parent_id'] = $category1['EcrMaster']['id'];
                    $data = $this->EcrMaster->find('all',array('fields'=>array('ecrName'),'conditions'=>$data));
                    foreach($data as $post):
                    $category[$post['EcrMaster']['ecrName']] = $post['EcrMaster']['ecrName'];
                    endforeach;
                }
            }
            $this->set('label',$label);
            $this->set('options',$category);			
        }		
    }
	
}
?>