<?php
class AdminsrDetailsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');
    public $uses=array('UploadExistingBase','ClientCategory','FieldCreation','CloseLoopMaster','update_closeloop','RegistrationMaster','EcrMaster','FieldMaster','CallMaster');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('view_details','update_closeloop','download','get_category_field','get_chapcher_field','get_fields');
        if(!$this->Session->check("admin_id")){
            return $this->redirect(array('controller'=>'Admins','action' => 'index'));
        }		
    }
    
    public function index() {
        $this->layout='adminlayout';
        $client =$this->RegistrationMaster->find('list',array('fields'=>array("company_id","Company_name")));
        $this->set('client',$client);
        $this->set('clientid',array());
                
        if(isset($_REQUEST['id']) && $_REQUEST['id'] !=""){
            $ClientId =$_REQUEST['id'];
            
            if($ClientId !=""){
            $this->set('Data',$this->ClientCategory->query("SELECT CONCAT(GROUP_CONCAT(DISTINCT(Label)),',',GROUP_CONCAT(DISTINCT(FieldName))) CatField, ecrName
FROM ecr_master t1 JOIN field_master t2 ON t1.Client=t2.ClientId WHERE t1.Client='$ClientId'"))	;


		$Datax = $this->ClientCategory->query("SELECT GROUP_CONCAT(DISTINCT(CONCAT('Category',Label))) CatField,t2.FieldName FROM ecr_master t1 JOIN field_master t2 ON t1.Client=t2.ClientId WHERE t1.Client='$ClientId'")	;
	
		$field =$this->FieldCreation->find('list',array('fields'=>array("FieldName"),'conditions'=>array('ClientId'=>$ClientId)));
		$fieldArr=array();
		$i=1;
		foreach($field as $val){
			$fieldArr[]="Field".$i;
			$i++;
			}
			
		if(!empty($fieldArr)){$field_Name=','.implode(",",$fieldArr);}else{$field_Name='';}
		if($Datax[0][0]['CatField'] !=''){$CatField =','.$Datax[0][0]['CatField'];}else{$CatField='';}
	
		$result=$this->UploadExistingBase->query("select SrNo,MSISDN $CatField $field_Name from call_master where ClientId=$ClientId");
		$this->set('data',$result);
            
            $this->set('clientid',$ClientId); 
            }
        }
        
        if($this->request->is('Post')){
            $data=$this->request->data['AdminsrDetails'];
            $ClientId =$data['clientID'];
            
            if($ClientId !=""){
            $this->set('Data',$this->ClientCategory->query("SELECT CONCAT(GROUP_CONCAT(DISTINCT(Label)),',',GROUP_CONCAT(DISTINCT(FieldName))) CatField, ecrName
FROM ecr_master t1 JOIN field_master t2 ON t1.Client=t2.ClientId WHERE t1.Client='$ClientId'"))	;


		$Datax = $this->ClientCategory->query("SELECT GROUP_CONCAT(DISTINCT(CONCAT('Category',Label))) CatField,t2.FieldName FROM ecr_master t1 JOIN field_master t2 ON t1.Client=t2.ClientId WHERE t1.Client='$ClientId'")	;
	
		$field =$this->FieldCreation->find('list',array('fields'=>array("FieldName"),'conditions'=>array('ClientId'=>$ClientId)));
		$fieldArr=array();
		$i=1;
		foreach($field as $val){
			$fieldArr[]="Field".$i;
			$i++;
			}
			
		if(!empty($fieldArr)){$field_Name=','.implode(",",$fieldArr);}else{$field_Name='';}
		if($Datax[0][0]['CatField'] !=''){$CatField =','.$Datax[0][0]['CatField'];}else{$CatField='';}
	
		$result=$this->UploadExistingBase->query("select SrNo,MSISDN $CatField $field_Name from call_master where ClientId=$ClientId");
		$this->set('data',$result);
            
            $this->set('clientid',$ClientId);  
            }
        }  
                	
    }
        
	public function view_details() {
		if(isset($_REQUEST['srno'])){
		$this->layout='ajax';
		$ClientId =$_REQUEST['cid'];
		$srno=$_REQUEST['srno'];

		$this->set('Data',$this->ClientCategory->query("SELECT CONCAT(GROUP_CONCAT(DISTINCT(Label)),',',GROUP_CONCAT(DISTINCT(FieldName))) CatField, ecrName
FROM ecr_master t1 JOIN field_master t2 ON t1.Client=t2.ClientId WHERE t1.Client='$ClientId'"))	;


		$Datax = $this->ClientCategory->query("SELECT GROUP_CONCAT(DISTINCT(CONCAT('Category',Label))) CatField,t2.FieldName FROM ecr_master t1 JOIN field_master t2 ON t1.Client=t2.ClientId WHERE t1.Client='$ClientId'")	;
	
		$field =$this->FieldCreation->find('list',array('fields'=>array("FieldName"),'conditions'=>array('ClientId'=>$ClientId)));
		$fieldArr=array();
		$fieldDetails=array();
		$i=1;
		foreach($field as $val){
			$fieldArr[]="Field".$i;
			$i++;
			}
		$field_Name=implode(",",$fieldArr);
		$CatField = $Datax[0][0]['CatField'];
	
		$result=$this->UploadExistingBase->query("select SrNo,MSISDN,$CatField,$field_Name from call_master where ClientId=$ClientId and SrNo=$srno");
		$c=0;
		foreach($result[0]['call_master'] as $row){
			$fieldDetails[$c]=$row;
			$c++;
		}
		$clcat =$this->CloseLoopMaster->find('list',array('fields'=>array("id","close_loop_category"),'conditions'=>array('parent_id'=>0,'client_id'=>$ClientId)));
		
		
		$catData=$this->UploadExistingBase->find('first',array('fields'=>array('Category1','Category2'),
		'conditions'=>array('SrNo'=>$srno,'ClientId'=>$ClientId)));
		
		$c1=$catData['UploadExistingBase']['Category1'];
		$c2=$catData['UploadExistingBase']['Category2'];
		
		$closeloop=$this->CloseLoopMaster->find('all',array('fields'=>array('id','close_loop_category','parent_id'),
		'conditions'=>array('Category1'=>$c1,'Category2'=>$c2,'client_id'=>$ClientId)));
		
		foreach($closeloop as $closelooparr){
		
		$close_pid=$closelooparr['CloseLoopMaster']['parent_id'];
		
		if(isset($close_pid) && $close_pid !=0){
			$p_name=$this->CloseLoopMaster->query("select close_loop_category from closeloop_master where id=$close_pid");
			$parentname=$p_name[0]['closeloop_master']['close_loop_category'];
			$childname=$closelooparr['CloseLoopMaster']['close_loop_category'];
		}

		else{
			$parentname=$closelooparr['CloseLoopMaster']['close_loop_category'];
			$childname="";
		}
		}
		$closeCatArr=array();
		
		$closeCatArr[]=array('close_cat1'=>isset ($parentname) ? $parentname : "",'close_cat2'=>isset ($childname) ? $childname : "",'srno'=>$srno);
		
		$this->set('clcat',$clcat);
		$this->set('data',$fieldDetails);
		$this->set('close_category',$closeCatArr);
                $this->set('cid',$_REQUEST['cid']);
                
		}
		
	}
	
	public function update_closeloop(){
		if(isset($_REQUEST['srno'])){
			$srno=$_REQUEST['srno'];
			$close_cat1=$_REQUEST['close_cat1'];
			$close_cat2=$_REQUEST['close_cat2'];
			$ClientId=$_REQUEST['cid'];
                        
			if($close_cat2 !=''){
				$data=array(
					'CloseLoopCate1'=>"'".$close_cat1."'",
					'CloseLoopCate2'=>"'".$close_cat2."'",
					'UpdateDate'=>"'".$date = date('Y-m-d H:i:s')."'"
					);
			}
			else{
				$data=array(
					'CloseLoopCate1'=>"'".$close_cat1."'",
					'UpdateDate'=>"'".$date = date('Y-m-d H:i:s')."'"
					);
			}
				
			if($this->UploadExistingBase->updateAll($data,array('SrNo'=>$srno,'ClientId' =>$ClientId))){
				echo "1";die;
			}else{
				echo "";die;
			}
			
		}
	}
        
        public function download() {
		$this->layout='ajax';
		if($this->request->is("POST")){
			
			$startdate=strtotime($this->request->data['AdminsrDetails']['startdate']);
			$enddate=strtotime($this->request->data['AdminsrDetails']['enddate']);
			$ClientId = $this->request->data['AdminsrDetails']['cid'];	
			if($enddate < $startdate){
				$this->Session->setFlash("Select valid date.");
				$this->redirect(array('action' => 'index','?'=>array('id'=>$ClientId)));
			}
			
			$category_field   = $this->get_category_field($ClientId);
			$capcture_field   = $this->get_chapcher_field($ClientId);
			$callmaster_field = $this->get_fields(count($capcture_field),"Field");
			$header_field=array_merge(array('MSISDN'),$category_field,$capcture_field,array('CallDate'));
			$values_field=array_merge(array('MSISDN'),$category_field,$callmaster_field,array('CallDate'));
			
			$tArr = $this->CallMaster->find('all',array(
				'fields'=>$values_field,
				'conditions' =>array(
					'date(CallDate) <=' => date("Y-m-d",$enddate), 
					'date(CallDate) >=' => date("Y-m-d",$startdate),
					'ClientId' => $ClientId)
			));
			
			$this->set('header',$header_field);
			$this->set('Data',$tArr);
		}	
	}
		
		
	public function get_category_field($ClientId){
		$ecr = $this->EcrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array(
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
		$fieldName = $this->FieldMaster->find('all',array('fields'=>array('FieldName'),'conditions'=>array('ClientId'=>$ClientId),'order'=>array('Priority')));
		foreach($fieldName as $row){
			$fldheader[]=$row['FieldMaster']['FieldName'];
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
	
	
	
}
?>