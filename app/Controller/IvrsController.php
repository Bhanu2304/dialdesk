<?php
class IvrsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('Ivr','PlanMaster','ClientCategory','IvrFileMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
		if($this->Session->check('companyid'))
		{
        $this->Auth->allow(
			'index',
                        'upload_ivr_file',
                        'upload_multiple_file',
                        'delete_ivr_file',
                        'download_ivr_file',
			'add');
		}
		else
		{$this->Auth->deny('index',
			'add',
			'view');}
    }
	
	public function index() 
	{
		
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');
                $this->set('ivrfile',$this->IvrFileMaster->find('all',array('conditions'=>array('client_id'=>$ClientId))));
                
		$store_all_id = array();		
		$html = "";
		
		if($id_result = $this->Ivr->find('all',array('conditions'=>array('clientId'=>$ClientId))))
		{
			foreach($id_result as $post):			
				array_push($store_all_id, $post['Ivr']['parent_id']);
			endforeach;
			
			$in_parent = 0; 
			//$html = <div class='overflow'><div>
			$html = "<div class='overflow'><div>".$this->in_parent($in_parent,$store_all_id,$html,$ClientId);
        	$html .= "</div></div>";
			
			$this->set('html',$html);
		}
		else
		{
			$this->Ivr->save(array('clientId'=>$ClientId,'parent_id'=>'0','Msg'=>'Welcome','createdate'=>date('Y-m-d H:i:s')));
			$this->redirect(array('action'=>'index'));
		}
	}
	
	public function in_parent($in_parent, $store_all_id,$html,$ClientId) 
	{
		if (in_array($in_parent, $store_all_id)) 
		{
			$result = $this->Ivr->find('all',array('conditions'=>array('parent_id'=>$in_parent,'clientId'=>$ClientId)));
        	$html.= $in_parent == 0 ? "<ul class='tree'>" : "<ul>";
       		 foreach($result as $post) :
        	$html .= "<li";
        	if ($post['Ivr']['hide'])
        	$html .= " class='thide'";
			$html .= "><div id=" . $post['Ivr']['id'] . "><span class='first_name' style=\"font-size:11px;\">" . wordwrap($post['Ivr']['Msg'],15,"<br>\n",TRUE) . "</span></div>";
        	$html = $this->in_parent($post['Ivr']['id'], $store_all_id,$html,$ClientId);
        	$html .= "</li>";
        endforeach;
        $html .= "</ul>";
     }
	return $html;
}	
	public function next() {
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');
		if($ClientId == ''){$this->redirect(array('controller'=>'ClientActivations','action'=>'logout'));}
		if($this->request->is('POST'))
		{
			
		}		
			}
	


    public function upload_ivr_file(){
        if ($this->request->is('post')){
            $compid = $this->Session->read('companyid');
            if (!file_exists(WWW_ROOT.'upload/ivr_file/client_'.$compid)) {
                mkdir(WWW_ROOT.'upload/ivr_file/client_'.$compid, 0777, true);
            }
            $path=WWW_ROOT.'upload/ivr_file/client_'.$compid.'/';
            $data=$this->upload_multiple_file($filename='ivrfile',$path);
            foreach($data as $file_name){
                $ivrFileArr=array('client_id'=>$compid,'file_name'=>$file_name);
                $this->IvrFileMaster->saveAll($ivrFileArr);
            }
            $this->Session->setFlash('IVR file upload successfully.');
            $this->redirect(array('controller' => 'Ivrs', 'action' => 'index'));
        }
    }
    
    public function upload_multiple_file($filename,$path){
		$files = $_FILES;
		$fileName=array();
		$cpt = count($_FILES[$filename]['name']);
		if($cpt >0){
			for($i=0; $i<$cpt; $i++){
				$rand = rand(100000,999999);
				$_FILES[$filename]['name']		= $files[$filename]['name'][$i];
				$_FILES[$filename]['type']		= $files[$filename]['type'][$i];
				$_FILES[$filename]['tmp_name']	= $files[$filename]['tmp_name'][$i];
				$_FILES[$filename]['error']		= $files[$filename]['error'][$i];
				$_FILES[$filename]['size']		= $files[$filename]['size'][$i];    
		
				$ary_ext=array('mp3','mp4'); 
				$ext = substr(strtolower(strrchr($_FILES[$filename]['name'], '.')), 1); 
				if(in_array($ext, $ary_ext)){
					move_uploaded_file($_FILES[$filename]['tmp_name'],$path.$rand.$_FILES[$filename]['name']);
					$fileName[]= $rand.$_FILES[$filename]['name'];
				}
			}
			return $data=$fileName;
		}
		else{
			$data=NULL;
		}
	}
        
        public function delete_ivr_file(){
            $cid=$this->Session->read('companyid'); 
            $path=WWW_ROOT.'upload/ivr_file/client_'.$cid.'/';
            if(isset($_REQUEST['id'])){
                $id=$_REQUEST['id'];
                $ivrfile=$this->IvrFileMaster->find('first',array('fields'=>array('file_name'),'conditions'=>array('id'=>$id,'client_id'=>$cid)));              
                if(!empty($ivrfile)){
                   unlink($path.$ivrfile['IvrFileMaster']['file_name']);
                   $this->IvrFileMaster->deleteAll(array('id'=>$id,'client_id'=>$cid));
                }
            }
            $this->redirect(array('controller' => 'Ivrs', 'action' => 'index'));
        }
        
        public function download_ivr_file(){
            if(isset($_GET['file'])){ 
                $cid=$this->Session->read('companyid'); 
                $path=WWW_ROOT.'upload/ivr_file/client_'.$cid.'/';
                $file = $path.$_GET['file'] ;
                if (file_exists($file) && is_readable($file) && preg_match('/\.mp3$/',$file))  { 
                    header('Content-type: application/mp3');  
                    header("Content-Disposition: attachment; filename=\"$file\"");   
                    readfile($file); 
                } 
            } 
        }
                        
             
	
}
?>