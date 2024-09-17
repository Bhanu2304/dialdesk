<?php
class HarvestChatController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('RegistrationMaster','HarvestChat','TrainingMaster','TrainingDetails','WhatsappTicket','WhatsappIssuelist','WhatsappNonRegNumber');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
        //$this->Auth->allow('index','updateticket');
        
        $this->Auth->allow('index','updateticket','report','export_report','view_non_regnumber','update_non_regnumber','ticket');
        
    }

    public function index() {
        $this->layout='user';

        // $data = $this->HarvestChat->find('all',array(
        //     'order' => array('HarvestChat.id' => 'desc'),
        //     'conditions' => array('complete' => '1')
        // ));
        //print_r($data);die;
        $qry = "SELECT created_at,scenario,SUM(IF(title = 'Kitty',1,0)) AS Kitty,SUM(IF(title = 'HG',1,0)) AS HG,SUM(IF(title = 'Modern',1,0)) AS Modern 
        FROM dialdesk_whatsapp_harvest_gold dw WHERE complete='1' GROUP BY scenario";

        $data = $this->HarvestChat->query($qry); 
        //print_r($data);die; 
        $this->set('data',$data);
    }
	
    public function ticket() {
        $this->layout='user';

        $data = $this->HarvestChat->find('all',array(
            'order' => array('HarvestChat.id' => 'desc'),
            'conditions' => array('complete' => '1')
        ));
        //print_r($data);die;
        $this->set('data',$data);
    }
	
    public function updateticket()
    {
        // $ClientId = $this->Session->read('admin_name');
        // echo $ClientId;die;
		// $result = $this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$ClientId)));
        //print_r($this->request->data);die;

        //echo $name;die;
        $id     =   $this->request->data['id'];	
        $data   =   $this->request->data['HarvestChat'];

        $status    =   $data['status'];
        $remarks   =   $data['remarks'];
        $expclose_date   =   $data['FromDate'].' '.date('H:i:s');

        $actclose_date = '';
        if(!empty($status))
        {
            if($status == 'Closed')
            {
                $actclose_date    .=   date('Y-m-d H:i:s');
                
            }

        }
     
        $dataArr = array(
                    'status'=>"'".$status."'",
                    'remarks'=>"'".$remarks."'",
                    'ex_close_date'=>"'".$expclose_date."'",
                    'ac_close_date'=>"'".$actclose_date."'",
                    'updated_at'=>"'".date('Y-m-d H:i:s')."'"
                    );
        
        $this->HarvestChat->updateAll($dataArr,array('id'=>$id));die;
    }

    public function report() {
        $this->layout='user';
                // start whatsapp 

                $w_qry = "SELECT SUM(IF(closure_status = 'Satisfied',1,0)) AS SATISFIED,
                SUM(IF(closure_status = 'Not Satisfied',1,0)) AS NOTSATISFIED FROM dialdesk_whatsapp_ticket WHERE  complete='1'";
        
                $Closure_status = $this->WhatsappTicket->query($w_qry);
                 $this->set('Closure_status',$Closure_status);
        
                // end whatsapp 
         if($this->request->is("POST")){
            
            
           $search=$this->request->data['HarvestChat'];
           //print_r($this->request->data);die;
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));
            

                $select = "SELECT * FROM `dialdesk_whatsapp_harvest_gold` dw
                WHERE DATE(created_at)  BETWEEN  '$start_time' AND '$end_time' and complete='1'";

            $dataArr = $this->HarvestChat->query($select);  
            //print_r($dataArr);die;
            $this->set('data',$dataArr);
         
        }

    }
    

        public function export_report()
        {
            if($this->request->is("POST")){
                
           
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=chat_report.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
            
                $search=$this->request->data['HarvestChat'];
                
                $FromDate   =   $search['startdate'];
                $ToDate     =   $search['enddate'];
                $start_time =   date("Y-m-d",strtotime("$FromDate"));
                $end_time   =   date("Y-m-d",strtotime("$ToDate"));
                
    
                $select = "SELECT * FROM `dialdesk_whatsapp_harvest_gold` dw WHERE DATE(created_at)  BETWEEN  '$start_time' AND '$end_time' and complete='1'";
    
                $dataArr = $this->HarvestChat->query($select);  

                ?>
            
                <table cellspacing="0" border="1">
                    <tr>
                        <th>S.No.</th>
                        <th>Chat Rcvd Mobile No.</th>			
                        <th>Ticket No.</th>
                        <th>Scenario</th>
                        <th>Sub Scenario</th>
                        <th>Name</th>
                        <th>Number</th>
                        <th>Pin Code</th>
                        <th>Name of Store</th>
                        <th>Expect Callback</th>
                        <th>Actual Callback</th>
                    </tr>
                    <?php $i=1; foreach($dataArr as $data) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $data['dw']['wa_id']; ?></td>
                        <td><?php echo $data['dw']['id']; ?></td>
                        <td><?php echo $data['dw']['scenario']; ?></td>
                        <td><?php echo $data['dw']['sub_scenario']; ?></td>
                        <td><?php echo $data['dw']['name']; ?></td>
                        <td><?php echo $data['dw']['phone_no']; ?></td>
                        <td><?php echo $data['dw']['pincode']; ?></td>
                        <td><?php echo $data['dw']['company_name']; ?></td>
                        <td><?php echo $data['dw']['ex_close_date'];  ?></td>
                        <td><?php echo $data['dw']['ac_close_date'];  ?></td>
                        <?php //echo substr($record['whatsapp_template']['number'],0,10); ?>
                    </tr>	
                    <?php } ?>
                </table>
                <?php
            }
            die;
        }

}
?>