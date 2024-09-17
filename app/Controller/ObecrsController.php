<?php
class ObecrsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('ObclientCategory','CampaignName','ObecrMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
		if($this->Session->check('companyid'))
		{
        $this->Auth->allow(
			'index',
			'index2',
			'add',
			'view',
			'create_category',
                        'update_category',
			'create_type',
                        'update_type',
			'create_sub_type1',
                        'update_sub_type1',
			'create_sub_type2',
                        'update_sub_type2',
			'create_sub_type3',
                        'update_sub_type3',
                        'delete_ecr',
                        'get_label1',
                        'edit_label2',
                        'edit_label2_sub1',
			'get_label2',
			'get_label3',
                        'edit_label3',
                        'edit_label2_sub2',
                        'edit_label3_sub1',
                        'edit_label3_sub2',
                        'edit_label4_sub1',
			'get_label4');
		}
		else
		{$this->Auth->deny('index',
			'add',
			'view');}
    }
	
	public function index() {
            $this->layout='user';
            $ClientId = $this->Session->read('companyid');
            if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
            $Category =$this->ObclientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Client'=>$ClientId,'label'=>'1')));
            $this->set('data',$this->ObclientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
            $this->set('Category',$Category);
            $this->set('Campaign',$this->CampaignName->find('list',array('fields'=>array('CampaignName'),'conditions'=>array('ClientId'=>$ClientId,'CampaignStatus'=>'A'))));

            if(isset($_REQUEST['cms']) && $_REQUEST['cms'] !=""){
                $this->set('cms',$_REQUEST['cms']);
            }
            
            // View ECR TREE 
            $data = array();
            $Category1 = array();
            if($this->request->is('Post')){
                $campaignid=$this->request->data['Obecr']['campaign'];
                $this->set('data',$this->ObclientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId,'CampaignId'=>$campaignid)))); 
                $this->set('cmid',$this->request->data['Obecr']['campaignid']);
                
                       
                $Category1 =$this->ObecrMaster->find('list',array('fields'=>array("Label","grp"),'conditions'=>array('Client'=>$ClientId,'CampaignId'=>$campaignid),'group'=>'Label'));
                $this->set('Category1',$Category1);
                
            }
            else if(isset($_REQUEST['cmid']) && $_REQUEST['cmid'] !=""){
                $this->set('data',$this->ObclientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId,'CampaignId'=>$_REQUEST['cmid'])))); 
                $this->set('cmid',$_REQUEST['cmid']);
                
                $Category1 =$this->ObecrMaster->find('list',array('fields'=>array("Label","grp"),'conditions'=>array('Client'=>$ClientId,'CampaignId'=>$_REQUEST['cmid']),'group'=>'Label'));
                $this->set('Category1',$Category1);
                
            }
            else{
                $this->set('data',$data);
               $this->set('Category1',$Category1);
            } 
                  
        }

		public function index2() {
            $this->layout='popuser';
            $ClientId = $this->Session->read('companyid');
            if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
            $Category =$this->ObclientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Client'=>$ClientId,'label'=>'1')));
            $this->set('data',$this->ObclientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
            $this->set('Category',$Category);
            $this->set('Campaign',$this->CampaignName->find('list',array('fields'=>array('CampaignName'),'conditions'=>array('ClientId'=>$ClientId,'CampaignStatus'=>'A'))));

            if(isset($_REQUEST['cms']) && $_REQUEST['cms'] !=""){
                $this->set('cms',$_REQUEST['cms']);
            }
            
            // View ECR TREE 
            $data = array();
            $Category1 = array();
            if($this->request->is('Post')){
                $campaignid=$this->request->data['Obecr']['campaign'];
                $this->set('data',$this->ObclientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId,'CampaignId'=>$campaignid)))); 
                $this->set('cmid',$this->request->data['Obecr']['campaignid']);
                
                       
                $Category1 =$this->ObecrMaster->find('list',array('fields'=>array("Label","grp"),'conditions'=>array('Client'=>$ClientId,'CampaignId'=>$campaignid),'group'=>'Label'));
                $this->set('Category1',$Category1);
                
            }
            else if(isset($_REQUEST['cmid']) && $_REQUEST['cmid'] !=""){
                $this->set('data',$this->ObclientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId,'CampaignId'=>$_REQUEST['cmid'])))); 
                $this->set('cmid',$_REQUEST['cmid']);
                
                $Category1 =$this->ObecrMaster->find('list',array('fields'=>array("Label","grp"),'conditions'=>array('Client'=>$ClientId,'CampaignId'=>$_REQUEST['cmid']),'group'=>'Label'));
                $this->set('Category1',$Category1);
                
            }
            else{
                $this->set('data',$data);
               $this->set('Category1',$Category1);
            } 
                  
        }
        
        

         public function delete_ecr(){
            $id=$this->request->query['id'];
            if($this->ObclientCategory->deleteAll(array('id'=>$id,'Client' => $this->Session->read('companyid')))){
                $this->ObclientCategory->deleteAll(array('parent_id'=>$id,'Client' => $this->Session->read('companyid')));
            }
            $this->redirect(array('action' => 'index','?'=>array('cmid'=>$_REQUEST['cmid'])));
        }
        

	public function create_category()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '1';
			$data['createdate'] = date('Y-m-d H:i:s');
			$data['Client'] = $this->Session->read('companyid');
			$data['ecrName'] = addslashes($this->request->data['Obecr']['category']);
                        $data['CampaignId'] = addslashes($this->request->data['Obecr']['campaign']);
			$this->ObclientCategory->save($data);
                        $this->Session->setFlash("Scenario created successfully.");
			}
                        $this->redirect(array('action' => 'index','?'=>array('cms'=>'0','cmid'=>$this->request->data['Obecr']['campaign'])));
		}
	}
        
        public function update_category(){
            if($this->request->is("POST")){
                if(!empty($this->request->data)){
                        $data = $this->request->data['Obecrs'];
                        $keys = array_keys($data);
                        $count = count($data);
                        for($i = 0; $i<$count; $i++)
                        {
                            $this->ObecrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
                        }
                }


            }
            echo "1";die;
        }
        

	public function create_type()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '2';
			$data['createdate'] = date('Y-m-d H:i:s');
			$data['Client'] = $this->Session->read('companyid');
			$data['ecrName'] = addslashes($this->request->data['Obecr']['type']);
			$data['parent_id'] = addslashes($this->request->data['Obecr']['parent1']);
                        $data['CampaignId'] = addslashes($this->request->data['Obecr']['campaign1']);
			$this->ObclientCategory->save($data);
                        $this->Session->setFlash("Sub Scenario 1 created successfully.");
			}
			$this->redirect(array('action' => 'index','?'=>array('cms'=>'1','cmid'=>$this->request->data['Obecr']['campaign1'])));
		}
	}
        
        	public function update_type()
	{
		if($this->request->is("POST"))
		{
  
			if(!empty($this->request->data))
			{
				$data = $this->request->data['Obecrs'];
				$this->set('data',$this->request->data);
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->ObecrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			
		}
                 echo "1";die;
	}

	public function create_sub_type1()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '3';
			$data['createdate'] = date('Y-m-d H:i:s');
			//$category = $this->request->data['Obecr'];
			$data['Client'] = $this->Session->read('companyid');
			$data['parent_id'] = addslashes($this->request->data['Obecr']['parent']);			
			$data['ecrName'] = addslashes($this->request->data['Obecr']['sub_type1']);
                        $data['CampaignId'] = addslashes($this->request->data['Obecr']['campaign2']);
			$this->ObclientCategory->save($data);
                        $this->Session->setFlash("Sub Scenario 2 created successfully.");
			}
			$this->redirect(array('action' => 'index','?'=>array('cms'=>'2','cmid'=>$this->request->data['Obecr']['campaign2'])));
		}
	}
        
        
        public function update_sub_type1()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
				$data = $this->request->data['Obecrs'];
				$this->set('data',$this->request->data);
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->ObecrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			
		}
                 echo "1";die;
	}

	public function create_sub_type2()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '4';
			$data['createdate'] = date('Y-m-d H:i:s');			
			$data['Client'] = $this->Session->read('companyid');
			$data['ecrName'] = addslashes($this->request->data['Obecr']['sub_type2']);
			$data['parent_id'] = addslashes($this->request->data['Obecr']['parent3']);
                         $data['CampaignId'] = addslashes($this->request->data['Obecr']['campaign3']);
			$this->ObclientCategory->save($data);
                        $this->Session->setFlash("Sub Scenario 3 created successfully.");
			}
			$this->redirect(array('action' => 'index','?'=>array('cms'=>'3','cmid'=>$this->request->data['Obecr']['campaign3'])));
		}
	}
        
        	public function update_sub_type2()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
				$data = $this->request->data['Obecrs'];
				$this->set('data',$this->request->data);
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->ObecrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			
		}
                echo "1";die;
	}

	public function create_sub_type3()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '5';
			//$category = $this->request->data['Obecr'];
			$data['createdate'] = date('Y-m-d H:i:s');
			$data['Client'] = $this->Session->read('companyid');
			$data['parent_id'] = addslashes($this->request->data['Obecr']['parent4']);			
			$data['ecrName'] = addslashes($this->request->data['Obecr']['sub_type3']);
                         $data['CampaignId'] = addslashes($this->request->data['Obecr']['campaign4']);
			$this->ObclientCategory->save($data);
                         $this->Session->setFlash("Sub Scenario 4 created successfully.");
			}
			$this->redirect(array('action' => 'index','?'=>array('cms'=>'4','cmid'=>$this->request->data['Obecr']['campaign4'])));
		}
	}
        
        public function update_sub_type3()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
				$data = $this->request->data['Obecrs'];
				$this->set('data',$this->request->data);
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->ObecrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			
		}
                echo "1";die;
	}
        
        
        public function get_label1()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '1';
			$conditions['CampaignId'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->ObclientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
        
	
	public function get_label2()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '2';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->ObclientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
        
        public function edit_label2(){
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '2';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$subType = $this->ObecrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			
			}
		}
	}
        
        
        public function edit_label2_sub1()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '2';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->ObecrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}

	public function get_label3()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '3';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->ObclientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
        
        public function edit_label3()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '3';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$subType = $this->ObecrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			
			}
		}
	}
        
        
        
 
	public function edit_label2_sub2()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '3';
			$conditions['parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->ObecrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
        
        public function edit_label3_sub1()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '4';
			$conditions['parent_id'] = $this->request->data['parent_id'];			
			$subType = $this->ObecrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 			
			}
		}
	}
        
           public function edit_label3_sub2(){
            $this->layout="ajax";
            if($this->request->is('POST')){
                if(!empty($this->request->data)){
                    $conditions['Label'] = '4';
                    $conditions['parent_id'] = $this->request->data['parent_id'];
                    $type = $this->request->data['type'];
                    $subType = $this->ObecrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
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
			$subType = $this->ObclientCategory->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
        
               public function edit_label4_sub1()
	{
		$this->layout="ajax";
		if($this->request->is('POST'))
		{
			if(!empty($this->request->data))
			{
			$conditions['Label'] = '5';
			$conditions['parent_id'] = $this->request->data['parent_id'];			
			$subType = $this->ObecrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			}
		}
	}
	
	/*public function add() {
		$this->layout='user';
		if($this->request->is('Post'))
		{
			$ClientId = $this->Session->read('companyid');
			if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
			$ecr = $this->request->data['Ecr']['Category'];
			$ecrName = $this->request->data['Ecr']['Text'];
			$data = array();
			$data['ecrName'] = addslashes($ecrName);
			$data['createdate'] = date('Y-m-d H:i:s');
			$flag = false;
			if($ecr=='')
			{
				$data['Label'] = '1';
				$flag = true;
			}
			else if($ecr=='' && $ecrName=='')
			{
				$this->Session->setFlash("Please Add Valid Field Name");
			}
			else if($this->ObclientCategory->find('first',array('fields'=>array('id'),'conditions'=>array('parent_id'=>$ecr,'ecrName'=>$ecrName,'Client'=>$ClientId))))
			{
				$this->Session->setFlash("Already Exists at Given Label");
			}
			else
			{
				$dataX = $this->ObclientCategory->find('first',array('fields'=>array('Label','id'),'conditions'=>array('id'=>$ecr,'Client'=>$ClientId)));
				$this->set('data',$ecr);
				$data['Label'] = addslashes($dataX['ObclientCategory']['Label']+1);
				$data['parent_id'] = addslashes($dataX['ObclientCategory']['id']);
				if($data['Label']==6)
				{
					$this->Session->setFlash("Label Not More Than 5");
					$flag = false;
				}
				else
				{$flag = true;}
				
			}
			if($flag)
			{
				$data['Client'] = addslashes($ClientId);
				$this->ObclientCategory->save($data);
				$this->Session->setFlash("Added");
			}
			$this->redirect(array('action'=>'index'));
		}
	}*/
	
	public function view(){
            $this->layout='user';
            $ClientId = $this->Session->read('companyid');
            if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}		
            $this->set('Campaign',$this->CampaignName->find('list',array('fields'=>array('CampaignName'),'conditions'=>array('ClientId'=>$ClientId))));
            
            $data = array();
            if($this->request->is('Post')){
                $campaignid=$this->request->data['Obecr']['campaign'];
                $this->set('data',$this->ObclientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId,'CampaignId'=>$campaignid))));  
            }
            else{
                $this->set('data',$data);
            }
	}
	
	
}
?>