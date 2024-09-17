<?php
class AdminTrainingsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','Training','FieldValue');
	
    public function beforeFilter() {
        parent::beforeFilter();
			$this->Auth->allow('update','view');
			if(!$this->Session->check("admin_id")){
				return $this->redirect(array('controller'=>'Admins','action' => 'index'));
			}
			
    }

	public function index() {
		$this->layout='adminlayout';
		$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
		$this->set('client',$client);
	}	
	
	public function update() {
		$msg = "<font color=\"#FF0000\">File is not Valid</font>";
		$this->layout='adminlayout';
		if($this->request->is('Post')){
			$ClientId = $this->request->data['AdminTrainings']['client'];
			$this->set('data',isset($this->request->data['AdminTrainings'])?$this->request->data['AdminTrainings']:"");
			$files = isset($this->request->data['AdminTrainings'])?$this->request->data['AdminTrainings']['files']:"";
			
				if(isset($files) && !empty($files))
				{
					App::uses('FileValidate', 'custom/File');
						$validate = new FileValidate();
						
					$filepath='';
					$flag = true;
					//$fileType = array("image/jpeg","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/vnd.ms-excel","");
					$size = 0; $i =0;
					foreach($files as $file):
						$size += round($file['size']/(1024*1024));
						if($size >500 )
						{
							$flag = false; 
							$msg = "File size Not More Than 500 MB";
							break;
						}
						$i++;
						if($i>10)
						{
						  $flag = false;
						  $msg = "Please Do Not Upload More Than 10 Files";
						  break;
						}
						
						$flag = false;
						if($validate->get_mimetype($file['name'])==$file['type'])
						{
							$flag = true;							
						}
						else
						{
							$msg = "Please Upload File Type PPT,Excel,Doc,Image \n File ".$file['name']." is not valid";
							break;
						}
						
					endforeach;
					
					$msg= "<font color=\"#FF0000\">".$msg."</font>";
					
					$arr = array();
					if($flag)
					{$i =1;
					foreach($files as $file):
						//move_uploaded_file($file['tmp_name'],WWW_ROOT.'upload\\'.$file['name']);
						move_uploaded_file($file['tmp_name'],WWW_ROOT.'training/'.$file['name']);
						$filepath.=addslashes($file['name']).",";
						$arr['Field'.$i++] = addslashes($file['name']);
					endforeach;
					}
					$arr['ClientId'] = $ClientId;
					$arr['createdate'] = date('Y-m-d H:i:s');
					if($flag){if($this->Training->save($arr)){$msg = "<font color=\"#009933\">File Uploaded Successfully</font>";}}
					
					//$this->set('data',$dd);
				}
				$this->Session->setFlash($msg);
				$this->redirect(array('action'=>'index'));
		}
	}
	
	public function view(){
		$this->layout='adminlayout';
		$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
		$this->set('client',$client);
		$data = array();
		if($this->request->is('Post')){
			$ClientId = $this->request->data['AdminTrainings']['client'];
			$data = $this->Training->find('all',array('conditions'=>array('ClientId'=>$ClientId)));
			$this->set('data',$data);
		}
		else{
			$this->set('data',$data);
		}		
	}

			
}
?>