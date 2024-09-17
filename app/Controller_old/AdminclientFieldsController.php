<?php
class AdminclientFieldsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('FieldCreation','FieldValue','RegistrationMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add','index' ,'view','setPriority','edit','update');
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }		
    }
    
    public function index(){
        $this->layout='adminlayout';
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
        $this->set('client',$client);
        $this->set('clientid',array());
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $ClientId =$_REQUEST['id'];
            $this->set('data',$this->FieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId))));
            $this->set('clientid',$ClientId);  
        }
        
        if($this->request->is('Post')){
            $data=$this->request->data['AdminclientFields'];
            $ClientId =$data['clientID'];
            $this->set('data',$this->FieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId))));
            $this->set('clientid',$ClientId);  
        }  
    }
    
    public function add(){
        if($this->request->is('Post')){
            $data=$this->request->data['AdminclientFields'];
            $ClientId =$data['cid'];
            $this->set("data",$this->request->data);
            $data = $this->request->data['AdminclientFields'];	
            if($data['FieldName']==''){
                $this->Session->setFlash("FieldName is Not Blank"); 
            }
            else if($data['FieldType']=='') {
                $this->Session->setFlash("FieldType is Not Choosen");
            }	
            else{
                $result['FieldName'] = addslashes($data['FieldName']);
		$result['FieldType'] = addslashes($data['FieldType']);
		if($data['FieldValidation']==''){$data['FieldValidation']="Char";}
		$result['FieldValidation'] = addslashes($data['FieldValidation']);
		$result['RequiredCheck'] = addslashes($data['RequiredCheck']);
		$result['ClientId'] = addslashes($ClientId);
                $data = $this->FieldCreation->find('first',array('fields'=>array("max"),'conditions'=>array('ClientId'=>$ClientId)));
                $result['Priority'] = $data['FieldCreation']['max']+1;
		$result['CreateDate'] = date("Y-m-d H:i:s");		
		$this->FieldCreation->save($result);
		unset($data);
			
                if($result['FieldType'] == 'DropDown'){ 
                    $id = $this->FieldCreation->getInsertId();
                    unset($result);$result = array();
                    $value = $this->request->data['down'];
                    $FieldValue = explode(',',$value);

                    for($i=0; $i<count($FieldValue); $i++)
                    {
                        $result[$i] =  array('FieldId'=>$id,'FieldValueName'=>addslashes($FieldValue[$i]),'ClientId'=>$ClientId);
                    }
                    $this->FieldValue->saveAll($result);
                    unset($result);
                }
            }			
	}
	$this->redirect(array('action'=>'index','?' => array('id' =>$ClientId)));
    }
	
	
	
    public function view(){
        $this->layout='adminlayout';
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
        $this->set('client',$client);
        $this->set('clientid',array());
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $ClientId =$_REQUEST['id'];
            $fieldName = $this->FieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId),'order'=>array('Priority')));		
            $fieldValue = $this->FieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$ClientId),'group'=>'FieldId'));		
            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);	
            $this->set('clientid',$ClientId);  
        }
        
        if($this->request->is('Post')){
            $data=$this->request->data['AdminclientFields'];
            $ClientId =$data['clientID'];
            $fieldName = $this->FieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId),'order'=>array('Priority')));		
            $fieldValue = $this->FieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$ClientId),'group'=>'FieldId'));		
            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);	
            $this->set('clientid',$ClientId);  
        }      
    }

    public function setPriority(){
        if($this->request->is("Post")){
            $ClientId = $this->request->data['AdminclientFields']['cid'];
            if(!empty($this->request->data['AdminclientFields'])){
                $data = array_filter($this->request->data['AdminclientFields']);
                $count = count($data);
                $keys = array_keys($data);
                for($i=0; $i<$count; $i++)
                {
                        $fieldName = $this->FieldCreation->updateAll(array('Priority'=>"'".$data[$keys[$i]]."'"),array("id"=>$keys[$i]));
                }
                $this->set('data',$keys);
                $this->redirect(array('action'=>'index','?' => array('id' =>$ClientId)));
            }
            else{
               $this->redirect(array('action'=>'index','?' => array('id' =>$ClientId)));
            }
        }


    }
    
    public function edit(){
        $this->layout="adminlayout";
        $ClientId = $this->params->query['cid'];
        $id = base64_decode($this->params->query['id']); 
        
        $fieldName = $this->FieldCreation->find('first',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'id'=>$id),'order'=>array('Priority')));
        $this->set('fieldName',$fieldName);
        if($fieldName['FieldCreation']['FieldType'] =='DropDown'){
            $this->set("fieldValue", $this->FieldValue->find('all',array('fields'=>array('id','FieldId','FieldValueName'),'conditions'=>array('FieldId'=>$id))));
        }
        $this->set('clientid',$ClientId);  
    }
    
    public function update(){
	if($this->request->is('Post')){
            $ClientId = $this->request->data['AdminclientFields']['cid'];
            $id = $this->request->data['id'];
            $data = $this->request->data['AdminclientFields'];
            
            if($data['FieldName']=='') 		{$this->Session->setFlash("FieldName is Not Blank"); }
            else if($data['FieldType']=='') {$this->Session->setFlash("FieldType is Not Choosen");}
			
            else
	    {
            
                $result['FieldName'] = "'".addslashes($data['FieldName'])."'";
		$result['FieldType'] = "'".addslashes($data['FieldType'])."'";
		if($data['FieldValidation']==''){$data['FieldValidation']="Char";}
		$result['FieldValidation'] = "'".addslashes($data['FieldValidation'])."'";
		$result['RequiredCheck'] = "'".addslashes($data['RequiredCheck'])."'";
		
                $data1 = $this->FieldCreation->find('first',array('fields'=>array('FieldType'),'conditions'=>array('id'=>$id)));
		if($data['FieldType'] == 'DropDown' && $data1['FieldCreation']['FieldType'] == 'DropDown')
              {
                  $old_value = $this->request->data['oldValues'];
                  $keys = array_keys($old_value);
                  for($i =0; $i<Count($keys); $i++)
                  {
                      //print_r($keys[$i]); die;
                    $this->FieldValue->updateAll(array('FieldValueName'=>"'".$old_value[$keys[$i]]."'"),array('id'=>$keys[$i],'FieldId'=>$id));
                  } 
              }
              		
		$this->FieldCreation->updateAll($result,array('id'=>$id));
		unset($data);
				
		if($result['FieldType'] == "'DropDown'")
		{                    
		    unset($result);$result = array();
		    $value = $this->request->data['down'];
                    if(!empty($value))
                    {
                        $FieldValue = explode(',',$value);
		
                        for($i=0; $i<count($FieldValue); $i++)
                        {
                            $result[$i] =  array('FieldId'=>$id,'FieldValueName'=>addslashes($FieldValue[$i]),'ClientId'=>$ClientId);
                        }
                        $this->FieldValue->saveAll($result);
                        unset($result);
                    }
		}
            }			
	}
	$this->redirect(array('action'=>'view','?' => array('id' =>$ClientId)));
	}   
	
}
?>