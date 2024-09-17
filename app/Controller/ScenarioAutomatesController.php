<?php
class ScenarioAutomatesController extends AppController{

        public $helpers = array('Html', 'Form','Js');
        public $components = array('RequestHandler');
        public $uses=array('RegistrationMaster','ScenarioAutomate');
	
        
        public function beforeFilter(){
            parent::beforeFilter();
            $this->Auth->allow();

        }

    
	    public function index() {
            $this->layout='user';
            if($this->Session->read('role') =="admin"){
                $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>1),'order'=>array('Company_name'=>'asc')));
                $this->set('client',$client);
                
                $scen_data = $this->ScenarioAutomate->find('all',array("conditions"=>"report_type='scenario'"));      
                $this->set('scen_data',$scen_data);
            }
                
            
            if($this->request->is("POST")){
        
                $ClientId = $this->Session->read('companyid');
                $client=$this->request->data['ScenarioAutomates']['client'];		
                $to=$this->request->data['ScenarioAutomates']['to'];
                $cc=$this->request->data['ScenarioAutomates']['cc'];
                $remarks=$this->request->data['ScenarioAutomates']['remarks'];
                $client_data =$this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$client)));
                $client_name = $client_data['RegistrationMaster']['company_name'];
            
                $this->Session->setFlash("Added Sucessfully");
                $this->ScenarioAutomate->save(array('report_type'=>'scenario','client'=>$client,'to'=>$to,'cc'=>$cc,'remarks'=>$remarks,'created_at'=>date('Y-m-d H:i:s'),'created_by'=>$ClientId,'client_name'=>$client_name));

                $this->redirect(array('controller'=>'ScenarioAutomates'));
               
            }

        }

        public function delete_alert(){
            $id=$this->request->query['id'];
            $this->ScenarioAutomate->deleteAll(array('id'=>$id));
            $this->redirect(array('controller'=>'ScenarioAutomates','action'=>'index'));
        }
        public function delete_alert2(){
            $id=$this->request->query['id'];
            $this->ScenarioAutomate->deleteAll(array('id'=>$id));
            $this->redirect(array('controller'=>'ScenarioAutomates','action'=>'call_summary_out'));
        }
        
        public function delete_alert3(){
            $id=$this->request->query['id'];
            $this->ScenarioAutomate->deleteAll(array('id'=>$id));
            $this->redirect(array('controller'=>'ScenarioAutomates','action'=>'call_summary_in'));
        }
        
        public function call_summary_out() {
            $this->layout='user';
            if($this->Session->read('role') =="admin"){
                $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
                $this->set('client',$client);
                
                $scen_data = $this->ScenarioAutomate->find('all',array("conditions"=>"report_type='call_summary'"));      
                $this->set('scen_data',$scen_data);
            }
                
            
            if($this->request->is("POST")){
        
                $ClientId = $this->Session->read('companyid');
                $client=$this->request->data['ScenarioAutomates']['client'];		
                $to=$this->request->data['ScenarioAutomates']['to'];
                $cc=$this->request->data['ScenarioAutomates']['cc'];
                $remarks=$this->request->data['ScenarioAutomates']['remarks'];
                $client_data =$this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$client)));
                $client_name = $client_data['RegistrationMaster']['company_name'];
            
                $this->Session->setFlash("Added Sucessfully");
                $this->ScenarioAutomate->save(array('report_type'=>'call_summary',
                    'client'=>$client,'to'=>$to,'cc'=>$cc,'remarks'=>$remarks,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'created_by'=>$ClientId,
                    'client_name'=>$client_name));

                $this->redirect(array('controller'=>'ScenarioAutomates','action'=>'call_summary_out'));
               
            }

        }

        public function call_summary_in() {
            $this->layout='user';
            if($this->Session->read('role') =="admin"){
                $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
                $this->set('client',$client);
                
                $scen_data = $this->ScenarioAutomate->find('all',array("conditions"=>"report_type='call_summary_in'"));      
                $this->set('scen_data',$scen_data);
            }
                
            
            if($this->request->is("POST")){
        
                $ClientId = $this->Session->read('companyid');
                $client=$this->request->data['ScenarioAutomates']['client'];		
                $to=$this->request->data['ScenarioAutomates']['to'];
                $cc=$this->request->data['ScenarioAutomates']['cc'];
                $remarks=$this->request->data['ScenarioAutomates']['remarks'];
                $client_data =$this->RegistrationMaster->find('first',array('conditions'=>array('company_id'=>$client)));
                $client_name = $client_data['RegistrationMaster']['company_name'];
            
                $this->Session->setFlash("Added Sucessfully");
                $this->ScenarioAutomate->save(array('report_type'=>'call_summary_in',
                    'client'=>$client,'to'=>$to,'cc'=>$cc,'remarks'=>$remarks,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'created_by'=>$ClientId,
                    'client_name'=>$client_name));

                $this->redirect(array('controller'=>'ScenarioAutomates','action'=>'call_summary_in'));
               
            }

        }
            
         
            
              
                
		
        
        
 
        
        
        
        

	
	
        
        
       
	

//        public function alert() 
//        {
//            $this->layout='user';
//    
//    
//            $sim_det = $this->AlertMechanism->find('all');      
//            $this->set('sim_det',$sim_det);
//                    
//                if($this->request->is('Post'))
//                {
//    
//                    //print_r($this->request->data);die;
//                    if(!empty($this->request->data))
//                    {  
//                        $data = array();
//                        $sim = $this->request->data;
//                        $sim = $sim['Dashboards'];
//                        $type = $sim['type'];
//                        
//                        $exist_sim = $this->AlertMechanism->find('first',array('conditions'=>array('type'=>$type)));
//                        //print_r($exist_sim);die;
//                            foreach($sim as $k=>$v)
//                            {
//                                if($exist_sim)
//                                {
//                                    $data[$k] = "'".addslashes($v)."'";
//                                }
//                                else
//                                {
//                                    $data[$k] = addslashes($v);
//                                }
//                            }
//                                if($exist_sim)
//                                {
//                                  $data['updated_at'] = "'".date('Y-m-d H:i:s')."'";
//                                }
//                                else
//                                {
//                                    $data['created_at'] = date('Y-m-d H:i:s');
//                                }
//                               
//                            if($exist_sim)
//                            {
//                                $this->AlertMechanism->updateAll($data,array('id'=>$exist_sim['AlertMechanism']['id']));
//                                $this->redirect(array('controller'=>'Dashboards'));
//                                $this->Session->setFlash('Alert Updated Successfully');
//                            }
//                            else if($this->AlertMechanism->save($data))
//                            {
//                                $this->redirect(array('controller'=>'Dashboards'));
//                                $this->Session->setFlash('Alert Added Successfully');
//                            }
//                            else
//                            {
//                                $this->Session->setFlash('Alert Not Added, Please Try Again');
//                            }
//                    }
//                }
//        }
    
    
//        public function delete_alert(){
//            $id=$this->request->query['id'];
//            $this->AlertMechanism->deleteAll(array('id'=>$id));
//            $this->redirect(array('controller'=>'Dashboards','action'=>'alert'));
//        }
}
?>