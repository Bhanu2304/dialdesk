<?php
class AdminPlansController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','PlanMasterUpdateHistory','PlanMaster','Waiver','BalanceMaster',
            'PlanBalanceMaster','TmpRegistrationMaster');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
	$this->Auth->allow('client_wise_plan_creation',
                'view_pending_plan',
                'edit_plan',
                'client_wise_plan_update',
                'plan_for_approval',
                'edit_plan_forapprove',
                'plan_approved',
                'view_client_plan',
                'update_client_plan',
                'get_client_name',
                'allocate_plan',
                'save_plan_allocation', //from on page to all
                'reallocate_plan',
                'get_plan_type',
                'send_email',
            'index1',
        'save_plan_re_allocation');
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

    public function index1() 
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

                //print_r($this->request->data);die;
                $data = $this->request->data;

		//print_r($data); die();
                
                if(!empty($data))
                {
                    $data['createdate'] = date('Y-m-d H:i:s');
                    $data['userid'] = $this->Session->read('admin_id');
                    $data['PlanType'] = "Prepaid";

                    $this->PlanMaster->save($data);
                    $this->Session->setFlash('Plan Create Successfully.');
                    $this->redirect(array('controller' => 'AdminPlans', 'action' => 'view_pending_plan'));
                }
            }
	}
    public function view_pending_plan() 
    {
        $this->layout='user';

        if($plan=$this->PlanMaster->find('all',array("conditions"=>array("approve_status='0' || approve_status='2'"),'order'=>array('PlanName'=>'asc'))))
        {
            $this->set('plans',$plan);
            //$this->set('clientId',$data['clientID']);
        }
        else
        {
            $this->set('plans',array());
        }
    }
        public function edit_plan()
        {
            $id = $this->params->query['plan_id']; 
            $this->layout='user';
            $this->set('plan',$this->PlanMaster->find('first',array('conditions'=>"id='$id'")));
            
        }
            public function client_wise_plan_update()
             {
                $this->Session->setFlash('Plan will not change.');
                $this->redirect(array('controller' => 'AdminPlans', 'action' => 'view_client_plan'));
                exit;
                if($this->request->is('Post'))
                {
                    
                    $data = $this->request->data;
                   
                    $id =  $data['id']; 
                    
                    $fetch_data = $this->PlanMaster->find('first',array('conditions'=>"id='$id' "));


                    $insert_data = []; 
                        foreach ($fetch_data as $childArray) 
                        { 
                            foreach ($childArray as $key => $value) 
                            { 
                            $insert_data[$key] = $value; 
                            } 
                        }


                   //print_r($insert_data); die();

                   // insert record

                   if(!empty($insert_data))
                   {
                       $insert_data['createdate'] = date('Y-m-d H:i:s');
                       $insert_data['userid'] = $this->Session->read('admin_id');
                       $insert_data['PlanId'] = $data['id']; 
                       $insert_data['Id'] = ''; 
                       $this->PlanMasterUpdateHistory->save($insert_data);
                       
                   }

                   
                   
                   
                   
                   // update
                   
                   
                    $data = $this->request->data;

                        echo"<br>";
        
               //print_r($data); die();                   
                       

                    $data['approve_status'] = '0';
                    $id = $data['id'];
                    unset($data['id']);
                    $new_data = array();
                    foreach($data as $key=>$value)
                    {
                        $new_data[$key] = "'".$value."'";
                    }


                    if(!empty($data))
                    {
                        $data['update_date'] = date('Y-m-d H:i:s');
                        $data['update_by'] = $this->Session->read('admin_id');
                        $this->PlanMaster->updateAll($new_data,array('id'=>$id));
                        $this->Session->setFlash('Plan Updated Successfully.');
                        $this->redirect(array('controller' => 'AdminPlans', 'action' => 'view_pending_plan'));
                    }
                }
            }
	
    public function plan_for_approval() 
    {
        $this->layout='user';

        if($plan=$this->PlanMaster->find('all',array("conditions"=>array("approve_status='0'"),'order'=>array('PlanName'=>'asc'))))
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
            $id = $this->params->query['plan_id'];
            $this->layout='user';
            $this->set('plan',$this->PlanMaster->find('first',array('conditions'=>"id='$id' and approve_status!='1'")));
            
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
                            $new_data[$key] = "'".$value."'";
                        }
                    }


                    if(!empty($data))
                    {
                        $data['update_date'] = date('Y-m-d H:i:s');
                        $data['update_by'] = $this->Session->read('admin_id');
                        $this->PlanMaster->updateAll($new_data,array('id'=>$id));
                        
                        $this->redirect(array('controller' => 'AdminPlans', 'action' => 'plan_for_approval'));
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
            /*if($plan=$this->PlanMaster->find('all',array("conditions"=>"id in (SELECT PlanId FROM balance_master bm
INNER JOIN `registration_master` rm ON bm.clientId = rm.company_id WHERE rm.status='A')",'order'=>array('PlanName'=>'asc'))))*/
            if($plan=$this->PlanMaster->find('all',array('order'=>array('PlanName'=>'asc'))))
            {
                $this->set('plans',$plan);
                $this->set('clientId',$data['clientID']);
            }
            else
            {
                $this->set('plans',array());
            }
	}
	
        
        
        public function allocate_plan()
        {
            $this->layout="user";
            if($this->request->is('POST'))
            {
                //print_r($this->request->data['AdminPlan']); exit;
                $userid = $this->Session->read('admin_id');
                $data = $this->request->data['AdminPlan'];
                $clientId = $data['clientId'];
                $planId = $data['PlanId'];
                //$planType = $data['PlanType'];

                $planType = "Prepaid";
                //$start_date = $data['start_date'];
                //$start_date = date_format(date_create($start_date),'Y-m-d');
                //$end_date = date('Y-m-d',strtotime($start_date .' + 1 years'));
                //$end_date = date('Y-m-d',strtotime($end_date .' - 1 days'));
                
                //print_r($data); exit;
                if($this->BalanceMaster->find('first',array('fields'=>'clientId','conditions'=>array('clientId'=>$clientId))))
                {
                    $this->Session->setFlash("Plan Already Mapped");
                }
                else
                {
                    if($PlanMaster = $this->PlanMaster->find('first',array('conditions'=>array('Id'=>$planId))))
                    {
                        $data = array();
                        //$waiver['clientId'] = 
                        $data['clientId'] = $clientId;
                        $data['PlanId'] = $planId;
                        $data['Balance'] = $PlanMaster['PlanMaster']['Balance'];
                        $data['MainBalance'] = $PlanMaster['PlanMaster']['Balance'];
                        $data['PlanType'] = $planType;
                        
                        //$data['start_date'] = $start_date;
                        //$data['end_date'] = $end_date;
                        
                        $data['userid'] = $this->Session->read("admin_id");
                        
                        //$waiver['createdate'] = $data['createdate'] = date('Y-m-d H:i:s');
                        
                        //print_r($data); exit;
                        
                        if($this->BalanceMaster->save($data))
                        {
                            $this->PlanMaster->query("INSERT INTO `history_plan_master`(planId,clientId,user_id,createdate)VALUE('$planId','$clientId','$userid',NOW());");
                            $open_exist = $this->Waiver->query("select * from billing_opening_balance where clientid='$clientId' limit 1"); 
                            if(empty($open_exist))
                            {
                                $tstart_date = date('Y-m-d');
                                $tend_date = date('Y-m-t');
                                $year = date('Y');
                                $Nmonth = date('M');
                                $open_exist = $this->Waiver->query("insert into billing_opening_balance set clientid='$clientId',bill_start_date='$tstart_date',bill_end_date='$tend_date',fin_year='$year',fin_month='$Nmonth'"); 
                            }
                            //$this->Waiver->save($waiver);                                                        
                            
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
            $this->set('PlanName',$this->PlanMaster->find('list',array('fields'=>array('Id','PlanName'),'order'=>array('PlanName'=>'asc'))));
            $this->set('ClientName',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>"status='A' and company_id not in (select clientid from balance_master where end_date is not null and end_date>curdate())",'order'=>array('Company_name'=>'asc'))));
            $this->set('plan_det',$this->RegistrationMaster->query("SELECT *,date_format(start_date,'%d-%b-%Y') start_date,date_format(end_date,'%d-%b-%Y') end_date FROM balance_master bm 
INNER JOIN plan_master pm ON bm.PlanId = pm.id 
INNER JOIN registration_master rm ON bm.clientId = rm.company_id order by company_name"));
            
            
        }
        
        public function save_plan_allocation()
        {
            $this->layout='ajax';
            if($this->request->is('POST'))
            {
                #print_r($_REQUEST);die;
                #print_r($this->request->data['AdminPlan']); exit;
                $userid = $this->Session->read('admin_id');
                //$data = $this->request->data['AdminPlan'];
                $clientId = $_REQUEST['client'];
                $planId = $_REQUEST['plan_list'];
                $planType = $_REQUEST['PlanType'];

                //$planType = "Prepaid";
                //$start_date = $data['start_date'];
                //$start_date = date_format(date_create($start_date),'Y-m-d');
                //$end_date = date('Y-m-d',strtotime($start_date .' + 1 years'));
                //$end_date = date('Y-m-d',strtotime($end_date .' - 1 days'));
                
                //print_r($data); exit;
                if($this->BalanceMaster->find('first',array('fields'=>'clientId','conditions'=>array('clientId'=>$clientId))))
                {
                    #$this->Session->setFlash("Plan Already Mapped");
                    echo 'Plan Already Mapped';exit;
                }
                else
                {
                    if($PlanMaster = $this->PlanMaster->find('first',array('conditions'=>array('Id'=>$planId))))
                    {
                        $data = array();
                        //$waiver['clientId'] = 
                        $data['clientId'] = $clientId;
                        $data['PlanId'] = $planId;
                        $data['Balance'] = $PlanMaster['PlanMaster']['Balance'];
                        $data['MainBalance'] = $PlanMaster['PlanMaster']['Balance'];
                        $data['PlanType'] = $planType;
                        
                        //$data['start_date'] = $start_date;
                        //$data['end_date'] = $end_date;
                        
                        $data['userid'] = $this->Session->read("admin_id");
                        
                        //$waiver['createdate'] = $data['createdate'] = date('Y-m-d H:i:s');
                        
                        //print_r($data); exit;
                        
                        if($this->BalanceMaster->save($data))
                        {
                            $this->PlanMaster->query("INSERT INTO `history_plan_master`(planId,clientId,user_id,createdate)VALUE('$planId','$clientId','$userid',NOW());");
                            $open_exist = $this->Waiver->query("select * from billing_opening_balance where clientid='$clientId' limit 1"); 
                            if(empty($open_exist))
                            {
                                $tstart_date = date('Y-m-d');
                                $tend_date = date('Y-m-t');
                                $year = date('Y');
                                $Nmonth = date('M');
                                $open_exist = $this->Waiver->query("insert into billing_opening_balance set clientid='$clientId',bill_start_date='$tstart_date',bill_end_date='$tend_date',fin_year='$year',fin_month='$Nmonth'"); 
                            }
                            //$this->Waiver->save($waiver);                                                        
                            echo 'Plan Allocated To Client';exit;
                            #$this->Session->setFlash("Plan Allocated To Client");
                        }
                        else
                        {
                            #$this->Session->setFlash("Plan Not Allocated to Client");
                            echo 'Plan Not Allocated to Client. Please Contact to Admin.';exit;
                        }
                        
                    }
                    else 
                    {
                        echo 'Plan Not Exists';exit;
                        #$this->Session->setFlash("Plan Not Exists");
                    }
                }
                
            }
            exit;
        }
        
        public function reallocate_plan()
        {
            if($this->request->is('POST'))
            {
                //print_r($this->request->data['AdminPlan']); exit;
                $userid = $this->Session->read('admin_id');
                $data = $this->request->data['AdminPlan'];
                $clientId = $data['clientId'];
                $planId = $data['PlanId'];
                $planType = $data['PlanType'];
                $start_date = $data['start_date'];
                $start_date = date_format(date_create($start_date),'Y-m-d');
                $end_date = date('Y-m-d',strtotime($start_date .' + 1 years'));
                $end_date = date('Y-m-d',strtotime($end_date .' - 1 days'));
                //print_r($data); exit;
                
                
                    if($PlanMaster = $this->PlanMaster->find('first',array('conditions'=>array('Id'=>$planId))))
                    {
                        $data = array();
                        $balance_mas = $this->BalanceMaster->find('first',array('conditions'=>"clientId='$clientId'"));
                        if(!empty($balance_mas))
                        {
                            
                            $data['PlanId'] = "'".$planId."'";
                            $data['Balance'] = "'".$PlanMaster['PlanMaster']['Balance']."'";
                            $data['MainBalance'] = "'".$PlanMaster['PlanMaster']['Balance']."'";
                            $data['PlanType'] = "'".$planType."'";
                            //if(!empty($balance_mas['BalanceMaster']['start_date']))
                            //$data['start_date'] = "'".$start_date."'";
                            //$data['end_date'] = "'".$end_date."'";
                            $data['userid'] = "'".$this->Session->read("admin_id")."'";

                            $data['update_date'] = "'".date('Y-m-d H:i:s')."'";

                            //print_r($data); exit;

                            if($this->BalanceMaster->updateAll($data,array('clientId'=>$clientId)))
                            {
                                $qry_alloc_log = "INSERT INTO `billing_plan_alloc_log` SET client_id='$clientId',plan_id='$planId', start_date='$start_date',created_by='$userid',alloc_type='Re-Allocate-plan',created_at=NOW();";
                                $this->BalanceMaster->query($qry_alloc_log);
                                $this->PlanMaster->query("INSERT INTO `history_plan_master`(planId,clientId,user_id,createdate)VALUE('$planId','$clientId','$userid',NOW());");
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
                        
                            $data['PlanId'] = $planId;
                            $data['Balance'] = $PlanMaster['PlanMaster']['Balance'];
                            $data['MainBalance'] = $PlanMaster['PlanMaster']['Balance'];
                            $data['PlanType'] = $planType;
                            $data['start_date'] = $start_date;
                            $data['end_date'] = $end_date;
                            $data['userid'] = $this->Session->read("admin_id");

                            $waiver['createdate'] = $data['createdate'] = date('Y-m-d H:i:s');

                            //print_r($data); exit;

                            if($this->BalanceMaster->save($data))
                            {
                                $this->PlanMaster->query("INSERT INTO `history_plan_master`(planId,clientId,user_id,createdate)VALUE('$planId','$clientId','$userid',NOW());");
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
            $this->set('PlanName',$this->PlanMaster->find('list',array('fields'=>array('Id','PlanName'),'order'=>array('PlanName'=>'asc'))));
            $this->set('ClientName',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),'conditions'=>"status='A' and company_id in (select clientid from balance_master  )",'order'=>array('Company_name'=>'asc'))));
            
            
        }
	
        public function save_plan_re_allocation()
        {
            $this->layout='ajax';
            if($this->request->is('POST'))
            {
                #print_r($_REQUEST); exit;
                $userid = $this->Session->read('admin_id');
                #$data = $this->request->data['AdminPlan'];
                $clientId = $_REQUEST['client'];
                $planId = $_REQUEST['plan_list'];
                
                $start_date = $_REQUEST['start_date'];
                $start_date = date_format(date_create($start_date),'Y-m-d');
                $end_date = date('Y-m-d',strtotime($start_date .' + 1 years'));
                $end_date = date('Y-m-d',strtotime($end_date .' - 1 days'));
                //print_r($data); exit;
                
                
                    if($PlanMaster = $this->PlanMaster->find('first',array('conditions'=>array('Id'=>$planId))))
                    {
                        $data = array();
                        $balance_mas = $this->BalanceMaster->find('first',array('conditions'=>"clientId='$clientId'"));
                        if(!empty($balance_mas))
                        {
                            
                            $data['PlanId'] = "'".$planId."'";
                            $data['Balance'] = "'".$PlanMaster['PlanMaster']['Balance']."'";
                            $data['MainBalance'] = "'".$PlanMaster['PlanMaster']['Balance']."'";
                            //if(!empty($balance_mas['BalanceMaster']['start_date']))
                            //$data['start_date'] = "'".$start_date."'";
                            //$data['end_date'] = "'".$end_date."'";
                            $data['userid'] = "'".$this->Session->read("admin_id")."'";

                            $data['update_date'] = "'".date('Y-m-d H:i:s')."'";

                            //print_r($data); exit;

                            if($this->BalanceMaster->updateAll($data,array('clientId'=>$clientId)))
                            {
                                $qry_alloc_log = "INSERT INTO `billing_plan_alloc_log` SET client_id='$clientId',plan_id='$planId', start_date='$start_date',created_by='$userid',alloc_type='Re-Allocate-plan',created_at=NOW();";
                                $this->BalanceMaster->query($qry_alloc_log);
                                $this->PlanMaster->query("INSERT INTO `history_plan_master`(planId,clientId,user_id,createdate)VALUE('$planId','$clientId','$userid',NOW());");
                                //$this->Waiver->save($waiver);
                                echo "Plan Re-Allocated To Client";exit;
                                #$this->Session->setFlash("Plan Re-Allocated To Client");
                            }
                            else
                            {
                                echo "Plan Not Re-Allocated to Client. Contact to Admin";exit;
                                #$this->Session->setFlash("Plan Not Re-Allocated to Client");
                            }
                        }
                        else
                        {
                            $waiver['clientId'] = $data['clientId'] = $clientId;
                        
                            $data['PlanId'] = $planId;
                            $data['Balance'] = $PlanMaster['PlanMaster']['Balance'];
                            $data['MainBalance'] = $PlanMaster['PlanMaster']['Balance'];
                            $data['PlanType'] = $planType;
                            $data['start_date'] = $start_date;
                            $data['end_date'] = $end_date;
                            $data['userid'] = $this->Session->read("admin_id");

                            $waiver['createdate'] = $data['createdate'] = date('Y-m-d H:i:s');

                            //print_r($data); exit;

                            if($this->BalanceMaster->save($data))
                            {
                                $this->PlanMaster->query("INSERT INTO `history_plan_master`(planId,clientId,user_id,createdate)VALUE('$planId','$clientId','$userid',NOW());");
                                //$this->Waiver->save($waiver);
                                #$this->Session->setFlash("Plan Re-Allocated To Client");
                                echo "Plan Re-Allocated To Client";exit;
                            }
                            else
                            {
                                #$this->Session->setFlash("Plan Not Re-Allocated to Client");
                                echo "Plan Not Re-Allocated to Client. Contact to Admin";exit;
                            }
                        }
                        
                        
                    }
                    else 
                    {
                        #$this->Session->setFlash("Plan Not Exists");
                        echo "Plan Not Exists";exit;
                    }
                
                
            }
            exit;
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