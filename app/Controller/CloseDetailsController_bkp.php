<?php
class CloseDetailsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('CloseFieldData','CloseFieldDataValue','CloseUpdate');
	
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid")){
                return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
	
    public function view_close_fields() {
        if(isset($_REQUEST['id'])){  
            $this->layout='ajax';
            $clientId = $this->Session->read('companyid');
           
            $fieldValue1 = $this->CloseFieldDataValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
            $fieldName1 = $this->CloseFieldData->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
            $CArr = $this->CloseUpdate->find('first',array('conditions'=>array('Id'=>$_REQUEST['id'],'ClientId' =>$clientId)));
            
            $this->set('fieldName1',$fieldName1);
            $this->set('fieldValue1',$fieldValue1);
            $this->set('callId',$_REQUEST['id']);
            $this->set('CArr',$CArr['CloseUpdate']);
        }	
    }
    
    public function update_srclose_field(){ 
        if($this->request->is("POST")){  
            $ClientId = $this->Session->read('companyid');
            $Id=$this->request->data['Id'];
            $data=$this->request->data['CloseDetails'];
       
            foreach($data as $kay=>$val){ 
                $dataArr[$kay]="'".$val."'";          
            }
            $dataArr['CFieldUpdate']="'".date('Y-m-d H:i:s')."'";          
                    
            $this->CloseUpdate->updateAll($dataArr,array('Id'=>$Id,'ClientId' =>$ClientId));
            $this->Session->setFlash('Your data update successfully.');
            $this->redirect(array('controller'=>'SrDetails','action' => 'index','?'=>array('status'=>'success')));
        }
    }
		
}
?>