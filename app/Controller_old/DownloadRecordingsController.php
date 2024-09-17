<?php
class DownloadRecordingsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('RecordingLog');
	
    public function beforeFilter() {
        parent::beforeFilter();
        if($this->Session->check('companyid')){
            $this->Auth->allow();
	}	
    }
	
    public function index(){
        if(isset($_REQUEST['lead_id']) && $_REQUEST['lead_id'] !=""){
            $filename=$_REQUEST['lead_id'];
            $ctype="application/gsm";
            $this->RecordingLog->useDbConfig = 'db2';
            $call_excute= $this->RecordingLog->query("Select start_time,location from recording_log where lead_id='$filename'");
            
            $dt=$call_excute[0]['recording_log'];
            
            $dir=substr($dt['start_time'],0,10);
            $dir=str_replace("-","",$dir);

            $tmp = explode("/",$dt['location']);
            $tmp_idx = count($tmp)-1;
            $tmp_filename = $tmp[$tmp_idx];
            
            if($tmp_filename!="") { $filename="/opt/recording/inbound/{$dir}/{$tmp_filename}"; } else { $filename=""; }
            
            if(file_exists($filename)){
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: private",false);
                header("Content-Type: $ctype");

                header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: ".filesize($filename));
                readfile("$filename");
            }  
        }
    }
                        	
}
?>