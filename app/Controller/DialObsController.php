<?php
	class DialObsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler','Session');
    public $uses=array('RegistrationMaster','AbandCallMaster','ObAllocationMaster');

	
   /* public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
       
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }
    }*/

    public function beforeFilter() {
        parent::beforeFilter();
		if($this->Session->check('companyid'))
		{
        $this->Auth->allow(
			'index',
			'get_allocation');
		}
		else
		{$this->Auth->deny('index',
			'add',
			'view'
			);
 		}
    }
	
	public function index() {
            //ini_set('max_execution_time', 0);
         //print_r($_FILES); exit;  
            
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');			
		
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

		if($this->request->is("POST") && !empty($this->request->data)){
                    
			
			$cid = $this->request->data['ClientId'];
            $allocationid = $this->request->data['DialObs']['AllocationName'];
			
            /*$csv_file = $this->request->data['DialObs']['uploadfile']['tmp_name'];
			$FileTye = $this->request->data['DialObs']['uploadfile']['type'];
			$info = explode(".",$this->request->data['DialObs']['uploadfile']['name']);
			//print_r($this->request->data); die;
			if(($FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream' || $FileTye=='text/csv') && strtolower(end($info)) == "csv"){
				
				if (($handle = fopen($csv_file, "r")) !== FALSE) {
					$filedata = fgetcsv($handle, 1000, ","); 
					
					while (($filedata = fgetcsv($handle, 1000, ",")) !== FALSE) {
						
						$this->AbandCallMaster->saveAll(array('ClientId'=>"{$cid}",'PhoneNo'=>"{$filedata[0]}",'AbandNoCount'=>"1",'CallDate'=>"{$update_date}",'EntryDate'=>"{$update_date}",'CallType'=>"OB Call"));			
                                        
					 }
					 
				}

				$this->Session->setFlash('<span style="color:green;">Data Upload SuccessFully</span>');
				$this->redirect(array('action' => 'index'));
			}
			else{
				$this->Session->setFlash('File Format not valid! Upload in CSV format.');
			}*/

             $qry = "SELECT CampaignId FROM ob_allocation_name where id='$allocationid'"; 

             $sel_data= $this->AbandCallMaster->query($qry);

             $qry1 = "INSERT INTO aband_call_master (ClientId, PhoneNo, AbandNoCount,CallDate,EntryDate,CallType,Dataid,Campaignid) SELECT '{$cid}', Field1, '1','{$update_date}','{$update_date}','OB Call',id,'{$sel_data[0]['ob_allocation_name']['CampaignId']}' FROM ob_campaign_data WHERE AllocationId='$allocationid'"; 

             $update_data= $this->AbandCallMaster->query($qry1);

             $this->Session->setFlash('<span style="color:green;">Data Upload SuccessFully</span>');
             $this->redirect(array('action' => 'index'));

		}
        if($this->Session->read('role') =="admin"){
            $this->set('data',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc'))));
        }	
        else{
            $this->set('data',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('status'=>'A','company_id'=>$ClientId),'order'=>array('Company_name'=>'asc'))));
        }
		
	}
		

public function get_allocation(){
        $this->layout='ajax';
        if($_REQUEST['clientid']){
         //   $campaignid=$_REQUEST['campid'];
           $clientid=$_REQUEST['clientid'];
            $allocation=$this->ObAllocationMaster->find('list',array('fields'=>array("id","AllocationName"),'conditions'=>
            array('ClientId'=>$clientid)));

            $this->set('allocations',$allocation);
        }
    }
	
}

?>