<?php
class AdmindataAllocationsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('CampaignName','ObAllocationMaster','ObCampaignDataMaster','RegistrationMaster','AgentMaster');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('get_campaign','get_allocation','get_count');
        // if(!$this->Session->check("admin_id")){
        //     return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        // }		
    }
    
    public function index() {

            $this->layout='user';
        
            if($this->Session->read('role') =="admin"){
                $company=$this->RegistrationMaster->find('list',array('fields'=>array("company_id","company_name")));
                $this->set('company',$company);

                $data = $this->AgentMaster->find('all',array('conditions'=>array('status'=>'A')));
                $this->set('page_record',$data);
            }else{
                $clientId   = $this->Session->read('companyid');
                $company =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','company_id'=>$clientId),'order'=>array('Company_name'=>'asc')));
                
                $this->set('company',$company);

                $data = $this->AgentMaster->find('all',array('conditions'=>array('status'=>'A','FIND_IN_SET(' . $clientId . ', ClientRights)')));
                $this->set('page_record',$data);
            }


            if($this->request->is("POST")){
                    $allocationid=$this->request->data['AdmindataAllocations']['AllocationName'];
                    $Count=$this->request->data['AdmindataAllocations']['Count'];
                    $allocated=$this->request->data['AdmindataAllocations']['Allocated'];
                    $agentid=$this->request->data['Agent'];

                    $arr = array();
                    if($allocated%count($agentid)==0)
                    {
                            for($i =0; $i<count($agentid);$i++)
                            {
                                    $arr[0][$agentid[$i]] = floor($allocated/count($agentid));					
                            }				
                    }
                    else
                    {
                            for($i =0; $i<count($agentid);$i++)
                            {
                                    $arr[0][$agentid[$i]] = floor($allocated/count($agentid));					
                            }

                            for($i =0; $i<$allocated%count($agentid); $i++)
                            {
                                    $arr[1][$agentid[$i]] = 1;
                            }
                    }

                    //print_r($arr);die;
                    if(!empty($arr[0]))
                    {
                    foreach($arr[0] as $key=>$value){

                            echo $key;

                            echo $qry="UPDATE ob_campaign_data set AgentId =$key WHERE AllocationId=$allocationid and AgentId IS NULL LIMIT $value";
                    $this->ObCampaignDataMaster->query($qry);
                    }
                    if(!empty($arr[1]))
                    {

                    foreach($arr[1] as $key=>$value){
                            echo $qry="UPDATE ob_campaign_data set AgentId =$key WHERE AllocationId=$allocationid and AgentId IS NULL LIMIT $value";
                            $this->ObCampaignDataMaster->query($qry);
                    }
                    }}
                    $this->Session->setFlash("Allocated Successfully.");
                    $this->redirect(array('action' => 'index'));

            }

    }
	
	public function get_campaign(){
		$this->layout='ajax';
		if($_REQUEST['id']){
		$this->set('campaign',$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$_REQUEST['id']))));
		}
	}
	
	public function get_allocation(){
		$this->layout='ajax';
		if($_REQUEST['campid']){
			$campaignid=$_REQUEST['campid'];
			$clientid=$_REQUEST['clientid'];
			$allocation=$this->ObAllocationMaster->find('list',array('fields'=>array("id","AllocationName"),'conditions'=>
			array('ClientId'=>$clientid,'CampaignId'=>$campaignid)));
			$this->set('allocations',$allocation);
		}
	}
	
	public function get_count(){
		$this->layout='ajax';
		if($_REQUEST['AllocationId']){
			$data=array('AllocationId'=>$_REQUEST['AllocationId'],'AgentId'=>NULL);
			echo count($this->ObCampaignDataMaster->find('all',array('fields'=>array("id"),'conditions'=>$data)));
			die;
		}
	}
	
}
?>