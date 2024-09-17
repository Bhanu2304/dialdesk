<?php
class AdminEcrsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('ClientCategory','ecrNameMaster','RegistrationMaster','EcrMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('update_ecr','update_category','update_type','update_sub_type1','update_sub_type2','update_sub_type3','editget_label2','editget_label2_sub1','editget_label2_sub2','editget_label3','editget_label3_sub1','editget_label4_sub1','create_category','create_type','create_sub_type1','create_sub_type2','create_sub_type3','get_label2','get_label3', 'get_label4');
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }		
    }
    
    public function index() {
        $this->layout='adminlayout';
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
        $this->set('client',$client);
        $this->set('clientid',array());
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $ClientId =$_REQUEST['id'];
            $Category =$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Client'=>$ClientId,'label'=>'1')));
            $this->set('data',$this->ClientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
            $this->set('Category',$Category);
            $this->set('clientid',$ClientId);  
        }
        
        if($this->request->is('Post')){
            $data=$this->request->data['AdminEcrs'];
            $ClientId =$data['clientID'];
            $Category =$this->ClientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Client'=>$ClientId,'label'=>'1')));
            $this->set('data',$this->ClientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
            $this->set('Category',$Category);
            $this->set('clientid',$ClientId);  
        }
    }
    
    public function create_category(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $cid=$this->request->data['AdminEcrs']['cid'];
                $data['Label'] = '1';
                $data['createdate'] = date('Y-m-d H:i:s');
                $data['Client'] = $cid;
                $data['ecrName'] = addslashes($this->request->data['AdminEcrs']['category']);
                $this->ClientCategory->save($data);
            }
            $this->Session->setFlash('Category Create Successfully.');
            $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
        }
    }
	
    public function create_type(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $cid=$this->request->data['AdminEcrs']['cid'];
                $data['Label'] = '2';
                $data['createdate'] = date('Y-m-d H:i:s');
                $data['Client'] = $cid;
                $data['ecrName'] = addslashes($this->request->data['AdminEcrs']['type']);
                $data['parent_id'] = addslashes($this->request->data['AdminEcrs']['category']);
                $this->ClientCategory->save($data);
            }
            $this->Session->setFlash('Create Type Successfully.');
            $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
        }
    }

    public function create_sub_type1(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $cid=$this->request->data['AdminEcrs']['cid'];
                $data['Label'] = '3';
                $data['createdate'] = date('Y-m-d H:i:s');
                $data['Client'] = $cid;
                $data['parent_id'] = addslashes($this->request->data['AdminEcrs']['Type']);			
                $data['ecrName'] = addslashes($this->request->data['AdminEcrs']['sub_type1']);
                $this->ClientCategory->save($data);
            }
            $this->Session->setFlash('Create Sub Type1 Successfully.');
            $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
        }
    }

    public function create_sub_type2(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $cid=$this->request->data['AdminEcrs']['cid'];
                $data['Label'] = '4';
                $data['createdate'] = date('Y-m-d H:i:s');			
                $data['Client'] = $cid;
                $data['ecrName'] = addslashes($this->request->data['AdminEcrs']['sub_type2']);
                $data['parent_id'] = addslashes($this->request->data['AdminEcrs']['sub_type1']);
                $this->ClientCategory->save($data);
            }
            $this->Session->setFlash('Create Sub Type2 Successfully.');
            $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
        }
    }

    public function create_sub_type3(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $cid=$this->request->data['AdminEcrs']['cid'];
                $data['Label'] = '5';
                $data['createdate'] = date('Y-m-d H:i:s');
                $data['Client'] = $cid;
                $data['parent_id'] = addslashes($this->request->data['AdminEcrs']['sub_type2']);			
                $data['ecrName'] = addslashes($this->request->data['AdminEcrs']['sub_type3']);			
                $this->ClientCategory->save($data);
            }
            $this->Session->setFlash('Create Sub Type3 Successfully.');
            $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
        }
    }
	
    public function get_label2(){
        $this->layout="ajax";
        if($this->request->is('POST')){
            if(!empty($this->request->data)){
                $conditions['Label'] = '2';
                $conditions['parent_id'] = $this->request->data['parent_id'];
                $type = $this->request->data['type'];
                $subType = $this->ClientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                $this->set('data',$subType); 
                $this->set('type',$type); 
            }
        }
    }

    public function get_label3(){
        $this->layout="ajax";
        if($this->request->is('POST')){
            if(!empty($this->request->data)){
                $conditions['Label'] = '3';
                $conditions['parent_id'] = $this->request->data['parent_id'];
                $type = $this->request->data['type'];
                $subType = $this->ClientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
                $this->set('data',$subType); 
                $this->set('type',$type); 
            }
        }
    }
	
	public function get_label4()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '4';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->ClientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
        
        
    //Edit Module 
        
    public function update_ecr() {
        $this->layout='adminlayout';
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
        $this->set('client',$client);
        $this->set('clientid',array());
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $ClientId =$_REQUEST['id'];
             $Category =$this->EcrMaster->find('list',array('fields'=>array("Label","grp"),'conditions'=>array('Client'=>$ClientId),'group'=>'Label'));
            $this->set('data',$this->ClientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
            $this->set('Category',$Category);
            $this->set('clientid',$ClientId);  
        }
        
        if($this->request->is('Post')){
            $data=$this->request->data['AdminEcrs'];
            $ClientId =$data['clientID'];
            $Category =$this->EcrMaster->find('list',array('fields'=>array("Label","grp"),'conditions'=>array('Client'=>$ClientId),'group'=>'Label'));
            $this->set('data',$this->ClientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
            $this->set('Category',$Category);
            $this->set('clientid',$ClientId);  
        }
    }
    
    public function update_category(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $cid=$this->request->data['AdminEcrs']['cid'];
                $data = $this->request->data['AdminEcrs'];
                $keys = array_keys($data);
                $count = count($data);
                for($i = 0; $i<$count; $i++){
                    $this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
                }
            }
            $this->Session->setFlash('Category Update Successfully.');
            $this->redirect(array('action'=>'update_ecr','?' => array('id' =>$cid)));
	}
    }
    
    public function update_type(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $cid=$this->request->data['AdminEcrs']['cid'];
                $data = $this->request->data['AdminEcrs'];
                $this->set('data',$this->request->data);
                $keys = array_keys($data);
                $count = count($data);
                for($i = 0; $i<$count; $i++){
                        $this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
                }
            }
            $this->Session->setFlash('Type Update Successfully.');
            $this->redirect(array('action'=>'update_ecr','?' => array('id' =>$cid)));
        }
    }

    
	public function update_sub_type1()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
				$cid=$this->request->data['AdminEcrs']['cid'];
                                $data = $this->request->data['AdminEcrs'];
				$this->set('data',$this->request->data);
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			$this->Session->setFlash('Sub Type1 Update Successfully.');
                        $this->redirect(array('action'=>'update_ecr','?' => array('id' =>$cid)));
		}
	}

       
	public function update_sub_type2()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
				$cid=$this->request->data['AdminEcrs']['cid'];
                                $data = $this->request->data['AdminEcrs'];
				$this->set('data',$this->request->data);
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			$this->Session->setFlash('Sub Type2 Update Successfully.');
                        $this->redirect(array('action'=>'update_ecr','?' => array('id' =>$cid)));
		}
	}
 
	public function update_sub_type3()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
				$cid=$this->request->data['AdminEcrs']['cid'];
                                $data = $this->request->data['AdminEcrs'];
				$this->set('data',$this->request->data);
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->EcrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			$this->Session->setFlash('Sub Type3 Update Successfully.');
                        $this->redirect(array('action'=>'update_ecr','?' => array('id' =>$cid)));
		}
	}
	
	public function editget_label2()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '2';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			
			}
		}
	}

	public function editget_label2_sub1()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '2';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
        
	public function editget_label2_sub2()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '3';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}

	public function editget_label3()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '3';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			
			}
		}
	}

	public function editget_label3_sub1()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '4';
			$conditions['parent_id'] = $this->request->data['parent_id'];			
			$subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 			
			}
		}
	}
	
	public function editget_label4_sub1()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '5';
			$conditions['parent_id'] = $this->request->data['parent_id'];			
			$subType = $this->EcrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			}
		}
	}
        
        
}
?>