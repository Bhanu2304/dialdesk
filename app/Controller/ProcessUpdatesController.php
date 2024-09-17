<?php
class ProcessUpdatesController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','ProcessUpdation','ProcessRead','BalanceMaster','BillingMaster',
            'BillMasterPost','BalanceMasterHistory','vicidialCloserLog');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('index','view','delete_data','read_data','report','export_report','view_invoice','view_statement','update_client_plan','get_client_name','allocate_plan','getbillmonth','view_prepaid_invoice','get_oldbill_date','get_tagging_status');
        if(!$this->Session->check("admin_id"))
        {
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }
    }

    public function index()
    {
        $this->layout = "user";

        $role = $this->Session->read("role");
        $companyid = $this->Session->read("companyid");

        if($role == 'client')
        {
            $client =$this->RegistrationMaster->find('first',array('fields'=>array("company_id","Company_name"),'conditions'=>array('company_id'=>$companyid,'Status'=>'A')));
            $this->set('companyid',$companyid);  
        }else{

        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));  
        

        }
        $this->set('client',$client);
        $this->set('role',$role);
        //$this->set('companyid',$companyid);

        if($this->request->is('Post'))
        {
            $data   =   $this->request->data['ProcessUpdates'];
            $validfrom =  $this->request->data['validfrom'];
            $validtill =  $this->request->data['validtill'];
            $checkcompany=$this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$data['clientID'],'Status'=>'A')));
            //print_r($data);die;
            $botArr = array();
            $botArr['date_time']    = $data['datetime'];
            $botArr['ClientId']     = $data['clientID'];
            $botArr['company_name'] = $checkcompany['RegistrationMaster']['company_name'];
            $botArr['process_update'] = $data['processdate'];
            $botArr['type']         = $data['permanent'];
            $botArr['valid_from']   = $validfrom;
            $botArr['valid_till']   = $validtill;

            //print_r($botArr);die;

            $this->ProcessUpdation->save($botArr);die;

        }
    }

    public function view()
    {
        $this->layout = "user";

        // $data = $this->ProcessUpdation->find('all',array(
        //     'order' => array('ProcessUpdation.id' => 'desc')
        // ));
        $qry = "SELECT pu.id,pu.date_time,pu.process_update,pu.id,pu.company_name,pu.type,pu.valid_from,pu.valid_till,COUNT(pr.id) AS `Total` FROM process_update pu
        LEFT JOIN process_read pr ON pu.id = pr.process_id GROUP BY pu.id ";
        $data = $this->ProcessUpdation->query($qry);
        //print_r($data);die;
        $this->set('data',$data);
    }

    public function delete_data()
    {
        $id  = $this->request->query['id'];
            
		$this->ProcessUpdation->delete(array('id'=>$id));
		$this->redirect(array('action' => 'index'));
    }

    public function read_data()
    {
        $id = $this->request->data['process_id'];
        $read_id = $this->Session->read('admin_id');

        $dataArr = array();
        $dataArr['process_id']    = $id;
        $dataArr['read_id']     = $read_id;

        $save = $this->ProcessRead->save($dataArr);
        if($save)
        {
            $update_date=date('Y-m-d H:i:s'); 
            $this->ProcessUpdation->updateAll(array('updated_at'=>"'$update_date'"),array('id'=>$id));
        }

        $this->Session->delete('notification_data');
        $this->Session->delete('Noti_count');

        $qry1 = "SELECT * from process_read where read_id='$read_id'";
        $notification_read = $this->ProcessRead->query($qry1);
        //print_r($notification_read);die;
        if(!empty($notification_read))
        {
                $q1 ='';
                foreach($notification_read as $noti)
                {
                $ReadData = $noti['process_read']['process_id'];
                $ch = explode(",", $ReadData);
                foreach ($ch as $ot) {
                    $q1 .="'$ot', ";
                }
                }
                $q1 = substr($q1, 0, -2);
            
            $qry = "SELECT * FROM process_update WHERE id NOT IN($q1) ORDER BY id DESC";
            $notification_data = $this->ProcessUpdation->query($qry);

        }else{

            $qry = "SELECT * FROM process_update ORDER BY id DESC";
            $notification_data = $this->ProcessUpdation->query($qry);

        }

        $Noti_count = count($notification_data);
   

        $this->Session->write('notification_data',$notification_data);
        $this->Session->write("Noti_count",$Noti_count);die;
    }

    public function report()
    {
        $this->layout = "user";

        if($this->request->is("POST"))
        {
            //print_r($this->request->data);die;
            $search=$this->request->data['ProcessUpdates'];
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time =   date("Y-m-d",strtotime("$FromDate"));
            $end_time   =   date("Y-m-d",strtotime("$ToDate"));
            
            // $qry = "SELECT *,DATE_FORMAT(created_at,'%d-%b-%y') dater FROM `process_update` WHERE  DATE(created_at) BETWEEN '$start_time' AND '$end_time'";
            // $data=$this->ProcessUpdation->query($qry);
            //print_r($data);die;
            $qry = "SELECT pu.id,pu.date_time,pu.process_update,pu.id,pu.company_name,pu.type,pu.valid_from,pu.valid_till,pr.read_id,pr.read_name FROM process_update pu
            LEFT JOIN process_read pr ON pu.id = pr.process_id where DATE(pr.created_at) BETWEEN '$start_time' AND '$end_time'";
            $data = $this->ProcessUpdation->query($qry);
	
            $this->set('data',$data);
         
        }
    }

    public function export_report()
    {
        if($this->request->is("POST"))
        {

                
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=process_report.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
        
        
            $search=$this->request->data['ProcessUpdates'];
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));
        
            $qry = "SELECT pu.id,pu.date_time,pu.process_update,pu.id,pu.company_name,pu.type,pu.valid_from,pu.valid_till,pr.read_id,pr.read_name FROM process_update pu
                LEFT JOIN process_read pr ON pu.id = pr.process_id where DATE(pr.created_at) BETWEEN '$start_time' AND '$end_time'";
            $data=$this->ProcessUpdation->query($qry);
            
            
            ?>
        
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>			
                        <th>Client</th>
                        <th>Process Name</th>
                        <th>Read Agent Name</th>
                    </tr>
                    
                </thead>
                <tbody>
                    <?php foreach($data as $record) { ?>
                        <tr>
                            <td><?php echo date_format(date_create($record['pu']['date_time']),'d M Y H:i:s'); ?></td>
                            <td><?php echo $record['pu']['company_name']; ?></td>
                            <td><?php echo $record['pu']['process_update']; ?></td>
                            <td><?php echo $record['pr']['read_name']; ?></td>
                            <?php //echo substr($record['whatsapp_template']['number'],0,10); ?>
                        </tr>	
                    <?php } ?>
                </tbody>
            </table>

            <?php
            }
            
           
                
             die;   
       
        
    }

}
?>