<?php
	class MobDatasController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler','Session');
    public $uses=array('Mobdata','Mob','DeleteMobdata');

	
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
			'view_unallocate',
			'update_code',
			're_allocate',
			'reallocate_code',
			'worked_data',
			 'update_details',
			'update_details_office',
			'executive_tracker',
			'show_location',
			'export_log');
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
                    
			
			$csv_file = $this->request->data['MobDatas']['uploadfile']['tmp_name'];
			$FileTye = $this->request->data['MobDatas']['uploadfile']['type'];
			$info = explode(".",$this->request->data['MobDatas']['uploadfile']['name']);
			//print_r($this->request->data); die;
			if(($FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream' || $FileTye=='text/csv') && strtolower(end($info)) == "csv"){
				
				if (($handle = fopen($csv_file, "r")) !== FALSE) {
					$filedata = fgetcsv($handle, 1000, ","); 
					
					while (($filedata = fgetcsv($handle, 1000, ",")) !== FALSE) {
						
						$this->Mobdata->saveAll(array('custcode'=>"{$filedata[0]}",'custname'=>"{$filedata[1]}",'adrress1'=>"{$filedata[2]}",'address2'=>"{$filedata[3]}",'address3'=>"{$filedata[4]}",'city'=>"{$filedata[5]}",'pinccode'=>"{$filedata[6]}",'c_email_id'=>"{$filedata[7]}",'smancode'=>"{$filedata[8]}",'smanname'=>"{$filedata[9]}",'disc'=>"{$filedata[10]}",'lockdays'=>"{$filedata[11]}",'locknoofbills'=>"{$filedata[12]}",'creditlimit'=>"{$filedata[13]}",'creditdays'=>"{$filedata[14]}",'branchname'=>"{$filedata[15]}",'dlno1'=>"{$filedata[16]}",'dlno2'=>"{$filedata[17]}",'dlexpdate'=>"{$filedata[18]}",'narcoticdlno'=>"{$filedata[19]}",'schdlno'=>"{$filedata[20]}",'foodlicno'=>"{$filedata[21]}",'tinno'=>"{$filedata[22]}",'cstno'=>"{$filedata[23]}",'pan'=>"{$filedata[24]}",'codecreatedon'=>"{$filedata[25]}",'customertype'=>"{$filedata[26]}",'area'=>"{$filedata[27]}",'route'=>"{$filedata[28]}",'area_code'=>"{$filedata[29]}",'route_code'=>"{$filedata[30]}",'mannaullocktype'=>"{$filedata[31]}",'dc_day_group'=>"{$filedata[32]}",'c_provisional_gstn_no'=>"{$filedata[33]}",'c_permanent_gstn_no'=>"{$filedata[34]}",'c_pan_no'=>"{$filedata[35]}",'c_phone_1'=>"{$filedata[36]}",'c_phone_2'=>"{$filedata[37]}",'c_fax'=>"{$filedata[38]}",'c_mobile'=>"{$filedata[39]}",'c_contact_person'=>"{$filedata[40]}",'tcs_flag'=>"{$filedata[41]}",'tds_flag'=>"{$filedata[42]}",'UploadBy'=>"{$update_user}",'UploadDate'=>"{$update_date}",'DataType'=>'Existing'));			
                                        
					 }
					 
				}

				$this->Session->setFlash('<span style="color:green;">Tickets Updates SuccessFully</span>');
				$this->redirect(array('action' => 'index'));
			}
			else{
				$this->Session->setFlash('File Format not valid! Upload in CSV format.');
			}
		}	
	}
		
 public function view_unallocate(){
        $this->layout='user';
        $this->set('data',$this->Mobdata->find('all',array('conditions'=>array('smancode'=>''))));
        $this->set('executive',$this->Mob->find('all',array('conditions'=>array('Status'=>'1'))));
    }

