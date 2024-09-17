<?php
class AdminIvrsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','Ivr','PlanMaster');
	
    public function beforeFilter() {
		parent::beforeFilter();
			$this->Auth->allow();
			if(!$this->Session->check("admin_id")){
				return $this->redirect(array('controller'=>'Admins','action' => 'index'));
			}		
    }
	
	public function index(){
		$this->layout='adminlayout';
		$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
		$this->set('client',$client);
		
		if($this->request->is('Post')){
			$ClientId = $this->request->data['AdminIvrs']['client'];
			$this->Session->write("companyid",$ClientId);
			
			$store_all_id = array();	
			$html = "";	
			if($id_result = $this->Ivr->find('all',array('conditions'=>array('clientId'=>$ClientId)))){
				foreach($id_result as $post):			
					array_push($store_all_id, $post['Ivr']['parent_id']);
				endforeach;
				
				$in_parent = 0; 
				$html = "<div class='overflow'><div>".$this->in_parent($in_parent,$store_all_id,$html,$ClientId);
				$html .= "</div></div>";
				$this->set('html',$html);
			}
			else{
				$this->Ivr->save(array('clientId'=>$ClientId,'parent_id'=>'0','Msg'=>'Welcome','createdate'=>date('Y-m-d H:i:s')));
				$this->redirect(array('action'=>'index'));
			}
		}
		else{
			$this->set('html','');
			$this->Session->delete('companyid');
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
			$html .= "><div id=" . $post['Ivr']['id'] . "><span class='first_name'>" . $post['Ivr']['Msg'] . "</span></div>";
        	$html = $this->in_parent($post['Ivr']['id'], $store_all_id,$html,$ClientId);
        	$html .= "</li>";
        endforeach;
        $html .= "</ul>";
     }
	return $html;
}	
	
}
?>