<?php
class AdminCampaignsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('CampaignName','PurposeCampaign','EcrCampaign','CampaignMaster','Obup','RegistrationMaster','ObCampaignDataMaster','ObAllocationMaster');
  
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add_campaign','get_header_field','import_export_campaign','download','save_Campaign_data','count_field');
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
            $cmp=$this->CampaignName->find('all',array('conditions'=>array('ClientId'=>$ClientId)));
            $campaign_header=array();
            foreach($cmp as $row){
                $cmpId=$row['CampaignName']['id'];
                $cmpName=$row['CampaignName']['CampaignName'];
                $TotalCount=$row['CampaignName']['TotalCount'];
                $field=$this->get_header_field($ClientId,$cmpId,$TotalCount);
                $campaign_header[]=array('campaign'=>$cmpName,'field'=>$field);
            }
            $this->set('campaign_header',$campaign_header);
            $this->set('clientid',$ClientId);  
        }
        
        if($this->request->is('Post')){
            $data=$this->request->data['AdminCampaigns'];
            $ClientId =$data['clientID'];
            $cmp=$this->CampaignName->find('all',array('conditions'=>array('ClientId'=>$ClientId)));
            $campaign_header=array();
            foreach($cmp as $row){
                    $cmpId=$row['CampaignName']['id'];
                    $cmpName=$row['CampaignName']['CampaignName'];
                    $TotalCount=$row['CampaignName']['TotalCount'];
                    $field=$this->get_header_field($ClientId,$cmpId,$TotalCount);
                    $campaign_header[]=array('campaign'=>$cmpName,'field'=>$field);
            }
            $this->set('campaign_header',$campaign_header);
            $this->set('clientid',$ClientId);
            $this->set('clientid',$ClientId);  
        }
    }
    
    public function get_header_field($ClientId,$Campaign,$TotalCount){
        $TotalField=array();
        for($i=1;$i<=$TotalCount;$i++){$TotalField[]="Field$i";}
        $tArr=$this->CampaignName->find('first',array('fields'=>$TotalField,'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));
        return $tArr['CampaignName'];
    }
    
    public function add_campaign() {
        if($this->request->is("POST") && !empty($this->request->data)){
            $cid=$this->request->data['AdminCampaigns']['cid'];
            $data['CampaignName']['CreationDate'] = date('Y-m-d H:i:s');
            $data['CampaignName']['ClientId'] = $cid;
            $data['CampaignName']['CampaignName'] = addslashes($this->request->data['AdminCampaigns']['CampaignName']);
					
            $csv_file = $this->request->data['AdminCampaigns']['File'][0]['tmp_name'];
            $FileTye = $this->request->data['AdminCampaigns']['File'][0]['type'];
            $info = explode(".",$this->request->data['AdminCampaigns']['File'][0]['name']);
            if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
                    if (($handle = fopen($csv_file, "r")) !== FALSE) {
                            $i=1;
                            while (($filedata = fgetcsv($handle, 1000, ",")) !== FALSE) {

                                    for ($c=0; $c < count($filedata); $c++) {
                                            $field="Field$i";
                                            $data['CampaignName'][$field]=$filedata[$c];
                                            $i++;
                                    }
                                    $data['CampaignName']['TotalCount']=count($filedata);
                            }

                    }	

                    if($this->CampaignName->find('first',array('fields'=>array('id'),'conditions'=>$data))){
                            $this->Session->setFlash("Already Exists at Given Campaign Name");
                            $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
                    }else{
                            $this->CampaignName->save($data);
                            $this->Session->setFlash('<span style="color:green;">Campaign Create Successfully.</span>');
                            $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
                    }	
            }
            else{
                    $this->Session->setFlash('File Format not valid!=>'.$FileTye);
                    $this->redirect(array('action'=>'index','?' => array('id' =>$cid)));
            }				
	}
    }
    
    
    public function import_export_campaign() {
        $this->layout='adminlayout';
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
        $this->set('client',$client);
        $this->set('clientid',array());
        
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $ClientId =$_REQUEST['id'];
            $Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId)));
            $this->set('Campaign',$Campaign);
            $this->set('clientid',$ClientId);  
        }
        
        if($this->request->is('Post')){
            $data=$this->request->data['AdminCampaigns'];
            $ClientId =$data['clientID'];
            $Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId)));
            $this->set('Campaign',$Campaign);
            $this->set('clientid',$ClientId);  
        }   	
    }
	
    public function download() {
        $this->layout='ajax';
        $ClientId = $this->request->query['cid'];
        $Campaign= $this->request->query['id'];
        $data=$this->CampaignName->find('first',array('fields'=>array("TotalCount"),'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));
        $TotalCount=$data['CampaignName']['TotalCount'];
        $TotalField=array();
        for($i=1;$i<=$TotalCount;$i++){$TotalField[]="Field$i";}
        $tArr=$this->CampaignName->find('first',array('fields'=>$TotalField,'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));
        $this->set('Data',$tArr['CampaignName']);
    }
    
    public function save_Campaign_data() {
		if($this->request->is("POST") && !empty($this->request->data)){
                    $ClientId =$this->request->data['AdminCampaigns']['cid'];
                    
			$id=$this->request->data['AdminCampaigns']['CampaignName'];
			$alocationName=addslashes(trim($this->request->data['AdminCampaigns']['AllocationName']));
			$data['ObCampaignDataMaster']['CreationDate']= date('Y-m-d H:i:s');
			$data['ObCampaignDataMaster']['ClientId']= $ClientId;
			
			$existAlocation=array('ClientId'=>$ClientId,'CampaignId'=>$id,'AllocationName'=>$alocationName);
			if($this->ObAllocationMaster->find('first',array('fields'=>array('id'),'conditions'=>$existAlocation))){
				$this->Session->setFlash("Already Exists at Given Allocation Name");
                                $this->redirect(array('action'=>'import_export_campaign','?' => array('id' =>$ClientId)));
			}
			
			$csv_file = $this->request->data['AdminCampaigns']['File']['tmp_name'];
			$FileTye = $this->request->data['AdminCampaigns']['File']['type'];
			$info = explode(".",$this->request->data['AdminCampaigns']['File']['name']);
		
			if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
				
				if (($handle = fopen($csv_file, "r")) !== FALSE) {
					$filedata = fgetcsv($handle, 1000, ","); 
					
					$cntField=$this->count_field($ClientId,$id);
					
					if(count($filedata) != $cntField){
						$this->Session->setFlash("Does not Match This Campaign Formate.");
                                                 $this->redirect(array('action'=>'import_export_campaign','?' => array('id' =>$ClientId)));
					}
					
					$allocation=array('ClientId'=>$ClientId,'CampaignId'=>$id,'AllocationName'=>$alocationName,'CreateDate'=>date('Y-m-d H:i:s'),
					'TotalCount'=>count($filedata));
					$this->ObAllocationMaster->save($allocation);
					$allocid=$this->ObAllocationMaster->getLastInsertId();
					$data['ObCampaignDataMaster']['AllocationId']= $allocid;
					
					while (($filedata = fgetcsv($handle, 1000, ",")) !== FALSE) {
									
						for ($c=0; $c < count($filedata); $c++) {
							$n=$c+1;
							$field="Field$n";
							$data['ObCampaignDataMaster'][$field]=$filedata[$c];
						}
						$this->ObCampaignDataMaster->saveAll($data);
					 }
					 
				}

				$this->Session->setFlash('Upload SuccessFully');
                                 $this->redirect(array('action'=>'import_export_campaign','?' => array('id' =>$ClientId)));
			}
			else{
				$this->Session->setFlash('File Format not valid! Upload in CSV formate.');
                                 $this->redirect(array('action'=>'import_export_campaign','?' => array('id' =>$ClientId)));
			}
		}
           
	}
    
    public function count_field($ClientId,$Campaign){
        $data=$this->CampaignName->find('first',array('fields'=>array("TotalCount"),'conditions'=>array('ClientId'=>$ClientId,'id'=>$Campaign)));
	$TotalCount=$data['CampaignName']['TotalCount'];
	return $TotalCount;
    }
    
    
    
        
}
?>