public function update_code() {
        $this->layout='user';
       // print_r($_POST); exit;
        if($this->request->is("POST")){
           // $id             =   $this->request->data['id'];		
            $excode       =   $this->request->data['excode'];
            $selectcode    =   $this->request->data['selectcode'];
            
            if($this->Session->read('role') =="client"){
                $update_user=$this->Session->read('email');
            }
            else if($this->Session->read('role') =="agent"){
                $update_user=$this->Session->read('agent_username');
            }
            if($this->Session->read('role') =="admin"){
                $update_user=$this->Session->read('admin_name');
            }

            $update_date    =   date('Y-m-d H:i:s');

            	foreach ($selectcode as $key => $value) {
            		$data=array('smancode'=>"'".$excode."'");    	
					$this->Mobdata->updateAll($data,array('Id'=>$value));
            	}
            	$this->Session->setFlash('<span style="color:green;">Case Has been Allocated</span>');
				$this->redirect(array('action' => 'view_unallocate'));

        }	
    }


public function re_allocate(){
ini_set('memory_limit', '1024M');
        $this->layout='user';
//print_r($_POST); exit;
if($this->request->is("POST")){
$search=$this->request->data['MobDatas'];
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];            
            $start_time=date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));
            $excode_assigned     =   $this->request->data['excode_assigned'];
            $this->set('excode_assigned',$excode_assigned);

       // $this->set('data',$this->Mobdata->find('all',array('conditions'=>array('smancode !='=>'','CreateBy'=>''))));
            $smancode_qry = "";
            if($excode_assigned!='All')
            {
            	$smancode_qry = " and smancode='$excode_assigned'";
            }
	 $qry = "SELECT Id,custcode,custname,c_phone_1,c_phone_2,c_contact_person,adrress1,smancode FROM `mob_data`  WHERE (CreateDate is null || CreateDate='0000-00-00 00:00:00') and smancode!=''   $smancode_qry and DATE(UploadDate) BETWEEN '$start_time' AND '$end_time'";  
       $data=$this->Mobdata->query($qry);
      // print_r($data);exit;
$this->set('data',$data);
        
	}
$this->set('executive',$this->Mob->find('all',array('conditions'=>array('Status'=>'1'))));
    }

