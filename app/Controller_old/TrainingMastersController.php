<?php
class TrainingMastersController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler','Session');
    public $uses=array('Training','FieldValue','TrainingMaster','TrainingDetails');
	
    public function beforeFilter() {
        parent::beforeFilter();
        if($this->Session->check("companyid")){
            $this->Auth->allow('index','update','delete_training_file','download_training_file');
        }	
    }
	
    public function index() {
        $this->layout='user';
        $ClientId = $this->Session->read('companyid');
        $data = array();
        $data = $this->TrainingMaster->find('all',array('conditions'=>array('ClientId'=>$ClientId)));
        $this->set('data',$data);
    }
	
    public function update() {
        if(isset($_FILES['training'])){
            $compid  = $this->Session->read('companyid');
            $desc=$this->request->data;
            
            if (!file_exists(WWW_ROOT.'upload/training_file/client_'.$compid)) {
                mkdir(WWW_ROOT.'upload/training_file/client_'.$compid, 0777, true);
            }
            $path=WWW_ROOT.'upload/training_file/client_'.$compid.'/';
            
            $filename='training';
            $fileName=array();
            $fileName['ClientId']=$compid;
            $files = $_FILES;
            $cpt = count($_FILES['training']['name']);
            
            if($cpt >0){
                for($i=0; $i<$cpt; $i++){         
                    $fn=$i+1;
                    $rand = rand(100000,999999);
                    $_FILES[$filename]['name']		= $files[$filename]['name'][$i];
                    $_FILES[$filename]['type']		= $files[$filename]['type'][$i];
                    $_FILES[$filename]['tmp_name']	= $files[$filename]['tmp_name'][$i];
                    $_FILES[$filename]['error']		= $files[$filename]['error'][$i];
                    $_FILES[$filename]['size']		= $files[$filename]['size'][$i];    

                    $ary_ext=array('jpg','jpeg','gif','png','pdf','doc','csv','xlsx','xls','csv'); 
                    $ext = substr(strtolower(strrchr($_FILES[$filename]['name'], '.')), 1); 
                    if(in_array($ext, $ary_ext)){
                        move_uploaded_file($_FILES[$filename]['tmp_name'],$path.$rand.$_FILES[$filename]['name']);
                        $fileName['Field'.$fn]= $rand.$_FILES[$filename]['name'];
                    }
                    $fileName['Des'.$fn]= $desc['description'][$i];
                }
                $this->TrainingMaster->saveAll($fileName);
            }
        }
        $this->Session->setFlash('Training document upload successfully.');
        $this->redirect(array('action'=>'index'));
    }
    
    public function delete_training_file(){
        $cid=$this->Session->read('companyid'); 
        $path=WWW_ROOT.'upload/training_file/client_'.$cid.'/';
        if(isset($_REQUEST['id'])){
            $id=$_REQUEST['id'];
            $trafile=$this->Training->find('first',array('conditions'=>array('id'=>$id,'ClientId'=>$cid))); 
            for($i=1; $i <= 10; $i++){
                $file=$trafile['Training']['Field'.$i];
                if($file !=""){
                    unlink($path.$file);
                }
            }
            $this->Training->deleteAll(array('id'=>$id,'ClientId'=>$cid));
        }
        $this->redirect(array('action'=>'index'));
    }
    
    public function download_training_file(){
        if(isset($_GET['file'])){ 
            $cid=$this->Session->read('companyid'); 
            $path=WWW_ROOT.'upload/training_file/client_'.$cid.'/';
            $file = $path.$_GET['file'] ;
          
            if (file_exists($file) && is_readable($file))  {
                $extension = explode('.',$file);
                $extension = $extension[count($extension)-1]; 
                
                header('Content-Type: application/'.$extension);  
                header('Content-Disposition: attachment; filename=' . $file);  
                readfile($file); 
                exit;
            }
            else{
                $this->Session->setFlash('<span style="color:red;">File does not exists on given path.</span>');
                $this->redirect(array('action'=>'index'));
            }
            
            
        } 
    }

	
	
}
?>