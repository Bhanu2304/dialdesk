<?php
class AdminToolPlansController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','PlanMasterUpdateHistory','PlanMaster','PlanMasters','Waiver','BalanceMaster','PlanBalanceMaster','TmpRegistrationMaster','PlanToolMaster','BalanceToolMaster');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
	    $this->Auth->allow('client_wise_plan_creation',
                'view_pending_toolplan',
                'edit_tool_plan',
                'client_wise_plan_update',
                'plan_for_approval',
                'edit_plan_forapprove',
                'plan_approved',
                'view_client_plan',
                'update_client_plan',
                'get_client_name',
                'allocate_toolplan',
                'reallocate_toolplan',
                'get_plan_type',
                'send_email');
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
                    $this->redirect(array('controller' => 'AdminPlans', 'action' => 'view_pending_plan', '?' => array('id' =>$_REQUEST['id'])));	
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
                $this->redirect(array('controller' => 'AdminPlans', 'action' => 'view_pending_plan', '?' => array('id' =>$data['clientID'])));	
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
                    $data['PlanType'] = "Prepaid";
                    if(!empty($data['dialdeecheck']))
                    {
                        $plancreate = 'Dialdee';

                    }elseif(!empty($data['vfocheck']))
                    {
                        $plancreate = 'Vfo';
                    }elseif(!empty($data['leadsquarecheck']))
                    {
                        $plancreate = 'Leadsquared';
                    }
                    $data['PlanCreate'] = $plancreate;

                    $this->PlanToolMaster->save($data);
                    $this->Session->setFlash('Plan Create Successfully.');
                    $this->redirect(array('controller' => 'AdminToolPlans', 'action' => 'view_pending_toolplan'));
                }
            }
	}

    public function view_pending_toolplan() 
    {
        $this->layout='user';

        if($plan=$this->PlanToolMaster->find('all',array("conditions"=>array("approve_status='0' || approve_status='2'"))))
        {
            $this->set('plans',$plan);

        }
        else
        {
            $this->set('plans',array());
        }
    }
        public function edit_tool_plan()
        {
            $id = $this->params->query['tool_id']; 
            $this->layout='user';
            // $plan = $this->PlanToolMaster->find('first',array('conditions'=>"id='$id'"));
            // print_r($plan);die;
            $this->set('plan',$this->PlanToolMaster->find('first',array('conditions'=>"id='$id'")));
            
        }
            public function client_wise_plan_update()
            {
             
                if($this->request->is('Post'))
                {
                    
                    $id =  $data['id']; 
                    
                    $fetch_data = $this->PlanToolMaster->find('first',array('conditions'=>"id='$id' "));
                    //print_r($data);die;
                    $insert_data = []; 
                        foreach ($fetch_data as $childArray) 
                        { 
                            foreach ($childArray as $key => $value) 
                            { 
                                
                            $insert_data[$key] = $value; 
                            } 
                        }
                   
                    $data = $this->request->data;

                        echo"<br>";

                    $data['approve_status'] = '0';
                    $id = $data['id'];
                    unset($data['id']);

                    $new_data = array();
                    foreach($data as $key=>$value)
                    {
                        $new_data[$key] = "'".addslashes($value)."'";
                    }


                    if(!empty($data))
                    {
                        $data['update_date'] = date('Y-m-d H:i:s');
                        $data['update_by'] = $this->Session->read('admin_id');
                        $this->PlanToolMaster->updateAll($new_data,array('id'=>$id));
                        $this->Session->setFlash('Plan Updated Successfully.');
                        $this->redirect(array('controller' => 'AdminToolPlans', 'action' => 'view_pending_toolplan'));
                    }
                }
            }

    public function plan_for_approval() 
    {
        $this->layout='user';

        if($plan=$this->PlanToolMaster->find('all',array("conditions"=>array("approve_status='0'"),'order'=>array('PlanName'=>'asc'))))
        {
            $this->set('plans',$plan);
            //$this->set('clientId',$data['clientID']);
        }
        else
        {
            $this->set('plans',array());
        }
    }
        public function edit_plan_forapprove()
        {
            $id = $this->params->query['tool_id'];
            //print_r($id);die;
            $this->layout='user';
            $this->set('plan',$this->PlanToolMaster->find('first',array('conditions'=>"id='$id' and approve_status!='1'")));
            
        }
            public function plan_approved()
            {
                if($this->request->is('Post'))
                {
                    $data = $this->request->data;
                    if($data['submit']=='Approve')
                    {
                        $data['approve_status'] = '1';
                        $data['approval_date'] = date('Y-m-d H:i:s');
                        $this->Session->setFlash('Plan Approved Successfully.');
                    }
                    else
                    {
                        $data['approve_status'] = '2';
                        $this->Session->setFlash('Plan Rejected Successfully.');
                    }
                    $id = $data['id'];
                    unset($data['id']);
                    unset($data['dialdeecheck']);
                    unset($data['submit']);
                    $new_data = array();
                    foreach($data as $key=>$value)
                    {
                        
                        if($key=='reject_remarks')
                        {
                            $new_data[$key] = "'".addslashes($value)."'";
                        }
                        else
                        {
                            $new_data[$key] = "'".addslashes($value)."'";
                        }
                    }


                    if(!empty($data))
                    {
                        $data['update_date'] = date('Y-m-d H:i:s');
                        $data['update_by'] = $this->Session->read('admin_id');
                        $this->PlanToolMaster->updateAll($new_data,array('id'=>$id));
                        
                        $this->redirect(array('controller' => 'AdminToolPlans', 'action' => 'plan_for_approval'));
                    }
                }
            }
    
    
        public function get_plan_type(){
            if(isset($_REQUEST['planid']) && $_REQUEST['planid'] !=""){
            $planArr=$this->PlanToolMaster->find('first',array('fields'=>array('PlanType'),'conditions'=>array('Id'=>$_REQUEST['planid'])));
                echo $planArr['PlanMaster']['PlanType'];
            }die;
        }

        
        
        public function view_client_plan() 
        {
            $this->layout='user';
            
            if($plan=$this->PlanToolMaster->find('all',array('order'=>array('PlanName'=>'asc'))))
            {
                $this->set('plans',$plan);
                $this->set('clientId',$data['clientID']);
            }
            else
            {
                $this->set('plans',array());
            }
	}
	
        
        
        public function allocate_toolplan()
        {
            $this->layout="user";
            if($this->request->is('POST'))
            {
                $userid = $this->Session->read('admin_id');
                $data = $this->request->data['AdminToolPlan'];
                $clientId = $data['clientId'];
                $planId = $data['PlanId'];
                // $planType = $data['PlanType'];

                // $planType = "Prepaid";
                $start_date = $data['start_date'];
                $start_date = date_format(date_create($start_date),'Y-m-d');
                $end_date = date('Y-m-d',strtotime($start_date .' + 1 years'));
                $end_date = date('Y-m-d',strtotime($end_date .' - 1 days'));
                
                //print_r($data); exit;
            
                if($this->BalanceToolMaster->find('first',array('fields'=>'clientId','conditions'=>array('clientId'=>$clientId))))
                {
                    $this->Session->setFlash("Plan Tool Already Mapped");
                }
                else
                {
                   
                    if($PlanMaster = $this->PlanToolMaster->find('first',array('conditions'=>array('Id'=>$planId))))
                    {
                        $data = array();
                        //$waiver['clientId'] = 
                        $data['clientId'] = $clientId;
                        $data['ToolId'] = $planId;
                        $data['ToolType'] = $PlanMaster['PlanToolMaster']['PlanCreate'];
                        
                        $data['start_date'] = $start_date;
                        $data['end_date'] = $end_date;
                        
                        $data['userid'] = $this->Session->read("admin_id");
                        $data['createdate'] = date('Y-m-d H:i:s');
                        
                        //$waiver['createdate'] = $data['createdate'] = date('Y-m-d H:i:s');
                        
                        //print_r($data); exit;
                        
                        if($this->BalanceToolMaster->save($data))
                        {
                                                                             
                            
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

           
            $this->set('PlanName',$this->PlanToolMaster->find('list',array('fields'=>array('Id','PlanName'),'order'=>array('PlanName'=>'asc'),'conditions'=>array('approve_status'=>'1'))));

            $this->set('ClientName',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc'))));
            $this->set('plan_det',$this->RegistrationMaster->query("SELECT *,date_format(start_date,'%d-%b-%Y') start_date,date_format(end_date,'%d-%b-%Y') end_date FROM balance_tool_master bm 
            INNER JOIN plan_tool_master pm ON bm.ToolId = pm.id 
            INNER JOIN registration_master rm ON bm.clientId = rm.company_id order by company_name"));
            
            
        }
        
        public function reallocate_toolplan()
        {
            if($this->request->is('POST'))
            {
                //print_r($this->request->data); exit;
                $userid = $this->Session->read('admin_id');
                $data = $this->request->data['AdminToolPlan'];
                $clientId = $data['clientId'];
                $planId = $data['PlanId'];
                $planType = $data['PlanType'];
                $start_date = $data['start_date'];
                $start_date = date_format(date_create($start_date),'Y-m-d');
                $end_date = date('Y-m-d',strtotime($start_date .' + 1 years'));
                $end_date = date('Y-m-d',strtotime($end_date .' - 1 days'));
                //print_r($data); exit;
                
                
                if($PlanMaster = $this->PlanToolMaster->find('first',array('conditions'=>array('Id'=>$planId))))
                {
                    $data = array();
                    $balance_mas = $this->BalanceToolMaster->find('first',array('conditions'=>"clientId='$clientId'"));
                    if(!empty($balance_mas))
                    {
                        
                        $data['ToolId'] = "'".$planId."'";
                        // $data['Balance'] = "'".$PlanMaster['PlanMaster']['Balance']."'";
                        // $data['MainBalance'] = "'".$PlanMaster['PlanMaster']['Balance']."'";
                        $data['ToolType'] = "'".$PlanMaster['PlanToolMaster']['PlanCreate']."'";
                        $data['start_date'] = "'".$start_date."'";
                        $data['end_date'] = "'".$end_date."'";
                        $data['userid'] = "'".$this->Session->read("admin_id")."'";

                        $data['update_date'] = "'".date('Y-m-d H:i:s')."'";

                        //print_r($data); exit;

                        if($this->BalanceToolMaster->updateAll($data,array('clientId'=>$clientId)))
                        {
                            //$this->PlanToolMaster->query("INSERT INTO `history_plan_master`(planId,clientId,user_id,createdate)VALUE('$planId','$clientId','$userid',NOW());");
                            //$this->Waiver->save($waiver);
                            $this->Session->setFlash("Plan Re-Allocated To Client");
                        }
                        else
                        {
                            $this->Session->setFlash("Plan Not Re-Allocated to Client");
                        }
                    }
                    else
                    {
                        $waiver['clientId'] = $data['clientId'] = $clientId;
                    
                        $data['ToolId'] = $planId;
                        // $data['Balance'] = $PlanMaster['PlanMaster']['Balance'];
                        // $data['MainBalance'] = $PlanMaster['PlanMaster']['Balance'];
                        $data['ToolType'] = "'".$PlanMaster['PlanToolMaster']['PlanCreate']."'";
                        $data['start_date'] = $start_date;
                        $data['end_date'] = $end_date;
                        $data['userid'] = $this->Session->read("admin_id");

                        $waiver['createdate'] = $data['createdate'] = date('Y-m-d H:i:s');

                        //print_r($data); exit;

                        if($this->BalanceToolMaster->save($data))
                        {
                            //$this->PlanMaster->query("INSERT INTO `history_plan_master`(planId,clientId,user_id,createdate)VALUE('$planId','$clientId','$userid',NOW());");
                            //$this->Waiver->save($waiver);
                            $this->Session->setFlash("Plan Re-Allocated To Client");
                        }
                        else
                        {
                            $this->Session->setFlash("Plan Not Re-Allocated to Client");
                        }
                    }
                    
                    
                }
                else 
                {
                    $this->Session->setFlash("Plan Not Exists");
                }
                
            }
            $this->layout="user";
            $this->set('PlanName',$this->PlanToolMaster->find('list',array('fields'=>array('Id','PlanName'),'order'=>array('PlanName'=>'asc'),'conditions'=>array('approve_status'=>'1'))));
            $this->set('ClientName',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>"status='A' and company_id in (select clientid from balance_tool_master where end_date is not null and end_date>curdate())",'order'=>array('Company_name'=>'asc'))));
            
            
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