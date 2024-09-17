<?php
class ClientWaiversController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','Waiver','BalanceMaster','PlanMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
			$this->Auth->allow('client_wise_waiver_creation','view_client_waiver','edit_waiver','add_waiver','get_client_name');
			if(!$this->Session->check("admin_id")){
				return $this->redirect(array('controller'=>'Admins','action' => 'index'));
			}		
    }

    public function index() 
        {
        $this->layout='user';
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
        $this->set('client',$client);
        $this->set('clientid',array());

        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            if($this->Waiver->find('first',array('fields'=>array('Id'),'conditions'=>array('clientId'=>$_REQUEST['id'])))){
                    $this->redirect(array('controller' => 'ClientWaivers', 'action' => 'view_client_waiver', '?' => array('id' =>$_REQUEST['id'])));	
            }
            else{
                $this->set('waiver',array());
                $this->set('clientid',$_REQUEST['id']);
            }
        }

        if($this->request->is('Post')){
            $data=$this->request->data['ClientWaivers'];
            //print_r($data); exit;
            if($this->Waiver->find('first',array('fields'=>array('id'),'conditions'=>array('clientId'=>$data['clientID'])))){
                $this->redirect(array('controller' => 'ClientWaivers', 'action' => 'view_client_waiver', '?' => array('id' =>$data['clientID'])));	
            }
            else{
                $this->set('waiver',array());
                $this->set('clientid',$data['clientID']);
            }
        }
    }
	
	public function client_wise_waiver_creation()
        {
		if($this->request->is('Post'))
                {
			$data=$this->request->data['ClientWaivers']; //client new waiver creation
                        $waiver=$this->request->data['Free']; //client free waiver
                        $client_account = array(); //client account balance
                        //print_r($waiver); exit;
			//$data['clientName'] = $this->get_client_name($data['clientID']);
                        $client_account['clientId'] = $waiver['clientId']=$data['clientId'] = $data['clientID'];   //for saving client account
			$client_account['createdate'] = $waiver['createdate']= $data['createdate'] = date('Y-m-d H:i:s'); //same createdate for all
                        $client_account['FreeValueBalance'] = $data['FreeValue'];
                        $client_account['FreeInboundBalance'] = $waiver['InboundCall'];
                        $client_account['FreeOutboundBalance'] = $waiver['OutboundCall'];
                        $client_account['FreeMissCallBalance'] = $waiver['MissCall'];
                        $client_account['FreeVFOBalance'] = $waiver['VFOCall'];
                        $client_account['FreeSMSBalance'] = $waiver['SMS'];
                        $client_account['FreeEmailBalance'] = $waiver['Email'];
                        
			if($this->Waiver->find('first',array('fields'=>array('id'),'conditions'=>array('clientID'=>$data['clientID'])))){
				$this->Session->setFlash('Waiver allready Created.');
				$this->redirect(array('controller' => 'ClientWaivers', 'action' => 'view_client_waiver', '?' => array('id' =>$data['clientID'])));	
			}
                        else if($this->Waiver->find('first',array('fields'=>array('id'),'conditions'=>array('clientID'=>$data['clientID']))))
                        {
                            $this->Session->setFlash('Waiver Waiver Already Exist! Delete if First');
				$this->redirect(array('controller' => 'ClientWaivers', 'action' => 'view_client_waiver', '?' => array('id' =>$data['clientID'])));	
                        }
                        
			else{
                                
				$this->Waiver->save($data);
                                
                                $WaiverId = $this->Waiver->getInsertId();
                                
                                $client_account['WaiverId'] = $waiver['WaiverId']=$WaiverId;
                                
                                $this->Waiver->save($waiver);
                                
                                //$client_account = array();
                                $client_account['clientId'] = $waiver['clientId'];
                                
                                $this->BalanceMaster->save($client_account);
                                //print_r($client_account); exit;
                                
				$this->Session->setFlash('Waiver Create Successfully.');
				$this->redirect(array('controller' => 'ClientWaivers', 'action' => 'view_client_waiver', '?' => array('id' =>$data['clientID'])));
                            }
		}
	}
	
	public function view_client_waiver() {
		$this->layout='user';
		$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
		$this->set('client',$client);
		if($this->request->is('Post')){
			$data=$this->request->data['ClientWaivers'];
			if($waiver=$this->Waiver->find('first',array('conditions'=>array('clientId'=>$data['clientID'])))){
				$this->set('waiver',$waiver);
			}
			else{
				$this->set('waiver',array());
			}	
		}
		if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
			if($waiver=$this->Waiver->find('first',array('conditions'=>array('clientId'=>$_REQUEST['id'])))){
				$this->set('waiver',$waiver);
			}
			else{
				$this->set('waiver',array());
			}	
		}
			
	}
	
        public function edit_waiver()
        {
            $id = $this->params->query['id'];
            $this->layout='user';
            $this->set('waiver',$this->Waiver->find('first',array('conditions'=>array('id'=>$id))));
            
            if($this->request->is('Post'))
            {
                //print_r($this->request->data); exit;
                if(!empty($this->request->data['id']) && !empty($this->request->data['ClientWaivers']))
                {
                    $id=$this->request->data['id'];
                    $data = $this->request->data['ClientWaivers'];
                    
                    $oldWaiver = $this->Waiver->find('first',array('conditions'=>array('id'=>$id)));
                    $clientId = $oldWaiver['Waiver']['clientId'];
                    
                    $balance_master = $this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>$clientId)));
                    
                    $BalanceMaster['FreeBalance'] = $balance_master['BalanceMaster']['FreeBalance'] + ($data['FreeValue']-$oldWaiver['Waiver']['FreeValue']);
                    $BalanceMaster['FreeInboundMinute'] = $balance_master['BalanceMaster']['FreeInboundMinute'] + ($data['InboundCall']-$oldWaiver['Waiver']['InboundCall']);
                    $BalanceMaster['FreeOutboundMinute'] = $balance_master['BalanceMaster']['FreeOutboundMinute'] + ($data['OutboundCall']-$oldWaiver['Waiver']['OutboundCall']);
                    $BalanceMaster['FreeMissCallMinute'] = $balance_master['BalanceMaster']['FreeMissCallMinute'] + ($data['MissCall']-$oldWaiver['Waiver']['MissCall']);
                    $BalanceMaster['FreeVFOMinute'] = $balance_master['BalanceMaster']['FreeVFOMinute'] + ($data['VFOCall']-$oldWaiver['Waiver']['VFOCall']);
                    $BalanceMaster['FreeSMS'] = $balance_master['BalanceMaster']['FreeSMS'] + ($data['SMS']-$oldWaiver['Waiver']['SMS']);
                    $BalanceMaster['FreeEmail'] = $balance_master['BalanceMaster']['FreeEmail'] + ($data['Email']-$oldWaiver['Waiver']['Email']);
                    
                    foreach($data as $k=>$v)
                    {
                        $dataY[$k] = "'".addslashes($v)."'";
                    }
                    
                    
                    //print_r($BalanceMaster); exit;
                    if($this->Waiver->updateAll($dataY,array('id'=>$id)))
                    {
                        $this->BalanceMaster->updateAll($BalanceMaster,array('clientId'=>$clientId));
                        unset($this->request->data);unset($data);unset($dataY);unset($k);unset($v);
                        $this->Session->setFlash('Waiver Updated Successfully.');
                        $this->redirect(array('controller'=>'ClientWaivers','action'=>'view_client_waiver','?'=>array('id'=>$clientId)));
                    }
                    else
                    {
                        unset($this->request->data);unset($data);unset($dataY);unset($k);unset($v);
                        $this->Session->setFlash('Waiver Not Updated.');
                        $this->redirect(array('controller'=>'ClientWaivers','action'=>'view_client_waiver','?'=>array('id'=>$clientId)));
                    }
                } 
            }
        }
        
        public function add_waiver()
        {
            $id = $this->params->query['id'];
            $this->layout='user';
            $user = $this->Session->read("admin_id");
            if($this->request->is('Post'))
            {
                //print_r($this->request->data); exit;
                if(!empty($this->request->data['ClientWaivers']))
                {
                    $data = $this->request->data['ClientWaivers'];
                    $type = $data['WaiverType'];
                    
                    $dataY = $this->Waiver->find('first',array('fields'=>array('clientId',$type,'RentalPeriod'),'conditions'=>array('id'=>$id)));
                    $clientId = $dataY['Waiver']['clientId'];
                    $dataZ = array();
                    
                    $balance_master = $this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>$clientId)));
                    
                    //print_r($type); exit;
                    if($type=='PeriodType')
                    {
                        $no = $data['RentalPeriod'];
                        $period = $data[$type];
                       
                        if(empty($dataY['Waiver'][$type]))
                        {
                            $dataZ[$type] = $data[$type];
                            $dataZ['RentalPeriod'] = $data['RentalPeriod'];
                        }
                        
                        else
                        {
                            if($dataY['Waiver'][$type]==$data[$type])
                            {
                                $dataZ[$type] = $data[$type];
                                $dataZ['RentalPeriod'] = $data['RentalPeriod']+$dataY['Waiver']['RentalPeriod'];
                                //print_r($dataZ); exit;
                            }
                            else
                            {
                                if($dataY['Waiver'][$type]=='Day')
                                {
                                    $day1 = $dataY['Waiver']['RentalPeriod'];
                                }
                                else if($dataY['Waiver'][$type]=='Month')
                                {
                                    $day1 = $dataY['Waiver']['RentalPeriod']*30;
                                }
                                else if($dataY['Waiver'][$type]=='Year')
                                {
                                    $day1 = $dataY['Waiver']['RentalPeriod']*365;
                                }
                                
                                if($data[$type]=='Day')
                                {
                                    $day2 = $data['RentalPeriod'];
                                }
                                else if($data[$type]=='Month')
                                {
                                    $day2 = $data['RentalPeriod']*30;
                                }
                                else if($dataY['Waiver'][$type]=='Year')
                                {
                                    $day2 = $data['RentalPeriod']*365;
                                }
                                
                                $day = $day1+$day2;
                                if(($day/365)>0)
                                {
                                    $periodType = 'Year';
                                    $RentalPeriod = round($day/365,0);
                                }
                                else if(($day/30)>0)
                                {
                                    $periodType = 'Month';
                                    $RentalPeriod = round($day/30,0);
                                }
                                else if($day>0)
                                {
                                    $periodType = 'Day';
                                    $RentalPeriod = $day;
                                }
                                
                                $dataZ[$type] =$periodType;
                                $dataZ['RentalPeriod'] = $RentalPeriod;
                                
                                
                            }
                        }
                        $this->BalanceMaster->query("update `balance_master` set end_date = DATE_ADD(end_date,INTERVAL $no {$data[$type]}) where clientId='$clientId'");
                        $this->BalanceMaster->query("INSERT INTO `history_balance_master`(Reasion,clientId,start_date,end_date,new_end_date,period,
                            old_waiver,new_waiver,user_id,createdate)
                        VALUES('Waiver Given on Rental Period','$clientId','{$balance_master['BalanceMaster']['start_date']}',"
                        . "'{$balance_master['BalanceMaster']['end_date']}',DATE_ADD('{$balance_master['BalanceMaster']['end_date']}',INTERVAL $no {$data[$type]}),"
                        . "'$no {$data[$type]}','{$dataY['Waiver']['RentalPeriod']} {$dataY['Waiver'][$type]}','{$dataZ['RentalPeriod']} {$dataZ[$type]}','$user',NOW());");
                    }
                    else
                    {
                        $value  = $data['freewaiver'];
                        $value += $dataY['Waiver'][$type];
                        $dataZ = array($type=>$value);
                        
                        if($type=='Balance'){  
                            $BalanceMaster['MainBalance'] = $balance_master['BalanceMaster']['MainBalance'] +$data['freewaiver'];
                        }
                        
                        $this->BalanceMaster->updateAll($BalanceMaster,array('clientId'=>$clientId));
                        $this->BalanceMaster->query("INSERT INTO `history_balance_master`(Reasion,clientId,old_balance,
                            new_balance,waiver_amount,old_waiver,new_waiver,user_id,createdate)
                        VALUES('Waiver Given on Balance','$clientId','{$balance_master['BalanceMaster']['Balance']}',"
                        . "'{$BalanceMaster['Balance']}','{$data['freewaiver']}','{$dataY['Waiver'][$type]}','$value','$user',NOW());");
                    }
                    $data = array();
                    foreach($dataZ as $k=>$v)
                    {
                        $data[$k]  ="'".$v."'";
                    }
                    $dataZ = $data;
                    if($this->Waiver->updateAll($dataZ,array('id'=>$id)))
                    {
                        
                        unset($this->request->data);unset($data);unset($dataY);unset($k);unset($v); //unset($clientId);
                        unset($data);  unset($dataZ); unset($id); unset($period); unset($type);
                        $this->Session->setFlash('Waiver Updated Successfully.');
                        $this->redirect(array('controller'=>'ClientWaivers','action'=>'view_client_waiver','?'=>array('id'=>$clientId)));
                    }
                    else
                    {
                        unset($this->request->data);unset($data);unset($dataY);unset($k);unset($v); //unset($clientId);
                        unset($data);  unset($dataZ); unset($id); unset($period); unset($type);
                        $this->Session->setFlash('Waiver Not Updated.');
                        //$this->redirect(array('controller'=>'ClientWaivers','action'=>'view_client_waiver','?'=>array('id'=>$clientId)));
                    }
                } 
            }
        }
        
	public function update_client_waiver(){
		if($this->request->is('Post')){
			$data=$this->request->data['ClientWaivers'];
			$dataArr=array(
				'WaiverAmount'=>"'".$data['WaiverAmount']."'",
				'InboundCallCharge'=>"'".$data['InboundCallCharge']."'",
				'OutboundCallCharge'=>"'".$data['OutboundCallCharge']."'",
				'SmsCallCharge'=>"'".$data['SmsCallCharge']."'",
				'EmailCallCharge'=>"'".$data['EmailCallCharge']."'"
				);	
			$this->Waiver->updateAll($dataArr,array('clientID'=>$data['clientID']));
			$this->Session->setFlash('Waiver Update Successfully.');
			$this->redirect(array('controller' => 'ClientWaivers', 'action' => 'view_client_waiver', '?' => array('id' =>$data['clientID'])));
		}	
	}
	
	
	public function get_client_name($id){
		$client =$this->RegistrationMaster->find('first',array('fields'=>array("Company_name"),'conditions'=>array('company_id'=>$id)));
		return $client['RegistrationMaster']['Company_name'];
	}
		
}
?>