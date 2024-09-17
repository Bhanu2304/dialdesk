<?php
class WhatsappTemplatesController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','WhatsappTemplate','PagesMaster1','BalanceMaster','BillingMaster',
            'BillMasterPost','BalanceMasterHistory','vicidialCloserLog');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('index','view','export_whatsapp_template','read_data','index1','buildMenu','view_invoice','view_statement','update_client_plan','get_client_name','allocate_plan','getbillmonth','view_prepaid_invoice','get_oldbill_date','get_tagging_status');
        if(!$this->Session->check("admin_id"))
        {
                return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }
    }

    public function index()
    {
        $this->layout = "user";

        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));  
        $this->set('client',$client);

        if($this->request->is('Post') && !empty($this->request->data))
        {
            //print_r($this->request->data);die;
            $update_date=date('Y-m-d H:i:s'); 
            $csv_file = $this->request->data['WhatsappTemplate']['uploadfile']['tmp_name'];
			$FileTye = $this->request->data['WhatsappTemplate']['uploadfile']['type'];
			$info = explode(".",$this->request->data['WhatsappTemplate']['uploadfile']['name']);
			//print_r($this->request->data); die;
			if(($FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream' || $FileTye=='text/csv') && strtolower(end($info)) == "csv"){
				
				if (($handle = fopen($csv_file, "r")) !== FALSE) {
					$filedata = fgetcsv($handle, 1000, ","); 
					
					while (($filedata = fgetcsv($handle, 1000, ",")) !== FALSE) {
						
						$this->WhatsappTemplate->saveAll(array('name'=>"{$filedata[0]}",'number'=>"91$filedata[1]",'template_name'=>"{$filedata[2]}",'template_id'=>"{$filedata[3]}",'upload_date'=>"{$update_date}"));			
                                        
					 }
					 
				}

				$this->Session->setFlash('<span style="color:green;">CSV Updates SuccessFully</span>');
				$this->redirect(array('action' => 'index'));
			}
			else{
				$this->Session->setFlash('File Format not valid! Upload in CSV format.');
			}

        }
    }

    public function view()
    {
        $this->layout = "user";

        if($this->request->is("POST"))
        {
            //print_r($this->request->data);die;
            $search=$this->request->data['WhatsappTemplate'];
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));
            
            $qry = "SELECT *,DATE_FORMAT(upload_date,'%d-%b-%y') dater FROM `whatsapp_template` WHERE  DATE(upload_date) BETWEEN '$start_time' AND '$end_time'";
            $data=$this->WhatsappTemplate->query($qry);

	
            $this->set('data',$data);
         
        }
    }

    public function export_whatsapp_template()
    {
        if($this->request->is("POST")){

                
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=message_broadcasting_report.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
           
            
            $search=$this->request->data['WhatsappTemplate'];
            $FromDate   =   $search['startdate'];
            $ToDate     =   $search['enddate'];
            $start_time=date("Y-m-d",strtotime("$FromDate"));
            $end_time=date("Y-m-d",strtotime("$ToDate"));
            
            $qry = "SELECT *,DATE_FORMAT(upload_date,'%d-%b-%y') dater FROM `whatsapp_template` WHERE  DATE(upload_date) BETWEEN '$start_time' AND '$end_time'";
            $data=$this->WhatsappTemplate->query($qry);
            
            
            ?>
        
            <table cellspacing="0" border="1">
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Number</th>
                <th>Template Id</th>
                <th>Status</th>
            </tr>
            <?php foreach($data as $record) { ?>
            <tr>
                <td><?php echo $record['0']['dater']; ?></td>
                <td><?php echo $record['whatsapp_template']['name']; ?></td>
                <td><?php echo $record['whatsapp_template']['number']; ?></td>
                <td><?php echo $record['whatsapp_template']['template_id']; ?></td>
                <td><?php echo $record['whatsapp_template']['status']; ?></td>
            </tr>
	        <?php } ?>
            </table>

            <?php
            }
            
           
                
             die;   
       
        
    }
    public function index1()
    {
        $this->layout = "user";

        $client = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));

        $UserRight = $this->buildMenu($client);
        $this->set('UserRight',$UserRight);
        

        if($this->request->is("POST"))
        {
            //print_r($this->request->data);die;
            $data = $this->request->data['WhatsappTemplate'];
            $client_Arr = $this->request->data['selectAll'];
            $name   =   $data['template_name'];
            $id     =   $data['emailid'];

            $update_date = date('Y-m-d H:i:s');

            foreach($client_Arr as $clientid)
            {
                $client_data = $this->RegistrationMaster->find('first',array('fields'=>array("company_name","phone_no"),'conditions'=>array('company_id'=>$clientid)));
                $company_name = $client_data['RegistrationMaster']['company_name'];
                $company_number = $client_data['RegistrationMaster']['phone_no'];

                $this->WhatsappTemplate->saveAll(array('name'=>"$company_name",'number'=>"91$company_number",'template_name'=>"$name",'template_id'=>"$id",'upload_date'=>"{$update_date}"));			
                $this->Session->setFlash('<span style="color:green;">Data Save SuccessFully</span>');
						
            }
            $this->redirect(array('action' => 'index1'));
        }

    }

    function buildMenu($menu) {
        $html = "";
        $char=" ";
            foreach ($menu as $menu_id) {
                if (isset($menu_id['RegistrationMaster'])) {
                    $html .= "<li><div class='checkbox-primary'><label><input class='.checkbox-info' type='checkbox' id='" . $menu_id['RegistrationMaster']['company_id'] . "' name='selectAll[]'  value='".$menu_id['RegistrationMaster']['company_id']."'> ".$menu_id['RegistrationMaster']['company_name']."</label></div></li>";
                }  
            }

        return $html;
    }


}
?>