public function reallocate_code() {
ini_set('memory_limit', '512M');
        $this->layout='user';
       // print_r($_POST); exit;
        if($this->request->is("POST")){
           // $id             =   $this->request->data['id'];		
            $excode       =   $this->request->data['excode'];
            $selectcode    =   $this->request->data['selectcode'];
            
            if($this->Session->read('role') =="client"){
                $update_user=$this->Session->read('email');
            }
            else if($this->Session->read('role') =="agent"){
                $update_user=$this->Session->read('agent_username');
            }
            if($this->Session->read('role') =="admin"){
                $update_user=$this->Session->read('admin_name');
            }

            $update_date    =   date('Y-m-d H:i:s');
            //print_r($selectcode);exit;
            $case_count = 0;
            foreach ($selectcode as $key => $value) {
            		
            		$record = $this->Mobdata->find('first',array('conditions'=>"Id='$value'"));
            		$new_record['dataid'] = $value;
            		$new_record['smancode'] = $record['Mobdata']['smancode'];
                    //print_r($new_record);exit;
            		$this->DeleteMobdata->saveAll($new_record);
            		$data=array('smancode'=>"'".$excode."'",'SentStatus'=>'0');
					if($this->Mobdata->updateAll($data,array('Id'=>$value)))
                    {
                        $case_count++;
                    }
            }
            $total = count($selectcode);
            	$this->Session->setFlash('<span style="color:green;">Total Case '.$total.',No. of Case '.$case_count.' Has been ReAllocated</span>');
				$this->redirect(array('action' => 're_allocate'));

        }	
    }


     public function worked_data() {
     	ini_set('memory_limit', '512M');
        $this->layout='user';
        if($this->request->is("POST")){
            
            //$campaignId ="campaign_id in(". $this->Session->read('campaignid').")";
            //$clientId   =   $this->Session->read('companyid');
            $search=$this->request->data['MobDatas'];
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));
            
            $qry = "SELECT *,DATE_FORMAT(UploadDate,'%d-%b-%y') dater,DATE_FORMAT(UploadDate,'%d-%b-%y %H:%i:%s') UploadDate FROM `mob_data`  WHERE DATE(UploadDate) BETWEEN '$start_time' AND '$end_time'"; 
            $data=$this->Mobdata->query($qry);
            
            //print_r($data);exit;
         

	
        $this->set('data',$data);
         
        }
    }

    public function update_details(){
        $this->layout='user';
        if(isset($_REQUEST['Id'])){
            $result = $this->Mobdata->find('first',array('conditions'=>array('Id'=>$_REQUEST['Id'])));
           //print_r($result);exit;
            $this->set('data',$result);
        }
    }


    public function update_details_office() {
        $this->layout='user';
       //print_r($_POST); 
        if($this->request->is("POST")){
            $id             =   $this->request->data['company_id'];       
            $smanname       =   $this->request->data['smanname'];
            $disc    =   $this->request->data['disc'];
            $lockdays    =   $this->request->data['lockdays'];
            $locknoofbills    =   $this->request->data['locknoofbills'];
            $creditlimit    =   $this->request->data['creditlimit'];
            $creditdays    =   $this->request->data['creditdays'];
            $customertype    =   $this->request->data['customertype'];
            $area    =   $this->request->data['area'];
            $route    =   $this->request->data['route'];
            $mannaullocktype    =   $this->request->data['mannaullocktype'];
            $tcs_flag    =   $this->request->data['tcs_flag'];
            $tds_flag    =   $this->request->data['tds_flag'];
            $Supervisor    =   $this->request->data['Supervisor'];
            $DeliveryBoy    =   $this->request->data['DeliveryBoy'];
            $narcoticdlno    =   $this->request->data['narcoticdlno'];
            $schdlno    =   $this->request->data['schdlno'];
            $tinno    =   $this->request->data['tinno'];
            $cstno    =   $this->request->data['cstno'];
            $dc_day_group    =   $this->request->data['dc_day_group'];
            $c_provisional_gstn_no    =   $this->request->data['c_provisional_gstn_no'];
            $c_fax    =   $this->request->data['c_fax'];
            
            if($this->Session->read('role') =="client"){
                $update_user=$this->Session->read('email');
            }
            else if($this->Session->read('role') =="agent"){
                $update_user=$this->Session->read('agent_username');
            }
            if($this->Session->read('role') =="admin"){
                $update_user=$this->Session->read('admin_name');
            }

            $update_date    =   date('Y-m-d H:i:s');

                
        $data=array('smanname'=>"'".$smanname."'",'disc'=>"'".$disc."'",'lockdays'=>"'".$lockdays."'",'locknoofbills'=>"'".$locknoofbills."'",'creditlimit'=>"'".$creditlimit."'",'creditdays'=>"'".$creditdays."'",'customertype'=>"'".$customertype."'",'area'=>"'".$area."'",'route'=>"'".$route."'",'mannaullocktype'=>"'".$mannaullocktype."'",'tcs_flag'=>"'".$tcs_flag."'",'tds_flag'=>"'".$tds_flag."'",'Supervisor'=>"'".$Supervisor."'",'DeliveryBoy'=>"'".$DeliveryBoy."'",'narcoticdlno'=>"'".$narcoticdlno."'",'schdlno'=>"'".$schdlno."'",'tinno'=>"'".$tinno."'",'cstno'=>"'".$cstno."'",'dc_day_group'=>"'".$dc_day_group."'",'c_provisional_gstn_no'=>"'".$c_provisional_gstn_no."'",'c_fax'=>"'".$c_fax."'",'OfficeBy'=>"'".$update_user."'",'OfficeDate'=>"'".$update_date."'");       
            $this->Mobdata->updateAll($data,array('Id'=>$id)); 
               // print_r($data);exit;
                $this->Session->setFlash('<span style="color:green;">Data Has been Updated</span>');
                $this->redirect(array('action' => 'worked_data'));

        }   
    }

