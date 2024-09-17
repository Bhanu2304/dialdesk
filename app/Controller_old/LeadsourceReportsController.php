<?php
class LeadsourceReportsController extends AppController{
    public $helpers = array('Html', 'Form','Js');
    public $components = array('RequestHandler');  
    public $uses=array('EcrMaster','FieldMaster','CallMaster','CloseFieldData');
    
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow();
        if(!$this->Session->check("companyid")){
            return $this->redirect(array('controller'=>'ClientActivations','action' => 'login'));
        }
    }
    
    public function index(){
        $this->layout='user';
        if($this->request->is("POST")){
            
            $ClientId   =   $this->Session->read('companyid');
            
            if($ClientId =="325"){
                
                header("Content-Type: application/vnd.ms-excel; name='excel'");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=import_format.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                
                
                $search             =   $this->request->data['LeadsourceReports'];
                $startdate          =   strtotime($search['startdate']);
                $enddate            =   strtotime($search['enddate']);
                $data['ClientId']   =   $ClientId;

                if(isset($search['startdate']) && $search['startdate'] !=""){$data['date(CallDate) >=']=date("Y-m-d",$startdate);}else{unset($data['date(CallDate) >=']);}
                if(isset($search['enddate']) && $search['enddate'] !=""){$data['date(CallDate) <=']=date("Y-m-d",$enddate);}else{unset($data['date(CallDate) <=']);}
                 
                $category_field     =   $this->get_category_field($ClientId);
                $category_field1    =   $this->get_category_field_header($ClientId);
                $capcture_field     =   $this->get_chapcher_field($ClientId);
                $callmaster_field   =   $this->get_chapcher_field2($ClientId);
                $capcture_field1    =   $this->get_close_field($ClientId);
                $callmaster_field1  =   $this->get_close_field2($ClientId);
                
                $header_field       =   array_merge(array('Call Id'),$category_field1,$capcture_field,array('CallDate','Call Action','Call Sub Action','Call Action Remarks','Closer Date','Follow Up Date','TAT','Due Date','Call Created','Call Status'),$capcture_field1);
                $values_field       =   array_merge(array('OtherId','SrNo'),$category_field,$callmaster_field,array('CallDate','CloseLoopCate1','CloseLoopCate2','closelooping_remarks','CloseLoopingDate','FollowupDate','tat','duedate','callcreated','AbandStatus'),$callmaster_field1);
                $tArr               =   $this->CallMaster->find('all',array('fields'=>$values_field,'conditions' =>$data));
                ?>

                <table border="1">
                    <tr>
                        <th>S.No</th>
                        <th>Unique Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>LeadSource</th>
                        <th>SzoSource</th>
                        <th>Date</th>
                        <?php $i=1; foreach($header_field as $hedrow){ ?><th><?php echo $hedrow;?></th><?php }?>
                        <th>Closer Time</th>
                    </tr>
                    <?php 
                    foreach($tArr as $head){
                        $Id         =   $head['CallMaster']['OtherId'];
                        $ArrData    =   $this->CallMaster->query("SELECT * FROM `AnandHearingApiData` WHERE ClientId='$ClientId' AND Id='$Id'");   
                    ?>
                    
                    <tr>
                        <td><?php echo $i++;?></td>
                        <td><?php echo $ArrData[0]['AnandHearingApiData']['cfhID'];?></td>
                        <td><?php echo $ArrData[0]['AnandHearingApiData']['Name'];?></td>
                        <td><?php echo $ArrData[0]['AnandHearingApiData']['Email'];?></td>
                        <td><?php echo $ArrData[0]['AnandHearingApiData']['Phone'];?></td>
                        <td><?php echo $ArrData[0]['AnandHearingApiData']['City'];?></td>
                        <td><?php echo $ArrData[0]['AnandHearingApiData']['LeadSource'];?></td>
                        <td><?php echo $ArrData[0]['AnandHearingApiData']['SzoSource'];?></td>
                        <td><?php echo $ArrData[0]['AnandHearingApiData']['CustDate'];?></td>
                        <?php foreach($head['CallMaster'] as $key=>$row){ if($key !="OtherId"){?><td><?php  echo $row;?></td><?php }}?>
                        <?php
                        if($head['CallMaster']['CloseLoopingDate'] !=""){$cld=$head['CallMaster']['CloseLoopingDate'];}else{$cld="";}                      
                        if($cld !=""){
                            $t1 = StrToTime ($cld);
                            $t2 = StrToTime ($head['CallMaster']['CallDate']);
                            $diff = $t1 - $t2;
                            $hours = $diff / ( 60 * 60 );
                        }
                        else{
                            $hours="";  
                        }
                        echo "<td>".round($hours)."</td>";     
                        ?>              
                    </tr>
                   
                    <?php }?>
                </table>
                <?php
                die;
            }
            else{
                return $this->redirect(array('controller'=>'LeadsourceReports','action' => 'index')); 
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