<?php
	class CloseloopsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler','Session');
    public $uses=array('CallMaster');

	
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid")){
                return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
	
	public function index() {
            //ini_set('max_execution_time', 0);
           
            
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');			
		      

		if($this->request->is("POST") && !empty($this->request->data)){
                    
			
			$csv_file = $this->request->data['Closeloops']['uploadfile']['tmp_name'];
			$FileTye = $this->request->data['Closeloops']['uploadfile']['type'];
			$info = explode(".",$this->request->data['Closeloops']['uploadfile']['name']);
			//print_r($this->request->data); die;
			//print_r($info);die;
			//echo $FileTye;die;
			//echo strtolower(end($info));die;
			if(($FileTye=='application/csv' || $FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream' || $FileTye=='text/csv') && strtolower(end($info)) == "csv"){
				
				if (($handle = fopen($csv_file, "r")) !== FALSE) {
					$filedata = fgetcsv($handle, 1000, ","); 
					
					while (($filedata = fgetcsv($handle, 1000, ",")) !== FALSE) {
						$Remarks = str_replace("'","", $filedata[5]);
					$this->CallMaster->updateAll(array('CField1'=>"'{$filedata[4]}'",'CField4'=>"'{$Remarks}'",'CFieldUpdate'=>'now()'),"Id='{$filedata[0]}' and ClientId='{$filedata[1]}'");				
                                        
					 }
					 
				}

				$this->Session->setFlash('<span style="color:green;">Tickets Updates SuccessFully</span>');
				$this->redirect(array('action' => 'index'));
			}
			else{
				$this->Session->setFlash('File Format not valid! Upload in CSV formate.');
			}
		}	
	}
		
	
}

?>