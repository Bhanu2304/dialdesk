<?php
class InboundGroupsController extends AppController{
    //ini_set('max_execution_time', '0');
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','ObdData','ObdList','VicidialListMaster','DidMaster','DidHistoryMaster','VicidialInboundGroup');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('index','addlist','delete_list','report','export_report','clientdid','update_priority');
        // if(!$this->Session->check("admin_id"))
        // {
        //         return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        // }
    }

    public function index(){
        $this->layout='user';
        $client = $this->RegistrationMaster->query("SELECT * FROM `registration_master` WHERE status='A' and (campaignid IS NOT NULL && campaignid != '')");

        $CampInf = array();
        foreach($client as $v)
        {
             $Clientid = $v['registration_master']['company_name']; 
             $group_id = $v['registration_master']['campaignid']; 

            $qry = "select * from vicidial_inbound_groups where group_id in($group_id)";

            $this->VicidialInboundGroup->useDbConfig = 'db2';
            $dataArr = $this->VicidialInboundGroup->query($qry);	
            $CampInf[$Clientid][$group_id] = $dataArr; 

        }


        $this->set('dataArr',$CampInf);

        if($this->request->is('Post')){
            //print_r($this->request->data);die;
            
            $group_id = $this->request->data['group_id'];
            $priority = $this->request->data['priority'];

            $dataArr = array('queue_priority'=>"'".$priority."'");
            $this->VicidialInboundGroup->updateAll($dataArr,array('group_id'=>$group_id));
            $this->VicidialInboundGroup->useDbConfig = 'db2';
 

        }
    }
    public function update_priority()
    {
        if($this->request->is('Post')){
            $data = $this->request->data['priority'];
            //print_r($data['AKAI']['priority']);die;
            $abc = array();
            foreach($data as $priority=>$key)
            {
                $this->VicidialInboundGroup->useDbConfig = 'db2';
                $this->VicidialInboundGroup->updateAll($key,array('group_id'=>$priority));
                
            }

        }


    }
    



   


}
?>