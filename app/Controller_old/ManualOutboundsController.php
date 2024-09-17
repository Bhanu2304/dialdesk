<?php
class ManualOutboundsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('FieldMaster','FieldValue','EcrMaster','CallMaster','OutboundMaster','ClientMaster','ObField','ObFieldValue','ObEcr','CampaignName','ObCampaignDataMaster','ObAllocationMaster','TrainingMaster','Matrix','SMSText','Crone','vicidialCloserLog','OutboundCloseLoopMaster','LogincreationMaster');
         
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('outboundhome','save_tagging_outbound','allocation_name','search_result','index','movedirectly','getOBECRName');            
        if(!$this->Session->check("companyid")){
                return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
	
    public function allocation_name(){  
        $this->layout = 'ajax';
        if($this->request->is('POST')){
            $clientId = $this->Session->read("companyid");
            $campaignId = $this->request->data['campaignId'];
            $this->set('allocationName',$this->ObAllocationMaster->find('list',array('fields'=>array('AllocationName'),'conditions'=>array('ClientId'=>$clientId, 'CampaignId'=>$campaignId))));
        }
    }
    
    
    public function index(){
        $this->layout='user';
        $clientId = $this->Session->read("companyid");
        
        if($this->Session->read("role") =="agent"){
            $username=$this->Session->read("agent_username");
            $UserArr = $this->LogincreationMaster->find('first',array('conditions' =>array('username'=>$username,'create_id' =>$clientId)));
            $outboundAccess=explode(',',$UserArr['LogincreationMaster']['outbound_access']);
            $this->set('campaignName',$this->CampaignName->find('list',array('fields'=>array('CampaignName'),'conditions'=>array('ClientId'=>$clientId,'id'=>$outboundAccess))));
        }
        else{
            $this->set('campaignName',$this->CampaignName->find('list',array('fields'=>array('CampaignName'),'conditions'=>array('ClientId'=>$clientId))));
        }
        if($this->request->is('POST')){
            $data=$this->request->data; 
            $this->Session->write('CampaignId',$data['ManualOutbounds']['campaignId']);
            $this->Session->write('Allocation',$data['ManualOutbounds']['allocationId']);
            $this->redirect(array('controller'=>'ManualOutbounds','action'=>'outboundhome'));	
        } 
    }
    
        
        
    public function outboundhome(){
        $this->layout="user";
        $clientId = $this->Session->read("companyid");		
        $allocationID = $this->Session->read('Allocation');								
        $CampaignId = $this->Session->read('CampaignId');
        
        $fieldName = $this->ObField->find('all',array('conditions'=>array('ClientId'=>$clientId,'CampaignId'=>$CampaignId,'FieldStatus'=>NULL),'order'=>array('Priority')));
        $this->set('fieldName',$fieldName);
            
        $fieldSearch = $this->ObField->find('list',array('fields'=>array('fieldNumber'),'conditions'=>array('CampaignId'=>$CampaignId,'FieldStatus'=>NULL),'order'=>array('Priority')));
        foreach($fieldSearch as $f)
        {
            $headervalue[] = 'Field'.$f; 
        }
        $this->set('headervalue',$headervalue);
            
            
        $ObFieldValue = $this->ObFieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
        $this->set('ObFieldValue',$ObFieldValue);	

        $ecr = $this->ObEcr->find('list',array('fields'=>array('id','ecr'),'conditions'=>array('Client'=>$clientId,'CampaignId'=>$CampaignId,'Label'=>'1')));
        $this->set('ecr',$ecr);

        $ecrNew = $this->ObEcr->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId,'CampaignId'=>$CampaignId),'group'=>'Label','order'=>array('Label'=>'asc')));						
        $this->set('ecrNew',$ecrNew);
            
            
           
        $tdata = $this->TrainingMaster->find('all',array('conditions'=>array('ClientId'=>$clientId)));
        if(!empty($tdata)){
            $this->set('tdata',$tdata);
        }
        else{
            $this->set('tdata',array());
        }
               
            $AssignData=$this->ObCampaignDataMaster->find('all',array('conditions'=>array('AllocationId'=>$allocationID,'AgentId'=>$this->Session->read('userid'),'DataStatus'=>null)));
            $this->set('totaldata',count($AssignData));

            // History display code
            
            
                
            $history = $this->OutboundMaster->find('all',array('order'=>array('SrNo'=>'desc'),'conditions'=>array('MSISDN'=>$this->Session->read('userphone'),'ClientId'=>$clientId,'AllocationId'=>$allocationID)));
            $this->set('history',$history);
            unset($fieldName);unset($ObFieldValue);unset($ecr);unset($data);unset($history);
            
            
		
		$search = array();
		$fields = array();
		if($this->request->is('POST'))
		{
			
                //print_r($this->request->data);
               
                        $fields[0] = 'CallDate';
                        $data = array_filter($this->request->data['Category']);
                        $fields2 = array_keys($this->request->data['Category']);
                        for($i = 0; $i<count($fields2); $i++)
                       {
                           $fields[] = 'Category'.$fields2[$i];
                       }
                        foreach($data as $s=>$v)
			{
				if(!empty($v))
				{
					$search['OutboundMaster.Category'.$s.' LIKE'] = '%'.$v.'%';
				}
			}
                        
                       
			
			$data = array_filter($this->request->data['FieldMaster']);
                        $fields3 = array_keys($this->request->data['FieldMaster']);
                        for($i = 0; $i<count($fields3); $i++)
                       {
                           $fields[] = $fields3[$i];
                       }
			
			foreach($data as $s=>$v)
			{
				if(!empty($v))
				{
					$search['OutboundMaster.'.$s.' LIKE'] = '%'.$v.'%';
				}
			}
                        
                        
                       
                       
                       
			$search = $this->OutboundMaster->find('all',array('fields'=>$fields,'conditions'=>array('or'=>$search)));
                        
		}
                
               
                
		$this->set('search',$search);	
	}
        
        
        
        
        public function search_result(){      
            $this->layout = 'ajax';
            $clientId = $this->Session->read("companyid");		
            $allocationID = $this->Session->read('Allocation');								
            $CampaignId = $this->Session->read('CampaignId');
            
            
            $fieldName = $this->ObField->find('all',array('conditions'=>array('ClientId'=>$clientId,'CampaignId'=>$CampaignId,'FieldStatus'=>NULL),'order'=>array('Priority')));
            $this->set('fieldName',$fieldName);	
            
            $ObFieldValue = $this->ObFieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
            $this->set('ObFieldValue',$ObFieldValue);	
             
            $ecr = $this->ObEcr->find('list',array('fields'=>array('id','ecrName'),'conditions'=>array('Client'=>$clientId,'CampaignId'=>$CampaignId,'Label'=>'1')));
            $this->set('ecr',$ecr);
            
            $ecrNew = $this->ObEcr->find('list',array('fields'=>array('label','ecrName'),'conditions'=>array('Client'=>$clientId,'CampaignId'=>$CampaignId),'group'=>'Label','order'=>array('Label'=>'asc')));						
            $this->set('ecrNew',$ecrNew);
            
           
            // History display code
                
            $history = $this->OutboundMaster->find('all',array('order'=>array('CallDate'=>'desc'),'conditions'=>array('AllocationId'=>$allocationID)));
            $this->set('history',$history);
            unset($fieldName);unset($ObFieldValue);unset($ecr);unset($data);unset($history);
            
            
		
		$search = array();
		$fields = array();
		if($this->request->is('POST')){

                        $fields[0] = 'CallDate';
                        $data = array_filter($this->request->data['Category']);
                        $fields2 = array_keys($this->request->data['Category']);
                        for($i = 0; $i<count($fields2); $i++)
                       {
                           $fields[] = 'Category'.$fields2[$i];
                       }
                        foreach($data as $s=>$v)
			{
				if(!empty($v))
				{
					$search['OutboundMaster.Category'.$s.' LIKE'] = '%'.$v.'%';
				}
			}
                        
                       
                   
			
			$data1 = array_filter($this->request->data['ManualOutbounds']);
                        $fields3 = array_keys($this->request->data['ManualOutbounds']);
                         isset ($data1['CallDate']) ? $data1['CallDate'] =date("Y-m-d",strtotime($data1['CallDate'])) : "";
                        for($i = 0; $i<count($fields3); $i++)
                       {
                           $fields[] = $fields3[$i];
                       }
			
			foreach($data1 as $s=>$v)
			{
				if(!empty($v))
				{
					$search['OutboundMaster.'.$s.' LIKE'] = '%'.$v.'%';
				}
			}
                        
                        
                       if(!empty($data) || !empty($data1)){
                           $search = $this->OutboundMaster->find('all',array('fields'=>$fields,'conditions'=>array('or'=>$search)));
                           $this->set('search',$search);	
                       }
                       else{
                           echo "";die;
                       }
                       
                        
		}
        }
        
        
        
    public function getOBECRName($name,$clientId){
            $data =$this->ObEcr->find('first',array('fields'=>array('id'),'conditions'=>array('Client'=>$clientId,'ecrName'=>$name)));  
            return $data['ObEcr']['id'];
        }    
        
	public function save_tagging_outbound(){
            if($this->request->is("POST")){ 
                $clientId = $this->Session->read("companyid");
                $condition = array();
                $data['OutboundMaster'] = $this->request->data['ManualOutbounds'];
                $this->Session->write('userphone',$data['OutboundMaster']['MSISDN']);
                $CampaignId = $this->Session->read('CampaignId');
                $condition[] = $this->getOBECRName($data['OutboundMaster']['Category1'],$this->Session->read("companyid"));
                
                $category_1=$data['OutboundMaster']['Category1'];
                $category_2="";
                $category_3="";
                $category_4="";
                $category_5="";
                 
                if(isset($data['OutboundMaster']['Category2'])){
                    $exp=explode('@@',$data['OutboundMaster']['Category2']);
                    $data['OutboundMaster']['Category2']=$exp[1];
                    $condition[]=$exp[0];
                    $category_2=$data['OutboundMaster']['Category2'];
                }
                 if(isset($data['OutboundMaster']['Category3'])){
                    $exp=explode('@@',$data['OutboundMaster']['Category3']);
                    $data['OutboundMaster']['Category3']=$exp[1];
                    $condition[]=$exp[0];
                    $category_3=$data['OutboundMaster']['Category3'];
                }
                 if(isset($data['OutboundMaster']['Category4'])){
                    $exp=explode('@@',$data['OutboundMaster']['Category4']);
                    $data['OutboundMaster']['Category4']=$exp[1];
                    $condition[]=$exp[0];
                    $category_4=$data['OutboundMaster']['Category4'];
                }
                 if(isset($data['OutboundMaster']['Category5'])){
                    $exp=explode('@@',$data['OutboundMaster']['Category5']);
                    $data['OutboundMaster']['Category5']=$exp[1];
                    $condition[]=$exp[0];
                    $category_5=$data['OutboundMaster']['Category5'];
                }
                
                // Close By System---------------------------
                
                $clqry="SELECT id,close_loop_category,close_loop
                FROM outbound_closeloop_master 
                WHERE CONCAT(IF(CategoryName1='All',1,CONCAT('$category_1',IF(CategoryName2='All',1,CONCAT('$category_2',IF(CategoryName3='All',1,
                CONCAT('$category_3',IF(CategoryName4='All',1,
                CONCAT('$category_4',IF(CategoryName5='All',1,'$category_5')
                ))))))))) = CONCAT(IF(CategoryName1='All',1,CONCAT(IFNULL(CategoryName1,''),IF(CategoryName2='All',1,
                CONCAT(IFNULL(CategoryName2,''),IF(CategoryName3='All',1,
                CONCAT(IFNULL(CategoryName3,''),IF(CategoryName4='All',1,
                CONCAT(IFNULL(CategoryName4,''),IF(CategoryName5='All',1,IFNULL(CategoryName5,''))
                )))))))))
                AND client_id='$clientId' AND CampaignId='$CampaignId' AND close_loop='system' AND label='1' LIMIT 1";
                
                $clArr=$this->OutboundCloseLoopMaster->query($clqry);
                
                $closeId=$clArr[0]['outbound_closeloop_master']['id'];
                $closeLoop=$clArr[0]['outbound_closeloop_master']['close_loop'];
                $closeLoopCat=$clArr[0]['outbound_closeloop_master']['close_loop_category'];
                $clSubArr=$this->OutboundCloseLoopMaster->query("SELECT close_loop_category  FROM outbound_closeloop_master WHERE client_id='$clientId' AND close_loop='system' AND parent_id='$closeId' LIMIT 1");
                $closeLoopSubCat=$clSubArr[0]['outbound_closeloop_master']['close_loop_category'];
                
                   
                if(!empty($clArr)){
                    if($closeLoop ==="system" ){
                        $data['OutboundMaster']['close_loop'] = $closeLoop;
                        $data['OutboundMaster']['CloseLoopCate1'] =$closeLoopCat;
                        $data['OutboundMaster']['CloseLoopCate2'] =$closeLoopSubCat;
                        $data['OutboundMaster']['UpdateDate'] = date('Y-m-d H:i:s');
                        $data['OutboundMaster']['CloseLoopingDate'] = date('Y-m-d H:i:s');  
                    }
                }
                
                // Close By Manual---------------------------
                  
                $clqry1="SELECT id,close_loop_category,close_loop
                FROM outbound_closeloop_master 
                WHERE CONCAT(IF(CategoryName1='All',1,CONCAT('$category_1',IF(CategoryName2='All',1,CONCAT('$category_2',IF(CategoryName3='All',1,
                CONCAT('$category_3',IF(CategoryName4='All',1,
                CONCAT('$category_4',IF(CategoryName5='All',1,'$category_5')
                ))))))))) = CONCAT(IF(CategoryName1='All',1,CONCAT(IFNULL(CategoryName1,''),IF(CategoryName2='All',1,
                CONCAT(IFNULL(CategoryName2,''),IF(CategoryName3='All',1,
                CONCAT(IFNULL(CategoryName3,''),IF(CategoryName4='All',1,
                CONCAT(IFNULL(CategoryName4,''),IF(CategoryName5='All',1,IFNULL(CategoryName5,''))
                )))))))))
                AND client_id='$clientId' AND CampaignId='$CampaignId' AND close_loop='manual' AND label='1' LIMIT 1";
                
                $clArr1=$this->OutboundCloseLoopMaster->query($clqry1);
                
                if(empty($clArr) && empty($clArr1)){
                    $data['OutboundMaster']['close_loop'] = 'system';
                    $data['OutboundMaster']['CloseLoopCate1'] ='Close By System';
                    $data['OutboundMaster']['CloseLoopCate2'] ='';
                    $data['OutboundMaster']['UpdateDate'] = date('Y-m-d H:i:s');
                    $data['OutboundMaster']['CloseLoopingDate'] = date('Y-m-d H:i:s');  
                }
                
                $id = $data['OutboundMaster']['DataId'];
                $data['OutboundMaster']['CallDate'] = date('Y-m-d H:i:s');
                $data['OutboundMaster']['ClientId'] = $this->Session->read("companyid");
                $data['OutboundMaster']['AllocationId'] = $this->Session->read('Allocation');
                $data['OutboundMaster']['CallType'] = "Outbound";
                
                
                if($this->Session->read("role") =="client"){
                    $data['OutboundMaster']['callcreated'] ='Client - '.$this->Session->read("email");
                }
                else if($this->Session->read("role") =="agent"){
                    $data['OutboundMaster']['callcreated'] ='Client - '.$this->Session->read("agent_username");
                }
                            
                $dataSource = $this->OutboundMaster->getDataSource();
                $dataSource ->begin();
                $flag = $this->OutboundMaster->save($data);
                $lastid=$this->OutboundMaster->getLastInsertId();
                $SrNumber=$this->OutboundMaster->find('first',array('fields'=>array('SrNo'),'conditions'=>array('Id'=>$lastid)));       
                $sr=$SrNumber['OutboundMaster']['SrNo'];
                
                /*
                if($flag){
                    $dataSource->commit();
                    $condition= implode(',', $condition);
                          
                    $matrix = $this->Matrix->query("SELECT alertType,alertOn,personName,email,mobileno,tat
                    FROM tbl_matrix WHERE clientId='$clientId' AND CONCAT(category, IF(`type` IS NOT NULL OR `type` !='',CONCAT(',',`type`),''),
                    IF(`subtype` IS NOT NULL OR `subtype` !='',CONCAT(',',subtype),''), IF(`subtype1` IS NOT NULL OR `subtype1` !='',CONCAT(',',subtype1),''),
                    IF(`subtype2` IS NOT NULL OR `subtype2` !='',CONCAT(',',subtype2),'')) = '$condition'");
       
       
                   
                    $smsText = $this->SMSText->query("SELECT alertType,smsText
                    FROM tbl_sms WHERE clientId='$clientId' AND CONCAT(category, IF(`type` IS NOT NULL OR `type` !='',CONCAT(',',`type`),''),
                    IF(`subtype` IS NOT NULL OR `subtype` !='',CONCAT(',',subtype),''), IF(`subtype1` IS NOT NULL OR `subtype1` !='',CONCAT(',',subtype1),''),
                    IF(`subtype2` IS NOT NULL OR `subtype2` !='',CONCAT(',',subtype2),'')) = '$condition'");
                   
                    if(!empty($matrix)){
                        $i=0;
                        foreach($matrix as $m):
                            $crone[$i]['clientId'] = $clientId;
                            $crone[$i]['bpo'] = 1;
                            $crone[$i]['data_id'] = $this->OutboundMaster->id;
                            $crone[$i]['alertType'] = $m['tbl_matrix']['alertType'];
                            $crone[$i]['alertOn'] = $m['tbl_matrix']['alertOn'];
                            $crone[$i]['personName'] = $m['tbl_matrix']['personName'];
                            $crone[$i]['email'] = $m['tbl_matrix']['email'];
                            $crone[$i]['mobileno'] = $m['tbl_matrix']['mobileno'];
                            $crone[$i]['tat'] = $m['tbl_matrix']['tat'];
                            $crone[$i]['msg'] = $smsText[0]['tbl_sms']['smsText'];
                            $crone[$i++]['createdate'] = date('Y-m-d H:i:s');
                        endforeach;

                        $this->Crone->saveAll($crone);
                    }
                }
                else{$dataSource->rollback();}
                */
                $this->Session->setFlash("Your SrNo $sr is successfully created.");
                $this->redirect(array('action'=>'outboundhome'));
            }
	}
        
}