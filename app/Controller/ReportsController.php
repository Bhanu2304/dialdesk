<?php
class ReportsController extends AppController{
    
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('RegistrationMaster','CallMaster','BalanceMaster','PlanMaster','vicidialCloserLog','vicidialLog','vicidialUserLog','ClientReportMaster','AbandCallMaster','Agent');
	
    public function beforeFilter(){
        parent::beforeFilter();
        $this->vicidialCloserLog->useDbConfig = 'db2';
        $this->vicidialLog->useDbConfig = 'db2';

        $this->Auth->allow('index','view_report','agentwise','view_agentwise','aband_report','export_aband_report','combined_allreport','export_combined_allreport');
        if(!$this->Session->check("admin_id")){
                return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }
    }

    public function index(){
        $this->layout = "user";

        if($this->Session->read('role') =="admin"){
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','is_dd_client'=>'1'),'order'=>array('Company_name'=>'asc')));
            //$this->set('category',$this->EcrRecord->find('list',array('fields'=>array('ecrName','ecrName'),'conditions'=>array('Client'=>$clientId,'Label'=>1,'parent_id'=>NULL))));
            $client = array('All'=>'All')+$client;
            $this->set('client',$client); 
        }else{
            $clientId   = $this->Session->read('companyid');
            $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name"),'conditions'=>array('status'=>'A','company_id'=>$clientId),'order'=>array('Company_name'=>'asc')));
            
            $this->set('client',$client); 
        }
        if($this->request->is('Post')){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=CallTaggingSummary.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $fdate=$this->request->data['FromDate'];
            $ldate=$this->request->data['ToDate'];
            $client = $this->request->data['clientID'];
            $conditions = "and date(CallDate) between '$fdate' and '$ldate'";
            $view_date = "date(t2.call_date) between '$fdate' and '$ldate'";

            if($client == 'All')
            {
                $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('company_name')));
                $clientList = $this->ClientReportMaster->find('list',array('fields'=>array('ClientId'),'conditions'=>array('Status'=>'A')));

            }else{

                $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A','company_id'=>$client),'order'=>array('company_name')));
            }
            // $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('company_name')));
            // $clientList = $this->ClientReportMaster->find('list',array('fields'=>array('ClientId'),'conditions'=>array('Status'=>'A')));
            $this->vicidialCloserLog->useDbConfig = 'db2';
            ?>        
            <table border="1">
                <tr>
                    <th>CLIENT NAME</th>
                    <th>TOTAL ANSWER</th>
                    <th>TOTAL ABAND</th>
                    <th>TOTAL TAGGED</th>
                    <th>UNIQUE TAGGED</th>
                    <th>ABAND CALL BACK</th>
                </tr>
                <?php 
                $ans=0;
                $abn=0;
                $tag=0;
                $tagu=0;
                $bak=0;

                foreach($clientArr as $row){
                    $Campagn=$row['RegistrationMaster']['campaignid'];
                    $CompanyId=$row['RegistrationMaster']['company_id'];
                    
                    if($Campagn !=""){
                        $CampagnId ="and t2.campaign_id in ($Campagn)";
                    }
                    else{
                        $CampagnId ="and t2.campaign_id in ('')";
                    }
                    
                    //echo "Select count(Id) as totaltagu FROM call_master where ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND CallType !='Upload' group by LeadId ";die;
                   
                //     $dt= $this->vicidialCloserLog->query("SELECT COUNT(*) `Total`,
                //    SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
                //     SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`
                //     FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid LEFT JOIN recording_log t4 ON t2.lead_id=t4.lead_id 
                //     WHERE $view_date $CampagnId and t2.term_reason!='AFTERHOURS' ");

                $dt= $this->vicidialCloserLog->query("SELECT COUNT(*) `Total`, SUM(IF(t2.`user` !='VDCL',1,0)) `Answered`, SUM(IF(t2.`user` ='VDCL',1,0)) `Abandon` FROM asterisk.vicidial_closer_log t2 
                    LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid LEFT JOIN asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid 
                    WHERE $view_date $CampagnId and t2.term_reason!='AFTERHOURS'");


                    $TACC=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND TagStatus IS NULL");
                    $tc=$this->CallMaster->query("Select count(Id) as totaltag, COUNT(DISTINCT LeadId) as totaltagu FROM call_master where ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND CallType !='Upload' AND CallType !='VFO-Inbound'");
                    $TACB=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCallBack FROM `aband_call_master` WHERE ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND (TagStatus='yes' or TagStatus='1')");
         
                    $AnsData=$dt[0][0]['Answered'];
                    //$AbnData=$TACC[0][0]['AbandCount'];
                    $AbnData=$dt[0][0]['Abandon'];
                    $totalTag=$tc[0][0]['totaltag'];
                    $totalTagU=$tc[0][0]['totaltagu'];
                    $BakData=$TACB[0][0]['AbandCallBack'];
                    
                    $ans=$ans+$AnsData;
                    $abn=$abn+$AbnData;
                    $tag=$tag+$totalTag;
                    $tagu=$tagu+$totalTagU;
                    $bak=$bak+$BakData;
                    
                ?>	
                    <tr>
                        <td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                        <td><?php echo $AnsData;?></td>
                        <td><?php echo $AbnData;?></td>
                        <td><?php echo $totalTag; ?></td>
                        <td><?php echo $totalTagU; ?></td>
                        <td><?php echo $BakData; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>TOTAL</td>
                    <td><?php echo $ans;?></td>
                    <td><?php echo $abn;?></td>
                    <td><?php echo $tag;?></td>
                    <td><?php echo $tagu;?></td>
                    <td><?php echo $bak;?></td>
                </tr>
            </table>
            <?php
            exit;
        }
    }
    
    public function view_report(){
        $this->layout = "ajax";
        if($_REQUEST['fdate']){
            
            $fdate=$_REQUEST['fdate'];
            $ldate=$_REQUEST['ldate'];
            $client = $_REQUEST['client_id'];
            $conditions = "and date(CallDate) between '$fdate' and '$ldate'";
            $view_date = "date(t2.call_date) between '$fdate' and '$ldate'";
            if($client == 'All')
            {
                $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('company_name')));
                $clientList = $this->ClientReportMaster->find('list',array('fields'=>array('ClientId'),'conditions'=>array('Status'=>'A')));

            }else{

                $clientArr = $this->RegistrationMaster->find('all',array('conditions'=>array('status'=>'A','company_id'=>$client),'order'=>array('company_name')));
            }
            //print_r($clientArr);die;
            
            $this->vicidialCloserLog->useDbConfig = 'db2';
            ?>        
             <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>CLIENT NAME</th>
                        <th>TOTAL ANSWER</th>
                        <th>TOTAL ABAND</th>
                        <th>TOTAL TAGGED</th>
                        <th>UNIQUE TAGGED</th>
                        <th>ABAND CALL BACK</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $ans=0;
                $abn=0;
                $tag=0;
                $tagu=0;
                $bak=0;
                
                foreach($clientArr as $row){
                    $Campagn=$row['RegistrationMaster']['campaignid'];
                    $CompanyId=$row['RegistrationMaster']['company_id'];
                    
                    if($Campagn !=""){
                        $CampagnId ="and t2.campaign_id in ($Campagn)";
                    }
                    else{
                        $CampagnId ="and t2.campaign_id in ('')";
                    }

                    // $dt= $this->vicidialCloserLog->query("SELECT COUNT(*) `Total`,
                    // SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`,
                    // SUM(IF(t2.status IS NULL OR t2.status='DROP',1,0)) `Abandon`
                    // FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid LEFT JOIN recording_log t4 ON t2.lead_id=t4.lead_id 
                    // WHERE $view_date $CampagnId and t2.term_reason!='AFTERHOURS'");

                    $dt= $this->vicidialCloserLog->query("SELECT COUNT(*) `Total`, SUM(IF(t2.`user` !='VDCL',1,0)) `Answered`, SUM(IF(t2.`user` ='VDCL',1,0)) `Abandon` FROM asterisk.vicidial_closer_log t2 
                    LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid LEFT JOIN asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid 
                    WHERE $view_date $CampagnId and t2.term_reason!='AFTERHOURS'");

                    
                    $TACC=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCount FROM `aband_call_master` WHERE ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND TagStatus IS NULL");
                    
                    $tc=$this->CallMaster->query("Select count(Id) as totaltag , COUNT(DISTINCT LeadId) as totaltagu FROM call_master where ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND CallType !='Upload' AND CallType !='VFO-Inbound'");
                    //$TACB=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCallBack FROM `aband_call_master` WHERE ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND TagStatus='yes'");

                    $TACB=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCallBack FROM `aband_call_master` WHERE ClientId='{$row['RegistrationMaster']['company_id']}' $conditions AND (TagStatus='yes' or TagStatus='1')");
                    
         
                    $AnsData=$dt[0][0]['Answered'];
                    //$AbnData=$TACC[0][0]['AbandCount'];
                    $AbnData=$dt[0][0]['Abandon'];
                    $totalTag=$tc[0][0]['totaltag'];
                    $totalTagU=$tc[0][0]['totaltagu'];
                    $BakData=$TACB[0][0]['AbandCallBack'];
                    
                    $ans=$ans+$AnsData;
                    $abn=$abn+$AbnData;
                    $tag=$tag+$totalTag;
                    $tagu=$tagu+$totalTagU;
                    $bak=$bak+$BakData;
                ?>
                <tr>
                    <td><?php echo $row['RegistrationMaster']['company_name'];?></td>
                    <td><?php echo $AnsData;?></td>
                    <td><?php echo $AbnData;?></td>
                    <td><?php echo $totalTag; ?></td>
                    <td><?php echo $totalTagU; ?></td>
                    <td><?php echo $BakData; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td>TOTAL</td>
                    <td><?php echo $ans;?></td>
                    <td><?php echo $abn;?></td>
                    <td><?php echo $tag;?></td>
                    <td><?php echo $tagu;?></td>
                    <td><?php echo $bak;?></td>
                </tr>
                </tbody>                    
            </table>
            <?php
            exit;
        }
    }
    
    
    public function agentwise(){
        $this->layout = "user";
        if($this->request->is('Post')){
            
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=AgentWiseCallTaggingSummary.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $fdate=$this->request->data['FromDate'];
            $ldate=$this->request->data['ToDate'];
            $conditions = "and date(CallDate) between '$fdate' and '$ldate'";
            $view_date = "date(t2.call_date) between '$fdate' and '$ldate'";
            
            $AgentArr = $this->Agent->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('displayname')));
            $this->vicidialCloserLog->useDbConfig = 'db2';
            ?>        
            <table border="1">
                <tr>
                    <th>AGENT NAME</th>
                    <th>TOTAL ANSWER</th>
                    <th>TOTAL TAGGED</th>
                    <th>UNIQUE TAGGED</th>
                    <!--<th>ABAND CALL BACK</th>-->
                </tr>
                <?php 
                $ans=0;
                $tag=0;
                $tagu=0;
                //$bak=0;
                
