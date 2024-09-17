<?php
class OpdashTestController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler');
	public $uses=array('ClientCategory','UploadExistingBase','RegistrationMaster','vicidialCloserLog',
            'vicidialUserLog','CallMaster','CallRecord','CampaignName','CallMasterOut','ClientReportMaster',
            'AbandCallMaster','vicidialLog','PlanMaster','InitialInvoice','CostCenterMaster','BillMaster','BillingLedger','BalanceMaster','BillingMaster','AgentMaster');
	
            #$path = "/var/www/html/dialdesk/app/webroot/sl_log";
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
        //echo $this->Session->read("companyid");exit;
        if(!$this->Session->check("companyid") && !$this->Session->check("admin_id"))
        {
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
        
    }
    
    public function get_agent_chart()
    {
        $this->layout="ajax";
        $agentId = $this->request->data['client2'];
        $viewType = $this->request->data['type'];

        $agentIds = $this->getAgentIds($agentId);
        $agentIdstring = implode(',', $agentIds);
        $agent_data = $this->getAgentUtilizationData($agentIdstring,$viewType);
        echo $agent_data['Utilization'];exit;

    }

    public function get_sl_chart()
    {
        $this->layout="ajax";
        $clientIds = $this->request->data['client2'];
        $viewType = $this->request->data['type'];

        $campaignIds = $this->getCampaignIds($clientIds);
        #print_r($campaignIds);die;
        $campaignIdString = implode(',', $campaignIds);
        $campaignIdCondition = "t2.campaign_id IN ($campaignIdString)";
        $dt = $this->getCallData($campaignIdCondition, $viewType);
        #print_r($dt);exit;
        echo json_encode($dt);exit;
        //print_r($agent_data);exit;
        //$this->set('agent_data', $agent_data);
        
        
    }

    public function al_sl_chart()
	{
		$this->layout='user';

        $this->setClientsAndAgents();
        $campaignIds = $this->getAllCampaignIds();

        $campaignIdCondition = "t2.campaign_id IN ($campaignIds)";
        $dt = $this->getCallData($campaignIdCondition, 'Today');
        
        $agentIds = $this->getAllAgentIds();
        $agent_data = $this->getAgentUtilizationData($agentIds,'Today');

        if($this->request->is("POST"))
        {
            #print_r($this->request->data);die;

            $clientIds = $this->request->data['clientID'];
            $viewType = $this->request->data['view_type'];
            $agentIds = $this->request->data['agent_id'];
            $viewType2 = $this->request->data['view_type2'];

            if(!empty($clientIds))
            {
                $campaignIds = $this->getCampaignIds($clientIds);
                $campaignIdString = implode(',', $campaignIds);
                $campaignIdCondition = "t2.campaign_id IN ($campaignIdString)";
                $dt = $this->getCallData($campaignIdCondition, $viewType);
            }

            if(!empty($agentIds))
            {
                $agentIds = $this->getAgentIds($agentIds);
                $agentIdstring = implode(',', $agentIds);
                $agent_data = $this->getAgentUtilizationData($agentIdstring,$viewType2);
            }
            
            
            
        }

        $this->set('view_type',$viewType);
        $this->set('view_type2',$viewType2);

        $this->set('data', $dt);
        $this->set('agent_data', $agent_data);
        
        
	}


    function setClientsAndAgents()
    {
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
        $client = array('All'=>'All')+$client;
        $this->set('client',$client);

        $agent_arr = $this->AgentMaster->find('list',array('fields'=>array("username","displayname"),'conditions'=>array('status'=>'A'))); 
        $agent_arr = array('All'=>'All')+$agent_arr;
        $this->set('agent_arr',$agent_arr);

    }


    function getAllCampaignIds()
    {
        $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
        $clientInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(campaignid) AS campaign_id  FROM `registration_master` WHERE `status`='A' AND is_dd_client='1'");
        return $clientInfo[0][0]['campaign_id'];
    }

    function getAllAgentIds()
    {
        $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
        $agentInfo = $this->RegistrationMaster->query("SELECT GROUP_CONCAT(CONCAT('\'', username, '\'')) AS username FROM `agent_master` WHERE `status`='A'"); 
        return $agentInfo[0][0]['username'];
    }

    function getCampaignIds($clientArr)
    {
        $campaignIds = [];
        foreach ($clientArr as $client) {
            $query = ($client == "All") ? "
                SELECT GROUP_CONCAT(campaignid) AS campaign_id 
                FROM `registration_master` 
                WHERE `status`='A' AND is_dd_client='1'
            " : "
                SELECT GROUP_CONCAT(campaignid) AS campaign_id 
                FROM `registration_master` 
                WHERE company_id='$client' AND `status`='A' AND is_dd_client='1'
            ";
            //echo $query;die;
            $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
            $clientInfo = $this->RegistrationMaster->query($query);
            if (!empty($clientInfo[0][0]['campaign_id'])) {
                $campaignIds[] = $clientInfo[0][0]['campaign_id'];
            }
        }
        return $campaignIds;
    }

    function getAgentIds($agentArr)
    {
        $agentIds = [];
        foreach ($agentArr as $agent) {
            $query = ($agent == "All") ? "
            SELECT GROUP_CONCAT(CONCAT('\'', username, '\'')) AS username FROM `agent_master` WHERE `status`='A'
            " : "
            SELECT GROUP_CONCAT(CONCAT('\'', username, '\'')) AS username FROM `agent_master` WHERE `status`='A' and username='$agent'
            ";
            #echo $query;die;
            $this->RegistrationMaster->query("SET GROUP_CONCAT_MAX_LEN=20000");
            $clientInfo = $this->RegistrationMaster->query($query);
            if (!empty($clientInfo[0][0]['username'])) {
                $agentIds[] = $clientInfo[0][0]['username'];
            }
        }
        return $agentIds;
    }



    function getCallData($campaignIds, $viewType)
    {
        $dateCondition = ($viewType == "MTD") ? 
            "DATE(t2.call_date) BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()" : 
            "DATE(t2.call_date) = CURDATE()";

       // $campaignIdCondition =   "t2.campaign_id in(".$campaignIds.")";


         $query = "SELECT  SUM(IF(t2.`user` != 'VDCL', 1, 0)) AS `Answered`,  SUM(IF(t2.`user` = 'VDCL', 1, 0)) AS `Abandon`,
            SUM(IF(t2.`user` != 'VDCL' AND t2.queue_seconds <= 20, 1, 0)) AS `WithinSLA`, SUM(IF(t2.`user` != 'VDCL' AND t2.queue_seconds <= 10, 1, 0)) AS `WithinSLATen`
            FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.vicidial_agent_log t3 ON t2.uniqueid = t3.uniqueid 
            WHERE $dateCondition AND $campaignIds   AND t2.term_reason != 'AFTERHOURS'  AND t2.lead_id IS NOT NULL";
        $this->vicidialCloserLog->useDbConfig = 'db2';
        $this->path;
        return $this->vicidialCloserLog->query($query);
    }


    function getAgentUtilizationData($agentIds,$viewType)
    {
        $wheretag = "";
        if($viewType =="MTD")
        {
            $wheretag .= "and DATE(event_time) BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()";
        }else{
            $wheretag .= "AND DATE(event_time) = CURDATE()";
        }
        #print_r($agentIds);die;
        $agentIdCondition = !empty($agentIds) ? "t1.user IN (" . $agentIds . ")" : "1=1";
        
        $query = "SELECT t1.user, SUM(t1.talk_sec) AS talk_sec,  SUM(t1.dispo_sec) AS dispo_sec,SUM(IF(t1.wait_sec > 10000, 0, wait_sec)) AS wait_sec,
        SUM(IF(t1.pause_sec > 10000, 0, pause_sec)) AS pause_sec FROM asterisk.vicidial_agent_log t1 WHERE $agentIdCondition  $wheretag ";
        #t1.user in ('IDC53213','IDC56050')
        $this->vicidialCloserLog->useDbConfig = 'db2';
        $agentData = $this->vicidialCloserLog->query($query);
        #print_r($agentData[0]);die;

        $A = $agentData[0][0]['talk_sec'] ;
        $B = $agentData[0][0]['talk_sec'] + $agentData[0][0]['dispo_sec'] + $agentData[0][0]['wait_sec'] + $agentData[0][0]['pause_sec'];
        $utilization['Utilization'] = round($A / $B * 100, 2);

        return $utilization;
    }
    

}
?>