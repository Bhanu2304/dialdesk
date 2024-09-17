<?php
class ProcessIntegrationsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','IvrMaster');
	
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('process_integration','view_process','getParentProcess');
		if(!$this->Session->check("companyid")){
			return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
		}
    }
	
	


	public function index(){
		$this->layout='user';
		if ($this->request->is('post')){
			$ClientId=$this->Session->read('companyid');
			//$pid=addslashes(trim($this->request->data['ProcessIntegrations']['pid']));
			$pid=$this->request->data['lastparent'];
			if($pid ==''){
				$parentid=0;	
			}
			else{
				$parentid=$pid;
			}
			
			$name=addslashes(trim($this->params['data']['name']));
			$inputArr=array(
				'name'=>addslashes(trim($this->params['data']['name'])),
				'pid'=>addslashes(trim($pid)),
				'cid'=>$this->Session->read('companyid'));
				
				if($this->IvrMaster->find('first',array('fields'=>array('id'),'conditions'=>array('pid'=>$parentid,'name'=>$name,'cid'=>$ClientId)))){
					$this->Session->setFlash("Already Exists at Given Process Name");
					$this->redirect(array('controller' => 'ProcessIntegrations', 'action' => 'index'));
				}
				else{
					$this->IvrMaster->save($inputArr);
					$this->redirect(array('controller' => 'ProcessIntegrations', 'action' => 'view_process'));
				}
		}else{
			$ivrData=array();
			//$data = $this->fetchCategoryTree();
			$data = $this->IvrMaster->find('list',array('fields'=>array("id","name"),'conditions'=>array('pid'=>0,'cid'=>$this->Session->read('companyid'))));
			$this->set('data',$data);
		}
	}
	
	
	public function getParentProcess(){
		$this->layout="ajax";
		$data = $this->request->data;
		$label ="pid".$data['Label'];
		$dataArr['pid']=$data['pid'];
		$dataArr['cid']=$this->Session->read('companyid');
		$data = $this->IvrMaster->find('list',array('fields'=>array("id","name"),'conditions'=>$dataArr));
		$this->set('data',$data);
		$this->set('label',$label);
	}
	
	
	
	

	public function fetchCategoryTree($parent = 0, $spacing = '', $user_tree_array = '') {
		if (!is_array($user_tree_array))
			$user_tree_array = array();
			$cid=$this->Session->read('companyid');
			$query=$this->IvrMaster->query("SELECT id,name,pid,cid FROM ivr WHERE 1 AND pid = $parent AND cid=$cid ORDER BY id ASC");
			
			if (count($query) > 0) {
				foreach($query as $row){
					$user_tree_array[] = array("id" => $row['ivr']['id'], "name" => $spacing . $row['ivr']['name']);
					$user_tree_array = $this->fetchCategoryTree($row['ivr']['id'], $spacing.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $user_tree_array);
				}
			}
			return $user_tree_array;
	}
	
	public function view_process() {
		$this->layout='user';
		$categoryList = $this->categoryParentChildTree($parent = 0, $spacing = '', $category_tree_array = '');
		$this->set('record',$categoryList);	
	}
	
	public function categoryParentChildTree($parent, $spacing, $category_tree_array) {
    	if (!is_array($category_tree_array))
        $category_tree_array = array();
		$clientid=$this->Session->read('companyid');
 		$query=$this->IvrMaster->query("SELECT id,name,pid,cid FROM ivr WHERE 1 AND pid = $parent AND cid=$clientid ORDER BY id ASC");
    	if (count($query) > 0) {
		foreach($query as $rowCategories){
            $category_tree_array[] = array("id" => $rowCategories['ivr']['id'], "name" => $spacing . $rowCategories['ivr']['name']);
            $category_tree_array = $this->categoryParentChildTree($rowCategories['ivr']['id'], '&nbsp;&nbsp;&nbsp;&nbsp;'.$spacing . '-&nbsp;', $category_tree_array);
        }
    }
    return $category_tree_array;
}



}
?>