public function executive_tracker() {
       
        $this->layout='user';
        //print_r($_POST); exit;
        if($this->request->is("POST")){
           // $id             =   $this->request->data['id'];       
            $excode       =   $this->request->data['excode'];
            $search       =   $this->request->data['MobDatas'];
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time   =   date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));

            $qry = "SELECT smancode UserID,GoogleLat Latitude,GoogleLon Longitude,'Case Submission' Descr,CreateDate CreateDate,c_mobile MSISDN FROM mob_data WHERE smancode='$excode' and date(CreateDate) BETWEEN '$start_time' and '$end_time' and GoogleLat!='0.0'  and GoogleLon!='0.0' and GoogleLat!=''  ORDER BY CreateDate";  
            $data=$this->Mobdata->query($qry);
            
            //print_r($data);exit;
         

    
        $this->set('data',$data);

        }


        $this->set('executive',$this->Mob->find('all',array('conditions'=>array('Status'=>'1'))));
        
    }

    public function show_location() {
       
        $this->layout='user';
        $this->set('executive',$this->Mob->find('all',array('conditions'=>array('Status'=>'1'))));
        
    }

/////////////////   Data Export////////////////////

     public function export_log()
    {
        if($this->request->is("POST")){
            
               
	        header("Content-Type: application/vnd.ms-excel; name='excel'");
	        header("Content-type: application/octet-stream");
	        header("Content-Disposition: attachment; filename=export_log.xls");
	        header("Pragma: no-cache");
	        header("Expires: 0");
       
            $search=$this->request->data['MobDatas'];
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));
            
           // $clientId       =   $this->Session->read('companyid');
            $qry = "SELECT *,DATE_FORMAT(UploadDate,'%d-%b-%y') dater,DATE_FORMAT(UploadDate,'%d-%b-%y %H:%i:%s') UploadDate FROM `mob_data` lo  WHERE DATE(UploadDate) BETWEEN '$start_time' AND '$end_time'";
            $data=$this->Mobdata->query($qry);
            
            
            ?>
        
            <table cellspacing="0" border="1">
                <tr>
				<th>Customer code</th>			
				<th>Firm Name</th>
                <th>Address</th>
                <th>Locality</th>
                <th>Landmark</th>
                <th>City</th>
                <th>Pin code</th>
                <th>Email id</th>
                <th>Salesman Code</th>
                <th>Salesman Name</th>
                <th>Discount</th>
                <th>Lock days</th>
                <th>Lock nos. of Bill</th>
                <th>Credit limit</th>
                <th>Credit days</th>
                <th>Branch name</th>
                <th>Drug lic. no. 20B</th>
                <th>Drug lic. no. 21B</th>
                <th>Drug Lic. expiry date</th>
                <th>Norcotic DL no.</th>
                <th>Schedule H1 DL no.</th>
                <th>Food License no.</th>
                <th>Tin No.</th>
                <th>Cst No.</th>
                <th>Pan No.</th>
                <th>Code creation date</th>
                <th>Customer Category</th>
                <th>Area Name</th>
                <th>Route Name</th>
                <th>Area code</th>
                <th>Route code</th>
                <th>Mannual Lock Type</th>
                <th>DC Day</th>
                <th>Provisional GST no.</th>
                <th>Permanent GST no.</th>
                <th>c_pan_no</th>
                <th>Phone 1</th>
                <th>Phone 2</th>
                <th>Fax No.</th>
                <th>Registered Mobile</th>
                <th>Owner Name</th>
                <th>TCS Flag</th>
                <th>TDS flag</th>
                <th>Order Person name</th>
                <th>Supervisor</th>
                <th>Delivery Boy</th>
                <th>Shop closing day</th>
                <th>Google Location</th>
                <th>Upload image</th>
                <th>Upload video</th>
                <th>No. of Employees</th>
                <th>Shop area sqft.  (Lumpsum)</th>
                <th>Computrized</th>
                <th>Nature of outlet</th>
                <th>Item Range</th>
                <th>Cashier</th>
                <th>major companies</th>
                <th>major items</th>

                </tr>
       <?php foreach($data as $record) { ?>
		<tr>
				<td><?php echo $record['lo']['custcode']; ?></td>
				<td><?php echo $record['lo']['custname']; ?></td>
				<td><?php echo $record['lo']['adrress1']; ?></td>
				<td><?php echo $record['lo']['adrress2']; ?></td>
				<td><?php echo $record['lo']['adrress3']; ?></td>
				<td><?php echo $record['lo']['city']; ?></td>
				<td><?php echo $record['lo']['pinccode']; ?></td>
				<td><?php echo $record['lo']['c_email_id']; ?></td>
				<td><?php echo $record['lo']['smancode']; ?></td>
				<td><?php echo $record['lo']['smanname']; ?></td>
				<td><?php echo $record['lo']['disc']; ?></td>
				<td><?php echo $record['lo']['lockdays']; ?></td>
				<td><?php echo $record['lo']['locknoofbills']; ?></td>
				<td><?php echo $record['lo']['creditlimit']; ?></td>
				<td><?php echo $record['lo']['creditdays']; ?></td>
				<td><?php echo $record['lo']['branchname']; ?></td>
				<td><?php echo $record['lo']['dlno1']; ?></td>
				<td><?php echo $record['lo']['dlno2']; ?></td>
				<td><?php echo $record['lo']['dlexpdate']; ?></td>
				<td><?php echo $record['lo']['narcoticdlno']; ?></td>
				<td><?php echo $record['lo']['schdlno']; ?></td>
				<td><?php echo $record['lo']['foodlicno']; ?></td>
				<td><?php echo $record['lo']['tinno']; ?></td>
				<td><?php echo $record['lo']['cstno']; ?></td>
				<td><?php echo $record['lo']['pan']; ?></td>
				<td><?php echo $record['lo']['codecreatedon']; ?></td>
				<td><?php echo $record['lo']['customertype']; ?></td>
				<td><?php echo $record['lo']['area']; ?></td>
				<td><?php echo $record['lo']['route']; ?></td>
				<td><?php echo $record['lo']['area_code']; ?></td>
				<td><?php echo $record['lo']['route_code']; ?></td>
				<td><?php echo $record['lo']['mannaullocktype']; ?></td>
				<td><?php echo $record['lo']['dc_day_group']; ?></td>
				<td><?php echo $record['lo']['c_provisional_gstn_no']; ?></td>
				<td><?php echo $record['lo']['c_permanent_gstn_no']; ?></td>
				<td><?php echo $record['lo']['c_pan_no']; ?></td>
				<td><?php echo $record['lo']['c_phone_1']; ?></td>
				<td><?php echo $record['lo']['c_phone_2']; ?></td>
				<td><?php echo $record['lo']['c_fax']; ?></td>
				<td><?php echo $record['lo']['c_mobile']; ?></td>
				<td><?php echo $record['lo']['c_contact_person']; ?></td>
				<td><?php echo $record['lo']['tcs_flag']; ?></td>
				<td><?php echo $record['lo']['tds_flag']; ?></td>
				<td><?php echo $record['lo']['OrderPersonname']; ?></td>
				<td><?php echo $record['lo']['Supervisor']; ?></td>
				<td><?php echo $record['lo']['DeliveryBoy']; ?></td>
				<td><?php echo $record['lo']['Shopclosingday']; ?></td>
				<td><?php echo $record['lo']['GoogleLat']."-".$record['lo']['GoogleLon']; ?></td>
				<td><?php echo $record['lo']['Uploadimage1']."-".$record['lo']['Uploadimage2']."-".$record['lo']['Uploadimage3']."-".$record['lo']['Uploadimage4']."-".$record['lo']['Uploadimage5']; ?></td>
				<td><?php echo $record['lo']['Uploadvideo']; ?></td>
				<td><?php echo $record['lo']['NoofEmployees']; ?></td>
				<td><?php echo $record['lo']['Shopareasqft']; ?></td>
				<td><?php echo $record['lo']['Computrized']; ?></td>
				<td><?php echo $record['lo']['Natureofoutlet']; ?></td>
				<td><?php echo $record['lo']['ItemRange']; ?></td>
				<td><?php echo $record['lo']['Cashier']; ?></td>
				<td><?php echo $record['lo']['majorcompanies']; ?></td>
				<td><?php echo $record['lo']['majoritems']; ?></td>

                
	</tr>		
	<?php } ?>
            </table>

            <?php
            }
             die;   
    }

	
}

?>