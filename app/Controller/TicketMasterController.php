<?php
class TicketMasterController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('RegistrationMaster','FieldValue','TrainingMaster','TrainingDetails','WhatsappTicket','WhatsappIssuelist','WhatsappNonRegNumber');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
        //$this->Auth->allow('index','updateticket');
        
        $this->Auth->allow('index','updateticket','report','export_complaint','view_non_regnumber','update_non_regnumber');
        
    }
	
    public function index() {
        $this->layout='user';

        $data = $this->WhatsappTicket->find('all',array(
            'order' => array('WhatsappTicket.id' => 'desc'),
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
        $auth_name = $this->Session->read('admin_name');
        $name = str_replace(' ', '%', $auth_name);
        //echo $name;die;
        $id     =   $this->request->data['id'];	
        $data   =   $this->request->data['TicketMaster'];

        $acknowledgement   =   $data['acknowledgement'];
        $status    =   $data['status'];
        $remarks   =   $data['remarks'];
        $expclose_date   =   $data['FromDate'];

        $actclose_date = '';

        if(!empty($status))
        {
            if($status == 'WIP')
            {
                $url = "https://192.168.10.20/lms/public/dialdesk/whatsapp_dialdesk.php?status=WIP&id=$id&name=$name&exdate=$expclose_date";
                
            }else
            {
                $actclose_date    .=   date('Y-m-d H:i:s');
                $url = "https://192.168.10.20/lms/public/dialdesk/whatsapp_dialdesk.php?status=close&id=$id";
                
            }
            //echo $url;die;
             $ch = curl_init();
           
             curl_setopt($ch, CURLOPT_URL, $url);
             curl_setopt($ch, CURLOPT_HEADER, 1);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

             $data = curl_exec($ch);
          
             //return $data;die;
            
        }
     
        $dataArr = array(
                    'aknowledgement'=>"'".$acknowledgement."'",
                    'status'=>"'".$status."'",
                    'remarks'=>"'".$remarks."'",
                    'ex_close_date'=>"'".$expclose_date."'",
                    'ac_close_date'=>"'".$actclose_date."'",
                    'updated_at'=>"'".date('Y-m-d H:i:s')."'"
                    );
        
        $this->WhatsappTicket->updateAll($dataArr,array('id'=>$id));die;
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
            
            
            $clientId   =   $this->Session->read('companyid');
           $search=$this->request->data['TicketMaster'];
           //print_r($search);die;
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));
            

        // $select1 = "SELECT * FROM `dialdesk_whatsapp_ticket` WHERE complete='1'";
        // $whatsapp_ticket = $this->WhatsappTicket->query($select1);
        // $headerArr = array();
        // //$openArr = array();
        //     foreach($whatsapp_ticket as $w_tic)
        //     {
        //         $status = $w_tic['dialdesk_whatsapp_ticket']['status'];
        //         $head = $w_tic['dialdesk_whatsapp_ticket']['concern1'];
        //         $headerArr[$head] = $head;

        //     }

        //     $select2 = "SELECT SUM(IF(STATUS != 'Closed' OR STATUS IS NULL,1,0)) AS OPEN , SUM(IF(STATUS = 'Closed',1,0)) AS CLOSE , COUNT(*) `Total` FROM `dialdesk_whatsapp_ticket`
        //     WHERE  complete='1'";
        //     $data = $this->WhatsappTicket->query($select2);

                $select = "SELECT SUM(IF(STATUS != 'Closed' OR STATUS IS NULL,1,0)) AS OPEN ,
                SUM(IF(STATUS = 'Closed',1,0)) AS CLOSE , 
                COUNT(*) `Total`,
                SUM(IF(DATE(ex_close_date) > DATE(ac_close_date) AND DATE(ex_close_date) = DATE(ac_close_date) OR DATE(ex_close_date) IS NULL ,1,0)) AS INTAT,
                SUM(IF(DATE(ex_close_date) < DATE(ac_close_date) OR DATE(ex_close_date) IS NOT NULL ,1,0)) AS OUTTAT,concern1 
                FROM `dialdesk_whatsapp_ticket`
                WHERE DATE(created_at)  BETWEEN  '$start_time' AND '$end_time' and complete='1' GROUP BY concern1";

            $dataArr = $this->WhatsappTicket->query($select);  
            //print_r($dataArr);die;
            $this->set('dataArr',$dataArr);
         
        }

    }
    

        public function export_complaint()
        {
            if($this->request->is("POST")){
                
           
                    header("Content-Type: application/vnd.ms-excel; name='excel'");
                    header("Content-type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=complaint_log_report.xls");
                    header("Pragma: no-cache");
                    header("Expires: 0");
               
                
                $search=$this->request->data['TicketMaster'];
                $FromDate   =   $search['startdate'];
                $ToDate     =   $search['enddate'];
                $start_time=date("Y-m-d",strtotime("$FromDate"));
                $end_time=date("Y-m-d",strtotime("$ToDate"));
                
                $select = "SELECT SUM(IF(STATUS != 'Closed' OR STATUS IS NULL,1,0)) AS OPEN ,
                SUM(IF(STATUS = 'Closed',1,0)) AS CLOSE , 
                COUNT(*) `Total`,
                SUM(IF(DATE(ex_close_date) > DATE(ac_close_date) AND DATE(ex_close_date) = DATE(ac_close_date) OR DATE(ex_close_date) IS NULL ,1,0)) AS INTAT,
                SUM(IF(DATE(ex_close_date) < DATE(ac_close_date) OR DATE(ex_close_date) IS NOT NULL ,1,0)) AS OUTTAT,concern1 
                FROM `dialdesk_whatsapp_ticket`
                WHERE DATE(created_at)  BETWEEN '$start_time' AND '$end_time' and complete='1' GROUP BY concern1";

               $dataArr = $this->WhatsappTicket->query($select);
                
                
                ?>
            
                <table cellspacing="0" border="1">
                    <tr>
                        <th>Issue</th>			
                        <th>Open</th>
                        <th>Close</th>
                        <th>Total</th>
                        <th>Within TAT</th>
                        <th>Beyond TAT</th>
                        <!-- <th>Comapny Name</th> -->
                    </tr>
                    <?php foreach($dataArr as $data) { ?>
                    <tr>
                            <td><?php echo $data['dialdesk_whatsapp_ticket']['concern1']; ?></td>
                            <td><?php echo $data['0']['OPEN']; ?></td>
                            <td><?php echo $data['0']['CLOSE']; ?></td>
                            <td><?php echo $data['0']['Total']; ?></td>
                            <td><?php echo $data['0']['INTAT']; ?></td>
                            <td><?php echo $data['0']['OUTTAT']; ?></td>
                    </tr>		
                    <?php } ?>
                </table>
    
                <?php
                }
                
               
                    
                 die;   
           
            
        }

        public function view_non_regnumber()
        {
            $this->layout='user';

            $ClientId = $this->Session->read('companyid');
            $data = $this->WhatsappNonRegNumber->find('all',array(
                'order' => array('WhatsappNonRegNumber.id' => 'desc'),
                'conditions' => array('status' => '0')
            ));

            //print_r($data);die;
            $this->set('data',$data);

            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
       
            $this->set('client',$client); 
        }

        public function update_non_regnumber()
        {
            $id     =   $this->request->data['id'];	
            $data   =   $this->request->data['TicketMaster'];
    
            $clientID   =   $data['clientID'];
            $number    =   $data['number'];
            $timestamp = date('Y-m-d H:i:s');

             $url = "https://192.168.10.20/lms/public/dialdesk/whatsapp_dialdesk.php?RegisterNumber=$number&REGID=$id";
             
            
             $ch = curl_init();
           
             curl_setopt($ch, CURLOPT_URL, $url);
             curl_setopt($ch, CURLOPT_HEADER, 1);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            $data = curl_exec($ch);
          
             //return $data;die;
             $result = substr($number, 2);

            $dataArr = array(
                        'cp3_phone'=>"'".$result."'",
                        'update_date'=>"'".$timestamp."'"
                        );
            
            $this->RegistrationMaster->updateAll($dataArr,array('company_id'=>$clientID));
            $this->WhatsappNonRegNumber->updateAll(array('status'=>'1',),array('id'=>$id));
            die;
        }


       
 


}
?>