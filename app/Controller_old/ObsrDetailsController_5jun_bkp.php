<?php
class ObsrDetailsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('CampaignName','ObecrMaster','ObField','ObfieldValue','ObcallMasterout','CallMasterOut','ObCatMaster','ObField','ObAllocationMaster','CampaignName');

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
        
        $Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$clientId)));
        $this->set('Campaign',$Campaign);
                
        $fieldName = $this->ObField->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
        $fieldValue = $this->ObfieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
        $ecr = $this->ObecrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId),'group'=>'Label','order'=>array('Label'=>'asc')));	
        
        $this->set('fieldName',$fieldName);
        $this->set('fieldValue',$fieldValue);
        $this->set('ecr',$ecr);
        
        
        
        if($this->request->is("POST")){
            $ClientId = $this->Session->read('companyid');
            $search=$this->request->data['ObExportReports'];

            $CampaignId=$search['CampaignName'];
            $AllocationId=$search['AllocationName'];

            $startdate=strtotime($search['startdate']);
            $enddate=strtotime($search['enddate']);

            $data['ClientId']=$ClientId;
            if(isset($search['startdate']) && $search['startdate'] !=""){$data['date(CallMasterOut.CallDate) >=']=date("Y-m-d",$startdate);}else{unset($data['date(CallMasterOut.CallDate) >=']);}
            if(isset($search['enddate']) && $search['enddate'] !=""){$data['date(CallMasterOut.CallDate) <=']=date("Y-m-d",$enddate);}else{unset($data['date(CallMasterOut.CallDate) <=']);}
            if(isset($search['firstsr']) && $search['firstsr'] !=""){$data['CallMasterOut.SrNo >=']=$search['firstsr'];}else{unset($data['CallMasterOut.SrNo >=']);}
            if(isset($search['lastsr']) && $search['lastsr'] !=""){$data['CallMasterOut.SrNo <=']=$search['lastsr'];}else{unset($data['CallMasterOut.SrNo <=']);}
            if(isset($search['MSISDN']) && $search['MSISDN'] !=""){$data['CallMasterOut.MSISDN']=$search['MSISDN'];}else{unset($data['CallMasterOut.MSISDN']);}
            if(isset($search['Category1']) && $search['Category1'] !=""){$data['CallMasterOut.Category1']=$search['Category1'];}else{unset($data['CallMasterOut.Category1']);}
            if(isset($search['Category2']) && $search['Category2'] !=""){$data['CallMasterOut.Category2']=$search['Category2'];}else{unset($data['CallMasterOut.Category2']);}
            if(isset($search['AllocationName']) && $search['AllocationName'] !=""){$data['CallMasterOut.AllocationId']=$search['AllocationName'];}else{unset($data['CallMasterOut.AllocationId']);}

            $total_count      = $this->campaign_count_field($AllocationId,$ClientId);
            $category_field   = $this->get_category_field($ClientId,$CampaignId);
            $category_field1   = $this->get_category_field_header($ClientId,$CampaignId);
            $capcture_field   = $this->get_chapcher_field($ClientId,$CampaignId);
            
            //$callmaster_field = $this->get_fields(count($capcture_field),"Field");
            $callmaster_field = $this->get_field_number($ClientId,$CampaignId);
            
            $cmp_field   	  = $this->get_fields($total_count,"Field");
            $campaign_field   = $this->get_campaign_fields($CampaignId,$ClientId,$cmp_field);
            $allocation_field = $this->get_fields($total_count,"obcd.Field");

            $header_field=array_merge(array('Call From'),$category_field1,$capcture_field,array('CallDate','Call Created'),$campaign_field);
            $values_field=array_merge(array('MSISDN'),$category_field,$callmaster_field,array('CallDate','callcreated'),$allocation_field);


            $tArr = $this->CallMasterOut->find('all',array(
                    'fields'=>$values_field,
                    'joins' => array(
                                                    array(
                                                            'table' => 'ob_campaign_data',
                                                            'type'=>'Left',
                                                            'alias'=>'obcd',
                                                            'conditions'=>array("CallMasterOut.DataId=obcd.id"),
                                                            //'group' => array('ObAllocationMaster.id'),
                                                    ),			
                                            ),
                    'conditions' =>$data
            ));
            

            $this->set('header',$header_field);
            $this->set('Data',$tArr);
            $this->set('AllocationId',$AllocationId);
        }	
        
        
        
        
        
        //$history = $this->ObcallMasterout->find('all',array('conditions'=>array('ClientId'=>$clientId),'order'=>array('CallDate'=>'desc')));	
	//$this->set('history',$history);	
    }
    
    public function view_details() {
        if(isset($_REQUEST['srno'])){
            $this->layout='ajax';
            $clientId = $this->Session->read('companyid');
            $srno=$_REQUEST['srno'];

            $fieldName = $this->ObField->find('all',array('conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
            $fieldValue = $this->ObfieldValue->find('list',array('fields'=>array('FieldId',"value"),'conditions'=>array('ClientId'=>$clientId,'FieldStatus'=>NULL),'group'=>'FieldId'));
            $ecr = $this->ObecrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array('Client'=>$clientId),'group'=>'Label','order'=>array('Label'=>'asc')));	
        
            $this->set('fieldName',$fieldName);
            $this->set('fieldValue',$fieldValue);
            $this->set('ecr',$ecr);
            $history = $this->ObcallMasterout->find('first',array('conditions'=>array('SrNo'=>$srno,'ClientId'=>$clientId),'order'=>array('CallDate'=>'desc')));	
            $this->set('history',$history);	
        }	
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