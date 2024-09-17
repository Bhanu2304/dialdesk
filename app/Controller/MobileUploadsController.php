<?php

App::uses('AppController', 'Controller');
class MobileUploadsController extends AppController {
public $components = array('Session');
public $uses=array('MobileUpload','Dispositions','WhatsappTransaction');
    public function beforeFilter() 
    {    
            parent::beforeFilter();
            // Allow users to register and logout.
            $this->Auth->allow('index','add','file_upload','boundreport','MobileUploads_boundreport','MobileUploads_boundview','Bound_disposition','Bound_disposition_report','Whatsapp_transaction','Whatsapp_transaction_report');
    
      }


    public function index()
    {
        $this->layout='user';
        $username = $this->Session->read("username");
        // $this->set('data',$this->MobileUpload->find('all',array('order'=>array('id'=>'desc'),'conditions'=>array('agent_code'=>$username))));       
        $this->set('data',$this->MobileUpload->find('all',array('order'=>array('id'=>'desc'))));       
    }


    public function add() 
    {
        $this->layout='user';

          if($this->request->is('Post'))
        {
                   $file = $_FILES["file"]["tmp_name"]; 
                    
                    $file_open = fopen($file,"r");
                    $result = array();
                    $idd='1';
                    while(($csv = fgetcsv($file_open, 1000, ",")) !== false)
                    {

                        if($idd!=1) 
                        {
                            $result['Customer_Code'] = $csv['0'];
                                $result['Cust_name'] = $csv['1'];
                                $result['mobile_no1'] = $csv['2'];
                                $result['mobile_no2'] = $csv['3'];
                                $result['address1'] = $csv['4'];
                                $result['address2'] = $csv['5'];
                                $result['address3'] = $csv['6'];
                                $result['city'] = $csv['7'];
                                $result['pincode'] = $csv['8'];
                                $result['email_id'] = $csv['9'];
                                $result['salesman_code'] = $csv['10'];
                                $result['salesman_name'] = $csv['11'];
                                $result['district'] = $csv['12'];
                                $result['dlno1'] = $csv['13'];
                                $result['dlno2'] = $csv['14'];
                                $result['dl_expiry_date'] = $csv['15'];
                                $result['pan_no'] = $csv['16'];
                                $result['code_created_on'] = $csv['17'];
                                $result['customer_type'] = $csv['18'];
                                $result['agent_code'] = $csv['19']; 
                                
                                $result['CreateDate'] = date("Y-m-d H:i:s");


                                // $result[] = $csv;  
                                
                                $this->MobileUpload->saveAll($result);
                        }

                                
                                
                        $idd++;        
                                
                    }

                    unset($data);
                     
                $this->Session->setFlash(' Mobile Upload Data Added Successfully.');
            
                $this->redirect(array('controller' => 'MobileUploads', 'action' => 'index'));
        }


        
    }

    public function singledata()
    {
        $this->layout='user';
        $username = $this->Session->read("username");

        if(isset($_REQUEST['dial']) && $_REQUEST['dial'] !="")
        {
             $dial = $_REQUEST['dial']; 
            //$this->set('data',$this->MobileUpload->find('all',array('order'=>array('id'=>'desc'),'conditions'=>array('agent_code'=>$username))));       
        
            $mobiledata = $this->MobileUpload->find('first',array('conditions'=>array('agent_code'=>$dial)));
            
            //print_r($mobiledata); exit;

            $this->set('Customer_Code',$mobiledata['MobileUpload']['Customer_Code']); 
            $this->set('Cust_name',$mobiledata['MobileUpload']['Cust_name']); 
            $this->set('mobile_no1',$mobiledata['MobileUpload']['mobile_no1']); 
            $this->set('mobile_no2',$mobiledata['MobileUpload']['mobile_no2']); 
            $this->set('address1',$mobiledata['MobileUpload']['address1']); 
            $this->set('address2',$mobiledata['MobileUpload']['address2']); 
            $this->set('address3',$mobiledata['MobileUpload']['address3']); 
            $this->set('city',$mobiledata['MobileUpload']['city']); 
            $this->set('pincode',$mobiledata['MobileUpload']['pincode']); 
            $this->set('email_id',$mobiledata['MobileUpload']['email_id']); 
            $this->set('salesman_code',$mobiledata['MobileUpload']['salesman_code']); 
            $this->set('salesman_name',$mobiledata['MobileUpload']['salesman_name']); 
            $this->set('district',$mobiledata['MobileUpload']['district']); 
            $this->set('dlno1',$mobiledata['MobileUpload']['dlno1']); 
            $this->set('dlno2',$mobiledata['MobileUpload']['dlno2']); 
            $this->set('dl_expiry_date',$mobiledata['MobileUpload']['dl_expiry_date']); 
            $this->set('pan_no',$mobiledata['MobileUpload']['pan_no']); 
            $this->set('code_created_on',$mobiledata['MobileUpload']['code_created_on']); 
            $this->set('customer_type',$mobiledata['MobileUpload']['customer_type']); 
            $this->set('agent_code',$mobiledata['MobileUpload']['agent_code']); 
            $this->set('CreateDate',$mobiledata['MobileUpload']['CreateDate']); 


        } 
        else
        {
            echo "no record found!";
        } 
        

        // $this->set('data',$this->MobileUpload->find('all',array('order'=>array('id'=>'desc'),'conditions'=>array('agent_code'=>$username))));       
        //$this->set('data',$this->MobileUpload->find('all',array('order'=>array('id'=>'desc'))));       
    }



