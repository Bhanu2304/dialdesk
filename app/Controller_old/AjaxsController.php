<?php
class AjaxsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('Ivr');
	
    public function beforeFilter() 
	{
        parent::beforeFilter();
		if($this->Session->check('companyid'))
		{
        $this->Auth->allow(
			'index');
		}
		else
		{$this->Auth->deny('index',
			'add',
			'view');}
    }

	public function index()
	{	
	if($this->request->data['action']=='edit')
	{
		
		if($this->request->is('Post'))
		{
			
			$Msg =$this->request->data['first_name'];
			$id = $this->request->data['id'];
			
			$this->Ivr->updateAll(array('Msg'=>"'".$Msg."'"),array('id'=>$id));
		}
	}
	
	else if($this->request->data['action']=='delete')
	{
		if($this->request->is('Post'))
		{
			$id = $this->request->data['id'];			
			$this->Ivr->DeleteAll(array('id'=>$id));
		}
	}
	
	else if($this->request->data['action']=='add')
	{
		$this->layout="ajax";
		if($this->request->is('Post'))
		{
			$showhideval = isset($this->request->data['showhideval']) ? 1 : 0;
			$ClientId = $this->Session->read('companyid');
			$msg = $this->request->data['first_name'];
			$parent_id = $this->request->data['parentid'];
			$this->Ivr->save(array('Msg'=>$msg,'parent_id'=>$parent_id,'clientId'=>$ClientId,'createdate'=>date('Y-m-d H:i:s')));
			$this->set('add',$this->Ivr->getLastInsertID());
		}
	}
	else if($this->request->data['action']=='drag')
	{
		if($this->request->is('Post'))
		{						
			$id = $this->request->data['id'];
			$parent_id = $this->request->data['parentid'];
			$this->Ivr->updateAll(array('parent_id'=>$parent_id),array('id'=>$id));
		}
	}
	else if($this->request->data['action']=='addform')
	{
		$this->layout = 'ajax';
		if($this->request->is('Post'))
		{	
		$this->set('addform','1');					
		}
	}
	
	else if($this->request->data['action']=='editform')
	{
		$this->layout = 'ajax';
		if($this->request->is('Post'))
		{
			
			$edit_ele_id = $this->request->data['edit_ele_id'];
			$data = $this->Ivr->find('all',array('conditions'=>array('id' =>$edit_ele_id)));
			$this->set('editform',$data);
  		}
	}
	}
}
?>