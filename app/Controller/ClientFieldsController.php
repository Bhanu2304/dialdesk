<?php
class ClientFieldsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('FieldCreation','FieldValue');
	
    public function beforeFilter() {
        parent::beforeFilter();
		if($this->Session->check('companyid')){
        $this->Auth->allow(
			'index',
            'index2',
			'add',
			'view',
			'setPriority',
                        'edit',
                        'delete_clientfields',
                        'update');
		}
                /*
		else
		{
			$this->deny('index',
			'add',
			'view',
			'setPriority');
			}*/
    }
	
	public function index() {
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');
		if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
		$this->set('data',$this->FieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL))));
                
                 $fieldName = $this->FieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL),'order'=>array('Priority')));		
            $fieldValue = $this->FieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL),'group'=>'FieldId'));		

            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);
                
	}

    public function index2() {
		$this->layout='popuser';
        #$ClientId = $_GET['client_id'];
		$ClientId = $this->Session->read('companyid');
		if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
		$this->set('data',$this->FieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL))));
                
                 $fieldName = $this->FieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL),'order'=>array('Priority')));		
            $fieldValue = $this->FieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL),'group'=>'FieldId'));		
        #print_r($fieldValue);die;
            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);
                
	}
	
	public function add() 
        {
            $this->layout='user';
            if($this->request->is('Post'))
            {
            $ClientId = $this->Session->read('companyid');
            if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
            $this->set("data",$this->request->data);
           // print_r($this->request->data); exit;
            $data = $this->request->data['ClientFields'];
			
            if($data['FieldName']=='') 		{$this->Session->setFlash("FieldName is Not Blank"); }
            else if($data['FieldType']=='') {$this->Session->setFlash("FieldType is Not Choosen");}
			
            else
            {
                $result['FieldName'] = addslashes($data['FieldName']);
		$result['FieldType'] = addslashes($data['FieldType']);
		if($data['FieldValidation']==''){$data['FieldValidation']="Char";}
		$result['FieldValidation'] = addslashes($data['FieldValidation']);
		$result['RequiredCheck'] = addslashes($data['RequiredCheck']);
		$result['ClientId'] = addslashes($ClientId);
                $data = $this->FieldCreation->find('first',array('fields'=>array("max"),'conditions'=>array('ClientId'=>$ClientId)));
                $data2 = $this->FieldCreation->find('first',array('fields'=>array("max2"),'conditions'=>array('ClientId'=>$ClientId)));
                $result['Priority'] = $data['FieldCreation']['max']+1;
                $result['fieldNumber'] = $data2['FieldCreation']['max2']+1;
		$result['CreateDate'] = date("Y-m-d H:i:s");
				
		$this->FieldCreation->save($result);
		unset($data);
				
            if($result['FieldType'] == 'DropDown')
            { 
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
		$this->redirect(array('controller' => 'ClientFields', 'action' => 'index'));
	}
	
	
	
    public function view()
        {
            $ClientId = $this->Session->read('companyid');
            if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}		
            $fieldName = $this->FieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId),'order'=>array('Priority')));		
            $fieldValue = $this->FieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$ClientId),'group'=>'FieldId'));		

            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);		
            $this->layout="user";
	}

	public function setPriority()
	{
		$this->layout="user";
		if($this->request->is("Post")){
			$ClientId = $this->Session->read('companyid');
			if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
                        if(!empty($this->request->data['ClientFields'])){
			$data = array_filter($this->request->data['ClientFields']);
			$count = count($data);
			$keys = array_keys($data);
			for($i=0; $i<$count; $i++)
			{
				$fieldName = $this->FieldCreation->updateAll(array('Priority'=>"'".$data[$keys[$i]]."'"),array("id"=>$keys[$i]));
			}
			$this->set('data',$keys);
			$this->redirect(array('controller' => 'ClientFields', 'action' => 'index'));
                        }
                        else{
                           $this->redirect(array('controller' => 'ClientFields', 'action' => 'index')); 
                        }
		}
		

	}
    
    public function edit(){
        $this->layout="ajax";
        if(isset($_REQUEST['id'])){
            $ClientId = $this->Session->read('companyid');
            $id = $_REQUEST['id'];    
            $fieldName = $this->FieldCreation->find('first',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'id'=>$id),'order'=>array('Priority')));
            $this->set('fieldName',$fieldName);
            if($fieldName['FieldCreation']['FieldType'] =='DropDown'){
                $this->set("fieldValue", $this->FieldValue->find('all',array('fields'=>array('id','FieldId','FieldValueName'),'conditions'=>array('FieldId'=>$id))));
            }
        }
    }
    
    public function update() {
        $this->layout='user';
	if($this->request->is('Post')){
            $ClientId = $this->Session->read('companyid');
	    if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
            $id = $this->request->data['id'];
            $data = $this->request->data['ClientFields'];
            
            if($data['FieldName']==''){$this->Session->setFlash("FieldName is Not Blank"); }
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
        $this->Session->setFlash("Fields update successfully.");
	$this->redirect(array('controller' => 'ClientFields', 'action' => 'index'));
	}   
        
        
       public function delete_clientfields(){
        $id=$this->request->query['id'];
        /*
        if($this->FieldCreation->deleteAll(array('id'=>$id,'ClientId' => $this->Session->read('companyid')))){
            $this->FieldValue->deleteAll(array('FieldId'=>$id,'ClientId' => $this->Session->read('companyid')));
        }*/
        $fieldStatus="D";
        if($this->FieldCreation->updateAll(array('FieldStatus'=>"'".$fieldStatus."'"),array('id'=>$id,'ClientId' => $this->Session->read('companyid')))){
            $this->FieldValue->updateAll(array('FieldStatus'=>"'".$fieldStatus."'"),array('FieldId'=>$id,'ClientId' => $this->Session->read('companyid')));
        }
        $this->redirect(array('action' => 'index'));
    }
        
	
}
?>