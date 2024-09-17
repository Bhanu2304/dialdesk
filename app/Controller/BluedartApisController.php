<?php
class BluedartApisController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('AgentMaster','RegistrationMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('view_agent','delete_agents');
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }		
    }
	
    public function index() {
        $this->layout='user';
        if($this->request->is("POST")){
            $csv_file   =   $_FILES['uploadcsv']['tmp_name'];
            $FileTye    =   $_FILES['uploadcsv']['type'];
            $info       =   explode(".",$_FILES['uploadcsv']['name']);
            
            if(($FileTye=='text/csv' || $FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
                if (($handle = fopen($csv_file, "r")) !== FALSE) {
                    $filedata = fgetcsv($handle, 1000, ",");
   
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $ServiceCenterCode=$data[0];
                        $ServiceCenterAddress=  addslashes($data[1]);
                        
                        if($list_value!=''){									
                            $list_value=$list_value.",('".$ServiceCenterCode."','".$ServiceCenterAddress."',NOW())";
                        }
                        else{
                            $list_value="('".$ServiceCenterCode."','".$ServiceCenterAddress."',NOW())";
                        }   
                    }
                    
                    $this->RegistrationMaster->query("truncate table bluedart_address_master");
                    $this->RegistrationMaster->query("INSERT INTO bluedart_address_master(`ServiceCenterCode`,`Address`,`UploadDate`) values $list_value"); 
                    $this->Session->setFlash('<span style="color:green;font-weight:bold;" >Your csv upload successfully.</span>'); 
                    $this->redirect(array('action' => 'index'));
                }				 
            }
            else{
                $this->Session->setFlash('<span style="color:red;font-weight:bold;" >Upload only csv file.</span>'); 
                $this->redirect(array('action' => 'index'));
            }
          
        }	
    }
		
}

?>