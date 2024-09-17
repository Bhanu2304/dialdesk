<?php
class ClientAccountsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','Waiver','PlanMaster','BalanceMaster');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
	$this->Auth->allow('client_wise_account_creation','view_client_account','edit_account','add_account','get_client_name',
                'add_start_date','save_start_date'
                );
	if(!$this->Session->check("admin_id"))
        {
            return $this->redirect(array('controller'=>'ClientAccounts','action' => 'index'));
	}
    }

    public function index() 
    {
        $this->layout='user';
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
        $this->set('client',$client);
        $this->set('clientid',array());

        if(isset($_REQUEST['id']) && $_REQUEST['id'] !="")
        {
            if($this->Account->find('first',array('fields'=>array('id'),'conditions'=>array('clientId'=>$_REQUEST['id']))))
            {
                    $this->redirect(array('controller' => 'ClientAccounts', 'action' => 'view_client_account', '?' => array('id' =>$_REQUEST['id'])));	
            }
            else
            {
                $this->set('account',array());
                $this->set('clientid',$_REQUEST['id']);
            }
        }

        if($this->request->is('Post'))
        {
            $data=$this->request->data['ClientAccounts'];
            
            if($this->BalanceMaster->find('first',array('fields'=>array('id'),'conditions'=>array('clientId'=>$data['clientID']))))
            {
                $this->redirect(array('controller' => 'ClientAccounts', 'action' => 'view_client_account', '?' => array('id' =>$data['clientID'])));	
            }
            else
            {
                $this->set('account',array());
                $this->set('clientid',$data['clientID']);
            }
        }
    }
	
	public function client_wise_account_creation()
        {
		if($this->request->is('Post'))
                {
			$data=$this->request->data['ClientAccounts']; //client new account creation
                        $account=$this->request->data['Free']; //client free account
                        $client_account = array(); //client account balance
                        //print_r($account); exit;
			//$data['clientName'] = $this->get_client_name($data['clientID']);
                        $client_account['clientId'] = $account['clientId']=$data['clientId'] = $data['clientID'];   //for saving client account
			$client_account['createdate'] = $account['createdate']= $data['createdate'] = date('Y-m-d H:i:s'); //same createdate for all
                        $client_account['FreeValueBalance'] = $data['FreeValue'];
                        $client_account['FreeInboundBalance'] = $account['InboundCall'];
                        $client_account['FreeOutboundBalance'] = $account['OutboundCall'];
                        $client_account['FreeMissCallBalance'] = $account['MissCall'];
                        $client_account['FreeVFOBalance'] = $account['VFOCall'];
                        $client_account['FreeSMSBalance'] = $account['SMS'];
                        $client_account['FreeEmailBalance'] = $account['Email'];
                        
			if($this->BalanceMaster->find('first',array('fields'=>array('id'),'conditions'=>array('clientID'=>$data['clientID']))))
                        {
                            $this->Session->setFlash('Account allready Created.');
                            $this->redirect(array('controller' => 'ClientAccounts', 'action' => 'view_client_account', '?' => array('id' =>$data['clientID'])));	
			}
                        else if($this->BalanceMaster->find('first',array('fields'=>array('id'),'conditions'=>array('clientID'=>$data['clientID']))))
                        {
                            $this->Session->setFlash('Account Account Already Exist! Delete if First');
                            $this->redirect(array('controller' => 'ClientAccounts', 'action' => 'view_client_account', '?' => array('id' =>$data['clientID'])));	
                        }
                        
			else
                        {
                            $this->BalanceMaster->save($data);
                            $AccountId = $this->BalanceMaster->getInsertId();
                            $client_account['AccountId'] = $account['AccountId']=$AccountId;
                            $this->Account->save($account);
                            //$client_account = array();
                            $client_account['clientId'] = $account['clientId'];

                            $this->BalanceMaster->save($client_account);
                            //print_r($client_account); exit;

                            $this->Session->setFlash('Account Create Successfully.');
                            $this->redirect(array('controller' => 'ClientAccounts', 'action' => 'view_client_account', '?' => array('id' =>$data['clientID'])));
                        }
		}
	}
	
	public function view_client_account() 
        {
		$this->layout='user';
		$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>"status='A'",'order'=>"RegistrationMaster.company_name"));
		$this->set('client',$client);
		if($this->request->is('Post'))
                {
			$data=$this->request->data['ClientAccounts'];
                       //print_r($this->request->data); exit;
			if($account=$this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>$data['clientID']))))
                        {
                            $this->set('account',$account);
                            
			}
			else
                        {
                            $this->set('account',array());
			}
                        $this->set('clientId',$data['clientId']);
		}
                 
		if(isset($_REQUEST['id']) && $_REQUEST['id'] !="")
                {
                    
                    if($account=$this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>$_REQUEST['id']))))
                    {
                        $this->set('account',$account);
                    }
                    else
                    {
                        $this->set('account',array());
                    }
                    $this->set('clientId',$_REQUEST['id']);
		}	
	}
	
        public function edit_account()
        {
            $id = $this->params->query['id'];
            $this->layout='user';
            $account = $this->BalanceMaster->find('first',array('conditions'=>array('id'=>$id)));
            //print_r($account);
            if(empty($account['ClientAccount']['RemainingRentalDays']))
            {
              $plan =   $this->PlanMaster->find('first',array('fields'=>array('PeriodType','RentalPeriod'),'conditions'=>array('clientId'=>$account['BalanceMaster']['clientId'])));
              //print_r($plan); exit;
              if(!empty($plan))
              {
                  $account['ClientAccount']['RemainingRentalDays'] = $plan['PlanMaster']['RentalPeriod'].' '.$plan['PlanMaster']['PeriodType'];
              }
            }
            $this->set('account',$account);
            
            if($this->request->is('Post'))
            {
                //print_r($this->request->data); exit;
                $clientId = $this->BalanceMaster->find('first',array('fields'=>array('clientId'),'conditions'=>array('id'=>$id)));
                $clientId = $clientId['BalanceMaster']['clientId'];
                
                $planMaster = $this->PlanMaster->find('first',array('conditions'=>array('clientId'=>$clientId)));
                $periodType = $planMaster['PlanMaster']['PeriodType'];
                $rentalPeriod = $planMaster['PlanMaster']['RentalPeriod'];
                
                if(!empty($this->request->data['id']) && !empty($this->request->data['ClientAccounts']))
                {
                    $id=$this->request->data['id'];
                    $data = $this->request->data['ClientAccounts'];
                    
                    if(!empty($data['start_date']))
                    {
                        $date = date_create($data['start_date']);
                        $data['start_date']= date_format($date, 'Y-m-d');
                        //echo "UPDATE balance_master SET end_date = DATE_ADD(start_date,INTERVAL $rentalPeriod $periodType ) where clientId='$clientId';"; exit;
                        $this->BalanceMaster->query("UPDATE balance_master SET end_date = DATE_ADD(start_date,INTERVAL $rentalPeriod $periodType ) where clientId='$clientId';");
                    }
                    else
                    {
                        unset($data['start_date']);
                    }
                    
                    
                    foreach($data as $k=>$v)
                    {
                        $dataY[$k] = "'".addslashes($v)."'";
                    }
                    
                    
                    //print_r($clientId); exit;
                    if($this->BalanceMaster->updateAll($dataY,array('id'=>$id)))
                    {
                        unset($this->request->data);unset($data);unset($dataY);unset($k);unset($v);
                        $this->Session->setFlash('Account Updated Successfully.');
                        $this->redirect(array('controller'=>'ClientAccounts','action'=>'view_client_account','?'=>array('id'=>$clientId)));
                    }
                    else
                    {
                        unset($this->request->data);unset($data);unset($dataY);unset($k);unset($v);
                        $this->Session->setFlash('Account Not Updated.');
                        $this->redirect(array('controller'=>'ClientAccounts','action'=>'view_client_account','?'=>array('id'=>$clientId)));
                    }
                } 
            }
        }
        
        public function add_start_date()
        {
            $this->layout="user";
            
            
            
            $userid = $this->Session->read('admin_id');
            
            if($this->request->is('POST'))
            {
                //print_r($this->request->data['ClientAccounts']); exit;
                $data = $this->request->data['ClientAccounts'];
                $clientId = $data['clientId'];
                $start_date = $data['start_date'];
                
                /*if($this->BalanceMaster->find('first',array('fields'=>'clientId','conditions'=>array('clientId'=>$clientId,'not'=>array('start_date'=>null)))))
                {
                    $this->Session->setFlash("Activation Date Already Exists");
                }
                else
                {*/
                    if(!empty($start_date) && !empty($clientId))
                    {
                        //$data = array();
                        $start_date = $data['start_date'];
                        $start_date = date_format(date_create($start_date),'Y-m-d');
                        $end_date = date('Y-m-d',strtotime($start_date .' + 1 years'));
                        $end_date = date('Y-m-d',strtotime($end_date .' - 1 days'));
                        
                        //$data['start_date'] = $start_date;
                        $plan_det = $this->BalanceMaster->find('first',array('fields'=>array('PlanId','start_date'),'conditions'=>array('clientId'=>$clientId)));
                       if(!empty($plan_det))
                       {    
                            $dataN['activation_date'] = "'".date('Y-m-d H:i:s')."'";
                            $dataN['start_date'] = "'".$start_date."'";
                            $dataN['end_date'] = "'".$end_date."'";
                            //print_r($dataN);exit;
                            $allocation_type = 'activation';
                            $plan_id = $plan_det['BalanceMaster']['PlanId'];
                            if(!empty($plan_det['BalanceMaster']['start_date']))
                            {
                                $allocation_type = 're-activation';
                            }
                            
                            if($this->BalanceMaster->updateAll($dataN,array('clientId'=>$clientId)))
                            {
                                $qry_alloc_log = "INSERT INTO `billing_plan_alloc_log` SET client_id='$clientId',plan_id='$plan_id', start_date='$start_date',created_by='$userid',alloc_type='$allocation_type',created_at=NOW();";
                                $this->BalanceMaster->query($qry_alloc_log);
                                $this->Session->setFlash("Activation Date has been Saved.");
                            }
                            else
                            {
                                $this->Session->setFlash("Please Try Again");
                            }
                       }
                       else
                       {
                           $this->Session->setFlash("Plan Not Allocated To Client");
                       }
                    }
                    else 
                    {
                        $this->Session->setFlash("Activation Date Is Empty");
                    }
                }  
           // }
            
            
            $this->set('ClientName',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),
                'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc'))));
            
            $activation_list = $this->BalanceMaster->query("SELECT company_name,date_format(start_date,'%d-%M-%Y') activation_date FROM registration_master rm 
INNER JOIN balance_master bm ON rm.company_id = bm.clientId
WHERE `status` = 'A' ORDER BY company_name");
            $this->set('activation_list',$activation_list);
        }
        
        public function save_start_date()
        {
            $this->layout='ajax';
            $userid = $this->Session->read('admin_id');
            if($this->request->is('POST'))
            {
                #print_r($_REQUEST); exit;
                #$data = $this->request->data['ClientAccounts'];
                $clientId = $_REQUEST['client'];
                $start_date = $_REQUEST['start_date'];
                
                /*if($this->BalanceMaster->find('first',array('fields'=>'clientId','conditions'=>array('clientId'=>$clientId,'not'=>array('start_date'=>null)))))
                {
                    $this->Session->setFlash("Activation Date Already Exists");
                }
                else
                {*/
                    if(!empty($start_date) && !empty($clientId))
                    {
                        //$data = array();
                        $start_date = $data['start_date'];
                        $start_date = date_format(date_create($start_date),'Y-m-d');
                        $end_date = date('Y-m-d',strtotime($start_date .' + 1 years'));
                        $end_date = date('Y-m-d',strtotime($end_date .' - 1 days'));
                        
                        //$data['start_date'] = $start_date;
                        $plan_det = $this->BalanceMaster->find('first',array('fields'=>array('PlanId','start_date'),'conditions'=>array('clientId'=>$clientId)));
                       if(!empty($plan_det))
                       {    
                            $dataN['activation_date'] = "'".date('Y-m-d H:i:s')."'";
                            $dataN['start_date'] = "'".$start_date."'";
                            $dataN['end_date'] = "'".$end_date."'";
                            //print_r($dataN);exit;
                            $allocation_type = 'activation';
                            $plan_id = $plan_det['BalanceMaster']['PlanId'];
                            if(!empty($plan_det['BalanceMaster']['start_date']))
                            {
                                $allocation_type = 're-activation';
                            }
                            
                            if($this->BalanceMaster->updateAll($dataN,array('clientId'=>$clientId)))
                            {
                                $qry_alloc_log = "INSERT INTO `billing_plan_alloc_log` SET client_id='$clientId',plan_id='$plan_id', start_date='$start_date',created_by='$userid',alloc_type='$allocation_type',created_at=NOW();";
                                $this->BalanceMaster->query($qry_alloc_log);
                                #$this->Session->setFlash("Activation Date has been Saved.");
                                echo "Activation Date has been Saved.";exit;
                            }
                            else
                            {
                               # $this->Session->setFlash("Please Try Again");
                                echo "Something went wrong. Please Try Again";exit;
                            }
                       }
                       else
                       {
                           echo "Plan Not Allocated To Client";exit;
                           #$this->Session->setFlash("Plan Not Allocated To Client");
                       }
                    }
                    else 
                    {
                        echo "Activation Date Is Empty";exit;
                        #$this->Session->setFlash("Activation Date Is Empty");
                    }
                } 
            exit;
        }
        
	public function get_client_name($id)
        {
		$client =$this->RegistrationMaster->find('first',array('fields'=>array("Company_name"),'conditions'=>array('company_id'=>$id)));
		return $client['RegistrationMaster']['Company_name'];
	}
		
}
?>