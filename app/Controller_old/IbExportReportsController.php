<?php
	class IbExportReportsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler','Session');
	public $uses=array('EcrMaster','FieldMaster','CallMaster','CloseFieldData');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('download');
    }
	
	public function index() {
		$this->layout='user';
	}
	
	public function download() {
            $this->layout='ajax';
            if($this->request->is("POST")){
                $ClientId = $this->Session->read('companyid');
                $search=$this->request->data['IbExportReports'];
              
                $startdate=strtotime($search['startdate']);
                $enddate=strtotime($search['enddate']);

                $data['ClientId']=$ClientId;
                $data['CallType !=']='Upload';
                
                if(isset($search['startdate']) && $search['startdate'] !=""){$data['date(CallDate) >=']=date("Y-m-d",$startdate);}else{unset($data['date(CallDate) >=']);}
                if(isset($search['enddate']) && $search['enddate'] !=""){$data['date(CallDate) <=']=date("Y-m-d",$enddate);}else{unset($data['date(CallDate) <=']);}
                if(isset($search['firstsr']) && $search['firstsr'] !=""){$data['SrNo >=']=$search['firstsr'];}else{unset($data['SrNo >=']);}
                if(isset($search['lastsr']) && $search['lastsr'] !=""){$data['SrNo <=']=$search['lastsr'];}else{unset($data['SrNo <=']);}
                if(isset($search['MSISDN']) && $search['MSISDN'] !=""){$data['MSISDN']=$search['MSISDN'];}else{unset($data['MSISDN']);}
                if(isset($search['Category1']) && $search['Category1'] !=""){$data['Category1']=$search['Category1'];}else{unset($data['Category1']);}
                if(isset($search['Category2']) && $search['Category2'] !=""){$data['Category2']=$search['Category2'];}else{unset($data['Category2']);}
                if(isset($search['Category3']) && $search['Category3'] !=""){$data['Category3']=$search['Category3'];}else{unset($data['Category3']);}
                if(isset($search['Category4']) && $search['Category4'] !=""){$data['Category4']=$search['Category4'];}else{unset($data['Category4']);}
                if(isset($search['Category5']) && $search['Category5'] !=""){$data['Category5']=$search['Category5'];}else{unset($data['Category5']);}
                
                if(isset($search['Category1']) && $search['Category1'] ==="All"){unset($data['Category1']);}
                if(isset($search['Category2']) && $search['Category2'] ==="All"){unset($data['Category2']);}
                if(isset($search['Category3']) && $search['Category3'] ==="All"){unset($data['Category3']);}
                if(isset($search['Category4']) && $search['Category4'] ==="All"){unset($data['Category4']);}
                if(isset($search['Category5']) && $search['Category5'] ==="All"){unset($data['Category5']);}
                
                if(isset($search['CloseLoopCate1']) && $search['CloseLoopCate1'] !=""){$data['CloseLoopCate1']=$search['CloseLoopCate1']=='0'?null:$search['CloseLoopCate1'];}else{unset($data['CloseLoopCate1']);}
   
                $category_field   = $this->get_category_field($ClientId);
                $category_field1   = $this->get_category_field_header($ClientId);
                $capcture_field   = $this->get_chapcher_field($ClientId);
                $callmaster_field = $this->get_chapcher_field2($ClientId);
                
                $capcture_field1   = $this->get_close_field($ClientId);
                $callmaster_field1 = $this->get_close_field2($ClientId);
                
                $header_field=array_merge(array('IN CALL FROM','Call Id'),$category_field1,$capcture_field,array('CallDate','Call Action','Call Sub Action','Call Action Remarks','Closer Date','Follow Up Date','TAT','Due Date','Call Created','Call Status'),$capcture_field1);
                $values_field=array_merge(array('MSISDN','SrNo'),$category_field,$callmaster_field,array('CallDate','CloseLoopCate1','CloseLoopCate2','closelooping_remarks','CloseLoopingDate','FollowupDate','tat','duedate','callcreated','AbandStatus'),$callmaster_field1);
                
                
               

                $tArr = $this->CallMaster->find('all',array('fields'=>$values_field,'conditions' =>$data));
                
                $this->set('header',$header_field);
                $this->set('Data',$tArr);
            }
	}
        
        
        public function get_close_field($ClientId){
		$fldheader=array();
		$fieldName = $this->CloseFieldData->find('all',array('fields'=>array('FieldName'),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
		foreach($fieldName as $row){
			$fldheader[]=$row['CloseFieldData']['FieldName'];
		}
		return $fldheader;
	}
        
        public function get_close_field2($ClientId){
		$fldheader=array();
		$fieldName = $this->CloseFieldData->find('all',array('fields'=>array('fieldNumber'),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
		foreach($fieldName as $row){
			$fldheader[]='CField'.$row['CloseFieldData']['fieldNumber'];
		}
		return $fldheader;
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
        
        public function get_category_field_header($ClientId){
		$ecr = $this->EcrMaster->find('list',array('fields'=>array('label','ecr'),'conditions'=>array(
		'Client'=>$ClientId),'group'=>'Label','order'=>array('Label'=>'asc')));
		$keys = array_keys($ecr);
		$catheader=array();
		for($i=0;$i<count($keys);$i++){
			$key = $keys[$i];
                        if($key ==1){
                            $catheader[]="SCENARIO";
                        }
                        else{
                            $j=$key-1;
                           $catheader[]="SUB SCENARIO ".$j; 
                        }
		}
		return $catheader;
	}
	
	public function get_chapcher_field($ClientId){
		$fldheader=array();
		$fieldName = $this->FieldMaster->find('all',array('fields'=>array('FieldName'),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
		foreach($fieldName as $row){
			$fldheader[]=$row['FieldMaster']['FieldName'];
		}
		return $fldheader;
	}
	public function get_chapcher_field2($ClientId){
		$fldheader=array();
		$fieldName = $this->FieldMaster->find('all',array('fields'=>array('fieldNumber'),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
		foreach($fieldName as $row){
			$fldheader[]='Field'.$row['FieldMaster']['fieldNumber'];
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