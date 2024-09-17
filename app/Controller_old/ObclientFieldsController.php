<?php
class ObclientFieldsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('ObfieldCreation','ObfieldValue','CampaignName');
	
    public function beforeFilter() {
        parent::beforeFilter();
		if($this->Session->check('companyid')){
        $this->Auth->allow(
			'index',
			'add',
			'view',
			'setPriority',
                        'delete_clientfields',
                        'edit',
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
        $this->set('Campaign',$this->CampaignName->find('list',array('fields'=>array('CampaignName'),'conditions'=>array('ClientId'=>$ClientId,'CampaignStatus'=>'A'))));
        
        if($this->request->is('Post')){
            $campaignid=$this->request->data['ObclientFields']['campaign'];
            $this->set('cmid',$campaignid);
            $this->set('data',$this->ObfieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'CampaignId'=>$campaignid,'FieldStatus'=>NULL))));
            $fieldName = $this->ObfieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'CampaignId'=>$campaignid,'FieldStatus'=>NULL),'order'=>array('Priority')));		
            $fieldValue = $this->ObfieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL),'group'=>'FieldId'));		
            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);
        }
        else if(isset($_REQUEST['cmid']) && $_REQUEST['cmid'] !=""){
            $campaignid=$_REQUEST['cmid'];
            $this->set('cmid',$campaignid);
            $this->set('data',$this->ObfieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'CampaignId'=>$campaignid,'FieldStatus'=>NULL))));
            $fieldName = $this->ObfieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'CampaignId'=>$campaignid,'FieldStatus'=>NULL),'order'=>array('Priority')));		
            $fieldValue = $this->ObfieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL),'group'=>'FieldId'));		
            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);
                
        }




    }
        
        /*
            public function edit(){
       $this->layout="ajax";
        $ClientId = $this->Session->read('companyid');
	if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
         $id = base64_decode($this->params->query['id']);         
        
        //$this->set('fieldName',$this->ObfieldCreation->find('first',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'id'=>$id),'order'=>array('Priority'))));
        $fieldName = $this->ObfieldCreation->find('first',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'id'=>$id),'order'=>array('Priority')));
        $this->set('fieldName',$fieldName);
        if($fieldName['ObfieldCreation']['FieldType'] =='DropDown')
        {
            $this->set("fieldValue", $this->ObfieldValue->find('all',array('fields'=>array('id','FieldId','FieldValueName'),'conditions'=>array('FieldId'=>$id))));
        }
    }
        */
        
        public function edit(){
        $this->layout="ajax";
        if(isset($_REQUEST['id'])){
            $ClientId = $this->Session->read('companyid');
            $id = $_REQUEST['id']; 
            $this->set('cmid',$_REQUEST['cmid']);
            $fieldName = $this->ObfieldCreation->find('first',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId,'id'=>$id),'order'=>array('Priority')));
            $this->set('fieldName',$fieldName);
            if($fieldName['ObfieldCreation']['FieldType'] =='DropDown'){
                $this->set("fieldValue", $this->ObfieldValue->find('all',array('fields'=>array('id','FieldId','FieldValueName'),'conditions'=>array('FieldId'=>$id))));
            }
        }
    }
        
        
        public function delete_clientfields(){
        $id=$this->request->query['id'];
        $CampaignId=$this->request->query['CampaignId'];
        
        /*
        if($this->ObfieldCreation->deleteAll(array('id'=>$id,'ClientId' => $this->Session->read('companyid')))){
            $this->ObfieldValue->deleteAll(array('FieldId'=>$id,'ClientId' => $this->Session->read('companyid')));
        }*/
        
        $fieldStatus="D";
        if($this->ObfieldCreation->updateAll(array('FieldStatus'=>"'".$fieldStatus."'"),array('id'=>$id,'ClientId' => $this->Session->read('companyid')))){
            $this->ObfieldValue->updateAll(array('FieldStatus'=>"'".$fieldStatus."'"),array('FieldId'=>$id,'ClientId' => $this->Session->read('companyid')));
        }
        
        
        $this->redirect(array('controller' => 'ObclientFields', 'action' => 'index','?'=>array('cmid'=>$CampaignId)));
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
            $data = $this->request->data['ObclientFields'];
            $CampaignId=$this->request->data['ObclientFields']['CampaignId'];
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
                $result['CampaignId'] = addslashes($data['CampaignId']);
                $data = $this->ObfieldCreation->find('first',array('fields'=>array("max"),'conditions'=>array('ClientId'=>$ClientId,'CampaignId'=>$CampaignId)));
                $data2 = $this->ObfieldCreation->find('first',array('fields'=>array("max2"),'conditions'=>array('ClientId'=>$ClientId,'CampaignId'=>$CampaignId)));
                $result['Priority'] = $data['ObfieldCreation']['max']+1;
                $result['fieldNumber'] = $data2['ObfieldCreation']['max2']+1;
		$result['CreateDate'] = date("Y-m-d H:i:s");
                
               
                
                
		$this->ObfieldCreation->save($result);
		unset($data);
				
            if($result['FieldType'] == 'DropDown')
            { 
                $id = $this->ObfieldCreation->getInsertId();
                unset($result);$result = array();
                $value = $this->request->data['down'];
                $FieldValue = explode(',',$value);
					
                for($i=0; $i<count($FieldValue); $i++)
                {
                    $result[$i] =  array('FieldId'=>$id,'FieldValueName'=>addslashes($FieldValue[$i]),'ClientId'=>$ClientId);
                }
                $this->ObfieldValue->saveAll($result);
                unset($result);
            }
            }			
	}
        $this->redirect(array('controller' => 'ObclientFields', 'action' => 'index','?'=>array('cmid'=>$CampaignId)));
	}
	
	
	
    public function view()
        {
            $ClientId = $this->Session->read('companyid');
            if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}		
            $fieldName = $this->ObfieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId),'order'=>array('Priority')));		
            $fieldValue = $this->ObfieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$ClientId),'group'=>'FieldId'));		

            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);		
            $this->layout="user";
	}

	public function setPriority()
	{
		$this->layout="user";
		if($this->request->is("Post"))
		{
                  
                    
                   $CampaignId =$this->request->data['ObclientFields']['CampaignId'];
                    
			$ClientId = $this->Session->read('companyid');
			if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
			$data = array_filter($this->request->data['ObclientFields']);
			$count = count($data);
			$keys = array_keys($data);
			for($i=0; $i<$count; $i++)
			{
				$fieldName = $this->ObfieldCreation->updateAll(array('Priority'=>"'".$data[$keys[$i]]."'"),array("id"=>$keys[$i]));
			}
			$this->set('data',$keys);
			$this->redirect(array('controller' => 'ObclientFields', 'action' => 'index','?'=>array('cmid'=>$CampaignId)));
		}
		

	}
    

    
    public function update() {
        $this->layout='user';
	if($this->request->is('Post')){
            $ClientId = $this->Session->read('companyid');
	    if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
                $id = $this->request->data['id'];
                $data = $this->request->data['ObclientFields'];
                $CampaignId=$data['CampaignId'];
              
                
            
            if($data['FieldName']=='') 		{$this->Session->setFlash("FieldName is Not Blank"); }
            else if($data['FieldType']=='') {$this->Session->setFlash("FieldType is Not Choosen");}
			
            else
	    {
            
                $result['FieldName'] = "'".addslashes($data['FieldName'])."'";
		$result['FieldType'] = "'".addslashes($data['FieldType'])."'";
		if($data['FieldValidation']==''){$data['FieldValidation']="Char";}
		$result['FieldValidation'] = "'".addslashes($data['FieldValidation'])."'";
		$result['RequiredCheck'] = "'".addslashes($data['RequiredCheck'])."'";
		
                $data1 = $this->ObfieldCreation->find('first',array('fields'=>array('FieldType'),'conditions'=>array('id'=>$id)));
		if($data['FieldType'] == 'DropDown' && $data1['ObfieldCreation']['FieldType'] == 'DropDown')
              {
                  $old_value = $this->request->data['oldValues'];
                  $keys = array_keys($old_value);
                  for($i =0; $i<Count($keys); $i++)
                  {
                      //print_r($keys[$i]); die;
                    $this->ObfieldValue->updateAll(array('FieldValueName'=>"'".$old_value[$keys[$i]]."'"),array('id'=>$keys[$i],'FieldId'=>$id));
                  } 
              }
              		
		$this->ObfieldCreation->updateAll($result,array('id'=>$id));
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
                        $this->ObfieldValue->saveAll($result);
                        unset($result);
                    }
		}
            }			
	}
        $this->Session->setFlash("Fields update successfully.");
        $this->redirect(array('controller' => 'ObclientFields', 'action' => 'index','?'=>array('cmid'=>$CampaignId)));
        
        
        
	}  
        
        
        
        
        
        
        
        
        
	
}
?>