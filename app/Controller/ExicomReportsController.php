<?php
class ExicomReportsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses=array('EcrMaster','FieldMaster','CallMaster','CloseFieldData','FecreationMaster','ExicomTaggingMaster');
    
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid")){
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='user';
           
        $this->set('received',$this->ExicomTaggingMaster->find('count',array('conditions' =>array('date(CallDate)'=>date('Y-m-d')))));
        $this->set('pendings',$this->ExicomTaggingMaster->find('count',array('conditions' =>array('date(CallDate)'=>date('Y-m-d'),'Field45'=>NULL))));
        
        if($this->request->is("POST")){
            
            $ClientId   =   $this->Session->read('companyid');
            
            
            
            if($ClientId =="326"){
                
                
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=export.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                
                
                $search             =   $this->request->data['ExicomReports'];
                $startdate          =   strtotime($search['startdate']);
                $enddate            =   strtotime($search['enddate']);
                $data['ClientId']   =   $ClientId;

                if(isset($search['startdate']) && $search['startdate'] !=""){$data['date(CallDate) >=']=date("Y-m-d",$startdate);}else{unset($data['date(CallDate) >=']);}
                if(isset($search['enddate']) && $search['enddate'] !=""){$data['date(CallDate) <=']=date("Y-m-d",$enddate);}else{unset($data['date(CallDate) <=']);}
                $values_field=array('SrNo','CallDate','Field1','Field2','Field12','Field9','Field10','Field41','Field42','Field43','Field44');
                $tArr               =   $this->ExicomTaggingMaster->find('all',array('fields'=>$values_field,'conditions' =>$data));
                ?>

                <table border="1">
                    <tr>
                        <th>Reference ID</th>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>Customer Mobile Number</th>
                        <th>Company name</th>
                        <th>Side ID</th>
                        <th>Asset serial Number</th>
                        <th>Feedback received from Field</th>
                        <th>VOC</th>
                        <th>Remarks</th>
                        <th>Date of Feedack received from Field</th>
                    </tr>
                    <?php foreach($tArr as $data){?>
                    
                    <tr>
                        <td><?php echo $data['ExicomTaggingMaster']['SrNo'];?></td>
                        <td><?php echo $data['ExicomTaggingMaster']['CallDate'] !=""? date("d-m-Y H:i:s",strtotime($data['ExicomTaggingMaster']['CallDate'])):"" ;?></td>
                        <td><?php echo $data['ExicomTaggingMaster']['Field1'];?></td>
                        <td><?php echo $data['ExicomTaggingMaster']['Field2'];?></td>
                        <td><?php echo $data['ExicomTaggingMaster']['Field12'];?></td>
                        <td><?php echo $data['ExicomTaggingMaster']['Field9'];?></td>
                        <td><?php echo $data['ExicomTaggingMaster']['Field10'];?></td>
                        <td><?php echo $data['ExicomTaggingMaster']['Field41'];?></td>
                        <td><?php echo $data['ExicomTaggingMaster']['Field42'];?></td>
                        <td><?php echo $data['ExicomTaggingMaster']['Field43'];?></td>
                        <td><?php echo $data['ExicomTaggingMaster']['Field44'] !=""? date("d-m-Y H:i:s",strtotime($data['ExicomTaggingMaster']['Field44'])):"" ;?></td>
                    </tr>
                    <?php }?>
                </table>
                <?php
                die;
            }
            else{
                return $this->redirect(array('controller'=>'ExicomReports','action' => 'index')); 
            }  
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