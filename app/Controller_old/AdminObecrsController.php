<?php
class AdminObecrsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('ObclientCategory','CampaignName','RegistrationMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
                        'index',
			'add',
			'view',
			'create_category',
			'create_type',
			'create_sub_type1',
			'create_sub_type2',
			'create_sub_type3',
                        'get_label1',
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
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
        $this->set('client',$client);
        $this->set('clientid',array());
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $ClientId =$_REQUEST['id'];
            $Category =$this->ObclientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Client'=>$ClientId,'label'=>'1')));
            $this->set('data',$this->ObclientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
            $this->set('Category',$Category);
            $this->set('Campaign',$this->CampaignName->find('list',array('fields'=>array('CampaignName'),'conditions'=>array('ClientId'=>$ClientId))));
            $this->set('clientid',$ClientId);  
        }
        
        if($this->request->is('Post')){
            $data=$this->request->data['AdminObecrs'];
            $ClientId =$data['clientID'];
            $Category =$this->ObclientCategory->find('list',array('fields'=>array("id","ecrName"),'conditions'=>array('Client'=>$ClientId,'label'=>'1')));
            $this->set('data',$this->ObclientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
            $this->set('Category',$Category);
            $this->set('Campaign',$this->CampaignName->find('list',array('fields'=>array('CampaignName'),'conditions'=>array('ClientId'=>$ClientId))));
            $this->set('clientid',$ClientId);  
        }  
    }
	
    public function create_category(){
        if($this->request->is("POST")){
            if(!empty($this->request->data)){
                $cid=$this->request->data['AdminObecrs']['cid'];
                $data['Label'] = '1';
                $data['createdate'] = date('Y-m-d H:i:s');
                $data['Client'] = $cid;
                $data['ecrName'] = addslashes($this->request->data['AdminObecrs']['category']);
                $data['CampaignId'] = addslashes($this->request->data['AdminObecrs']['campaign']);
                $this->ObclientCategory->save($data);
            }
            $this->Session->setFlash('Category Create Successfully.');
            $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
        }
    }
	
    public function create_type()
    {
            if($this->request->is("POST"))
            {
                    if(!empty($this->request->data))
                    {
                    $cid=$this->request->data['AdminObecrs']['cid'];
                    $data['Label'] = '2';
                    $data['createdate'] = date('Y-m-d H:i:s');
                    $data['Client'] = $cid;
                    $data['ecrName'] = addslashes($this->request->data['AdminObecrs']['type']);
                    $data['parent_id'] = addslashes($this->request->data['AdminObecrs']['parent1']);
                    $data['CampaignId'] = addslashes($this->request->data['AdminObecrs']['campaign1']);
                    $this->ObclientCategory->save($data);
                    }

                    $this->Session->setFlash('Category Type Successfully.');
                    $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
            }
    }

	public function create_sub_type1()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
                        $cid=$this->request->data['AdminObecrs']['cid'];
			$data['Label'] = '3';
			$data['createdate'] = date('Y-m-d H:i:s');
			$data['Client'] = $cid;
			$data['parent_id'] = addslashes($this->request->data['AdminObecrs']['parent']);			
			$data['ecrName'] = addslashes($this->request->data['AdminObecrs']['sub_type1']);
                        $data['CampaignId'] = addslashes($this->request->data['AdminObecrs']['campaign2']);
			$this->ObclientCategory->save($data);
			}
			$this->Session->setFlash('Category Type1 Successfully.');
                        $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
		}
	}

	public function create_sub_type2()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
                        $cid=$this->request->data['AdminObecrs']['cid'];
			$data['Label'] = '4';
			$data['createdate'] = date('Y-m-d H:i:s');			
			$data['Client'] = $cid;
			$data['ecrName'] = addslashes($this->request->data['AdminObecrs']['sub_type2']);
			$data['parent_id'] = addslashes($this->request->data['AdminObecrs']['parent3']);
                         $data['CampaignId'] = addslashes($this->request->data['AdminObecrs']['campaign3']);
			$this->ObclientCategory->save($data);
			}
			$this->Session->setFlash('Category Type2 Successfully.');
                        $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
		}
	}

	public function create_sub_type3()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
                        $cid=$this->request->data['AdminObecrs']['cid'];
			$data['Label'] = '5';
			$data['createdate'] = date('Y-m-d H:i:s');
			$data['Client'] = $cid;
			$data['parent_id'] = addslashes($this->request->data['AdminObecrs']['parent4']);			
			$data['ecrName'] = addslashes($this->request->data['AdminObecrs']['sub_type3']);
                         $data['CampaignId'] = addslashes($this->request->data['AdminObecrs']['campaign4']);
			$this->ObclientCategory->save($data);
			}
			$this->Session->setFlash('Category Type3 Successfully.');
                        $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
		}
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
        
        public function get_campaign(){
            $this->layout='ajax';
            if($_REQUEST['id']){
                $this->set('campaign',$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$_REQUEST['id']))));
            }
	}
	
	public function view(){
            $this->layout='adminlayout';
            $this->set('client',$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"))));
            $data = array();
            if($this->request->is('Post')){
                $ClientId=$this->request->data['AdminObecrs']['clientID'];
                $campaignid=$this->request->data['AdminObecrs']['campaign'];
                $this->set('data',$this->ObclientCategory->find('all',array('fields'=>array('Label','ecrName','id','parent_id'),'conditions'=>array('Client'=>$ClientId,'CampaignId'=>$campaignid)))); 
                $this->set('campaign',$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId))));
                $this->set('clientid',$ClientId);
                $this->set('campaign_id',$campaignid); 
            }
            else{
                $this->set('data',$data);
            }
	}
	
	
}
?>