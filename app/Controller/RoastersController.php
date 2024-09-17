<?php
class RoastersController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('AgentMaster','Roaster');
            // 'BillMasterPost','BalanceMasterHistory','vicidialCloserLog');
	
    // public function beforeFilter() 
    // {
    //     parent::beforeFilter();
    //     $this->Auth->allow('index','view','export_whatsapp_template','read_data','get_billing_reports','view_bill','view_invoice','view_statement','update_client_plan','get_client_name','allocate_plan','getbillmonth','view_prepaid_invoice','get_oldbill_date','get_tagging_status');
    //     if(!$this->Session->check("admin_id"))
    //     {
    //             return $this->redirect(array('controller'=>'Admins','action' => 'index'));
    //     }
    // }

    public function index()
    {
        $this->layout = "user";

        // $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));  
        // $this->set('client',$client);
        $now = date('Y-m-d H:i:s');
        $date_capture_start =  strtotime($now.' +1 days');
        $cap_date = date('Y-m-d',$date_capture_start);
        
        $this->set('cap_date',date('d-m-Y',$date_capture_start));

        if($this->request->is('Post') && !empty($this->request->data))
        {
          
            $csv_file = $this->request->data['Roaster']['uploadfile']['tmp_name'];
			$FileTye = $this->request->data['Roaster']['uploadfile']['type'];
			$info = explode(".",$this->request->data['Roaster']['uploadfile']['name']);
            
            $userid = $this->Session->read('admin_id');

			
			if(($FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream' || $FileTye=='text/csv') && strtolower(end($info)) == "csv"){
				
				if (($handle = fopen($csv_file, "r")) !== FALSE) {
                    $aData = array();
                    $flag_all_right = true;
                    $filedata = fgetcsv($handle, 1000, ",");
                    $records = array();
                    $row=2;
                    if($filedata[0] == 'Agent' && $filedata[1] == 'shiftstarttime' && $filedata[2] == 'shiftendtime' && $filedata[3] == 'working_hour')
                    {
                        while (($filedata = fgetcsv($handle, 1000, ",")) !== FALSE) {
                               
                                // $this->Session->setFlash('File Format not valid! Upload in CSV format.');
                                $agent = $filedata[0];
                                $records[] = array('roasterdate'=>$cap_date,'Agent'=>"{$filedata[0]}",'shiftstarttime'=>"{$filedata[1]}",'shiftendtime'=>"{$filedata[2]}",'working_hour'=>"{$filedata[3]}",'created_by'=>$userid,'created_at'=>"$now");
                                
                                $exist = $this->AgentMaster->find('first',array('conditions'=>"username='$agent'"));

                                if($exist)
                                {

                                }
                                else
                                {
                                    $flag_all_right = false;
                                    break;
                                }		
                                $row++; 
                        }
                        
                    }
                    else
                    {
                        $this->Session->setFlash('Upload File Format Not valid');
                    }
					if($flag_all_right)
                    {
                        $this->Roaster->query("DELETE FROM `roaster` WHERE DATE(created_at)=CURDATE(); ");

                    $this->Roaster->saveall($records);	
                    $this->Session->setFlash('<span style="color:green;">CSV Uploaded SuccessFully</span>');
                    }
                    else
                    {
                        $this->Session->setFlash("<span style=\"color:red;\">Agent Not Found at Row No. $row. </span>");
                    }    
                                        
					 }
                     else{
                        $this->Session->setFlash('File Format not valid! Upload in CSV format.');
                    }
               
					 
				}

				$this->redirect(array('action' => 'index'));
			}
			
        }

    public function view()
    {
        $this->layout='user';
        //$this->set('data',$this->Roaster->find('all'));
        $this->set('data',$this->Roaster->find('all', array('order'=>array('Roaster.roasterdate'=>'desc'))));
    }
  


}
?>