<?php
class AbandonCallReportsController extends AppController
{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses = array('EcrRecord','vicidialLog','CallRecord','vicidialCloserLog','vicidialUserLog','CroneJob','AbandCallMaster','CallMaster','RegistrationMaster','EcrMaster','ClientCategory');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
        
    }
    
    public function index() 
    {
        $this->layout='user';
	    $ClientId = $this->Session->read('companyid');
    
             $search=$this->request->data['AbandonCallReports'];
           
             if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }
        
        
//        $this->set('report',$report);
    }
    
    public function export_abandon_call_reports() {
        

            
 
            $search     =   $this->request->data['AbandonCallReports'];
            $client	=  $search['clientID'];
            $startdate = implode("-",array_reverse(explode("-",$search['startdate'])));
            $enddate = implode("-",array_reverse(explode("-",$search['enddate'])));
            //print_r($data);die;
            $ClientId = $search['clientID'];
            
//            print_r($search);die;

            $qr_where = "";
            if($client== 'All')
            {
               
            }
            else
            {
             $qr_where ="and clientId='$client'";
            }
            $data =$this->RegistrationMaster->query("select *,date(CallDate) CallDate1 from aband_call_master where date(CallDate) between '$startdate' and '$enddate' $qr_where");
            //print_r($data);die;
            
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=abandoncalldetailanaylsisreports.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ?>
            <table cellpadding="0" cellspacing="0" border="2" class="table table-striped table-bordered" >
                <thead>
                        <tr>
                            <th>SrNo</th>
                            <th>Client Name</th>
                            <th>Customer Number</th>
                            <th>Date & Time of Abandon</th>
                            <th>Count Of Call</th>
                            <th>First Call Back Time</th>
                            <th>Abandon Status</th>
                            <th>Call Status</th>
                            <th>Scenario</th>
                            <th>Sub-Scenario</th>
                            <th>Resolution Status</th>
                            <th>Attempt Number</th>
                            <th>Average Duration Of Attempts Done</th>
                            <th>Agent Tagging Remarks</th>
         
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        foreach($data as $row){
                            $ClientId = $row['aband_call_master']['ClientId'];
                            $lead_id = $row['aband_call_master']['LeadId'];
                            $call_date= $row['0']['CallDate1']; 
                            $PhoneNo = $row['aband_call_master']['PhoneNo']; 
                                $Id = $row['aband_call_master']['Id'];
                            $call_master =$this->RegistrationMaster->query("select * from call_master where ClientId='$ClientId' and date(CallDate) = '$call_date'  and MSISDN='$PhoneNo'  limit 1");
                            $exist =$this->RegistrationMaster->query("select * from aband_call_master where ClientId='$ClientId' and id!='$Id' and PhoneNo='$PhoneNo' and date(CallDate) = '$call_date' limit 1");
                            $check_call_status = "SELECT t2.status AS STATUS
            FROM asterisk.vicidial_log t2
                        LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid LEFT JOIN vicidial_users vu ON t2.user=vu.user
                        WHERE DATE(t2.call_date) = '$call_date'  AND t2.campaign_id='dialdesk'
                        AND LEFT(t2.phone_number,10) = '$PhoneNo'
                        AND t2.lead_id IS NOT NULL";
                             $this->vicidialLog->useDbConfig = 'db2';
                             $call_status = $this->vicidialLog->query($check_call_status);
                            ?>
                        <tr>
                            <td><?php echo $i++;?></td>
                            
                            <td><?php echo $row['aband_call_master']['CompanyName'];?></td>
                            <td><?php echo $row['aband_call_master']['PhoneNo'];?></td>
                            <td><?php echo $row['aband_call_master']['CallDate'];?></td>
                            <td><?php echo $row['aband_call_master']['AbandNoCount'];?></td>
                            <td><?php echo $row['aband_call_master']['Callbackdate'];?></td>
                            <td><?php  // print_r($call_status);exit;
                            if($call_status[0]['t2']['STATUS']!='A')
                            {
                                if($row['aband_call_master']['AbandNoCount']>1)
                                {echo 'Repeat';}
                                else {echo 'Unique';}
                            }
                            else
                            {   
                                echo 'Repeat Abandon-Connected';
                            }
                            
                                ?></td>
                            <td><?php  // print_r($call_status);exit;
                            if($call_status[0]['t2']['STATUS']!='A')
                            {
                                echo 'Not Connected';
                            }
                            else
                            {   
                                echo 'Connected';
                            }
                                ?>
                            </td>
                            <td><?php echo $call_master[0]['call_master']['Category1'];?></td>
                              <td><?php echo $call_master[0]['call_master']['Category2'];?></td>
                            <th><?php if($call_status[0]['t2']['STATUS']=='A'){echo 'Resolved';} else {echo 'Not Resolved';} ?></th>
                            <th><?php echo 1; ?></th>
                            <th><?php  if(!empty($row['aband_call_master']['Callbackdate']))
                            {
                               echo $hourdiff = round((strtotime($row['aband_call_master']['Callbackdate']) - strtotime($row['aband_call_master']['CallDate']))/60, 0);
                            }    ?></th>
                            <th><?php $field_det = $this->RegistrationMaster->query("SELECT fieldNumber FROM `field_master` WHERE clientId='$ClientId' AND FieldName LIKE '%remark%'"); 
                            $remarksField = 'Field'.$field_det[0]['field_master']['fieldNumber'];
                            echo $call_master[0]['call_master'][$remarksField];
                            ?></th>
                                          
                          
                        </tr>

                    <?php }?>

                    </tbody>
        
        
	
                </table> <?php

            die;

        

    }

    public function external() 
    {
        $this->layout='user';
	    $ClientId = $this->Session->read('companyid');
    
             $search=$this->request->data['AbandonCallReports'];
           
             if($this->Session->read('role') =="admin"){
            
            
            $this->set('client',$client); 
        }
        
        
//        $this->set('report',$report);
    }

    public function export_abandon_export_reports() {
        

            
 
        $search     =   $this->request->data['AbandonCallReports'];
        
        $startdate = implode("-",array_reverse(explode("-",$search['startdate'])));
        $enddate = implode("-",array_reverse(explode("-",$search['enddate'])));
        //print_r($data);die;
        $client = $this->Session->read('companyid');
        
//            print_r($search);die;

        $qr_where = "";
        
         $qr_where ="and clientId='$client'";
        
        $data =$this->RegistrationMaster->query("select *,date(CallDate) CallDate1 from aband_call_master where date(CallDate) between '$startdate' and '$enddate' $qr_where");
        //print_r($data);die;
        
        header("Content-Type: application/vnd.ms-excel; name='excel'");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=abandoncalldetailanaylsisreports.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        ?>
        <table cellpadding="0" cellspacing="0" border="2" class="table table-striped table-bordered" >
            <thead>
                    <tr>
                        <th>SrNo</th>
                        <th>Client Name</th>
                        <th>Customer Number</th>
                        <th>Date & Time of Abandon</th>
                        <th>Count Of Call</th>
                        <!-- <th>First Call Back Time</th> -->
                        <th>Abandon Status</th>
                        <th>Call Status</th>
                        <th>Scenario</th>
                        <th>Sub-Scenario</th>
                        <!-- <th>Resolution Status</th>
                        <th>Attempt Number</th>
                        <th>Average Duration Of Attempts Done</th> -->
                        <th>Agent Tagging Remarks</th>
     
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i=1;
                    foreach($data as $row){
                        $ClientId = $row['aband_call_master']['ClientId'];
                        $lead_id = $row['aband_call_master']['LeadId'];
                        $call_date= $row['0']['CallDate1']; 
                        $PhoneNo = $row['aband_call_master']['PhoneNo']; 
                            $Id = $row['aband_call_master']['Id'];
                        $call_master =$this->RegistrationMaster->query("select * from call_master where ClientId='$ClientId' and date(CallDate) = '$call_date'  and MSISDN='$PhoneNo'  limit 1");
                        $exist =$this->RegistrationMaster->query("select * from aband_call_master where ClientId='$ClientId' and id!='$Id' and PhoneNo='$PhoneNo' and date(CallDate) = '$call_date' limit 1");
                        $check_call_status = "SELECT t2.status AS STATUS
        FROM asterisk.vicidial_log t2
                    LEFT JOIN vicidial_agent_log t3 ON t2.uniqueid=t3.uniqueid LEFT JOIN vicidial_users vu ON t2.user=vu.user
                    WHERE DATE(t2.call_date) = '$call_date'  AND t2.campaign_id='dialdesk'
                    AND LEFT(t2.phone_number,10) = '$PhoneNo'
                    AND t2.lead_id IS NOT NULL";
                         $this->vicidialLog->useDbConfig = 'db2';
                         $call_status = $this->vicidialLog->query($check_call_status);
                        ?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        
                        <td><?php echo $row['aband_call_master']['CompanyName'];?></td>
                        <td><?php echo $row['aband_call_master']['PhoneNo'];?></td>
                        <td><?php echo $row['aband_call_master']['CallDate'];?></td>
                        <td><?php echo $row['aband_call_master']['AbandNoCount'];?></td>
                        <!-- <td><?php //echo $row['aband_call_master']['Callbackdate'];?></td> -->
                        <td><?php  // print_r($call_status);exit;
                            if($call_status[0]['t2']['STATUS']!='A')
                            {
                                if($row['aband_call_master']['AbandNoCount']>1)
                                {echo 'Repeat';}
                                else {echo 'Unique';}
                            }
                            else
                            {   
                                echo 'Repeat Abandon-Connected';
                            }
                            
                                ?></td>
                      
                        <td><?php  // print_r($call_status);exit;
                        if($call_status[0]['t2']['STATUS']!='A')
                        {
                            echo 'Not Connected';
                        }
                        else
                        {   
                            echo 'Connected';
                        }
                            ?>
                        </td>
                        <td><?php echo $call_master[0]['call_master']['Category1'];?></td>
                          <td><?php echo $call_master[0]['call_master']['Category2'];?></td>
                       
                        <th><?php $field_det = $this->RegistrationMaster->query("SELECT fieldNumber FROM `field_master` WHERE clientId='$ClientId' AND FieldName LIKE '%remark%'"); 
                        $remarksField = 'Field'.$field_det[0]['field_master']['fieldNumber'];
                        echo $call_master[0]['call_master'][$remarksField];
                        ?></th>
                                      
                      
                    </tr>

                <?php }?>

                </tbody>
    
    

            </table> <?php

        die;

    

}
   
    
    

    
  
    
   


}
?>