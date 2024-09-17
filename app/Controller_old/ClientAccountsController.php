<?php
class ClientAccountsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','Waiver','PlanMaster','BalanceMaster');
	
    public function beforeFilter() 
    {
        parent::beforeFilter();
	$this->Auth->allow('client_wise_account_creation','view_client_account','edit_account','add_account','get_client_name',
                'add_start_date'
                );
	if(!$this->Session->check("admin_id"))
        {
            return $this->redirect(array('controller'=>'ClientAccounts','action' => 'index'));
	}
    }

    public function index() 
    {
        $this->layout='user';
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
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
                        
			else{
                                
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
		$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
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
            $ActivateClient = $this->BalanceMaster->find('list',array('fields'=>array('Id','clientId'),'conditions'=>array('start_date'=>null)));
            $this->set('ClientName',$this->RegistrationMaster->find('list',array('fields'=>array('company_id','company_name'),
                'conditions'=>array('company_id'=>array_values($ActivateClient)))));
            
            $userid = $this->Session->read('admin_id');
            
            if($this->request->is('POST'))
            {
                //print_r($this->request->data['ClientAccounts']); exit;
                $data = $this->request->data['ClientAccounts'];
                $clientId = $data['clientId'];
                $start_date = $data['start_date'];
                
                if($this->BalanceMaster->find('first',array('fields'=>'clientId','conditions'=>array('clientId'=>$clientId,'not'=>array('start_date'=>null)))))
                {
                    $this->Session->setFlash("Start Date Already Exists");
                }
                else
                {
                    if(!empty($start_date) && !empty($clientId))
                    {
                        //$data = array();
                        $start_date = date_format(date_create($start_date), 'Y-m-d');
                        //$data['start_date'] = $start_date;
                        
                       if($planId = $this->BalanceMaster->find('first',array('fields'=>'PlanId','conditions'=>array('clientId'=>$clientId))))
                       {    
                            if($planPeriod = $this->PlanMaster->find('first',array('fields'=>array('PeriodType','RentalPeriod'),
                                'conditions'=>array('Id'=>$planId['BalanceMaster']['PlanId']))))
                            {
                                //print_r($planPeriod); exit;
                                $RentalPeriod = $planPeriod['PlanMaster']['RentalPeriod'];
                                $period = $planPeriod['PlanMaster']['PeriodType'];
                                
                                $this->BalanceMaster->query("UPDATE `balance_master` SET start_date ='$start_date', "
                                        . "end_date = DATE_ADD('$start_date',INTERVAL $RentalPeriod $period) WHERE clientId='$clientId' "
                                        . "AND start_date IS NULL");
                                
                                $this->PlanMaster->query("INSERT INTO `history_balance_master`(Reasion,clientId,start_date,end_date,period,user_id,createdate)
                                VALUES('Add Start Date By Admin','$clientId','$start_date',DATE_ADD('$start_date',INTERVAL $RentalPeriod $period),'$RentalPeriod $period','$userid',NOW());");
                                
                                $this->Waiver->save(array('clientId'=>$clientId,'createdate'=>date('Y-m-d H:i:s')));
                                
                                $this->Session->setFlash("Start Date Added To Client");
                            }
                            else
                            {
                                $this->Session->setFlash("Plan Not Exists");
                            }
                       }
                       else
                       {
                           $this->Session->setFlash("Plan Not Exists For the Client");
                       }
                    }
                    else 
                    {
                        $this->Session->setFlash("Start Date Is Empty");
                    }
                }  
            }
        }
        
	public function get_client_name($id)
        {
		$client =$this->RegistrationMaster->find('first',array('fields'=>array("Company_name"),'conditions'=>array('company_id'=>$id)));
		return $client['RegistrationMaster']['Company_name'];
	}
		
}
?>