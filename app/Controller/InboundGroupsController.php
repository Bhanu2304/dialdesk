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

            $qry = "select *,'$Clientid' company_name from vicidial_inbound_groups where group_id in($group_id) ";

            $this->VicidialInboundGroup->useDbConfig = 'db2';
            $dataArr = $this->VicidialInboundGroup->query($qry);
            foreach($dataArr as $data)
            {
                $CampInf[$data['vicidial_inbound_groups']['queue_priority']][] = $data; 
                $sort_arr[] = $data['vicidial_inbound_groups']['queue_priority'];
            }	
            
            

        }
        $sort_arr=array_unique($sort_arr);
        rsort($sort_arr);
        #print_r($sort_arr);exit;
        #print_r($CampInf);die;
        
        $this->set('shortArr',$sort_arr);
        $this->set('dataArr',$CampInf);



        if($this->request->is('Post')){
            //print_r($this->request->data);die;
            
            $group_id = $this->request->data['group_id'];
            $priority = $this->request->data['priority'];
            
            $dataArr = array('queue_priority'=>"'".$priority."'");
           #ksort($priority);
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
    public function export_report()
    {
        $client = $this->RegistrationMaster->query("SELECT * FROM `registration_master` WHERE status='A' and (campaignid IS NOT NULL && campaignid != '')");

        $CampInf = array();

       
        foreach($client as $v)
        {
             $Clientid = $v['registration_master']['company_name']; 
             $group_id = $v['registration_master']['campaignid']; 

            $qry = "select *,'$Clientid' company_name from vicidial_inbound_groups where group_id in($group_id) ";

            $this->VicidialInboundGroup->useDbConfig = 'db2';
            $dataArr = $this->VicidialInboundGroup->query($qry);
            foreach($dataArr as $data)
            {
                $CampInf[$data['vicidial_inbound_groups']['queue_priority']][] = $data; 
                $sort_arr[] = $data['vicidial_inbound_groups']['queue_priority'];
            }	
            
            

        }
        $shortArr=array_unique($sort_arr);
        rsort($shortArr);
        header("Content-Type: application/vnd.ms-excel; name='excel'");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=inbound_groups.xls");
        header("Pragma: no-cache");
        header("Expires: 0"); ?>

        <table cellpadding="0" cellspacing="0" border="1" class="table table-striped table-bordered" >
        <thead>
            <tr>
               <th>Sr No.</th>
                <th>Company Name</th>
                <th>Group Id</th>
                <th>Group Name</th>
                <th>Priority</th>
               
            </tr>
        </thead>
        <tbody>
    <?php $i =0; foreach($shortArr as $data1=>$key1)
    {
        // echo $key1;
        
        foreach($CampInf[$key1] as $now){  
            #print_r($data);exit;
            ?>

                <tr>
                   <td><?php echo ++$i;?></td>
                    <td><?php echo $now['0']['company_name']; ?></td>
                    <td><?php echo $now['vicidial_inbound_groups']['group_id'];?></td>
                    <td><?php echo $now['vicidial_inbound_groups']['group_name'];?></td>
                    <td><?php echo $now['vicidial_inbound_groups']['queue_priority']; ?></td>  
                </tr>
      <?php  } 
    }   ?>
        </tbody>
    </table><?php die;

        

    }
    



   


}
?>