<?php
class OutboundsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('CampaignName','PurposeCampaign','EcrCampaign','CampaignMaster','Obup','ObAllocationMaster','CampaignNameType');
      
	
    public function beforeFilter() {
        parent::beforeFilter();
		if($this->Session->check('companyid'))
		{
        $this->Auth->allow(
			'index',
			'create_campaign',
			'add_campaign',
			'import_data',
			'view_campaign',
			'delete_campaign',
			 'prcampaign',
			'create_category',
			'create_type',
			'create_sub_type1',
			'create_sub_type2',
			'create_sub_type3',
			'get_label1',
			'get_label2',
			'get_label3',
			'get_label4');
		}
		else
		{$this->Auth->deny('index',
			'add',
			'view'
			);
 		}
    }
	
	public function index() {
            $this->layout='user';
            $ClientId = $this->Session->read('companyid');	
	}
	
	public function add_campaign() {
            $this->layout='user';
            $ClientId=$this->Session->read('companyid');
            $cmp=$this->CampaignName->find('all',array('conditions'=>array('ClientId'=>$ClientId,'CampaignStatus'=>'A')));
            
            $CampaignList =$this->CampaignNameType->find('list',array('fields'=>array("CampaignType","CampaignType"),'conditions'=>array('ClientId'=>$ClientId)));
            
          
            
            $this->set('CampaignList',$CampaignList);
            
            $campaign_header=array();
            foreach($cmp as $row){
                $cmpId=$row['CampaignName']['id'];
                $cmpparName=$row['CampaignName']['CampaignParentName'];
                $cmpName=$row['CampaignName']['CampaignName'];
                $TotalCount=$row['CampaignName']['TotalCount'];
                $field=$this->get_header_field($ClientId,$cmpId,$TotalCount);
                $campaign_header[]=array('cmpparName'=>$cmpparName,'campaign'=>$cmpName,'field'=>$field,'campaignid'=>$cmpId);
            }

            $this->set('campaign_header',$campaign_header);
                
            if($this->request->is("POST") && !empty($this->request->data)){
                
                $data['CampaignName']['CreationDate'] = date('Y-m-d H:i:s');
                $data['CampaignName']['ClientId'] = $this->Session->read('companyid');
                $data['CampaignName']['CampaignName'] = addslashes($this->request->data['Outbounds']['CampaignName']);
                $data['CampaignName']['CampaignParentName'] = addslashes($this->request->data['Outbounds']['CampaignParentName']);
                
                $CampaignType = addslashes($this->request->data['Outbounds']['CampaignType']);
		$fieldName=$this->request->data['field_name'];
  
                
                if($CampaignType !="clientUser" && empty($fieldName[0])){
                   $this->Session->setFlash("Field name is required for manage by dialdesk."); 
                   $this->redirect(array('action' => 'add_campaign'));
                }
                
                
                $k=1;
                foreach($fieldName as $val){
                    $data['CampaignName']['Field'.$k]=$val;
                 $k++;   
                }
                
                $data['CampaignName']['TotalCount']=count($fieldName);
                
                
                if($this->Session->read('role') =="client"){
                    $update_user=$this->Session->read('email');
                }
                else if($this->Session->read('role') =="agent"){
                    $update_user=$this->Session->read('agent_username');
                }
                if($this->Session->read('role') =="admin"){
                    $update_user=$this->Session->read('admin_name');
                }
                
               


                if($this->CampaignName->find('first',array('fields'=>array('id'),'conditions'=>$data))){
                    $this->Session->setFlash("Already Exists at Given Campaign Name");
                    $this->redirect(array('action' => 'add_campaign'));
                }else{
                    
                    $data['CampaignName']['update_user']=$update_user;
                    
                    $this->CampaignName->saveAll($data);
                    
                    // Start Manage by client 
                    if($CampaignType =="clientUser"){
                        $Campaign_id=$this->CampaignName->getLastInsertId();
                        $allocation=array(
                            'ClientId'=>$ClientId,
                            'CampaignId'=>$Campaign_id,
                            'AllocationName'=>$data['CampaignName']['CampaignName'].' Allocation',
                            'CreateDate'=>date('Y-m-d H:i:s'),
                            'TotalCount'=>'0',
                            'upload_type'=>'clientUser');
                        
                        $this->ObAllocationMaster->save($allocation);
                    }		                
                    // End Manage By client 
                    
                    $this->Session->setFlash("Campaign name create successfully.");
                    $this->redirect(array('action' => 'add_campaign'));
                }	
			
            }
        }


	
	public function get_header_field($ClientId,$Campaign,$TotalCount){
		$TotalField=array();
		for($i=1;$i<=$TotalCount;$i++){$TotalField[]="Field$i";}
		$tArr=$this->CampaignName->find('first',array('fields'=>$TotalField,'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));
		return $tArr['CampaignName'];
	}

	public function delete_campaign(){
            $id  = $this->request->query['id'];
            
            if($this->Session->read('role') =="client"){
                $update_user=$this->Session->read('email');
            }
            else if($this->Session->read('role') =="agent"){
                $update_user=$this->Session->read('agent_username');
            }
            if($this->Session->read('role') =="admin"){
                $update_user=$this->Session->read('admin_name');
            }
            
            $update_date=date('Y-m-d H:i:s');
            $status="D";
            
            $dataArr=array('CampaignStatus'=>"'".$status."'",'update_user'=>"'".$update_user."'",'update_date'=>"'".$update_date."'");	
            $this->CampaignName->updateAll($dataArr,array('id'=>$id,'ClientId' => $this->Session->read('companyid')));
            //$this->CampaignName->deleteAll(array('id'=>$id,'ClientId' => $this->Session->read('companyid')));
            $this->redirect(array('action' => 'add_campaign'));
	}
		
	public function prcampaign() {
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');
		if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
		//$Campaign =$this->PurposeCampaign->find('list',array('fields'=>array("id","Campaign"),'conditions'=>array('Client'=>$ClientId,'Label'=>'1')));
		$this->set('Campaign',$this->CampaignName->find('list',array('fields'=>array('CampaignName'),'conditions'=>array('ClientId'=>$ClientId))));
		
	}
	public function import_data() {
		$this->layout='user';
		 
		
	}
	
	
	
 	
	public function create_category()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '1';
			//$category = $this->request->data['Outbounds'];
			$data['createdate'] = date('Y-m-d H:i:s');
			$data['ClientId'] = $this->Session->read('companyid');
			$data['CampaignId'] = addslashes($this->request->data['Outbounds']['campaign']);
			$data['Ecr'] = addslashes($this->request->data['Outbounds']['category']);
			$this->PurposeCampaign->save($data);
			}
			$this->redirect(array('action'=>'prcampaign'));
		}
	}
	
	
	public function create_type()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '2';
			$data['createdate'] = date('Y-m-d H:i:s');
			$data['ClientId'] = $this->Session->read('companyid');
			$data['Ecr'] = addslashes($this->request->data['Outbounds']['type']);
			$data['Parent_id'] = addslashes($this->request->data['Outbounds']['parent1']);
			$data['CampaignId'] = addslashes($this->request->data['Outbounds']['campaign1']);
			$this->PurposeCampaign->save($data);
			}
			$this->redirect(array('action'=>'prcampaign'));
		}
	}




	public function create_sub_type1()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '3';
			$data['createdate'] = date('Y-m-d H:i:s');
			//$category = $this->request->data['Outbounds'];
			$data['ClientId'] = $this->Session->read('companyid');
			$data['Ecr'] = addslashes($this->request->data['Outbounds']['sub_type1']);
			$data['Parent_id'] = addslashes($this->request->data['Outbounds']['parent']);
			$data['CampaignId'] = addslashes($this->request->data['Outbounds']['campaign2']);			
			$this->PurposeCampaign->save($data);
			}
			$this->redirect(array('action'=>'prcampaign'));
		}
	}

	public function create_sub_type2()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '4';
			$data['createdate'] = date('Y-m-d H:i:s');			
			$data['ClientId'] = $this->Session->read('companyid');
			$data['Ecr'] = addslashes($this->request->data['Outbounds']['sub_type2']);
			$data['Parent_id'] = addslashes($this->request->data['Outbounds']['parent3']);
			$data['CampaignId'] = addslashes($this->request->data['Outbounds']['campaign']);
			$this->PurposeCampaign->save($data);
			}
			$this->redirect(array('action'=>'prcampaign'));
		}
	}

	public function create_sub_type3()
	{
		if($this->request->is("POST"))
		{
			if(!empty($this->request->data))
			{
			$data['Label'] = '5';
			//$category = $this->request->data['Outbounds'];
			$data['createdate'] = date('Y-m-d H:i:s');
			$data['ClientId'] = $this->Session->read('companyid');
			$data['Ecr'] = addslashes($this->request->data['Outbounds']['sub_type3']);	
			$data['Parent_id'] = addslashes($this->request->data['Outbounds']['parent4']);			
		    $data['CampaignId'] = addslashes($this->request->data['Outbounds']['campaign']);
			$this->PurposeCampaign->save($data);
			}
			$this->redirect(array('action'=>'prcampaign'));
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
			$subType = $this->PurposeCampaign->find('list',array('fields'=>array('Ecr'),'conditions'=>$conditions));
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
			$conditions['Parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->PurposeCampaign->find('list',array('fields'=>array('Ecr'),'conditions'=>$conditions));
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
			$conditions['Parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->PurposeCampaign->find('list',array('fields'=>array('Ecr'),'conditions'=>$conditions));
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
			$conditions['Parent_id'] = $this->request->data['parent_id'];
			$type = $this->request->data['type'];
			$subType = $this->PurposeCampaign->find('list',array('fields'=>array('Ecr'),'conditions'=>$conditions));
			$this->set('data',$subType); 
			$this->set('type',$type); 
			}
		}
	}
	
	/*public function add() {
		$this->layout='user';
		if($this->request->is('Post'))
		{
			$ClientId = $this->Session->read('companyid');
			if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
			$Outbounds = $this->request->data['Outbounds']['Category'];
			$Campaign = $this->request->data['Outbounds']['Text'];
			$data = array();
			$data['Campaign'] = addslashes($Campaign);
			$data['createdate'] = date('Y-m-d H:i:s');
			$flag = false;
			if($Outbounds=='')
			{
				$data['Label'] = '1';
				$flag = true;
			}
			else if($Outbounds=='' && $Campaign=='')
			{
				$this->Session->setFlash("Please Add Valid Field Name");
			}
			else if($this->PurposeCampaign->find('first',array('fields'=>array('id'),'conditions'=>array('parent_id'=>$Outbounds,'Campaign'=>$Campaign,'Client'=>$ClientId))))
			{
				$this->Session->setFlash("Already Exists at Given Label");
			}
			else
			{
				$dataX = $this->PurposeCampaign->find('first',array('fields'=>array('Label','id'),'conditions'=>array('id'=>$Outbounds,'Client'=>$ClientId)));
				$this->set('data',$Outbounds);
				$data['Label'] = addslashes($dataX['PurposeCampaign']['Label']+1);
				$data['parent_id'] = addslashes($dataX['PurposeCampaign']['id']);
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
				$this->PurposeCampaign->save($data);
				$this->Session->setFlash("Added");
			}
			$this->redirect(array('action'=>'index'));
		}
	}*/
	
	public function view(){
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');
		if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
		$Category =$this->PurposeCampaign->find('list',array('fields'=>array("id","name"),'conditions'=>array('Client'=>$ClientId)));
		$this->set('data',$this->PurposeCampaign->find('all',array('fields'=>array('Label','Campaign','id','parent_id'),'conditions'=>array('Client'=>$ClientId))));
		//$Category = Set::combine($Category,'{n}.PurposeCampaign.id',array('{0}{1}','{n}.PurposeCampaign.Campaign','{n}.PurposeCampaign.Label'));
		$this->set('Category',$Category);
	}
	
	

	
}
?>