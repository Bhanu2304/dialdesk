<?php
class SimsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses = array('Sim','SimAlert');
    public function beforeFilter() {
        parent::beforeFilter();
		
        $this->Auth->allow(
			'index',
			'add',
			'view',
            'edit',
            'alert',
            'update');
		
                /*
		else
		{
			$this->deny('index',
			'add',
			'view',
			'setPriority');
			}*/
    }
	
	public function index() 
        {
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');
		
		$this->set('data',$this->Sim->find('all'));
                
                if($this->request->is('Post'))
                {
                    if(!empty($this->request->data))
                    {   $data = array();
                        //print_r($this->request->data);die;
                        $sim = $this->request->data;
                        $sim = $sim['Sims'];
                        
                        if($this->Sim->find('first',array('conditions'=>array('contactnumber'=>$sim['contactnumber']))))
                        {
                            $this->Session->setFlash('Sim Already Exists');
                        }
                        else
                        {
                            foreach($sim as $k=>$v)
                            {
                                if($k=='rechargedate' || $k=='dateofalert')
                                {
                                    $v_split =  explode("-",$v);
                                    $v_sort = array_reverse($v_split);
                                    $v = implode('-',$v_sort); 
            
                                }
                                $data[$k] = addslashes($v);
                            }

                            $data['created_at'] = date('Y-m-d H:i:s');
                            $data['created_by'] = $this->Session->read('admin_id');
                            if($this->Sim->save($data))
                            {
                                $this->redirect(array('controller'=>'Sims'));
                                $this->Session->setFlash('Sim Added Successfully');
                            }
                            else
                            {
                                $this->Session->setFlash('Sim Not Added, Please Try Again');
                            }
                        }
                    }
                }
	}
	

    public function edit()
    {
        $this->layout="ajax";
        if(isset($_REQUEST['id']))
        {
            $id = $_REQUEST['id']; 
            $sim = $this->Sim->find('first',array('conditions'=>array('id'=>$id)));
            $this->set('sim',$sim);
        }
    }
    
    public function update() {
        $this->layout='user';
        if($this->request->is('Post'))
        {   
            $id = $this->request->data['id'];
            $data = $this->request->data['Sims'];
            // print_r($this->request->data);die;
            
            foreach($data as $k=>$v):
                if($k=='rechargedate' || $k=='dateofalert')
                {
                    $v_split =  explode("-",$v);
                    $v_sort = array_reverse($v_split);
                    $v = implode('-',$v_sort); 

                }
                $data1[$k] = "'".addslashes($v)."'";
            endforeach;
        
            $data = $data1;
            $data['updated_at'] =  "'".date('Y-m-d H:i:s'). "'";
            $data['updated_by'] =  "'".$this->Session->read('admin_id'). "'";
            //print_r($data);die;
            if($this->Sim->updateAll($data,array('id'=>$id)))
            {$this->Session->setFlash("Record Updated Successfully");}
            else{$this->Session->setFlash("Record Not Updated Successfully, Please Try Again!");}
            unset($data);unset($data1);
        }
        $this->Session->setFlash("Fields update successfully.");
	    $this->redirect(array('controller' => 'Sims', 'action' => 'index'));
	}
    
    public function alert() 
    {
		$this->layout='user';
		$sim_det = $this->SimAlert->find('first');
                
                $this->set('sim_det',$sim_det['SimAlert']);
                
            if($this->request->is('Post'))
            {
                if(!empty($this->request->data))
                {  
                    $data = array();
                    $sim = $this->request->data;
                    $sim = $sim['Sims'];
                    
                    $exist_sim = $this->SimAlert->find('first');
                        foreach($sim as $k=>$v)
                        {
                            if($exist_sim)
                            {
                                $data[$k] = "'".addslashes($v)."'";
                            }
                            else
                            {
                                $data[$k] = addslashes($v);
                            }
                        }
                        if($exist_sim)
                            {
                         $data['updated_at'] = "'".date('Y-m-d H:i:s')."'";
                            }
                            else
                            {
                                $data['created_at'] = date('Y-m-d H:i:s');
                            }
                        if($exist_sim)
                        {
                            $this->SimAlert->updateAll($data,array('id'=>$exist_sim['SimAlert']['id']));
                            $this->redirect(array('controller'=>'Sims'));
                            $this->Session->setFlash('Alert Updated Successfully');
                        }
                        else if($this->SimAlert->save($data))
                        {
                            $this->redirect(array('controller'=>'Sims'));
                            $this->Session->setFlash('Alert Added Successfully');
                        }
                        else
                        {
                            $this->Session->setFlash('Alert Not Added, Please Try Again');
                        }
                }
            }
	}
        
        
       
        
	
}
?>