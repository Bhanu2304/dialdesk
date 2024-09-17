<?php
class AdminDetailsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('RegistrationMaster','StateMaster','CityMaster','DidMaster','DidHistoryMaster',
        'ClientRequestMaster','ObCampaignDataTypeMaster','InGroupCampaignMaster','ListMaster','BenchmarkClient','BenchmarkAgent','AgentMaster','Benchmark');
	
    public function beforeFilter() {
        parent::beforeFilter();
            $this->Auth->allow('update_did','update_did2','add_did','delete_did','clientdid','view_client','client_details',
                    'client_details1','get_city','update_client','client_permission','update_client_permissions','view_client_request',
                    'update_request','get_client_id','addcampaign','update_campaign','update_campaign2','addcampaignsubtype','addcampaignsubtype1','addcampaignsubtype_save','addcampaignlistid','addcampaignlistid1',
                    'addcampaignlistid_save','benchmarking','benchmarking_new','update_benchmarking','update_benchmarking_new','agent_benchmarking','update_agent_benchmarking','benchmarking_new1','update_benchmarking_new1','add_channel');
                if(!$this->Session->check("admin_id")){
                    return $this->redirect(array('controller'=>'admins','action' => 'index'));
		}
    }

	public function index() {
            $this->layout='user';		
	}
	
	public function view_client() {
		$this->layout='user';
		$result = $this->RegistrationMaster->find('all',array('order' => array('company_name'=>'asc')));
		$this->set('clint_record',$result);
		
	}
        
        public function view_client_request() {
		    $this->layout='user';
		    //$result = $this->ClientRequestMaster->find('all',array('order' => array('id'=>'desc')));
                
            $result = $this->ClientRequestMaster->find('all',
            array(
                'fields'=>array('ClientRequestMaster.id,client_id,request_type,request_data,request_status,request_date,response_date,registration_master.auth_person','registration_master.company_name'),
                'joins' => array(
                    array(
                        'table' =>'registration_master',
                        'type'=>'Left',
                        'alias'=>'registration_master',
                        'conditions'=>array("ClientRequestMaster.client_id=registration_master.company_id"),
                        'group' => array('ClientRequestMaster.client_id'),
                    )
                )
                
            ));
                
       
		    $this->set('client_request',$result);
		
	    }
        
        public function update_request(){
            if($_REQUEST['id']){
                $data = $this->ClientRequestMaster->find('first',array('conditions' => array('id'=>$_REQUEST['id'])));
                $reqtype=$data['ClientRequestMaster']['request_type'];
                $clientid=$data['ClientRequestMaster']['client_id'];
                $reqdata=$data['ClientRequestMaster']['request_data'];
                
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
                
               if($reqtype ==="email"){
                    $dataArr=array('email'=>"'".$reqdata."'",'modify_date'=>"'".date('Y-m-d H:i:s')."'",'update_user'=>"'".$update_user."'",'update_date'=>"'".$update_date."'");
               }
               else if($reqtype ==="phone"){
                    $dataArr=array('phone_no'=>"'".$reqdata."'",'modify_date'=>"'".date('Y-m-d H:i:s')."'",'update_user'=>"'".$update_user."'",'update_date'=>"'".$update_date."'");
               }
               if($this->RegistrationMaster->updateAll($dataArr,array('company_id'=>$clientid))){
                   $st="N";
                   $updateStatus=array('request_status'=>"'".$st."'",'response_date'=>"'".date('Y-m-d H:i:s')."'",);
                   $this->ClientRequestMaster->updateAll($updateStatus,array('id'=>$_REQUEST['id']));
                   $clientArr = $this->RegistrationMaster->find('first',array('fields'=>array('email','auth_person'),'conditions' => array('company_id'=>$clientid)));
                   $user_email=$clientArr['RegistrationMaster']['email'];
                   $name=$clientArr['RegistrationMaster']['auth_person'];

                    require_once(APP . 'Lib' . DS . 'send_email' . DS . 'function.php');

                    $EmailText='';
                    $to=array('Email'=>$user_email,'Name'=>$user_email);	
                    $from=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
                    $reply=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
                    $Sub="Dialdesk:Cheange Client Request.";
                    $EmailText.="Dear $name,<br/><br/>";
                    $EmailText.="Your request change successfully.<br/><br/>";

                    $emaildata=array('ReceiverEmail'=>$to,'SenderEmail'=>$from,'ReplyEmail'=>$reply,'Subject'=>$Sub,'EmailText'=>$EmailText);

                    if(send_email($emaildata)){
                        $this->Session->setFlash('Client request change sussesfully.');
                        $this->redirect(array('controller' => 'AdminDetails', 'action' => 'view_client_request'));
                    }
               }
               
                
            }
            
        }

       
        public function client_permission(){
            $this->layout='user';
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
            $this->set('client',$client);
            $this->set('clientid',array());
            
            if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
                $clpermission=$this->RegistrationMaster->find('first',array('fields'=>array('status','status_remarks'),'conditions'=>array('company_id'=>$_REQUEST['id'])));
                $this->set('clpermission',$clpermission);
                $this->set('clientid',$_REQUEST['id']);
            }
            if($this->request->is('Post')){
                $data=$this->request->data['AdminDetails'];
                $clpermission=$this->RegistrationMaster->find('first',array('fields'=>array('status','status_remarks'),'conditions'=>array('company_id'=>$data['clientID'])));
                $this->set('clpermission',$clpermission);
                $this->set('clientid',$data['clientID']);
            }
	}
        
        public function clientdid(){
            $this->layout='user';
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
            $this->set('client',$client);
            $this->set('clientid',array());
            
            if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
                $this->set('didnumber',$this->DidMaster->find('first',array('conditions'=>array('client_id'=>$_REQUEST['id']))));
                $this->set('hisdidnumber',$this->DidHistoryMaster->find('all',array('conditions'=>array('client_id'=>$_REQUEST['id']))));
                $this->set('clientid',$_REQUEST['id']);
            }
            if($this->request->is('Post')){
                $data=$this->request->data['AdminDetails'];
                $this->set('didnumber',$this->DidMaster->find('first',array('conditions'=>array('client_id'=>$data['clientID']))));
                $this->set('all_didnumber',$this->DidMaster->find('all',array('conditions'=>array('client_id'=>$data['clientID']))));
                $this->set('hisdidnumber',$this->DidHistoryMaster->find('all',array('conditions'=>array('client_id'=>$data['clientID']))));
                $this->set('clientid',$data['clientID']);
            }
	}
        
        public function addcampaign()
        {
            $this->layout='user';
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'order'=>array("company_name")));
            $this->set('client',$client);
            $this->set('clientid',array());
            
            if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
                $this->set('campaignid',$this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$_REQUEST['id']))));
                $this->set('clientid',$_REQUEST['id']);
            }
            if($this->request->is('Post')){
                $data=$this->request->data['AdminDetails'];
                $this->set('campaignid',$this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$data['clientID']))));
                $this->set('clientid',$data['clientID']);
            }
	    }
        
            
         public function update_campaign(){
            if($this->request->is('Post')){
                $data=$this->request->data['AdminDetails'];
                $exp=  explode(',', str_replace(" ","",$data['campaignid']));
                $campaignName=array();
                for($i=0;$i<count($exp);$i++){
                    $campaignName[]="'$exp[$i]'";
                }
                $campName=implode(',', $campaignName);

                $exp1=  explode(',', str_replace(" ","",$data['GroupId']));
                $GroupName=array();
                for($i=0;$i<count($exp1);$i++){
                    $GroupName[]="'$exp1[$i]'";
                }
                $grpName=implode(',', $GroupName);

                $exp2=  explode(',', str_replace(" ","",$data['multilang_ivrs']));
                $MultiLangName=array();
                for($i=0;$i<count($exp2);$i++){
                    $MultiLangName[]="'$exp2[$i]'";
                }
                $multiName=implode(',', $MultiLangName);
                
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

                $this->InGroupCampaignMaster->deleteAll(array('client_id' =>$data['client_id']));
                foreach($exp as $camp)
                {
                  
                    $dataMulti = array(
                        array(
                            'InGroupCampaignMaster' =>
                            array(
                                'campaign_name' => $camp,
                                'client_id' => $data['client_id']
                            )
                        ),
                    );
                    
                    $this->InGroupCampaignMaster->saveAll($dataMulti);
                      
                }
                
                $dataArr=array('campaignid'=>"'".addslashes($campName)."'",'GroupId'=>"'".addslashes($grpName)."'",'multilang_ivrs'=>"'".addslashes($multiName)."'",'update_user'=>"'".$update_user."'",'update_date'=>"'".$update_date."'");
                $this->RegistrationMaster->updateAll($dataArr,array('company_id'=>$data['client_id']));
                $this->Session->setFlash('update successfully.');
                $this->redirect(array('controller' => 'AdminDetails', 'action' => 'addcampaign', '?' => array('id' =>$data['client_id'])));
            }
        }
        
        public function update_campaign2(){

            $this->layout='ajax';

            if($this->request->is('Post')){
                #print_r($_REQUEST);die;
                #$data=$this->request->data['AdminDetails'];
                $exp=  explode(',', str_replace(" ","",$_REQUEST['campaignid']));
                $campaignName=array();
                for($i=0;$i<count($exp);$i++){
                    $campaignName[]="'$exp[$i]'";
                }
                $campName=implode(',', $campaignName);

                $exp1=  explode(',', str_replace(" ","",$_REQUEST['GroupId']));
                $GroupName=array();
                for($i=0;$i<count($exp1);$i++){
                    $GroupName[]="'$exp1[$i]'";
                }
                $grpName=implode(',', $GroupName);

                $exp2=  explode(',', str_replace(" ","",$_REQUEST['multilang_ivrs']));
                $MultiLangName=array();
                for($i=0;$i<count($exp2);$i++){
                    $MultiLangName[]="'$exp2[$i]'";
                }
                $multiName=implode(',', $MultiLangName);
                
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

                $this->InGroupCampaignMaster->deleteAll(array('client_id' =>$_REQUEST['client']));
                foreach($exp as $camp)
                {
                  
                    $dataMulti = array(
                        array(
                            'InGroupCampaignMaster' =>
                            array(
                                'campaign_name' => $camp,
                                'client_id' => $_REQUEST['client']
                            )
                        ),
                    );
                    
                    $this->InGroupCampaignMaster->saveAll($dataMulti);
                      
                }
                
                $dataArr=array('campaignid'=>"'".addslashes($campName)."'",'GroupId'=>"'".addslashes($grpName)."'",'multilang_ivrs'=>"'".addslashes($multiName)."'",'update_user'=>"'".$update_user."'",'update_date'=>"'".$update_date."'");
                $this->RegistrationMaster->updateAll($dataArr,array('company_id'=>$_REQUEST['client']));
                echo 'update successfully.';exit;
                
            }
        }

        public function addcampaignsubtype()
        {
            $this->layout='user';
            

            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>"A")));
            $this->set('client',$client);
            $this->set('clientid',array());


            $result = $this->ObCampaignDataTypeMaster->find('all',
                array(
                    'fields'=>array('ObCampaignDataTypeMaster.id,CampaignType,ClientId,registration_master.company_id','registration_master.company_name'),
                    'joins' => array(
                        array(
                            'table' =>'registration_master',
                            'type'=>'Left',
                            'alias'=>'registration_master',
                            'conditions'=>array("ObCampaignDataTypeMaster.ClientId=registration_master.company_id"),
                            'group' => array('ObCampaignDataTypeMaster.ClientId'),
                        )
                    )
                    
                ));
                
		        $this->set('campaign_type_record',$result);


        
	    }

        public function addcampaignsubtype1()
        {
            $this->layout='popuser';
            
            $client_id = $this->Session->read('companyid'); 
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('company_id'=>$client_id)));
            #$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>"A")));
            $this->set('client',$client);
            $this->set('clientid',array());


            $result = $this->ObCampaignDataTypeMaster->find('all',
                array(
                    'fields'=>array('ObCampaignDataTypeMaster.id,CampaignType,ClientId,registration_master.company_id','registration_master.company_name'),
                    'joins' => array(
                        array(
                            'table' =>'registration_master',
                            'type'=>'Left',
                            'alias'=>'registration_master',
                            'conditions'=>array("ObCampaignDataTypeMaster.ClientId=registration_master.company_id"),
                            'group' => array('ObCampaignDataTypeMaster.ClientId'),
                        )
                    )
                    
                ));
                
		        $this->set('campaign_type_record',$result);


        
	    }
        
        public function addcampaignsubtype_save()
        {
            

            if($this->request->is('Post'))
            {
              
               $clientid =$this->request->data['clientID'];

               $type = $this->request->data['campaign_type']; 
              
               $data =['CampaignType'=> $type,'ClientId'=> $clientid];
                
                    $this->ObCampaignDataTypeMaster->save($data);  
                    $this->Session->setFlash('Add Campaign Sub Type Successfully.');
               
                $this->redirect(array('controller' => 'AdminDetails', 'action' => 'addcampaignsubtype'));
            }
        }
            






        public function add_did(){
            if($this->request->is('Post')){
                $data=$this->request->data['AdminDetails'];
                $existDid=$this->DidMaster->find('first',array('conditions'=>array('did_number'=>$data['did_number'])));
                $existCus=$this->DidMaster->find('first',array('conditions'=>array('customer_care_number'=>$data['customer_care_number'])));
              
                if (strlen($data['did_number']) < 7){
                    $this->Session->setFlash('<span style="color:red;">Did number should required 7 digit.</span>');
                }
                else if (strlen($data['customer_care_number']) < 7){ 
                    $this->Session->setFlash('<span style="color:red;">Customer care number should required 7 digit.</span>');
                }
                else if(!empty($existDid)){
                    $this->Session->setFlash('<span style="color:red;">Did number already used for other client.</span>');
                }
                else if(!empty($existCus)){
                    $this->Session->setFlash('<span style="color:red;">Customer care number already used for other client.</span>');
                }
                else{
                    $this->DidMaster->save($data);  
                    $this->Session->setFlash('Add did number Successfully.');
                }
                $this->redirect(array('controller' => 'AdminDetails', 'action' => 'clientdid', '?' => array('id' =>$data['client_id'])));
            }
        }
        
        public function update_did(){
            if($this->request->is('Post')){
                $data=$this->request->data['AdminDetails'];
                $dataArr=array(
                    'did_number'=>"'".$data['did_number']."'",
                    'customer_care_number'=>"'".$data['customer_care_number']."'",
                    'update_date'=>"'".date('Y-m-d H:i:s')."'"
                    );

                $existDid=$this->DidMaster->find('first',array('conditions'=>array('did_number'=>$data['did_number'],'client_id !='=>$data['client_id'])));
                $existCus=$this->DidMaster->find('first',array('conditions'=>array('customer_care_number'=>$data['customer_care_number'],'client_id !='=>$data['client_id'])));
          
                
                if (strlen($data['did_number']) < 7){
                    $this->Session->setFlash('<span style="color:red;">Did number should required 7 digit.</span>');
                }
                else if (strlen($data['customer_care_number']) < 7){
                    $this->Session->setFlash('<span style="color:red;">Customer care number should required 7 digit.</span>');
                }
                else if(!empty($existDid)){
                    $this->Session->setFlash('<span style="color:red;">Did number already used for other client.</span>');
                }
                else if(!empty($existCus)){
                    $this->Session->setFlash('<span style="color:red;">Customer care number already used for other client.</span>');
                }
                else{
                   // $this->DidHistoryMaster->save($hisData['DidMaster']);
                    $this->DidMaster->updateAll($dataArr,array('id'=>$data['id'],'client_id'=>$data['client_id']));
                    $this->Session->setFlash('Update did number Successfully.');
                }
                 
                $this->redirect(array('controller' => 'AdminDetails', 'action' => 'clientdid', '?' => array('id' =>$data['client_id'])));
            }
        }
        
        public function update_did2(){
            $this->layout='ajax';
            if($this->request->is('Post')){
                #print_r($_REQUEST);die;
                #$data=$this->request->data['AdminDetails'];
                $dataArr=array(
                    'did_number'=>$_REQUEST['did_number'],
                    'customer_care_number'=>$_REQUEST['customer_care_number'],
                    'create_date'=>date('Y-m-d H:i:s')
                    );

                $dataArr2=array(
                    'did_number'=>"'".$_REQUEST['did_number']."'",
                    'customer_care_number'=>"'".$_REQUEST['customer_care_number']."'",
                    'update_date'=>"'".date('Y-m-d H:i:s')."'"
                    );

                $existDid=$this->DidMaster->find('first',array('conditions'=>array('did_number'=>$_REQUEST['did_number'],'client_id !='=>$_REQUEST['client'])));
                $existCus=$this->DidMaster->find('first',array('conditions'=>array('customer_care_number'=>$_REQUEST['customer_care_number'],'client_id !='=>$_REQUEST['client'])));
          
                
                if (strlen($_REQUEST['did_number']) < 7){
                    echo 'Did number should required 7 digit.';exit;
                }
                else if (strlen($_REQUEST['customer_care_number']) < 7){
                    echo 'Customer care number should required 7 digit.';exit;
                }
                else if(!empty($existDid)){
                    echo 'Did number already used for other client.';exit;
                }
                else if(!empty($existCus)){
                    echo 'Customer Care No. already used for other client.';exit;
                }
                else{
                   // $this->DidHistoryMaster->save($hisData['DidMaster']);
                    $existDid=$this->DidMaster->find('first',array('conditions'=>array('client_id ='=>$_REQUEST['client'])));
                    if($existDid)
                    {
                        #print_r($existDid);die;
                        $this->DidMaster->updateAll($dataArr2,array('id'=>$existDid['DidMaster']['id'],'client_id'=>$existDid['DidMaster']['client_id']));
                        echo '1';exit;
                    }
                    else
                    {
                        #print_r($dataArr);die;
                        $dataArr['client_id'] = $_REQUEST['client'];
                        #print_r($dataArr);die;
                        if($this->DidMaster->save($dataArr))
                        {
                            echo '1';exit;
                        }
                        else
                        {
                            echo 'DID No. Not. Updated, Please Try Again Later.';exit;
                        }
                    }
                    
                }
                 
                exit;
            }
        }
        
        
        public function delete_did(){
            if(isset($_REQUEST['id'])){
                $this->DidMaster->delete(array('id'=>$_REQUEST['id'],'client_id'=>$_REQUEST['cid']));
            }
            $this->redirect(array('controller' => 'AdminDetails', 'action' => 'clientdid', '?' => array('id' =>$_REQUEST['cid'])));
        }
        
        public function update_client_permissions(){
            if($this->request->is('Post')){
                $data=$this->request->data['AdminDetails'];
                
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
                
                $dataArr=array(
                    'status'=>"'".$data['permission']."'",
                    'status_remarks'=>"'".$data['status_remarks']."'",
                    'update_user'=>"'".$update_user."'",
                    'update_date'=>"'".$update_date."'"
                    );	
                $this->RegistrationMaster->updateAll($dataArr,array('company_id'=>$data['clientID']));
                $this->Session->setFlash('Client Permissions Update Successfully.');
                $this->redirect(array('controller' => 'AdminDetails', 'action' => 'client_permission', '?' => array('id' =>$data['clientID'])));
            }	
	}
	
	public function client_details(){
		$this->layout='user';
        

		if(isset($_REQUEST['id']) && isset($_REQUEST['type'])){
			$result = $this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$_REQUEST['id'])));

            //print_r($result);die;

			$state=$this->StateMaster->find('list',array('fields'=>array("Id","StateName")));
			$this->set('state',$state);
		
			$city =$this->CityMaster->find('list',array('fields'=>array("CityName","CityName")));
			$this->set('city',$city);
			
			$stateid=$this->StateMaster->find('first',array('fields'=>array("Id"),'conditions'=>array('StateName'=>$result['RegistrationMaster']['state'])));
			$this->set('stateid',$stateid);
			$this->set('type',$_REQUEST['type']);
			$this->set('data',$result);
		}
	}

    public function client_details1(){
		$this->layout='user';
        

		if(isset($_REQUEST['id']) && isset($_REQUEST['type'])){
			$result = $this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$_REQUEST['id'])));

            //print_r($result);die;

			$state=$this->StateMaster->find('list',array('fields'=>array("Id","StateName")));
			$this->set('state',$state);
		
			$city =$this->CityMaster->find('list',array('fields'=>array("CityName","CityName")));
			$this->set('city',$city);
			
			$stateid=$this->StateMaster->find('first',array('fields'=>array("Id"),'conditions'=>array('StateName'=>$result['RegistrationMaster']['state'])));
			$this->set('stateid',$stateid);
			$this->set('type',$_REQUEST['type']);
			$this->set('data',$result);
		}
	}
	
	public function update_client(){
		$data=$this->request->data;
        //print_r($data);die;
        
            
		$state=$this->StateMaster->find('first',array('fields' => array( 'StateName'),'conditions'=>array('Id'=>$data['state'])));
		$stateName=$state['StateMaster']['StateName'];
                
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
       
		$dataArr=array(
			'company_name'=>"'".addslashes(trim($data['company_name']))."'",
			'reg_office_address1'=>"'".addslashes(trim($data['reg_office_address1']))."'",
			'reg_office_address2'=>"'".addslashes(trim($data['reg_office_address2']))."'",
			'city'=>"'".addslashes(trim($data['city']))."'",
			'state'=>"'".$stateName."'",
			'pincode'=>"'".addslashes(trim($data['pincode']))."'",
			'auth_person'=>"'".addslashes(trim($data['auth_person']))."'",
			'designation'=>"'".addslashes(trim($data['designation']))."'",
			'phone_no'=>"'".addslashes(trim($data['phone_no']))."'",
			'email'=>"'".addslashes(trim($data['email']))."'",
			'password'=>"'".addslashes(trim($data['password']))."'",
			'comm_address1'=>"'".addslashes(trim($data['comm_address1']))."'",
			'comm_address2'=>"'".addslashes(trim($data['comm_address2']))."'",
			'comm_city'=>"'".addslashes(trim($data['comm_city']))."'",
			'comm_state'=>"'".addslashes(trim($data['comm_state']))."'",
			'comm_pincode'=>"'".addslashes(trim($data['comm_pincode']))."'",
			'contact_person1'=>"'".addslashes(trim($data['contact_person1']))."'",
			'cp1_designation'=>"'".addslashes(trim($data['cp1_designation']))."'",
			'cp1_phone'=>"'".addslashes(trim($data['cp1_phone']))."'",
			'cp1_email'=>"'".addslashes(trim($data['cp1_email']))."'",
			'contact_person2'=>"'".addslashes(trim($data['contact_person2']))."'",
			'cp2_designation'=>"'".addslashes(trim($data['cp2_designation']))."'",
			'cp2_phone'=>"'".addslashes(trim($data['cp2_phone']))."'",
			'cp2_email'=>"'".addslashes(trim($data['cp2_email']))."'",
			'contact_person3'=>"'".addslashes(trim($data['contact_person3']))."'",
			'cp3_designation'=>"'".addslashes(trim($data['cp3_designation']))."'",
			'cp3_phone'=>"'".addslashes(trim($data['cp3_phone']))."'",
			'cp3_email'=>"'".addslashes(trim($data['cp3_email']))."'",
            'status'=>"'".addslashes(trim($data['permission']))."'",
            'CsatSms'=>"'".addslashes(trim($data['CsatSms']))."'",
            'update_user'=>"'".$update_user."'",
            'update_date'=>"'".$update_date."'",
            'status_remarks'=>"'".addslashes(trim($data['status_remarks']))."'",
            'is_dd_client'=>"'".addslashes(trim($data['is_dd']))."'",
            'client_category'=>"'".addslashes(trim($data['client_category']))."'",            
            'billing_email_id'=>"'".addslashes(trim($data['billing_email_id']))."'");

            if(!empty($data['deactive_date'])) 
            {
                $dataArr['deactive_date'] = "'".addslashes(date("Y-m-d", strtotime($data['deactive_date'])))."'"; 
                $this->DidMaster->delete(array('client_id'=>$data['company_id']));

            }
                     
            for($i=0 ; $i<=22; $i++){
                $Sel = "select * from tbl_benchmark where ClientId='{$data['company_id']}' and benchmark='{$data['Name' . $i]}'";
                $Bench = $this->RegistrationMaster->query($Sel);
                //print_r($Bench); die;
                if(empty($Bench))
                {
                $Ins = "insert into tbl_benchmark set benchmark='{$data['Name' . $i]}',Green='{$data['Green' . $i]}',Orange='{$data['Orange' . $i]}',Red='{$data['Red' . $i]}',ClientId='{$data['company_id']}'";
                $this->RegistrationMaster->query($Ins);
                } else {
                $Upd = "update tbl_benchmark set benchmark='{$data['Name' . $i]}',Green='{$data['Green' . $i]}',Orange='{$data['Orange' . $i]}',Red='{$data['Red' . $i]}',ClientId='{$data['company_id']}' where ClientId='{$data['company_id']}' and benchmark='{$data['Name' . $i]}'";
                $this->RegistrationMaster->query($Upd);    
                }
            }
			$this->RegistrationMaster->updateAll($dataArr,array('company_id'=>$data['company_id']));
			
			$compid=$data['company_id'];
			$doc1=$data['incorporation_certificate'];
			$doc2=$data['pancard'];
			$doc3=$data['bill_address_prof'];
			$doc4=$data['authorized_id_prof'];
			$doc5=$data['auth_person_address_prof'];
			
			if (!file_exists(WWW_ROOT.'upload/client_file/client_'.$compid)) {
    			mkdir(WWW_ROOT.'upload/client_file/client_'.$compid, 0777, true);
			}
			$path=WWW_ROOT.'upload/client_file/client_'.$compid.'/';
					
			if(!empty($_FILES['userfile']['name'])){
				$data=$this->upload_multiple_file($filename='userfile',$path,$doc1);
				$this->RegistrationMaster->updateAll(array('incorporation_certificate'=>"'".$data."'"),array('company_id'=>$compid));
			}
			if(!empty($_FILES['userfile2']['name'])){
				$data2=$this->upload_multiple_file($filename='userfile2',$path,$doc2);
				$this->RegistrationMaster->updateAll(array('pancard'=>"'".$data2."'"),array('company_id'=>$compid));
			}
			if(!empty($_FILES['userfile3']['name'])){
				$data3=$this->upload_multiple_file($filename='userfile3',$path,$doc3);
				$this->RegistrationMaster->updateAll(array('bill_address_prof'=>"'".$data3."'"),array('company_id'=>$compid));
			}
			if(!empty($_FILES['userfile4']['name'])){
				$data4=$this->upload_multiple_file($filename='userfile4',$path,$doc4);
				$this->RegistrationMaster->updateAll(array('authorized_id_prof'=>"'".$data4."'"),array('company_id'=>$compid));
			}
			if(!empty($_FILES['userfile5']['name'])){
				$data5=$this->upload_multiple_file($filename='userfile5',$path,$doc5);
				$this->RegistrationMaster->updateAll(array('auth_person_address_prof'=>"'".$data5."'"),array('company_id'=>$compid));
			}
			$this->redirect(array('controller' => 'AdminDetails', 'action' => 'client_details', '?' => array('id' =>$compid,'type'=>'edit')));
	}
	
	
	public function upload_multiple_file($filename,$path,$doc){
		$files = $_FILES;
		$fileName=array();
		$cpt = count($_FILES[$filename]['name']);
		if($cpt >0){
			for($i=0; $i<$cpt; $i++){
				$rand = rand(100000,999999);
				$_FILES[$filename]['name']		= $files[$filename]['name'][$i];
				$_FILES[$filename]['type']		= $files[$filename]['type'][$i];
				$_FILES[$filename]['tmp_name']	= $files[$filename]['tmp_name'][$i];
				$_FILES[$filename]['error']		= $files[$filename]['error'][$i];
				$_FILES[$filename]['size']		= $files[$filename]['size'][$i];    
		
				$ary_ext=array('jpg','jpeg','gif','png','pdf','txt'); 
				$ext = substr(strtolower(strrchr($_FILES[$filename]['name'], '.')), 1); 
				if(in_array($ext, $ary_ext)){
					move_uploaded_file($_FILES[$filename]['tmp_name'],$path.$rand.$_FILES[$filename]['name']);
					$fileName[]= $rand.$_FILES[$filename]['name'];
				}
			}
			if($doc !=""){
				return $data=implode(',',array_merge(explode(",",$doc),$fileName));	
			}
			else{
				return $data=implode(',',$fileName);
			}
		}
	}
		
	public function get_city(){
		$this->layout='ajax';
		if($_REQUEST['id']){
			$city =$this->CityMaster->find('list',array('fields'=>array("CityName","CityName"),'conditions'=>array('StateId'=>$_REQUEST['id'])));
			$this->set('city',$city);
		}
	}

    public function addcampaignlistid()
    {
        $this->layout='user';

        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>"A")));
        $this->set('client',$client);
        $this->set('clientid',array());


        $result = $this->ListMaster->find('all',
            array(
                'fields'=>array('*,registration_master.company_id','registration_master.company_name'),
                'joins' => array(
                    array(
                        'table' =>'registration_master',
                        'type'=>'Left',
                        'alias'=>'registration_master',
                        'conditions'=>array("ListMaster.client_id=registration_master.company_id"),
                        'group' => array('ListMaster.client_id'),
                    )
                )
                
            ));

            // $viewListId = $this->ListMaster->find('all',array('group'=>array("ListMaster.client_id")));
            // $this->set('viewListId',$viewListId);
            //print_r($result);die;
            
            $this->set('result',$result);


    
    }

    public function addcampaignlistid1()
    {
        $this->layout='popuser';
        $client_id = $this->Session->read('companyid'); 
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('company_id'=>$client_id)));
        #$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>"A")));
        $this->set('client',$client);
        $this->set('clientid',array());


        $result = $this->ListMaster->find('all',
            array(
                'fields'=>array('*,registration_master.company_id','registration_master.company_name'),
                'joins' => array(
                    array(
                        'table' =>'registration_master',
                        'type'=>'Left',
                        'alias'=>'registration_master',
                        'conditions'=>array("ListMaster.client_id=registration_master.company_id"),
                        'group' => array('ListMaster.client_id'),
                    )
                )
                
            ));

            // $viewListId = $this->ListMaster->find('all',array('group'=>array("ListMaster.client_id")));
            // $this->set('viewListId',$viewListId);
            //print_r($result);die;
            
            $this->set('result',$result);


    
    }
    
    public function addcampaignlistid_save()
    {
        
        if($this->request->is('Post'))
        {
          
           $clientid =$this->request->data['clientID'];
           $type = $this->request->data['campaign_type']; 
           $update_date=date('Y-m-d H:i:s'); 
           $update_user=$this->Session->read('admin_name');

           $Arr=array('list_id'=>$type);
           $arrData=$this->ListMaster->find('first',array('conditions'=>$Arr));

           if(empty($arrData['ListMaster']))
           {
                $data =['list_id'=> $type,'client_id'=> $clientid,'create_date'=> $update_date,'update_user'=> $update_user];
                
                $this->ListMaster->save($data);  
                $this->Session->setFlash('Add Campaign List Id Successfully.');

           }else{

             $this->Session->setFlash('List Id Already Exist.');
           }
          
          
           
            $this->redirect(array('controller' => 'AdminDetails', 'action' => 'addcampaignlistid'));
        }
    }

    public function benchmarking(){
		$this->layout='user';
        

        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
        $this->set('client',$client);
        if($this->request->is('post'))
        {   
            $data = $this->request->data['Home'];
            $client_id = $data['clientID'];
            #echo $client_id = $this->request->data('Home');die;
            $this->set('companyid',$client_id);
        }	
		
	}


    public function update_benchmarking()
    {
     
        if($this->request->is('post')) {
            #echo "<pre>";
            #print_r($data=$this->request->data);die;
            $client_id = $this->request->data['client_id'];
            $data = $this->request->data('Benchmark');
            // echo "<pre>";
            // echo count($data);echo "<br>";
            // print_r($data);die;

            $benchmarkData = [];
            foreach ($data as $particular => $values) {
                for ($i = 1; $i <= 24; $i++) {
                    
                    //if (!empty($values[$i]['time']) && !empty($values[$i]['figure_in']) && !empty($values[$i]['figure']) && !empty($values[$i]['variance'])) {
                    //}
                        
                        // $data1['benchmark'] = $values[$i]['particular'];
                        // $data1['time'] = $values[$i]['time'];
                        // $data1['figure_in'] = $values[$i]['figure_in'];
                        // $data1['figure'] = $values[$i]['figure'];
                        // $data1['variance'] = $values[$i]['variance'];
                        

                        // Attempt to save the entity
                        #$this->Benchmark->save($data1);
                      #print_r($data1) ;die;
                      $benchmarkData[] = [
                        'client_id' => $client_id,
                        'benchmark' => $values[$i]['particular'],
                        'time' => $values[$i]['time'],
                        'figure_in' => $values[$i]['figure_in'],
                        'figure' => $values[$i]['figure'],
                        'variance' => $values[$i]['variance']
                    ];
                }
            }
            #echo "<pre>";
            #print_r($benchmarkData);die;
            $this->Benchmark->saveMany($benchmarkData);
            $this->redirect(array('controller' => 'AdminDetails', 'action' => 'benchmarking'));
            #$this->Flash->success(__('Benchmark data saved successfully.'));
        }
        #$this->set(compact('yourTableName'));
    
    }

    public function benchmarking_new(){
		$this->layout='user';
        

		$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
        $this->set('client',$client);
        $benchmark_cat = $this->Benchmark->find('all',array("conditions"=>"atype='client'"));
        if($this->request->is('post'))
        {   
            $data = $this->request->data['Home'];
            $client_id = $data['clientID'];

            #$benchmark_arr =$this->BenchmarkClient->find('all','conditions'=>array('client_id'=>$name));
            $benchmark_arr = $this->BenchmarkClient->find('all',array("conditions"=>"client_id='$client_id'"));
            
            #print_r($benchmark_arr);die;
            #echo $client_id = $this->request->data('Home');die;
            $this->set('benchmark_arr',$benchmark_arr);
            $this->set('companyid',$client_id);
        }
        $this->set('benchmark_cat',$benchmark_cat);
		
	}


    public function update_benchmarking_new()
    {
     
        if($this->request->is('post')) {
            // echo "<pre>";
            // print_r($data=$this->request->data);die;
            $client_id = $this->request->data['client_id'];
            $data = $this->request->data('Benchmark');
             #echo "<pre>";
            // echo count($data);echo "<br>";
             #print_r($data);die;

            $benchmarkData = [];
            foreach ($data as $particular => $values) {
                for ($i = 1; $i <= 24; $i++) {
                    
                        
                      #print_r($data1) ;die;
                      $benchmarkData[] = [
                        'client_id' => $client_id,
                        'benchmark' => $particular,
                        'time' => $values[$i]['time'],
                        'figure' => $values[$i]['figure'],
                        'variance' => $values[$i]['variance']
                    ];
                }
            }
            // echo "<pre>";
            // print_r($benchmarkData);die;
            $this->BenchmarkClient->saveMany($benchmarkData);
            $this->redirect(array('controller' => 'AdminDetails', 'action' => 'benchmarking_new'));
            #$this->Flash->success(__('Benchmark data saved successfully.'));
        }
        #$this->set(compact('yourTableName'));
    
    }


    public function agent_benchmarking(){
		$this->layout='user';
        
    
        $benchmark_cat = $this->Benchmark->find('all',array("conditions"=>"atype='agent'"));
		$client =$this->AgentMaster->find('list',array('fields'=>array("username","displayname"),'conditions'=>array('status'=>'A'),'order'=>array('displayname'=>'asc')));
        $this->set('client',$client);
        if($this->request->is('post'))
        {   
            $data = $this->request->data['Home'];
            $client_id = $data['clientID'];

            
            $benchmark_arr =$this->BenchmarkAgent->find('all');
            #print_r($benchmark_arr);die;
            #echo $client_id = $this->request->data('Home');die;
            $this->set('benchmark_arr',$benchmark_arr);
            $this->set('companyid',$client_id);
        }
    
        $this->set('benchmark_cat',$benchmark_cat);
		
	}

    public function update_agent_benchmarking()
    {
     
        if($this->request->is('post')) {
            // echo "<pre>";
            // print_r($data=$this->request->data);die;
            $client_id = $this->request->data['client_id'];
            $data = $this->request->data('Benchmark');
             #echo "<pre>";
            // echo count($data);echo "<br>";
             #print_r($data);die;

            $benchmarkData = [];
            foreach ($data as $particular => $values) {
                for ($i = 1; $i <= 24; $i++) {
                    
                        
                      #print_r($data1) ;die;
                      $benchmarkData[] = [
                        'user' => $client_id,
                        'benchmark' => $particular,
                        'time' => $values[$i]['time'],
                        'figure' => $values[$i]['figure'],
                        'variance' => $values[$i]['variance']
                    ];
                }
            }
            // echo "<pre>";
            // print_r($benchmarkData);die;
            $this->BenchmarkAgent->saveMany($benchmarkData);
            $this->redirect(array('controller' => 'AdminDetails', 'action' => 'agent_benchmarking'));
            #$this->Flash->success(__('Benchmark data saved successfully.'));
        }
        #$this->set(compact('yourTableName'));
    
    }

    public function benchmarking_new1(){
		$this->layout='user';
        

		$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
        $this->set('client',$client);
        $benchmark_cat = $this->Benchmark->find('all',array("conditions"=>"active='1'"));
        if($this->request->is('post'))
        {   
            $data = $this->request->data['Home'];
            $client_id = $data['clientID'];

            #$benchmark_arr =$this->BenchmarkClient->find('all','conditions'=>array('client_id'=>$name));
            $benchmark_arr = $this->BenchmarkClient->find('all',array("conditions"=>"client_id='$client_id'"));

            if(!empty($benchmark_arr))
            {
                $benchmark_data = array();
                foreach($benchmark_arr as $bench)
                {
                    if($bench['BenchmarkClient']['benchmark'] == 'No of calls')
                    {   
                        $benchmark_data[$bench['BenchmarkClient']['benchmark']][$bench['BenchmarkClient']['time']]['time'] = $bench['BenchmarkClient']['time'];
                        $benchmark_data[$bench['BenchmarkClient']['benchmark']][$bench['BenchmarkClient']['time']]['figure'] = $bench['BenchmarkClient']['figure'];
                        $benchmark_data[$bench['BenchmarkClient']['benchmark']][$bench['BenchmarkClient']['time']]['variance'] = $bench['BenchmarkClient']['variance'];

                    }else{

                        $benchmark_data[$bench['BenchmarkClient']['benchmark']]['figure'] = $bench['BenchmarkClient']['figure'];
                        $benchmark_data[$bench['BenchmarkClient']['benchmark']]['variance'] = $bench['BenchmarkClient']['variance'];
                    }
                    
                }
            }
            
            #print_r($benchmark_data);die;
            #echo $client_id = $this->request->data('Home');die;
            $this->set('benchmark_data',$benchmark_data);
            $this->set('companyid',$client_id);
        }
        $this->set('benchmark_cat',$benchmark_cat);
		
	}
    
    public function update_benchmarking_new1()
    {
     
        if($this->request->is('post')) {
            // echo "<pre>";
            #print_r($data=$this->request->data);die;
            $client_id = $this->request->data['client_id'];
            $data = $this->request->data('Benchmark');
             #echo "<pre>";
            // echo count($data);echo "<br>";
             #print_r($data);die;

            $benchmarkData = [];
            foreach ($data as $particular => $values) {
                if ($particular == "No of calls") {
                    for ($i = 1; $i <= 24; $i++) {
                        
                            
                        #print_r($data1) ;die;
                        $benchmarkData[] = [
                            'client_id' => $client_id,
                            'benchmark' => $particular,
                            'time' => $values[$i]['time'],
                            'figure' => $values[$i]['figure'],
                            'variance' => $values[$i]['variance']
                        ];
                    }
                }else{
                    $benchmarkData[] = [
                        'client_id' => $client_id,
                        'benchmark' => $particular,
                        'time' => $values['time'],
                        'figure' => $values['figure'],
                        'variance' => $values['variance']
                    ];
                }
            }
            // echo "<pre>";
            #print_r($benchmarkData);die;
            $delete_all = $this->BenchmarkClient->deleteAll(array('client_id' => $client_id));
            $this->BenchmarkClient->saveMany($benchmarkData);
            $this->redirect(array('controller' => 'AdminDetails', 'action' => 'benchmarking_new1'));
            #$this->Flash->success(__('Benchmark data saved successfully.'));
        }
        #$this->set(compact('yourTableName'));
    
    }

    public function add_channel()
    {
        $this->layout='user';

        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>"A")));
        $this->set('client',$client);
        $this->set('clientid',array());


        $result = $this->ListMaster->find('all',
            array(
                'fields'=>array('*,registration_master.company_id','registration_master.company_name'),
                'joins' => array(
                    array(
                        'table' =>'registration_master',
                        'type'=>'Left',
                        'alias'=>'registration_master',
                        'conditions'=>array("ListMaster.client_id=registration_master.company_id"),
                        'group' => array('ListMaster.client_id'),
                    )
                )
                
            ));

            // $viewListId = $this->ListMaster->find('all',array('group'=>array("ListMaster.client_id")));
            // $this->set('viewListId',$viewListId);
            //print_r($result);die;
            
            $this->set('result',$result);


    
    }

    
		
}
?>