<?php
class ObCreationsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('ObFieldValue','ObField');
	
    public function beforeFilter() {
        parent::beforeFilter();
		if($this->Session->check('companyid')){
        $this->Auth->allow(
			'index',
			'add',
			'view',
			'setPriority');
		}
		else
		{
			$this->Auth->deny('index',
			'add',
			'view',
			'setPriority');
			}
    }
	
	public function index() {
		$this->layout='user';
		//$ClientId = $this->Session->read('companyid');
		//$Category =$this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('Client'=>$ClientId)));
		//$this->set('data',$this->ClientCategory->find('all',array('fields'=>array('Label','Plan','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
		//$Category = Set::combine($Category,'{n}.ClientCategory.id',array('{0}{1}','{n}.ClientCategory.Plan','{n}.ClientCategory.Label'));
		$ClientId = $this->Session->read('companyid');
		if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
		$this->set('data',$this->ObField->find('all',array('conditions'=>array('ClientId'=>$ClientId))));
	}
	
	public function add() {
		$this->layout='user';
		if($this->request->is('Post'))
		{
			$ClientId = $this->Session->read('companyid');
			if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
			$this->set("data",$this->request->data);
			$data = $this->request->data['ObCreations'];
			
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
				$result['createdate'] = date("Y-m-d H:i:s");
				
				$this->ObField->save($result);
				unset($data);
				
				if($result['FieldType'] == 'DropDown')
				{
					$id = $this->ObField->getInsertId();
					unset($result);$result = array();
					$value = $this->request->data['down'];
					$FieldValue = explode(',',$value);
					
					for($i=0; $i<count($FieldValue); $i++)
					{
						$result[$i] =  array('FieldId'=>$id,'FieldValueName'=>addslashes($FieldValue[$i]),'ClientId'=>$ClientId);
					}
					$this->ObFieldValue->saveAll($result);
					unset($result);
				}
			}			
		}
		$this->redirect(array('controller' => 'ObCreations', 'action' => 'index'));
	}
	
	
	
	public function view(){
		$this->layout="user";
		$ClientId = $this->Session->read('companyid');
		if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}		
		$fieldName = $this->ObField->find('all',array('conditions'=>array('ClientId'=>$ClientId),'order'=>array('Priority')));		
		$fieldValue = $this->ObFieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$ClientId),'group'=>'FieldId'));		

		$this->set('fieldName',$fieldName);
		$this->set('fieldValue',$fieldValue);		
		
	}

	public function setPriority()
	{
		$this->layout="user";
		if($this->request->is("Post"))
		{
			$ClientId = $this->Session->read('companyid');
			if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
			$data = array_filter($this->request->data['ObCreations']);
			$count = count($data);
			$keys = array_keys($data);
			for($i=0; $i<$count; $i++)
			{
				$fieldName = $this->ObField->updateAll(array('Priority'=>"'".$data[$keys[$i]]."'"),array("id"=>$keys[$i]));
			}
			$this->set('data',$keys);
			$this->redirect(array('controller' => 'ObCreations', 'action' => 'index'));
		}
		

	}
	
	
}
?>