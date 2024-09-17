<?php
class AbandCallbackController extends AppController{

        public $helpers = array('Html', 'Form','Js');
        public $components = array('RequestHandler');
        public $uses=array('RegistrationMaster','AbandCallTime');
	
        
        public function beforeFilter(){
            parent::beforeFilter();
            $this->Auth->allow();
            // if(!$this->Session->check("companyid")){
            //     return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
            // }

        }

    
	    public function index() {
            $this->layout='user';
            if($this->Session->read('role') =="admin"){
                $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
                $this->set('client',$client); 
            }else{
                $clientId   = $this->Session->read('companyid');
                $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','company_id'=>$clientId),'order'=>array('Company_name'=>'asc')));
                
                $this->set('client',$client); 
            }


           $data_aband = $this->AbandCallTime->query("SELECT id,getClientName(client_id) as clientname,start_time,end_time,aband_status,created_at FROM aband_call_time WHERE active='1'");
           $this->set('data_aband',$data_aband);
                
            
            if($this->request->is("POST"))
            {

                $admin_id = $this->Session->read('admin_id');
        
                $client_id =$this->request->data['AbandCallback']['client_id'];
                $start_time=$this->request->data['AbandCallback']['start_time'];
                $end_time=$this->request->data['AbandCallback']['end_time'];
                $aband_status=$this->request->data['AbandCallback']['aband_status'];
                

                $client_data =$this->AbandCallTime->find('first',array('conditions'=>array('client_id'=>$client_id,'active'=>1)));

                // if(!empty($client_data))
                // {
                //     $this->Session->setFlash("Aband Time Already Set.");
                //     $this->redirect(array('controller'=>'AbandCallback'));

                // }else{

                $this->Session->setFlash("Aband Time Save Sucessfully");
                $this->AbandCallTime->save(array('client_id'=>$client_id,'start_time'=>$start_time,'end_time'=>$end_time,'aband_status'=>$aband_status,'created_at'=>date('Y-m-d H:i:s'),'created_by'=>$admin_id));

                $this->redirect(array('controller'=>'AbandCallback'));

                //}
               
            }

        }

        public function delete_aband()
        {

            $id=$this->request->query['id'];

            $dataArr['active'] = "'0'";

            $save = $this->AbandCallTime->updateAll($dataArr,array('id'=>$id));
            $this->redirect(array('controller'=>'AbandCallback','action'=>'index'));
        }
            
         
}
?>