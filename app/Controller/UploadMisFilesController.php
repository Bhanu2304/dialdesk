<?php
class UploadMisFilesController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler','Session');
	public $uses=array('UploadMisFile','RegistrationMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
			'index',
			'add','download','get_file');
    }
	
	public function index() {
		$this->layout='user';		
		$ClientId = $this->Session->read('companyid');
                
	}
	
         public function add() {
            $this->layout='user';
            $ClientId = $this->Session->read('companyid');
            
            $startdate = $this->request->data['startdate'];
            $tmp_file = $_FILES['mis_file']['tmp_name'];
            $FileTye = $_FILES['mis_file']['type'];
            $f_Name = $_FILES['mis_file']['name'];
            $Doc_Name = preg_replace('/[^A-Za-z0-9.\-]/', '', $f_Name);
            $size = $_FILES['mis_file']['size'];
            
            if(!empty($startdate))
            {
                $startdate = date('Y-m-d',strtotime($startdate));
            }
            if($size==0)
            {
                $this->Session->setFlash('File is empty. Please upload a file.');
                $this->redirect(array('action'=>'index'));
            }
            else
            {
                $rec_exist = $this->UploadMisFile->find('first',array('conditions'=>"date(upload_date)='$startdate' and client_id='$ClientId'"));
                if(!empty($rec_exist))
                {
                    $rec_id = $rec_exist['UploadMisFile']['id'];
                }
                else
                {
                    $recordb = array('upload_date'=>$startdate,'client_id'=>$ClientId);
                    $rec = $this->UploadMisFile->save($recordb);
                    $rec_id =$this->UploadMisFile->id; 
                }
                $dir_name = "/var/www/html/dialdesk/app/webroot/clientwise_mis_files/$rec_id";
                if (!file_exists($dir_name)) 
                {
                    mkdir("$dir_name/", 0777, true);
                }
                $filename = "$dir_name/$Doc_Name";
                $move_status = move_uploaded_file($tmp_file,$filename );
                if($move_status){
                    $this->UploadMisFile->updateAll(array('file_name'=>"'".$filename."'"),array('id'=>$rec_id));
                    $this->Session->setFlash('File Uploaded Successfully.');
                    $this->redirect(array('action'=>'index'));
                }
                else{
                    $this->Session->setFlash('File Uploaded Failed. Please Try Again.');
                    $this->redirect(array('action'=>'index'));
                }
            }
               
            
	}
	public function download() {
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');
		
		if(!empty($this->request->data))
                {
                    
                    $startdate = $this->request->data['startdate'];
                    $enddate = $this->request->data['enddate'];
                    $startdate = date('Y-m-d',strtotime($startdate));
                    $enddate = date('Y-m-d',strtotime($enddate));
                    
                    $record_arr = $this->UploadMisFile->find('all',array('conditions'=>"date(upload_date) between '$startdate' and '$enddate' and client_id='$ClientId'"));
                    //print_r($record_arr);exit;
                    echo '<script>
function myFunction(url) {
  window.open(url);
}
</script>';   
                    foreach($record_arr as $record)
                    {
                        $filename = $record['UploadMisFile']['file_name'];
                        if(file_exists($filename)){
                            $url = "get_file?file_name=".base64_encode($filename);
                        echo "<script type=\"text/javascript\">myFunction('".$url."');</script>"; 
                       // $msg = "self.close();";
                    }
                else{
                     
                        $msg = "alert('No Recording Files !');";  
                        echo "<script type=\"text/javascript\">$msg</script>"; exit;
                    }
                    
                    }
                    
                    //print_r($files_url);exit;
                    //$this->redirect($files_url);
                    //header("Location: $files_url");

                }
	
	}
	
        
        public function get_file()
        {
            $this->layout="ajax";
            //print_r($this->params->query);
            $filename = base64_decode($this->params->query['file_name']); 
            
            header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename='.basename($filename));
                        header('Content-Transfer-Encoding: binary');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($filename));
                        ob_clean();
                        flush();
            readfile($filename);
            
            
        }
        
       
        
        
        
}
?>