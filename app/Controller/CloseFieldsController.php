<?php
class CloseFieldsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('FieldCreation','FieldValue','CloseField','CloseFieldValue');
	
    public function beforeFilter() {
        parent::beforeFilter();
		if($this->Session->check('companyid')){
        $this->Auth->allow(
			'index',
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
		$this->set('data',$this->CloseField->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL))));
                
                 $fieldName = $this->CloseField->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL),'order'=>array('Priority')));		
            $fieldValue = $this->CloseFieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL),'group'=>'FieldId'));		

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
            $data = $this->request->data['CloseFields'];
			
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
                $data = $this->CloseField->find('first',array('fields'=>array("max"),'conditions'=>array('ClientId'=>$ClientId)));
                $data2 = $this->CloseField->find('first',array('fields'=>array("max2"),'conditions'=>array('ClientId'=>$ClientId)));
                $result['Priority'] = $data['CloseField']['max']+1;
                $result['fieldNumber'] = $data2['CloseField']['max2']+1;
		$result['CreateDate'] = date("Y-m-d H:i:s");
				
		$this->CloseField->save($result);
		unset($data);
				
            if($result['FieldType'] == 'DropDown')
            { 
                $id = $this->CloseField->getInsertId();
                unset($result);$result = array();
                $value = $this->request->data['down'];
                $FieldValue = explode(',',$value);
					
                for($i=0; $i<count($FieldValue); $i++)
                {
                    $result[$i] =  array('FieldId'=>$id,'FieldValueName'=>addslashes($FieldValue[$i]),'ClientId'=>$ClientId);
                }
                $this->CloseFieldValue->saveAll($result);
                unset($result);
            }
            }			
	}
		$this->redirect(array('controller' => 'CloseFields', 'action' => 'index'));
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
                        if(!empty($this->request->data['CloseFields'])){
			$data = array_filter($this->request->data['CloseFields']);
			$count = count($data);
			$keys = array_keys($data);
			for($i=0; $i<$count; $i++)
			{
				$fieldName = $this->CloseField->updateAll(array('Priority'=>"'".$data[$keys[$i]]."'"),array("id"=>$keys[$i]));
			}
			$this->set('data',$keys);
			$this->redirect(array('controller' => 'CloseFields', 'action' => 'index'));
                        }
                        else{
                           $this->redirect(array('controller' => 'CloseFields', 'action' => 'index')); 
                        }
		}
		

	}
    
    public function edit(){
        $this->layout="ajax";
        if(isset($_REQUEST['id'])){
            $ClientId = $this->Session->read('companyid');
            $id = $_REQUEST['id'];    
            $fieldName = $this->CloseField->find('first',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'id'=>$id),'order'=>array('Priority')));
            $this->set('fieldName',$fieldName);
            if($fieldName['CloseField']['FieldType'] =='DropDown'){
                $this->set("fieldValue", $this->CloseFieldValue->find('all',array('fields'=>array('id','FieldId','FieldValueName'),'conditions'=>array('FieldId'=>$id))));
            }
        }
    }
    
    public function update() {
        $this->layout='user';
	if($this->request->is('Post')){
            $ClientId = $this->Session->read('companyid');
	    if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
            $id = $this->request->data['id'];
            $data = $this->request->data['CloseFields'];
            
            if($data['FieldName']==''){$this->Session->setFlash("FieldName is Not Blank"); }
            else if($data['FieldType']=='') {$this->Session->setFlash("FieldType is Not Choosen");}
			
            else
	    {
            
                $result['FieldName'] = "'".addslashes($data['FieldName'])."'";
		$result['FieldType'] = "'".addslashes($data['FieldType'])."'";
		if($data['FieldValidation']==''){$data['FieldValidation']="Char";}
		$result['FieldValidation'] = "'".addslashes($data['FieldValidation'])."'";
		$result['RequiredCheck'] = "'".addslashes($data['RequiredCheck'])."'";
		
                $data1 = $this->CloseField->find('first',array('fields'=>array('FieldType'),'conditions'=>array('id'=>$id)));
		if($data['FieldType'] == 'DropDown' && $data1['CloseField']['FieldType'] == 'DropDown')
              {
                  $old_value = $this->request->data['oldValues'];
                  $keys = array_keys($old_value);
                  for($i =0; $i<Count($keys); $i++)
                  {
                      //print_r($keys[$i]); die;
                    $this->CloseFieldValue->updateAll(array('FieldValueName'=>"'".$old_value[$keys[$i]]."'"),array('id'=>$keys[$i],'FieldId'=>$id));
                  } 
              }
              		
		$this->CloseField->updateAll($result,array('id'=>$id));
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
                        $this->CloseFieldValue->saveAll($result);
                        unset($result);
                    }
		}
            }			
	}
        $this->Session->setFlash("Fields update successfully.");
	$this->redirect(array('controller' => 'CloseFields', 'action' => 'index'));
	}   
        
        
       public function delete_clientfields(){
        $id=$this->request->query['id'];
        $fieldStatus="D";
        if($this->CloseField->updateAll(array('FieldStatus'=>"'".$fieldStatus."'"),array('id'=>$id,'ClientId' => $this->Session->read('companyid')))){
            $this->CloseFieldValue->updateAll(array('FieldStatus'=>"'".$fieldStatus."'"),array('FieldId'=>$id,'ClientId' => $this->Session->read('companyid')));
        }
        $this->redirect(array('action' => 'index'));
    }
        
	
}
?>