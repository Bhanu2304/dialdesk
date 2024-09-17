<?php
class ObReallocationsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    
    public $uses=array(
        'CallMasterOut',
        'AgentMaster',
        'ObecrMaster',
        'ObFieldValue',
        'CampaignName',
        'TmpObData',
        'Obup',
        'ObAllocationMaster',
        'ObCampaignDataMaster',
        'ListMaster',
        'VicidialListMaster'
        );
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
                'index',
                'add',
                'download_campaign',
                'delete_allocation',
                'getAllocation',
                'getScenario',
                'getAgent',
                'getAllocationName'
                );
    }
    
    public function getAllocation(){
        $this->layout='ajax';
        if(isset($_REQUEST['campid'])){
            $clientid   =   $this->Session->read('companyid');
            $campaignid =   $_REQUEST['campid'];
            $allocation =   $this->ObAllocationMaster->find('list',array('fields'=>array("id","AllocationName"),'conditions'=>array('ClientId'=>$clientid,'CampaignId'=>$campaignid,'AllocationStatus'=>'A')));
            
            if(!empty($allocation)){
                echo "<option value='' >Select Allocation</option>";
                foreach($allocation as $key=>$val){
                    echo "<option value='$key'>$val</option>";
                }
            }
            else{
                echo "<option value='' >Select Allocation</option>";
            }
            die;
        }
    }
    
    public function getScenario(){
        $this->layout='ajax';
        
        
        if(isset($_REQUEST['campid'])){
            $clientid   =   $this->Session->read('companyid');
            $campaignid =   $_REQUEST['campid'];
            $allocation =   $this->ObecrMaster->find('list',array('fields'=>array("ecrName","ecrName"),'conditions'=>array('ecrName !='=>'Contacted','Client'=>$clientid,'CampaignId'=>$campaignid,'Label'=>1),'group'=>'ecrName','order'=>array('ecrName'=>'asc')));
            
            
            
            if(!empty($allocation)){
                echo "<option value='' >Select Secnario</option>";
                //echo "<option value='ALL' >ALL</option>";
                foreach($allocation as $key=>$val){
                    if(strtolower($val) !="no need to call"){
                        echo "<option value='$key'>$val</option>";
                    }
                }
            }
            else{
                echo "<option value='' >Select Secnario</option>";
            }
            die;
        }
    }
    
    public function getAgent(){
        $this->layout   =   'ajax';
        $allocation     =   $this->AgentMaster->find('list',array('fields'=>array("id","username"),'conditions'=>array('status'=>'A'),'order'=>array('username'=>'asc')));
        if(!empty($allocation)){
            echo "<option value='' >Select Agent</option>";
            foreach($allocation as $key=>$val){
                echo "<option value='$key'>$val</option>";
            }
        }
        else{
            echo "<option value='' >Select Agent</option>";
        }
            die;
        
    }
    
    public function getAllocationName(){
        $this->layout='ajax';
        if(isset($_REQUEST['AttemptId'])){
            $clientid       =   $this->Session->read('companyid');
            $AllocationId   =   $_REQUEST['AllocationId'];
            $AttemptId      =   $_REQUEST['AttemptId'];
            $allocation     =   $this->ObAllocationMaster->find('first',array('fields'=>array("AllocationName"),'conditions'=>array('ClientId'=>$clientid,'id'=>$AllocationId,'AllocationStatus'=>'A')));

            if(!empty($allocation)){
                echo $allocation['ObAllocationMaster']['AllocationName']."_".$AttemptId;
            }
            else{
                echo "";
            }
            die;
        }
    }

    public function index() {
        $this->layout='user';
        
        $ClientId = $this->Session->read('companyid');			
        $Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId,'CampaignStatus'=>'A')));
        $this->set('Campaign',$Campaign);

        $viewListId = $this->ListMaster->find('list',array('fields'=>array("list_id","list_id"),'conditions'=>array('client_id'=>$ClientId)));
        $this->set('viewListId',$viewListId);

        if($this->request->is("POST") && !empty($this->request->data)){
            $Request            =   $this->request->data['ObReallocations'];
            $AgentId            =   $Request['AgentId'];
            $ScenarioName       =   $Request['ScenarioName'];
            $AllocationId       =   $Request['AllocationId'];
            $uploadType         =   $Request['uploadType'];
            $listid             =   $Request['listid'];
            $id                 =   $Request['CampaignName'];      
            $allocid            =   $AllocationId;
            
            
            
            $MaxAttemptCount    =   $this->CallMasterOut->query("SELECT MAX(AttemptStatus) MaxAttempt FROM call_master_out WHERE ClientId='$ClientId' AND AllocationId='$AllocationId'");
            $MaxAttempt         =   $MaxAttemptCount[0][0]['MaxAttempt'];
            
            $CallMansterOut     =   $this->CallMasterOut->find('all',array('fields'=>array('DataId','Category1'),'conditions'=>array('ClientId'=>$ClientId,'AllocationId'=>$AllocationId,'Category1'=>$ScenarioName,'AttemptStatus'=>$MaxAttempt)));
            
            /*
            if($ScenarioName !="ALL"){
                $CallMansterOut     =   $this->CallMasterOut->find('all',array('fields'=>array('DataId','Category1'),'conditions'=>array('ClientId'=>$ClientId,'AllocationId'=>$AllocationId,'Category1'=>$ScenarioName,'AttemptStatus'=>$MaxAttempt)));
            }
            else{
                $CallMansterOut     =   $this->CallMasterOut->find('all',array('fields'=>array('DataId','Category1'),'conditions'=>array('ClientId'=>$ClientId,'AllocationId'=>$AllocationId,'Category1 !='=>'Contacted','AttemptStatus'=>$MaxAttempt))); 
            } 
            */
            
            foreach ($CallMansterOut as $value){
                $ObCampaignDataId   =   $value['CallMasterOut']['DataId'];
                
                if($AgentId =="Same"){
                    $this->ObCampaignDataMaster->query("UPDATE `ob_campaign_data` SET DataStatus=NULL,CallDate=NULL WHERE id='$ObCampaignDataId' AND DataStatus IS NOT NULL AND AttemptStatus <='3'");
                }
                else{
                    $this->ObCampaignDataMaster->query("UPDATE `ob_campaign_data` SET AgentId='$AgentId',DataStatus=NULL,CallDate=NULL WHERE id='$ObCampaignDataId' AND DataStatus IS NOT NULL AND AttemptStatus <='3'");
                } 
                
                if($uploadType ==="pd"){   
                    $ObCampdataArr      =   $this->ObCampaignDataMaster->find('first',array('fields'=>array('Field1'),'conditions'=>array('id'=>$ObCampaignDataId,'DataStatus !='=>NULL,'AttemptStatus <='=>3)));
                    $phone_number       =   $ObCampdataArr['ObCampaignDataMaster']['Field1'];

                    $list_value="('".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','NEW','{$listid}','$allocid','-4.00','N','1','$phone_number'".")";
                    $list_impt_qry="INSERT INTO asterisk.vicidial_list (entry_date,modify_date,`status`,list_id,source_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number) VALUES $list_value";                        
                    $this->VicidialListMaster->useDbConfig = 'db2';
                    $this->VicidialListMaster->query($list_impt_qry);                           
                }
               $this->ObCampaignDataMaster->query("UPDATE `ob_campaign_data` SET AttemptStatus=AttemptStatus+1 WHERE id='$ObCampaignDataId' AND AttemptStatus <='3'"); 
            }
            
            

            $this->Session->setFlash("Your re allocation process creation successfully.");
            $this->redirect(array('action' => 'index'));
        }	
    }
	
        public function delete_allocation(){
            $id=$this->request->query['id'];
            
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
            
            $this->ObAllocationMaster->query("UPDATE ob_allocation_name SET AllocationStatus='D',update_user='$update_user',update_date='$update_date' WHERE id='$id'");
            
            /*
            if($this->ObAllocationMaster->deleteAll(array('id'=>$id,'ClientId' => $this->Session->read('companyid')))){
                $this->ObCampaignDataMaster->deleteAll(array('AllocationId'=>$id));
            }
            */
            $this->redirect(array('action' => 'index'));
        }
        
        public function get_campaign($ClientId,$Campaign){
            $data=$this->CampaignName->find('first',array('fields'=>array("CampaignName"),'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));	
            if(!empty($data)){
                return $data['CampaignName']['CampaignName'];
            }
	}
        
        public function get_allocation($ClientId,$allockid){
            $data=$this->ObAllocationMaster->find('first',array('fields'=>array("AllocationName"),'conditions'=>array('ClientId'=>$ClientId,'id'=>$allockid)));	
            if(!empty($data)){
                return $data['ObAllocationMaster']['AllocationName'];
            }
	}
        
	
	public function get_allocation_field($ClientId,$Campaign){
		$data=$this->ObAllocationMaster->find('first',array('fields'=>array("TotalCount"),'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));
		if(!empty($data)){
                $TotalCount=$data['ObAllocationMaster']['TotalCount'];
		return $TotalCount;
                }
	}
        
        public function count_field($ClientId,$Campaign){
		$data=$this->CampaignName->find('first',array('fields'=>array("TotalCount"),'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));
		if(!empty($data)){
                $TotalCount=$data['CampaignName']['TotalCount'];
		return $TotalCount;
                }
	}
        
	
	
	public function download_campaign() {
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');			
		$Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId)));
		$this->set('Campaign',$Campaign);	
	}
	
	public function download() {
		$this->layout='ajax';
		$ClientId = $this->Session->read('companyid');
		$Campaign= $this->request->query['id'];
		$data=$this->CampaignName->find('first',array('fields'=>array("TotalCount"),'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));
		$TotalCount=$data['CampaignName']['TotalCount'];
		$TotalField=array();
		for($i=1;$i<=$TotalCount;$i++){$TotalField[]="Field$i";}
		$tArr=$this->CampaignName->find('first',array('fields'=>$TotalField,'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));
		$this->set('Data',$tArr['CampaignName']);
	}
		
	
}

?>