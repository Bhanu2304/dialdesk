<?php
    class Ajax2sController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('WorkFlow','Ivr');
	
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

    public function index(){	
        $this->set('image',$this->webroot);
	if($this->request->data['action']=='edit'){
            if($this->request->is('Post')){
                $ClientId = $this->Session->read('companyid');
                $data=$this->request->data;
              
                $dataArr=array(
                    'start_time'=>"'".$data['first_name']."'",
                    'end_time'=>"'".$data['end_time']."'",
                    'transferType'=>"'".$data['transferType']."'",
                    'Numbers'=>"'".$data['Numbers']."'"
                    );
                
                $this->WorkFlow->updateAll($dataArr,array('id'=>$data['id'],'clientId'=>$ClientId,));
                $this->Session->setFlash("Work flow update successfully.");
                $this->redirect(array('controller' => 'WorkFlows', 'action' => 'index'));
            }
	}
	
	else if($this->request->data['action']=='delete'){
            if($this->request->is('Post')){
                $this->WorkFlow->DeleteAll(array('parent_id'=>$this->request->data['id']));
            }
	}
	
	else if($this->request->data['action']=='add'){
            $this->layout="ajax"; 
            if($this->request->is('Post')){
                $ClientId = $this->Session->read('companyid');
                $data=$this->request->data;
                
                $dataArr=array(
                    'clientId'=>$ClientId,
                    'parent_id'=>$data['parentid'],
                    'start_time'=>$data['first_name'],
                    'end_time'=>$data['first_name'],
                    'transferType'=>$data['transferType'],
                    'Numbers'=>$data['Numbers'],
                    'createdate'=>date('Y-m-d H:i:s')
                );
                
                if($this->WorkFlow->find('first',array('fields'=>array('id'),'conditions'=>array('parent_id' =>$data['parentid'],'clientId'=>$ClientId,)))){
                    echo "1";die;
                }
                else{
                    $this->WorkFlow->saveAll($dataArr);
                    echo "";die;
                }
                
                 
    
                //$showhideval = isset($this->request->data['showhideval']) ? 1 : 0;
                  

                //$parent_id = $this->request->data['parentid'];
               // $this->WorkFlow->saveAll($dataArr);
                //$this->redirect(array('controller' => 'WorkFlows', 'action' => 'index'));
                //$this->set('add',$this->WorkFlow->getLastInsertID());
            }
	}
	else if($this->request->data['action']=='drag')
	{
		if($this->request->is('Post'))
		{						
			$id = $this->request->data['id'];
			$parent_id = $this->request->data['parentid'];
			$this->WorkFlow->updateAll(array('parent_id'=>$parent_id),array('id'=>$id));
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
	
	else if($this->request->data['action']=='editform'){
            $this->layout = 'ajax';
            if($this->request->is('Post')){
                $ClientId = $this->Session->read('companyid');
                $edit_ele_id = $this->request->data['edit_ele_id'];
                $data = $this->Ivr->find('all',array('conditions'=>array('id' =>$edit_ele_id)));
                $this->set('editform',$data);
                $data1 = $this->WorkFlow->find('first',array('conditions'=>array('clientId'=>$ClientId,'parent_id' =>$edit_ele_id))); 
                $this->set('data1',$data1['WorkFlow']);
            }
	}
        
	}
}
?>