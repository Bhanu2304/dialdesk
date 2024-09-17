<?php
class AdminobecrEditsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('ObecrMaster','CampaignName','RegistrationMaster');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
                        'index',
			'add',
			'view',
			'update_category',
			'update_type',
			'get_label2_sub1',
			'get_label2_sub2',
			'get_label3_sub1',
			'get_label4_sub1',
			'update_sub_type1',
			'update_sub_type2',
			'update_sub_type3',
			'get_label2',
			'get_label3',
                        'get_campaign',
			'get_label4');
        
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }		
    }
    
    
    public function index() { 
        $this->layout='adminlayout';
        $this->set('client',$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"))));
        $Category = array();
        $campaign = array();
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $ClientId =$_REQUEST['id'];
            $campaignid=$_REQUEST['cmp'];
            $this->set('Category',$this->ObecrMaster->find('list',array('fields'=>array("Label","grp"),'conditions'=>array('Client'=>$ClientId,'CampaignId'=>$campaignid),'group'=>'Label')));
            $this->set('campaign',$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId))));
            $this->set('clientid',$ClientId); 
            $this->set('campaign_id',$campaignid);
        }
     
        if($this->request->is('Post')){
            $ClientId=$this->request->data['AdminobecrEdits']['clientID'];
            $campaignid=$this->request->data['AdminobecrEdits']['campaign'];
            $this->set('Category',$this->ObecrMaster->find('list',array('fields'=>array("Label","grp"),'conditions'=>array('Client'=>$ClientId,'CampaignId'=>$campaignid),'group'=>'Label')));
            $this->set('campaign',$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId))));
            $this->set('clientid',$ClientId); 
            $this->set('campaign_id',$campaignid); 
        }  
    }
    
     public function get_campaign(){
        $this->layout='ajax';
        if($_REQUEST['id']){
            $this->set('campaign',$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$_REQUEST['id']))));
        }
	}
    
	public function update_category(){
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
				$data = $this->request->data['AdminobecrEdits']; 
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->ObecrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			$this->Session->setFlash('Update Category Successfully.');
                        $this->redirect(array('action'=>'index','?' => array('id' =>$data['cid'],'cmp' =>$data['cmp'])));
                        
		}
	}
	
	public function update_type()
	{
		if($this->request->is("POST"))
		{
                   
                    
                    
			if(!empty($this->request->data))
			{
				$data = $this->request->data['AdminobecrEdits'];
				$this->set('data',$this->request->data);
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->ObecrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			$this->Session->setFlash('Update Type Successfully.');
                        $this->redirect(array('action'=>'index','?' => array('id' =>$data['cid'],'cmp' =>$data['cmp'])));
		}
	}

	public function update_sub_type1()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
				$data = $this->request->data['AdminobecrEdits'];
				$this->set('data',$this->request->data);
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->ObecrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			$this->Session->setFlash('Update Sub Type1 Successfully.');
                        $this->redirect(array('action'=>'index','?' => array('id' =>$data['cid'],'cmp' =>$data['cmp'])));
		}
	}

	public function update_sub_type2()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
				$data = $this->request->data['AdminobecrEdits'];
				$this->set('data',$this->request->data);
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->ObecrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			$this->Session->setFlash('Update Sub Type2 Successfully.');
                        $this->redirect(array('action'=>'index','?' => array('id' =>$data['cid'],'cmp' =>$data['cmp'])));
		}
	}

	public function update_sub_type3()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
				$data = $this->request->data['AdminobecrEdits'];
				$this->set('data',$this->request->data);
				$keys = array_keys($data);
				$count = count($data);
				for($i = 0; $i<$count; $i++)
				{
					$this->ObecrMaster->updateAll(array('ecrName'=>"'".$data[$keys[$i]]."'"),array('id'=>$keys[$i]));
				}
			}
			$this->Session->setFlash('Update Sub Type3 Successfully.');
                        $this->redirect(array('action'=>'index','?' => array('id' =>$data['cid'],'cmp' =>$data['cmp'])));
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
			$subType = $this->ObecrMaster->find('list',array('fields'=>array('ecrName'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			
			}
		}
	}

	public function get_label2_sub1()
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
	public function get_label2_sub2()
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

	public function get_label3()
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

	public function get_label3_sub1()
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
        
	
	public function get_label4_sub1()
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
	
	public function view(){
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');
		if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
		$Category =$this->ClientCategory->find('list',array('fields'=>array("id","name"),'conditions'=>array('Client'=>$ClientId)));
		$this->set('data',$this->ClientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
		//$Category = Set::combine($Category,'{n}.ClientCategory.id',array('{0}{1}','{n}.ClientCategory.ecrName','{n}.ClientCategory.Label'));
		$this->set('Category',$Category);
	}
	
	
}
?>