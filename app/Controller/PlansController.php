<?php
class PlansController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses = array('Plan');
    public function beforeFilter() {
        parent::beforeFilter();
		if($this->Session->check('companyid')){
        $this->Auth->allow(
			'index',
			'add',
			'view',
                        'edit',
                        'delete_plan',
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
	
	public function index() 
        {
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');
		if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
		$this->set('data',$this->Plan->find('all'));
                
                if($this->request->is('Post'))
                {
                    if(!empty($this->request->data))
                    {   $data = array();
                        //print_r($this->request->data);
                        $plan = $this->request->data;
                        $plan = $plan['Plans'];
                        
                        if($this->Plan->find('first',array('conditions'=>array('PlanName'=>$plan['PlanName']))))
                        {
                            $this->Session->setFlash('Plan Already Exists');
                        }
                        else
                        {
                            foreach($plan as $k=>$v)
                            {
                                $data[$k] = addslashes($v);
                            }
                        
                            if($this->Plan->save($data))
                            {
                                $this->redirect(array('controller'=>'Plans'));
                                $this->Session->setFlash('Plan Added Successfully');
                            }
                            else
                            {
                                $this->Session->setFlash('Plan Not Added, Please Try Again');
                            }
                        }
                    }
                }
	}
	
//	public function add() 
//        {
//            $this->layout='user';
//            if($this->request->is('Post'))
//            {
//            $ClientId = $this->Session->read('companyid');
//            if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
//            $this->set("data",$this->request->data);
//           // print_r($this->request->data); exit;
//            $data = $this->request->data['ClientFields'];
//			
//            if($data['FieldName']=='') 		{$this->Session->setFlash("FieldName is Not Blank"); }
//            else if($data['FieldType']=='') {$this->Session->setFlash("FieldType is Not Choosen");}
//			
//            else
//            {
//                $result['FieldName'] = addslashes($data['FieldName']);
//		$result['FieldType'] = addslashes($data['FieldType']);
//		if($data['FieldValidation']==''){$data['FieldValidation']="Char";}
//		$result['FieldValidation'] = addslashes($data['FieldValidation']);
//		$result['RequiredCheck'] = addslashes($data['RequiredCheck']);
//		$result['ClientId'] = addslashes($ClientId);
//                $data = $this->FieldCreation->find('first',array('fields'=>array("max"),'conditions'=>array('ClientId'=>$ClientId)));
//                $result['Priority'] = $data['FieldCreation']['max']+1;
//		$result['CreateDate'] = date("Y-m-d H:i:s");
//				
//		$this->FieldCreation->save($result);
//		unset($data);
//				
//            if($result['FieldType'] == 'DropDown')
//            { 
//                $id = $this->FieldCreation->getInsertId();
//                unset($result);$result = array();
//                $value = $this->request->data['down'];
//                $FieldValue = explode(',',$value);
//					
//                for($i=0; $i<count($FieldValue); $i++)
//                {
//                    $result[$i] =  array('FieldId'=>$id,'FieldValueName'=>addslashes($FieldValue[$i]),'ClientId'=>$ClientId);
//                }
//                $this->FieldValue->saveAll($result);
//                unset($result);
//            }
//            }			
//	}
//		$this->redirect(array('controller' => 'ClientFields', 'action' => 'index'));
//	}
	
	
	
//    public function view()
//        {
//            $ClientId = $this->Session->read('companyid');
//            if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}		
//            $fieldName = $this->FieldCreation->find('all',array('fields'=>array('id','FieldName','FieldType','FieldValidation','RequiredCheck','Priority'),'conditions'=>array('ClientId'=>$ClientId),'order'=>array('Priority')));		
//            $fieldValue = $this->FieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$ClientId),'group'=>'FieldId'));		
//
//            $this->set('fieldName',$fieldName);
//            $this->set('fieldValue',$fieldValue);		
//            $this->layout="user";
//	}

//	public function setPriority()
//	{
//		$this->layout="user";
//		if($this->request->is("Post")){
//			$ClientId = $this->Session->read('companyid');
//			if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
//                        if(!empty($this->request->data['ClientFields'])){
//			$data = array_filter($this->request->data['ClientFields']);
//			$count = count($data);
//			$keys = array_keys($data);
//			for($i=0; $i<$count; $i++)
//			{
//				$fieldName = $this->FieldCreation->updateAll(array('Priority'=>"'".$data[$keys[$i]]."'"),array("id"=>$keys[$i]));
//			}
//			$this->set('data',$keys);
//			$this->redirect(array('controller' => 'ClientFields', 'action' => 'index'));
//                        }
//                        else{
//                           $this->redirect(array('controller' => 'ClientFields', 'action' => 'index')); 
//                        }
//		}
//		
//
//	}
    
public function edit()
{
    $this->layout="ajax";
    if(isset($_REQUEST['id']))
    {
        $ClientId = $this->Session->read('companyid');
        $id = $_REQUEST['id'];    
        $plan = $this->Plan->find('first',array('conditions'=>array('id'=>$id)));
        $this->set('plan',$plan);
    }
}
    
    public function update() {
        $this->layout='user';
	if($this->request->is('Post'))
        {
            $ClientId = $this->Session->read('companyid');
	    if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
            $id = $this->request->data['id'];
            $data = $this->request->data['Plans'];
            
            foreach($data as $k=>$v):
                $data1[$k] = "'".addslashes($v)."'";
            endforeach;
		
            $data = $data1;
            if($this->Plan->updateAll($data,array('id'=>$id)))
            {$this->Session->setFlash("Record Updated Successfully");}
            else{$this->Session->setFlash("Record Not Updated Successfully, Please Try Again!");}
            unset($data);unset($data1);
	}
        $this->Session->setFlash("Fields update successfully.");
	$this->redirect(array('controller' => 'Plans', 'action' => 'index'));
	}   
        
        
       public function delete_plan(){
        $id=$this->request->query['id'];
        if($this->Plan->deleteAll(array('id'=>$id)))
        {
            $this->Session->setFlash("Record Deleted Successfully");
        }
        else
        {
            $this->Session->setFlash("Record Not Successfully,Please Try Again");
        }
        $this->redirect(array('action' => 'index'));
    }
        
	
}
?>