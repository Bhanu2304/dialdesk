<?php
	class ObImportsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler','Session');
	public $uses=array('ObFieldValue','CampaignName','TmpObData','Obup','ObAllocationMaster','ObCampaignDataMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
			'index',
			'add',
			'download_campaign',
                        'delete_allocation',
                        'get_campaign',
			'download');
    }
	
	public function index() {
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');			
		$Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId)));
		$this->set('Campaign',$Campaign);

                /* View Allocation */
                
                $allocArr = $this->ObAllocationMaster->find('all',array('conditions' =>array('ClientId' => $this->Session->read('companyid'))));
                
                $allocationData=array();
		foreach($allocArr as $row){
                    $allocationData[]=array(
                        'id'=>$row['ObAllocationMaster']['id'],
                        'cmapname'=>$this->get_campaign($ClientId,$row['ObAllocationMaster']['CampaignId']),
                        'allocname'=>$row['ObAllocationMaster']['AllocationName'],
                        'createdate'=>$row['ObAllocationMaster']['CreateDate']
                    );
		}
                 
                $this->set('viewAlloc',$allocationData);
                
                /* View Allocation Data */
                
                
                /*
                $alockId=array();
                foreach($allocArr as $alock){
                    $alockId[]=$alock['ObAllocationMaster']['id'];
                }
               
                $allocArr = $this->ObCampaignDataMaster->find('all',array('conditions' =>array('AllocationId' =>$alockId)));
                
                $allocatedData=array();
		foreach($allocArr as $alData){
                    $Allocfield=$this->get_allocation_field($ClientId,$alData['ObCampaignDataMaster']['AllocationId']);
                    
                    for($i=1;$i<=3;$i++){
                        $fldArr['Field'.$i]=$alData['ObCampaignDataMaster']['Field'.$i];
                    }
                    
                    $allocationData[]=array(
                        'id'=>$alData['ObCampaignDataMaster']['id'],
                        'allocname'=>$this->get_allocation($ClientId,$alData['ObCampaignDataMaster']['AllocationId']),
                        $fldArr,
                       
                        'createdate'=>$alData['ObCampaignDataMaster']['CreationDate']
                    );
		}
                print_r($allocationData);die;

                $this->set('viewAllocData',$allocatedData);
                */
                
                
                
                
                

		if($this->request->is("POST") && !empty($this->request->data)){
			$id=$this->request->data['ObImports']['CampaignName'];
			$alocationName=addslashes(trim($this->request->data['ObImports']['AllocationName']));
			$data['ObCampaignDataMaster']['CreationDate']= date('Y-m-d H:i:s');
			$data['ObCampaignDataMaster']['ClientId']= $ClientId;
			
			$existAlocation=array('ClientId'=>$ClientId,'CampaignId'=>$id,'AllocationName'=>$alocationName);
			if($this->ObAllocationMaster->find('first',array('fields'=>array('id'),'conditions'=>$existAlocation))){
				$this->Session->setFlash("Already Exists at Given Allocation Name");
				$this->redirect(array('action' => 'index'));
			}
			
			$csv_file = $this->request->data['ObImports']['File']['tmp_name'];
			$FileTye = $this->request->data['ObImports']['File']['type'];
			$info = explode(".",$this->request->data['ObImports']['File']['name']);
		
			if(($FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
				
				if (($handle = fopen($csv_file, "r")) !== FALSE) {
					$filedata = fgetcsv($handle, 1000, ","); 
					
					$cntField=$this->count_field($ClientId,$id);
					
					if(count($filedata) != $cntField){
						$this->Session->setFlash("Does not Match This Campaign Formate.");
						$this->redirect(array('action' => 'index'));
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

				$this->Session->setFlash('<span style="color:green;">Upload SuccessFully</span>');
				$this->redirect(array('action' => 'index'));
			}
			else{
				$this->Session->setFlash('File Format not valid! Upload in CSV formate.');
			}
		}	
	}
	
        public function delete_allocation(){
            $id=$this->request->query['id'];
            if($this->ObAllocationMaster->deleteAll(array('id'=>$id,'ClientId' => $this->Session->read('companyid')))){
                $this->ObCampaignDataMaster->deleteAll(array('AllocationId'=>$id));
            }
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