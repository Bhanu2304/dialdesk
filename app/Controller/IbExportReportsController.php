<?php
	class IbExportReportsController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler','Session');
	public $uses=array('EcrMaster','FieldMaster','CallMaster','CloseFieldData');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('download','downloadcsv');
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
                //print_r($callmaster_field);exit;
                $capcture_field1   = $this->get_close_field($ClientId);
                $callmaster_field1 = $this->get_close_field2($ClientId);
                
                $header_field=array_merge(array('IN CALL FROM','Call Id'),$category_field1,$capcture_field,array('CallDate','Call Action','Call Sub Action','Call Action Remarks','Closure Date','Follow Up Date','Case Closed By','TAT','Due Date','Call Created','Call Status'),$capcture_field1);
                $values_field=array_merge(array('MSISDN','SrNo'),$category_field,$callmaster_field,array('CallDate','CloseLoopCate1','CloseLoopCate2','closelooping_remarks','CloseLoopingDate','FollowupDate','CaseCloseBy','tat','duedate','callcreated','AbandStatus'),$callmaster_field1);
                
                
                
                if($ClientId=='364')
                {
                    $header_field_new=array_merge(
                            array('IN CALL FROM'=>'IN CALL FROM','Call Id'=>'Call Id'),
                            array('SCENARIO'=>'SCENARIO','SUB SCENARIO'=>'SUB SCENARIO 1','Name'=>'Name','Phone Number'=>'Phone Number','E-mail Address'=>'E-mail Address','Skin type'=>'Skin type','Age (Yrs)'=>'Age (Yrs)','Environment'=>'Environment','Name of Product-1'=>'Product',
                                'Name of Product-2'=>'Product',
                                'Name of Product-3'=>'Product',
                                'Location'=>'Location','Customer VOC'=>'Customer VOC','Agent VOC'=>'Agent VOC'),
                            array('CallDate'=>'CallDate','Call Closure Date'=>'Call Closure Date with time','Call Attend By'=>'Call Created','Call Status'=>'Call Status'),
                            array('HOD Remarks'=>'HOD Remarks','Follow up call date'=>'Followup Date','follow up remarks'=>'Follow DD Remarks','Follow up call Closure date'=>'Closure Time','Case Closed By'=>'Call Created','Call Created'=>'CallDate'));
                    
                    $values_field_new = array();
                    $Field30 = 30;
                        foreach($header_field_new as $head)
                        {
                            if(in_array($head,$header_field))
                            {
                                $index = array_search($head,$header_field);
                                $values_field_new[] = $values_field[$index];
                            }
                            else
                            {
                                $values_field_new[] = 'Field'.$Field30;
                                $Field30++;
                            }
                        }
                        //$values_field_new[] = 'CallDate';
                        $values_field = $values_field_new;
                }
                
                //$values_field_n = implode(",",$values_field);
                
                //print_r($values_field); exit; 
                
                $tArr = $this->CallMaster->find('all',array('fields'=>$values_field,'conditions' =>$data));
                
                $this->set('ClientId',$ClientId);
                $this->set('header_field_new',$header_field_new);
                $this->set('header',$header_field);
                $this->set('Data',$tArr);
                $this->set('showVal',$search);
            }
	}
     
    public function downloadcsv() {
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
   
                
                
                $header_field=array('Id','Client Id','Sr No','MSISDN','status','remarks');
                $values_field=array('Id','ClientId','SrNo','MSISDN');
                
                
                $delimiter = ',';
                $enclosure = '"';

                $list[] = $header_field;
                $Data = $this->CallMaster->find('all',array('fields'=>$values_field,'conditions' =>$data));
                
                foreach($Data as $head)
                {
                    $val = array();
                    foreach($head['CallMaster'] as $row){
                        $val[] = $row;
                    }
                    $list[] = $val;
                }

                header("Content-type: text/csv");
                header("Content-Disposition: attachment; filename=incall_closeloop_det.csv");
                header("Pragma: no-cache");
                header("Expires: 0");

                foreach ($list as $row) {
                    echo implode($delimiter, $row) . "\r\n";
                }

                exit; 
                //fclose($fp);
                //$values_field_n = implode(",",$values_field);
                
                //print_r($values_field); exit; 
                
                
                
                $this->set('ClientId',$ClientId);
                $this->set('header_field_new',$header_field_new);
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
            
            //echo "page is under maintanance. <br/> ";
		$fldheader=array();
		$fieldName = $this->FieldMaster->find('all',array('fields'=>array('fieldNumber','FieldValidation'),'conditions'=>array('ClientId'=>$ClientId,'FieldStatus'=>NULL),'order'=>array('Priority')));
		foreach($fieldName as $row){
			
                        if(strtolower($row['FieldMaster']['FieldValidation'])=='numeric_2')
                        {
                            $fld = 'Field'.$row['FieldMaster']['fieldNumber'];
                            $fldheader[]='CONCAT("'."'".'",'.$fld.') as '.$fld;
                        }
                        else
                        {
                            $fldheader[]='Field'.$row['FieldMaster']['fieldNumber'];
                        }
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