<?php
class ObAttemptwiseReportsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('CampaignName','ObecrMaster','ObField','ObFieldValue','ObcallMasterout','CallMasterOut','ObCatMaster',
    'ObField','ObAllocationMaster','CampaignName','ObCloseFieldData','ObCloseFieldDataValue','LogincreationMaster');

    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid")){
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
	
    public function index() {            
        $this->layout='user';
	$clientId = $this->Session->read('companyid');
        
        if($this->Session->read("role") =="agent"){
            $username       =   $this->Session->read("agent_username");
            $UserArr        =   $this->LogincreationMaster->find('first',array('conditions' =>array('username'=>$username,'create_id' =>$clientId)));
            $outboundAccess =   explode(',',$UserArr['LogincreationMaster']['outbound_access']);
            $Campaign       =   $this->CampaignName->find('list',array('fields'=>array("CampaignParentName","CampaignParentName"),'conditions'=>array('ClientId'=>$clientId,'CampaignStatus'=>'A','id'=>$outboundAccess),'group'=>'CampaignParentName'));
        }
        else{
          $Campaign =$this->CampaignName->find('list',array('fields'=>array("CampaignParentName","CampaignParentName"),'conditions'=>array('ClientId'=>$clientId,'CampaignStatus'=>'A'),'group'=>'CampaignParentName'));  
        }
        
        $this->set('Campaign',$Campaign);
           
        $fieldName      =   $this->ObField->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
        $fieldValue     =   $this->ObFieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
        $ecr            =   $this->ObecrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId),'group'=>'Label','order'=>array('Label'=>'asc')));	
        
        $this->set('fieldName',$fieldName);
        $this->set('fieldValue',$fieldValue);
        $this->set('ecr',$ecr);
    }
    
    
    public function download() {
        $this->layout='ajax';
        if($this->request->is("POST")){
            $ClientId       =   $this->Session->read('companyid');
            $search         =   $this->request->data['ObAttemptwiseReports'];
            $CampaignId     =   $search['CampaignName'];
            $AllocationId   =   $search['AllocationName'];
            $startdate      =   date("Y-m-d",strtotime($search['startdate'])); 
            $enddate        =   date("Y-m-d",strtotime($search['enddate'])); 
            $WhereDate      =   "";
            
            if($search['startdate'] !="" && $search['enddate'] !=""){
                $WhereDate  =" AND DATE(CallDate) BETWEEN '$startdate' AND '$enddate'";
            }
            
            header("Content-Type: application/vnd.ms-excel; name='excel'");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=out_call_attempt_wise_report.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
           
            $total_count            =   $this->campaign_count_field($AllocationId,$ClientId);
            $category_field         =   $this->get_category_field($ClientId,$CampaignId);
            $category_field1        =   $this->get_category_field_header($ClientId,$CampaignId);
            $capcture_field         =   $this->get_chapcher_field($ClientId,$CampaignId);
            $callmaster_field       =   $this->get_field_number($ClientId,$CampaignId);
            $close_capcture_field   =   $this->get_close_chapcher_field($ClientId,$CampaignId);
            $close_callmaster_field =   $this->get_close_field_number($ClientId,$CampaignId);
            $cmp_field              =   $this->get_fields($total_count,"Field");
            $campaign_field         =   $this->get_campaign_fields($CampaignId,$ClientId,$cmp_field);
            $allocation_field       =   $this->get_fields($total_count,"Field");
            
            $header_field           =   array_merge(array('S.No','Campaign Name','Allocation Name'),$campaign_field,$capcture_field,array('Fresh Attempt Status','Second Attempt Status','Third Attempt Status','Rescheduled Date'));
            $AllocationString       =   implode(",",$AllocationId);
            $whereAllocation        =   "'".$AllocationString."'";
            
            $ImportDataArr          =   $this->CallMasterOut->query("SELECT * FROM ob_campaign_data WHERE AllocationId IN($whereAllocation)");
            $TaggingDataArr         =   $this->CallMasterOut->query("SELECT * FROM call_master_out WHERE AllocationId IN($whereAllocation) $WhereDate");
            
            $MaxAttempt         =   array();
            $AttemptContact     =   array();
            $AttemptFirst       =   array();
            $AttemptSecond      =   array();
            $AttemptThird       =   array();
            
            foreach($AllocationId as $val){
                $MaxAttemptCount    =   $this->CallMasterOut->query("SELECT MAX(AttemptStatus) MaxAttempt FROM call_master_out WHERE AllocationId='$val' $WhereDate");
                $MaxAttempt[$val]   =   $MaxAttemptCount[0][0]['MaxAttempt'];
            }
            
            foreach($TaggingDataArr as $row){
                 
                if($row['call_master_out']['Category1'] =="Contacted"){
                    $AttemptContact[$row['call_master_out']['DataId']]=$row['call_master_out'];
                }
                if($row['call_master_out']['AttemptStatus'] =="1"){
                    $AttemptFirst[$row['call_master_out']['DataId']]=$row['call_master_out']['Category1'];
                }
                if($row['call_master_out']['AttemptStatus'] =="2"){
                    $AttemptSecond[$row['call_master_out']['DataId']]=$row['call_master_out']['Category1'];
                }
                if($row['call_master_out']['AttemptStatus'] =="3"){
                    $AttemptThird[$row['call_master_out']['DataId']]=$row['call_master_out']['Category1'];
                } 
                if($row['call_master_out']['Category1'] =="Reschedule" && $row['call_master_out']['AttemptStatus'] ==$MaxAttempt[$row['call_master_out']['AllocationId']]){
                    $AttemptReschedule[$row['call_master_out']['DataId']]=$row['call_master_out']['RescheduleData'];
                } 
                
            }
            
            /*
            echo "<pre>";
            print_r($AttemptReschedule);
            echo "</pre>";
            */
            ?>

            <?php if(isset($ImportDataArr) && !empty($ImportDataArr)){?>
            <table cellspacing="0" border="1">
                <thead>
                    <tr>
                        <?php foreach($header_field as $hedrow){?>
                        <th><?php echo $hedrow;?></th>
                        <?php }?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i=1; 
                    foreach($ImportDataArr as $Improw){
                    $DataId             =   $Improw['ob_campaign_data']['id'];
                    $AlloId             =   $Improw['ob_campaign_data']['AllocationId'];
                    $CampaignName       =   $this->getCampaignName($AlloId,$ClientId);
                    $AllocationName     =   $this->getAllocationName($AlloId,$ClientId);
                    ?>
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td><?php echo $CampaignName;?></td>
                        <td><?php echo $AllocationName;?></td>
                        
                        <?php foreach($allocation_field as $impfield){?>
                        <td><?php echo $Improw['ob_campaign_data'][$impfield];?></td>
                        <?php }?>
                        
                        <?php foreach($callmaster_field as $capfield){?>
                        <td><?php echo $AttemptContact[$DataId][$capfield];?></td>
                        <?php }?>
                        
                        <td><?php echo $AttemptFirst[$DataId];?></td>
                        <td><?php echo $AttemptSecond[$DataId];?></td>
                        <td><?php echo $AttemptThird[$DataId];?></td>
                        <td><?php echo $AttemptReschedule[$DataId];?></td>
                    </tr>
                    <?php }?>
                </tbody> 
            </table>
            <?php }?>
            
        <?php   
        die;
        }	
    }
    
    public function getAllocationName($id,$ClientId){
        $data   = $this->ObAllocationMaster->find('first',array('fields'=>array('AllocationName'),'conditions'=>array('id'=>$id,'AllocationStatus'=>'A','ClientId'=>$ClientId)));
        return $data['ObAllocationMaster']['AllocationName'];
    }
    
    public function getCampaignName($id,$ClientId){
        $data       =   $this->ObAllocationMaster->find('first',array('fields'=>array('CampaignId'),'conditions'=>array('id'=>$id,'AllocationStatus'=>'A','ClientId'=>$ClientId)));
        $CampaignId =   $data['ObAllocationMaster']['CampaignId'];
        
        $data       =   $this->CampaignName->find('first',array('fields'=>array('CampaignName'),'conditions'=>array('id'=>$CampaignId,'CampaignStatus'=>'A','ClientId'=>$ClientId)));
        return $data['CampaignName']['CampaignName'];
    }
       
    public function get_type(){
        $this->layout='ajax';
        if($_REQUEST['campid']){
            $clientId=$this->Session->read('companyid');
            $this->set('type',$this->ObecrMaster->find('list',array('fields'=>array('ecrName','ecrName'),'conditions'=>array('CampaignId'=>$_REQUEST['campid'],'Client'=>$clientId,'Label'=>1),'group'=>'ecrName','order'=>array('ecrName'=>'asc'))));	    
        }
    }

    public function get_subtype(){
        $this->layout='ajax';
        if($_REQUEST['campid']){
            $clientId=$this->Session->read('companyid');
            $this->set('subtype',$this->ObecrMaster->find('list',array('fields'=>array('ecrName','ecrName'),'conditions'=>array('CampaignId'=>$_REQUEST['campid'],'Client'=>$clientId,'Label'=>2),'group'=>'ecrName','order'=>array('ecrName'=>'asc'))));	    
        }
    }
    
    public function getcampaignlist(){
        if($_REQUEST['camptype']){
            $clientId   =   $this->Session->read('companyid');
            $Campaign   =   $this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('CampaignParentName'=>$_REQUEST['camptype'],'ClientId'=>$clientId,'CampaignStatus'=>'A')));  
            if(!empty($Campaign)){
                echo "<option value=''>Select Campaign</option>";
                foreach ($Campaign as $key=>$val){
                    echo "<option value='$key'>$val</option>";
                }
            }
            else{
               echo "<option value=''>Select Campaign</option>"; 
            }
            die; 
        }
    }

    public function get_category_field($ClientId,$CampaignId){
		$ecr = $this->ObCatMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array(
		'Client'=>$ClientId,'CampaignId'=>$CampaignId),'group'=>'Label','order'=>array('Label'=>'asc')));
		$keys = array_keys($ecr);
		$catheader=array();
		for($i=0;$i<count($keys);$i++){
			$key = $keys[$i];
			$catheader[]="Category".$key;
		}
		return $catheader;
	}
        
    public function get_category_field_header($ClientId,$CampaignId){
		$ecr = $this->ObCatMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array(
		'Client'=>$ClientId,'CampaignId'=>$CampaignId),'group'=>'Label','order'=>array('Label'=>'asc')));
		$keys = array_keys($ecr);
		$catheader=array();
		for($i=0;$i<count($keys);$i++){
			$key = $keys[$i];
                        if($key ==1){
			$catheader[]="Scenarios";
                        }
                        else{
                            $catheader[]="Sub Scenarios ".$key=$key-1;
                        }
		}
		return $catheader;
	}
	
	public function get_chapcher_field($ClientId,$CampaignId){
		$fldheader=array();
		$fieldName = $this->ObField->find('all',array('fields'=>array('FieldName'),'conditions'=>array('ClientId'=>$ClientId,'CampaignId'=>$CampaignId,'FieldStatus'=>NULL),'order'=>array('Priority')));
		foreach($fieldName as $row){
			$fldheader[]=$row['ObField']['FieldName'];
		}
		return $fldheader;
	}
        
        public function get_close_chapcher_field($ClientId,$CampaignId){
		$fldheader=array();
		$fieldName = $this->ObCloseFieldData->find('all',array('fields'=>array('FieldName'),'conditions'=>array('ClientId'=>$ClientId,'CampaignId'=>$CampaignId,'FieldStatus'=>NULL),'order'=>array('Priority')));
		foreach($fieldName as $row){
			$fldheader[]=$row['ObCloseFieldData']['FieldName'];
		}
		return $fldheader;
	}

	public function get_fields($field,$type){
		$column=array();
		for($i=1;$i<=$field;$i++){
			$column[]=$type.$i;
		}
		return $column;
	}
       
        public function get_field_number($ClientId,$CampaignId){
		$fldheader=array();
		$fieldName = $this->ObField->find('all',array('fields'=>array('fieldNumber'),'conditions'=>array('ClientId'=>$ClientId,'CampaignId'=>$CampaignId,'FieldStatus'=>NULL),'order'=>array('Priority')));
		foreach($fieldName as $row){
			$fldheader[]="Field".$row['ObField']['fieldNumber'];
		} 
		return $fldheader;
	}
        
        public function get_close_field_number($ClientId,$CampaignId){
		$fldheader=array();
		$fieldName = $this->ObCloseFieldData->find('all',array('fields'=>array('fieldNumber'),'conditions'=>array('ClientId'=>$ClientId,'CampaignId'=>$CampaignId,'FieldStatus'=>NULL),'order'=>array('Priority')));
		foreach($fieldName as $row){
			$fldheader[]="CField".$row['ObCloseFieldData']['fieldNumber'];
		} 
		return $fldheader;
	}
        
	
	public function campaign_count_field($id,$ClientId){
		$allocation=$this->ObAllocationMaster->find('first',array('fields'=>array("TotalCount"),'conditions'=>array('ClientId'=>$ClientId,'id'=>$id)));
		$total=$allocation['ObAllocationMaster']['TotalCount'];
		return $total;
	}
	
	public function get_campaign_fields($id,$ClientId,$field){
		$Campaign =$this->CampaignName->find('first',array('fields'=>$field,'conditions'=>array('ClientId'=>$ClientId,'id'=>$id)));
		$fldheader=array();
		foreach($field as $row){
			$fldheader[]=$Campaign['CampaignName'][$row];
		}
		return $fldheader;
	}
    
	
}
?>