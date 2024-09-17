<?php
class ClientCrmController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('RegistrationMaster','BalanceMaster','PlanMaster','DidMaster','EcrMaster','FieldCreation','CloseLoopMaster','ObecrMaster','ObfieldCreation','OutboundCloseLoopMaster','ListMaster','ObCampaignDataTypeMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('view','ob');
        if(!$this->Session->check("admin_id")){
                return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }		
    }

    public function index() 
    {
        $this->layout="user";
        $client_id = $this->Session->read('companyid');   
        $this->set('client_id',$client_id); 
        if($client_id == ''){$this->redirect(array('controller'=>'Admins','action'=>''));}
        $client_det = $this->RegistrationMaster->find('first',array('conditions'=>"company_id='$client_id'"));
        $this->set('client_det',$client_det);
        
        $plan_name = "";
        $allocate_plan = $this->BalanceMaster->find('first',array('conditions'=>array('clientId'=>$client_id)));
        $plan_det = array();
        //for plan check work starts from here
        if($allocate_plan)
        {
            $plan_action = "re-allocate";
            #print_r($allocate_plan);exit;
            $plan_id = $allocate_plan['BalanceMaster']['PlanId'];
            $plan_det = $this->PlanMaster->find('first',array('conditions'=>"id='$plan_id'"));
        }
        else
        {
            $plan_action = "allocate";
            $plan_name = "";
        }
        $this->set('plan_action',$plan_action);
        $this->set('plan_det',$plan_det);
        
        $activation_det = $this->BalanceMaster->query("SELECT company_name,date_format(start_date,'%d-%M-%Y') activation_date FROM registration_master rm 
            INNER JOIN balance_master bm ON rm.company_id = bm.clientId
            WHERE rm.company_id='$client_id' ORDER BY company_name");
            $activation_date = 0;
            if(!empty($activation_det))
            {
                if(!empty($activation_det['0']['0']['activation_date']))
                {$activation_date = $activation_det['0']['0']['activation_date'];}
            }
        
        $this->set('activation_date',$activation_date);
        
        //inbound works ends here
        $did_number_list = $this->DidMaster->find('all',array('conditions'=>array('client_id'=>$client_id)));
        #print_r($did_number_list);exit;
        if(!empty($did_number_list))
        {
            $did_list = array();
            foreach($did_number_list as $did_obj)
            {
               $did_list[] =  $did_obj['DidMaster']['did_number'];
            }
            #print_r($did_list);exit;
            $did = implode(',',$did_list);
            $this->set('did',$did); 
        }
        $this->set('did_action',"admin_details/clientdid");
        
        //Campaign check works starts from here
        $campaignid = str_replace("'"," ",$client_det['RegistrationMaster']['campaignid']);
        $campaign_action = "";
        
        $campaign_action = "admin_details/addcampaign";
        
        $this->set('campaignid',$campaignid);
        $this->set('campaign_action',$campaign_action); 
        $exp=  explode(",", $client_det['RegistrationMaster']['campaignid']);
        $campaignName=array();
        for($i=0;$i<count($exp);$i++){
            $campaignName[]=str_replace("'", '', $exp[$i]);
        } 
        $campName=implode(',', $campaignName);
        $this->set('campName',$campName);
        
        $exp2=  explode(",", $client_det['RegistrationMaster']['GroupId']);
        $GroupName=array();
        for($i=0;$i<count($exp2);$i++){
            $GroupName[]=str_replace("'", '', $exp2[$i]);
        } 
        $grpName=implode(',', $GroupName);
        $this->set('grpName',$grpName);
        
        $exp3=  explode(",", $client_det['RegistrationMaster']['multilang_ivrs']);
        $MultiLangName=array();
        for($i=0;$i<count($exp3);$i++){
            $MultiLangName[]=str_replace("'", '', $exp3[$i]);
        } 
        $multiName=implode(',', $MultiLangName);
        $this->set('multiName',$multiName);
        //Campaign check works ends from here
        
        
        //scenario works starts from here
        $ecdata =$this->EcrMaster->find('list',array('fields'=>array("Id","ecrName"),'conditions'=>array('Client'=>$client_id,'Label'=>'1')));
        $scenario = implode(", ",$ecdata);
        $this->set('scenario',$scenario);
        $this->set('scenario_action',"Ecrs");
        #print_r($ecdata);exit;

        //outbound scenario works starts from here
        $ecdata =$this->ObecrMaster->find('list',array('fields'=>array("Id","ecrName"),'conditions'=>array('Client'=>$client_id,'Label'=>'1')));
        $scenario = implode(", ",$ecdata);
        $this->set('obscenario',$scenario);
        $this->set('obscenario_action',"Obecrs");
        
        //MANAGE REQUIRED FIELDS works starts from here
        $fielddata =$this->FieldCreation->find('list',array('fields'=>array('id','FieldName'),'conditions'=>array('ClientId'=>$client_id,'FieldStatus'=>NULL)));
        $rfields = implode(", ",$fielddata);
        $this->set('rfields',$rfields);
        $this->set('rfield_action',"ClientFields");
        #print_r($ecdata);exit;
        //manage required fields works ends from here

        //MANAGE ob REQUIRED FIELDS works starts from here
        $fielddata =$this->ObfieldCreation->find('list',array('fields'=>array('id','FieldName'),'conditions'=>array('ClientId'=>$client_id,'FieldStatus'=>NULL)));
        $rfields = implode(", ",$fielddata);
        $this->set('obrfields',$rfields);
        $this->set('obrfield_action',"ObclientFields");
        #print_r($ecdata);exit;
        //manage ob required fields works ends from here
        
        //manage close looping works starts from here
        $closeloop_data =$this->CloseLoopMaster->find('list',array('fields'=>array('id','CategoryName1'),'conditions' =>array('client_id' => $client_id)));
        $closeloop_scenario = implode(", ",$closeloop_data);
        $this->set('closeloop_scenario',$closeloop_scenario);
        $this->set('closeloop_action',"CloseLoopings");
        #print_r($ecdata);exit;
        //manage close looping works ends from here

         //manage ob close looping works starts from here
         $closeloop_data =$this->OutboundCloseLoopMaster->find('list',array('fields'=>array('id','CategoryName1'),'conditions' =>array('client_id' => $client_id)));
         $closeloop_scenario = implode(", ",$closeloop_data);
         $this->set('obcloseloop_scenario',$closeloop_scenario);
         $this->set('obcloseloop_action',"ObcloseLoopings");
         #print_r($ecdata);exit;
         //manage ob close looping works ends from here
        
        $client_list = array($client_det['RegistrationMaster']['company_id']=>$client_det['RegistrationMaster']['company_name']);
        $this->set('client_list',$client_list);
        //for plan allocaton works starts from here
        $this->set('PlanList',$this->PlanMaster->find('list',array('fields'=>array('Id','PlanName'),'order'=>array('PlanName'=>'asc'))));
         
        //for client did creation works starts from here
        $this->set('didnumber',$this->DidMaster->find('first',array('conditions'=>array('client_id'=>$client_id))));

        //for list id
        $list_data = $this->ListMaster->find('list',array('fields'=>array('id','list_id'),'conditions'=>array('client_id'=>$client_id)));
        $list_id = implode(", ",$list_data);
        $this->set('list_id',$list_id);
        $this->set('list_action',"AdminDetails/addcampaignlistid");

        //for campaign subtype
        $camptype_data = $this->ObCampaignDataTypeMaster->find('list',array('fields'=>array('id','CampaignType'),'conditions'=>array('clientid'=>$client_id)));
        $camp_subtype = implode(", ",$camptype_data);
        $this->set('camp_subtype',$camp_subtype);
        $this->set('camp_action',"AdminDetails/addcampaignsubtype");        
        
    }
	
	
		
}
?>