                foreach($AgentArr as $row){
                    $AgentId=$row['Agent']['id'];
                    $user=$row['Agent']['username'];
                    
                    $dt= $this->vicidialCloserLog->query("SELECT SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`
                    FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid LEFT JOIN recording_log t4 ON t2.lead_id=t4.lead_id 
                    WHERE $view_date and t2.user='$user' and t2.term_reason!='AFTERHOURS'");
                   
                    $tc=$this->CallMaster->query("Select count(Id) as totaltag, COUNT(DISTINCT LeadId) as totaltagu FROM call_master where AgentId='$AgentId' $conditions AND CallType !='Upload' AND CallType !='VFO-Inbound' AND callcreated LIKE 'DialDesk%'");
                    //$TACB=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCallBack FROM `aband_call_master` WHERE user='$user' $conditions AND TagStatus='yes'");
                    
                    $AnsData=$dt[0][0]['Answered'];
                    $totalTag=$tc[0][0]['totaltag'];
                    $totalTagU=$tc[0][0]['totaltagu'];
                    //$BakData=$TACB[0][0]['AbandCallBack'];
                    
                    $ans=$ans+$AnsData;
                    $tag=$tag+$totalTag;
                    $tagu=$tagu+$totalTagU;
                    //$bak=$bak+$BakData;
                ?>
                <tr>
                    <td><?php echo $row['Agent']['displayname'];?> (<?php echo $row['Agent']['username'];?>) (<?php echo $row['Agent']['id'];?>)</td>
                    <td><?php echo $AnsData;?></td>
                    <td><?php echo $totalTag; ?></td>
                    <td><?php echo $totalTagU; ?></td>
                   <!-- <td><?php echo $BakData; ?></td>-->
                </tr>
                <?php } ?>
                
                <?php 
                $tcu=$this->CallMaster->query("Select count(Id) as totaltag, COUNT(DISTINCT LeadId) as totaltagu FROM call_master where CallType !='Upload' $conditions AND CallType !='VFO-Inbound' AND callcreated LIKE 'User%'");
                ?>
                <?php if(!empty($tcu)){?>
                <tr>
                    <td>Client/User</td>
                    <td></td>
                    <td><?php echo $tcu[0][0]['totaltag'];?></td>
                    <td><?php echo $tcu[0][0]['totaltagu'];?></td>
                    <!--
                    <td><?php echo $bak;?></td>
                    -->
                </tr>
                <?php }?>
                <tr>
                    <td>TOTAL</td>
                    <td><?php echo $ans;?></td>
                    <td><?php echo ($tag+$tcu[0][0]['totaltag']);?></td>
                    <td><?php echo ($tagu+$tcu[0][0]['totaltagu']);?></td>
                    <!--
                    <td><?php echo $bak;?></td>
                    -->
                </tr>
                
            </table>
            <?php
            exit;
        }
    }
    
    public function view_agentwise(){
        $this->layout = "ajax";
        if($_REQUEST['fdate']){
            
            $fdate=$_REQUEST['fdate'];
            $ldate=$_REQUEST['ldate'];
            $conditions = "and date(CallDate) between '$fdate' and '$ldate'";
            $view_date = "date(t2.call_date) between '$fdate' and '$ldate'";
           
            $AgentArr = $this->Agent->find('all',array('conditions'=>array('status'=>'A'),'order'=>array('displayname')));
            $this->vicidialCloserLog->useDbConfig = 'db2';
            ?>        
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>AGENT NAME</th>
                        <th>TOTAL ANSWER</th>
                        <th>TOTAL TAGGED</th>
                        <th>UNIQUE TAGGED</th>
                       <!-- <th>ABAND CALL BACK</th>-->
                    </tr>
                </thead>
                <tbody>
                <?php 
                $ans=0;
                $tag=0;
                $tagu=0;
                //$bak=0;
                
                foreach($AgentArr as $row){
                    $AgentId=$row['Agent']['id'];
                    $user=$row['Agent']['username'];
                    
                    $dt= $this->vicidialCloserLog->query("SELECT SUM(If((t2.status='SALE' OR t2.status='BC' OR t2.status='Comp' OR t2.status='DISPO' OR t2.status='ENQ' OR t2.status='INCALL' OR t2.status='REQ' OR t2.status='XFER' OR t2.status='TIMEOT' OR t2.status='CALLBK' OR t2.status='CallBa' OR t2.status='ODR' OR t2.status='A' OR t2.status='C' OR t2.status='SE' OR t2.status='R' OR t2.status='ODR' OR t2.status='GE' OR t2.status='AA' OR t2.status='ERI' OR t2.status='B')and t2.user !='vdcl',1,0)) `Answered`
                   FROM asterisk.vicidial_closer_log t2 LEFT JOIN asterisk.call_log t1 ON t1.uniqueid=t2.uniqueid left join asterisk.vicidial_agent_log t3 ON t1.uniqueid=t3.uniqueid LEFT JOIN recording_log t4 ON t2.lead_id=t4.lead_id 
                    WHERE $view_date and t2.user='$user' and t2.term_reason!='AFTERHOURS'");
                   
                    $tc=$this->CallMaster->query("Select count(Id) as totaltag, COUNT(DISTINCT LeadId) as totaltagu FROM call_master where AgentId='$AgentId' $conditions AND CallType !='Upload' AND CallType !='VFO-Inbound' AND callcreated LIKE 'DialDesk%' ");
                    //$TACB=$this->AbandCallMaster->query("SELECT COUNT(Id) AS AbandCallBack FROM `aband_call_master` WHERE user='$user' $conditions AND TagStatus='yes'");
                    
                    $AnsData=$dt[0][0]['Answered'];
                    $totalTag=$tc[0][0]['totaltag'];
                    $totalTagU=$tc[0][0]['totaltagu'];
                    //$BakData=$TACB[0][0]['AbandCallBack'];
                    
                    $ans=$ans+$AnsData;
                    $tag=$tag+$totalTag;
                    $tagu=$tagu+$totalTagU;
                    //$bak=$bak+$BakData;
                ?>
                <tr>
                    <td><?php echo $row['Agent']['displayname'];?> (<?php echo $row['Agent']['username'];?>) (<?php echo $row['Agent']['id'];?>)</td>
                    <td><?php echo $AnsData;?></td>
                    <td><?php echo $totalTag; ?></td>
                    <td><?php echo $totalTagU; ?></td>
                   <!-- <td><?php echo $BakData; ?></td>-->
                </tr>
                <?php } ?>
                
                <?php 
                $tcu=$this->CallMaster->query("Select count(Id) as totaltag, COUNT(DISTINCT LeadId) as totaltagu FROM call_master where CallType !='Upload' $conditions AND CallType !='VFO-Inbound' AND callcreated LIKE 'User%'");
                ?>
                <?php if($tcu[0][0]['totaltag'] > 0){?>
                <tr>
                    <td>Client/User</td>
                    <td></td>
                    <td><?php echo $tcu[0][0]['totaltag'];?></td>
                    <td><?php echo $tcu[0][0]['totaltagu'];?></td>
                    <!--
                    <td><?php echo $bak;?></td>
                    -->
                </tr>
                <?php }?>

                <tr>
                    <td>TOTAL</td>
                    <td><?php echo $ans;?></td>
                    <td><?php echo $tag;?></td>
                    <td><?php echo $tagu;?></td>
                    <!--<td><?php echo $bak;?></td>-->
                </tr>
                </tbody>                    
            </table>
            <?php
            exit;
        }
    }
    
    public function aband_report()
    {
        $this->layout = "user";
        if($this->request->is('Post'))
        {
            if($_REQUEST['FromDate']){
            
                 header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=AbandCallReport.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
                
            $fdate=$_REQUEST['FromDate'];
            $ldate=$_REQUEST['ToDate'];
            $conditions = " date(CallDate) between '$fdate' and '$ldate'";
            
            $clientArr = $this->AbandCallMaster->find('all',array('conditions'=>$conditions,'order'=>array('CompanyName')));
            //$clientList = $this->ClientReportMaster->find('list',array('fields'=>array('ClientId'),'conditions'=>array('Status'=>'A')));
            
            ?>        
             <table border="2">
                
                    <tr>
                        <th>CLIENT NAME</th>
                        <th>TOTAL ABAND</th>
                        <th>PhoneNo</th>
                        <th>EntryDate</th>
                        <th>ABAND CALL Status</th>
                        <th>ABAND CALL Back Time</th>
                    </tr>
                
                <tbody>
                <?php 
                
                
                foreach($clientArr as $row){
                    
                ?>
                <tr>
                    <td><?php echo $row['AbandCallMaster']['CompanyName'];?></td>
                    <td><?php echo $row['AbandCallMaster']['AbandNoCount'];?></td>
                    <td><?php echo $row['AbandCallMaster']['PhoneNo'];?></td>
                    <td><?php echo $row['AbandCallMaster']['EntryDate'];?></td>
                    <td><?php echo $row['AbandCallMaster']['TagStatus'];?></td>
                    <td><?php echo $row['AbandCallMaster']['Callbackdate'];?></td>
                    
                </tr>
                <?php } ?>
                
                </tbody>                    
            </table>
            <?php
            exit;
        }
        }
        
    }
    public function export_aband_report()
    {
        $this->layout = "ajax";
        if($_REQUEST['fdate']){
            
            $fdate=$_REQUEST['fdate'];
            $ldate=$_REQUEST['ldate'];
            $conditions = " date(CallDate) between '$fdate' and '$ldate'";
            
            $clientArr = $this->AbandCallMaster->find('all',array('conditions'=>$conditions,'order'=>array('CompanyName')));
            //$clientList = $this->ClientReportMaster->find('list',array('fields'=>array('ClientId'),'conditions'=>array('Status'=>'A')));
            
            ?>        
             <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>CLIENT NAME</th>
                        <th>TOTAL ABAND</th>
                        <th>PhoneNo</th>
                        <th>EntryDate</th>
                        <th>ABAND CALL Status</th>
                        <th>ABAND CALL Back Time</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                
                
                foreach($clientArr as $row){
                    
                ?>
                <tr>
                    <td><?php echo $row['AbandCallMaster']['CompanyName'];?></td>
                    <td><?php echo $row['AbandCallMaster']['AbandNoCount'];?></td>
                    <td><?php echo $row['AbandCallMaster']['PhoneNo'];?></td>
                    <td><?php echo $row['AbandCallMaster']['EntryDate'];?></td>
                    <td><?php echo $row['AbandCallMaster']['TagStatus'];?></td>
                    <td><?php echo $row['AbandCallMaster']['Callbackdate'];?></td>
                    
                </tr>
                <?php } ?>
                
                </tbody>                    
            </table>
            <?php
            exit;
        }
    }

    // Basant

    public function combined_allreport()
    {
        $this->layout = "user";
               
    }

    public function export_combined_allreport1()
    {
        $this->layout = "ajax";     

        if($this->request->is('Post'))
        {
            
            // header("Content-type: application/octet-stream");
            // header("Content-Disposition: attachment; filename=combinedReportofAllClient.xls");
            // header("Pragma: no-cache");
            // header("Expires: 0");
            
            $fdate=$this->request->data['FromDate'];
            $ldate=$this->request->data['ToDate'];



            // $all_dt = $this->RegistrationMaster->query("SELECT * FROM balance_master bm 
            // INNER JOIN plan_master pm ON bm.PlanId = pm.id 
            // INNER JOIN registration_master rm ON bm.clientId = rm.company_id  order by company_name limit 20 ");

            // $all_dt = $this->RegistrationMaster->query("SELECT * FROM balance_master bm 
            // INNER JOIN plan_master pm ON bm.PlanId = pm.id 
            // INNER JOIN registration_master rm ON bm.clientId = 199  order by company_name limit 20 ");

              //Akai India

            $all_dt = $this->RegistrationMaster->query("SELECT * FROM balance_master bm 
            INNER JOIN plan_master pm ON bm.PlanId = pm.id 
            INNER JOIN registration_master rm ON bm.clientId = rm.company_id ORDER BY company_name limit 5");

            // print_r($all_dt);  die;
             
            //   $clientId = $all_dt[0]['rm']['company_id'];

            //   echo $Campagn = $all_dt[0]['rm']['campaignid'];


            ?>        
            <table border="1">
                <tr>
                    <th>Client Name</th>
                    <th>From</th>
                    <th>To</th>

                    <th>Inbound Call</th>
                    <th>Outbound Call</th>
                    <th>Night Shift l/b</th>
                    <th>SMS</th>
                    <th>EMAIL</th>
                    <th>MISSCALL</th>
                    <th>Call Forwarding</th>
                    <th>IVR Automation</th>

                    <th></th>
                    <th></th>
                    <th>Inbound Call</th>
                    <th>Outbound Call</th>
                    <th>Night Shift l/b</th>
                    <th>SMS</th>
                    <th>EMAIL</th>
                    <th>MISSCALL</th>
                    <th>Call Forwarding</th>
                    <th>IVR Automation</th>

                    <th></th>
                    <th></th>
                    <th>Inbound Call</th>
                    <th>Outbound Call</th>
                    <th>Night Shift l/b</th>
                    <th>SMS</th>
                    <th>EMAIL</th>
                    <th>MISSCALL</th>
                    <th>Call Forwarding</th>
                    <th>IVR Automation</th>
                </tr>
                <?php     
                
                foreach($all_dt as $row){


                    // $clientId = 199;
                //$clientId = 301;

                
                     //$Campagn = "'motherson','E_Motherson','H_Motherson'";
                     //$Campagn = "'AKAI'";

                     $clientId = $row['rm']['company_id']; 

                     $Campagn = $row['rm']['campaignid'];


                     if(!empty($Campagn))
                     {
                        $campaign_condition = " AND t2.campaign_id IN ($Campagn)";

                        $vicidial_campaign_condition = " and v.campaign_id in ($Campagn)";
                        
                     }
                     else
                     {
                        $campaign_condition = ' ';
                        $vicidial_campaign_condition = ' ';
                     }

                    $BalanceMaster = $this->BalanceMaster->query("select * from `balance_master` where clientId='$clientId'  limit 1");


                //$ClientInfo = $this->RegistrationMaster->query("select * from `registration_master` where company_id='$clientId' limit 1");
                
                // echo $Campagn; die;

                
                
                $PlanDetails = $this->PlanMaster->query("select * from `plan_master` where Id='{$BalanceMaster[0]['balance_master']['PlanId']}' limit 1");
                
                $start_date = $BalanceMaster[0]['balance_master']['start_date']; 
                $end_date = $BalanceMaster[0]['balance_master']['end_date'];
                $balance = $BalanceMaster[0]['balance_master']['MainBalance'];

                 $PeriodType = strtolower($PlanDetails[0]['plan_master']['PeriodType']);

               
                if($PlanDetails[0]['plan_master']['first_minute']=='Enable')
                {
                    //$ib_first_min = $PlanDetails[0]['plan_master']['ib_first_min'];
                    $ib_first_min='1';
                    $ob_first_min='1';
                }
                else
                {
                    $ib_first_min='0';
                    $ob_first_min='0';
                }
                $ib_pulse_sec = $PlanDetails[0]['plan_master']['pulse_day_shift'];
                $ibn_pulse_sec = $PlanDetails[0]['plan_master']['pulse_night_shift'];
                $ib_pulse_rate = $PlanDetails[0]['plan_master']['rate_per_pulse_day_shift'];
                $ibn_pulse_rate = $PlanDetails[0]['plan_master']['rate_per_pulse_night_shift'];
                //$ifmp = ceil(60/$ib_pulse_sec);
                if(!empty($ib_pulse_sec)){$ifmp = ceil(60/$ib_pulse_sec);} else { $ifmp = 0;}

                //$ob_first_min = $PlanDetails[0]['plan_master']['ob_first_min'];
                $ob_pulse_sec = $PlanDetails[0]['plan_master']['pulse_outbound_call_shift'];
                $ob_pulse_rate = $PlanDetails[0]['plan_master']['rate_per_pulse_outbound_call_shift'];
                //$ofmp = ceil(60/$ob_pulse_sec); 
                if(!empty($ob_pulse_sec)){$ofmp = ceil(60/$ob_pulse_sec);} else { $ofmp = 0;}

                $bill_month = "";

                $InboundTotalTalkTime =0;
                $SMSTotal=0;

                $EmailTotal = 0;
                

                     //$qry_inb = "select if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`) length_in_sec, phone_number,call_date from `vicidial_closer_log` t2 left join vicidial_agent_log t3 on t2.uniqueid=t3.uniqueid where t2.user !='VDCL' and t2.campaign_id in ($Campagn) AND DATE(call_date) between '$fdate' AND '$ldate' ";


                     $qry_inb = "select if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`) length_in_sec, phone_number,call_date from `vicidial_closer_log` t2 left join vicidial_agent_log t3 on t2.uniqueid=t3.uniqueid where t2.user !='VDCL' $campaign_condition AND DATE(call_date) between '$fdate' AND '$ldate' ";

                    $this->vicidialCloserLog->useDbConfig = 'db2';
                    $all_inb_data = $this->vicidialCloserLog->query($qry_inb);                  
                    
                    // echo"<br>";
                    // print_r($all_inb_data); 
                    // die;

                    $inTotalSumaryUnit=0;
                    $inTotalSumaryUnitNight = 0;
                    $InTotalTalkTime =0;

                    $html1 ="";
                    $htmlD ="";

                    // print_r($all_inb_data); die;
                    if(!empty($all_inb_data))
                    {
                        foreach($all_inb_data as $inbDurArr)
                        {
                            //print_r($inbDurArr);


                            if(!empty($inbDurArr[0]['queue_seconds']))
                            {
                                $queue_seconds = $inbDurArr[0]['queue_seconds'];
                            }
                            else
                            {
                                $queue_seconds = 0;
                            }
                            
                            // $inbDuration=round($inbDurArr[0]['length_in_sec']-$inbDurArr[0]['queue_seconds']);

                             $inbDuration=round($inbDurArr[0]['length_in_sec']-$queue_seconds);

                            //die;
                            $amount = 0; 
                            $convrt_pulse = $inbDuration/$ib_pulse_sec;
                            if($ib_first_min=='1')
                            {
                                
                                // $minute = floor($inbDuration-$ifmp);
                                //echo $inbDuration;exit;
                                
                                if($convrt_pulse>$ifmp)
                                {
                                $subsequent = ($convrt_pulse-$ifmp); 
                                $total_pulse = $ifmp+ceil($subsequent);
                                }
                                else
                                {
                                $total_pulse = $ifmp;
                                }
                                
                                // echo $inbDurArr['t2']['call_date']; die;
                                
                                if(strtotime(date('H:i:s',strtotime($inbDurArr['t2']['call_date'])))>=strtotime('20:00:00') 
                                        || strtotime(date('H:i:s',strtotime($inbDurArr['t2']['call_date'])))<=strtotime('08:00:00'))
                                {
                                    $amount = $ibn_pulse_rate*$total_pulse; 
                                    //echo "ibn_"."$ibn_pulse_rate*$total_pulse";exit;
                                }
                                else
                                {
                                    $amount = $ib_pulse_rate*$total_pulse; 
                                    //echo "ib_"."$ib_pulse_rate*$total_pulse";exit;
                                }
                            }
                            else
                            {
                                if(!empty($ib_pulse_sec))
                                {

                                     $total_pulse = ceil($inbDuration/$ib_pulse_sec);  
                                }
                                else
                                {
                                    echo"basant";
                                    echo $inbDurArr['t2']['call_date'];
                                    $total_pulse = 0;
                                }
                               
                                if(strtotime(date('H:i:s',strtotime($inbDurArr['t2']['call_date'])))>=strtotime('20:00:00') 
                                        || strtotime(date('H:i:s',strtotime($inbDurArr['t2']['call_date'])))<strtotime('08:00:00'))
                                {
                                    $amount = $total_pulse*($ibn_pulse_rate);
                                }
                                else
                                {
                                    $amount = $total_pulse*($ib_pulse_rate);   
                                }
                            }
                            
                            $start_date1 = $start_date." 00:00:00"; 
                            $call_date = strtotime(date('Y-m-d H:i:s',strtotime($inbDurArr['t2']['call_date'])));

                            $period_arr = array();
                            $period_arr[1] =    $end_date." 23:59:59"; 
                            foreach($period_arr as $end_date)
                            {
                              //echo "{$inbDurArr[0]['call_date']}>=strtotime($start_date1) && {$inbDurArr[0]['call_date']} <$end_date"; exit;
                                if($call_date>=strtotime($start_date1) && $call_date<strtotime($end_date))
                                {
                                    $data[$end_date]['InTotalAmount'] += $amount;
                                    break;
                                }
                                else
                                {
                                    
                                    $start_date1 =   $end_date; 
                                }
                                $Inbnew_cycle_start = $start_date1; 
                                $Inbnew_cycle_end = $end_date;
                            }
                            //echo $inbDurArr['t2']['call_date']; die;
                            $inbDurArr[0]['Duration'] = $inbDuration;
                            $inbDurArr[0]['amount'] = $amount;
                            $inbDurArr[0]['unit'] = $total_pulse;
                            $inbData[$inbDurArr['t2']['call_date']][] = $inbDurArr;
                        }
                    }
                     //print_r($inbData); die;
                     if(!empty($inbData))
                     {
                            foreach($inbData as $call_date=>$inb_arr)
                            {
                                //print_r($call_date); die;

                                $call_date = substr($call_date,0,10); 

                                //print_r($inb_arr);
                                foreach($inb_arr as $inb)
                                {
                                    //print_r($inb); die;
                                
                                    if(strtotime($call_date)>=strtotime($Inbnew_cycle_start) && strtotime($call_date)>=strtotime($fdate))
                                    {
                                        $htmlD ="<tr>"; //exit;
                                        $htmlD .="<td>".date('Y-m-d',strtotime($inb['t2']['call_date']))."</td>"; 
                                        $htmlD .="<td>".date('H:i:s',strtotime($inb['t2']['call_date']))."</td>";
                                        $htmlD .="<td>".$inb['phone_number']."</td>";
                                        $htmlD .="<td>".$inb[0]['Duration']."</td>";
                                        $htmlD .="<td>".$inb['unit']."</td>";
                                        $htmlD .="<td>".round($inb['amount'],2)."</td>";
                                        $htmlD .="</tr>";
                                    
                                        
                                    
                                        if(strtotime(date('H:i:s',strtotime($inb['t2']['call_date'])))>=strtotime('20:00:00') 
                                                || strtotime(date('H:i:s',strtotime($inb['t2']['call_date'])))<strtotime('08:00:00'))
                                        {
                                            //$TinAmountNight = round($inb['amount'],2);
                                            // echo "basant";
                                            $html1N .= $htmlD;
                                            $inTotalSumaryUnitNight += $inb[0]['unit'];
                                            $InTotalTalkTimeNight += $inb[0]['Duration'];
                                            $InTotalPulseNight += $inb[0]['unit'];
                                            $InTotalTalkRateNight += round($inb[0]['amount'],2);
                                        }
                                        else
                                        {
                                            
                                            $html1 .= $htmlD;
                                            $inTotalSumaryUnit += $inb[0]['unit'];
                                            $InTotalTalkTime += $inb[0]['Duration'];
                                            $InTotalPulse += $inb[0]['unit'];
                                            $InTotalTalkRate += round($inb[0]['amount'],2);
                                        }
                                    
                                    }
                                    
                                }
                                
                                
                            }

                     }

                    
                    
                    //echo $html1; exit;
                    //print_r($data); exit;
                    //$html .="<tr><td colspan='3' ><b>Total</b></td><td><b>{$InTotalPulse}</b></td><td><b>{$InTotalAmount}</b></td></tr>";
                   
                   
                    // $html1 .="<tr><td colspan='3' align=\"right\"><b>Total</b></td>";
                    // $html1 .="<td><b> {$InTotalTalkTime}</b></td>";
                    // $html1 .="<td><b> {$InTotalPulse}</b></td>";
                    // $html1 .="<td><b> ".round($InTotalTalkRate,2)."</b></td>";
                    // $html1 .="</tr></table>";

                    //echo $html1;

                    // echo $InTotalTalkTime;
                    // echo"abc";
                    // echo $InTotalTalkTimeNight;
                    // echo "sdfsd";
                    // echo $inTotalSumaryUnit ;
                    //die;
                  
                    

                    
                     $qeyy = "SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='SMS' AND date(CallDate)  between '$fdate' AND '$ldate'";
                     
                    $SMSDetails = $this->BalanceMaster->query($qeyy);  
                    
                    // print_r($SMSDetails); 
                    
                    // die;

                    $SMSTotal=0;
                    foreach($SMSDetails as $sssdetail)
                   {
                       
                      $SMSTotal += $sssdetail['billing_master']['Unit'];  
                   }
                       // echo $SMSTotal;


                   // For Email

                   
                   $EmailDetails = $this->BalanceMaster->query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='Email' AND date(CallDate) between '$fdate' AND '$ldate' ");
   
                   $EmailTotal=0;
                   foreach($EmailDetails as $edetail)
                  {
                      
                     $EmailTotal += $edetail['billing_master']['Unit'];  
                  }
                      // echo $EmailTotal;
                 


                  // for outbound details

                // unit consumed

                if(empty($inTotalSumaryUnit))
                {
                    $inTotalSumaryUnit = 0;
                }
                if(empty($InTotalPulseNight))
                {
                    $InTotalPulseNight = 0;
                }
                if(empty($SMSTotal))
                {
                    $SMSTotal = 0;
                }
                if(empty($EmailTotal))
                {
                    $EmailTotal = 0;
                }
               


                        
                    $unit_Inbound_Call=$inTotalSumaryUnit;
                    $unit_Outbound_Call=0;
                    $unit_Night_Shift_ib=$InTotalPulseNight;
                    $unit_SMS=$SMSTotal;
                    $unit_EMAIL=$EmailTotal;
                    $unit_MISSCALL=0;
                    $unit_Call_Forwarding=0;
                    $unit_IVR_Automation = 0;


                    // Plan master  for per unit rate


                    $Inbound_Call=$row['pm']['InboundCallCharge'];
                    $Outbound_Call=$row['pm']['OutboundCallCharge'];
                    $Night_Shift_ib=$row['pm']['InboundCallChargeNight'];
                    $SMS=$row['pm']['SMSCharge'];
                    $EMAIL=$row['pm']['EmailCharge'];
                    $MISSCALL=$row['pm']['MissCallCharge'];
                    $Call_Forwarding=$row['pm']['VFOCallCharge'];
                    $IVR_Automation=$row['pm']['IVR_Charge'];
                    


                    // value

                    
                    $value_Inbound_Call=$unit_Inbound_Call*$Inbound_Call;
                    $value_Outbound_Call=$unit_Outbound_Call*$Outbound_Call;
                    $value_Night_Shift_ib=$unit_Night_Shift_ib*$Night_Shift_ib;
                    $value_SMS=$unit_SMS*$SMS;
                    $value_EMAIL=$unit_EMAIL*$EMAIL;
                    $value_MISSCALL=$unit_MISSCALL*$MISSCALL;
                    $value_Call_Forwarding=$unit_Call_Forwarding*$Call_Forwarding;
                    $value_IVR_Automation = $unit_IVR_Automation*$IVR_Automation;
                    
                ?>	
                    <tr>
                        <td><?php echo $row['rm']['company_name'];?></td>
                        <td><?php echo date_format(date_create($fdate),"d-M-Y");?></td>
                        <td><?php echo date_format(date_create($ldate),"d-M-Y");?></td>
                        <td><?php echo $unit_Inbound_Call;?></td>
                        <td><?php echo $unit_Outbound_Call;?></td>
                        <td><?php echo $unit_Night_Shift_ib; ?></td>
                        <td><?php echo $unit_SMS; ?></td>
                        <td><?php echo $unit_EMAIL; ?></td>
                        <td><?php echo $unit_MISSCALL; ?></td>
                        <td><?php echo $unit_Call_Forwarding; ?></td>
                        <td><?php echo $unit_IVR_Automation; ?></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $Inbound_Call;?></td>
                        <td><?php echo $Outbound_Call;?></td>
                        <td><?php echo $Night_Shift_ib; ?></td>
                        <td><?php echo $SMS; ?></td>
                        <td><?php echo $EMAIL; ?></td>
                        <td><?php echo $MISSCALL; ?></td>
                        <td><?php echo $Call_Forwarding; ?></td>
                        <td><?php echo $IVR_Automation; ?></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $value_Inbound_Call;?></td>
                        <td><?php echo $value_Outbound_Call;?></td>
                        <td><?php echo $value_Night_Shift_ib; ?></td>
                        <td><?php echo $value_SMS; ?></td>
                        <td><?php echo $value_EMAIL; ?></td>
                        <td><?php echo $value_MISSCALL; ?></td>
                        <td><?php echo $value_Call_Forwarding; ?></td>
                        <td><?php echo $value_IVR_Automation; ?></td>

                    </tr>
                <?php } ?>
                
            </table>
            <?php
            exit;
        }
        

    }

    public function export_combined_allreport()
    {
        $this->layout = "ajax";     

        if($this->request->is('Post'))
        {
            
            // header("Content-type: application/octet-stream");
            // header("Content-Disposition: attachment; filename=combinedReportofAllClient.xls");
            // header("Pragma: no-cache");
            // header("Expires: 0");
            
            $fdate=$this->request->data['FromDate'];
            $ldate=$this->request->data['ToDate'];



            // $all_dt = $this->RegistrationMaster->query("SELECT * FROM balance_master bm 
            // INNER JOIN plan_master pm ON bm.PlanId = pm.id 
            // INNER JOIN registration_master rm ON bm.clientId = rm.company_id  order by company_name limit 20 ");

            // $all_dt = $this->RegistrationMaster->query("SELECT * FROM balance_master bm 
            // INNER JOIN plan_master pm ON bm.PlanId = pm.id 
            // INNER JOIN registration_master rm ON bm.clientId = 199  order by company_name limit 20 ");

              //Akai India

            $all_dt = $this->RegistrationMaster->query("SELECT * FROM balance_master bm 
            INNER JOIN plan_master pm ON bm.PlanId = pm.id 
            INNER JOIN registration_master rm ON bm.clientId = rm.company_id WHERE rm.status='A' ORDER BY company_name");


            //$all_dt = $this->RegistrationMaster->query("SELECT * FROM  registration_master rm WHERE rm.status='A' ORDER BY company_name");


             //print_r($all_dt);  die;
             
            //   $clientId = $all_dt[0]['rm']['company_id'];

            //   echo $Campagn = $all_dt[0]['rm']['campaignid'];


            ?>        
            <table border="1">
                <tr>
                    <th>Client Name</th>
                    <th>From</th>
                    <th>To</th>

                    <th>Inbound Call</th>
                    <th>Outbound Call</th>
                    <th>Night Shift l/b</th>
                    <th>SMS</th>
                    <th>EMAIL</th>
                    <th>MISSCALL</th>
                    <th>Call Forwarding</th>
                    <th>IVR Automation</th>

                    <th></th>
                    <th></th>
                    <th>Inbound Call</th>
                    <th>Outbound Call</th>
                    <th>Night Shift l/b</th>
                    <th>SMS</th>
                    <th>EMAIL</th>
                    <th>MISSCALL</th>
                    <th>Call Forwarding</th>
                    <th>IVR Automation</th>

                    <th></th>
                    <th></th>
                    <th>Inbound Call</th>
                    <th>Outbound Call</th>
                    <th>Night Shift l/b</th>
                    <th>SMS</th>
                    <th>EMAIL</th>
                    <th>MISSCALL</th>
                    <th>Call Forwarding</th>
                    <th>IVR Automation</th>
                </tr>
                <?php     
                
                      // $clientId = 199;
                   $clientId = 301;

                
                   //$Campagn = "'motherson','E_Motherson','H_Motherson'";
                   $Campagn = "'AKAI'";

                  


                   if(!empty($Campagn))
                   {
                      $campaign_condition = " AND t2.campaign_id IN ($Campagn)";

                      $vicidial_campaign_condition = " and v.campaign_id in ($Campagn)";
                      
                   }
                   else
                   {
                      $campaign_condition = ' ';
                      $vicidial_campaign_condition = ' ';
                   }

                  if(!empty($Campagn) && !empty($clientId))
                  {

                      $BalanceMaster = $this->BalanceMaster->query("select * from `balance_master` where clientId='$clientId'  limit 1");

                      $PlanDetails = $this->PlanMaster->query("select * from `plan_master` where Id='{$row['bm']['PlanId']}' limit 1");

                      $start_date = $row['bm']['start_date']; 
                      $end_date = $row['bm']['end_date'];
                      $balance = $row['bm']['MainBalance'];

                      $PeriodType = strtolower($row['pm']['PeriodType']);


                      
                      if($row['pm']['first_minute']=='Enable')
                      {
                          //$ib_first_min = $row['pm']['ib_first_min'];
                          $ib_first_min='1';
                          $ob_first_min='1';
                      }
                      else
                      {
                          $ib_first_min='0';
                          $ob_first_min='0';
                      }
                      $ib_pulse_sec = $row['pm']['pulse_day_shift'];
                      $ibn_pulse_sec = $row['pm']['pulse_night_shift'];
                      $ib_pulse_rate = $row['pm']['rate_per_pulse_day_shift'];
                      $ibn_pulse_rate = $row['pm']['rate_per_pulse_night_shift'];
                      //$ifmp = ceil(60/$ib_pulse_sec);
                      if(!empty($ib_pulse_sec)){$ifmp = ceil(60/$ib_pulse_sec);} else { $ifmp = 0;}

                      //$ob_first_min = $row['pm']['ob_first_min'];
                      $ob_pulse_sec = $row['pm']['pulse_outbound_call_shift'];
                      $ob_pulse_rate = $row['pm']['rate_per_pulse_outbound_call_shift'];
                      //$ofmp = ceil(60/$ob_pulse_sec); 
                      if(!empty($ob_pulse_sec)){$ofmp = ceil(60/$ob_pulse_sec);} else { $ofmp = 0;}
                  
                      

                      $bill_month = "";

                      $InboundTotalTalkTime =0;
                      $SMSTotal=0;

                      $EmailTotal = 0;


                          //$qry_inb = "select if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`) length_in_sec, phone_number,call_date from `vicidial_closer_log` t2 left join vicidial_agent_log t3 on t2.uniqueid=t3.uniqueid where t2.user !='VDCL' and t2.campaign_id in ($Campagn) AND DATE(call_date) between '$fdate' AND '$ldate' ";


                          $qry_inb = "select if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`) length_in_sec, phone_number,call_date from `vicidial_closer_log` t2 left join vicidial_agent_log t3 on t2.uniqueid=t3.uniqueid where t2.user !='VDCL' $campaign_condition AND DATE(call_date) between '$fdate' AND '$ldate' ";

                          $this->vicidialCloserLog->useDbConfig = 'db2';
                          $all_inb_data = $this->vicidialCloserLog->query($qry_inb);                  
                          
                          //  echo"<br>";
                          //  print_r($all_inb_data); 
                          //  die;

                          $inTotalSumaryUnit=0;
                          $inTotalSumaryUnitNight = 0;
                          $InTotalTalkTime =0;

                          $html1 ="";
                          $htmlD ="";

                          // problem comes after this
                          
                          if(!empty($all_inb_data))
                          {
                              foreach($all_inb_data as $inbDurArr)
                              {
                                  //print_r($inbDurArr);


                                  if(!empty($inbDurArr[0]['queue_seconds']))
                                  {
                                      $queue_seconds = $inbDurArr[0]['queue_seconds'];
                                  }
                                  else
                                  {
                                      $queue_seconds = 0;
                                  }
                                  
                                  // $inbDuration=round($inbDurArr[0]['length_in_sec']-$inbDurArr[0]['queue_seconds']);

                                  $inbDuration=round($inbDurArr[0]['length_in_sec']-$queue_seconds);

                                  //die;
                                  $amount = 0; 
                                  $convrt_pulse = $inbDuration/$ib_pulse_sec;
                                  if($ib_first_min=='1')
                                  {
                                      
                                      // $minute = floor($inbDuration-$ifmp);
                                      //echo $inbDuration;exit;
                                      
                                      if($convrt_pulse>$ifmp)
                                      {
                                          $subsequent = ($convrt_pulse-$ifmp); 
                                          $total_pulse = $ifmp+ceil($subsequent);
                                      }
                                      else
                                      {
                                          $total_pulse = $ifmp;
                                      }
                                      
                                      // echo $inbDurArr['t2']['call_date']; die;
                                      
                                      if(strtotime(date('H:i:s',strtotime($inbDurArr['t2']['call_date'])))>=strtotime('20:00:00') 
                                              || strtotime(date('H:i:s',strtotime($inbDurArr['t2']['call_date'])))<=strtotime('08:00:00'))
                                      {
                                          $amount = $ibn_pulse_rate*$total_pulse; 
                                          //echo "ibn_"."$ibn_pulse_rate*$total_pulse";exit;
                                      }
                                      else
                                      {
                                          $amount = $ib_pulse_rate*$total_pulse; 
                                          //echo "ib_"."$ib_pulse_rate*$total_pulse";exit;
                                      }
                                  }
                                  else
                                  {
                                      if(!empty($ib_pulse_sec))
                                      {

                                          $total_pulse = ceil($inbDuration/$ib_pulse_sec);  
                                      }
                                      else
                                      {
                                          // echo"basant";
                                          // echo $inbDurArr['t2']['call_date'];
                                          $total_pulse = 0;
                                      }
                                  
                                      if(strtotime(date('H:i:s',strtotime($inbDurArr['t2']['call_date'])))>=strtotime('20:00:00') 
                                              || strtotime(date('H:i:s',strtotime($inbDurArr['t2']['call_date'])))<strtotime('08:00:00'))
                                      {
                                          $amount = $total_pulse*($ibn_pulse_rate);
                                      }
                                      else
                                      {
                                          $amount = $total_pulse*($ib_pulse_rate);   
                                      }
                                  }
                                  
                                  $start_date1 = $start_date." 00:00:00"; 
                                  $call_date = strtotime(date('Y-m-d H:i:s',strtotime($inbDurArr['t2']['call_date'])));

                                  $period_arr = array();
                                  $period_arr[1] =    $end_date." 23:59:59"; 
                                  foreach($period_arr as $end_date)
                                  {
                                  //echo "{$inbDurArr[0]['call_date']}>=strtotime($start_date1) && {$inbDurArr[0]['call_date']} <$end_date"; exit;
                                      if($call_date>=strtotime($start_date1) && $call_date<strtotime($end_date))
                                      {
                                          //$data[$end_date]['InTotalAmount'] += $amount;
                                          break;
                                      }
                                      else
                                      {
                                          
                                          $start_date1 =   $end_date; 
                                      }
                                      $Inbnew_cycle_start = $start_date1; 
                                      $Inbnew_cycle_end = $end_date;
                                  }
                                  //echo $inbDurArr['t2']['call_date']; die;
                                  $inbDurArr[0]['Duration'] = $inbDuration;
                                  $inbDurArr[0]['amount'] = $amount;
                                  $inbDurArr[0]['unit'] = $total_pulse;
                                  $inbData[$inbDurArr['t2']['call_date']][] = $inbDurArr;
                              }
                          }
                          //print_r($inbData); die;

                          $html1N ='';
                          $InTotalTalkTimeNight=0;
                          $InTotalPulseNight=0;
                          $InTotalTalkRateNight=0;
                          $InTotalPulse=0;
                          $InTotalTalkRate=0;
                          

                          if(!empty($inbData))
                          {
                                  foreach($inbData as $call_date=>$inb_arr)
                                  {
                                      //print_r($call_date); die;

                                      $call_date = substr($call_date,0,10); 

                                      //print_r($inb_arr);
                                      foreach($inb_arr as $inb)
                                      {
                                          //print_r($inb); die;
                                      
                                          if(strtotime($call_date)>=strtotime($Inbnew_cycle_start) && strtotime($call_date)>=strtotime($fdate))
                                          {
                                              // $htmlD ="<tr>"; //exit;
                                              // $htmlD .="<td>".date('Y-m-d',strtotime($inb['t2']['call_date']))."</td>"; 
                                              // $htmlD .="<td>".date('H:i:s',strtotime($inb['t2']['call_date']))."</td>";
                                              // $htmlD .="<td>".$inb['t2']['phone_number']."</td>";
                                              // $htmlD .="<td>".$inb[0]['Duration']."</td>";
                                              // $htmlD .="<td>".$inb[0]['unit']."</td>";
                                              // $htmlD .="<td>".round($inb[0]['amount'],2)."</td>";
                                              // $htmlD .="</tr>";
                                          
                                              
                                          
                                              if(strtotime(date('H:i:s',strtotime($inb['t2']['call_date'])))>=strtotime('20:00:00') 
                                                      || strtotime(date('H:i:s',strtotime($inb['t2']['call_date'])))<strtotime('08:00:00'))
                                              {
                                                  //$TinAmountNight = round($inb['amount'],2);
                                                  // echo "basant";
                                                  // $html1N .= $htmlD;
                                                  $inTotalSumaryUnitNight += $inb[0]['unit'];
                                                  $InTotalTalkTimeNight += $inb[0]['Duration'];
                                                  $InTotalPulseNight += $inb[0]['unit'];
                                                  $InTotalTalkRateNight += round($inb[0]['amount'],2);
                                              }
                                              else
                                              {
                                                  
                                                  $html1 .= $htmlD;
                                                  $inTotalSumaryUnit += $inb[0]['unit'];
                                                  $InTotalTalkTime += $inb[0]['Duration'];
                                                  $InTotalPulse += $inb[0]['unit'];
                                                  $InTotalTalkRate += round($inb[0]['amount'],2);
                                              }
                                          
                                          }
                                          
                                      }
                                      
                                      
                                  }

                          }



                          //echo $html1; exit;
                          //print_r($data); exit;
                          //$html .="<tr><td colspan='3' ><b>Total</b></td><td><b>{$InTotalPulse}</b></td><td><b>{$InTotalAmount}</b></td></tr>";


                          $html1 .="<tr><td colspan='3' align=\"right\"><b>Total</b></td>";
                          $html1 .="<td><b> {$InTotalTalkTime}</b></td>";
                          $html1 .="<td><b> {$InTotalPulse}</b></td>";
                          $html1 .="<td><b> ".round($InTotalTalkRate,2)."</b></td>";
                          $html1 .="</tr></table>";

                          echo $html1;

                          echo $InTotalTalkTime;
                           echo"<br>";
                          echo $InTotalPulse;
                          echo"<br>";
                           echo $InTotalPulseNight;
                          echo "<br>";
                          echo $InTotalTalkTimeNight;
                          echo "<br>";
                          echo $inTotalSumaryUnitNight ;
                          
                          die;

                     }



                foreach($all_dt as $row)
                {


                    $clientId = $row['rm']['company_id']; 

                    $Campagn = $row['rm']['campaignid'];

                    // $clientId = 199;
                   $clientId = 301;

                
                     //$Campagn = "'motherson','E_Motherson','H_Motherson'";
                     $Campagn = "'AKAI'";

                    


                     if(!empty($Campagn))
                     {
                        $campaign_condition = " AND t2.campaign_id IN ($Campagn)";

                        $vicidial_campaign_condition = " and v.campaign_id in ($Campagn)";
                        
                     }
                     else
                     {
                        $campaign_condition = ' ';
                        $vicidial_campaign_condition = ' ';
                     }

                    if(!empty($Campagn) && !empty($clientId))
                    {

                        $BalanceMaster = $this->BalanceMaster->query("select * from `balance_master` where clientId='$clientId'  limit 1");

                        $PlanDetails = $this->PlanMaster->query("select * from `plan_master` where Id='{$row['bm']['PlanId']}' limit 1");

                        $start_date = $row['bm']['start_date']; 
                        $end_date = $row['bm']['end_date'];
                        $balance = $row['bm']['MainBalance'];

                        $PeriodType = strtolower($row['pm']['PeriodType']);


                        
                        if($row['pm']['first_minute']=='Enable')
                        {
                            //$ib_first_min = $row['pm']['ib_first_min'];
                            $ib_first_min='1';
                            $ob_first_min='1';
                        }
                        else
                        {
                            $ib_first_min='0';
                            $ob_first_min='0';
                        }
                        $ib_pulse_sec = $row['pm']['pulse_day_shift'];
                        $ibn_pulse_sec = $row['pm']['pulse_night_shift'];
                        $ib_pulse_rate = $row['pm']['rate_per_pulse_day_shift'];
                        $ibn_pulse_rate = $row['pm']['rate_per_pulse_night_shift'];
                        //$ifmp = ceil(60/$ib_pulse_sec);
                        if(!empty($ib_pulse_sec)){$ifmp = ceil(60/$ib_pulse_sec);} else { $ifmp = 0;}

                        //$ob_first_min = $row['pm']['ob_first_min'];
                        $ob_pulse_sec = $row['pm']['pulse_outbound_call_shift'];
                        $ob_pulse_rate = $row['pm']['rate_per_pulse_outbound_call_shift'];
                        //$ofmp = ceil(60/$ob_pulse_sec); 
                        if(!empty($ob_pulse_sec)){$ofmp = ceil(60/$ob_pulse_sec);} else { $ofmp = 0;}
                    
                        

                        $bill_month = "";

                        $InboundTotalTalkTime =0;
                        $SMSTotal=0;

                        $EmailTotal = 0;


                            //$qry_inb = "select if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`) length_in_sec, phone_number,call_date from `vicidial_closer_log` t2 left join vicidial_agent_log t3 on t2.uniqueid=t3.uniqueid where t2.user !='VDCL' and t2.campaign_id in ($Campagn) AND DATE(call_date) between '$fdate' AND '$ldate' ";


                            $qry_inb = "select if(t3.`talk_sec` is null,t2.length_in_sec,t3.`talk_sec`) length_in_sec, phone_number,call_date from `vicidial_closer_log` t2 left join vicidial_agent_log t3 on t2.uniqueid=t3.uniqueid where t2.user !='VDCL' $campaign_condition AND DATE(call_date) between '$fdate' AND '$ldate' ";

                            $this->vicidialCloserLog->useDbConfig = 'db2';
                            $all_inb_data = $this->vicidialCloserLog->query($qry_inb);                  
                            
                            //  echo"<br>";
                            //  print_r($all_inb_data); 
                            //  die;

                            $inTotalSumaryUnit=0;
                            $inTotalSumaryUnitNight = 0;
                            $InTotalTalkTime =0;

                            $html1 ="";
                            $htmlD ="";

                            // problem comes after this
                            
                            if(!empty($all_inb_data))
                            {
                                foreach($all_inb_data as $inbDurArr)
                                {
                                    //print_r($inbDurArr);


                                    if(!empty($inbDurArr[0]['queue_seconds']))
                                    {
                                        $queue_seconds = $inbDurArr[0]['queue_seconds'];
                                    }
                                    else
                                    {
                                        $queue_seconds = 0;
                                    }
                                    
                                    // $inbDuration=round($inbDurArr[0]['length_in_sec']-$inbDurArr[0]['queue_seconds']);

                                    $inbDuration=round($inbDurArr[0]['length_in_sec']-$queue_seconds);

                                    //die;
                                    $amount = 0; 
                                    $convrt_pulse = $inbDuration/$ib_pulse_sec;
                                    if($ib_first_min=='1')
                                    {
                                        
                                        // $minute = floor($inbDuration-$ifmp);
                                        //echo $inbDuration;exit;
                                        
                                        if($convrt_pulse>$ifmp)
                                        {
                                            $subsequent = ($convrt_pulse-$ifmp); 
                                            $total_pulse = $ifmp+ceil($subsequent);
                                        }
                                        else
                                        {
                                            $total_pulse = $ifmp;
                                        }
                                        
                                        // echo $inbDurArr['t2']['call_date']; die;
                                        
                                        if(strtotime(date('H:i:s',strtotime($inbDurArr['t2']['call_date'])))>=strtotime('20:00:00') 
                                                || strtotime(date('H:i:s',strtotime($inbDurArr['t2']['call_date'])))<=strtotime('08:00:00'))
                                        {
                                            $amount = $ibn_pulse_rate*$total_pulse; 
                                            //echo "ibn_"."$ibn_pulse_rate*$total_pulse";exit;
                                        }
                                        else
                                        {
                                            $amount = $ib_pulse_rate*$total_pulse; 
                                            //echo "ib_"."$ib_pulse_rate*$total_pulse";exit;
                                        }
                                    }
                                    else
                                    {
                                        if(!empty($ib_pulse_sec))
                                        {

                                            $total_pulse = ceil($inbDuration/$ib_pulse_sec);  
                                        }
                                        else
                                        {
                                            // echo"basant";
                                            // echo $inbDurArr['t2']['call_date'];
                                            $total_pulse = 0;
                                        }
                                    
                                        if(strtotime(date('H:i:s',strtotime($inbDurArr['t2']['call_date'])))>=strtotime('20:00:00') 
                                                || strtotime(date('H:i:s',strtotime($inbDurArr['t2']['call_date'])))<strtotime('08:00:00'))
                                        {
                                            $amount = $total_pulse*($ibn_pulse_rate);
                                        }
                                        else
                                        {
                                            $amount = $total_pulse*($ib_pulse_rate);   
                                        }
                                    }
                                    
                                    $start_date1 = $start_date." 00:00:00"; 
                                    $call_date = strtotime(date('Y-m-d H:i:s',strtotime($inbDurArr['t2']['call_date'])));

                                    $period_arr = array();
                                    $period_arr[1] =    $end_date." 23:59:59"; 
                                    foreach($period_arr as $end_date)
                                    {
                                    //echo "{$inbDurArr[0]['call_date']}>=strtotime($start_date1) && {$inbDurArr[0]['call_date']} <$end_date"; exit;
                                        if($call_date>=strtotime($start_date1) && $call_date<strtotime($end_date))
                                        {
                                            //$data[$end_date]['InTotalAmount'] += $amount;
                                            break;
                                        }
                                        else
                                        {
                                            
                                            $start_date1 =   $end_date; 
                                        }
                                        $Inbnew_cycle_start = $start_date1; 
                                        $Inbnew_cycle_end = $end_date;
                                    }
                                    //echo $inbDurArr['t2']['call_date']; die;
                                    $inbDurArr[0]['Duration'] = $inbDuration;
                                    $inbDurArr[0]['amount'] = $amount;
                                    $inbDurArr[0]['unit'] = $total_pulse;
                                    $inbData[$inbDurArr['t2']['call_date']][] = $inbDurArr;
                                }
                            }
                            //print_r($inbData); die;

                            $html1N ='';
                            $InTotalTalkTimeNight=0;
                            $InTotalPulseNight=0;
                            $InTotalTalkRateNight=0;
                            $InTotalPulse=0;
                            $InTotalTalkRate=0;

                            if(!empty($inbData))
                            {
                                    foreach($inbData as $call_date=>$inb_arr)
                                    {
                                        //print_r($call_date); die;

                                        $call_date = substr($call_date,0,10); 

                                        //print_r($inb_arr);
                                        foreach($inb_arr as $inb)
                                        {
                                            //print_r($inb); die;
                                        
                                            if(strtotime($call_date)>=strtotime($Inbnew_cycle_start) && strtotime($call_date)>=strtotime($fdate))
                                            {
                                                // $htmlD ="<tr>"; //exit;
                                                // $htmlD .="<td>".date('Y-m-d',strtotime($inb['t2']['call_date']))."</td>"; 
                                                // $htmlD .="<td>".date('H:i:s',strtotime($inb['t2']['call_date']))."</td>";
                                                // $htmlD .="<td>".$inb['t2']['phone_number']."</td>";
                                                // $htmlD .="<td>".$inb[0]['Duration']."</td>";
                                                // $htmlD .="<td>".$inb[0]['unit']."</td>";
                                                // $htmlD .="<td>".round($inb[0]['amount'],2)."</td>";
                                                // $htmlD .="</tr>";
                                            
                                                
                                            
                                                if(strtotime(date('H:i:s',strtotime($inb['t2']['call_date'])))>=strtotime('20:00:00') 
                                                        || strtotime(date('H:i:s',strtotime($inb['t2']['call_date'])))<strtotime('08:00:00'))
                                                {
                                                    //$TinAmountNight = round($inb['amount'],2);
                                                    // echo "basant";
                                                    // $html1N .= $htmlD;
                                                    $inTotalSumaryUnitNight += $inb[0]['unit'];
                                                    $InTotalTalkTimeNight += $inb[0]['Duration'];
                                                    $InTotalPulseNight += $inb[0]['unit'];
                                                    $InTotalTalkRateNight += round($inb[0]['amount'],2);
                                                }
                                                else
                                                {
                                                    
                                                    // $html1 .= $htmlD;
                                                    $inTotalSumaryUnit += $inb[0]['unit'];
                                                    $InTotalTalkTime += $inb[0]['Duration'];
                                                    $InTotalPulse += $inb[0]['unit'];
                                                    $InTotalTalkRate += round($inb[0]['amount'],2);
                                                }
                                            
                                            }
                                            
                                        }
                                        
                                        
                                    }

                            }



                            //echo $html1; exit;
                            //print_r($data); exit;
                            //$html .="<tr><td colspan='3' ><b>Total</b></td><td><b>{$InTotalPulse}</b></td><td><b>{$InTotalAmount}</b></td></tr>";


                            // $html1 .="<tr><td colspan='3' align=\"right\"><b>Total</b></td>";
                            // $html1 .="<td><b> {$InTotalTalkTime}</b></td>";
                            // $html1 .="<td><b> {$InTotalPulse}</b></td>";
                            // $html1 .="<td><b> ".round($InTotalTalkRate,2)."</b></td>";
                            // $html1 .="</tr></table>";

                            //echo $html1;

                            // echo $InTotalTalkTime;
                            // echo"abc";
                            // echo $InTotalTalkTimeNight;
                            // echo "sdfsd";
                            // echo $inTotalSumaryUnit ;
                            //die;



                        

                            
                            // problem end here




                        // for sms
                        
                        $qeyy = "SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='SMS' AND date(CallDate)  between '$fdate' AND '$ldate'";
                        
                        $SMSDetails = $this->BalanceMaster->query($qeyy);  
                        
                        // print_r($SMSDetails); 
                        
                        // die;

                        $SMSTotal=0;
                        foreach($SMSDetails as $sssdetail)
                        {
                            
                            $SMSTotal += $sssdetail['billing_master']['Unit'];  
                        }
                            // echo $SMSTotal;


                        // For Email

                        
                        $EmailDetails = $this->BalanceMaster->query("SELECT DATE_FORMAT(CallDate,'%d %b %y') `CallDate`,CallTime,CallFrom,Unit FROM `billing_master` WHERE clientId='$clientId' AND DedType='Email' AND date(CallDate) between '$fdate' AND '$ldate' ");
        
                        $EmailTotal=0;
                        foreach($EmailDetails as $edetail)
                            {
                                
                                $EmailTotal += $edetail['billing_master']['Unit'];  
                            }
                                // echo $EmailTotal;
                            


                            // for outbound details

                                // unit consumed

                                // if(empty($inTotalSumaryUnit))
                                // {
                                //     $inTotalSumaryUnit = 0;
                                // }
                                // if(empty($InTotalPulseNight))
                                // {
                                //     $InTotalPulseNight = 0;
                                // }
                                if(empty($SMSTotal))
                                {
                                    $SMSTotal = 0;
                                }
                                if(empty($EmailTotal))
                                {
                                    $EmailTotal = 0;
                                }
                    


                            
                        $unit_Inbound_Call=@$inTotalSumaryUnit;
                        $unit_Outbound_Call=0;
                        $unit_Night_Shift_ib=@$InTotalPulseNight;
                        $unit_SMS=$SMSTotal;
                        $unit_EMAIL=$EmailTotal;
                        $unit_MISSCALL=0;
                        $unit_Call_Forwarding=0;
                        $unit_IVR_Automation = 0;


                        // Plan master  for per unit rate


                        $Inbound_Call=$row['pm']['InboundCallCharge'];
                        $Outbound_Call=$row['pm']['OutboundCallCharge'];
                        $Night_Shift_ib=$row['pm']['InboundCallChargeNight'];
                        $SMS=$row['pm']['SMSCharge'];
                        $EMAIL=$row['pm']['EmailCharge'];
                        $MISSCALL=$row['pm']['MissCallCharge'];
                        $Call_Forwarding=$row['pm']['VFOCallCharge'];
                        $IVR_Automation=$row['pm']['IVR_Charge'];

                        if(empty($Inbound_Call))
                        {
                            $Inbound_Call = 0;
                        }
                        if(empty($Outbound_Call))
                        {
                            $Outbound_Call = 0;
                        }
                        if(empty($Night_Shift_ib))
                        {
                            $Night_Shift_ib = 0;
                        }
                        if(empty($SMS))
                        {
                            $SMS = 0;
                        }
                        if(empty($EMAIL))
                        {
                            $EMAIL = 0;
                        }
                        if(empty($MISSCALL))
                        {
                            $MISSCALL = 0;
                        }
                        if(empty($Call_Forwarding))
                        {
                            $Call_Forwarding = 0;
                        }
                        if(empty($IVR_Automation))
                        {
                            $IVR_Automation = 0;
                        }
                        


                        // value

                        
                        $value_Inbound_Call=$unit_Inbound_Call*$Inbound_Call;
                        $value_Outbound_Call=$unit_Outbound_Call*$Outbound_Call;
                        $value_Night_Shift_ib=$unit_Night_Shift_ib*$Night_Shift_ib;
                        $value_SMS=$unit_SMS*$SMS;
                        $value_EMAIL=$unit_EMAIL*$EMAIL;
                        $value_MISSCALL=$unit_MISSCALL*$MISSCALL;
                        $value_Call_Forwarding=$unit_Call_Forwarding*$Call_Forwarding;
                        $value_IVR_Automation = $unit_IVR_Automation*$IVR_Automation;
                        
                        ?>	
                        <tr>
                            <td><?php echo $row['rm']['company_name'];?></td>
                            <td><?php echo date_format(date_create($fdate),"d-M-Y");?></td>
                            <td><?php echo date_format(date_create($ldate),"d-M-Y");?></td>
                            <td><?php echo $unit_Inbound_Call;?></td>
                            <td><?php echo $unit_Outbound_Call;?></td>
                            <td><?php echo $unit_Night_Shift_ib; ?></td>
                            <td><?php echo $unit_SMS; ?></td>
                            <td><?php echo $unit_EMAIL; ?></td>
                            <td><?php echo $unit_MISSCALL; ?></td>
                            <td><?php echo $unit_Call_Forwarding; ?></td>
                            <td><?php echo $unit_IVR_Automation; ?></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $Inbound_Call;?></td>
                            <td><?php echo $Outbound_Call;?></td>
                            <td><?php echo $Night_Shift_ib; ?></td>
                            <td><?php echo $SMS; ?></td>
                            <td><?php echo $EMAIL; ?></td>
                            <td><?php echo $MISSCALL; ?></td>
                            <td><?php echo $Call_Forwarding; ?></td>
                            <td><?php echo $IVR_Automation; ?></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $value_Inbound_Call;?></td>
                            <td><?php echo $value_Outbound_Call;?></td>
                            <td><?php echo $value_Night_Shift_ib; ?></td>
                            <td><?php echo $value_SMS; ?></td>
                            <td><?php echo $value_EMAIL; ?></td>
                            <td><?php echo $value_MISSCALL; ?></td>
                            <td><?php echo $value_Call_Forwarding; ?></td>
                            <td><?php echo $value_IVR_Automation; ?></td>

                        </tr>
                <?php } ?>

                <?php } ?>
                
            </table>
            <?php
            exit;
        }
        

    }
}
?>