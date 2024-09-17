<?php
class AdminobsrDetailsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('UploadExistingBase','ClientCategory','FieldCreation','CloseLoopMaster','update_closeloop','CallMasterOut','ObecrMaster','ObfieldCreation','ObfieldValue','ObcallMasterout','RegistrationMaster','CampaignName','ObAllocationMaster','ObCatMaster','ObField');
 
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('get_allocation','download','get_chapcher_field','get_chapcher_field','get_fields','campaign_count_field','get_campaign_fields');
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }		
    }
	
    public function index() {
        $this->layout='adminlayout';
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
        $this->set('client',$client);
        $this->set('clientid',array());
        $ClientId = "4";
        
        
         if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $ClientId =$_REQUEST['id'];
            if($ClientId !=""){
            
            $Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId)));
            $this->set('Campaign',$Campaign);
            
            $this->set('Data',$this->ObecrMaster->query("SELECT CONCAT(GROUP_CONCAT(DISTINCT(Label)),',',GROUP_CONCAT(DISTINCT(FieldName))) CatField, ecrName FROM obecr_master t1 JOIN obfield_master t2 ON t1.Client=t2.ClientId WHERE t1.Client='$ClientId'"));


            $Datax = $this->ObecrMaster->query("SELECT GROUP_CONCAT(DISTINCT(CONCAT('Category',Label))) CatField,t2.FieldName FROM obecr_master t1 JOIN obfield_master t2 ON t1.Client=t2.ClientId WHERE t1.Client='$ClientId'");

            $field =$this->ObfieldCreation->find('list',array('fields'=>array("FieldName"),'conditions'=>array('ClientId'=>$ClientId)));
            $fieldArr=array();
            $i=1;
            foreach($field as $val){
                    $fieldArr[]="Field".$i;
                    $i++;
                    }

            if(!empty($fieldArr)){$field_Name=','.implode(",",$fieldArr);}else{$field_Name='';}
            if($Datax[0][0]['CatField'] !=''){$CatField =','.$Datax[0][0]['CatField'];}else{$CatField='';}

            $result=$this->ObcallMasterout->query("select SrNo,MSISDN $CatField $field_Name from call_master_out where ClientId=$ClientId");
            $this->set('data',$result);
            
            $this->set('clientid',$ClientId); 
            }
         }
         
         if($this->request->is('Post')){
            $data=$this->request->data['AdminobsrDetails'];
            $ClientId =$data['clientID'];
            if($ClientId !=""){
            $Campaign =$this->CampaignName->find('list',array('fields'=>array("id","CampaignName"),'conditions'=>array('ClientId'=>$ClientId)));
            $this->set('Campaign',$Campaign);
            
            $this->set('Data',$this->ObecrMaster->query("SELECT CONCAT(GROUP_CONCAT(DISTINCT(Label)),',',GROUP_CONCAT(DISTINCT(FieldName))) CatField, ecrName FROM obecr_master t1 JOIN obfield_master t2 ON t1.Client=t2.ClientId WHERE t1.Client='$ClientId'"));


            $Datax = $this->ObecrMaster->query("SELECT GROUP_CONCAT(DISTINCT(CONCAT('Category',Label))) CatField,t2.FieldName FROM obecr_master t1 JOIN obfield_master t2 ON t1.Client=t2.ClientId WHERE t1.Client='$ClientId'");

            $field =$this->ObfieldCreation->find('list',array('fields'=>array("FieldName"),'conditions'=>array('ClientId'=>$ClientId)));
            $fieldArr=array();
            $i=1;
            foreach($field as $val){
                    $fieldArr[]="Field".$i;
                    $i++;
                    }

            if(!empty($fieldArr)){$field_Name=','.implode(",",$fieldArr);}else{$field_Name='';}
            if($Datax[0][0]['CatField'] !=''){$CatField =','.$Datax[0][0]['CatField'];}else{$CatField='';}

            $result=$this->ObcallMasterout->query("select SrNo,MSISDN $CatField $field_Name from call_master_out where ClientId=$ClientId");
            $this->set('data',$result);
            
            $this->set('clientid',$ClientId);  
            }
         }

    }
    
    public function get_allocation(){
        $this->layout='ajax';
        if($_REQUEST['campid']){
            $clientid=$_REQUEST['cid'];
            $campaignid=$_REQUEST['campid'];
            $allocation=$this->ObAllocationMaster->find('list',array('fields'=>array("id","AllocationName"),'conditions'=>array('ClientId'=>$clientid,'CampaignId'=>$campaignid)));
            $this->set('allocations',$allocation);
        }
    }

	public function download() {
		$this->layout='ajax';
		if($this->request->is("POST")){
			$ClientId = $this->request->data['AdminobsrDetails']['cid'];
			$CampaignId=$this->request->data['AdminobsrDetails']['CampaignName'];
			$AllocationId=$this->request->data['AdminobsrDetails']['AllocationName'];
			$startdate=strtotime($this->request->data['AdminobsrDetails']['startdate']);
			$enddate=strtotime($this->request->data['AdminobsrDetails']['enddate']);
				
			if($enddate < $startdate){
				$this->Session->setFlash("Select valid date.");
				$this->redirect(array('action' => 'index'));
			}
			
			$total_count      = $this->campaign_count_field($AllocationId,$ClientId);
			$category_field   = $this->get_category_field($ClientId);
			$capcture_field   = $this->get_chapcher_field($ClientId);
			$callmaster_field = $this->get_fields(count($capcture_field),"Field");
			$cmp_field   	  = $this->get_fields($total_count,"Field");
			$campaign_field   = $this->get_campaign_fields($CampaignId,$ClientId,$cmp_field);
			$allocation_field = $this->get_fields($total_count,"obcd.Field");
			
			$header_field=array_merge(array('MSISDN'),$category_field,$capcture_field,array('CallDate'),$campaign_field);
			$values_field=array_merge(array('MSISDN'),$category_field,$callmaster_field,array('CallDate'),$allocation_field);

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
				'conditions' =>array(
					'date(CallMasterOut.CallDate) <=' => date("Y-m-d",$enddate), 
					'date(CallMasterOut.CallDate) >=' => date("Y-m-d",$startdate),
					'obcd.AllocationId' => $AllocationId,
				)
			));
                        
                    
			$this->set('header',$header_field);
			$this->set('Data',$tArr);
		}	
	}
		
		
	public function get_category_field($ClientId){
		$ecr = $this->ObCatMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array(
		'Client'=>$ClientId),'group'=>'Label','order'=>array('Label'=>'asc')));
		$keys = array_keys($ecr);
		$catheader=array();
		for($i=0;$i<count($keys);$i++){
			$key = $keys[$i];
			$catheader[]="Category".$key;
		}
		return $catheader;
	}
	
	public function get_chapcher_field($ClientId){
		$fldheader=array();
		$fieldName = $this->ObField->find('all',array('fields'=>array('FieldName'),'conditions'=>array('ClientId'=>$ClientId),'order'=>array('Priority')));
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