    public function dispositions()
    {
        $this->layout='user';

        if($this->request->is('Post'))
      {
          //print_r($this->request); die;

                    $result['dispositions'] = $this->request->data['disposition'];
                    $result['sub_dispositions'] = $this->request->data['sub_disposition'];
                    $result['agent'] = $this->request->data['third'];

                  $this->Dispositions->save($result);

                  unset($data);
                   
              $this->Session->setFlash(' Dispositions Data Added Successfully.');
          
              $this->redirect(array('controller' => 'MobileUploads', 'action' => 'index'));
      }
    }

    // Reports

    public function boundreport()
    {
        $this->layout='user';
        $username = $this->Session->read("username");
//print_r($_POST);
        if($this->request->is('Post'))
         {
            $search     =$this->request->data['MobileUploads'];
            $reporttype=$this->request->data['reporttype']; 	
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];
	   
		if($reporttype=='OB Data') {
            $qry="SELECT * FROM `mobile_upload_disposition_details` t2 LEFT JOIN mobile_upload t1 ON t2.`dialid`=t1.`id` WHERE DATE(created_at) BETWEEN '$FromDate' AND '$ToDate' and category='OB'";
		} else if($reporttype=='IB Data') {
	    $qry="SELECT * FROM `mobile_upload_disposition_details` t2 WHERE DATE(created_at) BETWEEN '$FromDate' AND '$ToDate' and category='IB'";
	
		}
            $dt=$this->MobileUpload->query($qry);

           //    print_r($dt);die;
		$this->set('ReportType',$reporttype);
               $this->set('Data',$dt);
         }


