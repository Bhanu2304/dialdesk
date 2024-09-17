<?php
class AgentImagesController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses = array('ImageMail','AgentWhatsappData');
    public function beforeFilter() {
        parent::beforeFilter();
		
        $this->Auth->allow(
			'index',
            'edit',
            'update',
        'view_image');
		
    }
	
    public function index() 
    {
		$this->layout='user';
		$sim_det = $this->ImageMail->find('first');
                
                $this->set('sim_det',$sim_det['ImageMail']);
                
            if($this->request->is('Post'))
            {
                if(!empty($this->request->data))
                {  
                    $data = array();
                    $sim = $this->request->data;
                
                    $sim = $sim['AgentImages'];
                    
                    $exist_sim = $this->ImageMail->find('first');
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
                            $this->ImageMail->updateAll($data,array('id'=>$exist_sim['ImageMail']['id']));
                            $this->redirect(array('controller'=>'AgentImages'));
                            $this->Session->setFlash('Alert Updated Successfully');
                        }
                        else if($this->ImageMail->save($data))
                        {
                            $this->redirect(array('controller'=>'AgentImages'));
                            $this->Session->setFlash('Alert Added Successfully');
                        }
                        else
                        {
                            $this->Session->setFlash('Alert Not Added, Please Try Again');
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
            //print_r($data);die;
            if($this->Sim->updateAll($data,array('id'=>$id)))
            {$this->Session->setFlash("Record Updated Successfully");}
            else{$this->Session->setFlash("Record Not Updated Successfully, Please Try Again!");}
            unset($data);unset($data1);
        }
        $this->Session->setFlash("Fields update successfully.")
                ;
	    $this->redirect(array('controller' => 'Sims', 'action' => 'index'));
	}

    public function view_image()
    {
        $this->layout='user';

        $data =$this->AgentWhatsappData->find('all');
        $qry = "select * from agent_whatsapp_log where type='image' and  image is not null";
  
        $this->set('data',$this->AgentWhatsappData->query($qry));

    }
    

        
        
       
        
	
}
?>