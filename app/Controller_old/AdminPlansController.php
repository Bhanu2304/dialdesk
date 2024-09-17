<?php
class AdminPlansController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','PlanMaster','Waiver','BalanceMaster','TmpRegistrationMaster');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
	$this->Auth->allow('client_wise_plan_creation','view_client_plan','edit_plan','update_client_plan','get_client_name','allocate_plan','get_plan_type','send_email');
	if(!$this->Session->check("admin_id"))
        {
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
	}
    }

    public function index() 
    {
        $this->layout='user';
        $client = $this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
        $this->set('client',$client);
        $this->set('clientid',array());

        if(isset($_REQUEST['id']) && $_REQUEST['id'] !="")
        {
            if($this->PlanMaster->find('first',array('fields'=>array('id'),'conditions'=>array('clientID'=>$_REQUEST['id']))))
            {
                    $this->redirect(array('controller' => 'AdminPlans', 'action' => 'view_client_plan', '?' => array('id' =>$_REQUEST['id'])));	
            }
            else
            {
                $this->set('plan',array());
                $this->set('clientid',$_REQUEST['id']);
            }
        }

        if($this->request->is('Post'))
        {
            $data=$this->request->data['AdminPlans'];
            if($this->PlanMaster->find('first',array('fields'=>array('id'),'conditions'=>array('clientID'=>$data['clientID']))))
            {
                $this->redirect(array('controller' => 'AdminPlans', 'action' => 'view_client_plan', '?' => array('id' =>$data['clientID'])));	
            }
            else
            {
                $this->set('plan',array());
                $this->set('clientid',$data['clientID']);
            }
        }
    }
	
	public function client_wise_plan_creation()
        {
            if($this->request->is('Post'))
            {
                $data = $this->request->data;
                
                if(!empty($data))
                {
                    $data['createdate'] = date('Y-m-d H:i:s');
                    $data['userid'] = $this->Session->read('admin_id');
                    $this->PlanMaster->save($data);
                    $this->Session->setFlash('Plan Create Successfully.');
                    $this->redirect(array('controller' => 'AdminPlans', 'action' => 'view_client_plan'));
                }
            }
	}
	
        
        public function get_plan_type(){
            if(isset($_REQUEST['planid']) && $_REQUEST['planid'] !=""){
            $planArr=$this->PlanMaster->find('first',array('fields'=>array('PlanType'),'conditions'=>array('Id'=>$_REQUEST['planid'])));
                echo $planArr['PlanMaster']['PlanType'];
            }die;
        }

        public function view_client_plan() 
        {
            $this->layout='user';
            
            if($plan=$this->PlanMaster->find('all'))
            {
                $this->set('plans',$plan);
                $this->set('clientId',$data['clientID']);
            }
            else
            {
                $this->set('plans',array());
            }
	}
	
        public function edit_plan()
        {
            $id = $this->params->query['id'];
            $this->layout='user';
            $this->set('plan',$this->PlanMaster->find('first',array('conditions'=>array('id'=>$id))));
            
            if($this->request->is('Post'))
            {
                //print_r($this->request->data); exit;
                if(!empty($this->request->data['id']) && !empty($this->request->data['AdminPlans']))
                {
                    $id=$this->request->data['id'];
                    $data = $this->request->data['AdminPlans'];
                    $newBalance = $data['Balance'];
                    //print_r($newBalance); exit;
                    
                    $oldPlan = $this->PlanMaster->find('first',array('fields'=>array('Balance','clientId'),'conditions'=>array('id'=>$id)));
                    $clientId = $oldPlan['PlanMaster']['clientId'];
                    
                    //print_r($oldPlan); exit;
                    
                    $balance_master = $this->BalanceMaster->find('first',array('fields'=>array('Balance'),'conditions'=>array('clientId'=>$clientId)));
                    //print_r($balance_master); exit;
                    $BalanceMaster['Balance'] =$balance_master['BalanceMaster']['Balance'] + ($newBalance - $oldPlan['PlanMaster']['Balance']);
                    //print_r($BalanceMaster); exit;
                    
                    foreach($data as $k=>$v)
                    {
                        $dataY[$k] = "'".addslashes($v)."'";
                    }
                    
                    //$clientId = $this->PlanMaster->find('first',array('fields'=>array('clientId'),'conditions'=>array('id'=>$id)));
                    
                    //print_r($clientId); exit;
                    if($this->PlanMaster->updateAll($dataY,array('id'=>$id)))
                    {
                        $this->BalanceMaster->updateAll($BalanceMaster,array('clientId'=>$clientId));
                        unset($this->request->data);unset($data);unset($dataY);unset($k);unset($v);
                        $this->Session->setFlash('Plan Updated Successfully.');
                        $this->redirect(array('controller'=>'AdminPlans','action'=>'view_client_plan','?'=>array('id'=>$clientId)));
                    }
                    else
                    {
                        unset($this->request->data);unset($data);unset($dataY);unset($k);unset($v);
                        $this->Session->setFlash('Plan Not Updated.');
                        $this->redirect(array('controller'=>'AdminPlans','action'=>'view_client_plan','?'=>array('id'=>$clientId)));
                    }
                } 
            }
        }
        
        public function allocate_plan()
        {
            $this->layout="user";
            $this->set('PlanName',$this->PlanMaster->find('list',array('fields'=>array('Id','PlanName'),'Order'=>array('PlanName'=>'Asc'))));
            $this->set('ClientName',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'Order'=>array('company_name'=>'Asc'))));
            
            if($this->request->is('POST'))
            {
                //print_r($this->request->data['AdminPlan']); exit;
                $userid = $this->Session->read('admin_id');
                $data = $this->request->data['AdminPlan'];
                $clientId = $data['clientId'];
                $planId = $data['PlanId'];
                $planType = $data['PlanType'];
                
                //print_r($data); exit;
                if($this->BalanceMaster->find('first',array('fields'=>'clientId','conditions'=>array('clientId'=>$clientId))))
                {
                    $this->Session->setFlash("Plan Already Exists");
                }
                else
                {
                    if($PlanMaster = $this->PlanMaster->find('first',array('conditions'=>array('Id'=>$planId))))
                    {
                        $data = array();
                        $waiver['clientId'] = $data['clientId'] = $clientId;
                        
                        $data['PlanId'] = $planId;
                        $data['Balance'] = $PlanMaster['PlanMaster']['Balance'];
                        $data['MainBalance'] = $PlanMaster['PlanMaster']['Balance'];
                        $data['PlanType'] = $planType;
                        $data['userid'] = $this->Session->read("admin_id");
                        $waiver['createdate'] = $data['createdate'] = date('Y-m-d H:i:s');
                        
                        //print_r($data); exit;
                        
                        if($this->BalanceMaster->save($data))
                        {
                            $this->PlanMaster->query("INSERT INTO `history_plan_master`(planId,clientId,user_id,createdate)VALUE('$planId','$clientId','$userid',NOW());");
                            $this->Waiver->save($waiver);
                            $this->Session->setFlash("Plan Allocated To Client");
                        }
                        else
                        {
                            $this->Session->setFlash("Plan Not Allocated to Client");
                        }
                        
                    }
                    else 
                    {
                        $this->Session->setFlash("Plan Not Exists");
                    }
                }
                
            }
        }
	
	
	public function get_client_name($id){
		$client =$this->RegistrationMaster->find('first',array('fields'=>array("Company_name"),'conditions'=>array('company_id'=>$id)));
		return $client['RegistrationMaster']['Company_name'];
	}
	 public function send_email()
    {
        
        

				//$this->TmpRegistrationMaster->deleteAll(array('company_id'=>$companyid),false);
				//$this->Session->delete('tmpid');
				//$this->Session->delete('verify_no');
   				//$this->Session->destroy();
				
				$base = Router::fullbaseUrl().$this->webroot."ClientActivations/verify_email?ver=";
				$company_id = base64_encode(289);
				$name="Manoj";
				$user_email = "manoj.kt77@gmail.com";
				
				
                                $EmailText='';
				$to=array('Email'=>$user_email,'Name'=>$user_email);
                                $AddTo = array('krishna.kumar1@teammas.in');
				$from=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
				$reply=array('Email'=>"ispark@teammas.in",'Name'=>'Mas Callnet');
				$Sub="Dialdesk: Verify your account.";
				$EmailText.="Dear $name,<br/><br/>";
				$EmailText.="To verify your account, please click on following link.If your browser does not open it, please copy and paste it in your browser's address bar.<br/><br/>";
                                 $EmailText.="<a href='$base$company_id' style='text-decoration:none;'><button style='cursor:pointer;' >Verify Account</button></a><br/><br/>";
				
                                require_once(APP . 'Lib' . DS . 'send_email' . DS . 'function.php');
                                
				$emaildata=array('ReceiverEmail'=>$to,'AddTo'=>$AddTo,'SenderEmail'=>$from,'ReplyEmail'=>$reply,'Subject'=>$Sub,'EmailText'=>$EmailText);
                                send_email($emaildata); exit;
				
    }	
}
?>