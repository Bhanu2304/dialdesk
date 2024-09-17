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
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
        $this->set('client',$client);
        $this->set('clientid',array());

        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            if($this->BalanceMaster->find('first',array('fields'=>array('Id'),'conditions'=>array('clientId'=>$_REQUEST['id'])))){
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
            if($this->BalanceMaster->find('first',array('fields'=>array('id'),'conditions'=>array('clientId'=>$data['clientID'])))){
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
		$client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A'),'order'=>array('Company_name'=>'asc')));
		$this->set('client',$client);
		if($this->request->is('Post')){
			$data=$this->request->data['ClientWaivers'];
                        //print_r($data); exit;
                        $waiver=$this->Waiver->find('all',array('conditions'=>array('clientId'=>$data['clientID'])));
			if(!empty($waiver)){
                                //print_r($waiver); exit;
				$this->set('waiver_all',$waiver);
			}
			else{
				$this->set('waiver_all',array());
			}
                        $this->set('clientId',$data['clientID']);
		}
		if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
			if($waiver=$this->Waiver->find('all',array('conditions'=>array('clientId'=>$_REQUEST['id'])))){
				$this->set('waiver_all',$waiver);
			}
			else{
				$this->set('waiver_all',array());
			}
                        $this->set('clientId',$_REQUEST['id']);
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
            
            //$start_date = 
            $BalanceMaster = $this->BalanceMaster->find('first',array('conditions'=>"clientId='$id'"));
            //print_r($BalanceMaster); exit;
            $start_date1 = $start_date = $BalanceMaster['BalanceMaster']['start_date'];
            $end_date = $BalanceMaster['BalanceMaster']['end_date'];
            $PlanId = $BalanceMaster['BalanceMaster']['PlanId'];
            
            $PlanDetails = $this->PlanMaster->find('first',array('conditions'=>"Id='{$PlanId}' "));
            $PeriodType = strtolower($PlanDetails['PlanMaster']['PeriodType']);
            
           if($PeriodType=='month')
            {
               for($i=1;$i<=12;$i++)
               {
                    $end_date1 = date('Y-m-d',strtotime($start_date ." + $i months"));
                    $end_date2 = date('Y-m-d',strtotime($end_date1 ." - 1 days"));
                    $period_arr["$start_date1@$end_date2"] =  date('d-M-y',strtotime($start_date1)).' To '.  date('d-M-y',strtotime($end_date2)); 
                    $start_date1 = $end_date1;
               }
            }
            else if($PeriodType=='quater')
            {
                for($i=1;$i<=4;$i++)
               {
                    $end_date1 = date('Y-m-d',strtotime($start_date ." + ".($i*3)." months"));
                    $end_date2 = date('Y-m-d',strtotime($end_date1 ." - 1 days"));
                    $period_arr["$start_date1@$end_date2"] =  date('d-M-y',strtotime($start_date1)).' To '.  date('d-M-y',strtotime($end_date2)); 
                    $start_date1 = $end_date1;
               }
            }
            else if($PeriodType=='year')
            {
                $period_arr["$start_date@$end_date"] =    date('d-M-y',strtotime($start_date)).' To '.  date('d-M-y',strtotime($end_date));
            } 
            
            $this->set('month_arr',$period_arr);
            $this->set('id',$id);
            //print_r($period_arr); exit;
            
            if($this->request->is('Post'))
            {
                //print_r($this->request->data); exit;
                if(!empty($this->request->data['ClientWaivers']))
                {
                    //print_r($this->request->data); exit;
                    $data = $this->request->data['ClientWaivers'];
                    $type = $data['WaiverType'];
                    
                    //$dataY = $this->Waiver->find('first',array('fields'=>array('clientId',$type,'RentalPeriod'),'conditions'=>array('id'=>$id)));
                    //$clientId = $dataY['Waiver']['clientId'];
                    $dataZ = array();
                    $value  = $data['freewaiver'];
                    //$value += $dataY['Waiver'][$type];
                    $dataZ = array($type=>$value);
                    $dataZ['remarks'] = addslashes($data['Remarks']);
                    $session_arr =  explode("@",$data['Period']);
                    $dataZ['start_date'] = $session_arr['0'];
                    $dataZ['end_date'] = $session_arr['1'];
                    $dataZ['clientId'] = $this->request->data['client_id']; 
                    $user = $this->Session->read("admin_id");
                    $dataZ['clientId'] = $this->request->data['client_id']; 
                    $dataZ['createdate'] = date('Y-m-d H:i:s'); 
                    $dataZ['created_by'] = $user; 
                    $data = array();
                    
                    
                    //print_r($dataZ); exit;
                    
                    //$dataZ = $data;
                    if($this->Waiver->save($dataZ))
                    { //exit;
                        unset($this->request->data);unset($data);unset($dataY);unset($k);unset($v); //unset($clientId);
                        unset($data);  unset($dataZ); unset($id); unset($period); unset($type);
                        $this->Session->setFlash('Balance Updated Successfully.');
                        //$this->redirect(array('controller'=>'ClientWaivers','action'=>'view_client_waiver','?'=>array('id'=>$clientId)));
                    }
                    else
                    {
                        
                        unset($this->request->data);unset($data);unset($dataY);unset($k);unset($v); //unset($clientId);
                        unset($data);  unset($dataZ); unset($id); unset($period); unset($type);
                        $this->Session->setFlash('Balance Not Updated.');
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