        // $this->set('data',$this->MobileUpload->find('all',array('order'=>array('id'=>'desc'),'conditions'=>array('agent_code'=>$username))));       
        $this->set('data',$this->MobileUpload->find('all',array('order'=>array('id'=>'desc'))));       
    }

    public function MobileUploads_boundreport()
    {
        
         $search     =$this->request->data['MobileUploads'];
         $reporttype=$this->request->data['reporttype'];
         $FromDate=$search['startdate'];
         $ToDate=$search['enddate'];

         if($reporttype=='OB Data') {
            $qry="SELECT * FROM `mobile_upload_disposition_details` t2 LEFT JOIN mobile_upload t1 ON t2.`dialid`=t1.`id` WHERE DATE(created_at) BETWEEN '$FromDate' AND '$ToDate' and category='OB'";
		} else if($reporttype=='IB Data') {
	    $qry="SELECT * FROM `mobile_upload_disposition_details` t2 WHERE DATE(created_at) BETWEEN '$FromDate' AND '$ToDate' and category='IB'";
	
		}
        
            $dt=$this->Dispositions->query($qry);

            //print_r($dt);
            

            $filename = "Mobile_upload_call_report".date("Y-m-d_h-i_s",time());
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=$filename."."xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ?>
	<?php if($reporttype=='OB Data') { ?>
            <table cellspacing="0" border="1">
            <thead>
                <tr style="background-color:#317EAC; color:#FFFFFF;">                        
                        
                    <th>Sno.</th>           
                    <th>Customer Code</th> 
                    <th>Cust name</th> 
                    <th>mobile no1</th> 
                    <th>mobile no2</th> 
                    <th>address1</th> 
                    <th>address2</th> 
                    <th>address3</th> 
                    <th>city</th> 
                    <th>pincode</th> 
                    <th>email id</th> 
                    <th>salesman code</th> 
                    <th>salesman name</th> 
                    <th>district</th> 
                    <th>dlno1</th> 
                    <th>dlno2</th> 
                    <th>dl expiry date</th> 
                    <th>pan no</th> 
                    <th>code created on</th> 
                    <th>customer type</th> 
                    <th>agent code</th> 
                    <th>CreateDate</th>
                    <th>Category</th>
                    <th>Mobile No.</th>
                    <th>Dispositions</th>
                    <th>Sub Dispositions</th>
                    <th>Agent</th>
                    <th>Dial Id</th>
                    <th>Remarks</th>
<th>CaLL Date</th>

                </tr>
                
            </thead>
            <tbody>

            <?php $ik='1';  foreach ($dt as $row) { ?>
              
                    <tr>
                            <td><?php echo $ik++;?></td>
                            <td><?php echo $row['t1']['Customer_Code'];?></td> 
                            <td><?php echo $row['t1']['Cust_name'];?></td> 
                            <td><?php echo $row['t1']['mobile_no1'];?></td> 
                            <td><?php echo $row['t1']['mobile_no2'];?></td> 
                            <td><?php echo $row['t1']['address1'];?></td> 
                            <td><?php echo $row['t1']['address2'];?></td> 
                            <td><?php echo $row['t1']['address3'];?></td> 
                            <td><?php echo $row['t1']['city'];?></td> 
                            <td><?php echo $row['t1']['pincode'];?></td> 
                            <td><?php echo $row['t1']['email_id'];?></td> 
                            <td><?php echo $row['t1']['salesman_code'];?></td> 
                            <td><?php echo $row['t1']['salesman_name'];?></td> 
                            <td><?php echo $row['t1']['district'];?></td> 
                            <td><?php echo $row['t1']['dlno1'];?></td> 
                            <td><?php echo $row['t1']['dlno2'];?></td> 
                            <td><?php echo $row['t1']['dl_expiry_date'];?></td> 
                            <td><?php echo $row['t1']['pan_no'];?></td> 
                            <td><?php echo $row['t1']['code_created_on'];?></td> 
                            <td><?php echo $row['t1']['customer_type'];?></td> 
                            <td><?php echo $row['t1']['agent_code'];?></td> 
                            <td><?php echo $row['t1']['CreateDate'];?></td>
                            <td><?php echo $row['t2']['category'];?></td>
                            <td><?php echo $row['t2']['mobile'];?></td>
                            <td><?php echo $row['t2']['dispositions'];?></td>
                            <td><?php echo $row['t2']['sub_dispositions'];?></td>
                            <td><?php echo $row['t2']['agentid'];?></td>
                            <td><?php echo $row['t2']['dialid'];?></td>
                            <td><?php echo $row['t2']['remarks'];?></td>
			    <td><?php echo $row['t2']['created_at'];?></td>
                    </tr>



                <?php 
                    } 
                                    
            echo '</tbody>
            </table>';  
 } else if($reporttype=='IB Data') { 
?>
 <table cellspacing="0" border="1">
            <thead>
                <tr style="background-color:#317EAC; color:#FFFFFF;">                        
                        
                   <th>S.N</th>   
                                    <th>Mobile no</th> 
                                    <th>agent code</th> 
                                    <th>Category</th>
                                    <th>Dispositions</th>
                                    <th>Sub Dispositions</th>
                                    <th>Remarks</th>
<th>Call Date</th>

                </tr>
                
            </thead>
            <tbody>

            <?php $ik='1';  foreach ($dt as $row) { ?>
              
                    <tr>
                            <td><?php echo $ik++;?></td>
                            
                            <td><?php echo $row['t2']['mobile'];?></td>
			    <td><?php echo $row['t2']['agentid'];?></td>
			    <td><?php echo $row['t2']['category'];?></td>
                            <td><?php echo $row['t2']['dispositions'];?></td>
                            <td><?php echo $row['t2']['sub_dispositions'];?></td>
                            <td><?php echo $row['t2']['remarks'];?></td>
<td><?php echo $row['t2']['created_at'];?></td>
                    </tr>



                <?php 
                    } 
                                    
            echo '</tbody>
            </table>';

}
            exit;

            $this->redirect(array('controller' => 'MobileUploads', 'action' => 'boundreport'));
    }

    //Bound_disposition_report

    
    public function Bound_disposition()
    {
        $this->layout='user';
        $username = $this->Session->read("username");

        if($this->request->is('Post'))
         {
             $search     =$this->request->data['MobileUploads']; 
         
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];
   
            $qry="SELECT mudd.*,am.displayname FROM mobile_upload_disposition_details AS mudd,agent_master AS am 
            WHERE am.username=mudd.agentid AND DATE(created_at) BETWEEN '$FromDate' AND '$ToDate'";
           
               $dt=$this->Dispositions->query($qry);
   
               
               $this->set('Data',$dt);
         }


        // $this->set('data',$this->MobileUpload->find('all',array('order'=>array('id'=>'desc'),'conditions'=>array('agent_code'=>$username))));       
        $this->set('data',$this->MobileUpload->find('all',array('order'=>array('id'=>'desc'))));       
    }

    public function Bound_disposition_report()
    {
        
         $search     =$this->request->data['MobileUploads'];
         if($this->request->is('Post'))
         {
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];

            $qry="SELECT mudd.*,am.displayname FROM mobile_upload_disposition_details AS mudd,agent_master AS am 
            WHERE am.username=mudd.agentid AND DATE(created_at) BETWEEN '$FromDate' AND '$ToDate'";
            
            $dt=$this->Dispositions->query($qry);

            //print_r($dt);
            

            $filename = "Mobile_Bound_disposition_report".date("Y-m-d_h-i_s",time());
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=$filename."."xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ?>
            <table cellspacing="0" border="1">
            <thead>
                <tr style="background-color:#317EAC; color:#FFFFFF;">                        
                        
                    <th>Sno.</th>           
                    <th>Category</th>
                    <th>Mobile No.</th>
                    <th>Dispositions</th>
                    <th>Sub Dispositions</th>
                    <th>Agent</th>
                    <th>Dial Id</th>
                    <th>Remarks</th>

                </tr>
                
            </thead>
            <tbody>

            <?php $ik='1';  foreach ($dt as $result) { ?>
              
                    <tr>
                            <td><?php echo $ik++;?></td>
                            <td><?php echo $result['mudd']['category'];?></td>
                            <td><?php echo $result['mudd']['mobile'];?></td>
                            <td><?php echo $result['mudd']['dispositions'];?></td>
                            <td><?php echo $result['mudd']['sub_dispositions'];?></td>
                            <td><?php echo $result['am']['displayname'];?></td>
                            <td><?php echo $result['mudd']['dialid'];?></td>
                            <td><?php echo $result['mudd']['remarks'];?></td>
                    </tr>



                <?php 
                    } 
                                    
            echo '</tbody>
            </table>';  

            exit;

                }

            $this->redirect(array('controller' => 'MobileUploads', 'action' => 'boundreport'));
    }

    // Whatsapp_transaction

    
    public function Whatsapp_transaction()
    {
        $this->layout='user';
        $username = $this->Session->read("username");

        if($this->request->is('Post'))
         {
             $search     =$this->request->data['MobileUploads']; 
         
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];
   
            $qry="SELECT wt.*,am.displayname FROM watsapp_transaction AS wt,agent_master AS am 
            WHERE am.username=wt.agent_code AND DATE(wt.createdate) BETWEEN '$FromDate' AND '$ToDate'";
           
               $dt=$this->WhatsappTransaction->query($qry);

            //    print_r($dt); die;
               
               $this->set('Data',$dt);
         }


        // $this->set('data',$this->MobileUpload->find('all',array('order'=>array('id'=>'desc'),'conditions'=>array('agent_code'=>$username))));       
        $this->set('data',$this->WhatsappTransaction->find('all',array('order'=>array('id'=>'desc'))));       
    }

    public function Whatsapp_transaction_report()
    {
        
         $search     =$this->request->data['MobileUploads'];
         if($this->request->is('Post'))
         {
            $FromDate=$search['startdate'];
            $ToDate=$search['enddate'];

            $qry="SELECT wt.*,am.displayname FROM watsapp_transaction AS wt,agent_master AS am 
            WHERE am.username=wt.agent_code AND DATE(wt.createdate) BETWEEN '$FromDate' AND '$ToDate'";
            
            $dt=$this->WhatsappTransaction->query($qry);

            //print_r($dt);
            

            $filename = "Whatsapp_transaction_report".date("Y-m-d_h-i_s",time());
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=$filename."."xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ?>
            <table cellspacing="0" border="1">
            <thead>
                <tr style="background-color:#317EAC; color:#FFFFFF;">                        
                        
                                
                    <th>Name</th>
                    <th>Mobile No.</th>
                    <th>wa_id</th>
                    <th>Message Id</th>
                    <th>Text</th>
                    <th>Type</th>
                    <th>Agent</th>
                    <th>createdate</th>

                </tr>
                
            </thead>
            <tbody>

            <?php $ik='1';  foreach ($dt as $result) { ?>
              
                    <tr>
                            
                            <td><?php echo $result['wt']['name'];?></td>
                            <td><?php echo $result['wt']['from'];?></td>
                            <td><?php echo $result['wt']['wa_id'];?></td>
                            <td><?php echo $result['wt']['message_id'];?></td>
                            <td><?php echo $result['wt']['text'];?></td>                            
                            <td><?php echo $result['wt']['type'];?></td>
                            <td><?php echo $result['am']['displayname'];?></td>
                            <td><?php echo $result['wt']['createdate'];?></td>
                    </tr>



                <?php 
                    } 
                                    
            echo '</tbody>
            </table>';  

            exit;

                }

            
    }

}
?>