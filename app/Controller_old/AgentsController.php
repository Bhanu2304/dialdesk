<?php
class AgentsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('FieldMaster','FieldValue','EcrMaster','CallMaster','ClientMaster','ObField','ObFieldValue','ObEcr','CampaignName','ObCampaignDataMaster','ObAllocationMaster','UploadExistingBase','CloseLoopMaster','TimePeriod','SocialMediaFeedback','MailMaster');
	
        //public $uses=array('FieldMaster','FieldValue','EcrMaster','CallMaster','OutboundMaster','ClientMaster','ObField','ObFieldValue','ObEcr','CampaignName','ObCampaignDataMaster','ObAllocationMaster');
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('save_tagging','campaignName','index','getECRName','search_result','getChild','integerToRoman','dlfaddress');
               
		if(!$this->Session->check("companyid")){
			return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
		}
    }
	
	public function index() {
            $this->layout='user';
            $clientId = $this->Session->read("companyid");
            
            if(isset($_REQUEST['postid']) && isset($_REQUEST['posttype']) && $_REQUEST['postid'] !="" && $_REQUEST['posttype'] !=""){
                $this->set('postid',$_REQUEST['postid']);
                $this->set('posttype',$_REQUEST['posttype']);
            }
            
            $fieldName = $this->FieldMaster->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
            $fieldValue = $this->FieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
            
            $ecr = $this->EcrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId),'group'=>'Label','order'=>array('Label'=>'asc')));		
 
            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);
            $this->set('ecr',$ecr);

		$data['leadId'] = $this->params->query("lead_id");
		$data['phone_code'] = $this->params->query("phone_code");
		$data['phone_number'] = '';
		$data['title'] = $this->params->query("title");
		$data['firstName'] = $this->params->query("first_name");
		$data['lastName'] = $this->params->query("last_name");
		$data['dob'] = $this->params->query("date_of_birth");
		$data['email'] = $this->params->query("email");
		$data['address1'] = $this->params->query("address1");
		$data['address2'] = $this->params->query("address2");
		$data['address3'] = $this->params->query("address3");
		$data['city'] = $this->params->query("city");
		$data['state'] = $this->params->query("state");
		$data['postal_code'] = $this->params->query("postal_code");
		
		$this->set('data',$data);
		$history = array();
		$history = $this->CallMaster->find('all',array('conditions'=>array('MSISDN'=>$this->Session->read("pno"),'ClientId'=>$clientId),'order'=>array('CallDate'=>'desc')));
		
                $fieldSearch = $this->FieldMaster->find('list',array('fields'=>array('fieldNumber'),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
                foreach($fieldSearch as $f)
                {
                    $headervalue[] = 'Field'.$f; 
                }
                $this->set('headervalue',$headervalue);
                
                $this->set('history',$history);
		unset($fieldName);unset($fieldValue);unset($ecr);unset($data);unset($history);
	}
        
       
            public function search_result(){ 
            $this->layout = 'ajax';
            $search = array();
            $fields = array();
           
            if($this->request->is('POST')){
                $clientId = $this->Session->read("companyid");
                $fields = array_keys($this->request->data['Agents']);
                $data = array_filter($this->request->data['Agents']);  
     
                isset ($data['CallDate']) ? $data['CallDate'] =date("Y-m-d",strtotime($data['CallDate'])) : "";

                 if(!empty($data)){
                    foreach($data as $s=>$v){
                    if(!empty($v)){
                        $search['CallMaster.'.$s.' LIKE'] = '%'.$v.'%';
                    }
                }
                
             
                
                $search = $this->CallMaster->find('all',array('fields'=>$fields,'conditions'=>array('or'=>$search,'ClientId'=>$this->Session->read('companyid'))));       
               
               
                
                if(!empty($search)){
                $this->set('search',$search); 
                }
                else{
                echo "";die;
            }
            }
            else{
                echo "";die;
            }
            $fieldName = $this->FieldMaster->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
            $ecr = $this->EcrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId),'group'=>'Label','order'=>array('Label'=>'asc')));		
            $this->set('fieldName',$fieldName);
            $this->set('ecr',$ecr);
                
            }  
        }
        
        public function save_tagging(){
            if($this->request->is("POST")){
                $clientId = $this->Session->read("companyid");
                
                $data['CallMaster'] = $this->request->data['Agents'];
                $data['CallMaster']['CallDate'] = date('Y-m-d H:i:s');
                
                $this->Session->write('pno',$data['CallMaster']['MSISDN']);
                
                if($this->Session->read("role") ==="client"){
                    $agentid     = $clientId;
                    $callcreated = "Client - ".$this->Session->read("email");          
                }
                if($this->Session->read("role") ==="agent"){
                    $agentid     = $this->Session->read("agent_userid");
                    $callcreated = "User - ".$this->Session->read("agent_username");          
                }

                $data['CallMaster']['AgentId'] = $agentid;
                $data['CallMaster']['callcreated']=$callcreated;
                $data['CallMaster']['ClientId'] = $clientId;
                $data['CallMaster']['CallType'] = 'Manual';
                
                $category_1="";
                $category_2="";
                $category_3="";
                $category_4="";
                $category_5="";
                
                if(isset($data['CallMaster']['Category1'])){
                  $category_1=$data['CallMaster']['Category1'];
                  $mArr['Category1']=$this->getECRName($data['CallMaster']['Category1'],$clientId);
                    
                }
                if(isset($data['CallMaster']['Category2'])){
                    $exp=explode('@@',$data['CallMaster']['Category2']);
                    $mArr['Category2']=$exp[0];
                    $data['CallMaster']['Category2']=$exp[1];
                    $category_2=$exp[1];
                }
                 if(isset($data['CallMaster']['Category3'])){
                    $exp=explode('@@',$data['CallMaster']['Category3']);
                    $mArr['Category3']=$exp[0];
                    $data['CallMaster']['Category3']=$exp[1];
                    $category_3=$exp[1];
                    
                }
                 if(isset($data['CallMaster']['Category4'])){
                    $exp=explode('@@',$data['CallMaster']['Category4']);
                    $mArr['Category4']=$exp[0];
                    $data['CallMaster']['Category4']=$exp[1];
                    $category_4=$exp[1];
                    
                }
                 if(isset($data['CallMaster']['Category5'])){
                    $exp=explode('@@',$data['CallMaster']['Category5']);
                    $mArr['Category5']=$exp[0];
                    $data['CallMaster']['Category5']=$exp[1];
                    $category_5=$exp[1];
                }
                
                
                $clqry="SELECT id,close_loop_category,close_loop

                FROM closeloop_master 
                WHERE CONCAT(IF(CategoryName1='All',1,CONCAT('$category_1',IF(CategoryName2='All',1,CONCAT('$category_2',IF(CategoryName3='All',1,
                CONCAT('$category_3',IF(CategoryName4='All',1,
                CONCAT('$category_4',IF(CategoryName5='All',1,'$category_5')
                ))))))))) = CONCAT(IF(CategoryName1='All',1,CONCAT(IFNULL(CategoryName1,''),IF(CategoryName2='All',1,
            CONCAT(IFNULL(CategoryName2,''),IF(CategoryName3='All',1,
                CONCAT(IFNULL(CategoryName3,''),IF(CategoryName4='All',1,
                CONCAT(IFNULL(CategoryName4,''),IF(CategoryName5='All',1,IFNULL(CategoryName5,''))
                )))))))))
                AND client_id='$clientId' AND close_loop='system' AND label='1' LIMIT 1";
                
                $clArr=$this->CloseLoopMaster->query($clqry);
   
                if(!empty($clArr)){
                    $closeId=$clArr[0]['closeloop_master']['id'];
                    $closeLoop=$clArr[0]['closeloop_master']['close_loop'];
                    $closeLoopCat=$clArr[0]['closeloop_master']['close_loop_category'];
                    $clSubArr=$this->CloseLoopMaster->query("SELECT close_loop_category  FROM closeloop_master WHERE client_id='$clientId' AND close_loop='system' AND parent_id='$closeId' LIMIT 1");
                    $closeLoopSubCat=$clSubArr[0]['closeloop_master']['close_loop_category'];
                    
                    if($closeLoop ==="system" ){
                        $data['CallMaster']['close_loop'] = $closeLoop;
                        $data['CallMaster']['CloseLoopCate1'] =$closeLoopCat;
                        $data['CallMaster']['CloseLoopCate2'] =$closeLoopSubCat;
                        $data['CallMaster']['UpdateDate'] = date('Y-m-d H:i:s');
                        $data['CallMaster']['CloseLoopingDate'] = date('Y-m-d H:i:s');  
                    }
                }
                
                $clqry1="SELECT id,close_loop_category,close_loop
            FROM closeloop_master 
            WHERE CONCAT(IF(CategoryName1='All',1,CONCAT('$category_1',IF(CategoryName2='All',1,CONCAT('$category_2',IF(CategoryName3='All',1,
            CONCAT('$category_3',IF(CategoryName4='All',1,
            CONCAT('$category_4',IF(CategoryName5='All',1,'$category_5')
            ))))))))) = CONCAT(IF(CategoryName1='All',1,CONCAT(IFNULL(CategoryName1,''),IF(CategoryName2='All',1,
	CONCAT(IFNULL(CategoryName2,''),IF(CategoryName3='All',1,
            CONCAT(IFNULL(CategoryName3,''),IF(CategoryName4='All',1,
            CONCAT(IFNULL(CategoryName4,''),IF(CategoryName5='All',1,IFNULL(CategoryName5,''))
            )))))))))
            AND client_id='$clientId' AND close_loop='manual' AND label='1' LIMIT 1";
                
            $clArr1=$this->CloseLoopMaster->query($clqry1);
                
            if(empty($clArr) && empty($clArr1)){
                $data['CallMaster']['close_loop'] = '';
                $data['CallMaster']['CloseLoopCate1'] ='Close By System';
                $data['CallMaster']['CloseLoopCate2'] ='';
                $data['CallMaster']['UpdateDate'] = date('Y-m-d H:i:s');
                $data['CallMaster']['CloseLoopingDate'] = date('Y-m-d H:i:s');  
            }
                
           
                // Start Tat and due date funciton-----------
                
                if(!empty($clArr1)){
                    $tmcond1='';
                    $tmcond2='';
                    $tmcond3='';
                    $tmcond4='';
                    $tmcond5='';
                    
                    if($category_1 !=""){
                        $tmcond1 =" and Category1='$category_1'";
                    }
                    if($category_2 !=""){
                        $tmcond2 =" and Category2='$category_2'";
                    }
                    if($category_3 !=""){
                        $tmcond3 =" and Category3='$category_3'";
                    }
                    if($category_4 !=""){
                        $tmcond4 =" and Category4='$category_4'";
                    }
                    if($category_5 !=""){
                        $tmcond5 =" and Category5='$category_5'";
                    }
                    
                    $tatcon=$tmcond1.$tmcond2.$tmcond3.$tmcond4.$tmcond5;
                    
                    $tc=$this->TimePeriod->query("select working_type,time_Hours,start_time,end_time from tbl_time where clientId='$clientId' $tatcon limit 1");
                    $workingtype=$tc[0]['tbl_time']['working_type'];
                    $hours=$tc[0]['tbl_time']['time_Hours'];
                    
                    if($workingtype ==="Clock Hours"){
                        $time=strtotime("+$hours hour");
                        $data['CallMaster']['tat'] =$hours.' - '.$workingtype;
                        $data['CallMaster']['duedate'] =date("Y-m-d H:i:s",$time);
                        
                    }
                    
                    if($workingtype ==="Custom Hours" && !empty($tc[0]['tbl_time']['start_time']) && !empty($tc[0]['tbl_time']['end_time'])){
                       $start_time=str_replace(" ","",$tc[0]['tbl_time']['start_time']); //replace spaces from start time
                       $end_time=str_replace(" ","",$tc[0]['tbl_time']['end_time']); //replace spaces from end time
                       
                       $data['CallMaster']['tat'] =$hours.' - '.$workingtype; //saving in  table
                        
                       $taggTime = explode(":",date("H:i"));     //array explore for converting it in minutes
                       $taggT = $taggTime[0]*60+$taggTime[1];    //convert in minute and addition of it
                        
                       $end_of_dayTime = explode(":",$end_time);  //same as above for end time
                       $end_time = $end_of_dayTime[0]*60+$end_of_dayTime[1]; 
                       
                       $hours = $hours*60;                      //converting tat time in minutes
                       $tat = $hours-($end_time-$taggT);       //substract remaing business minutes from tat time. 
                        
                        if($tat > 0)                        //if plus then tat will close tommorrow. if minus tat will close today.
                        {
                            $tatDate = explode(":",$start_time); //converting business open time to minutes for adding tat time
                            $next_time = $tatDate[0]*60+$tatDate[1]; //converted in minutes
                            $tat = $tat+$next_time;     //addition of remainng tat time in next start day time.

                            $tat = round($tat/60,0).":".round($tat%60,0);  //converting date format
                            $date=date('Y-m-d')." $tat:00";   //converted in date format

                            $date=date_create("$date");     //object of date
                            date_add($date,date_interval_create_from_date_string("1 days")); //addition of one day
                            $next_time = date_format($date,"Y-m-d H:i:s");   //now we got next tat close time    
                            $data['CallMaster']['duedate'] =$next_time;
                        }
                        else{
                            $hours2=$tc[0]['tbl_time']['time_Hours'];
                            $time=strtotime("+$hours2 hour");
                            $data['CallMaster']['duedate'] =date("Y-m-d H:i:s",$time);
                        }  
                    }
   
                }
                 
                // End Tat and due date funciton-----------
               
      
                
                $dataSource = $this->CallMaster->getDataSource();
                $dataSource ->begin();
                $flag = $this->CallMaster->save($data);
                $lastid=$this->CallMaster->getLastInsertId();
                $SrNumber=$this->CallMaster->find('first',array('fields'=>'SrNo','conditions'=>array('ClientId'=>$clientId,'Id'=>$lastid)));       
                $sr=$SrNumber['CallMaster']['SrNo'];
                $this->set('data',$data);

                if($flag){$dataSource->commit();}
                else{$dataSource->rollback();}
                
                // start facebook mail integration update mail.
                if(isset($_REQUEST['postid']) && isset($_REQUEST['posttype']) && $_REQUEST['postid'] !="" && $_REQUEST['posttype'] !=""){
                    $status="CLOSE";
                    $data=array('status'=>"'".$status."'");
                    
                    if($_REQUEST['posttype']=='comment'){
                        $this->SocialMediaFeedback->updateAll($data,array('id'=>$_REQUEST['postid'],'client_id' => $clientId));
                    }
                    else if($_REQUEST['posttype']=='messenger'){    
                      $this->SocialMediaFeedback->query("UPDATE facebook_conversation_master SET update_status='$status',sr_update_time=NOW()  WHERE id='{$_REQUEST['postid']}'");  
                    }
                    else 
                        if($_REQUEST['posttype']=='emailbox'){
                        $this->MailMaster->updateAll($data,array('Id'=>$_REQUEST['postid']));
                    }
                }
                // emd facebook mail integration update mail.
                
               $this->Session->setFlash("Your SrNo (".$sr.") is successfully created.");
               $this->redirect(array('action'=>'index'));
            }
	}
        
        function dlfaddress(){
            if(isset($_REQUEST['subscenaro'])){
                $exp=  explode('-', $_REQUEST['subscenaro']); 
                if(trim($exp[1])=="I"){ 
                    $this->set('options',array('Ashoka crescent'=>'Ashoka crescent','Shahtoot marg'=>'Shahtoot marg','Deodar marg'=>'Deodar marg','Block-A'=>'Block-A','Block-A Extension'=>'Block-A Extension','Block-B'=>'Block-B','Paschim Marg'=>'Paschim Marg','Sukhchain Marg'=>'Sukhchain Marg','Block-C'=>'Block-C','Amaltas Marg'=>'Amaltas Marg','Block-D'=>'Block-D','Block-D (ltc Housing)'=>'Block-D (ltc Housing)','Club Marg'=>'Club Marg','Arjun Marg'=>'Arjun Marg','Block-E'=>'Block-E','Market Road'=>'Market Road','Bodhi Marg'=>'Bodhi Marg','Block-F'=>'Block-F','Silver oaks Avenue'=>'Silver oaks Avenue','Block-G'=>'Block-G','Kachnar Marg'=>'Kachnar Marg','Champa Marg'=>'Champa Marg','Kusum Marg'=>'Kusum Marg','Block-H'=>'Block-H','Other'=>'Other'));
                }
                else if(trim($exp[1])=="II"){ 
                    $this->set('options',array('Dakshin Marg'=>'Dakshin Marg','Block-J'=>'Block-J','Block-K'=>'Block-K','Cassia Marg'=>'Cassia Marg','Block-L'=>'Block-L','Block-L Extention'=>'Block-L Extention','Akash Neem Marg'=>'Akash Neem Marg','Gulmohar Marg'=>'Gulmohar Marg','Block-M'=>'Block-M','Madhya Marg'=>'Madhya Marg','Jacaranda marg'=>'Jacaranda marg','Block-N'=>'Block-N','Block-P'=>'Block-P','Poorvi Marg'=>'Poorvi Marg','Bougainvillea Marg'=>'Bougainvillea Marg','Block-Q'=>'Block-Q','Block-X'=>'Block-X','Other'=>'Other'));
                }
                else if(trim($exp[1])=="III"){ 
                    $this->set('options',array('Nathupur Road'=>'Nathupur Road','Block-S'=>'Block-S','Siris Road'=>'Siris Road','Moulsari Avenue'=>'Moulsari Avenue','Block-T'=>'Block-T','Block-U (Plotted)'=>'Block-U (Plotted)','Block-U (Pink Town Houses)'=>'Block-U (Pink Town Houses)','Block-U (White Town Houses)'=>'Block-U (White Town Houses)','Block-V'=>'Block-V','Block-W'=>'Block-W','Other'=>'Other'));  
                }
                else{
                   $this->set('options',array('Paschimi Marg'=>'Paschimi Marg','Block - B'=>'Block - B','Block - A Extension'=>'Block - A Extension','Block - A'=>'Block - A','Deodar Marg'=>'Deodar Marg','Shahtoot Marg'=>'Shahtoot Marg','Sukhchain Marg'=>'Sukhchain Marg','Block - C'=>'Block - C','Amaltas Marg'=>'Amaltas Marg','Block - D'=>'Block - D','Block - D (Itc Housing)'=>'Block - D (Itc Housing)','Club Marg'=>'Club Marg','Arjun Marg'=>'Arjun Marg','Block - E'=>'Block - E','Market Road'=>'Market Road','Bodhi Marg'=>'Bodhi Marg','Ashoka Crescent'=>'Ashoka Crescent'));   
                }  
            }
        }
                
        public function getECRName($name,$clientId){
            $data =$this->EcrMaster->find('first',array('fields'=>array('id'),'conditions'=>array('Client'=>$clientId,'ecrName'=>$name)));  
            return $data['EcrMaster']['id'];
        }
        
        
        
        
        	public function getChild()
	{
		$this->layout ='ajax';
		if($this->request->is("POST"))
		{
                   
                    
			if(!empty($this->request->data))
			{
				$label = $data['Label'] = addslashes($this->request->data['Label']);
				$label ++;
							
				$data['Client'] = $this->Session->read('companyid');
                                $parentName=addslashes($this->request->data['Parent']);
				$pos = strpos($parentName, '@@');
                
                                if ($pos === false) {
                                    $data['ecrName']=$parentName;
                                }
                                else{ 
                                    $exp=explode('@@',$parentName);
                                    $data['id'] = $exp[0];
                                    $data['ecrName'] = $exp[1];
                                }
                                
                                
				
				$category1 = $this->EcrMaster->find('first',array('fields'=>array('id'),'conditions'=>$data));
			
				$category = array();		
				if(!empty($category1))
					{
						$data = array();
						$data['Label'] = $label;
						$data['Client'] = $this->Session->read('companyid');
						$data['parent_id'] = $category1['EcrMaster']['id'];
						$data = $this->EcrMaster->find('all',array('fields'=>array('id','ecrName'),'conditions'=>$data));
				
						foreach($data as $post):
							$category[$post['EcrMaster']['id'].'@@'.$post['EcrMaster']['ecrName']] = $post['EcrMaster']['ecrName'];
						endforeach;
					}
			}
			
			$this->set('label',$label);
			$this->set('options',$category);		
		}		
	}
        
}